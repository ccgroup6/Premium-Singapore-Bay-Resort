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

// advanced - MENU ////
function my_private_site_admin_advanced_menu() {
	$args = array(
		'id'           => 'my_private_site_tab_advanced_page',
		'title'        => 'My Private Site - Advanced',
		// page title
		'menu_title'   => 'Advanced',
		// title on left sidebar
		'tab_title'    => 'Advanced',
		// title displayed on the tab
		'object_types' => array( 'options-page' ),
		'option_key'   => 'my_private_site_tab_advanced',
		'parent_slug'  => 'my_private_site_tab_main',
		'tab_group'    => 'my_private_site_tab_set',

	);

	// 'tab_group' property is supported in > 2.4.0.
	if ( version_compare( CMB2_VERSION, '2.4.0' ) ) {
		$args['display_cb'] = 'my_private_site_cmb_options_display_with_tabs';
	}

	do_action( 'my_private_site_tab_advanced_before', $args );

	// call on button hit for page save
	add_action( 'admin_post_my_private_site_tab_advanced', 'my_private_site_tab_advanced_process_buttons' );

	// clear previous error messages if coming from another page
	my_private_site_clear_cmb2_submit_button_messages( $args['option_key'] );

	$args          = apply_filters( 'my_private_site_tab_advanced_menu', $args );
	$addon_options = new_cmb2_box( $args );

	my_private_site_admin_advanced_section_data( $addon_options );
	my_private_site_admin_logs_section_data( $addon_options );

	do_action( 'my_private_site_tab_advanced_after', $addon_options );
}

add_action( 'cmb2_admin_init', 'my_private_site_admin_advanced_menu' );

