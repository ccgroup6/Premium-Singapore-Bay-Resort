<?php
namespace De_Sina_Extension;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Plugin;
use \De_Sina_Extension\De_Sina_Extension_Base;
use \De_Sina_Extension\De_Sina_Ext_Controls;

/**
 * De_Sina_Ext_Functions Class For widgets functionality
 *
 * @since 3.0.0
 */
abstract class De_Sina_Ext_Functions extends De_Sina_Extension_Base{
	 /**
	 * Enqueue CSS files
	 *
	 * @since 3.0.0
	 */
	public function widget_styles() {
		wp_enqueue_style( 'sina-morphing-anim', DETHEMEKIT_ADDONS_SINA_EXT_URL .'assets/css/sina-morphing.min.css', [], DETHEMEKIT_ADDONS_VERSION );
	}

	/**
	 * Enqueue CSS files
	 *
	 * @since 3.0.0
	 */
	public function de_scroll_animation_widget_styles() {
    	wp_enqueue_style( 'de-scroll-animation-css', DETHEMEKIT_ADDONS_SINA_EXT_URL .'assets/css/de-scroll-animation.css', [], DETHEMEKIT_ADDONS_VERSION );
  	}

	/**
	 * Enqueue CSS files
	 *
	 * @since 3.0.0
	 */
	public function de_reveal_animation_widget_styles() {
    	wp_enqueue_style( 'de-reveal-animation-css', DETHEMEKIT_ADDONS_SINA_EXT_URL .'assets/css/de-reveal-animation.css', [], DETHEMEKIT_ADDONS_VERSION );

		wp_enqueue_style( 'de-curtain-animation-revealer', DETHEMEKIT_ADDONS_SINA_EXT_URL .'assets/css/revealer.css', [], DETHEMEKIT_ADDONS_VERSION );
		wp_enqueue_style( 'de-reveal-curtain-animation-css', DETHEMEKIT_ADDONS_SINA_EXT_URL .'assets/css/de-reveal-curtain-animation.css', [], DETHEMEKIT_ADDONS_VERSION );


		// Letter Animation CSS
		wp_enqueue_style( 'de-reveal-letter-decolines-css', DETHEMEKIT_ADDONS_SINA_EXT_URL .'assets/css/letter/decolines.css', [], DETHEMEKIT_ADDONS_VERSION );
		wp_enqueue_style( 'de-reveal-letter-normalize-css', DETHEMEKIT_ADDONS_SINA_EXT_URL .'assets/css/letter/normalize.css', [], DETHEMEKIT_ADDONS_VERSION );
		wp_enqueue_style( 'de-reveal-letter-lettereffect-css', DETHEMEKIT_ADDONS_SINA_EXT_URL .'assets/css/letter/lettereffect.css', [], DETHEMEKIT_ADDONS_VERSION );
		wp_enqueue_style( 'de-reveal-letter-pater-css', DETHEMEKIT_ADDONS_SINA_EXT_URL .'assets/css/letter/pater.css', [], DETHEMEKIT_ADDONS_VERSION );
  }

	/**
	 * Enqueue CSS files
	 *
	 * @since 3.0.0
	 */
	public function de_staggering_widget_styles() {
		// De Staggering
		wp_enqueue_style( 'de-staggering-animate', DETHEMEKIT_ADDONS_SINA_EXT_URL .'assets/css/de_staggering/animate.css', [], DETHEMEKIT_ADDONS_VERSION );

		wp_enqueue_style( 'de-staggering-css', DETHEMEKIT_ADDONS_SINA_EXT_URL .'assets/css/de_staggering/de-staggering.css', [], DETHEMEKIT_ADDONS_VERSION );
  }

	 /**
	 * Enqueue JS files
	 *
	 * @since 3.0.0
	 */
	public function widget_scripts() {
		wp_enqueue_script( 'dethemekit-anime-js', DETHEMEKIT_ADDONS_SINA_EXT_URL .'assets/js/anime.min.js');
	}

  /**
	 * Enqueue JS files
	 *
	 * @since 3.0.0
	 */
	public function de_scroll_animation_widget_scripts() {
		wp_enqueue_script( 'de-scroll-animation-scrollmonitor', DETHEMEKIT_ADDONS_SINA_EXT_URL .'assets/js/scrollMonitor.js');
    	wp_enqueue_script( 'de-scroll-animation-preview-js', DETHEMEKIT_ADDONS_SINA_EXT_URL .'assets/js/de_scroll_animation.preview.js', [ 'elementor-frontend' ], false, true);
  	}

