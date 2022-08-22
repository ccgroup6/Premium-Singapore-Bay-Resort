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

$atts['_slick_breakpoints'] = isset( $atts['_slick_breakpoints'] ) ? $atts['_slick_breakpoints'] : 'lg:5|md:5|sm:2|xs:1';
$slick_atts = Extras_Composer_Utils::parse_slick_atts( $atts );
$GLOBALS['room_content_alignment'] = $atts['alignment'];

if ( false !== strpos( $atts['layout'], 'grid' ) ) {
	$GLOBALS['room_column'] = 12;
}
?>
<div class="fl-rooms <?php echo esc_attr( $el_class ); ?>" <?php echo $this->build_attributes( $slick_atts ); // phpcs:ignore WordPress.Security.EscapeOutput ?>>

	<?php while ( $query->have_posts() ) : $query->the_post(); ?>

		<?php abrs_get_template_part( 'template-parts/archive/content', $atts['layout'] ); ?>

	<?php endwhile; ?>

	<?php wp_reset_postdata(); ?>
</div>
