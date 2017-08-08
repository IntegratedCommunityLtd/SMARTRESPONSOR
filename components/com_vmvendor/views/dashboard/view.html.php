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

class VMVendorViewDashboard extends JViewLegacy
{
	// Overwriting JViewLegacy display method
	function display($tpl = null) 
	{
		require_once JPATH_COMPONENT.'/helpers/functions.php';	
		$this->vmitemid 		=VmvendorFunctions::getVMItemid();	
		JHtml::_('bootstrap.framework');//breaks page js
		JHtml::_('behavior.modal'); 
		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		$doc = JFactory::getDocument();
		$juri 			= JURI::base();
		if($user->id==0)
		{
			$app->enqueueMessage( JText::_('COM_VMVENDOR_DASHBOARD_MUSTLOGIN') , 'message');
			return false;	
		}
		$this->mysellarray		= $this->get('mysells');
		$this->myreviewsarray	= $this->get('myreviews');
		$this->mytaxes			= $this->get('mytaxes');
		$this->main_currency	= $this->get('currency');
		$this->profile_itemid 	= $this->get('VendorprofileItemid');
		
		$this->vendor_id = $this->get('Vendorid');

		$this->tabsOptions = array( 'active' => 'mysells' );
		if($app->input->get('start_date') !='')
			$this->tabsOptions = array( 'active' => 'mystats' );
		elseif($app->input->get('delete_taxid') !='')
			$this->tabsOptions = array( 'active' => 'mytaxes' );
		elseif($app->input->get('review_id') !='')
			$this->tabsOptions = array( 'active' => 'productreviews' );

		$allowed = 1;
		$cparams 					= JComponentHelper::getParams('com_vmvendor');
		
		$profiletypes_mode		= $cparams->get('profiletypes_mode', 0);
		$profileman				= $cparams->get('profileman', '');
		$profiletypes_ids		= $cparams->get('profiletypes_ids');
		if($profiletypes_mode>0 && $profiletypes_ids!='')
		{
			require_once JPATH_COMPONENT.'/helpers/getallowedprofiles.php';
			if($profileman =='js')
		   	{
				$allowed = VmvendorAllowedProfiles::getJSProfileallowed($profiletypes_ids);
		    }
		    elseif($profileman =='es')
		    {
				$allowed = VmvendorAllowedProfiles::getESProfileallowed($profiletypes_ids);
		    }
		    if($allowed==0)
		    	return false;	
		}
		
		if($profileman =='es') // for customer pms contact form
		{
			require_once JPATH_ADMINISTRATOR . '/components/com_easysocial/includes/foundry.php';
			$doc->addStyleSheet( $juri.'components/com_easysocial/themes/wireframe/styles/style.min.css' );
			Foundry::language()->load( 'com_easysocial' , JPATH_ROOT );
			$modules    = Foundry::modules( 'mod_easysocial_toolbar' );
			$modules->loadComponentScripts();
		}
		$this->mysells				= $this->mysellarray[0];
		$this->total				= $this->mysellarray[1];
 		$this->limit				= $this->mysellarray[2];
		$this->limitstart			= $this->mysellarray[3];
		$this->myreviews			= $this->myreviewsarray[0];
		$this->reviews_total		= $this->myreviewsarray[1];
 		$this->reviews_limit		= $this->myreviewsarray[2];
		$this->reviews_limitstart	= $this->myreviewsarray[3];
		$shipment_mode				= $cparams->get('shipment_mode',0);
		if($shipment_mode)
			$this->shipmentextensionid	= $this->get('ShipmentExtensionID');
		// Assign data to the view
		$pagination = new JPagination( $this->total, $this->limitstart, $this->limit );		
		$this->assignRef('pagination', $pagination );
		// Display the view
		parent::display($tpl);
	}
}