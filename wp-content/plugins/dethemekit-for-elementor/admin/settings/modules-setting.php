<?php

namespace DethemeKitAddons\Admin\Settings;

use DethemeKitAddons\Helper_Functions;

if( ! defined( 'ABSPATH' ) ) exit(); // Exit if accessed directly

class Modules_Settings {
    
    protected $page_slug = 'dethemekit-addons';

    public static $pa_elements_keys = ['dethemekit-banner', 'dethemekit-blog', 'dethemekit-carousel', 'dethemekit-countdown', 'dethemekit-counter', 'dethemekit-dual-header', 'dethemekit-fancytext', 'dethemekit-image-separator', 'dethemekit-lottie', 'dethemekit-maps', 'dethemekit-modalbox', 'dethemekit-person', 'dethemekit-progressbar', 'dethemekit-testimonials', 'dethemekit-title', 'dethemekit-videobox', 'dethemekit-pricing-table', 'dethemekit-button', 'dethemekit-contactform',  'dethemekit-image-button', 'dethemekit-grid', 'dethemekit-vscroll', 'dethemekit-image-scroll', 'dethemekit-templates', 'dethemekit-duplicator'];
    
    private $pa_default_settings;
    
    private $pa_settings;
    
    private $pa_get_settings;
   
    public function __construct() {
        
        add_action( 'admin_menu', array( $this,'pa_admin_menu') );
        
        add_action( 'admin_enqueue_scripts', array( $this, 'pa_admin_page_scripts' ) );
        
        add_action( 'wp_ajax_pa_save_admin_addons_settings', array( $this, 'pa_save_settings' ) );
        
        add_action( 'admin_enqueue_scripts',array( $this, 'localize_js_script' ) );
        
    }
    
    public function localize_js_script(){
        wp_localize_script(
            'pa-admin-js',
            'dethemekitRollBackConfirm',
            [
                'home_url'  => home_url(),
                'i18n' => [
					'rollback_confirm' => __( 'Are you sure you want to reinstall version ' . DETHEMEKIT_ADDONS_STABLE_VERSION . ' ?', 'dethemekit-addons-for-elementor' ),
					'rollback_to_previous_version' => __( 'Rollback to Previous Version', 'dethemekit-addons-for-elementor' ),
					'yes' => __( 'Yes', 'dethemekit-addons-for-elementor' ),
					'cancel' => __( 'Cancel', 'dethemekit-addons-for-elementor' ),
				],
            ]
            );
    }

    public function pa_admin_page_scripts () {
        
        wp_enqueue_style( 'pa_admin_icon', DETHEMEKIT_ADDONS_URL .'admin/assets/fonts/style.css' );
        
        $suffix = is_rtl() ? '-rtl' : '';
        
        $current_screen = get_current_screen();
        
        wp_enqueue_style(
            'pa-notice-css',
            DETHEMEKIT_ADDONS_URL . 'admin/assets/css/notice' . $suffix . '.css'
        );
        
        if( strpos( $current_screen->id , $this->page_slug ) !== false ) {
            
            wp_enqueue_style(
                'pa-admin-css',
                DETHEMEKIT_ADDONS_URL.'admin/assets/css/admin' . $suffix . '.css'
            );
            
            wp_enqueue_style(
                'pa-sweetalert-style',
                DETHEMEKIT_ADDONS_URL . 'admin/assets/js/sweetalert2/sweetalert2.min.css'
            );
            
            wp_enqueue_script(
                'pa-admin-js',
                DETHEMEKIT_ADDONS_URL .'admin/assets/js/admin.js',
                array('jquery'),
                DETHEMEKIT_ADDONS_VERSION,
                true
            );
            
            wp_enqueue_script(
                'pa-admin-dialog',
                DETHEMEKIT_ADDONS_URL . 'admin/assets/js/dialog/dialog.js',
                array('jquery-ui-position'),
                DETHEMEKIT_ADDONS_VERSION,
                true
            );
            
            wp_enqueue_script(
                'pa-sweetalert-core',
                DETHEMEKIT_ADDONS_URL . 'admin/assets/js/sweetalert2/core.js',
                array('jquery'),
                DETHEMEKIT_ADDONS_VERSION,
                true
            );
            
			wp_enqueue_script(
                'pa-sweetalert',
                DETHEMEKIT_ADDONS_URL . 'admin/assets/js/sweetalert2/sweetalert2.min.js',
                array( 'jquery', 'pa-sweetalert-core' ),
                DETHEMEKIT_ADDONS_VERSION,
                true
            );
            
        }
    }

