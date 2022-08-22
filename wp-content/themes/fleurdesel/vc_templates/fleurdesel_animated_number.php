<?php
/**
 * Template for shortcode Fleurdesel animated number
 *
 * @package Fleurdesel
 */

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );

// Build element classes.
$el_class  = $this->getExtraClass( $atts['el_class'] );
$el_class .= vc_shortcode_custom_css_class( $atts['css'], ' ' );
$color = $atts['color'] ? 'style="color:' . esc_attr( $atts['color'] ) . '"' : '';
?>
<div class="fleurdesel-animate text-black fleurdesel-animate--icon text-center <?php echo esc_attr( $el_class ); ?>" <?php echo $color; // phpcs:ignore WordPress.Security.EscapeOutput?>>
	<?php if ( $atts['show_x_icon'] ) : ?>
	<span class="fleurdesel-animate__icon fz-10">
		<i class="<?php echo esc_html( apply_filters( 'fleurdesel_animated_icon', 'fh fh-close' ) ); ?>"></i>
	</span>
	<?php endif; ?>
	<div class="fleurdesel-animate__number font-1 font-700 h1 mb-15" data-counterup-time="1500" data-counterup-delay="30" data-counterup-beginat="0" <?php print $color; // phpcs:ignore WordPress.Security.EscapeOutput?>>
		<?php echo esc_attr( $atts['number'] ) ?>
	</div>
	<?php if ( $atts['text'] ) : ?>
	<div class="fleurdesel-animate__title text-uppercase font-700 fz-14 letter-spacing-2">
		<?php echo esc_attr( $atts['text'] ) ?>
	</div>
	<?php endif; ?>
</div>
