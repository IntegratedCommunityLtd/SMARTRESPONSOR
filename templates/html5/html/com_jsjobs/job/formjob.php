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
 * File Name:	views/employer/tmpl/formjob.php
  ^
 * Description: template for form job
  ^
 * History:		NONE
  ^
 */
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.formvalidation');
JHTML :: _('behavior.calendar');
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_jsjobs/css/token-input-jsjobs.css');
$document->addStyleSheet('components/com_jsjobs/css/combobox/chosen.css');
if (JVERSION < 3) {
    JHtml::_('behavior.mootools');
    $document->addScript('components/com_jsjobs/js/jquery.js');
} else {
    JHtml::_('behavior.framework');
    JHtml::_('jquery.framework');
}
$document->addScript('components/com_jsjobs/js/jquery.tokeninput.js');
$document->addScript('components/com_jsjobs/js/combobox/chosen.jquery.js');
$document->addScript('components/com_jsjobs/js/combobox/prism.js');

if ($this->config['date_format'] == 'm/d/Y')
    $dash = '/';
else
    $dash = '-';
$dateformat = $this->config['date_format'];
$firstdash = strpos($dateformat, $dash, 0);
$firstvalue = substr($dateformat, 0, $firstdash);
$firstdash = $firstdash + 1;
$seconddash = strpos($dateformat, $dash, $firstdash);
$secondvalue = substr($dateformat, $firstdash, $seconddash - $firstdash);
$seconddash = $seconddash + 1;
$thirdvalue = substr($dateformat, $seconddash, strlen($dateformat) - $seconddash);
$js_dateformat = '%' . $firstvalue . $dash . '%' . $secondvalue . $dash . '%' . $thirdvalue;
$js_scriptdateformat = $firstvalue . $dash . $secondvalue . $dash . $thirdvalue;
?>
<style type="text/css">
    #coordinatebutton{ float:right; clear:both; }
    #coordinatebutton .cbutton{ padding:1; font-size: 1.08em; }
    #coordinatebutton .cbutton:hover{ font-size: 1.08em; padding:1; }
