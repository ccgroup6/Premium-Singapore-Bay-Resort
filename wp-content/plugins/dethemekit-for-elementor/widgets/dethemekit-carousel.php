<?php
/**
 * DethemeKit Carousel.
 */

namespace DethemeKitAddons\Widgets;

// Elementor Classes.
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Border;

// DethemeKitAddons Classes.
use DethemeKitAddons\Helper_Functions;
use DethemeKitAddons\Includes;
use DethemeKitAddons\DethemeKit_Template_Tags;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // No access of directly access.
}

/**
 * Class DethemeKit_Carousel
 */
class DethemeKit_Carousel extends Widget_Base {

	protected $template_instance;

	/**
	 * Get Elementor Helper Instance.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function getTemplateInstance() {
		return $this->template_instance = Includes\DethemeKit_Template_Tags::getInstance();
	}

	/**
	 * Retrieve Widget Name.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_name() {
		return 'dethemekit-carousel-widget';
	}

	/**
	 * Retrieve Widget Title.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_title() {
		return __('De Carousel', 'dethemekit-addons-for-elementor') ;

	}

	/**
	 * Retrieve Widget Icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string widget icon.
	 */
	public function get_icon() {
		return 'eicon-media-carousel';
	}

	/**
	 * Widget preview refresh button.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function is_reload_preview_required() {
		return true;
	}

	/**
	 * Retrieve Widget Dependent CSS.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array CSS style handles.
	 */
	public function get_style_depends() {
		return array(
			'font-awesome-5-all',
			'dethemekit-addons',
		);
	}

	/**
	 * Retrieve Widget Dependent JS.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array JS script handles.
	 */
	public function get_script_depends() {
		return array(
			'slick',
			'dethemekit-addons-js',
			'dethemekit-decarousel-js',
		);
	}

	/**
	 * Retrieve Widget Categories.
	 *
	 * @since 1.5.1
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'dethemekit-elements' );
	}

	/**
	 * Retrieve Widget Support URL.
	 *
	 * @access public
	 *
	 * @return string support URL.
	 */
	public function get_custom_help_url() {
		return 'https://detheme.helpscoutdocs.com/category/367-dethemekit-for-elementor-wordpress-plugin';
	}

