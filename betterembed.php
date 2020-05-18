<?php
/**
 * Better Embed
 *
 * @package           BetterEmbed
 * @copyright         2020 Better Embed
 * @license           GPL-3.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Better Embed
 * Plugin URI:        https://www.acolono.com/betterembed
 * Description:       Include the essential content of any website or service such as Facebook posts, Twitter tweets, Instagram posts, YouTube videos, WordPress Blogposts etc. into your own page without any extra effort.
 * Version:           0.1.0-alpha.1
 * Requires at least: 5.4
 * Requires PHP:      7.2
 * Author:            Better Embed
 * Author URI:        https://www.acolono.com/betterembed
 * License:           GPL v3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
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

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'betterembed_load_textdomain' );

function betterembed_load_textdomain() {
	load_plugin_textdomain( 'betterembed', false, basename( __DIR__ ) . '/languages' );
}

function betterembed_register_block() {

	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}

	// Automatically load dependencies and version.
	$assetFile = include(__DIR__ . '/build/index.asset.php');

	wp_register_script(
		'betterembed',
		plugins_url( 'build/index.js', __FILE__ ),
		$assetFile['dependencies'],
		$assetFile['version']
	);

	/*
	wp_register_style(
		'betterembed-editor',
		plugins_url( 'editor.css', __FILE__ ),
		array( 'wp-edit-blocks' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'editor.css' )
	);

	wp_register_style(
		'betterembed',
		plugins_url( 'style.css', __FILE__ ),
		[],
		filemtime( plugin_dir_path( __FILE__ ) . 'style.css' )
	);
	*/

	register_block_type( 'betterembed/betterembed', array(
		//'style' => 'betterembed',
		//'editor_style' => 'betterembed-editor',
		'editor_script' => 'betterembed',
		'render_callback' => function ( $attributes, $content ) {

			$url = 'https://api.betterembed.com/api/v0/item';
			$url = add_query_arg(
				array( 'url' => $attributes['url'] ),
				$url
			);

			$request = wp_remote_get( $url );

			$body = json_decode(wp_remote_retrieve_body( $request ), true );

			//sleep(2);

			//We can handle an empty block differently in frontend. Somewhat hacky but hey...
			//return '';

			return sprintf(
				'<div class="wp-block-betterembed-betterembed %s"><h3>%s</h3><p>%s</p></div>',
				esc_attr($attributes['className']),
				!empty($body['title'])?esc_html($body['title']):'',
				esc_html($body['body'])
			);

		},
		'attributes' => [
			'align' => [
				'type' => 'string',
				'default' => '',
			],
			'className' => [
				'type' => 'string',
				'default' => '',
			],
			'url' => [
				'type' => 'string',
				'default' => '',
			],
		],
	) );

	if ( function_exists( 'wp_set_script_translations' ) ) {
		/**
		 * May be extended to wp_set_script_translations( 'my-handle', 'my-domain',
		 * plugin_dir_path( MY_PLUGIN ) . 'languages' ) ). For details see
		 * https://make.wordpress.org/core/2018/11/09/new-javascript-i18n-support-in-wordpress/
		 */
		wp_set_script_translations( 'betterembed', 'betterembed' );
	}

}
add_action( 'init', 'betterembed_register_block' );
