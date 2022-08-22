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
 * Builds a browser notification data object for a reminder.
 * 
 * @since 	1.15.0 (J) - 1.5.0 (WP)
 */
final class VBONotificationDataReminder extends VBONotificationAdapter
{
	/**
	 * The type of the scheduled notification.
	 * 
	 * @var 	string
	 */
	protected $_notification_type = 'reminder';

	/**
	 * Gets the reminder ID.
	 * 
	 * @return 	int  the reminder record ID.
	 */
	public function getReminderId()
	{
		return (int)$this->get('id', 0);
	}

	/**
	 * Sets a "non-reserved" (public) object property for the reminder ID.
	 * 
	 * @param 	int 	$id 	the reminder record ID.
	 * 
	 * @return 	self
	 */
	public function setReminderId($id)
	{
		$this->set('id', (int)$id);

		return $this;
	}

	/**
	 * Returns the URL to build the notification display data.
	 * Method is declared as protected.
	 * Checks if the reminder exists and returns the default build URL.
	 * 
	 * @return 	null|string 	the url to build the notification display data.
	 */
	protected function generateBuildUrl()
	{
		$reminder_id = $this->getReminderId();

		if (empty($reminder_id)) {
			return null;
		}

		return VikBooking::ajaxUrl($this->_notif_display_url);
	}
}
