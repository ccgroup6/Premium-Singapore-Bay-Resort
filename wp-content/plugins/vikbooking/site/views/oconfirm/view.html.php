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

class VikbookingViewOconfirm extends JViewVikBooking {
	function display($tpl = null) {
		$session = JFactory::getSession();
		$vbo_tn = VikBooking::getTranslator();
		$proomid = VikRequest::getVar('roomid', array());
		$proomindex = VikRequest::getVar('roomindex', array());
		$pdays = VikRequest::getInt('days', '', 'request');
		$pcheckin = VikRequest::getInt('checkin', '', 'request');
		$pcheckout = VikRequest::getInt('checkout', '', 'request');
		$proomsnum = VikRequest::getInt('roomsnum', '', 'request');
		$padults = VikRequest::getVar('adults', array());
		$pchildren = VikRequest::getVar('children', array());
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
			if (!empty($proomid[$ind])) {
				$q = "SELECT * FROM `#__vikbooking_rooms` WHERE `id`='".intval($proomid[$ind])."' AND `avail`='1';";
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
			VikError::raiseWarning('', JText::translate('VBROOMNOTFND'));
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
		//VBO 1.10 - Modify booking
		$mod_booking = array();
		$skip_busy_ids = array();
		$cur_mod = $session->get('vboModBooking', '');
		if (is_array($cur_mod) && count($cur_mod)) {
			$mod_booking = $cur_mod;
			$skip_busy_ids = VikBooking::loadBookingBusyIds($mod_booking['id']);
		}
		//
		//check that room(s) are available and get the price(s)
		$groupdays = VikBooking::getGroupDays($pcheckin, $pcheckout, $daysdiff);
		$morehst = VikBooking::getHoursRoomAvail() * 3600;
		$validtime = true;
		$prices = array();
		foreach ($rooms as $num => $r) {
			$ppriceid = VikRequest::getString('priceid'.$num, '', 'request');
			if (!empty($ppriceid)) {
				$prices[$num] = intval($ppriceid);
			}
			$check = "SELECT `id`,`checkin`,`checkout` FROM `#__vikbooking_busy` WHERE `idroom`='" . $r['id'] . "';";
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
						$validtime = false;
						break;
					}
				}
			}
		}
		//
		if ($validtime == true) {
			if (count($prices) == count($rooms)) {
				//load options
				$optionals = '';
				$selopt = '';
				$q = "SELECT `opt`.* FROM `#__vikbooking_optionals` AS `opt` ORDER BY `opt`.`ordering` ASC;";
				$dbo->setQuery($q);
				$dbo->execute();
				if ($dbo->getNumRows() > 0) {
					$optionals = $dbo->loadAssocList();
					$vbo_tn->translateContents($optionals, '#__vikbooking_optionals');
					$selopt = array();
				}
				//
				//Package
				$pkg = array();
				if (!empty($ppkg_id)) {
					$pkg = VikBooking::validateRoomPackage($ppkg_id, $rooms, $daysdiff, $pcheckin, $pcheckout);
					if (!is_array($pkg) || (is_array($pkg) && !(count($pkg) > 0)) ) {
						if (!is_array($pkg)) {
							VikError::raiseWarning('', $pkg);
						}
						$mainframe = JFactory::getApplication();
						$mainframe->redirect(JRoute::rewrite("index.php?option=com_vikbooking&view=packagedetails&pkgid=".$ppkg_id.(!empty($pitemid) ? "&Itemid=".$pitemid : ""), false));
						exit;
					}
				}
				//
				$tars = array();
				$validfares = true;
				foreach ($rooms as $num => $r) {
					if (!(count($pkg) > 0)) {
						$q = "SELECT * FROM `#__vikbooking_dispcost` WHERE `idroom`='" . $r['id'] . "' AND `days`='" . $daysdiff . "' AND `idprice`='" . $prices[$num] . "';";
						$dbo->setQuery($q);
						$dbo->execute();
						if ($dbo->getNumRows() == 1) {
							$tar = $dbo->loadAssocList();
							$tar = VikBooking::applySeasonsRoom($tar, $pcheckin, $pcheckout);
							//different usage
							if ($r['fromadult'] <= $arrpeople[$num]['adults'] && $r['toadult'] >= $arrpeople[$num]['adults']) {
								$diffusageprice = VikBooking::loadAdultsDiff($r['id'], $arrpeople[$num]['adults']);
								//Occupancy Override
								$occ_ovr = VikBooking::occupancyOverrideExists($tar, $arrpeople[$num]['adults']);
								$diffusageprice = $occ_ovr !== false ? $occ_ovr : $diffusageprice;
								//
								if (is_array($diffusageprice)) {
									//set a charge or discount to the price(s) for the different usage of the room
									foreach ($tar as $kpr => $vpr) {
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
							$validfares = false;
							break;
						}
					}
					//load selected options
					if (@is_array($optionals)) {
						foreach ($optionals as $opt) {
							if (!empty($opt['ageintervals']) && $arrpeople[$num]['children'] > 0) {
								$tmpvar = VikRequest::getVar('optid'.$num.$opt['id'], array(0));
								if (is_array($tmpvar) && count($tmpvar) > 0 && !empty($tmpvar[0])) {
									$opt['quan'] = 1;
									$optagenames = VikBooking::getOptionIntervalsAges($opt['ageintervals']);
									$optagepcent = VikBooking::getOptionIntervalsPercentage($opt['ageintervals']);
									$optageovrct = VikBooking::getOptionIntervalChildOverrides($opt, $arrpeople[$num]['adults'], $arrpeople[$num]['children']);
									$optorigname = $opt['name'];
									foreach ($tmpvar as $child_num => $chvar) {
										$ageintervals_child_string = isset($optageovrct['ageintervals_child' . ($child_num + 1)]) ? $optageovrct['ageintervals_child' . ($child_num + 1)] : $opt['ageintervals'];
										$optagecosts = VikBooking::getOptionIntervalsCosts($ageintervals_child_string);
										$opt['cost'] = $optagecosts[($chvar - 1)];
										if (array_key_exists(($chvar - 1), $optagepcent) && $optagepcent[($chvar - 1)] == 1 && ( (array_key_exists($num, $tars) && count($tars[$num]) > 0) || (is_array($pkg) && count($pkg) > 0) )) {
											//percentage value of the adults tariff
											if (is_array($pkg) && count($pkg) > 0) {
												$opt['cost'] = ($pkg['pernight_total'] == 1 ? ($pkg['cost'] * $daysdiff) : $pkg['cost']) * $optagecosts[($chvar - 1)] / 100;
											} else {
												$opt['cost'] = $tars[$num][0]['cost'] * $optagecosts[($chvar - 1)] / 100;
											}
										} elseif (array_key_exists(($chvar - 1), $optagepcent) && $optagepcent[($chvar - 1)] == 2 && ( (array_key_exists($num, $tars) && count($tars[$num]) > 0) || (is_array($pkg) && count($pkg) > 0) )) {
											//VBO 1.10 - percentage value of room base cost
											if (is_array($pkg) && count($pkg) > 0) {
												$opt['cost'] = ($pkg['pernight_total'] == 1 ? ($pkg['cost'] * $daysdiff) : $pkg['cost']) * $optagecosts[($chvar - 1)] / 100;
											} else {
												$display_rate = isset($tars[$num][0]['room_base_cost']) ? $tars[$num][0]['room_base_cost'] : $tars[$num][0]['cost'];
												$opt['cost'] = $display_rate * $optagecosts[($chvar - 1)] / 100;
											}
										}
										$opt['name'] = $optorigname.' ('.$optagenames[($chvar - 1)].')';
										$opt['chageintv'] = $chvar;
										$selopt[$num][] = $opt;
									}
								}
							} else {
								$tmpvar = VikRequest::getString('optid'.$num.$opt['id'], '', 'request');
								if (!empty($tmpvar)) {
									$opt['quan'] = $tmpvar;
									// VBO 1.11 - options percentage cost of the room total fee
									if (is_array($pkg) && count($pkg) > 0) {
										$deftar_basecosts = $pkg['pernight_total'] == 1 ? ($pkg['cost'] * $daysdiff) : $pkg['cost'];
									} else {
										$deftar_basecosts = $tars[$num][0]['cost'];
									}
									$opt['cost'] = (int)$opt['pcentroom'] ? ($deftar_basecosts * $opt['cost'] / 100) : $opt['cost'];
									//
									$selopt[$num][] = $opt;
								}
							}
						}
					}
					//
				}	
				if ($validfares === true) {
					if (VikBooking::dayValidTs($pdays, $pcheckin, $pcheckout)) {
						$q = "SELECT * FROM `#__vikbooking_gpayments` WHERE `published`='1' ORDER BY `#__vikbooking_gpayments`.`ordering` ASC;";
						$dbo->setQuery($q);
						$dbo->execute();
						$payments = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : "";
						if (is_array($payments)) {
							$vbo_tn->translateContents($payments, '#__vikbooking_gpayments');
						}
						$q = "SELECT * FROM `#__vikbooking_custfields` ORDER BY `#__vikbooking_custfields`.`ordering` ASC;";
						$dbo->setQuery($q);
						$dbo->execute();
						$cfields = $dbo->getNumRows() ? $dbo->loadAssocList() : array();
						if (count($cfields)) {
							$vbo_tn->translateContents($cfields, '#__vikbooking_custfields');
						}
						$countries = '';
						foreach ($cfields as $cf) {
							if ($cf['type'] == 'country') {
								$q = "SELECT * FROM `#__vikbooking_countries` ORDER BY `#__vikbooking_countries`.`country_name` ASC;";
								$dbo->setQuery($q);
								$dbo->execute();
								$countries = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : "";
								break;
							}
						}
						if (!empty($countries) && is_array($countries)) {
							$vbo_tn->translateContents($countries, '#__vikbooking_countries');
						}
						//coupon
						$pcouponcode = VikRequest::getString('couponcode', '', 'request');
						$coupon = "";
						if (strlen($pcouponcode) > 0 && !(count($pkg) > 0)) {
							$coupon = VikBooking::getCouponInfo($pcouponcode);
							if (is_array($coupon)) {
								$coupondateok = true;
								$couponroomok = true;
								if (strlen($coupon['datevalid']) > 0) {
									$dateparts = explode("-", $coupon['datevalid']);
									$pickinfo = getdate($pcheckin);
									$dropinfo = getdate($pcheckout);
									$checkpick = mktime(0, 0, 0, $pickinfo['mon'], $pickinfo['mday'], $pickinfo['year']);
									$checkdrop = mktime(0, 0, 0, $dropinfo['mon'], $dropinfo['mday'], $dropinfo['year']);
									if(!($checkpick >= $dateparts[0] && $checkpick <= $dateparts[1] && $checkdrop >= $dateparts[0] && $checkdrop <= $dateparts[1])) {
										$coupondateok = false;
									}
								}
								if (!empty($coupon['minlos']) && $coupon['minlos'] > $daysdiff) {
									$coupondateok = false;
								}
								if ($coupondateok === true) {
									if ($coupon['allvehicles'] == 0) {
										foreach ($rooms as $num => $r) {
											if (!(preg_match("/;".$r['id'].";/i", $coupon['idrooms']))) {
												$couponroomok = false;
												break;
											}
										}
									}
									if ($couponroomok !== true) {
										$coupon = "";
										VikError::raiseWarning('', JText::translate('VBCOUPONINVROOM'));
									}
								} else {
									$coupon = "";
									VikError::raiseWarning('', JText::translate('VBCOUPONINVDATES'));
								}
							} else {
								VikError::raiseWarning('', JText::translate('VBCOUPONNOTFOUND'));
							}
						}
						//end coupon
						//Customer Details
						$cpin = VikBooking::getCPinIstance();
						$customer_details = $cpin->loadCustomerDetails();
						//
						$this->rooms = &$rooms;
						$this->tars = &$tars;
						$this->prices = &$prices;
						$this->arrpeople = &$arrpeople;
						$this->roomsnum = &$proomsnum;
						$this->selopt = &$selopt;
						$this->days = &$daysdiff;
						$this->coupon = &$coupon;
						$this->first = &$pcheckin;
						$this->second = &$pcheckout;
						$this->payments = &$payments;
						$this->cfields = &$cfields;
						$this->customer_details = &$customer_details;
						$this->countries = &$countries;
						$this->pkg = &$pkg;
						$this->mod_booking = &$mod_booking;
						$this->vbo_tn = &$vbo_tn;
						//theme
						$theme = VikBooking::getTheme();
						if ($theme != 'default') {
							$thdir = VBO_SITE_PATH.DS.'themes'.DS.$theme.DS.'oconfirm';
							if (is_dir($thdir)) {
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
						$rooms_ids  = array();
						$prices_ids = array();
						foreach ($rooms as $ir => $r) {
							$rooms_ids[$ir]  = $r['id'];
							$prices_ids[$ir] = $prices[$ir];
						}
						VikBooking::getTracker()->pushDates($pcheckin, $pcheckout, $daysdiff)->pushParty($arrpeople)->pushRooms($rooms_ids, $prices_ids, $proomindex)->closeTrack();
						//
						
						parent::display($tpl);
					} else {
						showSelectVb(JText::translate('VBERRCALCTAR'));
					}
				} else {
					showSelectVb(JText::translate('VBTARNOTFOUND'));
				}
			} else {
				showSelectVb(JText::translate('VBNOTARSELECTED'));
			}	
		} else {
			showSelectVb(JText::translate('VBROOMNOTCONS') . " " . date($df . ' H:i', $pcheckin) . " " . JText::translate('VBROOMNOTCONSTO') . " " . date($df . ' H:i', $pcheckout));
		}
	}
}
