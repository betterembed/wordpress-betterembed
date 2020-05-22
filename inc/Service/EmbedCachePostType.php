<?php


namespace BetterEmbed\WordPress\Service;


use BetterEmbed\WordPress\Plugin;

class EmbedCachePostType implements Service {

	/** @var Plugin */
	protected $plugin;

	public function init(Plugin $plugin){
		$this->plugin = $plugin;
		$this->registerPostType();
	}

	protected function registerPostType(){


		/**
		 * Enables UI für the Cache Post Type for debugging.
		 *
		 * @since 0.0.1-alpha
		 *
		 * @param int $enabled If the UI is enabled. Default `false`.
		 */
		$showUi = (bool) apply_filters(
			$this->plugin->namespace('showUI'),
			( defined('BETTEREMBED_DEBUG') && BETTEREMBED_DEBUG )
		);

		register_post_type(
			$this->plugin->prefix('cache'),
			array(
				'labels'           => array(
					'name'          => __( 'BetterEmbeds', 'betterembed' ),
					'singular_name' => __( 'BetterEmbed',  'betterembed' ),
				),
				'description'      => __( 'Cache for Embeds', 'betterembed' ),
				'public'           => false,
				'hierarchical'     => false,
				'show_ui'          => $showUi,
				'menu_icon'        => 'dashicons-editor-code',
				'rewrite'          => false,
				'query_var'        => false,
				'delete_with_user' => false,
				'can_export'       => false,
				'supports'         => array(),
			)
		);

	}

}
