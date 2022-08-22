/**
 * VikBooking Core v1.5.1
 * Copyright (C) 2022 E4J s.r.l. All Rights Reserved.
 * http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * https://vikwp.com | https://e4j.com | https://e4jconnect.com
 */

window['VBOCore'] = class VBOCore {

	/**
	 * Proxy to support static injection of params.
	 */
	constructor(params) {
		if (typeof params === 'object') {
			VBOCore.setOptions(params);
		}
	}

	/**
	 * Inject options by overriding default properties.
	 * 
	 * @param 	object 	params
	 * 
	 * @return 	self
	 */
	static setOptions(params) {
		if (typeof params === 'object') {
			VBOCore.options = Object.assign(VBOCore.options, params);
		}

		return VBOCore;
	}

	/**
	 * Getter for admin_widgets private options property.
	 * 
	 * @return 	array
	 */
	static get admin_widgets() {
		return VBOCore.options.admin_widgets;
	}

	/**
	 * Getter for multitask open event private property.
	 * 
	 * @return 	string
	 */
	static get multitask_open_event() {
		return VBOCore.options.multitask_open_event;
	}

	/**
	 * Getter for multitask close event private property.
	 * 
	 * @return 	string
	 */
	static get multitask_close_event() {
		return VBOCore.options.multitask_close_event;
	}

	/**
	 * Getter for multitask shortcut event private property.
	 * 
	 * @return 	string
	 */
	static get multitask_shortcut_event() {
		return VBOCore.options.multitask_shortcut_ev;
	}

	/**
	 * Getter for multitask seach focus shortcut event private property.
	 * 
	 * @return 	string
	 */
	static get multitask_searchfs_event() {
		return VBOCore.options.multitask_searchfs_ev;
	}

	/**
	 * Parses an AJAX response error object.
	 * 
	 * @param 	object  err
	 * 
	 * @return  bool
	 */
	static isConnectionLostError(err) {
		if (!err || !err.hasOwnProperty('status')) {
			return false;
		}

		return (
			err.statusText == 'error'
			&& err.status == 0
			&& (err.readyState == 0 || err.readyState == 4)
			&& (!err.hasOwnProperty('responseText') || err.responseText == '')
		);
	}

	/**
	 * Ensures AJAX requests that fail due to connection errors are retried automatically.
	 * 
	 * @param 	string  	url
	 * @param 	object 		data
	 * @param 	function 	success
	 * @param 	function 	failure
	 * @param 	number 		attempt
	 */
	static doAjax(url, data, success, failure, attempt) {
		const AJAX_MAX_ATTEMPTS = 3;

		if (attempt === undefined) {
			attempt = 1;
		}

		return jQuery.ajax({
			type: 'POST',
			url: url,
			data: data
		}).done(function(resp) {
			if (success !== undefined) {
				// launch success callback function
				success(resp);
			}
		}).fail(function(err) {
			/**
			 * If the error is caused by a site connection lost, and if the number
			 * of retries is lower than max attempts, retry the same AJAX request.
			 */
			if (attempt < AJAX_MAX_ATTEMPTS && VBOCore.isConnectionLostError(err)) {
				// delay the retry by half second
				setTimeout(function() {
					// re-launch same request and increase number of attempts
					console.log('Retrying previous AJAX request');
					VBOCore.doAjax(url, data, success, failure, (attempt + 1));
				}, 500);
			} else {
				// launch the failure callback otherwise
				if (failure !== undefined) {
					failure(err);
				}
			}

			// always log the error in console
			console.log('AJAX request failed' + (err.status == 500 ? ' (' + err.responseText + ')' : ''), err);
		});
	}

	/**
	 * Matches a keyword against a text.
	 * 
	 * @param 	string 	search 	the keyword to search.
	 * @param 	string 	text 	the text to compare.
	 * 
	 * @return 	bool
	 */
	static matchString(search, text) {
		return ((text + '').indexOf(search) >= 0);
	}

	/**
	 * Initializes the multitasking panel for the admin widgets.
	 * 
	 * @param 	object 	params 	the panel object params.
	 * 
	 * @return 	bool
	 */
	static prepareMultitasking(params) {
		var panel_opts = {
			selector: 		 "",
			sclass_l_small:  "vbo-sidepanel-right",
			sclass_l_large:  "vbo-sidepanel-large",
			btn_trigger: 	 "",
			search_selector: "#vbo-sidepanel-search-input",
			search_nores: 	 ".vbo-sidepanel-add-widgets-nores",
			close_selector:  ".vbo-sidepanel-dismiss-btn",
			t_layout_small:	 ".vbo-sidepanel-layout-small",
			t_layout_large:  ".vbo-sidepanel-layout-large",
			wclass_base_sel: ".vbo-admin-widgets-widget-output",
			wclass_l_small:  "vbo-admin-widgets-container-small",
			wclass_l_large:  "vbo-admin-widgets-container-large",
			addws_selector:	 ".vbo-sidepanel-add-widgets",
			addw_selector:	 ".vbo-sidepanel-add-widget",
			addwfs_selector: ".vbo-sidepanel-add-widget-focussed",
			wtags_selector:	 ".vbo-sidepanel-widget-tags",
			addw_data_attr:  "data-vbowidgetid",
			actws_selector:  ".vbo-sidepanel-active-widgets",
			editw_selector:  ".vbo-sidepanel-edit-widgets-trig",
			rmwidget_class:  "vbo-admin-widgets-widget-remove",
			rmwidget_icn: 	 "",
			notif_selector:  ".vbo-sidepanel-notifications-btn",
			notif_on_class:  "vbo-sidepanel-notifications-on",
			notif_off_class: "vbo-sidepanel-notifications-off",
			open_class: 	 "vbo-sidepanel-open",
			close_class: 	 "vbo-sidepanel-close",
			cur_widget_cls:  "vbo-admin-widgets-container-small",
		};

		if (typeof params === 'object') {
			panel_opts = Object.assign(panel_opts, params);
		}

		if (!panel_opts.btn_trigger || !panel_opts.selector) {
			console.error('Got no trigger or selector');
			return false;
		}

		// push panel options
		VBOCore.setOptions({
			panel_opts: panel_opts,
		});

		// setup browser notifications
		VBOCore.setupNotifications();

		// count active widgets on current page
		var tot_active_widgets = VBOCore.options.admin_widgets.length;
		if (tot_active_widgets > 0) {
			// hide add-widgets container
			jQuery(panel_opts.addws_selector).hide();

			// register listener for input search blur
			VBOCore.registerSearchWidgetsBlur();
		}

		// register click event on trigger button
		jQuery(VBOCore.options.panel_opts.btn_trigger).on('click', function() {
			var side_panel = jQuery(VBOCore.options.panel_opts.selector);
			if (side_panel.hasClass(VBOCore.options.panel_opts.open_class)) {
				// hide panel
				VBOCore.side_panel_on = false;
				VBOCore.emitMultitaskEvent(VBOCore.multitask_close_event);
				side_panel.addClass(VBOCore.options.panel_opts.close_class).removeClass(VBOCore.options.panel_opts.open_class);
				// always hide add-widgets container
				jQuery(VBOCore.options.panel_opts.addws_selector).hide();
				// check if we are currently editing
				var is_editing = (jQuery('.' + VBOCore.options.panel_opts.editmode_class).length > 0);
				if (is_editing) {
					// deactivate editing mode
					VBOCore.toggleWidgetsPanelEditing(null);
				}
			} else {
				// show panel
				VBOCore.side_panel_on = true;
				VBOCore.emitMultitaskEvent(VBOCore.multitask_open_event);
				side_panel.addClass(VBOCore.options.panel_opts.open_class).removeClass(VBOCore.options.panel_opts.close_class);
				if (!VBOCore.options.admin_widgets.length) {
					// set focus on search widgets input with delay for the opening animation
					setTimeout(function() {
						jQuery(VBOCore.options.panel_opts.search_selector).focus();
					}, 300);
				}
			}
		});

		// register close/dismiss button
		jQuery(VBOCore.options.panel_opts.close_selector).on('click', function() {
			jQuery(VBOCore.options.panel_opts.btn_trigger).trigger('click');
		});

		// register toggle layout buttons
		jQuery(VBOCore.options.panel_opts.t_layout_large).on('click', function() {
			// large layout
			jQuery(VBOCore.options.panel_opts.selector).addClass(VBOCore.options.panel_opts.sclass_l_large).removeClass(VBOCore.options.panel_opts.sclass_l_small);
			jQuery(VBOCore.options.panel_opts.wclass_base_sel).addClass(VBOCore.options.panel_opts.wclass_l_large).removeClass(VBOCore.options.panel_opts.wclass_l_small);
			VBOCore.options.panel_opts.cur_widget_cls = VBOCore.options.panel_opts.sclass_l_large;
		});
		jQuery(VBOCore.options.panel_opts.t_layout_small).on('click', function() {
			// small layout
			jQuery(VBOCore.options.panel_opts.selector).addClass(VBOCore.options.panel_opts.sclass_l_small).removeClass(VBOCore.options.panel_opts.sclass_l_large);
			jQuery(VBOCore.options.panel_opts.wclass_base_sel).addClass(VBOCore.options.panel_opts.wclass_l_small).removeClass(VBOCore.options.panel_opts.wclass_l_large);
			VBOCore.options.panel_opts.cur_widget_cls = VBOCore.options.panel_opts.sclass_l_small;
		});

		// register listener for esc key pressed
		jQuery(document).keyup(function(e) {
			if (!VBOCore.side_panel_on) {
				return;
			}
			if ((e.key && e.key === "Escape") || (e.keyCode && e.keyCode == 27)) {
				jQuery(VBOCore.options.panel_opts.btn_trigger).trigger('click');
			}
		});

		// register listener for input search focus
		jQuery(VBOCore.options.panel_opts.search_selector).on('focus', function() {
			// always show add-widgets container
			var widget_focus_class = VBOCore.options.panel_opts.addwfs_selector.replace('.', '');
			jQuery(VBOCore.options.panel_opts.addw_selector).removeClass(widget_focus_class);
			jQuery(VBOCore.options.panel_opts.addws_selector).show();
		});

		// register listener on input search widget
		jQuery(VBOCore.options.panel_opts.search_selector).keyup(function(e) {
			// get the keyword to look for
			var keyword = jQuery(this).val();
			// counting matching widgets
			var matching = 0;
			var first_matched = null;
			var widget_focus_class = VBOCore.options.panel_opts.addwfs_selector.replace('.', '');

			// adjust widgets to be displayed
			if (!keyword.length) {
				// show all widgets for selection
				jQuery(VBOCore.options.panel_opts.addw_selector).show();
				// hide "no results"
				jQuery(VBOCore.options.panel_opts.search_nores).hide();
				// all widgets are matching
				matching = jQuery(VBOCore.options.panel_opts.addw_selector).length;
			} else {
				// make the keyword lowercase
				keyword = (keyword + '').toLowerCase();
				// parse all widget's description tags
				jQuery(VBOCore.options.panel_opts.addw_selector).each(function() {
					var elem  = jQuery(this);
					var descr = elem.find(VBOCore.options.panel_opts.wtags_selector).text();
					if (VBOCore.matchString(keyword, descr)) {
						elem.show();
						matching++;
						if (!first_matched) {
							// store the first widget that matched
							first_matched = elem.attr(VBOCore.options.panel_opts.addw_data_attr);
						}
					} else {
						elem.hide();
					}
				});
				// check how many widget matched
				if (matching > 0) {
					// hide "no results"
					jQuery(VBOCore.options.panel_opts.search_nores).hide();
				} else {
					// show "no results"
					jQuery(VBOCore.options.panel_opts.search_nores).show();
				}
			}

			// check for shortcuts
			if (!e.key) {
				return;
			}

			// handle Enter key press to add a widget
			if (e.key === "Enter") {
				// on Enter key pressed, add the first matching widget or the focussed one
				var focussed_wid = jQuery(VBOCore.options.panel_opts.addwfs_selector + ':visible').first();
				if (focussed_wid.length) {
					VBOCore.addWidgetToPanel(focussed_wid.attr(VBOCore.options.panel_opts.addw_data_attr));
					jQuery(VBOCore.options.panel_opts.search_selector).trigger('blur');
				} else if (first_matched) {
					VBOCore.addWidgetToPanel(first_matched);
					jQuery(VBOCore.options.panel_opts.search_selector).trigger('blur');
				}
				return;
			}

			// handle arrow keys selection
			if (matching > 0 && (e.key === 'ArrowDown' || e.key === 'ArrowUp')) {
				// on arrow key pressed, select the next or prev widget
				var addws_element  = jQuery(VBOCore.options.panel_opts.addws_selector);
				var addws_cont_pos = addws_element.offset().top;
				var addws_otheight = addws_element.outerHeight();
				var addws_scrolltp = addws_element.scrollTop();

				if (e.key === 'ArrowDown') {
					var default_widg = jQuery(VBOCore.options.panel_opts.addw_selector + ':visible').first();
				} else {
					var default_widg = jQuery(VBOCore.options.panel_opts.addw_selector + ':visible').last();
				}
				var focussed_wid = jQuery(VBOCore.options.panel_opts.addwfs_selector + ':visible').first();
				var addw_height  = default_widg.outerHeight();
				var focussed_pos = default_widg.offset().top + addw_height;

				if (focussed_wid.length) {
					focussed_wid.removeClass(widget_focus_class);
					if (e.key === 'ArrowDown') {
						var goto_wid = focussed_wid.nextAll(VBOCore.options.panel_opts.addw_selector + ':visible').first();
					} else {
						var goto_wid = focussed_wid.prevAll(VBOCore.options.panel_opts.addw_selector + ':visible').first();
					}
					if (goto_wid.length) {
						goto_wid.addClass(widget_focus_class);
						focussed_pos = goto_wid.offset().top + addw_height;
					} else {
						default_widg.addClass(widget_focus_class);
					}
				} else {
					default_widg.addClass(widget_focus_class);
				}

				if (focussed_pos > (addws_cont_pos + addws_otheight)) {
					addws_element.scrollTop(focussed_pos - addws_cont_pos - addw_height + addws_scrolltp);
				} else if (focussed_pos < 0) {
					addws_element.scrollTop(0);
				}
			}
		});

		// register listener for adding widgets
		jQuery(VBOCore.options.panel_opts.addw_selector).on('click', function() {
			var widget_id = jQuery(this).attr(VBOCore.options.panel_opts.addw_data_attr);
			if (!widget_id || !widget_id.length) {
				return false;
			}
			VBOCore.addWidgetToPanel(widget_id);
		});

		// register listener for updating multitask sidepanel with debounce
		document.addEventListener(VBOCore.options.multitask_save_event, VBOCore.debounceEvent(VBOCore.saveMultitasking, 1000));

		// subscribe to event for multitask shortcut
		document.addEventListener(VBOCore.multitask_shortcut_event, function() {
			// toggle multitask panel
			jQuery(VBOCore.options.panel_opts.btn_trigger).trigger('click');
		});

		// subscribe to event for multitask search focus shortcut
		document.addEventListener(VBOCore.multitask_searchfs_event, function() {
			// focus search multitask widgets
			jQuery(VBOCore.options.panel_opts.search_selector).trigger('focus');
		});

		// register click event on edit widgets button
		jQuery(VBOCore.options.panel_opts.editw_selector).on('click', function() {
			VBOCore.toggleWidgetsPanelEditing(null);
		});
	}

	/**
	 * Registers the blur event for the search widgets input.
	 */
	static registerSearchWidgetsBlur() {
		if (VBOCore.options.active_listeners.hasOwnProperty('registerSearchWidgetsBlur')) {
			// listener is already registered
			return true;
		}

		jQuery(VBOCore.options.panel_opts.search_selector).on('blur', function(e) {
			if (e && e.relatedTarget) {
				if (e.relatedTarget.classList.contains(VBOCore.options.panel_opts.addw_selector.replace('.', ''))) {
					// add new widget was clicked, abort hiding process or click event won't fire on target element
					return;
				}
			}
			var keyword = jQuery(this).val();
			if (!keyword.length) {
				// hide add-widgets container
				jQuery(VBOCore.options.panel_opts.addws_selector).hide();
			}
		});

		// register flag for listener active
		VBOCore.options.active_listeners['registerSearchWidgetsBlur'] = 1;
	}

	/**
	 * Removes the blur event handler for the search widgets input.
	 */
	static unregisterSearchWidgetsBlur() {
		if (!VBOCore.options.active_listeners.hasOwnProperty('registerSearchWidgetsBlur')) {
			// nothing to unregister
			return true;
		}

		jQuery(VBOCore.options.panel_opts.search_selector).off('blur');

		// delete flag for listener active
		delete VBOCore.options.active_listeners['registerSearchWidgetsBlur'];
	}

	/**
	 * Adds a widget identifier to the multitask panel.
	 * 
	 * @param 	string 	widget_id 	the widget identifier string to add.
	 */
	static addWidgetToPanel(widget_id) {
		// prepend container to panel
		var widget_classes = [VBOCore.options.panel_opts.wclass_base_sel.replace('.', ''), VBOCore.options.panel_opts.cur_widget_cls];
		var widget_div = '<div class="' + widget_classes.join(' ') + ' " ' + VBOCore.options.panel_opts.addw_data_attr + '="' + widget_id + '" style="display: none;"></div>';
		var widget_elem = jQuery(widget_div);
		jQuery(VBOCore.options.panel_opts.actws_selector).prepend(widget_elem);

		// always hide add-widgets container
		jQuery(VBOCore.options.panel_opts.addws_selector).hide();

		// trigger debounced map saving event
		VBOCore.emitMultitaskEvent();

		// register listener for input search blur
		VBOCore.registerSearchWidgetsBlur();

		// render widget
		var call_method = 'render';
		VBOCore.doAjax(
			VBOCore.options.widget_ajax_uri,
			{
				widget_id: widget_id,
				call: 	   call_method,
				vbo_page:  VBOCore.options.current_page,
				vbo_uri:   VBOCore.options.current_page_uri,
				multitask: 1,
			},
			function(response) {
				// display widgets editing button
				VBOCore.toggleWidgetsPanelEditing(true);
				// parse response
				try {
					var obj_res = typeof response === 'string' ? JSON.parse(response) : response;
					if (obj_res.hasOwnProperty(call_method)) {
						// populate widget HTML content and display it
						widget_elem.html(obj_res[call_method]).fadeIn();
						// always scroll active widgets list to top
						jQuery(VBOCore.options.panel_opts.actws_selector).scrollTop(0);
					} else {
						console.error('Unexpected JSON response', obj_res);
					}
				} catch(err) {
					console.error('could not parse JSON response', err, response);
				}
			},
			function(error) {
				console.error(error);
			}
		);
	}

	/**
	 * Toggles the edit mode of the multitask widgets panel.
	 * 
	 * @param 	bool 	added 	true if a widget was just added, false if it was just removed.
	 */
	static toggleWidgetsPanelEditing(added) {
		// check if we are currently editing
		var is_editing = (jQuery('.' + VBOCore.options.panel_opts.editmode_class).length > 0);

		// check added action status
		if (added === true) {
			// show button for edit mode
			jQuery(VBOCore.options.panel_opts.editw_selector).show();
			return;
		}

		// grab all widgets
		var editing_widgets = jQuery(VBOCore.options.panel_opts.wclass_base_sel);

		if (added === false) {
			if (!editing_widgets.length) {
				// hide button for edit mode after removing the last widget
				jQuery(VBOCore.options.panel_opts.editw_selector).hide();
			}
			return;
		}

		if (is_editing) {
			// deactivate editing mode
			editing_widgets.removeClass(VBOCore.options.panel_opts.editmode_class);
			jQuery('.' + VBOCore.options.panel_opts.rmwidget_class).remove();
		} else {
			// activate editing mode by looping through all widgets
			editing_widgets.each(function() {
				// build remove-widget element
				var rm_widget = jQuery('<div></div>').addClass(VBOCore.options.panel_opts.rmwidget_class).on('click', function() {
					VBOCore.removeWidgetFromPanel(this);
				}).html(VBOCore.options.panel_opts.rmwidget_icn);
				// add editing class and prepend removing element
				jQuery(this).addClass(VBOCore.options.panel_opts.editmode_class).prepend(rm_widget);
			});
		}
	}

	/**
	 * Handles the removal of a widget from the multitask panel.
	 * 
	 * @param 	object 	element
	 */
	static removeWidgetFromPanel(element) {
		if (!element) {
			console.error('Invalid widget element to remove', element);
			return false;
		}
		var widget_cont = jQuery(element).parent(VBOCore.options.panel_opts.wclass_base_sel);
		if (!widget_cont || !widget_cont.length) {
			console.error('Could not find widget container to remove', element);
			return false;
		}
		var widget_id = widget_cont.attr(VBOCore.options.panel_opts.addw_data_attr);
		if (!widget_id || !widget_id.length) {
			console.error('Empty widget id to remove', element);
			return false;
		}
		// find the index of the widget to remove in the panel
		var widget_index = jQuery(VBOCore.options.panel_opts.wclass_base_sel).index(widget_cont);
		if (widget_index < 0) {
			console.error('Empty widget index to remove', widget_cont);
			return false;
		}
		// make sure the index in the array matches the id
		if (!VBOCore.options.admin_widgets.hasOwnProperty(widget_index) || VBOCore.options.admin_widgets[widget_index]['id'] != widget_id) {
			console.error('Unmatching widget index or id', VBOCore.options.admin_widgets, widget_index, widget_id);
			return false;
		}
		// remove this widget from the array
		VBOCore.options.admin_widgets.splice(widget_index, 1);

		// remove element from document
		widget_cont.remove();

		// check widgets editing button status
		VBOCore.toggleWidgetsPanelEditing(false);

		// trigger debounced map saving event
		VBOCore.emitMultitaskEvent();

		if (!VBOCore.options.admin_widgets.length) {
			// unregister listener for input search blur
			VBOCore.unregisterSearchWidgetsBlur();
		}
	}

	/**
	 * Emits an event related to the multitask features.
	 */
	static emitMultitaskEvent(ev_name) {
		var def_ev_name = VBOCore.options.multitask_save_event;
		if (typeof ev_name === 'string') {
			def_ev_name = ev_name;
		}

		// trigger the event
		document.dispatchEvent(new Event(def_ev_name));
	}

	/**
	 * Attempts to save the multitask widgets for this page.
	 */
	static saveMultitasking() {
		// gather the list of active widgets
		var active_widgets = [];
		var cur_admin_widgets = [];
		jQuery(VBOCore.options.panel_opts.actws_selector).find(VBOCore.options.panel_opts.wclass_base_sel).each(function() {
			var widget_id = jQuery(this).attr(VBOCore.options.panel_opts.addw_data_attr);
			if (widget_id && widget_id.length) {
				// push id in list
				active_widgets.push(widget_id);
				// push object with dummy name for global widgets
				cur_admin_widgets.push({
					id: widget_id,
					name: widget_id,
				});
			}
		});

		// update multitask widgets map for this page
		VBOCore.doAjax(
			VBOCore.options.multitask_ajax_uri,
			{
				call: 'updateMultitaskingMap',
				call_args: [
					VBOCore.options.current_page,
					active_widgets,
					0
				],
			},
			function(response) {
				try {
					var obj_res = typeof response === 'string' ? JSON.parse(response) : response;
					if (obj_res.hasOwnProperty('result') && obj_res['result']) {
						// set current widgets
						VBOCore.setOptions({
							admin_widgets: cur_admin_widgets
						});
					} else {
						console.error('Unexpected or invalid JSON response', response);
					}
				} catch(err) {
					console.error('could not parse JSON response', err, response);
				}
			},
			function(error) {
				console.error(error);
			}
		);
	}

	/**
	 * Setups the browser notifications, if supported.
	 */
	static setupNotifications() {
		if (!('Notification' in window)) {
			// browser does not support notifications
			jQuery(VBOCore.options.panel_opts.notif_selector).hide();
			return false;
		}

		if (Notification.permission && Notification.permission === 'granted') {
			// permissions were granted already
			jQuery(VBOCore.options.panel_opts.notif_selector)
				.addClass(VBOCore.options.panel_opts.notif_on_class)
				.attr('title', VBOCore.options.tn_texts.notifs_enabled);
			return true;
		}

		// notifications supported, but perms not granted
		jQuery(VBOCore.options.panel_opts.notif_selector)
			.addClass(VBOCore.options.panel_opts.notif_off_class)
			.attr('title', VBOCore.options.tn_texts.notifs_disabled);

		// register click-event listener on button to enable notifications
		jQuery(VBOCore.options.panel_opts.notif_selector).on('click', function() {
			VBOCore.requestNotifPerms();
		});

		// subscribe to the multitask-panel-open event to show the status of the notifications
		document.addEventListener(VBOCore.multitask_open_event, function() {
			if (VBOCore.notificationsEnabled() === false) {
				// add "shaking" class to notifications button
				jQuery(VBOCore.options.panel_opts.notif_selector).addClass('shaking');
			}
		});

		// subscribe to the multitask-panel-close event to update the status of the notifications
		document.addEventListener(VBOCore.multitask_close_event, function() {
			// always remove "shaking" class from notifications button
			jQuery(VBOCore.options.panel_opts.notif_selector).removeClass('shaking');
		});
	}

	/**
	 * Tells whether the notifications are enabled, disabled, not supported.
	 */
	static notificationsEnabled() {
		if (!('Notification' in window)) {
			// not supported
			return null;
		}

		if (Notification.permission && Notification.permission === 'granted') {
			// enabled
			return true;
		}

		// disabled
		return false;
	}

	/**
	 * Attempts to request the notifications permissions to the browser.
	 * For security reasons, this should run upon a user gesture (click).
	 */
	static requestNotifPerms() {
		if (!('Notification' in window)) {
			// browser does not support notifications
			return false;
		}

		// run permissions request in a try-catch statement to support all browsers
		try {
			// handle promise-based version to request permissions
			Notification.requestPermission().then((permission) => {
				VBOCore.handleNotifPerms(permission);
			});
		} catch(e) {
			// run the callback-based version
			Notification.requestPermission(function(permission) {
				VBOCore.handleNotifPerms(permission);
			});
		}
	}

	/**
	 * Handles the notifications permission response (from callback or promise resolved).
	 */
	static handleNotifPerms(permission) {
		// check the permission status from the Notification object interface
		if ((Notification.permission && Notification.permission === 'granted') || (typeof permission === 'string' && permission === 'granted')) {
			// permissions granted!
			jQuery(VBOCore.options.panel_opts.notif_selector)
				.removeClass(VBOCore.options.panel_opts.notif_off_class)
				.addClass(VBOCore.options.panel_opts.notif_on_class)
				.attr('title', VBOCore.options.tn_texts.notifs_enabled);
			return true;
		} else {
			// permissions denied :(
			jQuery(VBOCore.options.panel_opts.notif_selector)
				.removeClass(VBOCore.options.panel_opts.notif_on_class)
				.addClass(VBOCore.options.panel_opts.notif_off_class)
				.attr('title', VBOCore.options.tn_texts.notifs_disabled);
			// show alert message
			console.error('Permission denied for enabling browser notifications', permission);
			alert(VBOCore.options.tn_texts.notifs_disabled_help);
		}

		return false;
	}

	/**
	 * Given a date-time string, returns a Date object representation.
	 * 
	 * @param 	string 	dtime_str 	the date-time string in "Y-m-d H:i:s" format.
	 */
	static getDateTimeObject(dtime_str) {
		// instantiate a new date object
		var date_obj = new Date();

		// parse date-time string
		let dtime_parts = dtime_str.split(' ');
		let date_parts  = dtime_parts[0].split('-');
		if (dtime_parts.length != 2 || date_parts.length != 3) {
			// invalid military format
			return date_obj;
		}
		let time_parts = dtime_parts[1].split(':');

		// set accurate date-time values
		date_obj.setFullYear(date_parts[0]);
		date_obj.setMonth((parseInt(date_parts[1]) - 1));
		date_obj.setDate(parseInt(date_parts[2]));
		date_obj.setHours(parseInt(time_parts[0]));
		date_obj.setMinutes(parseInt(time_parts[1]));
		date_obj.setSeconds(0);
		date_obj.setMilliseconds(0);

		// return the accurate date object
		return date_obj;
	}

	/**
	 * Given a list of schedules, enqueues notifications to watch.
	 * 
	 * @param 	array|object 	schedules 	list of or one notification object(s).
	 * 
	 * @return 	bool
	 */
	static enqueueNotifications(schedules) {
		if (!Array.isArray(schedules) || !schedules.length) {
			if (typeof schedules === 'object' && schedules.hasOwnProperty('dtime')) {
				// convert the single schedule to an array
				schedules = [schedules];
			} else {
				// invalid argument passed
				return false;
			}
		}

		for (var i in schedules) {
			if (!schedules.hasOwnProperty(i) || typeof schedules[i] !== 'object') {
				continue;
			}
			VBOCore.notifications.push(schedules[i]);
		}

		// setup the timeouts to schedule the notifications
		return VBOCore.scheduleNotifications();
	}

	/**
	 * Schedule the trigger timings for each notification.
	 */
	static scheduleNotifications() {
		if (!VBOCore.notifications.length) {
			// no notifications to be scheduled
			return false;
		}
		if (VBOCore.notificationsEnabled() !== true) {
			// notifications not enabled
			console.info('Browser notifications disabled or unsupported.');
		}

		// gather current date-timing information
		const now_date = new Date();
		const now_time = now_date.getTime();

		// parse all notifications to schedule the timers if not set
		for (let i = 0; i < VBOCore.notifications.length; i++) {
			let notif = VBOCore.notifications[i];

			if (typeof notif !== 'object' || !notif.hasOwnProperty('dtime')) {
				// invalid notification object, unset it
				VBOCore.notifications.splice(i, 1);
				continue;
			}

			// check if timer has been set
			if (!notif.hasOwnProperty('id_timer')) {
				// estimate trigger timing
				let in_ms = 0;
				// check for imminent notifications
				if (typeof notif.dtime === 'string' && notif.dtime.indexOf('now') >= 0) {
					// imminent ones will be delayed by one second
					in_ms = 1000;
				} else {
					// check overdue date-time (notif.dtime can also be a Date object instance)
					let nexp = VBOCore.getDateTimeObject(notif.dtime);
					in_ms = nexp.getTime() - now_time;
				}
				if (in_ms > 0) {
					// schedule notification trigger
					let id_timer = setTimeout(() => {
						VBOCore.dispatchNotification(notif);
					}, in_ms);
					// set timer on notification object
					VBOCore.notifications[i]['id_timer'] = id_timer;
				}
			}
		}

		return true;
	}

	/**
	 * Deregister all scheduled notifications.
	 */
	static unscheduleNotifications() {
		if (!VBOCore.notifications.length) {
			// no notifications scheduled
			return false;
		}

		for (let i = 0; i < VBOCore.notifications.length; i++) {
			let notif = VBOCore.notifications[i];

			if (typeof notif === 'object' && notif.hasOwnProperty('id_timer')) {
				// unset timeout for this notification
				clearTimeout(notif['id_timer']);
			}
		}

		// reset pool
		VBOCore.notifications = [];
	}

	/**
	 * Update or delete a previously scheduled notification.
	 * 
	 * @param 	object 			match_props  map of properties to match.
	 * @param 	string|number  	newdtime 	 the new date time to schedule (0 for deleting).
	 * 
	 * @return 	null|bool 					 true only if a notification matched.
	 */
	static updateNotification(match_props, newdtime) {
		if (!VBOCore.notifications.length) {
			// no notifications set, terminate
			return null;
		}

		if (typeof match_props !== 'object') {
			// no properties to match the notification
			return null;
		}

		// gather current date-timing information
		const now_date = new Date();
		const now_time = now_date.getTime();

		// parse all notifications scheduled
		for (let i = 0; i < VBOCore.notifications.length; i++) {
			let notif = VBOCore.notifications[i];

			let all_matched = true;
			let to_matching = false;
			for (let prop in match_props) {
				if (!match_props.hasOwnProperty(prop)) {
					continue;
				}
				to_matching = true;
				if (!notif.hasOwnProperty(prop) || notif[prop] != match_props[prop]) {
					all_matched = false;
					break;
				}
			}

			if (all_matched && to_matching) {
				// notification object found
				if (notif.hasOwnProperty('id_timer')) {
					// unset previous timeout for this notification
					clearTimeout(notif['id_timer']);
				}
				// update or delete scheduled notification
				if (newdtime === 0) {
					// delete notification from queue
					VBOCore.notifications.splice(i, 1);
				} else {
					// update timing scheduler
					let in_ms = 0;
					// check for imminent notifications
					if (typeof newdtime === 'string' && newdtime.indexOf('now') >= 0) {
						// imminent ones will be delayed by one second
						in_ms = 1000;
					} else {
						// check overdue date-time (newdtime can also be a Date object instance)
						let nexp = VBOCore.getDateTimeObject(newdtime);
						in_ms = nexp.getTime() - now_time;
					}
					if (in_ms > 0) {
						// schedule notification trigger
						let id_timer = setTimeout(() => {
							VBOCore.dispatchNotification(notif);
						}, in_ms);
						// set timer on notification object
						VBOCore.notifications[i]['id_timer'] = id_timer;
					}
					// update date-time value on notification object
					VBOCore.notifications[i]['dtime'] = newdtime;
				}

				// terminate parsing and return true
				return true;
			}
		}

		// notification object not found
		return false;
	}

	/**
	 * Dispatch the notification object.
	 * Expected notification properties:
	 * 
	 * {
	 *		id: 		number
	 * 		type: 		string
	 * 		dtime: 		string|Date
	 *		build_url: 	string|null
	 * }
	 * 
	 * @param 	object 	notif 	the notification object.
	 */
	static dispatchNotification(notif) {
		if (typeof notif !== 'object') {
			return false;
		}

		// subscribe to building notification data
		VBOCore.buildNotificationData(notif).then((data) => {
			// dispatch the notification

			// check if the click event should be registered
			let func_nodes;
			if (data.onclick) {
				let callback_parts = data.onclick.split('.');
				while (callback_parts.length) {
					// compose window static method string to avoid using eval()
					let tmp = callback_parts.shift();
					if (!func_nodes) {
						func_nodes = window[tmp];
					} else {
						func_nodes = func_nodes[tmp];
					}
				}
			}

			// prepare properties to delete the notification from queue
			let match_props = {};
			for (let prop in notif) {
				if (!notif.hasOwnProperty(prop) || prop == 'id_timer') {
					continue;
				}
				match_props[prop] = notif[prop];
			}

			// check browser Notifications API
			if (VBOCore.notificationsEnabled() !== true) {
				// notifications not enabled, fallback to toast message
				let toast_notif_data = {
					title: 	data.title,
					body:  	data.message,
					icon:  	data.icon,
					delay: 	{
						min: 6000,
						max: 20000,
						tolerance: 4000,
					},
					action: () => {
						VBOToast.dispose(true);
					},
					sound: VBOCore.options.notif_audio_url
				};
				if (func_nodes) {
					toast_notif_data.action = function() {
						func_nodes(data);
					};
				}
				VBOToast.enqueue(new VBOToastMessage(toast_notif_data));

				// delete dispatched notification from queue
				VBOCore.updateNotification(match_props, 0);

				return;
			}

			// use the browser's native Notifications API
			let browser_notif = new Notification(data.title, {
				body: data.message,
				icon: data.icon,
				tag:  'vbo_notification'
			});

			if (func_nodes) {
				// register notification click event
				browser_notif.addEventListener('click', () => {
					func_nodes(data);
				});
			}

			// delete dispatched notification from queue
			VBOCore.updateNotification(match_props, 0);

		}).catch((error) => {
			console.error(error);
		});
	}

	/**
	 * Asynchronous build of the notification data object for dispatch.
	 * Minimum expected notification display data properties:
	 * 
	 * {
	 *		title: 	 string
	 * 		message: string
	 * 		icon: 	 string
	 *		onclick: function
	 * }
	 * 
	 * @param 	object 	notif 	the scheduled notification object.
	 * 
	 * @return 	Promise
	 */
	static buildNotificationData(notif) {
		return new Promise((resolve, reject) => {
			// notification object validation
			if (typeof notif !== 'object') {
				reject('Invalid notification object');
				return;
			}

			if (!notif.hasOwnProperty('build_url') || !notif.build_url) {
				// building callback not necessary
				if (!notif.title && !notif.message) {
					reject('Unexpected notification object');
					return;
				}
				// we expect the notification to be built already
				resolve(notif);
				return;
			}

			// build the notification data
			VBOCore.doAjax(
				notif.build_url,
				{
					payload: JSON.stringify(notif)
				},
				function(response) {
					// parse response
					try {
						var obj_res = typeof response === 'string' ? JSON.parse(response) : response;
						if (obj_res.hasOwnProperty('title')) {
							resolve(obj_res);
						} else {
							reject('Unexpected JSON response');
						}
					} catch(err) {
						reject('could not parse JSON response');
					}
				},
				function(error) {
					reject(error.responseText);
				}
			);
		});
	}

	/**
	 * Handle a navigation towards a given URL.
	 * Common handler for browser notifications click.
	 * 
	 * @param 	object 	data 	notification display data payload.
	 */
	static handleGoto(data) {
		if (typeof data !== 'object' || !data.hasOwnProperty('gotourl') || !data.gotourl) {
			return;
		}
		// redirect
		document.location.href = data.gotourl;
	}

	/**
	 * Register the latest data to watch for the preloaded admin widgets.
	 * 
	 * @param 	object 	watch_data
	 */
	static registerWatchData(watch_data) {
		if (typeof watch_data !== 'object' || watch_data == null) {
			VBOCore.widgets_watch_data = null;
			return false;
		}

		// set watch-data map
		VBOCore.widgets_watch_data = watch_data;

		// schedule watching interval
		if (VBOCore.watch_data_interval == null) {
			VBOCore.watch_data_interval = window.setInterval(VBOCore.watchWidgetsData, 60000);
		}
	}

	/**
	 * Periodic widgets data watching for new events.
	 */
	static watchWidgetsData() {
		if (typeof VBOCore.widgets_watch_data !== 'object' || VBOCore.widgets_watch_data == null) {
			return;
		}

		// call on new events
		VBOCore.doAjax(
			VBOCore.options.watchdata_ajax_uri,
			{
				watch_data: JSON.stringify(VBOCore.widgets_watch_data),
			},
			function(response) {
				try {
					var obj_res = typeof response === 'string' ? JSON.parse(response) : response;
					if (obj_res.hasOwnProperty('watch_data')) {
						// update watch data map for next schedule
						VBOCore.widgets_watch_data = obj_res['watch_data'];
						// check for notifications
						if (obj_res.hasOwnProperty('notifications') && Array.isArray(obj_res['notifications'])) {
							// dispatch notifications
							for (var i = 0; i < obj_res['notifications'].length; i++) {
								VBOCore.dispatchNotification(obj_res['notifications'][i]);
							}
						}
					} else {
						console.error('Unexpected or invalid JSON response', response);
					}
				} catch(err) {
					console.error('could not parse JSON response', err, response);
				}
			},
			function(error) {
				console.error(error);
			}
		);
	}

	/**
	 * Helper method used to copy the text of an
	 * input element within the clipboard.
	 *
	 * Clipboard copy will take effect only in case the
	 * function is handled by a DOM event explicitly
	 * triggered by the user, such as a "click".
	 *
	 * @param 	mixed 	input  The input containing the text to copy.
	 *
	 * @return 	Promise
	 */
	static copyToClipboard(input) {
		// register and return promise
		return new Promise((resolve, reject) => {
			// define a fallback function
			var fallback = function(input) {
				// focus the input
				input.focus();
				// select the text inside the input
				input.select();

				try {
					// try to copy with shell command
					var copy = document.execCommand('copy');

					if (copy) {
						// copied successfully
						resolve(copy);
					} else {
						// unable to copy
						reject(copy);
					}
				} catch (error) {
					// unable to exec the command
					reject(error);
				}
			};

			// look for navigator clipboard
			if (!navigator || !navigator.clipboard) {
				// navigator clipboard not supported, use fallback
				fallback(input);
				return;
			}

			// try to copy within the clipboard by using the navigator
			navigator.clipboard.writeText(input.value).then(() => {
				// copied successfully
				resolve(true);
			}).catch((error) => {
				// revert to the fallback
				fallback(input);
			});
		});
	}

	/**
	 * Debounce technique to group a flurry of events into one single event.
	 */
	static debounceEvent(func, wait, immediate) {
		var timeout;
		return function() {
			var context = this, args = arguments;
			var later = function() {
				timeout = null;
				if (!immediate) func.apply(context, args);
			};
			var callNow = immediate && !timeout;
			clearTimeout(timeout);
			timeout = setTimeout(later, wait);
			if (callNow) {
				func.apply(context, args);
			}
		}
	}

	/**
	 * Throttle guarantees a constant flow of events at a given time interval.
	 * Runs immediately when the event takes place, but can be delayed.
	 */
	static throttleEvent(method, delay) {
		var time = Date.now();
		return function() {
			if ((time + delay - Date.now()) < 0) {
				method();
				time = Date.now();
			}
		}
	}
}

