<?php
/**
 * Post Comment Option Class
 *
 * @package jeg-elementor-kit
 * @author Jegstudio
 * @since 1.5.0
 */

namespace Jeg\Elementor_Kit\Elements\Options;

/**
 * Class Post_Comment_Option
 *
 * @package Jeg\Elementor_Kit\Elements\Options
 */
class Post_Comment_Option extends Option_Abstract {
	/**
	 * Show color scheme flag for element.
	 *
	 * @return bool
	 */
	public function show_color_scheme() {
		return false;
	}

	/**
	 * Compatibility column
	 *
	 * @return array
	 */
	public function compatible_column() {
		return array();
	}

	/**
	 * Override function to remove compatible column alert
	 */
	public function set_compatible_column_option() {
	}

	/**
	 * Element name
	 *
	 * @return string
	 */
	public function get_element_name() {
		return esc_html__( 'JKit - Post Comment', 'jeg-elementor-kit' );
	}

	/**
	 * Element category
	 *
	 * @return string
	 */
	public function get_category() {
		return esc_html__( 'Jeg Elementor Kit - Single Post', 'jeg-elementor-kit' );
	}

	/**
	 * Element options
	 */
	public function set_options() {
		$this->set_style_option();
		$this->set_element_options();

		parent::set_options();
	}

	/**
	 * Option segments
	 */
	public function set_segments() {
		$this->segments['segment_comment'] = array(
			'name'     => esc_html__( 'Post Comment', 'jeg-elementor-kit' ),
			'priority' => 10,
		);

		$this->set_style_segment();
	}

	/**
	 * Style segments
	 */
	public function set_style_segment() {
		$this->segments['style_form_fields'] = array(
			'name'      => esc_html__( 'Form Fields', 'jeg-elementor-kit' ),
			'priority'  => 11,
			'kit_style' => true,
		);

		$this->segments['style_button'] = array(
			'name'      => esc_html__( 'Button', 'jeg-elementor-kit' ),
			'priority'  => 12,
			'kit_style' => true,
		);

		parent::set_style_segment();
	}

	/**
	 * Set element option
	 */
	public function set_element_options() {
		$this->options['sg_comment_notice'] = array(
			'type'    => 'raw',
			'title'   => '',
			'segment' => 'segment_comment',
			'raw'     => esc_html__( 'This widget uses the currently active theme comments design and layout to display the comment form and comments.', 'jeg-elementor-kit' ),
			'classes' => 'elementor-descriptor',
		);
	}

