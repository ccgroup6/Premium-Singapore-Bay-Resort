<?php
/**
 * Custom color support in Customizer.
 *
 * @package Fleurdesel
 */

/**
 * Get color
 *
 * @param  array $color color scheme.
 *
 * @return string
 */
function fleurdesel_get_color_scheme( $color = [] ) {
	$color_scheme = [
		'primary'   => '#b98036',
		'secondary' => '#1c1c1c',
	];

	$color_scheme = apply_filters( 'fleurdesel_default_color_scheme', $color_scheme );

	return wp_parse_args( $color, $color_scheme );
}

/**
 * Add support color in Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function fleurdesel_customizer_color_register( $wp_customize ) {
	$wp_customize->get_section( 'colors' )->panel           = 'fleurdesel_colors';
	$wp_customize->get_section( 'background_image' )->panel = 'fleurdesel_colors';

	// Register colors panel.
	$wp_customize->add_panel( 'fleurdesel_colors', [
		'title'    => esc_html__( 'Colors', 'fleurdesel' ),
		'priority' => 55,
	] );

	// Default Color.
	$color_scheme = fleurdesel_get_color_scheme();

	$wp_customize->add_setting( 'fleurdesel_color[primary]', [
		'default'           => $color_scheme['primary'],
		// 'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	] );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'fleurdesel_color[primary]', [
		'label'   => esc_html__( 'Primary Color', 'fleurdesel' ),
		'section' => 'colors',
	] ) );

	// Add main text color setting and control.
	$wp_customize->add_setting( 'fleurdesel_color[secondary]', [
		'default'           => $color_scheme['secondary'],
		// 'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	] );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'fleurdesel_color[secondary]', [
		'label'   => esc_html__( 'Secondary Color', 'fleurdesel' ),
		'section' => 'colors',
	] ) );
}

add_action( 'customize_register', 'fleurdesel_customizer_color_register' );

/**
 * Binds the JS listener to make Customizer color_scheme control.
 */
function fleurdesel_customize_control_js() {
	wp_enqueue_script( 'fleurdesel-color-scheme-control', get_template_directory_uri() . '/js/color-scheme-control.js',
		[ 'customize-controls', 'iris', 'underscore', 'wp-util' ], '08112016', true );
}

add_action( 'customize_controls_enqueue_scripts', 'fleurdesel_customize_control_js' );

/**
 * Outputs an Underscore template for generating CSS for the color scheme.
 *
 * The template generates the css dynamically for instant display in the
 * Customizer preview.
 */
function fleurdesel_color_scheme_css_template() {
	?>
	<script type="text/html" id="tmpl-fleurdesel-color-scheme">
		<?php
		echo fleurdesel_get_color_scheme_css( [ // WPCS: XSS OK.
			'primary'   => '{{ data.primary }}',
			'secondary' => '{{ data.secondary }}',
		] );
		?>
	</script>
	<?php
}

add_action( 'customize_controls_print_footer_scripts', 'fleurdesel_color_scheme_css_template' );

/**
 * Returns CSS for the color schemes.
 *
 * @param array $color Color scheme color.
 *
 * @return string
 */
