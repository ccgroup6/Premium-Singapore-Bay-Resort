<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Fleurdesel
 */

get_header( '404' ); ?>

	<section class="page-404 not-found" style="background-image: url(<?php echo esc_url( fleurdesel_option( 'bg_404' ) ); ?>);">
		<div class="page-404__wrapper">
			<div class="container">
				<div class="page-404__content">
					<div class="page-404__logo">
						<a href="<?php echo esc_url( home_url( '/' ) ) ?>">
							<img src="<?php echo esc_url( fleurdesel_option( '404_logo' ) ); ?>" alt="Footer Logo">
						</a>
					</div>

					<div class="page-404__subtitle"><?php esc_html_e( 'OOPS!', 'fleurdesel' ); ?></div>

					<div class="page-404__title"><?php esc_html_e( '404', 'fleurdesel' ); ?></div>

					<div class="page-404__desc">
						<?php
						echo wp_kses_post( nl2br( __( "Error The Resource requested\ncould not be found\non this sever", 'fleurdesel' ) ) );
						?>
					</div>

					<a href="<?php echo esc_url( home_url( '/' ) ) ?>" class="page-404__button"><?php esc_html_e( 'HOME PAGE &rarr;', 'fleurdesel' ); ?></a>
				</div>
			</div>
		</div>
	</section><!-- .page-404 -->

<?php
get_footer( '404' );
