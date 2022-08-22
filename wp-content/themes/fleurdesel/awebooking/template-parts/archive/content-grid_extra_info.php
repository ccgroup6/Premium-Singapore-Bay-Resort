<?php
/**
 * The template for displaying room content within loops
 *
 * This template can be overridden by copying it to yourtheme/awebooking/template-parts/archive/content.php.
 *
 * @see      http://docs.awethemes.com/awebooking/developers/theme-developers/
 * @author   awethemes
 * @package  AweBooking
 * @version  3.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $room_type;

// Ensure visibility.
if ( empty( $room_type ) ) {
	return;
}

// Column.
$column_class = Fleurdesel_Awebooking_Custom::get_column();
if ( isset( $GLOBALS['room_column'] ) ) {
	$column_class = $GLOBALS['room_column'];
}

// Alignment.
$alignment = fleurdesel_option( 'room_content_alignment' );
if ( isset( $GLOBALS['room_content_alignment'] ) ) {
	$alignment = $GLOBALS['room_content_alignment'];
}

$area_size = $room_type->get( 'area_size' ) ? esc_html( $room_type->get( 'area_size' ) ) : get_post_meta( $room_type->get_id(), 'fl_apb_area_size', true );
$bedrooms = $room_type->get( 'bedrooms' ) ? absint( $room_type->get( 'bedrooms' ) ) : get_post_meta( $room_type->get_id(), 'fl_apb_bedroom', true );

$post_class = 'col-md-6 col-lg-' . esc_attr( $column_class );
?>
<div <?php post_class( $post_class ); ?>>
	<div class="fleurdesel-room fleurdesel-room--grid">
		<div class="fleurdesel-room__media">
			<?php if ( has_post_thumbnail() ) : ?>
				<a href="<?php echo esc_url( get_the_permalink() ); ?>">
					<?php echo abrs_get_thumbnail(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</a>
			<?php endif; ?>
		</div>

		<div class="fleurdesel-room__data text-<?php echo esc_attr( $alignment ); ?>">
			<div class="fleurdesel-room__wrap">
				<?php the_title( '<h2 class="fleurdesel-room__title h4"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>

				<?php awebooking_template_loop_price(); ?>

				<div class="fleurdesel-room__extra fleurdesel-room__extra--info">
					<?php fleurdesel_room_occupancy_box( $room_type ); ?>

					<?php if ( $area_size ) : ?>
						<p>
							<span>
								<?php
								printf( "%02d\n", $area_size ); // Wpcs: xss ok.
								?>
							</span>
							<?php
							/* translators: %s measure unit */
							printf( esc_html__( 'Size %s', 'fleurdesel' ), abrs_get_measure_unit_label() ); // phpcs:ignore WordPress.Security.EscapeOutput
							?>
						</p>
					<?php endif; ?>

					<?php if ( $bedrooms ) : ?>
						<p>
							<span>
								<?php
								printf( "%02d\n", $bedrooms ); // Wpcs: xss ok.
								?>
							</span>
							<?php echo esc_html( _n( 'Bedroom', 'Bedrooms', absint( $bedrooms ), 'fleurdesel' ) ); ?>
						</p>
					<?php endif; ?>
				</div>

				<?php awebooking_template_loop_view_more(); ?>
			</div>
		</div>
	</div>
</div>
