<?php
/**
 * My Private Site by David Gewirtz, adopted from Jon ‘jonradio’ Pearkins
 *
 * Lab Notes: http://zatzlabs.com/lab-notes/
 * Plugin Page: https://zatzlabs.com/project/my-private-site/
 * Contact: http://zatzlabs.com/contact-us/
 *
 * Copyright (c) 2015-2020 by David Gewirtz
 */

function my_private_site_array_size( $array ) {
	// particularly for non-countable arrays
	$count = 0;
	if ( is_array( $array ) ) {
		foreach ( $array as $value ) {
			++ $count;
		}
	}

	return $count;
}

function my_private_site_force_unset_array_by_index( $array, $index ) {
	$new_array = array();
	$size      = my_private_site_array_size( $array );
	$count     = 0;
	for ( $i = 0; $i < $size; ++ $i ) {
		if ( $index != $i ) {
			$new_array[ $count ] = $array[ $i ];
			++ $count;
		}
	}

	return $new_array;
}

// From CMB2 Snippet Library
// https://github.com/CMB2/CMB2-Snippet-Library/edit/master/options-and-settings-pages/options-pages-with-tabs-and-submenus.php
/**
 * A CMB2 options-page display callback override which adds tab navigation among
 * CMB2 options pages which share this same display callback.
 *
 * @param CMB2_Options_Hookup $cmb_options The CMB2_Options_Hookup object.
 */
function my_private_site_cmb_options_display_with_tabs( $cmb_options ) {
	$tabs = my_private_site_cmb_options_page_tabs( $cmb_options );
	// All we're doing here is making sure we're on the right page
	// phpcs:ignore WordPress.Security.NonceVerification
	if ( isset( $_GET['page'] ) ) {
		$get_page = sanitize_text_field( $_GET['page'] );
	} else {
		$get_page = '';
	}

	?>
    <div class="wrap cmb2-options-page option-<?php echo esc_attr( $cmb_options->option_key ); ?>">
		<?php if ( get_admin_page_title() ) : ?>
            <h2><?php echo wp_kses_post( get_admin_page_title() ); ?></h2>
		<?php endif; ?>
        <h2 class="nav-tab-wrapper">
			<?php foreach ( $tabs as $option_key => $tab_title ) : ?>
                <a class="nav-tab
				<?php
				if ( $get_page != '' && $option_key === $get_page ) :
					?>
					 nav-tab-active<?php endif; ?>"
                   href="<?php menu_page_url( $option_key ); ?>"><?php echo wp_kses_post( $tab_title ); ?></a>
			<?php endforeach; ?>
        </h2>
        <form class="cmb-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST"
              id="<?php echo esc_attr( $cmb_options->cmb->cmb_id ); ?>" enctype="multipart/form-data"
              encoding="multipart/form-data">
            <input type="hidden" name="action" value="<?php echo esc_attr( $cmb_options->option_key ); ?>">
			<?php $cmb_options->options_page_metabox(); ?>
			<?php submit_button( esc_attr( $cmb_options->cmb->prop( 'save_button' ) ), 'primary', 'submit-cmb' ); ?>
        </form>
    </div>
	<?php
}

/**
 * Gets navigation tabs array for CMB2 options pages which share the given
 * display_cb param.
 *
 * @param CMB2_Options_Hookup $cmb_options The CMB2_Options_Hookup object.
 *
 * @return array Array of tab information.
 */
function my_private_site_cmb_options_page_tabs( $cmb_options ) {
	$tab_group = $cmb_options->cmb->prop( 'tab_group' );
	$tabs      = array();

	foreach ( CMB2_Boxes::get_all() as $cmb_id => $cmb ) {
		if ( $tab_group === $cmb->prop( 'tab_group' ) ) {
			$tabs[ $cmb->options_page_keys()[0] ] = $cmb->prop( 'tab_title' )
				? $cmb->prop( 'tab_title' )
				: $cmb->prop( 'title' );
		}
	}

	return $tabs;
}

