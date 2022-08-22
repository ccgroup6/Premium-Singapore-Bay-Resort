<?php

$notfound_template = get_option( 'jkit_notfound_template' );
$page_template     = get_page_template_slug( $notfound_template );

if ( 'elementor_canvas' === $page_template ) {
	?>
	<!doctype html>
	<html <?php language_attributes(); ?>>
		<head>
			<meta charset="<?php bloginfo( 'charset' ); ?>">
			<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
			<link rel="profile" href="https://gmpg.org/xfn/11">
			<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
			<?php wp_head(); ?>
		</head>
		<body <?php body_class(); ?>>
			<?php wp_body_open(); ?>
			<div id="page" class="jkit-template <?php echo get_jkit_template_classes( '404' ); ?> find-404 site">
				<?php echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $notfound_template, true ); ?>
			</div>
			<?php wp_footer(); ?>
		</body>
	</html>
	<?php
} else {
	ob_start();
	get_header();
	echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $notfound_template, true );
	get_footer();

	echo ob_get_clean();
}
