<?php
/**
 * Template for Left Logo Footer
 *
 * @package Fleur de sel
 */

?>
<footer id="colophon" class="left-logo-footer clearfix" <?php fleurdesel_footer_atts(); ?> role="contentinfo">
	<div class="container">
		<div class="row">
			<div class="col-md-3 col-xs-12">
				<?php fleurdesel_site_logo( 'footer_left_logo', true, true, '<div class="left-logo-footer__logo">', '</div>' ); ?>
			</div>

			<?php if ( fleurdesel_option( 'copyright' ) ) : ?>
			<div class="col-md-6 col-xs-12">
					<div class="left-logo-footer__copyright">
						<p><?php echo wp_kses_post( fleurdesel_option( 'copyright' ) ); ?></p>
					</div>
			</div>
			<?php endif; ?>

			<?php if ( fleurdesel_option( 'show_footer_social' ) ) : ?>
			<div class="col-md-3 col-xs-12">
				<div class="left-logo-footer__social">
					<?php fleurdesel_social_follow(); ?>
				</div>
			</div>
			<?php endif; ?>
		</div>

	</div>
</footer><!-- #colophon -->
