<?php
/**
 * Custom AweBooking.
 *
 * @package Fleurdesel
 */

if ( ! class_exists( 'AweBooking' ) ) {
	return;
}

/**
 * Register Custom Awebooking.
 */
class Fleurdesel_Awebooking_Custom {

	/**
	 * Initialize.
	 */
	public static function init() {
		add_action( 'widgets_init', array( __CLASS__, 'widgets_init' ) );
		add_action( 'fleurdesel_room_detail_extra_info', array( __CLASS__, 'room_detail_extra_info' ) );
		add_filter( 'fleurdesel_get_page_title_theme_settings', array( __CLASS__, 'get_single_room_page_title_atts' ), 10, 1 );
		add_filter( 'fleurdesel_page_title', array( __CLASS__, 'get_single_room_page_title' ), 10, 1 );
		add_action( 'fleurdesel_before_page_title_tag', array( __CLASS__, 'get_price_single_room' ), 10, 1 );

		add_action( 'abrs_before_archive_loop', array( __CLASS__, 'archive_room_loop_start' ), 5 );
		add_action( 'abrs_after_archive_loop', array( __CLASS__, 'archive_room_loop_end' ), 15 );

		add_action( 'fleurdesel_room_detail_tabs', array( __CLASS__, 'get_single_room_tabs' ), 10 );
		add_action( 'admin_init', array( __CLASS__, 'migrate_awebooking_v3_1' ) );
	}

	/**
	 * Register awebooking widget area.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
	 */
	public static function widgets_init() {
		register_sidebar([
			'name'          => esc_html__( 'AweBooking', 'fleurdesel' ),
			'id'            => 'awebooking',
			'before_widget' => '<section id="%1$s" class="awebooking-widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		]);
	}

	/**
	 * Check layout has column.
	 */
	public static function has_column() {
		$room_layout = fleurdesel_option( 'room_layout' );
		if ( false !== strpos( $room_layout, 'grid' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Get room column
	 */
	public static function get_column() {
		$room_column = fleurdesel_option( 'room_column' );
		$column_attr = 12 / $room_column;

		return $column_attr;
	}

	/**
	 * Add html tag to before overlay layout.
	 */
	public static function get_before_tag_overlay_layout() {
		$room_layout = fleurdesel_option( 'room_layout' );
		if ( false === strpos( $room_layout, 'overlay' ) ) {
			return;
		}
		$overlay_column = static::get_column();
		?>
		<div class="clearfix" data-cols="<?php echo intval( $overlay_column ); ?>">
		<?php
	}

	/**
	 * Add html tag to after overlay layout.
	 */
	public static function get_after_tag_overlay_layout() {
		$room_layout = fleurdesel_option( 'room_layout' );
		if ( false === strpos( $room_layout, 'overlay' ) ) {
			return;
		}
		?>
		</div>
		<?php
	}

	/**
	 * Get extra info template.
	 */
	public static function room_detail_extra_info() {
		abrs_get_template_part( 'template-parts/single/extra-info' );
	}

	/**
	 * Get loop start HTML for room.
	 */
	public static function archive_room_loop_start() {
		$classes[] = 'room_types';
		$classes[] = 'room-list';

		if ( static::has_column() ) {
			$classes[] = 'row';
		}

		$classes = apply_filters( 'fleurdesel_archive_room_loop_start_classes', $classes );

		echo '<div class="' . esc_attr( join( ' ', $classes ) ) . '">';
		static::get_before_tag_overlay_layout();
	}


	/**
	 * Get loop end HTML for room.
	 */
	public static function archive_room_loop_end() {
		static::get_after_tag_overlay_layout();
	}

	/**
	 * Get page title attributes for event.
	 *
	 * @param  [array] $page_title [page_title].
	 * @return [array]             [page_title]
	 */
	public static function get_single_room_page_title_atts( $page_title ) {
		if ( is_singular( 'room_type' ) && has_post_thumbnail() ) {
			$page_title['page_title_bg_color'] = '';
			$page_title['page_title_bg_image'] = get_the_post_thumbnail_url( get_the_ID(), 'full' );
		}

		return $page_title;
	}

	/**
	 * Get single room title.
	 *
	 * @param  [string] $page_title [page_title].
	 * @return [string]             [page_title].
	 */
	public static function get_single_room_page_title( $page_title ) {
		if ( is_singular( 'room_type' ) ) {
			$page_title = get_the_title();
		}

		return $page_title;
	}

	/**
	 * Gets single room tabs
	 *
	 * @return void
	 */
	public static function get_single_room_tabs() {
		abrs_get_template_part( 'template-parts/single/tabs' );
	}

	/**
	 * Get price for single room in page title.
	 */
	public static function get_price_single_room() {
		if ( ! is_singular( 'room_type' ) ) {
			return;
		}

		$room_type = abrs_get_room_type( get_the_ID() );
		?>
		<h1 class="fleurdesel-title"><?php echo esc_html( $room_type->get_title() ); ?></h1>
		<?php if ( $price_html = abrs_format_price( $room_type->get( 'rack_rate' ) ) ) : ?>
			<p class="fz-14 font-500 letter-spacing-2 text-uppercase white">
				<?php
				/* translators: %s room price */
				printf( esc_html__( 'Start from %s / Night', 'fleurdesel' ), '<span>' . $price_html . '</span>' ); // phpcs:ignore WordPress.Security.EscapeOutput
				?>
			</p>
		<?php endif;
	}

	/**
	 * Migrate awebooking.
	 *
	 * @return void
	 */
	public static function migrate_awebooking_v3_1() {
		if ( get_option( 'fleurdesel_version', false ) ) {
			return;
		}

		// Update amenity icons.
		$amenities = get_terms( array(
			'taxonomy'   => 'hotel_amenity',
			'hide_empty' => false,
		) );

		if ( ! $amenities ) {
			return;
		}

		foreach ( $amenities as $key => $amenity ) {
			$term_id  = $amenity->term_id;
			$new_icon = get_term_meta( $term_id, '_icon', true );
			$old_icon = get_term_meta( $term_id, 'fl_amenity_icon', true );

			if ( $old_icon && ! $new_icon ) {
				update_term_meta( $term_id, '_icon', $old_icon );
				delete_term_meta( $term_id, 'fl_amenity_icon' );
			}
		}

		update_option( 'fleurdesel_version', wp_get_theme()->get( 'Version' ) );
	}
}
Fleurdesel_Awebooking_Custom::init();
