<?php


//START ELEMENT customcss
class nd_elements_customcss_element extends \Elementor\Widget_Base {

	public function get_name() { return 'customcss'; }
	public function get_title() { return __( 'Custom CSS', 'nd-elements' ); }
	public function get_icon() { return 'fa fa-code'; }
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

	   	$this->add_control(
			'custom_css',
			[
				'label' => __( 'Custom CSS', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::CODE,
				'language' => 'css',
				'rows' => 20,
			]
		);

		$this->end_controls_section();

	}


 
	/*START RENDER*/
	protected function render() {

		$nd_elements_settings = $this->get_settings_for_display();
		$nd_elements_customcss = $nd_elements_settings['custom_css'];
		
		$nd_elements_result = '<style type="text/css">'.$nd_elements_customcss.'</style>';

		$nd_elements_allowed_html = [
		    'style'      => [
		        'type' => [],
		    ],
		];

		echo wp_kses( $nd_elements_result, $nd_elements_allowed_html );

	}




}
