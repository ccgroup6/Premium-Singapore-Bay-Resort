<?php

/**
 * Plugin Name: Hostinger
 * Plugin URI: https://www.hostinger.com
 * Description: Hostinger WordPress plugin.
 * Version: 1.0
 * Author: Hostinger
 * Author URI: https://www.hostinger.com
 *
 */

add_filter('astra_get_pro_url', 'astra_pro_affiliate_link', 10, 2);
function astra_pro_affiliate_link($astra_pro_url, $url)
{
    return add_query_arg('bsf', '5643', $url);
}

add_filter( 'optinmonster_sas_id', 'optinmonster_sas_id' );
function optinmonster_sas_id( $id )
{
    return '3107422';
}

add_filter( 'wpforms_upgrade_link', 'wpforms_upgrade_link' );
function wpforms_upgrade_link( $link )
{
    return 'https://shareasale.com/r.cfm?b=834775&u=3107422&m=64312&urllink=' . rawurlencode( $link );
}

add_filter( 'monsterinsights_shareasale_id', 'monsterinsights_shareasale_id' );
function monsterinsights_shareasale_id( $id )
{
    return '3107422';
}

add_filter( 'aioseo_upgrade_link', 'aioseo_upgrade_link' );
function aioseo_upgrade_link( $link )
{
    return 'https://shareasale.com/r.cfm?b=1491200&u=3107422&m=94778&urllink=' . rawurlencode( $link );
}

// Extendify plugin settings
$file = plugin_dir_path( __FILE__ ) . 'extendify.php';
if (file_exists($file)) {
    include $file;
}
