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

jimport('joomla.application.component.view');

class VikbookingViewOperators extends JViewVikBooking {
	function display($tpl = null) {
		VikBooking::prepareViewContent();

		$operator = VikBooking::getOperatorInstance()->getOperatorAccount();

		if ($operator === false) {
			// operator needs to log in (default_login.php)
			$tpl = 'login';
		} else {
			// operator is logged in (default_dashboard.php)
			$tpl = 'dashboard';
		}

		$this->operator = &$operator;

		parent::display($tpl);
	}
}
