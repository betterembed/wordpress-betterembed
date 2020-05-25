<?php

namespace BetterEmbed\WordPress\Exception;

class JsonException extends \Exception implements BetterEmbedException
{

    public static function forVarious() {
        $message = 'Invalid JSON';

        return new static($message);
    }
}
