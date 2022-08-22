<?php
if (!class_exists('TFWooProductGrid_Widget_Free')) {
	class TFWooProductGrid_Widget_Free extends \Elementor\Widget_Base {

		public function get_name() {
	        return 'tfwooproductgrid';
	    }
	    
	    public function get_title() {
	        return esc_html__( 'TF Woo Product Grid', 'themesflat-addons-for-elementor' );
	    }

	    public function get_icon() {
	        return 'eicon-gallery-grid';
	    }	    
	    
	    public function get_categories() {
	        return [ 'themesflat_addons_wc' ];
	    }

	    public function get_style_depends(){
		    return ['tf-font-awesome','tf-regular','owl-carousel','tf-style'];
	  	}
	    public function get_script_depends(){
		    return ['imagesloaded-pkgd', 'jquery-isotope', 'owl-carousel', 'tf-main'];
		}	

		protected function register_controls() {
	        // Start Setting        
				$this->start_controls_section( 
					'section_product',
		            [
		                'label' => esc_html__('Product Settings', 'themesflat-addons-for-elementor'),
		            ]
		        );

		        $this->add_control( 
					'product_categories',
					[
						'label' => esc_html__( 'Categories', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SELECT2,
						'options' => ThemesFlat_Addon_For_Elementor_Free::tf_get_taxonomies_product(),
						'label_block' => true,
		                'multiple' => true
					]
				);

		        $this->add_control( 
					'product_per_page',
		            [
		                'label' => esc_html__( 'Number Show Products', 'themesflat-addons-for-elementor' ),
		                'type' => \Elementor\Controls_Manager::NUMBER,
		                'default' => '9',
		            ]
		        );	

		        $this->add_control(
	                'product_product_filter',
	                [
	                    'label' => esc_html__( 'Filter By', 'themesflat-addons-for-elementor' ),
	                    'type' => \Elementor\Controls_Manager::SELECT,
	                    'default' => 'recent',
	                    'options' => [
	                        'recent' => esc_html__( 'Recent Products', 'themesflat-addons-for-elementor' ),
	                        'featured' => esc_html__( 'Featured Products', 'themesflat-addons-for-elementor' ),
	                        'best_selling' => esc_html__( 'Best Selling Products', 'themesflat-addons-for-elementor' ),
	                        'sale' => esc_html__( 'Sale Products', 'themesflat-addons-for-elementor' ),
	                        'top_rated' => esc_html__( 'Top Rated Products', 'themesflat-addons-for-elementor' ),
	                        'mixed_order' => esc_html__( 'Mixed order Products', 'themesflat-addons-for-elementor' ),
	                    ],
	                ]
	            );

	            $this->add_control(
	                'orderby',
	                [
	                    'label' => esc_html__( 'Orderby', 'themesflat-addons-for-elementor' ),
	                    'type' => \Elementor\Controls_Manager::SELECT,
	                    'default' => 'date',
	                    'options' => [
	                        'ID'            => esc_html__('ID','themesflat-addons-for-elementor'),
	                        'date'          => esc_html__('Date','themesflat-addons-for-elementor'),
	                        'name'          => esc_html__('Name','themesflat-addons-for-elementor'),
	                        'title'         => esc_html__('Title','themesflat-addons-for-elementor'),
	                        'comment_count' => esc_html__('Comment count','themesflat-addons-for-elementor'),
	                        'rand'          => esc_html__('Random','themesflat-addons-for-elementor'),
	                    ]
	                ]
	            );

	            $this->add_control(
	                'order',
	                [
	                    'label' => esc_html__( 'Order', 'themesflat-addons-for-elementor' ),
	                    'type' => \Elementor\Controls_Manager::SELECT,
	                    'default' => 'DESC',
	                    'options' => [
	                        'DESC'  => esc_html__('Descending','themesflat-addons-for-elementor'),
	                        'ASC'   => esc_html__('Ascending','themesflat-addons-for-elementor'),
	                    ]
	                ]
	            );

		        $this->add_control( 
		        	'product_layout',
					[
						'label' => esc_html__( 'Layout', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SELECT,
						'default' => 'layout-1',
						'options' => [
							'layout-1' => esc_html__( 'Layout 1', 'themesflat-addons-for-elementor' ),
							'layout-2' => esc_html__( 'Layout 2', 'themesflat-addons-for-elementor' ),
							'layout-3' => esc_html__( 'Layout 3', 'themesflat-addons-for-elementor' ),
						]
					]
				);

				$this->add_control(
	                'product_column',
	                [
	                    'label' => esc_html__( 'Columns', 'themesflat-addons-for-elementor' ),
	                    'type' => \Elementor\Controls_Manager::SELECT,
	                    'default' => '4',
	                    'options' => [
	                        '1' => esc_html__( '1', 'themesflat-addons-for-elementor' ),
	                        '2' => esc_html__( '2', 'themesflat-addons-for-elementor' ),
	                        '3' => esc_html__( '3', 'themesflat-addons-for-elementor' ),
	                        '4' => esc_html__( '4', 'themesflat-addons-for-elementor' ),
	                        '5' => esc_html__( '5', 'themesflat-addons-for-elementor' ),
	                        '6' => esc_html__( '6', 'themesflat-addons-for-elementor' ),
	                    ]
	                ]
	            );	            

	            $this->add_control(
					'carousel',
					[
						'label' => esc_html__( 'Carousel', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SWITCHER,
						'label_on' => esc_html__( 'Yes', 'themesflat-addons-for-elementor' ),
						'label_off' => esc_html__( 'No', 'themesflat-addons-for-elementor' ),
						'return_value' => 'yes',
						'default' => 'no',
					]
				); 

				$this->add_control(
					'pagination',
					[
						'label' => esc_html__( 'Pagination', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SWITCHER,
						'label_on' => esc_html__( 'Yes', 'themesflat-addons-for-elementor' ),
						'label_off' => esc_html__( 'No', 'themesflat-addons-for-elementor' ),
						'return_value' => 'yes',
						'default' => 'no',
						'condition' => [
							'carousel!' => 'yes'
						]
					]
				); 

		        $this->end_controls_section();
	        // /.End Setting

		    // Start Image        
				$this->start_controls_section( 
					'section_product_image',
		            [
		                'label' => esc_html__('Image', 'themesflat-addons-for-elementor'),
		            ]
		        );	 

				$this->add_group_control( 
					\Elementor\Group_Control_Image_Size::get_type(),
					[
						'name' => 'thumbnail',
						'default' => 'large',
					]
				);

		        $this->end_controls_section();
	        // /.End Image

	        // Start Meta        
				$this->start_controls_section( 
					'section_product_meta',
		            [
		                'label' => esc_html__('Meta', 'themesflat-addons-for-elementor'),
		            ]
		        );	

		        $this->add_control(
					'product_sale',
					[
						'label' => esc_html__( 'Sale', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SWITCHER,
						'label_on' => esc_html__( 'Show', 'themesflat-addons-for-elementor' ),
						'label_off' => esc_html__( 'Hide', 'themesflat-addons-for-elementor' ),
						'return_value' => 'yes',
						'default' => 'yes',
					]
				); 

				$this->add_control(
					'custom_sale',
					[
						'label' => esc_html__( 'Custom Sale', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SWITCHER,
						'label_on' => esc_html__( 'Yes', 'themesflat-addons-for-elementor' ),
						'label_off' => esc_html__( 'No', 'themesflat-addons-for-elementor' ),
						'return_value' => 'yes',
						'default' => 'no',
						'condition' => [
							'product_sale' => 'yes',
						]
					]
				);

				$this->add_control(
	                'sale_style',
	                [
	                    'label' => esc_html__( 'Sale Style', 'themesflat-addons-for-elementor' ),
	                    'type' => \Elementor\Controls_Manager::SELECT,
	                    'default' => 'sale_text',
	                    'options' => [
	                        'sale_text'  => esc_html__('Sale Text','themesflat-addons-for-elementor'),
	                        'sale_percent'   => esc_html__('Sale Percent','themesflat-addons-for-elementor'),
	                    ],
	                    'condition' => [
	                    	'product_sale' => 'yes',
	                    	'custom_sale' => 'yes',
	                    ]
	                ]
	            );

	            $this->add_control(
					'enter_sale_text',
					[
						'label' => esc_html__( 'Sale Text', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::TEXT,
						'default' => esc_html__( 'Sale!', 'themesflat-addons-for-elementor' ),
						'condition' => [
							'product_sale' => 'yes',
	                    	'custom_sale' => 'yes',
	                    	'sale_style' => 'sale_text',
	                    ]
					]
				);

				$this->add_control(
					'prefix_sale_percent',
					[
						'label' => esc_html__( 'Prefix Percent', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::TEXT,
						'default' => esc_html__( '-', 'themesflat-addons-for-elementor' ),
						'condition' => [
							'product_sale' => 'yes',
	                    	'custom_sale' => 'yes',
	                    	'sale_style' => 'sale_percent',
	                    ]
					]
				);

				$this->add_control(
					'badge',
					[
						'label' => esc_html__( 'Badge', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SWITCHER,
						'label_on' => esc_html__( 'Show', 'themesflat-addons-for-elementor' ),
						'label_off' => esc_html__( 'Hide', 'themesflat-addons-for-elementor' ),
						'return_value' => 'yes',
						'default' => 'yes',
					]
				); 

		        $this->end_controls_section();
	        // /.End Meta

	        // Start Filter Bar        
				$this->start_controls_section( 
					'section_posts_filter',
		            [
		                'label' => esc_html__('Filter Bar', 'themesflat-addons-for-elementor'),
		            ]
		        );

		        $this->add_control(
					'filter_bar',
					[
						'label' => esc_html__( 'Filter Bar', 'themesflat-addons-for-elementor' ),
						'description' => esc_html__( 'If you turn on the filter bar, you should set number show products to (-1)', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SWITCHER,
						'label_on' => esc_html__( 'Yes', 'themesflat-addons-for-elementor' ),
						'label_off' => esc_html__( 'No', 'themesflat-addons-for-elementor' ),
						'return_value' => 'yes',
						'default' => 'no',
					]
				);	

				$this->add_control(
					'filter_type',
					[
						'label' => esc_html__( 'Filter Type', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SELECT,
						'default' => 'filter_category',
						'options' => [
							'filter_badge'  => esc_html__( 'Filter Badge', 'themesflat-addons-for-elementor' ),
							'filter_category' => esc_html__( 'Filter Category', 'themesflat-addons-for-elementor' ),
						],
						'condition' => [
							'filter_bar' => 'yes',
						],
					]
				);

				$this->add_control(
					'filter_product_categories',
					[
						'label' => esc_html__( 'Filter Categories', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::TEXT,
						'label_block' => true,
						'default' => '',
						'placeholder' => esc_html__( 'Categories Order Split By , (ex: 1,2,3)', 'themesflat-addons-for-elementor' ),
						'condition' => [
							'filter_bar' => 'yes',
							'filter_type' => 'filter_category',
						],
					]
				);  

				$this->add_control( 
					'toolbar_control',
					[
						'label' => esc_html__( 'Toolbar Control', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SWITCHER,
						'label_on' => esc_html__( 'On', 'themesflat-addons-for-elementor' ),
						'label_off' => esc_html__( 'Off', 'themesflat-addons-for-elementor' ),
						'return_value' => 'yes',
						'default' => 'yes',
						'condition' => [
							'filter_bar' => 'yes',
							'filter_type' => 'filter_badge'
						],
					]
				);

		        $this->end_controls_section();
	        // /.End Filter Bar 

	        // Start Carousel        
				$this->start_controls_section( 
					'section_product_carousel',
		            [
		                'label' => esc_html__('Carousel', 'themesflat-addons-for-elementor'),
		                'condition' => [
		                	'carousel' => 'yes',
		                ]
		            ]
		        );	 

				$this->add_control( 
					'carousel_loop',
					[
						'label' => esc_html__( 'Loop', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SWITCHER,
						'label_on' => esc_html__( 'On', 'themesflat-addons-for-elementor' ),
						'label_off' => esc_html__( 'Off', 'themesflat-addons-for-elementor' ),
						'return_value' => 'yes',
						'default' => 'yes',				
					]
				);

				$this->add_control( 
					'carousel_auto',
					[
						'label' => esc_html__( 'Auto Play', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SWITCHER,
						'label_on' => esc_html__( 'On', 'themesflat-addons-for-elementor' ),
						'label_off' => esc_html__( 'Off', 'themesflat-addons-for-elementor' ),
						'return_value' => 'yes',
						'default' => 'yes',				
					]
				);	

				$this->add_control(
					'carousel_spacer',
					[
						'label' => esc_html__( 'Spacer', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::NUMBER,
						'min' => 0,
						'max' => 100,
						'step' => 1,
						'default' => 30,				
					]
				);

				$this->add_control( 
		        	'carousel_column_desk',
					[
						'label' => esc_html__( 'Columns Desktop', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SELECT,
						'default' => '4',
						'options' => [
							'1' => esc_html__( '1', 'themesflat-addons-for-elementor' ),
							'2' => esc_html__( '2', 'themesflat-addons-for-elementor' ),
							'3' => esc_html__( '3', 'themesflat-addons-for-elementor' ),
							'4' => esc_html__( '4', 'themesflat-addons-for-elementor' ),
							'5' => esc_html__( '5', 'themesflat-addons-for-elementor' ),
							'6' => esc_html__( '6', 'themesflat-addons-for-elementor' ),
						],				
					]
				);

				$this->add_control( 
		        	'carousel_column_tablet',
					[
						'label' => esc_html__( 'Columns Tablet', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SELECT,
						'default' => '2',
						'options' => [
							'1' => esc_html__( '1', 'themesflat-addons-for-elementor' ),
							'2' => esc_html__( '2', 'themesflat-addons-for-elementor' ),
							'3' => esc_html__( '3', 'themesflat-addons-for-elementor' ),
							'4' => esc_html__( '4', 'themesflat-addons-for-elementor' ),
							'5' => esc_html__( '5', 'themesflat-addons-for-elementor' ),
							'6' => esc_html__( '6', 'themesflat-addons-for-elementor' ),
						],				
					]
				);

				$this->add_control( 
		        	'carousel_column_mobile',
					[
						'label' => esc_html__( 'Columns Mobile', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SELECT,
						'default' => '1',
						'options' => [
							'1' => esc_html__( '1', 'themesflat-addons-for-elementor' ),
							'2' => esc_html__( '2', 'themesflat-addons-for-elementor' ),
							'3' => esc_html__( '3', 'themesflat-addons-for-elementor' ),
							'4' => esc_html__( '4', 'themesflat-addons-for-elementor' ),
							'5' => esc_html__( '5', 'themesflat-addons-for-elementor' ),
							'6' => esc_html__( '6', 'themesflat-addons-for-elementor' ),
						],				
					]
				);		

		        $this->end_controls_section();
	    	// /.End Carousel

		    // Start Carousel Arrow        
				$this->start_controls_section( 
					'section_arrow',
		            [
		                'label' => esc_html__('Carousel Arrow', 'themesflat-addons-for-elementor'),
		                'condition' => [
		                	'carousel' => 'yes',
		                ]
		            ]
		        );

		        $this->add_control( 
					'carousel_arrow',
					[
						'label' => esc_html__( 'Arrow', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SWITCHER,
						'label_on' => esc_html__( 'Show', 'themesflat-addons-for-elementor' ),
						'label_off' => esc_html__( 'Hide', 'themesflat-addons-for-elementor' ),
						'return_value' => 'yes',
						'default' => 'yes',				
						'description'	=> 'Just show when you have two slide',
					]
				);

		        $this->end_controls_section();
	        // /.End Carousel Arrow

		    // Start Carousel Bullets        
				$this->start_controls_section( 
					'section_bullets',
		            [
		                'label' => esc_html__('Carousel Bullets', 'themesflat-addons-for-elementor'),
		                'condition' => [
		                	'carousel' => 'yes',
		                ]
		            ]
		        );

				$this->add_control( 
					'carousel_bullets',
		            [
		                'label'         => esc_html__( 'Bullets', 'themesflat-addons-for-elementor' ),
		                'type'          => \Elementor\Controls_Manager::SWITCHER,
		                'label_on'      => esc_html__( 'Show', 'themesflat-addons-for-elementor' ),
		                'label_off'     => esc_html__( 'Hide', 'themesflat-addons-for-elementor' ),
		                'return_value'  => 'yes',
		                'default'       => 'yes',
		            ]
		        );        

		        $this->end_controls_section();
	        // /.End Carousel Bullets         

	        // Start Wishlist
		        if ( class_exists( 'YITH_WCWL' ) ) {
		        	$this->start_controls_section( 
						'section_product_wishlist',
			            [
			                'label' => esc_html__('Wishlist', 'themesflat-addons-for-elementor'),
			            ]
			        );
		        	$this->add_control(
						'wishlist',
						[
							'label' => esc_html__( 'Wishlist', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::SWITCHER,
							'label_on' => esc_html__( 'Show', 'themesflat-addons-for-elementor' ),
							'label_off' => esc_html__( 'Hide', 'themesflat-addons-for-elementor' ),
							'return_value' => 'yes',
							'default' => 'no',
						]
					); 
					$this->end_controls_section();
		        }
	        // /.End Wishlist 

	        // Start Compare 
		        if ( class_exists( 'YITH_Woocompare' ) ) {
		        	$this->start_controls_section( 
						'section_product_compare',
			            [
			                'label' => esc_html__('Compare', 'themesflat-addons-for-elementor'),
			            ]
			        );
		        	$this->add_control(
						'compare',
						[
							'label' => esc_html__( 'Compare', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::SWITCHER,
							'label_on' => esc_html__( 'Show', 'themesflat-addons-for-elementor' ),
							'label_off' => esc_html__( 'Hide', 'themesflat-addons-for-elementor' ),
							'return_value' => 'yes',
							'default' => 'no',
						]
					);
					$this->end_controls_section();
		        }
		    // /.End Compare

		    // Start Quick View
		        if ( class_exists( 'YITH_WCQV' ) ) {
		        	$this->start_controls_section( 
						'section_product_quickview',
			            [
			                'label' => esc_html__('Quick View', 'themesflat-addons-for-elementor'),
			            ]
			        );
		        	$this->add_control(
						'quickview',
						[
							'label' => esc_html__( 'Quick View', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::SWITCHER,
							'label_on' => esc_html__( 'Show', 'themesflat-addons-for-elementor' ),
							'label_off' => esc_html__( 'Hide', 'themesflat-addons-for-elementor' ),
							'return_value' => 'yes',
							'default' => 'no',
						]
					);
					$this->end_controls_section();
		        }
		    // /.End Quick View

	        // Start Product Style        
				$this->start_controls_section( 
					'section_product_style',
		            [
		                'label' => esc_html__('Product', 'themesflat-addons-for-elementor'),
		                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
		            ]
		        );	        

				$this->add_responsive_control(
					'product_padding',
					[
						'label' => esc_html__( 'Padding', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],		
						'default' => [
							'top' => '15',
							'right' => '15',
							'bottom' => '15',
							'left' => '15',
							'unit' => 'px',
							'isLinked' => false,
						],			
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .product-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							'{{WRAPPER}} .tf-woo-product .products' => 'margin: -{{TOP}}{{UNIT}} -{{RIGHT}}{{UNIT}} -{{BOTTOM}}{{UNIT}} -{{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'product_margin',
					[
						'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],		
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .product-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							'{{WRAPPER}} .tf-woo-product .products' => 'margin: -{{TOP}}{{UNIT}} -{{RIGHT}}{{UNIT}} -{{BOTTOM}}{{UNIT}} -{{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_control(
					'h_inner_product',
					[
						'label' => esc_html__( 'Inner Product', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_responsive_control(
					'inner_product_padding',
					[
						'label' => esc_html__( 'Padding', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],		
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .product-item .product-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);	

				$this->add_control(
					'product_align',
					[
						'label' => esc_html__( 'Alignment', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::CHOOSE,
						'options' => [
							'tf-product-left'    => [
								'title' => esc_html__( 'Left', 'themesflat-addons-for-elementor' ),
								'icon' => 'eicon-text-align-left',
							],
							'tf-product-center' => [
								'title' => esc_html__( 'Center', 'themesflat-addons-for-elementor' ),
								'icon' => 'eicon-text-align-center',
							],
							'tf-product-right' => [
								'title' => esc_html__( 'Right', 'themesflat-addons-for-elementor' ),
								'icon' => 'eicon-text-align-right',
							],
						],
						'default' => 'tf-product-center',
					]
				);	   

				$this->start_controls_tabs( 
					'product_style_tabs' 
					);
		        	$this->start_controls_tab( 'product_style_normal_tab',
						[
							'label' => esc_html__( 'Normal', 'themesflat-addons-for-elementor' ),
						] );
		        		$this->add_control(
							'product_background_color',
							[
								'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'default' => '',
								'selectors' => [
									'{{WRAPPER}} .tf-woo-product .product-inner' => 'background-color: {{VALUE}}',
								],
							]
						);

						$this->add_group_control(
							\Elementor\Group_Control_Box_Shadow::get_type(),
							[
								'name' => 'product_box_shadow',
								'label' => esc_html__( 'Box Shadow', 'themesflat-addons-for-elementor' ),
								'selector' => '{{WRAPPER}} .tf-woo-product .product-inner',
							]
						);						

						$this->add_group_control(
							\Elementor\Group_Control_Border::get_type(),
							[
								'name' => 'product_border',
								'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
								'selector' => '{{WRAPPER}} .tf-woo-product .product-inner',
							]
						);	

						$this->add_responsive_control( 
							'product_border_radius',
							[
								'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
								'type' => \Elementor\Controls_Manager::DIMENSIONS,
								'size_units' => [ 'px' , '%' ],
								'selectors' => [
									'{{WRAPPER}} .tf-woo-product .product-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
								],
							]
						); 		
					$this->end_controls_tab();

					$this->start_controls_tab( 'product_style_hover_tab',
						[
							'label' => esc_html__( 'Hover', 'themesflat-addons-for-elementor' ),
						] );
						$this->add_control(
							'product_background_color_hover',
							[
								'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'default' => '',
								'selectors' => [
									'{{WRAPPER}} .tf-woo-product .product-inner:hover' => 'background-color: {{VALUE}}',
								],
							]
						);

						$this->add_group_control(
							\Elementor\Group_Control_Box_Shadow::get_type(),
							[
								'name' => 'product_box_shadow_hover',
								'label' => esc_html__( 'Box Shadow', 'themesflat-addons-for-elementor' ),
								'selector' => '{{WRAPPER}} .tf-woo-product .product-inner:hover',
							]
						);

						$this->add_group_control(
							\Elementor\Group_Control_Border::get_type(),
							[
								'name' => 'product_border_hover',
								'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
								'selector' => '{{WRAPPER}} .tf-woo-product .product-inner:hover',
							]
						);

						$this->add_responsive_control( 
							'product_border_radius_hover',
							[
								'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
								'type' => \Elementor\Controls_Manager::DIMENSIONS,
								'size_units' => [ 'px' , '%' ],
								'selectors' => [
									'{{WRAPPER}} .tf-woo-product .product-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
								],
							]
						); 
					$this->end_controls_tab();
				$this->end_controls_tabs();   

		        $this->end_controls_section();
	        // /.End Product Style

		    // Start Image Style        
				$this->start_controls_section( 
					'section_product_image_style',
		            [
		                'label' => esc_html__('Image', 'themesflat-addons-for-elementor'),
		                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
		            ]
		        );

		        $this->add_control(
					'image_hover_gallery',
					[
						'label' => esc_html__( 'Show Title', 'themesflat-addons-for-elementor' ),
						'description' => esc_html__( 'If yes, on hover the product will change to the gallery.', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SWITCHER,
						'label_on' => esc_html__( 'Yes', 'themesflat-addons-for-elementor' ),
						'label_off' => esc_html__( 'No', 'themesflat-addons-for-elementor' ),
						'return_value' => 'yes',
						'default' => 'yes',
					]
				);

		        $this->add_control(
					'image_background_color',
					[
						'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .product-item .product-thumb' => 'background-color: {{VALUE}}',
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
	        // /.End Image Style

		    // Start Content Style        
				$this->start_controls_section( 
					'section_product_style_content',
		            [
		                'label' => esc_html__('Content', 'themesflat-addons-for-elementor'),
		                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
		            ]
		        ); 

				$this->add_responsive_control(
					'content_padding',
					[
						'label' => esc_html__( 'Padding', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],					
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .product-item .product-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'content_margin',
					[
						'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .product-item .product-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);			

				$this->start_controls_tabs( 
					'content_style_tabs' 
					);
		        	$this->start_controls_tab( 'content_style_normal_tab',
						[
							'label' => esc_html__( 'Normal', 'themesflat-addons-for-elementor' ),
						] );
		        		$this->add_control(
							'content_background_color',
							[
								'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'default' => '',
								'selectors' => [
									'{{WRAPPER}} .tf-woo-product .product-item .product-content' => 'background-color: {{VALUE}}',
								],
							]
						);					
					$this->end_controls_tab();

					$this->start_controls_tab( 'content_style_hover_tab',
						[
							'label' => esc_html__( 'Hover', 'themesflat-addons-for-elementor' ),
						] );						
						$this->add_control(
							'content_background_color_hover',
							[
								'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'default' => '',
								'selectors' => [
									'{{WRAPPER}} .tf-woo-product .product-item .product-inner:hover .product-content' => 'background-color: {{VALUE}}',
								],
							]
						);
					$this->end_controls_tab();
				$this->end_controls_tabs();  

		        $this->end_controls_section();
	    	// /.End Content Style

	        // Start Title Style        
				$this->start_controls_section( 
					'section_product_style_title',
		            [
		                'label' => esc_html__('Title', 'themesflat-addons-for-elementor'),
		                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
		            ]
		        );	

		        $this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'title_typography',
						'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
						'fields_options' => [
				            'typography' => ['default' => 'yes'],
				            'font_family' => [ 'default' => 'Oswald' ],
				            'font_size' => ['default' => ['size' => 18]],
				            'font_weight' => ['default' => 600],
				            'line_height' => ['default' => ['size' => 1.5, 'unit' => 'em',]],
				            'text_transform' => ['default' => 'uppercase'],
				        ],
						'selector' => '{{WRAPPER}} .tf-woo-product .product-item .title',
					]
				);   

				$this->add_group_control(
					\Elementor\Group_Control_Text_Shadow::get_type(),
					[
						'name' => 'title_text_shadow',
						'label' => esc_html__( 'Text Shadow', 'themesflat-addons-for-elementor' ),
						'selector' => '{{WRAPPER}} .tf-woo-product .product-item .title',
					]
				);  

				$this->add_responsive_control(
					'title_padding',
					[
						'label' => esc_html__( 'Padding', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],					
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .product-item .title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'title_margin',
					[
						'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'default' => [
							'top' => '15',
							'right' => '0',
							'bottom' => '15',
							'left' => '0',
							'unit' => 'px',
							'isLinked' => false,
						],
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .product-item .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);			

				$this->start_controls_tabs( 
					'title_style_tabs' 
					);
		        	$this->start_controls_tab( 'title_style_normal_tab',
						[
							'label' => esc_html__( 'Normal', 'themesflat-addons-for-elementor' ),
						] );
		        		$this->add_control(
							'title_color',
							[
								'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'default' => '#151515',
								'selectors' => [
									'{{WRAPPER}} .tf-woo-product .product-item .title a' => 'color: {{VALUE}}',
								],
							]
						);					
					$this->end_controls_tab();

					$this->start_controls_tab( 'title_style_hover_tab',
						[
							'label' => esc_html__( 'Hover', 'themesflat-addons-for-elementor' ),
						] );						
						$this->add_control(
							'title_color_hover',
							[
								'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'default' => '#A13502',
								'selectors' => [
									'{{WRAPPER}} .tf-woo-product .product-item .title a:hover' => 'color: {{VALUE}}',
								],
							]
						);
					$this->end_controls_tab();
				$this->end_controls_tabs();  

		        $this->end_controls_section();
	    	// /.End Title Style

		    // Start Price Style        
				$this->start_controls_section( 
					'section_product_style_price',
		            [
		                'label' => esc_html__('Price', 'themesflat-addons-for-elementor'),
		                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
		            ]
		        );

		        $this->add_control(
	                'position_price',
	                [
	                    'label' => esc_html__( 'Position Price', 'themesflat-addons-for-elementor' ),
	                    'type' => \Elementor\Controls_Manager::SELECT,
	                    'default' => 'price-default',
	                    'options' => [
	                        'price-default' => esc_html__( 'Default', 'themesflat-addons-for-elementor' ),
	                        'price-reverse' => esc_html__( 'Reverse', 'themesflat-addons-for-elementor' ),
	                    ],
	                ]
	            );           

				$this->add_group_control(
					\Elementor\Group_Control_Text_Shadow::get_type(),
					[
						'name' => 'price_text_shadow',
						'label' => esc_html__( 'Text Shadow', 'themesflat-addons-for-elementor' ),
						'selector' => '{{WRAPPER}} .tf-woo-product .product-item .price',
					]
				);  

				$this->add_responsive_control(
					'price_padding',
					[
						'label' => esc_html__( 'Padding', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .product-item .price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'price_margin',
					[
						'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .product-item .price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);	

				$this->add_responsive_control(
					'price_distance_between',
					[
						'label' => esc_html__( 'Distance Between', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 100,
								'step' => 1,
							],
						],
						'default' => [
							'unit' => 'px',
							'size' => 5,
						],
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .product-item .price del, {{WRAPPER}} .tf-woo-product .product-item .price ins' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_control(
					'h_price',
					[
						'label' => esc_html__( 'Price', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);	

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'price_typography',
						'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
						'fields_options' => [
				            'typography' => ['default' => 'yes'],
				            'font_family' => [ 'default' => 'Oswald' ],
				            'font_size' => ['default' => ['size' => 18]],
				            'font_weight' => ['default' => 400],
				            'line_height' => ['default' => ['size' => 1.5, 'unit' => 'em',]],
				        ],
						'selector' => '{{WRAPPER}} .tf-woo-product .product-item .price',
					]
				);	

				$this->add_control(
					'price_color',
					[
						'label' => esc_html__( 'Price Color', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '#A13502',
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .product-item .price ins' => 'color: {{VALUE}}',
							'{{WRAPPER}} .tf-woo-product .product-item .price' => 'color: {{VALUE}}',
						],
					]
				);

				$this->add_control(
					'h_price_sale',
					[
						'label' => esc_html__( 'Price Sale', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'price_sale_typography',
						'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
						'fields_options' => [
				            'typography' => ['default' => 'yes'],
				            'font_family' => [ 'default' => 'Oswald' ],
				            'font_size' => ['default' => ['size' => 15]],
				            'font_weight' => ['default' => 400],
				            'line_height' => ['default' => ['size' => 1.5, 'unit' => 'em',]],
				        ],
						'selector' => '{{WRAPPER}} .tf-woo-product .product-item .price del',
					]
				);

				$this->add_control(
					'price_sale_color',
					[
						'label' => esc_html__( 'Price Sale Color', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '#77706D',
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .product-item .price del' => 'color: {{VALUE}}',
						],
					]
				);

		        $this->end_controls_section();
	    	// /.End Price Style

		    // Start Meta Style        
				$this->start_controls_section( 
					'section_product_style_meta',
		            [
		                'label' => esc_html__('Meta', 'themesflat-addons-for-elementor'),
		                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
		            ]
		        );	

		        $this->add_control(
					'h_sale',
					[
						'label' => esc_html__( 'Sale', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

		        $this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'sale_typography',
						'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
						'fields_options' => [
				            'typography' => ['default' => 'yes'],
				            'font_size' => ['default' => ['size' => 14]],
				            'font_weight' => ['default' => 400],
				            'line_height' => ['default' => ['size' => 1, 'unit' => 'em',]],
				        ],
						'selector' => '{{WRAPPER}} .tf-woo-product .product-item .onsale',
					]
				);   

				$this->add_group_control(
					\Elementor\Group_Control_Text_Shadow::get_type(),
					[
						'name' => 'sale_text_shadow',
						'label' => esc_html__( 'Text Shadow', 'themesflat-addons-for-elementor' ),
						'selector' => '{{WRAPPER}} .tf-woo-product .product-item .onsale',
					]
				);  

				$this->add_responsive_control(
					'sale_padding',
					[
						'label' => esc_html__( 'Padding', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],					
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .product-item .onsale' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'sale_margin',
					[
						'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .product-item .onsale' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);			

				$this->add_control(
					'sale_color',
					[
						'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '#ffffff',
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .product-item .onsale' => 'color: {{VALUE}}',
						],
					]
				);

				$this->add_control(
					'sale_background_color',
					[
						'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '#23252a',
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .product-item .onsale' => 'background-color: {{VALUE}}',
						],
					]
				);	

				$this->add_responsive_control( 
					'sale_border_radius',
					[
						'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px' , '%' ],
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .product-item .onsale' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);   

				$this->add_control(
					'h_badge_first',
					[
						'label' => esc_html__( 'Badge First', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'badge_typography',
						'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
						'fields_options' => [
				            'typography' => ['default' => 'yes'],
				            'font_size' => ['default' => ['size' => 14]],
				            'font_weight' => ['default' => 400],
				            'line_height' => ['default' => ['size' => 1, 'unit' => 'em',]],
				        ],
						'selector' => '{{WRAPPER}} .tf-woo-product .product-item .badge.badge01',
					]
				);   

				$this->add_group_control(
					\Elementor\Group_Control_Text_Shadow::get_type(),
					[
						'name' => 'badge_text_shadow',
						'label' => esc_html__( 'Text Shadow', 'themesflat-addons-for-elementor' ),
						'selector' => '{{WRAPPER}} .tf-woo-product .product-item .badge.badge01',
					]
				);  

				$this->add_control(
					'badge_padding',
					[
						'label' => esc_html__( 'Padding', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],					
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .product-item .badge.badge01' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_control(
					'badge_margin',
					[
						'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .product-item .badge.badge01' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);			

				$this->add_control(
					'badge_color',
					[
						'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '#ffffff',
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .product-item .badge.badge01' => 'color: {{VALUE}}',
						],
					]
				);

				$this->add_control(
					'badge_background_color',
					[
						'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '#ff6962',
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .product-item .badge.badge01' => 'background-color: {{VALUE}}',
						],
					]
				);	

				$this->add_responsive_control( 
					'badge_border_radius',
					[
						'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px' , '%' ],
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .product-item .badge.badge01' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_control(
					'h_badge_last',
					[
						'label' => esc_html__( 'Badge Last', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'badge_last_typography',
						'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
						'fields_options' => [
				            'typography' => ['default' => 'yes'],
				            'font_size' => ['default' => ['size' => 14]],
				            'font_weight' => ['default' => 400],
				            'line_height' => ['default' => ['size' => 1, 'unit' => 'em',]],
				        ],
						'selector' => '{{WRAPPER}} .tf-woo-product .product-item .badge.badge02',
					]
				);   

				$this->add_group_control(
					\Elementor\Group_Control_Text_Shadow::get_type(),
					[
						'name' => 'badge_last_text_shadow',
						'label' => esc_html__( 'Text Shadow', 'themesflat-addons-for-elementor' ),
						'selector' => '{{WRAPPER}} .tf-woo-product .product-item .badge.badge02',
					]
				);  

				$this->add_control(
					'badge_last_padding',
					[
						'label' => esc_html__( 'Padding', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],					
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .product-item .badge.badge02' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_control(
					'badge_last_margin',
					[
						'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .product-item .badge.badge02' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);			

				$this->add_control(
					'badge_last_color',
					[
						'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '#ffffff',
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .product-item .badge.badge02' => 'color: {{VALUE}}',
						],
					]
				);

				$this->add_control(
					'badge_last_background_color',
					[
						'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '#ffaa65',
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .product-item .badge.badge02' => 'background-color: {{VALUE}}',
						],
					]
				);	

				$this->add_responsive_control( 
					'badge_last_border_radius',
					[
						'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px' , '%' ],
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .product-item .badge.badge02' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

		        $this->end_controls_section();
	    	// /.End Meta Style

		    // Start Action Button Style
				$this->start_controls_section( 
					'section_product_style_action_btn',
		            [
		                'label' => esc_html__('Action Button', 'themesflat-addons-for-elementor'),
		                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
		            ]
		        );

		        $this->add_control(
					'action_btn_margin',
					[
						'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'default' => [
							'top' => '15',
							'right' => '0',
							'bottom' => '0',
							'left' => '0',
							'unit' => 'px',
							'isLinked' => false,
						],
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .product-item .product-actions' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

		        $this->add_control(
					'h_addtocart',
					[
						'label' => esc_html__( 'Add To Cart', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);	

		        $this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'addtocart_typography',
						'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
						'fields_options' => [
				            'typography' => ['default' => 'yes'],
				            'font_family' => [ 'default' => 'Open Sans' ],
				            'font_size' => ['default' => ['size' => 14]],
				            'font_weight' => ['default' => 700],
				            'line_height' => ['default' => ['size' => 1, 'unit' => 'em',]],
				            'text_transform' => ['default' => 'uppercase'],
				        ],
						'selector' => '{{WRAPPER}} .tf-woo-product .product-item .product-actions > a, {{WRAPPER}} .tf-woo-product .product-item .product-actions > a.add_to_cart_button:after, {{WRAPPER}} .tf-woo-product .product-item .product-actions > a.added_to_cart:after',
					]
				);   

				$this->add_group_control(
					\Elementor\Group_Control_Text_Shadow::get_type(),
					[
						'name' => 'addtocart_text_shadow',
						'label' => esc_html__( 'Text Shadow', 'themesflat-addons-for-elementor' ),
						'selector' => '{{WRAPPER}} .tf-woo-product .product-item .product-actions > a',
					]
				);  

				$this->add_control(
					'addtocart_padding',
					[
						'label' => esc_html__( 'Padding', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'default' => [
							'top' => '14',
							'right' => '0',
							'bottom' => '14',
							'left' => '0',
							'unit' => 'px',
							'isLinked' => false,
						],					
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .product-item .product-actions > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_control(
					'addtocart_margin',
					[
						'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .product-item .product-actions > a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'addtocart_width',
					[
						'label' => esc_html__( 'Add To Cart Width', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 500,
								'step' => 1,
							],
						],
						'default' => [
							'unit' => 'px',
							'size' => 150,
						],
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .product-item .product-actions > a' => 'width: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_control(
					'addtocart_fullwidth',
					[
						'label' => esc_html__( 'Button Full Width', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SWITCHER,
						'label_on' => esc_html__( 'Yes', 'themesflat-addons-for-elementor' ),
						'label_off' => esc_html__( 'No', 'themesflat-addons-for-elementor' ),
						'return_value' => 'yes',
						'default' => 'no',
					]
				);			

				$this->start_controls_tabs( 
					'addtocart_style_tabs' 
					);
		        	$this->start_controls_tab( 'addtocart_style_normal_tab',
						[
							'label' => esc_html__( 'Normal', 'themesflat-addons-for-elementor' ),
						] );
		        		$this->add_control(
							'addtocart_color',
							[
								'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'default' => '#A13502',
								'selectors' => [
									'{{WRAPPER}} .tf-woo-product .product-item .product-actions > a' => 'color: {{VALUE}}',
								],
							]
						);
						$this->add_control(
							'addtocart_background_color',
							[
								'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'default' => '#f6ece5',
								'selectors' => [
									'{{WRAPPER}} .tf-woo-product .product-item .product-actions > a' => 'background-color: {{VALUE}}',
								],
							]
						);
						$this->add_group_control(
							\Elementor\Group_Control_Box_Shadow::get_type(),
							[
								'name' => 'addtocart_box_shadow',
								'label' => esc_html__( 'Box Shadow', 'themesflat-addons-for-elementor' ),
								'selector' => '{{WRAPPER}} .tf-woo-product .product-item .product-actions > a',
							]
						);
						$this->add_group_control(
							\Elementor\Group_Control_Border::get_type(),
							[
								'name' => 'addtocart_border',
								'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
								'selector' => '{{WRAPPER}} .tf-woo-product .product-item .product-actions > a',
							]
						);
						$this->add_responsive_control( 
							'addtocart_border_radius',
							[
								'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
								'type' => \Elementor\Controls_Manager::DIMENSIONS,
								'size_units' => [ 'px' , '%' ],
								'selectors' => [
									'{{WRAPPER}} .tf-woo-product .product-item .product-actions > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
								],
							]
						);				
					$this->end_controls_tab();

					$this->start_controls_tab( 'addtocart_style_hover_tab',
						[
							'label' => esc_html__( 'Hover', 'themesflat-addons-for-elementor' ),
						] );						
						$this->add_control(
							'addtocart_color_hover',
							[
								'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'default' => '#ffffff',
								'selectors' => [
									'{{WRAPPER}} .tf-woo-product .product-item .product-actions > a:hover' => 'color: {{VALUE}}',
								],
							]
						);
						$this->add_control(
							'addtocart_background_color_hover',
							[
								'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'default' => '#A13502',
								'selectors' => [
									'{{WRAPPER}} .tf-woo-product .product-item .product-actions > a:hover' => 'background-color: {{VALUE}}',
								],
							]
						);
						$this->add_group_control(
							\Elementor\Group_Control_Box_Shadow::get_type(),
							[
								'name' => 'addtocart_box_shadow_hover',
								'label' => esc_html__( 'Box Shadow', 'themesflat-addons-for-elementor' ),
								'selector' => '{{WRAPPER}} .tf-woo-product .product-item .product-actions > a:hover',
							]
						);
						$this->add_group_control(
							\Elementor\Group_Control_Border::get_type(),
							[
								'name' => 'addtocart_border_hover',
								'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
								'selector' => '{{WRAPPER}} .tf-woo-product .product-item .product-actions > a:hover',
							]
						);
						$this->add_responsive_control( 
							'addtocart_border_radius_hover',
							[
								'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
								'type' => \Elementor\Controls_Manager::DIMENSIONS,
								'size_units' => [ 'px' , '%' ],
								'selectors' => [
									'{{WRAPPER}} .tf-woo-product .product-item .product-actions > a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
								],
							]
						);
					$this->end_controls_tab();
				$this->end_controls_tabs();  

		        $this->end_controls_section();
	    	// /.End Action Button Style

		    // Start Pagination Style        
				$this->start_controls_section( 
					'section_product_pagination_style',
		            [
		                'label' => esc_html__('Pagination', 'themesflat-addons-for-elementor'),
		                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
		                'condition' => [
							'pagination' => 'yes'
						]
		            ]
		        );	

		        $this->add_control(
					'pagination_style',
					[
						'label' => esc_html__( 'Style', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SELECT,
						'default' => 'numeric-link',
						'options' => [
							'numeric-link' => esc_html__( 'Numeric & Page', 'themesflat-addons-for-elementor' ),
							'link'  => esc_html__( 'Page', 'themesflat-addons-for-elementor' ),
							'numeric' => esc_html__( 'Numeric', 'themesflat-addons-for-elementor' ),					
							'loadmore' => esc_html__( 'Load More', 'themesflat-addons-for-elementor' ),
						],
					]
				);

				$this->add_control(
					'pagination_align',
					[
						'label' =>esc_html__( 'Alignment', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::CHOOSE,
						'options' => [
							'left'    => [
								'title' =>esc_html__( 'Left', 'themesflat-addons-for-elementor' ),
								'icon' => 'eicon-text-align-left',
							],
							'center' => [
								'title' =>esc_html__( 'Center', 'themesflat-addons-for-elementor' ),
								'icon' => 'eicon-text-align-center',
							],
							'right' => [
								'title' =>esc_html__( 'Right', 'themesflat-addons-for-elementor' ),
								'icon' => 'eicon-text-align-right',
							],
						],
						'default' => 'left',
						'condition' => [
							'pagination_style' => ['numeric-link','numeric','loadmore'],
						],
					]
				);

		        $this->add_group_control( 
		        	\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'pagination_typography',
						'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
						'selector' => '{{WRAPPER}} .tf-woo-product .pagination a, {{WRAPPER}} .tf-woo-product .pagination span',
					]
				);

				$this->add_control( 
					'pagination_padding',
					[
						'label' => esc_html__( 'Padding', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .pagination a, {{WRAPPER}} .tf-woo-product .pagination span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);		

				$this->add_control( 
					'pagination_margin',
					[
						'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .pagination a, {{WRAPPER}} .tf-woo-product .pagination span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);    		        

		        $this->start_controls_tabs( 'pagination_style_tabs' );
		        	$this->start_controls_tab( 'pagination_style_normal_tab',
						[
							'label' => esc_html__( 'Normal', 'themesflat-addons-for-elementor' ),
						] );

		        		$this->add_control( 
							'pagination_color',
							[
								'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'default' => '#000000',
								'selectors' => [
									'{{WRAPPER}} .tf-woo-product .pagination a' => 'color: {{VALUE}}',
								],
							]
						);

						$this->add_control( 
							'pagination_bgcolor',
							[
								'label' => esc_html__( 'Backgound Color', 'themesflat-addons-for-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'default' => '',
								'selectors' => [
									'{{WRAPPER}} .tf-woo-product .pagination a' => 'background-color: {{VALUE}}',
								],
							]
						);

		        		$this->add_group_control( 
							\Elementor\Group_Control_Border::get_type(),
							[
								'name' => 'pagination_border',
								'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
								'selector' => '{{WRAPPER}} .tf-woo-product .pagination a, {{WRAPPER}} .tf-woo-product .pagination span',
							]
						);

						$this->add_control( 
							'pagination_border_radius',
							[
								'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
								'type' => \Elementor\Controls_Manager::DIMENSIONS,
								'size_units' => [ 'px', 'em', '%' ],
								'selectors' => [
									'{{WRAPPER}} .tf-woo-product .pagination a, {{WRAPPER}} .tf-woo-product .pagination span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
								],
							]
						);
					$this->end_controls_tab();
					$this->start_controls_tab( 'pagination_style_hover_tab',
						[
							'label' => esc_html__( 'Hover', 'themesflat-addons-for-elementor' ),
						] );

						$this->add_control( 
							'pagination_color_hover',
							[
								'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'default' => 'rgba(0, 0, 0, 0.5)',
								'selectors' => [
									'{{WRAPPER}} .tf-woo-product .pagination a:hover, {{WRAPPER}} .tf-woo-product .pagination span.current' => 'color: {{VALUE}};',
								],
							]
						);

						$this->add_control( 
							'pagination_bgcolor_hover',
							[
								'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'default' => '',
								'selectors' => [
									'{{WRAPPER}} .tf-woo-product .pagination a:hover, {{WRAPPER}} .tf-woo-product .pagination span.current' => 'background-color: {{VALUE}};',
								],
							]
						);

						$this->add_group_control( 
							\Elementor\Group_Control_Border::get_type(),
							[
								'name' => 'pagination_border_hover',
								'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
								'selector' => '{{WRAPPER}} .tf-woo-product .pagination a:hover, {{WRAPPER}} .tf-woo-product .pagination span.current',
							]
						);

						$this->add_control( 
							'pagination_border_radius_hover',
							[
								'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
								'type' => \Elementor\Controls_Manager::DIMENSIONS,
								'size_units' => [ 'px', 'em', '%' ],
								'selectors' => [
									'{{WRAPPER}} .tf-woo-product .pagination a:hover, {{WRAPPER}} .tf-woo-product .pagination span.current' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
								],
							]
						);
					$this->end_controls_tab();
				$this->end_controls_tabs();		

		        $this->end_controls_section();
	        // /.End Pagination Style

		    // Start Filter Bar Style        
				$this->start_controls_section( 
					'section_posts_filter_style',
		            [
		                'label' => esc_html__('Filter Bar', 'themesflat-addons-for-elementor'),
		                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
		                'condition' => [
							'filter_bar' => 'yes',
						],
		            ]
		        ); 

				$this->add_responsive_control( 
					'filter_margin',
					[
						'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .filter-bar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control( 
					'filter_padding',
					[
						'label' => esc_html__( 'Padding', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .filter-bar' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_control(
					'filter_align',
					[
						'label' =>esc_html__( 'Alignment', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::CHOOSE,
						'options' => [
							'left'    => [
								'title' =>esc_html__( 'Left', 'themesflat-addons-for-elementor' ),
								'icon' => 'eicon-text-align-left',
							],
							'center' => [
								'title' =>esc_html__( 'Center', 'themesflat-addons-for-elementor' ),
								'icon' => 'eicon-text-align-center',
							],
							'right' => [
								'title' =>esc_html__( 'Right', 'themesflat-addons-for-elementor' ),
								'icon' => 'eicon-text-align-right',
							],
						],
						'default' => 'center',
					]
				);

				$this->add_responsive_control( 
					'filter_link_padding',
					[
						'label' => esc_html__( 'Padding Link', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .products-filter li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);		

				$this->add_responsive_control( 
					'filter_link_margin',
					[
						'label' => esc_html__( 'Margin Link', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .products-filter li a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_group_control( 
		        	\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'filter_typography',
						'label' => esc_html__( 'Typography', 'themesflat-addons-for-elementor' ),
						'selector' => '{{WRAPPER}} .tf-woo-product .products-filter li a',
					]
				);

				$this->start_controls_tabs( 'filter_style_tabs' );
		        	$this->start_controls_tab( 'filter_style_normal_tab',
						[
							'label' => esc_html__( 'Normal', 'themesflat-addons-for-elementor' ),
						] );

		        		$this->add_control( 
							'filter_link_color',
							[
								'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'default' => 'rgba(0, 0, 0, 0.4)',
								'selectors' => [
									'{{WRAPPER}} .tf-woo-product .products-filter li a' => 'color: {{VALUE}}',
								],
							]
						);

						$this->add_control( 
							'filter_link_bgcolor',
							[
								'label' => esc_html__( 'Backgound Color', 'themesflat-addons-for-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'default' => '',
								'selectors' => [
									'{{WRAPPER}} .tf-woo-product .products-filter li a' => 'background-color: {{VALUE}}',
								],
							]
						);

		        		$this->add_group_control( 
							\Elementor\Group_Control_Border::get_type(),
							[
								'name' => 'filter_link_border',
								'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
								'selector' => '{{WRAPPER}} .tf-woo-product .products-filter li a',
							]
						);

						$this->add_control( 
							'filter_link_border_radius',
							[
								'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
								'type' => \Elementor\Controls_Manager::DIMENSIONS,
								'size_units' => [ 'px', 'em', '%' ],
								'selectors' => [
									'{{WRAPPER}} .tf-woo-product .products-filter li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
								],
							]
						);
					$this->end_controls_tab();
					$this->start_controls_tab( 'filter_style_hover_tab',
						[
							'label' => esc_html__( 'Hover & Active', 'themesflat-addons-for-elementor' ),
						] );

						$this->add_control( 
							'filter_link_color_hover',
							[
								'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'default' => '#ffffff',
								'selectors' => [
									'{{WRAPPER}} .tf-woo-product .products-filter li a:hover, {{WRAPPER}} .tf-woo-product .products-filter li.active a' => 'color: {{VALUE}};',
								],
							]
						);

						$this->add_control( 
							'filter_link_bgcolor_hover',
							[
								'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'default' => '#A13502',
								'selectors' => [
									'{{WRAPPER}} .tf-woo-product .products-filter li a:hover, {{WRAPPER}} .tf-woo-product .products-filter li.active a' => 'background-color: {{VALUE}};',
								],
							]
						);

						$this->add_group_control( 
							\Elementor\Group_Control_Border::get_type(),
							[
								'name' => 'filter_link_border_hover',
								'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
								'selector' => '{{WRAPPER}} .tf-woo-product .products-filter li a:hover, {{WRAPPER}} .tf-woo-product .products-filter li.active a',
							]
						);

						$this->add_control( 
							'filter_link_border_radius_hover',
							[
								'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
								'type' => \Elementor\Controls_Manager::DIMENSIONS,
								'size_units' => [ 'px', 'em', '%' ],
								'selectors' => [
									'{{WRAPPER}} .tf-woo-product .products-filter li a:hover, {{WRAPPER}} .tf-woo-product .products-filter li.active a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
								],
							]
						);
					$this->end_controls_tab();
				$this->end_controls_tabs();

				$this->add_control(
					'h_form_filter',
					[
						'label' => esc_html__( 'Form Filter', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_control( 
					'form_filter_bgcolor',
					[
						'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '#ffffff',
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .filter-bar .wrap-form-filter' => 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control( 
					\Elementor\Group_Control_Border::get_type(),
					[
						'name' => 'form_filter_border',
						'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
						'selector' => '{{WRAPPER}} .tf-woo-product .filter-bar .wrap-form-filter',
					]
				);

				$this->add_control( 
					'form_filter_border_radius',
					[
						'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', 'em', '%' ],
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .filter-bar .wrap-form-filter' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'box_shadow',
						'label' => esc_html__( 'Box Shadow', 'themesflat-addons-for-elementor' ),
						'selector' => '{{WRAPPER}} .tf-woo-product .filter-bar .wrap-form-filter',
					]
				);

				$this->start_controls_tabs( 'btn_form_filter_style_tabs' );
		        	$this->start_controls_tab( 'btn_form_filter_style_normal_tab',
						[
							'label' => esc_html__( 'Normal', 'themesflat-addons-for-elementor' ),
						] );

		        		$this->add_control( 
							'btn_form_filter_color',
							[
								'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'default' => '#000000',
								'selectors' => [
									'{{WRAPPER}} .tf-woo-product .filter-bar .wrap-form-filter .filter-button' => 'color: {{VALUE}}',
								],
							]
						);

						$this->add_control( 
							'btn_form_filter_bgcolor',
							[
								'label' => esc_html__( 'Backgound Color', 'themesflat-addons-for-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'default' => '#f6ece5',
								'selectors' => [
									'{{WRAPPER}} .tf-woo-product .filter-bar .wrap-form-filter .filter-button' => 'background-color: {{VALUE}}',
								],
							]
						);

		        		$this->add_group_control( 
							\Elementor\Group_Control_Border::get_type(),
							[
								'name' => 'btn_form_filter_border',
								'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
								'selector' => '{{WRAPPER}} .tf-woo-product .filter-bar .wrap-form-filter .filter-button',
							]
						);

						$this->add_control( 
							'btn_form_filter_border_radius',
							[
								'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
								'type' => \Elementor\Controls_Manager::DIMENSIONS,
								'size_units' => [ 'px', 'em', '%' ],
								'selectors' => [
									'{{WRAPPER}} .tf-woo-product .filter-bar .wrap-form-filter .filter-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
								],
							]
						);
					$this->end_controls_tab();
					$this->start_controls_tab( 'btn_form_filter_style_hover_tab',
						[
							'label' => esc_html__( 'Hover & Active', 'themesflat-addons-for-elementor' ),
						] );

						$this->add_control( 
							'btn_form_filter_color_hover',
							[
								'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'default' => '#ffffff',
								'selectors' => [
									'{{WRAPPER}} .tf-woo-product .filter-bar .wrap-form-filter .filter-button:hover' => 'color: {{VALUE}};',
								],
							]
						);

						$this->add_control( 
							'btn_form_filter_bgcolor_hover',
							[
								'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'default' => '#A13502',
								'selectors' => [
									'{{WRAPPER}} .tf-woo-product .filter-bar .wrap-form-filter .filter-button:hover' => 'background-color: {{VALUE}};',
								],
							]
						);

						$this->add_group_control( 
							\Elementor\Group_Control_Border::get_type(),
							[
								'name' => 'btn_form_filter_border_hover',
								'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
								'selector' => '{{WRAPPER}} .tf-woo-product .filter-bar .wrap-form-filter .filter-button:hover',
							]
						);

						$this->add_control( 
							'btn_form_filter_border_radius_hover',
							[
								'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
								'type' => \Elementor\Controls_Manager::DIMENSIONS,
								'size_units' => [ 'px', 'em', '%' ],
								'selectors' => [
									'{{WRAPPER}} .tf-woo-product .filter-bar .wrap-form-filter .filter-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
								],
							]
						);
					$this->end_controls_tab();
				$this->end_controls_tabs();

		        $this->end_controls_section();
	        // /.End Filter Bar Style 

		    // Start Carousel Arrow Style
		        $this->start_controls_section( 
					'section_arrow_style',
		            [
		                'label' => esc_html__('Carousel Arrow', 'themesflat-addons-for-elementor'),
		                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
		                'condition' => [
		                	'carousel' => 'yes',
		                	'carousel_arrow' => 'yes',
		                ]
		            ]
		        );

		        $this->add_control( 
					'carousel_prev_icon', [
		                'label' => esc_html__( 'Prev Icon', 'themesflat-addons-for-elementor' ),
		                'type' => \Elementor\Controls_Manager::ICON,
		                'default' => 'fa fa-chevron-left',
		                'include' => [
							'fa fa-angle-double-left',
							'fa fa-angle-left',
							'fa fa-chevron-left',
							'fa fa-arrow-left',
						],  
		                'condition' => [                	
		                    'carousel_arrow' => 'yes',
		                ]
		            ]
		        );

		    	$this->add_control( 
		    		'carousel_next_icon', [
		                'label' => esc_html__( 'Next Icon', 'themesflat-addons-for-elementor' ),
		                'type' => \Elementor\Controls_Manager::ICON,
		                'default' => 'fa fa-chevron-right',
		                'include' => [
							'fa fa-angle-double-right',
							'fa fa-angle-right',
							'fa fa-chevron-right',
							'fa fa-arrow-right',
						], 
		                'condition' => [                	
		                    'carousel_arrow' => 'yes',
		                ]
		            ]
		        );

		        $this->add_responsive_control( 
		        	'carousel_arrow_fontsize',
					[
						'label' => esc_html__( 'Font Size', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 100,
								'step' => 1,
							]
						],
						'default' => [
							'unit' => 'px',
							'size' => 20,
						],
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .owl-nav .owl-prev, {{WRAPPER}} .tf-woo-product .owl-nav .owl-next' => 'font-size: {{SIZE}}{{UNIT}};',
						],
						'condition' => [					
		                    'carousel_arrow' => 'yes',
		                ]
					]
				);

				$this->add_responsive_control( 
					'w_size_carousel_arrow',
					[
						'label' => esc_html__( 'Width', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 200,
								'step' => 1,
							]
						],
						'default' => [
							'unit' => 'px',
							'size' => 70,
						],
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .owl-nav .owl-prev, {{WRAPPER}} .tf-woo-product .owl-nav .owl-next' => 'width: {{SIZE}}{{UNIT}};',
						],
						'condition' => [					
		                    'carousel_arrow' => 'yes',
		                ]
					]
				);

				$this->add_responsive_control( 
					'h_size_carousel_arrow',
					[
						'label' => esc_html__( 'Height', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 200,
								'step' => 1,
							]
						],
						'default' => [
							'unit' => 'px',
							'size' => 70,
						],
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .owl-nav .owl-prev, {{WRAPPER}} .tf-woo-product .owl-nav .owl-next' => 'height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
						],
						'condition' => [					
		                    'carousel_arrow' => 'yes',
		                ]
					]
				);	

				$this->add_responsive_control( 
					'carousel_arrow_horizontal_position_prev',
					[
						'label' => esc_html__( 'Horizontal Position Previous', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => -200,
								'max' => 2000,
								'step' => 1,
							],
							'%' => [
								'min' => 0,
								'max' => 100,
							],
						],
						'default' => [
							'unit' => 'px',
							'size' => 0,
						],
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .owl-nav .owl-prev' => 'left: {{SIZE}}{{UNIT}};',
						],
						'condition' => [					
		                    'carousel_arrow' => 'yes',
		                ]
					]
				);

				$this->add_responsive_control( 
					'carousel_arrow_horizontal_position_next',
					[
						'label' => esc_html__( 'Horizontal Position Next', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => -200,
								'max' => 2000,
								'step' => 1,
							],
							'%' => [
								'min' => 0,
								'max' => 100,
							],
						],
						'default' => [
							'unit' => 'px',
							'size' => 0,
						],
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .owl-nav .owl-next' => 'right: {{SIZE}}{{UNIT}};',
						],
						'condition' => [					
		                    'carousel_arrow' => 'yes',
		                ]
					]
				);

				$this->add_responsive_control( 
					'carousel_arrow_vertical_position',
					[
						'label' => esc_html__( 'Vertical Position', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => -1000,
								'max' => 1000,
								'step' => 1,
							],
							'%' => [
								'min' => 0,
								'max' => 100,
							],
						],
						'default' => [
							'unit' => '%',
							'size' => 50,
						],
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .owl-nav .owl-prev, {{WRAPPER}} .tf-woo-product .owl-nav .owl-next' => 'top: {{SIZE}}{{UNIT}};',
						],
						'condition' => [					
		                    'carousel_arrow' => 'yes',
		                ]
					]
				);

				$this->start_controls_tabs( 
					'carousel_arrow_tabs',
					[
						'condition' => [
			                'carousel_arrow' => 'yes',	                
			            ]
					] );
					$this->start_controls_tab( 
						'carousel_arrow_normal_tab',
						[
							'label' => esc_html__( 'Normal', 'themesflat-addons-for-elementor' ),						
						]
						);
						$this->add_control( 
							'carousel_arrow_color',
				            [
				                'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
				                'type' => \Elementor\Controls_Manager::COLOR,
				                'default' => '#ffffff',
				                'selectors' => [
									'{{WRAPPER}} .tf-woo-product .owl-nav .owl-prev, {{WRAPPER}} .tf-woo-product .owl-nav .owl-next' => 'color: {{VALUE}}',
								],
								'condition' => [
				                    'carousel_arrow' => 'yes',
				                ]
				            ]
				        );
				        $this->add_control( 
				        	'carousel_arrow_bg_color',
				            [
				                'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
				                'type' => \Elementor\Controls_Manager::COLOR,
				                'default' => '#f6ece5',
				                'selectors' => [
									'{{WRAPPER}} .tf-woo-product .owl-nav .owl-prev, {{WRAPPER}} .tf-woo-product .owl-nav .owl-next' => 'background-color: {{VALUE}};',
								],
								'condition' => [
				                    'carousel_arrow' => 'yes',
				                ]
				            ]
				        );	
				        $this->add_group_control( 
				        	\Elementor\Group_Control_Border::get_type(),
							[
								'name' => 'carousel_arrow_border',
								'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
								'selector' => '{{WRAPPER}} .tf-woo-product .owl-nav .owl-prev, {{WRAPPER}} .tf-woo-product .owl-nav .owl-next',
								'condition' => [
				                    'carousel_arrow' => 'yes',
				                ]
							]
						);
						$this->add_responsive_control( 
							'carousel_arrow_border_radius',
				            [
				                'label' => esc_html__( 'Border Radius Previous', 'themesflat-addons-for-elementor' ),
				                'type' => \Elementor\Controls_Manager::DIMENSIONS,
				                'size_units' => [ 'px', '%', 'em' ],
				                'selectors' => [
				                    '{{WRAPPER}} .tf-woo-product .owl-nav .owl-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				                ],
				                'condition' => [
				                    'carousel_arrow' => 'yes',
				                ]
				            ]
				        );
				        $this->add_responsive_control( 
							'carousel_arrow_border_radius_next',
				            [
				                'label' => esc_html__( 'Border Radius Next', 'themesflat-addons-for-elementor' ),
				                'type' => \Elementor\Controls_Manager::DIMENSIONS,
				                'size_units' => [ 'px', '%', 'em' ],
				                'selectors' => [
				                    '{{WRAPPER}} .tf-woo-product .owl-nav .owl-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				                ],
				                'condition' => [
				                    'carousel_arrow' => 'yes',
				                ]
				            ]
				        );
			        $this->end_controls_tab();

			        $this->start_controls_tab( 
				    	'carousel_arrow_hover_tab',
						[
							'label' => esc_html__( 'Hover', 'themesflat-addons-for-elementor' ),
						]
						);
				    	$this->add_control( 
				    		'carousel_arrow_color_hover',
				            [
				                'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
				                'type' => \Elementor\Controls_Manager::COLOR,
				                'default' => '#ffffff',
				                'selectors' => [
									'{{WRAPPER}} .tf-woo-product .owl-nav .owl-prev:hover, {{WRAPPER}} .tf-woo-product .owl-nav .owl-next:hover' => 'color: {{VALUE}}',
								],
								'condition' => [
				                    'carousel_arrow' => 'yes',
				                ]
				            ]
				        );
				        $this->add_control( 
				        	'carousel_arrow_hover_bg_color',
				            [
				                'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
				                'type' => \Elementor\Controls_Manager::COLOR,
				                'default' => '#222222',
				                'selectors' => [
									'{{WRAPPER}} .tf-woo-product .owl-nav .owl-prev:hover, {{WRAPPER}} .tf-woo-product .owl-nav .owl-next:hover' => 'background-color: {{VALUE}};',
								],
								'condition' => [
				                    'carousel_arrow' => 'yes',
				                ]
				            ]
				        );
				        $this->add_group_control( 
				        	\Elementor\Group_Control_Border::get_type(),
							[
								'name' => 'carousel_arrow_border_hover',
								'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
								'selector' => '{{WRAPPER}} .tf-woo-product .owl-nav .owl-prev:hover, {{WRAPPER}} .tf-woo-product .owl-nav .owl-next:hover',
								'condition' => [
				                    'carousel_arrow' => 'yes',
				                ]
							]
						);
						$this->add_responsive_control( 
							'carousel_arrow_border_radius_hover',
				            [
				                'label' => esc_html__( 'Border Radius Previous', 'themesflat-addons-for-elementor' ),
				                'type' => \Elementor\Controls_Manager::DIMENSIONS,
				                'size_units' => [ 'px', '%', 'em' ],
				                'selectors' => [
				                    '{{WRAPPER}} .tf-woo-product .owl-nav .owl-prev:hover, {{WRAPPER}} .tf-woo-product .owl-nav .owl-next:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				                ],
				                'condition' => [
				                    'carousel_arrow' => 'yes',
				                ]
				            ]
				        );
		       		$this->end_controls_tab();
		        $this->end_controls_tabs();

		        $this->end_controls_section();
		    // /.End Carousel Arrow Style 

		    // Start Carousel Bullets Style
		        $this->start_controls_section( 
					'section_bullets_style',
		            [
		                'label' => esc_html__('Carousel Bullets', 'themesflat-addons-for-elementor'),
		                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
		                'condition' => [
		                	'carousel' => 'yes',
		                	'carousel_bullets' => 'yes',
		                ]
		            ]
		        );       

				$this->add_responsive_control( 
					'carousel_bullets_horizontal_position',
					[
						'label' => esc_html__( 'Horizonta Offset', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 2000,
								'step' => 1,
							],
							'%' => [
								'min' => 0,
								'max' => 100,
							],
						],
						'default' => [
							'unit' => '%',
							'size' => 50,
						],
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .owl-dots' => 'left: {{SIZE}}{{UNIT}};',
						],
						'condition' => [					
		                    'carousel_bullets' => 'yes',
		                ]
					]
				);

				$this->add_responsive_control( 
					'carousel_bullets_vertical_position',
					[
						'label' => esc_html__( 'Vertical Offset', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => -200,
								'max' => 1000,
								'step' => 1,
							],
							'%' => [
								'min' => 0,
								'max' => 100,
							],
						],
						'default' => [
							'unit' => 'px',
							'size' => -40,
						],
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .owl-dots' => 'bottom: {{SIZE}}{{UNIT}};',
						],
						'condition' => [					
		                    'carousel_bullets' => 'yes',
		                ]
					]
				);

				$this->add_responsive_control( 
					'carousel_bullets_margin',
					[
						'label' => esc_html__( 'Spacing', 'themesflat-addons-for-elementor' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 50,
								'step' => 1,
							],
						],
						'default' => [
							'unit' => 'px',
							'size' => 5,
						],
						'selectors' => [
							'{{WRAPPER}} .tf-woo-product .owl-dots .owl-dot' => 'margin: 0 {{SIZE}}{{UNIT}};',
						],
						'condition' => [
		                    'carousel_bullets' => 'yes',
		                ]
					]
				);

				$this->start_controls_tabs( 
					'carousel_bullets_tabs',
						[
							'condition' => [						
			                    'carousel_bullets' => 'yes',
			                ]
						] );
					$this->start_controls_tab( 
						'carousel_bullets_normal_tab',
						[
							'label' => esc_html__( 'Normal', 'themesflat-addons-for-elementor' ),						
						]
						);
						$this->add_responsive_control( 
				        	'w_size_carousel_bullets',
								[
									'label' => esc_html__( 'Width', 'themesflat-addons-for-elementor' ),
									'type' => \Elementor\Controls_Manager::SLIDER,
									'size_units' => [ 'px' ],
									'range' => [
										'px' => [
											'min' => 0,
											'max' => 100,
											'step' => 1,
										]
									],
									'default' => [
										'unit' => 'px',
										'size' => 15,
									],
									'selectors' => [
										'{{WRAPPER}} .tf-woo-product .owl-dots .owl-dot' => 'width: {{SIZE}}{{UNIT}};',
									],
									'condition' => [						
					                    'carousel_bullets' => 'yes',
					                ]
								]
						);
						$this->add_responsive_control( 
							'h_size_carousel_bullets',
							[
								'label' => esc_html__( 'Height', 'themesflat-addons-for-elementor' ),
								'type' => \Elementor\Controls_Manager::SLIDER,
								'size_units' => [ 'px' ],
								'range' => [
									'px' => [
										'min' => 0,
										'max' => 100,
										'step' => 1,
									]
								],
								'default' => [
									'unit' => 'px',
									'size' => 15,
								],
								'selectors' => [
									'{{WRAPPER}} .tf-woo-product .owl-dots .owl-dot' => 'height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
								],
								'condition' => [					
				                    'carousel_bullets' => 'yes',
				                ]
							]
						);
						$this->add_control( 
							'carousel_bullets_bg_color',
				            [
				                'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
				                'type' => \Elementor\Controls_Manager::COLOR,
				                'default' => '#f6ece5',
				                'selectors' => [
									'{{WRAPPER}} .tf-woo-product .owl-dots .owl-dot' => 'background-color: {{VALUE}}',
								],
								'condition' => [
				                    'carousel_bullets' => 'yes',
				                ]
				            ]
				        );
				        $this->add_group_control( 
				        	\Elementor\Group_Control_Border::get_type(),
							[
								'name' => 'carousel_bullets_border',
								'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
								'selector' => '{{WRAPPER}} .tf-woo-product .owl-dots .owl-dot',
								'condition' => [
				                    'carousel_bullets' => 'yes',
				                ]
							]
						);
						$this->add_responsive_control( 
							'carousel_bullets_border_radius',
				            [
				                'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
				                'type' => \Elementor\Controls_Manager::DIMENSIONS,
				                'size_units' => [ 'px', '%', 'em' ],
				                'selectors' => [
				                    '{{WRAPPER}} .tf-woo-product .owl-dots .owl-dot' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				                ],
				                'condition' => [
				                    'carousel_bullets' => 'yes',
				                ]
				            ]
				        );
				    $this->end_controls_tab();

			        $this->start_controls_tab( 
			        	'carousel_bullets_hover_tab',
						[
							'label' => esc_html__( 'Active', 'themesflat-addons-for-elementor' ),
						]
						);
						$this->add_responsive_control( 
				        	'w_size_carousel_bullets_active',
								[
									'label' => esc_html__( 'Width', 'themesflat-addons-for-elementor' ),
									'type' => \Elementor\Controls_Manager::SLIDER,
									'size_units' => [ 'px' ],
									'range' => [
										'px' => [
											'min' => 0,
											'max' => 100,
											'step' => 1,
										]
									],
									'default' => [
										'unit' => 'px',
										'size' => 15,
									],
									'selectors' => [
										'{{WRAPPER}} .tf-woo-product .owl-dots .owl-dot.active' => 'width: {{SIZE}}{{UNIT}};',
									],
									'condition' => [						
					                    'carousel_bullets' => 'yes',
					                ]
								]
						);
						$this->add_responsive_control( 
							'h_size_carousel_bullets_active',
							[
								'label' => esc_html__( 'Height', 'themesflat-addons-for-elementor' ),
								'type' => \Elementor\Controls_Manager::SLIDER,
								'size_units' => [ 'px' ],
								'range' => [
									'px' => [
										'min' => 0,
										'max' => 100,
										'step' => 1,
									]
								],
								'default' => [
									'unit' => 'px',
									'size' => 15,
								],
								'selectors' => [
									'{{WRAPPER}} .tf-woo-product .owl-dots .owl-dot.active' => 'height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
								],
								'condition' => [					
				                    'carousel_bullets' => 'yes',
				                ]
							]
						);
						$this->add_control( 
							'size_carousel_bullets_active_scale_hover',
							[
								'label' => esc_html__( 'Scale', 'themesflat-addons-for-elementor' ),
								'type' => \Elementor\Controls_Manager::SLIDER,
								'size_units' => [ 'px' ],
								'range' => [
									'px' => [
										'min' => 1,
										'max' => 2,
										'step' => 0.1,
									],
								],
								'default' => [
									'unit' => 'px',
									'size' => 1,
								],
								'selectors' => [
									'{{WRAPPER}} .tf-woo-product .owl-dots .owl-dot.active, {{WRAPPER}} .tf-woo-product .owl-dots .owl-dot:hover' => 'transform: scale({{SIZE}});',
								],
							]
						);
			        	$this->add_control( 
			        		'carousel_bullets_hover_bg_color',
				            [
				                'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
				                'type' => \Elementor\Controls_Manager::COLOR,
				                'default' => '#000000',
				                'selectors' => [
									'{{WRAPPER}} .tf-woo-product .owl-dots .owl-dot:hover, {{WRAPPER}} .tf-woo-product .owl-dots .owl-dot.active' => 'background-color: {{VALUE}}',
								],
								'condition' => [
				                    'carousel_bullets' => 'yes',
				                ]
				            ]
				        );
			        	$this->add_group_control( 
			        		\Elementor\Group_Control_Border::get_type(),
							[
								'name' => 'carousel_bullets_border_hover',
								'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
								'selector' => '{{WRAPPER}} .tf-woo-product .owl-dots .owl-dot:hover, {{WRAPPER}} .tf-woo-product .owl-dots .owl-dot.active',
								'condition' => [
				                    'carousel_bullets' => 'yes',
				                ]
							]
						);
						$this->add_responsive_control( 
							'carousel_bullets_border_radius_hover',
				            [
				                'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
				                'type' => \Elementor\Controls_Manager::DIMENSIONS,
				                'size_units' => [ 'px', '%', 'em' ],
				                'selectors' => [
				                    '{{WRAPPER}} .tf-woo-product .owl-dots .owl-dot:hover, {{WRAPPER}} .tf-woo-product .owl-dots .owl-dot.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				                ],
				                'condition' => [
				                    'carousel_bullets' => 'yes',
				                ]
				            ]
				        );
					$this->end_controls_tab();
			    $this->end_controls_tabs();	

		        $this->end_controls_section();
		    // /.End Carousel Bullets Style    

		    // Start Wishlist Style  
		    	if ( class_exists( 'YITH_WCWL' ) ) {
					$this->start_controls_section( 
						'section_product_wishlist_style',
			            [
			                'label' => esc_html__('Wishlist', 'themesflat-addons-for-elementor'),
			                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			                'condition' => [
								'wishlist' => 'yes',
							],
			            ]
			        );			        

					$this->add_control( 
						'wishlist_padding',
						[
							'label' => esc_html__( 'Padding', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', 'em' ],
							'default' => [
								'top' => '9',
								'right' => '14',
								'bottom' => '9',
								'left' => '14',
								'unit' => 'px',
								'isLinked' => false,
							],
							'selectors' => [
								'{{WRAPPER}} .tf-woo-product .yith-wcwl-add-to-wishlist .yith-wcwl-add-button a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
								'{{WRAPPER}} .tf-woo-product .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistexistsbrowse' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
								'{{WRAPPER}} .tf-woo-product .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
							'condition' => [
								'wishlist' => 'yes',
							],
						]
					);		

					$this->add_control( 
						'wishlist_margin',
						[
							'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', 'em' ],
							'default' => [
								'top' => '0',
								'right' => '0',
								'bottom' => '0',
								'left' => '5',
								'unit' => 'px',
								'isLinked' => false,
							],
							'selectors' => [
								'{{WRAPPER}} .tf-woo-product .yith-wcwl-add-to-wishlist .yith-wcwl-add-button a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
								'{{WRAPPER}} .tf-woo-product .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistexistsbrowse' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
								'{{WRAPPER}} .tf-woo-product .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
							'condition' => [
								'wishlist' => 'yes',
							],
						]
					); 

					$this->add_responsive_control( 
						'wishlist_icon_size',
						[
							'label' => esc_html__( 'Wishlist Icon Size', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'size_units' => [ 'px' ],
							'range' => [
								'px' => [
									'min' => 0,
									'max' => 100,
									'step' => 1,
								]
							],
							'default' => [
								'unit' => 'px',
								'size' => 15,
							],
							'selectors' => [
								'{{WRAPPER}} .tf-woo-product .yith-wcwl-add-to-wishlist .yith-wcwl-add-button a i, {{WRAPPER}} .tf-woo-product .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistexistsbrowse i, {{WRAPPER}} .tf-woo-product .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse i' => 'font-size: {{SIZE}}{{UNIT}};',
							],
							'condition' => [					
			                    'wishlist' => 'yes',
			                ]
						]
					);   		        

			        $this->start_controls_tabs( 'wishlist_style_tabs', ['condition' => [ 'wishlist' => 'yes' ]] );
			        	$this->start_controls_tab( 'wishlist_style_normal_tab',
							[
								'label' => esc_html__( 'Normal', 'themesflat-addons-for-elementor' ),
							] );

			        		$this->add_control( 
								'wishlist_color',
								[
									'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
									'type' => \Elementor\Controls_Manager::COLOR,
									'default' => '#A13502',
									'selectors' => [
										'{{WRAPPER}} .tf-woo-product .yith-wcwl-add-to-wishlist .yith-wcwl-add-button a' => 'color: {{VALUE}};'
									],
								]
							);

							$this->add_control( 
								'wishlist_bgcolor',
								[
									'label' => esc_html__( 'Backgound Color', 'themesflat-addons-for-elementor' ),
									'type' => \Elementor\Controls_Manager::COLOR,
									'default' => '#f6ece5',
									'selectors' => [
										'{{WRAPPER}} .tf-woo-product .yith-wcwl-add-to-wishlist .yith-wcwl-add-button a' => 'background-color: {{VALUE}};'
									],
								]
							);

			        		$this->add_group_control( 
								\Elementor\Group_Control_Border::get_type(),
								[
									'name' => 'wishlist_border',
									'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
									'selector' => '{{WRAPPER}} .tf-woo-product .yith-wcwl-add-to-wishlist .yith-wcwl-add-button a, {{WRAPPER}} .tf-woo-product .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistexistsbrowse a, {{WRAPPER}} .tf-woo-product .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse a',
								]
							);

							$this->add_control( 
								'wishlist_border_radius',
								[
									'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
									'type' => \Elementor\Controls_Manager::DIMENSIONS,
									'size_units' => [ 'px', 'em', '%' ],
									'selectors' => [
										'{{WRAPPER}} .tf-woo-product .yith-wcwl-add-to-wishlist .yith-wcwl-add-button a, {{WRAPPER}} .tf-woo-product .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistexistsbrowse a, {{WRAPPER}} .tf-woo-product .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
									],
								]
							);
						$this->end_controls_tab();
						$this->start_controls_tab( 'wishlist_style_hover_tab',
							[
								'label' => esc_html__( 'Hover', 'themesflat-addons-for-elementor' ),
							] );

							$this->add_control( 
								'wishlist_color_hover',
								[
									'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
									'type' => \Elementor\Controls_Manager::COLOR,
									'default' => '#ffffff',
									'selectors' => [
										'{{WRAPPER}} .tf-woo-product .yith-wcwl-add-to-wishlist .yith-wcwl-add-button a:hover' => 'color: {{VALUE}};',
										'{{WRAPPER}} .tf-woo-product .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistexistsbrowse' => 'color: {{VALUE}};',
										'{{WRAPPER}} .tf-woo-product .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse' => 'color: {{VALUE}};',
									],
								]
							);

							$this->add_control( 
								'wishlist_bgcolor_hover',
								[
									'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
									'type' => \Elementor\Controls_Manager::COLOR,
									'default' => '#A13502',
									'selectors' => [
										'{{WRAPPER}} .tf-woo-product .yith-wcwl-add-to-wishlist .yith-wcwl-add-button a:hover' => 'background-color: {{VALUE}};',
										'{{WRAPPER}} .tf-woo-product .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistexistsbrowse' => 'background-color: {{VALUE}};',
										'{{WRAPPER}} .tf-woo-product .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse' => 'background-color: {{VALUE}};',
									],
								]
							);

							$this->add_group_control( 
								\Elementor\Group_Control_Border::get_type(),
								[
									'name' => 'wishlist_border_hover',
									'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
									'selector' => '{{WRAPPER}} .tf-woo-product .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistexistsbrowse a, {{WRAPPER}} .tf-woo-product .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse a',
								]
							);

							$this->add_control( 
								'wishlist_border_radius_hover',
								[
									'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
									'type' => \Elementor\Controls_Manager::DIMENSIONS,
									'size_units' => [ 'px', 'em', '%' ],
									'selectors' => [
										'{{WRAPPER}} .tf-woo-product .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistexistsbrowse a, {{WRAPPER}} .tf-woo-product .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
									],
								]
							);
						$this->end_controls_tab();
					$this->end_controls_tabs();

			        $this->end_controls_section();
		        }
	        // /.End Wishlist Style

		    // Start Compare Style  
		    	if ( class_exists( 'YITH_Woocompare' ) ) {
					$this->start_controls_section( 
						'section_product_compare_style',
			            [
			                'label' => esc_html__('Compare', 'themesflat-addons-for-elementor'),
			                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			                'condition' => [
								'compare' => 'yes',
							],
			            ]
			        );	

					$this->add_control( 
						'compare_padding',
						[
							'label' => esc_html__( 'Padding', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', 'em' ],
							'default' => [
								'top' => '9',
								'right' => '14',
								'bottom' => '9',
								'left' => '14',
								'unit' => 'px',
								'isLinked' => false,
							],
							'selectors' => [
								'{{WRAPPER}} .tf-woo-product .tf-compare-button a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
							'condition' => [
								'compare' => 'yes',
							],
						]
					);		

					$this->add_control( 
						'compare_margin',
						[
							'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', 'em' ],
							'default' => [
								'top' => '0',
								'right' => '0',
								'bottom' => '0',
								'left' => '5',
								'unit' => 'px',
								'isLinked' => false,
							],
							'selectors' => [
								'{{WRAPPER}} .tf-woo-product .tf-compare-button a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
							'condition' => [
								'compare' => 'yes',
							],
						]
					); 

					$this->add_responsive_control( 
						'compare_icon_size',
						[
							'label' => esc_html__( 'Wishlist Icon Size', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'size_units' => [ 'px' ],
							'range' => [
								'px' => [
									'min' => 0,
									'max' => 100,
									'step' => 1,
								]
							],
							'default' => [
								'unit' => 'px',
								'size' => 15,
							],
							'selectors' => [
								'{{WRAPPER}} .tf-woo-product .tf-compare-button a:before' => 'font-size: {{SIZE}}{{UNIT}};',
							],
							'condition' => [					
			                    'compare' => 'yes',
			                ]
						]
					);   		        

			        $this->start_controls_tabs( 'compare_style_tabs', ['condition' => [ 'compare' => 'yes' ]] );
			        	$this->start_controls_tab( 'compare_style_normal_tab',
							[
								'label' => esc_html__( 'Normal', 'themesflat-addons-for-elementor' ),
							] );

			        		$this->add_control( 
								'compare_color',
								[
									'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
									'type' => \Elementor\Controls_Manager::COLOR,
									'default' => '#A13502',
									'selectors' => [
										'{{WRAPPER}} .tf-woo-product .tf-compare-button a:before' => 'color: {{VALUE}};'
									],
								]
							);

							$this->add_control( 
								'compare_bgcolor',
								[
									'label' => esc_html__( 'Backgound Color', 'themesflat-addons-for-elementor' ),
									'type' => \Elementor\Controls_Manager::COLOR,
									'default' => '#f6ece5',
									'selectors' => [
										'{{WRAPPER}} .tf-woo-product .tf-compare-button a' => 'background-color: {{VALUE}};'
									],
								]
							);

			        		$this->add_group_control( 
								\Elementor\Group_Control_Border::get_type(),
								[
									'name' => 'compare_border',
									'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
									'selector' => '{{WRAPPER}} .tf-woo-product .tf-compare-button a',
								]
							);

							$this->add_control( 
								'compare_border_radius',
								[
									'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
									'type' => \Elementor\Controls_Manager::DIMENSIONS,
									'size_units' => [ 'px', 'em', '%' ],
									'selectors' => [
										'{{WRAPPER}} .tf-woo-product .tf-compare-button a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
									],
								]
							);
						$this->end_controls_tab();
						$this->start_controls_tab( 'compare_style_hover_tab',
							[
								'label' => esc_html__( 'Hover', 'themesflat-addons-for-elementor' ),
							] );

							$this->add_control( 
								'compare_color_hover',
								[
									'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
									'type' => \Elementor\Controls_Manager::COLOR,
									'default' => '#ffffff',
									'selectors' => [
										'{{WRAPPER}} .tf-woo-product .tf-compare-button a:hover:before' => 'color: {{VALUE}};',
										'{{WRAPPER}} .tf-woo-product .tf-compare-button a.added:before' => 'color: {{VALUE}};',
									],
								]
							);

							$this->add_control( 
								'compare_bgcolor_hover',
								[
									'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
									'type' => \Elementor\Controls_Manager::COLOR,
									'default' => '#A13502',
									'selectors' => [									
										'{{WRAPPER}} .tf-woo-product .tf-compare-button a:hover' => 'background-color: {{VALUE}};',
										'{{WRAPPER}} .tf-woo-product .tf-compare-button a.added' => 'background-color: {{VALUE}};',
									],
								]
							);

							$this->add_group_control( 
								\Elementor\Group_Control_Border::get_type(),
								[
									'name' => 'compare_border_hover',
									'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
									'selector' => '{{WRAPPER}} .tf-woo-product .tf-compare-button a:hover, {{WRAPPER}} .tf-woo-product .tf-compare-button a.added',
								]
							);

							$this->add_control( 
								'compare_border_radius_hover',
								[
									'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
									'type' => \Elementor\Controls_Manager::DIMENSIONS,
									'size_units' => [ 'px', 'em', '%' ],
									'selectors' => [
										'{{WRAPPER}} .tf-woo-product .tf-compare-button a:hover, {{WRAPPER}} .tf-woo-product .tf-compare-button a.added' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
									],
								]
							);
						$this->end_controls_tab();
					$this->end_controls_tabs();

			        $this->end_controls_section();
		        }
	        // /.End Compare Style

		    // Start Quick View Style  
		    	if ( class_exists( 'YITH_WCQV' ) ) {
					$this->start_controls_section( 
						'section_product_quickview_style',
			            [
			                'label' => esc_html__('Quick View', 'themesflat-addons-for-elementor'),
			                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			                'condition' => [
								'quickview' => 'yes',
							],
			            ]
			        );			         

					$this->add_control( 
						'quickview_padding',
						[
							'label' => esc_html__( 'Padding', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', 'em' ],
							'default' => [
								'top' => '9',
								'right' => '14',
								'bottom' => '9',
								'left' => '14',
								'unit' => 'px',
								'isLinked' => false,
							],
							'selectors' => [
								'{{WRAPPER}} .tf-woo-product .tf-quick-view-button a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
							'condition' => [
								'quickview' => 'yes',
							],
						]
					);		

					$this->add_control( 
						'quickview_margin',
						[
							'label' => esc_html__( 'Margin', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', 'em' ],
							'default' => [
								'top' => '0',
								'right' => '0',
								'bottom' => '0',
								'left' => '5',
								'unit' => 'px',
								'isLinked' => false,
							],
							'selectors' => [
								'{{WRAPPER}} .tf-woo-product .tf-quick-view-button a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
							'condition' => [
								'quickview' => 'yes',
							],
						]
					); 

					$this->add_responsive_control( 
						'quickview_icon_size',
						[
							'label' => esc_html__( 'Wishlist Icon Size', 'themesflat-addons-for-elementor' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'size_units' => [ 'px' ],
							'range' => [
								'px' => [
									'min' => 0,
									'max' => 100,
									'step' => 1,
								]
							],
							'default' => [
								'unit' => 'px',
								'size' => 15,
							],
							'selectors' => [
								'{{WRAPPER}} .tf-woo-product .tf-quick-view-button a:before' => 'font-size: {{SIZE}}{{UNIT}};',
							],
							'condition' => [					
			                    'quickview' => 'yes',
			                ]
						]
					);   		        

			        $this->start_controls_tabs( 'quickview_style_tabs', ['condition' => [ 'quickview' => 'yes' ]] );
			        	$this->start_controls_tab( 'quickview_style_normal_tab',
							[
								'label' => esc_html__( 'Normal', 'themesflat-addons-for-elementor' ),
							] );

			        		$this->add_control( 
								'quickview_color',
								[
									'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
									'type' => \Elementor\Controls_Manager::COLOR,
									'default' => '#A13502',
									'selectors' => [
										'{{WRAPPER}} .tf-woo-product .tf-quick-view-button a:before' => 'color: {{VALUE}};'
									],
								]
							);

							$this->add_control( 
								'quickview_bgcolor',
								[
									'label' => esc_html__( 'Backgound Color', 'themesflat-addons-for-elementor' ),
									'type' => \Elementor\Controls_Manager::COLOR,
									'default' => '#f6ece5',
									'selectors' => [
										'{{WRAPPER}} .tf-woo-product .tf-quick-view-button a' => 'background-color: {{VALUE}};'
									],
								]
							);

			        		$this->add_group_control( 
								\Elementor\Group_Control_Border::get_type(),
								[
									'name' => 'quickview_border',
									'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
									'selector' => '{{WRAPPER}} .tf-woo-product .tf-quick-view-button a',
								]
							);

							$this->add_control( 
								'quickview_border_radius',
								[
									'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
									'type' => \Elementor\Controls_Manager::DIMENSIONS,
									'size_units' => [ 'px', 'em', '%' ],
									'selectors' => [
										'{{WRAPPER}} .tf-woo-product .tf-quick-view-button a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
									],
								]
							);
						$this->end_controls_tab();
						$this->start_controls_tab( 'quickview_style_hover_tab',
							[
								'label' => esc_html__( 'Hover', 'themesflat-addons-for-elementor' ),
							] );

							$this->add_control( 
								'quickview_color_hover',
								[
									'label' => esc_html__( 'Color', 'themesflat-addons-for-elementor' ),
									'type' => \Elementor\Controls_Manager::COLOR,
									'default' => '#ffffff',
									'selectors' => [
										'{{WRAPPER}} .tf-woo-product .tf-quick-view-button a:hover:before' => 'color: {{VALUE}};',
									],
								]
							);

							$this->add_control( 
								'quickview_bgcolor_hover',
								[
									'label' => esc_html__( 'Background Color', 'themesflat-addons-for-elementor' ),
									'type' => \Elementor\Controls_Manager::COLOR,
									'default' => '#A13502',
									'selectors' => [									
										'{{WRAPPER}} .tf-woo-product .tf-quick-view-button a:hover' => 'background-color: {{VALUE}};',
									],
								]
							);

							$this->add_group_control( 
								\Elementor\Group_Control_Border::get_type(),
								[
									'name' => 'quickview_border_hover',
									'label' => esc_html__( 'Border', 'themesflat-addons-for-elementor' ),
									'selector' => '{{WRAPPER}} .tf-woo-product .tf-quick-view-button a:hover',
								]
							);

							$this->add_control( 
								'quickview_border_radius_hover',
								[
									'label' => esc_html__( 'Border Radius', 'themesflat-addons-for-elementor' ),
									'type' => \Elementor\Controls_Manager::DIMENSIONS,
									'size_units' => [ 'px', 'em', '%' ],
									'selectors' => [
										'{{WRAPPER}} .tf-woo-product .tf-quick-view-button a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
									],
								]
							);
						$this->end_controls_tab();
					$this->end_controls_tabs();

			        $this->end_controls_section();
		        }
	        // /.End Quick View Style

		}	

		protected function render($instance = []) {
			$settings = $this->get_settings_for_display();
			$class_products = $carousel = $attr_carousel = $carousel_arrow = $carousel_bullets = '';
			
			if ( get_query_var('paged') ) {
	           $paged = get_query_var('paged');
	        } elseif ( get_query_var('page') ) {
	           $paged = get_query_var('page');
	        } else {
	           $paged = 1;
	        }

			$args = array(
	            'post_type'             => 'product',
	            'post_status'           => 'publish',
	            'ignore_sticky_posts'   => 1,
	            'paged' => $paged,
	            'posts_per_page'        => $settings['product_per_page']            
	        );

			$product_categories = $settings['product_categories'];
	        $product_cats = str_replace(' ', '', $product_categories);

	        if ( "0" != $product_categories) {
	            if( is_array($product_cats) && count($product_cats) > 0 ){
	                $field_name = is_numeric($product_cats[0])?'term_id':'slug';
	                $args['tax_query'][] = array(
	                    array(
	                        'taxonomy' => 'product_cat',
	                        'terms' => $product_cats,
	                        'field' => $field_name,
	                        'include_children' => false
	                    )
	                );
	            }
	        }

	        switch( $settings['product_product_filter'] ){
	            case 'sale':
	                $args['post__in'] = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
	            break;

	            case 'featured':
	                $args['tax_query'][] = array(
	                    'taxonomy' => 'product_visibility',
	                    'field'    => 'name',
	                    'terms'    => 'featured',
	                    'operator' => 'IN',
	                );
	            break;

	            case 'best_selling':
	                $args['meta_key']   = 'total_sales';
	                $args['orderby']    = 'meta_value_num';
	                $args['order']      = 'desc';
	            break;

	            case 'top_rated': 
	                $args['meta_key']   = '_wc_average_rating';
	                $args['orderby']    = 'meta_value_num';
	                $args['order']      = 'desc';          
	            break;

	            case 'mixed_order':
	                $args['orderby']    = 'rand';
	            break;

	            default: 
	            	/* Recent */
	                $args['orderby']    = 'date';
	                $args['order']      = 'desc';
	            break;
	        }

	        $args['orderby'] = $settings['orderby'] ;
	        $args['order'] = $settings['order'] ;


	        if ($settings['carousel'] == 'yes') {
	        	$carousel = 'owl-carousel owl-theme';

	        	$attr_carousel .= 'data-loop='.$settings['carousel_loop'].' ';
	        	$attr_carousel .= 'data-auto='.$settings['carousel_auto'].' ';
	        	$attr_carousel .= 'data-column='.$settings['carousel_column_desk'].' ';
	        	$attr_carousel .= 'data-column2='.$settings['carousel_column_tablet'].' ';
	        	$attr_carousel .= 'data-column3='.$settings['carousel_column_mobile'].' ';
	        	$attr_carousel .= 'data-spacer='.$settings['carousel_spacer'].' ';

	        	$carousel_arrow = 'no-arrow';
				if ( $settings['carousel_arrow'] == 'yes' ) {
					$carousel_arrow = 'has-arrow';
				}
				$carousel_bullets = 'no-bullets';
				if ( $settings['carousel_bullets'] == 'yes' ) {
					$carousel_bullets = 'has-bullets';
				}

	        }

	        /* Filter Bar */ 
	        $show_filter_product = '';
			if ( $settings['filter_bar'] == 'yes') {					
				$show_filter_product = 'show_filter_product';
				
				$terms_slug = wp_list_pluck( get_terms( 'product_cat','orderby=name&hide_empty=1'), 'slug' );
				$filters_cat = wp_list_pluck( get_terms( 'product_cat','orderby=name&hide_empty=1'), 'name','slug' );
				$cat_order = strtolower($settings['filter_product_categories']);		
			}    
	        /* End Filter Bar */        

			$this->add_render_attribute( 'tf_woo_product', ['id' => "tf-woo-product-{$this->get_id()}", 'class' => ['tf-woo-product', $settings['product_layout'], $settings['position_price'], $settings['product_align'], 'button-full-width-'.$settings['addtocart_fullwidth'], 'carousel-'.$settings['carousel'], $carousel_bullets, $carousel_arrow, $show_filter_product, $settings['filter_type'] ], 'data-tabid' => $this->get_id()] );
			
			$class_products .= 'columns-'.$settings['product_column'].' ';
			$class_products .= $carousel.' ';

					

			$query_products = new WP_Query( $args );
			if( $query_products->have_posts() ):	
				?>		
				<div <?php echo $this->get_render_attribute_string('tf_woo_product'); ?> <?php echo esc_attr($attr_carousel); ?> data-prev_icon="<?php echo esc_attr($settings['carousel_prev_icon']) ?>" data-next_icon="<?php echo esc_attr($settings['carousel_next_icon']) ?>">

					<!-- Filter Bar -->
					<?php 
						if ( $settings['filter_bar'] == 'yes') {
							echo '<div class="filter-bar">';
							if ( $settings['filter_type'] == 'filter_badge' ) {
								$array_1 = $array_2 = $array_3 = $sum_sale = array();
								while( $query_products->have_posts() ): $query_products->the_post();
									if (get_post_meta(get_the_ID(), '_tf_flashsale_text', true)) {
										$array_1[str_replace(' ','-',strtolower(get_post_meta(get_the_ID(), '_tf_flashsale_text', true)))] = get_post_meta(get_the_ID(), '_tf_flashsale_text', true);
									}
									if (get_post_meta(get_the_ID(), '_tf_flashsale_text_2', true)) {
										$array_2[str_replace(' ','-',strtolower(get_post_meta(get_the_ID(), '_tf_flashsale_text_2', true)))] = get_post_meta(get_the_ID(), '_tf_flashsale_text_2', true);
									}
									$sale_price = get_post_meta( get_the_ID(), '_sale_price', true);
									$max_sale[] = intval($sale_price);					
								endwhile; 
								if (max($max_sale) != 0 && max($max_sale) > 0) {
									$array_3['sale'] = 'Sale';
								}
								$array_merge = array_merge($array_1, $array_2, $array_3);
								$filters_badge = array_unique($array_merge);						
								echo '<ul class="products-filter products-filter-badge '.$settings['filter_align'].'"><li class="active" data-filter="all"><a data-filter="*" href="#">' . esc_html__( 'All', 'themesflat-addons-for-elementor' ) . '</a></li>'; 
						
									foreach ($filters_badge as $key => $value) {
										echo '<li data-filter="' . esc_attr( strtolower($key)) . '"><a data-filter=".' . esc_attr( strtolower($key)) . '" href="#" title="' . esc_attr( $value ) . '">' . esc_html( $value ) . '</a></li>'; 
									}
					            echo '</ul>';
					        } 

					        if ($settings['filter_type'] == 'filter_category' ) {
								echo '<ul class="products-filter products-filter-category '.$settings['filter_align'].'"><li class="active"><a data-filter="*" href="#">' . esc_html__( 'All', 'themesflat-addons-for-elementor' ) . '</a></li>'; 
								if ($cat_order == '') {
									foreach ($filters_cat as $key => $value) {
										echo '<li><a data-filter=".' . esc_attr( strtolower($key)) . '" href="#" title="' . esc_attr( $value ) . '">' . esc_html( $value ) . '</a></li>'; 
									}			
								}
								else {
									$cat_order = explode(",", $cat_order);
									foreach ($cat_order as $key) {
										$key = trim($key);
										echo '<li><a data-filter=".' . esc_attr( strtolower($key)) . '" href="#" title="' . esc_attr( $filters_cat[$key] ) . '">' . esc_html( $filters_cat[$key] ) . '</a></li>'; 
									}
								}
					            echo '</ul>';
					        } 				        				        
				        }
					?>
					<!-- End Filter Bar -->

					<!-- Layout Bar -->
					<?php if ( $settings['filter_bar'] == 'yes'): ?>
						<?php if ( $settings['filter_type'] == 'filter_badge' && $settings['toolbar_control'] == 'yes' ): ?>
							<div class="layout-bar">
								<ul class="toolbar-control">
									<li>
										<?php $count_products = $query_products->post_count; ?>
										<span class="woocommerce-result-count" data-count="<?php echo esc_attr($count_products) ?>"> 
											<?php echo esc_attr($count_products) ?> products
										</span>
									</li>
									<li class="toolbar-filter-columns">
								        <span class="shop-columns shop-columns-6" data-columns="6" title="6 Columns">
								            <svg viewBox="0 0 16 16" id="small-view-size" xmlns="http://www.w3.org/2000/svg"><path d="M4.769 3.385c0 .762-.623 1.385-1.385 1.385S2 4.146 2 3.385 2.623 2 3.385 2s1.384.623 1.384 1.385zM9.385 3.385c0 .762-.623 1.385-1.385 1.385s-1.385-.624-1.385-1.385S7.238 2 8 2s1.385.623 1.385 1.385zM4.769 8c0 .762-.623 1.385-1.385 1.385S2 8.762 2 8s.623-1.385 1.385-1.385S4.769 7.238 4.769 8zM9.385 8c0 .762-.623 1.385-1.385 1.385S6.615 8.762 6.615 8 7.238 6.615 8 6.615 9.385 7.238 9.385 8zM4.769 12.615c0 .762-.623 1.385-1.384 1.385S2 13.377 2 12.615s.623-1.385 1.385-1.385 1.384.624 1.384 1.385zM9.385 12.615C9.385 13.377 8.762 14 8 14s-1.385-.623-1.385-1.385.623-1.384 1.385-1.384 1.385.623 1.385 1.384zM14 3.385c0 .762-.623 1.385-1.385 1.385s-1.385-.623-1.385-1.385S11.854 2 12.615 2C13.377 2 14 2.623 14 3.385zM14 8c0 .762-.623 1.385-1.385 1.385S11.231 8.762 11.231 8s.623-1.385 1.385-1.385C13.377 6.615 14 7.238 14 8zM14 12.615c0 .762-.623 1.385-1.385 1.385s-1.385-.623-1.385-1.385.623-1.385 1.385-1.385A1.39 1.39 0 0114 12.615z"></path></svg>
								        </span>
								        <span class="shop-columns shop-columns-5" data-columns="5" title="5 Columns">
								            <svg viewBox="0 0 16 16" id="medium-view-size" xmlns="http://www.w3.org/2000/svg"><path d="M7 4.5C7 5.875 5.875 7 4.5 7S2 5.875 2 4.5 3.125 2 4.5 2 7 3.125 7 4.5zM14 4.5C14 5.875 12.875 7 11.5 7S9 5.875 9 4.5 10.125 2 11.5 2 14 3.125 14 4.5zM7 11.5C7 12.875 5.875 14 4.5 14S2 12.875 2 11.5 3.125 9 4.5 9 7 10.125 7 11.5zM14 11.5c0 1.375-1.125 2.5-2.5 2.5S9 12.875 9 11.5 10.125 9 11.5 9s2.5 1.125 2.5 2.5z"></path></svg>
								        </span>
								        <span class="shop-columns shop-columns-4" data-columns="4" title="4 Columns">
								            <svg viewBox="0 0 16 16" id="large-view-size" xmlns="http://www.w3.org/2000/svg"><path d="M14 8c0 3.3-2.7 6-6 6s-6-2.7-6-6 2.7-6 6-6 6 2.7 6 6z"></path></svg>
								        </span>
								    </li>
									<li class="toolbar-filter-form">
										<span class="toggle-filter-form">
											<svg viewBox="0 0 20 20" id="filter" xmlns="http://www.w3.org/2000/svg"><path fill="none" stroke-width="2" stroke-linejoin="round" stroke-miterlimit="10" d="M12 9v8l-4-4V9L2 3h16z"></path></svg>
											<?php esc_html_e('Filter','themesflat-addons-for-elementor'); ?> 
										</span>
										<div class="wrap-form-filter">										
											<h3 class="widget-title">Filter <span class="close">
												<svg viewBox="0 0 20 20" id="close-delete" xmlns="http://www.w3.org/2000/svg"><path d="M19 2.414L17.586 1 10 8.586 2.414 1 1 2.414 8.586 10 1 17.586 2.414 19 10 11.414 17.586 19 19 17.586 11.414 10z"></path></svg>
											</span></h3>
																				
											<form action="<?php echo site_url() ?>/wp-admin/admin-ajax.php" method="POST" id="form_filter">
											    <div class="product-filter category">
											        <div class="filter-control">
											            <select name="product_cats">
											            	<option value="all">Select a Category</option>
											            	<?php 
															foreach ( $filters_cat as $key => $value ) {
																echo '<option value="' . esc_attr( strtolower($key)) . '">' . esc_html( $value ) . '</option>'; 
															}
											            	?>
											            </select>
											        </div>
											    </div>						    
											    
											    <input type="hidden" name="product_tab_badge" id="product_tab_badge" value="all" />
											    <button type="submit" class="filter-button"><?php esc_html_e('Filter','themesflat-addons-for-elementor'); ?></button>
											    <input type="hidden" name="settings" value="<?php echo base64_encode(serialize($settings)); ?>" />
											    <input type="hidden" name="action" value="tf_product_filter">
											</form>
										</div>
									</li>
								</ul>							
							</div>
						<?php endif; ?>
						</div>
					<?php endif; ?>
					<!-- End Layout Bar -->				

					<?php if ($settings['filter_bar'] == 'yes'): ?>
					<!-- Content Tab -->
						<!-- Filter Badge -->
							<?php if ($settings['filter_type'] == 'filter_badge' ): ?>
							<?php 
							    $tax_query_tab[] = array(
								    'taxonomy' => 'product_visibility',
								    'field'    => 'name',
								    'terms'    => 'featured',
								    'operator' => 'IN',
								);

							    $args_tab = array(
							        'post_type'           => 'product',
							        'post_status'         => 'publish',
							        'ignore_sticky_posts' => 1,
							        'posts_per_page'      => $settings['product_per_page'],
							        'tax_query'          => $tax_query_tab
							    );
							?>
							<div class="content-tab">
								<div class="content-tab-inner tab-inner-all">
									<div class="products <?php echo esc_attr($class_products); ?>">
										<?php $query_products_tab = new WP_Query( $args_tab ); ?>
										<?php while( $query_products_tab->have_posts() ): $query_products_tab->the_post(); ?>
										    <div class="product-item">
										    	<div class="product-inner">
										    		<?php if( has_post_thumbnail() ): ?>
												    	<div class="product-thumb">
												    		<a href="<?php the_permalink();?>" class="image">
								                                <?php 
								                                if ($settings['product_sale'] == 'yes') {
									                                if ($settings['custom_sale'] == 'yes') {
									                                	echo get_sale_price($settings['product_sale'] ,$settings['sale_style'], $settings['enter_sale_text'], $settings['prefix_sale_percent']);
									                                }else {
									                                	woocommerce_show_product_loop_sale_flash();
									                                }
									                            }
								                                echo get_flash_sale($settings['badge']);
								                                
								                                $get_post_thumbnail = get_post_thumbnail_id();
								                                if ( !empty( wc_get_product()->get_gallery_image_ids() ) ){
								                                	$image_id = wc_get_product()->get_gallery_image_ids()[1]; 
								                                	$image_src = wp_get_attachment_image_src ($image_id, 'full');
								                                }				                                
								                                if ( !empty( wc_get_product()->get_gallery_image_ids() ) && $image_src != '' && $settings['image_hover_gallery'] == 'yes' ):
								                                	$image_id = wc_get_product()->get_gallery_image_ids()[1];		                                
								                                	?>
								                                	<img src="<?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_src( $get_post_thumbnail, 'thumbnail', $settings ); ?>" alt="image" class="image_on">	
								                                	<img src="<?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_src( $image_id, 'thumbnail', $settings ); ?>" alt="image" class="image_off">
								                                	<?php
								                                else: ?>			                                
									                                <img src="<?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_src( $get_post_thumbnail, 'thumbnail', $settings ); ?>" alt="image" class="<?php echo 'elementor-animation-'.esc_attr($settings['hover_animation']); ?>">	
								                                <?php endif ?>
								                            </a>

								                            <?php if ( $settings['product_layout'] == 'layout-2' ): ?>
									                            <div class="product-actions">
										                        	<?php woocommerce_template_loop_add_to_cart(); ?>
										                        	<?php 
										                        	if ( class_exists( 'YITH_WCWL' ) && shortcode_exists( 'yith_wcwl_add_to_wishlist') && $settings['wishlist'] == 'yes' ){
																		echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
																	}
																	if ( class_exists( 'YITH_Woocompare' ) && $settings['compare'] == 'yes' ){
																		echo tf_yith_compare_button();
																	}
																	if ( class_exists( 'YITH_WCQV' ) && $settings['quickview'] == 'yes' ){
																		echo tf_yith_quick_view_button();
																	}									
																	?>
										                        </div>  
									                        <?php endif; ?>

									                        <?php if ( $settings['product_layout'] == 'layout-3' ): ?>
									                            <div class="product-actions">
										                        	<?php 
										                        	if ( class_exists( 'YITH_WCWL' ) && shortcode_exists( 'yith_wcwl_add_to_wishlist') && $settings['wishlist'] == 'yes' ){
																		echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
																	}
																	if ( class_exists( 'YITH_Woocompare' ) && $settings['compare'] == 'yes' ){
																		echo tf_yith_compare_button();
																	}
																	if ( class_exists( 'YITH_WCQV' ) && $settings['quickview'] == 'yes' ){
																		echo tf_yith_quick_view_button();
																	}									
																	?>
										                        </div>  
									                        <?php endif; ?>

												    	</div>
											    	<?php endif; ?>
											    	<div class="product-content">
											    		<h4 class="title"><a href="<?php the_permalink();?>"><?php echo get_the_title();?></a></h4>
							                            <?php woocommerce_template_loop_price();?>	

							                            <?php if ($settings['product_layout'] == 'layout-1'): ?>                            
								                            <div class="product-actions">
									                        	<?php woocommerce_template_loop_add_to_cart(); ?>
									                        	<?php 
									                        	if ( class_exists( 'YITH_WCWL' ) && shortcode_exists( 'yith_wcwl_add_to_wishlist') && $settings['wishlist'] == 'yes' ){
																	echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
																}
																if ( class_exists( 'YITH_Woocompare' ) && $settings['compare'] == 'yes' ){
																	echo tf_yith_compare_button();
																}
																if ( class_exists( 'YITH_WCQV' ) && $settings['quickview'] == 'yes' ){
																	echo tf_yith_quick_view_button();
																}									
																?>
									                        </div>  
								                        <?php endif; ?>

								                        <?php if ($settings['product_layout'] == 'layout-3'): ?>                            
								                            <div class="product-actions">
									                        	<?php woocommerce_template_loop_add_to_cart(); ?>
									                        </div>  
								                        <?php endif; ?>  

											    	</div>
											    </div>
										    </div>
									    <?php endwhile; ?>
									    <?php wp_reset_postdata(); ?>
									</div>
								</div>

								<?php foreach ($filters_badge as $key => $value): ?>
									<?php if ($key == 'sale') { break; } ?>							
									<div class="content-tab-inner tab-inner-<?php echo esc_attr($key); ?>">
										<div class="products <?php echo esc_attr($class_products); ?>">
											<?php 
									        $args_tab = array(
									        	'post_type' => 'product',
		    									'post_status' => 'publish',
		    									'tax_query' => $tax_query_tab,
											    'meta_query'    => array(
											        'relation'  => 'OR',
											        array(
											            'key'       => '_tf_flashsale_text',
											            'value'     => $key,
											            'compare'   => '='
											        ),
											        array(
											            'key'       => '_tf_flashsale_text_2',
											            'value'     => $key,
											            'compare'   => '='
											        )
											    )
											);
											$query_products_tab = new WP_Query( $args_tab );
											?>
											<?php while( $query_products_tab->have_posts() ): $query_products_tab->the_post(); ?>
											    <div class="product-item">
											    	<div class="product-inner">
											    		<?php if( has_post_thumbnail() ): ?>
													    	<div class="product-thumb">
													    		<a href="<?php the_permalink();?>" class="image">
									                                <?php 
									                                if ($settings['product_sale'] == 'yes') {
										                                if ($settings['custom_sale'] == 'yes') {
										                                	echo get_sale_price($settings['product_sale'] ,$settings['sale_style'], $settings['enter_sale_text'], $settings['prefix_sale_percent']);
										                                }else {
										                                	woocommerce_show_product_loop_sale_flash();
										                                }
										                            }
									                                echo get_flash_sale($settings['badge']);
									                                
									                                $get_post_thumbnail = get_post_thumbnail_id();
									                                if ( !empty( wc_get_product()->get_gallery_image_ids() ) ){
									                                	$image_id = wc_get_product()->get_gallery_image_ids()[1]; 
									                                	$image_src = wp_get_attachment_image_src ($image_id, 'full');
									                                }				                                
									                                if ( !empty( wc_get_product()->get_gallery_image_ids() ) && $image_src != '' && $settings['image_hover_gallery'] == 'yes' ):
									                                	$image_id = wc_get_product()->get_gallery_image_ids()[1];		                                
									                                	?>
									                                	<img src="<?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_src( $get_post_thumbnail, 'thumbnail', $settings ); ?>" alt="image" class="image_on">	
									                                	<img src="<?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_src( $image_id, 'thumbnail', $settings ); ?>" alt="image" class="image_off">
									                                	<?php
									                                else: ?>			                                
										                                <img src="<?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_src( $get_post_thumbnail, 'thumbnail', $settings ); ?>" alt="image" class="<?php echo 'elementor-animation-'.esc_attr($settings['hover_animation']); ?>">	
									                                <?php endif ?>
									                            </a>

									                            <?php if ( $settings['product_layout'] == 'layout-2' ): ?>
										                            <div class="product-actions">
											                        	<?php woocommerce_template_loop_add_to_cart(); ?>
											                        	<?php 
											                        	if ( class_exists( 'YITH_WCWL' ) && shortcode_exists( 'yith_wcwl_add_to_wishlist') && $settings['wishlist'] == 'yes' ){
																			echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
																		}
																		if ( class_exists( 'YITH_Woocompare' ) && $settings['compare'] == 'yes' ){
																			echo tf_yith_compare_button();
																		}
																		if ( class_exists( 'YITH_WCQV' ) && $settings['quickview'] == 'yes' ){
																			echo tf_yith_quick_view_button();
																		}									
																		?>
											                        </div>  
										                        <?php endif; ?>

										                        <?php if ( $settings['product_layout'] == 'layout-3' ): ?>
										                            <div class="product-actions">
											                        	<?php 
											                        	if ( class_exists( 'YITH_WCWL' ) && shortcode_exists( 'yith_wcwl_add_to_wishlist') && $settings['wishlist'] == 'yes' ){
																			echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
																		}
																		if ( class_exists( 'YITH_Woocompare' ) && $settings['compare'] == 'yes' ){
																			echo tf_yith_compare_button();
																		}
																		if ( class_exists( 'YITH_WCQV' ) && $settings['quickview'] == 'yes' ){
																			echo tf_yith_quick_view_button();
																		}									
																		?>
											                        </div>  
										                        <?php endif; ?>

													    	</div>
												    	<?php endif; ?>
												    	<div class="product-content">
												    		<h4 class="title"><a href="<?php the_permalink();?>"><?php echo get_the_title();?></a></h4>
								                            <?php woocommerce_template_loop_price();?>	

								                            <?php if ($settings['product_layout'] == 'layout-1'): ?>                            
									                            <div class="product-actions">
										                        	<?php woocommerce_template_loop_add_to_cart(); ?>
										                        	<?php 
										                        	if ( class_exists( 'YITH_WCWL' ) && shortcode_exists( 'yith_wcwl_add_to_wishlist') && $settings['wishlist'] == 'yes' ){
																		echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
																	}
																	if ( class_exists( 'YITH_Woocompare' ) && $settings['compare'] == 'yes' ){
																		echo tf_yith_compare_button();
																	}
																	if ( class_exists( 'YITH_WCQV' ) && $settings['quickview'] == 'yes' ){
																		echo tf_yith_quick_view_button();
																	}									
																	?>
										                        </div>  
									                        <?php endif; ?>

									                        <?php if ($settings['product_layout'] == 'layout-3'): ?>                            
									                            <div class="product-actions">
										                        	<?php woocommerce_template_loop_add_to_cart(); ?>
										                        </div>  
									                        <?php endif; ?>  

												    	</div>
												    </div>
											    </div>
										    <?php endwhile; ?>
										    <?php wp_reset_postdata(); ?>
										</div>
									</div>							
								<?php endforeach; ?>

								<div class="content-tab-inner tab-inner-sale">
									<div class="products <?php echo esc_attr($class_products); ?>">
										<?php 
									    $args_tab = array(
									    	'post_type' => 'product',
											'post_status' => 'publish',
											'tax_query' => $tax_query_tab,
										    'meta_query'    => array(
										        'relation'  => 'AND',
										        array(
										            'key'       => '_sale_price',
										            'value'     => 0,
										            'compare'   => '!='
										        ),
										        array(
										            'key'       => '_sale_price',
										            'value'     => 0,
										            'compare'   => '>'
										        )
										    )
										);
										$query_products_tab = new WP_Query( $args_tab );
										?>
										<?php while( $query_products_tab->have_posts() ): $query_products_tab->the_post(); ?>
										    <div class="product-item">
										    	<div class="product-inner">
										    		<?php if( has_post_thumbnail() ): ?>
												    	<div class="product-thumb">
												    		<a href="<?php the_permalink();?>" class="image">
								                                <?php 
								                                if ($settings['product_sale'] == 'yes') {
									                                if ($settings['custom_sale'] == 'yes') {
									                                	echo get_sale_price($settings['product_sale'] ,$settings['sale_style'], $settings['enter_sale_text'], $settings['prefix_sale_percent']);
									                                }else {
									                                	woocommerce_show_product_loop_sale_flash();
									                                }
									                            }
								                                echo get_flash_sale($settings['badge']);
								                                
								                                $get_post_thumbnail = get_post_thumbnail_id();
								                                if ( !empty( wc_get_product()->get_gallery_image_ids() ) ){
								                                	$image_id = wc_get_product()->get_gallery_image_ids()[1]; 
								                                	$image_src = wp_get_attachment_image_src ($image_id, 'full');
								                                }				                                
								                                if ( !empty( wc_get_product()->get_gallery_image_ids() ) && $image_src != '' && $settings['image_hover_gallery'] == 'yes' ):
								                                	$image_id = wc_get_product()->get_gallery_image_ids()[1];		                                
								                                	?>
								                                	<img src="<?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_src( $get_post_thumbnail, 'thumbnail', $settings ); ?>" alt="image" class="image_on">	
								                                	<img src="<?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_src( $image_id, 'thumbnail', $settings ); ?>" alt="image" class="image_off">
								                                	<?php
								                                else: ?>			                                
									                                <img src="<?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_src( $get_post_thumbnail, 'thumbnail', $settings ); ?>" alt="image" class="<?php echo 'elementor-animation-'.esc_attr($settings['hover_animation']); ?>">	
								                                <?php endif ?>
								                            </a>

								                            <?php if ( $settings['product_layout'] == 'layout-2' ): ?>
									                            <div class="product-actions">
										                        	<?php woocommerce_template_loop_add_to_cart(); ?>
										                        	<?php 
										                        	if ( class_exists( 'YITH_WCWL' ) && shortcode_exists( 'yith_wcwl_add_to_wishlist') && $settings['wishlist'] == 'yes' ){
																		echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
																	}
																	if ( class_exists( 'YITH_Woocompare' ) && $settings['compare'] == 'yes' ){
																		echo tf_yith_compare_button();
																	}
																	if ( class_exists( 'YITH_WCQV' ) && $settings['quickview'] == 'yes' ){
																		echo tf_yith_quick_view_button();
																	}									
																	?>
										                        </div>  
									                        <?php endif; ?>

									                        <?php if ( $settings['product_layout'] == 'layout-3' ): ?>
									                            <div class="product-actions">
										                        	<?php 
										                        	if ( class_exists( 'YITH_WCWL' ) && shortcode_exists( 'yith_wcwl_add_to_wishlist') && $settings['wishlist'] == 'yes' ){
																		echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
																	}
																	if ( class_exists( 'YITH_Woocompare' ) && $settings['compare'] == 'yes' ){
																		echo tf_yith_compare_button();
																	}
																	if ( class_exists( 'YITH_WCQV' ) && $settings['quickview'] == 'yes' ){
																		echo tf_yith_quick_view_button();
																	}									
																	?>
										                        </div>  
									                        <?php endif; ?>

												    	</div>
											    	<?php endif; ?>
											    	<div class="product-content">
											    		<h4 class="title"><a href="<?php the_permalink();?>"><?php echo get_the_title();?></a></h4>
							                            <?php woocommerce_template_loop_price();?>	

							                            <?php if ($settings['product_layout'] == 'layout-1'): ?>                            
								                            <div class="product-actions">
									                        	<?php woocommerce_template_loop_add_to_cart(); ?>
									                        	<?php 
									                        	if ( class_exists( 'YITH_WCWL' ) && shortcode_exists( 'yith_wcwl_add_to_wishlist') && $settings['wishlist'] == 'yes' ){
																	echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
																}
																if ( class_exists( 'YITH_Woocompare' ) && $settings['compare'] == 'yes' ){
																	echo tf_yith_compare_button();
																}
																if ( class_exists( 'YITH_WCQV' ) && $settings['quickview'] == 'yes' ){
																	echo tf_yith_quick_view_button();
																}									
																?>
									                        </div>  
								                        <?php endif; ?>

								                        <?php if ($settings['product_layout'] == 'layout-3'): ?>                            
								                            <div class="product-actions">
									                        	<?php woocommerce_template_loop_add_to_cart(); ?>
									                        </div>  
								                        <?php endif; ?>  

											    	</div>
											    </div>
										    </div>
									    <?php endwhile; ?>
									    <?php wp_reset_postdata(); ?>
									</div>
								</div>							
							</div>
							<?php endif; ?>
						<!-- End Filter Badge -->

						<!-- Filter Category -->
							<?php if ($settings['filter_type'] == 'filter_category' ): ?>
							<div class="content-tab">
								<div class="content-tab-inner tab-inner-all">
									<div class="products <?php echo esc_attr($class_products); ?>">					
										<?php while( $query_products->have_posts() ): $query_products->the_post(); ?>
										    <div class="product-item">
										    	<div class="product-inner">
										    		<?php if( has_post_thumbnail() ): ?>
												    	<div class="product-thumb">
												    		<a href="<?php the_permalink();?>" class="image">
								                                <?php 
								                                if ($settings['product_sale'] == 'yes') {
									                                if ($settings['custom_sale'] == 'yes') {
									                                	echo get_sale_price($settings['product_sale'] ,$settings['sale_style'], $settings['enter_sale_text'], $settings['prefix_sale_percent']);
									                                }else {
									                                	woocommerce_show_product_loop_sale_flash();
									                                }
									                            }
								                                echo get_flash_sale($settings['badge']);
								                                
								                                $get_post_thumbnail = get_post_thumbnail_id();
								                                if ( !empty( wc_get_product()->get_gallery_image_ids() ) ){
								                                	$image_id = wc_get_product()->get_gallery_image_ids()[1]; 
								                                	$image_src = wp_get_attachment_image_src ($image_id, 'full');
								                                }				                                
								                                if ( !empty( wc_get_product()->get_gallery_image_ids() ) && $image_src != '' && $settings['image_hover_gallery'] == 'yes' ):
								                                	$image_id = wc_get_product()->get_gallery_image_ids()[1];		                                
								                                	?>
								                                	<img src="<?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_src( $get_post_thumbnail, 'thumbnail', $settings ); ?>" alt="image" class="image_on">	
								                                	<img src="<?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_src( $image_id, 'thumbnail', $settings ); ?>" alt="image" class="image_off">
								                                	<?php
								                                else: ?>			                                
									                                <img src="<?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_src( $get_post_thumbnail, 'thumbnail', $settings ); ?>" alt="image" class="<?php echo 'elementor-animation-'.esc_attr($settings['hover_animation']); ?>">	
								                                <?php endif ?>
								                            </a>

								                            <?php if ( $settings['product_layout'] == 'layout-2' ): ?>
									                            <div class="product-actions">
										                        	<?php woocommerce_template_loop_add_to_cart(); ?>
										                        	<?php 
										                        	if ( class_exists( 'YITH_WCWL' ) && shortcode_exists( 'yith_wcwl_add_to_wishlist') && $settings['wishlist'] == 'yes' ){
																		echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
																	}
																	if ( class_exists( 'YITH_Woocompare' ) && $settings['compare'] == 'yes' ){
																		echo tf_yith_compare_button();
																	}
																	if ( class_exists( 'YITH_WCQV' ) && $settings['quickview'] == 'yes' ){
																		echo tf_yith_quick_view_button();
																	}									
																	?>
										                        </div>  
									                        <?php endif; ?>

									                        <?php if ( $settings['product_layout'] == 'layout-3' ): ?>
									                            <div class="product-actions">
										                        	<?php 
										                        	if ( class_exists( 'YITH_WCWL' ) && shortcode_exists( 'yith_wcwl_add_to_wishlist') && $settings['wishlist'] == 'yes' ){
																		echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
																	}
																	if ( class_exists( 'YITH_Woocompare' ) && $settings['compare'] == 'yes' ){
																		echo tf_yith_compare_button();
																	}
																	if ( class_exists( 'YITH_WCQV' ) && $settings['quickview'] == 'yes' ){
																		echo tf_yith_quick_view_button();
																	}									
																	?>
										                        </div>  
									                        <?php endif; ?>

												    	</div>
											    	<?php endif; ?>
											    	<div class="product-content">
											    		<h4 class="title"><a href="<?php the_permalink();?>"><?php echo get_the_title();?></a></h4>
							                            <?php woocommerce_template_loop_price();?>	

							                            <?php if ($settings['product_layout'] == 'layout-1'): ?>                            
								                            <div class="product-actions">
									                        	<?php woocommerce_template_loop_add_to_cart(); ?>
									                        	<?php 
									                        	if ( class_exists( 'YITH_WCWL' ) && shortcode_exists( 'yith_wcwl_add_to_wishlist') && $settings['wishlist'] == 'yes' ){
																	echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
																}
																if ( class_exists( 'YITH_Woocompare' ) && $settings['compare'] == 'yes' ){
																	echo tf_yith_compare_button();
																}
																if ( class_exists( 'YITH_WCQV' ) && $settings['quickview'] == 'yes' ){
																	echo tf_yith_quick_view_button();
																}									
																?>
									                        </div>  
								                        <?php endif; ?>

								                        <?php if ($settings['product_layout'] == 'layout-3'): ?>                            
								                            <div class="product-actions">
									                        	<?php woocommerce_template_loop_add_to_cart(); ?>
									                        </div>  
								                        <?php endif; ?>  

											    	</div>
											    </div>
										    </div>
									    <?php endwhile; ?>	
									    <span class="loading-icon" style="display: none !important;">
						                    <span class="bubble">
						                        <span class="dot"></span>
						                    </span>
						                    <span class="bubble">
						                        <span class="dot"></span>
						                    </span>
						                    <span class="bubble">
						                        <span class="dot"></span>
						                    </span>
						                </span>			    
									</div>
								</div>
								<?php if ($cat_order == ''): ?>
									<?php foreach ($filters_cat as $key => $value): ?>
										<div class="content-tab-inner tab-inner-<?php echo esc_attr($key); ?>">							
											<div class="products <?php echo esc_attr($class_products); ?>">
												<?php 
												$args_tab_cat = array(  
									                'post_type' => 'product',
									                'post_status' => 'publish',
									                'posts_per_page' => $settings['product_per_page'],      
									                'tax_query' => array(
									                    array(
									                        'taxonomy' => 'product_cat',
									                        'field' => 'slug',
									                        'terms' => $key
									                    )
									                ),                
									            );
												$query_products_tab_cat = new WP_Query( $args_tab_cat );
												?>
												<?php while( $query_products_tab_cat->have_posts() ): $query_products_tab_cat->the_post(); ?>
												    <div class="product-item">
												    	<div class="product-inner">
												    		<?php if( has_post_thumbnail() ): ?>
														    	<div class="product-thumb">
														    		<a href="<?php the_permalink();?>" class="image">
										                                <?php 
										                                if ($settings['product_sale'] == 'yes') {
											                                if ($settings['custom_sale'] == 'yes') {
											                                	echo get_sale_price($settings['product_sale'] ,$settings['sale_style'], $settings['enter_sale_text'], $settings['prefix_sale_percent']);
											                                }else {
											                                	woocommerce_show_product_loop_sale_flash();
											                                }
											                            }
										                                echo get_flash_sale($settings['badge']);
										                                
										                                $get_post_thumbnail = get_post_thumbnail_id();
										                                if ( !empty( wc_get_product()->get_gallery_image_ids() ) ){
										                                	$image_id = wc_get_product()->get_gallery_image_ids()[1]; 
										                                	$image_src = wp_get_attachment_image_src ($image_id, 'full');
										                                }				                                
										                                if ( !empty( wc_get_product()->get_gallery_image_ids() ) && $image_src != '' && $settings['image_hover_gallery'] == 'yes' ):
										                                	$image_id = wc_get_product()->get_gallery_image_ids()[1];		                                
										                                	?>
										                                	<img src="<?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_src( $get_post_thumbnail, 'thumbnail', $settings ); ?>" alt="image" class="image_on">	
										                                	<img src="<?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_src( $image_id, 'thumbnail', $settings ); ?>" alt="image" class="image_off">
										                                	<?php
										                                else: ?>			                                
											                                <img src="<?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_src( $get_post_thumbnail, 'thumbnail', $settings ); ?>" alt="image" class="<?php echo 'elementor-animation-'.esc_attr($settings['hover_animation']); ?>">	
										                                <?php endif ?>
										                            </a>

										                            <?php if ( $settings['product_layout'] == 'layout-2' ): ?>
											                            <div class="product-actions">
												                        	<?php woocommerce_template_loop_add_to_cart(); ?>
												                        	<?php 
												                        	if ( class_exists( 'YITH_WCWL' ) && shortcode_exists( 'yith_wcwl_add_to_wishlist') && $settings['wishlist'] == 'yes' ){
																				echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
																			}
																			if ( class_exists( 'YITH_Woocompare' ) && $settings['compare'] == 'yes' ){
																				echo tf_yith_compare_button();
																			}
																			if ( class_exists( 'YITH_WCQV' ) && $settings['quickview'] == 'yes' ){
																				echo tf_yith_quick_view_button();
																			}									
																			?>
												                        </div>  
											                        <?php endif; ?>

											                        <?php if ( $settings['product_layout'] == 'layout-3' ): ?>
											                            <div class="product-actions">
												                        	<?php 
												                        	if ( class_exists( 'YITH_WCWL' ) && shortcode_exists( 'yith_wcwl_add_to_wishlist') && $settings['wishlist'] == 'yes' ){
																				echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
																			}
																			if ( class_exists( 'YITH_Woocompare' ) && $settings['compare'] == 'yes' ){
																				echo tf_yith_compare_button();
																			}
																			if ( class_exists( 'YITH_WCQV' ) && $settings['quickview'] == 'yes' ){
																				echo tf_yith_quick_view_button();
																			}									
																			?>
												                        </div>  
											                        <?php endif; ?>

														    	</div>
													    	<?php endif; ?>
													    	<div class="product-content">
													    		<h4 class="title"><a href="<?php the_permalink();?>"><?php echo get_the_title();?></a></h4>
									                            <?php woocommerce_template_loop_price();?>	

									                            <?php if ($settings['product_layout'] == 'layout-1'): ?>                            
										                            <div class="product-actions">
											                        	<?php woocommerce_template_loop_add_to_cart(); ?>
											                        	<?php 
											                        	if ( class_exists( 'YITH_WCWL' ) && shortcode_exists( 'yith_wcwl_add_to_wishlist') && $settings['wishlist'] == 'yes' ){
																			echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
																		}
																		if ( class_exists( 'YITH_Woocompare' ) && $settings['compare'] == 'yes' ){
																			echo tf_yith_compare_button();
																		}
																		if ( class_exists( 'YITH_WCQV' ) && $settings['quickview'] == 'yes' ){
																			echo tf_yith_quick_view_button();
																		}									
																		?>
											                        </div>  
										                        <?php endif; ?>

										                        <?php if ($settings['product_layout'] == 'layout-3'): ?>                            
										                            <div class="product-actions">
											                        	<?php woocommerce_template_loop_add_to_cart(); ?>
											                        </div>  
										                        <?php endif; ?>  

													    	</div>
													    </div>
												    </div>
											    <?php endwhile; ?>
											    <?php wp_reset_postdata(); ?>
											</div>									
										</div>
									<?php endforeach; ?>
								<?php else: ?>						
									<?php foreach ($cat_order as $key): ?>
										<?php $key = trim($key); ?>
										<div class="content-tab-inner tab-inner-<?php echo esc_attr( strtolower($key)); ?>">
											<div class="content-tab-inner tab-inner-<?php echo esc_attr($key); ?>">							
												<div class="products <?php echo esc_attr($class_products); ?>">
													<?php 
													$args_tab_cat = array(  
										                'post_type' => 'product',
										                'post_status' => 'publish',
										                'posts_per_page' => $settings['product_per_page'],      
										                'tax_query' => array(
										                    array(
										                        'taxonomy' => 'product_cat',
										                        'field' => 'slug',
										                        'terms' => $key
										                    )
										                ),                
										            );
													$query_products_tab_cat = new WP_Query( $args_tab_cat );
													?>
													<?php while( $query_products_tab_cat->have_posts() ): $query_products_tab_cat->the_post(); ?>
													    <div class="product-item">
													    	<div class="product-inner">
													    		<?php if( has_post_thumbnail() ): ?>
															    	<div class="product-thumb">
															    		<a href="<?php the_permalink();?>" class="image">
											                                <?php 
											                                if ($settings['product_sale'] == 'yes') {
												                                if ($settings['custom_sale'] == 'yes') {
												                                	echo get_sale_price($settings['product_sale'] ,$settings['sale_style'], $settings['enter_sale_text'], $settings['prefix_sale_percent']);
												                                }else {
												                                	woocommerce_show_product_loop_sale_flash();
												                                }
												                            }
											                                echo get_flash_sale($settings['badge']);
											                                
											                                $get_post_thumbnail = get_post_thumbnail_id();
											                                if ( !empty( wc_get_product()->get_gallery_image_ids() ) ){
											                                	$image_id = wc_get_product()->get_gallery_image_ids()[1]; 
											                                	$image_src = wp_get_attachment_image_src ($image_id, 'full');
											                                }				                                
											                                if ( !empty( wc_get_product()->get_gallery_image_ids() ) && $image_src != '' && $settings['image_hover_gallery'] == 'yes' ):
											                                	$image_id = wc_get_product()->get_gallery_image_ids()[1];		                                
											                                	?>
											                                	<img src="<?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_src( $get_post_thumbnail, 'thumbnail', $settings ); ?>" alt="image" class="image_on">	
											                                	<img src="<?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_src( $image_id, 'thumbnail', $settings ); ?>" alt="image" class="image_off">
											                                	<?php
											                                else: ?>			                                
												                                <img src="<?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_src( $get_post_thumbnail, 'thumbnail', $settings ); ?>" alt="image" class="<?php echo 'elementor-animation-'.esc_attr($settings['hover_animation']); ?>">	
											                                <?php endif ?>
											                            </a>

											                            <?php if ( $settings['product_layout'] == 'layout-2' ): ?>
												                            <div class="product-actions">
													                        	<?php woocommerce_template_loop_add_to_cart(); ?>
													                        	<?php 
													                        	if ( class_exists( 'YITH_WCWL' ) && shortcode_exists( 'yith_wcwl_add_to_wishlist') && $settings['wishlist'] == 'yes' ){
																					echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
																				}
																				if ( class_exists( 'YITH_Woocompare' ) && $settings['compare'] == 'yes' ){
																					echo tf_yith_compare_button();
																				}
																				if ( class_exists( 'YITH_WCQV' ) && $settings['quickview'] == 'yes' ){
																					echo tf_yith_quick_view_button();
																				}									
																				?>
													                        </div>  
												                        <?php endif; ?>

												                        <?php if ( $settings['product_layout'] == 'layout-3' ): ?>
												                            <div class="product-actions">
													                        	<?php 
													                        	if ( class_exists( 'YITH_WCWL' ) && shortcode_exists( 'yith_wcwl_add_to_wishlist') && $settings['wishlist'] == 'yes' ){
																					echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
																				}
																				if ( class_exists( 'YITH_Woocompare' ) && $settings['compare'] == 'yes' ){
																					echo tf_yith_compare_button();
																				}
																				if ( class_exists( 'YITH_WCQV' ) && $settings['quickview'] == 'yes' ){
																					echo tf_yith_quick_view_button();
																				}									
																				?>
													                        </div>  
												                        <?php endif; ?>

															    	</div>
														    	<?php endif; ?>
														    	<div class="product-content">
														    		<h4 class="title"><a href="<?php the_permalink();?>"><?php echo get_the_title();?></a></h4>
										                            <?php woocommerce_template_loop_price();?>	

										                            <?php if ($settings['product_layout'] == 'layout-1'): ?>                            
											                            <div class="product-actions">
												                        	<?php woocommerce_template_loop_add_to_cart(); ?>
												                        	<?php 
												                        	if ( class_exists( 'YITH_WCWL' ) && shortcode_exists( 'yith_wcwl_add_to_wishlist') && $settings['wishlist'] == 'yes' ){
																				echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
																			}
																			if ( class_exists( 'YITH_Woocompare' ) && $settings['compare'] == 'yes' ){
																				echo tf_yith_compare_button();
																			}
																			if ( class_exists( 'YITH_WCQV' ) && $settings['quickview'] == 'yes' ){
																				echo tf_yith_quick_view_button();
																			}									
																			?>
												                        </div>  
											                        <?php endif; ?>

											                        <?php if ($settings['product_layout'] == 'layout-3'): ?>                            
											                            <div class="product-actions">
												                        	<?php woocommerce_template_loop_add_to_cart(); ?>
												                        </div>  
											                        <?php endif; ?>  

														    	</div>
														    </div>
													    </div>
												    <?php endwhile; ?>
												    <?php wp_reset_postdata(); ?>
												</div>									
											</div>
										</div>
									<?php endforeach; ?>
								<?php endif; ?>
							</div>
							<?php endif; ?>
						<!-- End Filter Category -->
					<!-- End Content Tab -->
					<?php else: ?>
						<div class="products <?php echo esc_attr($class_products); ?>">					
							<?php while( $query_products->have_posts() ): $query_products->the_post(); ?>
							    <div class="product-item">
							    	<div class="product-inner">
							    		<?php if( has_post_thumbnail() ): ?>
									    	<div class="product-thumb">
									    		<a href="<?php the_permalink();?>" class="image">
					                                <?php 
					                                if ($settings['product_sale'] == 'yes') {
						                                if ($settings['custom_sale'] == 'yes') {
						                                	echo get_sale_price($settings['product_sale'] ,$settings['sale_style'], $settings['enter_sale_text'], $settings['prefix_sale_percent']);
						                                }else {
						                                	woocommerce_show_product_loop_sale_flash();
						                                }
						                            }
					                                echo get_flash_sale($settings['badge']);
					                                
					                                $get_post_thumbnail = get_post_thumbnail_id();
					                                if ( !empty( wc_get_product()->get_gallery_image_ids() ) ){
					                                	$image_id = wc_get_product()->get_gallery_image_ids()[1]; 
					                                	$image_src = wp_get_attachment_image_src ($image_id, 'full');
					                                }				                                
					                                if ( !empty( wc_get_product()->get_gallery_image_ids() ) && $image_src != '' && $settings['image_hover_gallery'] == 'yes' ):
					                                	$image_id = wc_get_product()->get_gallery_image_ids()[1];                   
					                                	?>
					                                	<img src="<?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_src( $get_post_thumbnail, 'thumbnail', $settings ); ?>" alt="image" class="image_on">	
					                                	<img src="<?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_src( $image_id, 'thumbnail', $settings ); ?>" alt="image" class="image_off">
					                                	<?php
					                                else: ?>			                                
						                                <img src="<?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_src( $get_post_thumbnail, 'thumbnail', $settings ); ?>" alt="image" class="<?php echo 'elementor-animation-'.esc_attr($settings['hover_animation']); ?>">	
					                                <?php endif ?>
					                            </a>

					                            <?php if ( $settings['product_layout'] == 'layout-2' ): ?>
						                            <div class="product-actions">
							                        	<?php woocommerce_template_loop_add_to_cart(); ?>
							                        	<?php 
							                        	if ( class_exists( 'YITH_WCWL' ) && shortcode_exists( 'yith_wcwl_add_to_wishlist') && $settings['wishlist'] == 'yes' ){
															echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
														}
														if ( class_exists( 'YITH_Woocompare' ) && $settings['compare'] == 'yes' ){
															echo tf_yith_compare_button();
														}
														if ( class_exists( 'YITH_WCQV' ) && $settings['quickview'] == 'yes' ){
															echo tf_yith_quick_view_button();
														}									
														?>
							                        </div>  
						                        <?php endif; ?>

						                        <?php if ( $settings['product_layout'] == 'layout-3' ): ?>
						                            <div class="product-actions">
							                        	<?php 
							                        	if ( class_exists( 'YITH_WCWL' ) && shortcode_exists( 'yith_wcwl_add_to_wishlist') && $settings['wishlist'] == 'yes' ){
															echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
														}
														if ( class_exists( 'YITH_Woocompare' ) && $settings['compare'] == 'yes' ){
															echo tf_yith_compare_button();
														}
														if ( class_exists( 'YITH_WCQV' ) && $settings['quickview'] == 'yes' ){
															echo tf_yith_quick_view_button();
														}									
														?>
							                        </div>  
						                        <?php endif; ?>

									    	</div>
								    	<?php endif; ?>
								    	<div class="product-content">
								    		<h4 class="title"><a href="<?php the_permalink();?>"><?php echo get_the_title();?></a></h4>
				                            <?php woocommerce_template_loop_price();?>	

				                            <?php if ($settings['product_layout'] == 'layout-1'): ?>                            
					                            <div class="product-actions">
						                        	<?php woocommerce_template_loop_add_to_cart(); ?>
						                        	<?php 
						                        	if ( class_exists( 'YITH_WCWL' ) && shortcode_exists( 'yith_wcwl_add_to_wishlist') && $settings['wishlist'] == 'yes' ){
														echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
													}
													if ( class_exists( 'YITH_Woocompare' ) && $settings['compare'] == 'yes' ){
														echo tf_yith_compare_button();
													}
													if ( class_exists( 'YITH_WCQV' ) && $settings['quickview'] == 'yes' ){
														echo tf_yith_quick_view_button();
													}									
													?>
						                        </div>  
					                        <?php endif; ?>

					                        <?php if ($settings['product_layout'] == 'layout-3'): ?>                            
					                            <div class="product-actions">
						                        	<?php woocommerce_template_loop_add_to_cart(); ?>
						                        </div>  
					                        <?php endif; ?>  

								    	</div>
								    </div>
							    </div>
						    <?php endwhile; ?>			    
						</div>
					<?php endif; ?>

					<?php 
					if ($settings['pagination'] == 'yes') {
						tf_custom_pagination_woo($query_products, $paged, $settings['pagination_style'], $settings['pagination_align']);
					}
					wp_reset_postdata();
					?>
				</div>			
				<?php			
			else:
				echo '<div class="no-found">';
		        	esc_html_e('No product found', 'themesflat-addons-for-elementor');
		        echo '</div>';
			endif;
		}

			

	}	
}