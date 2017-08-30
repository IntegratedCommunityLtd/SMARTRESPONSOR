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
 * File Name:	views/employer/tmpl/jobsearchresults.php
  ^
 * Description: template view job search results
  ^
 * History:		NONE
  ^
 */
defined('_JEXEC') or die('Restricted access');

if (isset($this->resume[0])) {
    if ($this->resume[0]->cat_title != '')
        $ptitle = $this->resume[0]->cat_title;
    if ($this->resume[0]->subcategory != '')
        $ptitle .= ' : ' . $this->resume[0]->subcategory;
    $catlink = JRoute::_("index.php?option=com_jsjobs&c=resume&view=resume&layout=resume_bycategory&cat=" . $this->resume[0]->cat_id . "&Itemid=" . $this->Itemid);
    $categoryid = $this->resume[0]->cat_id;
}elseif (isset($this->subcategorytitle)) {
    if ($this->subcategorytitle->cat_title != '')
        $ptitle = $this->subcategorytitle->cat_title;
    if ($this->subcategorytitle->subcategory != '')
        $ptitle .= ' : ' . $this->subcategorytitle->subcategory;
    $catlink = JRoute::_("index.php?option=com_jsjobs&c=resume&view=resume&layout=resume_bycategory&cat=" . $this->subcategorytitle->cat_id . "&Itemid=" . $this->Itemid);
    $categoryid = $this->subcategorytitle->cat_id;
}
$ptitle = $ptitle;
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
        if (isset($this->resume)) {

            if ($this->userrole->rolefor == 1) { // employer
                $link = "index.php?option=com_jsjobs&c=resume&view=resume&layout=resume_bysubcategory&resumesubcat=" . $this->resume[0]->aliasid . "&Itemid=" . $this->Itemid;

                if ($this->sortlinks['sortorder'] == 'ASC')
                    $img = "components/com_jsjobs/images/sort0.png";
                else
                    $img = "components/com_jsjobs/images/sort1.png";
                ?>
                <div id="js_main_wrapper">
                    <span class="js_controlpanel_section_title"><?php echo $ptitle; ?></span>
                    <div id="sortbylinks">
                        <span class="my_resume_sbl_links"><a class="<?php if ($this->sortlinks['sorton'] == 'application_title') echo 'selected'; ?>" href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['application_title']; ?>"><?php echo JText::_('Title'); ?><?php if ($this->sortlinks['sorton'] == 'application_title') { ?> <img src="<?php echo $img ?>"> <?php } ?></a></span>
                        <span class="my_resume_sbl_links"><a class="<?php if ($this->sortlinks['sorton'] == 'jobtype') echo 'selected'; ?>" href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['jobtype']; ?>"><?php echo JText::_('Job type'); ?><?php if ($this->sortlinks['sorton'] == 'jobtype') { ?> <img src="<?php echo $img ?>"> <?php } ?></a></span>
                        <span class="my_resume_sbl_links"><a class="<?php if ($this->sortlinks['sorton'] == 'salaryrange') echo 'selected'; ?>" href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['salaryrange']; ?>"><?php echo JText::_('Salary Range'); ?><?php if ($this->sortlinks['sorton'] == 'salaryrange') { ?> <img src="<?php echo $img ?>"> <?php } ?></a></span>
                        <span class="my_resume_sbl_links"><a class="<?php if ($this->sortlinks['sorton'] == 'created') echo 'selected'; ?>" href="<?php echo $link ?>&sortby=<?php echo $this->sortlinks['created']; ?>"><?php echo JText::_('Date posted'); ?><?php if ($this->sortlinks['sorton'] == 'created') { ?> <img src="<?php echo $img ?>"> <?php } ?></a></span>
                    </div>
                    <?php
                    foreach ($this->resume as $resume) {
                        $comma = "";
                        ?>
                        <div class="js_job_main_wrapper">
                            <div class="js_job_image_area">
                                <div class="js_job_image_wrapper mycompany">
                                    <?php
                                    if ($resume->photo != '') {
                                        $imgsrc = $this->config['data_directory'] . "/data/jobseeker/resume_" . $resume->id . "/photo/" . $resume->photo;
                                    } else {
                                        $imgsrc = "components/com_jsjobs/images/jsjobs_logo.png";
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
                                    <div class='js_job_data_2_wrapper'>
                                        <?php $link = 'index.php?option=com_jsjobs&c=resume&view=resume&layout=view_resume&nav=4&rd=' . $resume->resumealiasid . '&cat=' . $resume->cat_id . '&Itemid=' . $this->Itemid; ?>
                                        <a class="js_job_data_area_button" href="<?php echo $link ?>"><?php echo JText::_('View'); ?></a>
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
                                    echo JHtml::_('date', $resume->created, $this->config['date_format']) . "</span>";
                                    ?>
                                </div>
                            </div>
                        </div> 
                        <?php
                    }
                    $querystring = '&resumesubcat=' . $this->resume[0]->aliasid . '&Itemid=' . $this->Itemid;
                    ?>
                </div>
                <form action="<?php echo JRoute::_('index.php?option=com_jsjobs&c=resume&view=resume&layout=resume_bysubcategory&Itemid=' . $this->Itemid); ?>" method="post">
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
            <?php } else { // not allowed job posting 
                $this->jsjobsmessages->getAccessDeniedMsg('You are not allowed', 'You are not allow to view this page', 0);
            }
        } else { // no result found in this category 
            $this->jsjobsmessages->getAccessDeniedMsg('Could not find any matching results.', 'Could not find any matching results.', 0);
        }
        ?>	


        <script language="javascript">
            //showsavesearch(0); 
        </script>
        <?php
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
