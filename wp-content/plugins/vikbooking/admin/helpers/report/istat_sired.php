<?php
/**
 * @package     VikBooking
 * @subpackage  com_vikbooking
 * @author      Lorenzo - e4j - Extensionsforjoomla.com
 * @copyright   Copyright (C) 2019 e4j - Extensionsforjoomla.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @link        https://vikwp.com
 */

defined('ABSPATH') or die('No script kiddies please!');

/**
 * ISTAT SIRED child Class of VikBookingReport
 *
 * @see 	https://sired.sardegnaturismo.it/IW_OT/Downloads/TracciatoGiornaliero.pdf
 */
class VikBookingReportIstatSired extends VikBookingReport
{
	/**
	 * Property 'defaultKeySort' is used by the View that renders the report.
	 */
	public $defaultKeySort = 'checkin';
	/**
	 * Property 'defaultKeyOrder' is used by the View that renders the report.
	 */
	public $defaultKeyOrder = 'ASC';
	/**
	 * Property 'customExport' is used by the View to display custom export buttons.
	 * We should not define the property $exportAllowed.
	 */
	public $customExport = '';
	/**
	 * Debug mode is activated by passing the value 'e4j_debug' > 0
	 */
	private $debug;
	/**
	 * An associative array of regions (keys) and provinces (sub-arrays)
	 * 
	 * @var 	array
	 */

	/**
	 * Class constructor should define the name of the report and
	 * other vars. Call the parent constructor to define the DB object.
	 */
	function __construct()
	{
		$this->reportFile = basename(__FILE__, '.php');
		$this->reportName = 'ISTAT SIRED';
		$this->reportFilters = array();

		$this->cols = array();
		$this->rows = array();
		$this->footerRow = array();

		$this->debug = (VikRequest::getInt('e4j_debug', 0, 'request') > 0);
		$this->comuniProvince = $this->loadComuniProvince();
		$this->nazioni = $this->loadNazioni();

		parent::__construct();
	}

	/**
	 * Returns the name of this report.
	 *
	 * @return 	string
	 */
	public function getName()
	{
		return $this->reportName;
	}

	/**
	 * Returns the name of this file without .php.
	 *
	 * @return 	string
	 */
	public function getFileName()
	{
		return $this->reportFile;
	}

