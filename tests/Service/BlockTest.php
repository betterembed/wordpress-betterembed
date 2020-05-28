<?php

namespace BetterEmbed\WordPress\Tests\Service;

use BetterEmbed\WordPress\Model\Item;
use BetterEmbed\WordPress\Plugin;
use BetterEmbed\WordPress\Service\Block;
use BetterEmbed\WordPress\Storage\Storage;
use BetterEmbed\WordPress\Tests\WpTestCase;
use BetterEmbed\WordPress\View\TemplateView;
use WP_Block_Type_Registry;

class BlockTest extends WpTestCase
{

    public function testInit() {
        $storageStub      = $this->createMock(Storage::class);
        $templateViewStub = $this->createMock(TemplateView::class);
        $plugin           = new Plugin(dirname(__FILE__, 2), 'betterembed');

        $block = new Block($storageStub, $templateViewStub);
        $block->init($plugin);

        $this->assertTrue(WP_Block_Type_Registry::get_instance()->is_registered('betterembed/embed'));

        $blockType = WP_Block_Type_Registry::get_instance()->get_registered('betterembed/embed');

        $this->assertNotNull($blockType);
        $this->assertSame('betterembed/embed', $blockType->name);
    }

    public function testRenderMissingUrl() {
        $storageStub = $this->createMock(Storage::class);

        $templateViewObserver = $this
            ->getMockBuilder(TemplateView::class)
            ->setMethods(array( 'render' ))
            ->disableOriginalConstructor()
            ->getMock();

        $templateViewObserver
            ->expects($this->once())
            ->method('render')
            ->with($this->equalTo('error.php'));

        $block = new Block($storageStub, $templateViewObserver);
        $block->render(array(), '');
    }

    public function testRenderEmptyRender() {
        $url      = 'https://example.com/';
        $itemType = 'example';

        $plugin = new Plugin(dirname(__FILE__, 2), 'betterembed');

        $storageStub = $this->createMock(Storage::class);
        $storageStub->method('getItemFromUrl')
                    ->willReturn(new Item(
                        $url,
                        $itemType
                    ));

        $templateViewObserver = $this
            ->getMockBuilder(TemplateView::class)
            ->setMethods(array( 'render' ))
            ->disableOriginalConstructor()
            ->getMock();

        $templateViewObserver
            ->expects($this->exactly(2))
            ->method('render')
            ->willReturn('')
            ->withConsecutive(
                array( $this->equalTo('betterembed.php') ),
                array( $this->equalTo('error.php') )
            );

        $block = new Block($storageStub, $templateViewObserver);
        $block->init($plugin);
        $block->render(array( 'url' => $url ), '');
    }

    public function testRender() {
        $url      = 'https://example.com/';
        $itemType = 'example';

        $plugin = new Plugin(dirname(__FILE__, 2), 'betterembed');

        $storageStub = $this->createMock(Storage::class);
        $storageStub->method('getItemFromUrl')
                    ->willReturn(new Item(
                        $url,
                        $itemType
                    ));

        $templateViewObserver = $this
            ->getMockBuilder(TemplateView::class)
            ->setMethods(array( 'render' ))
            ->disableOriginalConstructor()
            ->getMock();

        $templateViewObserver
            ->expects($this->once())
            ->method('render')
            ->with($this->equalTo('betterembed.php'));

        $block = new Block($storageStub, $templateViewObserver);
        $block->init($plugin);
        $block->render(array( 'url' => $url ), '');
    }
}
