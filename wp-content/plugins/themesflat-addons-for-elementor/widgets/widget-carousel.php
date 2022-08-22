<?php
class TFCarousel_Widget_Free extends \Elementor\Widget_Base {

	public function get_name() {
        return 'tfcarousel';
    }
    
    public function get_title() {
        return esc_html__( 'TF Carousel', 'themesflat-addons-for-elementor' );
    }

    public function get_icon() {
        return 'eicon-slider-push';
    }
    
    public function get_categories() {
        return [ 'themesflat_addons' ];
    }

    public function get_style_depends() {
		return ['tf-font-awesome','tf-regular','owl-carousel','tf-style'];
	}
	public function get_script_depends() {
		return ['owl-carousel','tf-main'];
	}	

	protected function register_controls() {
        // Start Carousel Setting        
		$this->start_controls_section( 
			'section_carousel',
            [
                'label' => esc_html__('Carousel', 'themesflat-addons-for-elementor'),
            ]
        );	    

		$repeater = new \Elementor\Repeater();

		$repeater->add_control( 
			'list_content_type',
			[
				'label' => esc_html__( 'Content type', 'themesflat-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'content',
				'options' => [
					'content'  => esc_html__( 'Content', 'themesflat-addons-for-elementor' ),
					'template' => esc_html__( 'Template', 'themesflat-addons-for-elementor' ),
				],
			]
		);

		$repeater->add_control(
			'image',
			[
				'label' => __( 'Choose Image', 'themesflat-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => URL_THEMESFLAT_ADDONS_ELEMENTOR_FREE."assets/img/placeholder.jpg",
				],
				'condition' => [
					'list_content_type' => 'content',
				],
			]
		);

		$repeater->add_control( 
			'list_content_template',
			[
				'label' => esc_html__( 'Template', 'themesflat-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => ThemesFlat_Addon_For_Elementor_Free::tf_get_template_elementor(),
				'condition' => [
					'list_content_type' => 'template',
				],				
			]
		);	

		$this->add_control( 
			'carousel_list',
				[					
					'type' => \Elementor\Controls_Manager::REPEATER,
					'fields' => $repeater->get_controls(),
					'default' => [
						[ ],
						[ ],
						[ ],
					],					
				]
			);
		
		$this->end_controls_section();
        // /.End Carousel	

        // Start Setting        
		$this->start_controls_section( 
			'section_setting',
            [
                'label' => esc_html__('Setting', 'themesflat-addons-for-elementor'),
            ]
        );	

		$this->add_control( 
			'carousel_loop',
			[
				'label' => esc_html__( 'Loop', 'themesflat-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'themesflat-addons-for-elementor' ),
				'label_off' => esc_html__( 'Off', 'themesflat-addons-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',				
			]
		);

		$this->add_control( 
			'carousel_auto',
			[
				'label' => esc_html__( 'Auto Play', 'themesflat-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'themesflat-addons-for-elementor' ),
				'label_off' => esc_html__( 'Off', 'themesflat-addons-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',				
			]
		);	

		$this->add_control(
			'carousel_spacer',
			[
				'label' => esc_html__( 'Spacer', 'themesflat-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 100,
				'step' => 1,
				'default' => 30,				
			]
		);

		$this->add_control( 
        	'carousel_column_desk',
			[
				'label' => esc_html__( 'Columns Desktop', 'themesflat-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '2',
				'options' => [
					'1' => esc_html__( '1', 'themesflat-addons-for-elementor' ),
					'2' => esc_html__( '2', 'themesflat-addons-for-elementor' ),
					'3' => esc_html__( '3', 'themesflat-addons-for-elementor' ),
					'4' => esc_html__( '4', 'themesflat-addons-for-elementor' ),
					'5' => esc_html__( '5', 'themesflat-addons-for-elementor' ),
					'6' => esc_html__( '6', 'themesflat-addons-for-elementor' ),
				],				
			]
		);

		$this->add_control( 
        	'carousel_column_tablet',
			[
				'label' => esc_html__( 'Columns Tablet', 'themesflat-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '1',
				'options' => [
					'1' => esc_html__( '1', 'themesflat-addons-for-elementor' ),
					'2' => esc_html__( '2', 'themesflat-addons-for-elementor' ),
					'3' => esc_html__( '3', 'themesflat-addons-for-elementor' ),
					'4' => esc_html__( '4', 'themesflat-addons-for-elementor' ),
					'5' => esc_html__( '5', 'themesflat-addons-for-elementor' ),
					'6' => esc_html__( '6', 'themesflat-addons-for-elementor' ),
				],				
			]
		);

		$this->add_control( 
        	'carousel_column_mobile',
			[
				'label' => esc_html__( 'Columns Mobile', 'themesflat-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '1',
				'options' => [
					'1' => esc_html__( '1', 'themesflat-addons-for-elementor' ),
					'2' => esc_html__( '2', 'themesflat-addons-for-elementor' ),
					'3' => esc_html__( '3', 'themesflat-addons-for-elementor' ),
					'4' => esc_html__( '4', 'themesflat-addons-for-elementor' ),
					'5' => esc_html__( '5', 'themesflat-addons-for-elementor' ),
					'6' => esc_html__( '6', 'themesflat-addons-for-elementor' ),
				],				
			]
		);		
        $this->end_controls_section();
        // /.End Setting

        // Start Arrow        
		$this->start_controls_section( 
			'section_arrow',
            [
                'label' => esc_html__('Arrow', 'themesflat-addons-for-elementor'),
            ]
        );

        $this->add_control( 
			'carousel_arrow',
			[
				'label' => esc_html__( 'Arrow', 'themesflat-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'themesflat-addons-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'themesflat-addons-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',				
				'description'	=> 'Just show when you have two slide',
				'separator' => 'before',
			]
		);

        $this->add_control( 
			'carousel_prev_icon', [
                'label' => esc_html__( 'Prev Icon', 'themesflat-addons-for-elementor' ),
                'type' => \Elementor\Controls_Manager::ICON,
                'default' => 'fa fa-chevron-left',
                'include' => [
					'fa fa-angle-double-left',
					'fa fa-angle-left',
					'fa fa-chevron-left',
					'fa fa-arrow-left',
				],  
                'condition' => [                	
                    'carousel_arrow' => 'yes',
                ]
            ]
        );

    	$this->add_control( 
    		'carousel_next_icon', [
                'label' => esc_html__( 'Next Icon', 'themesflat-addons-for-elementor' ),
                'type' => \Elementor\Controls_Manager::ICON,
                'default' => 'fa fa-chevron-right',
                'include' => [
					'fa fa-angle-double-right',
					'fa fa-angle-right',
					'fa fa-chevron-right',
					'fa fa-arrow-right',
				], 
                'condition' => [                	
                    'carousel_arrow' => 'yes',
                ]
            ]
        );

        $this->add_responsive_control( 
        	'carousel_arrow_fontsize',
			[
				'label' => esc_html__( 'Font Size', 'themesflat-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .tf-carousel-box .owl-nav .owl-prev, {{WRAPPER}} .tf-carousel-box .owl-nav .owl-next' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [					
                    'carousel_arrow' => 'yes',
                ]
			]
		);

		$this->add_responsive_control( 
			'w_size_carousel_arrow',
			[
				'label' => esc_html__( 'Width', 'themesflat-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 70,
				],
				'selectors' => [
					'{{WRAPPER}} .tf-carousel-box .owl-nav .owl-prev, {{WRAPPER}} .tf-carousel-box .owl-nav .owl-next' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [					
                    'carousel_arrow' => 'yes',
                ]
			]
		);

		$this->add_responsive_control( 
			'h_size_carousel_arrow',
			[
				'label' => esc_html__( 'Height', 'themesflat-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 70,
				],
				'selectors' => [
					'{{WRAPPER}} .tf-carousel-box .owl-nav .owl-prev, {{WRAPPER}} .tf-carousel-box .owl-nav .owl-next' => 'height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [					
                    'carousel_arrow' => 'yes',
                ]
			]
		);	

		$this->add_responsive_control( 
			'carousel_arrow_horizontal_position_prev',
			[
				'label' => esc_html__( 'Horizontal Position Previous', 'themesflat-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -200,
						'max' => 2000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tf-carousel-box .owl-nav .owl-prev' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [					
                    'carousel_arrow' => 'yes',
                ]
			]
		);

		$this->add_responsive_control( 
			'carousel_arrow_horizontal_position_next',
			[
				'label' => esc_html__( 'Horizontal Position Next', 'themesflat-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -200,
						'max' => 2000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .tf-carousel-box .owl-nav .owl-next' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [					
                    'carousel_arrow' => 'yes',
                ]
			]
		);

		$this->add_responsive_control( 
			'carousel_arrow_vertical_position',
			[
				'label' => esc_html__( 'Vertical Position', 'themesflat-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .tf-carousel-box .owl-nav .owl-prev, {{WRAPPER}} .tf-carousel-box .owl-nav .owl-next' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [					
                    'carousel_arrow' => 'yes',
                ]
			]
		);

		$this->start_controls_tabs( 
			'carousel_arrow_tabs',
			[
				'condition' => [
	                'carousel_arrow' => 'yes',	                
	            ]
			] );

			$this->start_controls_tab( 
				'carousel_arrow_normal_tab',
				[
					'label' => esc_html__( 'Normal', 'themesflat-addons-for-elementor' ),						
				]
			);

			$this->add_control( 
				'carousel_arrow_color',
	            [
	                'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
	                'type' => \Elementor\Controls_Manager::COLOR,
	                'default' => '#ffffff',
	                'selectors' => [
						'{{WRAPPER}} .tf-carousel-box .owl-nav .owl-prev, {{WRAPPER}} .tf-carousel-box .owl-nav .owl-next' => 'color: {{VALUE}}',
					],
					'condition' => [
	                    'carousel_arrow' => 'yes',
	                ]
	            ]
	        );

	        $this->add_control( 
	        	'carousel_arrow_bg_color',
	            [
	                'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
	                'type' => \Elementor\Controls_Manager::COLOR,
	                'default' => '#0080f0',
	                'selectors' => [
						'{{WRAPPER}} .tf-carousel-box .owl-nav .owl-prev, {{WRAPPER}} .tf-carousel-box .owl-nav .owl-next' => 'background-color: {{VALUE}};',
					],
					'condition' => [
	                    'carousel_arrow' => 'yes',
	                ]
	            ]
	        );	

	        $this->add_group_control( 
	        	\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'carousel_arrow_border',
					'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-carousel-box .owl-nav .owl-prev, {{WRAPPER}} .tf-carousel-box .owl-nav .owl-next',
					'condition' => [
	                    'carousel_arrow' => 'yes',
	                ]
				]
			);

			$this->add_responsive_control( 
				'carousel_arrow_border_radius',
	            [
	                'label' => esc_html__( 'Border Radius Previous', 'themesflat-addons-for-elementor' ),
	                'type' => \Elementor\Controls_Manager::DIMENSIONS,
	                'size_units' => [ 'px', '%', 'em' ],
	                'selectors' => [
	                    '{{WRAPPER}} .tf-carousel-box .owl-nav .owl-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	                ],
	                'condition' => [
	                    'carousel_arrow' => 'yes',
	                ]
	            ]
	        );

	        $this->add_responsive_control( 
				'carousel_arrow_border_radius_next',
	            [
	                'label' => esc_html__( 'Border Radius Next', 'themesflat-addons-for-elementor' ),
	                'type' => \Elementor\Controls_Manager::DIMENSIONS,
	                'size_units' => [ 'px', '%', 'em' ],
	                'selectors' => [
	                    '{{WRAPPER}} .tf-carousel-box .owl-nav .owl-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	                ],
	                'condition' => [
	                    'carousel_arrow' => 'yes',
	                ]
	            ]
	        );

	        $this->end_controls_tab();

	        $this->start_controls_tab( 
		    	'carousel_arrow_hover_tab',
				[
					'label' => esc_html__( 'Hover', 'themesflat-addons-for-elementor' ),
				]
			);

	    	$this->add_control( 
	    		'carousel_arrow_color_hover',
	            [
	                'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
	                'type' => \Elementor\Controls_Manager::COLOR,
	                'default' => '#ffffff',
	                'selectors' => [
						'{{WRAPPER}} .tf-carousel-box .owl-nav .owl-prev:hover, {{WRAPPER}} .tf-carousel-box .owl-nav .owl-next:hover' => 'color: {{VALUE}}',
					],
					'condition' => [
	                    'carousel_arrow' => 'yes',
	                ]
	            ]
	        );

	        $this->add_control( 
	        	'carousel_arrow_hover_bg_color',
	            [
	                'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
	                'type' => \Elementor\Controls_Manager::COLOR,
	                'default' => '#222222',
	                'selectors' => [
						'{{WRAPPER}} .tf-carousel-box .owl-nav .owl-prev:hover, {{WRAPPER}} .tf-carousel-box .owl-nav .owl-next:hover' => 'background-color: {{VALUE}};',
					],
					'condition' => [
	                    'carousel_arrow' => 'yes',
	                ]
	            ]
	        );

	        $this->add_group_control( 
	        	\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'carousel_arrow_border_hover',
					'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-carousel-box .owl-nav .owl-prev:hover, {{WRAPPER}} .tf-carousel-box .owl-nav .owl-next:hover',
					'condition' => [
	                    'carousel_arrow' => 'yes',
	                ]
				]
			);

			$this->add_responsive_control( 
				'carousel_arrow_border_radius_hover',
	            [
	                'label' => esc_html__( 'Border Radius Previous', 'themesflat-addons-for-elementor' ),
	                'type' => \Elementor\Controls_Manager::DIMENSIONS,
	                'size_units' => [ 'px', '%', 'em' ],
	                'selectors' => [
	                    '{{WRAPPER}} .tf-carousel-box .owl-nav .owl-prev:hover, {{WRAPPER}} .tf-carousel-box .owl-nav .owl-next:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	                ],
	                'condition' => [
	                    'carousel_arrow' => 'yes',
	                ]
	            ]
	        );

       		$this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
        // /.End Arrow

        // Start Arrow        
		$this->start_controls_section( 
			'section_bullets',
            [
                'label' => esc_html__('Bullets', 'themesflat-addons-for-elementor'),
            ]
        );

		$this->add_control( 
			'carousel_bullets',
            [
                'label'         => esc_html__( 'Bullets', 'themesflat-addons-for-elementor' ),
                'type'          => \Elementor\Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Show', 'themesflat-addons-for-elementor' ),
                'label_off'     => esc_html__( 'Hide', 'themesflat-addons-for-elementor' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
                'separator' => 'before',
            ]
        );        

		$this->add_responsive_control( 
			'carousel_bullets_horizontal_position',
			[
				'label' => esc_html__( 'Horizonta Offset', 'themesflat-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .tf-carousel-box .owl-dots' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [					
                    'carousel_bullets' => 'yes',
                ]
			]
		);

		$this->add_responsive_control( 
			'carousel_bullets_vertical_position',
			[
				'label' => esc_html__( 'Vertical Offset', 'themesflat-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -200,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => -40,
				],
				'selectors' => [
					'{{WRAPPER}} .tf-carousel-box .owl-dots' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [					
                    'carousel_bullets' => 'yes',
                ]
			]
		);

		$this->add_responsive_control( 
			'carousel_bullets_margin',
			[
				'label' => esc_html__( 'Spacing', 'themesflat-addons-for-elementor' ),
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
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .tf-carousel-box .owl-dots .owl-dot' => 'margin: 0 {{SIZE}}{{UNIT}};',
				],
				'condition' => [
                    'carousel_bullets' => 'yes',
                ]
			]
		);

		$this->start_controls_tabs( 
			'carousel_bullets_tabs',
				[
					'condition' => [						
	                    'carousel_bullets' => 'yes',
	                ]
				] );
			$this->start_controls_tab( 
				'carousel_bullets_normal_tab',
				[
					'label' => esc_html__( 'Normal', 'themesflat-addons-for-elementor' ),						
				]
			);

			$this->add_responsive_control( 
	        	'w_size_carousel_bullets',
					[
						'label' => esc_html__( 'Width', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 100,
								'step' => 1,
							]
						],
						'default' => [
							'unit' => 'px',
							'size' => 15,
						],
						'selectors' => [
							'{{WRAPPER}} .tf-carousel-box .owl-dots .owl-dot' => 'width: {{SIZE}}{{UNIT}};',
						],
						'condition' => [						
		                    'carousel_bullets' => 'yes',
		                ]
					]
			);			

			$this->add_responsive_control( 
				'h_size_carousel_bullets',
				[
					'label' => esc_html__( 'Height', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
							'step' => 1,
						]
					],
					'default' => [
						'unit' => 'px',
						'size' => 15,
					],
					'selectors' => [
						'{{WRAPPER}} .tf-carousel-box .owl-dots .owl-dot' => 'height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
					],
					'condition' => [					
	                    'carousel_bullets' => 'yes',
	                ]
				]
			);

			$this->add_control( 
				'carousel_bullets_bg_color',
	            [
	                'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
	                'type' => \Elementor\Controls_Manager::COLOR,
	                'default' => '#0080f0',
	                'selectors' => [
						'{{WRAPPER}} .tf-carousel-box .owl-dots .owl-dot' => 'background-color: {{VALUE}}',
					],
					'condition' => [
	                    'carousel_bullets' => 'yes',
	                ]
	            ]
	        );

	        $this->add_group_control( 
	        	\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'carousel_bullets_border',
					'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-carousel-box .owl-dots .owl-dot',
					'condition' => [
	                    'carousel_bullets' => 'yes',
	                ]
				]
			);

			$this->add_responsive_control( 
				'carousel_bullets_border_radius',
	            [
	                'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
	                'type' => \Elementor\Controls_Manager::DIMENSIONS,
	                'size_units' => [ 'px', '%', 'em' ],
	                'selectors' => [
	                    '{{WRAPPER}} .tf-carousel-box .owl-dots .owl-dot' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	                ],
	                'condition' => [
	                    'carousel_bullets' => 'yes',
	                ]
	            ]
	        );

		    $this->end_controls_tab();

	        $this->start_controls_tab( 
	        	'carousel_bullets_hover_tab',
				[
					'label' => esc_html__( 'Active', 'themesflat-addons-for-elementor' ),
				]
			);

			$this->add_responsive_control( 
	        	'w_size_carousel_bullets_active',
					[
						'label' => esc_html__( 'Width', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 100,
								'step' => 1,
							]
						],
						'default' => [
							'unit' => 'px',
							'size' => 15,
						],
						'selectors' => [
							'{{WRAPPER}} .tf-carousel-box .owl-dots .owl-dot.active' => 'width: {{SIZE}}{{UNIT}};',
						],
						'condition' => [						
		                    'carousel_bullets' => 'yes',
		                ]
					]
			);

			$this->add_responsive_control( 
				'h_size_carousel_bullets_active',
				[
					'label' => esc_html__( 'Height', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
							'step' => 1,
						]
					],
					'default' => [
						'unit' => 'px',
						'size' => 15,
					],
					'selectors' => [
						'{{WRAPPER}} .tf-carousel-box .owl-dots .owl-dot.active' => 'height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
					],
					'condition' => [					
	                    'carousel_bullets' => 'yes',
	                ]
				]
			);

			$this->add_control( 
				'size_carousel_bullets_active_scale_hover',
				[
					'label' => esc_html__( 'Scale', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 1,
							'max' => 2,
							'step' => 0.1,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 1,
					],
					'selectors' => [
						'{{WRAPPER}} .tf-carousel-box .owl-dots .owl-dot.active, {{WRAPPER}} .tf-carousel-box .owl-dots .owl-dot:hover' => 'transform: scale({{SIZE}});',
					],
				]
			);	

        	$this->add_control( 
        		'carousel_bullets_hover_bg_color',
	            [
	                'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
	                'type' => \Elementor\Controls_Manager::COLOR,
	                'default' => '#000000',
	                'selectors' => [
						'{{WRAPPER}} .tf-carousel-box .owl-dots .owl-dot:hover, {{WRAPPER}} .tf-carousel-box .owl-dots .owl-dot.active' => 'background-color: {{VALUE}}',
					],
					'condition' => [
	                    'carousel_bullets' => 'yes',
	                ]
	            ]
	        );

        	$this->add_group_control( 
        		\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'carousel_bullets_border_hover',
					'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-carousel-box .owl-dots .owl-dot:hover, {{WRAPPER}} .tf-carousel-box .owl-dots .owl-dot.active',
					'condition' => [
	                    'carousel_bullets' => 'yes',
	                ]
				]
			);

			$this->add_responsive_control( 
				'carousel_bullets_border_radius_hover',
	            [
	                'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
	                'type' => \Elementor\Controls_Manager::DIMENSIONS,
	                'size_units' => [ 'px', '%', 'em' ],
	                'selectors' => [
	                    '{{WRAPPER}} .tf-carousel-box .owl-dots .owl-dot:hover, {{WRAPPER}} .tf-carousel-box .owl-dots .owl-dot.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	                ],
	                'condition' => [
	                    'carousel_bullets' => 'yes',
	                ]
	            ]
	        );

			$this->end_controls_tab();

	    $this->end_controls_tabs();	

        $this->end_controls_section();
        // /.End Arrow    
	    
	}

	protected function render($instance = []) {
		$settings = $this->get_settings_for_display();
		
		$carousel_arrow = 'no-arrow';
		if ( $settings['carousel_arrow'] == 'yes' ) {
			$carousel_arrow = 'has-arrow';
		}

		$carousel_bullets = 'no-bullets';
		if ( $settings['carousel_bullets'] == 'yes' ) {
			$carousel_bullets = 'has-bullets';
		}

		?>
		<div class="tf-carousel-box <?php echo esc_attr($carousel_arrow); ?> <?php echo esc_attr($carousel_bullets); ?>" data-loop="<?php echo esc_attr($settings['carousel_loop']); ?>" data-auto="<?php echo esc_attr($settings['carousel_auto']); ?>" data-column="<?php echo esc_attr($settings['carousel_column_desk']); ?>" data-column2="<?php echo esc_attr($settings['carousel_column_tablet']); ?>" data-column3="<?php echo esc_attr($settings['carousel_column_mobile']); ?>" data-spacer="<?php echo esc_attr($settings['carousel_spacer']); ?>" data-prev_icon="<?php echo esc_attr($settings['carousel_prev_icon']) ?>" data-next_icon="<?php echo esc_attr($settings['carousel_next_icon']) ?>">
			<div class="owl-carousel owl-theme">
			<?php foreach ($settings['carousel_list'] as $carousel): ?>
				<?php if($carousel['list_content_type'] == 'content') : ?>
					<div class="item"><img src="<?php echo esc_attr($carousel['image']['url']); ?>" alt="image"></div>
				<?php elseif($carousel['list_content_type'] == 'template') : ?>
					<div class="item">
						<?php 
						if ( !empty($carousel['list_content_template']) ) {
				            $post_id = flat_get_post_page_content($carousel['list_content_template']);
				            echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($post_id);	
				        }	
						?>
					</div>
				<?php endif; ?>
			<?php endforeach;?>
			</div>
		</div>
		<?php	
	}	

}