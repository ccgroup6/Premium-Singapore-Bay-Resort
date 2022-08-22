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

// make sure the basic setup has been completed
$up_running = true;

/**
 * @wponly - check if some shortcodes have been defined before showing the Dashboard
 */
$model = JModel::getInstance('vikbooking', 'shortcodes');
$itemid = $model->all('post_id');
//

if ($this->arrayfirst['totprices'] < 1 || $this->arrayfirst['totrooms'] < 1 || $this->arrayfirst['totdailyfares'] < 1 || count($itemid) < 1) {
	// first setup helper needed
	$up_running = false;

	?>
<div class="vbo-dashboard-firstsetup-wrap">
	<div class="vbo-dashboard-firstsetup-container">
		<div class="vbo-dashboard-firstsetup-head">
			<h3><?php echo JText::translate('VBDASHFIRSTSETTITLE'); ?></h3>
			<h4><?php echo JText::translate('VBDASHFIRSTSETSUBTITLE'); ?></h4>
		</div>
		<?php
		/**
		 * Load sampledata template.
		 * 
		 * @since 	1.3.9
		 */
		echo $this->loadTemplate('sampledata');
		//
		?>
		<div class="vbo-dashboard-firstsetup-body">
			<div class="vbo-dashboard-firstsetup-task vbo-dashboard-firstsetup-task-<?php echo $this->arrayfirst['totprices'] < 1 ? 'incomplete' : 'completed'; ?>">
				<div class="vbo-dashboard-firstsetup-task-wrap">
					<div class="vbo-dashboard-firstsetup-task-number">
						<span>1.</span>
					</div>
					<div class="vbo-dashboard-firstsetup-task-details">
						<div class="vbo-dashboard-firstsetup-task-name"><?php echo JText::translate('VBDASHNOPRICES'); ?></div>
						<div class="vbo-dashboard-firstsetup-task-count">
							<span class="vbo-dashboard-firstsetup-task-val"><?php echo $this->arrayfirst['totprices']; ?></span>
						<?php
						if ($this->arrayfirst['totprices'] > 0) {
							?>
							<span class="vbo-dashboard-firstsetup-done"><?php VikBookingIcons::e('check-circle'); ?></span>
							<?php
						}
						?>
						</div>
					</div>
				<?php
				if ($this->arrayfirst['totprices'] < 1) {
					?>
					<div class="vbo-dashboard-firstsetup-task-action">
						<a href="index.php?option=com_vikbooking&task=prices" class="button button-secondary"><?php echo JText::translate('VBCONFIGURETASK'); ?></a>
					</div>
					<?php
				}
				?>
					<div class="vbo-dashboard-firstsetup-task-description">
						<p><?php echo JText::translate('VBOWIZARDRPLANSMESS'); ?></p>
					</div>
				</div>
			</div>
			<div class="vbo-dashboard-firstsetup-task vbo-dashboard-firstsetup-task-<?php echo $this->arrayfirst['totrooms'] < 1 ? 'incomplete' : 'completed'; ?>">
				<div class="vbo-dashboard-firstsetup-task-wrap">
					<div class="vbo-dashboard-firstsetup-task-number">
						<span>2.</span>
					</div>
					<div class="vbo-dashboard-firstsetup-task-details">
						<div class="vbo-dashboard-firstsetup-task-name"><?php echo JText::translate('VBDASHNOROOMS'); ?></div>
						<div class="vbo-dashboard-firstsetup-task-count">
							<span class="vbo-dashboard-firstsetup-task-val"><?php echo $this->arrayfirst['totrooms']; ?></span>
						<?php
						if ($this->arrayfirst['totrooms'] > 0) {
							?>
							<span class="vbo-dashboard-firstsetup-done"><?php VikBookingIcons::e('check-circle'); ?></span>
							<?php
						}
						?>
						</div>
					</div>
					<?php
				if ($this->arrayfirst['totrooms'] < 1) {
					?>
					<div class="vbo-dashboard-firstsetup-task-action">
						<a href="index.php?option=com_vikbooking&task=rooms" class="button button-secondary"><?php echo JText::translate('VBCONFIGURETASK'); ?></a>
					</div>
					<?php
				}
				?>
					<div class="vbo-dashboard-firstsetup-task-description">
						<p><?php echo JText::translate('VBODASHFIRSTSETUPROOMS'); ?></p>
					</div>
				<?php
				/**
				 * @wponly 	we print a button to import the bookings from third party plugins.
				 * 
				 * @since 	1.3.5
				 */
				if ($this->arrayfirst['totrooms'] > 0) {
					$tpp_supported = VikBooking::canImportBookingsFromThirdPartyPlugins();
					if ($tpp_supported !== false) {
						?>
					<div class="vbo-dashboard-firstsetup-importbftpp">
						<h5 class="vbo-dashboard-firstsetup-importbftpp-title"><?php echo JText::translate('VBO_IMPBFTPP_WANTTO'); ?></h5>
						<a class="btn btn-large btn-success" href="index.php?option=com_vikbooking&view=importbftpp"><?php VikBookingIcons::e('download'); ?> <?php echo JText::translate('VBO_IMPBFTPP_DOIMPORT_SHORT'); ?></a>
					</div>
						<?php
					}
				}
				//
				?>
				</div>
			</div>
			<div class="vbo-dashboard-firstsetup-task vbo-dashboard-firstsetup-task-<?php echo $this->arrayfirst['totdailyfares'] < 1 ? 'incomplete' : 'completed'; ?>">
				<div class="vbo-dashboard-firstsetup-task-wrap">
					<div class="vbo-dashboard-firstsetup-task-number">
						<span>3.</span>
					</div>
					<div class="vbo-dashboard-firstsetup-task-details">
						<div class="vbo-dashboard-firstsetup-task-name"><?php echo JText::translate('VBDASHNODAILYFARES'); ?></div>
						<div class="vbo-dashboard-firstsetup-task-count">
							<span class="vbo-dashboard-firstsetup-task-val"><?php echo $this->arrayfirst['totdailyfares'] < 1 ? '0' : ''; ?></span>
						<?php
						if ($this->arrayfirst['totdailyfares'] > 0) {
							?>
							<span class="vbo-dashboard-firstsetup-done"><?php VikBookingIcons::e('check-circle'); ?></span>
							<?php
						}
						?>
						</div>
					</div>
					<?php
				if ($this->arrayfirst['totdailyfares'] < 1) {
					?>
					<div class="vbo-dashboard-firstsetup-task-action">
						<a href="index.php?option=com_vikbooking&task=tariffs" class="button button-secondary"><?php echo JText::translate('VBCONFIGURETASK'); ?></a>
					</div>
					<?php
				}
				?>
					<div class="vbo-dashboard-firstsetup-task-description">
						<p><?php echo JText::translate('VBODASHFIRSTSETUPTARIFFS'); ?></p>
					</div>
				</div>
			</div>
			<div class="vbo-dashboard-firstsetup-task vbo-dashboard-firstsetup-task-<?php echo count($itemid) < 1 ? 'incomplete' : 'completed'; ?>">
				<div class="vbo-dashboard-firstsetup-task-wrap">
					<div class="vbo-dashboard-firstsetup-task-number">
						<span>4.</span>
					</div>
					<div class="vbo-dashboard-firstsetup-task-details">
						<div class="vbo-dashboard-firstsetup-task-name"><?php echo JText::translate('VBFIRSTSETSHORTCODES'); ?></div>
						<div class="vbo-dashboard-firstsetup-task-count">
							<span class="vbo-dashboard-firstsetup-task-val"><?php echo count($itemid); ?></span>
						<?php
						if (count($itemid) > 0) {
							?>
							<span class="vbo-dashboard-firstsetup-done"><?php VikBookingIcons::e('check-circle'); ?></span>
							<?php
						}
						?>
						</div>
					</div>
					<?php
				if (count($itemid) < 1) {
					?>
					<div class="vbo-dashboard-firstsetup-task-action">
						<a href="index.php?option=com_vikbooking&view=shortcodes" class="button button-secondary"><?php echo JText::translate('VBCONFIGURETASK'); ?></a>
					</div>
					<?php
				}
				?>
					<div class="vbo-dashboard-firstsetup-task-description">
						<p><?php echo JText::translate('VBODASHFIRSTSETUPSHORTCODES'); ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
	<?php
}

if ($up_running === true) {
	// load the admin widgets
	?>
<div class="vbo-dashboard-fullcontainer vbo-admin-widgets-container">
	<?php
	/**
	 * Load the template file for the admin widgets when the first setup is complete.
	 * 
	 * @since 	1.4.0
	 */
	echo $this->loadTemplate('widgets');
	//
	?>
</div>
	<?php
}
