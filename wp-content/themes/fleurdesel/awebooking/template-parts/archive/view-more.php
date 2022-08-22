<?php
/**
 * The template for displaying view more in the template-parts/archive/content.php template
 *
 * This template can be overridden by copying it to {yourchild-theme}/awebooking/template-parts/archive/view-more.php.
 *
 * @see      http://docs.awethemes.com/awebooking/developers/theme-developers/
 * @author   awethemes
 * @package  AweBooking
 * @version  3.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<p class="mb-0">
	<a href="<?php echo esc_url( get_the_permalink() ); ?>" class="fz-12 font-500 btn-view text-uppercase text-gray-8989 <?php echo isset( $GLOBALS['view_more_class'] ) ? esc_attr( $GLOBALS['view_more_class'] ) : ''; ?>">
		<?php esc_html_e( 'Room Detail', 'fleurdesel' ); ?>
	</a>
</p>
