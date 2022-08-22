<?php
/**
 * Template part for packages grid
 *
 * @package Fleurdesel
 * @var array    $atts
 * @var WP_Query $query
 * @var string   $el_class
 */

$columns = $atts['columns'];
$col_class = 'col-md-' . intval( 12 / $columns );
?>
<div class="row row-eq-height">
	<?php
	while ( $query->have_posts() ) : $query->the_post();
		?>
		<div class="<?php echo esc_attr( $col_class ); ?>">
			<div class="fleurdesel-package">

				<div class="fleurdesel-package__data">
					<?php the_title( '<h2 class="fleurdesel-package__title h4 mb-20"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' ); ?>

					<p class="fleurdesel-package__desc mb-25">
						<?php echo esc_html( wp_trim_words( get_the_content(), 25, '&hellip;' ) ); ?>
					</p>

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
		</div>
		<?php
	endwhile;
	wp_reset_postdata();
	?>
</div>
