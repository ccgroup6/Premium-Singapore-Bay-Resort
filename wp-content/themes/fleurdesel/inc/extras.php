<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Fleurdesel
 */

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function fleurdesel_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', bloginfo( 'pingback_url' ), '">';
	}
}
add_action( 'wp_head', 'fleurdesel_pingback_header' );

/**
 * Converts a HEX value to RGB.
 *
 * @param string $color The original color, in 3- or 6-digit hexadecimal form.
 * @return array Array containing RGB (red, green, and blue) values for the given
 *               HEX code, empty array otherwise.
 */
function fleurdesel_hex2rgb( $color ) {
	$color = trim( $color, '#' );

	if ( strlen( $color ) === 3 ) {
		$r = hexdec( substr( $color, 0, 1 ) . substr( $color, 0, 1 ) );
		$g = hexdec( substr( $color, 1, 1 ) . substr( $color, 1, 1 ) );
		$b = hexdec( substr( $color, 2, 1 ) . substr( $color, 2, 1 ) );
	} else if ( strlen( $color ) === 6 ) {
		$r = hexdec( substr( $color, 0, 2 ) );
		$g = hexdec( substr( $color, 2, 2 ) );
		$b = hexdec( substr( $color, 4, 2 ) );
	} else {
		return array();
	}

	return array(
		'red' => $r,
		'green' => $g,
		'blue' => $b,
	);
}

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function fleurdesel_body_classes( $classes ) {
	global $is_IE;

	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// If it's IE, add a class.
	if ( $is_IE ) {
		$classes[] = 'ie';
	}

	// Are we on mobile?
	if ( wp_is_mobile() ) {
		$classes[] = 'mobile';
	}

	return $classes;
}
add_filter( 'body_class', 'fleurdesel_body_classes' );

/**
 * Site layout classes.
 *
 * @param array $classes Classes for the layout element.
 */
function fleurdesel_layout_class( $classes ) {
	$classes = (array) $classes;
	$classes[] = 'site-layout';

	if ( class_exists( 'Fleurdesel_Sidebar' ) ) {

		if ( Fleurdesel_Sidebar::has_sidebar() ) {
			$classes[] = sprintf( 'sidebar-%s', Fleurdesel_Sidebar::get_sidebar_area() );

			if ( ! is_active_sidebar( Fleurdesel_Sidebar::get_sidebar() ) ) {
				$classes[] = 'no-active-sidebar';
			}
		} else {
			$classes[] = 'sidebar-none';
		}
	}

	$classes = apply_filters( 'fleurdesel_layout_class', $classes );

	echo 'class="' . esc_attr( join( ' ', $classes ) ) . '"';
}

/**
 * Site content classes.
 *
 * @param array $classes Classes for the layout element.
 */
function fleurdesel_content_class( $classes = '' ) {
	global $template;

	$classes = (array) $classes;

	$classes[] = 'site-container';
	$classes[] = basename( $template, '.php' );
	$classes[] = isset( $GLOBALS['container_class'] ) ? $GLOBALS['container_class'] : 'container';

	$classes = apply_filters( 'fleurdesel_content_class', $classes );

	echo 'class="' . esc_attr( join( ' ', $classes ) ) . '"';
}

/**
 * Fleurdesel get site logo
 *
 * @param  string  $logo       Logo type to get.
 * @param  boolean $with_link  Display site logo with link.
 * @param  boolean $echo       Echo output.
 * @param  string  $before     Before output logo.
 * @param  string  $after      After output logo.
 * @return string
 */
function fleurdesel_site_logo( $logo = 'site_logo', $with_link = true, $echo = true, $before = '', $after = '' ) {
	$output = '';

	$site_logo = fleurdesel_option( $logo );

	if ( $site_logo ) {
		if ( is_int( $site_logo ) ) {
			$site_logo = wp_get_attachment_image_url( $site_logo, 'full' );
		}

		$image = sprintf( '<img src="%1$s" class="site-logo-img" alt="%2$s">', $site_logo, get_bloginfo( 'name' ) );

		if ( $with_link ) {
			$image = sprintf( '<a class="site-logo site-logo--link %3$s" href="%1$s" rel="home">%2$s</a>', esc_url( home_url( '/' ) ), $image, $logo );
		} else {
			$image = sprintf( '<span class="site-logo %2$s">%1$s</span>', $image, $logo );
		}

		$output  = $before . $image . $after;
	}

	/**
	 * Apply filter to site logo hooks.
	 *
	 * @var string
	 */
	$output = apply_filters( 'fleurdesel_site_logo', $output, $logo, $with_link, $before, $after );

	if ( ! $echo ) {
		return $output;
	}

	echo $output; // phpcs:ignore WordPress.Security.EscapeOutput
}

