<?php
/**
 * Plugin Name: Jeg Elementor Kit
 * Plugin URI: http://jegtheme.com/
 * Description: Additional highly customizable widgets for Elementor page builder
 * Version: 2.4.4
 * Author: Jegstudio
 * Author URI: http://jegtheme.com
 * License: GPLv3
 * Text Domain: jeg-elementor-kit
 *
 * Elementor tested up to: 3.6.7
 * Elementor Pro tested up to: 3.7.2
 *
 * @author: Jegstudio
 * @since 1.0.0
 * @package jeg-elementor-kit
 */

/**
 * Initialize Plugin
 */
add_action(
	'plugins_loaded',
	function() {
		defined( 'JEG_ELEMENTOR_KIT' ) || define( 'JEG_ELEMENTOR_KIT', 'jeg-elementor-kit' );
		defined( 'JEG_ELEMENTOR_KIT_NAME' ) || define( 'JEG_ELEMENTOR_KIT_NAME', 'Jeg Elementor Kit' );
		defined( 'JEG_ELEMENTOR_KIT_VERSION' ) || define( 'JEG_ELEMENTOR_KIT_VERSION', '2.4.4' );
		defined( 'JEG_ELEMENTOR_KIT_URL' ) || define( 'JEG_ELEMENTOR_KIT_URL', plugins_url( JEG_ELEMENTOR_KIT ) );
		defined( 'JEG_ELEMENTOR_KIT_FILE' ) || define( 'JEG_ELEMENTOR_KIT_FILE', __FILE__ );
		defined( 'JEG_ELEMENTOR_KIT_BASE' ) || define( 'JEG_ELEMENTOR_KIT_BASE', plugin_basename( __FILE__ ) );
		defined( 'JEG_ELEMENTOR_KIT_DIR' ) || define( 'JEG_ELEMENTOR_KIT_DIR', plugin_dir_path( __FILE__ ) );
		defined( 'JEG_ELEMENTOR_KIT_ID' ) || define( 'JEG_ELEMENTOR_KIT_ID', 0 );

		defined( 'JEG_THEME_URL' ) || define( 'JEG_THEME_URL', JEG_ELEMENTOR_KIT_URL );
		defined( 'JEG_ELEMENT_THEME_URL' ) || define( 'JEG_ELEMENT_THEME_URL', JEG_ELEMENTOR_KIT_URL . '/lib/jeg-element' );

		if ( ! defined( 'JEG_VERSION' ) ) {
			require_once JEG_ELEMENTOR_KIT_DIR . 'lib/jeg-framework/bootstrap.php';
		}

		if ( ! defined( 'JEG_ELEMENT_VERSION' ) ) {
			require_once JEG_ELEMENTOR_KIT_DIR . 'lib/jeg-element/bootstrap.php';
		}

		require_once JEG_ELEMENTOR_KIT_DIR . 'autoload.php';
		require_once JEG_ELEMENTOR_KIT_DIR . 'helper.php';

		Jeg\Elementor_Kit\Init::instance();
	},
	99
);
