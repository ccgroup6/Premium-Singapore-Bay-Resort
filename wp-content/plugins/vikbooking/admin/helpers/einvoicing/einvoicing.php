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
 * eInvoicing parent Class of all sub-classes
 */
abstract class VikBookingEInvoicing
{
	protected $driverFile = '';
	protected $driverName = '';
	protected $driverId = null;
	protected $driverFilters = array();
	protected $driverButtons = array();
	protected $driverScript = '';
	protected $hasSettings = false;
	protected $warning = [];
	protected $error = [];
	protected $info = [];
	protected $dbo;
	protected $session;

	protected $cols = array();
	protected $rows = array();
	protected $footerRow = array();

	/**
	 * @var  mixed 	flag modifiable by external invokes (cron, analogic invoices)
	 */
	public $externalCall = null;

	/**
	 * @var  array 	data injectable by external callers
	 */
	public $externalData = array();

	/**
	 * Class constructors should define some vars of the driver in use.
	 */
	public function __construct() {
		$this->dbo = JFactory::getDbo();
		$this->session = JFactory::getSession();

		/**
		 * Turn on the flag that the eInvocing class is running
		 * so that other parts of the framework can avoid to
		 * invoke again this class and to generate double e-invoices.
		 */
		defined('VBO_EINVOICING_RUN') OR define('VBO_EINVOICING_RUN', 1);
	}

	/**
	 * Extending Classes should define this method
	 * to get the name of class file.
	 */
	abstract public function getFileName();

	/**
	 * Extending Classes should define this method
	 * to get the name of the driver.
	 */
	abstract public function getName();

	/**
	 * Extending Classes should define this method
	 * to get the filters of the driver.
	 */
	abstract public function getFilters();

	/**
	 * Extending Classes should define this method
	 * to get the driver action buttons.
	 */
	abstract public function getButtons();

	/**
	 * Extending Classes should define this method
	 * to generate the bookings data (cols and rows).
	 */
	abstract public function getBookingsData();

	/**
	 * Extending Classes should define this method
	 * to prepare the settings of the driver before saving.
	 * Views should not call this method.
	 */
	abstract protected function prepareSavingSettings();

	/**
	 * Extending Classes should define this method
	 * to generate the electronic invoices.
	 */
	abstract public function generateEInvoices();

	/**
	 * Extending Classes should define this method
	 * to generate the electronic invoice for
	 * the given booking ID or booking array.
	 */
	abstract public function generateEInvoice($data);

	/**
	 * Extending Classes should define this method
	 * to generate the electronic invoice from a custom
	 * invoice not related to any booking ID.
	 * Existing (analogic) invoice record and customer
	 * shall be passed as arguments.
	 */
	abstract public function prepareCustomInvoiceData($invoice, $customer);

	/**
	 * Extending Classes should define this method
	 * to check if an electronic invoice exists for
	 * a given booking ID or Number. This is useful
	 * for external scripts to request later the
	 * obliteration of the invoice to create a new one.
	 * For example, during the update of a custom invoice.
	 */
	abstract public function eInvoiceExists($data);

	/**
	 * Extending Classes should define this method
	 * to obliterate an existingelectronic invoice.
	 * This is useful for external scripts to request the
	 * obliteration of the invoice to create a new one.
	 * For example, during the update of a custom invoice.
	 */
	abstract public function obliterateEInvoice($data);

	/**
	 * Loads the settings for the driver, which parameters
	 * must be saved as a JSON encoded string. Never cache
	 * the settings fetched as they may be updated by other methods.
	 *
	 * @return 	mixed 	array if settings exist, false otherwise
	 */
	protected function loadSettings()
	{
		$q = "SELECT * FROM `#__vikbooking_einvoicing_config` WHERE `driver`=".$this->dbo->quote($this->getFileName())." ORDER BY `id` DESC LIMIT 1;";
		$this->dbo->setQuery($q);
		$this->dbo->execute();
		if (!$this->dbo->getNumRows()) {
			// no settings defined for this driver
			return false;
		}
		$settings = $this->dbo->loadAssoc();
		$settings['params'] = !empty($settings['params']) ? json_decode($settings['params'], true) : array();
		$settings['params'] = is_array($settings['params']) ? $settings['params'] : array();

		// update driverId
		$this->driverId = $settings['id'];

		// return array of settings with decoded parameters
		return $settings;
	}

