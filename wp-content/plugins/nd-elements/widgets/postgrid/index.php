<?php


//START ELEMENT POST GRID
class nd_elements_postgrid_element extends \Elementor\Widget_Base {

	public function get_name() { return 'postgrid'; }
	public function get_title() { return __( 'Post Grid', 'nd-elements' ); }
	public function get_icon() { return 'fa fa-book'; }
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
	      'postgrid_layout',
	      [
	        'label' => __( 'Layout', 'nd-elements' ),
	        'type' => \Elementor\Controls_Manager::SELECT,
	        'default' => 'layout-1',
	        'options' => [
	          'layout-1' => __( 'Layout 1', 'nd-elements' ),
	          'layout-2' => __( 'Layout 2', 'nd-elements' ),
	          'layout-3' => __( 'Layout 3', 'nd-elements' ),
	          'layout-4' => __( 'Layout 4', 'nd-elements' ),
	          'layout-5' => __( 'Layout 5', 'nd-elements' ),
	          'layout-6' => __( 'Layout 6', 'nd-elements' ),
	          'layout-7' => __( 'Layout 7', 'nd-elements' ),
	        ],
	      ]
	    );


		$this->add_control(
			'postgrid_width',
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

		$this->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail', // // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
				'exclude' => [ 'custom' ],
				'include' => [],
				'default' => 'large',
			]
		);

		$this->add_control(
			'postgrid_order',
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
			'postgrid_orderby',
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
			'postgrid_qnt',
			[
				'label' => __( 'Posts Per Page', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => -1,
				'max' => 20,
				'step' => 1,
				'default' => 3,
			]
		);


		$this->add_control(
			'postgrid_id',
			[
				'label' => __( 'ID', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 9000,
				'step' => 1,
			]
		);


		$this->add_control(
			'postgrid_category',
			[
				'label' => __( 'Category Slug', 'nd-elements' ),
				'type' => \Elementor\Controls_Manager::TEXT,
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
			'postgrid_color',
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
  		wp_enqueue_script('nd_elements_postgrid_js', esc_url( plugins_url('js/postgrid.js', __FILE__ )) );
		
  		//get datas
  		$nd_elements_settings = $this->get_settings_for_display();
		$nd_elements_postgrid_order = $nd_elements_settings['postgrid_order'];
		$nd_elements_postgrid_orderby = $nd_elements_settings['postgrid_orderby'];
		$postgrid_qnt = $nd_elements_settings['postgrid_qnt'];
		$postgrid_width = $nd_elements_settings['postgrid_width'];
		$postgrid_color = $nd_elements_settings['postgrid_color'];
		$postgrid_layout = $nd_elements_settings['postgrid_layout'];
		$postgrid_image_size = $nd_elements_settings['thumbnail_size'];
		$nd_elements_postgrid_id = $nd_elements_settings['postgrid_id'];
		$postgrid_category = $nd_elements_settings['postgrid_category'];

		//default values
		if ($postgrid_width == '') { $postgrid_width = "nd_elements_width_100_percentage"; }
		if ($postgrid_layout == '') { $postgrid_layout = "layout-1"; }
		if ($postgrid_qnt == '') { $postgrid_qnt = 3; }
		if ($nd_elements_postgrid_order == '') { $nd_elements_postgrid_order = 'DESC'; }
		if ($nd_elements_postgrid_orderby == '') { $nd_elements_postgrid_orderby = 'date'; }
		if ($postgrid_color == '') { $postgrid_color = '#000000'; }
		if ($postgrid_image_size == '') { $postgrid_image_size = 'large'; }

		//args
		$args = array(
			'post_type' => 'post',
			'posts_per_page' => $postgrid_qnt,
			'order' => $nd_elements_postgrid_order,
			'orderby' => $nd_elements_postgrid_orderby,
			'p' => $nd_elements_postgrid_id,
			'category_name' => $postgrid_category
		);
		$the_query = new WP_Query( $args );

		//check with realpath
  		$nd_elements_layout_selected = dirname( __FILE__ ).'/layout/'.$postgrid_layout.'.php';
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
				'loading' => [],
				'width' => [],
				'height' => [],
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
			'strong'      => [
				'class' => [],
				'style' => [],
			],
			'p'      => [
				'stye' => [],
				'class' => [],
			],
		];

		echo wp_kses( $nd_elements_result, $nd_elements_allowed_html );

	}
	//END RENDER


}
//END ELEMENT POST GRID
