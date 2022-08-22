<?php
/**
 * Template for shortcode Fleurdesel Check Availability.
 *
 * @package Fleurdesel
 */

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );

// Build element classes.
$el_class  = $this->getExtraClass( $atts['el_class'] );
$el_class .= vc_shortcode_custom_css_class( $atts['css'], ' ' );

// Back-up for AweBooking 3.1
$template = $atts['layout'];

$attributes = [
	'template'        => $template,
	'hotel_location'  => ( ( 'modern' != $template ) && $atts['location'] ) ? true : false,
	'occupancy'       => $atts['occupancy'] ? true : false,
];

$shortcode_atts = '';
foreach ( $attributes as $attribute => $value ) {
	$shortcode_atts .= $attribute . '="' . esc_attr( $value ) . '" ';
}

?>
<div class="fl-check-availability <?php echo esc_attr( $el_class ); ?>">
	<?php echo do_shortcode( '[awebooking_search_form ' . $shortcode_atts . ']' ); ?>
</div>
