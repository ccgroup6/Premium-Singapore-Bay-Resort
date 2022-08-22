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
$col_class = 'col-md-6 col-lg-' . intval( 12 / $columns );
?>
<div class="row row-eq-height no-gutter">
	<?php
	while ( $query->have_posts() ) : $query->the_post();
		?>
		<div class="no-gutter <?php echo esc_attr( $col_class ); ?>">
			<div class="fleurdesel-package border-right border-right--hover pl-30 pr-30 pt-20 pb-50 mb-0">

				<div class="fleurdesel-package__data">
					<?php the_title( '<h2 class="fleurdesel-package__title h5 mb-20"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' ); ?>

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
