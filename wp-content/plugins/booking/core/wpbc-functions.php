<?php 
/**
 * @version 1.0
 * @package Booking Calendar 
 * @subpackage Support Functions
 * @category Functions
 * 
 * @author wpdevelop
 * @link https://wpbookingcalendar.com/
 * @email info@wpbookingcalendar.com
 *
 * @modified 29.09.2015
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly


////////////////////////////////////////////////////////////////////////////////
// Formatting functions
////////////////////////////////////////////////////////////////////////////////    
/**
 * Sanitize term to Slug format (no spaces, lowercase).
 * urldecode - reverse munging of UTF8 characters.
 *
 * @param mixed $value
 * @return string
 */
function wpbc_get_slug_format( $value ) {
    return  urldecode( sanitize_title( $value ) );
}


/**
 * Get Slug Format Option Value for saving to  the options table.
 * Replacing - to _ and restrict length to 64 characters.
 * 
 * @param string $value
 * @return string
 */
function wpbc_get_slug_format_4_option_name( $value ) {
    
    $value = wpbc_get_slug_format( $value );
    $value = str_replace('-', '_', $value);
    $value = substr($value, 0, 64);
    return $value;
}



/**
 * Check if this demo website
 *
 * @return bool
 */
function wpbc_is_this_demo() {

//return !false;

	//FixIn: 7.2.1.17
	if  (
				(  ( isset( $_SERVER['SCRIPT_FILENAME'] ) ) && ( strpos( $_SERVER['SCRIPT_FILENAME'], 'wpbookingcalendar.com' ) !== false ) )
			||  (  ( isset( $_SERVER['HTTP_HOST'] ) ) && ( strpos( $_SERVER['HTTP_HOST'], 'wpbookingcalendar.com' ) !== false )  )
		)
		  return true;
		else
		  return false;
}


////////////////////////////////////////////////////////////////////////////////
//  B o o k i n g    f u n c t i o n s        
////////////////////////////////////////////////////////////////////////////////

//FixIn: 8.1.3.5
/** Check, if exist booking for this hash. If exist, get Email of this booking
 *
 * @param string $booking_hash		- booking hash.
 * @param string $booking_data_key	- booking field key - default 'email'.
 *
 * @return bool | booking data field
 */
function wpbc_check_hash_get_booking_details( $booking_hash, $booking_data_key = 'email' ){

	$return_val = false;

	// $booking_hash = '0d55671fd055fd64423294f89d6b58e6';        	// debugging

	if ( ! empty( $booking_hash ) ) {


		$my_booking_id_type = apply_bk_filter( 'wpdev_booking_get_hash_to_id', false, $booking_hash );
//debuge($my_booking_id_type);

		if ( ! empty( $my_booking_id_type ) ) {

			list( $booking_id, $resource_id ) = $my_booking_id_type;

			$booking_data = wpbc_get_booking_details( $booking_id );
//debuge('$booking_data',$booking_data);

			if ( ! empty( $booking_data ) ) {

				$booking_details = wpbc_get_booking_params( $booking_id, $booking_data->form, $resource_id );
//debuge( '$booking_details', $booking_details );

				if ( isset( $booking_details[ $booking_data_key ] ) ) {
					$return_val = $booking_details[ $booking_data_key ];
				}
			}
		}
	}
	return $return_val;
}


//FixIn: 8.1.3.5
/** Get booking details
 *
 * @param $booking_id - int
 *
 * @return mixed - booking details or false if not found
 * Example:
 stdClass Object
(
	[booking_id] => 26
	[trash] => 0
	[sync_gid] =>
	[is_new] => 0
	[status] =>
	[sort_date] => 2018-02-27 00:00:00
	[modification_date] => 2018-02-18 12:49:30
	[form] => text^selected_short_dates_hint3^02/27/2018 - 03/02/2018~text^days_number_hint3^4~text^cost_hint3^40.250&nbsp;&#36;~text^name3^Victoria~text^secondname3^vica~email^email3^vica@wpbookingcalendar.com~text^phone3^test booking ~select-one^visitors3^1
	[hash] => 0d55671fd055fd64423294f89d6b58e6
	[booking_type] => 3
	[remark] =>
	[cost] => 40.25
	[pay_status] => 151895097121.16
	[pay_request] => 0
)
 */
function wpbc_get_booking_details( $booking_id ){

	global $wpdb;

	$slct_sql = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}booking WHERE booking_id = %d LIMIT 0,1", $booking_id );

	$sql_results  = $wpdb->get_row( $slct_sql );

	if ( ! empty( $sql_results ) ) {
		return $sql_results;
	} else
    	return false;
}


/** Get Booking Details
 *
 * @param int $booking_id - ID of booking                                       // 999
 * @param string $formdata         - booking form data content                  // select-one^rangetime4^10:00 - 12:00~text^name4^Jo~text^secondname4^Smith~email^email4^smith@wpbookingcalendar.com~...
 * @param int $booking_resource_id - booking resource type                      // 4
 *
 * @return array
 * Example:
  Array
        (
            [booking_id] => 26
            [id] => 26
            [days_input_format] => 01.03.2018,02.03.2018,27.02.2018,28.02.2018
            [days_only_sql] => 2018-02-27,2018-02-28,2018-03-01,2018-03-02
            [dates_sql] => 2018-02-27 00:00:00, 2018-02-28 00:00:00, 2018-03-01 00:00:00, 2018-03-02 00:00:00
            [check_in_date_sql] => 2018-02-27 00:00:00
            [check_out_date_sql] =>  2018-03-02 00:00:00
            [dates] => 02/27/2018 - 03/02/2018
            [check_in_date] => 02/27/2018
            [check_out_date] => 03/02/2018
            [check_out_plus1day] => 03/03/2018
            [dates_count] => 4
            [days_count] => 4
            [nights_count] => 3
            [check_in_date_hint] => 02/27/2018
            [check_out_date_hint] => 03/02/2018
            [start_time_hint] => 00:00
            [end_time_hint] => 00:00
            [selected_dates_hint] => 02/27/2018, 02/28/2018, 03/01/2018, 03/02/2018
            [selected_timedates_hint] => 02/27/2018, 02/28/2018, 03/01/2018, 03/02/2018
            [selected_short_dates_hint] => 02/27/2018 - 03/02/2018
            [selected_short_timedates_hint] => 02/27/2018 - 03/02/2018
            [days_number_hint] => 4
            [nights_number_hint] => 3
            [siteurl] => http://beta
            [resource_title] => Apartment A
            [bookingtype] => Apartment A
            [remote_ip] => 127.0.0.1
            [user_agent] => Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0
            [request_url] => http://beta/wp-admin/post.php?post=1473&action=edit
            [current_date] => 02/18/2018
            [current_time] => 14:11
            [cost_hint] => 40.250 $
            [name] => Victoria
            [secondname] => vica
            [email] => vica@wpbookingcalendar.com
            [phone] => test booking
            [visitors] => 1
            [booking_resource_id] => 3
            [resource_id] => 3
            [type_id] => 3
            [type] => 3
            [resource] => 3
		 	[content] => '........'
 			[moderatelink] => http://beta/wp-admin/admin.php?page=wpbc&view_mode=vm_listing&tab=actions&wh_booking_id=26
            [visitorbookingediturl] => http://beta/?booking_hash=0d55671fd055fd64423294f89d6b58e6
            [visitorbookingcancelurl] => http://beta/?booking_hash=0d55671fd055fd64423294f89d6b58e6&booking_cancel=1
            [visitorbookingpayurl] => http://beta/?booking_hash=0d55671fd055fd64423294f89d6b58e6&booking_pay=1
            [bookinghash] => 0d55671fd055fd64423294f89d6b58e6
            [db_cost] => 40.25
            [db_cost_hint] => 40.250 $
            [modification_date] =>  2018-02-18 12:49:30
            [modification_year] => 2018
            [modification_month] => 02
            [modification_day] => 18
            [modification_hour] => 12
            [modification_minutes] => 49
            [modification_seconds] => 30
 */
