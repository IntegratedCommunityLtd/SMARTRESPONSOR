<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once JPATH_SITE . '/components/com_users/helpers/route.php';

JHtml::_('behavior.keepalive');
JHtml::_('bootstrap.tooltip');

?>
<form role="form" action="<?php echo JRoute::_(htmlspecialchars(JUri::getInstance()->toString()), true, $params->get('usesecure')); ?>" method="post" id="login-form" class="form-inline">
	<?php if ($params->get('pretext')) : ?>
		<div class="pretext">
			<p><?php echo $params->get('pretext'); ?></p>
		</div>
	<?php endif; ?>
	<div class="userdata">
		<div id="form-login-username" class="control-group">

				<?php if (!$params->get('usetext')) : ?>
					
					<div class="input-group">
						<span class="input-group-addon glyphicon glyphicon-user hasTooltip hidden-xs" title="<?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?>"></span>
						<input id="modlgn-username" type="text" name="username" class="form-control" tabindex="0" size="18" placeholder="<?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?>" />
					</div>
					
				<?php else: ?>
					<label for="modlgn-username"><?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?></label>
					<input id="modlgn-username" type="text" name="username" class="form-control" tabindex="0" size="18" placeholder="<?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?>" />
				<?php endif; ?>

		</div>
		<div id="form-login-password" class="control-group">

		<?php if (!$params->get('usetext')) : ?>

					<div class="input-group">
						<span class="input-group-addon glyphicon glyphicon-th hasTooltip hidden-xs" title="<?php echo JText::_('JGLOBAL_PASSWORD') ?>"></span>
						<input id="modlgn-passwd" type="password" name="password" class="form-control" tabindex="0" placeholder="<?php echo JText::_('JGLOBAL_PASSWORD') ?>" />
					</div>
					

				<?php else: ?>
				
					<label for="modlgn-passwd"><?php echo JText::_('JGLOBAL_PASSWORD') ?></label>
					<input id="modlgn-passwd" type="password" name="password" class="form-control" tabindex="0" placeholder="<?php echo JText::_('JGLOBAL_PASSWORD') ?>" />
				<?php endif; ?>
		</div>
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
					<div class="input-group">		
		<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
						<span class="btn btn-primary"><input id="modlgn-remember" type="checkbox" name="remember" class="" value="yes"/><?php echo JText::_('MOD_LOGIN_REMEMBER_ME') ?></span>
		<?php endif; ?>
						<button type="submit" tabindex="0" name="Submit" class="btn btn-primary"><?php echo JText::_('JLOGIN') ?></button>
					</div>
		<?php
			$usersConfig = JComponentHelper::getParams('com_users'); ?>

			<?php if ($usersConfig->get('allowUserRegistration')) : ?>

					<a href="<?php echo JRoute::_('index.php?option=com_users&view=registration&Itemid=' . UsersHelperRoute::getRegistrationRoute()); ?>" class="btn btn-warning">
					<?php echo JText::_('MOD_LOGIN_REGISTER'); ?> <span class="icon-arrow-right"></span></a>

			<?php endif; ?>

					<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind&Itemid=' . UsersHelperRoute::getRemindRoute()); ?>" class="btn btn-warning">
					<?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_USERNAME'); ?></a>


					<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset&Itemid=' . UsersHelperRoute::getResetRoute()); ?>" class="btn btn-warning">
					<?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_PASSWORD'); ?></a>


		<input type="hidden" name="option" value="com_users" />
		<input type="hidden" name="task" value="user.login" />
		<input type="hidden" name="return" value="<?php echo $return; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
	<?php if ($params->get('posttext')) : ?>
		<div class="posttext">
			<p><?php echo $params->get('posttext'); ?></p>
		</div>
	<?php endif; ?>
</form>
