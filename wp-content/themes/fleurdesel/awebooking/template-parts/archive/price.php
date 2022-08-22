<?php
/**
 * The template for displaying price in the template-parts/archive/content.php template
 *
 * This template can be overridden by copying it to {yourchild-theme}/awebooking/template-parts/archive/price.php.
 *
 * @see      http://docs.awethemes.com/awebooking/developers/theme-developers/
 * @author   awethemes
 * @package  AweBooking
 * @version  3.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $room_type;

if ( ! $room_type->get( 'rack_rate' ) ) {
	return;
}

?>

<p class="fz-14 font-700 letter-spacing-3 text-uppercase text-color-1">
	<?php
	/* translators: %s room price */
	printf( esc_html__( 'Start from %s/night', 'awebooking' ), '<span>' . abrs_format_price( $room_type->get( 'rack_rate' ) ) . '</span>' ); // phpcs:ignore WordPress.Security.EscapeOutput
	?>
</p>
