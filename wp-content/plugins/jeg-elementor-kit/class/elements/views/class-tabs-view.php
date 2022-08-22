<?php
/**
 * Tabs View Class
 *
 * @package jeg-elementor-kit
 * @author Jegstudio
 * @since 1.8.0
 */

namespace Jeg\Elementor_Kit\Elements\Views;

use Elementor\Plugin;

/**
 * Class Tabs_View
 *
 * @package Jeg\Elementor_Kit\Elements\Views
 */
class Tabs_View extends View_Abstract {
	/**
	 * Build block content
	 */
	public function build_content() {
		$lists   = $this->attribute['sg_content_list'];
		$nav     = $this->render_nav( $lists );
		$content = $this->render_content( $lists );

		return $this->render_wrapper( 'tabs', $nav . $content, array( 'layout-' . esc_attr( $this->attribute['sg_general_layout'] ) ) );
	}

	/**
	 * Render Navigation
	 *
	 * @param array $lists Content lists.
	 */
	public function render_nav( $lists ) {
		$nav           = '';
		$caret         = 'yes' === $this->attribute['st_caret_enable'] ? 'caret-on' : '';
		$icon_enable   = 'yes' === $this->attribute['sg_general_icon_enable'];
		$icon_position = $icon_enable ? 'icon-position-' . esc_attr( $this->attribute['sg_general_icon_position'] ) : '';

		foreach ( $lists as $list ) {
			$active    = 'yes' === $list['sg_content_set_default'] ? 'active' : '';
			$title     = '<span class="tab-title">' . esc_attr( $list['sg_content_list_title'] ) . '</span>';
			$tab_id    = 'tab-' . esc_attr( $list['_id'] );
			$icon_type = $list['sg_content_icon_type'];

			if ( $icon_enable ) {
				if ( 'icon' === $icon_type ) {
					if ( 'icon-position-after' === $icon_position ) {
						$title = $title . $this->render_icon_element( $list['sg_content_icon'] );
					} else {
						$title = $this->render_icon_element( $list['sg_content_icon'] ) . $title;
					}
				} elseif ( 'image' === $icon_type ) {
					if ( 'icon-position-after' === $icon_position ) {
						$title = $title . $this->render_image_element( $list['sg_content_image'], $list['sg_content_image_size_imagesize_size'] );
					} else {
						$title = $this->render_image_element( $list['sg_content_image'], $list['sg_content_image_size_imagesize_size'] ) . $title;
					}
				}
			}

			$nav .= '<li class="tab-nav ' . $active . '" data-tab="' . $tab_id . '">' . $title . '</li>';
		}

		$nav = '<div class="tab-navigation"><ul class="tab-nav-list ' . $icon_position . ' ' . $caret . '">' . $nav . '</ul></div>';

		return $nav;
	}

	/**
	 * Render Content
	 *
	 * @param array $lists Content list.
	 */
	public function render_content( $lists ) {
		$content = '';

		foreach ( $lists as $list ) {
			$active       = 'yes' === $list['sg_content_set_default'] ? 'active' : '';
			$content_type = $list['sg_content_type'];
			$tab_id       = 'tab-' . esc_attr( $list['_id'] );

			if ( 'content' === $content_type ) {
				$content .= '<div class="tab-content ' . $tab_id . ' ' . $active . '">' . wp_kses_post( $list['sg_content_text'] ) . '</div>';
			} elseif ( 'template' === $content_type ) {
				$content .= '<div class="tab-content ' . $tab_id . ' ' . $active . '">' . Plugin::$instance->frontend->get_builder_content( $list['sg_content_template'], true ) . '</div>';
			}
		}

		$content = '<div class="tab-content-list">' . $content . '</div>';

		return $content;
	}
}
