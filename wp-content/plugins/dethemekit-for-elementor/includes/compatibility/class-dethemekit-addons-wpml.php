<?php

namespace DethemeKitAddons\Compatibility\WPML;

if ( ! defined('ABSPATH') ) exit; // No access of directly access

if ( ! class_exists ('DethemeKit_Addons_Wpml') ) {
    
    /**
    * Class DethemeKit_Addons_Wpml.
    */
   class DethemeKit_Addons_Wpml {

       /*
        * Instance of the class
        * @access private
        * @since 3.1.9
        */
        private static $instance = null;

       /**
        * Constructor
        */
       public function __construct() {
           
           $is_wpml_active = self::is_wpml_active();
           
           // WPML String Translation plugin exist check.
           if ( $is_wpml_active ) {
               
               $this->includes();

               add_filter( 'wpml_elementor_widgets_to_translate', [ $this, 'translatable_widgets' ] );
           }
       }
       
       
       /*
        * Is WPML Active
        * 
        * Check if WPML Multilingual CMS and WPML String Translation active
        * 
        * @since 3.1.9
        * @access private
        * 
        * @return boolean is WPML String Translation 
        */
       public static function is_wpml_active() {
           
           include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
           
           $wpml_active = is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' );
           
           $string_translation_active = is_plugin_active( 'wpml-string-translation/plugin.php' );
           
           return $wpml_active && $string_translation_active;
           
       }

       /**
        * 
        * Includes
        * 
        * Integrations class for widgets with complex controls.
        *
        * @since 3.1.9
        */
       public function includes() {
    
            include_once( 'widgets/carousel.php' );
            include_once( 'widgets/fancy-text.php' );
            include_once( 'widgets/grid.php' );
            include_once( 'widgets/maps.php' );
            include_once( 'widgets/pricing-table.php' );
            include_once( 'widgets/progress-bar.php' );
            include_once( 'widgets/vertical-scroll.php' );
    
       }

       /**
        * Widgets to translate.
        *
        * @since 3.1.9
        * @param array $widgets Widget array.
        * @return array
        */
       function translatable_widgets( $widgets ) {

           $widgets['dethemekit-addon-banner'] = [
               'conditions' => [ 'widgetType' => 'dethemekit-addon-banner' ],
               'fields'     => [
                   [
                       'field'       => 'dethemekit_banner_title',
                       'type'        => __( 'Banner: Title', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   [
                       'field'       => 'dethemekit_banner_description',
                       'type'        => __( 'Banner: Description', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'AREA',
                   ],
                   [
                       'field'       => 'dethemekit_banner_more_text',
                       'type'        => __( 'Banner: Button Text', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   'dethemekit_banner_image_custom_link' => [
                       'field'       => 'url',
                       'type'        => __( 'Banner: URL', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINK',
                   ],
                   'dethemekit_banner_link' => [
                       'field'       => 'url',
                       'type'        => __( 'Banner: Button URL', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINK',
                   ],
               ]
           ];
           
           $widgets['dethemekit-addon-button'] = [
               'conditions' => [ 'widgetType' => 'dethemekit-addon-button' ],
               'fields'     => [
                   [
                       'field'       => 'dethemekit_button_text',
                       'type'        => __( 'Button: Text', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   'dethemekit_button_link' => [
                       'field'       => 'url',
                       'type'        => __( 'Button: URL', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINK',
                   ],
               ]
           ];
           
           $widgets['dethemekit-countdown-timer'] = [
               'conditions' => [ 'widgetType' => 'dethemekit-countdown-timer' ],
               'fields'     => [
                   [
                       'field'       => 'dethemekit_countdown_expiry_text_',
                       'type'        => __( 'Countdown: Expiration Message', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'AREA',
                   ],
                   [
                       'field'       => 'dethemekit_countdown_day_singular',
                       'type'        => __( 'Countdown: Day Singular', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   [
                       'field'       => 'dethemekit_countdown_day_plural',
                       'type'        => __( 'Countdown: Day Plural', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   [
                       'field'       => 'dethemekit_countdown_week_singular',
                       'type'        => __( 'Countdown: Week Singular', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   [
                       'field'       => 'dethemekit_countdown_week_plural',
                       'type'        => __( 'Countdown: Week Plural', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   [
                       'field'       => 'dethemekit_countdown_month_singular',
                       'type'        => __( 'Countdown: Month Singular', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   [
                       'field'       => 'dethemekit_countdown_month_plural',
                       'type'        => __( 'Countdown: Month Plural', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   [
                       'field'       => 'dethemekit_countdown_year_singular',
                       'type'        => __( 'Countdown: Year Singular', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   [
                       'field'       => 'dethemekit_countdown_year_plural',
                       'type'        => __( 'Countdown: Year Plural', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   [
                       'field'       => 'dethemekit_countdown_hour_singular',
                       'type'        => __( 'Countdown: Hour Singular', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   [
                       'field'       => 'dethemekit_countdown_hour_plural',
                       'type'        => __( 'Countdown: Hour Plural', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   [
                       'field'       => 'dethemekit_countdown_minute_singular',
                       'type'        => __( 'Countdown: Minute Singular', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   [
                       'field'       => 'dethemekit_countdown_minute_plural',
                       'type'        => __( 'Countdown: Minute Plural', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   [
                       'field'       => 'dethemekit_countdown_second_singular',
                       'type'        => __( 'Countdown: Second Singular', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   [
                       'field'       => 'dethemekit_countdown_second_plural',
                       'type'        => __( 'Countdown: Second Plural', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   'dethemekit_countdown_expiry_redirection_' => [
                       'field'       => 'url',
                       'type'        => __( 'Countdown: Direction URL', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINK',
                   ],
               ]
           ];
           
           $widgets['dethemekit-counter'] = [
               'conditions' => [ 'widgetType' => 'dethemekit-counter' ],
               'fields'     => [
                   [
                       'field'       => 'dethemekit_counter_title',
                       'type'        => __( 'Counter: Title Text', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   [
                       'field'       => 'dethemekit_counter_t_separator',
                       'type'        => __( 'Counter: Thousands Separator', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   [
                       'field'       => 'dethemekit_counter_preffix',
                       'type'        => __( 'Counter: Prefix', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   [
                       'field'       => 'dethemekit_counter_suffix',
                       'type'        => __( 'Counter: Suffix', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   'dethemekit_dual_heading_link' => [
                       'field'       => 'url',
                       'type'        => __( 'Advanced Heading: Heading URL', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINK',
                   ]
               ]
           ];
           
           $widgets['dethemekit-addon-dual-header'] = [
               'conditions' => [ 'widgetType' => 'dethemekit-addon-dual-header' ],
               'fields'     => [
                   [
                       'field'       => 'dethemekit_dual_header_first_header_text',
                       'type'        => __( 'Dual Heading: First Heading', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   [
                       'field'       => 'dethemekit_dual_header_second_header_text',
                       'type'        => __( 'Dual Heading: Second Heading', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   'dethemekit_dual_heading_link' => [
                       'field'       => 'url',
                       'type'        => __( 'Advanced Heading: Heading URL', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINK',
                   ]
               ]
           ];
           
           $widgets['dethemekit-carousel-widget'] = [
               'conditions' => [ 'widgetType' => 'dethemekit-carousel-widget' ],
               'integration-class' => 'DethemeKitAddons\Compatibility\WPML\Widgets\Carousel',
           ];
           
           $widgets['dethemekit-addon-fancy-text'] = [
               'conditions' => [ 'widgetType' => 'dethemekit-addon-fancy-text' ],
               'fields'     => [
                   [
                       'field'       => 'dethemekit_fancy_prefix_text',
                       'type'        => __( 'Fancy Text: Prefix', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   [
                       'field'       => 'dethemekit_fancy_suffix_text',
                       'type'        => __( 'Fancy Text: Suffix', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   [
                       'field'       => 'dethemekit_fancy_text_cursor_text',
                       'type'        => __( 'Fancy Text: Cursor Text', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
               ],
               'integration-class' => 'DethemeKitAddons\Compatibility\WPML\Widgets\FancyText',
           ];
           
           $widgets['dethemekit-img-gallery'] = [
               'conditions' => [ 'widgetType' => 'dethemekit-img-gallery' ],
               'fields'     => [
                   [
                       'field'       => 'dethemekit_gallery_load_more_text',
                       'type'        => __( 'Grid: Load More Button', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ]
               ],
               'integration-class' => 'DethemeKitAddons\Compatibility\WPML\Widgets\Grid',
           ];
           
           $widgets['dethemekit-addon-image-button'] = [
               'conditions' => [ 'widgetType' => 'dethemekit-addon-image-button' ],
               'fields'     => [
                   [
                       'field'       => 'dethemekit_image_button_text',
                       'type'        => __( 'Button: Text', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   'dethemekit_image_button_link' => [
                       'field'       => 'url',
                       'type'        => __( 'Button: URL', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINK',
                   ],
               ]
           ];
           
           $widgets['dethemekit-image-scroll'] = [
               'conditions' => [ 'widgetType' => 'dethemekit-image-scroll' ],
               'fields'     => [
                   [
                       'field'       => 'link_text',
                       'type'        => __( 'Image Scroll: Link Title', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   'link' => [
                       'field'       => 'url',
                       'type'        => __( 'Image Scroll: URL', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINK',
                   ]
               ]
           ];
           
           $widgets['dethemekit-addon-image-separator'] = [
               'conditions' => [ 'widgetType' => 'dethemekit-addon-image-separator' ],
               'fields'     => [
                   [
                       'field'       => 'dethemekit_image_separator_image_link_text',
                       'type'        => __( 'Image Separator: Link Title', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   'link' => [
                       'field'       => 'dethemekit_image_separator_image_link',
                       'type'        => __( 'Image Separator: URL', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINK',
                   ]
               ]
           ];
           
           $widgets['dethemekit-addon-maps'] = [
               'conditions' => [ 'widgetType' => 'dethemekit-addon-maps' ],
               'fields'     => [
                   [
                       'field'       => 'dethemekit_maps_center_lat',
                       'type'        => __( 'Maps: Center Latitude', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   [
                       'field'       => 'dethemekit_maps_center_long',
                       'type'        => __( 'Maps: Center Longitude', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ]
               ],
               'integration-class' => 'DethemeKitAddons\Compatibility\WPML\Widgets\Maps',
           ];
           
           $widgets['dethemekit-addon-modal-box'] = [
               'conditions' => [ 'widgetType' => 'dethemekit-addon-modal-box' ],
               'fields'     => [
                   [
                       'field'       => 'dethemekit_modal_box_title',
                       'type'        => __( 'Modal Box: Header Title', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   [
                       'field'       => 'dethemekit_modal_box_content',
                       'type'        => __( 'Modal Box: Content Text', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'VISUAL',
                   ],
                   [
                       'field'       => 'dethemekit_modal_close_text',
                       'type'        => __( 'Modal Box: Close Button', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   [
                       'field'       => 'dethemekit_modal_box_button_text',
                       'type'        => __( 'Modal Box: Trigger Button', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   [
                       'field'       => 'dethemekit_modal_box_selector_text',
                       'type'        => __( 'Modal Box: Trigger Text', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],  
               ],
           ];
           
           $widgets['dethemekit-addon-person'] = [
               'conditions' => [ 'widgetType' => 'dethemekit-addon-person' ],
               'fields'     => [
                   [
                       'field'       => 'dethemekit_person_name',
                       'type'        => __( 'Person: Name', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   [
                       'field'       => 'dethemekit_person_title',
                       'type'        => __( 'Person: Title', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   [
                       'field'       => 'dethemekit_person_content',
                       'type'        => __( 'Person: Description', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'AREA',
                   ],
               ],
           ];
           
           $widgets['dethemekit-addon-pricing-table'] = [
               'conditions' => [ 'widgetType' => 'dethemekit-addon-pricing-table' ],
               'fields'     => [
                   [
                       'field'       => 'dethemekit_pricing_table_title_text',
                       'type'        => __( 'Pricing Table: Title', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   [
                       'field'       => 'dethemekit_pricing_table_slashed_price_value',
                       'type'        => __( 'Pricing Table: Slashed Price', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   [
                       'field'       => 'dethemekit_pricing_table_price_currency',
                       'type'        => __( 'Pricing Table: Currency', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   [
                       'field'       => 'dethemekit_pricing_table_price_value',
                       'type'        => __( 'Pricing Table: Price Value', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   [
                       'field'       => 'dethemekit_pricing_table_price_separator',
                       'type'        => __( 'Pricing Table: Separator', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   [
                       'field'       => 'dethemekit_pricing_table_price_duration',
                       'type'        => __( 'Pricing Table: Duration', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   [
                       'field'       => 'dethemekit_pricing_table_description_text',
                       'type'        => __( 'Pricing Table: Description', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'AREA',
                   ],
                   [
                       'field'       => 'dethemekit_pricing_table_button_text',
                       'type'        => __( 'Pricing Table: Button Text', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   [
                       'field'       => 'dethemekit_pricing_table_button_link',
                       'type'        => __( 'Pricing Table: Button URL', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINK',
                   ],
                   [
                       'field'       => 'dethemekit_pricing_table_badge_text',
                       'type'        => __( 'Pricing Table: Badge', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
               ],
               'integration-class' => 'DethemeKitAddons\Compatibility\WPML\Widgets\Pricing_Table',
           ];
           
           $widgets['dethemekit-addon-progressbar'] = [
               'conditions' => [ 'widgetType' => 'dethemekit-addon-progressbar' ],
               'fields'     => [
                   [
                       'field'       => 'dethemekit_progressbar_left_label',
                       'type'        => __( 'Progress Bar: Left Label', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
               ],
               'integration-class' => 'DethemeKitAddons\Compatibility\WPML\Widgets\Progress_Bar',
           ];
           
           $widgets['dethemekit-addon-testimonials'] = [
               'conditions' => [ 'widgetType' => 'dethemekit-addon-testimonials' ],
               'fields'     => [
                   [
                       'field'       => 'dethemekit_testimonial_person_name',
                       'type'        => __( 'Testimonial: Name', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   [
                       'field'       => 'dethemekit_testimonial_company_name',
                       'type'        => __( 'Testimonial: Company', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ],
                   [
                       'field'       => 'dethemekit_testimonial_company_link',
                       'type'        => __( 'Testimonial: Company Link', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINK',
                   ],
                   [
                       'field'       => 'dethemekit_testimonial_content',
                       'type'        => __( 'Testimonial: Content', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'AREA',
                   ],
               ],
           ];
           
           $widgets['dethemekit-addon-title'] = [
               'conditions' => [ 'widgetType' => 'dethemekit-addon-title' ],
               'fields'     => [
                   [
                       'field'       => 'dethemekit_title_text',
                       'type'        => __( 'Title: Text', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ]
               ],
           ];
           
           $widgets['dethemekit-addon-video-box'] = [
               'conditions' => [ 'widgetType' => 'dethemekit-addon-video-box' ],
               'fields'     => [
                   [
                       'field'       => 'dethemekit_video_box_link',
                       'type'        => __( 'Video Box: Link', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINK',
                   ],
                   [
                       'field'       => 'dethemekit_video_box_description_text',
                       'type'        => __( 'Video Box: Description', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'AREA',
                   ]
               ]
           ];
           
           $widgets['dethemekit-vscroll'] = [
               'conditions' => [ 'widgetType' => 'dethemekit-vscroll' ],
               'fields'     => [
                   [
                       'field'       => 'dots_tooltips',
                       'type'        => __( 'Vertical Scroll: Tooltips', 'dethemekit-addons-for-elementor' ),
                       'editor_type' => 'LINE',
                   ]
               ],
               'integration-class' => 'DethemeKitAddons\Compatibility\WPML\Widgets\Vertical_Scroll',
           ];

           return $widgets;
       }
       
       /**
         * Creates and returns an instance of the class
         * @since 0.0.1
         * @access public
         * return object
         */
        public static function get_instance() {
            if( self::$instance == null ) {
                self::$instance = new self;
            }
            return self::$instance;
        }
       
   }
 
}

if( ! function_exists('dethemekit_addons_wpml') ) {
    
    /**
    * Triggers `get_instance` method
    * @since 0.0.1 
   * @access public
    * return object
    */
    function dethemekit_addons_wpml() {
        
     DethemeKit_Addons_Wpml::get_instance();
        
    }
    
}
dethemekit_addons_wpml();