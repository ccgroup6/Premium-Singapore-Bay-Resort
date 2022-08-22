<?php
/**
 * My Private Site by David Gewirtz, adopted from Jon ‘jonradio’ Pearkins
 *
 * Lab Notes: http://zatzlabs.com/lab-notes/
 * Plugin Page: https://zatzlabs.com/project/my-private-site/
 * Contact: http://zatzlabs.com/contact-us/
 *
 * Copyright (c) 2015-2020 by David Gewirtz
 */

// initialize the CMB2 library
if ( file_exists( dirname( __FILE__ ) . '/library/cmb2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/library/cmb2/init.php';
} elseif ( file_exists( dirname( __FILE__ ) . '/library/CMB2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/library/CMB2/init.php';
}

require_once 'util/utilities.php';
require_once 'util/utilities5.php';
require_once 'legacy/legacy.php';

function my_private_site_admin_loader() {

	// bring in telemetry
	require_once 'telemetry/deactivate.php';

	// bring in the admin page tabs
	require_once 'admin/main.php';
	require_once 'admin/site-privacy.php';
	require_once 'admin/landing-page.php';
	require_once 'admin/public-pages.php';
	require_once 'admin/selective-content.php';
	require_once 'admin/membership.php';
	require_once 'admin/addons.php';
	require_once 'admin/licenses.php';
	require_once 'admin/advanced.php';
}

// load and enqueue supporting resources

function my_private_site_queue_admin_stylesheet() {
	do_action( 'my_private_site_add_styles_first' );

	$style_url = plugins_url( '/css/adminstyles.css', __FILE__ );

	wp_register_style( 'my_private_site_admin_css', $style_url );
	wp_enqueue_style( 'my_private_site_admin_css' );

	// remodal library used by telemetry
	wp_enqueue_script( 'remodal', plugins_url( '/library/remodal/remodal.min.js', __FILE__ ) );
	wp_enqueue_style( 'remodal', plugins_url( '/library/remodal/remodal.css', __FILE__ ) );
	wp_enqueue_style( 'remodal-default-theme', plugins_url( '/library/remodal/remodal-default-theme.css', __FILE__ ) );

	do_action( 'my_private_site_add_styles_after' );
}

add_action( 'admin_enqueue_scripts', 'my_private_site_queue_admin_stylesheet' );

function my_private_site_init() {
	// Initialize options to defaults as needed
	my_private_site_admin_loader();

	// check to see if user has been told where the My Private Site settings are
	$internal_settings = get_option( 'jr_ps_internal_settings' );
	if ( isset( $internal_settings['warning_privacy'] ) ) {
		unset( $internal_settings['warning_privacy'] );
		update_option( 'jr_ps_internal_settings', $internal_settings );
	}

	// check to see if first run time has been recorded
	$first_run = get_option( 'jr_ps_first_run_time' );
	if ( $first_run == false ) {
		update_option( 'jr_ps_first_run_time', time() );
	}
}

add_shortcode( 'privacy', 'my_private_site_shortcode' );

// SHORTCODE - ADD CAPABILITIES TO THE SHORTCODE ////
function my_private_site_shortcode( $atts, $content = null ) {
	// HIDE
	if ( isset( $atts['hide-if'] ) ) {
		$condition_to_check = strtolower( $atts['hide-if'] );
		switch ( $condition_to_check ) {
			case 'logged-in':
				if ( is_user_logged_in() ) {
					$content = '';
				}
				break;
			case 'logged-out':
				if ( ! is_user_logged_in() ) {
					$content = '';
				}
				break;
		}
	}

	return $content;
}


