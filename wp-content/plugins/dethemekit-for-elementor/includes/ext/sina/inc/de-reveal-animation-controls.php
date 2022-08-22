<?php
namespace De_Sina_Extension;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Css_Filter;

/**
 * De_Reveal_Animation_Controls Class for extends controls
 *
 * @since 3.0.1
 */
class De_Reveal_Animation_Controls{
	/**
	 * Instance
	 *
	 * @since 3.1.13
	 * @var De_Reveal_Animation_Controls The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 3.1.13
	 * @return De_Reveal_Animation_Controls An Instance of the class.
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
		return $template;
	}

	public function update_template_content($template,$widget) {
		return $template;
	}

	public function register_controls($elems) {
		$elems->start_controls_section(
			'de_reveal_animation_section',
			[
				'label' => __( 'De Reveal Animation', 'detheme-kit' ),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);

		$elems->add_control(
			'de_reveal_animation',
			[
				'label' => '<strong>'.esc_html__( 'De Reveal Animation', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::SWITCHER,
				'prefix_class' => 'de_reveal_animation_',
			]
		);

		$elems->add_control(
			'de_reveal_default_preview',
			[
				'type' => Controls_Manager::BUTTON,
				'button_type' => 'default',
				'text' => esc_html__( 'Preview', 'detheme-kit' ),
				'show_label' => false,
				'event' => 'RunPreviewDefault',
				'condition' => [ 'de_reveal_animation' => 'yes', 'de_reveal_animation_type' => 'default' ],
			]
		);

		$elems->add_control(
			'de_reveal_curtain_preview',
			[
				'type' => Controls_Manager::BUTTON,
				'button_type' => 'default',
				'text' => esc_html__( 'Preview', 'detheme-kit' ),
				'show_label' => false,
				'event' => 'RunPreviewCurtain',
				'condition' => [ 'de_reveal_animation' => 'yes', 'de_reveal_animation_type' => 'blockcurtain' ],
			]
		);

		$elems->add_control(
			'de_reveal_letter_preview',
			[
				'type' => Controls_Manager::BUTTON,
				'button_type' => 'default',
				'text' => esc_html__( 'Preview', 'detheme-kit' ),
				'show_label' => false,
				'event' => 'RunPreviewLetter',
				'condition' => [ 'de_reveal_animation' => 'yes', 'de_reveal_animation_type' => 'letter' ],
			]
		);

		$elems->add_control(
			'de_reveal_animation_type',
			[
				'label' => '<strong>'.esc_html__( 'Animation Type', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' => esc_html__( 'Block', 'detheme-kit' ),
					'blockcurtain' => esc_html__( 'Curtain', 'detheme-kit' ),
					'letter' => esc_html__( 'Letter', 'detheme-kit' ),
				],
				'default' => 'default',
				'prefix_class' => 'de_reveal_animation_type_',
				'condition' => [ 'de_reveal_animation' => 'yes' ],
			]
		);

		$elems->add_control(
			'de_reveal_animation_style',
			[
				'label' => '<strong>'.esc_html__( 'Animation Style', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::SELECT,
				'options' => [
					'fu' => esc_html__( 'Fade Up', 'detheme-kit' ),
					'fd' => esc_html__( 'Fade Down', 'detheme-kit' ),
					'fl' => esc_html__( 'Fade Left', 'detheme-kit' ),
					'fr' => esc_html__( 'Fade Right', 'detheme-kit' ),
					'rotate' => esc_html__( 'Rotate', 'detheme-kit' ),
					'scale' => esc_html__( 'Scale', 'detheme-kit' ),
				],
				'default' => 'fu',
				'prefix_class' => 'de_reveal_animation_style_',
				'condition' => [ 'de_reveal_animation' => 'yes', 'de_reveal_animation_type' => 'default'  ],
			]
		);

		$elems->add_control(
			'de_reveal_curtain_direction',
			[
				'label' => '<strong>'.esc_html__( 'Curtain Direction', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::SELECT,
				'options' => [
					'lr' => esc_html__( 'Left to Right', 'detheme-kit' ),
					'rl' => esc_html__( 'Right to Left', 'detheme-kit' ),
					'tb' => esc_html__( 'Top to Bottom', 'detheme-kit' ),
					'bt' => esc_html__( 'Bottom to Top', 'detheme-kit' ),
				],
				'default' => 'lr',
				'prefix_class' => 'de_reveal_curtain_direction_',
				'condition' => [ 'de_reveal_animation' => 'yes', 'de_reveal_animation_type' => 'blockcurtain' ],
			]
		);

		$elems->add_control(
			'de_reveal_curtain_color',
			[
				'label' => '<strong>'.esc_html__( 'Curtain Color', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .block-revealer__element' => 'background-color: {{VALUE}};',
				],
				'condition' => [ 'de_reveal_animation' => 'yes', 'de_reveal_animation_type' => 'blockcurtain' ],
			]
		);

		$elems->add_control(
			'de_reveal_curtain_delay',
			[
				'label' => '<strong>'.esc_html__( 'Curtain Delay (ms)', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::NUMBER,
				'prefix_class' => 'de_reveal_curtain_delay_',
				'default' => '0',
				'condition' => [ 'de_reveal_animation' => 'yes', 'de_reveal_animation_type' => 'blockcurtain' ],
			]
		);

		$elems->add_control(
			'de_reveal_letter_effects',
			[
				'label' => '<strong>'.esc_html__( 'Letter Effects', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::SELECT,
				'options' => [
					'fx1' => esc_html__( 'Effect 1', 'detheme-kit' ),
					'fx2' => esc_html__( 'Effect 2', 'detheme-kit' ),
					'fx3' => esc_html__( 'Effect 3', 'detheme-kit' ),
					'fx4' => esc_html__( 'Effect 4', 'detheme-kit' ),
					'fx5' => esc_html__( 'Effect 5', 'detheme-kit' ),
					'fx6' => esc_html__( 'Effect 6', 'detheme-kit' ),
					'fx7' => esc_html__( 'Effect 7', 'detheme-kit' ),
					'fx8' => esc_html__( 'Effect 8', 'detheme-kit' ),
					'fx9' => esc_html__( 'Effect 9', 'detheme-kit' ),
					'fx10' => esc_html__( 'Effect 10', 'detheme-kit' ),
					'fx11' => esc_html__( 'Effect 11', 'detheme-kit' ),
					'fx12' => esc_html__( 'Effect 12', 'detheme-kit' ),
					'fx13' => esc_html__( 'Effect 13', 'detheme-kit' ),
					'fx14' => esc_html__( 'Effect 14', 'detheme-kit' ),
					'fx15' => esc_html__( 'Effect 15', 'detheme-kit' ),
					'fx16' => esc_html__( 'Effect 16', 'detheme-kit' ),
					'fx17' => esc_html__( 'Effect 17', 'detheme-kit' ),
					'fx18' => esc_html__( 'Effect 18', 'detheme-kit' ),
				],
				'default' => 'fx1',
				'prefix_class' => 'de_reveal_letter_effects_',
				'condition' => [ 'de_reveal_animation' => 'yes', 'de_reveal_animation_type' => 'letter' ],
			]
		);

		$elems->add_control(
			'de_reveal_letter_initial_state',
			[
				'label' => '<strong>'.esc_html__( 'Initial State', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::SELECT,
				'options' => [
                    'hidden' => esc_html__( 'Hidden', 'detheme-kit' ),
                    'visible' => esc_html__( 'Visible', 'detheme-kit' ),
                ],
				'default' => 'hidden',
				'prefix_class' => 'de_reveal_letter_initial_state_',
				'condition' => [ 'de_reveal_animation' => 'yes', 'de_reveal_animation_type' => 'letter' ],
			]
		);

		$elems->add_control(
			'de_reveal_easing',
			[
				'label' => '<strong>'.esc_html__( 'Reveal Easing', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::SELECT,
				'options' => [
					'linear' => esc_html__( 'linear', 'detheme-kit' ),
					'easeInQuad' => esc_html__( 'easeInQuad', 'detheme-kit' ),
					'easeOutQuad' => esc_html__( 'easeOutQuad', 'detheme-kit' ),
					'easeInOutQuad' => esc_html__( 'easeInOutQuad', 'detheme-kit' ),
					'easeInCubic' => esc_html__( 'easeInCubic', 'detheme-kit' ),
					'easeOutCubic' => esc_html__( 'easeOutCubic', 'detheme-kit' ),
					'easeInOutCubic' => esc_html__( 'easeInOutCubic', 'detheme-kit' ),
					'easeInQuart' => esc_html__( 'easeInQuart', 'detheme-kit' ),
					'easeOutQuart' => esc_html__( 'easeOutQuart', 'detheme-kit' ),
					'easeInOutQuart' => esc_html__( 'easeInOutQuart', 'detheme-kit' ),
					'easeInQuint' => esc_html__( 'easeInQuint', 'detheme-kit' ),
					'easeOutQuint' => esc_html__( 'easeOutQuint', 'detheme-kit' ),
					'easeInOutQuint' => esc_html__( 'easeInOutQuint', 'detheme-kit' ),
					'easeInExpo' => esc_html__( 'easeInExpo', 'detheme-kit' ),
					'easeOutExpo' => esc_html__( 'easeOutExpo', 'detheme-kit' ),
					'easeInOutExpo' => esc_html__( 'easeInOutExpo', 'detheme-kit' ),
					'easeInSine' => esc_html__( 'easeInSine', 'detheme-kit' ),
					'easeOutSine' => esc_html__( 'easeOutSine', 'detheme-kit' ),
					'easeInOutSine' => esc_html__( 'easeInOutSine', 'detheme-kit' ),
					'easeInCirc' => esc_html__( 'easeInCirc', 'detheme-kit' ),
					'easeOutCirc' => esc_html__( 'easeOutCirc', 'detheme-kit' ),
					'easeInOutCirc' => esc_html__( 'easeInOutCirc', 'detheme-kit' ),
					'easeInElastic' => esc_html__( 'easeInElastic', 'detheme-kit' ),
					'easeOutElastic' => esc_html__( 'easeOutElastic', 'detheme-kit' ),
					'easeInOutElastic' => esc_html__( 'easeInOutElastic', 'detheme-kit' ),
					'easeInBack' => esc_html__( 'easeInBack', 'detheme-kit' ),
					'easeOutBack' => esc_html__( 'easeOutBack', 'detheme-kit' ),
					'easeInOutBack' => esc_html__( 'easeInOutBack', 'detheme-kit' ),
					'easeInBounce' => esc_html__( 'easeInBounce', 'detheme-kit' ),
					'easeOutBounce' => esc_html__( 'easeOutBounce', 'detheme-kit' ),
					'easeInOutBounce' => esc_html__( 'easeInOutBounce', 'detheme-kit' ),
				],
				'default' => 'linear',
				'prefix_class' => 'de_reveal_easing_',
				'condition' => [ 'de_reveal_animation' => 'yes', 'de_reveal_animation_type!' => 'letter' ],
			]
		);

		$elems->add_control(
			'de_reveal_default_rotation',
			[
				'label' => '<strong>'.esc_html__( 'Rotation Degree', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::NUMBER,
				'prefix_class' => 'de_reveal_default_rotation_',
				'default' => '0',
				'condition' => [ 'de_reveal_animation' => 'yes', 'de_reveal_animation_type' => 'default', 'de_reveal_animation_style' => 'rotate' ],
			]
		);

		$elems->add_control(
			'de_reveal_default_scale',
			[
				'label' => '<strong>'.esc_html__( 'Scale', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::NUMBER,
				'prefix_class' => 'de_reveal_default_scale_',
				'default' => '1',
				'condition' => [ 'de_reveal_animation' => 'yes', 'de_reveal_animation_type' => 'default', 'de_reveal_animation_style' => 'scale' ],
			]
		);

		$elems->add_control(
			'de_reveal_distance',
			[
				'label' => '<strong>'.esc_html__( 'Distance (px)', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::NUMBER,
				'prefix_class' => 'de_reveal_distance_',
				'default' => '200',
				'condition' => [ 'de_reveal_animation' => 'yes', 'de_reveal_animation_type' => 'default', 'de_reveal_animation_style' => ['fu','fd','fl','fr'] ],
			]
		);

		$elems->add_control(
			'de_reveal_default_delay',
			[
				'label' => '<strong>'.esc_html__( 'Delay (ms)', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::NUMBER,
				'prefix_class' => 'de_reveal_default_delay_',
				'default' => '0',
				'condition' => [ 'de_reveal_animation' => 'yes', 'de_reveal_animation_type' => 'default' ],
			]
		);

		$elems->add_control(
			'de_reveal_duration',
			[
				'label' => '<strong>'.esc_html__( 'Reveal Duration', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::NUMBER,
				'prefix_class' => 'de_reveal_duration_',
				'default' => '1000',
				'condition' => [ 'de_reveal_animation' => 'yes', 'de_reveal_animation_type!' => 'letter' ],
			]
		);

		$elems->add_control(
			'de_reveal_direction',
			[
				'label' => '<strong>'.esc_html__( 'Direction', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::SELECT,
				'options' => [
					'normal' => esc_html__( 'Normal', 'detheme-kit' ),
					'reverse' => esc_html__( 'Reverse', 'detheme-kit' ),
					'alternate' => esc_html__( 'Alternate', 'detheme-kit' ),
				],
				'default' => 'normal',
				'prefix_class' => 'de_reveal_direction_',
				'condition' => [ 'de_reveal_animation' => 'yes', 'de_reveal_animation_type' => 'default' ],
			]
		);

		$elems->add_control(
			'de_reveal_loop',
			[
				'label' => '<strong>'.esc_html__( 'Loop', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::SELECT,
				'options' => [
					'custom' => esc_html__( 'Custom', 'detheme-kit' ),
					'infinite' => esc_html__( 'Infinite', 'detheme-kit' ),
				],
				'default' => 'custom',
				'prefix_class' => 'de_reveal_loop_',
				'condition' => [ 'de_reveal_animation' => 'yes', 'de_reveal_animation_type' => 'default' ],
			]
		);

		$elems->add_control(
			'de_reveal_custom_loop',
			[
				'label' => '<strong>'.esc_html__( 'Number of loops', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::NUMBER,
				'prefix_class' => 'de_reveal_custom_loop_',
				'default' => 1,
				'min' => 1,
				'step' => 1,
				'condition' => [ 'de_reveal_animation' => 'yes', 'de_reveal_animation_type' => 'default', 'de_reveal_loop' => 'custom' ],
			]
		);

		$elems->add_control(
			'de_reveal_start',
			[
				'label' => '<strong>'.esc_html__( 'Start animate in view (0-1)', 'detheme-kit' ).'</strong>',
				'description' => esc_html__( 'This determines the percentage of the trigger that must be visible before the animation is triggered', 'detheme-kit' ),
				'type' => Controls_Manager::NUMBER,
				'prefix_class' => 'de_reveal_start_',
				'default' => 0.5,
                'min' => 0.0,
                'max' => 1.0,
                'step' => 0.1,
				'condition' => [ 'de_reveal_animation' => 'yes' ],
			]
		);

		$elems->add_control(
			'de_reveal_animate_in_viewport',
			[
				'label' => '<strong>'.esc_html__( 'Animate in viewport', 'detheme-kit' ).'</strong>',
				'description' => esc_html__( 'This determines how the element\'s animation will run each time the element in viewport', 'detheme-kit' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'runonce' => esc_html__( 'Run Once', 'detheme-kit' ),
					'alwaysrun' => esc_html__( 'Always Run', 'detheme-kit' ),
				],
				'default' => 'runonce',
				'prefix_class' => 'de_reveal_animate_in_viewport_',
				'condition' => [ 'de_reveal_animation' => 'yes' ],
			]
		);

		$elems->add_control(
			'de_reveal_on_desktop',
			[
				'label' => esc_html__( 'Run Animation on Desktop', 'detheme-kit' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'prefix_class' => 'de_reveal_on_desktop_',
				'condition' => [ 'de_reveal_animation' => 'yes' ],
			]
		);

		$elems->add_control(
			'de_reveal_on_tablet',
			[
				'label' => esc_html__( 'Run Animation on Tablet', 'detheme-kit' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'prefix_class' => 'de_reveal_on_tablet_',
				'condition' => [ 'de_reveal_animation' => 'yes' ],
			]
		);

		$elems->add_control(
			'de_reveal_on_mobile',
			[
				'label' => esc_html__( 'Run Animation on Mobile', 'detheme-kit' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'prefix_class' => 'de_reveal_on_mobile_',
				'condition' => [ 'de_reveal_animation' => 'yes' ],
			]
		);

		$elems->end_controls_section();
	}

	public function section_register_controls($elems) {
		$elems->start_controls_section(
			'de_reveal_animation_section',
			[
				'label' => __( 'De Reveal Animation', 'detheme-kit' ),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);

		$elems->add_control(
			'de_reveal_animation',
			[
				'label' => '<strong>'.esc_html__( 'De Reveal Animation', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::SWITCHER,
				'prefix_class' => 'de_reveal_animation_',
			]
		);

		$elems->add_control(
			'de_reveal_default_preview',
			[
				'type' => Controls_Manager::BUTTON,
				'button_type' => 'default',
				'text' => esc_html__( 'Preview', 'detheme-kit' ),
				'show_label' => false,
				'event' => 'RunPreviewDefault',
				'condition' => [ 'de_reveal_animation' => 'yes', 'de_reveal_animation_type' => 'default' ],
			]
		);

		$elems->add_control(
			'de_reveal_curtain_preview',
			[
				'type' => Controls_Manager::BUTTON,
				'button_type' => 'default',
				'text' => esc_html__( 'Preview', 'detheme-kit' ),
				'show_label' => false,
				'event' => 'RunPreviewCurtain',
				'condition' => [ 'de_reveal_animation' => 'yes', 'de_reveal_animation_type' => 'blockcurtain' ],
			]
		);

		$elems->add_control(
			'de_reveal_letter_preview',
			[
				'type' => Controls_Manager::BUTTON,
				'button_type' => 'default',
				'text' => esc_html__( 'Preview', 'detheme-kit' ),
				'show_label' => false,
				'event' => 'RunPreviewLetter',
				'condition' => [ 'de_reveal_animation' => 'yes', 'de_reveal_animation_type' => 'letter' ],
			]
		);

		$elems->add_control(
			'de_reveal_animation_type',
			[
				'label' => '<strong>'.esc_html__( 'Animation Type', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' => esc_html__( 'Block', 'detheme-kit' ),
					'blockcurtain' => esc_html__( 'Curtain', 'detheme-kit' ),
					'letter' => esc_html__( 'Letter', 'detheme-kit' ),
				],
				'default' => 'default',
				'prefix_class' => 'de_reveal_animation_type_',
				'condition' => [ 'de_reveal_animation' => 'yes' ],
			]
		);

		$elems->add_control(
			'de_reveal_animation_style',
			[
				'label' => '<strong>'.esc_html__( 'Animation Style', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::SELECT,
				'options' => [
					'fu' => esc_html__( 'Fade Up', 'detheme-kit' ),
					'fd' => esc_html__( 'Fade Down', 'detheme-kit' ),
					'fl' => esc_html__( 'Fade Left', 'detheme-kit' ),
					'fr' => esc_html__( 'Fade Right', 'detheme-kit' ),
					'rotate' => esc_html__( 'Rotate', 'detheme-kit' ),
					'scale' => esc_html__( 'Scale', 'detheme-kit' ),
				],
				'default' => 'fu',
				'prefix_class' => 'de_reveal_animation_style_',
				'condition' => [ 'de_reveal_animation' => 'yes', 'de_reveal_animation_type' => 'default'  ],
			]
		);

		$elems->add_control(
			'de_reveal_curtain_direction',
			[
				'label' => '<strong>'.esc_html__( 'Curtain Direction', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::SELECT,
				'options' => [
					'lr' => esc_html__( 'Left to Right', 'detheme-kit' ),
					'rl' => esc_html__( 'Right to Left', 'detheme-kit' ),
					'tb' => esc_html__( 'Top to Bottom', 'detheme-kit' ),
					'bt' => esc_html__( 'Bottom to Top', 'detheme-kit' ),
				],
				'default' => 'lr',
				'prefix_class' => 'de_reveal_curtain_direction_',
				'condition' => [ 'de_reveal_animation' => 'yes', 'de_reveal_animation_type' => 'blockcurtain' ],
			]
		);

		$elems->add_control(
			'de_reveal_curtain_color',
			[
				'label' => '<strong>'.esc_html__( 'Curtain Color', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{SELECTOR}} {{WRAPPER}} .block-revealer__element' => 'background-color: {{VALUE}};',
				],
				'condition' => [ 'de_reveal_animation' => 'yes', 'de_reveal_animation_type' => 'blockcurtain' ],
			]
		);

		$elems->add_control(
			'de_reveal_curtain_delay',
			[
				'label' => '<strong>'.esc_html__( 'Curtain Delay (ms)', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::NUMBER,
				'prefix_class' => 'de_reveal_curtain_delay_',
				'default' => '0',
				'condition' => [ 'de_reveal_animation' => 'yes', 'de_reveal_animation_type' => 'blockcurtain' ],
			]
		);

		$elems->add_control(
			'de_reveal_easing',
			[
				'label' => '<strong>'.esc_html__( 'Reveal Easing', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::SELECT,
				'options' => [
					'linear' => esc_html__( 'linear', 'detheme-kit' ),
					'easeInQuad' => esc_html__( 'easeInQuad', 'detheme-kit' ),
					'easeOutQuad' => esc_html__( 'easeOutQuad', 'detheme-kit' ),
					'easeInOutQuad' => esc_html__( 'easeInOutQuad', 'detheme-kit' ),
					'easeInCubic' => esc_html__( 'easeInCubic', 'detheme-kit' ),
					'easeOutCubic' => esc_html__( 'easeOutCubic', 'detheme-kit' ),
					'easeInOutCubic' => esc_html__( 'easeInOutCubic', 'detheme-kit' ),
					'easeInQuart' => esc_html__( 'easeInQuart', 'detheme-kit' ),
					'easeOutQuart' => esc_html__( 'easeOutQuart', 'detheme-kit' ),
					'easeInOutQuart' => esc_html__( 'easeInOutQuart', 'detheme-kit' ),
					'easeInQuint' => esc_html__( 'easeInQuint', 'detheme-kit' ),
					'easeOutQuint' => esc_html__( 'easeOutQuint', 'detheme-kit' ),
					'easeInOutQuint' => esc_html__( 'easeInOutQuint', 'detheme-kit' ),
					'easeInExpo' => esc_html__( 'easeInExpo', 'detheme-kit' ),
					'easeOutExpo' => esc_html__( 'easeOutExpo', 'detheme-kit' ),
					'easeInOutExpo' => esc_html__( 'easeInOutExpo', 'detheme-kit' ),
					'easeInSine' => esc_html__( 'easeInSine', 'detheme-kit' ),
					'easeOutSine' => esc_html__( 'easeOutSine', 'detheme-kit' ),
					'easeInOutSine' => esc_html__( 'easeInOutSine', 'detheme-kit' ),
					'easeInCirc' => esc_html__( 'easeInCirc', 'detheme-kit' ),
					'easeOutCirc' => esc_html__( 'easeOutCirc', 'detheme-kit' ),
					'easeInOutCirc' => esc_html__( 'easeInOutCirc', 'detheme-kit' ),
					'easeInElastic' => esc_html__( 'easeInElastic', 'detheme-kit' ),
					'easeOutElastic' => esc_html__( 'easeOutElastic', 'detheme-kit' ),
					'easeInOutElastic' => esc_html__( 'easeInOutElastic', 'detheme-kit' ),
					'easeInBack' => esc_html__( 'easeInBack', 'detheme-kit' ),
					'easeOutBack' => esc_html__( 'easeOutBack', 'detheme-kit' ),
					'easeInOutBack' => esc_html__( 'easeInOutBack', 'detheme-kit' ),
					'easeInBounce' => esc_html__( 'easeInBounce', 'detheme-kit' ),
					'easeOutBounce' => esc_html__( 'easeOutBounce', 'detheme-kit' ),
					'easeInOutBounce' => esc_html__( 'easeInOutBounce', 'detheme-kit' ),					
				],
				'default' => 'linear',
				'prefix_class' => 'de_reveal_easing_',
				'condition' => [ 'de_reveal_animation' => 'yes' ],
			]
		);

		$elems->add_control(
			'de_reveal_default_rotation',
			[
				'label' => '<strong>'.esc_html__( 'Rotation Degree', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::NUMBER,
				'prefix_class' => 'de_reveal_default_rotation_',
				'default' => '0',
				'condition' => [ 'de_reveal_animation' => 'yes', 'de_reveal_animation_type' => 'default', 'de_reveal_animation_style' => 'rotate' ],
			]
		);

		$elems->add_control(
			'de_reveal_default_scale',
			[
				'label' => '<strong>'.esc_html__( 'Scale', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::NUMBER,
				'prefix_class' => 'de_reveal_default_scale_',
				'default' => '1',
				'condition' => [ 'de_reveal_animation' => 'yes', 'de_reveal_animation_type' => 'default', 'de_reveal_animation_style' => 'scale' ],
			]
		);

		$elems->add_control(
			'de_reveal_distance',
			[
				'label' => '<strong>'.esc_html__( 'Distance (px)', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::NUMBER,
				'prefix_class' => 'de_reveal_distance_',
				'default' => '200',
				'condition' => [ 'de_reveal_animation' => 'yes', 'de_reveal_animation_type' => 'default', 'de_reveal_animation_style' => ['fu','fd','fl','fr'] ],
			]
		);

		$elems->add_control(
			'de_reveal_default_delay',
			[
				'label' => '<strong>'.esc_html__( 'Delay (ms)', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::NUMBER,
				'prefix_class' => 'de_reveal_default_delay_',
				'default' => '0',
				'condition' => [ 'de_reveal_animation' => 'yes', 'de_reveal_animation_type' => 'default' ],
			]
		);
		
		$elems->add_control(
			'de_reveal_duration',
			[
				'label' => '<strong>'.esc_html__( 'Reveal Duration', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::NUMBER,
				'prefix_class' => 'de_reveal_duration_',
				'default' => '1000',
				'condition' => [ 'de_reveal_animation' => 'yes' ],
			]
		);

		$elems->add_control(
			'de_reveal_direction',
			[
				'label' => '<strong>'.esc_html__( 'Direction', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::SELECT,
				'options' => [
					'normal' => esc_html__( 'Normal', 'detheme-kit' ),
					'reverse' => esc_html__( 'Reverse', 'detheme-kit' ),
					'alternate' => esc_html__( 'Alternate', 'detheme-kit' ),
				],
				'default' => 'normal',
				'prefix_class' => 'de_reveal_direction_',
				'condition' => [ 'de_reveal_animation' => 'yes', 'de_reveal_animation_type' => 'default' ],
			]
		);

		$elems->add_control(
			'de_reveal_loop',
			[
				'label' => '<strong>'.esc_html__( 'Loop', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::SELECT,
				'options' => [
					'custom' => esc_html__( 'Custom', 'detheme-kit' ),
					'infinite' => esc_html__( 'Infinite', 'detheme-kit' ),
				],
				'default' => 'custom',
				'prefix_class' => 'de_reveal_loop_',
				'condition' => [ 'de_reveal_animation' => 'yes', 'de_reveal_animation_type' => 'default' ],
			]
		);

		$elems->add_control(
			'de_reveal_custom_loop',
			[
				'label' => '<strong>'.esc_html__( 'Number of loops', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::NUMBER,
				'prefix_class' => 'de_reveal_custom_loop_',
				'default' => '1',
				'condition' => [ 'de_reveal_animation' => 'yes', 'de_reveal_animation_type' => 'default', 'de_reveal_loop' => 'custom' ],
			]
		);

		$elems->add_control(
			'de_reveal_start',
			[
				'label' => '<strong>'.esc_html__( 'Start animate in view (0-1)', 'detheme-kit' ).'</strong>',
				'description' => esc_html__( 'This determines the percentage of the trigger that must be visible before the animation is triggered', 'detheme-kit' ),
				'type' => Controls_Manager::NUMBER,
				'prefix_class' => 'de_reveal_start_',
				'default' => 0.5,
                'min' => 0.1,
                'max' => 1.0,
                'step' => 0.1,
				'condition' => [ 'de_reveal_animation' => 'yes' ],
			]
		);

		$elems->add_control(
			'de_reveal_animate_in_viewport',
			[
				'label' => '<strong>'.esc_html__( 'Animate in viewport', 'detheme-kit' ).'</strong>',
				'description' => esc_html__( 'This determines how the element\'s animation will run each time the element in viewport', 'detheme-kit' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'alwaysrun' => esc_html__( 'Always Run', 'detheme-kit' ),
					'runonce' => esc_html__( 'Run Once', 'detheme-kit' ),
				],
				'default' => 'alwaysrun',
				'prefix_class' => 'de_reveal_animate_in_viewport_',
				'condition' => [ 'de_reveal_animation' => 'yes' ],
			]
		);

		$elems->add_control(
			'de_reveal_stagger',
			[
				'label' => '<strong>'.esc_html__( 'Stagger animate child columns', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::SWITCHER,
				'prefix_class' => 'de_reveal_stagger_',
                'condition' => [ 'de_reveal_animation' => 'yes' ],
			]
		);

		$elems->add_control(
			'de_reveal_stagger_child_delay',
			[
				'label' => '<strong>'.esc_html__( 'Child Element Delay (ms)', 'detheme-kit' ).'</strong>',
				'type' => Controls_Manager::NUMBER,
				'prefix_class' => 'de_reveal_stagger_child_delay_',
				'default' => '500',
				'condition' => [ 'de_reveal_animation' => 'yes', 'de_reveal_stagger' => 'yes' ],
			]
		);

		$elems->add_control(
			'de_reveal_on_desktop',
			[
				'label' => esc_html__( 'Run Animation on Desktop', 'detheme-kit' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'prefix_class' => 'de_reveal_on_desktop_',
				'condition' => [ 'de_reveal_animation' => 'yes' ],
			]
		);

		$elems->add_control(
			'de_reveal_on_tablet',
			[
				'label' => esc_html__( 'Run Animation on Tablet', 'detheme-kit' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'prefix_class' => 'de_reveal_on_tablet_',
				'condition' => [ 'de_reveal_animation' => 'yes' ],
			]
		);

		$elems->add_control(
			'de_reveal_on_mobile',
			[
				'label' => esc_html__( 'Run Animation on Mobile', 'detheme-kit' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'prefix_class' => 'de_reveal_on_mobile_',
				'condition' => [ 'de_reveal_animation' => 'yes' ],
			]
		);

		$elems->end_controls_section();
	}

	public function column_register_controls($elems) {
	}
}