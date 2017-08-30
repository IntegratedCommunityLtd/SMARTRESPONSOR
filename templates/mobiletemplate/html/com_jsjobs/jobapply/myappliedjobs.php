<?php
/**
 * @Copyright Copyright (C) 2009-2011
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
  + Created by:          Ahmad Bilal
 * Company:     Buruj Solutions
  + Contact:        www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:  Jan 11, 2009
  ^
  + Project:        JS Jobs
 * File Name:   views/jobseeker/tmpl/myappliedjobs.php
  ^
 * Description: template view for my applied jobs
  ^
 * History:     NONE
  ^
 */
defined('_JEXEC') or die('Restricted access');
$link = 'index.php?option=com_jsjobs&c=jobapply&view=jobapply&layout=myappliedjobs&Itemid=' . $this->Itemid;
?>
<div id="js_menu_wrapper">
    <?php
    if (sizeof($this->jobseekerlinks) != 0) {
        foreach ($this->jobseekerlinks as $lnk) {
            ?>                     
            <a class="js_menu_link <?php if ($lnk[2] == 'job') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
            <?php
        }
    }
    if (sizeof($this->employerlinks) != 0) {
        foreach ($this->employerlinks as $lnk) {
            ?>
            <a class="js_menu_link <?php if ($lnk[2] == 'job') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
            <?php
        }
    }
    ?>
