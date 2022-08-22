<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Fleurdesel
 */

$related_query = fleurdesel_related_query( get_the_ID(), array( 'post_type' => 'fl_event' ) );
?>
<?php if ( $related_query->have_posts() ) : ?>
<div class="fleurdesel-related-events fleurdesel-slick-modern show-arrows pt-70">
	<h2 class="post-title text-center mb-50"><?php esc_html_e( 'Related Events', 'fleurdesel' ) ?></h2>
	<div class="row related-events-carousel" data-init="slick" data-fade="false" data-arrows="true" data-dots="false" data-breakpoints='{"lg":3, "md":3, "sm":2, "xs":1}'>
		<?php while ( $related_query->have_posts() ) : $related_query->the_post(); ?>
			<div class="col-sm-2 col-md-4">
				<?php get_template_part( 'template-parts/event-layout/content' ); ?>
			</div>
		<?php endwhile; ?>
	</div>
</div>
<?php endif; ?>
<?php wp_reset_postdata(); ?>
