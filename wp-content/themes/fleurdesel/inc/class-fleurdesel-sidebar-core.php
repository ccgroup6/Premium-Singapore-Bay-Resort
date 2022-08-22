<?php
/**
 * Sidebar feature support for Fleurdesel theme.
 *
 * @package Fleurdesel
 */

use Skeleton\Metabox;
/**
 * Fleurdesel Sidebar Class.
 */
final class Fleurdesel_Sidebar {
	/**
	 * //
	 *
	 * @var string
	 */
	protected static $cache_setting = array();

	/**
	 * Conditionally hook into WordPress.
	 */
	public static function init() {
		add_action( 'awethemes/theme_options/registers', array( __CLASS__, '_register_metabox' ) );
		add_action( 'awethemes/theme_options/registers', array( __CLASS__, '_register_theme_options' ) );
	}

	/**
	 * Get sidebar name in current screen.
	 *
	 * @return string
	 */
	public static function get_sidebar() {
		return static::has_sidebar() ? static::get_setting( 'name' ) : '';
	}

	/**
	 * Get sidebar area in current screen.
	 *
	 * @return string
	 */
	public static function get_sidebar_area() {
		return static::get_setting( 'area' );
	}

	/**
	 * If current screen is no sidebar.
	 *
	 * @return boolean
	 */
	public static function is_no_sidebar() {
		return static::get_setting( 'area' ) === 'none';
	}

	/**
	 * If current screen have a sidebar.
	 *
	 * @return boolean
	 */
	public static function has_sidebar() {
		return static::get_setting( 'area' ) !== 'none';
	}

	/**
	 * Get sidebar setting in current screen.
	 *
	 * @param  string $get Key name to get.
	 * @return string|array
	 */
	public static function get_setting( $get = null ) {
		if ( $setting = static::$cache_setting ) {
			return isset( $setting[ $get ] ) ? $setting[ $get ] : $setting;
		}

		foreach ( static::allowed_pages() as $id => $name ) {
			$options[ $id ] = array(
				'name' => fleurdesel_option( 'fleurdesel_sidebar_' . $id . '_name', static::default_sidebar( $id ) ),
				'area' => fleurdesel_option( 'fleurdesel_sidebar_' . $id . '_area', static::default_area( $id ) ),
			);
		}
		foreach ( static::allowed_pages() as $id => $name ) {
			$_default = array(
				'name' => static::default_sidebar( $id ),
				'area' => static::default_area(),
			);

			if ( isset( $options[ $id ] ) ) {
				$options[ $id ] = wp_parse_args( $options[ $id ], $_default );
			} else {
				$options[ $id ] = $_default;
			}
		}

		if ( is_home() ) {

			$setting = $options['home'];

		} elseif ( function_exists( 'is_shop' ) && is_shop() ) {

			$setting = $options['shop'];

		} elseif ( function_exists( 'abrs_is_room_type_archive' ) && abrs_is_room_type_archive() ) {

			$setting = $options['room_type'];

		} elseif ( is_tax() || is_archive() || is_search() ) {
			if ( function_exists( 'is_product_taxonomy' ) && is_product_taxonomy() ) {
				$setting = $options['shop_archive'];
			} else {
				$setting = $options['archive'];
			}

			if ( class_exists( 'Fleurdesel_Event_Post_Type' ) && is_post_type_archive( 'fl_event' ) ) {
				$setting = $options['fl_event'];
			}

			$_term = get_queried_object();

			// If we in taxonomy page...
			if ( isset( $_term->term_id ) ) {
				$_meta_data['is_overwrite'] = get_post_meta( $_term->term_id, 'is_overwrite', true );
				$_meta_data['area'] = get_post_meta( $_term->term_id, 'meta_sidebar_area', true );
				$_meta_data['name'] = get_post_meta( $_term->term_id, 'meta_sidebar_name', true );

				if ( is_array( $_meta_data ) && ! empty( $_meta_data['is_overwrite'] ) ) {
					unset( $_meta_data['is_overwrite'] );
					$setting = $_meta_data;
				}
			}
		} elseif ( is_page() ) {
			if ( function_exists( 'is_product' ) && is_product() && isset( $options['product'] ) ) {
				$setting = $options['product'];
			} else {
				$_key = is_single() ? 'single' : 'page';
				$setting = $options[ $_key ];
			}

			$_meta_data['is_overwrite'] = get_post_meta( get_the_ID(), 'is_overwrite', true );
			$_meta_data['area']         = get_post_meta( get_the_ID(), 'meta_sidebar_area', true );
			$_meta_data['name']         = get_post_meta( get_the_ID(), 'meta_sidebar_name', true );
			if ( is_array( $_meta_data ) && ! empty( $_meta_data['is_overwrite'] ) ) {
				unset( $_meta_data['is_overwrite'] );
				$setting = $_meta_data;
			}
		} else {
			$setting = array(
				'name' => static::default_sidebar(),
				'area' => static::default_area(),
			);
		}

		static::$cache_setting = apply_filters( 'fleurdesel_get_sidebar_setting', $setting, $options );

		return isset( static::$cache_setting[ $get ] ) ? static::$cache_setting[ $get ] : static::$cache_setting;
	}

