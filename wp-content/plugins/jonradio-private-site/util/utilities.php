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

// quick array name-of function
// from http://php.net/manual/en/function.key.php
function my_private_site_name_of( array $a, $pos ) {
	$temp = array_slice( $a, $pos, 1, true );

	return key( $temp );
}

// from http://www.w3schools.com/php/filter_validate_url.asp
// returns a clean URL or false
// use === false to check it
function my_private_site_validate_url( $url ) {
	// Remove all illegal characters from a url
	$url = filter_var( $url, FILTER_SANITIZE_URL );

	// Validate url
	if ( ! filter_var( $url, FILTER_VALIDATE_URL ) === false ) {
		return $url;
	} else {
		return false;
	}
}

function my_private_site_obscurify_string( $s, $char = '*', $inner_obscure = true ) {
	$length = strlen( $s );
	if ( $length > 6 ) {
		$segment_size = intval( $length / 3 );
		$seg1         = substr( $s, 0, $segment_size );
		$seg2         = substr( $s, $segment_size, $segment_size );
		$seg3         = substr( $s, $segment_size * 2, $length - ( $segment_size * 2 ) );

		if ( $inner_obscure ) {
			$seg2 = str_repeat( $char, $segment_size );
		} else {
			$seg1 = str_repeat( $char, $segment_size );
			$seg3 = str_repeat( $char, strlen( $seg3 ) );
		}

		$s = $seg1 . $seg2 . $seg3;
	}

	return $s;
}

// label display functions

function my_private_site_get_feature_promo( $desc, $url, $upgrade = 'UPGRADE', $break = '<BR>' ) {
	$feature_desc = sanitize_text_field( htmlspecialchars( $desc ) );

	$promo  = $break;
	$promo .= '<span style="background-color:DarkGoldenRod; color:white;font-style:normal;text-weight:bold">';
	$promo .= '&nbsp;' . $upgrade . ':&nbsp;';
	$promo .= '</span>';
	$promo .= '<span style="color:DarkGoldenRod;font-style:normal;">';
	$promo .= '&nbsp;' . $feature_desc . ' ';
	$promo .= '<A target="_blank" HREF="' . $url . '">Learn more.</A>';
	$promo .= '</span>';

	return $promo;
}

function my_private_site_display_label( $before = '&nbsp;', $message = 'BETA', $after = '', $background = '' ) {
	if ( $background == '' ) {
		$background = 'darkgrey';
	}
	$label  = $before . '<span style="background-color:' . $background . '; color:white;font-style:normal;text-weight:bold">';
	$label .= '&nbsp;' . $message . '&nbsp;';
	$label .= '</span>' . $after;

	return $label;
}

function my_private_site_display_fail() {
	return my_private_site_display_label( '&nbsp;', 'FAIL', '', 'red' );
}

function my_private_site_display_pass() {
	return my_private_site_display_label( '&nbsp;', 'PASS', '', 'green' );
}

// *** EDD LICENSING ***

function my_private_site_store_url() {
	return 'https://zatzlabs.com';
}

function my_private_site_telemetry_url() {
	return 'https://zatzlabs.com';
}

/******************************************************************************************************/
function my_private_site_debug_log( $message ) {
	$max_log_line_count = 200;

	$debug_log = get_option( 'jr_ps_donate_log' );

	if ( empty( $debug_log ) ) {
		$debug_log = array();
	}

	$timestamp = current_time( 'mysql' );

	$debug_log[] = $timestamp . ' ' . $message;

	if ( count( $debug_log ) > $max_log_line_count ) {
		$debug_log = array_slice( $debug_log, -$max_log_line_count, 0 );
	}

	update_option( 'jr_ps_donate_log', $debug_log );
}

function my_private_site_get_license_key( $item ) {
	$license_key   = '';
	$license_array = unserialize( get_option( 'jr_ps_licenses' ) );
	if ( isset( $license_array[ $item ] ) ) {
		$license_key = $license_array[ $item ];
	}

	return $license_key;
}

function my_private_site_confirm_license_key( $key ) {
	if ( $key == '' ) {
		return false;
	}

	return true;
}

function my_private_site_edd_activate_license( $product, $license, $url ) {
	my_private_site_debug_log( '----------------------------------------' );
	my_private_site_debug_log( 'LICENSE ACTIVATION STARTED' );

	// retrieve the license from the database
	$license = trim( $license );
	my_private_site_debug_log( 'Product: ' . $product );
	my_private_site_debug_log( 'License key: ' . my_private_site_obscurify_string( $license ) );

	// Call the custom API.
	$response = wp_remote_get(
		add_query_arg(
			array(
				'edd_action' => 'activate_license',
				'license'    => $license,
				'item_name'  => urlencode( $product ),
				// the name of our product in EDD
			),
			$url
		),
		array(
			'timeout'   => 15,
			'sslverify' => false,
		)
	);

	// make sure the response came back okay
	if ( is_wp_error( $response ) ) {
		my_private_site_debug_log( 'Response error detected: ' . $response->get_error_message() );

		return false;
	}

	// decode the license data
	$license_data = json_decode( wp_remote_retrieve_body( $response ) );

	// $license_data->license will be either "active" or "inactive" <-- "valid"
	if ( isset( $license_data->license ) && $license_data->license == 'active' || $license_data->license == 'valid' ) {
		my_private_site_debug_log( 'License check value: ' . $license_data->license );
		my_private_site_debug_log( 'License check returning valid.' );

		return 'valid';
	}

	my_private_site_debug_log( 'License check returning invalid.' );

	return 'invalid';
}

function my_private_site_edd_deactivate_license( $product, $license, $url ) {
	my_private_site_debug_log( '----------------------------------------' );
	my_private_site_debug_log( 'LICENSE DEACTIVATION STARTED' );

	// retrieve the license from the database

	$license = trim( $license );
	my_private_site_debug_log( 'Product: ' . $product );
	my_private_site_debug_log( 'License key: ' . my_private_site_obscurify_string( $license ) );

	// Call the custom API.
	$response = wp_remote_get(
		add_query_arg(
			array(
				'edd_action' => 'deactivate_license',
				'license'    => $license,
				'item_name'  => urlencode( $product ),
				// the name of our product in EDD
			),
			$url
		),
		array(
			'timeout'   => 15,
			'sslverify' => false,
		)
	);

	// make sure the response came back okay
	if ( is_wp_error( $response ) ) {
		my_private_site_debug_log( 'Response error detected: ' . $response->get_error_message() );

		return false;
	}

	// decode the license data
	$license_data = json_decode( wp_remote_retrieve_body( $response ) );

	// $license_data->license will be either "active" or "inactive" <-- "valid"
	if ( isset( $license_data->license ) && $license_data->license == 'deactivated' ) {
		my_private_site_debug_log( 'License check value: ' . $license_data->license );
		my_private_site_debug_log( 'License check returning deactivated.' );

		return 'deactivated';
	}

	my_private_site_debug_log( 'License check returning invalid.' );

	return 'invalid';
}
