<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Fleurdesel
 */

if ( isset( $GLOBALS['hidden_blog_sidebar'] ) && true == $GLOBALS['hidden_blog_sidebar'] ) {
	return;
}

if ( ! is_active_sidebar( $sidebar_name = Fleurdesel_Sidebar::get_sidebar() ) ) {
	return;
}

?>
<aside id="secondary" class="sidebar widget-area">
	<?php dynamic_sidebar( $sidebar_name ); ?>
</aside><!-- #secondary -->
