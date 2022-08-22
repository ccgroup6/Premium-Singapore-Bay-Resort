<?php
/**
 * Template for shortcode Fleurdesel Testimonials carousel: cemtered with small content.
 *
 * @package Fleurdesel
 */

?>
<?php foreach ( $testimonials as $testimonial ) : ?>
<div class="fleurdesel-testimonial__item fleurdesel-testimonial__item--small-content text-center">
	<div class="fleurdesel-testimonial__wrap">
		<p class="fleurdesel-testimonial__icon font-1 text-color-1"></p>

		<?php if ( $testimonial['content'] ) : ?>
		<div class="row">
			<div class="offset-lg-2 col-lg-8">
				<p class="fleurdesel-testimonial__desc"><?php echo esc_html( $testimonial['content'] ); ?></p>
			</div>
		</div>
		<?php endif; ?>

		<?php if ( $testimonial['avatar'] ) : ?>
		<p class="fleurdesel-testimonial__avatar inline-block">
			<?php echo wp_get_attachment_image( $testimonial['avatar'], 'full' ); ?>
		</p>
		<?php endif; ?>

		<?php if ( $testimonial['name'] ) : ?>
		<p class="fleurdesel-testimonial__name font-700"><?php echo esc_html( $testimonial['name'] ) ?></p>
		<?php endif; ?>

		<?php if ( $testimonial['from'] ) : ?>
		<p class="fleurdesel-testimonial__contry fz-14"><?php esc_html_e( 'From', 'fleurdesel' ) ?> <?php echo esc_html( $testimonial['from'] ); ?></p>
		<?php endif; ?>
	</div>
</div>
<?php endforeach; ?>
