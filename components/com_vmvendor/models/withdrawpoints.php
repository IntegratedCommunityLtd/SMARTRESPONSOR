<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
class VmvendorModelWithdrawpoints extends JModelForm
{
	protected $mypoints;
	protected $paypalemail;
	protected $iban;
	protected $currency;
	protected $my_currency;
	protected $virtuemart_vendor_id;
	protected $ratio;
	public function getPoints()
    {
        $db 		= JFactory::getDBO();
		$user 		= JFactory::getUser();
		$cparams 	= JComponentHelper::getParams('com_vmvendor');
		$enable_vendorpoints = $cparams->get('enable_vendorpoints' , 'vmv' );
		if($enable_vendorpoints=='vmv' OR $enable_vendorpoints=='1')
		{
			$q = 'SELECT `points`
			FROM `#__vmvendor_userpoints` 				  
			WHERE `userid` ='.$user->id;
		}
		elseif($enable_vendorpoints=='alta' OR $enable_vendorpoints=='alpha')
		{
			$q = 'SELECT `points`
			FROM `#__alpha_userpoints` 				  
			WHERE `userid` ='.$user->id;
		}
        $db->setQuery($q);
        $mypoints = $db->loadResult();
		if(!$mypoints)
			$mypoints = '0';
        return $mypoints;
    }

	public function getMainCurrency()
    {
        $db = JFactory::getDBO();
        $q ="SELECT vc.`currency_code_3` , vc.`currency_symbol` , vc.`currency_positive_style` ,
		 vc.`currency_decimal_place` , vc.`currency_decimal_symbol` , vc.`currency_thousands` 
        FROM `#__virtuemart_currencies` vc 
        LEFT JOIN `#__virtuemart_vendors` vv ON vv.`vendor_currency` = vc.`virtuemart_currency_id` 
        WHERE vv.`virtuemart_vendor_id` ='1' " ;        
        $db->setQuery($q);
        $currency = $db->loadObject();
        return $currency;
    }
    public function getVendorMainCurrency() 
	{
		$db 	= JFactory::getDBO();
		$vendorid = $this->getVendorid();
		$q ="SELECT vc.`currency_code_3` , vc.`currency_symbol` , vc.`currency_positive_style` ,
			vc.`currency_decimal_place` , vc.`currency_decimal_symbol` , vc.`currency_thousands` 
			FROM `#__virtuemart_currencies` vc 
			JOIN `#__virtuemart_vendors` vv ON vv.`vendor_currency` = vc.`virtuemart_currency_id` 
			WHERE vv.`virtuemart_vendor_id` ='".$vendorid."' ";
		$db->setQuery($q);
		$my_currency = $db->loadObject();
		if(!$my_currency)
		{
			$q ="SELECT vc.`currency_code_3` , vc.`currency_symbol` , vc.`currency_positive_style` ,
			vc.`currency_decimal_place` , vc.`currency_decimal_symbol` , vc.`currency_thousands` 
			FROM `#__virtuemart_currencies` vc 
			LEFT JOIN `#__virtuemart_vendors` vv ON vv.`vendor_currency` = vc.`virtuemart_currency_id` 
			WHERE vv.`virtuemart_vendor_id` ='1' " ;  
			$db->setQuery($q);
			$my_currency = $db->loadObject();
		}
		return $my_currency;
	}
	
	 public function getVendorid() 
	{
		$db 	= JFactory::getDBO();
		$user 	= JFactory::getUser();
		$q = "SELECT virtuemart_vendor_id FROM #__virtuemart_vmusers 
		WHERE virtuemart_user_id= '".$user->id."' ";
		$db->setQuery($q);
		$virtuemart_vendor_id = $db->loadResult();
		$virtuemart_vendor_id = $virtuemart_vendor_id;
		return $virtuemart_vendor_id;
	}
	
	public function getCurrencyRatio($currA , $currB ) 
	{
		require_once JPATH_ADMINISTRATOR.'/components/com_virtuemart/plugins/currency_converter/convertECB.php';
		$this->_currencyConverter = new convertECB();
		$amountA = 1 ;
		//$ratio = convertECB::convert( $amountA, $currA='', $currB='', $a2rC = true, $relatedCurrency = 'EUR')
		//echo $amountA.','. $currA.','. $currB.','. $a2rC = true.','. $relatedCurrency = 'EUR';
		$ratio = $this->_currencyConverter->convert( $amountA, $currA, $currB, $a2rC = true, $relatedCurrency = 'EUR');
		return $ratio;
	}
	
	 public function getAcceptedCurrencies() 
	{
		$db 	= JFactory::getDBO();
		$q = "SELECT vendor_currency FROM #__virtuemart_vendors WHERE virtuemart_vendor_id='1'";
		$db->setQuery($q);
		$curz = $db->loadResult();
		$q = "SELECT vendor_accepted_currencies FROM #__virtuemart_vendors WHERE virtuemart_vendor_id='1'";
		$db->setQuery($q);
		$accept_curz = $db->loadResult().','.$curz;
		$accept_curz = explode(',',$accept_curz);
		return array_unique($accept_curz);
	}
	
	public function getForm($data = array(), $loadData = true)
	{
		$form = $this->loadForm(
			'com_vmvendor.withdrawpoints',
			'withdrawpoints',
			array('control' => 'jform', 'load_data' => true)
		);
		if (empty($form))
			return false;
		return $form;
	}
}