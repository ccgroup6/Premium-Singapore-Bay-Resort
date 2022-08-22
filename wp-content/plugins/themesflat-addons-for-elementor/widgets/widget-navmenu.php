<?php
class TFNavMenu_Widget_Free extends \Elementor\Widget_Base {

	public function get_name() {
        return 'tf-nav-menu';
    }
    
    public function get_title() {
        return esc_html__( 'TF Nav Menu', 'themesflat-addons-for-elementor' );
    }

    public function get_icon() {
        return 'eicon-nav-menu';
    }
    
    public function get_categories() {
        return [ 'themesflat_addons' ];
    }

    public function get_style_depends() {
		return ['tf-font-awesome','tf-regular','tf-style'];
	}
	public function get_script_depends() {
		return ['tf-main'];
	}

    public function get_menus(){
        $list = [];
        $menus = wp_get_nav_menus();
        foreach($menus as $menu){
            $list[$menu->slug] = $menu->name;
        }

        return $list;
    }

	protected function register_controls() {
        // Start Menu Settings        
			$this->start_controls_section( 
				'section_menu_setting',
	            [
	                'label' => esc_html__('Menu Settings', 'themesflat-addons-for-elementor'),
	            ]
	        );

	        $this->add_control(
            	'one_page_enable',
	            [
					'label' => esc_html__('Enable one page?', 'themesflat-addons-for-elementor'),
					'description'	=> esc_html__('This works in the current page.', 'themesflat-addons-for-elementor'),
	                'type' => \Elementor\Controls_Manager::SWITCHER,
	                'default' => 'no',
	                'label_on' =>esc_html__( 'Yes', 'themesflat-addons-for-elementor' ),
	                'label_off' =>esc_html__( 'No', 'themesflat-addons-for-elementor' ),
	            ]
			);	

	        $this->add_control(
	            'nav_menu',
	            [
	                'label'     =>esc_html__( 'Select menu', 'themesflat-addons-for-elementor' ),
	                'type'      =>  \Elementor\Controls_Manager::SELECT,
	                'options'   => $this->get_menus(),
	            ]
			);

			$this->add_control(
				'layout',
				[
					'label' => esc_html__( 'Layout', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'horizontal',
					'options' => [
						'horizontal'  => esc_html__( 'Horizontal', 'themesflat-addons-for-elementor' ),
						'only-icon' => esc_html__( 'Only Icon', 'themesflat-addons-for-elementor' ),
					],
				]
			);

			$this->add_control(
				'menu_panel_style',
				[
					'label' => esc_html__( 'Panel Style On Mobile', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'menu-panel-style-left',
					'options' => [
						'menu-panel-style-default'  => esc_html__( 'Default', 'themesflat-addons-for-elementor' ),
						'menu-panel-style-left' => esc_html__( 'Left', 'themesflat-addons-for-elementor' ),
					],
				]
			);

			$this->add_control(
				'main_menu_position',
				[
					'label' => esc_html__( 'Alignment Menu', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::CHOOSE,
					'options' => [
						'tf-alignment-left'    => [
							'title' => esc_html__( 'Left', 'themesflat-addons-for-elementor' ),
							'icon' => 'eicon-text-align-left',
						],
						'tf-alignment-center' => [
							'title' => esc_html__( 'Center', 'themesflat-addons-for-elementor' ),
							'icon' => 'eicon-text-align-center',
						],
						'tf-alignment-right' => [
							'title' => esc_html__( 'Right', 'themesflat-addons-for-elementor' ),
							'icon' => 'eicon-text-align-right',
						],
						'tf-alignment-justify' => [
							'title' => esc_html__( 'Justified', 'themesflat-addons-for-elementor' ),
							'icon' => 'eicon-text-align-justify',
						],
					],
					'default' => 'tf-alignment-left',
				]
			);

	        $this->add_control(
				'submenu_icon',
				[
					'label' => esc_html__( 'Submenu Icon', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'arrows',
					'options' => [
						'classic'  => esc_html__( 'Classic', 'themesflat-addons-for-elementor' ),
						'arrows' => esc_html__( 'Arrows', 'themesflat-addons-for-elementor' ),
						'plus' => esc_html__( 'Plus', 'themesflat-addons-for-elementor' ),
					],
				]
			);			

			$this->add_control(
				'heading_responsive',
				[
					'label' => esc_html__( 'Responsive', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);			

	        $this->add_control(
				'nav_menu_logo',
				[
					'label' => esc_html__( 'Choose Mobile Menu Logo', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::MEDIA,
					'default' => [
						'url' => URL_THEMESFLAT_ADDONS_ELEMENTOR_FREE."assets/img/placeholder.jpg",
					],
				]
			);

			$this->add_control(
				'nav_menu_logo_url_to',
				[
					'label' => esc_html__( 'Link Logo', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'home',
					'options' => [
						'home' => esc_html__( 'Home', 'themesflat-addons-for-elementor' ),
						'custom' => esc_html__( 'Custom URL', 'themesflat-addons-for-elementor' ),
					],
					'condition' => [
						'nav_menu_logo[url]!' => '',
					],
				]
			);

			$this->add_control(
				'nav_menu_logo_link',
				[
					'label' => esc_html__( 'URL', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::URL,
					'placeholder' => 'https://your-link.com',
					'condition' => [
						'nav_menu_logo_url_to' => 'custom',
					],
					'show_label' => false,
				]
			);

			$this->add_control(
				'btn_menu_mobile_icon',
				[			        
			        'label' => esc_html__('Icon Button Menu Mobile & Only Icon', 'themesflat-addons-for-elementor'),
			        'type' => \Elementor\Controls_Manager::ICONS,
			        'default' => [
			            'value' => 'fas fa-bars',
			            'library' => 'fa-solid',
			        ],
			    ]
			);

			$this->add_control(
				'btn_menu_close',
				[			        
			        'label' => esc_html__('Icon Close Menu', 'themesflat-addons-for-elementor'),
			        'type' => \Elementor\Controls_Manager::ICONS,
			        'default' => [
			            'value' => 'fas fa-times',
			            'library' => 'fa-solid',
			        ],
			    ]
			);			

			$this->end_controls_section();
        // /.End Menu Settings

        // Start Main Menu Style 
	        $this->start_controls_section( 
	        	'section_style_menu',
	            [
	                'label' => esc_html__( 'Main Menu', 'themesflat-addons-for-elementor' ),
	                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	            ]
	        );

	        $this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'item_menu_typography',
					'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
					'fields_options' => [
			            'typography' => ['default' => 'yes'],
			            'font_family' => [ 'default' => 'Poppins' ],
			            'font_size' => ['default' => ['size' => 14]],
			            'font_weight' => ['default' => 600],
			        ],
					'selector' => '{{WRAPPER}} .tf-nav-menu .mainnav .menu-container > ul > li > a',
					'separator' => 'before',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'item_menu_border',
					'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-nav-menu .mainnav .menu-container > ul > li > a',
					
				]
			);

	        $this->add_control(
				'item_menu_horizontal_padding',
				[
					'label' => esc_html__( 'Horizontal Padding', 'themesflat-addons-for-elementor' ),
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
						'size' => 15,
					],
					'selectors' => [
						'{{WRAPPER}} .tf-nav-menu .mainnav .menu-container > ul > li > a' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
					],										
				]
			);

			$this->add_control(
				'item_menu_vertical_padding',
				[
					'label' => esc_html__( 'Vertical Padding', 'themesflat-addons-for-elementor' ),
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
						'size' => 10,
					],
					'selectors' => [
						'{{WRAPPER}} .tf-nav-menu .mainnav .menu-container > ul > li > a' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'item_menu_space_between',
				[
					'label' => esc_html__( 'Space Between', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
							'step' => 1,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .tf-nav-menu .mainnav .menu-container > ul > li' => 'margin-right: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .tf-nav-menu .mainnav .menu-container > ul > li:last-child' => 'margin-right: 0;',
					],
				]
			);

			$this->add_control(
				'link_hover_effect',
				[
					'label' => esc_html__( 'Link Hover Effect', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'underline',
					'options' => [
						'none'  => esc_html__( 'None', 'themesflat-addons-for-elementor' ),
						'underline' => esc_html__( 'Underline', 'themesflat-addons-for-elementor' ),
						'overline' => esc_html__( 'Overline', 'themesflat-addons-for-elementor' ),
						'double-line' => esc_html__( 'Double Line', 'themesflat-addons-for-elementor' ),
						'text' => esc_html__( 'Text', 'themesflat-addons-for-elementor' ),
					],
				]
			);

			$this->add_control(
				'animation_line',
				[
					'label' => esc_html__( 'Animation', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'fade',
					'options' => [
						'normal'  => esc_html__( 'Normal', 'themesflat-addons-for-elementor' ),
						'fade'  => esc_html__( 'Fade', 'themesflat-addons-for-elementor' ),
						'slide' => esc_html__( 'Slide', 'themesflat-addons-for-elementor' ),
						'drop-in' => esc_html__( 'Drop In', 'themesflat-addons-for-elementor' ),
						'drop-out' => esc_html__( 'Drop Out', 'themesflat-addons-for-elementor' ),						
					],
					'condition' => [
						'link_hover_effect!' => 'none',
					],
				]
			);	   

			$this->add_control(
				'submenu_icon_margin',
				[
					'label' => esc_html__( 'Submenu Icon Margin', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .tf-nav-menu .mainnav .menu-container li.menu-item-has-children > a > i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			); 

			$this->start_controls_tabs( 'item_menu_tabs', ['separator' => 'before'] );				

				$this->start_controls_tab( 
					'item_menu_normal_tab',
					[
						'label' => esc_html__( 'Normal', 'themesflat-addons-for-elementor' ),						
					]
					);

					$this->add_control(
						'item_menu_color',
						[
							'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '#000000',
							'selectors' => [
								'{{WRAPPER}} .tf-nav-menu .mainnav .menu-container > ul > li > a' => 'color: {{VALUE}}',
							],
						]
					);	

			        $this->add_control(
						'item_menu_background',
						[
							'label' => esc_html__( 'Background', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .tf-nav-menu .mainnav .menu-container > ul > li > a' => 'background-color: {{VALUE}}',
							],
						]
					);			        		
				
				$this->end_controls_tab();

				$this->start_controls_tab( 
			    	'item_menu_hover_tab',
					[
						'label' => esc_html__( 'Hover', 'themesflat-addons-for-elementor' ),
					]
					);

					$this->add_control(
						'item_menu_color_hover',
						[
							'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '#23A455',
							'selectors' => [
								'{{WRAPPER}} .tf-nav-menu .mainnav .menu-container > ul > li > a:hover' => 'color: {{VALUE}}',
								'{{WRAPPER}} .tf_link_effect_underline .mainnav .menu-container > ul > li > a:after' => 'background-color: {{VALUE}}',
								'{{WRAPPER}} .tf_link_effect_overline .mainnav .menu-container > ul > li > a:after' => 'background-color: {{VALUE}}',
								'{{WRAPPER}} .tf_link_effect_double-line .mainnav .menu-container > ul > li > a:before' => 'background-color: {{VALUE}}',
								'{{WRAPPER}} .tf_link_effect_double-line .mainnav .menu-container > ul > li > a:after' => 'background-color: {{VALUE}}',
							],
						]
					);	

					$this->add_control(
						'item_menu_background_hover',
						[
							'label' => esc_html__( 'Background', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .tf-nav-menu .mainnav .menu-container > ul > li > a:hover' => 'background-color: {{VALUE}}',
							],
						]
					);									
				
				$this->end_controls_tab();

				$this->start_controls_tab( 
			    	'item_menu_active_tab',
					[
						'label' => esc_html__( 'Active', 'themesflat-addons-for-elementor' ),
					]
					);

					$this->add_control(
						'item_menu_color_active',
						[
							'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '#23A455',
							'selectors' => [
								'{{WRAPPER}} .tf-nav-menu .mainnav .menu-container > ul > li.current-menu-ancestor > a' => 'color: {{VALUE}}',
								'{{WRAPPER}} .tf-nav-menu .mainnav .menu-container > ul > li.current-menu-item > a' => 'color: {{VALUE}}',
								'{{WRAPPER}} .tf_link_effect_underline .mainnav .menu-container > ul > li.current-menu-ancestor > a:after' => 'background-color: {{VALUE}}',
								'{{WRAPPER}} .tf_link_effect_overline .mainnav .menu-container > ul > li.current-menu-ancestor > a:after' => 'background-color: {{VALUE}}',
								'{{WRAPPER}} .tf_link_effect_double-line .mainnav .menu-container > ul > li.current-menu-ancestor > a:before' => 'background-color: {{VALUE}}',
								'{{WRAPPER}} .tf_link_effect_double-line .mainnav .menu-container > ul > li.current-menu-ancestor > a:after' => 'background-color: {{VALUE}}',

							],
						]
					);

					$this->add_control(
						'item_menu_background_active',
						[
							'label' => esc_html__( 'Background', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .tf-nav-menu .mainnav .menu-container > ul > li.current-menu-ancestor > a' => 'background-color: {{VALUE}}',
								'{{WRAPPER}} .tf-nav-menu .mainnav .menu-container > ul > li.current-menu-item > a' => 'background-color: {{VALUE}}',
							],
						]
					);										
				
				$this->end_controls_tab();

	        $this->end_controls_tabs();

	        $this->end_controls_section();
	    // /.End Main Menu Style

	    // Start Dropdown Style 
	        $this->start_controls_section( 
	        	'section_style_submenu',
	            [
	                'label' => esc_html__( 'Dropdown', 'themesflat-addons-for-elementor' ),
	                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	            ]
	        );

	        $this->add_control(
				'dropdown_style',
				[
					'label' => esc_html__( 'Dropdown Style', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'dropdown-style1',
					'options' => [
						'dropdown-default'  => esc_html__( 'Default', 'themesflat-addons-for-elementor' ),
						'dropdown-style1' => esc_html__( 'Style 1', 'themesflat-addons-for-elementor' ),
					],
				]
			);

	        $this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'item_submenu_typography',
					'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
					'fields_options' => [
			            'typography' => ['default' => 'yes'],
			            'font_family' => [ 'default' => 'Poppins' ],
			            'font_size' => ['default' => ['size' => 14]],
			            'font_weight' => ['default' => 600],			            
			        ],
					'selector' => '{{WRAPPER}} .tf-nav-menu .mainnav .menu-container > ul li ul.sub-menu li',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'item_submenu_box_shadow',
					'label' => esc_html__( 'Box Shadow', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-nav-menu .mainnav .menu-container > ul li ul.sub-menu',
					
				]
			);	        	        		        

			$this->add_responsive_control(
				'item_submenu_width',
				[
					'label' => esc_html__( 'Dropdown Width', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 500,
							'step' => 1,
	                    ]
	                ],
					'default' => [
						'size' => 250,
						'unit' => 'px',
					],
					'selectors' => [
						'{{WRAPPER}} .tf-nav-menu .mainnav .menu-container > ul > li ul.sub-menu' => 'min-width: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'item_submenu_spacing',
				[
					'label' => esc_html__( 'Top Distance', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 300,
							'step' => 1,
	                    ],
	                    '%' => [
							'min' => 0,
							'max' => 200,
						],
	                ],
	                'devices' => [ 'desktop', 'tablet', 'mobile' ],
					'desktop_default' => [
						'size' => 130,
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
						'{{WRAPPER}} .tf-nav-menu .mainnav .menu-container > ul > li > ul.sub-menu' => 'top: {{SIZE}}{{UNIT}};',
					],					
				]
			);

			$this->add_control(
				'item_submenu_horizontal_padding',
				[
					'label' => esc_html__( 'Horizontal Padding', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 50,
							'step' => 1,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 30,
					],
					'selectors' => [
						'{{WRAPPER}} .tf-nav-menu .mainnav .menu-container > ul li ul.sub-menu li a' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
					],					
				]
			);

			$this->add_control(
				'item_submenu_vertical_padding',
				[
					'label' => esc_html__( 'Vertical Padding', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 50,
							'step' => 1,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 12,
					],
					'selectors' => [
						'{{WRAPPER}} .tf-nav-menu .mainnav .menu-container > ul li ul.sub-menu li a' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
					],
				]
			);

	        $this->add_control(
				'heading_dropdown_divider',
				[
					'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_control(
				'dropdown_divider_border',
				[
					'label'       => esc_html__( 'Border Style', 'themesflat-addons-for-elementor' ),
					'type'        => \Elementor\Controls_Manager::SELECT,
					'default'     => 'solid',
					'label_block' => false,
					'options'     => [
						'none'   => esc_html__( 'None', 'themesflat-addons-for-elementor' ),
						'solid'  => esc_html__( 'Solid', 'themesflat-addons-for-elementor' ),
						'double' => esc_html__( 'Double', 'themesflat-addons-for-elementor' ),
						'dotted' => esc_html__( 'Dotted', 'themesflat-addons-for-elementor' ),
						'dashed' => esc_html__( 'Dashed', 'themesflat-addons-for-elementor' ),
					],
					'selectors'   => [
						'{{WRAPPER}} .tf-nav-menu .mainnav .menu-container > ul > li ul.sub-menu li:not(:last-child)' => 'border-bottom-style: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'divider_border_color',
				[
					'label'     => esc_html__( 'Border Color', 'themesflat-addons-for-elementor' ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'default'   => '#f5f5f5',
					'selectors' => [
						'{{WRAPPER}} .tf-nav-menu .mainnav .menu-container > ul > li ul.sub-menu li:not(:last-child)' => 'border-bottom-color: {{VALUE}};',
					],
					'condition' => [
						'dropdown_divider_border!' => 'none',
					],
				]
			);

			$this->add_control(
				'dropdown_divider_width',
				[
					'label'     => esc_html__( 'Border Width', 'themesflat-addons-for-elementor' ),
					'type'      => \Elementor\Controls_Manager::SLIDER,
					'range'     => [
						'px' => [
							'max' => 50,
						],
					],
					'default'   => [
						'size' => '1',
						'unit' => 'px',
					],
					'selectors' => [
						'{{WRAPPER}} .tf-nav-menu .mainnav .menu-container > ul > li ul.sub-menu li:not(:last-child)' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
					],
					'condition' => [
						'dropdown_divider_border!' => 'none',
					],
				]
			);

			$this->add_control(
				'heading_dropdown_divider_first_child',
				[
					'label' => esc_html__( 'Border First Child', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_control(
				'dropdown_divider_border_first_child',
				[
					'label'       => esc_html__( 'Border Style', 'themesflat-addons-for-elementor' ),
					'type'        => \Elementor\Controls_Manager::SELECT,
					'default'     => 'none',
					'label_block' => false,
					'options'     => [
						'none'   => esc_html__( 'None', 'themesflat-addons-for-elementor' ),
						'solid'  => esc_html__( 'Solid', 'themesflat-addons-for-elementor' ),
						'double' => esc_html__( 'Double', 'themesflat-addons-for-elementor' ),
						'dotted' => esc_html__( 'Dotted', 'themesflat-addons-for-elementor' ),
						'dashed' => esc_html__( 'Dashed', 'themesflat-addons-for-elementor' ),
					],
					'selectors'   => [
						'{{WRAPPER}} .tf-nav-menu .mainnav .menu-container > ul > li ul.sub-menu li:first-child' => 'border-top-style: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'divider_border_color_first_child',
				[
					'label'     => esc_html__( 'Border Color', 'themesflat-addons-for-elementor' ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'default'   => '#f7f7f7',
					'selectors' => [
						'{{WRAPPER}} .tf-nav-menu .mainnav .menu-container > ul > li ul.sub-menu li:first-child' => 'border-top-color: {{VALUE}};',
					],
					'condition' => [
						'dropdown_divider_border_first_child!' => 'none',
					],
				]
			);

			$this->add_control(
				'dropdown_divider_width_first_child',
				[
					'label'     => esc_html__( 'Border Width', 'themesflat-addons-for-elementor' ),
					'type'      => \Elementor\Controls_Manager::SLIDER,
					'range'     => [
						'px' => [
							'max' => 50,
						],
					],
					'default'   => [
						'size' => '1',
						'unit' => 'px',
					],
					'selectors' => [
						'{{WRAPPER}} .tf-nav-menu .mainnav .menu-container > ul > li ul.sub-menu li:first-child' => 'border-top-width: {{SIZE}}{{UNIT}}',
					],
					'condition' => [
						'dropdown_divider_border_first_child!' => 'none',
					],
				]
			);

			$this->add_control(
				'heading_dropdown_divider_last_child',
				[
					'label' => esc_html__( 'Border Last Child', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_control(
				'dropdown_divider_border_last_child',
				[
					'label'       => esc_html__( 'Border Style', 'themesflat-addons-for-elementor' ),
					'type'        => \Elementor\Controls_Manager::SELECT,
					'default'     => 'none',
					'label_block' => false,
					'options'     => [
						'none'   => esc_html__( 'None', 'themesflat-addons-for-elementor' ),
						'solid'  => esc_html__( 'Solid', 'themesflat-addons-for-elementor' ),
						'double' => esc_html__( 'Double', 'themesflat-addons-for-elementor' ),
						'dotted' => esc_html__( 'Dotted', 'themesflat-addons-for-elementor' ),
						'dashed' => esc_html__( 'Dashed', 'themesflat-addons-for-elementor' ),
					],
					'selectors'   => [
						'{{WRAPPER}} .tf-nav-menu .mainnav .menu-container > ul > li ul.sub-menu li:last-child' => 'border-bottom-style: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'divider_border_color_last_child',
				[
					'label'     => esc_html__( 'Border Color', 'themesflat-addons-for-elementor' ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'default'   => '#f7f7f7',
					'selectors' => [
						'{{WRAPPER}} .tf-nav-menu .mainnav .menu-container > ul > li ul.sub-menu li:last-child' => 'border-bottom-color: {{VALUE}};',
					],
					'condition' => [
						'dropdown_divider_border_last_child!' => 'none',
					],
				]
			);

			$this->add_control(
				'dropdown_divider_width_last_child',
				[
					'label'     => esc_html__( 'Border Width', 'themesflat-addons-for-elementor' ),
					'type'      => \Elementor\Controls_Manager::SLIDER,
					'range'     => [
						'px' => [
							'max' => 50,
						],
					],
					'default'   => [
						'size' => '1',
						'unit' => 'px',
					],
					'selectors' => [
						'{{WRAPPER}} .tf-nav-menu .mainnav .menu-container > ul > li ul.sub-menu li:last-child' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
					],
					'condition' => [
						'dropdown_divider_border_last_child!' => 'none',
					],
				]
			);

			$this->add_control(
				'dropdown-border-radius',
				[
					'label' => esc_html__( 'Border Radius', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'default' => [
						'top' => 5,
						'right' => 5,
						'bottom' => 5,
						'left' => 5,
						'unit' => 'px',
						'isLinked' => true,
					],
					'selectors' => [
						'{{WRAPPER}} .tf-nav-menu .mainnav .menu-container ul.sub-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .tf-nav-menu .mainnav .menu-container ul.sub-menu li:first-child' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0px 0px;',
						'{{WRAPPER}} .tf-nav-menu .mainnav .menu-container ul.sub-menu li:last-child' => 'border-radius: 0px 0px {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'separator' => 'before'
				]
			);

			$this->start_controls_tabs( 
	        	'item_submenu_tabs' 
	        	);				

				$this->start_controls_tab( 
					'item_submenu_normal_tab',
					[
						'label' => esc_html__( 'Normal', 'themesflat-addons-for-elementor' ),						
					]
					);	

					$this->add_control(
						'item_submenu_color',
						[
							'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '#1a1b1e',
							'selectors' => [
								'{{WRAPPER}} .tf-nav-menu .mainnav .menu-container ul.sub-menu a' => 'color: {{VALUE}}',
							],
						]
					);			

			        $this->add_control(
						'item_submenu_background_color',
						[
							'label' => esc_html__( 'Background', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '#ffffff',
							'selectors' => [
								'{{WRAPPER}} .tf-nav-menu .mainnav .menu-container ul.sub-menu' => 'background-color: {{VALUE}}',
								'{{WRAPPER}} .tf-nav-menu.dropdown-style1 .mainnav .menu-container ul.sub-menu:after' => 'background-color: {{VALUE}}',
							],
						]
					);			        					
				
				$this->end_controls_tab();

				$this->start_controls_tab( 
			    	'item_submenu_hover_tab',
					[
						'label' => esc_html__( 'Hover', 'themesflat-addons-for-elementor' ),
					]
					);

					$this->add_control(
						'item_submenu_color_hover',
						[
							'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '#23A455',
							'selectors' => [
								'{{WRAPPER}} .tf-nav-menu .mainnav .menu-container ul.sub-menu a:hover' => 'color: {{VALUE}}',
							],
						]
					);	

					$this->add_control(
						'item_submenu_background_color_hover',
						[
							'label' => esc_html__( 'Background', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '#f5f5f5',
							'selectors' => [
								'{{WRAPPER}} .tf-nav-menu .mainnav .menu-container ul.sub-menu li:hover' => 'background-color: {{VALUE}}',
							],
						]
					);			        				
				
				$this->end_controls_tab();

				$this->start_controls_tab( 
			    	'item_submenu_active_tab',
					[
						'label' => esc_html__( 'Active', 'themesflat-addons-for-elementor' ),
					]
					);

					$this->add_control(
						'item_submenu_color_active',
						[
							'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '#23A455',
							'selectors' => [
								'{{WRAPPER}} .tf-nav-menu .mainnav .menu-container ul.sub-menu li.current_page_item > a' => 'color: {{VALUE}}',
								'{{WRAPPER}} .tf-nav-menu .mainnav .menu-container ul.sub-menu > li.current-menu-item > a' => 'color: {{VALUE}}',
							],
						]
					);	

					$this->add_control(
						'item_submenu_background_color_active',
						[
							'label' => esc_html__( 'Background', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '#f5f5f5',
							'selectors' => [
								'{{WRAPPER}} .tf-nav-menu .mainnav .menu-container ul.sub-menu li.current_page_item' => 'background-color: {{VALUE}}',
								'{{WRAPPER}} .tf-nav-menu .mainnav .menu-container ul.sub-menu > li.current-menu-item' => 'background-color: {{VALUE}}',
							],
						]
					);			        					
				
				$this->end_controls_tab();

	        $this->end_controls_tabs();

	        $this->end_controls_section();
	    // /.End Dropdown Style

	    // Start Mobile Button & Close Icon Style 
	        $this->start_controls_section( 
	        	'section_style_menu_trigger',
	            [
	                'label' => esc_html__( 'Mobile Button & Close Icon', 'themesflat-addons-for-elementor' ),
	                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	            ]
	        );	        

	        $this->add_responsive_control(
				'toggle_button_menu_size',
				[
					'label'     => esc_html__( 'Icon Size', 'themesflat-addons-for-elementor' ),
					'type'      => \Elementor\Controls_Manager::SLIDER,
					'range'     => [
						'px' => [
							'min' => 15,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .tf-nav-menu .btn-menu-mobile, {{WRAPPER}} .tf-nav-menu .btn-menu-only' => 'font-size: {{SIZE}}{{UNIT}}',
					],
				]
			);

			$this->add_responsive_control(
				'toggle_button_menu_border_width',
				[
					'label'     => esc_html__( 'Border Width', 'themesflat-addons-for-elementor' ),
					'type'      => \Elementor\Controls_Manager::SLIDER,
					'range'     => [
						'px' => [
							'max' => 10,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .tf-nav-menu .btn-menu-mobile, {{WRAPPER}} .tf-nav-menu .btn-menu-only' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid;',
					],
				]
			);

			$this->add_responsive_control(
				'toggle_button_menu_border_radius',
				[
					'label'      => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
					'type'       => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .tf-nav-menu .btn-menu-mobile, {{WRAPPER}} .tf-nav-menu .btn-menu-only' => 'border-radius: {{SIZE}}{{UNIT}}',
					],
				]
			);

			$this->add_responsive_control(
				'toggle_button_menu_padding',
				[
					'label' => esc_html__( 'Padding', 'themesflat-addons-for-elementor' ),
	                'type' => \Elementor\Controls_Manager::DIMENSIONS,
	                'size_units' => [ 'px' ],
	                'default' => [
	                    'top' => 8,
	                    'right' => 16,
	                    'bottom' => 8,
	                    'left' => 16,
	                    'unit' => 'px',
	                ],
					'selectors' => [
						'{{WRAPPER}} .tf-nav-menu .btn-menu-mobile, {{WRAPPER}} .tf-nav-menu .btn-menu-only' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
	        );

	        $this->add_responsive_control(
				'toggle_button_menu_margin',
				[
					'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
	                'type' => \Elementor\Controls_Manager::DIMENSIONS,
	                'size_units' => [ 'px' ],
	                'default' => [
	                    'top' => 0,
	                    'right' => 0,
	                    'bottom' => 0,
	                    'left' => 0,
	                    'unit' => 'px',
	                ],
					'selectors' => [
						'{{WRAPPER}} .tf-nav-menu .btn-menu-mobile, {{WRAPPER}} .tf-nav-menu .btn-menu-only' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
	        );

	        $this->start_controls_tabs( 
	        	'toggle_button_menu_tabs' 
	        	);				

				$this->start_controls_tab( 
					'toggle_button_menu_normal_tab',
					[
						'label' => esc_html__( 'Normal', 'themesflat-addons-for-elementor' ),						
					]
					);	

					$this->add_control(
						'toggle_button_menu_color',
						[
							'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '#000000',
							'selectors' => [
								'{{WRAPPER}} .tf-nav-menu .btn-menu-mobile, {{WRAPPER}} .tf-nav-menu .btn-menu-only' => 'color: {{VALUE}}',
							],
						]
					);	

			        $this->add_control(
						'toggle_button_menu_bgcolor',
						[
							'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => 'rgba(255,255,255,0)',
							'selectors' => [
								'{{WRAPPER}} .tf-nav-menu .btn-menu-mobile, {{WRAPPER}} .tf-nav-menu .btn-menu-only' => 'background-color: {{VALUE}}',
							],
						]
					);												
				
				$this->end_controls_tab();

				$this->start_controls_tab( 
			    	'toggle_button_menu_hover_tab',
					[
						'label' => esc_html__( 'Hover', 'themesflat-addons-for-elementor' ),
					]
					);

					$this->add_control(
						'toggle_button_menu_color_hover',
						[
							'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '#23A455',
							'selectors' => [
								'{{WRAPPER}} .tf-nav-menu .btn-menu-mobile:hover, {{WRAPPER}} .tf-nav-menu .btn-menu-only:hover' => 'color: {{VALUE}}',
							],
						]
					);	

					$this->add_control(
						'toggle_button_menu_bgcolor_hover',
						[
							'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => 'rgba(255,255,255,0)',
							'selectors' => [
								'{{WRAPPER}} .tf-nav-menu .btn-menu-mobile:hover, {{WRAPPER}} .tf-nav-menu .btn-menu-only:hover' => 'background-color: {{VALUE}}',
							],
						]
					);									
				
				$this->end_controls_tab();

	        $this->end_controls_tabs();

	        $this->add_control(
				'heading_panel_btn_close',
				[
					'label' => esc_html__( 'Button Close', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);		

	        $this->add_responsive_control(
				'panel_button_close_size',
				[
					'label'     => esc_html__( 'Icon Size', 'themesflat-addons-for-elementor' ),
					'type'      => \Elementor\Controls_Manager::SLIDER,
					'range'     => [
						'px' => [
							'min' => 15,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .tf-nav-menu .tf-close' => 'font-size: {{SIZE}}{{UNIT}}',
						'{{WRAPPER}} .tf-nav-menu .close-menu-panel-style-default' => 'font-size: {{SIZE}}{{UNIT}}',
					],
				]
			);

			$this->add_responsive_control(
				'panel_button_close_border_width',
				[
					'label'     => esc_html__( 'Border Width', 'themesflat-addons-for-elementor' ),
					'type'      => \Elementor\Controls_Manager::SLIDER,
					'range'     => [
						'px' => [
							'max' => 10,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 3,
					],
					'selectors' => [
						'{{WRAPPER}} .tf-nav-menu .tf-close' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid;',
						'{{WRAPPER}} .tf-nav-menu .close-menu-panel-style-default' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid;',
					],
				]
			);

			$this->add_responsive_control(
				'panel_button_close_border_radius',
				[
					'label'      => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
					'type'       => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'default' => [
						'unit' => 'px',
						'size' => 0,
					],
					'selectors'  => [
						'{{WRAPPER}} .tf-nav-menu .tf-close' => 'border-radius: {{SIZE}}{{UNIT}}',
						'{{WRAPPER}} .tf-nav-menu .close-menu-panel-style-default' => 'border-radius: {{SIZE}}{{UNIT}}',
					],
				]
			);

			$this->add_responsive_control(
				'panel_button_close_padding',
				[
					'label' => esc_html__( 'Padding', 'themesflat-addons-for-elementor' ),
	                'type' => \Elementor\Controls_Manager::DIMENSIONS,
	                'size_units' => [ 'px' ],
	                'default' => [
	                    'top' => 10,
	                    'right' => 12,
	                    'bottom' => 10,
	                    'left' => 12,
	                    'unit' => 'px',
	                ],
					'selectors' => [
						'{{WRAPPER}} .tf-nav-menu .tf-close' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .tf-nav-menu .close-menu-panel-style-default' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
	        );

	        $this->add_responsive_control(
				'panel_button_close_margin',
				[
					'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
	                'type' => \Elementor\Controls_Manager::DIMENSIONS,
	                'size_units' => [ 'px' ],
	                'default' => [
	                    'top' => 0,
	                    'right' => 0,
	                    'bottom' => 0,
	                    'left' => 0,
	                    'unit' => 'px',
	                ],
					'selectors' => [
						'{{WRAPPER}} .tf-nav-menu .tf-close' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .tf-nav-menu .close-menu-panel-style-default' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
	        );

	        $this->start_controls_tabs( 
	        	'panel_button_close_tabs' 
	        	);				

				$this->start_controls_tab( 
					'panel_button_close_normal_tab',
					[
						'label' => esc_html__( 'Normal', 'themesflat-addons-for-elementor' ),						
					]
					);	

					$this->add_control(
						'panel_button_close_color',
						[
							'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => 'rgba(255,255,255,1)',
							'selectors' => [
								'{{WRAPPER}} .tf-nav-menu .tf-close' => 'color: {{VALUE}}',
								'{{WRAPPER}} .tf-nav-menu .close-menu-panel-style-default' => 'color: {{VALUE}}',
							],
						]
					);	

			        $this->add_control(
						'panel_button_close_bgcolor',
						[
							'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => 'rgba(255,255,255,0)',
							'selectors' => [
								'{{WRAPPER}} .tf-nav-menu .tf-close' => 'background-color: {{VALUE}}',
								'{{WRAPPER}} .tf-nav-menu .close-menu-panel-style-default' => 'background-color: {{VALUE}}',
							],
						]
					);												
				
				$this->end_controls_tab();

				$this->start_controls_tab( 
			    	'panel_button_close_hover_tab',
					[
						'label' => esc_html__( 'Hover', 'themesflat-addons-for-elementor' ),
					]
					);

					$this->add_control(
						'panel_button_close_color_hover',
						[
							'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => 'rgba(255,255,255,1)',
							'selectors' => [
								'{{WRAPPER}} .tf-nav-menu .tf-close:hover' => 'color: {{VALUE}}',
								'{{WRAPPER}} .tf-nav-menu .close-menu-panel-style-default:hover' => 'color: {{VALUE}}',
							],
						]
					);	

					$this->add_control(
						'panel_button_close_bgcolor_hover',
						[
							'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => 'rgba(255,255,255,0)',
							'selectors' => [
								'{{WRAPPER}} .tf-nav-menu .tf-close:hover' => 'background-color: {{VALUE}}',
								'{{WRAPPER}} .tf-nav-menu .close-menu-panel-style-default:hover' => 'background-color: {{VALUE}}',
							],
						]
					);									
				
				$this->end_controls_tab();

	        $this->end_controls_tabs();

	    	$this->end_controls_section();
	    // /.End Mobile Button & Close Icon Style

	    // Start Menu Panel Style 
	        $this->start_controls_section( 
	        	'section_style_mobile_panel',
	            [
	                'label' => esc_html__( 'Menu Panel', 'themesflat-addons-for-elementor' ),
	                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	            ]
	        );

	        $this->add_control(
				'mobile_menu_alignment',
				[
					'label' => esc_html__( 'Alignment', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::CHOOSE,
					'options' => [
						'mobile-menu-alignment-left'    => [
							'title' => esc_html__( 'Left', 'themesflat-addons-for-elementor' ),
							'icon' => 'eicon-text-align-left',
						],
						'mobile-menu-alignment-center' => [
							'title' => esc_html__( 'Center', 'themesflat-addons-for-elementor' ),
							'icon' => 'eicon-text-align-center',
						],
					],
					'default' => 'mobile-menu-alignment-left',
				]
			);

	        $this->add_control(
				'panel_background',
				[
					'label' => esc_html__( 'Background', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '#0A0A0A',
					'selectors' => [
						'{{WRAPPER}} .tf-nav-menu .nav-panel' => 'background-color: {{VALUE}}',
					],
				]
			);

	        $this->add_group_control(
				\Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'panel_box_shadow',
					'label' => esc_html__( 'Box Shadow', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-nav-menu .nav-panel',
				]
			);

	        $this->add_control(
				'panel_padding',
				[
					'label' => esc_html__( 'Padding', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'default' => [
	                    'top' => 0,
	                    'right' => 0,
	                    'bottom' => 0,
	                    'left' => 0,
	                    'unit' => 'px',
	                ],
					'selectors' => [
						'{{WRAPPER}} .tf-nav-menu .nav-panel' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'panel_width',
				[
					'label' => esc_html__( 'Width', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 700,
							'step' => 1,
	                    ],
	                    '%' => [
							'min' => 0,
							'max' => 200,
						],
	                ],
					'devices' => [ 'desktop', 'tablet', 'mobile' ],
					'desktop_default' => [
						'size' => 300,
						'unit' => 'px',
					],
					'tablet_default' => [
						'size' => 300,
						'unit' => 'px',
					],
					'mobile_default' => [
						'size' => 250,
						'unit' => 'px',
					],
					'selectors' => [
						'{{WRAPPER}} .tf-nav-menu .nav-panel' => 'width: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
						'menu_panel_style' => 'menu-panel-style-left',
					],
				]
			);			

			$this->add_control(
				'heading_panel_overlay',
				[
					'label' => esc_html__( 'Overlay', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => [
						'menu_panel_style' => 'menu-panel-style-left',
					],
				]
			);

			$this->add_control(
				'panel_background_overlay',
				[
					'label' => esc_html__( 'Background', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => 'rgba(0, 0, 0, 0.8)',
					'selectors' => [
						'{{WRAPPER}} .tf-nav-menu .mobile-menu-overlay' => 'background-color: {{VALUE}}',
					],
					'condition' => [
						'menu_panel_style' => 'menu-panel-style-left',
					],
				]
			);

	        $this->add_control(
				'heading_panel_logo',
				[
					'label' => esc_html__( 'Logo', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_responsive_control(
				'panel_logo_width',
				[
					'label' => esc_html__( 'Width', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 500,
							'step' => 1,
	                    ],
	                    '%' => [
							'min' => 0,
							'max' => 200,
						],
	                ],
	                'devices' => [ 'desktop', 'tablet', 'mobile' ],
					'desktop_default' => [
						'size' => 200,
						'unit' => 'px',
					],
					'tablet_default' => [
						'size' => 200,
						'unit' => 'px',
					],
					'mobile_default' => [
						'size' => 200,
						'unit' => 'px',
					],
					'selectors' => [
						'{{WRAPPER}} .tf-nav-menu .nav-panel .logo-nav' => 'width: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'panel_logo_padding',
				[
					'label' => esc_html__( 'Padding', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .tf-nav-menu .nav-panel .logo-nav' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'panel_logo_margin',
				[
					'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'default' => [
	                    'top' => 20,
	                    'right' => 0,
	                    'bottom' => 20,
	                    'left' => 20,
	                    'unit' => 'px',
	                ],
					'selectors' => [
						'{{WRAPPER}} .tf-nav-menu .nav-panel .logo-nav' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'heading_memu_mobile_divider',
				[
					'label' => esc_html__( 'Border Menu', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_control(
				'memu_mobile_divider_border',
				[
					'label'       => esc_html__( 'Border Style', 'themesflat-addons-for-elementor' ),
					'type'        => \Elementor\Controls_Manager::SELECT,
					'default'     => 'solid',
					'label_block' => false,
					'options'     => [
						'none'   => esc_html__( 'None', 'themesflat-addons-for-elementor' ),
						'solid'  => esc_html__( 'Solid', 'themesflat-addons-for-elementor' ),
						'double' => esc_html__( 'Double', 'themesflat-addons-for-elementor' ),
						'dotted' => esc_html__( 'Dotted', 'themesflat-addons-for-elementor' ),
						'dashed' => esc_html__( 'Dashed', 'themesflat-addons-for-elementor' ),
					],
					'selectors'   => [
						'{{WRAPPER}} .tf-nav-menu .mainnav-mobi .menu-container ul li' => 'border-top-style: {{VALUE}};',
						'{{WRAPPER}} .tf-nav-menu .mainnav-mobi .menu-container > ul > li:last-child' => 'border-bottom-style: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'memu_mobile_divider_border_color',
				[
					'label'     => esc_html__( 'Border Color', 'themesflat-addons-for-elementor' ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'default'   => '#FFFFFF0F',
					'selectors' => [
						'{{WRAPPER}} .tf-nav-menu .mainnav-mobi .menu-container ul li' => 'border-top-color: {{VALUE}};',
						'{{WRAPPER}} .tf-nav-menu .mainnav-mobi .menu-container > ul > li:last-child' => 'border-bottom-color: {{VALUE}};',
					],
					'condition' => [
						'memu_mobile_divider_border!' => 'none',
					],
				]
			);

			$this->add_control(
				'memu_mobile_divider_width',
				[
					'label'     => esc_html__( 'Border Width', 'themesflat-addons-for-elementor' ),
					'type'      => \Elementor\Controls_Manager::SLIDER,
					'range'     => [
						'px' => [
							'max' => 50,
						],
					],
					'default'   => [
						'size' => '1',
						'unit' => 'px',
					],
					'selectors' => [
						'{{WRAPPER}} .tf-nav-menu .mainnav-mobi .menu-container ul li' => 'border-top-width: {{SIZE}}{{UNIT}}',
						'{{WRAPPER}} .tf-nav-menu .mainnav-mobi .menu-container > ul > li:last-child' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
					],
					'condition' => [
						'memu_mobile_divider_border!' => 'none',
					],
				]
			);

			$this->add_control(
				'heading_panel_menu',
				[
					'label' => esc_html__( 'Menu', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);			

			$this->add_control(
				'panel_memu_mobile_margin',
				[
					'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .tf-nav-menu .mainnav-mobi' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);			

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'panel_menu_typography',
					'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
					'fields_options' => [
			            'typography' => ['default' => 'yes'],
			            'font_family' => [ 'default' => 'Poppins' ],
			            'font_size' => ['default' => ['size' => 16]],
			            'font_weight' => ['default' => 600],
			            'text_transform' => ['default' => 'uppercase'],
			        ],
					'selector' => '{{WRAPPER}} .tf-nav-menu .nav-panel .mainnav-mobi ul li a',
				]
			);	

			$this->add_control(
				'horizontal_padding_link',
				[
					'label'     => esc_html__( 'Horizontal Padding Link (px)', 'themesflat-addons-for-elementor' ),
					'type'      => \Elementor\Controls_Manager::SLIDER,
					'separator' => 'before',
					'range'     => [
						'px' => [
							'max' => 200,
						],
					],
					'default'   => [
						'size' => '20',
						'unit' => 'px',
					],
					'selectors' => [
						'{{WRAPPER}} .tf-nav-menu .mainnav-mobi .menu-container ul li a' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}}',
						'{{WRAPPER}} .tf-nav-menu .mainnav-mobi .menu-container ul.sub-menu li a' => 'padding-left: calc({{SIZE}}{{UNIT}} + 10px); padding-right: calc({{SIZE}}{{UNIT}} + 10px)',
		 			'{{WRAPPER}} .tf-nav-menu .mainnav-mobi .menu-container ul.sub-menu ul.sub-menu li a' => 'padding-left: calc({{SIZE}}{{UNIT}} + 20px); padding-right: calc({{SIZE}}{{UNIT}} + 20px)',
					],
				]
			);

			$this->add_control(
				'vertical_padding_link',
				[
					'label'     => esc_html__( 'Vertical Padding Link (px)', 'themesflat-addons-for-elementor' ),
					'type'      => \Elementor\Controls_Manager::SLIDER,
					'range'     => [
						'px' => [
							'max' => 200,
						],
					],
					'default'   => [
						'size' => '18',
						'unit' => 'px',
					],
					'selectors' => [
						'{{WRAPPER}} .tf-nav-menu .mainnav-mobi .menu-container ul li a' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}}',
					],
				]
			);

			$this->add_control(
				'width_icon_submenu',
				[
					'label'     => esc_html__( 'Width Icon Submenu (px)', 'themesflat-addons-for-elementor' ),
					'type'      => \Elementor\Controls_Manager::SLIDER,						
					'range'     => [
						'px' => [
							'max' => 200,
						],
					],
					'default'   => [
						'size' => '60',
						'unit' => 'px',
					],
					'selectors' => [
						'{{WRAPPER}} .tf-nav-menu .mainnav-mobi .btn-submenu' => 'width: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'height_icon_submenu',
				[
					'label'     => esc_html__( 'Height Icon Submenu (px)', 'themesflat-addons-for-elementor' ),
					'type'      => \Elementor\Controls_Manager::SLIDER,						
					'range'     => [
						'px' => [
							'max' => 200,
						],
					],
					'default'   => [
						'size' => '60',
						'unit' => 'px',
					],
					'selectors' => [
						'{{WRAPPER}} .tf-nav-menu .mainnav-mobi .btn-submenu' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->start_controls_tabs( 'panel_menu_tabs' );				

				$this->start_controls_tab( 
					'panel_menu_normal_tab',
					[
						'label' => esc_html__( 'Normal', 'themesflat-addons-for-elementor' ),						
					]
					);

			        $this->add_control(
						'panel_menu_color',
						[
							'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '#ffffff',
							'selectors' => [
								'{{WRAPPER}} .tf-nav-menu .mainnav-mobi .menu-container ul li a, {{WRAPPER}} .tf-nav-menu .mainnav-mobi .btn-submenu i' => 'color: {{VALUE}}',
							],
						]
					);		
				
				$this->end_controls_tab();

				$this->start_controls_tab( 
			    	'panel_menu_hover_tab',
					[
						'label' => esc_html__( 'Hover', 'themesflat-addons-for-elementor' ),
					]
					);

			        $this->add_control(
						'panel_menu_color_hover',
						[
							'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '#23A455',
							'selectors' => [
								'{{WRAPPER}} .tf-nav-menu .mainnav-mobi .menu-container ul li a:hover' => 'color: {{VALUE}}',
							],
						]
					);					
				
				$this->end_controls_tab();

				$this->start_controls_tab( 
			    	'panel_menu_active_tab',
					[
						'label' => esc_html__( 'Active', 'themesflat-addons-for-elementor' ),
					]
					);

			        $this->add_control(
						'panel_menu_color_active',
						[
							'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '#23A455',
							'selectors' => [
								'{{WRAPPER}} .tf-nav-menu .mainnav-mobi .menu-container ul li.current_page_item > a' => 'color: {{VALUE}}',
								'{{WRAPPER}} .tf-nav-menu .mainnav-mobi .menu-container ul li.current-menu-ancestor > a' => 'color: {{VALUE}}',
								'{{WRAPPER}} .tf-nav-menu .mainnav-mobi .menu-container ul li.current-menu-item > a' => 'color: {{VALUE}}',
							],
						]
					);						
				
				$this->end_controls_tab();
			$this->end_controls_tabs();
	    	$this->end_controls_section();
	    // /.End Menu Panel Style
	}

	protected function render($instance = []) {
		$settings = $this->get_settings_for_display();
		$class = $btn_menu_mobile_icon = $btn_menu_close = $url_logo = $logo = $submenu_icon = $one_page = '';

		if ($settings['one_page_enable'] == 'yes') {
			$one_page = ' has-one-page';
		}

		$class .= $settings['main_menu_position'] . ' ' . $settings['layout'] . ' '.$settings['menu_panel_style'] .' tf_link_effect_'. $settings['link_hover_effect'] .' tf_animation_line_'. $settings['animation_line'] . $one_page . ' '.$settings['dropdown_style'];

		switch ($settings['submenu_icon']) {
			case 'classic':
				$submenu_icon = '<i class="fa fa-caret-right" aria-hidden="true"></i>';
				break;
			case 'arrows':
				$submenu_icon = '<i class="fa fa-angle-right" aria-hidden="true"></i>';
				break;	
			case 'plus':
				$submenu_icon = '<i>+</i>';
				break;		
			default:
				$submenu_icon = '<i class="fa fa-angle-right" aria-hidden="true"></i>';
				break;
		}

		if ( $settings['btn_menu_mobile_icon']['value'] != '' ) {
			if ( !empty( $settings['btn_menu_mobile_icon']['value']['url'] ) ) {
				$btn_menu_mobile_icon = sprintf(
		           '<img class="logo_svg" src="%1$s" alt="%2$s"/>',
		             $settings['btn_menu_mobile_icon']['value']['url'],
		             $settings['btn_menu_mobile_icon']['value']['id']
		            
		         ); 
			} else {
				$btn_menu_mobile_icon = sprintf(
		             '<i class="%1$s"></i>',
		            $settings['btn_menu_mobile_icon']['value']
		        );  
			}
		}


		if ( $settings['btn_menu_close']['value'] != '' ) {
			if ( !empty( $settings['btn_menu_close']['value']['url'] ) ) {
				$btn_menu_close = sprintf(
		           '<img class="logo_svg" src="%1$s" alt="%2$s"/>',
		             $settings['btn_menu_close']['value']['url'],
		             $settings['btn_menu_close']['value']['id']
		            
		         ); 
			} else {
				$btn_menu_close = sprintf(
		             '<i class="%1$s"></i>',
		            $settings['btn_menu_close']['value']
		        );  
			}
		}

		if ($settings['nav_menu_logo']['url']) {
			$url_logo = $settings['nav_menu_logo']['url'];			
			if ($settings['nav_menu_logo_url_to'] == 'custom') {			
				$logo = '<a href="'.$settings['nav_menu_logo_link']['url'].'" class="logo-nav"> <img src="'.$url_logo.'" alt="'.get_bloginfo('name').'"> </a>';

			}else {		
				$logo = '<a href="'. esc_url(home_url('/')).'" class="logo-nav"> <img src="'.$url_logo.'" alt="'.get_bloginfo('name').'"></a>';
			}
		}else {
			if ($settings['nav_menu_logo_url_to'] == 'custom') {			
				$logo = '<a href="'.$settings['nav_menu_logo_link']['url'].'" class="logo-nav">'.get_bloginfo('name').'</a>';

			}else {		
				$logo = '<a href="'. esc_url(home_url('/')).'" class="logo-nav">'.get_bloginfo('name').'</a>';
			}
		}

		
		$id_random = 'tf-nav-'.uniqid();

		$args = array(
	        'menu'            => $settings['nav_menu'],
	        'container'       => 'div',
	        'container_class' => 'menu-container tf-menu-container',
	        'container_id'    => '',
	        'menu_class'      => 'menu',
	        'menu_id'         => '',
	        'echo'            => false,
	        'fallback_cb'     => 'wp_page_menu',
	        'before'          => '',
	        'after'           => '',
	        'link_before'     => '',
	        'link_after'      => $submenu_icon,
	        'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
	        'item_spacing'    => 'preserve',
	        'depth'           => 0,
	        'walker'          => '',
	        'theme_location'  => '',
	    );
		
		echo sprintf ( 
			'<div class="tf-nav-menu %1$s %6$s" data-id_random="%6$s">
				<div class="nav-panel %7$s">
					<div class="wrap-logo-nav">%4$s</div>
					<div class="mainnav-mobi">%2$s</div>
					<div class="wrap-close-menu-panel-style-default"><button class="close-menu-panel-style-default">%5$s</button></div>					
				</div>				
				<div class="mainnav nav">%2$s</div>
				<div class="mobile-menu-overlay"></div>
				<button class="tf-close">%5$s</button>
				<button class="btn-menu-mobile">
					<span class="open-icon">%3$s</span>
				</button>
				<button class="btn-menu-only">
					<span class="open-icon">%3$s</span>
				</button>
			</div>',
			$class,
            wp_nav_menu($args),
            $btn_menu_mobile_icon,
            $logo,
            $btn_menu_close,
            $id_random,
            $settings['mobile_menu_alignment']         
        );
	}

	

}
