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
 * Admin Widget parent Class of all sub-classes.
 * 
 * @since 	1.14 (J) - 1.4.0 (WP)
 */
abstract class VikBookingAdminWidget
{
	/**
	 * The name of the widget.
	 *
	 * @var 	string
	 */
	protected $widgetName = null;

	/**
	 * The description of the widget.
	 *
	 * @var 	string
	 */
	protected $widgetDescr = '';

	/**
	 * The icon of the widget in HTML.
	 *
	 * @var 	string
	 * 
	 * @since 	1.15.0 (J) - 1.5.0 (WP)
	 */
	protected $widgetIcon = '';

	/**
	 * The style name of the widget.
	 *
	 * @var 	string
	 * 
	 * @since 	1.15.0 (J) - 1.5.0 (WP)
	 */
	protected $widgetStyleName = 'regular';

	/**
	 * The widget settings.
	 *
	 * @var 	mixed
	 */
	protected $widgetSettings = null;

	/**
	 * The VBO application object.
	 *
	 * @var 	object
	 */
	protected $vbo_app = null;

	/**
	 * The date format.
	 *
	 * @var 	string
	 */
	protected $df = '';

	/**
	 * The date separator.
	 *
	 * @var 	string
	 */
	protected $datesep = '';

	/**
	 * Whether the multitask panel
	 * is invoking the widget.
	 *
	 * @var 	bool
	 */
	protected $is_multitask = false;

	/**
	 * The widget identifier.
	 *
	 * @var 	string
	 */
	private $widgetId = null;

	/**
	 * Class constructors should define some vars for the widget in use.
	 */
	public function __construct()
	{
		$this->vbo_app = VikBooking::getVboApplication();
		$nowdf = VikBooking::getDateFormat(true);
		if ($nowdf == "%d/%m/%Y") {
			$this->df = 'd/m/Y';
		} elseif ($nowdf == "%m/%d/%Y") {
			$this->df = 'm/d/Y';
		} else {
			$this->df = 'Y/m/d';
		}
		$this->datesep = VikBooking::getDateSeparator(true);
	}

	/**
	 * Gets the name of the current widget.
	 * 
	 * @return 	string 	the widget name.
	 */
	public function getName()
	{
		return $this->widgetName;
	}

	/**
	 * Gets the description for the current widget.
	 * 
	 * @return 	string 	the widget description.
	 */
	public function getDescription()
	{
		return $this->widgetDescr;
	}

	/**
	 * Gets the icon for the current widget.
	 * 
	 * @return 	string 	the widget HTML string icon.
	 */
	public function getIcon()
	{
		return !empty($this->widgetIcon) ? $this->widgetIcon : $this->getDefaultIcon();
	}

	/**
	 * Gets the style name for the current widget.
	 * 
	 * @return 	string 	the widget style name.
	 */
	public function getStyleName()
	{
		return $this->widgetStyleName;
	}

	/**
	 * Gets the identifier of the current widget.
	 * 
	 * @return 	string 	the widget identifier.
	 */
	public function getIdentifier()
	{
		if (!$this->widgetId) {
			// fetch widget ID from class name
			$this->widgetId = preg_replace("/^VikBookingAdminWidget/i", '', get_class($this));
			// place an underscore between each camelCase
			$this->widgetId = strtolower(preg_replace("/([a-z])([A-Z])/", '$1_$2', $this->widgetId));
			$this->widgetId = strtolower($this->widgetId);
		}

		return $this->widgetId;
	}

	/**
	 * Turns on/off the flag to detect multitask rendering.
	 * 
	 * @param 	bool 	$in 	true if in multitask panel, or false.
	 * 
	 * @return 	self
	 * 
	 * @since 	1.15.0 (J) - 1.5.0 (WP)
	 */
	public function setInMultitask($in = false)
	{
		$this->is_multitask = (bool)$in;

		return $this;
	}

	/**
	 * Default method to preload the necessary assets of the widget.
	 * Extending classes needing certain assets to be available should
	 * override this method and let it load the CSS/JS assets.
	 * Useful to avoid JS errors during the AJAX generation of a widget.
	 * 
	 * The method can also be helpful for a widget to register watch data.
	 * For example, the last ID retrieved of a certain record can be used
	 * to receive it back during the periodic data-watching and be able
	 * to detect if a new event has occurred to fire a browser notification.
	 * 
	 * @return 	mixed 	watch-data object or null.
	 * 
	 * @since 	1.15.0 (J) - 1.5.0 (WP)
	 */
	public function preload()
	{
		return null;
	}

