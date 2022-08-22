<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'De_Sticky_Assets' ) ) {

	/**
	 * Define De_Sticky_Assets class
	 */
	class De_Sticky_Assets {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Localize data
		 *
		 * @var array
		 */
		public $elements_data = array(
			'sections' => array(),
			'columns'  => array(),
		);

		/**
		 * Constructor for the class
		 */
		public function init() {
			add_action( 'elementor/frontend/after_enqueue_styles',   array( $this, 'enqueue_styles' ) );
			add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );
			add_action( 'admin_enqueue_scripts',  array( $this, 'admin_enqueue_styles' ) );
		}

		/**
		 * Enqueue public-facing stylesheets.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function enqueue_styles() {

			wp_enqueue_style(
				'de-sticky-frontend',
				DETHEMEKIT_ADDONS_URL . 'assets/css/de-sticky-frontend.css' ,
				false,
				DETHEMEKIT_ADDONS_VERSION
			);
			wp_enqueue_style(
				'de-product-display',
				DETHEMEKIT_ADDONS_URL . 'assets/css/de-product-display.css' ,
				false,
				DETHEMEKIT_ADDONS_VERSION
			);
		}

		/**
		 * Enqueue plugin scripts only with elementor scripts
		 *
		 * @return void
		 */
		public function enqueue_scripts() {

			wp_enqueue_script(
				'de-sticky-frontend',
				DETHEMEKIT_ADDONS_URL . 'assets/js/de-sticky-frontend.js' ,
				array( 'jquery', 'elementor-frontend' ),
				DETHEMEKIT_ADDONS_VERSION,
				true
			);

			wp_enqueue_script(
				'de-active-icon-box',
				DETHEMEKIT_ADDONS_URL . 'assets/js/de-active-icon-box.js' ,
				array( 'jquery', 'elementor-frontend' ),
				DETHEMEKIT_ADDONS_VERSION,
				true
			);

			wp_enqueue_script(
				'de-active-column',
				DETHEMEKIT_ADDONS_URL . 'assets/js/de-active-column.js' ,
				array( 'jquery', 'elementor-frontend' ),
				DETHEMEKIT_ADDONS_VERSION,
				true
			);

			// wp_localize_script( 'de-sticky-frontend', 'DeStickySettings', array(
			// 	'elements_data' => $this->elements_data,
			// ) );
			wp_localize_script( 'de-sticky-frontend', 'DeStickySettings', array(
				'elements_data' => $this->elements_data,
			) );

			wp_localize_script( 'de-rico-sticky', 'DeRicoSettings', array(
				'elements_data' => $this->elements_data,
			) );
		}

		/**
		 * Enqueue admin styles
		 *
		 * @return void
		 */
		public function admin_enqueue_styles() {
			$screen = get_current_screen();

			if ( 'plugins' === $screen->base ) {
				wp_enqueue_style(
					'de-sticky-admin',
					DETHEMEKIT_ADDONS_URL . 'assets/css/de-sticky-admin.css' ,
					false,
					DETHEMEKIT_ADDONS_VERSION
				);
			}
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}

}

function de_sticky_assets() {
	return De_Sticky_Assets::get_instance();
}
