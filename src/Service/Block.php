<?php

namespace BetterEmbed\WordPress\Service;

use BetterEmbed\WordPress\Exception\FailedToGetItem;
use BetterEmbed\WordPress\Model\Item;
use BetterEmbed\WordPress\Plugin;
use BetterEmbed\WordPress\Storage\Storage;
use BetterEmbed\WordPress\View\TemplateView;
use Exception;

use function BetterEmbed\WordPress\be_reset_item_data;
use function BetterEmbed\WordPress\be_setup_item_data;

class Block implements Service
{

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

    public function init( Plugin $plugin ) {
        $this->plugin = $plugin;
        $this->registerBlock();
    }

    protected function registerBlock() {

        register_block_type(
            $this->plugin->namespace('embed'),
            array(
                'style'           => $this->plugin->prefix('style'),
                'script'          => $this->plugin->prefix('frontend'),
                'editor_style'    => $this->plugin->prefix('editor'),
                'editor_script'   => $this->plugin->prefix('editor'),
                'render_callback' => array( $this, 'render' ),
                'attributes'      => array(
                    'align'     => array(
                        'type'    => 'string',
                        'default' => '',
                    ),
                    'className' => array(
                        'type'    => 'string',
                        'default' => '',
                    ),
                    'url'       => array(
                        'type'    => 'string',
                        'default' => '',
                    ),
                ),
            )
        );
    }

    /**
     * Render the block.
     *
     * @param array $attributes
     * @param string $content
     *
     * @return string
     */
    public function render( array $attributes, string $content) {
        if (empty($attributes['url']) || filter_var($attributes['url'], FILTER_VALIDATE_URL) === false) {
            return $this->view->render('error.php');
        }

        try {
            $item = $this->storage->getItemFromUrl($attributes['url']);
        } catch (FailedToGetItem $exception) {
            if ($this->plugin->betterEmbedDebugEnabled()) {
                return $this->view->render('error.php');
            } else {
                return $content;
            }
        } catch (Exception $exception) {
            return $content;
        }

        if (!empty($attributes['align'])) {
            $item = new Item(
                $item->url(),
                $item->itemType(),
                $item->title(),
                $item->body(),
                $item->thumbnailUrl(),
                $item->authorName(),
                $item->authorUrl(),
                $item->publishedAtRaw(),
                $attributes['align']
            );
        }

        be_setup_item_data($item);
        $html = $this->view->render($this->plugin->namespace() . '.php');
        be_reset_item_data();
        if ($html === '') {
            return $this->view->render('error.php');
        }
        return $html;
    }
}
