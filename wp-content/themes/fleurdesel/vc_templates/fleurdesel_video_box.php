<?php
/**
 * Template for shortcode Fleurdesel video box
 *
 * @package Fleurdesel
 */

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );

if ( ! $atts['video_url'] ) {
	return;
}

// Build element classes.
$el_class  = $this->getExtraClass( $atts['el_class'] );
$el_class .= vc_shortcode_custom_css_class( $atts['css'], ' ' );
?>
<div class="video-box <?php echo esc_attr( $el_class ); ?>" data-init="video-box" data-effect="popup">
	<?php if ( $atts['video_image'] ) : ?>
		<a href="#" class="video-box__button"><i class="fa fa-play-circle"></i></a>

		<div class="video-box__overlay" style="background-image: url(<?php echo esc_url( wp_get_attachment_image_url( $atts['video_image'], 'full' ) ); ?>);"></div>
	<?php endif; ?>

	<div class="video-box__video">
		<?php echo wp_oembed_get( $atts['video_url'], array( 'height' => (int) $atts['video_height'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</div>
</div>