function wpbc_get_booking_params( $booking_id, $formdata, $booking_resource_id = 1 ) { 
    
    $replace = array();   

    // Resources /////////////////////////////////////////////////////////////// 
    $bk_title = '';
    if ( function_exists( 'get_booking_title' ) )
        $bk_title = get_booking_title( $booking_resource_id );
            
    ////////////////////////////////////////////////////////////////////////////
    // Dates Dif. Formats
    ////////////////////////////////////////////////////////////////////////////
    $sql_dates_format = wpbc_get_str_sql_dates_in_booking( $booking_id );       // 2016-08-03 16:00:01, 2016-08-03 18:00:02
    
    $sql_dates_only = explode(',',$sql_dates_format);
    $sql_days_only_array = array();
    $days_as_in_form_array = array();
    foreach ( $sql_dates_only as $sql_day_only ) {
        $sql_days_only_array[] = trim( substr($sql_day_only, 0, 11 ) );
        $days_as_in_form_array[] = date_i18n( "d.m.Y", strtotime( trim( substr($sql_day_only, 0, 11 ) ) ) );
    }
    $sql_days_only_array = array_unique( $sql_days_only_array );
    sort( $sql_days_only_array );
    $sql_days_only = implode( ',', $sql_days_only_array );
    
    $days_as_in_form_array = array_unique( $days_as_in_form_array );
    sort( $days_as_in_form_array );
    $days_as_in_form = implode( ',', $days_as_in_form_array );
        
    $sql_days_only_with_full_times = array();
    foreach ( $sql_days_only_array as $sql_day ) {
        $sql_days_only_with_full_times[] = $sql_day . ' 00:00:00';
    }    
    $sql_days_only_with_full_times = implode(',', $sql_days_only_with_full_times );
    
    
    
    if ( get_bk_option( 'booking_date_view_type' ) == 'short' )
        $formated_booking_dates = wpbc_get_dates_short_format( $sql_dates_format );
    else
        $formated_booking_dates = wpbc_change_dates_format( $sql_dates_format );
    
    $sql_dates_format_check_in_out = explode(',', $sql_dates_format );

    $my_check_in_date       = wpbc_change_dates_format( $sql_dates_format_check_in_out[0] );
    $my_check_out_date      = wpbc_change_dates_format( $sql_dates_format_check_in_out[ count( $sql_dates_format_check_in_out ) - 1 ] );

    $my_check_out_plus1day  = wpbc_change_dates_format( date_i18n( 'Y-m-d H:i:s', strtotime( $sql_dates_format_check_in_out[ count( $sql_dates_format_check_in_out ) - 1 ] . " +1 day" ) ) ); //FixIn: 6.0.1.11


    $date_format = get_bk_option( 'booking_date_format');
    $check_in_date_hint  = date_i18n( $date_format, strtotime( $sql_days_only_array[0] ) );
    $check_out_date_hint = date_i18n( $date_format, strtotime( $sql_days_only_array[ ( count( $sql_days_only_array ) - 1  ) ]  ) );
    
    // Booking Times ///////////////////////////////////////////////////////////
    $start_end_time = wpbc_get_times_in_form( $formdata, $booking_resource_id ); // false || 
    
    if ( $start_end_time !== false ) {
        $start_time = $start_end_time[0];                                       // array('00','00','01');
        $end_time   = $start_end_time[1];                                       // array('00','00','01');
    } else {
        $start_time = array('00','00','00');
        $end_time   = array('00','00','00');
    }
    
    $time_format = get_bk_option( 'booking_time_format');
    if ( $time_format === false  ) $time_format = '';
 
    $start_time_hint = date_i18n( $time_format, mktime( $start_time[0], $start_time[1], $start_time[2] ) );
    $end_time_hint   = date_i18n( $time_format, mktime( $end_time[0], $end_time[1], $end_time[2] ) );            
    ////////////////////////////////////////////////////////////////////////////


    

    // Other ///////////////////////////////////////////////////////////////////
    $replace[ 'booking_id' ]    = $booking_id;
    $replace[ 'id' ]            = $replace[ 'booking_id' ];
    
/*
            [days_input_format] => 08.09.2016,09.09.2016,10.09.2016
 
            [days_only_sql]     => 2016-09-08,2016-09-09,2016-09-10
            [dates_sql]         => 2016-09-08 16:00:01, 2016-09-09 00:00:00, 2016-09-10 18:00:02
            [check_in_date_sql] => 2016-09-08 16:00:01
            [check_out_date_sql]=> 2016-09-10 18:00:02
  
            [dates]             => September 8, 2016 16:00 - September 10, 2016 18:00
            [check_in_date]     => September 8, 2016 16:00
            [check_out_date]    => September 10, 2016 18:00
            [check_out_plus1day]=> September 11, 2016 18:00
            [dates_count]       => 3
            [days_count]        => 3
            [nights_count]      => 2
 */    
    
    $replace[ 'days_input_format' ] = $days_as_in_form;                         // 28.07.2016
    $replace[ 'days_only_sql' ]     = $sql_days_only;                           // 2016-07-28
    $replace[ 'dates_sql' ]         = $sql_dates_format;                        // 2016-07-28 16:00:01, 2016-07-28 18:00:02
    $replace[ 'check_in_date_sql' ] = $sql_dates_format_check_in_out[0];        // 2016-07-28 16:00:01
    $replace[ 'check_out_date_sql' ] = $sql_dates_format_check_in_out[ count( $sql_dates_format_check_in_out ) - 1 ];       // 2016-07-28 18:00:02
    $replace[ 'dates' ]             = $formated_booking_dates;                  // July 28, 2016 16:00 - July 28, 2016 18:00
    $replace[ 'check_in_date' ]     = $my_check_in_date;                        // July 28, 2016 16:00
    $replace[ 'check_out_date' ]    = $my_check_out_date;                       // July 28, 2016 18:00
    $replace[ 'check_out_plus1day'] = $my_check_out_plus1day;                   // July 29, 2016 18:00
    $replace[ 'dates_count' ]       = count( $sql_days_only_array );            // 1
    $replace[ 'days_count' ]        = count( $sql_days_only_array );            // 1
    $replace[ 'nights_count' ]      = ( $replace[ 'days_count' ] > 1 ) ? ( $replace[ 'days_count' ] - 1 ) : $replace[ 'days_count' ];       // 1

    $replace[ 'check_in_date_hint' ]  = $check_in_date_hint;                    // 11/25/2013
    $replace[ 'check_out_date_hint' ] = $check_out_date_hint;                   // 11/27/2013
    $replace[ 'start_time_hint' ]   = $start_time_hint;                         // 10:00
    $replace[ 'end_time_hint' ]     = $end_time_hint;                           // 12:00
    
$replace['selected_dates_hint']       = wpbc_change_dates_format( $sql_days_only_with_full_times );                 // 11/25/2013, 11/26/2013, 11/27/2013
    $replace['selected_timedates_hint']   = wpbc_change_dates_format( $sql_dates_format );              // 11/25/2013 10:00, 11/26/2013, 11/27/2013 12:00        
$replace['selected_short_dates_hint']     = wpbc_get_dates_short_format( $sql_days_only_with_full_times );          // 11/25/2013 - 11/27/2013
    $replace['selected_short_timedates_hint'] = wpbc_get_dates_short_format( $sql_dates_format );       // 11/25/2013 10:00 - 11/27/2013 12:00        
    $replace[ 'days_number_hint' ]   = $replace[ 'days_count' ];                // 3
    $replace[ 'nights_number_hint' ] = $replace[ 'nights_count' ];              // 2
    $replace[ 'siteurl' ]       = htmlspecialchars_decode( '<a href="' . home_url() . '">' . home_url() . '</a>' );
    $replace[ 'resource_title'] = apply_bk_filter( 'wpdev_check_for_active_language', $bk_title );
    $replace[ 'bookingtype' ]   = $replace[ 'resource_title'];
    $replace[ 'remote_ip'     ] = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '';          // The IP address from which the user is viewing the current page. 
    $replace[ 'user_agent'    ] = (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : '';  // Contents of the User-Agent: header from the current request, if there is one. 
    $replace[ 'request_url'   ] = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '';        // The address of the page (if any) where action was occured. Because we are sending it in Ajax request, we need to use the REFERER HTTP
    $replace[ 'current_date' ]  = date_i18n( get_bk_option( 'booking_date_format' ) );
    $replace[ 'current_time' ]  = date_i18n( get_bk_option( 'booking_time_format' ) );                                                    

    
    // Form Fields /////////////////////////////////////////////////////////////
    $booking_form_show_array = get_form_content( $formdata, $booking_resource_id, '', $replace );    // We use here $replace array,  becaise in "Content of booking filds data" form  can  be shortcodes from above definition
                    
    foreach ( $booking_form_show_array['_all_fields_'] as $shortcode_name => $shortcode_value ) {
        
        if ( ! isset( $replace[ $shortcode_name ] ) )
            $replace[ $shortcode_name ] = $shortcode_value;
    }
    $replace[ 'content' ]       = $booking_form_show_array['content'];

    // Links ///////////////////////////////////////////////////////////////////
    $replace[ 'moderatelink' ]  = htmlspecialchars_decode( 
                                                        //    '<a href="' . 
                                                            esc_url( wpbc_get_bookings_url() . '&view_mode=vm_listing&tab=actions&wh_booking_id=' . $booking_id ) 
                                                        //    . '">' . __('here', 'booking') . '</a>'  
                                                        );    
    $replace[ 'visitorbookingediturl' ]     = apply_bk_filter( 'wpdev_booking_set_booking_edit_link_at_email', '[visitorbookingediturl]', $booking_id );
    $replace[ 'visitorbookingslisting' ]     = apply_bk_filter( 'wpdev_booking_set_booking_edit_link_at_email', '[visitorbookingslisting]', $booking_id );	//FixIn: 8.1.3.5.1
    $replace[ 'visitorbookingcancelurl' ]   = apply_bk_filter( 'wpdev_booking_set_booking_edit_link_at_email', '[visitorbookingcancelurl]', $booking_id );
    $replace[ 'visitorbookingpayurl' ]      = apply_bk_filter( 'wpdev_booking_set_booking_edit_link_at_email', '[visitorbookingpayurl]', $booking_id );
    $replace[ 'bookinghash' ]               = apply_bk_filter( 'wpdev_booking_set_booking_edit_link_at_email', '[bookinghash]', $booking_id );
    
    // Cost ////////////////////////////////////////////////////////////////////    
    $replace[ 'db_cost' ]        = apply_bk_filter( 'get_booking_cost_from_db', '', $booking_id );
    $replace[ 'db_cost_hint' ]   = wpbc_get_cost_with_currency_for_user( $replace[ 'db_cost' ], $booking_resource_id );

    ////////////////////////////////////////////////////////////////////////////

	//FixIn: 8.0.1.7
	$modification_date = wpbc_get_booking_modification_date( $booking_id );
	$replace[ 'modification_date' ]  = $modification_date;
	$replace[ 'modification_year' ]  = date_i18n( 'Y', strtotime( $modification_date ) );
	$replace[ 'modification_month' ] = date_i18n( 'm', strtotime( $modification_date ) );
	$replace[ 'modification_day' ]   = date_i18n( 'd', strtotime( $modification_date ) );
	$replace['modification_hour']    = date_i18n( 'H', strtotime( $modification_date ) );
	$replace['modification_minutes'] = date_i18n( 'i', strtotime( $modification_date ) );
	$replace['modification_seconds'] = date_i18n( 's', strtotime( $modification_date ) );

    return $replace;
}


//FixIn: 8.0.1.7
function wpbc_get_booking_modification_date( $booking_id ){
	global $wpdb;
    $modification_date = ' ' . $wpdb->get_var( $wpdb->prepare( "SELECT modification_date FROM {$wpdb->prefix}booking  WHERE booking_id = %d " , $booking_id ) );
    return $modification_date;
}


/** Get additional parameters to the replace array  for specific booking
 * @param $replace
 * @param $booking_id
 * @param $bktype
 * @param $formdata
 *
 * @return mixed
 */
function wpbc_replace_params_for_booking_func( $replace, $booking_id, $bktype, $formdata ){

	$modification_date = wpbc_get_booking_modification_date( $booking_id );
	if ( ! isset( $replace['modification_date'] ) ) {
		$replace['modification_date'] = $modification_date;
	}
	if ( ! isset( $replace['modification_year'] ) ) {
		$replace['modification_year'] = date_i18n( 'Y', strtotime( $modification_date ) );
	}
	if ( ! isset( $replace['modification_month'] ) ) {
		$replace['modification_month'] = date_i18n( 'm', strtotime( $modification_date ) );
	}
	if ( ! isset( $replace['modification_day'] ) ) {
		$replace['modification_day'] = date_i18n( 'd', strtotime( $modification_date ) );
	}

	if ( ! isset( $replace['modification_hour'] ) ) {
		$replace['modification_hour'] = date_i18n( 'H', strtotime( $modification_date ) );
	}
	if ( ! isset( $replace['modification_minutes'] ) ) {
		$replace['modification_minutes'] = date_i18n( 'i', strtotime( $modification_date ) );
	}
	if ( ! isset( $replace['modification_seconds'] ) ) {
		$replace['modification_seconds'] = date_i18n( 's', strtotime( $modification_date ) );
	}

	//FixIn: 8.4.2.11
	if ( isset( $replace['rangetime'] ) ) {
		$replace['rangetime'] = wpbc_time_slot_in_format( $replace['rangetime'] );
	}
	if ( isset( $replace['starttime'] ) ) {
		$replace['starttime'] = wpbc_time_in_format( $replace['starttime'] );
	}
	if ( isset( $replace['endtime'] ) ) {
		$replace['endtime'] = wpbc_time_in_format( $replace['endtime'] );
	}

//	$booking_id

	//FixIn: 8.2.1.25
	$booking_data = wpbc_get_booking_details( $booking_id );

	if ( ! empty( $booking_data ) ) {
		foreach ( $booking_data as $booking_key => $booking_data ) {
			if ( ! isset( $replace[ $booking_key ] ) ) {
				$replace[ $booking_key ] = $booking_data;
			}
		}
	}
//debuge($replace);
	return $replace;
}
add_filter( 'wpbc_replace_params_for_booking', 'wpbc_replace_params_for_booking_func', 10, 4 );


/**
	 * Replace shortcodes in string
 * 
 * @param string $subject - string to  manipulate
 * @param array $replace_array - array with  values to  replace                 // array( [booking_id] => 9, [id] => 9, [dates] => July 3, 2016 14:00 - July 4, 2016 16:00, .... )
 * @param mixed $replace_unknown_shortcodes - replace unknown params, if false, then  no replace unknown params
 * @return string
 */
function wpbc_replace_booking_shortcodes( $subject, $replace_array , $replace_unknown_shortcodes = ' ' ) {

    $defaults = array(
        'ip'                => apply_bk_filter( 'wpbc_get_user_ip' )
        , 'blogname'        => wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES )
        , 'siteurl'         => get_site_url()
    );

    $replace = wp_parse_args( $replace_array, $defaults );

    foreach ( $replace as $replace_shortcode => $replace_value ) {

        $subject = str_replace( array(   '[' . $replace_shortcode . ']'
                                       , '{' . $replace_shortcode . '}' )
                                , $replace_value
                                , $subject );
    }

    // Remove all shortcodes, which is not replaced early.
    if ( $replace_unknown_shortcodes !== false )    
        $subject = preg_replace( '/[\s]{0,}[\[\{]{1}[a-zA-Z0-9.,-_]{0,}[\]\}]{1}[\s]{0,}/', $replace_unknown_shortcodes, $subject );  

    
    return $subject;        
}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  S u p p o r t    f u n c t i o n s        
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    // Get array of images - icons inside of this directory
    function wpbc_dir_list ($directories) {

        // create an array to hold directory list
        $results = array();

        if (is_string($directories)) $directories = array($directories);
        foreach ($directories as $dir) {
	        if ( is_dir( $dir ) ) {
		        $directory = $dir;
	        } else {
		        $directory = WPBC_PLUGIN_DIR . $dir;
	        }
            
            if ( file_exists( $directory ) ) {                                  //FixIn: 5.4.5
                // create a handler for the directory
                $handler = @opendir($directory);
                if ($handler !== false) {
                    // keep going until all files in directory have been read
                    while ($file = readdir($handler)) {

                        // if $file isn't this directory or its parent,
                        // add it to the results array
                        if ($file != '.' && $file != '..' && ( strpos($file, '.css' ) !== false ) )
                            $results[] = array($file, /* WPBC_PLUGIN_URL .*/ $dir . $file,  ucfirst(strtolower( str_replace('.css', '', $file))) );
                    }

                    // tidy up: close the handler
                    closedir($handler);
                }
            }
        }
        // done!
        return $results;
    }
    
    /**
	 * Get absolute URL to  relative plugin path.
     *  Possibly to load minified version of file,  if its exist
     * @param string $path    - path
     * @return string
     */
    function wpbc_plugin_url( $path ) {
        /*
        if ( ( defined( 'WP_BK_MIN' ) ) && ( WP_BK_MIN ) ){
            $path_min = $path;
            if ( substr( $path_min , -3 ) === '.js' ) {
                $path_min = substr( $path_min , 0, -3 ) . '.min.js';
            }
            if ( substr( $path_min , -4 ) === '.css' ) {
                $path_min = substr( $path_min , 0, -4 ) . '.min.css';
            }
            if (  file_exists( trailingslashit( WPBC_PLUGIN_DIR ) . ltrim( $path_min, '/\\' ) )  )  // check if this file exist
                return trailingslashit( WPBC_PLUGIN_URL ) . ltrim( $path_min, '/\\' );
        }
        */
        return trailingslashit( WPBC_PLUGIN_URL ) . ltrim( $path, '/\\' );
    }
   
    
    /**
	 * Check  if such file exist or not.
     * 
     * @param string $path - relative path to  file (relative to plugin folder).
     * @return boolean true | false
     */
    function wpbc_is_file_exist( $path ) {
                             
        if (  file_exists( trailingslashit( WPBC_PLUGIN_DIR ) . ltrim( $path, '/\\' ) )  )  // check if this file exist
            return true;
        else 
            return false;
    }
    
    ////////////////////////////////////////////////////////////////////////////
    // Admin Menu Links
    ////////////////////////////////////////////////////////////////////////////

    /**
	 * Get URL to specific Admin Menu page
     * 
     * @param string $menu_type         -   { booking | add | resources | settings }
     * @param boolean $is_absolute_url  - Absolute or relative url { default: true }
     * @param boolean $is_old           - { default: true } 
     * @return string                   - URL  to  menu
     */
    function wpbc_get_menu_url( $menu_type, $is_absolute_url = true, $is_old = true) {
$is_old = false;        
        switch ( $menu_type) {
            
            case 'booking':                                                     // Bookings
            case 'bookings':
            case 'booking-listing':
            case 'bookings-listing':
            case 'listing':
            case 'overview':
            case 'calendar-overview':                
            case 'timeline':                
                if ( $is_old ) {
                    $link = WPBC_PLUGIN_DIRNAME . '/'. WPBC_PLUGIN_FILENAME . "wpdev-booking";
                } else {
                    $link = 'wpbc';
                }
                break;
                
            case 'add':                                                         // Add New Booking
            case 'add-bookings':
            case 'add-booking':
            case 'new':
            case 'new-bookings':
            case 'new-booking':
                if (  $is_old ) {
                    $link = WPBC_PLUGIN_DIRNAME . '/'. WPBC_PLUGIN_FILENAME . "wpdev-booking-reservation";
                } else {
                    $link = 'wpbc-new';
                }
                break;
                
            case 'resources':                                                   // Resources
            case 'booking-resources':
                if (  $is_old ) {
                    $link = WPBC_PLUGIN_DIRNAME . '/'. WPBC_PLUGIN_FILENAME . "wpdev-booking-resources";
                } else {
                    $link = 'wpbc-resources';
                }
                break;

            case 'settings':                                                    // Settings
            case 'options':
                if (  $is_old ) {
                    $link = WPBC_PLUGIN_DIRNAME . '/'. WPBC_PLUGIN_FILENAME . "wpdev-booking-option";
                } else {
                    $link = 'wpbc-settings';
                }
                break;

            default:                                                            // Bookings
                if ( $is_old ) {
                    $link = WPBC_PLUGIN_DIRNAME . '/'. WPBC_PLUGIN_FILENAME . "wpdev-booking";
                } else {
                    $link = 'wpbc';
                }
                break;
                
                break;
        }
        
        if ( $is_absolute_url ) {
            $link = admin_url( 'admin.php' ) . '?page=' . $link ;
        } 
        
        return $link;        
    }

    // // // // // // // // // // // // // // // // // // // // // // // // // /
    
    /**
	 * Get URL of Booking Listing or Calendar Overview page
     * 
     * @param boolean $is_absolute_url  - Absolute or relative url { default: true }
     * @param boolean $is_old           - { default: true } 
     * @return string                   - URL  to  menu
     */
    function wpbc_get_bookings_url( $is_absolute_url = true, $is_old = true ) {
        return wpbc_get_menu_url( 'booking', $is_absolute_url, $is_old );
    }
    
    /**
	 * Get URL of Booking > Add booking page
     * 
     * @param boolean $is_absolute_url  - Absolute or relative url { default: true }
     * @param boolean $is_old           - { default: true } 
     * @return string                   - URL  to  menu
     */
    function wpbc_get_new_booking_url( $is_absolute_url = true, $is_old = true ) {
        return wpbc_get_menu_url( 'add', $is_absolute_url, $is_old );
    }
    
    /**
	 * Get URL of Booking > Resources page
     * 
     * @param boolean $is_absolute_url  - Absolute or relative url { default: true }
     * @param boolean $is_old           - { default: true } 
     * @return string                   - URL  to  menu
     */
    function wpbc_get_resources_url( $is_absolute_url = true, $is_old = true ) {
        return wpbc_get_menu_url( 'resources', $is_absolute_url, $is_old );
    }
       
    /**
	 * Get URL of Booking > Settings page
     * 
     * @param boolean $is_absolute_url  - Absolute or relative url { default: true }
     * @param boolean $is_old           - { default: true } 
     * @return string                   - URL  to  menu
     */
    function wpbc_get_settings_url( $is_absolute_url = true, $is_old = true ) {
        return wpbc_get_menu_url( 'settings', $is_absolute_url, $is_old );
    }
    
    // // // // // // // // // // // // // // // // // // // // // // // // // /
    
    /**
	 * Check if this Booking Listing or Calendar Overview page
     * @param string $server_param -  'REQUEST_URI' | 'HTTP_REFERER'  Default: 'REQUEST_URI'
     * @return boolean true | false
     */
    function wpbc_is_bookings_page( $server_param = 'REQUEST_URI' ) { 
        // Old
        if (  ( is_admin() ) &&
              ( strpos($_SERVER[ $server_param ],'wpdev-booking.phpwpdev-booking') !== false ) &&
              ( strpos($_SERVER[ $server_param ],'wpdev-booking.phpwpdev-booking-reservation') === false )
            ) {
            return true;
        } 
        // New 
        if (  ( is_admin() ) &&
              ( strpos($_SERVER[ $server_param ],'page=wpbc') !== false ) &&
              ( strpos($_SERVER[ $server_param ],'page=wpbc-') === false )
            ) {
            return true;
        } 
        return false;
    }
    
    /**
	 * Check if this Booking > Add booking page
     * @param string $server_param -  'REQUEST_URI' | 'HTTP_REFERER'  Default: 'REQUEST_URI'
     * @return boolean true | false
     */
    function wpbc_is_new_booking_page( $server_param = 'REQUEST_URI' ) {
        // Old
        if (  ( is_admin() ) &&              
              ( strpos($_SERVER[ $server_param ],'wpdev-booking.phpwpdev-booking-reservation') !== false )
            ) {
            return true;
        } 
        // New 
        if (  ( is_admin() ) &&
              ( strpos($_SERVER[ $server_param ],'page=wpbc-new') !== false )
            ) {
            return true;
        } 
        return false;
    }
    
    /**
	 * Check if this Booking > Resources page
     * @param string $server_param -  'REQUEST_URI' | 'HTTP_REFERER'  Default: 'REQUEST_URI'
     * @return boolean true | false
     */
    function wpbc_is_resources_page( $server_param = 'REQUEST_URI' ) {
        
        // Old
        if (  ( is_admin() ) &&              
              ( strpos($_SERVER[ $server_param ],'wpdev-booking.phpwpdev-booking-resources') !== false )
            ) {
            return true;
        } 
        // New 
        if (  ( is_admin() ) &&
              ( strpos($_SERVER[ $server_param ],'page=wpbc-resources') !== false )
            ) {
            return true;
        } 
        return false;
    }

    /**
	 * Check if this Booking > Settings page
     * @param string $server_param -  'REQUEST_URI' | 'HTTP_REFERER'  Default: 'REQUEST_URI'
     * @return boolean true | false
     */    
    function wpbc_is_settings_page( $server_param = 'REQUEST_URI' ) {
        
        // Old
        if (  ( is_admin() ) &&              
              ( strpos($_SERVER[ $server_param ],'wpdev-booking.phpwpdev-booking-option') !== false )
            ) {
            return true;
        } 
        // New 
        if (  ( is_admin() ) &&
              ( strpos($_SERVER[ $server_param ],'page=wpbc-settings') !== false )
            ) {
            return true;
        } 
        return false;
    }
    
    ////////////////////////////////////////////////////////////////////////////
    
        
    /**
	 * Insert New Line symbols after <br> tags. Usefull for the settings pages to  show in redable view
     * 
     * @param type $param
     * @return type
     */
    function wpbc_nl_after_br($param) {
        
        $value = preg_replace( "@(&lt;|<)br\s*/?(&gt;|>)(\r\n)?@", "<br/>", $param );
        
        return $value;
    }
    

    /**
     * Replace ** to <strong> and * to  <em>
     * 
     * @param String $text
     * @return string
     */
    if ( ! function_exists( 'wpbc_recheck_strong_symbols' ) ) { 
    function wpbc_recheck_strong_symbols( $text ){
    
        $patterns =  '/(\*\*)(\s*[^\*\*]*)(\*\*)/';    
        $replacement = '<strong>${2}</strong>';
        $value_return = preg_replace($patterns, $replacement, $text);

        $patterns =  '/(\*)(\s*[^\*]*)(\*)/';    
        $replacement = '<em>${2}</em>';
        $value_return = preg_replace($patterns, $replacement, $value_return);

        return $value_return;
    }
    }
    
    
    // Set URL from absolute to relative (starting from /)                            
    function wpbc_set_relative_url( $url ){

        $url = esc_url_raw($url);

        $url_path = parse_url($url,  PHP_URL_PATH);
        $url_path =  ( empty($url_path) ? $url : $url_path );

        $url =  trim($url_path, '/');
        return  '/' . $url;
    }
    
    // Get Correct Relative URL 
    function wpbc_make_link_relative( $link ){

        if ( $link  == get_option('siteurl') ) 
            $link = '/';
        $link = '/' . trim( wp_make_link_relative( $link ), '/' ); 

        return $link;        
    }

    // Get Correct Absolute URL 
    function wpbc_make_link_absolute( $link ){
            
        if ( ( $link  != home_url() ) && ( strpos($link, 'http') !== 0 ) ) {
	        $link = apply_bk_filter( 'wpdev_check_for_active_language', $link );           //FixIn: 8.4.5.1
	        $link = home_url() . '/' . trim( wp_make_link_relative( $link ), '/' );        //FixIn: 7.0.1.20
        }
        return esc_js( $link ) ;
    }

    
    function wpdev_bk_arraytolower( $array ){
        return unserialize( strtolower( serialize( $array ) ) );
    }



    function get_bk_current_user_id() {
        $user = wp_get_current_user();
        return ( isset( $user->ID ) ? (int) $user->ID : 0 );
    }


    // Get form content for table
    function get_booking_form_show() {

        $booking_form_show = apply_bk_filter( 'wpbc_get_free_booking_show_form' );
        return  $booking_form_show;

        /*
        $booking_form_field_active1     = get_bk_option( 'booking_form_field_active1');
        $booking_form_field_label1      = get_bk_option( 'booking_form_field_label1');
        $booking_form_field_label1      = apply_bk_filter('wpdev_check_for_active_language', $booking_form_field_label1 );
        
        $booking_form_field_active2     = get_bk_option( 'booking_form_field_active2');
        $booking_form_field_label2      = get_bk_option( 'booking_form_field_label2');
        $booking_form_field_label2      = apply_bk_filter('wpdev_check_for_active_language', $booking_form_field_label2 );
        
        $booking_form_field_active3     = get_bk_option( 'booking_form_field_active3');
        $booking_form_field_label3      = get_bk_option( 'booking_form_field_label3');
        $booking_form_field_label3      = apply_bk_filter('wpdev_check_for_active_language', $booking_form_field_label3 );
        
        $booking_form_field_active4     = get_bk_option( 'booking_form_field_active4');
        $booking_form_field_label4      = get_bk_option( 'booking_form_field_label4');
        $booking_form_field_label4      = apply_bk_filter('wpdev_check_for_active_language', $booking_form_field_label4 );
        
        $booking_form_field_active5     = get_bk_option( 'booking_form_field_active5');
        $booking_form_field_label5      = get_bk_option( 'booking_form_field_label5');
        $booking_form_field_label5      = apply_bk_filter('wpdev_check_for_active_language', $booking_form_field_label5 );
        
        $booking_form_field_active6     = get_bk_option( 'booking_form_field_active6');
        $booking_form_field_label6      = get_bk_option( 'booking_form_field_label6');
        $booking_form_field_label6      = apply_bk_filter('wpdev_check_for_active_language', $booking_form_field_label6 );
        
        $booking_form_show = '<div style="text-align:left;word-wrap: break-word;">';
        if ($booking_form_field_active1 != 'Off')
        $booking_form_show.='<strong>'.$booking_form_field_label1.'</strong>: <span class="fieldvalue">[name]</span><br/>';
        if ($booking_form_field_active2 != 'Off')
        $booking_form_show.='<strong>'.$booking_form_field_label2.'</strong>: <span class="fieldvalue">[secondname]</span><br/>';
        if ($booking_form_field_active3 != 'Off')
        $booking_form_show.='<strong>'.$booking_form_field_label3.'</strong>: <span class="fieldvalue">[email]</span><br/>';
        if ($booking_form_field_active6 == 'On')
        $booking_form_show.='<strong>'.$booking_form_field_label6.'</strong>: <span class="fieldvalue">[visitors]</span><br/>';
        if ($booking_form_field_active4 != 'Off')
        $booking_form_show.='<strong>'.$booking_form_field_label4.'</strong>: <span class="fieldvalue">[phone]</span><br/>';
        if ($booking_form_field_active5 != 'Off')
        $booking_form_show.='<strong>'.$booking_form_field_label5.'</strong>: <br /><span class="fieldvalue">[details]</span>';
        $booking_form_show.='</div>';
            
        return $booking_form_show;
        */
    }

    // Parse form content
    function get_form_content ( $formdata, $bktype =-1, $booking_form_show ='', $extended_params = array() ) {
//debuge($formdata, $bktype , $booking_form_show, $extended_params);

        if ( $bktype == -1 ) {
            if ( function_exists('get__default_type') ) 
                $bktype = get__default_type();
            else 
                $bktype = 1;
        }

        if ( $booking_form_show === '' ) {
            
            if ( ! class_exists('wpdev_bk_personal') ) {
                
                $booking_form_show  = get_booking_form_show();
                
            } else {
                
                $booking_form_show  = get_bk_option( 'booking_form_show' );

                if ( class_exists('wpdev_bk_biz_m') ) {

                    // BM :: Get default Custom Form  of Resource
                    $my_booking_form_name = apply_bk_filter( 'wpbc_get_default_custom_form', 'standard', $bktype );
                    if ( ( $my_booking_form_name != 'standard' ) && ( ! empty( $my_booking_form_name ) ) )
                        $booking_form_show = apply_bk_filter( 'wpdev_get_booking_form_content', $booking_form_show, $my_booking_form_name );

                    //MU :: if resource of "Regular User" - then  GET STANDARD user form ( if ( get_bk_option( 'booking_is_custom_forms_for_regular_users' ) !== 'On' ) )
					$booking_form_show = apply_bk_filter( 'wpbc_multiuser_get_booking_form_show_of_regular_user',  $booking_form_show, $bktype, $my_booking_form_name );	//FixIn: 8.1.3.19
                }                
            }
                
            // Language
            $booking_form_show =  apply_bk_filter('wpdev_check_for_active_language', $booking_form_show );  
        }

        
//debuge($formdata, $bktype, $booking_form_show);
        $formdata_array = explode('~',$formdata);
        $formdata_array_count = count($formdata_array);
        $email_adress='';
        $name_of_person = '';
        $coupon_code = '';
        $secondname_of_person = '';
        $visitors_count = 1;
        $select_box_selected_items = array();
        $check_box_selected_items = array();
        $all_fields_array = array();
        $all_fields_array_without_types = array();
        $checkbox_value=array();
   
        for ( $i=0 ; $i < $formdata_array_count ; $i++) {
            
            if ( empty( $formdata_array[$i] ) ) {
                continue;
            }
            
            $elemnts = explode('^',$formdata_array[$i]);
//debuge($elemnts);
            $type = $elemnts[0];
            $element_name = $elemnts[1];
            $value = $elemnts[2];            
            $value = nl2br($value);                                             // Add BR instead if /n elements
            
            $count_pos = strlen( $bktype );

            $type_name = $elemnts[1];
            $type_name = str_replace('[]','',$type_name);
            if ($bktype == substr( $type_name,  -1*$count_pos ) ) $type_name = substr( $type_name, 0, -1*$count_pos ); // $type_name = str_replace($bktype,'',$elemnts[1]);

            if ( ( ($type_name == 'email') || ($type == 'email')  ) && ( empty($email_adress) )   )    $email_adress = $value;  //FixIn: 6.0.1.9
            if ( ($type_name == 'coupon') || ($type == 'coupon')  )             $coupon_code = $value;
            if ( ($type_name == 'name') || ($type == 'name')  )                 $name_of_person = $value;
            if ( ($type_name == 'secondname') || ($type == 'secondname')  )     $secondname_of_person = $value;
            if ( ($type_name == 'visitors') || ($type == 'visitors')  )         $visitors_count = $value;

            //FixIn: TimeFreeGenerator //FixIn: 8.2.1.26 - only for Booking Calendar Free version  show times in AM/PM fomrat  or other depend from  time format  at the WordPress > Settings > General  page.
//            if ( ! class_exists('wpdev_bk_personal') ) {									//FixIn: 8.4.2.7
//	            if ( ( $type_name == 'rangetime' ) || ( $type == 'rangetime' ) ) {			//FixIn: 8.4.2.11
//	            	$value = wpbc_time_slot_in_format(  $value );
//	            }
//            }

            if ($type == 'checkbox') {
//debuge($type_name , $type,   $element_name, $value);
//       children   checkbox children11[]    true
                if ($value == 'true') {
                    $value = __('yes' ,'booking');
                }

                if ($value == 'false') {
                    $value = __('no' ,'booking');
                }

                if  ( $value !='' )
                    if ( ( isset($checkbox_value[ str_replace('[]','',(string) $element_name) ]) ) && ( is_array($checkbox_value[ str_replace('[]','',(string) $element_name) ]) ) ) {
                        $checkbox_value[ str_replace('[]','',(string) $element_name) ][] = $value;
                    } else {
                        //if ($value != __('yes' ,'booking') )
                            $checkbox_value[ str_replace('[]','',(string) $element_name) ] = array($value);
                        //else
                            //$checkbox_value[ str_replace('[]','',(string) $element_name) ] = 'checkbox';
                    }

                $value = '['. $type_name .']';                                  //FixIn: 6.1.1.14
                //$value = $value .' ' . '['. $type_name .']';              
                    
            }
//debuge($value, $checkbox_value);
            if ( ( $type == 'select-one') || ( $type == 'select-multiple' )  || ( $type == 'radio' ) ) { // add all select box selected items to return array
                $select_box_selected_items[$type_name] = $value;
            }
//debuge($type, $value);
            if ( ($type == 'checkbox') && (isset($checkbox_value)) ) {
                if (isset(  $checkbox_value[ str_replace('[]','',(string) $element_name) ] )) {
                    if (is_array(  $checkbox_value[ str_replace('[]','',(string) $element_name) ] ))
                        $current_checkbox_value = implode(', ', $checkbox_value[ str_replace('[]','',(string) $element_name) ] );
                    else
                        $current_checkbox_value = $checkbox_value[ str_replace('[]','',(string) $element_name) ] ;
                } else {
                    $current_checkbox_value = '';
                }
                $all_fields_array[ str_replace('[]','',(string) $element_name) ] = $current_checkbox_value;
                $all_fields_array_without_types[ substr(   str_replace('[]','',(string) $element_name), 0 , -1*strlen( $bktype ) )  ] = $current_checkbox_value;

                $check_box_selected_items[$type_name] = $current_checkbox_value;
            } else {

            	//FixIn: 8.4.2.11
 				$all_fields_array_without_types[ substr(   str_replace('[]','',(string) $element_name), 0 , -1*strlen( $bktype ) )   ] = $value;
				/**
				   ['_all_']        => $all_fields_array,        CONVERT to  " AM/PM "
				   ['_all_fields_'] => $all_fields_array_without_types => in " 24 hour " format - for ability correct  calculate Booking > Resources > Advanced cost page.
				 */
	            if ( ( $type_name == 'rangetime' ) || ( $type == 'rangetime' ) ) {
	            	$value = wpbc_time_slot_in_format(  $value );
	            }
				$all_fields_array[ str_replace('[]','',(string) $element_name) ] = $value;
	            //FixIn: 8.4.2.11

            }
            $is_skip_replace = false;                                           //FixIn: 7.0.1.45
            if ( ( $type == 'radio' ) && empty( $value ) )        
                    $is_skip_replace = true;
            if( ! $is_skip_replace )
                $booking_form_show = str_replace( '['. $type_name .']', $value ,$booking_form_show);
        }

//debuge($all_fields_array,$all_fields_array_without_types);
        if (! isset($all_fields_array_without_types[ 'booking_resource_id'  ])) $all_fields_array_without_types[ 'booking_resource_id'  ] = $bktype;
        if (! isset($all_fields_array_without_types[ 'resource_id'  ]))         $all_fields_array_without_types[ 'resource_id'  ] = $bktype;
        if (! isset($all_fields_array_without_types[ 'type_id'  ]))             $all_fields_array_without_types[ 'type_id'  ] = $bktype;

        if (! isset($all_fields_array_without_types[ 'type'  ]))                $all_fields_array_without_types[ 'type'  ] = $bktype;
        if (! isset($all_fields_array_without_types[ 'resource'  ]))            $all_fields_array_without_types[ 'resource'  ] = $bktype;

        foreach ($extended_params as $key_param=>$value_param) {
            if (! isset($all_fields_array_without_types[  $key_param  ]))            $all_fields_array_without_types[ $key_param  ] = $value_param;
        }
//debuge($booking_form_show, $all_fields_array_without_types);die;
        foreach ( $all_fields_array_without_types as $key_param=>$value_param) {                                  //FixIn: 6.1.1.4
            if (   ( gettype ( $value_param ) != 'array' ) 
                && ( gettype ( $value_param ) != 'object' ) 
                ) {
                $booking_form_show = str_replace( '['. $key_param .']', $value_param ,$booking_form_show);
				
				$all_fields_array_without_types[ $key_param ] = str_replace( "&amp;", '&', $value_param );					//FixIn:7.1.2.12
            }
			
			
        }
        // Remove all shortcodes, which is not replaced early.
        $booking_form_show = preg_replace ('/[\s]{0,}\[[a-zA-Z0-9.,-_]{0,}\][\s]{0,}/', '', $booking_form_show);  //FixIn: 6.1.1.4
        
		$booking_form_show = str_replace( "&amp;", '&', $booking_form_show );											//FixIn:7.1.2.12
		
        $return_array =   array('content' => $booking_form_show,
                                'email' => $email_adress,
                                'name' => $name_of_person,
                                'secondname' => $secondname_of_person ,
                                'visitors' => $visitors_count ,
                                'coupon'=>$coupon_code ,
                                '_all_' => $all_fields_array,
                                '_all_fields_'=>$all_fields_array_without_types
                               ) ;

        foreach ($select_box_selected_items as $key=>$value) {
            if (! isset($return_array[$key])) {
                $return_array[$key] = $value;
            }
        }
        foreach ($check_box_selected_items as $key=>$value) {
            if (! isset($return_array[$key])) {
                $return_array[$key] = $value;
            }
        }

        return $return_array ;

    }

    
    
    // Get fields from booking form at the settings page or return false if no fields
    function wpbc_get_fields_list_in_booking_form() {


        $booking_forms   = array();
        $booking_forms[] = array( 'name' => 'standard', 'form' => get_bk_option( 'booking_form' ), 'content' => get_bk_option( 'booking_form_show' ) );

        $is_can = apply_bk_filter('multiuser_is_user_can_be_here', true, 'only_super_admin');
        if ( ( $is_can ) || ( get_bk_option( 'booking_is_custom_forms_for_regular_users' ) === 'On' ) ) {
            $booking_forms_extended = get_bk_option( 'booking_forms_extended');
            if ($booking_forms_extended !== false) {
                if ( is_serialized( $booking_forms_extended ) ) {
                    $booking_forms_extended = unserialize($booking_forms_extended);
                    foreach ($booking_forms_extended as $form_extended) {
                        $booking_forms[] = $form_extended;
                    }
                }
            }
        }    

        foreach ($booking_forms as $form_key => $booking_form_element) {
            $booking_form  = $booking_form_element['form'];


            // $booking_form  = get_bk_option( 'booking_form' );
            $types = 'text[*]?|email[*]?|time[*]?|textarea[*]?|select[*]?|checkbox[*]?|radio|acceptance|captchac|captchar|file[*]?|quiz';
            $regex = '%\[\s*(' . $types . ')(\s+[a-zA-Z][0-9a-zA-Z:._-]*)([-0-9a-zA-Z:#_/|\s]*)?((?:\s*(?:"[^"]*"|\'[^\']*\'))*)?\s*\]%';
            $regex2 = '%\[\s*(country[*]?|starttime[*]?|endtime[*]?)(\s*[a-zA-Z]*[0-9a-zA-Z:._-]*)([-0-9a-zA-Z:#_/|\s]*)*((?:\s*(?:"[^"]*"|\'[^\']*\'))*)?\s*\]%';
            $fields_count = preg_match_all($regex, $booking_form, $fields_matches) ;
            $fields_count2 = preg_match_all($regex2, $booking_form, $fields_matches2) ;

            //Gathering Together 2 arrays $fields_matches  and $fields_matches2
            foreach ($fields_matches2 as $key => $value) {
                if ($key == 2) $value = $fields_matches2[1];
                foreach ($value as $v) {
                    $fields_matches[$key][count($fields_matches[$key])]  = $v;
                }
            }
            $fields_count += $fields_count2;

            $booking_forms[$form_key]['num'] = $fields_count;
            $booking_forms[$form_key]['listing'] = array();//$fields_matches;

            $booking_forms[$form_key]['listing']['labels'] = $fields_matches[2];
            $booking_forms[$form_key]['listing']['fields'] = $fields_matches[2];

            foreach ($fields_matches[1] as $key_fm=>$value_fm) {
                $fields_matches[1][$key_fm] = trim(str_replace('*','',$value_fm));
            }

            $booking_forms[$form_key]['listing']['fields_type'] = $fields_matches[1];

    //        if ($booking_form_element['name'] == 'standard') {            
    //            array_unshift($booking_forms[$form_key]['listing']['labels'], __('None' ,'booking') );
    //            array_unshift($booking_forms[$form_key]['listing']['fields'], '' );
    //            array_unshift($booking_forms[$form_key]['listing']['fields_type'], 'text' );
    //            $booking_forms[$form_key]['num']++;
    //        }

            // Reset
            unset( $booking_forms[$form_key]['form'] );
            unset( $booking_forms[$form_key]['content'] );
        }

        return $booking_forms;

    }

    

    function parse_calendar_options($bk_otions ){
        
            if (empty($bk_otions)) return false;

            /* $matches    structure:
             * Array
                (
                    [0] => Array
                        (
                            [0] => {calendar months="6" months_num_in_row="2" width="284px" cell_height="40px"}, 
                            [1] => calendar
                            [2] => months="6" months_num_in_row="2" width="284px" cell_height="40px"
                        )

                    [1] => Array
                        (
                            [0] => {select-day condition="weekday" for="5" value="3"}, 
                            [1] => select-day
                            [2] => condition="weekday" for="5" value="3"
                        )
                     .....
                )
             */
//debuge($bk_otions);                        
            $pattern_to_search='%\s*{([^\s]+)\s*([^}]+)\s*}\s*[,]?\s*%';
            preg_match_all($pattern_to_search, $bk_otions, $matches, PREG_SET_ORDER);
            foreach ($matches as $value) {
                if ($value[1] == 'calendar') {
                    $paramas = $value[2];
                    $paramas = trim($paramas);
                    $paramas = explode(' ',$paramas);
                    $options = array();
                    foreach ($paramas as $vv) {
                        if (! empty($vv)) {
                            $vv = trim($vv);
                            $vv = explode('=',$vv);    
                            $options[$vv[0]] = trim($vv[1]);
                        }
                    }
                    if (count($options)==0) return false;
                    else                    return $options;
                }
            }
        // We are do  not have the "calendar" options in the shortcode    
        return false;
    }
    


    // Get version
    function get_bk_version(){ 
        $version = 'free';
        if ( class_exists( 'wpdev_bk_personal' ) ) $version = 'personal';
        if ( class_exists( 'wpdev_bk_biz_s' ) )    $version = 'biz_s';
        if ( class_exists( 'wpdev_bk_biz_m' ) )    $version = 'biz_m';
        if ( class_exists( 'wpdev_bk_biz_l' ) )    $version = 'biz_l';
        return $version;
    }
    

    /**
	 * Check if user accidentially update Booking Calendar Paid version to Free
     * 
     * @return bool
     */
    function wpbc_is_updated_paid_to_free() {
        
        if ( ( wpbc_is_table_exists('bookingtypes') ) && ( ! class_exists('wpdev_bk_personal') )  ) 
            return  true;
        else
            return false;                    
    }
    
    ////////////////////////////////////////////////////////////////////////////
    function wpbc_get_ver_sufix() {               
        if( strpos( strtolower(WPDEV_BK_VERSION) , 'multisite') !== false  ) {
            $v_type = '-multi';                         
        } else if( strpos( strtolower(WPDEV_BK_VERSION) , 'develop') !== false  ) {
            $v_type = '-dev';
        } else {
            $v_type = '';
        }  
        $v = '';
        if (class_exists('wpdev_bk_personal'))  $v = 'ps'. $v_type;
        if (class_exists('wpdev_bk_biz_s'))     $v = 'bs'. $v_type;
        if (class_exists('wpdev_bk_biz_m'))     $v = 'bm'. $v_type;
        if (class_exists('wpdev_bk_biz_l'))     $v = 'bl'. $v_type;
        if (class_exists('wpdev_bk_multiuser')) $v = '';        
        return $v ;
    }
    
    
    function wpbc_up_link() {
        if ( ! wpbc_is_this_demo() ) 
             $v = wpbc_get_ver_sufix();
        else $v = '';
        return 'https://wpbookingcalendar.com/' . ( ( empty($v) ) ? '' : 'upgrade-' . $v  . '/' ) ;
    }
    
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // DB - cheking if table, field or index exists
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * Check if table exist
     * 
     * @global type $wpdb
     * @param string $tablename
     * @return 0|1
     */
    function wpbc_is_table_exists( $tablename ) {
        
        global $wpdb;

	    //FixIn: 8.7.3.16
	    if (
		    ( ( ! empty( $wpdb->prefix ) ) && ( strpos( $tablename, $wpdb->prefix ) === false ) )
		    || ( '_' == $wpdb->prefix )
	    ) {
		    $tablename = $wpdb->prefix . $tablename;
	    }

        $sql_check_table = $wpdb->prepare("SHOW TABLES LIKE %s" , $tablename ); //FixIn: 5.4.3
        
        $res = $wpdb->get_results( $sql_check_table );
        
        return count($res);                                                     //FixIn: 5.4.3
        /*
        $sql_check_table = $wpdb->prepare("
            SELECT COUNT(*) AS count
            FROM information_schema.tables
            WHERE table_schema = '". DB_NAME ."'
            AND table_name = %s " , $tablename );

        $res = $wpdb->get_results( $sql_check_table );
        return $res[0]->count;*/
    }

    
    /**
     * Check if table exist
     * 
     * @global type $wpdb
     * @param string $tablename
     * @param type $fieldname
     * @return 0|1
     */
    function wpbc_is_field_in_table_exists( $tablename , $fieldname) {
        global $wpdb;
        if ( (! empty($wpdb->prefix) ) && ( strpos($tablename, $wpdb->prefix) === false ) ) $tablename = $wpdb->prefix . $tablename ;
        $sql_check_table = "SHOW COLUMNS FROM {$tablename}" ;

        $res = $wpdb->get_results( $sql_check_table );

        foreach ($res as $fld) {
            if ($fld->Field == $fieldname) return 1;
        }

        return 0;
    }

    
    /**
     * Check if index exist
     * 
     * @global type $wpdb
     * @param string $tablename
     * @param type $fieldindex
     * @return 0|1
     */
    function wpbc_is_index_in_table_exists( $tablename , $fieldindex) {
        global $wpdb;
        if ( (! empty($wpdb->prefix) ) && ( strpos($tablename, $wpdb->prefix) === false ) ) $tablename = $wpdb->prefix . $tablename ;
        $sql_check_table = $wpdb->prepare("SHOW INDEX FROM {$tablename} WHERE Key_name = %s", $fieldindex );       
        $res = $wpdb->get_results( $sql_check_table );
        if (count($res)>0) return 1;
        else               return 0;
    }

    
    
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    // Replace the shortcodes in the form by values from array
    function replace_bk_shortcodes_in_form($form, $field_values=array(), $is_delete_unknown_shortcodes = false) {

        $new_form = $form;

        // Patern for searching of the shortcodes in some form
        $any_shortcodes = '[a-zA-Z][0-9a-zA-Z:._-]*';
        $regex = '%\[\s*(' . $any_shortcodes . ')\s*\]%';

        // Search  any shortcodes in the $form
        preg_match_all($regex, $form, $matches, PREG_PATTERN_ORDER);   // PREG_PATTERN_ORDER, PREG_SET_ORDER, PREG_OFFSET_CAPTURE

        // Loop  all found shortcodes
        if (isset($matches[1])) {
                foreach ($matches[1] as $key=>$field) {

                    //$field             // secondname
                    //$matches[0][$key]  // [secondname]
                    //$matches[1][$key]  // secondname

                    if (isset($field_values[$field])) $replace_value = $field_values[$field];
                    else {
                        if ($is_delete_unknown_shortcodes) $replace_value = '';
                        else $replace_value = $matches[0][$key];
                    }

                    $new_form = str_replace( $matches[0][$key] , $replace_value, $new_form);
                }
        }
        return  $new_form;
    }



        /**
	 * Get fields from booking form at the settings page or return false if no fields
         * 
         * @param string $booking_form 
         * @return mixed  false | array( $fields_count, $fields_matches )
         */
        function wpbc_get_fields_from_booking_form( $booking_form = '' ){
            if ( empty( $booking_form )  )
                $booking_form  = get_bk_option( 'booking_form' );
            $types = 'text[*]?|email[*]?|time[*]?|textarea[*]?|select[*]?|checkbox[*]?|radio|acceptance|captchac|captchar|file[*]?|quiz';
            $regex = '%\[\s*(' . $types . ')(\s+[a-zA-Z][0-9a-zA-Z:._-]*)([-0-9a-zA-Z:#_/|\s]*)?((?:\s*(?:"[^"]*"|\'[^\']*\'))*)?\s*\]%';
            $regex2 = '%\[\s*(country[*]?|starttime[*]?|endtime[*]?)(\s*[a-zA-Z]*[0-9a-zA-Z:._-]*)([-0-9a-zA-Z:#_/|\s]*)*((?:\s*(?:"[^"]*"|\'[^\']*\'))*)?\s*\]%';
            $fields_count = preg_match_all($regex, $booking_form, $fields_matches) ;
            $fields_count2 = preg_match_all($regex2, $booking_form, $fields_matches2) ;

            //Gathering Together 2 arrays $fields_matches  and $fields_matches2
            foreach ($fields_matches2 as $key => $value) {
                if ($key == 2) $value = $fields_matches2[1];
                foreach ($value as $v) {
                    $fields_matches[$key][count($fields_matches[$key])]  = $v;
                }
            }
            $fields_count += $fields_count2;

            if ($fields_count>0) return array($fields_count, $fields_matches);
            else return false;
        }
    

        /**
	 * Get Get only SELECT, CHCKBOX & RADIO fields from booking form at the settings page or return false if no fields
         * 
         * @param string $booking_form 
         * @return mixed  false | array( $fields_count, $fields_matches )
         */
        function wpbc_get_select_checkbox_fields_from_booking_form( $booking_form = '' ){
            
            if ( empty( $booking_form )  )  
                $booking_form  = get_bk_option( 'booking_form' );
            
            $types = 'select[*]?|checkbox[*]?|radio[*]?';                                                                //FixIn: 8.1.3.7
            $regex = '%\[\s*(' . $types . ')(\s+[a-zA-Z][0-9a-zA-Z:._-]*)([-0-9a-zA-Z:#_/|\s]*)?((?:\s*(?:"[^"]*"|\'[^\']*\'))*)?\s*\]%';
            
            $fields_count = preg_match_all($regex, $booking_form, $fields_matches) ;
            
            if ( $fields_count > 0 ) 
                 return array( $fields_count, $fields_matches );
            else return false;
        }
    

    /**
	 * Get parameters of shortcode in string
     * 
     * @param string $shortcode
     * @param string $subject
     * @return mixed: array | false
     */    
    function wpbc_get_params_of_shortcode_in_string( $shortcode, $subject , $pos = 0 ) {   //FixIn: 7.0.1.8     7.0.1.52
        $pos = strpos($subject, '['.$shortcode , $pos );                                   //FixIn: 7.0.1.52 
        if ( $pos !== false ) {
           $pos2 = strpos($subject, ']', ($pos+2));

           $my_params = substr($subject, $pos+strlen('['.$shortcode), ( $pos2-$pos-strlen('['.$shortcode) ) );

            $pattern_to_search = '%\s*([^=]*)=[\'"]([^\'"]*)[\'"]\s*%';
            preg_match_all($pattern_to_search, $my_params, $keywords, PREG_SET_ORDER);

            foreach ($keywords as $value) {
                if (count($value)>1) {
                    $shortcode_params[ $value[1] ] = trim($value[2]);
                }
            }
            $shortcode_params['start']=$pos+1;
            $shortcode_params['end']=$pos2;

            return $shortcode_params;
        } else
           return false;
    }
        
        
    //   Get header info from this file, just for compatibility with WordPress 2.8 and older versions //////////////////////////////////////
    if (!function_exists ('get_file_data_wpdev')) {
    function get_file_data_wpdev( $file, $default_headers, $context = '' ) {
        // We don't need to write to the file, so just open for reading.
        $fp = fopen( $file, 'r' );

        // Pull only the first 8kiB of the file in.
        $file_data = fread( $fp, 8192 );

        // PHP will close file handle, but we are good citizens.
        fclose( $fp );

        if( $context != '' ) {
            $extra_headers = array();//apply_filters( "extra_$context".'_headers', array() );

            $extra_headers = array_flip( $extra_headers );
            foreach( $extra_headers as $key=>$value ) {
                $extra_headers[$key] = $key;
            }
            $all_headers = array_merge($extra_headers, $default_headers);
        } else {
            $all_headers = $default_headers;
        }

        foreach ( $all_headers as $field => $regex ) {
            preg_match( '/' . preg_quote( $regex, '/' ) . ':(.*)$/mi', $file_data, ${$field});
            if ( !empty( ${$field} ) )
                ${$field} =  trim(preg_replace("/\s*(?:\*\/|\?>).*/", '',  ${$field}[1] ));
            else
                ${$field} = '';
        }

        $file_data = compact( array_keys( $all_headers ) );

        return $file_data;
    }
    }


    // Security  
    function escape_any_xss($formdata){

        $formdata_array = explode('~',$formdata);
        $formdata_array_count = count($formdata_array);

        $clean_formdata = '';

        for ( $i=0 ; $i < $formdata_array_count ; $i++) {
            $elemnts = explode('^',$formdata_array[$i]);
            if ( count( $elemnts ) > 2 ) {
                $type = $elemnts[0];
                $element_name = $elemnts[1];
                $value = $elemnts[2];

                $value = wpbc_clean_parameter( $value );

                // convert to new value
                $clean_formdata .= $type . '^' . $element_name . '^' . $value . '~';
            }
        }

        $clean_formdata = substr($clean_formdata, 0, -1);
        $clean_formdata = str_replace('%', '&#37;', $clean_formdata ); // clean any % from the form, because otherwise, there is problems with SQL prepare function
        
        return $clean_formdata;
    }

    
    /**
	 * Check  paramter  if it number or comma separated list  of numbers
     * 
     * @global type $wpdb
     * @param string $value
     * @return string
     * 
     * Exmaple:
                        wpbc_clean_digit_or_csd( '12,a,45,9' )                  => '12,0,45,9'
     * or
                        wpbc_clean_digit_or_csd( '10a' )                        => '10
     * or
                        wpbc_clean_digit_or_csd( array( '12,a,45,9', '10a' ) )  => array ( '12,0,45,9',  '10' )
     */
    function wpbc_clean_digit_or_csd( $value ) {                                //FixIn:6.2.1.4 
        
        if ( $value === '' ) return $value;
        
        
        if ( is_array( $value ) ) {
            foreach ( $value as $key => $check_value ) {
                $value[ $key ] = wpbc_clean_digit_or_csd( $check_value ); 
            }
            return $value;
        }
        
        
        global $wpdb;
        
        $value = str_replace( ';', ',', $value );

        $array_of_nums = explode(',', $value);

        $result = array();
        foreach ($array_of_nums as $check_element) {
            //$result[] = $wpdb->prepare( "%d", $check_element );
            $result[] = intval( $check_element );						//FixIn: 8.0.2.10
        }
        $result = implode(',', $result );
        return $result;
    }

    
    /**
	 * Cehck  about Valid date,  like 2016-07-20 or digit
     * 
     * @param string $value
     * @return string or int
     */
    function wpbc_clean_digit_or_date( $value ) {                               //FixIn:6.2.1.4
    
        if ( $value === '' ) return $value;
        
        if ( preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $value ) ) {
            
            return $value;                                                      // Date is valid in format: 2016-07-20
        } else {
            return intval( $value );
        }
        
    }
    
    
    // check $value for injection here
    function wpbc_clean_parameter( $value ) {
        
        $value = preg_replace( '/<[^>]*>/', '', $value );                       // clean any tags
        $value = str_replace( '<', ' ', $value ); 
        $value = str_replace( '>', ' ', $value ); 
        $value = strip_tags( $value );
                
        // Clean SQL injection    
        $value = esc_sql( $value );		
		$value = esc_textarea( $value );																				//FixIn: 7.1.1.2
		
        return $value; 
    }

    
    function wpbc_esc_like( $value_trimmed ) {
 
        global $wpdb;
        if ( method_exists( $wpdb ,'esc_like' ) )
            return $wpdb->esc_like( $value_trimmed );                           // Its require minimum WP 4.0.0
        else
            return addcslashes( $value_trimmed, '_%\\' );                       // Direct implementation  from $wpdb->esc_like(
    }
    
    
    /**
	 * Clean user string for using in SQL LIKE statement - append to  LIKE sql
     * 
     * @param string $value - to clean
     * @return string       - escaped
     *                                  Exmaple:    
     *                                              $search_escaped_like_title = wpbc_clean_like_string_for_append_in_sql_for_db( $input_var );
     * 
     *                                              $where_sql = " WHERE title LIKE ". $search_escaped_like_title ." ";
     */
    function wpbc_clean_like_string_for_append_in_sql_for_db( $value ) {
        global $wpdb;
        
        $value_trimmed = trim( stripslashes( $value ) );
	$wild = '%';	
	$like = $wild . wpbc_esc_like( $value_trimmed ) . $wild;
	$sql  = $wpdb->prepare( "'%s'", $like );

        return $sql;    
        
        
	/* Help:
         * First half of escaping for LIKE special characters % and _ before preparing for MySQL.
	 * Use this only before wpdb::prepare() or esc_sql().  Reversing the order is very bad for security.
	 *
	 * Example Prepared Statement:
	 *
	 *     $wild = '%';
	 *     $find = 'only 43% of planets';
	 *     $like = $wild . wpbc_esc_like( $find ) . $wild;
	 *     $sql  = $wpdb->prepare( "SELECT * FROM $wpdb->posts WHERE post_content LIKE '%s'", $like );
	 *
	 * Example Escape Chain:
	 *
	 *     $sql  = esc_sql( wpbc_esc_like( $input ) );
	 */        

    }
    
    
    /**
	 * Clean string for using in SQL LIKE requests inside single quotes:    WHERE title LIKE '%". $escaped_search_title ."%'
     *  Replaced _ to \_     % to \%      \   to   \\
     * @param string $value - to clean
     * @return string       - escaped
     *                                  Exmaple:    
     *                                              $search_escaped_like_title = wpbc_clean_like_string_for_db( $input_var );
     * 
     *                                              $where_sql = " WHERE title LIKE '%". $search_escaped_like_title ."%' ";
     * 
     *                                  Important! Use SINGLE quotes after in SQL query:  LIKE '%".$data."%'
     */
    function wpbc_clean_like_string_for_db( $value ){

        global $wpdb;
        
        $value_trimmed = trim( stripslashes( $value ) );

        $value_trimmed =  wpbc_esc_like( $value_trimmed );

        $value = trim( $wpdb->prepare( "'%s'",  $value_trimmed ) , "'" );

        return $value;
        
	/* Help:
         * First half of escaping for LIKE special characters % and _ before preparing for MySQL.
	 * Use this only before wpdb::prepare() or esc_sql().  Reversing the order is very bad for security.
	 *
	 * Example Prepared Statement:
	 *
	 *     $wild = '%';
	 *     $find = 'only 43% of planets';
	 *     $like = $wild . wpbc_esc_like( $find ) . $wild;
	 *     $sql  = $wpdb->prepare( "SELECT * FROM $wpdb->posts WHERE post_content LIKE '%s'", $like );
	 *
	 * Example Escape Chain:
	 *
	 *     $sql  = esc_sql( wpbc_esc_like( $input ) );
	 */        
    }
    
    
    /**
	 * Escape string from SQL for the HTML form field
     * 
     * @param string $value
     * @return string
     * 
     * Used: esc_sql function.
     * 
     * https://codex.wordpress.org/Function_Reference/esc_sql 
     * Note: Be careful to use this function correctly. It will only escape values to be used in strings in the query. 
     * That is, it only provides escaping for values that will be within quotes in the SQL (as in field = '{$escaped_value}'). 
     * If your value is not going to be within quotes, your code will still be vulnerable to SQL injection. 
     * For example, this is vulnerable, because the escaped value is not surrounded by quotes in the SQL query: 
     * ORDER BY {$escaped_value}. As such, this function does not escape unquoted numeric values, field names, or SQL keywords. 
     *         
     */
    function wpbc_clean_string_for_form( $value ){
        
        global $wpdb;
        
        $value_trimmed = trim( stripslashes( $value ) );

        //FixIn: 8.0.2.10		//Fix for update of WP 4.8.3
		if ( method_exists( $wpdb, 'remove_placeholder_escape' ) )
        	$esc_sql_value =  $wpdb->remove_placeholder_escape( esc_sql( $value_trimmed ) );
		else
			$esc_sql_value =  esc_sql(  $value_trimmed );

        //$value = trim( $wpdb->prepare( "'%s'",  $esc_sql_value ) , "'" );
               
        $esc_sql_value = trim( stripslashes( $esc_sql_value ) );
        
        return $esc_sql_value;
    
    }
    
    
    ////////////////////////////////////////////////////////////////////////////
    
    
    function wpbc_get_number_new_bookings(){

          global $wpdb;

         //if  ( wpbc_is_field_in_table_exists('booking','is_new') == 0 )  return 0;  // do not created this field, so return 0

          $trash_bookings = ' AND bk.trash != 1 ';                                //FixIn: 6.1.1.10  - check also  below usage of {$trash_bookings}           
          $sql_req = "SELECT bk.booking_id FROM {$wpdb->prefix}booking as bk WHERE  bk.is_new = 1 {$trash_bookings} " ;

          $sql_req = apply_bk_filter('get_sql_for_checking_new_bookings', $sql_req );
          $sql_req = apply_bk_filter('get_sql_for_checking_new_bookings_multiuser', $sql_req );

          $bookings = $wpdb->get_results( $sql_req );

          return count($bookings) ;
        
    }

	/**
	 * Update 'is_new' status of bookings in Database
	 *
	 * @param $id_of_new_bookings	inr or comma seperated ID of bookings. Example:  '1'  |   '3,5,7,9'
	 * @param $is_new				'0' | '1'
	 * @param $user_id				'user_id'
	 *
	 */
    function wpbc_update_number_new_bookings( $id_of_new_bookings, $is_new = '0' , $user_id = 1 ){
        global $wpdb;

        if (count($id_of_new_bookings) > 0 ) {

            //if  (wpbc_is_field_in_table_exists('booking','is_new') == 0)  return 0;  // do not created this field, so return 0
            
            $id_of_new_bookings = implode(',', $id_of_new_bookings);
            $id_of_new_bookings = wpbc_clean_like_string_for_db( $id_of_new_bookings );
            
            
//debuge($id_of_new_bookings);           
            if ($id_of_new_bookings == 'all') {
                $update_sql = "UPDATE {$wpdb->prefix}booking AS bk SET bk.is_new = {$is_new}  WHERE bk.is_new != {$is_new} ";    //FixIn: 8.2.1.18
//debuge($update_sql);                
                $update_sql = apply_bk_filter('update_sql_for_checking_new_bookings', $update_sql, 0 , $user_id );
            } else
                $update_sql = "UPDATE {$wpdb->prefix}booking AS bk SET bk.is_new = {$is_new} WHERE bk.booking_id IN  ( {$id_of_new_bookings} ) ";

            if ( false === $wpdb->query( $update_sql  ) ) {
                debuge_error('Error during updating status of bookings at DB',__FILE__,__LINE__);
                die();
            }
        }
    }


    // Add Admin Bar
    add_action( 'admin_bar_menu', 'wp_admin_bar_bookings_menu', 70 );

    function wp_admin_bar_bookings_menu(){
        global $wp_admin_bar;
        
        $current_user = wp_get_current_user();

        $curr_user_role = get_bk_option( 'booking_user_role_booking' );
        $level = 10;
        if ($curr_user_role == 'administrator')       $level = 10;
        else if ($curr_user_role == 'editor')         $level = 7;
        else if ($curr_user_role == 'author')         $level = 2;
        else if ($curr_user_role == 'contributor')    $level = 1;
        else if ($curr_user_role == 'subscriber')     $level = 0;

        $is_super_admin = apply_bk_filter('multiuser_is_user_can_be_here', false, 'only_super_admin');
        if (   ( ($current_user->user_level < $level) && (! $is_super_admin)  ) || !is_admin_bar_showing() ) return;


        $update_count = wpbc_get_number_new_bookings();

        $title = 'Booking Calendar';//__('Booking Calendar' ,'booking');	//FixIn: 9.1.3.3
        $update_title = ''// '<img src="'.WPBC_PLUGIN_URL .'/assets/img/icon-16x16.png" style="height: 16px;vertical-align: sub;" />&nbsp;' 
                        . $title;
        
        
        
        $is_user_activated = apply_bk_filter('multiuser_is_current_user_active',  true );           //FixIn: 6.0.1.17
        if ( ( $update_count > 0) && ( $is_user_activated ) ) {
            $update_count_title = "&nbsp;<span class='booking-count bk-update-count' style='background: #f0f0f1;color: #2c3338;display: inline;padding: 2px 5px;font-weight: 600;border-radius: 10px;'>"
								  . number_format_i18n($update_count)
								  . "</span>" ; //id='booking-count'
            $update_title .= $update_count_title;
        }

        $link_bookings = wpbc_get_bookings_url();
        $link_res      = wpbc_get_resources_url();
        $link_settings = wpbc_get_settings_url();
        

        $wp_admin_bar->add_menu(
                array(
                    'id' => 'bar_wpbc',
                    'title' => $update_title ,
                    'href' => wpbc_get_bookings_url()
                    )
                );

        $wp_admin_bar->add_menu(
                array(
                    'id' => 'bar_wpbc_calendar_overview',
                    'title' => __( 'Calendar Overview', 'booking' ),
                    'href' => wpbc_get_bookings_url() . '&view_mode=vm_calendar',
                    'parent' => 'bar_wpbc',
                )
        );
        $wp_admin_bar->add_menu(
                array(
                    'id' => 'bar_wpbc_booking_listing',
                    'title' => __( 'Booking Listing', 'booking' ),
                    'href' => wpbc_get_bookings_url() . '&view_mode=vm_listing',
                    'parent' => 'bar_wpbc',
                )
        );
        
        
        
         $curr_user_role_settings = get_bk_option( 'booking_user_role_settings' );
         $level = 10;
         if ($curr_user_role_settings == 'administrator')       $level = 10;
         else if ($curr_user_role_settings == 'editor')         $level = 7;
         else if ($curr_user_role_settings == 'author')         $level = 2;
         else if ($curr_user_role_settings == 'contributor')    $level = 1;
         else if ($curr_user_role_settings == 'subscriber')     $level = 0;

         if (   ( ($current_user->user_level < $level) && (! $is_super_admin)  ) || !is_admin_bar_showing() ) return;

 
        $wp_admin_bar->add_menu(
                array(
                    'id' => 'bar_wpbc_new',
                    'title' => __( 'Add booking', 'booking' ),
                    'href' => wpbc_get_new_booking_url(),
                    'parent' => 'bar_wpbc',
                )
        );
        
        if (  class_exists( 'wpdev_bk_personal' ) )
            $wp_admin_bar->add_menu(
                array(
                    'id' => 'bar_wpbc_resources',
                    'title' => __( 'Resources', 'booking' ),
                    'href' => $link_res,
                    'parent' => 'bar_wpbc',
                )
        );
                if ( class_exists( 'wpdev_bk_biz_m' ) )
                    $wp_admin_bar->add_menu(
                        array(
                            'id' => 'bar_wpbc_resources_cost',
                            'title' => __( 'Costs and Rates', 'booking' ),
                            'href' => $link_res . '&tab=cost',
                            'parent' => 'bar_wpbc_resources'
                        )
                    );
                if ( class_exists( 'wpdev_bk_biz_m' ) )
                    $wp_admin_bar->add_menu(
                        array(
                            'id' => 'bar_wpbc_resources_cost_advanced',
                            'title' => __( 'Advanced Cost', 'booking' ),
                            'href' => $link_res . '&tab=cost_advanced',
                            'parent' => 'bar_wpbc_resources'
                        )
                    );
                if ( class_exists( 'wpdev_bk_biz_l' ) )
                    $wp_admin_bar->add_menu(
                        array(
                            'id' => 'bar_wpbc_resources_coupons',
                            'title' => __( 'Coupons', 'booking' ),
                            'href' => $link_res . '&tab=coupons',
                            'parent' => 'bar_wpbc_resources'
                        )
                    );
                if ( class_exists( 'wpdev_bk_biz_m' ) )
                    $wp_admin_bar->add_menu(
                        array(
                            'id' => 'bar_wpbc_resources_availability',
                            'title' => __( 'Availability', 'booking' ),
                            'href' => $link_res . '&tab=availability',
                            'parent' => 'bar_wpbc_resources'
                        )
                    );
                if ( class_exists( 'wpdev_bk_biz_m' ) )
                    $wp_admin_bar->add_menu(
                        array(
                            'id' => 'bar_wpbc_resources_filter',
                            'title' => __( 'Season Filters', 'booking' ),
                            'href' => $link_res . '&tab=filter',
                            'parent' => 'bar_wpbc_resources'
                        )
                    );
        
        
        $wp_admin_bar->add_menu(
                array(
                    'id' => 'bar_wpbc_settings',
                    'title' => __( 'Settings', 'booking' ),
                    'href' => wpbc_get_settings_url(),
                    'parent' => 'bar_wpbc',
                )
        );
        
                $wp_admin_bar->add_menu(
                        array(
                            'id' => 'bar_wpbc_settings_form',
                            'title' => __( 'Form', 'booking' ),
                            'href' => $link_settings . '&tab=form',
                            'parent' => 'bar_wpbc_settings'
                        )
                );
                $wp_admin_bar->add_menu(
                        array(
                            'id' => 'bar_wpbc_settings_email',
                            'title' => __( 'Emails', 'booking' ),
                            'href' => $link_settings . '&tab=email',
                            'parent' => 'bar_wpbc_settings'
                        )
                );
                if ( class_exists( 'wpdev_bk_biz_s' ) )
                    $wp_admin_bar->add_menu(
                        array(
                            'id' => 'bar_wpbc_settings_payment',
                            'title' => __( 'Payment', 'booking' ),
                            'href' => $link_settings . '&tab=payment',
                            'parent' => 'bar_wpbc_settings'
                        )
                    );
                $wp_admin_bar->add_menu(
                        array(
                            'id' => 'bar_wpbc_settings_sync',
                            'title' => __( 'Sync', 'booking' ),															//FixIn: 8.0
                            'href' => $link_settings . '&tab=sync',
                            'parent' => 'bar_wpbc_settings'
                        )
                );    
                if ($is_super_admin)
                    if ( class_exists( 'wpdev_bk_biz_l' ) )
                        $wp_admin_bar->add_menu(
                            array(
                                'id' => 'bar_wpbc_settings_search',
                                'title' => __( 'Search', 'booking' ),
                                'href' => $link_settings . '&tab=search',
                                'parent' => 'bar_wpbc_settings'
                            )
                        );
    }


    define ('OBC_CHECK_URL', 'https://wpbookingcalendar.com/');

    function wpdev_ajax_check_bk_news( $sub_url = '' ){

        $v=array();
        if (class_exists('wpdev_bk_personal'))          $v[] = 'wpdev_bk_personal';
        if (class_exists('wpdev_bk_biz_s'))             $v[] = 'wpdev_bk_biz_s';
        if (class_exists('wpdev_bk_biz_m'))             $v[] = 'wpdev_bk_biz_m';
        if (class_exists('wpdev_bk_biz_l'))             $v[] = 'wpdev_bk_biz_l';
        if (class_exists('wpdev_bk_multiuser'))         $v[] = 'wpdev_bk_multiuser';

        $obc_settings = array();
        $ver = get_bk_option('bk_version_data');
        if ( $ver !== false ) { $obc_settings = array( 'subscription_key'=>wp_json_encode($ver) ); }
        
        $params = array(
                    'action' => 'get_news',
                    'subscription_email' => isset($obc_settings['subscription_email'])?$obc_settings['subscription_email']:false,
                    'subscription_key'   => isset($obc_settings['subscription_key'])?$obc_settings['subscription_key']:false,
                    'bk' => array('bk_ver'=>WPDEV_BK_VERSION, 'bk_url'=>WPBC_PLUGIN_URL,'bk_dir'=>WPBC_PLUGIN_DIR, 'bk_clss'=>$v),
                    'siteurl'            => get_option('siteurl'),
                    'siteip'            => $_SERVER['SERVER_ADDR'],
                    'admin_email'        => get_option('admin_email')
        );

        $request = new WP_Http();
        if (empty($sub_url)) $sub_url = 'info/';
        $result  = $request->request( OBC_CHECK_URL . $sub_url, array(
            'method' => 'POST',
            'timeout' => 15,
            'body' => $params
            ));

        if (!is_wp_error($result) && ($result['response']['code']=='200') && (true) ) {

           $string = ($result['body']);                                         //$string = str_replace( "'", '&#039;', $string );
           echo $string;
           echo ' <script type="text/javascript"> ';  
           echo '    jQuery("#ajax_bk_respond").after( jQuery("#ajax_bk_respond #bk_news_loaded") );';
           echo '    jQuery("#bk_news_loaded").slideUp(1).slideDown(1500);';
           echo ' </script> ';

        } else  /**/
            { // Some error appear
            echo '<div id="bk_errror_loading">';
            if (is_wp_error($result))  echo $result->get_error_message();
            else                       echo $result['response']['message'];
            echo '</div>';
            echo ' <script type="text/javascript"> ';
            echo '    document.getElementById("bk_news").style.display="none";';
            echo '    jQuery("#ajax_bk_respond").after( jQuery("#ajax_bk_respond #bk_errror_loading") );';
            echo '    jQuery("#bk_errror_loading").slideUp(1).slideDown(1500);';
            echo '    jQuery("#bk_news_section").animate({opacity:1},3000).slideUp(1500);';
            echo ' </script> ';
        }

    }


    function wpdev_ajax_check_bk_version(){
        $v=array();
        if (class_exists('wpdev_bk_personal'))            $v[] = 'wpdev_bk_personal';
        if (class_exists('wpdev_bk_biz_s'))        $v[] = 'wpdev_bk_biz_s';
        if (class_exists('wpdev_bk_biz_m'))   $v[] = 'wpdev_bk_biz_m';
        if (class_exists('wpdev_bk_biz_l'))          $v[] = 'wpdev_bk_biz_l';
        if (class_exists('wpdev_bk_multiuser'))      $v[] = 'wpdev_bk_multiuser';

        $obc_settings = array();
        $params = array(
                    'action' => 'set_register',
                    'order_number'   => isset($_POST['order_num'])?$_POST['order_num']:false,
                    'bk' => array('bk_ver'=>WPDEV_BK_VERSION, 'bk_url'=>WPBC_PLUGIN_URL,'bk_dir'=>WPBC_PLUGIN_DIR, 'bk_clss'=>$v),
                    'siteurl'            => get_option('siteurl'),
                    'siteip'            => $_SERVER['SERVER_ADDR'],
                    'admin_email'        => get_option('admin_email')
        );

        update_bk_option( 'bk_version_data' ,  serialize($params) );

        $request = new WP_Http();
        $result  = $request->request( OBC_CHECK_URL . 'register/', array(
            'method' => 'POST',
            'timeout' => 15,
            'body' => $params
            ));

        if ( ! is_wp_error($result) 
            && ( $result['response']['code']=='200' ) 
            && ( true ) ) {

           $string = ($result['body']);                                         //$string = str_replace( "'", '&#039;', $string );
           echo $string ;
           echo ' <script type="text/javascript"> ';  
           echo '    jQuery("#ajax_message").append( jQuery("#ajax_respond #bk_registration_info") );';           
           echo '    jQuery("#ajax_message").append( "<div id=\'bk_registration_info_reload\'>If page will not reload automatically,  please refresh page after 60 seconds...</div>" );';           
           echo ' </script> ';
           
        } else  /**/
            { // Some error appear
            echo '<div id="bk_errror_loading" class="warning_message" >';
            echo '<div class="info_message">'; _e('Warning! Some error occur, during sending registration request.' ,'booking'); echo '</div>';
            
            if (is_wp_error($result))  echo $result->get_error_message();
            else                       echo $result['response']['message'];
            echo '<br /><br />';
            _e('Please refresh this page and if the same error appear again contact support by email (with  info about order number and website) for finishing the registrations' ,'booking'); echo ' <a href="mailto:activate@wpbookingcalendar.com">activate@wpbookingcalendar.com</a>';
            echo '</strong></div>';
            echo ' <script type="text/javascript"> ';
            echo '    jQuery( "#ajax_message" ).html( "" );';
            
            echo '    jQuery("#ajax_message").append( jQuery("#ajax_respond #bk_errror_loading") );';
            echo '    jQuery("#bk_errror_loading").slideUp(1).slideDown(1500);';
            
            echo '    jQuery("#recheck_version").animate({opacity:1},3000).slideUp(1500);';
            echo ' </script> ';
        }


    }

    

    function wpbc_show_booking_footer(){
        $wpdev_copyright_adminpanel  = get_bk_option( 'booking_wpdev_copyright_adminpanel' );             // check
        if ( ( $wpdev_copyright_adminpanel !== 'Off' ) && ( ! wpbc_is_this_demo() ) ) {
            $message = '';
//            $message .= '<a target="_blank" href="https://wpbookingcalendar.com/">Booking Calendar</a> ' . __('version' ,'booking') . ' ' . WP_BK_VERSION_NUM ;
//
//            $message .= ' | '. sprintf(__('Add your %s on %swordpress.org%s, if you enjoyed by this plugin.' ,'booking'), 
//                            '<a target="_blank" href="http://goo.gl/tcrrpK" >&#9733;&#9733;&#9733;&#9733;&#9733;</a>',
//                            '<a target="_blank" href="http://goo.gl/tcrrpK" >',
//                            '</a>'   );
            
            $message .= sprintf( __( 'If you like %s please leave us a %s rating. A huge thank you in advance!', 'booking' )
                                , '<strong>Booking Calendar</strong>' . ' ' . WP_BK_VERSION_NUM  . '' // '<strong>Booking Calendar</strong>'
                                , '<a href="https://wordpress.org/support/plugin/booking/reviews/#new-post" target="_blank" title="' . esc_attr__( 'Thanks :)', 'booking' ) . '">'
                                    . '&#9733;&#9733;&#9733;&#9733;&#9733;' 
                                    . '</a>' 
                            );            
            
            echo '<div id="wpbc-footer" style="position:absolute;bottom:40px;text-align:left;width:95%;font-size:0.9em;text-shadow:0 1px 0 #fff;margin:0;color:#888;">' . $message . '</div>';
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function(){
                    jQuery('#wpfooter').append( jQuery('#wpbc-footer') );
                });
            </script>
            <?php
        }
    }
    
    
    
    
