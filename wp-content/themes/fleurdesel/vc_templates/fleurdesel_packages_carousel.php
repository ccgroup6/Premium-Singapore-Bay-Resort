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

// Slick args.
$atts['_slick_breakpoints'] = isset( $atts['_slick_breakpoints'] ) ? $atts['_slick_breakpoints'] : 'lg:3|md:3|sm:1|xs:1';
$slick_atts = Extras_Composer_Utils::parse_slick_atts( $atts );

// Build element classes.
$el_class = $this->getExtraClass( $atts['el_class'] );
$el_class .= vc_shortcode_custom_css_class( $atts['css'], ' ' );

$passed_data = compact( 'atts', 'query', 'el_class' );
?>
<div class="fl-packages fl-packages--carousel fleurdesel-slick-modern <?php echo esc_attr( $el_class ); ?>">
	<div class="row row-eq-height no-gutter" <?php echo $this->build_attributes( $slick_atts ); // phpcs:ignore WordPress.Security.EscapeOutput ?>>
		<div class="col-md-4 no-gutter">
			<?php
			$i = 0;
			while ( $query->have_posts() ) : $query->the_post();
				?>
				<div class="fleurdesel-package border-right border-right--hover pl-30 pr-30 pt-20 pb-50 mb-0">

					<div class="fleurdesel-package__data">
						<?php the_title( '<h2 class="fleurdesel-package__title h5 mb-20"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' ); ?>

						<?php /*<p class="fleurdesel-package__desc mb-25">
							<?php echo esc_html( wp_trim_words( get_the_content(), 25, '&hellip;' ) ); ?>
						</p>*/ ?>

						<?php if ( $price = Fleurdesel_Package_Functions::get_price() ) : ?>
							<p class="fleurdesel-package__price text-color-1 fz-20 mb-20">
								<?php printf( '%s/%s', esc_html( $price['value'] ), esc_html( $price['unit'] ) ); ?>
							</p>
						<?php endif; ?>

						<p class="fleurdesel-package__action">
							<a href="<?php the_permalink(); ?>" class="btn-view fz-12 font-700 text-uppercase text-gray-8989"><?php esc_html_e( 'View Details', 'fleurdesel' ); ?></a>
						</p>
					</div>

				</div>

				<?php
				if ( $i % 2 ) {
					echo '</div> <!-- End column --> <div class="col-md-4 no-gutter">';
				}

				$i++;
			endwhile;
			wp_reset_postdata();
			?>
		</div>
		<!-- /.fleurdesel-package -->
	</div>
</div>
