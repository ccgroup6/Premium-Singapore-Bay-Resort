<?php
/**
 * Template part for feature image.
 *
 * @package Fleurdesel
 */

if ( ! has_post_thumbnail() ) {
	return;
}
?>
<div class="post-media">
	<div class="post-image">
		<?php
		if ( is_single() ) :
			the_post_thumbnail( isset( $GLOBALS['thumbnail_size'] ) ? $GLOBALS['thumbnail_size'] : 'post-thumbnail' );
		else : ?>
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( isset( $GLOBALS['thumbnail_size'] ) ? $GLOBALS['thumbnail_size'] : 'post-thumbnail' ); ?>
			</a>
		<?php endif; ?>
	</div>
</div>
