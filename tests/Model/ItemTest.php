<?php

namespace BetterEmbed\WordPress\Tests\Model;

use BetterEmbed\WordPress\Model\Item;
use BetterEmbed\WordPress\Tests\TestCase;
use DateTimeImmutable;

class ItemTest extends TestCase
{

    public function testPublishedAtInvalid() {
        $item = new Item('', '', '', '', '', '', '', '0');
        $this->assertSame('0', $item->publishedAtRaw());
        $this->assertNull($item->publishedAt());

        $item = new Item('', '', '', '', '', '', '', 'nonsense');
        $this->assertSame('nonsense', $item->publishedAtRaw());
        $this->assertNull($item->publishedAt());
    }

    public function testPublishedAtValid() {
        $item = new Item('', '', '', '', '', '', '', '1970-01-01T00:00:00+00:00');
        $this->assertInstanceOf(DateTimeImmutable::class, $item->publishedAt());
        $this->assertSame(0, $item->publishedAt()->getTimestamp());

        $item = new Item('', '', '', '', '', '', '', '1970-01-01T00:00:00-01:00');
        $this->assertSame(HOUR_IN_SECONDS, $item->publishedAt()->getTimestamp());
    }
}
