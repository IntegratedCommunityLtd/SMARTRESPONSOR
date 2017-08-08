<?php
defined('_JEXEC') or die();
$user		= JFactory::getUser();
$viewName	= JRequest::getCmd( 'view');
$taskName	= JRequest::getCmd( 'task');
// call the auto refresh on specific page

if ($user->guest) {						// если пользователь гость
?>
<div class="page-header">
	<h3><?php echo $this->escape($menuParams->get('page_title')); ?></h3>
</div>
<?php
} else {
?>
<?php
/*
<?php if ($menuParams != '' && $menuParams->get('show_page_heading') != 0) : ?>


<?php endif;?>
<?php if($showToolbar) : ?>
*/
?>

<div class="navbar js-toolbar">
  <div class="navbar-inner">
      <?php /* <a class="btn btn-navbar js-bar-collapse-btn">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a> */ ?>
      	<ul class="nav">
		        <li class="active">
					<a href="#" onclick="history.back();"><i class="joms-icon-uniE62D"></i></a>
				</li>
        	<li <?php echo $active == 0 ? ' class="active"' :'';?> ><a href="<?php echo CRoute::_( 'index.php?option=com_community&view=frontpage' );?>">
        		<i class="joms-icon-home"></i></a>
					</li>
					<li>
						<a class="joms-toolbar-global-notif" href="javascript:joms.notifications.showWindow();" title="<?php echo JText::_( 'COM_COMMUNITY_NOTIFICATIONS_GLOBAL' );?>">
							<i class="joms-icon-globe"></i>
							<?php if( $newEventInviteCount ) { ?>
							<span class="js-counter joms-rounded"><?php echo $newEventInviteCount; ?></span>
							<?php } ?>
						</a>
					</li>

					<li>
						<a class="joms-toolbar-friend-invite-notif" href="<?php echo CRoute::_( 'index.php?option=com_community&view=friends&task=pending' );?>" onclick="joms.notifications.showRequest();return false;" title="<?php echo JText::_( 'COM_COMMUNITY_NOTIFICATIONS_INVITE_FRIENDS' );?>">
							<i class="joms-icon-user"></i>
							<?php if( $newFriendInviteCount ){ ?><span class="js-counter joms-rounded"><?php echo $newFriendInviteCount; ?></span><?php } ?>
						</a>
					</li>
					<?php if($isMessageEnable) {?>
						<li>
							<a class="joms-toolbar-new-message-notif" href="<?php echo CRoute::_( 'index.php?option=com_community&view=inbox' );?>"  onclick="joms.notifications.showInbox();return false;" title="<?php echo JText::_( 'COM_COMMUNITY_NOTIFICATIONS_INBOX' );?>">
								<i class="joms-icon-envelope-alt"></i>
								<?php if( $newMessageCount ){ ?><span class="js-counter joms-rounded"><?php echo $newMessageCount; ?></span><?php } ?>
							</a>
						</li>
					<?php }?>
					<?php /* ИКОНКА ВЫХОД Я ЗАКОМЕНТИЛ
					<li>
						<a href="javascript:void(0);" title="<?php echo JText::_('COM_COMMUNITY_LOGOUT'); ?>" onclick="document.communitylogout2.submit();">
							<i class="joms-icon-exit"></i>
						</a>
					<form class="cForm" action="<?php echo JRoute::_('index.php');?>" method="post" name="communitylogout2" id="communitylogout2">
						<input type="hidden" name="option" value="<?php echo COM_USER_NAME ; ?>" />
						<input type="hidden" name="task" value="<?php echo COM_USER_TAKS_LOGOUT ; ?>" />
						<input type="hidden" name="return" value="<?php echo $logoutLink; ?>" />
						<?php echo JHtml::_('form.token'); ?>
					</form>
					</li>
					*/ ?>
				</ul>

  </div>
</div>
<?php
}
?>
<?php //endif; ?>

<?php //if ( $miniheader ) : ?>
	<?php //echo @$miniheader; ?>
<?php //endif; ?>

<?php //if ( !empty( $groupMiniHeader ) ) : ?>
	<?php //echo $groupMiniHeader; ?>
<?php //endif; ?>