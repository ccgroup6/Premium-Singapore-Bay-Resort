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
 * Helper class to handle reminders.
 * 
 * @since 	1.15.0 (J) - 1.5.0 (WP)
 */
final class VBORemindersHelper extends JObject
{
	/**
	 * Proxy to construct the object.
	 * 
	 * @param 	array|object  $data  optional data to bind.
	 * 
	 * @return 	self
	 */
	public static function getInstance($data = array())
	{
		return new static($data);
	}

	/**
	 * Loads records from the db table for the reminders.
	 * 
	 * @param 	array 	$fetch 		optional query fetch options.
	 * @param 	int 	$offset 	optional query limit start.
	 * @param 	int 	$length 	optional query limit length.
	 * 
	 * @return 	array 				list of record objects, if any.
	 */
	public function loadReminders($fetch = [], $offset = 0, $length = 0)
	{
		$dbo = JFactory::getDbo();

		// build default fetch params
		$params = [
			'after' 	=> 'NOW',
			'before' 	=> null,
			'idorder' 	=> 0,
			'completed' => 0,
			'expired' 	=> 0,
		];
		if (is_array($fetch) && count($fetch)) {
			// merge fetch params
			$params = array_merge($params, $fetch);
		}

		// build query
		$clauses = [];
		$ordering = ["`duedate` ASC"];
		if (empty($params['expired']) && !empty($params['after']) && is_string($params['after'])) {
			if (!strcasecmp($params['after'], 'NOW')) {
				$clauses[] = "`duedate` >= NOW()";
			} else {
				// datetime string is expected
				$clauses[] = "`duedate` >= " . $dbo->quote($params['after']);
			}
		}
		if (!empty($params['before'])) {
			// set clause for max future date
			$clauses[] = "`duedate` <= " . $dbo->quote($params['before']);
		}
		if (empty($params['completed'])) {
			$clauses[] = "`completed` = 0";
		}
		if (!empty($params['idorder'])) {
			// exclude all reminders not for this booking
			$params['idorder'] = (int)$params['idorder'];
			$clauses[] = "`idorder` IN (0, {$params['idorder']})";
			// set ordering to display reminders for this booking on top
			array_unshift($ordering, "`idorder` DESC");
		}

		$q = "SELECT * FROM `#__vikbooking_reminders`" . (count($clauses) ? ' WHERE ' . implode(' AND ', $clauses) : '') . " ORDER BY " . implode(', ', $ordering);
		$dbo->setQuery($q, $offset, $length);
		$dbo->execute();
		if (!$dbo->getNumRows()) {
			return [];
		}
		$reminders = $dbo->loadObjectList();

		// decode payload on all records, if needed
		foreach ($reminders as $k => $reminder) {
			if (!empty($reminder->payload)) {
				$reminders[$k]->payload = json_decode($reminder->payload);
			}
		}

		return $reminders;
	}

	/**
	 * Returns a list of imminent reminders.
	 * 
	 * @param 	int 	$length 	the maximum records to fetch.
	 * 
	 * @return 	array 				list of object records, if any.
	 */
	public function getImminents($length = 10)
	{
		// imminents reminders are meant to not expire in more than 1 day
		$now_info = getdate();
		$lim_max_date = date('Y-m-d H:i:s', mktime(23, 59, 59, $now_info['mon'], ($now_info['mday'] + 1), $now_info['year']));

		$fetch = [
			'after' 	=> 'NOW',
			'before' 	=> $lim_max_date,
			'idorder' 	=> 0,
			'completed' => 0,
			'expired' 	=> 0,
		];

		return $this->loadReminders($fetch, 0, $length);
	}

