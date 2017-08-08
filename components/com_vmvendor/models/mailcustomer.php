<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
class VmvendorModelMailcustomer extends JModelForm
{
	static function getCustomercontacts()
	{
		$cparams 					= JComponentHelper::getParams('com_vmvendor');
		$app						= JFactory::getApplication();
		$naming 					= $cparams->get('naming');
		$db = JFactory::getDBO();
		$q = "SELECT `".$naming."`, `email` FROM `#__users` WHERE `id`='".$app->input->getInt('customer_userid')."' ";
		$db->setQuery($q);
		$customercontacts = $db->loadRow();
		return $customercontacts;
	}
	
	static function getOrderItem()
	{
		$db 	= JFactory::getDBO();
		$app	= JFactory::getApplication();
		$q = "SELECT  voi.`virtuemart_product_id` , voi.`order_item_sku` , voi.`order_item_name`, voi.`product_quantity` ,
		vo.`order_number`
		FROM `#__virtuemart_order_items` voi 
		LEFT JOIN `#__virtuemart_orders` vo ON vo.`virtuemart_order_id` = voi.`virtuemart_order_id` 
		WHERE voi.`virtuemart_order_item_id` = '".$app->input->getInt('orderitem_id')."' ";
		$db->setQuery($q);
		$orderitem = $db->loadRow();
		return $orderitem;
	}
	
	static function getVendorData()
	 {
		if (!class_exists( 'VmConfig' ))
			require JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php';
		VmConfig::loadConfig();
		
		$user = JFactory::getUser();
		$db = JFactory::getDBO();
		$q ="SELECT   vvl.vendor_store_name , vvl.vendor_phone 
		FROM #__virtuemart_vendors_".VMLANG." vvl 
		JOIN #__virtuemart_vmusers vv ON vv.virtuemart_vendor_id = vvl.virtuemart_vendor_id 
		WHERE vv.virtuemart_user_id='".$user->id."' " ;
		$db->setQuery($q);
		$vendordata = $db->loadObject();
		return $vendordata;
		 
	 }
	
	static function getGuestContact()
	{
		$db 			= JFactory::getDBO();
		$app			= JFactory::getApplication();
		$orderitem_id 	= $app->input->getInt('orderitem_id');
		$q = "SELECT  vou.`first_name` , vou.`middle_name` , vou.`last_name` , vou.`email` 
		FROM `#__virtuemart_order_userinfos` vou 
		JOIN  #__virtuemart_order_items voi ON vou.virtuemart_order_id = voi.virtuemart_order_id 
		WHERE vou.address_type= 'BT' 
		AND voi.virtuemart_order_item_id ='".$orderitem_id."' ";
		$db->setQuery($q);
		$orderitem = $db->loadRow();
		return $orderitem;
	}
	public function getForm($data = array(), $loadData = true)
	{
		$form = $this->loadForm(
					'com_vmvendor.mailcustomer', 
					'mailcustomer', 
					array('control' => 'jform', 'load_data' => true)
					);
		if (empty($form))
			return false;
		return $form;
	}
}
?>