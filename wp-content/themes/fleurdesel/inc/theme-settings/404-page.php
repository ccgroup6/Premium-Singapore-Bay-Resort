<?php
/**
 * Register 404 settings.
 *
 * @param  Object $framework object.
 *
 * @package Fleurdeel
 */
function fleurdesel_404_options( $framework ) {
	$framework->add_section( '404', function( $tab ) {

		$tab->set( array(
			'title'    => esc_html__( '404 Page', 'fleurdesel' ),
			'icon'     => 'dashicons-dismiss',
			'priority' => 100,
		) );

		$tab->add_field( array(
			'id'   => '404_logo',
			'name' => esc_html__( '404 logo', 'fleurdesel' ),
			'desc' => esc_html__( 'Select an image.', 'fleurdesel' ),
			'type' => 'file',
		) );

		$tab->add_field( array(
			'id'   => 'bg_404',
			'name' => esc_html__( '404 Background', 'fleurdesel' ),
			'desc' => esc_html__( 'Select an image.', 'fleurdesel' ),
			'type' => 'file',
		) );
	}, 25 );
}
add_action( 'awethemes/theme_options/registers', 'fleurdesel_404_options' );
