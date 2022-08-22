<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Fleurdesel
 */

$GLOBALS['thumbnail_size'] = 'fleurdesel-large';
$price = Fleurdesel_Package_Functions::get_price();
?>
<div class="col-md-6 col-lg-5">
	<div class="fleurdesel-package">
		<div class="fleurdesel-package__media">
			<?php the_post_thumbnail( 'post-thumbnail' ); ?>
		</div>
		<div class="fleurdesel-package__data">
			<?php the_title( '<h2 class="fleurdesel-package__title h4 mb-20"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
			<p class="fleurdesel-package__desc mb-25"><?php the_excerpt(); ?></p>
			<?php if ( isset( $price['value'] ) && $price['value'] ) : ?>
			<p class="fleurdesel-package__price text-color-1 fz-20 mb-20">
			<?php echo esc_attr( $price['value'] ) ?>
			<?php esc_html_e( '/', 'fleurdesel' ) ?>
			<?php echo esc_attr( $price['unit'] ) ?></p>
			<?php endif; ?>
			<p class="fleurdesel-package__action">
				<a href="<?php echo esc_url( get_permalink() ); ?>" class="btn-view fz-12 font-700 text-uppercase text-gray-8989"><?php esc_html_e( 'View Details', 'fleurdesel' ) ?></a>
			</p>
		</div>
	</div>
</div>
