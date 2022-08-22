<?php

namespace DethemeKitAddons\Admin\Includes;

use DethemeKitAddons\Helper_Functions;

if( ! defined( 'ABSPATH') ) exit();

class Admin_Notices {
    
    private static $instance = null;
    
    private static $elementor = 'elementor';
    
    private static $papro = 'dethemekit-addons-pro';
    
    /**
    * Constructor for the class
    */
    public function __construct() {
        
        add_action( 'admin_init', array( $this, 'init') );
        
        add_action( 'admin_notices', array( $this, 'admin_notices' ) );
        
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
        
        add_action( 'wp_ajax_pa_reset_admin_notice', array( $this, 'reset_admin_notice' ) );
        
        add_action( 'wp_ajax_pa_dismiss_admin_notice', array( $this, 'dismiss_admin_notice' ) );
        
    }
    
    /**
    * init required functions
    */
    public function init() {

        $this->handle_review_notice();
        
        $this->handle_lottie_notice();
        
    }
    
    /**
    * init notices check functions
    */
    public function admin_notices() {
        
        $this->required_plugins_check();
        
        $cache_key = 'dethemekit_notice_' . DETHEMEKIT_ADDONS_VERSION;
        
        // $response = get_transient( $cache_key );
        
        // if ( false == $response ) {
        //     $this->get_review_notice();
        // }
        
        // $this->get_lottie_notice();
        
    }

    /**
     * 
     * Checks if review message is dismissed.
     * 
     * @access public
     * @return void
     * 
     */
    public function handle_review_notice() {

        if ( ! isset( $_GET['pa_review'] ) ) {
            return;
        }

        if ( 'opt_out' === $_GET['pa_review'] ) {
            check_admin_referer( 'opt_out' );

            update_option( 'pa_review_notice', '1' );
        }

        wp_redirect( remove_query_arg( 'pa_review' ) );
        
        exit;
    }
   
    /**
     * Checks if DethemeKit Horizontal Scroll message is dismissed.
     * 
     * @since 3.11.7
     * @access public
     * 
     * @return void
     */
    public function handle_lottie_notice() {
        
        if ( ! isset( $_GET['lottie_widget'] ) ) {
            return;
        }

        if ( 'opt_out' === $_GET['lottie_widget'] ) {
            check_admin_referer( 'opt_out' );

            update_option( 'lottie_widget_notice', '1' );
        }

        wp_redirect( remove_query_arg( 'lottie_widget' ) );
        exit;
    }
    
