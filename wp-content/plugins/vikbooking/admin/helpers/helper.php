<?php
/**
 * @package     VikBooking
 * @subpackage  com_vikbooking
 * @author      Alessio Gaggii - e4j - Extensionsforjoomla.com
 * @copyright   Copyright (C) 2018 e4j - Extensionsforjoomla.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @link        https://vikwp.com
 */

// No direct access to this file
defined('ABSPATH') or die('No script kiddies please!');

class VikBookingHelper
{
	public static function printHeader($highlight = "")
	{
		$cookie = JFactory::getApplication()->input->cookie;
		$tmpl = VikRequest::getVar('tmpl');
		if ($tmpl == 'component') {
			return;
		}
		$view = VikRequest::getVar('view');
		/**
		 * @wponly Hide menu for Pro-update views
		 */
		$skipmenu = array('getpro');
		if (in_array($view, $skipmenu)) {
			return;
		}
		//
		$channel_manager_btn = '';
		if (is_file(VCM_SITE_PATH.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'lib.vikchannelmanager.php')) {
			$channel_manager_btn = '<li><span class="vmenulink"><a href="index.php?option=com_vikchannelmanager" class="vcmlink"><i class="vboicn-cloud"></i>'.JText::translate('VBMENUCHANNELMANAGER').'</a></span></li>';
		}
		$backlogo = VikBooking::getBackendLogo();
		$vbo_auth_global = JFactory::getUser()->authorise('core.vbo.global', 'com_vikbooking');
		$vbo_auth_rateplans = JFactory::getUser()->authorise('core.vbo.rateplans', 'com_vikbooking');
		$vbo_auth_rooms = JFactory::getUser()->authorise('core.vbo.rooms', 'com_vikbooking');
		$vbo_auth_pricing = JFactory::getUser()->authorise('core.vbo.pricing', 'com_vikbooking');
		$vbo_auth_bookings = JFactory::getUser()->authorise('core.vbo.bookings', 'com_vikbooking');
		$vbo_auth_availability = JFactory::getUser()->authorise('core.vbo.availability', 'com_vikbooking');
		$vbo_auth_management = JFactory::getUser()->authorise('core.vbo.management', 'com_vikbooking');
		$vbo_auth_pms = JFactory::getUser()->authorise('core.vbo.pms', 'com_vikbooking');
		$reviews_dld = 0;

		/**
		 * Check VCM subscription status.
		 * 
		 * @since 	1.15.0 (J) - 1.5.0 (WP)
		 */
		$vcm_expiration_reminder = VikBooking::getVCMSubscriptionStatus();

		?>
		<div class="vbo-menu-container<?php echo $view == 'dashboard' ? ' vbo-menu-container-closer' : ''; ?>">
			<div class="vbo-menu-left">
				<a href="index.php?option=com_vikbooking"><img src="<?php echo VBO_ADMIN_URI.(!empty($backlogo) ? 'resources/'.$backlogo : 'vikbooking.png'); ?>" alt="VikBooking Logo" /></a>
			</div>
			<div class="vbo-menu-right">
				<ul class="vbo-menu-ul">
					<?php
					if ($vbo_auth_global || $vbo_auth_management) {
					?><li class="vbo-menu-parent-li">
						<span><?php VikBookingIcons::e('cogs'); ?><a href="javascript: void(0);"><?php echo JText::translate('VBMENUFOUR'); ?></a></span>
						<ul class="vbo-submenu-ul">
							<?php if ($vbo_auth_global) : ?><li><span class="<?php echo ($highlight=="14" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=payments"><?php echo JText::translate('VBMENUTENEIGHT'); ?></a></span></li><?php endif; ?>
							<?php if ($vbo_auth_global) : ?><li><span class="<?php echo ($highlight=="16" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=customf"><?php echo JText::translate('VBMENUTENTEN'); ?></a></span></li><?php endif; ?>
							<?php if ($vbo_auth_management) : ?><li><span class="<?php echo ($highlight=="21" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=translations"><?php echo JText::translate('VBMENUTRANSLATIONS'); ?></a></span></li><?php endif; ?>
							<?php if ($vbo_auth_global) : ?><li><span class="<?php echo ($highlight=="11" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=config"><?php echo JText::translate('VBMENUTWELVE'); ?></a></span></li><?php endif; ?>
						</ul>
					</li><?php
					}
					if ($vbo_auth_rateplans) {
					?><li class="vbo-menu-parent-li">
						<span><?php VikBookingIcons::e('briefcase'); ?><a href="javascript: void(0);"><?php echo JText::translate('VBMENURATEPLANS'); ?></a></span>
						<ul class="vbo-submenu-ul">
							<li><span class="<?php echo ($highlight=="2" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=iva"><?php echo JText::translate('VBMENUNINE'); ?></a></span></li>
							<li><span class="<?php echo ($highlight=="1" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=prices"><?php echo JText::translate('VBMENUFIVE'); ?></a></span></li>
							<li><span class="<?php echo ($highlight=="17" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=coupons"><?php echo JText::translate('VBMENUCOUPONS'); ?></a></span></li>
							<li><span class="<?php echo ($highlight=="packages" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=packages"><?php echo JText::translate('VBMENUPACKAGES'); ?></a></span></li>
						</ul>
					</li><?php
					}
					if ($vbo_auth_rooms || $vbo_auth_pricing) {
					?><li class="vbo-menu-parent-li">
						<span><?php VikBookingIcons::e('bed'); ?><a href="javascript: void(0);"><?php echo JText::translate('VBMENUTWO'); ?></a></span>
						<ul class="vbo-submenu-ul">
							<?php if ($vbo_auth_rooms) : ?><li><span class="<?php echo ($highlight=="4" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=categories"><?php echo JText::translate('VBMENUSIX'); ?></a></span></li><?php endif; ?>
							<?php if ($vbo_auth_rooms) : ?><li><span class="<?php echo ($highlight=="5" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=carat"><?php echo JText::translate('VBMENUTENFOUR'); ?></a></span></li><?php endif; ?>
							<?php if ($vbo_auth_pricing) : ?><li><span class="<?php echo ($highlight=="6" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=optionals"><?php echo JText::translate('VBMENUTENFIVE'); ?></a></span></li><?php endif; ?>
							<?php if ($vbo_auth_rooms) : ?><li><span class="<?php echo ($highlight=="7" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=rooms"><?php echo JText::translate('VBMENUTEN'); ?></a></span></li><?php endif; ?>
						</ul>
					</li><?php
					}
					if ($vbo_auth_pricing) {
					?><li class="vbo-menu-parent-li">
						<span><i class="vboicn-calculator"></i><a href="javascript: void(0);"><?php echo JText::translate('VBMENUFARES'); ?></a></span>
						<ul class="vbo-submenu-ul">
							<li><span class="<?php echo ($highlight=="fares" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=tariffs"><?php echo JText::translate('VBMENUPRICESTABLE'); ?></a></span></li>
							<li><span class="<?php echo ($highlight=="13" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=seasons"><?php echo JText::translate('VBMENUTENSEVEN'); ?></a></span></li>
							<li><span class="<?php echo ($highlight=="restrictions" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=restrictions"><?php echo JText::translate('VBMENURESTRICTIONS'); ?></a></span></li>
							<li><span class="<?php echo ($highlight=="20" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=ratesoverv"><?php echo JText::translate('VBMENURATESOVERVIEW'); ?></a></span></li>
						</ul>
					</li><?php
					}
					?><li class="vbo-menu-parent-li">
						<span><?php VikBookingIcons::e('calendar-check'); ?><a href="javascript: void(0);"><?php echo JText::translate('VBMENUTHREE'); ?></a></span>
						<ul class="vbo-submenu-ul">
							<li><span class="<?php echo (in_array($highlight, ['18', 'shortcodes', 'acl']) ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking"><?php echo JText::translate('VBMENUDASHBOARD'); ?></a></span></li>
							<?php if ($vbo_auth_availability || $vbo_auth_bookings) : ?><li><span class="<?php echo ($highlight=="19" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=calendar"><?php echo JText::translate('VBMENUQUICKRES'); ?></a></span></li><?php endif; ?>
							<?php if ($vbo_auth_availability) : ?><li><span class="<?php echo ($highlight=="15" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=overv"><?php echo JText::translate('VBMENUTENNINE'); ?></a></span></li><?php endif; ?>
							<?php if ($vbo_auth_bookings) : ?><li><span class="<?php echo ($highlight=="8" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=orders"><?php echo JText::translate('VBMENUSEVEN'); ?></a></span></li><?php endif; ?>
							<?php echo $vbo_auth_availability || $vbo_auth_bookings ? $channel_manager_btn : ''; ?>
						</ul>
					</li><?php
					if ($vbo_auth_management) {
					?><li class="vbo-menu-parent-li">
						<span><?php VikBookingIcons::e('chart-pie'); ?><a href="javascript: void(0);"><?php echo JText::translate('VBMENUMANAGEMENT'); ?></a></span>
						<ul class="vbo-submenu-ul">
							<li><span class="<?php echo ($highlight=="22" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=customers"><?php echo JText::translate('VBMENUCUSTOMERS'); ?></a></span></li>
							<li><span class="<?php echo ($highlight=="invoices" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=invoices"><?php echo JText::translate('VBMENUINVOICES'); ?></a></span></li>
							<li><span class="<?php echo ($highlight=="stats" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=stats"><?php echo JText::translate('VBMENUSTATS'); ?></a></span></li>
							<li><span class="<?php echo ($highlight=="trackings" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=trackings"><?php echo JText::translate('VBMENUTRACKINGS'); ?></a></span></li>
							<li><span class="<?php echo (in_array($highlight, ["crons", "managecron"]) ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;view=crons"><?php echo JText::translate('VBMENUCRONS'); ?></a></span></li>
						</ul>
					</li><?php
					}
					if ($vbo_auth_pms) {
					?><li class="vbo-menu-parent-li">
						<span><?php VikBookingIcons::e('tasks'); ?><a href="javascript: void(0);"><?php echo JText::translate('VBMENUPMS'); ?></a></span>
						<ul class="vbo-submenu-ul">
							<li><span class="<?php echo ($highlight=="operators" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=operators"><?php echo JText::translate('VBMENUOPERATORS'); ?></a></span></li>
							<li><span class="<?php echo ($highlight=="tableaux" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=tableaux"><?php echo JText::translate('VBMENUTABLEAUX'); ?></a></span></li>
							<li><span class="<?php echo ($highlight=="pmsreports" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=pmsreports"><?php echo JText::translate('VBMENUPMSREPORTS'); ?></a></span></li>
							<li><span class="<?php echo ($highlight=="einvoicing" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikbooking&amp;task=einvoicing"><?php echo JText::translate('VBMENUEINVOICING'); ?></a></span></li>
						</ul>
					</li><?php
					}
					?>
				</ul>
				<div class="vbo-menu-updates">
			<?php
			/**
			 * @wponly PRO Version
			 */
			VikBookingLoader::import('update.license');
			if (!VikBookingLicense::isPro()) {
				?>
					<button type="button" class="vbo-gotopro" title="<?php echo addslashes(JText::translate('VBOGOTOPROBTN')); ?>" onclick="document.location.href='admin.php?option=com_vikbooking&view=gotopro';">
						<?php VikBookingIcons::e('rocket'); ?>
						<span><?php echo JText::translate('VBOGOTOPROBTN'); ?></span>
					</button>
				<?php
			} else {
				?>
					<button type="button" class="vbo-alreadypro" title="<?php echo addslashes(JText::translate('VBOISPROBTN')); ?>" onclick="document.location.href='admin.php?option=com_vikbooking&view=gotopro';">
						<?php VikBookingIcons::e('trophy'); ?>
						<span><?php echo JText::translate('VBOISPROBTN'); ?></span>
					</button>
				<?php
			}

			if ($vbo_auth_management && in_array($highlight, array('18', '11', '8'))) {
				// VCM Opportunities
				$opp = VikBooking::getVcmOpportunityInstance();
				if (!is_null($opp)) {
					// download opportunities if it's time to do it
					if ($opp->shouldRequestOpportunities()) {
						$opp->downloadOpportunities();
					}
					// count opportunities
					$opp_filters = array(
						'status' => 0,
						'action' => 0,
					);
					$new_opp_count = count($opp->loadOpportunities($opp_filters, null, null));
					if ($new_opp_count > 0) {
						/**
						 * @wponly  we use admin.php for the button link
						 */
						?>
					<button type="button" class="vbo-opportunities-btnbadge" data-opportunity-count="<?php echo $new_opp_count; ?>" title="<?php echo addslashes(JText::translate('VBOGOTOOPPORTUNITIES')); ?>" onclick="document.location.href='admin.php?option=com_vikchannelmanager&task=opportunities';">
						<?php VikBookingIcons::e('crown'); ?>
						<span><?php echo JText::translate('VBOGOTOOPPORTUNITIES'); ?></span>
					</button>
						<?php
					}
				}

				// Guest Reviews
				$reviews_dld = VikBooking::shouldDownloadReviews();
				/**
				 * @wponly  we use admin.php for the button link
				 */
				?>
					<button type="button" class="vbo-reviews-btnbadge" data-reviews-count="" title="<?php echo addslashes(JText::translate('VBOPANELREVIEWS')); ?>" onclick="<?php echo $reviews_dld >= 0 ? "window.open('admin.php?option=com_vikchannelmanager&task=reviews', '_blank'); jQuery(this).removeClass('vbo-reviews-btnbadge-alert').attr('data-reviews-count', '');" : "alert('" . addslashes(JText::translate('VBOGUESTREVSVCMREQ')) . "');"; ?>">
						<?php VikBookingIcons::e('star'); ?>
						<span><?php echo JText::translate('VBOPANELREVIEWS'); ?></span>
					</button>
				<?php
			}

			/**
			 * Admin widgets multitasking side panel.
			 * 
			 * @since 	1.15.0 (J) - 1.5.0 (WP)
			 */
			if ($view != 'dashboard') {
				?>
					<button type="button" class="vbo-multitasking-apps">
						<?php VikBookingIcons::e('th'); ?>
					</button>

					<?php
					// prepare the layout data array
					$layout_data = array(
						'vbo_page' 	  => (empty($view) ? VikRequest::getString('task', '') : $view),
						'btn_trigger' => '.vbo-multitasking-apps',
					);
					echo JLayoutHelper::render('sidepanel.multitasking', $layout_data);
					?>
				<?php
			}
			?>	
				</div>
			</div>
		</div>

		<div style="clear: both;"></div>

		<script type="text/javascript">
		var vbo_menu_type = <?php echo (int)$cookie->get('vboMenuType', '0', 'string'); ?>;
		var vbo_menu_on = ((vbo_menu_type % 2) == 0);
		//
		function vboDetectMenuChange(e) {
			e = e || window.event;
			if (e.key && e.key === 'Enter' && e.metaKey) {
				VBOCore.emitMultitaskEvent(VBOCore.multitask_shortcut_event);
				return;
			}
			if (e.key && e.key === 'f' && e.metaKey && VBOCore.side_panel_on) {
				VBOCore.emitMultitaskEvent(VBOCore.multitask_searchfs_event);
				e.preventDefault();
				return;
			}
			if (((e.key && e.key === 'm') || e.which == 77 || e.keyCode == 77) && e.altKey) {
				//ALT+M
				vbo_menu_type++;
				vbo_menu_on = ((vbo_menu_type % 2) == 0);
				console.info(vbo_menu_type, vbo_menu_on);
				// set cookie to remember the menu-type preference
				var nd = new Date();
				nd.setTime(nd.getTime() + (365*24*60*60*1000));
				document.cookie = "vboMenuType="+vbo_menu_type+"; expires=" + nd.toUTCString() + "; path=/; SameSite=Lax";
			}
		}
		document.onkeydown = vboDetectMenuChange;
		//
		jQuery(function() {
			jQuery('.vbo-menu-parent-li').click(function() {
				if (jQuery(this).find('ul.vbo-submenu-ul').is(':visible')) {
					vbo_menu_on = false;
					return;
				}
				jQuery('ul.vbo-submenu-ul').hide();
				jQuery(this).find('ul.vbo-submenu-ul').show();
				vbo_menu_on = true;
			});
			jQuery('.vbo-menu-parent-li').hover(
				function() {
					if (vbo_menu_on === true) {
						jQuery(this).addClass('vbo-menu-parent-li-opened');
						jQuery(this).find('ul.vbo-submenu-ul').show();
					}
				},function() {
					if (vbo_menu_on === true) {
						jQuery(this).removeClass('vbo-menu-parent-li-opened');
						jQuery(this).find('ul.vbo-submenu-ul').hide();
					}
				}
			);
			var targetY = jQuery('.vbo-menu-right').offset().top + jQuery('.vbo-menu-right').outerHeight() + 150;
			jQuery(document).click(function(event) { 
				if (!jQuery(event.target).closest('.vbo-menu-right').length && parseInt(event.which) == 1 && event.pageY < targetY) {
					jQuery('ul.vbo-submenu-ul').hide();
					vbo_menu_on = true;
				}
			});
			if (jQuery('.vmenulinkactive').length) {
				jQuery('.vmenulinkactive').parent('li').parent('ul').parent('li').addClass('vbo-menu-parent-li-active');
				if ((vbo_menu_type % 2) != 0) {
					jQuery('.vmenulinkactive').parent('li').parent('ul').show();
				}
			}
			if (<?php echo $reviews_dld; ?> > 0) {
				// download new reviews
				jQuery.ajax({
					type: 'POST',
					url: 'index.php?option=com_vikchannelmanager&task=reviews_download&tmpl=component',
					data: {
						return: 1,
						everywhere: 1
					}
				}).done(function(resp) {
					try {
						if (resp.indexOf('e4j.ok') >= 0) {
							var tot_reviews = resp.replace('e4j.ok.', '');
							tot_reviews = parseInt(tot_reviews);
							if (!isNaN(tot_reviews) && tot_reviews > 0) {
								// update button badge
								jQuery('.vbo-reviews-btnbadge').attr('data-reviews-count', tot_reviews).addClass('vbo-reviews-btnbadge-alert');
							}
						}
					}
					catch(err) {
						console.error('Error in decoding response', err, resp);
					}
				}).fail(function(resp){
					console.error('Could not download new reviews from Channel Manager', resp);
				});
			}
		});
		</script>
		<?php

		// handle subscription expiration reminder modal
		if (is_array($vcm_expiration_reminder) && $vcm_expiration_reminder['days_to_exp'] >= 0) {
			// subscription is expiring, but it's not expired yet, display a modal-reminder
			?>
		<div class="vbo-info-overlay-block vbo-info-overlay-expiration-reminder">
			<div class="vbo-info-overlay-content">
				<h3 style="color: var(--vbo-red-color);"><i class="vboicn-warning"></i> <?php echo JText::translate('VCM_EXPIRATION_REMINDERS'); ?></h3>
				<div>
					<h4><?php echo JText::sprintf('VCM_EXPIRATION_REMINDER_DAYS', $vcm_expiration_reminder['days_to_exp'], $vcm_expiration_reminder['expiration_ymd']); ?></h4>
				</div>
				<div class="vbo-info-overlay-footer">
					<div class="vbo-info-overlay-footer-right">
						<button type="button" class="btn btn-danger" onclick="jQuery('.vbo-info-overlay-expiration-reminder').fadeOut();"><?php echo JText::translate('VBOBTNKEEPREMIND'); ?></button>
					</div>
				</div>
			</div>
		</div>

		<script type="text/javascript">
			jQuery(function() {
				jQuery('.vbo-info-overlay-expiration-reminder').fadeIn();
			});
		</script>
			<?php
		}
	}
	
