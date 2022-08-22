<?php
/**
 * Template for shortcode fleurdesel_posts
 *
 * @package Fleurdesel
 */

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );

$query_args = array(
	'post_type'           => 'fl_event',
	'post_status'         => 'publish',
	'ignore_sticky_posts' => 1,
	'orderby'             => $atts['orderby'],
	'order'               => $atts['order'],
	'posts_per_page'      => $atts['per_page'],
);

if ( $atts['filter_ids'] && ( $category = explode( ',', $atts['filter_ids'] ) ) ) {
	$query_args['tax_query'] = array(
		array(
			'taxonomy' => 'fl_event_cat',
			'field' => 'id',
			'terms' => $category,
		),
	);
}

$fl_events = new WP_Query( $query_args );

$layout = $atts['layout'];
$column = $atts['column'] ? $atts['column'] : '';
// Build element classes.
$el_class = $this->getExtraClass( $atts['el_class'] );
$el_class .= vc_shortcode_custom_css_class( $atts['css'], ' ' );
if ( 'grid' === $layout ) {
	$el_class .= ' row';
}
?>
<?php if ( $fl_events->have_posts() ) : ?>
<div class="fleurdesel-events-sc <?php echo esc_attr( $el_class ); ?>">
	<?php while ( $fl_events->have_posts() ) : $fl_events->the_post();
		if ( 'grid' === $layout ) {
			fleurdesel_before_custom_column( 'event_column', $column );
		}

		/*
		 * Include the Post-Format-specific template for the content.
		 * If you want to override this in a child theme, then include a file
		 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
		 */
		get_template_part( 'template-parts/event-layout/content', $layout );

		if ( 'grid' === $layout ) {
			fleurdesel_after_custom_column( 'event_column' );
		}

		endwhile;
	?>

	<?php wp_reset_postdata(); ?>
</div>
<?php endif; ?>
