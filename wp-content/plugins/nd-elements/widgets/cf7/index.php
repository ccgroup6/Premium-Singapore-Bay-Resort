<?php


//START ELEMENT CF7
class nd_elements_cf7_element extends \Elementor\Widget_Base {

	public function get_name() { return 'cf7'; }
	public function get_title() { return __( 'Contact Form 7', 'nd-elements' ); }
	public function get_icon() { return 'fa fa-envelope'; }
	public function get_categories() { return [ 'nd-elements' ]; }

	
	/*START CONTROLS*/
	protected function _register_controls() {

		
		/*Create Tab*/
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Options', 'nd-elements' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

	    //get all cf7 forms
		$nd_elements_cf7 = get_posts( 'post_type="wpcf7_contact_form"&numberposts=-1' );
		$nd_elements_contact_forms = array();
		if ( $nd_elements_cf7 ) {
			foreach ( $nd_elements_cf7 as $nd_elements_cform ) {
				$nd_elements_contact_forms[ $nd_elements_cform->ID ] = $nd_elements_cform->post_title;
			}
		} else {
			$nd_elements_contact_forms[ __( 'No contact forms found', 'nd-shortcodes' ) ] = 0;
		}
		//END get all cf7 forms

	    /*Create Control*/
		$this->add_control(
			'nd_elements_cf7',
			[
				'label' => __( 'Contact Form', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'solid',
				'options' => $nd_elements_contact_forms,
			]
		);

		$this->add_control(
			'navigation_align',
			[
				'label' => __( 'Text Alignment', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'nd-elements' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'nd-elements' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'nd-elements' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'left',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .nd_elements_cf7_component input[type="text"]' => 'text-align: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="email"]' => 'text-align: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="url"]' => 'text-align: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="tel"]' => 'text-align: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="number"]' => 'text-align: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="date"]' => 'text-align: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="file"]' => 'text-align: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="password"]' => 'text-align: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component select' => 'text-align: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component textarea' => 'text-align: {{VALUE}}'
				],
			]
		);

		$this->end_controls_section();


		/*************************START STYLE TAB*************************/


		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Fields Style', 'nd-elements' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'fields_typography',
				'label' => __( 'Typography', 'nd-elements' ),
				'selector' => '{{WRAPPER}} 
					.nd_elements_cf7_component input[type="text"],
					.nd_elements_cf7_component input[type="email"],
					.nd_elements_cf7_component input[type="url"],
					.nd_elements_cf7_component input[type="tel"],
					.nd_elements_cf7_component input[type="number"],
					.nd_elements_cf7_component input[type="date"],
					.nd_elements_cf7_component input[type="file"],
					.nd_elements_cf7_component input[type="password"],
					.nd_elements_cf7_component select,
					.nd_elements_cf7_component textarea',
			]
		);

		$this->add_control(
			'fields_padding',
			[
				'label' => __( 'Padding', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 10,
					'right' => 20,
					'bottom' => 10,
					'left' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .nd_elements_cf7_component input[type="text"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="email"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="url"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="tel"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="number"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="date"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="file"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="password"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					'{{WRAPPER}} .nd_elements_cf7_component select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					'{{WRAPPER}} .nd_elements_cf7_component textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
				],
			]
		);

		$this->add_responsive_control(
			'fields_width',
			[
				'label' => __( 'Width', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px','%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1200,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'desktop_default' => [
					'size' => 100,
					'unit' => '%',
				],
				'tablet_default' => [
					'size' => 100,
					'unit' => '%',
				],
				'mobile_default' => [
					'size' => 100,
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .nd_elements_cf7_component input[type="text"]' => 'width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="email"]' => 'width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="url"]' => 'width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="tel"]' => 'width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="number"]' => 'width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="date"]' => 'width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="file"]' => 'width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="password"]' => 'width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .nd_elements_cf7_component select' => 'width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .nd_elements_cf7_component textarea' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);


		$this->start_controls_tabs(
			'style_tabs_1'
		);

		$this->start_controls_tab(
			'style_normal_tab_1',
			[
				'label' => __( 'Normal', 'plugin-name' ),
			]
		);

		$this->add_control(
			'fields_color',
			[
				'label' => __( 'Text Color', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .nd_elements_cf7_component input[type="text"]' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="email"]' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="url"]' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="tel"]' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="number"]' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="date"]' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="file"]' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="password"]' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .nd_elements_cf7_component select' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .nd_elements_cf7_component textarea' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="text"]::-webkit-input-placeholder' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="email"]::-webkit-input-placeholder' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="url"]::-webkit-input-placeholder' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="tel"]::-webkit-input-placeholder' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="number"]::-webkit-input-placeholder' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="date"]::-webkit-input-placeholder' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="file"]::-webkit-input-placeholder' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="password"]::-webkit-input-placeholder' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .nd_elements_cf7_component select::-webkit-input-placeholder' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .nd_elements_cf7_component textarea::-webkit-input-placeholder' => 'color: {{VALUE}} !important',
				],
			]
		);

		$this->add_control(
			'fields_bgcolor',
			[
				'label' => __( 'Background Color', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .nd_elements_cf7_component input[type="text"]' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="email"]' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="url"]' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="tel"]' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="number"]' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="date"]' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="file"]' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="password"]' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component select' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component textarea' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'hr_border_1',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);


		$this->add_control(
			'fields_border_width',
			[
				'label' => __( 'Border Width', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .nd_elements_cf7_component input[type="text"]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="email"]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="url"]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="tel"]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="number"]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="date"]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="file"]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="password"]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					'{{WRAPPER}} .nd_elements_cf7_component select' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					'{{WRAPPER}} .nd_elements_cf7_component textarea' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'fields_border_color',
			[
				'label' => __( 'Border Color', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#f1f1f1',
				'selectors' => [
					'{{WRAPPER}} .nd_elements_cf7_component input[type="text"]' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="email"]' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="url"]' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="tel"]' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="number"]' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="date"]' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="file"]' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="password"]' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component select' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component textarea' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'fields_border_radius',
			[
				'label' => __( 'Border Radius', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .nd_elements_cf7_component input[type="text"]' => 'border-radius: {{SIZE}}px',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="email"]' => 'border-radius: {{SIZE}}px',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="url"]' => 'border-radius: {{SIZE}}px',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="tel"]' => 'border-radius: {{SIZE}}px',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="number"]' => 'border-radius: {{SIZE}}px',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="date"]' => 'border-radius: {{SIZE}}px',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="file"]' => 'border-radius: {{SIZE}}px',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="password"]' => 'border-radius: {{SIZE}}px',
					'{{WRAPPER}} .nd_elements_cf7_component select' => 'border-radius: {{SIZE}}px',
					'{{WRAPPER}} .nd_elements_cf7_component textarea' => 'border-radius: {{SIZE}}px',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_hover_tab_1',
			[
				'label' => __( 'Hover', 'plugin-name' ),
			]
		);

		$this->add_control(
			'fields_color_hover',
			[
				'label' => __( 'Text Color', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .nd_elements_cf7_component input[type="text"]:hover' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="email"]:hover' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="url"]:hover' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="tel"]:hover' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="number"]:hover' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="date"]:hover' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="file"]:hover' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="password"]:hover' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .nd_elements_cf7_component select:hover' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .nd_elements_cf7_component textarea:hover' => 'color: {{VALUE}} !important',
				],
			]
		);

		$this->add_control(
			'fields_bgcolor_hover',
			[
				'label' => __( 'Background Color', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .nd_elements_cf7_component input[type="text"]:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="email"]:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="url"]:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="tel"]:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="number"]:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="date"]:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="file"]:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="password"]:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component select:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component textarea:hover' => 'background-color: {{VALUE}}',
				],
			]
		);


		$this->add_control(
			'hrh_border_1',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);


		$this->add_control(
			'fields_border_width_hover',
			[
				'label' => __( 'Border Width', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .nd_elements_cf7_component input[type="text"]:hover' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="email"]:hover' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="url"]:hover' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="tel"]:hover' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="number"]:hover' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="date"]:hover' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="file"]:hover' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="password"]:hover' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					'{{WRAPPER}} .nd_elements_cf7_component select:hover' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					'{{WRAPPER}} .nd_elements_cf7_component textarea:hover' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'fields_border_color_hover',
			[
				'label' => __( 'Border Color', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#f1f1f1',
				'selectors' => [
					'{{WRAPPER}} .nd_elements_cf7_component input[type="text"]:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="email"]:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="url"]:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="tel"]:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="number"]:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="date"]:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="file"]:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="password"]:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component select:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_cf7_component textarea:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'fields_border_radius_hover',
			[
				'label' => __( 'Border Radius', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .nd_elements_cf7_component input[type="text"]:hover' => 'border-radius: {{SIZE}}px',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="email"]:hover' => 'border-radius: {{SIZE}}px',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="url"]:hover' => 'border-radius: {{SIZE}}px',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="tel"]:hover' => 'border-radius: {{SIZE}}px',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="number"]:hover' => 'border-radius: {{SIZE}}px',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="date"]:hover' => 'border-radius: {{SIZE}}px',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="file"]:hover' => 'border-radius: {{SIZE}}px',
					'{{WRAPPER}} .nd_elements_cf7_component input[type="password"]:hover' => 'border-radius: {{SIZE}}px',
					'{{WRAPPER}} .nd_elements_cf7_component select:hover' => 'border-radius: {{SIZE}}px',
					'{{WRAPPER}} .nd_elements_cf7_component textarea:hover' => 'border-radius: {{SIZE}}px',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		

		$this->end_controls_section();


		$this->start_controls_section(
			'style_section_2',
			[
				'label' => __( 'Button Style', 'nd-elements' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'label' => __( 'Typography', 'nd-elements' ),
				'selector' => '{{WRAPPER}} .nd_elements_cf7_component input[type="submit"]',
			]
		);

		$this->add_control(
			'button_padding',
			[
				'label' => __( 'Padding', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 10,
					'right' => 20,
					'bottom' => 10,
					'left' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .nd_elements_cf7_component input[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_width',
			[
				'label' => __( 'Width', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px','%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1200,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'desktop_default' => [
					'size' => 100,
					'unit' => '%',
				],
				'tablet_default' => [
					'size' => 100,
					'unit' => '%',
				],
				'mobile_default' => [
					'size' => 100,
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .nd_elements_cf7_component input[type="submit"]' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);





		$this->start_controls_tabs(
			'style_tabs'
		);

		$this->start_controls_tab(
			'style_normal_tab',
			[
				'label' => __( 'Normal', 'plugin-name' ),
			]
		);

		$this->add_control(
			'button_color',
			[
				'label' => __( 'Text Color', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .nd_elements_cf7_component input[type="submit"]' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_bgcolor',
			[
				'label' => __( 'Background Color', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .nd_elements_cf7_component input[type="submit"]' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'hr_border',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);


		$this->add_control(
			'button_border_width',
			[
				'label' => __( 'Border Width', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .nd_elements_cf7_component input[type="submit"]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label' => __( 'Border Color', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#f1f1f1',
				'selectors' => [
					'{{WRAPPER}} .nd_elements_cf7_component input[type="submit"]' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .nd_elements_cf7_component input[type="submit"]' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_hover_tab',
			[
				'label' => __( 'Hover', 'plugin-name' ),
			]
		);

		$this->add_control(
			'button_color_hover',
			[
				'label' => __( 'Text Color', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .nd_elements_cf7_component input[type="submit"]:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_bgcolor_hover',
			[
				'label' => __( 'Background Color', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .nd_elements_cf7_component input[type="submit"]:hover' => 'background-color: {{VALUE}}',
				],
			]
		);


		$this->add_control(
			'hrh_border',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);


		$this->add_control(
			'button_border_width_hover',
			[
				'label' => __( 'Border Width', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .nd_elements_cf7_component input[type="submit"]:hover' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'button_border_color_hover',
			[
				'label' => __( 'Border Color', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#f1f1f1',
				'selectors' => [
					'{{WRAPPER}} .nd_elements_cf7_component input[type="submit"]:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_border_radius_hover',
			[
				'label' => __( 'Border Radius', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .nd_elements_cf7_component input[type="submit"]:hover' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();





		$this->end_controls_section();

	}


 
	/*START RENDER*/
	protected function render() {

		$nd_elements_settings = $this->get_settings_for_display();
		$nd_elements_cf7_id = $nd_elements_settings['nd_elements_cf7'];
		
		$nd_elements_result = '
		<div class="nd_elements_cf7_component">
			'.do_shortcode('[contact-form-7 id="'.$nd_elements_cf7_id.'"]').'
		</div>';

  		$nd_elements_allowed_html = [
		    'div'      => [
				'role' => [],
				'class' => [],
				'id' => [],
				'lang' => [], 
				'dir' => [],
				'style' => [],
				'aria-hidden' => [],
			],
			'p'      => [ 
				'role' => [],
				'aria-live' => [],
				'aria-atomic' => [],
			],
			'ul'      => [],
			'li'      => [],
			'br'      => [],
			'label'      => [],
			'form'      => [ 
				'action' => [],
				'method' => [],
				'class' => [],
				'enctype' => [],
				'novalidate' => [],
				'data-status' => [],
			],
			'input'      => [ 
				'type' => [],
				'name' => [],
				'value' => [],
				'size' => [],
				'class' => [],
				'id' => [],
				'aria-required' => [],
				'aria-invalid' => [],
				'placeholder' => [],
				'checked' => [],
				'min' => [],
				'max' => [],
				'autocomplete' => [],
				'accept' => [],
			],
			'span'      => [ 
				'class' => [],
				'data-name' => [],
				'id' => [],
			],
			'textarea'      => [ 
				'name' => [],
				'cols' => [],
				'rows' => [],
				'class' => [],
				'id' => [],
				'aria-required' => [],
				'aria-invalid' => [],
				'placeholder' => [],
			],
			'select'      => [ 
				'name' => [],
				'class' => [],
				'id' => [],
				'aria-required' => [],
				'aria-invalid' => [],
				'multiple' => [],
			],
			'option'      => [ 
				'value' => [],
			],
		];

		echo wp_kses( $nd_elements_result, $nd_elements_allowed_html );
		#echo $nd_elements_result;

	}




}
