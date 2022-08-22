<?php


//START ELEMENT POST GRID
class nd_elements_marquee_element extends \Elementor\Widget_Base {

	public function get_name() { return 'marquee'; }
	public function get_title() { return __( 'Marquee', 'nd-elements' ); }
	public function get_icon() { return 'fa fa-newspaper'; }
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
	      'marquee_layout',
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
			'marquee_label',
			[
				'label' => __( 'Label Text', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'NEWS', 'nd-elements' ),
				'placeholder' => __( 'Type your label', 'nd-elements' ),
			]
		);


		$this->add_control(
			'marquee_content',
			[
				'label' => __( 'Content', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 3,
				'default' => __( 'This is the latest News', 'nd-elements' ),
				'placeholder' => __( 'Type your content here', 'nd-elements' ),
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Label', 'nd-elements' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
				'label' => __( 'Typography', 'nd-elements' ),
				'selector' => '{{WRAPPER}} 
					.nd_elements_marquee_component .nd_elements_marquee_label',
			]
		);

		$this->add_control(
			'label_padding',
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
					'{{WRAPPER}} .nd_elements_marquee_component .nd_elements_marquee_label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
				],
			]
		);

		$this->add_control(
			'label_color',
			[
				'label' => __( 'Text Color', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .nd_elements_marquee_component .nd_elements_marquee_label' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'label_bgcolor',
			[
				'label' => __( 'Background Color', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .nd_elements_marquee_component .nd_elements_marquee_label' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'style_section_2',
			[
				'label' => __( 'Content', 'nd-elements' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'label' => __( 'Typography', 'nd-elements' ),
				'selector' => '{{WRAPPER}} 
					.nd_elements_marquee_component .nd_elements_marquee_content',
			]
		);


		$this->add_control(
			'content_color',
			[
				'label' => __( 'Text Color', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .nd_elements_marquee_component .nd_elements_marquee_content' => 'color: {{VALUE}}',
				],
			]
		);


		$this->add_responsive_control(
			'content_margin_left',
			[
				'label' => __( 'Margin Left', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'devices' => [ 'desktop' ],
				'desktop_default' => [
					'size' => 10,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .nd_elements_marquee_component .nd_elements_marquee_content' => 'margin-left: {{SIZE}}{{UNIT}};',
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
		$marquee_layout = $nd_elements_settings['marquee_layout'];
		$marquee_label = $nd_elements_settings['marquee_label'];
		$marquee_content = $nd_elements_settings['marquee_content'];

		//default values
		if ($marquee_layout == '') { $marquee_layout = "layout-1"; }

  		//check with realpath
  		$nd_elements_layout_selected = dirname( __FILE__ ).'/layout/'.$marquee_layout.'.php';
  		include realpath($nd_elements_layout_selected);

  		$nd_elements_allowed_html = [
		    'div'      => [
		        'class' => [],
		    ],
		    'marquee'      => [
		        'class' => [],
		    ],
		    'span'      => [
		        'class' => [],
		    ],
		];

		echo wp_kses( $nd_elements_result, $nd_elements_allowed_html );

	}
	//END RENDER


}
//END ELEMENT POST GRID
