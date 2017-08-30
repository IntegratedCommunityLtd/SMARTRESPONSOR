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
 * File Name:	views/employer/tmpl/mycompanies.php
  ^
 * Description: template view for my companies
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
        if ($this->mystats_allowed == VALIDATE) { // employer
            $isodd = 0;
            ?>
            <div id="js_main_wrapper">
                <span class="js_controlpanel_section_title"><?php echo JText::_('Stats'); ?></span>
                <span class="js_job_title"><?php echo JText::_('My Stats'); ?></span>
                <div class="js_listing_wrapper">
                    <span class="stats_data_title"><?php echo JText::_('Companies'); ?></span>
                    <span class="stats_data_value"><?php echo $this->totalcompanies; ?></span>
                    <span class="stats_data_title"><?php echo JText::_('Jobs'); ?></span>
                    <span class="stats_data_value"><?php echo $this->totaljobs; ?></span>
                </div>            
                <span class="js_job_title"><?php echo JText::_('Jobs'); ?></span>
                <div class="js_listing_wrapper">
                    <span class="stats_data_title"><?php echo JText::_('Jobs Allow'); ?></span>
                    <span class="stats_data_value">
                        <?php
                        if ($this->ispackagerequired != 1) {
                            echo JText::_('Unlimited');
                        } elseif ($this->jobsallow == -1) {
                            echo JText::_('Unlimited');
                        } else
                            echo $this->jobsallow;
                        ?>
                    </span>
                    <span class="stats_data_title"><?php echo JText::_('Published Jobs'); ?></span>
                    <span class="stats_data_value">
                        <?php echo $this->publishedjob; ?>
                    </span>
                    <span class="stats_data_title"><?php echo JText::_('Expired Jobs'); ?></span>
                    <span class="stats_data_value">
                        <?php echo $this->expiredjob; ?>
                    </span>
                    <span class="stats_data_title"><?php echo JText::_('Available Jobs'); ?></span>
                    <span class="stats_data_value">
                        <?php
                        if ($this->ispackagerequired != 1) {
                            echo JText::_('Unlimited');
                        } elseif ($this->jobsallow == -1) {
                            echo JText::_('Unlimited');
                        } else {
                            $available_jobs = $this->jobsallow - $this->totaljobs;
                            echo $available_jobs;
                        }
                        ?>
                    </span>
                </div>            
                <span class="js_job_title"><?php echo JText::_('Companies'); ?></span>
                <div class="js_listing_wrapper">
                    <span class="stats_data_title"><?php echo JText::_('Companies Allow'); ?></span>
                    <span class="stats_data_value">
                        <?php
                        if ($this->ispackagerequired != 1) {
                            echo JText::_('Unlimited');
                        } elseif ($this->companiesallow == -1) {
                            echo JText::_('Unlimited');
                        } else
                            echo $this->companiesallow;
                        ?>
                    </span>
                    <span class="stats_data_title"><?php echo JText::_('Published Companies'); ?></span>
                    <span class="stats_data_value"><?php echo $this->totalcompanies; ?></span>				
                    <span class="stats_data_title"><?php echo JText::_('Expired Companies'); ?></span>
                    <span class="stats_data_value">
                        <?php echo '0'; ?>
                    </span>
                    <span class="stats_data_title"><?php echo JText::_('Available Companies'); ?></span>
                    <span class="stats_data_value">
                        <?php
                        if ($this->ispackagerequired != 1) {
                            echo JText::_('Unlimited');
                        } elseif ($this->companiesallow == -1) {
                            echo JText::_('Unlimited');
                        } else {
                            $available_companies = $this->companiesallow - $this->totalcompanies;
                            echo $available_companies;
                        }
                        ?>
                    </span>				
                </div>
                <?php
                if ($this->ispackagerequired != 1) {
                    $message = "<strong>" . JText::_('Package Not Required') . "</strong>";
                    ?>
                    <div id="stats_package_message">
                        <?php echo $message; ?>
                    </div>

                    <?php
                }
            } else { // not allowed job posting
                switch ($this->mystats_allowed) {
                    case JOBSEEKER_NOT_ALLOWED_EMPLOYER_PRIVATE_AREA:
                        $this->jsjobsmessages->getAccessDeniedMsg('Job Seeker Not Allow', 'Job seeker is not allow in employer private area', 0);
                        break;
                    case USER_ROLE_NOT_SELECTED:
                        $link = "index.php?option=com_jsjobs&c=common&view=common&layout=new_injsjobs&Itemid=".$this->Itemid;
                        $this->jsjobsmessages->getUserNotSelectedMsg('User Role Not Selected', 'User Role Is Not Selected, Please Select Your Role', $link);
                        break;
                    case VISITOR_NOT_ALLOWED_EMPLOYER_PRIVATE_AREA:
                        $this->jsjobsmessages->getAccessDeniedMsg('Visitor Not Allow', 'Visitor is not allow in employer private area', 1);
                    break;
                }
            }
        }//ol
        ?>	

