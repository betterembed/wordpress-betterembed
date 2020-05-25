<?php // phpcs:ignore PSR1.Files.SideEffects.FoundWithSymbols

namespace BetterEmbed\WordPress\Storage;

use BetterEmbed\WordPress\Api\Api;
use BetterEmbed\WordPress\Exception\BetterEmbedException;
use BetterEmbed\WordPress\Exception\FailedToCreateCache;
use BetterEmbed\WordPress\Exception\FailedToGetItem;
use BetterEmbed\WordPress\Model\Item;
use BetterEmbed\WordPress\Util\AttachmentHelper;
use WP_Query;

// TODO: Can this be solved in a smarter way?
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

class PostTypeCache implements Storage
{


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

        if (is_null($cacheId)) {
            $item = $this->api->getItem($url);
            try {
                $cacheId = $this->saveItem($item);
            } catch (FailedToCreateCache $exception) {
                throw FailedToGetItem::fromCacheException($url, $exception);
            }
        }
        return $this->buildItem($cacheId);
    }

    /**
     * @param Item $item
     *
     * @throws FailedToCreateCache
     *
     * @return int
     */
    protected function saveItem( Item $item): int {

        $postArray = array(
            //TODO: Should this maybe be saved as meta to keep this field for caching date?
            'post_date_gmt' => $item->publishedAt()->format('Y-m-d H:i:s'),
            'post_content'  => $item->body(),
            'post_title'    => $item->title(),
            'post_status'   => 'publish',
            'post_type'     => $this->postType(),
            'post_name'     => $this->hash($item->url()),
        );

        $postId =  wp_insert_post($postArray);

        if ($postId === 0) {
            throw FailedToCreateCache::forVarious($item->url());
        }
        if (is_wp_error($postId)) {
            throw FailedToCreateCache::fromWpError($item->url(), $postId);
        }

        if ($item->thumbnailUrl() !== '') {
            try {
                $this->importThumbnail($item->thumbnailUrl(), $postId);
            } catch (BetterEmbedException $exception) {
                wp_delete_post($postId);
                throw FailedToCreateCache::fromException($item->url(), $exception);
            }
        }

        $metaData = array(
            'url'          => $item->url(),
            'itemType'     => $item->itemType(),
            'thumbnailUrl' => $item->thumbnailUrl(),
            'authorName'   => $item->authorName(),
            'authorUrl'    => $item->authorUrl(),
        );

        foreach ($metaData as $metaKey => $metaValue) {
            if (update_post_meta($postId, $metaKey, $metaValue) === false) {
                wp_delete_post($postId);
                throw FailedToCreateCache::fromMeta($item->url(), $metaKey, $metaValue);
            }
        }

        return $postId;
    }

    protected function importThumbnail( string $url, int $itemAttachmentPostId) {

        $attachmentId = AttachmentHelper::urlToAttachment($url, $itemAttachmentPostId);

        if (update_post_meta($itemAttachmentPostId, '_thumbnail_id', $attachmentId) === false) {
            throw FailedToCreateCache::fromMeta($url, '_thumbnail_id', $attachmentId);
        }

        return $attachmentId;
    }

    protected function buildItem( int $cacheId): Item {

        $url          = get_post_meta($cacheId, 'url', true) ?? '';
        $itemType     = get_post_meta($cacheId, 'itemType', true) ?? '';
        $title        = get_post($cacheId)->post_title ?? '';
        $body         = get_post($cacheId)->post_content ?? '';
        $thumbnailUrl = get_the_post_thumbnail_url($cacheId) ?? '';
        $authorName   = get_post_meta($cacheId, 'authorName', true) ?? '';
        $authorUrl    = get_post_meta($cacheId, 'authorUrl', true) ?? '';
        $publishedAt  = get_post_datetime($cacheId, 'date', 'gmt')->format('c');

        return new Item(
            $url,
            $itemType,
            $title,
            $body,
            $thumbnailUrl,
            $authorName,
            $authorUrl,
            $publishedAt
        );
    }

    protected function hash( string $url ): string {
        return md5($url);
    }

    protected function postType(): string {
        return 'betterembed_cache'; //TODO: This needs to come from config.
    }

    /**
     * @see \WP_Embed::find_oembed_post_id()
     *
     * @param string $url
     *
     * @return int|null
     */
    protected function getCacheForUrl( string $url ): ?int {

        $betterembed_post_query = new WP_Query(
            array(
                'post_type'              => $this->postType(),
                'post_status'            => 'publish',
                'name'                   => $this->hash($url),
                'posts_per_page'         => 1,
                'no_found_rows'          => true,
                'cache_results'          => true,
                'update_post_term_cache' => false,
                'lazy_load_term_meta'    => false,
            )
        );

        if (! empty($betterembed_post_query->posts)) {
            // Note: 'fields' => 'ids' is not being used in order to cache the post object as it will be needed.
            return $betterembed_post_query->posts[0]->ID;
        }

        return null;
    }
}