	/**
	 * Gets a specific reminder by ID.
	 * 
	 * @param 	int 	$rid 	the record ID.
	 * 
	 * @return 	null|object
	 */
	public function getReminder($rid)
	{
		if (empty($rid)) {
			return null;
		}

		$dbo = JFactory::getDbo();

		$q = "SELECT * FROM `#__vikbooking_reminders` WHERE `id`=" . (int)$rid;
		$dbo->setQuery($q, 0, 1);
		$dbo->execute();
		if (!$dbo->getNumRows()) {
			return null;
		}

		return $dbo->loadObject();
	}

	/**
	 * Inserts a new reminder record object.
	 * 
	 * @param 	object 	&$reminder 	the record object to insert.
	 * 
	 * @return 	bool
	 */
	public function saveReminder(&$reminder)
	{
		if (!is_object($reminder) || !count(get_object_vars($reminder))) {
			$this->setError('Empty or invalid argument');
			return false;
		}

		$dbo = JFactory::getDbo();

		try {
			$dbo->insertObject('#__vikbooking_reminders', $reminder, 'id');
		} catch (Exception $e) {
			// do nothing
			$this->setError('The query to insert the record failed');
		}

		return (!empty($reminder->id));
	}

	/**
	 * Inserts a new reminder record object.
	 * 
	 * @param 	object 	$reminder 	the record object to insert.
	 * 
	 * @return 	bool
	 */
	public function updateReminder($reminder)
	{
		if (!is_object($reminder) || !count(get_object_vars($reminder))) {
			$this->setError('Empty or invalid argument');
			return false;
		}

		if (empty($reminder->id)) {
			$this->setError('Empty reminder id');
			return false;
		}

		$dbo = JFactory::getDbo();

		$res = false;

		try {
			$res = $dbo->updateObject('#__vikbooking_reminders', $reminder, 'id');
		} catch (Exception $e) {
			// do nothing
			$this->setError('The query to insert the record failed');
		}

		return $res;
	}

	/**
	 * Deletes an existing reminder record.
	 * 
	 * @param 	int|object 	$reminder 	the record to remove.
	 * 
	 * @return 	bool
	 */
	public function deleteReminder($reminder)
	{
		if (!is_numeric($reminder) && !is_object($reminder)) {
			return false;
		}

		$reminder_id = null;

		if (is_object($reminder) && !empty($reminder->id)) {
			$reminder_id = (int)$reminder->id;
		} elseif (is_numeric($reminder)) {
			$reminder_id = (int)$reminder;
		}

		if (empty($reminder_id)) {
			return false;
		}

		$dbo = JFactory::getDbo();

		$q = "DELETE FROM `#__vikbooking_reminders` WHERE `id`=" . $reminder_id;
		$dbo->setQuery($q);
		$dbo->execute();

		return ($dbo->getAffectedRows() > 0);
	}

	/**
	 * Removes the reminders with a due date in the past.
	 * 
	 * @return 	void
	 */
	public function removeExpired()
	{
		$dbo = JFactory::getDbo();

		$q = "DELETE FROM `#__vikbooking_reminders` WHERE `duedate` < NOW();";
		$dbo->setQuery($q);
		$dbo->execute();

		return;
	}

