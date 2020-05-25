<?php


namespace BetterEmbed\WordPress\Exception;


class FailedToDownloadUrl extends \RuntimeException implements BetterEmbedException {

	public static function fromWpError( string $url, \WP_Error $error ) {
		$message = \sprintf(
			'Could not download URL "%1$s". Reason: "%2$s".',
			$url,
			$error->get_error_message()
		);

		return new static( $message );
	}

}
