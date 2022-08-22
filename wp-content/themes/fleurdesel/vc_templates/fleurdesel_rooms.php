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
$el_class .= ' room-layout-' . $atts['layout'];
if ( false !== strpos( $atts['layout'], 'grid' ) ) {
	$el_class .= ' row';
	$GLOBALS['room_column'] = 12 / $atts['columns'];
}
$GLOBALS['room_content_alignment'] = $atts['room_content_alignment'];
?>
<div class="fl-rooms <?php echo esc_attr( $el_class ); ?>">
	<?php if ( false !== strpos( $atts['layout'], 'overlay' ) ) : ?>
		<div class="clearfix" data-cols="<?php echo intval( 12 / $atts['columns'] ); ?>">
	<?php endif; ?>

	<?php while ( $query->have_posts() ) : $query->the_post(); ?>

		<?php abrs_get_template_part( 'template-parts/archive/content', $atts['layout'] ); ?>

	<?php endwhile; ?>

	<?php if ( false !== strpos( $atts['layout'], 'overlay' ) ) : ?>
		</div>
	<?php endif;
	wp_reset_postdata(); ?>
</div>
