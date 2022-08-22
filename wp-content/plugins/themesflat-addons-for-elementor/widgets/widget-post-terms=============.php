<?php
class TFPostTerms_Widget_Free extends \Elementor\Widget_Base {

	public function get_name() {
        return 'tfposts-terms';
    }
    
    public function get_title() {
        return esc_html__( 'TF Post Terms', 'themesflat-addons-for-elementor' );
    }

    public function get_icon() {
        return 'eicon-post-info';
    }
    
    public function get_categories() {
        return [ 'themesflat_addons_single_post' ];
    }

	protected function register_controls() {
        // Start Tab Setting        
			$this->start_controls_section( 'section_tabs',
	            [
	                'label' => esc_html__('Post Terms', 'themesflat-addons-for-elementor'),
	            ]
	        );

	        $this->add_control( 
	        	'term_taxonomy',
				[
					'label' => esc_html__( 'Taxonomy', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'category',
					'options' => [
						'category'    => esc_html__( 'Category', 'themesflat-addons-for-elementor' ),
						'post_tag'    => esc_html__( 'Post Tag', 'themesflat-addons-for-elementor' ),
						'post_format' => esc_html__( 'Post Format', 'themesflat-addons-for-elementor' ),
					],
				]
			);

			$this->add_control(
				'separator',
				[
					'label' => esc_html__( 'Separator', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => esc_html__( ',', 'themesflat-addons-for-elementor' ),
				]
			);

			$this->add_control( 
	        	'html_tag',
				[
					'label' => esc_html__( 'HTML Tag', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'span',
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
						'term' => esc_html__( 'Term', 'themesflat-addons-for-elementor' ),
					],
				]
			);
	        
			$this->end_controls_section();
        // /.End Tab Setting 

	    // Start Style
	        $this->start_controls_section( 'section_post_terms',
	            [
	                'label' => esc_html__( 'Post Terms', 'themesflat-addons-for-elementor' ),
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
						'{{WRAPPER}} .tf-post-terms' => 'text-align: {{VALUE}}',
					],
				]
			);	

			$this->add_group_control( 
	        	\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'typography',
					'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-post-terms .post-terms',
				]
			); 

			$this->start_controls_tabs(  'terms_style_tabs' );

        	$this->start_controls_tab( 'terms_style_normal_tab',
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
							'{{WRAPPER}} .tf-post-terms .post-terms .term-list' => 'color: {{VALUE}}',
							'{{WRAPPER}} .tf-post-terms .post-terms a' => 'color: {{VALUE}}',					
						],
					]
				);
				$this->add_group_control(
					\Elementor\Group_Control_Text_Shadow::get_type(),
					[
						'name' => 'text_shadow',
						'label' => esc_html__( 'Text Shadow', 'themesflat-addons-for-elementor' ),
						'selector' => '{{WRAPPER}} .tf-post-terms .post-terms .term-list',
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
							'{{WRAPPER}} .tf-post-terms .post-terms .term-list, {{WRAPPER}} .tf-post-terms .post-terms a' => '-webkit-transition: all {{SIZE}}s; -moz-transition: all {{SIZE}}s; -ms-transition: all {{SIZE}}s; -o-transition: all {{SIZE}}s; transition: all {{SIZE}}s;',
						],	
					]
				);			
			$this->end_controls_tab();

			$this->start_controls_tab( 'terms_style_hover_tab',
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
							'{{WRAPPER}} .tf-post-terms .post-terms .term-list:hover' => 'color: {{VALUE}}',
							'{{WRAPPER}} .tf-post-terms .post-terms a:hover' => 'color: {{VALUE}}',
						],
					]
				);
				$this->add_group_control(
					\Elementor\Group_Control_Text_Shadow::get_type(),
					[
						'name' => 'text_shadow_hover',
						'label' => esc_html__( 'Text Shadow', 'themesflat-addons-for-elementor' ),
						'selector' => '{{WRAPPER}} .tf-post-terms .post-terms .term-list:hover',
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

		$this->add_render_attribute( 'tf_post_terms_wrapper', ['id' => "tf-post-terms-{$this->get_id()}", 'class' => ['tf-post-terms'], 'data-tabid' => $this->get_id()] );

		$content = '';
		$term_list = get_the_terms( get_the_ID(), $settings['term_taxonomy'] );				

		if ( ! empty( $term_list ) && is_array( $term_list ) ) {
			$count     = count( $term_list );
			$term = $term_list[0]->name;

			if ( 'term' === $settings['select_link_to'] ) {
				$term = sprintf( '<a href="%1$s">%2$s</a>', esc_url( get_term_link( $term_list[0] ) ), $term );
			}

			$animation = ! empty( $settings['hover_animation'] ) ? 'elementor-animation-' . esc_attr( $settings['hover_animation'] . ' inline-block' ) : '';

			$content = sprintf( '<%1$s class="term-list %2$s">%3$s</%1$s>', $settings['html_tag'], $animation, $term );

			for ( $i = 1; $i < $count; $i++ ) {
				$term = $term_list[ $i ]->name;

				if ( 'term' === $settings['select_link_to'] ) {
					$term = sprintf( '<a href="%1$s">%2$s</a>', esc_url( get_term_link( $term_list[ $i ] ) ), $term );
				}

				$content .= sprintf( '%1$s  <%2$s class="term-list %3$s">%4$s</%2$s>', $settings['separator'], $settings['html_tag'], $animation, $term );
			}

			$content = sprintf( '<span class="post-terms">%1$s</span>', $content );

			echo sprintf ( 
				'<div %1$s> 
					%2$s                
	            </div>',
	            $this->get_render_attribute_string('tf_post_terms_wrapper'),
	            $content
	        );	
	    }
		
	}

	

}