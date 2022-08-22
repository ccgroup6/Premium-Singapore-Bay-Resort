<?php


//START ELEMENT POST GRID
class nd_elements_beforeafter_element extends \Elementor\Widget_Base {

	public function get_name() { return 'beforeafter'; }
	public function get_title() { return __( 'Before After', 'nd-elements' ); }
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
	      'beforeafter_layout',
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
			'beforeafter_image_before',
			[
				'label' => __( 'Image Before', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'beforeafter_image_after',
			[
				'label' => __( 'Image After', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'beforeafter_icon',
			[
				'label' => __( 'Icon', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);


		$this->end_controls_section();


		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Style', 'nd-elements' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'beforeafter_color_1',
			[
				'label' => __( 'Color 1', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .nd_elements_beforeafter_component .ui-slider-handle' => 'background-color: {{VALUE}} !important',
				],
			]
		);

		$this->add_control(
			'beforeafter_color_2',
			[
				'label' => __( 'Color 2', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .nd_elements_beforeafter_component .ui-slider-handle:after' => 'background-color: {{VALUE}} !important',
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
		$nd_elements_layout = $nd_elements_settings['beforeafter_layout'];
		$nd_elements_image_before = $nd_elements_settings['beforeafter_image_before']['url'];
		$nd_elements_image_after = $nd_elements_settings['beforeafter_image_after']['url'];
		$nd_elements_icon = $nd_elements_settings['beforeafter_icon']['url'];

		wp_enqueue_script('jquery-ui-slider');

		//default values
		if ($nd_elements_layout == '') { $nd_elements_layout = "layout-1"; }

  		//check with realpath
  		$nd_elements_layout_selected = dirname( __FILE__ ).'/layout/'.$nd_elements_layout.'.php';
  		include realpath($nd_elements_layout_selected);

  		$nd_elements_allowed_html = [
		    'div'      => [
		        'class' => [],
		        'id' => [],
		    ],
		    'style'      => [],
		    'script'      => [],
		    'img'      => [
		        'class' => [],
		        'src' => [],
		    ],
		];

		echo wp_kses( $nd_elements_result, $nd_elements_allowed_html );

	}
	//END RENDER


}
//END ELEMENT POST GRID
