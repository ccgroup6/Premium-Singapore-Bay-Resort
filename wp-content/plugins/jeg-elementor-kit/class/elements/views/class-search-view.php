<?php
/**
 * Search View Class
 *
 * @package jeg-elementor-kit
 * @author Jegstudio
 * @since 1.10.0
 */

namespace Jeg\Elementor_Kit\Elements\Views;

/**
 * Class Search_View
 *
 * @package Jeg\Elementor_Kit\Elements\Views
 */
class Search_View extends View_Abstract {
	/**
	 * Build block content
	 */
	public function build_content() {
		$output = null;
		$icon   = $this->render_icon_element( $this->attribute['sg_search_icon'] );
		$type   = $this->attribute['sg_search_style'];

		if ( 'popup' === $type ) {
			$output = $this->render_modal( $icon );
		} else {
			$output = $this->render_form( $icon );
		}

		return $this->render_wrapper( 'search', $output );
	}

	/**
	 * Render Modal
	 *
	 * @param string $icon Rendered icon.
	 */
	private function render_modal( $icon ) {
		return '<a href="#jkit-search-modal-' . $this->unique_id . '" class="jkit-search-modal">' . $icon . '</a>
		<div class="mfp-hide jkit-modal-search-panel" id="jkit-search-modal-' . $this->unique_id . '">
			' . $this->render_form( $icon ) . '
		</div>';
	}

	/**
	 * Render Form
	 *
	 * @param string $icon Rendered icon.
	 */
	private function render_form( $icon ) {
		$language_prefix = function_exists( 'pll_current_language' ) ? pll_current_language() : '';
		$placeholder     = esc_attr( $this->attribute['sg_search_placeholder'] );
		$button_icon     = 'icon' === $this->attribute['sg_search_button_style'] ? $icon : esc_attr( $this->attribute['sg_search_text'] );

		return '<div class="jkit-search-panel">
			<form role="search" method="get" class="jkit-search-group" action="' . esc_url( home_url( '/' . $language_prefix ) ) . '">
				<input type="search" class="jkit-search-field" placeholder="' . $placeholder . '" value="' . esc_attr( get_search_query() ) . '" name="s" />
				<button type="submit" class="jkit-search-button">' . $button_icon . '</button>
			</form>
		</div>';
	}
}
