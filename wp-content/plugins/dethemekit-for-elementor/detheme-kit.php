<?php
/**
 * Plugin Name:         DethemeKit for Elementor
 * Plugin URI:          https://vastthemes.com
 * Description:         Detheme Widgets for elementor.
 * Version:             2.0.2
 * Author:              deTheme
 * Author URI:          https://detheme.com
 * Requires at least:   5.2
 * Tested up to:        5.9
 *
 * Text Domain: detheme-kit
 * Domain Path: /languages/
 
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Define Constants
define('DETHEMEKIT_ADDONS_VERSION', '2.0.2');
define('DETHEMEKIT_ADDONS_URL', plugins_url( '/', __FILE__ ) );
define('DETHEMEKIT_ADDONS_PATH', plugin_dir_path( __FILE__ ) );
define('DETHEMEKIT_ADDONS_FILE', __FILE__);
define('DETHEMEKIT_ADDONS_BASENAME', plugin_basename( DETHEMEKIT_ADDONS_FILE ) );
define('DETHEMEKIT_ADDONS_DIR_URL', plugin_dir_url( __FILE__ ));
define('DETHEMEKIT_ADDONS_STABLE_VERSION', '2.0.2');

define('DETHEMEKIT_ADDONS_DIR', __DIR__);
define('DETHEMEKIT_ADDONS_DIRNAME', dirname(DETHEMEKIT_ADDONS_BASENAME));

define('DETHEMEKIT_ADDONS_SINA_EXT_URL', plugins_url('/includes/ext/sina/', DETHEMEKIT_ADDONS_FILE));
define('DETHEMEKIT_ADDONS_SINA_EXT_INC', DETHEMEKIT_ADDONS_DIR .'/includes/ext/sina/inc/');

require DETHEMEKIT_ADDONS_SINA_EXT_INC . 'de-sina-ext-base.php';
require DETHEMEKIT_ADDONS_SINA_EXT_INC . 'de-sina-ext-func.php';
require DETHEMEKIT_ADDONS_SINA_EXT_INC . 'de-sina-ext.php';


include_once DETHEMEKIT_ADDONS_PATH . 'includes/de_loop/dtk-dependencies.php';
include_once DETHEMEKIT_ADDONS_PATH . 'includes/de_loop/enqueue-styles.php';
include_once DETHEMEKIT_ADDONS_PATH . 'includes/de_loop/ajax-pagination.php';


//check if Elementor is installed
if (dtk_dependencies()) {
	add_action( 'elementor_pro/init', 'dtk_elementor_init' );
	function dtk_elementor_init(){
		// if(!in_array('ele-custom-skin/ele-custom-skin.php', apply_filters('active_plugins', get_option('active_plugins')))){ 
			//plugin is not activated
			include_once DETHEMEKIT_ADDONS_PATH.'includes/de_loop/admin-bar-menu.php';
			// require_once DETHEMEKIT_ADDONS_PATH . 'theme-builder/init.php';
			require_once DETHEMEKIT_ADDONS_PATH . 'modules/loop-item/module.php';
		// }
	}

	add_action('elementor/widgets/widgets_registered','dtk_add_skins');
	function dtk_add_skins(){
	//   require_once DETHEMEKIT_ADDONS_PATH.'skins/skin-custom.php';
	  require_once DETHEMEKIT_ADDONS_PATH.'skins/skin-template-kit.php';
	}  

	// dynamic background fix
	require_once DETHEMEKIT_ADDONS_PATH.'includes/de_loop/dynamic-style.php';
}

/**
 * The Hooks
 *
 * @package DethemeKit
 */

if ( ! function_exists( 'detheme_kit_enqueue_scripts' ) ) :

	/**
	 * Enqueue plugin styles.
	 */
	function detheme_kit_enqueue_scripts() {
		// wp_register_style( 'dethemekit-panel', plugins_url( '/detheme-kit/widgets/init/assets/css/dethemekit-panel.css'), \Detheme_Kit::VERSION );
		// wp_enqueue_style( 'dethemekit-panel' );
		
		// We prefer to using legacy jquery to handle WordPress 5.6
		if (!is_admin()) {
			wp_deregister_script('jquery');
			wp_deregister_script('jquery-migrate');

			wp_register_script('jquery', plugins_url( '/assets/js/jquery-1.12.4-wp.js', __FILE__ ), false);
			wp_enqueue_script('jquery');
			
			wp_register_script('jquery-migrate', plugins_url( '/assets/js/jquery-migrate-1.4.1-wp.js', __FILE__ ), false);
			wp_enqueue_script('jquery-migrate');
		}

	}
	add_action( 'wp_enqueue_scripts', 'detheme_kit_enqueue_scripts' );
