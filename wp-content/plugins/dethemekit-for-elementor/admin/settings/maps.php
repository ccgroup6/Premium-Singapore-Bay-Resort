<?php

namespace DethemeKitAddons\Admin\Settings;

use DethemeKitAddons\Helper_Functions;

if( ! defined( 'ABSPATH' ) ) exit;

class Maps {
    
    public static $pa_maps_keys = [ 'dethemekit-map-api', 'dethemekit-map-disable-api', 'dethemekit-map-cluster', 'dethemekit-map-locale' ];
    
    private $pa_maps_default_settings;
    
    private $pa_maps_settings;
    
    private $pa_maps_get_settings;
    
    public function __construct() {
        
        add_action( 'admin_menu', array ( $this,'create_maps_menu' ), 100 );
        
        add_action( 'wp_ajax_pa_maps_save_settings', array( $this, 'pa_save_maps_settings' ) );
        
    }
    
    public function create_maps_menu() {
        
        add_submenu_page(
            'dethemekit-addons',
            '',
            __('Google Maps', 'dethemekit-addons-for-elementor'),
            'manage_options',
            'dethemekit-addons-maps',
            [ $this, 'pa_maps_page' ]
        );
        
    }
    
    public function pa_maps_page() {
        
        $js_info = array(
            'ajaxurl'   => admin_url( 'admin-ajax.php' ),
            'nonce' 	=> wp_create_nonce( 'pa-maps' ),
		);
        
        wp_localize_script( 'pa-admin-js', 'settings', $js_info );
        
        $this->pa_maps_default_settings = $this->get_default_keys();
       
        $this->pa_maps_get_settings = $this->get_enabled_keys();
       
        $pa_maps_new_settings = array_diff_key( $this->pa_maps_default_settings, $this->pa_maps_get_settings );
        
        if( ! empty( $pa_maps_new_settings ) ) {
            $pa_maps_updated_settings = array_merge( $this->pa_maps_get_settings, $pa_maps_new_settings );
            update_option( 'pa_maps_save_settings', $pa_maps_updated_settings );
        }
        
        $this->pa_maps_get_settings = get_option( 'pa_maps_save_settings', $this->pa_maps_default_settings );
        
        $settings = $this->pa_maps_get_settings;
        
        $locales = Helper_Functions::get_google_maps_prefixes();
        
        ?>
        <div class="wrap">
           <div class="response-wrap"></div>
           <form action="" method="POST" id="pa-maps" name="pa-maps">
           <div class="pa-header-wrapper">
              <div class="pa-title-left">
                  <h1 class="pa-title-main"><?php echo Helper_Functions::name(); ?></h1>
                 <h3 class="pa-title-sub"><?php echo sprintf(__('Thank you for using %s. This plugin has been developed by %s and we hope you enjoy using it.','dethemekit-addons-for-elementor'), Helper_Functions::name(), Helper_Functions::author()); ?></h3>
              </div>
              <?php if( ! Helper_Functions::is_hide_logo()) : ?>
                    <div class="pa-title-right">
                        <img class="pa-logo" src="<?php echo DETHEMEKIT_ADDONS_URL . 'admin/images/dethemekit-addons-logo.png';?>">
                    </div>
                <?php endif; ?>
           </div>
           <div class="pa-settings-tabs">
              <div id="pa-maps-api" class="pa-maps-tab">
                 <div class="pa-row">
                    <table class="pa-maps-table">
                       <tr>
                          <p class="pa-maps-api-notice">
                             <?php echo esc_html( Helper_Functions::get_prefix() ) . __(' Maps Element requires Google API key to be entered below. If you don’t have one, click ', 'dethemekit-addons-for-elementor'); ?><a href="https://dethemekitaddons.com/docs/getting-your-api-key-for-google-reviews/" target="_blank"><?php echo __('here', 'dethemekit-addons-for-elementor'); ?></a><?php echo __(' to get your  key.', 'dethemekit-addons-for-elementor'); ?>
                          </p>
                       </tr>
                       <tr>
                          <td>
                             <h4 class="pa-api-title"><?php echo __('Google Maps API Key:', 'dethemekit-addons-for-elementor'); ?></h4>
                          </td>
                          <td>
                              <input name="dethemekit-map-api" id="dethemekit-map-api" type="text" placeholder="API Key" value="<?php echo esc_attr( $settings['dethemekit-map-api'] ); ?>">
                          </td>
                       </tr>
                       <tr>
                          <td>
                             <h4 class="pa-api-disable-title"><?php echo __('Google Maps Localization Language:', 'dethemekit-addons-for-elementor'); ?></h4>
                          </td>
                          <td>
                              <select name="dethemekit-map-locale" id="dethemekit-map-locale" class="placeholder placeholder-active">
                                    <option value=""><?php _e( 'Default', 'dethemekit-addons-for-elementor' ); ?></option>
                                <?php foreach ( $locales as $key => $value ) { ?>
                                    <?php
                                    $selected = '';
                                    if ( $key === $settings['dethemekit-map-locale'] ) {
                                        $selected = 'selected="selected" ';
                                    }
                                    ?>
                                    <option value="<?php echo esc_attr( $key ); ?>" <?php echo $selected; ?>><?php echo esc_attr( $value ); ?></option>
                                    <?php } ?>
                                </select>
                          </td>
                       </tr>
                       <tr>
                          <td>
                             <h4 class="pa-api-disable-title"><?php echo __('Load Maps API JS File:','dethemekit-addons-for-elementor'); ?></h4>
                          </td>
                          <td>
                              <input name="dethemekit-map-disable-api" id="dethemekit-map-disable-api" type="checkbox" <?php checked(1, $settings['dethemekit-map-disable-api'], true) ?>><span><?php echo __('This will load API JS file if it\'s not loaded by another theme or plugin', 'dethemekit-addons-for-elementor'); ?></span>
                          </td>
                       </tr>
                       <tr>
                          <td>
                             <h4 class="pa-api-disable-title"><?php echo __('Load Markers Clustering JS File:','dethemekit-addons-for-elementor'); ?></h4>
                          </td>
                          <td>
                              <input name="dethemekit-map-cluster" id="dethemekit-map-cluster" type="checkbox" <?php checked(1, $settings['dethemekit-map-cluster'], true) ?>><span><?php echo __('This will load the JS file for markers clusters', 'dethemekit-addons-for-elementor'); ?></span>
                          </td>
                       </tr>
                    </table>
                    <input type="submit" value="<?php echo __('Save Settings', 'dethemekit-addons-for-elementor'); ?>" class="button pa-btn pa-save-button">
                    <?php if( ! Helper_Functions::is_hide_rate() ) : ?>
                        <div>
                                <p><?php echo __('Did you like DethemeKit Addons for Elementor Plugin? Please ', 'dethemekit-addons-for-elementor'); ?><a href="https://wordpress.org/support/plugin/dethemekit-addons-for-elementor/reviews/#new-post" target="_blank"><?php echo __('Click Here to Rate it ★★★★★', 'dethemekit-addons-for-elementor'); ?></a></p>
                        </div>
                    <?php endif; ?>
                 </div>
              </div>
           </div>
           </form>
        </div>
    <?php }
    
    public static function get_default_keys() {
        
        $default_keys = array_fill_keys( self::$pa_maps_keys, true );
        
        return $default_keys;
    }
    
    public static function get_enabled_keys() {
        
        $enabled_keys = get_option( 'pa_maps_save_settings', self::get_default_keys() );
        
        return $enabled_keys;
    }
    
    public function pa_save_maps_settings() {
        
        check_ajax_referer('pa-maps', 'security');

        if( isset( $_POST['fields'] ) ) {
            parse_str( $_POST['fields'], $settings );
        } else {
            return;
        }
        
        $this->pa_maps_settings = array(
            'dethemekit-map-api'           => sanitize_text_field( $settings['dethemekit-map-api'] ),
            'dethemekit-map-disable-api'   => intval( $settings['dethemekit-map-disable-api'] ? 1 : 0 ),
            'dethemekit-map-cluster'       => intval( $settings['dethemekit-map-cluster'] ? 1 : 0 ),
            'dethemekit-map-locale'        => sanitize_text_field( $settings['dethemekit-map-locale'] )
        );
        
        update_option( 'pa_maps_save_settings', $this->pa_maps_settings );
        
        return true;
    }
}