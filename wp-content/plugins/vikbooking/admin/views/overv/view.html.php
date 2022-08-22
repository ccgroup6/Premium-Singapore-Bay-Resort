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

class VikBookingViewOverv extends JViewVikBooking {
	
	function display($tpl = null) {
		// Set the toolbar
		$this->addToolBar();

		$dbo = JFactory::getDbo();
		if (file_exists(VCM_ADMIN_PATH.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'vcm-channels.css')) {
			$document = JFactory::getDocument();
			$document->addStyleSheet(VCM_ADMIN_URI.'assets/css/vcm-channels.css');
		}
		$session = JFactory::getSession();
		$cookie = JFactory::getApplication()->input->cookie;
		$pmonth = VikRequest::getString('month', '', 'request');
		$pmnum = VikRequest::getInt('mnum', '', 'request');
		$cmnum = $cookie->get('vbOvwMnum', '', 'string');
		$punits_show_type = VikRequest::getString('units_show_type', '', 'request');
		// category filter
		$pcategory_id = VikRequest::getString('category_id', '', 'request');
		$scategory_id = $session->get('vbOvwCatid', 0);
		$pcategory_id = !strlen($pcategory_id) && !empty($scategory_id) ? $scategory_id : $pcategory_id;
		$session->set('vbOvwCatid', $pcategory_id);
		//
		
		$pbmode = VikRequest::getString('bmode', '', 'request');
		if (!empty($pbmode) && ($pbmode == 'classic' || $pbmode == 'tags')) {
			VikRequest::setCookie('vbTagsMode', $pbmode, (time() + (86400 * 365)), '/');
			$session->set('vbTagsMode', $pbmode);
		} else {
			$cbmode = $cookie->get('vbTagsMode', '', 'string');
			$pbmode = (!empty($cbmode) && ($cbmode == 'classic' || $cbmode == 'tags') ? $cbmode : 'classic');
			VikRequest::setCookie('vbTagsMode', $pbmode, (time() + (86400 * 365)), '/');
			$session->set('vbTagsMode', $pbmode);
		}	
		
		if (!empty($punits_show_type)) {
			$session->set('vbUnitsShowType', $punits_show_type);
		}
		if (empty($pmonth)) {
			$sess_month = $session->get('vbOverviewMonth', '');
			if (!empty($sess_month)) {
				$pmonth = $sess_month;
			}
		}
		if (intval($cmnum) > 0 && empty($pmnum)) {
			$pmnum = $cmnum;
		}
		if ($pmnum > 0) {
			VikRequest::setCookie('vbOvwMnum', $pmnum, (time() + (86400 * 365)), '/');
			$session->set('vbOvwMnum', $pmnum);
		} else {
			$smnum = $session->get('vbOvwMnum', '1');
			$pmnum = intval($smnum) > 0 ? $smnum : 1;
		}
		//Remove expired locked records
		$q = "DELETE FROM `#__vikbooking_tmplock` WHERE `until`<" . time() . ";";
		$dbo->setQuery($q);
		$dbo->execute();
		//
		$oldest_checkin = 0;
		$furthest_checkout = 0;
		$q = "SELECT `checkin` FROM `#__vikbooking_busy` ORDER BY `checkin` ASC LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$oldest_checkin = $dbo->loadResult();
		}
		$q = "SELECT `checkout` FROM `#__vikbooking_busy` ORDER BY `checkout` DESC LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$furthest_checkout = $dbo->loadResult();
		}
		if (!empty($pmonth)) {
			$session->set('vbOverviewMonth', $pmonth);
			$tsstart = $pmonth;
		} else {
			$oggid = getdate();
			$tsstart = mktime(0, 0, 0, $oggid['mon'], 1, $oggid['year']);
		}
		$oggid = getdate($tsstart);
		$tsend = mktime(0, 0, 0, ($oggid['mon'] + $pmnum), 1, $oggid['year']);
		$today = getdate();
		$firstmonth = mktime(0, 0, 0, $today['mon'], 1, $today['year']);
		$wmonthsel = "<select name=\"month\" onchange=\"document.vboverview.submit();\">\n";
		if (!empty($oldest_checkin)) {
			$oldest_date = getdate($oldest_checkin);
			$oldest_month = mktime(0, 0, 0, $oldest_date['mon'], 1, $oldest_date['year']);
			if ($oldest_month < $firstmonth) {
				while ($oldest_month < $firstmonth) {
					$wmonthsel .= "<option value=\"".$oldest_month."\"".($oldest_month == $tsstart ? " selected=\"selected\"" : "").">".VikBooking::sayMonth($oldest_date['mon'])." ".$oldest_date['year']."</option>\n";
					if ($oldest_date['mon']==12) {
						$nextmon = 1;
						$year = $oldest_date['year'] + 1;
					} else {
						$nextmon = $oldest_date['mon'] + 1;
						$year = $oldest_date['year'];
					}
					$oldest_month = mktime(0, 0, 0, $nextmon, 1, $year);
					$oldest_date = getdate($oldest_month);
				}
			}
		}
		$wmonthsel .= "<option value=\"".$firstmonth."\"".($firstmonth == $tsstart ? " selected=\"selected\"" : "").">".VikBooking::sayMonth($today['mon'])." ".$today['year']."</option>\n";
		$futuremonths = 12;
		if (!empty($furthest_checkout)) {
			$furthest_date = getdate($furthest_checkout);
			$furthest_month = mktime(0, 0, 0, $furthest_date['mon'], 1, $furthest_date['year']);
			if ($furthest_month > $firstmonth) {
				$monthsdiff = floor(($furthest_month - $firstmonth) / (86400 * 30));
				$futuremonths = $monthsdiff > $futuremonths ? $monthsdiff : $futuremonths;
			}
		}
		for ($i = 1; $i <= $futuremonths; $i++) {
			$newts = getdate($firstmonth);
			if ($newts['mon'] == 12) {
				$nextmon = 1;
				$year = $newts['year'] + 1;
			} else {
				$nextmon = $newts['mon'] + 1;
				$year = $newts['year'];
			}
			$firstmonth = mktime(0, 0, 0, $nextmon, 1, $year);
			$newts = getdate($firstmonth);
			$wmonthsel .= "<option value=\"".$firstmonth."\"".($firstmonth==$tsstart ? " selected=\"selected\"" : "").">".VikBooking::sayMonth($newts['mon'])." ".$newts['year']."</option>\n";
		}
		$wmonthsel .= "</select>\n";
		$mainframe = JFactory::getApplication();
		$lim = $mainframe->getUserStateFromRequest("com_vikbooking.limit", 'limit', $mainframe->get('list_limit'), 'int');
		$lim0 = VikRequest::getVar('limitstart', 0, '', 'int');
		/**
		 * Filter by category
		 * 
		 * @since 	1.13
		 */
		$catfilter = !empty($pcategory_id) ? "(`r`.`idcat`='" . $pcategory_id . ";' OR `r`.`idcat` LIKE '" . $pcategory_id . ";%' OR `r`.`idcat` LIKE '%;" . $pcategory_id . ";%' OR `r`.`idcat` LIKE '%;" . $pcategory_id . ";')" : "";
		//
		$q = "SELECT SQL_CALC_FOUND_ROWS `r`.* FROM `#__vikbooking_rooms` AS `r`" . (!empty($catfilter) ? " WHERE " . $catfilter : '') . " ORDER BY `r`.`name` ASC";
		$dbo->setQuery($q, $lim0, $lim);
		$dbo->execute();
		if ($dbo->getNumRows() < 1) {
			VikError::raiseWarning('', JText::translate('VBOVERVIEWNOROOMS'));
			$mainframe = JFactory::getApplication();
			$mainframe->redirect("index.php?option=com_vikbooking");
			exit;
		}
		$rows = $dbo->loadAssocList();
		$dbo->setQuery('SELECT FOUND_ROWS();');
		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $dbo->loadResult(), $lim0, $lim );
		$navbut = "<table align=\"center\"><tr><td>".$pageNav->getListFooter()."</td></tr></table>";
		$arrbusy = array();
		$actnow = time();
		$arrbusy['tmplock'] = array();
		foreach ($rows as $r) {
			$q = "SELECT `b`.*,`ob`.`idorder`,`o`.`closure` FROM `#__vikbooking_busy` AS `b` 
				LEFT JOIN `#__vikbooking_ordersbusy` AS `ob` ON `b`.`id`=`ob`.`idbusy` 
				LEFT JOIN `#__vikbooking_orders` AS `o` ON `ob`.`idorder`=`o`.`id` 
				WHERE `b`.`idroom`=" . (int)$r['id'] . " AND (`b`.`checkin`>=" . $tsstart . " OR `b`.`checkout`>=" . $tsstart . ") AND (`b`.`checkin`<=" . $tsend . " OR `b`.`checkout`<=" . $tsstart . ");";
			$dbo->setQuery($q);
			$dbo->execute();
			$arrbusy[$r['id']] = $dbo->getNumRows() ? $dbo->loadAssocList() : [];
			//locked (stand-by) records - new in VBO 1.9
			$q = "SELECT `l`.*,`or`.`idroom` FROM `#__vikbooking_tmplock` AS `l` LEFT JOIN `#__vikbooking_ordersrooms` AS `or` ON `l`.`idorder`=`or`.`idorder` WHERE `or`.`idroom`='".$r['id']."' AND (`l`.`checkin`>=".$tsstart." OR `l`.`checkout`>=".$tsstart.") AND (`l`.`checkin`<=".$tsend." OR `l`.`checkout`<=".$tsstart.") AND `l`.`until`>=".$actnow.";";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() > 0) {
				$arrbusy['tmplock'][$r['id']] = $dbo->loadAssocList();
			}
			//
		}

		/**
		 * Filter by category
		 * 
		 * @since 	1.13
		 */
		$categories = array();
		$q = "SELECT `id`,`name` FROM `#__vikbooking_categories` ORDER BY `name` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$allcats = $dbo->loadAssocList();
			foreach ($allcats as $cat) {
				$categories[$cat['id']] = $cat['name'];
			}
		}
		//

		/**
		 * Check the next festivities periodically
		 * 
		 * @since 	1.12.0
		 */
		$fests = VikBooking::getFestivitiesInstance();
		if ($fests->shouldCheckFestivities()) {
			$fests->storeNextFestivities();
		}
		$festivities = $fests->loadFestDates(date('Y-m-d', $tsstart), date('Y-m-d', $tsend));

		/**
		 * Load room day notes from first month
		 * 
		 * @since 	1.13.5
		 */
		$rdaynotes = VikBooking::getCriticalDatesInstance()->loadRoomDayNotes(date('Y-m-d', $tsstart), date('Y-m-d', $tsend));
		//
		
		$this->rows = &$rows;
		$this->arrbusy = &$arrbusy;
		$this->wmonthsel = &$wmonthsel;
		$this->tsstart = &$tsstart;
		$this->festivities = &$festivities;
		$this->rdaynotes = &$rdaynotes;
		$this->lim0 = &$lim0;
		$this->navbut = &$navbut;
		$this->categories = &$categories;
		
		// Display the template
		parent::display($tpl);
	}

	/**
	 * Sets the toolbar
	 */
	protected function addToolBar() {
		JToolBarHelper::title(JText::translate('VBMAINOVERVIEWTITLE'), 'vikbooking');
		JToolBarHelper::cancel( 'canceledorder', JText::translate('VBBACK'));
		JToolBarHelper::spacer();
	}

}
