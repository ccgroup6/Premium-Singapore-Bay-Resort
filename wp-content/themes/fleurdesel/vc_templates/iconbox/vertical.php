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
			<div class="fleurdesel-iconbox__media h1 text-color-1 mb-15" <?php echo $color_attr; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
				<?php echo wp_kses_post( $icon ); ?>
			</div>
			<!-- /.fleurdesel-iconbox -->
		<?php endif; ?>

		<div class="fleurdesel-iconbox__data">
			<?php if ( $atts['title'] ) : ?>
				<h2 class="fleurdesel-iconbox__title h6 font-2 font-500 mb-20" <?php echo $color_attr; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
					<?php if ( ! empty( $link['url'] ) ) : ?>
						<a href="<?php echo esc_url( $link['url'] ); ?>"<?php if ( $link['target'] ) : ?> target="<?php echo esc_attr( $link['target'] ); ?>"<?php endif; ?><?php if ( $link['rel'] ) : ?> rel="<?php echo esc_attr( $link['rel'] ); ?>"<?php endif; ?>><?php echo esc_html( $atts['title'] ); ?></a>
					<?php else : ?>
						<?php echo esc_html( $atts['title'] ); ?>
					<?php endif; ?>
				</h2>
			<?php endif; ?>

			<?php if ( $atts['desc'] ) : ?>
				<p class="fleurdesel-iconbox__desc" <?php echo $color_attr; // phpcs:ignore WordPress.Security.EscapeOutput ?>><?php echo wp_kses_post( nl2br( $atts['desc'] ) ); ?></p>
			<?php endif; ?>
		</div>
		<!-- /.fleurdesel-iconbox__info -->
	</div>
</div>
