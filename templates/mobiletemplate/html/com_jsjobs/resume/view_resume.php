<?php
/**
 * @Copyright Copyright (C) 2009-2011
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
  + Created by:          Ahmad Bilal
 * Company:     Buruj Solutions
  + Contact:     www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:  Jan 11, 2009
  ^
  + Project:         JS Jobs
 * File Name:   views/jobseeker/tmpl/view_resume.php
  ^
 * Description: template for view resume
  ^
 * History:     NONE
  ^
 */
defined('_JEXEC') or die('Restricted access');

$document = JFactory::getDocument();
if (JVERSION < 3) {
    JHtml::_('behavior.mootools');
    $document->addScript('components/com_jsjobs/js/jquery.js');
} else {
    JHtml::_('behavior.framework');
    JHtml::_('jquery.framework');
}

if (!empty($this->cvids)) {
    $jobaliasid = $this->jobaliasid;
    $rd = $this->resume->id;
    $nav = $this->nav; // hardcode for the employer resumes navigations.
    $sort = $this->sortby;
    $index_cvid = -1;
    foreach ($this->cvids AS $key => $cvid) {
        if ($rd == $cvid->cvid) {
            $index_cvid = $key;
            break;
        }
    }
    if ($index_cvid != -1) {
        $rd = $this->cvids[$index_cvid]->cvid;
        if (isset($this->cvids[($index_cvid - 1)]))
            $pre_rd_link = 'index.php?option=com_jsjobs&c=resume&view=resume&layout=view_resume&nav=2&rd=' . $this->cvids[($index_cvid - 1)]->cvid . '&bd=' . $jobaliasid . '&sortby=' . $sort . '&ta=' . $this->tabaction . '&Itemid=' . $this->Itemid;

        if (isset($this->cvids[($index_cvid + 1)]))
            $nex_rd_link = 'index.php?option=com_jsjobs&c=resume&view=resume&layout=view_resume&nav=2&rd=' . $this->cvids[($index_cvid + 1)]->cvid . '&bd=' . $jobaliasid . '&sortby=' . $sort . '&ta=' . $this->tabaction . '&Itemid=' . $this->Itemid;
    }
}

if (isset($this->isadmin) AND $this->isadmin == 1) {
    $validatePermissions = 0;
} else {
    $validatePermissions = 1;
}
?>
<?php $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://"; ?>
<script type="text/javascript" src="<?php echo $protocol; ?>maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
    function toggleMap(id) {
        var containerWidth = jQuery("#address_" + id).width();
        var printResume = <?php
                            if (isset($this->printresume) && !empty($this->printresume)) {
                                echo $this->printresume;
                            } else {
                                echo 0;
                            }
                            ?>;
        if (printResume == 1) {
            jQuery("div.map_view").css('width', (containerWidth + 230) + 'px');
            jQuery("div.map_view").css('overflow', 'hidden');
        } else {
            jQuery("div.map_view").css('width', containerWidth);
        }
        if (jQuery("#map_container_" + id).is(':visible')) {
            jQuery("#map_container_" + id).hide('slow');
            jQuery("#address_" + id).find("div.map-toggler").find("span img").attr("src", "<?php echo JURI::root(); ?>components/com_jsjobs/images/show-map.png");
            jQuery("#address_" + id).find("div.map-toggler").find("span.text").html("<?php echo JText::_('Show map'); ?>");
        } else {
            jQuery("#map_container_" + id).show('slow');
            jQuery("#address_" + id).find("div.map-toggler").find("span img").attr("src", "<?php echo JURI::root(); ?>components/com_jsjobs/images/hide-map.png");
            jQuery("#address_" + id).find("div.map-toggler").find("span.text").html("<?php echo JText::_('Hide map'); ?>");
            loadMap(id);
        }
    }
    function loadMap(addressid) {
        var default_latitude = document.getElementById('latitude_' + addressid).value;
        var default_longitude = document.getElementById('longitude_' + addressid).value;

        var latlng = new google.maps.LatLng(default_latitude, default_longitude);
        zoom = 10;
        var myOptions = {
            zoom: zoom,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("map_view_" + addressid), myOptions);
        var lastmarker = new google.maps.Marker({
            postiion: latlng,
            map: map,
        });
        var marker = new google.maps.Marker({
            position: latlng,
            map: map,
        });
        marker.setMap(map);
        lastmarker = marker;

        google.maps.event.addListener(map, "click", function (e) {
            return false;
        });
    }
