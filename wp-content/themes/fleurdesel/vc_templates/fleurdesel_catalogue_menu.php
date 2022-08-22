<?php
/**
 * Template for shortcode Fleurdesel Clients
 *
 * @package Fleurdesel
 */

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );

// Build catalogue_menus group fields.
$catalogue_menus = (array) vc_param_group_parse_atts( $atts['catalogue_menus'] );

$catalogue_menus = array_map( function ( $catalogue_menu ) {
	return shortcode_atts( array(
		'title'             	=> '',
		'description'           => '',
		'price'             	=> '',
		'price_unit'            => '',
		'recommend'             => '',
	), $catalogue_menu );
}, $catalogue_menus );

// Don't output anything if see empty $catalogue_menus.
if ( empty( $catalogue_menus ) ) {
	return;
}

// Build element classes.
$el_class  = $this->getExtraClass( $atts['el_class'] );
$el_class .= vc_shortcode_custom_css_class( $atts['css'], ' ' );
?>
<?php foreach ( $catalogue_menus as $key => $catalogue_menu ) : ?>
<div class="fleurdesel-catalog <?php echo esc_attr( $el_class ); ?>">
	<div class="fleurdesel-catalog__wrap border-bottom">
		<div class="fleurdesel-catalog__left">
			<?php if ( $catalogue_menu['title'] ) : ?>
			<h2 class="fleurdesel-catalog__name h6"><?php echo esc_html( $catalogue_menu['title'] ); ?></h2>
			<?php endif; ?>
			<?php if ( $catalogue_menu['description'] ) : ?>
			<p class="fz-14"><?php echo esc_html( $catalogue_menu['description'] ); ?></p>
			<?php endif; ?>
		</div>
		<!-- .fleurdesel-catalog__left -->
		<?php if ( $catalogue_menu['price'] ) : ?>
		<div class="fleurdesel-catalog__right">
			<div class="fleurdesel-catalog__price font-500 fz-20 text-color-1"><?php echo esc_html( $catalogue_menu['price'] ); ?></div>
			<?php if ( $catalogue_menu['price_unit'] ) : ?>
			<div class="text-uppercase letter-spacing-2 font-700 fz-12 text-black"><?php esc_html_e( '/', 'fleurdesel' ) ?><?php echo esc_html( $catalogue_menu['price_unit'] ); ?></div>
			<?php endif; ?>
		</div>
		<?php endif; ?>
		<!-- .fleurdesel-catalog__right -->
		<?php if ( $catalogue_menu['recommend'] ) : ?>
		<span class="fleurdesel-catalog__recommend letter-spacing-2 text-uppercase bg-color-1 text-white fz-10"><i class="fa fa-star"></i><?php esc_html_e( 'Recommend', 'fleurdesel' ) ?></span>
		<?php endif; ?>
	</div>
</div>
<?php endforeach; ?>
