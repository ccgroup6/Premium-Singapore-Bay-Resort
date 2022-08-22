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
 * Reports parent Class of all sub-classes
 */
abstract class VikBookingReport
{
	protected $reportName = '';
	protected $reportFile = '';
	protected $reportFilters = array();
	protected $reportScript = '';
	protected $warning = '';
	protected $error = '';
	protected $dbo;

	protected $cols = array();
	protected $rows = array();
	protected $footerRow = array();

	/**
	 * An array of custom options to be passed to the report.
	 * Reports can use them before generating the report data.
	 * 
	 * @var 	array
	 * 
	 * @since 	1.15.0 (J) - 1.5.0 (WP)
	 */
	protected $options = array();

	/**
	 * Class constructor should define the name of
	 * the report and the filters to be displayed.
	 */
	public function __construct() {
		$this->dbo = JFactory::getDbo();
	}

	/**
	 * Extending Classes should define this method
	 * to get the name of the report.
	 */
	abstract public function getName();

	/**
	 * Extending Classes should define this method
	 * to get the name of class file.
	 */
	abstract public function getFileName();

	/**
	 * Extending Classes should define this method
	 * to get the filters of the report.
	 */
	abstract public function getFilters();

	/**
	 * Extending Classes should define this method
	 * to generate the report data (cols and rows).
	 */
	abstract public function getReportData();

	/**
	 * Loads a specific report class and returns its instance.
	 * Should be called for instantiating any report sub-class.
	 * 
	 * @param 	string 	$report 	the report file name (i.e. "revenue").
	 * 
	 * @return 	mixed 	false or requested report object.
	 */
	public static function getInstanceOf($report)
	{
		if (empty($report) || !is_string($report)) {
			return false;
		}

		if (substr($report, -4) != '.php') {
			$report .= '.php';
		}

		$report_path = dirname(__FILE__) . DIRECTORY_SEPARATOR . $report;

		if (!is_file($report_path)) {
			return false;
		}

		require_once $report_path;

		$classname = 'VikBookingReport' . str_replace(' ', '', ucwords(str_replace('.php', '', str_replace('_', ' ', $report))));
		if (!class_exists($classname)) {
			return false;
		}

		return new $classname;
	}

	/**
	 * Injects request parameters for the report like if some filters were set.
	 * 
	 * @param 	array 	$params 	associative list of request vars to inject.
	 *
	 * @return 	self
	 */
	public function injectParams($params)
	{
		if (is_array($params) && count($params)) {
			foreach ($params as $key => $value) {
				/**
				 * For more safety across different platforms and versions (J3/J4 or WP)
				 * we inject values in the super global array as well as in the input object.
				 */
				VikRequest::setVar($key, $value, 'request');
				VikRequest::setVar($key, $value);
			}
		}

		return $this;
	}

	/**
	 * Loads the jQuery UI Datepicker.
	 * Method used only by sub-classes.
	 *
	 * @return 	self
	 */
	protected function loadDatePicker()
	{
		$vbo_app = new VboApplication();
		$vbo_app->loadDatePicker();

		return $this;
	}

	/**
	 * Loads Charts CSS/JS assets.
	 *
	 * @return 	self
	 */
	public function loadChartsAssets()
	{
		$document = JFactory::getDocument();
		$document->addStyleSheet(VBO_ADMIN_URI . 'resources/Chart.min.css', array('version' => VIKBOOKING_SOFTWARE_VERSION));
		$document->addScript(VBO_ADMIN_URI . 'resources/Chart.min.js', array('version' => VIKBOOKING_SOFTWARE_VERSION));

		return $this;
	}

	/**
	 * Loads all the rooms in VBO and returns the array.
	 *
	 * @return 	array
	 */
	protected function getRooms()
	{
		$rooms = array();
		$q = "SELECT * FROM `#__vikbooking_rooms` ORDER BY `name` ASC;";
		$this->dbo->setQuery($q);
		$this->dbo->execute();
		if ($this->dbo->getNumRows() > 0) {
			$rooms = $this->dbo->loadAssocList();
		}

		return $rooms;
	}

	/**
	 * Loads all the rate plans in VBO and returns the array.
	 *
	 * @return 	array
	 * 
	 * @since 	1.15.0 (J) - 1.5.0 (WP)
	 */
	protected function getRatePlans()
	{
		$rplans = array();
		$q = "SELECT * FROM `#__vikbooking_prices` ORDER BY `name` ASC;";
		$this->dbo->setQuery($q);
		$this->dbo->execute();
		if ($this->dbo->getNumRows() > 0) {
			$rplans = $this->dbo->loadAssocList();
		}

		return VikBooking::sortRatePlans($rplans);
	}

