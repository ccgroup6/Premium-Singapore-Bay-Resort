<?php
/**
 * Template for shortcode Fleurdesel Clients
 *
 * @package Fleurdesel
 */

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );

// Build testimonials group fields.
$testimonials = (array) vc_param_group_parse_atts( $atts['testimonials'] );

$testimonials = array_map( function ( $testimonial ) {
	return shortcode_atts( array(
		'avatar'             	=> '',
		'name'            	    => '',
		'from'             	    => '',
		'content'             	=> '',
	), $testimonial );
}, $testimonials );

// Don't output anything if see empty $testimonials.
if ( empty( $testimonials ) ) {
	return;
}

$atts['_slick_breakpoints'] = isset( $atts['_slick_breakpoints'] ) ? $atts['_slick_breakpoints'] : 'lg:1|md:1|sm:1|xs:1';
$slick_atts = Extras_Composer_Utils::parse_slick_atts( $atts );

// Build element classes.
$el_class  = $this->getExtraClass( $atts['el_class'] );
$el_class .= vc_shortcode_custom_css_class( $atts['css'], ' ' );

// Pass args to template part.
$pass_args = array(
	'atts'      		=> $atts,
	'testimonials'    	=> $testimonials,
);
?>

<div class="fleurdesel-testimonial fleurdesel-slick-modern <?php echo esc_attr( $el_class ); ?>" <?php echo $this->build_attributes( $slick_atts ); // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<?php $this->load_template_part( 'testimonials_carousel_tpl/layout-' . $atts['layout'] . '.php', $pass_args ); ?>
</div>
