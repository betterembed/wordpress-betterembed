<?php


namespace BetterEmbed\WordPress\Storage;


use BetterEmbed\WordPress\Api\Api;
use BetterEmbed\WordPress\Model\Item;
use WP_Query;

class PostTypeCache implements Storage {


	/** @var Api */
	protected $api;

	public function __construct( Api $api ) {
		$this->api = $api;
	}

	/**
	 * @see \WP_Embed::shortcode()
	 *
	 * @param string $url
	 *
	 * @return Item
	 */
	public function getItemFromUrl( string $url ): Item {

		$cacheId = $this->getCacheForUrl($url);

		if(is_null($cacheId)){
			$item = $this->api->getItem( $url );
			$this->saveItem($item);
			return $item;
		}else{
			return $this->buildItem($cacheId);
		}

	}
	protected function saveItem(Item $item){

		$postArray = array(
			'post_date_gmt' => $item->publishedAt()->format("Y-m-d H:i:s"), //TODO: Should this maybe be saved as meta to keep thsi field for caching date?
			'post_content'  => $item->body(),
			'post_title'    => $item->title(),
			'post_status'   => 'publish',
			'post_type'     => $this->postType(),
			'post_name'     => $this->hash($item->url()),
		);

		$postId =  wp_insert_post( $postArray );

		if(is_wp_error($postId)) return;

		$metaData = array(
			'url' => $item->url(),
			'itemType' => $item->itemType(),
			'thumbnailUrl' => $item->thumbnailUrl(),
			'thumbnailContentType' => $item->thumbnailContentType(),
			'thumbnailContent' => $item->thumbnailContent(),
			'authorName' => $item->authorName(),
			'authorUrl' => $item->authorUrl(),
		);

		foreach ($metaData as $metaKey => $metaValue) {
			if(update_post_meta($postId, $metaKey, $metaValue) === false){
				throw new \Error('Error Updating Meta');
				//TODO: Rollback?
			}
		}

	}


	protected function buildItem(int $cacheId): Item{

		$url = get_post_meta($cacheId, 'url', true ) ?? '';
		$itemType = get_post_meta($cacheId, 'itemType', true ) ?? '';
		$title = get_post($cacheId)->post_title ?? '';
		$body = get_post($cacheId)->post_content ?? '';
		$thumbnailUrl = get_post_meta($cacheId, 'thumbnailUrl', true ) ?? '';
		$thumbnailContentType = get_post_meta($cacheId, 'thumbnailContentType', true ) ?? '';
		$thumbnailContent = get_post_meta($cacheId, 'thumbnailContent', true ) ?? '';
		$authorName = get_post_meta($cacheId, 'authorName', true ) ?? '';
		$authorUrl = get_post_meta($cacheId, 'authorUrl', true ) ?? '';
		$publishedAt = get_post_datetime( $cacheId, 'date', 'gmt')->format('c');

		return new Item(
			$url,
			$itemType,
			$title,
			$body,
			$thumbnailUrl,
			$thumbnailContentType,
			$thumbnailContent,
			$authorName,
			$authorUrl,
			$publishedAt
		);

	}

	protected function hash( string $url ):string {
		return md5( $url );
	}

	protected function postType():string {
		return 'betterembed_cache'; //TODO: This needs to come from config.
	}

	/**
	 * @see \WP_Embed::find_oembed_post_id()
	 *
	 * @param string $url
	 *
	 * @return int|null
	 */
	protected function getCacheForUrl( string $url ):?int {

		$betterembed_post_query = new WP_Query(
			array(
				'post_type'              => $this->postType(),
				'post_status'            => 'publish',
				'name'                   => $this->hash( $url ),
				'posts_per_page'         => 1,
				'no_found_rows'          => true,
				'cache_results'          => true,
				//'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
				'lazy_load_term_meta'    => false,
			)
		);

		if ( ! empty( $betterembed_post_query->posts ) ) {
			// Note: 'fields' => 'ids' is not being used in order to cache the post object as it will be needed.
			return $betterembed_post_query->posts[0]->ID;
		}

		return null;

	}

}
