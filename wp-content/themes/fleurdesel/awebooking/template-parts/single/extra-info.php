<?php
global $room_type;

// Room settings.
$area_size = $room_type->get( 'area_size' ) ? esc_html( $room_type->get( 'area_size' ) ) : get_post_meta( $room_type->get_id(), 'fl_apb_area_size', true );
$view = $room_type->get( 'view' ) ? esc_html( $room_type->get( 'view' ) ) : get_post_meta( $room_type->get_id(), 'fl_apb_view', true );
$bedrooms = $room_type->get( 'bedrooms' ) ? absint( $room_type->get( 'bedrooms' ) ) : get_post_meta( $room_type->get_id(), 'fl_apb_bedroom', true );
?>

<div class="fleurdesel-room-extra-info clearfix">
	<?php if ( $area_size ) : ?>
	<div class="fleurdesel-room-extra-info__item">
		<div class="fleurdesel-room-extra-info__wrap">
			<?php fleurdesel_room_area_size_box( $room_type ); ?>
		</div>
	</div>
	<?php endif; ?>

	<?php if ( $view ) : ?>
	<div class="fleurdesel-room-extra-info__item">
		<div class="fleurdesel-room-extra-info__wrap">
			<?php fleurdesel_room_view_box( $room_type ); ?>
		</div>
	</div>
	<?php endif; ?>

	<?php if ( $occupancy = $room_type->get( 'maximum_occupancy' ) ) : ?>
		<div class="fleurdesel-room-extra-info__item">
			<div class="fleurdesel-room-extra-info__wrap">
				<p>
					<i class="fh fh-guest"></i>
					<span><?php printf( "%02d\n", $occupancy ); // Wpcs: xss ok. ?></span><?php esc_html_e( 'Max guest', 'fleurdesel' ); ?>
				</p>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( $bedrooms ) : ?>
	<div class="fleurdesel-room-extra-info__item">
		<div class="fleurdesel-room-extra-info__wrap">
			<?php fleurdesel_bedrooms_box( $room_type ); ?>
		</div>
	</div>
	<?php endif; ?>

	<?php if ( $room_type->get( 'beds' ) ) : ?>
	<div class="fleurdesel-room-extra-info__item">
		<div class="fleurdesel-room-extra-info__wrap">
			<?php fleurdesel_beds_box( $room_type ); ?>
		</div>
	</div>
	<?php endif; ?>
</div>