	/**
	 * Returns the filters of this report.
	 *
	 * @return 	array
	 */
	public function getFilters()
	{
		if (count($this->reportFilters)) {
			//do not run this method twice, as it could load JS and CSS files.
			return $this->reportFilters;
		}	
		$this->loadCss();

		//load the jQuery UI Datepicker
		$this->loadDatePicker();
		

		//custom export button
		$this->customExport = '<a href="JavaScript: void(0);" onclick="vboDownloadIstatDoc();" class="vbcsvexport"><i class="'.VikBookingIcons::i('download').'"></i> <span>Genera Documento Dati ISTAT</span></a>';

		// helper filters
		$hidden_vals = '<div id="vbo-report-istat-hidden" style="display: none;">';
		// Filtro nazione nascita
		$hidden_vals .= '	<div id="vbo-report-istat-nazione" class="vbo-report-istat-selcont" style="display: none;">';
		$hidden_vals .= '		<select id="choose-nazione" onchange="vboReportChosenNazione(this);"><option value=""></option>';
			// sort all foreign countries in a clone to not lose keys. We add also Liechtenstein which equals to 'Svizzera'

		//
		$hidden_vals .= '		<optgroup label=" Nazioni ">';
		foreach ($this->nazioni as $countrytipo => $name) {
			$hidden_vals .= '		<option value="'.$countrytipo.'">'.$name['name'].'</option>';
		}
		$hidden_vals .= '		</optgroup>';
		$hidden_vals .= '		</select>';

		$hidden_vals .= '	</div>';

		// comune 
		$hidden_vals .= '	<div id="vbo-report-istat-comune" class="vbo-report-istat-selcont" style="display: none;">';
		$hidden_vals .= '		<select id="choose-comune" onchange="vboReportChosenComune(this);"><option value=""></option>';
		if (isset($this->comuniProvince['comuni']) && count($this->comuniProvince['comuni'])) {
			foreach ($this->comuniProvince['comuni'] as $code => $comune) {
				$hidden_vals .= '	<option value="'.$code.'">' . (is_array($comune) ? $comune['name'] : $comune) . '</option>'."\n";
			}
		}
		$hidden_vals .= '		</select>';
		$hidden_vals .= '	</div>';

		//Sesso
		$hidden_vals .= '	<div id="vbo-report-istat-sesso" class="vbo-report-istat-selcont" style="display: none;">';
		$hidden_vals .= '		<select id="choose-sesso" onchange="vboReportChosenSesso(this);"><option value=""></option>';
		$sessos = array(
			1 => 'M',
			2 => 'F'
		);
		foreach ($sessos as $code => $ses) {
			$hidden_vals .= '	<option value="'.$code.'">'.$ses.'</option>'."\n";
		}
		$hidden_vals .= '		</select>';
		$hidden_vals .= '	</div>';
		//data di nascita
		$hidden_vals .= '	<div id="vbo-report-istat-dbirth" class="vbo-report-istat-selcont" style="display: none;">';
		$hidden_vals .= '		<input type="text" size="40" id="choose-dbirth" placeholder="Data di Nascita" value="" /><br/>';
		$hidden_vals .= '		<button type="button" class="btn" onclick="vboReportChosenDbirth(document.getElementById(\'choose-dbirth\').value);">'.JText::translate('VBAPPLY').'</button>';
		$hidden_vals .= '	</div>';
		//
		$hidden_vals .= '</div>';

		//From Date Filter
		$filter_opt = array(
			'label' => '<label for="fromdate">'.JText::translate('VBOREPORTSDATEFROM').'</label>',
			'html' => '<input type="text" id="fromdate" name="fromdate" value="" class="vbo-report-datepicker vbo-report-datepicker-from" />'.$hidden_vals,
			'type' => 'calendar',
			'name' => 'fromdate'
		);
		array_push($this->reportFilters, $filter_opt);

		//To Date Filter
		$filter_opt = array(
			'label' => '<label for="todate">'.JText::translate('VBOREPORTSDATETO').'</label>',
			'html' => '<input type="text" id="todate" name="todate" value="" class="vbo-report-datepicker vbo-report-datepicker-to" />',
			'type' => 'calendar',
			'name' => 'todate'
		);
		array_push($this->reportFilters, $filter_opt);

		// Filtro Codice Struttura
		$pcodstru = VikRequest::getString('codstru', '', 'request');
		$filter_opt = array(
			'label' => '<label for="codstru">Codice Struttura</label>',
			'html' => '<input type="text" id="codstru" name="codstru" value="'.$pcodstru.'" size="10" />',
			'type' => 'text',
			'name' => 'codstru'
		);
		array_push($this->reportFilters, $filter_opt);

		// Filtro Numero Letti
		$pletti = VikRequest::getInt('numletti', 0, 'request');
		$filter_opt = array(
			'label' => '<label for="numletti">Numero Letti Disponibili</label>',
			'html' => '<input type="number" id="numletti" name="numletti" value="'.$pletti.'" size="10" />',
			'type' => 'text',
			'name' => 'numletti'
		);
		array_push($this->reportFilters, $filter_opt);

		//jQuery code for the datepicker calendars and select2
		$pfromdate = VikRequest::getString('fromdate', '', 'request');
		$ptodate = VikRequest::getString('todate', '', 'request');
		$js = 'var reportActiveCell = null, reportObj = {};
		jQuery(document).ready(function() {
			jQuery(".vbo-report-datepicker:input").datepicker({
				maxDate: 0,
				dateFormat: "'.$this->getDateFormat('jui').'",
				onSelect: vboReportCheckDates
			});
			'.(!empty($pfromdate) ? 'jQuery(".vbo-report-datepicker-from").datepicker("setDate", "'.$pfromdate.'");' : '').'
			'.(!empty($ptodate) ? 'jQuery(".vbo-report-datepicker-to").datepicker("setDate", "'.$ptodate.'");' : '').'
			//prepare filler helpers
			jQuery("#vbo-report-istat-hidden").children().detach().appendTo(".vbo-info-overlay-report");
			jQuery("#choose-comune").select2({placeholder: "- Seleziona un Comune -", width: "200px"});
			jQuery("#choose-provincia").select2({placeholder: "- Seleziona una Provincia -", width: "200px"});
			jQuery("#choose-nazione").select2({placeholder: "- Seleziona una Nazione -", width: "200px"});
			jQuery("#choose-documento").select2({placeholder: "- Seleziona un Documento -", width: "200px"});
			jQuery("#choose-sesso").select2({placeholder: "- Seleziona Sesso -", width: "200px"});
			jQuery("#choose-dbirth").datepicker({
				maxDate: 0,
				dateFormat: "dd/mm/yy",
				changeMonth: true,
				changeYear: true,
				yearRange: "'.(date('Y') - 100).':'.date('Y').'"
			});
			//click events
			jQuery(".vbo-report-load-comuneres, .vbo-report-load-comunenas").click(function() {
				reportActiveCell = this;
				jQuery(".vbo-report-istat-selcont").hide();
				jQuery("#vbo-report-istat-comune").show();
				vboShowOverlay();
			});
			jQuery(".vbo-report-load-provincia").click(function() {
				reportActiveCell = this;
				jQuery(".vbo-report-istat-selcont").hide();
				jQuery("#vbo-report-istat-provincia").show();
				vboShowOverlay();
			});
			
			jQuery(".vbo-report-load-nazioneres, .vbo-report-load-cittadinanza, .vbo-report-load-nazionenas").click(function() {
				reportActiveCell = this;
				jQuery(".vbo-report-istat-selcont").hide();
				jQuery("#vbo-report-istat-nazione").show();
				vboShowOverlay();
			});
			jQuery(".vbo-report-load-doctype").click(function() {
				reportActiveCell = this;
				jQuery(".vbo-report-istat-selcont").hide();
				jQuery("#vbo-report-istat-doctype").show();
				vboShowOverlay();
			});
			jQuery(".vbo-report-load-docplace").click(function() {
				reportActiveCell = this;
				jQuery(".vbo-report-istat-selcont").hide();
				jQuery("#vbo-report-istat-comune").show();
				jQuery("#vbo-report-istat-nazione").show();
				vboShowOverlay();
			});
			jQuery(".vbo-report-load-sesso").click(function() {
				reportActiveCell = this;
				jQuery(".vbo-report-istat-selcont").hide();
				jQuery("#vbo-report-istat-sesso").show();
				vboShowOverlay();
			});
			jQuery(".vbo-report-load-docnum").click(function() {
				reportActiveCell = this;
				jQuery(".vbo-report-istat-selcont").hide();
				jQuery("#vbo-report-istat-docnum").show();
				vboShowOverlay();
				setTimeout(function(){jQuery("#choose-docnum").focus();}, 500);
			});
			jQuery(".vbo-report-load-dbirth").click(function() {
				reportActiveCell = this;
				jQuery(".vbo-report-istat-selcont").hide();
				jQuery("#vbo-report-istat-dbirth").show();
				vboShowOverlay();
				//pretend the overlay is off, or navigating in the datepicker will close the modal.
				setTimeout(function(){vbo_overlay_on = false;}, 800);
				//
			});
		});
		function vboReportCheckDates(selectedDate, inst) {
			if (selectedDate === null || inst === null) {
				return;
			}
			var cur_from_date = jQuery(this).val();
			if (jQuery(this).hasClass("vbo-report-datepicker-from") && cur_from_date.length) {
				var nowstart = jQuery(this).datepicker("getDate");
				var nowstartdate = new Date(nowstart.getTime());
				jQuery(".vbo-report-datepicker-to").datepicker("option", {minDate: nowstartdate});
			}
		}
		function vboReportChosenComune(comune) {
			var c_code = comune.value;
			var c_val = comune.options[comune.selectedIndex].text;
			if (reportActiveCell !== null) {
				var nowindex = jQuery(".vbo-reports-output table tbody tr").index(jQuery(reportActiveCell).closest("tr"));
				if (isNaN(nowindex) || parseInt(nowindex) < 0) {
					alert("Error, cannot find element to update.");
				} else {
					jQuery(reportActiveCell).addClass("vbo-report-load-elem-filled").find("span").text(c_val);
					if (!reportObj.hasOwnProperty(nowindex)) {
						reportObj[nowindex] = {};
					}
					if (jQuery(reportActiveCell).hasClass("vbo-report-load-docplace")) {
						reportObj[nowindex].docplace = c_code;
					} else if (jQuery(reportActiveCell).hasClass("vbo-report-load-comunenas")) {
						reportObj[nowindex].combirth = c_code;
					} else {
						reportObj[nowindex].comres = c_code;

					}
				}
			}
			reportActiveCell = null;
			vboHideOverlay();
			jQuery("#choose-comune").val("").select2("data", null, false);
		}
		function vboReportChosenProvincia(prov) {
			var c_code = prov.value;
			var c_val = prov.options[prov.selectedIndex].text;
			if (reportActiveCell !== null) {
				var nowindex = jQuery(".vbo-reports-output table tbody tr").index(jQuery(reportActiveCell).closest("tr"));
				if (isNaN(nowindex) || parseInt(nowindex) < 0) {
					alert("Error, cannot find element to update.");
				} else {
					jQuery(reportActiveCell).addClass("vbo-report-load-elem-filled").find("span").text(c_val);
					if (!reportObj.hasOwnProperty(nowindex)) {
						reportObj[nowindex] = {};
					}
					reportObj[nowindex].probirth = c_code;
				}
			}
			reportActiveCell = null;
			vboHideOverlay();
			jQuery("#choose-provincia").val("").select2("data", null, false);
		}
		function vboReportChosenNazione(naz) {
			var c_code = naz.value;
			var c_val = naz.options[naz.selectedIndex].text;
			if (reportActiveCell !== null) {
				var nowindex = jQuery(".vbo-reports-output table tbody tr").index(jQuery(reportActiveCell).closest("tr"));
				if (isNaN(nowindex) || parseInt(nowindex) < 0) {
					alert("Error, cannot find element to update.");
				} else {
					jQuery(reportActiveCell).addClass("vbo-report-load-elem-filled").find("span").text(c_val);
					if (!reportObj.hasOwnProperty(nowindex)) {
						reportObj[nowindex] = {};
					}
					if (jQuery(reportActiveCell).hasClass("vbo-report-load-nazionenas")) {
						reportObj[nowindex].stabirth = c_code;
					} else if (jQuery(reportActiveCell).hasClass("vbo-report-load-docplace")) {
						reportObj[nowindex].docplace = c_code;
					} else if (jQuery(reportActiveCell).hasClass("vbo-report-load-nazioneres")) {
						reportObj[nowindex].stares = c_code;
					} else {
						reportObj[nowindex].citizen = c_code;
					}
				}
			}
			reportActiveCell = null;
			vboHideOverlay();
			jQuery("#choose-nazione").val("").select2("data", null, false);

		}
		function vboReportChosenDocumento(doctype) {
			var c_code = doctype.value;
			var c_val = doctype.options[doctype.selectedIndex].text;
			if (reportActiveCell !== null) {
				var nowindex = jQuery(".vbo-reports-output table tbody tr").index(jQuery(reportActiveCell).closest("tr"));
				if (isNaN(nowindex) || parseInt(nowindex) < 0) {
					alert("Error, cannot find element to update.");
				} else {
					jQuery(reportActiveCell).addClass("vbo-report-load-elem-filled").find("span").text(c_val);
					if (!reportObj.hasOwnProperty(nowindex)) {
						reportObj[nowindex] = {};
					}
					reportObj[nowindex].doctype = c_code;
				}
			}
			reportActiveCell = null;
			vboHideOverlay();
			jQuery("#choose-documento").val("").select2("data", null, false);
		}
		function vboReportChosenSesso(sesso) {
			var c_code = sesso.value;
			var c_val = sesso.options[sesso.selectedIndex].text;
			if (reportActiveCell !== null) {
				var nowindex = jQuery(".vbo-reports-output table tbody tr").index(jQuery(reportActiveCell).closest("tr"));
				if (isNaN(nowindex) || parseInt(nowindex) < 0) {
					alert("Error, cannot find element to update.");
				} else {
					jQuery(reportActiveCell).addClass("vbo-report-load-elem-filled").find("span").text(c_val);
					if (!reportObj.hasOwnProperty(nowindex)) {
						reportObj[nowindex] = {};
					}
					reportObj[nowindex].gender = c_code;
				}
			}
			reportActiveCell = null;
			vboHideOverlay();
			jQuery("#choose-sesso").val("").select2("data", null, false);
		}
		function vboReportChosenDocnum(val) {
			var c_code = val, c_val = val;
			if (reportActiveCell !== null) {
				var nowindex = jQuery(".vbo-reports-output table tbody tr").index(jQuery(reportActiveCell).closest("tr"));
				if (isNaN(nowindex) || parseInt(nowindex) < 0) {
					alert("Error, cannot find element to update.");
				} else {
					jQuery(reportActiveCell).addClass("vbo-report-load-elem-filled").find("span").text(c_val);
					if (!reportObj.hasOwnProperty(nowindex)) {
						reportObj[nowindex] = {};
					}
					reportObj[nowindex].docnum = c_code;
				}
			}
			reportActiveCell = null;
			vboHideOverlay();
			jQuery("#choose-docnum").val("");
		}
		function vboReportChosenDbirth(val) {
			var c_code = val, c_val = val;
			if (reportActiveCell !== null) {
				var nowindex = jQuery(".vbo-reports-output table tbody tr").index(jQuery(reportActiveCell).closest("tr"));
				if (isNaN(nowindex) || parseInt(nowindex) < 0) {
					alert("Error, cannot find element to update.");
				} else {
					jQuery(reportActiveCell).addClass("vbo-report-load-elem-filled").find("span").text(c_val);
					if (!reportObj.hasOwnProperty(nowindex)) {
						reportObj[nowindex] = {};
					}
					reportObj[nowindex].dbirth = c_code;
				}
			}
			reportActiveCell = null;
			vboHideOverlay();
			jQuery("#choose-dbirth").val("");
		}
		//download function
		function vboDownloadIstatDoc() {
			// check if empty values have been filled in or ask for confirmation
			var missing_italia = jQuery(".vbo-report-load-nazione").not(".vbo-report-load-elem-filled").length;
			var missing_tipo = jQuery(".vbo-report-load-cittadinanza").not(".vbo-report-load-elem-filled").length;
			if ((missing_italia > 0 || missing_tipo > 0) && !confirm("Qualche dato mancante evidenziato in rosso non è stato compilato. Vuoi continuare?")) {
				return false;
			} else if (!confirm("Sei sicuro di aver compilato tutti i dati per il documento?")) {
				return false;
			}
			document.adminForm.target = "_blank";
			document.adminForm.action += "&tmpl=component";
			vboSetFilters({exportreport: "1", filler: JSON.stringify(reportObj)}, true);
			setTimeout(function() {
				document.adminForm.target = "";
				document.adminForm.action = document.adminForm.action.replace("&tmpl=component", "");
				vboSetFilters({exportreport: "0", filler: ""}, false);
			}, 1000);
		}
		';
		$this->setScript($js);
		
		// additional CSS code for other elements
		JFactory::getDocument()->addStyleDeclaration('.vbo-report-load-nazionenas span, .vbo-report-load-nazioneres span, .vbo-report-load-comunenas span, .vbo-report-load-comuneres span  {
			display: inline-block;
			border: 1px solid #dd0000;
			cursor: pointer;
			color: #dd0000;
			padding: 0px 7px;
			text-align: center;

		}
		.vbo-report-load-elem-filled span {
			border: 0;
			padding: 0;
			color: #070f63;
		}');

		return $this->reportFilters;
	}