	/**
	 * //
	 *
	 * @param string $id Sidebar area name.
	 * @return string
	 */
	public static function default_sidebar( $id = null ) {
		$default_sidebar = 'sidebar-1';

		if ( in_array( $id, array( 'shop', 'shop_archive' ) ) ) {
			return 'shop';
		}

		if ( 'fl_event' === $id ) {
			return 'fl_event';
		}

		return apply_filters( 'fleurdesel_sidebar_default', $default_sidebar );
	}

	/**
	 * //
	 *
	 * @return string
	 */
	public static function default_area( $id = null ) {
		$default_sidebar = 'right';

		if ( 'fl_event' == $id ) {
			return 'none';
		}

		if ( 'room_type' == $id ) {
			return 'none';
		}

		return apply_filters( 'fleurdesel_sidebar_area_default', $default_sidebar );
	}

	/**
	 * Get default sidebar area.
	 *
	 * @return array
	 */
	public static function sidebar_area() {
		$sidebar_area = array(
			'none'  => esc_html__( 'No Sidebar', 'fleurdesel' ),
			'left'  => esc_html__( 'Sidebar Left', 'fleurdesel' ),
			'right' => esc_html__( 'Sidebar Right', 'fleurdesel' ),
		);

		/**
		 * Apply filter and return sidebar area.
		 */
		return apply_filters( 'fleurdesel_sidebar_area', $sidebar_area );
	}

	/**
	 * Allowed pages can register in customizer.
	 *
	 * @return array
	 */
	protected static function allowed_pages() {
		$pages = array(
			'home'     => esc_html__( 'Blog (Index)', 'fleurdesel' ),
			'archive'  => esc_html__( 'Archive', 'fleurdesel' ),
			'page'     => esc_html__( 'Page', 'fleurdesel' ),
			'single'   => esc_html__( 'Single', 'fleurdesel' ),
		);

		if ( class_exists( 'WooCommerce' ) ) {
			$pages['shop'] = esc_html__( 'Shop', 'fleurdesel' );
		}

		if ( class_exists( 'Fleurdesel_Event_Post_Type' ) ) {
			$pages['fl_event'] = esc_html__( 'Event Archive', 'fleurdesel' );
		}

		if ( class_exists( 'AweBooking' ) ) {
			$pages['room_type'] = esc_html__( 'Room type Archive', 'fleurdesel' );
		}

		return $pages;
	}

