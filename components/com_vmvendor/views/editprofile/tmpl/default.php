<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2016 Adrien Roussel
 * @license : GNU/GPL v3
 * @Website : https://www.nordmograph.com/extensions
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.keepalive');
JHTML::_('behavior.formvalidator');

$user 			= JFactory::getUser();
$doc 			= JFactory::getDocument();
$app			= JFactory::getApplication();
$juri 			= JURI::base();
$doc->addStylesheet( $juri.'components/com_vmvendor/assets/css/fontello.css');
$doc->addStylesheet( $juri.'components/com_vmvendor/assets/css/editprofile.css');
$vendor_store_desc 			= $this->vendor_data[0];
$vendor_terms_of_service	= $this->vendor_data[1];
$vendor_legal_info			= $this->vendor_data[2];	
$vendor_store_name			= ucfirst($this->vendor_data[3]);
$vendor_phone				= $this->vendor_data[4];
$vendor_url					= $this->vendor_data[5];
$vendor_id 					= $this->vendor_data[6];
$vendor_thumb 				= $this->vendor_thumb;
$cparams 				= JComponentHelper::getParams('com_vmvendor');
$profileman 			= $cparams->get('profileman');


$profiletypes_mode		= $cparams->get('profiletypes_mode', 0);
$profiletypes_ids		= $cparams->get('profiletypes_ids');

$config_withdraw_paypal	= $cparams->get('config_withdraw_paypal',1);	
$config_withdraw_iban	= $cparams->get('withdraw_iban', 1);

$currency_mode 			= $cparams->get('currency_mode',0);
$use_zipcode			= $cparams->get('use_zipcode',1);
$use_statefield			= $cparams->get('use_statefield',1);

$formbehavior_chosen	= $cparams->get('formbehavior_chosen',1);
if($formbehavior_chosen)
	JHtml::_('formbehavior.chosen', 'select');

// check iban is valid
if($config_withdraw_iban)
{
	$doc->addScript($juri.'components/com_vmvendor/assets/js/iban.js');
	$ib= "jQuery(document).ready(function(){
	document.formvalidator.setHandler('iban', function(value) {
		return IBAN.isValid(value);
	});
	});";
	$doc->addScriptDeclaration($ib);
}




if( $cparams->get('load_bootstrap_css',0) )
	$doc->addStyleSheet( $juri.'media/jui/css/bootstrap.min.css');

	
if (!class_exists( 'VmConfig' ))
	require(JPATH_ADMINISTRATOR .  '/components/com_virtuemart/helpers/config.php');	
$use_as_catalog 	=  VmConfig::get('use_as_catalog');


