<?php
/**
 * Template part for displaying page-title.
 *
 * @package Fleurdesel
 */

$page_title = fleurdesel_get_page_title_values();
if ( empty( $page_title['page_title_show'] ) ) {
	return;
}

// Hidden page-title if...
if ( ! empty( $GLOBALS['hidden_page_title'] ) || ! apply_filters( 'display_page_title', true ) ) {
	return;
}

$element = apply_filters( 'fleurdesel_page_title_element_tag', 'h1' );
?>
<section class="fleurdesel-pagetitle">
	<div class="fleurdesel-overlay"></div>
	<div class="container">
		<div class="section-header">
			<?php do_action( 'fleurdesel_before_page_title_tag' ); ?>
			<?php fleurdesel_page_title( "<{$element} class=\"fleurdesel-title\">", "</{$element}>" ); ?>
		</div>
	</div>
</section>
