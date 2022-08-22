<?php
/**
 * @package     VikBooking
 * @subpackage  com_vikbooking
 * @author      Alessio Gaggii - e4j - Extensionsforjoomla.com
 * @copyright   Copyright (C) 2018 e4j - Extensionsforjoomla.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @link        https://vikwp.com
 */

defined('ABSPATH') or die('No script kiddies please!');

/**
 * Class handler for admin widget "bookings calendar".
 * 
 * @since 	1.15.0 (J) - 1.5.0 (WP)
 */
class VikBookingAdminWidgetBookingsCalendar extends VikBookingAdminWidget
{
	/**
	 * The instance counter of this widget. Since we do not load individual parameters
	 * for each widget's instance, we use a static counter to determine its settings.
	 *
	 * @var 	int
	 */
	protected static $instance_counter = -1;

	/**
	 * Default number of bookings per page.
	 * 
	 * @var 	int
	 */
	protected $bookings_per_page = 6;

	/**
	 * Class constructor will define the widget name and identifier.
	 */
	public function __construct()
	{
		// call parent constructor
		parent::__construct();

		$this->widgetName = JText::translate('VBO_W_BOOKSCAL_TITLE');
		$this->widgetDescr = JText::translate('VBO_W_BOOKSCAL_DESCR');
		$this->widgetId = basename(__FILE__, '.php');

		// define widget and icon and style name
		$this->widgetIcon = '<i class="' . VikBookingIcons::i('calendar') . '"></i>';
		$this->widgetStyleName = 'brown';
	}

