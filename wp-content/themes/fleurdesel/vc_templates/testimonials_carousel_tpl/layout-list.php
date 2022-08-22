<?php
/**
 * Template for shortcode Fleurdesel Testimonials carousel: list
 *
 * @package Fleurdesel
 */

?>
<?php foreach ( $testimonials as $testimonial ) : ?>
<div class="fleurdesel-testimonial__item fleurdesel-testimonial__item--list">
	<div class="fleurdesel-testimonial__wrap">
		<div class="row">
			<div class="offset-md-1 col-12 col-md-2">
				<?php if ( $testimonial['avatar'] ) : ?>
				<div class="fleurdesel-testimonial__avatar">
					<?php echo wp_get_attachment_image( $testimonial['avatar'], 'full' ); ?>
				</div>
				<?php endif; ?>
			</div>
			<div class="col-12 col-md-8">
				<?php if ( $testimonial['content'] ) : ?>
				<p class="fleurdesel-testimonial__desc"><?php echo esc_html( $testimonial['content'] ); ?></p>
				<?php endif; ?>
				<div>
					<?php if ( $testimonial['name'] ) : ?>
					<p class="fleurdesel-testimonial__name font-700 inline-block"><?php echo esc_html( $testimonial['name'] ) ?></p>
					<?php endif; ?>

					<?php if ( $testimonial['from'] ) : ?>
					<p class="fleurdesel-testimonial__contry fz-14 inline-block"><?php esc_html_e( 'From', 'fleurdesel' ) ?> <?php echo esc_html( $testimonial['from'] ); ?></p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endforeach; ?>
