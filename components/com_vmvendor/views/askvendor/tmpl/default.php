<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2016 Adrien Roussel
 * @license : GNU/GPL v3
 * @Website : https://www.nordmograph.com/extensions
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JHtml::_('bootstrap.framework');
JHtml::_('behavior.keepalive');
JHTML::_('behavior.formvalidator');
jimport( 'joomla.form.form' );
$user 			= JFactory::getUser();
$app 			= JFactory::getApplication();
$juri 			= JURI::base();
echo '<link rel="stylesheet" href="'.$juri.'components/com_vmvendor/assets/css/fontello.css">';
$doc 					= JFactory::getDocument();
$cparams 				= JComponentHelper::getParams('com_vmvendor');
$modaltype				= $cparams->get('modaltype','s');
$tipclass				= $cparams->get('tipclass','');
$naming 				= $cparams->get('naming', 'username');
$href = $app->input->get('href','','raw');
/*if($href!='')
{
	$href .= '&view=productdetails';
	$href .= '&virtuemart_product_id='. $app->input->get('virtuemart_product_id','','id');
	$href .= '&virtuemart_category_id='. $app->input->get('virtuemart_category_id','','id');
	$href .= '&Itemid='. $app->input->get('Itemid','','id');
}*/
$tmpl = $app->input->get('tmpl','');				
$yourname = 	'';
$youremail=		'';
if($user->id>0)
{
	$yourname = 	$user->$naming;
	$youremail=		$user->email;
}
$product_url = $app->input->get('href');
		
		
$emailto 		= $this->vendorcontacts[1];
$productname 	= $this->productname;
$sent = $app->input->getInt('sent');
if($sent>0 )
{
	if($sent==1 )
		echo '<div class="alert alert-success">'.JText::_('COM_VMVENDOR_ASKVENDOR_SENT');	
	else
		echo '<div class="alert alert-danger">'.JText::_('COM_VMVENDOR_ASKVENDOR_EMAILFAILED');	
	echo '</div>';	
	echo '<div style="text-align:center;padding:200px 0 0 0 ;">
	<input type="button" value="'.JText::_('COM_VMVENDOR_ASKVENDOR_CLOSE').'"   class="btn btn-lg btn-primary"  onclick="window.parent.SqueezeBox.close();" />
	</div>';
}	
else
{
	if($modaltype=='j' OR $modaltype=='s')
	{
		echo '<h4 class="modal-title">'.JText::_('COM_VMVENDOR_ASKVENDOR_TITLE1').' '.ucfirst($this->vendorcontacts[0]);
		if($productname!='')
		echo ' '.JText::_('COM_VMVENDOR_ASKVENDOR_TITLE2');
		echo '</h4>';
		echo '<hr />';
	}
	
	
	
	echo '<form  method="post" name="adminForm" id="adminForm"  class="form-validate form-horizontal" action="'.JRoute::_('index.php?option=com_vmvendor&view=askvendor&Itemid='.$app->input->getInt('Itemid') ).'" >';
	 ?>
      <fieldset name="mainfields">
      <div class="control-group">
     	<div class="control-label">
         	 <?php  echo $this->form->getLabel('formname') ?>                
			</div>
            <div class="controls">
       		 <?php  echo $this->form->getInput('formname')  	?>
        	</div>
            </div>
     
       <div class="control-group">
       <div class="control-label">
         	 <?php  echo $this->form->getLabel('formemail') ?>                
			</div>
            <div class="controls">
       		 <?php  echo $this->form->getInput('formemail')  	?>
        	</div>
            </div>
    
        <div class="control-group">
        <div class="control-label">
         	 <?php  echo $this->form->getLabel('formsubject') ?>                
			</div>
            <div class="controls">
       		 <?php  echo $this->form->getInput('formsubject')  	?>
        	</div>
            </div>
        <div class="control-group">
        <div class="control-label">
         	 <?php  echo $this->form->getLabel('formmessage') ?>                
			</div>
            <div class="controls">
       		 <?php  echo $this->form->getInput('formmessage')  	?>
        	</div>
         </div>
      
        <?php
	
		JPluginHelper::importPlugin('captcha');
		?>          
            <div class="control-group">
            <div class="control-label">
         	 <?php  //echo $this->form->getLabel('captcha') ?>                
			</div>
            <div class="controls">
       		 <?php  echo $this->form->getInput('captcha')  	?>
        	</div>
            </div>
        <?php
	echo '</fieldset>';
	if($productname!='')
		echo JText::_('COM_VMVENDOR_ASKVENDOR_URLWILLBEADDED');
	?>
    <div class="form-actions">
	
	<button type="submit" class="btn btn-lg btn-primary validate" >
                    <i class="vmv-icon-ok"></i> <?php echo JText::_('COM_VMVENDOR_ASKVENDOR_SEND') ?>
                </button>
				
				
	<input type="reset" value="<?php echo JText::_('COM_VMVENDOR_ASKVENDOR_RESET') ?>" class="btn btn-sm "/>
    <?php
	if($tmpl!='component')
	{
		echo '<a class="btn btn-sm" onclick="avascript:history.back()"><i class="vmv-icon-cancel"></i> '.JText::_('JCANCEL').'</a>';
     } 
	 echo JHTML::_( 'form.token' ); //add hidden token field to prevent CSRF vulnerability ?>
	<input type="hidden" name="formhref" value="<?php echo urlencode($href); ?>">
	<input type="hidden" name="emailto" value="<?php echo $emailto ?>" />
	<input type="hidden" name="option" value="com_vmvendor" />
    <input type="hidden" name="tmpl" value="<?php echo $tmpl ?>" />
    <input type="hidden" name="controller" value="askvendor" />
    <input type="hidden" name="task" value="askvendor.send" /> 
		</div>
		</form>
		<?php
}