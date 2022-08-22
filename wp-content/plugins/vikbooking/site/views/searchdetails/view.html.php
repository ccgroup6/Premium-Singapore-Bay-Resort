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

class VikbookingViewSearchdetails extends JViewVikBooking {
	function display($tpl = null) {
		$vbo_tn = VikBooking::getTranslator();
		$proomid = VikRequest::getInt('roomid', '', 'request');
		$pcheckin = VikRequest::getInt('checkin', '', 'request');
		$pcheckout = VikRequest::getInt('checkout', '', 'request');
		$padults = VikRequest::getInt('adults', '', 'request');
		$pchildren = VikRequest::getInt('children', '', 'request');
		$dbo = JFactory::getDBO();
		$q = "SELECT * FROM `#__vikbooking_rooms` WHERE `id`=".$dbo->quote($proomid)." AND `avail`='1';";
		$dbo->setQuery($q);
		$dbo->execute();
		if($dbo->getNumRows() == 1) {
			$room=$dbo->loadAssocList();
			$vbo_tn->translateContents($room, '#__vikbooking_rooms');
			$secdiff = $pcheckout - $pcheckin;
			$daysdiff = $secdiff / 86400;
			if (is_int($daysdiff)) {
				if ($daysdiff < 1) {
					$daysdiff = 1;
				}
			} else {
				if ($daysdiff < 1) {
					$daysdiff = 1;
				} else {
					$sum = floor($daysdiff) * 86400;
					$newdiff = $secdiff - $sum;
					$maxhmore = VikBooking::getHoursMoreRb() * 3600;
					if ($maxhmore >= $newdiff) {
						$daysdiff = floor($daysdiff);
					}else {
						$daysdiff = ceil($daysdiff);
					}
				}
			}
			$q="SELECT * FROM `#__vikbooking_dispcost` WHERE `idroom`='".$room[0]['id']."' AND `days`='".$daysdiff."' ORDER BY `#__vikbooking_dispcost`.`cost` ASC;";
			$dbo->setQuery($q);
			$dbo->execute();
			if($dbo->getNumRows() > 0) {
				$tar=$dbo->loadAssocList();
			}else {
				$q="SELECT * FROM `#__vikbooking_dispcost` WHERE `idroom`='".$room[0]['id']."' ORDER BY `#__vikbooking_dispcost`.`cost` ASC;";
				$dbo->setQuery($q);
				$dbo->execute();
				if($dbo->getNumRows() > 0) {
					$tar=$dbo->loadAssocList();
					$tar[0]['cost']=($tar[0]['cost'] / $tar[0]['days']);
				}else {
					$tar[0]['cost']=0;
				}
			}
			//Closed rate plans on these dates
			$roomrpclosed = VikBooking::getRoomRplansClosedInDates(array($room[0]['id']), $pcheckin, $daysdiff);
			if(count($roomrpclosed) > 0 && array_key_exists($room[0]['id'], $roomrpclosed)) {
				foreach ($tar as $kk => $tt) {
					if(array_key_exists('idprice', $tt) && array_key_exists($tt['idprice'], $roomrpclosed[$room[0]['id']])) {
						unset($tar[$kk]);
					}
				}
				$tar = array_values($tar);
			}
			//
			if(!(count($tar) > 0)) {
				$tar = array(array('cost' => 0));
			}
			$tar = array($tar[0]);
			$tar = VikBooking::applySeasonsRoom($tar, $pcheckin, $pcheckout);
			//different usage
			if ($room[0]['fromadult'] <= $padults && $room[0]['toadult'] >= $padults) {
				$diffusageprice = VikBooking::loadAdultsDiff($room[0]['id'], $padults);
				//Occupancy Override
				$occ_ovr = VikBooking::occupancyOverrideExists($tar, $padults);
				$diffusageprice = $occ_ovr !== false ? $occ_ovr : $diffusageprice;
				//
				if (is_array($diffusageprice)) {
					//set a charge or discount to the price for the different usage of the room
					foreach($tar as $kpr => $vpr) {
						$tar[$kpr]['diffusage'] = $padults;
						if ($diffusageprice['chdisc'] == 1) {
							//charge
							if ($diffusageprice['valpcent'] == 1) {
								//fixed value
								$tar[$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? 1 : 0;
								$aduseval = $diffusageprice['pernight'] == 1 ? $diffusageprice['value'] * $tar[$kpr]['days'] : $diffusageprice['value'];
								$tar[$kpr]['diffusagecost'] = "+".$aduseval;
								$tar[$kpr]['cost'] = $vpr['cost'] + $aduseval;
							}else {
								//percentage value
								$tar[$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? $vpr['cost'] : 0;
								$aduseval = $diffusageprice['pernight'] == 1 ? round(($vpr['cost'] * $diffusageprice['value'] / 100) * $tar[$kpr]['days'] + $vpr['cost'], 2) : round(($vpr['cost'] * (100 + $diffusageprice['value']) / 100), 2);
								$tar[$kpr]['diffusagecost'] = "+".$diffusageprice['value']."%";
								$tar[$kpr]['cost'] = $aduseval;
							}
						}else {
							//discount
							if ($diffusageprice['valpcent'] == 1) {
								//fixed value
								$tar[$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? 1 : 0;
								$aduseval = $diffusageprice['pernight'] == 1 ? $diffusageprice['value'] * $tar[$kpr]['days'] : $diffusageprice['value'];
								$tar[$kpr]['diffusagecost'] = "-".$aduseval;
								$tar[$kpr]['cost'] = $vpr['cost'] - $aduseval;
							}else {
								//percentage value
								$tar[$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? $vpr['cost'] : 0;
								$aduseval = $diffusageprice['pernight'] == 1 ? round($vpr['cost'] - ((($vpr['cost'] / $tar[$kpr]['days']) * $diffusageprice['value'] / 100) * $tar[$kpr]['days']), 2) : round(($vpr['cost'] * (100 - $diffusageprice['value']) / 100), 2);
								$tar[$kpr]['diffusagecost'] = "-".$diffusageprice['value']."%";
								$tar[$kpr]['cost'] = $aduseval;
							}
						}
					}
				}elseif($room[0]['toadult'] > $padults) {
					$tar[0]['diffusage'] = $padults;
				}
			}
			//
			$this->room = &$room[0];
			$this->tar = &$tar;
			$this->checkin = &$pcheckin;
			$this->checkout = &$pcheckout;
			$this->adults = &$padults;
			$this->children = &$pchildren;
			$this->daysdiff = &$daysdiff;
			$this->vbo_tn = &$vbo_tn;
			//theme
			$theme = VikBooking::getTheme();
			if($theme != 'default') {
				$thdir = VBO_SITE_PATH.DS.'themes'.DS.$theme.DS.'searchdetails';
				if(is_dir($thdir)) {
					$this->_setPath('template', $thdir.DS);
				}
			}
			//
			parent::display($tpl);
		}else {
			$mainframe = JFactory::getApplication();
			$mainframe->redirect("index.php?option=com_vikbooking&view=roomslist");
		}
	}
}
?>