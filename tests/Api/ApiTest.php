<?php

namespace BetterEmbed\WordPress\Tests\Api;

use BetterEmbed\WordPress\Api\Api;
use BetterEmbed\WordPress\Exception\FailedToDownloadUrl;
use BetterEmbed\WordPress\Exception\InvalidUrl;
use BetterEmbed\WordPress\Tests\WpTestCase;

use function Brain\Monkey\Functions\when;

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
}
