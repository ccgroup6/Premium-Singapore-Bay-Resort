<?php

namespace DethemeKit;
/**
 * Class Core
 *
 * Main Core class
 * @since 1.2.0
 */
class Core {

	/**
	 * Instance
	 *
	 * @since 1.2.0
	 * @access private
	 * @static
	 *
	 * @var Core The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return Core An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	// instance of all control's base class
	public static function get_url(){
		return \Detheme_Kit::module_url() . 'controls/';
	}

	public static function get_dir(){
		return \Detheme_Kit::module_dir() . 'controls/';
	}

	/**
	 * widget_scripts
	 *
	 * Load required Core core files.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function widget_scripts() {
		wp_register_script( 'detheme-kit', plugins_url( '/assets/js/dethemekit.js', __FILE__ ), [ 'jquery' ], false, true );

	}
	/**
     * Enqueue scripts
     *
     * Enqueue js and css to admin.
     *
     * @since 1.0.0
     * @access public
     */
    public function enqueue_admin(){
        
        // wp_register_style( 'dethemekit-css-admin', plugins_url( 'widgets/init/assets/css/dethemekit.css'), \Detheme_Kit::VERSION );
        // wp_enqueue_style( 'dethemekit-css-admin' );

    }

	/**
	 * Include Widgets files
	 *
	 * Load widgets files
	 *
	 * @since 1.2.0
	 * @access private
	 */
	private function include_widgets_files() {
		require_once( __DIR__ . '/widgets/de-breadcrumb.php' );
		require_once( __DIR__ . '/widgets/de-post-title.php' );
		require_once( __DIR__ . '/widgets/de-copyright.php' );
		require_once( __DIR__ . '/widgets/de-product-display.php' );
		require_once( __DIR__ . '/widgets/de-product-tab-slide.php' );
		require_once( __DIR__ . '/widgets/de-post-excerpt.php' );
		require_once( __DIR__ . '/widgets/de-post-date.php' );
		require_once( __DIR__ . '/widgets/de-post-author.php' );
		require_once( __DIR__ . '/widgets/de-post-terms.php' );
		require_once( __DIR__ . '/widgets/de-post-featured-image.php' );
		require_once( __DIR__ . '/widgets/de-post-comments.php' );
		// require_once( __DIR__ . '/widgets/de-instagram.php' );
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function register_widgets() {
		// Its is now safe to include Widgets files
		$this->include_widgets_files();

		// Register Widgets
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\De_Breadcrumb() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\De_Post_Title() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\De_Copyright() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\De_Post_Excerpt() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\De_Post_Date() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\De_Post_Author() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\De_Post_Terms() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\De_Post_Featured_Image() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\De_Post_Comments() );
		// \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\De_Instagram() );

		if( class_exists('WooCommerce') ){
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\De_Product_Display() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\De_Product_Tabs_Widget() );
		}
	}

	/**
	 *  Core class constructor
	 *
	 * Register Core action hooks and filters
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function __construct() {

		// Includes necessary files
		$this->include_files();

		// load icons
        Modules\Controls\Icons::_get_instance()->dekit_icons_pack();

		// Register widget scripts
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_scripts' ] );

		// Register widgets
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );

		// Enqueue admin scripts.
        add_action( 'admin_enqueue_scripts', [$this, 'enqueue_admin'] );
	}

	private function include_files(){

		// Controls_Manager
        include_once self::get_dir() . 'de-global-style.php';

        // Controls_Manager
        include_once self::get_dir() . 'control-manager.php';

         // icons
		include_once self::get_dir() . 'icons.php';
		// include_once self::module_url() . 'controls/icons.php';

		require_once ( DETHEMEKIT_ADDONS_PATH . 'includes/class-column-clickable.php' );
	}
	
	public function icon( $controls_manager ) {
        $controls_manager->unregister_control( $controls_manager::ICON );
        $controls_manager->register_control( $controls_manager::ICON, new \DethemeKit\Modules\Controls\Icon());
	}

}

// Instantiate Core Class
Core::instance();
