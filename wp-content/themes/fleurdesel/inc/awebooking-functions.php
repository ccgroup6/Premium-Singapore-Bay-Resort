<?php
/**
 * Room functions.
 *
 * @package Fleurdesel
 */

if ( ! class_exists( 'AweBooking' ) ) {
	return;
}

if ( ! function_exists( 'awebooking_template_loop_price' ) ) :
	/**
	 * Gets loop price template.
	 */
	function awebooking_template_loop_price() {
		abrs_get_template_part( 'template-parts/archive/price' );
	}
endif;

if ( ! function_exists( 'awebooking_template_loop_view_more' ) ) :
	/**
	 * Gets room view more template.
	 */
	function awebooking_template_loop_view_more() {
		abrs_get_template_part( 'template-parts/archive/view-more' );
	}
endif;

if ( ! function_exists( 'awebooking_template_loop_description' ) ) :
	/**
	 * Gets room description template.
	 */
	function awebooking_template_loop_description() {
		abrs_get_template_part( 'template-parts/archive/description' );
	}
endif;

if ( ! function_exists( 'fleurdesel_room_area_size_box' ) ) :
	/**
	 * Room area size box.
	 *
	 * @param  Room_Type $room_type room type obj
	 * @return void
	 */
	function fleurdesel_room_area_size_box( $room_type ) {
		$area_size = $room_type->get( 'area_size' ) ? esc_html( $room_type->get( 'area_size' ) ) : get_post_meta( $room_type->get_id(), 'fl_apb_area_size', true );
		if ( ! $area_size ) {
			return;
		}
		?>
		<p>
			<i class="fh fh-room-size"></i>
			<span><?php printf( "%02d\n", $area_size ); // Wpcs: xss ok. ?></span><?php print abrs_get_measure_unit_label(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</p>
		<?php
	}
endif;

if ( ! function_exists( 'fleurdesel_room_view_box' ) ) :
	/**
	 * Room view box.
	 *
	 * @param  Room_Type $room_type room type obj
	 * @return void
	 */
	function fleurdesel_room_view_box( $room_type ) {
		$view = $room_type->get( 'view' ) ?:
			get_post_meta( $room_type->get_id(), 'fl_apb_view', true );

		if ( ! $view ) {
			return;
		}
		?>
		<p>
			<i class="fh fh-sea-view"></i>
			<?php echo esc_html( $view ); ?>
		</p>
		<?php
	}
endif;

if ( ! function_exists( 'fleurdesel_bedrooms_box' ) ) :
	/**
	 * Room bedrooms box.
	 *
	 * @param  Room_Type $room_type room type obj
	 * @return void
	 */
	function fleurdesel_bedrooms_box( $room_type ) {
		$bedrooms = $room_type->get( 'bedrooms' ) ? absint( $room_type->get( 'bedrooms' ) ) : get_post_meta( $room_type->get_id(), 'fl_apb_bedroom', true );
		if ( ! $bedrooms ) {
			return;
		}
		?>
		<p>
			<i class="fh fh-bedroom"></i>
			<?php
				printf(
					'<span>%1$d </span>%2$s',
					absint( $bedrooms ),
					esc_html( _n( 'Bedroom', 'Bedrooms', absint( $bedrooms ), 'fleurdesel' ) )
				);
			?>
		</p>
		<?php
	}
endif;

if ( ! function_exists( 'fleurdesel_beds_box' ) ) :
	/**
	 * Room beds box.
	 *
	 * @param  Room_Type $room_type room type obj
	 * @return void
	 */
	function fleurdesel_beds_box( $room_type ) {
		if ( ! $room_type->get( 'beds' ) ) {
			return;
		}
		?>
		<p>
			<i class="fh fh-double-bed-02"></i>
			<?php print abrs_get_room_beds( $room_type ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</p>
		<?php
	}
endif;

if ( ! function_exists( 'fleurdesel_room_occupancy_box' ) ) :
	/**
	 * Room occupancy box.
	 *
	 * @param  Room_Type $room_type room type obj
	 * @return void
	 */
	function fleurdesel_room_occupancy_box( $room_type ) {
		if ( ! $occupancy = $room_type->get( 'maximum_occupancy' ) ) {
			return;
		}
		?>
		<p>
			<span><?php printf( "%02d\n", $occupancy ); // Wpcs: xss ok. ?></span><?php esc_html_e( 'Max guest', 'fleurdesel' ); ?>
		</p>
		<?php
	}
endif;

function fleurdesel_get_single_room_layout() {
	$layout = fleurdesel_option( 'room_detail_layout' );

	if ( isset( $_GET['layout'] ) && in_array( $_GET['layout'], [ 'standard', 'modern' ] ) ) {
		$layout = sanitize_text_field( $_GET['layout'] );
	}

	return $layout;
}