	/**
	 * Loads the report data from the DB.
	 * Returns true in case of success, false otherwise.
	 * Sets the columns and rows for the report to be displayed.
	 *
	 * @return 	boolean
	 */
	public function getReportData()
	{
		if (strlen($this->getError())) {
			//Export functions may set errors rather than exiting the process, and the View may continue the execution to attempt to render the report.
			return false;
		}
		//Input fields and other vars
		$pfromdate = VikRequest::getString('fromdate', '', 'request');
		$ptodate = VikRequest::getString('todate', '', 'request');
		$pcodstru = VikRequest::getString('codstru', '', 'request');
		$pkrsort = VikRequest::getString('krsort', $this->defaultKeySort, 'request');
		$pkrsort = empty($pkrsort) ? $this->defaultKeySort : $pkrsort;
		$pkrorder = VikRequest::getString('krorder', $this->defaultKeyOrder, 'request');
		$pkrorder = empty($pkrorder) ? $this->defaultKeyOrder : $pkrorder;
		$pkrorder = $pkrorder == 'DESC' ? 'DESC' : 'ASC';
		$df = $this->getDateFormat();
		$datesep = VikBooking::getDateSeparator();
		if (empty($ptodate)) {
			$ptodate = $pfromdate;
		}
		$records = array();
		$q = "SELECT SUM(`units`) AS `sommaunita`, SUM(`totpeople`) AS `numeropersone`, COUNT(*) AS `numerocamere`  FROM `#__vikbooking_rooms` WHERE `avail`= '1';";
		$this->dbo->setQuery($q);
		$this->dbo->execute();
		if ($this->dbo->getNumRows() > 0) {
			$records = $this->dbo->loadAssocList();
		}
		$totalBeds =(int) ($records[0]['sommaunita'] * ($records[0]['numeropersone']/$records[0]['numerocamere'])); 
		$pletti = VikRequest::getString('numletti', $totalBeds, 'request');
		//Get dates timestamps
		$from_ts = VikBooking::getDateTimestamp($pfromdate, 0, 0);
		$to_ts = VikBooking::getDateTimestamp($ptodate, 23, 59, 59);
		if (empty($pfromdate) || empty($from_ts) || empty($to_ts)) {
			$this->setError(JText::translate('VBOREPORTSERRNODATES'));
			return false;
		}
		if (empty($pcodstru)) {
			$this->setError('Inserisci il codice della tua Struttura.<br/>Si tratta di un codice univoco di identificazione che ti viene assegnato dall\'Amministrazione competente.');
			return false;
		}

		//Query to obtain the records (all check-ins within the dates filter)
		$records = array();
		$q = "SELECT `o`.`id`,`o`.`custdata`,`o`.`ts`,`o`.`days`,`o`.`checkin`,`o`.`checkout`,`o`.`totpaid`,`o`.`roomsnum`,`o`.`total`,`o`.`idorderota`,`o`.`channel`,`o`.`country`,".
			"`or`.`idorder`,`or`.`idroom`,`or`.`adults`,`or`.`children`,`or`.`t_first_name`,`or`.`t_last_name`,`or`.`cust_cost`,`or`.`cust_idiva`,`or`.`extracosts`,`or`.`room_cost`,".
			"`co`.`idcustomer`,`co`.`pax_data`,`c`.`first_name`,`c`.`last_name`,`c`.`country` AS `customer_country`,`c`.`city`,`c`.`doctype`,`c`.`docnum`,`c`.`gender`,`c`.`bdate`,`c`.`pbirth`,`cy`.`country_name`,".
			"(SELECT `h`.`dt` FROM `#__vikbooking_orderhistory` AS `h` WHERE `h`.`idorder`=`o`.`id` AND `h`.`type`='RP' AND `h`.`descr`=".$this->dbo->quote($this->reportName)." ORDER BY `h`.`dt` DESC LIMIT 1) AS `history_last` ".
			"FROM `#__vikbooking_orders` AS `o` LEFT JOIN `#__vikbooking_ordersrooms` AS `or` ON `or`.`idorder`=`o`.`id` ".
			"LEFT JOIN `#__vikbooking_customers_orders` AS `co` ON `co`.`idorder`=`o`.`id` LEFT JOIN `#__vikbooking_customers` AS `c` ON `c`.`id`=`co`.`idcustomer` LEFT JOIN `#__vikbooking_countries` AS `cy` ON `cy`.`country_3_code`=`c`.`country` ".
			"WHERE `o`.`status`='confirmed' AND `o`.`closure`=0 AND `o`.`checkin`>=".$from_ts." AND `o`.`checkin`<=".$to_ts." ".
			"ORDER BY `o`.`checkin` ASC, `o`.`id` ASC;";
		$this->dbo->setQuery($q);
		$this->dbo->execute();
		if ($this->dbo->getNumRows() > 0) {
			$records = $this->dbo->loadAssocList();
		}
		if (!count($records)) {
			$this->setError(JText::translate('VBOREPORTSERRNORESERV'));
			return false;
		}


		//nest records with multiple rooms booked inside sub-array
		$bookings = array();
		foreach ($records as $v) {
			if (!isset($bookings[$v['id']])) {
				$bookings[$v['id']] = array();
			}
			// for bc, if the country is defined at booking level, set it also for the customer
			if (empty($v['customer_country']) && !empty($v['country'])) {
				$v['customer_country'] = $v['country'];
			}
			//calculate the from_ts and to_ts values for later comparison
			$in_info = getdate($v['checkin']);
			$out_info = getdate($v['checkout']);
			$v['from_ts'] = mktime(0, 0, 0, $in_info['mon'], $in_info['mday'], $in_info['year']);
			$v['to_ts'] = mktime(23, 59, 59, $out_info['mon'], ($out_info['mday'] - 1), $out_info['year']);
			//
			array_push($bookings[$v['id']], $v);
		}

		//define the columns of the report
		$this->cols = array(
			//Tipo alloggiato
			array(
				'key' => 'tipo',
				'attr' => array(
					'class="vbo-report-longlbl"'
				),
				'label' => 'Tipo Alloggiato'
			),
			//checkin
			array(
				'key' => 'checkin',
				'attr' => array(
					'class="center"'
				),
				'sortable' => 1,
				'label' => 'Arrivo'
			),
			
			//cognome
			array(
				'key' => 'cognome',
				'label' => JText::translate('VBTRAVELERLNAME')
			),
			//nome
			array(
				'key' => 'nome',
				'label' => JText::translate('VBTRAVELERNAME')
			),
			//sesso
			array(
				'key' => 'gender',
				'attr' => array(
					'class="center"'
				),
				'label' => JText::translate('VBCUSTOMERGENDER')
			),
			//data di nascita
			array(
				'key' => 'dbirth',
				'attr' => array(
					'class="center"'
				),
				'label' => JText::translate('VBCUSTOMERBDATE')
			),
			//stato di nascita
			array(
				'key' => 'stabirth',
				'attr' => array(
					'class="center"'
				),
				'label' => 'Stato Nascita'
			),
			//comune di nascita
			array(
				'key' => 'combirth',
				'attr' => array(
					'class="center"'
				),
				'label' => 'Comune Nascita'
			),
			//cittadinanza
			array(
				'key' => 'citizen',
				'attr' => array(
					'class="center"'
				),
				'label' => 'Cittadinanza'
			),
			//stato di residenza
			array(
				'key' => 'stares',
				'attr' => array(
					'class="center"'
				),
				'label' => 'Stato residenza'
			), 
			//comune di residenza
			array(
				'key' => 'comres',
				'attr' => array(
					'class="center"'
				),
				'label' => 'Comune residenza'
			), 
			//checkout
			array(
				'key' => 'checkout',
				'attr' => array(
					'class="center"'
				),
				'sortable' => 1,
				'label' => 'Partenza'
			),
			//occupazione (numero di camere prenotate)
			array(
				'key' => 'roomsbooked',
				'attr' => array(
					'class="center"'
				),
				'sortable' => 1,
				'label' => 'Occupazione',
				'tip' => 'Questo valore indica il numero di camere occupate da ogni prenotazione, ed è un dato che verrà comunicato all\'ISTAT.',
			),
			//camere (numero di camere totali)
			array(
				'key' => 'totrooms',
				'attr' => array(
					'class="center"'
				),
				'sortable' => 1,
				'label' => 'Camere',
				'tip' => 'Questo valore indica il numero totale di camere nella tua struttura, ed è un dato che deve essere comunicato all\'ISTAT. Viene calcolato con una somma delle unità di tutte le camere attualmente Pubblicate nel sistema.',
			),
			//IdSWH (identificativo posizione - we use the booking ID)
			array(
				'key' => 'idswh',
				'attr' => array(
					'class="center"'
				),
				'sortable' => 1,
				'label' => 'Codice IDSWH',
				'tip' => 'Questo è il codice identificativo della trasmissione del dato verso l\'ISTAT, ed è uguale all\'ID della prenotazione nel sistema.',
				'export_name' => 'IdSWH'
			)
		);

		// total rooms units ("camere")
		$total_rooms_units = $this->countRooms();

		//loop over the bookings to build the rows of the report
		foreach ($bookings as $gbook) {
			$guests_rows = array($gbook[0]);
			$tot_guests_rows = 1;
			$tipo = 16;
			//Codici Tipo Alloggiato
			// 16 = Ospite Singolo
			// 17 = Capofamiglia
			// 18 = Capogruppo
			// 19 = Familiare
			// 20 = Membro Gruppo
			//
			$guestsnum = 0;
			foreach ($gbook as $book) {
				$guestsnum += $book['adults'] + $book['children'];
			}
			$country = '';
			if (!empty($gbook[0]['country'])) {
				$country = $gbook[0]['country'];
			} elseif (!empty($gbook[0]['customer_country'])) {
				$country = $gbook[0]['customer_country'];
			}
			if (!empty($gbook[0]['pax_data'])) {
				$pax_data = json_decode($gbook[0]['pax_data'], true);
				if (count($pax_data)) {
					$guests_rows[0]['pax_data'] = $pax_data;
					$tot_guests_rows = 0;
					foreach ($pax_data as $roomguests) {
						$tot_guests_rows += count($roomguests);
					}
					for ($i = 1; $i < $tot_guests_rows; $i++) {
						array_push($guests_rows, $guests_rows[0]);
					}
					$tipo = count($guests_rows) > 1 ? 17 : $tipo;
				}
			}
			$history_last = $gbook[0]['history_last'];
			$tipo_provenienza = $this->guessTipoProvenienza($gbook[0]);	
			$guest_ind = 1;
			foreach ($guests_rows as $ind => $guests) {
				$use_tipo = $ind > 0 && $tipo == 17 ? 19 : $tipo;
				$insert_row = array();
				//Tipo Alloggiato
				array_push($insert_row, array(
					'key' => 'tipo',
					'callback' => function ($val) {
						switch ($val) {
							case 16:
								return 'Ospite Singolo';
							case 17:
								return 'Capofamiglia';
							case 18:
								return 'Capogruppo';
							case 19:
								return 'Familiare';
							case 20:
								return 'Membro Gruppo';
						}
						return '?';
					},
					'callback_export' => function ($val) {
						return $val;
					},
					'value' => $use_tipo
				));
				//Data Arrivo
				array_push($insert_row, array(
					'key' => 'checkin',
					'attr' => array(
						'class="center"'
					),
					'callback' => function ($val) {
						return date('d/m/Y', $val);
					},

					'value' => $guests['checkin']
				));
				
				//Cognome
				$cognome = !empty($guests['t_last_name']) ? $guests['t_last_name'] : $guests['last_name'];
				if (is_array($guests['pax_data']) && count($guests['pax_data']) > 0) {
					$j = 0;
					foreach ($guests['pax_data'] as $rnum => $rguests) {
						foreach ($rguests as $rguest) {
							$j++;
							if ($j == $guest_ind) {
								$cognome = !empty($rguest['last_name']) ? $rguest['last_name'] : $cognome;
								break 2;
							}
						}
					}
				}
				array_push($insert_row, array(
					'key' => 'cognome',
					'value' => $cognome,
					'callback_export' => function ($val) {
						return $val;
					},
				));
				//Nome
				$nome = !empty($guests['t_first_name']) ? $guests['t_first_name'] : $guests['first_name'];
				if (is_array($guests['pax_data']) && count($guests['pax_data']) > 0) {
					$j = 0;
					foreach ($guests['pax_data'] as $rnum => $rguests) {
						foreach ($rguests as $rguest) {
							$j++;
							if ($j == $guest_ind) {
								$nome = !empty($rguest['first_name']) ? $rguest['first_name'] : $nome;
								break 2;
							}
						}
					}
				}
				array_push($insert_row, array(
					'key' => 'nome',
					'value' => $nome,
					'callback_export' => function ($val) {
						return $val;
					},
				));
				//Sesso
				$gender = !empty($guests['gender']) && $guest_ind < 2 ? strtoupper($guests['gender']) : '';
				$gender = $gender == 'F' ? 2 : ($gender == 'M' ? 1 : $gender);
				if (is_array($guests['pax_data']) && count($guests['pax_data']) > 0) {
					$j = 0;
					foreach ($guests['pax_data'] as $rnum => $rguests) {
						foreach ($rguests as $rguest) {
							$j++;
							if ($j == $guest_ind) {
								$gender = !empty($rguest['gender']) ? $rguest['gender'] : $gender;
								$gender = $gender == 'Female' ? 2 : ($gender == 'Male' ? 1 : $gender);
								break 2;
							}
						}
					}
				}
				array_push($insert_row, array(
					'key' => 'gender',
					'attr' => array(
						'class="center'.(empty($gender) ? ' vbo-report-load-sesso' : '').'"'
					),
					'callback' => function ($val) {
						return $val == 2 ? 'F' : ($val == 1 ? 'M' : '?');
					},
					'callback_export' => function ($val) {
						return $val == 2 ? 'F' : ($val == 1 ? 'M' : '?');
					},
					'value' => $gender
				));
				//Data di nascita
				$dbirth = !empty($guests['bdate']) && $guest_ind < 2 ? VikBooking::getDateTimestamp($guests['bdate'], 0, 0) : '';
				if (is_array($guests['pax_data']) && count($guests['pax_data']) > 0) {
					$j = 0;
					foreach ($guests['pax_data'] as $rnum => $rguests) {
						foreach ($rguests as $rguest) {
							$j++;
							if ($j == $guest_ind) {
								$dbirth = !empty($rguest['date_birth']) ? $rguest['date_birth'] : $dbirth;
								break 2;
							}
						}
					}
				}
				$dbirth = (strpos($dbirth, '/') === false && strpos($dbirth, VikBooking::getDateSeparator()) === false) ? $dbirth : VikBooking::getDateTimestamp($dbirth, 0, 0);
				array_push($insert_row, array(
					'key' => 'dbirth',
					'attr' => array(
						'class="center'.(empty($dbirth) ? ' vbo-report-load-dbirth' : '').'"'
					),
					'callback' => function ($val) {
						return (!empty($val) && strpos($val, '/') === false && strpos($val, VikBooking::getDateSeparator()) === false) ? date('d/m/Y', $val) : (!empty($val) && strpos($val, '/') !== false ? $val : '?');
					},
					'value' => $dbirth
				));

				//Stato di nascita
				$stabirth = '';
				$staval = '';
				if (is_array($guests['pax_data']) && count($guests['pax_data']) > 0) {
					$j = 0;
					foreach ($guests['pax_data'] as $rnum => $rguests) {
						foreach ($rguests as $rguest) {
							$j++;
							if ($j == $guest_ind) {
								$stabirth = !empty($rguest['country']) ? $rguest['country'] : $stabirth;
								$staval = $this->checkCountry($stabirth);
								break 2;
							}
						}
					}
				}
				array_push($insert_row, array(
					'key' => 'stabirth',
					'attr' => array(
						'class="center'.(empty($stabirth) ? ' vbo-report-load-nazione' : '').'"'
					),
					'callback' => function ($val) {
						return (!empty($val) ? $this->nazioni[$val]['name'] : '?');
					},
					'no_export_callback' => 1,

					'value' => !empty($staval) ? $staval : ''
				));
		//Comune di nascita
				$combirth = !empty($guests['pbirth']) && $guest_ind < 2 ? strtoupper($guests['pbirth']) : '';
				$comval = '';
				$result = $this->sanitizeComune($combirth);
				$similar = false;
				$found = false;

				if (!empty($combirth) && $guest_ind < 2) {
					//If $combirth have been sanitized, then you should just check if the province is the right one
					if (isset($result['combirth'])) {
						$result = $this->checkComune($result['combirth'], true, $result['province']);
					} else {
						$result = $this->checkComune($combirth, false, '');
					}	
					$combirth = $result['combirth'];
				}

				if (is_array($guests['pax_data']) && count($guests['pax_data']) > 0) {
					$j = 0;
					foreach ($guests['pax_data'] as $rnum => $rguests) {
						foreach ($rguests as $rguest) {
							$j++;
							if ($j == $guest_ind) {
								$combirth = !empty($rguest['place_birth'])  ? strtoupper($rguest['place_birth']) : $combirth;
								$result = $this->sanitizeComune($combirth);
								//If $combirth have been sanitized, then you should just check if the province is the right one
								if (isset($result['combirth'])) {
									$result = $this->checkComune($result['combirth'], true, $result['province']);
								} else {
									$result = $this->checkComune($combirth, false, '');
								}
								$comval = isset($result['comval']) ? $result['comval'] : $comval;
								break 2;
							}
						}
					}
				}
				array_push($insert_row, array(
					'key' => 'combirth',
					'attr' => array(
						'class="center'.(empty($comval) || $result['similar'] ? ' vbo-report-load-comune' : '').'"'
					),
					'callback' => function($val) {
						return !empty($val) && isset($this->comuniProvince['comuni'][$val]) ? $this->comuniProvince['comuni'][$val]['name'] : '?';
					},
					'no_export_callback' => 1,
					'value' => $comval
				));
				
				
				//Cittadinanza
				$citizen = !empty($guests['country']) && $guest_ind < 2 ? $guests['country'] : '';
				$citizenres = array();
				$citizenval = '';
				if (!empty($citizen) && $guest_ind < 2) {
					$citizenval = $this->checkCountry($citizen);
				}
				if (is_array($guests['pax_data']) && count($guests['pax_data']) > 0) {
					$j = 0;
					foreach ($guests['pax_data'] as $rnum => $rguests) {
						foreach ($rguests as $rguest) {
							$j++;
							if ($j == $guest_ind) {
								$citizen = !empty($rguest['nationality']) ? $rguest['nationality'] : $citizen;
								$citizenval = $this->checkCountry($citizen);
								break 2;
							}
						}
					}
				}

				array_push($insert_row, array(
					'key' => 'citizen',
					'attr' => array(
						'class="center'.(empty($citizen) ? ' vbo-report-load-cittadinanza' : '').'"'
					),
					'callback' => function ($val) {
						return !empty($val) ? $this->nazioni[$val]['name'] : '?';
					},
					'no_export_callback' => 1,

					'value' => !empty($citizenval) ? $citizenval : ''
				));
				//Stato di residenza
				array_push($insert_row, array(
					'key' => 'stares',
					'attr' => array(
						'class="center vbo-report-load-nazioneres"'
					),
					'value' => '?',
					
				));
				//Comune di residenza
				array_push($insert_row, array(
					'key' => 'comres',
					'attr' => array(
						'class="center vbo-report-load-comuneres"'
					),
					'value' => '?',
					
				)); 
				array_push($insert_row,array(
					'key' => 'checkout',
					'attr' => array(
						'class="center"'
					),
					'callback' => function ($val) {
						return date('d/m/Y', $val);
					},
					'value' => $gbook[0]['checkout']
				));
				//Camere prenotate 
				array_push($insert_row, array(
					'key' => 'roomsbooked',
					'attr' => array(
						'class="center"'
					),
					'value' => count($gbook),
					'callback_export' => function ($val) {
						return $val;
					},
				));
				//Camere prenotate 
				array_push($insert_row, array(
					'key' => 'totrooms',
					'attr' => array(
						'class="center"'
					),
					'value' => $total_rooms_units,
					'callback_export' => function ($val) {
						return $val;
					},
				));
				//id booking
				array_push($insert_row, array(
					'key' => 'idbooking',
					'attr' => array(
						'class="center"'
					),
					'callback' => function ($val) {
						return '<a href="index.php?option=com_vikbooking&task=editorder&cid[]='.$val.'" target="_blank"><i class="'.VikBookingIcons::i('external-link').'"></i> '.$val.'</a>';
					},
					'callback_export' => function ($val) {
						return $val;
					},
					'value' => $guests['id']
				));

				//push fields in the rows array as a new row
				array_push($this->rows, $insert_row);
				//increment guest index
				$guest_ind++;
			}
		}

		//sort rows
		$this->sortRows($pkrsort, $pkrorder);

		//the footer row will just print the amount of records to export
		array_push($this->footerRow, array(
			array(
				'attr' => array(
					'class="vbo-report-total"'
				),
				'value' => '<h3>'.JText::translate('VBOREPORTSTOTALROW').'</h3>'
			),
			array(
				'attr' => array(
					'colspan="'.(count($this->cols) - 1).'"'
				),
				'value' => count($this->rows)
			)
		));

		//Debug
		if ($this->debug) {
			$this->setWarning('path to report file = '.urlencode(dirname(__FILE__)).'<br/>');
			$this->setWarning('$total_rooms_units = '.$total_rooms_units.'<br/>');
			$this->setWarning('$bookings:<pre>'.print_r($bookings, true).'</pre><br/>');
		}
		//

		return true;
	}