	/**
	 * Custom method for this widget only to load the bookings calendar.
	 * The method is called by the admin controller through an AJAX request.
	 * The visibility should be public, it should not exit the process, and
	 * any content sent to output will be returned to the AJAX response.
	 * In this case we return an array because this method requires "return":1.
	 */
	public function loadBookingsCalendar()
	{
		// get today's date
		$today_ymd = date('Y-m-d');

		$offset = VikRequest::getString('offset', $today_ymd, 'request');
		$wrapper = VikRequest::getString('wrapper', '', 'request');
		$room_id = VikRequest::getInt('room_id', 0, 'request');
		$date_dir = VikRequest::getInt('date_dir', 0, 'request');
		$bid = VikRequest::getInt('bid', 0, 'request');
		$bid = !empty($date_dir) ? 0 : $bid;

		// the booking ID can be passed in case of multitask data for this page
		if (!empty($bid)) {
			// load the availability of the month when booking starts
			$booking_info = VikBooking::getBookingInfoFromID($bid);
			if ($booking_info && count($booking_info)) {
				// force the offset to use as start date
				$offset = date('Y-m-d', $booking_info['checkin']);
			}
		}

		// calculate date timestamps interval
		$now_info = getdate(strtotime($offset));
		if ($date_dir > 0) {
			// next month from current offset
			$from_ts = mktime(0, 0, 0, ($now_info['mon'] + 1), 1, $now_info['year']);
			$to_ts = mktime(23, 59, 59, ($now_info['mon'] + 1), date('t', $from_ts), $now_info['year']);
		} elseif ($date_dir < 0) {
			// prev month from current offset
			$from_ts = mktime(0, 0, 0, ($now_info['mon'] - 1), 1, $now_info['year']);
			$to_ts = mktime(23, 59, 59, ($now_info['mon'] - 1), date('t', $from_ts), $now_info['year']);
		} else {
			// no navigation, use current offset
			$from_ts = mktime(0, 0, 0, $now_info['mon'], 1, $now_info['year']);
			$to_ts = mktime(23, 59, 59, $now_info['mon'], date('t', $now_info[0]), $now_info['year']);
		}

		// build week days list according to settings
		$firstwday = (int)VikBooking::getFirstWeekDay(true);
		$days_labels = array(
			JText::translate('VBSUN'),
			JText::translate('VBMON'),
			JText::translate('VBTUE'),
			JText::translate('VBWED'),
			JText::translate('VBTHU'),
			JText::translate('VBFRI'),
			JText::translate('VBSAT'),
		);
		$days_indexes = array();
		for ($i = 0; $i < 7; $i++) {
			$days_indexes[$i] = (6 - ($firstwday - $i) + 1) % 7;
		}

		// start looping from the first day of the current month
		$info_arr = getdate($from_ts);

		// build period name
		$period_date = VikBooking::sayMonth($info_arr['mon']) . ' ' . $info_arr['year'];

		// invoke availability helper class
		$av_helper = VikBooking::getAvailabilityInstance();

		// get all rooms
		$all_rooms = $av_helper->loadRooms();

		// count maximum units available depending on filter
		$tot_rooms = 1;
		if (!empty($room_id) && isset($all_rooms[$room_id])) {
			// use the units of the filtered room
			$max_units = $all_rooms[$room_id]['units'];
		} else {
			// sum all room units
			$max_units = 0;
			$tot_rooms = count($all_rooms);
			foreach ($all_rooms as $rid => $room) {
				$max_units += $room['units'];
			}
		}

		// build "search name"
		if (!empty($room_id) && isset($all_rooms[$room_id])) {
			$search_name = $all_rooms[$room_id]['name'];
		} else {
			$search_name = preg_replace("/[^A-Za-z0-9 ]/", '', JText::translate('VBOSTATSALLROOMS'));
		}

		// load busy records
		$room_filter = !empty($room_id) ? array($room_id) : array();
		$busy_records = VikBooking::loadBusyRecords($room_filter, $from_ts, $to_ts);

		// load festivities or room-day notes
		$festivities = array();
		$rday_notes  = array();
		if (!empty($room_id) && isset($all_rooms[$room_id])) {
			// load room-day notes for the given room id
			$rday_notes = VikBooking::getCriticalDatesInstance()->loadRoomDayNotes(date('Y-m-d', $from_ts), date('Y-m-d', $to_ts), $room_id);
		} else {
			// load festivities when no specific room filter set
			$fests = VikBooking::getFestivitiesInstance();
			if ($fests->shouldCheckFestivities()) {
				$fests->storeNextFestivities();
			}
			$festivities = $fests->loadFestDates(date('Y-m-d', $from_ts), date('Y-m-d', $to_ts));
		}

		// start output buffering
		ob_start();

		// generate calendar
		$d_count = 0;
		$mon_lim = $info_arr['mon'];
		$next_offset = date('Y-m-d', $from_ts);

		?>
		<div class="vbo-widget-booskcal-mday-wrap" style="display: none;">
			<div class="vbo-widget-booskcal-mday-head">
				<a class="vbo-widget-booskcal-mday-back" href="JavaScript: void(0);" onclick="vboWidgetBooksCalMonth('<?php echo $wrapper; ?>');"><?php VikBookingIcons::e('chevron-left'); ?> <?php echo $period_date; ?></a>
				<span class="vbo-widget-booskcal-mday-name"></span>
			</div>
			<div class="vbo-dashboard-guests-latest vbo-widget-booskcal-mday-list" data-ymd="" data-offset="0" data-length="<?php echo $this->bookings_per_page; ?>"></div>
		</div>

		<table class="vbadmincaltable vbo-widget-booskcal-calendar-table">
			<tbody>
				<tr class="vbadmincaltrmdays">
				<?php
				// display week days in the proper order
				for ($i = 0; $i < 7; $i++) {
					$d_ind = ($i + $firstwday) < 7 ? ($i + $firstwday) : ($i + $firstwday - 7);
					?>
					<td class="vbo-widget-booskcal-cell-wday"><?php echo $days_labels[$d_ind]; ?></td>
					<?php
				}
				?>
				</tr>
				<tr>
				<?php
				// display empty cells until the first week-day of the month
				for ($i = 0, $n = $days_indexes[$info_arr['wday']]; $i < $n; $i++, $d_count++) {
					?>
					<td class="vbo-widget-booskcal-cell-mday vbo-widget-booskcal-cell-empty">&nbsp;</td>
					<?php
				}
				// display month days
				while ($info_arr['mon'] == $mon_lim) {
					if ($d_count > 6) {
						$d_count = 0;
						// close current row and open a new one
						echo "\n</tr>\n<tr>\n";
					}
					// count units booked on this day
					$tot_units_booked = 0;
					$cell_classes = [];
					$cell_bids = [];
					foreach ($busy_records as $rid => $rbusy) {
						foreach ($rbusy as $b) {
							$tmpone = getdate($b['checkin']);
							$ritts = mktime(0, 0, 0, $tmpone['mon'], $tmpone['mday'], $tmpone['year']);
							$tmptwo = getdate($b['checkout']);
							$conts = mktime(0, 0, 0, $tmptwo['mon'], $tmptwo['mday'], $tmptwo['year']);
							if ($info_arr[0] >= $ritts && $info_arr[0] < $conts) {
								// increase units booked
								$tot_units_booked++;
								if ($tot_rooms === 1) {
									if (!empty($b['closure'])) {
										// hightlight that this was a closure
										$cell_classes[] = 'busy-closure';
									} elseif (!empty($b['sharedcal'])) {
										// hightlight that this was a reflection from a shared calendar
										$cell_classes[] = 'busy-sharedcalendar';
									}
								}
								// check if we can push the booking ID involved
								if (!empty($b['idorder'])) {
									$cell_bids[] = $b['idorder'];
								}
							}
						}
					}
					// check status for this day
					if ($tot_units_booked > 0) {
						if ($tot_units_booked < $max_units) {
							// prepend the "partially-busy" class
							array_unshift($cell_classes, 'vbo-partially');
						}
						// prepend the "busy" cell class so that this will be first
						array_unshift($cell_classes, 'busy');
					} else {
						// set the "free" cell class
						$cell_classes[] = 'free';
					}
					// set ymd values
					$cell_ymd = date('Y-m-d', $info_arr[0]);
					if ($cell_ymd == $today_ymd) {
						// set the "today" cell class
						$cell_classes[] = 'is-today';
					}
					$cell_day_read = VikBooking::sayWeekDay($info_arr['wday']) . ' ' . $info_arr['mday'];

					// count values for this day
					$has_fests = isset($festivities[$cell_ymd]);
					$rdnotes_key = $cell_ymd . '_' . $room_id . '_0';
					$has_rdnotes = isset($rday_notes[$rdnotes_key]);

					?>
					<td class="vbo-widget-booskcal-cell-mday <?php echo implode(' ', $cell_classes); ?>" onclick="vboWidgetBooksCalMday('<?php echo $wrapper; ?>', this);" data-bids="<?php echo implode(',', array_unique($cell_bids)); ?>" data-ymd="<?php echo $cell_ymd; ?>" data-dayread="<?php echo htmlspecialchars($cell_day_read); ?>">
						<span class="vbo-widget-booskcal-mday-val"><?php echo $info_arr['mday']; ?></span>
					<?php
					if ($has_fests || $has_rdnotes) {
						?>
						<div class="vbo-widget-booskcal-mday-info">
						<?php
						if ($has_fests) {
							?>
							<span class="vbo-widget-booskcal-mday-fests"><?php VikBookingIcons::e('birthday-cake'); ?></span>
							<?php
						}
						if ($has_rdnotes) {
							?>
							<span class="vbo-widget-booskcal-mday-rdnotes"><?php VikBookingIcons::e('sticky-note'); ?></span>
							<?php
						}
						?>
						</div>
						<?php
					}
					?>
					</td>
					<?php
					$dayts = mktime(0, 0, 0, $info_arr['mon'], ($info_arr['mday'] + 1), $info_arr['year']);
					$info_arr = getdate($dayts);
					$d_count++;
				}
				// add empty cells until the end of the row
				for ($i = $d_count; $i <= 6; $i++) {
					?>
					<td class="vbo-widget-booskcal-cell-mday vbo-widget-booskcal-cell-empty">&nbsp;</td>
					<?php
				}
				?>
				</tr>
			</tbody>
		</table>
		<?php

		// get the HTML buffer
		$html_content = ob_get_contents();
		ob_end_clean();

		// return an associative array of values
		return array(
			'html' 		  => $html_content,
			'offset' 	  => $next_offset,
			'search_name' => $search_name,
			'period_date' => $period_date,
		);
	}

