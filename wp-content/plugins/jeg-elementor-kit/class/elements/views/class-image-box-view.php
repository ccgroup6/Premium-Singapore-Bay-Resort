<?php
/**
 * Image Box View Class
 *
 * @package jeg-elementor-kit
 * @author Jegstudio
 * @since 1.1.0
 */

namespace Jeg\Elementor_Kit\Elements\Views;

/**
 * Class Image_Box_View
 *
 * @package Jeg\Elementor_Kit\Elements\Views
 */
class Image_Box_View extends View_Abstract {
	/**
	 * Build block content
	 *
	 * @return bool
	 */
	public function build_content() {
		$style           = esc_attr( $this->attribute['sg_image_content_style'] );
		$hover_animation = esc_attr( $this->attribute['st_container_hover_animation'] );

		return $this->render_wrapper( 'image-box', $this->render_image() . $this->render_body(), array( 'style-' . $style, 'elementor-animation-' . $hover_animation ) );
	}

	/**
	 * Render Image
	 *
	 * @return mixed
	 */
	private function render_image() {
		$link                  = $this->attribute['sg_image_link'];
		$link_enable           = $this->attribute['sg_image_link_enable'];
		$image_size            = $this->attribute['sg_image_image_size_imagesize_size'];
		$image_attachment      = $this->attribute['sg_image_choose'];
		$image_hover_animation = esc_attr( $this->attribute['st_image_hover_animation'] );
		$image                 = $this->render_image_element( $image_attachment, $image_size );

		$image = '<div class="image-box-header elementor-animation-' . $image_hover_animation . '">' . $image . '</div>';
		$image = 'yes' === $link_enable ? $this->render_url_element( $link, null, null, $image ) : $image;

		return $image;
	}

	/**
	 * Render Body
	 *
	 * @return mixed
	 */
	private function render_body() {
		$title               = esc_attr( $this->attribute['sg_body_title'] );
		$title_tag           = esc_attr( $this->attribute['sg_body_title_tag'] );
		$title_icon_position = esc_attr( $this->attribute['sg_body_title_icon_position'] );
		$description         = $this->attribute['sg_body_description'];
		$button              = $this->render_button();
		$border_bottom       = $this->render_border_bottom();
		$title_icon          = $this->render_icon_element( $this->attribute['sg_body_title_icon'] );

		if ( 'before' === $title_icon_position ) {
			$title = '<' . $title_tag . ' class="body-title icon-position-' . $title_icon_position . '">' . $title_icon . $title . '</' . $title_tag . '>';
		} else {
			$title = '<' . $title_tag . ' class="body-title icon-position-' . $title_icon_position . '">' . $title . $title_icon . '</' . $title_tag . '>';
		}

		$body =
		'<div class="image-box-body">
            <div class="body-inner">' . $title . '
                <div class="body-description">' . $description . '</div>
                <div class="body-button">' . $button . '</div>
                ' . $border_bottom . '
            </div>
        </div>';

		return $body;
	}

	/**
	 * Render Button
	 *
	 * @return mixed
	 */
	private function render_button() {
		$button = null;

		if ( 'yes' === $this->attribute['sg_button_enable'] ) {
			$icon_position = $this->attribute['sg_button_icon_position'];
			$link          = $this->attribute['sg_button_link'];
			$label         = esc_attr( $this->attribute['sg_button_label'] );
			$icon          = $this->render_icon_element( $this->attribute['sg_button_icon'] );

			if ( 'before' === $icon_position ) {
				$button = $this->render_url_element( $link, null, null, $icon . $label );
			} else {
				$button = $this->render_url_element( $link, null, null, $label . $icon );
			}

			$button = '<div class="button-box icon-position-' . $icon_position . '"><div class="button-wrapper">' . $button . '</div></div>';
		}

		return $button;
	}

	/**
	 * Render Border Bottom
	 *
	 * @return mixed
	 */
	private function render_border_bottom() {
		$border_bottom = null;

		if ( 'yes' === $this->attribute['sg_body_enable_hover_border_bottom'] ) {
			$border_bottom = '<div class="border-bottom ' . esc_attr( $this->attribute['sg_body_hover_direction'] ) . '"></div>';
		}

		return $border_bottom;
	}
}
