<?php
/**
 * Template for shortcode Fleurdesel service
 *
 * @package Fleurdesel
 */

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );

// Build element classes.
$el_class  = $this->getExtraClass( $atts['el_class'] );
$el_class .= vc_shortcode_custom_css_class( $atts['css'], ' ' );
?>
<div class="fleurdesel-service__item <?php echo esc_url( $el_class ) ?>">
	<?php if ( $atts['image'] ) : ?>
	<div class="fleurdesel-service__img">
		<?php echo wp_get_attachment_image( $atts['image'], 'full' ); ?>
	</div>
	<?php endif; ?>
	<!-- /.fleurdesel-service__img -->

	<div class="fleurdesel-service__info text-center">
		<?php if ( $atts['subtitle'] ) : ?>
		<p class="fleurdesel-service__meta text-white font-600 fz-14 text-uppercase"><?php echo esc_html( $atts['subtitle'] ) ?></p>
		<?php endif; ?>

		<?php if ( $atts['title'] ) : ?>
		<h2 class="fleurdesel-service__title h3 text-white"><?php echo esc_html( $atts['title'] ) ?></h2>
		<?php endif; ?>

		<?php if ( $atts['link'] ) : ?>
		<p class="fleurdesel-service__action">
			<a href="<?php echo esc_url( $atts['link'] ); ?>" class="btn-view fz-12 font-600 btn-primary text-uppercase">
				<?php esc_html_e( 'View More', 'fleurdesel' ); ?>
			</a>
		</p>
		<?php endif; ?>
	</div>
	<!-- /.fleurdesel-service__info -->
</div>
<!-- /.fleurdesel-service__item -->
