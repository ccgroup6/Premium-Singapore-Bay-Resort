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

// membership - MENU ////
function my_private_site_admin_membership_menu() {
	$args = array(
		'id'           => 'my_private_site_tab_membership_page',
		'title'        => 'My Private Site - Membership',
		// page title
		'menu_title'   => 'Membership',
		// title on left sidebar
		'tab_title'    => 'Membership',
		// title displayed on the tab
		'object_types' => array( 'options-page' ),
		'option_key'   => 'my_private_site_tab_membership',
		'parent_slug'  => 'my_private_site_tab_main',
		'tab_group'    => 'my_private_site_tab_set',

	);

	// 'tab_group' property is supported in > 2.4.0.
	if ( version_compare( CMB2_VERSION, '2.4.0' ) ) {
		$args['display_cb'] = 'my_private_site_cmb_options_display_with_tabs';
	}

	do_action( 'my_private_site_tab_membership_before', $args );

	// call on button hit for page save
	add_action( 'admin_post_my_private_site_tab_membership', 'my_private_site_tab_membership_process_buttons' );

	// clear previous error messages if coming from another page
	my_private_site_clear_cmb2_submit_button_messages( $args['option_key'] );

	$args          = apply_filters( 'my_private_site_tab_membership_menu', $args );
	$addon_options = new_cmb2_box( $args );

	my_private_site_admin_membership_section_data( $addon_options );

	do_action( 'my_private_site_tab_membership_after', $addon_options );
}

add_action( 'cmb2_admin_init', 'my_private_site_admin_membership_menu' );

// membership - SECTION - DATA ////
function my_private_site_admin_membership_section_data( $section_options ) {
	$handler_function = 'my_private_site_admin_membership_preload'; // setup the preload handler function

	$section_options = apply_filters( 'my_private_site_tab_membership_section_data', $section_options );

	$section_desc = '<i>Choose whether users are allowed to self-register on this private site.</i>';
	$section_desc .= '<i> Both checkboxes must be checked for users to be able to self-register.</i>';

	$section_options->add_field(
		array(
			'name'        => 'Membership and Registration',
			'id'          => 'jr_ps_admin_membership_title',
			'type'        => 'title',
			'after_field' => $section_desc,
		)
	);

	$section_options->add_field(
		array(
			'name'  => 'Membership',
			'id'    => 'jr_ps_admin_membership_register',
			'type'  => 'checkbox',
			'after' => 'Anyone can register',
			// 'desc'  =>  'This is the same Membership option displayed on the General Settings admin panel' ),
		)
	);
	my_private_site_preload_cmb2_field_filter( 'jr_ps_admin_membership_register', $handler_function );

	$section_options->add_field(
		array(
			'name'  => 'Reveal Registration Page',
			'id'    => 'jr_ps_admin_membership_reveal',
			'type'  => 'checkbox',
			'after' => 'Do not block standard User Registration page (required to self-register)',
			// 'desc'  =>  'This is the same Membership option displayed on the General Settings admin panel' ),
		)
	);
	my_private_site_preload_cmb2_field_filter( 'jr_ps_admin_membership_reveal', $handler_function );

	my_private_site_display_cmb2_submit_button(
		$section_options,
		array(
			'button_id'          => 'jr_ps_button_membership_save',
			'button_text'        => 'Update Options',
			'button_success_msg' => 'Options updated.',
			'button_error_msg'   => '',
		)
	);
	$section_options = apply_filters( 'my_private_site_tab_membership_section_data_options', $section_options );
}

// membership - PROCESS FORM SUBMISSIONS
function my_private_site_tab_membership_process_buttons() {
	// Process Save changes button
	// This is a callback that has to be passed the full array for consideration
	// phpcs:ignore WordPress.Security.NonceVerification
	$_POST = apply_filters( 'validate_page_slug_my_private_site_tab_membership', $_POST );

	if ( isset( $_POST['jr_ps_button_membership_save'], $_POST['jr_ps_button_membership_save_nonce'] ) ) {
		if ( ! wp_verify_nonce( $_POST['jr_ps_button_membership_save_nonce'], 'jr_ps_button_membership_save' ) ) {
			wp_die( 'Security violation detected [A016]. Access denied.', 'Security violation', array( 'response' => 403 ) );
		}

		$settings = get_option( 'jr_ps_settings' );
		// these just check for value existence
		// phpcs:ignore WordPress.Security.NonceVerification
		if ( isset( $_POST['jr_ps_admin_membership_register'] ) ) {
			update_option( 'users_can_register', true );
		} else {
			update_option( 'users_can_register', false );
		}
		// these just check for value existence
		// phpcs:ignore WordPress.Security.NonceVerification
		if ( isset( $_POST['jr_ps_admin_membership_reveal'] ) ) {
			$settings['reveal_registration'] = true;
		} else {
			$settings['reveal_registration'] = false;
		}
		$result = update_option( 'jr_ps_settings', $settings );
		my_private_site_flag_cmb2_submit_button_success( 'jr_ps_button_membership_save' );
	}
}

function my_private_site_admin_membership_preload( $data, $object_id, $args, $field ) {
	// find out what field we're getting
	$field_id = $args['field_id'];

	// get stored data from plugin
	$internal_settings = get_option( 'jr_ps_internal_settings' );
	$settings          = get_option( 'jr_ps_settings' );
	$wp_user_reg       = get_option( 'users_can_register' ); // this is a standard WordPress option

	// Pull from existing My Private Site data formats
	switch ( $field_id ) {
		case 'jr_ps_admin_membership_register':
			if ( isset( $wp_user_reg ) ) {
				return $wp_user_reg;
			} else {
				return false;
			}
			break;
		case 'jr_ps_admin_membership_reveal':
			if ( isset( $settings['reveal_registration'] ) ) {
				return $settings['reveal_registration'];
			} else {
				return false;
			}
			break;
	}
}