</script>
<?php
if (isset($this->printresume) AND $this->printresume != 1) {
    ?>
    <div id="js_menu_wrapper">
        <?php
        if (isset($this->jobseekerlinks)) {
            if (sizeof($this->jobseekerlinks) != 0) {
                foreach ($this->jobseekerlinks as $lnk) { ?>                     
                    <a class="js_menu_link <?php if ($lnk[2] == 'job') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
                    <?php
                }
            }
        }
        if (isset($this->employerlinks)) {
            if (sizeof($this->employerlinks) != 0) {
                foreach ($this->employerlinks as $lnk) {
                    ?>
                    <a class="js_menu_link <?php if ($lnk[2] == 'job') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
                    <?php
                }
            }
        }
        ?>
    </div>
    <?php
}
if ($this->config['offline'] == '1') {
    $this->jsjobsmessages->getSystemOfflineMsg($this->config);
} else {
    $printform = 1;
    $printform = 1;
    $canprint = 1; //canprint if the message is already print or not
    if ((isset($this->resume)) && ($this->resume->id != 0)) { // not new form
        if ($this->resume->status == 1) { // Employment Application is actve
            $printform = 1;
        } elseif ($this->resume->status == 0) { // not allowed job posting
            $printform = 0;
            $canprint = 0;
            $this->jsjobsmessages->getAccessDeniedMsg('Resume Waiting For Approval', 'Resume Waiting For Approval', 0);
        } else { // not allowed job posting
            $printform = 0;
            $canprint = 0;
            $this->jsjobsmessages->getAccessDeniedMsg('Resume Rejected', 'Resume Rejected', 0);
        }
        if ($this->nav == 1) {
            if ($this->resume->uid != $this->uid) { // job seeker can't see other job seeker resume
                $printform = 0;
                $canprint = 0;
                $this->jsjobsmessages->getAccessDeniedMsg('You are not allowed', 'You are not allow to view this page', 0);
            }
        }
    } elseif ($this->canview == 0) {
        $printform = 0;
        $canprint = 0;
        $this->jsjobsmessages->getAccessDeniedMsg('You can not view resume in detail', 'You can not view resume in detail', 0);
    }

    if ($canprint == 1)
        if ($printform == 1) {
            if (isset($this->resume)) {
                ?>
                <div id="js_main_wrapper">
                        <?php if ($validatePermissions == 1) { ?>
                        <span class="js_controlpanel_section_title">
                            <?php echo JText::_('View Resume'); ?>
                    <?php if (($this->nav == 2) || ($this->nav == 3) || ($this->nav == 5)) { ?>
                        <?php $jobid = $this->jobaliasid; ?>
                                <a class="js_resume_pdf_link" target="_blank" href="<?php echo JURI::root(); ?>index.php?option=com_jsjobs&c=resume&view=output&layout=resumepdf&format=pdf&rd=<?php echo $this->resume->resumealiasid; ?>&bd=<?php echo $this->jobaliasid; ?>&ms=<?php echo $this->ms; ?>">
                                    <img src="<?php echo JURI::root(); ?>/components/com_jsjobs/images/pdf.png" width="24" height="24" />
                                </a>
                        <?php } ?>
                        </span>
                        <?php
                    }

                    function getResumeUserField($thisjob, $field) {
                        $data = '';
                        $isodd = 0;
                        if ($thisjob->isjobsharing != "") {
                            if (is_object($thisjob->userfields)) {
                                for ($k = 0; $k < 15; $k++) {
                                    $isodd = 1 - $isodd;
                                    $field_title = 'fieldtitle_' . $k;
                                    $field_value = 'fieldvalue_' . $k;
                                    if (!empty($thisjob->userfields->$field_title) && $thisjob->userfields->$field_title != null) {
                                        $data .= '  <div class="js-row no-margin">
                                        <div class="js-resume-data-title js-col-lg-3 js-col-md-3 js-col-xs-3 no-padding">
                                            <span>' . $thisjob->userfields->$field_title . '</span>
                                        </div>
                                        <div class="js-resume-data-value js-col-lg-8 js-col-md-8 js-col-xs-8 no-padding">
                                            ' . $thisjob->userfields->$field_value . '
                                        </div>
                                    </div>';
                                    }
                                }
                            }
                        } else {
                            $isodd = 1;
                            $i = 0;
                            foreach ($thisjob->userfields as $ufield) {
                                if ($field->published == 1 && $field->field == $ufield[0]->id) {
                                    $isodd = 1 - $isodd;
                                    $userfield = $ufield[0];
                                    $i++;
                                    $data .= ' <div class="js-row no-margin">
                                        <div class="js-resume-data-title js-col-lg-3 js-col-md-3 js-col-xs-3 no-padding">
                                            <span>' . $userfield->title . '</span>
                                        </div>';
                                    if (isset($ufield[1]) && is_array($ufield[1]) && !empty($ufield[1])) {
                                        foreach ($ufield[1] AS $u_data) {
                                            if ($userfield->type == "checkbox") {
                                                $fvalue = $u_data->data;
                                                $fvalue = ($fvalue == '1') ? "True" : "false";
                                            } elseif ($userfield->type == "select") {
                                                if (isset($ufield[2])) {
                                                    $fvalue = $ufield[2]->fieldtitle;
                                                } else {
                                                    $fvalue = "";
                                                }
                                            } else {
                                                $fvalue = $u_data->data;
                                            }
//                                            if ($subreferenceid != null) {
//                                                if ($subreferenceid != $u_data->subreferenceid)
//                                                    $fvalue = '';
//                                                else
//                                                    break;
//                                            }
                                        }
                                    }else {
                                        $fvalue = '';
                                    }
                                    $data .= ' <div class="js-resume-data-value js-col-lg-8 js-col-md-8 js-col-xs-8 no-padding">
                                    ' . $fvalue . '
                                </div>
                            </div>';
                                }
                            }
                        }
                        return $data;
                    }

                    function getResumeUserFieldForAddress($thisjob, $field) {
                        $data = '';
                        $isodd = 0;
                        if ($thisjob->isjobsharing != "") {
                            if (is_object($thisjob->userfields)) {
                                for ($k = 0; $k < 15; $k++) {
                                    $isodd = 1 - $isodd;
                                    $field_title = 'fieldtitle_' . $k;
                                    $field_value = 'fieldvalue_' . $k;
                                    if (!empty($thisjob->userfields->$field_title) && $thisjob->userfields->$field_title != null) {
                                        $data .= '  <div class="js-col-lg-11 js-col-md-11 js-col-xs-11 no-padding">
                                        <span class="addressDetails">' . $thisjob->userfields->$field_title . ' ' . $thisjob->userfields->$field_value . '</span>
                                    </div>';
                                    }
                                }
                            }
                        } else {
                            $isodd = 1;
                            $i = 0;
                            foreach ($thisjob->userfields as $ufield) {
                                if ($field->published == 1 && $field->field == $ufield[0]->id) {
                                    $isodd = 1 - $isodd;
                                    $userfield = $ufield[0];
                                    $i++;
                                    $data .= '  <div class="js-col-lg-11 js-col-md-11 js-col-xs-11 no-padding">
                                    <span class="addressDetails">' . $userfield->title . ' ';
                                    if (isset($ufield[1]) && is_array($ufield[1]) && !empty($ufield[1])) {
                                        foreach ($ufield[1] AS $u_data) {
                                            if ($userfield->type == "checkbox") {
                                                $fvalue = $u_data->data;
                                                $fvalue = ($fvalue == '1') ? "True" : "false";
                                            } elseif ($userfield->type == "select") {
                                                if (isset($ufield[2])) {
                                                    $fvalue = $ufield[2]->fieldtitle;
                                                } else {
                                                    $fvalue = "";
                                                }
                                            } else {
                                                $fvalue = $u_data->data;
                                            }
//                                            if ($subreferenceid != null) {
//                                                if ($subreferenceid != $u_data->subreferenceid)
//                                                    $fvalue = '';
//                                                else
//                                                    break;
//                                            }
                                        }
                                    }else {
                                        $fvalue = '';
                                    }
                                    $data .= $fvalue . '
                                </span>
                            </div>';
                                }
                            }
                        }
                        return $data;
                    }

                    function getResumeField($fieldtitle, $fieldvalue, $published) {
                        $data = '';
                        if ($published == 1) {
                            $data .= '
                    <div class="js-row no-margin">
                        <div class="js-resume-data-title js-col-lg-3 js-col-md-3 js-col-xs-3 no-padding">
                            <span>' . $fieldtitle . '</span>
                        </div>
                        <div class="js-resume-data-value js-col-lg-8 js-col-md-8 js-col-xs-8 no-padding">
                            <span>' . $fieldvalue . '</span>
                        </div>
                    </div>';
                        }
                        return $data;
                    }

                    if (isset($this->printresume) AND $this->printresume != 1) {
                        ?>
                    <?php if ((($this->nav == 2) || ($this->nav == 3) || ($this->nav == 5))) { ?>
                            <span class="js_controlpanel_section_title">
                                <?php if(isset($pre_rd_link)){ ?>
                                <span class="js_resume_prev"><a href="<?php echo $pre_rd_link; ?>" ><?php echo "<<&nbsp;&nbsp;" . JText::_('Previous Resume'); ?></a></span>
                                <?php } ?>
                                <?php if(isset($nex_rd_link)){ ?>
                                <span class="js_resume_next"><a href="<?php echo $nex_rd_link; ?>" ><?php echo JText::_('Next Resume') . "&nbsp;&nbsp;>>"; ?></a></span>
                                <?php } ?>
                            </span>
                            <?php
                        }
                    }
                    ?>
                    <div class="js-row no-margin">
                        <?php
                        $section_personal = 0;
                        $first_name = 0;
                        $middle_name = 0;
                        $last_name = 0;
                        $email_address = 0;
                        $cell = 0;
                        $photo = 0;
                        $section_moreoptions = 0;
                        for ($i = 0; $i < COUNT($this->fieldsordering['personal']); $i++) {
                            $field = $this->fieldsordering['personal'][$i];
                            switch ($field->field) {
                                case "section_personal":
                                    $section_personal = ($field->published == 1) ? 1 : 0;
                                    unset($this->fieldsordering['personal'][$i]);
                                    break;
                                case "first_name":
                                    $first_name = ($field->published == 1) ? 1 : 0;
                                    unset($this->fieldsordering['personal'][$i]);
                                    break;
                                case "middle_name":
                                    $middle_name = ($field->published == 1) ? 1 : 0;
                                    unset($this->fieldsordering['personal'][$i]);
                                    break;
                                case "last_name":
                                    $last_name = ($field->published == 1) ? 1 : 0;
                                    unset($this->fieldsordering['personal'][$i]);
                                    break;
                                case "email_address":
                                    $email_address = ($field->published == 1) ? 1 : 0;
                                    unset($this->fieldsordering['personal'][$i]);
                                    break;
                                case "cell":
                                    $cell = ($field->published == 1) ? 1 : 0;
                                    unset($this->fieldsordering['personal'][$i]);
                                    break;
                                case "photo":
                                    $photo = ($field->published == 1) ? 1 : 0;
                                    unset($this->fieldsordering['personal'][$i]);
                                    break;
                                case "section_moreoptions":
                                    $section_moreoptions = ($field->published == 1) ? 1 : 0;
                                    unset($this->fieldsordering['personal'][$i]);
                                    break;
                            }
                        }
                        $data = '';
                        if ($section_personal == 1) {
                            $data .= '
                                    <a id="personalSectionAnchor">
                                        <div class="js-row no-margin">
                                            <div class="js-resume-section-title js-col-lg-12 js-col-md-12 js-col-xs-12">
                                                <img src="' . JURI::root() . '/components/com_jsjobs/images/personal-info.png"/>
                                                <span>' . JText::_("Personal Information") . '</span>
                                            </div>
                                        </div>
                                    </a>
                            ';
                        }
                        $data .= '
                            <div id="resumeFormContainer" class="js-resume-section-body personal-section js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                <div id="js-resume-section-view js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                    <div class="js-resume-section-view js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                        <div class="js-resume-profile js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                            <div class="js-col-lg-3 js-col-md-3 js-col-xs-12 no-padding">';
                        if ($photo == 1) {
                            if (!empty($this->resume->photo)) {
                                if(isset($this->resume->image_path)){
                                    $data .= '<img class="avatar" height="150" width="150" src="' . $this->resume->image_path . '"  />';
                                }else{
                                    $data .= '<img class="avatar" height="150" width="150" src="' . JURI::root() . $this->config['data_directory'] . '/data/jobseeker/resume_' . $this->resume->id . '/photo/' . $this->resume->photo . '"  />';
                                }
                                
                            } else {
                                $data .= '<img class="avatar" src="' . JURI::root() . '/components/com_jsjobs/images/jobseeker.png"  />';
                            }
                        }
                        $data .= '
                                            </div>
                                            <div class="js-resume-profile-info js-col-lg-8 js-col-md-8 js-col-xs-8 no-padding">
                                                <div class="profile-name-outer js-row no-margin">
                                                    <div class="js-resume-profile-name js-col-lg-11 js-col-md-11 js-col-xs-11 no-padding">
                                                        <span>';
                        if ($first_name == 1) {
                            $data .= $this->resume->first_name . ' ';
                        }
                        if ($middle_name == 1) {
                            $data .= $this->resume->middle_name . ' ';
                        }
                        if ($last_name == 1) {
                            $data .= $this->resume->last_name;
                        }
                        $data .= '
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="js-resume-profile-email js-row no-margin">
                                                    <span>';
                        if ($email_address == 1) {
                            $data .= $this->resume->email_address;
                        }
                        $data .= '
                                                    </span>
                                                </div>
                                                <div class="js-resume-profile-cell js-row no-margin">
                                                    <span>';
                        if ($cell == 1) {
                            $data .= $this->resume->cell;
                        }
                        $data .= '
                                                    </span>
                                                </div>
                                            </div>
                                        </div>';
                        if (isset($this->printresume) AND $this->printresume == 1) {
                            $data .= '<div class="js-row no-margin"></div>';
                        }
                        $data .= '
                                        <div class="js-resume-data js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">';
                        foreach ($this->fieldsordering['personal'] as $field) {
                            switch ($field->field) {
                                case "application_title":
                                    $data .= getResumeField(JText::_('Application Title'), $this->resume->application_title, $field->published);
                                    break;
                                case "nationality":
                                    $data .= getResumeField($this, JText::_('Nationality'), $this->resume->nationalitycountry, $field->published);
                                    break;
                                case "resumefiles":
                                    $data .= '
                                                        <div class="js-row no-margin">
                                                            <div class="js-resume-data-title js-col-lg-3 js-col-md-3 js-col-xs-3 no-padding">
                                                                <span>' . JText::_('Resume Files') . '</span>
                                                            </div>
                                                            <div id="resumeFilesList" class="js-resume-data-value filesList js-col-lg-8 js-col-md-8 js-col-xs-8 no-padding">';
                                    if(isset($this->resumefiles)){
                                        if (isset($this->printresume) && $this->printresume == 1) {
                                            $data .= '<ul>';
                                        } else {
                                            $data .= '<ul><a title="'.JText::_('Download all').'" class="zip-downloader" href="' . JURI::root() . 'index.php?option=com_jsjobs&c=resume&task=getallresumefiles&resumeid=' . $this->resume->id . '&sr=1" onclick="" target="_blank"><img src="' . JURI::root() . 'components/com_jsjobs/images/download-all.png"></a>';
                                        }
                                        foreach($this->resumefiles AS $file){
                                            if(!($file instanceof Object)){
                                                $file = (Object) $file;
                                            }
                                            $selectedFilename = substr($file->filename, 0, 4);
                                            $fileExt = substr($file->filename, strrpos($file->filename, '.') + 1);
                                            $data .= '<li id="' . $file->id . '" class="selectedFile" title="' . $file->filename . '">' . $selectedFilename . '.. .' . $fileExt;
                                            if ($this->printresume != 1) {
                                                $data .= '<a target="_blank" href="' . $file->filepath . '" alt=""><img src="' . JURI::root() . 'components/com_jsjobs/images/download.png" height="15px" width="15px" /></a></li>';
                                            }
                                        }
                                        $data .= '</ul>';
                                    }
                                    $data .= '
                                                            </div>
                                                        </div>';
                                    break;
                                case "date_of_birth":
                                    if($this->resume->date_of_birth == '0000-00-00 00:00:00')
                                        $date = '';
                                    else
                                        $date = JHtml::_('date', $this->resume->date_of_birth, $this->config['date_format']);
                                    $data .= getResumeField(JText::_('Date Of Birth'), $date, $field->published);
                                    break;
                                case "gender":
                                    $gender = ($this->resume->gender == 1) ? JText::_('Male') : JText::_('Female');
                                    $data .= getResumeField(JText::_('Gender'), $gender, $field->published);
                                    break;
                                case "iamavailable":
                                    $available = ($this->resume->iamavailable == 1) ? JText::_('Yes') : JText::_('No');
                                    $data .= getResumeField(JText::_('I Am Available'), $available, $field->published);
                                    break;
                                case "searchable":
                                    $searchable = ($this->resume->searchable == 1) ? JText::_('Yes') : JText::_('No');
                                    $data .= getResumeField(JText::_('Searchable'), $searchable, $field->published);
                                    break;
                                case "home_phone":
                                    $data .= getResumeField(JText::_('Home Phone'), $this->resume->home_phone, $field->published);
                                    break;
                                case "work_phone":
                                    $data .= getResumeField(JText::_('Work Phone'), $this->resume->work_phone, $field->published);
                                    break;
                                case "job_category":
                                    $data .= getResumeField(JText::_('Category'), $this->resume->categorytitle, $field->published);
                                    break;
                                case "job_subcategory":
                                    $data .= getResumeField(JText::_('Sub Category'), $this->resume->subcategorytitle, $field->published);
                                    break;
                                case "salary":
                                    $salary = $this->getJSModel('common')->getSalaryRangeView($this->resume->symbol,$this->resume->rangestart,$this->resume->rangeend,$this->resume->salarytype,$this->config['currency_align']);
                                    $data .= getResumeField(JText::_('Salary'), $salary, $field->published);
                                    break;
                                case "desired_salary":
                                    $salary = $this->getJSModel('common')->getSalaryRangeView($this->resume->dsymbol,$this->resume->drangestart,$this->resume->drangeend,$this->resume->dsalarytype,$this->config['currency_align']);
                                    $data .= getResumeField(JText::_('Desired Salary'), $salary, $field->published);
                                    break;
                                case "keywords":
                                    $data .= getResumeField(JText::_('Keywords'), $this->resume->keywords, $field->published);
                                    break;
                                case "jobtype":
                                    $data .= getResumeField(JText::_('Work Preference'), $this->resume->heighesteducationtitle, $field->published);
                                    break;
                                case "heighestfinisheducation":
                                    $data .= getResumeField(JText::_('Highest Finished Education'), $this->resume->jobtypetitle, $field->published);
                                    break;
                                case "date_start":
                                    if($this->resume->date_of_birth == '0000-00-00 00:00:00')
                                        $datestart = '';
                                    else
                                        $datestart = JHtml::_('date', $this->resume->date_start, $this->config['date_format']);
                                    $data .= getResumeField(JText::_('Date You Can Start'), $datestart, $field->published);
                                    break;
                                case "total_experience":
                                    $data .= getResumeField(JText::_('Total Experience'), $this->resume->total_experience, $field->published);
                                    break;
                                case "video":
                                    $html = '
                                                                <iframe title="YouTube video player" width="380" height="290" 
                                                                        src="http://www.youtube.com/embed/' . $this->resume->video . '" frameborder="0" allowfullscreen>
                                                                </iframe>
                                                                ';
                                    $data .= getResumeField(JText::_('Video'), $html, $field->published);
                                    break;
                                default:
                                    $data .= getResumeUserField($this, $field);
                                    break;
                            }
                        }
                        $data .= '
                                        </div>
                                    </div>
                                </div>
                            </div>';
                        $section_address = 0;
                        if ($this->fieldsordering['address'][0]->field == 'section_address') {
                            $section_address = $this->fieldsordering['address'][0]->published;
                            unset($this->fieldsordering['address'][0]);
                        }
                        if ($section_address == 1) {
                            $data .= '
                                        <a id="addressSectionAnchor" alt="">
                                            <div class="js-row no-margin">
                                                <div class="js-resume-section-title js-col-lg-12 js-col-md-12 js-col-xs-12">
                                                    <img src="' . JURI::root() . 'components/com_jsjobs/images/location.png"/>
                                                    <span>' . JText::_("Address") . '</span>
                                                </div>
                                            </div>
                                        </a>
                                        ';
                            if (empty($this->addresses)) {
                                if ($this->show_only_section_that_have_value != 1) {
                                    $data .= '
                                                <div id="addressFormContainer" class="js-resume-section-body address-section js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                                    <div id="js-resume-address-section-view" class="js-row no-margin">
                                                        <div class="section-error js-col-lg-12 js-col-md-12">' . JText::_("No Record Found") . '</div>
                                                    </div>
                                                </div>';
                                }
                            } else {
                                $data .= '
                                    <div id="addressFormContainer" class="js-resume-section-body address-section js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                        <div id="js-resume-address-section-view" class="js-row no-margin">';
                                foreach ($this->addresses as $address) {
                                    if(!($address instanceof Object)){
                                        $address = (object) $address;
                                    }
                                    $add_loccount = 0;
                                    $data .= '
                                            <div id="address_' . $address->id . '" class="js-resume-address-section-view js-row no-margin">';
                                    foreach ($this->fieldsordering['address'] as $field) {
                                        switch ($field->field) {
                                            case "address_city":
                                                if ($field->published == 1) {
                                                    $data .= '
                                                                            <div class="js-col-lg-11 js-col-md-11 js-col-xs-11 no-padding">
                                                                                <span class="addressDetails">';
                                                    $comma = '';
                                                    $addresslayouttype = $this->config['defaultaddressdisplaytype'];
                                                    if ($address->address_cityname != '') {
                                                        $data .= $address->address_cityname;
                                                        $comma = ', ';
                                                    }
                                                    switch ($addresslayouttype) {
                                                        case 'csc':
                                                            if ($address->address_statename != '') {
                                                                $data .= $comma . $address->address_statename;
                                                            }
                                                            if ($address->address_countryname != '') {
                                                                $data .= $comma . $address->address_countryname;
                                                            }
                                                            break;
                                                        case 'cs':
                                                            if ($address->address_statename != '') {
                                                                $data .= $comma . $address->address_statename;
                                                            }
                                                            break;
                                                        case 'cc':
                                                            if ($address->address_countryname != '') {
                                                                $data .= $comma . $address->address_countryname;
                                                            }
                                                            break;
                                                    }
                                                    $data .= '      </span>
                                                                            </div>';
                                                }
                                                break;
                                            case "address_zipcode":
                                                if ($field->published == 1) {
                                                    $data .= '  <div class="js-col-lg-11 js-col-md-11 js-col-xs-11 no-padding">
                                                                                <span class="addressDetails">' . JText::_('Zipcode') . ' ' . $address->address_zipcode . '</span>
                                                                            </div>';
                                                }
                                                break;
                                            case "address":
                                                if ($field->published == 1) {
                                                    $address_address = ($address->address == '') ? 'N/A' : $address->address;
                                                    $data .= '
                                                                        <div class="js-col-lg-11 js-col-md-11 js-col-xs-12 no-padding">
                                                                            <span class="sectionText">' . $address_address . '</span>
                                                                        </div>';
                                                }
                                                break;
                                            case "latitude":
                                            case "longitude":
                                            case "address_location":
                                                if ($field->published == 1 && $add_loccount == 0) {
                                                    $data .= '<input class="form-control" id="longitude_' . $address->id . '" type="hidden" name="longitude" value="' . $address->longitude . '" />
                                                                        <input class="form-control" id="latitude_' . $address->id . '" type="hidden" name="latitude" value="' . $address->latitude . '" />';
                                                    if (isset($this->printresume) AND $this->printresume == 1) {
                                                        $data .= '
                                                                    <div id="map_container_' . $address->id . '" class="map_container js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding" style="display:none;">
                                                                        <div id="map_view_' . $address->id . '" class="map_view"></div>
                                                                    </div><script type="text/javascript">toggleMap(' . $address->id . ');</script>';
                                                    } else {
                                                        $data .= '
                                                                    <div class="map-toggler js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding" onclick="return toggleMap(' . $address->id . ');">
                                                                        <span><img src="' . JURI::root() . 'components/com_jsjobs/images/show-map.png" /><span class="text">' . JText::_('Show Map') . '</span></span>
                                                                    </div>
                                                                    <div id="map_container_' . $address->id . '" class="map_container js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding" style="display:none;">
                                                                        <div id="map_view_' . $address->id . '" class="map_view"></div>
                                                                    </div>';
                                                    }
                                                    $add_loccount++;
                                                }
                                                break;
                                            default:
                                                $data .= getResumeUserFieldForAddress($this, $field);
                                                break;
                                        }
                                    }
                                    $data .= '
                                                    </div>';
                                }
                                $data .= '
                                        </div>
                                    </div>';
                            }
                        }
                        $section_institute = 0;
                        if ($this->fieldsordering['institute'][0]->field == 'section_institute') {
                            $section_institute = $this->fieldsordering['institute'][0]->published;
                            unset($this->fieldsordering['institute'][0]);
                        }
                        if ($section_institute == 1) {
                            $data .= '
                                        <a id="instituteSectionAnchor" alt="">
                                            <div class="js-row no-margin">
                                                <div class="js-resume-section-title js-col-lg-12 js-col-md-12 js-col-xs-12">
                                                    <img src="' . JURI::root() . 'components/com_jsjobs/images/education.png"/>
                                                    <span>' . JText::_("Institutes") . '</span>
                                                </div>
                                            </div>
                                        </a>
                                        ';
                            if (empty($this->institutes)) {
                                if ($this->show_only_section_that_have_value != 1) {
                                    $data .= '
                                        <div id="addressFormContainer" class="js-resume-section-body address-section js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                            <div id="js-resume-address-section-view" class="js-row no-margin">
                                                <div class="section-error js-col-lg-12 js-col-md-12">' . JText::_("No Record Found") . '</div>
                                            </div>
                                        </div>';
                                }
                            } else {
                                $data .= '
                                    <div id="instituteFormContainer" class="js-resume-section-body institute-section js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                        <div id="js-resume-data-section-view" class="js-row no-margin">';
                                // resume institutes starts here
                                foreach ($this->institutes as $institute) {
                                    if(!($institute instanceof Object)){
                                        $institute = (Object) $institute;
                                    }
                                    $data .= '
                                            <div id="institute_' . $institute->id . '" class="js-resume-data-section-view js-row no-margin">';
                                    foreach ($this->fieldsordering['institute'] as $field) {
                                        switch ($field->field) {
                                            case "institute":
                                                if ($field->published == 1) {
                                                    $institute_institute = ($institute->institute != '') ? $institute->institute : 'N/A';
                                                    $data .= '<div class="js-resume-data-head js-row no-margin">
                                                                            <div class="js-col-lg-10 js-col-md-10 no-padding">
                                                                                <span class="data-head-name">' . $institute_institute . '</span>
                                                                            </div>
                                                                        </div>';
                                                }
                                                break;
                                            case "institute_city":
                                                $comma = '';
                                                $addresslayouttype = $this->config['defaultaddressdisplaytype'];
                                                $cityname = ($institute->institute_cityname != '') ? $institute->institute_cityname : 'N/A';
                                                $data .= getResumeField(JText::_('City'), $cityname, $field->published);
                                                switch ($addresslayouttype) {
                                                    case 'csc':
                                                        $statename = ($institute->institute_statename != '') ? $institute->institute_statename : 'N/A';
                                                        $data .= getResumeField(JText::_('State'), $statename, $field->published);
                                                        $countryname = ($institute->institute_countryname != '') ? $institute->institute_countryname : 'N/A';
                                                        $data .= getResumeField(JText::_('Country'), $countryname, $field->published);
                                                        break;
                                                    case 'cs':
                                                        $statename = ($institute->institute_statename != '') ? $institute->institute_statename : 'N/A';
                                                        $data .= getResumeField(JText::_('State'), $statename, $field->published);
                                                        break;
                                                    case 'cc':
                                                        $countryname = ($institute->institute_countryname != '') ? $institute->institute_countryname : 'N/A';
                                                        $data .= getResumeField(JText::_('Country'), $countryname, $field->published);
                                                        break;
                                                }
                                                break;
                                            case "institute_address":
                                                $value = ($institute->institute_address != '') ? $institute->institute_address : 'N/A';
                                                $data .= getResumeField(JText::_('Address'), $value, $field->published);
                                                break;
                                            case "institute_certificate_name":
                                                $value = ($institute->institute_certificate_name != '') ? $institute->institute_certificate_name : 'N/A';
                                                $data .= getResumeField(JText::_('Cert/deg/oth'), $value, $field->published);
                                                break;
                                            case "institute_study_area":
                                                $value = ($institute->institute_study_area != '') ? $institute->institute_study_area : 'N/A';
                                                $data .= getResumeField(JText::_('Study Area'), $value, $field->published);
                                                break;
                                            default:
                                                $data .= getResumeUserField($this, $field);
                                                break;
                                        }
                                    }
                                    $data .= '</div>';
                                }
                                $data .= '
                                        </div>
                                    </div>';
                            }
                        }
                        $section_employer = 0;
                        if ($this->fieldsordering['employer'][0]->field == 'section_employer') {
                            $section_employer = $this->fieldsordering['employer'][0]->published;
                            unset($this->fieldsordering['employer'][0]);
                        }
                        if ($section_employer == 1) {
                            $data .= '
                                        <a id="employerSectionAnchor" alt="">
                                            <div class="js-row no-margin">
                                                <div class="js-resume-section-title js-col-lg-12 js-col-md-12 js-col-xs-12">
                                                    <img src="' . JURI::root() . 'components/com_jsjobs/images/employer.png"/>
                                                    <span>' . JText::_("Employers") . '</span>
                                                </div>
                                            </div>
                                        </a>
                                        ';
                            if (empty($this->employers)) {
                                if ($this->show_only_section_that_have_value != 1) {
                                    $data .= '
                                        <div id="addressFormContainer" class="js-resume-section-body address-section js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                            <div id="js-resume-address-section-view" class="js-row no-margin">
                                                <div class="section-error js-col-lg-12 js-col-md-12">' . JText::_("No Record Found") . '</div>
                                            </div>
                                        </div>';
                                }
                            } else {
                                $data .= '
                                    <div id="employerFormContainer" class="js-resume-section-body employer-section js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                        <div id="js-resume-data-section-view" class="js-row no-margin">';
                                // resume employers starts here
                                foreach ($this->employers as $employer) {
                                    if(!($employer instanceof Object)){
                                        $employer = (Object) $employer;
                                    }
                                    $data .= '<div id="employer_' . $employer->id . '" class="js-resume-data-section-view js-row no-margin">';
                                    foreach ($this->fieldsordering['employer'] as $field) {
                                        switch ($field->field) {
                                            case "employer":
                                                if ($field->published == 1) {
                                                    $employer_employer = ($employer->employer != '') ? $employer->employer : 'N/A';
                                                    $data .= '
                                                                        <div class="js-resume-data-head js-row no-margin">
                                                                            <div class="js-col-lg-10 js-col-md-10 no-padding">
                                                                                <span class="data-head-name">' . $employer_employer . '</span>
                                                                            </div>
                                                                        </div>';
                                                }
                                                break;
                                            case "employer_city":
                                                $comma = '';
                                                $addresslayouttype = $this->config['defaultaddressdisplaytype'];
                                                $cityname = ($employer->employer_cityname != '') ? $employer->employer_cityname : 'N/A';
                                                $data .= getResumeField(JText::_('City'), $cityname, $field->published);
                                                switch ($addresslayouttype) {
                                                    case 'csc':
                                                        $statename = ($employer->employer_statename != '') ? $employer->employer_statename : 'N/A';
                                                        $data .= getResumeField(JText::_('State'), $statename, $field->published);
                                                        $countryname = ($employer->employer_countryname != '') ? $employer->employer_countryname : 'N/A';
                                                        $data .= getResumeField(JText::_('Country'), $countryname, $field->published);
                                                        break;
                                                    case 'cs':
                                                        $statename = ($employer->employer_statename != '') ? $employer->employer_statename : 'N/A';
                                                        $data .= getResumeField(JText::_('State'), $statename, $field->published);
                                                        break;
                                                    case 'cc':
                                                        $countryname = ($employer->employer_countryname != '') ? $employer->employer_countryname : 'N/A';
                                                        $data .= getResumeField(JText::_('Country'), $countryname, $field->published);
                                                        break;
                                                }
                                                break;
                                            case "employer_position":
                                                $employer_position = ($employer->employer_position != '') ? $employer->employer_position : 'N/A';
                                                $data .= getResumeField(JText::_("Position"), $employer_position, $field->published);
                                                break;
                                            case "employer_resp":
                                                $employer_resp = ($employer->employer_resp != '') ? $employer->employer_resp : 'N/A';
                                                $data .= getResumeField(JText::_("Responsibilities"), $employer_resp, $field->published);
                                                break;
                                            case "employer_pay_upon_leaving":
                                                $employer_pay_upon_leaving = ($employer->employer_pay_upon_leaving != '') ? $employer->employer_pay_upon_leaving : 'N/A';
                                                $data .= getResumeField(JText::_("Pay Upon Leaving"), $employer_pay_upon_leaving, $field->published);
                                                break;
                                            case "employer_supervisor":
                                                $employer_supervisor = ($employer->employer_supervisor != '') ? $employer->employer_supervisor : 'N/A';
                                                $data .= getResumeField(JText::_("Supervisor"), $employer_supervisor, $field->published);
                                                break;
                                            case "employer_from_date":
                                                $employer_fromdate = JHtml::_('date', $employer->employer_from_date, $this->config['date_format']);
                                                $data .= getResumeField(JText::_("From Date"), $employer_fromdate, $field->published);
                                                break;
                                            case "employer_to_date":
                                                $employer_todate = JHtml::_('date', $employer->employer_to_date, $this->config['date_format']);
                                                $data .= getResumeField(JText::_("To Date"), $employer_todate, $field->published);
                                                break;
                                            case "employer_leave_reason":
                                                $employer_leavereason = ($employer->employer_leave_reason != '') ? $employer->employer_leave_reason : 'N/A';
                                                $data .= getResumeField(JText::_("Reason For Leaving"), $employer_leavereason, $field->published);
                                                break;
                                            case "employer_zip":
                                                $employer_zipcode = ($employer->employer_zip != '') ? $employer->employer_zip : 'N/A';
                                                $data .= getResumeField(JText::_("Zip Code"), $employer_zipcode, $field->published);
                                                break;
                                            case "employer_phone":
                                                $employer_phone = ($employer->employer_phone != '') ? $employer->employer_phone : 'N/A';
                                                $data .= getResumeField(JText::_("Phone"), $employer_phone, $field->published);
                                                break;
                                            case "employer_address":
                                                $employer_address = ($employer->employer_address != '') ? $employer->employer_address : 'N/A';
                                                $data .= getResumeField(JText::_("Address"), $employer_address, $field->published);
                                                break;
                                            default:
                                                $data .= getResumeUserField($this, $field);
                                                break;
                                        }
                                    }
                                    $data .= '  </div>';
                                }
                                $data .= '
                                        </div>
                                    </div>';
                            }
                        }
                        $section_skills = 0;
                        if ($this->fieldsordering['skills'][0]->field == 'section_skills') {
                            $section_skills = $this->fieldsordering['skills'][0]->published;
                            unset($this->fieldsordering['skills'][0]);
                        }
                        if ($section_skills == 1) {
                            $data .= '
                                        <a id="skillsSectionAnchor" alt="">
                                            <div class="js-row no-margin">
                                                <div class="js-resume-section-title js-col-lg-12 js-col-md-12 js-col-xs-12">
                                                    <img src="' . JURI::root() . 'components/com_jsjobs/images/skills.png"/>
                                                    <span>' . JText::_("Skills") . '</span>
                                                </div>
                                            </div>
                                        </a>
                                        ';
                            if ($this->resume->skills == '') {
                                if ($this->show_only_section_that_have_value != 1) {
                                    $data .= '
                                        <div id="skillsFormContainer" class="js-resume-section-body address-section js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                            <div id="js-resume-address-section-view" class="js-row no-margin">
                                                <div class="section-error js-col-lg-12 js-col-md-12">' . JText::_("No Record Found") . '</div>
                                            </div>
                                        </div>';
                                }
                            } else {
                                $data .= '
                                    <div id="skillsFormContainer" class="js-resume-section-body skills-section js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                        <div id="js-resume-skills-section-view js-row no-margin">';
                                // resume skills starts here
                                $data .= '<div id="skills" class="js-resume-data-section-view js-row no-margin">';
                                foreach ($this->fieldsordering['skills'] as $field) {
                                    switch ($field->field) {
                                        case "skills":
                                            $data .= getResumeField(JText::_("Skills"), $this->resume->skills, $field->published);
                                            break;
                                        default:
                                            $data .= getResumeUserField($this, $field);
                                            break;
                                    }
                                }
                                $data .= '</div>';
                                $data .= '
                                        </div>
                                    </div>';
                            }
                        }
                        $section_resume = 0;
                        if ($this->fieldsordering['resume'][0]->field == 'section_resume') {
                            $section_resume = $this->fieldsordering['resume'][0]->published;
                            unset($this->fieldsordering['resume'][0]);
                        }
                        if ($section_resume == 1) {
                            $data .= '
                                        <a id="editorSectionAnchor" alt="">
                                            <div class="js-row no-margin">
                                                <div class="js-resume-section-title js-col-lg-12 js-col-md-12 js-col-xs-12">
                                                    <img src="' . JURI::root() . 'components/com_jsjobs/images/resume.png"/>
                                                    <span>' . JText::_("Resume") . '</span>
                                                </div>
                                            </div>
                                        </a>
                                        ';
                            if ($this->resume->resume == '') {
                                if ($this->show_only_section_that_have_value != 1) {
                                    $data .= '
                                        <div id="editorFormContainer" class="js-resume-section-body address-section js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                            <div id="js-resume-address-section-view" class="js-row no-margin">
                                                <div class="section-error js-col-lg-12 js-col-md-12">' . JText::_("No Record Found") . '</div>
                                            </div>
                                        </div>';
                                }
                            } else {
                                $data .= '
                                    <div id="editorFormContainer" class="js-resume-section-body editor-section js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                        <div id="js-resume-editor-section-view js-row no-margin">';
                                // resume skills starts here
                                $data .= '<div id="editorView" class="js-resume-address-section-view js-row no-margin">';
                                foreach ($this->fieldsordering['resume'] as $field) {
                                    switch ($field->field) {
                                        case "resume":
                                            if ($field->published == 1) {
                                                $data .= '
                                                            <div class="js-row no-margin">
                                                                <div class="js-resume-data-title js-col-lg-12 js-col-md-12 no-padding">
                                                                    <span>' . JText::_('Resume Editor') . '</span>
                                                                </div>
                                                                <div class="js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                                                    <span class="addressDetails">' . $this->resume->resume . '</span>
                                                                </div>
                                                            </div>';
                                            }
                                            break;
                                        default:
                                            $data .= getResumeUserField($this, $field);
                                            break;
                                    }
                                }
                                $data .= '</div>
                                        </div>
                                    </div>';
                            }
                        }
                        $section_references = 0;
                        if ($this->fieldsordering['reference'][0]->field == 'section_reference') {
                            $section_references = $this->fieldsordering['reference'][0]->published;
                            unset($this->fieldsordering['reference'][0]);
                        }
                        if ($section_references == 1) {
                            $data .= '
                                        <a id="referenceSectionAnchor" alt="">
                                            <div class="js-row no-margin">
                                                <div class="js-resume-section-title js-col-lg-12 js-col-md-12 js-col-xs-12">
                                                    <img src="' . JURI::root() . 'components/com_jsjobs/images/referances.png"/>
                                                    <span>' . JText::_("References") . '</span>
                                                </div>
                                            </div>
                                        </a>
                                        ';
                            if (empty($this->references)) {
                                if ($this->show_only_section_that_have_value != 1) {
                                    $data .= '
                                        <div id="referenceFormContainer" class="js-resume-section-body address-section js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                            <div id="js-resume-address-section-view" class="js-row no-margin">
                                                <div class="section-error js-col-lg-12 js-col-md-12">' . JText::_("No Record Found") . '</div>
                                            </div>
                                        </div>';
                                }
                            } else {
                                $data .= '
                                    <div id="referenceFormContainer" class="js-resume-section-body reference-section js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                        <div id="js-reference-data-section-view" class="js-row no-margin">';
                                // resume employers starts here
                                foreach ($this->references as $reference) {
                                    if(!($reference instanceof Object)){
                                        $reference = (Object) $reference;
                                    }
                                    $data .= '<div id="reference_' . $reference->id . '" class="js-resume-data-section-view js-row no-margin">';
                                    foreach ($this->fieldsordering['reference'] as $field) {
                                        switch ($field->field) {
                                            case "reference":
                                                if ($field->published == 1) {
                                                    $reference_reference = ($reference->reference != '') ? $reference->reference : 'N/A';
                                                    $data .= '
                                                                    <div class="js-resume-data-head js-row no-margin">
                                                                        <div class="js-col-lg-10 js-col-md-10 no-padding">
                                                                            <span class="data-head-name">' . $reference_reference . '</span>
                                                                        </div>
                                                                    </div>';
                                                }
                                                break;
                                            case "reference_city":
                                                $comma = '';
                                                $addresslayouttype = $this->config['defaultaddressdisplaytype'];
                                                $cityname = ($reference->reference_cityname != '') ? $reference->reference_cityname : 'N/A';
                                                $data .= getResumeField(JText::_('City'), $cityname, $field->published);
                                                switch ($addresslayouttype) {
                                                    case 'csc':
                                                        $statename = ($reference->reference_statename != '') ? $reference->reference_statename : 'N/A';
                                                        $data .= getResumeField(JText::_('State'), $statename, $field->published);
                                                        $countryname = ($reference->reference_countryname != '') ? $reference->reference_countryname : 'N/A';
                                                        $data .= getResumeField(JText::_('Country'), $countryname, $field->published);
                                                        break;
                                                    case 'cs':
                                                        $statename = ($reference->reference_statename != '') ? $reference->reference_statename : 'N/A';
                                                        $data .= getResumeField(JText::_('State'), $statename, $field->published);
                                                        break;
                                                    case 'cc':
                                                        $countryname = ($reference->reference_countryname != '') ? $reference->reference_countryname : 'N/A';
                                                        $data .= getResumeField(JText::_('Country'), $countryname, $field->published);
                                                        break;
                                                }
                                                break;
                                            case "reference_name":
                                                $reference_name = ($reference->reference_name != '') ? $reference->reference_name : 'N/A';
                                                $data .= getResumeField(JText::_('Name'), $reference_name, $field->published);
                                                break;
                                            case "reference_years":
                                                $reference_year = ($reference->reference_years != '') ? $reference->reference_years : 'N/A';
                                                $data .= getResumeField(JText::_('To Date'), $reference_year, $field->published);
                                                break;
                                            case "reference_relation":
                                                $reference_relation = ($reference->reference_relation != '') ? $reference->reference_relation : 'N/A';
                                                $data .= getResumeField(JText::_('Reference Relation'), $reference_relation, $field->published);
                                                break;
                                            case "reference_zipcode":
                                                $reference_zipcode = ($reference->reference_zipcode != '') ? $reference->reference_zipcode : 'N/A';
                                                $data .= getResumeField(JText::_('Zip Code'), $reference_zipcode, $field->published);
                                                break;
                                            case "reference_phone":
                                                $reference_phone = ($reference->reference_phone != '') ? $reference->reference_phone : 'N/A';
                                                $data .= getResumeField(JText::_('Phone'), $reference_phone, $field->published);
                                                break;
                                            case "reference_address":
                                                $reference_address = ($reference->reference_address != '') ? $reference->reference_address : 'N/A';
                                                $data .= getResumeField(JText::_('Address'), $reference_address, $field->published);
                                                break;
                                            default:
                                                $data .= getResumeUserField($this, $field);
                                                break;
                                        }
                                    }
                                    $data .= '  </div>';
                                }
                                $data .= '</div>
                                        </div>';
                            }
                        }
                        $section_language = 0;
                        if ($this->fieldsordering['language'][0]->field == 'section_language') {
                            $section_language = $this->fieldsordering['language'][0]->published;
                            unset($this->fieldsordering['language'][0]);
                        }
                        if ($section_language == 1) {
                            $data .= '
                                        <a id="languageSectionAnchor" alt="">
                                            <div class="js-row no-margin">
                                                <div class="js-resume-section-title js-col-lg-12 js-col-md-12 js-col-xs-12">
                                                    <img src="' . JURI::root() . 'components/com_jsjobs/images/language.png"/>
                                                    <span>' . JText::_("Language") . '</span>
                                                </div>
                                            </div>
                                        </a>
                                        ';
                            if (empty($this->languages)) {
                                if ($this->show_only_section_that_have_value != 1) {
                                    $data .= '
                                        <div id="languageFormContainer" class="js-resume-section-body address-section js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                            <div id="js-resume-address-section-view" class="js-row no-margin">
                                                <div class="section-error js-col-lg-12 js-col-md-12">' . JText::_("No Record Found") . '</div>
                                            </div>
                                        </div>';
                                }
                            } else {
                                $data .= '
                                    <div id="languageFormContainer" class="js-resume-section-body language-section js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                        <div id="js-language-data-section-view" class="js-row no-margin">';
                                // resume languages starts here
                                foreach ($this->languages as $language) {
                                    if(!($language instanceof Object)){
                                        $language = (Object) $language;
                                    }
                                    $data .= '<div id="language_' . $language->id . '" class="js-resume-data-section-view js-row no-margin">';
                                    foreach ($this->fieldsordering['language'] as $field) {
                                        switch ($field->field) {
                                            case "language":
                                                if ($field->published == 1) {
                                                    $language_language = ($language->language != '') ? $language->language : 'N/A';
                                                    $data .= '                                                                
                                                                    <div class="js-resume-data-head js-row no-margin">
                                                                        <div class="js-col-lg-10 js-col-md-10 no-padding">
                                                                            <span>' . $language->language . '</span>
                                                                        </div>
                                                                    </div>';
                                                }
                                                break;
                                            case "language_reading":
                                                $language_reading = ($language->language_reading != '') ? $language->language_reading : 'N/A';
                                                $data .= getResumeField(JText::_('Reading'), $language_reading, $field->published);
                                                break;
                                            case "language_writing":
                                                $language_writing = ($language->language_writing != '') ? $language->language_writing : 'N/A';
                                                $data .= getResumeField(JText::_('Position'), $language_writing, $field->published);
                                                break;
                                            case "language_understanding":
                                                $language_understanding = ($language->language_understanding != '') ? $language->language_understanding : 'N/A';
                                                $data .= getResumeField(JText::_('Understanding'), $language_understanding, $field->published);
                                                break;
                                            case "language_where_learned":
                                                $language_where_learned = ($language->language_where_learned != '') ? $language->language_where_learned : 'N/A';
                                                $data .= getResumeField(JText::_('Language Where Learned'), $language_where_learned, $field->published);
                                                break;
                                            default:
                                                $data .= getResumeUserField($this, $field);
                                                break;
                                        }
                                    }
                                    $data .= '  </div>';
                                }
                                $data .= '
                                        </div>
                                    </div>';
                            }
                        }
                        echo $data;
                        ?>
                        <?php
                        if (isset($this->resume)) {
                            if (($this->resume->created == '0000-00-00 00:00:00') || ($this->resume->created == '')) {
                                $curdate = date('Y-m-d H:i:s');
                            } else {
                                $curdate = $this->resume->created;
                            }
                        } else {
                            $curdate = date('Y-m-d H:i:s');
                        }
                        ?>
                    </div>
                </div>
            <?php } else {
                $this->jsjobsmessages->getAccessDeniedMsg('Could not find any matching results.', 'Could not find any matching results.', 0);
            }
        } else { // not allowed job posting 
            $this->jsjobsmessages->getAccessDeniedMsg('You are not allowed', 'You are not allow to view this page', 0);
        }
}//ol
?>

