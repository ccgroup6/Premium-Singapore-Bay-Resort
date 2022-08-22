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

$max_adults    = abrs_get_option( 'search_form_max_adults' );
$max_children  = abrs_get_option( 'search_form_max_children' );
$max_infants   = abrs_get_option( 'search_form_max_infants' );

$form_classes = array(
	'searchbox',
	'fleurdesel-awebooking',
	'fleurdesel-awebooking--modern',
	abrs_children_bookable() ? 'has-children' : '',
	abrs_infants_bookable() ? 'has-infants' : '',
	( abrs_multiple_hotels() && ! abrs_is_room_type() && $atts['hotel_location'] ) ? 'has-hotel' : '',
);

$action = abrs_get_page_permalink( 'search_results' );
?>
<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $form_classes ) ) ); ?>">
	<div class="apb-shortcode-check-room-js">
		<form id="apb-check-avb-form" method="GET" class="apb-check-avb-form" action="<?php echo esc_url( apply_filters( 'abrs_search_form_action', $action ) ); ?>">
			<?php abrs_search_form_hidden_fields( $atts ); ?>

			<input type="text" data-hotel="rangepicker" style="display: none;"/>

			<div class="awebooking-wrapper">
				<div class="apb-content">
					<div class="row">
						<div class="col-md-6 col-lg-3">
							<label class="apb-field apb-field--arrival">
								<span><?php esc_html_e( 'Arrival Date', 'fleurdesel' ); ?></span>
								<div class="apb-field-group">
									<i class="apbf apbf-select"></i>
									<span class="js-start-date-number apb-calendar"></span>
									<span class="js-fl-start-month"><?php esc_html_e( 'Month', 'fleurdesel' ); ?></span>
								</div>

								<input type="text" class="js-input-modern awebooking-start-date" name="check_in" value="<?php echo isset( $_GET['check_in'] ) ? esc_attr( $_GET['check_in'] ) : ''; ?>" style="opacity: 0;">
							</label>
						</div>

						<div class="col-md-6 col-lg-3">
							<label class="apb-field apb-field--daparture">
								<span><?php esc_html_e( 'Departure Date', 'fleurdesel' ); ?></span>

								<div class="apb-field-group">
									<i class="apbf apbf-select"></i>
									<span class="js-end-date-number apb-calendar"></span>
									<span class="js-fl-end-month"><?php esc_html_e( 'Month', 'fleurdesel' ); ?></span>
								</div>
								<input type="text" class="js-input-modern awebooking-end-date" name="check_out" value="<?php echo isset( $_GET['check_out'] ) ? $_GET['check_out'] : ''; ?>" style="opacity: 0;">
							</label>
						</div>

						<?php if ( $atts['occupancy'] ) : ?>
							<?php if ( $max_adults ) : ?>
								<div class="col-md-6 col-lg-3">
									<label class="apb-field apb-field--adult">
										<span><?php esc_html_e( 'Adults', 'awebooking' ); ?></span>
										<div class="apb-field-group">
											<i class="apbf apbf-select"></i>
											<select name="adults" class="awebooking-select apb-select">
												<?php for ( $i = 1; $i <= $max_adults; $i++ ) : ?>
												<option value="<?php echo esc_attr( $i ); ?>" <?php echo isset( $_GET['adults'] ) ? selected( $_GET['adults'], $i, false ) : ''; ?>><?php echo esc_html( $i ); ?></option>
												<?php endfor; ?>
											</select>
											<span><?php esc_html_e( 'Person', 'fleurdesel' ); ?></span>
										</div>
									</label>
								</div>
							<?php endif; ?>

							<?php if ( abrs_children_bookable() && $max_children ) : ?>
								<div class="col-md-6 col-lg-3">
									<label class="apb-field apb-field--child">
										<span><?php esc_html_e( 'Children', 'awebooking' ); ?></span>
										<div class="apb-field-group">
											<i class="apbf apbf-select"></i>
											<select name="children" class="awebooking-select apb-select">
												<?php for ( $i = 0; $i <= $max_children; $i++ ) : ?>
												<option value="<?php echo esc_attr( $i ); ?>" <?php echo isset( $_GET['children'] ) ? selected( $_GET['children'], $i, false ) : ''; ?>><?php echo esc_html( $i ); ?></option>
												<?php endfor; ?>
											</select>
											<span><?php esc_html_e( 'Person', 'fleurdesel' ); ?></span>
										</div>
									</label>
								</div>
							<?php endif; ?>

							<?php if ( abrs_infants_bookable() && $max_infants ) : ?>
								<div class="col-md-6 col-lg-3">
									<label class="apb-field apb-field--infants">
										<span><?php esc_html_e( 'Infants', 'awebooking' ); ?></span>
										<div class="apb-field-group">
											<i class="apbf apbf-select"></i>
											<select name="infants" class="awebooking-select apb-select">
												<?php for ( $i = 0; $i <= $max_infants; $i++ ) : ?>
												<option value="<?php echo esc_attr( $i ); ?>" <?php echo isset( $_GET['infants'] ) ? selected( $_GET['infants'], $i, false ) : ''; ?>><?php echo esc_html( $i ); ?></option>
												<?php endfor; ?>
											</select>
											<span><?php esc_html_e( 'Person', 'fleurdesel' ); ?></span>
										</div>
									</label>
								</div>
							<?php endif; ?>
						<?php endif; ?>

						<div class="col-lg-12">
							<button type="submit" data-type="single-check" class="apb-btn apb-check-available-js"><?php esc_html_e( 'CHECK AVAILABILITY', 'fleurdesel' ); ?></button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
