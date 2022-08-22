<?php

namespace DethemeKitAddons;

use DethemeKitAddons\Admin\Settings\Maps;
use DethemeKitAddons\Admin\Settings\Modules_Settings;
use DethemeKitAddons\Helper_Functions;

if( ! defined( 'ABSPATH' ) ) exit();

class Addons_Integration {
    
    //Class instance
    private static $instance = null;
    
    //Modules Keys
    private static $modules = null;
    
    //`dethemekit_Template_Tags` Instance
    protected $templateInstance;


    //Maps Keys
    private static $maps = null;
    
    
    /**
    * Initialize integration hooks
    *
    * @return void
    */
    public function __construct() {
        
        self::$modules = Modules_Settings::get_enabled_keys();
        
        self::$maps = Maps::get_enabled_keys();
        
        $this->templateInstance = Includes\dethemekit_Template_Tags::getInstance();
        
        add_action( 'elementor/editor/before_enqueue_styles', array( $this, 'dethemekit_font_setup' ) );
        
        add_action( 'elementor/widgets/widgets_registered', array( $this, 'widgets_area' ) );
        
        add_action( 'elementor/editor/before_enqueue_scripts', array( $this,'enqueue_editor_scripts') );
        
        add_action( 'elementor/preview/enqueue_styles', array( $this, 'enqueue_preview_styles' ) );
        
        add_action( 'elementor/frontend/after_register_styles', array( $this, 'register_frontend_styles' ) );
        
        add_action( 'elementor/frontend/after_register_scripts', array( $this, 'register_frontend_scripts' ) );
        
        add_action( 'wp_ajax_get_elementor_template_content', array( $this, 'get_template_content' ) );
        
    }
    
    /**
    * Loads plugin icons font
    * @since 1.0.0
    * @access public
    * @return void
    */
    public function dethemekit_font_setup() {
        
        wp_enqueue_style(
            'dethemekit-addons-font',
            DETHEMEKIT_ADDONS_URL . 'assets/editor/css/style.css',
            array(),
            DETHEMEKIT_ADDONS_VERSION
        );
        
        $badge_text = Helper_Functions::get_badge();
        
        $dynamic_css = sprintf( '[class^="pa-"]::after, [class*=" pa-"]::after { content: "%s"; }', $badge_text ) ;

        wp_add_inline_style( 'dethemekit-addons-font',  $dynamic_css );
        
    }
    
    /** 
    * Register Frontend CSS files
    * @since 2.9.0
    * @access public
    */
    public function register_frontend_styles() {
        
        $dir = Helper_Functions::get_styles_dir();
		$suffix = Helper_Functions::get_assets_suffix();
        
        $is_rtl = is_rtl() ? '-rtl' : '';

        wp_register_style(
			'font-awesome-5-all',
			ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/all.min.css',
			false,
			DETHEMEKIT_ADDONS_VERSION
		);
        
        
        wp_register_style(
            'pa-prettyphoto',
            DETHEMEKIT_ADDONS_URL . 'assets/frontend/' . $dir . '/prettyphoto' . $is_rtl . $suffix . '.css',
            array(),
            DETHEMEKIT_ADDONS_VERSION,
            'all'
        );
        
        wp_register_style(
            'dethemekit-addons',
            DETHEMEKIT_ADDONS_URL . 'assets/frontend/' . $dir . '/dethemekit-addons' . $is_rtl . $suffix . '.css',
            array(),
            DETHEMEKIT_ADDONS_VERSION,
            'all'
        );
        
    }
    
    /**
     * Enqueue Preview CSS files
     * 
     * @since 2.9.0
     * @access public
     * 
     */
    public function enqueue_preview_styles() {
        
        wp_enqueue_style( 'pa-prettyphoto' );
        
        wp_enqueue_style( 'dethemekit-addons' );

    }
    
    /** 
     * Load widgets require function
     * 
     * @since 1.0.0
     * @access public
     * 
     */
    public function widgets_area() {
        $this->widgets_register();
    }
    
