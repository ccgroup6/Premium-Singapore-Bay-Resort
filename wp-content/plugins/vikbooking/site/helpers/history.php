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
 * Handles all the events involving a reservation.
 */
class VboBookingHistory
{
	/**
	 * @var  int
	 */
	protected $bid = null;

	/**
	 * @var  array
	 */
	protected $prevBooking = null;

	/**
	 * @var  mixed
	 */
	protected $data = null;

	/**
	 * @var  object
	 */
	protected $dbo = null;

	/**
	 * @var  array
	 */
	protected $typesMap = [];

	/**
	 * List of event types worthy of a notification.
	 * 
	 * @var  	array
	 * 
	 * @since 	1.15.0 (J) - 1.5.0 (WP)
	 */
	protected $worthy_events = [
		'NC',
		'MW',
		'NP',
		'P0',
		'PN',
		'CR',
		'CW',
		'MC',
		'CC',
		'NO',
		'PO',
		'IR',
	];

	/**
	 * Class constructor.
	 */
	public function __construct()
	{
		$this->dbo = JFactory::getDbo();
		$this->typesMap = $this->getTypesMap();
	}

	/**
	 * Returns an array of types mapped to
	 * the corresponding language definition.
	 * All the history types should be listed here.
	 *
	 * @return 	array
	 */
	public function getTypesMap()
	{
		return [
			//New booking with status Confirmed
			'NC' => JText::translate('VBOBOOKHISTORYTNC'),
			//Booking modified from website
			'MW' => JText::translate('VBOBOOKHISTORYTMW'),
			//Booking modified from back-end
			'MB' => JText::translate('VBOBOOKHISTORYTMB'),
			//New booking from back-end
			'NB' => JText::translate('VBOBOOKHISTORYTNB'),
			//New booking with status Pending
			'NP' => JText::translate('VBOBOOKHISTORYTNP'),
			//Booking paid for the first time
			'P0' => JText::translate('VBOBOOKHISTORYTP0'),
			//Booking paid for a second time
			'PN' => JText::translate('VBOBOOKHISTORYTPN'),
			//Cancellation request message
			'CR' => JText::translate('VBOBOOKHISTORYTCR'),
			//Booking cancelled via front-end website
			'CW' => JText::translate('VBOBOOKHISTORYTCW'),
			//Booking auto cancelled via front-end
			'CA' => JText::translate('VBOBOOKHISTORYTCA'),
			//Booking cancelled via back-end by admin
			'CB' => JText::translate('VBOBOOKHISTORYTCB'),
			//Booking Receipt generated via back-end
			'BR' => JText::translate('VBOBOOKHISTORYTBR'),
			//Booking Invoice generated
			'BI' => JText::translate('VBOBOOKHISTORYTBI'),
			//Booking registration unset status by admin
			'RA' => JText::translate('VBOBOOKHISTORYTRA'),
			//Booking checked-in status set by admin
			'RB' => JText::translate('VBOBOOKHISTORYTRB'),
			//Booking checked-out status set by admin
			'RC' => JText::translate('VBOBOOKHISTORYTRC'),
			//Booking no-show status set by admin
			'RZ' => JText::translate('VBOBOOKHISTORYTRZ'),
			//Booking set to Confirmed by admin
			'TC' => JText::translate('VBOBOOKHISTORYTTC'),
			//Booking set to Confirmed via App
			'AC' => JText::translate('VBOBOOKHISTORYTAC'),
			//Booking modified from channel
			'MC' => JText::translate('VBOBOOKHISTORYTMC'),
			//Booking cancelled from channel
			'CC' => JText::translate('VBOBOOKHISTORYTCC'),
			//Booking removed via App
			'AR' => JText::translate('VBOBOOKHISTORYTAR'),
			//Booking modified via App
			'AM' => JText::translate('VBOBOOKHISTORYTAM'),
			//New booking via App
			'AN' => JText::translate('VBOBOOKHISTORYTAN'),
			//New Booking from OTA
			'NO' => JText::translate('VBOBOOKHISTORYTNO'),
			//Report affecting the booking
			'RP' => JText::translate('VBOBOOKHISTORYTRP'),
			//Custom email sent to the customer by admin
			'CE' => JText::translate('VBOBOOKHISTORYTCE'),
			//Custom SMS sent to the customer by admin
			'CS' => JText::translate('VBOBOOKHISTORYTCS'),
			//Payment Update (a new amount paid has been set)
			'PU' => JText::translate('VBOBOOKHISTORYTPU'),
			// Upsell Extras via front-end
			'UE' => JText::translate('VBOBOOKHISTORYTUE'),
			// Report guest misconduct to OTA
			'GM' => JText::translate('VBOBOOKHISTORYTGM'),
			// Send Email Cancellation by admin
			'EC' => JText::translate('VBOBOOKHISTORYTEC'),
			// Amount refunded
			'RF' => JText::translate('VBOBOOKHISTORYTRF'),
			// Refund Updated
			'RU' => JText::translate('VBOBOOKHISTORYTRU'),
			// Payable Amount Updated
			'PB' => JText::translate('VBOBOOKHISTORYTPB'),
			// Channel Manager custom event
			'CM' => JText::translate('VBOBOOKHISTORYTCM'),
			// Channel Manager payout notification
			'PO' => JText::translate('VBOBOOKHISTORYTPO'),
			// Inquiry reservation (website)
			'IR' => JText::translate('VBOBOOKHISTORYTIR'),
		];
	}

