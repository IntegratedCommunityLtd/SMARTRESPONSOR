<?php 
/*
 * @component com_vmvendor
 * @copyright Copyright (C) 2010-2016 Adrien ROUSSEL Nordmograph.com
 * @license GNU/GPL Version 3
 * @Website : https://www.nordmograph.com/extensions
 */
  
 defined('JPATH_BASE') or die; 
 class JFormFieldBody extends JFormField  
 { 
     /** 
      * The form field type. 
      */ 
     public $type = 'body'; 
  
     /** 
      * Overrides parent's getinput method 
      */ 
     protected function getInput() 
     { 
     	$user 		= JFactory::getUser();
		$app		= JFactory::getApplication();
		$cparams 	= JComponentHelper::getParams('com_vmvendor');
		$naming 	= $cparams->get('naming', 'username');
		
		require_once JPATH_BASE.'/components/com_vmvendor/models/mailcustomer.php';
		$orderitem 		= VmvendorModelMailcustomer::getOrderItem();
		$customercontacts = VmvendorModelMailcustomer::getCustomercontacts();
		$vendordata 	= VmvendorModelMailcustomer::getVendorData();
		$vendor_name 	= $vendordata->vendor_store_name;
		$vendor_phone 	= $vendordata->vendor_phone;
		
		$product_name 	= $orderitem[2];
		$order_number 	= $orderitem[4];
		
		$customer_name 	= $customercontacts[0];
		$emailto		= $customercontacts[1];
		if($app->input->getInt('customer_userid')=='0')
		{
			$guest_contact = VmvendorModelMailcustomer::getGuestContact();
			$customer_name = $guest_contact[0].' '.$guest_contact[1].' '.$guest_contact[2];
			$emailto		= $guest_contact[3];
			
		}
	
		$default_subject = sprintf(
							JText::_('COM_VMVENDOR_CUSTOMERCONTACT_ABOUTYOURORDER'),
							$order_number,
							ucfirst($product_name)
						);
		$default_message = sprintf(
							JText::_('COM_VMVENDOR_CUSTOMERCONTACT_BODY'),
							ucfirst($customer_name)
						);
		$default_message .= $default_subject;
	
		$signature = "\n\n--\n".$user->$naming."\n";
		$signature .= $vendor_name."\n";
		$signature .= $vendor_phone."\n";
		$signature .= JURI::base();
	
		$default_message .= $signature;
		echo '<textarea type="text" name = "'.$this->name.'"  id="'.$this->id.'" 
		class="inputbox required" >'.$default_message.'</textarea>';
	}	
 } 
 ?> 