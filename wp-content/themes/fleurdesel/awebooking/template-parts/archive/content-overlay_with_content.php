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
$GLOBALS['view_more_class'] = 'btn-primary';

// Ensure visibility.
if ( empty( $room_type ) ) {
	return;
}

// Alignment.
$alignment = fleurdesel_option( 'room_content_alignment' );
if ( isset( $GLOBALS['room_content_alignment'] ) ) {
	$alignment = $GLOBALS['room_content_alignment'];
}

$post_class = 'fleurdesel-room fleurdesel-room--overlay-extra';
?>
<div <?php post_class( $post_class ); ?>>
	<div class="fleurdesel-room fleurdesel-room--grid">
		<div class="fleurdesel-room__content">
			<div class="fleurdesel-room__media">
				<?php if ( has_post_thumbnail() ) : ?>
					<a href="<?php echo esc_url( get_the_permalink() ); ?>">
						<?php echo abrs_get_thumbnail( $room_type->get_id(), 'fleurdesel-vertical' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</a>
				<?php endif; ?>
			</div>

			<div class="fleurdesel-room__data text-<?php echo esc_attr( $alignment ); ?>">
				<div class="fleurdesel-room__wrap">
					<?php the_title( '<h2 class="fleurdesel-room__title h4"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>

					<?php awebooking_template_loop_price(); ?>

					<?php awebooking_template_loop_description(); ?>

					<?php awebooking_template_loop_view_more(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