endif;
/**
 * Main Elementor Detheme Kit Class
 *
 * The init class that runs the Detheme Kit plugin.
 * Intended To make sure that the plugin's minimum requirements are met.
 *
 * You should only modify the constants to match your plugin's needs.
 *
 * Any custom code should go inside Plugin Class in the plugin.php file.
 * @since 1.2.0
 */
final class Detheme_Kit {

	/**
	 * Plugin Version
	 *
	 * @since 1.2.0
	 * @var string The plugin version.
	 */
	const VERSION = '1.0.0';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.2.0
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.2.0
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '7.0';

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		// Init Plugin
		add_action( 'plugins_loaded', array( $this, 'dethemekit_addons_elementor_setup' ) );
            
		add_action( 'elementor/init', array( $this, 'elementor_init' ) );
		
		add_action( 'init', array( $this, 'init' ), -999 );

		add_action( 'admin_post_dethemekit_addons_rollback', 'post_dethemekit_addons_rollback' );
		
		register_activation_hook( DETHEMEKIT_ADDONS_FILE, array( $this, 'set_transient' ) );

		add_action('plugins_loaded', function () {
			$default_dethemekit_options = array(
				'de_scroll_animation'   => '1',
				'de_reveal_animation'   => '1',
				'de_staggering'   => '1',
				'de_carousel'   => '1',
				'de_gallery'   => '1'
			);
	  
			$dethemekit_options = get_option( 'dethemekit_option' );
			if (!$dethemekit_options) {
				update_option( 'dethemekit_option', $default_dethemekit_options );
			}	  
		});

		add_action('plugins_loaded', function () {
			De_Sina_Extension::instance();
		});

		register_activation_hook( DETHEMEKIT_ADDONS_FILE, function() {
			flush_rewrite_rules();
		});		

		register_deactivation_hook( DETHEMEKIT_ADDONS_FILE, function() {
			flush_rewrite_rules();
		});
		
		add_action( "in_plugin_update_message-".DETHEMEKIT_ADDONS_BASENAME, array ( $this, 'detheme_action_in_plugin_update_message_plugin_name'), 10, 1 );
		// add_action( 'in_plugin_update_message-' . DETHEMEKIT_ADDONS_BASENAME, function( $plugin_data ) {$this->version_update_warning( DETHEMEKIT_ADDONS_VERSION, $plugin_data['new_version'] );} );

		add_action( 'wp_dashboard_setup', array( $this, 'dethemekit_add_dashboard_widgets' ),111 );

