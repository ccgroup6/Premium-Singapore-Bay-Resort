<?php
class TFPostInfo_Widget_Free extends \Elementor\Widget_Base {

	public function get_name() {
        return 'tfposts-info';
    }
    
    public function get_title() {
        return esc_html__( 'TF Post Info', 'themesflat-addons-for-elementor' );
    }

    public function get_icon() {
        return 'eicon-post-info';
    }
    
    public function get_categories() {
        return [ 'themesflat_addons_single_post' ];
    }

    public function get_style_depends() {
		return ['tf-style'];
	}

	protected function register_controls() {
        // Start Tab Setting        
			$this->start_controls_section( 'section_metadata',
	            [
	                'label' => esc_html__('Meta Data', 'themesflat-addons-for-elementor'),
	            ]
	        );	

	        $this->add_control(
				'layout',
				[
					'label' => esc_html__( 'Layout', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::CHOOSE,
					'default' => 'layout-inline',
					'options' => [
						'layout-list' => [
							'title' => esc_html__( 'List', 'themesflat-addons-for-elementor' ),
							'icon' => 'eicon-editor-list-ul',
						],
						'layout-inline' => [
							'title' => esc_html__( 'Inline', 'themesflat-addons-for-elementor' ),
							'icon' => 'eicon-ellipsis-h',
						],
					],
				]
			);	

			$repeater = new \Elementor\Repeater();
				$repeater->add_control(
					'type',
					[
						'label' => esc_html__( 'Type', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SELECT,
						'default' => 'author',
						'options' => [
							'author' => esc_html__( 'Author', 'themesflat-addons-for-elementor' ),
							'date' => esc_html__( 'Date', 'themesflat-addons-for-elementor' ),
							'time' => esc_html__( 'Time', 'themesflat-addons-for-elementor' ),
							'comments' => esc_html__( 'Comments', 'themesflat-addons-for-elementor' ),
							'terms' => esc_html__( 'Terms', 'themesflat-addons-for-elementor' ),
							'custom' => esc_html__( 'Custom', 'themesflat-addons-for-elementor' ),
						],
					]
				);

				// Author
				$repeater->add_control(
					'show_avatar',
					[
						'label' => esc_html__( 'Avatar', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SWITCHER,
						'label_on' => esc_html__( 'Show', 'themesflat-addons-for-elementor' ),
						'label_off' => esc_html__( 'Hide', 'themesflat-addons-for-elementor' ),
						'return_value' => 'yes',
						'default' => 'none',
						'condition' => [
							'type' => 'author',
						],
					]
				);
				$repeater->add_responsive_control(
					'avatar_size',
					[
						'label' => esc_html__( 'Avatar Size', 'themesflat-addons-for-elementor' ),
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
							'{{WRAPPER}} {{CURRENT_ITEM}} .list-icon img' => 'max-width: {{SIZE}}{{UNIT}}',
						],
						'condition' => [
							'show_avatar' => 'yes',
						],
					]
				);
				$repeater->add_control(
					'avatar_radius',
					[
						'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'range' => [
							'%' => [
								'max' => 100,
							],
						],
						'default' => [
							'unit' => '%',
							'size' => 50,
						],
						'selectors' => [
							'{{WRAPPER}} {{CURRENT_ITEM}} .list-icon img' => 'border-radius: {{SIZE}}{{UNIT}}',
						],
						'condition' => [
							'show_avatar' => 'yes',
						],
					]
				);
				// Date
				$repeater->add_control(
					'date_format',
					[
						'label' => esc_html__( 'Date Format', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SELECT,
						'default' => 'default',
						'options' => [
							'default' => esc_html__( 'Default', 'themesflat-addons-for-elementor' ),
							'0' => esc_html__( 'November 19, 2021 (F j, Y)', 'themesflat-addons-for-elementor' ),
							'1' => esc_html__('2021-04-19 (Y-m-d)', 'themesflat-addons-for-elementor' ),
							'2' => esc_html__('04/19/2021 (m/d/Y)', 'themesflat-addons-for-elementor' ),
							'3' => esc_html__('19/04/2021 (d/m/Y)', 'themesflat-addons-for-elementor' ),
							'custom' => esc_html__( 'Custom', 'themesflat-addons-for-elementor' ),
						],
						'condition' => [
							'type' => 'date',
						],
					]
				);
				$repeater->add_control(
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
							'type' => 'date',
							'date_format'	=> 'custom',
						],
					]
				);
				// Comments
				$repeater->add_control(
					'string_no_comments',
					[
						'label' => esc_html__( 'No Comments', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::TEXT,
						'default' => 'No Comments',
						'placeholder' => esc_html__( 'No Comments', 'themesflat-addons-for-elementor' ),
						'condition' => [
							'type' => 'comments',
						],
					]
				);
				$repeater->add_control(
					'string_one_comment',
					[
						'label' => esc_html__( 'One Comment', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::TEXT,
						'default' => 'One Comment',
						'placeholder' => esc_html__( 'One Comment', 'themesflat-addons-for-elementor' ),
						'condition' => [
							'type' => 'comments',
						],
					]
				);
				$repeater->add_control(
					'string_comments',
					[
						'label' => esc_html__( 'Comments', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::TEXT,
						'default' => '%s Comments',
						'placeholder' => esc_html__( '%s Comments', 'themesflat-addons-for-elementor' ),
						'condition' => [
							'type' => 'comments',
						],
					]
				);
				//Terms
				$repeater->add_control(
					'taxonomy',
					[
						'label' => esc_html__( 'Taxonomy', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SELECT2,
						'label_block' => true,
						'default' => [],
						'options' => $this->tf_get_taxonomies(),
						'condition' => [
							'type' => 'terms',
						],
					]
				);
				//Custom
				$repeater->add_control(
					'custom_text',
					[
						'label' => esc_html__( 'Custom', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::TEXT,
						'label_block' => true,
						'condition' => [
							'type' => 'custom',
						],
					]
				);
				$repeater->add_control(
					'custom_url',
					[
						'label' => esc_html__( 'Custom URL', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::URL,
						'placeholder' => __( 'https://your-link.com', 'plugin-domain' ),
						'show_external' => true,
						'default' => [
							'url' => '',
							'is_external' => true,
							'nofollow' => true,
						],
						'condition' => [
							'type' => 'custom',
						],
					]
				);

				$repeater->add_control( 
		        	'link_to',
					[
						'label' => esc_html__( 'Link', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SELECT,
						'default' => 'none',
						'options' => [
							'none' => esc_html__( 'None', 'themesflat-addons-for-elementor' ),
							'link' => esc_html__( 'Link', 'themesflat-addons-for-elementor' ),
						],
						'condition' => [
							'type!' => ['time', 'custom'],
						],
					]
				);
				$repeater->add_control( 'icon_info', 
		        	[
		                'label' => esc_html__( 'Icon', 'themesflat-addons-for-elementor' ),
		                'type' => \Elementor\Controls_Manager::ICONS,
		                'condition' => [
							'show_avatar!' => 'yes',
						],
		            ]
		        );
		        $repeater->add_responsive_control(
					'icon_size',
					[
						'label' => esc_html__( 'Icon Size', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 200,
								'step' => 1,
							],
						],
						'selectors' => [
							'{{WRAPPER}} {{CURRENT_ITEM}} .list-icon' => 'font-size: {{SIZE}}{{UNIT}}',
						],
						'condition' => [
							'show_avatar!' => 'yes',
						],
					]
				);
			$this->add_control('info_list',
	            [
	                'label'  => esc_html__('Create Info','themesflat-addons-for-elementor'),
	                'type'   => \Elementor\Controls_Manager::REPEATER,
	                'fields' => $repeater->get_controls(),	                
	                'default' => [
	                    [   'type' => 'author',
	                    	'icon_info' => [
								'value' => 'far fa-user',
								'library' => 'fa-regular',
							],
	                    ],
	                    [   'type' => 'date',
	                    	'icon_info' => [
								'value' => 'far fa-calendar-alt',
								'library' => 'fa-regular',
							],
	                    ],
	                    [   'type' => 'comments',
	                    	'icon_info' => [
								'value' => 'far fa-comment-dots',
								'library' => 'fa-regular',
							],
	                    ],
	                ],
	                'title_field' => '{{{ elementor.helpers.renderIcon( this, icon_info, {}, "i", "panel" ) }}} <span style="text-transform: capitalize;">{{{ type }}}</span>',
	            ]
	        );	
	        
			$this->end_controls_section();
        // /.End Tab Setting 

	    // Start Style List
	        $this->start_controls_section( 'section_style_list',
	            [
	                'label' => esc_html__( 'List', 'themesflat-addons-for-elementor' ),
	                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	            ]
	        );

	        $this->add_responsive_control(
				'space_between',
				[
					'label' => esc_html__( 'Space Between', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'max' => 100,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 20,
					],
					'selectors' => [
						'{{WRAPPER}} .tf-post-info:not(.layout-inline) .list-item:not(:last-child)' => 'padding-bottom: calc({{SIZE}}{{UNIT}}/2)',
						'{{WRAPPER}} .tf-post-info:not(.layout-inline) .list-item:not(:first-child)' => 'margin-top: calc({{SIZE}}{{UNIT}}/2)',
						'{{WRAPPER}} .tf-post-info.layout-inline .list-item' => 'margin-right: calc({{SIZE}}{{UNIT}}/2); margin-left: calc({{SIZE}}{{UNIT}}/2)',
						'{{WRAPPER}} .tf-post-info.layout-inline' => 'margin-right: calc(-{{SIZE}}{{UNIT}}/2); margin-left: calc(-{{SIZE}}{{UNIT}}/2)',
					],
				]
			);

			$this->add_responsive_control(
				'alignment',
				[
					'label' => esc_html__( 'Alignment', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::CHOOSE,
					'options' => [
						'left' => [
							'title' => esc_html__( 'Start', 'themesflat-addons-for-elementor' ),
							'icon' => 'eicon-h-align-left',
						],
						'center' => [
							'title' => esc_html__( 'Center', 'themesflat-addons-for-elementor' ),
							'icon' => 'eicon-h-align-center',
						],
						'right' => [
							'title' => esc_html__( 'End', 'themesflat-addons-for-elementor' ),
							'icon' => 'eicon-h-align-right',
						],
					],
					'prefix_class' => 'elementor%s-align-',
				]
			);

        	$this->end_controls_section();    
	    // /.End Style List

        // Start Style Icon
	        $this->start_controls_section( 'section_style_icon',
	            [
	                'label' => esc_html__( 'Icon', 'themesflat-addons-for-elementor' ),
	                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	            ]
	        );

	        $this->add_control( 
				'icon_color',
	            [
	                'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
	                'type' => \Elementor\Controls_Manager::COLOR,
	                'selectors' => [
						'{{WRAPPER}} .tf-post-info .list-post-info .list-item .list-icon' => 'color: {{VALUE}}',
					],
	            ]
	        );

        	$this->end_controls_section();    
	    // /.End Style Icon

        // Start Style Text
	        $this->start_controls_section( 'section_style_text',
	            [
	                'label' => esc_html__( 'Text', 'themesflat-addons-for-elementor' ),
	                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	            ]
	        );

	        $this->add_control(
				'text_indent',
				[
					'label' => esc_html__( 'Indent', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'max' => 50,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 5,
					],
					'selectors' => [
						'{{WRAPPER}} .tf-post-info .list-post-info .list-item .list-text' => 'padding-left: {{SIZE}}{{UNIT}}',
					],
				]
			);

			$this->add_group_control( 
	        	\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'text_typography',
					'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-post-info .list-post-info .list-item .list-text',
				]
			);  

	        $this->add_control( 
				'text_color',
	            [
	                'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
	                'type' => \Elementor\Controls_Manager::COLOR,
	                'selectors' => [
						'{{WRAPPER}} .tf-post-info .list-post-info .list-item .list-text, {{WRAPPER}} .tf-post-info .list-post-info .list-item .list-text a' => 'color: {{VALUE}}',
					],
	            ]
	        );

	        $this->add_control( 
				'text_color_hover',
	            [
	                'label' => esc_html__( 'Color Hover', 'themesflat-addons-for-elementor' ),
	                'type' => \Elementor\Controls_Manager::COLOR,
	                'selectors' => [
						'{{WRAPPER}} .tf-post-info .list-post-info .list-item .list-text a:hover' => 'color: {{VALUE}}',
					],
	            ]
	        );

        	$this->end_controls_section();    
	    // /.End Style Text
	}

	protected function tf_get_taxonomies() {
		$taxonomies = get_taxonomies( [
			'show_in_nav_menus' => true,
		], 'objects' );

		$options = [
			'' => esc_html__( 'Choose', 'themesflat-addons-for-elementor' ),
		];

		foreach ( $taxonomies as $taxonomy ) {
			$options[ $taxonomy->name ] = $taxonomy->label;
		}

		return $options;
	}

	protected function render($instance = []) {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'tf_post_date_wrapper', ['id' => "tf-post-info-{$this->get_id()}", 'class' => ['tf-post-info', $settings['layout']], 'data-tabid' => $this->get_id()] );

		$content = '';
		$post    = get_post();
		if ( ! empty( $post ) ) {			
			if ( $settings['info_list'] ) {		
				foreach ( $settings['info_list'] as $info_list ) {
					switch ( $info_list['type'] ) {
						case 'author':							
							if ($info_list['show_avatar'] == 'yes') {
								$icon =  get_avatar( get_the_author_meta( 'ID', $post->post_author ), 300 );
							}else {
								ob_start();
								$icon = \Elementor\Icons_Manager::render_icon( $info_list['icon_info'], [ 'aria-hidden' => 'true' ] );
								$icon = ob_get_clean();
							}

							$text = get_the_author_meta( 'display_name' );
							if ($info_list['link_to'] == 'link') {
								$text = sprintf( '<a href="%1$s">%2$s</a>', esc_url( get_author_posts_url(get_the_author_meta('ID')) ), get_the_author_meta('display_name') );
							}							

							$content .= sprintf( '
								<li class="list-item %3$s">
									<span class="list-icon">%1$s</span>
									<span class="list-text">%2$s</span>
								</li>' 
								, $icon, $text , 'elementor-repeater-item-'.$info_list['_id'] );
						break;

						case 'date':
							ob_start();
							$icon = \Elementor\Icons_Manager::render_icon( $info_list['icon_info'], [ 'aria-hidden' => 'true' ] );
							$icon = ob_get_clean();
							
							$format_options = [
								'default' => 'F j, Y',
								'0' => 'F j, Y',
								'1' => 'Y-m-d',
								'2' => 'm/d/Y',
								'3' => 'd/m/Y',
								'custom' => $info_list['date_format_custom'],
							];
							$text = get_the_time( $format_options[ $info_list['date_format'] ] );
							if ($info_list['link_to'] == 'link') {
								$text = sprintf( '<a href="%1$s">%2$s</a>', get_day_link( get_post_time( 'Y' ), get_post_time( 'm' ), get_post_time( 'j' ) ), get_the_time( $format_options[ $info_list['date_format'] ] ) );
							}

							$content .= sprintf( '
								<li class="list-item %3$s">
									<span class="list-icon">%1$s</span>
									<span class="list-text">%2$s</span>
								</li>' 
								, $icon, $text , 'elementor-repeater-item-'.$info_list['_id'] );
						break;

						case 'comments':
							if ( comments_open() ) {
								ob_start();
								$icon = \Elementor\Icons_Manager::render_icon( $info_list['icon_info'], [ 'aria-hidden' => 'true' ] );
								$icon = ob_get_clean();

								$string_no_comments = esc_html__( 'No Comments', 'themesflat-addons-for-elementor' );
								$string_one_comment = esc_html__( 'One Comment', 'themesflat-addons-for-elementor' );
								$string_comments = esc_html__( '%s Comments', 'themesflat-addons-for-elementor' );	

								if ( ! empty( $info_list['string_no_comments'] ) ) {
									$string_no_comments = $info_list['string_no_comments'];
								}
								if ( ! empty( $info_list['string_one_comment'] ) ) {
									$string_one_comment = $info_list['string_one_comment'];
								}
								if ( ! empty( $info_list['string_comments'] ) ) {
									$string_comments = $info_list['string_comments'];
								}

								$num_comments = (int) get_comments_number();
								if ( 0 === $num_comments ) {
									$text = $string_no_comments;
								} else {
									$text = sprintf( _n( $string_one_comment, $string_comments, $num_comments, 'themesflat-addons-for-elementor' ), $num_comments );
								}

								if ($info_list['link_to'] == 'link') {
									$text = sprintf( '<a href="%1$s">%2$s</a>', get_comments_link(), $text );
								}

								$content .= sprintf( '
									<li class="list-item %3$s">
										<span class="list-icon">%1$s</span>
										<span class="list-text">%2$s</span>
									</li>' 
									, $icon, $text , 'elementor-repeater-item-'.$info_list['_id'] );
							}
						break;

						case 'terms':
							ob_start();
							$icon = \Elementor\Icons_Manager::render_icon( $info_list['icon_info'], [ 'aria-hidden' => 'true' ] );
							$icon = ob_get_clean();

							$terms_list = [];
							$taxonomy = $info_list['taxonomy'];
							$terms = wp_get_post_terms( get_the_ID(), $taxonomy );
							foreach ( $terms as $term ) {
								$term_name = $term->name;
								$term_url = get_term_link( $term );								
								if ($info_list['link_to'] == 'link') {
									$terms_list[] = '<a href="' . esc_attr( $term_url ) . '" class="terms-list-item">' . esc_html( $term_name ) . '</a>';
								}else {
									$terms_list[] = '<span class="terms-list-item">' . esc_html( $term_name ) . '</span>';
								}
							}

							$text = implode( ', ', $terms_list );
							$content .= sprintf( '
									<li class="list-item %3$s">
										<span class="list-icon">%1$s</span>
										<span class="list-text">%2$s</span>
									</li>' 
									, $icon, $text , 'elementor-repeater-item-'.$info_list['_id'] );
						break;

						case 'custom':
							ob_start();
							$icon = \Elementor\Icons_Manager::render_icon( $info_list['icon_info'], [ 'aria-hidden' => 'true' ] );
							$icon = ob_get_clean();

							$text = $info_list['custom_text'];
							if ($info_list['custom_url']['url'] != '') {
								$target = $info_list['custom_url']['is_external'] ? ' target="_blank"' : '';
								$nofollow = $info_list['custom_url']['nofollow'] ? ' rel="nofollow"' : '';
								$text = sprintf( '<a href="%1$s" %3$s %4$s>%2$s</a>', $info_list['custom_url']['url'], $info_list['custom_text'], $target, $nofollow );
							}

							$content .= sprintf( '
									<li class="list-item %3$s">
										<span class="list-icon">%1$s</span>
										<span class="list-text">%2$s</span>
									</li>' 
									, $icon, $text, 'elementor-repeater-item-'.$info_list['_id'] );
						break;
					}				
				}				
			}			

			echo sprintf ( 
				'<div %1$s> 
					<ul class="list-post-info">%2$s</ul>               
	            </div>',
	            $this->get_render_attribute_string('tf_post_date_wrapper'),
	            $content
	        );	
	    }
		
	}

	

}