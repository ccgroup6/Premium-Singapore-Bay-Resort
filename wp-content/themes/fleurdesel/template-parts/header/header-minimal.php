<?php
/**
 * Template for header minimal
 *
 * @package Fleurdesel
 */

?>
<div class="hidden-md-down">
	<div class="header-minimal">
		<div class="header-minimal__wrapper">
			<div class="header-minimal__left">
				<a href="#fullscreen-panel" id="fs-panel-open-btn" class="menu-btn header-minimal__menu-btn">
					<span class="menu-icon">
						<span></span>
					</span>
				</a>
			</div>

			<div class="container">
				<?php fleurdesel_site_logo( 'header_minimal_logo', true, true, '<div class="header-minimal__logo">', '</div>' ); ?>
			</div>

			<div class="header-minimal__right">
				<?php $minimal_shortcode = apply_filters( 'fleurdesel_header_minimal_shortcode', '[awebooking_search_form location="" template="standard-2"]' ); ?>
				<?php echo do_shortcode( $minimal_shortcode ); ?>
			</div>
		</div>
	</div> <!-- End .header-minimal -->
</div>
