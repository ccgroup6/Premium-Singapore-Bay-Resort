<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Fleurdesel
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php fleurdesel_post_media(); ?>

	<div class="post-data">
		<?php fleurdesel_posted_on(); ?>

		<?php
		if ( is_single() ) {
			the_title( '<h1 class="post-title">', '</h1>' );
		} else {
			the_title( '<h2 class="post-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		}
		?>

		<?php get_template_part( 'template-parts/post-meta' ); ?>

		<div class="post-content">
			<?php fleurdesel_the_content(); ?>
		</div><!-- .post-content -->
	</div>
</article><!-- #post-## -->
