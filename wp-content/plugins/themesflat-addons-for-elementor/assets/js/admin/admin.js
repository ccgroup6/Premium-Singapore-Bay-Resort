jQuery(document).ready(function ($) {
	var tfhf_hide_rule_field = function() {
		var selected = jQuery('#tfhf_template_type').val() || 'none';
		jQuery( '.tfhf-options-table' ).removeClass().addClass( 'tfhf-options-table widefat tfhf-selected-template-type-' + selected );
	}

	jQuery(document).on( 'change', '#tfhf_template_type', function( e ) {
		tfhf_hide_rule_field();
	});

	tfhf_hide_rule_field();
});
