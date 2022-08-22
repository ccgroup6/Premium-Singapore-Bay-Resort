<?php

namespace DethemeKitAddons\Includes\Templates\Documents;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class DethemeKit_Section_Document extends DethemeKit_Document_Base {
    
     public function get_name() {
		return 'dethemekit_page';
	}

	public static function get_title() {
		return __( 'Section', 'dethemekit-addons-for-elementor' );
	}

	public function has_conditions() {
		return false;
	}

}