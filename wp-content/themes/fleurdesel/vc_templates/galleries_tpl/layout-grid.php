<?php
/**
 * Template for shortcode Fleurdesel Galleries: Grid
 *
 * @package Fleurdesel
 */

?>
<div class="dropdown fleurdesel-gallery__filter font-1" data-init="filter">
	<button class="btn btn-secondary dropdown-toggle font-1" hidden type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php esc_html_e( 'All', 'fleurdesel' ) ?></button>
	<div class="dropdown-menu">
		<a class="dropdown-item current" href="#" data-filter="*"><?php esc_html_e( 'All', 'fleurdesel' ) ?></a>
		<?php $i = 0; ?>
		<?php foreach ( $groups as $key => $group ) : ?>
		<a class="dropdown-item" href="#" data-filter=".group-<?php echo esc_attr( $i ); ?>"><?php echo esc_html( $group ); ?></a>
		<?php $i++; endforeach; ?>
	</div>
</div>
<!-- /.fleurdesel-gallery__filter -->

<div class="fleurdesel-gallery__wrap clearfix" data-init="isotope" data-cols="4" data-gap="30" data-gallery="popup">
	<div class="grid-sizer"></div>
	<?php $j = 0; ?>
	<?php foreach ( $galleries as $gallery ) :

		if ( empty( $gallery['image'] ) ) {
			continue;
		}
	?>
	<div class="fleurdesel-gallery__item group-<?php echo esc_attr( $j ); ?>" data-grid="grid-item">
		<div class="fleurdesel-gallery__media">
 			<a href="<?php echo esc_url( wp_get_attachment_url( $gallery['image'], 'full' ) ); ?>">
				<?php echo wp_get_attachment_image( $gallery['image'], 'fleurdesel-medium' ); ?>
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
	<?php $j++; endforeach; ?>
	<!-- /.fleurdesel-gallery__item -->

</div>