	/**
	 * Saves the settings for the driver.
	 * The View may call this method to save the driver settings.
	 *
	 * @return 	boolean 	true if settings were stored, false otherwise
	 */
	public function saveSettings()
	{
		$data = $this->prepareSavingSettings();
		if (!$data instanceof stdClass || !count(get_object_vars($data))) {
			return false;
		}

		$q = "SELECT * FROM `#__vikbooking_einvoicing_config` WHERE `driver`=".$this->dbo->quote($this->getFileName())." ORDER BY `id` DESC LIMIT 1;";
		$this->dbo->setQuery($q);
		$this->dbo->execute();
		if (!$this->dbo->getNumRows()) {
			// create new driver record as it's the first time we're saving the settings
			if (!$this->dbo->insertObject('#__vikbooking_einvoicing_config', $data, 'id')) {
				return false;
			}
		} else {
			// update the driver record with the new settings
			$current = $this->dbo->loadAssoc();
			$data->id = $current['id'];
			if (!$this->dbo->updateObject('#__vikbooking_einvoicing_config', $data, 'id')) {
				return false;
			}
		}

		$this->setInfo(JText::translate('VBODRIVERSETTSUPD'));
		
		return true;
	}

	/**
	 * Returns whether the driver has settings to be defined.
	 *
	 * @return 	boolean
	 */
	public function hasSettings()
	{
		return $this->hasSettings;
	}

	/**
	 * Echoes the HTML required for the driver settings form.
	 * This method should be extended by the driver if settings are needed.
	 *
	 * @return 	void
	 */
	public function printSettings()
	{
		return;
	}

	/**
	 * Echoes the HTML required for the driver overlay form.
	 * This method should be extended by the driver if needed.
	 * For example, to show contents within a modal.
	 *
	 * @return 	void
	 */
	public function printOverlayContent()
	{
		return;
	}

	/**
	 * Returns the ID of the current driver by taking it from the settings.
	 * By calling loadSettings(), if some settings are defined, the ID is set in driverId.
	 * 
	 * @return 	mixed 			the ID of the current driver if some settings were stored or null.
	 * 
	 * @uses 	loadSettings
	 */
	protected function getDriverId()
	{
		if (is_null($this->driverId)) {
			// this method will set a value for the property driverId if some settings were saved
			$this->loadSettings();
		}

		return $this->driverId;
	}

	/**
	 * Stores an electronic invoice. If the booking ID is passed through the data
	 * the system will set to obliterated all e-invoices previously made for that booking.
	 * Custom (manual) invoices should be obliterated by the external caller script instead.
	 * 
	 * @param 	object 	$data 	stdClass object with the data to store
	 * 
	 * @return 	mixed 	the ID of the invoice stored, false otherwise
	 */
	protected function storeEInvoice($data)
	{
		if (!$data instanceof stdClass || !count(get_object_vars($data))) {
			return false;
		}

		if (isset($data->idorder) && (int)$data->idorder > 0) {
			// obliterate any possible previous e-invoice for this booking
			$q = "UPDATE `#__vikbooking_einvoicing_data` SET `obliterated`=1 WHERE `driverid`=".(int)$this->driverId." AND `idorder`=".(int)$data->idorder.";";
			$this->dbo->setQuery($q);
			$this->dbo->execute();
		}

		if ($this->dbo->insertObject('#__vikbooking_einvoicing_data', $data, 'id')) {
			return $data->id;
		}
		
		return false;
	}

	/**
	 * Updates the information for an electronic invoice.
	 * 
	 * @param 	object 	$data 	stdClass object with the data to store
	 * 
	 * @return 	boolean
	 */
	protected function updateEInvoice($data)
	{
		if (!$data instanceof stdClass || !count(get_object_vars($data))) {
			return false;
		}

		if ($this->dbo->updateObject('#__vikbooking_einvoicing_data', $data, 'id')) {
			return true;
		}
		
		return false;
	}

