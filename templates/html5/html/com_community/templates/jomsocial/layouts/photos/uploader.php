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

//
$isAlbumExist = count($allAlbums) >= 1;

?>

<?php if ($disableUpload) { ?>

<div class="joms-popup__content joms-popup__content--single">
    <?php echo $preMessage; ?>
</div>

<?php } else { ?>

<div class="joms-popup__content">
    <div>
        <div class="joms-tab__bar">
            <a href="#joms-js__new-album"<?php echo $isAlbumExist && $selectedAlbum ? '' : ' class="active"' ?>><?php echo JText::_('COM_COMMUNITY_PHOTOS_CREATE_NEW_ALBUM_TITLE'); ?></a>
            <a href="<?php echo $isAlbumExist ? '#joms-js__select-album' : 'javascript:" onclick="alert(\'' . JText::_('COM_COMMUNITY_PHOTOS_NO_ALBUM_CREATED') . '\');' ?>"<?php echo $isAlbumExist && $selectedAlbum ? ' class="active"' : '' ?>><?php echo JText::_('COM_COMMUNITY_PHOTOS_ADD_TO_EXISTING_ALBUM'); ?></a>
        </div>
        <div class="joms-tab__content<?php echo $isAlbumExist && $selectedAlbum ? ' joms-popup__hide' : '' ?>" id="joms-js__new-album">
            <input type="text" class="joms-input" name="album-name" placeholder="<?php echo JText::_('COM_COMMUNITY_PHOTOS_NEW'); ?> <?php echo JText::_('COM_COMMUNITY_PHOTOS_ALBUM_NAME'); ?>">
            <?php if (intval($groupId) === 0) { ?>
            <div><small><?php echo $preMessage; ?></small></div>
            <?php } ?>
        </div>
        <div class="joms-tab__content<?php echo $isAlbumExist && $selectedAlbum ? '' : ' joms-popup__hide' ?>" id="joms-js__select-album">
            <select class="joms-select" name="album-id">
                <?php foreach ($allAlbums as $index => $album) { ?>
                    <option value="<?php echo $album->id; ?>"<?php echo $album->id == $selectedAlbum ? ' selected="selected"' : '' ?>><?php echo trim($album->name); ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
</div>

<div class="joms-popup__content">
    <div class="joms-gallery" style="min-height:150px"></div>
</div>

<div class="joms-popup__action">
    <button class="joms-button--primary joms-js--btn-add" data-lang-more="<?php echo JText::_('COM_COMMUNITY_PHOTOS_ADD_MORE_FILES') ?>"><?php echo JText::_('COM_COMMUNITY_PHOTOS_MULTIUPLOAD_ADD_FILES'); ?></button>
    <button class="joms-button--primary joms-js--btn-view" style="display:none"><?php echo JText::_("COM_COMMUNITY_UPLOAD_VIEW_ALBUM"); ?></button>
</div>

<?php } ?>
