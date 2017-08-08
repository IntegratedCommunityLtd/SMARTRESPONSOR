<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2016 Adrien Roussel
 * @license : GNU/GPL v3
 * @Website : https://www.nordmograph.com/extensions
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');
class VmvendorViewEdittax extends JViewLegacy
{
	protected $form;
	
	function display($tpl = null) 
	{
		$app 	= JFactory::getApplication();
		jimport( 'joomla.form.form' ); 
		JHTML::_('jquery.framework'); /// included in Bootstrap
		JHTML::_('behavior.keepalive');
		
		JHTML::_('behavior.formvalidator');
		JForm::addFormPath(JPATH_COMPONENT.'/models/forms');
		$model  	= $this->getModel('edittax', 'VmvendorModel');
		$this->form	= $this->get('Form');
		
		$doc	= JFactory::getDocument();
		$doc->addScript( JURI::base().'components/com_vmvendor/assets/js/ays/jquery.are-you-sure.js' );
		$s="jQuery(function(){jQuery('form.vmv-form').areYouSure({'message':'".JText::_('PLG_VMVENDOR_AYS_NOTFINISHED')."'});})";	
		$doc->addScriptDeclaration( $s );
		
		
		$cparams 	= JComponentHelper::getParams('com_vmvendor');
		$tax_mode 	= $cparams->get('tax_mode',0);
		if($tax_mode!=2)
		{
			$app->enqueueMessage( JText::_('COM_VMVENDOR_EDITTAX_TAX_MODE_DISABLED'). '<br />
			<input type="button" class="btn btn-default" name="cancel" id="cancelbutton" 
			value="'.JText::_('JCANCEL').'" onclick="history.go(-1)">','warning' );
			return false;	
		}
		$this->taxdata					= $this->get('thistaxdata');
		$this->tax_cats					= $this->get('thistaxcats');
		$this->virtuemart_vendor_id		= $this->get('vendorid');
		if(!$this->virtuemart_vendor_id)
		{
			$app->enqueueMessage( JText::_('COM_VMVENDOR_EDITPRO_NOTAVENDORYET'). ' <input type="button" 
			class="btn" name="cancel" id="cancelbutton" value="'.JText::_('JCANCEL').'" onclick="history.go(-1)">' ,'warning');
			return false;
		}
		$this->vendor_shoppergroups		= $this->get('vendorshoppergroups');
		$data = array( 'calc_name'        	=> $this->taxdata[1] ,
						'calc_descr'        => $this->taxdata[2] ,
						'calc_kind'        	=> $this->taxdata[3] ,
						'taxcatselect'      => $this->tax_cats ,
						'calc_mathop'       => $this->taxdata[4] ,
						'calc_value'        => $this->taxdata[5] 
		 			);
		if (!empty($data))
		{
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
		parent::display($tpl);
	}
}