	/**
	 * Sets the current booking ID.
	 * 
	 * @param 	int 	$bid
	 *
	 * @return 	self
	 **/
	public function setBid($bid)
	{
		$this->bid = (int)$bid;

		return $this;
	}

	/**
	 * Sets the previous booking array.
	 * To calculate what has changed in the booking after the
	 * modification, VBO uses the method getLogBookingModification().
	 * VCM instead should use this method to tell the class that
	 * what has changed should be calculated to obtain the 'descr'
	 * text of the history record that will be stored.
	 * 
	 * @param 	array 	$booking
	 *
	 * @return 	self
	 **/
	public function setPrevBooking($booking)
	{
		if (is_array($booking)) {
			$this->prevBooking = $booking;
		}

		return $this;
	}

	/**
	 * Sets extra data for the current history log.
	 * 
	 * @param 	mixed 	$data 	array, object or string of extra data.
	 * 							Useful to store the amount paid to be invoiced
	 * 							or the transaction details of the payments.
	 *
	 * @return 	self
	 **/
	public function setExtraData($data)
	{
		$this->data = $data;

		return $this;
	}

	/**
	 * Checks whether the type for the history record is valid.
	 *
	 * @param 	string 		$type
	 * @param 	[bool] 		$returnit 	if true, the translated value is returned. Otherwise boolean is returned
	 *
	 * @return 	boolean
	 */
	public function validType($type, $returnit = false)
	{
		if ($returnit) {
			return isset($this->typesMap[strtoupper($type)]) ? $this->typesMap[strtoupper($type)] : $type;
		}

		return isset($this->typesMap[strtoupper($type)]);
	}

	/**
	 * Reads the booking record.
	 * Returns false in case of failure.
	 *
	 * @param 	mixed 	
	 *
	 * @return 	array
	 */
	protected function getBookingInfo()
	{
		$q = "SELECT * FROM `#__vikbooking_orders` WHERE `id`=".(int)$this->bid.";";
		$this->dbo->setQuery($q);
		$this->dbo->execute();
		if ($this->dbo->getNumRows() < 1) {
			return false;
		}
		return $this->dbo->loadAssoc();
	}

	/**
	 * Stores a new history record for the booking.
	 * 
	 * @param 	string 		$type 	the char-type of store we are making for the history
	 * @param 	[string] 	$descr 	the description of this booking record (optional)
	 *
	 * @return 	boolean
	 **/
	public function store($type, $descr = '')
	{
		if (is_null($this->bid) || !$this->validType($type)) {
			return false;
		}

		if (!$booking_info = $this->getBookingInfo()) {
			return false;
		}

		if (empty($descr) && is_array($this->prevBooking)) {
			//VCM (including the App) could set the previous booking information, so we need to calculate what has changed with the booking
			//load VBO language
			$lang = JFactory::getLanguage();
			$lang->load('com_vikbooking', VIKBOOKING_ADMIN_LANG, $lang->getTag(), true);
			if (!class_exists('VikBooking')) {
				require_once(VBO_SITE_PATH.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'lib.vikbooking.php');
			}
			$descr = VikBooking::getLogBookingModification($this->prevBooking);
		}

		/**
		 * Store the extra data for this history log. Useful to store
		 * information about the amount paid and its invoicing status.
		 * 
		 * @since 	1.1.7
		 */
		$extra_data = $this->data;
		if (!is_null($extra_data) && !is_scalar($extra_data)) {
			$extra_data = json_encode($extra_data);
		}

		/**
		 * @wponly 	we do not use JFactory::getDate()->toSql(true) because we
		 * store the time in UTC, then we format it using JHtmlDate
		 */
		$q = "INSERT INTO `#__vikbooking_orderhistory` (`idorder`, `dt`, `type`, `descr`, `totpaid`, `total`, `data`) VALUES (".$this->bid.", ".$this->dbo->quote(JFactory::getDate()->toSql()).", ".$this->dbo->quote($type).", ".(empty($descr) ? "NULL" : $this->dbo->quote($descr)).", ".(float)$booking_info['totpaid'].", ".(float)$booking_info['total'].", ".(is_null($extra_data) ? 'NULL' : $this->dbo->quote($extra_data)).");";
		$this->dbo->setQuery($q);
		$this->dbo->execute();
		$rid = $this->dbo->insertid();

		return ((int)$rid > 0);
	}

