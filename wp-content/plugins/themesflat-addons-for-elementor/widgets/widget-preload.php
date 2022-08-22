<?php
class TFPreload_Widget_Free extends \Elementor\Widget_Base {

	public function get_name() {
        return 'tf-preload';
    }
    
    public function get_title() {
        return esc_html__( 'TF Preload', 'themesflat-addons-for-elementor' );
    }

    public function get_icon() {
        return 'eicon-loading';
    }
    
    public function get_categories() {
        return [ 'themesflat_addons' ];
    }

    public function get_style_depends() {
		return ['tf-style'];
	}
	public function get_script_depends() {
		return ['tf-main'];
	}

	protected function register_controls() {
        // Start Settings        
			$this->start_controls_section( 
				'section_preload',
	            [
	                'label' => esc_html__('Settings', 'themesflat-addons-for-elementor'),
	            ]
	        );	

	        $this->add_control(
				'style',
				[
					'label' => esc_html__( 'Styles', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'preload-1',
					'options' => [
						'preload-1' => esc_html__( 'Preload Styles 1', 'themesflat-addons-for-elementor' ),
						'preload-2' => esc_html__( 'Preload Styles 2', 'themesflat-addons-for-elementor' ),
						'preload-3' => esc_html__( 'Preload Styles 3', 'themesflat-addons-for-elementor' ),
						'preload-4' => esc_html__( 'Preload Styles 4', 'themesflat-addons-for-elementor' ),
						'preload-5' => esc_html__( 'Preload Styles 5', 'themesflat-addons-for-elementor' ),
						'preload-6' => esc_html__( 'Preload Styles 6', 'themesflat-addons-for-elementor' ),
						'preload-7' => esc_html__( 'Preload Styles 7', 'themesflat-addons-for-elementor' ),
						'preload-8' => esc_html__( 'Preload Styles 8', 'themesflat-addons-for-elementor' ),
					],
				]
			);

			$this->add_control( 
				'preload_color',
				[
					'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '#03b162',
					'selectors' => [
						'{{WRAPPER}} .tf-preloader-wrap.style-preload-1 .loader-icon' => 'border-top-color: {{VALUE}};border-left-color: {{VALUE}};border-bottom-color: {{VALUE}};',
						'{{WRAPPER}} .tf-preloader-wrap.style-preload-2 .spin-load-1' => 'border-top-color: {{VALUE}};',
						'{{WRAPPER}} .tf-preloader-wrap.style-preload-3 .cssload-loader .cssload-side' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .tf-preloader-wrap.style-preload-4 .sk-circle .sk-child:before' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .tf-preloader-wrap.style-preload-5 .load:before, {{WRAPPER}} .tf-preloader-wrap.style-preload-5 .load:after' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .tf-preloader-wrap.style-preload-6 .double-bounce3, {{WRAPPER}} .tf-preloader-wrap.style-preload-6 .double-bounce4' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .tf-preloader-wrap.style-preload-7 .saquare-loader-1' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .tf-preloader-wrap.style-preload-8 .line-loader > div' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_control( 
				'preload_color_1',
				[
					'label' => esc_html__( 'Color Inner', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '#BA55D3',
					'selectors' => [
						'{{WRAPPER}} .tf-preloader-wrap.style-preload-2 .spin-load-1:before' => 'border-top-color: {{VALUE}};',
					],
					'condition' => [
						'style' => 'preload-2',
					],
				]
			);

			$this->add_control( 
				'preload_color_2',
				[
					'label' => esc_html__( 'Color Inner', 'themesflat-addons-for-elementor' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '#FF00FF',
					'selectors' => [
						'{{WRAPPER}} .tf-preloader-wrap.style-preload-2 .spin-load-1:after' => 'border-top-color: {{VALUE}};',
					],
					'condition' => [
						'style' => 'preload-2',
					],
				]
			);	

	        $this->end_controls_section();
        // /.End Settings

	}

	protected function render($instance = []) {
		$settings = $this->get_settings_for_display();
		$this->add_render_attribute( 'tf_preloader_wrapper', ['id' => "tf-preloader-wrap-{$this->get_id()}", 'class' => ['tf-preloader-wrap', 'style-'.$settings['style']], 'data-tabid' => $this->get_id()] );

		switch ( $settings['style'] ) {
	        case 'preload-1':
	            $content = sprintf('<div class="loader-icon"></div>');
	            break;        
	        case 'preload-2':
	            $content = sprintf('<div class="spin-load-holder"><span class="spin-load-1"></span></div>');
	            break;
	        case 'preload-3':
	            $content = sprintf(' 
	                <div class="load-holder" style="height: 105px">
	                    <div class="cssload-loader">
	                        <div class="cssload-side"></div>
	                        <div class="cssload-side"></div>
	                        <div class="cssload-side"></div>
	                        <div class="cssload-side"></div>
	                        <div class="cssload-side"></div>
	                        <div class="cssload-side"></div>
	                        <div class="cssload-side"></div>
	                        <div class="cssload-side"></div>
	                    </div>
	                </div>');
	            break;
	        case 'preload-4':
	            $content = sprintf(  
	                '<div class="load-holder" style="height: 105px">
	                    <div class="sk-circle">
	                      <div class="sk-circle1 sk-child"></div>
	                      <div class="sk-circle2 sk-child"></div>
	                      <div class="sk-circle3 sk-child"></div>
	                      <div class="sk-circle4 sk-child"></div>
	                      <div class="sk-circle5 sk-child"></div>
	                      <div class="sk-circle6 sk-child"></div>
	                      <div class="sk-circle7 sk-child"></div>
	                      <div class="sk-circle8 sk-child"></div>
	                      <div class="sk-circle9 sk-child"></div>
	                      <div class="sk-circle10 sk-child"></div>
	                      <div class="sk-circle11 sk-child"></div>
	                      <div class="sk-circle12 sk-child"></div>
	                    </div>
	                </div>' );
	            break;
	        case 'preload-5':
	            $content = sprintf('<div class="load-holder"><span class="load"></span></div>');
	            break;
	        case 'preload-6':
	            $content = sprintf('<div class="pulse-loader"><div class="double-bounce3"></div><div class="double-bounce4"></div></div>');
	            break;
	        case 'preload-7':
	            $content = sprintf('<div class="saquare-loader-1"></div>');
	            break;
	        case 'preload-8':
	            $content = sprintf(
	                '<div class="line-loader">
	                    <div class="rect1"></div>
	                    <div class="rect2"></div>
	                    <div class="rect3"></div>
	                    <div class="rect4"></div>
	                    <div class="rect5"></div>
	                </div>');
	            break;
	        default:
	            $content = sprintf('<div class="loader-icon"></div>');
	            break;
	    }

	    if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
	    	echo sprintf ( 
				'<div %1$s> 
					%2$s               
	            </div>',
	            $this->get_render_attribute_string('tf_preloader_wrapper'),
	            $content
	        );
	    }else {
		    echo sprintf ( 
				'<div %1$s> 
					<div class="tf-preloader">
						<div class="preloader-inner">%2$s</div>  
					<div>              
	            </div>',
	            $this->get_render_attribute_string('tf_preloader_wrapper'),
	            $content
	        );
		}
	}

		

}