<?php

namespace DethemeKitAddons\Compatibility\WPML\Widgets;

use WPML_Elementor_Module_With_Items;

if ( ! defined('ABSPATH') ) exit; // No access of directly access

/**
 * Grid
 *
 * Registers translatable widget with items.
 *
 * @since 3.1.9
 */
class Grid extends WPML_Elementor_Module_With_Items {

	/**
	 * Retrieve the field name.
	 *
	 * @since 3.1.9
	 * @return string
	 */
	public function get_items_field() {
		return 'dethemekit_gallery_img_content';
	}

	/**
	 * Retrieve the fields inside the repeater.
	 *
	 * @since 3.1.9
	 *
	 * @return array
	 */
	public function get_fields() {
		return array(
			'dethemekit_gallery_img_name',
            'dethemekit_gallery_img_desc'
		);
	}

	/**
	 * Get the title for each repeater string
	 *
	 * @since 3.1.9
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		
        if ( 'dethemekit_gallery_img_name' === $field ) {
			return __( 'Grid: Image Name', 'dethemekit-addons-for-elementor' );
		}
        if ( 'dethemekit_gallery_img_desc' === $field ) {
			return __( 'Grid: Image Description', 'dethemekit-addons-for-elementor' );
		}

		return '';
		
	}

	/**
	 * Get `editor_type` for each repeater string
	 *
	 * @since 3.1.9
	 *
	 * @return string
	 */
	protected function get_editor_type( $field ) {

		if ( 'dethemekit_gallery_img_name' === $field ) {
			return 'LINE';
		}
        if ( 'dethemekit_gallery_img_desc' === $field ) {
			return 'AREA';
		}

		return '';
	}

}
