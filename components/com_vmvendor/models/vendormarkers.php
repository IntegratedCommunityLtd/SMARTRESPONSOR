<?php
/*
 * @component com_vmvendor
 * @copyright Copyright (C) 2008-2016 Adrien ROUSSEL Nordmograph.com
 * @license GNU/GPL Version 3
 * @Website : https://www.nordmograph.com/extensions
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modelitem');
class VmvendorModelVendormarkers extends JModelLegacy
{
	protected $markers;
	public function getVendormarkers() 
    {
        $db 		= JFactory::getDBO();
		$lang 		= JFactory::getLanguage();
		$dblang 	= $lang->getTag();
		$dblang 	= str_replace('-','_',strtolower($dblang) );
		$jinput 	= JFactory::getApplication()->input;

		
		$a = $swLat	= $jinput->post->get('swLat');
		$b = $swLng	= $jinput->post->get('swLng');
		$c = $neLat	= $jinput->post->get('neLat');
		$d = $neLng	= $jinput->post->get('neLng');
		$condition1 = $a < $c ? "vva.latitude BETWEEN $a AND $c":"vva.latitude BETWEEN $c AND $a";
		$condition2 = $b < $d ? "vva.longitude BETWEEN $b AND $d":"vva.longitude BETWEEN $d AND $b";

		//component options
		//$cparams 			= JComponentHelper::getParams('com_vmvendor');
		//$profiletypesmode		= $cparams->get('profiletypesmode','core');

		$q ="SELECT vva.vendor_user_id AS userid, vva.latitude, vva.longitude ,";

		$q .= " vvl.vendor_store_name AS name 
		 FROM #__vmvendor_vendoraddress AS vva
		 JOIN  #__users AS u ON vva.vendor_user_id = u.id 
		 JOIN #__virtuemart_vmusers vv ON vv.virtuemart_user_id = u.id 
		 JOIN  #__virtuemart_vendors_".$dblang." vvl ON vvl.virtuemart_vendor_id = vv.virtuemart_vendor_id ";

		$q .= " WHERE  u.block !=1  ";

		$q .= " AND vva.latitude !='' AND vva.longitude !='' AND vva.latitude !='255' AND vva.longitude !='255' ";
		$q .= " AND  ( $condition1 ) AND ( $condition2 ) ";
		$db->setQuery($q);
		$markers = $db->loadObjectList();
		return $markers;
	}

	

}