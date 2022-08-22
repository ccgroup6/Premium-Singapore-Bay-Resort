"use strict";
/**
 *   Ajax   ----------------------------------------------------------------------------------------------------- */
//var is_this_action = false;

/**
 * Send Ajax action request,  like approving or cancellation
 *
 * @param action_param
 */

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }

function wpbc_ajx_booking_ajax_action_request() {
  var action_param = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  console.groupCollapsed('WPBC_AJX_BOOKING_ACTIONS');
  console.log(' == Ajax Actions :: Params == ', action_param); //is_this_action = true;

  wpbc_booking_listing_reload_button__spin_start(); // Get redefined Locale,  if action on single booking !

  if (undefined != action_param['booking_id'] && !Array.isArray(action_param['booking_id'])) {
    // Not array
    action_param['locale'] = wpbc_get_selected_locale(action_param['booking_id'], wpbc_ajx_booking_listing.get_secure_param('locale'));
  }

  var action_post_params = {
    action: 'WPBC_AJX_BOOKING_ACTIONS',
    nonce: wpbc_ajx_booking_listing.get_secure_param('nonce'),
    wpbc_ajx_user_id: undefined == action_param['user_id'] ? wpbc_ajx_booking_listing.get_secure_param('user_id') : action_param['user_id'],
    wpbc_ajx_locale: undefined == action_param['locale'] ? wpbc_ajx_booking_listing.get_secure_param('locale') : action_param['locale'],
    action_params: action_param
  }; // It's required for CSV export - getting the same list  of bookings

  if (typeof action_param.search_params !== 'undefined') {
    action_post_params['search_params'] = action_param.search_params;
    delete action_post_params.action_params.search_params;
  } // Start Ajax


  jQuery.post(wpbc_global1.wpbc_ajaxurl, action_post_params,
  /**
   * S u c c e s s
   *
   * @param response_data		-	its object returned from  Ajax - class-live-searcg.php
   * @param textStatus		-	'success'
   * @param jqXHR				-	Object
   */
  function (response_data, textStatus, jqXHR) {
    console.log(' == Ajax Actions :: Response WPBC_AJX_BOOKING_ACTIONS == ', response_data);
    console.groupEnd(); // Probably Error

    if (_typeof(response_data) !== 'object' || response_data === null) {
      jQuery('#wh_sort_selector').hide();
      jQuery(wpbc_ajx_booking_listing.get_other_param('listing_container')).html('<div class="wpbc-settings-notice notice-warning" style="text-align:left">' + response_data + '</div>');
      return;
    }

    wpbc_booking_listing_reload_button__spin_pause();
    wpbc_admin_show_message(response_data['ajx_after_action_message'].replace(/\n/g, "<br />"), '1' == response_data['ajx_after_action_result'] ? 'success' : 'error', 10000); // Success response

    if ('1' == response_data['ajx_after_action_result']) {
      var is_reload_ajax_listing = true; // After Google Calendar import show imported bookings and reload the page for toolbar parameters update

      if (false !== response_data['ajx_after_action_result_all_params_arr']['new_listing_params']) {
        wpbc_ajx_booking_send_search_request_with_params(response_data['ajx_after_action_result_all_params_arr']['new_listing_params']);
        var closed_timer = setTimeout(function () {
          if (wpbc_booking_listing_reload_button__is_spin()) {
            if (undefined != response_data['ajx_after_action_result_all_params_arr']['new_listing_params']['reload_url_params']) {
              document.location.href = response_data['ajx_after_action_result_all_params_arr']['new_listing_params']['reload_url_params'];
            } else {
              document.location.reload();
            }
          }
        }, 2000);
        is_reload_ajax_listing = false;
      } // Start download exported CSV file


      if (undefined != response_data['ajx_after_action_result_all_params_arr']['export_csv_url']) {
        wpbc_ajx_booking__export_csv_url__download(response_data['ajx_after_action_result_all_params_arr']['export_csv_url']);
        is_reload_ajax_listing = false;
      }

      if (is_reload_ajax_listing) {
        wpbc_ajx_booking__actual_listing__show(); //	Sending Ajax Request	-	with parameters that  we early  defined in "wpbc_ajx_booking_listing" Obj.
      }
    } // Remove spin icon from  button and Enable this button.


    wpbc_button__remove_spin(response_data['ajx_cleaned_params']['ui_clicked_element_id']); // Hide modals

    wpbc_popup_modals__hide();
    jQuery('#ajax_respond').html(response_data); // For ability to show response, add such DIV element to page
  }).fail(function (jqXHR, textStatus, errorThrown) {
    if (window.console && window.console.log) {
      console.log('Ajax_Error', jqXHR, textStatus, errorThrown);
    }

    jQuery('#wh_sort_selector').hide();
    var error_message = '<strong>' + 'Error!' + '</strong> ' + errorThrown;

    if (jqXHR.responseText) {
      error_message += jqXHR.responseText;
    }

    error_message = error_message.replace(/\n/g, "<br />");
    wpbc_ajx_booking_show_message(error_message);
  }) // .done(   function ( data, textStatus, jqXHR ) {   if ( window.console && window.console.log ){ console.log( 'second success', data, textStatus, jqXHR ); }    })
  // .always( function ( data_jqXHR, textStatus, jqXHR_errorThrown ) {   if ( window.console && window.console.log ){ console.log( 'always finished', data_jqXHR, textStatus, jqXHR_errorThrown ); }     })
  ; // End Ajax
}
/**
 *   Support Functions - Spin Icon in Buttons  ------------------------------------------------------------------ */

/**
 * Remove spin icon from  button and Enable this button.
 *
 * @param button_clicked_element_id		- HTML ID attribute of this button
 * @return string						- CSS classes that was previously in button icon
 */


function wpbc_button__remove_spin(button_clicked_element_id) {
  var previos_classes = '';

  if (undefined != button_clicked_element_id) {
    var jElement = jQuery('#' + button_clicked_element_id);

    if (jElement.length) {
      previos_classes = wpbc_button_disable_loading_icon(jElement.get(0));
    }
  }

  return previos_classes;
}
/**
 * Show Loading (rotating arrow) icon for button that has been clicked
 *
 * @param this_button		- this object of specific button
 * @return string			- CSS classes that was previously in button icon
 */


function wpbc_button_enable_loading_icon(this_button) {
  var jButton = jQuery(this_button);
  var jIcon = jButton.find('i');
  var previos_classes = jIcon.attr('class');
  jIcon.removeClass().addClass('menu_icon icon-1x wpbc_icn_rotate_right wpbc_spin'); // Set Rotate icon
  //jIcon.addClass( 'wpbc_animation_pause' );												// Pause animation
  //jIcon.addClass( 'wpbc_ui_red' );														// Set icon color red

  jIcon.attr('wpbc_previous_class', previos_classes);
  jButton.addClass('disabled'); // Disable button
  //jButton.prop( "disabled", true );
  // We need to  set  here attr instead of prop, because for A elements,  attribute 'disabled' do  not added with jButton.prop( "disabled", true );

  jButton.attr('wpbc_previous_onclick', jButton.attr('onclick')); //Save this value

  jButton.attr('onclick', ''); // Disable actions "on click"

  return previos_classes;
}
/**
 * Hide Loading (rotating arrow) icon for button that was clicked and show previous icon and enable button
 *
 * @param this_button		- this object of specific button
 * @return string			- CSS classes that was previously in button icon
 */


function wpbc_button_disable_loading_icon(this_button) {
  var jButton = jQuery(this_button);
  var jIcon = jButton.find('i');
  var previos_classes = jIcon.attr('wpbc_previous_class');

  if ('' != previos_classes) {
    jIcon.removeClass().addClass(previos_classes);
  }

  jButton.removeClass('disabled'); // Remove Disable button

  var previous_onclick = jButton.attr('wpbc_previous_onclick');

  if ('' != previous_onclick) {
    jButton.attr('onclick', previous_onclick);
  }

  return previos_classes;
}
/**
 * Hide all open modal popups windows
 */


function wpbc_popup_modals__hide() {
  // Hide modals
  if ('function' === typeof jQuery('.wpbc_popup_modal').wpbc_my_modal) {
    jQuery('.wpbc_popup_modal').wpbc_my_modal('hide');
  }
}
/**
 *   Dates  Short <-> Wide    ----------------------------------------------------------------------------------- */


function wpbc_ajx_click_on_dates_short() {
  jQuery('#booking_dates_small,.booking_dates_full').hide();
  jQuery('#booking_dates_full,.booking_dates_small').show();
  wpbc_ajx_booking_send_search_request_with_params({
    'ui_usr__dates_short_wide': 'short'
  });
}

function wpbc_ajx_click_on_dates_wide() {
  jQuery('#booking_dates_full,.booking_dates_small').hide();
  jQuery('#booking_dates_small,.booking_dates_full').show();
  wpbc_ajx_booking_send_search_request_with_params({
    'ui_usr__dates_short_wide': 'wide'
  });
}

function wpbc_ajx_click_on_dates_toggle(this_date) {
  jQuery(this_date).parents('.wpbc_col_dates').find('.booking_dates_small').toggle();
  jQuery(this_date).parents('.wpbc_col_dates').find('.booking_dates_full').toggle();
  /*
  var visible_section = jQuery( this_date ).parents( '.booking_dates_expand_section' );
  visible_section.hide();
  if ( visible_section.hasClass( 'booking_dates_full' ) ){
  	visible_section.parents( '.wpbc_col_dates' ).find( '.booking_dates_small' ).show();
  } else {
  	visible_section.parents( '.wpbc_col_dates' ).find( '.booking_dates_full' ).show();
  }*/

  console.log('wpbc_ajx_click_on_dates_toggle', this_date);
}
/**
 *   Locale   --------------------------------------------------------------------------------------------------- */

/**
 * 	Select options in select boxes based on attribute "value_of_selected_option" and RED color and hint for LOCALE button   --  It's called from 	wpbc_ajx_booking_define_ui_hooks()  	each  time after Listing loading.
 */


function wpbc_ajx_booking__ui_define__locale() {
  jQuery('.wpbc_listing_container select').each(function (index) {
    var selection = jQuery(this).attr("value_of_selected_option"); // Define selected select boxes

    if (undefined !== selection) {
      jQuery(this).find('option[value="' + selection + '"]').prop('selected', true);

      if ('' != selection && jQuery(this).hasClass('set_booking_locale_selectbox')) {
        // Locale
        var booking_locale_button = jQuery(this).parents('.ui_element_locale').find('.set_booking_locale_button'); //booking_locale_button.css( 'color', '#db4800' );		// Set button  red

        booking_locale_button.addClass('wpbc_ui_red'); // Set button  red

        if ('function' === typeof wpbc_tippy) {
          booking_locale_button.get(0)._tippy.setContent(selection);
        }
      }
    }
  });
}
/**
 *   Remark   --------------------------------------------------------------------------------------------------- */

/**
 * Define content of remark "booking note" button and textarea.  -- It's called from 	wpbc_ajx_booking_define_ui_hooks()  	each  time after Listing loading.
 */


function wpbc_ajx_booking__ui_define__remark() {
  jQuery('.wpbc_listing_container .ui_remark_section textarea').each(function (index) {
    var text_val = jQuery(this).val();

    if (undefined !== text_val && '' != text_val) {
      var remark_button = jQuery(this).parents('.ui_group').find('.set_booking_note_button');

      if (remark_button.length > 0) {
        remark_button.addClass('wpbc_ui_red'); // Set button  red

        if ('function' === typeof wpbc_tippy) {
          //remark_button.get( 0 )._tippy.allowHTML = true;
          //remark_button.get( 0 )._tippy.setContent( text_val.replace(/[\n\r]/g, '<br>') );
          remark_button.get(0)._tippy.setProps({
            allowHTML: true,
            content: text_val.replace(/[\n\r]/g, '<br>')
          });
        }
      }
    }
  });
}
/**
 * Actions ,when we click on "Remark" button.
 *
 * @param jq_button  -	this jQuery button  object
 */


function wpbc_ajx_booking__ui_click__remark(jq_button) {
  jq_button.parents('.ui_group').find('.ui_remark_section').toggle();
}
/**
 *   Change booking resource   ---------------------------------------------------------------------------------- */


function wpbc_ajx_booking__ui_click_show__change_resource(booking_id, resource_id) {
  // Define ID of booking to hidden input
  jQuery('#change_booking_resource__booking_id').val(booking_id); // Select booking resource  that belong to  booking

  jQuery('#change_booking_resource__resource_select').val(resource_id).trigger('change');
  var cbr; // Get Resource section

  cbr = jQuery("#change_booking_resource__section").detach(); // Append it to booking ROW

  cbr.appendTo(jQuery("#ui__change_booking_resource__section_in_booking_" + booking_id));
  cbr = null; // Hide sections of "Change booking resource" in all other bookings ROWs
  //jQuery( ".ui__change_booking_resource__section_in_booking" ).hide();

  if (!jQuery("#ui__change_booking_resource__section_in_booking_" + booking_id).is(':visible')) {
    jQuery(".ui__under_actions_row__section_in_booking").hide();
  } // Show only "change booking resource" section  for current booking


  jQuery("#ui__change_booking_resource__section_in_booking_" + booking_id).toggle();
}

