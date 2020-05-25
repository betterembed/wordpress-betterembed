<?php


namespace BetterEmbed\WordPress\Exception;


class FailedToGetItem extends \RuntimeException implements BetterEmbedException {

	public static function fromCacheException( string $url, FailedToCreateCache $exception ){

		$message = \sprintf(
			'Could not get item cache for URL "%1$s". Reason: "%2$s".',
			$url,
			$exception->getMessage()
		);

		return new static( $message, (int) $exception->getCode(), $exception );
	}

}
