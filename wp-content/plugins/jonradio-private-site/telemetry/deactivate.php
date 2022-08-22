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


// based on code from https://github.com/CodeCabin/plugin-deactivation-survey



add_filter(
	'my_private_site_deactivate_feedback_form_plugins',
	function ( $plugins ) {
		$time_now          = time();
		$time_then         = get_option( 'jr_ps_first_run_time' );
		$internal_settings = get_option( 'jr_ps_internal_settings' );

		$plugins[] = (object) array(
			'slug'         => 'jonradio-private-site',
			'version'      => $internal_settings['version'],
			'timeNow'      => $time_now,
			'installTime'  => $time_then,
			'useDuration'  => $time_now - $time_then,
			'telemetryUrl' => my_private_site_telemetry_url(),
		);
		return $plugins;
	}
);

if ( ! is_admin() ) {
	return;
}

global $pagenow;

if ( $pagenow != 'plugins.php' ) {
	return;
}

if ( defined( 'MY_PRIVATE_SITE_DEACTIVATE_FEEDBACK_FORM_INCLUDED' ) ) {
	return;
}
define( 'MY_PRIVATE_SITE_DEACTIVATE_FEEDBACK_FORM_INCLUDED', true );

add_action(
	'admin_enqueue_scripts',
	function () {
		// Enqueue scripts
		wp_enqueue_script( 'my-private-site-deactivate-feedback-form', plugin_dir_url( __FILE__ ) . 'js/deactivate.js' );
		wp_enqueue_style( 'my-private-site-deactivate-feedback-form', plugin_dir_url( __FILE__ ) . 'css/deactivate.css' );

		// Localized strings
		wp_localize_script(
			'my-private-site-deactivate-feedback-form',
			'my_private_site_deactivate_feedback_form_strings',
			array(
				'quick_feedback'        => __( 'Help us improve. Why are you deactivating?', 'my-private-site' ),
				'foreword'              => __( 'Your feedback is fully anonymous and will be read directly by the lead developer', 'my-private-site' ),
				'better_plugins_name'   => __( 'Please tell us which plugin?', 'my-private-site' ),
				'please_tell_us'        => __( 'Please tell us the reason so we can improve the plugin', 'my-private-site' ),
				'do_not_attach_email'   => __( 'Do not send my e-mail address with this feedback', 'my-private-site' ),

				'brief_description'     => __( 'Please share any feedback you wish', 'my-private-site' ),

				'cancel'                => __( 'Cancel', 'my-private-site' ),
				'skip_and_deactivate'   => __( 'Skip &amp; Deactivate', 'my-private-site' ),
				'submit_and_deactivate' => __( 'Submit &amp; Deactivate', 'my-private-site' ),
				'please_wait'           => __( 'Please wait', 'my-private-site' ),
				'thank_you'             => __( 'Thank you!', 'my-private-site' ),
			)
		);

		// Plugins
		$plugins = apply_filters( 'my_private_site_deactivate_feedback_form_plugins', array() );

		// Reasons
		$defaultReasons = array(
			'no-longer-needed'       => __( 'I don\'t need My Private Site any more', 'my-private-site' ),
			'missing-feature'        => __( 'My Private Site is missing a feature I need', 'my-private-site' ),
			'not-get-to-work'        => __( 'I couldn\'t get it to work right', 'my-private-site' ),
			'found-better-plugin'    => __( 'I found a plugin I like better', 'my-private-site' ),
			'plugin-broke-site'      => __( 'My Private Site broke my site', 'my-private-site' ),
			'short-period'           => __( 'I only needed My Private Site for a short period', 'my-private-site' ),
			'temporary-deactivation' => __( 'It\'s a temporary deactivation. I\'m troubleshooting', 'my-private-site' ),
			'other'                  => __( 'Other', 'my-private-site' ),
		);

		foreach ( $plugins as $plugin ) {
			$plugin->reasons = apply_filters( 'my_private_site_deactivate_feedback_form_reasons', $defaultReasons, $plugin );
		}

		// Send plugin data
		wp_localize_script( 'my-private-site-deactivate-feedback-form', 'my_private_site_deactivate_feedback_form_plugins', $plugins );
	}
);

/**
 * Hook for adding plugins, pass an array of objects in the following format:
 *  'slug'        => 'plugin-slug'
 *  'version'    => 'plugin-version'
 *
 * @return array The plugins in the format described above
 */
add_filter(
	'my_private_site_deactivate_feedback_form_plugins',
	function ( $plugins ) {
		return $plugins;
	}
);