// set up filter to pre-load field values from My Private Site database
// from: https://github.com/CMB2/CMB2/wiki/Tips-&-Tricks#override-the-data-storage-location-for-a-cmb2-box
function my_private_site_preload_cmb2_field_filter( $field_id, $handler_function_name ) {
	add_filter(
		'cmb2_override_' . $field_id . '_meta_value', // the filter
		$handler_function_name,
		10,
		4
	);
}

function my_private_site_display_cmb2_submit_button( $section_options, $button_options ) {
	$section_options->add_field(
		array(
			'name'           => $button_options['button_text'],
			'id'             => $button_options['button_id'],
			'button_options' => $button_options,
			'type'           => 'ignoreme',
			'render_row_cb'  => 'my_private_site_display_cmb2_submit_button_callback',
		)
	);
}

function my_private_site_display_cmb2_submit_button_callback( $field_args, $field ) {
	// get button values
	$page_stub          = $field->object_id;
	$button_id          = $field->args['button_options']['button_id'];
	$button_text        = $field->args['button_options']['button_text'];
	$button_success_msg = $field->args['button_options']['button_success_msg'];
	$button_error_msg   = $field->args['button_options']['button_error_msg'];

	// debug code
	// $button_list_option_name = 'jr_ps_my_private_site_tab_settings_button_list';
	// $button_list_option      = get_option($button_list_option_name);
	// if ($button_list_option == false) {
	// $button_list_array = array();
	// } else {
	// $button_list_array = unserialize($button_list_option);
	// }
	// $foo = serialize($button_list_array);

	// show error if option set
	$error_msg = my_private_site_get_cmb2_submit_button_error_message( $button_id );
	if ( $error_msg != '' ) {
		echo '<div id="' . esc_attr( $button_id ) . '" class="notice notice-error">';
		echo esc_attr( $error_msg );
		echo '</div>';
	}

	// show message if option set
	$button_msg = my_private_site_get_cmb2_submit_button_success_message( $button_id );
	if ( $button_msg != '' ) {
		echo '<div id="' . esc_attr( $button_id ) . '" class="notice notice-message">';
		echo esc_attr( $button_msg );
		echo '</div>';
	}

	// set up the fresh button message array for this admin page load
	$button_list_option_name = 'jr_ps_' . $page_stub . '_button_list';
	$button_list_option      = get_option( $button_list_option_name );

	if ( $button_list_option == false ) {
		$button_list_array = array();
	} else {
		$button_list_array = unserialize( $button_list_option );
	}
	$button_id_success = $button_id . '_success';
	$button_id_error   = $button_id . '_error';

	// if ( count( $button_list_array ) == 0 ) {
	$button_list_array[ $button_id_success ] = $button_success_msg;
	$button_list_array[ $button_id_error ]   = $button_error_msg;
	// } else {
	// $button_list_array->$button_id_success = $button_success_msg;
	// $button_list_array->$button_id_error   = $button_error_msg;
	// }
	$button_list_option = serialize( $button_list_array );
	update_option( $button_list_option_name, $button_list_option );

	// create nonce code based on the ID of the button

	$nonce      = wp_create_nonce( $button_id );
	$nonce_name = $button_id . '_nonce';

	// display the button
	?>
    <div class="cmb-action-button-row">
        <p class="submit">
            <input type="hidden" id="<?php echo esc_attr( $nonce_name ); ?>"
                   name="<?php echo esc_attr( $nonce_name ); ?>"
                   value="<?php echo esc_attr( $nonce ); ?>"/>
            <input type="submit" name="<?php echo esc_attr( $button_id ); ?>"
                   id="<?php echo esc_attr( $button_id ); ?>"
                   class="button button-primary"
                   value="<?php echo esc_attr( $button_text ); ?>"></p>
    </div>
	<?php
}

function my_private_site_flag_cmb2_submit_button_success( $button_id, $msg = '' ) {
	my_private_site_set_cmb2_submit_button_flag( $button_id, '_success', $msg );
}

