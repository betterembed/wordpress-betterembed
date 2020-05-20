<?php


namespace BetterEmbed\WordPress\Storage;


use BetterEmbed\WordPress\Api\Api;
use BetterEmbed\WordPress\Model\Item;

class NoCache implements Storage {

	/** @var Api */
	protected $api;

	public function __construct( Api $api ) {
		$this->api = $api;
	}

	public function getItemFromUrl( string $url ): Item {

		$item = $this->api->getItem( $url );

		return $item;

	}
}
