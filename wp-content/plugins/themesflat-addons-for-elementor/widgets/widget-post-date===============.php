<?php
class TFPostDate_Widget_Free extends \Elementor\Widget_Base {

	public function get_name() {
        return 'tfposts-date';
    }
    
    public function get_title() {
        return esc_html__( 'TF Post Date', 'themesflat-addons-for-elementor' );
    }

    public function get_icon() {
        return 'eicon-date';
    }
    
    public function get_categories() {
        return [ 'themesflat_addons_single_post' ];
    }

	protected function register_controls() {
        // Start Tab Setting        
			$this->start_controls_section( 'section_tabs',
	            [
	                'label' => esc_html__('Post Date', 'themesflat-addons-for-elementor'),
	            ]
	        );

			$this->add_control( 
	        	'post_date_type',
				[
					'label' => esc_html__( 'Post Date Type', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'published',
					'options' => [
						'published' => esc_html__( 'Published Date', 'themesflat-addons-for-elementor' ),
						'modified' => esc_html__( 'Modified Date', 'themesflat-addons-for-elementor' ),
						'both' => esc_html__( 'Show Both', 'themesflat-addons-for-elementor' ),
					],
				]
			); 

			$this->add_control( 
	        	'post_date_format',
				[
					'label' => esc_html__( 'Post Date Format', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'default',
					'options' => [
						'default' => esc_html__( 'WordPress Default Format', 'themesflat-addons-for-elementor' ),
						'ago' => esc_html__( 'Relative Date/Time Format (ago)', 'themesflat-addons-for-elementor' ),
						'custom' => esc_html__( 'Custom Format', 'themesflat-addons-for-elementor' ),
					],
				]
			);

			$this->add_control(
				'date_format_custom',
				[
					'label' => esc_html__( 'Post Date Custom Format', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => esc_html__( 'F j, Y', 'themesflat-addons-for-elementor' ),
					'placeholder' => esc_html__( 'F j, Y', 'themesflat-addons-for-elementor' ),
					'description' => 	wp_kses(
											sprintf(
												__( 'Insert custom date format for single post meta. For more detail about this format, please refer to <a href="%s" target="_blank">Developer Codex</a>.', 'themesflat-addons-for-elementor' ),
												'https://wordpress.org/support/article/formatting-date-and-time/'
											),
											wp_kses_allowed_html()
										),
					'condition' => [
						'post_date_format'	=> 'custom',
					],
				]
			);

			$this->add_control( 
	        	'html_tag',
				[
					'label' => esc_html__( 'HTML Tag', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'h6',
					'options' => [
						'h1' => esc_html__( 'H1', 'themesflat-addons-for-elementor' ),
						'h2' => esc_html__( 'H2', 'themesflat-addons-for-elementor' ),
						'h3' => esc_html__( 'H3', 'themesflat-addons-for-elementor' ),
						'h4' => esc_html__( 'H4', 'themesflat-addons-for-elementor' ),
						'h5' => esc_html__( 'H5', 'themesflat-addons-for-elementor' ),
						'h6' => esc_html__( 'H6', 'themesflat-addons-for-elementor' ),
						'span' => esc_html__( 'span', 'themesflat-addons-for-elementor' ),
						'p' => esc_html__( 'p', 'themesflat-addons-for-elementor' ),
						'div' => esc_html__( 'div', 'themesflat-addons-for-elementor' ),
					],
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
	        
			$this->end_controls_section();
        // /.End Tab Setting 

	    // Start Style
	        $this->start_controls_section( 'section_post_date',
	            [
	                'label' => esc_html__( 'Post Date', 'themesflat-addons-for-elementor' ),
	                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
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
						'justify' => [
							'title' => esc_html__( 'Justified', 'themesflat-addons-for-elementor' ),
							'icon' => 'eicon-text-align-justify',
						],
					],
					'selectors' => [
						'{{WRAPPER}} .tf-post-date' => 'text-align: {{VALUE}}',
					],
				]
			);	

			$this->add_group_control( 
	        	\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'typography',
					'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-post-date .post-date',
				]
			); 

			$this->start_controls_tabs(  'date_style_tabs' );

        	$this->start_controls_tab( 'date_style_normal_tab',
				[
					'label' => esc_html__( 'Normal', 'themesflat-addons-for-elementor' ),					
				] );	
        		$this->add_control( 
					'color',
					[
						'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .tf-post-date .post-date' => 'color: {{VALUE}}',
							'{{WRAPPER}} .tf-post-date .post-date a' => 'color: {{VALUE}}',					
						],
					]
				);
				$this->add_group_control(
					\Elementor\Group_Control_Text_Shadow::get_type(),
					[
						'name' => 'text_shadow',
						'label' => esc_html__( 'Text Shadow', 'themesflat-addons-for-elementor' ),
						'selector' => '{{WRAPPER}} .tf-post-date .post-date',
					]
				);	
				$this->add_control(
					'transition',
					[
						'label' => esc_html__( 'Transition', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 10,
								'step' => 0.1,
							],
						],
						'default' => [
							'unit' => 'px',
							'size' => 0.3,
						],
						'selectors' => [
							'{{WRAPPER}} .tf-post-date .post-date, {{WRAPPER}} .tf-post-date .post-date a' => '-webkit-transition: all {{SIZE}}s; -moz-transition: all {{SIZE}}s; -ms-transition: all {{SIZE}}s; -o-transition: all {{SIZE}}s; transition: all {{SIZE}}s;',
						],	
					]
				);			
			$this->end_controls_tab();

			$this->start_controls_tab( 'date_style_hover_tab',
				[
					'label' => esc_html__( 'Hover', 'themesflat-addons-for-elementor' ),
				] );

				$this->add_control( 
					'color_hover',
					[
						'label' => esc_html__( 'Color Hover', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .tf-post-date .post-date:hover' => 'color: {{VALUE}}',
							'{{WRAPPER}} .tf-post-date .post-date a:hover' => 'color: {{VALUE}}',
						],
					]
				);
				$this->add_group_control(
					\Elementor\Group_Control_Text_Shadow::get_type(),
					[
						'name' => 'text_shadow_hover',
						'label' => esc_html__( 'Text Shadow', 'themesflat-addons-for-elementor' ),
						'selector' => '{{WRAPPER}} .tf-post-date .post-date:hover',
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

	public function tf_ago_time( $time ) {
		return esc_html(
			sprintf(
				esc_html__( '%s ago', 'themesflat-addons-for-elementor' ),
				$time
			)
		);
	}

	public function tf_get_post_ago_time( $type, $post ) {
		if ( 'published' === $type ) {
			$output = $this->tf_ago_time( human_time_diff( get_the_time( 'U', $post ), current_time( 'timestamp' ) ) );
		} else {
			$output = $this->tf_ago_time( human_time_diff( get_the_modified_time( 'U', $post ), current_time( 'timestamp' ) ) );
		}

		return $output;
	}

	public function tf_get_post_date( $format = '', $post = null, $type = '' ) {
		if ( 'published' === $type ) {
			return get_the_date( $format, $post );
		}

		return get_the_modified_date( $format, $post );
	}

	public function get_post_date( $post, $format, $type, $custom ) {
		if ( 'ago' === $format ) {
			$output = $this->tf_get_post_ago_time( $type, $post );
		} elseif ( 'custom' === $format ) {
			$output = $this->tf_get_post_date( $custom, $post, $type );
		} else {
			$output = $this->tf_get_post_date( null, $post, $type );
		}

		return $output;
	}

	protected function render($instance = []) {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'tf_post_date_wrapper', ['id' => "tf-post-date-{$this->get_id()}", 'class' => ['tf-post-date'], 'data-tabid' => $this->get_id()] );

		$content = '';
		$post    = get_post();

		if ( ! empty( $post ) ) {
			if ( 'both' === $settings['post_date_type'] ) {
				$date = $this->get_post_date( $post, $settings['post_date_format'], 'published', $settings['date_format_custom'] );
				$date = $date . esc_html__( ' - Updated on ', 'themesflat-addons-for-elementor' );
				$date = $date . $this->get_post_date( $post, $settings['post_date_format'], 'modified', $settings['date_format_custom'] );
			} else {
				$date = $this->get_post_date( $post, $settings['post_date_format'], $settings['post_date_type'], $settings['date_format_custom'] );
			}	

			if ( ! empty( $date ) ) {
				switch ( $settings['select_link_to'] ) {
					case 'home':
						$content = sprintf( '<a href="%1$s">%2$s</a>', esc_url( get_home_url() ), $date );
						break;
					case 'post':
						$content = sprintf( '<a href="%1$s">%2$s</a>', esc_url( get_the_permalink() ), $date );
						break;
					case 'custom':
						$target = $settings['link_to']['is_external'] ? ' target="_blank"' : '';
						$nofollow = $settings['link_to']['nofollow'] ? ' rel="nofollow"' : '';
						$content = sprintf( '<a href="%1$s" %2$s %3$s>%4$s</a>', esc_url( $settings['link_to']['url'] ), esc_attr($target), esc_attr($nofollow), $date );
						break;
					default:
						$content = $date;
						break;
				}

				$animation = ! empty( $settings['hover_animation'] ) ? 'elementor-animation-' . esc_attr( $settings['hover_animation'] . ' inline-block' ) : '';

				$content = sprintf( '<%1$s class="post-date %2$s">%3$s</%1$s>', $settings['html_tag'], $animation, $content );

				echo sprintf ( 
					'<div %1$s> 
						%2$s                
		            </div>',
		            $this->get_render_attribute_string('tf_post_date_wrapper'),
		            $content
		        );
		    }	
	    }
		
	}

	

}