	public static function printFooter()
	{
		$tmpl = VikRequest::getVar('tmpl');
		if ($tmpl == 'component') return;
		/**
		 * @wponly "Powered by" is VikWP.com
		 */
		echo '<br clear="all" />' . '<div id="hmfooter">' . JText::sprintf('VBFOOTER', VIKBOOKING_SOFTWARE_VERSION) . ' <a href="https://vikwp.com/" target="_blank">VikWP - vikwp.com</a></div>';
	}

	//VikUpdater plugin methods - Start
	public static function pUpdateProgram($version)
	{
		/**
		 * @wponly 	do nothing
		 */
	}

	/**
	 * Scan changelog structure.
	 *
	 * @param 	array 	$arr 	The list containing changelog elements.
	 * @param 	mixed 	$html 	The html built. 
	 * 							Specify false to echo the structure immediately.
	 *
	 * @return 	string|void 	The HTML structure or nothing.
	 */
	private static function digChangelog(array $arr, $html = '')
	{

		foreach( $arr as $elem ):

			if ( isset($elem->tag) ):

				// build attributes

				$attributes = "";
				if ( isset($elem->attributes) ) {

					foreach( $elem->attributes as $k => $v ) {
						$attributes .= " $k=\"$v\"";
					}

				}

				// build tag opening

				$str = "<{$elem->tag}$attributes>";

				if ( $html ) {
					$html .= $str;
				} else {
					echo $str;
				}

				// display contents

				if ( isset($elem->content) ) {

					if ( $html ) {
						$html .= $elem->content;
					} else {
						echo $elem->content;
					}

				}

				// recursive iteration for elem children

				if ( isset($elem->children) ) {
					self::digChangelog($elem->children, $html);
				}

				// build tag closure

				$str = "</{$elem->tag}>";

				if ( $html ) {
					$html .= $str;
				} else {
					echo $str;
				}

			endif;

		endforeach;

		return $html;
	}
	//VikUpdater plugin methods - End

}