	/**
	 * Checks whether an analogic invoice in PDF was already issued
	 * for the given booking ID. This is to avoid double PDF invoices.
	 * 
	 * @param 	int 		$idorder 	the ID of the booking
	 * 
	 * @return 	boolean
	 */
	protected function hasAnalogicInvoice($idorder)
	{
		if (empty($idorder)) {
			return false;
		}

		$q = "SELECT `id` FROM `#__vikbooking_invoices` WHERE `idorder`=".(int)$idorder.";";
		$this->dbo->setQuery($q);
		$this->dbo->execute();
		if ($this->dbo->getNumRows()) {
			return true;
		}

		return false;
	}

	/**
	 * Generates an analogic invoice in PDF format for the given booking ID.
	 * 
	 * @param 	int 		$idorder 	the ID of the booking.
	 * @param 	int 		$invnum 	the number of the invoice.
	 * @param 	string 		$invdate 	the date for the invoice in Y-m-d format.
	 * 
	 * @return 	boolean
	 */
	protected function generateAnalogicInvoice($idorder, $invnum, $invdate)
	{
		if (empty($idorder)) {
			return false;
		}

		// get booking record
		$booking = array();
		$q = "SELECT `o`.*,`co`.`idcustomer`,CONCAT_WS(' ',`c`.`first_name`,`c`.`last_name`) AS `customer_name`,`c`.`pin` AS `customer_pin`,`nat`.`country_name` FROM `#__vikbooking_orders` AS `o` LEFT JOIN `#__vikbooking_customers_orders` `co` ON `co`.`idorder`=`o`.`id` LEFT JOIN `#__vikbooking_customers` `c` ON `c`.`id`=`co`.`idcustomer` LEFT JOIN `#__vikbooking_countries` `nat` ON `nat`.`country_3_code`=`o`.`country` WHERE `o`.`id`=".(int)$idorder." AND `o`.`status`='confirmed' AND `o`.`total` > 0;";
		$this->dbo->setQuery($q);
		$this->dbo->execute();
		if ($this->dbo->getNumRows() > 0) {
			$booking = $this->dbo->loadAssoc();
		}

		if (!count($booking)) {
			return false;
		}

		try {
			$res = VikBooking::generateBookingInvoice($booking, $invnum, '', date($this->getDateFormat(), strtotime($invdate)));
		} catch (Exception $e) {
			return false;
		}

		return $res;
	}

	/**
	 * Loads an helper file to obtain HTML content within a buffer.
	 * 
	 * @param 	string 		the path to the layout/helper file to include
	 * @param 	array 		the vars needed by the layout/helper file
	 *
	 * @return 	string 		the HTML content to print, or an empty string
	 */
	protected function loadHelperFile($fpath, $data = array())
	{
		if (!is_file($fpath)) {
			return '';
		}

		// capture the content of the layout/helper file within a buffer
		ob_start();
		include $fpath;
		$content = ob_get_contents();
		ob_end_clean();

		// return the content to be displayed
		return $content;
	}

	/**
	 * Requires an helper file.
	 *
	 * @return 	void
	 */
	protected function importHelper($fpath)
	{
		if (!is_file($fpath)) {
			return;
		}

		require_once $fpath;
	}

	/**
	 * Returns whether the driver has some session filters to
	 * immediately call getBookingsData() when the page loads.
	 * Child classes could override this method depending on the needs.
	 *
	 * @return 	boolean
	 */
	public function hasFiltersSet()
	{
		return false;
	}

	/**
	 * Loads the jQuery UI Datepicker.
	 * Method used only by sub-classes.
	 *
	 * @return 	self
	 */
	protected function loadDatePicker()
	{
		$vbo_app = VikBooking::getVboApplication();
		$vbo_app->loadDatePicker();

		return $this;
	}

