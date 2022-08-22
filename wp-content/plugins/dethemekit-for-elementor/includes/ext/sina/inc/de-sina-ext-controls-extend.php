<?php
namespace De_Sina_Extension;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Css_Filter;

/**
 * De_Sina_Ext_Controls Class for extends controls
 *
 * @since 3.0.1
 */
class De_Sina_Ext_Controls{
	/**
	 * Instance
	 *
	 * @since 3.1.13
	 * @var De_Sina_Ext_Controls The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 3.1.13
	 * @return De_Sina_Ext_Controls An Instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct() {
		add_action('elementor/element/common/_section_style/before_section_end', [$this, 'register_controls']);
		add_action('elementor/element/column/section_advanced/before_section_end', [$this, 'column_register_controls']);
		add_action('elementor/element/section/section_advanced/before_section_end', [$this, 'column_register_controls']);
	}

	public function register_controls($elems) {
		$elems->add_control(
			'sina_is_morphing_animation',
			[
				'label' => '<strong>'.esc_html__( 'De Mask', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::SWITCHER,
				'prefix_class' => 'sina-morphing-anim-',
				'separator' => 'before',
			]
		);
		$elems->add_control(
			'sina_transform_effects',
			[
				'label' => '<strong>'.esc_html__( 'De Transform', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::SELECT,
				'options' => [
					'translate' => esc_html__( 'Translate', 'detheme-kit' ),
					'scaleX' => esc_html__( 'Scale X', 'detheme-kit' ),
					'scaleY' => esc_html__( 'Scale Y', 'detheme-kit' ),
					'scaleZ' => esc_html__( 'Scale Z', 'detheme-kit' ),
					'rotateX' => esc_html__( 'Rotate X', 'detheme-kit' ),
					'rotateY' => esc_html__( 'Rotate Y', 'detheme-kit' ),
					'rotateZ' => esc_html__( 'Rotate Z', 'detheme-kit' ),
					'skewX' => esc_html__( 'Skew X', 'detheme-kit' ),
					'skewY' => esc_html__( 'Skew Y', 'detheme-kit' ),
					'none' => esc_html__( 'None', 'detheme-kit' ),
				],
				'default' => 'none',
			]
		);
		$elems->add_responsive_control(
			'sina_transform_perspective',
			[
				'label' => esc_html__( 'Perspective Size', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 1,
						'min' => 0,
						'max' => 10000,
					],
				],
				'default' => [
					'size' => '1000',
				],
				'condition' => [
					'sina_transform_effects' => ['rotateX', 'rotateY'],
				],
				'selectors' => [
					'{{WRAPPER}}' => 'perspective: {{SIZE}}px;',
				],
			]
		);

		$elems->start_controls_tabs( 'sina_transform_effects_tabs' );

		$elems->start_controls_tab(
			'sina_transform_effects_normal',
			[
				'label' => esc_html__( 'Normal', 'detheme-kit' ),
			]
		);

		$elems->add_responsive_control(
			'sina_transform_effects_translateX',
			[
				'label' => esc_html__( 'Translate X', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'default' => [
					'size' => '0',
				],
				'condition' => [
					'sina_transform_effects' => 'translate',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_translateY',
			[
				'label' => esc_html__( 'Translate Y', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'default' => [
					'size' => '0',
				],
				'condition' => [
					'sina_transform_effects' => 'translate',
				],
				'selectors' => [
					'(desktop){{WRAPPER}} .elementor-widget-container' => 'transform: translate({{sina_transform_effects_translateX.SIZE || 0}}px, {{sina_transform_effects_translateY.SIZE || 0}}px);',
					'(tablet){{WRAPPER}} .elementor-widget-container' => 'transform: translate({{sina_transform_effects_translateX_tablet.SIZE || 0}}px, {{sina_transform_effects_translateY_tablet.SIZE || 0}}px);',
					'(mobile){{WRAPPER}} .elementor-widget-container' => 'transform: translate({{sina_transform_effects_translateX_mobile.SIZE || 0}}px, {{sina_transform_effects_translateY_mobile.SIZE || 0}}px);',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_scaleX',
			[
				'label' => esc_html__( 'Scale X', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 0.1,
						'min' => 0.1,
						'max' => 5,
					],
				],
				'default' => [
					'size' => '1',
				],
				'condition' => [
					'sina_transform_effects' => 'scaleX',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'transform: scaleX({{SIZE}});',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_scaleY',
			[
				'label' => esc_html__( 'Scale Y', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 0.1,
						'min' => 0.1,
						'max' => 5,
					],
				],
				'default' => [
					'size' => '1',
				],
				'condition' => [
					'sina_transform_effects' => 'scaleY',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'transform: scaleY({{SIZE}});',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_scaleZ',
			[
				'label' => esc_html__( 'Scale Z', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 0.1,
						'min' => 0.1,
						'max' => 5,
					],
				],
				'default' => [
					'size' => '1',
				],
				'condition' => [
					'sina_transform_effects' => 'scaleZ',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'transform: scale({{SIZE}});',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_rotateX',
			[
				'label' => esc_html__( 'Rotate X', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 1,
						'min' => 0,
						'max' => 360,
					],
				],
				'default' => [
					'size' => '0',
				],
				'condition' => [
					'sina_transform_effects' => 'rotateX',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'transform: rotateX({{SIZE}}deg);',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_rotateY',
			[
				'label' => esc_html__( 'Rotate Y', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 1,
						'min' => 0,
						'max' => 360,
					],
				],
				'default' => [
					'size' => '0',
				],
				'condition' => [
					'sina_transform_effects' => 'rotateY',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'transform: rotateY({{SIZE}}deg);',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_rotateZ',
			[
				'label' => esc_html__( 'Rotate Z', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 1,
						'min' => 0,
						'max' => 360,
					],
				],
				'default' => [
					'size' => '0',
				],
				'condition' => [
					'sina_transform_effects' => 'rotateZ',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'transform: rotateZ({{SIZE}}deg);',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_skewX',
			[
				'label' => esc_html__( 'Skew X', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 1,
						'min' => -60,
						'max' => 60,
					],
				],
				'default' => [
					'size' => '0',
				],
				'condition' => [
					'sina_transform_effects' => 'skewX',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'transform: skewX({{SIZE}}deg);',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_skewY',
			[
				'label' => esc_html__( 'Skew Y', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 1,
						'min' => -60,
						'max' => 60,
					],
				],
				'default' => [
					'size' => '0',
				],
				'condition' => [
					'sina_transform_effects' => 'skewY',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'transform: skewY({{SIZE}}deg);',
				],
			]
		);
		$elems->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'sina_transform_effects_filters',
				'selector' => '{{WRAPPER}} .elementor-widget-container',
			]
		);

		$elems->end_controls_tab();

		$elems->start_controls_tab(
			'sina_transform_effects_hover',
			[
				'label' => esc_html__( 'Hover', 'detheme-kit' ),
			]
		);

		$elems->add_responsive_control(
			'sina_transform_effects_translateX_hover',
			[
				'label' => esc_html__( 'Translate X', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'default' => [
					'size' => '0',
				],
				'condition' => [
					'sina_transform_effects' => 'translate',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_translateY_hover',
			[
				'label' => esc_html__( 'Translate Y', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'default' => [
					'size' => '-10',
				],
				'condition' => [
					'sina_transform_effects' => 'translate',
				],
				'selectors' => [
					'(desktop){{WRAPPER}} .elementor-widget-container:hover' => 'transform: translate({{sina_transform_effects_translateX_hover.SIZE || 0}}px, {{sina_transform_effects_translateY_hover.SIZE || 0}}px);',
					'(tablet){{WRAPPER}} .elementor-widget-container:hover' => 'transform: translate({{sina_transform_effects_translateX_hover_tablet.SIZE || 0}}px, {{sina_transform_effects_translateY_hover_tablet.SIZE || 0}}px);',
					'(mobile){{WRAPPER}} .elementor-widget-container:hover' => 'transform: translate({{sina_transform_effects_translateX_hover_mobile.SIZE || 0}}px, {{sina_transform_effects_translateY_hover_mobile.SIZE || 0}}px);',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_scaleX_hover',
			[
				'label' => esc_html__( 'Scale X', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 0.1,
						'min' => 0.1,
						'max' => 5,
					],
				],
				'default' => [
					'size' => '1.05',
				],
				'condition' => [
					'sina_transform_effects' => 'scaleX',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container:hover' => 'transform: scaleX({{SIZE}});',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_scaleY_hover',
			[
				'label' => esc_html__( 'Scale Y', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 0.1,
						'min' => 0.1,
						'max' => 5,
					],
				],
				'default' => [
					'size' => '1.05',
				],
				'condition' => [
					'sina_transform_effects' => 'scaleY',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container:hover' => 'transform: scaleY({{SIZE}});',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_scaleZ_hover',
			[
				'label' => esc_html__( 'Scale Z', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 0.1,
						'min' => 0.1,
						'max' => 5,
					],
				],
				'default' => [
					'size' => '1.05',
				],
				'condition' => [
					'sina_transform_effects' => 'scaleZ',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container:hover' => 'transform: scale({{SIZE}});',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_rotateX_hover',
			[
				'label' => esc_html__( 'Rotate X', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 1,
						'min' => 0,
						'max' => 360,
					],
				],
				'default' => [
					'size' => '15',
				],
				'condition' => [
					'sina_transform_effects' => 'rotateX',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container:hover' => 'transform: rotateX({{SIZE}}deg);',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_rotateY_hover',
			[
				'label' => esc_html__( 'Rotate Y', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 1,
						'min' => 0,
						'max' => 360,
					],
				],
				'default' => [
					'size' => '15',
				],
				'condition' => [
					'sina_transform_effects' => 'rotateY',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container:hover' => 'transform: rotateY({{SIZE}}deg);',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_rotateZ_hover',
			[
				'label' => esc_html__( 'Rotate Z', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 1,
						'min' => 0,
						'max' => 360,
					],
				],
				'default' => [
					'size' => '5',
				],
				'condition' => [
					'sina_transform_effects' => 'rotateZ',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container:hover' => 'transform: rotateZ({{SIZE}}deg);',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_skewX_hover',
			[
				'label' => esc_html__( 'Skew X', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 1,
						'min' => -60,
						'max' => 60,
					],
				],
				'default' => [
					'size' => '10',
				],
				'condition' => [
					'sina_transform_effects' => 'skewX',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container:hover' => 'transform: skewX({{SIZE}}deg);',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_skewY_hover',
			[
				'label' => esc_html__( 'Skew Y', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 1,
						'min' => -60,
						'max' => 60,
					],
				],
				'default' => [
					'size' => '5',
				],
				'condition' => [
					'sina_transform_effects' => 'skewY',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container:hover' => 'transform: skewY({{SIZE}}deg);',
				],
			]
		);
		$elems->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'sina_transform_effects_filters_hover',
				'selector' => '{{WRAPPER}} .elementor-widget-container:hover',
			]
		);
		$elems->add_control(
			'sina_transform_effects_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 100,
						'min' => 0,
						'max' => 10000,
					],
				],
				'default' => [
					'size' => '400',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'transition: all {{SIZE}}ms;',
				],
			]
		);

		$elems->end_controls_tab();

		$elems->end_controls_tabs();
	}

	public function column_register_controls($elems) {
		$elems->add_control(
			'sina_is_morphing_animation',
			[
				'label' => '<strong>'.esc_html__( 'De Mask', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::SWITCHER,
				'prefix_class' => 'sina-morphing-anim-',
				'separator' => 'before',
			]
		);
		$elems->add_control(
			'sina_transform_effects',
			[
				'label' => '<strong>'.esc_html__( 'De Transform', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::SELECT,
				'options' => [
					'translate' => esc_html__( 'Translate', 'detheme-kit' ),
					'scaleX' => esc_html__( 'Scale X', 'detheme-kit' ),
					'scaleY' => esc_html__( 'Scale Y', 'detheme-kit' ),
					'scaleZ' => esc_html__( 'Scale Z', 'detheme-kit' ),
					'rotateX' => esc_html__( 'Rotate X', 'detheme-kit' ),
					'rotateY' => esc_html__( 'Rotate Y', 'detheme-kit' ),
					'rotateZ' => esc_html__( 'Rotate Z', 'detheme-kit' ),
					'skewX' => esc_html__( 'Skew X', 'detheme-kit' ),
					'skewY' => esc_html__( 'Skew Y', 'detheme-kit' ),
					'none' => esc_html__( 'None', 'detheme-kit' ),
				],
				'default' => 'none',
			]
		);
		$elems->add_responsive_control(
			'sina_transform_perspective',
			[
				'label' => esc_html__( 'Perspective Size', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 1,
						'min' => 0,
						'max' => 10000,
					],
				],
				'default' => [
					'size' => '1000',
				],
				'condition' => [
					'sina_transform_effects' => ['rotateX', 'rotateY'],
				],
				'selectors' => [
					'{{WRAPPER}}' => 'perspective: {{SIZE}}px;',
				],
			]
		);

		$elems->start_controls_tabs( 'sina_transform_effects_tabs' );

		$elems->start_controls_tab(
			'sina_transform_effects_normal',
			[
				'label' => esc_html__( 'Normal', 'detheme-kit' ),
			]
		);

		$elems->add_responsive_control(
			'sina_transform_effects_translateX',
			[
				'label' => esc_html__( 'Translate X', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'default' => [
					'size' => '0',
				],
				'condition' => [
					'sina_transform_effects' => 'translate',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_translateY',
			[
				'label' => esc_html__( 'Translate Y', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'default' => [
					'size' => '0',
				],
				'condition' => [
					'sina_transform_effects' => 'translate',
				],
				'selectors' => [
					'(desktop){{WRAPPER}}' => 'transform: translate({{sina_transform_effects_translateX.SIZE || 0}}px, {{sina_transform_effects_translateY.SIZE || 0}}px);',
					'(tablet){{WRAPPER}}' => 'transform: translate({{sina_transform_effects_translateX_tablet.SIZE || 0}}px, {{sina_transform_effects_translateY_tablet.SIZE || 0}}px);',
					'(mobile){{WRAPPER}}' => 'transform: translate({{sina_transform_effects_translateX_mobile.SIZE || 0}}px, {{sina_transform_effects_translateY_mobile.SIZE || 0}}px);',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_scaleX',
			[
				'label' => esc_html__( 'Scale X', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 0.1,
						'min' => 0.1,
						'max' => 5,
					],
				],
				'default' => [
					'size' => '1',
				],
				'condition' => [
					'sina_transform_effects' => 'scaleX',
				],
				'selectors' => [
					'{{WRAPPER}}' => 'transform: scaleX({{SIZE}});',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_scaleY',
			[
				'label' => esc_html__( 'Scale Y', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 0.1,
						'min' => 0.1,
						'max' => 5,
					],
				],
				'default' => [
					'size' => '1',
				],
				'condition' => [
					'sina_transform_effects' => 'scaleY',
				],
				'selectors' => [
					'{{WRAPPER}}' => 'transform: scaleY({{SIZE}});',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_scaleZ',
			[
				'label' => esc_html__( 'Scale Z', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 0.1,
						'min' => 0.1,
						'max' => 5,
					],
				],
				'default' => [
					'size' => '1',
				],
				'condition' => [
					'sina_transform_effects' => 'scaleZ',
				],
				'selectors' => [
					'{{WRAPPER}}' => 'transform: scale({{SIZE}});',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_rotateX',
			[
				'label' => esc_html__( 'Rotate X', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 1,
						'min' => 0,
						'max' => 360,
					],
				],
				'default' => [
					'size' => '0',
				],
				'condition' => [
					'sina_transform_effects' => 'rotateX',
				],
				'selectors' => [
					'{{WRAPPER}}' => 'transform: rotateX({{SIZE}}deg);',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_rotateY',
			[
				'label' => esc_html__( 'Rotate Y', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 1,
						'min' => 0,
						'max' => 360,
					],
				],
				'default' => [
					'size' => '0',
				],
				'condition' => [
					'sina_transform_effects' => 'rotateY',
				],
				'selectors' => [
					'{{WRAPPER}}' => 'transform: rotateY({{SIZE}}deg);',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_rotateZ',
			[
				'label' => esc_html__( 'Rotate Z', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 1,
						'min' => 0,
						'max' => 360,
					],
				],
				'default' => [
					'size' => '0',
				],
				'condition' => [
					'sina_transform_effects' => 'rotateZ',
				],
				'selectors' => [
					'{{WRAPPER}}' => 'transform: rotateZ({{SIZE}}deg);',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_skewX',
			[
				'label' => esc_html__( 'Skew X', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 1,
						'min' => -60,
						'max' => 60,
					],
				],
				'default' => [
					'size' => '0',
				],
				'condition' => [
					'sina_transform_effects' => 'skewX',
				],
				'selectors' => [
					'{{WRAPPER}}' => 'transform: skewX({{SIZE}}deg);',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_skewY',
			[
				'label' => esc_html__( 'Skew Y', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 1,
						'min' => -60,
						'max' => 60,
					],
				],
				'default' => [
					'size' => '0',
				],
				'condition' => [
					'sina_transform_effects' => 'skewY',
				],
				'selectors' => [
					'{{WRAPPER}}' => 'transform: skewY({{SIZE}}deg);',
				],
			]
		);
		$elems->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'sina_transform_effects_filters',
				'selector' => '{{WRAPPER}}',
			]
		);

		$elems->end_controls_tab();

		$elems->start_controls_tab(
			'sina_transform_effects_hover',
			[
				'label' => esc_html__( 'Hover', 'detheme-kit' ),
			]
		);

		$elems->add_responsive_control(
			'sina_transform_effects_translateX_hover',
			[
				'label' => esc_html__( 'Translate X', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'default' => [
					'size' => '0',
				],
				'condition' => [
					'sina_transform_effects' => 'translate',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_translateY_hover',
			[
				'label' => esc_html__( 'Translate Y', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'default' => [
					'size' => '-10',
				],
				'condition' => [
					'sina_transform_effects' => 'translate',
				],
				'selectors' => [
					'(desktop){{WRAPPER}}:hover' => 'transform: translate({{sina_transform_effects_translateX_hover.SIZE || 0}}px, {{sina_transform_effects_translateY_hover.SIZE || 0}}px);',
					'(tablet){{WRAPPER}}:hover' => 'transform: translate({{sina_transform_effects_translateX_hover_tablet.SIZE || 0}}px, {{sina_transform_effects_translateY_hover_tablet.SIZE || 0}}px);',
					'(mobile){{WRAPPER}}:hover' => 'transform: translate({{sina_transform_effects_translateX_hover_mobile.SIZE || 0}}px, {{sina_transform_effects_translateY_hover_mobile.SIZE || 0}}px);',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_scaleX_hover',
			[
				'label' => esc_html__( 'Scale X', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 0.1,
						'min' => 0.1,
						'max' => 5,
					],
				],
				'default' => [
					'size' => '1.05',
				],
				'condition' => [
					'sina_transform_effects' => 'scaleX',
				],
				'selectors' => [
					'{{WRAPPER}}:hover' => 'transform: scaleX({{SIZE}});',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_scaleY_hover',
			[
				'label' => esc_html__( 'Scale Y', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 0.1,
						'min' => 0.1,
						'max' => 5,
					],
				],
				'default' => [
					'size' => '1.05',
				],
				'condition' => [
					'sina_transform_effects' => 'scaleY',
				],
				'selectors' => [
					'{{WRAPPER}}:hover' => 'transform: scaleY({{SIZE}});',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_scaleZ_hover',
			[
				'label' => esc_html__( 'Scale Z', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 0.1,
						'min' => 0.1,
						'max' => 5,
					],
				],
				'default' => [
					'size' => '1.05',
				],
				'condition' => [
					'sina_transform_effects' => 'scaleZ',
				],
				'selectors' => [
					'{{WRAPPER}}:hover' => 'transform: scale({{SIZE}});',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_rotateX_hover',
			[
				'label' => esc_html__( 'Rotate X', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 1,
						'min' => 0,
						'max' => 360,
					],
				],
				'default' => [
					'size' => '15',
				],
				'condition' => [
					'sina_transform_effects' => 'rotateX',
				],
				'selectors' => [
					'{{WRAPPER}}:hover' => 'transform: rotateX({{SIZE}}deg);',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_rotateY_hover',
			[
				'label' => esc_html__( 'Rotate Y', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 1,
						'min' => 0,
						'max' => 360,
					],
				],
				'default' => [
					'size' => '15',
				],
				'condition' => [
					'sina_transform_effects' => 'rotateY',
				],
				'selectors' => [
					'{{WRAPPER}}:hover' => 'transform: rotateY({{SIZE}}deg);',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_rotateZ_hover',
			[
				'label' => esc_html__( 'Rotate Z', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 1,
						'min' => 0,
						'max' => 360,
					],
				],
				'default' => [
					'size' => '5',
				],
				'condition' => [
					'sina_transform_effects' => 'rotateZ',
				],
				'selectors' => [
					'{{WRAPPER}}:hover' => 'transform: rotateZ({{SIZE}}deg);',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_skewX_hover',
			[
				'label' => esc_html__( 'Skew X', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 1,
						'min' => -60,
						'max' => 60,
					],
				],
				'default' => [
					'size' => '10',
				],
				'condition' => [
					'sina_transform_effects' => 'skewX',
				],
				'selectors' => [
					'{{WRAPPER}}:hover' => 'transform: skewX({{SIZE}}deg);',
				],
			]
		);
		$elems->add_responsive_control(
			'sina_transform_effects_skewY_hover',
			[
				'label' => esc_html__( 'Skew Y', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 1,
						'min' => -60,
						'max' => 60,
					],
				],
				'default' => [
					'size' => '5',
				],
				'condition' => [
					'sina_transform_effects' => 'skewY',
				],
				'selectors' => [
					'{{WRAPPER}}:hover' => 'transform: skewY({{SIZE}}deg);',
				],
			]
		);
		$elems->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'sina_transform_effects_filters_hover',
				'selector' => '{{WRAPPER}}:hover',
			]
		);
		$elems->add_control(
			'sina_transform_effects_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'detheme-kit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'step' => 100,
						'min' => 0,
						'max' => 10000,
					],
				],
				'default' => [
					'size' => '400',
				],
				'selectors' => [
					'{{WRAPPER}}' => 'transition: all {{SIZE}}ms;',
				],
			]
		);

		$elems->end_controls_tab();

		$elems->end_controls_tabs();
	}
}