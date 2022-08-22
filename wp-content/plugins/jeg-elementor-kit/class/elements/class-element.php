<?php
/**
 * Jeg Elementor Kit Class
 *
 * @package jeg-elementor-kit
 * @author Jegstudio
 * @since 1.0.0
 */

namespace Jeg\Elementor_Kit\Elements;

use Elementor\Controls_Manager;
use Jeg\Element\Element as Jeg_Element;

/**
 * Class Element
 *
 * @package Jeg\Elementor_Kit
 */
class Element {
	/**
	 * Element Manager
	 *
	 * @var Elements_Manager
	 */
	public $manager;

	/**
	 * Class instance
	 *
	 * @var Element
	 */
	private static $instance;

	/**
	 * Module constructor.
	 */
	private function __construct() {
		$this->setup_init();
		$this->setup_hook();
	}

	/**
	 * Setup Classes
	 */
	private function setup_init() {
		$this->manager = Jeg_Element::instance()->manager;
	}

	/**
	 * Setup Hooks
	 */
	private function setup_hook() {
		add_filter( 'jeg_register_elements', array( $this, 'register_element' ) );
		add_action( 'elementor/element/common/_section_style/after_section_end', array( $this, 'add_widget_options' ), 10 );
		add_action( 'elementor/element/column/section_advanced/after_section_end', array( $this, 'add_column_options' ), 10, 2 );
		add_action( 'elementor/element/section/section_advanced/after_section_end', array( $this, 'add_section_options' ), 10, 2 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 99 );
	}

	/**
	 * Get class instance
	 *
	 * @return Element
	 */
	public static function instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Register all elements
	 *
	 * @param array $elements Elements.
	 *
	 * @return array
	 */
	public function register_element( $elements ) {
		$element_config = get_option( 'jkit_elements_enable', array() );

		foreach ( $this->list_elements() as $item ) {
			$item_key = 'jkit_' . strtolower( $item );

			if ( ! isset( $element_config[ $item_key ] ) || $element_config[ $item_key ] ) {
				$namespace             = '\Jeg\Elementor_Kit\Elements';
				$elements[ $item_key ] = array(
					'option'    => $namespace . '\Options\\' . $item . '_Option',
					'view'      => $namespace . '\Views\\' . $item . '_View',
					'elementor' => $namespace . '\Elementor\\' . $item . '_Elementor',
				);
			}
		}

		return $elements;
	}

	/**
	 * List of elements
	 *
	 * @return array
	 */
	public function list_elements() {
		$default = array(
			'Nav_Menu',
			'Off_Canvas',
			'Search',
			'Icon_Box',
			'Image_Box',
			'Fun_Fact',
			'Progress_Bar',
			'Client_Logo',
			'Testimonials',
			'Accordion',
			'Gallery',
			'Team',
			'Pie_Chart',
			'Portfolio_Gallery',
			'Tabs',
			'Animated_Text',
			'Heading',
			'Countdown',
			'Button',
			'Dual_Button',
			'Video_Button',
			'Social_Share',
			'Post_Block',
			'Post_List',
			'Category_List',
			'Feature_List',
			'Contact_Form_7',
			'Mailchimp',
			'Post_Title',
			'Post_Featured_Image',
			'Post_Comment',
			'Post_Terms',
			'Post_Excerpt',
			'Post_Date',
			'Post_Author',
			'Post_Content',
			'Banner',
		);

		if ( class_exists( 'WooCommerce' ) ) {
			$woo_elements = array(
				'Product_Grid',
				'Product_Carousel',
				'Product_Categories',
			);

			$default = array_merge( $default, $woo_elements );
		}

		return apply_filters( 'jkit_list_elements', $default );
	}

