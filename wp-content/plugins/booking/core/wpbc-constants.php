<?php
/**
 * @version 1.0
 * @package Booking Calendar 
 * @subpackage Define Constants
 * @category Bookings
 * 
 * @author wpdevelop
 * @link https://wpbookingcalendar.com/
 * @email info@wpbookingcalendar.com
 *
 * @modified 2014.05.17
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly

////////////////////////////////////////////////////////////
//   USERS  CONFIGURABLE  CONSTANTS           //////////////
////////////////////////////////////////////////////////////

if ( ! defined( 'WP_BK_CHECK_LESS_THAN_PARAM_IN_SEARCH' ) ) {   define( 'WP_BK_CHECK_LESS_THAN_PARAM_IN_SEARCH', false ); }         // Its will set 'Less than' logic for numbers in search  form  for custom  fields.
if ( ! defined( 'WP_BK_CHECK_IF_CUSTOM_PARAM_IN_SEARCH' ) ) {   define( 'WP_BK_CHECK_IF_CUSTOM_PARAM_IN_SEARCH', true ); }	        // Logical 'OR'.        Check (in search results) custom fields parameter that can include to  multiple selected options in search form.
if ( ! defined( 'WP_BK_CHECK_OUT_MINUS_DAY_SEARCH' ) ) {        define( 'WP_BK_CHECK_OUT_MINUS_DAY_SEARCH', '0' ); }	            // Define minus or plus some day(s) for check out search days. Search availability workflow for some customers.


////////////////////////////////////////////////////////////
//   SYSTEM  CONSTANTS                        //////////////
////////////////////////////////////////////////////////////
if ( ! defined( 'WP_BK_MINOR_UPDATE' ) ) {      define( 'WP_BK_MINOR_UPDATE',   true ); }
if ( ! defined( 'WP_BK_RESPONSE' ) ) {          define( 'WP_BK_RESPONSE',       false ); }
if ( ! defined( 'WP_BK_BETA_DATA_FILL' ) ) {    define( 'WP_BK_BETA_DATA_FILL', 0 ); }                                  // Set 0 for no filling or 2 for 241 bookings or more for more


////////////////////////////////////////////////////////////
//   Deprecated
////////////////////////////////////////////////////////////
// 'WP_BK_PAYMENT_FORM_ONLY_IN_REQUEST'                 -   deprecated   -   configure this option at Booking > Settings > Payment page     //FixIn: 8.1.3.2x   // Its will show payment form  only in payment request during sending from  Booking Listing page and do not show payment form  after  visitor made the booking.
// 'WP_BK_AUTO_SEND_PAY_REQUEST_IF_ADD_IN_ADMIN_PANEL'  -   deprecated   -   configure this option at Booking > Settings > Payment page     //FixIn: 8.1.3.2x   // Auto send payment request,  if booking was added in admin panel,  and WP_BK_AUTO_APPROVE_IF_ADD_IN_ADMIN_PANEL == true
// 'WP_BK_SHOW_DEPOSIT_AND_TOTAL_PAYMENT'               -   deprecated   -   configure this option at Booking > Settings > Payment page     //FixIn: 8.1.3.2x   // Show both deposit and total cost payment forms, after visitor submit booking. Important! Please note, in this case at admin panel for booking will be saved deposit cost and notes about deposit, do not depend from the visitor choise of this payment. So you need to check each such payment manually.
// 'WP_BK_AUTO_APPROVE_WHEN_IMPORT_GCAL'                -   deprecated   -   configure this option at Booking > Settings General page       //FixIn: 8.1.3.2x   // Auto  approve booking,  if imported from Google Calendar   //FixIn:7.0.1.59
// 'WP_BK_AUTO_APPROVE_WHEN_ZERO_COST'                  -   deprecated   -   configure this option at Booking > Settings General page       //FixIn: 8.1.3.2x   // Auto  approve booking,  if the cost of booking == 0
// 'WP_BK_AUTO_APPROVE_IF_ADD_IN_ADMIN_PANEL'           -   deprecated   -   configure this option at Booking > Settings General page       //FixIn: 8.1.3.2x   // Auto  approve booking,  if booking added in admin panel
// 'WP_BK_LAST_CHECKOUT_DAY_AVAILABLE'                  -   deprecated   -   configure this option at Booking > Settings General page       //FixIn: 8.1.3.28   // Its will remove last selected day  of booking during saving it as booking.   //FixIn: 6.2.3.6
