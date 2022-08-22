<?php
/**
 * Template for shortcode fleurdesel_packages
 *
 * @package Fleurdesel
 */

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );

if ( ! $atts['packages'] ) {
	return;
}

$packages = explode( ',', $atts['packages'] );
$packages = array_map( 'trim', $packages );

if ( ! $packages ) {
	return;
}

$query_args = array(
	'post_type'   => 'fl_package',
	'post_status' => 'publish',
	'nopaging'    => true,
	'post__in'    => $packages,
	'orderby'     => 'post__in',
);

$query = new WP_Query( $query_args );

if ( ! $query->have_posts() ) {
	return;
}

// Build element classes.
$el_class = $this->getExtraClass( $atts['el_class'] );
$el_class .= vc_shortcode_custom_css_class( $atts['css'], ' ' );
$el_class .= ' fl-packages--' . $atts['layout'];
$el_class .= ' fl-packages--columns-' . $atts['columns'];

$passed_data = compact( 'atts', 'query', 'el_class' );
?>
<div class="fl-packages <?php echo esc_attr( $el_class ); ?>">
	<?php $this->load_template_part( 'packages/' . $atts['layout'] . '.php', $passed_data ); ?>
</div>
