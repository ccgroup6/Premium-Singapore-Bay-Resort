<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Fleurdesel
 */

$footer_layout = fleurdesel_option( 'footer_layout' );
?>
	<?php if ( empty( $GLOBALS['fleurdesel_hide_layout'] ) ) : ?>
		</div><!-- #layout -->
	<?php endif ?>

	</div><!-- #content -->

	<?php do_action( 'fleurdesel_content_end' ); ?>

	<?php fleurdesel_footer(); ?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
