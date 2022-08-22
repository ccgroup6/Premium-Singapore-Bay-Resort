<?php
/**
 * Register preloader in theme options.
 *
 * @param  [type] $framework [description].
 */
function fleurdesel_preloader_options( $framework ) {
	$framework->add_section( 'preloader', function( $tab ) {

		$tab->set( array(
			'title'    => esc_html__( 'Preloader', 'fleurdesel' ),
			'icon'     => apply_filters( 'fleurdesel_preloader_settings_icon', 'dashicons-image-rotate' ),
			'priority' => 110,
		) );

		$tab->add_field(array(
			'id'   => 'preloader_show',
			'name' => esc_html__( 'Enable preloader?', 'fleurdesel' ),
			'type' => 'toggle',
		) );

		$tab->add_field( array(
			'id'   => 'preloader_logo',
			'name' => esc_html__( 'Preloader logo', 'fleurdesel' ),
			'type' => 'file',
			'deps' => array( 'preloader_show', '==', '1' ),
		) );

	}, 60 );
}
add_action( 'awethemes/theme_options/registers', 'fleurdesel_preloader_options' );
