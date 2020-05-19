<?php

namespace BetterEmbed\WordPress {

	function be_get_the_title(){
		$item = Container::currentItem();
		return is_null($item)?'':$item->title();
	}

	function be_the_title(){
		echo be_get_the_title();
	}

	function be_get_the_content(){
		$item = Container::currentItem();
		return is_null($item)?'':$item->body();
	}

	function be_the_content(){
		echo be_get_the_content();
	}

}
