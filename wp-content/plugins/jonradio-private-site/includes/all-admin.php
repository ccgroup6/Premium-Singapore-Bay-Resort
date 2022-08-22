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

/*
	Loaded for all Admin panels.
*/

// Exit if .php file accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$internal_settings = get_option( 'jr_ps_internal_settings' );
if ( isset( $internal_settings['warning_privacy'] ) ) {
	add_action( 'all_admin_notices', 'jr_ps_warning_privacy' );

	/**
	 * Warn that Private Site is turned OFF by default
	 *
	 * Put Warning on top of every Admin page (visible to Admins only)
	 * until Admin visits plugin's Settings page.
	 */
	function jr_ps_warning_privacy() {
		global $jr_ps_plugin_data;
		if ( current_user_can( 'manage_options' ) ) {
			echo '<div class="updated"><p><b>Site is not private. <a href="'
				. esc_url( admin_url( 'options-general.php?page=my_private_site_tab_main' ))
				. '">Customize My Private Site settings</a>.</b></p></div>';
		}
	}
}
if ( $internal_settings !== false ) {
	// only show if this is an upgrading user
	if ( ! isset( $internal_settings['jr_ps_30_notice_shown'] ) ) {
		add_action( 'admin_notices', 'my_private_site_jr_ps_30_update_alert_message' );
	}
}

