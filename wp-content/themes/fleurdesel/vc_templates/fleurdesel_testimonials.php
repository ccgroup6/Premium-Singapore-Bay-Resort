<?php
/**
 * Template for shortcode Fleurdesel Clients
 *
 * @package Fleurdesel
 */

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );

// Build testimonials group fields.
$testimonials = (array) vc_param_group_parse_atts( $atts['testimonials'] );

$testimonials = array_map( function ( $testimonial ) {
	return shortcode_atts( array(
		'avatar'             	=> '',
		'name'            	    => '',
		'from'             	    => '',
		'title'             	=> '',
		'content'             	=> '',
	), $testimonial );
}, $testimonials );

// Don't output anything if see empty $testimonials.
if ( empty( $testimonials ) || ( count( $testimonials ) === 1 && ! $testimonials[0]['avatar'] ) ) {
	return;
}

// Build element classes.
$el_class  = $this->getExtraClass( $atts['el_class'] );
$el_class .= vc_shortcode_custom_css_class( $atts['css'], ' ' );
?>
<?php if ( ! $atts['hidden_form'] ) : ?>
<div id="testimonials-form" class="fleurdesel-testimonial-popup white-popup container mfp-hide fleurdesel-bg">
	<div class="fleurdesel-testimonial-popup__wrap">
		<?php echo do_shortcode( $atts['form_content'] ); ?>
	</div>
</div>
<?php endif; ?>

<div class="mb-50">
	<div class="clearfix <?php echo esc_attr( $el_class ); ?>" data-init="masonry" data-cols="4" data-gap="30">
		<div class="grid-sizer"></div>
		<?php if ( ! $atts['hidden_form'] ) : ?>
		<div class="fleurdesel-testimonial" data-grid="grid-item">
			<div class="fleurdesel-testimonial__item fleurdesel-bg text-center">
				<div class="fleurdesel-testimonial__wrap">
					<p class="fleurdesel-testimonial__icon fleurdesel-testimonial__icon--no-quote font-1 fz-40 text-color-1 mb-30">
						<i class="<?php echo esc_attr( apply_filters( 'fleurdesel_testimonial_book_icon', 'fh fh-book' ) ); ?>"></i>
					</p>
					<?php if ( $atts['form_title'] ) : ?>
					<h2 class="fleurdesel-testimonial__title h4"><?php echo wp_kses( $atts['form_title'], '</ br>', array( '' ) ); ?></h2>
					<?php endif; ?>

					<?php if ( $atts['form_description'] ) : ?>
					<p class="fleurdesel-testimonial__desc"><?php echo esc_html( $atts['form_description'] ) ?></p>
					<?php endif; ?>

					<p class="fleurdesel-testimonial__action">
						<a href="#testimonials-form" class="open-popup-link btn-view btn-primary fz-12 font-500 text-uppercase"><?php esc_html_e( 'Write in guest book', 'fleurdesel' ); ?></a>
					</p>
				</div>
			</div>
		</div>
		<?php endif; ?>
		<!-- /.fleurdesel-testimonial -->

		<?php foreach ( $testimonials as $testimonial ) :

			if ( empty( $testimonial['avatar'] ) ) {
				continue;
			}
		?>
		<div class="fleurdesel-testimonial" data-grid="grid-item">
			<div class="fleurdesel-testimonial__item text-center">
				<div class="fleurdesel-testimonial__wrap">
					<p class="fleurdesel-testimonial__icon font-1 text-color-1"></p>

					<?php if ( $testimonial['title'] ) : ?>
					<h2 class="fleurdesel-testimonial__title h4"><?php echo esc_html( $testimonial['title'] ) ?></h2>
					<?php endif; ?>

					<?php if ( $testimonial['title'] ) : ?>
					<p class="fleurdesel-testimonial__desc"><?php echo esc_html( $testimonial['content'] ) ?></p>
					<?php endif; ?>

					<?php if ( $testimonial['avatar'] ) : ?>
					<p class="fleurdesel-testimonial__avatar">
						<?php echo wp_get_attachment_image( $testimonial['avatar'], 'full' ); ?>
					</p>
					<?php endif; ?>

					<?php if ( $testimonial['name'] ) : ?>
					<p class="fleurdesel-testimonial__name font-700"><?php echo esc_html( $testimonial['name'] ) ?></p>
					<?php endif; ?>

					<?php if ( $testimonial['from'] ) : ?>
					<p class="fleurdesel-testimonial__contry fz-14"><?php echo esc_html( $testimonial['from'] ) ?></p>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php endforeach; ?>
		<!-- /.fleurdesel-testimonial -->

	</div>
</div>
