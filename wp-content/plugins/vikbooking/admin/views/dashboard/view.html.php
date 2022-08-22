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

// import Joomla view library
jimport('joomla.application.component.view');

class VikBookingViewDashboard extends JViewVikBooking {
	
	function display($tpl = null) {
		// Set the toolbar
		$this->addToolBar();

		/**
		 * @wponly - trigger back up of extendable files
		 */
		VikBookingLoader::import('update.manager');
		VikBookingUpdateManager::triggerExtendableClassesBackup('languages', "/^.+\-((?!en_US|it_IT).)+$/");
		//
		
		$dbo = JFactory::getDbo();

		$q = "SELECT COUNT(*) FROM `#__vikbooking_prices`;";
		$dbo->setQuery($q);
		$dbo->execute();
		$totprices = $dbo->loadResult();

		$q = "SELECT COUNT(*) FROM `#__vikbooking_rooms`;";
		$dbo->setQuery($q);
		$dbo->execute();
		$totrooms = $dbo->loadResult();

		$q = "SELECT COUNT(*) FROM `#__vikbooking_dispcost`;";
		$dbo->setQuery($q);
		$dbo->execute();
		$totdailyfares = $dbo->loadResult();
		
		$arrayfirst = array(
			'totprices' => $totprices,
			'totrooms' => $totrooms,
			'totdailyfares' => $totdailyfares
		);
		
		$this->arrayfirst = &$arrayfirst;
		
		// Display the template
		parent::display($tpl);
	}

	/**
	 * Sets the toolbar
	 */
	protected function addToolBar() {
		JToolBarHelper::title(JText::translate('VBMAINDASHBOARDTITLE'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.admin', 'com_vikbooking')) {
			JToolBarHelper::preferences('com_vikbooking');

			/**
			 * @wponly
			 */
			JToolBarHelper::shortcodes('com_vikbooking');
		}		
	}

}
