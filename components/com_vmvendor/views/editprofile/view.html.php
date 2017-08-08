<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2016 Adrien Roussel
 * @license : GNU/GPL
 * @Website : https://www.nordmograph.com/extensions
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
class VmvendorViewEditprofile extends JViewLegacy
{
	 protected $form;
	 // Overwriting JViewLegacy display method
	function display($tpl = null) 
	{
		JHtml::_('behavior.keepalive');
		JHtml::_('jquery.framework'); /// included in Bootstrap
		$app 	= JFactory::getApplication();
		$user = JFactory::getUser();
		if(!$user->id )
		{
			$app->enqueueMessage( JText::_('COM_VMVENDOR_PROFILE_MUSTLOGIN') , 'message');
			return false;
		}
		/*$doc	= JFactory::getDocument();
		$doc->addScript( JURI::base().'components/com_vmvendor/assets/js/ays/jquery.are-you-sure.js' );
		$s = "jQuery(function(){ jQuery('form.vmv-form').areYouSure({'message':'".JText::_('PLG_VMVENDOR_AYS_NOTFINISHED')."'});})";	
		$doc->addScriptDeclaration( $s );*/
		
		// Assign data to the view
		jimport( 'joomla.form.form' ); 
		JForm::addFormPath(JPATH_COMPONENT.'/models/forms');
		JForm::addFieldPath(JPATH_COMPONENT . '/models/fields/editprofile');
		$model  = $this->getModel('editprofile', 'VmvendorModel');

		
		$cparams 			= JComponentHelper::getParams('com_vmvendor');
		$profiletypes_mode	= $cparams->get('profiletypes_mode', 0);
		$profileman			= $cparams->get('profileman', '');
		$profiletypes_ids	= $cparams->get('profiletypes_ids');
		
		
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


		$this->form	= $this->get('Form');
		$this->vendor_data		= $this->get('vendordata');
		$this->vendor_thumb		= $this->get('vendorthumb');
		// Display the view
		$data = array( 'vendor_title'        		=> $this->vendor_data[3] ,
						'vendor_telephone'      	=> $this->vendor_data[4] ,
						'vendor_url'        		=> $this->vendor_data[5] ,
						'vendor_store_desc'     	=> $this->vendor_data[0] ,
						'vendor_terms_of_service'   => $this->vendor_data[1] ,
						'vendor_legal_info'        	=> $this->vendor_data[2] ,
						
						'vendor_address'        	=> $this->vendor_data[7] ,
						'vendor_zip'        		=> $this->vendor_data[8] ,
						'vendor_city'        		=> $this->vendor_data[9] ,
						'vendor_state_id'        	=> $this->vendor_data[10] ,
						'vendor_country_id'        	=> $this->vendor_data[11] ,
						'paypal_email'        		=> $this->vendor_data[12] ,
						'iban'        				=> $this->vendor_data[13] ,
							   
							 );
				if (!empty($data)) {
					$this->form->bind($data);
				
				}

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
		$this->tabsOptions = array( 'active' => 'vendorprofileTab_1' );
		parent::display($tpl);
	}
}