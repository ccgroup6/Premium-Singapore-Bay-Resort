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
remove_action( 'abrs_single_room_sidebar', 'abrs_single_room_form', 10 );
?>

<article id="room-<?php the_ID(); ?>" <?php post_class( 'awebooking-room-type awebooking awebooking--modern' ); ?>>
	<div class="apb-container apb-single-room">
		<div class="apb-product_detail room-detail">
			<div class="container">
				<div class="apb-layout show-arrows">
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
			</div>

			<div class="room-detail_book">
				<div class="container">
					<?php
					/**
					 * abrs_single_room_sidebar hook.
					 *
					 * @hooked abrs_single_room_form() - 10.
					 */
					do_action( 'abrs_single_room_sidebar' );

					abrs_get_search_form([
						'template'  => 'standard-1',
						'only_room' => get_the_ID(),
					]);
					?>
				</div>
			</div>
			<div class="container">
				<div class="extra-info">
					<?php do_action( 'fleurdesel_room_detail_extra_info' ); ?>
				</div>

				<div class="apb-product_tab clearfix">
					<?php do_action( 'fleurdesel_room_detail_tabs' ); ?>
				</div>
			</div>
		</div>
	</div>
</article><!-- #room-<?php the_ID(); ?> -->
<?php do_action( 'abrs_after_single_room' ); ?>
