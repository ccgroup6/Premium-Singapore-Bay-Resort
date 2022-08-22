<?php
class TFPostAuthor_Widget_Free extends \Elementor\Widget_Base {

	public function get_name() {
        return 'tfposts-author';
    }
    
    public function get_title() {
        return esc_html__( 'TF Post Author', 'themesflat-addons-for-elementor' );
    }

    public function get_icon() {
        return 'eicon-person';
    }
    
    public function get_categories() {
        return [ 'themesflat_addons_single_post' ];
    }

	protected function register_controls() {
        // Start Tab Setting        
			$this->start_controls_section( 'section_tabs',
	            [
	                'label' => esc_html__('Post Author', 'themesflat-addons-for-elementor'),
	            ]
	        );

			$this->add_control( 
	        	'post_author_type',
				[
					'label' => esc_html__( 'Post Author Type', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'display_name',
					'options' => [
						'display_name' => esc_html__( 'Display Name', 'themesflat-addons-for-elementor' ),
						'user_name' => esc_html__( 'User Name', 'themesflat-addons-for-elementor' ),
						'user_bio' => esc_html__( 'User Biographical Info', 'themesflat-addons-for-elementor' ),
						'user_image' => esc_html__( 'User Avatar', 'themesflat-addons-for-elementor' ),
						'first_name' => esc_html__( 'First Name', 'themesflat-addons-for-elementor' ),
						'last_name' => esc_html__( 'Last Name', 'themesflat-addons-for-elementor' ),
						'first_last' => esc_html__( 'First + Last Name', 'themesflat-addons-for-elementor' ),
						'last_first' => esc_html__( 'Last + First Name', 'themesflat-addons-for-elementor' ),
						'nick_name' => esc_html__( 'Nick Name', 'themesflat-addons-for-elementor' ),
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
						'author' => esc_html__( 'Author URL', 'themesflat-addons-for-elementor' ),
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
	        $this->start_controls_section( 'section_post_author',
	            [
	                'label' => esc_html__( 'Post Author', 'themesflat-addons-for-elementor' ),
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
						'{{WRAPPER}} .tf-post-author' => 'text-align: {{VALUE}}',
					],
				]
			);	

			$this->add_group_control( 
	        	\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'typography',
					'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-post-author .post-author',
					'condition' =>[
	                    'post_author_type!' => 'user_image',
	                ],
				]
			);

			$this->add_control(
				'avatar_width',
				[
					'label' => esc_html__( 'Width', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [					
						'%' => [
							'min' => 0,
							'max' => 100,
						],
						'px' => [
							'min' => 0,
							'max' => 1000,
						],
					],
					'default' => [
						'unit' => '%',
						'size' => 100,
					],
					'selectors' => [
						'{{WRAPPER}} .tf-post-author .post-author img' => 'max-width: {{SIZE}}{{UNIT}};',
					],
					'condition' =>[
	                    'post_author_type' => 'user_image',
	                ],				
				]
			);	

			$this->add_control(
				'opacity',
				[
					'label' => esc_html__( 'Opacity', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ '%' ],
					'range' => [					
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
						'{{WRAPPER}} .tf-post-author .post-author img' => 'opacity: {{SIZE}}{{UNIT}};',
					],	
					'condition' =>[
	                    'post_author_type' => 'user_image',
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
						'{{WRAPPER}} .tf-post-author .post-author img' => '-moz-transform: rotate({{SIZE}}deg); -webkit-transform: rotate( {{SIZE}}deg ); -o-transform: rotate({{SIZE}}deg); -ms-transform: rotate({{SIZE}}deg); transform: rotate( {{SIZE}}deg );',
					],	
					'condition' =>[
	                    'post_author_type' => 'user_image',
	                ],			
				]
			);

			$this->add_group_control( 
				\Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'box_shadow',
					'label' => esc_html__( 'Box Shadow', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-post-author .post-author img',
					'condition' =>[
	                    'post_author_type' => 'user_image',
	                ],
				]
			);

			$this->add_group_control( 
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'border',
					'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-post-author .post-author img',
					'condition' =>[
	                    'post_author_type' => 'user_image',
	                ],
				]
			);    

			$this->add_responsive_control( 
				'border_radius',
				[
					'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' , '%' ],
					'selectors' => [
						'{{WRAPPER}} .tf-post-author .post-author img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' =>[
	                    'post_author_type' => 'user_image',
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
						'{{WRAPPER}} .tf-post-author .post-author, {{WRAPPER}} .tf-post-author .post-author a' => '-webkit-transition: all {{SIZE}}s; -moz-transition: all {{SIZE}}s; -ms-transition: all {{SIZE}}s; -o-transition: all {{SIZE}}s; transition: all {{SIZE}}s;',
					],	
				]
			);

			$this->start_controls_tabs(  'Author_style_tabs',['condition' =>[ 'post_author_type!' => 'user_image' ]] );

        	$this->start_controls_tab( 'author_style_normal_tab',
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
							'{{WRAPPER}} .tf-post-author .post-author' => 'color: {{VALUE}}',
							'{{WRAPPER}} .tf-post-author .post-author a' => 'color: {{VALUE}}',					
						],
					]
				);
				$this->add_group_control(
					\Elementor\Group_Control_Text_Shadow::get_type(),
					[
						'name' => 'text_shadow',
						'label' => esc_html__( 'Text Shadow', 'themesflat-addons-for-elementor' ),
						'selector' => '{{WRAPPER}} .tf-post-author .post-author',
					]
				);		
			$this->end_controls_tab();

			$this->start_controls_tab( 'author_style_hover_tab',
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
							'{{WRAPPER}} .tf-post-author .post-author:hover' => 'color: {{VALUE}}',
							'{{WRAPPER}} .tf-post-author .post-author a:hover' => 'color: {{VALUE}}',
						],
					]
				);
				$this->add_group_control(
					\Elementor\Group_Control_Text_Shadow::get_type(),
					[
						'name' => 'text_shadow_hover',
						'label' => esc_html__( 'Text Shadow', 'themesflat-addons-for-elementor' ),
						'selector' => '{{WRAPPER}} .tf-post-author .post-author:hover',
					]
				);		
			$this->end_controls_tab();
			$this->end_controls_tabs();	        
        	$this->end_controls_section();    
	    // /.End Style
	}

	public function get_author( $post, $post_author_type ) {
		$author = '';

		switch ( $post_author_type ) {
			case 'first_name':
				$author = get_the_author_meta( 'first_name', $post->post_author );
				break;
			case 'last_name':
				$author = get_the_author_meta( 'last_name', $post->post_author );
				break;
			case 'first_last':
				$author = sprintf( '%s %s', get_the_author_meta( 'first_name', $post->post_author ), get_the_author_meta( 'last_name', $post->post_author ) );
				break;
			case 'last_first':
				$author = sprintf( '%s %s', get_the_author_meta( 'last_name', $post->post_author ), get_the_author_meta( 'first_name', $post->post_author ) );
				break;
			case 'nick_name':
				$author = get_the_author_meta( 'nickname', $post->post_author );
				break;
			case 'display_name':
				$author = get_the_author_meta( 'display_name', $post->post_author );
				break;
			case 'user_name':
				$author = get_the_author_meta( 'user_login', $post->post_author );
				break;
			case 'user_bio':
				$author = get_the_author_meta( 'description', $post->post_author );
				break;
			case 'user_image':
				$author = get_avatar( get_the_author_meta( 'email', $post->post_author ), 256 );
				break;
		}

		return $author;
	}

	protected function render($instance = []) {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'tf_post_author_wrapper', ['id' => "tf-post-author-{$this->get_id()}", 'class' => ['tf-post-author'], 'data-tabid' => $this->get_id()] );

		$content = '';
		$post    = get_post();				

		if ( ! empty( $post ) ) {
			$author = $this->get_author( $post, $settings['post_author_type'] );
			if ( ! empty( $author ) ) {
				switch ( $settings['select_link_to'] ) {
					case 'home':
						$content = sprintf( '<a href="%1$s">%2$s</a>', esc_url( get_home_url() ), $author );
						break;
					case 'post':
						$content = sprintf( '<a href="%1$s">%2$s</a>', esc_url( get_the_permalink() ), $author );
						break;
					case 'author':
						$content = sprintf( '<a href="%1$s">%2$s</a>', esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ), $author );
						break;
					case 'custom':
						$target = $settings['link_to']['is_external'] ? ' target="_blank"' : '';
						$nofollow = $settings['link_to']['nofollow'] ? ' rel="nofollow"' : '';
						$content = sprintf( '<a href="%1$s" %2$s %3$s>%4$s</a>', esc_url( $settings['link_to']['url'] ), esc_attr($target), esc_attr($nofollow), $author );
						break;
					default:
						$content = $author;
						break;
				}

				$animation = ! empty( $settings['hover_animation'] ) ? 'elementor-animation-' . esc_attr( $settings['hover_animation'] . ' inline-block' ) : '';

				$content = sprintf( '<%1$s class="post-author %2$s">%3$s</%1$s>', $settings['html_tag'], $animation, $content );

				echo sprintf ( 
					'<div %1$s> 
						%2$s                
		            </div>',
		            $this->get_render_attribute_string('tf_post_author_wrapper'),
		            $content
		        );
		    }
	    }	
		
	}

	

}