</style>

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
    ?>
    <script language="javascript">

            function check_start_stop_job_publishing_dates(forcall) {
                var date_start_make = new Array();
                var date_stop_make = new Array();
                var split_start_value = new Array();
                var split_stop_value = new Array();
                var returnvalue = true;

                var start_string = document.getElementById("job_startpublishing").value;
                var stoppublishing_obj = document.getElementById("stoppublishing"); // when job exipry in days ,week or month  
                if (typeof stoppublishing_obj !== 'undefined' && stoppublishing_obj !== null) {
                    var stoppublishing_value = document.getElementById("stoppublishing").value;
                    if (stoppublishing_value)
                        return true; // no comparision with dates because stop date is enforce unpublished form the package 
                } else {
                    var stop_string = document.getElementById("job_stoppublishing").value;
                }
                var format_type = document.getElementById("j_dateformat").value;
                if (format_type == 'd-m-Y') {
                    split_start_value = start_string.split('-');

                    date_start_make['year'] = split_start_value[2];
                    date_start_make['month'] = split_start_value[1];
                    date_start_make['day'] = split_start_value[0];

                    split_stop_value = stop_string.split('-');

                    date_stop_make['year'] = split_stop_value[2];
                    date_stop_make['month'] = split_stop_value[1];
                    date_stop_make['day'] = split_stop_value[0];

                } else if (format_type == 'm/d/Y') {
                    split_start_value = start_string.split('/');
                    date_start_make['year'] = split_start_value[2];
                    date_start_make['month'] = split_start_value[0];
                    date_start_make['day'] = split_start_value[1];

                    split_stop_value = stop_string.split('/');

                    date_stop_make['year'] = split_stop_value[2];
                    date_stop_make['month'] = split_stop_value[0];
                    date_stop_make['day'] = split_stop_value[1];

                } else if (format_type == 'Y-m-d') {

                    split_start_value = start_string.split('-');

                    date_start_make['year'] = split_start_value[0];
                    date_start_make['month'] = split_start_value[1];
                    date_start_make['day'] = split_start_value[2];

                    split_stop_value = stop_string.split('-');

                    date_stop_make['year'] = split_stop_value[0];
                    date_stop_make['month'] = split_stop_value[1];
                    date_stop_make['day'] = split_stop_value[2];

                }
                var start = new Date(date_start_make['year'], date_start_make['month'] - 1, date_start_make['day']);
                var stop = new Date(date_stop_make['year'], date_stop_make['month'] - 1, date_stop_make['day']);
                if (start >= stop) {
                    if (forcall == 1) {
                        jQuery("#job_startpublishing").addClass("invalid");
                        var isedit = document.getElementById("id");
                        if (isedit.value != "" && isedit.value != 0) { // for edit case
                            alert('<?php echo JText::_('Start Publishing Date Must Be Less Then Stop Publishing Date'); ?>');
                        } else { // for add case
                            alert('<?php echo JText::_('Please Enter A Valid Start Publishing Date'); ?>');

                        }
                    } else if (forcall == 2) {
                        jQuery("#job_stoppublishing").addClass("invalid");
                        alert('<?php echo JText::_('Please Enter A Valid Stop Publishing Date'); ?>');
                    }
                    returnvalue = false;
                }
                return returnvalue;
            }

            function validate_checkstartdate() {
                var date_start_make = new Array();
                var split_start_value = new Array();
                f = document.adminForm;
                var isstartpubreadonly = document.getElementById("startpubreadonly");
                if (typeof isstartpubreadonly !== 'undefined' && isstartpubreadonly !== null) {
                    if (isstartpubreadonly.value != "" && isstartpubreadonly.value != 0) {
                        return true;
                    }
                } else {
                    var isedit = document.getElementById("id");
                    if (isedit.value != "" && isedit.value != 0) {
                        var return_value = check_start_stop_job_publishing_dates(1);
                        return return_value;
                    } else {
                        var returnvalue = true;
                        var today = new Date()
                        today.setHours(0, 0, 0, 0);

                        var start_string = document.getElementById("job_startpublishing").value;
                        var format_type = document.getElementById("j_dateformat").value;
                        if (format_type == 'd-m-Y') {
                            split_start_value = start_string.split('-');

                            date_start_make['year'] = split_start_value[2];
                            date_start_make['month'] = split_start_value[1];
                            date_start_make['day'] = split_start_value[0];


                        } else if (format_type == 'm/d/Y') {
                            split_start_value = start_string.split('/');
                            date_start_make['year'] = split_start_value[2];
                            date_start_make['month'] = split_start_value[0];
                            date_start_make['day'] = split_start_value[1];


                        } else if (format_type == 'Y-m-d') {

                            split_start_value = start_string.split('-');

                            date_start_make['year'] = split_start_value[0];
                            date_start_make['month'] = split_start_value[1];
                            date_start_make['day'] = split_start_value[2];
                        }

                        var startpublishingdate = new Date(date_start_make['year'], date_start_make['month'] - 1, date_start_make['day']);

                        if (today > startpublishingdate) {
                            jQuery("#job_startpublishing").addClass("invalid");
                            var isedit = document.getElementById("id");
                            if (isedit.value != "" && isedit.value != 0) { // for edit case
                                alert('<?php echo JText::_('Start Publishing Date Must Be Less Then Stop Publishing Date'); ?>');
                            } else { // for add case
                                alert('<?php echo JText::_('Please Enter A Valid Start Publishing Date'); ?>');

                            }
                            returnvalue = false;
                        }
                        return returnvalue;


                    }
                }

            }
            function validate_checkstopdate() {
                var isstoppubreadonly = document.getElementById("stoppubreadonly");
                if (typeof isstoppubreadonly !== 'undefined' && isstoppubreadonly !== null) {
                    if (isstoppubreadonly.value != "" && isstoppubreadonly.value != 0) {
                        return true;
                    }
                } else {
                    var return_value = check_start_stop_job_publishing_dates(2);
                    return return_value;
                }

            }
            function validate_checkagefrom() {
                var optionagefrom = document.getElementById("agefrom");
                var strUser = optionagefrom.options[optionagefrom.selectedIndex].text;
                var range_from_value = parseInt(strUser, 10);

                var optionageto = document.getElementById("ageto");
                var strUserTo = optionageto.options[optionageto.selectedIndex].text;
                var range_from_to = parseInt(strUserTo, 10);
                if (range_from_value > range_from_to) {
                    jQuery("#agefrom").addClass("invalid");
                    alert('<?php echo JText::_('Age From Must Be Less Then Age To'); ?>');
                    return false;
                } else if (range_from_value == range_from_to) {
                    return true;
                }
                return true;
            }


            function validate_salaryrangefrom() {
                var optionsalaryrangefrom = document.getElementById("salaryrangefrom");
                var strUser = optionsalaryrangefrom.options[optionsalaryrangefrom.selectedIndex].text;
                var salaryrange_from_value = parseInt(strUser, 10);

                var optionsalaryrangeto = document.getElementById("salaryrangeto");
                var strUserTo = optionsalaryrangeto.options[optionsalaryrangeto.selectedIndex].text;
                var salaryrangerange_from_to = parseInt(strUserTo, 10);
                if (salaryrange_from_value > salaryrangerange_from_to) {
                    jQuery("#salaryrangefrom").addClass("invalid");
                    alert('<?php echo JText::_('Salary Range From Must Be Less Then Salary Range To'); ?>');
                    return false;
                } else if (salaryrange_from_value == salaryrangerange_from_to) {
                    return true;
                }
                return true;

            }

            function validate_checkageto() {
                var optionagefrom = document.getElementById("agefrom");
                var strUser = optionagefrom.options[optionagefrom.selectedIndex].text;
                var range_from_value = parseInt(strUser, 10);

                var optionageto = document.getElementById("ageto");
                var strUserTo = optionageto.options[optionageto.selectedIndex].text;
                var range_from_to = parseInt(strUserTo, 10);
                if (range_from_to < range_from_value) {
                    jQuery("#ageto").addClass("invalid");
                    alert('<?php echo JText::_('Age To Must Be Greater Then Age From'); ?>');
                    return false;
                } else if (range_from_to == range_from_value) {
                    return true;
                }
                return true;
            }
            function validate_salaryrangeto() {
                var optionsrangefrom_obj = document.getElementById("salaryrangefrom");
                if (typeof optionsrangefrom_obj !== 'undefined' && optionsrangefrom_obj !== null) {
                    var optionsalaryrangefrom = document.getElementById("salaryrangefrom");
                    var strUser = optionsalaryrangefrom.options[optionsalaryrangefrom.selectedIndex].text;
                    var salaryrange_from_value = parseInt(strUser, 10);

                    var optionsalaryrangeto = document.getElementById("salaryrangeto");
                    var strUserTo = optionsalaryrangeto.options[optionsalaryrangeto.selectedIndex].text;
                    var salaryrangerange_from_to = parseInt(strUserTo, 10);
                    if (salaryrangerange_from_to < salaryrange_from_value) {
                        jQuery("#salaryrangeto").addClass("invalid");
                        alert('<?php echo JText::_('Salary Range To Must Be Greater Then Salary Range From'); ?>');
                        return false;
                    } else if (salaryrangerange_from_to == salaryrange_from_value) {
                        return true;
                    }
                    return true;
                }
                return true;
            }

            function hideShowRange(hideSrc, showSrc, showName, showVal) {
                document.getElementById(hideSrc).style.display = "none";
                document.getElementById(showSrc).style.display = "block";
                document.getElementById(showName).value = showVal;
            }

            function myValidate(f) {

                var msg = new Array();
                var multiselectpackage = document.getElementById("multipackage");
                if (typeof multiselectpackage !== 'undefined' && multiselectpackage !== null) {
                    var m_p_value = multiselectpackage.options[multiselectpackage.selectedIndex].value;
                    if (m_p_value == "") {
                        jQuery("#multipackage").addClass("invalid");
                        msg.push('<?php echo JText::_('Please Select Package To Add New Job'); ?>');
                        alert(msg.join('\n'));
                        return false;
                    }
                }
                var startpub_required = document.getElementById('startdate-required').value;
                if (startpub_required != '') {
                    var startpub_value = document.getElementById('job_startpublishing').value;
                    if (startpub_value == '') {
                        jQuery("#job_startpublishing").addClass("invalid");
                        msg.push('<?php echo JText::_('Please Enter Job Publish Date'); ?>');
                        alert(msg.join('\n'));
                        return false;
                    }
                }
                var stoppub_required = document.getElementById("stopdate-required");
                if (typeof stoppub_required !== 'undefined' && stoppub_required !== null) {
                    var stoppub_required = document.getElementById('stopdate-required').value;
                    if (stoppub_required != '') {
                        var stop_obj = document.getElementById("job_stoppublishing");
                        if (typeof stop_obj !== 'undefined' && stop_obj !== null) {
                            var stoppub_value = document.getElementById('job_stoppublishing').value;
                            if (stoppub_value == '') {
                                jQuery("#job_stoppublishing").addClass("invalid");
                                msg.push('<?php echo JText::_('Please Enter Job Stop Publish Date'); ?>');
                                alert(msg.join('\n'));
                                return false;
                            }
                        }
                    }
                }
                var desc_obj = document.getElementById("description-required");
                if (typeof desc_obj !== 'undefined' && desc_obj !== null) {
                    var desc_required_val = document.getElementById("description-required").value;
                    if (desc_required_val != '') {
                        var jobdescription = tinyMCE.get('description').getContent();
                        if (jobdescription == '') {
                            msg.push('<?php echo JText::_('Please Enter Job Description'); ?>');
                            alert(msg.join('\n'));
                            return false;
                        }
                    }
                }
                var qal_obj = document.getElementById("qualification-required");
                if (typeof qal_obj !== 'undefined' && qal_obj !== null) {
                    var qal_required_val = document.getElementById("qualification-required").value;
                    if (qal_required_val != '') {
                        var jobqal = tinyMCE.get('qualifications').getContent();
                        if (jobqal == '') {
                            msg.push('<?php echo JText::_('Please Enter Job Qualifications'); ?>');
                            alert(msg.join('\n'));
                            return false;
                        }
                    }
                }
                var pref_obj = document.getElementById("prefferdskills-required");
                if (typeof pref_obj !== 'undefined' && pref_obj !== null) {
                    var pref_required_val = document.getElementById("prefferdskills-required").value;
                    if (pref_required_val != '') {
                        var jobpref = tinyMCE.get('prefferdskills').getContent();
                        if (jobpref == '') {
                            msg.push('<?php echo JText::_('Please Enter Job Prefferd Skills'); ?>');
                            alert(msg.join('\n'));
                            return false;
                        }
                    }
                }
                var agr_obj = document.getElementById("agreement-required");
                if (typeof agr_obj !== 'undefined' && agr_obj !== null) {
                    var agr_required_val = document.getElementById("agreement-required").value;
                    if (agr_required_val != '') {
                        var jobagr = tinyMCE.get('agreement').getContent();
                        if (jobagr == '') {
                            msg.push('<?php echo JText::_('Please Enter Job Agreement'); ?>');
                            alert(msg.join('\n'));
                            return false;
                        }
                    }
                }
                var fil_obj = document.getElementById("filter-required");
                if (typeof fil_obj !== 'undefined' && fil_obj !== null) {
                    var fil_required_val = document.getElementById("filter-required").value;
                    if (fil_required_val != '') {
                        var atLeastOneIsChecked = jQuery("input:checked").length;
                        if (atLeastOneIsChecked == 0) {
                            msg.push('<?php echo JText::_('Please Check Filter Apply Setting'); ?>');
                            alert(msg.join('\n'));
                            return false;
                        }
                    }
                }
                var emailsetting_obj = document.getElementById("email-required");
                if (typeof emailsetting_obj !== 'undefined' && emailsetting_obj !== null) {
                    var email_required_val = document.getElementById("email-required").value;
                    if (email_required_val != '') {
                        var checkedradio = jQuery('input[name=sendemail]:radio:checked').length;
                        if (checkedradio == 0) {
                            msg.push('<?php echo JText::_('Please Check Email Setting'); ?>');
                            alert(msg.join('\n'));
                            return false;
                        }
                    }
                }
                var chkst_return = validate_checkstartdate();
                if (chkst_return == false)
                    return false;
                var chkstop_return = validate_checkstopdate();
                if (chkstop_return == false)
                    return false;
                var call_agefrom = jQuery("#agefrom").lenght;
                if (typeof call_agefrom != 'undefined') {
                    var chkagefrom_return = validate_checkagefrom();
                    if (chkagefrom_return == false)
                        return false;
                }
                var call_salaryrangefrom = jQuery("#salaryrangefrom").lenght;
                if (typeof call_salaryrangefrom != 'undefined') {
                    var chksalfrom_return = validate_salaryrangefrom();
                    if (chksalfrom_return == false)
                        return false;
                }
                var call_ageto = jQuery("#ageto").lenght;
                if (typeof call_ageto != 'undefined') {
                    var chkageto_return = validate_checkageto();
                    if (chkageto_return == false)
                        return false;
                }
                var call_salaryrangeto = jQuery("#salaryrangeto").lenght;
                if (typeof call_salaryrangeto != 'undefined') {
                    var chksalto_return = validate_salaryrangeto();
                    if (chksalto_return == false)
                        return false;
                }

                if (document.formvalidator.isValid(f)) {
                    f.check.value = '<?php if (JVERSION < 3)
        echo JUtility::getToken();
    else
        echo JSession::getFormToken();
    ?>';//send token

                } else {
                    msg.push('<?php echo JText::_('Some Values Are Not Acceptable Please Retry'); ?>');
                    alert(msg.join('\n'));
                    return false;
                }
                return true;
            }


            function hasClass(el, selector) {
                var className = " " + selector + " ";

                if ((" " + el.className + " ").replace(/[\n\t]/g, " ").indexOf(className) > -1) {
                    return true;
                }
                return false;
            }

        </script>

        <?php
        if ($this->canaddnewjob == VALIDATE) { // add new job, in edit case always VAlidate
            if ($this->isuserhascompany != 3) {
                ?>
                <div id="js_main_wrapper">
                    <span class="js_controlpanel_section_title"><?php echo JText::_('Job Info'); ?></span>

                    <form action="index.php" method="post" name="adminForm" id="adminForm" class="jsautoz_form" onSubmit="return myValidate(this);">
            <?php if ($this->packagecombo != false) { ?>
                            <div class="fieldwrapper">
                                <div class="fieldtitle">
                                    <label id="durationmsg" for="duration"><?php echo JText::_('Select Package'); ?></label>
                                </div>
                                <div class="fieldvalue">
                            <?php echo $this->packagecombo[0]; ?>
                                </div>
                            </div>				        
                        <?php } ?>
                        <?php
                        $i = 0;
                        foreach ($this->fieldsordering as $field) {
                            switch ($field->field) {
                                case "jobtitle": $title_required = ($field->required ? 'required' : '');
                                    ?>
                                    <div class="fieldwrapper">
                                        <div class="fieldtitle">
                                            <label id="titlemsg" for="title"><?php echo JText::_('Job Title'); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                        </div>
                                        <div class="fieldvalue">
                                            <input class="inputbox <?php echo $title_required; ?>" type="text" name="title" id="title" size="40" maxlength="255" value="<?php if (isset($this->job)) echo $this->job->title; ?>" />
                                        </div>
                                    </div>				        
                        <?php
                        break;
                    case "company":
                        ?>
                                    <div class="fieldwrapper">
                                        <div class="fieldtitle">
                                            <label id="companymsg" for="company"><?php echo JText::_('Company'); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                        </div>
                                        <div class="fieldvalue">
                                    <?php echo $this->lists['companies']; ?>
                                        </div>
                                    </div>				        
                        <?php
                        break;
                    case "department":
                        ?>
                                    <div class="fieldwrapper">
                                        <div class="fieldtitle">
                                            <label id="departmentmsg" for="department"><?php echo JText::_('Department'); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                        </div>
                                        <div class="fieldvalue" id="department">
                                    <?php if (isset($this->lists['departments'])) echo $this->lists['departments']; ?>
                                        </div>
                                    </div>				        
                                            <?php
                                            break;
                                        case "jobcategory":
                                            ?>
                                    <div class="fieldwrapper">
                                        <div class="fieldtitle">
                                    <?php echo JText::_('Categories'); ?><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                        </div>
                                        <div class="fieldvalue">
                        <?php echo $this->lists['jobcategory']; ?>
                                        </div>
                                    </div>				        
                        <?php
                        break;
                    case "subcategory":
                        ?>
                                    <div class="fieldwrapper">
                                        <div class="fieldtitle">
                                    <?php echo JText::_('Sub Category'); ?><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                        </div>
                                        <div class="fieldvalue" id="fj_subcategory">
                        <?php echo $this->lists['subcategory']; ?>
                                        </div>
                                    </div>				        
                        <?php
                        break;
                    case "jobtype":
                        ?>
                                    <?php if ($field->published == 1) { ?>
                                        <div class="fieldwrapper">
                                            <div class="fieldtitle">
                                        <?php echo JText::_('Jobtype'); ?><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                            </div>
                                            <div class="fieldvalue">
                                                <?php echo $this->lists['jobtype']; ?>
                                            </div>
                                        </div>				        
                                            <?php } ?>
                                            <?php
                                            break;
                                        case "jobstatus":
                                            ?>
                                    <div class="fieldwrapper">
                                        <div class="fieldtitle">
                                    <?php echo JText::_('Job status'); ?><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                        </div>
                                        <div class="fieldvalue">
                                            <?php echo $this->lists['jobstatus']; ?>
                                        </div>
                                    </div>				        
                                            <?php
                                            break;
                                        case "jobshift":
                                            ?>
                                    <?php if ($field->published == 1) { ?>
                                        <div class="fieldwrapper">
                                            <div class="fieldtitle">
                                        <?php echo JText::_('Shift'); ?><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                            </div>
                                            <div class="fieldvalue">
                                                <?php echo $this->lists['shift']; ?>
                                            </div>
                                        </div>				        
                                            <?php } ?>
                                            <?php
                                            break;
                                        case "jobsalaryrange":
                                            ?>
                                    <?php if ($field->published == 1) { ?>
                                        <div class="fieldwrapper">
                                            <div class="fieldtitle">
                                        <?php echo JText::_('Salary range'); ?><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                            </div>
                                            <div class="fieldvalue">
                            <?php echo $this->lists['currencyid']; ?>
                                                <?php echo $this->lists['salaryrangefrom']; ?>
                                                <?php echo $this->lists['salaryrangeto']; ?>
                            <?php echo $this->lists['salaryrangetypes']; ?>
                                            </div>
                                        </div>				        
                                            <?php } ?>
                                            <?php
                                            break;
                                        case "heighesteducation":
                                            ?>
                                            <?php if ($field->published == 1) { ?>
                                        <div class="fieldwrapper">
                                            <div class="fieldtitle">
                                                <?php echo JText::_('Education'); ?><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                            </div>
                                            <div class="fieldvalue">
                                                <?php
                                                if (isset($this->job))
                                                    $iseducationminimax = $this->job->iseducationminimax;
                                                else
                                                    $iseducationminimax = 1;
                                                if ($iseducationminimax == 1) {
                                                    $educationminimaxdivstyle = "display:block;";
                                                    $educationrangedivstyle = "display:block;";
                                                } else {
                                                    $educationminimaxdivstyle = "display:block";
                                                    $educationrangedivstyle = "display:block;;";
                                                }
                                                ?>
                                                <input type="hidden" name="iseducationminimax" id="iseducationminimax" value="<?php echo $iseducationminimax; ?>">
                                                <div id="educationminimaxdiv" style="<?php echo $educationminimaxdivstyle; ?>">
                            <?php echo $this->lists['educationminimax']; ?>
                            <?php echo $this->lists['education']; ?>
                                                    <a  onclick="hideShowRange('educationminimaxdiv', 'educationrangediv', 'iseducationminimax', 0);" style="cursor:pointer;"><?php echo JText::_('Specify Range'); ?></a>
                                                </div>
                                                <div id="educationrangediv" data-jquery="hideit" style="<?php echo $educationrangedivstyle; ?>">
                            <?php echo $this->lists['minimumeducationrange']; ?>
                                        <?php echo $this->lists['maximumeducationrange']; ?>
                                                    <a onclick="hideShowRange('educationrangediv', 'educationminimaxdiv', 'iseducationminimax', 1);" style="cursor:pointer;"><?php echo JText::_('Cancel Range'); ?></a>
                                                </div>
                                            </div>
                                        </div>				        
                                        <div class="fieldwrapper">
                                            <div class="fieldtitle">
                                                <label id="degreetitlesmsg" for="degreetitle"><?php echo JText::_('Degree Title'); ?></label>
                                            </div>
                                            <div class="fieldvalue">
                                                <input class="inputbox" type="text" name="degreetitle" id="degreetitle" size="30" maxlength="40" value="<?php if (isset($this->job)) echo $this->job->degreetitle; ?>" />
                                            </div>
                                        </div>				        
                        <?php } ?>
                        <?php
                        break;
                    case "noofjobs":
                        if ($field->published == 1) {
                            $noofjob_required = ($field->required ? 'required' : '');
                            ?>
                                        <div class="fieldwrapper">
                                            <div class="fieldtitle">
                                                <label id="noofjobsmsg" for="noofjobs"><?php echo JText::_('No of jobs'); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                            </div>
                                            <div class="fieldvalue">
                                                <input class="inputbox  <?php echo $noofjob_required; ?> validate-numeric" type="text" name="noofjobs" id="noofjobs" size="10" maxlength="10" value="<?php if (isset($this->job)) echo $this->job->noofjobs; ?>" />
                                            </div>
                                        </div>				        
                                            <?php } ?>	
                                            <?php
                                            break;
                                        case "experience":
                                            ?>
                                            <?php if ($field->published == 1) { ?>
                                        <div class="fieldwrapper">
                                            <div class="fieldtitle">
                                                <label id="experiencesmsg" for="experience"><?php echo JText::_('Experience'); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                            </div>
                                            <div class="fieldvalue">
                            <?php
                            if (isset($this->job))
                                $isexperienceminimax = $this->job->isexperienceminimax;
                            else
                                $isexperienceminimax = 1;
                            if ($isexperienceminimax == 1) {
                                $experienceminimaxdivstyle = "display:block;";
                                $experiencerangedivstyle = "display:block;";
                            } else {
                                $experienceminimaxdivstyle = "display:block;";
                                $experiencerangedivstyle = "display:block;";
                            }
                            ?>
                                                <input type="hidden" name="isexperienceminimax" id="isexperienceminimax" value="<?php echo $isexperienceminimax; ?>">
                                                <div id="experienceminimaxdiv" style="<?php echo $experienceminimaxdivstyle; ?>">
                                        <?php echo $this->lists['experienceminimax']; ?>
                                        <?php echo $this->lists['experience']; ?>
                                                    <a  onclick="hideShowRange('experienceminimaxdiv', 'experiencerangediv', 'isexperienceminimax', 0);" style="cursor:pointer;"><?php echo JText::_('Specify Range'); ?></a>
                                                </div>
                                                <div id="experiencerangediv" data-jquery="hideit" style="<?php echo $experiencerangedivstyle; ?>">
                                        <?php echo $this->lists['minimumexperiencerange']; ?>
                            <?php echo $this->lists['maximumexperiencerange']; ?>
                                                    <a onclick="hideShowRange('experiencerangediv', 'experienceminimaxdiv', 'isexperienceminimax', 1);" style="cursor:pointer;"><?php echo JText::_('Cancel Range'); ?></a>
                                                </div>
                                                <input class="inputbox" type="text" name="experiencetext" id="experiencetext" size="30" maxlength="150" value="<?php if (isset($this->job)) echo $this->job->experiencetext; ?>" />
                            <?php echo JText::_('If Any Other Experience'); ?>
                                            </div>
                                        </div>				        
                                    <?php } ?>
                                    <?php
                                    break;
                                case "duration":
                                    ?>
                                    <?php if ($field->published == 1) {
                                        $duration_required = ($field->required ? 'required' : '');
                                        ?>
                                        <div class="fieldwrapper">
                                            <div class="fieldtitle">
                                                <label id="durationmsg" for="duration"><?php echo JText::_('Duration'); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                            </div>
                                            <div class="fieldvalue">
                                                <input class="inputbox <?php echo $duration_required; ?>" type="text" name="duration" id="duration" size="10" maxlength="15" value="<?php if (isset($this->job)) echo $this->job->duration; ?>" /><?php echo JText::_('Duration'); ?>
                                            </div>
                                        </div>				        
                                            <?php } ?>
                                            <?php
                                            break;
                                        case "startpublishing":
                                            ?>
                                            <?php
                                            $startdatevalue = '';
                                            $startpub_required = ($field->required ? 'required' : '');
                                            if (isset($this->job))
                                                $startdatevalue = date($this->config['date_format'], strtotime($this->job->startpublishing));
                                            ?>
                                    <div class="fieldwrapper">
                                        <div class="fieldtitle">
                                            <label id="startpublishingmsg" for="startpublishing"><?php echo JText::_('Start Publishing'); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                        </div>
                                        <div class="fieldvalue">
                                    <?php
                                    if (isset($this->job)) { //edit
                                        if (isset($this->packagedetail[2]) AND $this->packagedetail[2] == 1) {
                                            echo JHTML::_('calendar', JHtml::_('date', $this->job->startpublishing, $this->config['date_format']), 'startpublishing', 'job_startpublishing', $js_dateformat, array('class' => 'inputbox validate-checkstartdate', 'size' => '10', 'maxlength' => '19', 'readonly' => 'readonly'));
                                            echo '<input type="hidden" name="startpubreadonly" id="startpubreadonly" value="1" size=""/>';
                                        } else
                                            echo JHTML::_('calendar', JHtml::_('date', $this->job->startpublishing, $this->config['date_format']), 'startpublishing', 'job_startpublishing', $js_dateformat, array('class' => 'inputbox validate-checkstartdate', 'size' => '10', 'maxlength' => '19'));
                                        ?>
                            <?php
                        }else {
                            echo JHTML::_('calendar', '', 'startpublishing', 'job_startpublishing', $js_dateformat, array('class' => 'inputbox required validate-checkstartdate', 'size' => '10', 'maxlength' => '19'));
                        }
                        ?>
                                            <input type='hidden' id='startdate-required' name="startdate-required" value="<?php echo $startpub_required; ?>">
                                        </div>
                                    </div>				        
                                            <?php
                                            break;
                                        case "stoppublishing":
                                            ?>
                                            <?php
                                            $stopdatevalue = '';
                                            $stoppublish_required = ($field->required ? 'required' : '');
                                            ?>
                                    <div class="fieldwrapper">
                                        <div class="fieldtitle">
                                            <label id="stoppublishingmsg" for="stoppublishing"><?php echo JText::_('Stop Publishing'); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                        </div>
                                        <div class="fieldvalue">
                                            <?php
                                            if (isset($this->packagedetail[2]) AND $this->packagedetail[2] == 1) {
                                                if (isset($this->job)) {
                                                    echo JHTML::_('calendar', JHtml::_('date', $this->job->stoppublishing, $this->config['date_format']), 'stoppublishing', 'job_stoppublishing', $js_dateformat, array('class' => 'inputbox required validate-checkstopdate', 'size' => '10', 'maxlength' => '19', 'readonly' => 'readonly'));
                                                    echo '<input type="hidden" name="stoppubreadonly" id="stoppubreadonly" value="1" size=""/>';
                                                } else {
                                                    if ($this->packagedetail[4] == 1)
                                                        $enforcetype = JText::_('Days');
                                                    elseif ($this->packagedetail[4] == 2)
                                                        $enforcetype = JText::_('Weeks');
                                                    elseif ($this->packagedetail[4] == 3)
                                                        $enforcetype = JText::_('Months');
                                                    echo $this->packagedetail[3] . ' ' . $enforcetype;
                                                    ?>
                                                    <input type="hidden" name="stoppublishing" id="stoppublishing" value="<?php echo $this->packagedetail[3]; ?>" size=""/>
                                <?php
                            }
                        }else {
                            if (isset($this->job->stoppublishing)) {
                                echo JHTML::_('calendar', JHtml::_('date', $this->job->stoppublishing, $this->config['date_format']), 'stoppublishing', 'job_stoppublishing', $js_dateformat, array('class' => 'inputbox validate-checkstopdate', 'size' => '10', 'maxlength' => '19'));
                            } else {
                                echo JHTML::_('calendar', '', 'stoppublishing', 'job_stoppublishing', $js_dateformat, array('class' => 'inputbox validate-checkstopdate', 'size' => '10', 'maxlength' => '19'));
                            }
                            ?>
                                                <input type='hidden' id='stopdate-required' name="stopdate-required" value="<?php echo $stoppublish_required; ?>">
                                    <?php } ?>
                                        </div>
                                    </div>				        
                                    <?php
                                    break;
                                case "age":
                                    ?>
                        <?php if ($field->published == 1) { ?>
                                        <div class="fieldwrapper">
                                            <div class="fieldtitle">
                                                <label id="agefrommsg" for="agefrom"><?php echo JText::_('Age'); ?></label><?php if ($field->required == 1) echo '&nbsp;<font color="red">*</font>'; ?>
                                            </div>
                                            <div class="fieldvalue">
                                        <?php echo $this->lists['agefrom']; ?>
                                        <?php echo $this->lists['ageto']; ?>
                                            </div>
                                        </div>				        
                        <?php } ?>
                        <?php
                        break;
                    case "gender":
                        ?>
                                            <?php if ($field->published == 1) { ?>
                                        <div class="fieldwrapper">
                                            <div class="fieldtitle">
                                                <label id="gendermsg" for="gender"><?php echo JText::_('Gender'); ?><?php if ($field->required == 1) echo '&nbsp;<font color="red">*</font>'; ?></label>
                                            </div>
                                            <div class="fieldvalue">
                                        <?php echo $this->lists['gender']; ?>
                                            </div>
                                        </div>				        
                        <?php } ?>
                        <?php
                        break;
                    case "careerlevel":
                        ?>
                                            <?php if ($field->published == 1) { ?>
                                        <div class="fieldwrapper">
                                            <div class="fieldtitle">
                                                <label id="careerlevelmsg" for="careerlevel"><?php echo JText::_('Career Level'); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                            </div>
                                            <div class="fieldvalue">
                                        <?php echo $this->lists['careerlevel']; ?>
                                            </div>
                                        </div>				        
                        <?php } ?>

                        <?php
                        break;
                    case "workpermit":
                        ?>
                                    <?php if ($field->published == 1) { ?>
                                        <div class="fieldwrapper">
                                            <div class="fieldtitle">
                                                <label id="workpermitmsg" for="workpermit"><?php echo JText::_('Work Permit'); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                            </div>
                                            <div class="fieldvalue">
                                        <?php echo $this->lists['workpermit']; ?>
                                            </div>
                                        </div>				        
                        <?php } ?>
                        <?php
                        break;
                    case "requiredtravel":
                        ?>
                                            <?php if ($field->published == 1) { ?>
                                        <div class="fieldwrapper">
                                            <div class="fieldtitle">
                                                <label id="requiredtravelmsg" for="requiredtravel"><?php echo JText::_('Required Travel'); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                            </div>
                                            <div class="fieldvalue">
                                                <?php echo $this->lists['requiredtravel']; ?>
                                            </div>
                                        </div>				        
                                            <?php } ?>

                        <?php
                        break;
                    case "description":
                        ?>
                                    <?php if ($field->published == 1) {
                                        $des_required = ($field->required ? 'required' : '');
                                        ?>
                                        <div class="fieldwrapper">
                                            <div class="fieldtitle">
                                                <label id="descriptionmsg" for="description"><strong><?php echo JText::_('Description'); ?></strong></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                            </div>
                                            <div class="fieldvalue">
                                                <?php if ($this->config['job_editor'] == 1) { ?>
                                                    <?php
                                                    $editor = JFactory::getEditor();
                                                    if (isset($this->job))
                                                        echo $editor->display('description', $this->job->description, '100%', '100%', '60', '20', false);
                                                    else
                                                        echo $editor->display('description', '', '100%', '100%', '60', '20', false);
                                                    ?>	
                                                    <input type='hidden' id='description-required' name="description-required" value="<?php echo $des_required; ?>">
                                                <?php }else { ?>
                                                    <textarea class="inputbox <?php echo $des_required; ?>" name="description" id="description" cols="60" rows="5"><?php if (isset($this->job)) echo $this->job->description; ?></textarea>
                                                <?php } ?>
                                            </div>
                                        </div>				        
                                    <?php } ?>
                                    <?php
                                    break;
                                case "agreement":
                                    ?>
                                    <?php if ($field->published == 1) {
                                        $agr_required = ($field->required ? 'required' : '');
                                        ?>
                                        <div class="fieldwrapper">
                                            <div class="fieldtitle">
                                                <label id="agreementmsg" for="agreement"><strong><?php echo JText::_('Agreement'); ?></strong></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                            </div>
                                            <div class="fieldvalue">
                                                <?php if ($this->config['job_editor'] == 1) { ?>
                                                    <?php
                                                    $editor = JFactory::getEditor();
                                                    if (isset($this->job))
                                                        echo $editor->display('agreement', $this->job->agreement, '100%', '100%', '60', '20', false);
                                                    else
                                                        echo $editor->display('agreement', '', '100%', '100%', '60', '20', false);
                                                    ?>	
                                                    <input type='hidden' id='agreement-required' name="agreement-required" value="<?php echo $agr_required; ?>">
                                                <?php }else { ?>
                                                    <textarea class="inputbox <?php echo $agr_required; ?>" name="agreement" id="agreement" cols="60" rows="5"><?php if (isset($this->job)) echo $this->job->agreement; ?></textarea>
                            <?php } ?>
                                            </div>
                                        </div>				        
                                    <?php } ?>
                                    <?php
                                    break;
                                case "qualifications":
                                    ?>
                        <?php if ($field->published == 1) {
                            $qal_required = ($field->required ? 'required' : '');
                            ?>
                                        <div class="fieldwrapper">
                                            <div class="fieldtitle">
                                                <label id="qualificationsmsg" for="qualifications"><strong><?php echo JText::_('Qualifications'); ?></strong></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                            </div>
                                            <div class="fieldvalue">
                                                <?php if ($this->config['job_editor'] == 1) { ?>
                                                    <?php
                                                    $editor = JFactory::getEditor();
                                                    if (isset($this->job))
                                                        echo $editor->display('qualifications', $this->job->qualifications, '100%', '100%', '60', '20', false);
                                                    else
                                                        echo $editor->display('qualifications', '', '100%', '100%', '60', '20', false);
                                                    ?>	
                                                    <input type='hidden' id='qualification-required' name="qualification-required" value="<?php echo $qal_required; ?>">
                            <?php }else { ?>
                                                    <textarea class="inputbox <?php echo $qal_required; ?>" name="qualifications" id="qualifications" cols="60" rows="5"><?php if (isset($this->job)) echo $this->job->qualifications; ?></textarea>
                                        <?php } ?>
                                            </div>
                                        </div>				        
                                    <?php } ?>
                                    <?php
                                    break;
                                case "prefferdskills":
                                    ?>
                                            <?php if ($field->published == 1) {
                                                $pref_required = ($field->required ? 'required' : '');
                                                ?>
                                        <div class="fieldwrapper">
                                            <div class="fieldtitle">
                                                <label id="prefferdskillsmsg" for="prefferdskills"><strong><?php echo JText::_('Preferred Skills'); ?></strong></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                            </div>
                                            <div class="fieldvalue">
                                        <?php if ($this->config['job_editor'] == 1) { ?>
                                            <?php
                                            $editor = JFactory::getEditor();
                                            if (isset($this->job))
                                                echo $editor->display('prefferdskills', $this->job->prefferdskills, '100%', '100%', '60', '20', false);
                                            else
                                                echo $editor->display('prefferdskills', '', '100%', '100%', '60', '20', false);
                                            ?>	
                                                    <input type='hidden' id='prefferdskills-required' name="prefferdskills-required" value="<?php echo $pref_required; ?>">
                            <?php }else { ?>
                                                    <textarea class="inputbox <?php echo $pref_required; ?>" name="prefferdskills" id="prefferdskills" cols="60" rows="5"><?php if (isset($this->job)) echo $this->job->prefferdskills; ?></textarea>
                            <?php } ?>
                                            </div>
                                        </div>				        
                        <?php } ?>
                                    <?php
                                    break;
                                case "city":
                                    ?>
                                    <?php if ($field->published == 1) {
                                        $city_required = ($field->required ? 'required' : '');
                                        ?>
                                        <div class="fieldwrapper">
                                            <div class="fieldtitle">
                                                <label id="citymsg" for="city"><?php echo JText::_('City'); ?></label>&nbsp;<?php
                            if ($field->required == 1) {
                                echo '&nbsp;<font color="red">*</font>';
                            }
                            ?>
                                            </div>
                                            <div class="fieldvalue" id="job_city">
                                                <input class="inputbox <?php echo $city_required; ?>" type="text" name="city" id="city" size="40" maxlength="100" value="" />
                                                <input class="inputbox" type="hidden" name="citynameforedit" id="citynameforedit" size="40" maxlength="100" value="<?php if (isset($this->multiselectedit)) echo $this->multiselectedit; ?>" />
                                            </div>
                                        </div>				        
                                    <?php } ?>
                                    <?php
                                    break;
                                case "metadescription":
                                    if ($field->published == 1) {
                                        $mdes_required = ($field->required ? 'required' : '');
                                        ?>
                                        <div class="fieldwrapper">
                                            <div class="fieldtitle">
                                                <label id="metadescriptionmsg" for="metadescription"><?php echo JText::_('Meta Description'); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                            </div>
                                            <div class="fieldvalue">
                                                <textarea cols="45" rows="5" class="inputbox <?php echo $mdes_required; ?> " name="metadescription" id="metadescription" ><?php if (isset($this->job)) echo $this->job->metadescription; ?></textarea>
                                            </div>
                                        </div>				        
                        <?php } ?>	  
                        <?php
                        break;
                    case "metakeywords":
                        if ($field->published == 1) {
                            $mkyw_required = ($field->required ? 'required' : '');
                            ?>
                                        <div class="fieldwrapper">
                                            <div class="fieldtitle">
                                                <label id="metakeywordsmsg" for="metakeywords"><?php echo JText::_('Meta Keywords'); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                            </div>
                                            <div class="fieldvalue">
                                                <textarea cols="45" rows="5" class="inputbox <?php echo $mkyw_required; ?>" name="metakeywords" id="metakeywords" ><?php if (isset($this->job)) echo $this->job->metakeywords; ?></textarea>
                                            </div>
                                        </div>				        
                                    <?php } ?>	
                                    <?php
                                    break;
                                case "video":
                                    ?>
                                    <?php if ($field->published == 1) {
                                        $video_required = ($field->required ? 'required' : '');
                                        ?>
                                        <div class="fieldwrapper">
                                            <div class="fieldtitle">
                                                <label id="videomsg" for="video"><?php echo JText::_('Video'); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                            </div>
                                            <div class="fieldvalue">
                                                <input class="inputbox <?php echo $video_required; ?>" type="text" name="video" id="video" size="40" maxlength="255" value="<?php if (isset($this->job)) echo $this->job->video; ?>" /><?php echo JText::_('Youtube Video Id'); ?>
                                            </div>
                                        </div>				        
                                    <?php } ?>  
                                    <?php
                                    break;
                                case "map":
                                    ?>
                                    <?php if ($field->published == 1) { ?>
                                        <div class="fieldwrapper">
                                            <div class="fieldtitle">
                                                <label id="mapmsg" for="map"><?php echo JText::_('Map'); ?></label>
                                            </div>
                                            <div class="fieldvalue">
                                                <div id="map" ><div id="map_container"></div></div>
                                                <br/><input type="text" id="longitude" name="longitude" value="<?php if (isset($this->job)) echo $this->job->longitude; ?>"/><?php echo JText::_('Longitude'); ?><!--<div id="coordinatebutton"><input type="button" class="cbutton" value="<?php echo JText::_('Get Address From Marker'); ?>" onclick="Javascript: loadMap(2,'country','state','city');"/></div>-->
                                                <br/><input type="text" id="latitude" name="latitude" value="<?php if (isset($this->job)) echo $this->job->latitude; ?>"/><?php echo JText::_('Latittude'); ?><div id="coordinatebutton"><input type="button" class="cbutton" value="<?php echo JText::_('Set Marker From Address'); ?>" onclick="Javascript: loadMap(3, 'country', 'state', 'city');"/></div>
                                            </div>
                                        </div>				        
                                    <?php } ?>  
                                    <?php
                                    break;
                                default:
                                    if ($field->published == 1) {

                                        foreach ($this->userfields as $ufield) {
                                            if ($field->field == $ufield[0]->id) {
                                                $userfield = $ufield[0];
                                                $i++;
                                                echo '<div class="fieldwrapper">';
                                                if ($userfield->required == 1) {
                                                    echo '  <div class="fieldtitle">
                                                                                <label id=' . $userfield->name . 'msg for="' . $userfield->name . '" >' . $userfield->title . '</label>&nbsp;<font color="red">*</font>
                                                                            </div>';
                                                    if ($userfield->type == 'emailaddress')
                                                        $cssclass = "class ='inputbox required validate-email' ";
                                                    else
                                                        $cssclass = "class ='inputbox required' ";
                                                }else {
                                                    echo '<div class="fieldtitle">'
                                                    . $userfield->title .
                                                    '</div>';
                                                    if ($userfield->type == 'emailaddress')
                                                        $cssclass = "class ='inputbox validate-email' ";
                                                    else
                                                        $cssclass = "class='inputbox' ";
                                                }
                                                echo '<div class="fieldvalue">';

                                                $readonly = $userfield->readonly ? ' readonly="readonly"' : '';
                                                $maxlength = $userfield->maxlength ? 'maxlength="' . $userfield->maxlength . '"' : '';
                                                if (isset($ufield[1])) {
                                                    $fvalue = $ufield[1]->data;
                                                    $userdataid = $ufield[1]->id;
                                                } else {
                                                    $fvalue = "";
                                                    $userdataid = "";
                                                }
                                                echo '<input type="hidden" id="userfields_' . $i . '_id" name="userfields_' . $i . '_id"  value="' . $userfield->id . '"  />';
                                                echo '<input type="hidden" id="userdata_' . $i . '_id" name="userdata_' . $i . '_id"  value="' . $userdataid . '"  />';
                                                switch ($userfield->type) {
                                                    case 'text':
                                                        echo '<input type="text" id="userfields_' . $i . '" name="userfields_' . $i . '" size="' . $userfield->size . '" value="' . $fvalue . '" ' . $cssclass . $maxlength . $readonly . ' />';
                                                        break;
                                                    case 'emailaddress':
                                                        echo '<input type="text" id="userfields_' . $i . '" name="userfields_' . $i . '" size="' . $userfield->size . '" value="' . $fvalue . '" ' . $cssclass . $maxlength . $readonly . ' />';
                                                        break;
                                                    case 'date':
                                                        $userfieldid = 'userfields_' . $i;
                                                        $userfieldid = "'" . $userfieldid . "'";
                                                        echo JHTML::_('calendar', $fvalue, 'userfields_' . $i, 'userfields_' . $i, $js_dateformat, array('class' => 'inputbox', 'size' => '10', 'maxlength' => '19'));
                                                        break;
                                                    case 'textarea':
                                                        echo '<textarea name="userfields_' . $i . '" id="userfields_' . $i . '_field" cols="' . $userfield->cols . '" rows="' . $userfield->rows . '" ' . $readonly . '>' . $fvalue . '</textarea>';
                                                        break;
                                                    case 'checkbox':
                                                        echo '<input type="checkbox" name="userfields_' . $i . '" id="userfields_' . $i . '_field" value="1" ' . 'checked="checked"' . '/>';
                                                        break;
                                                    case 'select':
                                                        $htm = '<select name="userfields_' . $i . '" id="userfields_' . $i . '" >';
                                                        if (isset($ufield[2])) {
                                                            foreach ($ufield[2] as $opt) {
                                                                if ($opt->id == $fvalue)
                                                                    $htm .= '<option value="' . $opt->id . '" selected="yes">' . $opt->js_job_form_field_title . ' </option>';
                                                                else
                                                                    $htm .= '<option value="' . $opt->id . '">' . $opt->js_job_form_field_title . ' </option>';
                                                            }
                                                        }
                                                        $htm .= '</select>';
                                                        echo $htm;
                                                }
                                                echo '</div></div>';
                                            }
                                        }
                                    }
                            }
                        }
                        echo '<input type="hidden" id="userfields_total" name="userfields_total"  value="' . $i . '"  />';
                        ?>
            <?php
            foreach ($this->fieldsordering as $field) {
                switch ($field->field) {
                    case "emailsetting":
                        if ($field->published) {
                            $email_required = ($field->required ? 'required' : '');
                            ?>
                                        <div class="fieldwrapper">
                                            <div class="fieldtitle">
                                                <label id="filter" for="filter"><?php echo JText::_('Email Setting'); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                            </div>
                                            <div class="fieldvalue">
                                                <div id="resumeapplyfilter">
                                                    <table cellpadding="5" cellspacing="0" border="1" width="100%" class="adminform">
                                                        <tr>
                                                            <td>
                                                                <table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform">
                                                                    <tr><td align="left"><span id="jobsapplyalertsettingheading"><?php echo JText::_('Email Setting for Applied Resume'); ?></span></td></tr>
                                                                    <tr>
                                                                        <td>
                                                                            <input type="radio" name="sendemail" value="0" class="radio" <?php
                                        if (isset($this->job)) {
                                            echo ($this->job->sendemail == 0) ? "checked='checked'" : "";
                                        }
                                        ?> /> 							
                                                                            <label for="sendemail"><?php echo JText::_('Do not email me'); ?></label>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <input type="radio" name="sendemail" value="1" class="radio" <?php
                                        if (isset($this->job)) {
                                            echo ($this->job->sendemail == 1) ? "checked='checked'" : "";
                                        }
                                        ?> /> 							
                                                                            <label for="sendemail"><?php echo JText::_('Email me'); ?></label>
                                                                        </td>
                                                                    </tr>
                                                                </table>	
                                                            </td>
                                                        <input type='hidden' id='email-required' name="email-required" value="<?php echo $email_required; ?>">
                                                        </tr>	
                                                    </table>
                                                </div>
                                            </div>
                                        </div>				        
                                        </td>
                                        </tr>	  
                        <?php } ?>	  
                        <?php break; ?>
                <?php } ?>			
            <?php } ?>			
                        <div class="fieldwrapper">
                            <input class="form-control" id="button" class="button" type="submit" name="submit_app" value="<?php echo JText::_('Save job'); ?>" />
                        </div>				        
            <?php
            if (isset($this->job)) {
                if (($this->job->created == '0000-00-00 00:00:00') || ($this->job->created == ''))
                    $curdate = date('Y-m-d H:i:s');
                else
                    $curdate = $this->job->created;
            } else
                $curdate = date('Y-m-d H:i:s');
            ?>
                        <input type="hidden" name="created" value="<?php echo $curdate; ?>" />
                        <input type="hidden" name="view" value="jobposting" />
                        <input type="hidden" name="layout" value="viewjob" />
                        <input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
                        <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                        <input type="hidden" name="task" value="savejob" />
                        <input type="hidden" name="c" value="job" />
                        <input type="hidden" name="check" value="" />
            <?php if (isset($this->packagedetail[0])) { ?>
                            <input type="hidden" id="packageid" name="packageid" value="<?php echo $this->packagedetail[0]; ?>" />
                <?php } ?>	
                        <input type="hidden" name="paymenthistoryid" id="paymenthistoryid" value="<?php echo $this->packagedetail[1]; ?>" />
                        <input type="hidden" name="enforcestoppublishjob" id="enforcestoppublishjob" value="<?php echo $this->packagedetail[2]; ?>" />
                        <input type="hidden" name="enforcestoppublishjobvalue" id="enforcestoppublishjobvalue" value="<?php echo $this->packagedetail[3]; ?>" />
                        <input type="hidden" name="enforcestoppublishjobtype" id="enforcestoppublishjobtype" value="<?php echo $this->packagedetail[4]; ?>" />

                        <input type="hidden" name="packagearray" id="packagearray" value="" />

                        <input type="hidden" name="Itemid" id="Itemid" value="<?php echo $this->Itemid; ?>" />
                        <input type="hidden" name="id" id="id" value="<?php if (isset($this->job)) echo $this->job->id; ?>" />
                        <input type="hidden" name="j_dateformat" id="j_dateformat" value="<?php echo $js_scriptdateformat; ?>" />

                        <input type="hidden" name="default_longitude" id="default_longitude" value="<?php echo $this->config['default_longitude']; ?>" />
                        <input type="hidden" name="default_latitude" id="default_latitude" value="<?php echo $this->config['default_latitude']; ?>" />

                        <script language=Javascript>

                            function getdepartments(src, val) {
                                jQuery("#" + src).html("Loading ...");
                                jQuery.post('index.php?option=com_jsjobs&task=department.listdepartments', {val: val}, function (data) {
                                    if (data) {
                                        jQuery("#" + src).html(data);
                                        jQuery("#" + src + " select.jsjobs-cbo").chosen();
                                    }
                                });
                            }
                            function fj_getsubcategories(src, val) {
                                jQuery("#" + src).html("Loading ...");
                                jQuery.post('index.php?option=com_jsjobs&c=subcategory&task=listsubcategories', {val: val}, function (data) {
                                    if (data) {
                                        jQuery("#" + src).html(data);
                                        jQuery("#" + src + " select.jsjobs-cbo").chosen();
                                    } else {
                                        alert('<?php echo JText::_('Error While Getting Subcategories'); ?>');
                                    }
                                });
                            }
                        </script>


                    </form>
                </div>
            <?php } else { // user has not company  
                ?>  
                <div class="js_job_error_messages_wrapper">
                    <div class="js_job_messages_image_wrapper">
                        <img class="js_job_messages_image" src="components/com_jsjobs/images/3.png"/>
                    </div>
                    <div class="js_job_messages_data_wrapper">
                        <span class="js_job_messages_main_text">
                            <?php echo JText::_('Please Add Company Brfore New Job'); ?>
                        </span>
                        <span class="js_job_messages_block_text">
                            <?php echo JText::_('Please Add Company Brfore New Job'); ?>
                        </span>
                        <div class="js_job_messages_button_wrapper">
                            <a class="js_job_message_button" href="index.php?option=com_jsjobs&c=company&view=company&layout=formcompany"><?php echo "  " . JText::_('Add Company'); ?></a>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else { // can not add new job
        switch ($this->canaddnewjob) {
            case NO_PACKAGE:
                $link = "index.php?option=com_jsjobs&c=employerpackages&view=employerpackages&layout=packages&Itemid=".$this->Itemid;
                $this->jsjobsmessages->getPackageExpireMsg('Package Not Purchased', 'Package is required to perform this action, please get package', $link);
                break;
            case EXPIRED_PACKAGE:
                $link = "index.php?option=com_jsjobs&c=employerpackages&view=employerpackages&layout=packages&Itemid=".$this->Itemid;
                $this->jsjobsmessages->getPackageExpireMsg('Your current package is expired', 'Package is required to perform this action and your current package is expired, please get new package', $link);
                break;
            case JOB_LIMIT_EXCEEDS:
                $link = "index.php?option=com_jsjobs&c=employerpackages&view=employerpackages&layout=packages&Itemid=".$this->Itemid;
                $this->jsjobsmessages->getPackageExpireMsg('You can not add new job, your job limit exceeds', 'You Can Not Add New Job, Pleae Get Package To Extend Your Job Limit', $link);
                break;
            case JOBSEEKER_NOT_ALLOWED_EMPLOYER_PRIVATE_AREA:
                $this->jsjobsmessages->getAccessDeniedMsg('Job Seeker Not Allow', 'Job seeker is not allow in employer private area');
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

    <style type="text/css">
        div#map_container{
            width:100%;
            height:350px;
        }
    </style>
    <script type="text/javascript"   src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>

    <script type="text/javascript">

                jQuery(document).ready(function () {
                    var cityname = jQuery("#citynameforedit").val();
                    if (cityname != "") {
                        jQuery("#city").tokenInput("<?php echo JURI::root() . "index.php?option=com_jsjobs&c=cities&task=getaddressdatabycityname"; ?>", {
                            theme: "jsjobs",
                            preventDuplicates: true,
                            hintText: "<?php echo JText::_('Type In A Search Term'); ?>",
                            noResultsText: "<?php echo JText::_('No Results'); ?>",
                            searchingText: "<?php echo JText::_('Searching...'); ?>",
                            //tokenLimit: 1,
                            prePopulate: <?php if (isset($this->multiselectedit))
    echo $this->multiselectedit;
else
    echo "''";
?>
                        });
                    } else {
                        jQuery("#city").tokenInput("<?php echo JURI::root() . "index.php?option=com_jsjobs&c=cities&task=getaddressdatabycityname"; ?>", {
                            theme: "jsjobs",
                            preventDuplicates: true,
                            hintText: "<?php echo JText::_('Type In A Search Term'); ?>",
                            noResultsText: "<?php echo JText::_('No Results'); ?>",
                            searchingText: "<?php echo JText::_('Searching...'); ?>",
                            //tokenLimit: 1

                        });
                    }

                    jQuery("select.jsjobs-cbo").chosen();
                    jQuery("input.jsjobs-inputbox").button()
                            .css({
                                'width': '192px',
                                'border': '1px solid #A9ABAE',
                                'cursor': 'text',
                                'margin': '0',
                                'padding': '4px'
                            });

                });




                function changeDate(id) {
                    var packagearray = '<?php echo json_encode($this->packagecombo[1]); ?>';
                    var objarray = eval('(' + packagearray + ')');
                    var currentpackage = objarray[id];
                    document.getElementById('packageid').value = currentpackage.packageid;
                    if (currentpackage.enforcestoppublishjob == 1) {
                        document.getElementById('paymenthistoryid').value = currentpackage.paymentid;
                        document.getElementById('enforcestoppublishjob').value = currentpackage.enforcestoppublishjob;
                        document.getElementById('enforcestoppublishjobvalue').value = currentpackage.enforcestoppublishjobvalue;
                        document.getElementById('enforcestoppublishjobtype').value = currentpackage.enforcestoppublishjobtype;
                        switch (currentpackage.enforcestoppublishjobtype) {
                            case "1":
                                var durationtype = 'Days';
                                break;
                            case "2":
                                var durationtype = 'Week';
                                break;
                            case "3":
                                var durationtype = 'Month';
                                break;
                        }
                        var days = currentpackage.enforcestoppublishjobvalue.toString();
                        var duration = days + ' ' + durationtype;
                        var stoppublishing = document.getElementById('stoppublishingdate').innerHTML;
                        document.getElementById('stoppublishingdate').innerHTML = duration;
                    } else {
                        var jversion = '<?php echo JVERSION; ?>';
                        var oInput, oImg;
                        oInput = document.createElement("INPUT");
                        oInput.name = "stoppublishing";
                        oInput.setAttribute('id', "job_stoppublishing");
                        oInput.setAttribute('size', "10");
                        oInput.setAttribute('class', "inputbox required validate-checkstopdate");

                        oImg = document.createElement("IMG");
                        oImg.src = "<?php echo $link = JURI::root(); ?>/media/system/images/calendar.png";
                        oImg.setAttribute('id', "job_stoppublishing_img");

                        document.getElementById('stoppublishingdate').innerHTML = '';
                        document.getElementById('stoppublishingdate').appendChild(oInput);
                        document.getElementById('stoppublishingdate').appendChild(oImg);
                        Calendar.setup({
                            inputField: "job_stoppublishing", // id of the input field
                            ifFormat: "<?php echo $js_dateformat; ?>", // format of the input field
                            button: "job_stoppublishing_img", // trigger for the calendar (button ID)
                            align: "Tl", // alignment (defaults to "Bl")
                            singleClick: true,
                            firstDay: 1
                        });
                    }
                }
                var map_obj = document.getElementById('map_container');
                if (typeof map_obj !== 'undefined' && map_obj !== null) {
                    window.onload = loadMap(1, '', '', '');
                }


                function loadMap(callfrom, country, state, city) {
                    var values_longitude = [];
                    var values_latitude = [];
                    var latedit = [];
                    var longedit = [];
                    var longitude = '';
                    var latitude = '';

                    var long_obj = document.getElementById('longitude');
                    if (typeof long_obj !== 'undefined' && long_obj !== null) {
                        longitude = document.getElementById('longitude').value;
                        if (longitude != '')
                            longedit = longitude.split(",");
                    }
                    var lat_obj = document.getElementById('latitude');
                    if (typeof long_obj !== 'undefined' && long_obj !== null) {
                        latitude = document.getElementById('latitude').value;
                        if (latitude != '')
                            latedit = latitude.split(",");
                    }

                    var default_latitude = document.getElementById('default_latitude').value;
                    var default_longitude = document.getElementById('default_longitude').value;
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
                            if (callfrom == 1) {
                                var marker = new google.maps.Marker({
                                    position: latlng,
                                    map: map,
                                    visible: true,
                                });

                                document.getElementById('longitude').value = marker.position.lng();
                                document.getElementById('latitude').value = marker.position.lat();
                                marker.setMap(map);
                                values_longitude.push(longedit[i]);
                                values_latitude.push(latedit[i]);
                                document.getElementById('latitude').value = values_latitude;
                                document.getElementById('longitude').value = values_longitude;

                                //lastmarker = marker;
                            }

                        }
                    } else {
                        var latlng = new google.maps.LatLng(default_latitude, default_longitude);
                        zoom = 4;
                        var myOptions = {
                            zoom: zoom,
                            center: latlng,
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                        };
                        var map = new google.maps.Map(document.getElementById("map_container"), myOptions);
                        var lastmarker = new google.maps.Marker({
                            postiion: latlng,
                            map: map,
                        });
                        if (callfrom == 1) {
                            var marker = new google.maps.Marker({
                                position: latlng,
                                map: map,
                            });
                            document.getElementById('longitude').value = marker.position.lng();
                            document.getElementById('latitude').value = marker.position.lat();
                            marker.setMap(map);
                            values_longitude.push(document.getElementById('longitude').value);
                            values_latitude.push(document.getElementById('latitude').value);

                            lastmarker = marker;
                        }

                    }
                    google.maps.event.addListener(map, "click", function (e) {
                        var latLng = new google.maps.LatLng(e.latLng.lat(), e.latLng.lng());
                        geocoder = new google.maps.Geocoder();
                        geocoder.geocode({'latLng': latLng}, function (results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                //lastmarker.setMap(null);
                                var marker = new google.maps.Marker({
                                    position: results[0].geometry.location,
                                    map: map,
                                });
                                marker.setMap(map);
                                //lastmarker = marker;
                                document.getElementById('latitude').value = marker.position.lat();
                                document.getElementById('longitude').value = marker.position.lng();
                                values_longitude.push(document.getElementById('longitude').value);
                                values_latitude.push(document.getElementById('latitude').value);
                                document.getElementById('latitude').value = values_latitude;
                                document.getElementById('longitude').value = values_longitude;

                            } else {
                                alert("Geocode was not successful for the following reason: " + status);
                            }
                        });
                    });


                    if (callfrom == 3) {
                        var value = '';
                        var zoom = 4;
                        jQuery("div#job_city > ul > li > p").each(function () {
                            value = jQuery(this).html();
                            if (value != '') {
                                geocoder = new google.maps.Geocoder();
                                geocoder.geocode({'address': value}, function (results, status) {
                                    if (status == google.maps.GeocoderStatus.OK) {
                                        map.setCenter(results[0].geometry.location);
                                        document.getElementById('latitude').value = results[0].geometry.location.lat();
                                        document.getElementById('longitude').value = results[0].geometry.location.lng();
                                        map.setZoom(zoom);
                                        //lastmarker.setMap(null);
                                        var marker = new google.maps.Marker({
                                            position: results[0].geometry.location,
                                            map: map,
                                        });
                                        marker.setMap(map);
                                        values_longitude.push(document.getElementById('longitude').value);
                                        values_latitude.push(document.getElementById('latitude').value);
                                        document.getElementById('latitude').value = values_latitude;
                                        document.getElementById('longitude').value = values_longitude;

                                        //lastmarker = marker;
                                    } else {
                                        alert("Geocode was not successful for the following reason: " + status);
                                    }
                                });
                            }
                        });
                    }
                    jQuery(document).ready(function () {
                        jQuery('div[data-jquery="hideit"]').each(function () {
                            jQuery(this).hide()
                        });
                    });
                }
    </script>
