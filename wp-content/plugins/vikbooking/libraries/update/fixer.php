<?php
/** 
 * @package   	VikBooking - Libraries
 * @subpackage 	update
 * @author    	E4J s.r.l.
 * @copyright 	Copyright (C) 2018 E4J s.r.l. All Rights Reserved.
 * @license  	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link 		https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

/**
 * Implements the abstract methods to fix an update.
 *
 * Never use exit() and die() functions to stop the flow.
 * Return false instead to break process safely.
 */
class VikBookingUpdateFixer
{
	/**
	 * The current version.
	 *
	 * @var string
	 */
	protected $version;

	/**
	 * Class constructor.
	 */
	public function __construct($version)
	{
		$this->version = $version;
	}

	/**
	 * This method is called before the SQL installation.
	 *
	 * @return 	boolean  True to proceed with the update, otherwise false to stop.
	 */
	public function beforeInstallation()
	{
		/**
		 * Make sure all the necessary (and new) directories have been created.
		 * 
		 * @since 	1.5.0
		 */
		VikBookingUpdateManager::installUploadBackup();

		if (version_compare($this->version, '1.5.0', '<')) {
			/**
			 * For those upgrading to VBO 1.5.0 we need to move the customer upload
			 * document directories to the new location. Basically, they have been
			 * moved to the new dir /customerdocs inside the plugin's upload dir of WP.
			 * 
			 * @since 	1.5.0
			 */
			$dbo = JFactory::getDbo();

			$old_custdocs_path = str_replace(DIRECTORY_SEPARATOR . 'customerdocs', '', VBO_CUSTOMERS_PATH);
			$new_custdocs_path = VBO_CUSTOMERS_PATH;

			$q = "SELECT `docsfolder` FROM `#__vikbooking_customers` WHERE `docsfolder` IS NOT NULL";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows()) {
				$doc_folders = $dbo->loadObjectList();
				foreach ($doc_folders as $doc_folder) {
					if (empty($doc_folder->docsfolder)) {
						continue;
					}
					// move directory to new path
					$from_dir = $old_custdocs_path . DIRECTORY_SEPARATOR . $doc_folder->docsfolder;
					$to_dir   = $new_custdocs_path . DIRECTORY_SEPARATOR . $doc_folder->docsfolder;
					if (JFolder::copy($from_dir, $to_dir, $path = '', $force = true)) {
						JFolder::delete($from_dir);
					}
				}
			}
		}

		if (version_compare($this->version, '1.5.11', '<') && !VikBookingLiteManager::guessPro())
		{
			$dbo = JFactory::getDbo();
			// truncate the payment gateways table
			$dbo->setQuery("TRUNCATE TABLE `#__vikbooking_gpayments`");
			$dbo->execute();
		}

		return true;
	}

	/**
	 * This method is called after the SQL installation.
	 *
	 * @return 	boolean  True to proceed with the update, otherwise false to stop.
	 */
	public function afterInstallation()
	{
		return true;
	}
}
