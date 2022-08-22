<?php

if( ! defined( 'ABSPATH' ) ) exit; //Exit if accessed directly

/**
 *
 *  Fire the rollback function
 * 
*/
function post_dethemekit_addons_rollback() {
    
    check_admin_referer( 'dethemekit_addons_rollback' );
    
    $plugin_slug = basename( DETHEMEKIT_ADDONS_FILE, '.php' );
    
    $da_rollback = new DA_Rollback(
        [
            'version' => DETHEMEKIT_ADDONS_STABLE_VERSION,
            'plugin_name' => DETHEMEKIT_ADDONS_BASENAME,
            'plugin_slug' => $plugin_slug,
            'package_url' => sprintf( 'https://downloads.wordpress.org/plugin/%s.%s.zip', $plugin_slug, DETHEMEKIT_ADDONS_STABLE_VERSION ),
        ]
    );

    $da_rollback->run();

    wp_die(
        '', __( 'Rollback to Previous Version', 'dethemekit-addons-for-elementor' ), [
        'response' => 200,
        ]
    );
}

