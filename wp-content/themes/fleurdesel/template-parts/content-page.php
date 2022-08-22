<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Fleurdesel
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php fleurdesel_post_media(); ?>

	<div class="post-data">
		<div class="post-content">
			<?php the_content(); ?>
		</div><!-- .post-content -->
	</div>
</article><!-- #post-## -->