////////////////////////////////////////////////////////////////////////////////
//  Support functions
////////////////////////////////////////////////////////////////////////////////

function get_wpbc_current_user_id() {
    $user = wp_get_current_user();
    return ( isset( $user->ID ) ? (int) $user->ID : 0 );
}


/**
	 * Check  if Current User have specific Role
 * 
 * @return bool Whether the current user has the given capability. 
 */
function wpbc_is_current_user_have_this_role( $user_role ) {
    
   if ( $user_role == 'administrator' )  $user_role = 'activate_plugins';
   if ( $user_role == 'editor' )         $user_role = 'publish_pages';
   if ( $user_role == 'author' )         $user_role = 'publish_posts';
   if ( $user_role == 'contributor' )    $user_role = 'edit_posts';
   if ( $user_role == 'subscriber')      $user_role = 'read';
   
   return current_user_can( $user_role );
}


function wpbc_get_user_ip() {
//return '84.243.195.114'  ;                    // Test     //90.36.89.174
    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $userIP = $_SERVER['HTTP_CLIENT_IP'] ;
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $userIP = $_SERVER['HTTP_X_FORWARDED_FOR'] ;
    } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $userIP = $_SERVER['HTTP_X_FORWARDED'] ;
    } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $userIP = $_SERVER['HTTP_FORWARDED_FOR'] ; 
    } elseif (isset($_SERVER['HTTP_FORWARDED'])) {
            $userIP = $_SERVER['HTTP_FORWARDED'] ;
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $userIP = $_SERVER['REMOTE_ADDR'] ;
    } else {
            $userIP = "" ;
    }
    return $userIP ;
}
add_bk_filter('wpbc_get_user_ip', 'wpbc_get_user_ip');


