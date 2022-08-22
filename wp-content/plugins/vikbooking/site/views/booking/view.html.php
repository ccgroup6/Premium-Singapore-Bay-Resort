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

class VikbookingViewBooking extends JViewVikBooking {
	function display($tpl = null) {
		$dbo = JFactory::getDBO();
		$document = JFactory::getDocument();
		$mainframe = JFactory::getApplication();
		$vbo_tn = VikBooking::getTranslator();
		
		// validation of data and availability before the rendering
		$sid = VikRequest::getString('sid', '', 'request');
		$ts = VikRequest::getString('ts', '', 'request');
		if (empty($sid) || empty($ts)) {
			showSelectVb(JText::translate('VBINSUFDATA'));
			return;
		}
		$q = "SELECT `o`.*,(SELECT SUM(`or`.`adults`) FROM `#__vikbooking_ordersrooms` AS `or` WHERE `or`.`idorder`=`o`.`id`) AS `tot_adults` FROM `#__vikbooking_orders` AS `o` WHERE (`o`.`sid`=" . $dbo->quote($sid) . " OR `o`.`idorderota`=" . $dbo->quote($sid) . ") AND `o`.`ts`=" . $dbo->quote($ts) . ";";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() < 1) {
			showSelectVb(JText::translate('VBORDERNOTFOUND'));
			return;
		}
		$order = $dbo->loadAssocList();
		if ($order[0]['status'] == "confirmed") {
			// prepare impression data for channels
			$impressiondata = $order[0];
			$q = "SELECT `or`.`idtar`,`d`.`idprice`,`p`.`idiva`,`t`.`aliq`,`t`.`taxcap` FROM `#__vikbooking_ordersrooms` AS `or` " .
				"LEFT JOIN `#__vikbooking_dispcost` `d` ON `d`.`id`=`or`.`idtar` " . 
				"LEFT JOIN `#__vikbooking_prices` `p` ON `p`.`id`=`d`.`idprice` " . 
				"LEFT JOIN `#__vikbooking_iva` `t` ON `t`.`id`=`p`.`idiva` " . 
				"WHERE `or`.`idorder`='".$order[0]['id']."' ORDER BY `t`.`aliq` ASC LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() == 1) {
				$taxdata = $dbo->loadAssocList();
				$taxes = 0;
				if (!empty($taxdata[0]['aliq'])) {
					$realtotal = round(($order[0]['total'] / ((100 + $taxdata[0]['aliq']) / 100)), 2);
					$taxes = round(($order[0]['total'] - $realtotal), 2);
					/**
					 * Tax Cap implementation.
					 * 
					 * @since 	1.12
					 */
					if ($taxdata[0]['taxcap'] > 0 && $taxes > $taxdata[0]['taxcap']) {
						$realtotal = $order[0]['total'] - $taxdata[0]['taxcap'];
						$taxes = $taxdata[0]['taxcap'];
					}
					$impressiondata['total'] = $realtotal;
				}
				$impressiondata['taxes'] = $taxes;
				$impressiondata['fees'] = 0;
			}
			VikBooking::invokeChannelManager(true, $impressiondata);
			// end prepare impression data for channels
		} elseif ($order[0]['status'] == "standby") {
			$roomavail = false;
			$q = "SELECT `or`.*,`r`.`units` FROM `#__vikbooking_ordersrooms` AS `or`, `#__vikbooking_rooms` AS `r` WHERE `or`.`idorder`=".(int)$order[0]['id']." AND `or`.`idroom`=`r`.`id`;";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() > 0) {
				$orderrooms = $dbo->loadAssocList();
				foreach ($orderrooms as $or) {
					$roomavail = VikBooking::roomBookable($or['idroom'], $or['units'], $order[0]['checkin'], $order[0]['checkout']);
					if (!$roomavail) {
						break;
					}
				}
			}
			$today_midnight = mktime(0, 0, 0, date('n'), date('j'), date('Y'));
			$autoremove = false;
			if ($today_midnight > $order[0]['checkin']) {
				$roomavail = false;
			}
			$minautoremove = VikBooking::getMinutesAutoRemove();
			$mins_elapsed = floor((time() - $order[0]['ts']) / 60);
			if ($minautoremove > 0 && $mins_elapsed > $minautoremove) {
				$roomavail = false;
				$autoremove = true;
			}

			/**
			 * Make sure not to cancel an OTA pending reservation.
			 * 
			 * @since 	1.15.4 (J) - 1.5.10 (WP)
			 */
			$is_ota_pending = (!empty($order[0]['type']) && !empty($order[0]['channel']));

			if ($roomavail || $is_ota_pending) {
				// invoke channel impression
				VikBooking::invokeChannelManager(false);
				//
			} else {
				// set the booking to cancelled
				$q = "UPDATE `#__vikbooking_orders` SET `status`='cancelled' WHERE `id`=".(int)$order[0]['id'].";";
				$dbo->setQuery($q);
				$dbo->execute();
				// update status in the array
				$order[0]['status'] = 'cancelled';
				//
				$q = "DELETE FROM `#__vikbooking_tmplock` WHERE `idorder`=" . (int)$order[0]['id'] . ";";
				$dbo->setQuery($q);
				$dbo->execute();
				$q = "SELECT * FROM `#__vikbooking_ordersbusy` WHERE `idorder`=".(int)$order[0]['id'].";";
				$dbo->setQuery($q);
				$dbo->execute();
				if ($dbo->getNumRows() > 0) {
					$ordbusy = $dbo->loadAssocList();
					foreach($ordbusy as $ob) {
						$q = "DELETE FROM `#__vikbooking_busy` WHERE `id`=".(int)$ob['idbusy'].";";
						$dbo->setQuery($q);
						$dbo->execute();
					}
				}
				$q = "DELETE FROM `#__vikbooking_ordersbusy` WHERE `idorder`=".(int)$order[0]['id'].";";
				$dbo->setQuery($q);
				$dbo->execute();

				/**
				 * We now save onto a var the full error message to understand what made the booking become cancelled.
				 * This way, by checking the booking history it will be easy to understand the cause of the issue.
				 * 
				 * @since 	October 15th 2020
				 */
				$history_err_descr = '';
				if ($today_midnight > $order[0]['checkin']) {
					$history_err_descr = JText::translate('VBOBOOKNOLONGERPAYABLE');
				} elseif ($autoremove === true) {
					$history_err_descr = JText::translate('VBOERRAUTOREMOVED');
				} else {
					$history_err_descr = JText::translate('VBERRREPSEARCH');
				}
				if (!empty($history_err_descr)) {
					VikError::raiseWarning('', $history_err_descr);
				}

				//Booking History
				VikBooking::getBookingHistoryInstance()->setBid($order[0]['id'])->store('CA', $history_err_descr);
				//
			}
		}

		// render the booking details

		//set noindex instruction for robots
		$document->setMetaData('robots', 'noindex,follow');
		//load jQuery
		if (VikBooking::loadJquery()) {
			JHtml::fetch('jquery.framework', true, true);
			JHtml::fetch('script', VBO_SITE_URI.'resources/jquery-1.12.4.min.js');
		}
		//
		$tars = array();
		$cookie = $mainframe->input->cookie;
		$pcheckin = $order[0]['checkin'];
		$pcheckout = $order[0]['checkout'];
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
		$is_package = !empty($order[0]['pkg']) ? true : false;
		$orderrooms = array();
		$q = "SELECT `or`.`idroom`,`or`.`adults`,`or`.`children`,`or`.`idtar`,`or`.`optionals`,`or`.`roomindex`,`or`.`pkg_id`,`or`.`pkg_name`,`or`.`cust_cost`,`or`.`cust_idiva`,`or`.`extracosts`,`or`.`room_cost`,`or`.`otarplan`,`r`.`id` AS `r_reference_id`,`r`.`name`,`r`.`img`,`r`.`idcarat`,`r`.`fromadult`,`r`.`toadult` FROM `#__vikbooking_ordersrooms` AS `or`,`#__vikbooking_rooms` AS `r` WHERE `or`.`idorder`='".$order[0]['id']."' AND `or`.`idroom`=`r`.`id` ORDER BY `or`.`id` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$orderrooms = $dbo->loadAssocList();
			$vbo_tn->translateContents($orderrooms, '#__vikbooking_rooms', array('id' => 'r_reference_id'));
			foreach ($orderrooms as $kor => $or) {
				$num = $kor + 1;
				if ($is_package === true || (!empty($or['cust_cost']) && $or['cust_cost'] > 0.00)) {
					//package or custom cost set from the back-end
					continue;
				}
				$q = "SELECT `t`.*,`p`.`name`,`p`.`free_cancellation`,`p`.`canc_deadline`,`p`.`canc_policy` FROM `#__vikbooking_dispcost` AS `t` LEFT JOIN `#__vikbooking_prices` AS `p` ON `t`.`idprice`=`p`.`id` WHERE `t`.`id`='" . $or['idtar'] . "';";
				$dbo->setQuery($q);
				$dbo->execute();
				if ($dbo->getNumRows() == 1) {
					$tar = $dbo->loadAssocList();
					$vbo_tn->translateContents($tar, '#__vikbooking_prices', array('id' => 'idprice'));
					$tar = VikBooking::applySeasonsRoom($tar, $order[0]['checkin'], $order[0]['checkout']);
					//different usage
					if ($or['fromadult'] <= $or['adults'] && $or['toadult'] >= $or['adults']) {
						$diffusageprice = VikBooking::loadAdultsDiff($or['idroom'], $or['adults']);
						//Occupancy Override
						$occ_ovr = VikBooking::occupancyOverrideExists($tar, $or['adults']);
						$diffusageprice = $occ_ovr !== false ? $occ_ovr : $diffusageprice;
						//
						if (is_array($diffusageprice)) {
							//set a charge or discount to the price(s) for the different usage of the room
							foreach ($tar as $kpr => $vpr) {
								$tar[$kpr]['diffusage'] = $or['adults'];
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
					$tars[$num] = $tar[0];
				}
			}
		}
		//
		$days_to_arrival = 0;
		$now_info = getdate();
		$checkin_info = getdate($order[0]['checkin']);
		if ($now_info[0] < $checkin_info[0]) {
			while ($now_info[0] < $checkin_info[0]) {
				if (!($now_info['mday'] != $checkin_info['mday'] || $now_info['mon'] != $checkin_info['mon'] || $now_info['year'] != $checkin_info['year'])) {
					break;
				}
				$days_to_arrival++;
				$now_info = getdate(mktime(0, 0, 0, $now_info['mon'], ($now_info['mday'] + 1), $now_info['year']));
			}
		}
		//
		$is_refundable = 0;
		$daysadv_refund_arr = array();
		$daysadv_refund = 0;
		$canc_policy = '';
		foreach ($tars as $num => $tar) {
			if ($tar['free_cancellation'] < 1) {
				//if at least one rate plan is non-refundable, the whole reservation cannot be cancelled
				$is_refundable = 0;
				$daysadv_refund_arr = array();
				break;
			}
			$is_refundable = 1;
			$daysadv_refund_arr[] = $tar['canc_deadline'];
		}
		//get the rate plan with the lowest cancellation deadline
		$daysadv_refund = count($daysadv_refund_arr) > 0 ? min($daysadv_refund_arr) : $daysadv_refund;
		if ($daysadv_refund > 0) {
			foreach ($tars as $num => $tar) {
				if ($tar['free_cancellation'] > 0 && $tar['canc_deadline'] == $daysadv_refund) {
					//get the cancellation policy from the first rate plan with free cancellation and same cancellation deadline
					$canc_policy = $tar['canc_policy'];
					break;
				}
			}
		}
		//
		$payment = "";
		if (!empty($order[0]['idpayment'])) {
			$exppay = explode('=', $order[0]['idpayment']);
			$payment = VikBooking::getPayment($exppay[0], $vbo_tn);
		}
		$pnodep = VikRequest::getString('nodep', '', 'request');
		$cnodep = $cookie->get('vboFA', '', 'string');
		$nodep = intval($pnodep) > 0 || intval($cnodep) > 0 ? 1 : 0;

		/**
		 * Upselling extra services only for non-cancelled bookings
		 * with a check-out date in the future.
		 * 
		 * @since 	1.3.0
		 */
		$upselling = array();
		if ($order[0]['status'] != 'cancelled' && VikBooking::upsellingEnabled() && $order[0]['checkout'] > time()) {
			$upsell_data = array();
			foreach ($orderrooms as $kor => $or) {
				$room_data = new stdClass;
				$room_data->id = $or['idroom'];
				$room_data->name = $or['name'];
				$room_data->img = $or['img'];
				$room_data->adults = $or['adults'];
				$room_data->children = $or['children'];
				$room_data->nights = $order[0]['days'];
				$room_data->options = array();
				if (!empty($or['optionals'])) {
					$optids = explode(';', $or['optionals']);
					foreach ($optids as $optid) {
						if (!empty($optid)) {
							// the : may express the quantity
							$optidparts = explode(':', $optid);
							array_push($room_data->options, (int)$optidparts[0]);
						}
					}
				}
				array_push($upsell_data, $room_data);
			}
			$upselling = VikBooking::loadUpsellingData(
				$upsell_data, 
				array(
					'id' => $order[0]['id'], 
					'checkin' => $order[0]['checkin'], 
					'checkout' => $order[0]['checkout'],
				), 
				$vbo_tn
			);
		}
		
		$this->ord = &$order[0];
		$this->orderrooms = &$orderrooms;
		$this->tars = &$tars;
		$this->days_to_arrival = &$days_to_arrival;
		$this->is_refundable = &$is_refundable;
		$this->daysadv_refund = &$daysadv_refund;
		$this->canc_policy = &$canc_policy;
		$this->payment = &$payment;
		$this->nodep = &$nodep;
		$this->upselling = &$upselling;
		$this->vbo_tn = &$vbo_tn;

		// theme
		$theme = VikBooking::getTheme();
		if ($theme != 'default') {
			$thdir = VBO_SITE_PATH . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . $theme . DIRECTORY_SEPARATOR . 'booking';
			if (is_dir($thdir)) {
				$this->_setPath('template', $thdir . DIRECTORY_SEPARATOR);
			}
		}
		//

		parent::display($tpl);
	}
}
