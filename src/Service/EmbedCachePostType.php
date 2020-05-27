<?php

namespace BetterEmbed\WordPress\Service;

use BetterEmbed\WordPress\Plugin;
use WP_Post;

class EmbedCachePostType implements Service
{

    /** @var Plugin */
    protected $plugin;

    public function init( Plugin $plugin) {
        $this->plugin = $plugin;
        $this->registerPostType();

        add_filter('pre_delete_post', array( $this, 'deleteAttachmentsWithPost' ), 10, 3);

        /**
         * TODO: Consider skipping the trash to immediately delete this post type?
         * But since this post type is private and will probably only ever get deleted by this plugin an alternative
         * is to just handle this by always using `wp_delete_attachment()` with the `$force_delete` parameter.
         */

        /**
         * TODO: Consider hiding attachments for this post type from the Media Library.
         */
    }

    protected function registerPostType() {

        /**
         * Enables UI für the Cache Post Type for debugging.
         *
         * @since 0.0.1-alpha
         *
         * @param int $enabled If the UI is enabled. Default `false`.
         */
        $showUi = (bool) apply_filters(
            $this->plugin->namespace('showui'),
            $this->plugin->betterEmbedDebugEnabled()
        );

        register_post_type(
            $this->postTypeKey(),
            array(
                'labels'           => array(
                    'name'          => __('BetterEmbeds', 'betterembed'),
                    'singular_name' => __('BetterEmbed', 'betterembed'),
                ),
                'description'      => __('Cache for Embeds', 'betterembed'),
                'public'           => false,
                'hierarchical'     => false,
                'show_ui'          => $showUi,
                'menu_icon'        => 'dashicons-editor-code',
                'rewrite'          => false,
                'query_var'        => false,
                'delete_with_user' => false,
                'can_export'       => false,
                'supports'         => array( 'title', 'editor', 'thumbnail' ),
            )
        );
    }

    /**
     * Before deleting a post delete all associated attachments and prevent post deletion on failure.
     *
     * @param bool|null $delete
     * @param WP_Post $post
     * @param bool $force_delete
     *
     * @return bool|null
     */
    public function deleteAttachmentsWithPost( $delete, WP_Post $post, bool $force_delete) {

        if ($post->post_type !== $this->postTypeKey()) {
            return $delete;
        }

        $attachments = get_attached_media('', $post->ID);

        foreach ($attachments as $attachment) {
            $success = wp_delete_attachment($attachment->ID, 'true');

            //If deleting the attachments fails abort the post deletion to prevent stray attachments.
            if (!$success || is_null($success)) {
                return false;
            }
        }

        return $delete;
    }

    protected function postTypeKey(): string {
        return $this->plugin->prefix('cache');
    }
}