/**
	 * Transform the REQESTS parameters (GET and POST) into URL
 * 
 * @param type $page_param
 * @param array $exclude_params
 * @param type $only_these_parameters
 * @return type
 */
function wpbc_get_params_in_url( $page_param , $exclude_params = array(), $only_these_parameters = false, $is_escape_url = true, $only_get = false ){			//FixIn: 8.0.1.101     //Fix: $is_escape_url = false

    $exclude_params[] = 'page';
    $exclude_params[] = 'post_type';
    
    if ( isset( $_GET['page'] ) ) 
        $page_param = $_GET['page'];
    
    $get_paramaters = array( 'page' => $page_param );
    
    if ( $only_get )
        $check_params = $_GET;
    else 
        $check_params = $_REQUEST;
//debuge($check_params);    
    foreach ( $check_params as $prm_key => $prm_value ) {
        
        // Skip  parameters arrays,  like $_GET['rvaluation_to'] = Array ( [0] => 6,  [1] => 14,  [2] => 14 )
        if ( 
               (  is_string( $prm_value ) )  
            || ( is_numeric( $prm_value ) ) 
            ) {    
            
            if ( strlen( $prm_value ) > 1000 ) {                                    // Check  about TOOO long parameters,  if it exist  then  reset it.
                $prm_value = '';
            }

            if ( ! in_array( $prm_key, $exclude_params ) )
                if ( ( $only_these_parameters === false ) || ( in_array( $prm_key, $only_these_parameters ) ) )
                        $get_paramaters[ $prm_key ] = $prm_value;
        }
    }
//debuge($exclude_params);    
    $url = admin_url( add_query_arg(  $get_paramaters , 'admin.php' ) );

    if ( $is_escape_url )
    	$url = esc_url_raw( $url );							//FixIn: 8.1.1.7
        // $url = esc_url( $url );
    
    return $url;
    
    /*      // Old variant:
            if ( isset( $_GET['page'] ) ) $page_param = $_GET['page'];

            $url_start = 'admin.php?page=' . $page_param . '&';    
            $exclude_params[] = 'page';
            foreach ( $_REQUEST as $prm_key => $prm_value ) {

                if ( !in_array( $prm_key, $exclude_params ) )
                    if ( ($only_these_parameters === false) || ( in_array( $prm_key, $only_these_parameters ) ) )

                        $url_start .= $prm_key . '=' . $prm_value . '&';

            }
            $url_start = substr( $url_start, 0, -1 );

            return $url_start;
     */     
}




