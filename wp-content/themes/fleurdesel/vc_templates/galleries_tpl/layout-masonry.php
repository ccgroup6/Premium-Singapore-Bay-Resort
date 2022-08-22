<?php
/**
 * Template for shortcode Fleurdesel Galleries: Masonry
 *
 * @package Fleurdesel
 */

?>
<div class="dropdown fleurdesel-gallery__filter font-1" data-init="filter">
	<button class="btn btn-secondary dropdown-toggle font-1" hidden type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php esc_html_e( 'All', 'fleurdesel' ) ?></button>
	<div class="dropdown-menu">
		<a class="dropdown-item current" href="#" data-filter="*"><?php esc_html_e( 'All', 'fleurdesel' ) ?></a>
		<?php foreach ( $groups as $key => $group ) : ?>
		<a class="dropdown-item" href="#" data-filter=".<?php echo esc_attr( $key ) ?>"><?php echo esc_html( $group ); ?></a>
		<?php endforeach; ?>
	</div>
</div>
<!-- /.fleurdesel-gallery__filter -->

<div class="fleurdesel-gallery__wrap clearfix" data-init="isotope" data-cols="4" data-gap="30" data-gallery="popup">
	<div class="grid-sizer"></div>

	<?php foreach ( $galleries as $gallery ) :

		if ( empty( $gallery['image'] ) ) {
			continue;
		}
	?>
	<div class="fleurdesel-gallery__item <?php echo sanitize_title( $gallery['group'] ); // phpcs:ignore WordPress.Security.EscapeOutput?>" data-grid="grid-item">
		<div class="fleurdesel-gallery__media">
			<a href="<?php echo esc_url( wp_get_attachment_url( $gallery['image'], 'full' ) ); ?>">
				<?php echo wp_get_attachment_image( $gallery['image'], 'full' ); ?>
			</a>
		</div>
		<!-- /.fleurdesel-gallery__media -->
		<?php if ( $gallery['title'] ) : ?>
		<div class="fleurdesel-gallery__data">
			<h2 class="fleurdesel-gallery__title h4"><?php echo esc_html( $gallery['title'] ) ?></h2>
		</div>
		<?php endif; ?>
		<!-- /.fleurdesel-gallery__ -->
	</div>
	<?php endforeach; ?>
	<!-- /.fleurdesel-gallery__item -->

</div>