/**
 * Small function to display site copyright.
 *
 * @param  string $before Before output copyright.
 * @param  string $after  After output copyright.
 * @param  bool   $echo   Echo or return output.
 */
function fleurdesel_site_copyright( $before = '', $after = '', $echo = true ) {
	$copyright = fleurdesel_option( 'copyright' );

	if ( ! $copyright ) {
		return;
	}

	$theme  = wp_get_theme();
	$search = array(
		'{c}',
		'{year}',
		'{sitename}',
		'{theme}',
		'{author}',
	);

	$replace = array(
		' &copy; ',
		date( 'Y' ),
		get_bloginfo( 'name' ),
		/* translators: %1$s: theme name, %2$s: them author */
		sprintf( esc_html__( '%1$s by %2$s', 'fleurdesel' ), $theme->name, $theme->display( 'Author' ) ),
		$theme->display( 'Author' ),
	);

	$output  = $before;
	$output .= str_replace( $search, $replace, $copyright );
	$output .= $after;

	/**
	 * Fire a filter for $output.
	 *
	 * @var string
	 */
	$output = apply_filters( 'fleurdesel_site_copyright', $output );

	if ( ! $echo ) {
		return $output;
	}

	print $output; // WPCS: XSS OK.
}

/**
 * Wrap `<span class="count"></span>` to posts count in archive posts list.
 *
 * @param  string $link_html Link html.
 * @param  string $url       Url.
 * @param  string $text      Text.
 * @param  string $format    Format.
 * @param  string $before    Content before.
 * @param  string $after     Content after.
 * @return string
 */
function fleurdesel_archive_link_wrap_count( $link_html, $url, $text, $format, $before, $after ) {
	if ( 'html' == $format && ! empty( $after ) ) {
		$link_html = str_replace( '</a>&nbsp;(', '&nbsp;<span class="count">(', $link_html );
		$link_html = str_replace( ')', ')</span></a>', $link_html );
	}

	return $link_html;
}
add_filter( 'get_archives_link', 'fleurdesel_archive_link_wrap_count', 10, 6 );

/**
 * Wrap `<span class="count"></span>` to posts count in category posts list.
 *
 * @param  string $link_html Link html.
 * @return string
 */
function fleurdesel_categories_link_wrap_count( $link_html ) {
	$link_html = str_replace( '</a> (', ' <span class="count">(', $link_html );
	$link_html = str_replace( ')', ')</span></a>', $link_html );

	return $link_html;
}
add_filter( 'wp_list_categories', 'fleurdesel_categories_link_wrap_count' );

/**
 * Change markup for link page.
 *
 * @param  string $link Link html.
 * @param  int    $page Page number.
 * @return string
 */
function fleurdesel_change_link_page_markup( $link, $page ) {
	global $wp_query;

	$current_page = ! empty( $wp_query->query_vars['page'] ) ? absint( $wp_query->query_vars['page'] ) : 1;

	if ( is_singular() && $page == $current_page ) {
		$link = sprintf( '<span class="current">%s</span>', $link );
	}

	return $link;
}
add_filter( 'wp_link_pages_link', 'fleurdesel_change_link_page_markup', 10, 2 );

if ( ! function_exists( 'fleurdesel_pagination' ) ) {
	/**
	 * Display pagination.
	 */
	function fleurdesel_pagination() {
		the_posts_pagination( array(
			'prev_text' => '<i class="fa fa-chevron-left"></i>',
			'next_text' => '<i class="fa fa-chevron-right"></i>',
		) );
	}
}

/**
 * Show pagination above footer.
 */
function fleurdesel_blog_pagination() {
	if ( $GLOBALS['wp_query']->max_num_pages <= 1 ) {
		return;
	}

	get_template_part( 'template-parts/pagination' );
}
add_action( 'fleurdesel_content_end', 'fleurdesel_blog_pagination' );

if ( ! function_exists( 'fleurdesel_post_media' ) ) {
	/**
	 * Display post media base on post format.
	 */
	function fleurdesel_post_media() {
		if ( is_sticky() ) {
			echo '<span class="sticky-label"><i class="fa fa-star"></i></span>';
		}
		$blog_layout = fleurdesel_option( 'blog_layout' );

		if ( isset( $GLOBALS['blog_layout'] ) ) {
			$blog_layout = $GLOBALS['blog_layout'];
		}

		if ( ( 'list' === $blog_layout ) || ( 'zigzag' === $blog_layout ) ) {
			$GLOBALS['thumbnail_size'] = 'fleurdesel-medium';
			fleurdesel_post_media_standard();
			return;
		}

		$format = get_post_format();

		switch ( $format ) {
			case 'gallery':
				fleurdesel_post_media_gallery();
				break;

			case 'audio':
				fleurdesel_post_media_audio();
				break;

			case 'video':
				fleurdesel_post_media_video();
				break;

			case 'quote':
				fleurdesel_post_media_quote();
				break;

			default:
				fleurdesel_post_media_standard();
		}
	}
}

