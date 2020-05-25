<?php // phpcs:ignore PSR1.Files.SideEffects.FoundWithSymbols

/**
 * Better Embed
 *
 * @package           BetterEmbed
 * @copyright         2020 Better Embed
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Better Embed
 * Plugin URI:        https://www.acolono.com/betterembed
 * Description:       Embed content from other sites in a privacy friendly, performant and easily themeable way.
 * Version:           0.1.0-alpha.1
 * Requires at least: 5.4
 * Requires PHP:      7.2
 * Author:            Better Embed
 * Author URI:        https://www.acolono.com/betterembed
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       betterembed
 * Domain Path:       /languages
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

defined('ABSPATH') || exit;

$betterembed_autoloader = __DIR__ . '/vendor/autoload.php';
if (! file_exists($betterembed_autoloader)) {
    return false;
}

/** @noinspection PhpIncludeInspection */
require_once $betterembed_autoloader;

function betterembed_init() {

    $plugin = new \BetterEmbed\WordPress\Plugin(__FILE__, 'betterembed');
    $plugin->init(
        array(
            new \BetterEmbed\WordPress\Service\Assets(),
            new \BetterEmbed\WordPress\Service\Block(
                new \BetterEmbed\WordPress\Storage\PostTypeCache(
                    new \BetterEmbed\WordPress\Api\Api('https://api.betterembed.com/api/v0/item')
                ),
                new \BetterEmbed\WordPress\View\TemplateView(__DIR__ . '/templates')
            ),
            new \BetterEmbed\WordPress\Service\EmbedCachePostType(),
        )
    );

    load_plugin_textdomain('betterembed', false, basename(__DIR__) . '/languages');
}

add_action('init', 'betterembed_init');
