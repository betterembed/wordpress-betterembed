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
    function be_setup_item_data( Item $item) {
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
     * Return the source URL of the embed.
     *
     * @return string
     */
    function be_get_the_url() {
        $item = Container::currentItem();
        return is_null($item) ? '' : esc_url($item->url());
    }

    /**
     * Display the source URL of the embed.
     */
    function be_the_url() {
        echo be_get_the_url();
    }

    /**
     * Return the type of the embed.
     *
     * @todo Clarify the possible values. Can be the name of a known service or what, the <title>?
     *
     * @return string
     */
    function be_get_the_item_type() {
        $item = Container::currentItem();
        return is_null($item) ? '' : esc_html($item->itemType());
    }

    /**
     * Display the type of the embed.
     */
    function be_the_item_type() {
        echo be_get_the_item_type();
    }

    /**
     * Return the title of the embed.
     *
     * @return string
     */
    function be_get_the_title() {
        $item = Container::currentItem();
        return is_null($item) ? '' : esc_html($item->title());
    }

    /**
     * Display the title of the embed.
     */
    function be_the_title() {
        echo be_get_the_title();
    }

    /**
     * Return the main text of the embed.
     *
     * @todo Naming inconsistency: API: `body` | CSS: `betterembed__text`
     *
     * @return string
     */
    function be_get_the_body() {
        $item = Container::currentItem();
        return is_null($item) ? '' : esc_html($item->body());
    }

    /**
     * Display the main text of the embed.
     */
    function be_the_body() {
        echo be_get_the_body();
    }

    /**
     * Return the URL of the embed thumbnail.
     *
     * @todo Add a privacy switch to make this not return external URLs.
     *
     * @return string
     */
    function be_get_the_thumbnail_url() {
        $item = Container::currentItem();
        return is_null($item) ? '' : esc_url($item->thumbnailUrl());
    }

    /**
     * Display the URL of the embed thumbnail.
     */
    function be_the_thumbnail_url() {
        echo be_get_the_thumbnail_url();
    }

    /**
     * Display the embed thumbnail.
     */
    function be_the_thumbnail() {
        $url = be_get_the_thumbnail_url();

        if ('' === $url) {
            return '';
        }

        printf(
            '<img src="%s" alt="">',
            $url
        );
    }

    //TODO: Implement local thumbnail helpers.

    /**
     * Return the name of the embeds author.
     *
     * @return string
     */
    function be_get_the_author_name() {
        $item = Container::currentItem();
        return is_null($item) ? '' : esc_html($item->authorName());
    }

    /**
     * Display the name of the embeds author.
     */
    function be_the_author_name() {
        echo be_get_the_author_name();
    }

    /**
     * Return the URL of the embeds author.
     *
     * @return string
     */
    function be_get_the_author_url() {
        $item = Container::currentItem();
        return is_null($item) ? '' : esc_html($item->authorUrl());
    }

    /**
     * Display the URL of the embeds author.
     */
    function be_the_author_url() {
        echo be_get_the_author_url();
    }

    /**
     * Return the publication date of the embed as raw DateTime object.
     *
     * @return DateTimeInterface|string
     */
    function be_get_the_datetime() {
        $item = Container::currentItem();
        return is_null($item) ? '' : $item->publishedAt();
    }

    /**
     * Return the publication date of the embed.
     *
     * @param string $format PHP date format defaults to the date_format option if not specified.
     *
     * @return string
     */
    function be_get_the_date( string $format = '' ) {

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
     * @param string $format PHP date format defaults to the date_format option if not specified.
     */
    function be_the_date( string $format = '' ) {
        echo be_get_the_date($format);
    }

    /**
     * Display the URL of the embeds author.
     *
     * @param string $format PHP date format defaults to the date_format option if not specified.
     */
    function be_the_date_human( string $format = '' ) {
        $dateTime = be_get_the_datetime();

        if ('' === $dateTime) {
            return;
        }

        $humanTimeDiff = human_time_diff($dateTime->getTimestamp());

        echo esc_html(
            sprintf(__('%s ago', 'betterembed'), $humanTimeDiff)
        );
    }

    /**
     * Return the original embed HTML as determined by WordPress.
     *
     * @return string
     */
    function be_get_the_embed_html() {
        $item = Container::currentItem();

        if (is_null($item)) {
            return '';
        }

        $url = esc_url($item->url());

        if ($url === '') {
            return '';
        }

        //TODO: Consider forcing the cache onto the betterembed CPT instead of the post this is embed into for consistent cache clearing.
        //Do this only in initial betterembed fetch using \WP_Embed::cache_oembed()

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
     */
    function be_the_embed_html() {
        echo be_get_the_embed_html();
    }

}
