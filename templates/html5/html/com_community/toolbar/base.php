<?php
defined('_JEXEC') or die();
$viewName = JRequest::getCmd( 'view');
$taskName = JRequest::getCmd( 'task');

//require_once( JPATH_ROOT .'/components/com_community/libraries/core.php' );
//$svgPath = CFactory::getPath('template://assets/icon/joms-icon.svg');
//include_once $svgPath;
// call the auto refresh on specific page
?>
<?php //if ($menuParams != '' && $menuParams->get('show_page_heading') != 0) : ?>
<?
/*
<div class="page-header">
    <h3><?php echo $this->escape($menuParams->get('page_title')); ?></h3>
</div>
*/
?>
<?php //endif; ?>
<?php //if ($showToolbar) : ?>

<?php /*
<div class="joms-menu">

    <ul>
        <li><a href="<?php echo CRoute::_('index.php?option=com_community&view=frontpage'); ?>"><?php echo JText::_('COM_COMMUNITY_HOME'); ?></a></li>
        <?php foreach ($menus as $menu) { ?>
        <li>
            <a href="<?php echo CRoute::_($menu->item->link); ?>"><?php echo JText::_($menu->item->name); ?></a>
            <?php if ( !empty($menu->childs) ) { ?>
            <span class="joms-menu__toggle">
                <svg viewBox="0 0 16 16" class="joms-icon">
                    <use xlink:href="<?php echo CRoute::getURI(); ?>#joms-icon-arrow-down"></use>
                </svg>
            </span>
            <ul>
                <?php foreach ($menu->childs as $child) { ?>
                <li>
                    <?php if ($child->script) { ?>
                        <a href="javascript:" onclick="<?php echo $child->link; ?>">
                    <?php } else { ?>
                        <a href="<?php echo CRoute::_($child->link); ?>">
                    <?php } ?>
                    <?php echo JText::_($child->name); ?></a>
                </li>
                <?php } ?>
            </ul>
            <?php } ?>
        </li>
        <?php } ?>
    </ul>
</div>



<div class="joms-menu--user">
*/ ?>


<?php /*
    $document	= &JFactory::getDocument();
    $renderer	= $document->loadRenderer('modules');
    $options	= array('style' => 'xhtml');
    $position	= 'left';
    echo $renderer->render($position, $options, null);
	*/
?>
<?php /*

    <ul>
        <li><a href="<?php echo CRoute::_('index.php?option=com_community&view=profile'); ?>"><?php echo JText::_('COM_COMMUNITY_MY_PROFILE'); ?></a></li>
        <li><a href="<?php echo CRoute::_('index.php?option=com_community&view=profile&task=editPage'); ?>"><?php echo JText::_('COM_COMMUNITY_PROFILE_CUSTOMIZE_PAGE'); ?></a></li>
        <li><a href="<?php echo CRoute::_('index.php?option=com_community&view=profile&task=preferences'); ?>"><?php echo JText::_('COM_COMMUNITY_EDIT_PREFERENCES'); ?></a></li>
        <li><a href="<?php echo CRoute::_('index.php?option=' . COM_USER_NAME . '&task=' . COM_USER_TAKS_LOGOUT . '&' . JSession::getFormToken() . '=1&return=' . $logoutLink); ?>"><?php echo JText::_('COM_COMMUNITY_LOGOUT'); ?></a></li>
    </ul>
</div>
	*/
?>
<?php //endif; ?>

<?php //if (isset($miniheader) && $miniheader) { ?>
    <?php //echo $miniheader; ?>
<?php //} ?>

<?php //if ( !empty( $groupMiniHeader ) ) { ?>
    <?php //echo $groupMiniHeader; ?>
<?php //}; ?>

<?php
   // if(isset($eventMiniHeader) && $eventMiniHeader){
   //     echo $eventMiniHeader;
   // }
?>

