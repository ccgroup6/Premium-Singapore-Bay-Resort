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

$start_date_str = Fleurdesel_Event_Functions::get_event_time( 'start', get_option( 'time_format', false ) . ' ' . get_option( 'date_format', false ) );

$end_date = Fleurdesel_Event_Functions::get_event_time( 'end', get_option( 'time_format', false ) . ' ' .get_option( 'date_format', false ) );
$location = Fleurdesel_Event_Functions::get_more_info( 'information_location' );
$map = Fleurdesel_Event_Functions::get_more_info( 'information_map' );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'fleurdesel-event fleurdesel-event--zigzag' ); ?>>
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
		<div class="event-price mb-15">
			<span class="event-price__value"><?php echo esc_html( $price['value'] ) ?></span>
			<?php esc_html_e( '/', 'fleurdesel' ) ?>
			<span class="event-price__unit"><?php echo esc_html( $price['unit'] ) ?></span>
		</div>
		<?php endif; ?>

		<div class="event-info">
			<?php if ( $location ) : ?>
			<div class="mb-20">
				<p class="event-title font-700 fz-14 text-uppercase">
					<i class="fa fa-map-marker"></i>
					<span><?php esc_html_e( 'LOCATION', 'fleurdesel' ) ?></span>
				</p>
				<p><?php echo esc_html( $location ) ?></p>
			</div>
			<?php endif; ?>

			<div class="mb-20">
				<p class="event-title font-700 fz-14 text-uppercase">
					<i class="fa fa-map-marker"></i>
					<span><?php esc_html_e( 'TIME', 'fleurdesel' ) ?></span>
				</p>
				<p><?php esc_html_e( 'Start: ', 'fleurdesel' ); ?> <?php echo esc_html( $start_date_str ) ?></p>
				<?php if ( $end_date ) : ?>
				<p><?php esc_html_e( 'End: ', 'fleurdesel' ); ?> <?php echo esc_html( $end_date ) ?></p>
				<?php endif; ?>
			</div>

			<?php if ( $map ) : ?>
				<a href="<?php echo esc_url( $map ); ?>">
					<i class="fa fa-map-o"></i> <span><?php esc_html_e( 'Google Map', 'fleurdesel' ); ?></span>
				</a>
			<?php endif; ?>
		</div>
	</div>
</article>
