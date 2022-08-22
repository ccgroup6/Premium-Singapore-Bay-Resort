<?php
/**
 * Fullscreen menu
 *
 * @package Fleurdesel
 */

?>
<div class="fs-panel" id="fullscreen-panel">
	<a href="#fullscreen-panel" id="fs-panel-close-btn" class="fs-panel__close-btn">
		<span class="close-icon"></span>
	</a>

	<div class="fs-panel__bg" style="background-image: url(<?php echo esc_url( fleurdesel_option( 'bg_fs_panel' ) ); ?>);"></div>

	<div class="fs-panel__content pt-70 pb-70">
		<div class="container">
			<div class="row">
				<div class="col-lg-6">
					<?php
					if ( has_nav_menu( 'primary' ) ) {
						wp_nav_menu( array(
							'theme_location'  => 'primary',
							'container'       => 'nav',
							'container_class' => 'fs-panel__menu',
							'fallback_cb'     => false,
						) );
					}

					if ( fleurdesel_option( 'fs_panel_left_sidebar' ) ) {
						dynamic_sidebar( fleurdesel_option( 'fs_panel_left_sidebar' ) );
					}
					?>
				</div>

				<div class="col-lg-6">
					<?php
					if ( fleurdesel_option( 'fs_panel_right_sidebar' ) ) {
						dynamic_sidebar( fleurdesel_option( 'fs_panel_right_sidebar' ) );
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>
