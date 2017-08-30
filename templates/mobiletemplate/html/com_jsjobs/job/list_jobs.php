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
 * File Name:	views/jobseeker/tmpl/list_jobs.php
  ^
 * Description: template view for list jobs of a category
  ^
 * History:		NONE
  ^
 */
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_jsjobs/css/token-input-jsjobs.css');
if (JVERSION < 3) {
    JHtml::_('behavior.mootools');
    $document->addScript('components/com_jsjobs/js/jquery.js');
} else {
    JHtml::_('behavior.framework');
    JHtml::_('jquery.framework');
}
$document->addScript('components/com_jsjobs/js/jquery.tokeninput.js');

if (isset($this->jobs[0]))
    $link = 'index.php?option=com_jsjobs&c=job&view=job&layout=list_jobs&cat=' . $this->jobs[0]->categoryaliasid . '&Itemid=' . $this->Itemid;
else
    $link = 'index.php?option=com_jsjobs&c=job&view=job&layout=list_jobs&cat=' . $this->categoryid . '&Itemid=' . $this->Itemid;

$ptitle = '';
if (isset($_GET['cn']))
    $cn = $_GET['cn'];
else
    $cn = '';
if (isset($this->jobs[0])) {
    if ($this->jobs[0]->cat_title != '')
        $ptitle = $this->jobs[0]->cat_title;
} else
    $ptitle = $this->category->cat_title;
$ptitle = JText::_('Category') . ' : ' . $ptitle;

