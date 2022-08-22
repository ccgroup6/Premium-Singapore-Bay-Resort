<?php
/**
 * Fleurdesel functions and definitions.
 *
 * @link    https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Fleurdesel
 */

/**
 * WordPress and PHP version compat.
 */
require get_template_directory() . '/inc/version-compat.php';

if ( ! function_exists( 'fleurdesel_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function fleurdesel_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Fleurdesel, use a find and replace
		 * to change 'fleurdesel' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'fleurdesel', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 750, 460, true );
		add_image_size( 'fleurdesel-large', 1140, 560, true );
		add_image_size( 'fleurdesel-medium', 640, 500, true );
		add_image_size( 'fleurdesel-extra-medium', 750, 650, true );
		add_image_size( 'fleurdesel-vertical', 640, 745, true );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( [
			'primary'     => esc_html__( 'Primary', 'fleurdesel' ),
			'mobile-menu' => esc_html__( 'Mobile Menu', 'fleurdesel' ),
		] );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', [
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		] );

		/*
		 * Enable support for Post Formats.
		 * See https://developer.wordpress.org/themes/functionality/post-formats/
		 */
		add_theme_support( 'post-formats', [
			'gallery',
			'quote',
			'image',
			'video',
			'audio',
			'link',
		] );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'fleurdesel_custom_background_args', [
			'default-color' => 'ffffff',
			'default-image' => '',
		] ) );

		add_theme_support( 'awethemes/theme_options', [
			'option_id' => 'fleurdesel_options',
		] );
	}
endif;
add_action( 'after_setup_theme', 'fleurdesel_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function fleurdesel_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'fleurdesel_content_width', 750 );
}

