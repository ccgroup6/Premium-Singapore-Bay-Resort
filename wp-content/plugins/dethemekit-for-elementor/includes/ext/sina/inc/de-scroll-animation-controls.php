<?php
namespace De_Sina_Extension;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Css_Filter;

/**
 * De_Scroll_Animation_Controls Class for extends controls
 *
 * @since 3.0.1
 */
class De_Scroll_Animation_Controls{
	/**
	 * Instance
	 *
	 * @since 3.1.13
	 * @var De_Scroll_Animation_Controls The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 3.1.13
	 * @return De_Scroll_Animation_Controls An Instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct() {
		add_action('elementor/element/common/_section_style/after_section_end', [$this, 'register_controls']);
		add_action('elementor/element/column/section_advanced/before_section_end', [$this, 'column_register_controls']);
		add_action('elementor/element/section/section_advanced/before_section_end', [$this, 'column_register_controls']);

		add_filter( 'elementor/widget/render_content', [$this, 'render_template_content'], 10, 2 );
		add_filter( 'elementor/widget/print_template', [$this, 'update_template_content'], 10, 2 );
	}

	public function render_template_content($template,$widget) {
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
			'de_scroll_animation_section',
			[
				'label' => __( 'De Scroll Animation', 'detheme-kit' ),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);

		$elems->add_control(
			'de_scroll_animation',
			[
				'label' => esc_html__( 'De Scroll Animation', 'detheme-kit' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'prefix_class' => 'de_scroll_animation_',
			]
		);

		$elems->add_control(
			'de_scroll_animation_preview',
			[
				'label' => esc_html__( 'Run Animation on Preview', 'detheme-kit' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'prefix_class' => 'de_scroll_animation_preview_',
				'condition' => [ 'de_scroll_animation' => 'yes' ],
			]
		);

		$elems->add_control(
			'de_scroll_translateX_popover_toggle',
			[
				'label' => esc_html__( '- Translate X', 'detheme-kit' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'checked',
				'default' => 'unchecked',
				'prefix_class' => 'de_scroll_translateX_popover_',
				'condition' => [ 'de_scroll_animation' => 'yes' ],
			]
		);

		$elems->start_popover();

		$elems->add_control(
			'de_scroll_translateX_distance',
			[
				'label' => esc_html__( 'Distance (px)', 'detheme-kit' ),
				'type' => Controls_Manager::NUMBER,
				'prefix_class' => 'de_scroll_translateX_distance_',
				'default' => '500',
				'condition' => [ 'de_scroll_translateX_popover_toggle' => 'checked' ],
			]
		);

		$elems->end_popover();

        $elems->add_control(
			'de_scroll_translateY_popover_toggle',
			[
				'label' => esc_html__( '- Translate Y', 'detheme-kit' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'checked',
				'default' => 'unchecked',
				'prefix_class' => 'de_scroll_translateY_popover_',
				'condition' => [ 'de_scroll_animation' => 'yes' ],
			]
		);

		$elems->start_popover();

		$elems->add_control(
			'de_scroll_translateY_distance',
			[
				'label' => esc_html__( 'Distance (px)', 'detheme-kit' ),
				'type' => Controls_Manager::NUMBER,
				'prefix_class' => 'de_scroll_translateY_distance_',
				'default' => '500',
				'condition' => [ 'de_scroll_translateY_popover_toggle' => 'checked' ],
			]
		);

		$elems->end_popover();

        $elems->add_control(
			'de_scroll_rotate_popover_toggle',
			[
				'label' => esc_html__( '- Rotate', 'detheme-kit' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'checked',
				'default' => 'unchecked',
				'prefix_class' => 'de_scroll_rotate_popover_',
				'condition' => [ 'de_scroll_animation' => 'yes' ],
			]
		);

		$elems->start_popover();

		$elems->add_control(
			'de_scroll_rotate_distance',
			[
				'label' => esc_html__( 'Degree', 'detheme-kit' ),
				'type' => Controls_Manager::NUMBER,
				'prefix_class' => 'de_scroll_rotate_distance_',
				'default' => '90',
				'condition' => [ 'de_scroll_rotate_popover_toggle' => 'checked' ],
			]
		);

		$elems->end_popover();

        $elems->add_control(
			'de_scroll_scale_popover_toggle',
			[
				'label' => esc_html__( '- Scale', 'detheme-kit' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'checked',
				'default' => 'unchecked',
				'prefix_class' => 'de_scroll_scale_popover_',
				'condition' => [ 'de_scroll_animation' => 'yes' ],
			]
		);

		$elems->start_popover();

		$elems->add_control(
			'de_scroll_scale_distance',
			[
				'label' => esc_html__( 'Scale', 'detheme-kit' ),
				'type' => Controls_Manager::NUMBER,
				'prefix_class' => 'de_scroll_scale_distance_',
				'default' => '1.5',
				'condition' => [ 'de_scroll_scale_popover_toggle' => 'checked' ],
			]
		);

		$elems->end_popover();

        $elems->add_control(
			'de_scroll_skew_popover_toggle',
			[
				'label' => esc_html__( '- Skew', 'detheme-kit' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'checked',
				'default' => 'unchecked',
				'prefix_class' => 'de_scroll_skew_popover_',
				'condition' => [ 'de_scroll_animation' => 'yes' ],
			]
		);

		$elems->start_popover();

		$elems->add_control(
			'de_scroll_skew_distance',
			[
				'label' => esc_html__( 'Degree', 'detheme-kit' ),
				'type' => Controls_Manager::NUMBER,
				'prefix_class' => 'de_scroll_skew_distance_',
				'default' => '180',
				'condition' => [ 'de_scroll_skew_popover_toggle' => 'checked' ],
			]
		);

		$elems->end_popover();

		$elems->add_control(
			'de_scroll_start_animate',
			[
				'label' => '<strong>'.esc_html__( 'Start Animate (%)', 'detheme-kit' ).'</strong>',
				'description' => esc_html__( 'The percentage of the visible element part in viewport to start the element\'s animation', 'detheme-kit' ),
				'type' => Controls_Manager::NUMBER,
				'prefix_class' => 'de_scroll_start_animate_',
				'default' => 0,
				'min' => 0,
				'condition' => [ 'de_scroll_animation' => 'yes' ],
			]
		);

		$elems->add_control(
			'de_scroll_finish_animate',
			[
				'label' => '<strong>'.esc_html__( 'Finish Animate (%)', 'detheme-kit' ).'</strong>',
				'description' => esc_html__( 'The percentage of the visible element part in viewport to finish the element\'s animation', 'detheme-kit' ),
				'type' => Controls_Manager::NUMBER,
				'prefix_class' => 'de_scroll_finish_animate_',
				'default' => 100,
				'min' => 0,
				'condition' => [ 'de_scroll_animation' => 'yes' ],
			]
		);

		$elems->add_control(
			'de_scroll_animation_on_desktop',
			[
				'label' => esc_html__( 'Run Animation on Desktop', 'detheme-kit' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'prefix_class' => 'de_scroll_animation_on_desktop_',
				'condition' => [ 'de_scroll_animation' => 'yes' ],
			]
		);

		$elems->add_control(
			'de_scroll_animation_on_tablet',
			[
				'label' => esc_html__( 'Run Animation on Tablet', 'detheme-kit' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'prefix_class' => 'de_scroll_animation_on_tablet_',
				'condition' => [ 'de_scroll_animation' => 'yes' ],
			]
		);

		$elems->add_control(
			'de_scroll_animation_on_mobile',
			[
				'label' => esc_html__( 'Run Animation on Mobile', 'detheme-kit' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'prefix_class' => 'de_scroll_animation_on_mobile_',
				'condition' => [ 'de_scroll_animation' => 'yes' ],
			]
		);

		$elems->end_controls_section();
	}

	public function column_register_controls($elems) {
	}
}