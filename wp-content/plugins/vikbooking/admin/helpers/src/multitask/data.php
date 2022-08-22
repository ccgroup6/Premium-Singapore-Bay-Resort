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
 * Defines a data registry container for the admin widgets
 * being rendered in the multitask panel for encapsulation.
 * 
 * @since 	1.15.0 (J) - 1.5.0 (WP)
 */
final class VBOMultitaskData extends JObject
{
	/**
	 * Gets the current name of the view/task.
	 * 
	 * @return 	string
	 */
	public function getPage()
	{
		return $this->get('page_name', null);
	}

	/**
	 * Sets the current name of the view/task.
	 * 
	 * @param 	string 	$page 	the name of the view/task.
	 * 
	 * @return 	self
	 */
	public function setPage($page = '')
	{
		$this->set('page_name', (string)$page);

		return $this;
	}

	/**
	 * Gets the current page URI.
	 * 
	 * @return 	string
	 */
	public function getURI()
	{
		return $this->get('page_uri', null);
	}

	/**
	 * Sets the current page URI.
	 * 
	 * @param 	string 	$uri 	the current page URI.
	 * 
	 * @return 	self
	 */
	public function setURI($uri = '')
	{
		$this->set('page_uri', (string)$uri);

		return $this;
	}

	/**
	 * Gets the current page query.
	 * 
	 * @return 	array
	 */
	public function getQuery()
	{
		return $this->get('page_query', []);
	}

	/**
	 * Sets the current page query.
	 * 
	 * @param 	array 	$query 	the current page query.
	 * 
	 * @return 	self
	 */
	public function setQuery($query = array())
	{
		$this->set('page_query', (array)$query);

		return $this;
	}

	/**
	 * Gets the current booking id.
	 * 
	 * @return 	int 	the booking id.
	 */
	public function getBookingId()
	{
		return $this->get('booking_id', 0);
	}

	/**
	 * Sets the current booking id.
	 * 
	 * @param 	int 	$bid 	the reservation id.
	 * 
	 * @return 	self
	 */
	public function setBookingId($bid = 0)
	{
		$this->set('booking_id', (int)$bid);

		return $this;
	}
}