    /**
     * Requires widgets files
     * 
     * @since 1.0.0
     * @access private
     */
    private function widgets_register() {
        $dethemekit_option = get_option( 'dethemekit_option' );

        $check_component_active = self::$modules;

        foreach ( glob( DETHEMEKIT_ADDONS_PATH . 'widgets/' . '*.php' ) as $file ) {
            
            $slug = basename( $file, '.php' );
            
            $enabled = isset( $check_component_active[ $slug ] ) ? $check_component_active[ $slug ] : '';

            if ( $slug === 'dethemekit-carousel' ) {
                $enabled = isset( $dethemekit_option['de_carousel'] ) && $dethemekit_option['de_carousel'];
            } 

            if ( $slug === 'dethemekit-grid' ) {
                $enabled = isset( $dethemekit_option['de_gallery'] ) && $dethemekit_option['de_gallery'];
            } 

            if ( filter_var( $enabled, FILTER_VALIDATE_BOOLEAN ) || ! $check_component_active ) {
                $this->register_addon( $file );
            }
        }

    }
    
    /**
     * Registers required JS files
     * 
     * @since 1.0.0
     * @access public
    */
    public function register_frontend_scripts() {
        
        $maps_settings = self::$maps;
        
        $dir = Helper_Functions::get_scripts_dir();
		$suffix = Helper_Functions::get_assets_suffix();
        
        $locale = isset ( $maps_settings['dethemekit-map-locale'] ) ? $maps_settings['dethemekit-map-locale'] : "en";
        
        wp_register_script(
            'dethemekit-addons-js',
            DETHEMEKIT_ADDONS_URL . 'assets/frontend/' . $dir . '/dethemekit-addons' . $suffix . '.js',
            array('jquery'),
            DETHEMEKIT_ADDONS_VERSION,
            true
        );
        
        wp_register_script(
            'prettyPhoto-js',
            DETHEMEKIT_ADDONS_URL . 'assets/frontend/' . $dir . '/prettyPhoto' . $suffix . '.js',
            array('jquery'),
            DETHEMEKIT_ADDONS_VERSION,
            true
        );
        
        wp_register_script(
            'vticker-js',
            DETHEMEKIT_ADDONS_URL . 'assets/frontend/' . $dir . '/vticker' . $suffix . '.js',
            array('jquery'),
            DETHEMEKIT_ADDONS_VERSION,
            true
        );
        wp_register_script(
            'typed-js',
            DETHEMEKIT_ADDONS_URL . 'assets/frontend/' . $dir . '/typed' . $suffix . '.js',
            array('jquery'),
            DETHEMEKIT_ADDONS_VERSION,
            true
        );
        
        wp_register_script(
            'count-down-timer-js',
            DETHEMEKIT_ADDONS_URL . 'assets/frontend/' . $dir . '/jquery-countdown' . $suffix . '.js',
            array('jquery'),
            DETHEMEKIT_ADDONS_VERSION,
            true
        );
       
        wp_register_script(
            'isotope-js',
            DETHEMEKIT_ADDONS_URL . 'assets/frontend/' . $dir . '/isotope' . $suffix . '.js',
            array('jquery'),
            DETHEMEKIT_ADDONS_VERSION,
            true
        );

        wp_register_script(
            'modal-js',
            DETHEMEKIT_ADDONS_URL . 'assets/frontend/' . $dir . '/modal' . $suffix . '.js',
            array('jquery'),
            DETHEMEKIT_ADDONS_VERSION,
            true
        );
        
        wp_register_script(
            'dethemekit-maps-js',
            DETHEMEKIT_ADDONS_URL . 'assets/frontend/' . $dir . '/dethemekit-maps' . $suffix . '.js',
            array( 'jquery', 'dethemekit-maps-api-js' ),
            DETHEMEKIT_ADDONS_VERSION,
            true
        );

        wp_register_script( 
            'vscroll-js',
            DETHEMEKIT_ADDONS_URL . 'assets/frontend/' . $dir . '/dethemekit-vscroll' . $suffix . '.js',
            array('jquery'),
            DETHEMEKIT_ADDONS_VERSION,
            true
        );
        
       wp_register_script( 
           'slimscroll-js',
           DETHEMEKIT_ADDONS_URL . 'assets/frontend/' . $dir . '/jquery-slimscroll' . $suffix . '.js',
           array('jquery'),
           DETHEMEKIT_ADDONS_VERSION,
           true
       );
       
       wp_register_script( 
           'iscroll-js',
           DETHEMEKIT_ADDONS_URL . 'assets/frontend/' . $dir . '/iscroll' . $suffix . '.js',
           array('jquery'),
           DETHEMEKIT_ADDONS_VERSION,
           true
       );
       
       wp_register_script(
            'tilt-js',
            DETHEMEKIT_ADDONS_URL . 'assets/frontend/' . $dir . '/universal-tilt' . $suffix . '.js',
            array( 'jquery' ), 
            DETHEMEKIT_ADDONS_VERSION, 
            true
        );

        wp_register_script(
            'lottie-js',
            DETHEMEKIT_ADDONS_URL . 'assets/frontend/' . $dir . '/lottie' . $suffix . '.js',
            array( 'jquery' ), 
            DETHEMEKIT_ADDONS_VERSION, 
            true
        );

        wp_register_script(
            'dethemekit-decarousel-js',
            DETHEMEKIT_ADDONS_URL . 'assets/frontend/' . $dir . '/dethemekit-decarousel.js',
            array('jquery'),
            DETHEMEKIT_ADDONS_VERSION,
            true
        );
       
        if( !empty( $maps_settings['dethemekit-map-cluster'] ) ) {
            wp_register_script(
                'google-maps-cluster',
                'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js',
                array(),
                DETHEMEKIT_ADDONS_VERSION,
                false
            );
        }
        
        if ( !empty( $maps_settings['dethemekit-map-disable-api'] ) ){
            if( $maps_settings['dethemekit-map-disable-api'] && '1' != $maps_settings['dethemekit-map-api'] ) {
                $api = sprintf ( 'https://maps.googleapis.com/maps/api/js?key=%1$s&language=%2$s', $maps_settings['dethemekit-map-api'], $locale );
                wp_register_script(
                    'dethemekit-maps-api-js',
                    $api,
                    array(),
                    DETHEMEKIT_ADDONS_VERSION,
                    false
                );
            }
        }
        
        wp_register_script(
			'pa-slick',
			DETHEMEKIT_ADDONS_URL . 'assets/frontend/' . $dir . '/slick' . $suffix . '.js',
			array( 'jquery' ),
			DETHEMEKIT_ADDONS_VERSION,
			true
		);

		wp_localize_script(
			'dethemekit-addons-js',
			'DethemeKitSettings',
			array(
				'ajaxurl' => esc_url( admin_url( 'admin-ajax.php' ) ),
				'nonce'   => wp_create_nonce( 'pa-blog-widget-nonce' ),
			)
		);
        
    }
    
