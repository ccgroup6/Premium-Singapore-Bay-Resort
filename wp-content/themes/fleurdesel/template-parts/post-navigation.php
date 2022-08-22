<?php
/**
 * Template part for post-navigation for single post.
 *
 * @package Fleurdesel
 */

$prev_post = get_previous_post();
$next_post = get_next_post();

if ( ! $prev_post && ! $next_post ) {
	return;
}

$posts = array();
$prev_post_id = $next_post_id = 0;

if ( $prev_post ) {
	$posts[] = $prev_post;
	$prev_post_id = $prev_post->ID;
}

if ( $next_post ) {
	$posts[] = $next_post;
	$next_post_id = $next_post->ID;
}

if ( empty( $posts ) ) {
	return;
}

$el_class  = 'posts-mini mb-125';
$el_class .= count( $posts ) === 1 ? ' first-or-last text-center' : '';
?>

<div class="<?php echo esc_attr( $el_class ); ?>">
	<div class="row">
		<?php foreach ( $posts as $post ) : setup_postdata( $post ); ?>
			<?php
			$post_class = '';

			if ( get_the_ID() === $prev_post_id ) {
				$text_align = 'text-right';
				$post_class = 'prev-post';
				$nav = '<div class="icon-nav"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark"><i class="fa fa-chevron-left"></i></a></div>';
			} elseif ( get_the_ID() === $next_post_id ) {
				$text_align = 'text-left';
				$post_class = 'next-post';
				$nav = '<div class="icon-nav"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark"><i class="fa fa-chevron-right"></i></a></div>';
			}
			?>
			<article <?php post_class( $post_class ); ?>>

				<div class="entry-container">
					<?php echo $nav; // phpcs:ignore WordPress.Security.EscapeOutput ?>

					<?php fleurdesel_posted_on(); ?>

					<?php the_title( '<h2 class="post-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>

				</div><!-- entry-container -->
			</article>
		<?php endforeach; ?>
	</div>
</div>

<?php wp_reset_postdata(); ?>