// advanced - SECTION - DATA ////
function my_private_site_admin_advanced_section_data( $section_options ) {
	$handler_function = 'my_private_site_admin_advanced_preload'; // setup the preload handler function
	$home_url         = trim( get_home_url(), '\ /' );
	$section_options  = apply_filters( 'my_private_site_tab_advanced_section_data', $section_options );

	$section_desc = '<i>Choose advanced custom login options.</i>';

	$section_options->add_field(
		array(
			'name'        => 'Advanced Options',
			'id'          => 'jr_ps_admin_advanced_title',
			'type'        => 'title',
			'after_field' => $section_desc,
		)
	);

	$section_options->add_field(
		array(
			'name'  => 'Custom Login Page',
			'id'    => 'jr_ps_admin_advanced_enable_custom_login',
			'type'  => 'checkbox',
			'after' => 'Enable custom login page',
			// 'desc'  =>  'This is the same advanced option displayed on the General Settings admin panel' ),
		)
	);
	my_private_site_preload_cmb2_field_filter( 'jr_ps_admin_advanced_enable_custom_login', $handler_function );

	$section_options->add_field(
		array(
			'name' => 'Custom Login URL',
			'id'   => 'jr_ps_admin_advanced_url',
			'type' => 'text',
			'desc' => 'Add custom login page URL. Must begin with ' . $home_url . '.',
		)
	);
	my_private_site_preload_cmb2_field_filter( 'jr_ps_admin_advanced_url', $handler_function );

	if ( ! function_exists( 'my_private_site_pp_plugin_updater' ) ) {
		$section_options->add_field(
			array(
				'name' => 'Add Password Reset URL',
				'id'   => 'jr_ps_admin_advanced_password_reset_url',
				'type' => 'text',
				'desc' => 'Add public password reset page URL. Must begin with ' . $home_url . '.',
			)
		);
		my_private_site_preload_cmb2_field_filter( 'jr_ps_admin_advanced_password_reset_url', $handler_function );
	}

	// $compatibility_mode = array(
	// 'STANDARD'  => 'Standard',
	// 'ELEMENTOR' => 'Elementor Fix',
	// );
	//
	// $compatibility_desc = "Adjust this setting if My Private Site doesn't properly block access.";
	//
	// $section_options->add_field(array(
	// 'name'    => __('Compatibility Mode'),
	// 'id'      => 'jr_ps_admin_advanced_compatibility_mode',
	// 'type'    => 'select',
	// 'default' => 'STANDARD',
	// the index key of the label array below
	// 'options' => $compatibility_mode,
	// 'desc'    => $compatibility_desc,
	// ));
	// my_private_site_preload_cmb2_field_filter('jr_ps_admin_advanced_compatibility_mode', $handler_function);

	// although this feature was in Jonradio's original code, there's nothing he does with it other than set it
	// $section_options->add_field(array(
	// 'name'  => 'Validate Login URL',
	// 'id'    => 'jr_ps_admin_advanced_validate_login_url',
	// 'type'  => 'checkbox',
	// 'after' => 'URL for your custom login page must begin with ' . $home_url .
	// '<br><span style="color:red">It is recommended you leave this option checked.',
	// 'desc'  =>  'This is the same advanced option displayed on the General Settings admin panel' ),
	// ));
	// my_private_site_preload_cmb2_field_filter('jr_ps_admin_advanced_validate_login_url', $handler_function);

	$section_options->add_field(
		array(
			'name'  => 'Custom Landing Location',
			'id'    => 'jr_ps_admin_advanced_custom_landing',
			'type'  => 'checkbox',
			'after' => 'Allow landing location for custom login pages. ' .
			           '<br><span style="color:red">This is dangerous. It could permanently lock you out of your site.<br>' .
			           '<h1 style="color:red">If you lock yourself out, I will not be able to help you get back in!</h1>',
			// 'desc'  =>  'This is the same advanced option displayed on the General Settings admin panel' ),
		)
	);
	my_private_site_preload_cmb2_field_filter( 'jr_ps_admin_advanced_custom_landing', $handler_function );

	$section_desc = <<<EOD
<p>These settings allow you to specify a custom login page that ignores the standard WordPress login at
    THIS-SITE/wp-login.php.</p>

<p>If the Custom Login page is not based on the standard WordPress Login page, it may not accept the
    ?redirect_to=http://landingurl query that is automatically added to the URL of the custom login page. If this causes
    difficultly, choose the Omit ?redirect_to= from URL option on the Landing Page tab.</p>

<p>Even with a custom login page configured, the standard WordPress login page will still appear in certain
    circumstances, such as logging into the Admin panels.</p>

<p>The Custom Landing Location advanced option may, under some circumstances, lock you out of your own WordPress site
    and prevent visitors from viewing your site. To recover, you will have to rename or delete the
    /wp-contents/plugins/jonradio-private-site/ folder with FTP or a file manager provided with your web hosting. If you
    are not familiar with either of these methods for deleting files within your WordPress installation, you risk making
    your WordPress site completely inoperative. In other words, don't check that button unless you know what you're
    doing and are prepared to recover your site.</p>
EOD;

	$section_options->add_field(
		array(
			'name'        => 'Tips for advanced login options',
			'id'          => 'my_private_site_admin_public_pages_tips',
			'type'        => 'title',
			'after_field' => $section_desc,
		)
	);

	my_private_site_display_cmb2_submit_button(
		$section_options,
		array(
			'button_id'          => 'jr_ps_button_advanced_save',
			'button_text'        => 'Save Advanced Options',
			'button_success_msg' => 'Advanced options saved.',
			'button_error_msg'   => 'Please enter a valid URL',
		)
	);
	$section_options = apply_filters( 'my_private_site_tab_advanced_section_data_options', $section_options );
}

