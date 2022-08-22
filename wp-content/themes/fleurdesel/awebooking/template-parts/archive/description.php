<?php
/**
 * The template for displaying description in the template-parts/archive/content.php template
 *
 * This template can be overridden by copying it to {yourchild-theme}/awebooking/template-parts/archive/description.php.
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

if ( ! $description = $room_type->get( 'short_description' ) ) {
	return;
}

?>
<div class="fleurdesel-room__desc mb-20"><?php echo wp_trim_words( $description, 35, ' ' ); ?></div>
