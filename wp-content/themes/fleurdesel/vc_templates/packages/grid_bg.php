<?php
/**
 * Template part for packages grid_bg
 *
 * @package Fleurdesel
 * @var array    $atts
 * @var WP_Query $query
 * @var string   $el_class
 */

?>
<div class="clearfix">
	<?php
	while ( $query->have_posts() ) : $query->the_post();
		?>
		<div class="fleurdesel-package fleurdesel-package--bg">
			<div class="fleurdesel-package__media">
				<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'fleurdesel-medium' ); ?>
				<?php endif; ?>
			</div>

			<div class="fleurdesel-package__data">
				<?php the_title( '<h2 class="fleurdesel-package__title mb-20"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' ); ?>

				<?php if ( $price = Fleurdesel_Package_Functions::get_price() ) : ?>
					<p class="fleurdesel-package__price fz-14 font-700 text-uppercase letter-spacing-2 mb-20">
						<?php printf( '%s/%s', esc_html( $price['value'] ), esc_html( $price['unit'] ) ); ?>
					</p>
				<?php endif; ?>

				<p class="fleurdesel-package__action">
					<a href="<?php the_permalink(); ?>" class="btn-view btn-primary btn-outline-primary fz-12 font-700 text-white text-uppercase"><?php esc_html_e( 'View Details', 'fleurdesel' ); ?></a>
				</p>
			</div>
		</div>
		<!-- /.fleurdesel-package -->
		<?php
	endwhile;
	wp_reset_postdata();
	?>
</div>