	/**
	 * Loads all the history records for this booking
	 *
	 * @return 	array
	 */
	public function loadHistory()
	{
		$history = array();

		if (empty($this->bid)) {
			return $history;
		}

		$q = "SELECT * FROM `#__vikbooking_orderhistory` WHERE `idorder`=".(int)$this->bid." ORDER BY `dt` DESC;";
		$this->dbo->setQuery($q);
		$this->dbo->execute();
		if ($this->dbo->getNumRows() > 0) {
			$history = $this->dbo->loadAssocList();
		}

		return $history;
	}

	/**
	 * Checks whether this booking has an event of the given type.
	 * 
	 * @param 	string 	$type 	the type of the event.
	 *
	 * @return 	mixed 			last date on success, false otherwise.
	 * 
	 * @since 	1.3.5
	 */
	public function hasEvent($type)
	{
		if (empty($type) || empty($this->bid)) {
			return false;
		}

		$q = "SELECT `dt` FROM `#__vikbooking_orderhistory` WHERE `idorder`=" . (int)$this->bid . " AND `type`=" . $this->dbo->quote($type) . " ORDER BY `dt` DESC";
		$this->dbo->setQuery($q, 0, 1);
		$this->dbo->execute();
		if ($this->dbo->getNumRows()) {
			return $this->dbo->loadResult();
		}

		return false;
	}

	/**
	 * Returns a list of records with data defined for the given event type.
	 * Useful to get a list of transactions data for the refund operations.
	 * 
	 * @param 	mixed 		$type 		string or array event type(s).
	 * @param 	callable 	$callvalid 	callback for the data validation.
	 * @param 	bool 		$onlydata 	whether to get just the event data.
	 *
	 * @return 	mixed 					false or array with data records.
	 * 
	 * @since 	1.4.0
	 */
	public function getEventsWithData($type, $callvalid = null, $onlydata = true)
	{
		if (empty($type) || empty($this->bid)) {
			return false;
		}

		if (!is_array($type)) {
			$type = array($type);
		}

		// quote all given types
		$types = array_map(array($this->dbo, 'quote'), $type);

		$q = "SELECT * FROM `#__vikbooking_orderhistory` WHERE `idorder`=" . (int)$this->bid . " AND `type` IN (" . implode(', ', $types) . ") ORDER BY `dt` ASC;";
		$this->dbo->setQuery($q);
		$this->dbo->execute();
		if ($this->dbo->getNumRows()) {
			$events = $this->dbo->loadAssocList();
			$datas  = array();
			foreach ($events as $k => $e) {
				$data = json_decode($e['data']);
				if (!empty($data)) {
					$events[$k]['data'] = $data;
				}
				$valid_data = true;
				if (is_callable($callvalid)) {
					$valid_data = call_user_func($callvalid, $events[$k]['data']);
				}
				if ($valid_data) {
					array_push($datas, $events[$k]['data']);
				}
			}

			return $onlydata ? $datas : $events;
		}

		return false;
	}