	/**
	 * Returns the number of total units for all rooms, or for a specific room.
	 * By default, the rooms unpublished are skipped, and all rooms are used.
	 * 
	 * @param 	[mixed] $idroom 	int or array.
	 * @param 	[int] 	$published 	true or false.
	 *
	 * @return 	int
	 */
	protected function countRooms($idroom = 0, $published = 1)
	{
		$totrooms = 0;
		$clauses = array();
		if (is_int($idroom) && $idroom > 0) {
			$clauses[] = "`id`=".(int)$idroom;
		} elseif (is_array($idroom) && count($idroom)) {
			$clauses[] = "`id` IN (" . implode(', ', $idroom) . ")";
		}
		if ($published) {
			$clauses[] = "`avail`=1";
		}
		$q = "SELECT SUM(`units`) FROM `#__vikbooking_rooms`".(count($clauses) ? " WHERE ".implode(' AND ', $clauses) : "").";";
		$this->dbo->setQuery($q);
		$this->dbo->execute();
		if ($this->dbo->getNumRows() > 0) {
			$totrooms = (int)$this->dbo->loadResult();
		}

		return $totrooms;
	}

	/**
	 * Concatenates the JavaScript rules.
	 * Method used only by sub-classes.
	 *
	 * @param 	string 		$str
	 *
	 * @return 	self
	 */
	protected function setScript($str)
	{
		$this->reportScript .= $str."\n";

		return $this;
	}

	/**
	 * Gets the current script string.
	 *
	 * @return 	string
	 */
	public function getScript()
	{
		return rtrim($this->reportScript, "\n");
	}

	/**
	 * Returns the date format in VBO for date, jQuery UI, Joomla/WordPress.
	 * The visibility of this method should be public for anyone who needs it.
	 *
	 * @param 	string 		$type
	 *
	 * @return 	string
	 */
	public function getDateFormat($type = 'date')
	{
		$nowdf = VikBooking::getDateFormat();
		if ($nowdf == "%d/%m/%Y") {
			$df = 'd/m/Y';
			$juidf = 'dd/mm/yy';
		} elseif ($nowdf == "%m/%d/%Y") {
			$df = 'm/d/Y';
			$juidf = 'mm/dd/yy';
		} else {
			$df = 'Y/m/d';
			$juidf = 'yy/mm/dd';
		}

		switch ($type) {
			case 'jui':
				return $juidf;
			case 'joomla':
			case 'wordpress':
				return $nowdf;
			default:
				return $df;
		}
	}

	/**
	 * Returns the translated weekday.
	 * Uses the back-end language definitions.
	 *
	 * @param 	int 	$wday
	 * @param 	string 	$type 	use 'long' for the full name of the week, short for the 3-char version
	 *
	 * @return 	string
	 */
	protected function getWdayString($wday, $type = 'long')
	{
		$wdays_map_long = array(
			JText::translate('VBWEEKDAYZERO'),
			JText::translate('VBWEEKDAYONE'),
			JText::translate('VBWEEKDAYTWO'),
			JText::translate('VBWEEKDAYTHREE'),
			JText::translate('VBWEEKDAYFOUR'),
			JText::translate('VBWEEKDAYFIVE'),
			JText::translate('VBWEEKDAYSIX')
		);

		$wdays_map_short = array(
			JText::translate('VBSUN'),
			JText::translate('VBMON'),
			JText::translate('VBTUE'),
			JText::translate('VBWED'),
			JText::translate('VBTHU'),
			JText::translate('VBFRI'),
			JText::translate('VBSAT')
		);

		if ($type != 'long') {
			return isset($wdays_map_short[(int)$wday]) ? $wdays_map_short[(int)$wday] : '';
		}

		return isset($wdays_map_long[(int)$wday]) ? $wdays_map_long[(int)$wday] : '';
	}

	/**
	 * Sets the columns for this report.
	 *
	 * @param 	array 	$arr
	 *
	 * @return 	self
	 */
	protected function setReportCols($arr)
	{
		$this->cols = $arr;

		return $this;
	}

	/**
	 * Returns the columns for this report.
	 * Should be called after getReportData()
	 * or the returned array will be empty.
	 *
	 * @return 	array
	 */
	public function getReportCols()
	{
		return $this->cols;
	}

