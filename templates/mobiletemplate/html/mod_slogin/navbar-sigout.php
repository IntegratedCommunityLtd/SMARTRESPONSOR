<?php
defined('_JEXEC') or die('(@)|(@)');
?>

<?php if ($type == 'logout') : ?>


	<?php echo JText::sprintf('MOD_SLOGIN_HINAME', htmlspecialchars($user->get('name')));	 ?>


			<?php	if ($params->get('slogin_link_auch_edit', 1) == 1) {?>
				<li><a href="<?php echo JRoute::_('index.php?option=com_users&view=edit'); ?>"><?php echo JText::_('MOD_SLOGIN_EDIT_YOUR_PROFILE'); ?></a></li>
			<?php }	?>
			<?php	if ($params->get('slogin_link_profile', 1) == 1) {?>
			<li><a href="<?php echo JRoute::_('index.php?option=com_slogin&view=fusion'); ?>"><?php echo JText::_('MOD_SLOGIN_EDIT_YOUR_SOCIAL_AUCH'); ?></a></li>
			<?php }	?>

	<div class="logout-button">
		<input type="submit" name="Submit" class="button" value="<?php echo JText::_('JLOGOUT'); ?>" />
		<input type="hidden" name="option" value="com_users" />
		<input type="hidden" name="task" value="user.logout" />
		<input type="hidden" name="return" value="<?php echo $return; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>

<?php else : ?>
<?php if ($params->get('inittext')): ?>
	<div class="pretext">
	<p><?php echo $params->get('inittext'); ?></p>
	</div>
<?php endif; ?>
<div id="slogin-buttons" class="slogin-buttons <?php echo $moduleclass_sfx?>">

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
            <a  rel="nofollow" <?php echo $linkParams;?> href="<?php echo JRoute::_($link['link']);?>"><span class="<?php echo $link['class'];?>">&nbsp;</span></a>
        <?php endforeach; ?>
    <?php endif; ?>

</div>
<div class="slogin-clear"></div>
<?php if ($params->get('pretext')): ?>
	<div class="pretext">
	<p><?php echo $params->get('pretext'); ?></p>
	</div>
<?php endif; ?>
<?php if ($params->get('show_login_form')): ?>
<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form" >


	<?php if ($params->get('posttext')): ?>
		<div class="posttext">
		<p><?php echo $params->get('posttext'); ?></p>
		</div>
	<?php endif; ?>
</form>
<?php endif; ?>
<?php endif; ?>

<?php echo $jll; ?>