	/**
	 * Custom method for this widget only to load the bookings of a month-day.
	 * The method is called by the admin controller through an AJAX request.
	 * The visibility should be public, it should not exit the process, and
	 * any content sent to output will be returned to the AJAX response.
	 * In this case we return an array because this method requires "return":1.
	 */
	public function loadMdayBookings()
	{
		// get today's date
		$today_ymd = date('Y-m-d');

		$page_offset = VikRequest::getInt('page_offset', 0, 'request');
		$page_length = VikRequest::getInt('page_length', $this->bookings_per_page, 'request');
		$ymd = VikRequest::getString('ymd', $today_ymd, 'request');
		$wrapper = VikRequest::getString('wrapper', '', 'request');
		$room_id = VikRequest::getInt('room_id', 0, 'request');

		// calculate date timestamps interval for the given day
		$day_info = getdate(strtotime($ymd));
		$from_ts = mktime(0, 0, 0, $day_info['mon'], $day_info['mday'], $day_info['year']);
		$to_ts = mktime(23, 59, 59, $day_info['mon'], $day_info['mday'], $day_info['year']);

		// load busy records
		$room_filter = !empty($room_id) ? array($room_id) : array();
		$busy_records = VikBooking::loadBusyRecords($room_filter, $from_ts, $to_ts);

		// gather all bookings touching this day
		$booking_ids = array();
		foreach ($busy_records as $rid => $rbusy) {
			foreach ($rbusy as $b) {
				$tmpone = getdate($b['checkin']);
				$ritts = mktime(0, 0, 0, $tmpone['mon'], $tmpone['mday'], $tmpone['year']);
				$tmptwo = getdate($b['checkout']);
				$conts = mktime(0, 0, 0, $tmptwo['mon'], $tmptwo['mday'], $tmptwo['year']);
				if ($from_ts >= $ritts && $from_ts < $conts) {
					if (empty($b['idorder']) || in_array($b['idorder'], $booking_ids)) {
						continue;
					}
					array_push($booking_ids, $b['idorder']);
				}
			}
		}

		// invoke availability helper class
		$av_helper = VikBooking::getAvailabilityInstance();

		// collect booking information
		$booking_details = array();
		foreach ($booking_ids as $bid) {
			$booking = $av_helper->getBookingDetails($bid);
			if (!is_array($booking) || !count($booking)) {
				continue;
			}
			$booking_details[$bid] = $booking;
		}

		// check if a next page can be available
		$tot_bookings  = count($booking_details);
		$has_next_page = ($tot_bookings > ($page_length + $page_offset));

		// slice the records, if needed
		if ($tot_bookings > $page_length) {
			$booking_details = array_slice($booking_details, $page_offset, $page_length, true);
		}

		// load festivities or room-day notes
		$festivities = array();
		$rday_notes  = array();
		if (!empty($room_id)) {
			// load room-day notes for the given room id
			$rday_notes = VikBooking::getCriticalDatesInstance()->loadRoomDayNotes(date('Y-m-d', $from_ts), date('Y-m-d', $to_ts), $room_id);
		} else {
			// load festivities when no specific room filter set
			$festivities = VikBooking::getFestivitiesInstance()->loadFestDates(date('Y-m-d', $from_ts), date('Y-m-d', $to_ts));
		}

		// start output buffering
		ob_start();

		if (count($festivities)) {
			// display the festivities for this day
			?>
			<div class="vbo-widget-booskcal-events vbo-widget-booskcal-fests">
			<?php
			foreach ($festivities as $fest_ymd => $fest) {
				if (empty($fest['festinfo']) || !is_array($fest['festinfo'])) {
					continue;
				}
				foreach ($fest['festinfo'] as $fest_info) {
					if (!is_object($fest_info) || empty($fest_info->trans_name)) {
						continue;
					}
					?>
				<div class="vbo-widget-booskcal-event vbo-widget-booskcal-fest">
					<strong><?php echo $fest_info->trans_name; ?></strong>
					<?php
					if (!empty($fest_info->descr)) {
						?>
					<div><?php echo nl2br($fest_info->descr); ?></div>
						<?php
					}
					?>
				</div>
					<?php
				}
			}
			?>
			</div>
			<?php
		}

		if (count($rday_notes)) {
			// display the room-day notes for this day
			?>
			<div class="vbo-widget-booskcal-events vbo-widget-booskcal-rdaynotes">
			<?php
			foreach ($rday_notes as $rday_note) {
				if (empty($rday_note['info']) || !is_array($rday_note['info'])) {
					continue;
				}
				foreach ($rday_note['info'] as $note_info) {
					if (!is_object($note_info) || empty($note_info->name)) {
						continue;
					}
					?>
				<div class="vbo-widget-booskcal-event vbo-widget-booskcal-rdaynote">
					<strong><?php echo $note_info->name; ?></strong>
					<?php
					if (!empty($note_info->descr)) {
						?>
					<div><?php echo nl2br($note_info->descr); ?></div>
						<?php
					}
					?>
				</div>
					<?php
				}
			}
			?>
			</div>
			<?php
		}

		if (!count($booking_details)) {
			?>
			<p class="info"><?php echo JText::translate('VBNOORDERSFOUND'); ?></p>
			<?php
		} else {
			// display all bookings of this day
			foreach ($booking_details as $booking) {
				// get channel logo and other details
				$ch_logo_obj  = VikBooking::getVcmChannelsLogo($booking['channel'], true);
				$channel_logo = is_object($ch_logo_obj) ? $ch_logo_obj->getSmallLogoURL() : '';
				$nights_lbl = $booking['days'] > 1 ? JText::translate('VBDAYS') : JText::translate('VBDAY');
				// compose customer name
				$customer_name = !empty($booking['customer_fullname']) ? $booking['customer_fullname'] : '';
				if ($booking['closure'] > 0 || !strcasecmp($booking['custdata'], JText::translate('VBDBTEXTROOMCLOSED'))) {
					$customer_name = '<span class="vbordersroomclosed"><i class="' . VikBookingIcons::i('ban') . '"></i> ' . JText::translate('VBDBTEXTROOMCLOSED') . '</span>';
				}
				if (empty($customer_name)) {
					$customer_name = VikBooking::getFirstCustDataField($booking['custdata']);
				}
				?>
				<div class="vbo-dashboard-guest-activity vbo-widget-booskcal-reservation" onclick="vboWidgetBooksCalOpenBooking('<?php echo $booking['id']; ?>');">
					<div class="vbo-dashboard-guest-activity-avatar">
					<?php
					if (!empty($channel_logo)) {
						// channel logo has got the highest priority
						?>
						<img class="vbo-dashboard-guest-activity-avatar-profile" src="<?php echo $channel_logo; ?>" />
						<?php
					} else {
						// we use an icon as fallback
						VikBookingIcons::e('hotel', 'vbo-dashboard-guest-activity-avatar-icon');
					}
					?>
					</div>
					<div class="vbo-dashboard-guest-activity-content">
						<div class="vbo-dashboard-guest-activity-content-head">
							<div class="vbo-dashboard-guest-activity-content-info-details">
								<h4><?php echo $customer_name; ?></h4>
								<div class="vbo-dashboard-guest-activity-content-info-icon">
									<span><?php VikBookingIcons::e('plane-arrival'); ?> <?php echo date(str_replace("/", $this->datesep, $this->df), $booking['checkin']); ?> - <?php echo $booking['days'] . ' ' . $nights_lbl; ?></span>
								</div>
							</div>
							<div class="vbo-dashboard-guest-activity-content-info-date">
								<span>
									<span class="label label-info"><?php echo $booking['id']; ?></span>
								</span>
								<span><?php echo date(str_replace("/", $this->datesep, $this->df) . ' H:i', $booking['ts']); ?></span>
							</div>
						</div>
					</div>
				</div>
				<?php
			}

			// append navigation
			?>
				<div class="vbo-widget-commands vbo-widget-commands-right">
					<div class="vbo-widget-commands-main">
					<?php
					if ($page_offset > 0) {
						// show backward navigation button
						?>
						<div class="vbo-widget-command-chevron vbo-widget-command-prev">
							<span class="vbo-widget-command-chevron-prev" onclick="vboWidgetBooksCalMdayNavigate('<?php echo $wrapper; ?>', -1);"><?php VikBookingIcons::e('chevron-left'); ?></span>
						</div>
						<?php
					}
					if ($has_next_page) {
						// show forward navigation button
						?>
						<div class="vbo-widget-command-chevron vbo-widget-command-next">
							<span class="vbo-widget-command-chevron-next" onclick="vboWidgetBooksCalMdayNavigate('<?php echo $wrapper; ?>', 1);"><?php VikBookingIcons::e('chevron-right'); ?></span>
						</div>
					<?php
					}
					?>
					</div>
				</div>
			<?php
		}

		// get the HTML buffer
		$html_content = ob_get_contents();
		ob_end_clean();

		// return an associative array of values
		return array(
			'html' 		   => $html_content,
			'tot_bookings' => $tot_bookings,
			'next_page'    => (int)$has_next_page,
		);
	}