	/**
	 * Sorts the rows of the report by key.
	 *
	 * @param 	string 		$krsort 	the key attribute of the array pairs
	 * @param 	string 		$krorder 	ascending (ASC) or descending (DESC)
	 *
	 * @return 	void
	 */
	protected function sortRows($krsort, $krorder)
	{
		if (empty($krsort) || !(count($this->rows))) {
			return;
		}

		$map = array();
		foreach ($this->rows as $k => $row) {
			foreach ($row as $kk => $v) {
				if (isset($v['key']) && $v['key'] == $krsort) {
					$map[$k] = $v['value'];
				}
			}
		}
		if (!(count($map))) {
			return;
		}

		if ($krorder == 'ASC') {
			asort($map);
		} else {
			arsort($map);
		}

		$sorted = array();
		foreach ($map as $k => $v) {
			$sorted[$k] = $this->rows[$k];
		}

		$this->rows = $sorted;
	}

	/**
	 * Sets the rows for this report.
	 *
	 * @param 	array 	$arr
	 *
	 * @return 	self
	 */
	protected function setReportRows($arr)
	{
		$this->rows = $arr;

		return $this;
	}

	/**
	 * Returns the rows for this report.
	 * Should be called after getReportData()
	 * or the returned array will be empty.
	 *
	 * @return 	array
	 */
	public function getReportRows()
	{
		return $this->rows;
	}

	/**
	 * This method returns one or more rows (given the depth) generated by
	 * the current report invoked. It is useful to clean up the callbacks
	 * of the various cell-rows, to obtain a parsable result.
	 * Can be called as first method, by skipping also getReportData(). 
	 * 
	 * @param 	int 	$depth 	how many records to obtain, null for all.
	 *
	 * @return 	array 	the queried report value in the given depth.
	 * 
	 * @uses 	getReportData()
	 */
	public function getReportValues($depth = null)
	{
		if (!count($this->rows) && !$this->getReportData()) {
			return array();
		}

		$report_values = array();

		foreach ($this->rows as $rk => $row) {
			$report_values[$rk] = array();
			foreach ($row as $col => $coldata) {
				$display_value = $coldata['value'];
				if (isset($coldata['callback']) && is_callable($coldata['callback'])) {
					// launch callback
					$display_value = $coldata['callback']($coldata['value']);
				}
				// push column value
				$report_values[$rk][$coldata['key']] = array(
					'value' 		=> $coldata['value'],
					'display_value' => $display_value,
				);
				/**
				 * We also pass along any reserved key for this row-data.
				 * 
				 * @since 	1.15.0 (J) - 1.5.0 (WP)
				 */
				foreach ($coldata as $res_key => $data_val) {
					if (substr($res_key, 0, 1) == '_') {
						// push this reserved key
						$report_values[$rk][$coldata['key']][$res_key] = $data_val;
					}
				}
			}
		}

		if (!count($report_values)) {
			return array();
		}

		if ($depth === 1) {
			// get an associative array with the first row calculated
			return $report_values[0];
		}

		if (is_int($depth) && $depth > 0 && count($report_values) >= $depth) {
			// get the requested portion of the array
			return array_slice($report_values, 0, $depth);
		}

		return $report_values;
	}

	/**
	 * Maps the columns labels to an associative array to be used for the values.
	 * 
	 * @return 	array 	associative list of column keys and related values.
	 */
	public function getColumnsValues()
	{
		if (!count($this->cols)) {
			return array();
		}

		$col_values = array();

		foreach ($this->cols as $col) {
			if (!isset($col['key'])) {
				continue;
			}
			$col_values[$col['key']] = $col;
			unset($col_values[$col['key']]['key']);
		}

		return $col_values;
	}

	/**
	 * Gets a property defined by the report. Useful to get custom
	 * properties set up by a specific report maybe for the Chart.
	 * 
	 * @param 	string 	$property 	the name of the property needed.
	 * @param 	mixed 	$def 		default value to return.
	 * 
	 * @return 	mixed 	false on failure, property requested otherwise.
	 */
	public function getProperty($property, $def = false)
	{
		if (isset($this->{$property})) {
			return $this->{$property};
		}

		return $def;
	}

