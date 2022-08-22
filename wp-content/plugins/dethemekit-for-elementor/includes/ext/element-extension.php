<?php

use Elementor\Core\Schemes\Color;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'De_Sticky_Element_Extension' ) ) {

	
	/**
	 * Define De_Sticky_Element_Extension class
	 */
	class De_Sticky_Element_Extension {

		/**
		 * Sections Data
		 *
		 * @var array
		 */
		public $sections_data = array();

		/**
		 * Columns Data
		 *
		 * @var array
		 */
		public $columns_data = array();

		/**
		 * A reference to an instance of this class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    object
		 */
		private static $instance = null;

		/**
		 * Init Handler
		 */
		public function init() {

			$dethemekit_option = get_option( 'dethemekit_option' );
			
			if ( isset( $dethemekit_option['de_carousel'] ) && $dethemekit_option['de_carousel']) {
				add_action( 'elementor/element/section/section_advanced/after_section_end', array( $this, 'register_controls' ), 10, 2 );
				add_action( 'elementor/element/column/section_advanced/after_section_end', array( $this, 'after_column_section_layout_carousel' ), 10, 2 );
			}

			add_action( 'elementor/element/column/section_advanced/after_section_end', array( $this, 'after_column_section_layout' ), 11, 2 );

			add_action( 'elementor/frontend/section/before_render',  array( $this, 'column_before_render' ) );
			add_action( 'elementor/frontend/column/before_render',  array( $this, 'column_before_render' ) );
			add_action( 'elementor/frontend/element/before_render', array( $this, 'column_before_render' ) );

			add_action( 'elementor/element/section/section_advanced/after_section_end', array( $this, 'add_section_sticky_controls' ), 10, 2 );

			add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'enqueue_scripts' ), 9 );

		}

		/**
		 * After column_layout callback
		 *
		 * @param  object $elems
		 * @param  array $args
		 * @return void
		 */
		public function register_controls($elems) {
			
			$elems->start_controls_section('dethemekit_carousel_global_settings_advance2',
			[
				'label'         => __( 'De Carousel Navigation Wrapper' , 'dethemekit-addons-for-elementor' ),
				'tab'   => Elementor\Controls_Manager::TAB_ADVANCED,
			]
			);
			$elems->add_control('dethemekit_carousel_child_name',
				array(          
					'label'       => __( 'De Carousel Target', 'dethemekit-addons-for-elementor' ),
					'type'        => Elementor\Controls_Manager::SELECT2,
					'options'     => array(
						''   	  => __( 'Select Field', 'dethemekit-addons-for-elementor' ),
						'de_carousel_1'   	=> __( 'De Carousel 1', 'dethemekit-addons-for-elementor' ),
						'de_carousel_2' 	=> __( 'De Carousel 2', 'dethemekit-addons-for-elementor' ),
						'de_carousel_3'   	=> __( 'De Carousel 3', 'dethemekit-addons-for-elementor' ),
						'de_carousel_4' 	=> __( 'De Carousel 4', 'dethemekit-addons-for-elementor' ),
					),
					'default'     => '',
					'description' => '<a href="https://detheme.helpscoutdocs.com/article/368-how-to-use-decarousel" target="_blank">How To Use De Carousel ?</a>'

				)
			);

			$elems->add_control('dethemekit_carousel_scrollable',
				[
					'label' 		=> __( 'Responsive Mobile Tab', 'dethemekit-addons-for-elementor' ),
					'type'			=> Elementor\Controls_Manager::SWITCHER,
				]
			);
			$elems->add_control('dethemekit_carousel_pointer',
				[
					'label' 		=> __( 'Set Cursor to Pointer', 'dethemekit-addons-for-elementor' ),
					'type'			=> Elementor\Controls_Manager::SWITCHER,
					'description'	=> __( 'Change Mouse Cursor to "Pointer" When Hovering Child Columns.', 'dethemekit-addons-for-elementor' ),
					// 'selectors' 	=> array(
					// 	'.dethemekit_show_pointer .'.$settings['dethemekit_carousel_child_name'].':hover' => 'cursor:pointer;')
				]
			);

			$elems->add_control('dethemekit_carousel_tab_active1',
				[
					'label' 		=> __( 'Set Default Active Tab 1', 'dethemekit-addons-for-elementor' ),
					'type'			=> Elementor\Controls_Manager::SWITCHER,
					'condition' => array(
						'dethemekit_carousel_child_name' => 'de_carousel_1',
					),
				]
			);

			$elems->add_group_control(
				Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'dethemekit_de_carousel_tab_background1',
					'types' => [ 'classic' ],
					'selector' => '.dethemekit_child_de_carousel_1.de-carousel-active',
					'condition' => array(
						'dethemekit_carousel_tab_active1' => 'yes',
					),
					
				]
			);

			$elems->add_group_control(
				Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'dethemekit_de_carousel_border1',
					'selector' => '.dethemekit_child_de_carousel_1.de-carousel-active',
					'condition' => array(
						'dethemekit_carousel_tab_active1' => 'yes',
					),
				]
			);
			$elems->add_group_control(
				Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'dethemekit_de_carousel_box_shadow1',
					'selector' => '.dethemekit_child_de_carousel_1.de-carousel-active',
					'condition' => array(
						'dethemekit_carousel_tab_active1' => 'yes',
					),
				]
			);

			$elems->add_control('dethemekit_carousel_tab_active_icon_box1',
				[
					'label' 		=> __( 'Style Active Tab Icon Box 1', 'dethemekit-addons-for-elementor' ),
					'type'			=> Elementor\Controls_Manager::SWITCHER,
					'description'	=> __( 'Set Style Active Column From Icon Box Style.', 'dethemekit-addons-for-elementor' ),
					'condition' => array(
						'dethemekit_carousel_tab_active1' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_1',
					),
				]
			);

			$elems->add_control(
				'dethemekit_carousel_tab_active_icon_color1',
				[
					'label'     => __( 'Icon Color', 'detheme-kit' ),
					'type'      => Elementor\Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Color::get_type(),
						'value' => Color::COLOR_3,
					],
					'selectors' => [
						'.dethemekit_child_de_carousel_1.de-carousel-active .elementor-icon' => 'fill: {{VALUE}} !important;color: {{VALUE}} !important;border-color: {{VALUE}} !important;',
					],
					'condition' => array(
						'dethemekit_carousel_tab_active_icon_box1' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_1',
					),
				]
			);

			$elems->add_control(
				'dethemekit_carousel_tab_active_title_color1',
				[
					'label'     => __( 'Title Color', 'detheme-kit' ),
					'type'      => Elementor\Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Color::get_type(),
						'value' => Color::COLOR_3,
					],
					'selectors' => [
						'.dethemekit_child_de_carousel_1.de-carousel-active .elementor-icon-box-title' => 'color: {{VALUE}} !important;',
					],
					'condition' => array(
						'dethemekit_carousel_tab_active_icon_box1' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_1',
					),
				]
			);

			$elems->add_control(
				'dethemekit_carousel_tab_active_description_color1',
				[
					'label'     => __( 'Description Color', 'detheme-kit' ),
					'type'      => Elementor\Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Color::get_type(),
						'value' => Color::COLOR_3,
					],
					'selectors' => [
						'.dethemekit_child_de_carousel_1.de-carousel-active .elementor-icon-box-description' => 'color: {{VALUE}} !important;',
					],
					'condition' => array(
						'dethemekit_carousel_tab_active_icon_box1' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_1',
					),
				]
			);


			$elems->add_control('dethemekit_carousel_tab_active2',
				[
					'label' 		=> __( 'Set Default Active Tab 2', 'dethemekit-addons-for-elementor' ),
					'type'			=> Elementor\Controls_Manager::SWITCHER,
					'condition' => array(
						'dethemekit_carousel_child_name' => 'de_carousel_2',
					),
				]
			);

			$elems->add_group_control(
				Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'dethemekit_de_carousel_tab_background2',
					'types' => [ 'classic' ],
					'selector' => '.dethemekit_child_de_carousel_2.de-carousel-active',
					'condition' => array(
						'dethemekit_carousel_tab_active2' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_2',
					),
					
				]
			);

			$elems->add_group_control(
				Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'dethemekit_de_carousel_border2',
					'selector' => '.dethemekit_child_de_carousel_2.de-carousel-active',
					'condition' => array(
						'dethemekit_carousel_tab_active2' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_2'
					),
				]
			);
			$elems->add_group_control(
				Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'dethemekit_de_carousel_box_shadow2',
					'selector' => '.dethemekit_child_de_carousel_2.de-carousel-active',
					'condition' => array(
						'dethemekit_carousel_tab_active2' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_2'
					),
				]
			);

			$elems->add_control('dethemekit_carousel_tab_active_icon_box2',
				[
					'label' 		=> __( 'Style Active Tab Icon Box 2', 'dethemekit-addons-for-elementor' ),
					'type'			=> Elementor\Controls_Manager::SWITCHER,
					'description'	=> __( 'Set Style Active Column From Icon Box Style.', 'dethemekit-addons-for-elementor' ),
					'condition' => array(
						'dethemekit_carousel_tab_active2' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_2'
					),
				]
			);

			$elems->add_control(
				'dethemekit_carousel_tab_active_icon_color2',
				[
					'label'     => __( 'Icon Color', 'detheme-kit' ),
					'type'      => Elementor\Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Color::get_type(),
						'value' => Color::COLOR_3,
					],
					'selectors' => [
						'.dethemekit_child_de_carousel_2.de-carousel-active .elementor-icon' => 'fill: {{VALUE}} !important;color: {{VALUE}} !important;border-color: {{VALUE}} !important;',
					],
					'condition' => array(
						'dethemekit_carousel_tab_active_icon_box2' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_2'
					),
				]
			);

			$elems->add_control(
				'dethemekit_carousel_tab_active_title_color2',
				[
					'label'     => __( 'Title Color', 'detheme-kit' ),
					'type'      => Elementor\Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Color::get_type(),
						'value' => Color::COLOR_3,
					],
					'selectors' => [
						'.dethemekit_child_de_carousel_2.de-carousel-active .elementor-icon-box-title' => 'color: {{VALUE}} !important;',
					],
					'condition' => array(
						'dethemekit_carousel_tab_active_icon_box2' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_2'
					),
				]
			);

			$elems->add_control(
				'dethemekit_carousel_tab_active_description_color2',
				[
					'label'     => __( 'Description Color', 'detheme-kit' ),
					'type'      => Elementor\Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Color::get_type(),
						'value' => Color::COLOR_3,
					],
					'selectors' => [
						'.dethemekit_child_de_carousel_2.de-carousel-active .elementor-icon-box-description' => 'color: {{VALUE}} !important;',
					],
					'condition' => array(
						'dethemekit_carousel_tab_active_icon_box2' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_2'
					),
				]
			);

			$elems->add_control('dethemekit_carousel_tab_active3',
				[
					'label' 		=> __( 'Set Default Active Tab 3', 'dethemekit-addons-for-elementor' ),
					'type'			=> Elementor\Controls_Manager::SWITCHER,
					'condition' => array(
						'dethemekit_carousel_child_name' => 'de_carousel_3',
					),
				]
			);

			$elems->add_group_control(
				Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'dethemekit_de_carousel_tab_background3',
					'types' => [ 'classic' ],
					'selector' => '.dethemekit_child_de_carousel_3.de-carousel-active',
					'condition' => array(
						'dethemekit_carousel_tab_active3' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_3',
					),
					
				]
			);

			$elems->add_group_control(
				Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'dethemekit_de_carousel_border3',
					'selector' => '.dethemekit_child_de_carousel_3.de-carousel-active',
					'condition' => array(
						'dethemekit_carousel_tab_active3' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_3',
					),
				]
			);
			$elems->add_group_control(
				Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'dethemekit_de_carousel_box_shadow3',
					'selector' => '.dethemekit_child_de_carousel_3.de-carousel-active',
					'condition' => array(
						'dethemekit_carousel_tab_active3' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_3',
					),
				]
			);

			$elems->add_control('dethemekit_carousel_tab_active_icon_box3',
				[
					'label' 		=> __( 'Style Active Tab Icon Box 3', 'dethemekit-addons-for-elementor' ),
					'type'			=> Elementor\Controls_Manager::SWITCHER,
					'description'	=> __( 'Set Style Active Column From Icon Box Style.', 'dethemekit-addons-for-elementor' ),
					'condition' => array(
						'dethemekit_carousel_tab_active3' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_3',
					),
				]
			);

			$elems->add_control(
				'dethemekit_carousel_tab_active_icon_color3',
				[
					'label'     => __( 'Icon Color', 'detheme-kit' ),
					'type'      => Elementor\Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Color::get_type(),
						'value' => Color::COLOR_3,
					],
					'selectors' => [
						'.dethemekit_child_de_carousel_3.de-carousel-active .elementor-icon' => 'fill: {{VALUE}} !important;color: {{VALUE}} !important;border-color: {{VALUE}} !important;',
					],
					'condition' => array(
						'dethemekit_carousel_tab_active_icon_box3' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_3',
					),
				]
			);

			$elems->add_control(
				'dethemekit_carousel_tab_active_title_color3',
				[
					'label'     => __( 'Title Color', 'detheme-kit' ),
					'type'      => Elementor\Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Color::get_type(),
						'value' => Color::COLOR_3,
					],
					'selectors' => [
						'.dethemekit_child_de_carousel_3.de-carousel-active .elementor-icon-box-title' => 'color: {{VALUE}} !important;',
					],
					'condition' => array(
						'dethemekit_carousel_tab_active_icon_box3' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_3',
					),
				]
			);

			$elems->add_control(
				'dethemekit_carousel_tab_active_description_color3',
				[
					'label'     => __( 'Description Color', 'detheme-kit' ),
					'type'      => Elementor\Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Color::get_type(),
						'value' => Color::COLOR_3,
					],
					'selectors' => [
						'.dethemekit_child_de_carousel_3.de-carousel-active .elementor-icon-box-description' => 'color: {{VALUE}} !important;',
					],
					'condition' => array(
						'dethemekit_carousel_tab_active_icon_box3' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_3',
					),
				]
			);

			$elems->add_control('dethemekit_carousel_tab_active4',
				[
					'label' 		=> __( 'Set Default Active Tab 4', 'dethemekit-addons-for-elementor' ),
					'type'			=> Elementor\Controls_Manager::SWITCHER,
					'condition' => array(
						'dethemekit_carousel_child_name' => 'de_carousel_4',
					),
				]
			);

			$elems->add_group_control(
				Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'dethemekit_de_carousel_tab_background4',
					'types' => [ 'classic' ],
					'selector' => '.dethemekit_child_de_carousel_4.de-carousel-active',
					'condition' => array(
						'dethemekit_carousel_tab_active4' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_4',
					),
					
				]
			);

			$elems->add_group_control(
				Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'dethemekit_de_carousel_border4',
					'selector' => '.dethemekit_child_de_carousel_4.de-carousel-active',
					'condition' => array(
						'dethemekit_carousel_tab_active4' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_4',
					),
				]
			);
			$elems->add_group_control(
				Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'dethemekit_de_carousel_box_shadow4',
					'selector' => '.dethemekit_child_de_carousel_4.de-carousel-active',
					'condition' => array(
						'dethemekit_carousel_tab_active4' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_4',
					),
				]
			);

			$elems->add_control('dethemekit_carousel_tab_active_icon_box4',
				[
					'label' 		=> __( 'Style Active Tab Icon Box 4', 'dethemekit-addons-for-elementor' ),
					'type'			=> Elementor\Controls_Manager::SWITCHER,
					'description'	=> __( 'Set Style Active Column From Icon Box Style.', 'dethemekit-addons-for-elementor' ),
					'condition' => array(
						'dethemekit_carousel_tab_active4' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_4',
					),
				]
			);

			$elems->add_control(
				'dethemekit_carousel_tab_active_icon_color4',
				[
					'label'     => __( 'Icon Color', 'detheme-kit' ),
					'type'      => Elementor\Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Color::get_type(),
						'value' => Color::COLOR_3,
					],
					'selectors' => [
						'.dethemekit_child_de_carousel_4.de-carousel-active .elementor-icon' => 'fill: {{VALUE}} !important;color: {{VALUE}} !important;border-color: {{VALUE}} !important;',
					],
					'condition' => array(
						'dethemekit_carousel_tab_active_icon_box4' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_4',
					),
				]
			);

			$elems->add_control(
				'dethemekit_carousel_tab_active_title_color4',
				[
					'label'     => __( 'Title Color', 'detheme-kit' ),
					'type'      => Elementor\Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Color::get_type(),
						'value' => Color::COLOR_3,
					],
					'selectors' => [
						'.dethemekit_child_de_carousel_4.de-carousel-active .elementor-icon-box-title' => 'color: {{VALUE}} !important;',
					],
					'condition' => array(
						'dethemekit_carousel_tab_active_icon_box4' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_4',
					),
				]
			);

			$elems->add_control(
				'dethemekit_carousel_tab_active_description_color4',
				[
					'label'     => __( 'Description Color', 'detheme-kit' ),
					'type'      => Elementor\Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Color::get_type(),
						'value' => Color::COLOR_3,
					],
					'selectors' => [
						'.dethemekit_child_de_carousel_4.de-carousel-active .elementor-icon-box-description' => 'color: {{VALUE}} !important;',
					],
					'condition' => array(
						'dethemekit_carousel_tab_active_icon_box4' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_4',
					),
				]
			);

			
			
			$elems->end_controls_section();
		}
			

		/**
		 * After column_layout callback carousel
		 *
		 * @param  object $obj
		 * @param  array $args
		 * @return void
		 */
		public function after_column_section_layout_carousel( $obj ) {

			if ( !empty ($obj->settings )){
				$data     = $obj->settings;
				// var_dump($data);
				$settings = isset($data['settings'])?$data['settings']:'';	
			}
			
			// RUN IF SETTINGS-DETHEMEKIT-DE-CAROUSEL CHECKED
		// 	$dethemekit_option = get_option( 'dethemekit_option' );
		// if ( !empty( $dethemekit_option['de_carousel'])) {

			$obj->start_controls_section('dethemekit_carousel_global_settings_advance',
			[
				'label'         => __( 'De Carousel Navigation Item' , 'dethemekit-addons-for-elementor' ),
				'tab'   => Elementor\Controls_Manager::TAB_ADVANCED,
			]
			);
			$obj->add_control('dethemekit_carousel_child_name',
				array(
					'label'       => __( 'De Carousel Target', 'dethemekit-addons-for-elementor' ),
					'type'        => Elementor\Controls_Manager::SELECT2,
					'options'     => array(
						''   	  => __( 'Select Field', 'dethemekit-addons-for-elementor' ),
						'de_carousel_1'   	=> __( 'De Carousel 1', 'dethemekit-addons-for-elementor' ),
						'de_carousel_2' 	=> __( 'De Carousel 2', 'dethemekit-addons-for-elementor' ),
						'de_carousel_3'   	=> __( 'De Carousel 3', 'dethemekit-addons-for-elementor' ),
						'de_carousel_4' 	=> __( 'De Carousel 4', 'dethemekit-addons-for-elementor' ),
					),
					'default'     => '',
					'description' => '<a href="https://detheme.helpscoutdocs.com/article/368-how-to-use-decarousel" target="_blank">How To Use De Carousel ?</a>'
				)
			);

			// BEGIN ACTIVE TAB 1 & ICON BOX 1

			$obj->add_control('dethemekit_carousel_tab_active1',
				[
					'label' 		=> __( 'Set Default Active Tab 1', 'dethemekit-addons-for-elementor' ),
					'type'			=> Elementor\Controls_Manager::SWITCHER,
					'condition' => array(
						'dethemekit_carousel_child_name' => 'de_carousel_1',
					),
				]
			);

			$obj->add_group_control(
				Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'dethemekit_de_carousel_tab_background1',
					'types' => [ 'classic' ],
					'selector' => '.dethemekit_child_de_carousel_1.de-carousel-active',
					'condition' => array(
						'dethemekit_carousel_tab_active1' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_1',
					),
					
				]
			);
			
			$obj->add_group_control(
				Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'dethemekit_de_carousel_border1',
					'selector' => '.dethemekit_child_de_carousel_1.de-carousel-active',
					'condition' => array(
						'dethemekit_carousel_tab_active1' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_1',
					),
				]
			);
			$obj->add_group_control(
				Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'dethemekit_de_carousel_box_shadow1',
					'selector' => '.dethemekit_child_de_carousel_1.de-carousel-active',
					'condition' => array(
						'dethemekit_carousel_tab_active1' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_1',
					),
				]
			);

			$obj->add_control('dethemekit_carousel_tab_active_icon_box1',
				[
					'label' 		=> __( 'Style Active Tab Icon Box 1', 'dethemekit-addons-for-elementor' ),
					'type'			=> Elementor\Controls_Manager::SWITCHER,
					'description'	=> __( 'Set Style Active Column From Icon Box Style.', 'dethemekit-addons-for-elementor' ),
					'condition' => array(
						'dethemekit_carousel_tab_active1' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_1',
					),
				]
			);

			$obj->add_control(
				'dethemekit_carousel_tab_active_icon_color1',
				[
					'label'     => __( 'Icon Color', 'detheme-kit' ),
					'type'      => Elementor\Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Color::get_type(),
						'value' => Color::COLOR_3,
					],
					'selectors' => [
						'.dethemekit_child_de_carousel_1.de-carousel-active .elementor-icon' => 'fill: {{VALUE}} !important;color: {{VALUE}} !important;border-color: {{VALUE}} !important;',
					],
					'condition' => array(
						'dethemekit_carousel_tab_active_icon_box1' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_1',
					),
				]
			);

			$obj->add_control(
				'dethemekit_carousel_tab_active_title_color1',
				[
					'label'     => __( 'Title Color', 'detheme-kit' ),
					'type'      => Elementor\Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Color::get_type(),
						'value' => Color::COLOR_3,
					],
					'selectors' => [
						'.dethemekit_child_de_carousel_1.de-carousel-active .elementor-icon-box-title' => 'color: {{VALUE}} !important;',
					],
					'condition' => array(
						'dethemekit_carousel_tab_active_icon_box1' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_1',
					),
				]
			);

			$obj->add_control(
				'dethemekit_carousel_tab_active_description_color1',
				[
					'label'     => __( 'Description Color', 'detheme-kit' ),
					'type'      => Elementor\Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Color::get_type(),
						'value' => Color::COLOR_3,
					],
					'selectors' => [
						'.dethemekit_child_de_carousel_1.de-carousel-active .elementor-icon-box-description' => 'color: {{VALUE}} !important;',
					],
					'condition' => array(
						'dethemekit_carousel_tab_active_icon_box1' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_1',
					),
				]
			);

			// END  ACTIVE TAB 1 & ICON BOX 1

			// BEGIN  ACTIVE TAB 2 & ICON BOX 2

			$obj->add_control('dethemekit_carousel_tab_active2',
				[
					'label' 		=> __( 'Set Default Active Tab 2', 'dethemekit-addons-for-elementor' ),
					'type'			=> Elementor\Controls_Manager::SWITCHER,
					'condition' => array(
						'dethemekit_carousel_child_name' => 'de_carousel_2',
					),
				]
			);

			$obj->add_group_control(
				Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'dethemekit_de_carousel_tab_background2',
					'types' => [ 'classic' ],
					'selector' => '.dethemekit_child_de_carousel_2.de-carousel-active',
					'condition' => array(
						'dethemekit_carousel_tab_active2' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_2',
					),
					
				]
			);

			$obj->add_group_control(
				Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'dethemekit_de_carousel_border2',
					'selector' => '.dethemekit_child_de_carousel_2.de-carousel-active',
					'condition' => array(
						'dethemekit_carousel_tab_active2' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_2',
					),
				]
			);
			$obj->add_group_control(
				Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'dethemekit_de_carousel_box_shadow2',
					'selector' => '.dethemekit_child_de_carousel_2.de-carousel-active',
					'condition' => array(
						'dethemekit_carousel_tab_active2' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_2',
					),
				]
			);

			$obj->add_control('dethemekit_carousel_tab_active_icon_box2',
				[
					'label' 		=> __( 'Style Active Tab Icon Box 2', 'dethemekit-addons-for-elementor' ),
					'type'			=> Elementor\Controls_Manager::SWITCHER,
					'description'	=> __( 'Set Style Active Column From Icon Box Style.', 'dethemekit-addons-for-elementor' ),
					'condition' => array(
						'dethemekit_carousel_child_name' => 'de_carousel_2',
						'dethemekit_carousel_tab_active2' => 'yes',
					),
				]
			);

			$obj->add_control(
				'dethemekit_carousel_tab_active_icon_color2',
				[
					'label'     => __( 'Icon Color', 'detheme-kit' ),
					'type'      => Elementor\Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Color::get_type(),
						'value' => Color::COLOR_3,
					],
					'selectors' => [
						'.dethemekit_child_de_carousel_2.de-carousel-active .elementor-icon' => 'fill: {{VALUE}} !important;color: {{VALUE}} !important;border-color: {{VALUE}} !important;',
					],
					'condition' => array(
						'dethemekit_carousel_tab_active_icon_box2' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_2',
					),
				]
			);

			$obj->add_control(
				'dethemekit_carousel_tab_active_title_color2',
				[
					'label'     => __( 'Title Color', 'detheme-kit' ),
					'type'      => Elementor\Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Color::get_type(),
						'value' => Color::COLOR_3,
					],
					'selectors' => [
						'.dethemekit_child_de_carousel_2.de-carousel-active .elementor-icon-box-title' => 'color: {{VALUE}} !important;',
					],
					'condition' => array(
						'dethemekit_carousel_tab_active_icon_box2' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_2',
					),
				]
			);

			$obj->add_control(
				'dethemekit_carousel_tab_active_description_color2',
				[
					'label'     => __( 'Description Color', 'detheme-kit' ),
					'type'      => Elementor\Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Color::get_type(),
						'value' => Color::COLOR_3,
					],
					'selectors' => [
						'.dethemekit_child_de_carousel_2.de-carousel-active .elementor-icon-box-description' => 'color: {{VALUE}} !important;',
					],
					'condition' => array(
						'dethemekit_carousel_tab_active_icon_box2' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_2',
					),
				]
			);

			// END  ACTIVE TAB 2 & ICON BOX 2

			// BEGIN  ACTIVE TAB 3 & ICON BOX 3
			$obj->add_control('dethemekit_carousel_tab_active3',
			[
				'label' 		=> __( 'Set Default Active Tab 3', 'dethemekit-addons-for-elementor' ),
				'type'			=> Elementor\Controls_Manager::SWITCHER,
				'condition' => array(
					'dethemekit_carousel_child_name' => 'de_carousel_3',
				),
			]
			);

			$obj->add_group_control(
				Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'dethemekit_de_carousel_tab_background3',
					'types' => [ 'classic' ],
					'selector' => '.dethemekit_child_de_carousel_3.de-carousel-active',
					'condition' => array(
						'dethemekit_carousel_tab_active3' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_3',
					),
					
				]
			);
			
			$obj->add_group_control(
				Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'dethemekit_de_carousel_border3',
					'selector' => '.dethemekit_child_de_carousel_3.de-carousel-active',
					'condition' => array(
						'dethemekit_carousel_tab_active3' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_3',
					),
				]
			);
			$obj->add_group_control(
				Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'dethemekit_de_carousel_box_shadow3',
					'selector' => '.dethemekit_child_de_carousel_3.de-carousel-active',
					'condition' => array(
						'dethemekit_carousel_tab_active3' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_3',
					),
				]
			);

			$obj->add_control('dethemekit_carousel_tab_active_icon_box3',
				[
					'label' 		=> __( 'Style Active Tab Icon Box 3', 'dethemekit-addons-for-elementor' ),
					'type'			=> Elementor\Controls_Manager::SWITCHER,
					'description'	=> __( 'Set Style Active Column From Icon Box Style.', 'dethemekit-addons-for-elementor' ),
					'condition' => array(
						'dethemekit_carousel_tab_active3' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_3',
					),
				]
			);

			$obj->add_control(
				'dethemekit_carousel_tab_active_icon_color3',
				[
					'label'     => __( 'Icon Color', 'detheme-kit' ),
					'type'      => Elementor\Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Color::get_type(),
						'value' => Color::COLOR_3,
					],
					'selectors' => [
						'.dethemekit_child_de_carousel_3.de-carousel-active .elementor-icon' => 'fill: {{VALUE}} !important;color: {{VALUE}} !important;border-color: {{VALUE}} !important;',
					],
					'condition' => array(
						'dethemekit_carousel_tab_active_icon_box3' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_3',
					),
				]
			);

			$obj->add_control(
				'dethemekit_carousel_tab_active_title_color3',
				[
					'label'     => __( 'Title Color', 'detheme-kit' ),
					'type'      => Elementor\Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Color::get_type(),
						'value' => Color::COLOR_3,
					],
					'selectors' => [
						'.dethemekit_child_de_carousel_3.de-carousel-active .elementor-icon-box-title' => 'color: {{VALUE}} !important;',
					],
					'condition' => array(
						'dethemekit_carousel_tab_active_icon_box3' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_3',
					),
				]
			);

			$obj->add_control(
				'dethemekit_carousel_tab_active_description_color3',
				[
					'label'     => __( 'Description Color', 'detheme-kit' ),
					'type'      => Elementor\Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Color::get_type(),
						'value' => Color::COLOR_3,
					],
					'selectors' => [
						'.dethemekit_child_de_carousel_3.de-carousel-active .elementor-icon-box-description' => 'color: {{VALUE}} !important;',
					],
					'condition' => array(
						'dethemekit_carousel_tab_active_icon_box3' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_3',
					),
				]
			);

			// END  ACTIVE TAB 3 & ICON BOX 3

			// BEGIN  ACTIVE TAB 4 & ICON BOX 4
			$obj->add_control('dethemekit_carousel_tab_active4',
			[
				'label' 		=> __( 'Set Default Active Tab 4', 'dethemekit-addons-for-elementor' ),
				'type'			=> Elementor\Controls_Manager::SWITCHER,
				'condition' => array(
					'dethemekit_carousel_child_name' => 'de_carousel_4',
				),
			]
			);

			$obj->add_group_control(
				Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'dethemekit_de_carousel_tab_background4',
					'types' => [ 'classic' ],
					'selector' => '.dethemekit_child_de_carousel_4.de-carousel-active',
					'condition' => array(
						'dethemekit_carousel_tab_active4' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_4',
					),
					
				]
			);
			
			$obj->add_group_control(
				Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'dethemekit_de_carousel_border4',
					'selector' => '.dethemekit_child_de_carousel_4.de-carousel-active',
					'condition' => array(
						'dethemekit_carousel_tab_active4' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_4',
					),
				]
			);
			$obj->add_group_control(
				Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'dethemekit_de_carousel_box_shadow4',
					'selector' => '.dethemekit_child_de_carousel_4.de-carousel-active',
					'condition' => array(
						'dethemekit_carousel_tab_active4' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_4',
					),
				]
			);

			$obj->add_control('dethemekit_carousel_tab_active_icon_box4',
				[
					'label' 		=> __( 'Style Active Tab Icon Box 4', 'dethemekit-addons-for-elementor' ),
					'type'			=> Elementor\Controls_Manager::SWITCHER,
					'description'	=> __( 'Set Style Active Column From Icon Box Style.', 'dethemekit-addons-for-elementor' ),
					'condition' => array(
						'dethemekit_carousel_tab_active4' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_4',
					),
				]
			);

			$obj->add_control(
				'dethemekit_carousel_tab_active_icon_color4',
				[
					'label'     => __( 'Icon Color', 'detheme-kit' ),
					'type'      => Elementor\Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Color::get_type(),
						'value' => Color::COLOR_3,
					],
					'selectors' => [
						'.dethemekit_child_de_carousel_4.de-carousel-active .elementor-icon' => 'fill: {{VALUE}} !important;color: {{VALUE}} !important;border-color: {{VALUE}} !important;',
					],
					'condition' => array(
						'dethemekit_carousel_tab_active_icon_box4' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_4',
					),
				]
			);

			$obj->add_control(
				'dethemekit_carousel_tab_active_title_color4',
				[
					'label'     => __( 'Title Color', 'detheme-kit' ),
					'type'      => Elementor\Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Color::get_type(),
						'value' => Color::COLOR_3,
					],
					'selectors' => [
						'.dethemekit_child_de_carousel_4.de-carousel-active .elementor-icon-box-title' => 'color: {{VALUE}} !important;',
					],
					'condition' => array(
						'dethemekit_carousel_tab_active_icon_box4' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_4',
					),
				]
			);

			$obj->add_control(
				'dethemekit_carousel_tab_active_description_color4',
				[
					'label'     => __( 'Description Color', 'detheme-kit' ),
					'type'      => Elementor\Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Color::get_type(),
						'value' => Color::COLOR_3,
					],
					'selectors' => [
						'.dethemekit_child_de_carousel_4.de-carousel-active .elementor-icon-box-description' => 'color: {{VALUE}} !important;',
					],
					'condition' => array(
						'dethemekit_carousel_tab_active_icon_box4' => 'yes',
						'dethemekit_carousel_child_name' => 'de_carousel_4',
					),
				]
			);

			// END  ACTIVE TAB 4 & ICON BOX 4
			
			$obj->end_controls_section();

			// }
		}

		/**
		 * After column_layout callback
		 *
		 * @param  object $obj
		 * @param  array $args
		 * @return void
		 */
		public function after_column_section_layout( $obj ) {

			if ( !empty ($obj->settings )){
				$data     = $obj->settings;
				// var_dump($data);
				$settings = isset($data['settings'])?$data['settings']:'';	
			}

			$obj->start_controls_section(
				'de_sticky_column_sticky_section',
				array(
					'label' => esc_html__( 'De Sticky', 'desticky-for-elementor' ),
					'tab'   => Elementor\Controls_Manager::TAB_ADVANCED,
				)
			);

			$obj->add_control(
				'de_sticky_column_sticky_enable',
				array(
					'label'        => esc_html__( 'Sticky Column', 'desticky-for-elementor' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'desticky-for-elementor' ),
					'label_off'    => esc_html__( 'No', 'desticky-for-elementor' ),
					'return_value' => 'true',
					'default'      => 'false',
				)
			);

			$obj->add_control(
				'de_sticky_column_sticky_top_spacing',
				array(
					'label'   => esc_html__( 'Top Spacing', 'desticky-for-elementor' ),
					'type'    => Elementor\Controls_Manager::NUMBER,
					'default' => 50,
					'min'     => 0,
					'max'     => 500,
					'step'    => 1,
					'condition' => array(
						'de_sticky_column_sticky_enable' => 'true',
					),
				)
			);

			$obj->add_control(
				'de_sticky_column_sticky_bottom_spacing',
				array(
					'label'   => esc_html__( 'Bottom Spacing', 'desticky-for-elementor' ),
					'type'    => Elementor\Controls_Manager::NUMBER,
					'default' => 50,
					'min'     => 0,
					'max'     => 500,
					'step'    => 1,
					'condition' => array(
						'de_sticky_column_sticky_enable' => 'true',
					),
				)
			);

			$obj->add_responsive_control(
				'de_sticky_column_sticky_padding',
				[
					'label' => esc_html__( 'Margin', 'elementor' ),
					'type' => Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%', 'rem' ],

					'selectors' => [
						'.de-sticky-column-sticky' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					
					'condition' => array(
						'de_sticky_column_sticky_enable' => 'true',
					),
				]
			);

			$obj->add_responsive_control(
				'de_sticky_column_sticky_margin',
				[
					'label' => esc_html__( 'Padding', 'elementor' ),
					'type' => Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%', 'rem' ],

					'selectors' => [
						'.de-sticky-column-sticky' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					
					'condition' => array(
						'de_sticky_column_sticky_enable' => 'true',
					),
				]
			);

			$obj->add_control(
				'de_sticky_column_sticky_enable_on',
				array(
					'label'    => __( 'Sticky On', 'desticky-for-elementor' ),
					'type'     => Elementor\Controls_Manager::SELECT2,
					'multiple' => true,
					'label_block' => 'true',
					'default' => array(
						'desktop',
						'tablet',
					),
					'options' => array(
						'desktop' => __( 'Desktop', 'desticky-for-elementor' ),
						'tablet'  => __( 'Tablet', 'desticky-for-elementor' ),
						'mobile'  => __( 'Mobile', 'desticky-for-elementor' ),
					),
					'condition' => array(
						'de_sticky_column_sticky_enable' => 'true',
					),
					'render_type' => 'none',
				)
			);

			$obj->end_controls_section();
		}

		/**
		 * Before column render callback.
		 *
		 * @param object $element
		 *
		 * @return void
		 */
		public function column_before_render( $element ) {

			$data     = $element->get_data();
			$type     = isset( $data['elType'] ) ? $data['elType'] : 'column';
			$settings = isset($data['settings'])?$data['settings']:'';
			// var_dump($settings);

			
			if ( isset( $settings['dethemekit_carousel_child_name'] ) ) {
				
				if ( ! isset($settings['dethemekit_carousel_parent']) && ! isset($settings['dethemekit_carousel_child'])){

					$element->add_render_attribute( '_wrapper', array(
						'class' => 'dethemekit_child_'.$settings['dethemekit_carousel_child_name'],
					) );
				}
			}

			if ( isset($settings['dethemekit_carousel_tab_active_icon_box']) ){
				$element->add_render_attribute( '_wrapper', array(
						'class' => 'de-carousel-tab-active-icon-box',
				) );
			}

			if ( isset($settings['dethemekit_carousel_scrollable']) ){
				$element->add_render_attribute( '_wrapper', array(
						'class' => 'dethemekit_de-horizontal-scroll',
				) );
			}

			if ( isset($settings['dethemekit_carousel_tab_active1']) ){
				$element->add_render_attribute( '_wrapper', array(
						'class' => 'de-carousel-active',
				) );
			}
			if ( isset($settings['dethemekit_carousel_tab_active2']) ){
				$element->add_render_attribute( '_wrapper', array(
						'class' => 'de-carousel-active',
				) );
			}
			if ( isset($settings['dethemekit_carousel_tab_active3']) ){
				$element->add_render_attribute( '_wrapper', array(
						'class' => 'de-carousel-active',
				) );
			}
			if ( isset($settings['dethemekit_carousel_tab_active4']) ){
				$element->add_render_attribute( '_wrapper', array(
						'class' => 'de-carousel-active',
				) );
			}
			if ( isset($settings['dethemekit_carousel_pointer']) ){
				$element->add_render_attribute( '_wrapper', array(
						'class' => 'dethemekit_show_pointer',
				) );
			}
			if ( isset($settings['dethemekit_carousel_pointer2']) ){
				$element->add_render_attribute( '_wrapper', array(
						'class' => 'ricooke',
				) );
				?>
				<script>
					$(document).ready( function() {
						$('.ricooke').addClass('coco');
					});
				</script>
				<div class='ricozefa'></div>
				<?php 
			}

			if ( isset($settings['dethemekit_de_carousel_tab_background_color']) ){
				$element->add_render_attribute( '_wrapper', array(
						'class' => 'testricor',
				) );
			}

			

			if ( 'column' !== $type ) {
				return;
			}

			if ( isset( $settings['de_sticky_column_sticky_enable'] ) ) {
				$column_settings = array(
					'id'            => $data['id'],
					'sticky'        => filter_var( $settings['de_sticky_column_sticky_enable'], FILTER_VALIDATE_BOOLEAN ),
					'topSpacing'    => isset( $settings['de_sticky_column_sticky_top_spacing'] ) ? $settings['de_sticky_column_sticky_top_spacing'] : 50,
					'bottomSpacing' => isset( $settings['de_sticky_column_sticky_bottom_spacing'] ) ? $settings['de_sticky_column_sticky_bottom_spacing'] : 50,
					'stickyOn'      => isset( $settings['de_sticky_column_sticky_enable_on'] ) ? $settings['de_sticky_column_sticky_enable_on'] : array( 'desktop', 'tablet' ),
				);

				if ( filter_var( $settings['de_sticky_column_sticky_enable'], FILTER_VALIDATE_BOOLEAN ) ) {

					$element->add_render_attribute( '_wrapper', array(
						'class' => 'de-sticky-column-sticky',
						'data-de-sticky-column-settings' => json_encode( $column_settings ),
					) );
				}

				$this->columns_data[ $data['id'] ] = $column_settings;
			}

			if ( !empty( $settings['dethemekit_carousel_parent'] ) && empty( $settings['dethemekit_carousel_child_name']) ) {

				// TRANSITION FROM DE CAROUSEL NON MULTI
				$element->add_render_attribute( '_wrapper', 'class', 'dethemekit_child_de_carousel_1 kop1'.$settings['dethemekit_carousel_child_name'] );
				$settings['dethemekit_carousel_child_name'] = 'de_carousel_1';
				$settings['dethemekit_carousel_parent'] = "";
			}

			if ( isset( $settings['dethemekit_carousel_childs'] ) ) {
				$element->add_render_attribute( '_wrapper', 'class', 'dethemekit_parent_de_carousel_1' );
				$settings['dethemekit_carousel_parent_name'] = 'de_carousel_1';
				$settings['dethemekit_carousel_childs'] = "";
			}
			
		}

		/**
		 * Add sticky controls to section settings.
		 *
		 * @param object $element Element instance.
		 * @param array  $args    Element arguments.
		 */
		public function add_section_sticky_controls( $element, $args ) {
			$element->start_controls_section(
				'de_sticky_section_sticky_settings',
				array(
					'label' => esc_html__( 'De Sticky', 'desticky-for-elementor' ),
					'tab'   => Elementor\Controls_Manager::TAB_ADVANCED,
				)
			);

			$element->add_control(
				'de_sticky_section_sticky',
				array(
					'label'   => esc_html__( 'Sticky Section', 'desticky-for-elementor' ),
					'type'    => Elementor\Controls_Manager::SWITCHER,
					'default' => '',
					'frontend_available' => true,
				)
			);

			$element->add_control(
				'de_sticky_section_sticky_visibility',
				array(
					'label'       => esc_html__( 'Sticky Section Visibility', 'desticky-for-elementor' ),
					'type'        => Elementor\Controls_Manager::SELECT2,
					'multiple'    => true,
					'label_block' => true,
					'default' => array( 'desktop', 'tablet', 'mobile' ),
					'options' => array(
						'desktop' => esc_html__( 'Desktop', 'desticky-for-elementor' ),
						'tablet'  => esc_html__( 'Tablet', 'desticky-for-elementor' ),
						'mobile'  => esc_html__( 'Mobile', 'desticky-for-elementor' ),
					),
					'condition' => array(
						'de_sticky_section_sticky' => 'yes',
					),
					'frontend_available' => true,
				)
			);

			$element->add_control(
				'de_sticky_section_sticky_z_index',
				array(
					'label'       => esc_html__( 'Z-index', 'desticky-for-elementor' ),
					'type'        => Elementor\Controls_Manager::NUMBER,
					'placeholder' => 1100,
					'min'         => 1,
					'max'         => 10000,
					'step'        => 1,
					'selectors'   => array(
						'{{WRAPPER}}.de-sticky-section-sticky--stuck' => 'z-index: {{VALUE}};',
					),
					'condition' => array(
						'de_sticky_section_sticky' => 'yes',
					),
				)
			);

			$element->add_control(
				'de_sticky_section_sticky_max_width',
				array(
					'label' => esc_html__( 'Max Width (px)', 'desticky-for-elementor' ),
					'type'  => Elementor\Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 500,
							'max' => 2000,
						),
					),
					'selectors'   => array(
						'{{WRAPPER}}.de-sticky-section-sticky--stuck' => 'max-width: {{SIZE}}{{UNIT}};',
					),
					'condition' => array(
						'de_sticky_section_sticky' => 'yes',
					),
				)
			);

			$element->add_responsive_control(
				'de_sticky_section_sticky_style_heading',
				array(
					'label'     => esc_html__( 'Sticky Section Style', 'desticky-for-elementor' ),
					'type'      => Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => array(
						'de_sticky_section_sticky' => 'yes',
					),
				)
			);

			$element->add_responsive_control(
				'de_sticky_section_sticky_margin',
				array(
					'label'      => esc_html__( 'Margin', 'desticky-for-elementor' ),
					'type'       => Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'allowed_dimensions' => 'vertical',
					'placeholder' => array(
						'top'    => '',
						'right'  => 'auto',
						'bottom' => '',
						'left'   => 'auto',
					),
					'selectors' => array(
						'{{WRAPPER}}.de-sticky-section-sticky--stuck' => 'margin-top: {{TOP}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
					),
					'condition' => array(
						'de_sticky_section_sticky' => 'yes',
					),
				)
			);

			$element->add_responsive_control(
				'de_sticky_section_sticky_padding',
				array(
					'label'      => esc_html__( 'Padding', 'desticky-for-elementor' ),
					'type'       => Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em', '%' ),
					'selectors'  => array(
						'{{WRAPPER}}.de-sticky-section-sticky--stuck' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'condition' => array(
						'de_sticky_section_sticky' => 'yes',
					),
				)
			);

			$element->add_group_control(
				Elementor\Group_Control_Background::get_type(),
				array(
					'name'      => 'de_sticky_section_sticky_background',
					'selector'  => '{{WRAPPER}}.de-sticky-section-sticky--stuck',
					'condition' => array(
						'de_sticky_section_sticky' => 'yes',
					),
				)
			);

			$element->add_group_control(
				Elementor\Group_Control_Box_Shadow::get_type(),
				array(
					'name'      => 'de_sticky_section_sticky_box_shadow',
					'selector'  => '{{WRAPPER}}.de-sticky-section-sticky--stuck',
					'condition' => array(
						'de_sticky_section_sticky' => 'yes',
					),
				)
			);

			$element->add_control(
				'de_sticky_section_sticky_transition',
				array(
					'label'   => esc_html__( 'Transition Duration', 'desticky-for-elementor' ),
					'type'    => Elementor\Controls_Manager::SLIDER,
					'default' => array(
						'size' => 0.1,
					),
					'range' => array(
						'px' => array(
							'max'  => 3,
							'step' => 0.1,
						),
					),
					'selectors' => array(
						'{{WRAPPER}}.de-sticky-section-sticky--stuck.de-sticky-transition-in, {{WRAPPER}}.de-sticky-section-sticky--stuck.de-sticky-transition-out' => 'transition: margin {{SIZE}}s, padding {{SIZE}}s, background {{SIZE}}s, box-shadow {{SIZE}}s',
					),
					'condition' => array(
						'de_sticky_section_sticky' => 'yes',
					),
				)
			);

			$element->end_controls_section();
		}

		/**
		 * Enqueue scripts
		 *
		 * @return void
		 */
		public function enqueue_scripts() {

			wp_enqueue_script(
				'de-resize-sensor',
				DETHEMEKIT_ADDONS_URL . 'assets/js/lib/ResizeSensor.min.js' ,
				array( 'jquery' ),
				'1.7.0',
				true
			);

			wp_enqueue_script(
				'de-sticky-sidebar',
				DETHEMEKIT_ADDONS_URL . 'assets/js/lib/sticky-sidebar/sticky-sidebar.min.js' ,
				array( 'jquery', 'de-resize-sensor' ),
				'3.3.1',
				true
			);

			wp_enqueue_script(
				'jsticky',
				DETHEMEKIT_ADDONS_URL . 'assets/js/lib/jsticky/jquery.jsticky.js' ,
				array( 'jquery' ),
				'1.1.0',
				true
			);

			de_sticky_assets()->elements_data['columns'] = $this->columns_data;
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return object
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}
}

/**
 * Returns instance of De_Sticky_Element_Extension
 *
 * @return object
 */
function de_sticky_element_extension() {
	return De_Sticky_Element_Extension::get_instance();
}
