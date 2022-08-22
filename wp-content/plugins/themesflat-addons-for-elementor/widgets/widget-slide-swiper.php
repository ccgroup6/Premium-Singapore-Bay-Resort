<?php
class TFSlideSwiper_Widget_Free extends \Elementor\Widget_Base {

	public function get_name() {
        return 'tf-slide-swiper';
    }
    
    public function get_title() {
        return esc_html__( 'TF Slide Swiper', 'themesflat-addons-for-elementor' );
    }

    public function get_icon() {
        return 'eicon-slider-push';
    }
    
    public function get_categories() {
        return [ 'themesflat_addons' ];
    }

    public function get_style_depends() {
		return ['tf-font-awesome','tf-regular','swiper','tf-style'];
	}

	public function get_script_depends() {
		return ['tf-swiper','tf-main'];
	}

	protected function register_controls() {
		// Start Slides Setting 	
			$this->start_controls_section(
				'section_slides',
				[
					'label' => esc_html__( 'Slides', 'themesflat-addons-for-elementor' ),
				]
			);	

			$this->add_control(
				'gallery',
				[
					'label' => esc_html__( 'Add Images', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::GALLERY,
					'default' => [],
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Image_Size::get_type(),
				[
					'name' => 'thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
					'separator' => 'none',
					'default' => 'full',
				]
			);		

			$this->end_controls_section();
		// /.End Slides Setting

		// Start Slides Options 
			$this->start_controls_section(
				'section_slider_options',
				[
					'label' => esc_html__( 'Slides Options', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SECTION,
				]
			);			

			$this->add_control(
				'direction',
				[
					'label' => esc_html__( 'Motion Direction', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'horizontal',
					'options' => [
						'horizontal' => esc_html__( 'Horizontal', 'themesflat-addons-for-elementor' ),
						'vertical' => esc_html__( 'Vertical', 'themesflat-addons-for-elementor' ),
					],
				]
			);

			$this->add_control(
				'reverse_direction',
				[
					'label' => esc_html__( 'Reverse Direction', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'return_value' => 'yes',
					'default' => 'no',
				]
			);

			$this->add_responsive_control( 
	        	'vertical_height',
				[
					'label' => esc_html__( 'Vertical Height', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1000,
							'step' => 1,
						]
					],
					'default' => [
						'unit' => 'px',
						'size' => 570,
					],
					'selectors' => [
						'{{WRAPPER}} .tf-slide-swiper .swiper-container-vertical' => 'height: {{SIZE}}{{UNIT}};',
					],
					'condition' => [					
	                    'direction' => 'vertical',
	                ]
				]
			);

			$this->add_control(
				'autoplay',
				[
					'label' => esc_html__( 'Autoplay Transfer', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'return_value' => 'yes',
					'default' => 'yes',
				]
			);

			$this->add_control(
				'pause_on_interaction',
				[
					'label' => esc_html__( 'Pause on Interaction', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'return_value' => 'yes',
					'default' => 'yes',
					'condition' => [
						'autoplay!' => '',
					],
				]
			);

			$this->add_control(
				'autoplay_speed',
				[
					'label' => esc_html__( 'Autoplay Speed', 'themesflat-addons-for-elementor' ) . ' (ms)',
					'type' => \Elementor\Controls_Manager::NUMBER,
					'default' => 100,
					'condition' => [
						'autoplay' => 'yes',
					],
				]
			);

			$this->add_control(
				'infinite',
				[
					'label' => esc_html__( 'Infinite Loop', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'return_value' => 'yes',
					'default' => 'yes',
				]
			);			

			$this->add_control(
				'transition_speed',
				[
					'label' => esc_html__( 'Transition Speed', 'themesflat-addons-for-elementor' ) . ' (ms)',
					'type' => \Elementor\Controls_Manager::NUMBER,
					'default' => 3000,
				]
			);

			$this->add_control(
				'space_between',
				[
					'label' => esc_html__( 'Space Between', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'min' => 0,
					'max' => 100,
					'step' => 1,
					'default' => 30,
				]
			);

			$this->add_control(
				'slides_show',
				[
					'label' => esc_html__( 'Slides To Show', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => '6',
					'options' => [
						'1' => esc_html__( '1', 'themesflat-addons-for-elementor' ),
						'2' => esc_html__( '2', 'themesflat-addons-for-elementor' ),
						'3' => esc_html__( '3', 'themesflat-addons-for-elementor' ),
						'4' => esc_html__( '4', 'themesflat-addons-for-elementor' ),
						'5' => esc_html__( '5', 'themesflat-addons-for-elementor' ),
						'6' => esc_html__( '6', 'themesflat-addons-for-elementor' ),
						'7' => esc_html__( '7', 'themesflat-addons-for-elementor' ),
						'8' => esc_html__( '8', 'themesflat-addons-for-elementor' ),
						'9' => esc_html__( '9', 'themesflat-addons-for-elementor' ),
						'10' => esc_html__( '10', 'themesflat-addons-for-elementor' ),
					],
				]
			);

			$this->add_control(
				'slides_show_tablet',
				[
					'label' => esc_html__( 'Slides To Show Tablet', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => '3',
					'options' => [
						'1' => esc_html__( '1', 'themesflat-addons-for-elementor' ),
						'2' => esc_html__( '2', 'themesflat-addons-for-elementor' ),
						'3' => esc_html__( '3', 'themesflat-addons-for-elementor' ),
						'4' => esc_html__( '4', 'themesflat-addons-for-elementor' ),
						'5' => esc_html__( '5', 'themesflat-addons-for-elementor' ),
						'6' => esc_html__( '6', 'themesflat-addons-for-elementor' ),
						'7' => esc_html__( '7', 'themesflat-addons-for-elementor' ),
						'8' => esc_html__( '8', 'themesflat-addons-for-elementor' ),
						'9' => esc_html__( '9', 'themesflat-addons-for-elementor' ),
						'10' => esc_html__( '10', 'themesflat-addons-for-elementor' ),
					],
				]
			);

			$this->add_control(
				'slides_show_mobile',
				[
					'label' => esc_html__( 'Slides To Show Mobile', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => '2',
					'options' => [
						'1' => esc_html__( '1', 'themesflat-addons-for-elementor' ),
						'2' => esc_html__( '2', 'themesflat-addons-for-elementor' ),
						'3' => esc_html__( '3', 'themesflat-addons-for-elementor' ),
						'4' => esc_html__( '4', 'themesflat-addons-for-elementor' ),
						'5' => esc_html__( '5', 'themesflat-addons-for-elementor' ),
						'6' => esc_html__( '6', 'themesflat-addons-for-elementor' ),
						'7' => esc_html__( '7', 'themesflat-addons-for-elementor' ),
						'8' => esc_html__( '8', 'themesflat-addons-for-elementor' ),
						'9' => esc_html__( '9', 'themesflat-addons-for-elementor' ),
						'10' => esc_html__( '10', 'themesflat-addons-for-elementor' ),
					],
				]
			);

			$this->add_responsive_control( 
	        	'rotate',
				[
					'label' => esc_html__( 'Rotate', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'deg' ],
					'range' => [
						'deg' => [
							'min' => -45,
							'max' => 45,
							'step' => 1,
						]
					],
					'default' => [
						'unit' => 'deg',
						'size' => 0,
					],
					'selectors' => [
						'{{WRAPPER}} .tf-slide-swiper .swiper-container-primary' => 'transform: rotate({{SIZE}}{{UNIT}});',
					]
				]
			);

			$this->end_controls_section();
		// /.End Slides Options

		// Start Slides Navigation 
			$this->start_controls_section(
				'section_slider_navigation',
				[
					'label' => esc_html__( 'Slides Navigation', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SECTION,
				]
			);
			$this->add_control(
				'navigation',
				[
					'label' => esc_html__( 'Navigation', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'none',
					'options' => [
						'both' => esc_html__( 'Arrows and Bullets', 'themesflat-addons-for-elementor' ),
						'arrows' => esc_html__( 'Arrows', 'themesflat-addons-for-elementor' ),
						'bullets' => esc_html__( 'Bullets', 'themesflat-addons-for-elementor' ),
						'none' => esc_html__( 'None', 'themesflat-addons-for-elementor' ),
					],
				]
			);			
			$this->end_controls_section();
		// /.End Slides Navigation

		// Start Arrow        
			$this->start_controls_section( 
				'section_arrow',
	            [
	                'label' => esc_html__('Arrow', 'themesflat-addons-for-elementor'),
	                'condition' => [
						'navigation' => [ 'arrows', 'both' ],
					],
	            ]
	        );

	        $this->add_control(
				'arrows_position',
				[
					'label' => esc_html__( 'Arrows Position', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'inside',
					'options' => [
						'inside' => esc_html__( 'Inside', 'themesflat-addons-for-elementor' ),
						'outside' => esc_html__( 'Outside', 'themesflat-addons-for-elementor' ),
					],
					'prefix_class' => 'elementor-arrows-position-',
					'condition' => [
						'navigation' => [ 'arrows', 'both' ],
					],
				]
			);

	        $this->add_control(
				'prev_icon',
				[
					'label' => esc_html__( 'Prev Icon', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'default' => [
						'value' => 'fas fa-chevron-left',
						'library' => 'fa-solid',
					],
					'recommended' => [
						'fa-solid' => [
							'angle-double-left',
							'angle-left',
							'chevron-left',
							'arrow-left',
						],
						'fa-regular' => [
							'arrow-alt-circle-left',
						],
					],
				]
			);

	        $this->add_control(
				'next_icon',
				[
					'label' => esc_html__( 'Next Icon', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'default' => [
						'value' => 'fas fa-chevron-right',
						'library' => 'fa-solid',
					],
					'recommended' => [
						'fa-solid' => [
							'angle-double-right',
							'angle-right',
							'chevron-right',
							'arrow-right',
						],
						'fa-regular' => [
							'arrow-alt-circle-right',
						],
					],
				]
			);

	        $this->add_responsive_control( 
	        	'arrow_fontsize',
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
						'size' => 30,
					],
					'selectors' => [
						'{{WRAPPER}} .swiper-button-arrows' => 'font-size: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control( 
				'w_size_arrow',
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
					'selectors' => [
						'{{WRAPPER}} .swiper-button-arrows' => 'width: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control( 
				'h_size_arrow',
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
					'selectors' => [
						'{{WRAPPER}} .swiper-button-arrows' => 'height: {{SIZE}}{{UNIT}}; margin-top: calc( -{{SIZE}}{{UNIT}} / 2 )',
					],
				]
			);	

			$this->add_responsive_control( 
				'arrow_horizontal_position_prev',
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
					'selectors' => [
						'{{WRAPPER}} .swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control( 
				'arrow_horizontal_position_next',
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
					'selectors' => [
						'{{WRAPPER}} .swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control( 
				'arrow_vertical_position',
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
					'selectors' => [
						'{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-next' => 'top: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->start_controls_tabs( 'arrow_tabs' );
				$this->start_controls_tab( 
						'arrow_normal_tab',
						[
							'label' => esc_html__( 'Normal', 'themesflat-addons-for-elementor' ),						
						]
					);
					$this->add_control( 
						'arrow_color',
			            [
			                'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
			                'type' => \Elementor\Controls_Manager::COLOR,
			                'default' => '#0080f0',
			                'selectors' => [
								'{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-next' => 'color: {{VALUE}}',
							],
			            ]
			        );
			        $this->add_control( 
			        	'arrow_bg_color',
			            [
			                'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
			                'type' => \Elementor\Controls_Manager::COLOR,
			                'default' => '',
			                'selectors' => [
								'{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-next' => 'background-color: {{VALUE}};',
							],
			            ]
			        );	
			        $this->add_group_control( 
			        	\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'arrow_border',
							'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
							'selector' => '{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-next',
						]
					);
					$this->add_responsive_control( 
						'arrow_border_radius',
			            [
			                'label' => esc_html__( 'Border Radius Previous', 'themesflat-addons-for-elementor' ),
			                'type' => \Elementor\Controls_Manager::DIMENSIONS,
			                'size_units' => [ 'px', '%', 'em' ],
			                'selectors' => [
			                    '{{WRAPPER}} .tf-carousel-box .owl-nav .owl-prev, {{WRAPPER}} .tf-carousel-box .owl-nav .owl-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			                ],
			            ]
			        );
		        $this->end_controls_tab();

		        $this->start_controls_tab( 
				    	'arrow_hover_tab',
						[
							'label' => esc_html__( 'Hover', 'themesflat-addons-for-elementor' ),
						]
					);
			    	$this->add_control( 
			    		'arrow_color_hover',
			            [
			                'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
			                'type' => \Elementor\Controls_Manager::COLOR,
			                'default' => '',
			                'selectors' => [
								'{{WRAPPER}} .swiper-button-prev:hover, {{WRAPPER}} .swiper-button-next:hover' => 'color: {{VALUE}}',
							],
			            ]
			        );
			        $this->add_control( 
			        	'arrow_hover_bg_color',
			            [
			                'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
			                'type' => \Elementor\Controls_Manager::COLOR,
			                'default' => '',
			                'selectors' => [
								'{{WRAPPER}} .swiper-button-prev:hover, {{WRAPPER}} .swiper-button-next:hover' => 'background-color: {{VALUE}};',
							],
			            ]
			        );
			        $this->add_group_control( 
			        	\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'arrow_border_hover',
							'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
							'selector' => '{{WRAPPER}} .swiper-button-prev:hover, {{WRAPPER}} .swiper-button-next:hover',
						]
					);
					$this->add_responsive_control( 
						'arrow_border_radius_hover',
			            [
			                'label' => esc_html__( 'Border Radius Previous', 'themesflat-addons-for-elementor' ),
			                'type' => \Elementor\Controls_Manager::DIMENSIONS,
			                'size_units' => [ 'px', '%', 'em' ],
			                'selectors' => [
			                    '{{WRAPPER}} .swiper-button-prev:hover, {{WRAPPER}} .swiper-button-next:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			                ],
			            ]
			        );
	       		$this->end_controls_tab();
	        $this->end_controls_tabs();

	        $this->end_controls_section();
        // /.End Arrow

		// Start Bullets        
			$this->start_controls_section( 
				'section_bullets',
	            [
	                'label' => esc_html__('Bullets', 'themesflat-addons-for-elementor'),
	                'condition' => [					
	                    'navigation' => ['both','bullets'],
	                ]
	            ]
	        ); 

	        $this->add_control(
				'bullets_type',
				[
					'label' => esc_html__( 'Bullets Type', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'bullets',
					'options' => [
						'bullets' => esc_html__( 'Bullets', 'themesflat-addons-for-elementor' ),
						'fraction' => esc_html__( 'Fraction', 'themesflat-addons-for-elementor' ),
						'progressbar' => esc_html__( 'Progress Bar', 'themesflat-addons-for-elementor' ),
					],
					'condition' => [
						'navigation' => [ 'bullets', 'both' ],
					],
				]
			); 

	        $this->add_control(
				'bullets_position',
				[
					'label' => esc_html__( 'Bullets Position', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'inside',
					'options' => [
						'outside' => esc_html__( 'Outside', 'themesflat-addons-for-elementor' ),
						'inside' => esc_html__( 'Inside', 'themesflat-addons-for-elementor' ),
					],
					'prefix_class' => 'elementor-pagination-position-',
					'condition' => [
						'navigation' => [ 'bullets', 'both' ],
					],
				]
			);

			$this->add_control(
				'bullets_size',
				[
					'label' => esc_html__( 'Bullets Size', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 5,
							'max' => 50,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 10,
					],
					'selectors' => [
						'{{WRAPPER}} .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
						'{{WRAPPER}} .swiper-container-horizontal .swiper-pagination-progressbar' => 'height: {{SIZE}}{{UNIT}}',
						'{{WRAPPER}} .swiper-container-vertical .swiper-pagination-progressbar' => 'width: {{SIZE}}{{UNIT}}',
					],
					'condition' => [
						'navigation' => [ 'bullets', 'both' ],
						'bullets_type!' => 'fraction',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'bullets_typography',
					'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .swiper-pagination-fraction .swiper-pagination-total',
					'condition' => [
						'navigation' => [ 'bullets', 'both' ],
						'bullets_type' => 'fraction',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'bullets_typography_active',
					'label' => esc_html__( 'Typography Active', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .swiper-pagination-fraction',
					'condition' => [
						'navigation' => [ 'bullets', 'both' ],
						'bullets_type' => 'fraction',
					],
				]
			);   

			$this->add_responsive_control( 
				'bullets_horizontal_position',
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
							'min' => -100,
							'max' => 100,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .swiper-pagination' => 'left: {{SIZE}}{{UNIT}};display: inline-block;width: auto;transform: translateX(-50%);',
					],					
				]
			);

			$this->add_responsive_control( 
				'bullets_vertical_position',
				[
					'label' => esc_html__( 'Vertical Offset', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1000,
							'step' => 1,
						],
						'%' => [
							'min' => -100,
							'max' => 100,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .swiper-pagination' => 'bottom: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->start_controls_tabs( 'bullets_tabs' );
				$this->start_controls_tab( 
						'bullets_normal_tab',
						[
							'label' => esc_html__( 'Normal', 'themesflat-addons-for-elementor' ),						
						]
					);
					$this->add_control( 
						'bullets_color',
			            [
			                'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
			                'type' => \Elementor\Controls_Manager::COLOR,
			                'default' => '#FFFFFF99',
			                'selectors' => [
								'{{WRAPPER}} .swiper-pagination .swiper-pagination-bullet' => 'background-color: {{VALUE}};opacity: 1;',
								'{{WRAPPER}} .swiper-pagination-fraction' => 'color: {{VALUE}};',
								'{{WRAPPER}} .swiper-pagination-progressbar' => 'background-color: {{VALUE}};',
							],
			            ]
			        );
			        $this->add_group_control( 
			        	\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'bullets_border',
							'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
							'selector' => '{{WRAPPER}} .swiper-pagination .swiper-pagination-bullet',
							'condition' => [
								'navigation' => [ 'bullets', 'both' ],
								'bullets_type!' => 'fraction',
							],
						]
					);
					$this->add_responsive_control( 
						'bullets_border_radius',
			            [
			                'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
			                'type' => \Elementor\Controls_Manager::DIMENSIONS,
			                'size_units' => [ 'px', '%', 'em' ],
			                'selectors' => [
			                    '{{WRAPPER}} .swiper-pagination .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			                ],
			                'condition' => [
								'navigation' => [ 'bullets', 'both' ],
								'bullets_type!' => 'fraction',
							],
			            ]
			        );
			    $this->end_controls_tab();

		        $this->start_controls_tab( 
			        	'bullets_hover_tab',
						[
							'label' => esc_html__( 'Active', 'themesflat-addons-for-elementor' ),
						]
					);
		        	$this->add_control( 
		        		'bullets_hover_color',
			            [
			                'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
			                'type' => \Elementor\Controls_Manager::COLOR,
			                'default' => '#FFFFFF',
			                'selectors' => [
								'{{WRAPPER}} .swiper-pagination .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'background-color: {{VALUE}}',
								'{{WRAPPER}} .swiper-pagination-fraction .swiper-pagination-current' => 'color: {{VALUE}};',
								'{{WRAPPER}} .swiper-pagination-progressbar .swiper-pagination-progressbar-fill' => 'background-color: {{VALUE}};',
							],
			            ]
			        );
		        	$this->add_group_control( 
		        		\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'bullets_border_hover',
							'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
							'selector' => '{{WRAPPER}} .swiper-pagination .swiper-pagination-bullet.swiper-pagination-bullet-active',
							'condition' => [
								'navigation' => [ 'bullets', 'both' ],
								'bullets_type!' => 'fraction',
							],
						]
					);
					$this->add_responsive_control( 
						'bullets_border_radius_hover',
			            [
			                'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
			                'type' => \Elementor\Controls_Manager::DIMENSIONS,
			                'size_units' => [ 'px', '%', 'em' ],
			                'selectors' => [
			                    '{{WRAPPER}} .swiper-pagination .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			                ],
			                'condition' => [
								'navigation' => [ 'bullets', 'both' ],
								'bullets_type!' => 'fraction',
							],
			            ]
			        );
				$this->end_controls_tab();
		    $this->end_controls_tabs();	

	        $this->end_controls_section();
        // /.End Bullets

	    // Start Tab General Style
	        $this->start_controls_section( 'section_text_style',
	            [
	                'label' => esc_html__( 'General', 'themesflat-addons-for-elementor' ),
	                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	            ]
	        );
	        $this->add_responsive_control( 'wrap_img_width',
				[
					'label' => esc_html__( 'Image Wrap Width', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1000,
							'step' => 1,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
							'step' => 1,
						]
					],
					'default' => [
						'unit' => '%',
						'size' => 100,
					],
					'selectors' => [
						'{{WRAPPER}} .swiper-container-primary .swiper-slide .wrap-image' => 'width: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control( 'wrap_img_heigth',
				[
					'label' => esc_html__( 'Image Wrap Height', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1000,
							'step' => 1,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
							'step' => 1,
						]
					],
					'default' => [
						'unit' => '%',
						'size' => 100,
					],
					'selectors' => [
						'{{WRAPPER}} .swiper-container-primary .swiper-slide .wrap-image' => 'height: {{SIZE}}{{UNIT}};',
					],
				]
			);
	        $this->add_responsive_control( 'img_border_radius',
				[
					'label' => esc_html__( 'Image Border Radius', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
							'step' => 1,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
							'step' => 1,
						]
					],
					'default' => [
						'unit' => 'px',
						'size' => 0,
					],
					'selectors' => [
						'{{WRAPPER}} .swiper-container-primary .swiper-slide .wrap-image, {{WRAPPER}} .swiper-container-primary .swiper-slide img' => 'border-radius: {{SIZE}}{{UNIT}};',
					],
				]
			);
        	$this->end_controls_section();
        // /. End Tab General Style 
		
	}

	protected function render($instance = []) {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['gallery'] ) ) {
			return;
		}

		$pause_on_interaction = 'no';
		if ($settings['pause_on_interaction'] == 'yes') {
			$pause_on_interaction = 'yes';
		}

		$autoplay = 'no';
		if ($settings['autoplay'] == 'yes') {
			$autoplay = 'yes';
		}

		$infinite_loop = 'no';
		if ($settings['infinite'] == 'yes') {
			$infinite_loop = 'yes';
		}

		$reverse_direction = 'no';
		if ($settings['reverse_direction'] == 'yes') {
			$reverse_direction = 'yes';
		}

		$this->add_render_attribute( 'tf_slide_swiper', [ 'id' => "tf-slide-swiper-{$this->get_id()}", 'class' => [ 'tf-slide-swiper' ], 'data-tabid' => $this->get_id(), 'data-transition_speed' => $settings['transition_speed'], 'data-autoplay' => $autoplay, 'data-pause_on_interaction' => $pause_on_interaction, 'data-autoplay_speed' => $settings['autoplay_speed'], 'data-infinite_loop' => $infinite_loop, 'data-bullets_type' => $settings['bullets_type'], 'data-direction' => $settings['direction'], 'data-space_between' => $settings['space_between'], 'data-slides_show' => $settings['slides_show'], 'data-slides_show_tablet' => $settings['slides_show_tablet'], 'data-slides_show_mobile' => $settings['slides_show_mobile'], 'data-reverse_direction' => $reverse_direction ] );				

		$slides = [];
		$slide_count = 0;

		//var_dump($settings['gallery']);

		foreach ( $settings['gallery'] as $index => $attachment ) {
			$slide_html = '';
			$image_url = \Elementor\Group_Control_Image_Size::get_attachment_image_src( $attachment['id'], 'thumbnail', $settings );

			if ( ! $image_url && isset( $attachment['url'] ) ) {
				$image_url = $attachment['url'];
			}

			$slide_html .= '<div class="wrap-image"><img class="swiper-slide-image" src="' . esc_attr( $image_url ) . '" alt="' . esc_attr( \Elementor\Control_Media::get_image_alt( $attachment ) ) . '" /></div>';

			$slides[] = '<div class="swiper-slide">' . $slide_html . '</div>';
			$slide_count++;
		}

		$show_bullets = ( in_array( $settings['navigation'], [ 'bullets', 'both' ] ) );
		$show_arrows = ( in_array( $settings['navigation'], [ 'arrows', 'both' ] ) );

		$slides_count = count( $settings['gallery'] );

		?>
		<div <?php echo $this->get_render_attribute_string('tf_slide_swiper'); ?>>
			<div class="wrap-swiper-container">
				<!-- Slider container -->
				<div class="swiper-container swiper-container-primary">
					<!-- Swiper wrapper -->
					<div class="swiper-wrapper">
						<!-- Slides -->
						<?php echo implode( '', $slides ); ?>
					</div>

					<?php if ( 1 < $slides_count ) : ?>
							<!-- Pagination -->
						<?php if ( $show_bullets ) : ?>
							<div class="swiper-pagination"></div>
						<?php endif; ?>
						
						<?php if ( $show_arrows ) : ?>
							<!-- Navigation buttons -->
							<div class="swiper-button-prev swiper-button-arrows elementor-swiper-button-prev"><?php \Elementor\Icons_Manager::render_icon( $settings['prev_icon'], [ 'aria-hidden' => 'true' ] ); ?></div>
							<div class="swiper-button-next swiper-button-arrows elementor-swiper-button-next"><?php \Elementor\Icons_Manager::render_icon( $settings['next_icon'], [ 'aria-hidden' => 'true' ] ); ?></div>
						<?php endif; ?>
					<?php endif; ?>
				</div>
			</div>
        </div>

		<?php	
		
	}
}