	/**
	 * Add custom option to elementor widgets
	 *
	 * @param \Elementor\Element_Base $element The edited element.
	 */
	public function add_widget_options( $element ) {
		$element->start_controls_section(
			'jkit_transform_section',
			array(
				'label'     => '<i class="jkit-option-additional"></i> ' . esc_html__( 'Transform', 'jeg-elementor-kit' ),
				'tab'       => Controls_Manager::TAB_ADVANCED,
				'condition' => array(
					'_transform_rotate_popover!' => 'transform',
				),
			)
		);

		$element->add_responsive_control(
			'jkit_transform_rotate',
			array(
				'label'       => esc_html__( 'Rotate', 'jeg-elementor-kit' ),
				'type'        => Controls_Manager::SLIDER,
				'range'       => array(
					'px' => array(
						'min'  => 0,
						'max'  => 360,
						'step' => 1,
					),
				),
				'selectors'   => array(
					'{{WRAPPER}}:not(.e-transform) > .elementor-widget-container' => '-moz-transform: rotate({{SIZE}}deg); -webkit-transform: rotate({{SIZE}}deg); -o-transform: rotate({{SIZE}}deg); -ms-transform: rotate({{SIZE}}deg); transform: rotate({{SIZE}}deg);',
					'{{WRAPPER}}.e-transform > .elementor-widget-container' => '--e-transform-rotateZ: {{SIZE}}deg;',
				),
				'condition'   => array(
					'_transform_rotate_popover!' => 'transform',
				),
				'description' => esc_html__( 'Since Elementor version 3.5.0, it has its own Transform settings. When you use Transform Rotate from Elementor, this setting will be hidden.', 'jeg-elementor-kit' ),
			)
		);

		$element->end_controls_section();

		$element->start_controls_section(
			'jkit_glass_blur_section',
			array(
				'label' => '<i class="jkit-option-additional"></i> ' . esc_html__( 'Glass Blur Effect', 'jeg-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			)
		);

		$element->add_responsive_control(
			'jkit_glass_blur_level',
			array(
				'label'       => esc_html__( 'Blur', 'jeg-elementor-kit' ),
				'type'        => Controls_Manager::SLIDER,
				'range'       => array(
					'px' => array(
						'min'  => 0,
						'max'  => 20,
						'step' => 0.1,
					),
				),
				'description' => esc_html__( 'The blur effect will be set on the widget container. Make sure to set background to transparent to see the blur effect.', 'jeg-elementor-kit' ),
				'selectors'   => array(
					'{{WRAPPER}} .elementor-widget-container::before' => 'content: ""; width: 100%; height: 100%; position: absolute; left: 0; top: 0; -webkit-backdrop-filter: blur({{SIZE}}{{UNIT}}); backdrop-filter: blur({{SIZE}}{{UNIT}});',
				),
			)
		);

		$element->end_controls_section();
	}

	/**
	 * Add custom option to elementor columns
	 *
	 * @param \Elementor\Element_Base $column The edited element.
	 * @param array @args The         $args that sent to $element->start_controls_section.
	 */
	public function add_column_options( $column, $args ) {
		$column->start_controls_section(
			'jkit_glass_blur_section',
			array(
				'label' => '<i class="jkit-option-additional"></i> ' . esc_html__( 'Glass Blur Effect', 'jeg-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			)
		);

		$column->add_responsive_control(
			'jkit_glass_blur_level',
			array(
				'label'     => esc_html__( 'Blur', 'jeg-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 20,
						'step' => 0.1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} > .elementor-element-populated::before' => 'content: ""; width: 100%; height: 100%; position: absolute; left: 0; top: 0; -webkit-backdrop-filter: blur({{SIZE}}{{UNIT}}); backdrop-filter: blur({{SIZE}}{{UNIT}});',
				),
			)
		);

		$column->end_controls_section();

		$column->start_controls_section(
			'jkit_sticky_element_section',
			array(
				'label' => '<i class="jkit-option-additional"></i> ' . esc_html__( 'Sticky Element', 'jeg-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			)
		);

		$column->add_control(
			'jkit_sticky_section',
			array(
				'label'        => esc_html__( 'Enable Sticky Element', 'jeg-elementor-kit' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'enabled',
				'selectors'    => array(
					'{{WRAPPER}}.elementor-column.jkit-sticky-element--enabled' => 'position: -webkit-sticky; position: sticky; height: fit-content;',
				),
				'prefix_class' => 'jkit-sticky-element--',
			)
		);

		$column->add_control(
			'jkit_sticky_trigger',
			array(
				'label'        => esc_html__( 'Sticky Trigger', 'jeg-elementor-kit' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'down',
				'options'      => array(
					'up'   => esc_html__( 'On Scroll Up', 'jeg-elementor-kit' ),
					'down' => esc_html__( 'On Scroll Down', 'jeg-elementor-kit' ),
					'both' => esc_html__( 'On Both', 'jeg-elementor-kit' ),
				),
				'prefix_class' => 'jkit-sticky-element-on--',
				'condition'    => array(
					'jkit_sticky_section' => 'enabled',
				),
			)
		);

		$column->add_control(
			'jkit_sticky_top_position',
			array(
				'label'              => esc_html__( 'Top Position', 'jeg-elementor-kit' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => array( 'px', '%' ),
				'range'              => array(
					'px' => array(
						'min' => 0,
						'max' => 500,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'            => array(
					'size' => 0,
					'unit' => 'px',
				),
				'selectors'          => array(
					'{{WRAPPER}}.elementor-column.jkit-sticky-element--enabled.sticky-pinned.jkit-sticky-element-on--down' => 'top: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.elementor-column.jkit-sticky-element--enabled.sticky-pinned.jkit-sticky-element-on--both' => 'top: {{SIZE}}{{UNIT}};',
				),
				'condition'          => array(
					'jkit_sticky_section' => 'enabled',
					'jkit_sticky_trigger' => array( 'down', 'both' ),
				),
				'frontend_available' => true,
			)
		);

		$column->add_control(
			'jkit_sticky_bottom_position',
			array(
				'label'              => esc_html__( 'Bottom Position', 'jeg-elementor-kit' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => array( 'px', '%' ),
				'range'              => array(
					'px' => array(
						'min' => 0,
						'max' => 500,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'            => array(
					'size' => 0,
					'unit' => 'px',
				),
				'selectors'          => array(
					'{{WRAPPER}}.elementor-column.jkit-sticky-element--enabled.sticky-pinned.jkit-sticky-element-on--up' => 'bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.elementor-column.jkit-sticky-element--enabled.sticky-pinned.jkit-sticky-element-on--both' => 'bottom: {{SIZE}}{{UNIT}};',
				),
				'condition'          => array(
					'jkit_sticky_section' => 'enabled',
					'jkit_sticky_trigger' => array( 'up', 'both' ),
				),
				'frontend_available' => true,
			)
		);

		$column->add_control(
			'jkit_sticky_zindex',
			array(
				'label'     => esc_html__( 'Z-Index', 'jeg-elementor-kit' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 100,
				'min'       => 0,
				'max'       => 999,
				'step'      => 1,
				'selectors' => array(
					'{{WRAPPER}}.elementor-column.jkit-sticky-element--enabled' => 'z-index: {{VALUE}};',
				),
				'condition' => array(
					'jkit_sticky_section' => 'enabled',
				),
			)
		);

		$column->end_controls_section();
	}

	/**
	 * Add custom option to elementor sections
	 *
	 * @param \Elementor\Element_Base $section The edited element.
	 * @param array                   $args The args that sent to $element->start_controls_section.
	 */
	public function add_section_options( $section, $args ) {
		$section->start_controls_section(
			'jkit_glass_blur_section',
			array(
				'label' => '<i class="jkit-option-additional"></i> ' . esc_html__( 'Glass Blur Effect', 'jeg-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			)
		);

		$section->add_responsive_control(
			'jkit_glass_blur_level',
			array(
				'label'     => esc_html__( 'Blur', 'jeg-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 20,
						'step' => 0.1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}::before' => 'content: ""; width: 100%; height: 100%; position: absolute; left: 0; top: 0; -webkit-backdrop-filter: blur({{SIZE}}{{UNIT}}); backdrop-filter: blur({{SIZE}}{{UNIT}});',
				),
			)
		);

		$section->end_controls_section();

		$section->start_controls_section(
			'jkit_sticky_element_section',
			array(
				'label' => '<i class="jkit-option-additional"></i> ' . esc_html__( 'Sticky Element', 'jeg-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			)
		);

		$section->add_control(
			'jkit_sticky_section',
			array(
				'label'        => esc_html__( 'Enable Sticky Element', 'jeg-elementor-kit' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'enabled',
				'prefix_class' => 'jkit-sticky-element--',
			)
		);

		$section->add_control(
			'jkit_sticky_trigger',
			array(
				'label'        => esc_html__( 'Sticky Trigger', 'jeg-elementor-kit' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'down',
				'options'      => array(
					'up'   => esc_html__( 'On Scroll Up', 'jeg-elementor-kit' ),
					'down' => esc_html__( 'On Scroll Down', 'jeg-elementor-kit' ),
					'both' => esc_html__( 'On Both', 'jeg-elementor-kit' ),
				),
				'prefix_class' => 'jkit-sticky-element-on--',
				'condition'    => array(
					'jkit_sticky_section' => 'enabled',
				),
			)
		);

		$section->add_control(
			'jkit_sticky_position',
			array(
				'label'        => esc_html__( 'Sticky Position', 'jeg-elementor-kit' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'sticky',
				'options'      => array(
					'sticky' => esc_html__( 'Sticky', 'jeg-elementor-kit' ),
					'fixed'  => esc_html__( 'Fixed', 'jeg-elementor-kit' ),
				),
				'prefix_class' => 'jkit-sticky-position--',
				'condition'    => array(
					'jkit_sticky_section'  => 'enabled',
					'jkit_sticky_trigger!' => 'both',
				),
			)
		);

		$section->add_control(
			'jkit_sticky_top_position',
			array(
				'label'              => esc_html__( 'Top Position', 'jeg-elementor-kit' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => array( 'px', '%' ),
				'range'              => array(
					'px' => array(
						'min' => 0,
						'max' => 500,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'            => array(
					'size' => 0,
					'unit' => 'px',
				),
				'selectors'          => array(
					'{{WRAPPER}}.elementor-section.jkit-sticky-element--enabled.sticky-pinned.jkit-sticky-element-on--down' => 'top: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.elementor-section.jkit-sticky-element--enabled.sticky-pinned.jkit-sticky-element-on--both' => 'top: {{SIZE}}{{UNIT}};',
				),
				'condition'          => array(
					'jkit_sticky_section' => 'enabled',
					'jkit_sticky_trigger' => array( 'down', 'both' ),
				),
				'frontend_available' => true,
			)
		);

		$section->add_control(
			'jkit_sticky_bottom_position',
			array(
				'label'              => esc_html__( 'Bottom Position', 'jeg-elementor-kit' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => array( 'px', '%' ),
				'range'              => array(
					'px' => array(
						'min' => 0,
						'max' => 500,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'            => array(
					'size' => 0,
					'unit' => 'px',
				),
				'selectors'          => array(
					'{{WRAPPER}}.elementor-section.jkit-sticky-element--enabled.sticky-pinned.jkit-sticky-element-on--up' => 'bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.elementor-section.jkit-sticky-element--enabled.sticky-pinned.jkit-sticky-element-on--both' => 'bottom: {{SIZE}}{{UNIT}};',
				),
				'condition'          => array(
					'jkit_sticky_section' => 'enabled',
					'jkit_sticky_trigger' => array( 'up', 'both' ),
				),
				'frontend_available' => true,
			)
		);

		$section->add_control(
			'jkit_sticky_background_color',
			array(
				'label'         => esc_html__( 'Background Color', 'jeg-elementor-kit' ),
				'type'          => Controls_Manager::COLOR,
				'selectors'     => array(
					'{{WRAPPER}}.elementor-section.jkit-sticky-element--enabled.sticky-pinned' => 'background-color: {{VALUE}};',
				),
				'condition'     => array(
					'jkit_sticky_section' => 'enabled',
				),
				'hide_in_inner' => true,
			)
		);

		$section->add_control(
			'jkit_sticky_transition',
			array(
				'label'         => esc_html__( 'Transition', 'jeg-elementor-kit' ),
				'type'          => Controls_Manager::SLIDER,
				'default'       => array(
					'size' => 0.1,
				),
				'range'         => array(
					'px' => array(
						'max'  => 3,
						'step' => 0.1,
					),
				),
				'selectors'     => array(
					'{{WRAPPER}}.elementor-section.jkit-sticky-element--enabled.sticky-pinned' => 'transition: margin {{SIZE}}s, padding {{SIZE}}s, background {{SIZE}}s, box-shadow {{SIZE}}s',
					'{{WRAPPER}}.elementor-section.jkit-sticky-element--enabled' => 'transition: margin {{SIZE}}s, padding {{SIZE}}s, background {{SIZE}}s, box-shadow {{SIZE}}s',
				),
				'condition'     => array(
					'jkit_sticky_section'           => 'enabled',
					'jkit_sticky_background_color!' => '',
				),
				'hide_in_inner' => true,
			)
		);

		$section->add_control(
			'jkit_sticky_zindex',
			array(
				'label'     => esc_html__( 'Z-Index', 'jeg-elementor-kit' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 100,
				'min'       => 0,
				'max'       => 9999,
				'step'      => 1,
				'selectors' => array(
					'{{WRAPPER}}.elementor-section.jkit-sticky-element--enabled' => 'z-index: {{VALUE}};',
				),
				'condition' => array(
					'jkit_sticky_section' => 'enabled',
				),
			)
		);

		$section->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'jkit_sticky_shadow',
				'label'     => esc_html__( 'Box Shadow', 'jeg-elementor-kit' ),
				'selector'  => '{{WRAPPER}}.elementor-section.jkit-sticky-element--enabled.sticky-pinned',
				'condition' => array(
					'jkit_sticky_section' => 'enabled',
				),
			)
		);

		$section->end_controls_section();
	}

	/**
	 * Enqueue The Script of Section
	 *
	 * @param \Elementor\Controls_Stack $class The edited element.
	 * @param string                    $section_id The ID of Section.
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'jkit-sticky-element' );
	}
}
