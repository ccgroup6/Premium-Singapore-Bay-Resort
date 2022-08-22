<?php
class TFImageBox_Widget_Free extends \Elementor\Widget_Base {

	public function get_name() {
        return 'tfimagebox';
    }
    
    public function get_title() {
        return esc_html__( 'TF Image Box', 'themesflat-addons-for-elementor' );
    }

    public function get_icon() {
        return 'eicon-image-box';
    }
    
    public function get_categories() {
        return [ 'themesflat_addons' ];
    }

    public function get_style_depends() {
		return ['tf-font-awesome','tf-regular','tf-style'];
	}

	protected function register_controls() {
		// Start Image        
			$this->start_controls_section( 
				'section_image',
	            [
	                'label' => esc_html__('Image', 'themesflat-addons-for-elementor'),
	            ]
	        );	

	        $this->add_control(
				'image',
				[
					'label' => esc_html__( 'Choose Image', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::MEDIA,
					'default' => [
						'url' => URL_THEMESFLAT_ADDONS_ELEMENTOR_FREE."assets/img/placeholder.jpg",
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Image_Size::get_type(),
				[
					'name' => 'thumbnail',
					'include' => [],
					'default' => 'large',
				]
			);

	    	$this->end_controls_section();
	    // /.End Image

        // Start Content        
			$this->start_controls_section( 
				'section_content',
	            [
	                'label' => esc_html__('Content', 'themesflat-addons-for-elementor'),
	            ]
	        );	

	        $this->add_control(
				'icon_name',
				[
					'label' => esc_html__( 'Icon', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::ICONS,
				]
			);         	

			$this->add_control(
				'title',
				[
					'label' => esc_html__( 'Title', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::TEXTAREA,
					'default' => esc_html__( 'FINANCIAL PROJECTIONS AND ANALYSIS', 'themesflat-addons-for-elementor' ),
				]
			); 

			$this->add_control(
				'description',
				[
					'label' => esc_html__( 'Description', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::TEXTAREA,
					'default' => 'Sed ut perspiciatis unde omnis iste natus error voluptatem accusantium doloremque laudantium, totam aperiam.',
				]
			); 
					
	        $this->end_controls_section();
        // /.End Content

	    // Start Button        
			$this->start_controls_section( 
				'section_button',
	            [
	                'label' => esc_html__('Button', 'themesflat-addons-for-elementor'),
	            ]
	        );

	        $this->add_control(
				'show_button',
				[
					'label' => esc_html__( 'Show Button', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Show', 'themesflat-addons-for-elementor' ),
					'label_off' => esc_html__( 'Hide', 'themesflat-addons-for-elementor' ),
					'return_value' => 'yes',
					'default' => 'yes',
				]
			);

			$this->add_control( 
				'button_text',
				[
					'label' => esc_html__( 'Button Text', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => esc_html__( 'Read More', 'themesflat-addons-for-elementor' ),
					'condition' => [
	                    'show_button'	=> 'yes',
	                ],
				]
			);

	        $this->add_control(
				'link',
				[
					'label' => esc_html__( 'Link', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::URL,
					'placeholder' => esc_html__( 'https://your-link.com', 'themesflat-addons-for-elementor' ),
					'condition' => [
						'show_button' => 'yes'
					]
				]
			);

	        $this->end_controls_section();
        // /.End Button	

	    // Start General Style       
			$this->start_controls_section( 
				'section_style_general',
	            [
	                'label' => esc_html__('General', 'themesflat-addons-for-elementor'),
	                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	            ]
	        );

	        $this->add_responsive_control(
				'wrap_align',
				[
					'label' => esc_html__( 'Alignment', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::CHOOSE,
					'options' => [
						'left'    => [
							'title' => esc_html__( 'Left', 'themesflat-addons-for-elementor' ),
							'icon' => 'eicon-text-align-left',
						],
						'center' => [
							'title' => esc_html__( 'Center', 'themesflat-addons-for-elementor' ),
							'icon' => 'eicon-text-align-center',
						],
						'right' => [
							'title' => esc_html__( 'Right', 'themesflat-addons-for-elementor' ),
							'icon' => 'eicon-text-align-right',
						],
						'justify' => [
							'title' => esc_html__( 'Justified', 'themesflat-addons-for-elementor' ),
							'icon' => 'eicon-text-align-justify',
						],
					],
					'default' => 'center',
					'selectors' => [
						'{{WRAPPER}} .tf-imagebox' => 'text-align: {{VALUE}}',
					],
				]
			);

	    	$this->end_controls_section();
        // /.End General Style  
        
        // Start Image Style       
			$this->start_controls_section( 
				'section_style_image',
	            [
	                'label' => esc_html__('Image', 'themesflat-addons-for-elementor'),
	                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	            ]
	        ); 

	        $this->add_responsive_control( 
				'image_width',
				[
					'label' => esc_html__( 'Image Width', 'themesflat-addons-for-elementor' ),
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
						'{{WRAPPER}} .tf-imagebox .image' => 'max-width: {{SIZE}}{{UNIT}};',
					],
				]
			); 	  

			$this->add_group_control( 
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'image_border',
					'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-imagebox .image',
				]
			);

	        $this->add_responsive_control( 
				'image_border_radius',
				[
					'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' , '%' ],
					'selectors' => [
						'{{WRAPPER}} .tf-imagebox .image, {{WRAPPER}} .tf-imagebox .image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			); 

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'image_background',
					'label' => esc_html__( 'Background', 'themesflat-addons-for-elementor' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .tf-imagebox .image',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'image_box_shadow',
					'label' => esc_html__( 'Box Shadow', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-imagebox .image',
				]
			);

			$this->add_control( 
				'image_padding',
				[
					'label' => esc_html__( 'Padding', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .tf-imagebox .image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control( 
				'image_margin',
				[
					'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .tf-imagebox .image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->start_controls_tabs( 
				'image_style_tabs' 
				);

	        	$this->start_controls_tab( 
	        		'image_style_normal_tab',
					[
						'label' => esc_html__( 'Normal', 'themesflat-addons-for-elementor' ),
					] );	
	        		
	        		$this->add_control( 
						'image_opacity',
						[
							'label' => esc_html__( 'Image Opacity', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'size_units' => [ 'px' ],
							'range' => [
								'px' => [
									'min' => 0,
									'max' => 1,
									'step' => 0.01,
								],
							],
							'default' => [
								'unit' => 'px',
								'size' => 1,
							],
							'selectors' => [
								'{{WRAPPER}} .tf-imagebox .image img' => 'opacity: {{SIZE}};',
							],
						]
					);			
					
				$this->end_controls_tab();

				$this->start_controls_tab( 
					'image_style_hover_tab',
					[
						'label' => esc_html__( 'Hover', 'themesflat-addons-for-elementor' ),
					] );

					$this->add_control( 
						'image_opacity_hover',
						[
							'label' => esc_html__( 'Image Opacity', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'size_units' => [ 'px' ],
							'range' => [
								'px' => [
									'min' => 0,
									'max' => 1,
									'step' => 0.01,
								],
							],
							'default' => [
								'unit' => 'px',
								'size' => 1,
							],
							'selectors' => [
								'{{WRAPPER}} .tf-imagebox:hover .image img' => 'opacity: {{SIZE}};',
							],
						]
					);

					$this->add_control( 
						'image_scale_hover',
						[
							'label' => esc_html__( 'Image Scale', 'themesflat-addons-for-elementor' ),
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
								'size' => 1,1,
							],
							'selectors' => [
								'{{WRAPPER}} .tf-imagebox:hover .image img' => 'transform: scale({{SIZE}});',
							],
						]
					);	
										
				$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_control(
				'image_overlay',
				[
					'label' => esc_html__( 'Overlay', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_control(
				'show_image_overlay',
				[
					'label' => esc_html__( 'Show Overlay', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Show', 'themesflat-addons-for-elementor' ),
					'label_off' => esc_html__( 'Hide', 'themesflat-addons-for-elementor' ),
					'return_value' => 'yes',
					'default' => 'yes',
				]
			);

			$this->add_control(
				'image_overlay_background_color',
				[
					'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => 'rgba(0, 0, 0, 0.5)',
					'selectors' => [
						'{{WRAPPER}} .tf-imagebox .image .image-overlay' => 'background-color: {{VALUE}};',
					],
					'condition' => [
	                    'show_image_overlay'	=> 'yes',
	                ]
				]
			);

			$this->add_control(
				'image_overlay_effect',
				[
					'label' => esc_html__( 'Effect Overlay', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'fade-in',
					'options' => [
						'default' => esc_html__( 'Default', 'themesflat-addons-for-elementor' ),
						'fade-in' => esc_html__( 'Fade In', 'themesflat-addons-for-elementor' ),
						'fade-in-up' => esc_html__( 'Fade In Up', 'themesflat-addons-for-elementor' ),
						'fade-in-down' => esc_html__( 'Fade In Down', 'themesflat-addons-for-elementor' ),
						'fade-in-left' => esc_html__( 'Fade In Left', 'themesflat-addons-for-elementor' ),
						'fade-in-right' => esc_html__( 'Fade In Right', 'themesflat-addons-for-elementor' ),
					],
					'condition' => [
	                    'show_image_overlay'	=> 'yes',
	                ]
				]
			);	       

	        $this->end_controls_section();
        // /.End Image Style 

        // Start Content Style        
			$this->start_controls_section( 
				'section_style_content',
	            [
	                'label' => esc_html__('Content', 'themesflat-addons-for-elementor'),
	                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	            ]
	        ); 

	        $this->add_control(
				'content_style',
				[
					'label' => esc_html__( 'Style', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'style-1',
					'options' => [
						'style-1' => esc_html__( 'Default', 'themesflat-addons-for-elementor' ),
						'style-2' => esc_html__( 'Content Absolute (Full)', 'themesflat-addons-for-elementor' ),
						'style-3' => esc_html__( 'Content Absolute (Only Title)', 'themesflat-addons-for-elementor' )
					],
				]
			);   

			$this->add_control(
				'content_effect',
				[
					'label' => esc_html__( 'Effect', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'fade-in',
					'options' => [
						'fade-in' => esc_html__( 'Fade In', 'themesflat-addons-for-elementor' ),
						'fade-in-up' => esc_html__( 'Fade In Up', 'themesflat-addons-for-elementor' ),
						'fade-in-down' => esc_html__( 'Fade In Down', 'themesflat-addons-for-elementor' ),
						'fade-in-left' => esc_html__( 'Fade In Left', 'themesflat-addons-for-elementor' ),
						'fade-in-right' => esc_html__( 'Fade In Right', 'themesflat-addons-for-elementor' ),
					],
					'condition' => [
	                    'content_style'	=> 'style-2',
	                ]
				]
			);	

	        $this->add_responsive_control( 
				'content_padding',
				[
					'label' => esc_html__( 'Padding', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em' ],
					'default' => [
						'top' => '20',
						'right' => '20',
						'bottom' => '20',
						'left' => '20',
						'unit' => 'px',
					],
					'selectors' => [
						'{{WRAPPER}} .tf-imagebox .content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);		

			$this->add_responsive_control( 
				'content_margin',
				[
					'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .tf-imagebox .content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			); 

			$this->add_group_control(
				\Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'content_box_shadow',
					'label' => esc_html__( 'Box Shadow', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-imagebox .content',
				]
			);

			$this->add_control( 
				'content_border_radius',
				[
					'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} .tf-imagebox .content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
					],
				]
			);

			$this->add_control( 
				'content_background_color',
				[
					'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .tf-imagebox .content' => 'background-color: {{VALUE}}',
					],
				]
			); 

			$this->add_control( 
				'content_background_color_hover',
				[
					'label' => esc_html__( 'Background Color Hover', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .tf-imagebox .content:hover' => 'background-color: {{VALUE}}',
					],
				]
			); 

			$this->add_control( 
				'heading_title_show',
				[
					'label' => esc_html__( 'Title Show', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => [
						'content_style' => 'style-3'
					]
				]
			);

			$this->add_control( 
				'title_show_color',
				[
					'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '#ffffff',
					'selectors' => [
						'{{WRAPPER}} .tf-imagebox .content-only .title a' => 'color: {{VALUE}}',
					],
					'condition' => [
						'content_style' => 'style-3'
					]
				]
			); 

			$this->add_control( 
				'title_show_background_color',
				[
					'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '#23A455',
					'selectors' => [
						'{{WRAPPER}} .tf-imagebox .content-only' => 'background-color: {{VALUE}}',
					],
					'condition' => [
						'content_style' => 'style-3'
					]
				]
			); 

			$this->add_responsive_control( 
				'title_padding_show',
				[
					'label' => esc_html__( 'Padding', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em' ],
					'default' => [
						'top' => '15',
						'right' => '20',
						'bottom' => '15',
						'left' => '20',
						'unit' => 'px',
						'isLinked' => 'false',
					],
					'selectors' => [
						'{{WRAPPER}} .tf-imagebox .content-only .title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' => [
						'content_style' => 'style-3'
					]
				]
			);

			$this->add_responsive_control( 
				'title_spacer_show',
				[
					'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em' ],
					'default' => [
						'top' => '0',
						'right' => '0',
						'bottom' => '0',
						'left' => '0',
						'unit' => 'px',
						'isLinked' => 'false',
					],
					'selectors' => [
						'{{WRAPPER}} .tf-imagebox .content-only .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' => [
						'content_style' => 'style-3'
					]
				]
			);	

			$this->add_control( 
				'heading_title_show_icon',
				[
					'label' => esc_html__( 'Icon Title Show', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'condition' => [
						'content_style' => 'style-3'
					]
				]
			);

			$this->add_control( 
				'title_show_icon_color',
				[
					'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '#ffffff',
					'selectors' => [
						'{{WRAPPER}} .tf-imagebox .content-only .wrap-icon' => 'color: {{VALUE}}',
					],
					'condition' => [
						'content_style' => 'style-3'
					]
				]
			); 

			$this->add_control( 
				'title_show_icon_background_color',
				[
					'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '#d83030',
					'selectors' => [
						'{{WRAPPER}} .tf-imagebox .content-only .wrap-icon' => 'background-color: {{VALUE}}',
					],
					'condition' => [
						'content_style' => 'style-3'
					]
				]
			); 

			$this->add_responsive_control(
				'title_show_icon_size',
				[
					'label' => esc_html__( 'Icon Size', 'themesflat-addons-for-elementor' ),
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
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 70,
					],
					'selectors' => [
						'{{WRAPPER}} .tf-imagebox .content-only .wrap-icon i' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}',
						'{{WRAPPER}} .tf-imagebox .content-only .wrap-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .tf-imagebox .content-only .title' => 'max-width: calc(100% - {{SIZE}}{{UNIT}});',							
					],
					'condition' => [
						'content_style' => 'style-3',
						'icon_name[value]!' => '',
					]
				]
			);

			$this->add_responsive_control(
				'title_show_icon_font_size',
				[
					'label' => esc_html__( 'Icon Font Size', 'themesflat-addons-for-elementor' ),
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
						'size' => 25,
					],
					'selectors' => [
						'{{WRAPPER}} .tf-imagebox .content-only .wrap-icon' => 'font-size: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .tf-imagebox .content-only .wrap-icon img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
						'content_style' => 'style-3'
					]
				]
			);

			$this->add_control( 
				'heading_icon',
				[
					'label' => esc_html__( 'Icon', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);	

			$this->add_control( 
				'icon_color',
				[
					'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '#000000',
					'selectors' => [
						'{{WRAPPER}} .tf-imagebox .content .wrap-icon' => 'color: {{VALUE}}',
					],
				]
			);	

			$this->add_responsive_control(
				'icon_font_size',
				[
					'label' => esc_html__( 'Icon Font Size', 'themesflat-addons-for-elementor' ),
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
						'size' => 30,
					],
					'selectors' => [
						'{{WRAPPER}} .tf-imagebox .content .wrap-icon' => 'font-size: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .tf-imagebox .content .wrap-icon img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					],
				]
			); 

			$this->add_responsive_control( 
				'icon_spacer',
				[
					'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em' ],
					'default' => [
						'top' => '0',
						'right' => '0',
						'bottom' => '20',
						'left' => '0',
						'unit' => 'px',
						'isLinked' => 'false',
					],
					'selectors' => [
						'{{WRAPPER}} .tf-imagebox .content .wrap-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control( 
				'heading_title',
				[
					'label' => esc_html__( 'Title', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_control(
				'wrap_heading',
				[
					'label' => esc_html__( 'Wrap Heading', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'h4',
					'options' => [
						'h1'  => esc_html__( 'H1', 'themesflat-addons-for-elementor' ),
						'h2'  => esc_html__( 'H2', 'themesflat-addons-for-elementor' ),
						'h3'  => esc_html__( 'H3', 'themesflat-addons-for-elementor' ),
						'h4'  => esc_html__( 'H4', 'themesflat-addons-for-elementor' ),
						'h5'  => esc_html__( 'H5', 'themesflat-addons-for-elementor' ),
						'h6'  => esc_html__( 'H6', 'themesflat-addons-for-elementor' ),
					],
				]
			);

	        $this->add_group_control( 
	        	\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'title_typography',
					'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-imagebox .title a',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Text_Shadow::get_type(),
				[
					'name' => 'title_text_shadow',
					'label' => esc_html__( 'Text Shadow', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-imagebox .title a',
				]
			);

			$this->add_control( 
				'title_color',
				[
					'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '#000000',
					'selectors' => [
						'{{WRAPPER}} .tf-imagebox .title a' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_control( 
				'title_color_hover',
				[
					'label' => esc_html__( 'Color Hover', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '#23A455',
					'selectors' => [
						'{{WRAPPER}} .tf-imagebox .title a:hover' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_responsive_control( 
				'title_spacer',
				[
					'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em' ],
					'default' => [
						'top' => '0',
						'right' => '0',
						'bottom' => '10',
						'left' => '0',
						'unit' => 'px',
						'isLinked' => 'false',
					],
					'selectors' => [
						'{{WRAPPER}} .tf-imagebox .content .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control( 
				'heading_description',
				[
					'label' => esc_html__( 'Description', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_group_control( 
	        	\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'description_typography',
					'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-imagebox .description',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Text_Shadow::get_type(),
				[
					'name' => 'description_text_shadow',
					'label' => esc_html__( 'Text Shadow', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-imagebox .description',
				]
			);

			$this->add_control( 
				'description_color',
				[
					'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '#000000',
					'selectors' => [
						'{{WRAPPER}} .tf-imagebox .description' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_responsive_control( 
				'description_spacer',
				[
					'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .tf-imagebox .description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

	    	$this->end_controls_section();
        // /.End Content Style 

	    // Start Button Style 
		    $this->start_controls_section( 
		    	'section_style_button',
	            [
	                'label' => esc_html__( 'Button', 'themesflat-addons-for-elementor' ),
	                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	            ]
	        );

	        $this->add_control(
				'button_align',
				[
					'label' => esc_html__( 'Alignment', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::CHOOSE,
					'options' => [
						'left'    => [
							'title' => esc_html__( 'Left', 'themesflat-addons-for-elementor' ),
							'icon' => 'eicon-text-align-left',
						],
						'center' => [
							'title' => esc_html__( 'Center', 'themesflat-addons-for-elementor' ),
							'icon' => 'eicon-text-align-center',
						],
						'right' => [
							'title' => esc_html__( 'Right', 'themesflat-addons-for-elementor' ),
							'icon' => 'eicon-text-align-right',
						],
						'justify' => [
							'title' => esc_html__( 'Justified', 'themesflat-addons-for-elementor' ),
							'icon' => 'eicon-text-align-justify',
						],
					],
				]
			);

	        $this->add_group_control( 
	        	\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'button_typography',
					'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-imagebox .tf-button',
				]
			);

			$this->add_responsive_control( 
				'button_padding',
				[
					'label' => esc_html__( 'Padding', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em' ],
					'default' => [
						'top' => '15',
						'right' => '30',
						'bottom' => '15',
						'left' => '30',
						'unit' => 'px',
					],
					'selectors' => [
						'{{WRAPPER}} .tf-imagebox .tf-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);		

			$this->add_responsive_control( 
				'button_margin',
				[
					'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em' ],
					'default' => [
						'top' => '20',
						'right' => '0',
						'bottom' => '0',
						'left' => '0',
						'unit' => 'px',
					],
					'selectors' => [
						'{{WRAPPER}} .tf-imagebox .tf-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->start_controls_tabs( 
				'button_style_tabs' 
				);

	        	$this->start_controls_tab( 
	        		'button_style_normal_tab',
					[
						'label' => esc_html__( 'Normal', 'themesflat-addons-for-elementor' ),
					] );	
	        		$this->add_control( 
						'button_color',
						[
							'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '#ffffff',
							'selectors' => [
								'{{WRAPPER}} .tf-imagebox .tf-button' => 'color: {{VALUE}}',
								'{{WRAPPER}} .tf-imagebox .tf-button i' => 'color: {{VALUE}}',
								'{{WRAPPER}} .tf-imagebox .tf-button svg' => 'fill: {{VALUE}}',
							],
						]
					);

					$this->add_control( 
						'button_bg_color',
						[
							'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '#23A455',
							'selectors' => [
								'{{WRAPPER}} .tf-imagebox .tf-button' => 'background-color: {{VALUE}}',
							],
						]
					);

					$this->add_group_control( 
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'button_border',
							'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
							'selector' => '{{WRAPPER}} .tf-imagebox .tf-button',
						]
					);

					$this->add_control( 
						'button_border_radius',
						[
							'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', 'em', '%' ],
							'selectors' => [
								'{{WRAPPER}} .tf-imagebox .tf-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);				
					
				$this->end_controls_tab();

				$this->start_controls_tab( 
					'button_style_hover_tab',
					[
						'label' => esc_html__( 'Hover', 'themesflat-addons-for-elementor' ),
					] );

					$this->add_control( 
						'button_color_hover',
						[
							'label' => esc_html__( 'Color Hover', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '#ffffff',
							'selectors' => [
								'{{WRAPPER}} .tf-imagebox .tf-button:hover' => 'color: {{VALUE}}',
								'{{WRAPPER}} .tf-imagebox .tf-button:hover i' => 'color: {{VALUE}}',
								'{{WRAPPER}} .tf-imagebox .tf-button:hover svg' => 'fill: {{VALUE}}',
							],
						]
					);

					$this->add_control( 
						'button_bg_color_hover',
						[
							'label' => esc_html__( 'Background Color Hover', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '#000000',
							'selectors' => [
								'{{WRAPPER}} .tf-imagebox .hover-default.tf-button:hover, {{WRAPPER}} .tf-imagebox .btn-overlay:after' => 'background-color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'button_animation_options',
						[
							'label' => esc_html__( 'Effect Type', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::SELECT,
							'default' => 'default',
							'options' => [
								'default' => esc_html__( 'Default', 'themesflat-addons-for-elementor' ),
								'button' => esc_html__( 'Elementor Button Effect', 'themesflat-addons-for-elementor' ),
								'button-overlay' => esc_html__( 'TF Effect', 'themesflat-addons-for-elementor' ),
							]
						]
					);

					$this->add_control(
						'button_animation_overlay',
						[
							'label' => esc_html__( 'Style', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::SELECT,
							'default' => 'from-top',
							'options' => [								
								'from-top' => esc_html__( 'From Top', 'themesflat-addons-for-elementor' ),
								'from-bottom' => esc_html__( 'From Bottom', 'themesflat-addons-for-elementor' ),
								'from-left' => esc_html__( 'From Left', 'themesflat-addons-for-elementor' ),
								'from-right' => esc_html__( 'From Right', 'themesflat-addons-for-elementor' ),
								'from-center' => esc_html__( 'From Center', 'themesflat-addons-for-elementor' ),
								'skew' => esc_html__( 'Skew', 'themesflat-addons-for-elementor' ),								
							],
							'condition'=> [
								'button_animation_options' => 'button-overlay',
							],
						]
					);	

					$this->add_control(
						'button_animation',
						[
							'label' => esc_html__( 'Hover Animation', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::SELECT,
							'default' => 'elementor-animation-push',
							'options' => [
								'elementor-animation-grow' => esc_html__( 'Grow', 'themesflat-addons-for-elementor' ),
								'elementor-animation-shrink' => esc_html__( 'Shrink', 'themesflat-addons-for-elementor' ),
								'elementor-animation-pulse' => esc_html__( 'Pulse', 'themesflat-addons-for-elementor' ),
								'elementor-animation-pulse-grow' => esc_html__( 'Pulse Grow', 'themesflat-addons-for-elementor' ),
								'elementor-animation-pulse-shrink' => esc_html__( 'Pulse Shrink', 'themesflat-addons-for-elementor' ),
								'elementor-animation-push' => esc_html__( 'Push', 'themesflat-addons-for-elementor' ),
								'elementor-animation-pop' => esc_html__( 'Pop', 'themesflat-addons-for-elementor' ),
								'elementor-animation-bob' => esc_html__( 'Bob', 'themesflat-addons-for-elementor' ),
								'elementor-animation-hang' => esc_html__( 'Hang', 'themesflat-addons-for-elementor' ),
								'elementor-animation-skew' => esc_html__( 'Skew', 'themesflat-addons-for-elementor' ),
								'elementor-animation-wobble-vertical' => esc_html__( 'Wobble Vertical', 'themesflat-addons-for-elementor' ),
								'elementor-animation-wobble-horizontal' => esc_html__( 'Wobble Horizontal', 'themesflat-addons-for-elementor' ),

							],
							'condition'=> [
								'button_animation_options' => 'button',
							],
						]
					);				

					$this->add_group_control( 
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'button_border_hover',
							'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
							'selector' => '{{WRAPPER}} .tf-imagebox .tf-button:hover',
						]
					);

					$this->add_control( 
						'button_border_radius_hover',
						[
							'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', 'em', '%' ],
							'selectors' => [
								'{{WRAPPER}} .tf-imagebox .tf-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);
					
				$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_control( 
				'heading_button_icon',
				[
					'label' => esc_html__( 'Icon', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			); 

			$this->add_control( 
				'icon_button',
				[
					'label' => esc_html__( 'Icon Button', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'fa4compatibility' => 'icon_bt',
					'default' => [
						'value' => 'fas fa-angle-double-right',
						'library' => 'fa-solid',
					],				
				]
			);

			$this->add_control( 
				'button_icon_size',
				[
					'label' => esc_html__( 'Icon Size', 'themesflat-addons-for-elementor' ),
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
						'size' => 15,
					],
					'selectors' => [
						'{{WRAPPER}} .tf-imagebox .tf-button i' => 'font-size: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .tf-imagebox .tf-button svg' => 'width: {{SIZE}}{{UNIT}};',
					],
				]
			); 

			$this->add_control( 
				'button_icon_position',
				[
					'label' => esc_html__( 'Icon Position', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'bt_icon_after',
					'options' => [
						'bt_icon_before'  => esc_html__( 'Before', 'themesflat-addons-for-elementor' ),
						'bt_icon_after' => esc_html__( 'After', 'themesflat-addons-for-elementor' ),
					],
				]
			);

			$this->add_control( 
				'button_icon_spacer',
				[
					'label' => esc_html__( 'Icon Spacer', 'themesflat-addons-for-elementor' ),
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
						'size' => 10,
					],
					'selectors' => [
						'{{WRAPPER}} .tf-imagebox .tf-button.bt_icon_before i' => 'margin-right: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .tf-imagebox .tf-button.bt_icon_before svg' => 'margin-right: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .tf-imagebox .tf-button.bt_icon_after i' => 'margin-left: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .tf-imagebox .tf-button.bt_icon_after svg' => 'margin-left: {{SIZE}}{{UNIT}};',
					],
				]
			);

		    $this->end_controls_section();
	    // /.End Button Style
	}

	protected function render($instance = []) {
		$settings = $this->get_settings_for_display();
		
		$image =  \Elementor\Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'image' );

		$html_title = $html_description = $html_image_overlay = $button = $icon_button = $icon_name = $html_icon = $has_icon = $target = $nofollow = $link_url = '';

		if ( isset( $settings['icon_button']['value'] ) ) {
			if ( !empty( $settings['icon_button']['value']['url'] ) ) {
				$icon_button .= sprintf(
		           '<img class="logo_svg" src="%1$s" alt="%2$s"/>',
		             $settings['icon_button']['value']['url'],
		             $settings['icon_button']['value']['id']
		            
		         ); 
			} else {
				$icon_button .= sprintf(
		             '<i class="%1$s"></i>',
		            $settings['icon_button']['value']
		        );  
			}
		} 	

		$btn_animation = 'hover-default';
		if ($settings['button_animation_options'] == 'button') {
			$btn_animation = 'hover-default ' . $settings['button_animation'];
		}elseif ($settings['button_animation_options'] == 'button-overlay') {
			$btn_animation = 'btn-overlay ' . $settings['button_animation_overlay'];
		}		

		if ( $settings['show_button'] == 'yes' ) {
			$link_url = $settings['link']['url'];
			
			if ($settings['button_icon_position'] == 'bt_icon_after') {
				$button =  sprintf ('<div class="tf-button-container %4$s"><a class="tf-button %5$s %6$s" %7$s %8$s href="%3$s">%1$s %2$s</a></div>',$settings['button_text'] , $icon_button, $link_url, $settings['button_align'], $settings['button_icon_position'], $btn_animation , $target, $nofollow);
			}else{
				$button =  sprintf ('<div class="tf-button-container %4$s"><a class="tf-button %5$s %6$s" %7$s %8$s href="%3$s">%2$s %1$s</a></div>',$settings['button_text'] , $icon_button, $link_url, $settings['button_align'], $settings['button_icon_position'], $btn_animation , $target, $nofollow);
			}

			$target = $settings['link']['is_external'] ? ' target="_blank"' : '';
			$nofollow = $settings['link']['nofollow'] ? ' rel="nofollow"' : '';
			
		}		

		if ($settings['show_image_overlay'] == 'yes') {
			$html_image_overlay = sprintf('<div class="image-overlay %1$s"></div>', $settings['image_overlay_effect']);
		}

		if ($settings['title'] != '') {
			$html_title = sprintf('<%2$s class="title"><a href="%3$s">%1$s</a></%2$s>', $settings['title'], $settings['wrap_heading'], $link_url);
		}

		if ($settings['description'] != '') {
			$html_description = sprintf('<div class="description">%1$s</div>', $settings['description']);
		}

		if ( $settings['icon_name']['value'] != '' ) {
			if ( !empty( $settings['icon_name']['value']['url'] ) ) {
				$icon_name = sprintf(
		           '<img class="logo_svg" src="%1$s" alt="%2$s"/>',
		             $settings['icon_name']['value']['url'],
		             $settings['icon_name']['value']['id']		            
		        ); 

		        $html_icon = sprintf('<div class="wrap-icon">%1$s</div>', $icon_name);
			} else {
				$icon_name = sprintf(
		             '<i class="%1$s"></i>',
		            $settings['icon_name']['value']
		        );  
		        $html_icon = sprintf('<div class="wrap-icon">%1$s</div>', $icon_name);
			}

			$has_icon = 'has-icon';
		}		

		echo sprintf ( 
			'<div class="tf-imagebox %6$s"> 
                <div class="image">%1$s %5$s</div>
                <div class="content-only %9$s"> 
                	%8$s               	              
					%2$s
				</div>
                <div class="content %7$s">	
                	%8$s                
					%2$s
	                %3$s
	                %4$s
				</div>
            </div>',
            $image,
            $html_title,
            $html_description,           
            $button,
            $html_image_overlay,
            $settings['content_style'],
            $settings['content_effect'],
            $html_icon,
            $has_icon
        );
			
	}

		

}