echo '<h1>'.JText::_('COM_VMVENDOR_EDITPRO_EDITYOURPROF').'</h1>';

	 ?>
	
	
    <form  method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" class="vmv-form form-validate form-horizontal"  action="<?php echo JRoute::_('index.php?option=com_vmvendor&view=editprofile&Itemid='.$app->input->get('Itemid') ) ?>" >
    <div class="form-actions " style="text-align:right">
	<button type="submit"  class="btn btn-primary validate"  >
	 	<i class="vmv-icon-ok"></i> <?php  echo JText::_('COM_VMVENDOR_EDITPRO_SUBMIT');   ?>
    </button>
    
	 <button type="button" class="btn" onclick="Joomla.submitbutton('editprofile.cancel')">
                    <i class="vmv-icon-cancel"></i> <?php echo JText::_('JCANCEL') ?>
                </button>
                
             
	
    </div>
    <?php
	echo JHtml::_('bootstrap.startTabSet', 'vendorprofileTab', $this->tabsOptions );
	echo JHtml::_('bootstrap.addTab', 'vendorprofileTab', 'vendorprofileTab_1',
			 JText::_('COM_VMVENDOR_EDITPRO_TABINFO') , 'class="active"' );
	?>
    	 <fieldset name="mainfields">
            <div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('vendor_title') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('vendor_title') ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('vendor_image') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('vendor_image');
					
					if(!$vendor_thumb)
					$vendor_thumb = 'components/com_vmvendor/assets/img/noimage.gif'; 
					?>
   				 <img src="<?php echo $juri.$vendor_thumb;   ?>" height="25" style="vertical-align:middle;"/>
                </div>
            </div>
            <?php 

			if(!$use_as_catalog){   ?>
                <div class="control-group">
                    <div class="control-label">
                        <?php  echo $this->form->getLabel('vendor_telephone') ?> 
                                        
                    </div>
                    <div class="controls">
                        <?php  echo $this->form->getInput('vendor_telephone') ?>
                    </div>
                </div>
                
                <div class="control-group">
                    <div class="control-label">
                        <?php  echo $this->form->getLabel('vendor_url') ?> 
                                        
                    </div>
                    <div class="controls">
                        <?php  echo $this->form->getInput('vendor_url') ?>
                    </div>
                </div>
            <?php 
			}  ?>
            
            
            <div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('vendor_address') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('vendor_address') ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('vendor_city') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('vendor_city') ?>
                </div>
            </div>
           <?php if($use_zipcode)
			{ 
			 ?>
              <div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('vendor_zip') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('vendor_zip') ?>
                </div>
            </div>
            <?php 
			}  
			 if($use_statefield)
			{ 
			 ?>
                <div class="control-group">
                    <div class="control-label">
                        <?php  echo $this->form->getLabel('vendor_state') ?> 
                                        
                    </div>
                    <div class="controls">
                        <?php  echo $this->form->getInput('vendor_state') ?>
                    </div>
                </div>
                <?php 
			}  ?>
            <div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('vendor_country') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('vendor_country') ?>
                </div>
            </div>
            
            
            <?php
			
	if(!$use_as_catalog)
{  		
	  if($config_withdraw_paypal)
       {  ?>
        <div class="control-group">
                <div class="control-label">
                    <?php  echo $this->form->getLabel('paypal_email') ?> 
                                    
                </div>
                <div class="controls">
                    <?php  echo $this->form->getInput('paypal_email') ?>
                </div>
            </div>
        <?php  } 
		
		if($config_withdraw_iban)
       {  ?>
        <div class="control-group">
                <div class="control-label">
                    <?php  echo $this->form->getLabel('iban') ?> 
                                    
                </div>
                <div class="controls">
                    <?php  echo $this->form->getInput('iban') ?>
                </div>
            </div>
        <?php  }
		
		
		 if($currency_mode)
       {  ?>
        <div class="control-group">
                <div class="control-label">
                    <?php  echo $this->form->getLabel('mycurrency') ?> 
                                    
                </div>
                <div class="controls">
                    <?php  echo $this->form->getInput('mycurrency') ?>
                </div>
            </div>
        <?php  
		}	
}
		
			
		echo JHtml::_('bootstrap.endTab');

		echo JHtml::_('bootstrap.addTab', 'vendorprofileTab', 'vendorprofileTab_2', JText::_('COM_VMVENDOR_EDITPRO_TABDESCRIPTION')  );
		
		
		 ?>
               <div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('vendor_store_desc') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('vendor_store_desc') ?>
                </div>
            </div>
            <?php 

if(!$use_as_catalog)
{   ?>
               <div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('vendor_terms_of_service') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('vendor_terms_of_service') ?>
                </div>
            </div>
            
               <div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('vendor_legal_info') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('vendor_legal_info') ?>
                </div>
            </div>
            
              <?php
			 ;
		  }  
		  echo JHtml::_('bootstrap.endTab');
		  echo JHtml::_('bootstrap.endTabSet');?>
   

        
         </fieldset>
    
    
   
    
    
    
    
    
    
    
    
    
	<?php
	if($profileman=='js' || $profileman=='es'){
		?>
	<div class="control-group">
                <div class="control-label">
                   	<?php  echo $this->form->getLabel('activity_stream') ?> 
                                    
                </div>
                <div class="controls">
                	<?php  echo $this->form->getInput('activity_stream') ?>
                </div>
            </div>
            
	<?php } ?>
    
    
    <div class="form-actions">
	<?php echo JHTML::_( 'form.token' ); //add hidden token field to prevent CSRF vulnerability ?>
	 <input type="hidden" name="option" value="com_vmvendor" />
     <input type="hidden" name="controller" value="editprofile" />
	<input type="hidden" name="task" value="editprofile.save" />
    
	<button type="submit"  class="btn btn-primary validate" >
	 	<i class="vmv-icon-ok"></i> <?php  echo JText::_('COM_VMVENDOR_EDITPRO_SUBMIT');   ?>
    </button>
    
	 <button type="button" class="btn" onclick="Joomla.submitbutton('editprofile.cancel')">
                    <i class="vmv-icon-cancel"></i>&#160;<?php echo JText::_('JCANCEL') ?>
                </button>
    </div>
	</form>