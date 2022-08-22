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
 * @deprecated 	This template file is no longer used in the Dashboard, as the forecast
 * 				has become an admin widget that can be turned off and on.
 * @since 		1.4.0
 */

$layout_data = array(
	'vbo_page' => 'dashboard',
);

?>

<div class="vbo-dashboard-forecast-wrap">
	<h4><?php echo JText::translate('VBOFORECAST'); ?></h4>
	<div class="vbo-dashboard-forecast-inner">
		<?php echo JLayoutHelper::render('reports.occupancy', $layout_data); ?>
	</div>
</div>
