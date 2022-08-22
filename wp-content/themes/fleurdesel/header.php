<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Fleurdesel
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php
if ( function_exists( 'wp_body_open' ) ) {
	wp_body_open();
}
?>

<?php get_template_part( 'template-parts/preloader' ); ?>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'fleurdesel' ); ?></a>

	<header id="masthead" class="site-header" <?php echo fleurdesel_get_page_title_atts(); // phpcs:ignore WordPress.Security.EscapeOutput ?> role="banner">
		<?php fleurdesel_header_layout(); ?>

		<?php get_template_part( 'template-parts/page-title' ); ?>
	</header>

	<?php do_action( 'fleurdesel_content_begin' ); ?>

	<div id="content" <?php fleurdesel_content_class(); ?>>

		<?php if ( empty( $GLOBALS['fleurdesel_hide_layout'] ) ) : ?>
			<div id="layout" <?php fleurdesel_layout_class( isset( $GLOBALS['fleurdesel_layout_class'] ) ? $GLOBALS['fleurdesel_layout_class'] : '' ); ?>>
		<?php endif ?>
