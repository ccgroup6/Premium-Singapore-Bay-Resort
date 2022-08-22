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
 * This class is used to handle the operators.
 * 
 * @since 	1.11
 */
class VikBookingOperator
{
	/**
	 * The singleton instance of the class.
	 *
	 * @var VikBookingOperator
	 */
	protected static $instance = null;

	/**
	 * The database handler instance.
	 *
	 * @var object
	 */
	protected $dbo;

	/**
	 * Class constructor is protected.
	 *
	 * @see 	getInstance()
	 */
	protected function __construct()
	{
		$this->dbo = JFactory::getDbo();
	}

	/**
	 * Returns the global Tracker object, either
	 * a new instance or the existing instance
	 * if the class was already instantiated.
	 *
	 * @return 	self 	A new instance of the class.
	 */
	public static function getInstance()
	{
		if (is_null(static::$instance)) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Returns the list of all the operators.
	 *
	 * @return 	array 	The list of operators
	 */
	public function getAll()
	{
		$operators = array();
		
		$q = "SELECT * FROM `#__vikbooking_operators` ORDER BY `first_name` ASC, `last_name` ASC;";
		$this->dbo->setQuery($q);
		$this->dbo->execute();
		if ($this->dbo->getNumRows()) {
			$all = $this->dbo->loadAssocList();
			foreach ($all as $o) {
				$operators[$o['id']] = $o;
			}
		}

		return $operators;
	}

	/**
	 * Returns the list of all the operators filtered by a
	 * specific permission type and with an array of its permissions.
	 * 
	 * @param 	string 	$permtype 	the type of permission to look for
	 * @param 	array 	[$list] 	the list of operators
	 *
	 * @return 	array 	The list of operators
	 */
	public function getOperatorsFromPermissions($permtype, $list = array())
	{
		if (!count($list)) {
			$list = $this->getAll();
		}

		$operators = $list;

		foreach ($operators as $k => $v) {
			$perms = !empty($v['perms']) ? json_decode($v['perms'], true) : array();
			$perms = !is_array($perms) ? array() : $perms;
			$enabled = false;
			foreach ($perms as $perm) {
				if (isset($perm['type']) && $perm['type'] == $permtype) {
					$operators[$k]['perms'] = $perm['perms'];
					$enabled = true;
					break;
				}
			}
			if (!$enabled) {
				unset($operators[$k]);
			}
		}

		return $operators;
	}

	/**
	 * Tells whether the operator is logged in by renewing
	 * the session cookie if the auth method is through code.
	 * Returns the information of the logged in operator or false.
	 *
	 * @return 	mixed 	Array if the operator is logged in, false otherwise.
	 */
	public function getOperatorAccount()
	{
		$user = JFactory::getUser();
		
		/**
		 * @wponly 	because of the magic method __get() in JUser, we should not
		 * let empty evaluate an internal property of the $user object, or this
		 * will return false, even if the property is actually not empty.
		 * Do not use !empty($user->id) or this will be false even if the user
		 * is actually logged in. Assign $user->id to a variable instead.
		 */
		$ujid = $user->id;

		if (!$user->guest && !empty($ujid)) {
			$q = "SELECT * FROM `#__vikbooking_operators` WHERE `ujid`=".(int)$user->id." LIMIT 1;";
			$this->dbo->setQuery($q);
			$this->dbo->execute();
			if ($this->dbo->getNumRows()) {
				// user is logged in to the site account
				return $this->dbo->loadAssoc();
			}
		}
		
		$session = JFactory::getSession();
		$sessval = $session->get('vboOpFngpt', '');
		$cookie  = JFactory::getApplication()->input->cookie;
		$cksess  = $cookie->get('vboOpFngpt', '', 'string');

		if (!empty($sessval)) {
			// look up for this fingerprint in the session
			$q = "SELECT * FROM `#__vikbooking_operators` WHERE `fingpt`=".$this->dbo->quote($sessval)." LIMIT 1;";
			$this->dbo->setQuery($q);
			$this->dbo->execute();
			if ($this->dbo->getNumRows()) {
				// user is logged in through the session
				return $this->dbo->loadAssoc();
			}
		}

		if (!empty($cksess)) {
			// look up for this fingerprint in the cookie
			$q = "SELECT * FROM `#__vikbooking_operators` WHERE `fingpt`=".$this->dbo->quote($cksess)." LIMIT 1;";
			$this->dbo->setQuery($q);
			$this->dbo->execute();
			if ($this->dbo->getNumRows()) {
				// user is logged in through the cookie, renew it for 2 weeks before returning the array
				VikRequest::setCookie('vboOpFngpt', $cksess, (time() + (86400 * 14)), '/', '', false, true);
				return $this->dbo->loadAssoc();
			}
		}

		// user is not logged in
		return false;
	}

	/**
	 * Login request through the authentication code.
	 * If code is valid, the session and cookie are set.
	 *
	 * @param 	string 		$code 	the authentication code to check
	 *
	 * @return 	boolean 	true if the code exists, false otherwise
	 */
	public function authOperator($code)
	{
		if (empty($code)) {
			return false;
		}

		$session = JFactory::getSession();
		$cookie  = JFactory::getApplication()->input->cookie;

		$q = "SELECT * FROM `#__vikbooking_operators` WHERE `code`=".$this->dbo->quote($code)." LIMIT 1;";
		$this->dbo->setQuery($q);
		$this->dbo->execute();
		if ($this->dbo->getNumRows()) {
			// valid code provided
			$operator = $this->dbo->loadAssoc();
			
			// send cookie
			VikRequest::setCookie('vboOpFngpt', $operator['fingpt'], (time() + (86400 * 14)), '/', '', false, true);
			// update session value
			$session->set('vboOpFngpt', $operator['fingpt']);

			return true;
		}

		// invalid code provided
		return false;
	}

	/**
	 * Unsets the session value and sends an expired cookie.
	 * This action completely logs out an operator that should log back in.
	 *
	 * @return 	void
	 */
	public function logoutOperator()
	{
		$app 	 = JFactory::getApplication();
		$session = JFactory::getSession();
		$cookie  = $app->input->cookie;

		$session->set('vboOpFngpt', '');
		VikRequest::setCookie('vboOpFngpt', $operator['fingpt'], (time() - (86400 * 14)), '/', '', false, true);

		// logout from the site
		$app->logout(JFactory::getUser()->id);
	}

	/**
	 * Checks whether the given operator has the permissions to access
	 * the given view type (permission type). Permissions are converted
	 * into an array if still JSON encoded, and updated via reference.
	 * 
	 * @param 	array 		$operator 		the operator record
	 * @param 	string 		$permtype 		the name of the view to access
	 *
	 * @return 	boolean
	 */
	public function checkPermissions(&$operator, $permtype)
	{
		if (!is_array($operator) || !count($operator) || empty($operator['perms'])) {
			return false;
		}

		if (is_scalar($operator['perms'])) {
			$operator['perms'] = json_decode($operator['perms'], true);
			$operator['perms'] = is_array($operator['perms']) ? $operator['perms'] : array();
		}

		foreach ($operator['perms'] as $k => $perm) {
			if (isset($perm['type']) && $perm['type'] == $permtype) {
				// has permission for this View, take only these perms
				$operator['perms'] = $operator['perms'][$k]['perms'];
				return true;
			}
		}

		// permission not found
		return false;
	}

}
