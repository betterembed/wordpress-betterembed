<?php

use function BetterEmbed\WordPress\{be_the_content, be_the_title};

?>

<!-- betterembed element -->
<article class="betterembed js-betterembed">
	<button type="button" class="betterembed__show-original-element js-betterembed-show-message"></button>
	<div class="betterembed__item">
		<header>
			<h3 class="betterembed__network">Twitter</h3>
		</header>
		<div class="betterembed__body">
			<div class="betterembed__msg">
				<p>
					<?php echo esc_html(__(
						'With a click on the link below, the original content will be loaded. This can include remote content and you can possibly be tracked from the original provider.',
						'betterembed'
					)); ?>
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
			<figure class="betterembed__media">
				<img src="" alt="">
			</figure>
			<h3 class="betterembed__title">
				<?php be_the_title(); ?>
			</h3>
			<div class="betterembed__text">
				<?php be_the_content(); ?>
				<a class="betterembed__read-more" href="https://twitter.com/acolono/status/1122073732565086208" target="_blank" rel="nofollow noopener">read more</a>
			</div>
			<footer class="betterembed__footer">
            <span class="betterembed__author">
              <a href="https://twitter.com/acolono" target="_blank" rel="nofollow noopener">acolono GmbH</a>
            </span>
				<span>x days ago</span>
			</footer>
		</div>
	</div>
	<div class="betterembed__embed"></div>
</article>
