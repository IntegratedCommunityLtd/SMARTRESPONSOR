<?php


defined('_JEXEC') or die();
?>

<?php
 $document = JFactory::getDocument();
 $is_rtl = ( $document->direction == 'rtl' ) ? 'dir="rtl"' : '';
?>

<div class="cModule cProfile-Friends app-box">
	<?php /*<h3 class="app-box-header"><?php // echo JText::_('COM_COMMUNITY_PROFILE_FRIENDS'); ?></h3>*/ ?>
	<div class="app-box-content">
		<?php
		if( $friends )
		{
		?>
		<ul class="cResetList cThumbsList clearfix">
			<?php
			for($i = 0; $i < count($friends); $i++) {
				$friend =& $friends[$i];
			?>
			<li>
				<a href="<?php echo CRoute::_('index.php?option=com_community&view=profile&userid='.$friend->id); ?>">
					<img alt="<?php echo $friend->getDisplayName();?>" title="<?php echo $friend->getTooltip(); ?>" src="<?php echo $friend->getThumbAvatar(); ?>" class="cAvatar jomNameTips" />
				</a>
			</li>
			<?php } ?>
		</ul>
		<?php
		}
		else
		{
		?>
			<div class="cEmpty"><?php echo JText::_('COM_COMMUNITY_NO_FRIENDS_YET');?></div>
		<?php
		}
		?>
	</div>
	<div class="app-box-footer">
		<a href="<?php echo CRoute::_('index.php?option=com_community&view=friends&userid=' . $user->id ); ?>">
			<span><?php echo JText::_('COM_COMMUNITY_FRIENDS_VIEW_ALL'); ?></span>
			<span <?php echo $is_rtl; ?> > (<?php echo $total;?>)</span>
		</a>
	</div>
</div>
