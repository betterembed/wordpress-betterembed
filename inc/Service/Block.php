<?php


namespace BetterEmbed\WordPress\Service;


use BetterEmbed\WordPress\Api\Api;
use BetterEmbed\WordPress\Container;
use BetterEmbed\WordPress\Plugin;
use BetterEmbed\WordPress\View\TemplateView;

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
			$this->plugin->namespace('betterembed'),
			array(
			//'style' => $this->plugin->prefix('style'),
			//'editor_style' => $this->plugin->prefix('editor'),
			'editor_script' => $this->plugin->prefix('editor'),
			'render_callback' => function ( $attributes, $content ) {
				Container::setCurrentItem(
					$this->api->getItem($attributes['url'])
				);
				return $this->view->render( $this->plugin->namespace() . '.php' );
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
