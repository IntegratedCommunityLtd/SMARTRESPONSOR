<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2016 Adrien Roussel
 * @license : GNU/GPL v3
 * @Website : https://www.nordmograph.com/extensions
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');
class VmvendorViewWithdrawpoints extends JViewLegacy
{
	protected $form;
	// Overwriting JViewLegacy display method
	function display($tpl = null) 
	{
		$juri 	= JURI::base();
		$app 	= JFactory::getApplication();
		$doc 	= JFactory::getDocument();
		$doc->addStylesheet($juri.'components/com_vmvendor/assets/css/fontello.css');
		$doc->addStylesheet($juri.'components/com_vmvendor/assets/js/sweetalert/sweetalert2.css');
		$doc->addScript($juri.'components/com_vmvendor/assets/js/sweetalert/sweetalert2.js');
		jimport( 'joomla.form.form' );
		JHTML::_('jquery.framework'); /// included in Bootstrap
		JHTML::_('behavior.keepalive'); 
		JHTML::_('bootstrap.framework');
		JHtml::_('behavior.formvalidator');		
		JForm::addFormPath(JPATH_COMPONENT.'/models/forms');
		$model  	= $this->getModel('withdrawpoints', 'VmvendorModel');
		$this->form	= $this->get('Form');   
		$this->vendorpoints 	= $this->get('points');
		
		$main_currency 		= $this->get('MainCurrency');
		$this->main_currency_code 	= $main_currency->currency_code_3;
		$this->main_currency_symbol	= $main_currency->currency_symbol;
		$this->main_position		= $main_currency->currency_positive_style;
		$this->main_decim			= $main_currency->currency_decimal_place;
		
		$this->accept_curz 		= $this->get('AcceptedCurrencies');
		if(count($this->accept_curz)<2)
			$currency		= $main_currency;
		else
			$currency 		= $this->get('VendorMainCurrency');
		$this->currency_code 	= $currency->currency_code_3;
		$this->currency_symbol	= $currency->currency_symbol;
		$this->position			= $currency->currency_positive_style;
		$this->decim			= $currency->currency_decimal_place;
	
		
		if($this->main_currency_code == $this->currency_code)
			$this->currency_ratio = 1;
		else
			$this->currency_ratio = $model->getCurrencyRatio($this->main_currency_code , $this->currency_code ) ;
			
		$cparams 				= JComponentHelper::getParams('com_vmvendor');
		$withdraw_minimum		= $cparams->get('withdraw_minimum', '0');
		$config_withdraw_paypal	= $cparams->get('withdraw_paypal',1);
		$config_withdraw_iban	= $cparams->get('withdraw_iban',1);
		
		if( !$config_withdraw_paypal && !$config_withdraw_iban)
			$app->enqueueMessage(  JText::_('COM_VMVENDOR_WITHDRAWPOINTS_ATLEASTONEMETHODREQUIRED') , 'warning');	
		
		if($this->vendorpoints<$withdraw_minimum)
		{
			$app->enqueueMessage( 
				sprintf(JText::_('COM_VMVENDOR_WITHDRAWPOINTS_NOTENOUGH'),$withdraw_minimum ,$this->vendorpoints ) ,
				 'warning')	;	
		}
		
		// Display the view
		parent::display($tpl);
	}
}