function my_private_site_flag_cmb2_submit_button_error( $button_id, $msg = '' ) {
	my_private_site_set_cmb2_submit_button_flag( $button_id, '_error', $msg );
}

function my_private_site_set_cmb2_submit_button_flag( $button_id, $what_to_set, $msg = '' ) {
	// uses the default param so we can have two different functions with essentially the same code
	// it will be easier to read on the form settings pages
	// All we're doing here is making sure we're on the right page
	// phpcs:ignore WordPress.Security.NonceVerification
	if ( isset( $_POST['action'] ) ) {
		$page_stub = sanitize_text_field( $_POST['action'] );
	} else {
		$page_stub = '';
	}

	$button_to_set = $button_id . $what_to_set;

	$button_list_option_name = 'jr_ps_' . $page_stub . '_button_list';
	$button_list_option      = get_option( $button_list_option_name );
	if ( $button_list_option != false ) {
		$button_list_array = unserialize( $button_list_option );

		if ( $msg == '' ) {
			if ( isset( $button_list_array[ $button_to_set ] ) ) {
				$message_to_set = $button_list_array[ $button_to_set ];
			} else {
				$message_to_set = '';
			}
		} else {
			$message_to_set = $msg;
		}

		unset( $button_list_array );
		$button_list_array                   = array();
		$button_list_array[ $button_to_set ] = $message_to_set;

		$button_list_option = serialize( $button_list_array );
		update_option( $button_list_option_name, $button_list_option );
	}
}

function my_private_site_get_cmb2_submit_button_success_message( $button_id ) {
	return my_private_site_get_cmb2_submit_button_message( $button_id, '_success' );
}

function my_private_site_get_cmb2_submit_button_error_message( $button_id ) {
	return my_private_site_get_cmb2_submit_button_message( $button_id, '_error' );
}

function my_private_site_get_cmb2_submit_button_message( $button_id, $what_to_get ) {
	// uses the default param so we can have two different functions with essentially the same code
	// it will be easier to read on the form settings pages
	// All we're doing here is making sure we're on the right page
	// phpcs:ignore WordPress.Security.NonceVerification
	if ( isset( $_GET['page'] ) ) {
		$page_stub = sanitize_text_field( $_GET['page'] );

		$button_list_option_name = 'jr_ps_' . $page_stub . '_button_list';
		$button_list_option      = get_option( $button_list_option_name );
		if ( $button_list_option == false ) {
			return '';
		} else {
			$button_list_array = unserialize( $button_list_option );
		}
		if ( isset( $button_list_array[ $button_id . $what_to_get ] ) ) {
			return $button_list_array[ $button_id . $what_to_get ];
		} else {
			return '';
		}
	} else {
		return '';
	}
}

function my_private_site_clear_cmb2_submit_button_messages( $page_stub ) {
	if ( ! my_private_site_is_referred_by_page( $page_stub ) ) {
		// clear previous error messages if coming from another page
		$button_list_option_name = 'jr_ps_' . $page_stub . '_button_list';
		$button_list_array       = array();
		$button_list_option      = serialize( $button_list_array );
		update_option( $button_list_option_name, $button_list_option );
	}
}

// Adds custom action button to form
// $name is the the text displayed in the button
// $id is the unique id of the action button
function my_private_site_cmb2_add_action_button( $section_options, $name, $id ) {
	$section_options->add_field(
		array(
			'name'          => esc_attr( $name ),
			'id'            => esc_attr( $id ),
			'render_row_cb' => 'my_private_site_cmb2_row_callback_for_action_button',
		)
	);
}

