<?php
/* @version     1.0.0
 * @component com_geommunity3cb
 * @copyright Copyright (C) 2010-2015 Adrien ROUSSEL Nordmograph.com
 * @license GNU/GPL Version 3
 * @Website : https://www.nordmograph.com/extensions
 */
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.view');
class VmvendorViewVendorinfowindow extends JViewLegacy
{
	function display($tpl = null) 
	{
		$juri = JURI::Base();
		$this->infowindow  	= $this->get('Userinfowindow');
		$infowindow			= $this->infowindow;
		$thumb				= $this->get('VendorThumb');
		$jinput 			= JFactory::getApplication()->input;
		$this->userid		= $jinput->getInt('contentid');
		$this->latitude		= $jinput->get('latitude');
		$this->longitude	= $jinput->get('longitude');
		$profile_itemid				= $this->get('VendorprofileItemid');
		
		
		$this->naming 		= $infowindow->name;
		$this->lastvisitDate= $infowindow->lastvisitDate;
		$this->daysdiff		=  number_format( ( time() - strtotime( $this->lastvisitDate ) ) /(60*60*24) ) ;
		if($infowindow->onlinestatus=='0')
			$this->onlinestatus='off';
		else
			$this->onlinestatus='on';
			
		$this->products 			= $infowindow->products_count;
		$this->reviews 				= $infowindow->reviews_count;
		
		$this->rating_sum			= $infowindow->rating_sum;
		$this->rating_count			= $infowindow->rating_count;
		if($this->rating_count>0)
			$this->rating  = number_format( $this->rating_sum / $this->rating_count	 * 5 /100 , 1 );
		else
			$this->rating = 0;
		
		$this->avatar 			= $thumb;
		//$this->canvas 			= $infowindow->canvas;
		
		$this->profile_url 		= JRoute::_('index.php?option=com_vmvendor&view=vendorprofile&userid='.$this->userid.'&Itemid='.$profile_itemid );
		
		if(!$this->avatar)
			$this->avatar 	= $juri.'components/com_vmvendor/assets/img/noimage.gif';
		else
			$this->avatar 	= $juri.$this->avatar;	
		
		parent::display($tpl);
	}
	
}