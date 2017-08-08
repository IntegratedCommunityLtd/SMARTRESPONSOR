<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2016 Adrien Roussel
 * @license : GNU/GPL
 * @Website : https://www.nordmograph.com
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JHtml::_('bootstrap.framework');
JHtml::_('behavior.keepalive');
JHTML::_('behavior.formvalidator');
jimport( 'joomla.form.form' );
$user 	= JFactory::getUser();
$app 	= JFactory::getApplication();
$doc 	= JFactory::getDocument();
$juri 	= JURI::base();


$cparams 	= JComponentHelper::getParams('com_vmvendor');
$modaltype	= $cparams->get('modaltype','s');

$doc->addStylesheet($juri.'components/com_vmvendor/assets/css/mailcustomer.css');
			
$customer_userid = $app->input->getInt('customer_userid');
		
$customer_name = $this->customercontacts[0];
$emailto		= $this->customercontacts[1];
if($app->input->getInt('customer_userid')=='0')
{
	$guest_contact = VmvendorModelMailcustomer::getGuestContact();
	$customer_name = $guest_contact[0].' '.$guest_contact[1].' '.$guest_contact[2];
	$emailto		= $guest_contact[3];
}
if($modaltype=='j' OR $modaltype=='s')
{
	echo '<h4 
	class="modal-title">'.JText::_('COM_VMVENDOR_CUSTOMERCONTACT_TITLE').': '.ucwords($customer_name).'</h4><hr />';
}
?>
<form  method="post" name="adminForm" id="adminForm"  class="form-validate form-horizontal" action="<?php echo JRoute::_('index.php?option=com_vmvendor&view=mailcustomer&tmpl=component&Itemid='.$app->input->getInt('Itemid') ) ?>" >
<fieldset name="mainfields">
      <div class="control-group">
     		<div class="control-label">
         	 <?php  echo $this->form->getLabel('vendorname') ?>                
			</div>
            <div class="controls">
       		 <?php  echo $this->form->getInput('vendorname')  	?>
        	</div>
		</div>
     
       <div class="control-group">
       		<div class="control-label">
         	 <?php  echo $this->form->getLabel('vendoremail') ?>                
			</div>
            <div class="controls">
       		 <?php  echo $user->email  	?>
        	</div>
        </div>
        <div class="control-group">
        	<div class="control-label">
         	 <?php  echo $this->form->getLabel('subject') ?>                
			</div>
            <div class="controls">
       		 <?php  echo $this->form->getInput('subject')  	?>
        	</div>
        </div>
        <div class="control-group">
			<div class="control-label">
         	 <?php  echo $this->form->getLabel('body') ?>                
			</div>
            <div class="controls">
       		 <?php  echo $this->form->getInput('body') ?>
        	</div>
         </div>
      </fieldset>
      
      <div class="form-actions">
	<button type="submit" class="btn btn-lg btn-primary validate" >
                    <i class="vmv-icon-mail"></i> <?php echo JText::_('COM_VMVENDOR_ASKVENDOR_SEND') ?>
              </button>			
	<input type="reset" value="<?php echo JText::_('COM_VMVENDOR_ASKVENDOR_RESET') ?>" class="btn btn-sm "/>
   <?php
echo JHTML::_( 'form.token' ); //add hidden token field to prevent CSRF vulnerability 
echo '<input type="hidden" name="orderitem_id" value="'.$app->input->getInt('orderitem_id').'" />
<input type="hidden" name="option" value="com_vmvendor" />
<input type="hidden" name="controller" value="mailcustomer" />
<input type="hidden" name="task" value="mailcustomer.send" />
</div>
</form>';