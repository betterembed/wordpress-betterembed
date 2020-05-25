<?php

namespace BetterEmbed\WordPress\Model;

use DateTime;
use DateTimeInterface;
use Exception;

class Item
{

    protected $url;

    protected $itemType;

    protected $title;
    protected $body;

    protected $thumbnailUrl;

    protected $authorName;
    protected $authorUrl;

    protected $publishedAt;

    /**
     * Item constructor.
     *
     * @param string $url
     * @param string $itemType
     * @param string $title
     * @param string $body
     * @param string $thumbnailUrl
     * @param string $authorName
     * @param string $authorUrl
     * @param string $publishedAt UTC time
     */
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
        $this->url          = $url;
        $this->itemType     = $itemType;
        $this->title        = $title;
        $this->body         = $body;
        $this->thumbnailUrl = $thumbnailUrl;
        $this->authorName   = $authorName;
        $this->authorUrl    = $authorUrl;
        $this->publishedAt  = $publishedAt;
    }

    /**
     * @return string
     */
    public function url() {
        return $this->url;
    }

    /**
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
            return new DateTime($this->publishedAt);
        } catch (Exception $exception) {
            return new DateTime();
        }
    }
}
