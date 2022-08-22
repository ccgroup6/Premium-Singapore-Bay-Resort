<?php
class TFPostImage_Widget_Free extends \Elementor\Widget_Base {

	public function get_name() {
        return 'tfposts-image';
    }
    
    public function get_title() {
        return esc_html__( 'TF Post Image', 'themesflat-addons-for-elementor' );
    }

    public function get_icon() {
        return 'eicon-featured-image';
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
	                'label' => esc_html__('Post Image', 'themesflat-addons-for-elementor'),
	            ]
	        );	

	        $this->add_group_control( 
				\Elementor\Group_Control_Image_Size::get_type(),
				[
					'name' => 'thumbnail',
					'default' => 'large',
				]
			);

			$this->add_control( 
	        	'select_link_to',
				[
					'label' => esc_html__( 'Link To', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'none',
					'options' => [
						'none' => esc_html__( 'None', 'themesflat-addons-for-elementor' ),
						'home' => esc_html__( 'Home URL', 'themesflat-addons-for-elementor' ),
						'post' => esc_html__( 'Post URL', 'themesflat-addons-for-elementor' ),
						'media' => esc_html__( 'Media URL', 'themesflat-addons-for-elementor' ),
						'custom' => esc_html__( 'Custom URL', 'themesflat-addons-for-elementor' ),
					],
				]
			); 

			$this->add_control(
				'link_to',
				[
					'label' => esc_html__( 'Link', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::URL,
					'placeholder' => esc_html__( 'https://your-link.com', 'themesflat-addons-for-elementor' ),
					'show_external' => true,
					'default' => [
						'url' => '',
						'is_external' => true,
						'nofollow' => true,
					],
					'condition' => [
	                    'select_link_to'	=> 'custom',
	                ],
				]
			);

			$this->add_control(
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
					'default' => 'center',
					'selectors' => [
						'{{WRAPPER}} .tf-post-featured-image' => 'text-align: {{VALUE}}',
					],
				]
			);
	        
			$this->end_controls_section();
        // /.End Tab Setting 

	    // Start Style
	        $this->start_controls_section( 'section_post_image',
	            [
	                'label' => esc_html__( 'Post Image', 'themesflat-addons-for-elementor' ),
	                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	            ]
	        );

			$this->add_responsive_control(
				'featured_width',
				[
					'label' => esc_html__( 'Width', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%', 'vw' ],
					'range' => [					
						'%' => [
							'min' => 0,
							'max' => 100,
						],
						'px' => [
							'min' => 0,
							'max' => 1000,
						],
						'vw' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .tf-post-featured-image img' => 'width: {{SIZE}}{{UNIT}};',
					],				
				]
			);

			$this->add_responsive_control(
				'featured_max_width',
				[
					'label' => esc_html__( 'Max Width', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%', 'vw' ],
					'range' => [					
						'%' => [
							'min' => 0,
							'max' => 100,
						],
						'px' => [
							'min' => 0,
							'max' => 1000,
						],
						'vw' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .tf-post-featured-image img' => 'max-width: {{SIZE}}{{UNIT}};',
					],				
				]
			);

			$this->add_responsive_control(
				'featured_height',
				[
					'label' => esc_html__( 'Heigth', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'vh' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 500,
						],
						'vh' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .tf-post-featured-image img' => 'height: {{SIZE}}{{UNIT}};',
					],				
				]
			);	

			$this->add_control(
				'rotate',
				[
					'label' => esc_html__( 'Rotate', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ '%' ],
					'range' => [					
						'%' => [
							'min' => -360,
							'max' => 360,
						],
					],
					'default' => [
						'unit' => '%',
						'size' => 0,
					],
					'selectors' => [
						'{{WRAPPER}} .tf-post-featured-image img' => '-moz-transform: rotate({{SIZE}}deg); -webkit-transform: rotate( {{SIZE}}deg ); -o-transform: rotate({{SIZE}}deg); -ms-transform: rotate({{SIZE}}deg); transform: rotate( {{SIZE}}deg );',
					],				
				]
			);	

			$this->add_group_control( 
				\Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'box_shadow',
					'label' => esc_html__( 'Box Shadow', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-post-featured-image img',
				]
			);

			$this->add_group_control( 
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'border',
					'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-post-featured-image img',
				]
			);    

			$this->add_responsive_control( 
				'border_radius',
				[
					'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' , '%' ],
					'selectors' => [
						'{{WRAPPER}} .tf-post-featured-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->start_controls_tabs(  'image_style_tabs' );
	        	$this->start_controls_tab( 'image_style_normal_tab',
					[
						'label' => esc_html__( 'Normal', 'themesflat-addons-for-elementor' ),					
					] );
	        		$this->add_control(
						'opacity',
						[
							'label' => esc_html__( 'Opacity', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'size_units' => [ 'px' ],
							'range' => [					
								'px' => [
									'min' => 0,
									'max' => 100,
									'step' => 0.1,
								],
							],
							'selectors' => [
								'{{WRAPPER}} .tf-post-featured-image img' => 'opacity: {{SIZE}}%;',
							],				
						]
					);
					$this->add_group_control( 
						\Elementor\Group_Control_Css_Filter::get_type(),
						[
							'name' => 'css_filter',
							'label' => esc_html__( 'CSS Filters', 'themesflat-addons-for-elementor' ),
							'selector' => '{{WRAPPER}} .tf-post-featured-image img',
						]
					);
				$this->end_controls_tab();

				$this->start_controls_tab( 'image_style_hover_tab',
					[
						'label' => esc_html__( 'Hover', 'themesflat-addons-for-elementor' ),
					] );
					$this->add_control(
						'opacity_hover',
						[
							'label' => esc_html__( 'Opacity', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'size_units' => [ 'px' ],
							'range' => [					
								'px' => [
									'min' => 0,
									'max' => 100,
									'step' => 0.1,
								],
							],
							'selectors' => [
								'{{WRAPPER}} .tf-post-featured-image img:hover' => 'opacity: {{SIZE}}%;',
							],				
						]
					);
					$this->add_group_control( 
						\Elementor\Group_Control_Css_Filter::get_type(),
						[
							'name' => 'css_filter_hover',
							'label' => esc_html__( 'CSS Filters', 'themesflat-addons-for-elementor' ),
							'selector' => '{{WRAPPER}} .tf-post-featured-image img:hover',
						]
					);
					$this->add_control(
						'transition_duration',
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
								'{{WRAPPER}} .tf-post-featured-image img' => 'transition-duration: {{SIZE}}s;',
							],				
						]
					);
					$this->add_control(
						'hover_animation',
						[
							'label' => esc_html__( 'Hover Animation', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::HOVER_ANIMATION,
						]
					);					
				$this->end_controls_tab();
			$this->end_controls_tabs();	
	        
	        $this->end_controls_section();    
	    // /.End Style 
	}

	protected function render($instance = []) {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'tf_post_featured_image_wrapper', ['id' => "tf-post-featured-image-{$this->get_id()}", 'class' => ['tf-post-featured-image'], 'data-tabid' => $this->get_id()] );

		$content = '';
		$get_post_thumbnail = get_post_thumbnail_id();
		$image = '<img src="'.\Elementor\Group_Control_Image_Size::get_attachment_image_src( $get_post_thumbnail, 'thumbnail', $settings ).'" alt="image">';			

		switch ( $settings['select_link_to'] ) {
			case 'home':
				$content = sprintf( '<a href="%1$s">%2$s</a>', esc_url( get_home_url() ), $image );
				break;
			case 'post':
				$content = sprintf( '<a href="%1$s">%2$s</a>', esc_url( get_the_permalink() ), $image );
				break;
			case 'media':
				$content = sprintf( '<a href="%1$s">%2$s</a>', esc_url( get_the_post_thumbnail_url() ), $image );
				break;
			case 'custom':
				$target = $settings['link_to']['is_external'] ? ' target="_blank"' : '';
				$nofollow = $settings['link_to']['nofollow'] ? ' rel="nofollow"' : '';
				$content = sprintf( '<a href="%1$s" %2$s %3$s>%4$s</a>', esc_url( $settings['link_to']['url'] ), esc_attr($target), esc_attr($nofollow), $image );
				break;
			default:
				$content = $image;
				break;
		}

		$animation = ! empty( $settings['hover_animation'] ) ? 'elementor-animation-' . esc_attr( $settings['hover_animation'] ) : '';

		echo sprintf ( 
			'<div %1$s> 
				<div class="post-featured-image %3$s">
                	%2$s
                <div>
            </div>',
            $this->get_render_attribute_string('tf_post_featured_image_wrapper'),
            $content,
            $animation
        );	
		
	}

	

}