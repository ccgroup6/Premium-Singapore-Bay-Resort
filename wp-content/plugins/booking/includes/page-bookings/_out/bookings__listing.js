"use strict";

function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }

jQuery('body').on({
  'touchmove': function touchmove(e) {
    jQuery('.timespartly').each(function (index) {
      var td_el = jQuery(this).get(0);

      if (undefined != td_el._tippy) {
        var instance = td_el._tippy;
        instance.hide();
      }
    });
  }
});
/**
 * Request Object
 * Here we can  define Search parameters and Update it later,  when  some parameter was changed
 *
 */

var wpbc_ajx_booking_listing = function (obj, $) {
  // Secure parameters for Ajax	------------------------------------------------------------------------------------
  var p_secure = obj.security_obj = obj.security_obj || {
    user_id: 0,
    nonce: '',
    locale: ''
  };

  obj.set_secure_param = function (param_key, param_val) {
    p_secure[param_key] = param_val;
  };

  obj.get_secure_param = function (param_key) {
    return p_secure[param_key];
  }; // Listing Search parameters	------------------------------------------------------------------------------------


  var p_listing = obj.search_request_obj = obj.search_request_obj || {
    sort: "booking_id",
    sort_type: "DESC",
    page_num: 1,
    page_items_count: 10,
    create_date: "",
    keyword: "",
    source: ""
  };

  obj.search_set_all_params = function (request_param_obj) {
    p_listing = request_param_obj;
  };

  obj.search_get_all_params = function () {
    return p_listing;
  };

  obj.search_get_param = function (param_key) {
    return p_listing[param_key];
  };

  obj.search_set_param = function (param_key, param_val) {
    // if ( Array.isArray( param_val ) ){
    // 	param_val = JSON.stringify( param_val );
    // }
    p_listing[param_key] = param_val;
  };

  obj.search_set_params_arr = function (params_arr) {
    _.each(params_arr, function (p_val, p_key, p_data) {
      // Define different Search  parameters for request
      this.search_set_param(p_key, p_val);
    });
  }; // Other parameters 			------------------------------------------------------------------------------------


  var p_other = obj.other_obj = obj.other_obj || {};

  obj.set_other_param = function (param_key, param_val) {
    p_other[param_key] = param_val;
  };

  obj.get_other_param = function (param_key) {
    return p_other[param_key];
  };

  return obj;
}(wpbc_ajx_booking_listing || {}, jQuery);
/**
 *   Ajax  ------------------------------------------------------------------------------------------------------ */

/**
 * Send Ajax search request
 * for searching specific Keyword and other params
 */