    /**
     * Required plugin check
     * 
     * Shows an admin notice when Elementor is missing.
     * 
     * @access public
     * 
     * @return boolean
     */
    public function required_plugins_check() {

        $elementor_path = sprintf( '%1$s/%1$s.php', self::$elementor );
        
        if( ! defined('ELEMENTOR_VERSION' ) ) {

            if ( ! Helper_Functions::is_plugin_installed( $elementor_path ) ) {

                if( self::check_user_can( 'install_plugins' ) ) {

                    $install_url = wp_nonce_url( self_admin_url( sprintf( 'update.php?action=install-plugin&plugin=%s', self::$elementor ) ), 'install-plugin_elementor' );

                    $message = sprintf( '<p>%s</p>', __('DethemeKit Addons for Elementor is not working because you need to Install Elementor plugin.', 'dethemekit-addons-for-elementor' ) );

                    $message .= sprintf( '<p><a href="%s" class="button-primary">%s</a></p>', $install_url, __( 'Install Now', 'dethemekit-addons-for-elementor' ) );

                }
            } else {
                if( self::check_user_can( 'activate_plugins' ) ) {

                    $activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $elementor_path . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $elementor_path );

                    $message = '<p>' . __( 'DethemeKit Addons for Elementor is not working because you need to activate Elementor plugin.', 'dethemekit-addons-for-elementor' ) . '</p>';

                    $message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, __( 'Activate Now', 'dethemekit-addons-for-elementor' ) ) . '</p>';

                }
            }
            $this->render_admin_notices( $message );
        }
    }
    
    /**
     * Gets admin review notice HTML
     * 
     * @since 2.8.4
     * @return void
     * 
     */
    public function get_review_text( $review_url, $optout_url ) {
        
        $notice = sprintf(
            '<p>' . __('Did you like','dethemekit-addons-for-elementor') . '<strong>&nbspDethemeKit Addons for Elementor&nbsp</strong>' . __('Plugin?','dethemekit-addons-for-elementor') . '</p>
             <p>' . __('Could you please do us a BIG favor ? if you could take 2 min of your time, we\'d really appreciate if you give DethemeKit Addons for Elementor 5-star rating on WordPress. By spreading the love, we can create even greater free stuff in the future!','dethemekit-addons-for-elementor') . '</p>
            <p><a class="button button-primary" href="%s" target="_blank"><span><i class="dashicons dashicons-external"></i>' . __('Leave a Review','dethemekit-addons-for-elementor') . '</span></a>
                <a class="button button-secondary pa-notice-reset"><span><i class="dashicons dashicons-calendar-alt"></i>' . __('Maybe Later','dethemekit-addons-for-elementor') . '</span></a>
                <a class="button button-secondary" href="%2$s"><span><i class="dashicons dashicons-smiley"></i>' . __('I Already did','dethemekit-addons-for-elementor') . '</span></a>
            </p>',
        $review_url, $optout_url );
        
        return $notice;
    }
        
    /**
     * Checks if review admin notice is dismissed
     * 
     * @since 2.6.8
     * @return void
     * 
     */
    public function get_review_notice() {

        $review = get_option( 'pa_review_notice' );

        $review_url = 'https://wordpress.org/support/plugin/dethemekit-addons-for-elementor/reviews/?filter=5';

        if ( '1' === $review ) {
            return;
        } else if ( '1' !== $review ) {
            $optout_url = wp_nonce_url( add_query_arg( 'pa_review', 'opt_out' ), 'opt_out' );
        ?>

        <div class="error pa-notice-wrap" data-notice="pa-review">
            <div class="pa-img-wrap">
                <img src="<?php echo DETHEMEKIT_ADDONS_URL .'admin/images/dethemekit-addons-logo.png'; ?>">
            </div>
            <div class="pa-text-wrap">
                <?php echo $this->get_review_text( $review_url, $optout_url ); ?>
            </div>
            <div class="pa-notice-close">
                <a href="<?php echo esc_url( $optout_url ); ?>"><span class="dashicons dashicons-dismiss"></span></a>
            </div>
        </div>
            
        <?php }
        
    }
    
    
    /**
     * 
     * Shows admin notice for DethemeKit Lottie Animations.
     * 
     * @since 3.11.7
     * @access public
     * 
     * @return void
     */
    public function get_lottie_notice() {
        
        $lottie_notice = get_option( 'lottie_widget_notice' );
        
        if( '1' === $lottie_notice )
            return;
        
        $theme = Helper_Functions::get_installed_theme();
    
        $notice_url = sprintf( 'https://dethemekitaddons.com/elementor-lottie-animations-widget/?utm_source=lottie-notification&utm_medium=wp-dash&utm_campaign=get-pro&utm_term=%s', $theme );
    
        $templates_message = '<div class="pa-text-wrap">';

        $templates_message .= '<img class="pa-notice-logo" src="' . DETHEMEKIT_ADDONS_URL .'admin/images/dethemekit-addons-logo.png' . '">';

        $templates_message .= '<strong>' . __('DethemeKit Lottie Animations','dethemekit-addons-for-elementor') . '&nbsp</strong><span>' . __('widget is now available.', 'dethemekit-addons-for-elementor') . '&nbsp</span><a href="' . esc_url( $notice_url ) . '" target="_blank">' . __('Check it out now', 'dethemekit-addons-for-elementor') . '</a>';

        $templates_message .= '<div class="pa-notice-close" data-notice="lottie"><span class="dashicons dashicons-dismiss"></span></div>';

        $templates_message .= '</div>';

        $this->render_admin_notices( $templates_message );

        
        
    }
    
    /**
     * Checks user credentials for specific action
     * 
     * @since 2.6.8
     * 
     * @return boolean
     */
    public static function check_user_can( $action ) {
        return current_user_can( $action );
    }
    
    /**
     * Renders an admin notice error message
     * 
     * @since 1.0.0
     * @access private
     * 
     * @return void
     */
    private function render_admin_notices( $message, $class = '', $handle = '' ) {
        ?>
            <div class="error pa-new-feature-notice <?php echo $class; ?>" data-notice="<?php echo $handle; ?>">
                <?php echo $message; ?>
            </div>
        <?php
    }
    
    /*
     * Register admin scripts
     * 
     * @since 3.2.8
     * @access public
     * 
     */
    public function admin_enqueue_scripts() {
        
        wp_enqueue_script(
            'pa-notice',
            DETHEMEKIT_ADDONS_URL . 'admin/assets/js/pa-notice.js',
            array( 'jquery' ),
            DETHEMEKIT_ADDONS_VERSION,
            true
        );
        
    }
    
    /**
     * Set transient for admin notice
     * 
     * @since 3.2.8
     * @access public
     * 
     * @return void
     */
    public function reset_admin_notice() {
        
        $key = isset( $_POST['notice'] ) ? $_POST['notice'] : '';
        
        if ( ! empty( $key ) ) {
            
            $cache_key = 'dethemekit_notice_' . DETHEMEKIT_ADDONS_VERSION;
        
            set_transient( $cache_key, true, WEEK_IN_SECONDS );
            
            wp_send_json_success();
            
        } else {
            
            wp_send_json_error();
            
        }
        
    }
    
    /**
     * Dismiss admin notice
     * 
     * @since 3.11.7
     * @access public
     * 
     * @return void
     */
    public function dismiss_admin_notice() {
        
        $key = isset( $_POST['notice'] ) ? $_POST['notice'] : '';
        
        if ( ! empty( $key ) ) {
            
            update_option( 'lottie_widget_notice', '1' );
            
            wp_send_json_success();
            
        } else {
            
            wp_send_json_error();
            
        }
        
    }
    
    

    public static function get_instance() {
        if( self::$instance == null ) {
            self::$instance = new self;
        }
        return self::$instance;
    }
       
}

if( ! function_exists('get_notices_instance') ) {
    /**
	 * Returns an instance of the plugin class.
	 * @since  1.1.1
	 * @return object
	 */
    function get_notices_instance() {
        return Admin_Notices::get_instance();
    }
}

get_notices_instance();