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
 * Helper class to handle rooms data.
 * 
 * @since 	1.15.1 (J) - 1.5.2 (WP)
 */
final class VBORoomHelper extends JObject
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
	 * Checks whether a room has been configured with LOS pricing rules.
	 * VCM comes with a similar built-in method, but we need this feature
	 * to be available also for those who only use VBO. Moreover, this method
	 * can identify the first night with a non-proportional rate.
	 * 
	 * @param 	int 	$idroom 	the ID of the room in VBO.
	 * @param 	int 	$idprice 	the optional rate plan ID in VBO.
	 * @param 	bool 	$get_nights whether to return the number of nights when LOS starts.
	 * 
	 * @return 	bool|int			false on failure or if no LOS prices found, true or int otherwise.
	 */
	public static function hasLosRecords($idroom, $idprice = 0, $get_nights = false)
	{
		if (empty($idroom)) {
			return false;
		}

		$dbo = JFactory::getDbo();
		$q = "SELECT * FROM `#__vikbooking_dispcost` WHERE `idroom`=" . (int)$idroom . (!empty($idprice) ? " AND `idprice`=" . (int)$idprice : '') . " ORDER BY `days` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if (!$dbo->getNumRows()) {
			return false;
		}
		$los_data = $dbo->loadAssocList();

		$los_pricing = array();
		foreach ($los_data as $cost) {
			if (!isset($los_pricing[$cost['days']])) {
				$los_pricing[$cost['days']] = array();
			}
			array_push($los_pricing[$cost['days']], $cost);
		}
		// sort by number of nights
		ksort($los_pricing);

		// compose lowest costs per rate plan
		$base_costs = array();
		foreach ($los_pricing as $nights => $costs) {
			foreach ($costs as $rplan_cost) {
				$base_costs[$rplan_cost['idprice']] = ($rplan_cost['cost'] / $rplan_cost['days']);
			}
			// we take the costs for the lowest number of nights
			break;
		}

		// check if rates change depending on the number of nights of stay
		foreach ($los_pricing as $nights => $costs) {
			foreach ($costs as $rplan_cost) {
				$base_cost = ($rplan_cost['cost'] / $rplan_cost['days']);
				if (isset($base_costs[$rplan_cost['idprice']]) && round($base_costs[$rplan_cost['idprice']], 2) != round($base_cost, 2)) {
					/**
					 * Average rates should be compared after applying rounding or we may face issues.
					 * For example, 383.97 / 3 = 127.99, but it's actually = 127.99000000000001 with
					 * an absolute number for the difference with 127.99 of 1.4210854715202004E-14
					 * which results to be greater than 0 but less than 1. Therefore, we also allow
					 * an absolute number for the difference of 0.05 cents for a proper check.
					 */
					$price_diff = abs($base_costs[$rplan_cost['idprice']] - $base_cost);
					if ($price_diff > 0.05) {
						// this is a non-proportional cost per night, so LOS records have been defined
						return $get_nights ? $nights : true;
					}
				}
			}
		}

		// all costs per night were proportional
		return false;
	}
}
