<?php
/**
 * Jeg Elementor Kit Class
 *
 * @package jeg-elementor-kit
 * @author Jegstudio
 * @since 1.0.0
 */

namespace Jeg\Elementor_Kit;

use Jeg\Elementor_Kit\Ajax\Ajax;
use Jeg\Elementor_Kit\Dashboard\Dashboard;
use Jeg\Elementor_Kit\Assets\Asset;
use Jeg\Elementor_Kit\Elements\Element;
use Jeg\Elementor_Kit\Templates\Template;

/**
 * Class Init
 *
 * @package Jeg\Elementor_Kit
 */
class Init {
	/**
	 * Class Instance
	 *
	 * @var Init
	 */
	private static $instance;

	/**
	 * Init constructor.
	 */
	private function __construct() {
		$this->setup_init();
		$this->setup_hook();
	}

	/**
	 * Setup Classes
	 */
	private function setup_init() {
		Element::instance();
		Asset::instance();
		Ajax::instance();
		Dashboard::instance();
		Template::instance();
	}

	/**
	 * Setup Hooks
	 */
	private function setup_hook() {
		add_filter( 'body_class', array( $this, 'load_body_class' ) );
		add_action( 'in_plugin_update_message-' . JEG_ELEMENTOR_KIT_BASE, array( $this, 'plugin_update_message' ), 10, 2 );
	}

	/**
	 * Get class instance
	 *
	 * @return Init
	 */
	public static function instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Add body class
	 *
	 * @param array $classes Body classes.
	 */
	public function load_body_class( $classes ) {
		$classes[] = 'jkit-color-scheme';
		return apply_filters( 'jkit_body_classes', $classes );
	}

	/**
	 * Custom Plugin Notice Update
	 *
	 * @since 2.4.3
	 *
	 * @param array  $plugin_data An array of plugin metadata. See get_plugin_data()
	 *                            and the {@see 'plugin_row_meta'} filter for the list
	 *                            of possible values.
	 * @param object $response {
	 *     An object of metadata about the available plugin update.
	 *
	 *     @type string   $id           Plugin ID, e.g. `w.org/plugins/[plugin-name]`.
	 *     @type string   $slug         Plugin slug.
	 *     @type string   $plugin       Plugin basename.
	 *     @type string   $new_version  New plugin version.
	 *     @type string   $url          Plugin URL.
	 *     @type string   $package      Plugin update package URL.
	 *     @type string[] $icons        An array of plugin icon URLs.
	 *     @type string[] $banners      An array of plugin banner URLs.
	 *     @type string[] $banners_rtl  An array of plugin RTL banner URLs.
	 *     @type string   $requires     The version of WordPress which the plugin requires.
	 *     @type string   $tested       The version of WordPress the plugin is tested against.
	 *     @type string   $requires_php The version of PHP which the plugin requires.
	 */
	function plugin_update_message( $plugin_data, $response ) {
		echo '<br><b style="margin-left: 26px;">' . esc_html__( 'It is recommended that you backup your site before updating the plugin so rollback is possible whenever needed.', 'jeg-elementor-kit' ) . '<b>';
	}
}