// LOGS - SECTION - DATA ////
function my_private_site_admin_logs_section_data( $section_options ) {
	$section_options->add_field(
		array(
			'name'    => __( 'Log Data', 'cmb2' ),
			'id'      => 'my_private_site_log_data',
			'type'    => 'title',
			'default' => 'log data',
		)
	);
	$section_options = apply_filters( 'my_private_site_tab_logs_section_data', $section_options );

	$debug_log_content = get_option( 'jr_ps_log' );
	$log_data          = '';

	if ( empty( $debug_log_content ) ) {
		$log_data = esc_html__( 'The log is empty.', 'my-private-site' );
	} else {
		foreach ( $debug_log_content as $debug_log_entry ) {
			if ( $log_data != '' ) {
				$log_data .= "\n";
			}
			$log_data .= esc_html( $debug_log_entry );
		}
	}

	$debug_mode = get_option( 'jr_ps_debug_mode' );
	if ( $debug_mode == 1 ) {
		// we're in debug, so we'll return lots of log info

		$display_options = array(
			'My Private Site Log Data' => $log_data,
			// Removes the default data by passing an empty value below.
			'Admin Page Framework'     => '',
			'Browser'                  => '',
		);
	} else {
		$display_options = array(
			'My Private Site Log Data' => $log_data,
			// Removes the default data by passing an empty value below.
			'Admin Page Framework'     => '',
			'WordPress'                => '',
			'PHP'                      => '',
			'Server'                   => '',
			'PHP Error Log'            => '',
			'MySQL'                    => '',
			'MySQL Error Log'          => '',
			'Browser'                  => '',
		);
	}

	$section_options->add_field(
		array(
			'name'    => 'System Information',
			'id'      => 'my_private_site_system_information',
			'type'    => 'textarea_code',
			'default' => $log_data,
		)
	);

	my_private_site_display_cmb2_submit_button(
		$section_options,
		array(
			'button_id'          => 'jr_ps_button_settings_logs_delete',
			'button_text'        => 'Delete Log',
			'button_success_msg' => 'Log deleted.',
			'button_error_msg'   => '',
		)
	);

	$section_options = apply_filters( 'my_private_site_tab_logs_section_data_options', $section_options );
}