    /*
     * Enqueue editor scripts
     * 
     * @since 3.2.5
     * @access public
     */
    public function enqueue_editor_scripts() {
		
		$map_enabled = isset( self::$modules['dethemekit-maps'] ) ? self::$modules['dethemekit-maps'] : 1;
        
		if ( ($map_enabled) && !empty ($maps['dethemekit-map-api']) ) {
		
        	$dethemekit_maps_api = self::$maps['dethemekit-map-api'];
        
        	$locale = isset ( self::$maps['dethemekit-map-locale'] ) ? self::$maps['dethemekit-map-locale'] : "en";

        	$dethemekit_maps_disable_api = self::$maps['dethemekit-map-disable-api'];
        
        	if ( $dethemekit_maps_disable_api && '1' != $dethemekit_maps_api ) {
                
				$api = sprintf ( 'https://maps.googleapis.com/maps/api/js?key=%1$s&language=%2$s', $dethemekit_maps_api, $locale );
            	wp_enqueue_script(
                	'dethemekit-maps-api-js',
                	$api,
                	array(),
                	DETHEMEKIT_ADDONS_VERSION,
                	false
            	);

        	}

			wp_enqueue_script(
				'pa-maps-finder',
				DETHEMEKIT_ADDONS_URL . 'assets/editor/js/pa-maps-finder.js',
				array( 'jquery' ),
				DETHEMEKIT_ADDONS_VERSION,
				true
			);

        }

    }
    
