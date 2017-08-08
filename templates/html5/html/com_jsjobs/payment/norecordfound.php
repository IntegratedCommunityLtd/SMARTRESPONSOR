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
?>

    <div id="js_menu_wrapper">
        <?php
        if (sizeof($this->jobseekerlinks) != 0) {
            foreach ($this->jobseekerlinks as $lnk) {
                ?>                     
                <a class="js_menu_link <?php if ($lnk[2] == 'coverletter') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
                <?php
            }
        }
        if (sizeof($this->employerlinks) != 0) {
            foreach ($this->employerlinks as $lnk) {
                ?>
                <a class="js_menu_link <?php if ($lnk[2] == 'coverletter') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
                <?php
            }
        }
        ?>
    </div>
<?php 
if ($this->config['offline'] == '1') {
    $this->jsjobsmessages->getSystemOfflineMsg($this->config);
} else {
    ?>
    <div id="js_main_wrapper">
        <span class="js_controlpanel_section_title"><?php echo JText::_('No Record Found'); ?></span>
        <div id="jsjobs_norecordfoundwrapper">
            <div id="jsjobs_norecordfoundupperdiv">
                <span id="jsjobs_norecordfoundtextimage">
                    <?php echo JText::_('Error'); ?>
                </span>
            </div>
            <div id="jsjobs_norecordfoundlowerdiv">
                <?php echo JText::_('No Record Found For Your Request'); ?>
            </div>
        </div>
    </div>
        <?php
    }//ol
    ?>