function fleurdesel_get_color_scheme_css( $color ) {
	$color = fleurdesel_get_color_scheme( $color );

	return <<<CSS

	/* Primary */
	.apb-btn, .fleurdesel-awebooking .apb-btn:hover, .sticky-label, .menu-icon span, .menu-icon:before, .menu-icon:after, .fleurdesel-awebooking .apb-field label:after, .slick-slider .slick-arrow:hover, .header-mobile__button, .fleurdesel-map__icon, .pagination .nav-links a.page-numbers:hover, .pagination .nav-links a.page-numbers:focus, .pagination .nav-links span.current {
		background-color: {$color['primary']} !important;
	}
	.fleurdesel-awebooking .apb-btn, .fleurdesel-awebooking .apb-btn:hover, .fleurdesel-iconbox--border:hover, .fleurdesel-slick-modern .slick-prev:hover, .fleurdesel-slick-modern .slick-next:hover, .fleurdesel-mailchimp:hover, .classic-header__nav .main-navigation > li > a:hover,
	 .fleurdesel-testimonial__item:hover, .spin, .pagination .nav-links a.page-numbers:hover, .pagination .nav-links a.page-numbers:focus, .pagination .nav-links span.current {
		border-color: {$color['primary']} !important;
	}
	a:focus, a:hover, .text-color-1, .fleurdesel-awebooking .apb-field label, .fleurdesel-iconbox--border:before, .fleurdesel-iconbox--border:after, .fleurdesel-iconbox--border .fleurdesel-iconbox__wrap:before, .fleurdesel-iconbox--border .fleurdesel-iconbox__wrap:after, .fleurdesel-posts-carousel__item .post-date a, .fleurdesel-mailchimp__wrap:before, .fleurdesel-mailchimp__wrap:after, .fleurdesel-mailchimp:before, .fleurdesel-mailchimp:after, .mc4wp-form button, .fleurdesel-awebooking.fleurdesel-awebooking--modern .apb-input, .fleurdesel-awebooking.fleurdesel-awebooking--modern .apb-select, .fleurdesel-awebooking.fleurdesel-awebooking--modern .apb-calendar, .fleurdesel-awebooking.fleurdesel-awebooking--modern .select2-selection__rendered, .fleurdesel-awebooking.fleurdesel-awebooking--modern .apb-field-group i,
	 .fleurdesel-event .event-price, .fleurdesel-gallery__filter a.current, .fleurdesel-gallery__item:hover .fleurdesel-gallery__title, .fleurdesel-testimonial__item:before, .fleurdesel-testimonial__item:after,
	  .fleurdesel-testimonial__wrap:before, .fleurdesel-testimonial__wrap:after, .side-panel__close-btn, .side-panel__menu li.current-menu-parent a, .post-date a {
		color: {$color['primary']} !important;
	}
	
	.sticky-label:before, .sticky-label:after, .spin, .spin:before {
		border-top-color: {$color['primary']} !important;
	}
	
	.side-panel__menu li.current-menu-parent a, .side-panel__menu a:hover, .awebooking-datepicker.flatpickr-calendar.arrowTop:after {
		border-bottom-color: {$color['primary']} !important;
	}
	
	.sticky-label:before {
		border-right-color: {$color['primary']} !important;
	}
	
	.sticky-label:after {
		border-left-color: {$color['primary']} !important;
	}
	
	.fleurdesel-room--overlay .btn-view:hover, .fleurdesel-room--overlay .awebooking .button:hover, .awebooking .fleurdesel-room--overlay .button:hover, .fleurdesel-room--overlay .awebooking-block .button:hover, .awebooking-block .fleurdesel-room--overlay .button:hover, .fleurdesel-room--overlay .apb-btn:hover, .fleurdesel-room--overlay-extra .btn-view:hover, .fleurdesel-room--overlay-extra .awebooking .button:hover, .awebooking .fleurdesel-room--overlay-extra .button:hover, .fleurdesel-room--overlay-extra .awebooking-block .button:hover, .awebooking-block .fleurdesel-room--overlay-extra .button:hover, .fleurdesel-room--overlay-extra .apb-btn:hover, .btn-primary:hover, button:hover, input:hover[type="submit"], .btn-primary, button, input[type="submit"] {
		border-color: {$color['primary']} !important;
		background-color: {$color['primary']} !important;
	}
	
	.awebooking-datepicker .flatpickr-weekdays, .awebooking-datepicker .flatpickr-months .flatpickr-month, .awebooking-datepicker span.flatpickr-weekday {
		background-color: {$color['primary']} !important;
	}
	
	.awebooking-datepicker .flatpickr-day.selected, .awebooking-datepicker .flatpickr-day.startRange, .awebooking-datepicker .flatpickr-day.endRange, .awebooking-datepicker .flatpickr-day.selected.inRange, .awebooking-datepicker .flatpickr-day.startRange.inRange, .awebooking-datepicker .flatpickr-day.endRange.inRange, .awebooking-datepicker .flatpickr-day.selected:focus, .awebooking-datepicker .flatpickr-day.startRange:focus, .awebooking-datepicker .flatpickr-day.endRange:focus, .awebooking-datepicker .flatpickr-day.selected:hover, .awebooking-datepicker .flatpickr-day.startRange:hover, .awebooking-datepicker .flatpickr-day.endRange:hover, .awebooking-datepicker .flatpickr-day.selected.prevMonthDay, .awebooking-datepicker .flatpickr-day.startRange.prevMonthDay, .awebooking-datepicker .flatpickr-day.endRange.prevMonthDay, .awebooking-datepicker .flatpickr-day.selected.nextMonthDay, .awebooking-datepicker .flatpickr-day.startRange.nextMonthDay, .awebooking-datepicker .flatpickr-day.endRange.nextMonthDay, .awebooking-datepicker .flatpickr-day.today:focus {
		border-color: {$color['primary']} !important;
		background: {$color['primary']} !important;
	}
	
	.pagination .nav-links a.page-numbers:hover {
		color: #fff !important;
	}

	/* Secondary */
	h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
	    color: {$color['secondary']};
	}
