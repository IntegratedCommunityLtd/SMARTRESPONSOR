<?php
defined('_JEXEC') or die;
?>
<style>
 #navbar{display:none!Important;}
 #smarttop, #b1, #b2, #b3, #b4 {padding-top: 0px!important;margin-top: 0px!important;}
</style>
<script type="text/javascript" async src="/js/jquery.fullPage.js"></script>
<script type="text/javascript">
//			jQuery('#b0').css('display', 'none!important');	
			jQuery('#fp-nav').addClass(' hidden-xs');	
			jQuery('#navbar').css('display', 'none!important');	
			jQuery('#b1').css('display', 'none!important');	
			jQuery('#b2').css('display', 'none!important');	
//			jQuery('#b0').css('height', '0!important');	
			jQuery('#b1').css('width', '0px!important');	
			jQuery('#b2').css('width', '0px!important');
			jQuery('.active').css('border', 'none!important');	
			jQuery('#b3').css('width', 'inherit');	
			jQuery('#b3').css('padding', '0');				
			jQuery('#nent').css('max-width', '100%!important');	
			jQuery('#nent').css('width', '100%');	
//			jQuery('#nent').css('margin-top', '0');	
//			jQuery('#smarttop').css('margin-top', '0');	
//			jQuery('#page').css('margin-top', '45px');	
</script>
<link rel="stylesheet" type="text/css" defer href="/css/jquery.fullPage.css" />
<link rel="stylesheet" type="text/css" defer href="/css/checkbox-style.css" />
<link rel="stylesheet" type="text/css" href="/css/roboto.css">
<link rel="stylesheet" type="text/css" href="/css/sigin.css" />

<div id="page" class="page">

	<div id="guest" class="section">

			<div class="clear"></div>
				<span class="text-center">
					<?php echo JText::_('COM_USERS_SIGIN_CONNECT') ?>
					<?php echo JText::_('COM_USERS_SIGIN_ITSEASY') ?>
					<?php echo JText::_('COM_USERS_SIGIN_WELLCOME_NET') ?>
				</span>
	<div class="row">
	<div class="col-md-2"></div>
	<div class="col-md-8 text-center">
		<form id="member-registration" role="form" action="<?php echo JRoute::_('index.php?option=com_ajax&option=com_users&task=registration.register'); ?>" method="post" class="form-validate" enctype="multipart/form-data" data-toggle="validator">
			<div class="btn-group-vertical">
				<div class="form-group member-registration">
					
					<span class="text-center">
							<h3><?php echo JText::_('COM_USERS_REGISTRATION_DEFAULT_LABEL') ?></h3> 
					</span>
		
					<fieldset>
						<input id="jform_name" type="text" name="jform[name]" value="" autocomplete="off" class="required form-control" tabindex="0" size="30" placeholder="<?php echo JText::_('MOD_LOGIN_VALUE_NAME') ?>" required="" aria-required="true" >				
						<input id="jform_username" type="text" name="jform[username]" value="" autocomplete="off" class="validate-username required form-control" tabindex="1" size="30" maxlength="99" aria-required="true" placeholder="<?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?>" />
						<input id="jform_password1" type="password" name="jform[password1]" value="" autocomplete="off" class="form-control validate-password required" tabindex="2" size="30" maxlength="99" value="" aria-required="true" placeholder="<?php echo JText::_('MOD_LOGIN_VALUE_PASSWORD') ?>" />
						<input id="jform_password2" type="password" name="jform[password2]" value="" autocomplete="off" class="form-control validate-password required" tabindex="3" size="30" maxlength="99" aria-required="true" placeholder="<?php echo JText::_('MOD_LOGIN_VALUE_PASSWORD_CONFIRM') ?>" />
						<input id="jform_email1" type="email" name="jform[email1]" value="" autocomplete="off" class="validate-email form-control required" tabindex="4" size="30" placeholder="<?php echo JText::_('MOD_LOGIN_VALUE_EMAIL') ?>" required="" aria-required="true" >
						<input id="jform_email2" type="email" name="jform[email2]" value="" autocomplete="off" class="validate-email form-control required" tabindex="4" size="30" placeholder="<?php echo JText::_('MOD_LOGIN_VALUE_EMAIL') ?>" required="" aria-required="true" >
					</fieldset>
						<?php
						// Возврат модуля с Регистраций через ФБ
						$modules =JModuleHelper::getModules('facebook-login');
								foreach ($modules as $module){
									echo JModuleHelper::renderModule($module);
								}
						?>						
				</div>
					<input type="hidden" name="option" value="com_users" />
					<input type="hidden" name="task" value="registration.register" />
					<?php echo JHtml::_('form.token');?>			
						<button type="submit" class="btn btn-warning btn-lg submit-registration" tabindex="5"><?php echo JText::_('COM_USERS_REGISTRATION');?></button>
			</div>


		</form>



	</div>					
	<div class="col-md-2"></div>
	</div>
<?php	/*	
					<h3 class="hidden-xs hidden-phone"><?php //echo JText::_('COM_USERS_SIGIN_CONGRATS') ?></h4>
					<h4 class="hidden-xs hidden-phone"><?php //echo JText::_('COM_USERS_IS') ?></h4>
					<h5 class="hidden-xs hidden-phone separatortop separatorbutton"><?php //echo JText::_('COM_USERS_ISIS') ?></h5>
		*/
		?>
	</div>	

</div>
</div>

<script type="text/javascript">
// tab form login & registration
jQuery(document).ready(function($){
            $('#page').fullpage({
				slidesNavigation: false,
				navigation: false,
				scrollOverflow: false,
            });


	//add class .form-control to input

});
jQuery('input.inputbox').addClass('form-control');
</script>