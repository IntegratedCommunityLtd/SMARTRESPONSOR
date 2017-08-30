<?php
defined('_JEXEC') or die('');
?>
<?php
$usersConfig = JComponentHelper::getParams('com_users');
?>
<noindex>
<?php if ($type == 'logout') : ?>

<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form">

    <?php if (!empty($avatar)) : ?>
        <div class="slogin-avatar">
			<a href="<?php echo $profileLink; ?>" target="_blank">
				<img src="<?php echo $avatar; ?>" alt=""/>
			</a>
        </div>
    <?php endif; ?>

    <div class="login-greeting">
        <?php echo JText::sprintf('MOD_SLOGIN_HINAME', htmlspecialchars($user->get('name')));	 ?>
    </div>
		<ul class="ul-jlslogin">
			<?php	if ($params->get('slogin_link_auch_edit', 1) == 1) {?>
				<li><a href="<?php echo JRoute::_('index.php?option=com_users&view=edit'); ?>"><?php echo JText::_('MOD_SLOGIN_EDIT_YOUR_PROFILE'); ?></a></li>
			<?php }	?>
			<?php	if ($params->get('slogin_link_profile', 1) == 1) {?>
			<li><a href="<?php echo JRoute::_('index.php?option=com_slogin&view=fusion'); ?>"><?php echo JText::_('MOD_SLOGIN_EDIT_YOUR_SOCIAL_AUCH'); ?></a></li>
			<?php }	?>
		</ul>
    <div class="logout-button">
        <input type="submit" name="Submit" class="button signbtn" value="<?php echo JText::_('JLOGOUT'); ?>" />
        <input type="hidden" name="option" value="com_users" />
        <input type="hidden" name="task" value="user.logout" />
        <input type="hidden" name="return" value="<?php echo $return; ?>" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>
<?php else : ?>
<?php if ($params->get('inittext')): ?>

    <div class="pretext">
        <p><?php echo $params->get('inittext'); ?></p>
    </div>
    <?php endif; ?>



<div class="slogin-clear"></div>

    <?php if ($params->get('pretext')): ?>
    <div class="pretext">
        <p><?php echo $params->get('pretext'); ?></p>
    </div>
    <?php endif; ?>

<?php if ($params->get('show_login_form')): ?>
<form class="form-inline" role="form" action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form" >
		<div class="input-group">
			<div class="input-group-addon joms-icon-user-male l">
				</div>		
			<label class="sr-only" for="exampleInputEmail2"><?php echo JText::_('MOD_SLOGIN_VALUE_USERNAME') ?>	</label>
					<input id="modlgn-username" type="text" name="username" class="form-control"  size="7" placeholder="<?php echo JText::_('MOD_SLOGIN_VALUE_USERNAME') ?>"/>
		</div>

		<div class="input-group">
			<div class="input-group-addon joms-icon-uniE622 l">
				</div>
			<label class="sr-only" for="exampleInputPassword2"><?php echo JText::_('MOD_SLOGIN_VALUE_PASSWORD') ?></label>
					<input id="modlgn-passwd" type="password" name="password" class="form-control" size="7"  placeholder="<?php echo JText::_('MOD_SLOGIN_VALUE_PASSWORD') ?>"/>
		</div>
		
		<div class="input-group btn btn-primary sign">
		<div class="checkbox">
		    <label>
				<input id="modlgn-remember" type="checkbox" name="remember" class="" value="yes"/>
			</label>
		</div>
		<?php echo JText::_('MOD_SLOGIN_REMEMBER_ME') ?>
					<input type="submit" name="Submit" class="btn btn-primary" value="<?php echo JText::_('JLOGIN') ?>" />
					<input type="hidden" name="option" value="com_users" />
					<input type="hidden" name="task" class="input-group-addon" value="user.login" />
					<input type="hidden" name="return" value="<?php echo $return; ?>" />	
					<?php echo JHtml::_('form.token'); ?>
		</div>
</form>
<div class="helper">
	<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>"><?php echo JText::_('MOD_SLOGIN_FORGOT_YOUR_USERNAME'); ?></a>	
	<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>"><?php echo JText::_('MOD_SLOGIN_FORGOT_YOUR_PASSWORD'); ?></a>				
	<a href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>"><?php echo JText::_('MOD_SLOGIN_REGISTER'); ?></a>	
</div>


    <?php endif; ?>
<?php endif; ?>
</noindex>