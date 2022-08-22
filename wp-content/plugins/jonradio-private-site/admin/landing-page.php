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

// landing_page - MENU ////
function my_private_site_admin_landing_page_menu() {
	$args = array(
		'id'           => 'my_private_site_tab_landing_page_page',
		'title'        => 'My Private Site - Landing Page',
		// page title
		'menu_title'   => 'Landing Page',
		// title on left sidebar
		'tab_title'    => 'Landing Page',
		// title displayed on the tab
		'object_types' => array( 'options-page' ),
		'option_key'   => 'my_private_site_tab_landing_page',
		'parent_slug'  => 'my_private_site_tab_main',
		'tab_group'    => 'my_private_site_tab_set',

	);

	// 'tab_group' property is supported in > 2.4.0.
	if ( version_compare( CMB2_VERSION, '2.4.0' ) ) {
		$args['display_cb'] = 'my_private_site_cmb_options_display_with_tabs';
	}

	do_action( 'my_private_site_tab_landing_page_before', $args );

	// call on button hit for page save
	add_action( 'admin_post_my_private_site_tab_landing_page', 'my_private_site_tab_landing_page_process_buttons' );

	// clear previous error messages if coming from another page
	my_private_site_clear_cmb2_submit_button_messages( $args['option_key'] );

	$args            = apply_filters( 'my_private_site_tab_landing_page_menu', $args );
	$landing_options = new_cmb2_box( $args );

	my_private_site_admin_landing_page_section_data( $landing_options );

	do_action( 'my_private_site_tab_landing_page_after', $landing_options );
}

add_action( 'cmb2_admin_init', 'my_private_site_admin_landing_page_menu' );

function my_private_site_admin_landing_page_section_data( $section_options ) {
	// init values
	$handler_function = 'my_private_site_admin_landing_page_preload'; // setup the preload handler function
	$home_url         = trim( get_home_url(), '\ /' );

	$section_options = apply_filters( 'my_private_site_tab_landing_page_section_data', $section_options );

	$section_desc = '<i>Choose where you want the user to be redirected to upon successful login.</i>';

	$section_options->add_field(
		array(
			'name'        => 'Choose Destination Landing Page',
			'id'          => 'jr_ps_admin_landing_page_title',
			'type'        => 'title',
			'after_field' => $section_desc,
		)
	);

	$radio_options = array(
		'return' => 'Return to Same URL',
		'home'   => 'Redirect to Site Home',
		'admin'  => 'Redirect to WordPress Admin Dashboard',
		'omit'   => 'Omit ?redirect_to= from URL',
		'url'    => 'Redirect to Specified URL',
	);

	// Test email section
	$section_desc = '<i>Specify where to redirect visitors immediately after login.</i>';

	$section_options->add_field(
		array(
			'name'    => 'Landing Page After Login',
			'id'      => 'jr_ps_admin_landing_page_option',
			'type'    => 'radio',
			'options' => $radio_options,
			'desc'    =>
				'If you\'re not sure, choose Return to Same URL. Choose Omit if you\'re using a custom login page.',
		)
	);
	my_private_site_preload_cmb2_field_filter( 'jr_ps_admin_landing_page_option', $handler_function );

	$section_options->add_field(
		array(
			'name' => 'Specified Destination URL',
			'id'   => 'jr_ps_admin_landing_page_url',
			'type' => 'text',
			'desc' => 'URL must begin with ' . $home_url,

		)
	);
	my_private_site_preload_cmb2_field_filter( 'jr_ps_admin_landing_page_url', $handler_function );

	$wp_login =
		'Apply landing location when a wp-login.php URL is clicked or typed without &redirect_to= in URL';

	$section_options->add_field(
		array(
			'name'  => 'Manage wp-login.php',
			'id'    => 'jr_ps_admin_landing_page_wplogin',
			'type'  => 'checkbox',
			'after' => $wp_login,
		)
	);
	my_private_site_preload_cmb2_field_filter( 'jr_ps_admin_landing_page_wplogin', $handler_function );

	my_private_site_display_cmb2_submit_button(
		$section_options,
		array(
			'button_id'          => 'jr_ps_button_landing_page_save',
			'button_text'        => 'Save Landing Page',
			'button_success_msg' => 'Landing page saved.',
			'button_error_msg'   => 'Please enter a valid URL',
		)
	);
	$section_options = apply_filters( 'my_private_site_tab_landing_page_section_data_options', $section_options );
}

function my_private_site_tab_landing_page_process_buttons() {
	// Process Save changes button
	// This is a callback that has to be passed the full array for consideration
	// phpcs:ignore WordPress.Security.NonceVerification
	$_POST = apply_filters( 'validate_page_slug_my_private_site_tab_landing_page', $_POST );

	if ( isset( $_POST['jr_ps_button_landing_page_save'], $_POST['jr_ps_button_landing_page_save_nonce'] ) ) {
		if ( ! wp_verify_nonce( $_POST['jr_ps_button_landing_page_save_nonce'], 'jr_ps_button_landing_page_save' ) ) {
			wp_die( 'Security violation detected [A001]. Access denied.', 'Security violation', array( 'response' => 403 ) );
		}

		$settings = get_option( 'jr_ps_settings' );
		// these just check for value existence
		// phpcs:ignore WordPress.Security.NonceVerification
		if ( isset( $_POST['jr_ps_admin_landing_page_url'] ) ) {
			$settings['specific_url'] = $_POST['jr_ps_admin_landing_page_url'];
		} else {
			$settings['specific_url'] = '';
		}
		// these just check for value existence
		// phpcs:ignore WordPress.Security.NonceVerification
		if ( isset( $_POST['jr_ps_admin_landing_page_option'] ) ) {
			$settings['landing'] = $_POST['jr_ps_admin_landing_page_option'];
		}
		// these just check for value existence
		// phpcs:ignore WordPress.Security.NonceVerification
		if ( isset( $_POST['jr_ps_admin_landing_page_wplogin'] ) ) {
			$settings['wplogin_php'] = true;
		} else {
			$settings['wplogin_php'] = false;
		}

		$result = update_option( 'jr_ps_settings', $settings );
		my_private_site_flag_cmb2_submit_button_success( 'jr_ps_button_landing_page_save' );
	}
}

function my_private_site_admin_landing_page_preload( $data, $object_id, $args, $field ) {
	// find out what field we're getting
	$field_id = $args['field_id'];

	// get stored data from plugin
	$internal_settings = get_option( 'jr_ps_internal_settings' );
	$settings          = get_option( 'jr_ps_settings' );

	switch ( $field_id ) {
		case 'jr_ps_admin_landing_page_url':
			if ( isset( $settings['specific_url'] ) ) {
				return $settings['specific_url'];
			} else {
				return false;
			}
			break;
		case 'jr_ps_admin_landing_page_option':
			if ( isset( $settings['landing'] ) ) {
				return $settings['landing'];
			} else {
				return false;
			}
			break;
		case 'jr_ps_admin_landing_page_wplogin':
			if ( isset( $settings['wplogin_php'] ) ) {
				if ( $settings['wplogin_php'] == true ) {
					return 'on';
				}
			} else {
				return false;
			}
			break;
	}
}