	/**
	 * Given two dates, compares the relative differences and returns the information.
	 * The language definitions are supposed to be loaded from the admin section.
	 * 
	 * @param 	string|DateTime 	$date_a 	the date to compare from.
	 * @param 	string|DateTime 	$date_b 	the date to compare against.
	 * 
	 * @return 	array 				false on failure, array with diff otherwise.
	 */
	public function relativeDatesDiff($date_a, $date_b = null)
	{
		if (is_string($date_a)) {
			$date_a = new DateTime($date_a);
		}

		if (!($date_a instanceof DateTime)) {
			$date_a = new DateTime();
		}

		if (is_string($date_b) || empty($date_b)) {
			// by default we compare against now
			if (empty($date_b)) {
				$date_b = new DateTime();
			} else {
				$date_b = new DateTime($date_b);
			}
		}

		if (!($date_b instanceof DateTime)) {
			$date_b = new DateTime();
		}

		// calculate close y-m-d dates
		$fromd_ymd = $date_a->format('Y-m-d');
		$today = date('Y-m-d');
		$yesterday = date('Y-m-d', strtotime('-1 day'));
		$tomorrow = date('Y-m-d', strtotime('+1 day'));

		// get the date interval object of differences
		$dt_interval = $date_a->diff($date_b);

		// compose the associative data to be returned
		$diff_data = [
			'past' 	  	=> ($date_a < $date_b),
			'sameday' 	=> ($fromd_ymd == $date_b->format('Y-m-d')),
			'today'   	=> ($fromd_ymd == $today),
			'yesterday' => ($fromd_ymd == $yesterday),
			'tomorrow' 	=> ($fromd_ymd == $tomorrow),
			'seconds' 	=> $dt_interval->s,
			'minutes' 	=> $dt_interval->i,
			'hours'   	=> $dt_interval->h,
			'days' 	  	=> $dt_interval->d,
			/**
			 * Rely on weeks only if less than a month for better precision.
			 * Weeks is the only value to not be calculated natively in DateInterval.
			 */
			'weeks'   	=> ($dt_interval->m > 0 ? 0 : floor($dt_interval->d / 7)),
			//
			'months'  	=> $dt_interval->m,
			'years'   	=> $dt_interval->y,
			// set the DateTime objects parsed
			'date_a' 	=> $date_a,
			'date_b' 	=> $date_b,
			// prepare the formatted relative difference string
			'relative' 	=> $fromd_ymd,
		];

		// build the relative difference string
		if ($diff_data['today']) {
			$diff_data['relative'] = JText::translate('VBTODAY');
		} elseif ($diff_data['yesterday']) {
			$diff_data['relative'] = JText::translate('VBOYESTERDAY');
		} elseif ($diff_data['tomorrow']) {
			$diff_data['relative'] = JText::translate('VBOTOMORROW');
		} elseif ($diff_data['years'] > 0) {
			// no translations available at the moment for the singular version of "year"
			$diff_num = $diff_data['years'] . ' ' . JText::translate('VBCONFIGSEARCHPMAXDATEYEARS');
			$diff_data['relative'] = JText::sprintf(($diff_data['past'] ? 'VBO_REL_EXP_PAST' : 'VBO_REL_EXP_FUTURE'), strtolower($diff_num));
		} elseif ($diff_data['months'] > 0) {
			$diff_num = $diff_data['months'] . ' ' . JText::translate(($diff_data['months'] > 1 ? 'VBCONFIGSEARCHPMAXDATEMONTHS' : 'VBPVIEWRESTRICTIONSTWO'));
			$diff_data['relative'] = JText::sprintf(($diff_data['past'] ? 'VBO_REL_EXP_PAST' : 'VBO_REL_EXP_FUTURE'), strtolower($diff_num));
		} elseif ($diff_data['weeks'] > 1) {
			// use weeks only if more than one for a better precision
			$diff_num = $diff_data['weeks'] . ' ' . JText::translate(($diff_data['weeks'] > 1 ? 'VBCONFIGSEARCHPMAXDATEWEEKS' : 'VBOWEEK'));
			$diff_data['relative'] = JText::sprintf(($diff_data['past'] ? 'VBO_REL_EXP_PAST' : 'VBO_REL_EXP_FUTURE'), strtolower($diff_num));
		} elseif ($diff_data['days'] > 0) {
			$diff_num = $diff_data['days'] . ' ' . JText::translate(($diff_data['days'] > 1 ? 'VBCONFIGSEARCHPMAXDATEDAYS' : 'VBODAY'));
			$diff_data['relative'] = JText::sprintf(($diff_data['past'] ? 'VBO_REL_EXP_PAST' : 'VBO_REL_EXP_FUTURE'), strtolower($diff_num));
		}

		return $diff_data;
	}
}
