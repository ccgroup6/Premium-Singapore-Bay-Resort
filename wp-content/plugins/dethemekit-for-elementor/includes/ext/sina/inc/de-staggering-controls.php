<?php
namespace De_Sina_Extension;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Css_Filter;

/**
 * De_Staggering_Controls Class for extends controls
 *
 * @since 3.0.1
 */
class De_Staggering_Controls{
	/**
	 * Instance
	 *
	 * @since 3.1.13
	 * @var De_Staggering_Controls The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 3.1.13
	 * @return De_Staggering_Controls An Instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct() {
		add_action('elementor/element/common/_section_style/after_section_end', [$this, 'register_controls']);
		add_action('elementor/element/column/section_advanced/after_section_end', [$this, 'column_register_controls']);
		add_action('elementor/element/section/section_advanced/after_section_end', [$this, 'section_register_controls']);

		add_filter( 'elementor/widget/render_content', [$this, 'render_template_content'], 10, 2 );
		add_filter( 'elementor/widget/print_template', [$this, 'update_template_content'], 10, 2 );
	}

	public function render_template_content($template,$widget) {
		// $settings = $widget->get_settings_for_display();

		// $widget->add_render_attribute(
		// 	'module',
		// 	[
		// 		'data-anim' => '',
		// 		'data-threshold' => '0.1'
		// 	]
		// );

		// $widget->add_render_attribute(
		// 	'content',
		// 	[
		// 		'data-animated' => '',
		// 	]
		// );

		// $template = '<div ' . $widget->get_render_attribute_string( 'module' ) . '>' 
		// 	. '<div ' . $widget->get_render_attribute_string( 'content' ) . '>' 
		// 	. $template 
		// 	. '</div></div>';

		// if ( 'image' === $widget->get_name() ) {
		// 	$settings = $widget->get_settings_for_display();
		// 	$template = '<div class="block-revealer__content" style="opacity: 1;">' . $template . '</div><div class="block-revealer__element" style="opacity: 1;"></div>';
		// }

		return $template;
	}

	public function update_template_content($template,$widget) {
		// if ( 'image' === $widget->get_name() ) {
		// 	$template = '<div class="block-revealer__content" style="opacity: 1;">' . $template . '</div><div class="block-revealer__element"></div>';
		// }

		return $template;
	}

	public function register_controls($elems) {
		$elems->start_controls_section(
			'de_staggering_section',
			[
				'label' => __( 'De Staggering', 'detheme-kit' ),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);

		$elems->add_control(
			'de_staggering_animation',
			[
				'label' => '<strong>'.esc_html__( 'De Stagger Animation', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::SWITCHER,
				'prefix_class' => 'de_staggering_animation_',
			]
		);

		$elems->add_control(
			'de_staggering_child_initial_state',
			[
				'label' => '<strong>'.esc_html__( 'Initial State', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::SELECT,
				'options' => [
          'inherited' => esc_html__( 'Inherited', 'detheme-kit' ),
          'hidden' => esc_html__( 'Hidden', 'detheme-kit' ),
          'visible' => esc_html__( 'Visible', 'detheme-kit' ),
        ],
				'default' => 'inherited',
				'prefix_class' => 'de_staggering_child_initial_state_',
				'condition' => [ 'de_staggering_animation' => 'yes' ],
			]
		);

		$elems->add_control(
			'de_staggering_animation_mousehover',
			[
				'label' => '<strong>'.esc_html__( 'Animation on Mouse Hover', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::SELECT,
				'options' => [
                    'inherited' => esc_html__( 'inherited', 'detheme-kit' ),
                    'none' => esc_html__( 'none', 'detheme-kit' ),
                    'backInDown' => esc_html__( 'backInDown', 'detheme-kit' ),
                    'backOutDown' => esc_html__( 'backOutDown', 'detheme-kit' ),
                    'backInLeft' => esc_html__( 'backInLeft', 'detheme-kit' ),
                    'backOutLeft' => esc_html__( 'backOutLeft', 'detheme-kit' ),
                    'backInRight' => esc_html__( 'backInRight', 'detheme-kit' ),
                    'backOutRight' => esc_html__( 'backOutRight', 'detheme-kit' ),
                    'backInUp' => esc_html__( 'backInUp', 'detheme-kit' ),
                    'backOutUp' => esc_html__( 'backOutUp', 'detheme-kit' ),
                    'bounce' => esc_html__( 'bounce', 'detheme-kit' ),
                    'bounceIn' => esc_html__( 'bounceIn', 'detheme-kit' ),
                    'bounceOut' => esc_html__( 'bounceOut', 'detheme-kit' ),
                    'bounceInDown' => esc_html__( 'bounceInDown', 'detheme-kit' ),
                    'bounceOutDown' => esc_html__( 'bounceOutDown', 'detheme-kit' ),
                    'bounceInLeft' => esc_html__( 'bounceInLeft', 'detheme-kit' ),
                    'bounceOutLeft' => esc_html__( 'bounceOutLeft', 'detheme-kit' ),
                    'bounceInRight' => esc_html__( 'bounceInRight', 'detheme-kit' ),
                    'bounceOutRight' => esc_html__( 'bounceOutRight', 'detheme-kit' ),
                    'bounceInUp' => esc_html__( 'bounceInUp', 'detheme-kit' ),
                    'bounceOutUp' => esc_html__( 'bounceOutUp', 'detheme-kit' ),
                    'fadeIn' => esc_html__( 'fadeIn', 'detheme-kit' ),
                    'fadeOut' => esc_html__( 'fadeOut', 'detheme-kit' ),
					'fadeInDown' => esc_html__( 'fadeInDown', 'detheme-kit' ),
                    'fadeOutDown' => esc_html__( 'fadeOutDown', 'detheme-kit' ),
					'fadeInDownBig' => esc_html__( 'fadeInDownBig', 'detheme-kit' ),
                    'fadeOutDownBig' => esc_html__( 'fadeOutDownBig', 'detheme-kit' ),
					'fadeInLeft' => esc_html__( 'fadeInLeft', 'detheme-kit' ),
                    'fadeOutLeft' => esc_html__( 'fadeOutLeft', 'detheme-kit' ),
					'fadeInLeftBig' => esc_html__( 'fadeInLeftBig', 'detheme-kit' ),
                    'fadeOutLeftBig' => esc_html__( 'fadeOutLeftBig', 'detheme-kit' ),
					'fadeInRight' => esc_html__( 'fadeInRight', 'detheme-kit' ),
                    'fadeOutRight' => esc_html__( 'fadeOutRight', 'detheme-kit' ),
					'fadeInRightBig' => esc_html__( 'fadeInRightBig', 'detheme-kit' ),
                    'fadeOutRightBig' => esc_html__( 'fadeOutRightBig', 'detheme-kit' ),
					'fadeInUp' => esc_html__( 'fadeInUp', 'detheme-kit' ),
                    'fadeOutUp' => esc_html__( 'fadeOutUp', 'detheme-kit' ),
					'fadeInUpBig' => esc_html__( 'fadeInUpBig', 'detheme-kit' ),
                    'fadeOutUpBig' => esc_html__( 'fadeOutUpBig', 'detheme-kit' ),
					'fadeInTopLeft' => esc_html__( 'fadeInTopLeft', 'detheme-kit' ),
                    'fadeOutTopLeft' => esc_html__( 'fadeOutTopLeft', 'detheme-kit' ),
					'fadeInTopRight' => esc_html__( 'fadeInTopRight', 'detheme-kit' ),
                    'fadeOutTopRight' => esc_html__( 'fadeOutTopRight', 'detheme-kit' ),
					'fadeInBottomLeft' => esc_html__( 'fadeInBottomLeft', 'detheme-kit' ),
                    'fadeOutBottomLeft' => esc_html__( 'fadeOutBottomLeft', 'detheme-kit' ),
					'fadeInBottomRight' => esc_html__( 'fadeInBottomRight', 'detheme-kit' ),
                    'fadeOutBottomRight' => esc_html__( 'fadeOutBottomRight', 'detheme-kit' ),
                    'flash' => esc_html__( 'flash', 'detheme-kit' ),
                    'flip' => esc_html__( 'flip', 'detheme-kit' ),
                    'flipInX' => esc_html__( 'flipInX', 'detheme-kit' ),
                    'flipOutX' => esc_html__( 'flipOutX', 'detheme-kit' ),
                    'flipInY' => esc_html__( 'flipInY', 'detheme-kit' ),
                    'flipOutY' => esc_html__( 'flipOutY', 'detheme-kit' ),
                    'headShake' => esc_html__( 'headShake', 'detheme-kit' ),
                    'heartBeat' => esc_html__( 'heartBeat', 'detheme-kit' ),
                    'hinge' => esc_html__( 'hinge', 'detheme-kit' ),
                    'jackInTheBox' => esc_html__( 'jackInTheBox', 'detheme-kit' ),
                    'jello' => esc_html__( 'jello', 'detheme-kit' ),
                    'lightSpeedInRight' => esc_html__( 'lightSpeedInRight', 'detheme-kit' ),
                    'lightSpeedOutRight' => esc_html__( 'lightSpeedOutRight', 'detheme-kit' ),
                    'lightSpeedInLeft' => esc_html__( 'lightSpeedInLeft', 'detheme-kit' ),
                    'lightSpeedOutLeft' => esc_html__( 'lightSpeedOutLeft', 'detheme-kit' ),
                    'pulse' => esc_html__( 'pulse', 'detheme-kit' ),
                    'rollIn' => esc_html__( 'rollIn', 'detheme-kit' ),
                    'rollOut' => esc_html__( 'rollOut', 'detheme-kit' ),
                    'rotateIn' => esc_html__( 'rotateIn', 'detheme-kit' ),
                    'rotateOut' => esc_html__( 'rotateOut', 'detheme-kit' ),
                    'rotateInDownLeft' => esc_html__( 'rotateInDownLeft', 'detheme-kit' ),
                    'rotateOutDownLeft' => esc_html__( 'rotateOutDownLeft', 'detheme-kit' ),
                    'rotateInDownRight' => esc_html__( 'rotateInDownRight', 'detheme-kit' ),
                    'rotateOutDownRight' => esc_html__( 'rotateOutDownRight', 'detheme-kit' ),
                    'rotateInUpLeft' => esc_html__( 'rotateInUpLeft', 'detheme-kit' ),
                    'rotateOutUpLeft' => esc_html__( 'rotateOutUpLeft', 'detheme-kit' ),
                    'rotateInUpRight' => esc_html__( 'rotateInUpRight', 'detheme-kit' ),
                    'rotateOutUpRight' => esc_html__( 'rotateOutUpRight', 'detheme-kit' ),
                    'rubberBand' => esc_html__( 'rubberBand', 'detheme-kit' ),
                    'shakeX' => esc_html__( 'shakeX', 'detheme-kit' ),
                    'shakeY' => esc_html__( 'shakeY', 'detheme-kit' ),
                    'slideInDown' => esc_html__( 'slideInDown', 'detheme-kit' ),
                    'slideOutDown' => esc_html__( 'slideOutDown', 'detheme-kit' ),
                    'slideInLeft' => esc_html__( 'slideInLeft', 'detheme-kit' ),
                    'slideOutLeft' => esc_html__( 'slideOutLeft', 'detheme-kit' ),
                    'slideInRight' => esc_html__( 'slideInRight', 'detheme-kit' ),
                    'slideOutRight' => esc_html__( 'slideOutRight', 'detheme-kit' ),
                    'slideInUp' => esc_html__( 'slideInUp', 'detheme-kit' ),
                    'slideOutUp' => esc_html__( 'slideOutUp', 'detheme-kit' ),
                    'swing' => esc_html__( 'swing', 'detheme-kit' ),
                    'tada' => esc_html__( 'tada', 'detheme-kit' ),
                    'wobble' => esc_html__( 'wobble', 'detheme-kit' ),
                    'zoomIn' => esc_html__( 'zoomIn', 'detheme-kit' ),
                    'zoomOut' => esc_html__( 'zoomOut', 'detheme-kit' ),
                    'zoomInDown' => esc_html__( 'zoomInDown', 'detheme-kit' ),
                    'zoomOutDown' => esc_html__( 'zoomOutDown', 'detheme-kit' ),
                    'zoomInLeft' => esc_html__( 'zoomInLeft', 'detheme-kit' ),
                    'zoomOutLeft' => esc_html__( 'zoomOutLeft', 'detheme-kit' ),
                    'zoomInRight' => esc_html__( 'zoomInRight', 'detheme-kit' ),
                    'zoomOutRight' => esc_html__( 'zoomOutRight', 'detheme-kit' ),
                    'zoomInUp' => esc_html__( 'zoomInUp', 'detheme-kit' ),
                    'zoomOutUp' => esc_html__( 'zoomOutUp', 'detheme-kit' ),
                ],
				'default' => 'inherited',
				'prefix_class' => 'de_staggering_animation_mousehover_',
				'condition' => [ 'de_staggering_animation' => 'yes' ],
			]
		);

		$elems->add_control(
			'de_staggering_animation_mouseout',
			[
				'label' => '<strong>'.esc_html__( 'Animation on Mouse Out', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::SELECT,
				'options' => [
                    'inherited' => esc_html__( 'inherited', 'detheme-kit' ),
                    'none' => esc_html__( 'none', 'detheme-kit' ),
                    'backInDown' => esc_html__( 'backInDown', 'detheme-kit' ),
                    'backOutDown' => esc_html__( 'backOutDown', 'detheme-kit' ),
                    'backInLeft' => esc_html__( 'backInLeft', 'detheme-kit' ),
                    'backOutLeft' => esc_html__( 'backOutLeft', 'detheme-kit' ),
                    'backInRight' => esc_html__( 'backInRight', 'detheme-kit' ),
                    'backOutRight' => esc_html__( 'backOutRight', 'detheme-kit' ),
                    'backInUp' => esc_html__( 'backInUp', 'detheme-kit' ),
                    'backOutUp' => esc_html__( 'backOutUp', 'detheme-kit' ),
                    'bounce' => esc_html__( 'bounce', 'detheme-kit' ),
                    'bounceIn' => esc_html__( 'bounceIn', 'detheme-kit' ),
                    'bounceOut' => esc_html__( 'bounceOut', 'detheme-kit' ),
                    'bounceInDown' => esc_html__( 'bounceInDown', 'detheme-kit' ),
                    'bounceOutDown' => esc_html__( 'bounceOutDown', 'detheme-kit' ),
                    'bounceInLeft' => esc_html__( 'bounceInLeft', 'detheme-kit' ),
                    'bounceOutLeft' => esc_html__( 'bounceOutLeft', 'detheme-kit' ),
                    'bounceInRight' => esc_html__( 'bounceInRight', 'detheme-kit' ),
                    'bounceOutRight' => esc_html__( 'bounceOutRight', 'detheme-kit' ),
                    'bounceInUp' => esc_html__( 'bounceInUp', 'detheme-kit' ),
                    'bounceOutUp' => esc_html__( 'bounceOutUp', 'detheme-kit' ),
                    'fadeIn' => esc_html__( 'fadeIn', 'detheme-kit' ),
                    'fadeOut' => esc_html__( 'fadeOut', 'detheme-kit' ),
					'fadeInDown' => esc_html__( 'fadeInDown', 'detheme-kit' ),
                    'fadeOutDown' => esc_html__( 'fadeOutDown', 'detheme-kit' ),
					'fadeInDownBig' => esc_html__( 'fadeInDownBig', 'detheme-kit' ),
                    'fadeOutDownBig' => esc_html__( 'fadeOutDownBig', 'detheme-kit' ),
					'fadeInLeft' => esc_html__( 'fadeInLeft', 'detheme-kit' ),
                    'fadeOutLeft' => esc_html__( 'fadeOutLeft', 'detheme-kit' ),
					'fadeInLeftBig' => esc_html__( 'fadeInLeftBig', 'detheme-kit' ),
                    'fadeOutLeftBig' => esc_html__( 'fadeOutLeftBig', 'detheme-kit' ),
					'fadeInRight' => esc_html__( 'fadeInRight', 'detheme-kit' ),
                    'fadeOutRight' => esc_html__( 'fadeOutRight', 'detheme-kit' ),
					'fadeInRightBig' => esc_html__( 'fadeInRightBig', 'detheme-kit' ),
                    'fadeOutRightBig' => esc_html__( 'fadeOutRightBig', 'detheme-kit' ),
					'fadeInUp' => esc_html__( 'fadeInUp', 'detheme-kit' ),
                    'fadeOutUp' => esc_html__( 'fadeOutUp', 'detheme-kit' ),
					'fadeInUpBig' => esc_html__( 'fadeInUpBig', 'detheme-kit' ),
                    'fadeOutUpBig' => esc_html__( 'fadeOutUpBig', 'detheme-kit' ),
					'fadeInTopLeft' => esc_html__( 'fadeInTopLeft', 'detheme-kit' ),
                    'fadeOutTopLeft' => esc_html__( 'fadeOutTopLeft', 'detheme-kit' ),
					'fadeInTopRight' => esc_html__( 'fadeInTopRight', 'detheme-kit' ),
                    'fadeOutTopRight' => esc_html__( 'fadeOutTopRight', 'detheme-kit' ),
					'fadeInBottomLeft' => esc_html__( 'fadeInBottomLeft', 'detheme-kit' ),
                    'fadeOutBottomLeft' => esc_html__( 'fadeOutBottomLeft', 'detheme-kit' ),
					'fadeInBottomRight' => esc_html__( 'fadeInBottomRight', 'detheme-kit' ),
                    'fadeOutBottomRight' => esc_html__( 'fadeOutBottomRight', 'detheme-kit' ),
                    'flash' => esc_html__( 'flash', 'detheme-kit' ),
                    'flip' => esc_html__( 'flip', 'detheme-kit' ),
                    'flipInX' => esc_html__( 'flipInX', 'detheme-kit' ),
                    'flipOutX' => esc_html__( 'flipOutX', 'detheme-kit' ),
                    'flipInY' => esc_html__( 'flipInY', 'detheme-kit' ),
                    'flipOutY' => esc_html__( 'flipOutY', 'detheme-kit' ),
                    'headShake' => esc_html__( 'headShake', 'detheme-kit' ),
                    'heartBeat' => esc_html__( 'heartBeat', 'detheme-kit' ),
                    'hinge' => esc_html__( 'hinge', 'detheme-kit' ),
                    'jackInTheBox' => esc_html__( 'jackInTheBox', 'detheme-kit' ),
                    'jello' => esc_html__( 'jello', 'detheme-kit' ),
                    'lightSpeedInRight' => esc_html__( 'lightSpeedInRight', 'detheme-kit' ),
                    'lightSpeedOutRight' => esc_html__( 'lightSpeedOutRight', 'detheme-kit' ),
                    'lightSpeedInLeft' => esc_html__( 'lightSpeedInLeft', 'detheme-kit' ),
                    'lightSpeedOutLeft' => esc_html__( 'lightSpeedOutLeft', 'detheme-kit' ),
                    'pulse' => esc_html__( 'pulse', 'detheme-kit' ),
                    'rollIn' => esc_html__( 'rollIn', 'detheme-kit' ),
                    'rollOut' => esc_html__( 'rollOut', 'detheme-kit' ),
                    'rotateIn' => esc_html__( 'rotateIn', 'detheme-kit' ),
                    'rotateOut' => esc_html__( 'rotateOut', 'detheme-kit' ),
                    'rotateInDownLeft' => esc_html__( 'rotateInDownLeft', 'detheme-kit' ),
                    'rotateOutDownLeft' => esc_html__( 'rotateOutDownLeft', 'detheme-kit' ),
                    'rotateInDownRight' => esc_html__( 'rotateInDownRight', 'detheme-kit' ),
                    'rotateOutDownRight' => esc_html__( 'rotateOutDownRight', 'detheme-kit' ),
                    'rotateInUpLeft' => esc_html__( 'rotateInUpLeft', 'detheme-kit' ),
                    'rotateOutUpLeft' => esc_html__( 'rotateOutUpLeft', 'detheme-kit' ),
                    'rotateInUpRight' => esc_html__( 'rotateInUpRight', 'detheme-kit' ),
                    'rotateOutUpRight' => esc_html__( 'rotateOutUpRight', 'detheme-kit' ),
                    'rubberBand' => esc_html__( 'rubberBand', 'detheme-kit' ),
                    'shakeX' => esc_html__( 'shakeX', 'detheme-kit' ),
                    'shakeY' => esc_html__( 'shakeY', 'detheme-kit' ),
                    'slideInDown' => esc_html__( 'slideInDown', 'detheme-kit' ),
                    'slideOutDown' => esc_html__( 'slideOutDown', 'detheme-kit' ),
                    'slideInLeft' => esc_html__( 'slideInLeft', 'detheme-kit' ),
                    'slideOutLeft' => esc_html__( 'slideOutLeft', 'detheme-kit' ),
                    'slideInRight' => esc_html__( 'slideInRight', 'detheme-kit' ),
                    'slideOutRight' => esc_html__( 'slideOutRight', 'detheme-kit' ),
                    'slideInUp' => esc_html__( 'slideInUp', 'detheme-kit' ),
                    'slideOutUp' => esc_html__( 'slideOutUp', 'detheme-kit' ),
                    'swing' => esc_html__( 'swing', 'detheme-kit' ),
                    'tada' => esc_html__( 'tada', 'detheme-kit' ),
                    'wobble' => esc_html__( 'wobble', 'detheme-kit' ),
                    'zoomIn' => esc_html__( 'zoomIn', 'detheme-kit' ),
                    'zoomOut' => esc_html__( 'zoomOut', 'detheme-kit' ),
                    'zoomInDown' => esc_html__( 'zoomInDown', 'detheme-kit' ),
                    'zoomOutDown' => esc_html__( 'zoomOutDown', 'detheme-kit' ),
                    'zoomInLeft' => esc_html__( 'zoomInLeft', 'detheme-kit' ),
                    'zoomOutLeft' => esc_html__( 'zoomOutLeft', 'detheme-kit' ),
                    'zoomInRight' => esc_html__( 'zoomInRight', 'detheme-kit' ),
                    'zoomOutRight' => esc_html__( 'zoomOutRight', 'detheme-kit' ),
                    'zoomInUp' => esc_html__( 'zoomInUp', 'detheme-kit' ),
                    'zoomOutUp' => esc_html__( 'zoomOutUp', 'detheme-kit' ),
                ],
				'default' => 'inherited',
				'prefix_class' => 'de_staggering_animation_mouseout_',
				'condition' => [ 'de_staggering_animation' => 'yes' ],
			]
		);

		$elems->end_controls_section();
	}

	public function section_register_controls($elems) {
	}

	public function column_register_controls($elems) {
		$elems->start_controls_section(
			'de_staggering_section',
			[
				'label' => __( 'De Staggering', 'detheme-kit' ),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);

		$elems->add_control(
			'de_staggering_hover',
			[
				'label' => '<strong>'.esc_html__( 'De Stagger Hover Trigger', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::SWITCHER,
				'prefix_class' => 'de_staggering_hover_',
			]
		);

		$elems->add_control(
			'de_staggering_preview_on_hover',
			[
				'label' => '<strong>'.esc_html__( 'Preview Animation on Hover', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::SWITCHER,
				'prefix_class' => 'de_staggering_preview_on_hover_',
                'condition' => [ 'de_staggering_hover' => 'yes' ],
			]
		);

		$elems->add_control(
			'de_staggering_parent_initial_state',
			[
				'label' => '<strong>'.esc_html__( 'Initial State', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::SELECT,
				'options' => [
                    'hidden' => esc_html__( 'Hidden', 'detheme-kit' ),
                    'visible' => esc_html__( 'Visible', 'detheme-kit' ),
                ],
				'default' => 'hidden',
				'prefix_class' => 'de_staggering_parent_initial_state_',
				'condition' => [ 'de_staggering_hover' => 'yes' ],
			]
		);

		$elems->add_control(
			'de_staggering_parent_animation_mousehover',
			[
				'label' => '<strong>'.esc_html__( 'Animation on Mouse Hover', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::SELECT,
				'options' => [
                    'none' => esc_html__( 'none', 'detheme-kit' ),
                    'backInDown' => esc_html__( 'backInDown', 'detheme-kit' ),
                    'backOutDown' => esc_html__( 'backOutDown', 'detheme-kit' ),
                    'backInLeft' => esc_html__( 'backInLeft', 'detheme-kit' ),
                    'backOutLeft' => esc_html__( 'backOutLeft', 'detheme-kit' ),
                    'backInRight' => esc_html__( 'backInRight', 'detheme-kit' ),
                    'backOutRight' => esc_html__( 'backOutRight', 'detheme-kit' ),
                    'backInUp' => esc_html__( 'backInUp', 'detheme-kit' ),
                    'backOutUp' => esc_html__( 'backOutUp', 'detheme-kit' ),
                    'bounce' => esc_html__( 'bounce', 'detheme-kit' ),
                    'bounceIn' => esc_html__( 'bounceIn', 'detheme-kit' ),
                    'bounceOut' => esc_html__( 'bounceOut', 'detheme-kit' ),
                    'bounceInDown' => esc_html__( 'bounceInDown', 'detheme-kit' ),
                    'bounceOutDown' => esc_html__( 'bounceOutDown', 'detheme-kit' ),
                    'bounceInLeft' => esc_html__( 'bounceInLeft', 'detheme-kit' ),
                    'bounceOutLeft' => esc_html__( 'bounceOutLeft', 'detheme-kit' ),
                    'bounceInRight' => esc_html__( 'bounceInRight', 'detheme-kit' ),
                    'bounceOutRight' => esc_html__( 'bounceOutRight', 'detheme-kit' ),
                    'bounceInUp' => esc_html__( 'bounceInUp', 'detheme-kit' ),
                    'bounceOutUp' => esc_html__( 'bounceOutUp', 'detheme-kit' ),
                    'fadeIn' => esc_html__( 'fadeIn', 'detheme-kit' ),
                    'fadeOut' => esc_html__( 'fadeOut', 'detheme-kit' ),
					'fadeInDown' => esc_html__( 'fadeInDown', 'detheme-kit' ),
                    'fadeOutDown' => esc_html__( 'fadeOutDown', 'detheme-kit' ),
					'fadeInDownBig' => esc_html__( 'fadeInDownBig', 'detheme-kit' ),
                    'fadeOutDownBig' => esc_html__( 'fadeOutDownBig', 'detheme-kit' ),
					'fadeInLeft' => esc_html__( 'fadeInLeft', 'detheme-kit' ),
                    'fadeOutLeft' => esc_html__( 'fadeOutLeft', 'detheme-kit' ),
					'fadeInLeftBig' => esc_html__( 'fadeInLeftBig', 'detheme-kit' ),
                    'fadeOutLeftBig' => esc_html__( 'fadeOutLeftBig', 'detheme-kit' ),
					'fadeInRight' => esc_html__( 'fadeInRight', 'detheme-kit' ),
                    'fadeOutRight' => esc_html__( 'fadeOutRight', 'detheme-kit' ),
					'fadeInRightBig' => esc_html__( 'fadeInRightBig', 'detheme-kit' ),
                    'fadeOutRightBig' => esc_html__( 'fadeOutRightBig', 'detheme-kit' ),
					'fadeInUp' => esc_html__( 'fadeInUp', 'detheme-kit' ),
                    'fadeOutUp' => esc_html__( 'fadeOutUp', 'detheme-kit' ),
					'fadeInUpBig' => esc_html__( 'fadeInUpBig', 'detheme-kit' ),
                    'fadeOutUpBig' => esc_html__( 'fadeOutUpBig', 'detheme-kit' ),
					'fadeInTopLeft' => esc_html__( 'fadeInTopLeft', 'detheme-kit' ),
                    'fadeOutTopLeft' => esc_html__( 'fadeOutTopLeft', 'detheme-kit' ),
					'fadeInTopRight' => esc_html__( 'fadeInTopRight', 'detheme-kit' ),
                    'fadeOutTopRight' => esc_html__( 'fadeOutTopRight', 'detheme-kit' ),
					'fadeInBottomLeft' => esc_html__( 'fadeInBottomLeft', 'detheme-kit' ),
                    'fadeOutBottomLeft' => esc_html__( 'fadeOutBottomLeft', 'detheme-kit' ),
					'fadeInBottomRight' => esc_html__( 'fadeInBottomRight', 'detheme-kit' ),
                    'fadeOutBottomRight' => esc_html__( 'fadeOutBottomRight', 'detheme-kit' ),
                    'flash' => esc_html__( 'flash', 'detheme-kit' ),
                    'flip' => esc_html__( 'flip', 'detheme-kit' ),
                    'flipInX' => esc_html__( 'flipInX', 'detheme-kit' ),
                    'flipOutX' => esc_html__( 'flipOutX', 'detheme-kit' ),
                    'flipInY' => esc_html__( 'flipInY', 'detheme-kit' ),
                    'flipOutY' => esc_html__( 'flipOutY', 'detheme-kit' ),
                    'headShake' => esc_html__( 'headShake', 'detheme-kit' ),
                    'heartBeat' => esc_html__( 'heartBeat', 'detheme-kit' ),
                    'hinge' => esc_html__( 'hinge', 'detheme-kit' ),
                    'jackInTheBox' => esc_html__( 'jackInTheBox', 'detheme-kit' ),
                    'jello' => esc_html__( 'jello', 'detheme-kit' ),
                    'lightSpeedInRight' => esc_html__( 'lightSpeedInRight', 'detheme-kit' ),
                    'lightSpeedOutRight' => esc_html__( 'lightSpeedOutRight', 'detheme-kit' ),
                    'lightSpeedInLeft' => esc_html__( 'lightSpeedInLeft', 'detheme-kit' ),
                    'lightSpeedOutLeft' => esc_html__( 'lightSpeedOutLeft', 'detheme-kit' ),
                    'pulse' => esc_html__( 'pulse', 'detheme-kit' ),
                    'rollIn' => esc_html__( 'rollIn', 'detheme-kit' ),
                    'rollOut' => esc_html__( 'rollOut', 'detheme-kit' ),
                    'rotateIn' => esc_html__( 'rotateIn', 'detheme-kit' ),
                    'rotateOut' => esc_html__( 'rotateOut', 'detheme-kit' ),
                    'rotateInDownLeft' => esc_html__( 'rotateInDownLeft', 'detheme-kit' ),
                    'rotateOutDownLeft' => esc_html__( 'rotateOutDownLeft', 'detheme-kit' ),
                    'rotateInDownRight' => esc_html__( 'rotateInDownRight', 'detheme-kit' ),
                    'rotateOutDownRight' => esc_html__( 'rotateOutDownRight', 'detheme-kit' ),
                    'rotateInUpLeft' => esc_html__( 'rotateInUpLeft', 'detheme-kit' ),
                    'rotateOutUpLeft' => esc_html__( 'rotateOutUpLeft', 'detheme-kit' ),
                    'rotateInUpRight' => esc_html__( 'rotateInUpRight', 'detheme-kit' ),
                    'rotateOutUpRight' => esc_html__( 'rotateOutUpRight', 'detheme-kit' ),
                    'rubberBand' => esc_html__( 'rubberBand', 'detheme-kit' ),
                    'shakeX' => esc_html__( 'shakeX', 'detheme-kit' ),
                    'shakeY' => esc_html__( 'shakeY', 'detheme-kit' ),
                    'slideInDown' => esc_html__( 'slideInDown', 'detheme-kit' ),
                    'slideOutDown' => esc_html__( 'slideOutDown', 'detheme-kit' ),
                    'slideInLeft' => esc_html__( 'slideInLeft', 'detheme-kit' ),
                    'slideOutLeft' => esc_html__( 'slideOutLeft', 'detheme-kit' ),
                    'slideInRight' => esc_html__( 'slideInRight', 'detheme-kit' ),
                    'slideOutRight' => esc_html__( 'slideOutRight', 'detheme-kit' ),
                    'slideInUp' => esc_html__( 'slideInUp', 'detheme-kit' ),
                    'slideOutUp' => esc_html__( 'slideOutUp', 'detheme-kit' ),
                    'swing' => esc_html__( 'swing', 'detheme-kit' ),
                    'tada' => esc_html__( 'tada', 'detheme-kit' ),
                    'wobble' => esc_html__( 'wobble', 'detheme-kit' ),
                    'zoomIn' => esc_html__( 'zoomIn', 'detheme-kit' ),
                    'zoomOut' => esc_html__( 'zoomOut', 'detheme-kit' ),
                    'zoomInDown' => esc_html__( 'zoomInDown', 'detheme-kit' ),
                    'zoomOutDown' => esc_html__( 'zoomOutDown', 'detheme-kit' ),
                    'zoomInLeft' => esc_html__( 'zoomInLeft', 'detheme-kit' ),
                    'zoomOutLeft' => esc_html__( 'zoomOutLeft', 'detheme-kit' ),
                    'zoomInRight' => esc_html__( 'zoomInRight', 'detheme-kit' ),
                    'zoomOutRight' => esc_html__( 'zoomOutRight', 'detheme-kit' ),
                    'zoomInUp' => esc_html__( 'zoomInUp', 'detheme-kit' ),
                    'zoomOutUp' => esc_html__( 'zoomOutUp', 'detheme-kit' ),
                ],
				'default' => 'fadeIn',
				'prefix_class' => 'de_staggering_parent_animation_mousehover_',
				'condition' => [ 'de_staggering_hover' => 'yes' ],
			]
		);

		$elems->add_control(
			'de_staggering_parent_animation_mouseout',
			[
				'label' => '<strong>'.esc_html__( 'Animation on Mouse Out', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::SELECT,
				'options' => [
                    'none' => esc_html__( 'none', 'detheme-kit' ),
                    'backInDown' => esc_html__( 'backInDown', 'detheme-kit' ),
                    'backOutDown' => esc_html__( 'backOutDown', 'detheme-kit' ),
                    'backInLeft' => esc_html__( 'backInLeft', 'detheme-kit' ),
                    'backOutLeft' => esc_html__( 'backOutLeft', 'detheme-kit' ),
                    'backInRight' => esc_html__( 'backInRight', 'detheme-kit' ),
                    'backOutRight' => esc_html__( 'backOutRight', 'detheme-kit' ),
                    'backInUp' => esc_html__( 'backInUp', 'detheme-kit' ),
                    'backOutUp' => esc_html__( 'backOutUp', 'detheme-kit' ),
                    'bounce' => esc_html__( 'bounce', 'detheme-kit' ),
                    'bounceIn' => esc_html__( 'bounceIn', 'detheme-kit' ),
                    'bounceOut' => esc_html__( 'bounceOut', 'detheme-kit' ),
                    'bounceInDown' => esc_html__( 'bounceInDown', 'detheme-kit' ),
                    'bounceOutDown' => esc_html__( 'bounceOutDown', 'detheme-kit' ),
                    'bounceInLeft' => esc_html__( 'bounceInLeft', 'detheme-kit' ),
                    'bounceOutLeft' => esc_html__( 'bounceOutLeft', 'detheme-kit' ),
                    'bounceInRight' => esc_html__( 'bounceInRight', 'detheme-kit' ),
                    'bounceOutRight' => esc_html__( 'bounceOutRight', 'detheme-kit' ),
                    'bounceInUp' => esc_html__( 'bounceInUp', 'detheme-kit' ),
                    'bounceOutUp' => esc_html__( 'bounceOutUp', 'detheme-kit' ),
                    'fadeIn' => esc_html__( 'fadeIn', 'detheme-kit' ),
                    'fadeOut' => esc_html__( 'fadeOut', 'detheme-kit' ),
					'fadeInDown' => esc_html__( 'fadeInDown', 'detheme-kit' ),
                    'fadeOutDown' => esc_html__( 'fadeOutDown', 'detheme-kit' ),
					'fadeInDownBig' => esc_html__( 'fadeInDownBig', 'detheme-kit' ),
                    'fadeOutDownBig' => esc_html__( 'fadeOutDownBig', 'detheme-kit' ),
					'fadeInLeft' => esc_html__( 'fadeInLeft', 'detheme-kit' ),
                    'fadeOutLeft' => esc_html__( 'fadeOutLeft', 'detheme-kit' ),
					'fadeInLeftBig' => esc_html__( 'fadeInLeftBig', 'detheme-kit' ),
                    'fadeOutLeftBig' => esc_html__( 'fadeOutLeftBig', 'detheme-kit' ),
					'fadeInRight' => esc_html__( 'fadeInRight', 'detheme-kit' ),
                    'fadeOutRight' => esc_html__( 'fadeOutRight', 'detheme-kit' ),
					'fadeInRightBig' => esc_html__( 'fadeInRightBig', 'detheme-kit' ),
                    'fadeOutRightBig' => esc_html__( 'fadeOutRightBig', 'detheme-kit' ),
					'fadeInUp' => esc_html__( 'fadeInUp', 'detheme-kit' ),
                    'fadeOutUp' => esc_html__( 'fadeOutUp', 'detheme-kit' ),
					'fadeInUpBig' => esc_html__( 'fadeInUpBig', 'detheme-kit' ),
                    'fadeOutUpBig' => esc_html__( 'fadeOutUpBig', 'detheme-kit' ),
					'fadeInTopLeft' => esc_html__( 'fadeInTopLeft', 'detheme-kit' ),
                    'fadeOutTopLeft' => esc_html__( 'fadeOutTopLeft', 'detheme-kit' ),
					'fadeInTopRight' => esc_html__( 'fadeInTopRight', 'detheme-kit' ),
                    'fadeOutTopRight' => esc_html__( 'fadeOutTopRight', 'detheme-kit' ),
					'fadeInBottomLeft' => esc_html__( 'fadeInBottomLeft', 'detheme-kit' ),
                    'fadeOutBottomLeft' => esc_html__( 'fadeOutBottomLeft', 'detheme-kit' ),
					'fadeInBottomRight' => esc_html__( 'fadeInBottomRight', 'detheme-kit' ),
                    'fadeOutBottomRight' => esc_html__( 'fadeOutBottomRight', 'detheme-kit' ),
                    'flash' => esc_html__( 'flash', 'detheme-kit' ),
                    'flip' => esc_html__( 'flip', 'detheme-kit' ),
                    'flipInX' => esc_html__( 'flipInX', 'detheme-kit' ),
                    'flipOutX' => esc_html__( 'flipOutX', 'detheme-kit' ),
                    'flipInY' => esc_html__( 'flipInY', 'detheme-kit' ),
                    'flipOutY' => esc_html__( 'flipOutY', 'detheme-kit' ),
                    'headShake' => esc_html__( 'headShake', 'detheme-kit' ),
                    'heartBeat' => esc_html__( 'heartBeat', 'detheme-kit' ),
                    'hinge' => esc_html__( 'hinge', 'detheme-kit' ),
                    'jackInTheBox' => esc_html__( 'jackInTheBox', 'detheme-kit' ),
                    'jello' => esc_html__( 'jello', 'detheme-kit' ),
                    'lightSpeedInRight' => esc_html__( 'lightSpeedInRight', 'detheme-kit' ),
                    'lightSpeedOutRight' => esc_html__( 'lightSpeedOutRight', 'detheme-kit' ),
                    'lightSpeedInLeft' => esc_html__( 'lightSpeedInLeft', 'detheme-kit' ),
                    'lightSpeedOutLeft' => esc_html__( 'lightSpeedOutLeft', 'detheme-kit' ),
                    'pulse' => esc_html__( 'pulse', 'detheme-kit' ),
                    'rollIn' => esc_html__( 'rollIn', 'detheme-kit' ),
                    'rollOut' => esc_html__( 'rollOut', 'detheme-kit' ),
                    'rotateIn' => esc_html__( 'rotateIn', 'detheme-kit' ),
                    'rotateOut' => esc_html__( 'rotateOut', 'detheme-kit' ),
                    'rotateInDownLeft' => esc_html__( 'rotateInDownLeft', 'detheme-kit' ),
                    'rotateOutDownLeft' => esc_html__( 'rotateOutDownLeft', 'detheme-kit' ),
                    'rotateInDownRight' => esc_html__( 'rotateInDownRight', 'detheme-kit' ),
                    'rotateOutDownRight' => esc_html__( 'rotateOutDownRight', 'detheme-kit' ),
                    'rotateInUpLeft' => esc_html__( 'rotateInUpLeft', 'detheme-kit' ),
                    'rotateOutUpLeft' => esc_html__( 'rotateOutUpLeft', 'detheme-kit' ),
                    'rotateInUpRight' => esc_html__( 'rotateInUpRight', 'detheme-kit' ),
                    'rotateOutUpRight' => esc_html__( 'rotateOutUpRight', 'detheme-kit' ),
                    'rubberBand' => esc_html__( 'rubberBand', 'detheme-kit' ),
                    'shakeX' => esc_html__( 'shakeX', 'detheme-kit' ),
                    'shakeY' => esc_html__( 'shakeY', 'detheme-kit' ),
                    'slideInDown' => esc_html__( 'slideInDown', 'detheme-kit' ),
                    'slideOutDown' => esc_html__( 'slideOutDown', 'detheme-kit' ),
                    'slideInLeft' => esc_html__( 'slideInLeft', 'detheme-kit' ),
                    'slideOutLeft' => esc_html__( 'slideOutLeft', 'detheme-kit' ),
                    'slideInRight' => esc_html__( 'slideInRight', 'detheme-kit' ),
                    'slideOutRight' => esc_html__( 'slideOutRight', 'detheme-kit' ),
                    'slideInUp' => esc_html__( 'slideInUp', 'detheme-kit' ),
                    'slideOutUp' => esc_html__( 'slideOutUp', 'detheme-kit' ),
                    'swing' => esc_html__( 'swing', 'detheme-kit' ),
                    'tada' => esc_html__( 'tada', 'detheme-kit' ),
                    'wobble' => esc_html__( 'wobble', 'detheme-kit' ),
                    'zoomIn' => esc_html__( 'zoomIn', 'detheme-kit' ),
                    'zoomOut' => esc_html__( 'zoomOut', 'detheme-kit' ),
                    'zoomInDown' => esc_html__( 'zoomInDown', 'detheme-kit' ),
                    'zoomOutDown' => esc_html__( 'zoomOutDown', 'detheme-kit' ),
                    'zoomInLeft' => esc_html__( 'zoomInLeft', 'detheme-kit' ),
                    'zoomOutLeft' => esc_html__( 'zoomOutLeft', 'detheme-kit' ),
                    'zoomInRight' => esc_html__( 'zoomInRight', 'detheme-kit' ),
                    'zoomOutRight' => esc_html__( 'zoomOutRight', 'detheme-kit' ),
                    'zoomInUp' => esc_html__( 'zoomInUp', 'detheme-kit' ),
                    'zoomOutUp' => esc_html__( 'zoomOutUp', 'detheme-kit' ),
                ],
				'default' => 'fadeOut',
				'prefix_class' => 'de_staggering_parent_animation_mouseout_',
				'condition' => [ 'de_staggering_hover' => 'yes' ],
			]
		);

		$elems->add_control(
			'de_staggering_child_delay',
			[
				'label' => '<strong>'.esc_html__( 'Child Element Delay (ms)', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::NUMBER,
				'prefix_class' => 'de_staggering_child_delay_',
				'default' => '500',
				'condition' => [ 'de_staggering_hover' => 'yes' ],
			]
		);

		$elems->add_control(
			'de_staggering_on_desktop',
			[
				'label' => esc_html__( 'Run Animation on Desktop', 'detheme-kit' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'prefix_class' => 'de_staggering_on_desktop_',
				'condition' => [ 'de_staggering_hover' => 'yes' ],
			]
		);

		$elems->add_control(
			'de_staggering_on_tablet',
			[
				'label' => esc_html__( 'Run Animation on Tablet', 'detheme-kit' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'prefix_class' => 'de_staggering_on_tablet_',
				'condition' => [ 'de_staggering_hover' => 'yes' ],
			]
		);

		$elems->add_control(
			'de_staggering_on_mobile',
			[
				'label' => esc_html__( 'Run Animation on Mobile', 'detheme-kit' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'prefix_class' => 'de_staggering_on_mobile_',
				'condition' => [ 'de_staggering_hover' => 'yes' ],
			]
		);


		$elems->end_controls_section();
    }
}