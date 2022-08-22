<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Fleurdesel
 */

if ( ! function_exists( 'fleurdesel_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function fleurdesel_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			get_the_date( DATE_W3C ),
			get_the_date(),
			get_the_modified_date( DATE_W3C ),
			get_the_modified_date()
		);

		printf(
			/* translators: %s: post date */
			'<div class="post-date">%s</div>',
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>' // phpcs:ignore WordPress.Security.EscapeOutput
		);
	}
endif;

if ( ! function_exists( 'fleurdesel_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author and categories.
	 */
	function fleurdesel_posted_by() {
		printf(
			/* translators: %1$s: post author, %2$s: post categories list */
			esc_html_x( 'Posted by %1$s in %2$s', 'post author and categories', 'fleurdesel' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>',
			wp_kses_post( get_the_category_list( ', ' ) )
		);
	}
endif;

if ( ! function_exists( 'fleurdesel_the_content' ) ) {
	/**
	 * Display blog list post content.
	 */
	function fleurdesel_the_content() {
		if ( is_single() || ( 'content' === fleurdesel_option( 'blog_summary' ) ) ) {
			the_content( sprintf(
				/* translators: %s: Name of current post. */
				wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'fleurdesel' ), array(
					'span' => array(
						'class' => array(),
					),
				) ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			) );

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'fleurdesel' ),
				'after'  => '</div>',
			) );
		} elseif ( 'excerpt' === fleurdesel_option( 'blog_summary' ) || isset( $GLOBALS['display_blog_content'] ) ) {
			fleurdesel_the_excerpt();
		}
	}
}

if ( ! function_exists( 'fleurdesel_the_excerpt' ) ) {
	/**
	 * Display view more.
	 */
	function fleurdesel_the_excerpt() {

		the_excerpt(); ?>
		<a href="<?php echo esc_url( get_permalink() ) ?>" class="btn-view fz-12 font-600 text-gray-8989 text-uppercase">
			<?php esc_html_e( 'View More', 'fleurdesel' ) ?>
		</a>
		<?php
	}
}

/**
 * Fleurdesel Page title.
 *
 * @param  string  $before before.
 * @param  string  $after after.
 * @param  boolean $echo title.
 */
function fleurdesel_page_title( $before, $after, $echo = true ) {
	$page_title = '';

	// Check WooCommerce support.
	if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
		$page_title = woocommerce_page_title( false );
	} else {
		if ( is_home() || is_single() ) {
			$page_title = '';
		} elseif ( is_search() ) {
			$page_title = esc_html__( 'Search', 'fleurdesel' );
		} elseif ( is_page() ) {
			$page_title = get_the_title();
		} elseif ( ! is_singular() ) {
			$page_title = get_the_archive_title();
		}

		if ( is_singular( 'fl_event' ) || is_post_type_archive( 'fl_event' ) ) {
			$page_title = esc_html__( 'Our Events', 'fleurdesel' );
		}
	}

	if ( $page_title ) {
		$page_title = apply_filters( 'fleurdesel_page_title', $page_title );
		$page_title = wp_kses_post( $before . $page_title . $after );
	}

	if ( ! $echo ) {
		return $page_title;
	}

	echo $page_title; // phpcs:ignore WordPress.Security.EscapeOutput
}

/**
 * Output a comment in the HTML5 format.
 *
 * @param object $comment Comment to display.
 * @param array  $args    An array of arguments.
 * @param int    $depth   Depth of comment.
 */
function fleurdesel_html5_comment( $comment, $args, $depth ) {
	$tag = ( 'div' === $args['style'] ) ? 'div' : 'li'; ?>

	<<?php echo esc_html( $tag ); ?> id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>

	<article id="div-comment-<?php comment_ID(); ?>" class="comment__body">
		<?php if ( 0 != $args['avatar_size'] ) : ?>
			<div class="comment__avatar">
				<a href="<?php echo esc_url( get_comment_author_url() ); ?>" title="<?php echo esc_attr( get_comment_author() ); ?>">
					<?php echo get_avatar( $comment, $args['avatar_size'] ); ?>
				</a>
			</div><!-- /.comment__avatar -->
		<?php endif ?>

		<div class="comment__container">
			<div class="comment-metadata">
				<h4 class="comment-author">
					<?php echo get_comment_author_link(); ?>
				</h4><!-- /.comment__author -->

				<span class="comment__meta">
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID, $args ) ); ?>">
						<time datetime="<?php comment_time( 'c' ); ?>">
							<?php printf( esc_html__( '%1$s at %2$s', 'fleurdesel' ), get_comment_date( '', $comment ), get_comment_time() ); ?>
						</time>
					</a>
				</span>

				<?php if ( '0' == $comment->comment_approved ) : ?>
					<p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'fleurdesel' ); ?></p>
				<?php endif; ?>
			</div><!-- /.comment-metadata -->

			<div class="comment-content entry-content">
				<?php comment_text(); ?>
			</div><!-- /.comment__content -->

			<div class="comment__action">
				<?php comment_reply_link( array_merge( $args, array(
					'depth'     => $depth,
					'max_depth' => $args['max_depth'],
					'add_below' => 'div-comment',
				) ) ); ?>

				<?php edit_comment_link( esc_html__( 'Edit', 'fleurdesel' ) ); ?>
			</div><!-- /.comment__action -->
		</div><!-- /.comment__container -->

	</article><!-- /.comment_body --><?php
	// Note: No close tag is here.
}

/**
 * Before custom column.
 *
 * @param  string $option column: event_column.
 * @param  int    $custom  column: column number.
 */
