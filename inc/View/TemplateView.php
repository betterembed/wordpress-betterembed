<?php


namespace BetterEmbed\WordPress\View;


class TemplateView {

	protected $templateFolder;

	public function __construct( string $templateFolder ) {
		$this->templateFolder = trailingslashit( $templateFolder );
	}

	public function render( $file ){

		$buffer_level = \ob_get_level();

		\ob_start();

		try {
			include $this->templateFolder . $file;
		} catch ( \Exception $exception ) {
			// Remove whatever levels were added up until now.
			while ( \ob_get_level() > $buffer_level ) {
				\ob_end_clean();
			}
		}

		return \ob_get_clean() ?: '';

	}

}
