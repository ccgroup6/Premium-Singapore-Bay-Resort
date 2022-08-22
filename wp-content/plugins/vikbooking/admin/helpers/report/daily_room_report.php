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
* Daily Room Report child Class of VikBookingReport
*/
class VikBookingReportDailyRoomReport extends VikBookingReport
{
	/**
	 * Property 'defaultKeySort' is used by the View that renders the report.
	 */
	public $defaultKeySort = 'type';
	/**
	 * Property 'defaultKeyOrder' is used by the View that renders the report.
	 */
	public $defaultKeyOrder = 'ASC';
	/**
	 * Property 'exportAllowed' is used by the View to display the export button.
	 */
	public $exportAllowed = 1;
	/**
	 * Debug mode is activated by passing the value 'e4j_debug' > 0
	 */
	private $debug;

	/**
	 * Class constructor should define the name of the report and
	 * other vars. Call the parent constructor to define the DB object.
	 */
	function __construct()
	{
		$this->reportFile = basename(__FILE__, '.php');
		$this->reportName = JText::translate('VBOREPORT'.strtoupper(str_replace('_', '', $this->reportFile)));
		$this->reportFilters = array();

		$this->cols = array();
		$this->rows = array();
		$this->footerRow = array();

		$this->debug = (VikRequest::getInt('e4j_debug', 0, 'request') > 0);

		parent::__construct();
	}

	/**
	 * Returns the name of this report.
	 *
	 * @return 	string
	 */
	public function getName()
	{
		return $this->reportName;
	}

	/**
	 * Returns the name of this file without .php.
	 *
	 * @return 	string
	 */
	public function getFileName()
	{
		return $this->reportFile;
	}