	/**
	 * Counts the number of days of difference between two timestamps.
	 * 
	 * @param 	int 	$to_ts 		the target end date timestamp.
	 * @param 	int 	$from_ts 	the starting date timestamp.
	 * 
	 * @return 	int 	the days of difference between from and to timestamps.
	 */
	public function countDaysTo($to_ts, $from_ts = 0)
	{
		if (empty($from_ts)) {
			$from_ts = time();
		}

		// whether DateTime can be used
		$usedt = false;

		if (class_exists('DateTime')) {
			$from_date = new DateTime(date('Y-m-d', $from_ts));
			if (method_exists($from_date, 'diff')) {
				$usedt = true;
			}
		}

		if ($usedt) {
			$to_date = new DateTime(date('Y-m-d', $to_ts));
			$daysdiff = (int)$from_date->diff($to_date)->format('%a');
			if ($to_ts < $from_ts) {
				// we need a negative integer number
				$daysdiff = $daysdiff - ($daysdiff * 2);
			}
			return $daysdiff;
		}

		return (int)round(($to_ts - $from_ts) / 86400);
	}

	/**
	 * Counts the average difference between two integers.
	 * 
	 * @param 	int 	$in_days_from 	days to the lowest timestamp.
	 * @param 	int 	$in_days_to 	days to the highest timestamp.
	 * 
	 * @return 	int 	the average number between the two values.
	 */
	public function countAverageDays($in_days_from, $in_days_to)
	{
		return (int)floor(($in_days_from + $in_days_to) / 2);
	}

	/**
	 * Sets the footer row (the totals) for this report.
	 *
	 * @param 	array 	$arr
	 *
	 * @return 	self
	 */
	protected function setReportFooterRow($arr)
	{
		$this->footerRow = $arr;

		return $this;
	}

	/**
	 * Returns the footer row for this report.
	 * Should be called after getReportData()
	 * or the returned array will be empty.
	 *
	 * @return 	array
	 */
	public function getReportFooterRow()
	{
		return $this->footerRow;
	}

	/**
	 * Sub-classes can extend this method to define the
	 * the canvas HTML tag for rendenring the Chart.
	 * Any necessary script shall be set within this method.
	 * Data can be passed as a mixed value through the argument.
	 * This is the first method to be called when working with the Chart.
	 * 
	 * @param 	mixed 	$data 	any necessary value to render the Chart.
	 *
	 * @return 	string 	the HTML of the canvas element.
	 */
	public function getChart($data = null)
	{
		return '';
	}

	/**
	 * Sub-classes can extend this method to define the
	 * the title of the Chart to be rendered.
	 *
	 * @return 	string 	the title of the Chart.
	 */
	public function getChartTitle()
	{
		return '';
	}

	/**
	 * Sub-classes can extend this method to define
	 * the meta data for the Chart containing stats.
	 * An array for each meta-data should be returned.
	 * 
	 * @param 	mixed 	$position 	string for the meta-data position
	 * 								in the Chart (top, right, bottom).
	 * @param 	mixed 	$data 		some arguments to be passed.
	 *
	 * @return 	array
	 */
	public function getChartMetaData($position = null, $data = null)
	{
		return array();
	}

	/**
	 * Sets an array of custom options for this report. Useful to inject
	 * params before getting the report data and changing the behavior.
	 *
	 * @param 	array 	$arr
	 *
	 * @return 	self
	 * 
	 * @since 	1.15.0 (J) - 1.5.0 (WP)
	 */
	public function setReportOptions($options = array())
	{
		$this->options = $options;

		return $this;
	}

	/**
	 * Returns the custom options for the report. Useful to
	 * behave differently depending on who calls the report.
	 * By default, the method returns an instance of JObject
	 * to easily access all custom options defined, if any.
	 * 
	 * @param 	bool 	$registry 	true to get a JObject instance.
	 *
	 * @return 	mixed 				instance of JObject or raw array.
	 * 
	 * @since 	1.15.0 (J) - 1.5.0 (WP)
	 */
	public function getReportOptions($registry = true)
	{
		if ($registry) {
			return new JObject($this->options);
		}

		return $this->options;
	}

	/**
	 * Sets warning messages by concatenating the existing ones.
	 * Method used only by sub-classes.
	 *
	 * @param 	string 		$str
	 *
	 * @return 	self
	 */
	protected function setWarning($str)
	{
		$this->warning .= $str."\n";

		return $this;
	}

	/**
	 * Gets the current warning string.
	 *
	 * @return 	string
	 */
	public function getWarning()
	{
		return rtrim($this->warning, "\n");
	}

	/**
	 * Sets errors by concatenating the existing ones.
	 * Method used only by sub-classes.
	 *
	 * @param 	string 		$str
	 *
	 * @return 	self
	 */
	protected function setError($str)
	{
		$this->error .= $str."\n";

		return $this;
	}

	/**
	 * Gets the current error string.
	 *
	 * @return 	string
	 */
	public function getError()
	{
		return rtrim($this->error, "\n");
	}
}
