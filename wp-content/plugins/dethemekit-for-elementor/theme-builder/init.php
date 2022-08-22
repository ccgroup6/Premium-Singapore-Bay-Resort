<?php

require_once DETHEMEKIT_ADDONS_PATH.'theme-builder/conditions/loop.php';
require_once DETHEMEKIT_ADDONS_PATH.'theme-builder/documents/loop.php';
require_once DETHEMEKIT_ADDONS_PATH.'theme-builder/conditions/custom-grid.php';
require_once DETHEMEKIT_ADDONS_PATH.'theme-builder/documents/custom-grid.php';
require_once DETHEMEKIT_ADDONS_PATH.'theme-builder/dynamic-tags/ele-tags.php';
//add new tags
$newtags=new ElementorPro\Modules\DynamicTags\Detags();
$newtags::instance();
//require_once DETHEMEKIT_ADDONS_PATH.'theme-builder/classes/custom-types-manager.php';

use Elementor\TemplateLibrary\Source_Local;
use ElementorPro\Modules\ThemeBuilder\Documents\DeLoop;
use ElementorPro\Plugin;
use ElementorPro\Modules\ThemeBuilder\Documents\Theme_Document;
use Elementor\Core\Documents_Manager;
use ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager;


Plugin::elementor()->documents->register_document_type( 'de_loop', DeLoop::get_class_full_name() );
Source_Local::add_template_type( 'de_loop' );

function dtk_get_document( $post_id ) {
  $document = null;

  try {
    $document = Plugin::elementor()->documents->get( $post_id );
  } catch ( \Exception $e ) {}

  if ( ! empty( $document ) && ! $document instanceof Theme_Document ) {
    $document = null;
  }

  return $document;
}

function dtk_add_more_types($settings){
  $post_id = get_the_ID();
  $document = dtk_get_document( $post_id );

  if ( ! $document || !array_key_exists('theme_builder', $settings)) {
		return $settings;
	}
  
  $new_types=['de_loop'=>DeLoop::get_properties()];
  $add_settings=['theme_builder' => ['types' =>$new_types]];
  if (!array_key_exists('de_loop', $settings['theme_builder']['types'])) $settings = array_merge_recursive($settings, $add_settings);
  return $settings;
}

add_filter( 'elementor_pro/editor/localize_settings', 'dtk_add_more_types' );


function dtk_register_elementor_locations( $elementor_theme_manager ) {

	$elementor_theme_manager->register_location(
		'de_loop',
		[
			'label' => __( 'De Loop', 'detheme-kit' ),
			'multiple' => true,
			'edit_in_content' => true,
		]
	);

}
add_action( 'elementor/theme/register_locations', 'dtk_register_elementor_locations' );

function dtk_enqueue_scripts($post){
  $document = dtk_get_document( $post->get_post_id() );
  //print_r($document->get_location());
  if($document)
  if('de_loop' == $document->get_location()){
      wp_enqueue_script(
        'ecs-preview',
        DETHEMEKIT_ADDONS_DIR_URL.'assets/js/de_loop/ecs_preview.js',
        array( 'jquery', 'elementor-frontend' ),
        DETHEMEKIT_ADDONS_VERSION,
        true
      );
  }
}
add_action( 'elementor/preview/init', 'dtk_enqueue_scripts' );

/* register custom grid document */
	function dtk_register_documents_grid( Documents_Manager $documents_manager ) {
		$documents_manager->register_document_type( 'de_grid', deGrid::get_class_full_name() );
	}


add_action( 'elementor/documents/register', 'dtk_register_documents_grid' );

	function dtk_register_location_grid( Locations_Manager $location_manager ) {
		$location_manager->register_location(
			'de_grid',
			[
				'label' => __( 'De Grid', 'detheme-kit' ),
				'multiple' => true,
				'edit_in_content' => true,
			]
		);
	}

add_action( 'elementor/theme/register_locations', 'dtk_register_location_grid' );
