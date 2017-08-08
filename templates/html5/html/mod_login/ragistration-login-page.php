<?php
defined('_JEXEC') or die;
$app = JFactory::getApplication();
//JResponse::setHeader('Content-Type', 'text/plain', TRUE);
?>
<script type="text/javascript" async src="//smartresponsor.com/js/jquery.fullPage.js"></script>
  	<form id="member-registration" role="form" action="<?php echo JRoute::_('index.php?option=com_ajax&option=com_users&task=registration.register'); ?>" method="post" class="form-validate" enctype="multipart/form-data" data-toggle="validator" style="display:none;">
		<div class="btn-group-vertical">
			<div class="form-group member-registration">
				<button id="btnformlogin" type="button" class="glyphicon glyphicon-user login" ></button>
				<span class="text-center">
						<h3><?php echo JText::_('COM_USERS_REGISTRATION_DEFAULT_LABEL') ?></h3> 
				</span>
					<?php 
					// Возврат модуля с Регистраций через ФБ
						$modules =JModuleHelper::getModules('facebook-login');
							foreach ($modules as $module){
								echo JModuleHelper::renderModule($module);
							}
					?>
				<span>&nbsp </span>
				<button id="btnformloginOne" type="button" class="btn btn-warning btn-xs"><?php echo JText::_('COM_USERS_SIGIN_AUTHORIZATION'); ?></button>					
				<fieldset>
					<input id="jform_name" type="text" name="jform[name]" value="" autocomplete="off" class="required form-control" tabindex="0" size="30" placeholder="<?php echo JText::_('MOD_LOGIN_VALUE_NAME') ?>" required="" aria-required="true" >				
					<input id="jform_username" type="text" name="jform[username]" value="" autocomplete="off" class="validate-username required form-control" tabindex="1" size="30" maxlength="99" aria-required="true" placeholder="<?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?>" />
					<input id="jform_password1" type="password" name="jform[password1]" value="" autocomplete="off" class="form-control validate-password required" tabindex="2" size="30" maxlength="99" value="" aria-required="true" placeholder="<?php echo JText::_('MOD_LOGIN_VALUE_PASSWORD') ?>" />
					<input id="jform_password2" type="password" name="jform[password2]" value="" autocomplete="off" class="form-control validate-password required" tabindex="3" size="30" maxlength="99" aria-required="true" placeholder="<?php echo JText::_('MOD_LOGIN_VALUE_PASSWORD_CONFIRM') ?>" />
					<input id="jform_email1" type="email" name="jform[email1]" value="" autocomplete="off" class="validate-email form-control required" tabindex="4" size="30" placeholder="<?php echo JText::_('MOD_LOGIN_VALUE_EMAIL') ?>" required="" aria-required="true" >
				</fieldset>
			</div>
				<input type="hidden" name="option" value="com_users" />
				<input type="hidden" name="task" value="registration.register" />
				<?php echo JHtml::_('form.token');?>			
					<button type="submit" class="btn btn-warning btn-lg submit-registration" tabindex="5"><?php echo JText::_('COM_USERS_REGISTRATION');?></button>
		</div>
	</form>
