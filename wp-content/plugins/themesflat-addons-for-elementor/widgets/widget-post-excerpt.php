<?php
class TFPostExcerpt_Widget_Free extends \Elementor\Widget_Base {

	public function get_name() {
        return 'tfposts-excerpt';
    }
    
    public function get_title() {
        return esc_html__( 'TF Post Excerpt', 'themesflat-addons-for-elementor' );
    }

    public function get_icon() {
        return 'eicon-post-excerpt';
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
	                'label' => esc_html__('Post Excerpt', 'themesflat-addons-for-elementor'),
	            ]
	        );

			$this->add_control( 
	        	'html_tag',
				[
					'label' => esc_html__( 'HTML Tag', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'p',
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
						'{{WRAPPER}} .tf-post-excerpt' => 'text-align: {{VALUE}}',
					],
				]
			);
	        
			$this->end_controls_section();
        // /.End Tab Setting 

	    // Start Style
	        $this->start_controls_section( 'section_post_excerpt',
	            [
	                'label' => esc_html__( 'Post Excerpt', 'themesflat-addons-for-elementor' ),
	                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	            ]
	        );

			$this->add_group_control( 
	        	\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'typography',
					'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-post-excerpt .post-excerpt',
				]
			); 

			$this->add_control( 
				'color',
				[
					'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .tf-post-excerpt .post-excerpt' => 'color: {{VALUE}}',
						'{{WRAPPER}} .tf-post-excerpt .post-excerpt a' => 'color: {{VALUE}}',					
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
			        
        	$this->end_controls_section();    
	    // /.End Style 
	}

	protected function render($instance = []) {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'tf_post_excerpt_wrapper', ['id' => "tf-post-excerpt-{$this->get_id()}", 'class' => ['tf-post-excerpt'], 'data-tabid' => $this->get_id()] );

		$content = '';
		$excerpt = get_the_excerpt();			

		switch ( $settings['select_link_to'] ) {
			case 'home':
				$content = sprintf( '<a href="%1$s">%2$s</a>', esc_url( get_home_url() ), $excerpt );
				break;
			case 'post':
				$content = sprintf( '<a href="%1$s">%2$s</a>', esc_url( get_the_permalink() ), $excerpt );
				break;
			case 'custom':
				$target = $settings['link_to']['is_external'] ? ' target="_blank"' : '';
				$nofollow = $settings['link_to']['nofollow'] ? ' rel="nofollow"' : '';
				$content = sprintf( '<a href="%1$s" %2$s %3$s>%4$s</a>', esc_url( $settings['link_to']['url'] ), esc_attr($target), esc_attr($nofollow), $excerpt );
				break;
			default:
				$content = $excerpt;
				break;
		}

		$animation = ! empty( $settings['hover_animation'] ) ? 'elementor-animation-' . esc_attr( $settings['hover_animation'] . ' inline-block' ) : '';

		$content = sprintf( '<%1$s class="post-excerpt %2$s">%3$s</%1$s>', $settings['html_tag'], $animation, $content );

		echo sprintf ( 
			'<div %1$s> 
				%2$s                
            </div>',
            $this->get_render_attribute_string('tf_post_excerpt_wrapper'),
            $content
        );	
		
	}

	

}