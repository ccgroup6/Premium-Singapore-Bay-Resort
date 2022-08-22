<?php
/**
 * Flexible Posts Widget: Fleurdesel widget template
 *
 * @package Fleurdesel
 */

// Block direct requests.
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

echo wp_kses_post( $before_widget );

if ( ! empty( $title ) ) {
	echo wp_kses_post( $before_title . $title . $after_title );
}

if ( $flexible_posts->have_posts() ) :
?>
	<ul class="small-posts">
		<?php
		while ( $flexible_posts->have_posts() ) : $flexible_posts->the_post();
			global $post; ?>
			<li <?php post_class(); ?>>
				<?php
				if ( true == $thumbnail ) {
					// If the post has a feature image, show it.
					if ( has_post_thumbnail() ) {
						echo '<div class="post-image"><a href="' . esc_url( get_permalink() ) . '">';
						the_post_thumbnail( $thumbsize );
						echo '</a></div>';
						// Else if the post has a mime type that starts with "image/" then show the image directly.
					} elseif ( 'image/' == substr( $post->post_mime_type, 0, 6 ) ) {
						echo '<div class="post-image"><a href="' . esc_url( get_permalink() ) . '">';
						echo wp_get_attachment_image( $post->ID, $thumbsize );
						echo '</a></div>';
					}
				}

				the_title( '<div class="post-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></div>' );

				fleurdesel_posted_on();
				?>
			</li>
		<?php endwhile; ?>
	</ul><!-- .dpe-flexible-posts -->
<?php
endif; // End if().

echo wp_kses_post( $after_widget );
