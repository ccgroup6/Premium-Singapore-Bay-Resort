<?php

namespace DethemeKitAddons\Includes\Templates\Types;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'DethemeKit_Structure_Section' ) ) {

	/**
	 * Define DethemeKit_Structure_Section class
	 */
	class DethemeKit_Structure_Section extends DethemeKit_Structure_Base {

		public function get_id() {
            return 'dethemekit_section';
		}

		public function get_single_label() {
			return __( 'Section', 'dethemekit-addons-for-elementor' );
		}

		public function get_plural_label() {
			return __( 'Sections', 'dethemekit-addons-for-elementor' );
		}

		public function get_sources() {
			return array( 'dethemekit-api' );
		}

		public function get_document_type() {
			return array(
				'class' => 'DethemeKit_Section_Document',
				'file'  => DETHEMEKIT_ADDONS_PATH . 'includes/templates/documents/section.php',
			);
		}

		/**
		 * Library settings for current structure
		 *
		 * @return void
		 */
		public function library_settings() {

			return array(
				'show_title'    => false,
				'show_keywords' => true,
			);

		}

	}

}
