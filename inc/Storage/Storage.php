<?php

namespace BetterEmbed\WordPress\Storage;

use BetterEmbed\WordPress\Model\Item;

interface Storage
{

    public function getItemFromUrl( string $url): Item;
}
