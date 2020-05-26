<?php

namespace BetterEmbed\WordPress {

    use BetterEmbed\WordPress\Model\Item;
    use DateTimeInterface;
    use WP_Embed;

    /**
     * Sets the current Better Embed Item in the global container.
     * This is similar to WPs `setup_postdata()` but uses a Container Class instead of globals.
     *
     * @param Item $item
     */
    function be_setup_item_data( Item $item ) {
        Container::setCurrentItem($item);
    }

    /**
     * Resets/clears the current Better Embed Item in the global container.
     * This is similar to WPs `wp_reset_postdata()` but uses a Container Class instead of globals.
     */
    function be_reset_item_data() {
        Container::clearCurrentItem();
    }

    /**
     * Return the source URL of the embed as returned by the BetterEmbed API.
     *
     * @see be_the_url() to display this value.
     *
     * @return string
     */
    function be_get_the_url(): string {
        $item = Container::currentItem();
        return is_null($item) ? '' : esc_url($item->url());
    }

    /**
     * Display the source URL of the embed as returned by the BetterEmbed API.
     *
     * @see be_get_the_url() to return this value.
     */
    function be_the_url() {
        echo be_get_the_url();
    }

    /**
     * Return the type of the embed.
     *
     * @todo Clarify the possible values. Can be the name of a known service or what, the <title>?
     *
     * @see be_the_item_type() to display this value.
     *
     * @return string
     */
    function be_get_the_item_type(): string {
        $item = Container::currentItem();
        return is_null($item) ? '' : esc_html($item->itemType());
    }

    /**
     * Display the type of the embed.
     *
     * @see be_get_the_item_type() to return this value.
     */
    function be_the_item_type() {
        echo be_get_the_item_type();
    }

    /**
     * Return the title of the embed.
     *
     * @see be_the_title() to display this value.
     *
     * @return string
     */
    function be_get_the_title(): string {
        $item = Container::currentItem();
        return is_null($item) ? '' : esc_html($item->title());
    }

    /**
     * Display the title of the embed.
     *
     * @see be_get_the_title() to return this value.
     */
    function be_the_title() {
        echo be_get_the_title();
    }

    /**
     * Return the main text of the embed.
     *
     * @see be_get_the_text() to display this value.
     *
     * @return string
     */
    function be_get_the_text(): string {
        $item = Container::currentItem();
        return is_null($item) ? '' : esc_html($item->body());
    }

    /**
     * Display the main text of the embed.
     *
     * @see be_get_the_text() to return this value.
     */
    function be_the_text() {
        echo be_get_the_text();
    }

    /**
     * Return the URL of the embed thumbnail.
     *
     * @todo Add a privacy switch to make this not return external URLs.
     *
     * @see be_the_thumbnail_url() to display this value.
     *
     * @return string
     */
    function be_get_the_thumbnail_url(): string {
        $item = Container::currentItem();
        return is_null($item) ? '' : esc_url($item->thumbnailUrl());
    }

    /**
     * Display the URL of the embed thumbnail.
     *
     * @see be_get_the_thumbnail_url() to return this value.
     */
    function be_the_thumbnail_url() {
        echo be_get_the_thumbnail_url();
    }

