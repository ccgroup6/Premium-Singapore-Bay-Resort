<?php

namespace DethemeKit\Widgets;
// Elementor Classes.
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Embed;
use Elementor\Controls_Stack;
use Elementor\Control_Media;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use DethemeKit\Modules\Woocommerce\Classes\Products_Renderer;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class De_Product
 */
class De_Product_Display extends Widget_Base {

    public function get_name() {
        return 'dethemekit-product-display';
    }
    
    public function get_title() {
        return __( 'De Product Display', 'dethemekit' );
    }

    public function get_icon() {
        return 'eicon-cart-light';
    }
    
    public function get_categories() {
        return [ 'dethemekit-elements' ];
    }

    public function get_style_depends(){
        return [
            'htflexboxgrid',
            'font-awesome',
            'simple-line-icons',
            'elementor-icons-shared-0-css','elementor-icons-fa-brands','elementor-icons-fa-regular','elementor-icons-fa-solid',
            'slick',
            'dethemekit-widgets',
        ];
    }

    public function get_script_depends() {
        return [
            'slick',
            'countdown-min',
            'dethemekit-widgets-scripts',
        ];
    }

    public function get_keywords(){
        return ['slider','product','dethemekit','de product display','woocommerce'];
    }

    protected function _register_controls() {

        // Product Content
        $this->start_controls_section(
            'dethemekit-products-layout-setting',
            [
                'label' => esc_html__( 'Layout Settings', 'dethemekit' ),
            ]
        );
            
            $this->add_control(
                'product_layout_style',
                [
                    'label'   => __( 'Layout', 'dethemekit' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'default',
                    'options' => [
                        'slider'   => __( 'Slider', 'dethemekit' ),
                        'tab'      => __( 'Tab', 'dethemekit' ),
                        'default'  => __( 'Default', 'dethemekit' ),
                    ]
                ]
            );

            $this->add_responsive_control(
                'columns',
                [
                    'label' => __( 'Columns', 'dethemekit' ),
                    'type' => Controls_Manager::NUMBER,
                    'prefix_class' => 'elementor-products-columns%s-',
                    'min' => 1,
                    'max' => 12,
                    'required' => true,
                    'render_type' => 'template',
                    'default' => Products_Renderer::DEFAULT_COLUMNS_AND_ROWS,
                    'device_args' => [
                        Controls_Stack::RESPONSIVE_TABLET => [
                            'required' => false,
                        ],
                        Controls_Stack::RESPONSIVE_MOBILE => [
                            'required' => false,
                        ],
                    ],
                    'min_affected_device' => [
                        Controls_Stack::RESPONSIVE_DESKTOP => Controls_Stack::RESPONSIVE_TABLET,
                        Controls_Stack::RESPONSIVE_TABLET => Controls_Stack::RESPONSIVE_TABLET,
                    ],
                    'condition' => [
                        'product_layout_style!' => 'slider',
                    ]
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'dethemekit-products',
            [
                'label' => esc_html__( 'Query Settings', 'dethemekit' ),
            ]
        );

            $this->add_control(
                'dethemekit_product_grid_product_filter',
                [
                    'label' => esc_html__( 'Filter By', 'dethemekit' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'recent',
                    'options' => [
                        'recent' => esc_html__( 'Recent Products', 'dethemekit' ),
                        'featured' => esc_html__( 'Featured Products', 'dethemekit' ),
                        'best_selling' => esc_html__( 'Best Selling Products', 'dethemekit' ),
                        'sale' => esc_html__( 'Sale Products', 'dethemekit' ),
                        'top_rated' => esc_html__( 'Top Rated Products', 'dethemekit' ),
                        'mixed_order' => esc_html__( 'Random Products', 'dethemekit' ),
                        'show_byid' => esc_html__( 'Show By ID', 'dethemekit' ),
                        'show_byid_manually' => esc_html__( 'Add ID Manually', 'dethemekit' ),
                    ],
                ]
            );

            $this->add_control(
                'dethemekit_product_id',
                [
                    'label' => __( 'Select Product', 'dethemekit' ),
                    'type' => Controls_Manager::SELECT2,
                    'label_block' => true,
                    'multiple' => true,
                    'options' => dethemekit_post_name( 'product' ),
                    'condition' => [
                        'dethemekit_product_grid_product_filter' => 'show_byid',
                    ]
                ]
            );

            $this->add_control(
                'dethemekit_product_ids_manually',
                [
                    'label' => __( 'Product IDs', 'dethemekit' ),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => true,
                    'condition' => [
                        'dethemekit_product_grid_product_filter' => 'show_byid_manually',
                    ]
                ]
            );

            $this->add_control(
              'dethemekit_product_grid_products_count',
                [
                    'label'   => __( 'Product Limit', 'dethemekit' ),
                    'type'    => Controls_Manager::NUMBER,
                    'default' => 3,
                    'step'    => 1,
                ]
            );

            $this->add_control(
                'dethemekit_product_grid_categories',
                [
                    'label' => esc_html__( 'Product Categories', 'dethemekit' ),
                    'type' => Controls_Manager::SELECT2,
                    'label_block' => true,
                    'multiple' => true,
                    'options' => dethemekit_taxonomy_list(),
                    'condition' => [
                        'dethemekit_product_grid_product_filter!' => 'show_byid',
                    ]
                ]
            );

            $this->add_control(
                'dethemekit_custom_order',
                [
                    'label' => esc_html__( 'Custom Order', 'dethemekit' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'orderby',
                [
                    'label' => esc_html__( 'Order by', 'dethemekit' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'none',
                    'options' => [
                        'none'          => esc_html__('None','dethemekit'),
                        'ID'            => esc_html__('ID','dethemekit'),
                        'date'          => esc_html__('Date','dethemekit'),
                        'name'          => esc_html__('Name','dethemekit'),
                        'title'         => esc_html__('Title','dethemekit'),
                        'comment_count' => esc_html__('Comment count','dethemekit'),
                        'rand'          => esc_html__('Random','dethemekit'),
                    ],
                    'condition' => [
                        'dethemekit_custom_order' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'order',
                [
                    'label' => esc_html__( 'Order', 'dethemekit' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'DESC',
                    'options' => [
                        'DESC'  => esc_html__('Descending','dethemekit'),
                        'ASC'   => esc_html__('Ascending','dethemekit'),
                    ],
                    'condition' => [
                        'dethemekit_custom_order' => 'yes',
                    ]
                ]
            );

        $this->end_controls_section();

        // Product Content
        $this->start_controls_section(
            'dethemekit-products-content-setting',
            [
                'label' => esc_html__( 'Content Settings', 'dethemekit' ),
            ]
        );
            
            $this->add_control(
                'product_content_style',
                [
                    'label'   => __( 'Style', 'dethemekit' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => '1',
                    'options' => [
                        '1'  => __( 'Style One', 'dethemekit' ),
                        '2'  => __( 'Style Two', 'dethemekit' ),
                        '3'  => __( 'Style Three', 'dethemekit' ),
                        '4'  => __( 'Style Four', 'dethemekit' ),
                    ]
                ]
            );

            $this->add_control(
                'hide_product_title',
                [
                    'label'     => __( 'Hide Title', 'dethemekit' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .ht-product-inner .ht-product-title' => 'display: none !important;',
                    ],
                ]
            );

            $this->add_control(
                'hide_product_price',
                [
                    'label'     => __( 'Hide Price', 'dethemekit' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .ht-product-inner .ht-product-price' => 'display: none !important;',
                    ],
                ]
            );

            $this->add_control(
                'hide_product_category',
                [
                    'label'     => __( 'Hide Category', 'dethemekit' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .ht-product-inner .ht-product-categories' => 'display: none !important;',
                    ],
                ]
            );

            $this->add_control(
                'hide_product_ratting',
                [
                    'label'     => __( 'Hide Rating', 'dethemekit' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .ht-product-inner .ht-product-ratting-wrap' => 'display: none !important;',
                    ],
                ]
            );

            $this->add_control(
                'stock_progress_bar',
                [
                    'label'     => __( 'Show Product Stock Progress Bar', 'dethemekit-pro' ),
                    'type'      => Controls_Manager::SWITCHER,
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_stock_progressbar',
            [
                'label' => __( 'Stock Progressbar', 'dethemekit-pro' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition'=>[
                    'stock_progress_bar'=>'yes',
                ],
            ]
        );
            
            $this->add_control(
                'hide_order_counter',
                [
                    'label'     => __( 'Hide Order Counter', 'dethemekit-pro' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .wltotal-sold' => 'display: none !important;',
                    ],
                ]
            );

            $this->add_control(
                'hide_available_counter',
                [
                    'label'     => __( 'Hide Available Counter', 'dethemekit-pro' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .wlcurrent-stock' => 'display: none !important;',
                    ],
                ]
            );

            $this->add_control(
                'order_custom_text',
                [
                    'label' => __( 'Ordered Custom Text', 'dethemekit-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => __( 'Ordered', 'dethemekit-pro' ),
                    'condition' => [
                        'hide_order_counter!' => 'yes',
                    ],
                    'label_block' => true,
                ]
            );

            $this->add_control(
                'available_custom_text',
                [
                    'label' => __( 'Available Custom Text', 'dethemekit-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => __( 'Items available', 'dethemekit-pro' ),
                    'condition' => [
                        'hide_available_counter!' => 'yes',
                    ],
                    'label_block' => true,
                ]
            );

        $this->end_controls_section();

        // Product Action Button
        $this->start_controls_section(
            'dethemekit-products-action-button',
            [
                'label' => esc_html__( 'Action Button Settings', 'dethemekit' ),
            ]
        );
            
            $this->add_control(
                'show_action_button',
                [
                    'label' => __( 'Action Button', 'dethemekit' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'dethemekit' ),
                    'label_off' => __( 'Hide', 'dethemekit' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'action_button_style',
                [
                    'label'   => __( 'Style', 'dethemekit' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => '1',
                    'options' => [
                        '1'   => __( 'Style One', 'dethemekit' ),
                        '2'   => __( 'Style Two', 'dethemekit' ),
                        '3'   => __( 'Style Three', 'dethemekit' ),
                    ],
                    'condition'=>[
                        'show_action_button'=>'yes',
                    ]
                ]
            );

            $this->add_control(
                'action_button_show_on',
                [
                    'label'   => __( 'Show on', 'dethemekit' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'normal',
                    'options' => [
                        'hover'   => __( 'Hover', 'dethemekit' ),
                        'normal'  => __( 'Normal', 'dethemekit' ),
                    ],
                    'condition'=>[
                        'show_action_button'=>'yes',
                    ]
                ]
            );

            $this->add_control(
                'action_button_position',
                [
                    'label'   => __( 'Position', 'dethemekit' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => __( 'Left', 'dethemekit' ),
                            'icon'  => 'eicon-h-align-left',
                        ],
                        'right' => [
                            'title' => __( 'Right', 'dethemekit' ),
                            'icon'  => 'eicon-h-align-right',
                        ],
                        'middle' => [
                            'title' => __( 'Middle', 'dethemekit' ),
                            'icon'  => 'eicon-v-align-middle',
                        ],
                        'bottom' => [
                            'title' => __( 'Bottom', 'dethemekit' ),
                            'icon'  => 'eicon-v-align-bottom',
                        ],
                        'contentbottom' => [
                            'title' => __( 'Content Bottom', 'dethemekit' ),
                            'icon'  => 'eicon-v-align-bottom',
                        ],
                    ],
                    'default'     => is_rtl() ? 'left' : 'right',
                    'toggle'      => false,
                    'condition'=>[
                        'show_action_button'=>'yes',
                    ]
                ]
            );

            $this->add_control(
                'addtocart_button_txt',
                [
                    'label' => __( 'Show Add to Cart Button Text', 'dethemekit' ),
                    'type' => Controls_Manager::SWITCHER,
                ]
            );

        $this->end_controls_section();

        // Product Image Setting
        $this->start_controls_section(
            'dethemekit-products-thumbnails-setting',
            [
                'label' => esc_html__( 'Image Settings', 'dethemekit' ),
            ]
        );

            $this->add_control(
                'thumbnails_style',
                [
                    'label'   => __( 'Thumbnails Style', 'dethemekit' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => '1',
                    'options' => [
                        '1'   => __( 'Single Image', 'dethemekit' ),
                        '2'  => __( 'Image Slider', 'dethemekit' ),
                        '3'  => __( 'Gallery Tab', 'dethemekit' ),
                    ]
                ]
            );

            $this->add_control(
                'image_navigation_bg_color',
                [
                    'label' => __( 'Arrows Color', 'dethemekit' ),
                    'type' => Controls_Manager::COLOR,
                    'default' =>'#444444',
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-image-wrap .ht-product-image .ht-product-image-slider .slick-arrow' => 'color: {{VALUE}} !important;',
                    ],
                    'condition'=>[
                        'thumbnails_style'=>'2',
                    ]
                ]
            );

            $this->add_control(
                'image_dots_normal_bg_color',
                [
                    'label' => __( 'Dots Background Color', 'dethemekit' ),
                    'type' => Controls_Manager::COLOR,
                    'default' =>'#cccccc',
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-image-wrap .ht-product-image .ht-product-image-slider .slick-dots li button' => 'background-color: {{VALUE}} !important;',
                    ],
                    'condition'=>[
                        'thumbnails_style'=>'2',
                    ]
                ]
            );

            $this->add_control(
                'image_dots_hover_bg_color',
                [
                    'label' => __( 'Dots Active Background Color', 'dethemekit' ),
                    'type' => Controls_Manager::COLOR,
                    'condition'=>[
                        'thumbnails_style'=>'2',
                    ],
                    'default' =>'#666666',
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-image-wrap .ht-product-image .ht-product-image-slider .slick-dots li.slick-active button' => 'background-color: {{VALUE}} !important;',
                    ],
                ]
            );

            $this->add_control(
                'image_tab_menu_border_color',
                [
                    'label' => __( 'Border Color', 'dethemekit' ),
                    'type' => Controls_Manager::COLOR,
                    'default' =>'#737373',
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-image-wrap .ht-product-image .ht-product-cus-tab-links li a' => 'border-color: {{VALUE}};',
                    ],
                    'condition'=>[
                        'thumbnails_style'=>'3',
                    ]
                ]
            );

            $this->add_control(
                'image_tab_menu_active_border_color',
                [
                    'label' => __( 'Active Border Color', 'dethemekit' ),
                    'type' => Controls_Manager::COLOR,
                    'default' =>'#ECC87B',
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-image-wrap .ht-product-image .ht-product-cus-tab-links li a.htactive' => 'border-color: {{VALUE}} !important;',
                    ],
                    'condition'=>[
                        'thumbnails_style'=>'3',
                    ]
                ]
            );

        $this->end_controls_section();

        // Product countdown
        $this->start_controls_section(
            'dethemekit-products-countdown-setting',
            [
                'label' => esc_html__( 'Countdown Settings', 'dethemekit' ),
            ]
        );
            
            $this->add_control(
                'show_countdown',
                [
                    'label' => __( 'Show Countdown Timer', 'dethemekit' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'dethemekit' ),
                    'label_off' => __( 'Hide', 'dethemekit' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
            );

            $this->add_control(
                'show_countdown_gutter',
                [
                    'label' => __( 'Gutter', 'dethemekit' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Yes', 'dethemekit' ),
                    'label_off' => __( 'No', 'dethemekit' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'condition' =>[
                        'show_countdown' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'product_countdown_position',
                [
                    'label'   => __( 'Position', 'dethemekit' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => __( 'Left', 'dethemekit' ),
                            'icon'  => 'eicon-h-align-left',
                        ],
                        'right' => [
                            'title' => __( 'Right', 'dethemekit' ),
                            'icon'  => 'eicon-h-align-right',
                        ],
                        'middle' => [
                            'title' => __( 'Middle', 'dethemekit' ),
                            'icon'  => 'eicon-v-align-middle',
                        ],
                        'bottom' => [
                            'title' => __( 'Bottom', 'dethemekit' ),
                            'icon'  => 'eicon-v-align-bottom',
                        ],
                        'contentbottom' => [
                            'title' => __( 'Content Bottom', 'dethemekit' ),
                            'icon'  => 'eicon-v-align-bottom',
                        ],
                    ],
                    'default'     => 'bottom',
                    'toggle'      => false,
                    'label_block' => true,
                    'condition' =>[
                        'show_countdown' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'custom_labels',
                [
                    'label'        => __( 'Custom Label', 'dethemekit' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'condition'   => [
                        'show_countdown' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'customlabel_days',
                [
                    'label'       => __( 'Days', 'dethemekit' ),
                    'type'        => Controls_Manager::TEXT,
                    'placeholder' => __( 'Days', 'dethemekit' ),
                    'condition'   => [
                        'custom_labels!' => '',
                        'show_countdown' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'customlabel_hours',
                [
                    'label'       => __( 'Hours', 'dethemekit' ),
                    'type'        => Controls_Manager::TEXT,
                    'placeholder' => __( 'Hours', 'dethemekit' ),
                    'condition'   => [
                        'custom_labels!' => '',
                        'show_countdown' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'customlabel_minutes',
                [
                    'label'       => __( 'Minutes', 'dethemekit' ),
                    'type'        => Controls_Manager::TEXT,
                    'placeholder' => __( 'Minutes', 'dethemekit' ),
                    'condition'   => [
                        'custom_labels!' => '',
                        'show_countdown' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'customlabel_seconds',
                [
                    'label'       => __( 'Seconds', 'dethemekit' ),
                    'type'        => Controls_Manager::TEXT,
                    'placeholder' => __( 'Seconds', 'dethemekit' ),
                    'condition'   => [
                        'custom_labels!' => '',
                        'show_countdown' => 'yes',
                    ]
                ]
            );

        $this->end_controls_section();

        // Product slider setting
        $this->start_controls_section(
            'dethemekit-products-slider',
            [
                'label' => esc_html__( 'Slider Option', 'dethemekit' ),
                'condition' => [
                    'product_layout_style' => 'slider',
                ]
            ]
        );

            $this->add_control(
                'slitems',
                [
                    'label' => esc_html__( 'Slider Items', 'dethemekit' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 10,
                    'step' => 1,
                    'default' => 3
                ]
            );

            $this->add_control(
                'slarrows',
                [
                    'label' => esc_html__( 'Slider Arrow', 'dethemekit' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'sldots',
                [
                    'label' => esc_html__( 'Slider dots', 'dethemekit' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'no'
                ]
            );

            $this->add_control(
                'slpause_on_hover',
                [
                    'type' => Controls_Manager::SWITCHER,
                    'label_off' => __('No', 'dethemekit'),
                    'label_on' => __('Yes', 'dethemekit'),
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'label' => __('Pause on Hover?', 'dethemekit'),
                ]
            );

            $this->add_control(
                'slautolay',
                [
                    'label' => esc_html__( 'Slider auto play', 'dethemekit' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'separator' => 'before',
                    'default' => 'no'
                ]
            );

            $this->add_control(
                'slautoplay_speed',
                [
                    'label' => __('Autoplay speed', 'dethemekit'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 3000,
                    'condition' => [
                        'slautolay' => 'yes',
                    ]
                ]
            );


            $this->add_control(
                'slanimation_speed',
                [
                    'label' => __('Autoplay animation speed', 'dethemekit'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 300,
                    'condition' => [
                        'slautolay' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'slscroll_columns',
                [
                    'label' => __('Slider item to scroll', 'dethemekit'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 10,
                    'step' => 1,
                    'default' => 3,
                ]
            );

            $this->add_control(
                'heading_tablet',
                [
                    'label' => __( 'Tablet', 'dethemekit' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );

            $this->add_control(
                'sltablet_display_columns',
                [
                    'label' => __('Slider Items', 'dethemekit'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 8,
                    'step' => 1,
                    'default' => 2,
                ]
            );

            $this->add_control(
                'sltablet_scroll_columns',
                [
                    'label' => __('Slider item to scroll', 'dethemekit'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 8,
                    'step' => 1,
                    'default' => 2,
                ]
            );

            $this->add_control(
                'sltablet_width',
                [
                    'label' => __('Tablet Resolution', 'dethemekit'),
                    'description' => __('The resolution to tablet.', 'dethemekit'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 750,
                ]
            );

            $this->add_control(
                'heading_mobile',
                [
                    'label' => __( 'Mobile Phone', 'dethemekit' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );

            $this->add_control(
                'slmobile_display_columns',
                [
                    'label' => __('Slider Items', 'dethemekit'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 4,
                    'step' => 1,
                    'default' => 1,
                ]
            );

            $this->add_control(
                'slmobile_scroll_columns',
                [
                    'label' => __('Slider item to scroll', 'dethemekit'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 4,
                    'step' => 1,
                    'default' => 1,
                ]
            );

            $this->add_control(
                'slmobile_width',
                [
                    'label' => __('Mobile Resolution', 'dethemekit'),
                    'description' => __('The resolution to mobile.', 'dethemekit'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 480,
                ]
            );

        $this->end_controls_section(); // Slider Option end

        // Style Default tab section
        $this->start_controls_section(
            'universal_product_style_section',
            [
                'label' => __( 'Style', 'dethemekit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_responsive_control(
                'product_inner_padding',
                [
                    'label' => __( 'Padding', 'dethemekit' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce div.product.mb-30' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_inner_margin',
                [
                    'label' => __( 'Margin', 'dethemekit' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce div.product.mb-30' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'product_inner_border_color',
                [
                    'label' => __( 'Border Color', 'dethemekit' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#f1f1f1',
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner' => 'border-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'product_inner_box_shadow',
                    'label' => __( 'Hover Box Shadow', 'dethemekit' ),
                    'selector' => '{{WRAPPER}} .ht-products .ht-product .ht-product-inner:hover',
                ]
            );

            $this->add_control(
                'product_content_area_heading',
                [
                    'label' => __( 'Content area', 'dethemekit' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'product_content_area_padding',
                [
                    'label' => __( 'Padding', 'dethemekit' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'product_content_area_bg_color',
                [
                    'label' => __( 'Background Color', 'dethemekit' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => dethemekit_get_option( 'content_area_bg','dethemekit_style_tabs', '#ffffff' ),
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'product_content_area_border',
                    'label' => __( 'Border', 'dethemekit' ),
                    'selector' => '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content',
                ]
            );

            $this->add_control(
                'product_badge_heading',
                [
                    'label' => __( 'Product Badge', 'dethemekit' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'product_badge_color',
                [
                    'label' => __( 'Badge Color', 'dethemekit' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => dethemekit_get_option( 'badge_color','dethemekit_style_tabs', '#444444' ),
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-image-wrap .ht-product-label' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'product_badge_typography',
                    'scheme' => Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-image-wrap .ht-product-label',
                ]
            );

            // Product Category
            $this->add_control(
                'product_category_heading',
                [
                    'label' => __( 'Product Category', 'dethemekit' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'product_category_typography',
                    'scheme' => Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-categories a',
                ]
            );

            $this->add_control(
                'product_category_color',
                [
                    'label' => __( 'Category Color', 'dethemekit' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => dethemekit_get_option( 'category_color','dethemekit_style_tabs', '#444444' ),
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-categories a' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-categories::before' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'product_category_hover_color',
                [
                    'label' => __( 'Category Hover Color', 'dethemekit' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => dethemekit_get_option( 'category_hover_color','dethemekit_style_tabs', '#dc9a0e' ),
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-categories a:hover' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_category_margin',
                [
                    'label' => __( 'Margin', 'dethemekit' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-categories' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            // Product Title
            $this->add_control(
                'product_title_heading',
                [
                    'label' => __( 'Product Title', 'dethemekit' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'product_title_typography',
                    'scheme' => Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-title a',
                ]
            );

            $this->add_control(
                'product_title_color',
                [
                    'label' => __( 'Title Color', 'dethemekit' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => dethemekit_get_option( 'title_color','dethemekit_style_tabs', '#444444' ),
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-title a' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'product_title_hover_color',
                [
                    'label' => __( 'Title Hover Color', 'dethemekit' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => dethemekit_get_option( 'title_hover_color','dethemekit_style_tabs', '#dc9a0e' ),
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-title a:hover' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_title_margin',
                [
                    'label' => __( 'Margin', 'dethemekit' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            // Product Price
            $this->add_control(
                'product_price_heading',
                [
                    'label' => __( 'Product Price', 'dethemekit' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'product_sale_price_color',
                [
                    'label' => __( 'Sale Price Color', 'dethemekit' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => dethemekit_get_option( 'sale_price_color','dethemekit_style_tabs', '#444444' ),
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-price span' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'product_sale_price_typography',
                    'scheme' => Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-price span',
                ]
            );

            $this->add_control(
                'product_regular_price_color',
                [
                    'label' => __( 'Regular Price Color', 'dethemekit' ),
                    'type' => Controls_Manager::COLOR,
                    'separator' => 'before',
                    'default' => dethemekit_get_option( 'regular_price_color','dethemekit_style_tabs', '#444444' ),
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-price span del span,{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-price span del' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'product_regular_price_typography',
                    'scheme' => Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-price span del span',
                ]
            );

            $this->add_responsive_control(
                'product_price_margin',
                [
                    'label' => __( 'Margin', 'dethemekit' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            // Product Rating
            $this->add_control(
                'product_rating_heading',
                [
                    'label' => __( 'Product Rating', 'dethemekit' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'product_rating_color',
                [
                    'label' => __( 'Empty Rating Color', 'dethemekit' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => dethemekit_get_option( 'empty_rating_color','dethemekit_style_tabs', '#aaaaaa' ),
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-ratting-wrap .ht-product-ratting .ht-product-user-ratting i.empty' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'product_rating_give_color',
                [
                    'label' => __( 'Rating Color', 'dethemekit' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => dethemekit_get_option( 'rating_color','dethemekit_style_tabs', '#dc9a0e' ),
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-ratting-wrap .ht-product-ratting .ht-product-user-ratting i' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_rating_margin',
                [
                    'label' => __( 'Margin', 'dethemekit' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-content .ht-product-content-inner .ht-product-ratting-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section(); // Style Default End

        // Style Action Button tab section
        $this->start_controls_section(
            'universal_product_action_button_style_section',
            [
                'label' => __( 'Action Button Style', 'dethemekit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'product_action_button_background_color',
                    'label' => __( 'Background', 'dethemekit' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-image-wrap .ht-product-action ul',
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'product_action_button_box_shadow',
                    'label' => __( 'Box Shadow', 'dethemekit' ),
                    'selector' => '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-image-wrap .ht-product-action ul',
                ]
            );

            $this->add_control(
                'product_tooltip_heading',
                [
                    'label' => __( 'Tooltip', 'dethemekit' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

                $this->add_control(
                    'product_tooltip_color',
                    [
                        'label' => __( 'Tooltip Color', 'dethemekit' ),
                        'type' => Controls_Manager::COLOR,
                        'default' => dethemekit_get_option( 'tooltip_color','dethemekit_style_tabs', '#ffffff' ),
                        'selectors' => [
                            '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-image-wrap .ht-product-action ul li a .ht-product-action-tooltip,{{WRAPPER}} span.dethemekit-tip' => 'color: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_group_control(
                    Group_Control_Background::get_type(),
                    [
                        'name' => 'product_action_button_tooltip_background_color',
                        'label' => __( 'Background', 'dethemekit' ),
                        'types' => [ 'classic', 'gradient' ],
                        'selector' => '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-image-wrap .ht-product-action ul li a .ht-product-action-tooltip,{{WRAPPER}} span.dethemekit-tip',
                    ]
                );

            $this->start_controls_tabs('product_action_button_style_tabs');

                // Normal
                $this->start_controls_tab(
                    'product_action_button_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'dethemekit' ),
                    ]
                );
                    
                    $this->add_control(
                        'product_action_button_normal_color',
                        [
                            'label' => __( 'Color', 'dethemekit' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => dethemekit_get_option( 'btn_color','dethemekit_style_tabs', '#000000' ),
                            'selectors' => [
                                '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-image-wrap .ht-product-action ul li a' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'product_action_button_font_size',
                        [
                            'label' => __( 'Font Size', 'dethemekit' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 200,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'default' => [
                                'unit' => 'px',
                                'size' => 20,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-action ul li a i' => 'font-size: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .dethemekit-compare.compare::before,{{WRAPPER}} .ht-product-action ul li.dethemekit-cart a::before' => 'font-size: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'product_action_button_line_height',
                        [
                            'label' => __( 'Line Height', 'dethemekit' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 200,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'default' => [
                                'unit' => 'px',
                                'size' => 30,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-action ul li a i' => 'line-height: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .dethemekit-compare.compare::before,{{WRAPPER}} .ht-product-action ul li.dethemekit-cart a::before' => 'line-height: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'product_action_button_normal_background_color',
                            'label' => __( 'Background', 'dethemekit' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-image-wrap .ht-product-action ul li',
                        ]
                    );

                    $this->add_responsive_control(
                        'product_action_button_normal_padding',
                        [
                            'label' => __( 'Padding', 'dethemekit' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-image-wrap .ht-product-action ul li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'product_action_button_normal_margin',
                        [
                            'label' => __( 'Margin', 'dethemekit' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-image-wrap .ht-product-action ul li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'product_action_button_normal_button_border',
                            'label' => __( 'Border', 'dethemekit' ),
                            'selector' => '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-image-wrap .ht-product-action ul li',
                        ]
                    );

                    $this->add_responsive_control(
                        'product_action_button_border_radius',
                        [
                            'label' => __( 'Border Radius', 'dethemekit' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-image-wrap .ht-product-action ul li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'product_action_button_width',
                        [
                            'label' => __( 'Width', 'dethemekit' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 200,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'default' => [
                                'unit' => 'px',
                                'size' => 30,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-action ul li a' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'product_action_button_height',
                        [
                            'label' => __( 'Height', 'dethemekit' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 200,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'default' => [
                                'unit' => 'px',
                                'size' => 30,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-action ul li a' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                // Hover
                $this->start_controls_tab(
                    'product_action_button_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'dethemekit' ),
                    ]
                );
                    
                    $this->add_control(
                        'product_action_button_hover_color',
                        [
                            'label' => __( 'Color', 'dethemekit' ),
                            'type' => Controls_Manager::COLOR,
                            'default' => dethemekit_get_option( 'btn_hover_color','dethemekit_style_tabs', '#dc9a0e' ),
                            'selectors' => [
                                '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-image-wrap .ht-product-action ul li:hover a' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .ht-product-action .yith-wcwl-wishlistaddedbrowse a, .ht-product-action .yith-wcwl-wishlistexistsbrowse a' => 'color: {{VALUE}} !important;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'product_action_button_hover_background_color',
                            'label' => __( 'Background', 'dethemekit' ),
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-image-wrap .ht-product-action ul li:hover',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'product_action_button_hover_button_border',
                            'label' => __( 'Border', 'dethemekit' ),
                            'selector' => '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-image-wrap .ht-product-action ul li:hover',
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

        // Style Countdown tab section
        $this->start_controls_section(
            'universal_product_counter_style_section',
            [
                'label' => __( 'Offer Price Countdown', 'dethemekit' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_countdown'=>'yes',
                ]
            ]
        );

            $this->add_control(
                'product_counter_color',
                [
                    'label' => __( 'Color', 'dethemekit' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => dethemekit_get_option( 'counter_color','dethemekit_style_tabs', '#ffffff' ),
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-countdown-wrap .ht-product-countdown .cd-single .cd-single-inner h3' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-countdown-wrap .ht-product-countdown .cd-single .cd-single-inner p' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'product_counter_background_color',
                    'label' => __( 'Counter Background', 'dethemekit' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-countdown-wrap .ht-product-countdown .cd-single .cd-single-inner,{{WRAPPER}} .ht-products .ht-product.ht-product-countdown-fill .ht-product-inner .ht-product-countdown-wrap .ht-product-countdown',
                ]
            );

            $this->add_responsive_control(
                'product_counter_space_between',
                [
                    'label' => __( 'Space', 'dethemekit' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-products .ht-product .ht-product-inner .ht-product-countdown-wrap .ht-product-countdown .cd-single' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            
        $this->end_controls_section();

        // Slider Button style
        $this->start_controls_section(
            'products-slider-controller-style',
            [
                'label' => esc_html__( 'Slider Controller Style', 'dethemekit' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'product_layout_style' => 'slider',
                ]
            ]
        );

            $this->start_controls_tabs('product_sliderbtn_style_tabs');

                // Slider Button style Normal
                $this->start_controls_tab(
                    'product_sliderbtn_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'dethemekit' ),
                    ]
                );

                    $this->add_control(
                        'button_style_heading',
                        [
                            'label' => __( 'Navigation Arrow', 'dethemekit' ),
                            'type' => Controls_Manager::HEADING,
                        ]
                    );

                    $this->add_responsive_control(
                        'nvigation_position',
                        [
                            'label' => __( 'Position', 'dethemekit' ),
                            'type' => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 1000,
                                    'step' => 5,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'default' => [
                                'unit' => '%',
                                'size' => 50,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-arrow' => 'top: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_color',
                        [
                            'label' => __( 'Color', 'dethemekit' ),
                            'type' => Controls_Manager::COLOR,
                            'default' =>'#dddddd',
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-arrow' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_bg_color',
                        [
                            'label' => __( 'Background Color', 'dethemekit' ),
                            'type' => Controls_Manager::COLOR,
                            'default' =>'#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-arrow' => 'background-color: {{VALUE}} !important;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'button_border',
                            'label' => __( 'Border', 'dethemekit' ),
                            'selector' => '{{WRAPPER}} .product-slider .slick-arrow',
                        ]
                    );

                    $this->add_responsive_control(
                        'button_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'dethemekit' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-arrow' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'button_padding',
                        [
                            'label' => __( 'Padding', 'dethemekit' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_style_dots_heading',
                        [
                            'label' => __( 'Navigation Dots', 'dethemekit' ),
                            'type' => Controls_Manager::HEADING,
                        ]
                    );

                        $this->add_responsive_control(
                            'dots_position',
                            [
                                'label' => __( 'Position', 'dethemekit' ),
                                'type' => Controls_Manager::SLIDER,
                                'size_units' => [ 'px', '%' ],
                                'range' => [
                                    'px' => [
                                        'min' => 0,
                                        'max' => 1000,
                                        'step' => 5,
                                    ],
                                    '%' => [
                                        'min' => 0,
                                        'max' => 100,
                                    ],
                                ],
                                'default' => [
                                    'unit' => '%',
                                    'size' => 50,
                                ],
                                'selectors' => [
                                    '{{WRAPPER}} .product-slider .slick-dots' => 'left: {{SIZE}}{{UNIT}};',
                                ],
                            ]
                        );

                        $this->add_control(
                            'dots_bg_color',
                            [
                                'label' => __( 'Background Color', 'dethemekit' ),
                                'type' => Controls_Manager::COLOR,
                                'default' =>'#ffffff',
                                'selectors' => [
                                    '{{WRAPPER}} .product-slider .slick-dots li button' => 'background-color: {{VALUE}} !important;',
                                ],
                            ]
                        );

                        $this->add_group_control(
                            Group_Control_Border::get_type(),
                            [
                                'name' => 'dots_border',
                                'label' => __( 'Border', 'dethemekit' ),
                                'selector' => '{{WRAPPER}} .product-slider .slick-dots li button',
                            ]
                        );

                        $this->add_responsive_control(
                            'dots_border_radius',
                            [
                                'label' => esc_html__( 'Border Radius', 'dethemekit' ),
                                'type' => Controls_Manager::DIMENSIONS,
                                'selectors' => [
                                    '{{WRAPPER}} .product-slider .slick-dots li button' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                                ],
                            ]
                        );

                $this->end_controls_tab();// Normal button style end

                // Button style Hover
                $this->start_controls_tab(
                    'product_sliderbtn_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'dethemekit' ),
                    ]
                );

                    $this->add_control(
                        'button_style_arrow_heading',
                        [
                            'label' => __( 'Navigation', 'dethemekit' ),
                            'type' => Controls_Manager::HEADING,
                        ]
                    );

                    $this->add_control(
                        'button_hover_color',
                        [
                            'label' => __( 'Color', 'dethemekit' ),
                            'type' => Controls_Manager::COLOR,
                            'default' =>'#23252a',
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-arrow:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'button_hover_bg_color',
                        [
                            'label' => __( 'Background', 'dethemekit' ),
                            'type' => Controls_Manager::COLOR,
                            'default' =>'#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-arrow:hover' => 'background-color: {{VALUE}} !important;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'button_hover_border',
                            'label' => __( 'Border', 'dethemekit' ),
                            'selector' => '{{WRAPPER}} .product-slider .slick-arrow:hover',
                        ]
                    );

                    $this->add_responsive_control(
                        'button_hover_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'dethemekit' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .product-slider .slick-arrow:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );


                    $this->add_control(
                        'button_style_dotshov_heading',
                        [
                            'label' => __( 'Navigation Dots', 'dethemekit' ),
                            'type' => Controls_Manager::HEADING,
                        ]
                    );

                        $this->add_control(
                            'dots_hover_bg_color',
                            [
                                'label' => __( 'Background Color', 'dethemekit' ),
                                'type' => Controls_Manager::COLOR,
                                'default' =>'#282828',
                                'selectors' => [
                                    '{{WRAPPER}} .product-slider .slick-dots li button:hover' => 'background-color: {{VALUE}} !important;',
                                    '{{WRAPPER}} .product-slider .slick-dots li.slick-active button' => 'background-color: {{VALUE}} !important;',
                                ],
                            ]
                        );

                        $this->add_group_control(
                            Group_Control_Border::get_type(),
                            [
                                'name' => 'dots_border_hover',
                                'label' => __( 'Border', 'dethemekit' ),
                                'selector' => '{{WRAPPER}} .product-slider .slick-dots li button:hover',
                            ]
                        );

                        $this->add_responsive_control(
                            'dots_border_radius_hover',
                            [
                                'label' => esc_html__( 'Border Radius', 'dethemekit' ),
                                'type' => Controls_Manager::DIMENSIONS,
                                'selectors' => [
                                    '{{WRAPPER}} .product-slider .slick-dots li button:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                                ],
                            ]
                        );

                $this->end_controls_tab();// Hover button style end

            $this->end_controls_tabs();

        $this->end_controls_section(); // Tab option end

        // Product Tab menu setting
        $this->start_controls_section(
            'dethemekit-products-tab-menu',
            [
                'label' => esc_html__( 'Tab Menu Style', 'dethemekit' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'product_layout_style' => 'tab',
                ]
            ]
        );

            $this->add_responsive_control(
                'dethemekit-tab-menu-align',
                [
                    'label' => __( 'Alignment', 'dethemekit' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => __( 'Left', 'dethemekit' ),
                            'icon' => 'fa fa-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'dethemekit' ),
                            'icon' => 'fa fa-align-center',
                        ],
                        'right' => [
                            'title' => __( 'Right', 'dethemekit' ),
                            'icon' => 'fa fa-align-right',
                        ],
                        'justify' => [
                            'title' => __( 'Justified', 'dethemekit' ),
                            'icon' => 'fa fa-align-justify',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .product-tab-list.ht-text-center' => 'text-align: {{VALUE}};',
                    ],
                    'default' => 'center',
                    'separator' =>'after',
                ]
            );

            $this->add_responsive_control(
                'product_tab_menu_area_margin',
                [
                    'label' => __( 'Tab Menu Area Margin', 'dethemekit' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-tab-menus' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->start_controls_tabs('product_tab_style_tabs');

                // Tab menu style Normal
                $this->start_controls_tab(
                    'product_tab_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'dethemekit' ),
                    ]
                );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'tabmenutypography',
                            'scheme' => Typography::TYPOGRAPHY_1,
                            'selector' => '{{WRAPPER}} .ht-tab-menus li a',
                        ]
                    );

                    $this->add_control(
                        'tab_menu_color',
                        [
                            'label' => __( 'Color', 'dethemekit' ),
                            'type' => Controls_Manager::COLOR,
                            'default' =>'#23252a',
                            'selectors' => [
                                '{{WRAPPER}} .ht-tab-menus li a' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'product_tab_menu_bg_color',
                        [
                            'label' => __( 'Product tab menu background', 'dethemekit' ),
                            'type' => Controls_Manager::COLOR,
                            'default' =>'#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .ht-tab-menus li a' => 'background-color: {{VALUE}} !important;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'tabmenu_border',
                            'label' => __( 'Border', 'dethemekit' ),
                            'selector' => '{{WRAPPER}} .ht-tab-menus li a',
                        ]
                    );

                    $this->add_responsive_control(
                        'tabmenu_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'dethemekit' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .ht-tab-menus li a' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'product_tab_menu_padding',
                        [
                            'label' => __( 'Tab Menu padding', 'dethemekit' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .ht-tab-menus li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        'product_tab_menu_margin',
                        [
                            'label' => __( 'Tab Menu margin', 'dethemekit' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors' => [
                                '{{WRAPPER}} .ht-tab-menus li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                            ],
                        ]
                    );

                $this->end_controls_tab();// Normal tab menu style end

                // Tab menu style Hover
                $this->start_controls_tab(
                    'product_tab_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'dethemekit' ),
                    ]
                );


                    $this->add_control(
                        'tab_menu_hover_color',
                        [
                            'label' => __( 'Color', 'dethemekit' ),
                            'type' => Controls_Manager::COLOR,
                            'default' =>'#23252a',
                            'selectors' => [
                                '{{WRAPPER}} .ht-tab-menus li a:hover' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .ht-tab-menus li a.htactive' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'product_tab_menu_hover_bg_color',
                        [
                            'label' => __( 'Product tab menu background', 'dethemekit' ),
                            'type' => Controls_Manager::COLOR,
                            'default' =>'#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .ht-tab-menus li a:hover' => 'background-color: {{VALUE}} !important;',
                                '{{WRAPPER}} .ht-tab-menus li a.htactive' => 'background-color: {{VALUE}} !important;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'tabmenu_hover_border',
                            'label' => __( 'Border', 'dethemekit' ),
                            'selector' => '{{WRAPPER}} .ht-tab-menus li a:hover',
                            'selector' => '{{WRAPPER}} .ht-tab-menus li a.htactive',
                        ]
                    );

                    $this->add_responsive_control(
                        'tabmenu_hover_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'dethemekit' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .ht-tab-menus li a:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                                '{{WRAPPER}} .ht-tab-menus li a.htactive' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );

                $this->end_controls_tab();// Hover tab menu style end

            $this->end_controls_tabs();

        $this->end_controls_section(); // Tab option end

        // Progressbar Style
        $this->start_controls_section(
            'section_stock_progressbar_style',
            [
                'label' => __( 'Stock Progressbar', 'dethemekit-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'stock_progress_bar'=>'yes',
                ],
            ]
        );

            $this->add_control(
                'progressbar_heading',
                [
                    'label' => __( 'Progressbar', 'dethemekit-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'progressbar_height',
                [
                    'label' => __( 'Height', 'dethemekit-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 10,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .dethemekit-stock-progress-bar .wlprogress-area' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'progressbar_bg_color',
                [
                    'label' => __( 'Background Color', 'dethemekit-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .dethemekit-stock-progress-bar .wlprogress-area' => 'background-color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'progressbar_active_bg_color',
                [
                    'label' => __( 'Sell Progress Background Color', 'dethemekit-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .dethemekit-stock-progress-bar .wlprogress-bar' => 'background-color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'progressbar_area',
                [
                    'label' => __( 'Margin', 'dethemekit-pro' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .dethemekit-stock-progress-bar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'progressbar_order_heading',
                [
                    'label' => __( 'Order & Ability Counter', 'dethemekit-pro' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'order_ability_typography',
                    'label' => __( 'Typography', 'dethemekit-pro' ),
                    'selector' => '{{WRAPPER}} .dethemekit-stock-progress-bar .wlstock-info',
                ]
            );

            $this->add_control(
                'order_ability_color',
                [
                    'label' => __( 'Label Color', 'dethemekit-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .dethemekit-stock-progress-bar .wlstock-info' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'counter_number_color',
                [
                    'label' => __( 'Counter Number Color', 'dethemekit-pro' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .dethemekit-stock-progress-bar .wlstock-info span' => 'color: {{VALUE}}',
                    ],
                ]
            );

        $this->end_controls_section();


    }

    protected function render( $instance = [] ) {

        $settings           = $this->get_settings_for_display();
        $product_type       = $this->get_settings_for_display('dethemekit_product_grid_product_filter');
        $per_page           = $this->get_settings_for_display('dethemekit_product_grid_products_count');
        $custom_order_ck    = $this->get_settings_for_display('dethemekit_custom_order');
        $orderby            = $this->get_settings_for_display('orderby');
        $order              = $this->get_settings_for_display('order');
        $tabuniqid          = $this->get_id();
        $columns            = $this->get_settings_for_display('dethemekit_product_grid_column');

        // Stock Progress Bar data
        $order_text     = $settings['order_custom_text'] ? $settings['order_custom_text'] : esc_html__('Ordered:','dethemekit-pro');
        $available_text = $settings['available_custom_text'] ? $settings['available_custom_text'] : esc_html__( 'Items available:','dethemekit-pro' );

        // Query Argument
        $args = array(
            'post_type'             => 'product',
            'post_status'           => 'publish',
            'ignore_sticky_posts'   => 1,
            'posts_per_page'        => $per_page,
        );

        switch( $product_type ){

            case 'sale':
                $args['post__in'] = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
            break;

            case 'featured':
                $args['tax_query'][] = array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'featured',
                    'operator' => 'IN',
                );
            break;

            case 'best_selling':
                $args['meta_key']   = 'total_sales';
                $args['orderby']    = 'meta_value_num';
                $args['order']      = 'desc';
            break;

            case 'top_rated': 
                $args['meta_key']   = '_wc_average_rating';
                $args['orderby']    = 'meta_value_num';
                $args['order']      = 'desc';          
            break;

            case 'mixed_order':
                $args['orderby']    = 'rand';
            break;

            case 'show_byid':
                $args['post__in'] = $settings['dethemekit_product_id'];
            break;

            case 'show_byid_manually':
                $args['post__in'] = explode( ',', $settings['dethemekit_product_ids_manually'] );
            break;

            default: /* Recent */
                $args['orderby']    = 'date';
                $args['order']      = 'desc';
            break;
        }

        // Custom Order
        if( $custom_order_ck == 'yes' ){
            $args['orderby'] = $orderby;
            $args['order'] = $order;
        }

        $get_product_categories = $settings['dethemekit_product_grid_categories']; // get custom field value
        $product_cats = str_replace(' ', '', $get_product_categories);
        if ( "0" != $get_product_categories) {
            if( is_array($product_cats) && count($product_cats) > 0 ){
                $field_name = is_numeric($product_cats[0])?'term_id':'slug';
                $args['tax_query'][] = array(
                    array(
                        'taxonomy' => 'product_cat',
                        'terms' => $product_cats,
                        'field' => $field_name,
                        'include_children' => false
                    )
                );
            }
        }

        $products = new \WP_Query( $args );

        // Calculate Column
        $collumval = ( $settings['product_layout_style'] == 'slider' ) ? 'ht-product mb-30 product ht-col-xs-12' : 'ht-product ht-col-lg-4 ht-col-md-6 ht-col-sm-6 ht-col-xs-12 mb-30 product';
        if( $columns !='' ){
            if( $columns == 5 ){
                $collumval = 'ht-product cus-col-5 ht-col-md-6 ht-col-sm-6 ht-col-xs-12 mb-30 product';
            }else{
                $colwidth = round( 12 / $columns );
                $collumval = 'ht-product ht-col-lg-'.$colwidth.' ht-col-md-6 ht-col-sm-6 ht-col-xs-12 mb-30 product';
            }
        }

        // Action Button Style
        if( $settings['action_button_style'] == 2 ){
            $collumval .= ' ht-product-action-style-2';
        }elseif( $settings['action_button_style'] == 3 ){
            $collumval .= ' ht-product-action-style-2 ht-product-action-round';
        }else{
            $collumval = $collumval;
        }

        // Position Action Button
        if( $settings['action_button_position'] == 'right' ){
            $collumval .= ' ht-product-action-right';
        }elseif( $settings['action_button_position'] == 'bottom' ){
            $collumval .= ' ht-product-action-bottom';
        }elseif( $settings['action_button_position'] == 'middle' ){
            $collumval .= ' ht-product-action-middle';
        }elseif( $settings['action_button_position'] == 'contentbottom' ){
            $collumval .= ' ht-product-action-bottom-content';
        }else{
            $collumval = $collumval;
        }

        // Show Action
        if( $settings['action_button_show_on'] == 'hover' ){
            $collumval .= ' ht-product-action-on-hover';
        }

        // Content Style
        if( $settings['product_content_style'] == 2 ){
            $collumval .= ' ht-product-category-right-bottom';
        }elseif( $settings['product_content_style'] == 3 ){
            $collumval .= ' ht-product-ratting-top-right';
        }elseif( $settings['product_content_style'] == 4 ){
            $collumval .= ' ht-product-content-allcenter';
        }else{
            $collumval = $collumval;
        }

        // Position countdown
        if( $settings['product_countdown_position'] == 'left' ){
            $collumval .= ' ht-product-countdown-left';
        }elseif( $settings['product_countdown_position'] == 'right' ){
            $collumval .= ' ht-product-countdown-right';
        }elseif( $settings['product_countdown_position'] == 'middle' ){
            $collumval .= ' ht-product-countdown-middle';
        }elseif( $settings['product_countdown_position'] == 'bottom' ){
            $collumval .= ' ht-product-countdown-bottom';
        }elseif( $settings['product_countdown_position'] == 'contentbottom' ){
            $collumval .= ' ht-product-countdown-content-bottom';
        }else{
            $collumval = $collumval;
        }

        // Countdown Gutter 
        if( $settings['show_countdown_gutter'] != 'yes' ){
           $collumval .= ' ht-product-countdown-fill'; 
        }

        // Countdown Custom Label
        if( $settings['show_countdown'] == 'yes' ){
            $data_customlavel = [];
            $data_customlavel['daytxt'] = ! empty( $settings['customlabel_days'] ) ? $settings['customlabel_days'] : 'Days';
            $data_customlavel['hourtxt'] = ! empty( $settings['customlabel_hours'] ) ? $settings['customlabel_hours'] : 'Hours';
            $data_customlavel['minutestxt'] = ! empty( $settings['customlabel_minutes'] ) ? $settings['customlabel_minutes'] : 'Min';
            $data_customlavel['secondstxt'] = ! empty( $settings['customlabel_seconds'] ) ? $settings['customlabel_seconds'] : 'Sec';
        }

        // Slider Options
        $is_rtl = is_rtl();
        $direction = $is_rtl ? 'rtl' : 'ltr';
        $slider_settings = [
            'arrows' => ('yes' === $settings['slarrows']),
            'dots' => ('yes' === $settings['sldots']),
            'autoplay' => ('yes' === $settings['slautolay']),
            'autoplay_speed' => absint($settings['slautoplay_speed']),
            'animation_speed' => absint($settings['slanimation_speed']),
            'pause_on_hover' => ('yes' === $settings['slpause_on_hover']),
            'rtl' => $is_rtl,
        ];

        $slider_responsive_settings = [
            'product_items' => $settings['slitems'],
            'scroll_columns' => $settings['slscroll_columns'],
            'tablet_width' => $settings['sltablet_width'],
            'tablet_display_columns' => $settings['sltablet_display_columns'],
            'tablet_scroll_columns' => $settings['sltablet_scroll_columns'],
            'mobile_width' => $settings['slmobile_width'],
            'mobile_display_columns' => $settings['slmobile_display_columns'],
            'mobile_scroll_columns' => $settings['slmobile_scroll_columns'],

        ];
        $slider_settings = array_merge( $slider_settings, $slider_responsive_settings );


        // Action Button
        $this->add_render_attribute( 'action_btn_attr', 'class', 'dethemekit-action-btn-area' );

        if( $settings['addtocart_button_txt'] == 'yes' ){
            $this->add_render_attribute( 'action_btn_attr', 'class', 'dethemekit-btn-text-cart' );
        }

        ?>
            <?php if ( $settings['product_layout_style'] == 'tab' ) { ?>
                <div class="product-tab-list ht-text-center">
                    <ul class="ht-tab-menus">
                        <?php
                            $m=0;
                            if( is_array( $product_cats ) && count( $product_cats ) > 0 ){

                                // Category retrive
                                $catargs = array(
                                    'orderby'    => 'name',
                                    'order'      => 'ASC',
                                    'hide_empty' => true,
                                    'slug'       => $product_cats,
                                );
                                $prod_categories = get_terms( 'product_cat', $catargs );

                                foreach( $prod_categories as $prod_cats ){
                                    $m++;

                                    $field_name = is_numeric( $product_cats[0] ) ? 'term_id' : 'slug';
                                    $args['tax_query'] = array(
                                        array(
                                            'taxonomy' => 'product_cat',
                                            'terms' => $prod_cats,
                                            'field' => $field_name,
                                            'include_children' => false
                                        ),
                                    );
                                    if( 'featured' == $product_type ){
                                        $args['tax_query'][] = array(
                                            'taxonomy' => 'product_visibility',
                                            'field'    => 'name',
                                            'terms'    => 'featured',
                                            'operator' => 'IN',
                                        );
                                    }
                                    $fetchproduct = new \WP_Query( $args );

                                    if( $fetchproduct->have_posts() ){
                                        ?>
                                            <li><a class="<?php if($m==1){ echo 'htactive';}?>" href="#dethemekittab<?php echo $tabuniqid.esc_attr($m);?>">
                                                <?php echo esc_attr( $prod_cats->name,'dethemekit' );?>
                                            </a></li>
                                        <?php
                                    }
                                }
                            }
                        ?>
                    </ul>
                </div>
            <?php }; ?>

            <?php if( is_array( $product_cats ) && (count( $product_cats ) > 0) && ( $settings['product_layout_style'] == 'tab' ) ): ?>
                <div class="ht-products woocommerce">
                    
                    <?php
                    $z=0;
                    $tabcatargs = array(
                        'orderby'    => 'name',
                        'order'      => 'ASC',
                        'hide_empty' => true,
                        'slug'       => $product_cats,
                    );
                    $tabcat_fach = get_terms( 'product_cat', $tabcatargs );
                    foreach( $tabcat_fach as $cats ):
                        $z++;
                        $field_name = is_numeric( $product_cats[0] ) ? 'term_id' : 'slug';
                        $args['tax_query'] = array(
                            array(
                                'taxonomy' => 'product_cat',
                                'terms' => $cats,
                                'field' => $field_name,
                                'include_children' => false
                            ),
                        );
                        if( 'featured' == $product_type ){
                            $args['tax_query'][] = array(
                                'taxonomy' => 'product_visibility',
                                'field'    => 'name',
                                'terms'    => 'featured',
                                'operator' => 'IN',
                            );
                        }
                        $products = new \WP_Query( $args );

                        if( $products->have_posts() ):
                    ?>
                        <div class="ht-tab-pane <?php if( $z==1 ){ echo 'htactive'; } ?>" id="<?php echo 'dethemekittab'.$tabuniqid.$z;?>">
                            <div class="ht-row">

                                <?php
                                while( $products->have_posts() ): $products->the_post();

                                    // Sale Schedule
                                    $offer_start_date_timestamp = get_post_meta( get_the_ID(), '_sale_price_dates_from', true );
                                    $offer_start_date = $offer_start_date_timestamp ? date_i18n( 'Y/m/d', $offer_start_date_timestamp ) : '';
                                    $offer_end_date_timestamp = get_post_meta( get_the_ID(), '_sale_price_dates_to', true );
                                    $offer_end_date = $offer_end_date_timestamp ? date_i18n( 'Y/m/d', $offer_end_date_timestamp ) : '';

                                    // Gallery Image
                                    global $product;
                                    $gallery_images_ids = $product->get_gallery_image_ids() ? $product->get_gallery_image_ids() : array();
                                    if ( has_post_thumbnail() ){
                                        array_unshift( $gallery_images_ids, $product->get_image_id() );
                                    }
                                    
                                ?>

                                    <!--Product Start-->
                                    <div <?php wc_product_class( $collumval ); ?>>
                                        <div class="ht-product-inner">

                                            <div class="ht-product-image-wrap">
                                                <?php
                                                    if( class_exists('WooCommerce') ){ 
                                                        dethemekit_custom_product_badge(); 
                                                        dethemekit_sale_flash();
                                                    }
                                                ?>
                                                <div class="ht-product-image">
                                                    <?php  if( $settings['thumbnails_style'] == 2 && $gallery_images_ids ): ?>
                                                        <div class="ht-product-image-slider ht-product-image-thumbnaisl-<?php echo $tabuniqid; ?>">
                                                            <?php
                                                                foreach ( $gallery_images_ids as $gallery_attachment_id ) {
                                                                    echo '<a href="'.esc_url( get_the_permalink() ).'" class="item">'.wp_get_attachment_image( $gallery_attachment_id, 'woocommerce_thumbnail' ).'</a>';
                                                                }
                                                            ?>
                                                        </div>

                                                    <?php elseif( $settings['thumbnails_style'] == 3 && $gallery_images_ids ) : $tabactive = ''; ?>
                                                        <div class="ht-product-cus-tab">
                                                            <?php
                                                                $i = 0;
                                                                foreach ( $gallery_images_ids as $gallery_attachment_id ) {
                                                                    $i++;
                                                                    if( $i == 1 ){ $tabactive = 'htactive'; }else{ $tabactive = ' '; }
                                                                    echo '<div class="ht-product-cus-tab-pane '.$tabactive.'" id="image-'.$i.get_the_ID().'"><a href="'.esc_url( get_the_permalink() ).'">'.wp_get_attachment_image( $gallery_attachment_id, 'woocommerce_thumbnail' ).'</a></div>';
                                                                }
                                                            ?>
                                                        </div>
                                                        <ul class="ht-product-cus-tab-links">
                                                            <?php
                                                                $j = 0;
                                                                foreach ( $gallery_images_ids as $gallery_attachment_id ) {
                                                                    $j++;
                                                                    if( $j == 1 ){ $tabactive = 'htactive'; }else{ $tabactive = ' '; }
                                                                    echo '<li><a href="#image-'.$j.get_the_ID().'" class="'.$tabactive.'">'.wp_get_attachment_image( $gallery_attachment_id, 'woocommerce_gallery_thumbnail' ).'</a></li>';
                                                                }
                                                            ?>
                                                        </ul>

                                                    <?php else: ?>
                                                        <a href="<?php the_permalink();?>"> 
                                                            <?php woocommerce_template_loop_product_thumbnail(); ?> 
                                                        </a>
                                                    <?php endif; ?>

                                                </div>

                                                <?php if( $settings['show_countdown'] == 'yes' && $settings['product_countdown_position'] != 'contentbottom' && $offer_end_date != '' ):

                                                    if( $offer_start_date_timestamp && $offer_end_date_timestamp && current_time( 'timestamp' ) > $offer_start_date_timestamp && current_time( 'timestamp' ) < $offer_end_date_timestamp
                                                    ): 
                                                ?>
                                                    <div class="ht-product-countdown-wrap">
                                                        <div class="ht-product-countdown" data-countdown="<?php echo esc_attr( $offer_end_date ); ?>" data-customlavel='<?php echo wp_json_encode( $data_customlavel ) ?>'></div>
                                                    </div>
                                                <?php endif; endif; ?>

                                                <?php if( $settings['show_action_button'] == 'yes' ){ if( $settings['action_button_position'] != 'contentbottom' ): ?>
                                                    <div class="ht-product-action">
                                                        <ul <?php echo $this->get_render_attribute_string( 'action_btn_attr' ); ?>>
                                                            <li>
                                                                <a href="javascript:void(0);" class="dethemekitquickview" data-quick-id="<?php the_ID();?>" >
                                                                    <i class="sli sli-magnifier"></i>
                                                                    <span class="ht-product-action-tooltip"><?php esc_html_e('Quick View','dethemekit'); ?></span>
                                                                </a>
                                                            </li>
                                                            <?php
                                                                if ( class_exists( 'YITH_WCWL' ) ) {
                                                                    echo '<li>'.dethemekit_add_to_wishlist_button('<i class="sli sli-heart"></i>','<i class="sli sli-heart"></i>', 'yes').'</li>';
                                                                }
                                                                if( class_exists('TInvWL_Public_AddToWishlist') ){
                                                                    echo '<li>';
                                                                        \TInvWL_Public_AddToWishlist::instance()->htmloutput();
                                                                    echo '</li>';
                                                                }
                                                            ?>
                                                            <?php
                                                                if( function_exists('dethemekit_compare_button') && class_exists('YITH_Woocompare_Frontend') ){
                                                                    echo '<li>';
                                                                        dethemekit_compare_button(2);
                                                                    echo '</li>';
                                                                }
                                                            ?>
                                                            <li class="dethemekit-cart"><?php woocommerce_template_loop_add_to_cart(); ?></li>
                                                        </ul>
                                                    </div>
                                                <?php endif; } ?>

                                            </div>

                                            <div class="ht-product-content">
                                                <div class="ht-product-content-inner">
                                                    <div class="ht-product-categories"><?php dethemekit_get_product_category_list(); ?></div>
                                                    <h4 class="ht-product-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                                    <div class="ht-product-price"><?php woocommerce_template_loop_price();?></div>
                                                    <div class="ht-product-ratting-wrap"><?php echo dethemekit_wc_get_rating_html(); ?></div>

                                                    <?php if( $settings['show_action_button'] == 'yes' ){ if( $settings['action_button_position'] == 'contentbottom' ): ?>
                                                        <div class="ht-product-action">
                                                            <ul <?php echo $this->get_render_attribute_string( 'action_btn_attr' ); ?>>
                                                                <li>
                                                                    <a href="javascript:void(0);" class="dethemekitquickview" data-quick-id="<?php the_ID();?>" >
                                                                        <i class="sli sli-magnifier"></i>
                                                                        <span class="ht-product-action-tooltip"><?php esc_html_e('Quick View','dethemekit'); ?></span>
                                                                    </a>
                                                                </li>
                                                                <?php
                                                                    if ( class_exists( 'YITH_WCWL' ) ) {
                                                                        echo '<li>'.dethemekit_add_to_wishlist_button('<i class="sli sli-heart"></i>','<i class="sli sli-heart"></i>', 'yes').'</li>';
                                                                    }
                                                                    if( class_exists('TInvWL_Public_AddToWishlist') ){
                                                                        echo '<li>';
                                                                            \TInvWL_Public_AddToWishlist::instance()->htmloutput();
                                                                        echo '</li>';
                                                                    }
                                                                ?>
                                                                <?php
                                                                    if( function_exists('dethemekit_compare_button') && class_exists('YITH_Woocompare_Frontend') ){
                                                                        echo '<li>';
                                                                            dethemekit_compare_button(2);
                                                                        echo '</li>';
                                                                    }
                                                                ?>
                                                                <li class="dethemekit-cart"><?php woocommerce_template_loop_add_to_cart(); ?></li>
                                                            </ul>
                                                        </div>
                                                    <?php endif; } ?>

                                                    <?php
                                                        if( $settings['stock_progress_bar'] == 'yes'){
                                                            dethemekit_stock_status( $order_text, $available_text, get_the_ID() );
                                                        }
                                                    ?>

                                                </div>
                                                <?php 
                                                    if( $settings['show_countdown'] == 'yes' && $settings['product_countdown_position'] == 'contentbottom' && $offer_end_date != ''  ):

                                                        if( $offer_start_date_timestamp && $offer_end_date_timestamp && current_time( 'timestamp' ) > $offer_start_date_timestamp && current_time( 'timestamp' ) < $offer_end_date_timestamp
                                                        ):
                                                ?>
                                                    <div class="ht-product-countdown-wrap">
                                                        <div class="ht-product-countdown" data-countdown="<?php echo esc_attr( $offer_end_date ); ?>" data-customlavel='<?php echo wp_json_encode( $data_customlavel ) ?>'></div>
                                                    </div>
                                                <?php endif; endif; ?>
                                            </div>

                                        </div>
                                    </div>
                                    <!--Product End-->

                                <?php endwhile; wp_reset_query(); wp_reset_postdata(); ?>

                            </div>
                        </div>
                    <?php endif; endforeach; ?>
                    
                </div>

            <?php else: ?>
                <?php if( $settings['product_layout_style'] == 'slider' ){ echo '<div class="ht-row">'; } ?>
                    <div class="ht-products woocommerce <?php if( $settings['product_layout_style'] == 'slider' ){ echo esc_attr( 'product-slider' ); } else{ echo 'ht-row'; } ?>" dir="<?php echo $direction; ?>" data-settings='<?php if( $settings['product_layout_style'] == 'slider' ){ echo wp_json_encode( $slider_settings ); } ?>'>

                        <?php
                            if( $products->have_posts() ):

                                while( $products->have_posts() ): $products->the_post();

                                    // Sale Schedule
                                    $offer_start_date_timestamp = get_post_meta( get_the_ID(), '_sale_price_dates_from', true );
                                    $offer_start_date = $offer_start_date_timestamp ? date_i18n( 'Y/m/d', $offer_start_date_timestamp ) : '';
                                    $offer_end_date_timestamp = get_post_meta( get_the_ID(), '_sale_price_dates_to', true );
                                    $offer_end_date = $offer_end_date_timestamp ? date_i18n( 'Y/m/d', $offer_end_date_timestamp ) : '';

                                    // Gallery Image
                                    global $product;
                                    $gallery_images_ids = $product->get_gallery_image_ids() ? $product->get_gallery_image_ids() : array();
                                    if ( has_post_thumbnail() ){
                                        array_unshift( $gallery_images_ids, $product->get_image_id() );
                                    }

                        ?>

                            <!--Product Start-->
                            <div class="<?php echo $collumval; ?>">
                                <div class="ht-product-inner">

                                    <div class="ht-product-image-wrap">
                                        <?php
                                            if( class_exists('WooCommerce') ){ 
                                                dethemekit_custom_product_badge(); 
                                                dethemekit_sale_flash();
                                            }
                                        ?>
                                        <div class="ht-product-image">
                                            <?php  if( $settings['thumbnails_style'] == 2 && $gallery_images_ids ): ?>
                                                <div class="ht-product-image-slider ht-product-image-thumbnaisl-<?php echo $tabuniqid; ?>" data-slick='{"rtl":<?php if( is_rtl() ){ echo 'true'; }else{ echo 'false'; } ?> }'>
                                                    <?php
                                                        foreach ( $gallery_images_ids as $gallery_attachment_id ) {
                                                            echo '<a href="'.esc_url( get_the_permalink() ).'" class="item">'.wp_get_attachment_image( $gallery_attachment_id, 'woocommerce_thumbnail' ).'</a>';
                                                        }
                                                    ?>
                                                </div>

                                            <?php elseif( $settings['thumbnails_style'] == 3 && $gallery_images_ids ) : $tabactive = ''; ?>
                                                <div class="ht-product-cus-tab">
                                                    <?php
                                                        $i = 0;
                                                        foreach ( $gallery_images_ids as $gallery_attachment_id ) {
                                                            $i++;
                                                            if( $i == 1 ){ $tabactive = 'htactive'; }else{ $tabactive = ' '; }
                                                            echo '<div class="ht-product-cus-tab-pane '.$tabactive.'" id="image-'.$i.get_the_ID().'"><a href="#">'.wp_get_attachment_image( $gallery_attachment_id, 'woocommerce_thumbnail' ).'</a></div>';
                                                        }
                                                    ?>
                                                </div>
                                                <ul class="ht-product-cus-tab-links">
                                                    <?php
                                                        $j = 0;
                                                        foreach ( $gallery_images_ids as $gallery_attachment_id ) {
                                                            $j++;
                                                            if( $j == 1 ){ $tabactive = 'htactive'; }else{ $tabactive = ' '; }
                                                            echo '<li><a href="#image-'.$j.get_the_ID().'" class="'.$tabactive.'">'.wp_get_attachment_image( $gallery_attachment_id, 'woocommerce_gallery_thumbnail' ).'</a></li>';
                                                        }
                                                    ?>
                                                </ul>

                                            <?php else: ?>
                                                <a href="<?php the_permalink();?>"> 
                                                    <?php woocommerce_template_loop_product_thumbnail(); ?> 
                                                </a>
                                            <?php endif; ?>

                                        </div>

                                        <?php if( $settings['show_countdown'] == 'yes' && $settings['product_countdown_position'] != 'contentbottom' && $offer_end_date != '' ):

                                            if( $offer_start_date_timestamp && $offer_end_date_timestamp && current_time( 'timestamp' ) > $offer_start_date_timestamp && current_time( 'timestamp' ) < $offer_end_date_timestamp
                                            ): 
                                        ?>
                                            <div class="ht-product-countdown-wrap">
                                                <div class="ht-product-countdown" data-countdown="<?php echo esc_attr( $offer_end_date ); ?>" data-customlavel='<?php echo wp_json_encode( $data_customlavel ) ?>'></div>
                                            </div>
                                        <?php endif; endif; ?>

                                        <?php if( $settings['show_action_button'] == 'yes' ){ if( $settings['action_button_position'] != 'contentbottom' ): ?>
                                            <div class="ht-product-action">
                                                <ul <?php echo $this->get_render_attribute_string( 'action_btn_attr' ); ?>>
                                                    <li>
                                                        <a href="javascript:void(0);" class="dethemekitquickview" data-quick-id="<?php the_ID();?>" >
                                                            <i class="sli sli-magnifier"></i>
                                                            <span class="ht-product-action-tooltip"><?php esc_html_e('Quick View','dethemekit'); ?></span>
                                                        </a>
                                                    </li>
                                                    <?php
                                                        if ( class_exists( 'YITH_WCWL' ) ) {
                                                            echo '<li>'.dethemekit_add_to_wishlist_button('<i class="sli sli-heart"></i>','<i class="sli sli-heart"></i>', 'yes').'</li>';
                                                        }
                                                        if( class_exists('TInvWL_Public_AddToWishlist') ){
                                                            echo '<li>';
                                                                \TInvWL_Public_AddToWishlist::instance()->htmloutput();
                                                            echo '</li>';
                                                        }
                                                    ?>
                                                    <?php
                                                        if( function_exists('dethemekit_compare_button') && class_exists('YITH_Woocompare_Frontend') ){
                                                            echo '<li>';
                                                                dethemekit_compare_button(2);
                                                            echo '</li>';
                                                        }
                                                    ?>
                                                    <li class="dethemekit-cart"><?php woocommerce_template_loop_add_to_cart(); ?></li>
                                                </ul>
                                            </div>
                                        <?php endif; }?>

                                    </div>

                                    <div class="ht-product-content">
                                        <div class="ht-product-content-inner">
                                            <div class="ht-product-categories"><?php dethemekit_get_product_category_list(); ?></div>
                                            <h4 class="ht-product-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                            <div class="ht-product-price"><?php woocommerce_template_loop_price();?></div>
                                            <div class="ht-product-ratting-wrap"><?php echo dethemekit_wc_get_rating_html(); ?></div>

                                            <?php if( $settings['show_action_button'] == 'yes' ){ if( $settings['action_button_position'] == 'contentbottom' ): ?>
                                                <div class="ht-product-action">
                                                    <ul <?php echo $this->get_render_attribute_string( 'action_btn_attr' ); ?>>
                                                        <li>
                                                            <a href="javascript:void(0);" class="dethemekitquickview" data-quick-id="<?php the_ID();?>" >
                                                                <i class="sli sli-magnifier"></i>
                                                                <span class="ht-product-action-tooltip"><?php esc_html_e('Quick View','dethemekit'); ?></span>
                                                            </a>
                                                        </li>
                                                        <?php
                                                            if ( class_exists( 'YITH_WCWL' ) ) {
                                                                echo '<li>'.dethemekit_add_to_wishlist_button('<i class="sli sli-heart"></i>','<i class="sli sli-heart"></i>', 'yes').'</li>';
                                                            }
                                                            if( class_exists('TInvWL_Public_AddToWishlist') ){
                                                                echo '<li>';
                                                                    \TInvWL_Public_AddToWishlist::instance()->htmloutput();
                                                                echo '</li>';
                                                            }
                                                        ?>
                                                        <?php
                                                            if( function_exists('dethemekit_compare_button') && class_exists('YITH_Woocompare_Frontend') ){
                                                                echo '<li>';
                                                                    dethemekit_compare_button(2);
                                                                echo '</li>';
                                                            }
                                                        ?>
                                                        <li class="dethemekit-cart"><?php woocommerce_template_loop_add_to_cart(); ?></li>
                                                    </ul>
                                                </div>
                                            <?php endif; } ?>
                                            <?php
                                                if( $settings['stock_progress_bar'] == 'yes'){
                                                    dethemekit_stock_status( $order_text, $available_text, get_the_ID() );
                                                }
                                            ?>
                                        </div>
                                        <?php 
                                            if( $settings['show_countdown'] == 'yes' && $settings['product_countdown_position'] == 'contentbottom' && $offer_end_date != ''  ):

                                                if( $offer_start_date_timestamp && $offer_end_date_timestamp && current_time( 'timestamp' ) > $offer_start_date_timestamp && current_time( 'timestamp' ) < $offer_end_date_timestamp
                                                ):
                                        ?>
                                            <div class="ht-product-countdown-wrap">
                                                <div class="ht-product-countdown" data-countdown="<?php echo esc_attr( $offer_end_date ); ?>" data-customlavel='<?php echo wp_json_encode( $data_customlavel ) ?>'></div>
                                            </div>
                                        <?php endif; endif; ?>
                                    </div>

                                </div>
                            </div>
                            <!--Product End-->

                        <?php endwhile; wp_reset_query(); wp_reset_postdata(); endif; ?>
                    </div>
                <?php if( $settings['product_layout_style'] == 'slider' ){ echo '</div>'; } ?>
            <?php endif; ?>

            <?php if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) { ?>
                <script>
                    jQuery(document).ready(function($) {
                        'use strict';
                        $(".ht-product-image-thumbnaisl-<?php echo $tabuniqid; ?>").slick({
                            dots: true,
                            arrows: true,
                            prevArrow: '<button class="slick-prev"><i class="sli sli-arrow-left"></i></button>',
                            nextArrow: '<button class="slick-next"><i class="sli sli-arrow-right"></i></button>',
                        });
                    });
                </script>
            <?php } ?>

        <?php

    }

}

// \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new De_Product_Display() );