<?php

namespace BetterEmbed\WordPress\Exception;

use BetterEmbed\WordPress\Model\ProblemDetails;
use RuntimeException;
use WP_Error;

use function sprintf;

class FailedToDownloadUrl extends RuntimeException implements BetterEmbedException
{

    public static function forVarious( string $url ) {
        $message = sprintf(
            'Could not download URL "%1$s".',
            $url
        );

        return new static($message);
    }

    public static function forEmpty( string $url ) {
        $message = sprintf(
            'The response from the URL "%1$s" was empty.',
            $url
        );

        return new static($message);
    }

    public static function fromWpError( string $url, WP_Error $error ) {
        $message = sprintf(
            'Could not download URL "%1$s". Reason: "%2$s".',
            $url,
            $error->get_error_message()
        );

        return new static($message);
    }

    public static function fromProblemDetails( string $url, ProblemDetails $problemDetails ) {

        $message = sprintf(
            '(%1$s) (%2$s): (%3$s)',
            $problemDetails->status(),
            $problemDetails->title(),
            $problemDetails->detail()
        );

        return new static($message);
    }
}
