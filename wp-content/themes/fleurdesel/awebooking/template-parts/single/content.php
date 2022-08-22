<?php
/**
 * The template for displaying single room content
 *
 * This template can be overridden by copying it to {yourtheme}/awebooking/template-parts/single/content.php.
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

do_action( 'abrs_print_notices' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}

remove_action( 'abrs_single_room_sections', 'abrs_single_room_description', 10 );
remove_action( 'abrs_single_room_sections', 'abrs_single_room_amenities', 15 );
?>

<article id="room-<?php the_ID(); ?>" <?php post_class( 'awebooking-room-type awebooking awebooking--standard' ); ?>>
	<div class="apb-container apb-single-room">
		<div class="apb-product_detail room-detail">
			<div class="apb-layout">
				<div class="row">
					<div class="apb-content-area col-lg-8">
						<?php
						/**
						 * abrs_single_room_sections hook.
						 *
						 * @hooked abrs_single_room_description() removed by theme - 10.
						 * @hooked abrs_single_room_amenities() removed by theme   - 15.
						 * @hooked abrs_single_room_gallery()                      - 20.
						 */
						do_action( 'abrs_single_room_sections' );
						?>
					</div>
					<div class="apb-widget-area col-lg-4">
						<div class="room-detail_book">
							<?php
							/**
							 * abrs_single_room_sidebar hook.
							 *
							 * @hooked abrs_single_room_form() - 10.
							 */
							do_action( 'abrs_single_room_sidebar' );
							?>
						</div>
					</div>
				</div>
			</div>

			<div class="extra-info">
				<?php do_action( 'fleurdesel_room_detail_extra_info' ); ?>
			</div>

			<div class="apb-product_tab clearfix">
				<?php do_action( 'fleurdesel_room_detail_tabs' ); ?>
			</div>
		</div>
	</div>
</article><!-- #room-<?php the_ID(); ?> -->
<?php do_action( 'abrs_after_single_room' ); ?>
