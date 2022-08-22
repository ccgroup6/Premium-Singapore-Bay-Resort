<?php
namespace DethemeKit\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for breadcrumb.
 *
 * @since 1.0.0
 */
class De_Breadcrumb extends Widget_Base {

  // public function __construct( $data = [], $args = null ) {
	// 	parent::__construct( $data, $args );

  //   // $this->init($data);
  //   wp_localize_script( 'de_breadcrumb_script', 'de_breadcrumbAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ))); 
  //   wp_enqueue_script( 'de_breadcrumb_script' );

  //   $this->init_ajax();
  // }

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'de-breadcrumb';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'De Breadcrumb', 'dethemekit-addons-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-product-breadcrumbs';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'dethemekit-elements' ];
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ 'dethemekit-addons-for-elementor', 'de_breadcrumb_script' ];
	}

	private function get_available_menus() {
		$menus = wp_get_nav_menus();

		$options = [];

		foreach ( $menus as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}

		return $options;
	}

  private function get_breadcrumbs() {
		$menus = wp_get_nav_menus();

		$options = [];

		foreach ( $menus as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}

		return $options;
	}

  private function set_breadcrumb($menu_slug) {
    $menu_items = wp_get_nav_menu_items($menu_slug);
    $post_id = get_the_ID();
    $result = array();
    $return = '';
    $menu_item_id = 0;
    if ($menu_items) {
      foreach( $menu_items as $menu_item ) {
        if( intval($menu_item->object_id) === $post_id ) {
          $menu_item_id = $menu_item->ID;
          break;	
        }  
      }
    }

    if ( isset($menu_items) && ($menu_item_id !== 0) ) {

      $this->recursive_menu_items($menu_items, $menu_item_id, $result);
      $result = array_reverse($result);

      foreach( $result as $breadcrumb_item ) {
        $return .= '|||';
        $return .= $breadcrumb_item->title;
      } 

    } else {
      $return .= '|||';
      $return .= get_the_title($post_id);
    }

    return $return;
  }

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Detheme Kit Breadcrumb', 'dethemekit-addons-for-elementor' ),
			]
		);

    $this->add_control(
			'breadcrumb_source',
			[
				'label' => esc_html__( 'Breadcrumb Source', 'dethemekit-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'dethemekit-addons-for-elementor' ),
					'menu' => esc_html__( 'Menu Structure', 'dethemekit-addons-for-elementor' ),
					'page' => esc_html__( 'Parent/Child Pages', 'dethemekit-addons-for-elementor' ),
				],
				'default' => 'none',
			]
		);

		$menus = $this->get_available_menus();

		if ( ! empty( $menus ) ) {
			$this->add_control(
				'source_menu',
				[
					'label' => __( 'Source Menu', 'dethemekit-addons-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => $menus,
					'default' => array_keys( $menus )[0],
					'save_default' => true,
					'separator' => 'after',
					'description' => sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'dethemekit-addons-for-elementor' ), admin_url( 'nav-menus.php' ) ),
          'condition' => [ 'breadcrumb_source' => 'menu' ],
        ]
			);
		} else {
			$this->add_control(
				'source_menu',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => '<strong>' . __( 'There are no menus in your site.', 'dethemekit-addons-for-elementor' ) . '</strong><br>' . sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to create one.', 'dethemekit-addons-for-elementor' ), admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
					'separator' => 'after',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
          'condition' => [ 'breadcrumb_source' => 'menu' ],
				]
			);
		}

    $repeater = new \Elementor\Repeater();

    $repeater->add_control(
      'breadcrumb_menu_slug',
      [
        'label' => __( 'Slug', 'dethemekit-addons-for-elementor' ),
        'type' => Controls_Manager::TEXT,
        'default' => '',
        // 'condition' => [ 'breadcrumb_source' => 'hidden' ],
      ]
    );  

    $repeater->add_control(
      'breadcrumb_content',
      [
        'label' => __( 'Content', 'dethemekit-addons-for-elementor' ),
        'type' => Controls_Manager::TEXT,
        'default' => '',
        // 'condition' => [ 'breadcrumb_source' => 'hidden' ],
      ]
    );  

    $breadcrumb_menus = wp_get_nav_menus();

    $b_defaults = [];

	$breadcrumb_html_header = '<a href="'.home_url().'" rel="nofollow">' . esc_html__('Home','dethemekit-addons-for-elementor') . '</a>';
	$breadcrumb_html_footer = '';

    foreach ( $breadcrumb_menus as $b_menu ) {
			$b_defaults[] = [
        'breadcrumb_menu_slug' => $b_menu->slug, 
        'breadcrumb_content' => $breadcrumb_html_header . $this->set_breadcrumb($b_menu->slug) . $breadcrumb_html_footer,
      ];
    }

    // print_r($b_defaults);
    // exit;

    $this->add_control(
			'list7',
			[
				'label' => __( 'Repeater List', 'dethemekit-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
        'default' => $b_defaults,
        'condition' => [ 'breadcrumb_source' => 'hidden' ],
			]
		);

		$breadcrumbs = $this->get_breadcrumbs();

		$this->add_control(
			'selected_icon',
			[
				'label' => __( 'Separator Icon', 'dethemekit-addons-for-elementor' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default' => [
					'value' => 'fas fa-chevron-right',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_responsive_control(
			'size',
			[
				'label' => __( 'Separator Size', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .breadcrumbs i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'separator_margin',
			[
				'label' => __( 'Separator Margin', 'dethemekit-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'unit' => 'px',
					'top' => '0',
					'right' => '0',
					'bottom' => '0',
					'left' => '0',
					'isLinked' => false
				],
				'selectors' => [
					'{{WRAPPER}} .breadcrumbs i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'separator_padding',
			[
				'label' => __( 'Separator Padding', 'dethemekit-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'unit' => 'px',
					'top' => '0',
					'right' => '10',
					'bottom' => '0',
					'left' => '10',
					'isLinked' => false
				],
				'selectors' => [
					'{{WRAPPER}} .breadcrumbs i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'dethemekit-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => __( 'Left', 'dethemekit-addons-for-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'dethemekit-addons-for-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => __( 'Right', 'dethemekit-addons-for-elementor' ),
						'icon' => 'eicon-text-align-right',
					]
				],
				'default' => 'flex-start',
				'selectors' => [
					'{{WRAPPER}} .uf-breadcrumbs' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Font', 'dethemekit-addons-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'breadcrumb_color',
			[
				'label' => __( 'Text Color', 'dethemekit-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .breadcrumbs' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'breadcrumb_link_color',
			[
				'label' => __( 'Link Color', 'dethemekit-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .breadcrumbs a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'breadcrumb_link_color_on_hover',
			[
				'label' => __( 'Link Color on hover', 'dethemekit-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .breadcrumbs a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'breadcrumb_icon_color',
			[
				'label' => __( 'Separator Icon Color', 'dethemekit-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .breadcrumbs i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .breadcrumbs',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'selector' => '{{WRAPPER}} .breadcrumbs',
			]
		);

		

		$this->end_controls_section();
	}

	/**
	 * Generate breadcrumbs
	 */
	function get_content_template_breadcrumb() {
?>
    <#
    switch (settings.breadcrumb_source) {
      case 'menu':
        selected = settings.source_menu
        sel_arr = selected.split('-')
    #>
    <# if ( settings.list7.length ) { #>
			<# _.each( settings.list7, function( item ) { 
          if (selected === item.breadcrumb_menu_slug ) {
            bc_content = item.breadcrumb_content
            separator = '<i aria-hidden="true" class="' + settings.selected_icon.value + '"></i>'
            bc_content = bc_content.replaceAll('|||', separator);
      #>
				{{{ bc_content }}}
			<# }}); #>
		<# } #>      
      <?php // $this->get_content_by_menu(); ?>
    <#
        break;
      case 'page':
    #>
        <?php $this->get_content_by_page(); ?>
    <#
        break;  
      default:
    #>
        <?php $this->get_content_by_default(); ?>
    <#
        break;
    }
    #>
<?php
	}

  function get_content_by_default() {
		// echo '<div class="container">';
		// echo '<div class="uf-breadcrumbs">';
		echo '<a href="'.home_url().'" rel="nofollow">' . esc_html__('Home','dethemekit-addons-for-elementor') . '</a>';
    
    if (is_singular('page')) {
      echo '<i aria-hidden="true" class="{{{ settings.selected_icon.value }}}" />';
      the_title();
    } elseif (is_category() || is_single()) {
			echo '<i aria-hidden="true" class="{{{ settings.selected_icon.value }}}" />';
			$categories = get_the_category_list(' &bull; ');
			if (is_single()) {
				if (!empty($categories)) {
					echo $categories;
					echo '<i class="{{{ settings.selected_icon.value }}}" />';
				}
				the_title();
			}
		} elseif (is_search()) {
			echo '<i aria-hidden="true" class="{{{ settings.selected_icon.value }}}" />';
			echo "Search Results for... ";
			echo '"<em>';
			echo the_search_query();
			echo '</em>"';
		}
		// echo '</div></div>';   
  } //function get_content_by_default

  function get_content_by_menu() {
    $settings = $this->get_settings_for_display();

		// echo '<div class="container">';
		// echo '<div class="uf-breadcrumbs">';
		echo '<a href="'.home_url().'" rel="nofollow">' . esc_html__('Home','dethemekit-addons-for-elementor') . '</a>';
    
    if (is_singular('page')) {
      // $menu_items = wp_get_nav_menu_items('breadcrumb-1');
      $menu_items = wp_get_nav_menu_items($settings['source_menu']);
      $post_id = get_the_ID();
      $result = array();
      $menu_item_id = 0;
      if ($menu_items) {
        foreach( $menu_items as $menu_item ) {
          if( intval($menu_item->object_id) === $post_id ) {
            $menu_item_id = $menu_item->ID;
            break;	
          }  
        }
      }
  
      if ( isset($menu_items) && ($menu_item_id !== 0) ) {

        $this->recursive_menu_items($menu_items, $menu_item_id, $result);
        $result = array_reverse($result);

        foreach( $result as $breadcrumb_item ) {
          echo '<i aria-hidden="true" class="{{{ settings.selected_icon.value }}}" data-menu="{{{ settings.source_menu }}}" />';
          echo $breadcrumb_item->title;
        } 

      } else {
        echo '<i aria-hidden="true" class="{{{ settings.selected_icon.value }}}" data-menu="{{{ settings.source_menu }}}" />';
        the_title();
      }

    } elseif (is_category() || is_single()) {
			echo '<i aria-hidden="true" class="{{{ settings.selected_icon.value }}}" />';
			$categories = get_the_category_list(' &bull; ');
			if (is_single()) {
				if (!empty($categories)) {
					echo $categories;
					echo '<i class="{{{ settings.selected_icon.value }}}" />';
				}
				the_title();
			}
		} elseif (is_search()) {
			echo '<i aria-hidden="true" class="{{{ settings.selected_icon.value }}}" />';
			echo "Search Results for... ";
			echo '"<em>';
			echo the_search_query();
			echo '</em>"';
		}
		// echo '</div></div>';   
  } //function get_content_by_menu()

  function get_content_by_page() {
		// echo '<div class="container">';
		// echo '<div class="uf-breadcrumbs">';
		echo '<a href="'.home_url().'" rel="nofollow">' . esc_html__('Home','dethemekit-addons-for-elementor') . '</a>';
    
    if (is_singular('page')) {
      $post_ids = array();
      $post_id = get_the_ID();
      $post_ids[] = $post_id; 

      $post_id = get_post_parent($post_id);
      while ( !is_null($post_id) ) {
        $post_ids[] = $post_id;
        
        $post_id = get_post_parent($post_id);
      }

      $post_ids = array_reverse($post_ids);
      foreach( $post_ids as $post_id ) {
        echo '<i aria-hidden="true" class="{{{ settings.selected_icon.value }}}" />';
        echo get_the_title($post_id);
      }
    } elseif (is_category() || is_single()) {
			echo '<i aria-hidden="true" class="{{{ settings.selected_icon.value }}}" />';
			$categories = get_the_category_list(' &bull; ');
			if (is_single()) {
				if (!empty($categories)) {
					echo $categories;
					echo '<i class="{{{ settings.selected_icon.value }}}" />';
				}
				the_title();
			}
		} elseif (is_search()) {
			echo '<i aria-hidden="true" class="{{{ settings.selected_icon.value }}}" />';
			echo "Search Results for... ";
			echo '"<em>';
			echo the_search_query();
			echo '</em>"';
		}
		// echo '</div></div>';   
  } //function get_content_by_page()

	function get_render_breadcrumb() {
		$settings = $this->get_settings_for_display();

    switch ($settings['breadcrumb_source']) {
      case 'menu':
        $this->get_render_by_menu();
        break;
      case 'page':
        $this->get_render_by_page();
        break;  
      default:
        $this->get_render_by_default();
        break;
    }
	}

  function get_render_by_default() {
		$settings = $this->get_settings_for_display();
		// echo '<div class="container">';
		// echo '<div class="uf-breadcrumbs">';
		echo '<a href="'.home_url().'" rel="nofollow">' . esc_html__('Home','dethemekit-addons-for-elementor') . '</a>';
		if (is_category() || is_single()) {
      Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
			$categories = get_the_category_list(' &bull; ');
			if (is_single()) {
				if (!empty($categories)) {
					echo $categories;
					Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
				}
				the_title();
			}
		} elseif (is_page()) {
			Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
			the_title();
		} elseif (is_search()) {
			Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
			echo "Search Results for... ";
			echo '"<em>';
			echo the_search_query();
			echo '</em>"';
		}
		// echo '</div></div>';
  } // function get_render_by_default()

  function get_render_by_menu() {
		$settings = $this->get_settings_for_display();
		// echo '<div class="container">';
		// echo '<div class="uf-breadcrumbs">';
		echo '<a href="'.home_url().'" rel="nofollow">' . esc_html__('Home','dethemekit-addons-for-elementor') . '</a>';

    if (is_singular('page')) {
      $menu_items = wp_get_nav_menu_items($settings['source_menu']);
      $post_id = get_the_ID();
      $result = array();
      $menu_item_id = 0;
      foreach( $menu_items as $menu_item ) {
        if( intval($menu_item->object_id) === $post_id ) {
          $menu_item_id = $menu_item->ID;
          break;	
        }  
      }
  
      if ( isset($menu_items) && ($menu_item_id !== 0) ) {

        $this->recursive_menu_items($menu_items, $menu_item_id, $result);
        $result = array_reverse($result);

        foreach( $result as $breadcrumb_item ) {
          Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );

          if( intval($breadcrumb_item->object_id) === $post_id ) {
            echo $breadcrumb_item->title;
          } else {
            echo '<a href="'.$breadcrumb_item->url.'" rel="nofollow">' . $breadcrumb_item->title . '</a>';
          }
        } 

      } else {
        Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
        the_title();
      }

    } elseif (is_category() || is_single()) {
      $menu_items = wp_get_nav_menu_items($settings['source_menu']);
      $post_id = get_the_ID();
      $result = array();
      $menu_item_id = 0;
      foreach( $menu_items as $menu_item ) {
        if( intval($menu_item->object_id) === $post_id ) {
          $menu_item_id = $menu_item->ID;
          break;	
        }  
      }
  
      if ( isset($menu_items) && ($menu_item_id !== 0) ) {

        $this->recursive_menu_items($menu_items, $menu_item_id, $result);
        $result = array_reverse($result);

        foreach( $result as $breadcrumb_item ) {
          Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
          if( intval($breadcrumb_item->object_id) === $post_id ) {
            echo $breadcrumb_item->title;
          } else {
            echo '<a href="'.$breadcrumb_item->url.'" rel="nofollow">' . $breadcrumb_item->title . '</a>';
          }
        } 

      } else {
  
        Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
        $categories = get_the_category_list(' &bull; ');
        if (is_single()) {
          if (!empty($categories)) {
            echo $categories;
            Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
          }
          the_title();
        }  

      }

		} elseif (is_search()) {
			Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
			echo "Search Results for... ";
			echo '"<em>';
			echo the_search_query();
			echo '</em>"';
		}
		// echo '</div></div>';
  } // function get_render_by_menu()

  function get_render_by_page() {
		$settings = $this->get_settings_for_display();
		// echo '<div class="container">';
		// echo '<div class="uf-breadcrumbs">';
		echo '<a href="'.home_url().'" rel="nofollow">' . esc_html__('Home','dethemekit-addons-for-elementor') . '</a>';

    if (is_singular('page')) {
      $post_ids = array();
      $post_id = get_the_ID();
      $active_post_id = $post_id; 
      $post_ids[] = $post_id; 

      $post_id = get_post_parent($post_id);
      while ( !is_null($post_id) ) {
        $post_ids[] = $post_id;
        
        $post_id = get_post_parent($post_id);
      }

      $post_ids = array_reverse($post_ids);
      foreach( $post_ids as $post_id ) {
        Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );

        if( $active_post_id === $post_id ) {
          echo get_the_title($post_id);
        } else {
          echo '<a href="' . get_permalink($post_id) . '" rel="nofollow">' . get_the_title($post_id) . '</a>';
        }

      } 

    } elseif (is_category() || is_single()) {
      Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
			$categories = get_the_category_list(' &bull; ');
			if (is_single()) {
				if (!empty($categories)) {
					echo $categories;
					Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
				}
				the_title();
			}
		} elseif (is_search()) {
			Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
			echo "Search Results for... ";
			echo '"<em>';
			echo the_search_query();
			echo '</em>"';
		}
		// echo '</div></div>';
  }

	function recursive_menu_items(array $menu_items, $menu_item_id, array &$target) {
    foreach( $menu_items as $menu_item ) {
      if( $menu_item->ID === $menu_item_id ) {	
        $target[] = $menu_item;
    
        if ( $menu_item->menu_item_parent !== 0 ) {
          $this->recursive_menu_items($menu_items, $menu_item->menu_item_parent, $target);
        } else {
          break;
        }
      }
    }
  }

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		if ( ! function_exists( 'calia_breadcrumbs' ) ) { ?>
			<div class="breadcrumbs">
				<?php $this->get_render_breadcrumb(); ?>
			</div>
		<?php 
		} else {
			do_action( 'calia_breadcrumbs' ); 
		}
	}

	
	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function content_template() {
		if ( ! function_exists( 'calia_breadcrumbs' ) ) { ?>
			<div class="breadcrumbs">
				<?php $this->get_content_template_breadcrumb(); ?>
			</div>
		<?php 
		} else {
			// do_action( 'calia_breadcrumbs' ); 
			$this->get_content_template_breadcrumb();
		}
	}
}
