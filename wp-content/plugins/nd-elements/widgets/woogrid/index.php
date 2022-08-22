<?php


//START ELEMENT POST GRID
class nd_elements_woogrid_element extends \Elementor\Widget_Base {

	public function get_name() { return 'woogrid'; }
	public function get_title() { return __( 'WooCommerce Grid', 'nd-elements' ); }
	public function get_icon() { return 'fa fa-shopping-cart'; }
	public function get_categories() { return [ 'nd-elements' ]; }

	
	/*START CONTROLS*/
	protected function _register_controls() {

	
		/*Create Tab*/
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Main Options', 'nd-elements' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);


		$this->add_control(
	      'woogrid_layout',
	      [
	        'label' => __( 'Layout', 'nd-elements' ),
	        'type' => \Elementor\Controls_Manager::SELECT,
	        'default' => 'layout-1',
	        'options' => [
	          'layout-1'  => __( 'Layout 1', 'nd-elements' ),
	          'layout-2' => __( 'Layout 2', 'nd-elements' ),
	          'layout-3' => __( 'Layout 3', 'nd-elements' ),
	          'layout-4' => __( 'Layout 4', 'nd-elements' ),
	        ],
	      ]
	    );


		$this->add_control(
			'woogrid_width',
			[
				'label' => __( 'Width', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'nd_elements_width_100_percentage',
				'options' => [
					'nd_elements_width_100_percentage'  => __( '1 Column', 'nd-elements' ),
					'nd_elements_width_50_percentage' => __( '2 Columns', 'nd-elements' ),
					'nd_elements_width_33_percentage'  => __( '3 Columns', 'nd-elements' ),
					'nd_elements_width_25_percentage' => __( '4 Columns', 'nd-elements' ),
				],
			]
		);


		$this->add_control(
			'woogrid_order',
			[
				'label' => __( 'Order', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => [
					'DESC'  => __( 'DESC', 'nd-elements' ),
					'ASC' => __( 'ASC', 'nd-elements' ),
				],
			]
		);


		$this->add_control(
			'woogrid_orderby',
			[
				'label' => __( 'Order By', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'ID'  => __( 'ID', 'nd-elements' ),
					'author' => __( 'Author', 'nd-elements' ),
					'title'  => __( 'Title', 'nd-elements' ),
					'name' => __( 'Name', 'nd-elements' ),
					'type'  => __( 'Type', 'nd-elements' ),
					'date' => __( 'Date', 'nd-elements' ),
					'modified'  => __( 'Modified', 'nd-elements' ),
					'rand' => __( 'Random', 'nd-elements' ),
					'comment_count'  => __( 'Comment Count', 'nd-elements' ),
				],
			]
		);



		$this->add_control(
			'woogrid_qnt',
			[
				'label' => __( 'Posts Per Page', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => -1,
				'max' => 20,
				'step' => 1,
				'default' => 3,
			]
		);
		

		$this->end_controls_section();



		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Style', 'nd-elements' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_control(
			'woogrid_color',
			[
				'label' => __( 'Color', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000000',
			]
		);

		$this->end_controls_section();

	}
	//END CONTROLS


 
	/*START RENDER*/
	protected function render() {

		$nd_elements_result = '';

		//add script
		wp_enqueue_script('masonry');
  		wp_enqueue_script('nd_elements_woogrid_js', esc_url( plugins_url('js/woogrid.js', __FILE__ )) );

  		//get datas
  		$nd_elements_settings = $this->get_settings_for_display();
		$nd_elements_woogrid_order = $nd_elements_settings['woogrid_order'];
		$nd_elements_woogrid_orderby = $nd_elements_settings['woogrid_orderby'];
		$woogrid_qnt = $nd_elements_settings['woogrid_qnt'];
		$woogrid_width = $nd_elements_settings['woogrid_width'];
		$woogrid_color = $nd_elements_settings['woogrid_color'];
		$woogrid_layout = $nd_elements_settings['woogrid_layout'];

		//default values
		if ($woogrid_width == '') { $woogrid_width = "nd_elements_width_100_percentage"; }
		if ($woogrid_layout == '') { $woogrid_layout = "layout-1"; }
		if ($woogrid_qnt == '') { $woogrid_qnt = 3; }
		if ($nd_elements_woogrid_order == '') { $nd_elements_woogrid_order = 'DESC'; }
		if ($nd_elements_woogrid_orderby == '') { $nd_elements_woogrid_orderby = 'date'; }
		if ($woogrid_color == '') { $woogrid_color = '#282828'; }

		//args
		$args = array(
			'post_type' => 'product',
			'posts_per_page' => $woogrid_qnt,
			'order' => $nd_elements_woogrid_order,
			'orderby' => $nd_elements_woogrid_orderby,
		);
		$the_query = new WP_Query( $args );

		//check with realpath
  		$nd_elements_layout_selected = dirname( __FILE__ ).'/layout/'.$woogrid_layout.'.php';
  		include realpath($nd_elements_layout_selected);

  		wp_reset_postdata();

  		$nd_elements_allowed_html = [
		    'div'      => [
				'class' => [],
				'style' => [],
			],
			'a'      => [
				'href' => [],
				'class' => [],
				'style' => [],
			],
			'img'      => [
				'class' => [],
				'alt' => [],
				'src' => [],
				'style' => [],
			],
			'h1'      => [
				'class' => [],
				'style' => [],
			],
			'h2'      => [
				'class' => [],
				'style' => [],
			],
			'h3'      => [
				'class' => [],
				'style' => [],
			],
			'h4'      => [
				'class' => [],
				'style' => [],
			],
			'h5'      => [
				'class' => [],
				'style' => [],
			],
			'h6'      => [
				'class' => [],
				'style' => [],
			],
			'p'      => [
				'class' => [],
				'style' => [],
			],
			'strong'      => [],
		];

		echo wp_kses( $nd_elements_result, $nd_elements_allowed_html );

	}
	//END RENDER


}
//END ELEMENT POST GRID