CSS;
}

/**
 * Enqueues front-end CSS
 *
 * @see wp_add_inline_style()
 */
function fleurdesel_enqueue_custom_css() {
	$default = fleurdesel_get_color_scheme();
	$color   = wp_parse_args( get_theme_mod( 'fleurdesel_color' ), $default );

	$css = '';

	if ( $color['primary'] !== $default['primary'] ) {
		$css .= <<<CSS
		/* Primary */
		.apb-btn, .fleurdesel-awebooking .apb-btn:hover, .sticky-label, .menu-icon span, .menu-icon:before, .menu-icon:after, .fleurdesel-awebooking .apb-field label:after, .slick-slider .slick-arrow:hover, .header-mobile__button, .fleurdesel-map__icon, .pagination .nav-links a.page-numbers:hover, .pagination .nav-links a.page-numbers:focus, .pagination .nav-links span.current {
			background-color: {$color['primary']} !important;
		}
		.fleurdesel-awebooking .apb-btn, .fleurdesel-awebooking .apb-btn:hover, .fleurdesel-iconbox--border:hover, .fleurdesel-slick-modern .slick-prev:hover, .fleurdesel-slick-modern .slick-next:hover, .fleurdesel-mailchimp:hover, .classic-header__nav .main-navigation > li > a:hover,
		 .fleurdesel-testimonial__item:hover, .pagination .nav-links a.page-numbers:hover, .pagination .nav-links a.page-numbers:focus, .pagination .nav-links span.current {
			border-color: {$color['primary']} !important;
		}
		a:focus, a:hover, .text-color-1, .fleurdesel-awebooking .apb-field label, .fleurdesel-iconbox--border:before, .fleurdesel-iconbox--border:after, .fleurdesel-iconbox--border .fleurdesel-iconbox__wrap:before, .fleurdesel-iconbox--border .fleurdesel-iconbox__wrap:after, .fleurdesel-posts-carousel__item .post-date a, .fleurdesel-mailchimp__wrap:before, .fleurdesel-mailchimp__wrap:after, .fleurdesel-mailchimp:before, .fleurdesel-mailchimp:after, .mc4wp-form button, .fleurdesel-awebooking.fleurdesel-awebooking--modern .apb-input, .fleurdesel-awebooking.fleurdesel-awebooking--modern .apb-select, .fleurdesel-awebooking.fleurdesel-awebooking--modern .apb-calendar, .fleurdesel-awebooking.fleurdesel-awebooking--modern .select2-selection__rendered, .fleurdesel-awebooking.fleurdesel-awebooking--modern .apb-field-group i,
		 .fleurdesel-event .event-price, .fleurdesel-gallery__filter a.current, .fleurdesel-gallery__item:hover .fleurdesel-gallery__title, .fleurdesel-testimonial__item:before, .fleurdesel-testimonial__item:after,
		  .fleurdesel-testimonial__wrap:before, .fleurdesel-testimonial__wrap:after, .side-panel__close-btn, .side-panel__menu li.current-menu-parent a, .post-date a {
			color: {$color['primary']} !important;
		}
		
		.sticky-label:before, .sticky-label:after, .spin, .spin:before {
			border-top-color: {$color['primary']} !important;
		}
		
		.side-panel__menu li.current-menu-parent a, .side-panel__menu a:hover, .awebooking-datepicker.flatpickr-calendar.arrowTop:after {
			border-bottom-color: {$color['primary']} !important;
		}
		
		.sticky-label:before {
			border-right-color: {$color['primary']} !important;
		}
		
		.sticky-label:after {
			border-left-color: {$color['primary']} !important;
		}
		
		.fleurdesel-room--overlay .btn-view:hover, .fleurdesel-room--overlay .awebooking .button:hover, .awebooking .fleurdesel-room--overlay .button:hover, .fleurdesel-room--overlay .awebooking-block .button:hover, .awebooking-block .fleurdesel-room--overlay .button:hover, .fleurdesel-room--overlay .apb-btn:hover, .fleurdesel-room--overlay-extra .btn-view:hover, .fleurdesel-room--overlay-extra .awebooking .button:hover, .awebooking .fleurdesel-room--overlay-extra .button:hover, .fleurdesel-room--overlay-extra .awebooking-block .button:hover, .awebooking-block .fleurdesel-room--overlay-extra .button:hover, .fleurdesel-room--overlay-extra .apb-btn:hover, .btn-primary:hover, button:hover, input:hover[type="submit"], .btn-primary, button, input[type="submit"] {
			border-color: {$color['primary']} !important;
			background-color: {$color['primary']} !important;
		}
		
		.awebooking-datepicker .flatpickr-weekdays, .awebooking-datepicker .flatpickr-months .flatpickr-month, .awebooking-datepicker span.flatpickr-weekday {
			background-color: {$color['primary']} !important;
		}
		
		.awebooking-datepicker .flatpickr-day.selected, .awebooking-datepicker .flatpickr-day.startRange, .awebooking-datepicker .flatpickr-day.endRange, .awebooking-datepicker .flatpickr-day.selected.inRange, .awebooking-datepicker .flatpickr-day.startRange.inRange, .awebooking-datepicker .flatpickr-day.endRange.inRange, .awebooking-datepicker .flatpickr-day.selected:focus, .awebooking-datepicker .flatpickr-day.startRange:focus, .awebooking-datepicker .flatpickr-day.endRange:focus, .awebooking-datepicker .flatpickr-day.selected:hover, .awebooking-datepicker .flatpickr-day.startRange:hover, .awebooking-datepicker .flatpickr-day.endRange:hover, .awebooking-datepicker .flatpickr-day.selected.prevMonthDay, .awebooking-datepicker .flatpickr-day.startRange.prevMonthDay, .awebooking-datepicker .flatpickr-day.endRange.prevMonthDay, .awebooking-datepicker .flatpickr-day.selected.nextMonthDay, .awebooking-datepicker .flatpickr-day.startRange.nextMonthDay, .awebooking-datepicker .flatpickr-day.endRange.nextMonthDay, .awebooking-datepicker .flatpickr-day.today:focus {
			border-color: {$color['primary']} !important;
			background: {$color['primary']} !important;
		}
		
		.pagination .nav-links a.page-numbers:hover {
			color: #fff !important;
		}
CSS;
	}

	if ( $color['secondary'] !== $default['secondary'] ) {
		$css .= <<<CSS
		/* Secondary */
		h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
		    color: {$color['secondary']};
		}
CSS;
	}

	wp_add_inline_style( 'fleurdesel-style', $css );
}

add_action( 'wp_enqueue_scripts', 'fleurdesel_enqueue_custom_css', 20 );
