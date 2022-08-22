<?php


//START ELEMENT NAVIGATION
class nd_elements_navigation_element extends \Elementor\Widget_Base {

	public function get_name() { return 'navigation'; }
	public function get_title() { return __( 'Navigation', 'nd-elements' ); }
	public function get_icon() { return 'fa fa-bars'; }
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

		/*START Built navigation array*/
		$nd_elements_menus = get_terms('nav_menu');
	    $nd_elements_all_menus = array();
	    $nd_elements_i = 0;

	    foreach($nd_elements_menus as $nd_elements_menu){

	    	//nav info
	    	$nd_elements_navigation_name = $nd_elements_menu->name;	
	    	$nd_elements_navigation_id = $nd_elements_menu->term_id;	
	      
			$nd_elements_all_menus[$nd_elements_navigation_id] = $nd_elements_navigation_name;
			$nd_elements_i = $nd_elements_i + 1;
	    
	    } 
	    /*END Built navigation array*/


	    /*Create Control*/
		$this->add_control(
			'nd_elements_navigation',
			[
				'label' => __( 'Navigation', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'solid',
				'options' => $nd_elements_all_menus,
			]
		);


		$this->add_control(
			'navigation_align',
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
					'{{WRAPPER}} .nd_elements_navigation_component > div' => 'text-align: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_open_navigation_sidebar_content' => 'float: {{VALUE}}',
				],
			]
		);


		$this->add_control(
			'navigation_between_items',
			[
				'label' => __( 'Space Between Items', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .nd_elements_navigation_component ul.menu > li a' => 'padding: 0px {{SIZE}}px;',
				],
			]
		);


		$this->add_control(
			'navigation_mobile_icon',
			[
				'label' => __( 'Mobile Icon', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);


		$this->end_controls_section();


		$this->start_controls_section(
			'content_section_2',
			[
				'label' => __( 'Dropdown Options', 'nd-elements' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);


		$this->add_control(
			'navigation_dropdown_width',
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
					'size' => 200,
				],
				'selectors' => [
					'{{WRAPPER}} .nd_elements_navigation_component div > ul li > ul.sub-menu' => 'width: {{SIZE}}px;',
					'{{WRAPPER}} .nd_elements_navigation_component div > ul li > ul.sub-menu li > ul.sub-menu' => 'margin-left: {{SIZE}}px;',
				],
			]
		);


		$this->add_control(
			'navigation_dropdown_top_padding',
			[
				'label' => __( 'Top Space', 'nd-elements' ),
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
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .nd_elements_navigation_component div > ul li > ul.sub-menu' => 'padding-top: {{SIZE}}px;',
				],
			]
		);


		$this->add_control(
			'navigation_item_padding',
			[
				'label' => __( 'Item Padding', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 15,
					'right' => 20,
					'bottom' => 15,
					'left' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .nd_elements_navigation_component div > ul li > ul.sub-menu > li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->end_controls_section();


		/*************************START STYLE TAB*************************/

		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Main Style', 'nd-elements' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'navigation_typography',
				'label' => __( 'Typography', 'nd-elements' ),
				'selector' => '{{WRAPPER}} .nd_elements_navigation_component ul.menu > li a',
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
			'navigation_color',
			[
				'label' => __( 'Color', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .nd_elements_navigation_component ul.menu > li a' => 'color: {{VALUE}}',
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
			'navigation_color_hover',
			[
				'label' => __( 'Color', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .nd_elements_navigation_component ul.menu > li a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();


		$this->end_controls_section();


		$this->start_controls_section(
			'style_section_2',
			[
				'label' => __( 'Dropdown Style', 'nd-elements' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'navigation_dropdown_typography',
				'label' => __( 'Typography', 'nd-elements' ),
				'selector' => '{{WRAPPER}} .nd_elements_navigation_component div > ul li > ul.sub-menu li a',
			]
		);


		$this->add_control(
			'hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);
		
		$this->add_control(
			'navigation_dropdown_border_width',
			[
				'label' => __( 'Border Width', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .nd_elements_navigation_component div > ul li > ul.sub-menu > li' => 'border-bottom-width: {{SIZE}}px;',
				],
			]
		);

		$this->add_control(
			'navigation_dropdown_border_color',
			[
				'label' => __( 'Border Color', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .nd_elements_navigation_component div > ul li > ul.sub-menu > li' => 'border-bottom-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'hr_2',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'navigation_dropdown_shadow',
				'label' => __( 'Shadow', 'nd-elements' ),
				'selector' => '{{WRAPPER}} .nd_elements_navigation_component div > ul li > ul.sub-menu > li',
			]
		);


		$this->start_controls_tabs(
			'style_2_dropdown_style'
		);

		$this->start_controls_tab(
			'style_2_normal_tab',
			[
				'label' => __( 'Normal', 'plugin-name' ),
			]
		);

		$this->add_control(
			'navigation_dropdown_color',
			[
				'label' => __( 'Color', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .nd_elements_navigation_component div > ul li > ul.sub-menu li a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .nd_elements_navigation_component div > ul li > ul.sub-menu li.menu-item-has-children > a:after' => 'border-color: transparent transparent transparent {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'navigation_dropdown_bg_color',
			[
				'label' => __( 'Background Color', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .nd_elements_navigation_component div > ul li > ul.sub-menu > li' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_2_hover_tab',
			[
				'label' => __( 'Hover', 'plugin-name' ),
			]
		);

		$this->add_control(
			'navigation_dropdown_color_hover',
			[
				'label' => __( 'Color', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .nd_elements_navigation_component div > ul li > ul.sub-menu li a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'navigation_dropdown_bg_color_hover',
			[
				'label' => __( 'Background Color', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#f9f9f9',
				'selectors' => [
					'{{WRAPPER}} .nd_elements_navigation_component div > ul li > ul.sub-menu > li:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();



		$this->start_controls_section(
			'style_section_3',
			[
				'label' => __( 'Mobile Sidebar', 'nd-elements' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'navigation_mobilesidebar_bg',
			[
				'label' => __( 'Background Color', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .nd_elements_navigation_sidebar_content' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();






		/*************************END STYLE TAB*************************/

	}



 
	/*START RENDER*/
	protected function render() {

		//add script
  		wp_enqueue_script('nd_elements_navigation_js', esc_url( plugins_url('js/navigation.js', __FILE__ )) );

		$nd_elements_settings = $this->get_settings_for_display();
		$nd_elements_menu = $nd_elements_settings['nd_elements_navigation'];
		$navigation_mobile_icon = $nd_elements_settings['navigation_mobile_icon']['url'];

		$nd_elements_args = array(
			'menu'   => $nd_elements_menu,
			'echo' => false
		);

		//check if menus are present
		$nd_elements_menus = get_terms('nav_menu');

		if ( empty($nd_elements_menus) ) {

			$nd_elements_menu_content = '
			<div class="nd_elements_section">
				<ul class="menu">
					<li>
						<a target="_blank" href="'.get_admin_url().'nav-menus.php">'.__('No menus have been created yet. Create some.', 'nd-elements' ).'</a>
					</li>
				</ul>
			</div>';

		}else{
			$nd_elements_menu_content = wp_nav_menu( $nd_elements_args );
		}


		//icon
		if ( $navigation_mobile_icon == '' ){
			$navigation_mobile_icon = '<img alt="open-navigation" width="25" class="nd_elements_open_navigation_sidebar_content" src="'.esc_url( plugins_url('img/navigation-open.svg', __FILE__ )).'">';	
		}else{
			$navigation_mobile_icon = '<img alt="open-navigation" width="25" class="nd_elements_open_navigation_sidebar_content" src="'.esc_url($navigation_mobile_icon).'">';		
		}

		//prepare the output
		$nd_elements_result = '
		<div class="nd_elements_section">
			<div class="nd_elements_navigation_component">

				'.$nd_elements_menu_content.'
				
				'.$navigation_mobile_icon.'
			
			</div>
		</div>

		<!--START menu responsive-->
		<div class="nd_elements_navigation_sidebar_content">

			<img alt="close-navigation" width="25" class="nd_elements_close_navigation_sidebar_content" src="'.esc_url( plugins_url('img/navigation-close.svg', __FILE__ )).'">

		    <div class="nd_elements_navigation_sidebar">
		        '.wp_nav_menu( $nd_elements_args ).'
		    </div>

		</div>
		<!--END menu responsive-->
		';
		
  		$nd_elements_allowed_html = [
		    'div'      => [
				'class' => [],
			],
			'ul'      => [
				'id' => [],
				'class' => [],
			],
			'li'      => [
				'id' => [],
				'class' => [],
			],
			'a'      => [
				'href' => [],
				'class' => [],
				'aria-current' => [],
				'target' => [],
				'title' => [],
			],
			'img'      => [
				'alt' => [],
				'width' => [],
				'class' => [],
				'src' => [],
			],
		];

		echo wp_kses( $nd_elements_result, $nd_elements_allowed_html );

	}




}
