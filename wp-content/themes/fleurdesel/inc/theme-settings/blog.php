<?php
/**
 * Register blog settings.
 *
 * @param  Object $framework object.
 *
 * @package Fleurdesel
 */
function fleurdesel_blog_options( $framework ) {
	$framework->add_section( 'blog', function( $tab ) {

		$tab->set( array(
			'title'    => esc_html__( 'Blog', 'fleurdesel' ),
			'icon'     => 'dashicons-admin-post',
			'priority' => 40,
		) );

		$tab->add_field(array(
			'id'   => 'blog_area',
			'name' => esc_html__( 'Blog', 'fleurdesel' ),
			'type' => 'title',
			'desc' => esc_html__( 'Settings for blog', 'fleurdesel' ),
		) );

		$tab->add_field(array(
			'id'      => 'blog_layout',
			'name'    => esc_html__( 'Blog layouts', 'fleurdesel' ),
			'type'    => 'select',
			'desc'    => esc_html__( 'Select a layout to show in Blog page.', 'fleurdesel' ),
			'options' => array(
				'standard' => esc_html__( 'Standard (default)', 'fleurdesel' ),
				'list'     => esc_html__( 'List', 'fleurdesel' ),
				'zigzag'   => esc_html__( 'Zigzag', 'fleurdesel' ),
			),
		) );

		$tab->add_field(array(
			'id'      => 'blog_summary',
			'name'    => esc_html__( 'Post Summary', 'fleurdesel' ),
			'type'    => 'select',
			'desc'    => esc_html__( 'Select a post summary to show in Blog page.', 'fleurdesel' ),
			'options' => array(
				'content' => esc_html__( 'Content (default)', 'fleurdesel' ),
				'excerpt' => esc_html__( 'Excerpt', 'fleurdesel' ),
				'none'    => esc_html__( 'None', 'fleurdesel' ),
			),
		) );

		$tab->add_field(array(
			'id'   => 'single_area',
			'name' => esc_html__( 'Single Post', 'fleurdesel' ),
			'type' => 'title',
			'desc' => esc_html__( 'Settings for single page', 'fleurdesel' ),
		) );

		$tab->add_field( array(
			'id'   => 'single_sharing',
			'name' => esc_html__( 'Single sharing | Show', 'fleurdesel' ),
			'type' => 'toggle',
			'desc' => esc_html__( 'Off / On', 'fleurdesel' ),
		));

		$tab->add_field( array(
			'id'   => 'single_post_navigation',
			'name' => esc_html__( 'Single post navigation | Show', 'fleurdesel' ),
			'type' => 'toggle',
			'desc' => esc_html__( 'Off / On', 'fleurdesel' ),
		));
	} );
}
add_action( 'awethemes/theme_options/registers', 'fleurdesel_blog_options' );
