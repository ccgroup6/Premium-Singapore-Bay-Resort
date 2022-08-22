<?php
/**
 * Template for shortcode fleurdesel_posts
 *
 * @package Fleurdesel
 */

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );

$posts_query = $atts['loop'];

list( $args, $fleurdesel_query ) = vc_build_loop_query( $posts_query, false );

// Build element classes.
$el_class = 'blog-layout-' . $atts['layout'];
$el_class .= $this->getExtraClass( $atts['el_class'] );
$el_class .= vc_shortcode_custom_css_class( $atts['css'], ' ' );
if ( ( 'list' === $atts['layout'] ) || ( 'zigzag' === $atts['layout'] ) ) {
	$GLOBALS['display_blog_content'] = true;
}
$GLOBALS['blog_layout'] = $atts['layout'];

?>
<div class="fleurdesel-post-sc <?php echo esc_attr( $el_class ); ?>">
	<?php while ( $fleurdesel_query->have_posts() ) : $fleurdesel_query->the_post();

		/*
		 * Include the Post-Format-specific template for the content.
		 * If you want to override this in a child theme, then include a file
		 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
		 */
		get_template_part( 'template-parts/content', get_post_format() );

		endwhile;
	?>

	<?php wp_reset_postdata(); ?>
</div>
