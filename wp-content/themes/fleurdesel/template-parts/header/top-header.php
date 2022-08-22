<?php
/**
 * Template part for top header
 *
 * @package Fleurdesel
 */

if ( ! fleurdesel_option( 'header_top_left_content' ) && ! fleurdesel_option( 'header_top_right_content' ) ) {
	return;
}
?>
<div class="top-header">
	<div class="container">
		<div class="top-header__main icons-bar clearfix">

			<div class="top-header__left">
				<?php echo do_shortcode( wp_kses_post( fleurdesel_option( 'header_top_left_content' ) ) ); ?>
			</div>

			<div class="top-header__right">
				<?php echo do_shortcode( wp_kses_post( fleurdesel_option( 'header_top_right_content' ) ) ); ?>
			</div>
		</div>
	</div>
</div>