  /**
	 * Enqueue JS files
	 *
	 * @since 3.0.0
	 */
	public function de_reveal_animation_widget_scripts() {
		wp_enqueue_script( 'de-reveal-animation-intersection-observer', DETHEMEKIT_ADDONS_SINA_EXT_URL .'assets/js/intersectionobserver.js', [ 'elementor-frontend' ], false, true);

		wp_enqueue_script( 'de-reveal-letter-charming-js', DETHEMEKIT_ADDONS_SINA_EXT_URL .'assets/js/letter/charming.min.js', [ 'elementor-frontend' ], false, true);
		wp_enqueue_script( 'de-reveal-letter-lineMaker-js', DETHEMEKIT_ADDONS_SINA_EXT_URL .'assets/js/letter/lineMaker.js', [ 'elementor-frontend' ], false, true);
		wp_enqueue_script( 'de-reveal-letter-imagesloaded-js', DETHEMEKIT_ADDONS_SINA_EXT_URL .'assets/js/letter/imagesloaded.pkgd.min.js', [ 'elementor-frontend' ], false, true);
		wp_enqueue_script( 'de-reveal-letter-textfx-js', DETHEMEKIT_ADDONS_SINA_EXT_URL .'assets/js/letter/textfx.js', [ 'elementor-frontend' ], false, true);

		// wp_enqueue_script( 'de-reveal-animation-observer', DETHEMEKIT_ADDONS_SINA_EXT_URL .'assets/js/animation_observer.js', [ 'elementor-frontend', 'de-reveal-animation-intersection-observer' ], false, true);
		wp_enqueue_script( 'de-curtain-animation-main', DETHEMEKIT_ADDONS_SINA_EXT_URL .'assets/js/main.js');
		wp_enqueue_script( 'de-reveal-animation-preview', DETHEMEKIT_ADDONS_SINA_EXT_URL .'assets/js/de_reveal_animation.preview.js', [ 'elementor-frontend', 'de-reveal-animation-intersection-observer' ], false, true);
  }

  /**
	 * Enqueue JS files
	 *
	 * @since 3.0.0
	 */
	public function de_staggering_widget_scripts() {
		// DE STAGGERING
		wp_enqueue_script( 'de-staggering', DETHEMEKIT_ADDONS_SINA_EXT_URL .'assets/js/de_staggering/de_staggering.js', [ 'elementor-frontend' ], false, true);
  }

	/**
	 * Initialize the plugin
	 *
	 * @since 3.0.0
	 */
	public function init() {
		$dethemekit_option = get_option( 'dethemekit_option' );

		// Enqueue Widget Styles
		add_action( 'elementor/frontend/after_register_styles', [ $this, 'widget_styles' ] );

		// Enqueue Widget Scripts
		add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'widget_scripts' ] );

		$this->files();
		$this->load_actions();

		De_Sina_Ext_Controls::instance();

		if ( isset( $dethemekit_option['de_scroll_animation'] ) && $dethemekit_option['de_scroll_animation']) {
      // Enqueue Widget Styles
      add_action( 'elementor/frontend/after_register_styles', [ $this, 'de_scroll_animation_widget_styles' ] );

      // Enqueue Widget Scripts
      add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'de_scroll_animation_widget_scripts' ] );

      De_Scroll_Animation_Controls::instance();
		}

		if ( isset( $dethemekit_option['de_reveal_animation'] ) && $dethemekit_option['de_reveal_animation']) {
      // Enqueue Widget Styles
      add_action( 'elementor/frontend/after_register_styles', [ $this, 'de_reveal_animation_widget_styles' ] );

      // Enqueue Widget Scripts
      add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'de_reveal_animation_widget_scripts' ] );

      De_Reveal_Animation_Controls::instance();
		}

		if ( isset( $dethemekit_option['de_staggering'] ) && $dethemekit_option['de_staggering']) {
      // Enqueue Widget Styles
      add_action( 'elementor/frontend/after_register_styles', [ $this, 'de_staggering_widget_styles' ] );

      // Enqueue Widget Scripts
      add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'de_staggering_widget_scripts' ] );

      De_Staggering_Controls::instance();
		}
	}

	/**
	 * Include helper & hooks files
	 *
	 * @since 3.0.0
	 */
	public function files() {
		require_once( DETHEMEKIT_ADDONS_SINA_EXT_INC .'de-sina-ext-controls-extend.php' );
		require_once( DETHEMEKIT_ADDONS_SINA_EXT_INC .'de-scroll-animation-controls.php' );
		require_once( DETHEMEKIT_ADDONS_SINA_EXT_INC .'de-reveal-animation-controls.php' );
		require_once( DETHEMEKIT_ADDONS_SINA_EXT_INC .'de-staggering-controls.php' );
	}
}