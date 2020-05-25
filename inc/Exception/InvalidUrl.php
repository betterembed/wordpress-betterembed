<?php


namespace BetterEmbed\WordPress\Exception;


class InvalidUrl extends \InvalidArgumentException implements BetterEmbedException {

	public static function fromUrl( string $url ) {
		$message = \sprintf(
			'The url "%s" is not valid or readable.',
			$url
		);

		return new static( $message );
	}

}
