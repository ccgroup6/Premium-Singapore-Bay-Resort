<?php
/**
 * Template for Classic Footer
 *
 * @package Fleur de sel
 */

$fcolumn_transforms_class = array(
	1 => 'col-md-6 offset-md-3',
	2 => 'col-md-6',
	3 => 'col-md-4',
	4 => 'col-md-3',
);
?>
<footer id="colophon" class="classic-footer clearfix" <?php fleurdesel_footer_atts(); ?> role="contentinfo">
	<div class="container">
		<?php if ( fleurdesel_option( 'show_footer_classic_columns' ) ) : ?>
		<?php if ( $fcolumns = (int) fleurdesel_option( 'classic_footer_column' ) ) : ?>
		<div class="classic-footer__columns">
			<div class="row">
				<?php for ( $i = 1; $i <= $fcolumns; $i++ ) :
					$sidebar_name = fleurdesel_option( 'classic_footer_sidebar_' . $i );
					if ( is_active_sidebar( $sidebar_name ) ) : ?>

					<div class="<?php echo esc_attr( $fcolumn_transforms_class[ $fcolumns ] ); ?>">
						<?php dynamic_sidebar( $sidebar_name ); ?>
					</div>

					<?php endif;
				endfor; ?>
			</div>
		</div>
		<?php endif; ?>
		<?php endif; ?>
		<div class="footer-info">
			<div class="footer-info__left">
				<p><?php echo wp_kses_post( fleurdesel_option( 'copyright' ) ); ?></p>
			</div>
			<div class="footer-info__right">

				<?php
				if ( fleurdesel_option( 'right_text' ) ) {
					echo wp_kses_post( fleurdesel_option( 'right_text' ) );
				} ?>

			</div>
		</div>
	</div>
</footer><!-- #colophon -->