function my_private_site_cmb2_row_callback_for_action_button( $field_args, $field ) {
	$button_name     = $field->args['name'];
	$button_id       = $field->args['id'];
	$button_error_id = $button_id . '_error';
	$button_msg_id   = $button_id . '_msg';

	// show error if option set
	$error_msg = get_option( $button_error_id );
	if ( $error_msg != false ) {
		if ( $error_msg != '' ) {
			echo '<div id="' . esc_attr( $button_error_id ) . '" class="notice notice-error">';
			echo esc_attr( $error_msg );
			echo '</div>';
		}
	}
	// show message if option set
	$button_msg = get_option( $button_msg_id );
	if ( $button_msg != false ) {
		if ( $button_msg != '' ) {
			echo '<div id="' . esc_attr( $button_msg_id ) . '" class="notice notice-message">';
			echo esc_attr( $error_msg );
			echo '</div>';
		}
	}

	// display the button
	?>
    <div class="cmb-action-button-row">
        <p class="submit">
            <input type="submit" name="<?php echo esc_attr( $button_id ); ?>"
                   id="<?php echo esc_attr( $button_id ); ?>"
                   class="button button-primary"
                   value="<?php echo esc_attr( $button_name ); ?>"></p>
    </div>
	<?php
}

// Adds a static description line to the form
// $name is the the text displayed in the button
// 'desc' passed as an argument is the static text description displayed
// $id is the unique id of the action button
function my_private_site_cmb2_add_static_desc( $section_options, $desc, $id ) {
	$section_options->add_field(
		array(
			'desc'          => $desc,
			'id'            => esc_attr( $id ),
			'render_row_cb' => 'my_private_site_cmb2_row_callback_for_static_desc',
		)
	);
}

function my_private_site_cmb2_row_callback_for_static_desc( $field_args, $field ) {
	$desc = $field->args['desc'];
	$id   = $field->args['id'];

	?>

    <div class="cmb-static-desc-row" id="<?php echo esc_attr( $id ); ?>">
        <div class="cmb-td">
            <p class="cmb2-metabox-description"><?php echo esc_textarea( $desc ); ?></p>
        </div>
    </div>

	<?php
}

// PRE 5.0, needs conversion to 5.0
// This function builds both options and settings based on passed arrays
// The $options_array is an array that would be passed to the addSettingsField method
// If $settings_array is passed (not false), it will create a section and add the options to that section
function my_private_site_process_add_settings_fields_with_options5(
	$options_array, $apf_object, $settings_array = array()
) {
	if ( count( $settings_array ) > 0 ) {
		$apf_object->addSettingSections( $settings_array );
		$section_id = $settings_array['section_id'];
	}

	for ( $i = 0; $i < count( $options_array ); ++ $i ) {
		// read in stored options
		// by using this approach, we don't need to special-case for
		// fields and field types that don't save option data
		$option = $options_array[ $i ]['field_id'];

		$stored_option = get_option( $option, false );
		if ( $stored_option != false ) {
			$options_array[ $i ]['default'] = $stored_option;
		}

		// build up the settings field display
		if ( count( $settings_array ) > 0 ) {
			$apf_object->addSettingFields( $section_id, $options_array[ $i ] );
		} else {
			$apf_object->addSettingFields( $options_array[ $i ] );
		}
	}
}

function my_private_site_is_referred_by_page( $page ) {
	// takes the value of $args['option_key']) from calling function as parameter
	// this is the name of the admin page we're checking
	// good for seeing if self-referring, if user was redirected from the current page
	$referring_page = '';
	if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
		$referring_page = sanitize_text_field( $_SERVER['HTTP_REFERER'] );
	}
	$parts_list = wp_parse_url( $referring_page );

	if ( isset( $parts_list['query'] ) ) {
		$query = $parts_list['query'];
	} else {
		$query = '';
	}

	// we could split the string to parse away the page= but why bother?
	if ( $query != 'page=' . $page ) {
		return false;
	} else {
		return true;
	}
}

function my_private_site_cpt_list_type() {
	if ( isset( $_SERVER['REQUEST_URI'] ) ) {
		$request    = sanitize_url( $_SERVER['REQUEST_URI'] );
		$parts_list = wp_parse_url( $request );
		parse_str( $parts_list['query'], $query_parts );
		if ( isset( $query_parts['post_type'] ) ) {
			$post_type = strtolower( $query_parts['post_type'] );

			return $post_type;
		} else {
			return '';
		}
	} else {
		return '';
	}
}
