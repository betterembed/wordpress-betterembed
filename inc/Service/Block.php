<?php


namespace BetterEmbed\WordPress\Service;


use BetterEmbed\WordPress\Api\Api;
use BetterEmbed\WordPress\Container;
use BetterEmbed\WordPress\Plugin;
use BetterEmbed\WordPress\View\TemplateView;
use function BetterEmbed\WordPress\be_reset_item_data;
use function BetterEmbed\WordPress\be_setup_item_data;

class Block implements Service {

	/** @var Api */
	protected $api;

	/** @var Plugin */
	protected $plugin;

	/** @var TemplateView */
	protected $view;

	public function __construct( Api $api, TemplateView $view ) {
		$this->api  = $api;
		$this->view = $view;
	}

	public function init(Plugin $plugin){
		$this->plugin = $plugin;
		$this->registerBlock();
	}

	protected function registerBlock(){

		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		register_block_type(
			$this->plugin->namespace('embed'),
			array(
			//'style' => $this->plugin->prefix('style'),
			//'editor_style' => $this->plugin->prefix('editor'),
			'editor_script' => $this->plugin->prefix('editor'),
			'render_callback' => function ( $attributes, $content ) {
				if( empty($attributes['url']) || filter_var($attributes['url'], FILTER_VALIDATE_URL) === FALSE){
					return $this->view->render( 'error.php' );
				};

				be_setup_item_data( $this->api->getItem( $attributes['url'] ) );
				$html = $this->view->render( $this->plugin->namespace() . '.php' );
				be_reset_item_data();
				return $html;
			},
			'attributes' => array(
				'align' => array(
					'type' => 'string',
					'default' => '',
				),
				'className' => array(
					'type' => 'string',
					'default' => '',
				),
				'url' => array(
					'type' => 'string',
					'default' => '',
				),
			),
		) );

	}

}
