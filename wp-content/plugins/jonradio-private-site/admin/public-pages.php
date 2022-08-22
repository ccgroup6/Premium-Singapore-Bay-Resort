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

if ( ! function_exists( 'jr_ps_site_url' ) ) {
	require_once jr_ps_path() . 'includes/functions-admin.php';
}

// public_pages - MENU ////
function my_private_site_admin_public_pages_menu() {
	$args = array(
		'id'           => 'my_private_site_tab_public_pages_page',
		'title'        => 'My Private Site - Home Page',
		// page title
		'menu_title'   => 'Home Page',
		// title on left sidebar
		'tab_title'    => 'Home Page',
		// title displayed on the tab
		'object_types' => array( 'options-page' ),
		'option_key'   => 'my_private_site_tab_public_pages',
		'parent_slug'  => 'my_private_site_tab_main',
		'tab_group'    => 'my_private_site_tab_set',
	);

	// 'tab_group' property is supported in > 2.4.0.
	if ( version_compare( CMB2_VERSION, '2.4.0' ) ) {
		$args['display_cb'] = 'my_private_site_cmb_options_display_with_tabs';
	}

	do_action( 'my_private_site_tab_public_pages_before', $args );

	// call on button hit for page save
	add_action( 'admin_post_my_private_site_tab_public_pages', 'my_private_site_tab_public_pages_process_buttons' );

	// clear previous error messages if coming from another page
	my_private_site_clear_cmb2_submit_button_messages( $args['option_key'] );

	$args            = apply_filters( 'my_private_site_tab_public_pages_menu', $args );
	$section_options = new_cmb2_box( $args );

	my_private_site_admin_public_pages_make_public_section_data( $section_options );

	do_action( 'my_private_site_tab_public_pages_after', $section_options );
}

add_action( 'cmb2_admin_init', 'my_private_site_admin_public_pages_menu' );

// MAKE PUBLIC SECTION
function my_private_site_admin_public_pages_make_public_section_data( $section_options ) {
	// init values
	$handler_function = 'my_private_site_admin_public_pages_preload'; // setup the preload handler function
	$home_url         = trim( get_home_url(), '\ /' );
	$settings         = get_option( 'jr_ps_settings' );

	$section_options = apply_filters( 'my_private_site_tab_public_pages_make_public_section_data', $section_options );

	$section_desc = '<i>Allow home page to remain accessible to the public without needing a login.</i>';

	$section_options->add_field(
		array(
			'name'        => 'Public Home Page',
			'id'          => 'my_private_site_admin_public_pages_title',
			'type'        => 'title',
			'after_field' => $section_desc,
		)
	);

	$site_home = 'Allow site home page to remain accessible without requiring login';

	$section_options->add_field(
		array(
			'name'  => 'Site Home',
			'id'    => 'my_private_site_admin_public_pages_site_home',
			'type'  => 'checkbox',
			'after' => $site_home,
		)
	);
	my_private_site_preload_cmb2_field_filter( 'my_private_site_admin_public_pages_site_home', $handler_function );

	$section_desc = '<i>Specify pages to remain accessible to the public without needing a login.</i><br>';

	// promo
	$feature_desc  = 'Public Pages lets you designate specific pages, or all pages with specified ';
	$feature_desc .= 'prefixes, to be available to the public without login. ';
	$feature_desc .= 'You can also choose the overall privacy mode of the site. You can set the site to ';
	$feature_desc .= 'private and then open some pages to the public. Or you can set the site to public ';
	$feature_desc .= 'and restrict access to just some specific pages.';
	$feature_url   = 'https://zatzlabs.com/project/my-private-site-plugins-and-extensions/';
	$section_desc .= my_private_site_get_feature_promo( $feature_desc, $feature_url, 'UPGRADE', ' ' );

	$section_options->add_field(
		array(
			'name'        => 'Specify Public Pages',
			'id'          => 'my_private_site_admin_advanced_public_pages_title',
			'type'        => 'title',
			'after_field' => $section_desc,
		)
	);

	$section_desc  = '<i>Specify tags and categories that, when assigned to pages, will allow those pages ';
	$section_desc .= 'remain accessible to the public without needing a login.</i><br>';

	$feature_desc  = 'Tags & Categories lets you specify tags and categories that, when assigned to pages, ';
	$feature_desc .= 'allow those pages to remain accessible to the public without needing a login.';
	$feature_url   = 'https://zatzlabs.com/project/my-private-site-plugins-and-extensions/';
	$section_desc .= my_private_site_get_feature_promo( $feature_desc, $feature_url, 'UPGRADE', ' ' );

	$section_options->add_field(
		array(
			'name'        => 'Specify Tags & Categories',
			'id'          => 'my_private_site_admin_advanced_tags_categories_title',
			'type'        => 'title',
			'after_field' => $section_desc,
		)
	);

	my_private_site_display_cmb2_submit_button(
		$section_options,
		array(
			'button_id'          => 'jr_ps_button_public_pages_public_home',
			'button_text'        => 'Make Page Public',
			'button_success_msg' => 'Public page saved.',
			'button_error_msg'   => 'Please enter a valid URL',
		)
	);
	$section_options = apply_filters( 'my_private_site_tab_public_pages_make_public_section_data_options', $section_options );
}

function my_private_site_tab_public_pages_process_buttons() {
	// Process Save changes button
	// This is a callback that has to be passed the full array for consideration
	// phpcs:ignore WordPress.Security.NonceVerification
	$_POST    = apply_filters( 'validate_page_slug_my_private_site_tab_public_pages', $_POST );
	$settings = get_option( 'jr_ps_settings' );

	if ( isset( $_POST['jr_ps_button_public_pages_public_home'], $_POST['jr_ps_button_public_pages_public_home_nonce'] ) ) {
		if ( ! wp_verify_nonce( $_POST['jr_ps_button_public_pages_public_home_nonce'], 'jr_ps_button_public_pages_public_home' ) ) {
			wp_die( 'Security violation detected [A009]. Access denied.', 'Security violation', array( 'response' => 403 ) );
		}
		if ( isset( $_POST['my_private_site_admin_public_pages_site_home'] ) ) {
			$settings['excl_home'] = true;
		} else {
			if ( ! wp_verify_nonce( $_POST['jr_ps_button_public_pages_public_home_nonce'], 'jr_ps_button_public_pages_public_home' ) ) {
				wp_die( 'Security violation detected [A010]. Access denied.', 'Security violation', array( 'response' => 403 ) );
			}
			$settings['excl_home'] = false;
		}
	}

	$result = update_option( 'jr_ps_settings', $settings );

	my_private_site_flag_cmb2_submit_button_success( 'jr_ps_button_public_pages_public_home' );
}

function my_private_site_admin_public_pages_preload( $data, $object_id, $args, $field ) {
	// find out what field we're getting
	$field_id = $args['field_id'];

	// get stored data from plugin
	$internal_settings = get_option( 'jr_ps_internal_settings' );
	$settings          = get_option( 'jr_ps_settings' );

	switch ( $field_id ) {
		case 'my_private_site_admin_public_pages_site_home':
			if ( isset( $settings['excl_home'] ) ) {
				return $settings['excl_home'];
			} else {
				return false;
			}
			break;
	}
}

