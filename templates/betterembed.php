<?php

use function BetterEmbed\WordPress\{be_get_the_align,
    be_get_the_author_name,
    be_get_the_author_url,
    be_get_the_date,
    be_get_the_embed_html,
    be_get_the_item_type,
    be_get_the_thumbnail_url,
    be_get_the_title,
    be_get_the_url,
    be_the_author_name,
    be_the_author_url,
    be_the_text,
    be_the_date,
    be_the_date_human,
    be_the_item_type,
    be_the_thumbnail,
    be_the_title,
    be_the_url};

    $betterembed_css_classes = implode(' ', array_filter(
        array(
            'wp-block-betterembed-embed',
            'is-provider-' . sanitize_title_with_dashes(be_get_the_item_type()),
            be_get_the_align(),
        )
    ));
    ?>

<figure class="<?php echo esc_attr($betterembed_css_classes); ?>">
    <div class='wp-block-betterembed-embed__top'>
        <div class="wp-block-betterembed-embed__provider">
            <?php be_the_item_type(); ?>
        </div>
        <?php if (be_get_the_embed_html()) : ?>
        <button type="button" class="wp-block-betterembed-embed__switch wp-block-betterembed-embed__switch--hidden">
            <span class='wp-block-betterembed-embed__switch-show'>
                <?php echo esc_html(__('show original', 'betterembed')); ?>
            </span>
            <span class='wp-block-betterembed-embed__switch-hide'>
                <?php echo esc_html(__('hide original', 'betterembed')); ?>
            </span>
        </button>
        <div class="wp-block-betterembed-embed__dialog">
            <p>
                <?php
                echo esc_html(
                    __(
                        'With a click on the link below, the original content will be loaded. This can include remote content and you can possibly be tracked from the original provider.',
                        'betterembed'
                    )
                );
                ?>
            </p>
            <p>
                <strong>{{ Text should be adapted for your GDPR needs }}</strong>
            </p>

            <button type='button' class='wp-block-betterembed-embed__load-remote'>
                <?php echo esc_html(__('show original content', 'betterembed')); ?>
            </button>
            <button type='button' class='wp-block-betterembed-embed__cancel-remote'>
                <?php echo esc_html(__('close', 'betterembed')); ?>
            </button>
        </div>
        <?php endif; ?>
    </div>

    <div class='wp-block-betterembed-embed__body'>

        <?php if (be_get_the_title()) : ?>
            <h3 class="wp-block-betterembed-embed__title">
                <?php be_the_title(); ?>
            </h3>
        <?php endif; ?>

        <?php if (be_get_the_thumbnail_url()) : ?>
            <div class="wp-block-betterembed-embed__media">
                <?php be_the_thumbnail(); ?>
            </div>
        <?php endif; ?>

        <div class="wp-block-betterembed-embed__text">

            <?php be_the_text(); ?>

            <?php if (be_get_the_url()) : ?>
                <a class="wp-block-betterembed-embed__read-more" href="<?php be_the_url(); ?>" target="_blank" rel="nofollow noopener">
                    <?php echo esc_html(__('read more', 'betterembed')); ?>
                </a>
            <?php endif; ?>

        </div>

        <div class="wp-block-betterembed-embed__footer">

            <span class="wp-block-betterembed-embed__author">

                <?php

                if (be_get_the_author_url()) {
                    printf(
                        '<a href="%s" target="_blank" rel="nofollow noopener">',
                        be_get_the_author_url()
                    );
                }

                if (be_get_the_author_name()) {
                    be_the_author_name();
                } else {
                    be_the_author_url();
                }

                if (be_get_the_author_url()) {
                    echo '</a>';
                }

                ?>

            </span>

            <?php if (be_get_the_date()) : ?>
                <time class="wp-block-betterembed-embed__date" datetime="<?php be_the_date('Y-m-d'); ?>">
                    <?php be_the_date_human(); ?>
                </time>

            <?php endif; ?>

        </div>

    </div>
    <?php if (be_get_the_embed_html()) : ?>
    <div class="wp-block-betterembed-embed__embed"></div>
    <script type="text/template" class="wp-block-betterembed-embed__embed-raw"><?php echo wp_json_encode(be_get_the_embed_html()); ?></script>
    <?php endif; ?>
</figure>
