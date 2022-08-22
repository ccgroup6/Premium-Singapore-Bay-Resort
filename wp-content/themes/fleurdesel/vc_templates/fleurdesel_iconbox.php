<?php
/**
 * Template for shortcode Fleurdesel iconbox
 *
 * @package Fleurdesel
 */

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
$link = vc_build_link( $atts['link'] );

// Build element classes.
$el_class  = $this->getExtraClass( $atts['el_class'] );
$el_class .= vc_shortcode_custom_css_class( $atts['css'], ' ' );

switch ( $atts['layout'] ) {
	case 'horizontal':
		$el_class .= ' fleurdesel-iconbox--horizontal';
		break;

	case 'vertical_border':
		$el_class .= ' fleurdesel-iconbox--border text-center';
		break;

	default:
		$el_class .= ' text-center';
}

$icon = '';

if ( 'image' == $atts['type'] ) {
	if ( $atts['image'] ) {
		$icon = wp_get_attachment_image( $atts['image'], 'post-thumbnail' );
	}
} else {
	$icon = sprintf( '<i class="%s"></i>', esc_attr( $atts[ 'icon_' . $atts['icon_type'] ] ) );
}

$color_attr = '';
if ( $atts['color'] ) {
	$color_attr = sprintf( ' style="color: %s"', esc_attr( $atts['color'] ) );
}

$pass_args = compact( 'atts', 'link', 'el_class', 'icon', 'color_attr' );

$this->load_template_part( 'iconbox/' . $atts['layout'] . '.php', $pass_args );
