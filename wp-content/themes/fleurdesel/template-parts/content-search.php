<?php
/**
 * Template part for displaying results in search pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Fleurdesel
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php fleurdesel_post_media(); ?>

	<div class="post-data">
		<?php if ( 'post' == get_post_type() ) : ?>
			<?php fleurdesel_posted_on(); ?>
		<?php endif; ?>

		<?php the_title( '<h2 class="post-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>

		<?php if ( 'post' == get_post_type() ) : ?>
			<?php get_template_part( 'template-parts/post-meta' ); ?>
		<?php endif; ?>

		<div class="post-content">
			<?php the_excerpt(); ?>
		</div><!-- .post-content -->
	</div>
</article><!-- #post-## -->
