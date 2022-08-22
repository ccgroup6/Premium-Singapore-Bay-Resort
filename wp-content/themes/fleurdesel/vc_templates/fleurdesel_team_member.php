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

?>
<div class="fleurdesel-team__item text-center <?php echo esc_attr( $el_class ); ?>">
	<?php if ( $atts['avatar'] ) : ?>
	<div class="fleurdesel-team__img">
		<?php echo wp_get_attachment_image( $atts['avatar'], 'full' ); ?>
	</div>
	<?php endif; ?>
	<!-- /.fleurdesel-team__img -->

	<div class="fleurdesel-team__info">
		<?php if ( $atts['name'] ) : ?>
		<h2 class="fleurdesel-team__title h4"><?php echo esc_html( $atts['name'] ); ?></h2>
		<?php endif; ?>

		<?php if ( $atts['position'] ) : ?>
		<p class="fleurdesel-team__meta text-uppercase"><?php echo esc_html( $atts['position'] ); ?></p>
		<?php endif; ?>

		<p class="fleurdesel-team__action">
			<?php if ( $atts['facebook'] ) : ?>
			<a href="<?php echo esc_url( $atts['facebook'] ) ?>"><i class="fa fa-facebook"></i></a>
			<?php endif; ?>

			<?php if ( $atts['twitter'] ) : ?>
			<a href="<?php echo esc_url( $atts['twitter'] ) ?>"><i class="fa fa-twitter"></i></a>
			<?php endif; ?>

			<?php if ( $atts['instagram'] ) : ?>
			<a href="<?php echo esc_url( $atts['instagram'] ) ?>"><i class="fa fa-instagram"></i></a>
			<?php endif; ?>

			<?php if ( $atts['pinterest'] ) : ?>
			<a href="<?php echo esc_url( $atts['pinterest'] ) ?>"><i class="fa fa-pinterest"></i></a>
			<?php endif; ?>

			<?php if ( $atts['tumblr'] ) : ?>
			<a href="<?php echo esc_url( $atts['tumblr'] ) ?>"><i class="fa fa-tumblr"></i></a>
			<?php endif; ?>
		</p>
	</div>
	<!-- /.fleurdesel-team__info -->
</div>