if ( ! function_exists( 'fleurdesel_post_media_standard' ) ) {
	/**
	 * Show post featured image.
	 */
	function fleurdesel_post_media_standard() {
		get_template_part( 'template-parts/feature-image' );
	}
}

if ( ! function_exists( 'fleurdesel_post_media_quote' ) ) {
	/**
	 * Show post featured image.
	 */
	function fleurdesel_post_media_quote() {
		global $post;
		$parser = new Fleurdesel_Post_Format_Parser( get_post_field( 'post_content', $post ), 'quote' );
		$parser_data = $parser->get_data();

		if ( has_post_thumbnail() ) : ?>
			<div class="post-media" style="background-image: url(<?php the_post_thumbnail_url( 'full' ); ?>);">
		<?php else : ?>
			<div class="post-media">
		<?php endif; ?>

			<?php echo wp_kses_post( $parser_data['output'] ); ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'fleurdesel_post_media_gallery' ) ) {
	/**
	 * Show post featured image.
	 */
	function fleurdesel_post_media_gallery() {
		global $post;
		$parser = new Fleurdesel_Post_Format_Parser( get_post_field( 'post_content', $post ), 'gallery' );
		$parser_data = $parser->get_data();

		if ( ! empty( $parser_data['ids'] ) ) {
			$image_ids = $parser_data['ids'];
			?>
			<div class="post-media">
				<div data-arrows="true" data-init="slick" data-dots="false" data-autoplay="true">
					<?php foreach ( $image_ids as $image_id ) : ?>
						<div>
							<?php echo wp_get_attachment_image( $image_id, 'post-thumbnail' ); ?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php
		} else {
			fleurdesel_post_media_standard();
		}
	}
}

if ( ! function_exists( 'fleurdesel_post_media_audio' ) ) {
	/**
	 * Show post featured image.
	 */
	function fleurdesel_post_media_audio() {
		global $post;
		$parser = new Fleurdesel_Post_Format_Parser( get_post_field( 'post_content', $post ), 'audio' );
		$parser_data = $parser->get_data();

		if ( ! empty( $parser_data['output'] ) ) {
			echo '<div class="post-media">' . $parser_data['output'] . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput
		} else {
			fleurdesel_post_media_standard();
		}
	}
}

if ( ! function_exists( 'fleurdesel_post_media_video' ) ) {
	/**
	 * Show post featured image.
	 */
	function fleurdesel_post_media_video() {
		global $post;
		$parser = new Fleurdesel_Post_Format_Parser( get_post_field( 'post_content', $post ), 'video' );
		$parser_data = $parser->get_data();

		if ( ! empty( $parser_data['output'] ) ) {
			?>
			<div class="post-media">
				<div class="video-box" data-init="video-box" data-effect="popup">
					<?php if ( has_post_thumbnail() ) : ?>
						<a href="#" class="video-box__button"><i class="fa fa-play-circle"></i></a>

						<div class="video-box__overlay" style="background-image: url(<?php the_post_thumbnail_url( 'full' ); ?>);"></div>
					<?php endif; ?>

					<div class="video-box__video">
						<?php print $parser_data['output']; // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</div>
				</div>
			</div>
			<?php
		} else {
			fleurdesel_post_media_standard();
		}
	}
}

