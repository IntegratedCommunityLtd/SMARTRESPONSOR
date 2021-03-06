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
class VMVendorViewEditproduct extends JViewLegacy
{
	// Overwriting JViewLegacy display method
	function display($tpl = null) 
	{
		JHtml::_('jquery.framework'); /// included in Bootstrap
		JHtml::_('behavior.keepalive');
		$user 	= JFactory::getUser();
		
		$doc	= JFactory::getDocument();
		$juri 	= JURI::base();
		$doc->addStyleSheet($juri.'components/com_vmvendor/assets/css/fontello.css');
		$doc->addScript( JURI::base().'components/com_vmvendor/assets/js/ays/jquery.are-you-sure.js' );
		$s = "jQuery(function(){jQuery('form.vmv-form').areYouSure({'message':'".JText::_('PLG_VMVENDOR_AYS_NOTFINISHED')."'});})";	
		$doc->addScriptDeclaration( $s );
		
		$cparams    = JComponentHelper::getParams('com_vmvendor');
		$enable_embedvideo		= $cparams->get('enable_embedvideo', 0);
		$autopublish		= $cparams->get('autopublish', 1);		
		$max_imagefields	= $cparams->get('max_imagefields', 4);
		$max_filefields		= $cparams->get('max_filefields', 4);
		
		if($enable_embedvideo )
			$this->getEmbedvideoFields   = $this->get('EmbedvideoFields');


		
        // Assign data to the view
		$this->product_data			= $this->get('productdata');
		$this->product_images		= $this->get('productimages');
		$this->product_files		= $this->get('productfiles');
		$this->price_format			= $this->get('priceformat');
		$this->product_cats			= $this->get('multiplecats');
		$this->product_tags			= $this->get('producttags');
		$this->core_custom_fields	= $this->get('corecustomfields');
		$this->virtuemart_vendor_id	= $this->get('vendorid');
		
		
		$this->productlocation		= $this->get('ProductLocation');		
		$this->geoparams_set		= $this->productlocation['set'];
		$this->be_lat				= $this->productlocation['be_lat'];
		$this->be_lng				= $this->productlocation['be_lng'];
		$this->be_zoom				= $this->productlocation['be_zoom'];
		$this->be_maptype			= $this->productlocation['be_maptype'];
                
        $this->plan_max_img = '';
		$this->plan_max_files = '';
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
			
			
		}
		
		
		
		
		$product_vendor_id		= $this->product_data->virtuemart_vendor_id;
		if($product_vendor_id != $this->virtuemart_vendor_id)
		{
			$app->enqueueMessage( JText::_('COM_VMVENDOR_VMVENADD_NOTYOURPRODUCT'),'warning' );
			return false;
		}
		// Display the view
		parent::display($tpl);
	}
}