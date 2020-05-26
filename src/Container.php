<?php

namespace BetterEmbed\WordPress;

use BetterEmbed\WordPress\Model\Item;

/**
 * Global Container class as a replacement for globals.
 *
 * Class Container
 * @package BetterEmbed\WordPress
 */
class Container
{

    /** @var Item */
    protected static $currentItem;

    public static function setCurrentItem( Item $item ) {
        self::$currentItem = $item;
    }

    public static function clearCurrentItem() {
        self::$currentItem = null;
    }

    public static function currentItem(): ?Item {
        return self::$currentItem;
    }
}