<script language="javascript">
    var printpage = '<?php if (isset($this->printresume))
    echo $this->printresume;
else
    echo 0;
?>';
    if (printpage == 1) {
        window.onload = function () {
            window.print();
            window.close();
        }
    }
    jQuery(document).ready(function () {
        <?php if(!isset($this->resumefiles)) { ?>
            getResumeFiles(<?php echo $this->resume->id ?>, 'resumeFilesList', 'view');
        <?php } ?>
    });


    function getResumeFiles(resumeid, src, filesfor) {
        var printResume = '<?php
                                if (isset($this->printresume) && !empty($this->printresume)) {
                                    echo $this->printresume;
                                } else {
                                    echo 0;
                                }
                            ?>';
        jQuery.post('<?php echo JURI::root(); ?>index.php?option=com_jsjobs&c=resume&task=getresumefiles', {resumeid: resumeid, filesfor: filesfor, printresume: printResume}, function (data) {

            if (data) {
                if (jQuery.trim(data).length == 0) {
                    if (resumeid == -1) {
                        jQuery('#' + src).remove();
                    } else {
                        jQuery('#' + src).append("<?php echo JText::_('No uploaded file found'); ?>");
                    }
                } else {
                    jQuery("#" + src).append(data);
                }
            } else {
                alert("<?php echo JText::_('Error occurred while getting resume uploaded resume files'); ?>");
            }
        });
    }
    function getAllResumeFiles(resumeid) {
        jQuery.post('<?php echo JURI::root(); ?>index.php?option=com_jsjobs&c=resume&task=getallresumefiles', {resumeid: resumeid}, function (data) {
            if (!data) {
                alert("<?php echo JText::_('Error occurred while getting all resume files'); ?>");
            }
        });
    }
</script>