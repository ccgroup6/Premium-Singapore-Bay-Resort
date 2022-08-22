<?php
/**
 * Template for shortcode Fleurdesel Clients
 *
 * @package Fleurdesel
 */

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );

// Build clients group fields.
$clients = (array) vc_param_group_parse_atts( $atts['clients'] );

$clients = array_map( function ( $client ) {
	return shortcode_atts( array(
		'image'             	=> '',
		'name'            	=> '',
		'link'             	=> '',
	), $client );
}, $clients );

// Don't output anything if see empty $clients.
if ( empty( $clients ) || ( count( $clients ) === 1 && ! $clients[0]['image'] ) ) {
	return;
}

$atts['_slick_breakpoints'] = isset( $atts['_slick_breakpoints'] ) ? $atts['_slick_breakpoints'] : 'lg:5|md:5|sm:2|xs:1';
$slick_atts = Extras_Composer_Utils::parse_slick_atts( $atts );

// Build element classes.
$el_class  = $this->getExtraClass( $atts['el_class'] );
$el_class .= vc_shortcode_custom_css_class( $atts['css'], ' ' );
?>
<div class="fleurdesel-client <?php echo esc_attr( $el_class ); ?>" <?php echo $this->build_attributes( $slick_atts ); // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<?php foreach ( $clients as $client ) :

		if ( empty( $client['image'] ) ) {
			continue;
		}
		?>
		<div class="fleurdesel-client__item text-center">
			<div class="fleurdesel-client__wrap">
				<a href="<?php echo esc_url( $client['link'] ) ?>" title="<?php echo esc_html( $client['name'] ) ?>">
					<?php echo wp_get_attachment_image( $client['image'], 'full' ); ?>
				</a>
			</div>
		</div>
		<?php endforeach; ?>
</div>
