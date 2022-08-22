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


// ADDONS - MENU ////
function my_private_site_admin_addons_menu() {
	$args = array(
		'id'           => 'my_private_site_tab_addons_page',
		'title'        => 'My Private Site - Add-ons',
		// page title
		'menu_title'   => 'Add-ons',
		// title on left sidebar
		'tab_title'    => 'Add-ons',
		// title displayed on the tab
		'object_types' => array( 'options-page' ),
		'option_key'   => 'my_private_site_tab_addons',
		'parent_slug'  => 'my_private_site_tab_main',
		'tab_group'    => 'my_private_site_tab_set',

	);

	// 'tab_group' property is supported in > 2.4.0.
	if ( version_compare( CMB2_VERSION, '2.4.0' ) ) {
		$args['display_cb'] = 'my_private_site_cmb_options_display_with_tabs';
	}

	do_action( 'my_private_site_tab_addons_before', $args );

	// call on button hit for page save
	add_action( 'admin_post_my_private_site_tab_addons', 'my_private_site_tab_addons_process_buttons' );

	$args          = apply_filters( 'my_private_site_tab_addons_menu', $args );
	$addon_options = new_cmb2_box( $args );

	my_private_site_admin5_addons_section_data( $addon_options );

	do_action( 'my_private_site_tab_addons_after', $addon_options );
}

add_action( 'cmb2_admin_init', 'my_private_site_admin_addons_menu' );


// ADDONS - SECTION - DATA ////
function my_private_site_admin5_addons_section_data( $section_options ) {
	$section_options = apply_filters( 'my_private_site_tab_addons_section_data', $section_options );

	$section_options->add_field(
		array(
			'name'          => 'Add-ons',
			'id'            => 'my_private_site_add-ons_area',
			'type'          => 'text',
			'savetxt'       => '',
			'render_row_cb' => 'my_private_site_render_addons_tab_html',
		// this builds static text as provided
		)
	);
	$section_options = apply_filters( 'my_private_site_tab_addons_section_data_options', $section_options );
}

function my_private_site_render_addons_tab_html( $field_args, $field ) {
	$html_folder   = dirname( dirname( __FILE__ ) ) . '/html/';
		$html_file = $html_folder . 'admin-addons.php';
		require $html_file;
}

// ADDONS - PROCESS FORM SUBMISSIONS
function my_private_site_tab_addons_process_buttons() {
	// Process Save changes button
	// This is a callback that has to be passed the full array for consideration
	// phpcs:ignore WordPress.Security.NonceVerification
	$_POST = apply_filters( 'validate_page_slug_my_private_site_tab_addons', $_POST );
}
