<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Fleurdesel
 */

$layout = fleurdesel_option( 'event_layout' );
get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="row">
			<?php
			if ( have_posts() ) : ?>

				<?php

				/**
				 * Begin the loop hook.
				 */
				do_action( 'fleurdesel_begin_the_event_loop' );

				/* Start the Loop */
				while ( have_posts() ) : the_post();

						fleurdesel_before_custom_column( 'event_column' );

							/*
							 * Include the Post-Format-specific template for the content.
							 * If you want to override this in a child theme, then include a file
							 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
							 */
							get_template_part( 'template-parts/event-layout/content', $layout );

						fleurdesel_after_custom_column( 'event_column' );

				endwhile;

				/**
				 * End the loop hook.
				 */
				do_action( 'fleurdesel_end_the_event_loop' );

			else :

				get_template_part( 'template-parts/content', 'none' );

			endif; ?>
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
