<?php
/**
 * Template for Classic Header
 *
 * @package Fleur de sel
 */

?>
<div class="hidden-md-down">
	<div class="classic-header classic-header--boxed classic-header--white clearfix<?php echo fleurdesel_option( 'header_sticky' ) ? ' header-sticky' : ''; ?>">
		<?php get_template_part( 'template-parts/header/top-header' ); ?>

		<div class="container">
			<div class="classic-header__nav clearfix">
				<div class="fleur-menu-container clearfix">
					<div class="row">
						<div class="col-lg-3">
							<?php
							// Site Logo.
							fleurdesel_site_logo( 'header_classic_no_transparent_logo', true, true, '<div class="classic-logo">', '</div>' );
							?>
						</div>

						<div class="col-lg-9">
							<?php
							if ( has_nav_menu( 'primary' ) ) {
								// Primary Menu.
								wp_nav_menu( array(
									'theme_location' => 'primary',
									'container'      => '',
									'menu_id'        => 'primary-menu',
									'menu_class'     => 'main-navigation main-navigation--arrow2',
								) );
							} else { ?>
								<ul class="main-navigation">
									<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'fleurdesel' ); ?></a></li>
								</ul>
							<?php } ?>
						</div>
					</div>
				</div><!-- /.container -->
			</div>
		</div><!-- /.awemenu-container -->
	</div>
</div>
