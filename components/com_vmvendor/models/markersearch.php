<?php
/*
 * @component com_vmvendor
 * @copyright Copyright (C) 2010-2016 Adrien ROUSSEL Nordmograph.com
 * @license GNU/GPL Version 3
 * @Website : https://www.nordmograph.com/extensions
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modelitem');
class VmvendorModelMarkersearch extends JModelLegacy
{
	protected $markers;
	public function getMarkers() 
    {
        $db 		= JFactory::getDBO();
		$lang 		= JFactory::getLanguage();
		$dblang 	= $lang->getTag();
		$dblang 	= str_replace('-','_',strtolower($dblang) );
		
		$jinput 	= JFactory::getApplication()->input;	
		//$cparams 	= JComponentHelper::getParams( 'vmvendor' );
		$term		= $jinput->post->get('term',null,'string') ;

		
		$search_result = array();
		$i = 0;
		
		$q ="SELECT u.id , vvl.vendor_store_name AS name , vva.latitude, vva.longitude 
		FROM #__users u 
		JOIN #__virtuemart_vmusers vv ON vv.virtuemart_user_id = u.id
		JOIN  #__virtuemart_vendors_".$dblang." vvl ON vvl.virtuemart_vendor_id = vv.virtuemart_vendor_id
		JOIN  #__vmvendor_vendoraddress vva ON vva.vendor_user_id = vv.virtuemart_user_id ";
			
		$q .=" WHERE vvl.vendor_store_name LIKE '%".$db->escape($term)."%'  AND u.block ='0'  ";
			
		$q .= " AND latitude !='' AND longitude !='' AND latitude !='0' AND longitude !='0' 
		AND latitude !='255' AND longitude !='255' ";
		$db->setQuery($q);
		$members = $db->loadObjectList();
		foreach($members as $member)
		{
			$search_result[$i] =  array('title'=>$member->name , 
										'label'=>JText::_('COM_VMVENDOR_MAP_INT_VENDOR') , 
										'integration'=>'user' ,  
										'coords'=>$member->latitude.','.$member->longitude,
										'contentid'=>$member->id );
			$i++;
		}
		sort($search_result);
		return $search_result;
	}
}