	/**
	 * Default method to return a list of watch-data and new browser notifications.
	 * Extending classes needing to periodically watch for new events and capable
	 * of emitting browser notifications, can override this method. The last data
	 * to watch should be returned during the preloading. To be used with list().
	 * 
	 * @param 	VBONotificationWatchdata 	$watch_data 	the preloaded watch-data object.
	 * 
	 * @return 	array 						data object to watch next and notifications array.
	 * 
	 * @see 	preload()
	 * 
	 * @since 	1.15.0 (J) - 1.5.0 (WP)
	 */
	public function getNotifications(VBONotificationWatchdata $watch_data = null)
	{
		$watch_next = null;
		$notifications = null;

		return [$watch_next, $notifications];
	}

	/**
	 * Extending Classes should define this method to render the actual
	 * output of the admin widget. Multitask data can be passed along.
	 * 
	 * @param 	VBOMultitaskData 	$data 	optional multitask data object.
	 * 
	 * @since 	1.15.0 (J) - 1.5.0 (WP) type hint added for $data argument.
	 */
	abstract public function render(VBOMultitaskData $data = null);

	/**
	 * Returns the default icon for a widget.
	 * 
	 * @return 	string 	the default widget icon HTML string.
	 */
	protected function getDefaultIcon()
	{
		return '<i class="' . VikBookingIcons::i('cube') . '"></i>';
	}

	/**
	 * Tells the widget if its being rendered via AJAX.
	 * 
	 * @return 	bool 	true if rendering is being made via AJAX.
	 */
	protected function isAjaxRendering()
	{
		$widget_id = VikRequest::getString('widget_id', '', 'request');
		$call 	   = VikRequest::getString('call', '', 'request');

		return ($widget_id == $this->getIdentifier() && $call == 'render');
	}

	/**
	 * Tells the widget if its being rendered in the multitask panel.
	 * This kind of rendering could be in an AJAX context or in a regular
	 * loading flow. Use the apposite method to see if it's an AJAX rendering.
	 * 
	 * @return 	bool 	true if rendering is handled by the multitask panel.
	 * 
	 * @since 	1.15.0 (J) - 1.5.0 (WP)
	 */
	protected function isMultitaskRendering()
	{
		$multitask = VikRequest::getInt('multitask', 0, 'request');

		return ($multitask > 0 || $this->is_multitask === true);
	}

	/**
	 * Gets the configuration parameter name for the widget's settings.
	 * 
	 * @return 	string 	the param name of the settings record.
	 */
	protected function getSettingsParamName()
	{
		return 'admin_widget_' . $this->getIdentifier();
	}

	/**
	 * Gets the configuration parameter name for the widget's settings.
	 * For backward compatibility with older versions, the admin widget
	 * name and related param will contain the ending ".php" extension.
	 * By updating and by using these widgets, the new param name will
	 * be moved to the new nomenclature with no ending ".php" extension.
	 * 
	 * @return 	string 	the param name of the settings record.
	 * 
	 * @since 	1.15.0 (J) - 1.5.0 (WP)
	 * 
	 * @deprecated  Admin widget names no longer contain the ".php" extension.
	 * 				Until this update, only the "sticky_notes" widget was using
	 * 				its own settings, and upon updating, they will be supported
	 * 				just for BC. However, future updates will drop this BC support.
	 */
	protected function getBCSettingsParamName()
	{
		return 'admin_widget_' . $this->getIdentifier() . '.php';
	}

	/**
	 * Loads the widget's settings from the configuration table, if any.
	 * If no record found for this widget, an empty record will be inserted.
	 * 
	 * @return 	mixed 	the widget settings.
	 */
	protected function loadSettings()
	{
		$dbo = JFactory::getDbo();

		$param_name = $this->getSettingsParamName();

		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`=" . $dbo->quote($param_name) . ";";
		$dbo->setQuery($q);
		$dbo->execute();

		if (!$dbo->getNumRows()) {
			/**
			 * For BC support, we attempt to load the legacy settings
			 * param name inclusive of the ".php" extension.
			 * 
			 * @deprecated 	this will be dismissed in future updates.
			 */
			$bc_param_name = $this->getBCSettingsParamName();
			$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`=" . $dbo->quote($bc_param_name) . ";";
			$dbo->setQuery($q);
			$dbo->execute();
			if (!$dbo->getNumRows()) {

				// insert settings record for the widget & return null
				$q = "INSERT INTO `#__vikbooking_config` (`param`,`setting`) VALUES (" . $dbo->quote($param_name) . ", '');";
				$dbo->setQuery($q);
				$dbo->execute();

				return null;

			}
		}

		$settings = $dbo->loadResult();

		if (!strlen($settings)) {
			return null;
		}

		if (in_array(substr($settings, 0, 1), array('{', '['))) {
			// we have detected a JSON string, try to decoded it
			$decoded = json_decode($settings);
			if (function_exists('json_last_error') && json_last_error()) {
				// json is broken, reset settings and return null
				$this->resetSettings();
				return null;
			}
			// return the decoded settings
			return $decoded;
		}

