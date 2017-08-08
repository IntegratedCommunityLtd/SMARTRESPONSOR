<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2016 Adrien Roussel
 * @license : GNU/GPL v3
 * @Website : https://www.nordmograph.com/extensions
 */
defined('_JEXEC') or die('Restricted access');
// import Joomla view library
jimport('joomla.application.component.view');
class VmvendorViewAddproduct extends JViewLegacy
{
	// Overwriting JView display method
	function display($tpl = null) 
	{
		JHtml::_('jquery.framework'); /// included in Bootstrap
		JHtml::_('bootstrap.tooltip');
		JHtml::_('behavior.keepalive');
		$user 	= JFactory::getUser();
		$app	= JFactory::getApplication();
		$doc	= JFactory::getDocument();
		$juri 	= JURI::base();
		$doc->addStyleSheet($juri.'components/com_vmvendor/assets/css/addproduct.css');
		$doc->addStyleSheet($juri.'components/com_vmvendor/assets/css/fontello.css');
		$doc->addScript(JURI::base().'components/com_vmvendor/assets/js/ays/jquery.are-you-sure.js' );
		$s="jQuery(function(){jQuery('form.vmv-form').areYouSure({'message':'".JText::_('PLG_VMVENDOR_AYS_NOTFINISHED')."'});})";	
		$doc->addScriptDeclaration($s);

		$this->price_format			= $this->get('priceformat');
		$this->core_custom_fields	= $this->get('corecustomfields');
		$this->virtuemart_vendor_id	= $this->get('vendorid');
		
		
		if (!class_exists( 'VmConfig' ))
			require JPATH_ADMINISTRATOR .  '/components/com_virtuemart/helpers/config.php';
		VmConfig::loadConfig();
		$safepath	= VmConfig::get( 'forSale_path' );
		
		$cparams    = JComponentHelper::getParams('com_vmvendor');
		$enablefiles 		= $cparams->get('enablefiles', 0);
		$profileman  		= $cparams->get('profileman');
		//$paypalemail_field	= $cparams->get('paypalemail_field',1);
		$enable_embedvideo	= $cparams->get('enable_embedvideo', 0);
		$autopublish		= $cparams->get('autopublish', 1);
		$max_imagefields	= $cparams->get('max_imagefields', 4);
		$max_filefields		= $cparams->get('max_filefields', 4);
		
		if($enable_embedvideo )
			$this->getEmbedvideoFields   = $this->get('EmbedvideoFields');

		/*if($paypalemail_field && $user->id >0 )
		{
			$vendor_paypal_email		= $this->get('VendorPaypalEmail');
			if(!$vendor_paypal_email )
				$app->enqueueMessage( JText::_('COM_VMVENDOR_VMVENADD_MISSINGPAYPALEMAIL'),'warning');
		}*/

		if($enablefiles && $safepath=='')
			$app->enqueueMessage( JText::_('COM_VMVENDOR_VMVENADD_SAFEPATHREQUIRED'),'warning' );

		if(VmConfig::get('multix','none')!='admin')
			$app->enqueueMessage(  JText::_('COM_VMVENDOR_VMVENADD_MULTIVENDORREQUIRED'),'warning' );

		$this->plan_max_img ='';
		$this->plan_max_files = '';
		$this->autopublish =0;// for guests
		if($user->id>0)
		{
			require_once JPATH_COMPONENT.'/helpers/getvendorplan.php';
			$vendor_plan 			= VmvendorHelper::getvendorplan( $user->id );
			$vendor_products_count 	= VmvendorHelper::countVendorProducts( $user->id );
			
			if(!$vendor_plan)
			{
				$this->plan_max_products	= '';
				$this->plan_max_img 		= $max_imagefields;
				$this->plan_max_files 		= $max_filefields;
				$this->autopublish 			= $autopublish;
			}
			else
			{
				$this->plan_max_products 	= $vendor_plan->max_products;
				$this->plan_max_img 		= $vendor_plan->max_img;
				$this->plan_max_files 		= $vendor_plan->max_files;
				$this->autopublish 			= $vendor_plan->autopublish;
			}
			if($this->autopublish=='2')
				$this->autopublish=0;
			
			if( $this->plan_max_products >0 && $vendor_products_count >= $this->plan_max_products )
			{
				$app->enqueueMessage( sprintf( JText::_('COM_VMVENDOR_PLAN_MAXPRODREACHED') , $vendor_plan->max_products , $vendor_plan->title ),'warning' );
				return false;
			}
			
		}
		$allowed = 1;
		$profiletypes_mode		= $cparams->get('profiletypes_mode', 0);
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
		parent::display($tpl);
	}
}