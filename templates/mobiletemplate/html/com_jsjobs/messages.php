<?php

/**
 * @Copyright Copyright (C) 2012 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:		Buruj Solutions
  + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	April 05, 2012
  ^
  + Project: 		JS Jobs
  ^
 */
defined('_JEXEC') or die('Restricted access');

class JSJobsMessages {

    function getSystemOfflineMsg($config) {
        $msg = '<div class="js_job_error_messages_wrapper">
                    <div class="js_job_messages_image_wrapper">
                        <img class="js_job_messages_image" src="components/com_jsjobs/images/7.png"/>
                    </div>
                    <div class="js_job_messages_data_wrapper">
                        <span class="js_job_messages_main_text">' . JText::_("JS Jobs is offline") . '</span>
                        <span class="js_job_messages_block_text">' . $config["offline_text"] . '</span>
                    </div>
                </div>';
        echo $msg;
    }

    function getAccessDeniedMsg($msgTitle, $msgLang, $isVisitor = 0) {
        $msg = '<div class="js_job_error_messages_wrapper">
                    <div class="js_job_messages_image_wrapper">
                        <img class="js_job_messages_image" src="components/com_jsjobs/images/2.png"/>
                    </div>
                    <div class="js_job_messages_data_wrapper">
                        <span class="js_job_messages_main_text">' . JText::_($msgTitle) . '</span>
                        <span class="js_job_messages_block_text">' . JText::_($msgLang) . '</span>
                    </div>';
        if ($isVisitor == 1) {
            $msg.= '<div class="js_job_messages_button_wrapper">
                        <a class="js_job_message_button" href="index.php?option=com_users&view=login" >' . JText::_("Login") . '</a>
                        <a class="js_job_message_button" href="index.php?option=com_jsjobs&c=common&view=common&layout=userregister&userrole=1&Itemid=<?php echo $itemid; ?>" >' . JText::_("Register") . '</a>
                    </div>';
        }

        $msg.= '</div>';
        echo $msg;
    }

    function getPackageExpireMsg($msgTitle, $msgLang, $link, $linktitle = 'Packages') {
        $msg = '<div class="js_job_error_messages_wrapper">
                    <div class="js_job_messages_image_wrapper">
                        <img class="js_job_messages_image" src="components/com_jsjobs/images/2.png"/>
                    </div>
                    <div class="js_job_messages_data_wrapper">
                        <span class="js_job_messages_main_text">'.JText::_($msgTitle).'</span>
                        <span class="js_job_messages_block_text">'.JText::_($msgLang).'</span>
                        <div class="js_job_messages_button_wrapper">
                            <a class="js_job_message_button" href="'.$link.'" >'.JText::_($linktitle).'</a>
                        </div>
                    </div>
                </div>';
        echo $msg;
    }

    function getUserNotSelectedMsg($msgTitle, $msgLang, $link) {
        $msg = '<div class="js_job_error_messages_wrapper">
                    <div class="js_job_messages_image_wrapper">
                        <img class="js_job_messages_image" src="components/com_jsjobs/images/1.png"/>
                    </div>
                    <div class="js_job_messages_data_wrapper">
                        <span class="js_job_messages_main_text">'.JText::_($msgTitle).'</span>
                        <span class="js_job_messages_block_text">'.JText::_($msgLang).'</span>
                        <div class="js_job_messages_button_wrapper">
                            <a class="js_job_message_button" href="'.$link.'" >'.JText::_('Please Select Your Role').'</a>
                        </div>
                    </div>
                </div>';
        echo $msg;
    }

}

?>