	/**
	 * Loads all the rooms in VBO and returns the array.
	 *
	 * @return 	array 	associative array with key=ID value=data
	 */
	protected function getRooms()
	{
		$rooms = array();
		$q = "SELECT * FROM `#__vikbooking_rooms` ORDER BY `name` ASC;";
		$this->dbo->setQuery($q);
		$this->dbo->execute();
		if ($this->dbo->getNumRows() > 0) {
			$all = $this->dbo->loadAssocList();
			foreach ($all as $r) {
				$rooms[$r['id']] = $r;
			}
		}

		return $rooms;
	}

	/**
	 * Concatenates the JavaScript rules.
	 * Method used only by sub-classes.
	 *
	 * @param 	string 		$str
	 *
	 * @return 	self
	 */
	protected function setScript($str)
	{
		$this->driverScript .= $str."\n";

		return $this;
	}

	/**
	 * Returns the aliquote number from the given record ID.
	 *
	 * @param 	int 	$idvat 	the ID of the IVA record
	 *
	 * @return 	float 	the aliquot found or 0
	 */
	protected function getAliquoteById($idvat)
	{
		$aliq = 0;

		if (!empty($idvat)) {
			$q = "SELECT `aliq` FROM `#__vikbooking_iva` WHERE `id`=".(int)$idvat.";";
			$this->dbo->setQuery($q);
			$this->dbo->execute();
			if ($this->dbo->getNumRows()) {
				$aliq = $this->dbo->loadResult();
			}
		}

		return floatval($aliq);
	}

	/**
	 * Returns the aliquote number from the given price ID.
	 *
	 * @param 	int 	$idvat 	the ID of the IVA record
	 *
	 * @return 	float 	the aliquot found or 0
	 */
	protected function getAliquoteFromPriceId($idprice)
	{
		$aliq = 0;

		if (!empty($idprice)) {
			$q = "SELECT `p`.`idiva`,`i`.`aliq` FROM `#__vikbooking_prices` AS `p` LEFT JOIN `#__vikbooking_iva` `i` ON `i`.`id`=`p`.`idiva` WHERE `p`.`id`=".(int)$idprice.";";
			$this->dbo->setQuery($q);
			$this->dbo->execute();
			if ($this->dbo->getNumRows()) {
				$data = $this->dbo->loadAssoc();
				$aliq = $data['aliq'];
			}
		}

		return floatval($aliq);
	}

	/**
	 * Updates the current invoice number for later use.
	 *
	 * @param 	int 	$invnum 	the invoice number to set
	 *
	 * @return 	void
	 */
	protected function updateInvoiceNumber($invnum)
	{
		$q = "UPDATE `#__vikbooking_config` SET `setting`=".$this->dbo->quote((int)$invnum)." WHERE `param`='invoiceinum';";
		$this->dbo->setQuery($q);
		$this->dbo->execute();
	}

	/**
	 * Updates the progrssive number for the data transmission for this driver.
	 *
	 * @param 	int 	$num 	the prograssive number to set (should be already increased)
	 *
	 * @return 	void
	 */
	protected function updateProgressiveNumber($num)
	{
		$q = "UPDATE `#__vikbooking_einvoicing_config` SET `progcount`=".(int)$num." WHERE `driver`=".$this->dbo->quote($this->getFileName()).";";
		$this->dbo->setQuery($q);
		$this->dbo->execute();
	}

	/**
	 * Attempts to format the given XML string through DOMDocument.
	 * This method takes the XML string by reference.
	 *
	 * @param 	string 		$xml 	the XML string to be formatted
	 *
	 * @return 	string 		the formatted string or the original string
	 */
	protected function formatXmlString(&$xml)
	{
		if (!class_exists('DOMDocument')) {
			// we cannot format the XML because DOMDocument is missing
			return $xml;
		}

		$dom = new DOMDocument;
		$dom->preserveWhiteSpace = false;
		$dom->loadXML($xml);
		$dom->formatOutput = true;
		$xml = $dom->saveXML();

		return $xml;
	}

