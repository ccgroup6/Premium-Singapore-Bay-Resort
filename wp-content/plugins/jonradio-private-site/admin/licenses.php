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


// LICENSES - MENU ////
function my_private_site_admin_licenses_menu() {
	$args = array(
		'id'           => 'my_private_site_tab_licenses_page',
		'title'        => 'My Private Site - Licenses',
		// page title
		'menu_title'   => 'Licenses',
		// title on left sidebar
		'tab_title'    => 'Licenses',
		// title displayed on the tab
		'object_types' => array( 'options-page' ),
		'option_key'   => 'my_private_site_tab_licenses',
		'parent_slug'  => 'my_private_site_tab_main',
		'tab_group'    => 'my_private_site_tab_set',
		'save_button'  => 'Save Settings',
	);

	// 'tab_group' property is supported in > 2.4.0.
	if ( version_compare( CMB2_VERSION, '2.4.0' ) ) {
		$args['display_cb'] = 'my_private_site_cmb_options_display_with_tabs';
	}

	do_action( 'my_private_site_tab_licenses_before', $args );

	// call on button hit for page save
	add_action( 'admin_post_my_private_site_tab_licenses', 'my_private_site_tab_licenses_process_buttons' );

	// clear previous error messages if coming from another page
	my_private_site_clear_cmb2_submit_button_messages( $args['option_key'] );

	$args             = apply_filters( 'my_private_site_tab_licenses_menu', $args );
	$licenses_options = new_cmb2_box( $args );
	my_private_site_admin_licenses_section_data( $licenses_options );

	do_action( 'my_private_site_tab_licenses_after', $licenses_options );
}

add_action( 'cmb2_admin_init', 'my_private_site_admin_licenses_menu' );

// LICENSES - SECTION - TEST ////
function my_private_site_admin_licenses_section_data( $section_options ) {
	$section_desc  = 'If you have purchased any premium extensions, you will be able to enter ';
	$section_desc .= 'their license keys here. Your active license key is required to run the extension ';
	$section_desc .= 'and will also enable you to get automatic updates for the duration of your license.';

	$section_options->add_field(
		array(
			'name'        => 'License Activation',
			'id'          => 'my_private_site_template_email_title',
			'type'        => 'title',
			'after_field' => $section_desc,
		)
	);

	// $section_options = apply_filters (
	// 'my_private_site_admin_licenses_section_registration', $section_options );

	$section_options->add_field(
		array(
			'name'         => 'Licenses',
			'id'           => 'licenses_no_licenses',
			'type'         => 'licenses_html',
			'before_field' => 'Nothing has been installed or activated that requires a license.',
		)
	);

	$section_options = apply_filters( 'my_private_site_admin_licenses_section_registration_options', $section_options );
}

// LICENSES - PROCESS ////
function my_private_site_tab_licenses_process_buttons() {
	// This is a callback that has to be passed the full array for consideration
	// phpcs:ignore WordPress.Security.NonceVerification
	$_POST = apply_filters( 'validate_page_slug_my_private_site_tab_licenses', $_POST );
}
