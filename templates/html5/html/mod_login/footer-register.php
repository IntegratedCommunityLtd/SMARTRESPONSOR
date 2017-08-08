<?php
/**
 * Кнопка Регистрация и кнопки Забивалки в подвале
 */

defined('_JEXEC') or die;

require_once JPATH_SITE . '/components/com_users/helpers/route.php';

//JHtml::_('behavior.keepalive');
//JHtml::_('bootstrap.tooltip');

?>

<form role="form" action="<?php echo JRoute::_(htmlspecialchars(JUri::getInstance()->toString()), true, $params->get('usesecure')); ?>" method="post" id="login-form" class="">
	<?php if ($params->get('pretext')) : ?>
	<div class="clear"></div>	
		<div class="pretext">
			<p><?php echo $params->get('pretext'); ?></p>
		</div>
	<?php endif; ?>
	<div class="clearfix"></div>
	<div class="btn-group">
		<?php
			$usersConfig = JComponentHelper::getParams('com_users'); ?>
			<?php if ($usersConfig->get('allowUserRegistration')) : ?>
					<a id="register-remind" href="<?php echo JRoute::_('index.php?option=com_users&view=registration&Itemid=' . UsersHelperRoute::getRegistrationRoute()); ?>" class="btn btn-primary btn-block">
			<?php echo JText::_('MOD_LOGIN_REGISTER'); ?></a>
			<?php endif; ?>
					<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind&Itemid=' . UsersHelperRoute::getRemindRoute()); ?>" class="btn btn-default btn-block">
			<?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_USERNAME'); ?></a>
					<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset&Itemid=' . UsersHelperRoute::getResetRoute()); ?>" class="btn btn-default btn-block">
			<?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_PASSWORD'); ?></a>
	</div>
	<?php if ($params->get('posttext')) : ?>
		<div class="posttext">
			<p><?php echo $params->get('posttext'); ?></p>
		</div>
	<?php endif; ?>
</form>
