<?php

namespace BetterEmbed\WordPress\Tests\Api;

use BetterEmbed\WordPress\Api\Api;
use BetterEmbed\WordPress\Exception\FailedToDownloadUrl;
use BetterEmbed\WordPress\Exception\InvalidUrl;
use BetterEmbed\WordPress\Tests\WpTestCase;

class ApiTest extends WpTestCase
{

    public function testInvalidEndpointUrl() {
        $this->expectException(InvalidUrl::class);
        $api = new Api('not-a-url');
    }

    public function testInvalidEndpoint() {
        $this->expectException(FailedToDownloadUrl::class);
        $endpoint = 'https://example.com/404';
        $api      = new Api($endpoint);
        $api->getItem('https://example.com');
    }

    public function testInvalidEmbedUrl() {
        $this->expectException(FailedToDownloadUrl::class);
        $endpoint = 'https://api.betterembed.com/api/v0/item';
        $api      = new Api($endpoint);
        $api->getItem('https://example.com/404');
    }

    public function testExampleCom() {
        $endpoint = 'https://api.betterembed.com/api/v0/item';
        $api      = new Api($endpoint);
        $item     = $api->getItem('https://example.com');

        $this->assertSame('https://example.com/', $item->url());
        $this->assertSame('example', $item->itemType());
        $this->assertSame('Example Domain', $item->title());
    }
}
