<?php
/**
 * The template for displaying search form standard-3.
 *
 * This template can be overridden by copying it to {yourchild-theme}/awebooking/search-form-standard-3.php.
 *
 * @see      http://docs.awethemes.com/awebooking/developers/theme-developers/
 * @author   awethemes
 * @package  AweBooking
 * @version  3.1.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$max_adults   = abrs_get_option( 'search_form_max_adults' );
$max_children = abrs_get_option( 'search_form_max_children' );
$max_infants  = abrs_get_option( 'search_form_max_infants' );

$form_classes = array(
	'searchbox',
	'fleurdesel-awebooking',
	'fleurdesel-awebooking--standard-2',
	abrs_children_bookable() ? 'has-children' : '',
	abrs_infants_bookable() ? 'has-infants' : '',
	( abrs_multiple_hotels() && ! abrs_is_room_type() && $atts['hotel_location'] ) ? 'has-hotel' : '',
);

$action      = abrs_get_page_permalink( 'search_results' );
$date_format = apply_filters( 'fleurdesel_search_form_standard_2_date_format', 'm/d/Y' );
?>
<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $form_classes ) ) ); ?>">
	<div class="apb-shortcode-check-room-js">
		<form id="apb-check-avb-form" method="GET" class="apb-check-avb-form" action="<?php echo esc_url( apply_filters( 'abrs_search_form_action', $action ) ); ?>">
			<?php abrs_search_form_hidden_fields( $atts ); ?>

			<input type="text" data-hotel="rangepicker" style="display: none;"/>

			<div class="awebooking-wrapper inline">
				<div class="apb-content text-center clearfix">
					<?php if ( abrs_multiple_hotels() && ! abrs_is_room_type() && $atts['hotel_location'] ) : ?>
					<?php
					$current_hotel = abrs_http_request()->get( 'hotel' );
					if ( ! empty( $atts['only_room'] ) && is_numeric( $atts['only_room'] ) ) {
						$current_hotel = abrs_optional( abrs_get_room_type( $atts['only_room'] ) )->get( 'hotel_id' );
					}
					?>
						<div class="apb-field apb-field--hotel">
							<label><?php esc_html_e( 'Hotel', 'awebooking' ); ?></label>
							<div class="apb-field-group">
								<i class="apbf apbf-select"></i>
								<select name="hotel" class="awebooking-select apb-select">
									<?php foreach ( abrs_list_hotels( [], true ) as $hotel ) : ?>
										<option value="<?php echo esc_attr( $hotel->get_id() ); ?>" <?php selected( $hotel->get_id(), $current_hotel ); ?>><?php echo esc_html( $hotel->get( 'name' ) ); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					<?php endif; ?>

					<div class="apb-field apb-field--arrival">
						<label for="arrival"><?php esc_html_e( 'Arrival Date', 'fleurdesel' ); ?></label>
						<div class="apb-field-group searchbox__box--checkin">
							<i class="awebookingf awebookingf-calendar"></i>
							<input type="text" data-bind="value: checkInFormatted('<?php echo esc_attr( $date_format ); ?>')" class="searchbox__input-display awebooking-datepicker awebooking-input awebooking-start-date apb-input apb-calendar" placeholder="<?php esc_attr_e( 'Arrival Date', 'fleurdesel' ); ?>">
							<input type="hidden" data-bind="value: checkInDate" class="searchbox__input searchbox__input--checkin input-transparent" name="check_in" value="<?php echo esc_attr( $res_request['check_in'] ); ?>" placeholder="<?php esc_attr_e( 'Arrival Date', 'fleurdesel' ); ?>" autocomplete="off" aria-haspopup="true" />
						</div>
					</div>

					<div class="apb-field apb-field--departure searchbox__box--checkout">
						<label for="departure"><?php esc_html_e( 'Departure Date', 'fleurdesel' ); ?></label>
						<div class="apb-field-group">
							<i class="awebookingf awebookingf-calendar"></i>
							<input type="text" data-bind="value: checkOutFormatted('<?php echo esc_attr( $date_format ); ?>')" class="searchbox__input-display awebooking-datepicker awebooking-datepicker awebooking-input awebooking-end-date apb-input apb-calendar" placeholder="<?php esc_attr_e( 'Departure Date', 'fleurdesel' ); ?>">
							<input type="hidden" data-bind="value: checkOutDate" class="searchbox__input searchbox__input--checkout input-transparent" name="check_out" value="<?php echo esc_attr( $res_request['check_out'] ); ?>" placeholder="<?php esc_attr_e( 'Departure Date', 'fleurdesel' ); ?>" autocomplete="off" aria-haspopup="true" />
						</div>
					</div>

					<?php if ( $atts['occupancy'] ) : ?>
						<?php if ( $max_adults ) : ?>
						<div class="apb-field apb-field--adult">
							<label for="adults"><?php esc_html_e( 'Adults', 'awebooking' ); ?></label>
							<div class="apb-field-group">
								<i class="apbf apbf-select"></i>
								<select id="adults" name="adults" class="awebooking-select apb-select">
									<?php for ( $i = 1; $i <= $max_adults; $i++ ) : ?>
									<option value="<?php echo esc_attr( $i ); ?>" <?php echo isset( $_GET['adults'] ) ? selected( $_GET['adults'], $i, false ) : ''; ?>><?php echo esc_html( $i ); ?></option>
									<?php endfor; ?>
								</select>
							</div>
						</div>
						<?php endif; ?>

						<?php if ( abrs_children_bookable() && $max_children ) : ?>
						<div class="apb-field apb-field--child">
							<label for="children"><?php esc_html_e( 'Children', 'awebooking' ); ?></label>
							<div class="apb-field-group">
								<i class="apbf apbf-select"></i>
								<select id="children" name="children" class="awebooking-select apb-select">
									<?php for ( $i = 0; $i <= $max_children; $i++ ) : ?>
									<option value="<?php echo esc_attr( $i ); ?>" <?php echo isset( $_GET['children'] ) ? selected( $_GET['children'], $i, false ) : ''; ?>><?php echo esc_html( $i ); ?></option>
									<?php endfor; ?>
								</select>
							</div>
						</div>
						<?php endif; ?>

						<?php if ( abrs_infants_bookable() && $max_infants ) : ?>
						<div class="apb-field apb-field--infants">
							<label for="infants"><?php esc_html_e( 'Infants', 'awebooking' ); ?></label>
							<div class="apb-field-group">
								<i class="apbf apbf-select"></i>
								<select id="infants" name="infants" class="awebooking-select apb-select">
									<?php for ( $i = 0; $i <= $max_infants; $i++ ) : ?>
									<option value="<?php echo esc_attr( $i ); ?>" <?php echo isset( $_GET['infants'] ) ? selected( $_GET['infants'], $i, false ) : ''; ?>><?php echo esc_html( $i ); ?></option>
									<?php endfor; ?>
								</select>
							</div>
						</div>
						<?php endif; ?>
					<?php endif; ?>

					<div class="apb-field apb-field--action">
						<button type="submit" data-type="single-check" class="apb-btn apb-check-available-js"><?php esc_html_e( 'CHECK AVAILABILITY', 'fleurdesel' ); ?></button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