</div>
<?php
if ($this->config['offline'] == '1') {
    $this->jsjobsmessages->getSystemOfflineMsg($this->config);
} else {
    if ($this->myappliedjobs_allowed == VALIDATE) {
        if ($this->application) {
            if ($this->sortlinks['sortorder'] == 'ASC')
                $img = "components/com_jsjobs/images/sort0.png";
            else
                $img = "components/com_jsjobs/images/sort1.png";
            ?>
            <div id="js_main_wrapper">
                <span class="js_controlpanel_section_title"><?php echo JText::_('My Applied Jobs'); ?></span>
                <div id="sortbylinks">
                    <?php if (isset($this->fieldsordering['jobtitle']) && $this->fieldsordering['jobtitle'] == 1) { ?>
                        <span class="myapplied_jobs_sbl_links"><a class="<?php if ($this->sortlinks['sorton'] == 'title') echo 'selected' ?>" href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['title']; ?>"><?php echo JText::_('Title'); ?><?php if ($this->sortlinks['sorton'] == 'title') { ?> <img src="<?php echo $img ?>"> <?php } ?></a></span>
                    <?php
                    }
                    if (isset($this->fieldsordering['jobtype']) && $this->fieldsordering['jobtype'] == 1) {
                        ?>
                        <span class="myapplied_jobs_sbl_links"><a class="<?php if ($this->sortlinks['sorton'] == 'jobtype') echo 'selected' ?>" href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['jobtype']; ?>"><?php echo JText::_('Job Type'); ?><?php if ($this->sortlinks['sorton'] == 'jobtype') { ?> <img src="<?php echo $img ?>"> <?php } ?></a></span>
                    <?php
                    }
                    if (isset($this->fieldsordering['jobstatus']) && $this->fieldsordering['jobstatus'] == 1) {
                        ?>
                        <span class="myapplied_jobs_sbl_links"><a class="<?php if ($this->sortlinks['sorton'] == 'jobstatus') echo 'selected' ?>" href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['jobstatus']; ?>"><?php echo JText::_('Job Status'); ?><?php if ($this->sortlinks['sorton'] == 'jobstatus') { ?> <img src="<?php echo $img ?>"> <?php } ?></a></span>
                    <?php
                    }
                    if (isset($this->fieldsordering['company']) && $this->fieldsordering['company'] == 1) {
                        ?>
                        <span class="myapplied_jobs_sbl_links"><a class="<?php if ($this->sortlinks['sorton'] == 'company') echo 'selected' ?>" href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['company']; ?>"><?php echo JText::_('Company'); ?><?php if ($this->sortlinks['sorton'] == 'company') { ?> <img src="<?php echo $img ?>"> <?php } ?></a></span>
                    <?php
                    }
                    if (isset($this->fieldsordering['jobsalaryrange']) && $this->fieldsordering['jobsalaryrange'] == 1) {
                        ?>
                        <span class="myapplied_jobs_sbl_links"><a class="<?php if ($this->sortlinks['sorton'] == 'salaryrange') echo 'selected' ?>" href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['salaryrange']; ?>"><?php echo JText::_('Salary Range'); ?><?php if ($this->sortlinks['sorton'] == 'salaryrange') { ?> <img src="<?php echo $img ?>"> <?php } ?></a></span>
                <?php } ?>
                    <span class="myapplied_jobs_sbl_links"><a class="<?php if ($this->sortlinks['sorton'] == 'created') echo 'selected' ?>" href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['created']; ?>"><?php echo JText::_('Posted'); ?><?php if ($this->sortlinks['sorton'] == 'created') { ?> <img src="<?php echo $img ?>"> <?php } ?></a></span>
                </div>
                <?php
                $days = $this->config['newdays'];
                $isnew = date("Y-m-d H:i:s", strtotime("-$days days"));
                if (isset($this->application)) {
                    foreach ($this->application as $job) {
                        $comma = "";
                        ?>
                        <div class="js_job_main_wrapper">
                            <div class="js_job_image_area">
                                <div class="js_job_image_wrapper">
                                    <?php
                                    if (!empty($job->companylogo)) {
                                        if ($this->isjobsharing) {
                                            $imgsrc = $job->companylogo;
                                        } else {
                                            $imgsrc = $this->config['data_directory'] . '/data/employer/comp_' . $job->companyid . '/logo/' . $job->companylogo;
                                        }
                                    } else {
                                        $imgsrc = 'components/com_jsjobs/images/jobseeker.png';
                                    }
                                    ?>
                                    <img class="js_job_image" src="<?php echo $imgsrc; ?>" />
                                </div>
                                <div class="js_job_quick_view_wrapper">
                                    <?php
                                    if (isset($this->fieldsordering['noofjobs']) && $this->fieldsordering['noofjobs'] == 1) {
                                        if ($job->noofjobs != 0) {
                                            echo $job->noofjobs . " " . JText::_('Jobs');
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="js_job_data_area">
                                <div class="js_job_data_1">
                                    <?php if (isset($this->fieldsordering['jobtitle']) && $this->fieldsordering['jobtitle'] == 1) { ?>
                                        <span class="js_job_title">
                                            <?php $link = 'index.php?option=com_jsjobs&c=job&view=job&layout=view_job&nav=16&bd=' . $job->jobaliasid . '&Itemid=' . $this->Itemid; ?>
                                            <a href="<?php echo $link; ?>" class=''><?php echo $job->title; ?></a>
                                        </span>
                    <?php } ?>
                                    <span class="js_job_posted">
                                    <?php echo JText::_('Applied Date') . ':&nbsp;' . JHtml::_('date', $job->apply_date, $this->config['date_format']); ?>
                                    </span>
                                </div>
                                <div class="js_job_data_2">
                                    <?php
                                    if (isset($this->fieldsordering['company']) && $this->fieldsordering['company'] == 1) {
                                        echo "<div class='js_job_data_2_wrapper'>";
                                        if ($this->config['labelinlisting'] == '1') {
                                            echo "<span class=\"js_job_data_2_title\">" . JText::_('Company') . ": </span>";
                                        }
                                        $link = 'index.php?option=com_jsjobs&c=company&view=company&layout=view_company&nav=34&cd=' . $job->companyaliasid . '&cat=' . $job->jobcategory . '&Itemid=' . $this->Itemid;
                                        ?>
                                        <span class="js_job_data_2_value"><a class="jl_company_a" href="<?php echo $link ?>"><?php echo $job->companyname; ?></a></span></div>
                                    <?php
                                }
                                if (isset($this->fieldsordering['jobcategory']) && $this->fieldsordering['jobcategory'] == 1) {
                                    echo "<div class='js_job_data_2_wrapper'>";
                                    if ($this->config['labelinlisting'] == '1') {
                                        echo "<span class=\"js_job_data_2_title\">" . JText::_('Category') . ": </span>";
                                    }
                                    echo "<span class=\"js_job_data_2_value\">" . JText::_($job->cat_title) . "</span></div>";
                                }
                                if (isset($this->fieldsordering['jobsalaryrange']) && $this->fieldsordering['jobsalaryrange'] == 1) {
                                    if ($job->rangestart) {
                                        $salary = $this->getJSModel('common')->getSalaryRangeView($job->symbol,$job->rangestart,$job->rangeend,$job->salaytype,$this->config['currency_align']);
                                        echo "<div class='js_job_data_2_wrapper'>";
                                        if ($this->config['labelinlisting'] == '1') {
                                            echo "<span class=\"js_job_data_2_title\">" . JText::_('Salary') . ": </span>";
                                        }
                                        echo "<span class=\"js_job_data_2_value\">" . $salary . "</span></div>";
                                    }
                                }
                                if (isset($this->fieldsordering['jobtype']) && $this->fieldsordering['jobtype'] == 1) {
                                    echo "<div class='js_job_data_2_wrapper'>";
                                    if ($this->config['labelinlisting'] == '1') {
                                        echo "<span class=\"js_job_data_2_title\">" . JText::_('Job Type') . ": </span>";
                                    }
                                    echo "<span class=\"js_job_data_2_value\">" . JText::_($job->jobtypetitle);
                                    if ($this->fieldsordering['jobstatus'] == '1')
                                        echo ' - ' . JText::_($job->jobstatustitle);
                                    echo "</span></div>";
                                }
                                ?>
                            </div>
                            <div class="js_job_data_3 myjob">
                                <?php
                                if (isset($this->fieldsordering['city']) && $this->fieldsordering['city'] == 1) {

                                    echo "<span class=\"js_job_data_location_title\">" . JText::_('Location') . ":&nbsp;</span>";
                                    if (isset($job->city) AND ! empty($job->city)) {
                                        if (strlen($job->city) > 35) {
                                            ?> <span class="js_job_data_location_value"><?php echo JText::_('Multi City') . $job->multicity; ?></span>
                                            <?php
                                        } else {
                                            echo "<span class=\"js_job_data_location_value\">" . $job->city . "</span>";
                                        }
                                    }
                                }
                                ?>
                            </div>                
                            <?php
                            if ($this->config['show_applied_resume_status'] == 1) {
                                if ($job->resumestatus == 4) { ?>
                                    <div class="js_job_publish rejected"><canvas class="goldjob" width="20" height="20"></canvas><?php echo JText::_('Rejected'); ?></div>    
                                <?php } elseif ($job->resumestatus == 3) { ?>
                                    <div class="js_job_publish publish"><canvas class="goldjob" width="20" height="20"></canvas><?php echo JText::_('Hired'); ?></div>
                                <?php } elseif ($job->resumestatus == 5) { ?>
                                    <div class="js_job_publish pending"><canvas class="goldjob" width="20" height="20"></canvas><?php echo JText::_('Shortlist'); ?></div>
                                <?php } elseif ($job->resumestatus == 2) { ?>
                                    <div class="js_job_publish expired"><canvas class="goldjob" width="20" height="20"></canvas><?php echo JText::_('Spam'); ?></div>
                                    <?php
                                }
                            }
                            if ($job->created > $isnew) {
                                echo '<div id="jl_image_new"></div>';
                            }
                            ?>
                        </div>
                    </div>
                <?php
                }
            }
            ?>
            </div>
            <form action="<?php echo JRoute::_('index.php?option=com_jsjobs&c=jobapply&view=jobapply&layout=myappliedjobs&Itemid=' . $this->Itemid); ?>" method="post">
                <div id="jl_pagination">
                    <div id="jl_pagination_pageslink">
                        <?php echo $this->pagination->getPagesLinks(); ?>
                    </div>
                    <div id="jl_pagination_box">
                        <?php
                        echo JText::_('Display #');
                        echo $this->pagination->getLimitBox();
                        ?>
                    </div>
                    <div id="jl_pagination_counter">
            <?php echo $this->pagination->getResultsCounter(); ?>
                    </div>
                </div>
            </form> 
        <?php } else { // no result found in this category 
                $this->jsjobsmessages->getAccessDeniedMsg('Could not find any matching results.', 'Could not find any matching results.', 0);
                }
    } else {
        switch ($this->myappliedjobs_allowed) {
            case EMPLOYER_NOT_ALLOWED_JOBSEEKER_PRIVATE_AREA:
                $this->jsjobsmessages->getAccessDeniedMsg('Employer Not Allow', 'Employer is not allow in job seeker private area', 0);
                break;
            case USER_ROLE_NOT_SELECTED:
                $link = "index.php?option=com_jsjobs&c=common&view=common&layout=new_injsjobs&Itemid=".$this->Itemid;
                $this->jsjobsmessages->getUserNotSelectedMsg('User Role Not Selected', 'User Role Is Not Selected, Please Select Your Role', $link);
                break;
            case VISITOR_NOT_ALLOWED_JOBSEEKER_PRIVATE_AREA:
                $this->jsjobsmessages->getAccessDeniedMsg('Visitor Not Allow', 'Visitor is not allow in job seeker private area', 0);
                break;
        }
    }
}//ol
?>  


<?php
$document = JFactory::getDocument();
$document->addScript('components/com_jsjobs/js/canvas_script.js');
?>