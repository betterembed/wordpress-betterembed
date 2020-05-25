<?php


namespace BetterEmbed\WordPress\Exception;


class FailedToCreateAttachment extends \RuntimeException implements BetterEmbedException {

	public static function fromWpError( string $url, \WP_Error $error ) {
		$message = \sprintf(
			'Could not create attachment from URL "%1$s". Reason: "%2$s".',
			$url,
			$error->get_error_message()
		);

		return new static( $message );
	}

}