	/**
	 * Attempts to guess the column 'tipo' (provenienza)
	 * depending on the country and city given. For Italians,
	 * the name of the region is returned (if any, given the city).
	 * For foreigners, the country type is returned.
	 * 
	 * @param 	array 	the booking record
	 * 
	 * @return 	mixed 	string to use for the column 'tipo', -1 if empty.
	 */
	private function guessTipoProvenienza($booking)
	{
		if (empty($booking['customer_country'])) {
			// unable to proceed when the country is missing (-1).
			return -1;
		}

		// uppercase 3-char code of the country only
		$country3 = substr(strtoupper($booking['customer_country']), 0, 3);

		if ($country3 == 'ITA') {
			// Italian customer
			if (!empty($booking['city'])) {
				foreach ($this->map_regioni_prov as $tipo => $cities) {
					foreach ($cities as $city) {
						if (stripos($city, $booking['city']) !== false) {
							// region was found
							return $tipo;
						}
					}
				}
			}
			// empty city or no city found in the map
			return -1;
		}

		// foreigner customer
		if (isset($this->map_country_codes[$country3])) {
			// country name found for 'tipo' column
			return $this->map_country_codes[$country3];
		}
		// check the 'country_name' in 'other countries'
		if (!empty($booking['country_name'])) {
			foreach ($this->map_country_others as $tipo => $countries) {
				if (stripos($countries, $booking['country_name']) !== false) {
					// the name of the country taken from the 3-char code matches with an 'other country'. Guessing makes sense.
					return $tipo;
				}
			}
		}

		// nothing found
		return -1;
	}

