<?php

class TFPostNavigation_Widget_Free extends \Elementor\Widget_Base {

	public function get_name() {
        return 'tfposts-navigation';
    }
    
    public function get_title() {
        return esc_html__( 'TF Post Navigation', 'themesflat-addons-for-elementor' );
    }

    public function get_icon() {
        return 'eicon-post-navigation';
    }
    
    public function get_categories() {
        return [ 'themesflat_addons_single_post' ];
    }

    public function get_style_depends() {
		return ['tf-style'];
	}

	protected function register_controls() {
        // Start Tab Setting        
			$this->start_controls_section( 'section_tabs',
	            [
	                'label' => esc_html__('Post Navigation', 'themesflat-addons-for-elementor'),
	            ]
	        );

	        $this->add_control(
				'show_label',
				[
					'label' => esc_html__( 'Label', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Show', 'themesflat-addons-for-elementor' ),
					'label_off' => esc_html__( 'Hide', 'themesflat-addons-for-elementor' ),
					'default' => 'yes',
				]
			);

			$this->add_control(
				'prev_label',
				[
					'label' => esc_html__( 'Previous Label', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => esc_html__( 'PREVIOUS', 'themesflat-addons-for-elementor' ),
					'condition' => [
						'show_label' => 'yes',
					],
				]
			);

			$this->add_control(
				'next_label',
				[
					'label' => esc_html__( 'Next Label', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => esc_html__( 'NEXT', 'themesflat-addons-for-elementor' ),
					'condition' => [
						'show_label' => 'yes',
					],
				]
			);

			$this->add_control(
				'show_arrow',
				[
					'label' => esc_html__( 'Arrows', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Show', 'themesflat-addons-for-elementor' ),
					'label_off' => esc_html__( 'Hide', 'themesflat-addons-for-elementor' ),
					'default' => 'yes',
				]
			);

			$this->add_control(
				'arrow',
				[
					'label' => esc_html__( 'Arrows Type', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'fa fa-angle-left' => esc_html__( 'Angle', 'themesflat-addons-for-elementor' ),
						'fa fa-angle-double-left' => esc_html__( 'Double Angle', 'themesflat-addons-for-elementor' ),
						'fa fa-chevron-left' => esc_html__( 'Chevron', 'themesflat-addons-for-elementor' ),
						'fa fa-chevron-circle-left' => esc_html__( 'Chevron Circle', 'themesflat-addons-for-elementor' ),
						'fa fa-caret-left' => esc_html__( 'Caret', 'themesflat-addons-for-elementor' ),
						'fa fa-arrow-left' => esc_html__( 'Arrow', 'themesflat-addons-for-elementor' ),
						'fa fa-long-arrow-left' => esc_html__( 'Long Arrow', 'themesflat-addons-for-elementor' ),
						'fa fa-arrow-circle-left' => esc_html__( 'Arrow Circle', 'themesflat-addons-for-elementor' ),
						'fa fa-arrow-circle-o-left' => esc_html__( 'Arrow Circle Negative', 'themesflat-addons-for-elementor' ),
					],
					'default' => 'fa fa-angle-left',
					'condition' => [
						'show_arrow' => 'yes',
					],
				]
			);

			$this->add_control(
				'show_title',
				[
					'label' => esc_html__( 'Post Title', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Show', 'themesflat-addons-for-elementor' ),
					'label_off' => esc_html__( 'Hide', 'themesflat-addons-for-elementor' ),
					'default' => 'yes',
				]
			);

			$this->add_control(
				'show_separator',
				[
					'label' => esc_html__( 'Separator', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Show', 'themesflat-addons-for-elementor' ),
					'label_off' => esc_html__( 'Hide', 'themesflat-addons-for-elementor' ),
					'default' => 'yes',
				]
			);

			$post_type_options = [];
			$post_type_taxonomies = [];
			foreach ( $this->tf_get_public_post_types() as $post_type => $post_type_label ) {
				$taxonomies = $this->tf_get_taxonomies( [ 'object_type' => $post_type ], false );
				if ( empty( $taxonomies ) ) {
					continue;
				}

				$post_type_options[ $post_type ] = $post_type_label;
				$post_type_taxonomies[ $post_type ] = [];
				foreach ( $taxonomies as $taxonomy ) {
					$post_type_taxonomies[ $post_type ][ $taxonomy->name ] = $taxonomy->label;
				}
			}

			$this->add_control(
				'in_same_term',
				[
					'label' => esc_html__( 'In same Term', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'options' => $post_type_options,
					'default' => '',
					'multiple' => true,
					'label_block' => true,
					'description' => esc_html__( 'Indicates whether next post must be within the same taxonomy term as the current post, this lets you set a taxonomy per each post type', 'themesflat-addons-for-elementor' ),
				]
			);

			foreach ( $post_type_options as $post_type => $post_type_label ) {
				$this->add_control(
					$post_type . '_taxonomy',
					[
						'label' => $post_type_label . ' ' . esc_html__( 'Taxonomy', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SELECT,
						'options' => $post_type_taxonomies[ $post_type ],
						'default' => '',
						'condition' => [
							'in_same_term' => $post_type,
						],
					]
				);
			}
	        
			$this->end_controls_section();
        // /.End Tab Setting 

	 	// Start Style General       
			$this->start_controls_section( 
				'section_style_general',
	            [
	                'label' => esc_html__('General', 'themesflat-addons-for-elementor'),
	                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	            ]
	        );

	        $this->add_responsive_control( 
	        	'padding',
	            [
	                'label' => esc_html__( 'Padding', 'themesflat-addons-for-elementor' ),
	                'type' => \Elementor\Controls_Manager::DIMENSIONS,
	                'size_units' => ['px', 'em', '%'],
	                'selectors' => [
	                    '{{WRAPPER}} .tf-post-navigation .tf-wrap-post-navigation' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	                ],
	            ]
	        );

	        $this->add_responsive_control( 
	        	'margin',
	            [
	                'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
	                'type' => \Elementor\Controls_Manager::DIMENSIONS,
	                'size_units' => ['px', 'em', '%'],
	                'selectors' => [
	                    '{{WRAPPER}} .tf-post-navigation .tf-wrap-post-navigation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	                ],
	            ]
	        );

	        $this->add_group_control( 
	        	\Elementor\Group_Control_Border::get_type(),
	            [
	                'name' => 'border',
	                'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
	                'selector' => '{{WRAPPER}} .tf-post-navigation .tf-wrap-post-navigation',
	            ]
	        );
	        
	        $this->add_responsive_control( 
	        	'border_radius',
	            [
	                'label' => esc_html__('Border Radius', 'themesflat-addons-for-elementor'),
	                'type' => \Elementor\Controls_Manager::DIMENSIONS,
	                'size_units' => ['px', 'em', '%'],
	                'selectors' => [
	                    '{{WRAPPER}} .tf-post-navigation .tf-wrap-post-navigation' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	                ],
	            ]
	        );

	        $this->add_group_control( 
	        	\Elementor\Group_Control_Box_Shadow::get_type(),
	            [
	                'name' => 'shadow',	                
	                'label' => esc_html__( 'Box Shadow', 'themesflat-addons-for-elementor' ),
	                'selector' => '{{WRAPPER}} .tf-post-navigation .tf-wrap-post-navigation',	                
	            ]
	        );

	    	$this->end_controls_section();
        // /.End Style General  

	    // Start Style Label
	        $this->start_controls_section( 'section_label',
	            [
	                'label' => esc_html__( 'Label', 'themesflat-addons-for-elementor' ),
	                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	            ]
	        );

	        $this->add_group_control( 
	        	\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'label_typography',
					'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-post-navigation .tf-post-navigation-link .post-navigation-label',
				]
			);

	        $this->start_controls_tabs(  'label_style_tabs' );
	        	$this->start_controls_tab( 'label_style_normal_tab',
					[
						'label' => esc_html__( 'Normal', 'themesflat-addons-for-elementor' ),					
					] );	
	        		$this->add_control( 
						'label_color',
						[
							'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .tf-post-navigation .tf-post-navigation-link .post-navigation-label' => 'color: {{VALUE}}',					
							],
						]
					);		
				$this->end_controls_tab();

				$this->start_controls_tab( 'label_style_hover_tab',
					[
						'label' => esc_html__( 'Hover', 'themesflat-addons-for-elementor' ),
					] );
					$this->add_control( 
						'label_color_hover',
						[
							'label' => esc_html__( 'Color Hover', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .tf-post-navigation .tf-post-navigation-link .post-navigation-label:hover' => 'color: {{VALUE}}',
							],
						]
					);	
					$this->add_control(
						'label_transition_duration',
						[
							'label' => esc_html__( 'Transition Duration', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'size_units' => [ 'px' ],
							'range' => [
								'px' => [
									'min' => 0,
									'max' => 3,
									'step' => 0.1,
								],
							],
							'default' => [
								'unit' => 'px',
								'size' => 0.3,
							],
							'selectors' => [
								'{{WRAPPER}} .tf-post-navigation .tf-post-navigation-link .post-navigation-label' => 'transition-duration: {{SIZE}}s;',
							],
						]
					);						
				$this->end_controls_tab();
			$this->end_controls_tabs();        
			        
        	$this->end_controls_section();    
	    // /.End Style Label

       	// Start Style Title
	        $this->start_controls_section( 'section_title',
	            [
	                'label' => esc_html__( 'Post Title', 'themesflat-addons-for-elementor' ),
	                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	            ]
	        );

	        $this->add_group_control( 
	        	\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'title_typography',
					'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-post-navigation .tf-post-navigation-link .post-navigation-title',
				]
			);

	        $this->start_controls_tabs(  'title_style_tabs' );
	        	$this->start_controls_tab( 'title_style_normal_tab',
					[
						'label' => esc_html__( 'Normal', 'themesflat-addons-for-elementor' ),					
					] );	
	        		$this->add_control( 
						'title_color',
						[
							'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .tf-post-navigation .tf-post-navigation-link .post-navigation-title' => 'color: {{VALUE}}',					
							],
						]
					);		
				$this->end_controls_tab();

				$this->start_controls_tab( 'title_style_hover_tab',
					[
						'label' => esc_html__( 'Hover', 'themesflat-addons-for-elementor' ),
					] );
					$this->add_control( 
						'title_color_hover',
						[
							'label' => esc_html__( 'Color Hover', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .tf-post-navigation .tf-post-navigation-link .post-navigation-title:hover' => 'color: {{VALUE}}',
							],
						]
					);	
					$this->add_control(
						'title_transition_duration',
						[
							'label' => esc_html__( 'Transition Duration', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'size_units' => [ 'px' ],
							'range' => [
								'px' => [
									'min' => 0,
									'max' => 3,
									'step' => 0.1,
								],
							],
							'default' => [
								'unit' => 'px',
								'size' => 0.3,
							],
							'selectors' => [
								'{{WRAPPER}} .tf-post-navigation .tf-post-navigation-link .post-navigation-title' => 'transition-duration: {{SIZE}}s;',
							],	
						]
					);						
				$this->end_controls_tab();
			$this->end_controls_tabs();        
			        
        	$this->end_controls_section();    
	    // /.End Style Title

        // Start Style Arrows
	        $this->start_controls_section( 'section_arrows',
	            [
	                'label' => esc_html__( 'Arrows', 'themesflat-addons-for-elementor' ),
	                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	            ]
	        );

	        $this->add_responsive_control(
				'arrows_size',
				[
					'label' => esc_html__( 'Size', 'themesflat-addons-for-elementor' ),
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
						'size' => 30,
					],
					'selectors' => [
						'{{WRAPPER}} .tf-post-navigation .tf-post-navigation-link .post-navigation-arrow-wrapper' => 'font-size: {{SIZE}}{{UNIT}}',
					],
				]
			);

			$this->add_responsive_control(
				'arrows_gap',
				[
					'label' => esc_html__( 'Gap', 'themesflat-addons-for-elementor' ),
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
						'size' => 15,
					],
					'selectors' => [
						'{{WRAPPER}} .tf-post-navigation .tf-post-navigation-link .post-navigation-arrow-wrapper.post-navigation-arrow-next' => 'padding-left: {{SIZE}}{{UNIT}}',
						'{{WRAPPER}} .tf-post-navigation .tf-post-navigation-link .post-navigation-arrow-wrapper.post-navigation-arrow-prev' => 'padding-right: {{SIZE}}{{UNIT}}',
					],
				]
			);

	        $this->start_controls_tabs(  'arrows_style_tabs' );
	        	$this->start_controls_tab( 'arrows_style_normal_tab',
					[
						'label' => esc_html__( 'Normal', 'themesflat-addons-for-elementor' ),					
					] );	
	        		$this->add_control( 
						'arrows_color',
						[
							'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .tf-post-navigation .tf-post-navigation-link .post-navigation-arrow-wrapper' => 'color: {{VALUE}}',					
							],
						]
					);		
				$this->end_controls_tab();

				$this->start_controls_tab( 'arrows_style_hover_tab',
					[
						'label' => esc_html__( 'Hover', 'themesflat-addons-for-elementor' ),
					] );
					$this->add_control( 
						'arrows_color_hover',
						[
							'label' => esc_html__( 'Color Hover', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .tf-post-navigation .tf-post-navigation-link .post-navigation-arrow-wrapper:hover' => 'color: {{VALUE}}',
							],
						]
					);	
					$this->add_control(
						'arrows_transition_duration',
						[
							'label' => esc_html__( 'Transition Duration', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'size_units' => [ 'px' ],
							'range' => [
								'px' => [
									'min' => 0,
									'max' => 3,
									'step' => 0.1,
								],
							],
							'default' => [
								'unit' => 'px',
								'size' => 0.3,
							],
							'selectors' => [
								'{{WRAPPER}} .tf-post-navigation .tf-post-navigation-link .post-navigation-label' => 'transition-duration: {{SIZE}}s;',
							],
						]
					);						
				$this->end_controls_tab();
			$this->end_controls_tabs();        
			        
        	$this->end_controls_section();    
	    // /.End Style Arrows

        // Start Style Separator
	        $this->start_controls_section( 'section_separator',
	            [
	                'label' => esc_html__( 'Separator', 'themesflat-addons-for-elementor' ),
	                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	            ]
	        );

	        $this->add_control( 
				'separator_color',
				[
					'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '#d4d4d4',
					'selectors' => [
						'{{WRAPPER}} .tf-post-navigation .tf-wrap-post-navigation .tf-post-navigation-separator .navigation-separator' => 'background-color: {{VALUE}}',					
					],
				]
			);	

			$this->add_responsive_control(
				'separator_size',
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
						'size' => 1,
					],
					'selectors' => [
						'{{WRAPPER}} .tf-post-navigation .tf-wrap-post-navigation .tf-post-navigation-separator .navigation-separator' => 'width: {{SIZE}}{{UNIT}}',
					],
				]
			);        
			        
        	$this->end_controls_section();    
	    // /.End Style Separator
	}
	
	public function tf_get_public_post_types( $args = [] ) {
		$post_type_args = [
			'show_in_nav_menus' => true,
		];
		if ( ! empty( $args['post_type'] ) ) {
			$post_type_args['name'] = $args['post_type'];
			unset( $args['post_type'] );
		}
		$post_type_args = wp_parse_args( $post_type_args, $args );
		$_post_types = get_post_types( $post_type_args, 'objects' );
		$post_types = [];
		foreach ( $_post_types as $post_type => $object ) {
			$post_types[ $post_type ] = $object->label;
		}
		return $post_types;
	}

	public function tf_get_taxonomies( $args = [], $output = 'names', $operator = 'and' ) {
		global $wp_taxonomies;
		$field = ( 'names' === $output ) ? 'name' : false;
		if ( isset( $args['object_type'] ) ) {
			$object_type = (array) $args['object_type'];
			unset( $args['object_type'] );
		}
		$taxonomies = wp_filter_object_list( $wp_taxonomies, $args, $operator );
		if ( isset( $object_type ) ) {
			foreach ( $taxonomies as $tax => $tax_data ) {
				if ( ! array_intersect( $object_type, $tax_data->object_type ) ) {
					unset( $taxonomies[ $tax ] );
				}
			}
		}
		if ( $field ) {
			$taxonomies = wp_list_pluck( $taxonomies, $field );
		}
		return $taxonomies;
	}

	protected function render($instance = []) {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'tf_post_navigation_wrapper', ['id' => "tf-post-navigation-{$this->get_id()}", 'class' => ['tf-post-navigation'], 'data-tabid' => $this->get_id()] );

		$content = $prev_label = $next_label = $prev_arrow = $next_arrow = '';

		if ( $settings['show_label'] == 'yes'  ) {
			$prev_label = '<span class="post-navigation-label post-navigation-prev-label">' . $settings['prev_label'] . '</span>';
			$next_label = '<span class="post-navigation-label post-navigation-next-label">' . $settings['next_label'] . '</span>';
		}

		if ( $settings['show_arrow'] == 'yes' ) {
			if ( is_rtl() ) {
				$prev_icon_class = str_replace( 'left', 'right', $settings['arrow'] );
				$next_icon_class = $settings['arrow'];
			} else {
				$prev_icon_class = $settings['arrow'];
				$next_icon_class = str_replace( 'left', 'right', $settings['arrow'] );
			}

			$prev_arrow = '<span class="post-navigation-arrow-wrapper post-navigation-arrow-prev"><i class="' . $prev_icon_class . '" aria-hidden="true"></i></span>';
			$next_arrow = '<span class="post-navigation-arrow-wrapper post-navigation-arrow-next"><i class="' . $next_icon_class . '" aria-hidden="true"></i></span>';
		}

		$prev_title = '';
		$next_title = '';

        if ( $settings['show_title'] == 'yes' ) {
			$prev_title = '<span class="post-navigation-title post-navigation-prev-title">%title</span>';
			$next_title = '<span class="post-navigation-title post-navigation-next-title">%title</span>';
		}

        $in_same_term = false;
		$taxonomy = 'category';
		$post_type = get_post_type( get_queried_object_id() );

		if ( ! empty( $settings['in_same_term'] ) && is_array( $settings['in_same_term'] ) && in_array( $post_type, $settings['in_same_term'] ) ) {
			if ( isset( $settings[ $post_type . '_taxonomy' ] ) ) {
				$in_same_term = true;
				$taxonomy = $settings[ $post_type . '_taxonomy' ];
			}
		}
        ?>
        <div <?php echo $this->get_render_attribute_string('tf_post_navigation_wrapper'); ?>>
        	<div class="tf-wrap-post-navigation">
				<div class="tf-post-navigation-link tf-post-navigation-prev">
					<?php previous_post_link( '%link', $prev_arrow . '<span class="tf-post-navigation-link-prev">' . $prev_label . $prev_title . '</span>', $in_same_term, '', $taxonomy ); ?>
				</div>
				<?php if ( $settings['show_separator'] == 'yes' ) : ?>
					<div class="tf-post-navigation-separator">
						<div class="navigation-separator"></div>
					</div>
				<?php endif; ?>
				<div class="tf-post-navigation-link tf-post-navigation-next">
					<?php next_post_link( '%link', '<span class="tf-post-navigation-link-next">' . $next_label . $next_title . '</span>' . $next_arrow, $in_same_term, '', $taxonomy ); ?>
				</div>
			</div>
		</div>
        <?php	
		
	}

	

}