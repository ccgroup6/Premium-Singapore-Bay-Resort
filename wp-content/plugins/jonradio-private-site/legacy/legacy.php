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

function my_private_site_addon_version_check( $addon, $version ) {
	// validation code that will run in later versions to disable plugins, if necessary
	// false prevents the add-ons from doing anything
	return true;
}

function my_private_site_jr_ps_30_update_alert_message() {
	// if (isset ($_REQUEST['page'])) {
	// if ($_REQUEST['page'] != 'dgx_donate_menu_page') {
			$url = get_admin_url() . 'admin.php?page=my_private_site_tab_main';
			echo '<div class="error">';
			echo '<p>';
			echo esc_html__(
				'Alert - My Private Site has had a major update with new add-ons. ',
				'my-private-site'
			);
			echo '<A HREF="' . esc_url( $url) . '">Click here</A> ';
			echo esc_html__(
				'to learn about enabling your new features.',
				'my-private-site'
			);
			 echo '</p>';
			echo '</div>';
	// }
	// }
}

function my_private_site_clear_jr_ps_30_update_alert() {
	$internal_settings                          = get_option( 'jr_ps_internal_settings' );
	$internal_settings['jr_ps_30_notice_shown'] = true;
	update_option( 'jr_ps_internal_settings', $internal_settings );
}
