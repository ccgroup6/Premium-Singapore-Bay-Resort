<?php
/** 
 * @package     VikBooking
 * @subpackage  core
 * @author      Alessio Gaggii - E4J s.r.l.
 * @copyright   Copyright (C) 2022 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

/**
 * Multitask data extraction for admin widgets.
 * 
 * @since 	1.5.0
 */
final class VBOMultitaskParser
{
	/**
	 * The singleton instance of the class.
	 *
	 * @var  VBOMultitaskFactory
	 */
	private static $instance = null;

	/**
	 * The view/task where multitask is being used.
	 * 
	 * @var  string
	 */
	private $page_name = '';

	/**
	 * The page URI where multitask is being used.
	 * 
	 * @var  string
	 */
	private $page_uri = '';

	/**
	 * The page query values.
	 * 
	 * @var  array
	 */
	private $page_query = [];

	/**
	 * Returns the global object to immediately support chaining.
	 * Either a new instance or the existing instance.
	 * 
	 * @param 	string 	$page 	the name of the view/task.
	 * @param 	string 	$uri 	the page URI to parse.
	 *
	 * @return 	self
	 */
	public static function getInstance($page = '', $uri = '')
	{
		if (is_null(static::$instance)) {
			static::$instance = new static($page, $uri);
		}

		return static::$instance;
	}

	/**
	 * Class constructor.
	 * 
	 * @param 	string 	$page 	the name of the view/task.
	 * @param 	string 	$uri 	the page URI to parse.
	 */
	public function __construct($page = '', $uri = '')
	{
		// set name for view/task to consider
		$this->setPageName($page);

		// set URI to parse
		$this->setPageURI($uri);
	}

	/**
	 * Builds a multitask data object with the page information
	 * ready to be passed to the admin widget being rendered.
	 * 
	 * @return 	VBOMultitaskData
	 */
	public function getData()
	{
		// get a new multitask data object instance
		$multitask_data = new VBOMultitaskData;

		// inject current page values
		$multitask_data->setPage($this->getPageName());
		$multitask_data->setURI($this->getPageURI());
		$multitask_data->setQuery($this->getPageQueryValues());

		// check if the page has a booking
		$page_booking_id = $this->getPageBookingId();
		if ($page_booking_id) {
			$multitask_data->setBookingId($page_booking_id);
		}

		// return the data object for the admin widget
		return $multitask_data;
	}

	/**
	 * Gets the current name of the view/task.
	 * 
	 * @return 	string
	 */
	public function getPageName()
	{
		return $this->page_name;
	}

	/**
	 * Sets the current name of the view/task.
	 * 
	 * @param 	string 	$page 	the name of the view/task.
	 * 
	 * @return 	self
	 */
	public function setPageName($page = '')
	{
		$this->page_name = $page;

		return $this;
	}

	/**
	 * Gets the current page URI.
	 * 
	 * @return 	string
	 */
	public function getPageURI()
	{
		return $this->page_uri;
	}

	/**
	 * Sets the current page URI.
	 * 
	 * @param 	string 	$uri 	the page URI to parse
	 * 
	 * @return 	self
	 */
	public function setPageURI($uri = '')
	{
		$this->page_uri = $uri;

		// parse current URI
		$this->parseCurrentURI();

		return $this;
	}

	/**
	 * Gets the current values for the page query.
	 * 
	 * @return 	array
	 */
	public function getPageQueryValues()
	{
		return $this->page_query;
	}

	/**
	 * Parses the current URI to detect values to extract.
	 * 
	 * @return 	void
	 */
	private function parseCurrentURI()
	{
		// reset query values
		$this->page_query = [];

		if (empty($this->page_uri)) {
			// nothing to parse
			return;
		}

		// parse URL data
		$uri_data = parse_url($this->page_uri);

		if (!isset($uri_data['query'])) {
			// no query values to parse
			return;
		}

		// parse query variables
		$uri_data['query'] = str_replace('&amp;', '&', $uri_data['query']);

		// inject parsed string values in page_query
		parse_str($uri_data['query'], $this->page_query);

		// adjust values, if needed
		if (empty($this->page_name)) {
			// try guessing the page from the query values
			if (!empty($this->page_query['view'])) {
				$this->page_name = $this->page_query['view'];
			} elseif (!empty($this->page_query['task'])) {
				$this->page_name = $this->page_query['task'];
			}
		}
	}

	/**
	 * Checks whether the page contains a booking.
	 * 
	 * @return 	bool|int 	false on failure or reservation ID.
	 */
	private function getPageBookingId()
	{
		if (!is_array($this->page_query) || !count($this->page_query)) {
			return false;
		}

		// list of pages that can contain a reservation
		$pages_with_booking = [
			'editorder',
			'editbusy',
			'bookingcheckin',
		];

		if (!in_array($this->page_name, $pages_with_booking)) {
			return false;
		}

		if (!isset($this->page_query['cid']) || !is_array($this->page_query['cid'])) {
			return false;
		}

		if (empty($this->page_query['cid'][0])) {
			return false;
		}

		// booking ID found
		return (int)$this->page_query['cid'][0];
	}
}
