<?php 
/*
 * @component com_vmvendor
 * @copyright Copyright (C) 2010-2016 Adrien ROUSSEL Nordmograph.com
 * @license GNU/GPL Version 3
 * @Website : https://www.nordmograph.com/extensions
 */
  
 defined('JPATH_BASE') or die; 
 /* 
 jimport('joomla.html.html'); 
 jimport('joomla.form.formfield'); 
 jimport('joomla.form.helper'); 
 */ 
 JHtml::_('formbehavior.chosen', 'select');
 JFormHelper::loadFieldClass('sql'); 
  
 /** 
  * Supports an HTML select list of options driven by SQL 
  */ 
 class JFormFieldAcceptedcurrencies extends JFormFieldSQL 
 { 
     /** 
      * The form field type. 
      */ 
     public $type = 'acceptedcurrencies'; 
  
     /** 
      * Overrides parent's getinput method 
      */ 
     protected function getInput() 
     { 
        // Initialize variables. 
        $html = ''; 
        // Load user 
        $user 		= JFactory::getUser(); 
        $user_id 	= $user->get('id'); 
		$app 		= JFactory::getApplication();
		$db 	= JFactory::getDBO();
		$virtuemart_vendor_id 	= $this->getVendorid();
		$accepted_currencies 		= $this->getVendorAcceptedCurrencies();
		
		$q ="SELECT virtuemart_currency_id, currency_name, currency_code_3  
		FROM #__virtuemart_currencies WHERE published='1' AND shared='1' 
		ORDER BY ordering ASC, currency_code_3 ASC";
		$db->setQuery($q);
		$currencies = $db->loadObjectList();
			
		echo '<select multiple="multiple" size="5" name = "'.$this->name.'"  id="'.$this->id.'" class="" >';
				
		foreach($currencies as $currency)
		{
			echo '<option value="'.$currency->virtuemart_currency_id.'" ';
			if( in_array( $currency->virtuemart_currency_id ,  $accepted_currencies) )
				echo ' selected="selected" ';
			echo '  >';
			echo $currency->currency_code_3.' '.JText::_($currency->currency_code_3).'</option>';
		}
		echo '</select>';
     } 
	 
	 public function getVendorid() 
	{
		$db 	= JFactory::getDBO();
		$user 	= JFactory::getUser();
		$q = "SELECT `virtuemart_vendor_id` FROM `#__virtuemart_vmusers` 
		WHERE `virtuemart_user_id` = '".$user->id."' ";
		$db->setQuery($q);
		$virtuemart_vendor_id = $db->loadResult();
		$this->_virtuemart_vendor_id = $virtuemart_vendor_id;
		return $this->_virtuemart_vendor_id;
	}
	
	public function getVendorAcceptedCurrencies() 
	{
		$db 	= JFactory::getDBO();
		$vendorid = $this->getVendorid();
		$q = "SELECT vendor_accepted_currencies 
		FROM #__virtuemart_vendors  
		WHERE virtuemart_vendor_id = '".$vendorid."' ";
		$db->setQuery($q);
		$accepted_currencies = $db->loadResult();
		if(!$accepted_currencies || $accepted_currencies='' )
		{
			$q = "SELECT vendor_accepted_currencies 
			FROM #__virtuemart_vendors  
			WHERE virtuemart_vendor_id = '1' ";
			$db->setQuery($q);
			$accepted_currencies = $db->loadResult();
			
		}
		$accepted_currencies = explode(',', $accepted_currencies);
		return $this->accepted_currencies = $accepted_currencies;
	}
	

	  

 } 
 ?> 