	/**
	 * Generates the report columns and rows, then it outputs a CSV file
	 * for download. In case of errors, the process is not terminated (exit)
	 * to let the View display the error message.
	 * We use customExport() rather than exportCSV() only because we need a
	 * different download button rather than the classic "Export as CSV".
	 * 
	 * @param 	int 	$export_type 	the view will pass this argument to the method to call different types of export.
	 *
	 * @return 	mixed 	void on success with script termination, false otherwise.
	 */
	public function customExport($export_type = 0)
	{
		if (!$this->getReportData()) {
			return false;
		}
		$pfromdate = VikRequest::getString('fromdate', '', 'request');
		$ptodate = VikRequest::getString('todate', '', 'request');
		$pcodstru = VikRequest::getString('codstru', '', 'request');
		// manual values in filler
		$pfiller = VikRequest::getString('filler', '', 'request', VIKREQUEST_ALLOWRAW);
		$pfiller = !empty($pfiller) ? json_decode($pfiller, true) : array();
		$pfiller = !is_array($pfiller) ? array() : $pfiller;
		$records = array();

		$q = "SELECT SUM(`units`) AS `sommaunita`, SUM(`totpeople`) AS `numeropersone`, COUNT(*) AS `numerocamere`  FROM `#__vikbooking_rooms` WHERE `avail`= '1';";
		$this->dbo->setQuery($q);
		$this->dbo->execute();
		if ($this->dbo->getNumRows() > 0) {
			$records = $this->dbo->loadAssocList();
		}
		$totalBeds =(int) ($records[0]['sommaunita'] * ($records[0]['numeropersone']/$records[0]['numerocamere'])); 
		$pletti = VikRequest::getString('numletti', $totalBeds, 'request');

		//Debug
		if ($this->debug) {
			$this->setError('<pre>'.print_r($pfiller, true).'</pre><br/>');
			return false;
		}

		//map of the rows keys with their related length
		$keys_length_map = array(
			'tipo' => 2,
			'checkin' => 10,
			'cognome' => 50,
			'nome' => 30,
			'gender' => 1,
			'dbirth' => 10,
			'combirth' => 9,
			'probirth' => 2,
			'stabirth' => 9,
			'citizen' => 9,
			'comres' => 9,
			'prores' => 2,
			'stares' => 9,
			'checkout' => 10,
			'roomsbooked' => 3,
			'totrooms' => 3,
			'pletti' => 4,
			'idbooking' => 10,
			'tassasogg' => 1, //blank
			'turi' => 30, //blank
			'trasp' => 30, //blank
		);
		$keys_blank_map = array(
			'ind' => 50, //blank
			'doc' => 5, //blank
			'luogodoc' => 20, //blank
			'stdoc' => 9, //blank
		);
		$txt = '';
		// I need it to determine if I need the province or not
		$italia = false;
		$blank = false;

		//I need it since I need to save the nation later than the province, so I need to store it somewhere
		$nation = '';
		foreach ($this->rows as $ind => $row) {
			$italia = false;
			$nation = '';
			$blank = false;
			foreach ($row as $field) {
				if (isset($field['ignore_export'])) {
					continue;
				}
				// check if a value for this field was filled in manually
				if (is_array($pfiller) && isset($pfiller[$ind]) && isset($pfiller[$ind][$field['key']])) {
					if (strlen($pfiller[$ind][$field['key']])) {
						$field['value'] = $pfiller[$ind][$field['key']];
					}
				}
				
				// values set to -1 are usually empty and should have been filled in manually
				if ($field['value'] === -1) {
					// we raise an error in this case without stopping the process
					$field['value'] = 0;
					VikError::raiseWarning('', 'La riga #'.$ind.' ha un valore vuoto che doveva essere riempito manualmente cliccando sul blocco in rosso. Il file potrebbe contenere valori invalidi per questa riga.');
				}
				

				if (isset($field['callback_export'])) {
					$field['callback'] = $field['callback_export'];
				}
				$export_value = !isset($field['no_export_callback']) && isset($field['callback']) && is_callable($field['callback']) ? $field['callback']($field['value']) : $field['value'];
				//E4J Debug
				//echo '<pre> CHIAVE: ' . print_r($field['key'], true) . '</pre>';
				//E4J Debug
				//echo '<pre> VALORE 1: ' . print_r($field['value'], true) . '</pre>';	
				//echo '<pre> VALORE ESPORTAZIONE: ' . print_r($export_value, true) . '</pre>';
				//E4J Debug
				//controllo la nazione
				if ($field['key'] == 'stabirth' || $field['key'] == 'stares') {

					//se italia imposto il flag a true per prendere anche comuni e province dopo
					if($export_value == '100000100'){
						$italia = true;
					}
					$nation = $this->valueFiller($export_value, $keys_length_map[$field['key']]);
					if($field['key'] == 'stares'){
						$blank = true;
					} 
					continue;
				}

				//controllo su comuni e province:
				//se è italiano, allora prendo provincia e comune
				//se è straniero, riempio con gli spazi
				if ($field['key'] == 'combirth' || $field['key'] == 'comres') {
					if ($italia){
						//E4J Debug
						//echo '<pre>' . print_r("ITALIA", true) . '</pre>';
						foreach ($this->comuniProvince['province'] as $provs => $pname) {
							
							if ($export_value == $provs) {
								$export_value = $this->valueFiller($export_value, 9);
								//echo '<pre> LUNGHEZZA COMUNE ITALIANO: ' .$field['key'] . strlen($export_value) . '</pre>';

								$txt .= $export_value.$this->valueFiller($pname, 2);
								//echo '<pre> LUNGHEZZA: ' . strlen($export_value) . '</pre>';
							}
						}
					}
					else{
						$temp = $this->valueFiller(' ', 9);
						//echo '<pre> LUNGHEZZA ' .$field['key'] .' = '. strlen($temp) . '</pre>';

						$txt .= $temp.'  ';
					}	
					$txt .= $nation;
					//E4J Debug
					//echo '<pre>' .strlen($txt). '</pre>';

					//se sono arrivato allo stato di residenza, devo inserire tutti i campi vuoti con gli spazi
					if($blank){
						foreach ($keys_blank_map as $key => $value) {
							$txt.= $this->valueFiller('', $value);
							//E4J Debug
							//echo '<pre>' .strlen($txt). '</pre>';
						}
					}
					continue;
				}

				$export_value = $this->valueFiller($export_value, $keys_length_map[$field['key']]);
				//echo '<pre> LUNGHEZZA POST MODIFICA: ' . strlen($export_value) . '</pre>';

				//riempio i campi vuoti mancanti
				if($field['key'] == 'totrooms'){
					$export_value .=  $this->valueFiller($pletti, $keys_length_map['pletti']).' ';
				}

				if($field['key'] == 'checkout'){
					$export_value .=  $this->valueFiller(' ', $keys_length_map['turi']). $this->valueFiller(' ', $keys_length_map['trasp']);
				}
				
				if ($field['key'] != "idbooking"){
					$txt .= $export_value; 
				} else {
					$txt .= $export_value."1\r\n"; 
				}
				
				//E4J Debug
				//echo '<pre>' .strlen($txt). '</pre>';
				//E4J Debug
				//echo '<pre>' . print_r($txt , true) . '</pre>';
			}
		

		}
	//	die;
	
		// txt export
		$filename = $pcodstru.'-'.str_replace('/', '_', $pfromdate).'-'.str_replace('/', '_', $ptodate).'.txt';
		$filepath = dirname(__FILE__) . DIRECTORY_SEPARATOR . $filename;
		$handle = fopen($filepath, "w+");
   		fwrite($handle, $txt);
    	fclose($handle);
    	header('Content-Type: application/octet-stream');
	    header('Content-Disposition: attachment; filename='.basename($filepath));
	    header('Expires: 0');
	    header('Cache-Control: must-revalidate');
	    header('Pragma: public');
	    header('Content-Length: ' . filesize($filepath));
	    readfile($filepath);
	    @unlink($filepath);
		exit;
	}
	/**
	 * Parses the file Comuni.csv and returns two associative
	 * arrays: one for the Comuni and one for the Province.
	 * Every line of the CSV is composed of: Codice, Comune, Provincia.
	 *
	 * @return 	array
	 */
	private function loadComuniProvince()
	{
		$vals = array(
			'comuni' => array(
				0 => '-- Estero --'
			),
			'province' => array(
				0 => '-- Estero --'
			)
		);

		$csv = dirname(__FILE__).DIRECTORY_SEPARATOR.'Comuni.csv';
		$rows = file($csv);
		foreach ($rows as $row) {
			if (empty($row)) {
				continue;
			}
			$v = explode(';', $row);
			if (count($v) != 3) {
				continue;
			}
			$vals['comuni'][$v[0]]['name'] = $v[1];
			$vals['comuni'][$v[0]]['province'] = $v[2];
			$vals['province'][$v[2]] = $v[2];
		}

		return $vals;
	}
	/**
	 * Parses the file Nazioni.csv and returns an associative
	 * array with the code and name of the Nazione.
	 * Every line of the CSV is composed of: Codice, Nazione.
	 *
	 * @return 	array
	 */
	private function loadNazioni()
	{
		$nazioni = array();

		$csv = dirname(__FILE__).DIRECTORY_SEPARATOR.'Nazioni.csv';
		$rows = file($csv);
		foreach ($rows as $row) {
			if (empty($row)) {
				continue;
			}
			$v = explode(';', $row);
			if (count($v) != 3) {
				continue;
			}
			
			$nazioni[$v[0]]['name'] = $v[1];
			$nazioni[$v[0]]['three_code'] = $v[2];		

		}

		return $nazioni;
	}


