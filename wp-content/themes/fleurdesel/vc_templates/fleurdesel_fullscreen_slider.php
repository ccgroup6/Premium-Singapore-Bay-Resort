<?php
/**
 * Template for shortcode Fleurdesel Full screen slider.
 *
 * @package Fleurdesel
 */

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );

// Build sliders group fields.
$sliders = (array) vc_param_group_parse_atts( $atts['sliders'] );

$sliders = array_map( function ( $slider ) {
	return shortcode_atts( array(
		'image'             	=> '',
		'title'            	    => '',
		'link'					=> '',
	), $slider );
}, $sliders );

// Don't output anything if see empty $sliders.
if ( empty( $sliders ) || ( count( $sliders ) === 1 && ! $sliders[0]['image'] ) ) {
	return;
}

// Build element classes.
$el_class  = $this->getExtraClass( $atts['el_class'] );
$el_class .= vc_shortcode_custom_css_class( $atts['css'], ' ' );
?>

<div class="fleurdesel-slider <?php echo esc_attr( $el_class ); ?>">
	<span class="fleurdesel-slider__icon fleurdesel-slider__icon--zoom-in"><i class="fh fh-full"></i></span>
	<span class="fleurdesel-slider__icon fleurdesel-slider__icon--close"><i class="fh fh-close"></i></span>

	<div class="fleurdesel-slider__wrap" data-init="slick" data-autoplay="false">
		<?php foreach ( $sliders as $slider ) :

			if ( empty( $slider['image'] ) ) {
				continue;
			}
		?>
		<div class="fleurdesel-slider__item">
			<?php if ( $slider['image'] ) : ?>
			<span class="fleurdesel-slider__img-bg" style="background-image: url(<?php echo wp_get_attachment_url( $slider['image'] ); ?>);"></span>
			<?php endif; ?>

			<?php if ( $slider['title'] ) : ?>
			<h2 class="fleurdesel-slider__title h5 text-center"><a href="<?php echo isset( $slider['link'] ) ? esc_url( $slider['link'] ) : ''; ?>"><?php echo esc_html( $slider['title'] ) ?></a></h2>
			<?php endif; ?>
		</div>
		<?php endforeach; ?>
	</div>
</div>