	/**
	 * Gets the latest history event per booking within a list.
	 * Only the latest event per booking will be considered.
	 * 
	 * @param 	int 	$start 	 the query start offset.
	 * @param 	int 	$limit 	 the query limit.
	 * @param 	int 	$min_id  optional minimum history ID to fetch.
	 * 
	 * @return 	array 			 the list of recent history record objects.
	 * 
	 * @since 	1.15.0 (J) - 1.5.0 (WP)
	 */
	public function getLatestBookingEvents($start = 0, $limit = 20, $min_id = 0)
	{
		$clauses = [
			"`o`.`status` IS NOT NULL",
		];
		if ($min_id > 0) {
			$clauses[] = "`h1`.`id` > " . (int)$min_id;
		}

		$q = "SELECT `h1`.*, `o`.`custdata`, `o`.`status`, `o`.`days`, `o`.`checkin`, `o`.`checkout`,
			`o`.`totpaid`, `o`.`total`, `o`.`idorderota`, `o`.`channel`, `o`.`country`, `o`.`closure`,
			(
				SELECT CONCAT_WS(' ',`or`.`t_first_name`,`or`.`t_last_name`) 
				FROM `#__vikbooking_ordersrooms` AS `or` 
				WHERE `or`.`idorder`=`h1`.`idorder` LIMIT 1
			) AS `nominative`
			FROM `#__vikbooking_orderhistory` AS `h1`
			INNER JOIN
			(
				SELECT MAX(`dt`) AS `max_dt`, `idorder`
				FROM `#__vikbooking_orderhistory`
				GROUP BY `idorder`
			) AS `h2` ON `h1`.`idorder`=`h2`.`idorder` AND `h1`.`dt`=`h2`.`max_dt`
			LEFT JOIN `#__vikbooking_orders` AS `o` ON `h1`.`idorder`=`o`.`id`
			WHERE " . implode(' AND ', $clauses) . " ORDER BY `h1`.`dt` DESC, `h1`.`id` DESC";

		$this->dbo->setQuery($q, $start, $limit);
		$this->dbo->execute();
		if ($this->dbo->getNumRows()) {
			return $this->dbo->loadObjectList();
		}

		return array();
	}

	/**
	 * Helper method to set a new list of worthy event types.
	 * 
	 * @param 	array 	$worthy_events  list of event type identifiers.
	 * 
	 * @return 	self
	 * 
	 * @since 	1.15.0 (J) - 1.5.0 (WP)
	 */
	public function setWorthyEventTypes($worthy_events = [])
	{
		if (is_array($worthy_events)) {
			$this->worthy_events = $worthy_events;
		}

		return $this;
	}

	/**
	 * Helper method to get the list of worthy event types.
	 * 
	 * @return 	array
	 * 
	 * @since 	1.15.4 (J) - 1.5.10 (WP)
	 */
	public function getWorthyEventTypes()
	{
		return $this->worthy_events;
	}

	/**
	 * Gets the latest history events worthy of a notification.
	 * 
	 * @param 	int 	$min_id  optional minimum history ID to fetch.
	 * @param 	int 	$limit 	 the query limit.
	 * 
	 * @return 	array 			 the list of worthy history record objects.
	 * 
	 * @since 	1.15.0 (J) - 1.5.0 (WP)
	 */
	public function getWorthyEvents($min_id = 0, $limit = 0)
	{
		$clauses = [
			"`o`.`status` IS NOT NULL",
			"`o`.`closure` = 0"
		];
		if ($min_id > 0) {
			$clauses[] = "`h1`.`id` > " . (int)$min_id;
		}
		if (count($this->worthy_events)) {
			// quote all worthy types of event
			$worthy_types = array_map(array($this->dbo, 'quote'), $this->worthy_events);
			$clauses[] = "`h1`.`type` IN (" . implode(', ', $worthy_types) . ")";
		}

		$q = "SELECT `h1`.*, `o`.`status`, `o`.`days`, `o`.`checkin`, `o`.`checkout`,
			`o`.`totpaid`, `o`.`total`, `o`.`idorderota`, `o`.`channel`, `o`.`country`
			FROM `#__vikbooking_orderhistory` AS `h1`
			INNER JOIN
			(
				SELECT MAX(`dt`) AS `max_dt`, `idorder`
				FROM `#__vikbooking_orderhistory`
				GROUP BY `idorder`
			) AS `h2` ON `h1`.`idorder`=`h2`.`idorder` AND `h1`.`dt`=`h2`.`max_dt`
			LEFT JOIN `#__vikbooking_orders` AS `o` ON `h1`.`idorder`=`o`.`id`
			WHERE " . implode(' AND ', $clauses) . " ORDER BY `h1`.`dt` DESC";

		$this->dbo->setQuery($q, 0, $limit);
		$this->dbo->execute();
		if ($this->dbo->getNumRows()) {
			return $this->dbo->loadObjectList();
		}

		return array();
	}
}
