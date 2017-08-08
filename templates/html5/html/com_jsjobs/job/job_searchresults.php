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
 * File Name:   views/jobseeker/tmpl/jobsearchresults.php
  ^
 * Description: template view job search results
  ^
 * History:     NONE
  ^
 */
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();
JHTML::_('behavior.formvalidation');
if (JVERSION < 3) {
    JHtml::_('behavior.mootools');
    $document->addScript('components/com_jsjobs/js/jquery.js');
} else {
    JHtml::_('behavior.framework');
    JHtml::_('jquery.framework');
}


$link = 'index.php?option=com_jsjobs&c=job&view=job&layout=job_searchresults&Itemid=' . $this->Itemid;
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
require_once( 'jobapply.php' );
if ($this->config['offline'] == '1') {
    $this->jsjobsmessages->getSystemOfflineMsg($this->config);
} else {
    ?>
    <script language="javascript">
        function myValidate(f) {
            if (document.formvalidator.isValid(f)) {
            }
            else {
                alert('Search name is not acceptable.  Please retry.');
                return false;
            }
            return true;
        }

    </script>
    <?php
    if ($this->application) {

        if (isset($this->userrole->rolefor)) {
            if ($this->userrole->rolefor != '') {
                if ($this->userrole->rolefor == 2) // job seeker
                    $allowed = true;
                elseif ($this->userrole->rolefor == 1) {
                    if ($this->config['employerview_js_controlpanel'] == 1)
                        $allowed = true;
                    else
                        $allowed = false;
                }
            }else {
                $allowed = true;
            }
        } else
            $allowed = true; // user not logined
        if ($allowed == true) {

            if ($this->sortlinks['sortorder'] == 'ASC')
                $img = "components/com_jsjobs/images/sort0.png";
            else
                $img = "components/com_jsjobs/images/sort1.png";
            ?>
            <div id="js_main_wrapper">
                <span class="js_controlpanel_section_title"><?php echo JText::_('Job Search Results'); ?></span>
                <?php
                if ($this->canview == 1) {
                    if ($this->searchjobconfig['search_job_showsave'] == 1) {
                        if (($this->uid) && ($this->userrole->rolefor)) {
                            ?>
                            <div id="savesearch_form">
                                <form action="index.php" method="post" name="adminForm" id="adminForm" onsubmit="return myValidate(this);">
                                    <div class="js_label">
                                        <?php echo JText::_('Save This Search'); ?>
                                    </div>
                                    <div class="js_input_field">
                                        <input class="inputbox required" type="text" name="searchname" size="20" maxlength="30"  />
                                    </div>
                                    <div class="js_button_field">
                                        <input type="submit" id="button" class="button validate" value="<?php echo JText::_('Save'); ?>">
                                    </div>
                                    <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                                    <input type="hidden" name="task" value="savejobsearch" />
                                    <input type="hidden" name="c" value="jobsearch" />
                                </form> 
                            </div>
                            <?php
                        }
                    }
                }
                ?>
                <div id="sortbylinks">
                    <?php if ($this->listjobconfig['lj_title'] == '1') { ?>             
                        <span class="my_job_sbl_links"><a class="<?php if ($this->sortlinks['sorton'] == 'title') echo 'selected'; ?>" href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['title']; ?>"><?php echo JText::_('Title'); ?><?php if ($this->sortlinks['sorton'] == 'title') { ?> <img src="<?php echo $img ?>"> <?php } ?></a></span>
                        <?php
                    }
                    if ($this->listjobconfig['lj_category'] == '1') {
                        ?>  
                        <span class="my_job_sbl_links"><a class="<?php if ($this->sortlinks['sorton'] == 'category') echo 'selected'; ?>" href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['category']; ?>"><?php echo JText::_('Category'); ?><?php if ($this->sortlinks['sorton'] == 'category') { ?> <img src="<?php echo $img ?>"> <?php } ?></a></span>
                        <?php
                    }
                    if ($this->listjobconfig['lj_jobtype'] == '1') {
                        ?>  
                        <span class="my_job_sbl_links"><a class="<?php if ($this->sortlinks['sorton'] == 'jobtype') echo 'selected'; ?>" href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['jobtype']; ?>"><?php echo JText::_('Job Type'); ?><?php if ($this->sortlinks['sorton'] == 'jobtype') { ?> <img src="<?php echo $img ?>"> <?php } ?></a></span>
                        <?php
                    }
                    if ($this->listjobconfig['lj_jobstatus'] == '1') {
                        ?>  
                        <span class="my_job_sbl_links"><a class="<?php if ($this->sortlinks['sorton'] == 'jobstatus') echo 'selected'; ?>" href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['jobstatus']; ?>"><?php echo JText::_('Job Status'); ?><?php if ($this->sortlinks['sorton'] == 'jobstatus') { ?> <img src="<?php echo $img ?>"> <?php } ?></a></span>
                        <?php
                    }
                    if ($this->listjobconfig['lj_company'] == '1') {
                        ?>
                        <span class="my_job_sbl_links"><a class="<?php if ($this->sortlinks['sorton'] == 'company') echo 'selected'; ?>" href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['company']; ?>"><?php echo JText::_('Company'); ?><?php if ($this->sortlinks['sorton'] == 'company') { ?> <img src="<?php echo $img ?>"> <?php } ?></a></span>
                        <?php
                    }
                    if ($this->listjobconfig['lj_salary'] == '1') {
                        ?>  
                        <span class="my_job_sbl_links"><a class="<?php if ($this->sortlinks['sorton'] == 'salaryrange') echo 'selected'; ?>" href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['salaryrange']; ?>"><?php echo JText::_('Salary Range'); ?><?php if ($this->sortlinks['sorton'] == 'salaryrange') { ?> <img src="<?php echo $img ?>"> <?php } ?></a></span>
                        <?php
                    }
                    if ($this->listjobconfig['lj_created'] == '1') {
                        ?>  
                        <span class="my_job_sbl_links"><a class="<?php if ($this->sortlinks['sorton'] == 'created') echo 'selected'; ?>" href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['created']; ?>"><?php echo JText::_('Posted'); ?><?php if ($this->sortlinks['sorton'] == 'created') { ?> <img src="<?php echo $img ?>"> <?php } ?></a></span>
                    <?php } ?>
                </div>
                <?php
                $days = $this->config['newdays'];
                $isnew = date("Y-m-d H:i:s", strtotime("-$days days"));
                if (isset($this->application)) {
                    $noofjobs = 1;
                    foreach ($this->application as $job) {
                        $comma = "";
                        print_job($job, $this, $isnew, 1);
                    }
                ?>
                <form action="<?php echo JRoute::_('index.php?option=com_jsjobs&c=job&view=job&layout=job_searchresults&Itemid=' . $this->Itemid); ?>" method="post">
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
                <?php
                }
            } else { // not allowed job posting 
                $this->jsjobsmessages->getAccessDeniedMsg('You are not allowed', 'You are not allow to view this page', 0);
            }
        } else { // no result found in this category 
            $this->jsjobsmessages->getAccessDeniedMsg('Could not find any matching results.', 'Could not find any matching results.', 0);
        }
        ?>  


        <script language="javascript">
            showsavesearch(0);
        </script>
        <?php
    }//ol
    ?>

    <?php

    function print_job($job, $thisjob, $isnew, $jobtype = 1) { ?>
        <div class="js_job_main_wrapper">
            <div class="js_job_data_1">
                <span class="js_job_title">
                    <?php $link = 'index.php?option=com_jsjobs&c=job&view=job&layout=view_job&nav=15&bd=' . $job->jobaliasid . '&Itemid=' . $thisjob->Itemid; ?>
                    <a class="js_job_title" href="<?php echo $link ?>"><?php echo $job->title; ?></a>
                </span>
                <span class="js_job_posted">
                    <?php
                    if ($job->jobdays == 0)
                        echo JText::_('Posted') . ': ' . JText::_('Today');
                    else
                        echo JText::_('Posted') . ': ' . $job->jobdays . ' ' . JText::_('Days Ago');
                    ?>
                </span>
            </div>
            <div class="js_job_image_area">
                <div class="js_job_image_wrapper">
                    <?php
                    if (!empty($job->companylogo)) {
                        if (isset($job->localcompanyid))
                            $imgsrc = $thisjob->config['data_directory'] . '/data/employer/comp_' . $job->localcompanyid . '/logo/' . $job->companylogo;
                        if ($jobtype == 1) {
                            if ($thisjob->isjobsharing) {
                                $imgsrc = $job->companylogo;
                            } else {
                                $imgsrc = $thisjob->config['data_directory'] . '/data/employer/comp_' . $job->companyid . '/logo/' . $job->companylogo;
                            }
                        }
                    } else {
                        $imgsrc = 'components/com_jsjobs/images/blank_logo.png';
                    }
                    ?>
                    <img class="js_job_image" src="<?php echo $imgsrc; ?>" />
                </div>
            </div>
            <div class="js_job_data_area">
                <div class="js_job_data_2">
                    <?php if ($thisjob->listjobconfig['lj_company'] == '1') { ?>
                        <div class="js_job_data_2_wrapper">
                            <?php if ($thisjob->config['labelinlisting'] == '1') { ?>
                                <span class="js_job_data_2_title"><?php echo JText::_('Company') . ":"; ?></span>
                                <?php
                            }
                            $link = 'index.php?option=com_jsjobs&c=company&view=company&layout=view_company&nav=35&cd=' . $job->companyaliasid . '&cat=' . $job->jobcategory . '&Itemid=' . $thisjob->Itemid;
                            ?>
                            <span class="js_job_data_2_value"><a class="js_job_data_2_company_link" href="<?php echo $link ?>"><?php echo $job->companyname; ?></a></span>
                        </div>
                    <?php } ?>
                    <?php if ($thisjob->listjobconfig['lj_category'] == '1') { ?>
                        <div class='js_job_data_2_wrapper'>
                            <?php if ($thisjob->config['labelinlisting'] == '1') { ?>
                                <span class="js_job_data_2_title"><?php echo JText::_('Category') . ":"; ?></span>
                            <?php } ?>
                            <span class="js_job_data_2_value"><?php echo $job->cat_title; ?></span>
                        </div>
                    <?php } ?>
                    <?php
                    if ($thisjob->listjobconfig['lj_salary'] == '1') { ?>
                            <div class="js_job_data_2_wrapper">
                                <?php if ($thisjob->config['labelinlisting'] == '1') { ?>
                                    <span class="js_job_data_2_title"><?php echo JText::_('Salary') . ":"; ?></span>
                                <?php } ?>
                                <span class="js_job_data_2_value">
                                    <?php echo $thisjob->getJSModel('common')->getSalaryRangeView($job->symbol,$job->salaryfrom,$job->salaryto,$job->salaytype,$thisjob->config['currency_align']); ?>
                                </span>
                            </div>
                            <?php
                    }
                    if ($thisjob->listjobconfig['lj_jobtype'] == '1') {
                        ?>
                        <div class="js_job_data_2_wrapper">
                            <?php if ($thisjob->config['labelinlisting'] == '1') { ?>
                                <span class="js_job_data_2_title"><?php echo JText::_('Job Type') . ":"; ?></span>
                            <?php } ?>
                            <span class="js_job_data_2_value">
                                <?php 
                                    echo $job->jobtypetitle;
                                    if ($thisjob->listjobconfig['lj_jobstatus'] == '1')
                                        echo ' - ' . $job->jobstatustitle;
                                ?>
                            </span>
                        </div>
    <?php } ?>
                </div>
                <div class="js_job_data_3">
                    <span class="js_job_data_location_title"><?php echo JText::_('Location'); ?>:&nbsp;</span>
                    <?php
                    if ($thisjob->listjobconfig['lj_city'] == '1') {
                        if (isset($job->city) AND ! empty($job->city)) {
                            ?>
                            <span class="js_job_data_location_value">
                                <?php
                                if (strlen($job->city) > 35) {
                                    echo JText::_('Multi City') . $job->city;
                                } else {
                                    echo $job->city;
                                }
                                ?>
                            </span>
                            <?php
                        }
                    }
                    ?>
                    <div class="js_job_data_4">
                        <?php
                        $link = 'index.php?option=com_jsjobs&c=jobapply&view=jobapply&layout=job_apply&nav=25&bd=' . $job->jobaliasid . '&Itemid=' . $thisjob->Itemid;
                        ?>
                        <a href="Javascript: void(0);" class="js_job_data_button" data-jobapply="jobapply" data-jobid="<?php echo $job->jobaliasid; ?>" ><?php echo JText::_('Apply now'); ?></a>
                    </div>
                </div>
            </div>
            <?php
            switch ($jobtype) {
                case 1: // Normal Job 
                    ?>                        
                    <?php
                    break;
            }
            ?>            
            <?php if ($job->created > $isnew) { ?>
                <div class="js_job_new"><canvas class="newjob" width="20" height="20"></canvas><?php echo JText::_('New'); ?></div>
            <?php } ?>
            <?php
            if ($thisjob->listjobconfig['lj_noofjobs'] == '1') {
                if ($job->noofjobs != 0) {
                    ?>
                    <div class="js_job_number"><canvas class="newjob" width="20" height="20"></canvas><?php echo $job->noofjobs . ' ' . JText::_('Jobs'); ?></div>
                        <?php
                        }
                    }
                    ?>
        </div>    
    <?php } ?>
    <?php
    $document = JFactory::getDocument();
    $document->addScript('components/com_jsjobs/js/canvas_script.js');
    ?>