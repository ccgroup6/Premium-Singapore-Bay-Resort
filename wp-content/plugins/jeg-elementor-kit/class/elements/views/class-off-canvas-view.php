<?php
/**
 * Off Canvas View Class
 *
 * @package jeg-elementor-kit
 * @author Jegstudio
 * @since 1.7.0
 */

namespace Jeg\Elementor_Kit\Elements\Views;

use Elementor\Plugin;

/**
 * Class Off_Canvas_View
 *
 * @package Jeg\Elementor_Kit\Elements\Views
 */
class Off_Canvas_View extends View_Abstract {
	/**
	 * Build block content
	 *
	 * @return string
	 */
	public function build_content() {
		$toggle         = '';
		$panel_position = esc_attr( $this->attribute['st_panel_position'] );
		$toggle_type    = esc_attr( $this->attribute['sg_setting_open_type'] );
		$close_icon     = $this->render_icon_element( $this->attribute['sg_setting_close_icon'] );
		$template       = Plugin::$instance->frontend->get_builder_content( $this->attribute['sg_setting_template'], true );

		if ( 'icon' === $toggle_type ) {
			$icon = $this->render_icon_element( $this->attribute['sg_setting_open_icon'] );

			if ( ! empty( $icon ) ) {
				$toggle = '<a href="#" class="offcanvas-sidebar-button">' . $icon . '</a>';
			}
		} else {
			$text   = esc_attr( $this->attribute['sg_setting_open_text'] );
			$toggle = '<a href="#" class="offcanvas-sidebar-button">' . $text . '</a>';
		}

		if ( ! empty( $close_icon ) ) {
			$close_icon = '<a href="#" class="offcanvas-close-button">' . $close_icon . '</a>';
		}

		$content =
		'<div class="toggle-wrapper">' . $toggle . '</div>
		<div class="offcanvas-sidebar position-' . $panel_position . '">
			<div class="bg-overlay"></div>
			<div class="sidebar-widget">
				<div class="widget-container">
					<div class="widget-heading">' . $close_icon . '</div>
					<div class="widget-content">' . $template . '</div>
				</div>
			</div>
		</div>';

		return $this->render_wrapper( 'off-canvas', $content );
	}
}
