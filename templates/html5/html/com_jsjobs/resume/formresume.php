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
 * File Name:   views/application/tmpl/formresume.php
  ^
 * Description: template for form resume
  ^
 * History:     NONE
  ^
 */
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_jsjobs/css/token-input-jsjobs.css');
$document->addStyleSheet('components/com_jsjobs/css/combobox/chosen.css');
if (!isset($this->isadmin)) { // no need to add these files in administrator case
    JHTML::_('behavior.calendar');
    JHTML::_('behavior.formvalidation');
    if (JVERSION < 3) {
        JHtml::_('behavior.mootools');
        $document->addScript('components/com_jsjobs/js/jquery.js');
    } else {
        JHtml::_('behavior.framework');
        JHtml::_('jquery.framework');
    }
}
$document->addScript('components/com_jsjobs/js/jquery.tokeninput.js');
$document->addScript('components/com_jsjobs/js/multi-files-selector.js');

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
JText::script('Select Files');

global $mainframe;

if ($this->config['captchause'] == 0) {
    JPluginHelper::importPlugin('captcha');
    $dispatcher = JDispatcher::getInstance();
    $dispatcher->trigger('onInit', 'dynamic_recaptcha_1');
}

$resumeid = -1;
if (isset($this->resumeid) && !empty($this->resumeid)) {
    $resumeid = $this->resumeid;
}
?>
<input type="hidden" id="resume_temp" name="resume_temp" class="resume_temp" value="<?php echo $resumeid; ?>" />
<?php if ($this->isadmin == 0) { // no need to add these files in administrator case ?>
        <div id="js_menu_wrapper">
            <?php
            if (isset($this->jobseekerlinks)) {
                if (sizeof($this->jobseekerlinks) != 0) {
                    foreach ($this->jobseekerlinks as $lnk) {
                        ?>
                        <a class="js_menu_link <?php if ($lnk[2] == 'coverletter') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
                        <?php
                    }
                }
            }
            if (isset($this->employerlinks)) {
                if (sizeof($this->employerlinks) != 0) {
                    foreach ($this->employerlinks as $lnk) {
                        ?>
                        <a class="js_menu_link <?php if ($lnk[2] == 'coverletter') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
                        <?php
                    }
                }
            }
            ?>
        </div>
<?php } ?>
<?php
if ($this->config['offline'] == '1') {
    $this->jsjobsmessages->getSystemOfflineMsg($this->config);
} else {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://"; ?>
    <script type="text/javascript" src="<?php echo $protocol; ?>maps.googleapis.com/maps/api/js?sensor=false"></script>
    <script type="text/javascript" src="<?php echo JURI::root(); ?>media/editors/tinymce/tinymce.min.js"></script>    <script type="text/javascript">
        function myValidateForm(f){
            if (document.formvalidator.isValid(f)) {
                return true;
            } else {
                alert('<?php echo JText::_('Some values are not acceptable, please retry'); ?>');
                return false;
            }
        }
        jQuery(document).ready(function () {
            jQuery("body").delegate("button.delegateonclick", "click", function (e) {
                e.preventDefault();
            });
            var id = jQuery("#resume_temp").val();
            if (id != -1) {
                jQuery("#resumeFormContainer").append('<div id="js-resume-section-view" class="js-row no-margin"></div>');
                getResume(id, -1, 'js-resume-section-view', 'view', 'personal');
                <?php
                foreach ($this->fieldsordering AS $field) {
                    switch ($field->field) {
                        case 'section_address':
                            $sectionsArray[] = "address";
                            break;
                        case 'section_institute':
                            $sectionsArray[] = "institute";
                            break;
                        case 'section_employer':
                            $sectionsArray[] = "employer";
                            break;
                        case 'section_skills':
                            $sectionsArray[] = "skills";
                            break;
                        case 'section_resume':
                            $sectionsArray[] = "editor";
                            break;
                        case 'section_reference':
                            $sectionsArray[] = "reference";
                            break;
                        case 'section_language';
                            $sectionsArray[] = "language";
                            break;
                    }
                }
                for ($i = 0; $i < count($sectionsArray); $i++) {
                    echo 'jQuery("#' . $sectionsArray[$i] . 'FormContainer").append(\'<div id="js-resume-' . $sectionsArray[$i] . '-section-view" class="js-row no-margin"></div>\');';
                    echo 'getResume(id, -1, "js-resume-' . $sectionsArray[$i] . '-section-view", "view", "' . $sectionsArray[$i] . '");';
                }
                ?>
            } else {
                getResume(id, -1, "resumeFormContainer", "form", "personal");
            }
        });

        function deleteResumeSection(resumeid, sectionid, isadmin, section) {
            var result = confirm("<?php echo JText::_('Are you sure to delete'); ?>");
            if(result == true){
                jQuery("#ajax-loader").show();
                if (jQuery("#js_main_wrapper").find("form").length > 0 || jQuery("#resumeFormContainer").find("form").length > 0) {
                    jQuery("div." + section + "-section").append('<div class="section-error js-col-lg-12 js-col-md-12"><?php echo JText::_("Please save opened form"); ?></div>');
                    jQuery("#ajax-loader").hide();
                    return false;
                }
                if (resumeid == -1) {
                    alert('<?php echo JText::_("Visitor is not allow to edit resume"); ?>');
                    return false;
                }
                jQuery.post("<?php echo JURI::root(); ?>index.php?option=com_jsjobs&c=resume&task=deleteresumesection",{resumeid:resumeid,sectionid:sectionid,isadmin:isadmin,section:section},function(data){
                    if(data){
                        if(data == 1){
                            jQuery("#" + section + "_" + sectionid).remove();
                            jQuery("div." + section + "-section").append('<div class="section-error js-col-lg-12 js-col-md-12"><?php echo JText::_("Record has been deleted"); ?></div>');
                        }else if(data == 2){
                            jQuery("div." + section + "-section").append('<div class="section-error js-col-lg-12 js-col-md-12"><?php echo JText::_("Not your resume"); ?></div>');
                        }else{
                            jQuery("div." + section + "-section").append('<div class="section-error js-col-lg-12 js-col-md-12"><?php echo JText::_("Error deleting record"); ?></div>');
                        }
                    }else{
                        jQuery("div." + section + "-section").append('<div class="section-error js-col-lg-12 js-col-md-12"><?php echo JText::_("Error deleting record"); ?></div>');
                    }
                });
                setTimeout(function(){
                    jQuery("div." + section + "-section").find('div.section-error').remove();
                }, 3000);
                jQuery("#ajax-loader").hide();
            }
        }
        function getResumeForm(resumeid, sectionid, formfor) {
            jQuery("#ajax-loader").show();
            if (jQuery("#js_main_wrapper").find("form").length > 0 || jQuery("#resumeFormContainer").find("form").length > 0) {
                jQuery("div." + formfor + "-section").append('<div class="section-error js-col-lg-12 js-col-md-12"><?php echo JText::_("Please save opened form"); ?></div>');
                jQuery("#ajax-loader").hide();
                return false;
            }
            if (resumeid == -1) {
                alert('<?php echo JText::_("Visitor is not allow to edit resume"); ?>');
                return false;
            }
            if (formfor == 'personal') {
                jQuery("#js-resume-section-view").remove();
                getResume(resumeid, -1, "resumeFormContainer", "form", "personal");
                jQuery("#resumeAddressForm").hide("slow");
                jQuery("#resumeInstituteForm").hide("slow");
                jQuery("#resumeEmployerForm").hide("slow");
                jQuery("#resumeLanguageForm").hide("slow");
            } else {
                if (sectionid == -1) {
                    jQuery("#ajax-loader").show();
                    jQuery("#add-resume-" + formfor).remove();
                    var formforCopy = formfor;
                    var capitalizedForm = formforCopy.slice(0, 1).toUpperCase() + formfor.slice(1);
                    getResume(resumeid, -1, "js-resume-" + formfor + "-section-view", "form", formfor);
                    jQuery("#resume" + capitalizedForm + "Form").css('display', 'block');
                    jQuery("#ajax-loader").hide();
                } else {
                    if (formfor == "skills" || formfor == "editor") {
                        getResume(resumeid, -1, "" + formfor + "FormContainer", "form", formfor);
                        if (formfor == "editor") {
                            jQuery("#" + formfor + "View").remove();
                        } else {
                            jQuery("#" + formfor).remove();
                        }
                    } else {
                        jQuery("#" + formfor + "_" + sectionid).html("");
                        getResume(resumeid, sectionid, formfor + "_" + sectionid, "form", formfor + "");
                    }
                }
            }
        }
        function getResume(resumeid, sectionid, src, sectiontype, sectionName) {
            jQuery("#ajax-loader").show();
            if (typeof (resumeid) == 'string' || typeof (sectionid) == 'string') {
                resumeid = parseInt(resumeid);
                sectionid = parseInt(sectionid);
            }

            if (sectiontype == "form" && sectionName == "editor") {
                function isBrowserIE() {
                    return navigator.appName == "Microsoft Internet Explorer";
                }
                function jInsertEditorText(text, editor) {
                    if (isBrowserIE()) {
                        if (window.parent.tinyMCE) {
                            window.parent.tinyMCE.selectedInstance.selection.moveToBookmark(window.parent.global_ie_bookmark);
                        }
                    }
                    tinyMCE.execCommand('mceInsertContent', false, text);
                }
                var global_ie_bookmark = false;
                function IeCursorFix() {
                    if (isBrowserIE()) {
                        tinyMCE.execCommand('mceInsertContent', false, '');
                        global_ie_bookmark = tinyMCE.activeEditor.selection.getBookmark(false);
                    }
                    return true;
                }
            }
            jQuery.post('<?php echo JURI::root() . "index.php?option=com_jsjobs&c=resume&task=getresume"; ?>', {resumeid: resumeid, sectionid: sectionid, sectionName: sectionName, sectiontype: sectiontype, isadmin : <?php echo $this->isadmin; ?>}, function (data) {
                if (data) {
                    if (sectionName == 'personal') {
                        jQuery("#" + src).html("");
                        jQuery("#" + src).append(data);
                        if (sectiontype == 'form') {
                            getResumeFiles(resumeid, "existingFiles", "form");
                            jQuery("#resumeForm").append('<div id="black_wrapper_resumefiles" style="display:none;"></div><div id="resumeFilesPopup" class="resumeFilesPopup"><div id="resumeFiles_headline"><?php echo JText::_("Resume Files"); ?></div><div id="fileSelectionButton" class="fileSelectionButton"></div><div class="chosenFiles_heading"><span><?php echo JText::_("Resume Files"); ?></span></div><div id="filesInfo" class="filesInfo js-row no-margin"></div><div class="resumeFiles_close"><span id="closepopup"><?php echo JText::_("Ok"); ?></span></div></div>');
                            jQuery("div#black_wrapper_resumefiles").click(function () {
                                jQuery("div#resumeFilesPopup").css("visibility", "hidden");
                                jQuery("#black_wrapper_resumefiles").fadeOut();
                            });
                            jQuery("#closepopup").click(function () {
                                jQuery("div#resumeFilesPopup").css("visibility", "hidden");
                                jQuery("#black_wrapper_resumefiles").fadeOut();
                            });
                            <?php if ((isset($this->isadmin) AND $this->isadmin == 1)|| (isset($this->canaddnewresume) && $this->canaddnewresume == VALIDATE)) { //only execute if the resume form is allowed and open?>
                                    var vdate_start = document.getElementById("date_start");
                                    if (vdate_start != null){    
                                        Calendar.setup({inputField: "date_start",ifFormat: "<?php echo $js_dateformat; ?>",button: "date_start_img",align: "Tl",singleClick: true,firstDay: 0});
                                    }
                                    var vdate_of_birth = document.getElementById("date_of_birth");
                                    if (vdate_of_birth != null){    
                                        Calendar.setup({inputField: "date_of_birth",ifFormat: "<?php echo $js_dateformat; ?>",button: "date_of_birth_img",align: "Tl",singleClick: true,firstDay: 0});
                                    }
                            <?php } ?>
                        }
                        if (sectiontype == "view") {
                            getResumeFiles(resumeid, "resumeFilesList", "view")
                        }
                    } else {
                        if (sectiontype == 'form') {
                            if (sectionName == "address" || sectionName == "institute" || sectionName == "employer" || sectionName == "reference") {
                                data = jQuery.parseJSON(data);
                                inputfor = sectionName;
                                cityid = data.city_id;
                                cityname = data.city_name;
                                jQuery("#" + src).append(data.data);
                                getTokenInput(inputfor, cityid, cityname);
                            } else { // for skills, editor and language forms
                                jQuery("#" + src).append(data);
                            }
                        } else {
                            jQuery("#" + src).append(data);
                        }
                        if (sectiontype == "form" && sectionName == 'editor') {
                            if(typeof(tinyMCE) !== 'undefined'){
                                tinymce.each(tinyMCE.editors, function(e) {
                                    if(typeof(e) !== 'undefined'){
                                        try {
                                            tinymce.remove(e);
                                        } catch (ex) {
                                            console.log(ex)
                                        }
                                    }
                                });
                            }
                            tinyMCE.init({mode : "specific_textareas",editor_selection:'mce_editable'});
                        }
                        if (sectionName == 'employer' && sectiontype == 'form') {
                            Calendar.setup({inputField: "employer_to_date", ifFormat: "<?php echo $js_dateformat; ?>", button: "employer_to_date_img", align: "Tl", singleClick: true, firstDay: 0});
                            Calendar.setup({inputField: "employer_from_date", ifFormat: "<?php echo $js_dateformat; ?>", button: "employer_from_date_img", align: "Tl", singleClick: true, firstDay: 0});
                        }
                    }
                    jQuery("#ajax-loader").hide();
                    return true;
                } else {
                    alert('<?php echo JText::_("Error occurred while getting resume"); ?>');
                    jQuery("#ajax-loader").hide();
                    return false;
                }
            });
        }
        function fj_getsubcategories(src, val) {
            jQuery("#" + src).html("");
            jQuery.post("<?php echo JURI::root(); ?>index.php?option=com_jsjobs&c=subcategory&task=listsubcategories", {categoryid: val}, function (data) {
                if (data) {
                    jQuery("#" + src).html(data);
                    jQuery("#" + src + " select.jsjobs-cbo").chosen();
                } else {
                    alert("<?php echo JText::_('Error While Getting Subcategories'); ?>");
                }
            });
        }
        function resumePhotoSelection() {
            var photoValidated = 1;
            var photo = document.getElementById("photo").files[0];

            var maxPhotoSize = <?php echo $this->config['resume_photofilesize']; ?>;
            var photoTypes = "<?php echo $this->config['image_file_type']; ?>";
            var photoTypesArray = photoTypes.split(",");

            var photoExt = photo.name.split(".").pop();
            var photoSize = (photo.size / 1024);

            if (jQuery.inArray(photoExt, photoTypesArray) < 0) {
                alert("<?php echo JText::_('File extension mismatched'); ?>");
                jQuery("#photo").val("");
                photoValidated = 0;
            }
            if (photoSize > maxPhotoSize) {
                alert("<?php echo JText::_('File size exceeded'); ?>");
                jQuery("#photo").val("");
                photoValidated = 0;
            }
            if (photoValidated == 1) {
                document.getElementById("uploadPhotoFile").value = jQuery("#photo").val();
            }
        }
        function resumeFilesSelection() {
            jQuery("#black_wrapper_resumefiles").fadeIn(300, function () {
                jQuery("div#resumeFilesPopup").css("visibility", "visible");
                var resumeid = jQuery("#resume_temp").val();
                if (resumeid != -1) {
                    jQuery("#chosenFiles").remove();
                    jQuery("#filesInfo").prepend('<div id="chosenFiles" class="chosenFiles js-row no-margin"></div>');
                    getResumeFiles(resumeid, "chosenFiles", "popup");
                }
                var inputs = jQuery("#fileSelectionButton").children("input").length;
                if (inputs == 0) {
                    var postMaxSize = <?php echo (int) ini_get('post_max_size'); ?>;
                    var memoryLimit = <?php echo (int) ini_get('memory_limit'); ?>;
                    var maxResumeFiles = '<?php echo $this->config["document_max_files"]; ?>';
                    var maxDocumentSize = '<?php echo $this->config["document_file_size"]; ?>';
                    var fileTypes = '<?php echo $this->config["document_file_type"]; ?>';
                    var clearFilesLang = '<?php echo JText::_("Clear Files"); ?>';
                    var juriPath = "<?php echo JURI::root(); ?>";
                    var fileRejLang = '<?php echo JText::_("This file will be rejected"); ?>';
                    var errorLang = '<?php echo JText::_("Error"); ?>';
                    var extMissLang = '<?php echo JText::_("File extension mismatched"); ?>';
                    var sizeExceedLang = '<?php echo JText::_("Maximum size limit exceeded"); ?>';
                    var andSizeExceedLang = '<?php echo JText::_("And file size exceeded"); ?>';
                    var filesLimitExceedLang = '<?php echo JText::_("Maximum resume files limit occurred"); ?>';
                    var noFileLang = '<?php echo JText::_("No File Selected"); ?>';
                    addNewResumeInput(postMaxSize, memoryLimit, maxResumeFiles, maxDocumentSize, fileTypes, fileRejLang, clearFilesLang, errorLang, extMissLang, sizeExceedLang, andSizeExceedLang, filesLimitExceedLang, noFileLang, juriPath);
                }
            });
        }
        function getResumeFiles(resumeid, src, filesfor) {
            jQuery.post('<?php echo JURI::root() . "index.php?option=com_jsjobs&c=resume&task=getresumefiles"; ?>', {resumeid: resumeid, filesfor: filesfor}, function (data) {
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
                    //alert("<?php echo JText::_('Error occurred while getting resume uploaded resume files'); ?>");
                }
            });
        }
        function cancelForm(resumeid, cancelfor) {
            jQuery("#" + cancelfor + "FormContainer form").remove();
            jQuery("#js-resume-" + cancelfor + "-section-view").remove();
            jQuery("#" + cancelfor + "FormContainer").append('<div id="js-resume-' + cancelfor + '-section-view" class="js-row no-margin"></div>');
            getResume(resumeid, -1, "js-resume-" + cancelfor + "-section-view", "view", cancelfor);
            return false;
        }
        function deleteResumeFile(fileid, resumeid) {
            var confirmDelete = confirm("<?php echo JText::_('Confirm to delete resume file?'); ?>");
            if (confirmDelete == false) {
                return false;
            }
            jQuery("#ajax-loader").show();
            jQuery.post('<?php echo JURI::root(); ?>index.php?option=com_jsjobs&c=resume&task=deleteresumefiles', {fileid: fileid, resumeid: resumeid}, function (data) {
                if (data) {
                    jQuery("#existingFiles span#" + fileid).remove();
                    jQuery("#chosenFiles div#" + fileid).remove();
                } else {
                    alert('<?php echo JText::_("Error occurred while deleting resume file"); ?>');
                }
            });
            jQuery("#ajax-loader").hide();
        }
        function showMoreBasics() {
            if (jQuery("span#show-more-basics").html() == '<?php echo JText::_("Show More"); ?>') {
                jQuery("span#show-more-basics").html('<?php echo JText::_("Show Less"); ?>');
            } else {
                jQuery("span#show-more-basics").html('<?php echo JText::_("Show More"); ?>');
            }
            jQuery("#js-resume-more-options-container").toggle('slow');
        }
        function submitResumeForm() {
            jQuery("#ajax-loader").show();
            var f = document.forms["resumeForm"];
            var validation = myValidate(f);
            if (validation == false) {
                jQuery("#ajax-loader").hide();
                return false;
            }
            if (jQuery("#searchable:checkbox:checked").length == 0) {
                jQuery("#searchable").val(0);
            } else {
                jQuery("#searchable").val(1);
            }

            if (jQuery("#iamavailable:checkbox:checked").length == 0) {
                jQuery("#iamavailable").val(0);
            } else {
                jQuery("#iamavailable").val(1);
            }
            var formdata = new FormData(document.forms["resumeForm"]);
            jQuery.ajax({
                url: '<?php echo JURI::root(); ?>index.php?option=com_jsjobs&c=resume&task=saveresume',
                type: "POST",
                data: new FormData(document.forms["resumeForm"]),
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    var dataArray = data.split(",");
                    var msg = '';
                    switch (dataArray[1]) {
                        case '1':
                            msg = "<?php echo JText::_('Resume Saved'); ?>";
                            break;
                        case '2':
                            msg = "<?php echo JText::_('Error occurred while saving resume'); ?>";
                            break;
                    }
                    if (jQuery("#js_main_wrapper").find("div.ajax-response-msg").length > 0) {
                        jQuery("div.ajax-response-msg").remove();
                    }
                    jQuery("#js_main_wrapper").prepend('<div class="ajax-response-msg js-col-lg-12 js-col-md-12 js-col-xs-12">' + msg + '</div>');
                    if (jQuery("#resumeFormContainer").children("input").length == 0) {
                        jQuery("#resumeFormContainer").append("<div id='js-resume-section-view' class='js-row no-margin'></div>");
                    }
                    setTimeout(function () {
                        jQuery("div.ajax-response-msg").remove();
                    }, 2500);
                    jQuery("#resumeForm").remove();
                    var view = getResume(dataArray[0], -1, "js-resume-section-view", "view", "personal");
                    jQuery("#resume_temp").val(dataArray[0]);
                    jQuery("#js-resume-section-view").css("display", "block");
                }
            });
            jQuery("#ajax-loader").hide();
        }
        function submitResumeAddressForm() {
            var f = document.forms["resumeAddressForm"];
            var validation = myValidateForm(f);
            if (validation == false) {
                jQuery("#ajax-loader").hide();
                return false;
            }
            jQuery("#ajax-loader").show();
            jQuery.ajax({
                url: '<?php echo JURI::root(); ?>index.php?option=com_jsjobs&c=resume&task=saveresumesection',
                type: "POST",
                data: new FormData(document.forms["resumeAddressForm"]),
                contentType: false,
                cache: false,
                processData: false,
                success: function (data)
                {
                    if (data == "") {
                        alert("<?php echo JText::_('You have added maximum addresses'); ?>");
                        jQuery("#ajax-loader").hide();
                        return false;
                    } else {
                        jQuery("#js_main_wrapper").prepend('<div class="ajax-response-msg js-col-lg-12 js-col-md-12 js-col-xs-12"><?php echo JText::_("Resume Saved"); ?></div>');
                        setTimeout(function () {
                            jQuery("div.ajax-response-msg").remove();
                        }, 1000);
                        jQuery("#js-resume-address-section-view").remove();
                        jQuery("#addressFormContainer").append('<div id="js-resume-address-section-view" class="js-row no-margin"></div>');
                        getResume(data, -1, "js-resume-address-section-view", "view", "address");
                        jQuery("#js-resume-address-section-view").css("display", "block");
                        jQuery("#resumeAddressForm").remove();
                    }
                }
            });
            jQuery("#ajax-loader").hide();
        }
        function submitResumeInstituteForm() {
            var f = document.forms["resumeInstituteForm"];
            var validation = myValidateForm(f);
            if (validation == false) {
                jQuery("#ajax-loader").hide();
                return false;
            }
            jQuery("#ajax-loader").show();
            jQuery.ajax({
                url: '<?php echo JURI::root(); ?>index.php?option=com_jsjobs&c=resume&task=saveresumesection',
                type: "POST",
                data: new FormData(document.forms["resumeInstituteForm"]),
                contentType: false,
                cache: false,
                processData: false,
                success: function (data)
                {
                    if (data == "") {
                        alert("<?php echo JText::_('Added Max Institutes'); ?>");
                        jQuery("#ajax-loader").hide();
                        return false;
                    } else {
                        jQuery("#js_main_wrapper").prepend('<div class="ajax-response-msg js-col-lg-12 js-col-md-12 js-col-xs-12"><?php echo JText::_("Resume Saved"); ?></div>');
                        setTimeout(function () {
                            jQuery("div.ajax-response-msg").remove();
                        }, 1000);
                        jQuery("#js-resume-institute-section-view").remove();
                        jQuery("#instituteFormContainer").append('<div id="js-resume-institute-section-view" class="js-row no-margin"></div>');
                        getResume(data, -1, "js-resume-institute-section-view", "view", "institute");
                        jQuery("#js-resume-institute-section-view").css("display", "block");
                        jQuery("#resumeInstituteForm").remove();
                    }
                }
            });
            jQuery("#ajax-loader").hide();
        }
        function submitResumeEmployerForm() {
            var f = document.forms["resumeEmployerForm"];
            var validation = myValidateForm(f);
            if (validation == false) {
                jQuery("#ajax-loader").hide();
                return false;
            }
            jQuery("#ajax-loader").show();
            jQuery.ajax({
                url: '<?php echo JURI::root(); ?>index.php?option=com_jsjobs&c=resume&task=saveresumesection',
                type: "POST",
                data: new FormData(document.forms["resumeEmployerForm"]),
                contentType: false,
                cache: false,
                processData: false,
                success: function (data)
                {
                    if (data == "") {
                        alert("<?php echo JText::_('You have added maximum employers'); ?>");
                        jQuery("#ajax-loader").hide();
                        return false;
                    } else {
                        jQuery("#js_main_wrapper").prepend('<div class="ajax-response-msg js-col-lg-12 js-col-md-12 js-col-xs-12"><?php echo JText::_("Resume Saved"); ?></div>');
                        setTimeout(function () {
                            jQuery("div.ajax-response-msg").remove();
                        }, 1000);
                        jQuery("#js-resume-employer-section-view").remove();
                        jQuery("#employerFormContainer").append('<div id="js-resume-employer-section-view" class="js-row no-margin"></div>');
                        getResume(data, -1, "js-resume-employer-section-view", "view", "employer");
                        jQuery("#js-resume-employer-section-view").css("display", "block");
                        jQuery("#resumeEmployerForm").remove();
                    }
                }
            });
            jQuery("#ajax-loader").hide();
        }
        function submitResumeSkillsForm() {
            var f = document.forms["resumeSkillsForm"];
            var validation = myValidateForm(f);
            if (validation == false) {
                jQuery("#ajax-loader").hide();
                return false;
            }
            jQuery("#ajax-loader").show();
            jQuery.ajax({
                url: '<?php echo JURI::root(); ?>index.php?option=com_jsjobs&c=resume&task=saveresume',
                type: "POST",
                data: new FormData(document.forms["resumeSkillsForm"]),
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    var dataArray = data.split(",");
                    if (jQuery("#js_main_wrapper").find("div.ajax-response-msg").length > 0) {
                        jQuery("div.ajax-response-msg").remove();
                    }
                    jQuery("#js_main_wrapper").prepend('<div class="ajax-response-msg js-col-lg-12 js-col-md-12 js-col-xs-12">' + dataArray[1] + '</div>');
                    if (jQuery("#skillsFormContainer").children("input").length == 0) {
                        jQuery("#skillsFormContainer").append("<div id='js-resume-skills-section-view' class='js-row no-margin'></div>");
                    }
                    setTimeout(function () {
                        jQuery("div.ajax-response-msg").remove();
                    }, 1000);
                    if (dataArray[0] == 0) {
                        alert(dataArray[1]);
                    } else {
                        var view = getResume(dataArray[0], -1, "js-resume-skills-section-view", "view", "skills");
                        jQuery("#resume_temp").val(dataArray[0]);
                        jQuery("#js-resume-skills-section-view").css("display", "block");
                        jQuery("#resumeSkillsForm").remove();
                    }
                }
            });
            jQuery("#ajax-loader").hide();
        }
        function submitResumeEditorForm() {
            var f = document.forms["resumeEditorForm"];
            var validation = myValidateForm(f);
            if (validation == false) {
                jQuery("#ajax-loader").hide();
                return false;
            }
            jQuery("#ajax-loader").show();
            if (jQuery("div.editor").find("div.mce-container").length > 0) {
                var editorValue = jQuery("div.editor").contents().find("iframe").contents().find("#tinymce").html();
                jQuery("textarea#resume").val(editorValue);
            }

            jQuery.ajax({
                url: '<?php echo JURI::root(); ?>index.php?option=com_jsjobs&c=resume&task=saveresume',
                type: "POST",
                data: new FormData(document.forms["resumeEditorForm"]),
                contentType: false,
                cache: false,
                processData: false,
                success: function (data)
                {
                    var dataArray = data.split(",");
                    if (jQuery("#js_main_wrapper").find("div.ajax-response-msg").length > 0) {
                        jQuery("div.ajax-response-msg").remove();
                    }
                    jQuery("#js_main_wrapper").prepend('<div class="ajax-response-msg js-col-lg-12 js-col-md-12 js-col-xs-12">' + dataArray[1] + '</div>');
                    if (jQuery("#editorFormContainer").children("input").length == 0) {
                    }
                    setTimeout(function () {
                        jQuery("div.ajax-response-msg").remove();
                    }, 1000);
                    if (dataArray[0] == 0) {
                        alert(dataArray[1]);
                    } else {
                        jQuery("#editorFormContainer").html("");
                        jQuery("#editorFormContainer").append('<div id="editorView" class="js-resume-address-section-view js-row no-margin"></div>');
                        getResume(dataArray[0], -1, "editorView", "view", "editor");
                        jQuery("#resume_temp").val(dataArray[0]);
                        jQuery("#editorView").css("display", "block");
                    }
                }
            });
            jQuery("#ajax-loader").hide();
        }
        function resumeEditorToggler() {
            /*
            jQuery("div.toggle-editor a").on("click", function () {
                if (jQuery("div.editor").find("div.mce-container").length == 0) {
                    tinyMCE.execCommand("mceRemoveEditor", false, "resume");
                    return false;
                }
            });
*/
        }
        function submitResumeReferenceForm() {
            var f = document.forms["resumeReferenceForm"];
            var validation = myValidateForm(f);
            if (validation == false) {
                jQuery("#ajax-loader").hide();
                return false;
            }
            jQuery("#ajax-loader").show();
            jQuery.ajax({
                url: '<?php echo JURI::root(); ?>index.php?option=com_jsjobs&c=resume&task=saveresumesection',
                type: "POST",
                data: new FormData(document.forms["resumeReferenceForm"]),
                contentType: false,
                cache: false,
                processData: false,
                success: function (data)
                {
                    if (data == "") {
                        alert("<?php echo JText::_('You have added maximum references'); ?>");
                        jQuery("#ajax-loader").hide();
                        return false;
                    } else {
                        jQuery("#js_main_wrapper").prepend('<div class="ajax-response-msg js-col-lg-12 js-col-md-12 js-col-xs-12"><?php echo JText::_("Resume Saved"); ?></div>');
                        setTimeout(function () {
                            jQuery("div.ajax-response-msg").remove();
                        }, 1000);
                        jQuery("#js-resume-reference-section-view").remove();
                        jQuery("#referenceFormContainer").append('<div id="js-resume-reference-section-view" class="js-row no-margin"></div>');
                        getResume(data, -1, "js-resume-reference-section-view", "view", "reference");
                        jQuery("#js-resume-reference-section-view").css("display", "block");
                        jQuery("#resumeReferenceForm").remove();
                    }
                }
            });
            jQuery("#ajax-loader").hide();
        }
        function submitResumeLanguageForm() {
            var f = document.forms["resumeLanguageForm"];
            var validation = myValidateForm(f);
            if (validation == false) {
                jQuery("#ajax-loader").hide();
                return false;
            }
            jQuery("#ajax-loader").show();
            jQuery.ajax({
                url: '<?php echo JURI::root(); ?>index.php?option=com_jsjobs&c=resume&task=saveresumesection',
                type: "POST",
                data: new FormData(document.forms["resumeLanguageForm"]),
                contentType: false,
                cache: false,
                processData: false,
                success: function (data)
                {
                    if (data == "") {
                        alert("<?php echo JText::_('You have added maximum languages'); ?>");
                        jQuery("#ajax-loader").hide();
                        return false;
                    } else {
                        jQuery("#js_main_wrapper").prepend('<div class="ajax-response-msg js-col-lg-12 js-col-md-12 js-col-xs-12"><?php echo JText::_("Resume Saved"); ?></div>');
                        setTimeout(function () {
                            jQuery("div.ajax-response-msg").remove();
                        }, 1000);
                        jQuery("#js-resume-language-section-view").remove();
                        jQuery("#languageFormContainer").append('<div id="js-resume-language-section-view" class="js-row no-margin"></div>');
                        getResume(data, -1, "js-resume-language-section-view", "view", "language");
                        jQuery("#js-resume-language-section-view").css("display", "block");
                        jQuery("#resumeLanguageForm").remove();
                    }
                }
            });
            jQuery("#ajax-loader").hide();
        }
        function showSection(sectionFor) {
            if (sectionFor == "personal") {
                return;
            } else {
                if (sectionFor == 'skills') {
                    if (jQuery("div#" + sectionFor).length > 0) {
                        return false;
                    }
                } else if (sectionFor == 'resume') {
                    if (jQuery("div#" + sectionFor + "View").length > 0) {
                        return false;
                    }
                } else {
                    if (jQuery("div.js-resume-" + sectionFor + "-section-view").length > 0) {
                        return false;
                    }
                }

                if (jQuery("#js_main_wrapper").find("form").length == 0 || jQuery("#resumeFormContainer").length == 0) {

                    // Resume Addresses Form/View Section
                    if (jQuery("#" + sectionFor + "FormContainer form").children().length > 0) {
                        alert("<?php echo JText::_('Please save opened form'); ?>");
                        return false;
                    }
                    var sectionForCopy = sectionFor;
                    var capitalizedSection = sectionForCopy.slice(0, 1).toUpperCase() + sectionFor.slice(1);

                    jQuery("#" + sectionFor + "FormContainer").html("");
                    var resumeid = document.getElementById("resume_temp").value;
                    if (resumeid == null) {
                        return;
                    } else {
                        getResume(resumeid, -1, sectionFor + "FormContainer", "form", sectionFor);
                        jQuery("#resume" + capitalizedSection + "Form").toggle('slow');
                    }
                } else {
                    jQuery("#" + sectionFor + "FormContainer").html("");
                    jQuery("div." + sectionFor + "-section").css('padding', '10px 0px');
                    jQuery("div." + sectionFor + "-section").append('<div class="section-error js-col-lg-12 js-col-md-12"><?php echo JText::_("Please save opened form"); ?></div>');
                    setTimeout(function () {
                        jQuery("div." + sectionFor + "-section div.section-error").remove();
                        jQuery("div." + sectionFor + "-section").css('padding', '0px');
                    }, 2500);
                    return;
                }
            }
        }
        function getTokenInput(inputfor, cityid, cityname) {
            if (inputfor == "addresses") {
                inputfor = "address";
            }
            if (inputfor == "institutes") {
                inputfor = "institute";
            }
            if (inputfor == "employers") {
                inputfor = "employer";
            }
            if (inputfor == "references") {
                inputfor = "reference";
            }
            var city = jQuery("#" + inputfor + "cityforedit").val();
            if (city != "") {
                jQuery("#" + inputfor + "_city").tokenInput('<?php echo JURI::root() . "index.php?option=com_jsjobs&c=cities&task=getaddressdatabycityname"; ?>', {
                    theme: "jsjobs",
                    preventDuplicates: true,
                    hintText: "<?php echo JText::_('Type In A Search'); ?>",
                    noResultsText: "<?php echo JText::_('No Results'); ?>",
                    searchingText: "<?php echo JText::_('Searching...'); ?>",
                    tokenLimit: 1,
                    prePopulate: [{id: cityid, name: cityname}],
                    <?php if($this->config['newtyped_cities'] == 1){ ?>
                    onResult: function (item) {
                        if (jQuery.isEmptyObject(item)) {
                            return [{id: 0, name: jQuery("tester").text()}];
                        } else {
                            //add the item at the top of the dropdown
                            item.unshift({id: 0, name: jQuery("tester").text()});
                            return item;
                        }
                    },
                    onAdd: function (item) {
                        if (item.id > 0) {
                            return;
                        }
                        if (item.name.search(",") == -1) {
                            var input = jQuery("tester").text();
                            msg = '<?php echo JText::_("Location format is not correct please enter city in this format <br/>city name, country name <br/>or <br/>city name, state name, country name"); ?>';
                            jQuery("#" + inputfor + "_city").tokenInput("remove", item);
                            jQuery("div#warn-message").find("span.text").html(msg).show();
                            jQuery("div#warn-message").show();
                            jQuery("div#black_wrapper_jobapply").show();
                            return false;
                        } else {
                            jQuery.post("<?php echo JURI::root(); ?>index.php?option=com_jsjobs&task=cities.savecity", {citydata: jQuery("tester").text()}, function (data) {
                                if (data) {
                                    try {
                                        var value = jQuery.parseJSON(data);
                                        jQuery('#' + inputfor + '_city').tokenInput('remove', item);
                                        jQuery('#' + inputfor + '_city').tokenInput('add', {id: value.id, name: value.name});
                                    } catch (e) { // string is not the json its the message come from server
                                        msg = data;
                                        jQuery("div#warn-message").find("span.text").html(msg).show();
                                        jQuery("div#warn-message").show();
                                        jQuery("div#black_wrapper_jobapply").show();
                                        jQuery('#' + inputfor + '_city').tokenInput('remove', item);
                                    }
                                }
                            });
                        }
                    }
                    <?php } ?>
                });
            } else {
                jQuery("#" + inputfor + "_city").tokenInput('<?php echo JURI::root() . "index.php?option=com_jsjobs&c=cities&task=getaddressdatabycityname"; ?>', {
                    theme: "jsjobs",
                    preventDuplicates: true,
                    hintText: "<?php echo JText::_('Type In A Search'); ?>",
                    noResultsText: "<?php echo JText::_('No Results'); ?>",
                    searchingText: "<?php JText::_('Searching...'); ?>",
                    tokenLimit: 1,
                    <?php if($this->config['newtyped_cities'] == 1){ ?>
                    onResult: function (item) {
                        if (jQuery.isEmptyObject(item)) {
                            return [{id: 0, name: jQuery("tester").text()}];
                        } else {
                            //add the item at the top of the dropdown
                            item.unshift({id: 0, name: jQuery("tester").text()});
                            return item;
                        }
                    },
                    onAdd: function (item) {
                        if (item.id > 0) {
                            return;
                        }
                        if (item.name.search(",") == -1) {
                            var input = jQuery("tester").text();
                            msg = '<?php echo JText::_("Location format is not correct please enter city in this format <br/>city name, country name <br/>or <br/>city name, state name, country name"); ?>';
                            jQuery("#" + inputfor + "_city").tokenInput("remove", item);
                            jQuery("div#warn-message").find("span.text").html(msg).show();
                            jQuery("div#warn-message").show();
                            jQuery("div#black_wrapper_jobapply").show();
                            return false;
                        } else {
                            jQuery.post("<?php echo JURI::root(); ?>index.php?option=com_jsjobs&task=cities.savecity", {citydata: jQuery("tester").text()}, function (data) {
                                if (data) {
                                    try {
                                        var value = jQuery.parseJSON(data);
                                        jQuery('#' + inputfor + '_city').tokenInput('remove', item);
                                        jQuery('#' + inputfor + '_city').tokenInput('add', {id: value.id, name: value.name});
                                    } catch (e) { // string is not the json its the message come from server
                                        msg = data;
                                        jQuery("div#warn-message").find("span.text").html(msg).show();
                                        jQuery("div#warn-message").show();
                                        jQuery("div#black_wrapper_jobapply").show();
                                        jQuery('#' + inputfor + '_city').tokenInput('remove', item);
                                    }
                                }
                            });
                        }
                    }
                    <?php } ?>
                });
            }
        }
        function toggleMap(id) {
            var containerWidth = jQuery("#address_" + id).width();
            jQuery("div.map_view").css('width', containerWidth);
            if (jQuery("#map_container_" + id).is(':visible')) {
                jQuery("#map_container_" + id).hide('slow');
                jQuery("#address_" + id).find("div.map-toggler").find("span img").attr("src", "<?php echo JURI::root(); ?>components/com_jsjobs/images/show-map.png");
                jQuery("#address_" + id).find("div.map-toggler").find("span.text").html("<?php echo JText::_('Show map'); ?>");
            } else {
                jQuery("#map_container_" + id).show('slow');
                jQuery("#address_" + id).find("div.map-toggler").find("span img").attr("src", "<?php echo JURI::root(); ?>components/com_jsjobs/images/hide-map.png");
                jQuery("#address_" + id).find("div.map-toggler").find("span.text").html("<?php echo JText::_('Hide map'); ?>");
                loadViewMap(id);
            }
        }
        function loadViewMap(addressid) {
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
        function loadMap() {
            var default_latitude = document.getElementById('default_latitude').value;
            var default_longitude = document.getElementById('default_longitude').value;

            var latitude = document.getElementById('latitude').value;
            var longitude = document.getElementById('longitude').value;

            if ((latitude != '') && (longitude != '')) {
                default_latitude = latitude;
                default_longitude = longitude;
            }
            var latlng = new google.maps.LatLng(default_latitude, default_longitude);
            zoom = 10;
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
            var marker = new google.maps.Marker({
                position: latlng,
                map: map,
            });
            marker.setMap(map);
            lastmarker = marker;
            document.getElementById('latitude').value = marker.position.lat();
            document.getElementById('longitude').value = marker.position.lng();

            google.maps.event.addListener(map, "click", function (e) {
                var latLng = new google.maps.LatLng(e.latLng.lat(), e.latLng.lng());
                geocoder = new google.maps.Geocoder();
                geocoder.geocode({'latLng': latLng}, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (lastmarker != '')
                            lastmarker.setMap(null);
                        var marker = new google.maps.Marker({
                            position: results[0].geometry.location,
                            map: map,
                        });
                        marker.setMap(map);
                        lastmarker = marker;
                        document.getElementById('latitude').value = marker.position.lat();
                        document.getElementById('longitude').value = marker.position.lng();

                    } else {
                        alert("Geocode was not successful for the following reason: " + status);
                    }
                });
            });
        }
        function showdiv() {
            document.getElementById('map').style.visibility = 'visible';
        }
        function hidediv() {
            document.getElementById('map').style.visibility = 'hidden';
        }
        function validate_dateofbirth() {
            var date_of_birth_make = new Array();
            var split_date_of_birth_value = new Array();

            f = document.adminForm;
            var returnvalue = true;
            var today = new Date();
            today.setHours(0, 0, 0, 0);

            var date_of_birth_string = document.getElementById("date_of_birth").value;

            var format_type = document.getElementById("j_dateformat").value;
            if (format_type == "d-m-Y") {
                split_date_of_birth_value = date_of_birth_string.split("-");

                date_of_birth_make["year"] = split_date_of_birth_value[2];
                date_of_birth_make["month"] = split_date_of_birth_value[1];
                date_of_birth_make["day"] = split_date_of_birth_value[0];


            } else if (format_type == "m/d/Y") {

                split_date_of_birth_value = date_of_birth_string.split("/");

                date_of_birth_make["year"] = split_date_of_birth_value[2];
                date_of_birth_make["month"] = split_date_of_birth_value[0];
                date_of_birth_make["day"] = split_date_of_birth_value[1];

            } else if (format_type == "Y-m-d") {

                split_date_of_birth_value = date_of_birth_string.split("-");

                date_of_birth_make["year"] = split_date_of_birth_value[0];
                date_of_birth_make["month"] = split_date_of_birth_value[1];
                date_of_birth_make["day"] = split_date_of_birth_value[2];

            }

            var date_of_birth = new Date(date_of_birth_make["year"], date_of_birth_make["month"] - 1, date_of_birth_make["day"]);

            if (date_of_birth >= today) {
                jQuery("#date_of_birth").addClass("invalid");
                alert("<?php echo JText::_('Date of birth must be less then today'); ?>");
                returnvalue = false;
            }
            return returnvalue;

        }
        function validate_startdate() {
            f = document.adminForm;
            var returnvalue = true;
            var date_start_make = new Array();
            var split_start_value = new Array();

            var isedit = document.getElementById("id");
            if (isedit.value != "" && isedit.value != 0) {
                return true;
            } else {
                var today = new Date();
                today.setHours(0, 0, 0, 0);


                var start_string = document.getElementById("date_start").value;
                var format_type = document.getElementById("j_dateformat").value;
                if (format_type == "d-m-Y") {
                    split_start_value = start_string.split("-");

                    date_start_make["year"] = split_start_value[2];
                    date_start_make["month"] = split_start_value[1];
                    date_start_make["day"] = split_start_value[0];


                } else if (format_type == "m/d/Y") {
                    split_start_value = start_string.split("/");
                    date_start_make["year"] = split_start_value[2];
                    date_start_make["month"] = split_start_value[0];
                    date_start_make["day"] = split_start_value[1];


                } else if (format_type == "Y-m-d") {

                    split_start_value = start_string.split("-");

                    date_start_make["year"] = split_start_value[0];
                    date_start_make["month"] = split_start_value[1];
                    date_start_make["day"] = split_start_value[2];
                }

                var date_can_start = new Date(date_start_make["year"], date_start_make["month"] - 1, date_start_make["day"]);

                if (date_can_start < today) {
                    jQuery("#date_start").addClass("invalid");
                    alert("<?php echo JText::_('Date start must be greater then today'); ?>");
                    returnvalue = false;
                }
                return returnvalue;
            }

        }
        function myValidate(f) {
            var msg = new Array();

            if (document.formvalidator.isValid(f)) {
                f.check.value = '<?php if (JVERSION < 3) echo JUtility::getToken(); else echo JSession::getFormToken(); ?>';
            } else {
                msg.push('<?php echo JText::_('Some values are not acceptable, please retry'); ?>');
                alert(msg.join('\n'));
                return false;
            }

            var iamavailable_obj = document.getElementById("iamavailable_required");
            if (typeof iamavailable_obj !== 'undefined' && iamavailable_obj !== null) {
                var iamavailable_required_val = document.getElementById("iamavailable_required").value;
                if (iamavailable_required_val != '') {
                    var checked_iamavailable = jQuery('input[name=iamavailable]:checkbox:checked').length;
                    if (checked_iamavailable == 0) {
                        msg.push('<?php echo JText::_('Please check I am available'); ?>');
                        alert(msg.join('\n'));
                        return false;
                    }
                }
            }
            var searchable_obj = document.getElementById("searchable_required");
            if (typeof searchable_obj !== 'undefined' && searchable_obj !== null) {
                var searchable_required_val = document.getElementById("searchable_required").value;
                if (searchable_required_val != '') {
                    var checked_searchable = jQuery('input[name=searchable]:checkbox:checked').length;
                    if (checked_searchable == 0) {
                        msg.push('<?php echo JText::_('Please check searchable'); ?>');
                        alert(msg.join('\n'));
                        return false;
                    }
                }
            }
            var photo_obj = document.getElementById('photo_required');
            if (typeof photo_obj !== 'undefined' && photo_obj !== null) {
                var photo_required = document.getElementById('photo_required').value;
                if (photo_required != '') {
                    var photo_value = document.getElementById('photo').value;
                    if (photo_value == '') {
                        var photofile_value = document.getElementById('photo').value;
                        if (photofile_value == '') {
                            msg.push('<?php echo JText::_('Please select photo'); ?>');
                            alert(msg.join('\n'));
                            return false;
                        }
                    }
                }
            }
            var resume_file_obj = document.getElementById('resumefiles_required');
            if (typeof resume_file_obj !== 'undefined' && resume_file_obj !== null) {
                var resume_file_required = document.getElementById('resumefiles_required').value;
                if (resume_file_required != '') {
                    var resumefile_value = document.getElementById('resumefiles').value;
                    if (resumefile_value == '') {
                        var resumefilename_value = document.getElementById('resumefiles').value;
                        if (resumefilename_value == '') {
                            msg.push('<?php echo JText::_('Please select resume file'); ?>');
                            alert(msg.join('\n'));
                            return false;
                        }
                    }
                }
            }
            var email_obj = document.getElementById("email_address_required");
            if (typeof email_obj !== 'undefined' && email_obj !== null) {
                var email_required = document.getElementById('email_address_required').value;
                if (email_required != '') {
                    var email_value = document.getElementById('email_address').value;
                    var reg = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
                    if (reg.test(email_value) == false) {
                        jQuery("#email_address").addClass("invalid");
                        msg.push('<?php echo JText::_('Invalid Email'); ?>');
                        alert(msg.join('\n'));
                        return false;
                    }
                }
            }
            var dateofbirth_obj = document.getElementById("date-of-birth-required");
            if (typeof dateofbirth_obj !== 'undefined' && dateofbirth_obj !== null) {
                var dateofbirth_required = document.getElementById('date-of-birth-required').value;
                if (dateofbirth_required != '') {
                    var dateofbirth_value = document.getElementById('date_of_birth').value;
                    if (dateofbirth_value == '') {
                        jQuery("#date_of_birth").addClass("invalid");
                        msg.push('<?php echo JText::_('Please enter date of birth'); ?>');
                        alert(msg.join('\n'));
                        return false;
                    }
                }
            }
            var call_date_of_birth = jQuery("#date_of_birth").val();
            if (typeof call_date_of_birth != 'undefined') {
                var dob_return = validate_dateofbirth();
                if (dob_return == false)
                    return false;
            }
            var datestart_obj = document.getElementById("date_start_required");
            if (typeof datestart_obj !== 'undefined' && datestart_obj !== null) {
                var datestart_required = document.getElementById('date_start_required').value;
                if (datestart_required != '') {
                    var datestartvalue_required = document.getElementById('date_start').value;
                    if (datestartvalue_required == '') {
                        jQuery("#date_start").addClass("invalid");
                        msg.push('<?php echo JText::_('Please enter date you can start'); ?>');
                        alert(msg.join('\n'));
                        return false;
                    }
                }
            }
            var call_date_start = jQuery("#date_start").val();
            if (typeof call_date_start != 'undefined') {
                var startdate_return = validate_startdate();
                if (startdate_return == false)
                    return false;
            }
            return true;
        }
        /*
        tinyMCE.init({mode: "textareas", theme: "advanced", directionality: "ltr", language: "en", mode : "specific_textareas", autosave_restore_when_empty: false, skin: "lightgray", theme : "modern", schema: "html5", selector: "textarea.mce_editable", inline_styles: true, gecko_spellcheck: true, entity_encoding: "raw", valid_elements: "", extended_valid_elements: "hr[id|title|alt|class|width|size|noshade]",
            force_br_newlines: false, force_p_newlines: true, forced_root_block: "p", toolbar_items_size: "small", invalid_elements: "script,applet,iframe", plugins: "table link image code hr charmap autolink lists importcss", toolbar1: "bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | formatselect | bullist numlist",
            toolbar2: "outdent indent | undo redo | link unlink anchor image code | hr table | subscript superscript | charmap", removed_menuitems: "newdocument", relative_urls: true, remove_script_host: false, document_base_url: "<?php echo JURI::root(); ?>", content_css: "<?php echo JURI::root(); ?>templates/system/css/editor.css", importcss_append: true, resize: "both", height: "550", width: "", });
        */
    </script>
