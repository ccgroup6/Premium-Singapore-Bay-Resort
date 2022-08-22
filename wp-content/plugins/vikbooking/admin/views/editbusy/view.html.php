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

class VikBookingViewEditbusy extends JViewVikBooking {
	
	function display($tpl = null) {
		// Set the toolbar
		$this->addToolBar();

		$cid = VikRequest::getVar('cid', array(0));
		$oid = $cid[0];

		$dbo = JFactory::getDBO();
		$mainframe = JFactory::getApplication();
		if (empty($oid)) {
			VikError::raiseWarning('', 'Not Found');
			$mainframe->redirect("index.php?option=com_vikbooking&task=rooms");
			exit;
		}
		$q = "SELECT * FROM `#__vikbooking_orders` WHERE `id`=".(int)$oid.";";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() != 1) {
			VikError::raiseWarning('', JText::translate('VBPEDITBUSYONE'));
			$mainframe->redirect("index.php?option=com_vikbooking&task=rooms");
			exit;
		}
		$ord = $dbo->loadAssocList();
		$q = "SELECT `or`.*,`r`.`name`,`r`.`img`,`r`.`idopt`,`r`.`fromadult`,`r`.`toadult`,`r`.`fromchild`,`r`.`tochild` FROM `#__vikbooking_ordersrooms` AS `or`,`#__vikbooking_rooms` AS `r` WHERE `or`.`idorder`='".$ord[0]['id']."' AND `or`.`idroom`=`r`.`id` ORDER BY `or`.`id` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		$ordersrooms = $dbo->loadAssocList();
		$all_rooms = array();
		$q = "SELECT * FROM `#__vikbooking_rooms` ORDER BY `avail` DESC, `name` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$all_rooms = $dbo->loadAssocList();
		}
		$cpin = VikBooking::getCPinIstance();
		$customer = $cpin->getCustomerFromBooking($ord[0]['id']);
		if (count($customer) && !empty($customer['country'])) {
			if (file_exists(VBO_ADMIN_PATH.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'countries'.DIRECTORY_SEPARATOR.$customer['country'].'.png')) {
				$customer['country_img'] = '<img src="'.VBO_ADMIN_URI.'resources/countries/'.$customer['country'].'.png'.'" title="'.$customer['country'].'" class="vbo-country-flag vbo-country-flag-left"/>';
			}
		}
		
		$this->ordersrooms = &$ordersrooms;
		$this->ord = &$ord;
		$this->all_rooms = &$all_rooms;
		$this->customer = &$customer;
		
		// Display the template
		parent::display($tpl);
	}

	/**
	 * Sets the toolbar
	 */
	protected function addToolBar() {
		JToolBarHelper::title(JText::translate('VBMAINEBUSYTITLE'), 'vikbooking');
		$pfrominv = VikRequest::getInt('frominv', '', 'request');
		if ($pfrominv > 0 && JFactory::getUser()->authorise('core.edit', 'com_vikbooking')) {
			JToolBarHelper::apply( 'updatebusydoinv', JText::translate('VBSAVEANDDOINV'));
			JToolBarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.edit', 'com_vikbooking')) {
			JToolBarHelper::apply( 'updatebusy', JText::translate('VBSAVE'));
			JToolBarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.delete', 'com_vikbooking')) {
			JToolBarHelper::custom( 'removebusy', 'delete', 'delete', JText::translate('VBMAINEBUSYDEL'), false, false);
			JToolBarHelper::spacer();
		}
		$pgoto = VikRequest::getString('goto', '', 'request');
		if($pgoto == 'overv') {
			JToolBarHelper::custom( 'cancelbusy', 'back', 'back', JText::translate('VBOVIEWBOOKINGDET'), false, false);
		}
		JToolBarHelper::cancel( ($pgoto == 'overv' ? 'canceloverv' : 'cancelbusy'), JText::translate('VBBACK'));
		$pvcm = VikRequest::getInt('vcm', '', 'request');
		if($pvcm == 1) {
			JToolBarHelper::custom( 'cancelbusyvcm', 'back', 'back', JText::translate('VBBACKVCM'), false, false);
		}
		JToolBarHelper::spacer();
	}

}
