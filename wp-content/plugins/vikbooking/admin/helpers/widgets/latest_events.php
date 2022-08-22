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
 * Class handler for admin widget "latest events".
 * 
 * @since 	1.15.0 (J) - 1.5.0 (WP)
 */
class VikBookingAdminWidgetLatestEvents extends VikBookingAdminWidget
{
	/**
	 * The instance counter of this widget. Since we do not load individual parameters
	 * for each widget's instance, we use a static counter to determine its settings.
	 *
	 * @var 	int
	 */
	protected static $instance_counter = -1;

	/**
	 * Default number of events per page.
	 * 
	 * @var 	int
	 */
	protected $events_per_page = 6;

	/**
	 * Default number of loading skeletons (should be less than events per page).
	 * 
	 * @var 	int
	 */
	protected $tot_skeletons = 4;

	/**
	 * Class constructor will define the widget name and identifier.
	 */
	public function __construct()
	{
		// call parent constructor
		parent::__construct();

		$this->widgetName = JText::translate('VBO_W_LATESTEVS_TITLE');
		$this->widgetDescr = JText::translate('VBO_W_LATESTEVS_DESCR');
		$this->widgetId = basename(__FILE__, '.php');

		// define widget and icon and style name
		$this->widgetIcon = '<i class="' . VikBookingIcons::i('newspaper') . '"></i>';
		$this->widgetStyleName = 'blue';
	}

	/**
	 * This widget returns the latest history record ID to schedule
	 * periodic watch data in order to be able to trigger notifications.
	 * No CSS/JS assets are needed during preloading.
	 * 
	 * @return 	void|object
	 */
	public function preload()
	{
		// use the history class to get the latest event id
		$history_obj = VikBooking::getBookingHistoryInstance();
		$events = $history_obj->getLatestBookingEvents(0, 1);

		if (count($events)) {
			$watch_data = new stdClass;
			$watch_data->history_id = $events[0]->id;

			return $watch_data;
		}

		return null;
	}

	/**
	 * Checks for new notifications by using the previous preloaded watch-data.
	 * 
	 * @param 	VBONotificationWatchdata 	$watch_data 	the preloaded watch-data object.
	 * 
	 * @return 	array 						data object to watch next and notifications array.
	 * 
	 * @see 	preload()
	 * 
	 * @since 	1.15.0 (J) - 1.5.0 (WP)
	 */
	public function getNotifications(VBONotificationWatchdata $watch_data = null)
	{
		// default empty values
		$watch_next    = null;
		$notifications = null;

		if (!$watch_data) {
			return [$watch_next, $notifications];
		}

		$latest_history_id = (int)$watch_data->get('history_id', 0);
		if (empty($latest_history_id)) {
			return [$watch_next, $notifications];
		}

		// load the latest worthy-of-notification history events
		$history_obj = VikBooking::getBookingHistoryInstance();
		$events = $history_obj->getWorthyEvents($latest_history_id, 10);
		if (!count($events)) {
			return [$watch_next, $notifications];
		}

		// first off, build the next watch data for this widget
		$watch_next = new stdClass;
		$watch_next->history_id = $events[(count($events) - 1)]->id;

		// compose the notification(s) to dispatch
		$notifications = VBONotificationScheduler::getInstance()->buildHistoryDataObjects($events);

		return [$watch_next, $notifications];
	}

