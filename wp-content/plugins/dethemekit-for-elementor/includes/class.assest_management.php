<?php

namespace DethemeKit;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
* Assest Management
*/
class Assets_Management{
    
    /**
     * [$instance]
     * @var null
     */
    private static $instance = null;

    /**
     * [instance] Initializes a singleton instance
     * @return [Assets_Management]
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * [__construct] Class Constructor
     */
    function __construct(){
        $this->init();
    }

    /**
     * [init] Init
     * @return [void]
     */
    public function init() {

        // Register Scripts
        add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'register_assets' ] );

        // Frontend Scripts
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend_scripts' ] );

    }

    /**
     * All available styles
     *
     * @return array
     */
    public function get_styles() {

        $style_list = [
            'htflexboxgrid' => [
                'src'     => DETHEMEKIT_ADDONS_URL . 'assets/css/htflexboxgrid.css',
                'version' => DETHEMEKIT_ADDONS_VERSION
            ],
            'simple-line-icons-wl' => [
                'src'     => DETHEMEKIT_ADDONS_URL . 'assets/css/simple-line-icons.css',
                'version' => DETHEMEKIT_ADDONS_VERSION
            ],
            'font-awesome-four' => [
                'src'     => DETHEMEKIT_ADDONS_URL . 'assets/css/font-awesome.min.css',
                'version' => DETHEMEKIT_ADDONS_VERSION
            ],
            'dethemekit-widgets' => [
                'src'     => DETHEMEKIT_ADDONS_URL . 'assets/css/dethemekit-widgets.css',
                'version' => DETHEMEKIT_ADDONS_VERSION
            ],
            
            'slick' => [
                'src'     => DETHEMEKIT_ADDONS_URL . 'assets/css/slick.css',
                'version' => DETHEMEKIT_ADDONS_VERSION
            ],
            'dethemekit-widgets-rtl' => [
                'src'     => DETHEMEKIT_ADDONS_URL . 'assets/css/dethemekit-widgets-rtl.css',
                'version' => DETHEMEKIT_ADDONS_VERSION
            ],
            'dethemekit-ajax-search' => [
                'src'     => DETHEMEKIT_ADDONS_URL . 'assets/addons/ajax-search/css/ajax-search.css',
                'version' => DETHEMEKIT_ADDONS_VERSION
            ],

            'dethemekit-admin' => [
                'src'     => DETHEMEKIT_ADDONS_URL . 'includes/admin/assets/css/admin_optionspanel.css',
                'version' => DETHEMEKIT_ADDONS_VERSION
            ],
            'dethemekit-selectric' => [
                'src'     => DETHEMEKIT_ADDONS_URL . 'includes/admin/assets/lib/css/selectric.css',
                'version' => DETHEMEKIT_ADDONS_VERSION
            ],
            'dethemekit-de-carousel' => [
                'src'     => DETHEMEKIT_ADDONS_URL . 'assets/css/dethemekit-de-carousel.css',
                'version' => DETHEMEKIT_ADDONS_VERSION
            ],
            'dethemekit-temlibray-style' => [
                'src'     => DETHEMEKIT_ADDONS_URL . 'includes/admin/assets/css/tmp-style.css',
                'version' => DETHEMEKIT_ADDONS_VERSION
            ],
            
            
        ];
        
        return $style_list;

    }

    /**
     * All available scripts
     *
     * @return array
     */
    public function get_scripts() {

        $script_list = [
            'slick' => [
                'src'     => DETHEMEKIT_ADDONS_URL . 'assets/js/slick.min.js',
                'version' => DETHEMEKIT_ADDONS_VERSION,
                'deps'    => [ 'jquery' ]
            ],
            'countdown-min' => [
                'src'     => DETHEMEKIT_ADDONS_URL . 'assets/js/jquery.countdown.min.js',
                'version' => DETHEMEKIT_ADDONS_VERSION,
                'deps'    => [ 'jquery' ]
            ],
            'dethemekit-widgets-scripts' => [
                'src'     => DETHEMEKIT_ADDONS_URL . 'assets/js/dethemekit-widgets-active.js',
                'version' => DETHEMEKIT_ADDONS_VERSION,
                'deps'    => [ 'jquery','slick' ]
            ],
            'jquery-nicescroll' => [
                'src'     => DETHEMEKIT_ADDONS_URL . 'assets/addons/ajax-search/js/jquery.nicescroll.min.js',
                'version' => DETHEMEKIT_ADDONS_VERSION,
                'deps'    => [ 'jquery' ]
            ],
            'dethemekit-ajax-search' => [
                'src'     => DETHEMEKIT_ADDONS_URL . 'assets/addons/ajax-search/js/ajax-search.js',
                'version' => DETHEMEKIT_ADDONS_VERSION,
                'deps'    => [ 'dethemekit-widgets-scripts' ]
            ],
            'jquery-single-product-ajax-cart' =>[
                'src'     => DETHEMEKIT_ADDONS_URL . 'assets/js/single_product_ajax_add_to_cart.js',
                'version' => DETHEMEKIT_ADDONS_VERSION,
                'deps'    => [ 'jquery' ]
            ],

            'dethemekit-admin-main' =>[
                'src'     => DETHEMEKIT_ADDONS_URL . 'includes/admin/assets/js/dethemekit-admin.js',
                'version' => DETHEMEKIT_ADDONS_VERSION,
                'deps'    => [ 'jquery' ]
            ],

            'dethemekit-modernizr' => [
                'src'     => DETHEMEKIT_ADDONS_URL . 'includes/admin/assets/lib/js/modernizr.custom.63321.js',
                'version' => DETHEMEKIT_ADDONS_VERSION,
                'deps'    => [ 'jquery' ]
            ],
            'jquery-selectric' => [
                'src'     => DETHEMEKIT_ADDONS_URL . 'includes/admin/assets/lib/js/jquery.selectric.min.js',
                'version' => DETHEMEKIT_ADDONS_VERSION,
                'deps'    => [ 'jquery' ]
            ],
            'jquery-ScrollMagic' => [
                'src'     => DETHEMEKIT_ADDONS_URL . 'includes/admin/assets/lib/js/ScrollMagic.min.js',
                'version' => DETHEMEKIT_ADDONS_VERSION,
                'deps'    => [ 'jquery' ]
            ],
            'babel-min' => [
                'src'     => DETHEMEKIT_ADDONS_URL . 'includes/admin/assets/lib/js/babel.min.js',
                'version' => DETHEMEKIT_ADDONS_VERSION,
                'deps'    => [ 'jquery' ]
            ],
            'dethemekit-templates' => [
                'src'     => DETHEMEKIT_ADDONS_URL . 'includes/admin/assets/js/template_library_manager.js',
                'version' => DETHEMEKIT_ADDONS_VERSION,
                'deps'    => [ 'jquery' ]
            ],
            'dethemekit-install-manager' => [
                'src'     => DETHEMEKIT_ADDONS_URL . 'includes/admin/assets/js/install_manager.js',
                'version' => DETHEMEKIT_ADDONS_VERSION,
                'deps'    => [ 'dethemekit-templates', 'wp-util', 'updates' ]
            ],
            
        ];

        return $script_list;

    }

    /**
     * Register scripts and styles
     *
     * @return void
     */
    public function register_assets() {
        $scripts = $this->get_scripts();
        $styles  = $this->get_styles();

        // Register Scripts
        foreach ( $scripts as $handle => $script ) {
            $deps = ( isset( $script['deps'] ) ? $script['deps'] : false );
            wp_register_script( $handle, $script['src'], $deps, $script['version'], true );
        }

        // Register Styles
        foreach ( $styles as $handle => $style ) {
            $deps = ( isset( $style['deps'] ) ? $style['deps'] : false );
            wp_register_style( $handle, $style['src'], $deps, $style['version'] );
        }

        //Localize Scripts
        $localizeargs = array(
            'dethemekitajaxurl' => admin_url( 'admin-ajax.php' ),
            'ajax_nonce'       => wp_create_nonce( 'dethemekit_psa_nonce' ),
        );
        wp_localize_script( 'dethemekit-widgets-scripts', 'dethemekit_addons', $localizeargs );

        // For Admin
        // if( is_admin() ){

        //     $datalocalize = array(
        //         'contenttype' => dethemekit_get_option( 'notification_content_type','dethemekit_sales_notification_tabs', 'actual' ),
        //     );
        //     wp_localize_script( 'dethemekit-admin-main', 'admin_wllocalize_data', $datalocalize );

        //     //Localize Scripts For template Library
        //     $current_user  = wp_get_current_user();
        //     $localize_data = [
        //         'ajaxurl'          => admin_url( 'admin-ajax.php' ),
        //         'adminURL'         => admin_url(),
        //         'elementorURL'     => admin_url( 'edit.php?post_type=elementor_library' ),
        //         'version'          => DETHEMEKIT_ADDONS_VERSION,
        //         'pluginURL'        => plugin_dir_url( __FILE__ ),
        //         'alldata'          => ( !empty( \DethemeKit_Template_Library::instance()->get_templates_info()['templates'] ) ? \DethemeKit_Template_Library::instance()->get_templates_info()['templates']:array() ),
        //         'prolink'          => 'https://hasthemes.com/plugins/dethemekit-pro-woocommerce-page-builder/?fd',
        //         'prolabel'         => esc_html__( 'Pro', 'dethemekit' ),
        //         'loadingimg'       => DETHEMEKIT_ADDONS_URL . 'includes/admin/assets/images/loading.gif',
        //         'message'          =>[
        //             'packagedesc'=> esc_html__( 'in this package', 'dethemekit' ),
        //             'allload'    => esc_html__( 'All Items have been Loaded', 'dethemekit' ),
        //             'notfound'   => esc_html__( 'Nothing Found', 'dethemekit' ),
        //         ],
        //         'buttontxt'      =>[
        //             'tmplibrary' => esc_html__( 'Import to Library', 'dethemekit' ),
        //             'tmppage'    => esc_html__( 'Import to Page', 'dethemekit' ),
        //             'import'     => esc_html__( 'Import', 'dethemekit' ),
        //             'buynow'     => esc_html__( 'Buy Now', 'dethemekit' ),
        //             'preview'    => esc_html__( 'Preview', 'dethemekit' ),
        //             'installing' => esc_html__( 'Installing..', 'dethemekit' ),
        //             'activating' => esc_html__( 'Activating..', 'dethemekit' ),
        //             'active'     => esc_html__( 'Active', 'dethemekit' ),
        //         ],
        //         'user'           => [
        //             'email' => $current_user->user_email,
        //         ],
        //     ];
        //     wp_localize_script( 'dethemekit-templates', 'WLTM', $localize_data );
        // }
        
    }

    /**
     * [enqueue_frontend_scripts Load frontend scripts]
     * @return [void]
     */
    public function enqueue_frontend_scripts() {

        $current_theme = wp_get_theme( 'oceanwp' );
        // CSS File
        if ( $current_theme->exists() ){
            wp_enqueue_style( 'font-awesome-four' );
        }else{
            wp_enqueue_style( 'font-awesome' );
        }
        wp_enqueue_style( 'simple-line-icons-wl' );
        wp_enqueue_style( 'htflexboxgrid' );
        wp_enqueue_style( 'slick' );
        wp_enqueue_style( 'dethemekit-widgets' );
        wp_enqueue_style( 'dethemekit-de-carousel' );
        // If RTL
        if ( is_rtl() ) {
            wp_enqueue_style(  'dethemekit-widgets-rtl' );
        }

    }



}

Assets_Management::instance();