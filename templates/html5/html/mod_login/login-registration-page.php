<?php
defined('_JEXEC') or die;
require_once JPATH_SITE . '/components/com_users/helpers/route.php';
JHtml::_('behavior.keepalive');
JHtml::_('bootstrap.tooltip');
$usersConfig = JComponentHelper::getParams('com_users');
?>
<script type="text/javascript" async src="/js/jquery.fullPage.js"></script>
<link rel="stylesheet" type="text/css" defer href="/css/checkbox-style.css" />
<form role="form" action="<?php echo JRoute::_(htmlspecialchars(JUri::getInstance()->toString()), true, $params->get('usesecure')); ?>" method="post" id="member-login" class="form-validate" style="display:none;">
	<div class="btn-group-vertical">
		<div class="form-group member-login">
			<button id="btnformreg" type="button" class="glyphicon glyphicon-user registration"></button>
			<span class="text-center">
				<h3><?php echo JText::_('MOD_LOGIN') ?></h3>
			</span>
					<input id="modlgn-username" type="text" name="username" class="form-control" tabindex="0" size="30" placeholder="<?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?>" />
					<input id="modlgn-passwd" type="password" name="password" class="form-control" tabindex="0" size="30" placeholder="<?php echo JText::_('MOD_LOGIN_VALUE_PASSWORD') ?>" />
				</fieldset>
	<?php if (count($twofactormethods) > 1): ?>
	<div id="form-login-secretkey" class="control-group">

			<?php if (!$params->get('usetext')) : ?>
			
				<div class="input-group">
					<span class="input-group-addon glyphicon glyphicon-th icon-star hasTooltip" title="<?php echo JText::_('JGLOBAL_SECRETKEY'); ?>"></span>				
					<input id="modlgn-secretkey" autocomplete="off" type="text" name="secretkey" class="input-small" tabindex="0" placeholder="<?php echo JText::_('JGLOBAL_SECRETKEY') ?>" />
					<input id="modlgn-secretkey" autocomplete="off" type="text" name="secretkey" class="form-control" tabindex="0" placeholder="<?php echo JText::_('JGLOBAL_SECRETKEY') ?>" />
					<span class="btn width-auto hasTooltip" title="<?php echo JText::_('JGLOBAL_SECRETKEY_HELP'); ?>"><span class="icon-help"></span></span>						
				</div>
				
			<?php else: ?>
				<label for="modlgn-secretkey"><?php echo JText::_('JGLOBAL_SECRETKEY') ?></label>
				<input id="modlgn-secretkey" autocomplete="off" type="text" name="secretkey" class="form-control" tabindex="0" size="18" placeholder="<?php echo JText::_('JGLOBAL_SECRETKEY') ?>" />
				<span class="btn width-auto hasTooltip" title="<?php echo JText::_('JGLOBAL_SECRETKEY_HELP'); ?>">
					<span class="icon-help"></span>
				</span>
			<?php endif; ?>

	</div>
	<?php endif; ?>
				<input type="checkbox" class="checkbox" id="checkbox" name="remember" value="yes" />
				<label for="checkbox" class="remember"><h4><?php echo JText::_('MOD_LOGIN_REMEMBER_ME') ?></h4></label></br>
			<div>
				<a href="<?php echo JRoute::_('index.php?option=com_ajax&option=com_users&view=remind&Itemid=' . UsersHelperRoute::getRemindRoute()); ?>" class="btn btn-link reset"><?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_USERNAME'); ?></a><span> &nbsp </span>
				<a href="<?php echo JRoute::_('index.php?option=com_ajax&option=com_users&view=reset&Itemid=' . UsersHelperRoute::getResetRoute()); ?>" class="btn btn-link reset"><?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_PASSWORD'); ?></a>
			</div>				
			<input type="hidden" name="option" value="com_users" />
			<input type="hidden" name="task" value="user.login" />
			<input type="hidden" name="return" value="<?php echo $return; ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
				<button type="submit" tabindex="0" name="Submit" class="btn btn-warning btn-lg submit-login"><?php echo JText::_('JLOGIN') ?></button>
	</div>
</form>
