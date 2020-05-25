<?php

namespace BetterEmbed\WordPress\Exception;

use RuntimeException;

use function sprintf;

class FailedToGetItem extends RuntimeException implements BetterEmbedException
{

    public static function fromApiException( string $url, FailedToDownloadUrl $exception ) {

        $message = sprintf(
            'Could not get item from API for URL "%1$s". Reason: "%2$s".',
            $url,
            $exception->getMessage()
        );

        return new static($message, (int) $exception->getCode(), $exception);
    }

    public static function fromCacheException( string $url, FailedToCreateCache $exception ) {

        $message = sprintf(
            'Could not get item cache for URL "%1$s". Reason: "%2$s".',
            $url,
            $exception->getMessage()
        );

        return new static($message, (int) $exception->getCode(), $exception);
    }
}