	/**
	 * Add Additional Style.
	 */
	public function additional_style() {
		$this->options['st_form_label_heading'] = array(
			'type'    => 'heading',
			'title'   => esc_html__( 'Label', 'jeg-elementor-kit' ),
			'segment' => 'style_form_fields',
		);

		$this->options['st_form_label_typography'] = array(
			'type'      => 'typography',
			'title'     => esc_html__( 'Typography', 'jeg-elementor-kit' ),
			'segment'   => 'style_form_fields',
			'selectors' => '.jeg-elementor-kit.jkit-post-comment #comments .comment-form label',
		);

		$this->options['st_form_label_text_color'] = array(
			'type'      => 'color',
			'title'     => esc_html__( 'Text Color', 'jeg-elementor-kit' ),
			'segment'   => 'style_form_fields',
			'selectors' => '.jeg-elementor-kit.jkit-post-comment #comments .comment-form label',
		);

		$this->options['st_form_label_required_text_color'] = array(
			'type'      => 'color',
			'title'     => esc_html__( 'Reuired Color', 'jeg-elementor-kit' ),
			'segment'   => 'style_form_fields',
			'selectors' => '.jeg-elementor-kit.jkit-post-comment #comments .comment-form label .required',
		);

		$this->options['st_form_field_heading'] = array(
			'type'      => 'heading',
			'title'     => esc_html__( 'Field', 'jeg-elementor-kit' ),
			'segment'   => 'style_form_fields',
			'separator' => 'before',
		);

		$this->options['st_form_field_padding'] = array(
			'type'      => 'dimension',
			'title'     => esc_html__( 'Padding', 'jeg-elementor-kit' ),
			'segment'   => 'style_form_fields',
			'units'     => array( 'px', 'em' ),
			'selectors' => '.jeg-elementor-kit.jkit-post-comment #comments .comment-form #comment',
			'attribute' => 'padding',
		);

		$this->options['st_form_field_typography'] = array(
			'type'      => 'typography',
			'title'     => esc_html__( 'Typography', 'jeg-elementor-kit' ),
			'segment'   => 'style_form_fields',
			'selectors' => '.jeg-elementor-kit.jkit-post-comment #comments .comment-form #comment',
		);

		$this->options['st_form_field_tabs_start'] = array(
			'type'    => 'control_tabs_start',
			'segment' => 'style_form_fields',
		);

		$this->options['st_form_field_normal_tab_start'] = array(
			'type'    => 'control_tab_start',
			'title'   => esc_html__( 'Normal', 'jeg-elementor-kit' ),
			'segment' => 'style_form_fields',
		);

		$this->options['st_form_field_normal_text_color'] = array(
			'type'      => 'color',
			'title'     => esc_html__( 'Text Color', 'jeg-elementor-kit' ),
			'segment'   => 'style_form_fields',
			'selectors' => '.jeg-elementor-kit.jkit-post-comment #comments .comment-form #comment',
		);

		$this->options['st_form_field_normal_background'] = array(
			'type'      => 'background',
			'title'     => esc_html__( 'Background', 'jeg-elementor-kit' ),
			'segment'   => 'style_form_fields',
			'selectors' => '.jeg-elementor-kit.jkit-post-comment #comments .comment-form #comment',
			'options'   => array(
				'classic',
				'gradient',
			),
		);

		$this->options['st_form_field_normal_border'] = array(
			'type'      => 'border',
			'title'     => esc_html__( 'Border', 'jeg-elementor-kit' ),
			'segment'   => 'style_form_fields',
			'selectors' => '.jeg-elementor-kit.jkit-post-comment #comments .comment-form #comment',
		);

		$this->options['st_form_field_normal_radius'] = array(
			'type'      => 'dimension',
			'title'     => esc_html__( 'Border Radius', 'jeg-elementor-kit' ),
			'segment'   => 'style_form_fields',
			'units'     => array( 'px', '%', 'em' ),
			'selectors' => '.jeg-elementor-kit.jkit-post-comment #comments .comment-form #comment',
			'attribute' => 'border-radius',
		);

		$this->options['st_form_field_normal_boxshadow'] = array(
			'type'      => 'boxshadow',
			'title'     => esc_html__( 'Button Shadow', 'jeg-elementor-kit' ),
			'segment'   => 'style_form_fields',
			'selectors' => '.jeg-elementor-kit.jkit-post-comment #comments .comment-form #comment',
		);

		$this->options['st_form_field_normal_tab_end'] = array(
			'type'    => 'control_tab_end',
			'segment' => 'style_form_fields',
		);

		$this->options['st_form_field_focus_tab_start'] = array(
			'type'    => 'control_tab_start',
			'title'   => esc_html__( 'Focus', 'jeg-elementor-kit' ),
			'segment' => 'style_form_fields',
		);

		$this->options['st_form_field_focus_transition_duration'] = array(
			'type'      => 'slider',
			'title'     => esc_html__( 'Transition Duration', 'jeg-elementor-kit' ),
			'segment'   => 'style_form_fields',
			'options'   => array(
				'min'  => 0,
				'max'  => 10,
				'step' => 0.1,
			),
			'selectors' => array(
				'custom' => array(
					'{{WRAPPER}} .jeg-elementor-kit.jkit-post-comment #comments .comment-form #comment' => 'transition-duration: {{SIZE}}s;',
				),
			),
		);

		$this->options['st_form_field_focus_text_color'] = array(
			'type'      => 'color',
			'title'     => esc_html__( 'Text Color', 'jeg-elementor-kit' ),
			'segment'   => 'style_form_fields',
			'selectors' => '.jeg-elementor-kit.jkit-post-comment #comments .comment-form #comment:focus',
		);

		$this->options['st_form_field_focus_background'] = array(
			'type'      => 'background',
			'title'     => esc_html__( 'Background', 'jeg-elementor-kit' ),
			'segment'   => 'style_form_fields',
			'selectors' => '.jeg-elementor-kit.jkit-post-comment #comments .comment-form #comment:focus',
			'options'   => array(
				'classic',
				'gradient',
			),
		);

		$this->options['st_form_field_focus_border'] = array(
			'type'      => 'border',
			'title'     => esc_html__( 'Border', 'jeg-elementor-kit' ),
			'segment'   => 'style_form_fields',
			'selectors' => '.jeg-elementor-kit.jkit-post-comment #comments .comment-form #comment:focus',
		);

		$this->options['st_form_field_focus_radius'] = array(
			'type'      => 'dimension',
			'title'     => esc_html__( 'Border Radius', 'jeg-elementor-kit' ),
			'segment'   => 'style_form_fields',
			'units'     => array( 'px', '%', 'em' ),
			'selectors' => '.jeg-elementor-kit.jkit-post-comment #comments .comment-form #comment:focus',
			'attribute' => 'border-radius',
		);

		$this->options['st_form_fieldhoverl_boxshadow'] = array(
			'type'      => 'boxshadow',
			'title'     => esc_html__( 'Button Shadow', 'jeg-elementor-kit' ),
			'segment'   => 'style_form_fields',
			'selectors' => '.jeg-elementor-kit.jkit-post-comment #comments .comment-form #comment:focus',
		);

		$this->options['st_form_field_focus_tab_end'] = array(
			'type'    => 'control_tab_end',
			'segment' => 'style_form_fields',
		);

		$this->options['st_form_field_tabs_end'] = array(
			'type'    => 'control_tabs_end',
			'segment' => 'style_form_fields',
		);

		$this->options['st_button_padding'] = array(
			'type'      => 'dimension',
			'title'     => esc_html__( 'Padding', 'jeg-elementor-kit' ),
			'segment'   => 'style_button',
			'units'     => array( 'px', 'em' ),
			'selectors' => '.jeg-elementor-kit.jkit-post-comment #comments #respond .comment-form .form-submit #submit',
			'attribute' => 'padding',
		);

		$this->options['st_button_typography'] = array(
			'type'      => 'typography',
			'title'     => esc_html__( 'Typography', 'jeg-elementor-kit' ),
			'segment'   => 'style_button',
			'selectors' => '.jeg-elementor-kit.jkit-post-comment #comments #respond .comment-form .form-submit #submit',
		);

		$this->options['st_button_tabs_start'] = array(
			'type'    => 'control_tabs_start',
			'segment' => 'style_button',
		);

		$this->options['st_button_normal_tab_start'] = array(
			'type'    => 'control_tab_start',
			'title'   => esc_html__( 'Normal', 'jeg-elementor-kit' ),
			'segment' => 'style_button',
		);

		$this->options['st_button_normal_text_color'] = array(
			'type'      => 'color',
			'title'     => esc_html__( 'Text Color', 'jeg-elementor-kit' ),
			'segment'   => 'style_button',
			'selectors' => '.jeg-elementor-kit.jkit-post-comment #comments #respond .comment-form .form-submit #submit',
		);

		$this->options['st_button_normal_background'] = array(
			'type'      => 'background',
			'title'     => esc_html__( 'Background', 'jeg-elementor-kit' ),
			'segment'   => 'style_button',
			'selectors' => '.jeg-elementor-kit.jkit-post-comment #comments #respond .comment-form .form-submit #submit',
			'options'   => array(
				'classic',
				'gradient',
			),
		);

		$this->options['st_button_normal_border'] = array(
			'type'      => 'border',
			'title'     => esc_html__( 'Border', 'jeg-elementor-kit' ),
			'segment'   => 'style_button',
			'selectors' => '.jeg-elementor-kit.jkit-post-comment #comments #respond .comment-form .form-submit #submit',
		);

		$this->options['st_button_normal_radius'] = array(
			'type'      => 'dimension',
			'title'     => esc_html__( 'Border Radius', 'jeg-elementor-kit' ),
			'segment'   => 'style_button',
			'units'     => array( 'px', '%', 'em' ),
			'selectors' => '.jeg-elementor-kit.jkit-post-comment #comments #respond .comment-form .form-submit #submit',
			'attribute' => 'border-radius',
		);

		$this->options['st_button_normal_boxshadow'] = array(
			'type'      => 'boxshadow',
			'title'     => esc_html__( 'Button Shadow', 'jeg-elementor-kit' ),
			'segment'   => 'style_button',
			'selectors' => '.jeg-elementor-kit.jkit-post-comment #comments #respond .comment-form .form-submit #submit',
		);

		$this->options['st_button_normal_textshadow'] = array(
			'type'      => 'textshadow',
			'title'     => esc_html__( 'Text Shadow', 'jeg-elementor-kit' ),
			'segment'   => 'style_button',
			'selectors' => '.jeg-elementor-kit.jkit-post-comment #comments #respond .comment-form .form-submit #submit',
		);

		$this->options['st_button_normal_tab_end'] = array(
			'type'    => 'control_tab_end',
			'segment' => 'style_button',
		);

		$this->options['st_button_hover_tab_start'] = array(
			'type'    => 'control_tab_start',
			'title'   => esc_html__( 'Hover', 'jeg-elementor-kit' ),
			'segment' => 'style_button',
		);

		$this->options['st_button_hover_transition_duration'] = array(
			'type'      => 'slider',
			'title'     => esc_html__( 'Transition Duration', 'jeg-elementor-kit' ),
			'segment'   => 'style_button',
			'options'   => array(
				'min'  => 0,
				'max'  => 10,
				'step' => 0.1,
			),
			'selectors' => array(
				'custom' => array(
					'{{WRAPPER}} .jeg-elementor-kit.jkit-post-comment #comments #respond .comment-form .form-submit #submit' => 'transition-duration: {{SIZE}}s;',
				),
			),
		);

		$this->options['st_button_hover_text_color'] = array(
			'type'      => 'color',
			'title'     => esc_html__( 'Text Color', 'jeg-elementor-kit' ),
			'segment'   => 'style_button',
			'selectors' => '.jeg-elementor-kit.jkit-post-comment #comments #respond .comment-form .form-submit #submit:hover',
		);

		$this->options['st_button_hover_background'] = array(
			'type'      => 'background',
			'title'     => esc_html__( 'Background', 'jeg-elementor-kit' ),
			'segment'   => 'style_button',
			'selectors' => '.jeg-elementor-kit.jkit-post-comment #comments #respond .comment-form .form-submit #submit:hover',
			'options'   => array(
				'classic',
				'gradient',
			),
		);

		$this->options['st_button_hover_border'] = array(
			'type'      => 'border',
			'title'     => esc_html__( 'Border', 'jeg-elementor-kit' ),
			'segment'   => 'style_button',
			'selectors' => '.jeg-elementor-kit.jkit-post-comment #comments #respond .comment-form .form-submit #submit:hover',
		);

		$this->options['st_button_hover_radius'] = array(
			'type'      => 'dimension',
			'title'     => esc_html__( 'Border Radius', 'jeg-elementor-kit' ),
			'segment'   => 'style_button',
			'units'     => array( 'px', '%', 'em' ),
			'selectors' => '.jeg-elementor-kit.jkit-post-comment #comments #respond .comment-form .form-submit #submit:hover',
			'attribute' => 'border-radius',
		);

		$this->options['st_buttonhoverl_boxshadow'] = array(
			'type'      => 'boxshadow',
			'title'     => esc_html__( 'Button Shadow', 'jeg-elementor-kit' ),
			'segment'   => 'style_button',
			'selectors' => '.jeg-elementor-kit.jkit-post-comment #comments #respond .comment-form .form-submit #submit:hover',
		);

		$this->options['st_button_hover_textshadow'] = array(
			'type'      => 'textshadow',
			'title'     => esc_html__( 'Text Shadow', 'jeg-elementor-kit' ),
			'segment'   => 'style_button',
			'selectors' => '.jeg-elementor-kit.jkit-post-comment #comments #respond .comment-form .form-submit #submit:hover',
		);

		$this->options['st_button_hover_tab_end'] = array(
			'type'    => 'control_tab_end',
			'segment' => 'style_button',
		);

		$this->options['st_button_tabs_end'] = array(
			'type'    => 'control_tabs_end',
			'segment' => 'style_button',
		);
	}
}
