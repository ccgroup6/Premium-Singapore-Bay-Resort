<?php
if (!class_exists('TFMiniCart_Widget_Free')) {
	class TFMiniCart_Widget_Free extends \Elementor\Widget_Base {

		public function get_name() {
	        return 'tf-mini-cart';
	    }
	    
	    public function get_title() {
	        return esc_html__( 'TF Woo Mini Cart', 'themesflat-addons-for-elementor' );
	    }

	    public function get_icon() {
	        return 'eicon-cart';
	    }

	    public function get_style_depends(){
		    return ['all-font-awesome', 'tf-woo-product-minicart'];
	  	}

	    public function get_script_depends(){
		    return ['tf-woo-product-minicart'];
		}
	    
	    public function get_categories() {
	        return [ 'themesflat_addons_wc' ];
	    }

		protected function register_controls() {
	        // Start General        
				$this->start_controls_section( 
					'section_mini_cart',
		            [
		                'label' => esc_html__('General', 'themesflat-addons-for-elementor'),
		            ]
		        );	

		        $this->add_control(
					'style',
					[
						'label' => esc_html__( 'Style', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SELECT,
						'default' => 'icon-text',
						'options' => [
							'only-icon'  => esc_html__( 'Only Icon', 'themesflat-addons-for-elementor' ),
							'icon-text' => esc_html__( 'Icon & Text', 'themesflat-addons-for-elementor' ),
						],
					]
				);

				$this->add_responsive_control(
					'align',
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
						],
						'default' => 'right',
						'selectors' => [
							'{{WRAPPER}} .tf-mini-cart' => 'text-align: {{VALUE}};text-align: -webkit-{{VALUE}};',
						],
					]
				);

				$this->add_responsive_control( 
		        	'padding',
					[
						'label' => esc_html__( 'Padding', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'selectors' => [
							'{{WRAPPER}} .tf-mini-cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);	

				$this->add_responsive_control( 
					'margin',
					[
						'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'selectors' => [
							'{{WRAPPER}} .tf-mini-cart' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);  

		        $this->end_controls_section();
	        // /.End General

		    // Start Icon  
			    $this->start_controls_section( 
						'section_style_icon',
			            [
			                'label' => esc_html__('Icon', 'themesflat-addons-for-elementor'),
			            ]
			        );

			    	$this->add_control(
						'icon',
						[
							'label' => esc_html__( 'Icon', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::ICONS,
							'default' => [
								'value' => 'fas fa-shopping-basket',
								'library' => 'solid',
							],
						]
					);

					$this->add_control(
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
								'size' => 14,
							],
							'selectors' => [
								'{{WRAPPER}} .tf-mini-cart .icon-cart' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'icon_size',
						[
							'label' => esc_html__( 'Icon Size', 'themesflat-addons-for-elementor' ),
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
								'size' => 40,
							],
							'selectors' => [
								'{{WRAPPER}} .tf-mini-cart .icon-cart' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
							],
						]
					);				

					$this->add_group_control( 
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'icon_border',
							'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
							'selector' => '{{WRAPPER}} .tf-mini-cart .icon-cart',
						]
					);    

					$this->add_responsive_control( 
						'icon_border_radius',
						[
							'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px' , '%' ],
							'selectors' => [
								'{{WRAPPER}} .tf-mini-cart .icon-cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
								'{{WRAPPER}} .tf-mini-cart .mini-cart .icon-cart:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$this->add_group_control( 
						\Elementor\Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'icon_box_shadow',
							'label' => esc_html__( 'Box Shadow', 'themesflat-addons-for-elementor' ),
							'selector' => '{{WRAPPER}} .tf-mini-cart .icon-cart',
						]
					); 

					$this->start_controls_tabs( 'icon_color_tabs' );				

						$this->start_controls_tab( 
							'icon_color_normal_tab',
							[
								'label' => esc_html__( 'Normal', 'themesflat-addons-for-elementor' ),						
							]
							);
							$this->add_control( 
								'icon_color',
								[
									'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
									'type' => \Elementor\Controls_Manager::COLOR,
									'default' => '#ffffff',
									'selectors' => [
										'{{WRAPPER}} .tf-mini-cart .icon-cart' => 'color: {{VALUE}}',
									],
								]
							);

							$this->add_control( 
								'icon_background_color',
								[
									'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
									'type' => \Elementor\Controls_Manager::COLOR,
									'default' => '#03b162',
									'selectors' => [
										'{{WRAPPER}} .tf-mini-cart .icon-cart' => 'background-color: {{VALUE}}',
										'{{WRAPPER}} .tf-mini-cart .mini-cart .icon-cart:before' => 'color: {{VALUE}}',
									],
								]
							);
						$this->end_controls_tab();

						$this->start_controls_tab( 
							'icon_color_hover_tab',
							[
								'label' => esc_html__( 'Hover', 'themesflat-addons-for-elementor' ),						
							]
							);
							$this->add_control( 
								'icon_color_hover',
								[
									'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
									'type' => \Elementor\Controls_Manager::COLOR,
									'default' => '',
									'selectors' => [
										'{{WRAPPER}} .tf-mini-cart .icon-cart:hover' => 'color: {{VALUE}}',
									],
								]
							);

							$this->add_control( 
								'icon_background_color_hover',
								[
									'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
									'type' => \Elementor\Controls_Manager::COLOR,
									'default' => '',
									'selectors' => [
										'{{WRAPPER}} .tf-mini-cart .icon-cart:hover' => 'background-color: {{VALUE}}',
										'{{WRAPPER}} .tf-mini-cart .mini-cart .icon-cart:hover:before' => 'color: {{VALUE}}',
									],
								]
							);
						$this->end_controls_tab();

					$this->end_controls_tabs();				

			   	$this->end_controls_section();
	        // /.End Icon

			// Start Count  
			    $this->start_controls_section( 
						'section_style_count',
			            [
			                'label' => esc_html__('Count', 'themesflat-addons-for-elementor'),
			            ]
			        );

			    	$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'count_typography',
							'selector' => '{{WRAPPER}} .tf-mini-cart .mini-cart .cart-contents',
						]
					);

					$this->add_control(
						'count_size',
						[
							'label' => esc_html__( 'Icon Size', 'themesflat-addons-for-elementor' ),
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
								'{{WRAPPER}} .tf-mini-cart .mini-cart .cart-contents' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'count_offset_x',
						[
							'label' => esc_html__( 'Offset Horizontal', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'size_units' => [ 'px' ],
							'range' => [
								'px' => [
									'min' => -100,
									'max' => 100,
									'step' => 1,
								],
							],
							'default' => [
								'unit' => 'px',
								'size' => -10,
							],
							'selectors' => [
								'{{WRAPPER}} .tf-mini-cart .mini-cart .cart-contents' => 'top: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'count_offset_y',
						[
							'label' => esc_html__( 'Offset Vertical', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'size_units' => [ 'px' ],
							'range' => [
								'px' => [
									'min' => -100,
									'max' => 100,
									'step' => 1,
								],
							],
							'default' => [
								'unit' => 'px',
								'size' => 0,
							],
							'selectors' => [
								'{{WRAPPER}} .tf-mini-cart .mini-cart .cart-contents' => 'right: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control( 
						'count_color',
						[
							'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '#203b48',
							'selectors' => [
								'{{WRAPPER}} .tf-mini-cart .mini-cart .cart-contents' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_control( 
						'count_background_color',
						[
							'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '#fbd83f',
							'selectors' => [
								'{{WRAPPER}} .tf-mini-cart .mini-cart .cart-contents' => 'background-color: {{VALUE}}',
							],
						]
					);

					$this->add_responsive_control( 
						'count_border_radius',
						[
							'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px' , '%' ],
							'selectors' => [
								'{{WRAPPER}} .tf-mini-cart .mini-cart .cart-contents' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

			   	$this->end_controls_section();
	        // /.End Count

			// Start Text  
			    $this->start_controls_section( 
						'section_style_text',
			            [
			                'label' => esc_html__('Text', 'themesflat-addons-for-elementor'),
			                'condition' => [
			                	'style' => 'icon-text'
			                ]
			            ]
			        );

			    	$this->add_control(
						'text_spacing',
						[
							'label' => esc_html__( 'Spacing', 'themesflat-addons-for-elementor' ),
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
								'{{WRAPPER}} .tf-mini-cart .wrap-cart-count .content-text' => 'padding-left: {{SIZE}}{{UNIT}};',
							],
						]
					);

			    	$this->add_control(
						'h_text',
						[
							'label' => esc_html__( 'Text', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::HEADING,
						]
					);

			    	$this->add_control(
						'text',
						[
							'label' => esc_html__( 'Text', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::TEXT,
							'default' => esc_html__( 'ADD TO CART', 'themesflat-addons-for-elementor' ),
						]
					);

			    	$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'text_typography',
							'selector' => '{{WRAPPER}} .tf-mini-cart .wrap-cart-count .wrap-count-content h5, {{WRAPPER}} .tf-mini-cart .wrap-cart-count .wrap-count-content h6',
						]
					);

					$this->add_control( 
						'text_color',
						[
							'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '#203b48',
							'selectors' => [
								'{{WRAPPER}} .tf-mini-cart .wrap-cart-count .wrap-count-content h5, {{WRAPPER}} .tf-mini-cart .wrap-cart-count .wrap-count-content h6' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_responsive_control( 
						'text_margin',
						[
							'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'selectors' => [
								'{{WRAPPER}} .tf-mini-cart .wrap-cart-count .wrap-count-content h5, {{WRAPPER}} .tf-mini-cart .wrap-cart-count .wrap-count-content h6' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'h_text_count',
						[
							'label' => esc_html__( 'Count', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_control(
						'text_count',
						[
							'label' => esc_html__( 'Text Count', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::TEXT,
							'default' => esc_html__( 'Item:', 'themesflat-addons-for-elementor' ),
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'text_count_typography',
							'selector' => '{{WRAPPER}} .tf-mini-cart .wrap-cart-count .wrap-count-content div',
						]
					);

					$this->add_control( 
						'text_count_color',
						[
							'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '#707d84',
							'selectors' => [
								'{{WRAPPER}} .tf-mini-cart .wrap-cart-count .wrap-count-content div' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_responsive_control( 
						'text_count_margin',
						[
							'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'selectors' => [
								'{{WRAPPER}} .tf-mini-cart .wrap-cart-count .wrap-count-content div' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

			   	$this->end_controls_section();
	        // /.End Text

			// Start Canvas  
			    $this->start_controls_section( 
						'section_style_canvas',
			            [
			                'label' => esc_html__('Canvas', 'themesflat-addons-for-elementor'),
			            ]
			        );

					$this->add_responsive_control(
						'canvas_width',
						[
							'label' => esc_html__( 'Width', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'size_units' => [ 'px' ],
							'range' => [
								'px' => [
									'min' => 0,
									'max' => 1000,
									'step' => 1,
								],
							],
							'default' => [
								'unit' => 'px',
								'size' => 480,
							],
							'selectors' => [
								'{{WRAPPER}} .tf-mini-cart .mini-cart .widget_shopping_cart' => 'width: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control( 
			        	'canvas_padding',
						[
							'label' => esc_html__( 'Padding', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'default' => [
								'top' => 30,
								'right' => 40,
								'bottom' => 20,
								'left' => 40,
								'unit' => 'px',
								'isLinked' => true,
							],
							'selectors' => [
								'{{WRAPPER}} .tf-mini-cart .mini-cart .widget_shopping_cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);	

					$this->add_control( 
						'canvas_bg_color',
						[
							'label' => esc_html__( 'Background', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .tf-mini-cart .mini-cart .widget_shopping_cart' => 'background-color: {{VALUE}}',
							],
						]
					);

					$this->add_group_control( 
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'canvas_border',
							'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
							'selector' => '{{WRAPPER}} .tf-mini-cart .mini-cart .widget_shopping_cart',
						]
					);

					$this->add_group_control( 
						\Elementor\Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'canvas_box_shadow',
							'label' => esc_html__( 'Box Shadow', 'themesflat-addons-for-elementor' ),
							'selector' => '{{WRAPPER}} .tf-mini-cart .mini-cart .widget_shopping_cart',
						]
					);

					$this->add_control(
						'h_heading_canvas',
						[
							'label' => esc_html__( 'Heading Canvas', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_control(
						'heading_canvas',
						[
							'label' => esc_html__( 'Heading', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::TEXT,
							'default' => esc_html__( 'Cart', 'themesflat-addons-for-elementor' ),
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'heading_canvas_typography',
							'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
							'selector' => '{{WRAPPER}} .tf-mini-cart #canvas-mini-cart .cart-title',
						]
					);

					$this->add_control( 
						'heading_canvas_color',
						[
							'label' => esc_html__( 'Heading Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '#000000',
							'selectors' => [
								'{{WRAPPER}} .tf-mini-cart #canvas-mini-cart .cart-title' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_responsive_control( 
			        	'heading_canvas_padding',
						[
							'label' => esc_html__( 'Padding', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'default' => [
								'top' => 0,
								'right' => 0,
								'bottom' => 20,
								'left' => 0,
								'unit' => 'px',
								'isLinked' => false,
							],
							'selectors' => [
								'{{WRAPPER}} .tf-mini-cart #canvas-mini-cart .top-mini-cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control( 
			        	'heading_canvas_margin',
						[
							'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors' => [
								'{{WRAPPER}} .tf-mini-cart .mini-cart .widget_shopping_cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);	

					$this->add_control(
						'h_canvas_btn_close',
						[
							'label' => esc_html__( 'Button Close', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_control( 
						'btn_close_color',
						[
							'label' => esc_html__( 'Button Close Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '#000000',
							'selectors' => [
								'{{WRAPPER}} .tf-mini-cart #canvas-mini-cart .top-mini-cart .cart-close' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_control( 
						'btn_close_bg_color',
						[
							'label' => esc_html__( 'Button Close background Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => 'rgba(0,0,0,0.04)',
							'selectors' => [
								'{{WRAPPER}} .tf-mini-cart #canvas-mini-cart .top-mini-cart .cart-close' => 'background-color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'h_cart_total',
						[
							'label' => esc_html__( 'Cart Total', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'cart_total_typography',
							'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
							'selector' => '{{WRAPPER}} .tf-mini-cart #canvas-mini-cart .widget_shopping_cart_content .total, {{WRAPPER}} .tf-mini-cart #canvas-mini-cart .widget_shopping_cart_content .total strong',
						]
					);

					$this->add_control( 
						'cart_total_color',
						[
							'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '#000000',
							'selectors' => [
								'{{WRAPPER}} .tf-mini-cart #canvas-mini-cart .widget_shopping_cart_content .total' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'cart_total_padding',
						[
							'label' => esc_html__( 'Padding', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors' => [
								'{{WRAPPER}} .tf-mini-cart #canvas-mini-cart .widget_shopping_cart_content .total' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'cart_total_margin',
						[
							'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors' => [
								'{{WRAPPER}} .tf-mini-cart #canvas-mini-cart .widget_shopping_cart_content .total' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'h_btn_view_cart',
						[
							'label' => esc_html__( 'Button View Cart', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'view_cart_typography',
							'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
							'selector' => '{{WRAPPER}} .tf-mini-cart #canvas-mini-cart .widget_shopping_cart_content .total, {{WRAPPER}} .tf-mini-cart .woocommerce.widget_shopping_cart .buttons a.wc-forward',
						]
					);

					$this->start_controls_tabs( 'view_cart_color_tabs' );				

						$this->start_controls_tab( 
							'view_cart_color_normal_tab',
							[
								'label' => esc_html__( 'Normal', 'themesflat-addons-for-elementor' ),						
							]
							);
							$this->add_control( 
								'view_cart_color',
								[
									'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
									'type' => \Elementor\Controls_Manager::COLOR,
									'default' => '#ffffff',
									'selectors' => [
										'{{WRAPPER}} .tf-mini-cart .woocommerce.widget_shopping_cart .buttons a.wc-forward' => 'color: {{VALUE}}',
									],
								]
							);

							$this->add_control( 
								'view_cart_background_color',
								[
									'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
									'type' => \Elementor\Controls_Manager::COLOR,
									'default' => '#03b162',
									'selectors' => [
										'{{WRAPPER}} .tf-mini-cart .woocommerce.widget_shopping_cart .buttons a.wc-forward' => 'background-color: {{VALUE}}',
									],
								]
							);

							$this->add_group_control( 
								\Elementor\Group_Control_Border::get_type(),
								[
									'name' => 'view_cart_border',
									'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
									'selector' => '{{WRAPPER}} .tf-mini-cart .woocommerce.widget_shopping_cart .buttons a.wc-forward',
								]
							);
						$this->end_controls_tab();

						$this->start_controls_tab( 
							'view_cart_color_hover_tab',
							[
								'label' => esc_html__( 'Hover', 'themesflat-addons-for-elementor' ),						
							]
							);
							$this->add_control( 
								'view_cart_color_hover',
								[
									'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
									'type' => \Elementor\Controls_Manager::COLOR,
									'default' => '',
									'selectors' => [
										'{{WRAPPER}} .tf-mini-cart .woocommerce.widget_shopping_cart .buttons a.wc-forward:hover' => 'color: {{VALUE}}',
									],
								]
							);

							$this->add_control( 
								'view_cart_background_color_hover',
								[
									'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
									'type' => \Elementor\Controls_Manager::COLOR,
									'default' => '',
									'selectors' => [
										'{{WRAPPER}} .tf-mini-cart .woocommerce.widget_shopping_cart .buttons a.wc-forward:hover' => 'background-color: {{VALUE}}',
									],
								]
							);

							$this->add_group_control( 
								\Elementor\Group_Control_Border::get_type(),
								[
									'name' => 'view_cart_border_hover',
									'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
									'selector' => '{{WRAPPER}} .tf-mini-cart .woocommerce.widget_shopping_cart .buttons a.wc-forward',
								]
							);
						$this->end_controls_tab();

					$this->end_controls_tabs();

					$this->add_control(
						'h_btn_checkout',
						[
							'label' => esc_html__( 'Button Checkout', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::HEADING,
						]
					);

					$this->start_controls_tabs( 'checkout_color_tabs' );				

						$this->start_controls_tab( 
							'checkout_color_normal_tab',
							[
								'label' => esc_html__( 'Normal', 'themesflat-addons-for-elementor' ),						
							]
							);
							$this->add_control( 
								'checkout_color',
								[
									'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
									'type' => \Elementor\Controls_Manager::COLOR,
									'default' => '#203b48',
									'selectors' => [
										'{{WRAPPER}} .tf-mini-cart .woocommerce.widget_shopping_cart .buttons a.wc-forward.checkout' => 'color: {{VALUE}}',
									],
								]
							);

							$this->add_control( 
								'checkout_background_color',
								[
									'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
									'type' => \Elementor\Controls_Manager::COLOR,
									'default' => 'rgba(255,255,255,0)',
									'selectors' => [
										'{{WRAPPER}} .tf-mini-cart .woocommerce.widget_shopping_cart .buttons a.wc-forward.checkout' => 'background-color: {{VALUE}}',
									],
								]
							);

							$this->add_group_control( 
								\Elementor\Group_Control_Border::get_type(),
								[
									'name' => 'checkout_border',
									'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
									'selector' => '{{WRAPPER}} .tf-mini-cart .woocommerce.widget_shopping_cart .buttons a.wc-forward.checkout',
								]
							);
						$this->end_controls_tab();

						$this->start_controls_tab( 
							'checkout_color_hover_tab',
							[
								'label' => esc_html__( 'Hover', 'themesflat-addons-for-elementor' ),						
							]
							);
							$this->add_control( 
								'checkout_color_hover',
								[
									'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
									'type' => \Elementor\Controls_Manager::COLOR,
									'default' => '#ffffff',
									'selectors' => [
										'{{WRAPPER}} .tf-mini-cart .woocommerce.widget_shopping_cart .buttons a.wc-forward.checkout:hover' => 'color: {{VALUE}}',
									],
								]
							);

							$this->add_control( 
								'checkout_background_color_hover',
								[
									'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
									'type' => \Elementor\Controls_Manager::COLOR,
									'default' => '#03b162',
									'selectors' => [
										'{{WRAPPER}} .tf-mini-cart .woocommerce.widget_shopping_cart .buttons a.wc-forward.checkout:hover' => 'background-color: {{VALUE}}',
									],
								]
							);

							$this->add_group_control( 
								\Elementor\Group_Control_Border::get_type(),
								[
									'name' => 'checkout_border_hover',
									'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
									'selector' => '{{WRAPPER}} .tf-mini-cart .woocommerce.widget_shopping_cart .buttons a.wc-forward.checkout',
								]
							);
						$this->end_controls_tab();

					$this->end_controls_tabs();

			   	$this->end_controls_section();
	        // /.End Canvas

			// Start Products Inner Canvas  
			    $this->start_controls_section( 
						'section_style_inner_canvas',
			            [
			                'label' => esc_html__('Products Inner Canvas', 'themesflat-addons-for-elementor'),
			            ]
			        );

			    	$this->add_responsive_control(
						'canvas_distance_between_pr',
						[
							'label' => esc_html__( 'Distance Between Products', 'themesflat-addons-for-elementor' ),
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
								'{{WRAPPER}} .tf-mini-cart #canvas-mini-cart .widget_shopping_cart_content .woocommerce-mini-cart .mini_cart_item' => 'padding-top: {{SIZE}}{{UNIT}};padding-bottom: {{SIZE}}{{UNIT}};',
							],
						]
					);

			    	$this->add_responsive_control(
						'canvas_border_between_pr',
						[
							'label' => esc_html__( 'Border Between Products', 'themesflat-addons-for-elementor' ),
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
								'{{WRAPPER}} .tf-mini-cart #canvas-mini-cart .widget_shopping_cart_content .woocommerce-mini-cart .mini_cart_item' => 'border-top-width: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control( 
						'border_color',
						[
							'label' => esc_html__( 'Border Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '#e5e5e5',
							'selectors' => [
								'{{WRAPPER}} .tf-mini-cart #canvas-mini-cart .widget_shopping_cart_content .woocommerce-mini-cart .mini_cart_item' => 'border-color: {{VALUE}}',
							],
						]
					);

			    	$this->add_control(
						'h_canvas_image_pr',
						[
							'label' => esc_html__( 'Image', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_responsive_control(
						'canvas_image_width',
						[
							'label' => esc_html__( 'Image Width', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'size_units' => [ 'px' ],
							'range' => [
								'px' => [
									'min' => 0,
									'max' => 300,
									'step' => 1,
								],
							],
							'default' => [
								'unit' => 'px',
								'size' => 80,
							],
							'selectors' => [
								'{{WRAPPER}} .tf-mini-cart #canvas-mini-cart .widget_shopping_cart_content .woocommerce-mini-cart .mini_cart_item img' => 'width: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'canvas_image_margin_right',
						[
							'label' => esc_html__( 'Image Margin Right', 'themesflat-addons-for-elementor' ),
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
								'{{WRAPPER}} .tf-mini-cart #canvas-mini-cart .widget_shopping_cart_content .woocommerce-mini-cart .mini_cart_item img' => 'margin-right: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'h_canvas_title_pr',
						[
							'label' => esc_html__( 'Title Products', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_control( 
						'canvas_title_pr_color',
						[
							'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '#000000',
							'selectors' => [
								'{{WRAPPER}} .tf-mini-cart #canvas-mini-cart ul.cart_list li a' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_control( 
						'canvas_title_pr_color_hover',
						[
							'label' => esc_html__( 'Hover Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => 'rgba(0,0,0,0.5)',
							'selectors' => [
								'{{WRAPPER}} .tf-mini-cart #canvas-mini-cart ul.cart_list li a:hover' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'title_pr_canvas_typography',
							'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
							'selector' => '{{WRAPPER}} .tf-mini-cart #canvas-mini-cart ul.cart_list li a',
						]
					);

					$this->add_control(
						'h_canvas_quantity_pr',
						[
							'label' => esc_html__( 'Quantity Products', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_control( 
						'canvas_quantity_pr_color',
						[
							'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '#000000',
							'selectors' => [
								'{{WRAPPER}} .tf-mini-cart #canvas-mini-cart ul.cart_list li .quantity' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'quantity_pr_canvas_typography',
							'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
							'selector' => '{{WRAPPER}} .tf-mini-cart #canvas-mini-cart ul.cart_list li .quantity',
						]
					);

					$this->add_control(
						'h_canvas_remove_pr',
						[
							'label' => esc_html__( 'Button Remove Products', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_control( 
						'canvas_remove_pr_color',
						[
							'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '#959595',
							'selectors' => [
								'{{WRAPPER}} .tf-mini-cart #canvas-mini-cart ul.cart_list li a.remove_from_cart_button' => 'color: {{VALUE}} !important',
							],
						]
					);

					$this->add_control( 
						'canvas_remove_pr_color_hover',
						[
							'label' => esc_html__( 'Hover Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '#B70000',
							'selectors' => [
								'{{WRAPPER}} .tf-mini-cart #canvas-mini-cart ul.cart_list li .remove_from_cart_button:hover' => 'color: {{VALUE}} !important',
							],
						]
					);

					$this->add_responsive_control(
						'canvas_remove_pr_size',
						[
							'label' => esc_html__( 'Size', 'themesflat-addons-for-elementor' ),
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
								'{{WRAPPER}} .tf-mini-cart #canvas-mini-cart ul.cart_list li .remove_from_cart_button' => 'font-size: {{SIZE}}{{UNIT}} !important;',
							],
						]
					);

					$this->add_responsive_control(
						'canvas_remove_pr_position',
						[
							'label' => esc_html__( 'Position Vertical', 'themesflat-addons-for-elementor' ),
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
								'size' => 30,
							],
							'selectors' => [
								'{{WRAPPER}} .tf-mini-cart #canvas-mini-cart ul.cart_list li .remove_from_cart_button' => 'top: {{SIZE}}{{UNIT}}',
							],
						]
					);

			   	$this->end_controls_section();
	        // /.End Products Inner Canvas
		}

		protected function render($instance = []) {
			$settings = $this->get_settings_for_display();
			
			$count = ThemesFlat_Addon_For_Elementor_Free::themesflat_mini_cart_count();		
			?>
			<div class="tf-mini-cart">
				<div class="mini-cart">
	                <div class="cart-count">
	                    <div id="mini-cart-click" class="wrap-cart-count">
	                        <div class="wrap-count-content">
	                            <div class="inner-cart-count">
	                                <a class="icon-cart" href="<?php echo wc_get_cart_url(); ?>" title="<?php esc_html__( 'View your shopping cart', 'themesflat-addons-for-elementor' ); ?>"><?php \Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); ?></a>            
	                                <a class="cart-contents" href="<?php echo wc_get_cart_url(); ?>" title="<?php esc_html__( 'View your shopping cart', 'themesflat-addons-for-elementor' ); ?>">                
	                                    <?php
	                                    if ( $count > 0 ): ?>
	                                        <span class="cart-contents-count"><?php echo esc_attr( $count ); ?></span>
	                                    <?php else: ?>
	                                        <span class="cart-contents-count">0</span>
	                                    <?php endif; ?>
	                                </a>
	                            </div>
	                            <?php if($settings['style'] == 'icon-text'): ?>
	                            <div class="content-text">
	                                <h5><?php echo esc_attr($settings['text']); ?></h5>
	                                <div>
		                                <?php echo sprintf('( %1$s <span class="cart-contents-count">%2$s</span> )',$settings['text_count'], $count ); ?>
		                            </div>
	                            </div>
	                        	<?php endif; ?>
	                        </div>
	                    </div>
	                    <div class="overlay-mini-cart"></div>
	                    <div id="canvas-mini-cart" class="widget woocommerce widget_shopping_cart"> 
	                    	<div class="top-mini-cart">
		                    	<h4 class="cart-title"><?php echo esc_attr($settings['heading_canvas']); ?></h4> 
		                    	<span class="cart-close close-icon"><i class="fas fa-times"></i></span>   
	                    	</div>               	
	                        <div class="widget_shopping_cart_content"> 
	                            <?php if ( class_exists( 'woocommerce' ) ) { 
	                            	if ( ! empty( WC()->cart) ) { woocommerce_mini_cart(); }
	                            } ?>
	                        </div>	                    
	                    </div>
	                </div>                               
	            </div>
			</div>
			<?php
		}

			

	}
}