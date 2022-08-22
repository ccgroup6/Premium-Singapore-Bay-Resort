<?php
namespace De_Sina_Extension;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * De_Sina_Extension_Base Class for basic functionality
 *
 * @since 3.0.0
 */
abstract class De_Sina_Extension_Base{
	/**
	 * Load Action Hooks
	 *
	 * @since 3.0.0
	 */
	public function load_actions() {
		add_action( 'init', [ $this, 'i18n' ] );
	}

	/**
	 * Load Textdomain
	 *
	 * @since 1.0.0
	 */
	public function i18n() {
		load_plugin_textdomain( 'detheme-kit', false, DETHEMEKIT_ADDONS_DIRNAME.'/languages' );
	}
}