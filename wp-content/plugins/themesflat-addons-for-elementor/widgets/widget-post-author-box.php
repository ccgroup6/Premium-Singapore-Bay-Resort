<?php
class TFPostAuthorBox_Widget_Free extends \Elementor\Widget_Base {

	public function get_name() {
        return 'tf-author-box';
    }
    
    public function get_title() {
        return esc_html__( 'TF Author Box', 'themesflat-addons-for-elementor' );
    }

    public function get_icon() {
        return 'eicon-person';
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
	                'label' => esc_html__('Author Box', 'themesflat-addons-for-elementor'),
	            ]
	        );

			$this->add_control( 
	        	'post_author_source',
				[
					'label' => esc_html__( 'Source', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'current',
					'options' => [
						'current' => esc_html__( 'Current Author', 'themesflat-addons-for-elementor' ),
						'custom' => esc_html__( 'Custom', 'themesflat-addons-for-elementor' ),
					],
				]
			);

		 	$this->add_control(
				'image',
				[
					'label' => esc_html__( 'Choose Image', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::MEDIA,
					'default' => [
						'url' => \Elementor\Utils::get_placeholder_image_src(),
					],
					'condition' => [
						'post_author_source' => 'custom',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Image_Size::get_type(),
				[
					'name' => 'thumbnail',
					'include' => [],
					'default' => 'medium',
					'condition' => [
						'post_author_source' => 'custom',
					],
				]
			);

			$this->add_control(
				'name',
				[
					'label' => esc_html__( 'Name', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => esc_html__( 'John Doe', 'themesflat-addons-for-elementor' ),
					'condition' => [
						'post_author_source' => 'custom',
					],
				]
			);

			$this->add_control(
				'biography',
				[
					'label' => esc_html__( 'Biography', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::TEXTAREA,
					'rows' => 3,
					'default' => esc_html__( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'themesflat-addons-for-elementor' ),
					'condition' => [
						'post_author_source' => 'custom',
					],
				]
			);

			$this->add_control( 
	        	'html_tag',
				[
					'label' => esc_html__( 'HTML Tag', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'h4',
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

			$this->add_control(
				'layout',
				[
					'label' => esc_html__( 'Layout', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::CHOOSE,
					'options' => [
						'left' => [
							'title' => esc_html__( 'Left', 'themesflat-addons-for-elementor' ),
							'icon' => 'eicon-h-align-left',
						],
						'above' => [
							'title' => esc_html__( 'Above', 'themesflat-addons-for-elementor' ),
							'icon' => 'eicon-v-align-top',
						],
						'right' => [
							'title' => esc_html__( 'Right', 'themesflat-addons-for-elementor' ),
							'icon' => 'eicon-h-align-right',
						],
					],
					'default' => 'above',
					'separator' => 'before',
					'prefix_class' => 'tf-author-box-layout-image-',
				]
			);

			$this->add_control(
				'alignment',
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
					'selectors' => [
						'{{WRAPPER}} .tf-author-box' => 'text-align: {{VALUE}}',
					],
				]
			);
	        
			$this->end_controls_section();
        // /.End Tab Setting 

	    // Start Style Avatar
	        $this->start_controls_section( 'section_avatar',
	            [
	                'label' => esc_html__( 'Avatar', 'themesflat-addons-for-elementor' ),
	                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	            ]
	        );

			$this->add_control(
				'size_avatar',
				[
					'label' => esc_html__( 'Avatar Size', 'themesflat-addons-for-elementor' ),
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
						'unit' => 'px',
						'size' => 100,
					],
					'selectors' => [
						'{{WRAPPER}} .tf-author-box .author-box-avatar img' => 'max-width: {{SIZE}}{{UNIT}};',
					],			
				]
			);	

			$this->add_control(
				'rotate_avatar',
				[
					'label' => esc_html__( 'Rotate', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [					
						'px' => [
							'min' => -360,
							'max' => 360,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 0,
					],
					'selectors' => [
						'{{WRAPPER}} .tf-author-box .author-box-avatar img' => '-moz-transform: rotate({{SIZE}}deg); -webkit-transform: rotate( {{SIZE}}deg ); -o-transform: rotate({{SIZE}}deg); -ms-transform: rotate({{SIZE}}deg); transform: rotate( {{SIZE}}deg );',
					],			
				]
			);

			$this->add_group_control( 
				\Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'box_shadow_avatar',
					'label' => esc_html__( 'Box Shadow', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-author-box .author-box-avatar img',
				]
			);

			$this->add_group_control( 
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'border_avatar',
					'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-author-box .author-box-avatar img',
				]
			);    

			$this->add_responsive_control( 
				'border_radius_avatar',
				[
					'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' , '%' ],
					'default' => [
						'top' => '50',
						'right' => '50',
						'bottom' => '50',
						'left' => '50',
						'unit' => '%',
					],
					'selectors' => [
						'{{WRAPPER}} .tf-author-box .author-box-avatar img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'spacing_avatar',
				[
					'label' => esc_html__( 'Spacing', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 20,
					],
					'selectors' => [
						'{{WRAPPER}}.tf-author-box-layout-image-left .tf-author-box .author-box-avatar' => 'margin-right: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}}.tf-author-box-layout-image-right .tf-author-box .author-box-avatar' => 'margin-left: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
						'layout' => ['left', 'right'],
					],			
				]
			);		        
        	$this->end_controls_section();    
	    // /.End Style Avatar

        // Start Style Text
	        $this->start_controls_section( 'section_text',
	            [
	                'label' => esc_html__( 'Text', 'themesflat-addons-for-elementor' ),
	                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	            ]
	        );

	        $this->add_control(
				'h_name',
				[
					'label' => esc_html__( 'Name', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::HEADING,
				]
			);

			$this->add_group_control( 
	        	\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'typography_name',
					'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-author-box .author-box-name',
				]
			);			

			$this->add_control( 
				'color_name',
				[
					'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .tf-author-box .author-box-name' => 'color: {{VALUE}}',
						'{{WRAPPER}} .tf-author-box .author-box-name a' => 'color: {{VALUE}}',					
					],
				]
			);

			$this->add_responsive_control( 
	        	'margin_name',
				[
					'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .tf-author-box .author-box-bio' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					]
				]
			);

			$this->add_control(
				'h_biography',
				[
					'label' => esc_html__( 'Biography', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_group_control( 
	        	\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'typography_biography',
					'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-author-box .author-box-bio',
				]
			);			

			$this->add_control( 
				'color_biography',
				[
					'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .tf-author-box .author-box-bio' => 'color: {{VALUE}}',					
					],
				]
			);

			$this->add_responsive_control( 
	        	'margin_biography',
				[
					'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .tf-author-box .author-box-bio' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					]
				]
			);	 

        	$this->end_controls_section();    
	    // /.End Style Text
	}

	public function get_author( $post, $author_fields ) {
		$author = '';

		switch ( $author_fields ) {
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
				$author = get_avatar( get_the_author_meta( 'email', $post->post_author ), 300 );
				break;
		}

		return $author;
	}

	protected function render($instance = []) {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'tf_author_box_wrapper', ['id' => "tf-author-box-{$this->get_id()}", 'class' => ['tf-author-box'], 'data-tabid' => $this->get_id()] );

		$content = $author_user_image = $author_display_name = $author_name = $author_bio = '';		

		if ( $settings['post_author_source'] == 'current' ) {
			$avatar_args['size'] = 300;
			$user_id = get_the_author_meta( 'ID' );
			$author_user_image = get_avatar( $user_id , 300 );
			$author_display_name = get_the_author_meta( 'display_name' );
			$author_bio = get_the_author_meta( 'description' );
		}else {
			$author_user_image =  \Elementor\Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'image' );
			$author_display_name = $settings['name'];
			$author_bio = $settings['biography'];
		}

		switch ( $settings['select_link_to'] ) {
			case 'home':
				$author_name = sprintf( '<a href="%1$s">%2$s</a>', esc_url( get_home_url() ), $author_display_name );
				break;
			case 'post':
				$author_name = sprintf( '<a href="%1$s">%2$s</a>', esc_url( get_the_permalink() ), $author_display_name );
				break;
			case 'author':
				$author_name = sprintf( '<a href="%1$s">%2$s</a>', esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ), $author_display_name );
				break;
			case 'custom':
				$target = $settings['link_to']['is_external'] ? ' target="_blank"' : '';
				$nofollow = $settings['link_to']['nofollow'] ? ' rel="nofollow"' : '';
				$author_name = sprintf( '<a href="%1$s" %2$s %3$s>%4$s</a>', esc_url( $settings['link_to']['url'] ), esc_attr($target), esc_attr($nofollow), $author_display_name );
				break;
			default:
				$author_name = $author_display_name;
				break;
		}						

		$box_avatar = sprintf( '<div class="author-box-avatar">%1$s</div>',  $author_user_image );			
		$box_text = sprintf( '
						<div class="author-box-text">
							<%1$s class="author-box-name">%2$s</%1$s>
							<div class="author-box-bio">%3$s</div>
						</div>',
						$settings['html_tag'], $author_name, $author_bio 
					);

		$content = sprintf('<div class="author-box">%1$s %2$s</div>', $box_avatar, $box_text);

		echo sprintf ( 
			'<div %1$s> 
				%2$s                
            </div>',
            $this->get_render_attribute_string('tf_author_box_wrapper'),
            $content
        );
		
	}

	

}