	/**
	 * Add settings to the Theme options.
	 *
	 * @param  array $framework object.
	 */
	public static function _register_theme_options( $framework ) {

		$framework->add_section( 'sidebar', function( $tab ) {

			$tab->set( array(
				'title'    => esc_html__( 'Sidebar', 'fleurdesel' ),
				'icon'     => 'dashicons-align-left',
				'priority' => 60,
			) );

			foreach ( static::allowed_pages() as $id => $name ) {

				$id              = sanitize_key( $id );
				$section_id      = sprintf( 'fleurdesel_sidebar_%s', $id );
				$sidebar_id      = sprintf( 'fleurdesel_sidebar_%s_name', $id );
				$sidebar_area_id = sprintf( 'fleurdesel_sidebar_%s_area', $id );
				$tab->add_field(array(
					'id'          => $section_id,
					'name'        => $name,
					'type'        => 'title',
					'description' => sprintf( esc_html__( 'Settings for %s', 'fleurdesel' ), $name ),
				) );

				$tab->add_field(array(
					'id'      => $sidebar_area_id,
					'name'    => esc_html__( 'Sidebar Area', 'fleurdesel' ),
					'type'    => 'select',
					'options' => static::sidebar_area(),
					'default' => static::default_area( $id ),
				) );

				$tab->add_field(array(
					'id'      => $sidebar_id,
					'name'    => esc_html__( 'Sidebar Name', 'fleurdesel' ),
					'type'    => 'select',
					'options' => static::registered_sidebars(),
					'default' => static::default_sidebar( $id ),
					'deps'    => array( $sidebar_area_id, '!=', 'none' ),
				) );
			}
		} );
	}

	/**
	 * Register sidebar metabox.
	 */
	public static function _register_metabox() {
		 // Add 'product' if you want single product sidebar active.
		$sidebar = new Metabox( 'fleurdesel-sidebar', array(
			'id'           => 'fleurdesel-sidebar',
			'title'        => esc_html__( 'Sidebar', 'fleurdesel' ),
			'object_types' => apply_filters( 'fleurdesel_sidebar_metabox_screen', array( 'page', 'post' ) ),
			'taxonomies'   => apply_filters( 'fleurdesel_sidebar_taxonomy', array( 'category', 'post_tag', 'product_cat', 'product_tag' ) ),
			// 'context'   => 'side',
			// 'priority'  => 'low',
			'show_on_cb'   => function( $cmb ) {
				$current_template = get_post_meta( $cmb->object_id(), '_wp_page_template', true );

				if ( $current_template && in_array( $current_template, array( 'page-builder.php' ) ) ) {
					return false;
				}

				return true;
			},
		) );

		$sidebar->add_field( array(
			'name' => esc_html__( 'Use custom sidebar setting?', 'fleurdesel' ),
			'id'   => 'is_overwrite',
			'type' => 'checkbox',
		) );

		$sidebar->add_field(array(
			'id'      => 'meta_sidebar_area',
			'name'    => esc_html__( 'Sidebar Area', 'fleurdesel' ),
			'type'    => 'select',
			'options' => static::sidebar_area(),
			'default' => static::default_area(),
			'deps'    => array( 'is_overwrite', '==', 1 ),
		) );

		$sidebar->add_field(array(
			'id'      => 'meta_sidebar_name',
			'name'    => esc_html__( 'Sidebar Name', 'fleurdesel' ),
			'type'    => 'select',
			'options' => static::registered_sidebars(),
			'default' => static::default_sidebar(),
			'deps'    => array( 'is_overwrite|meta_sidebar_area', '==|!=', '1|none' ),
		) );
	}

	/**
	 * Get WP registered sidebar.
	 *
	 * @return array
	 */
	public static function registered_sidebars() {
		global $wp_registered_sidebars;

		$sidebars = array();

		foreach ( $wp_registered_sidebars as $id => $sidebar ) {
			$sidebars[ $id ] = $sidebar['name'];
		}

		return $sidebars;
	}

	/**
	 * Helper sanitize sidebar name.
	 *
	 * @param  string $sidebar Raw sidebar name.
	 * @return string
	 */
	public static function sanitize_sidebar( $sidebar ) {
		$allowed_sidebars = (array) static::registered_sidebars();

		if ( ! array_key_exists( $sidebar, $allowed_sidebars ) ) {
			$sidebar = static::default_sidebar();
		}

		return $sidebar;
	}

	/**
	 * Helper sanitize sidebar area.
	 *
	 * @param  string $sidebar_area Raw sidebar area.
	 * @return string
	 */
	public static function sanitize_sidebar_area( $sidebar_area ) {
		$allowed_sidebar_area = (array) static::sidebar_area();

		if ( ! array_key_exists( $sidebar_area, $allowed_sidebar_area ) ) {
			$sidebar_area = static::default_area();
		}

		return $sidebar_area;
	}
}

Fleurdesel_Sidebar::init();
