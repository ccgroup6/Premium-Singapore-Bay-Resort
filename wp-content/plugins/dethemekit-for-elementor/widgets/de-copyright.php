<?php
/**
 * Elementor Classes.
 *
 * @package DethemeKit
 */

namespace DethemeKit\Widgets;

use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * Elementor Copyright
 *
 * Elementor widget for copyright.
 *
 * @since 1.2.0
 */
class De_Copyright extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.2.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'copyright';
	}
	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.2.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'De Copyright', 'detheme-kit' );
	}
	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.2.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-footer';
	}
	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.2.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'dethemekit-elements' ];
	}
	/**
	 * Register Copyright controls.
	 *
	 * @since 1.2.0
	 * @access protected
	 */
	protected function _register_controls() { //phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
		$this->register_content_copy_right_controls();
	}
	/**
	 * Register Copyright General Controls.
	 *
	 * @since 1.2.0
	 * @access protected
	 */
	protected function register_content_copy_right_controls() {
		$this->start_controls_section(
			'section_title',
			[
				'label' => __( 'Copyright', 'detheme-kit' ),
			]
		);

		$this->add_control(
			'shortcode',
			[
				'label'   => __( 'Copyright Text', 'detheme-kit' ),
				'type'    => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'default' => __( 'Copyright © [dtk_current_year] [dtk_site_title] | Powered by [dtk_site_title]', 'detheme-kit' ),
			]
		);

		$this->add_control(
			'link',
			[
				'label'       => __( 'Link', 'detheme-kit' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'detheme-kit' ),
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'     => __( 'Alignment', 'detheme-kit' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'detheme-kit' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'detheme-kit' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'detheme-kit' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .dtk-copyright-wrapper' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => __( 'Text Color', 'detheme-kit' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Color::get_type(),
					'value' => Color::COLOR_3,
				],
				'selectors' => [
					// Stronger selector to avoid section style from overwriting.
					'{{WRAPPER}} .dtk-copyright-wrapper a, {{WRAPPER}} .dtk-copyright-wrapper' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'caption_typography',
				'selector' => '{{WRAPPER}} .dtk-copyright-wrapper, {{WRAPPER}} .dtk-copyright-wrapper a',
				'scheme'   => Typography::TYPOGRAPHY_3,
			]
		);
	}

	/**
	 * Render Copyright output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.2.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$link     = isset( $settings['link']['url'] ) ? $settings['link']['url'] : '';

		if ( ! empty( $settings['link']['nofollow'] ) ) {
			$this->add_render_attribute( 'link', 'rel', 'nofollow' );
		}
		if ( ! empty( $settings['link']['is_external'] ) ) {
			$this->add_render_attribute( 'link', 'target', '_blank' );
		}

		$copy_right_shortcode = do_shortcode( shortcode_unautop( $settings['shortcode'] ) ); ?>
		<div class="dtk-copyright-wrapper">
			<?php if ( ! empty( $link ) ) { ?>
				<a href="<?php echo esc_url( $link ); ?>" <?php echo $this->get_render_attribute_string( 'link' ); ?>>
					<span><?php echo wp_kses_post( $copy_right_shortcode ); ?></span>
				</a>
			<?php } else { ?>
				<span><?php echo wp_kses_post( $copy_right_shortcode ); ?></span>
			<?php } ?>
		</div>
		<?php
	}

	/**
	 * Render shortcode widget as plain content.
	 *
	 * Override the default behavior by printing the shortcode instead of rendering it.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function render_plain_content() {
		// In plain mode, render without shortcode.
		echo esc_attr( $this->get_settings( 'shortcode' ) );
	}

	/**
	 * Render shortcode widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function content_template() {}

	/**
	 * Render shortcode output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * Remove this after Elementor v3.3.0
	 *
	 * @since 1.2.0
	 * @access protected
	 */
	// protected function _content_template() {
	// 	$this->content_template();
	// }
}

/**
 * Helper class for the Copyright.
 *
 * @since 1.2.0
 */
class De_Copyright_Shortcode {

	/**
	 * Constructor.
	 */
	public function __construct() {

		add_shortcode( 'dtk_current_year', [ $this, 'display_current_year' ] );
		add_shortcode( 'dtk_site_title', [ $this, 'display_site_title' ] );
	}

	/**
	 * Get the dtk_current_year Details.
	 *
	 * @return array $dtk_current_year Get Current Year Details.
	 */
	public function display_current_year() {

		$dtk_current_year = gmdate( 'Y' );
		$dtk_current_year = do_shortcode( shortcode_unautop( $dtk_current_year ) );
		if ( ! empty( $dtk_current_year ) ) {
			return $dtk_current_year;
		}
	}

	/**
	 * Get site title of Site.
	 *
	 * @return string.
	 */
	public function display_site_title() {

		$dtk_site_title = get_bloginfo( 'name' );

		if ( ! empty( $dtk_site_title ) ) {
			return $dtk_site_title;
		}
	}

}

new De_Copyright_Shortcode();