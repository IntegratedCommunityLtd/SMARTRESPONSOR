<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2016 Adrien Roussel
 * @license : GNU/GPL v3
 * @Website : https://www.nordmograph.com/extensions
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
$juri 	= JURI::base();
$user 	= JFactory::getUser();
$app 	= JFactory::getApplication();
$doc 	= JFactory::getDocument();
$doc->addStylesheet($juri.'components/com_vmvendor/assets/css/fontello.css');
$doc->addStylesheet($juri.'components/com_vmvendor/assets/css/withdrawpoints.css');
$cparams 				= JComponentHelper::getParams('com_vmvendor');
$ratio 					= $cparams->get('aup_ratio','1');
$withdraw_minimum		= $cparams->get('withdraw_minimum', '0');
$withdraw_maximum		= $cparams->get('withdraw_maximum', '1000000');
$naming 				= $cparams->get('naming', 'username');	
$additional_recipient	= $cparams->get('additional_recipient');	
$config_withdraw_paypal	= $cparams->get('withdraw_paypal',1);
$config_withdraw_iban	= $cparams->get('withdraw_iban',1);
$tipclass		= $cparams->get('tipclass','');

echo '<h1><i class="vmv-icon-bank" ></i>'.JText::_('COM_VMVENDOR_WITHDRAWPOINTS_TITLE').'</h1>';

if($this->vendorpoints>=$withdraw_minimum && $this->vendorpoints>0)
	$ctr_class = 'badge-success';
else
	$ctr_class = 'badge-danger';
echo '<div id="vmv-pts-badgecontainer">
		<div class="badge vmv-pts-countr '.$ctr_class.'">
		<span id="vmv-cntr">'.$this->vendorpoints.'</span>
		<div>'.JText::_('COM_VMVENDOR_WITHDRAWPOINTS_MYPTS').'</div>
		</div>
		<div class="vmv-mini-info">';
		if(strpos($this->main_position,'symbol') < strpos($this->main_position,'number') )
			echo sprintf(JText::_('COM_VMVENDOR_WITHDRAWPOINTS_PTSRATILEGEND'), $this->main_currency_symbol, '',$ratio);
		else
			echo sprintf(JText::_('COM_VMVENDOR_WITHDRAWPOINTS_PTSRATILEGEND'),  '',$this->main_currency_symbol,$ratio);
		if($this->main_currency_code != $this->currency_code )
		{
			echo '<br />';
			if(strpos($this->position,'symbol') < strpos($this->position,'number') )
			{
				echo sprintf(JText::_('COM_VMVENDOR_WITHDRAWPOINTS_PTSRATILEGEND'), 
				$this->currency_symbol, '',
				number_format($ratio/$this->currency_ratio , $this->decim)
				);
			}
			else
			{
				echo sprintf(JText::_('COM_VMVENDOR_WITHDRAWPOINTS_PTSRATILEGEND'),  
				'',$this->currency_symbol,
				number_format($ratio/$this->currency_ratio)
				);
			}
		}
			
			
echo '</div></div>';

echo JText::_('COM_VMVENDOR_WITHDRAWPOINTS_DESC');


if($user->id>0)
{
	$yourname = 	$user->$naming;
	$youremail=		$user->email;
}

// prevent setting more points than owning
$maxpts= "jQuery(document).ready(function(){
	document.formvalidator.setHandler('maxpoints', function(value) {
		var res = true;
		if( value>".$this->vendorpoints." || value<".$withdraw_minimum." || value>".$withdraw_maximum." || value==0)
			res=false;
		if(!res)
			jQuery('#pts_equivalent').val('#');
		return res;
	});});";
$doc->addScriptDeclaration($maxpts);
	
	

if($config_withdraw_iban)// check iban is valid
{
	$doc->addScript($juri.'components/com_vmvendor/assets/js/iban.js');
	$ib= "jQuery(document).ready(function(){
		document.formvalidator.setHandler('iban', function(value) {
			return IBAN.isValid(value);
		});
	});";
	$doc->addScriptDeclaration($ib);
}

if($config_withdraw_paypal && $config_withdraw_iban)
{
	$rad="jQuery(document).ready(function(){ 
    jQuery('input[name$=\'jform[prefered_method]\']').click(function() {
        var test = jQuery(this).val();
        jQuery('div.withdr').hide();
		if(test=='paypal'){
			//jQuery('#iban input').val('');
			jQuery('#iban input').removeClass( 'required' );
		}
		if(test=='iban'){
			//jQuery('#paypal input').val('');
			jQuery('#paypal input').removeClass( 'required' );
		}
		jQuery('#'+test+' input').addClass( 'required' );
		jQuery('#'+test).show();
    }); 
	});";
	$doc->addScriptDeclaration($rad);	
}

