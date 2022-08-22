<?php
/**
 * Template for header mobile
 *
 * @package Fleurdesel
 */

?>
<div class="header-mobile">
	<div class="header-mobile__small-panel">
		<?php fleurdesel_site_logo( 'small_logo', true, true, '<div class="header-mobile__logo-small">', '</div>' ); ?>

		<a href="#side-panel" id="menu-open-btn" class="header-mobile__menu-btn">
			<span class="menu-icon">
				<span></span>
			</span>
		</a>

		<?php if ( fleurdesel_option( 'header_mobile_show_book_btn' ) ) : ?>
			<a href="<?php echo esc_url( get_post_type_archive_link( 'room_type' ) ); ?>" class="header-mobile__button"><?php esc_html_e( 'BOOK', 'fleurdesel' ); ?></a>
		<?php endif; ?>
	</div>

	<div id="side-panel" class="side-panel">
		<div class="side-panel__wrapper">
			<a href="#side-panel" id="menu-close-btn" class="side-panel__close-btn">
				<i class="fa fa-chevron-left"></i>
			</a>

			<div class="side-panel__data">
				<?php
				fleurdesel_site_logo(
					'side_logo',
					true,
					true,
					'<div class="side-panel__logo" style="background-image: url(' . esc_url( fleurdesel_option( 'bg_side_logo' ) ) . ');">', '</div>'
				);
				if ( has_nav_menu( 'mobile-menu' ) ) {
					wp_nav_menu( array(
						'theme_location'  => 'mobile-menu',
						'container'       => 'nav',
						'container_class' => 'side-panel__menu',
						'fallback_cb'     => false,
					) );
				}

				if ( fleurdesel_option( 'side_panel_sidebar' ) ) {
					dynamic_sidebar( fleurdesel_option( 'side_panel_sidebar' ) );
				}
				?>
			</div>
		</div>
	</div>
</div>
