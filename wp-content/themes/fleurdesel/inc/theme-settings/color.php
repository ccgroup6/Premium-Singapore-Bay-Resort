<?php
/**
 * Custom color support in Customizer.
 *
 * @package Fleurdesel
 */

/**
 * Get color
 *
 * @param  array $color //.
 * @return string
 */
function fleurdesel_get_color_scheme( $color = array() ) {
	$color_scheme = array(
		'primary'    => '#6d8e01',
		'text_color' => '#898989',
		'link_color' => '#000000',
		'link_hover' => '#6d8e01',
	);

	$color_scheme = apply_filters( 'fleurdesel_default_color_scheme', $color_scheme );

	return wp_parse_args( $color, $color_scheme );
}

/**
 * Add support color in Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function fleurdesel_customizer_color_register( $wp_customize ) {
	// Default Color.
	$color_scheme = fleurdesel_get_color_scheme();

	$wp_customize->add_setting( 'fleurdesel_color[primary]', array(
		'default'           => $color_scheme['primary'],
		// 'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'fleurdesel_color[primary]', array(
		'label'       => esc_html__( 'Primary Color', 'fleurdesel' ),
		'section'     => 'colors',
	) ) );

	// Add main text color setting and control.
	$wp_customize->add_setting( 'fleurdesel_color[text_color]', array(
		'default'           => $color_scheme['text_color'],
		// 'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'fleurdesel_color[text_color]', array(
		'label'       => esc_html__( 'Text Color', 'fleurdesel' ),
		'section'     => 'colors',
	) ) );

	// Add link color setting and control.
	$wp_customize->add_setting( 'fleurdesel_color[link_color]', array(
		'default'           => $color_scheme['link_color'],
		// 'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'fleurdesel_color[link_color]', array(
		'label'       => esc_html__( 'Link Color', 'fleurdesel' ),
		'section'     => 'colors',
	) ) );

	// Add link hover color setting and control.
	$wp_customize->add_setting( 'fleurdesel_color[link_hover]', array(
		'default'           => $color_scheme['link_hover'],
		// 'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'fleurdesel_color[link_hover]', array(
		'label'       => esc_html__( 'Link Hover Color', 'fleurdesel' ),
		'section'     => 'colors',
	) ) );
}
add_action( 'customize_register', 'fleurdesel_customizer_color_register' );

/**
 * Outputs an Underscore template for generating CSS for the color scheme.
 *
 * The template generates the css dynamically for instant display in the
 * Customizer preview.
 */
function fleurdesel_color_scheme_css_template() {
	?><script type="text/html" id="tmpl-fleurdesel-color-scheme">
		<?php echo fleurdesel_get_color_scheme_css( array(  // WPCS: XSS OK.
			'primary'    => '{{ data.primary }}',
			'text_color' => '{{ data.text_color }}',
			'link_color' => '{{ data.link_color }}',
			'link_hover' => '{{ data.link_hover }}',
		) ); ?>
	</script><?php
}
add_action( 'customize_controls_print_footer_scripts', 'fleurdesel_color_scheme_css_template' );

/**
 * Returns CSS for the color schemes.
 *
 * @param array $color Color scheme color.
 * @return string
 */
function fleurdesel_get_color_scheme_css( $color ) {
	$color = fleurdesel_get_color_scheme( $color );

	return <<<CSS

	/* Primary */
	.product--simple {
	    color: {$color['primary']};
	}
	.fleurdesel-single-carousel {
		background-color: {$color['primary']};
	}
	.newsletter {
	    border-color: {$color['primary']};
	}

	/* Text color */
	body {
		color: {$color['text_color']};
	}

	/* Link */
	a {
		color: {$color['link_color']};
	}
	a:hover {
		color: {$color['link_hover']};
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
	$color  = wp_parse_args( fleurdesel_option( 'fleurdesel_color' ), $default );

	$css = '';

	if ( $color['primary'] !== $default['primary'] ) {
		$css .= <<<CSS
		/* Primary */
		.product--simple {
		    color: {$color['primary']};
		}
		.fleurdesel-single-carousel {
			background-color: {$color['primary']};
		}
		.newsletter {
		    border-color: {$color['primary']};
		}
CSS;
	}

	if ( $color['text_color'] !== $default['text_color'] ) {
		$css .= <<<CSS
		/* Text color */
		body {
			color: {$color['text_color']};
		}
CSS;
	}

	if ( $color['link_color'] !== $default['link_color'] ) {
		$css .= <<<CSS
		a {
			color: {$color['link_color']};
		}
CSS;
	}

	if ( $color['link_hover'] !== $default['link_hover'] ) {
		$css .= <<<CSS
		a:hover {
			color: {$color['link_hover']};
		}
CSS;
	}
	wp_add_inline_style( 'fleurdesel', $css );
}
add_action( 'wp_enqueue_scripts', 'fleurdesel_enqueue_custom_css', 20 );
