<?php
/**
 * Templates Keywords Filter
 */
?>
<#
	if ( ! _.isEmpty( keywords ) ) {
#>
<label><?php echo __('Filter by Widget / Addon', 'dethemekit-addons-for-elementor'); ?></label>
<select id="elementor-template-library-filter-subtype" class="elementor-template-library-filter-select dethemekit-library-keywords" data-elementor-filter="subtype">
    <option value=""><?php echo __( 'All Widgets/Addons', 'dethemekit-addons-for-elementor' ); ?></option>
    <# _.each( keywords, function( title, slug ) { #>
    <option value="{{ slug }}">{{ title }}</option>
    <# } ); #>
</select>
<#
	}
#>