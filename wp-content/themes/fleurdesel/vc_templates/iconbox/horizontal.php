<?php
/**
 * Template for iconbox vertical
 *
 * @package Fleurdesel
 */

?>
<div class="fleurdesel-iconbox <?php echo esc_attr( $el_class ); ?>">
	<div class="fleurdesel-iconbox__wrap">
		<?php if ( $icon ) : ?>
			<div class="fleurdesel-iconbox__media fz-40 text-color-1" <?php echo $color_attr; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
				<?php echo wp_kses_post( $icon ); ?>
			</div>
			<!-- /.fleurdesel-iconbox -->
		<?php endif; ?>

		<?php if ( $atts['title'] ) : ?>
			<h2 class="fleurdesel-iconbox__title font-2 font-400 fz-14" <?php echo $color_attr; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
				<?php if ( ! empty( $link['url'] ) ) : ?>
					<a href="<?php echo esc_url( $link['url'] ); ?>"<?php if ( $link['target'] ) : ?> target="<?php echo esc_attr( $link['target'] ); ?>"<?php endif; ?><?php if ( $link['rel'] ) : ?> rel="<?php echo esc_attr( $link['rel'] ); ?>"<?php endif; ?>><?php echo esc_html( $atts['title'] ); ?></a>
				<?php else : ?>
					<?php echo esc_html( $atts['title'] ); ?>
				<?php endif; ?>
			</h2>
		<?php endif; ?>
		<!-- /.fleurdesel-iconbox__info -->
	</div>
</div>