////////////////////////////////////////////////////////////////////////////////    
// Mesages for Admin panel 
////////////////////////////////////////////////////////////////////////////////    


// Show Ajax message at the top of page //////////////////////////////////////////////////////////////////////////////////////////////////////

function wpbc_show_ajax_message( $message, $time_to_show = 3000, $is_error = false ) {

    // Recheck  for any "lang" shortcodes for replacing to correct language
    $message =  apply_bk_filter('wpdev_check_for_active_language', $message );

    // Escape any JavaScript from  message
    $notice =   html_entity_decode( esc_js( $message ) ,ENT_QUOTES) ;
    
    ?><script type="text/javascript">
        var my_message = '<?php echo $notice; ?>';
        wpbc_admin_show_message( my_message, '<?php echo ( $is_error ? 'error' : 'success' ); ?>', <?php echo $time_to_show; ?> );                                                                      
    </script><?php
}


/**
	 * Show "Saved Changes" message at  the top  of settings page.
 * 
 */    
function wpbc_show_changes_saved_message() {
    wpbc_show_message ( __('Changes saved.','booking'), 5 );
}    


/**
	 * Show Message at  Top  of Admin Pages
 * 
 * @param type $message         - mesage to  show
 * @param type $time_to_show    - number of seconds to  show, if 0 or skiped,  then unlimited time.
 * @param type $message_type    - Default: updated   { updated | error | notice }
 */
function wpbc_show_message ( $message, $time_to_show , $message_type = 'updated') {
        
    // Generate unique HTML ID  for the message
    $inner_message_id =  intval( time() * rand(10, 100) );

    // Get formated HTML message
    $notice = wpbc_get_formated_message( $message, $message_type, $inner_message_id );

    // Get the time of message showing
    $time_to_show = intval( $time_to_show ) * 1000;

    // Show this Message
    ?> <script type="text/javascript">                              
        if ( jQuery('.wpbc_admin_message').length ) {
                jQuery('.wpbc_admin_message').append( '<?php echo $notice; ?>' );
            <?php if ( $time_to_show > 0 ) { ?>
                jQuery('#wpbc_inner_message_<?php echo $inner_message_id; ?>').animate({opacity: 1},<?php echo $time_to_show; ?>).fadeOut( 2000 );
            <?php } ?>
        }
    </script> <?php
}


/**
	 * Escape and prepare message to  show it
 * 
 * @param type $message                 - message
 * @param type $message_type            - Default: updated   { updated | error | notice }
 * @param string $inner_message_id      - ID of message DIV,  can  be skipped
 * @return string
 */
function wpbc_get_formated_message ( $message, $message_type = 'updated', $inner_message_id = '') {
        

    // Recheck  for any "lang" shortcodes for replacing to correct language
    $message =  apply_bk_filter('wpdev_check_for_active_language', $message );

    // Escape any JavaScript from  message
    $notice =   html_entity_decode( esc_js( $message ) ,ENT_QUOTES) ;

    $notice .= '<a class="close tooltip_left" rel="tooltip" title="'. esc_js(__("Hide",'booking')). '" data-dismiss="alert" href="javascript:void(0)" onclick="javascript:jQuery(this).parent().hide();">&times;</a>';

    if (! empty( $inner_message_id ))
        $inner_message_id = 'id="wpbc_inner_message_'. $inner_message_id .'"';

    $notice = '<div '.$inner_message_id.' class="wpbc_inner_message '. $message_type . '">' . $notice . '</div>';

    return  $notice;
}


/**
	 * Show system info  in settings page
 * 
 * @param string $message                     ...  
 * @param string $message_type                'info' | 'warning' | 'error'
 * @param string $title                       __('Important!' ,'booking')  |  __('Note' ,'booking')
 * 
 * Exmaple:     wpbc_show_message_in_settings( __( 'Nothing Found', 'booking' ), 'warning', __('Important!' ,'booking') );
 */
function wpbc_show_message_in_settings( $message, $message_type = 'info', $title = '' , $is_echo = true ) {
    
    $message_content = '';
    
    $message_content .= '<div class="clear"></div>';
    
    $message_content .= '<div class="wpbc-settings-notice notice-' . $message_type . '" style="text-align:left;">';
    
    if ( ! empty( $title ) )
        $message_content .=  '<strong>' . esc_js( $title ) . '</strong> ';
        
    $message_content .= html_entity_decode( esc_js( $message ) ,ENT_QUOTES) ;
            
    $message_content .= '</div>';
    
    $message_content .= '<div class="clear"></div>';
    
    if ( $is_echo )
        echo $message_content;
    else
        return $message_content;
        
}

