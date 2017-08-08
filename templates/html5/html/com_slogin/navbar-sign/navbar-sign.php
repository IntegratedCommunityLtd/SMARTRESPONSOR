<?php
defined('_JEXEC') or die('');
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
	<?php if ($params->get('pretext')): ?>
		<div class="pretext">
		<p><?php echo $params->get('pretext'); ?></p>
		</div>
	<?php endif; ?>
	<fieldset class="userdata">
<div class="signform form-group">
	<div class="input-group signform">	
			<div class="input-group-addon joms-icon-user-male">
			</div>
		<input id="modlgn-username" type="text" name="username" class="input-group"  size="18" />
			<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
	<?php endif; ?>
	</div>
	<div class="input-group signform">
			<div class="input-group-addon joms-icon-uniE622">
			</div>
		<input id="modlgn-passwd" type="password" name="password" class="input-group" size="18"  />
	<label for="modlgn-remember" class="input-group-addon"><?php echo JText::_('MOD_SLOGIN_REMEMBER_ME') ?>
		<input id="modlgn-remember" type="checkbox" name="remember" class="input-group btn btn-primary" value="yes"/></label>
		<input type="submit" name="Submit" class="btn btn-primary" value="<?php echo JText::_('JLOGIN') ?>" />		
	</div>

	<input type="hidden" name="option" value="com_users" />
	<input type="hidden" name="task" class="btn btn-primary" value="user.login" />
	<input type="hidden" name="return" value="<?php echo $return; ?>" />	
</div>
	<?php echo JHtml::_('form.token'); ?>
	</fieldset>
	
	
	<ul class="list-inline forgotli">
		<li class="forgotli">
			<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
			<?php echo JText::_('MOD_SLOGIN_FORGOT_YOUR_PASSWORD'); ?></a>
					</li>
		<li class="forgotli">
			<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
			<?php echo JText::_('MOD_SLOGIN_FORGOT_YOUR_USERNAME'); ?></a>
		</li>
		<?php
		$usersConfig = JComponentHelper::getParams('com_users');
		if ($usersConfig->get('allowUserRegistration')) : ?>
		<li class="forgotli">
			<a href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>">
				<?php echo JText::_('MOD_SLOGIN_REGISTER'); ?></a>
		</li>
		<?php endif; ?>

<li id="slogin-buttons" class="slogin-buttons <?php // echo $moduleclass_sfx?>">
    <?php if (count($plugins)): ?>
        <?php
        foreach($plugins as $link):
            $linkParams = '';
            if(isset($link['params'])){
                foreach($link['params'] as $k => $v){
                    $linkParams .= ' ' . $k . '="' . $v . '"';
                }
            }
            ?>
            <a  rel="nofollow" <?php echo $linkParams;?> href="<?php echo JRoute::_($link['link']);?>"><span class="<?php echo $link['class'];?>"></span></a>
        <?php endforeach; ?>
    <?php endif; ?>
</li>

	</ul>
	<?php if ($params->get('posttext')): ?>
		<div class="posttext">
		<p><?php echo $params->get('posttext'); ?></p>
		</div>
	<?php endif; ?>
	
</form>




    <?php endif; ?>
<?php endif; ?>
</noindex>