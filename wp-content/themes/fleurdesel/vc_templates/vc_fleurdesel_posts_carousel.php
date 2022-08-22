<?php
/**
 * Template for shortcode fleurdesel_posts_carousel
 *
 * @package Fleurdesel
 */

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );

$posts_query = $atts['loop'];

list( $args, $fleurdesel_query ) = vc_build_loop_query( $posts_query, false );

$atts['_slick_breakpoints'] = isset( $atts['_slick_breakpoints'] ) ? $atts['_slick_breakpoints'] : 'lg:5|md:5|sm:2|xs:1';
$slick_atts = Extras_Composer_Utils::parse_slick_atts( $atts );

// Build element classes.
$el_class = $this->getExtraClass( $atts['el_class'] );
$el_class .= vc_shortcode_custom_css_class( $atts['css'], ' ' );
$GLOBALS['blog_layout'] = 'list';
$GLOBALS['display_blog_content'] = true;
$GLOBALS['thumbnail_size'] = 'fleurdesel-medium';
$GLOBALS['display_blog_content'] = true;
?>
<div class="fleurdesel-slick-modern">
	<div class="row fleurdesel-post-carousel <?php echo esc_attr( $el_class ); ?>" <?php echo $this->build_attributes( $slick_atts ); // phpcs:ignore WordPress.Security.EscapeOutput ?>>
		<?php while ( $fleurdesel_query->have_posts() ) : $fleurdesel_query->the_post(); ?>

			<div class="col-md-6 col-lg-4">
				<div class="fleurdesel-posts-carousel__item">
					<?php

					/*
					 * Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'template-parts/content', get_post_format() ); ?>

				</div>
			</div>

			<?php

			endwhile;
		?>

		<?php wp_reset_postdata(); ?>
	</div>
</div>
