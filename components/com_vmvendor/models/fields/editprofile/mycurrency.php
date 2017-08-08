<?php 
/*
 * @component com_vmvendor
 * @copyright Copyright (C) 2010-2016 Adrien ROUSSEL Nordmograph.com
 * @license GNU/GPL Version 3
 * @Website : https://www.nordmograph.com/extensions
 */
  
 defined('JPATH_BASE') or die; 
 JFormHelper::loadFieldClass('sql'); 
  
 /** 
  * Supports an HTML select list of options driven by SQL 
  */ 
 class JFormFieldMycurrency extends JFormFieldSQL 
 { 
     /** 
      * The form field type. 
      */ 
     public $type = 'mycurrency'; 
  
     /** 
      * Overrides parent's getinput method 
      */ 
     protected function getInput() 
     { 
        // Initialize variables. 
		$db 		= JFactory::getDBO();
		$virtuemart_vendor_id 	= $this->getVendorid();
		$main_currency_id 		= $this->getVendorMainCurrency();

		$q = "SELECT vendor_currency FROM #__virtuemart_vendors WHERE virtuemart_vendor_id='1'";
		$db->setQuery($q);
		$curz = $db->loadResult();
		
		$q = "SELECT vendor_accepted_currencies FROM #__virtuemart_vendors WHERE virtuemart_vendor_id='1'";
		$db->setQuery($q);
		$accept_curz = $db->loadResult().','.$curz;

		$q ="SELECT virtuemart_currency_id, currency_name, currency_code_3  
		FROM #__virtuemart_currencies 
		WHERE published='1' 
		AND virtuemart_currency_id IN(".$accept_curz.")
		ORDER BY ordering ASC, currency_code_3 ASC";
		//AND shared='1' 
		$db->setQuery($q);
		$currencies = $db->loadObjectList();
		
		echo '<select name = "'.$this->name.'"  id="'.$this->id.'" class="" >';
		foreach($currencies as $currency)
		{
			echo '<option value="'.$currency->virtuemart_currency_id.'" ';
			if( $currency->virtuemart_currency_id ==  $main_currency_id )
				echo ' selected="selected" ';
			echo '  >';
			echo $currency->currency_code_3.' - '.JText::_($currency->currency_code_3).'</option>';
		}
		echo '</select>';
     } 
	 
	 public function getVendorid() 
	{
		$db 	= JFactory::getDBO();
		$user 	= JFactory::getUser();
		$q = "SELECT virtuemart_vendor_id FROM #__virtuemart_vmusers 
		WHERE virtuemart_user_id= '".$user->id."' ";
		$db->setQuery($q);
		$virtuemart_vendor_id = $db->loadResult();
		$this->_virtuemart_vendor_id = $virtuemart_vendor_id;
		return $this->_virtuemart_vendor_id;
	}
	
	public function getVendorMainCurrency() 
	{
		$db 	= JFactory::getDBO();
		$vendorid = $this->getVendorid();
		$q = "SELECT vendor_currency 
		FROM #__virtuemart_vendors  
		WHERE virtuemart_vendor_id = '".$vendorid."' ";
		$db->setQuery($q);
		$main_currency_id = $db->loadResult();
		if(!$main_currency_id || $main_currency_id=='0' )
		{
			 $q = "SELECT vendor_currency 
			FROM #__virtuemart_vendors  
			WHERE virtuemart_vendor_id = '1' ";
			$db->setQuery($q);
			$main_currency_id = $db->loadResult();
		}
		return $this->main_currency_id = $main_currency_id;
	}
} 
 ?> 