function wpbc_ajx_booking_ajax_search_request() {
  console.groupCollapsed('AJX_BOOKING_LISTING');
  console.log(' == Before Ajax Send - search_get_all_params() == ', wpbc_ajx_booking_listing.search_get_all_params());
  wpbc_booking_listing_reload_button__spin_start();
  /*
  //FixIn: forVideo
  if ( ! is_this_action ){
  	//wpbc_ajx_booking__actual_listing__hide();
  	jQuery( wpbc_ajx_booking_listing.get_other_param( 'listing_container' ) ).html(
  		'<div style="width:100%;text-align: center;" id="wpbc_loading_section"><span class="wpbc_icn_autorenew wpbc_spin"></span></div>'
  		+ jQuery( wpbc_ajx_booking_listing.get_other_param( 'listing_container' ) ).html()
  	);
  	if ( 'function' === typeof (jQuery( '#wpbc_loading_section' ).wpbc_my_modal) ){			//FixIn: 9.0.1.5
  		jQuery( '#wpbc_loading_section' ).wpbc_my_modal( 'show' );
  	} else {
  		alert( 'Warning! Booking Calendar. Its seems that  you have deactivated loading of Bootstrap JS files at Booking Settings General page in Advanced section.' )
  	}
  }
  is_this_action = false;
  */
  // Start Ajax

  jQuery.post(wpbc_global1.wpbc_ajaxurl, {
    action: 'WPBC_AJX_BOOKING_LISTING',
    wpbc_ajx_user_id: wpbc_ajx_booking_listing.get_secure_param('user_id'),
    nonce: wpbc_ajx_booking_listing.get_secure_param('nonce'),
    wpbc_ajx_locale: wpbc_ajx_booking_listing.get_secure_param('locale'),
    search_params: wpbc_ajx_booking_listing.search_get_all_params()
  },
  /**
   * S u c c e s s
   *
   * @param response_data		-	its object returned from  Ajax - class-live-searcg.php
   * @param textStatus		-	'success'
   * @param jqXHR				-	Object
   */
  function (response_data, textStatus, jqXHR) {
    //FixIn: forVideo
    //jQuery( '#wpbc_loading_section' ).wpbc_my_modal( 'hide' );
    console.log(' == Response WPBC_AJX_BOOKING_LISTING == ', response_data);
    console.groupEnd(); // Probably Error

    if (_typeof(response_data) !== 'object' || response_data === null) {
      jQuery('#wh_sort_selector').hide();
      jQuery(wpbc_ajx_booking_listing.get_other_param('listing_container')).html('<div class="wpbc-settings-notice notice-warning" style="text-align:left">' + response_data + '</div>');
      return;
    } // Reload page, after filter toolbar was reseted


    if (undefined != response_data['ajx_cleaned_params'] && 'reset_done' === response_data['ajx_cleaned_params']['ui_reset']) {
      location.reload();
      return;
    } // Show listing


    if (response_data['ajx_count'] > 0) {
      wpbc_ajx_booking_show_listing(response_data['ajx_items'], response_data['ajx_search_params'], response_data['ajx_booking_resources']);
      wpbc_pagination_echo(wpbc_ajx_booking_listing.get_other_param('pagination_container'), {
        'page_active': response_data['ajx_search_params']['page_num'],
        'pages_count': Math.ceil(response_data['ajx_count'] / response_data['ajx_search_params']['page_items_count']),
        'page_items_count': response_data['ajx_search_params']['page_items_count'],
        'sort_type': response_data['ajx_search_params']['sort_type']
      });
      wpbc_ajx_booking_define_ui_hooks(); // Redefine Hooks, because we show new DOM elements
    } else {
      wpbc_ajx_booking__actual_listing__hide();
      jQuery(wpbc_ajx_booking_listing.get_other_param('listing_container')).html('<div class="wpbc-settings-notice0 notice-warning0" style="text-align:center;margin-left:-50px;">' + '<strong>' + 'No results found for current filter options...' + '</strong>' + //'<strong>' + 'No results found...' + '</strong>' +
      '</div>');
    } // Update new booking count


    if (undefined !== response_data['ajx_new_bookings_count']) {
      var ajx_new_bookings_count = parseInt(response_data['ajx_new_bookings_count']);

      if (ajx_new_bookings_count > 0) {
        jQuery('.wpbc_badge_count').show();
      }

      jQuery('.bk-update-count').html(ajx_new_bookings_count);
    }

    wpbc_booking_listing_reload_button__spin_pause();
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
 *   Views  ----------------------------------------------------------------------------------------------------- */

/**
 * Show Listing Table 		and define gMail checkbox hooks
 *
 * @param json_items_arr		- JSON object with Items
 * @param json_search_params	- JSON object with Search
 */


function wpbc_ajx_booking_show_listing(json_items_arr, json_search_params, json_booking_resources) {
  wpbc_ajx_define_templates__resource_manipulation(json_items_arr, json_search_params, json_booking_resources); //console.log( 'json_items_arr' , json_items_arr, json_search_params );

  jQuery('#wh_sort_selector').css("display", "flex");
  var list_header_tpl = wp.template('wpbc_ajx_booking_list_header');
  var list_row_tpl = wp.template('wpbc_ajx_booking_list_row'); // Header

  jQuery(wpbc_ajx_booking_listing.get_other_param('listing_container')).html(list_header_tpl()); // Body

  jQuery(wpbc_ajx_booking_listing.get_other_param('listing_container')).append('<div class="wpbc_selectable_body"></div>'); // R o w s

  console.groupCollapsed('LISTING_ROWS'); // LISTING_ROWS

  _.each(json_items_arr, function (p_val, p_key, p_data) {
    if ('undefined' !== typeof json_search_params['keyword']) {
      // Parameter for marking keyword with different color in a list
      p_val['__search_request_keyword__'] = json_search_params['keyword'];
    } else {
      p_val['__search_request_keyword__'] = '';
    }

    p_val['booking_resources'] = json_booking_resources;
    jQuery(wpbc_ajx_booking_listing.get_other_param('listing_container') + ' .wpbc_selectable_body').append(list_row_tpl(p_val));
  });

  console.groupEnd(); // LISTING_ROWS

  wpbc_define_gmail_checkbox_selection(jQuery); // Redefine Hooks for clicking at Checkboxes
}
/**
 * Define template for changing booking resources &  update it each time,  when  listing updating, useful  for showing actual  booking resources.
 *
 * @param json_items_arr		- JSON object with Items
 * @param json_search_params	- JSON object with Search
 * @param json_booking_resources	- JSON object with Resources
 */


function wpbc_ajx_define_templates__resource_manipulation(json_items_arr, json_search_params, json_booking_resources) {
  // Change booking resource
  var change_booking_resource_tpl = wp.template('wpbc_ajx_change_booking_resource');
  jQuery('#wpbc_hidden_template__change_booking_resource').html(change_booking_resource_tpl({
    'ajx_search_params': json_search_params,
    'ajx_booking_resources': json_booking_resources
  })); // Duplicate booking resource

  var duplicate_booking_to_other_resource_tpl = wp.template('wpbc_ajx_duplicate_booking_to_other_resource');
  jQuery('#wpbc_hidden_template__duplicate_booking_to_other_resource').html(duplicate_booking_to_other_resource_tpl({
    'ajx_search_params': json_search_params,
    'ajx_booking_resources': json_booking_resources
  }));
}
/**
 * Show just message instead of listing and hide pagination
 */


function wpbc_ajx_booking_show_message(message) {
  wpbc_ajx_booking__actual_listing__hide();
  jQuery(wpbc_ajx_booking_listing.get_other_param('listing_container')).html('<div class="wpbc-settings-notice notice-warning" style="text-align:left">' + message + '</div>');
}
/**
 *   H o o k s  -  its Action/Times when need to re-Render Views  ----------------------------------------------- */

/**
 * Send Ajax Search Request after Updating search request parameters
 *
 * @param params_arr
 */


function wpbc_ajx_booking_send_search_request_with_params(params_arr) {
  // Define different Search  parameters for request
  _.each(params_arr, function (p_val, p_key, p_data) {
    //console.log( 'Request for: ', p_key, p_val );
    wpbc_ajx_booking_listing.search_set_param(p_key, p_val);
  }); // Send Ajax Request


  wpbc_ajx_booking_ajax_search_request();
}
/**
 * Search request for "Page Number"
 * @param page_number	int
 */


function wpbc_ajx_booking_pagination_click(page_number) {
  wpbc_ajx_booking_send_search_request_with_params({
    'page_num': page_number
  });
}
/**
 *   Keyword Searching  ----------------------------------------------------------------------------------------- */

/**
 * Search request for "Keyword", also set current page to  1
 *
 * @param element_id	-	HTML ID  of element,  where was entered keyword
 */


function wpbc_ajx_booking_send_search_request_for_keyword(element_id) {
  // We need to Reset page_num to 1 with each new search, because we can be at page #4,  but after  new search  we can  have totally  only  1 page
  wpbc_ajx_booking_send_search_request_with_params({
    'keyword': jQuery(element_id).val(),
    'page_num': 1
  });
}
/**
 * Send search request after few seconds (usually after 1,5 sec)
 * Closure function. Its useful,  for do  not send too many Ajax requests, when someone make fast typing.
 */


var wpbc_ajx_booking_searching_after_few_seconds = function () {
  var closed_timer = 0;
  return function (element_id, timer_delay) {
    // Get default value of "timer_delay",  if parameter was not passed into the function.
    timer_delay = typeof timer_delay !== 'undefined' ? timer_delay : 1500;
    clearTimeout(closed_timer); // Clear previous timer
    // Start new Timer

    closed_timer = setTimeout(wpbc_ajx_booking_send_search_request_for_keyword.bind(null, element_id), timer_delay);
  };
}();
/**
 *   Define Dynamic Hooks  (like pagination click, which renew each time with new listing showing)  ------------- */

/**
 * Define HTML ui Hooks: on KeyUp | Change | -> Sort Order & Number Items / Page
 * We are hcnaged it each  time, when showing new listing, because DOM elements chnaged
 */


function wpbc_ajx_booking_define_ui_hooks() {
  if ('function' === typeof wpbc_define_tippy_tooltips) {
    wpbc_define_tippy_tooltips('.wpbc_listing_container ');
  }

  wpbc_ajx_booking__ui_define__locale();
  wpbc_ajx_booking__ui_define__remark(); // Items Per Page

  jQuery('.wpbc_items_per_page').on('change', function (event) {
    wpbc_ajx_booking_send_search_request_with_params({
      'page_items_count': jQuery(this).val(),
      'page_num': 1
    });
  }); // Sorting

  jQuery('.wpbc_items_sort_type').on('change', function (event) {
    wpbc_ajx_booking_send_search_request_with_params({
      'sort_type': jQuery(this).val()
    });
  });
}
/**
 *   Show / Hide Listing  --------------------------------------------------------------------------------------- */

/**
 *  Show Listing Table 	- 	Sending Ajax Request	-	with parameters that  we early  defined in "wpbc_ajx_booking_listing" Obj.
 */


function wpbc_ajx_booking__actual_listing__show() {
  wpbc_ajx_booking_ajax_search_request(); // Send Ajax Request	-	with parameters that  we early  defined in "wpbc_ajx_booking_listing" Obj.
}
/**
 * Hide Listing Table ( and Pagination )
 */


function wpbc_ajx_booking__actual_listing__hide() {
  jQuery('#wh_sort_selector').hide();
  jQuery(wpbc_ajx_booking_listing.get_other_param('listing_container')).html('');
  jQuery(wpbc_ajx_booking_listing.get_other_param('pagination_container')).html('');
}
/**
 *   Support functions for Content Template data  --------------------------------------------------------------- */

/**
 * Highlight strings,
 * by inserting <span class="fieldvalue name fieldsearchvalue">...</span> html  elements into the string.
 * @param {string} booking_details 	- Source string
 * @param {string} booking_keyword	- Keyword to highlight
 * @returns {string}
 */


function wpbc_get_highlighted_search_keyword(booking_details, booking_keyword) {
  booking_keyword = booking_keyword.trim().toLowerCase();

  if (0 == booking_keyword.length) {
    return booking_details;
  } // Highlight substring withing HTML tags in "Content of booking fields data" -- e.g. starting from  >  and ending with <


  var keywordRegex = new RegExp("fieldvalue[^<>]*>([^<]*".concat(booking_keyword, "[^<]*)"), 'gim'); //let matches = [...booking_details.toLowerCase().matchAll( keywordRegex )];

  var matches = booking_details.toLowerCase().matchAll(keywordRegex);
  matches = Array.from(matches);
  var strings_arr = [];
  var pos_previous = 0;
  var search_pos_start;
  var search_pos_end;

  var _iterator = _createForOfIteratorHelper(matches),
      _step;

  try {
    for (_iterator.s(); !(_step = _iterator.n()).done;) {
      var match = _step.value;
      search_pos_start = match.index + match[0].toLowerCase().indexOf('>', 0) + 1;
      strings_arr.push(booking_details.substr(pos_previous, search_pos_start - pos_previous));
      search_pos_end = booking_details.toLowerCase().indexOf('<', search_pos_start);
      strings_arr.push('<span class="fieldvalue name fieldsearchvalue">' + booking_details.substr(search_pos_start, search_pos_end - search_pos_start) + '</span>');
      pos_previous = search_pos_end;
    }
  } catch (err) {
    _iterator.e(err);
  } finally {
    _iterator.f();
  }

  strings_arr.push(booking_details.substr(pos_previous, booking_details.length - pos_previous));
  return strings_arr.join('');
}
/**
 * Convert special HTML characters   from:	 &amp; 	-> 	&
 *
 * @param text
 * @returns {*}
 */


function wpbc_decode_HTML_entities(text) {
  var textArea = document.createElement('textarea');
  textArea.innerHTML = text;
  return textArea.value;
}
/**
 * Convert TO special HTML characters   from:	 & 	-> 	&amp;
 *
 * @param text
 * @returns {*}
 */


function wpbc_encode_HTML_entities(text) {
  var textArea = document.createElement('textarea');
  textArea.innerText = text;
  return textArea.innerHTML;
}
/**
 *   Support Functions - Spin Icon in Buttons  ------------------------------------------------------------------ */

/**
 * Spin button in Filter toolbar  -  Start
 */


function wpbc_booking_listing_reload_button__spin_start() {
  jQuery('#wpbc_booking_listing_reload_button .menu_icon.wpbc_spin').removeClass('wpbc_animation_pause');
}
/**
 * Spin button in Filter toolbar  -  Pause
 */


function wpbc_booking_listing_reload_button__spin_pause() {
  jQuery('#wpbc_booking_listing_reload_button .menu_icon.wpbc_spin').addClass('wpbc_animation_pause');
}
/**
 * Spin button in Filter toolbar  -  is Spinning ?
 *
 * @returns {boolean}
 */


function wpbc_booking_listing_reload_button__is_spin() {
  if (jQuery('#wpbc_booking_listing_reload_button .menu_icon.wpbc_spin').hasClass('wpbc_animation_pause')) {
    return true;
  } else {
    return false;
  }
}
//# sourceMappingURL=data:application/json;charset=utf8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImluY2x1ZGVzL3BhZ2UtYm9va2luZ3MvX3NyYy9ib29raW5nc19fbGlzdGluZy5qcyJdLCJuYW1lcyI6WyJqUXVlcnkiLCJvbiIsImUiLCJlYWNoIiwiaW5kZXgiLCJ0ZF9lbCIsImdldCIsInVuZGVmaW5lZCIsIl90aXBweSIsImluc3RhbmNlIiwiaGlkZSIsIndwYmNfYWp4X2Jvb2tpbmdfbGlzdGluZyIsIm9iaiIsIiQiLCJwX3NlY3VyZSIsInNlY3VyaXR5X29iaiIsInVzZXJfaWQiLCJub25jZSIsImxvY2FsZSIsInNldF9zZWN1cmVfcGFyYW0iLCJwYXJhbV9rZXkiLCJwYXJhbV92YWwiLCJnZXRfc2VjdXJlX3BhcmFtIiwicF9saXN0aW5nIiwic2VhcmNoX3JlcXVlc3Rfb2JqIiwic29ydCIsInNvcnRfdHlwZSIsInBhZ2VfbnVtIiwicGFnZV9pdGVtc19jb3VudCIsImNyZWF0ZV9kYXRlIiwia2V5d29yZCIsInNvdXJjZSIsInNlYXJjaF9zZXRfYWxsX3BhcmFtcyIsInJlcXVlc3RfcGFyYW1fb2JqIiwic2VhcmNoX2dldF9hbGxfcGFyYW1zIiwic2VhcmNoX2dldF9wYXJhbSIsInNlYXJjaF9zZXRfcGFyYW0iLCJzZWFyY2hfc2V0X3BhcmFtc19hcnIiLCJwYXJhbXNfYXJyIiwiXyIsInBfdmFsIiwicF9rZXkiLCJwX2RhdGEiLCJwX290aGVyIiwib3RoZXJfb2JqIiwic2V0X290aGVyX3BhcmFtIiwiZ2V0X290aGVyX3BhcmFtIiwid3BiY19hanhfYm9va2luZ19hamF4X3NlYXJjaF9yZXF1ZXN0IiwiY29uc29sZSIsImdyb3VwQ29sbGFwc2VkIiwibG9nIiwid3BiY19ib29raW5nX2xpc3RpbmdfcmVsb2FkX2J1dHRvbl9fc3Bpbl9zdGFydCIsInBvc3QiLCJ3cGJjX2dsb2JhbDEiLCJ3cGJjX2FqYXh1cmwiLCJhY3Rpb24iLCJ3cGJjX2FqeF91c2VyX2lkIiwid3BiY19hanhfbG9jYWxlIiwic2VhcmNoX3BhcmFtcyIsInJlc3BvbnNlX2RhdGEiLCJ0ZXh0U3RhdHVzIiwianFYSFIiLCJncm91cEVuZCIsImh0bWwiLCJsb2NhdGlvbiIsInJlbG9hZCIsIndwYmNfYWp4X2Jvb2tpbmdfc2hvd19saXN0aW5nIiwid3BiY19wYWdpbmF0aW9uX2VjaG8iLCJNYXRoIiwiY2VpbCIsIndwYmNfYWp4X2Jvb2tpbmdfZGVmaW5lX3VpX2hvb2tzIiwid3BiY19hanhfYm9va2luZ19fYWN0dWFsX2xpc3RpbmdfX2hpZGUiLCJhanhfbmV3X2Jvb2tpbmdzX2NvdW50IiwicGFyc2VJbnQiLCJzaG93Iiwid3BiY19ib29raW5nX2xpc3RpbmdfcmVsb2FkX2J1dHRvbl9fc3Bpbl9wYXVzZSIsImZhaWwiLCJlcnJvclRocm93biIsIndpbmRvdyIsImVycm9yX21lc3NhZ2UiLCJyZXNwb25zZVRleHQiLCJyZXBsYWNlIiwid3BiY19hanhfYm9va2luZ19zaG93X21lc3NhZ2UiLCJqc29uX2l0ZW1zX2FyciIsImpzb25fc2VhcmNoX3BhcmFtcyIsImpzb25fYm9va2luZ19yZXNvdXJjZXMiLCJ3cGJjX2FqeF9kZWZpbmVfdGVtcGxhdGVzX19yZXNvdXJjZV9tYW5pcHVsYXRpb24iLCJjc3MiLCJsaXN0X2hlYWRlcl90cGwiLCJ3cCIsInRlbXBsYXRlIiwibGlzdF9yb3dfdHBsIiwiYXBwZW5kIiwid3BiY19kZWZpbmVfZ21haWxfY2hlY2tib3hfc2VsZWN0aW9uIiwiY2hhbmdlX2Jvb2tpbmdfcmVzb3VyY2VfdHBsIiwiZHVwbGljYXRlX2Jvb2tpbmdfdG9fb3RoZXJfcmVzb3VyY2VfdHBsIiwibWVzc2FnZSIsIndwYmNfYWp4X2Jvb2tpbmdfc2VuZF9zZWFyY2hfcmVxdWVzdF93aXRoX3BhcmFtcyIsIndwYmNfYWp4X2Jvb2tpbmdfcGFnaW5hdGlvbl9jbGljayIsInBhZ2VfbnVtYmVyIiwid3BiY19hanhfYm9va2luZ19zZW5kX3NlYXJjaF9yZXF1ZXN0X2Zvcl9rZXl3b3JkIiwiZWxlbWVudF9pZCIsInZhbCIsIndwYmNfYWp4X2Jvb2tpbmdfc2VhcmNoaW5nX2FmdGVyX2Zld19zZWNvbmRzIiwiY2xvc2VkX3RpbWVyIiwidGltZXJfZGVsYXkiLCJjbGVhclRpbWVvdXQiLCJzZXRUaW1lb3V0IiwiYmluZCIsIndwYmNfZGVmaW5lX3RpcHB5X3Rvb2x0aXBzIiwid3BiY19hanhfYm9va2luZ19fdWlfZGVmaW5lX19sb2NhbGUiLCJ3cGJjX2FqeF9ib29raW5nX191aV9kZWZpbmVfX3JlbWFyayIsImV2ZW50Iiwid3BiY19hanhfYm9va2luZ19fYWN0dWFsX2xpc3RpbmdfX3Nob3ciLCJ3cGJjX2dldF9oaWdobGlnaHRlZF9zZWFyY2hfa2V5d29yZCIsImJvb2tpbmdfZGV0YWlscyIsImJvb2tpbmdfa2V5d29yZCIsInRyaW0iLCJ0b0xvd2VyQ2FzZSIsImxlbmd0aCIsImtleXdvcmRSZWdleCIsIlJlZ0V4cCIsIm1hdGNoZXMiLCJtYXRjaEFsbCIsIkFycmF5IiwiZnJvbSIsInN0cmluZ3NfYXJyIiwicG9zX3ByZXZpb3VzIiwic2VhcmNoX3Bvc19zdGFydCIsInNlYXJjaF9wb3NfZW5kIiwibWF0Y2giLCJpbmRleE9mIiwicHVzaCIsInN1YnN0ciIsImpvaW4iLCJ3cGJjX2RlY29kZV9IVE1MX2VudGl0aWVzIiwidGV4dCIsInRleHRBcmVhIiwiZG9jdW1lbnQiLCJjcmVhdGVFbGVtZW50IiwiaW5uZXJIVE1MIiwidmFsdWUiLCJ3cGJjX2VuY29kZV9IVE1MX2VudGl0aWVzIiwiaW5uZXJUZXh0IiwicmVtb3ZlQ2xhc3MiLCJhZGRDbGFzcyIsIndwYmNfYm9va2luZ19saXN0aW5nX3JlbG9hZF9idXR0b25fX2lzX3NwaW4iLCJoYXNDbGFzcyJdLCJtYXBwaW5ncyI6IkFBQUE7Ozs7Ozs7Ozs7QUFFQUEsTUFBTSxDQUFDLE1BQUQsQ0FBTixDQUFlQyxFQUFmLENBQWtCO0FBQ2QsZUFBYSxtQkFBU0MsQ0FBVCxFQUFZO0FBRTNCRixJQUFBQSxNQUFNLENBQUUsY0FBRixDQUFOLENBQXlCRyxJQUF6QixDQUErQixVQUFXQyxLQUFYLEVBQWtCO0FBRWhELFVBQUlD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QixDQUFQO0FBQ0E7QUFFRDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7OztBQUNBLFNBQVNDLHlCQUFULENBQW9DQyxJQUFwQyxFQUEwQztBQUN6QyxNQUFJQyxRQUFRLEdBQUdDLFFBQVEsQ0FBQ0MsYUFBVCxDQUF3QixVQUF4QixDQUFmO0FBQ0FGLEVBQUFBLFFBQVEsQ0FBQ0csU0FBVCxHQUFxQkosSUFBckI7QUFDQSxTQUFPQyxRQUFRLENBQUNJLEtBQWhCO0FBQ0E7QUFFRDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7OztBQUNBLFNBQVNDLHlCQUFULENBQW1DTixJQUFuQyxFQUF5QztBQUN2QyxNQUFJQyxRQUFRLEdBQUdDLFFBQVEsQ0FBQ0MsYUFBVCxDQUF1QixVQUF2QixDQUFmO0FBQ0FGLEVBQUFBLFFBQVEsQ0FBQ00sU0FBVCxHQUFxQlAsSUFBckI7QUFDQSxTQUFPQyxRQUFRLENBQUNHLFNBQWhCO0FBQ0Q7QUFHRDtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7O0FBQ0EsU0FBU3pGLDhDQUFULEdBQXlEO0FBQ3hEbkQsRUFBQUEsTUFBTSxDQUFFLDBEQUFGLENBQU4sQ0FBb0VnSixXQUFwRSxDQUFpRixzQkFBakY7QUFDQTtBQUVEO0FBQ0E7QUFDQTs7O0FBQ0EsU0FBU3JFLDhDQUFULEdBQXlEO0FBQ3hEM0UsRUFBQUEsTUFBTSxDQUFFLDBEQUFGLENBQU4sQ0FBcUVpSixRQUFyRSxDQUErRSxzQkFBL0U7QUFDQTtBQUVEO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7OztBQUNBLFNBQVNDLDJDQUFULEdBQXNEO0FBQ2xELE1BQUtsSixNQUFNLENBQUUsMERBQUYsQ0FBTixDQUFxRW1KLFFBQXJFLENBQStFLHNCQUEvRSxDQUFMLEVBQThHO0FBQ2hILFdBQU8sSUFBUDtBQUNBLEdBRkUsTUFFSTtBQUNOLFdBQU8sS0FBUDtBQUNBO0FBQ0QiLCJzb3VyY2VzQ29udGVudCI6WyJcInVzZSBzdHJpY3RcIjtcclxuXHJcbmpRdWVyeSgnYm9keScpLm9uKHtcclxuICAgICd0b3VjaG1vdmUnOiBmdW5jdGlvbihlKSB7XHJcblxyXG5cdFx0alF1ZXJ5KCAnLnRpbWVzcGFydGx5JyApLmVhY2goIGZ1bmN0aW9uICggaW5kZXggKXtcclxuXHJcblx0XHRcdHZhciB0ZF9lbCA9IGpRdWVyeSggdGhpcyApLmdldCggMCApO1xyXG5cclxuXHRcdFx0aWYgKCAodW5kZWZpbmVkICE9IHRkX2VsLl90aXBweSkgKXtcclxuXHJcblx0XHRcdFx0dmFyIGluc3RhbmNlID0gdGRfZWwuX3RpcHB5O1xyXG5cdFx0XHRcdGluc3RhbmNlLmhpZGUoKTtcclxuXHRcdFx0fVxyXG5cdFx0fSApO1xyXG5cdH1cclxufSk7XHJcblxyXG4vKipcclxuICogUmVxdWVzdCBPYmplY3RcclxuICogSGVyZSB3ZSBjYW4gIGRlZmluZSBTZWFyY2ggcGFyYW1ldGVycyBhbmQgVXBkYXRlIGl0IGxhdGVyLCAgd2hlbiAgc29tZSBwYXJhbWV0ZXIgd2FzIGNoYW5nZWRcclxuICpcclxuICovXHJcbnZhciB3cGJjX2FqeF9ib29raW5nX2xpc3RpbmcgPSAoZnVuY3Rpb24gKCBvYmosICQpIHtcclxuXHJcblx0Ly8gU2VjdXJlIHBhcmFtZXRlcnMgZm9yIEFqYXhcdC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxyXG5cdHZhciBwX3NlY3VyZSA9IG9iai5zZWN1cml0eV9vYmogPSBvYmouc2VjdXJpdHlfb2JqIHx8IHtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0dXNlcl9pZDogMCxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0bm9uY2UgIDogJycsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdGxvY2FsZSA6ICcnXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIH07XHJcblxyXG5cdG9iai5zZXRfc2VjdXJlX3BhcmFtID0gZnVuY3Rpb24gKCBwYXJhbV9rZXksIHBhcmFtX3ZhbCApIHtcclxuXHRcdHBfc2VjdXJlWyBwYXJhbV9rZXkgXSA9IHBhcmFtX3ZhbDtcclxuXHR9O1xyXG5cclxuXHRvYmouZ2V0X3NlY3VyZV9wYXJhbSA9IGZ1bmN0aW9uICggcGFyYW1fa2V5ICkge1xyXG5cdFx0cmV0dXJuIHBfc2VjdXJlWyBwYXJhbV9rZXkgXTtcclxuXHR9O1xyXG5cclxuXHJcblx0Ly8gTGlzdGluZyBTZWFyY2ggcGFyYW1ldGVyc1x0LS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcblx0dmFyIHBfbGlzdGluZyA9IG9iai5zZWFyY2hfcmVxdWVzdF9vYmogPSBvYmouc2VhcmNoX3JlcXVlc3Rfb2JqIHx8IHtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0c29ydCAgICAgICAgICAgIDogXCJib29raW5nX2lkXCIsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdHNvcnRfdHlwZSAgICAgICA6IFwiREVTQ1wiLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRwYWdlX251bSAgICAgICAgOiAxLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRwYWdlX2l0ZW1zX2NvdW50OiAxMCxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0Y3JlYXRlX2RhdGUgICAgIDogXCJcIixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0a2V5d29yZCAgICAgICAgIDogXCJcIixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0c291cmNlICAgICAgICAgIDogXCJcIlxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0fTtcclxuXHJcblx0b2JqLnNlYXJjaF9zZXRfYWxsX3BhcmFtcyA9IGZ1bmN0aW9uICggcmVxdWVzdF9wYXJhbV9vYmogKSB7XHJcblx0XHRwX2xpc3RpbmcgPSByZXF1ZXN0X3BhcmFtX29iajtcclxuXHR9O1xyXG5cclxuXHRvYmouc2VhcmNoX2dldF9hbGxfcGFyYW1zID0gZnVuY3Rpb24gKCkge1xyXG5cdFx0cmV0dXJuIHBfbGlzdGluZztcclxuXHR9O1xyXG5cclxuXHRvYmouc2VhcmNoX2dldF9wYXJhbSA9IGZ1bmN0aW9uICggcGFyYW1fa2V5ICkge1xyXG5cdFx0cmV0dXJuIHBfbGlzdGluZ1sgcGFyYW1fa2V5IF07XHJcblx0fTtcclxuXHJcblx0b2JqLnNlYXJjaF9zZXRfcGFyYW0gPSBmdW5jdGlvbiAoIHBhcmFtX2tleSwgcGFyYW1fdmFsICkge1xyXG5cdFx0Ly8gaWYgKCBBcnJheS5pc0FycmF5KCBwYXJhbV92YWwgKSApe1xyXG5cdFx0Ly8gXHRwYXJhbV92YWwgPSBKU09OLnN0cmluZ2lmeSggcGFyYW1fdmFsICk7XHJcblx0XHQvLyB9XHJcblx0XHRwX2xpc3RpbmdbIHBhcmFtX2tleSBdID0gcGFyYW1fdmFsO1xyXG5cdH07XHJcblxyXG5cdG9iai5zZWFyY2hfc2V0X3BhcmFtc19hcnIgPSBmdW5jdGlvbiggcGFyYW1zX2FyciApe1xyXG5cdFx0Xy5lYWNoKCBwYXJhbXNfYXJyLCBmdW5jdGlvbiAoIHBfdmFsLCBwX2tleSwgcF9kYXRhICl7XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0Ly8gRGVmaW5lIGRpZmZlcmVudCBTZWFyY2ggIHBhcmFtZXRlcnMgZm9yIHJlcXVlc3RcclxuXHRcdFx0dGhpcy5zZWFyY2hfc2V0X3BhcmFtKCBwX2tleSwgcF92YWwgKTtcclxuXHRcdH0gKTtcclxuXHR9XHJcblxyXG5cclxuXHQvLyBPdGhlciBwYXJhbWV0ZXJzIFx0XHRcdC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxyXG5cdHZhciBwX290aGVyID0gb2JqLm90aGVyX29iaiA9IG9iai5vdGhlcl9vYmogfHwgeyB9O1xyXG5cclxuXHRvYmouc2V0X290aGVyX3BhcmFtID0gZnVuY3Rpb24gKCBwYXJhbV9rZXksIHBhcmFtX3ZhbCApIHtcclxuXHRcdHBfb3RoZXJbIHBhcmFtX2tleSBdID0gcGFyYW1fdmFsO1xyXG5cdH07XHJcblxyXG5cdG9iai5nZXRfb3RoZXJfcGFyYW0gPSBmdW5jdGlvbiAoIHBhcmFtX2tleSApIHtcclxuXHRcdHJldHVybiBwX290aGVyWyBwYXJhbV9rZXkgXTtcclxuXHR9O1xyXG5cclxuXHJcblx0cmV0dXJuIG9iajtcclxufSggd3BiY19hanhfYm9va2luZ19saXN0aW5nIHx8IHt9LCBqUXVlcnkgKSk7XHJcblxyXG5cclxuLyoqXHJcbiAqICAgQWpheCAgLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tICovXHJcblxyXG4vKipcclxuICogU2VuZCBBamF4IHNlYXJjaCByZXF1ZXN0XHJcbiAqIGZvciBzZWFyY2hpbmcgc3BlY2lmaWMgS2V5d29yZCBhbmQgb3RoZXIgcGFyYW1zXHJcbiAqL1xyXG5mdW5jdGlvbiB3cGJjX2FqeF9ib29raW5nX2FqYXhfc2VhcmNoX3JlcXVlc3QoKXtcclxuXHJcbmNvbnNvbGUuZ3JvdXBDb2xsYXBzZWQoJ0FKWF9CT09LSU5HX0xJU1RJTkcnKTsgY29uc29sZS5sb2coICcgPT0gQmVmb3JlIEFqYXggU2VuZCAtIHNlYXJjaF9nZXRfYWxsX3BhcmFtcygpID09ICcgLCB3cGJjX2FqeF9ib29raW5nX2xpc3Rpbmcuc2VhcmNoX2dldF9hbGxfcGFyYW1zKCkgKTtcclxuXHJcblx0d3BiY19ib29raW5nX2xpc3RpbmdfcmVsb2FkX2J1dHRvbl9fc3Bpbl9zdGFydCgpO1xyXG5cclxuLypcclxuLy9GaXhJbjogZm9yVmlkZW9cclxuaWYgKCAhIGlzX3RoaXNfYWN0aW9uICl7XHJcblx0Ly93cGJjX2FqeF9ib29raW5nX19hY3R1YWxfbGlzdGluZ19faGlkZSgpO1xyXG5cdGpRdWVyeSggd3BiY19hanhfYm9va2luZ19saXN0aW5nLmdldF9vdGhlcl9wYXJhbSggJ2xpc3RpbmdfY29udGFpbmVyJyApICkuaHRtbChcclxuXHRcdCc8ZGl2IHN0eWxlPVwid2lkdGg6MTAwJTt0ZXh0LWFsaWduOiBjZW50ZXI7XCIgaWQ9XCJ3cGJjX2xvYWRpbmdfc2VjdGlvblwiPjxzcGFuIGNsYXNzPVwid3BiY19pY25fYXV0b3JlbmV3IHdwYmNfc3BpblwiPjwvc3Bhbj48L2Rpdj4nXHJcblx0XHQrIGpRdWVyeSggd3BiY19hanhfYm9va2luZ19saXN0aW5nLmdldF9vdGhlcl9wYXJhbSggJ2xpc3RpbmdfY29udGFpbmVyJyApICkuaHRtbCgpXHJcblx0KTtcclxuXHRpZiAoICdmdW5jdGlvbicgPT09IHR5cGVvZiAoalF1ZXJ5KCAnI3dwYmNfbG9hZGluZ19zZWN0aW9uJyApLndwYmNfbXlfbW9kYWwpICl7XHRcdFx0Ly9GaXhJbjogOS4wLjEuNVxyXG5cdFx0alF1ZXJ5KCAnI3dwYmNfbG9hZGluZ19zZWN0aW9uJyApLndwYmNfbXlfbW9kYWwoICdzaG93JyApO1xyXG5cdH0gZWxzZSB7XHJcblx0XHRhbGVydCggJ1dhcm5pbmchIEJvb2tpbmcgQ2FsZW5kYXIuIEl0cyBzZWVtcyB0aGF0ICB5b3UgaGF2ZSBkZWFjdGl2YXRlZCBsb2FkaW5nIG9mIEJvb3RzdHJhcCBKUyBmaWxlcyBhdCBCb29raW5nIFNldHRpbmdzIEdlbmVyYWwgcGFnZSBpbiBBZHZhbmNlZCBzZWN0aW9uLicgKVxyXG5cdH1cclxufVxyXG5pc190aGlzX2FjdGlvbiA9IGZhbHNlO1xyXG4qL1xyXG5cdC8vIFN0YXJ0IEFqYXhcclxuXHRqUXVlcnkucG9zdCggd3BiY19nbG9iYWwxLndwYmNfYWpheHVybCxcclxuXHRcdFx0XHR7XHJcblx0XHRcdFx0XHRhY3Rpb24gICAgICAgICAgOiAnV1BCQ19BSlhfQk9PS0lOR19MSVNUSU5HJyxcclxuXHRcdFx0XHRcdHdwYmNfYWp4X3VzZXJfaWQ6IHdwYmNfYWp4X2Jvb2tpbmdfbGlzdGluZy5nZXRfc2VjdXJlX3BhcmFtKCAndXNlcl9pZCcgKSxcclxuXHRcdFx0XHRcdG5vbmNlICAgICAgICAgICA6IHdwYmNfYWp4X2Jvb2tpbmdfbGlzdGluZy5nZXRfc2VjdXJlX3BhcmFtKCAnbm9uY2UnICksXHJcblx0XHRcdFx0XHR3cGJjX2FqeF9sb2NhbGUgOiB3cGJjX2FqeF9ib29raW5nX2xpc3RpbmcuZ2V0X3NlY3VyZV9wYXJhbSggJ2xvY2FsZScgKSxcclxuXHJcblx0XHRcdFx0XHRzZWFyY2hfcGFyYW1zXHQ6IHdwYmNfYWp4X2Jvb2tpbmdfbGlzdGluZy5zZWFyY2hfZ2V0X2FsbF9wYXJhbXMoKVxyXG5cdFx0XHRcdH0sXHJcblx0XHRcdFx0LyoqXHJcblx0XHRcdFx0ICogUyB1IGMgYyBlIHMgc1xyXG5cdFx0XHRcdCAqXHJcblx0XHRcdFx0ICogQHBhcmFtIHJlc3BvbnNlX2RhdGFcdFx0LVx0aXRzIG9iamVjdCByZXR1cm5lZCBmcm9tICBBamF4IC0gY2xhc3MtbGl2ZS1zZWFyY2cucGhwXHJcblx0XHRcdFx0ICogQHBhcmFtIHRleHRTdGF0dXNcdFx0LVx0J3N1Y2Nlc3MnXHJcblx0XHRcdFx0ICogQHBhcmFtIGpxWEhSXHRcdFx0XHQtXHRPYmplY3RcclxuXHRcdFx0XHQgKi9cclxuXHRcdFx0XHRmdW5jdGlvbiAoIHJlc3BvbnNlX2RhdGEsIHRleHRTdGF0dXMsIGpxWEhSICkge1xyXG4vL0ZpeEluOiBmb3JWaWRlb1xyXG4vL2pRdWVyeSggJyN3cGJjX2xvYWRpbmdfc2VjdGlvbicgKS53cGJjX215X21vZGFsKCAnaGlkZScgKTtcclxuXHJcbmNvbnNvbGUubG9nKCAnID09IFJlc3BvbnNlIFdQQkNfQUpYX0JPT0tJTkdfTElTVElORyA9PSAnLCByZXNwb25zZV9kYXRhICk7IGNvbnNvbGUuZ3JvdXBFbmQoKTtcclxuXHRcdFx0XHRcdC8vIFByb2JhYmx5IEVycm9yXHJcblx0XHRcdFx0XHRpZiAoICh0eXBlb2YgcmVzcG9uc2VfZGF0YSAhPT0gJ29iamVjdCcpIHx8IChyZXNwb25zZV9kYXRhID09PSBudWxsKSApe1xyXG5cdFx0XHRcdFx0XHRqUXVlcnkoICcjd2hfc29ydF9zZWxlY3RvcicgKS5oaWRlKCk7XHJcblx0XHRcdFx0XHRcdGpRdWVyeSggd3BiY19hanhfYm9va2luZ19saXN0aW5nLmdldF9vdGhlcl9wYXJhbSggJ2xpc3RpbmdfY29udGFpbmVyJyApICkuaHRtbChcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCc8ZGl2IGNsYXNzPVwid3BiYy1zZXR0aW5ncy1ub3RpY2Ugbm90aWNlLXdhcm5pbmdcIiBzdHlsZT1cInRleHQtYWxpZ246bGVmdFwiPicgK1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRyZXNwb25zZV9kYXRhICtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCc8L2Rpdj4nXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCk7XHJcblx0XHRcdFx0XHRcdHJldHVybjtcclxuXHRcdFx0XHRcdH1cclxuXHJcblx0XHRcdFx0XHQvLyBSZWxvYWQgcGFnZSwgYWZ0ZXIgZmlsdGVyIHRvb2xiYXIgd2FzIHJlc2V0ZWRcclxuXHRcdFx0XHRcdGlmICggICAgICAgKCAgICAgdW5kZWZpbmVkICE9IHJlc3BvbnNlX2RhdGFbICdhanhfY2xlYW5lZF9wYXJhbXMnIF0pXHJcblx0XHRcdFx0XHRcdFx0JiYgKCAncmVzZXRfZG9uZScgPT09IHJlc3BvbnNlX2RhdGFbICdhanhfY2xlYW5lZF9wYXJhbXMnIF1bICd1aV9yZXNldCcgXSlcclxuXHRcdFx0XHRcdCl7XHJcblx0XHRcdFx0XHRcdGxvY2F0aW9uLnJlbG9hZCgpO1xyXG5cdFx0XHRcdFx0XHRyZXR1cm47XHJcblx0XHRcdFx0XHR9XHJcblxyXG5cdFx0XHRcdFx0Ly8gU2hvdyBsaXN0aW5nXHJcblx0XHRcdFx0XHRpZiAoIHJlc3BvbnNlX2RhdGFbICdhanhfY291bnQnIF0gPiAwICl7XHJcblxyXG5cdFx0XHRcdFx0XHR3cGJjX2FqeF9ib29raW5nX3Nob3dfbGlzdGluZyggcmVzcG9uc2VfZGF0YVsgJ2FqeF9pdGVtcycgXSwgcmVzcG9uc2VfZGF0YVsgJ2FqeF9zZWFyY2hfcGFyYW1zJyBdLCByZXNwb25zZV9kYXRhWyAnYWp4X2Jvb2tpbmdfcmVzb3VyY2VzJyBdICk7XHJcblxyXG5cdFx0XHRcdFx0XHR3cGJjX3BhZ2luYXRpb25fZWNobyhcclxuXHRcdFx0XHRcdFx0XHR3cGJjX2FqeF9ib29raW5nX2xpc3RpbmcuZ2V0X290aGVyX3BhcmFtKCAncGFnaW5hdGlvbl9jb250YWluZXInICksXHJcblx0XHRcdFx0XHRcdFx0e1xyXG5cdFx0XHRcdFx0XHRcdFx0J3BhZ2VfYWN0aXZlJzogcmVzcG9uc2VfZGF0YVsgJ2FqeF9zZWFyY2hfcGFyYW1zJyBdWyAncGFnZV9udW0nIF0sXHJcblx0XHRcdFx0XHRcdFx0XHQncGFnZXNfY291bnQnOiBNYXRoLmNlaWwoIHJlc3BvbnNlX2RhdGFbICdhanhfY291bnQnIF0gLyByZXNwb25zZV9kYXRhWyAnYWp4X3NlYXJjaF9wYXJhbXMnIF1bICdwYWdlX2l0ZW1zX2NvdW50JyBdICksXHJcblxyXG5cdFx0XHRcdFx0XHRcdFx0J3BhZ2VfaXRlbXNfY291bnQnOiByZXNwb25zZV9kYXRhWyAnYWp4X3NlYXJjaF9wYXJhbXMnIF1bICdwYWdlX2l0ZW1zX2NvdW50JyBdLFxyXG5cdFx0XHRcdFx0XHRcdFx0J3NvcnRfdHlwZScgICAgICAgOiByZXNwb25zZV9kYXRhWyAnYWp4X3NlYXJjaF9wYXJhbXMnIF1bICdzb3J0X3R5cGUnIF1cclxuXHRcdFx0XHRcdFx0XHR9XHJcblx0XHRcdFx0XHRcdCk7XHJcblx0XHRcdFx0XHRcdHdwYmNfYWp4X2Jvb2tpbmdfZGVmaW5lX3VpX2hvb2tzKCk7XHRcdFx0XHRcdFx0Ly8gUmVkZWZpbmUgSG9va3MsIGJlY2F1c2Ugd2Ugc2hvdyBuZXcgRE9NIGVsZW1lbnRzXHJcblxyXG5cdFx0XHRcdFx0fSBlbHNlIHtcclxuXHJcblx0XHRcdFx0XHRcdHdwYmNfYWp4X2Jvb2tpbmdfX2FjdHVhbF9saXN0aW5nX19oaWRlKCk7XHJcblx0XHRcdFx0XHRcdGpRdWVyeSggd3BiY19hanhfYm9va2luZ19saXN0aW5nLmdldF9vdGhlcl9wYXJhbSggJ2xpc3RpbmdfY29udGFpbmVyJyApICkuaHRtbChcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCc8ZGl2IGNsYXNzPVwid3BiYy1zZXR0aW5ncy1ub3RpY2UwIG5vdGljZS13YXJuaW5nMFwiIHN0eWxlPVwidGV4dC1hbGlnbjpjZW50ZXI7bWFyZ2luLWxlZnQ6LTUwcHg7XCI+JyArXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCc8c3Ryb25nPicgKyAnTm8gcmVzdWx0cyBmb3VuZCBmb3IgY3VycmVudCBmaWx0ZXIgb3B0aW9ucy4uLicgKyAnPC9zdHJvbmc+JyArXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdC8vJzxzdHJvbmc+JyArICdObyByZXN1bHRzIGZvdW5kLi4uJyArICc8L3N0cm9uZz4nICtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCc8L2Rpdj4nXHJcblx0XHRcdFx0XHRcdFx0XHRcdCk7XHJcblx0XHRcdFx0XHR9XHJcblxyXG5cdFx0XHRcdFx0Ly8gVXBkYXRlIG5ldyBib29raW5nIGNvdW50XHJcblx0XHRcdFx0XHRpZiAoIHVuZGVmaW5lZCAhPT0gcmVzcG9uc2VfZGF0YVsgJ2FqeF9uZXdfYm9va2luZ3NfY291bnQnIF0gKXtcclxuXHRcdFx0XHRcdFx0dmFyIGFqeF9uZXdfYm9va2luZ3NfY291bnQgPSBwYXJzZUludCggcmVzcG9uc2VfZGF0YVsgJ2FqeF9uZXdfYm9va2luZ3NfY291bnQnIF0gKVxyXG5cdFx0XHRcdFx0XHRpZiAoYWp4X25ld19ib29raW5nc19jb3VudD4wKXtcclxuXHRcdFx0XHRcdFx0XHRqUXVlcnkoICcud3BiY19iYWRnZV9jb3VudCcgKS5zaG93KCk7XHJcblx0XHRcdFx0XHRcdH1cclxuXHRcdFx0XHRcdFx0alF1ZXJ5KCAnLmJrLXVwZGF0ZS1jb3VudCcgKS5odG1sKCBhanhfbmV3X2Jvb2tpbmdzX2NvdW50ICk7XHJcblx0XHRcdFx0XHR9XHJcblxyXG5cdFx0XHRcdFx0d3BiY19ib29raW5nX2xpc3RpbmdfcmVsb2FkX2J1dHRvbl9fc3Bpbl9wYXVzZSgpO1xyXG5cclxuXHRcdFx0XHRcdGpRdWVyeSggJyNhamF4X3Jlc3BvbmQnICkuaHRtbCggcmVzcG9uc2VfZGF0YSApO1x0XHQvLyBGb3IgYWJpbGl0eSB0byBzaG93IHJlc3BvbnNlLCBhZGQgc3VjaCBESVYgZWxlbWVudCB0byBwYWdlXHJcblx0XHRcdFx0fVxyXG5cdFx0XHQgICkuZmFpbCggZnVuY3Rpb24gKCBqcVhIUiwgdGV4dFN0YXR1cywgZXJyb3JUaHJvd24gKSB7ICAgIGlmICggd2luZG93LmNvbnNvbGUgJiYgd2luZG93LmNvbnNvbGUubG9nICl7IGNvbnNvbGUubG9nKCAnQWpheF9FcnJvcicsIGpxWEhSLCB0ZXh0U3RhdHVzLCBlcnJvclRocm93biApOyB9XHJcblx0XHRcdFx0XHRqUXVlcnkoICcjd2hfc29ydF9zZWxlY3RvcicgKS5oaWRlKCk7XHJcblx0XHRcdFx0XHR2YXIgZXJyb3JfbWVzc2FnZSA9ICc8c3Ryb25nPicgKyAnRXJyb3IhJyArICc8L3N0cm9uZz4gJyArIGVycm9yVGhyb3duIDtcclxuXHRcdFx0XHRcdGlmICgganFYSFIucmVzcG9uc2VUZXh0ICl7XHJcblx0XHRcdFx0XHRcdGVycm9yX21lc3NhZ2UgKz0ganFYSFIucmVzcG9uc2VUZXh0O1xyXG5cdFx0XHRcdFx0fVxyXG5cdFx0XHRcdFx0ZXJyb3JfbWVzc2FnZSA9IGVycm9yX21lc3NhZ2UucmVwbGFjZSggL1xcbi9nLCBcIjxiciAvPlwiICk7XHJcblxyXG5cdFx0XHRcdFx0d3BiY19hanhfYm9va2luZ19zaG93X21lc3NhZ2UoIGVycm9yX21lc3NhZ2UgKTtcclxuXHRcdFx0ICB9KVxyXG5cdCAgICAgICAgICAvLyAuZG9uZSggICBmdW5jdGlvbiAoIGRhdGEsIHRleHRTdGF0dXMsIGpxWEhSICkgeyAgIGlmICggd2luZG93LmNvbnNvbGUgJiYgd2luZG93LmNvbnNvbGUubG9nICl7IGNvbnNvbGUubG9nKCAnc2Vjb25kIHN1Y2Nlc3MnLCBkYXRhLCB0ZXh0U3RhdHVzLCBqcVhIUiApOyB9ICAgIH0pXHJcblx0XHRcdCAgLy8gLmFsd2F5cyggZnVuY3Rpb24gKCBkYXRhX2pxWEhSLCB0ZXh0U3RhdHVzLCBqcVhIUl9lcnJvclRocm93biApIHsgICBpZiAoIHdpbmRvdy5jb25zb2xlICYmIHdpbmRvdy5jb25zb2xlLmxvZyApeyBjb25zb2xlLmxvZyggJ2Fsd2F5cyBmaW5pc2hlZCcsIGRhdGFfanFYSFIsIHRleHRTdGF0dXMsIGpxWEhSX2Vycm9yVGhyb3duICk7IH0gICAgIH0pXHJcblx0XHRcdCAgOyAgLy8gRW5kIEFqYXhcclxufVxyXG5cclxuXHJcbi8qKlxyXG4gKiAgIFZpZXdzICAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLSAqL1xyXG5cclxuLyoqXHJcbiAqIFNob3cgTGlzdGluZyBUYWJsZSBcdFx0YW5kIGRlZmluZSBnTWFpbCBjaGVja2JveCBob29rc1xyXG4gKlxyXG4gKiBAcGFyYW0ganNvbl9pdGVtc19hcnJcdFx0LSBKU09OIG9iamVjdCB3aXRoIEl0ZW1zXHJcbiAqIEBwYXJhbSBqc29uX3NlYXJjaF9wYXJhbXNcdC0gSlNPTiBvYmplY3Qgd2l0aCBTZWFyY2hcclxuICovXHJcbmZ1bmN0aW9uIHdwYmNfYWp4X2Jvb2tpbmdfc2hvd19saXN0aW5nKCBqc29uX2l0ZW1zX2FyciwganNvbl9zZWFyY2hfcGFyYW1zLCBqc29uX2Jvb2tpbmdfcmVzb3VyY2VzICl7XHJcblxyXG5cdHdwYmNfYWp4X2RlZmluZV90ZW1wbGF0ZXNfX3Jlc291cmNlX21hbmlwdWxhdGlvbigganNvbl9pdGVtc19hcnIsIGpzb25fc2VhcmNoX3BhcmFtcywganNvbl9ib29raW5nX3Jlc291cmNlcyApO1xyXG5cclxuLy9jb25zb2xlLmxvZyggJ2pzb25faXRlbXNfYXJyJyAsIGpzb25faXRlbXNfYXJyLCBqc29uX3NlYXJjaF9wYXJhbXMgKTtcclxuXHRqUXVlcnkoICcjd2hfc29ydF9zZWxlY3RvcicgKS5jc3MoIFwiZGlzcGxheVwiLCBcImZsZXhcIiApO1xyXG5cdHZhciBsaXN0X2hlYWRlcl90cGwgPSB3cC50ZW1wbGF0ZSggJ3dwYmNfYWp4X2Jvb2tpbmdfbGlzdF9oZWFkZXInICk7XHJcblx0dmFyIGxpc3Rfcm93X3RwbCAgICA9IHdwLnRlbXBsYXRlKCAnd3BiY19hanhfYm9va2luZ19saXN0X3JvdycgKTtcclxuXHJcblxyXG5cdC8vIEhlYWRlclxyXG5cdGpRdWVyeSggd3BiY19hanhfYm9va2luZ19saXN0aW5nLmdldF9vdGhlcl9wYXJhbSggJ2xpc3RpbmdfY29udGFpbmVyJyApICkuaHRtbCggbGlzdF9oZWFkZXJfdHBsKCkgKTtcclxuXHJcblx0Ly8gQm9keVxyXG5cdGpRdWVyeSggd3BiY19hanhfYm9va2luZ19saXN0aW5nLmdldF9vdGhlcl9wYXJhbSggJ2xpc3RpbmdfY29udGFpbmVyJyApICkuYXBwZW5kKCAnPGRpdiBjbGFzcz1cIndwYmNfc2VsZWN0YWJsZV9ib2R5XCI+PC9kaXY+JyApO1xyXG5cclxuXHQvLyBSIG8gdyBzXHJcbmNvbnNvbGUuZ3JvdXBDb2xsYXBzZWQoICdMSVNUSU5HX1JPV1MnICk7XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdC8vIExJU1RJTkdfUk9XU1xyXG5cdF8uZWFjaCgganNvbl9pdGVtc19hcnIsIGZ1bmN0aW9uICggcF92YWwsIHBfa2V5LCBwX2RhdGEgKXtcclxuXHRcdGlmICggJ3VuZGVmaW5lZCcgIT09IHR5cGVvZiBqc29uX3NlYXJjaF9wYXJhbXNbICdrZXl3b3JkJyBdICl7XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQvLyBQYXJhbWV0ZXIgZm9yIG1hcmtpbmcga2V5d29yZCB3aXRoIGRpZmZlcmVudCBjb2xvciBpbiBhIGxpc3RcclxuXHRcdFx0cF92YWxbICdfX3NlYXJjaF9yZXF1ZXN0X2tleXdvcmRfXycgXSA9IGpzb25fc2VhcmNoX3BhcmFtc1sgJ2tleXdvcmQnIF07XHJcblx0XHR9IGVsc2Uge1xyXG5cdFx0XHRwX3ZhbFsgJ19fc2VhcmNoX3JlcXVlc3Rfa2V5d29yZF9fJyBdID0gJyc7XHJcblx0XHR9XHJcblx0XHRwX3ZhbFsgJ2Jvb2tpbmdfcmVzb3VyY2VzJyBdID0ganNvbl9ib29raW5nX3Jlc291cmNlcztcclxuXHRcdGpRdWVyeSggd3BiY19hanhfYm9va2luZ19saXN0aW5nLmdldF9vdGhlcl9wYXJhbSggJ2xpc3RpbmdfY29udGFpbmVyJyApICsgJyAud3BiY19zZWxlY3RhYmxlX2JvZHknICkuYXBwZW5kKCBsaXN0X3Jvd190cGwoIHBfdmFsICkgKTtcclxuXHR9ICk7XHJcbmNvbnNvbGUuZ3JvdXBFbmQoKTsgXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQvLyBMSVNUSU5HX1JPV1NcclxuXHJcblx0d3BiY19kZWZpbmVfZ21haWxfY2hlY2tib3hfc2VsZWN0aW9uKCBqUXVlcnkgKTtcdFx0XHRcdFx0XHQvLyBSZWRlZmluZSBIb29rcyBmb3IgY2xpY2tpbmcgYXQgQ2hlY2tib3hlc1xyXG59XHJcblxyXG5cclxuXHQvKipcclxuXHQgKiBEZWZpbmUgdGVtcGxhdGUgZm9yIGNoYW5naW5nIGJvb2tpbmcgcmVzb3VyY2VzICYgIHVwZGF0ZSBpdCBlYWNoIHRpbWUsICB3aGVuICBsaXN0aW5nIHVwZGF0aW5nLCB1c2VmdWwgIGZvciBzaG93aW5nIGFjdHVhbCAgYm9va2luZyByZXNvdXJjZXMuXHJcblx0ICpcclxuXHQgKiBAcGFyYW0ganNvbl9pdGVtc19hcnJcdFx0LSBKU09OIG9iamVjdCB3aXRoIEl0ZW1zXHJcblx0ICogQHBhcmFtIGpzb25fc2VhcmNoX3BhcmFtc1x0LSBKU09OIG9iamVjdCB3aXRoIFNlYXJjaFxyXG5cdCAqIEBwYXJhbSBqc29uX2Jvb2tpbmdfcmVzb3VyY2VzXHQtIEpTT04gb2JqZWN0IHdpdGggUmVzb3VyY2VzXHJcblx0ICovXHJcblx0ZnVuY3Rpb24gd3BiY19hanhfZGVmaW5lX3RlbXBsYXRlc19fcmVzb3VyY2VfbWFuaXB1bGF0aW9uKCBqc29uX2l0ZW1zX2FyciwganNvbl9zZWFyY2hfcGFyYW1zLCBqc29uX2Jvb2tpbmdfcmVzb3VyY2VzICl7XHJcblxyXG5cdFx0Ly8gQ2hhbmdlIGJvb2tpbmcgcmVzb3VyY2VcclxuXHRcdHZhciBjaGFuZ2VfYm9va2luZ19yZXNvdXJjZV90cGwgPSB3cC50ZW1wbGF0ZSggJ3dwYmNfYWp4X2NoYW5nZV9ib29raW5nX3Jlc291cmNlJyApO1xyXG5cclxuXHRcdGpRdWVyeSggJyN3cGJjX2hpZGRlbl90ZW1wbGF0ZV9fY2hhbmdlX2Jvb2tpbmdfcmVzb3VyY2UnICkuaHRtbChcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRjaGFuZ2VfYm9va2luZ19yZXNvdXJjZV90cGwoIHtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdhanhfc2VhcmNoX3BhcmFtcycgICAgOiBqc29uX3NlYXJjaF9wYXJhbXMsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnYWp4X2Jvb2tpbmdfcmVzb3VyY2VzJzoganNvbl9ib29raW5nX3Jlc291cmNlc1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdH0gKVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0KTtcclxuXHJcblx0XHQvLyBEdXBsaWNhdGUgYm9va2luZyByZXNvdXJjZVxyXG5cdFx0dmFyIGR1cGxpY2F0ZV9ib29raW5nX3RvX290aGVyX3Jlc291cmNlX3RwbCA9IHdwLnRlbXBsYXRlKCAnd3BiY19hanhfZHVwbGljYXRlX2Jvb2tpbmdfdG9fb3RoZXJfcmVzb3VyY2UnICk7XHJcblxyXG5cdFx0alF1ZXJ5KCAnI3dwYmNfaGlkZGVuX3RlbXBsYXRlX19kdXBsaWNhdGVfYm9va2luZ190b19vdGhlcl9yZXNvdXJjZScgKS5odG1sKFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdGR1cGxpY2F0ZV9ib29raW5nX3RvX290aGVyX3Jlc291cmNlX3RwbCgge1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2FqeF9zZWFyY2hfcGFyYW1zJyAgICA6IGpzb25fc2VhcmNoX3BhcmFtcyxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdhanhfYm9va2luZ19yZXNvdXJjZXMnOiBqc29uX2Jvb2tpbmdfcmVzb3VyY2VzXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0fSApXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQpO1xyXG5cdH1cclxuXHJcblxyXG4vKipcclxuICogU2hvdyBqdXN0IG1lc3NhZ2UgaW5zdGVhZCBvZiBsaXN0aW5nIGFuZCBoaWRlIHBhZ2luYXRpb25cclxuICovXHJcbmZ1bmN0aW9uIHdwYmNfYWp4X2Jvb2tpbmdfc2hvd19tZXNzYWdlKCBtZXNzYWdlICl7XHJcblxyXG5cdHdwYmNfYWp4X2Jvb2tpbmdfX2FjdHVhbF9saXN0aW5nX19oaWRlKCk7XHJcblxyXG5cdGpRdWVyeSggd3BiY19hanhfYm9va2luZ19saXN0aW5nLmdldF9vdGhlcl9wYXJhbSggJ2xpc3RpbmdfY29udGFpbmVyJyApICkuaHRtbChcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0JzxkaXYgY2xhc3M9XCJ3cGJjLXNldHRpbmdzLW5vdGljZSBub3RpY2Utd2FybmluZ1wiIHN0eWxlPVwidGV4dC1hbGlnbjpsZWZ0XCI+JyArXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0bWVzc2FnZSArXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCc8L2Rpdj4nXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0KTtcclxufVxyXG5cclxuXHJcbi8qKlxyXG4gKiAgIEggbyBvIGsgcyAgLSAgaXRzIEFjdGlvbi9UaW1lcyB3aGVuIG5lZWQgdG8gcmUtUmVuZGVyIFZpZXdzICAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLSAqL1xyXG5cclxuLyoqXHJcbiAqIFNlbmQgQWpheCBTZWFyY2ggUmVxdWVzdCBhZnRlciBVcGRhdGluZyBzZWFyY2ggcmVxdWVzdCBwYXJhbWV0ZXJzXHJcbiAqXHJcbiAqIEBwYXJhbSBwYXJhbXNfYXJyXHJcbiAqL1xyXG5mdW5jdGlvbiB3cGJjX2FqeF9ib29raW5nX3NlbmRfc2VhcmNoX3JlcXVlc3Rfd2l0aF9wYXJhbXMgKCBwYXJhbXNfYXJyICl7XHJcblxyXG5cdC8vIERlZmluZSBkaWZmZXJlbnQgU2VhcmNoICBwYXJhbWV0ZXJzIGZvciByZXF1ZXN0XHJcblx0Xy5lYWNoKCBwYXJhbXNfYXJyLCBmdW5jdGlvbiAoIHBfdmFsLCBwX2tleSwgcF9kYXRhICkge1xyXG5cdFx0Ly9jb25zb2xlLmxvZyggJ1JlcXVlc3QgZm9yOiAnLCBwX2tleSwgcF92YWwgKTtcclxuXHRcdHdwYmNfYWp4X2Jvb2tpbmdfbGlzdGluZy5zZWFyY2hfc2V0X3BhcmFtKCBwX2tleSwgcF92YWwgKTtcclxuXHR9KTtcclxuXHJcblx0Ly8gU2VuZCBBamF4IFJlcXVlc3RcclxuXHR3cGJjX2FqeF9ib29raW5nX2FqYXhfc2VhcmNoX3JlcXVlc3QoKTtcclxufVxyXG5cclxuLyoqXHJcbiAqIFNlYXJjaCByZXF1ZXN0IGZvciBcIlBhZ2UgTnVtYmVyXCJcclxuICogQHBhcmFtIHBhZ2VfbnVtYmVyXHRpbnRcclxuICovXHJcbmZ1bmN0aW9uIHdwYmNfYWp4X2Jvb2tpbmdfcGFnaW5hdGlvbl9jbGljayggcGFnZV9udW1iZXIgKXtcclxuXHJcblx0d3BiY19hanhfYm9va2luZ19zZW5kX3NlYXJjaF9yZXF1ZXN0X3dpdGhfcGFyYW1zKCB7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0J3BhZ2VfbnVtJzogcGFnZV9udW1iZXJcclxuXHRcdFx0XHRcdFx0XHRcdFx0fSApO1xyXG59XHJcblxyXG5cclxuLyoqXHJcbiAqICAgS2V5d29yZCBTZWFyY2hpbmcgIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tICovXHJcblxyXG4vKipcclxuICogU2VhcmNoIHJlcXVlc3QgZm9yIFwiS2V5d29yZFwiLCBhbHNvIHNldCBjdXJyZW50IHBhZ2UgdG8gIDFcclxuICpcclxuICogQHBhcmFtIGVsZW1lbnRfaWRcdC1cdEhUTUwgSUQgIG9mIGVsZW1lbnQsICB3aGVyZSB3YXMgZW50ZXJlZCBrZXl3b3JkXHJcbiAqL1xyXG5mdW5jdGlvbiB3cGJjX2FqeF9ib29raW5nX3NlbmRfc2VhcmNoX3JlcXVlc3RfZm9yX2tleXdvcmQoIGVsZW1lbnRfaWQgKSB7XHJcblxyXG5cdC8vIFdlIG5lZWQgdG8gUmVzZXQgcGFnZV9udW0gdG8gMSB3aXRoIGVhY2ggbmV3IHNlYXJjaCwgYmVjYXVzZSB3ZSBjYW4gYmUgYXQgcGFnZSAjNCwgIGJ1dCBhZnRlciAgbmV3IHNlYXJjaCAgd2UgY2FuICBoYXZlIHRvdGFsbHkgIG9ubHkgIDEgcGFnZVxyXG5cdHdwYmNfYWp4X2Jvb2tpbmdfc2VuZF9zZWFyY2hfcmVxdWVzdF93aXRoX3BhcmFtcygge1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2tleXdvcmQnICA6IGpRdWVyeSggZWxlbWVudF9pZCApLnZhbCgpLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J3BhZ2VfbnVtJzogMVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdH0gKTtcclxufVxyXG5cclxuXHQvKipcclxuXHQgKiBTZW5kIHNlYXJjaCByZXF1ZXN0IGFmdGVyIGZldyBzZWNvbmRzICh1c3VhbGx5IGFmdGVyIDEsNSBzZWMpXHJcblx0ICogQ2xvc3VyZSBmdW5jdGlvbi4gSXRzIHVzZWZ1bCwgIGZvciBkbyAgbm90IHNlbmQgdG9vIG1hbnkgQWpheCByZXF1ZXN0cywgd2hlbiBzb21lb25lIG1ha2UgZmFzdCB0eXBpbmcuXHJcblx0ICovXHJcblx0dmFyIHdwYmNfYWp4X2Jvb2tpbmdfc2VhcmNoaW5nX2FmdGVyX2Zld19zZWNvbmRzID0gZnVuY3Rpb24gKCl7XHJcblxyXG5cdFx0dmFyIGNsb3NlZF90aW1lciA9IDA7XHJcblxyXG5cdFx0cmV0dXJuIGZ1bmN0aW9uICggZWxlbWVudF9pZCwgdGltZXJfZGVsYXkgKXtcclxuXHJcblx0XHRcdC8vIEdldCBkZWZhdWx0IHZhbHVlIG9mIFwidGltZXJfZGVsYXlcIiwgIGlmIHBhcmFtZXRlciB3YXMgbm90IHBhc3NlZCBpbnRvIHRoZSBmdW5jdGlvbi5cclxuXHRcdFx0dGltZXJfZGVsYXkgPSB0eXBlb2YgdGltZXJfZGVsYXkgIT09ICd1bmRlZmluZWQnID8gdGltZXJfZGVsYXkgOiAxNTAwO1xyXG5cclxuXHRcdFx0Y2xlYXJUaW1lb3V0KCBjbG9zZWRfdGltZXIgKTtcdFx0Ly8gQ2xlYXIgcHJldmlvdXMgdGltZXJcclxuXHJcblx0XHRcdC8vIFN0YXJ0IG5ldyBUaW1lclxyXG5cdFx0XHRjbG9zZWRfdGltZXIgPSBzZXRUaW1lb3V0KCB3cGJjX2FqeF9ib29raW5nX3NlbmRfc2VhcmNoX3JlcXVlc3RfZm9yX2tleXdvcmQuYmluZCggIG51bGwsIGVsZW1lbnRfaWQgKSwgdGltZXJfZGVsYXkgKTtcclxuXHRcdH1cclxuXHR9KCk7XHJcblxyXG5cclxuLyoqXHJcbiAqICAgRGVmaW5lIER5bmFtaWMgSG9va3MgIChsaWtlIHBhZ2luYXRpb24gY2xpY2ssIHdoaWNoIHJlbmV3IGVhY2ggdGltZSB3aXRoIG5ldyBsaXN0aW5nIHNob3dpbmcpICAtLS0tLS0tLS0tLS0tICovXHJcblxyXG4vKipcclxuICogRGVmaW5lIEhUTUwgdWkgSG9va3M6IG9uIEtleVVwIHwgQ2hhbmdlIHwgLT4gU29ydCBPcmRlciAmIE51bWJlciBJdGVtcyAvIFBhZ2VcclxuICogV2UgYXJlIGhjbmFnZWQgaXQgZWFjaCAgdGltZSwgd2hlbiBzaG93aW5nIG5ldyBsaXN0aW5nLCBiZWNhdXNlIERPTSBlbGVtZW50cyBjaG5hZ2VkXHJcbiAqL1xyXG5mdW5jdGlvbiB3cGJjX2FqeF9ib29raW5nX2RlZmluZV91aV9ob29rcygpe1xyXG5cclxuXHRpZiAoICdmdW5jdGlvbicgPT09IHR5cGVvZiggd3BiY19kZWZpbmVfdGlwcHlfdG9vbHRpcHMgKSApIHtcclxuXHRcdHdwYmNfZGVmaW5lX3RpcHB5X3Rvb2x0aXBzKCAnLndwYmNfbGlzdGluZ19jb250YWluZXIgJyApO1xyXG5cdH1cclxuXHJcblx0d3BiY19hanhfYm9va2luZ19fdWlfZGVmaW5lX19sb2NhbGUoKTtcclxuXHR3cGJjX2FqeF9ib29raW5nX191aV9kZWZpbmVfX3JlbWFyaygpO1xyXG5cclxuXHQvLyBJdGVtcyBQZXIgUGFnZVxyXG5cdGpRdWVyeSggJy53cGJjX2l0ZW1zX3Blcl9wYWdlJyApLm9uKCAnY2hhbmdlJywgZnVuY3Rpb24oIGV2ZW50ICl7XHJcblxyXG5cdFx0d3BiY19hanhfYm9va2luZ19zZW5kX3NlYXJjaF9yZXF1ZXN0X3dpdGhfcGFyYW1zKCB7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQncGFnZV9pdGVtc19jb3VudCcgIDogalF1ZXJ5KCB0aGlzICkudmFsKCksXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQncGFnZV9udW0nOiAxXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0fSApO1xyXG5cdH0gKTtcclxuXHJcblx0Ly8gU29ydGluZ1xyXG5cdGpRdWVyeSggJy53cGJjX2l0ZW1zX3NvcnRfdHlwZScgKS5vbiggJ2NoYW5nZScsIGZ1bmN0aW9uKCBldmVudCApe1xyXG5cclxuXHRcdHdwYmNfYWp4X2Jvb2tpbmdfc2VuZF9zZWFyY2hfcmVxdWVzdF93aXRoX3BhcmFtcyggeydzb3J0X3R5cGUnOiBqUXVlcnkoIHRoaXMgKS52YWwoKX0gKTtcclxuXHR9ICk7XHJcbn1cclxuXHJcblxyXG4vKipcclxuICogICBTaG93IC8gSGlkZSBMaXN0aW5nICAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0gKi9cclxuXHJcbi8qKlxyXG4gKiAgU2hvdyBMaXN0aW5nIFRhYmxlIFx0LSBcdFNlbmRpbmcgQWpheCBSZXF1ZXN0XHQtXHR3aXRoIHBhcmFtZXRlcnMgdGhhdCAgd2UgZWFybHkgIGRlZmluZWQgaW4gXCJ3cGJjX2FqeF9ib29raW5nX2xpc3RpbmdcIiBPYmouXHJcbiAqL1xyXG5mdW5jdGlvbiB3cGJjX2FqeF9ib29raW5nX19hY3R1YWxfbGlzdGluZ19fc2hvdygpe1xyXG5cclxuXHR3cGJjX2FqeF9ib29raW5nX2FqYXhfc2VhcmNoX3JlcXVlc3QoKTtcdFx0XHQvLyBTZW5kIEFqYXggUmVxdWVzdFx0LVx0d2l0aCBwYXJhbWV0ZXJzIHRoYXQgIHdlIGVhcmx5ICBkZWZpbmVkIGluIFwid3BiY19hanhfYm9va2luZ19saXN0aW5nXCIgT2JqLlxyXG59XHJcblxyXG4vKipcclxuICogSGlkZSBMaXN0aW5nIFRhYmxlICggYW5kIFBhZ2luYXRpb24gKVxyXG4gKi9cclxuZnVuY3Rpb24gd3BiY19hanhfYm9va2luZ19fYWN0dWFsX2xpc3RpbmdfX2hpZGUoKXtcclxuXHRqUXVlcnkoICcjd2hfc29ydF9zZWxlY3RvcicgKS5oaWRlKCk7XHJcblx0alF1ZXJ5KCB3cGJjX2FqeF9ib29raW5nX2xpc3RpbmcuZ2V0X290aGVyX3BhcmFtKCAnbGlzdGluZ19jb250YWluZXInICkgICAgKS5odG1sKCAnJyApO1xyXG5cdGpRdWVyeSggd3BiY19hanhfYm9va2luZ19saXN0aW5nLmdldF9vdGhlcl9wYXJhbSggJ3BhZ2luYXRpb25fY29udGFpbmVyJyApICkuaHRtbCggJycgKTtcclxufVxyXG5cclxuXHJcbi8qKlxyXG4gKiAgIFN1cHBvcnQgZnVuY3Rpb25zIGZvciBDb250ZW50IFRlbXBsYXRlIGRhdGEgIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLSAqL1xyXG5cclxuLyoqXHJcbiAqIEhpZ2hsaWdodCBzdHJpbmdzLFxyXG4gKiBieSBpbnNlcnRpbmcgPHNwYW4gY2xhc3M9XCJmaWVsZHZhbHVlIG5hbWUgZmllbGRzZWFyY2h2YWx1ZVwiPi4uLjwvc3Bhbj4gaHRtbCAgZWxlbWVudHMgaW50byB0aGUgc3RyaW5nLlxyXG4gKiBAcGFyYW0ge3N0cmluZ30gYm9va2luZ19kZXRhaWxzIFx0LSBTb3VyY2Ugc3RyaW5nXHJcbiAqIEBwYXJhbSB7c3RyaW5nfSBib29raW5nX2tleXdvcmRcdC0gS2V5d29yZCB0byBoaWdobGlnaHRcclxuICogQHJldHVybnMge3N0cmluZ31cclxuICovXHJcbmZ1bmN0aW9uIHdwYmNfZ2V0X2hpZ2hsaWdodGVkX3NlYXJjaF9rZXl3b3JkKCBib29raW5nX2RldGFpbHMsIGJvb2tpbmdfa2V5d29yZCApe1xyXG5cclxuXHRib29raW5nX2tleXdvcmQgPSBib29raW5nX2tleXdvcmQudHJpbSgpLnRvTG93ZXJDYXNlKCk7XHJcblx0aWYgKCAwID09IGJvb2tpbmdfa2V5d29yZC5sZW5ndGggKXtcclxuXHRcdHJldHVybiBib29raW5nX2RldGFpbHM7XHJcblx0fVxyXG5cclxuXHQvLyBIaWdobGlnaHQgc3Vic3RyaW5nIHdpdGhpbmcgSFRNTCB0YWdzIGluIFwiQ29udGVudCBvZiBib29raW5nIGZpZWxkcyBkYXRhXCIgLS0gZS5nLiBzdGFydGluZyBmcm9tICA+ICBhbmQgZW5kaW5nIHdpdGggPFxyXG5cdGxldCBrZXl3b3JkUmVnZXggPSBuZXcgUmVnRXhwKCBgZmllbGR2YWx1ZVtePD5dKj4oW148XSoke2Jvb2tpbmdfa2V5d29yZH1bXjxdKilgLCAnZ2ltJyApO1xyXG5cclxuXHQvL2xldCBtYXRjaGVzID0gWy4uLmJvb2tpbmdfZGV0YWlscy50b0xvd2VyQ2FzZSgpLm1hdGNoQWxsKCBrZXl3b3JkUmVnZXggKV07XHJcblx0bGV0IG1hdGNoZXMgPSBib29raW5nX2RldGFpbHMudG9Mb3dlckNhc2UoKS5tYXRjaEFsbCgga2V5d29yZFJlZ2V4ICk7XHJcblx0XHRtYXRjaGVzID0gQXJyYXkuZnJvbSggbWF0Y2hlcyApO1xyXG5cclxuXHRsZXQgc3RyaW5nc19hcnIgPSBbXTtcclxuXHRsZXQgcG9zX3ByZXZpb3VzID0gMDtcclxuXHRsZXQgc2VhcmNoX3Bvc19zdGFydDtcclxuXHRsZXQgc2VhcmNoX3Bvc19lbmQ7XHJcblxyXG5cdGZvciAoIGNvbnN0IG1hdGNoIG9mIG1hdGNoZXMgKXtcclxuXHJcblx0XHRzZWFyY2hfcG9zX3N0YXJ0ID0gbWF0Y2guaW5kZXggKyBtYXRjaFsgMCBdLnRvTG93ZXJDYXNlKCkuaW5kZXhPZiggJz4nLCAwICkgKyAxIDtcclxuXHJcblx0XHRzdHJpbmdzX2Fyci5wdXNoKCBib29raW5nX2RldGFpbHMuc3Vic3RyKCBwb3NfcHJldmlvdXMsIChzZWFyY2hfcG9zX3N0YXJ0IC0gcG9zX3ByZXZpb3VzKSApICk7XHJcblxyXG5cdFx0c2VhcmNoX3Bvc19lbmQgPSBib29raW5nX2RldGFpbHMudG9Mb3dlckNhc2UoKS5pbmRleE9mKCAnPCcsIHNlYXJjaF9wb3Nfc3RhcnQgKTtcclxuXHJcblx0XHRzdHJpbmdzX2Fyci5wdXNoKCAnPHNwYW4gY2xhc3M9XCJmaWVsZHZhbHVlIG5hbWUgZmllbGRzZWFyY2h2YWx1ZVwiPicgKyBib29raW5nX2RldGFpbHMuc3Vic3RyKCBzZWFyY2hfcG9zX3N0YXJ0LCAoc2VhcmNoX3Bvc19lbmQgLSBzZWFyY2hfcG9zX3N0YXJ0KSApICsgJzwvc3Bhbj4nICk7XHJcblxyXG5cdFx0cG9zX3ByZXZpb3VzID0gc2VhcmNoX3Bvc19lbmQ7XHJcblx0fVxyXG5cclxuXHRzdHJpbmdzX2Fyci5wdXNoKCBib29raW5nX2RldGFpbHMuc3Vic3RyKCBwb3NfcHJldmlvdXMsIChib29raW5nX2RldGFpbHMubGVuZ3RoIC0gcG9zX3ByZXZpb3VzKSApICk7XHJcblxyXG5cdHJldHVybiBzdHJpbmdzX2Fyci5qb2luKCAnJyApO1xyXG59XHJcblxyXG4vKipcclxuICogQ29udmVydCBzcGVjaWFsIEhUTUwgY2hhcmFjdGVycyAgIGZyb206XHQgJmFtcDsgXHQtPiBcdCZcclxuICpcclxuICogQHBhcmFtIHRleHRcclxuICogQHJldHVybnMgeyp9XHJcbiAqL1xyXG5mdW5jdGlvbiB3cGJjX2RlY29kZV9IVE1MX2VudGl0aWVzKCB0ZXh0ICl7XHJcblx0dmFyIHRleHRBcmVhID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCggJ3RleHRhcmVhJyApO1xyXG5cdHRleHRBcmVhLmlubmVySFRNTCA9IHRleHQ7XHJcblx0cmV0dXJuIHRleHRBcmVhLnZhbHVlO1xyXG59XHJcblxyXG4vKipcclxuICogQ29udmVydCBUTyBzcGVjaWFsIEhUTUwgY2hhcmFjdGVycyAgIGZyb206XHQgJiBcdC0+IFx0JmFtcDtcclxuICpcclxuICogQHBhcmFtIHRleHRcclxuICogQHJldHVybnMgeyp9XHJcbiAqL1xyXG5mdW5jdGlvbiB3cGJjX2VuY29kZV9IVE1MX2VudGl0aWVzKHRleHQpIHtcclxuICB2YXIgdGV4dEFyZWEgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCd0ZXh0YXJlYScpO1xyXG4gIHRleHRBcmVhLmlubmVyVGV4dCA9IHRleHQ7XHJcbiAgcmV0dXJuIHRleHRBcmVhLmlubmVySFRNTDtcclxufVxyXG5cclxuXHJcbi8qKlxyXG4gKiAgIFN1cHBvcnQgRnVuY3Rpb25zIC0gU3BpbiBJY29uIGluIEJ1dHRvbnMgIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLSAqL1xyXG5cclxuLyoqXHJcbiAqIFNwaW4gYnV0dG9uIGluIEZpbHRlciB0b29sYmFyICAtICBTdGFydFxyXG4gKi9cclxuZnVuY3Rpb24gd3BiY19ib29raW5nX2xpc3RpbmdfcmVsb2FkX2J1dHRvbl9fc3Bpbl9zdGFydCgpe1xyXG5cdGpRdWVyeSggJyN3cGJjX2Jvb2tpbmdfbGlzdGluZ19yZWxvYWRfYnV0dG9uIC5tZW51X2ljb24ud3BiY19zcGluJykucmVtb3ZlQ2xhc3MoICd3cGJjX2FuaW1hdGlvbl9wYXVzZScgKTtcclxufVxyXG5cclxuLyoqXHJcbiAqIFNwaW4gYnV0dG9uIGluIEZpbHRlciB0b29sYmFyICAtICBQYXVzZVxyXG4gKi9cclxuZnVuY3Rpb24gd3BiY19ib29raW5nX2xpc3RpbmdfcmVsb2FkX2J1dHRvbl9fc3Bpbl9wYXVzZSgpe1xyXG5cdGpRdWVyeSggJyN3cGJjX2Jvb2tpbmdfbGlzdGluZ19yZWxvYWRfYnV0dG9uIC5tZW51X2ljb24ud3BiY19zcGluJyApLmFkZENsYXNzKCAnd3BiY19hbmltYXRpb25fcGF1c2UnICk7XHJcbn1cclxuXHJcbi8qKlxyXG4gKiBTcGluIGJ1dHRvbiBpbiBGaWx0ZXIgdG9vbGJhciAgLSAgaXMgU3Bpbm5pbmcgP1xyXG4gKlxyXG4gKiBAcmV0dXJucyB7Ym9vbGVhbn1cclxuICovXHJcbmZ1bmN0aW9uIHdwYmNfYm9va2luZ19saXN0aW5nX3JlbG9hZF9idXR0b25fX2lzX3NwaW4oKXtcclxuICAgIGlmICggalF1ZXJ5KCAnI3dwYmNfYm9va2luZ19saXN0aW5nX3JlbG9hZF9idXR0b24gLm1lbnVfaWNvbi53cGJjX3NwaW4nICkuaGFzQ2xhc3MoICd3cGJjX2FuaW1hdGlvbl9wYXVzZScgKSApe1xyXG5cdFx0cmV0dXJuIHRydWU7XHJcblx0fSBlbHNlIHtcclxuXHRcdHJldHVybiBmYWxzZTtcclxuXHR9XHJcbn0iXSwiZmlsZSI6ImluY2x1ZGVzL3BhZ2UtYm9va2luZ3MvX291dC9ib29raW5nc19fbGlzdGluZy5qcyJ9
