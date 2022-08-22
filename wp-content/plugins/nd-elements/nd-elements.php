<?php
/*
Plugin Name:       Elements For Elementor
Description:       The plugin adds some useful Elementor components that can be integrated very easily on your own theme.
Version:           2.0
Plugin URI:        https://nicdark.com
Author:            Nicdark
Author URI:        https://nicdark.com
License:           GPLv2 or later
*/


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}


final class Nd_Elements_Elementor_Extension {


  const VERSION = '1.0.0';
  const MINIMUM_ELEMENTOR_VERSION = '2.0.0';
  const MINIMUM_PHP_VERSION = '7.0';
  private static $_instance = null;


  public static function instance() {

    if ( is_null( self::$_instance ) ) {
      self::$_instance = new self();
    }
    return self::$_instance;

  }





  public function __construct() {
    add_action( 'init', [ $this, 'i18n' ] );
    add_action( 'plugins_loaded', [ $this, 'init' ] );
  }

  public function i18n() { load_plugin_textdomain( 'nd-elements' );  }







  public function init() {

    // Check if Elementor installed and activated
    if ( ! did_action( 'elementor/loaded' ) ) {
      add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
      return;
    }

    // Check for required Elementor version
    if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
      add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
      return;
    }

    // Check for required PHP version
    if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
      add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
      return;
    }

    // Add Plugin actions
    add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );

  }







  public function admin_notice_missing_main_plugin() {

    if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

    $message = sprintf(
      /* translators: 1: Plugin name 2: Elementor */
      esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'nd-elements' ),
      '<strong>' . esc_html__( 'Elementor ND Elements Extension', 'nd-elements' ) . '</strong>',
      '<strong>' . esc_html__( 'Elementor', 'nd-elements' ) . '</strong>'
    );

    printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

  }




  public function admin_notice_minimum_elementor_version() {

    if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

    $message = sprintf(
      /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
      esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'nd-elements' ),
      '<strong>' . esc_html__( 'Elementor ND Elements Extension', 'nd-elements' ) . '</strong>',
      '<strong>' . esc_html__( 'Elementor', 'nd-elements' ) . '</strong>',
       self::MINIMUM_ELEMENTOR_VERSION
    );

    printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

  }




  public function admin_notice_minimum_php_version() {

    if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

    $message = sprintf(
      /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
      esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'nd-elements' ),
      '<strong>' . esc_html__( 'Elementor ND Elements Extension', 'nd-elements' ) . '</strong>',
      '<strong>' . esc_html__( 'PHP', 'nd-elements' ) . '</strong>',
       self::MINIMUM_PHP_VERSION
    );

    printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

  }

  

  /*INCLUDED ALL WIDGETS*/
  public function init_widgets() {
    
    //oembed
    require_once( __DIR__ . '/widgets/cf7/index.php' );
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \nd_elements_cf7_element() );

    //navigation
    require_once( __DIR__ . '/widgets/navigation/index.php' );
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \nd_elements_navigation_element() );

    //postgrid
    require_once( __DIR__ . '/widgets/postgrid/index.php' );
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \nd_elements_postgrid_element() );

    //events grid
    require_once( __DIR__ . '/widgets/eventsgrid/index.php' );
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \nd_elements_eventsgrid_element() );

    //customcss
    require_once( __DIR__ . '/widgets/customcss/index.php' );
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \nd_elements_customcss_element() );

    //woogrid
    require_once( __DIR__ . '/widgets/woogrid/index.php' );
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \nd_elements_woogrid_element() );

    //woocart
    require_once( __DIR__ . '/widgets/woocart/index.php' );
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \nd_elements_woocart_element() );

    //marquee
    require_once( __DIR__ . '/widgets/marquee/index.php' );
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \nd_elements_marquee_element() );

    //beforeafter
    require_once( __DIR__ . '/widgets/beforeafter/index.php' );
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \nd_elements_beforeafter_element() );

    //list
    require_once( __DIR__ . '/widgets/list/index.php' );
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \nd_elements_list_element() );

  }



}

Nd_Elements_Elementor_Extension::instance();




//START add custom css and js
function nd_elements_scripts() {
  
  //basic css plugin
  wp_enqueue_style( 'nd_elements_style', esc_url( plugins_url( 'css/style.css', __FILE__ ) ) );
  
}
add_action( 'wp_enqueue_scripts', 'nd_elements_scripts' );
//END add custom css and js




function add_elementor_widget_categories( $elements_manager ) {

  $elements_manager->add_category(
    'nd-elements',
    [
      'title' => __( 'ND Elements', 'nd-elements' ),
      'icon' => 'fa fa-plug',
    ]
  );

}
add_action( 'elementor/elements/categories_registered', 'add_elementor_widget_categories' );




//START edit my templates feature
add_action('elementor/editor/footer', function() { ?>
 
<style type="text/css" media="screen">

  #elementor-template-library-order-toolbar-local { display: none;  }
  .elementor-template-library-template-local { float: left;width: 30%;display: inline;height: auto;margin: 15px;padding: 8px 8px 0px 8px; box-sizing: border-box; }

  .nd_elements_myt_preview_img {  float:left; width: 100%; }
  .nd_elements_myt_preview_title { display: none; }

  .nd_elements_myt_preview_btn_container { float: left; width: 100%; padding: 8px 4px; }
  .nd_elements_myt_preview_btn_preview { float: left; width: 50%; display: inline; text-transform: uppercase; }
  .nd_elements_myt_preview_btn_insert_content { float: right; text-align: right; width: 50%;  }
  .nd_elements_myt_preview_btn_insert {  margin: 0px !important;padding: 2px 10px !important;display: inline-block;width: initial;text-transform: uppercase !important; background-color: #39b54a !important; color: #fff !important; }

  #elementor-template-library-templates-container { box-shadow: none !important; }

</style>



<script type="text/template" id="tmpl-elementor-template-library-template-local">


  <# if( thumbnail ) { #>
    <img class="nd_elements_myt_preview_img" src="{{{ thumbnail }}}" alt="">
  <# } #>

  <div class="nd_elements_myt_preview_title elementor-template-library-template-name elementor-template-library-local-column-1">
    {{{ title }}}
  </div>

  <div class="nd_elements_myt_preview_btn_container elementor-template-library-template-controls elementor-template-library-local-column-5">
    
    <div class="nd_elements_myt_preview_btn_preview elementor-template-library-template-preview">
      <?php echo esc_html__( 'Preview', 'nd-elements' ); ?>
    </div>

    <div class="nd_elements_myt_preview_btn_insert_content">

      <button class="nd_elements_myt_preview_btn_insert elementor-template-library-template-action elementor-template-library-template-insert elementor-button elementor-button-success">
        <?php echo esc_html__( 'Insert', 'nd-elements' ); ?>
      </button>

    </div>

  </div>


</script>


<?php }); 
//END edit my templates feature