    /**
     * Display the embed thumbnail.
     *
     * @see be_the_thumbnail_url() to display the thumbnail URL.
     * @see be_get_the_thumbnail_url() to return the thumbnail URL.
     */
    function be_the_thumbnail() {
        $url = be_get_the_thumbnail_url();

        if ('' === $url) {
            return;
        }

        /** @noinspection HtmlUnknownTarget */
        printf(
            '<img src="%s" alt="">',
            $url // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        );
    }

    //TODO: Implement local thumbnail helpers.

    /**
     * Return the name of the embeds author.
     *
     * @see be_the_author_name() to display this value.
     *
     * @return string
     */
    function be_get_the_author_name(): string {
        $item = Container::currentItem();
        return is_null($item) ? '' : esc_html($item->authorName());
    }

    /**
     * Display the name of the embeds author.
     *
     * @see be_get_the_author_name() to return this value.
     */
    function be_the_author_name() {
        echo be_get_the_author_name();
    }

    /**
     * Return the URL of the embeds author.
     *
     * @see be_the_author_url() to display this value.
     *
     * @return string
     */
    function be_get_the_author_url(): string {
        $item = Container::currentItem();
        return is_null($item) ? '' : esc_html($item->authorUrl());
    }

    /**
     * Display the URL of the embeds author.
     *
     * @see be_get_the_author_url() to return this value.
     */
    function be_the_author_url() {
        echo be_get_the_author_url();
    }

    /**
     * Return the publication date of the embed as raw DateTime object or null.
     *
     * @see be_get_the_date() to return the formatted date.
     * @see be_the_date() to display the formatted date.
     * @see be_the_date_human() to display the date formated as human readable time difference to now.
     *
     * @return DateTimeInterface|null
     */
    function be_get_the_datetime(): ?DateTimeInterface {
        $item = Container::currentItem();
        return $item->publishedAt();
    }

    /**
     * Return the publication date of the embed.
     *
     * @see be_get_the_datetime() to return the raw DateTime object.
     * @see be_the_date() to display the formatted date.
     * @see be_the_date_human() to display the date formated as human readable time difference to now.
     *
     * @param string $format PHP date format defaults to the date_format option if not specified.
     *
     * @return string
     */
    function be_get_the_date( string $format = '' ): string {

        $dateTime = be_get_the_datetime();

        if ('' === $dateTime) {
            return '';
        }

        if ('' === $format) {
            $format = get_option('date_format');
        }

        $publishedAt = wp_date($format, $dateTime->getTimestamp());

        return $publishedAt ? esc_html($publishedAt) : '';
    }

    /**
     * Display the URL of the embeds author.
     *
     * @see be_get_the_datetime() to return the raw DateTime object.
     * @see be_get_the_date() to return the formatted date.
     * @see be_the_date_human() to display the date formated as human readable time difference to now.
     *
     * @param string $format PHP date format defaults to the `date_format` option if not specified.
     */
    function be_the_date( string $format = '' ) {
        echo be_get_the_date($format);
    }

    /**
     * Display the URL of the embeds author.
     *
     * @see be_get_the_datetime() to return the raw DateTime object.
     * @see be_get_the_date() to return the formatted date.
     * @see be_the_date() to display the formatted date.
     */
    function be_the_date_human() {
        $dateTime = be_get_the_datetime();

        if ('' === $dateTime) {
            return;
        }

        $humanTimeDiff = human_time_diff($dateTime->getTimestamp());

        echo esc_html(
            /* translators: %s: Human-readable time difference. */
            sprintf(__('%s ago', 'betterembed'), $humanTimeDiff)
        );
    }

    /**
     * Return the original embed HTML as determined by WordPress.
     *
     * @see be_the_embed_html() to display this value.
     *
     * @return string
     */
    function be_get_the_embed_html(): string {
        $item = Container::currentItem();

        if (is_null($item)) {
            return '';
        }

        $url = esc_url($item->url());

        if ($url === '') {
            return '';
        }

        // TODO: Consider forcing the cache onto the betterembed CPT instead of the post this is embed.
        // this might make cache clearing easier..
        // Do this only in initial betterembed fetch using \WP_Embed::cache_oembed()

        /** @var WP_Embed $wpEmbedObject */
        $wpEmbedObject = $GLOBALS['wp_embed'];
        $embed         = $wpEmbedObject->shortcode(
            array(),
            $url
        );

        // If the link isn't embeddable return an empty link instead of a URL or link.
        if ($embed === $wpEmbedObject->maybe_make_link($url)) {
            return '';
        }

        return $embed;
    }

    /**
     * Display the original embed HTML as determined by WordPress.
     *
     * @see be_get_the_embed_html() to return this value.
     */
    function be_the_embed_html() {
        echo be_get_the_embed_html();
    }

}
