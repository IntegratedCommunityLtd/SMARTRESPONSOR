<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2016 Adrien Roussel
 * @license : GNU/GPL v3
 * @Website : https://www.nordmograph.com/extensions
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla view library
jimport('joomla.application.component.view');
class VmvendorViewMailCustomer extends JViewLegacy
{
	protected $form;
	// Overwriting JViewLegacy display method
	function display($tpl = null) 
	{
		$app 			= JFactory::getApplication();
		$doc 			= JFactory::getDocument();
		$juri = JURI::base();
		$doc->addStylesheet($juri.'components/com_vmvendor/assets/css/fontello.css');
		
		if($app->input->getInt('sent')=='1')
		{
			//$app->enqueueMessage(  );
			echo '<i class="vmv-icon-ok"></i> '.JText::_('COM_VMVENDOR_ASKVENDOR_SENT');
			return false;
		}
		jimport( 'joomla.form.form' );
		JHtml::_('behavior.keepalive'); 
		JForm::addFormPath(JPATH_COMPONENT.'/models/forms');
		JForm::addFieldPath(JPATH_COMPONENT . '/models/fields/mailcustomer');
		$model  = $this->getModel('mailcustomer', 'VmvendorModel');
		$this->form	= $this->get('Form'); 
		$this->customercontacts = $this->get('customercontacts');
		//$this->orderitem		= $this->get('orderitem');
		// Display the view	
		parent::display($tpl);
	}
}