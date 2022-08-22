<?php
class TFPostComment_Widget_Free extends \Elementor\Widget_Base {

	public function get_name() {
        return 'tfposts-comment';
    }
    
    public function get_title() {
        return esc_html__( 'TF Post Comment', 'themesflat-addons-for-elementor' );
    }

    public function get_icon() {
        return 'eicon-comments';
    }
    
    public function get_categories() {
        return [ 'themesflat_addons_single_post' ];
    }    

	protected function register_controls() {
        // Start Tab Setting        
			$this->start_controls_section( 'section_tabs',
	            [
	                'label' => esc_html__('Post comment', 'themesflat-addons-for-elementor'),
	            ]
	        );

	        $this->add_control( 
	        	'layout',
				[
					'label' => esc_html__( 'Layout', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'default',
					'options' => [
						'default'   => esc_html__( 'Default', 'themesflat-addons-for-elementor' ),
						'style1'    => esc_html__( 'Style 1', 'themesflat-addons-for-elementor' ),
						'style2' 	=> esc_html__( 'Style 2', 'themesflat-addons-for-elementor' ),
						'style3' 	=> esc_html__( 'Style 3', 'themesflat-addons-for-elementor' ),
					],
				]
			);	

			$this->add_control(
				'comment_form_title',
				[
					'label' => esc_html__( 'Comment Form Title', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => esc_html__( 'Leave a Reply', 'themesflat-addons-for-elementor' ),
					'label_block' => true,
					'condition' => [
						'layout!' => 'default',
					],
				]
			);

			$this->add_control(
				'show_label',
				[
					'label' => esc_html__( 'Show Label', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Show', 'themesflat-addons-for-elementor' ),
					'label_off' => esc_html__( 'Hide', 'themesflat-addons-for-elementor' ),
					'return_value' => 'yes',
					'default' => 'yes',
				]
			);

			$this->add_control(
				'show_placeholder',
				[
					'label' => esc_html__( 'Show Placeholder', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Show', 'themesflat-addons-for-elementor' ),
					'label_off' => esc_html__( 'Hide', 'themesflat-addons-for-elementor' ),
					'return_value' => 'yes',
					'default' => 'yes',
				]
			);

			$this->add_control(
				'input_name',
				[
					'label' => esc_html__( 'Name (Label & Placeholder)', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => esc_html__( 'Name *', 'themesflat-addons-for-elementor' ),	
					'label_block' => true,				
				]
			);

			$this->add_control(
				'input_email',
				[
					'label' => esc_html__( 'Email (Label & Placeholder)', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => esc_html__( 'Email *', 'themesflat-addons-for-elementor' ),
					'label_block' => true,					
				]
			);

			$this->add_control(
				'input_url',
				[
					'label' => esc_html__( 'Url (Label & Placeholder)', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => esc_html__( 'Website', 'themesflat-addons-for-elementor' ),
					'label_block' => true,					
				]
			);

			$this->add_control(
				'input_comment',
				[
					'label' => esc_html__( 'Comment (Label & Placeholder)', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => esc_html__( 'Comment *', 'themesflat-addons-for-elementor' ),	
					'label_block' => true,				
				]
			);

			$this->add_control(
				'btn_comment',
				[
					'label' => esc_html__( 'Button Comment', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => esc_html__( 'Post Comment', 'themesflat-addons-for-elementor' ),	
					'label_block' => true,				
				]
			);		
	        
			$this->end_controls_section();
        // /.End Tab Setting 

		// Start Style Comments List Title
	        $this->start_controls_section( 'section_list_comment_title',
	            [
	                'label' => esc_html__( 'List Comment Title', 'themesflat-addons-for-elementor' ),
	                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	            ]
	        );

	        $this->add_group_control( 
	        	\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'typography_list_comment_title',
					'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-post-comment .title-comments',
				]
			);
			$this->add_control( 
				'color_list_comment_title',
				[
					'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .tf-post-comment .title-comments' => 'color: {{VALUE}}',					
					],
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Text_Shadow::get_type(),
				[
					'name' => 'text_shadow_list_comment_title',
					'label' => esc_html__( 'Text Shadow', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-post-comment .title-comments',
				]
			);

	        $this->add_responsive_control( 
	        	'padding_list_comment_title',
				[
					'label' => esc_html__( 'Padding', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .tf-post-comment .title-comments' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);	

			$this->add_responsive_control( 
				'margin_list_comment_title',
				[
					'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .tf-post-comment .title-comments' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

        	$this->end_controls_section();    
	    // /.End Style

	    // Start Style Form Comment Title
	        $this->start_controls_section( 'section_form_comment_title',
	            [
	                'label' => esc_html__( 'Form Comment Title', 'themesflat-addons-for-elementor' ),
	                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	            ]
	        );

	        $this->add_group_control( 
	        	\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'typography_form_comment_title',
					'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-post-comment .comment-reply-title',
				]
			);
			$this->add_control( 
				'color_form_comment_title',
				[
					'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .tf-post-comment .comment-reply-title' => 'color: {{VALUE}}',					
					],
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Text_Shadow::get_type(),
				[
					'name' => 'text_shadow_form_comment_title',
					'label' => esc_html__( 'Text Shadow', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-post-comment .comment-reply-title',
				]
			);

	        $this->add_responsive_control( 
	        	'padding_form_comment_title',
				[
					'label' => esc_html__( 'Padding', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .tf-post-comment .comment-reply-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);	

			$this->add_responsive_control( 
				'margin_form_comment_title',
				[
					'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .tf-post-comment .comment-reply-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

        	$this->end_controls_section();    
	    // /.End Style 

    	// Start Style Label
	        $this->start_controls_section( 'section_post_comment_label',
	            [
	                'label' => esc_html__( 'Label', 'themesflat-addons-for-elementor' ),
	                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	            ]
	        );

	        $this->add_group_control( 
	        	\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'typography_comment_label',
					'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-post-comment .comment-form label',
				]
			);
			$this->add_control( 
				'color_comment_label',
				[
					'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .tf-post-comment .comment-form label' => 'color: {{VALUE}}',					
					],
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Text_Shadow::get_type(),
				[
					'name' => 'text_shadow_comment_label',
					'label' => esc_html__( 'Text Shadow', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-post-comment .comment-form label',
				]
			);
	        $this->add_responsive_control( 
	        	'padding_comment_label',
				[
					'label' => esc_html__( 'Padding', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .tf-post-comment .comment-form label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control( 
				'margin_comment_label',
				[
					'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .tf-post-comment .comment-form label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

	    	$this->end_controls_section();    
	    // /.End Style 

	   	// Start Style Placeholder
	        $this->start_controls_section( 'section_post_placeholder',
	            [
	                'label' => esc_html__( 'Placeholder', 'themesflat-addons-for-elementor' ),
	                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	            ]
	        );

	        $this->add_group_control( 
	        	\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'typography_placeholder',
					'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-post-comment .comment-form .comment-form-input textarea::-webkit-input-placeholder, {{WRAPPER}} .tf-post-comment .comment-form .comment-form-input textarea::placeholder, {{WRAPPER}} .tf-post-comment .comment-form .comment-form-input input::-webkit-input-placeholder, {{WRAPPER}} .tf-post-comment .comment-form .comment-form-input input::placeholder',
				]
			);
			$this->add_control( 
				'color_placeholder',
				[
					'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .tf-post-comment .comment-form .comment-form-input textarea::-webkit-input-placeholder' => 'color: {{VALUE}}',
						'{{WRAPPER}} .tf-post-comment .comment-form .comment-form-input textarea:-ms-input-placeholder' => 'color: {{VALUE}}',
						'{{WRAPPER}} .tf-post-comment .comment-form .comment-form-input textarea::placeholder' => 'color: {{VALUE}}',
						'{{WRAPPER}} .tf-post-comment .comment-form .comment-form-input input::-webkit-input-placeholder' => 'color: {{VALUE}}',
						'{{WRAPPER}} .tf-post-comment .comment-form .comment-form-input input:-ms-input-placeholder' => 'color: {{VALUE}}',
						'{{WRAPPER}} .tf-post-comment .comment-form .comment-form-input input::placeholder' => 'color: {{VALUE}}',				
					],
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Text_Shadow::get_type(),
				[
					'name' => 'text_shadow_placeholder',
					'label' => esc_html__( 'Text Shadow', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-post-comment .comment-form .comment-form-input textarea::-webkit-input-placeholder, {{WRAPPER}} .tf-post-comment .comment-form .comment-form-input textarea::placeholder, {{WRAPPER}} .tf-post-comment .comment-form .comment-form-input input::-webkit-input-placeholder, {{WRAPPER}} .tf-post-comment .comment-form .comment-form-input input::placeholder',
				]
			);

	    	$this->end_controls_section();    
	    // /.End Style

	    // Start Style input
	        $this->start_controls_section( 'section_post_input',
	            [
	                'label' => esc_html__( 'Input ', 'themesflat-addons-for-elementor' ),
	                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	            ]
	        );

	        $this->add_group_control( 
	        	\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'typography_input',
					'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-post-comment .comment-form .comment-form-input input, {{WRAPPER}} .tf-post-comment .comment-form .comment-form-input textarea',
				]
			);					
	        $this->add_responsive_control( 
	        	'padding_input',
				[
					'label' => esc_html__( 'Padding', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .tf-post-comment .comment-form .comment-form-input input, {{WRAPPER}} .tf-post-comment .comment-form .comment-form-input textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);			

			$this->start_controls_tabs(  'input_style_tabs' );
	        	$this->start_controls_tab( 'input_style_normal_tab',
					[
						'label' => esc_html__( 'Normal', 'themesflat-addons-for-elementor' ),					
					] );
					$this->add_control( 
						'bg_color_input',
						[
							'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .tf-post-comment .comment-form .comment-form-input input, {{WRAPPER}} .tf-post-comment .comment-form .comment-form-input textarea' => 'background-color: {{VALUE}}',				
							],
						]
					);		
	        		$this->add_group_control( 
			        	\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'border_input',
							'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
							'selector' => '{{WRAPPER}} .tf-post-comment .comment-form .comment-form-input input, {{WRAPPER}} .tf-post-comment .comment-form .comment-form-input textarea',
						]
					);
					$this->add_responsive_control( 
						'border_radius_input',
			            [
			                'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
			                'type' => \Elementor\Controls_Manager::DIMENSIONS,
			                'size_units' => [ 'px', '%', 'em' ],
			                'selectors' => [
			                    '{{WRAPPER}} .tf-post-comment .comment-form .comment-form-input input, {{WRAPPER}} .tf-post-comment .comment-form .comment-form-input textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			                ],
			            ]
			        );
				$this->end_controls_tab();

				$this->start_controls_tab( 'input_style_focus_tab',
					[
						'label' => esc_html__( 'Focus', 'themesflat-addons-for-elementor' ),
					] );
					$this->add_control( 
						'bg_color_input_focus',
						[
							'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .tf-post-comment .comment-form .comment-form-input input:focus, {{WRAPPER}} .tf-post-comment .comment-form .comment-form-input textarea:focus' => 'background-color: {{VALUE}}',				
							],
						]
					);
					$this->add_group_control( 
			        	\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'border_input_focus',
							'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
							'selector' => '{{WRAPPER}} .tf-post-comment .comment-form .comment-form-input input:focus, {{WRAPPER}} .tf-post-comment .comment-form .comment-form-input textarea:focus',
						]
					);
					$this->add_responsive_control( 
						'border_radius_input_focus',
			            [
			                'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
			                'type' => \Elementor\Controls_Manager::DIMENSIONS,
			                'size_units' => [ 'px', '%', 'em' ],
			                'selectors' => [
			                    '{{WRAPPER}} .tf-post-comment .comment-form .comment-form-input input:focus, {{WRAPPER}} .tf-post-comment .comment-form .comment-form-input textarea:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			                ],
			            ]
			        );
				$this->end_controls_tab();
			$this->end_controls_tabs();

	    	$this->end_controls_section();    
	    // /.End Style 

	    // Start Style Columns Input Spacing
	        $this->start_controls_section( 'section_post_column_input',
	            [
	                'label' => esc_html__( 'Columns Input Spacing', 'themesflat-addons-for-elementor' ),
	                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	            ]
	        );

	        $this->add_responsive_control( 
	        	'padding_column_input',
				[
					'label' => esc_html__( 'Columns Input Padding', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .tf-post-comment .comment-form .comment-notes' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .tf-post-comment .comment-form .comment-form-author' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .tf-post-comment .comment-form .comment-form-email' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .tf-post-comment .comment-form .comment-form-url' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .tf-post-comment .comment-form .comment-form-comment' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .tf-post-comment .comment-form .form-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .tf-post-comment .comment-form .logged-in-as' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

						'{{WRAPPER}} .tf-post-comment .comment-form ' => 'margin: 0 -{{RIGHT}}{{UNIT}} 0 -{{LEFT}}{{UNIT}};',
					],
				]
			);

	    	$this->end_controls_section();    
	    // /.End Style

	    // Start Style Button Comment
	        $this->start_controls_section( 'section_post_btn',
	            [
	                'label' => esc_html__( 'Button Comment', 'themesflat-addons-for-elementor' ),
	                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	            ]
	        );

	        $this->add_group_control( 
	        	\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'typography_btn',
					'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-post-comment .form-submit #submit',
				]
			);
	        $this->add_responsive_control( 
	        	'padding_btn',
				[
					'label' => esc_html__( 'Padding', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .tf-post-comment .form-submit #submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control( 
				'margin_btn',
				[
					'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .tf-post-comment .form-submit #submit' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->start_controls_tabs(  'btn_style_tabs' );
	        	$this->start_controls_tab( 'btn_style_normal_tab',
					[
						'label' => esc_html__( 'Normal', 'themesflat-addons-for-elementor' ),					
					] );
	        		$this->add_control( 
						'color_btn',
						[
							'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .tf-post-comment .form-submit #submit' => 'color: {{VALUE}}',					
							],
						]
					);
					$this->add_group_control(
						\Elementor\Group_Control_Background::get_type(),
						[
							'name' => 'background_btn',
							'label' => esc_html__( 'Background', 'themesflat-addons-for-elementor' ),
							'types' => [ 'classic', 'gradient' ],
							'selector' => '{{WRAPPER}} .tf-post-comment .form-submit #submit',
						]
					);	
					$this->add_group_control(
						\Elementor\Group_Control_Text_Shadow::get_type(),
						[
							'name' => 'text_shadow_btn',
							'label' => esc_html__( 'Text Shadow', 'themesflat-addons-for-elementor' ),
							'selector' => '{{WRAPPER}} .tf-post-comment .form-submit #submit',
						]
					);
					$this->add_group_control(
						\Elementor\Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'box_shadow_btn',
							'label' => esc_html__( 'Box Shadow', 'themesflat-addons-for-elementor' ),
							'selector' => '{{WRAPPER}} .tf-post-comment .form-submit #submit',
						]
					);
	        		$this->add_group_control( 
			        	\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'border_btn',
							'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
							'selector' => '{{WRAPPER}} .tf-post-comment .form-submit #submit',
						]
					);
					$this->add_responsive_control( 
						'border_radius_btn',
			            [
			                'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
			                'type' => \Elementor\Controls_Manager::DIMENSIONS,
			                'size_units' => [ 'px', '%', 'em' ],
			                'selectors' => [
			                    '{{WRAPPER}} .tf-post-comment .form-submit #submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			                ],
			            ]
			        );
				$this->end_controls_tab();

				$this->start_controls_tab( 'btn_style_hover_tab',
					[
						'label' => esc_html__( 'Hover', 'themesflat-addons-for-elementor' ),
					] );
					$this->add_control( 
						'color_btn_hover',
						[
							'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .tf-post-comment .form-submit #submit:hover' => 'color: {{VALUE}}',					
							],
						]
					);
					$this->add_group_control(
						\Elementor\Group_Control_Background::get_type(),
						[
							'name' => 'background_btn_hover',
							'label' => esc_html__( 'Background', 'themesflat-addons-for-elementor' ),
							'types' => [ 'classic', 'gradient' ],
							'selector' => '{{WRAPPER}} .tf-post-comment .form-submit #submit:hover',
						]
					);
					$this->add_group_control(
						\Elementor\Group_Control_Text_Shadow::get_type(),
						[
							'name' => 'text_shadow_btn_hover',
							'label' => esc_html__( 'Text Shadow', 'themesflat-addons-for-elementor' ),
							'selector' => '{{WRAPPER}} .tf-post-comment .form-submit #submit:hover',
						]
					);
					$this->add_group_control(
						\Elementor\Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'box_shadow_btn_hover',
							'label' => esc_html__( 'Box Shadow', 'themesflat-addons-for-elementor' ),
							'selector' => '{{WRAPPER}} .tf-post-comment .form-submit #submit:hover',
						]
					);
					$this->add_group_control( 
			        	\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'border_btn_hover',
							'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
							'selector' => '{{WRAPPER}} .tf-post-comment .form-submit #submit:hover',
						]
					);
					$this->add_responsive_control( 
						'border_radius_btn_hover',
			            [
			                'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
			                'type' => \Elementor\Controls_Manager::DIMENSIONS,
			                'size_units' => [ 'px', '%', 'em' ],
			                'selectors' => [
			                    '{{WRAPPER}} .tf-post-comment .form-submit #submit:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			                ],
			            ]
			        );
				$this->end_controls_tab();
			$this->end_controls_tabs();

	    	$this->end_controls_section();    
	    // /.End Style

	    // Start Style comment list
	        $this->start_controls_section( 'section_comments list',
	            [
	                'label' => esc_html__( 'Comments List', 'themesflat-addons-for-elementor' ),
	                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	            ]
	        );
	        $this->add_responsive_control( 
	        	'padding_comment_list',
				[
					'label' => esc_html__( 'Padding', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .tf-post-comment #comments .comment-list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control( 
				'margin_comment_list',
				[
					'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .tf-post-comment #comments .comment-list' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);       

			// Comment Body
				$this->add_control(
					'h_comment_body',
					[
						'label' => esc_html__( 'Comment Body', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);
				$this->add_responsive_control( 
		        	'padding_comment_body',
					[
						'label' => esc_html__( 'Padding', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%' ],
						'selectors' => [
							'{{WRAPPER}} .tf-post-comment #comments .comment .comment-body,{{WRAPPER}} .tf-post-comment #comments .pingback .comment-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_responsive_control( 
					'margin_comment_body',
					[
						'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%' ],
						'selectors' => [
							'{{WRAPPER}} .tf-post-comment #comments .comment .comment-body,{{WRAPPER}} .tf-post-comment #comments .pingback .comment-body' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_group_control( 
		        	\Elementor\Group_Control_Border::get_type(),
					[
						'name' => 'border_body_commment',
						'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
						'selector' => '{{WRAPPER}} .tf-post-comment #comments .comment .comment-body,{{WRAPPER}} .tf-post-comment #comments .pingback .comment-body',
					]
				);

			// Comment Children
				$this->add_control(
					'h_comment_children',
					[
						'label' => esc_html__( 'Comment Children', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);
				$this->add_responsive_control(
					'commment_children_spacing',
					[
						'label' => esc_html__( 'Spacing Left Comment Children', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px'],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 200,
								'step' => 1,
							],
						],
						'selectors' => [
							'{{WRAPPER}} .tf-post-comment #comments .children' => 'padding-left: {{SIZE}}{{UNIT}};',
						],
					]
				);			

			// Comment Avatar
				$this->add_control(
					'h_comment_avatar',
					[
						'label' => esc_html__( 'Comment Avatar', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);
				$this->add_control(
					'avatar_size',
					[
						'label' => esc_html__( 'Avatar Size', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px'],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 200,
								'step' => 1,
							],
						],
						'default' => [
							'unit' => 'px',
							'size' => 42,
						],
					]
				);
				$this->add_control(
					'avatar_radius',
					[
						'label' => esc_html__( 'Avatar Radius', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%'],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 200,
								'step' => 1,
							],
							'%' => [
								'min' => 0,
								'max' => 100,
								'step' => 1,
							],
						],
						'default' => [
							'unit' => '%',
							'size' => 50,
						],
						'selectors' => [
							'{{WRAPPER}} .tf-post-comment #comments .comment .avatar,{{WRAPPER}} .tf-post-comment #comments .pingback .avatar' => 'border-radius: {{SIZE}}{{UNIT}};',
						],
					]
				);
				$this->add_control(
					'body_commment_spacing',
					[
						'label' => esc_html__( 'Spacing Between Comment Body & Avatar', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px'],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 300,
								'step' => 1,
							],
						],
						'default' => [
							'unit' => 'px',
							'size' => 60,
						],
						'selectors' => [
							'{{WRAPPER}} .tf-post-comment #comments .comment .comment-body,{{WRAPPER}} .tf-post-comment #comments .pingback .comment-body' => 'padding-left: {{SIZE}}{{UNIT}};',
						],
					]
				);		

			// Comment Author
				$this->add_control(
					'h_comment_author',
					[
						'label' => esc_html__( 'Comment Author', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_group_control( 
		        	\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'typography_comment_author',
						'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
						'selector' => '{{WRAPPER}} .tf-post-comment .comment-body .comment-author a',
					]
				);
				$this->add_control( 
					'color_comment_author',
					[
						'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .tf-post-comment .comment-body .comment-author a' => 'color: {{VALUE}}',					
						],
					]
				);
				$this->add_control( 
					'color_comment_author_hover',
					[
						'label' => esc_html__( 'Color Hover', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .tf-post-comment .comment-body .comment-author a:hover' => 'color: {{VALUE}}',					
						],
					]
				);
				$this->add_group_control(
					\Elementor\Group_Control_Text_Shadow::get_type(),
					[
						'name' => 'text_shadow_comment_author',
						'label' => esc_html__( 'Text Shadow', 'themesflat-addons-for-elementor' ),
						'selector' => '{{WRAPPER}} .tf-post-comment .comment-body .comment-author a',
					]
				);
		        $this->add_responsive_control( 
		        	'padding_comment_author',
					[
						'label' => esc_html__( 'Padding', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%' ],
						'selectors' => [
							'{{WRAPPER}} .tf-post-comment .comment-body .comment-author a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_responsive_control( 
					'margin_comment_author',
					[
						'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%' ],
						'selectors' => [
							'{{WRAPPER}} .tf-post-comment .comment-body .comment-author a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

			// Comment Content
				$this->add_control(
					'h_comment_content',
					[
						'label' => esc_html__( 'Comment Content', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_group_control( 
		        	\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'typography_comment_content',
						'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
						'selector' => '{{WRAPPER}} .tf-post-comment .comment-body .comment-content',
					]
				);
				$this->add_control( 
					'color_comment_content',
					[
						'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .tf-post-comment .comment-body .comment-content' => 'color: {{VALUE}}',					
						],
					]
				);
				$this->add_group_control(
					\Elementor\Group_Control_Text_Shadow::get_type(),
					[
						'name' => 'text_shadow_comment_content',
						'label' => esc_html__( 'Text Shadow', 'themesflat-addons-for-elementor' ),
						'selector' => '{{WRAPPER}} .tf-post-comment .comment-body .comment-content',
					]
				);
		        $this->add_responsive_control( 
		        	'padding_comment_content',
					[
						'label' => esc_html__( 'Padding', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%' ],
						'selectors' => [
							'{{WRAPPER}} .tf-post-comment .comment-body .comment-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_responsive_control( 
					'margin_comment_content',
					[
						'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%' ],
						'selectors' => [
							'{{WRAPPER}} .tf-post-comment .comment-body .comment-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

			// Comment Metadata
				$this->add_control(
					'h_comment_metadata',
					[
						'label' => esc_html__( 'Comment Meta Data', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_group_control( 
		        	\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'typography_comment_metadata',
						'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
						'selector' => '{{WRAPPER}} .tf-post-comment .comment-body .comment-metadata a',
					]
				);
				$this->add_control( 
					'color_comment_metadata',
					[
						'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .tf-post-comment .comment-body .comment-metadata a' => 'color: {{VALUE}}',					
						],
					]
				);
				$this->add_control( 
					'color_comment_metadata_hover',
					[
						'label' => esc_html__( 'Color hover', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .tf-post-comment .comment-body .comment-metadata a:hover' => 'color: {{VALUE}}',					
						],
					]
				);
				$this->add_group_control(
					\Elementor\Group_Control_Text_Shadow::get_type(),
					[
						'name' => 'text_shadow_comment_metadata',
						'label' => esc_html__( 'Text Shadow', 'themesflat-addons-for-elementor' ),
						'selector' => '{{WRAPPER}} .tf-post-comment .comment-body .comment-metadata a',
					]
				);
		        $this->add_responsive_control( 
		        	'padding_comment_metadata',
					[
						'label' => esc_html__( 'Padding', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%' ],
						'selectors' => [
							'{{WRAPPER}} .tf-post-comment .comment-body .comment-metadata a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_responsive_control( 
					'margin_comment_metadata',
					[
						'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%' ],
						'selectors' => [
							'{{WRAPPER}} .tf-post-comment .comment-body .comment-metadata a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

			// Comment Reply
				$this->add_control(
					'h_comment_reply',
					[
						'label' => esc_html__( 'Comment Reply', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_group_control( 
		        	\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'typography_comment_reply',
						'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
						'selector' => '{{WRAPPER}} .tf-post-comment .comment-body .reply a',
					]
				);
				$this->add_control( 
					'color_comment_reply',
					[
						'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .tf-post-comment .comment-body .reply a' => 'color: {{VALUE}}',					
						],
					]
				);
				$this->add_control( 
					'color_comment_reply_hover',
					[
						'label' => esc_html__( 'Color Hover', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .tf-post-comment .comment-body .reply a:hover' => 'color: {{VALUE}}',					
						],
					]
				);
				$this->add_group_control(
					\Elementor\Group_Control_Text_Shadow::get_type(),
					[
						'name' => 'text_shadow_comment_reply',
						'label' => esc_html__( 'Text Shadow', 'themesflat-addons-for-elementor' ),
						'selector' => '{{WRAPPER}} .tf-post-comment .comment-body .reply a',
					]
				);
		        $this->add_responsive_control( 
		        	'padding_comment_reply',
					[
						'label' => esc_html__( 'Padding', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%' ],
						'selectors' => [
							'{{WRAPPER}} .tf-post-comment .comment-body .reply a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_responsive_control( 
					'margin_comment_reply',
					[
						'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%' ],
						'selectors' => [
							'{{WRAPPER}} .tf-post-comment .comment-body .reply a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

	    	$this->end_controls_section();    
	    // /.End Style
	}	

	protected function render($instance = []) {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'tf_post_comment_wrapper', ['id' => "tf-post-comment-{$this->get_id()}", 'class' => ['tf-post-comment', 'tf-comment-form-'.$settings['layout']], 'data-tabid' => $this->get_id()] );

		global $settings_input;
		$settings_input = $settings;
		

		if ($settings['layout'] != 'default') {
			global $comment_form_title;
			$comment_form_title = 'Leave a Reply';
			if ($settings['comment_form_title'] != '') {
				$comment_form_title = $settings['comment_form_title'];
			}		
			
			add_filter('comment_form_defaults', 'tf_set_comment_title_form', 20);
			function tf_set_comment_title_form( $defaults ){
				global $comment_form_title;
			    $defaults['title_reply'] = esc_html__($comment_form_title, 'themesflat-addons-for-elementor');
			    return $defaults;
			}
		}

		// Modify comments header text in comments
		/*add_filter( 'get_comments_number', 'tf_modify_title_comments');
		function tf_modify_title_comments() {
			remove_filter( 'get_comments_number', 'tf_modify_title_comments' );
		    return __(comments_number( 'No Comments', '1 Comment', '% Comments' ), 'themesflat-addons-for-elementor' ;
		}*/
		 
		// Unset from comment form
		function tf_move_comment_form_below( $fields ) { 
			global $settings_input;

			if ($settings_input['layout'] == 'style2' || $settings_input['layout'] == 'style3') {
			 	$comment_field = $fields['comment']; 
		    	unset( $fields['comment'] );
			}		    

		    $req = get_option( 'require_name_email' ) ? " aria-required='true'" : '';
		    $label_comment = ($settings_input['show_label'] == 'yes') ? '<label for="comment">'.esc_attr($settings_input['input_comment']).'</label>' : '';
		    $pla_comment = ($settings_input['show_placeholder'] == 'yes') ? ''.esc_attr($settings_input['input_comment']).'' : '';
		    $fields['comment'] = '<p class="comment-form-comment comment-form-input">'. $label_comment .
		 	     '<textarea id="comment" name="comment" placeholder="'.$pla_comment.'" cols="45" rows="8" maxlength="65525" required="required"></textarea></p>';

		    return $fields; 
		} 
		add_filter( 'comment_form_fields', 'tf_move_comment_form_below' );

		// Add the filter to have custom comment fields
		function tf_modify_comment_form_fields($fields){
			$commenter = wp_get_current_commenter();
			$req = get_option( 'require_name_email' ) ? " aria-required='true'" : '';

			global $settings_input;
			$label_name = ($settings_input['show_label'] == 'yes') ? '<label for="author">'.esc_attr($settings_input['input_name']).'</label>' : '';
			$label_email = ($settings_input['show_label'] == 'yes') ? '<label for="email">'.esc_attr($settings_input['input_email']).'</label>' : '';
			$label_url = ($settings_input['show_label'] == 'yes') ? '<label for="url">'.esc_attr($settings_input['input_url']).'</label>' : '';
			
			$pla_name = ($settings_input['show_placeholder'] == 'yes') ? ''.esc_attr($settings_input['input_name']).'' : '';
			$pla_email = ($settings_input['show_placeholder'] == 'yes') ? ''.esc_attr($settings_input['input_email']).'' : '';
			$pla_url = ($settings_input['show_placeholder'] == 'yes') ? ''.esc_attr($settings_input['input_url']).'' : '';

			$fields_author = '<p class="comment-form-author comment-form-input">' . $label_name .
			    '<input id="author" name="author" placeholder="'.$pla_name.'" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30" /></p>';

			$fields_email = '<p class="comment-form-email comment-form-input">'. $label_email .
			    '<input id="email" name="email" placeholder="'.$pla_email.'" type="text" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" /></p>';

			$fields_url = '<p class="comment-form-url comment-form-input">' . $label_url .
			    '<input id="url" name="url" placeholder="'.$pla_url.'" type="text" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" /></p>';
			
			$fields = array(				
			    'author' => $fields_author,
			    'email' => $fields_email,
			    'url' => $fields_url		    
			);

			return $fields;
		}
		add_filter('comment_form_default_fields','tf_modify_comment_form_fields');

		// Add the filter to have custom button text
		if ($settings_input['btn_comment'] != '') {
			function tf_change_submit_button_text( $defaults ) {
				global $settings_input;
			    $defaults['label_submit'] = $settings_input['btn_comment'];
			    return $defaults;
			}
			add_filter( 'comment_form_defaults', 'tf_change_submit_button_text' );
		}	

		// Add the filter to have custom avatar		
		if ($settings_input['avatar_size']['size']) {
			add_filter('get_avatar', 'tf_custom_avatar', 10, 5);		 
			function tf_custom_avatar($avatar, $id_or_email, $size, $default, $alt) {
				global $comment, $settings_input;
				$size = $settings_input['avatar_size']['size'];
				if ( is_object ( $comment ) && !empty ( $comment ) ) {
					// Remove to avoid recursion
					remove_filter( 'get_avatar', 'tf_custom_avatar' );
					$avatar = get_avatar( $comment, $size, $default );
					add_filter( 'get_avatar', 'tf_custom_avatar', 10, 5 );
				}			 
				return $avatar;
			}
		}

		ob_start();
		comments_template();
		$content = ob_get_contents();
		ob_end_clean();

		echo sprintf ( 
			'<div %1$s> 
				%2$s                
            </div>',
            $this->get_render_attribute_string('tf_post_comment_wrapper'),
            $content
        ); 

	}

	

}