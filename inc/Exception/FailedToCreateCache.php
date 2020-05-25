<?php

namespace BetterEmbed\WordPress\Exception;

use RuntimeException;
use WP_Error;

use function sprintf;

class FailedToCreateCache extends RuntimeException implements BetterEmbedException
{

    public static function forVarious( string $url ) {
        $message = sprintf(
            'Could not cache URL "%1$s".',
            $url
        );

        return new static($message);
    }

    public static function fromWpError( string $url, WP_Error $error ) {
        $message = sprintf(
            'Could not cache URL "%1$s". Reason: "%2$s".',
            $url,
            $error->get_error_message()
        );

        return new static($message);
    }

    public static function fromException( string $url, BetterEmbedException $exception ) {

        $message = sprintf(
            'Could not create attachment from URL "%1$s". Reason: "%2$s".',
            $url,
            $exception->getMessage()
        );

        return new static($message, (int) $exception->getCode(), $exception);
    }

    public static function fromMeta( string $url, string $metaKey, string $metaValue ) {

        $message = sprintf(
            'Could not create attachment meta from URL "%1$s" for key "%2$s" with value "%3$s".',
            $url,
            $metaKey,
            $metaValue
        );

        return new static($message);
    }
}
