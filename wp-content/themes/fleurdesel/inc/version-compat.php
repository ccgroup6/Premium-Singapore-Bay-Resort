<?php
/**
 * Fleurdesel back compat functionality.
 *
 * @package Fleurdesel
 */

/**
 * Fleurdesel only works in WordPress 4.6 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.6', '<' ) ) :
	/**
	 * Prevent switching to Fleurdesel on old versions of WordPress.
	 *
	 * Switches to the default theme.
	 */
	function fleurdesel_switch_theme() {
		switch_theme( WP_DEFAULT_THEME, WP_DEFAULT_THEME );

		unset( $_GET['activated'] );

		add_action( 'admin_notices', 'fleurdesel_upgrade_notice' );
	}
	add_action( 'after_switch_theme', 'fleurdesel_switch_theme' );

	/**
	 * Adds a message for unsuccessful theme switch.
	 *
	 * Prints an update nag after an unsuccessful attempt to switch to
	 * Fleurdesel on WordPress versions prior to 4.6.
	 *
	 * @global string $wp_version WordPress version.
	 */
	function fleurdesel_upgrade_notice() {
		$message = sprintf( esc_html__( 'Fleurdesel requires at least WordPress version 4.5. You are running version %s. Please upgrade and try again.', 'fleurdesel' ), $GLOBALS['wp_version'] );
		printf( '<div class="error"><p>%s</p></div>', $message ); // WPCS: XSS OK.
	}
endif;

/**
 * And only works with PHP 5.3.9 or later.
 */
if ( version_compare( phpversion(), '5.3.9', '<' ) ) :
	/**
	 * Prevent switching to Fleurdesel on old versions of PHP.
	 *
	 * Switches to the default theme.
	 */
	function fleurdesel_phpcompat_switch_theme() {
		switch_theme( WP_DEFAULT_THEME, WP_DEFAULT_THEME );

		unset( $_GET['activated'] );

		add_action( 'admin_notices', 'fleurdesel_phpcompat_upgrade_notice' );
	}
	add_action( 'after_switch_theme', 'fleurdesel_phpcompat_switch_theme' );

	/**
	 * Adds a message for outdate PHP version.
	 */
	function fleurdesel_phpcompat_upgrade_notice() {
		$message = sprintf( esc_html__( 'Fleurdesel requires at least PHP version 5.3.9. You are running version %s.', 'fleurdesel' ), phpversion() );
		printf( '<div class="error"><p>%s</p></div>', $message ); // WPCS: XSS OK.
	}
endif;
