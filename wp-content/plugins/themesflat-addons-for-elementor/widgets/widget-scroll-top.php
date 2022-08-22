<?php
class TFScrollTop_Widget_Free extends \Elementor\Widget_Base {

	public function get_name() {
        return 'tf-scroll-top';
    }
    
    public function get_title() {
        return esc_html__( 'TF Scroll Top', 'themesflat-addons-for-elementor' );
    }

    public function get_icon() {
        return 'eicon-v-align-top';
    }

    public function get_style_depends(){
	    return ['tf-font-awesome', 'tf-regular', 'tf-style'];
  	}

    public function get_script_depends(){
	    return ['jquery-easing','tf-main'];
	}
    
    public function get_categories() {
        return [ 'themesflat_addons' ];
    }

	protected function register_controls() {
        // Start Settings        
			$this->start_controls_section( 
				'section_scroll_top',
	            [
	                'label' => esc_html__('Settings', 'themesflat-addons-for-elementor'),
	            ]
	        );	

	        $this->add_control(
				'types',
				[
					'label' => esc_html__( 'Types', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'only-show',
					'options' => [
						'only-show'  => esc_html__( 'Default', 'themesflat-addons-for-elementor' ),
						'scroll-and-show' => esc_html__( 'Scroll and show', 'themesflat-addons-for-elementor' ),
					],
				]
			);

			$this->add_control(
				'scroll_top_style_show',
				[
					'label' => esc_html__( 'Style Show', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'from-bottom',
					'options' => [
						'from-bottom'  => esc_html__( 'From Bottom', 'themesflat-addons-for-elementor' ),
						'from-horizontal' => esc_html__( 'From Horizontal', 'themesflat-addons-for-elementor' ),
					],
					'condition' => [
						'types' => 'scroll-and-show',
					],
				]
			);

			$this->add_control(
				'scroll_top_position',
				[
					'label' => esc_html__( 'Scroll Top Position', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'scroll-top-position-right',
					'options' => [
						'scroll-top-position-left'  => esc_html__( 'Left', 'themesflat-addons-for-elementor' ),
						'scroll-top-position-right' => esc_html__( 'Right', 'themesflat-addons-for-elementor' ),
					],
					'condition' => [
						'types' => 'scroll-and-show',
					],
				]
			);

			$this->add_responsive_control(
				'scroll_top_vertical',
				[
					'label' => esc_html__( 'Scroll Top Vertical When Show', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 500,
							'step' => 1,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 60,
					],
					'selectors' => [
						'{{WRAPPER}} #tf-scroll-top.scroll-and-show.from-bottom .inner-scroll-top' => 'bottom: -{{SIZE}}{{UNIT}};',
						'{{WRAPPER}} #tf-scroll-top.scroll-and-show.from-bottom.show .inner-scroll-top' => 'bottom: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} #tf-scroll-top.scroll-and-show.from-horizontal .inner-scroll-top' => 'bottom: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
						'types' => 'scroll-and-show',
					],
				]
			);

			$this->add_responsive_control(
				'scroll_top_horizontal',
				[
					'label' => esc_html__( 'Scroll Top Horizontal When Show', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 500,
							'step' => 1,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 15,
					],
					'selectors' => [
						'{{WRAPPER}} #tf-scroll-top.scroll-and-show.scroll-top-position-right.from-bottom .inner-scroll-top' => 'right: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} #tf-scroll-top.scroll-and-show.scroll-top-position-left.from-bottom .inner-scroll-top' => 'left: {{SIZE}}{{UNIT}};',
						
						'{{WRAPPER}} #tf-scroll-top.scroll-and-show.scroll-top-position-right.from-horizontal .inner-scroll-top' => 'right: -{{SIZE}}{{UNIT}};',
						'{{WRAPPER}} #tf-scroll-top.scroll-and-show.scroll-top-position-left.from-horizontal .inner-scroll-top' => 'left: -{{SIZE}}{{UNIT}};',

						'{{WRAPPER}} #tf-scroll-top.scroll-and-show.scroll-top-position-right.from-horizontal.show .inner-scroll-top' => 'right: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} #tf-scroll-top.scroll-and-show.scroll-top-position-left.from-horizontal.show .inner-scroll-top' => 'left: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
						'types' => 'scroll-and-show',
					],
				]
			);

			$this->add_responsive_control(
				'scroll_top_width',
				[
					'label' => esc_html__( 'Width', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 500,
							'step' => 1,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 55,
					],
					'selectors' => [
						'{{WRAPPER}} #tf-scroll-top .inner-scroll-top' => 'width: {{SIZE}}{{UNIT}};',
					],
				]
			); 

			$this->add_responsive_control(
				'scroll_top_height',
				[
					'label' => esc_html__( 'Height', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 500,
							'step' => 1,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 55,
					],
					'selectors' => [
						'{{WRAPPER}} #tf-scroll-top .inner-scroll-top' => 'height: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->start_controls_tabs( 'scroll_top_color_tabs' );				

				$this->start_controls_tab( 
					'scroll_top_color_normal_tab',
					[
						'label' => esc_html__( 'Normal', 'themesflat-addons-for-elementor' ),						
					]
					);
					$this->add_control( 
						'scroll_top_color',
						[
							'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '#FFFFFF',
							'selectors' => [
								'{{WRAPPER}} #tf-scroll-top .inner-scroll-top' => 'color: {{VALUE}}',
								'{{WRAPPER}} #tf-scroll-top .inner-scroll-top .icon-scroll-top svg' => 'fill: {{VALUE}}',
							],
						]
					);

					$this->add_control( 
						'scroll_top_background_color',
						[
							'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '#222222',
							'selectors' => [
								'{{WRAPPER}} #tf-scroll-top .inner-scroll-top' => 'background-color: {{VALUE}}',
							],
						]
					);

					$this->add_group_control( 
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'scroll_top_border',
							'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
							'selector' => '{{WRAPPER}} #tf-scroll-top .inner-scroll-top',
						]
					);

					$this->add_responsive_control( 
						'scroll_top_border_radius',
						[
							'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px' , '%' ],
							'default' => [
								'top' => '5',
								'right' => '5',
								'bottom' => '5',
								'left' => '5',
								'unit' => 'px',
								'isLinked' => true,
							],
							'selectors' => [
								'{{WRAPPER}} #tf-scroll-top .inner-scroll-top' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$this->add_group_control( 
						\Elementor\Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'scroll_top_box_shadow',
							'label' => esc_html__( 'Box Shadow', 'themesflat-addons-for-elementor' ),
							'selector' => '{{WRAPPER}} #tf-scroll-top .inner-scroll-top',
						]
					);
				$this->end_controls_tab();

				$this->start_controls_tab( 
					'scroll_top_color_hover_tab',
					[
						'label' => esc_html__( 'Hover', 'themesflat-addons-for-elementor' ),						
					]
					);
					$this->add_control( 
						'scroll_top_color_hover',
						[
							'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '#FFFFFF',
							'selectors' => [
								'{{WRAPPER}} #tf-scroll-top .inner-scroll-top:hover' => 'color: {{VALUE}}',
								'{{WRAPPER}} #tf-scroll-top .inner-scroll-top:hover .icon-scroll-top svg' => 'fill: {{VALUE}}',
							],
						]
					);

					$this->add_control( 
						'scroll_top_background_color_hover',
						[
							'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '#03b162',
							'selectors' => [
								'{{WRAPPER}} #tf-scroll-top .inner-scroll-top:hover' => 'background-color: {{VALUE}}',
							],
						]
					);

					$this->add_group_control( 
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'scroll_top_border_hover',
							'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
							'selector' => '{{WRAPPER}} #tf-scroll-top .inner-scroll-top:hover',
						]
					);

					$this->add_responsive_control( 
						'scroll_top_border_radius_hover',
						[
							'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px' , '%' ],
							'selectors' => [
								'{{WRAPPER}} #tf-scroll-top .inner-scroll-top:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$this->add_group_control( 
						\Elementor\Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'scroll_top_box_shadow_hover',
							'label' => esc_html__( 'Box Shadow', 'themesflat-addons-for-elementor' ),
							'selector' => '{{WRAPPER}} #tf-scroll-top .inner-scroll-top:hover',
						]
					);
				$this->end_controls_tab();

			$this->end_controls_tabs();

	        $this->end_controls_section();
        // /.End Settings

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
							'value' => 'fas fa-chevron-up',
							'library' => 'solid',
						],
					]
				);

				$this->add_control(
					'icon_font_size',
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
							'size' => 15,
						],
						'selectors' => [
							'{{WRAPPER}} #tf-scroll-top .icon-scroll-top' => 'font-size: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} #tf-scroll-top .inner-scroll-top .icon-scroll-top svg' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
						],
					]
				);								

		   	$this->end_controls_section();
        // /.End Icon

	}

	protected function render($instance = []) {
		$settings = $this->get_settings_for_display();
		$this->add_render_attribute( 'tf_scroll_top', ['id' => "tf-scroll-top", 'class' => ['tf-scroll-top', $settings['types'], $settings['scroll_top_position'], $settings['scroll_top_style_show'] ], 'data-tabid' => $this->get_id()] );

		?>
		<div <?php echo $this->get_render_attribute_string('tf_scroll_top'); ?> data-type="<?php echo esc_attr($settings['types']); ?>">
			<a href="#" class="inner-scroll-top">
				<span class="icon-scroll-top"><?php \Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); ?></span>
			</a>
		</div>
		<?php
	}

		

}