// advanced - PROCESS FORM SUBMISSIONS
function my_private_site_tab_advanced_process_buttons() {
	// Process Save changes button
	// This is a callback that has to be passed the full array for consideration
	// phpcs:ignore WordPress.Security.NonceVerification
	$_POST = apply_filters( 'validate_page_slug_my_private_site_tab_advanced', $_POST );

	if ( isset( $_POST['jr_ps_button_advanced_save'], $_POST['jr_ps_button_advanced_save_nonce'] ) ) {
		if ( ! wp_verify_nonce( $_POST['jr_ps_button_advanced_save_nonce'], 'jr_ps_button_advanced_save' ) ) {
			wp_die( 'Security violation detected [A007]. Access denied.', 'Security violation', array( 'response' => 403 ) );
		}

		$settings = get_option( 'jr_ps_settings' );
		// these just check for value existence
		// phpcs:ignore WordPress.Security.NonceVerification
		if ( isset( $_POST['jr_ps_admin_advanced_url'] ) ) {
			$url = my_private_site_validate_url( esc_url_raw( $_POST['jr_ps_admin_advanced_url'] ) );
			if ( $url != false ) {
				$url = jr_v1_sanitize_url( $url );
			} else {
				$url = '';
			}
		} else {
			$url = '';
		}

		if ( ! function_exists( 'my_private_site_pp_plugin_updater' ) ) {
			// these just check for value existence
			// phpcs:ignore WordPress.Security.NonceVerification
			if ( isset( $_POST['jr_ps_admin_advanced_password_reset_url'] ) ) {
				$reset_url = my_private_site_validate_url( esc_url_raw( $_POST['jr_ps_admin_advanced_password_reset_url'] ) );
				if ( $reset_url != '' && $reset_url == false ) {
					my_private_site_flag_cmb2_submit_button_error(
						'jr_ps_button_advanced_save',
						'Valid password reset URL must be provided.'
					);

					return;
				}
				if ( $reset_url == false ) {
					$settings['excl_url'] = array();
				} else {
					$settings['excl_url'] = array(); // clear it just to be sure
					$url_array            = jr_v1_prep_url( $reset_url );
					$add_array            = array(
						$reset_url,
						$url_array,
					);
					$settings['excl_url'] = array( $add_array );
				}
			}
		}

		// these just check for value existence
		// phpcs:ignore WordPress.Security.NonceVerification
		if ( isset( $_POST['jr_ps_admin_advanced_enable_custom_login'] ) ) {
			// make sure a valid URL has been provided or set to empty
			if ( $url == '' ) {
				my_private_site_flag_cmb2_submit_button_error(
					'jr_ps_button_advanced_save',
					'URL must be provided if "Enable custom login page" is checked.'
				);

				return;
			}
			$settings['custom_login'] = true;
			$settings['login_url']    = $url;
		} else {
			if ( $url != '' ) {
				my_private_site_flag_cmb2_submit_button_error(
					'jr_ps_button_advanced_save',
					'Please check "Enable custom login page" to save custom login URL.'
				);

				return;
			}
			$settings['custom_login'] = false;
			$settings['login_url']    = '';
		}

		// these just check for value existence
		// phpcs:ignore WordPress.Security.NonceVerification
		if ( isset( $_POST['jr_ps_admin_advanced_custom_landing'] ) ) {
			$settings['override_omit'] = true;
		} else {
			$settings['override_omit'] = false;
		}

		update_option( 'jr_ps_settings', $settings );
		my_private_site_flag_cmb2_submit_button_success( 'jr_ps_button_advanced_save' );

		return;
	}

	if ( isset( $_POST['jr_ps_button_settings_logs_delete'], $_POST['jr_ps_button_settings_logs_delete_nonce'] ) ) {
		if ( ! wp_verify_nonce( $_POST['jr_ps_button_settings_logs_delete_nonce'], 'jr_ps_button_settings_logs_delete' ) ) {
			wp_die( 'Security violation detected [A015]. Access denied.', 'Security violation', array( 'response' => 403 ) );
		}
		delete_option( 'jr_ps_log' );
		my_private_site_flag_cmb2_submit_button_success( 'jr_ps_button_settings_logs_delete' );

		return;
	}
}

function my_private_site_admin_advanced_preload( $data, $object_id, $args, $field ) {
	// find out what field we're getting
	$field_id = $args['field_id'];

	// get stored data from plugin
	$internal_settings = get_option( 'jr_ps_internal_settings' );
	$settings          = get_option( 'jr_ps_settings' );

	// Pull from existing My Private Site data formats
	switch ( $field_id ) {
		case 'jr_ps_admin_advanced_enable_custom_login':
			if ( isset( $settings['custom_login'] ) ) {
				return $settings['custom_login'];
			} else {
				return false;
			}
			break;
		case 'jr_ps_admin_advanced_url':
			if ( isset( $settings['login_url'] ) ) {
				return $settings['login_url'];
			} else {
				return false;
			}
			break;
		case 'jr_ps_admin_advanced_password_reset_url':
			if ( ! function_exists( 'my_private_site_pp_plugin_updater' ) ) {
				if ( isset( $settings['excl_url'] ) ) {
					if ( isset( $settings['excl_url'][0][0] ) ) {
						return $settings['excl_url'][0][0];
					}
				} else {
					return false;
				}
			}
			break;
		case 'jr_ps_admin_advanced_validate_login_url':
			if ( isset( $settings['custom_login_onsite'] ) ) {
				return $settings['custom_login_onsite'];
			} else {
				return false;
			}
			break;
		// case 'jr_ps_admin_advanced_compatibility_mode':
		// if (isset($settings['compatibility_mode'])) {
		// return $settings['compatibility_mode'];
		// } else {
		// return 'STANDARD';
		// }
		// break;
		case 'jr_ps_admin_advanced_custom_landing':
			if ( isset( $settings['override_omit'] ) ) {
				return $settings['override_omit'];
			} else {
				return false;
			}
			break;
	}
}
