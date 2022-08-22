<?php 
//check if Elementor is installed

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function dtk_dependencies(){
  $dtk_elementor = true;
  
  if ( !dtk_is_plugin_active('elementor.php') ) $dtk_elementor=false;
  if ( !dtk_is_plugin_active('elementor-pro.php') ) $dtk_elementor=false;
  
  return $dtk_elementor;
}  

function dtk_clean_plugins($dtk_plugins){
  $results=[];
  foreach($dtk_plugins as $dtk_plugin){
    $folder="";
    $file="";
    list($folder,$file)=array_pad(explode('/',$dtk_plugin),2,"");
    if(!$file)  list($folder,$file)=array_pad(explode('\\',$dtk_plugin),2,""); // for windows
    $results[]=$file;
  }
  return $results;
}

function dtk_get_all_active_plugins(){

  if(function_exists('get_blog_option')){
    $dtk_multi_site = get_blog_option(get_current_blog_id(), 'active_plugins');
    $dtk_multi_site = isset($dtk_multi_site) ? $dtk_multi_site : [];
    $dtk_multi_sitewide=get_site_option( 'active_sitewide_plugins') ;
    if (is_array($dtk_multi_sitewide)) foreach($dtk_multi_sitewide as $dtk_key => $dtk_value){
      $dtk_multi_site[] = $dtk_key;  
    }
    $dtk_plugins = $dtk_multi_site;
  }
  else{
    $dtk_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
  }
  
  return  dtk_clean_plugins($dtk_plugins);
}

function dtk_is_plugin_active($plugin){
  $dtk_plugins = dtk_get_all_active_plugins();
  if ( in_array( $plugin ,$dtk_plugins) ) return true;
  return false;
}