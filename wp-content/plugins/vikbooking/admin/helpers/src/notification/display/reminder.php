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
 * Browser notification displayer handler for reminders.
 * 
 * @since 	1.15.0 (J) - 1.5.0 (WP)
 */
final class VBONotificationDisplayReminder extends JObject implements VBONotificationDisplayer
{
	/**
	 * Proxy for immediately getting the object and bind data.
	 * 
	 * @param 	array|object 	$data 	the notification payload data to bind.
	 */
	public static function getInstance($data = null)
	{
		return new static($data);
	}

	/**
	 * Composes an object with the necessary properties to display
	 * the notification in the browser. In case of errors while
	 * retrieving the reminder, the response is closed. Therefore,
	 * it's assumed that this process will run during an AJAX request.
	 * 
	 * @return 	null|object 	the notification display data payload.
	 */
	public function getData()
	{
		$reminder_id = $this->get('id');
		if (empty($reminder_id)) {
			VBOHttpDocument::getInstance()->close(500, 'Empty notification reminder id to display');
		}

		$reminder = VBORemindersHelper::getInstance()->getReminder($reminder_id);
		if (!$reminder) {
			VBOHttpDocument::getInstance()->close(404, 'Notification reminder id not found');
		}

		// compose the notification data to display
		$notif_data = new stdClass;
		$notif_data->title 	 = $reminder->title;
		$notif_data->message = empty($reminder->descr) ? $reminder->title : $reminder->descr;
		$notif_data->icon 	 = $this->getIconUrl();

		// check if click handler should be attached
		if (!empty($reminder->idorder)) {
			// register click event callback data
			$notif_data->onclick = 'VBOCore.handleGoto';
			$notif_data->gotourl = 'index.php?option=com_vikbooking&task=editorder&cid[]=' . $reminder->idorder;
			if (defined('ABSPATH')) {
				$notif_data->gotourl = str_replace('index.php', 'admin.php', $notif_data->gotourl);
			}
		} else {
			$notif_data->onclick = null;
		}

		return $notif_data;
	}

	/**
	 * Returns the URL to the default icon for the reminders
	 * browser notifications. Custom logos are preferred.
	 * 
	 * @return 	string|null
	 */
	private function getIconUrl()
	{
		$config = VBOFactory::getConfig();

		// back-end custom logo
		$use_logo = $config->get('backlogo');
		if (empty($use_logo) || !strcasecmp($use_logo, 'vikbooking.png')) {
			// fallback to company (site) logo
			$use_logo = $config->get('sitelogo');
		}

		if (!empty($use_logo) && strcasecmp($use_logo, 'vikbooking.png')) {
			// uploaded logo found
			$use_logo = VBO_ADMIN_URI . 'resources/' . $use_logo;
		} else {
			$use_logo = null;
		}

		return $use_logo;
	}
}