	private function csvDebug() {
		$row = 1;
		if (($handle = fopen(dirname(__FILE__).DIRECTORY_SEPARATOR.'Comuni.csv', "r")) !== FALSE) {
		    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
		        $num = count($data);
		        echo "<p> $num fields in line $row: <br /></p>\n";
		        $row++;
		        for ($c=0; $c < $num; $c++) {
		            echo $data[$c] . "<br />\n";
		        }
		    }
		    fclose($handle);
		}
	}
	/**
	 * This method adds blank spaces to the string
	 * until the passed length of string is reached.
	 *
	 * @param 	string 		$val
	 * @param 	int 		$len
	 *
	 * @return 	string
	 */
	private function valueFiller($val, $len)
	{
		$len = empty($len) || (int)$len <= 0 ? strlen($val) : (int)$len;

		//clean up $val in case there is still a CR or LF
		$val = str_replace(array("\r\n", "\r", "\n"), '', $val);
		//
		
		if (strlen($val) < $len) {
			while (strlen($val) < $len) {
				$val .= ' ';
			}
		} elseif (strlen($val) > $len) {
			$val = substr($val, 0, $len);
		}

		return $val;
	}

	private function loadCss() {
		?>
		<style>
			.select2-results__option {
				padding-left: 20px !important;
			}

		</style>
		<?php
	}

	/**
	 * 
	 * Returns an array that contains both name and key of the comune selected, plus the associated province.
	 *
	 * @return array
	 *
	 *
	 */
	private function checkComune($combirth, $checked, $province) {
		$result = array();
		$first_found = '';
		if (!count($this->comuniProvince)) {
			$this->comuniProvince = $this->loadComuniProvince();
		}
		if (empty($combirth)) {
			return $result;
		}
		foreach ($this->comuniProvince['comuni'] as $key => $value) {
			if (!isset($value['name'])) {
				continue;
			}
			if ($value['name'] == $combirth) {
				$result['found'] = true;
				$result['combirth'] = $value['name'];
				$result['province'] = $value['province'];
				$result['comval'] = $key;
				$result['similar'] = false;
				break;
			} else if (strpos($value['name'], trim($combirth)) !== false && empty($first_found)) {
				$result['found'] = true;
				$result['combirth'] = $value['name'];
				$first_found = $key;
				$result['similar'] = true;
				$result['province'] = $value['province'];
			}
		}
		if (!$result['found']) {
			$result['combirth'] = '';
		} 

		if ($checked === true && strlen($province) > 0  && $result['found']) {
			$result['province'] = $province;
			if($province == $value['province']) {
				$result['provinceok'] = true;
				$result['province'] = $province;
			} else {
				$result['provinceok'] = false;
			}
		}
		if ($result['similar'] && $result['found']) {
			$result['comval'] = $first_found;
		}

		return $result;
	}

	/**
	 *
	 * Returns the key of the state selected by the user.
	 *
 	 * @return string
 	 *
	 */
	private function checkCountry($country) {
		$found = false;
		$staval = '';
		if (!count($this->nazioni)) {
			$this->nazioni = $this->loadNazioni();
		}
		foreach ($this->nazioni as $key => $value) {
			if (trim($value['three_code']) == trim($country)) {
				$staval = $key;
				$found = true;
				break;
			}
		}
		if ($found !== true) {
			$staval = '';
		}
		return $staval;
	}

	/**
	 *
	 * Sanitizes the "Comune": if comune contains also the province, example PRATO (PO), 
	 * then I set both Comune and Province and I check both of them with the checkComune() function. 
	 *
	 * @return array
	 *
	 */
	private function sanitizeComune($combirth) {
		$result = array();

		if (strlen($combirth) > 2) {
			if (strpos($combirth, "(") !== false) {
				$comnas = explode("(", $combirth);
				$result['combirth'] = trim($comnas[0]);
				$result['province'] = $comnas[1];
				$result['province'] = str_replace(")", "", $result['province']);
				$result['checked'] = true;
			}
		} else if(strlen($combirth) > 0){
			$result['province'] = trim($combirth); 
			$result['similar'] = true;
		}
		return $result; 
	}



}