$swal = 'function ValidateWithd(it){
    var pointsamount=jQuery("#jform_withdrawpoints").val();
	var paypalID=jQuery("#jform_withdrawpaypalemail").val();
	var ibanID=jQuery("#jform_withdrawiban").val();
	var accountType = jQuery("input:radio[name =\'jform[prefered_method]\']:checked").val();
	if(accountType=="paypal")
		ibanID="";
	else if(accountType=="iban")
		paypalID="";
	swal({   
			title: "'.JText::_('COM_VMVENDOR_WITHDRAW_CONFIRM_TITLE').'",
			html: pointsamount + " '.JText::_('COM_VMVENDOR_WITHDRAW_POPUPSUMMARY').'<br />" + accountType.toUpperCase() +" : <b>"+paypalID + ibanID +"</b>",
			type: "warning",
			showCancelButton: true,
			cancelButtonColor: "#d33",
			confirmButtonColor: "green",
			confirmButtonText: "'.JText::_('JYes').'",
			closeOnConfirm: true 
		},
		function(isconf) {  
			//jQuery("#adminForm").submit();
			if(isconf)
				it.submit();
			
		}
	);
	return false;
};';
$doc->addScriptDeclaration($swal);	

	echo '<br /><br />';
	
	echo '<form  method="post" name="adminForm" id="adminForm"  onSubmit="return ValidateWithd(this)" class="vmv-form form-validate form-horizontal" action="'.JRoute::_('index.php?option=com_vmvendor&view=withdrawpoints&Itemid='.$app->input->get('Itemid') ).'" >';
	 ?>
      <fieldset name="mainfields">
      <div class="control-group">
     	<div class="control-label">
         	 <?php  echo $this->form->getLabel('withdrawpoints') ?>                
			</div>
            <div class="controls">
       		 <?php  echo $this->form->getInput('withdrawpoints') ;
			 
			  $calc = 'function calcul(){
                var pointsamount = jQuery("#jform_withdrawpoints").val();           
				var total = Number(pointsamount /'.($ratio / $this->currency_ratio) .');
				if(pointsamount>'.$this->vendorpoints.' || pointsamount<'.$withdraw_minimum.' || pointsamount>'.$withdraw_maximum.'  || pointsamount==0)
					jQuery("#pts_equivalent").val( "#" ) ;
				else
					jQuery("#pts_equivalent").val( total.toFixed('.$this->decim.') ) ;
            }';
		$doc->addScriptDeclaration($calc);
			 if(strpos($this->position,'symbol') < strpos($this->position,'number') )
				echo $this->currency_symbol;
			echo '<input type="text" name = "pts_equivalent"  id="pts_equivalent" width="20"
			class="" disabled value="'.number_format($this->vendorpoints / ($ratio / $this->currency_ratio) , $this->decim).'"  />';
			if(strpos($this->position,'symbol') > strpos($this->position,'number') )
				echo $this->currency_symbol;
				
			if(count($this->accept_curz)>1)	
            	echo '<a href="'.JRoute::_('index.php?option=com_vmvendor&view=editprofile').'" ><i class="vmv-icon-info-sign '.$tipclass.'" title="'.JText::_('COM_VMVENDOR_WITHDRAWDATA_CURRENCYNOTICE').'"></i></a>';
        ?>	</div>
            </div>
          
     <?php
     if($config_withdraw_paypal && $config_withdraw_iban)
	 { ?>
       <div class="control-group">
       <div class="control-label">
         	 <?php  echo $this->form->getLabel('prefered_method') ?>                
			</div>
            <div class="controls">
       		 <?php  echo $this->form->getInput('prefered_method')  	?>
        	</div>
            </div> 
 
        <div class="control-group">
       	<div class="control-label">
         	 <?php  echo JText::_('COM_VMVENDOR_WITHDRAWDATA_TITLE') ?>                
			</div>
            </div>
     <?php }  
	 
	 if($config_withdraw_paypal)
	 {
	 ?>
         <div class="control-group withdr" id="paypal">
       <div class="control-label">
         	 <?php  echo $this->form->getLabel('withdrawpaypalemail') ?>               
			</div>
            <div class="controls">
       		 <?php  echo $this->form->getInput('withdrawpaypalemail')  	?>
        	</div>
            </div>
    <?php }  
	 
	 if($config_withdraw_iban)
	 {
	 ?>
        <div class="control-group withdr" id="iban">
        <div class="control-label">
         	 <?php  echo $this->form->getLabel('withdrawiban') ?>           
			</div>
            <div class="controls">
       		 <?php  echo $this->form->getInput('withdrawiban')  	?>
        	</div>
            </div>
      <?php }  ?>
        <div class="control-group">
        <div class="control-label">
         	 <?php  echo $this->form->getLabel('copyoftherequest') ?>                
			</div>
            <div class="controls">
       		 <?php  echo $this->form->getInput('copyoftherequest')  	?>
        	</div>
         </div></fieldset>
    <div class="form-actions">
	<button type="submit" class="btn btn-large btn-primary validate" >
    	<i class="vmv-icon-ok"></i> <?php echo JText::_('COM_VMVENDOR_WITHDRAW_SENDREQUEST') ?>
    </button>		
	<input type="reset" value="<?php echo JText::_('COM_VMVENDOR_WITHDRAW_RESETFORM') ?>" class="btn btn-sm "/>
    </div>
    <?php
	 echo JHTML::_( 'form.token' ); //add hidden token field to prevent CSRF vulnerability 
?>
<input type="hidden" name="option" value="com_vmvendor" />
<input type="hidden" name="controller" value="withdrawpoints" />
<input type="hidden" name="task" value="withdrawpoints.send" /> 
</form>