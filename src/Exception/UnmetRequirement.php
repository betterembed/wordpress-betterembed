<?php

namespace BetterEmbed\WordPress\Exception;

class UnmetRequirement extends \RuntimeException implements BetterEmbedException
{

    public static function fromApiException( string $requirements, string $requiredVersion, string $actualVersion ) {

        $message = sprintf(
            '"%1$s" in version "%2$s" does not satisfy "%3$s".',
            $requirements,
            $actualVersion,
            $requiredVersion
        );

        return new static($message);
    }
}
