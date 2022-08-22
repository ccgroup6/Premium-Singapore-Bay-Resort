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

$operator = $this->operator;

$operator['perms'] = !empty($operator['perms']) ? json_decode($operator['perms'], true) : array();
$operator['perms'] = is_array($operator['perms']) ? $operator['perms'] : array();

$pitemid = VikRequest::getInt('Itemid', '', 'request');

?>
<div class="vbo-operator-dashboard">
	<h3><?php echo $operator['first_name'] . ' ' . $operator['last_name']; ?></h3>
	<div class="vbo-operator-dashboard-logout">
		<form action="index.php?option=com_vikbooking" method="post">
			<input type="submit" name="logout" value="<?php echo JText::translate('VBOLOGOUT'); ?>" class="vbo-logout vbo-pref-color-btn-secondary" />
			<input type="hidden" name="option" value="com_vikbooking" />
			<input type="hidden" name="task" value="operatorlogout" />
			<?php
			if (!empty($pitemid)) {
				?>
			<input type="hidden" name="Itemid" value="<?php echo $pitemid; ?>" />
				<?php
			}
			?>
		</form>
	</div>
	<div class="vbo-operator-dashboard-links">
		<ul>
		<?php
		foreach ($operator['perms'] as $perm) {
			?>
			<li>
				<div class="vbo-operator-dashboard-link-left">
					<a href="<?php echo JRoute::rewrite('index.php?option=com_vikbooking&view='.$perm['type'].(!empty($pitemid) ? '&Itemid='.$pitemid : '')); ?>"><?php echo JText::translate('VBOOPERPG'.strtoupper($perm['type'])); ?></a>
				</div>
				<div class="vbo-operator-dashboard-link-right">
					<a class="btn vbo-pref-color-btn" href="<?php echo JRoute::rewrite('index.php?option=com_vikbooking&view='.$perm['type'].(!empty($pitemid) ? '&Itemid='.$pitemid : '')); ?>"><?php echo JText::translate('VBOOPERVIEWPG'); ?></a>
				</div>
			</li>
			<?php
		}
		?>
		</ul>
	</div>
</div>