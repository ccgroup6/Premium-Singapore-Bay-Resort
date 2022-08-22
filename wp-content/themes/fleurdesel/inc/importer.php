<?php
/**
 * Importer.
 *
 * @package Fleurdesel
 */

/**
 * Register import demos.
 */
function fleurdesel_register_import_demo() {
	if ( ! function_exists( 'at_importer_register' ) ) {
		return;
	}

	at_importer_register( 'fleurdesel', array(
		'name'        => esc_html__( 'Fleurdesel', 'fleurdesel' ),
		'preview'     => esc_url( 'http://demo.awethemes.com/fleurdesel' ),
		'screenshot'  => get_template_directory_uri() . '/screenshot.png',
		'archive'     => get_template_directory() . '/dummy-data/dummy-data.zip',
	) );
}
add_action( 'admin_init', 'fleurdesel_register_import_demo' );

/**
 * Fleurdesel import theme options for default.
 *
 * @param array  $meta_data Meta data.
 * @param string $demo_path Demo path.
 * @param string $id        Demo id.
 */
function fleurdesel_import_theme_options( $meta_data, $demo_path, $id ) {
	WP_Filesystem();
	global $wp_filesystem;

	$theme_option_path = $demo_path . '/theme-options.txt';

	// Bail if no theme-options.txt found.
	if ( file_exists( $theme_option_path ) ) {
		$theme_options = CMB2_Boxes::get( 'fleurdesel_options' );
		$backup = new Skeleton\CMB2\Backup( $theme_options );

		$payload = $wp_filesystem->get_contents( $theme_option_path );
		$backup->restore( $payload );
	}

	// Set Menu.
	$primary_menu = get_term_by( 'name', 'Main menu', 'nav_menu' );

	if ( $primary_menu ) {
		set_theme_mod( 'nav_menu_locations', array(
			'primary' => $primary_menu->term_id,
		) );
	}

	if ( class_exists( 'AweBooking' ) ) {
		// Insert rooms.
		$room_types = get_posts( [
			'post_type'      => 'room_type',
			'post_status'    => 'public',
			'posts_per_page' => - 1,
		] );

		$room_type_ids = wp_list_pluck( $room_types, 'ID' );

		foreach ( $room_type_ids as $key => $room_type_id ) {
			$room_type = abrs_get_room_type( $room_type_id );
			$room_type->delete_meta( '_cache_total_rooms' );
			if ( count( $room_type->get_rooms() ) > 0 ) {
				continue;
			}

			$room = ( new AweBooking\Model\Room )->fill( [
				'name'      => sprintf( '%1$s 1', $room_type->get_title() ),
				'room_type' => $room_type->get_id(),
			] );

			$room->save();
		}

		// Update AweBooking pages.
		$options = cmb2_options( awebooking()->get_current_option() );

		if ( function_exists( 'abrs_get_option' ) ) {
			if ( ! abrs_get_option( 'page_check_availability' ) ) {
				if ( $page_check_availability = get_page_by_title( 'Check Availability' ) ) {
					$options->update( 'page_check_availability', $page_check_availability->ID, true, true );
				}
			}

			if ( ! abrs_get_option( 'page_checkout' ) ) {
				if ( $page_checkout = get_page_by_title( 'Checkout' ) ) {
					$options->update( 'page_checkout', $page_checkout->ID, true, true );
				}
			}
		}
	}
}
add_action( 'at_import_fleurdesel', 'fleurdesel_import_theme_options', 10, 3 );