	/**
	 * Custom method for this widget only to load the history events.
	 * The method is called by the admin controller through an AJAX request.
	 * The visibility should be public, it should not exit the process, and
	 * any content sent to output will be returned to the AJAX response.
	 * In this case we return an array because this method requires "return":1.
	 * 
	 * It's the actual rendering of the widget which also allows navigation.
	 * 
	 * @return 	mixed 	boolean false in case of error, or associative array.
	 */
	public function loadHistoryEvents()
	{
		$offset = VikRequest::getInt('offset', 0, 'request');
		$length = VikRequest::getInt('length', $this->events_per_page, 'request');
		$wrapper = VikRequest::getString('wrapper', '', 'request');

		// use the history class to get the events
		$history_obj = VikBooking::getBookingHistoryInstance();
		$events = $history_obj->getLatestBookingEvents($offset, $length);

		// check if a next page can be available
		$has_next_page = (count($events) >= $length);

		// start output buffering
		ob_start();

		// loop through all history events
		$now = time();
		$today = date('Y-m-d');
		foreach ($events as $history) {
			if ($history->status == 'confirmed') {
				$ord_status = '<span class="label label-success vbo-status-label">'.JText::translate('VBCONFIRMED').'</span>';
			} elseif ($history->status == 'standby') {
				$ord_status = '<span class="label label-warning vbo-status-label">'.JText::translate('VBSTANDBY').'</span>';
			} else {
				$ord_status = '<span class="label label-error vbo-status-label" style="background-color: #d9534f;">'.JText::translate('VBCANCELLED').'</span>';
			}
			$nominative = strlen($history->nominative) > 1 ? $history->nominative : VikBooking::getFirstCustDataField($history->custdata);
			$ch_logo_obj = VikBooking::getVcmChannelsLogo($history->channel, true);
			$channel_logo = is_object($ch_logo_obj) ? $ch_logo_obj->getSmallLogoURL() : '';
			$nights_lbl = $history->days > 1 ? JText::translate('VBDAYS') : JText::translate('VBDAY');
			$dt_obj = new JDate($history->dt);
			// calculate check-in date information
			$checkin_info = getdate($history->checkin);
			if (date('Y-m-d', $history->checkin) == $today) {
				// check-in today
				$checkind_lbl = JText::translate('VBTODAY');
				$checkind_icn = '<i class="' . VikBookingIcons::i('plane-arrival') . '"></i>';
			} elseif ($history->checkin > $now) {
				// check-in in the future
				$checkind_lbl = VikBooking::sayWeekDay($checkin_info['wday'], true) . ', ' . date(str_replace("/", $this->datesep, $this->df), $history->checkin);
				$checkind_icn = '<i class="' . VikBookingIcons::i('sort-up') . '"></i> ';
			} else {
				// check-in in the past
				$checkind_lbl = VikBooking::sayWeekDay($checkin_info['wday'], true) . ', ' . date(str_replace("/", $this->datesep, $this->df), $history->checkin);
				$checkind_icn = '<i class="' . VikBookingIcons::i('sort-down') . '"></i> ';
			}
			?>
			<div class="vbo-widget-history-record" data-recordid="<?php echo $history->id; ?>" onclick="vboWidgetLatestEventsOpenBooking('<?php echo $history->idorder; ?>');">
				<div class="vbo-widget-history-avatar">
				<?php
				if (!empty($channel_logo)) {
					// channel logo has got the highest priority
					?>
					<img class="vbo-widget-history-avatar-profile" src="<?php echo $channel_logo; ?>" />
					<?php
				} else {
					// we use an icon as fallback
					VikBookingIcons::e('hotel', 'vbo-widget-history-avatar-icon');
				}
				?>
				</div>
				<div class="vbo-widget-history-content">
					<div class="vbo-widget-history-content-head">
						<div class="vbo-widget-history-content-info-details">
							<?php echo $ord_status; ?>
							<h4><?php echo $nominative; ?></h4>
							<div class="vbo-widget-history-content-info-dates">
								<span class="vbo-widget-history-date"><?php echo $dt_obj->format('D, d M Y H:i', true); ?></span>
							</div>
						</div>
						<div class="vbo-widget-history-content-info-booking">
							<div class="vbo-widget-history-booking-id">
								<span class="label label-info"><?php echo $history->idorder; ?></span>
							</div>
							<div class="vbo-widget-history-booking-checkin">
								<span><?php echo $checkind_icn . $checkind_lbl; ?></span>
								<span><?php echo ' - ' . $history->days . ' ' . $nights_lbl; ?></span>
							</div>
						</div>
					</div>
					<div class="vbo-widget-history-content-info-msg">
						<span class="vbo-widget-history-content-info-msg-descr"><?php echo $history_obj->validType($history->type, true); ?></span>
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
			if ($offset > 0) {
				// show backward navigation button
				?>
				<div class="vbo-widget-command-chevron vbo-widget-command-prev">
					<span class="vbo-widget-command-chevron-prev" onclick="vboWidgetLatestEventsNavigate('<?php echo $wrapper; ?>', -1);"><?php VikBookingIcons::e('chevron-left'); ?></span>
				</div>
				<?php
			}
			if ($has_next_page) {
				// show forward navigation button
				?>
				<div class="vbo-widget-command-chevron vbo-widget-command-next">
					<span class="vbo-widget-command-chevron-next" onclick="vboWidgetLatestEventsNavigate('<?php echo $wrapper; ?>', 1);"><?php VikBookingIcons::e('chevron-right'); ?></span>
				</div>
			<?php
			}
			?>
			</div>
		</div>
		<?php

		// get the HTML buffer
		$html_content = ob_get_contents();
		ob_end_clean();

		// return an associative array of values
		return array(
			'html' 		  => $html_content,
			'tot_records' => count($events),
			'next_page'   => (int)$has_next_page,
		);
	}

	public function render(VBOMultitaskData $data = null)
	{
		// increase widget's instance counter
		static::$instance_counter++;

		// check whether the widget is being rendered via AJAX when adding it through the customizer
		$is_ajax = $this->isAjaxRendering();

		// generate a unique ID for the sticky notes wrapper instance
		$wrapper_instance = !$is_ajax ? static::$instance_counter : rand();
		$wrapper_id = 'vbo-widget-latestevents-' . $wrapper_instance;

		// get permissions
		$vbo_auth_bookings = JFactory::getUser()->authorise('core.vbo.bookings', 'com_vikbooking');

		?>
		<div class="vbo-admin-widget-wrapper">
			<div class="vbo-admin-widget-head">
				<h4><?php echo $this->widgetIcon; ?> <?php echo JText::translate('VBO_W_LATESTEVS_TITLE'); ?></h4>
			</div>
			<div id="<?php echo $wrapper_id; ?>" class="vbo-widget-latestevents-wrap" data-instance="<?php echo $wrapper_instance; ?>" data-offset="0" data-length="<?php echo $this->events_per_page; ?>">
				<div class="vbo-widget-latestevents-inner">
					<div class="vbo-widget-latestevents-list">
					<?php
					for ($i = 0; $i < $this->tot_skeletons; $i++) {
						?>
						<div class="vbo-dashboard-guest-activity vbo-dashboard-guest-activity-skeleton">
							<div class="vbo-dashboard-guest-activity-avatar">
								<div class="vbo-skeleton-loading vbo-skeleton-loading-avatar"></div>
							</div>
							<div class="vbo-dashboard-guest-activity-content">
								<div class="vbo-dashboard-guest-activity-content-head">
									<div class="vbo-skeleton-loading vbo-skeleton-loading-title"></div>
								</div>
								<div class="vbo-dashboard-guest-activity-content-info-msg">
									<div class="vbo-skeleton-loading vbo-skeleton-loading-content"></div>
								</div>
							</div>
						</div>
						<?php
					}
					?>
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
		<a class="vbo-widget-latest-events-basenavuri" href="index.php?option=com_vikbooking&task=editorder&cid[]=%d#bookhistory" style="display: none;"></a>

		<script type="text/javascript">

			/**
			 * Open the booking details page for the clicked event
			 */
			function vboWidgetLatestEventsOpenBooking(id) {
				var has_perms = <?php echo (int)$vbo_auth_bookings; ?>;
				if (!has_perms) {
					return false;
				}
				var open_url = jQuery('.vbo-widget-latest-events-basenavuri').first().attr('href');
				open_url = open_url.replace('%d', id);
				// navigate
				document.location.href = open_url;
			}

			/**
			 * Display the loading skeletons.
			 */
			function vboWidgetLatestEventsSkeletons(wrapper) {
				var widget_instance = jQuery('#' + wrapper);
				if (!widget_instance.length) {
					return false;
				}
				widget_instance.find('.vbo-widget-latestevents-list').html('');
				for (var i = 0; i < <?php echo $this->tot_skeletons; ?>; i++) {
					var skeleton = '';
					skeleton += '<div class="vbo-dashboard-guest-activity vbo-dashboard-guest-activity-skeleton">';
					skeleton += '	<div class="vbo-dashboard-guest-activity-avatar">';
					skeleton += '		<div class="vbo-skeleton-loading vbo-skeleton-loading-avatar"></div>';
					skeleton += '	</div>';
					skeleton += '	<div class="vbo-dashboard-guest-activity-content">';
					skeleton += '		<div class="vbo-dashboard-guest-activity-content-head">';
					skeleton += '			<div class="vbo-skeleton-loading vbo-skeleton-loading-title"></div>';
					skeleton += '		</div>';
					skeleton += '		<div class="vbo-dashboard-guest-activity-content-info-msg">';
					skeleton += '			<div class="vbo-skeleton-loading vbo-skeleton-loading-content"></div>';
					skeleton += '		</div>';
					skeleton += '	</div>';
					skeleton += '</div>';
					// append skeleton
					jQuery(skeleton).appendTo(widget_instance.find('.vbo-widget-latestevents-list'));
				}
			}

			/**
			 * Perform the request to load the history events.
			 */
			function vboWidgetLatestEventsLoad(wrapper) {
				var widget_instance = jQuery('#' + wrapper);
				if (!widget_instance.length) {
					return false;
				}

				// get vars for making the request
				var current_offset  = parseInt(widget_instance.attr('data-offset'));
				var length_per_page = parseInt(widget_instance.attr('data-length'));

				// the widget method to call
				var call_method = 'loadHistoryEvents';

				// make a request to load the history events
				VBOCore.doAjax(
					"<?php echo $this->getExecWidgetAjaxUri(); ?>",
					{
						widget_id: "<?php echo $this->getIdentifier(); ?>",
						call: call_method,
						return: 1,
						offset: current_offset,
						length: length_per_page,
						wrapper: wrapper,
						tmpl: "component"
					},
					function(response) {
						try {
							var obj_res = JSON.parse(response);
							if (!obj_res.hasOwnProperty(call_method) || !obj_res[call_method]) {
								console.error('Unexpected JSON response', obj_res);
								return false;
							}

							// replace HTML with new history events
							widget_instance.find('.vbo-widget-latestevents-list').html(obj_res[call_method]['html']);
							
							// check results
							var tot_records = obj_res[call_method]['tot_records'] || 0;
							if (!isNaN(tot_records) && parseInt(tot_records) < 1) {
								// no results can indicate the offset is invalid or too high
								if (!isNaN(current_offset) && parseInt(current_offset) > 0) {
									// reset offset to 0
									widget_instance.attr('data-offset', 0);
									// show loading skeletons
									vboWidgetLatestEventsSkeletons(wrapper);
									// reload the first page
									vboWidgetLatestEventsLoad(wrapper);
								}
							}
						} catch(err) {
							console.error('could not parse JSON response', err, response);
						}
					},
					function(error) {
						// remove the skeleton loading
						widget_instance.find('.vbo-widget-latestevents-list').find('.vbo-dashboard-guest-activity-skeleton').remove();
						console.error(error);
					}
				);
			}

			/**
			 * Navigate between the various pages of the history events.
			 */
			function vboWidgetLatestEventsNavigate(wrapper, direction) {
				var widget_instance = jQuery('#' + wrapper);
				if (!widget_instance.length) {
					return false;
				}

				// show loading skeletons
				vboWidgetLatestEventsSkeletons(wrapper);

				var instance_val = widget_instance.attr('data-instance');

				// current offset
				var current_offset = parseInt(widget_instance.attr('data-offset'));

				// events per page per type
				var steps = <?php echo $this->events_per_page; ?>;

				// check direction and update offsets for nav
				if (direction > 0) {
					// navigate forward
					widget_instance.attr('data-offset', (current_offset + steps));
				} else {
					// navigate backward
					var new_offset = current_offset - steps;
					new_offset = new_offset >= 0 ? new_offset : 0;
					widget_instance.attr('data-offset', new_offset);
				}

				// launch navigation
				vboWidgetLatestEventsLoad(wrapper);
			}
			
		</script>
			<?php
		}
		?>

		<script type="text/javascript">

			jQuery(document).ready(function() {

				// when document is ready, load history events for this widget's instance
				vboWidgetLatestEventsLoad('<?php echo $wrapper_id; ?>');

			});
			
		</script>

		<?php
	}
}