    public function pa_admin_menu() {
        
        $plugin_name = 'DethemeKit for Elementor';
        
        if( defined( 'DETHEMEKIT_PRO_ADDONS_VERSION' ) ) {
            if( isset( get_option( 'pa_wht_lbl_save_settings' )['dethemekit-wht-lbl-plugin-name'] ) ) {
                $name = get_option( 'pa_wht_lbl_save_settings' )['dethemekit-wht-lbl-plugin-name'];
                if( '' !== $name )
                    $plugin_name = $name;
            }
            
        }
        
        // HIDE FOR TEMP
        // add_menu_page(
        //     $plugin_name,
        //     $plugin_name,
        //     'manage_options',
        //     'dethemekit-addons',
        //     array( $this , 'pa_admin_page' ),
        //     '' ,
        //     100
        // );
    }

    public function pa_admin_page() {
        
        $theme_slug = Helper_Functions::get_installed_theme();
        
        $js_info = array(
            'ajaxurl'   => admin_url( 'admin-ajax.php' ),
            'nonce' 	=> wp_create_nonce( 'pa-elements' ),
            'theme'     => $theme_slug
		);

		wp_localize_script( 'pa-admin-js', 'settings', $js_info );
        
        $this->pa_default_settings = $this->get_default_keys();
       
        $this->pa_get_settings = $this->get_enabled_keys();
       
        $pa_new_settings = array_diff_key( $this->pa_default_settings, $this->pa_get_settings );
       
        if( ! empty( $pa_new_settings ) ) {
            $pa_updated_settings = array_merge( $this->pa_get_settings, $pa_new_settings );
            update_option( 'pa_save_settings', $pa_updated_settings );
        }
        $this->pa_get_settings = get_option( 'pa_save_settings', $this->pa_default_settings );
        
        $prefix = Helper_Functions::get_prefix();
        
	?>
	<div class="wrap">
        <div class="response-wrap"></div>
        <form action="" method="POST" id="pa-settings" name="pa-settings">
            <div class="pa-header-wrapper">
                <div class="pa-title-left">
                    <h1 class="pa-title-main"><?php echo Helper_Functions::name(); ?></h1>
                    <h3 class="pa-title-sub"><?php echo sprintf(__('Thank you for using %s. This plugin has been developed by %s and we hope you enjoy using it.','dethemekit-addons-for-elementor'), Helper_Functions::name(), Helper_Functions::author() ); ?></h3>
                </div>
                <?php if( ! Helper_Functions::is_hide_logo() ) : ?>
                <div class="pa-title-right">
                    <img class="pa-logo" src="<?php echo DETHEMEKIT_ADDONS_URL . 'admin/images/dethemekit-addons-logo.png';?>">
                </div>
                <?php endif; ?>
            </div>
            <div class="pa-settings-tabs">
                <div id="pa-modules" class="pa-settings-tab">
                    <div>
                        <br>
                        <input type="checkbox" class="pa-checkbox" checked="checked">
                        <label>Enable/Disable All</label>
                    </div>
                    <table class="pa-elements-table">
                        <tbody>
                            <tr>
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Banner', 'dethemekit-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="dethemekit-banner" name="dethemekit-banner" <?php checked(1, $this->pa_get_settings['dethemekit-banner'], true) ?>>
                                        <span class="slider round"></span>
                                </label>
                                </td>
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Blog', 'dethemekit-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="dethemekit-blog" name="dethemekit-blog" <?php checked(1, $this->pa_get_settings['dethemekit-blog'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Button', 'dethemekit-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="dethemekit-button" name="dethemekit-button" <?php checked(1, $this->pa_get_settings['dethemekit-button'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Carousel', 'dethemekit-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="dethemekit-carousel" name="dethemekit-carousel" <?php checked(1, $this->pa_get_settings['dethemekit-carousel'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Contact Form7', 'dethemekit-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="dethemekit-contactform" name="dethemekit-contactform" <?php checked(1, $this->pa_get_settings['dethemekit-contactform'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Countdown', 'dethemekit-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="dethemekit-countdown" name="dethemekit-countdown" <?php checked(1, $this->pa_get_settings['dethemekit-countdown'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Counter', 'dethemekit-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="dethemekit-counter" name="dethemekit-counter" <?php checked(1, $this->pa_get_settings['dethemekit-counter'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Dual Heading', 'dethemekit-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="dethemekit-dual-header" name="dethemekit-dual-header" <?php checked(1, $this->pa_get_settings['dethemekit-dual-header'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Fancy Text', 'dethemekit-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="dethemekit-fancytext" name="dethemekit-fancytext" <?php checked(1, $this->pa_get_settings['dethemekit-fancytext'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                                
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Media Grid', 'dethemekit-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="dethemekit-grid" name="dethemekit-grid" <?php checked(1, $this->pa_get_settings['dethemekit-grid'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Image Button', 'dethemekit-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="dethemekit-image-button" name="dethemekit-image-button" <?php checked(1, $this->pa_get_settings['dethemekit-image-button'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                                
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Image Scroll', 'dethemekit-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="dethemekit-image-scroll" name="dethemekit-image-scroll" <?php checked(1, $this->pa_get_settings['dethemekit-image-scroll'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Image Separator', 'dethemekit-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="dethemekit-image-separator" name="dethemekit-image-separator" <?php checked(1, $this->pa_get_settings['dethemekit-image-separator'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Lottie Animations', 'dethemekit-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="dethemekit-lottie" name="dethemekit-lottie" <?php checked(1, $this->pa_get_settings['dethemekit-lottie'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Maps', 'dethemekit-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="dethemekit-maps" name="dethemekit-maps" <?php checked(1, $this->pa_get_settings['dethemekit-maps'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Modal Box', 'dethemekit-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="dethemekit-modalbox" name="dethemekit-modalbox" <?php checked(1, $this->pa_get_settings['dethemekit-modalbox'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Team Members', 'dethemekit-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="dethemekit-person" name="dethemekit-person" <?php checked(1, $this->pa_get_settings['dethemekit-person'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>

                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Progress Bar', 'dethemekit-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="dethemekit-progressbar" name="dethemekit-progressbar" <?php checked(1, $this->pa_get_settings['dethemekit-progressbar'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>                                
                            </tr>
                            
                            <tr>
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Pricing Table', 'dethemekit-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="dethemekit-pricing-table" name="dethemekit-pricing-table" <?php checked(1, $this->pa_get_settings['dethemekit-pricing-table'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>

                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Testimonials', 'dethemekit-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="dethemekit-testimonials" name="dethemekit-testimonials" <?php checked(1, $this->pa_get_settings['dethemekit-testimonials'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Title', 'dethemekit-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="dethemekit-title" name="dethemekit-title" <?php checked(1, $this->pa_get_settings['dethemekit-title'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>

                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Video Box', 'dethemekit-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="dethemekit-videobox" name="dethemekit-videobox" <?php checked(1, $this->pa_get_settings['dethemekit-videobox'], true) ?>>
                                            <span class="slider round"></span>
                                        </label>
                                </td>
                            </tr>

                            <tr>
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Vertical Scroll', 'dethemekit-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="dethemekit-vscroll" name="dethemekit-vscroll" <?php checked(1, $this->pa_get_settings['dethemekit-vscroll'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Duplicator', 'dethemekit-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="dethemekit-duplicator" name="dethemekit-duplicator" <?php checked(1, $this->pa_get_settings['dethemekit-duplicator'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                                
                                <th><?php echo sprintf( '%1$s %2$s', $prefix, __('Templates', 'dethemekit-addons-for-elementor') ); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox" id="dethemekit-templates" name="dethemekit-templates" <?php checked(1, $this->pa_get_settings['dethemekit-templates'], true) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                </td>
                                
                            </tr>

                            <?php if( ! defined( 'DETHEMEKIT_PRO_ADDONS_VERSION' ) ) : ?> 
                            <tr class="pa-sec-elems-tr"><th><h1>PRO Elements</h1></th></tr>

                            <tr>
                                
                                <th><?php echo __('DethemeKit Alert Box', 'dethemekit-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                                <th><?php echo __('DethemeKit Behance Feed', 'dethemekit-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                            </tr>
                            
                            <tr>
                                
                                <th><?php echo __('DethemeKit Charts', 'dethemekit-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                                <th><?php echo __('DethemeKit Content Switcher', 'dethemekit-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                            </tr>
                            
                            <tr>
                                <th><?php echo __('DethemeKit Background Transition', 'dethemekit-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                                <th><?php echo __('DethemeKit Divider', 'dethemekit-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                
                                <th><?php echo __('DethemeKit Facebook Feed', 'dethemekit-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                                <th><?php echo __('DethemeKit Facebook Reviews', 'dethemekit-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo __('DethemeKit Flip Box', 'dethemekit-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                
                                <th><?php echo __('DethemeKit Google Reviews', 'dethemekit-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo __('DethemeKit Horizontal Scroll', 'dethemekit-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>

                                <th><?php echo __('DethemeKit Icon Box', 'dethemekit-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo __('DethemeKit iHover', 'dethemekit-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>

                                <th><?php echo __('DethemeKit Image Accordion', 'dethemekit-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo __('DethemeKit Image Comparison', 'dethemekit-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>

                                <th><?php echo __('DethemeKit Image Hotspots', 'dethemekit-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo __('DethemeKit Image Layers', 'dethemekit-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>

                                <th><?php echo __('DethemeKit Instagram Feed', 'dethemekit-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo __('DethemeKit Magic Section', 'dethemekit-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>

                                <th><?php echo __('DethemeKit Messenger Chat', 'dethemekit-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo __('DethemeKit Multi Scroll', 'dethemekit-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                <th><?php echo __('DethemeKit Preview Window', 'dethemekit-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo __('DethemeKit Table', 'dethemekit-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                <th><?php echo __('DethemeKit Tabs', 'dethemekit-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo __('DethemeKit Twitter Feed', 'dethemekit-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                <th><?php echo __('DethemeKit Unfold', 'dethemekit-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo __('DethemeKit Whatsapp Chat', 'dethemekit-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>

                                <th><?php echo __('DethemeKit Yelp Reviews', 'dethemekit-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo __('DethemeKit Section Parallax', 'dethemekit-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                <th><?php echo __('DethemeKit Section Particles', 'dethemekit-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><?php echo __('DethemeKit Section Animated Gradient', 'dethemekit-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                                <th><?php echo __('DethemeKit Section Ken Burns', 'dethemekit-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                            </tr>

                            <tr>
                                <th><?php echo __('DethemeKit Section Lottie Animations', 'dethemekit-addons-for-elementor'); ?></th>
                                <td>
                                    <label class="switch">
                                            <input type="checkbox">
                                            <span class="pro-slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            
                            <?php endif; ?> 
                        </tbody>
                    </table>
                    <input type="submit" value="<?php echo __('Save Settings', 'dethemekit-addons-for-elementor'); ?>" class="button pa-btn pa-save-button">
                    
                </div>
                <?php if( ! Helper_Functions::is_hide_rate()) : ?>
                    <div>
                        <p><?php echo __('Did you like DethemeKit Addons for Elementor Plugin? Please ', 'dethemekit-addons-for-elementor'); ?><a href="https://wordpress.org/support/plugin/dethemekit-addons-for-elementor/reviews/#new-post" target="_blank"><?php echo __('Click Here to Rate it ★★★★★', 'dethemekit-addons-for-elementor'); ?></a></p>
                    </div>
                <?php endif; ?>
            </div>
            </form>
        </div>
	<?php
}

    public static function get_default_keys() {
        
        $default_keys = array_fill_keys( self::$pa_elements_keys, true );
        
        return $default_keys;
    }
    
    public static function get_enabled_keys() {
        
        $enabled_keys = get_option( 'pa_save_settings', self::get_default_keys() );
        
        return $enabled_keys;
    }
    
    /*
     * Check If DethemeKit Templates is enabled
     * 
     * @since 3.6.0
     * @access public
     * 
     * @return boolean
     */
    public static function check_dethemekit_templates() {
        
        $settings = self::get_enabled_keys();
        
        if( ! isset( $settings['dethemekit-templates'] ) )
            return true;

        $is_enabled = $settings['dethemekit-templates'];
        
        return $is_enabled;
    }
    
    /*
     * Check If DethemeKit Duplicator is enabled
     * 
     * @since 3.9.7
     * @access public
     * 
     * @return boolean
     */
    public static function check_dethemekit_duplicator() {
        
        $settings = self::get_enabled_keys();
        
        if( ! isset( $settings['dethemekit-duplicator'] ) )
            return true;

        $is_enabled = $settings['dethemekit-duplicator'];
        
        return $is_enabled;
    }

    public function pa_save_settings() {
        
        check_ajax_referer( 'pa-elements', 'security' );

        if( isset( $_POST['fields'] ) ) {
            parse_str( $_POST['fields'], $settings );
        } else {
            return;
        }

        $this->pa_settings = array(
            'dethemekit-banner'            => intval( $settings['dethemekit-banner'] ? 1 : 0 ),
            'dethemekit-blog'              => intval( $settings['dethemekit-blog'] ? 1 : 0 ),
            'dethemekit-carousel'          => intval( $settings['dethemekit-carousel'] ? 1 : 0 ),
            'dethemekit-countdown'         => intval( $settings['dethemekit-countdown'] ? 1 : 0 ),
            'dethemekit-counter'           => intval( $settings['dethemekit-counter'] ? 1 : 0 ),
            'dethemekit-dual-header'       => intval( $settings['dethemekit-dual-header'] ? 1 : 0 ),
            'dethemekit-fancytext'         => intval( $settings['dethemekit-fancytext'] ? 1 : 0 ),
            'dethemekit-image-separator'   => intval( $settings['dethemekit-image-separator'] ? 1 : 0 ),
            'dethemekit-lottie'            => intval( $settings['dethemekit-lottie'] ? 1 : 0 ),
            'dethemekit-maps'              => intval( $settings['dethemekit-maps'] ? 1 : 0 ),
            'dethemekit-modalbox' 			=> intval( $settings['dethemekit-modalbox'] ? 1 : 0 ),
            'dethemekit-person' 			=> intval( $settings['dethemekit-person'] ? 1 : 0 ),
            'dethemekit-progressbar' 		=> intval( $settings['dethemekit-progressbar'] ? 1 : 0 ),
            'dethemekit-testimonials' 		=> intval( $settings['dethemekit-testimonials'] ? 1 : 0 ),
            'dethemekit-title'             => intval( $settings['dethemekit-title'] ? 1 : 0 ),
            'dethemekit-videobox'          => intval( $settings['dethemekit-videobox'] ? 1 : 0 ),
            'dethemekit-pricing-table'     => intval( $settings['dethemekit-pricing-table'] ? 1 : 0 ),
            'dethemekit-button'            => intval( $settings['dethemekit-button'] ? 1 : 0 ),
            'dethemekit-contactform'       => intval( $settings['dethemekit-contactform'] ? 1 : 0 ),
            'dethemekit-image-button'      => intval( $settings['dethemekit-image-button'] ? 1 : 0 ),
            'dethemekit-grid'              => intval( $settings['dethemekit-grid'] ? 1 : 0 ),
            'dethemekit-vscroll'           => intval( $settings['dethemekit-vscroll'] ? 1 : 0 ),
            'dethemekit-image-scroll'      => intval( $settings['dethemekit-image-scroll'] ? 1 : 0 ),
            'dethemekit-templates'         => intval( $settings['dethemekit-templates'] ? 1 : 0 ),
            'dethemekit-duplicator'        => intval( $settings['dethemekit-duplicator'] ? 1 : 0 ),
        );

        update_option( 'pa_save_settings', $this->pa_settings );

        return true;
    }
}