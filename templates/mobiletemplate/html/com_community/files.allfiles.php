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

<script type="text/javascript">
joms.jQuery(document).ready(function() {
    joms.jQuery('.cTabNavContainer').each(function() {
        var $this = joms.jQuery( this ),
            $container = $this.children('.cTabContainer');

        $this.find('ul.cTabNav li').on( 'click', function( e ) {
            var id;
            e.preventDefault();
            id = joms.jQuery( this ).find('a').attr('href');
            $container.children( id ).show().siblings().hide();

        }).eq( 0 ).click();
    });
});
</script>

<div class="fsearchWrap">
    <input type="text" onkeyup="joms.file.loadFile(this.value,'<?php echo $gid;?>',0,8,'<?php echo $type?>');" value="" placeholder="<?php echo JText::_('COM_COMMUNITY_FILES_SEARCH_FILES_PLACEHOLDER');?>" name="friendsearch" id="friend-search-filter">
</div>

<div class="cTabNavContainer cGroups-FilesListing" style="height:auto">

    <ul class="cTabNav">
        <li id="ctab-selected" onclick="joms.file.getFileList('mostdownload',<?php echo $gid?>,0,8,'<?php echo $type ?>');"><a href="#most-download"><?php echo JText::_('COM_COMMUNITY_FILES_MOST_DOWNLOADED');?></a></li>
        <li id="ctab-selected" onclick="joms.file.getFileList('document',<?php echo $gid?>,0,8,'<?php echo $type ?>');"><a href="#files-document"><?php echo JText::_('COM_COMMUNITY_FILES_DOCUMENT');?></a></li>
        <li id="ctab-selected" onclick="joms.file.getFileList('archive',<?php echo $gid?>,0,8,'<?php echo $type ?>');"><a href="#files-archive"><?php echo JText::_('COM_COMMUNITY_FILES_ARCHIVE');?></a></li>
        <li id="ctab-selected" onclick="joms.file.getFileList('images',<?php echo $gid?>,0,8,'<?php echo $type ?>');"><a href="#files-images"><?php echo JText::_('COM_COMMUNITY_FILES_IMAGES');?></a></li>
        <li id="ctab-selected" onclick="joms.file.getFileList('multimedia',<?php echo $gid?>,0,8,'<?php echo $type ?>');"><a href="#files-multimedia"><?php echo JText::_('COM_COMMUNITY_FILES_AUDIO_VIDEO');?></a></li>
        <li id="ctab-selected" onclick="joms.file.getFileList('miscellaneous',<?php echo $gid?>,0,8,'<?php echo $type ?>');"><a href="#files-miscellaneous"><?php echo JText::_('COM_COMMUNITY_FILES_OTHER');?></a></li>
    </ul>

    <div class="cTabContainer">
        <div id="most-download" class="cTab clrfix" style="height:auto">
            <ul id="most-download-list"></ul>
        </div>
        <div id="files-document" class="cTab clrfix" style="height:auto">
            <ul id="files-document-list"></ul>
        </div>
        <div id="files-archive" class="cTab clrfix" style="height:auto">
            <ul id="files-archive-list"></ul>
        </div>
        <div id="files-images" class="cTab clrfix" style="height:auto">
            <ul id="files-images-list"></ul>
        </div>
        <div id="files-multimedia" class="cTab clrfix" style="height:auto">
            <ul id="files-multimedia-list"></ul>
        </div>
        <div id="files-miscellaneous" class="cTab clrfix" style="height:auto">
            <ul id="files-miscellaneous-list"></ul>
        </div>
    </div>

    <div id="load-more-btn" class="load-more-btn"></div>

</div>