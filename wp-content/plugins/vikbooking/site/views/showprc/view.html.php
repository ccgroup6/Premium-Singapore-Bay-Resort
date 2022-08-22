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

class VikbookingViewShowprc extends JViewVikBooking {
	function display($tpl = null) {
		$session = JFactory::getSession();
		$vbo_tn = VikBooking::getTranslator();
		$proomopt = VikRequest::getVar('roomopt', array());
		$proomindex = VikRequest::getVar('roomindex', array());
		$pdays = VikRequest::getString('days', '', 'request');
		$pcheckin = VikRequest::getInt('checkin', '', 'request');
		$pcheckout = VikRequest::getInt('checkout', '', 'request');
		$padults = VikRequest::getVar('adults', array());
		$pchildren = VikRequest::getVar('children', array());
		$proomsnum = VikRequest::getInt('roomsnum', '', 'request');
		$ppkg_id = VikRequest::getInt('pkg_id', '', 'request');
		$pitemid = VikRequest::getInt('Itemid', '', 'request');
		$nowdf = VikBooking::getDateFormat();
		if ($nowdf == "%d/%m/%Y") {
			$df = 'd/m/Y';
		} elseif ($nowdf == "%m/%d/%Y") {
			$df = 'm/d/Y';
		} else {
			$df = 'Y/m/d';
		}
		$dbo = JFactory::getDBO();
		$rooms = array();
		$arrpeople = array();
		for ($ir = 1; $ir <= $proomsnum; $ir++) {
			$ind = $ir - 1;
			if (!empty($proomopt[$ind])) {
				$q = "SELECT * FROM `#__vikbooking_rooms` WHERE `id`='".intval($proomopt[$ind])."' AND `avail`='1';";
				$dbo->setQuery($q);
				$dbo->execute();
				if ($dbo->getNumRows() > 0) {
					$takeroom = $dbo->loadAssocList();
					$rooms[$ir] = $takeroom[0];
				}
			}
			if (!empty($padults[$ind])) {
				$arrpeople[$ir]['adults'] = intval($padults[$ind]);
			} else {
				$arrpeople[$ir]['adults'] = 0;
			}
			if (!empty($pchildren[$ind])) {
				$arrpeople[$ir]['children'] = intval($pchildren[$ind]);
			} else {
				$arrpeople[$ir]['children'] = 0;
			}
		}
		if (count($rooms) != $proomsnum) {
			VikError::raiseWarning('', JText::translate('VBERRSELECTINGROOMS'));
			$mainframe = JFactory::getApplication();
			$mainframe->redirect(JRoute::rewrite('index.php?option=com_vikbooking'.(!empty($pitemid) ? '&Itemid='.$pitemid : '')));
			exit;
		}
		$vbo_tn->translateContents($rooms, '#__vikbooking_rooms');
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
				} else {
					$daysdiff = ceil($daysdiff);
				}
			}
		}

		// modify booking
		$mod_booking   = [];
		$skip_busy_ids = [];
		$only_non_ref  = false;
		$cur_mod = $session->get('vboModBooking', '');
		if (is_array($cur_mod) && count($cur_mod)) {
			$mod_booking = $cur_mod;
			$skip_busy_ids = VikBooking::loadBookingBusyIds($mod_booking['id']);
			/**
			 * Booking modification should check if a non refundable rate plan was
			 * previously selected so that moving to a refundable rate will be denied.
			 * 
			 * @since 	1.15.3 (J) - 1.5.5 (WP)
			 */
			$mod_booking_rooms 	= VikBooking::loadOrdersRoomsData($mod_booking['id']);
			$mod_booking_rplans = [];
			$mod_booking_tars 	= [];
			foreach ($mod_booking_rooms as $mod_booking_room) {
				if (!empty($mod_booking_room['idtar']) && !in_array($mod_booking_room['idtar'], $mod_booking_tars)) {
					$mod_booking_tars[] = $mod_booking_room['idtar'];
				}
			}
			if (count($mod_booking_tars)) {
				$q = "SELECT `idprice` FROM `#__vikbooking_dispcost` WHERE `id` IN (" . implode(', ', $mod_booking_tars) . ") GROUP BY `idprice`;";
				$dbo->setQuery($q);
				$dbo->execute();
				if ($dbo->getNumRows()) {
					$mod_booking_rplans = $dbo->loadAssocList();
				}
			}
			$only_non_ref = VikBooking::findNonRefundableRates($mod_booking_rplans);
		}

		//check that room(s) are available
		$groupdays = VikBooking::getGroupDays($pcheckin, $pcheckout, $daysdiff);
		$morehst = VikBooking::getHoursRoomAvail() * 3600;
		$goonunits = true;
		$rooms_counts = array();
		foreach($rooms as $num => $r) {
			$check = "SELECT `id`,`checkin`,`checkout` FROM `#__vikbooking_busy` WHERE `idroom`='" . $r['id'] . "' AND `checkout`>".time().";";
			$dbo->setQuery($check);
			$dbo->execute();
			if ($dbo->getNumRows() > 0) {
				$busy = $dbo->loadAssocList();
				foreach ($groupdays as $gday) {
					$bfound = 0;
					foreach ($busy as $bu) {
						if (in_array($bu['id'], $skip_busy_ids)) {
							//VBO 1.10 - Booking modification
							continue;
						}
						if ($gday >= $bu['checkin'] && $gday <= ($morehst + $bu['checkout'])) {
							$bfound++;
						}
					}
					if ($bfound >= $r['units']) {
						$goonunits = false;
						break;
					}
				}
			}
			$rooms_counts[$r['id']]['name'] = $r['name'];
			$rooms_counts[$r['id']]['units'] = $r['units'];
			$rooms_counts[$r['id']]['count'] = empty($rooms_counts[$r['id']]['count']) ? 1 : ($rooms_counts[$r['id']]['count'] + 1);
		}
		if ($goonunits) {
			foreach ($rooms_counts as $idr => $unitused) {
				if ($unitused['count'] > $unitused['units']) {
					VikError::raiseWarning('', JText::sprintf('VBERRROOMUNITSNOTAVAIL', $unitused['count'], $unitused['name']));
					$mainframe = JFactory::getApplication();
					$mainframe->redirect(JRoute::rewrite('index.php?option=com_vikbooking'.(!empty($pitemid) ? '&Itemid='.$pitemid : '')));
					$goonunits = false;
					break;
				}
			}
		}
		//
		if ($goonunits) {
			$hoursdiff = VikBooking::countHoursToArrival($pcheckin);
			$tars = array();
			$aretherefares = true;
			//Closed rate plans on these dates
			$all_rooms = array();
			foreach ($rooms as $num => $r) {
				if (!in_array($r['id'], $all_rooms)) {
					$all_rooms[] = $r['id'];
				}
			}
			$roomrpclosed = VikBooking::getRoomRplansClosedInDates($all_rooms, $pcheckin, $daysdiff);
			//
			foreach ($rooms as $num => $r) {
				$q = "SELECT `d`.*,`p`.`minlos`,`p`.`minhadv` FROM `#__vikbooking_dispcost` AS `d` LEFT JOIN `#__vikbooking_prices` AS `p` ON `p`.`id`=`d`.`idprice` WHERE `d`.`days`=" . (int)$daysdiff . " AND `d`.`idroom`=" . (int)$r['id'] . ($only_non_ref === true ? ' AND `p`.`free_cancellation`=0' : '') . " ORDER BY `d`.`cost` ASC;";
				$dbo->setQuery($q);
				$dbo->execute();
				if ($dbo->getNumRows() > 0) {
					$tar = $dbo->loadAssocList();
					//Closed rate plans on these dates
					if (count($roomrpclosed) > 0 && array_key_exists($r['id'], $roomrpclosed)) {
						foreach ($tar as $kk => $tt) {
							if (array_key_exists('idprice', $tt) && array_key_exists($tt['idprice'], $roomrpclosed[$r['id']])) {
								unset($tar[$kk]);
							}
						}
					}
					//VBO 1.10 - rate plans with a minlos, or with a min hours in advance
					foreach ($tar as $kk => $tt) {
						if (!empty($tt['minlos']) && $tt['minlos'] > $daysdiff) {
							unset($tar[$kk]);
						} elseif ($hoursdiff < $tt['minhadv']) {
							unset($tar[$kk]);
						}
					}
					$tar = array_values($tar);
					if (!(count($tar) > 0)) {
						$aretherefares = false;
						break;
					}
					//
					$tar = VikBooking::applySeasonsRoom($tar, $pcheckin, $pcheckout);
					//different usage
					if ($r['fromadult'] <= $arrpeople[$num]['adults'] && $r['toadult'] >= $arrpeople[$num]['adults']) {
						$diffusageprice = VikBooking::loadAdultsDiff($r['id'], $arrpeople[$num]['adults']);

						/**
						 * Memorize immediately the OBP rules defined at room-level in order to avoid conflicts
						 * with rate plans with and without OBP overrides defined at rate plan level through SP.
						 * 
						 * @since 	1.15.5 (J) - 1.5.11 (WP)
						 */
						$orig_diffusage = $diffusageprice;

						//Occupancy Override
						$occ_ovr = VikBooking::occupancyOverrideExists($tar, $arrpeople[$num]['adults']);
						$diffusageprice = $occ_ovr !== false ? $occ_ovr : $diffusageprice;
						//
						if (is_array($diffusageprice)) {
							//set a charge or discount to the price(s) for the different usage of the room
							foreach ($tar as $kpr => $vpr) {
								//VBO 1.7
								$diffusageprice = array_key_exists('occupancy_ovr', $vpr) && array_key_exists($arrpeople[$num]['adults'], $vpr['occupancy_ovr']) ? $vpr['occupancy_ovr'][$arrpeople[$num]['adults']] : $orig_diffusage;
								//
								$tar[$kpr]['diffusage'] = $arrpeople[$num]['adults'];
								if ($diffusageprice['chdisc'] == 1) {
									//charge
									if ($diffusageprice['valpcent'] == 1) {
										//fixed value
										$tar[$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? 1 : 0;
										$aduseval = $diffusageprice['pernight'] == 1 ? $diffusageprice['value'] * $tar[$kpr]['days'] : $diffusageprice['value'];
										$tar[$kpr]['diffusagecost'] = "+".$aduseval;
										$tar[$kpr]['room_base_cost'] = $vpr['cost'];
										$tar[$kpr]['cost'] = $vpr['cost'] + $aduseval;
									} else {
										//percentage value
										$tar[$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? $vpr['cost'] : 0;
										$aduseval = $diffusageprice['pernight'] == 1 ? round(($vpr['cost'] * $diffusageprice['value'] / 100) * $tar[$kpr]['days'] + $vpr['cost'], 2) : round(($vpr['cost'] * (100 + $diffusageprice['value']) / 100), 2);
										$tar[$kpr]['diffusagecost'] = "+".$diffusageprice['value']."%";
										$tar[$kpr]['room_base_cost'] = $vpr['cost'];
										$tar[$kpr]['cost'] = $aduseval;
									}
								} else {
									//discount
									if ($diffusageprice['valpcent'] == 1) {
										//fixed value
										$tar[$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? 1 : 0;
										$aduseval = $diffusageprice['pernight'] == 1 ? $diffusageprice['value'] * $tar[$kpr]['days'] : $diffusageprice['value'];
										$tar[$kpr]['diffusagecost'] = "-".$aduseval;
										$tar[$kpr]['room_base_cost'] = $vpr['cost'];
										$tar[$kpr]['cost'] = $vpr['cost'] - $aduseval;
									} else {
										//percentage value
										$tar[$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? $vpr['cost'] : 0;
										$aduseval = $diffusageprice['pernight'] == 1 ? round($vpr['cost'] - ((($vpr['cost'] / $tar[$kpr]['days']) * $diffusageprice['value'] / 100) * $tar[$kpr]['days']), 2) : round(($vpr['cost'] * (100 - $diffusageprice['value']) / 100), 2);
										$tar[$kpr]['diffusagecost'] = "-".$diffusageprice['value']."%";
										$tar[$kpr]['room_base_cost'] = $vpr['cost'];
										$tar[$kpr]['cost'] = $aduseval;
									}
								}
							}
						}
					}
					//
					$tars[$num] = $tar;
				} else {
					$aretherefares = false;
					break;
				}
			}
			if ($aretherefares === true) {
				if (VikBooking::dayValidTs($pdays, $pcheckin, $pcheckout)) {
					$pkg = array();
					if(!empty($ppkg_id)) {
						$pkg = VikBooking::validateRoomPackage($ppkg_id, $rooms, $daysdiff, $pcheckin, $pcheckout);
						if(!is_array($pkg) || (is_array($pkg) && !(count($pkg) > 0)) ) {
							if(!is_array($pkg)) {
								VikError::raiseWarning('', $pkg);
							}
							$mainframe = JFactory::getApplication();
							$mainframe->redirect(JRoute::rewrite("index.php?option=com_vikbooking&view=packagedetails&pkgid=".$ppkg_id.(!empty($pitemid) ? "&Itemid=".$pitemid : ""), false));
							exit;
						}
					}
					$this->tars = &$tars;
					$this->rooms = &$rooms;
					$this->roomsnum = &$proomsnum;
					$this->arrpeople = &$arrpeople;
					$this->checkin = &$pcheckin;
					$this->checkout = &$pcheckout;
					$this->days = &$daysdiff;
					$this->pkg = &$pkg;
					$this->mod_booking = &$mod_booking;
					$this->vbo_tn = &$vbo_tn;
					//theme
					$theme = VikBooking::getTheme();
					if($theme != 'default') {
						$thdir = VBO_SITE_PATH.DS.'themes'.DS.$theme.DS.'showprc';
						if(is_dir($thdir)) {
							$this->_setPath('template', $thdir.DS);
						}
					}
					//

					/**
					 * We append to the booking process the rooms indexes booked through the interactive map, if any.
					 * 
					 * @since 	1.14 (J) - 1.4.0 (WP)
					 */
					if (count($proomindex) == count($rooms)) {
						$only_empty_indexes = true;
						foreach ($proomindex as $rindex) {
							if ((int)$rindex > 0) {
								// a true room index was selected
								$only_empty_indexes = false;
								break;
							}
						}
						if ($only_empty_indexes) {
							// we don't need to pass along the room indexes
							$proomindex = array();
						}
					} else {
						$proomindex = array();
					}
					$this->roomindex = &$proomindex;
					//

					// VBO 1.11 - push data to tracker
					$rooms_ids = array();
					foreach ($rooms as $ir => $r) {
						$rooms_ids[$ir] = $r['id'];
					}
					VikBooking::getTracker()->pushDates($pcheckin, $pcheckout, $daysdiff)->pushParty($arrpeople)->pushRooms($rooms_ids, array(), $proomindex)->closeTrack();
					//
					
					parent::display($tpl);
				} else {
					showSelectVb(JText::translate('VBERRCALCTAR'));
				}
			} else {
				showSelectVb(JText::translate('VBNOTARFNDSELO'));
			}
		} else {
			showSelectVb(JText::translate('VBROOMNOTRIT') . " " . date($df . ' H:i', $pcheckin) . " " . JText::translate('VBROOMNOTCONSTO') . " " . date($df . ' H:i', $pcheckout));
		}
	}
}