	/**
	 * Register Carousel controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'dethemekit_carousel_global_settings',
			array(
				'label' => __( 'De Carousel', 'dethemekit-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'dethemekit_carousel_parent_name',
			array(
				'label'       => __( 'Set De Carousel Name', 'dethemekit-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => array(
					''   	  => __( 'Select Field', 'dethemekit-addons-for-elementor' ),
					'de_carousel_1'   	=> __( 'De Carousel 1', 'dethemekit-addons-for-elementor' ),
					'de_carousel_2' 	=> __( 'De Carousel 2', 'dethemekit-addons-for-elementor' ),
					'de_carousel_3'   	=> __( 'De Carousel 3', 'dethemekit-addons-for-elementor' ),
					'de_carousel_4' 	=> __( 'De Carousel 4', 'dethemekit-addons-for-elementor' ),
				),
				'default'     => '',
				'description' => __( 'Used by De Carousel Navigation Wrapper (in Section) and De Carousel Navigation Item ( in column ) to target this element <br /><br /><a href="https://detheme.helpscoutdocs.com/article/368-how-to-use-decarousel" target="_blank">How To Use De Carousel ?</a>', 'dethemekit-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'dethemekit_carousel_content_type',
			array(
				'label'       => __( 'Content Type', 'dethemekit-addons-for-elementor' ),
				'description' => __( 'How templates are selected', 'dethemekit-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'select'   => __( 'Select Field', 'dethemekit-addons-for-elementor' ),
					'repeater' => __( 'Repeater', 'dethemekit-addons-for-elementor' ),
				),
				'default'     => 'select',
			)
		);

		$this->add_control(
			'dethemekit_carousel_slider_content',
			array(
				'label'       => __( 'Templates', 'dethemekit-addons-for-elementor' ),
				'description' => __( 'Slider content is a template which you can choose from Elementor library. Each template will be a slider content', 'dethemekit-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => $this->getTemplateInstance()->get_elementor_page_list(),
				'multiple'    => true,
				'label_block' => true,
				'condition'   => array(
					'dethemekit_carousel_content_type' => 'select',
				),
			)
		);

		$repeater = new REPEATER();

		$repeater->add_control(
			'dethemekit_carousel_repeater_item',
			array(
				'label'       => __( 'Content', 'dethemekit-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'options'     => $this->getTemplateInstance()->get_elementor_page_list(),
			)
		);

		$repeater->add_control(
			'custom_navigation',
			array(
				'label'       => __( 'Custom Navigation Element Selector', 'dethemekit-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'description' => __( 'Use this to add an element selector to be used to navigate to this slide. For example #slide-1', 'dethemekit-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'dethemekit_carousel_templates_repeater',
			array(
				'label'       => __( 'Templates', 'dethemekit-addons-for-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'condition'   => array(
					'dethemekit_carousel_content_type' => 'repeater',
				),
				'title_field' => 'Template: {{{ dethemekit_carousel_repeater_item }}}',
			)
		);

		$this->add_control(
			'dethemekit_carousel_slider_type',
			array(
				'label'       => __( 'Type', 'dethemekit-addons-for-elementor' ),
				'description' => __( 'Set a navigation type', 'dethemekit-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'horizontal' => __( 'Horizontal', 'dethemekit-addons-for-elementor' ),
					'vertical'   => __( 'Vertical', 'dethemekit-addons-for-elementor' ),
				),
				'default'     => 'horizontal',
			)
		);

		$this->add_control(
			'dethemekit_carousel_dot_navigation_show',
			array(
				'label'       => __( 'Dots', 'dethemekit-addons-for-elementor' ),
				'description' => __( 'Enable or disable navigation dots', 'dethemekit-addons-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'separator'   => 'before',
				'default'     => 'yes',
			)
		);

		$this->add_control(
			'dethemekit_carousel_dot_position',
			array(
				'label'     => __( 'Position', 'dethemekit-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'below',
				'options'   => array(
					'below' => __( 'Below Slides', 'dethemekit-addons-for-elementor' ),
					'above' => __( 'On Slides', 'dethemekit-addons-for-elementor' ),
				),
				'condition' => array(
					'dethemekit_carousel_dot_navigation_show' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'dethemekit_carousel_dot_offset',
			array(
				'label'      => __( 'Horizontal Offset', 'dethemekit-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .dethemekit-carousel-dots-above ul.slick-dots' => 'left: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'dethemekit_carousel_dot_navigation_show' => 'yes',
					'dethemekit_carousel_dot_position'        => 'above',
				),
			)
		);

		$this->add_responsive_control(
			'dethemekit_carousel_dot_voffset',
			array(
				'label'      => __( 'Vertical Offset', 'dethemekit-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .dethemekit-carousel-dots-above ul.slick-dots' => 'top: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .dethemekit-carousel-dots-below ul.slick-dots' => 'bottom: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'dethemekit_carousel_dot_navigation_show' => 'yes',
				),
			)
		);

		$this->add_control(
			'dethemekit_carousel_navigation_effect',
			array(
				'label'        => __( 'Ripple Effect', 'dethemekit-addons-for-elementor' ),
				'description'  => __( 'Enable a ripple effect when the active dot is hovered/clicked', 'dethemekit-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'prefix_class' => 'dethemekit-carousel-ripple-',
				'condition'    => array(
					'dethemekit_carousel_dot_navigation_show' => 'yes',
				),
			)
		);

		$this->add_control(
			'dethemekit_carousel_navigation_show',
			array(
				'label'       => __( 'Arrows', 'dethemekit-addons-for-elementor' ),
				'description' => __( 'Enable or disable navigation arrows', 'dethemekit-addons-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'separator'   => 'before',
				'default'     => 'yes',
			)
		);

		$this->add_control(
			'dethemekit_carousel_slides_to_show',
			array(
				'label'     => __( 'Appearance', 'dethemekit-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'all',
				'separator' => 'before',
				'options'   => array(
					'all'    => __( 'All visible', 'dethemekit-addons-for-elementor' ),
					'single' => __( 'One at a time', 'dethemekit-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'dethemekit_carousel_responsive_desktop',
			array(
				'label'   => __( 'Desktop Slides', 'dethemekit-addons-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 1,
			)
		);

		$this->add_control(
			'dethemekit_carousel_responsive_tabs',
			array(
				'label'   => __( 'Tabs Slides', 'dethemekit-addons-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 1,
			)
		);

		$this->add_control(
			'dethemekit_carousel_responsive_mobile',
			array(
				'label'   => __( 'Mobile Slides', 'dethemekit-addons-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 1,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'dethemekit_carousel_slides_settings',
			array(
				'label' => __( 'Slides Settings', 'dethemekit-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'dethemekit_carousel_loop',
			array(
				'label'       => __( 'Infinite Loop', 'dethemekit-addons-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => __( 'Restart the slider automatically as it passes the last slide', 'dethemekit-addons-for-elementor' ),
				'default'     => 'yes',
			)
		);

		$this->add_control(
			'dethemekit_carousel_fade',
			array(
				'label'       => __( 'Fade', 'dethemekit-addons-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => __( 'Enable fade transition between slides', 'dethemekit-addons-for-elementor' ),
				'condition'   => array(
					'dethemekit_carousel_slider_type' => 'horizontal',
				),
			)
		);

		$this->add_control(
			'dethemekit_carousel_zoom',
			array(
				'label'     => __( 'Zoom Effect', 'dethemekit-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => array(
					'dethemekit_carousel_fade'        => 'yes',
					'dethemekit_carousel_slider_type' => 'horizontal',
				),
			)
		);

		$this->add_control(
			'dethemekit_carousel_speed',
			array(
				'label'       => __( 'Transition Speed', 'dethemekit-addons-for-elementor' ),
				'description' => __( 'Set a navigation speed value. The value will be counted in milliseconds (ms)', 'dethemekit-addons-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 300,
			)
		);

		$this->add_control(
			'dethemekit_carousel_autoplay',
			array(
				'label'       => __( 'Autoplay Slides‏', 'dethemekit-addons-for-elementor' ),
				'description' => __( 'Slide will start automatically', 'dethemekit-addons-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'yes',
			)
		);

		$this->add_control(
			'dethemekit_carousel_autoplay_speed',
			array(
				'label'       => __( 'Autoplay Speed', 'dethemekit-addons-for-elementor' ),
				'description' => __( 'Autoplay Speed means at which time the next slide should come. Set a value in milliseconds (ms)', 'dethemekit-addons-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 5000,
				'condition'   => array(
					'dethemekit_carousel_autoplay' => 'yes',
				),
			)
		);

		$this->add_control(
			'dethemekit_carousel_animation_list',
			array(
				'label'       => __( 'Animations', 'dethemekit-addons-for-elementor' ),
				'type'        => Controls_Manager::HIDDEN,
				'render_type' => 'template',
			)
		);

		$this->add_control(
			'dethemekit_carousel_extra_class',
			array(
				'label'       => __( 'Extra Class', 'dethemekit-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'Add extra class name that will be applied to the carousel, and you can use this class for your customizations.', 'dethemekit-addons-for-elementor' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'dethemekit-carousel-advance-settings',
			array(
				'label' => __( 'Additional Settings', 'dethemekit-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'dethemekit_carousel_draggable_effect',
			array(
				'label'       => __( 'Draggable Effect', 'dethemekit-addons-for-elementor' ),
				'description' => __( 'Allow the slides to be dragged by mouse click', 'dethemekit-addons-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'yes',
			)
		);

		$this->add_control(
			'dethemekit_carousel_touch_move',
			array(
				'label'       => __( 'Touch Move', 'dethemekit-addons-for-elementor' ),
				'description' => __( 'Enable slide moving with touch', 'dethemekit-addons-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'yes',
			)
		);

		$this->add_control(
			'dethemekit_carousel_RTL_Mode',
			array(
				'label'       => __( 'RTL Mode', 'dethemekit-addons-for-elementor' ),
				'description' => __( 'Turn on RTL mode if your language starts from right to left', 'dethemekit-addons-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'condition'   => array(
					'dethemekit_carousel_slider_type!' => 'vertical',
				),
			)
		);

		$this->add_control(
			'dethemekit_carousel_adaptive_height',
			array(
				'label'       => __( 'Adaptive Height', 'dethemekit-addons-for-elementor' ),
				'description' => __( 'Adaptive height setting gives each slide a fixed height to avoid huge white space gaps', 'dethemekit-addons-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
			)
		);

		$this->add_control(
			'dethemekit_carousel_pausehover',
			array(
				'label'       => __( 'Pause on Hover', 'dethemekit-addons-for-elementor' ),
				'description' => __( 'Pause the slider when mouse hover', 'dethemekit-addons-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
			)
		);

		// $this->add_control(
		// 	'dethemekit_carousel_center_mode',
		// 	array(
		// 		'label'       => __( 'Center Mode', 'dethemekit-addons-for-elementor' ),
		// 		'description' => __( 'Center mode enables a centered view with partial next/previous slides. Animations and all visible scroll type doesn\'t work with this mode', 'dethemekit-addons-for-elementor' ),
		// 		'type'        => Controls_Manager::SWITCHER,
		// 	)
		// );

		// $this->add_control(
		// 	'dethemekit_carousel_space_btw_items',
		// 	array(
		// 		'label'       => __( 'Slides\' Spacing', 'dethemekit-addons-for-elementor' ),
		// 		'description' => __( 'Set a spacing value in pixels (px)', 'dethemekit-addons-for-elementor' ),
		// 		'type'        => Controls_Manager::NUMBER,
		// 		'default'     => '15',
		// 	)
		// );

		$this->add_control(
			'dethemekit_carousel_tablet_breakpoint',
			array(
				'label'       => __( 'Tablet Breakpoint', 'dethemekit-addons-for-elementor' ),
				'description' => __( 'Sets the breakpoint between desktop and tablet devices. Below this breakpoint tablet layout will appear (Default: 1025px).', 'dethemekit-addons-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 1025,
			)
		);

		$this->add_control(
			'dethemekit_carousel_mobile_breakpoint',
			array(
				'label'       => __( 'Mobile Breakpoint', 'dethemekit-addons-for-elementor' ),
				'description' => __( 'Sets the breakpoint between tablet and mobile devices. Below this breakpoint mobile layout will appear (Default: 768px).', 'dethemekit-addons-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 768,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'dethemekit_carousel_navigation_arrows',
			array(
				'label'     => __( 'Navigation Arrows', 'dethemekit-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'dethemekit_carousel_navigation_show' => 'yes',
				),
			)
		);

		$this->add_control(
			'custom_left_arrow',
			array(
				'label' => __( 'Custom Previous Icon', 'dethemekit-addons-for-elementor' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);

		$this->add_control(
			'custom_left_arrow_select',
			array(
				'label'       => __( 'Select Icon', 'dethemekit-addons-for-elementor' ),
				'type'        => Controls_Manager::ICONS,
				'default'     => array(
					'value'   => 'fas fa-arrow-alt-circle-left',
					'library' => 'fa-solid',
				),
				'skin'        => 'inline',
				'condition'   => array(
					'custom_left_arrow' => 'yes',
				),
				'label_block' => false,
			)
		);

		$this->add_control(
			'dethemekit_carousel_arrow_icon_prev_ver',
			array(
				'label'     => __( 'Top Icon', 'dethemekit-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left_arrow_bold'        => array(
						'icon' => 'fas fa-arrow-up',
					),
					'left_arrow_long'        => array(
						'icon' => 'fas fa-long-arrow-alt-up',
					),
					'left_arrow_long_circle' => array(
						'icon' => 'fas fa-arrow-circle-up',
					),
					'left_arrow_angle'       => array(
						'icon' => 'fas fa-angle-up',
					),
					'left_arrow_chevron'     => array(
						'icon' => 'fas fa-chevron-up',
					),
				),
				'default'   => 'left_arrow_angle',
				'condition' => array(
					'dethemekit_carousel_slider_type' => 'vertical',
					'custom_left_arrow!'           => 'yes',
				),
				'toggle'    => false,
			)
		);

		$this->add_control(
			'dethemekit_carousel_arrow_icon_prev',
			array(
				'label'     => __( 'Left Icon', 'dethemekit-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left_arrow_bold'        => array(
						'icon' => 'fas fa-arrow-left',
					),
					'left_arrow_long'        => array(
						'icon' => 'fas fa-long-arrow-alt-left',
					),
					'left_arrow_long_circle' => array(
						'icon' => 'fas fa-arrow-circle-left',
					),
					'left_arrow_angle'       => array(
						'icon' => 'fas fa-angle-left',
					),
					'left_arrow_chevron'     => array(
						'icon' => 'fas fa-chevron-left',
					),
				),
				'default'   => 'left_arrow_angle',
				'condition' => array(
					'dethemekit_carousel_slider_type!' => 'vertical',
					'custom_left_arrow!'            => 'yes',
				),
				'toggle'    => false,
			)
		);

		$this->add_control(
			'custom_right_arrow',
			array(
				'label'     => __( 'Custom Next Icon', 'dethemekit-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'custom_right_arrow_select',
			array(
				'label'       => __( 'Select Icon', 'dethemekit-addons-for-elementor' ),
				'type'        => Controls_Manager::ICONS,
				'default'     => array(
					'value'   => 'fas fa-arrow-alt-circle-right',
					'library' => 'fa-solid',
				),
				'skin'        => 'inline',
				'condition'   => array(
					'custom_right_arrow' => 'yes',
				),
				'label_block' => false,
			)
		);

		$this->add_control(
			'dethemekit_carousel_arrow_icon_next',
			array(
				'label'     => __( 'Right Icon', 'dethemekit-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'right_arrow_bold'        => array(
						'icon' => 'fas fa-arrow-right',
					),
					'right_arrow_long'        => array(
						'icon' => 'fas fa-long-arrow-alt-right',
					),
					'right_arrow_long_circle' => array(
						'icon' => 'fas fa-arrow-circle-right',
					),
					'right_arrow_angle'       => array(
						'icon' => 'fas fa-angle-right',
					),
					'right_arrow_chevron'     => array(
						'icon' => 'fas fa-chevron-right',
					),
				),
				'default'   => 'right_arrow_angle',
				'condition' => array(
					'dethemekit_carousel_slider_type!' => 'vertical',
					'custom_right_arrow!'           => 'yes',
				),
				'toggle'    => false,
			)
		);

		$this->add_control(
			'dethemekit_carousel_arrow_icon_next_ver',
			array(
				'label'     => __( 'Bottom Icon', 'dethemekit-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'right_arrow_bold'        => array(
						'icon' => 'fas fa-arrow-down',
					),
					'right_arrow_long'        => array(
						'icon' => 'fas fa-long-arrow-alt-down',
					),
					'right_arrow_long_circle' => array(
						'icon' => 'fas fa-arrow-circle-down',
					),
					'right_arrow_angle'       => array(
						'icon' => 'fas fa-angle-down',
					),
					'right_arrow_chevron'     => array(
						'icon' => 'fas fa-chevron-down',
					),
				),
				'default'   => 'right_arrow_angle',
				'condition' => array(
					'dethemekit_carousel_slider_type' => 'vertical',
					'custom_right_arrow!'          => 'yes',
				),
				'toggle'    => false,
			)
		);

		$this->add_responsive_control(
			'dethemekit_carousel_arrow_size',
			array(
				'label'      => __( 'Size', 'dethemekit-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'vw' ),
				'default'    => array(
					'size' => 14,
					'unit' => 'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'separator'  => 'before',
				'selectors'  => array(
					'{{WRAPPER}} .dethemekit-carousel-wrapper .slick-arrow' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .dethemekit-carousel-wrapper .slick-arrow svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'dethemekit_carousel_arrow_position',
			array(
				'label'     => __( 'Position (PX)', 'dethemekit-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => -100,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} a.carousel-arrow.carousel-next' => 'right: {{SIZE}}px',
					'{{WRAPPER}} a.carousel-arrow.carousel-prev' => 'left: {{SIZE}}px',
					'{{WRAPPER}} a.ver-carousel-arrow.carousel-next' => 'bottom: {{SIZE}}px',
					'{{WRAPPER}} a.ver-carousel-arrow.carousel-prev' => 'top: {{SIZE}}px',
				),
			)
		);

		$this->start_controls_tabs( 'dethemekit_button_style_tabs' );

		$this->start_controls_tab(
			'dethemekit_button_style_normal',
			array(
				'label' => __( 'Normal', 'dethemekit-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'dethemekit_carousel_arrow_color',
			array(
				'label'     => __( 'Color', 'dethemekit-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Color::get_type(),
					'value' => Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} .dethemekit-carousel-wrapper .slick-arrow' => 'color: {{VALUE}};',
					'{{WRAPPER}} .dethemekit-carousel-wrapper .slick-arrow svg, {{WRAPPER}} .dethemekit-carousel-wrapper .slick-arrow svg g path' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'dethemekit_carousel_arrow_bg_color',
			array(
				'label'     => __( 'Background Color', 'dethemekit-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} a.carousel-next, {{WRAPPER}} a.carousel-prev' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'dethemekit_carousel_arrows_border_normal',
				'selector' => '{{WRAPPER}} .slick-arrow',
			)
		);

		$this->add_control(
			'dethemekit_carousel_arrows_radius_normal',
			array(
				'label'      => __( 'Border Radius', 'dethemekit-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .slick-arrow' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'dethemekit_carousel_arrows_hover',
			array(
				'label' => __( 'Hover', 'dethemekit-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'dethemekit_carousel_hover_arrow_color',
			array(
				'label'     => __( 'Color', 'dethemekit-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Color::get_type(),
					'value' => Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} .dethemekit-carousel-wrapper .slick-arrow:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .dethemekit-carousel-wrapper .slick-arrow:hover svg, {{WRAPPER}} .dethemekit-carousel-wrapper .slick-arrow:hover svg g path' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'dethemekit_carousel_arrow_hover_bg_color',
			array(
				'label'     => __( 'Background Color', 'dethemekit-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} a.carousel-next:hover, {{WRAPPER}} a.carousel-prev:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'dethemekit_carousel_arrows_border_hover',
				'selector' => '{{WRAPPER}} .slick-arrow:hover',
			)
		);

		$this->add_control(
			'dethemekit_carousel_arrows_radius_hover',
			array(
				'label'      => __( 'Border Radius', 'dethemekit-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .slick-arrow:hover' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'dethemekit_carousel_navigation_dots',
			array(
				'label'     => __( 'Navigation Dots', 'dethemekit-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'dethemekit_carousel_dot_navigation_show' => 'yes',
				),
			)
		);

		$this->add_control(
			'dethemekit_carousel_dot_icon',
			array(
				'label'     => __( 'Icon', 'dethemekit-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'square_white' => array(
						'icon' => 'far fa-square',
					),
					'square_black' => array(
						'icon' => 'fas fa-square',
					),
					'circle_white' => array(
						'icon' => 'fas fa-circle',
					),
					'circle_thin'  => array(
						'icon' => 'far fa-circle',
					),
				),
				'default'   => 'circle_white',
				'condition' => array(
					'custom_pagination_icon!' => 'yes',
				),
				'toggle'    => false,
			)
		);

		$this->add_control(
			'custom_pagination_icon',
			array(
				'label' => __( 'Custom Icon', 'dethemekit-addons-for-elementor' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);

		$this->add_control(
			'custom_pagination_icon_select',
			array(
				'label'       => __( 'Select Icon', 'dethemekit-addons-for-elementor' ),
				'type'        => Controls_Manager::ICONS,
				'default'     => array(
					'value'   => 'fas fa-dot-circle',
					'library' => 'fa-solid',
				),
				'skin'        => 'inline',
				'condition'   => array(
					'custom_pagination_icon' => 'yes',
				),
				'label_block' => false,
			)
		);

		$this->add_responsive_control(
			'dot_size',
			array(
				'label'      => __( 'Size', 'dethemekit-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ul.slick-dots li, {{WRAPPER}} ul.slick-dots li svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: calc( {{SIZE}}{{UNIT}} / 2 )',
				),
			)
		);

		$this->add_control(
			'dethemekit_carousel_dot_navigation_color',
			array(
				'label'     => __( 'Color', 'dethemekit-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Color::get_type(),
					'value' => Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} ul.slick-dots li' => 'color: {{VALUE}}',
					'{{WRAPPER}} ul.slick-dots li svg, {{WRAPPER}} ul.slick-dots li svg g path' => 'fill: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'dethemekit_carousel_dot_navigation_active_color',
			array(
				'label'     => __( 'Active Color', 'dethemekit-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Color::get_type(),
					'value' => Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} ul.slick-dots li.slick-active' => 'color: {{VALUE}}',
					'{{WRAPPER}} ul.slick-dots li.slick-active svg, {{WRAPPER}} ul.slick-dots li.slick-active svg g path' => 'fill: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'dethemekit_carousel_ripple_active_color',
			array(
				'label'     => __( 'Active Ripple Color', 'dethemekit-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'dethemekit_carousel_navigation_effect' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}}.dethemekit-carousel-ripple-yes ul.slick-dots li.slick-active:hover:before' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'dethemekit_carousel_ripple_color',
			array(
				'label'     => __( 'Inactive Ripple Color', 'dethemekit-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'dethemekit_carousel_navigation_effect' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}}.dethemekit-carousel-ripple-yes ul.slick-dots li:hover:before' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Render Carousel widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings();

		$content_type = $settings['dethemekit_carousel_content_type'];
		
		$templates = array();

		if ( 'select' === $content_type ) {
			$templates = $settings['dethemekit_carousel_slider_content'];
		} else {
			$custom_navigation = array();

			foreach ( $settings['dethemekit_carousel_templates_repeater'] as $template ) {
				array_push( $templates, $template['dethemekit_carousel_repeater_item'] );
				array_push( $custom_navigation, $template['custom_navigation'] );
			}
		}

		if ( empty( $templates ) ) {
			return;
		}

		$vertical = 'vertical' === $settings['dethemekit_carousel_slider_type'] ? true : false;

		$slides_on_desk = $settings['dethemekit_carousel_responsive_desktop'];
		if ( 'all' === $settings['dethemekit_carousel_slides_to_show'] ) {
			$slides_scroll = ! empty( $slides_on_desk ) ? $slides_on_desk : 1;
		} else {
			$slides_scroll = 1;
		}

		$slides_show = ! empty( $slides_on_desk ) ? $slides_on_desk : 1;

		$slides_on_tabs = $settings['dethemekit_carousel_responsive_tabs'];
		$slides_on_mob  = $settings['dethemekit_carousel_responsive_mobile'];

		if ( empty( $settings['dethemekit_carousel_responsive_tabs'] ) ) {
			$slides_on_tabs = $slides_on_desk;
		}

		if ( empty( $settings['dethemekit_carousel_responsive_mobile'] ) ) {
			$slides_on_mob = $slides_on_desk;
		}

		$infinite = 'yes' === $settings['dethemekit_carousel_loop'] ? true : false;

		$fade = 'yes' === $settings['dethemekit_carousel_fade'] ? true : false;

		$speed = ! empty( $settings['dethemekit_carousel_speed'] ) ? $settings['dethemekit_carousel_speed'] : '';

		$autoplay = 'yes' === $settings['dethemekit_carousel_autoplay'] ? true : false;

		$autoplay_speed = ! empty( $settings['dethemekit_carousel_autoplay_speed'] ) ? $settings['dethemekit_carousel_autoplay_speed'] : '';

		$draggable = 'yes' === $settings['dethemekit_carousel_draggable_effect'] ? true : false;

		$touch_move = 'yes' === $settings['dethemekit_carousel_touch_move'] ? true : false;

		$dir = '';
		$rtl = false;

		if ( 'yes' === $settings['dethemekit_carousel_RTL_Mode'] ) {
			$rtl = true;
			$dir = 'dir="rtl"';
		}

		$adaptive_height = 'yes' === $settings['dethemekit_carousel_adaptive_height'] ? true : false;

		$pause_hover = 'yes' === $settings['dethemekit_carousel_pausehover'] ? true : false;

		$center_mode = 'yes' === isset($settings['dethemekit_carousel_center_mode']) ? true : false;

		$center_padding = ! empty( $settings['dethemekit_carousel_space_btw_items'] ) ? $settings['dethemekit_carousel_space_btw_items'] . 'px' : '';

		// Navigation arrow setting setup.
		if ( 'yes' === $settings['dethemekit_carousel_navigation_show'] ) {
			$arrows = true;

			if ( 'vertical' === $settings['dethemekit_carousel_slider_type'] ) {
				$vertical_alignment = 'ver-carousel-arrow';
			} else {
				$vertical_alignment = 'carousel-arrow';
			}

			if ( 'vertical' === $settings['dethemekit_carousel_slider_type'] ) {

				if ( 'yes' !== $settings['custom_left_arrow'] ) {
					$icon_prev = $settings['dethemekit_carousel_arrow_icon_prev_ver'];
					if ( 'left_arrow_bold' === $icon_prev ) {
						$icon_prev_class = 'fas fa-arrow-up';
					}
					if ( 'left_arrow_long' === $icon_prev ) {
						$icon_prev_class = 'fas fa-long-arrow-alt-up';
					}
					if ( 'left_arrow_long_circle' === $icon_prev ) {
						$icon_prev_class = 'fas fa-arrow-circle-up';
					}
					if ( 'left_arrow_angle' === $icon_prev ) {
						$icon_prev_class = 'fas fa-angle-up';
					}
					if ( 'left_arrow_chevron' === $icon_prev ) {
						$icon_prev_class = 'fas fa-chevron-up';
					}
				}

				if ( 'yes' !== $settings['custom_right_arrow'] ) {
					$icon_next = $settings['dethemekit_carousel_arrow_icon_next_ver'];
					if ( 'right_arrow_bold' === $icon_next ) {
						$icon_next_class = 'fas fa-arrow-down';
					}
					if ( 'right_arrow_long' === $icon_next ) {
						$icon_next_class = 'fas fa-long-arrow-alt-down';
					}
					if ( 'right_arrow_long_circle' === $icon_next ) {
						$icon_next_class = 'fas fa-arrow-circle-down';
					}
					if ( 'right_arrow_angle' === $icon_next ) {
						$icon_next_class = 'fas fa-angle-down';
					}
					if ( 'right_arrow_chevron' === $icon_next ) {
						$icon_next_class = 'fas fa-chevron-down';
					}
				}
			} else {

				if ( 'yes' !== $settings['custom_left_arrow'] ) {
					$icon_prev = $settings['dethemekit_carousel_arrow_icon_prev'];
					if ( 'left_arrow_bold' === $icon_prev ) {
						$icon_prev_class = 'fas fa-arrow-left';
					}
					if ( 'left_arrow_long' === $icon_prev ) {
						$icon_prev_class = 'fas fa-long-arrow-alt-left';
					}
					if ( 'left_arrow_long_circle' === $icon_prev ) {
						$icon_prev_class = 'fas fa-arrow-circle-left';
					}
					if ( 'left_arrow_angle' === $icon_prev ) {
						$icon_prev_class = 'fas fa-angle-left';
					}
					if ( 'left_arrow_chevron' === $icon_prev ) {
						$icon_prev_class = 'fas fa-chevron-left';
					}
				}

				if ( 'yes' !== $settings['custom_right_arrow'] ) {
					$icon_next = $settings['dethemekit_carousel_arrow_icon_next'];
					if ( 'right_arrow_bold' === $icon_next ) {
						$icon_next_class = 'fas fa-arrow-right';
					}
					if ( 'right_arrow_long' === $icon_next ) {
						$icon_next_class = 'fas fa-long-arrow-alt-right';
					}
					if ( 'right_arrow_long_circle' === $icon_next ) {
						$icon_next_class = 'fas fa-arrow-circle-right';
					}
					if ( 'right_arrow_angle' === $icon_next ) {
						$icon_next_class = 'fas fa-angle-right';
					}
					if ( 'right_arrow_chevron' === $icon_next ) {
						$icon_next_class = 'fas fa-chevron-right';
					}
				}
			}
		} else {
			$arrows = false;
		}

		if ( 'yes' === $settings['dethemekit_carousel_dot_navigation_show'] ) {
			$dots = true;
			if ( 'yes' !== $settings['custom_pagination_icon'] ) {
				if ( 'square_white' === $settings['dethemekit_carousel_dot_icon'] ) {
					$dot_icon = 'far fa-square';
				}
				if ( 'square_black' === $settings['dethemekit_carousel_dot_icon'] ) {
					$dot_icon = 'fas fa-square';
				}
				if ( 'circle_white' === $settings['dethemekit_carousel_dot_icon'] ) {
					$dot_icon = 'fas fa-circle';
				}
				if ( 'circle_thin' === $settings['dethemekit_carousel_dot_icon'] ) {
					$dot_icon = 'far fa-circle';
				}
				$custom_paging = $dot_icon;
			}
		} else {
			$dots = false;
		}
		$extra_class = ! empty( $settings['dethemekit_carousel_extra_class'] ) ? ' ' . $settings['dethemekit_carousel_extra_class'] : '';

		$animation_class = $settings['dethemekit_carousel_animation_list'];

		$animation = ! empty( $animation_class ) ? 'animated ' . $animation_class : 'null';

		$tablet_breakpoint = ! empty( $settings['dethemekit_carousel_tablet_breakpoint'] ) ? $settings['dethemekit_carousel_tablet_breakpoint'] : 1025;

		$mobile_breakpoint = ! empty( $settings['dethemekit_carousel_mobile_breakpoint'] ) ? $settings['dethemekit_carousel_mobile_breakpoint'] : 768;

		$carousel_settings = array(
			'vertical'       => $vertical,
			'slidesToScroll' => $slides_scroll,
			'slidesToShow'   => $slides_show,
			'infinite'       => $infinite,
			'speed'          => $speed,
			'fade'           => $fade,
			'autoplay'       => $autoplay,
			'autoplaySpeed'  => $autoplay_speed,
			'draggable'      => $draggable,
			'touchMove'      => $touch_move,
			'rtl'            => $rtl,
			'adaptiveHeight' => $adaptive_height,
			'pauseOnHover'   => $pause_hover,
			'centerMode'     => $center_mode,
			'centerPadding'  => $center_padding,
			'arrows'         => $arrows,
			'dots'           => $dots,
			'slidesDesk'     => $slides_on_desk,
			'slidesTab'      => $slides_on_tabs,
			'slidesMob'      => $slides_on_mob,
			// 'animation'      => $animation,
			'tabletBreak'    => $tablet_breakpoint,
			'mobileBreak'    => $mobile_breakpoint,
			'navigation'     => 'repeater' === $content_type ? $custom_navigation : array(),
		);

		$this->add_render_attribute( 'carousel', 'id', 'dethemekit-carousel-wrapper-' . esc_attr( $this->get_id() ) );

		$this->add_render_attribute(
			'carousel',
			'class',
			array(
				'dethemekit-carousel-wrapper',
				'carousel-wrapper-' . esc_attr( $this->get_id() ),
				$extra_class,
				$dir,
			)
		);

		if ( 'yes' === $settings['dethemekit_carousel_dot_navigation_show'] ) {
			$this->add_render_attribute( 'carousel', 'class', 'dethemekit-carousel-dots-' . $settings['dethemekit_carousel_dot_position'] );

		}

		if ( 'yes' === $settings['dethemekit_carousel_fade'] && 'yes' === $settings['dethemekit_carousel_zoom'] ) {
			$this->add_render_attribute( 'carousel', 'class', 'dethemekit-carousel-scale' );
		}

		if ( ! isset($settings['dethemekit_carousel_parent']) && ! isset($settings['dethemekit_carousel_child'])){
			$this->add_render_attribute( 'carousel', 'class', 'dethemekit_parent_'.$settings['dethemekit_carousel_parent_name'] );
		}


		$this->add_render_attribute( 'carousel', 'data-settings', wp_json_encode( $carousel_settings ) );


		
		?>

		<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'carousel' ) ); ?>>
			<?php if ( 'yes' === $settings['dethemekit_carousel_dot_navigation_show'] ) { ?>
				<div class="dethemekit-carousel-nav-dot">
				<?php if ( 'yes' !== $settings['custom_pagination_icon'] ) { ?>
					<i class="<?php echo esc_attr( $custom_paging ); ?>" aria-hidden="true"></i>
					<?php
				} else {
					Icons_Manager::render_icon( $settings['custom_pagination_icon_select'], array( 'aria-hidden' => 'true' ) );
				}
				?>
				</div>
			<?php } ?>
			<?php if ( 'yes' === $settings['dethemekit_carousel_navigation_show'] ) { ?>
				<div class="dethemekit-carousel-nav-arrow-prev">
					<a type="button" data-role="none" class="<?php echo esc_attr( $vertical_alignment ); ?> carousel-prev" aria-label="Previous" role="button">
					<?php if ( 'yes' !== $settings['custom_left_arrow'] ) { ?>
							<i class="<?php echo esc_attr( $icon_prev_class ); ?>" aria-hidden="true"></i>
						<?php
					} else {
						Icons_Manager::render_icon( $settings['custom_left_arrow_select'], array( 'aria-hidden' => 'true' ) );
					}
					?>
					</a>
				</div>
				<div class="dethemekit-carousel-nav-arrow-next">
					<a type="button" data-role="none" class="<?php echo esc_attr( $vertical_alignment ); ?> carousel-next" aria-label="Next" role="button">
					<?php if ( 'yes' !== $settings['custom_right_arrow'] ) { ?>
							<i class="<?php echo esc_attr( $icon_next_class ); ?>" aria-hidden="true"></i>
						<?php
					} else {
						Icons_Manager::render_icon( $settings['custom_right_arrow_select'], array( 'aria-hidden' => 'true' ) );
					}
					?>
					</a>
				</div>
			<?php } ?>
			<div id="dethemekit-carousel-<?php echo esc_attr( $this->get_id() ); ?>" class="dethemekit-carousel-inner">
				<?php
				foreach ( $templates as $template_title ) :
					if ( ! empty( $template_title ) ) :
						?>
						<div class="dethemekit-carousel-template item-wrapper">
							<?php echo $this->getTemplateInstance()->get_template_content( $template_title ); ?>
						</div>
						<?php
					endif;
				endforeach;
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render Carousel widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function content_template() {

		?>

		<#

			var vertical        = 'vertical' === settings.dethemekit_carousel_slider_type ? true : false,
				slidesOnDesk    = settings.dethemekit_carousel_responsive_desktop,
				slidesToScroll  = 1,
				iconNextClass   = '',
				iconPrevClass   = '',
				dotIcon         = '',
				verticalAlignment= '';

			if( 'all' === settings.dethemekit_carousel_slides_to_show ) {
				slidesToScroll = '' !== slidesOnDesk ? slidesOnDesk : 1;
			} else {
				slidesToScroll = 1;
			}

			var slidesToShow    = '' !== slidesOnDesk ? slidesOnDesk : 1,
				slidesOnTabs    = settings.dethemekit_carousel_responsive_tabs,
				slidesOnMob     = settings.dethemekit_carousel_responsive_mobile;

			if( '' === settings.dethemekit_carousel_responsive_tabs ) {
				slidesOnTabs = slidesOnDesk;
			}

			if( '' === settings.dethemekit_carousel_responsive_mobile ) {
				slidesOnMob = slidesOnDesk;
			}

			var infinite    = settings.dethemekit_carousel_loop === 'yes' ? true : false,
				fade        = settings.dethemekit_carousel_fade === 'yes' ? true : false,
				speed       = '' !== settings.dethemekit_carousel_speed ? settings.dethemekit_carousel_speed : '',
				autoplay    = settings.dethemekit_carousel_autoplay === 'yes' ? true : false,
				autoplaySpeed = '' !== settings.dethemekit_carousel_autoplay_speed ? settings.dethemekit_carousel_autoplay_speed : '',
				draggable   = settings.dethemekit_carousel_draggable_effect === 'yes' ? true  : false,
				touchMove   = settings.dethemekit_carousel_touch_move === 'yes' ? true : false,
				dir         = '',
				rtl         = false;

			if( 'yes' === settings.dethemekit_carousel_RTL_Mode ) {
				rtl = true;
				dir = 'dir="rtl"';
			}

			var adaptiveHeight  = 'yes' === settings.dethemekit_carousel_adaptive_height ? true : false,
				pauseOnHover    = 'yes' === settings.dethemekit_carousel_pausehover ? true : false,
				centerMode      = 'yes' === settings.dethemekit_carousel_center_mode ? true : false,
				centerPadding   = '' !== settings.dethemekit_carousel_space_btw_items ? settings.dethemekit_carousel_space_btw_items + "px" : '';

			// Navigation arrow setting setup
			if( 'yes' === settings.dethemekit_carousel_navigation_show ) {

				var arrows = true;

				if( 'vertical' === settings.dethemekit_carousel_slider_type ) {
					verticalAlignment = "ver-carousel-arrow";
				} else {
					verticalAlignment = "carousel-arrow";
				}

				if( 'vertical' === settings.dethemekit_carousel_slider_type ) {

					if ( 'yes' !== settings.custom_left_arrow ) {
						var iconPrev = settings.dethemekit_carousel_arrow_icon_prev_ver;

						if( iconPrev === 'left_arrow_bold' ) {
							iconPrevClass = 'fas fa-arrow-up';
						}
						if( iconPrev === 'left_arrow_long' ) {
							iconPrevClass  = 'fas fa-long-arrow-alt-up';
						}
						if( iconPrev === 'left_arrow_long_circle' ) {
							iconPrevClass  = 'fas fa-arrow-circle-up';
						}
						if( iconPrev === 'left_arrow_angle' ) {
							iconPrevClass  = 'fas fa-angle-up';
						}
						if( iconPrev === 'left_arrow_chevron' ) {
							iconPrevClass  = 'fas fa-chevron-up';
						}
					}

					if ( 'yes' !== settings.custom_right_arrow ) {
						var iconNext = settings.dethemekit_carousel_arrow_icon_next_ver;

						if( iconNext === 'right_arrow_bold' ) {
							iconNextClass = 'fas fa-arrow-down';
						}
						if( iconNext === 'right_arrow_long' ) {
							iconNextClass = 'fas fa-long-arrow-alt-down';
						}
						if( iconNext === 'right_arrow_long_circle' ) {
							iconNextClass = 'fas fa-arrow-circle-down';
						}
						if( iconNext === 'right_arrow_angle' ) {
							iconNextClass = 'fas fa-angle-down';
						}
						if( iconNext === 'right_arrow_chevron' ) {
							iconNextClass = 'fas fa-chevron-down';
						}
					}

				} else {

					if ( 'yes' !== settings.custom_left_arrow ) {
						var iconPrev = settings.dethemekit_carousel_arrow_icon_prev;

						if( iconPrev === 'left_arrow_bold' ) {
							iconPrevClass = 'fas fa-arrow-left';
						}
						if( iconPrev === 'left_arrow_long' ) {
							iconPrevClass = 'fas fa-long-arrow-alt-left';
						}
						if( iconPrev === 'left_arrow_long_circle' ) {
							iconPrevClass = 'fas fa-arrow-circle-left';
						}
						if( iconPrev === 'left_arrow_angle' ) {
							iconPrevClass = 'fas fa-angle-left';
						}
						if( iconPrev === 'left_arrow_chevron' ) {
							iconPrevClass = 'fas fa-chevron-left';
						}
					}

					if ( 'yes' !== settings.custom_right_arrow ) {
						var iconNext = settings.dethemekit_carousel_arrow_icon_next;

						if( iconNext === 'right_arrow_bold' ) {
							iconNextClass = 'fas fa-arrow-right';
						}
						if( iconNext === 'right_arrow_long' ) {
							iconNextClass = 'fas fa-long-arrow-alt-right';
						}
						if( iconNext === 'right_arrow_long_circle' ) {
							iconNextClass = 'fas fa-arrow-circle-right';
						}
						if( iconNext === 'right_arrow_angle' ) {
							iconNextClass = 'fas fa-angle-right';
						}
						if( iconNext === 'right_arrow_chevron' ) {
							iconNextClass = 'fas fa-chevron-right';
						}
					}
				}

			} else {
				var arrows = false;
			}

			if( 'yes' === settings.dethemekit_carousel_dot_navigation_show  ) {

				var dots =  true;
				if ( 'yes' !== settings.custom_pagination_icon ) {
					if( 'square_white' === settings.dethemekit_carousel_dot_icon ) {
						dotIcon = 'far fa-square';
					}
					if( 'square_black' === settings.dethemekit_carousel_dot_icon ) {
						dotIcon = 'fas fa-square';
					}
					if( 'circle_white' === settings.dethemekit_carousel_dot_icon ) {
						dotIcon = 'fas fa-circle';
					}
					if( 'circle_thin' === settings.dethemekit_carousel_dot_icon ) {
						dotIcon = 'far fa-circle';
					}
					var customPaging = dotIcon;
				}

			} else {

				var dots =  false;

			}
			var extraClass = '' !== settings.dethemekit_carousel_extra_class  ? ' ' + settings.dethemekit_carousel_extra_class : '';

			var animationClass  = settings.dethemekit_carousel_animation_list;
			var animation       = '' !== animationClass ? 'animated ' + animationClass : 'null';

			var tabletBreakpoint = '' !== settings.dethemekit_carousel_tablet_breakpoint ? settings.dethemekit_carousel_tablet_breakpoint : 1025;

			var mobileBreakpoint = '' !== settings.dethemekit_carousel_mobile_breakpoint ? settings.dethemekit_carousel_mobile_breakpoint : 768;

			var templates = [],
				contentType = settings.dethemekit_carousel_content_type;

			if( 'select' === contentType ) {

				templates = settings.dethemekit_carousel_slider_content;

			} else {

				var customNavigation = [];
				_.each( settings.dethemekit_carousel_templates_repeater, function( template ) {

					templates.push( template.dethemekit_carousel_repeater_item );
					customNavigation.push( template.custom_navigation );

				} );

			}

			var carouselSettings = {};

			carouselSettings.vertical       = vertical;
			carouselSettings.slidesToScroll = slidesToScroll;
			carouselSettings.slidesToShow   = slidesToShow;
			carouselSettings.infinite       = infinite;
			carouselSettings.speed          = speed;
			carouselSettings.fade           = fade;
			carouselSettings.autoplay       = autoplay;
			carouselSettings.autoplaySpeed  = autoplaySpeed;
			carouselSettings.draggable      = draggable;
			carouselSettings.touchMove      = touchMove;
			carouselSettings.rtl            = rtl;
			carouselSettings.adaptiveHeight = adaptiveHeight;
			carouselSettings.pauseOnHover   = pauseOnHover;
			carouselSettings.centerMode     = centerMode;
			carouselSettings.centerPadding  = centerPadding;
			carouselSettings.arrows         = arrows;
			carouselSettings.dots           = dots;
			carouselSettings.slidesDesk     = slidesOnDesk;
			carouselSettings.slidesTab      = slidesOnTabs;
			carouselSettings.slidesMob      = slidesOnMob;
			carouselSettings.animation      = animation;
			carouselSettings.tabletBreak    = tabletBreakpoint;
			carouselSettings.mobileBreak    = mobileBreakpoint;
			carouselSettings.navigation	    = 'repeater' === contentType ? customNavigation : [];


			view.addRenderAttribute( 'carousel', 'id', 'dethemekit-carousel-wrapper-' + view.getID() );

			view.addRenderAttribute( 'carousel', 'class', [
				'dethemekit-carousel-wrapper',
				'carousel-wrapper-' + view.getID(),
				extraClass,
				dir
			] );

			if( 'yes' === settings.dethemekit_carousel_dot_navigation_show ) {
				view.addRenderAttribute( 'carousel', 'class', 'dethemekit-carousel-dots-' + settings.dethemekit_carousel_dot_position );
			}

			if( 'yes' === settings.dethemekit_carousel_fade && 'yes' === settings.dethemekit_carousel_zoom ) {
				view.addRenderAttribute( 'carousel', 'class', 'dethemekit-carousel-scale' );
			}

			view.addRenderAttribute( 'carousel', 'data-settings', JSON.stringify( carouselSettings ) );

			view.addRenderAttribute( 'carousel-inner', 'id', 'dethemekit-carousel-' + view.getID() );
			view.addRenderAttribute( 'carousel-inner', 'class', 'dethemekit-carousel-inner' );

		#>

		<div {{{ view.getRenderAttributeString('carousel') }}}>
			<# if ( 'yes' === settings.dethemekit_carousel_dot_navigation_show ) { #>
				<div class="dethemekit-carousel-nav-dot">
				<# if ( 'yes' !== settings.custom_pagination_icon ) { #>
					<i class="{{customPaging}}" aria-hidden="true"></i>
				<# } else {
					var iconHTML = elementor.helpers.renderIcon( view, settings.custom_pagination_icon_select, {
						'aria-hidden': true
						}, 'i' , 'object' );
				#>
					{{{ iconHTML.value }}}
				<# } #>
				</div>
			<# } #>
			<# if ( 'yes' === settings.dethemekit_carousel_navigation_show ) { #>
				<div class="dethemekit-carousel-nav-arrow-prev">
					<a type="button" data-role="none" class="{{verticalAlignment}} carousel-prev" aria-label="Previous" role="button">
					<# if ( 'yes' !== settings.custom_left_arrow ) { #>
							<i class="{{iconPrevClass}}" aria-hidden="true"></i>
					<# } else {
						var iconHTML = elementor.helpers.renderIcon( view, settings.custom_left_arrow_select, {
						'aria-hidden': true
						}, 'i' , 'object' );
					#>
						{{{ iconHTML.value }}}
					<# } #>
					</a>
				</div>
				<div class="dethemekit-carousel-nav-arrow-next">
					<a type="button" data-role="none" class="{{verticalAlignment}} carousel-next" aria-label="Next" role="button">
					<# if ( 'yes' !== settings.custom_right_arrow ) { #>
							<i class="{{iconNextClass}}" aria-hidden="true"></i>
					<# } else {
						var iconHTML = elementor.helpers.renderIcon( view, settings.custom_right_arrow_select, {
						'aria-hidden': true
						}, 'i' , 'object' );
					#>
						{{{ iconHTML.value }}}
					<# } #>
					</a>
				</div>
			<# } #>
			<div {{{ view.getRenderAttributeString('carousel-inner') }}}>
				<# _.each( templates, function( templateID ) { 
					if( templateID ) { 
				#>
					<div class="item-wrapper" data-template="{{templateID}}"></div>
				<#  } 
				} ); #>
			</div>
		</div>

		<?php

	}
}
