<?php

namespace BetterEmbed\WordPress;

use BetterEmbed\WordPress\Model\Item;

class Container
{

    protected static $currentItem;

    public static function setCurrentItem( Item $item) {
        self::$currentItem = $item;
    }

    public static function clearCurrentItem() {
        self::$currentItem = null;
    }

    public static function currentItem(): ?Item {
        return self::$currentItem;
    }
}
