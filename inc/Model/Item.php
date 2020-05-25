<?php


namespace BetterEmbed\WordPress\Model;

use DateTimeInterface;

class Item {

	protected $url;

	protected $itemType;

	protected $title;
	protected $body;

	protected $thumbnailUrl;

	protected $authorName;
	protected $authorUrl;

	protected $publishedAt;

	public function __construct(
		string $url,
		string $itemType,
		string $title = '',
		string $body = '',
		string $thumbnailUrl = '',
		string $authorName = '',
		string $authorUrl = '',
		string $publishedAt = ''
	) {
		$this->url                  = $url;
		$this->itemType             = $itemType;
		$this->title                = $title;
		$this->body                 = $body;
		$this->thumbnailUrl         = $thumbnailUrl;
		$this->authorName           = $authorName;
		$this->authorUrl            = $authorUrl;
		$this->publishedAt          = $publishedAt;
	}

	/**
	 * @return string
	 */
	public function url() {
		return $this->url;
	}

	/**
	 * TODO: Is this from a limited list? Which types are known?
	 *
	 * @return string
	 */
	public function itemType() {
		return $this->itemType;
	}

	/**
	 * @return string
	 */
	public function title() {
		return $this->title;
	}

	/**
	 *
	 * @return string
	 */
	public function body() {
		return $this->body;
	}

	/**
	 * @return string
	 */
	public function thumbnailUrl() {
		return $this->thumbnailUrl;
	}

	/**
	 * @return string
	 */
	public function authorName() {
		return $this->authorName;
	}

	/**
	 * @return string
	 */
	public function authorUrl() {
		return $this->authorUrl;
	}

	/**
	 * @return DateTimeInterface
	 */
	public function publishedAt() {
		try {
			return new \DateTime($this->publishedAt);
		}catch (\Exception $exception){
			return new \DateTime();
		}
	}



}
