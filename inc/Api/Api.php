<?php


namespace BetterEmbed\WordPress\Api;


use BetterEmbed\WordPress\Model\Item;

class Api {

	protected $endpoint;
	protected $apiKey;

	public function __construct( string $endpoint, string $apiKey = '' ) {

		if (filter_var($endpoint, FILTER_VALIDATE_URL) === FALSE) {
			throw new \Error('Invalid URL');
		}

		$this->endpoint = $endpoint;
		$this->apiKey   = $apiKey;
	}


	public function getItem(string $embedUrl):Item{

		$url = add_query_arg(
			array( 'url' => $embedUrl ),
			$this->endpoint
		);

		$request = wp_remote_get( $url );

		if(is_wp_error($request)){
			throw new \Error('API Error');
		}

		$code = wp_remote_retrieve_response_code( $request );

		if( $code !== 200 ){
			throw new \Error('API Error');
		}

		$body = wp_remote_retrieve_body( $request );

		if(empty($body)){
			throw new \Error('API Error');
		}

		return $this->buildItem($body);

	}

	protected function buildItem(string $body):Item{

		//TODO: Make this more solid:
		$data = json_decode( $body, true );

		$item = new Item(
			$data['url'] ?? '',
			$data['itemType'] ?? '',
			$data['title'] ?? '',
			$data['body'] ?? '',
			$data['thumbnailUrl'] ?? '',
			$data['thumbnailContentType'] ?? '',
			$data['authorName'] ?? '',
			$data['authorUrl'] ?? '',
			$data['publishedAt'] ?? '',
			$data['embedHtml'] ?? ''
		);

		return $item;

	}

}