<div id="black_wrapper_jobapply" style="display:none;"></div>
<div id="warn-message" style="display: none;">
    <span class="close-warnmessage"><img src="<?php echo JURI::root(); ?>components/com_jsjobs/images/close-icon.png" /></span>
    <img src="<?php echo JURI::root(); ?>components/com_jsjobs/images/warning-icon.png" />
    <span class="text"></span>
</div>
<script type="text/javascript">
    jQuery("div#black_wrapper_jobapply,div#warn-message span.close-warnmessage").click(function () {
        jQuery("div#warn-message").fadeOut();
        jQuery("div#black_wrapper_jobapply").fadeOut();
    });
</script>
    
    <div id="js_main_wrapper">
        <?php
        function getSectionTitle($sectionFor, $title, $containerName, $published, $resumeid,$count) {
            if ($published == 1) {
                if ($sectionFor == "education") {
                    $sectionFor = "institute";
                }
                $section = '
            <a href="javascript:void(0);" id="' . $sectionFor . 'SectionAnchor" alt="" onclick="return showSection(\'' . $sectionFor . '\')">
                <div class="js-row no-margin">
                    <div class="js-resume-section-title js-col-lg-12 js-col-md-12 js-col-xs-12">';
                if($resumeid == -1 || $count == 0)
                    $section .= '<img src="' . JURI::root() . 'components/com_jsjobs/images/add-circle-selected.png"/>';
                $section .= '
                        <span>' . JText::_($title) . '</span>
                    </div>
                </div>
            </a>
            <div id="' . $containerName . 'FormContainer" class="js-resume-section-body ' . $sectionFor . '-section js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding"></div>';
                echo $section;
            }
        }

        if ((isset($this->isadmin) AND $this->isadmin == 1)|| (isset($this->canaddnewresume) && $this->canaddnewresume == VALIDATE)) { 
            if(isset($this->canaddnewresume) && $this->canaddnewresume == VALIDATE){ ?>
                <span class="js_controlpanel_section_title"><?php echo JText::_('Resume Form'); ?></span>
            <?php } ?>
            <div class="js-row no-margin">
                <?php
                foreach ($this->fieldsordering AS $field) {
                    switch ($field->field) {
                        case 'section_personal':
                            getSectionTitle('personal', 'Personal Information', 'resume', $field->published, $resumeid,1);
                            break;
                        case 'section_address':
                            $title = ($resumeid == -1) ? 'Add Address' : ($this->resumecountresult['address_count'] == 0) ? 'Add Address' : 'Address';
                            getSectionTitle('address', $title, 'address', $field->published, $resumeid,$this->resumecountresult['address_count']);
                            break;
                        case 'section_institute':
                            $title = ($resumeid == -1) ? 'Add Education' : ($this->resumecountresult['insititute_count'] == 0) ? 'Add Education' : 'Education';
                            getSectionTitle('education', $title, 'institute', $field->published, $resumeid,$this->resumecountresult['insititute_count']);
                            break;
                        case 'section_employer':
                            $title = ($resumeid == -1) ? 'Add Employer' : ($this->resumecountresult['employers_count'] == 0) ? 'Add Employer' : 'Employer';
                            getSectionTitle('employer', $title, 'employer', $field->published, $resumeid,$this->resumecountresult['employers_count']);
                            break;
                        case 'section_skills':
                            $title = ($resumeid == -1) ? 'Add Skills' : ($this->resumecountresult['skills_count'] == 0) ? 'Add Skills' : 'Skills';
                            getSectionTitle('skills', $title, 'skills', $field->published, $resumeid,$this->resumecountresult['skills_count']);
                            break;
                        case 'section_resume':
                            getSectionTitle('editor', 'Resume editor', 'editor', $field->published, $resumeid,$this->resumecountresult['resume_count']);
                            break;
                        case 'section_reference':
                            $title = ($resumeid == -1) ? 'Add Refereneces' : ($this->resumecountresult['references_count'] == 0) ? 'Add Refereneces' : 'Refereneces';
                            getSectionTitle('reference', $title, 'reference', $field->published, $resumeid,$this->resumecountresult['references_count']);
                            break;
                        case 'section_language';
                            $title = ($resumeid == -1) ? 'Add Language' : ($this->resumecountresult['languages_count'] == 0) ? 'Add Language' : 'Language';
                            getSectionTitle('language', $title, 'language', $field->published, $resumeid,$this->resumecountresult['languages_count']);
                            break;
                    }
                }
                ?>
            </div>
    <?php 
        } else { // can not add new resume 
            $itemid = $this->Itemid;
            switch ($this->canaddnewresume) {
                case NO_PACKAGE:
                    $link = "index.php?option=com_jsjobs&c=jobseekerpackages&view=jobseekerpackages&layout=packages&Itemid=".$this->Itemid;
                    $this->jsjobsmessages->getPackageExpireMsg('Package Not Purchased', 'Package is required to perform this action, please get package', $link);
                break;
                case EXPIRED_PACKAGE:
                    $link = "index.php?option=com_jsjobs&c=jobseekerpackages&view=jobseekerpackages&layout=packages&Itemid=".$this->Itemid;
                    $this->jsjobsmessages->getPackageExpireMsg('Package Not Purchased', 'Package is required to perform this action, please get package', $link);
                break;
                case RESUME_LIMIT_EXCEEDS:
                    $link = "index.php?option=com_jsjobs&c=jobseekerpackages&view=jobseekerpackages&layout=packages&Itemid=".$this->Itemid;
                    $this->jsjobsmessages->getPackageExpireMsg('Resume limit exceeds', 'You Can Not Add New Resume, Get New Package To Extend Your Resume Limit', $link);
                    break;
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
    ?>
    </div>
    <div id="ajax-loader" style="display:none"><img src="<?php echo JURI::root(); ?>components/com_jsjobs/images/loading.gif"></div>
<?php } ?>

    