<?php


use ElementorPro\Modules\ThemeBuilder\Module;
use ElementorPro\Core\Utils;
use ElementorPro\Modules\ThemeBuilder\Conditions\Post;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class dtk_Custom_Grid_Conditions  extends ElementorPro\Modules\ThemeBuilder\Conditions\Condition_Base {

	protected $sub_conditions = [];

	public static function get_type() {
		return 'de_grid';
	}

	public function get_name() {
		return 'de_grid';
	}

	public static function get_priority() {
		return 60;
	}

	public function get_label() {
		return __( 'De Grid', 'detheme-kit' );
	}

	public function get_all_label() {
		return __( 'No Conditions', 'detheme-kit' );
	}

	public function register_sub_conditions() {
    		// Last condition.
		$this->sub_conditions[] = 'not_found404';

	}

	public function check( $args ) {
		return false;
	}
}

add_action( 'elementor/theme/register_conditions', function( $conditions_manager ) {
  $conditions_manager->get_condition('general')->register_sub_condition( new dtk_Custom_Grid_Conditions() );

},100);
