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
 * File Name:	views/jobseeker/tmpl/myresume.php
  ^
 * Description: template view for my resume
  ^
 * History:		NONE
  ^
 */
defined('_JEXEC') or die('Restricted access');
$link = "index.php?option=com_jsjobs&c=resume&view=resume&layout=myresumes&Itemid=" . $this->Itemid;
?>
<script language=Javascript>
    function confirmdeleteresume() {
        return confirm("<?php echo JText::_('Are You Sure Delete The Resume'); ?>");
    }
</script>

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
        if ($this->myresume_allowed == VALIDATE) {
            if ($this->resumes) {
                if ($this->sortlinks['sortorder'] == 'ASC')
                    $img = "components/com_jsjobs/images/sort0.png";
                else
                    $img = "components/com_jsjobs/images/sort1.png";
                ?>
                <div id="js_main_wrapper">
                    <span class="js_controlpanel_section_title"><?php echo JText::_('My Resume'); ?></span>
                    <form action="index.php" method="post" name="adminForm">
                        <div id="sortbylinks">
                            <span class="my_resume_sbl_links"><a class="<?php if ($this->sortlinks['sorton'] == 'application_title') echo 'selected' ?>" href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['application_title']; ?>"><?php echo JText::_('Title'); ?><?php if ($this->sortlinks['sorton'] == 'application_title') { ?> <img src="<?php echo $img ?>"> <?php } ?></a></span>
                            <span class="my_resume_sbl_links"><a class="<?php if ($this->sortlinks['sorton'] == 'jobtype') echo 'selected' ?>" href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['jobtype']; ?>"><?php echo JText::_('Job Type'); ?><?php if ($this->sortlinks['sorton'] == 'jobtype') { ?> <img src="<?php echo $img ?>"> <?php } ?></a></span>
                            <span class="my_resume_sbl_links"><a class="<?php if ($this->sortlinks['sorton'] == 'salaryrange') echo 'selected' ?>" href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['salaryrange']; ?>"><?php echo JText::_('Salary Range'); ?><?php if ($this->sortlinks['sorton'] == 'salaryrange') { ?> <img src="<?php echo $img ?>"> <?php } ?></a></span>
                            <span class="my_resume_sbl_links"><a class="<?php if ($this->sortlinks['sorton'] == 'created') echo 'selected' ?>" href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['created']; ?>"><?php echo JText::_('Date posted'); ?><?php if ($this->sortlinks['sorton'] == 'created') { ?> <img src="<?php echo $img ?>"> <?php } ?></a></span>
                        </div>
                        <?php
                        $isnew = date("Y-m-d H:i:s", strtotime("-" . $this->config['newdays'] . " days"));
                        foreach ($this->resumes as $resume) {
                            $comma = "";
                            ?>
                            <div class="js_job_main_wrapper">
                                <div class="js_job_image_area">
                                    <div class="js_job_image_wrapper mycompany">
                                        <?php
                                        if ($resume->photo != '') {
                                            $imgsrc = $this->config['data_directory'] . "/data/jobseeker/resume_" . $resume->id . "/photo/" . $resume->photo;
                                        } else {
                                            $imgsrc = "components/com_jsjobs/images/jobseeker.png";
                                        }
                                        ?>
                                        <img class="js_job_image" src="<?php echo $imgsrc; ?>" />
                                    </div>
                                </div>
                                <div class="js_job_data_area">
                                    <div class="js_job_data_2 myresume first-child">
                                        <div class='js_job_data_2_wrapper'>
                                            <?php echo $resume->first_name . ' ' . $resume->last_name; ?>
                                        </div>
                                        <div class='js_job_data_2_wrapper'>
                                            (<?php echo $resume->application_title; ?>)
                                        </div>
                                        <div class='js_job_data_2_wrapper'>
                                            <?php echo $resume->email_address; ?>
                                        </div>
                                        <div class="js_job_data_2_wrapper">
                                            <?php
                                            $g_f_resume = 0;

                                            if ($resume->status == '0') {
                                                echo JText::_('Status') . ' : ' . JText::_('Approvalwaiting');
                                            } elseif ($resume->status == '-1') {
                                                echo JText::_('Status') . ' : ' . JText::_('Notapproved');
                                            } elseif ($resume->status == '1') {
                                                $link = 'index.php?option=com_jsjobs&c=resume&view=resume&layout=formresume&nav=29&rd=' . $resume->resumealiasid . '&Itemid=' . $this->Itemid;
                                                ?>
                                                <a class="icon" href="<?php echo $link ?>" title="<?php echo JText::_('Edit'); ?>"><img class="icon"width="17" height="17" src="components/com_jsjobs/images/edit.png" /></a>
                                                <?php $link = 'index.php?option=com_jsjobs&c=resume&view=resume&layout=view_resume&nav=1&rd=' . $resume->resumealiasid . '&Itemid=' . $this->Itemid; ?>
                                                <a class="icon" href="<?php echo $link ?>" title="<?php echo JText::_('View'); ?>"><img class="icon"width="17" height="17" src="components/com_jsjobs/images/view.png" /></a>
                                                <?php $link = 'index.php?option=com_jsjobs&task=resume.deleteresume&rd=' . $resume->resumealiasid . '&Itemid=' . $this->Itemid; ?>
                                                <a href="<?php echo $link ?>" onclick=" return confirmdeleteresume();" class="icon" title="<?php echo JText::_('Delete'); ?>"><img class="icon"width="17" height="17" src="components/com_jsjobs/images/delete.png" /></a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="js_job_data_2 myresume">
                                        <div class="js_job_data_2_wrapper">
                                            <span class="js_job_data_2_title"><?php echo JText::_('Total Experience') . ":"; ?></span>
                                            <span class="js_job_data_2_value">
                                                <?php
                                                if (!empty($resume->total_experience))
                                                    echo $resume->total_experience;
                                                else
                                                    echo JText::_('No Work Experience');
                                                ?>
                                            </span>
                                        </div>
                                        <div class="js_job_data_2_wrapper">
                                            <span class="js_job_data_2_title"><?php echo JText::_('Category') . ":"; ?></span>
                                            <span class="js_job_data_2_value"><?php echo $resume->cat_title; ?></span>
                                        </div>
                                        <div class="js_job_data_2_wrapper">
                                            <span class="js_job_data_2_title"><?php echo JText::_('Salary') . ":"; ?></span>
                                            <span class="js_job_data_2_value">
                                                <?php
                                                echo $this->getJSModel('common')->getSalaryRangeView($resume->symbol,$resume->rangestart,$resume->rangeend,$resume->salarytype,$this->config['currency_align']);
                                                ?>
                                            </span>
                                        </div>
                                        <div class="js_job_data_2_wrapper">
                                            <span class="js_job_data_2_title"><?php echo JText::_('Job Type') . ":"; ?></span>
                                            <span class="js_job_data_2_value"><?php echo $resume->jobtypetitle; ?></span>
                                        </div>
                                    </div>
                                    <div class="js_job_data_3 myresume">
                                        <?php
                                        $address = '';
                                        $comma = '';
                                        if ($resume->cityname != '') {
                                            $address = $comma . $resume->cityname;
                                            $comma = " ,";
                                        }

                                        if ($resume->statename != '') {
                                            $address .= $comma . $resume->statename;
                                            $comma = " ,";
                                        }

                                        if ($resume->countryname != '')
                                            $address .= $comma . $resume->countryname;
                                        ?>
                                        <span class="js_job_data_2_title"><?php echo JText::_('Address') . ":"; ?></span>
                                        <span class="js_job_data_2_value"><?php echo $address; ?></span>
                                        <?php
                                        echo "<span class='js_job_data_2_created_myresume'>" . JText::_('Created') . ": ";
                                        echo JHtml::_('date', $resume->created, $this->config['date_format']). "</span>";
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <?php
                        }
                        ?>
                        <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                        <input type="hidden" name="task" value="deleteresume" />
                        <input type="hidden" name="c" value="resume" />
                        <input type="hidden" id="id" name="id" value="" />
                        <input type="hidden" name="boxchecked" value="0" />
                    </form>
                </div>

                <form action="<?php echo JRoute::_('index.php?option=com_jsjobs&c=resume&view=resume&layout=myresumes&Itemid=' . $this->Itemid); ?>" method="post">
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
            <?php }else { // no result found in this category 
                $this->jsjobsmessages->getAccessDeniedMsg('Could not find any matching results.', 'Could not find any matching results.', 0);
            }
        } else {
        switch ($this->myresume_allowed) {
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


    <script type="text/javascript" language="javascript">
        function setLayoutSize() {
            var totalwidth = document.getElementById("rl_maindiv").offsetWidth;
            var per_width = (totalwidth * 0.23) - 10;
            var totalimagesdiv = document.getElementsByName("rl_imagediv").length;
            for (var i = 0; i < totalimagesdiv; i++) {
                document.getElementsByName("rl_imagediv")[i].style.minWidth = per_width + "px";
                document.getElementsByName("rl_imagediv")[i].style.width = per_width + "px";
            }
            var totalimages = document.getElementsByName("rl_image").length;
            for (var i = 0; i < totalimages; i++) {
                //document.getElementsByName("rl_image")[i].style.minWidth = per_width+"px";
                document.getElementsByName("rl_image")[i].style.width = per_width + "px";
                document.getElementsByName("rl_image")[i].style.maxWidth = per_width + "px";
            }
        }
        setLayoutSize();
    </script>
