<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2016 Adrien Roussel
 * @license : GNU/GPL v3
 * @Website : https://www.nordmograph.com/extensions
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
jimport('joomla.html.pagination' );

class VMVendorViewVendorprofile extends JViewLegacy
{
	// Overwriting JViewLegacy display method
	function display($tpl = null) 
	{
		JHtml::_('bootstrap.framework');//breaks page js
		require_once JPATH_COMPONENT.'/helpers/functions.php';
		$this->vmitemid 		= VmvendorFunctions::getVMItemid();
		$juri 	= JURI::base();
        $app = JFactory::getApplication();
		$doc = JFactory::getDocument();
		$doc->addStyleSheet($juri.'components/com_vmvendor/assets/css/fontello.css');
		$doc->addStyleSheet($juri.'components/com_vmvendor/assets/css/vendorprofile.css');
		
		$user = JFactory::getUser();
		$userid = $app->input->getInt('userid');
		if($user->id == 0 && !$userid )
		{
			$app->enqueueMessage( JText::_('COM_VMVENDOR_PROFILE_MUSTLOGIN') , 'message');
			return false;
		}
		elseif($user->id>0 && !$userid)
		{
			$userid = $user->id;
		}
		$this->ismyprofile = 0;
		if($userid == $user->id)
			$this->ismyprofile = 1;
		$allowed = 1;
		
		$cparams 		= JComponentHelper::getParams('com_vmvendor');
		$vmvcomment_enable	= $cparams->get('vmvcomment_enable',1);
		//$show_address	= $cparams->get('show_address',0);
		$exclude_users	= $cparams->get('exclude_users');
		$exclude_users	= explode(',', $exclude_users);
		
		$profileman 			= $cparams->get('profileman');
		
		
		$log_profilevisits	= $cparams->get('log_profilevisits', 1);
		if( $log_profilevisits && $user->id!='0' && $user->id!=$userid && !in_array( $user->id , $exclude_users) )
		{
			$this->get('logvisit');
		}
		$enable_vendormap		= $cparams->get('enable_vendormap',0);
		if($enable_vendormap)
			$this->coords				= $this->get('vendorlocation');
		
		$this->myproducts_array	= $this->get('myproducts');
		$this->session_currency	= $this->get('sessioncurrency');
		
		$this->main_currency	= $this->get('maincurrency');
		
		
			
			
		$this->vendor_data		= $this->get('vendordata');
		$this->user_thumb		= $this->get('userthumb');
		$this->jgroup			= $this->get('vendorjgroup');
		$this->vendor_thumb_url = $this->get('VendorThumb');
		$this->dashboard_itemid	= $this->get('DashboardItemid');
		$this->addproduct_itemid= $this->get('Addproductitemid');	
		$this->editprofile_itemid= $this->get('Editprofileitemid');		
		$this->allmyproducts	= $this->get('allmyproducts');
		$this->myproducts	= $this->myproducts_array[0];
		$this->total		= $this->myproducts_array[1];
 		$this->limit		= $this->myproducts_array[2];
		$this->limitstart	= $this->myproducts_array[3];
		if($vmvcomment_enable)
			$this->vmvcomments	= $this->get('VMVcomments');
		// Assign data to the view
		
	
		if(in_array($profileman , array('cb', 'js','es') ) )
		{
			$user_naming = $this->user_thumb[0];
			$profileitemid = $this->get('socialprofileitemid');
			if($profileman=='cb')
				$this->social_profile_url = JRoute::_('index.php?option=com_comprofiler&task=userProfile&user='.$userid.'&Itemid='.$profileitemid);
			elseif($profileman=='js')
				$this->social_profile_url = JRoute::_('index.php?option=com_community&view=profile&userid='.$userid.'&Itemid='.$profileitemid);
			elseif($profileman=='es')
				$this->social_profile_url = JRoute::_('index.php?option=com_easysocial&view=profile&id='.$userid.':'.JFilterOutput::stringURLSafe($user_naming).'&Itemid='.$profileitemid);
		}
	
		$pagination = new JPagination( $this->total, $this->limitstart, $this->limit );	
		$pagination->setAdditionalUrlParam('catfilter', $app->input->get('catfilter','','int') );
		
		$this->assignRef('pagination', $pagination );
		
		/*if($show_address)
 			$this->tabsOptions = array( 'active' => 'vendorprofileTab_0' );
		else*/
			$this->tabsOptions = array( 'active' => 'vendorprofileTab_1' );
		//if (!class_exists( 'VmConfig' ))
			//require JPATH_ADMINISTRATOR  . '/components/com_virtuemart/helpers/config.php';
		
		
		// Display the view
		parent::display($tpl);
	}
}