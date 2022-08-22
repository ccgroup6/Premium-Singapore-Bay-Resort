<?php
/** 
 * @package   	VikBooking
 * @subpackage 	core
 * @author    	E4J s.r.l.
 * @copyright 	Copyright (C) 2019 E4J s.r.l. All Rights Reserved.
 * @license  	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link 		https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

JLoader::import('adapter.mvc.controllers.admin');

/**
 * VikBooking plugin Shortcode controller.
 *
 * @since 	1.0
 * @see 	JControllerAdmin
 */
class VikBookingControllerShortcode extends JControllerAdmin
{
	public function savenew()
	{
		$this->save(2);
	}

	public function saveclose()
	{
		$this->save(1);
	}

	public function save($close = 0)
	{
		$app 	= JFactory::getApplication();
		$input 	= $app->input;
		$dbo 	= JFactory::getDbo();

		// get return URL
		$encoded = $input->getBase64('return', '');

		// make sure the user is authorised to change shortcodes
		if (!JFactory::getUser()->authorise('core.admin', 'com_vikbooking'))
		{
			$app->redirect($return);
		}

		// get item from request
		$data = $this->model->getFormData();

		// dispatch model to save the item
		$id = $this->model->save($data);

		if ($close == 2)
		{
			// save and new
			$return = 'admin.php?option=com_vikbooking&task=shortcodes.create&return=' . $encoded;
		}
		else if ($close == 1)
		{
			// save and close
			$return = 'admin.php?option=com_vikbooking&view=shortcodes&return=' . $encoded;
		}
		else
		{
			// save and stay in edit page
			$return = 'admin.php?option=com_vikbooking&task=shortcodes.edit&cid[]=' . $id . '&return=' . $encoded;
		}

		$app->redirect($return);
	}

	public function params()
	{
		$input = JFactory::getApplication()->input;

		$id 	= $input->getInt('id', 0);
		$type 	= $input->getString('type', '');

		// dispatch model to get the item (an empty ITEM if not exists)
		$item = $this->model->getItem($id);

		// inject the type to load the right form
		$item->type = $type;

		// obtain the type form
		$form = $this->model->getTypeForm($item);

		// if the form doesn't exist, the type is probably empty
		if (!$form)
		{
			// return an empty HTML
			echo "";
		}
		// render the form and encode the response
		else
		{
			$args = json_decode($item->json);
			echo json_encode($form->renderForm($args));
		}
		
		exit;
	}

	/**
	 * This task will create a page on WordPress with the requested Shortcode inside it.
	 * This is useful to automatically link Shortcodes in pages with no manual actions.
	 * 
	 * @since 	1.3.6
	 */
	public function add_to_page()
	{
		$app 	= JFactory::getApplication();
		$input 	= $app->input;
		$dbo 	= JFactory::getDbo();

		// get return URL
		$encoded = $input->getBase64('return', '');
		
		// always redirect to the shortcodes list
		$this->setRedirect('admin.php?option=com_vikbooking&view=shortcodes&return=' . $encoded);

		// make sure the user is authorised to change shortcodes
		if (!JFactory::getUser()->authorise('core.admin', 'com_vikbooking'))
		{
			return;
		}

		// get selected shortcodes
		$cid = $input->getUint('cid', array());

		// attempt to assign the shortcodes to a page
		if ($this->model->addPage($cid))
		{
			// add success message and redirect
			$app->enqueueMessage(JText::translate('VBO_SC_ADDTOPAGE_OK'));
		}

		// fetch all registered errors (if any)
		$errors = $this->model->getErrors();

		foreach ($errors as $error)
		{
			if ($error instanceof Exception)
			{
				$error = $error->getMessage();
			}

			// enqueue error message
			$app->enqueueMessage($error, 'error');
		}
	}
}