		// return the plain db value otherwise
		return $settings;
	}

	/**
	 * Updates the widget's settings in the configuration table.
	 * 
	 * @param 	mixed 	$data 	the settings to store, must be a scalar.
	 * 
	 * @return 	bool 	true on success, false otherwise.
	 */
	protected function updateSettings($data)
	{
		if ($data === null || !is_scalar($data)) {
			return false;
		}

		$param_name = $this->getSettingsParamName();

		$config = VBOFactory::getConfig();
		$config->set($param_name, $data);

		return true;
	}

	/**
	 * Resets the settings of the widget.
	 * 
	 * @return 	bool
	 */
	public function resetSettings()
	{
		$dbo = JFactory::getDbo();

		$param_name = $this->getSettingsParamName();

		$q = "SELECT `setting` FROM `#__vikbooking_config` WHERE `param`=" . $dbo->quote($param_name) . ";";
		$dbo->setQuery($q);
		$dbo->execute();
		if (!$dbo->getNumRows()) {
			// settings never used
			return false;
		}

		$q = "DELETE FROM `#__vikbooking_config` WHERE `param`=" . $dbo->quote($param_name) . ";";
		$dbo->setQuery($q);
		$dbo->execute();

		return true;
	}

	/**
	 * Method invoked during AJAX requests for removing an instance of this widget.
	 * By default we try to unset the passed instance index from the settings array.
	 * If the widget does not use settings, nothing is done. Widgets could override this method.
	 * 
	 * @return 	bool 	true if settings needed to be updated, false otherwise.
	 */
	public function removeInstance()
	{
		$settings = $this->loadSettings();
		if ($settings === null || !is_array($settings)) {
			return false;
		}

		$widget_instance = VikRequest::getInt('widget_instance', -1, 'request');

		if (!isset($settings[$widget_instance])) {
			// settings index not found
			return false;
		}

		// splice the array to remove the requested settings instance
		array_splice($settings, $widget_instance, 1);

		// update widget's settings
		$this->updateSettings(json_encode($settings));

		return true;
	}

	/**
	 * Method invoked during AJAX requests for moving an instance of this widget.
	 * This occurs when dragging and dropping the same type of widget to a different position.
	 * If the widget does not use settings, nothing is done. Widgets could override this method.
	 * 
	 * @return 	bool 	true if settings needed to be updated, false otherwise.
	 */
	public function sortInstance()
	{
		$settings = $this->loadSettings();
		if ($settings === null || !is_array($settings)) {
			return false;
		}

		$widget_index_old = VikRequest::getInt('widget_index_old', -1, 'request');
		$widget_index_new = VikRequest::getInt('widget_index_new', -1, 'request');

		if (!isset($settings[$widget_index_old]) || !isset($settings[$widget_index_new])) {
			// settings index not found
			return false;
		}

		// move the settings requested from the old index to the new index
		$extracted = array_splice($settings, $widget_index_old, 1);
		array_splice($settings, $widget_index_new, 0, $extracted);

		// update widget's settings
		$this->updateSettings(json_encode($settings));

		return true;
	}

	/**
	 * Returns the name of the user currently logged in.
	 * 
	 * @return 	string 	the name of the current user.
	 */
	protected function getLoggedUserName()
	{
		$user = JFactory::getUser();
		$name = $user->name;

		return $name;
	}

	/**
	 * Rewrites a given URI to perform an AJAX request.
	 * 
	 * @param 	string 	$uri 	the optional URI plus query to force.
	 * 
	 * @return 	string 	the proper AJAX uri for the current platform.
	 * 
	 * @since 	1.15.0 (J) - 1.5.0 (WP)
	 */
	protected function getExecWidgetAjaxUri($uri = '')
	{
		if (empty($uri)) {
			$uri = 'index.php?option=com_vikbooking&task=exec_admin_widget';
		}

		return VikBooking::ajaxUrl($uri);
	}

	/**
	 * Returns the date format in VBO of a particular type.
	 *
	 * @param 	string 	$type
	 *
	 * @return 	string
	 * 
	 * @since 	1.15.0 (J) - 1.5.0 (WP)
	 */
	protected function getDateFormat($type = 'date')
	{
		if ($this->df == 'd/m/Y') {
			$juidf = 'dd/mm/yy';
		} elseif ($this->df == 'm/d/Y') {
			$juidf = 'mm/dd/yy';
		} else {
			$juidf = 'yy/mm/dd';
		}

		switch ($type) {
			case 'jui':
				return $juidf;
			case 'joomla':
			case 'wordpress':
				return VikBooking::getDateFormat(true);
			default:
				return $this->df;
		}
	}
}