////////////////////////////////////////////////////////////////////////////////    
// Settings Meta Boxes
////////////////////////////////////////////////////////////////////////////////    
function wpbc_open_meta_box_section( $metabox_id, $title ) {
    
    $my_close_open_win_id = $metabox_id . '_metabox';
    ?>
    <div class='meta-box'>
        <div 
                id="<?php echo $my_close_open_win_id; ?>" 
                class="postbox <?php if ( '1' == get_user_option( 'booking_win_' . $my_close_open_win_id ) ) echo 'closed'; ?>" 
            ><div class="postbox-header" style="display: flex;flex-flow: row nowrap;border-bottom: 1px solid #ccd0d4;"><?php //FixIn: 8.7.8.1 ?>
				<h3 class='hndle' style="flex: 1 1 auto;border: none;">
                  <span><?php  echo wp_kses_post( $title ); ?></span>
			  	</h3>
				<div  title="<?php _e('Click to toggle','booking'); ?>"
                    class="handlediv"
                    onclick="javascript:wpbc_verify_window_opening(<?php echo get_wpbc_current_user_id(); ?>, '<?php echo $my_close_open_win_id; ?>');"
                ><br/></div>
			</div>
            <div class="inside">
    <?php        
}


function wpbc_close_meta_box_section() {
    ?>
              </div> 
        </div> 
    </div>                        
    <?php
}


////////////////////////////////////////////////////////////////////////////////
//  P a g i n a t i o n    o f    T a b l e    L  i s t i n g    ///////////////
////////////////////////////////////////////////////////////////////////////////
/**
	 * Show    P a g i n a t i o n
 * 
 * @param int $summ_number_of_items     - total  number of items
 * @param int $active_page_num          - number of activated page
 * @param int $num_items_per_page       - number of items per page
 * @param array $only_these_parameters  - array of keys to exclude from links
 * @param string $url_sufix             - usefule for anchor to  HTML section  with  specific ID,  Example: '#my_section'
 */
function wpbc_show_pagination( $summ_number_of_items, $active_page_num, $num_items_per_page , $only_these_parameters = false, $url_sufix = '' ) {
        
    if ( empty( $num_items_per_page ) ) {
        $num_items_per_page = '10';
    }

    $pages_number = ceil( $summ_number_of_items / $num_items_per_page );
    if ( $pages_number < 2 )
        return;

            //Fix: 5.1.4 - Just in case we are having tooo much  resources, then we need to show all resources - and its empty string
            if ( ( isset($_REQUEST['wh_booking_type'] ) ) && ( strlen($_REQUEST['wh_booking_type']) > 1000 ) ) {                   
                $_REQUEST['wh_booking_type'] = '';            
            }  
        
    // First  parameter  will overwriten by $_GET['page'] parameter
    $bk_admin_url = wpbc_get_params_in_url( wpbc_get_bookings_url( false, false ), array('page_num'), $only_these_parameters );

    
    ?>
    <span class="wpdevelop wpbc-pagination">
        <div class="container-fluid">  
            <div class="row">
                <div class="col-sm-12 text-center control-group0">
                    <nav class="btn-toolbar">
                      <div class="btn-group wpbc-no-margin" style="float:none;">

                        <?php if ( $pages_number > 1 ) { ?>
                                <a class="button button-secondary <?php echo ( $active_page_num == 1 ) ? ' disabled' : ''; ?>" 
                                   href="<?php echo $bk_admin_url; ?>&page_num=<?php if ($active_page_num == 1) { echo $active_page_num; } else { echo ($active_page_num-1); } echo $url_sufix; ?>">
                                    <?php _e('Prev', 'booking'); ?>
                                </a>
                        <?php } 

                        /** Number visible pages (links) that linked to active page, other pages skipped by "..." */
                        $num_closed_steps = 3;
                        
                        for ( $pg_num = 1; $pg_num <= $pages_number; $pg_num++ ) {
                             
                                if ( ! ( 
                                           ( $pages_number > ( $num_closed_steps * 4) ) 
                                        && ( $pg_num > $num_closed_steps ) 
                                        && ( ( $pages_number - $pg_num + 1 ) > $num_closed_steps ) 
                                        && (  abs( $active_page_num - $pg_num ) > $num_closed_steps )  
                                   ) ) {
                                    ?> <a class="button button-secondary <?php if ($pg_num == $active_page_num ) echo ' active'; ?>" 
                                         href="<?php echo $bk_admin_url; ?>&page_num=<?php echo $pg_num;  echo $url_sufix; ?>">
                                        <?php echo $pg_num; ?>
                                      </a><?php 
                                      
                                    if ( ( $pages_number > ( $num_closed_steps * 4) ) 
                                            && ( ($pg_num+1) > $num_closed_steps ) 
                                            && ( ( $pages_number - ( $pg_num + 1 ) ) > $num_closed_steps ) 
                                            &&  ( abs($active_page_num - ( $pg_num + 1 ) ) > $num_closed_steps )  
                                        ) {
                                        echo ' <a class="button button-secondary disabled" href="javascript:void(0);">...</a> ';
                                    }
                                }
                        }

                        if ( $pages_number > 1 ) { ?>
                                <a class="button button-secondary <?php echo ( $active_page_num == $pages_number ) ? ' disabled' : ''; ?>" 
                                   href="<?php echo $bk_admin_url; ?>&page_num=<?php  if ($active_page_num == $pages_number) { echo $active_page_num; } else { echo ($active_page_num+1); }  echo $url_sufix; ?>">
                                    <?php _e('Next', 'booking'); ?>
                                </a>
                        <?php } ?>

                      </div>
                    </nav>
                </div>
            </div>
        </div>
    </span>
    <?php
}



////////////////////////////////////////////////////////////////////////////////
// Inline JavaScript to Footer page
////////////////////////////////////////////////////////////////////////////////
/**
 * Queue  JavaScript for later output at  footer
 *
 * @param string $code
 */
function wpbc_enqueue_js( $code ) {
    global $wpbc_queued_js;

    if ( empty( $wpbc_queued_js ) ) {
        $wpbc_queued_js = '';
    }

    $wpbc_queued_js .= "\n" . $code . "\n";
}


/**
 * Output any queued javascript code in the footer.
 */
function wpbc_print_js() {
    
    global $wpbc_queued_js;

    if ( ! empty( $wpbc_queued_js ) ) {

        $wpbc_queued_js = wp_check_invalid_utf8( $wpbc_queued_js );
        
        $wpbc_queued_js = wp_specialchars_decode( $wpbc_queued_js , ENT_COMPAT);            // Converts double quotes  '&quot;' => '"'
        
        $wpbc_queued_js = preg_replace( '/&#(x)?0*(?(1)27|39);?/i', "'", $wpbc_queued_js );
        $wpbc_queued_js = str_replace( "\r", '', $wpbc_queued_js );

        echo "<!-- WPBC JavaScript -->\n<script type=\"text/javascript\">\njQuery(function($) {" . $wpbc_queued_js . "});\n</script>\n<!-- End WPBC JavaScript -->\n";

        $wpbc_queued_js = '';
        unset( $wpbc_queued_js );
    }
}


/**
 * Reload page by using JavaScript
 * 
 * @param string $url - URL of page to  load
 */
function wpbc_reload_page_by_js( $url ) {

    $redir = html_entity_decode( esc_url( $url ) );
    
    if ( ! empty( $redir ) ) {
        ?>
        <script type="text/javascript">                
            window.location.href = '<?php echo $redir ?>';                
        </script>
        <?php
    }
}


/**
	 * Redirect browser to a specific page
 * 
 * @param string $url - URL of page to redirect
 */
function wpbc_redirect( $url ) {
    
    $url = wpbc_make_link_absolute( $url );
    
    $url = html_entity_decode( esc_url( $url ) );
    
    echo '<script type="text/javascript">';
    echo 'window.location.href="'.$url.'";';
    echo '</script>';
    echo '<noscript>';
    echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
    echo '</noscript>';
}


/** Check  if user defined to  not show up_news section.
 *
 */
function wpbc_is_show_up_news(){                                                                                        //FixIn: 8.1.3.9

	$wpdev_copyright_adminpanel  = get_bk_option( 'booking_wpdev_copyright_adminpanel' );             // check
	if ( 	( $wpdev_copyright_adminpanel === 'Off' )
		 && ( ! wpbc_is_this_demo() )
		 && ( class_exists('wpdev_bk_personal') )
	) {
		return false;
	} else {
		return true;
	}
}


/**
	 * Show Welcome Panel with  links
 * 
 * @global type $wpbc_Dismiss
 */
