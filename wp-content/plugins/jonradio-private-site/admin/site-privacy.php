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


// site_privacy - MENU ////
function my_private_site_admin_site_privacy_menu() {
	$args = array(
		'id'           => 'my_private_site_tab_site_privacy_page',
		'title'        => 'My Private Site - Site Privacy',
		// page title
		'menu_title'   => 'Site Privacy',
		// title on left sidebar
		'tab_title'    => 'Site Privacy',
		// title displayed on the tab
		'object_types' => array( 'options-page' ),
		'option_key'   => 'my_private_site_tab_site_privacy',
		'parent_slug'  => 'my_private_site_tab_main',
		'tab_group'    => 'my_private_site_tab_set',

	);

	// 'tab_group' property is supported in > 2.4.0.
	if ( version_compare( CMB2_VERSION, '2.4.0' ) ) {
		$args['display_cb'] = 'my_private_site_cmb_options_display_with_tabs';
	}

	do_action( 'my_private_site_tab_site_privacy_before', $args );

	// call on button hit for page save
	add_action( 'admin_post_my_private_site_tab_site_privacy', 'my_private_site_tab_site_privacy_process_buttons' );

	// clear previous error messages if coming from another page
	my_private_site_clear_cmb2_submit_button_messages( $args['option_key'] );

	$args          = apply_filters( 'my_private_site_tab_site_privacy_menu', $args );
	$addon_options = new_cmb2_box( $args );

	my_private_site_admin_site_privacy_section_data( $addon_options );

	do_action( 'my_private_site_tab_site_privacy_after', $addon_options );
}

add_action( 'cmb2_admin_init', 'my_private_site_admin_site_privacy_menu' );

// site_privacy - SECTION - DATA ////
function my_private_site_admin_site_privacy_section_data( $section_options ) {
	$handler_function = 'my_private_site_admin_site_privacy_preload'; // setup the preload handler function

	$section_options = apply_filters( 'my_private_site_tab_site_privacy_section_data', $section_options );

	$settings       = get_option( 'jr_ps_settings' );
	$privacy_status = '<h1 style="color:red;">SITE IS NOT PRIVATE</h1>';
	if ( isset( $settings['private_site'] ) ) {
		if ( $settings['private_site'] == true ) {
			$privacy_status = '<h1 style="color:green;">SITE IS PRIVATE</h1>';
		}
	}
	$privacy_status = apply_filters( 'my_private_site_tab_site_privacy_status', $privacy_status );

	$section_desc  = '<i>Turn on or off the My Private Site security features.</i>';
	$section_desc .= $privacy_status;

	$section_options->add_field(
		array(
			'name'        => 'Make Site Private',
			'id'          => 'jr_ps_admin_site_privacy_title',
			'type'        => 'title',
			'after_field' => $section_desc,
		)
	);

	$section_options->add_field(
		array(
			'name'  => 'Site Privacy',
			'id'    => 'jr_ps_admin_site_privacy_enable',
			'type'  => 'checkbox',
			'after' => 'Enable login privacy',
		)
	);
	my_private_site_preload_cmb2_field_filter( 'jr_ps_admin_site_privacy_enable', $handler_function );

	$feature_desc  = 'Public Pages gives you choose the overall privacy mode of the site. You can set the site to ';
	$feature_desc .= 'private and then open some pages to the public. Or you can set the site to public and restrict ';
	$feature_desc .= 'access to just some specific pages.';
	$feature_url   = 'https://zatzlabs.com/project/my-private-site-public-pages/';
	$feature_desc  = my_private_site_get_feature_promo( $feature_desc, $feature_url, 'UPGRADE', ' ' );

	$section_options->add_field(
		array(
			'name'    => __( 'Site Privacy Mode' ),
			'id'      => 'jr_ps_admin_default_privacy_mode',
			'type'    => 'select',
			'default' => 'STANDARD',
			'options' => array( 'STANDARD' => 'Site Private, Some Pages Public' ),
			'desc'    => $feature_desc,
		)
	);

	$compatibility_mode = array(
		'STANDARD'  => 'Standard',
		'ELEMENTOR' => 'Theme Fix',
	);

	$compatibility_desc = "Adjust this setting if My Private Site doesn't properly block access for your theme.";

	$section_options->add_field(
		array(
			'name'    => __( 'Compatibility Mode' ),
			'id'      => 'jr_ps_admin_advanced_compatibility_mode',
			'type'    => 'select',
			'default' => 'STANDARD',
			// the index key of the label array below
			'options' => $compatibility_mode,
			'desc'    => $compatibility_desc,
		)
	);
	my_private_site_preload_cmb2_field_filter( 'jr_ps_admin_advanced_compatibility_mode', $handler_function );

	my_private_site_display_cmb2_submit_button(
		$section_options,
		array(
			'button_id'          => 'jr_ps_button_site_privacy_save',
			'button_text'        => 'Save Privacy Status',
			'button_success_msg' => 'Privacy status saved.',
			'button_error_msg'   => '',
		)
	);

	$section_options = apply_filters( 'my_private_site_tab_site_privacy_section_data_options', $section_options );
}

// site_privacy - PROCESS FORM SUBMISSIONS
function my_private_site_tab_site_privacy_process_buttons() {
	// Process Save changes button
	// This is a callback that has to be passed the full array for consideration
	// phpcs:ignore WordPress.Security.NonceVerification
	$_POST    = apply_filters( 'validate_page_slug_my_private_site_tab_site_privacy', $_POST );
	$settings = get_option( 'jr_ps_settings' );

	if ( isset( $_POST['jr_ps_button_site_privacy_save'], $_POST['jr_ps_button_site_privacy_save_nonce'] ) ) {
		if ( ! wp_verify_nonce( $_POST['jr_ps_button_site_privacy_save_nonce'], 'jr_ps_button_site_privacy_save' ) ) {
			wp_die( 'Security violation detected [A006]. Access denied.', 'Security violation', array( 'response' => 403 ) );
		}
		// these just check for value existence
		// phpcs:ignore WordPress.Security.NonceVerification
		if ( isset( $_POST['jr_ps_admin_site_privacy_enable'] ) ) {
			$settings['private_site'] = true;
		} else {
			$settings['private_site'] = false;
		}
		// these just check for value existence
		// phpcs:ignore WordPress.Security.NonceVerification
		if ( isset( $_POST['jr_ps_admin_advanced_compatibility_mode'] ) ) {
			$compatibility_mode             = trim( sanitize_text_field( $_POST['jr_ps_admin_advanced_compatibility_mode']) );
			$settings['compatibility_mode'] = $compatibility_mode;
		}
		$result = update_option( 'jr_ps_settings', $settings );
		my_private_site_flag_cmb2_submit_button_success( 'jr_ps_button_site_privacy_save' );
	}
}

function my_private_site_admin_site_privacy_preload( $data, $object_id, $args, $field ) {
	// find out what field we're getting
	$field_id = $args['field_id'];

	// get stored data from plugin
	$internal_settings = get_option( 'jr_ps_internal_settings' );
	$settings          = get_option( 'jr_ps_settings' );

	// Pull from existing My Private Site data formats
	switch ( $field_id ) {
		case 'jr_ps_admin_site_privacy_enable':
			if ( isset( $settings['private_site'] ) ) {
				return $settings['private_site'];
			} else {
				return false;
			}
			break;
		case 'jr_ps_admin_advanced_compatibility_mode':
			if ( isset( $settings['compatibility_mode'] ) ) {
				return $settings['compatibility_mode'];
			} else {
				return 'STANDARD';
			}
			break;
	}
}
