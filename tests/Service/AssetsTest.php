<?php

namespace BetterEmbed\WordPress\Tests\Service;

use BetterEmbed\WordPress\Plugin;
use BetterEmbed\WordPress\Service\Assets;
use BetterEmbed\WordPress\Tests\WpTestCase;

class AssetsTest extends WpTestCase
{

    public function testInit() {
        $plugin = new Plugin(dirname(__FILE__, 2), 'betterembed');
        $assets = new Assets('assets/build');
        $assets->init($plugin);

        $this->assertTrue(wp_script_is('betterembed_editor', 'registered'));
        $this->assertTrue(wp_style_is('betterembed_editor', 'registered'));

        $this->assertTrue(wp_script_is('betterembed_frontend', 'registered'));
        $this->assertTrue(wp_style_is('betterembed_style', 'registered'));
        $this->assertTrue(wp_style_is('betterembed_theme', 'registered'));
    }
}
