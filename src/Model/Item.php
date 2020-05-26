<?php

namespace BetterEmbed\WordPress\Model;

use DateTimeImmutable;
use Exception;

class Item
{

    /** @var string */
    protected $url;

    /** @var string */
    protected $itemType;

    /** @var string */
    protected $title;
    /** @var string  */
    protected $body;

    /** @var string */
    protected $thumbnailUrl;

    /** @var string */
    protected $authorName;
    /** @var string */
    protected $authorUrl;

    /** @var string */
    protected $publishedAt;

    /** @var string */
    protected $align;

    /**
     * @param string $url
     * @param string $itemType
     * @param string $title
     * @param string $body
     * @param string $thumbnailUrl
     * @param string $authorName
     * @param string $authorUrl
     * @param string $publishedAt UTC time
     * @param string $align
     */
    public function __construct(
        string $url,
        string $itemType,
        string $title = '',
        string $body = '',
        string $thumbnailUrl = '',
        string $authorName = '',
        string $authorUrl = '',
        string $publishedAt = '',
        string $align = ''
    ) {
        $this->url          = $url;
        $this->itemType     = $itemType;
        $this->title        = $title;
        $this->body         = $body;
        $this->thumbnailUrl = $thumbnailUrl;
        $this->authorName   = $authorName;
        $this->authorUrl    = $authorUrl;
        $this->publishedAt  = $publishedAt;
        $this->align        = $align;
    }

    /**
     * @return string
     */
    public function url(): string {
        return $this->url;
    }

    /**
     * @return string
     */
    public function itemType(): string {
        return $this->itemType;
    }

    /**
     * @return string
     */
    public function title(): string {
        return $this->title;
    }

    /**
     *
     * @return string
     */
    public function body(): string {
        return $this->body;
    }

    /**
     * @return string
     */
    public function thumbnailUrl(): string {
        return $this->thumbnailUrl;
    }

    /**
     * @return string
     */
    public function authorName(): string {
        return $this->authorName;
    }

    /**
     * @return string
     */
    public function authorUrl(): string {
        return $this->authorUrl;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function publishedAt(): ?DateTimeImmutable {
        try {
            return new DateTimeImmutable($this->publishedAt);
        } catch (Exception $exception) {
            return null;
        }
    }

    /**
     * @return string
     */
    public function publishedAtRaw(): string {
        return $this->publishedAt;
    }

    /**
     * @return string
     */
    public function align(): string {
        return $this->align;
    }
}
