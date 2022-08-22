<?php
/**
 * Template for shortcode Fleurdesel video box
 *
 * @package Fleurdesel
 */

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );

if ( ! $atts['title'] && ! $atts['subtitle'] ) {
	return;
}

// Build element classes.
$el_class  = $this->getExtraClass( $atts['el_class'] );
$el_class .= vc_shortcode_custom_css_class( $atts['css'], ' ' );
$el_class .= ' text-' . $atts['align'];

$title = $atts['title'];
$subtitle = $atts['subtitle'];

if ( $title ) {
	$classes = array( 'fleurdesel__title', 'h1', 'fz-' . $atts['title_size'] );

	if ( 'white' == $atts['style'] ) {
		$classes[] = 'text-white';
	}

	$title = sprintf(
		'<h2 class="%s">%s</h2>',
		esc_attr( implode( ' ', $classes ) ),
		esc_html( $title )
	);
}

if ( $subtitle ) {
	$classes = array( 'fleurdesel__meta', 'font-600' );

	if ( 'white' == $atts['style'] ) {
		$classes[] = 'text-white';
	} else {
		$classes[] = 'text-color-1';
	}

	$subtitle = sprintf(
		'<p class="%s">%s</p>',
		esc_attr( implode( ' ', $classes ) ),
		esc_html( $subtitle )
	);
}

if ( 'title_first' == $atts['order'] ) {
	$output = $title . $subtitle;
} else {
	$output = $subtitle . $title;
}
?>
<div class="fleurdesel-heading <?php echo esc_attr( $el_class ); ?>">
	<?php echo wp_kses_post( $output ); ?>
</div>
