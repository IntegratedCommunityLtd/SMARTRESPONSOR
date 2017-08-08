<?php
/**
 * @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
 * @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author iJoomla.com <webmaster@ijoomla.com>
 * @url https://www.jomsocial.com/license-agreement
 * The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
 * More info at https://www.jomsocial.com/license-agreement
 */
defined('_JEXEC') or die();
?>


<div class="joms-hcard">
    <div class="joms-avatar">
        <a href="<?php echo CUrlHelper::userLink($user->id); ?>">
            <img src="<?php echo $user->getAvatar(); ?>" alt="<?php echo $user->getDisplayName(); ?>">
        </a>
    </div>
    <div class="joms-gap"></div>
    <h4 class="joms-text--username joms-text--bold"><?php echo $user->name; ?></h4>
    <span class="joms-text--light"><?php echo (CFriendsHelper::getTotalMutualFriends($user->id)) ? JText::sprintf('COM_COMMUNITY_TOTAL_MUTUAL_FRIENDS',CFriendsHelper::getTotalMutualFriends($user->id)) : JText::_('COM_COMMUNITY_NO_MUTUAL_FRIENDS'); ?></span>
    <div class="joms-gap"></div>
    <a href="" class="joms-button--neutral joms-button--small">See Profile</a>
</div>
