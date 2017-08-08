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

$user = CFactory::getUser();
?>

<div class="joms-page joms-page--chat">
    <div class="joms-chat__wrapper">

        <!-- Sidebar -->
        <div class="joms-chat__conversations-wrapper">
            <div class="joms-chat__search">
                <input class="joms-input" type="text" placeholder="<?php echo JText::_('COM_COMMUNITY_CHAT_SEARCH'); ?>" />
            </div>
            <div class="joms-chat__conversations">
                <div class="joms-js-loading" style="padding:14px; text-align:center">
                    <img src="<?php echo JURI::root(true) ?>/components/com_community/assets/ajax-loader.gif" alt="loader" />
                </div>
                <div class="joms-js-notice" style="padding:14px; display:none"><?php echo JText::_('COM_COMMUNITY_CHAT_NOT_LOGIN'); ?></div>
                <div class="joms-js-list" style="display:none"></div>
            </div>
        </div>

        <!-- Wrapping one conversation -->
        <div class="joms-chat__messages-wrapper">
            <div class="joms-js--chat-header">
                <div class="joms-js--chat-header-info">
                    <div class="joms-chat__header">
                        <div class="joms-chat__recipents"></div>
                        <div class="joms-chat__actions">
                            <a href="#" class="joms-button--neutral joms-button--small">
                                <?php echo JText::_('COM_COMMUNITY_CHAT_NEW_MESSAGE'); ?>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="joms-js--chat-header-selector" style="display:none">
                    <div class="joms-chat__search">
                        <input class="joms-input" type="text" placeholder="<?php echo JText::_('COM_COMMUNITY_CHAT_SELECT_FRIEND'); ?>" />
                        <div style="position:relative">
                            <div class="joms-js--chat-header-selector-div" style="background:white;border:1px solid rgba(0,0,0,.2);border-top:0 none;left:0;padding:5px;position:absolute;right:0;top:0px;z-index:1">
                                <img src="<?php echo JURI::root(true) ?>/components/com_community/assets/ajax-loader.gif" alt="loader" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="joms-chat__messages" style="height:300px">
                <div class="joms-js--chat-conversation-loading" style="padding:6px;text-align:center;display:none">
                    <img src="<?php echo JURI::root(true) ?>/components/com_community/assets/ajax-loader.gif" alt="loader" />
                </div>
                <div class="joms-js--chat-conversation-messages" style="height:300px;overflow:auto;display:none"></div>
            </div>

            <div class="joms-chat__messagebox">
                <textarea rows="2" class="joms-textarea" disabled="disabled"></textarea>
            </div>
        </div>
        <!-- //conversation -->
    </div>
</div>

<!--
**** WINDOW CHAT
**** This is a popup chat which can be used on multiple pages, fixed to bottom.
****
-->
<div class="joms-chat__wrapper joms-chat--window">
  <div class="joms-chat__windows clearfix">
    <!-- Message window wrapper -->
    <div class="joms-chat__window" style="display:none">
      <div class="joms-chat__window-title">
        <span class="joms-chat__status"></span>
        Username
        <a href="#" class="joms-chat__window-close">
          <svg viewBox="0 0 16 16" class="joms-icon">
            <use xlink:href="<?php echo JUri::getInstance(); ?>#joms-icon-close"></use>
          </svg>
        </a>
      </div>

      <div class="joms-chat__window-body">
        <!-- Message wrapper -->
        <div class="joms-chat__message">
          <div class="joms-chat__message-avatar">
            <img src="" alt="">
          </div>

          <div class="joms-chat__message-bubble">
            <p>Message</p>
          </div>

          <div class="joms-chat__message-media">
            <img src="" alt="">
          </div>
        </div>
        <!-- //message -->
      </div>

      <div class="joms-chat__input-wrapper">
        <input type="text">

        <div class="joms-chat__input-actions">
          <a href="#">
            <svg viewBox="0 0 16 16" class="joms-icon">
              <use xlink:href="<?php echo JUri::getInstance(); ?>#joms-icon-camera"></use>
            </svg>
          </a>
        </div>
      </div>
    </div>
    <!-- //window -->
  </div>

  <div class="joms-chat__sidebar"></div>
</div>
<!-- //popup chat -->
