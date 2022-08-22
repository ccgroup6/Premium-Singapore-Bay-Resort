<?php
/**
 * Template for shortcode Fleurdesel Clients
 *
 * @package Fleurdesel
 */

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );

// Build features group fields.
$features = (array) vc_param_group_parse_atts( $atts['features'] );

// Build element classes.
$el_class  = $this->getExtraClass( $atts['el_class'] );
$el_class .= vc_shortcode_custom_css_class( $atts['css'], ' ' );

$button = vc_build_link( $atts['button'] );

$active = '';
if ( $atts['active'] ) {
	$el_class .= ' active';
	$active = 'active';
}
?>
<div class="fleurdesel-pricing-table text-center <?php echo esc_attr( $el_class ); ?>">
	<div class="fleurdesel-pricing-table__wrap <?php echo esc_attr( $active ); ?>">
		<div class="fleurdesel-pricing-table__header">
			<?php if ( $atts['subtitle'] ) : ?>
				<p class="fleurdesel-pricing-table__meta"><?php echo esc_html( $atts['subtitle'] ); ?></p>
			<?php endif; ?>

			<?php if ( $atts['title'] ) : ?>
				<h2 class="fleurdesel-pricing-table__title"><?php echo esc_html( $atts['title'] ); ?></h2>
			<?php endif; ?>

			<?php if ( $atts['desc'] ) : ?>
				<p class="fleurdesel-pricing-table__desc"><?php echo wp_kses_post( nl2br( $atts['desc'] ) ); ?></p>
			<?php endif; ?>
		</div>
		<!-- /.fleurdesel-pricing-table__header -->

		<?php if ( $atts['price'] ) : ?>
			<div class="fleurdesel-pricing-table__price">
				<p class="mb-0 fz-40"><b><?php echo esc_html( $atts['price'] ); ?></b></p>

				<?php if ( $atts['price_unit'] ) : ?>
					<p class="fz-14 font-700 letter-spacing-2 text-black"><?php echo esc_html( $atts['price_unit'] ); ?></p>
				<?php endif; ?>
			</div>
			<!-- /.fleurdesel-pricing-table__price -->
		<?php endif; ?>

		<div class="fleurdesel-pricing-table__info">
			<?php foreach ( $features as $feature ) :
				if ( empty( $feature['text'] ) ) {
					continue;
				}
				?>
				<p><?php echo wp_kses_post( html_entity_decode( $feature['text'] ) ); ?></p>
			<?php endforeach; ?>
		</div>
		<!-- /.fleurdesel-pricing-table__info -->

		<?php if ( ! empty( $button['title'] ) ) : ?>
			<div class="fleurdesel-pricing-table__action">
				<a href="<?php echo esc_url( $button['url'] ); ?>" class="btn-primary btn-view btn-outline-primary" <?php if ( $button['target'] ) { ?>target="<?php echo esc_attr( $button['target'] ); ?>"<?php }; ?> <?php if ( $button['rel'] ) { ?>rel="<?php echo esc_attr( $button['rel'] ); ?>"<?php }; ?>><?php echo esc_html( $button['title'] ); ?></a>
			</div>
		<?php endif; ?>
	</div>
</div>