function fleurdesel_before_custom_column( $option = 'event_column', $custom = null ) {
	if ( ! $column_value = fleurdesel_option( $option ) ) {
		return;
	}

	if ( $custom ) {
		$column_value = (int) $custom;
	}

	$column_layouts = array(
		1 => '<div class="col-md-12">',
		2 => '<div class="col-md-6">',
		3 => '<div class="col-md-6 col-lg-4">',
		4 => '<div class="col-md-6 col-lg-3">',
	);

	$output = isset( $column_layouts[ $column_value ] )
		? $column_layouts[ $column_value ]
		: '<div class="col-md-6">';

	echo $output; // phpcs:ignore WordPress.Security.EscapeOutput
}

/**
 * After custom column.
 *
 * @param  string $option column: blog_layout | video_layout | room_column.
 */
function fleurdesel_after_custom_column( $option = 'event_column' ) {
	if ( ! fleurdesel_option( $option ) ) {
		return;
	}

	echo '</div>';
}

/**
 * Print atts for footer.
 */
function fleurdesel_footer_atts() {
	$atts           = '';
	$footer_bg_type = fleurdesel_option( 'footer_bg_type' );
	if ( ( 'color' === $footer_bg_type ) && fleurdesel_option( 'footer_bg_color' ) ) {
		$atts = 'background-color: ' . esc_attr( fleurdesel_option( 'footer_bg_color' ) ) . ';';
	}

	if ( ( 'image' === $footer_bg_type ) && $image = fleurdesel_option( 'footer_bg_image' ) ) {
		if ( is_int( $image ) ) {
			$image = wp_get_attachment_image_url( $image, 'full' );
		}

		$atts .= 'background-image: url(' . esc_url( $image ) . ');';
	}

	if ( $atts ) {
		print 'style="' . $atts . '"'; // WPCS xss: ok.
	}
}

/**
 * Handle page title.
 */
function fleurdesel_get_page_title_values() {

	// Build page title data.
	$page_title = array(
		'page_title_bg_color' => fleurdesel_option( 'page_title_bg_color' ),
		'page_title_bg_image' => fleurdesel_option( 'page_title_bg_image' ),
		'page_title_show'     => fleurdesel_option( 'page_title_show' ),
	);

	$page_title = apply_filters( 'fleurdesel_get_page_title_theme_settings', $page_title );

	// We use woocommerce page-title setting if in woocommerce.
	if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
		$shop_id = wc_get_page_id( 'shop' );

		foreach ( $page_title as $key => $value ) {
			$shop_metadata['is_custom_page_title'] = get_post_meta( $shop_id, 'is_custom_page_title', true );
			$shop_metadata[ $key ] = get_post_meta( $shop_id, $key, true );
		}

		if ( is_array( $shop_metadata ) && $shop_metadata['is_custom_page_title'] ) {
			$page_title = fleurdesel_parse_custom_pagetitle( $shop_metadata );
		}

		unset( $shop_id, $shop_metadata );
	}

	if ( is_tax() || is_archive() ) {
		$_term = get_queried_object();

		if ( $_term && isset( $_term->term_id ) ) {

			foreach ( $page_title as $key => $value ) {
				$_metadata['is_custom_page_title'] = get_post_meta( $_term->term_id, 'is_custom_page_title', true );
				$_metadata[ $key ] = get_post_meta( $_term->term_id, $key, true );
			}
		}
	} elseif ( is_single() || is_page() ) {

		foreach ( $page_title as $key => $value ) {
			$_metadata['is_custom_page_title'] = get_post_meta( get_the_ID(), 'is_custom_page_title', true );
			$_metadata[ $key ] = get_post_meta( get_the_ID(), $key, true );
		}
	}

	// Build $_metadata.
	if ( isset( $_metadata ) && is_array( $_metadata ) && $_metadata['is_custom_page_title'] ) {
		$page_title = fleurdesel_parse_custom_pagetitle( $_metadata );
	}

	return $page_title;
}

/**
 * Get page title attributes.
 *
 * @param  array $page_title Page title values.
 * @return string
 */
function fleurdesel_get_page_title_atts( $page_title = array() ) {
	if ( ! $page_title ) {
		$page_title = apply_filters( 'fleurdesel_get_page_title_values', fleurdesel_get_page_title_values() );
	}

	if ( ! $page_title['page_title_show'] ) {
		return '';
	}

	// Build class and atts.
	$el_atts  = '';

	// Build el-atts with image.
	if ( $page_title['page_title_bg_color'] ) {
		$el_atts = 'background-color: ' . esc_attr( $page_title['page_title_bg_color'] ) . ';';
	}

	if ( $page_title['page_title_bg_image'] ) {
		$image = $page_title['page_title_bg_image'];
		$el_atts = 'background-image: url(' . esc_url( $image ) . ');';
	}

	$style = '';

	if ( $el_atts ) {
		$style = 'style="' . $el_atts . '"'; // WPCS xss: ok.
	}

	return $style;
}

/**
 * Get page title attributes for event.
 *
 * @param  [array] $page_title [page_title].
 * @return [array]             [page_title]
 */
function fleurdesel_get_event_page_title_atts( $page_title ) {
	if ( is_singular( 'fl_event' ) || is_post_type_archive( 'fl_event' ) ) {
		$page_title['page_title_bg_color'] = fleurdesel_option( 'event_page_title_bg_color' );
		$page_title['page_title_bg_image'] = fleurdesel_option( 'event_page_title_bg_image' );
	}

	return $page_title;
}
add_filter( 'fleurdesel_get_page_title_theme_settings', 'fleurdesel_get_event_page_title_atts', 10, 1 );