	/**
	 * Returns whether the debug request has been set.
	 *
	 * @return 	boolean
	 */
	protected function debugging()
	{
		$debug = VikRequest::getInt('e4j_debug', 0, 'request');

		return $debug;
	}

	/**
	 * Gets the current script string.
	 *
	 * @return 	string
	 */
	public function getScript()
	{
		return rtrim($this->driverScript, "\n");
	}

	/**
	 * Returns the date format in VBO for date, jQuery UI, Joomla.
	 * Method used only by sub-classes.
	 *
	 * @param 	string 		$type
	 *
	 * @return 	string
	 */
	protected function getDateFormat($type = 'date')
	{
		$nowdf = VikBooking::getDateFormat();
		if ($nowdf == "%d/%m/%Y") {
			$df = 'd/m/Y';
			$juidf = 'dd/mm/yy';
		} elseif ($nowdf == "%m/%d/%Y") {
			$df = 'm/d/Y';
			$juidf = 'mm/dd/yy';
		} else {
			$df = 'Y/m/d';
			$juidf = 'yy/mm/dd';
		}

		switch ($type) {
			case 'jui':
				return $juidf;
			case 'joomla':
				return $nowdf;
			default:
				return $df;
		}
	}

	/**
	 * Returns the translated weekday.
	 * Uses the back-end language definitions.
	 *
	 * @param 	int 	$wday
	 * @param 	string 	$type 	use 'long' for the full name of the week, short for the 3-char version
	 *
	 * @return 	string
	 */
	protected function getWdayString($wday, $type = 'long')
	{
		$wdays_map_long = array(
			JText::translate('VBWEEKDAYZERO'),
			JText::translate('VBWEEKDAYONE'),
			JText::translate('VBWEEKDAYTWO'),
			JText::translate('VBWEEKDAYTHREE'),
			JText::translate('VBWEEKDAYFOUR'),
			JText::translate('VBWEEKDAYFIVE'),
			JText::translate('VBWEEKDAYSIX')
		);

		$wdays_map_short = array(
			JText::translate('VBSUN'),
			JText::translate('VBMON'),
			JText::translate('VBTUE'),
			JText::translate('VBWED'),
			JText::translate('VBTHU'),
			JText::translate('VBFRI'),
			JText::translate('VBSAT')
		);

		if ($type != 'long') {
			return isset($wdays_map_short[(int)$wday]) ? $wdays_map_short[(int)$wday] : '';
		}

		return isset($wdays_map_long[(int)$wday]) ? $wdays_map_long[(int)$wday] : '';
	}

	/**
	 * Returns the translated month.
	 * Uses the back-end language definitions.
	 *
	 * @param 	int 	$month 	the month to convert (from 1 to 12)
	 *
	 * @return 	string
	 */
	protected function getMonthString($mon)
	{
		$mon--;

		$months_map_long = array(
			JText::translate('VBMONTHONE'),
			JText::translate('VBMONTHTWO'),
			JText::translate('VBMONTHTHREE'),
			JText::translate('VBMONTHFOUR'),
			JText::translate('VBMONTHFIVE'),
			JText::translate('VBMONTHSIX'),
			JText::translate('VBMONTHSEVEN'),
			JText::translate('VBMONTHEIGHT'),
			JText::translate('VBMONTHNINE'),
			JText::translate('VBMONTHTEN'),
			JText::translate('VBMONTHELEVEN'),
			JText::translate('VBMONTHTWELVE')
		);

		return isset($months_map_long[(int)$mon]) ? $months_map_long[(int)$mon] : '';
	}

