<?php


//START ELEMENT POST GRID
class nd_elements_woocart_element extends \Elementor\Widget_Base {

	public function get_name() { return 'woocart'; }
	public function get_title() { return __( 'WooCommerce Cart', 'nd-elements' ); }
	public function get_icon() { return 'fa fa-shopping-cart'; }
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
	      'woocart_layout',
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
			'woocart_align',
			[
				'label' => __( 'Alignment', 'nd-elements' ),
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
				'default' => 'center',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .nd_elements_woocart_component' => 'text-align: {{VALUE}}',
				],
			]
		);


		$this->add_control(
			'woocart_image',
			[
				'label' => __( 'Image', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'woocart_total',
			[
				'label' => __( 'Show Total Cart', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'nd-elements' ),
				'label_off' => __( 'Hide', 'nd-elements' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'selectors' => [
					'{{WRAPPER}} .nd_elements_woocart_component a.nd_elements_woocart_component_long' => 'display:block',
					'{{WRAPPER}} .nd_elements_woocart_component a.nd_elements_woocart_component_short' => 'display:none',
				],
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Text', 'nd-elements' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'woocart_typography',
				'label' => __( 'Typography', 'nd-elements' ),
				'selector' => '{{WRAPPER}} 
					.nd_elements_woocart_component a',
			]
		);

		$this->add_control(
			'woocart_color',
			[
				'label' => __( 'Text Color', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .nd_elements_woocart_component a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'woocart_text_margin_left',
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
					'{{WRAPPER}} .nd_elements_woocart_component a' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();



		$this->start_controls_section(
			'style_section_2',
			[
				'label' => __( 'Image', 'nd-elements' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_responsive_control(
			'woocart_image_width',
			[
				'label' => __( 'Width', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
						'step' => 1,
					],
				],
				'devices' => [ 'desktop' ],
				'desktop_default' => [
					'size' => 18,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .nd_elements_woocart_component img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->end_controls_section();


		$this->start_controls_section(
			'style_section_3',
			[
				'label' => __( 'Content', 'nd-elements' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_control(
			'woocart_margin_content',
			[
				'label' => __( 'Margin', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => -10,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .nd_elements_woocart_component .nd_elements_display_inline_block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
				],
			]
		);


		$this->end_controls_section();

	}
	//END CONTROLS


 
	/*START RENDER*/
	protected function render() {

		/*woo*/
		if ( null === WC()->cart ) {
			return;
		}

		$nd_elements_cart_count = WC()->cart->get_cart_contents_count();
		$nd_elements_cart_total = WC()->cart->get_cart_subtotal();
		$nd_elements_cart_url = wc_get_cart_url();
		/*woo*/

		$nd_elements_result = '';

  		//get datas
  		$nd_elements_settings = $this->get_settings_for_display();
		$woocart_layout = $nd_elements_settings['woocart_layout'];
		$woocart_total = $nd_elements_settings['woocart_total'];		

		//default values
		if ($woocart_layout == '') { $woocart_layout = "layout-1"; }

		//check with realpath
  		$nd_elements_layout_selected = dirname( __FILE__ ).'/layout/'.$woocart_layout.'.php';
  		include realpath($nd_elements_layout_selected);

  		$nd_elements_allowed_html = [
		    'div'      => [
				'class' => [],
			],	
			'img'      => [
				'class' => [],
				'src' => [],
			],
			'a'      => [
				'class' => [],
				'href' => [],
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
