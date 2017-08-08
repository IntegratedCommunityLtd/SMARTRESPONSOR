<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2016 Adrien Roussel
 * @license : GNU/GPL v3
 * @Website : https://www.nordmograph.com/extensions
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');
class ModVMVendorsHelper
{
    static function getVendorRating($vendor_user_id) 
	{
		$db = JFactory::getDBO();
		$vendor_rating = array();
		$q = "SELECT percent FROM #__vmvendor_vendorratings 
		WHERE vendor_user_id = '".$vendor_user_id."' AND percent >0 ";
		$db->setQuery($q);
		$votes = $db->loadObjectList();
		$votes_count = count($votes);
		$total_pct = 0;
		if(count($votes)){
			
		foreach($votes as $vote){
			$total_pct = $total_pct + $vote->percent;
		}
		if($votes_count)
			$average_percent = $total_pct / $votes_count;
		$vendor_rating['count'] = $votes_count;
		$vendor_rating['percent'] = $average_percent;
		}
		if($votes_count)
			return $vendor_rating;
	}
	
	
	static function getVendors($params,$sortby)
    {
        
		$limit 				= $params->get('limit', '10');
		$profileman 		= $params->get('profileman','0');
		$showpic 			= $params->get('showpic', 1);
		$show_rating 		= $params->get('show_rating', 1);
		$usergroup_filter	= $params->get('usergroup_filter' ,'1');
		$exclude_mainvendor	= $params->get('exclude_mainvendor' ,'1');
		
		$cparams 			= JComponentHelper::getParams('com_vmvendor');
		$naming 			= $cparams->get('naming');
		$profileitemid 		= $cparams->get('profileitemid');
		
		


		$db = JFactory::getDBO();
		$q = "SELECT COUNT(vp.`virtuemart_product_id`) AS count,
		 vv.`virtuemart_vendor_id` , vv.`vendor_name` ,
		  vvmu.`virtuemart_user_id` ";

		if($profileman >=1)
			$q .= "  , u.".$naming;
		if($showpic)
		{
			if($profileman =='0')
				$q .= ", vm.`file_url_thumb` AS avatar ";
			elseif($profileman =='cb') 
				$q .= ", c.`avatar` ";
			elseif($profileman =='js') 
				$q .= ", cu.`thumb` AS avatar";
			elseif($profileman =='es')
			{
				$q .= ", sa.`medium` AS avatar";
			}
		}
		
		
		$q .= " FROM `#__virtuemart_products` AS vp";
		$q .= " LEFT JOIN `#__virtuemart_vendors` AS vv ON vv.`virtuemart_vendor_id` = vp.`virtuemart_vendor_id` ";
		$q .= " JOIN `#__virtuemart_vmusers` vvmu ON vvmu.`virtuemart_vendor_id` = vv.`virtuemart_vendor_id` ";
		
		if($usergroup_filter!='1')
			$q .= " JOIN `#__user_usergroup_map` uum ON uum.`user_id`= vvmu.`virtuemart_user_id` and uum.`group_id`='".$usergroup_filter."' ";
			
		if($showpic)
		{
			if($profileman =='0')
			{ // core vmvendor thumb
				$q .= " LEFT JOIN `#__virtuemart_vendor_medias` vvm ON vvm.`virtuemart_vendor_id`= vv.`virtuemart_vendor_id` 
				LEFT JOIN `#__virtuemart_medias` vm ON vvm.`virtuemart_media_id` = vm.`virtuemart_media_id` ";
			}
			elseif($profileman =='cb')
			{
				$q .= " LEFT JOIN  #__comprofiler c ON c.user_id =  vvmu.`virtuemart_user_id`   ";
			}
			elseif($profileman =='js')
			{ 
				$q .= " LEFT JOIN  #__community_users cu ON cu.userid =  vvmu.`virtuemart_user_id`   ";
			}
			elseif($profileman =='es')
			{
				$q .= " LEFT JOIN  #__social_avatars sa ON sa.uid =  vvmu.`virtuemart_user_id`   AND sa.type='user' ";
			}
			
		}
		if($profileman !='0')
				$q .= " JOIN `#__users` u ON vvmu.`virtuemart_user_id` = u.`id` ";
			
		$q .= " WHERE  vp.`published`='1' ";
		if($exclude_mainvendor)
			$q .= " AND vv.`virtuemart_vendor_id` > 1 ";
					
		$q .= " GROUP BY vv.`virtuemart_vendor_id` ";	
		if ($sortby ==1)
			$q .= " ORDER BY vp.`virtuemart_vendor_id` DESC";
		elseif($sortby ==2)
		$q .= " ORDER BY RAND()";
		elseif($sortby ==3)
			$q .= " ORDER BY COUNT(vp.`virtuemart_product_id` ) DESC";
		elseif($sortby ==4)
		{
			//echo $q .= " ORDER BY percent DESC";
			$q .=" ORDER BY ( (SELECT SUM(percent) FROM #__vmvendor_vendorratings WHERE percent>0 AND vendor_user_id = vvmu.virtuemart_user_id) / (SELECT COUNT(*) FROM #__vmvendor_vendorratings WHERE percent>0 AND vendor_user_id = vvmu.virtuemart_user_id)   ) DESC ";
		}
		$q .= "  LIMIT ".$limit;
		$db->setQuery( $q );
		$vendors = $db->loadObjectList();
        return $vendors;
    }
	
	static function getVendorprofileItemid()
	{
		$app 	= JFactory::getApplication();
		$lang = JFactory::getLanguage();
		$db 	= JFactory::getDBO();
		$q = "SELECT `id` FROM `#__menu` WHERE `link`='index.php?option=com_vmvendor&view=vendorprofile' 
		AND `type`='component'  AND ( language ='".$lang->getTag()."' OR language='*') 
		AND published='1'  AND access='1' ";
		$db->setQuery($q);
		$itemid = $db->loadResult();
		if(!$itemid)
			$app->enqueueMessage('You must set and publish a menu item to the VMVendor Vendor profile page (set to all languages or to this specific language).','warning');
		else
			return $itemid;

	
	}
}