function wpbc_welcome_panel() {

    ?>
    <style type="text/css" media="screen">
        /*<![CDATA[*/
        /* WPBC Welcome Panel */
		/* //FixIn: 8.9.3.6 */
		.wpbc-panel .welcome-panel .welcome-panel-column-container {
			display: block;
			margin-top: 0px;
			padding: 0px;
			background: #fff;
		}
		.wpbc-panel .welcome-panel .welcome-panel-column ul {
			margin: 1.8em 1em 1em 0;
		}
		.wpbc-panel .welcome-panel {
			background-blend-mode: overlay;
			font-size: 14px;
			line-height: 1.3;
		}
		.wpbc-panel .welcome-panel-column {
			display: block;
		}
		.wpbc-panel .welcome-panel::before {
			content: none;
		}
		.wpbc-panel .welcome-panel-content {
			min-height: 100px;
		}
		/* End //FixIn: 8.9.3.6 */
        .wpbc-panel .welcome-panel {
            background: linear-gradient(to top, #F5F5F5, #FAFAFA) repeat scroll 0 0 #F5F5F5;
            border-color: #DFDFDF;
            position: relative;
            overflow: auto;
            margin: 5px 0 20px;
            padding: 23px 10px 12px;
            border-width: 1px;
            border-style: solid;
            border-radius: 3px;
            font-size: 13px;
            line-height: 2.1em;
        }
        .wpbc-panel .welcome-panel h3 {
            margin: 0;
            font-size: 21px;
            font-weight: 400;
            line-height: 1.2;
        }
        .wpbc-panel .welcome-panel h4 {
            margin: 1.33em 0 0;
            font-size: 13px;
            font-weight: 600;
        }
        .wpbc-panel .welcome-panel a{
            color:#21759B;
        }
        .wpbc-panel .welcome-panel .about-description {
            font-size: 16px;
            margin: 10px 0 5px;
			color: #72777c;
        }
        .wpbc-panel .welcome-panel .welcome-panel-close {
            position: absolute;
            top: 5px;
            right: 10px;
            padding: 8px 3px;
            font-size: 13px;
            text-decoration: none;
            line-height: 1;
        }
        .wpbc-panel .welcome-panel .welcome-panel-close:before {
            content: ' ';
            position: absolute;
            left: -12px;
            width: 10px;
            height: 100%;
            /*background: url('../wp-admin/images/xit.gif') 0 7% no-repeat;*/
        }
        .wpbc-panel .welcome-panel .welcome-panel-close:hover:before {
            background-position: 100% 7%;
        }
        .wpbc-panel .welcome-panel .button.button-hero {
            margin: 15px 0 3px;
        }
        .wpbc-panel .welcome-panel-content {
            margin-left: 13px;
            max-width: 1500px;
        }
        .wpbc-panel .welcome-panel .welcome-panel-column-container {
            clear: both;
            overflow: hidden;
            position: relative;
        }
        .wpbc-panel .welcome-panel .welcome-panel-column {
            width: 32%;
            min-width: 200px;
            float: left;
        }
        .ie8 .wpbc-panel .welcome-panel .welcome-panel-column {
            min-width: 230px;
        }
        .wpbc-panel .welcome-panel .welcome-panel-column:first-child {
            width: 36%;
        }
        .wpbc-panel .welcome-panel-column p {
            margin-top: 7px;
        }
        .wpbc-panel .welcome-panel .welcome-icon {
            background: none;    
            display: block;
            padding: 4px 0 0 2px;
        }
        .wpbc-panel .welcome-panel .welcome-add-page {
            background-position: 0 2px;
        }
        .wpbc-panel .welcome-panel .welcome-edit-page {
            background-position: 0 -90px;
        }
        .wpbc-panel .welcome-panel .welcome-learn-more {
            background-position: 0 -136px;
        }
        .wpbc-panel .welcome-panel .welcome-comments {
            background-position: 0 -182px;
        }
        .wpbc-panel .welcome-panel .welcome-view-site {
            background-position: 0 -274px;
        }
        .wpbc-panel .welcome-panel .welcome-widgets-menus {
            background-position: 1px -229px;
            line-height: 14px;
        }
        .wpbc-panel .welcome-panel .welcome-write-blog {
            background-position: 0 -44px;
        }
        .wpbc-panel .welcome-panel .welcome-panel-column ul {
            margin: 0.8em 1em 1em 0;
        }
        .wpbc-panel .welcome-panel .welcome-panel-column li {
            line-height: 1.8em;
            list-style-type: none;
        }
        @media screen and (max-width: 870px) {
            .wpbc-panel .welcome-panel .welcome-panel-column,
            .wpbc-panel .welcome-panel .welcome-panel-column:first-child {
                display: block;
                float: none;
                width: 100%;
            }
            .wpbc-panel .welcome-panel .welcome-panel-column li {
                display: inline-block;
                margin-right: 13px;
            }
            .wpbc-panel .welcome-panel .welcome-panel-column ul {
                margin: 0.4em 0 0;
            }
            .wpbc-panel .welcome-panel .welcome-icon {
                padding-left: 25px;
            }
        }
        /*]]>*/
    </style>                
    <div id="wpbc-panel-get-started" class="wpbc-panel" style="display:none;"> <div class="welcome-panel"><?php

        if ( ( class_exists( 'WPBC_Dismiss' )) && ( ! wpbc_is_this_demo() ) ) {
            
            global $wpbc_Dismiss;
            
            $is_panel_visible = $wpbc_Dismiss->render( 
                    array(
                            'id' => 'wpbc-panel-get-started',
                            'title' => sprintf( __( 'Dismiss' ,'booking') )
                          ) 
                                                     );
        } else $is_panel_visible = false;

        if ( $is_panel_visible )
            wpbc_welcome_panel_content();
        
    ?>  </div> </div> <?php
}

//FixIn: 8.1.3.10
/** Show dismiss close button  for specific HTML section
 *
 * @param  string $element_html_id   - ID of HTML selection  to  dismiss
 * @param  array  $params			 - array( 'title' => 'Dismiss', 'is_apply_in_demo' => false ) )
 *
 * @return bool
 */
function wpbc_is_dismissed( $element_html_id, $params = array() ){

    $defaults = array(
						  'title' => '&times;'
						, 'hint' => __( 'Dismiss' ,'booking')
						, 'is_apply_in_demo' => ! wpbc_is_this_demo()
						, 'class' => ''									// CSS class of  close X element
						, 'css' => ''									// Style class of  close X element
				);
    $params = wp_parse_args( $params, $defaults );


    $params['css'] = 'text-decoration: none;font-weight: 600;float:right;' . $params['css'];							// Append CSS instead of replace it

	if ( ( class_exists( 'WPBC_Dismiss' )) && ( $params[ 'is_apply_in_demo' ] ) ) {

		global $wpbc_Dismiss;

		$is_panel_visible = $wpbc_Dismiss->render(
													array(
															  'id' => $element_html_id
															, 'title' => $params['title']
															, 'hint' => $params['hint']
															, 'class' => $params['class']
															, 'css' => $params['css']
													)
											 );
	} else {
		$is_panel_visible = false;
	}

	return $is_panel_visible;
}


/**
	 * Content of Welcome Panel with  links
 * 
 */
function wpbc_welcome_panel_content() {

    ?>
    <div class="welcome-panel-content">
        <p class="about-description"><?php _e( 'We&#8217;ve assembled some links to get you started:','booking'); ?></p>
        <div class="welcome-panel-column-container">
            <div class="welcome-panel-column">
                <h4><?php _e( 'Get Started','booking'); ?></h4>
                <ul>
                    <li><div class="welcome-icon"><?php
                            printf( __( 'Insert booking form %sshortcode%s into your %sPost%s or %sPage%s','booking'), '<strong>',
                                '</strong>',
                                '<a href="' . admin_url( 'edit.php' ) . '">',
                                '</a>',
                                '<a href="' . admin_url( 'edit.php?post_type=page' ) . '">',
                                '</a>' );
                        ?></div></li>                            

                    <li><div class="welcome-icon"><?php 
                            printf( __( 'or add booking calendar %sWidget%s to your sidebar.','booking'),
                                '<a href="' . admin_url( 'widgets.php' ) . '">',
                                '</a>' );
                        ?></div></li>                            

                    <li><div class="welcome-icon"><?php
                            printf( __( 'Check %show todo%s that and what %sshortcodes%s are available.','booking'),
                                '<a href="https://wpbookingcalendar.com/faq/inserting-booking-form/" target="_blank">',
                                '</a>',
                                '<a href="https://wpbookingcalendar.com/faq/booking-calendar-shortcodes/" target="_blank">',
                                '</a>' );
                    ?></div></li>
                    <li><div class="welcome-icon"><?php 
                            printf( __( 'Add new booking from your post/page or from %sAdmin Panel%s.','booking'),
                                '<a href="' . esc_url( wpbc_get_new_booking_url() ) . '">', '</a>' );
                    ?></div></li>                    
                </ul>
            </div>
            <div class="welcome-panel-column">
                <h4><?php _e( 'Next Steps','booking'); ?></h4>
                <ul>
                    <li><div class="welcome-icon"><?php
                        printf( __( 'Check %sBooking Listing%s page for new bookings.','booking'),
                            '<a href="' . esc_url( wpbc_get_bookings_url(true, false) . '&view_mode=vm_listing' ) . '">',
                            '</a>' );
                    ?></div></li>                                                    
                    <li><div class="welcome-icon"><?php
                        printf( __( 'Configure booking %sSettings%s.' ,'booking'),
                            '<a href="' . esc_url( wpbc_get_settings_url(true, false) ) . '">', '</a>' );
                    ?></div></li>                            
                    <li><div class="welcome-icon"><?php
                        printf( __( 'Configure predefined set of your %sForm Fields%s.','booking'),
                            '<a href="' . esc_url( wpbc_get_settings_url(true, false) . '&tab=form' ) . '">', '</a>' );
                    ?></div></li>
                    <li><div class="welcome-icon"><?php
                        printf( __( 'Configure your predefined %sEmail Templates%s.','booking'),
                            '<a href="' . esc_url(  wpbc_get_settings_url(true, false) . '&tab=email' ) . '">', '</a>' );
                    ?></div></li>
                </ul>
            </div>
            <div class="welcome-panel-column welcome-panel-last">
                <h4><?php _e( 'Have a questions?','booking'); ?></h4>
                <ul>
                    <li><div class="welcome-icon"><?php
                        printf( __( 'Check out our %sHelp%s' ,'booking'),
                            '<a href="https://wpbookingcalendar.com/help/" target="_blank">',
                            '</a>' );
                    ?></div></li>
                    <li><div class="welcome-icon"><?php
                        printf( __( 'See %sFAQ%s.' ,'booking'),
                            '<a href="https://wpbookingcalendar.com/faq/" target="_blank">',
                            '</a>' );
                    ?></div></li>
                    <li><div class="welcome-icon"><?php
                        printf( __( 'Still having questions? Contact %sSupport%s.','booking'),
                            '<a href="https://wpbookingcalendar.com/support/" target="_blank">',
                            '</a>' );
                    ?></div></li>
                </ul>
            </div>
        </div>
        <div class="welcome-icon welcome-widgets-menus" style="text-align:right;font-style:italic;"><?php
            printf( __( 'Need even more functionality? Check %s higher versions %s','booking'),
                    '<a href="https://wpbookingcalendar.com/overview/" target="_blank">',
                    '</a>' 
                ); ?>
        </div>
    </div> 
    <?php
}

/** Get Warning Text  for Demo websites */
function wpbc_get_warning_text_in_demo_mode() {
    // return '<div class="wpbc-error-message wpbc_demo_test_version_warning"><strong>Warning!</strong> Demo test version does not allow changes to these items.</div>'; //Old Style
    return '<div class="wpbc-settings-notice notice-warning"><strong>Warning!</strong> Demo test version does not allow changes to these items.</div>';
}



////////////////////////////////////////////////////////////////////////////////
// Support functions for MU version
////////////////////////////////////////////////////////////////////////////////

/**
	 * Set  active User Environment in MultiUser  version, depend from owner of booking resource
 * 
 * @param int $previous_active_user (default=-1) - blank parameter
 * @param int $bktype - booking resource ID for checking
 * @return int - ID of Previous Active User
 * 
 * Usage:
   $previous_active_user = apply_bk_filter('wpbc_mu_set_environment_for_owner_of_resource', -1, $bktype );

 */
function wpbc_mu_set_environment_for_owner_of_resource( $previous_active_user = -1, $bktype = 1 ) {
    
    if ( class_exists('wpdev_bk_multiuser') )  {
        // Get  the owner of this booking resource                                    
        $user_bk_id = apply_bk_filter('get_user_of_this_bk_resource', false, $bktype );

        $user = wp_get_current_user();  
//        // Check if its different user
//        if ( ($user_bk_id !== false) && ($user->ID != $user_bk_id) ){         //FixIn: 7.0.1    
            // Get possible other active user settings
            $previous_active_user = apply_bk_filter('get_client_side_active_params_of_user'); 
//debuge('get_user_of_this_bk_resource', (int)$user_bk_id, $bktype, $previous_active_user);
            // Set active user of that specific booking resource
            make_bk_action('check_multiuser_params_for_client_side_by_user_id', $user_bk_id);
//        }                                                                     //FixIn: 7.0.1    
    }                            
    
    return $previous_active_user;
}
add_bk_filter('wpbc_mu_set_environment_for_owner_of_resource', 'wpbc_mu_set_environment_for_owner_of_resource');


/**
	 * Set environment for this user in MU version
 * 
 * @param int $previous_active_user - ID of user
 * Usage:
   make_bk_action('wpbc_mu_set_environment_for_user', $previous_active_user );
  
 */
function wpbc_mu_set_environment_for_user( $previous_active_user ) {
    
    if ( $previous_active_user !== -1 ) {
        // Reactivate the previous active user
        make_bk_action('check_multiuser_params_for_client_side_by_user_id', $previous_active_user );    
    }
}
add_bk_action('wpbc_mu_set_environment_for_user', 'wpbc_mu_set_environment_for_user');


////////////////////////////////////////////////////////////////////////////////
// Support functions in BS
////////////////////////////////////////////////////////////////////////////////
/**
	 * Format booking cost with a currency symbol.
 *  In MultiUser  version also checking about specific currency  that  belong to  specific WordPress user. 
 *  This checking based on belonging specific booking resource to  specific user.
 * 
 * @param float $cost
 * @param int $booking_resource_id
 * @return string                       - $cost_to_show_with_currency
 */
function wpbc_get_cost_with_currency_for_user( $cost, $booking_resource_id  = 0 ){
    
    if (  ( $cost === '' )  || ( ! class_exists( 'wpdev_bk_biz_s' ) )  ) 
        return '';
    
    
    if ( ! empty( $booking_resource_id ) )
        $previous_active_user = apply_bk_filter( 'wpbc_mu_set_environment_for_owner_of_resource', -1, $booking_resource_id );       // MU
//debuge($booking_resource_id , $previous_active_user, apply_bk_filter('get_user_of_this_bk_resource', false, $booking_resource_id ) );    
    $cost_to_show_with_currency = wpbc_cost_show( $cost, array(  'currency' => wpbc_get_currency() ) );
    
    if ( ! empty( $booking_resource_id ) )
        make_bk_action( 'wpbc_mu_set_environment_for_user', $previous_active_user );                                                // MU

    return $cost_to_show_with_currency;    
}



/**
	 * Get currency Symbol.
 *  In MultiUser  version also checking about specific currency  that  belong to  specific WordPress user. 
 *  This checking based on belonging specific booking resource to  specific user.
 * 
 * @param int $booking_resource_id  - ID of specific booking resource
 * @return string                   - currency  symbol
 */
function wpbc_get_currency_symbol_for_user( $booking_resource_id  = 0 ){

    if ( ! class_exists( 'wpdev_bk_biz_s' ) ) 
        return '';
    
    if ( ! empty( $booking_resource_id ) )
        $previous_active_user = apply_bk_filter( 'wpbc_mu_set_environment_for_owner_of_resource', -1, $booking_resource_id );       // MU
    
    $currency_symbol = wpbc_get_currency_symbol();
//debuge('$currency_symbol, $previous_active_user, $booking_resource_id ', $currency_symbol, $previous_active_user, $booking_resource_id );    
    if ( ! empty( $booking_resource_id ) )
        make_bk_action( 'wpbc_mu_set_environment_for_user', $previous_active_user );                                                // MU

    return $currency_symbol;
}



/**
	 * Check  if "Booking Manager" installed/activated and return version number
 * 
 * @return string - 0 if not installed,  otherwise version num
 */
function wpbc_get_wpbm_version() {
	
	if ( ! defined( 'WPBM_VERSION_NUM' ) )
		return 0;
	else 
		return WPBM_VERSION_NUM;
}


//FixIn: 8.4.7.20.2

/** Check if booking approved or not
 * @param $booking_id
 *
 * @return bool
 */
function wpbc_is_booking_approved( $booking_id ){                                                                       //FixIn: 8.1.2.8

	$is_booking_approved = false;

    global $wpdb;

    $dates_result = $wpdb->get_results( "SELECT DISTINCT approved FROM {$wpdb->prefix}bookingdates WHERE booking_id = {$booking_id} ORDER BY booking_date" );

    foreach ( $dates_result as $my_date ) {

	    if ( '1' == $my_date->approved ) {
	        $is_booking_approved = true;        //FixIn: 8.3.1.2
        }
    }

    return $is_booking_approved;
}


/**
 * Approve specific booking and send email about this.
 *
 * @param int $booking_id - ID of booking
 * @param string $email_reason
 */
function wpbc_auto_approve_booking( $booking_id , $email_reason = '' ) {

    global $wpdb;

    $booking_id = wpbc_clean_digit_or_csd( $booking_id );                   // Check  paramter  if it number or comma separated list  of numbers

	if ( is_numeric( $booking_id ) ) {                                                                                  //FixIn: 8.1.2.8
		if ( ! wpbc_is_booking_approved( $booking_id ) ) {
			do_action( 'wpbc_booking_approved', $booking_id, 1 );                                						//FixIn: 8.7.6.1
			wpbc_send_email_approved( $booking_id, 1, $email_reason );
		}
	} else {
		$booking_id_arr = explode( ',',$booking_id );
		foreach ( $booking_id_arr as $bk_id ) {
			if ( ! wpbc_is_booking_approved( $bk_id ) ) {
				do_action( 'wpbc_booking_approved', $bk_id, 1 );                                						//FixIn: 8.7.6.1
				wpbc_send_email_approved( $bk_id, 1, $email_reason );
			}

		}
	}

    $update_sql = "UPDATE {$wpdb->prefix}bookingdates SET approved = '1' WHERE booking_id IN ({$booking_id});";

    if ( false === $wpdb->query( $update_sql  ) ){

        wpbc_redirect( site_url()  );
    }
}


/**
 * Set as Pending specific booking and send email about this.
 *
 * @param int $booking_id - ID of booking
 * @param string $denyreason
 */
function wpbc_auto_pending_booking( $booking_id, $denyreason = '' ) {			 										//FixIn: 8.4.7.25

    global $wpdb;

    $booking_id = wpbc_clean_digit_or_csd( $booking_id );                   // Check  paramter  if it number or comma separated list  of numbers

	if ( is_numeric( $booking_id ) ) {                                                                                  //FixIn: 8.1.2.8
		if ( wpbc_is_booking_approved( $booking_id ) ) {
			wpbc_send_email_deny( $booking_id, 1, $denyreason );
		}
	} else {
		$booking_id_arr = explode( ',',$booking_id );
		foreach ( $booking_id_arr as $bk_id ) {
			if ( wpbc_is_booking_approved( $bk_id ) ) {
				wpbc_send_email_deny( $bk_id, 1, $denyreason );
			}

		}
	}

    $update_sql = "UPDATE {$wpdb->prefix}bookingdates SET approved = '0' WHERE booking_id IN ({$booking_id});";

    if ( false === $wpdb->query( $update_sql  ) ){

        wpbc_redirect( site_url()  );
    }
}


/**
 * Cancel (move to  Trash) specific booking.
 *
 * @param int $booking_id - ID of booking
 * @param string $email_reason	- 	reason  of cancellation
 */
function wpbc_auto_cancel_booking( $booking_id , $email_reason = '' ) {					//FixIn: 8.4.7.25

    global $wpdb;

    $booking_id = wpbc_clean_digit_or_csd( $booking_id );                   // Check  paramter  if it number or comma separated list  of numbers

	if ( empty( $email_reason ) ) {    //FixIn: 8.4.7.25
		// Get the reason of cancellation.
		$email_reason  = __( 'Payment rejected', 'booking' );
		$auto_cancel_pending_unpaid_bk_is_send_email = get_bk_option( 'booking_auto_cancel_pending_unpaid_bk_is_send_email' );
		if ( $auto_cancel_pending_unpaid_bk_is_send_email == 'On' ) {
			$email_reason = get_bk_option( 'booking_auto_cancel_pending_unpaid_bk_email_reason' );
		}
	}
    // Send decline emails
    wpbc_send_email_trash( $booking_id, 1, $email_reason );

    if ( false === $wpdb->query( "UPDATE {$wpdb->prefix}booking AS bk SET bk.trash = 1 WHERE booking_id IN ({$booking_id})" ) ){

        wpbc_redirect( site_url()  );
    }
}


//FixIn: 8.6.1.10
/**
 * Add Log info  to  Notes of bookings
 *
 * @param arr | int $booking_id_arr
 * @param string $message
 */
function wpbc_add_log_info( $booking_id_arr, $message ) {

	if ( get_bk_option( 'booking_log_booking_actions' ) !== 'On' ) {
		return;
	}

	$booking_id_arr = (array) $booking_id_arr;

	$is_append = true;
	foreach ( $booking_id_arr as $booking_id ) {
		$date_time = date_i18n( '[Y-m-d H:i] ' );
		make_bk_action('wpdev_make_update_of_remark' , $booking_id , $date_time . $message , $is_append );                //FixIn: 9.1.2.14
	}
}


/**
 * Get list of cities for timezone usage in format like:		$city["Europe"]["Kiev"] = "Kiev";
 *
 * @return array|array[]
 */
function wpbc_get_booking_region_cities_list(){                                                                         //FixIn: 8.9.4.9

	$city = array(
					  'Africa' => array()
					, 'America' => array()
					, 'Antarctica' => array()
					, 'Arctic' => array()
					, 'Asia' => array()
					, 'Atlantic' => array()
					, 'Australia' => array()
					, 'Europe' => array()
					, 'Indian' => array()
					, 'Pacific' => array()
				);
	$city["Africa"]["Abidjan"] = "Abidjan";
	$city["Africa"]["Accra"] = "Accra";
	$city["Africa"]["Addis_Ababa"] = "Addis Ababa";
	$city["Africa"]["Algiers"] = "Algiers";
	$city["Africa"]["Asmara"] = "Asmara";
	$city["Africa"]["Bamako"] = "Bamako";
	$city["Africa"]["Bangui"] = "Bangui";
	$city["Africa"]["Banjul"] = "Banjul";
	$city["Africa"]["Bissau"] = "Bissau";
	$city["Africa"]["Blantyre"] = "Blantyre";
	$city["Africa"]["Brazzaville"] = "Brazzaville";
	$city["Africa"]["Bujumbura"] = "Bujumbura";
	$city["Africa"]["Cairo"] = "Cairo";
	$city["Africa"]["Casablanca"] = "Casablanca";
	$city["Africa"]["Ceuta"] = "Ceuta";
	$city["Africa"]["Conakry"] = "Conakry";
	$city["Africa"]["Dakar"] = "Dakar";
	$city["Africa"]["Dar_es_Salaam"] = "Dar es Salaam";
	$city["Africa"]["Djibouti"] = "Djibouti";
	$city["Africa"]["Douala"] = "Douala";
	$city["Africa"]["El_Aaiun"] = "El Aaiun";
	$city["Africa"]["Freetown"] = "Freetown";
	$city["Africa"]["Gaborone"] = "Gaborone";
	$city["Africa"]["Harare"] = "Harare";
	$city["Africa"]["Johannesburg"] = "Johannesburg";
	$city["Africa"]["Kampala"] = "Kampala";
	$city["Africa"]["Khartoum"] = "Khartoum";
	$city["Africa"]["Kigali"] = "Kigali";
	$city["Africa"]["Kinshasa"] = "Kinshasa";
	$city["Africa"]["Lagos"] = "Lagos";
	$city["Africa"]["Libreville"] = "Libreville";
	$city["Africa"]["Lome"] = "Lome";
	$city["Africa"]["Luanda"] = "Luanda";
	$city["Africa"]["Lubumbashi"] = "Lubumbashi";
	$city["Africa"]["Lusaka"] = "Lusaka";
	$city["Africa"]["Malabo"] = "Malabo";
	$city["Africa"]["Maputo"] = "Maputo";
	$city["Africa"]["Maseru"] = "Maseru";
	$city["Africa"]["Mbabane"] = "Mbabane";
	$city["Africa"]["Mogadishu"] = "Mogadishu";
	$city["Africa"]["Monrovia"] = "Monrovia";
	$city["Africa"]["Nairobi"] = "Nairobi";
	$city["Africa"]["Ndjamena"] = "Ndjamena";
	$city["Africa"]["Niamey"] = "Niamey";
	$city["Africa"]["Nouakchott"] = "Nouakchott";
	$city["Africa"]["Ouagadougou"] = "Ouagadougou";
	$city["Africa"]["Porto-Novo"] = "Porto-Novo";
	$city["Africa"]["Sao_Tome"] = "Sao Tome";
	$city["Africa"]["Tripoli"] = "Tripoli";
	$city["Africa"]["Tunis"] = "Tunis";
	$city["Africa"]["Windhoek"] = "Windhoek";

	$city["America"]["Adak"] = "Adak";
	$city["America"]["Anchorage"] = "Anchorage";
	$city["America"]["Anguilla"] = "Anguilla";
	$city["America"]["Antigua"] = "Antigua";
	$city["America"]["Araguaina"] = "Araguaina";
	$city["America"]["Argentina/Buenos_Aires"] = "Argentina - Buenos Aires";
	$city["America"]["Argentina/Catamarca"] = "Argentina - Catamarca";
	$city["America"]["Argentina/Cordoba"] = "Argentina - Cordoba";
	$city["America"]["Argentina/Jujuy"] = "Argentina - Jujuy";
	$city["America"]["Argentina/La_Rioja"] = "Argentina - La Rioja";
	$city["America"]["Argentina/Mendoza"] = "Argentina - Mendoza";
	$city["America"]["Argentina/Rio_Gallegos"] = "Argentina - Rio Gallegos";
	$city["America"]["Argentina/Salta"] = "Argentina - Salta";
	$city["America"]["Argentina/San_Juan"] = "Argentina - San Juan";
	$city["America"]["Argentina/San_Luis"] = "Argentina - San Luis";
	$city["America"]["Argentina/Tucuman"] = "Argentina - Tucuman";
	$city["America"]["Argentina/Ushuaia"] = "Argentina - Ushuaia";
	$city["America"]["Aruba"] = "Aruba";
	$city["America"]["Asuncion"] = "Asuncion";
	$city["America"]["Atikokan"] = "Atikokan";
	$city["America"]["Bahia"] = "Bahia";
	$city["America"]["Barbados"] = "Barbados";
	$city["America"]["Belem"] = "Belem";
	$city["America"]["Belize"] = "Belize";
	$city["America"]["Blanc-Sablon"] = "Blanc-Sablon";
	$city["America"]["Boa_Vista"] = "Boa Vista";
	$city["America"]["Bogota"] = "Bogota";
	$city["America"]["Boise"] = "Boise";
	$city["America"]["Cambridge_Bay"] = "Cambridge Bay";
	$city["America"]["Campo_Grande"] = "Campo Grande";
	$city["America"]["Cancun"] = "Cancun";
	$city["America"]["Caracas"] = "Caracas";
	$city["America"]["Cayenne"] = "Cayenne";
	$city["America"]["Cayman"] = "Cayman";
	$city["America"]["Chicago"] = "Chicago";
	$city["America"]["Chihuahua"] = "Chihuahua";
	$city["America"]["Costa_Rica"] = "Costa Rica";
	$city["America"]["Cuiaba"] = "Cuiaba";
	$city["America"]["Curacao"] = "Curacao";
	$city["America"]["Danmarkshavn"] = "Danmarkshavn";
	$city["America"]["Dawson"] = "Dawson";
	$city["America"]["Dawson_Creek"] = "Dawson Creek";
	$city["America"]["Denver"] = "Denver";
	$city["America"]["Detroit"] = "Detroit";
	$city["America"]["Dominica"] = "Dominica";
	$city["America"]["Edmonton"] = "Edmonton";
	$city["America"]["Eirunepe"] = "Eirunepe";
	$city["America"]["El_Salvador"] = "El Salvador";
	$city["America"]["Fortaleza"] = "Fortaleza";
	$city["America"]["Glace_Bay"] = "Glace Bay";
	$city["America"]["Godthab"] = "Godthab";
	$city["America"]["Goose_Bay"] = "Goose Bay";
	$city["America"]["Grand_Turk"] = "Grand Turk";
	$city["America"]["Grenada"] = "Grenada";
	$city["America"]["Guadeloupe"] = "Guadeloupe";
	$city["America"]["Guatemala"] = "Guatemala";
	$city["America"]["Guayaquil"] = "Guayaquil";
	$city["America"]["Guyana"] = "Guyana";
	$city["America"]["Halifax"] = "Halifax";
	$city["America"]["Havana"] = "Havana";
	$city["America"]["Hermosillo"] = "Hermosillo";
	$city["America"]["Indiana/Indianapolis"] = "Indiana - Indianapolis";
	$city["America"]["Indiana/Knox"] = "Indiana - Knox";
	$city["America"]["Indiana/Marengo"] = "Indiana - Marengo";
	$city["America"]["Indiana/Petersburg"] = "Indiana - Petersburg";
	$city["America"]["Indiana/Tell_City"] = "Indiana - Tell City";
	$city["America"]["Indiana/Vevay"] = "Indiana - Vevay";
	$city["America"]["Indiana/Vincennes"] = "Indiana - Vincennes";
	$city["America"]["Indiana/Winamac"] = "Indiana - Winamac";
	$city["America"]["Inuvik"] = "Inuvik";
	$city["America"]["Iqaluit"] = "Iqaluit";
	$city["America"]["Jamaica"] = "Jamaica";
	$city["America"]["Juneau"] = "Juneau";
	$city["America"]["Kentucky/Louisville"] = "Kentucky - Louisville";
	$city["America"]["Kentucky/Monticello"] = "Kentucky - Monticello";
	$city["America"]["La_Paz"] = "La Paz";
	$city["America"]["Lima"] = "Lima";
	$city["America"]["Los_Angeles"] = "Los Angeles";
	$city["America"]["Maceio"] = "Maceio";
	$city["America"]["Managua"] = "Managua";
	$city["America"]["Manaus"] = "Manaus";
	$city["America"]["Marigot"] = "Marigot";
	$city["America"]["Martinique"] = "Martinique";
	$city["America"]["Mazatlan"] = "Mazatlan";
	$city["America"]["Menominee"] = "Menominee";
	$city["America"]["Merida"] = "Merida";
	$city["America"]["Mexico_City"] = "Mexico City";
	$city["America"]["Miquelon"] = "Miquelon";
	$city["America"]["Moncton"] = "Moncton";
	$city["America"]["Monterrey"] = "Monterrey";
	$city["America"]["Montevideo"] = "Montevideo";
	$city["America"]["Montreal"] = "Montreal";
	$city["America"]["Montserrat"] = "Montserrat";
	$city["America"]["Nassau"] = "Nassau";
	$city["America"]["New_York"] = "New York";
	$city["America"]["Nipigon"] = "Nipigon";
	$city["America"]["Nome"] = "Nome";
	$city["America"]["Noronha"] = "Noronha";
	$city["America"]["North_Dakota/Center"] = "North Dakota - Center";
	$city["America"]["North_Dakota/New_Salem"] = "North Dakota - New Salem";
	$city["America"]["Panama"] = "Panama";
	$city["America"]["Pangnirtung"] = "Pangnirtung";
	$city["America"]["Paramaribo"] = "Paramaribo";
	$city["America"]["Phoenix"] = "Phoenix";
	$city["America"]["Port-au-Prince"] = "Port-au-Prince";
	$city["America"]["Port_of_Spain"] = "Port of Spain";
	$city["America"]["Porto_Velho"] = "Porto Velho";
	$city["America"]["Puerto_Rico"] = "Puerto Rico";
	$city["America"]["Rainy_River"] = "Rainy River";
	$city["America"]["Rankin_Inlet"] = "Rankin Inlet";
	$city["America"]["Recife"] = "Recife";
	$city["America"]["Regina"] = "Regina";
	$city["America"]["Resolute"] = "Resolute";
	$city["America"]["Rio_Branco"] = "Rio Branco";
	$city["America"]["Santarem"] = "Santarem";
	$city["America"]["Santiago"] = "Santiago";
	$city["America"]["Santo_Domingo"] = "Santo Domingo";
	$city["America"]["Sao_Paulo"] = "Sao Paulo";
	$city["America"]["Scoresbysund"] = "Scoresbysund";
	$city["America"]["Shiprock"] = "Shiprock";
	$city["America"]["St_Barthelemy"] = "St Barthelemy";
	$city["America"]["St_Johns"] = "St Johns";
	$city["America"]["St_Kitts"] = "St Kitts";
	$city["America"]["St_Lucia"] = "St Lucia";
	$city["America"]["St_Thomas"] = "St Thomas";
	$city["America"]["St_Vincent"] = "St Vincent";
	$city["America"]["Swift_Current"] = "Swift Current";
	$city["America"]["Tegucigalpa"] = "Tegucigalpa";
	$city["America"]["Thule"] = "Thule";
	$city["America"]["Thunder_Bay"] = "Thunder Bay";
	$city["America"]["Tijuana"] = "Tijuana";
	$city["America"]["Toronto"] = "Toronto";
	$city["America"]["Tortola"] = "Tortola";
	$city["America"]["Vancouver"] = "Vancouver";
	$city["America"]["Whitehorse"] = "Whitehorse";
	$city["America"]["Winnipeg"] = "Winnipeg";
	$city["America"]["Yakutat"] = "Yakutat";
	$city["America"]["Yellowknife"] = "Yellowknife";

	$city["Antarctica"]["Casey"] = "Casey";
	$city["Antarctica"]["Davis"] = "Davis";
	$city["Antarctica"]["DumontDUrville"] = "DumontDUrville";
	$city["Antarctica"]["Mawson"] = "Mawson";
	$city["Antarctica"]["McMurdo"] = "McMurdo";
	$city["Antarctica"]["Palmer"] = "Palmer";
	$city["Antarctica"]["Rothera"] = "Rothera";
	$city["Antarctica"]["South_Pole"] = "South Pole";
	$city["Antarctica"]["Syowa"] = "Syowa";
	$city["Antarctica"]["Vostok"] = "Vostok";

	$city["Arctic"]["Longyearbyen"] = "Longyearbyen";

	$city["Asia"]["Aden"] = "Aden";
	$city["Asia"]["Almaty"] = "Almaty";
	$city["Asia"]["Amman"] = "Amman";
	$city["Asia"]["Anadyr"] = "Anadyr";
	$city["Asia"]["Aqtau"] = "Aqtau";
	$city["Asia"]["Aqtobe"] = "Aqtobe";
	$city["Asia"]["Ashgabat"] = "Ashgabat";
	$city["Asia"]["Baghdad"] = "Baghdad";
	$city["Asia"]["Bahrain"] = "Bahrain";
	$city["Asia"]["Baku"] = "Baku";
	$city["Asia"]["Bangkok"] = "Bangkok";
	$city["Asia"]["Beirut"] = "Beirut";
	$city["Asia"]["Bishkek"] = "Bishkek";
	$city["Asia"]["Brunei"] = "Brunei";
	$city["Asia"]["Choibalsan"] = "Choibalsan";
	$city["Asia"]["Chongqing"] = "Chongqing";
	$city["Asia"]["Colombo"] = "Colombo";
	$city["Asia"]["Damascus"] = "Damascus";
	$city["Asia"]["Dhaka"] = "Dhaka";
	$city["Asia"]["Dili"] = "Dili";
	$city["Asia"]["Dubai"] = "Dubai";
	$city["Asia"]["Dushanbe"] = "Dushanbe";
	$city["Asia"]["Gaza"] = "Gaza";
	$city["Asia"]["Harbin"] = "Harbin";
	$city["Asia"]["Ho_Chi_Minh"] = "Ho Chi Minh";
	$city["Asia"]["Hong_Kong"] = "Hong Kong";
	$city["Asia"]["Hovd"] = "Hovd";
	$city["Asia"]["Irkutsk"] = "Irkutsk";
	$city["Asia"]["Jakarta"] = "Jakarta";
	$city["Asia"]["Jayapura"] = "Jayapura";
	$city["Asia"]["Jerusalem"] = "Jerusalem";
	$city["Asia"]["Kabul"] = "Kabul";
	$city["Asia"]["Kamchatka"] = "Kamchatka";
	$city["Asia"]["Karachi"] = "Karachi";
	$city["Asia"]["Kashgar"] = "Kashgar";
	$city["Asia"]["Kathmandu"] = "Kathmandu";
	$city["Asia"]["Kolkata"] = "Kolkata";
	$city["Asia"]["Krasnoyarsk"] = "Krasnoyarsk";
	$city["Asia"]["Kuala_Lumpur"] = "Kuala Lumpur";
	$city["Asia"]["Kuching"] = "Kuching";
	$city["Asia"]["Kuwait"] = "Kuwait";
	$city["Asia"]["Macau"] = "Macau";
	$city["Asia"]["Magadan"] = "Magadan";
	$city["Asia"]["Makassar"] = "Makassar";
	$city["Asia"]["Manila"] = "Manila";
	$city["Asia"]["Muscat"] = "Muscat";
	$city["Asia"]["Nicosia"] = "Nicosia";
	$city["Asia"]["Novosibirsk"] = "Novosibirsk";
	$city["Asia"]["Omsk"] = "Omsk";
	$city["Asia"]["Oral"] = "Oral";
	$city["Asia"]["Phnom_Penh"] = "Phnom Penh";
	$city["Asia"]["Pontianak"] = "Pontianak";
	$city["Asia"]["Pyongyang"] = "Pyongyang";
	$city["Asia"]["Qatar"] = "Qatar";
	$city["Asia"]["Qyzylorda"] = "Qyzylorda";
	$city["Asia"]["Rangoon"] = "Rangoon";
	$city["Asia"]["Riyadh"] = "Riyadh";
	$city["Asia"]["Sakhalin"] = "Sakhalin";
	$city["Asia"]["Samarkand"] = "Samarkand";
	$city["Asia"]["Seoul"] = "Seoul";
	$city["Asia"]["Shanghai"] = "Shanghai";
	$city["Asia"]["Singapore"] = "Singapore";
	$city["Asia"]["Taipei"] = "Taipei";
	$city["Asia"]["Tashkent"] = "Tashkent";
	$city["Asia"]["Tbilisi"] = "Tbilisi";
	$city["Asia"]["Tehran"] = "Tehran";
	$city["Asia"]["Thimphu"] = "Thimphu";
	$city["Asia"]["Tokyo"] = "Tokyo";
	$city["Asia"]["Ulaanbaatar"] = "Ulaanbaatar";
	$city["Asia"]["Urumqi"] = "Urumqi";
	$city["Asia"]["Vientiane"] = "Vientiane";
	$city["Asia"]["Vladivostok"] = "Vladivostok";
	$city["Asia"]["Yakutsk"] = "Yakutsk";
	$city["Asia"]["Yekaterinburg"] = "Yekaterinburg";
	$city["Asia"]["Yerevan"] = "Yerevan";

	$city["Atlantic"]["Azores"] = "Azores";
	$city["Atlantic"]["Bermuda"] = "Bermuda";
	$city["Atlantic"]["Canary"] = "Canary";
	$city["Atlantic"]["Cape_Verde"] = "Cape Verde";
	$city["Atlantic"]["Faroe"] = "Faroe";
	$city["Atlantic"]["Madeira"] = "Madeira";
	$city["Atlantic"]["Reykjavik"] = "Reykjavik";
	$city["Atlantic"]["South_Georgia"] = "South Georgia";
	$city["Atlantic"]["Stanley"] = "Stanley";
	$city["Atlantic"]["St_Helena"] = "St Helena";

	$city["Australia"]["Adelaide"] = "Adelaide";
	$city["Australia"]["Brisbane"] = "Brisbane";
	$city["Australia"]["Broken_Hill"] = "Broken Hill";
	$city["Australia"]["Currie"] = "Currie";
	$city["Australia"]["Darwin"] = "Darwin";
	$city["Australia"]["Eucla"] = "Eucla";
	$city["Australia"]["Hobart"] = "Hobart";
	$city["Australia"]["Lindeman"] = "Lindeman";
	$city["Australia"]["Lord_Howe"] = "Lord Howe";
	$city["Australia"]["Melbourne"] = "Melbourne";
	$city["Australia"]["Perth"] = "Perth";
	$city["Australia"]["Sydney"] = "Sydney";

	$city["Europe"]["Amsterdam"] = "Amsterdam";
	$city["Europe"]["Andorra"] = "Andorra";
	$city["Europe"]["Athens"] = "Athens";
	$city["Europe"]["Belgrade"] = "Belgrade";
	$city["Europe"]["Berlin"] = "Berlin";
	$city["Europe"]["Bratislava"] = "Bratislava";
	$city["Europe"]["Brussels"] = "Brussels";
	$city["Europe"]["Bucharest"] = "Bucharest";
	$city["Europe"]["Budapest"] = "Budapest";
	$city["Europe"]["Chisinau"] = "Chisinau";
	$city["Europe"]["Copenhagen"] = "Copenhagen";
	$city["Europe"]["Dublin"] = "Dublin";
	$city["Europe"]["Gibraltar"] = "Gibraltar";
	$city["Europe"]["Guernsey"] = "Guernsey";
	$city["Europe"]["Helsinki"] = "Helsinki";
	$city["Europe"]["Isle_of_Man"] = "Isle of Man";
	$city["Europe"]["Istanbul"] = "Istanbul";
	$city["Europe"]["Jersey"] = "Jersey";
	$city["Europe"]["Kaliningrad"] = "Kaliningrad";
	$city["Europe"]["Kiev"] = "Kiev";
	$city["Europe"]["Lisbon"] = "Lisbon";
	$city["Europe"]["Ljubljana"] = "Ljubljana";
	$city["Europe"]["London"] = "London";
	$city["Europe"]["Luxembourg"] = "Luxembourg";
	$city["Europe"]["Madrid"] = "Madrid";
	$city["Europe"]["Malta"] = "Malta";
	$city["Europe"]["Mariehamn"] = "Mariehamn";
	$city["Europe"]["Minsk"] = "Minsk";
	$city["Europe"]["Monaco"] = "Monaco";
	$city["Europe"]["Moscow"] = "Moscow";
	$city["Europe"]["Oslo"] = "Oslo";
	$city["Europe"]["Paris"] = "Paris";
	$city["Europe"]["Podgorica"] = "Podgorica";
	$city["Europe"]["Prague"] = "Prague";
	$city["Europe"]["Riga"] = "Riga";
	$city["Europe"]["Rome"] = "Rome";
	$city["Europe"]["Samara"] = "Samara";
	$city["Europe"]["San_Marino"] = "San Marino";
	$city["Europe"]["Sarajevo"] = "Sarajevo";
	$city["Europe"]["Simferopol"] = "Simferopol";
	$city["Europe"]["Skopje"] = "Skopje";
	$city["Europe"]["Sofia"] = "Sofia";
	$city["Europe"]["Stockholm"] = "Stockholm";
	$city["Europe"]["Tallinn"] = "Tallinn";
	$city["Europe"]["Tirane"] = "Tirane";
	$city["Europe"]["Uzhgorod"] = "Uzhgorod";
	$city["Europe"]["Vaduz"] = "Vaduz";
	$city["Europe"]["Vatican"] = "Vatican";
	$city["Europe"]["Vienna"] = "Vienna";
	$city["Europe"]["Vilnius"] = "Vilnius";
	$city["Europe"]["Volgograd"] = "Volgograd";
	$city["Europe"]["Warsaw"] = "Warsaw";
	$city["Europe"]["Zagreb"] = "Zagreb";
	$city["Europe"]["Zaporozhye"] = "Zaporozhye";
	$city["Europe"]["Zurich"] = "Zurich";

	$city["Indian"]["Antananarivo"] = "Antananarivo";
	$city["Indian"]["Chagos"] = "Chagos";
	$city["Indian"]["Christmas"] = "Christmas";
	$city["Indian"]["Cocos"] = "Cocos";
	$city["Indian"]["Comoro"] = "Comoro";
	$city["Indian"]["Kerguelen"] = "Kerguelen";
	$city["Indian"]["Mahe"] = "Mahe";
	$city["Indian"]["Maldives"] = "Maldives";
	$city["Indian"]["Mauritius"] = "Mauritius";
	$city["Indian"]["Mayotte"] = "Mayotte";
	$city["Indian"]["Reunion"] = "Reunion";

	$city["Pacific"]["Apia"] = "Apia";
	$city["Pacific"]["Auckland"] = "Auckland";
	$city["Pacific"]["Chatham"] = "Chatham";
	$city["Pacific"]["Easter"] = "Easter";
	$city["Pacific"]["Efate"] = "Efate";
	$city["Pacific"]["Enderbury"] = "Enderbury";
	$city["Pacific"]["Fakaofo"] = "Fakaofo";
	$city["Pacific"]["Fiji"] = "Fiji";
	$city["Pacific"]["Funafuti"] = "Funafuti";
	$city["Pacific"]["Galapagos"] = "Galapagos";
	$city["Pacific"]["Gambier"] = "Gambier";
	$city["Pacific"]["Guadalcanal"] = "Guadalcanal";
	$city["Pacific"]["Guam"] = "Guam";
	$city["Pacific"]["Honolulu"] = "Honolulu";
	$city["Pacific"]["Johnston"] = "Johnston";
	$city["Pacific"]["Kiritimati"] = "Kiritimati";
	$city["Pacific"]["Kosrae"] = "Kosrae";
	$city["Pacific"]["Kwajalein"] = "Kwajalein";
	$city["Pacific"]["Majuro"] = "Majuro";
	$city["Pacific"]["Marquesas"] = "Marquesas";
	$city["Pacific"]["Midway"] = "Midway";
	$city["Pacific"]["Nauru"] = "Nauru";
	$city["Pacific"]["Niue"] = "Niue";
	$city["Pacific"]["Norfolk"] = "Norfolk";
	$city["Pacific"]["Noumea"] = "Noumea";
	$city["Pacific"]["Pago_Pago"] = "Pago Pago";
	$city["Pacific"]["Palau"] = "Palau";
	$city["Pacific"]["Pitcairn"] = "Pitcairn";
	$city["Pacific"]["Ponape"] = "Ponape";
	$city["Pacific"]["Port_Moresby"] = "Port Moresby";
	$city["Pacific"]["Rarotonga"] = "Rarotonga";
	$city["Pacific"]["Saipan"] = "Saipan";
	$city["Pacific"]["Tahiti"] = "Tahiti";
	$city["Pacific"]["Tarawa"] = "Tarawa";
	$city["Pacific"]["Tongatapu"] = "Tongatapu";
	$city["Pacific"]["Truk"] = "Truk";
	$city["Pacific"]["Wake"] = "Wake";
	$city["Pacific"]["Wallis"] = "Wallis";

	return $city;
}



////////////////////////////////////////////////////////////////////////////////
// Support functions for CSV  export
////////////////////////////////////////////////////////////////////////////////

	/**
	 * Get Path from request URL.  E.g.  'my-category/file.csv'  from  url,  like  https://server.com/my-category/file.csv
	 *
	 * It can detect internal sub folder or WordPress,  like http://server.com/my-website/
	 *
	 * @return false|string
	 */
	function wpbc_get_request_url_path(){

		if ( function_exists( 'wp_parse_url' ) ) {
			$my_parsed_url = wp_parse_url( $_SERVER['REQUEST_URI'] );
		} else {
			$my_parsed_url = @parse_url( $_SERVER['REQUEST_URI'] );
		}

		if ( false === $my_parsed_url ) {        // seriously malformed URLs, parse_url() may return FALSE.
			return false;
		}

		$my_parsed_url_path = trim( $my_parsed_url['path'] );
		$my_parsed_url_path = trim( $my_parsed_url_path, '/' );


		// Check internal sub folder of WP,  like http://server.com/my-website/[ ... LINK ...]                              //FixIn: 2.0.5.4
		if ( function_exists( 'wp_parse_url' ) ) {
			$wp_home_server_url = wp_parse_url( home_url() );
		} else {
			$wp_home_server_url = @parse_url( home_url() );
		}

		if ( ( false !== $wp_home_server_url ) && ( ! empty( $wp_home_server_url['path'] ) ) ) {		                    // seriously malformed URLs, parse_url() may return FALSE.

			$server_url_sufix	 = trim( $wp_home_server_url[ 'path' ] );       // [path] => /my-website
			$server_url_sufix	 = trim( $server_url_sufix, '/' );              // my-website

			if ( ! empty( $server_url_sufix ) ) {

				$check_sufix = substr( $my_parsed_url_path, 0, strlen( $server_url_sufix ) );

				if ( $check_sufix === $server_url_sufix ) {

					$my_parsed_url_path = substr( $my_parsed_url_path, strlen( $server_url_sufix ) );

					$my_parsed_url_path = trim( $my_parsed_url_path, '/' );
				}
			}
		}                                                                                                                   //End FixIn: 2.0.5.4

		return $my_parsed_url_path;
	}


	/** Count the number of bytes of a given string.
	* Input string is expected to be ASCII or UTF-8 encoded.
	* Warning: the function doesn't return the number of chars
	* in the string, but the number of bytes.
	* See http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
	* for information on UTF-8.
	*
	* @param string $str The string to compute number of bytes
	*
	* @return The length in bytes of the given string.
	*/
	function wpbc_get_bytes_from_str( $str ) {
		// STRINGS ARE EXPECTED TO BE IN ASCII OR UTF-8 FORMAT
		// Number of characters in string
		$strlen_var = strlen( $str );

		$d = 0;			// string bytes counter

		// Iterate over every character in the string, escaping with a slash or encoding to UTF-8 where necessary
		for ( $c = 0; $c < $strlen_var; ++ $c ) {
			$ord_var_c = ord( $str[$c] );        //FixIn: 2.0.17.1
			switch ( true ) {
				case(($ord_var_c >= 0x20) && ($ord_var_c <= 0x7F)):		// characters U-00000000 - U-0000007F (same as ASCII)
					$d ++;
					break;
				case(($ord_var_c & 0xE0) == 0xC0):						// characters U-00000080 - U-000007FF, mask 110XXXXX
					$d += 2;
					break;
				case(($ord_var_c & 0xF0) == 0xE0):						// characters U-00000800 - U-0000FFFF, mask 1110XXXX
					$d += 3;
					break;
				case(($ord_var_c & 0xF8) == 0xF0):						// characters U-00010000 - U-001FFFFF, mask 11110XXX
					$d += 4;
					break;
				case(($ord_var_c & 0xFC) == 0xF8):						// characters U-00200000 - U-03FFFFFF, mask 111110XX
					$d += 5;
					break;
				case(($ord_var_c & 0xFE) == 0xFC):						// characters U-04000000 - U-7FFFFFFF, mask 1111110X
					$d += 6;
					break;
				default:
					$d ++;
			}
		}
		return $d;
	}