		add_filter( 'plugin_action_links_' . DETHEMEKIT_ADDONS_BASENAME , array( $this, 'dethemekit_add_action_links' ) );

	}
 
	function dethemekit_add_action_links ( $actions ) {
		$mylinks = array(
			'<a href="' . admin_url( 'options-general.php?page=dethemekit-setting-page' ) . '">Settings</a>',
		);
		$actions = array_merge( $actions, $mylinks );
		return $actions;
	}


	public function version_update_warning( $current_version, $new_version ) {
		$current_version_minor_part = explode( '.', $current_version )[2];
		$new_version_minor_part = explode( '.', $new_version )[2];

		if ( $current_version_minor_part === $new_version_minor_part ) {
			return;
		}
		?>
		<hr class="e-major-update-warning__separator" />
		<div class="e-major-update-warning">
			<div class="e-major-update-warning__icon">
				<i class="eicon-info-circle"></i>
			</div>
			<div>
				<div class="e-major-update-warning__title">
					<?php echo esc_html__( 'New Features are here, update Dethemekit now!', 'dethemekit' ); ?>
				</div>
				<div class="e-major-update-warning__message">
					<?php
						printf(
							/* translators: %1$s Link open tag, %2$s: Link close tag. */
							esc_html__( 'We have been working on some features that can help you make an attractive website. Also, we are happy to inform you that we have opened our %1$sShop%2$s where you can find the best Elementor Templates in town!', 'dethemekit' ),
							'<a target="_blank" href="http://detheme.com/">',
							'</a>'
						);
					?>
				</div>
			</div>
		</div>
		<?php
	}

	public function detheme_action_in_plugin_update_message_plugin_name( $create_function ) { 
		?>
		<hr class="e-major-update-warning__separator" />
		<div class="e-major-update-warning">
			<div class="e-major-update-warning__icon">
				<i class="eicon-info-circle"></i>
			</div>
			<div>
				<div class="e-major-update-warning__title">
					<?php echo esc_html__( 'New Features are here, update Dethemekit now!', 'dethemekit' ); ?>
				</div>
				<div class="e-major-update-warning__message">
					<?php
						printf(
							/* translators: %1$s Link open tag, %2$s: Link close tag. */
							esc_html__( 'We have been working on some features that can help you make an attractive website. Also, we are happy to inform you that we have opened our %1$sShop%2$s where you can find the best Elementor Templates in town!', 'dethemekit' ),
							'<a target="_blank" href="http://detheme.com/">',
							'</a>'
						);
					?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Add a new dashboard widget.
	 */
	public function dethemekit_add_dashboard_widgets() {
		wp_add_dashboard_widget( 'dethemekit_dashboard_widget', 'DethemeKit For Elementor', array ( $this, 'dethemekit_dashboard_widget_function' ) );
		
		// Move dethemekit widget to top.
		global $wp_meta_boxes;

		$dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
		$ours = [
			'dethemekit_dashboard_widget' => $dashboard['dethemekit_dashboard_widget'],
		];

		$wp_meta_boxes['dashboard']['normal']['core'] = array_merge( $ours, $dashboard );
		// wp_add_dashboard_widget( 'dethemekit-stories', __( 'DethemeKit Storiese', 'dethemekit' ), [ $this, 'show' ] );
	}

	public function nc_settings_link( $links ) {
		// Build and escape the URL.
		$url = esc_url( add_query_arg(
			'page',
			'nelio-content-settings',
			get_admin_url() . 'admin.php'
		) );
		// Create the link.
		$settings_link = "<a href='$url'>" . __( 'Settings' ) . '</a>';
		// Adds the link to the end of the array.
		array_push(
			$links,
			$settings_link
		);
		return $links;
	}
	
	
	/**
	 * Output the contents of the dashboard widget
	 */
	public function dethemekit_dashboard_widget_function( $post, $callback_args ) {
		?>
		<a href="https://detheme.com/dethemekit" target="_blank">
		<img class="detheme-dashboard" style="width:100%;" src="<?php echo esc_url('https://detheme.com/dethemekit-2.0.jpg'); ?>" /></a>
		<?php
	}

	/**
	 * Elementor Init
	 * 
	 * @since 2.6.8
	 * @access public
	 * 
	 * @return void
	 */
	public function elementor_init() {
		
		require_once ( DETHEMEKIT_ADDONS_PATH . 'includes/compatibility/class-dethemekit-addons-wpml.php' );
		
		require_once ( DETHEMEKIT_ADDONS_PATH . 'includes/class-addons-category.php' );
		
	}

	/**
	 * Load required file for addons integration
	 * 
	 * @since 1.1.0
	 * @access public
	 * 
	 * @return void
	 */
	public function init_addons() {
		require_once ( DETHEMEKIT_ADDONS_PATH . 'includes/class-addons-integration.php' );
	}

	/**
	 * Installs translation text domain and checks if Elementor is installed
	 * 
	 * @since 1.1.0
	 * @access public
	 * 
	 * @return void
	 */
	public function dethemekit_addons_elementor_setup() {
		
		$this->load_domain();
		
		$this->init_files(); 
	}

	/**
	 * Load plugin translated strings using text domain
	 * 
	 * @since 1.1.0
	 * @access public
	 * 
	 * @return void
	 */
	public function load_domain() {
		
		load_plugin_textdomain( 'dethemekit-addons-for-elementor', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		
	}

	/**
	 * Load required files
	 *
	 * @return void
	 */
	public function load_files() {
		require_once ( DETHEMEKIT_ADDONS_PATH . 'includes/ext/element-extension.php' );
		require_once ( DETHEMEKIT_ADDONS_PATH . 'includes/assets.php' );
		// de-product-display
		require_once ( DETHEMEKIT_ADDONS_PATH . 'includes/class.assest_management.php' );
		
		if( class_exists('WooCommerce') ){
			require_once ( DETHEMEKIT_ADDONS_PATH . 'modules/woocommerce/classes/base-products-renderer.php' );
			require_once ( DETHEMEKIT_ADDONS_PATH . 'modules/woocommerce/classes/products-renderer.php' );
		}
	}

	/**
	 * Require initial necessary files
	 * 
	 * @since 1.1.0
	 * @access public
	 * 
	 * @return void
	 */
	public function init_files() {
		
		require_once ( DETHEMEKIT_ADDONS_PATH . 'includes/class-helper-functions.php' );
		require_once ( DETHEMEKIT_ADDONS_PATH . 'admin/settings/maps.php' );
		require_once ( DETHEMEKIT_ADDONS_PATH . 'admin/settings/modules-setting.php' );
		require_once ( DETHEMEKIT_ADDONS_PATH . 'includes/elementor-helper.php' );

		// de-product-display
		require_once ( DETHEMEKIT_ADDONS_PATH . 'includes/helper-function.php' );

		require_once ( DETHEMEKIT_ADDONS_PATH . 'modules/controls/icons.php' );
		
		if ( is_admin() ) {
			
			require_once ( DETHEMEKIT_ADDONS_PATH . 'admin/includes/dep/maintenance.php');
			require_once ( DETHEMEKIT_ADDONS_PATH . 'admin/includes/dep/rollback.php');
			
			require_once ( DETHEMEKIT_ADDONS_PATH . 'admin/includes/dep/admin-helper.php');
			require_once ( DETHEMEKIT_ADDONS_PATH . 'includes/class-beta-testers.php');
			require_once ( DETHEMEKIT_ADDONS_PATH . 'admin/settings/dethemekit-settings.php' );
			require_once ( DETHEMEKIT_ADDONS_PATH . 'includes/plugin.php');
			require_once ( DETHEMEKIT_ADDONS_PATH . 'admin/includes/admin-notices.php' );
			require_once ( DETHEMEKIT_ADDONS_PATH . 'admin/includes/plugin-info.php');
			require_once ( DETHEMEKIT_ADDONS_PATH . 'admin/includes/version-control.php');
			require_once ( DETHEMEKIT_ADDONS_PATH . 'admin/includes/reports.php');
			require_once ( DETHEMEKIT_ADDONS_PATH . 'admin/includes/papro-actions.php');
			// $beta_testers = new DethemeKit_Beta_Testers();
			
		}
    
	}
	
	/**
	 * Set transient for admin review notice
	 * 
	 * @since 1.1.0
	 * @access public
	 * 
	 * @return void
	 */
	public function set_transient() {
		
		$cache_key = 'dethemekit_notice_' . DETHEMEKIT_ADDONS_VERSION;
		
		$expiration = 3600 * 72;
		
		set_transient( $cache_key, true, $expiration );
	}

	/**
	 * Initialize the plugin
	 *
	 * Validates that Elementor is already loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed include the plugin class.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function init() {

		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );
			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
			return;
		}

		$this->init_addons();

		$this->load_files();
		de_sticky_element_extension()->init();
		de_sticky_assets()->init();


		// if ( DethemeKitAddons\Admin\Settings\Modules_Settings::check_dethemekit_templates() )
		// 		$this->init_templates();
				
		// Once we get here, We have passed all validation checks so we can safely include core plugin
		require_once( 'core.php' );
	}

	/**
	 * Load the required files for templates integration
	 * 
	 * @since 1.1.0
	 * @access public
	 * 
	 * @return void
	 */
	public function init_templates() {
		//Load templates file
		// require_once ( DETHEMEKIT_ADDONS_PATH . 'includes/templates/templates.php');
		// require_once ( DETHEMEKIT_ADDONS_PATH . 'includes/admin/include/template-library.php');
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'detheme-kit' ),
			'<strong>' . esc_html__( 'Elementor Hello World', 'detheme-kit' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'detheme-kit' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'detheme-kit' ),
			'<strong>' . esc_html__( 'Elementor Hello World', 'detheme-kit' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'detheme-kit' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Plugin file
	 *
	 * @since 1.0.0
	 * @var string plugins's root file.
	 */
	static function plugin_file(){
		return __FILE__;
	}

	/**
	 * Plugin url
	 *
	 * @since 1.0.0
	 * @var string plugins's root url.
	 */
	static function plugin_url(){
		return trailingslashit(plugin_dir_url( __FILE__ ));
	}

	/**
	 * Plugin dir
	 *
	 * @since 1.0.0
	 * @var string plugins's root directory.
	 */
	static function plugin_dir(){
		return trailingslashit(plugin_dir_path( __FILE__ ));
	}

	/**
	 * Plugin's module directory.
	 * 
	 * @since 1.0.0
	 * @var string module's root directory.
	 */
	static function module_dir(){
		return self::plugin_dir() . 'modules/';
	}

	/**
	 * Plugin's module url.
	 * 
	 * @since 1.0.0
	 * @var string module's root url.
	 */
	static function module_url(){
		return self::plugin_url() . 'modules/';
	}

    /**
     * Plugin's widget directory.
     *
     * @since 1.0.0
     * @var string widget's root directory.
     */
	static function widget_dir(){
		return self::plugin_dir() . 'widgets/';
	}

	// instance of all control's base class
	static function get_url(){
		return self::module_url() . 'controls/';
	}

	static function get_dir(){
		return self::module_dir() . 'controls/';
	}
	
    
	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'detheme-kit' ),
			'<strong>' . esc_html__( 'Elementor Hello World', 'detheme-kit' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'detheme-kit' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}
}

// Instantiate Detheme_Kit.
new Detheme_Kit();
