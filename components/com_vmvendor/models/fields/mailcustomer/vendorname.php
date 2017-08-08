<?php 
/*
 * @component com_vmvendor
 * @copyright Copyright (C) 2010-2016 Adrien ROUSSEL Nordmograph.com
 * @license GNU/GPL Version 3
 * @Website : https://www.nordmograph.com/extensions
 */
 defined('JPATH_BASE') or die; 
 class JFormFieldVendorname extends JFormField 
 { 
     public $type = 'vendorname'; 
     protected function getInput() 
     { 
		$user 		= JFactory::getUser();
		$cparams 	= JComponentHelper::getParams('com_vmvendor');
		$naming 	= $cparams->get('naming', 'username');
		require_once JPATH_BASE.'/components/com_vmvendor/models/mailcustomer.php';
		$vendordata 	= VmvendorModelMailcustomer::getVendorData();
		$vendor_name 	= $vendordata->vendor_store_name.' ('.$user->$naming.')';
		echo '<input type="text" name="'.$this->name.'"  id="'.$this->id.'" 
		class="inputbox required" value="'.$vendor_name.'" />';
	  }
 } 
 ?> 