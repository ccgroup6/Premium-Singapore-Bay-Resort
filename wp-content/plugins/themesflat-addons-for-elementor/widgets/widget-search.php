<?php
class TFSearch_Widget_Free extends \Elementor\Widget_Base {

	public function get_name() {
        return 'tf-search';
    }
    
    public function get_title() {
        return esc_html__( 'TF Search', 'themesflat-addons-for-elementor' );
    }

    public function get_icon() {
        return 'eicon-site-search';
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
        // Start Menu Setting        
			$this->start_controls_section( 
				'section_logo_setting',
	            [
	                'label' => esc_html__('Logo Setting', 'themesflat-addons-for-elementor'),
	            ]
	        );	
			
			$this->add_control(
				'icon_search',
				[			        
			        'label' => esc_html__('Icon Search', 'themesflat-addons-for-elementor'),
			        'type' => \Elementor\Controls_Manager::ICONS,
			        'default' => [
			            'value' => 'fas fa-search',
			            'library' => 'fa-solid',
			        ],
			    ]
			);

			$this->add_control(
				'form_style',
				[
					'label' => esc_html__( 'Style', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'style-default',
					'options' => [
						'style-default'  => esc_html__( 'Default', 'themesflat-addons-for-elementor' ),
						'style-poppup' => esc_html__( 'Poppup', 'themesflat-addons-for-elementor' ),
					],
				]
			);			

			$this->end_controls_section();
        // /.End Menu Setting		

		// Start Button Search Style 
	        $this->start_controls_section( 
	        	'section_style_button_search',
	            [
	                'label' => esc_html__( 'Button Search', 'themesflat-addons-for-elementor' ),
	                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	            ]
	        );

	        $this->add_control(
				'button_search_position',
				[
					'label' => esc_html__( 'Alignment', 'themesflat-addons-for-elementor' ),
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
					],
					'default' => 'tf-alignment-left',
				]
			);

	        $this->add_responsive_control(
				'btn_search_font_size',
				[
					'label' => esc_html__( 'Font size', 'themesflat-addons-for-elementor' ),
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
							'max' => 10,
							'step' => 1,
						],
	                ],
					'default' => [
						'size' => 20,
						'unit' => 'px',
					],
					'selectors' => [
						'{{WRAPPER}} .tf-widget-search .tf-icon-search' => 'font-size: {{SIZE}}{{UNIT}};',
					],					
				]
			);

			$this->add_control(
				'btn_search_padding',
				[
					'label' => esc_html__( 'Padding', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em' ],
					'default' => [
	                    'top' => 8,
	                    'right' => 16,
	                    'bottom' => 8,
	                    'left' => 16,
	                    'unit' => 'px',
	                ],
					'selectors' => [
						'{{WRAPPER}} .tf-widget-search .tf-icon-search' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'btn_search_margin',
				[
					'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .tf-widget-search .tf-icon-search' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'btn_search_box_shadow',
					'label' => esc_html__( 'Box Shadow', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-widget-search .tf-icon-search',
				]
			);

			$this->start_controls_tabs( 'btn_search_tabs' );				

				$this->start_controls_tab( 
					'btn_search_normal_tab',
					[
						'label' => esc_html__( 'Normal', 'themesflat-addons-for-elementor' ),						
					]
					);

			        $this->add_control(
						'btn_search_background',
						[
							'label' => esc_html__( 'Background', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => 'rgba(255,255,255,0)',
							'selectors' => [
								'{{WRAPPER}} .tf-widget-search .tf-icon-search' => 'background-color: {{VALUE}} !important',
							],
						]
					);

			        $this->add_control(
						'btn_search_color',
						[
							'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '#000000',
							'selectors' => [
								'{{WRAPPER}} .tf-widget-search .tf-icon-search' => 'color: {{VALUE}} !important',
							],
						]
					);	

					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'btn_search_border',
							'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
							'selector' => '{{WRAPPER}} .tf-widget-search .tf-icon-search',
						]
					);

					$this->add_control(
						'btn_search_border_radius',
						[
							'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', 'em' ],
							'selectors' => [
								'{{WRAPPER}} .tf-widget-search .tf-icon-search' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);		
				
				$this->end_controls_tab();

				$this->start_controls_tab( 
			    	'btn_search_hover_tab',
					[
						'label' => esc_html__( 'Hover', 'themesflat-addons-for-elementor' ),
					]
					);	

					$this->add_control(
						'btn_search_background_hover',
						[
							'label' => esc_html__( 'Background', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => 'rgba(255,255,255,0)',
							'selectors' => [
								'{{WRAPPER}} .tf-widget-search .tf-icon-search:hover' => 'background-color: {{VALUE}} !important',
							],
						]
					);

					$this->add_control(
						'btn_search_color_hover',
						[
							'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '#23A455',
							'selectors' => [
								'{{WRAPPER}} .tf-widget-search .tf-icon-search:hover' => 'color: {{VALUE}} !important',
							],
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'btn_search_border_hover',
							'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
							'selector' => '{{WRAPPER}} .tf-widget-search .tf-icon-search:hover',
						]
					);				
				
				$this->end_controls_tab();

	        $this->end_controls_tabs();

	        $this->end_controls_section();
	    // /.End Button Search Style

	    // Start Form Search Style 
	        $this->start_controls_section( 
	        	'section_style_form_search',
	            [
	                'label' => esc_html__( 'Form Search', 'themesflat-addons-for-elementor' ),
	                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	            ]
	        );

	        $this->add_control(
				'width',
				[
					'label' => esc_html__( 'Width', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 200,
							'max' => 1000,
							'step' => 1,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 800,
					],
					'selectors' => [
						'{{WRAPPER}} .tf-widget-search .tf-search-form' => 'width: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .tf-widget-search .search-panel' => 'max-width: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'height',
				[
					'label' => esc_html__( 'Height', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 200,
							'step' => 1,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 70,
					],
					'selectors' => [
						'{{WRAPPER}} .tf-widget-search .search-field' => 'height: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'padding-horizontal',
				[
					'label' => esc_html__( 'Padding Horizontal', 'themesflat-addons-for-elementor' ),
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
						'{{WRAPPER}} .tf-widget-search .search-field' => 'padding-left: {{SIZE}}{{UNIT}};padding-right: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .tf-widget-search .search-submit' => 'padding-left: {{SIZE}}{{UNIT}};padding-right: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'form_search_background',
				[
					'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => 'rgba(0, 0, 0, 0.87)',
					'selectors' => [
						'{{WRAPPER}} .tf-widget-search .tf-modal-search-panel' => 'background-color: {{VALUE}}',
						'{{WRAPPER}} .tf-widget-search.style-default .search-field' => 'background-color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'form_search_color',
				[
					'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '#ffffff',
					'selectors' => [
						'{{WRAPPER}} .tf-widget-search .search-field' => 'color: {{VALUE}}',
						'{{WRAPPER}} .tf-widget-search .search-submit' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'border-form',
				[
					'label' => esc_html__( 'Border', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'default' => [
						'top' => '2',
						'right' => '2',
						'bottom' => '2',
						'left' => '2',
						'unit' => 'px',
						'isLinked' => true,
					],
					'selectors' => [
						'{{WRAPPER}} .tf-widget-search .search-field' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'form_border_color',
				[
					'label' => esc_html__( 'Border Color', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '#ffffff',
					'selectors' => [
						'{{WRAPPER}} .tf-widget-search .search-field' => 'border-color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'border-radius-form',
				[
					'label' => esc_html__( 'Border Radius', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'default' => [
						'top' => '50',
						'right' => '50',
						'bottom' => '50',
						'left' => '50',
						'unit' => 'px',
						'isLinked' => true,
					],
					'selectors' => [
						'{{WRAPPER}} .tf-widget-search .search-field' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'form_search_placeholder_color',
				[
					'label' => esc_html__( 'Placeholder Color', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '#ffffff',
					'selectors' => [
						'{{WRAPPER}} .tf-widget-search .search-field::placeholder' => 'color: {{VALUE}}',
					],
				]
			);

	        $this->end_controls_section();
	    // /.End Form Search Style
	}

	protected function render($instance = []) {
		$settings = $this->get_settings_for_display();
		$class = $icon_search = '';
		$class .= $settings['button_search_position'];
		$class .= ' '.$settings['form_style'];

		if ( $settings['icon_search']['value'] != '' ) {
			if ( !empty( $settings['icon_search']['value']['url'] ) ) {
				$icon_search = sprintf(
		           '<img class="logo_svg" src="%1$s" alt="%2$s"/>',
		             $settings['icon_search']['value']['url'],
		             $settings['icon_search']['value']['id']
		            
		         ); 
			} else {
				$icon_search = sprintf(
		             '<i class="%1$s"></i>',
		            $settings['icon_search']['value']
		        );  
			}
		}
		
		if ($settings['form_style'] == 'style-poppup') {		
			echo sprintf ( 
				'<div class="tf-widget-search %1$s">
					<button class="tf-icon-search">%2$s</button>
					<div class="tf-modal-search-panel">
						<div class="search-panel">
							<form role="search" method="get" class="tf-search-form" action="%3$s">
			                    <input type="search" class="search-field" placeholder="Search…" value="%4$s" name="s">
			                    <button type="submit" class="search-submit"><i aria-hidden="true" class="fas fa-search"></i></button>
			                </form>
						</div>
						<button class="tf-close-modal"></button>
					</div>				
				</div>',
				$class,
				$icon_search,
				esc_url(home_url( '/' )),
				get_search_query()
				
	        );
        }else {
        	echo sprintf ( 
				'<div class="tf-widget-search %1$s">
					<form role="search" method="get" class="tf-search-form" action="%3$s">
	                    <input type="search" class="search-field" placeholder="Search…" value="%4$s" name="s">
	                    <button type="submit" class="search-submit">%2$s</button>
	                </form>			
				</div>',
				$class,
				$icon_search,
				esc_url(home_url( '/' )),
				get_search_query()
				
	        );
        }
	}

	

}
