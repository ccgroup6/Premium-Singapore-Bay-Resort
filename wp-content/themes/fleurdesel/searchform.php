<?php
/**
 * Search form template
 *
 * @package Fleurdesel
 */

?>
<div class="search">
	<form class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
		<input type="search" name="s" class="search-input" placeholder="<?php esc_attr_e( 'Search&hellip;', 'fleurdesel' ); ?>" value="<?php the_search_query(); ?>">

		<button type="submit" class="search-button"><i class="fa fa-search"></i></button>
	</form>
</div>
