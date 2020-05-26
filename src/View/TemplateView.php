<?php

namespace BetterEmbed\WordPress\View;

use Exception;

use function ob_end_clean;
use function ob_get_clean;
use function ob_get_level;
use function ob_start;

class TemplateView
{

    protected $templateFolder;

    public function __construct( string $templateFolder ) {
        $this->templateFolder = trailingslashit($templateFolder);
    }

    public function render( string $file ) {

        $buffer_level = ob_get_level();

        ob_start();

        try {
            include $this->templateFolder . $file;
        } catch (Exception $exception) {
            // Remove whatever levels were added up until now.
            while (ob_get_level() > $buffer_level) {
                ob_end_clean();
            }
        }

        return ob_get_clean() ?: '';
    }
}