add_action( 'after_setup_theme', 'fleurdesel_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function fleurdesel_widgets_init() {
	register_sidebar( [
		'name'          => esc_html__( 'Sidebar', 'fleurdesel' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'fleurdesel' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	] );

	if ( class_exists( 'Fleurdesel_Required' ) ) {
		register_sidebars( 3, [
			/* translators: %d: sidebar index */
			'name'          => __( 'Footer centered column %d', 'fleurdesel' ),
			'id'            => 'footer-centered',
			'description'   => '',
			'class'         => '',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		] );
	}
}

add_action( 'widgets_init', 'fleurdesel_widgets_init' );

/**
 * Move comment field to bottom.
 *
 * @param  array $fields The comment fields.
 *
 * @return array
 */
function fleurdesel_change_comment_field( $fields ) {
	$comment_field = $fields['comment'];

	unset( $fields['comment'] );
	$fields['comment'] = $comment_field;

	return $fields;
}

add_filter( 'comment_form_fields', 'fleurdesel_change_comment_field' );

if ( ! function_exists( 'fleurdesel_fonts_url' ) ) :
	/**
	 * Register Google fonts for theme.
	 *
	 * Create your own fleurdesel_fonts_url() function to override in a child theme.
	 *
	 * @return string Google fonts URL for the theme.
	 */
	function fleurdesel_fonts_url() {
		$fonts_url = '';
		$fonts     = [];
		$subsets   = 'latin,latin-ext';

		/* translators: If there are characters in your language that are not supported by Playfair Display, translate this to 'off'. Do not translate into your own language. */
		if ( 'off' !== _x( 'on', 'Playfair Display font: on or off', 'fleurdesel' ) ) {
			$fonts[] = 'Playfair Display:400,700';
		}

		/* translators: If there are characters in your language that are not supported by Roboto, translate this to 'off'. Do not translate into your own language. */
		if ( 'off' !== _x( 'on', 'Roboto font: on or off', 'fleurdesel' ) ) {
			$fonts[] = 'Roboto:400,500,700';
		}

		if ( $fonts ) {
			$fonts_url = add_query_arg( [
				'family' => urlencode( implode( '|', $fonts ) ),
				'subset' => urlencode( $subsets ),
			], 'https://fonts.googleapis.com/css' );
		}

		return $fonts_url;
	}
endif;

/**
 * Enqueue scripts and styles.
 */
function fleurdesel_scripts() {
	/**
	 * Should we load minified files?
	 */
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG === true ) ? '' : '.min';

	/**
	 * If we are debugging the site, use a unique version every page load so as to ensure no cache issues.
	 */
	$version = '2.0.4';

	// Google fonts.
	wp_enqueue_style( 'fleurdesel-google-font', fleurdesel_fonts_url(), [], null );
	wp_enqueue_style( 'font-awesome', get_stylesheet_directory_uri() . '/src/css/font-awesome' . $suffix . '.css', [], '4.7.0' );
	wp_enqueue_style( 'fleurdesel-icons', get_template_directory_uri() . '/dist/css/font-fleurdeselhotel' . $suffix . '.css', [], '28112016' );
	wp_enqueue_style( 'slick', get_stylesheet_directory_uri() . '/src/css/slick.css', [], '1.6.0' );
	wp_enqueue_style( 'magnific-popup', get_stylesheet_directory_uri() . '/src/css/magnific-popup.css', [], '1.1.0' );

	if ( ! is_404() ) {
		$style_css = is_rtl() ? '/dist/css/main-rtl' . $suffix . '.css' : '/dist/css/main' . $suffix . '.css';
		wp_enqueue_style( 'fleurdesel-style', get_template_directory_uri() . $style_css, [], $version );
	} else {
		wp_enqueue_style( 'fleurdesel-404', get_template_directory_uri() . '/dist/css/404' . $suffix . '.css', [], $version );
	}

	wp_enqueue_script( 'tether', get_template_directory_uri() . '/src/js/tether.min.js', [ 'jquery' ], '032017', true );

	wp_enqueue_script( 'jquery-ui-datepicker' );
	wp_enqueue_script( 'fleurdesel-navigation', get_template_directory_uri() . '/src/js/navigation.js', [], '20151215', true );
	wp_enqueue_script( 'fleurdesel-skip-link-focus-fix', get_template_directory_uri() . '/src/js/skip-link-focus-fix.js', [], '20151215', true );
	wp_enqueue_script( 'slick', get_template_directory_uri() . '/src/js/slick' . $suffix . '.js', [ 'jquery' ], '1.8.0', true );
	wp_enqueue_script( 'magnific-popup', get_template_directory_uri() . '/src/js/jquery.magnific-popup' . $suffix . '.js', [ 'jquery' ], '1.1.0', true );
	wp_enqueue_script( 'jquery-waypoints', get_template_directory_uri() . '/src/js/jquery.waypoints.min.js', [ 'jquery' ], '4.0.1', true );
	wp_enqueue_script( 'counterup', get_template_directory_uri() . '/src/js/jquery.counterup.min.js', [ 'jquery' ], '1.0.0', true );
	wp_enqueue_script( 'imagesloaded' );
	wp_enqueue_script( 'masonry', get_template_directory_uri() . '/src/js/masonry.pkgd.min.js', [ 'jquery' ], '4.0.0', true );
	wp_enqueue_script( 'isotope', get_template_directory_uri() . '/src/js/isotope.pkgd.min.js', [ 'jquery' ], '3.0.1', true );
	wp_enqueue_script( 'packery', get_template_directory_uri() . '/src/js/packery.pkgd.min.js', [ 'jquery' ], '2.0.0', true );
	wp_enqueue_script( 'jff-vendor', get_template_directory_uri() . '/src/js/jff-vendor.min.js', [ 'jquery' ], '1.0.0', true );
	wp_enqueue_script( 'jff-utils', get_template_directory_uri() . '/src/js/jff-utils.js', [ 'jquery' ], '1.0.0', true );
	wp_enqueue_script( 'fleur-select2', get_template_directory_uri() . '/src/js/select2.full.min.js', [ 'jquery' ], '4.0.3', true );

	wp_register_script( 'fleurdesel-awebooking-form', get_template_directory_uri() . '/src/js/awebooking-custom-datepicker.js', [ 'jquery' ], $version, true );

	wp_enqueue_script( 'fleurdesel-main', get_template_directory_uri() . '/src/js/main.js', [ 'jquery' ], $version, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

add_action( 'wp_enqueue_scripts', 'fleurdesel_scripts' );

/**
 * All default options for Fleurdesel
 *
 * @return array
 */
function fleurdesel_defaults() {
	$options = apply_filters( 'fleurdesel_defaults', [
		// Site logo.
		'small_logo'                         => get_template_directory_uri() . '/src/images/default/logo-small.png',
		'side_logo'                          => get_template_directory_uri() . '/src/images/default/logo-side.png',
		'site_logo'                          => get_template_directory_uri() . '/src/images/default/logo.png',
		'404_logo'                           => get_template_directory_uri() . '/src/images/default/logo-404.png',
		'bg_side_logo'                       => get_template_directory_uri() . '/src/images/default/bg-logo-side.png',
		'bg_404'                             => get_template_directory_uri() . '/src/images/default/bg-404.png',
		'bg_page_title'                      => get_template_directory_uri() . '/src/images/default/bg-page-title.png',
		'footer_logo'                        => get_template_directory_uri() . '/img/logo-white.png',

		// Preloader.
		'preloader_show'                     => true, // Options: true | false.
		'preloader_logo'                     => get_template_directory_uri() . '/src/images/default/logo-side.png',

		// Header.
		'header_layout'                      => 'classic', // Options: classic | minimal_fullscreen | minimal_side | classic_no_transparent | classic_centered_logo | classic_slider.
		'header_minimal_logo'                => get_template_directory_uri() . '/src/images/default/logo-text-black.png',
		'bg_fs_panel'                        => get_template_directory_uri() . '/src/images/default/bg-fs-panel.png',
		'header_classic_no_transparent_logo' => get_template_directory_uri() . '/src/images/default/logo-text-black.png',
		'header_centered_logo'               => get_template_directory_uri() . '/src/images/default/logo-centered.png',
		'header_sticky'                      => false, // Options: true | false.

		// Blog detail.
		'blog_layout'                        => 'standard', // Options: standard | list | zigzag.
		'blog_summary'                       => 'content', // Options: content | excerpt | none.
		'single_sharing'                     => false,
		'single_post_navigation'             => true,

		// Page title.
		'page_title_show'                    => true, // Options: true | false.
		'page_title_bg_color'                => '',
		'page_title_bg_image'                => get_template_directory_uri() . '/src/images/default/bg-page-title.png',

		// Event.
		'event_layout'                       => 'grid', // Options: grid |
		'event_detail_layout'                => 'right', // Options : left | right.
		'event_column'                       => '2', // Options: 1 | 2 | 3.
		'event_page_title_bg_color'          => '',
		'event_page_title_bg_image'          => '',

		// Awebooking.
		'room_layout'                        => 'grid', // Options: standard | grid |list | zigzag | grid_extra_info | grid_extra_info_icon | overlay_with_content | overlay_with_extra_info.
		'room_column'                        => 3, // Options: 2 | 3 | 4.
		'room_content_alignment'             => 'left', // Options: left | center | right.
		'room_detail_layout'                 => 'standard', // Options: standard | modern.
		'room_related'                       => false, // Options: true | false.

		// Social.
		'social_blank'                       => false, // Options: true | false.

		// Footer.
		'footer_layout'                      => 'classic', // Options: classic | left-logo | centered-logo.
		'footer_bg_type'                     => 'color',
		'footer_bg_color'                    => '',
		'footer_bg_image'                    => '',
		'show_footer_classic_columns'        => false,
		'classic_footer_column'              => '1', // Options: 1 | 2 | 3 | 4.
		'show_footer_centered_logo_columns'  => false,
		'centered_logo_footer_column'        => '1', // Options: 1 | 2 | 3.
		'right_text'                         => '',

		/* translators: %s: copyright link */
		'copyright'                          => sprintf( __( 'Copyright &copy; 2017 by %s. Fleur de sel Hotel Theme crafted with love', 'fleurdesel' ), '<a href="#">Awethemes</a>' ),
		'footer_centered_logo'               => get_template_directory_uri() . '/src/images/default/logo-footer-centered.png',
		'footer_left_logo'                   => get_template_directory_uri() . '/src/images/default/logo-footer-left.png',
	] );

	return $options;
}

/**
 * Get default option for Fleurdesel.
 *
 * @param  string $name Option key name to get.
 *
 * @return mixed
 */
function fleurdesel_default( $name ) {
	static $options;

	if ( ! $options ) {
		$options = fleurdesel_defaults();
	} // End if().

	return isset( $options[ $name ] ) ? $options[ $name ] : null;
}

/**
 * Get option for this theme.
 *
 * @param  string $name    Option key name to get.
 * @param  mixed  $default Default value will be returned if option not exists.
 *
 * @return mixed|null
 */
function fleurdesel_option( $name, $default = null ) {
	$name = sanitize_key( $name );

	if ( is_null( $default ) ) {
		$default = fleurdesel_default( $name );
	}

	if ( function_exists( 'cmb2_get_option' ) ) {
		$option = cmb2_get_option( 'fleurdesel_options', $name, null );
		$option = is_null( $option ) ? $default : $option;
	} else {
		$options = get_option( 'fleurdesel_options' );
		$option  = isset( $options[ $name ] ) ? $options[ $name ] : $default;
	}

	/**
	 * Apply a filter to custom option value.
	 *
	 * @param string $option Option value.
	 *
	 * @var mixed
	 */
	$option = apply_filters( 'fleurdesel_option_' . $name, $option );
	$option = apply_filters( 'fleurdesel_option', $option, $name );

	// Return it.
	return $option;
}

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Required plugins.
 */
require get_template_directory() . '/inc/tgm-register.php';

/**
 * Register Fleurdesel Iconfonts.
 */
require get_template_directory() . '/inc/class-fleurdesel-iconfonts.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

require get_template_directory() . '/inc/class-fleurdesel-social-core.php';

require get_template_directory() . '/inc/class-fleurdesel-sidebar-core.php';

require get_template_directory() . '/inc/class-fleurdesel-post-format-parser.php';

require get_template_directory() . '/inc/page-title.php';

require get_template_directory() . '/inc/importer.php';

require get_template_directory() . '/inc/custom-color.php';

// Theme settings.
require get_template_directory() . '/inc/theme-settings/general.php';

require get_template_directory() . '/inc/theme-settings/header.php';

require get_template_directory() . '/inc/theme-settings/footer.php';

require get_template_directory() . '/inc/theme-settings/blog.php';

require get_template_directory() . '/inc/theme-settings/event.php';

require get_template_directory() . '/inc/theme-settings/404-page.php';

require get_template_directory() . '/inc/theme-settings/preloader.php';

require get_template_directory() . '/inc/theme-settings/room.php';

// Custom layout.
require get_template_directory() . '/inc/custom-layout.php';

if ( class_exists( 'AweBooking' ) ) {
	require get_template_directory() . '/inc/class-fleurdesel-awebooking-custom.php';
	require get_template_directory() . '/inc/awebooking-functions.php';
}
