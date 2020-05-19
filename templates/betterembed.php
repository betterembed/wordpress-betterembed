<?php

use function BetterEmbed\WordPress\{be_get_the_author_name,
	be_get_the_author_url,
	be_get_the_embed_html,
	be_get_the_title,
	be_get_the_url,
	be_the_author_name,
	be_the_author_url,
	be_the_body,
	be_the_date_human,
	be_the_item_type,
	be_the_thumbnail,
	be_the_title,
	be_the_url};
?>

<!-- betterembed element -->
<article class="betterembed js-betterembed">
	<button type="button" class="betterembed__show-original-element js-betterembed-show-message"></button>
	<div class="betterembed__item">
		<header>
			<h3 class="betterembed__network"><?php be_the_item_type(); ?></h3>
		</header>
		<div class="betterembed__body">

			<?php if( be_get_the_embed_html() ): ?>
				<div class="betterembed__msg">
					<p>
						<?php echo esc_html(
							__(
								'With a click on the link below, the original content will be loaded. This can include remote content and you can possibly be tracked from the original provider.',
								'betterembed'
							)
						); ?>
						</p>
					<p>
						<strong>{{ Text should be adopted for your GDPR needs }}</strong>
					</p>
					<a href="javascript:;" class="betterembed__msg-button-primary js-betterembed-load-remote">
						<?php echo esc_html(__('show original content', 'betterembed' )); ?>
					</a>
					<a href="javascript:;" class="betterembed__msg-button-secondary js-betterembed-close">
						<?php echo esc_html(__('close', 'betterembed' )); ?>
					</a>
				</div>
			<?php endif; ?>

			<?php if( be_get_the_url() ): ?>
				<figure class="betterembed__media">
					<?php be_the_thumbnail(); ?>
				</figure>
			<?php endif; ?>

			<?php if( be_get_the_title() ): ?>
				<h3 class="betterembed__title">
					<?php be_the_title(); ?>
				</h3>
			<?php endif; ?>

			<div class="betterembed__text">

				<?php be_the_body(); ?>

				<?php if( be_get_the_url() ): ?>
					<a class="betterembed__read-more" href="<?php be_the_url(); ?>" target="_blank" rel="nofollow noopener">
						<?php echo esc_html(__('read more', 'betterembed' )); ?>
					</a>
				<?php endif; ?>

			</div>

			<footer class="betterembed__footer">
				<span class="betterembed__author">

					<?php if( be_get_the_author_url() ): ?>
						<a href="<?php be_the_author_url(); ?>" target="_blank" rel="nofollow noopener">
					<?php endif; ?>

						<?php if( be_get_the_author_name() ): ?>
					  		<?php be_the_author_name(); ?>
						<? else: ?>
							<?php be_the_author_url(); ?>
						<?php endif; ?>

					<?php if( be_get_the_author_url() ): ?>
						</a>
					<?php endif; ?>

				</span>

				<?php be_the_date_human(); ?>
			</footer>

		</div>
	</div>
	<div class="betterembed__embed"></div>
</article>
