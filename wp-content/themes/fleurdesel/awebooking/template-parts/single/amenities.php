<?php
/**
 * The template for displaying room amenities in the template-parts/single/content.php template
 *
 * This template can be overridden by copying it to {yourtheme}/awebooking/template-parts/single/amenities.php.
 *
 * @see      http://docs.awethemes.com/awebooking/developers/theme-developers/
 * @author   awethemes
 * @package  AweBooking
 * @version  3.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $room_type;

$amenities = wp_get_post_terms( $room_type->get_id(), 'hotel_amenity' );

if ( ! $amenities ) {
	return;
}
?>
<div class="row">
	<?php foreach ( $amenities as $amenity ) : ?>
		<div class="col-md-<?php echo isset( $GLOBALS['amenities_column'] ) ? esc_attr( $GLOBALS['amenities_column'] ) : 4; ?>">
			<p class="fleurdesel-amenities">
				<?php if ( $icon = get_term_meta( $amenity->term_id, '_icon', true ) ) : ?>
					<?php if ( 'svg' === $icon['type'] || 'image' === $icon['type'] ) : ?>
						<?php echo wp_get_attachment_image( $icon['icon'] ); ?>
					<?php else : ?>
						<i class="<?php echo esc_attr( $icon['type'] . ' ' . $icon['icon'] ); ?>"></i>
					<?php endif; ?>
				<?php else : ?>
					<i class="aficon aficon-checkmark"></i>
				<?php endif; ?>

				<span><?php echo esc_html( $amenity->name ); ?></span>
			</p>
		</div>
	<?php endforeach; ?>
</div>
