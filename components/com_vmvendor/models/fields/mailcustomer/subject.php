<?php 
/*
 * @component com_vmvendor
 * @copyright Copyright (C) 2010-2016 Adrien ROUSSEL Nordmograph.com
 * @license GNU/GPL Version 3
 * @Website : https://www.nordmograph.com/extensions
 */
  
 defined('JPATH_BASE') or die; 

 class JFormFieldSubject extends JFormField  
 { 
     /** 
      * The form field type. 
      */ 
     public $type = 'subject'; 
  
     /** 
      * Overrides parent's getinput method 
      */ 
     protected function getInput() 
     { 
       /*	$db 	= JFactory::getDBO();
		$app	= JFactory::getApplication();
		$q = "SELECT  voi.`virtuemart_product_id` , voi.`order_item_sku` , voi.`order_item_name`, voi.`product_quantity` ,
		vo.`order_number`
		FROM `#__virtuemart_order_items` voi 
		LEFT JOIN `#__virtuemart_orders` vo ON vo.`virtuemart_order_id` = voi.`virtuemart_order_id` 
		WHERE voi.`virtuemart_order_item_id` = '".$app->input->getInt('orderitem_id')."' ";
		$db->setQuery($q);
		$this->orderitem = $db->loadRow();*/
		require_once JPATH_BASE.'/components/com_vmvendor/models/mailcustomer.php';
		$this->orderitem = VmvendorModelMailcustomer::getOrderItem();
		//$model  = $this->getModel('mailcustomer', 'VmvendorModel');
		//$this->orderitem = $model->getOrderItem();
		$product_name = $this->orderitem[2];
		$order_number = $this->orderitem[4];
	
		$default_subject = sprintf(
							JText::_('COM_VMVENDOR_CUSTOMERCONTACT_ABOUTYOURORDER'),
							$order_number,
							ucfirst($product_name)
							);
		echo '<input type="text" name = "'.$this->name.'"  id="'.$this->id.'" 
		class="inputbox required" value="'.$default_subject.'" />';
	  }
	 
	 
	
 } 
 ?> 