<?php


namespace BetterEmbed\WordPress;


use BetterEmbed\WordPress\Model\Item;

class Container {

	protected static $currentItem;

	static public function setCurrentItem(Item $item){
		self::$currentItem = $item;
	}

	static public function clearCurrentItem(){
		self::$currentItem = null;
	}

	static public function currentItem():?Item{
		return self::$currentItem;
	}

}