function wpbc_ajx_booking__ui_click_save__change_resource(this_el, booking_action, el_id) {
  wpbc_ajx_booking_ajax_action_request({
    'booking_action': booking_action,
    'booking_id': jQuery('#change_booking_resource__booking_id').val(),
    'selected_resource_id': jQuery('#change_booking_resource__resource_select').val(),
    'ui_clicked_element_id': el_id
  });
  wpbc_button_enable_loading_icon(this_el); // wpbc_ajx_booking__ui_click_close__change_resource();
}

function wpbc_ajx_booking__ui_click_close__change_resource() {
  var cbrce; // Get Resource section

  cbrce = jQuery("#change_booking_resource__section").detach(); // Append it to hidden HTML template section  at  the bottom  of the page

  cbrce.appendTo(jQuery("#wpbc_hidden_template__change_booking_resource"));
  cbrce = null; // Hide all change booking resources sections

  jQuery(".ui__change_booking_resource__section_in_booking").hide();
}
/**
 *   Duplicate booking in other resource   ---------------------------------------------------------------------- */


function wpbc_ajx_booking__ui_click_show__duplicate_booking(booking_id, resource_id) {
  // Define ID of booking to hidden input
  jQuery('#duplicate_booking_to_other_resource__booking_id').val(booking_id); // Select booking resource  that belong to  booking

  jQuery('#duplicate_booking_to_other_resource__resource_select').val(resource_id).trigger('change');
  var cbr; // Get Resource section

  cbr = jQuery("#duplicate_booking_to_other_resource__section").detach(); // Append it to booking ROW

  cbr.appendTo(jQuery("#ui__duplicate_booking_to_other_resource__section_in_booking_" + booking_id));
  cbr = null; // Hide sections of "Duplicate booking" in all other bookings ROWs

  if (!jQuery("#ui__duplicate_booking_to_other_resource__section_in_booking_" + booking_id).is(':visible')) {
    jQuery(".ui__under_actions_row__section_in_booking").hide();
  } // Show only "Duplicate booking" section  for current booking ROW


  jQuery("#ui__duplicate_booking_to_other_resource__section_in_booking_" + booking_id).toggle();
}

function wpbc_ajx_booking__ui_click_save__duplicate_booking(this_el, booking_action, el_id) {
  wpbc_ajx_booking_ajax_action_request({
    'booking_action': booking_action,
    'booking_id': jQuery('#duplicate_booking_to_other_resource__booking_id').val(),
    'selected_resource_id': jQuery('#duplicate_booking_to_other_resource__resource_select').val(),
    'ui_clicked_element_id': el_id
  });
  wpbc_button_enable_loading_icon(this_el); // wpbc_ajx_booking__ui_click_close__change_resource();
}

function wpbc_ajx_booking__ui_click_close__duplicate_booking() {
  var cbrce; // Get Resource section

  cbrce = jQuery("#duplicate_booking_to_other_resource__section").detach(); // Append it to hidden HTML template section  at  the bottom  of the page

  cbrce.appendTo(jQuery("#wpbc_hidden_template__duplicate_booking_to_other_resource"));
  cbrce = null; // Hide all change booking resources sections

  jQuery(".ui__duplicate_booking_to_other_resource__section_in_booking").hide();
}
/**
 *   Change payment status   ------------------------------------------------------------------------------------ */


function wpbc_ajx_booking__ui_click_show__set_payment_status(booking_id) {
  var jSelect = jQuery('#ui__set_payment_status__section_in_booking_' + booking_id).find('select');
  var selected_pay_status = jSelect.attr("ajx-selected-value"); // Is it float - then  it's unknown

  if (!isNaN(parseFloat(selected_pay_status))) {
    jSelect.find('option[value="1"]').prop('selected', true); // Unknown  value is '1' in select box
  } else {
    jSelect.find('option[value="' + selected_pay_status + '"]').prop('selected', true); // Otherwise known payment status
  } // Hide sections of "Change booking resource" in all other bookings ROWs


  if (!jQuery("#ui__set_payment_status__section_in_booking_" + booking_id).is(':visible')) {
    jQuery(".ui__under_actions_row__section_in_booking").hide();
  } // Show only "change booking resource" section  for current booking


  jQuery("#ui__set_payment_status__section_in_booking_" + booking_id).toggle();
}

function wpbc_ajx_booking__ui_click_save__set_payment_status(booking_id, this_el, booking_action, el_id) {
  wpbc_ajx_booking_ajax_action_request({
    'booking_action': booking_action,
    'booking_id': booking_id,
    'selected_payment_status': jQuery('#ui_btn_set_payment_status' + booking_id).val(),
    'ui_clicked_element_id': el_id + '_save'
  });
  wpbc_button_enable_loading_icon(this_el);
  jQuery('#' + el_id + '_cancel').hide(); //wpbc_button_enable_loading_icon( jQuery( '#' + el_id + '_cancel').get(0) );
}

function wpbc_ajx_booking__ui_click_close__set_payment_status() {
  // Hide all change  payment status for booking
  jQuery(".ui__set_payment_status__section_in_booking").hide();
}
/**
 *   Change booking cost   -------------------------------------------------------------------------------------- */


function wpbc_ajx_booking__ui_click_save__set_booking_cost(booking_id, this_el, booking_action, el_id) {
  wpbc_ajx_booking_ajax_action_request({
    'booking_action': booking_action,
    'booking_id': booking_id,
    'booking_cost': jQuery('#ui_btn_set_booking_cost' + booking_id + '_cost').val(),
    'ui_clicked_element_id': el_id + '_save'
  });
  wpbc_button_enable_loading_icon(this_el);
  jQuery('#' + el_id + '_cancel').hide(); //wpbc_button_enable_loading_icon( jQuery( '#' + el_id + '_cancel').get(0) );
}

function wpbc_ajx_booking__ui_click_close__set_booking_cost() {
  // Hide all change  payment status for booking
  jQuery(".ui__set_booking_cost__section_in_booking").hide();
}
/**
 *   Send Payment request   -------------------------------------------------------------------------------------- */


function wpbc_ajx_booking__ui_click__send_payment_request() {
  wpbc_ajx_booking_ajax_action_request({
    'booking_action': 'send_payment_request',
    'booking_id': jQuery('#wpbc_modal__payment_request__booking_id').val(),
    'reason_of_action': jQuery('#wpbc_modal__payment_request__reason_of_action').val(),
    'ui_clicked_element_id': 'wpbc_modal__payment_request__button_send'
  });
  wpbc_button_enable_loading_icon(jQuery('#wpbc_modal__payment_request__button_send').get(0));
}
/**
 *   Import Google Calendar  ------------------------------------------------------------------------------------ */


function wpbc_ajx_booking__ui_click__import_google_calendar() {
  wpbc_ajx_booking_ajax_action_request({
    'booking_action': 'import_google_calendar',
    'ui_clicked_element_id': 'wpbc_modal__import_google_calendar__button_send',
    'booking_gcal_events_from': jQuery('#wpbc_modal__import_google_calendar__section #booking_gcal_events_from option:selected').val(),
    'booking_gcal_events_from_offset': jQuery('#wpbc_modal__import_google_calendar__section #booking_gcal_events_from_offset').val(),
    'booking_gcal_events_from_offset_type': jQuery('#wpbc_modal__import_google_calendar__section #booking_gcal_events_from_offset_type option:selected').val(),
    'booking_gcal_events_until': jQuery('#wpbc_modal__import_google_calendar__section #booking_gcal_events_until option:selected').val(),
    'booking_gcal_events_until_offset': jQuery('#wpbc_modal__import_google_calendar__section #booking_gcal_events_until_offset').val(),
    'booking_gcal_events_until_offset_type': jQuery('#wpbc_modal__import_google_calendar__section #booking_gcal_events_until_offset_type option:selected').val(),
    'booking_gcal_events_max': jQuery('#wpbc_modal__import_google_calendar__section #booking_gcal_events_max').val(),
    'booking_gcal_resource': jQuery('#wpbc_modal__import_google_calendar__section #wpbc_booking_resource option:selected').val()
  });
  wpbc_button_enable_loading_icon(jQuery('#wpbc_modal__import_google_calendar__section #wpbc_modal__import_google_calendar__button_send').get(0));
}
/**
 *   Export bookings to CSV  ------------------------------------------------------------------------------------ */


function wpbc_ajx_booking__ui_click__export_csv(params) {
  var selected_booking_id_arr = wpbc_get_selected_row_id();
  wpbc_ajx_booking_ajax_action_request({
    'booking_action': params['booking_action'],
    'ui_clicked_element_id': params['ui_clicked_element_id'],
    'export_type': params['export_type'],
    'csv_export_separator': params['csv_export_separator'],
    'csv_export_skip_fields': params['csv_export_skip_fields'],
    'booking_id': selected_booking_id_arr.join(','),
    'search_params': wpbc_ajx_booking_listing.search_get_all_params()
  });
  var this_el = jQuery('#' + params['ui_clicked_element_id']).get(0);
  wpbc_button_enable_loading_icon(this_el);
}
/**
 * Open URL in new tab - mainly  it's used for open CSV link  for downloaded exported bookings as CSV
 *
 * @param export_csv_url
 */


