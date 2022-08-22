<?php
/**
 * Template for shortcode Fleurdesel Rooms
 *
 * @package Fleurdesel
 */

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );

$query = new WP_Query( apply_filters( 'fleurdesel_room_sc_query_args', array(
	'post_type'           => 'room_type',
	'post_status'         => 'publish',
	'ignore_sticky_posts' => 1,
	'orderby'             => $atts['orderby'],
	'order'               => $atts['order'],
	'posts_per_page'      => $atts['per_page'],
), $atts ) );

if ( ! $query->have_posts() ) {
	return;
}

// Build element classes.
$el_class  = $this->getExtraClass( $atts['el_class'] );
$el_class .= vc_shortcode_custom_css_class( $atts['css'], ' ' );
$GLOBALS['thumbnail_size'] = 'full';
$GLOBALS['room_content_alignment'] = 'center';
?>

<div class="fleurdesel-room-modern-carousel clearfix" data-init="slick-modern">

	<?php while ( $query->have_posts() ) : $query->the_post(); ?>

		<?php abrs_get_template_part( 'template-parts/archive/content', 'overlay_with_content' ); ?>

	<?php endwhile; ?>

	<?php wp_reset_postdata(); ?>
</div>

