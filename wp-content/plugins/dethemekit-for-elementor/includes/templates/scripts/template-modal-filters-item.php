<?php
/**
 * Template Library Filter Item
 */
?>
<label class="dethemekit-template-filter-label">
	<input type="radio" value="{{ slug }}" <# if ( '' === slug ) { #> checked<# } #> name="dethemekit-template-filter">
	<span>{{ title.replace('&amp;', '&') }}</span>
</label>