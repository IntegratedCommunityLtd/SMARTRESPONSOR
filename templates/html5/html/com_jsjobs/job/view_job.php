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
 * File Name:   views/employer/tmpl/view_job.php
  ^
 * Description: template view for a job
  ^
 * History:     NONE
  ^
 */
defined('_JEXEC') or die('Restricted access');
global $mainframe;
$document = JFactory::getDocument();
if (JVERSION < 3) {
    JHtml::_('behavior.mootools');
    $document->addScript('components/com_jsjobs/js/jquery.js');
} else {
    JHtml::_('behavior.framework');
    JHtml::_('jquery.framework');
}
JHTML::_('behavior.modal');
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
    if (isset($this->job)) {//job summary table 
        //check which field is enabled or not
        require_once 'jobapply.php';
        $section_array = array();
        //requirement section
        if (
                (isset($this->fieldsordering['heighesteducation']) && $this->fieldsordering['heighesteducation'] == 1) ||
                (isset($this->fieldsordering['experience']) && $this->fieldsordering['experience'] == 1) ||
                (isset($this->fieldsordering['workpermit']) && $this->fieldsordering['workpermit'] == 1) ||
                (isset($this->fieldsordering['requiredtravel']) && $this->fieldsordering['requiredtravel'] == 1)
        )
            $section_array['requirement'] = 1;
        else
            $section_array['requirement'] = 0;

        ?>
        <div id="js_main_wrapper">
            <span class="js_controlpanel_section_title">
                <?php
                echo JText::_('Job Information');
                $days = $this->config['newdays'];
                $isnew = date("Y-m-d H:i:s", strtotime("-$days days"));
                if (isset($this->job)) {
                    if ($this->job->created > $isnew)
                        echo '<div class="js_job_new"><canvas class="newjob" width="20" height="20"></canvas>' . JText::_('New').'!' . '</div>';
                }
                ?>

            </span>
            <?php if($this->fieldsordering['jobtitle'] == 1 && $this->listjobconfig['lj_title'] == 1){ ?>
                <span class="js_job_title">
                    <?php
                    if (isset($this->job))
                        echo $this->job->title;
                    ?>
                </span>
            <?php } ?>
            <?php if($this->fieldsordering['company'] == 1 && $this->listjobconfig['lj_company'] == 1){ ?>
                <span class="js_controlpanel_section_title"><?php echo JText::_('Company Information'); ?></span>
                <?php if (isset($this->fieldsorderingcompany['logo']) && $this->fieldsorderingcompany['logo'] == 1) { ?>
                    <div class="js_job_company_logo">
                        <?php
                        if (!empty($this->job->companylogo)) {
                            if ($this->isjobsharing) {
                                $logourl = $this->job->companylogo;
                            } else {
                                $logourl = $this->config['data_directory'] . '/data/employer/comp_' . $this->job->companyid . '/logo/' . $this->job->companylogo;
                            }
                        } else {
                            $logourl = 'components/com_jsjobs/images/blank_logo.png';
                        }
                        ?>
                        <img class="js_job_company_logo" src="<?php echo $logourl; ?>" />
                    </div>
                <?php } ?>
                <div class="js_job_company_data">
                    <?php if (isset($this->fieldsorderingcompany['name']) && $this->fieldsorderingcompany['name'] == 1 && $this->config['comp_name'] == 1) { ?>
                        <div class="js_job_data_wrapper">
                            <span class="js_job_data_title"><?php echo JText::_('Company'); ?></span>
                            <span class="js_job_data_value">
                                <?php
                                if (isset($_GET['cat']))
                                    $jobcat = $_GET['cat'];
                                else
                                    $jobcat = null;
                                (isset($navcompany) && $navcompany == 41) ? $navlink = "&nav=41" : $navlink = "";
                                $link = 'index.php?option=com_jsjobs&c=company&view=company&layout=view_company' . $navlink . '&cd=' . $this->job->companyaliasid . '&cat=' . $this->job->jobcategory . '&Itemid=' . $this->Itemid;
                                ?>
                                <a class="js_job_company_anchor" href="<?php echo $link; ?>">
                                    <?php echo $this->job->companyname; ?>
                                </a>
                            </span>
                        </div>
                    <?php } ?>
                    <?php if (isset($this->fieldsorderingcompany['url']) && $this->fieldsorderingcompany['url'] == 1 && $this->config['comp_show_url'] == 1) { ?>
                        <div class="js_job_data_wrapper">
                            <span class="js_job_data_title"><?php echo JText::_('Website'); ?></span>
                            <span class="js_job_data_value">
                                <a class="js_job_company_anchor" href="<?php echo $this->job->companywebsite; ?>">
                                    <?php echo $this->job->companywebsite; ?>
                                </a>
                            </span>
                        </div>
                    <?php } ?>
                    <?php if (isset($this->fieldsorderingcompany['contactname']) && $this->fieldsorderingcompany['contactname'] == 1) { ?>
                        <div class="js_job_data_wrapper">
                            <span class="js_job_data_title"><?php echo JText::_('Contact Name'); ?></span>
                            <span class="js_job_data_value"><?php echo $this->job->companycontactname; ?></span>
                        </div>
                    <?php } ?>
                    <?php if (isset($this->fieldsorderingcompany['contactemail']) && $this->fieldsorderingcompany['contactemail'] == 1 && $this->config['comp_email_address'] == 1) { ?>
                        <div class="js_job_data_wrapper">
                            <span class="js_job_data_title"><?php echo JText::_('Contact Email'); ?></span>
                            <span class="js_job_data_value"><?php echo $this->job->companycontactemail; ?></span>
                        </div>
                    <?php } ?>
                    <?php if (isset($this->fieldsorderingcompany['since']) && $this->fieldsorderingcompany['since'] == 1) { ?>
                        <div class="js_job_data_wrapper">
                            <span class="js_job_data_title"><?php echo JText::_('Since'); ?></span>
                            <span class="js_job_data_value"><?php echo JHtml::_('date', $this->job->companysince, $this->config['date_format']); ?></span>
                        </div>
                    <?php } ?>
                </div>
                <span class="js_controlpanel_section_title"><?php echo JText::_('Job Information'); ?></span>
            <?php } ?>
            <?php if (isset($this->fieldsordering['jobtype']) && $this->fieldsordering['jobtype'] == 1 && $this->listjobconfig['lj_jobtype'] == 1) { ?>
                <div class="js_job_data_wrapper">
                    <span class="js_job_data_title"><?php echo JText::_('Job Type'); ?></span>
                    <span class="js_job_data_value"><?php echo JText::_($this->job->jobtypetitle); ?></span>
                </div>
            <?php } ?>
            <?php if (isset($this->fieldsordering['duration']) && $this->fieldsordering['duration'] == 1) { ?>
                <div class="js_job_data_wrapper">
                    <span class="js_job_data_title"><?php echo JText::_('Duration'); ?></span>
                    <span class="js_job_data_value"><?php echo JText::_($this->job->duration); ?></span>
                </div>
            <?php } ?>
            <?php if (isset($this->fieldsordering['zipcode']) && $this->fieldsordering['zipcode'] == 1) { ?>
                <div class="js_job_data_wrapper">
                    <span class="js_job_data_title"><?php echo JText::_('Zipcode'); ?></span>
                    <span class="js_job_data_value"><?php echo $this->job->zipcode; ?></span>
                </div>
            <?php } ?>
            <?php if (isset($this->fieldsordering['jobsalaryrange']) && $this->fieldsordering['jobsalaryrange'] == 1 && $this->listjobconfig['lj_salary'] == 1) { ?>
                <?php if ($this->job->hidesalaryrange != 1) { // show salary ?>
                    <div class="js_job_data_wrapper">
                        <span class="js_job_data_title"><?php echo JText::_('Salary Range'); ?></span>
                        <span class="js_job_data_value">
                            <?php
                            $salary = $this->getJSModel('common')->getSalaryRangeView($this->job->symbol,$this->job->salaryfrom,$this->job->salaryto,$this->job->salarytype,$this->config['currency_align']);
                            echo $salary;
                            ?>
                        </span>
                    </div>
                <?php } ?>
            <?php } ?>
            <?php if (isset($this->fieldsordering['department']) && $this->fieldsordering['department'] == 1) { ?>
                <div class="js_job_data_wrapper">
                    <span class="js_job_data_title"><?php echo JText::_(' Department'); ?></span>
                    <span class="js_job_data_value"><?php echo $this->job->departmentname; ?></span>
                </div>
            <?php } ?>
            <?php if (isset($this->fieldsordering['jobcategory']) && $this->fieldsordering['jobcategory'] == 1 && $this->listjobconfig['lj_category'] == 1) { ?>
                <div class="js_job_data_wrapper">
                    <span class="js_job_data_title"><?php echo JText::_('Category'); ?></span>
                    <span class="js_job_data_value"><?php echo JText::_($this->job->cat_title); ?></span>
                </div>
            <?php } ?>
            <?php if (isset($this->fieldsordering['subcategory']) && $this->fieldsordering['subcategory'] == 1) { ?>
                <div class="js_job_data_wrapper">
                    <span class="js_job_data_title"><?php echo JText::_('Sub Category'); ?></span>
                    <span class="js_job_data_value"><?php echo JText::_($this->job->subcategory); ?></span>
                </div>
            <?php } ?>
            <?php if (isset($this->fieldsordering['jobshift']) && $this->fieldsordering['jobshift'] == 1) { ?>
                <div class="js_job_data_wrapper">
                    <span class="js_job_data_title"><?php echo JText::_('Shift'); ?></span>
                    <span class="js_job_data_value"><?php echo JText::_($this->job->shifttitle); ?></span>
                </div>
            <?php } ?>
            <?php
            if ($this->isjobsharing != "") {
                if (is_array($this->userfields)) {
                    for ($k = 0; $k < 15; $k++) {
                        $field_title = 'fieldtitle_' . $k;
                        $field_value = 'fieldvalue_' . $k;
                        if(!empty($this->userfields[$field_title]) && !empty($this->userfields[$field_value])){
                            echo '<div class="js_job_data_wrapper">
                                    <span class="js_job_data_title">' . $this->userfields[$field_title] . '</span>
                                    <span class="js_job_data_value">' . $this->userfields[$field_value] . '</span>
                                </div>';
                        }
                    }
                }
            } else {
                $i = 0;
                foreach ($this->userfields as $ufield) {
                    if (isset($this->fieldsordering[$ufield[0]->id]) && $this->fieldsordering[$ufield[0]->id] == 1) {
                        $userfield = $ufield[0];
                        $i++;
                        echo '<div class="js_job_data_wrapper">
                            <span class="js_job_data_title">' . $userfield->title . '</span>
                            <span class="js_job_data_value">';
                        if ($userfield->type == "checkbox") {
                            if (isset($ufield[1])) {
                                $fvalue = $ufield[1]->data;
                                $userdataid = $ufield[1]->id;
                            } else {
                                $fvalue = "";
                                $userdataid = "";
                            }
                            if ($fvalue == '1')
                                $fvalue = "True";
                            else
                                $fvalue = "false";
                        }elseif ($userfield->type == "select") {
                            if (isset($ufield[2])) {
                                $fvalue = $ufield[2]->fieldtitle;
                                $userdataid = $ufield[2]->id;
                            } else {
                                $fvalue = "";
                                $userdataid = "";
                            }
                        } else {
                            if (isset($ufield[1])) {
                                $fvalue = $ufield[1]->data;
                                $userdataid = $ufield[1]->id;
                            } else {
                                $fvalue = "";
                                $userdataid = "";
                            }
                        }
                        echo $fvalue . '</span>
                            </div>';
                    }
                }
            }
            ?>
            <?php if($this->listjobconfig['lj_created'] == 1){ ?>
                <div class="js_job_data_wrapper">
                    <span class="js_job_data_title"><?php echo JText::_('Posted'); ?></span>
                    <span class="js_job_data_value"><?php echo JHtml::_('date', $this->job->created, $this->config['date_format']); ?></span>
                </div>
            <?php } ?>
            <?php if ($section_array['requirement'] == 1) { ?>
                <span class="js_controlpanel_section_title"><?php echo JText::_('Requirements'); ?></span>
                <?php
                if ($this->job->iseducationminimax == 1) {
                    if ($this->job->educationminimax == 1)
                        $title = JText::_('Minimum Education');
                    else
                        $title = JText::_('Maximum Education');
                    $educationtitle = JText::_($this->job->educationtitle);
                }else {
                    $title = JText::_('Education');
                    $educationtitle = JText::_($this->job->mineducationtitle) . ' - ' . JText::_($this->job->maxeducationtitle);
                }
                ?>
                <?php if (isset($this->fieldsordering['heighesteducation']) && $this->fieldsordering['heighesteducation'] == 1) { ?>
                    <div class="js_job_data_wrapper">
                        <span class="js_job_data_title"><?php echo JText::_($title); ?></span>
                        <span class="js_job_data_value"><?php echo JText::_($educationtitle); ?></span>
                    </div>
                    <div class="js_job_data_wrapper">
                        <span class="js_job_data_title"><?php echo JText::_('Degree Title'); ?></span>
                        <span class="js_job_data_value"><?php echo JText::_($this->job->degreetitle); ?></span>
                    </div>
                <?php } ?>
                <?php
                if ($this->job->isexperienceminimax == 1) {
                    if ($this->job->experienceminimax == 1)
                        $title = JText::_('Minimum Experience');
                    else
                        $title = JText::_('Maximum Experience');
                    $experiencetitle = $this->job->experiencetitle;
                }else {
                    $title = JText::_('Experience');
                    $experiencetitle = $this->job->minexperiencetitle . ' - ' . $this->job->maxexperiencetitle;
                }
                if ($this->job->experiencetext)
                    $experiencetitle .= ' (' . $this->job->experiencetext . ')';
                ?>
                <?php if (isset($this->fieldsordering['experience']) && $this->fieldsordering['experience'] == 1) { ?>
                    <div class="js_job_data_wrapper">
                        <span class="js_job_data_title"><?php echo $title; ?></span>
                        <span class="js_job_data_value"><?php echo JText::_($experiencetitle); ?></span>
                    </div>
                <?php } ?>
                <?php if(isset($this->fieldsordering['age']) && $this->fieldsordering['age'] == 1){ ?>
                    <div class="js_job_data_wrapper">
                        <span class="js_job_data_title"><?php echo JText::_('Age'); ?></span>
                        <span class="js_job_data_value"><?php echo JText::_($this->job->agefromtitle). ' - '.JText::_($this->job->agetotitle); ?></span>
                    </div>
                <?php } ?>
                <?php if (isset($this->fieldsordering['workpermit']) && $this->fieldsordering['workpermit'] == 1) { ?>
                    <div class="js_job_data_wrapper">
                        <span class="js_job_data_title"><?php echo JText::_('Work Permit'); ?></span>
                        <span class="js_job_data_value"><?php echo $this->job->workpermitcountry; ?></span>
                    </div>
                <?php } ?>
                <?php
                if (isset($this->fieldsordering['requiredtravel']) && $this->fieldsordering['requiredtravel'] == 1) {
                    switch ($this->job->requiredtravel) {
                        case 1: $requiredtraveltitle = JText::_('Not Required');
                            break;
                        case 2: $requiredtraveltitle = "25%";
                            break;
                        case 3: $requiredtraveltitle = "50%";
                            break;
                        case 4: $requiredtraveltitle = "75%";
                            break;
                        case 5: $requiredtraveltitle = "100%";
                            break;
                        default: $requiredtraveltitle = '';
                            break;
                    }
                    ?>
                    <div class="js_job_data_wrapper">
                        <span class="js_job_data_title"><?php echo JText::_('Required Travel'); ?></span>
                        <span class="js_job_data_value"><?php echo JText::_($requiredtraveltitle); ?></span>
                    </div>
                <?php } ?>
            <?php } ?>
            <span class="js_controlpanel_section_title"><?php echo JText::_('Job Status'); ?></span>
            <?php if (isset($this->fieldsordering['jobstatus']) && $this->fieldsordering['jobstatus'] == 1 && $this->listjobconfig['lj_jobstatus'] == 1) { ?>
                <div class="js_job_data_wrapper">
                    <span class="js_job_data_title"><?php echo JText::_('Job Status'); ?></span>
                    <span class="js_job_data_value"><?php echo JText::_($this->job->jobstatustitle); ?></span>
                </div>
            <?php } ?>
            <div class="js_job_data_wrapper">
                <span class="js_job_data_title"><?php echo JText::_('Start Publishing'); ?></span>
                <span class="js_job_data_value"><?php echo JHtml::_('date', $this->job->startpublishing, $this->config['date_format']); ?></span>
            </div>
            <?php if (isset($this->fieldsordering['noofjobs']) && $this->fieldsordering['noofjobs'] == 1 && $this->listjobconfig['lj_noofjobs'] == 1) { ?>
                <div class="js_job_data_wrapper">
                    <span class="js_job_data_title"><?php echo JText::_('No Of Jobs'); ?></span>
                    <span class="js_job_data_value"><?php echo JText::_($this->job->noofjobs); ?></span>
                </div>
            <?php } ?>
            <div class="js_job_data_wrapper">
                <span class="js_job_data_title"><?php echo JText::_('Stop Publishing'); ?></span>
                <span class="js_job_data_value"><?php echo JHtml::_('date', $this->job->stoppublishing, $this->config['date_format']); ?></span>
            </div>
            <span class="js_controlpanel_section_title"><?php echo JText::_('Location'); ?></span>            
            <?php if ($this->fieldsordering['city'] == '1' && $this->listjobconfig['lj_city'] == 1) { ?>
                <div class="js_job_full_width_data">
                    <?php if ($this->job->multicity != '') echo $this->job->multicity; ?>
                </div>
            <?php } ?>
            <?php if (isset($this->fieldsordering['map']) && $this->fieldsordering['map'] == 1) { ?>
                <div class="js_job_full_width_data">
                    <div id="map"><div id="map_container"></div></div>
                    <input type="hidden" id="longitude" name="longitude" value="<?php if (isset($this->job)) echo $this->job->longitude; ?>"/>
                    <input type="hidden" id="latitude" name="latitude" value="<?php if (isset($this->job)) echo $this->job->latitude; ?>"/>
                </div>
            <?php } ?>
            <?php if (isset($this->fieldsordering['video']) && $this->fieldsordering['video'] == 1) { ?>
                <?php if ($this->job->video) { ?>
                    <span class="js_controlpanel_section_title"><?php echo JText::_('Video'); ?></span>
                    <div class="js_job_full_width_data">
                        <iframe title="YouTube video player" width="480" height="390" 
                                src="http://www.youtube.com/embed/<?php echo $this->job->video; ?>" frameborder="0" allowfullscreen>
                        </iframe>
                    </div>
                <?php } ?>
            <?php } ?>
            <?php if($this->listjobconfig['lj_description'] == 1){ ?>
                <span class="js_controlpanel_section_title"><?php echo JText::_('Description'); ?></span>
                <div class="js_job_full_width_data"><?php echo $this->job->description; ?></div>
            <?php } ?>
            <?php if (isset($this->fieldsordering['agreement']) && $this->fieldsordering['agreement'] == 1) { ?>
                <span class="js_controlpanel_section_title"><?php echo JText::_('Agreement'); ?></span>
                <div class="js_job_full_width_data"><?php echo $this->job->agreement; ?></div>
            <?php } ?>
            <?php if (isset($this->fieldsordering['qualifications']) && $this->fieldsordering['qualifications'] == 1) { ?>
                <span class="js_controlpanel_section_title"><?php echo JText::_('Qualifications'); ?></span>
                <div class="js_job_full_width_data"><?php echo $this->job->qualifications; ?></div>
            <?php } ?>
            <?php if (isset($this->fieldsordering['prefferdskills']) && $this->fieldsordering['prefferdskills'] == 1) { ?>
                <span class="js_controlpanel_section_title"><?php echo JText::_('Preferred Skills'); ?></span>
                <div class="js_job_full_width_data"><?php echo $this->job->prefferdskills; ?></div>
            <?php } ?>
            <div class="js_job_apply_button">
                <?php $link = 'index.php?option=com_jsjobs&c=jobapply&view=jobapply&layout=job_apply&bd=' . $this->job->jobaliasid . '&Itemid=' . $this->Itemid; ?>
                <a class="js_job_button" data-jobapply="jobapply" data-jobid="<?php echo $this->job->jobaliasid; ?>" href="#" ><strong><?php echo JText::_('Apply Now'); ?></strong></a>           
            </div>
        </div>
        <?php } else {
                $this->jsjobsmessages->getAccessDeniedMsg('Could not find any matching results.', 'Could not find any matching results.', 0);
            }
}//ol
?>