	/**
	 * Returns the filters of this report.
	 *
	 * @return 	array
	 */
	public function getFilters()
	{
		if (count($this->reportFilters)) {
			//do not run this method twice, as it could load JS and CSS files.
			return $this->reportFilters;
		}

		//get VBO Application Object
		$vbo_app = new VboApplication();

		//load the jQuery UI Datepicker
		$this->loadDatePicker();

		//From Date Filter
		$filter_opt = array(
			'label' => '<label for="fromdate">'.JText::translate('VBOREPORTREVENUEDAY').'</label>',
			'html' => '<input type="text" id="fromdate" name="fromdate" value="" class="vbo-report-datepicker vbo-report-datepicker-from" />',
			'type' => 'calendar',
			'name' => 'fromdate'
		);
		array_push($this->reportFilters, $filter_opt);

		//To Date Filter
		$filter_opt = array(
			'label' => '<label for="todate">'.JText::translate('VBOREPORTSDATETO').'</label>',
			'html' => '<input type="text" id="todate" name="todate" value="" placeholder="'.addslashes(JText::translate('VBOFILTEISROPTIONAL')).'" class="vbo-report-datepicker vbo-report-datepicker-to" />',
			'type' => 'calendar',
			'name' => 'todate'
		);
		array_push($this->reportFilters, $filter_opt);

		//Room ID filter
		$pidroom = VikRequest::getInt('idroom', '', 'request');
		$all_rooms = $this->getRooms();
		$rooms = array();
		foreach ($all_rooms as $room) {
			$rooms[$room['id']] = $room['name'];
		}
		if (count($rooms)) {
			$rooms_sel_html = $vbo_app->getNiceSelect($rooms, $pidroom, 'idroom', JText::translate('VBOSTATSALLROOMS'), JText::translate('VBOSTATSALLROOMS'), '', '', 'idroom');
			$filter_opt = array(
				'label' => '<label for="idroom">'.JText::translate('VBOREPORTSROOMFILT').'</label>',
				'html' => $rooms_sel_html,
				'type' => 'select',
				'name' => 'idroom'
			);
			array_push($this->reportFilters, $filter_opt);
		}

		// get minimum check-in and maximum check-out for dates filters
		$df = $this->getDateFormat();
		$mincheckin = 0;
		$maxcheckout = 0;
		$q = "SELECT MIN(`checkin`) AS `mincheckin`, MAX(`checkout`) AS `maxcheckout` FROM `#__vikbooking_orders` WHERE `status`='confirmed' AND `closure`=0;";
		$this->dbo->setQuery($q);
		$this->dbo->execute();
		if ($this->dbo->getNumRows()) {
			$data = $this->dbo->loadAssoc();
			if (!empty($data['mincheckin']) && !empty($data['maxcheckout'])) {
				$mincheckin = $data['mincheckin'];
				$maxcheckout = $data['maxcheckout'];
			}
		}
		//

		//jQuery code for the datepicker calendars and select2
		$pfromdate = VikRequest::getString('fromdate', date($df), 'request');
		$ptodate = VikRequest::getString('todate', '', 'request');
		$js = 'jQuery(document).ready(function() {
			jQuery(".vbo-report-datepicker:input").datepicker({
				'.(!empty($mincheckin) ? 'minDate: "'.date($df, $mincheckin).'", ' : '').'
				'.(!empty($maxcheckout) ? 'maxDate: "'.date($df, $maxcheckout).'", ' : '').'
				dateFormat: "'.$this->getDateFormat('jui').'"
			});
			'.(!empty($pfromdate) ? 'jQuery(".vbo-report-datepicker-from").datepicker("setDate", "'.$pfromdate.'");' : '').'
			'.(!empty($ptodate) ? 'jQuery(".vbo-report-datepicker-to").datepicker("setDate", "'.$ptodate.'");' : '').'
		});';
		$this->setScript($js);

		return $this->reportFilters;
	}

	/**
	 * Loads the report data from the DB.
	 * Returns true in case of success, false otherwise.
	 * Sets the columns and rows for the report to be displayed.
	 *
	 * @return 	boolean
	 */
	public function getReportData()
	{
		if (strlen($this->getError())) {
			//Export functions may set errors rather than exiting the process, and the View may continue the execution to attempt to render the report.
			return false;
		}
		//Input fields and other vars
		$pfromdate = VikRequest::getString('fromdate', '', 'request');
		$ptodate = VikRequest::getString('todate', $pfromdate, 'request');
		$ptodate = empty($ptodate) ? $pfromdate : $ptodate;
		$pidroom = VikRequest::getInt('idroom', '', 'request');
		$pkrsort = VikRequest::getString('krsort', $this->defaultKeySort, 'request');
		$pkrsort = empty($pkrsort) ? $this->defaultKeySort : $pkrsort;
		$pkrorder = VikRequest::getString('krorder', $this->defaultKeyOrder, 'request');
		$pkrorder = empty($pkrorder) ? $this->defaultKeyOrder : $pkrorder;
		$pkrorder = $pkrorder == 'DESC' ? 'DESC' : 'ASC';
		$currency_symb = VikBooking::getCurrencySymb();
		$df = $this->getDateFormat();
		$datesep = VikBooking::getDateSeparator();
		//Get dates timestamps
		$from_ts = VikBooking::getDateTimestamp($pfromdate, 0, 0);
		$to_ts = VikBooking::getDateTimestamp($ptodate, 23, 59, 59);
		if (empty($pfromdate) || empty($from_ts)) {
			$this->setError(JText::translate('VBOREPORTSERRNODATES'));
			return false;
		}
		// check whether multiple/different dates were selected
		$multi_dates = ($pfromdate != $ptodate);
		$todaydt = $multi_dates ? '' : date('Y-m-d', $from_ts);

		//Query to obtain the records
		$records = array();
		$q = "SELECT `o`.`id`,`o`.`custdata`,`o`.`ts`,`o`.`days`,`o`.`checkin`,`o`.`checkout`,`o`.`roomsnum`,`o`.`idorderota`,`o`.`channel`,`o`.`country`,`o`.`adminnotes`,".
			"`or`.`idorder`,`or`.`idroom`,`or`.`adults`,`or`.`children`,`or`.`idtar`,`or`.`t_first_name`,`or`.`t_last_name`,`or`.`roomindex`,`or`.`pkg_name`,`or`.`otarplan`,`r`.`name`,`r`.`params`,".
			"`co`.`idcustomer`,`c`.`first_name`,`c`.`last_name`,`c`.`country` AS `customer_country` ".
			"FROM `#__vikbooking_orders` AS `o` LEFT JOIN `#__vikbooking_ordersrooms` AS `or` ON `or`.`idorder`=`o`.`id` LEFT JOIN `#__vikbooking_rooms` AS `r` ON `or`.`idroom`=`r`.`id` ".
			"LEFT JOIN `#__vikbooking_customers_orders` AS `co` ON `co`.`idorder`=`o`.`id` LEFT JOIN `#__vikbooking_customers` AS `c` ON `c`.`id`=`co`.`idcustomer` ".
			"WHERE `o`.`status`='confirmed' AND `o`.`closure`=0 AND `o`.`checkout`>=".$from_ts." AND `o`.`checkin`<=".$to_ts." ".(!empty($pidroom) ? "AND `or`.`idroom`=".(int)$pidroom." " : "").
			"ORDER BY `o`.`checkin` DESC, `o`.`id` ASC;";
		$this->dbo->setQuery($q);
		$this->dbo->execute();
		if ($this->dbo->getNumRows() > 0) {
			$records = $this->dbo->loadAssocList();
		}
		if (!count($records)) {
			$this->setError(JText::translate('VBOREPORTSERRNORESERV'));
			return false;
		}

		//nest records with multiple rooms booked inside sub-array
		$bookings = array();
		foreach ($records as $v) {
			if (!isset($bookings[$v['id']])) {
				$bookings[$v['id']] = array();
			}
			//calculate the from_ts and to_ts values for later comparison
			$in_info = getdate($v['checkin']);
			$out_info = getdate($v['checkout']);
			$v['from_ts'] = mktime(0, 0, 0, $in_info['mon'], $in_info['mday'], $in_info['year']);
			$v['to_ts'] = mktime(23, 59, 59, $out_info['mon'], ($out_info['mday'] - 1), $out_info['year']);
			//
			array_push($bookings[$v['id']], $v);
		}

		// Debug
		// $this->setWarning('<pre>'.print_r($bookings, true).'</pre>');
		//

		//define the columns of the report
		$this->cols = array(
			//Type
			array(
				'key' => 'type',
				'sortable' => 1,
				'label' => JText::translate('VBPSHOWSEASONSTHREE')
			),
			//ID
			array(
				'key' => 'id',
				'sortable' => 1,
				'label' => JText::translate('VBDASHBOOKINGID')
			),
			//checkin
			array(
				'key' => 'checkin',
				'attr' => array(
					'class="left"'
				),
				'sortable' => 1,
				'label' => JText::translate('VBPICKUPAT')
			),
			//checkout
			array(
				'key' => 'checkout',
				'attr' => array(
					'class="left"'
				),
				'sortable' => 1,
				'label' => JText::translate('VBRELEASEAT')
			),
			//nights
			array(
				'key' => 'nights',
				'attr' => array(
					'class="center"'
				),
				'sortable' => 1,
				'label' => JText::translate('VBDAYS')
			),
			//Room name
			array(
				'key' => 'room_name',
				'attr' => array(
					'class="center"'
				),
				'sortable' => 1,
				'label' => JText::translate('VBEDITORDERTHREE')
			),
			//guests
			array(
				'key' => 'guests',
				'attr' => array(
					'class="center"'
				),
				'sortable' => 1,
				'label' => JText::translate('VBPVIEWORDERSPEOPLE')
			),
			//customer
			array(
				'key' => 'customer',
				'attr' => array(
					'class="left"'
				),
				'sortable' => 1,
				'label' => JText::translate('VBCUSTOMERNOMINATIVE')
			),
			//admin notes
			array(
				'key' => 'admin notes',
				'attr' => array(
					'class="center"'
				),
				'sortable' => 1,
				'label' => JText::translate('VBADMINNOTESTOGGLE')
			),
			//rate plan
			array(
				'key' => 'rplan',
				'attr' => array(
					'class="center"'
				),
				'sortable' => 1,
				'label' => JText::translate('VBOROVWSELRPLAN')
			),
		);

		//loop over the bookings to build the list of countries
		$countries = array();
		foreach ($bookings as $k => $gbook) {
			$country = 'unknown';
			if (!empty($gbook[0]['country'])) {
				$country = $gbook[0]['country'];
			} elseif (!empty($gbook[0]['customer_country'])) {
				$country = $gbook[0]['customer_country'];
			}
			if (!in_array($country, $countries)) {
				array_push($countries, $country);
			}
			$bookings[$k][0]['country'] = $country;
		}

		$countries_map = $this->getCountriesMap($countries);

		// loop over the bookings to build the rows
		foreach ($bookings as $gbook) {
			// prepare vars
			$customer_name = '-----';
			if (!empty($gbook[0]['t_first_name']) && !empty($gbook[0]['t_last_name'])) {
				$customer_name = ltrim($gbook[0]['t_first_name'].' '.$gbook[0]['t_last_name'], ' ');
			} elseif (!empty($gbook[0]['first_name']) && !empty($gbook[0]['last_name'])) {
				$customer_name = ltrim($gbook[0]['first_name'].' '.$gbook[0]['last_name'], ' ');
			} elseif (!empty($gbook[0]['custdata'])) {
				$parts = explode("\n", $gbook[0]['custdata']);
				if (count($parts) >= 2 && strpos($parts[0], ':') !== false && strpos($parts[1], ':') !== false) {
					$first_parts = explode(':', $parts[0]);
					$second_parts = explode(':', $parts[1]);
					$customer_name = ltrim(trim($first_parts[1]).' '.trim($second_parts[1]), ' ');
				}
			}
			$country = $gbook[0]['country'];
			$in_info = getdate($gbook[0]['checkin']);
			$curwday_in = $this->getWdayString($in_info['wday'], 'short');
			$out_info = getdate($gbook[0]['checkout']);
			$curwday_out = $this->getWdayString($out_info['wday'], 'short');
			$type = JText::translate('VBOTYPESTAYOVER');
			if (date('Y-m-d', $gbook[0]['checkin']) == $todaydt) {
				$type = JText::translate('VBOTYPEARRIVAL');
			} elseif (date('Y-m-d', $gbook[0]['checkout']) == $todaydt) {
				$type = JText::translate('VBOTYPEDEPARTURE');
			}
			foreach ($gbook as $roomres) {
				// room name and index
				$unit_index = '';
				if (strlen($roomres['roomindex']) && !empty($roomres['params'])) {
					$room_params = json_decode($roomres['params'], true);
					if (is_array($room_params) && array_key_exists('features', $room_params) && @count($room_params['features']) > 0) {
						foreach ($room_params['features'] as $rind => $rfeatures) {
							if ($rind == $roomres['roomindex']) {
								foreach ($rfeatures as $fname => $fval) {
									if (strlen($fval)) {
										$unit_index = ' #'.$fval;
										break;
									}
								}
								break;
							}
						}
					}
				}
				$roomname = $roomres['name'] . $unit_index;

				// rate plan name
				if (!empty($roomres['otarplan'])) {
					$rplan = $roomres['otarplan'];
				} else {
					$rplan = $this->getPriceName($roomres['idtar']);
				}

				//push fields in the rows array as a new row
				array_push($this->rows, array(
					array(
						'key' => 'type',
						'value' => $type
					),
					array(
						'key' => 'id',
						'callback' => function ($val) {
							return '<a href="index.php?option=com_vikbooking&task=editorder&cid[]='.$val.'" target="_blank"><i class="'.VikBookingIcons::i('external-link').'"></i> '.$val.'</a>';
						},
						'export_callback' => function ($val) {
							return $val;
						},
						'value' => $roomres['id']
					),
					array(
						'key' => 'checkin',
						'callback' => function ($val) use ($df, $datesep, $curwday_in) {
							return $curwday_in.', '.date(str_replace("/", $datesep, $df), $val);
						},
						'export_callback' => function ($val) use ($df, $datesep, $curwday_in) {
							return $curwday_in.', '.date(str_replace("/", $datesep, $df), $val);
						},
						'value' => $roomres['checkin']
					),
					array(
						'key' => 'checkout',
						'callback' => function ($val) use ($df, $datesep, $curwday_out) {
							return $curwday_out.', '.date(str_replace("/", $datesep, $df), $val);
						},
						'export_callback' => function ($val) use ($df, $datesep, $curwday_out) {
							return $curwday_out.', '.date(str_replace("/", $datesep, $df), $val);
						},
						'value' => $roomres['checkout']
					),
					array(
						'key' => 'nights',
						'attr' => array(
							'class="center"'
						),
						'value' => $roomres['days']
					),
					array(
						'key' => 'room_name',
						'attr' => array(
							'class="center"'
						),
						'value' => $roomname
					),
					array(
						'key' => 'guests',
						'attr' => array(
							'class="center"'
						),
						'value' => ($roomres['adults'] + $roomres['children'])
					),
					array(
						'key' => 'customer',
						'attr' => array(
							'class="vbo-report-touristtaxes-countryname"'
						),
						'callback' => function ($val) use ($country) {
							if (is_file(VBO_ADMIN_PATH.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'countries'.DIRECTORY_SEPARATOR.$country.'.png')) {
								return $val.'<img src="'.VBO_ADMIN_URI.'resources/countries/'.$country.'.png" title="'.$country.'" class="vbo-country-flag vbo-country-flag-left" />';
							}
							return $val;
						},
						'export_callback' => function ($val) use ($country) {
							return $val . (!empty($country) ? ' ('.$country.')' : '');
						},
						'value' => $customer_name
					),
					array(
						'key' => 'admin notes',
						'attr' => array(
							'class="center"'
						),
						'value' => $roomres['adminnotes']
					),
					array(
						'key' => 'rplan',
						'attr' => array(
							'class="center"'
						),
						'value' => ucwords($rplan)
					),
				));
			}
		}

		//sort rows
		$this->sortRows($pkrsort, $pkrorder);

		//Debug
		if ($this->debug) {
			$this->setWarning('path to report file = '.urlencode(dirname(__FILE__)).'<br/>');
			$this->setWarning('$total_rooms_units = '.$total_rooms_units.'<br/>');
			$this->setWarning('$bookings:<pre>'.print_r($bookings, true).'</pre><br/>');
		}
		//

		return true;
	}

	/**
	 * Generates the report columns and rows, then it outputs a CSV file
	 * for download. In case of errors, the process is not terminated (exit)
	 * to let the View display the error message.
	 *
	 * @return 	mixed 	void on success with script termination, false otherwise.
	 */
	public function exportCSV()
	{
		if (!$this->getReportData()) {
			return false;
		}
		$pfromdate = VikRequest::getString('fromdate', '', 'request');
		$ptodate = VikRequest::getString('todate', '', 'request');

		$csvlines = array();

		//Push the head of the CSV file
		$csvcols = array();
		foreach ($this->cols as $col) {
			array_push($csvcols, $col['label']);
		}
		array_push($csvlines, $csvcols);

		//Push the rows of the CSV file
		foreach ($this->rows as $row) {
			$csvrow = array();
			foreach ($row as $field) {
				array_push($csvrow, (isset($field['export_callback']) && is_callable($field['export_callback']) ? $field['export_callback']($field['value']) : $field['value']));
			}
			array_push($csvlines, $csvrow);
		}

		//Force CSV download
		header("Content-type: text/csv");
		header("Cache-Control: no-store, no-cache");
		header('Content-Disposition: attachment; filename="'.$this->reportName.'-'.str_replace('/', '_', $pfromdate).(!empty($ptodate) && $ptodate != $pfromdate ? '-' . str_replace('/', '_', $ptodate) : '').'.csv"');
		$outstream = fopen("php://output", 'w');
		foreach ($csvlines as $csvline) {
			fputcsv($outstream, $csvline);
		}
		fclose($outstream);
		exit;
	}

	/**
	 * Maps the 3-char country codes to their full names.
	 * Translates also the 'unknown' country.
	 *
	 * @param 	array  		$countries
	 *
	 * @return 	array
	 */
	private function getCountriesMap($countries)
	{
		$map = array();

		if (in_array('unknown', $countries)) {
			$map['unknown'] = JText::translate('VBOREPORTTOPCUNKNC');
			foreach ($countries as $k => $v) {
				if ($v == 'unknown') {
					unset($countries[$k]);
				}
			}
		}

		if (count($countries)) {
			$clauses = array();
			foreach ($countries as $country) {
				array_push($clauses, $this->dbo->quote($country));
			}
			$q = "SELECT `country_name`,`country_3_code` FROM `#__vikbooking_countries` WHERE `country_3_code` IN (".implode(', ', $clauses).");";
			$this->dbo->setQuery($q);
			$this->dbo->execute();
			if ($this->dbo->getNumRows() > 0) {
				$records = $this->dbo->loadAssocList();
				foreach ($records as $v) {
					$map[$v['country_3_code']] = $v['country_name'];
				}
			}
		}

		return $map;
	}

	/**
	 * Finds the name of the rate plan from the given tariff ID.
	 *
	 * @param 	int  	$idtar	the ID of the tariff.
	 *
	 * @return 	string 			the name of the rate plan or an empty string.
	 */
	private function getPriceName($idtar)
	{
		if (empty($idtar)) {
			return JText::translate('VBOROOMCUSTRATEPLAN');
		}

		$q = "SELECT `p`.`name` FROM `#__vikbooking_prices` AS `p`
		LEFT JOIN `#__vikbooking_dispcost` AS `t` ON `p`.`id`=`t`.`idprice` WHERE `t`.`id`=".(int)$idtar.";";
		$this->dbo->setQuery($q);
		$this->dbo->execute();
		if ($this->dbo->getNumRows()) {
			return $this->dbo->loadResult();
		}

		return '';
	}

}