	/**
	 * Replaces accents and special characters to their non-special version.
	 * Takes also rid of the ampersand symbol that would break the XML.
	 *
	 * @param 	string 	$string 	the string to parse
	 *
	 * @return 	string
	 */
	protected function convertSpecials($string)
	{
		// map of special characters
		$table = array(
			'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Ă'=>'A', 'Ā'=>'A', 'Ą'=>'A', 'Æ'=>'A', 'Ǽ'=>'A',
			'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'ă'=>'a', 'ā'=>'a', 'ą'=>'a', 'æ'=>'a', 'ǽ'=>'a',

			'Þ'=>'B', 'þ'=>'b', 'ß'=>'Ss',

			'Ç'=>'C', 'Č'=>'C', 'Ć'=>'C', 'Ĉ'=>'C', 'Ċ'=>'C',
			'ç'=>'c', 'č'=>'c', 'ć'=>'c', 'ĉ'=>'c', 'ċ'=>'c',

			'Đ'=>'Dj', 'Ď'=>'D',
			'đ'=>'dj', 'ď'=>'d',

			'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ĕ'=>'E', 'Ē'=>'E', 'Ę'=>'E', 'Ė'=>'E',
			'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ĕ'=>'e', 'ē'=>'e', 'ę'=>'e', 'ė'=>'e',

			'Ĝ'=>'G', 'Ğ'=>'G', 'Ġ'=>'G', 'Ģ'=>'G',
			'ĝ'=>'g', 'ğ'=>'g', 'ġ'=>'g', 'ģ'=>'g',

			'Ĥ'=>'H', 'Ħ'=>'H',
			'ĥ'=>'h', 'ħ'=>'h',

			'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'İ'=>'I', 'Ĩ'=>'I', 'Ī'=>'I', 'Ĭ'=>'I', 'Į'=>'I',
			'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'į'=>'i', 'ĩ'=>'i', 'ī'=>'i', 'ĭ'=>'i', 'ı'=>'i',

			'Ĵ'=>'J',
			'ĵ'=>'j',

			'Ķ'=>'K',
			'ķ'=>'k', 'ĸ'=>'k',

			'Ĺ'=>'L', 'Ļ'=>'L', 'Ľ'=>'L', 'Ŀ'=>'L', 'Ł'=>'L',
			'ĺ'=>'l', 'ļ'=>'l', 'ľ'=>'l', 'ŀ'=>'l', 'ł'=>'l',

			'Ñ'=>'N', 'Ń'=>'N', 'Ň'=>'N', 'Ņ'=>'N', 'Ŋ'=>'N',
			'ñ'=>'n', 'ń'=>'n', 'ň'=>'n', 'ņ'=>'n', 'ŋ'=>'n', 'ŉ'=>'n',

			'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ō'=>'O', 'Ŏ'=>'O', 'Ő'=>'O', 'Œ'=>'O',
			'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ō'=>'o', 'ŏ'=>'o', 'ő'=>'o', 'œ'=>'o', 'ð'=>'o',

			'Ŕ'=>'R', 'Ř'=>'R',
			'ŕ'=>'r', 'ř'=>'r', 'ŗ'=>'r',

			'Š'=>'S', 'Ŝ'=>'S', 'Ś'=>'S', 'Ş'=>'S',
			'š'=>'s', 'ŝ'=>'s', 'ś'=>'s', 'ş'=>'s',

			'Ŧ'=>'T', 'Ţ'=>'T', 'Ť'=>'T',
			'ŧ'=>'t', 'ţ'=>'t', 'ť'=>'t',

			'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ũ'=>'U', 'Ū'=>'U', 'Ŭ'=>'U', 'Ů'=>'U', 'Ű'=>'U', 'Ų'=>'U',
			'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ü'=>'u', 'ũ'=>'u', 'ū'=>'u', 'ŭ'=>'u', 'ů'=>'u', 'ű'=>'u', 'ų'=>'u',

			'Ŵ'=>'W', 'Ẁ'=>'W', 'Ẃ'=>'W', 'Ẅ'=>'W',
			'ŵ'=>'w', 'ẁ'=>'w', 'ẃ'=>'w', 'ẅ'=>'w',

			'Ý'=>'Y', 'Ÿ'=>'Y', 'Ŷ'=>'Y',
			'ý'=>'y', 'ÿ'=>'y', 'ŷ'=>'y',

			'Ž'=>'Z', 'Ź'=>'Z', 'Ż'=>'Z',
			'ž'=>'z', 'ź'=>'z', 'ż'=>'z',
			'+'=>'',
	    );

	    $string = strtr($string, $table);
	    
	    $string = preg_replace("/[^\x9\xA\xD\x20-\x7F]/u", "", $string);

	    // replace ampersand
		$string = str_replace('&', 'and', $string);
		// convert to HTML entities for a safe XML content
		$string = htmlentities($string);
		// remove any ampersand added by htmlentities()
		$string = str_replace('&', '', $string);

	    return $string;
	}