function wpbc_ajx_booking__export_csv_url__download(export_csv_url) {
  //var selected_booking_id_arr = wpbc_get_selected_row_id();
  document.location.href = export_csv_url; // + '&selected_id=' + selected_booking_id_arr.join(',');
  // It's open additional dialog for asking opening ulr in new tab
  // window.open( export_csv_url, '_blank').focus();
}
//# sourceMappingURL=data:application/json;charset=utf8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImluY2x1ZGVzL3BhZ2UtYm9va2luZ3MvX3NyYy9ib29raW5nc19fYWN0aW9ucy5qcyJdLCJuYW1lcyI6WyJ3cGJjX2FqeF9ib29raW5nX2FqYXhfYWN0aW9uX3JlcXVlc3QiLCJhY3Rpb25fcGFyYW0iLCJjb25zb2xlIiwiZ3JvdXBDb2xsYXBzZWQiLCJsb2ciLCJ3cGJjX2Jvb2tpbmdfbGlzdGluZ19yZWxvYWRfYnV0dG9uX19zcGluX3N0YXJ0IiwidW5kZWZpbmVkIiwiQXJyYXkiLCJpc0FycmF5Iiwid3BiY19nZXRfc2VsZWN0ZWRfbG9jYWxlIiwid3BiY19hanhfYm9va2luZ19saXN0aW5nIiwiZ2V0X3NlY3VyZV9wYXJhbSIsImFjdGlvbl9wb3N0X3BhcmFtcyIsImFjdGlvbiIsIm5vbmNlIiwid3BiY19hanhfdXNlcl9pZCIsIndwYmNfYWp4X2xvY2FsZSIsImFjdGlvbl9wYXJhbXMiLCJzZWFyY2hfcGFyYW1zIiwialF1ZXJ5IiwicG9zdCIsIndwYmNfZ2xvYmFsMSIsIndwYmNfYWpheHVybCIsInJlc3BvbnNlX2RhdGEiLCJ0ZXh0U3RhdHVzIiwianFYSFIiLCJncm91cEVuZCIsImhpZGUiLCJnZXRfb3RoZXJfcGFyYW0iLCJodG1sIiwid3BiY19ib29raW5nX2xpc3RpbmdfcmVsb2FkX2J1dHRvbl9fc3Bpbl9wYXVzZSIsIndwYmNfYWRtaW5fc2hvd19tZXNzYWdlIiwicmVwbGFjZSIsImlzX3JlbG9hZF9hamF4X2xpc3RpbmciLCJ3cGJjX2FqeF9ib29raW5nX3NlbmRfc2VhcmNoX3JlcXVlc3Rfd2l0aF9wYXJhbXMiLCJjbG9zZWRfdGltZXIiLCJzZXRUaW1lb3V0Iiwid3BiY19ib29raW5nX2xpc3RpbmdfcmVsb2FkX2J1dHRvbl9faXNfc3BpbiIsImRvY3VtZW50IiwibG9jYXRpb24iLCJocmVmIiwicmVsb2FkIiwid3BiY19hanhfYm9va2luZ19fZXhwb3J0X2Nzdl91cmxfX2Rvd25sb2FkIiwid3BiY19hanhfYm9va2luZ19fYWN0dWFsX2xpc3RpbmdfX3Nob3ciLCJ3cGJjX2J1dHRvbl9fcmVtb3ZlX3NwaW4iLCJ3cGJjX3BvcHVwX21vZGFsc19faGlkZSIsImZhaWwiLCJlcnJvclRocm93biIsIndpbmRvdyIsImVycm9yX21lc3NhZ2UiLCJyZXNwb25zZVRleHQiLCJ3cGJjX2FqeF9ib29raW5nX3Nob3dfbWVzc2FnZSIsImJ1dHRvbl9jbGlja2VkX2VsZW1lbnRfaWQiLCJwcmV2aW9zX2NsYXNzZXMiLCJqRWxlbWVudCIsImxlbmd0aCIsIndwYmNfYnV0dG9uX2Rpc2FibGVfbG9hZGluZ19pY29uIiwiZ2V0Iiwid3BiY19idXR0b25fZW5hYmxlX2xvYWRpbmdfaWNvbiIsInRoaXNfYnV0dG9uIiwiakJ1dHRvbiIsImpJY29uIiwiZmluZCIsImF0dHIiLCJyZW1vdmVDbGFzcyIsImFkZENsYXNzIiwicHJldmlvdXNfb25jbGljayIsIndwYmNfbXlfbW9kYWwiLCJ3cGJjX2FqeF9jbGlja19vbl9kYXRlc19zaG9ydCIsInNob3ciLCJ3cGJjX2FqeF9jbGlja19vbl9kYXRlc193aWRlIiwid3BiY19hanhfY2xpY2tfb25fZGF0ZXNfdG9nZ2xlIiwidGhpc19kYXRlIiwicGFyZW50cyIsInRvZ2dsZSIsIndwYmNfYWp4X2Jvb2tpbmdfX3VpX2RlZmluZV9fbG9jYWxlIiwiZWFjaCIsImluZGV4Iiwic2VsZWN0aW9uIiwicHJvcCIsImhhc0NsYXNzIiwiYm9va2luZ19sb2NhbGVfYnV0dG9uIiwid3BiY190aXBweSIsIl90aXBweSIsInNldENvbnRlbnQiLCJ3cGJjX2FqeF9ib29raW5nX191aV9kZWZpbmVfX3JlbWFyayIsInRleHRfdmFsIiwidmFsIiwicmVtYXJrX2J1dHRvbiIsInNldFByb3BzIiwiYWxsb3dIVE1MIiwiY29udGVudCIsIndwYmNfYWp4X2Jvb2tpbmdfX3VpX2NsaWNrX19yZW1hcmsiLCJqcV9idXR0b24iLCJ3cGJjX2FqeF9ib29raW5nX191aV9jbGlja19zaG93X19jaGFuZ2VfcmVzb3VyY2UiLCJib29raW5nX2lkIiwicmVzb3VyY2VfaWQiLCJ0cmlnZ2VyIiwiY2JyIiwiZGV0YWNoIiwiYXBwZW5kVG8iLCJpcyIsIndwYmNfYWp4X2Jvb2tpbmdfX3VpX2NsaWNrX3NhdmVfX2NoYW5nZV9yZXNvdXJjZSIsInRoaXNfZWwiLCJib29raW5nX2FjdGlvbiIsImVsX2lkIiwid3BiY19hanhfYm9va2luZ19fdWlfY2xpY2tfY2xvc2VfX2NoYW5nZV9yZXNvdXJjZSIsImNicmNlIiwid3BiY19hanhfYm9va2luZ19fdWlfY2xpY2tfc2hvd19fZHVwbGljYXRlX2Jvb2tpbmciLCJ3cGJjX2FqeF9ib29raW5nX191aV9jbGlja19zYXZlX19kdXBsaWNhdGVfYm9va2luZyIsIndwYmNfYWp4X2Jvb2tpbmdfX3VpX2NsaWNrX2Nsb3NlX19kdXBsaWNhdGVfYm9va2luZyIsIndwYmNfYWp4X2Jvb2tpbmdfX3VpX2NsaWNrX3Nob3dfX3NldF9wYXltZW50X3N0YXR1cyIsImpTZWxlY3QiLCJzZWxlY3RlZF9wYXlfc3RhdHVzIiwiaXNOYU4iLCJwYXJzZUZsb2F0Iiwid3BiY19hanhfYm9va2luZ19fdWlfY2xpY2tfc2F2ZV9fc2V0X3BheW1lbnRfc3RhdHVzIiwid3BiY19hanhfYm9va2luZ19fdWlfY2xpY2tfY2xvc2VfX3NldF9wYXltZW50X3N0YXR1cyIsIndwYmNfYWp4X2Jvb2tpbmdfX3VpX2NsaWNrX3NhdmVfX3NldF9ib29raW5nX2Nvc3QiLCJ3cGJjX2FqeF9ib29raW5nX191aV9jbGlja19jbG9zZV9fc2V0X2Jvb2tpbmdfY29zdCIsIndwYmNfYWp4X2Jvb2tpbmdfX3VpX2NsaWNrX19zZW5kX3BheW1lbnRfcmVxdWVzdCIsIndwYmNfYWp4X2Jvb2tpbmdfX3VpX2NsaWNrX19pbXBvcnRfZ29vZ2xlX2NhbGVuZGFyIiwid3BiY19hanhfYm9va2luZ19fdWlfY2xpY2tfX2V4cG9ydF9jc3YiLCJwYXJhbXMiLCJzZWxlY3RlZF9ib29raW5nX2lkX2FyciIsIndwYmNfZ2V0X3NlbGVjdGVkX3Jvd19pZCIsImpvaW4iLCJz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LEVBQUFBLG9DQUFvQyxDQUFFO0FBQzVCLHNCQUEwQjJILE1BQU0sQ0FBRSxnQkFBRixDQURKO0FBRTVCLDZCQUEwQkEsTUFBTSxDQUFFLHVCQUFGLENBRko7QUFJNUIsbUJBQTBCQSxNQUFNLENBQUUsYUFBRixDQUpKO0FBSzVCLDRCQUEwQkEsTUFBTSxDQUFFLHNCQUFGLENBTEo7QUFNNUIsOEJBQTBCQSxNQUFNLENBQUUsd0JBQUYsQ0FOSjtBQVE1QixrQkFBZUMsdUJBQXVCLENBQUNFLElBQXhCLENBQTZCLEdBQTdCLENBUmE7QUFTNUIscUJBQWtCcEgsd0JBQXdCLENBQUNxSCxxQkFBekI7QUFUVSxHQUFGLENBQXBDO0FBWUEsTUFBSXhCLE9BQU8sR0FBR3BGLE1BQU0sQ0FBRSxNQUFNd0csTUFBTSxDQUFFLHVCQUFGLENBQWQsQ0FBTixDQUFrRGxFLEdBQWxELENBQXVELENBQXZELENBQWQ7QUFFQUMsRUFBQUEsK0JBQStCLENBQUU2QyxPQUFGLENBQS9CO0FBQ0E7QUFFRDtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7QUFDQSxTQUFTN0QsMENBQVQsQ0FBcURzRixjQUFyRCxFQUFxRTtBQUVwRTtBQUVBMUYsRUFBQUEsUUFBUSxDQUFDQyxRQUFULENBQWtCQyxJQUFsQixHQUF5QndGLGNBQXpCLENBSm9FLENBSTVCO0FBRXhDO0FBQ0E7QUFDQSIsInNvdXJjZXNDb250ZW50IjpbIlwidXNlIHN0cmljdFwiO1xyXG5cclxuLyoqXHJcbiAqICAgQWpheCAgIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tICovXHJcbi8vdmFyIGlzX3RoaXNfYWN0aW9uID0gZmFsc2U7XHJcbi8qKlxyXG4gKiBTZW5kIEFqYXggYWN0aW9uIHJlcXVlc3QsICBsaWtlIGFwcHJvdmluZyBvciBjYW5jZWxsYXRpb25cclxuICpcclxuICogQHBhcmFtIGFjdGlvbl9wYXJhbVxyXG4gKi9cclxuZnVuY3Rpb24gd3BiY19hanhfYm9va2luZ19hamF4X2FjdGlvbl9yZXF1ZXN0KCBhY3Rpb25fcGFyYW0gPSB7fSApe1xyXG5cclxuY29uc29sZS5ncm91cENvbGxhcHNlZCggJ1dQQkNfQUpYX0JPT0tJTkdfQUNUSU9OUycgKTsgY29uc29sZS5sb2coICcgPT0gQWpheCBBY3Rpb25zIDo6IFBhcmFtcyA9PSAnLCBhY3Rpb25fcGFyYW0gKTtcclxuLy9pc190aGlzX2FjdGlvbiA9IHRydWU7XHJcblxyXG5cdHdwYmNfYm9va2luZ19saXN0aW5nX3JlbG9hZF9idXR0b25fX3NwaW5fc3RhcnQoKTtcclxuXHJcblx0Ly8gR2V0IHJlZGVmaW5lZCBMb2NhbGUsICBpZiBhY3Rpb24gb24gc2luZ2xlIGJvb2tpbmcgIVxyXG5cdGlmICggICggdW5kZWZpbmVkICE9IGFjdGlvbl9wYXJhbVsgJ2Jvb2tpbmdfaWQnIF0gKSAmJiAoICEgQXJyYXkuaXNBcnJheSggYWN0aW9uX3BhcmFtWyAnYm9va2luZ19pZCcgXSApICkgKXtcdFx0XHRcdC8vIE5vdCBhcnJheVxyXG5cclxuXHRcdGFjdGlvbl9wYXJhbVsgJ2xvY2FsZScgXSA9IHdwYmNfZ2V0X3NlbGVjdGVkX2xvY2FsZSggYWN0aW9uX3BhcmFtWyAnYm9va2luZ19pZCcgXSwgd3BiY19hanhfYm9va2luZ19saXN0aW5nLmdldF9zZWN1cmVfcGFyYW0oICdsb2NhbGUnICkgKTtcclxuXHR9XHJcblxyXG5cdHZhciBhY3Rpb25fcG9zdF9wYXJhbXMgPSB7XHJcblx0XHRcdFx0XHRcdFx0XHRhY3Rpb24gICAgICAgICAgOiAnV1BCQ19BSlhfQk9PS0lOR19BQ1RJT05TJyxcclxuXHRcdFx0XHRcdFx0XHRcdG5vbmNlICAgICAgICAgICA6IHdwYmNfYWp4X2Jvb2tpbmdfbGlzdGluZy5nZXRfc2VjdXJlX3BhcmFtKCAnbm9uY2UnICksXHJcblx0XHRcdFx0XHRcdFx0XHR3cGJjX2FqeF91c2VyX2lkOiAoICggdW5kZWZpbmVkID09IGFjdGlvbl9wYXJhbVsgJ3VzZXJfaWQnIF0gKSA/IHdwYmNfYWp4X2Jvb2tpbmdfbGlzdGluZy5nZXRfc2VjdXJlX3BhcmFtKCAndXNlcl9pZCcgKSA6IGFjdGlvbl9wYXJhbVsgJ3VzZXJfaWQnIF0gKSxcclxuXHRcdFx0XHRcdFx0XHRcdHdwYmNfYWp4X2xvY2FsZTogICggKCB1bmRlZmluZWQgPT0gYWN0aW9uX3BhcmFtWyAnbG9jYWxlJyBdICkgID8gd3BiY19hanhfYm9va2luZ19saXN0aW5nLmdldF9zZWN1cmVfcGFyYW0oICdsb2NhbGUnICkgIDogYWN0aW9uX3BhcmFtWyAnbG9jYWxlJyBdICksXHJcblxyXG5cdFx0XHRcdFx0XHRcdFx0YWN0aW9uX3BhcmFtc1x0OiBhY3Rpb25fcGFyYW1cclxuXHRcdFx0XHRcdFx0XHR9O1xyXG5cclxuXHQvLyBJdCdzIHJlcXVpcmVkIGZvciBDU1YgZXhwb3J0IC0gZ2V0dGluZyB0aGUgc2FtZSBsaXN0ICBvZiBib29raW5nc1xyXG5cdGlmICggdHlwZW9mIGFjdGlvbl9wYXJhbS5zZWFyY2hfcGFyYW1zICE9PSAndW5kZWZpbmVkJyApe1xyXG5cdFx0YWN0aW9uX3Bvc3RfcGFyYW1zWyAnc2VhcmNoX3BhcmFtcycgXSA9IGFjdGlvbl9wYXJhbS5zZWFyY2hfcGFyYW1zO1xyXG5cdFx0ZGVsZXRlIGFjdGlvbl9wb3N0X3BhcmFtcy5hY3Rpb25fcGFyYW1zLnNlYXJjaF9wYXJhbXM7XHJcblx0fVxyXG5cclxuXHQvLyBTdGFydCBBamF4XHJcblx0alF1ZXJ5LnBvc3QoIHdwYmNfZ2xvYmFsMS53cGJjX2FqYXh1cmwgLFxyXG5cclxuXHRcdFx0XHRhY3Rpb25fcG9zdF9wYXJhbXMgLFxyXG5cclxuXHRcdFx0XHQvKipcclxuXHRcdFx0XHQgKiBTIHUgYyBjIGUgcyBzXHJcblx0XHRcdFx0ICpcclxuXHRcdFx0XHQgKiBAcGFyYW0gcmVzcG9uc2VfZGF0YVx0XHQtXHRpdHMgb2JqZWN0IHJldHVybmVkIGZyb20gIEFqYXggLSBjbGFzcy1saXZlLXNlYXJjZy5waHBcclxuXHRcdFx0XHQgKiBAcGFyYW0gdGV4dFN0YXR1c1x0XHQtXHQnc3VjY2VzcydcclxuXHRcdFx0XHQgKiBAcGFyYW0ganFYSFJcdFx0XHRcdC1cdE9iamVjdFxyXG5cdFx0XHRcdCAqL1xyXG5cdFx0XHRcdGZ1bmN0aW9uICggcmVzcG9uc2VfZGF0YSwgdGV4dFN0YXR1cywganFYSFIgKSB7XHJcblxyXG5jb25zb2xlLmxvZyggJyA9PSBBamF4IEFjdGlvbnMgOjogUmVzcG9uc2UgV1BCQ19BSlhfQk9PS0lOR19BQ1RJT05TID09ICcsIHJlc3BvbnNlX2RhdGEgKTsgY29uc29sZS5ncm91cEVuZCgpO1xyXG5cclxuXHRcdFx0XHRcdC8vIFByb2JhYmx5IEVycm9yXHJcblx0XHRcdFx0XHRpZiAoICh0eXBlb2YgcmVzcG9uc2VfZGF0YSAhPT0gJ29iamVjdCcpIHx8IChyZXNwb25zZV9kYXRhID09PSBudWxsKSApe1xyXG5cdFx0XHRcdFx0XHRqUXVlcnkoICcjd2hfc29ydF9zZWxlY3RvcicgKS5oaWRlKCk7XHJcblx0XHRcdFx0XHRcdGpRdWVyeSggd3BiY19hanhfYm9va2luZ19saXN0aW5nLmdldF9vdGhlcl9wYXJhbSggJ2xpc3RpbmdfY29udGFpbmVyJyApICkuaHRtbChcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCc8ZGl2IGNsYXNzPVwid3BiYy1zZXR0aW5ncy1ub3RpY2Ugbm90aWNlLXdhcm5pbmdcIiBzdHlsZT1cInRleHQtYWxpZ246bGVmdFwiPicgK1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRyZXNwb25zZV9kYXRhICtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCc8L2Rpdj4nXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCk7XHJcblx0XHRcdFx0XHRcdHJldHVybjtcclxuXHRcdFx0XHRcdH1cclxuXHJcblx0XHRcdFx0XHR3cGJjX2Jvb2tpbmdfbGlzdGluZ19yZWxvYWRfYnV0dG9uX19zcGluX3BhdXNlKCk7XHJcblxyXG5cdFx0XHRcdFx0d3BiY19hZG1pbl9zaG93X21lc3NhZ2UoXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAgcmVzcG9uc2VfZGF0YVsgJ2FqeF9hZnRlcl9hY3Rpb25fbWVzc2FnZScgXS5yZXBsYWNlKCAvXFxuL2csIFwiPGJyIC8+XCIgKVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQsICggJzEnID09IHJlc3BvbnNlX2RhdGFbICdhanhfYWZ0ZXJfYWN0aW9uX3Jlc3VsdCcgXSApID8gJ3N1Y2Nlc3MnIDogJ2Vycm9yJ1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQsIDEwMDAwXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQpO1xyXG5cclxuXHRcdFx0XHRcdC8vIFN1Y2Nlc3MgcmVzcG9uc2VcclxuXHRcdFx0XHRcdGlmICggJzEnID09IHJlc3BvbnNlX2RhdGFbICdhanhfYWZ0ZXJfYWN0aW9uX3Jlc3VsdCcgXSApe1xyXG5cclxuXHRcdFx0XHRcdFx0dmFyIGlzX3JlbG9hZF9hamF4X2xpc3RpbmcgPSB0cnVlO1xyXG5cclxuXHRcdFx0XHRcdFx0Ly8gQWZ0ZXIgR29vZ2xlIENhbGVuZGFyIGltcG9ydCBzaG93IGltcG9ydGVkIGJvb2tpbmdzIGFuZCByZWxvYWQgdGhlIHBhZ2UgZm9yIHRvb2xiYXIgcGFyYW1ldGVycyB1cGRhdGVcclxuXHRcdFx0XHRcdFx0aWYgKCBmYWxzZSAhPT0gcmVzcG9uc2VfZGF0YVsgJ2FqeF9hZnRlcl9hY3Rpb25fcmVzdWx0X2FsbF9wYXJhbXNfYXJyJyBdWyAnbmV3X2xpc3RpbmdfcGFyYW1zJyBdICl7XHJcblxyXG5cdFx0XHRcdFx0XHRcdHdwYmNfYWp4X2Jvb2tpbmdfc2VuZF9zZWFyY2hfcmVxdWVzdF93aXRoX3BhcmFtcyggcmVzcG9uc2VfZGF0YVsgJ2FqeF9hZnRlcl9hY3Rpb25fcmVzdWx0X2FsbF9wYXJhbXNfYXJyJyBdWyAnbmV3X2xpc3RpbmdfcGFyYW1zJyBdICk7XHJcblxyXG5cdFx0XHRcdFx0XHRcdHZhciBjbG9zZWRfdGltZXIgPSBzZXRUaW1lb3V0KCBmdW5jdGlvbiAoKXtcclxuXHJcblx0XHRcdFx0XHRcdFx0XHRcdGlmICggd3BiY19ib29raW5nX2xpc3RpbmdfcmVsb2FkX2J1dHRvbl9faXNfc3BpbigpICl7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0aWYgKCB1bmRlZmluZWQgIT0gcmVzcG9uc2VfZGF0YVsgJ2FqeF9hZnRlcl9hY3Rpb25fcmVzdWx0X2FsbF9wYXJhbXNfYXJyJyBdWyAnbmV3X2xpc3RpbmdfcGFyYW1zJyBdWyAncmVsb2FkX3VybF9wYXJhbXMnIF0gKXtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdGRvY3VtZW50LmxvY2F0aW9uLmhyZWYgPSByZXNwb25zZV9kYXRhWyAnYWp4X2FmdGVyX2FjdGlvbl9yZXN1bHRfYWxsX3BhcmFtc19hcnInIF1bICduZXdfbGlzdGluZ19wYXJhbXMnIF1bICdyZWxvYWRfdXJsX3BhcmFtcycgXTtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHR9IGVsc2Uge1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0ZG9jdW1lbnQubG9jYXRpb24ucmVsb2FkKCk7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0fVxyXG5cdFx0XHRcdFx0XHRcdFx0XHR9XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0fVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCwgMjAwMCApO1xyXG5cdFx0XHRcdFx0XHRcdGlzX3JlbG9hZF9hamF4X2xpc3RpbmcgPSBmYWxzZTtcclxuXHRcdFx0XHRcdFx0fVxyXG5cclxuXHRcdFx0XHRcdFx0Ly8gU3RhcnQgZG93bmxvYWQgZXhwb3J0ZWQgQ1NWIGZpbGVcclxuXHRcdFx0XHRcdFx0aWYgKCB1bmRlZmluZWQgIT0gcmVzcG9uc2VfZGF0YVsgJ2FqeF9hZnRlcl9hY3Rpb25fcmVzdWx0X2FsbF9wYXJhbXNfYXJyJyBdWyAnZXhwb3J0X2Nzdl91cmwnIF0gKXtcclxuXHRcdFx0XHRcdFx0XHR3cGJjX2FqeF9ib29raW5nX19leHBvcnRfY3N2X3VybF9fZG93bmxvYWQoIHJlc3BvbnNlX2RhdGFbICdhanhfYWZ0ZXJfYWN0aW9uX3Jlc3VsdF9hbGxfcGFyYW1zX2FycicgXVsgJ2V4cG9ydF9jc3ZfdXJsJyBdICk7XHJcblx0XHRcdFx0XHRcdFx0aXNfcmVsb2FkX2FqYXhfbGlzdGluZyA9IGZhbHNlO1xyXG5cdFx0XHRcdFx0XHR9XHJcblxyXG5cdFx0XHRcdFx0XHRpZiAoIGlzX3JlbG9hZF9hamF4X2xpc3RpbmcgKXtcclxuXHRcdFx0XHRcdFx0XHR3cGJjX2FqeF9ib29raW5nX19hY3R1YWxfbGlzdGluZ19fc2hvdygpO1x0Ly9cdFNlbmRpbmcgQWpheCBSZXF1ZXN0XHQtXHR3aXRoIHBhcmFtZXRlcnMgdGhhdCAgd2UgZWFybHkgIGRlZmluZWQgaW4gXCJ3cGJjX2FqeF9ib29raW5nX2xpc3RpbmdcIiBPYmouXHJcblx0XHRcdFx0XHRcdH1cclxuXHJcblx0XHRcdFx0XHR9XHJcblxyXG5cdFx0XHRcdFx0Ly8gUmVtb3ZlIHNwaW4gaWNvbiBmcm9tICBidXR0b24gYW5kIEVuYWJsZSB0aGlzIGJ1dHRvbi5cclxuXHRcdFx0XHRcdHdwYmNfYnV0dG9uX19yZW1vdmVfc3BpbiggcmVzcG9uc2VfZGF0YVsgJ2FqeF9jbGVhbmVkX3BhcmFtcycgXVsgJ3VpX2NsaWNrZWRfZWxlbWVudF9pZCcgXSApXHJcblxyXG5cdFx0XHRcdFx0Ly8gSGlkZSBtb2RhbHNcclxuXHRcdFx0XHRcdHdwYmNfcG9wdXBfbW9kYWxzX19oaWRlKCk7XHJcblxyXG5cdFx0XHRcdFx0alF1ZXJ5KCAnI2FqYXhfcmVzcG9uZCcgKS5odG1sKCByZXNwb25zZV9kYXRhICk7XHRcdC8vIEZvciBhYmlsaXR5IHRvIHNob3cgcmVzcG9uc2UsIGFkZCBzdWNoIERJViBlbGVtZW50IHRvIHBhZ2VcclxuXHRcdFx0XHR9XHJcblx0XHRcdCAgKS5mYWlsKCBmdW5jdGlvbiAoIGpxWEhSLCB0ZXh0U3RhdHVzLCBlcnJvclRocm93biApIHsgICAgaWYgKCB3aW5kb3cuY29uc29sZSAmJiB3aW5kb3cuY29uc29sZS5sb2cgKXsgY29uc29sZS5sb2coICdBamF4X0Vycm9yJywganFYSFIsIHRleHRTdGF0dXMsIGVycm9yVGhyb3duICk7IH1cclxuXHRcdFx0XHRcdGpRdWVyeSggJyN3aF9zb3J0X3NlbGVjdG9yJyApLmhpZGUoKTtcclxuXHRcdFx0XHRcdHZhciBlcnJvcl9tZXNzYWdlID0gJzxzdHJvbmc+JyArICdFcnJvciEnICsgJzwvc3Ryb25nPiAnICsgZXJyb3JUaHJvd24gO1xyXG5cdFx0XHRcdFx0aWYgKCBqcVhIUi5yZXNwb25zZVRleHQgKXtcclxuXHRcdFx0XHRcdFx0ZXJyb3JfbWVzc2FnZSArPSBqcVhIUi5yZXNwb25zZVRleHQ7XHJcblx0XHRcdFx0XHR9XHJcblx0XHRcdFx0XHRlcnJvcl9tZXNzYWdlID0gZXJyb3JfbWVzc2FnZS5yZXBsYWNlKCAvXFxuL2csIFwiPGJyIC8+XCIgKTtcclxuXHJcblx0XHRcdFx0XHR3cGJjX2FqeF9ib29raW5nX3Nob3dfbWVzc2FnZSggZXJyb3JfbWVzc2FnZSApO1xyXG5cdFx0XHQgIH0pXHJcblx0ICAgICAgICAgIC8vIC5kb25lKCAgIGZ1bmN0aW9uICggZGF0YSwgdGV4dFN0YXR1cywganFYSFIgKSB7ICAgaWYgKCB3aW5kb3cuY29uc29sZSAmJiB3aW5kb3cuY29uc29sZS5sb2cgKXsgY29uc29sZS5sb2coICdzZWNvbmQgc3VjY2VzcycsIGRhdGEsIHRleHRTdGF0dXMsIGpxWEhSICk7IH0gICAgfSlcclxuXHRcdFx0ICAvLyAuYWx3YXlzKCBmdW5jdGlvbiAoIGRhdGFfanFYSFIsIHRleHRTdGF0dXMsIGpxWEhSX2Vycm9yVGhyb3duICkgeyAgIGlmICggd2luZG93LmNvbnNvbGUgJiYgd2luZG93LmNvbnNvbGUubG9nICl7IGNvbnNvbGUubG9nKCAnYWx3YXlzIGZpbmlzaGVkJywgZGF0YV9qcVhIUiwgdGV4dFN0YXR1cywganFYSFJfZXJyb3JUaHJvd24gKTsgfSAgICAgfSlcclxuXHRcdFx0ICA7ICAvLyBFbmQgQWpheFxyXG59XHJcblxyXG5cclxuLyoqXHJcbiAqICAgU3VwcG9ydCBGdW5jdGlvbnMgLSBTcGluIEljb24gaW4gQnV0dG9ucyAgLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tICovXHJcblxyXG4vKipcclxuICogUmVtb3ZlIHNwaW4gaWNvbiBmcm9tICBidXR0b24gYW5kIEVuYWJsZSB0aGlzIGJ1dHRvbi5cclxuICpcclxuICogQHBhcmFtIGJ1dHRvbl9jbGlja2VkX2VsZW1lbnRfaWRcdFx0LSBIVE1MIElEIGF0dHJpYnV0ZSBvZiB0aGlzIGJ1dHRvblxyXG4gKiBAcmV0dXJuIHN0cmluZ1x0XHRcdFx0XHRcdC0gQ1NTIGNsYXNzZXMgdGhhdCB3YXMgcHJldmlvdXNseSBpbiBidXR0b24gaWNvblxyXG4gKi9cclxuZnVuY3Rpb24gd3BiY19idXR0b25fX3JlbW92ZV9zcGluKCBidXR0b25fY2xpY2tlZF9lbGVtZW50X2lkICl7XHJcblxyXG5cdHZhciBwcmV2aW9zX2NsYXNzZXMgPSAnJztcclxuXHRpZiAoIHVuZGVmaW5lZCAhPSBidXR0b25fY2xpY2tlZF9lbGVtZW50X2lkICl7XHJcblx0XHR2YXIgakVsZW1lbnQgPSBqUXVlcnkoICcjJyArIGJ1dHRvbl9jbGlja2VkX2VsZW1lbnRfaWQgKTtcclxuXHRcdGlmICggakVsZW1lbnQubGVuZ3RoICl7XHJcblx0XHRcdHByZXZpb3NfY2xhc3NlcyA9IHdwYmNfYnV0dG9uX2Rpc2FibGVfbG9hZGluZ19pY29uKCBqRWxlbWVudC5nZXQoIDAgKSApO1xyXG5cdFx0fVxyXG5cdH1cclxuXHJcblx0cmV0dXJuIHByZXZpb3NfY2xhc3NlcztcclxufVxyXG5cclxuXHJcblx0LyoqXHJcblx0ICogU2hvdyBMb2FkaW5nIChyb3RhdGluZyBhcnJvdykgaWNvbiBmb3IgYnV0dG9uIHRoYXQgaGFzIGJlZW4gY2xpY2tlZFxyXG5cdCAqXHJcblx0ICogQHBhcmFtIHRoaXNfYnV0dG9uXHRcdC0gdGhpcyBvYmplY3Qgb2Ygc3BlY2lmaWMgYnV0dG9uXHJcblx0ICogQHJldHVybiBzdHJpbmdcdFx0XHQtIENTUyBjbGFzc2VzIHRoYXQgd2FzIHByZXZpb3VzbHkgaW4gYnV0dG9uIGljb25cclxuXHQgKi9cclxuXHRmdW5jdGlvbiB3cGJjX2J1dHRvbl9lbmFibGVfbG9hZGluZ19pY29uKCB0aGlzX2J1dHRvbiApe1xyXG5cclxuXHRcdHZhciBqQnV0dG9uID0galF1ZXJ5KCB0aGlzX2J1dHRvbiApO1xyXG5cdFx0dmFyIGpJY29uID0gakJ1dHRvbi5maW5kKCAnaScgKTtcclxuXHRcdHZhciBwcmV2aW9zX2NsYXNzZXMgPSBqSWNvbi5hdHRyKCAnY2xhc3MnICk7XHJcblxyXG5cdFx0akljb24ucmVtb3ZlQ2xhc3MoKS5hZGRDbGFzcyggJ21lbnVfaWNvbiBpY29uLTF4IHdwYmNfaWNuX3JvdGF0ZV9yaWdodCB3cGJjX3NwaW4nICk7XHQvLyBTZXQgUm90YXRlIGljb25cclxuXHRcdC8vakljb24uYWRkQ2xhc3MoICd3cGJjX2FuaW1hdGlvbl9wYXVzZScgKTtcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQvLyBQYXVzZSBhbmltYXRpb25cclxuXHRcdC8vakljb24uYWRkQ2xhc3MoICd3cGJjX3VpX3JlZCcgKTtcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0Ly8gU2V0IGljb24gY29sb3IgcmVkXHJcblxyXG5cdFx0akljb24uYXR0ciggJ3dwYmNfcHJldmlvdXNfY2xhc3MnLCBwcmV2aW9zX2NsYXNzZXMgKVxyXG5cclxuXHRcdGpCdXR0b24uYWRkQ2xhc3MoICdkaXNhYmxlZCcgKTtcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQvLyBEaXNhYmxlIGJ1dHRvblxyXG5cdFx0Ly9qQnV0dG9uLnByb3AoIFwiZGlzYWJsZWRcIiwgdHJ1ZSApO1xyXG5cdFx0Ly8gV2UgbmVlZCB0byAgc2V0ICBoZXJlIGF0dHIgaW5zdGVhZCBvZiBwcm9wLCBiZWNhdXNlIGZvciBBIGVsZW1lbnRzLCAgYXR0cmlidXRlICdkaXNhYmxlZCcgZG8gIG5vdCBhZGRlZCB3aXRoIGpCdXR0b24ucHJvcCggXCJkaXNhYmxlZFwiLCB0cnVlICk7XHJcblxyXG5cdFx0akJ1dHRvbi5hdHRyKCAnd3BiY19wcmV2aW91c19vbmNsaWNrJywgakJ1dHRvbi5hdHRyKCAnb25jbGljaycgKSApO1x0XHQvL1NhdmUgdGhpcyB2YWx1ZVxyXG5cdFx0akJ1dHRvbi5hdHRyKCAnb25jbGljaycsICcnICk7XHRcdFx0XHRcdFx0XHRcdFx0XHRcdC8vIERpc2FibGUgYWN0aW9ucyBcIm9uIGNsaWNrXCJcclxuXHJcblx0XHRyZXR1cm4gcHJldmlvc19jbGFzc2VzO1xyXG5cdH1cclxuXHJcblxyXG5cdC8qKlxyXG5cdCAqIEhpZGUgTG9hZGluZyAocm90YXRpbmcgYXJyb3cpIGljb24gZm9yIGJ1dHRvbiB0aGF0IHdhcyBjbGlja2VkIGFuZCBzaG93IHByZXZpb3VzIGljb24gYW5kIGVuYWJsZSBidXR0b25cclxuXHQgKlxyXG5cdCAqIEBwYXJhbSB0aGlzX2J1dHRvblx0XHQtIHRoaXMgb2JqZWN0IG9mIHNwZWNpZmljIGJ1dHRvblxyXG5cdCAqIEByZXR1cm4gc3RyaW5nXHRcdFx0LSBDU1MgY2xhc3NlcyB0aGF0IHdhcyBwcmV2aW91c2x5IGluIGJ1dHRvbiBpY29uXHJcblx0ICovXHJcblx0ZnVuY3Rpb24gd3BiY19idXR0b25fZGlzYWJsZV9sb2FkaW5nX2ljb24oIHRoaXNfYnV0dG9uICl7XHJcblxyXG5cdFx0dmFyIGpCdXR0b24gPSBqUXVlcnkoIHRoaXNfYnV0dG9uICk7XHJcblx0XHR2YXIgakljb24gPSBqQnV0dG9uLmZpbmQoICdpJyApO1xyXG5cclxuXHRcdHZhciBwcmV2aW9zX2NsYXNzZXMgPSBqSWNvbi5hdHRyKCAnd3BiY19wcmV2aW91c19jbGFzcycgKTtcclxuXHRcdGlmICggJycgIT0gcHJldmlvc19jbGFzc2VzICl7XHJcblx0XHRcdGpJY29uLnJlbW92ZUNsYXNzKCkuYWRkQ2xhc3MoIHByZXZpb3NfY2xhc3NlcyApO1xyXG5cdFx0fVxyXG5cclxuXHRcdGpCdXR0b24ucmVtb3ZlQ2xhc3MoICdkaXNhYmxlZCcgKTtcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQvLyBSZW1vdmUgRGlzYWJsZSBidXR0b25cclxuXHJcblx0XHR2YXIgcHJldmlvdXNfb25jbGljayA9IGpCdXR0b24uYXR0ciggJ3dwYmNfcHJldmlvdXNfb25jbGljaycgKVxyXG5cdFx0aWYgKCAnJyAhPSBwcmV2aW91c19vbmNsaWNrICl7XHJcblx0XHRcdGpCdXR0b24uYXR0ciggJ29uY2xpY2snLCBwcmV2aW91c19vbmNsaWNrICk7XHJcblx0XHR9XHJcblxyXG5cdFx0cmV0dXJuIHByZXZpb3NfY2xhc3NlcztcclxuXHR9XHJcblxyXG5cclxuLyoqXHJcbiAqIEhpZGUgYWxsIG9wZW4gbW9kYWwgcG9wdXBzIHdpbmRvd3NcclxuICovXHJcbmZ1bmN0aW9uIHdwYmNfcG9wdXBfbW9kYWxzX19oaWRlKCl7XHJcblxyXG5cdC8vIEhpZGUgbW9kYWxzXHJcblx0aWYgKCAnZnVuY3Rpb24nID09PSB0eXBlb2YgKGpRdWVyeSggJy53cGJjX3BvcHVwX21vZGFsJyApLndwYmNfbXlfbW9kYWwpICl7XHJcblx0XHRqUXVlcnkoICcud3BiY19wb3B1cF9tb2RhbCcgKS53cGJjX215X21vZGFsKCAnaGlkZScgKTtcclxuXHR9XHJcbn1cclxuXHJcblxyXG4vKipcclxuICogICBEYXRlcyAgU2hvcnQgPC0+IFdpZGUgICAgLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0gKi9cclxuXHJcbmZ1bmN0aW9uIHdwYmNfYWp4X2NsaWNrX29uX2RhdGVzX3Nob3J0KCl7XHJcblx0alF1ZXJ5KCAnI2Jvb2tpbmdfZGF0ZXNfc21hbGwsLmJvb2tpbmdfZGF0ZXNfZnVsbCcgKS5oaWRlKCk7XHJcblx0alF1ZXJ5KCAnI2Jvb2tpbmdfZGF0ZXNfZnVsbCwuYm9va2luZ19kYXRlc19zbWFsbCcgKS5zaG93KCk7XHJcblx0d3BiY19hanhfYm9va2luZ19zZW5kX3NlYXJjaF9yZXF1ZXN0X3dpdGhfcGFyYW1zKCB7J3VpX3Vzcl9fZGF0ZXNfc2hvcnRfd2lkZSc6ICdzaG9ydCd9ICk7XHJcbn1cclxuXHJcbmZ1bmN0aW9uIHdwYmNfYWp4X2NsaWNrX29uX2RhdGVzX3dpZGUoKXtcclxuXHRqUXVlcnkoICcjYm9va2luZ19kYXRlc19mdWxsLC5ib29raW5nX2RhdGVzX3NtYWxsJyApLmhpZGUoKTtcclxuXHRqUXVlcnkoICcjYm9va2luZ19kYXRlc19zbWFsbCwuYm9va2luZ19kYXRlc19mdWxsJyApLnNob3coKTtcclxuXHR3cGJjX2FqeF9ib29raW5nX3NlbmRfc2VhcmNoX3JlcXVlc3Rfd2l0aF9wYXJhbXMoIHsndWlfdXNyX19kYXRlc19zaG9ydF93aWRlJzogJ3dpZGUnfSApO1xyXG59XHJcblxyXG5mdW5jdGlvbiB3cGJjX2FqeF9jbGlja19vbl9kYXRlc190b2dnbGUodGhpc19kYXRlKXtcclxuXHJcblx0alF1ZXJ5KCB0aGlzX2RhdGUgKS5wYXJlbnRzKCAnLndwYmNfY29sX2RhdGVzJyApLmZpbmQoICcuYm9va2luZ19kYXRlc19zbWFsbCcgKS50b2dnbGUoKTtcclxuXHRqUXVlcnkoIHRoaXNfZGF0ZSApLnBhcmVudHMoICcud3BiY19jb2xfZGF0ZXMnICkuZmluZCggJy5ib29raW5nX2RhdGVzX2Z1bGwnICkudG9nZ2xlKCk7XHJcblxyXG5cdC8qXHJcblx0dmFyIHZpc2libGVfc2VjdGlvbiA9IGpRdWVyeSggdGhpc19kYXRlICkucGFyZW50cyggJy5ib29raW5nX2RhdGVzX2V4cGFuZF9zZWN0aW9uJyApO1xyXG5cdHZpc2libGVfc2VjdGlvbi5oaWRlKCk7XHJcblx0aWYgKCB2aXNpYmxlX3NlY3Rpb24uaGFzQ2xhc3MoICdib29raW5nX2RhdGVzX2Z1bGwnICkgKXtcclxuXHRcdHZpc2libGVfc2VjdGlvbi5wYXJlbnRzKCAnLndwYmNfY29sX2RhdGVzJyApLmZpbmQoICcuYm9va2luZ19kYXRlc19zbWFsbCcgKS5zaG93KCk7XHJcblx0fSBlbHNlIHtcclxuXHRcdHZpc2libGVfc2VjdGlvbi5wYXJlbnRzKCAnLndwYmNfY29sX2RhdGVzJyApLmZpbmQoICcuYm9va2luZ19kYXRlc19mdWxsJyApLnNob3coKTtcclxuXHR9Ki9cclxuXHRjb25zb2xlLmxvZyggJ3dwYmNfYWp4X2NsaWNrX29uX2RhdGVzX3RvZ2dsZScsIHRoaXNfZGF0ZSApO1xyXG59XHJcblxyXG4vKipcclxuICogICBMb2NhbGUgICAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0gKi9cclxuXHJcbi8qKlxyXG4gKiBcdFNlbGVjdCBvcHRpb25zIGluIHNlbGVjdCBib3hlcyBiYXNlZCBvbiBhdHRyaWJ1dGUgXCJ2YWx1ZV9vZl9zZWxlY3RlZF9vcHRpb25cIiBhbmQgUkVEIGNvbG9yIGFuZCBoaW50IGZvciBMT0NBTEUgYnV0dG9uICAgLS0gIEl0J3MgY2FsbGVkIGZyb20gXHR3cGJjX2FqeF9ib29raW5nX2RlZmluZV91aV9ob29rcygpICBcdGVhY2ggIHRpbWUgYWZ0ZXIgTGlzdGluZyBsb2FkaW5nLlxyXG4gKi9cclxuZnVuY3Rpb24gd3BiY19hanhfYm9va2luZ19fdWlfZGVmaW5lX19sb2NhbGUoKXtcclxuXHJcblx0alF1ZXJ5KCAnLndwYmNfbGlzdGluZ19jb250YWluZXIgc2VsZWN0JyApLmVhY2goIGZ1bmN0aW9uICggaW5kZXggKXtcclxuXHJcblx0XHR2YXIgc2VsZWN0aW9uID0galF1ZXJ5KCB0aGlzICkuYXR0ciggXCJ2YWx1ZV9vZl9zZWxlY3RlZF9vcHRpb25cIiApO1x0XHRcdC8vIERlZmluZSBzZWxlY3RlZCBzZWxlY3QgYm94ZXNcclxuXHJcblx0XHRpZiAoIHVuZGVmaW5lZCAhPT0gc2VsZWN0aW9uICl7XHJcblx0XHRcdGpRdWVyeSggdGhpcyApLmZpbmQoICdvcHRpb25bdmFsdWU9XCInICsgc2VsZWN0aW9uICsgJ1wiXScgKS5wcm9wKCAnc2VsZWN0ZWQnLCB0cnVlICk7XHJcblxyXG5cdFx0XHRpZiAoICgnJyAhPSBzZWxlY3Rpb24pICYmIChqUXVlcnkoIHRoaXMgKS5oYXNDbGFzcyggJ3NldF9ib29raW5nX2xvY2FsZV9zZWxlY3Rib3gnICkpICl7XHRcdFx0XHRcdFx0XHRcdC8vIExvY2FsZVxyXG5cclxuXHRcdFx0XHR2YXIgYm9va2luZ19sb2NhbGVfYnV0dG9uID0galF1ZXJ5KCB0aGlzICkucGFyZW50cyggJy51aV9lbGVtZW50X2xvY2FsZScgKS5maW5kKCAnLnNldF9ib29raW5nX2xvY2FsZV9idXR0b24nIClcclxuXHJcblx0XHRcdFx0Ly9ib29raW5nX2xvY2FsZV9idXR0b24uY3NzKCAnY29sb3InLCAnI2RiNDgwMCcgKTtcdFx0Ly8gU2V0IGJ1dHRvbiAgcmVkXHJcblx0XHRcdFx0Ym9va2luZ19sb2NhbGVfYnV0dG9uLmFkZENsYXNzKCAnd3BiY191aV9yZWQnICk7XHRcdC8vIFNldCBidXR0b24gIHJlZFxyXG5cdFx0XHRcdCBpZiAoICdmdW5jdGlvbicgPT09IHR5cGVvZiggd3BiY190aXBweSApICl7XHJcblx0XHRcdFx0XHRib29raW5nX2xvY2FsZV9idXR0b24uZ2V0KDApLl90aXBweS5zZXRDb250ZW50KCBzZWxlY3Rpb24gKTtcclxuXHRcdFx0XHQgfVxyXG5cdFx0XHR9XHJcblx0XHR9XHJcblx0fSApO1xyXG59XHJcblxyXG4vKipcclxuICogICBSZW1hcmsgICAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0gKi9cclxuXHJcbi8qKlxyXG4gKiBEZWZpbmUgY29udGVudCBvZiByZW1hcmsgXCJib29raW5nIG5vdGVcIiBidXR0b24gYW5kIHRleHRhcmVhLiAgLS0gSXQncyBjYWxsZWQgZnJvbSBcdHdwYmNfYWp4X2Jvb2tpbmdfZGVmaW5lX3VpX2hvb2tzKCkgIFx0ZWFjaCAgdGltZSBhZnRlciBMaXN0aW5nIGxvYWRpbmcuXHJcbiAqL1xyXG5mdW5jdGlvbiB3cGJjX2FqeF9ib29raW5nX191aV9kZWZpbmVfX3JlbWFyaygpe1xyXG5cclxuXHRqUXVlcnkoICcud3BiY19saXN0aW5nX2NvbnRhaW5lciAudWlfcmVtYXJrX3NlY3Rpb24gdGV4dGFyZWEnICkuZWFjaCggZnVuY3Rpb24gKCBpbmRleCApe1xyXG5cdFx0dmFyIHRleHRfdmFsID0galF1ZXJ5KCB0aGlzICkudmFsKCk7XHJcblx0XHRpZiAoICh1bmRlZmluZWQgIT09IHRleHRfdmFsKSAmJiAoJycgIT0gdGV4dF92YWwpICl7XHJcblxyXG5cdFx0XHR2YXIgcmVtYXJrX2J1dHRvbiA9IGpRdWVyeSggdGhpcyApLnBhcmVudHMoICcudWlfZ3JvdXAnICkuZmluZCggJy5zZXRfYm9va2luZ19ub3RlX2J1dHRvbicgKTtcclxuXHJcblx0XHRcdGlmICggcmVtYXJrX2J1dHRvbi5sZW5ndGggPiAwICl7XHJcblxyXG5cdFx0XHRcdHJlbWFya19idXR0b24uYWRkQ2xhc3MoICd3cGJjX3VpX3JlZCcgKTtcdFx0Ly8gU2V0IGJ1dHRvbiAgcmVkXHJcblx0XHRcdFx0aWYgKCAnZnVuY3Rpb24nID09PSB0eXBlb2YgKHdwYmNfdGlwcHkpICl7XHJcblx0XHRcdFx0XHQvL3JlbWFya19idXR0b24uZ2V0KCAwICkuX3RpcHB5LmFsbG93SFRNTCA9IHRydWU7XHJcblx0XHRcdFx0XHQvL3JlbWFya19idXR0b24uZ2V0KCAwICkuX3RpcHB5LnNldENvbnRlbnQoIHRleHRfdmFsLnJlcGxhY2UoL1tcXG5cXHJdL2csICc8YnI+JykgKTtcclxuXHJcblx0XHRcdFx0XHRyZW1hcmtfYnV0dG9uLmdldCggMCApLl90aXBweS5zZXRQcm9wcygge1xyXG5cdFx0XHRcdFx0XHRhbGxvd0hUTUw6IHRydWUsXHJcblx0XHRcdFx0XHRcdGNvbnRlbnQgIDogdGV4dF92YWwucmVwbGFjZSggL1tcXG5cXHJdL2csICc8YnI+JyApXHJcblx0XHRcdFx0XHR9ICk7XHJcblx0XHRcdFx0fVxyXG5cdFx0XHR9XHJcblx0XHR9XHJcblx0fSApO1xyXG59XHJcblxyXG4vKipcclxuICogQWN0aW9ucyAsd2hlbiB3ZSBjbGljayBvbiBcIlJlbWFya1wiIGJ1dHRvbi5cclxuICpcclxuICogQHBhcmFtIGpxX2J1dHRvbiAgLVx0dGhpcyBqUXVlcnkgYnV0dG9uICBvYmplY3RcclxuICovXHJcbmZ1bmN0aW9uIHdwYmNfYWp4X2Jvb2tpbmdfX3VpX2NsaWNrX19yZW1hcmsoIGpxX2J1dHRvbiApe1xyXG5cclxuXHRqcV9idXR0b24ucGFyZW50cygnLnVpX2dyb3VwJykuZmluZCgnLnVpX3JlbWFya19zZWN0aW9uJykudG9nZ2xlKCk7XHJcbn1cclxuXHJcblxyXG4vKipcclxuICogICBDaGFuZ2UgYm9va2luZyByZXNvdXJjZSAgIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0gKi9cclxuXHJcbmZ1bmN0aW9uIHdwYmNfYWp4X2Jvb2tpbmdfX3VpX2NsaWNrX3Nob3dfX2NoYW5nZV9yZXNvdXJjZSggYm9va2luZ19pZCwgcmVzb3VyY2VfaWQgKXtcclxuXHJcblx0Ly8gRGVmaW5lIElEIG9mIGJvb2tpbmcgdG8gaGlkZGVuIGlucHV0XHJcblx0alF1ZXJ5KCAnI2NoYW5nZV9ib29raW5nX3Jlc291cmNlX19ib29raW5nX2lkJyApLnZhbCggYm9va2luZ19pZCApO1xyXG5cclxuXHQvLyBTZWxlY3QgYm9va2luZyByZXNvdXJjZSAgdGhhdCBiZWxvbmcgdG8gIGJvb2tpbmdcclxuXHRqUXVlcnkoICcjY2hhbmdlX2Jvb2tpbmdfcmVzb3VyY2VfX3Jlc291cmNlX3NlbGVjdCcgKS52YWwoIHJlc291cmNlX2lkICkudHJpZ2dlciggJ2NoYW5nZScgKTtcclxuXHR2YXIgY2JyO1xyXG5cclxuXHQvLyBHZXQgUmVzb3VyY2Ugc2VjdGlvblxyXG5cdGNiciA9IGpRdWVyeSggXCIjY2hhbmdlX2Jvb2tpbmdfcmVzb3VyY2VfX3NlY3Rpb25cIiApLmRldGFjaCgpO1xyXG5cclxuXHQvLyBBcHBlbmQgaXQgdG8gYm9va2luZyBST1dcclxuXHRjYnIuYXBwZW5kVG8oIGpRdWVyeSggXCIjdWlfX2NoYW5nZV9ib29raW5nX3Jlc291cmNlX19zZWN0aW9uX2luX2Jvb2tpbmdfXCIgKyBib29raW5nX2lkICkgKTtcclxuXHRjYnIgPSBudWxsO1xyXG5cclxuXHQvLyBIaWRlIHNlY3Rpb25zIG9mIFwiQ2hhbmdlIGJvb2tpbmcgcmVzb3VyY2VcIiBpbiBhbGwgb3RoZXIgYm9va2luZ3MgUk9Xc1xyXG5cdC8valF1ZXJ5KCBcIi51aV9fY2hhbmdlX2Jvb2tpbmdfcmVzb3VyY2VfX3NlY3Rpb25faW5fYm9va2luZ1wiICkuaGlkZSgpO1xyXG5cdGlmICggISBqUXVlcnkoIFwiI3VpX19jaGFuZ2VfYm9va2luZ19yZXNvdXJjZV9fc2VjdGlvbl9pbl9ib29raW5nX1wiICsgYm9va2luZ19pZCApLmlzKCc6dmlzaWJsZScpICl7XHJcblx0XHRqUXVlcnkoIFwiLnVpX191bmRlcl9hY3Rpb25zX3Jvd19fc2VjdGlvbl9pbl9ib29raW5nXCIgKS5oaWRlKCk7XHJcblx0fVxyXG5cclxuXHQvLyBTaG93IG9ubHkgXCJjaGFuZ2UgYm9va2luZyByZXNvdXJjZVwiIHNlY3Rpb24gIGZvciBjdXJyZW50IGJvb2tpbmdcclxuXHRqUXVlcnkoIFwiI3VpX19jaGFuZ2VfYm9va2luZ19yZXNvdXJjZV9fc2VjdGlvbl9pbl9ib29raW5nX1wiICsgYm9va2luZ19pZCApLnRvZ2dsZSgpO1xyXG59XHJcblxyXG5mdW5jdGlvbiB3cGJjX2FqeF9ib29raW5nX191aV9jbGlja19zYXZlX19jaGFuZ2VfcmVzb3VyY2UoIHRoaXNfZWwsIGJvb2tpbmdfYWN0aW9uLCBlbF9pZCApe1xyXG5cclxuXHR3cGJjX2FqeF9ib29raW5nX2FqYXhfYWN0aW9uX3JlcXVlc3QoIHtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdib29raW5nX2FjdGlvbicgICAgICAgOiBib29raW5nX2FjdGlvbixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdib29raW5nX2lkJyAgICAgICAgICAgOiBqUXVlcnkoICcjY2hhbmdlX2Jvb2tpbmdfcmVzb3VyY2VfX2Jvb2tpbmdfaWQnICkudmFsKCksXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnc2VsZWN0ZWRfcmVzb3VyY2VfaWQnIDogalF1ZXJ5KCAnI2NoYW5nZV9ib29raW5nX3Jlc291cmNlX19yZXNvdXJjZV9zZWxlY3QnICkudmFsKCksXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQndWlfY2xpY2tlZF9lbGVtZW50X2lkJzogZWxfaWRcclxuXHR9ICk7XHJcblxyXG5cdHdwYmNfYnV0dG9uX2VuYWJsZV9sb2FkaW5nX2ljb24oIHRoaXNfZWwgKTtcclxuXHJcblx0Ly8gd3BiY19hanhfYm9va2luZ19fdWlfY2xpY2tfY2xvc2VfX2NoYW5nZV9yZXNvdXJjZSgpO1xyXG59XHJcblxyXG5mdW5jdGlvbiB3cGJjX2FqeF9ib29raW5nX191aV9jbGlja19jbG9zZV9fY2hhbmdlX3Jlc291cmNlKCl7XHJcblxyXG5cdHZhciBjYnJjZTtcclxuXHJcblx0Ly8gR2V0IFJlc291cmNlIHNlY3Rpb25cclxuXHRjYnJjZSA9IGpRdWVyeShcIiNjaGFuZ2VfYm9va2luZ19yZXNvdXJjZV9fc2VjdGlvblwiKS5kZXRhY2goKTtcclxuXHJcblx0Ly8gQXBwZW5kIGl0IHRvIGhpZGRlbiBIVE1MIHRlbXBsYXRlIHNlY3Rpb24gIGF0ICB0aGUgYm90dG9tICBvZiB0aGUgcGFnZVxyXG5cdGNicmNlLmFwcGVuZFRvKGpRdWVyeShcIiN3cGJjX2hpZGRlbl90ZW1wbGF0ZV9fY2hhbmdlX2Jvb2tpbmdfcmVzb3VyY2VcIikpO1xyXG5cdGNicmNlID0gbnVsbDtcclxuXHJcblx0Ly8gSGlkZSBhbGwgY2hhbmdlIGJvb2tpbmcgcmVzb3VyY2VzIHNlY3Rpb25zXHJcblx0alF1ZXJ5KFwiLnVpX19jaGFuZ2VfYm9va2luZ19yZXNvdXJjZV9fc2VjdGlvbl9pbl9ib29raW5nXCIpLmhpZGUoKTtcclxufVxyXG5cclxuLyoqXHJcbiAqICAgRHVwbGljYXRlIGJvb2tpbmcgaW4gb3RoZXIgcmVzb3VyY2UgICAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tICovXHJcblxyXG5mdW5jdGlvbiB3cGJjX2FqeF9ib29raW5nX191aV9jbGlja19zaG93X19kdXBsaWNhdGVfYm9va2luZyggYm9va2luZ19pZCwgcmVzb3VyY2VfaWQgKXtcclxuXHJcblx0Ly8gRGVmaW5lIElEIG9mIGJvb2tpbmcgdG8gaGlkZGVuIGlucHV0XHJcblx0alF1ZXJ5KCAnI2R1cGxpY2F0ZV9ib29raW5nX3RvX290aGVyX3Jlc291cmNlX19ib29raW5nX2lkJyApLnZhbCggYm9va2luZ19pZCApO1xyXG5cclxuXHQvLyBTZWxlY3QgYm9va2luZyByZXNvdXJjZSAgdGhhdCBiZWxvbmcgdG8gIGJvb2tpbmdcclxuXHRqUXVlcnkoICcjZHVwbGljYXRlX2Jvb2tpbmdfdG9fb3RoZXJfcmVzb3VyY2VfX3Jlc291cmNlX3NlbGVjdCcgKS52YWwoIHJlc291cmNlX2lkICkudHJpZ2dlciggJ2NoYW5nZScgKTtcclxuXHR2YXIgY2JyO1xyXG5cclxuXHQvLyBHZXQgUmVzb3VyY2Ugc2VjdGlvblxyXG5cdGNiciA9IGpRdWVyeSggXCIjZHVwbGljYXRlX2Jvb2tpbmdfdG9fb3RoZXJfcmVzb3VyY2VfX3NlY3Rpb25cIiApLmRldGFjaCgpO1xyXG5cclxuXHQvLyBBcHBlbmQgaXQgdG8gYm9va2luZyBST1dcclxuXHRjYnIuYXBwZW5kVG8oIGpRdWVyeSggXCIjdWlfX2R1cGxpY2F0ZV9ib29raW5nX3RvX290aGVyX3Jlc291cmNlX19zZWN0aW9uX2luX2Jvb2tpbmdfXCIgKyBib29raW5nX2lkICkgKTtcclxuXHRjYnIgPSBudWxsO1xyXG5cclxuXHQvLyBIaWRlIHNlY3Rpb25zIG9mIFwiRHVwbGljYXRlIGJvb2tpbmdcIiBpbiBhbGwgb3RoZXIgYm9va2luZ3MgUk9Xc1xyXG5cdGlmICggISBqUXVlcnkoIFwiI3VpX19kdXBsaWNhdGVfYm9va2luZ190b19vdGhlcl9yZXNvdXJjZV9fc2VjdGlvbl9pbl9ib29raW5nX1wiICsgYm9va2luZ19pZCApLmlzKCc6dmlzaWJsZScpICl7XHJcblx0XHRqUXVlcnkoIFwiLnVpX191bmRlcl9hY3Rpb25zX3Jvd19fc2VjdGlvbl9pbl9ib29raW5nXCIgKS5oaWRlKCk7XHJcblx0fVxyXG5cclxuXHQvLyBTaG93IG9ubHkgXCJEdXBsaWNhdGUgYm9va2luZ1wiIHNlY3Rpb24gIGZvciBjdXJyZW50IGJvb2tpbmcgUk9XXHJcblx0alF1ZXJ5KCBcIiN1aV9fZHVwbGljYXRlX2Jvb2tpbmdfdG9fb3RoZXJfcmVzb3VyY2VfX3NlY3Rpb25faW5fYm9va2luZ19cIiArIGJvb2tpbmdfaWQgKS50b2dnbGUoKTtcclxufVxyXG5cclxuZnVuY3Rpb24gd3BiY19hanhfYm9va2luZ19fdWlfY2xpY2tfc2F2ZV9fZHVwbGljYXRlX2Jvb2tpbmcoIHRoaXNfZWwsIGJvb2tpbmdfYWN0aW9uLCBlbF9pZCApe1xyXG5cclxuXHR3cGJjX2FqeF9ib29raW5nX2FqYXhfYWN0aW9uX3JlcXVlc3QoIHtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdib29raW5nX2FjdGlvbicgICAgICAgOiBib29raW5nX2FjdGlvbixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdib29raW5nX2lkJyAgICAgICAgICAgOiBqUXVlcnkoICcjZHVwbGljYXRlX2Jvb2tpbmdfdG9fb3RoZXJfcmVzb3VyY2VfX2Jvb2tpbmdfaWQnICkudmFsKCksXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnc2VsZWN0ZWRfcmVzb3VyY2VfaWQnIDogalF1ZXJ5KCAnI2R1cGxpY2F0ZV9ib29raW5nX3RvX290aGVyX3Jlc291cmNlX19yZXNvdXJjZV9zZWxlY3QnICkudmFsKCksXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQndWlfY2xpY2tlZF9lbGVtZW50X2lkJzogZWxfaWRcclxuXHR9ICk7XHJcblxyXG5cdHdwYmNfYnV0dG9uX2VuYWJsZV9sb2FkaW5nX2ljb24oIHRoaXNfZWwgKTtcclxuXHJcblx0Ly8gd3BiY19hanhfYm9va2luZ19fdWlfY2xpY2tfY2xvc2VfX2NoYW5nZV9yZXNvdXJjZSgpO1xyXG59XHJcblxyXG5mdW5jdGlvbiB3cGJjX2FqeF9ib29raW5nX191aV9jbGlja19jbG9zZV9fZHVwbGljYXRlX2Jvb2tpbmcoKXtcclxuXHJcblx0dmFyIGNicmNlO1xyXG5cclxuXHQvLyBHZXQgUmVzb3VyY2Ugc2VjdGlvblxyXG5cdGNicmNlID0galF1ZXJ5KFwiI2R1cGxpY2F0ZV9ib29raW5nX3RvX290aGVyX3Jlc291cmNlX19zZWN0aW9uXCIpLmRldGFjaCgpO1xyXG5cclxuXHQvLyBBcHBlbmQgaXQgdG8gaGlkZGVuIEhUTUwgdGVtcGxhdGUgc2VjdGlvbiAgYXQgIHRoZSBib3R0b20gIG9mIHRoZSBwYWdlXHJcblx0Y2JyY2UuYXBwZW5kVG8oalF1ZXJ5KFwiI3dwYmNfaGlkZGVuX3RlbXBsYXRlX19kdXBsaWNhdGVfYm9va2luZ190b19vdGhlcl9yZXNvdXJjZVwiKSk7XHJcblx0Y2JyY2UgPSBudWxsO1xyXG5cclxuXHQvLyBIaWRlIGFsbCBjaGFuZ2UgYm9va2luZyByZXNvdXJjZXMgc2VjdGlvbnNcclxuXHRqUXVlcnkoXCIudWlfX2R1cGxpY2F0ZV9ib29raW5nX3RvX290aGVyX3Jlc291cmNlX19zZWN0aW9uX2luX2Jvb2tpbmdcIikuaGlkZSgpO1xyXG59XHJcblxyXG4vKipcclxuICogICBDaGFuZ2UgcGF5bWVudCBzdGF0dXMgICAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0gKi9cclxuXHJcbmZ1bmN0aW9uIHdwYmNfYWp4X2Jvb2tpbmdfX3VpX2NsaWNrX3Nob3dfX3NldF9wYXltZW50X3N0YXR1cyggYm9va2luZ19pZCApe1xyXG5cclxuXHR2YXIgalNlbGVjdCA9IGpRdWVyeSggJyN1aV9fc2V0X3BheW1lbnRfc3RhdHVzX19zZWN0aW9uX2luX2Jvb2tpbmdfJyArIGJvb2tpbmdfaWQgKS5maW5kKCAnc2VsZWN0JyApXHJcblxyXG5cdHZhciBzZWxlY3RlZF9wYXlfc3RhdHVzID0galNlbGVjdC5hdHRyKCBcImFqeC1zZWxlY3RlZC12YWx1ZVwiICk7XHJcblxyXG5cdC8vIElzIGl0IGZsb2F0IC0gdGhlbiAgaXQncyB1bmtub3duXHJcblx0aWYgKCAhaXNOYU4oIHBhcnNlRmxvYXQoIHNlbGVjdGVkX3BheV9zdGF0dXMgKSApICl7XHJcblx0XHRqU2VsZWN0LmZpbmQoICdvcHRpb25bdmFsdWU9XCIxXCJdJyApLnByb3AoICdzZWxlY3RlZCcsIHRydWUgKTtcdFx0XHRcdFx0XHRcdFx0Ly8gVW5rbm93biAgdmFsdWUgaXMgJzEnIGluIHNlbGVjdCBib3hcclxuXHR9IGVsc2Uge1xyXG5cdFx0alNlbGVjdC5maW5kKCAnb3B0aW9uW3ZhbHVlPVwiJyArIHNlbGVjdGVkX3BheV9zdGF0dXMgKyAnXCJdJyApLnByb3AoICdzZWxlY3RlZCcsIHRydWUgKTtcdFx0Ly8gT3RoZXJ3aXNlIGtub3duIHBheW1lbnQgc3RhdHVzXHJcblx0fVxyXG5cclxuXHQvLyBIaWRlIHNlY3Rpb25zIG9mIFwiQ2hhbmdlIGJvb2tpbmcgcmVzb3VyY2VcIiBpbiBhbGwgb3RoZXIgYm9va2luZ3MgUk9Xc1xyXG5cdGlmICggISBqUXVlcnkoIFwiI3VpX19zZXRfcGF5bWVudF9zdGF0dXNfX3NlY3Rpb25faW5fYm9va2luZ19cIiArIGJvb2tpbmdfaWQgKS5pcygnOnZpc2libGUnKSApe1xyXG5cdFx0alF1ZXJ5KCBcIi51aV9fdW5kZXJfYWN0aW9uc19yb3dfX3NlY3Rpb25faW5fYm9va2luZ1wiICkuaGlkZSgpO1xyXG5cdH1cclxuXHJcblx0Ly8gU2hvdyBvbmx5IFwiY2hhbmdlIGJvb2tpbmcgcmVzb3VyY2VcIiBzZWN0aW9uICBmb3IgY3VycmVudCBib29raW5nXHJcblx0alF1ZXJ5KCBcIiN1aV9fc2V0X3BheW1lbnRfc3RhdHVzX19zZWN0aW9uX2luX2Jvb2tpbmdfXCIgKyBib29raW5nX2lkICkudG9nZ2xlKCk7XHJcbn1cclxuXHJcbmZ1bmN0aW9uIHdwYmNfYWp4X2Jvb2tpbmdfX3VpX2NsaWNrX3NhdmVfX3NldF9wYXltZW50X3N0YXR1cyggYm9va2luZ19pZCwgdGhpc19lbCwgYm9va2luZ19hY3Rpb24sIGVsX2lkICl7XHJcblxyXG5cdHdwYmNfYWp4X2Jvb2tpbmdfYWpheF9hY3Rpb25fcmVxdWVzdCgge1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2Jvb2tpbmdfYWN0aW9uJyAgICAgICA6IGJvb2tpbmdfYWN0aW9uLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2Jvb2tpbmdfaWQnICAgICAgICAgICA6IGJvb2tpbmdfaWQsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnc2VsZWN0ZWRfcGF5bWVudF9zdGF0dXMnIDogalF1ZXJ5KCAnI3VpX2J0bl9zZXRfcGF5bWVudF9zdGF0dXMnICsgYm9va2luZ19pZCApLnZhbCgpLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J3VpX2NsaWNrZWRfZWxlbWVudF9pZCc6IGVsX2lkICsgJ19zYXZlJ1xyXG5cdH0gKTtcclxuXHJcblx0d3BiY19idXR0b25fZW5hYmxlX2xvYWRpbmdfaWNvbiggdGhpc19lbCApO1xyXG5cclxuXHRqUXVlcnkoICcjJyArIGVsX2lkICsgJ19jYW5jZWwnKS5oaWRlKCk7XHJcblx0Ly93cGJjX2J1dHRvbl9lbmFibGVfbG9hZGluZ19pY29uKCBqUXVlcnkoICcjJyArIGVsX2lkICsgJ19jYW5jZWwnKS5nZXQoMCkgKTtcclxuXHJcbn1cclxuXHJcbmZ1bmN0aW9uIHdwYmNfYWp4X2Jvb2tpbmdfX3VpX2NsaWNrX2Nsb3NlX19zZXRfcGF5bWVudF9zdGF0dXMoKXtcclxuXHQvLyBIaWRlIGFsbCBjaGFuZ2UgIHBheW1lbnQgc3RhdHVzIGZvciBib29raW5nXHJcblx0alF1ZXJ5KFwiLnVpX19zZXRfcGF5bWVudF9zdGF0dXNfX3NlY3Rpb25faW5fYm9va2luZ1wiKS5oaWRlKCk7XHJcbn1cclxuXHJcblxyXG4vKipcclxuICogICBDaGFuZ2UgYm9va2luZyBjb3N0ICAgLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0gKi9cclxuXHJcbmZ1bmN0aW9uIHdwYmNfYWp4X2Jvb2tpbmdfX3VpX2NsaWNrX3NhdmVfX3NldF9ib29raW5nX2Nvc3QoIGJvb2tpbmdfaWQsIHRoaXNfZWwsIGJvb2tpbmdfYWN0aW9uLCBlbF9pZCApe1xyXG5cclxuXHR3cGJjX2FqeF9ib29raW5nX2FqYXhfYWN0aW9uX3JlcXVlc3QoIHtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdib29raW5nX2FjdGlvbicgICAgICAgOiBib29raW5nX2FjdGlvbixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdib29raW5nX2lkJyAgICAgICAgICAgOiBib29raW5nX2lkLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2Jvb2tpbmdfY29zdCcgXHRcdCAgIDogalF1ZXJ5KCAnI3VpX2J0bl9zZXRfYm9va2luZ19jb3N0JyArIGJvb2tpbmdfaWQgKyAnX2Nvc3QnKS52YWwoKSxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCd1aV9jbGlja2VkX2VsZW1lbnRfaWQnOiBlbF9pZCArICdfc2F2ZSdcclxuXHR9ICk7XHJcblxyXG5cdHdwYmNfYnV0dG9uX2VuYWJsZV9sb2FkaW5nX2ljb24oIHRoaXNfZWwgKTtcclxuXHJcblx0alF1ZXJ5KCAnIycgKyBlbF9pZCArICdfY2FuY2VsJykuaGlkZSgpO1xyXG5cdC8vd3BiY19idXR0b25fZW5hYmxlX2xvYWRpbmdfaWNvbiggalF1ZXJ5KCAnIycgKyBlbF9pZCArICdfY2FuY2VsJykuZ2V0KDApICk7XHJcblxyXG59XHJcblxyXG5mdW5jdGlvbiB3cGJjX2FqeF9ib29raW5nX191aV9jbGlja19jbG9zZV9fc2V0X2Jvb2tpbmdfY29zdCgpe1xyXG5cdC8vIEhpZGUgYWxsIGNoYW5nZSAgcGF5bWVudCBzdGF0dXMgZm9yIGJvb2tpbmdcclxuXHRqUXVlcnkoXCIudWlfX3NldF9ib29raW5nX2Nvc3RfX3NlY3Rpb25faW5fYm9va2luZ1wiKS5oaWRlKCk7XHJcbn1cclxuXHJcblxyXG4vKipcclxuICogICBTZW5kIFBheW1lbnQgcmVxdWVzdCAgIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tICovXHJcblxyXG5mdW5jdGlvbiB3cGJjX2FqeF9ib29raW5nX191aV9jbGlja19fc2VuZF9wYXltZW50X3JlcXVlc3QoKXtcclxuXHJcblx0d3BiY19hanhfYm9va2luZ19hamF4X2FjdGlvbl9yZXF1ZXN0KCB7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnYm9va2luZ19hY3Rpb24nICAgICAgIDogJ3NlbmRfcGF5bWVudF9yZXF1ZXN0JyxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdib29raW5nX2lkJyAgICAgICAgICAgOiBqUXVlcnkoICcjd3BiY19tb2RhbF9fcGF5bWVudF9yZXF1ZXN0X19ib29raW5nX2lkJykudmFsKCksXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQncmVhc29uX29mX2FjdGlvbicgXHQgICA6IGpRdWVyeSggJyN3cGJjX21vZGFsX19wYXltZW50X3JlcXVlc3RfX3JlYXNvbl9vZl9hY3Rpb24nKS52YWwoKSxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCd1aV9jbGlja2VkX2VsZW1lbnRfaWQnOiAnd3BiY19tb2RhbF9fcGF5bWVudF9yZXF1ZXN0X19idXR0b25fc2VuZCdcclxuXHR9ICk7XHJcblx0d3BiY19idXR0b25fZW5hYmxlX2xvYWRpbmdfaWNvbiggalF1ZXJ5KCAnI3dwYmNfbW9kYWxfX3BheW1lbnRfcmVxdWVzdF9fYnV0dG9uX3NlbmQnICkuZ2V0KCAwICkgKTtcclxufVxyXG5cclxuXHJcbi8qKlxyXG4gKiAgIEltcG9ydCBHb29nbGUgQ2FsZW5kYXIgIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLSAqL1xyXG5cclxuZnVuY3Rpb24gd3BiY19hanhfYm9va2luZ19fdWlfY2xpY2tfX2ltcG9ydF9nb29nbGVfY2FsZW5kYXIoKXtcclxuXHJcblx0d3BiY19hanhfYm9va2luZ19hamF4X2FjdGlvbl9yZXF1ZXN0KCB7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnYm9va2luZ19hY3Rpb24nICAgICAgIDogJ2ltcG9ydF9nb29nbGVfY2FsZW5kYXInLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J3VpX2NsaWNrZWRfZWxlbWVudF9pZCc6ICd3cGJjX21vZGFsX19pbXBvcnRfZ29vZ2xlX2NhbGVuZGFyX19idXR0b25fc2VuZCdcclxuXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQsICdib29raW5nX2djYWxfZXZlbnRzX2Zyb20nIDogXHRcdFx0XHRqUXVlcnkoICcjd3BiY19tb2RhbF9faW1wb3J0X2dvb2dsZV9jYWxlbmRhcl9fc2VjdGlvbiAjYm9va2luZ19nY2FsX2V2ZW50c19mcm9tIG9wdGlvbjpzZWxlY3RlZCcpLnZhbCgpXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQsICdib29raW5nX2djYWxfZXZlbnRzX2Zyb21fb2Zmc2V0JyA6IFx0XHRqUXVlcnkoICcjd3BiY19tb2RhbF9faW1wb3J0X2dvb2dsZV9jYWxlbmRhcl9fc2VjdGlvbiAjYm9va2luZ19nY2FsX2V2ZW50c19mcm9tX29mZnNldCcgKS52YWwoKVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0LCAnYm9va2luZ19nY2FsX2V2ZW50c19mcm9tX29mZnNldF90eXBlJyA6IFx0alF1ZXJ5KCAnI3dwYmNfbW9kYWxfX2ltcG9ydF9nb29nbGVfY2FsZW5kYXJfX3NlY3Rpb24gI2Jvb2tpbmdfZ2NhbF9ldmVudHNfZnJvbV9vZmZzZXRfdHlwZSBvcHRpb246c2VsZWN0ZWQnKS52YWwoKVxyXG5cclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCwgJ2Jvb2tpbmdfZ2NhbF9ldmVudHNfdW50aWwnIDogXHRcdFx0alF1ZXJ5KCAnI3dwYmNfbW9kYWxfX2ltcG9ydF9nb29nbGVfY2FsZW5kYXJfX3NlY3Rpb24gI2Jvb2tpbmdfZ2NhbF9ldmVudHNfdW50aWwgb3B0aW9uOnNlbGVjdGVkJykudmFsKClcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCwgJ2Jvb2tpbmdfZ2NhbF9ldmVudHNfdW50aWxfb2Zmc2V0JyA6IFx0XHRqUXVlcnkoICcjd3BiY19tb2RhbF9faW1wb3J0X2dvb2dsZV9jYWxlbmRhcl9fc2VjdGlvbiAjYm9va2luZ19nY2FsX2V2ZW50c191bnRpbF9vZmZzZXQnICkudmFsKClcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCwgJ2Jvb2tpbmdfZ2NhbF9ldmVudHNfdW50aWxfb2Zmc2V0X3R5cGUnIDogalF1ZXJ5KCAnI3dwYmNfbW9kYWxfX2ltcG9ydF9nb29nbGVfY2FsZW5kYXJfX3NlY3Rpb24gI2Jvb2tpbmdfZ2NhbF9ldmVudHNfdW50aWxfb2Zmc2V0X3R5cGUgb3B0aW9uOnNlbGVjdGVkJykudmFsKClcclxuXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQsICdib29raW5nX2djYWxfZXZlbnRzX21heCcgOiBcdGpRdWVyeSggJyN3cGJjX21vZGFsX19pbXBvcnRfZ29vZ2xlX2NhbGVuZGFyX19zZWN0aW9uICNib29raW5nX2djYWxfZXZlbnRzX21heCcgKS52YWwoKVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0LCAnYm9va2luZ19nY2FsX3Jlc291cmNlJyA6IFx0alF1ZXJ5KCAnI3dwYmNfbW9kYWxfX2ltcG9ydF9nb29nbGVfY2FsZW5kYXJfX3NlY3Rpb24gI3dwYmNfYm9va2luZ19yZXNvdXJjZSBvcHRpb246c2VsZWN0ZWQnKS52YWwoKVxyXG5cdH0gKTtcclxuXHR3cGJjX2J1dHRvbl9lbmFibGVfbG9hZGluZ19pY29uKCBqUXVlcnkoICcjd3BiY19tb2RhbF9faW1wb3J0X2dvb2dsZV9jYWxlbmRhcl9fc2VjdGlvbiAjd3BiY19tb2RhbF9faW1wb3J0X2dvb2dsZV9jYWxlbmRhcl9fYnV0dG9uX3NlbmQnICkuZ2V0KCAwICkgKTtcclxufVxyXG5cclxuXHJcbi8qKlxyXG4gKiAgIEV4cG9ydCBib29raW5ncyB0byBDU1YgIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLSAqL1xyXG5mdW5jdGlvbiB3cGJjX2FqeF9ib29raW5nX191aV9jbGlja19fZXhwb3J0X2NzdiggcGFyYW1zICl7XHJcblxyXG5cdHZhciBzZWxlY3RlZF9ib29raW5nX2lkX2FyciA9IHdwYmNfZ2V0X3NlbGVjdGVkX3Jvd19pZCgpO1xyXG5cclxuXHR3cGJjX2FqeF9ib29raW5nX2FqYXhfYWN0aW9uX3JlcXVlc3QoIHtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdib29raW5nX2FjdGlvbicgICAgICAgIDogcGFyYW1zWyAnYm9va2luZ19hY3Rpb24nIF0sXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQndWlfY2xpY2tlZF9lbGVtZW50X2lkJyA6IHBhcmFtc1sgJ3VpX2NsaWNrZWRfZWxlbWVudF9pZCcgXSxcclxuXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnZXhwb3J0X3R5cGUnICAgICAgICAgICA6IHBhcmFtc1sgJ2V4cG9ydF90eXBlJyBdLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2Nzdl9leHBvcnRfc2VwYXJhdG9yJyAgOiBwYXJhbXNbICdjc3ZfZXhwb3J0X3NlcGFyYXRvcicgXSxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdjc3ZfZXhwb3J0X3NraXBfZmllbGRzJzogcGFyYW1zWyAnY3N2X2V4cG9ydF9za2lwX2ZpZWxkcycgXSxcclxuXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnYm9va2luZ19pZCdcdDogc2VsZWN0ZWRfYm9va2luZ19pZF9hcnIuam9pbignLCcpLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J3NlYXJjaF9wYXJhbXMnIDogd3BiY19hanhfYm9va2luZ19saXN0aW5nLnNlYXJjaF9nZXRfYWxsX3BhcmFtcygpXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0fSApO1xyXG5cclxuXHR2YXIgdGhpc19lbCA9IGpRdWVyeSggJyMnICsgcGFyYW1zWyAndWlfY2xpY2tlZF9lbGVtZW50X2lkJyBdICkuZ2V0KCAwIClcclxuXHJcblx0d3BiY19idXR0b25fZW5hYmxlX2xvYWRpbmdfaWNvbiggdGhpc19lbCApO1xyXG59XHJcblxyXG4vKipcclxuICogT3BlbiBVUkwgaW4gbmV3IHRhYiAtIG1haW5seSAgaXQncyB1c2VkIGZvciBvcGVuIENTViBsaW5rICBmb3IgZG93bmxvYWRlZCBleHBvcnRlZCBib29raW5ncyBhcyBDU1ZcclxuICpcclxuICogQHBhcmFtIGV4cG9ydF9jc3ZfdXJsXHJcbiAqL1xyXG5mdW5jdGlvbiB3cGJjX2FqeF9ib29raW5nX19leHBvcnRfY3N2X3VybF9fZG93bmxvYWQoIGV4cG9ydF9jc3ZfdXJsICl7XHJcblxyXG5cdC8vdmFyIHNlbGVjdGVkX2Jvb2tpbmdfaWRfYXJyID0gd3BiY19nZXRfc2VsZWN0ZWRfcm93X2lkKCk7XHJcblxyXG5cdGRvY3VtZW50LmxvY2F0aW9uLmhyZWYgPSBleHBvcnRfY3N2X3VybDsvLyArICcmc2VsZWN0ZWRfaWQ9JyArIHNlbGVjdGVkX2Jvb2tpbmdfaWRfYXJyLmpvaW4oJywnKTtcclxuXHJcblx0Ly8gSXQncyBvcGVuIGFkZGl0aW9uYWwgZGlhbG9nIGZvciBhc2tpbmcgb3BlbmluZyB1bHIgaW4gbmV3IHRhYlxyXG5cdC8vIHdpbmRvdy5vcGVuKCBleHBvcnRfY3N2X3VybCwgJ19ibGFuaycpLmZvY3VzKCk7XHJcbn0iXSwiZmlsZSI6ImluY2x1ZGVzL3BhZ2UtYm9va2luZ3MvX291dC9ib29raW5nc19fYWN0aW9ucy5qcyJ9
