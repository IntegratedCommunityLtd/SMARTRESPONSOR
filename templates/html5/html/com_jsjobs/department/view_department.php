<?php
/**
 * @Copyright Copyright (C) 2009-2011
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
  + Created by:          Ahmad Bilal
 * Company:		Buruj Solutions
  + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Jan 11, 2009
  ^
  + Project: 		JS Jobs
 * File Name:	views/application/tmpl/viewjob.php
  ^
 * Description: template view for a job
  ^
 * History:		NONE
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
    if (isset($this->department)) { ?>
        <div id="js_main_wrapper">
            <span class="js_controlpanel_section_title"><?php echo JText::_('Department Information'); ?></span>
            <div class="js_job_data_wrapper">
                <span class="js_job_data_title"><?php echo JText::_('Name'); ?></span>
                <span class="js_job_data_value"><?php echo $this->department->name; ?></span>
            </div>
            <div class="js_job_data_wrapper">
                <span class="js_job_data_title"><?php echo JText::_('Company'); ?></span>
                <span class="js_job_data_value">
                    <?php
                    $companyaliasid = ($this->isjobsharing != "") ? $this->department->scompanyaliasid : $this->department->companyaliasid;
                    $link = 'index.php?option=com_jsjobs&c=company&view=company&layout=view_company&nav=31&cd=' . $companyaliasid . '&Itemid=' . $this->Itemid;
                    ?>
                    <a href="<?php echo $link ?>"><?php echo $this->department->companyname; ?></a>
                </span>
            </div>
            <span class="js_controlpanel_section_title"><?php echo JText::_('Description'); ?></span>
            <div class="js_job_full_width_data"><?php echo $this->department->description; ?></div>
        </div>
    <?php } else { 
            $this->jsjobsmessages->getAccessDeniedMsg('Could not find any matching results.', 'Could not find any matching results.', 0);
        }
}//ol
?>