	/**
	 * Sets the columns for this driver.
	 *
	 * @param 	array 	$arr
	 *
	 * @return 	self
	 */
	protected function setDriverCols($arr)
	{
		$this->cols = $arr;

		return $this;
	}

	/**
	 * Returns the columns for this driver.
	 * Should be called after getBookingsData()
	 * or the returned array will be empty.
	 *
	 * @return 	array
	 */
	public function getDriverCols()
	{
		return $this->cols;
	}

	/**
	 * Sorts the rows of the driver by key.
	 *
	 * @param 	string 		$krsort 	the key attribute of the array pairs
	 * @param 	string 		$krorder 	ascending (ASC) or descending (DESC)
	 *
	 * @return 	void
	 */
	protected function sortRows($krsort, $krorder)
	{
		if (empty($krsort) || !(count($this->rows))) {
			return;
		}

		$map = array();
		foreach ($this->rows as $k => $row) {
			foreach ($row as $kk => $v) {
				if (isset($v['key']) && $v['key'] == $krsort) {
					$map[$k] = $v['value'];
				}
			}
		}
		if (!(count($map))) {
			return;
		}

		if ($krorder == 'ASC') {
			asort($map);
		} else {
			arsort($map);
		}

		$sorted = array();
		foreach ($map as $k => $v) {
			$sorted[$k] = $this->rows[$k];
		}

		$this->rows = $sorted;
	}

	/**
	 * Sets the rows for this driver.
	 *
	 * @param 	array 	$arr
	 *
	 * @return 	self
	 */
	protected function setDriverRows($arr)
	{
		$this->rows = $arr;

		return $this;
	}

	/**
	 * Returns the rows for this driver.
	 * Should be called after getBookingsData()
	 * or the returned array will be empty.
	 *
	 * @return 	array
	 */
	public function getDriverRows()
	{
		return $this->rows;
	}

	/**
	 * Sets the footer row (the totals) for this driver.
	 *
	 * @param 	array 	$arr
	 *
	 * @return 	self
	 */
	protected function setDriverFooterRow($arr)
	{
		$this->footerRow = $arr;

		return $this;
	}

	/**
	 * Returns the footer row for this driver.
	 * Should be called after getBookingsData()
	 * or the returned array will be empty.
	 *
	 * @return 	array
	 */
	public function getDriverFooterRow()
	{
		return $this->footerRow;
	}

	/**
	 * Sets warning messages by concatenating the existing ones.
	 * Method used only by sub-classes.
	 *
	 * @param 	string 		$str
	 *
	 * @return 	self
	 */
	protected function setWarning($str)
	{
		$this->warning[] = $str;

		return $this;
	}

	/**
	 * Gets the current warning string.
	 *
	 * @return 	string
	 */
	public function getWarning()
	{
		return implode('<br/>', $this->warning);
	}

	/**
	 * Sets errors by concatenating the existing ones.
	 * Method used only by sub-classes.
	 *
	 * @param 	string 		$str
	 *
	 * @return 	self
	 */
	protected function setError($str)
	{
		$this->error[] = $str;

		return $this;
	}

	/**
	 * Gets the current error string.
	 *
	 * @return 	string
	 */
	public function getError()
	{
		return implode('<br/>', $this->error);
	}

	/**
	 * Sets info messages by concatenating the existing ones.
	 * Method used only by sub-classes.
	 *
	 * @param 	string 		$str
	 *
	 * @return 	self
	 */
	protected function setInfo($str)
	{
		$this->info[] = $str;

		return $this;
	}

	/**
	 * Gets the current info string.
	 *
	 * @return 	string
	 */
	public function getInfo()
	{
		return implode('<br/>', $this->info);
	}
}