if ( ! function_exists( 'fleurdesel_content_single_footer' ) ) {
	/**
	 * Show single content footer.
	 */
	function fleurdesel_content_single_footer() {
		if ( ! has_tag() && ! fleurdesel_option( 'single_sharing' ) ) {
			return;
		}
		?>
		<div class="row mb-80">
			<div class="col-md-6">
				<?php the_tags( '<div class="post-tags"><i class="fa fa-tag"></i>', ', ', '</div>' ); ?>
			</div>

			<div class="col-md-6">
				<?php
				$share = fleurdesel_social()->get_share();

				if ( $share && empty( $GLOBALS['hidden_post_sharing'] ) && fleurdesel_option( 'single_sharing' ) ) : ?>
					<div class="post-sharing social-icons">
						<span><strong><?php esc_html_e( 'SHARE', 'fleurdesel' ); ?></strong></span>

						<span class="mr-15"><i class="fa fa-share-alt"></i></span>

						<?php foreach ( $share as $key => $value ) : ?>
							<a href="<?php echo esc_url( $value['link'] ) ?>" title="<?php echo esc_html( $value['name'] ) ?>" <?php if ( fleurdesel_option( 'social_blank' ) ) : ?>target="_blank"<?php endif; ?>>
								<i class="fa fa-<?php echo esc_html( $key ) ?>"></i>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
} // End if().

/**
 * Get all sidebars.
 *
 * @return array
 */
function fleurdesel_get_sidebars() {
	return $GLOBALS['wp_registered_sidebars'];
}

/**
 * Get sidebars for setting option.
 *
 * @return array
 */
function fleurdesel_get_sidebars_option() {
	$sidebars = fleurdesel_get_sidebars();
	$opt_sidebars = array();

	foreach ( $sidebars as $key => $sidebar ) {
		$opt_sidebars[ $key ] = $sidebar['name'];
	}

	return $opt_sidebars;
}

if ( ! function_exists( 'fleurdesel_header_layout' ) ) {
	/**
	 * Display header.
	 */
	function fleurdesel_header_layout() {
		$layout = fleurdesel_option( 'header_layout' );

		if ( 'minimal_fullscreen' === $layout ) {
			get_template_part( 'template-parts/header/header-minimal' );
		} elseif ( 'classic_no_transparent' === $layout ) {
			get_template_part( 'template-parts/header/header-classic-no-transparent' );
		} elseif ( 'classic_centered_logo' === $layout ) {
			get_template_part( 'template-parts/header/header-centered-logo' );
		} elseif ( 'classic_slider' === $layout ) {
			get_template_part( 'template-parts/header/header-with-slider' );
		} elseif ( 'minimal_side' !== $layout ) {
			get_template_part( 'template-parts/header/header-classic' );
		}
	}
}

/**
 * Display header panels.
 */
function fleurdesel_header_panels() {
	if ( is_404() ) {
		return;
	}

	$layout = fleurdesel_option( 'header_layout' );

	if ( 'minimal_fullscreen' === fleurdesel_option( 'header_layout' ) ) {
		get_template_part( 'template-parts/header/fullscreen-menu' );

		echo '<div class="hidden-lg-up">';
		get_template_part( 'template-parts/header/header', 'mobile' );
		echo '</div>';
	} elseif ( 'minimal_side' === $layout ) {
		get_template_part( 'template-parts/header/header', 'mobile' );
	} else {
		echo '<div class="hidden-lg-up">';
		get_template_part( 'template-parts/header/header', 'mobile' );
		echo '</div>';
	}
}
add_action( 'wp_footer', 'fleurdesel_header_panels' );

if ( ! function_exists( 'fleurdesel_footer' ) ) {
	/**
	 * Show footer.
	 */
	function fleurdesel_footer() {
		get_template_part( 'template-parts/footer/footer', fleurdesel_option( 'footer_layout' ) );
	}
}

if ( ! function_exists( 'fleurdesel_social_follow' ) ) {
	/**
	 * Show soclai follow icons.
	 */
	function fleurdesel_social_follow() {
		$profiles = fleurdesel_social()->get_profile();

		if ( ! $profiles ) {
			return;
		}
		?>
		<ul class="social-icons">
			<?php foreach ( $profiles as $key => $value ) : ?>
			<li>
				<a href="<?php echo esc_url( $value['link'] ) ?>" title="<?php echo esc_html( $value['name'] ) ?>" <?php if ( fleurdesel_option( 'social_blank' ) ) : ?>target="_blank"<?php endif; ?>>
					<i class="fa fa-<?php echo esc_html( $key ) ?>"></i>
				</a>
			</li>
			<?php endforeach; ?>
		</ul>
		<?php
	}
}

/**
 * //
 *
 * @param  integer $id   Post ID.
 * @param  array   $args Arguments.
 * @return WP_Query
 */
function fleurdesel_related_query( $id = null, $args = array() ) {
	$id = is_null( $id ) ? get_the_ID() : $id;

	$defaults = array( 'posts_per_page' => 5 );
	$args = wp_parse_args( $args, $defaults );

	// Set required arguments.
	$args['post__not_in'] = array( $id );
	$args['ignore_sticky_posts'] = true;

	// Tags.
	$tag_ids = array();
	$tags = wp_get_post_tags( $id );
	foreach ( $tags as $individual_tag ) {
		$tag_ids[] = $individual_tag->term_id;
	}

	$args['tag__in'] = $tag_ids;
	return new WP_Query( $args );
}
