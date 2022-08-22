<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Fleurdesel
 */

$GLOBALS['thumbnail_size'] = 'fleurdesel-medium';
$start_date = Fleurdesel_Event_Functions::get_event_time( 'start', 'd' );
$start_month = Fleurdesel_Event_Functions::get_event_time( 'start', 'F' );
$start_year = Fleurdesel_Event_Functions::get_event_time( 'start', 'Y' );
$price = Fleurdesel_Event_Functions::get_price();
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'fleurdesel-event fleurdesel-event--grid' ); ?>>
	<?php get_template_part( 'template-parts/feature-image' ); ?>

	<div class="post-data">
		<div class="event-time">
			<div class="event-time__date">
				<?php echo esc_html( $start_date ); ?>
			</div>
			<div class="event-time__month">
				<span><?php echo esc_html( $start_month ); ?></span>
				<span><?php echo esc_html( $start_year ); ?></span>
			</div>
		</div>

		<?php the_title( '<h2 class="post-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>

		<?php if ( isset( $price['value'] ) && $price['value'] ) : ?>
		<div class="event-price">
			<span class="event-price__value"><?php echo esc_html( $price['value'] ) ?></span>
			<?php esc_html_e( '/', 'fleurdesel' ) ?>
			<span class="event-price__unit"><?php echo esc_html( $price['unit'] ) ?></span>
		</div>
		<?php endif; ?>
	</div>
</article><!-- #post-## -->
