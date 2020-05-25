<?php


namespace BetterEmbed\WordPress\Service;


use BetterEmbed\WordPress\Exception\FailedToGetItem;
use BetterEmbed\WordPress\Plugin;
use BetterEmbed\WordPress\Storage\Storage;
use BetterEmbed\WordPress\View\TemplateView;
use function BetterEmbed\WordPress\be_reset_item_data;
use function BetterEmbed\WordPress\be_setup_item_data;

class Block implements Service {

	/** @var Storage */
	protected $storage;

	/** @var Plugin */
	protected $plugin;

	/** @var TemplateView */
	protected $view;

	public function __construct( Storage $storage, TemplateView $view ) {
		$this->storage = $storage;
		$this->view    = $view;
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
			'style' => $this->plugin->prefix('style'),
			'script' => $this->plugin->prefix('frontend'),
			'editor_style' => $this->plugin->prefix('editor'),
			'editor_script' => $this->plugin->prefix('editor'),
			'render_callback' => function ( $attributes, $content ) {
				if( empty($attributes['url']) || filter_var($attributes['url'], FILTER_VALIDATE_URL) === FALSE){
					return $this->view->render( 'error.php' );
				};

				try {
					$item = $this->storage->getItemFromUrl( $attributes['url'] );
				}catch (FailedToGetItem $exception){
					return $content;
				}

				be_setup_item_data( $item );
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
