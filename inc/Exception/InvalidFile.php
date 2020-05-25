<?php


namespace BetterEmbed\WordPress\Exception;


class InvalidFile extends \RuntimeException implements BetterEmbedException {

	public static function fromContentType( string $url, string $contentType ) {
		$message = \sprintf(
			'The Content-Type "%2$s" of the URL "%1$s" is not a valid image mime type.',
			$url,
			$contentType
		);

		return new static( $message );
	}

	public static function fromFileSizeType( string $url, int $size, int $maxSize ) {
		$message = \sprintf(
			'The size "%2$s" of the URL "%1$s" is bigger than the allowed maximum of "%3$s".',
			$url,
			$size,
			$maxSize
		);

		return new static( $message );
	}

}
