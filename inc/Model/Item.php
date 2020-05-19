<?php


namespace BetterEmbed\WordPress\Model;

use DateTimeInterface;

class Item {

	protected $url;

	protected $itemType;

	protected $title;
	protected $body;

	protected $thumbnailUrl;
	protected $thumbnailContentType;
	protected $thumbnailContent;

	protected $authorName;
	protected $authorUrl;

	protected $publishedAt;

	protected $embedHtml;

	public function __construct(
		string $url,
		string $itemType,
		string $title = '',
		string $body = '',
		string $thumbnailUrl = '',
		string $thumbnailContentType = '',
		string $thumbnailContent = '',
		string $authorName = '',
		string $authorUrl = '',
		string $publishedAt = '',
		string $embedHtml = ''
	) {
		$this->url                  = $url;
		$this->itemType             = $itemType;
		$this->title                = $title;
		$this->body                 = $body;
		$this->thumbnailUrl         = $thumbnailUrl;
		$this->thumbnailContentType = $thumbnailContentType;
		$this->thumbnailContent     = $thumbnailContent;
		$this->authorName           = $authorName;
		$this->authorUrl            = $authorUrl;
		$this->publishedAt          = $publishedAt;
		$this->embedHtml            = $embedHtml;
	}


	/**
	 * Raw HTML Embed as returned by the website.
	 *
	 * @return string
	 */
	public function embedHtml() {
		return $this->embedHtml;
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
	 * @return mixed
	 */
	public function thumbnailContentType() {
		return $this->thumbnailContentType;
	}

	/**
	 * @return mixed
	 */
	public function thumbnailContent() {
		return $this->thumbnailContent;
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
		return new \DateTime($this->publishedAt);
	}



}
