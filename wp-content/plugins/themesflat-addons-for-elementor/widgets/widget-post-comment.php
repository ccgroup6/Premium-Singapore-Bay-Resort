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

    public function get_style_depends() {
		return ['tf-style'];
	}  

	protected function register_controls() {
        // Start Tab Setting        
			$this->start_controls_section( 'section_tabs',
	            [
	                'label' => esc_html__('Post comment', 'themesflat-addons-for-elementor'),
	            ]
	        );

			$this->add_control(
				'raw',
				[					
					'type' => \Elementor\Controls_Manager::RAW_HTML,
					'raw' => esc_html__( 'This widget uses the currently active theme comments design and layout to display the comment form and comments.', 'themesflat-addons-for-elementor' ),
					'content_classes' => 'elementor-descriptor',
				]
			);
	        
			$this->end_controls_section();
        // /.End Tab Setting 
	}	

	protected function render($instance = []) {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'tf_post_comment_wrapper', ['id' => "tf-post-comment-{$this->get_id()}", 'class' => ['tf-post-comment'], 'data-tabid' => $this->get_id()] );
		
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