$jobcatlink = JRoute::_('index.php?option=com_jsjobs&c=category&view=category&layout=jobcat&Itemid=' . $this->Itemid);
$showgoogleadds = $this->config['googleadsenseshowinlistjobs'];
$afterjobs = $this->config['googleadsenseshowafter'];
$googleclient = $this->config['googleadsenseclient'];
$googleslot = $this->config['googleadsenseslot'];
$googleaddhieght = $this->config['googleadsenseheight'];
$googleaddwidth = $this->config['googleadsensewidth'];
$googleaddcss = $this->config['googleadsensecustomcss'];

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
?>

    <div id="js_menu_wrapper">
        <?php
        if (sizeof($this->jobseekerlinks) != 0) {
            foreach ($this->jobseekerlinks as $lnk) {
                ?>                     
                <a class="js_menu_link <?php if ($lnk[2] == 'job_categories') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
                <?php
            }
        }
        if (sizeof($this->employerlinks) != 0) {
            foreach ($this->employerlinks as $lnk) {
                ?>
                <a class="js_menu_link <?php if ($lnk[2] == 'job_categories') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
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
        if ($allowed == true) {

            if (isset($this->jobs))
                if ($this->jobs) {
                    ?>
                    <div id="js_main_wrapper">
                        <span class="js_controlpanel_section_title"><?php echo $ptitle; ?></span>
                        <?php
                        if ($this->sortlinks['sortorder'] == 'ASC')
                            $img = "components/com_jsjobs/images/sort0.png";
                        else
                            $img = "components/com_jsjobs/images/sort1.png";

                        if ($this->jobconfig['subcategories'] == 1) {
                            require_once('listjob_subcategories.php' );
                        }
                        ?>
                        <?php
                        if ($allowed == true) {
                            $flink = JRoute::_($link);
                            ?>
                            <div id="tp_filter">
                                <form action="<?php echo $flink; ?>" method="post" name="adminForm">
                    <?php require_once( JPATH_COMPONENT . '/views/job/tmpl/job_filters.php' ); ?>	
                                </form>
                            </div>
                            <?php } ?>
                        <div id="sortbylinks">
                            <?php if (isset($this->fieldsordering['jobtitle']) && $this->fieldsordering['jobtitle'] == 1) { ?>
                                <span class="myapplied_jobs_sbl_links"><a class="<?php if ($this->sortlinks['sorton'] == 'title') echo 'selected'; ?>" href="<?php echo $link . '&sortby=' . $this->sortlinks['title']; ?>"><?php echo JText::_('Title'); ?><?php if ($this->sortlinks['sorton'] == 'title') { ?> <img src="<?php echo $img ?>"> <?php } ?></a></span>
                            <?php
                            }
                            if (isset($this->fieldsordering['jobtype']) && $this->fieldsordering['jobtype'] == 1) {
                                ?>				
                                <span class="myapplied_jobs_sbl_links"><a class="<?php if ($this->sortlinks['sorton'] == 'jobtype') echo 'selected'; ?>" href="<?php echo $link . '&sortby=' . $this->sortlinks['jobtype']; ?>"><?php echo JText::_('Job Type'); ?><?php if ($this->sortlinks['sorton'] == 'jobtype') { ?> <img src="<?php echo $img ?>"> <?php } ?></a></span>
                            <?php
                            }
                            if (isset($this->fieldsordering['jobstatus']) && $this->fieldsordering['jobstatus'] == 1) {
                                ?>	
                                <span class="myapplied_jobs_sbl_links"><a class="<?php if ($this->sortlinks['sorton'] == 'jobstatus') echo 'selected'; ?>" href="<?php echo $link . '&sortby=' . $this->sortlinks['jobstatus']; ?>"><?php echo JText::_('Job status'); ?><?php if ($this->sortlinks['sorton'] == 'jobstatus') { ?> <img src="<?php echo $img ?>"> <?php } ?></a></span>
                            <?php
                            }
                            if (isset($this->fieldsordering['company']) && $this->fieldsordering['company'] == 1) {
                                ?>	
                                <span class="myapplied_jobs_sbl_links"><a class="<?php if ($this->sortlinks['sorton'] == 'company') echo 'selected'; ?>" href="<?php echo $link . '&sortby=' . $this->sortlinks['company']; ?>"><?php echo JText::_('Company'); ?><?php if ($this->sortlinks['sorton'] == 'company') { ?> <img src="<?php echo $img ?>"> <?php } ?></a></span>
                            <?php
                            }
                            if (isset($this->fieldsordering['jobsalaryrange']) && $this->fieldsordering['jobsalaryrange'] == 1) {
                                ?>	
                                <span class="myapplied_jobs_sbl_links"><a class="<?php if ($this->sortlinks['sorton'] == 'salaryrange') echo 'selected'; ?>" href="<?php echo $link . '&sortby=' . $this->sortlinks['salaryrange']; ?>"><?php echo JText::_('Salary Range'); ?><?php if ($this->sortlinks['sorton'] == 'salaryrange') { ?> <img src="<?php echo $img ?>"> <?php } ?></a></span>
                        <?php
                        }
                            ?>	
                                <span class="myapplied_jobs_sbl_links"><a class="<?php if ($this->sortlinks['sorton'] == 'created') echo 'selected'; ?>" href="<?php echo $link . '&sortby=' . $this->sortlinks['created']; ?>"><?php echo JText::_('Date posted'); ?><?php if ($this->sortlinks['sorton'] == 'created') { ?> <img src="<?php echo $img ?>"> <?php } ?></a></span>
                        </div>
                        <?php
                        $days = $this->config['newdays'];
                        $isnew = date("Y-m-d H:i:s", strtotime("-$days days"));

                        if (isset($this->jobs)) {
                            $noofjobs = 1;
                            foreach ($this->jobs as $job) {
                                $comma = "";
                                print_job($job, $this, $isnew, 1);
                                if ($showgoogleadds == 1) {
                                    if ($noofjobs % $afterjobs == 0) {
                                        ?>
                                        <table cellpadding="0" cellspacing="0" border="0" width="100%" style="<?php echo $googleaddcss; ?>">
                                            <tr>
                                                <td>
                                                    <script type="text/javascript">
                                                        google_ad_client = "<?php echo $googleclient; ?>";
                                                        google_ad_slot = "<?php echo $googleslot; ?>";
                                                        google_ad_width = "<?php echo $googleaddwidth; ?>";
                                                        google_ad_height = "<?php echo $googleaddhieght; ?>";
                                                    </script>
                                                    <script type="text/javascript"
                                                            src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
                                                    </script>
                                                </td>
                                            </tr>
                                        </table>
                                        <?php
                                    } $noofjobs++;
                                }
                            }
                        }
                        ?>
                <?php $querystring = '&fr=' . $this->listfor . '&cat=' . $this->jobs[0]->categoryaliasid . '&Itemid=' . $this->Itemid; ?>
                        <form action="<?php echo JRoute::_('index.php?option=com_jsjobs&c=job&view=job&layout=list_jobs' . $querystring); ?>" method="post">
                            <div id="jl_pagination">
                                <div id="jl_pagination_pageslink">
                                    <?php $this->pagination->setAdditionalUrlParam('', $querystring); ?>			
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
    } else { // not allowed job posting 
        $this->jsjobsmessages->getAccessDeniedMsg('You are not allowed', 'You are not allow to view this page', 0);
    }
}//ol
        ?>	

    <?php
    function print_job($job, $thisjob, $isnew, $jobtype = 1) { ?>
        <div class="js_job_main_wrapper">
            <div class="js_job_data_1">
                <?php if (isset($thisjob->fieldsordering['jobtitle']) && $thisjob->fieldsordering['jobtitle'] == 1) { ?>
                    <span class="js_job_title">
                        <?php $link = 'index.php?option=com_jsjobs&c=job&view=job&layout=view_job&nav=13&bd=' . $job->jobaliasid . '&Itemid=' . $thisjob->Itemid; ?>
                        <a class="js_job_title" href="<?php echo $link ?>"><?php echo $job->title; ?></a>
                    </span>
                <?php } ?>
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
                        if ($thisjob->isjobsharing) {
                            $imgsrc = $job->companylogo;
                        } else {
                            $imgsrc = $thisjob->config['data_directory'] . '/data/employer/comp_' . $job->companyid . '/logo/' . $job->companylogo;
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
                    <?php if (isset($thisjob->fieldsordering['company']) && $thisjob->fieldsordering['company'] == 1) { ?>
                        <div class="js_job_data_2_wrapper">
                            <?php if ($thisjob->config['labelinlisting'] == '1') { ?>
                                <span class="js_job_data_2_title"><?php echo JText::_('Company') . ":"; ?></span>
                                <?php
                            }
                            $link = 'index.php?option=com_jsjobs&c=company&view=company&layout=view_company&nav=32&cd=' . $job->companyaliasid . '&Itemid=' . $thisjob->Itemid;
                            ?>
                            <span class="js_job_data_2_value"><a class="js_job_data_2_company_link" href="<?php echo $link ?>"><?php echo $job->companyname; ?></a></span>
                        </div>
                    <?php } ?>
                    <?php if (isset($thisjob->fieldsordering['jobcategory']) && $thisjob->fieldsordering['jobcategory'] == 1) { ?>
                        <div class='js_job_data_2_wrapper'>
                            <?php if ($thisjob->config['labelinlisting'] == '1') { ?>
                                <span class="js_job_data_2_title"><?php echo JText::_('Category') . ":"; ?></span>
                            <?php } ?>
                            <span class="js_job_data_2_value"><?php echo JText::_($job->cat_title); ?></span>
                        </div>
                    <?php } ?>
                    <?php
                    if (isset($thisjob->fieldsordering['jobsalaryrange']) && $thisjob->fieldsordering['jobsalaryrange'] == 1) {
                        if ($job->salaryfrom) {
                            $salary = JSModel::getJSModel('common')->getSalaryRangeView($job->symbol, $job->salaryfrom, $job->salaryto, $job->salaytype, $thisjob->config['currency_align']);
                            ?>
                            <div class="js_job_data_2_wrapper">
                                <?php if ($thisjob->config['labelinlisting'] == '1') { ?>
                                    <span class="js_job_data_2_title"><?php echo JText::_('Salary') . ":"; ?></span>
                                <?php } ?>
                                <span class="js_job_data_2_value"><?php echo $salary; ?></span>
                            </div>
                            <?php
                        }
                    }
                    if (isset($thisjob->fieldsordering['jobtype']) && $thisjob->fieldsordering['jobtype'] == 1) {
                        ?>
                        <div class="js_job_data_2_wrapper">
                            <?php if ($thisjob->config['labelinlisting'] == '1') { ?>
                                <span class="js_job_data_2_title"><?php echo JText::_('Job Type') . ":"; ?></span>
                            <?php } ?>
                            <span class="js_job_data_2_value"><?php
                                echo JText::_($job->jobtype);
                                if ($thisjob->fieldsordering['jobstatus'] == '1')
                                    echo ' - ' . JText::_($job->jobstatus);
                                ?></span>
                        </div>
                    <?php } ?>
                </div>
                <div class="js_job_data_3">
                    <?php if (isset($thisjob->fieldsordering['city']) && $thisjob->fieldsordering['city'] == 1) { ?>
                        <span class="js_job_data_location_title"><?php echo JText::_('Location'); ?>:&nbsp;</span>
                        <?php if (isset($job->city) AND ! empty($job->city)) { ?>
                            <span class="js_job_data_location_value">
                                <?php
                                if (strlen($job->city) > 35) {
                                    if(isset($job->multicity))
                                        echo JText::_('Multi City') . $job->multicity;
                                    else
                                        echo $job->city;
                                } else {
                                    echo $job->city;
                                }
                                ?>
                            </span>
                        <?php } ?>
                    <?php } ?>
                    <div class="js_job_data_4">
                        <?php
                        $link = 'index.php?option=com_jsjobs&c=jobapply&view=jobapply&layout=job_apply&nav=25&bd=' . $job->jobaliasid . '&Itemid=' . $thisjob->Itemid;
                        ?>
                        <a href="Javascript: void(0);" class="js_job_data_button" data-jobapply="jobapply" data-jobid="<?php echo $job->jobaliasid; ?>" ><?php echo JText::_('Apply Now'); ?></a>
                    </div>
                </div>
            </div>
            <?php
            switch ($jobtype) {
                case 1: // Normal Job 
                    ?>                        
                    <?php
                    break;
                case 2: // Gold Job 
                    ?>
                    <div class="js_job_gold"><canvas class="goldjob" width="20" height="20"></canvas><?php echo JText::_('Gold'); ?></div>
                    <?php
                    break;
                case 3: // Featured Job 
                    ?>
                    <div class="js_job_featured"><canvas class="featuredjob" width="20" height="20"></canvas><?php echo JText::_('Featured'); ?></div>
                    <?php
                    break;
            }
            ?>            
            <?php if ($job->created > $isnew) { ?>
                <div class="js_job_new"><canvas class="newjob" width="20" height="20"></canvas><?php echo JText::_('New') . '!'; ?></div>
            <?php } ?>
            <?php
            if (isset($thisjob->fieldsordering['noofjobs']) && $thisjob->fieldsordering['noofjobs'] == 1) {
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