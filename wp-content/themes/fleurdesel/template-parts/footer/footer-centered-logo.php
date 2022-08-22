<?php
/**
 * Template for Centered Footer
 *
 * @package Fleur de sel
 */

$fcolumn_transforms_class = array(
	1 => 'col-md-6 offset-md-3',
	2 => 'col-md-6',
	3 => 'col-md-4',
);
?>
<footer id="colophon" class="centered-footer" <?php fleurdesel_footer_atts(); ?> role="contentinfo">
	<div class="container">

		<?php fleurdesel_site_logo( 'footer_centered_logo', true, true, '<div class="centered-footer__logo pt-60 pb-60">', '</div>' ); ?>

		<?php if ( fleurdesel_option( 'show_footer_centered_logo_columns' ) ) : ?>
			<div class="centered-footer__columns">
				<?php if ( $fcolumns = (int) fleurdesel_option( 'centered_logo_footer_column' ) ) : ?>
				<div class="row">
					<?php for ( $i = 1; $i <= $fcolumns; $i++ ) :
						$sidebar_name = fleurdesel_option( 'centered_logo_footer_sidebar_' . $i );
						if ( is_active_sidebar( $sidebar_name ) ) : ?>

						<div class="<?php echo esc_attr( $fcolumn_transforms_class[ $fcolumns ] ); ?>">
							<?php dynamic_sidebar( $sidebar_name ); ?>
						</div>

					<?php endif;
					endfor; ?>
				</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="centered-footer__info">
			<?php
			if ( fleurdesel_option( 'show_footer_social' ) ) {
				fleurdesel_social_follow();
			} ?>

			<?php if ( fleurdesel_option( 'copyright' ) ) : ?>
				<div class="centered-footer__copyright">
					<?php echo wp_kses_post( fleurdesel_option( 'copyright' ) ); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</footer><!-- #colophon -->
