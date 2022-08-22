<?php
if(!class_exists('Slide_Widget_Free')){
	class Slide_Widget_Free extends \Elementor\Widget_Base {

		public function get_name() {
	        return 'vegas-slider';
	    }
	    
	    public function get_title() {
	        return esc_html__( 'TF Simple Slider', 'themesflat-addons-for-elementor' );
	    }

	    public function get_icon() {
	        return 'eicon-slider-push';
	    }
	    
	    public function get_categories() {
	        return [ 'themesflat_addons' ];
	    }

	    public function get_style_depends(){
		    return ['slide-vegas','slide-ytplayer'];
		}
	    public function get_script_depends() {
	    	return ['jquery-easing','slide-vegas','slide-ytplayer','slide-typed','tf-main'];
	  	}

		protected function register_controls() {
			/* Start Slide Setting*/
				$this->start_controls_section('section_slider_hero',
		            [
		                'label'         => esc_html__('General','themesflat-addons-for-elementor'),
		            ]
		        );
				$this->add_control( 'vegas_slideshow_style',
		            [
		                'label' => esc_html__( 'Slide Style', 'themesflat-addons-for-elementor' ),
		                'type' => \Elementor\Controls_Manager::SELECT,
		                'default' => 'slidehero',
		                'options' => [
		                    'slidehero' => esc_html__( 'Image Slider', 'themesflat-addons-for-elementor' ),
		                    'slidevideo' => esc_html__( 'Video Slider', 'themesflat-addons-for-elementor' ),                    
		                ],
		            ]
		        );
		        $this->add_control(
		            'vegas_slideshow_height',
		            [
		                'label' => esc_html__( 'Height', 'themesflat-addons-for-elementor' ),
		                'type' => \Elementor\Controls_Manager::SELECT,
		                'default' => 'custom-height',
		                'options' => [
		                    'full-height' => esc_html__( 'Full Height', 'themesflat-addons-for-elementor' ),
		                    'custom-height' => esc_html__( 'Custom Height', 'themesflat-addons-for-elementor' ),
		                ],
		            ]
		        );	
		        $this->add_control(
					'custom_height',
					[
						'label' => esc_html__( 'Custom Height', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'min' => 100,
								'max' => 3000,
								'step' => 1,
							]
						],
						'default' => [
							'unit' => 'px',
							'size' => 700,
						],
						'condition' =>[
		                    'vegas_slideshow_height' => 'custom-height',
		                ],
					]
				);
				$this->add_control(
					'custom_height_tablet',
					[
						'label' => esc_html__( 'Custom Height Tablet', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'min' => 100,
								'max' => 3000,
								'step' => 1,
							]
						],
						'default' => [
							'unit' => 'px',
							'size' => 600,
						],
						'condition' =>[
		                    'vegas_slideshow_height' => 'custom-height',
		                ],
					]
				);	
				$this->add_control(
					'custom_height_mobile',
					[
						'label' => esc_html__( 'Custom Height Mobile', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'min' => 100,
								'max' => 3000,
								'step' => 1,
							]
						],
						'default' => [
							'unit' => 'px',
							'size' => 500,
						],
						'condition' =>[
		                    'vegas_slideshow_height' => 'custom-height',
		                ],
					]
				);        
		        $this->add_control( 'effect',
		            [
		                'label' => esc_html__( 'Effects', 'themesflat-addons-for-elementor' ),
		                'type' => \Elementor\Controls_Manager::SELECT,
		                'default' => 'slideRight',
		                'options' => [
		                    'fade' => esc_html__( 'Fade', 'themesflat-addons-for-elementor' ),
		                    'fade2' => esc_html__( 'Fade2', 'themesflat-addons-for-elementor' ),
		                    'slideLeft' => esc_html__( 'Slide Left', 'themesflat-addons-for-elementor' ),
		                    'slideLeft2' => esc_html__( 'Slide Left2', 'themesflat-addons-for-elementor' ),
		                    'slideRight' => esc_html__( 'Slide Right', 'themesflat-addons-for-elementor' ),
		                    'slideRight2' => esc_html__( 'Slide Right2', 'themesflat-addons-for-elementor' ),
		                    'slideUp' => esc_html__( 'Slide Up', 'themesflat-addons-for-elementor' ),
		                    'slideDown' => esc_html__( 'Slide Down', 'themesflat-addons-for-elementor' ),
		                    'slideDown2' => esc_html__( 'Slide Down2', 'themesflat-addons-for-elementor' ),
		                    'zoomIn' => esc_html__( 'Zoom In', 'themesflat-addons-for-elementor' ),
		                    'zoomIn2' => esc_html__( 'Zoom In2', 'themesflat-addons-for-elementor' ),
		                    'zoomOut' => esc_html__( 'Zoom Out', 'themesflat-addons-for-elementor' ),
		                    'zoomOut2' => esc_html__( 'Zoom Out2', 'themesflat-addons-for-elementor' ),
		                    'swirlLeft' => esc_html__( 'Swirl Left', 'themesflat-addons-for-elementor' ),
		                    'swirlLeft2' => esc_html__( 'Swirl Left2', 'themesflat-addons-for-elementor' ),
		                    'swirlRight' => esc_html__( 'Swirl Right', 'themesflat-addons-for-elementor' ),
		                    'swirlRight2' => esc_html__( 'Swirl Right2', 'themesflat-addons-for-elementor' ),
		                    'burn' => esc_html__( 'Burn', 'themesflat-addons-for-elementor' ),
		                    'burn2' => esc_html__( 'Burn2', 'themesflat-addons-for-elementor' ),
		                    'blur' => esc_html__( 'Blur', 'themesflat-addons-for-elementor' ),
		                    'blur2' => esc_html__( 'Blur2', 'themesflat-addons-for-elementor' ),
		                    'flash' => esc_html__( 'Flash', 'themesflat-addons-for-elementor' ),
		                    'flash2' => esc_html__( 'Flash2', 'themesflat-addons-for-elementor' )
		                ],
		            ]
		        ); 
		        $this->add_control( 'delay',
		            [
		                'label'   => esc_html__( 'Duration (ms)', 'themesflat-addons-for-elementor' ),
		                'type'    => \Elementor\Controls_Manager::TEXT,
		                'default' => '3000'
		            ]
		        );
		        $this->add_control( 'pattern_overlay',
		            [
		                'label' => esc_html__( 'Pattern Overlay', 'themesflat-addons-for-elementor' ),
		                'type' => \Elementor\Controls_Manager::SELECT,
		                'default' => 'style-1',
		                'options' => [
		                    ''          => esc_html__( 'No Parttern', 'themesflat-addons-for-elementor' ),
		                    'style-1'   => esc_html__( 'Style 1', 'themesflat-addons-for-elementor' ),
		                    'style-2'   => esc_html__( 'Style 2', 'themesflat-addons-for-elementor' ),
		                    'style-3'   => esc_html__( 'Style 3', 'themesflat-addons-for-elementor' ),
		                    'style-4'   => esc_html__( 'Style 4', 'themesflat-addons-for-elementor' ),
		                    'style-5'   => esc_html__( 'Style 5', 'themesflat-addons-for-elementor' ),
		                    'style-6'   => esc_html__( 'Style 6', 'themesflat-addons-for-elementor' ),
		                    'style-7'   => esc_html__( 'Style 7', 'themesflat-addons-for-elementor' ),
		                    'style-8'   => esc_html__( 'Style 8', 'themesflat-addons-for-elementor' ),
		                    'style-9'   => esc_html__( 'Style 9', 'themesflat-addons-for-elementor' ),
		                ],
		            ]
		        );
		        $this->add_control( 'color_overlay',
		            [
		                'label'     => esc_html__( 'Color Overlay', 'themesflat-addons-for-elementor' ),
		                'type'      => \Elementor\Controls_Manager::COLOR,
		                'default'=>'',
		            ]
		        );
		        $this->add_control( 'content_top',
		            [
		                'label'   => esc_html__( 'Content: Top Margin', 'themesflat-addons-for-elementor' ),
		                'type'    => \Elementor\Controls_Manager::TEXT,
		                'default' => '',
		                'placeholder' => esc_html__( '50', 'themesflat-addons-for-elementor' ),
		                'description' => esc_html__('Ex: 50. In case you want to set a spacing above the content area.', 'themesflat-addons-for-elementor')                    
		            ]
		        );
		        $this->add_control( 'content_into_grid',
		            [
		                'label'         => esc_html__( 'Content Area Into Grid?', 'themesflat-addons-for-elementor' ),
		                'type'          => \Elementor\Controls_Manager::SWITCHER,
		                'label_on'      => esc_html__( 'On', 'themesflat-addons-for-elementor' ),
		                'label_off'     => esc_html__( 'Off', 'themesflat-addons-for-elementor' ),
		                'return_value'  => 'yes',
		                'default'       => 'yes',
		            ]
		        );
		        $this->add_control( 'content_container',
					[
						'label' => esc_html__( 'Content Area Container Width', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'min' => 500,
								'max' => 1600,
								'step' => 1,
							],
						],
						'default' => [
							'unit' => 'px',
							'size' => 1200,
						],
						'selectors' => [
							'{{WRAPPER}} .hero-section .vegas-container' => 'max-width: {{SIZE}}{{UNIT}};',
						],
						'condition' => [
		                    'content_into_grid' => 'yes',
		                ]
					]
				);
		        $repeater = new \Elementor\Repeater();
				$repeater->add_control( 'vegas_slideshow_image',
		            [
		                'label'     => esc_html__( 'Image', 'themesflat-addons-for-elementor' ), 
		                'type'      => \Elementor\Controls_Manager::MEDIA, 
		                'dynamic' => [
		                    'active' => true,
		                ],
		                'default' => [
		                    'url' => URL_THEMESFLAT_ADDONS_ELEMENTOR_FREE."assets/img/default.jpg",
		                ], 
		            ]
		        );      
		        $this->add_control('vegas_slideshow_list',
		            [
		                'label'  => esc_html__('Image Slides','themesflat-addons-for-elementor'),
		                'type'   => \Elementor\Controls_Manager::REPEATER,
		                'fields' => $repeater->get_controls(),
		                'default' => [
		                    [ 'vegas_slideshow_text'   => 'Text', ]
		                ],
		                'condition' => [
		                    'vegas_slideshow_style' => 'slidehero',
		                ]
		            ]
		        );	
		        $this->add_control( 'video_link',
		            [
		                'label'   => esc_html__( 'Youtube link (URL)', 'themesflat-addons-for-elementor' ),
		                'type'    => \Elementor\Controls_Manager::TEXT,
		                'default' => 'https://www.youtube.com/watch?v=r0FAQ7yjOzA&t=7s',
		                'description' => esc_html__( 'Youtube link or ID. Ex: https://www.youtube.com/watch?v=r0FAQ7yjOzA&t=7s', 'themesflat-addons-for-elementor' ),
		                'condition' => [
		                    'vegas_slideshow_style' => 'slidevideo',
		                ]	                
		            ]
		        );
		        $this->end_controls_section();
	        /* End Slide Setting*/

	        /* Start Scroll Anchor Content*/
	        	$this->start_controls_section('section_slider_scroll_anchor',
		            [
		                'label'         => esc_html__('Scroll Anchor','themesflat-addons-for-elementor'),
		            ]
		        );
		        $this->add_control( 'arrow_anchor',
		            [
		                'label'         => esc_html__( 'Arrow Anchor', 'themesflat-addons-for-elementor' ),
		                'type'          => \Elementor\Controls_Manager::SWITCHER,
		                'label_on'      => esc_html__( 'On', 'themesflat-addons-for-elementor' ),
		                'label_off'     => esc_html__( 'Off', 'themesflat-addons-for-elementor' ),
		                'return_value'  => 'yes',
		                'default'       => 'no',
		            ]
		        );
		        $this->add_control( 'arrow_anchor_icon', 
		        	[
		                'label' => esc_html__( 'Icon', 'themesflat-addons-for-elementor' ),
		                'type' => \Elementor\Controls_Manager::ICONS,
		                'condition' => [
							'arrow_anchor[value]' => 'yes',
						],
		            ]
		        );
		        $this->add_control( 'arrow_anchor_svg_width',
					[
						'label' => __( 'Width Icon SVG', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', 'em' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 100,
								'step' => 1,
							],
							'em' => [
								'min' => 0,
								'max' => 100,
								'step' => 0.5,
							],
						],
						'default' => [
							'unit' => 'px',
							'size' => 20,
						],
						'selectors' => [
							'{{WRAPPER}} .scroll-target svg' => 'width: {{SIZE}}{{UNIT}};',
						],
						'condition' => [
							'arrow_anchor[value]' => 'yes',
							'arrow_anchor_icon[value]!' => '',
						],
					]
				);
				$this->add_control( 'arrow_anchor_font_size',
					[
						'label' => __( 'Size', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px'],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 1000,
								'step' => 5,
							],
						],
						'default' => [
							'unit' => 'px',
							'size' => 20,
						],
						'selectors' => [
							'{{WRAPPER}} .scroll-target' => 'font-size: {{SIZE}}{{UNIT}};',
						],
						'condition' => [
		                    'arrow_anchor[value]' => 'yes',
		                ]
					]
				);
				$this->add_control( 'arrow_anchor_color',
		            [
		                'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
		                'type' => \Elementor\Controls_Manager::COLOR,
		                'default' => '#ffffff',
		                'selectors' => [
							'{{WRAPPER}} .scroll-target, {{WRAPPER}} .scroll-target, {{WRAPPER}} .scroll-target svg' => 'color: {{VALUE}}; border-color: {{VALUE}}; fill: {{VALUE}}',
						],
						'condition' => [
		                    'arrow_anchor[value]' => 'yes',
		                ]
		            ]
		        );
		        $this->add_control( 'arrow_style',
		            [
		                'label' => esc_html__( 'Arrow Style', 'themesflat-addons-for-elementor' ),
		                'type' => \Elementor\Controls_Manager::SELECT,
		                'default' => 'default',
		                'options' => [
		                    'default'   => esc_html__( 'Default', 'themesflat-addons-for-elementor' ),
		                    'outline-circle'   => esc_html__( 'Outline Circle', 'themesflat-addons-for-elementor' ),
		                    'outline-square'   => esc_html__( 'Outline Square', 'themesflat-addons-for-elementor' ),
		                ],
		                'condition' => [
		                    'arrow_anchor[value]' => 'yes',
		                ]
		            ]
		        );
		        $this->add_control( 'arrow_anchor_effect',
		            [
		                'label'         => esc_html__( 'Effect', 'themesflat-addons-for-elementor' ),
		                'type'          => \Elementor\Controls_Manager::SWITCHER,
		                'label_on'      => esc_html__( 'On', 'themesflat-addons-for-elementor' ),
		                'label_off'     => esc_html__( 'Off', 'themesflat-addons-for-elementor' ),
		                'return_value'  => 'yes',
		                'default'       => 'no',
		                'condition' => [
		                    'arrow_anchor[value]' => 'yes',
		                ]
		            ]
		        );
		        $this->add_control( 'scroll_id',
		            [
		                'label'   => esc_html__( 'Scroll to Row (ID)', 'themesflat-addons-for-elementor' ),
		                'type'    => \Elementor\Controls_Manager::TEXT,
		                'default' => '',
		                'condition' => [
		                    'arrow_anchor[value]' => 'yes',
		                ]
		            ]
		        );
	        	$this->end_controls_section();
	         /* End Scroll Anchor Content*/

	        /* Start Slide Content*/
		        $this->start_controls_section('section_content_hero',
		            [
		                'label'         => esc_html__('Slide Content','themesflat-addons-for-elementor'),
		            ]
		        );
		        $this->add_control( 'animation_heading',
		            [
		                'label' => esc_html__( 'Animation Heading', 'themesflat-addons-for-elementor' ),
		                'type' => \Elementor\Controls_Manager::SELECT,
		                'default' => 'none',
		                'options' => [
		                	'none' => esc_html__( 'None', 'themesflat-addons-for-elementor' ),
		                    'type' => esc_html__( 'Typing', 'themesflat-addons-for-elementor' ),
		                    'scroll' => esc_html__( 'Scrolling', 'themesflat-addons-for-elementor' ),
		                ],
		            ]
		        );
		        $this->add_control('prefix_title_text',
		        	[
		                'label'   => esc_html__( 'Prefix Title', 'themesflat-addons-for-elementor' ),
		                'type'    => \Elementor\Controls_Manager::TEXT,
		                'label_block' => true,
		                'default' => '',                
		            ]
		    	);
		    	$this->add_control('suffix_title_text',
		        	[
		                'label'   => esc_html__( 'Suffix Title', 'themesflat-addons-for-elementor' ),
		                'type'    => \Elementor\Controls_Manager::TEXT,
		                'label_block' => true,
		                'default' => '',                
		            ]
		    	);
		        $repeater = new \Elementor\Repeater();
		        $repeater->add_control('vegas_title_text',
		        	[
		                'label'   => esc_html__( 'Text', 'themesflat-addons-for-elementor' ),
		                'type'    => \Elementor\Controls_Manager::TEXT,
		                'label_block' => true,
		                'default' => 'Create Any Slider You Can Imagine',                    
		            ]
		    	); 			
		        $this->add_control('vegas_content_list',
		            [
		                'label'  => esc_html__('Title','themesflat-addons-for-elementor'),
		                'type'   => \Elementor\Controls_Manager::REPEATER,
		                'fields' => $repeater->get_controls(),
		                'default' => [
		                    [ 'vegas_title_text'   => 'Create Any Slider You Can Imagine' ],
		                    [ 'vegas_title_text'   => 'Create Any Slider As Your Way' ],
		                    [ 'vegas_title_text'   => 'Customize Every Part of Your Slider' ],
		                ],	
		                'condition' => [
		                    'animation_heading' => ['type','scroll'],
		                ]                
		            ]
		        );
		        $this->add_control('vegas_title_text',
		        	[
		                'label'   => esc_html__( 'Title', 'themesflat-addons-for-elementor' ),
		                'type'    => \Elementor\Controls_Manager::TEXTAREA,
		                'default' => 'Create Any Slider You Can Imagine',  
		                'condition' => [
		                    'animation_heading' => 'none',
		                ]                  
		            ]
		    	);
		    	$this->add_control('vegas_sub_title_text',
		        	[
		                'label'   => esc_html__( 'Sub Title', 'themesflat-addons-for-elementor' ),
		                'type'    => \Elementor\Controls_Manager::TEXTAREA,
		                'default' => '',                   
		            ]
		    	);
		    	$this->add_control( 'sub_title_position',
					[
						'label' => __( 'Position Sub Title', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::CHOOSE,
						'default' => 'bottom',
						'options' => [
							'top' => [
								'title' => __( 'Before Title', 'themesflat-addons-for-elementor' ),
								'icon' => 'eicon-v-align-top',
							],
							'bottom' => [
								'title' => __( 'After Title', 'themesflat-addons-for-elementor' ),
								'icon' => 'eicon-v-align-bottom',
							],
						],
						'toggle' => false,
					]
				);
		        $this->add_control('vegas_desc_text',
		        	[
		            'label'         => esc_html__('Desciption','themesflat-addons-for-elementor'),
		            'type'        => \Elementor\Controls_Manager::TEXTAREA,
		            'default'     => 'E.Slider Add-ons for Elementor WordPress Page Builder was built for you.</br>Designers, developers, marketers, and entrepreneurs.</br>Create slider as your way – everything about slider is within reach!',
		        ]);
		        $this->end_controls_section();
	        /* End Slide Content*/

	        /* Start Button Setting */
		        $this->start_controls_section('section_button_setting_simple_slide',
		            [
		                'label'         => esc_html__('Buttons','themesflat-addons-for-elementor'),
		            ]
		        );
		        $repeater = new \Elementor\Repeater();
		        $repeater->start_controls_tabs( 'button_tabs' );
		        	$repeater->start_controls_tab( 'button_content_tab',
			            [
			                'label' => esc_html__( 'Content', 'themesflat-addons-for-elementor' ),
			            ]
			        	);
				        $repeater->add_control( 'btn_title', 
				        	[
				                'label' => esc_html__( 'Text', 'themesflat-addons-for-elementor' ),
				                'type' => \Elementor\Controls_Manager::TEXT,
				                'label_block' => true,
				                'default' => 'Buy only $10'
				            ]
				        );	        
				        $repeater->add_control( 'btn_url', 
				        	[
				                'label' => esc_html__( 'Button URL', 'themesflat-addons-for-elementor' ),
				                'type' => \Elementor\Controls_Manager::URL,
				                'default' => [
				                    'url' => '#'
				                ]
				            ]
				        );
				        $repeater->add_control( 'btn_icon', 
				        	[
				                'label' => esc_html__( 'Icon', 'themesflat-addons-for-elementor' ),
				                'type' => \Elementor\Controls_Manager::ICONS,
				            ]
				        );
				        $repeater->add_control( 'svg_width',
							[
								'label' => __( 'Width Icon SVG', 'themesflat-addons-for-elementor' ),
								'type' => \Elementor\Controls_Manager::SLIDER,
								'size_units' => [ 'px', 'em' ],
								'range' => [
									'px' => [
										'min' => 0,
										'max' => 100,
										'step' => 1,
									],
									'em' => [
										'min' => 0,
										'max' => 100,
										'step' => 0.5,
									],
								],
								'default' => [
									'unit' => 'px',
									'size' => 20,
								],
								'selectors' => [
									'{{WRAPPER}} .vegas-content a.button-one svg' => 'width: {{SIZE}}{{UNIT}};',
								],
								'condition' => [
									'btn_icon[value]!' => '',
								],
							]
						);
				        $repeater->add_control( 'icon_button_align',
							[
								'label' => esc_html__( 'Icon Position', 'themesflat-addons-for-elementor' ),
								'type' => \Elementor\Controls_Manager::SELECT,
								'default' => 'btn-icon-left',
								'options' => [
									'btn-icon-left' => esc_html__( 'Before', 'themesflat-addons-for-elementor' ),
									'btn-icon-right' => esc_html__( 'After', 'themesflat-addons-for-elementor' ),
								],
								'condition' => [
									'btn_icon[value]!' => '',
								],
							]
						);
						$repeater->add_control( 'icon_indent_left',
							[
								'label' => esc_html__( 'Icon Spacing Left', 'themesflat-addons-for-elementor' ),
								'type' => \Elementor\Controls_Manager::SLIDER,
								'range' => [
									'px' => [
										'max' => 50,
									],
								],
								'default' => [
									'size' => 5,
								],
								'selectors' => [
									'{{WRAPPER}} {{CURRENT_ITEM}} .btn-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};'
								],
								'condition' => [
									'btn_icon[value]!' => '',
									'icon_button_align[value]' => 'btn-icon-left',
								],
							]
						);
						$repeater->add_control( 'icon_indent_right',
							[
								'label' => esc_html__( 'Icon Spacing Right', 'themesflat-addons-for-elementor' ),
								'type' => \Elementor\Controls_Manager::SLIDER,
								'range' => [
									'px' => [
										'max' => 50,
									],
								],
								'default' => [
									'size' => 5,
								],
								'selectors' => [								
									'{{WRAPPER}} {{CURRENT_ITEM}} .btn-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};'
								],
								'condition' => [
									'btn_icon[value]!' => '',
									'icon_button_align[value]' => 'btn-icon-right',
								],
							]
						);
					$repeater->end_controls_tab();
					
					$repeater->start_controls_tab( 'button_style_tab',
			            [
			                'label' => esc_html__( 'Style', 'themesflat-addons-for-elementor' ),
			            ]
			        	);		        
				        $repeater->add_control( 'hr_btn_divider1',
				            [
				                'type' => \Elementor\Controls_Manager::DIVIDER,
				            ]
				        );
				        $repeater->add_group_control(
				            \Elementor\Group_Control_Typography::get_type(),
				            [
				                'name' => 'btn_typography_simple_slide',
				                'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
				                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}',
				            ]
				        );
				        $repeater->add_control( 'hr_btn_divider2',
				            [
				                'type' => \Elementor\Controls_Manager::DIVIDER,
				            ]
				        );
				        $repeater->add_responsive_control( 'btn_padding_simple_slide',
				            [
				                'label' => esc_html__( 'Padding', 'themesflat-addons-for-elementor' ),
				                'type' => \Elementor\Controls_Manager::DIMENSIONS,
				                'size_units' => [ 'px', '%', 'em' ],
				                'selectors' => [
				                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				                ],
				            ]
				        );
				        $repeater->add_responsive_control( 'btn_border_radius_simple_slide',
				            [
				                'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
				                'type' => \Elementor\Controls_Manager::DIMENSIONS,
				                'size_units' => [ 'px', '%', 'em' ],
				                'selectors' => [
				                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				                ],
				            ]
				        );
				        $repeater->add_responsive_control( 'btn_border_width_simple_slide',
				            [
				                'label' => esc_html__( 'Border Width', 'themesflat-addons-for-elementor' ),
				                'type' => \Elementor\Controls_Manager::DIMENSIONS,
				                'size_units' => [ 'px', '%', 'em' ],
				                'default' => [
												'top' => 2,
												'right' => 2,
												'bottom' => 2,
												'left' => 2,
												'unit' => 'px',
												'isLinked' => true,
											],
				                'selectors' => [
				                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				                ],
				            ]
				        );
				        $repeater->add_responsive_control( 'btn_margin',
							[
								'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
								'type' => \Elementor\Controls_Manager::DIMENSIONS,
								'size_units' => [ 'px', 'em', '%' ],
								'selectors' => [
									'{{WRAPPER}} {{CURRENT_ITEM}}' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
								],
								'separator' => 'before',
							]
						);
			        $repeater->end_controls_tab();

			        $repeater->start_controls_tab( 'style_normal_btn',
			            [
			                'label' => esc_html__( 'Normal', 'themesflat-addons-for-elementor' ),
			            ]
			        	);
			        	$repeater->add_control( 'font_color', 
			        		[
				                'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
				                'type' => \Elementor\Controls_Manager::COLOR,
				                'default' => '#ffffff',
				                'selectors' => [
				                    '{{WRAPPER}} {{CURRENT_ITEM}}, {{WRAPPER}} {{CURRENT_ITEM}} svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
				                ],

				            ]
				        );
				        $repeater->add_control( 'bg_color',
				            [
				                'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
				                'type' => \Elementor\Controls_Manager::COLOR,
				                'default' => '#0080f0',
				                'selectors' => [
				                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'background: {{VALUE}}',
				                ],
				            ]
				        );
				        $repeater->add_control( 'border_color',
				            [
				                'label' => esc_html__( 'Border Color', 'themesflat-addons-for-elementor' ),
				                'type' => \Elementor\Controls_Manager::COLOR,
				                'default' => '#0080f0',
				                'selectors' => [
				                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'border-color: {{VALUE}}',
				                ],
				            ]
				        );
			        $repeater->end_controls_tab();

			        $repeater->start_controls_tab( 'style_hover_btn',
			            [
			                'label' => esc_html__( 'Hover', 'themesflat-addons-for-elementor' ),
			            ]
			        	);
				        $repeater->add_control( 'hover_font_color', 
				        	[
				                'label' => esc_html__( 'Font Color', 'themesflat-addons-for-elementor' ),
				                'type' => \Elementor\Controls_Manager::COLOR,
				                'default' => '#ffffff',
				                'selectors' => array(
				                    '{{WRAPPER}} {{CURRENT_ITEM}}:hover, {{WRAPPER}} {{CURRENT_ITEM}}:hover svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
				                )
				            ]
				        );
				        $repeater->add_control( 'hover_bg_color', 
				            [
				                'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
				                'type' => \Elementor\Controls_Manager::COLOR,
				                'default' => '#222222',
				                'selectors' => array(
				                    '{{WRAPPER}} {{CURRENT_ITEM}}:hover' => 'background: {{VALUE}}',
				                )
				            ]
				        );
				        $repeater->add_control( 'hover_border_color', 
				        	[
				                'label' => esc_html__( 'Border Color', 'themesflat-addons-for-elementor' ),
				                'type' => \Elementor\Controls_Manager::COLOR,
				                'default' => '#222222',
				                'selectors' => array(
				                    '{{WRAPPER}} {{CURRENT_ITEM}}:hover' => 'border-color: {{VALUE}}',
				                )
				            ]
				        );
			        $repeater->end_controls_tab();
				$repeater->end_controls_tabs();
		        $this->add_control('create_buttons',
		            [
		                'label'  => esc_html__('Create buttons','themesflat-addons-for-elementor'),
		                'type'   => \Elementor\Controls_Manager::REPEATER,
		                'fields' => $repeater->get_controls(),
		                'title_field' => '{{{ btn_title }}}',
		            ]
		        );
		        $this->end_controls_section();
	        /* End Button Setting */ 

	        /* Start Tab Text Style */
		        $this->start_controls_section( 'section_text_style',
		            [
		                'label' => esc_html__( 'General', 'themesflat-addons-for-elementor' ),
		                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
		            ]
		        );
		        $this->add_responsive_control( 'align',
					[
						'label' => esc_html__( 'Alignment', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::CHOOSE,
						'options' => [
							'left' => [
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
							'{{WRAPPER}} .vegas-content' => 'text-align: {{VALUE}};',
						],
					]
				);
				$this->add_control( 'prefix_color',
		            [
		                'label' => esc_html__( 'Color Prefix', 'themesflat-addons-for-elementor' ),
		                'type' => \Elementor\Controls_Manager::COLOR,
		                'default' => '#ffffff',
		                'selectors' => [
							'{{WRAPPER}} .vegas-content .prefix-text' => 'color: {{VALUE}}',
						],
		            ]
		        );
				$this->add_responsive_control(
					'spacing_prefix',
					[
						'label' => esc_html__( 'Spacing Prefix', 'themesflat-addons-for-elementor' ),
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
							'size' => 5,
						],
						'selectors' => [
							'{{WRAPPER}} .vegas-content .prefix-text' => 'padding-right: {{SIZE}}{{UNIT}};',
						],
					]
				);
		        $this->add_control( 'suffix_color',
		            [
		                'label' => esc_html__( 'Color Suffix', 'themesflat-addons-for-elementor' ),
		                'type' => \Elementor\Controls_Manager::COLOR,
		                'default' => '#ffffff',
		                'selectors' => [
							'{{WRAPPER}} .vegas-content .suffix-text' => 'color: {{VALUE}}',
						],
		            ]
		        );
				$this->add_responsive_control(
					'spacing_suffix',
					[
						'label' => esc_html__( 'Spacing Suffix', 'themesflat-addons-for-elementor' ),
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
							'size' => 5,
						],
						'selectors' => [
							'{{WRAPPER}} .vegas-content .suffix-text' => 'padding-left: {{SIZE}}{{UNIT}};',
						],
					]
				);	
				$this->end_controls_section();

				$this->start_controls_section( 'section_text_style_title',
		            [
		                'label' => esc_html__( 'Title', 'themesflat-addons-for-elementor' ),
		                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
		            ]
		        );
		        $this->add_control( 'title_color',
		            [
		                'label' => esc_html__( 'Color Title', 'themesflat-addons-for-elementor' ),
		                'type' => \Elementor\Controls_Manager::COLOR,
		                'default' => '#ffffff',
		                'selectors' => [
							'{{WRAPPER}} .vegas-content .heading' => 'color: {{VALUE}}',
						],
		            ]
		        );
		        $this->add_group_control(
		            \Elementor\Group_Control_Typography::get_type(),
		            [
		                'name' => 'title_typography',
		                'label' => esc_html__( 'Typography Title', 'themesflat-addons-for-elementor' ),
		                'selector' => '{{WRAPPER}} .vegas-content .heading, {{WRAPPER}} .vegas-content .prefix-text, {{WRAPPER}} .vegas-content .suffix-text',
		            ]
		        );
				$this->add_responsive_control( 
					'margin_title',
					[
						'label' => esc_html__( 'Margin Title', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', 'em' ],
						'default' => 	[
											'top' => 0,
											'right' => 0,
											'bottom' => 20,
											'left' => 0,
											'unit' => 'px',
											'isLinked' => true,
										],
						'selectors' => [
							'{{WRAPPER}} .vegas-content .wrap-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				); 
				$this->end_controls_section();

				$this->start_controls_section( 'section_text_style_subtitle',
		            [
		                'label' => esc_html__( 'Sub Title', 'themesflat-addons-for-elementor' ),
		                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
		            ]
		        );
		        $this->add_control( 'sub_title_color',
		            [
		                'label' => esc_html__( 'Color Sub Title', 'themesflat-addons-for-elementor' ),
		                'type' => \Elementor\Controls_Manager::COLOR,
		                'default' => '#ffffff',
		                'selectors' => [
							'{{WRAPPER}} .vegas-content .sub-title' => 'color: {{VALUE}}',
						],
		            ]
		        );
		        $this->add_group_control(
		            \Elementor\Group_Control_Typography::get_type(),
		            [
		                'name' => 'sub_title_typography',
		                'label' => esc_html__( 'Typography Sub Title', 'themesflat-addons-for-elementor' ),
		                'selector' => '{{WRAPPER}} .vegas-content .sub-title',
		            ]
		        );
		        $this->add_control( 'width_sub_title',
					[
						'label' => esc_html__( 'Width Sub Title', 'themesflat-addons-for-elementor' ),
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
							'size' => 100,
						],
						'selectors' => [
							'{{WRAPPER}} .vegas-content .sub-title' => 'width: {{SIZE}}{{UNIT}};',
						],
					]
				);
				$this->add_responsive_control( 
					'margin_sub_title',
					[
						'label' => esc_html__( 'Margin Sub Title', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', 'em' ],
						'default' => 	[
											'top' => 0,
											'right' => 0,
											'bottom' => 40,
											'left' => 0,
											'unit' => 'px',
											'isLinked' => true,
										],
						'selectors' => [
							'{{WRAPPER}} .vegas-content .sub-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
		        $this->end_controls_section();

		        $this->start_controls_section( 'section_text_style_desc',
		            [
		                'label' => esc_html__( 'Desciption', 'themesflat-addons-for-elementor' ),
		                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
		            ]
		        );
		        $this->add_control( 'desc_color',
		            [
		                'label' => esc_html__( 'Color Desciption', 'themesflat-addons-for-elementor' ),
		                'type' => \Elementor\Controls_Manager::COLOR,
		                'default' => '#ffffff',
		                'selectors' => [
							'{{WRAPPER}} .vegas-content .desc' => 'color: {{VALUE}}',
						],
		            ]
		        );
		        $this->add_group_control(
		            \Elementor\Group_Control_Typography::get_type(),
		            [
		                'name' => 'desc_typography',
		                'label' => esc_html__( 'Typography Desciption', 'themesflat-addons-for-elementor' ),
		                'selector' => '{{WRAPPER}} .vegas-content .desc',
		            ]
		        );
		        $this->add_control( 'width_desc',
					[
						'label' => esc_html__( 'Width Desciption', 'themesflat-addons-for-elementor' ),
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
							'size' => 100,
						],
						'selectors' => [
							'{{WRAPPER}} .vegas-content .desc' => 'width: {{SIZE}}{{UNIT}};',
						],
					]
				);
				$this->add_responsive_control( 
					'margin_desc',
					[
						'label' => esc_html__( 'Margin Desciption', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', 'em' ],
						'default' => 	[
											'top' => 0,
											'right' => 0,
											'bottom' => 40,
											'left' => 0,
											'unit' => 'px',
											'isLinked' => true,
										],
						'selectors' => [
							'{{WRAPPER}} .vegas-content .desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);			
		        $this->end_controls_section();
	        /* End Tab Text Style */

		}

		protected function render() {
			$settings = $this->get_settings_for_display();
			$count = $img_str = $content_html = $content_html_inner = $arrow_html = $content_top = $color_overlay = $class_slideshow = $heading_html = $sub_title_html = $desc_html = $btn_html = $prefix = $suffix = $delay = $fancy_text_heading = $custom_height = $custom_height_tablet = $custom_height_mobile = '';		
	        $class_slideshow = 'hero-section ' . $settings['vegas_slideshow_style'] . ' ' . $settings['align'];
	        $color_overlay = $settings['color_overlay'];
	        $effect = $settings['effect'];
	        $arrow_anchor = $settings['arrow_anchor'];
	        $scroll_id = $settings['scroll_id'];
	        $arrow_style = $settings['arrow_style'];
	        if ($settings['arrow_anchor_effect'] == 'yes') {
	        	$arrow_style .= ' bounce-tf infinite-tf ';
	        }
	        
	        if ( $arrow_anchor == 'yes' ) {
	            $arrow_html .= sprintf( '<a href="#%2$s" class="vegas-arrow scroll-target %1$s">%3$s</a>', $arrow_style, esc_html( $scroll_id ), \Elementor\Addon_Elementor_Icon_manager_free::render_icon( $settings['arrow_anchor_icon'], [ 'aria-hidden' => 'true' ] ) );
	        }
	        $pattern_overlay = $settings['pattern_overlay'];

	        //if ( $settings['vegas_slideshow_height'] == 'custom-height' ) {
	        	$custom_height = $settings['custom_height']['size'];
	        	$custom_height_tablet = $settings['custom_height_tablet']['size'];
	        	$custom_height_mobile = $settings['custom_height_mobile']['size'];
	        	//$custom_height = $settings['custom_height'];
	        // }else {
	        // 	$custom_height = 'full-height';
	        // }
	        

			if ( ! empty( $settings['vegas_slideshow_list'] ) ) {
	            $imgs = $settings['vegas_slideshow_list'];
	            $count = count( $imgs );
	            $vegas_slideshow_list = $settings['vegas_slideshow_list'];
	            foreach ( $vegas_slideshow_list as $vegasslideritem ){
	                $img_str .= $vegasslideritem['vegas_slideshow_image']['url'].'|';                             
	            }
	            $img_str = substr( $img_str, 0, -1 );           	
	        }       
	        
	        if ( $settings['prefix_title_text'] != '' ) {
	        	$prefix = '<h2 class="prefix-text"> '.$settings['prefix_title_text'].' </h2>'; 
	        } 
	        if ( $settings['suffix_title_text'] != '' ) {
	        	$suffix  = '<h2 class="suffix-text"> '.$settings['suffix_title_text'].' </h2>'; 
	        }   

	        if ( $settings['animation_heading'] == 'type' ) {
	        	foreach ( $settings['vegas_content_list'] as $itemcontent ){
	                $fancy_text_heading .= $itemcontent['vegas_title_text'].',';                       
	            }
	        	$fancy_text_heading = substr( $fancy_text_heading, 0, -1 );
	        	$heading_html = '<div class="wrap-heading">'.$prefix.'<div class="slide-fancy-text typed fancy-text-heading" data-fancy="'.$fancy_text_heading.'">
							        <h2 class="heading">
							        <span class="text"></span>
							        </h2>
							    </div>'.$suffix.'</div>';
	        }elseif( $settings['animation_heading'] == 'scroll' ) {
	        	foreach ( $settings['vegas_content_list'] as $itemcontent ){
	                $fancy_text_heading .= '<h2 class="heading">'.$itemcontent['vegas_title_text'].'</h2>';                      
	            }
	        	$heading_html = '<div class="wrap-heading">'.$prefix . '<div class="slide-fancy-text scroll fancy-text-heading"> '.$fancy_text_heading.' </div>' . $suffix.'</div>';
	        }else {
	        	$heading_html = '<div class="wrap-heading">'.$prefix.' <h2 class="heading"> '.$settings['vegas_title_text'].' </h2> '.$suffix.'</div>';    
	        }   

	        if ($settings['vegas_sub_title_text'] != '') {
	        	$sub_title_html = '<h3 class="sub-title">'.$settings['vegas_sub_title_text'].'</h3>'; 
	        }	         

	        if ($settings['vegas_desc_text'] != '') {
	        	$desc_html = '<div class="desc">'.wp_kses_post($settings['vegas_desc_text']).'</div>';  
	        }	        

	        if ($settings['create_buttons']) {
				foreach ( $settings['create_buttons'] as $key => $value ) {			
					if( $key < 3 ) {
						if ($value['btn_title'] != '') {					
							if ( $value['icon_button_align'] == 'btn-icon-left' ) {
								$btn_html .= sprintf('<a href="'.$value['btn_url']['url'].'" class="button-one elementor-repeater-item-'.$value['_id'].'"><span class="btn-icon-left">%s</span> '.$value['btn_title'].'</a>', \Elementor\Addon_Elementor_Icon_manager_free::render_icon( $value['btn_icon'], [ 'aria-hidden' => 'true' ] ) );
							}else {
								$btn_html .= sprintf('<a href="'.$value['btn_url']['url'].'" class="button-one elementor-repeater-item-'.$value['_id'].'">'.$value['btn_title'].' <span class="btn-icon-right">%s</span></a>', \Elementor\Addon_Elementor_Icon_manager_free::render_icon( $value['btn_icon'], [ 'aria-hidden' => 'true' ] ) );
							}	
						}		
						
					}
				}			
			}

	        if ( $settings['content_into_grid'] == 'yes' ) {
	        	if ($settings['sub_title_position'] == 'top') {
	        		$content_html = sprintf( '<div class="vegas-container"><div class="vegas-content animation-heading-%s">%s %s %s %s</div></div>',  $settings['animation_heading'],$sub_title_html, $heading_html, $desc_html,  $btn_html );
	        	}else{
	        		$content_html = sprintf( '<div class="vegas-container"><div class="vegas-content animation-heading-%s">%s %s %s %s</div></div>',  $settings['animation_heading'],$heading_html, $sub_title_html, $desc_html,  $btn_html );
	        	}            
	        } else {
	        	if ($settings['sub_title_position'] == 'top') {
		            $content_html = sprintf( '<div class="vegas-content animation-heading-%s">%s %s %s %s</div>', $settings['animation_heading'], $sub_title_html, $heading_html, $desc_html, $btn_html );
		        }else{
		        	$content_html = sprintf( '<div class="vegas-content animation-heading-%s">%s %s %s %s</div>', $settings['animation_heading'], $heading_html, $sub_title_html, $desc_html, $btn_html );
		        }
	        }  

	        if ( $settings['delay'] != '' ) {
	        	$delay = $settings['delay'];
	        }     

	        $content_top = $settings['content_top'];  

			if ( $settings['vegas_slideshow_style'] == 'slidehero' ) {
	            echo sprintf(
	                '<div class="%10$s" data-count="%2$s" data-image="%1$s" data-effect="%5$s" data-overlay="%3$s" data-poverlay="%4$s" data-content="%8$s" data-height="%9$s" data-height_tablet="%12$s" data-height_mobile="%13$s" data-delay="%11$s" data-slide_type="%14$s">
	                    %6$s %7$s
	                </div>',
	                $img_str,
	                $count,
	                $color_overlay,
	                $pattern_overlay,
	                $effect,
	                $content_html,
	                $arrow_html,
	                $content_top,
	                $custom_height,
	                $class_slideshow,
	                $delay,
	                $custom_height_tablet,
	                $custom_height_mobile,
	                $settings['vegas_slideshow_height']
	            );
	        }

	        if ( $settings['vegas_slideshow_style'] == 'slidevideo' ) {
	        	$video_link = $settings['video_link'];
	        	$property = "{videoURL:'$video_link',containment:'.video.player', showControls:false, autoPlay:true, loop:true, mute:true, startAt:0, opacity:1, addRaster:'$pattern_overlay', quality:'default'}";

	        	echo sprintf(
	        		'<div class="%7$s video player" data-property="%1$s" data-overlay="%2$s" data-content="%5$s" data-height="%6$s" data-height_tablet="%9$s" data-height_mobile="%10$s" data-delay="%8$s" data-slide_type="%11$s">
	        			%3$s %4$s				    
				    </div>',
					$property,
					$color_overlay,
					$content_html,
					$arrow_html,
					$content_top,
					$custom_height,
					$class_slideshow,
					$delay,
					$custom_height_tablet,
	                $custom_height_mobile,
	                $settings['vegas_slideshow_height']
	        	);
	        }

		}

		

	}
}