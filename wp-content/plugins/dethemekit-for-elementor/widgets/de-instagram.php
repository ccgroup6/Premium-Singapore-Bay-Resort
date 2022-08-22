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
class De_Instagram extends Widget_Base {

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
		return 'de-instagram';
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
		return __( 'De Instagram', 'detheme-kit' );
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
		return [ 'detheme-kit' ];
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
				'label' => __( 'De Instagram', 'detheme-kit' ),
			]
		);

		$this->add_control(
			'dethemekit_product_ids_manually',
			[
				'label' => __( 'Username', 'dethemekit' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'condition' => [
					'dethemekit_product_grid_product_filter' => 'show_byid_manually',
				]
			]
		);

		

		

		$this->end_controls_section();
	}

	/**
	 * Generate breadcrumbs
	 */
	function get_content_template_breadcrumb() {
		echo '<div class="container">';
		echo '<div class="uf-breadcrumbs">';
		echo '<a href="'.home_url().'" rel="nofollow">Home</a>';
		if (is_category() || is_single()) {
			echo '<i aria-hidden="true" class="{{{ settings.selected_icon.value }}}" />';
			$categories = get_the_category_list(' &bull; ');
			if (is_single()) {
				if (!empty($categories)) {
					echo $categories;
					echo '<i class="{{{ settings.selected_icon.value }}}" />';
				}
				the_title();
			}
		} elseif (is_page()) {
			echo '<i aria-hidden="true" class="{{{ settings.selected_icon.value }}}" />';
			the_title();
		} elseif (is_search()) {
			echo '<i aria-hidden="true" class="{{{ settings.selected_icon.value }}}" />';
			echo "Search Results for... ";
			echo '"<em>';
			echo the_search_query();
			echo '</em>"';
		}
		echo '</div></div>';
	}

	function get_render_breadcrumb() {
		$settings = $this->get_settings_for_display();
		echo '<div class="container">';
		echo '<div class="uf-breadcrumbs">';
		echo '<a href="'.home_url().'" rel="nofollow">Home</a>';
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
		echo '</div></div>';
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
