<?php


namespace BetterEmbed\WordPress\Service;


use BetterEmbed\WordPress\Plugin;

class Assets implements Service {

	/** @var Plugin */
	protected $plugin;

	public function init(Plugin $plugin){
		$this->plugin = $plugin;
		$this->registerBackend();
		$this->registerFrontend();
	}

	protected function assetPath():string {
		return $this->plugin->pluginPath() . '/build/';
	}

	protected function registerBackend(){

		$pluginFile = $this->plugin->pluginFile();
		$pluginPath = $this->plugin->pluginPath();

		// Automatically load dependencies and version.
		$assetFile = include($this->assetPath() . 'index.asset.php');

		wp_register_script(
			$this->plugin->prefix('editor'),
			plugins_url( 'build/index.js', $pluginFile),
			$assetFile['dependencies'],
			$assetFile['version']
		);

		if ( function_exists( 'wp_set_script_translations' ) ) {
			/**
			 * May be extended to wp_set_script_translations( 'my-handle', 'my-domain',
			 * plugin_dir_path( MY_PLUGIN ) . 'languages' ) ). For details see
			 * https://make.wordpress.org/core/2018/11/09/new-javascript-i18n-support-in-wordpress/
			 */
			wp_set_script_translations( $this->plugin->prefix('editor'), 'betterembed' );
		}

		wp_register_style(
			$this->plugin->prefix('editor'),
			plugins_url( 'build/editor.css', $pluginFile ),
			array( 'wp-edit-blocks' ),
			filemtime( $this->assetPath() . 'editor.css' )
		);

	}

	protected function registerFrontend(){

		$pluginFile = $this->plugin->pluginFile();
		$pluginPath = $this->plugin->pluginPath();

		wp_register_style(
			$this->plugin->prefix('style'),
			plugins_url( 'build/style.css', $pluginFile ),
			array(),
			filemtime( $this->assetPath() . 'style.css' )
		);

	}

}
