<?php
class TFPostContent_Widget_Free extends \Elementor\Widget_Base {

	public function get_name() {
        return 'tfposts-content';
    }
    
    public function get_title() {
        return esc_html__( 'TF Post Content', 'themesflat-addons-for-elementor' );
    }

    public function get_icon() {
        return 'eicon-post-content';
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
						'{{WRAPPER}} .tf-post-content' => 'text-align: {{VALUE}}',
					],
				]
			);

			$this->add_group_control( 
	        	\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'typography',
					'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
					'selector' => '{{WRAPPER}} .tf-post-content',
				]
			); 

			$this->add_control( 
				'color',
				[
					'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .tf-post-content' => 'color: {{VALUE}}',
						'{{WRAPPER}} .tf-post-content div' => 'color: {{VALUE}}',				
					],
				]
			);
	        
			$this->end_controls_section();
	}

	public function tf_render_post_content( $with_wrapper = true ) {
		static $did_posts = [];
		$post = get_post();
		if ( post_password_required( $post->ID ) ) {
			echo get_the_password_form( $post->ID );
			return;
		}
		if ( isset( $did_posts[ $post->ID ] ) ) {
			return;
		}

		$did_posts[ $post->ID ] = true;
		$editor = \Elementor\Plugin::instance()->editor;
		$is_edit_mode = $editor->is_edit_mode();

		if ( \Elementor\Plugin::instance()->preview->is_preview_mode( $post->ID ) ) {
			$content = \Elementor\Plugin::instance()->preview->builder_wrapper( '' );
		} else {
			$editor->set_edit_mode( false );
			$content = \Elementor\Plugin::instance()->frontend->get_builder_content( $post->ID, true );

			if ( empty( $content ) ) {
				\Elementor\Plugin::instance()->frontend->remove_content_filter();
				setup_postdata( $post );
				echo apply_filters( 'the_content', get_the_content() );
				wp_link_pages( [
					'before' => '<div class="page-links tf-page-links"><span class="page-links-title tf-page-links-title">' . __( 'Pages:', 'themesflat-addons-for-elementor' ) . '</span>',
					'after' => '</div>',
					'link_before' => '<span>',
					'link_after' => '</span>',
					'pagelink' => '<span class="screen-reader-text">' . __( 'Page', 'themesflat-addons-for-elementor' ) . ' </span>%',
					'separator' => '<span class="screen-reader-text">, </span>',
				] );

				\Elementor\Plugin::instance()->frontend->add_content_filter();

				return;
			} else {
				$content = apply_filters( 'the_content', $content );
			}
		}
		\Elementor\Plugin::instance()->editor->set_edit_mode( $is_edit_mode );
		if ( $with_wrapper ) {
			echo '<div class="tf-post-content">' . balanceTags( $content, true ) . '</div>';
		} else {
			echo '<div class="tf-post-content">' . $content . '</div>';
		}
	}

	protected function render($instance = []) {
		$settings = $this->get_settings_for_display();
		$this->tf_render_post_content();
	}

	

}