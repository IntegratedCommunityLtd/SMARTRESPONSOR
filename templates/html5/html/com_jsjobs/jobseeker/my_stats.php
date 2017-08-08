<?php
/**
 * @Copyright Copyright (C) 2009-2011
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
  + Created by:          Ahmad Bilal
 * Company:		Buruj Solutions
  + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Oct 10, 2010
  ^
  + Project: 		JS Jobs
 * File Name:	views/employer/tmpl/my_stats.php
  ^
 * Description: template view for my stats
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
        if ($this->mystats_allowed == VALIDATE) {
            $isodd = 1;
            $print = 1;
            if (isset($this->package) && $this->package == false)
                $print = 0;
            ?>
            <div id="js_main_wrapper">
                <span class="js_controlpanel_section_title"><?php echo JText::_('Stats'); ?></span>
                <span class="js_job_title"><?php echo JText::_('My Stats'); ?></span>
                <div class="js_listing_wrapper">
                    <span class="stats_data_title"><?php echo JText::_('Resumes'); ?></span>
                    <span class="stats_data_value"><?php echo $this->totalresume; ?></span>
                    <span class="stats_data_title last-child"><?php echo JText::_('Cover Letters'); ?></span>
                    <span class="stats_data_value last-child"><?php echo $this->totalcoverletters; ?></span>
                </div>
                <span class="js_job_title"><?php echo JText::_('Package History'); ?></span>
                <div class="js_listing_wrapper">
                    <span class="stats_data_title"><?php echo JText::_('Resume Allow'); ?></span>
                    <span class="stats_data_value">
                        <?php
                        if ($this->ispackagerequired != 1) {
                            echo JText::_('Unlimited');
                        } elseif ($this->resumeallow == -1) {
                            echo JText::_('Unlimited');
                        } else
                            echo $this->resumeallow;
                        ?>
                    </span>
                    <span class="stats_data_title"><?php echo JText::_('Cover Letter Allow'); ?></span>
                    <span class="stats_data_value">
                        <?php
                        if ($this->ispackagerequired != 1) {
                            echo JText::_('Unlimited');
                        } elseif ($this->coverlettersallow == -1) {
                            echo JText::_('Unlimited');
                        } else
                            echo $this->coverlettersallow;
                        ?>
                    </span>
                    <span class="stats_data_title"><?php echo JText::_('Available Resume'); ?></span>
                    <span class="stats_data_value">
                        <?php
                        if ($this->ispackagerequired != 1) {
                            echo JText::_('Unlimited');
                        } elseif ($this->resumeallow == -1) {
                            echo JText::_('Unlimited');
                        } else {
                            $available_resume = $this->resumeallow - $this->totalresume;
                            echo $available_resume;
                        }
                        ?>
                    </span>
                    <span class="stats_data_title last-child"><?php echo JText::_('Available Cover Letter'); ?></span>
                    <span class="stats_data_value last-child">
                        <?php
                        if ($this->ispackagerequired != 1) {
                            echo JText::_('Unlimited');
                        } elseif ($this->coverlettersallow == -1) {
                            echo JText::_('Unlimited');
                        } else {
                            $available_coverletters = $this->coverlettersallow - $this->totalcoverletters;
                            echo $available_coverletters;
                        }
                        ?>
                    </span>
                </div>        
            </div>
            <?php
            if ($this->ispackagerequired != 1) {
                $message = "<strong>" . JText::_('Package Not Required') . "</strong>";
                ?>
                <div id="stats_package_message">
                    <?php echo $message; ?>
                </div>

                <?php
            } else {
                if ($print == 0) {
                    $message = '';
                    $j_p_link = JRoute::_('index.php?option=com_jsjobs&c=jobseekerpackages&view=jobseekerpackages&layout=packages&Itemid=' . $this->Itemid);
                    if (empty($this->packagedetail[0]->id)) {
                        $message = "<strong><font color='orangered'>" . JText::_('Job No Package') . " <a href=" . $j_p_link . ">" . JText::_('Jobseeker Packages') . "</a></font></strong>";
                    } else {
                        $days = $this->packagedetail[0]->packageexpiredays - $this->packagedetail[0]->packageexpireindays;
                        if ($days == 1)
                            $days = $days . ' ' . JText::_('Day');
                        else
                            $days = $days . ' ' . JText::_('Days');
                        $message = "<strong><font color='red'>" . JText::_('Your Package') . ' &quot;' . $this->packagedetail[0]->packagetitle . '&quot; ' . JText::_('Has Expired') . ' ' . $days . ' ' . JText::_('Ago') . " <a href=" . $j_p_link . ">" . JText::_('Jobseeker Packages') . "</a></font></strong>";
                    }
                    if ($message != '') {
                        $this->jsjobsmessages->getAccessDeniedMsg($message, $message, 0);
                    }
                }
            }
        } else {
            switch ($this->mystats_allowed) {
                case EMPLOYER_NOT_ALLOWED_JOBSEEKER_PRIVATE_AREA:
                    $this->jsjobsmessages->getAccessDeniedMsg('Employer Not Allow', 'Employer is not allow in job seeker private area', 0);
                    break;
                case USER_ROLE_NOT_SELECTED:
                        $link = "index.php?option=com_jsjobs&c=common&view=common&layout=new_injsjobs&Itemid=".$this->Itemid;
                        $this->jsjobsmessages->getUserNotSelectedMsg('User Role Not Selected', 'User Role Is Not Selected, Please Select Your Role', $link);
                    break;
                case VISITOR_NOT_ALLOWED_JOBSEEKER_PRIVATE_AREA:
                    $this->jsjobsmessages->getAccessDeniedMsg('Visitor Not Allow', 'Visitor is not allow in job seeker private area', 1);
                    break;
            }
        }
    }//ol
    ?>	

