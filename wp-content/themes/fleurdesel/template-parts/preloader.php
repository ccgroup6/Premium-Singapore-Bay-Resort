<?php
/**
 * Template part for preloader.
 *
 * @package Fleurdesel
 */

if ( ! fleurdesel_option( 'preloader_show' ) ) {
	return;
}
?>
<!-- PRELOADER -->
<div class="preloader" id="preloader">
	<div class="spin"></div>
	<?php fleurdesel_site_logo( 'preloader_logo', false, true ); ?>
</div>
