<?php

namespace BetterEmbed\WordPress\Service;

use BetterEmbed\WordPress\Plugin;

class Assets implements Service
{

    /** @var Plugin */
    protected $plugin;

    public function init( Plugin $plugin) {
        $this->plugin = $plugin;
        $this->registerBackend();
        $this->registerFrontend();
    }

    protected function assetPath( string $filename = ''): string {
        return $this->plugin->pluginPath() . '/assets/build/' . $filename;
    }

    protected function assetUrl( string $filename = ''): string {
        return plugins_url('assets/build/' . $filename, $this->plugin->pluginFile());
    }

    protected function registerBackend() {

        // Automatically load dependencies and version.
        /** @noinspection PhpIncludeInspection */
        $assetFile = include $this->assetPath('index.asset.php');

        wp_register_script(
            $this->plugin->prefix('editor'),
            $this->assetUrl('index.js'),
            $assetFile['dependencies'],
            $assetFile['version'],
            false
        );

        if (function_exists('wp_set_script_translations')) {
            /**
             * May be extended to wp_set_script_translations( 'my-handle', 'my-domain',
             * plugin_dir_path( MY_PLUGIN ) . 'languages' ) ). For details see
             * https://make.wordpress.org/core/2018/11/09/new-javascript-i18n-support-in-wordpress/
             */
            wp_set_script_translations($this->plugin->prefix('editor'), 'betterembed');
        }

        wp_register_style(
            $this->plugin->prefix('editor'),
            $this->assetUrl('editor.css'),
            array( 'wp-edit-blocks' ),
            filemtime($this->assetPath() . 'editor.css')
        );
    }

    protected function registerFrontend() {

        $pluginFile = $this->plugin->pluginFile();

        wp_register_script(
            $this->plugin->prefix('frontend'),
            $this->assetUrl('betterembed.js'),
            array(),
            filemtime($this->assetPath() . 'betterembed.js'),
            true
        );

        wp_register_style(
            $this->plugin->prefix('style'),
            $this->assetUrl('style.css'),
            array( $this->plugin->prefix('theme') ),
            filemtime($this->assetPath() . 'style.css')
        );

        wp_register_style(
            $this->plugin->prefix('theme'),
            $this->assetUrl('theme.css'),
            array(),
            filemtime($this->assetPath() . 'theme.css')
        );
    }
}
