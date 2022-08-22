<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Fleurdesel
 */

$GLOBALS['thumbnail_size'] = 'full';
$start_date = Fleurdesel_Event_Functions::get_event_time( 'start', 'd' );
$start_month = Fleurdesel_Event_Functions::get_event_time( 'start', 'F' );
$start_year = Fleurdesel_Event_Functions::get_event_time( 'start', 'Y' );

$start_date_str = Fleurdesel_Event_Functions::get_event_time( 'start', get_option( 'time_format', false ) . ' ' . get_option( 'date_format', false ) );

$end_date = Fleurdesel_Event_Functions::get_event_time( 'end', get_option( 'time_format', false ) . ' ' .get_option( 'date_format', false ) );

$price = Fleurdesel_Event_Functions::get_price();
$location = Fleurdesel_Event_Functions::get_more_info( 'information_location' );
$map = Fleurdesel_Event_Functions::get_more_info( 'information_map' );
$phone = Fleurdesel_Event_Functions::get_more_info( 'organizer_phone' );
$email = Fleurdesel_Event_Functions::get_more_info( 'organizer_email' );
$address = Fleurdesel_Event_Functions::get_more_info( 'organizer_address' );
$socials = Fleurdesel_Event_Functions::get_more_info( 'group_social' );
$socials = $socials[0];
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'fleurdesel-event fleurdesel-event--detail' ); ?>>
	<?php get_template_part( 'template-parts/feature-image' ); ?>
	<div class="row">
		<div class="col-md-7">
			<div class="post-data pt-0">
				<div class="event-time">
					<div class="event-time__date">
						<?php echo esc_html( $start_date ); ?>
					</div>
					<div class="event-time__month">
						<span><?php echo esc_html( $start_month ); ?></span>
						<span><?php echo esc_html( $start_year ); ?></span>
					</div>
				</div>

				<?php the_title( '<h1 class="post-title">', '</h1>' ); ?>

				<div class="entry-excerpt">
					<?php the_excerpt(); ?>
				</div>

				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			</div>
		</div>

		<div class="col-md-5">

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
				<div class="mb-20">
					<p>
						<a href="<?php echo esc_url( $map ); ?>" class="text-color-1" target="blank">
							<i class="fa fa-map-o"></i> <span><?php esc_html_e( 'Google Map', 'fleurdesel' ); ?></span>
						</a>
					</p>
				</div>
				<?php endif; ?>

				<?php if ( isset( $price['value'] ) && $price['value'] ) : ?>
				<div class="mb-20">
					<p class="event-title font-700 fz-14 text-uppercase">
						<span><?php esc_html_e( 'PRICE:', 'fleurdesel' ) ?></span>
					</p>
					<p>
						<span class="event-price__value"><?php echo esc_html( $price['value'] ) ?></span>
						<?php esc_html_e( '/', 'fleurdesel' ) ?>
						<span class="event-price__unit"><?php echo esc_html( $price['unit'] ) ?></span>
					</p>
				</div>
				<?php endif; ?>
			</div>

			<div class="event-info">
				<?php if ( $phone ) : ?>
				<div class="event-info__item mb-20">
					<p class="event-title font-700 fz-14 text-uppercase">
						<i class="fa fa-phone"></i>
						<span><?php esc_html_e( 'PHONE', 'fleurdesel' ) ?></span>
					</p>
					<p><?php echo esc_html( $phone ) ?></p>
				</div>
				<?php endif; ?>

				<?php if ( $email ) : ?>
				<div class="event-info__item mb-20">
					<p class="event-title font-700 fz-14 text-uppercase">
						<i class="fa fa-envelope"></i>
						<span><?php esc_html_e( 'EMAIL', 'fleurdesel' ) ?></span>
					</p>
					<p>
						<a href="mailto:<?php echo esc_attr( $email ) ?>" target="blank"><?php echo esc_html( $email ) ?></a>
					</p>
				</div>
				<?php endif; ?>

				<?php if ( $address ) : ?>
				<div class="event-info__item mb-20">
					<p class="event-title font-700 fz-14 text-uppercase">
						<i class="fa fa-location-arrow"></i>
						<span><?php esc_html_e( 'ADDRESS', 'fleurdesel' ) ?></span>
					</p>
					<p><?php echo esc_html( $address ) ?></p>
				</div>
				<?php endif; ?>

				<?php if ( isset( $socials ) && $socials ) : ?>
				<div class="event-info__item mb-20">
					<p class="event-title font-700 fz-14 text-uppercase">
						<i class="fa fa-share-alt"></i>
						<span><?php esc_html_e( 'SHARE', 'fleurdesel' ) ?></span>
					</p>
					<ul>
						<?php if ( isset( $socials['social_facebook'] ) && $socials['social_facebook'] ) : ?>
						<li>
							<a href="<?php echo esc_url( $socials['social_facebook'] ) ?>" title="Facebook">
								<i class="fa fa-facebook"></i>
							</a>
						</li>
						<?php endif; ?>

						<?php if ( isset( $socials['social_twitter'] ) && $socials['social_twitter'] ) : ?>
						<li>
							<a href="<?php echo esc_url( $socials['social_twitter'] ) ?>" title="Twitter">
								<i class="fa fa-twitter"></i>
							</a>
						</li>
						<?php endif; ?>

						<?php if ( isset( $socials['social_instagram'] ) && $socials['social_instagram'] ) : ?>
						<li>
							<a href="<?php echo esc_url( $socials['social_instagram'] ) ?>" title="Instagram">
								<i class="fa fa-instagram"></i>
							</a>
						</li>
						<?php endif; ?>

						<?php if ( isset( $socials['social_pinterest'] ) && $socials['social_pinterest'] ) : ?>
						<li>
							<a href="<?php echo esc_url( $socials['social_pinterest'] ) ?>" title="Pinterest">
								<i class="fa fa-pinterest-p"></i>
							</a>
						</li>
						<?php endif; ?>

						<?php if ( isset( $socials['social_gplus'] ) && $socials['social_gplus'] ) : ?>
						<li>
							<a href="<?php echo esc_url( $socials['social_gplus'] ) ?>" title="Google Plus">
								<i class="fa fa-google-plus"></i>
							</a>
						</li>
						<?php endif; ?>
					</ul>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

</article><!-- #post-## -->

<?php get_template_part( 'template-parts/event-layout/content', 'related' ); ?>