<style type="text/css">
    div#map_container{ width:100%; height:350px; }
</style>
<?php $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://"; ?>
<script type="text/javascript" src="<?php echo $protocol; ?>maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
                window.onload = loadMap();
                function loadMap() {
                    var latedit = [];
                    var longedit = [];

                    var longitude = jQuery('#longitude').val();
                    var latitude = jQuery('#latitude').val();
                    if (typeof (longitude) != "undefined" && typeof (latitude) != "undefined") {
                        latedit = latitude.split(",");
                        longedit = longitude.split(",");
                        if (latedit != '' && longedit != '') {
                            for (var i = 0; i < latedit.length; i++) {
                                var latlng = new google.maps.LatLng(latedit[i], longedit[i]);
                                zoom = 4;
                                var myOptions = {
                                    zoom: zoom,
                                    center: latlng,
                                    mapTypeId: google.maps.MapTypeId.ROADMAP
                                };
                                if (i == 0)
                                    var map = new google.maps.Map(document.getElementById("map_container"), myOptions);
                                var lastmarker = new google.maps.Marker({
                                    postiion: latlng,
                                    map: map,
                                });
                                var marker = new google.maps.Marker({
                                    position: latlng,
                                    zoom: zoom,
                                    map: map,
                                    visible: true,
                                });
                                marker.setMap(map);
                            }
                        }

                    }
                    loadMap

                }
                window.onload = function () {

                    if (document.getElementById('jobseeker_fb_comments') != null) {
                        var myFrame = document.getElementById('jobseeker_fb_comments');
                        if (myFrame != null)
                            myFrame.src = 'http://www.facebook.com/plugins/comments.php?href=' + location.href;
                    }
                    if (document.getElementById('employer_fb_comments') != null) {
                        var myFrame = document.getElementById('employer_fb_comments');
                        if (myFrame != null)
                            myFrame.src = 'http://www.facebook.com/plugins/comments.php?href=' + location.href;
                    }
                }
</script>
<?php
$document = JFactory::getDocument();
$document->addScript('components/com_jsjobs/js/canvas_script.js');
?>