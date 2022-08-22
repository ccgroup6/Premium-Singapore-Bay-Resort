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
 * Defines an abstract adapter to extend the browser notification data model.
 * 
 * @since 	1.15.0 (J) - 1.5.0 (WP)
 */
abstract class VBONotificationAdapter extends JObject implements VBONotificationData
{
	/**
	 * The type of the scheduled notification.
	 * 
	 * @var 	string
	 */
	protected $_notification_type = '';

	/**
	 * The default admin endpoint for displaying (building) a notification.
	 * 
	 * @var 	string
	 */
	protected $_notif_display_url = 'index.php?option=com_vikbooking&task=notification_displayer';

	/**
	 * Proxy to immediately access the object.
	 * 
	 * @param 	object|array 	$data 	optional data to bind.
	 * 
	 * @return 	self
	 */
	public static function getInstance($data = [])
	{
		return new static($data);
	}

	/**
	 * Gets the type of notification.
	 * 
	 * @return 	string
	 */
	public function getType()
	{
		return $this->_notification_type;
	}

	/**
	 * Gets the notification expiration date time.
	 * 
	 * @return 	string 	"now" by default for no scheduling.
	 */
	public function getDateTime()
	{
		return (string)$this->get('_date_time', 'now');
	}

	/**
	 * Sets the notification expiration date time.
	 * 
	 * @param 	string|DateTime 	$dtime
	 * 
	 * @return 	self
	 */
	public function setDateTime($dtime)
	{
		if ($dtime instanceof DateTime) {
			$dtime = $dtime->format('Y-m-d H:i:s');
		}
		// this sets a "reserved" property starting with "_"
		$this->set('_date_time', $dtime);

		return $this;
	}

	/**
	 * Accesses the flag to declare a date as with no time.
	 * 
	 * @return 	bool 	true if time is disabled.
	 */
	public function getNoTime()
	{
		return (bool)$this->set('_no_time', false);
	}

	/**
	 * Controls the flag to declare a date as with no time.
	 * 
	 * @param 	bool 	$set 	true to disable time.
	 * 
	 * @return 	self
	 */
	public function setNoTime($set = true)
	{
		// this sets a "reserved" property starting with "_"
		$this->set('_no_time', (bool)$set);

		return $this;
	}

	/**
	 * Returns the notification data object to be encoded.
	 * 
	 * @return 	object 	the notification data object for the document.
	 */
	public function toDataObject()
	{
		// access all "public" (not reserved) properties
		$props = $this->getProperties($public = true);

		// build data object
		$data = (object)$props;
		$data->type  = $this->getType();
		$data->dtime = $this->getDateTime();
		if ($this->getNoTime()) {
			$data->no_time = 1;
		}
		$data->build_url = $this->generateBuildUrl();

		return $data;
	}

	/**
	 * Extending classes can override this method when a notification does
	 * NOT require scheduling or building, but rather instant display data.
	 * 
	 * @return 	bool 	true if display data were built.
	 */
	public function buildDisplayData()
	{
		return false;
	}

	/**
	 * Returns the URL to build the notification display data.
	 * 
	 * @return 	null|string 	the url to build the notification display data.
	 */
	abstract protected function generateBuildUrl();
}
