<?php


//START ELEMENT POST GRID
class nd_elements_list_element extends \Elementor\Widget_Base {

	public function get_name() { return 'list'; }
	public function get_title() { return __( 'list', 'nd-elements' ); }
	public function get_icon() { return 'fa fa-list'; }
	public function get_categories() { return [ 'nd-elements' ]; }

	
	/*START CONTROLS*/
	protected function _register_controls() {

	
		/*Create Tab*/
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Main Options', 'nd-elements' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);


		$this->add_control(
	      'list_layout',
	      [
	        'label' => __( 'Layout', 'nd-elements' ),
	        'type' => \Elementor\Controls_Manager::SELECT,
	        'default' => 'layout-1',
	        'options' => [
	          'layout-1'  => __( 'Layout 1', 'nd-elements' ),
	        ],
	      ]
	    );


	    $this->add_control(
			'list_title',
			[
				'label' => __( 'Title', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Title', 'nd-elements' ),
				'placeholder' => __( 'Type your title', 'nd-elements' ),
			]
		);

		$this->add_control(
			'list_description',
			[
				'label' => __( 'Description', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Description', 'nd-elements' ),
				'placeholder' => __( 'Type your description', 'nd-elements' ),
			]
		);

		$this->add_control(
			'list_label',
			[
				'label' => __( 'Label', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Label', 'nd-elements' ),
				'placeholder' => __( 'Type your label', 'nd-elements' ),
			]
		);

		$this->add_control(
			'list_cta',
			[
				'label' => __( 'Call To Action', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'CTA', 'nd-elements' ),
				'placeholder' => __( 'Type your call to action', 'nd-elements' ),
			]
		);


		$this->add_control(
			'list_image',
			[
				'label' => __( 'Image', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);


		$this->add_control(
			'list_link',
			[
				'label' => __( 'Link', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'nd-elements' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				],
			]
		);


		$this->end_controls_section();


		$this->start_controls_section(
			'style_section_title',
			[
				'label' => __( 'Title', 'nd-elements' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'list_title_typo',
				'label' => __( 'Typography', 'nd-elements' ),
				'selector' => '{{WRAPPER}} 
					.nd_elements_list_component .nd_elements_list_component_title',
			]
		);

		$this->add_control(
			'list_title_color',
			[
				'label' => __( 'Text Color', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .nd_elements_list_component .nd_elements_list_component_title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'list_title_bgcolor',
			[
				'label' => __( 'Background Color', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .nd_elements_list_component .nd_elements_list_component_title' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();



		$this->start_controls_section(
			'style_section_label',
			[
				'label' => __( 'Label', 'nd-elements' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'list_label_typo',
				'label' => __( 'Typography', 'nd-elements' ),
				'selector' => '{{WRAPPER}} 
					.nd_elements_list_component .nd_elements_list_component_label',
			]
		);

		$this->add_control(
			'list_label_color',
			[
				'label' => __( 'Text Color', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .nd_elements_list_component .nd_elements_list_component_label' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();



		$this->start_controls_section(
			'style_section_description',
			[
				'label' => __( 'Description', 'nd-elements' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'list_description_typo',
				'label' => __( 'Typography', 'nd-elements' ),
				'selector' => '{{WRAPPER}} 
					.nd_elements_list_component .nd_elements_list_component_description',
			]
		);

		$this->add_control(
			'list_description_color',
			[
				'label' => __( 'Text Color', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .nd_elements_list_component .nd_elements_list_component_description' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();




		$this->start_controls_section(
			'style_section_cta',
			[
				'label' => __( 'Call To Action', 'nd-elements' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'list_cta_typo',
				'label' => __( 'Typography', 'nd-elements' ),
				'selector' => '{{WRAPPER}} 
					.nd_elements_list_component .nd_elements_list_component_cta',
			]
		);

		$this->add_control(
			'list_cta_color',
			[
				'label' => __( 'Text Color', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .nd_elements_list_component .nd_elements_list_component_cta' => 'color: {{VALUE}}',
				],
			]
		);


		$this->add_control(
			'list_cta_bgcolor',
			[
				'label' => __( 'Background Color', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .nd_elements_list_component .nd_elements_list_component_cta span' => 'background-color: {{VALUE}}',
				],
			]
		);


		$this->add_control(
			'list_cta_padding',
			[
				'label' => __( 'Padding', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 5,
					'right' => 10,
					'bottom' => 5,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .nd_elements_list_component .nd_elements_list_component_cta span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();




		$this->start_controls_section(
			'style_section_image',
			[
				'label' => __( 'Image', 'nd-elements' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_control(
			'list_image_width',
			[
				'label' => __( 'Width', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 400,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .nd_elements_list_component .nd_elements_list_component_image' => 'width: {{SIZE}}px;',
					'{{WRAPPER}} .nd_elements_list_component .nd_elements_position_relative' => 'min-height: {{SIZE}}px;',
				],
			]
		);



		$this->end_controls_section();



		$this->start_controls_section(
			'style_section_content',
			[
				'label' => __( 'Content', 'nd-elements' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_control(
			'list_content_column_width_1',
			[
				'label' => __( 'Title Column Width', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 80,
				],
			]
		);


		$this->add_control(
			'list_content_padding',
			[
				'label' => __( 'Padding', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 20,
					'right' => 20,
					'bottom' => 20,
					'left' => 120,
				],
				'selectors' => [
					'{{WRAPPER}} .nd_elements_list_component .nd_elements_list_component_content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();







		

	}
	//END CONTROLS


 
	/*START RENDER*/
	protected function render() {

		$nd_elements_result = '';

  		//get datas
  		$nd_elements_settings = $this->get_settings_for_display();
		$list_layout = $nd_elements_settings['list_layout'];
		$list_title = $nd_elements_settings['list_title'];
		$list_description = $nd_elements_settings['list_description'];
		$list_label = $nd_elements_settings['list_label'];
		$list_cta = $nd_elements_settings['list_cta'];
		$list_image = $nd_elements_settings['list_image']['url'];
		$list_content_column_width_1 = $nd_elements_settings['list_content_column_width_1']['size'];
		$list_content_column_width_2 = 100-$nd_elements_settings['list_content_column_width_1']['size'];

		//link
		$list_link_target = $nd_elements_settings['list_link']['is_external'] ? ' target="_blank"' : '';
		$list_link_nofollow = $nd_elements_settings['list_link']['nofollow'] ? ' rel="nofollow"' : '';
		$list_link_url = $nd_elements_settings['list_link']['url'];

		//default values
		if ($list_layout == '') { $list_layout = "layout-1"; }

		//check with realpath
  		$nd_elements_layout_selected = dirname( __FILE__ ).'/layout/'.$list_layout.'.php';
  		include realpath($nd_elements_layout_selected);

  		$nd_elements_allowed_html = [
		    'div'      => [ 
				'class' => [],
				'style' => [],
			],
			'img'      => [
				'class' => [],
				'src' => [],
			],
			'h4'      => [],
			'span'      => [
				'class' => [],
			],
			'p'      => [
				'class' => [],
			],
			'a'      => [ 
				'rel' => [], 
				'target' => [],
				'href' => [],
			],
		];

		echo wp_kses( $nd_elements_result, $nd_elements_allowed_html );

	}
	//END RENDER


}
//END ELEMENT POST GRID