/**
 * These used to be private static properties (static #options),
 * but they are only supported by quite recent browsers (especially Safari).
 * It's too risky, so we decided to keep the class properties public
 * without declaring them as static inside the class declaration.
 * 
 * @var  object
 */
VBOCore.options = {
	platform: 				null,
	base_uri: 				null,
	widget_ajax_uri: 		null,
	multitask_ajax_uri: 	null,
	watchdata_ajax_uri: 	null,
	current_page: 			null,
	current_page_uri: 		null,
	client: 				'admin',
	panel_opts: 			{},
	admin_widgets: 			[],
	notif_audio_url: 		'',
	active_listeners: 		{},
	tn_texts: 				{
		notifs_enabled: 		'',
		notifs_disabled: 		'',
		notifs_disabled_help: 	'',
	},
	multitask_save_event: 	'vbo-admin-multitask-save',
	multitask_open_event: 	'vbo-admin-multitask-open',
	multitask_close_event: 	'vbo-admin-multitask-close',
	multitask_shortcut_ev: 	'vbo_multitask_shortcut',
	multitask_searchfs_ev: 	'vbo_multitask_search_focus',
};

/**
 * @var  bool
 */
VBOCore.side_panel_on = false;

/**
 * @var  array
 */
VBOCore.notifications = [];

/**
 * @var  object
 */
VBOCore.widgets_watch_data = null;

/**
 * @var  number
 */
VBOCore.watch_data_interval = null;
