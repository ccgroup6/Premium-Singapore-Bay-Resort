<?php
/**
 * The template for displaying room gallery in the template-parts/single/content.php template
 *
 * This template can be overridden by copying it to {yourtheme}/awebooking/template-parts/single/gallery.php.
 *
 * @see      http://docs.awethemes.com/awebooking/developers/theme-developers/
 * @author   awethemes
 * @package  AweBooking
 * @version  3.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $post, $room_type;

$attachment_ids = $room_type->get_gallery_ids();

if ( has_post_thumbnail() ) {
	array_unshift( $attachment_ids, get_post_thumbnail_id() );
}

if ( empty( $attachment_ids ) ) {
	return;
}
?>

<div class="fleurdesel-apb-carousel">
	<span class="fleurdesel-apb-carousel__icon fleurdesel-apb-carousel__icon--zoom-in"><i class="fh fh-full"></i></span>
	<span class="fleurdesel-apb-carousel__icon fleurdesel-apb-carousel__icon--zoom-out"><i class="fh fh-close"></i></span>
	<div class="fleurdesel-apb-carousel__product-image" data-init="slick" data-fade="true" data-autoplay="false" data-slides-to-show="1" data-slides-to-scroll="1" data-arrows="true" data-dots="false" data-as-nav-for=".fleurdesel-apb-carousel__product-thumb">
		<?php foreach ( $attachment_ids as $attachment_id ) : ?>
			<div class="fleurdesel-apb-carousel__item">
				<span class="fleurdesel-apb-carousel__img-bg" style="background-image: url(<?php echo wp_get_attachment_image_url( $attachment_id, isset( $GLOBALS['fleurdesel_room_img_size'] ) ? $GLOBALS['fleurdesel_room_img_size'] : 'full' ); // WPCS: XSS OK. ?>);"></span>
			</div>
		<?php endforeach; ?>
	</div>
</div>

<div class="fleurdesel-apb-carousel__product-thumb" data-init="slick" data-fade="false" data-autoplay="false" data-slides-to-show="<?php echo isset( $GLOBALS['slides_to_show'] ) ? absint( $GLOBALS['slides_to_show'] ) : 5; ?>" data-slides-to-scroll="1" data-arrows="false" data-dots="false" data-infinite="true" data-center-mode="false" data-center-padding="0" data-focus-on-select="true" data-as-nav-for=".fleurdesel-apb-carousel__product-image">
	<?php foreach ( $attachment_ids as $attachment_id ) : ?>
		<div class="fleurdesel-apb-carousel__item-thumb">
			<?php echo wp_get_attachment_image( $attachment_id, 'post-thumbnail' ); ?>
		</div>
	<?php endforeach; ?>
</div>
