<?php
/**
 * Template for Classic Header
 *
 * @package Fleur de sel
 */

?>
<div class="hidden-md-down">
	<div class="classic-header classic-header--centered classic-header--white clearfix">
		<div class="centered-logo pt-10 pb-10">
			<?php fleurdesel_site_logo( 'header_centered_logo', true, true, '<div class="centered-logo__logo">', '</div>' ); ?>

			<div class="centered-logo__text">
				<div class="container">
					<div class="row">
						<div class="col-lg-6 centered-logo__text-left">
							<?php echo wp_kses_post( fleurdesel_option( 'header_top_left_content' ) ); ?>
						</div>

						<div class="col-lg-6 text-right centered-logo__text-right">
							<?php echo wp_kses_post( fleurdesel_option( 'header_top_right_content' ) ); ?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="classic-header__nav clearfix">
			<div class="container">

				<div class="fleur-menu-container clearfix">
					<?php
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
