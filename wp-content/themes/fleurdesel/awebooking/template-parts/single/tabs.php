<?php
/**
 * Single Room type tabs
 *
 * This template can be overridden by copying it to yourtheme/awebooking/single-room-type/tabs/tabs.php.
 *
 * @author  Awethemes
 * @package Awethemes/Templates
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="awebooking-tab">
	<ul class="awebooking-tab__controls apb-product_tab-header">
		<li class="description_tab" id="tab-title-description" role="tab" aria-controls="tab-description">
			<a href="#tab-description"><?php esc_html_e( 'Description', 'fleurdesel' ); ?></a>
		</li>

		<li class="amenities_tab" id="tab-title-amenities" role="tab" aria-controls="tab-amenities">
			<a href="#tab-amenities"><?php esc_html_e( 'Amenities', 'fleurdesel' ); ?></a>
		</li>
	</ul>

	<div class="awebooking-tab__wrapper apb-product_tab-panel tab-content">
		<div class="awebooking-tab__content entry-content" id="tab-description" role="tabpanel" aria-labelledby="tab-title-description">
			<?php abrs_single_room_description(); ?>
		</div>

		<div class="awebooking-tab__content entry-content" id="tab-amenities" role="tabpanel" aria-labelledby="tab-title-amenities">
			<?php abrs_single_room_amenities(); ?>
		</div>
	</div>
</div>