    /*
     * Get Template Content
     * 
     * Get Elementor template HTML content.
     * 
     * @since 3.2.6
     * @access public
     * 
     */
    public function get_template_content() {
        
        $template = $_GET['templateID'];
        
        if( ! isset( $template ) ) {
            return;
        }
        
        $template_content = $this->templateInstance->get_template_content( $template );
        
        if ( empty ( $template_content ) || ! isset( $template_content ) ) {
            wp_send_json_error();
        }
        
        $data = array(
            'template_content'  => $template_content
        );
        
        wp_send_json_success( $data );
        
    }
    
    /**
     * 
     * Register addon by file name.
     * 
     * @access public
     *
     * @param  string $file            File name.
     * @param  object $widgets_manager Widgets manager instance.
     * 
     * @return void
     */
    public function register_addon( $file ) {
        // $dethemekit_option = get_option( 'dethemekit_option' );

        $widget_manager = \Elementor\Plugin::instance()->widgets_manager;
        
        $base  = basename( str_replace( '.php', '', $file ) );
        $class = ucwords( str_replace( '-', ' ', $base ) );
        $class = str_replace( ' ', '_', $class );
        $class = sprintf( 'DethemeKitAddons\Widgets\%s', $class );
        
        if( 'DethemeKitAddons\Widgets\DethemeKit_Contactform' != $class ) {
            require $file;
        } else {
            if( function_exists('wpcf7') ) {
                require $file;
            }
        }
        
        if ( 'DethemeKitAddons\Widgets\DethemeKit_Blog' == $class ) {
            require_once ( DETHEMEKIT_ADDONS_PATH . 'widgets/dep/queries.php' );
        }

        if ( class_exists( $class ) ) {
            // if ( ('DethemeKitAddons\Widgets\Dethemekit_Carousel' == $class) && (!isset($dethemekit_option['de_carousel'])) ) {
            //     return;
            // } else if ( ('DethemeKitAddons\Widgets\Dethemekit_Grid' == $class) && (!isset($dethemekit_option['de_gallery'])) ) {
            //     return;
            // } else {
            //     $widget_manager->register_widget_type( new $class );
            // }
            // if ( ('DethemeKitAddons\Widgets\Dethemekit_Carousel' == $class) && (!isset($dethemekit_option['de_carousel'])) ) {
            //     return;
            // } else if ( ('DethemeKitAddons\Widgets\Dethemekit_Grid' == $class) && (!isset($dethemekit_option['de_gallery'])) ) {
            //     return;
            // } else {
                $widget_manager->register_widget_type( new $class );
            // }
                
        }
    }
    
    /**
     * 
     * Creates and returns an instance of the class
     * 
     * @since 1.0.0
     * @access public
     * 
     * @return object
     * 
     */
   public static function get_instance() {
       if( self::$instance == null ) {
           self::$instance = new self;
       }
       return self::$instance;
   }
}
    

if ( ! function_exists( 'dethemekit_addons_integration' ) ) {

	/**
	 * Returns an instance of the plugin class.
	 * @since  1.0.0
	 * @return object
	 */
	function dethemekit_addons_integration() {
		return Addons_Integration::get_instance();
	}
}
dethemekit_addons_integration();