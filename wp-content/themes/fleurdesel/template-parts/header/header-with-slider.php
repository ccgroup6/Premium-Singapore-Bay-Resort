<?php
/**
 * Template for Classic Header
 *
 * @package Fleur de sel
 */

?>
<div class="hidden-md-down">
	<div class="classic-header classic-header--has-slider clearfix">
		<?php if ( fleurdesel_option( 'header_slider' ) ) : ?>
			<div class="classic-header__slider">
				<?php echo do_shortcode( fleurdesel_option( 'header_slider' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</div>
		<?php endif; ?>

		<?php get_template_part( 'template-parts/header/top-header' ); ?>

		<div class="classic-header__nav clearfix">
			<div class="container">

				<div class="fleur-menu-container clearfix">
					<?php

					// Site Logo.
					fleurdesel_site_logo( 'site_logo', true, true, '<div class="classic-logo">', '</div>' );

					if ( has_nav_menu( 'primary' ) ) {
						// Primary Menu.
						wp_nav_menu( array(
							'theme_location' => 'primary',
							'container'      => '',
							'menu_id'        => 'primary-menu',
							'menu_class'     => 'main-navigation',
						) );
					} else { ?>
						<ul class="main-navigation">
							<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'fleurdesel' ); ?></a></li>
						</ul>
					<?php
					}
					?>
				</div><!-- /.awemenu-container -->
			</div><!-- /.container -->
		</div>
	</div>
</div>
