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
<div class="joms-page">
    <h3 class="joms-page__title">
        <?php echo JText::_('COM_COMMUNITY_CUSTOMIZE_MY_PAGE'); ?>
    </h3>

    <?php echo $submenu; ?>

    <div class="joms-body">

        <div class="joms-app__block core">
            <?php echo JText::_('COM_COMMUNITY_PROFILE'); ?>
        </div>

        <div class="joms-sidebar">

            <h4 class="joms-app__wrapper-title"><?php echo JText::_('COM_COMMUNITY_SIDEBAR_TOP'); ?></h4>
            <?php echo $appItems['sidebar-top-core']; ?>
            <div id="pos-profile-sidebar-top" class="app-position">
                <?php echo $appItems['sidebar-top']; ?>
            </div>
            <a href="javascript:" onclick="joms.api.appBrowse('sidebar-top');" class="joms-button--primary joms-button--full-small ">
                <span><?php echo JText::_('COM_COMMUNITY_ADD_APPLICATIONS'); ?></span>
            </a>


            <div class="joms-divider"></div>


            <h4 class="joms-app__wrapper-title"><?php echo JText::_('COM_COMMUNITY_SIDEBAR_BOTTOM'); ?></h4>
            <?php echo $appItems['sidebar-bottom-core'] ?>
            <div id="pos-profile-sidebar-bottom" class="app-position">
                <?php echo $appItems['sidebar-bottom']; ?>
            </div>
            <a href="javascript:" onclick="joms.api.appBrowse('sidebar-bottom');" class="joms-button--primary joms-button--full-small " ><?php echo JText::_('COM_COMMUNITY_ADD_APPLICATIONS'); ?>
            </a>

        </div>
        <div class="joms-main">

            <h4 class="joms-app__wrapper-title"><?php echo JText::_('COM_COMMUNITY_MAIN_CONTENT'); ?></h4>
            <div class="joms-app__block core"><?php echo JText::_('COM_COMMUNITY_ACTIVITY_STREAM'); ?></div>
            <div class="joms-app__block core"><?php echo JText::_('COM_COMMUNITY_ABOUT_ME'); ?></div>

            <?php echo $appItems['content-core']; ?>

            <div id="pos-profile-content" class="app-position">
                <?php echo $appItems['content']; ?>
            </div>

            <a href="javascript:" onclick="joms.api.appBrowse('content');" class="joms-button--primary joms-button--full-small "><?php echo JText::_('COM_COMMUNITY_ADD_APPLICATIONS'); ?></a>

        </div>
    </div>
</div>
