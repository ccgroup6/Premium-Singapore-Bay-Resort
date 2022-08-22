<?php

/**
 * PA Category Manager.
 */
namespace DethemeKitAddons\Includes;

use DethemeKitAddons\Helper_Functions;

if( ! defined( 'ABSPATH' ) ) exit();

/**
 * Class DethemeKit_Addons_Category.
 */
class Addons_Category {
    
    /**
	 * Class object
	 *
	 * @var instance
	 */
    private static $instance = null;
    
    public function __construct() {
        $this->create_dethemekit_category();
    }
    
    /*
     * Create DethemeKit Addons Category
     * 
     * Adds category `DethemeKit Addons` in the editor panel.
     * 
     * @access public
     * 
     */
    public function create_dethemekit_category() {
        \Elementor\Plugin::instance()->elements_manager->add_category(
            'dethemekit-elements',
            array(
                'title' => Helper_Functions::get_category()
            ),
        1);
    }

    /**
     * Creates and returns an instance of the class
     * 
     * @since  2.6.8
     * @access public
     * 
     * @return object
     */
   public static function get_instance() {
       if( self::$instance == null ) {
           self::$instance = new self;
       }
       return self::$instance;
   }
}
    

if ( ! function_exists( 'dethemekit_addons_category' ) ) {

	/**
	 * Returns an instance of the plugin class.
	 * @since  2.6.8
	 * @return object
	 */
	function dethemekit_addons_category() {
		return Addons_Category::get_instance();
	}
}
dethemekit_addons_category();