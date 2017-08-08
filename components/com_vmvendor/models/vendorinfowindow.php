	<?php
/*
 * @component com_vmvendor
 * @copyright Copyright (C) 2008-2016 Adrien ROUSSEL Nordmograph.com
 * @license GNU/GPL Version 3
 * @Website : https://www.nordmograph.com/extensions
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modelitem');
class VmvendorModelvendorinfowindow extends JModelLegacy
{
	protected $infowindow;
	static function getUserinfowindow( ) 
    {
        if (!class_exists( 'VmConfig' ))
			require JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php';
		VmConfig::loadConfig();
		
		$db 		= JFactory::getDBO();
		$jinput 	= JFactory::getApplication()->input;
		$juserid	= JFactory::getUser()->id;
		$userid		= $jinput->getInt('contentid');


		$q = "SELECT  u.lastvisitDate ,
		vvl.vendor_store_name AS name, vvl.vendor_phone , vv.virtuemart_vendor_id ";
		$q .= ", (SELECT COUNT(*) FROM #__session WHERE userid=u.id AND client_id='0') AS onlinestatus ";
		
		// count products
		$q .= " , (SELECT COUNT(*) FROM #__virtuemart_products WHERE virtuemart_vendor_id=vv.virtuemart_vendor_id AND published='1' ) AS products_count ";
		
		//get rating
		$q .= " , (SELECT SUM(percent) FROM #__vmvendor_vendorratings WHERE vendor_user_id='".$db->escape($userid)."') AS rating_sum ";
		
		$q .= " , (SELECT COUNT(percent) FROM #__vmvendor_vendorratings WHERE vendor_user_id='".$db->escape($userid)."') AS rating_count ";
		
		//count reviews
		$q .= " , (SELECT COUNT(*) FROM #__vmvendor_comments WHERE vendor_userid='".$db->escape($userid)."' AND state='1') AS reviews_count ";
		
		
		
		$q .= " FROM #__users u 
		JOIN #__virtuemart_vmusers vv ON vv.virtuemart_user_id = u.id
		JOIN #__virtuemart_vendors_".VMLANG." vvl ON vvl.virtuemart_vendor_id= vv.virtuemart_vendor_id ";
		$q .= " WHERE u.id='".$db->escape($userid)."'";
		$db->setQuery($q);
		$infowindow = $db->loadObject();
		return $infowindow;
	
	}
	function getVendorThumb()
	{
		$db 	= JFactory::getDBO();
		$jinput 	= JFactory::getApplication()->input;
		$userid		= $jinput->getInt('contentid');
		

		
			
		$q = "SELECT vm.file_url 
			FROM #__virtuemart_medias vm 
			LEFT JOIN #__virtuemart_vendor_medias vvm ON vvm.virtuemart_media_id = vm.virtuemart_media_id 
			LEFT JOIN #__virtuemart_vmusers vv ON vv.virtuemart_vendor_id = vvm.virtuemart_vendor_id
			WHERE vv.virtuemart_user_id = '". $userid."' 
			AND vm.file_type='vendor' ORDER BY file_is_product_image DESC ";
		$db->setQuery($q);
		return $vendor_thumb_url = $db->loadResult();
	}
	static function getVendorprofileItemid()
	{
		$lang = JFactory::getLanguage();
		$db 	= JFactory::getDBO();
		$q = "SELECT `id` FROM `#__menu` WHERE `link`='index.php?option=com_vmvendor&view=vendorprofile' 
		AND `type`='component'  AND ( language ='".$lang->getTag()."' OR language='*') 
		AND published='1'  AND access='1' ";
		$db->setQuery($q);
		return $profile_itemid = $db->loadResult();
	
	}
}