	/**
	 * Main method to invoke the widget. Contents will be loaded
	 * through AJAX requests, not via PHP when the page loads.
	 * 
	 * @param 	VBOMultitaskData 	$data
	 * 
	 * @return 	void
	 */
	public function render(VBOMultitaskData $data = null)
	{
		// increase widget's instance counter
		static::$instance_counter++;

		// check whether the widget is being rendered via AJAX when adding it through the customizer
		$is_ajax = $this->isAjaxRendering();

		// generate a unique ID for the sticky notes wrapper instance
		$wrapper_instance = !$is_ajax ? static::$instance_counter : rand();
		$wrapper_id = 'vbo-widget-booskcal-' . $wrapper_instance;

		// get permissions
		$vbo_auth_bookings = JFactory::getUser()->authorise('core.vbo.bookings', 'com_vikbooking');
		if (!$vbo_auth_bookings) {
			// display nothing
			return;
		}

		// invoke availability helper class
		$av_helper = VikBooking::getAvailabilityInstance();

		// get all rooms
		$all_rooms = $av_helper->loadRooms();

		/**
		 * This widget can make use of the Select2 jQuery plugin, but we do not preload it
		 * in order to save resources. We only load this JS asset when the widget is saved.
		 */
		$use_nice_select = false;
		if (!$is_ajax && count($all_rooms) > 1) {
			// load assets (with no "preloading")
			$this->vbo_app->loadSelect2();
			// turn flag on
			$use_nice_select = true;
		}

		// default dates and values
		$now_info = getdate();
		$from_ts = mktime(0, 0, 0, $now_info['mon'], 1, $now_info['year']);
		$to_ts = mktime(23, 59, 59, $now_info['mon'], date('t', $now_info[0]), $now_info['year']);
		$search_name = preg_replace("/[^A-Za-z0-9 ]/", '', JText::translate('VBOSTATSALLROOMS'));
		$period_date = VikBooking::sayMonth($now_info['mon']) . ' ' . $now_info['year'];

		// build week days list according to settings
		$firstwday = (int)VikBooking::getFirstWeekDay(true);
		$days_labels = array(
			JText::translate('VBSUN'),
			JText::translate('VBMON'),
			JText::translate('VBTUE'),
			JText::translate('VBWED'),
			JText::translate('VBTHU'),
			JText::translate('VBFRI'),
			JText::translate('VBSAT'),
		);
		$days_indexes = array();
		for ($i = 0; $i < 7; $i++) {
			$days_indexes[$i] = (6 - ($firstwday - $i) + 1) % 7;
		}

		// start looping from the first day of the current month
		$info_arr = getdate($from_ts);

		// week days counter
		$d_count = 0;
		$mon_lim = $info_arr['mon'];

		// check multitask data
		$page_bid = 0;
		if ($data !== null) {
			$page_bid = $data->getBookingID();
		}

		?>
		<div id="<?php echo $wrapper_id; ?>" class="vbo-admin-widget-wrapper" data-instance="<?php echo $wrapper_instance; ?>" data-pagebid="<?php echo $page_bid; ?>" data-offset="<?php echo date('Y-m-d', $from_ts); ?>">
			<div class="vbo-admin-widget-head">
				<div class="vbo-admin-widget-head-inline">
					<h4><?php VikBookingIcons::e('calendar'); ?> <?php echo JText::translate('VBO_W_BOOKSCAL_TITLE'); ?></h4>
					<div class="vbo-admin-widget-head-commands">

						<div class="vbo-reportwidget-commands">
							<div class="vbo-reportwidget-commands-main">
								<div class="vbo-reportwidget-command-dates">
									<div class="vbo-reportwidget-period-name"><?php echo $search_name; ?></div>
									<div class="vbo-reportwidget-period-date"><?php echo $period_date; ?></div>
								</div>
								<div class="vbo-reportwidget-command-chevron vbo-reportwidget-command-prev">
									<span class="vbo-widget-booskcal-dt-prev" onclick="vboWidgetBookCalsMonthNav('<?php echo $wrapper_id; ?>', -1);"><?php VikBookingIcons::e('chevron-left'); ?></span>
								</div>
								<div class="vbo-reportwidget-command-chevron vbo-reportwidget-command-next">
									<span class="vbo-widget-booskcal-dt-next" onclick="vboWidgetBookCalsMonthNav('<?php echo $wrapper_id; ?>', 1);"><?php VikBookingIcons::e('chevron-right'); ?></span>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
			<div class="vbo-widget-booskcal-wrap">
				<div class="vbo-widget-booskcal-inner">

					<div class="vbo-widget-booskcal-filter">
						<select class="vbo-booskcal-roomid" onchange="vboWidgetBookCalsSetRoom('<?php echo $wrapper_id; ?>', this.value);">
							<option></option>
						<?php
						foreach ($all_rooms as $rid => $room) {
							?>
							<option value="<?php echo $rid; ?>"><?php echo $room['name']; ?></option>
							<?php
						}
						?>
						</select>
					</div>

					<div class="vbo-widget-booskcal-calendar">

						<table class="vbadmincaltable vbo-widget-booskcal-calendar-table">
							<tbody>
								<tr class="vbadmincaltrmdays">
								<?php
								// display week days in the proper order
								for ($i = 0; $i < 7; $i++) {
									$d_ind = ($i + $firstwday) < 7 ? ($i + $firstwday) : ($i + $firstwday - 7);
									?>
									<td class="vbo-widget-booskcal-cell-wday"><?php echo $days_labels[$d_ind]; ?></td>
									<?php
								}
								?>
								</tr>
								<tr>
								<?php
								// display empty cells until the first week-day of the month
								for ($i = 0, $n = $days_indexes[$info_arr['wday']]; $i < $n; $i++, $d_count++) {
									?>
									<td class="vbo-widget-booskcal-cell-mday vbo-widget-booskcal-cell-empty">&nbsp;</td>
									<?php
								}
								// display month days
								while ($info_arr['mon'] == $mon_lim) {
									if ($d_count > 6) {
										$d_count = 0;
										// close current row and open a new one
										echo "\n</tr>\n<tr>\n";
									}
									?>
									<td class="vbo-widget-booskcal-cell-mday">
										<span class="vbo-widget-booskcal-mday-val"><?php echo $info_arr['mday']; ?></span>
									</td>
									<?php
									$dayts = mktime(0, 0, 0, $info_arr['mon'], ($info_arr['mday'] + 1), $info_arr['year']);
									$info_arr = getdate($dayts);
									$d_count++;
								}
								// add empty cells until the end of the row
								for ($i = $d_count; $i <= 6; $i++) {
									?>
									<td class="vbo-widget-booskcal-cell-mday vbo-widget-booskcal-cell-empty">&nbsp;</td>
									<?php
								}
								?>
								</tr>
							</tbody>
						</table>

					</div>

				</div>
			</div>
		</div>
		<?php

		if (static::$instance_counter === 0 || $is_ajax) {
			/**
			 * Print the JS code only once for all instances of this widget.
			 * The real rendering is made through AJAX, not when the page loads.
			 */
			?>
		<a class="vbo-widget-bookscal-basenavuri" href="index.php?option=com_vikbooking&task=editorder&cid[]=%d" style="display: none;"></a>

		<script type="text/javascript">

			/**
			 * Open the booking details page for the clicked reservation.
			 */
			function vboWidgetBooksCalOpenBooking(id) {
				var open_url = jQuery('.vbo-widget-bookscal-basenavuri').first().attr('href');
				open_url = open_url.replace('%d', id);
				// navigate in a new tab
				window.open(open_url, '_blank');
			}

			/**
			 * Display the loading skeletons.
			 */
			function vboWidgetBooksCalSkeletons(wrapper) {
				var widget_instance = jQuery('#' + wrapper);
				if (!widget_instance.length) {
					return false;
				}
				var skelton_html = '<div class="vbo-skeleton-loading vbo-skeleton-loading-mday-cell"></div>';
				widget_instance.find('.vbo-widget-booskcal-calendar').find('.vbo-widget-booskcal-cell-mday').attr('class', 'vbo-widget-booskcal-cell-mday').html(skelton_html);
			}

			/**
			 * Perform the request to load the bookings calendar.
			 */
			function vboWidgetBooksCalLoad(wrapper, dates_direction, page_bid) {
				var widget_instance = jQuery('#' + wrapper);
				if (!widget_instance.length) {
					return false;
				}

				// check if a navigation of dates was requested (0 = no dates nav)
				if (typeof dates_direction === 'undefined') {
					dates_direction = 0;
				}

				// check if multitask data passed a booking ID for the current page
				var force_bid = 0;
				if (typeof page_bid !== 'undefined' && !isNaN(page_bid)) {
					force_bid = page_bid;
				}

				// get vars for making the request
				var current_offset = widget_instance.attr('data-offset');
				var room_id = widget_instance.find('.vbo-booskcal-roomid').val();

				// the widget method to call
				var call_method = 'loadBookingsCalendar';

				// make a request to load the bookings calendar
				VBOCore.doAjax(
					"<?php echo $this->getExecWidgetAjaxUri(); ?>",
					{
						widget_id: "<?php echo $this->getIdentifier(); ?>",
						call: call_method,
						return: 1,
						bid: force_bid,
						offset: current_offset,
						room_id: room_id,
						date_dir: dates_direction,
						wrapper: wrapper,
						tmpl: "component"
					},
					function(response) {
						try {
							var obj_res = typeof response === 'string' ? JSON.parse(response) : response;
							if (!obj_res.hasOwnProperty(call_method)) {
								console.error('Unexpected JSON response', obj_res);
								return false;
							}

							// set new offset for navigation
							widget_instance.attr('data-offset', obj_res[call_method]['offset']);

							// update search name and month
							widget_instance.find('.vbo-reportwidget-period-name').text(obj_res[call_method]['search_name']);
							widget_instance.find('.vbo-reportwidget-period-date').text(obj_res[call_method]['period_date']);

							// replace HTML with new bookings calendar
							widget_instance.find('.vbo-widget-booskcal-calendar').html(obj_res[call_method]['html']);
						} catch(err) {
							console.error('could not parse JSON response', err, response);
						}
					},
					function(error) {
						// remove the skeleton loading
						widget_instance.find('.vbo-widget-booskcal-calendar').find('.vbo-skeleton-loading').remove();
						console.error(error);
					}
				);
			}

			/**
			 * Perform the request to load the month-day reservations.
			 */
			function vboWidgetBooksCalGetMdayRes(wrapper, ymd) {
				var widget_instance = jQuery('#' + wrapper);
				if (!widget_instance.length || !ymd) {
					return false;
				}

				// get vars for making the request
				var room_id = widget_instance.find('.vbo-booskcal-roomid').val();
				var page_offset = widget_instance.find('.vbo-widget-booskcal-mday-list').attr('data-offset');
				var page_length = widget_instance.find('.vbo-widget-booskcal-mday-list').attr('data-length');

				// the widget method to call
				var call_method = 'loadMdayBookings';

				// make a request to load the bookings calendar
				VBOCore.doAjax(
					"<?php echo $this->getExecWidgetAjaxUri(); ?>",
					{
						widget_id: "<?php echo $this->getIdentifier(); ?>",
						call: call_method,
						return: 1,
						page_offset: page_offset,
						page_length: page_length,
						ymd: ymd,
						room_id: room_id,
						wrapper: wrapper,
						tmpl: "component"
					},
					function(response) {
						try {
							var obj_res = typeof response === 'string' ? JSON.parse(response) : response;
							if (!obj_res.hasOwnProperty(call_method)) {
								console.error('Unexpected JSON response', obj_res);
								return false;
							}
							// replace HTML with month-day reservations
							widget_instance.find('.vbo-widget-booskcal-mday-list').html(obj_res[call_method]['html']);
						} catch(err) {
							console.error('could not parse JSON response', err, response);
						}
					},
					function(error) {
						// remove the skeleton loading
						widget_instance.find('.vbo-widget-booskcal-mday-list').html('');
						console.error(error);
					}
				);
			}

			/**
			 * Navigate between the months and load the bookings calendar.
			 */
			function vboWidgetBookCalsMonthNav(wrapper, direction) {
				var widget_instance = jQuery('#' + wrapper);
				if (!widget_instance.length) {
					return false;
				}

				// show loading skeletons
				vboWidgetBooksCalSkeletons(wrapper);

				// launch dates navigation and load records
				vboWidgetBooksCalLoad(wrapper, direction);
			}

			/**
			 * Change room calendar.
			 */
			function vboWidgetBookCalsSetRoom(wrapper, rid) {
				var widget_instance = jQuery('#' + wrapper);
				if (!widget_instance.length) {
					return false;
				}

				// show loading skeletons
				vboWidgetBooksCalSkeletons(wrapper);

				// let the records be loaded for this new room filter
				vboWidgetBooksCalLoad(wrapper, 0);
			}

			/**
			 * Generate the HTML skeleton string to the month-day reservations.
			 */
			function vboWidgetBooksCalMdaySkeleton() {
				var monthday_loading = '';
				monthday_loading += '<div class="vbo-dashboard-guest-activity vbo-dashboard-guest-activity-skeleton">';
				monthday_loading += '	<div class="vbo-dashboard-guest-activity-avatar">';
				monthday_loading += '		<div class="vbo-skeleton-loading vbo-skeleton-loading-avatar"></div>';
				monthday_loading += '	</div>';
				monthday_loading += '	<div class="vbo-dashboard-guest-activity-content">';
				monthday_loading += '		<div class="vbo-dashboard-guest-activity-content-head">';
				monthday_loading += '			<div class="vbo-skeleton-loading vbo-skeleton-loading-title"></div>';
				monthday_loading += '		</div>';
				monthday_loading += '		<div class="vbo-dashboard-guest-activity-content-subhead">';
				monthday_loading += '			<div class="vbo-skeleton-loading vbo-skeleton-loading-subtitle"></div>';
				monthday_loading += '		</div>';
				monthday_loading += '		<div class="vbo-dashboard-guest-activity-content-info-msg">';
				monthday_loading += '			<div class="vbo-skeleton-loading vbo-skeleton-loading-content"></div>';
				monthday_loading += '		</div>';
				monthday_loading += '	</div>';
				monthday_loading += '</div>';

				return monthday_loading;
			}

			/**
			 * Enter the month-day view mode from monthly view.
			 */
			function vboWidgetBooksCalMday(wrapper, element) {
				var widget_instance = jQuery('#' + wrapper);
				if (!widget_instance.length) {
					return false;
				}

				// get cell element
				var cell = jQuery(element);
				if (!cell || !cell.length) {
					return false;
				}

				// set month-day title
				var day_read = cell.attr('data-dayread');
				widget_instance.find('.vbo-widget-booskcal-mday-name').text(day_read);

				// get cell ymd value
				var day_ymd = cell.attr('data-ymd');

				// always update the proper ymd day
				widget_instance.find('.vbo-widget-booskcal-mday-list').attr('data-ymd', day_ymd);

				// get pre-loaded booking ids
				var tot_day_res = 0;
				var day_bids = cell.attr('data-bids');
				if (day_bids && day_bids.length) {
					tot_day_res = day_bids.split(',').length;
				}

				// populate loading skeletons for month-day bookings
				var monthday_loading = vboWidgetBooksCalMdaySkeleton();
				if (tot_day_res > 1) {
					// double up the loading skeletons
					monthday_loading = monthday_loading + monthday_loading;
				}
				widget_instance.find('.vbo-widget-booskcal-mday-list').html(monthday_loading);

				// toggle elements
				widget_instance.find('.vbo-widget-booskcal-calendar-table').hide();
				widget_instance.find('.vbo-widget-booskcal-mday-wrap').show();

				// launch month-day bookings retrieval
				vboWidgetBooksCalGetMdayRes(wrapper, day_ymd);
			}

			/**
			 * Navigate between the various pages of the month-day bookings.
			 */
			function vboWidgetBooksCalMdayNavigate(wrapper, direction) {
				var widget_instance = jQuery('#' + wrapper);
				if (!widget_instance.length) {
					return false;
				}

				// get bookings container
				var bookings_list = widget_instance.find('.vbo-widget-booskcal-mday-list');

				// show loading skeletons
				var monthday_loading = vboWidgetBooksCalMdaySkeleton();
				bookings_list.html(monthday_loading + monthday_loading);

				// get current offset and length (MUST be numbers, not strings)
				var current_offset = parseInt(bookings_list.attr('data-offset'));
				var current_length = parseInt(bookings_list.attr('data-length'));
				var day_ymd 	   = bookings_list.attr('data-ymd');

				// check direction and update offsets for nav
				if (direction > 0) {
					// navigate forward
					bookings_list.attr('data-offset', (current_offset + current_length));
				} else {
					// navigate backward
					var new_offset = current_offset - current_length;
					new_offset = new_offset >= 0 ? new_offset : 0;
					bookings_list.attr('data-offset', new_offset);
				}

				// launch month-day bookings retrieval
				vboWidgetBooksCalGetMdayRes(wrapper, day_ymd);
			}

			/**
			 * Go back to the montly view from the month-day view.
			 */
			function vboWidgetBooksCalMonth(wrapper) {
				var widget_instance = jQuery('#' + wrapper);
				if (!widget_instance.length) {
					return false;
				}

				// toggle elements
				widget_instance.find('.vbo-widget-booskcal-mday-wrap').hide();
				widget_instance.find('.vbo-widget-booskcal-mday-list').html('').attr('data-ymd', '').attr('data-offset', '0');
				widget_instance.find('.vbo-widget-booskcal-calendar-table').show();
			}

			/**
			 * Triggers when the multitask panel opens.
			 */
			function vboWidgetBooksCalMultitaskOpen(wrapper) {
				var widget_instance = jQuery('#' + wrapper);
				if (!widget_instance.length) {
					return false;
				}

				// check if a booking ID was set for this page
				var page_bid = widget_instance.attr('data-pagebid');
				if (!page_bid || page_bid < 1) {
					return false;
				}

				// show loading skeletons
				vboWidgetBooksCalSkeletons(wrapper);

				// load data by injecting the current booking ID
				vboWidgetBooksCalLoad(wrapper, 0, page_bid);
			}
			
		</script>
			<?php
		}
		?>

		<script type="text/javascript">

			jQuery(function() {

				// when document is ready, load bookings calendar for this widget's instance
				vboWidgetBooksCalLoad('<?php echo $wrapper_id; ?>');

				// subscribe to the multitask-panel-open event
				document.addEventListener(VBOCore.multitask_open_event, function() {
					vboWidgetBooksCalMultitaskOpen('<?php echo $wrapper_id; ?>');
				});

			<?php
			if ($use_nice_select) {
				// convert the select to a Select2 element
				?>
				jQuery('#<?php echo $wrapper_id; ?>').find('select.vbo-booskcal-roomid').select2({
					width: "100%",
					placeholder: "<?php echo htmlspecialchars(JText::translate('VBOREPORTSROOMFILT')); ?>",
					allowClear: true
				});
				<?php
			}
			?>

			});

		</script>

		<?php
	}
}
