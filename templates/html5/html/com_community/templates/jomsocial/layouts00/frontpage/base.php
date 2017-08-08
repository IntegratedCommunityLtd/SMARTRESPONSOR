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

<script type="text/javascript">joms.filters && joms.filters.bind();</script>

<?php
/**
 * if user logged in
 * 		load frontpage.members.php
 * else
 * 		load frontpage.guest.php
 */
echo $header;
?>

<div class="joms-body">

    <?php if ($moduleCount > 0) { ?>
    <div class="joms-sidebar">

        <div class="joms-module__wrapper"><?php $this->renderModules('js_side_top'); ?></div>
        <div class="joms-module__wrapper--stacked"><?php $this->renderModules('js_side_top_stacked'); ?></div>
        <div class="joms-module__wrapper"><?php $this->renderModules('js_side_bottom'); ?></div>
        <div class="joms-module__wrapper--stacked"><?php $this->renderModules('js_side_bottom_stacked'); ?></div>
        <div class="joms-module__wrapper"><?php $this->renderModules('js_side_frontpage_top'); ?></div>
        <div class="joms-module__wrapper--stacked"><?php $this->renderModules('js_side_frontpage_top_stacked'); ?></div>
        <div class="joms-module__wrapper"><?php $this->renderModules('js_side_frontpage'); ?></div>
        <div class="joms-module__wrapper--stacked"><?php $this->renderModules('js_side_frontpage_stacked'); ?></div>
        <div class="joms-module__wrapper"><?php $this->renderModules('js_side_frontpage_bottom'); ?></div>
        <div class="joms-module__wrapper--stacked"><?php $this->renderModules('js_side_frontpage_bottom_stacked'); ?></div>

    </div>
    <?php } ?>

    <div class="joms-main <?php echo $is_full = ($moduleCount == 0 ? 'joms-main--full' : ''); ?>">
        <?php if ($config->get('showactivitystream') == '1' || ($config->get('showactivitystream') == '2' && $my->id != 0 )) { ?>
            <?php ($my->id) ? $userstatus->render() : ''; ?>
            <!-- User logged than display filterbar -->
            <?php if ($alreadyLogin == 1) : ?>
                <div class="joms-activity-filter clearfix">
                    <div class="joms-activity-filter-action">

                        <a><svg viewBox="0 0 16 16" class="joms-icon">
                            <use xlink:href="<?php echo CRoute::getURI(); ?>#joms-icon-arrow-down"></use>
                        </svg>
                        <span class="joms-gap--inline-small"></span>
                        <?php echo JText::_("COM_COMMUNITY_FILTERBAR_FILTERBY"); ?></a>
                        <span class="joms-activity-filter-status" data-default="<?php echo JText::_("COM_COMMUNITY_FILTERBAR_ALL"); ?>"><?php echo $filterText; ?></span>
                    </div>
                    <form class="reset-gap">
                        <ul class="unstyled joms-activity-filter-dropdown joms-postbox-dropdown" style="display: none">
                            <li data-filter="all" class="<?php echo (isset($filter) && $filter == 'all') ? 'active' : ''; ?>" data-url="<?php echo CRoute::_('index.php?option=com_community&view=frontpage&filter=all'); ?>">
                                <svg viewBox="0 0 16 18" class="joms-icon">
                                    <use xlink:href="<?php echo CRoute::getURI(); ?>#joms-icon-star3"></use>
                                </svg>
                                <?php echo JText::_("COM_COMMUNITY_FILTERBAR_ALL"); ?>
                            </li>
                            <li data-filter="apps" data-value="profile" class="<?php echo (isset($filter) && $filter == 'apps' && $filterValue == "profile") ? 'active' : ''; ?>" data-url="<?php echo CRoute::_('index.php?option=com_community&view=frontpage&filter=apps&value=profile'); ?>">
                                <svg viewBox="0 0 16 18" class="joms-icon">
                                    <use xlink:href="<?php echo CRoute::getURI(); ?>#joms-icon-pencil"></use>
                                </svg>
                                <?php echo JText::_("COM_COMMUNITY_FILTERBAR_STATUS"); ?>
                            </li>
                            <?php if ($config->get('enablephotos')) { ?>
                                <li data-filter="apps" data-value="photo" class="<?php echo (isset($filter) && $filter == 'apps' && $filterValue == "photo") ? 'active' : ''; ?>" data-url="<?php echo CRoute::_('index.php?option=com_community&view=frontpage&filter=apps&value=photo'); ?>">
                                    <svg viewBox="0 0 16 18" class="joms-icon">
                                        <use xlink:href="<?php echo CRoute::getURI(); ?>#joms-icon-images"></use>
                                    </svg>
                                    <?php echo JText::_("COM_COMMUNITY_FILTERBAR_PHOTO"); ?>
                                </li>
                            <?php } ?>
                            <?php if ($config->get('enablevideos') == 1) { ?>
                                <li data-filter="apps" data-value="video" class="<?php echo (isset($filter) && $filter == 'apps' && $filterValue == "video") ? 'active' : ''; ?>" data-url="<?php echo CRoute::_('index.php?option=com_community&view=frontpage&filter=apps&value=video'); ?>">
                                    <svg viewBox="0 0 16 18" class="joms-icon">
                                        <use xlink:href="<?php echo CRoute::getURI(); ?>#joms-icon-camera2"></use>
                                    </svg>
                                    <?php echo JText::_("COM_COMMUNITY_FILTERBAR_VIDEO"); ?>
                                </li>
                            <?php } ?>
                            <?php if ($config->get('enablegroups') == 1) { ?>
                                <li data-filter="apps" data-value="group" class="<?php echo (isset($filter) && $filter == 'apps' && $filterValue == "group") ? 'active' : ''; ?>" data-url="<?php echo CRoute::_('index.php?option=com_community&view=frontpage&filter=apps&value=group'); ?>">
                                    <svg viewBox="0 0 16 18" class="joms-icon">
                                        <use xlink:href="<?php echo CRoute::getURI(); ?>#joms-icon-users"></use>
                                    </svg>
                                    <?php echo JText::_("COM_COMMUNITY_FILTERBAR_GROUP"); ?>
                                </li>
                            <?php } ?>
                            <?php if ($config->get('enableevents') == 1) { ?>
                                <li data-filter="apps" data-value="event" class="<?php echo (isset($filter) && $filter == 'apps' && $filterValue == "event") ? 'active' : ''; ?>" data-url="<?php echo CRoute::_('index.php?option=com_community&view=frontpage&filter=apps&value=event'); ?>">
                                    <svg viewBox="0 0 16 18" class="joms-icon">
                                        <use xlink:href="<?php echo CRoute::getURI(); ?>#joms-icon-calendar"></use>
                                    </svg>
                                    <?php echo JText::_("COM_COMMUNITY_FILTERBAR_EVENT"); ?>
                                </li>
                            <?php } ?>
                            <li data-filter="privacy" data-value="me-and-friends" class="<?php echo (isset($filter) && $filter == 'privacy' && $filterValue == "me-and-friends") ? 'active' : ''; ?>" data-url="<?php echo CRoute::_('index.php?option=com_community&view=frontpage&filter=privacy&value=me-and-friends'); ?>">
                                <svg viewBox="0 0 16 18" class="joms-icon">
                                    <use xlink:href="<?php echo CRoute::getURI(); ?>#joms-icon-user"></use>
                                </svg>
                                <?php echo JText::_("COM_COMMUNITY_FILTERBAR_MEANDFRIENDS"); ?>
                            </li>
                            <li data-filter="hashtag" data-value="__hashtag__" data-url="<?php echo CRoute::_('index.php?option=com_community&view=frontpage&filter=hashtag&value=__hashtag__'); ?>">
                                <input type="text" class="joms-input" placeholder="<?php echo JText::_("COM_COMMUNITY_ENTER_HASHTAG"); ?>" value="<?php echo $filterHashtag ? $filterValue : '' ?>" style="margin-bottom:6px">
                                <button class="joms-button--primary joms-button--full" style="margin-bottom:3px"><?php echo JText::_("COM_COMMUNITY_SEARCH_HASHTAG"); ?></button>
                            </li>
                        </ul>
                    </form>
                </div>
            <?php endif; ?>
             <div><?php echo $userActivities; ?></div>
        <?php } ?>

    </div>
</div>

<script>
    joms_filter_params = <?php echo json_encode(
        array(
            'filter'  => isset($filter) ? $filter : '',
            'value'   => isset($filterValue) ? $filterValue : '',
            'hashtag' => isset($filterHashtag) ? $filterHashtag : ''
        )
    ) ?>;
</script>

<?php if ($filterHashtag) { ?>
<script>joms_filter_hashtag = true;</script>
<?php } ?>
