<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Fleurdesel
 */

$GLOBALS['thumbnail_size'] = 'full';
$price = Fleurdesel_Package_Functions::get_price();
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'fleurdesel-event fleurdesel-event--detail' ); ?>>
	<?php get_template_part( 'template-parts/feature-image' ); ?>
	<div class="row">
		<div class="col-md-7">
			<div class="post-data">
				<div class="event-time">
					<div class="event-time__date">
						<?php echo esc_html( get_the_date( 'd' ) ); ?>
					</div>
					<div class="event-time__month">
						<span><?php echo esc_html( get_the_date( 'M' ) ); ?></span>
						<span><?php echo esc_html( get_the_date( 'Y' ) ); ?></span>
					</div>
				</div>

				<?php the_title( '<h1 class="post-title">', '</h1>' ); ?>
				<?php the_excerpt(); ?>
				<?php the_content(); ?>
			</div>
		</div>

		<div class="col-md-5">

			<?php if ( isset( $price['value'] ) && $price['value'] ) : ?>
			<div class="event-info">
				<div class="mb-20">
					<div class="event-price">
						<p class="event-title font-700 fz-14 text-uppercase">
							<span><?php esc_html_e( 'PRICE:', 'fleurdesel' ) ?></span>
						</p>
						<span class="event-price__value"><?php echo esc_html( $price['value'] ) ?></span>
						<?php esc_html_e( '/', 'fleurdesel' ) ?>
						<span class="event-price__unit"><?php echo esc_html( $price['unit'] ) ?></span>
					</div>
				</div>

			</div>
			<?php endif; ?>

		</div>
	</div>

</article><!-- #post-## -->
