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
 * File Name:	views/employer/tmpl/formcompany.php
  ^
 * Description: template for form company
  ^
 * History:		NONE
  ^
 */
defined('_JEXEC') or die('Restricted access');
$editor = JFactory :: getEditor();
JHTML :: _('behavior.calendar');
JHTML::_('behavior.formvalidation');

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
if ($this->config['offline'] == '1') {
    $this->jsjobsmessages->getSystemOfflineMsg($this->config);
} else {
    ?>
    <script language="javascript">

        function checkUrl(obj) {
            if (!obj.value.match(/^http[s]?\:\/\//))
                obj.value = 'http://' + obj.value;
        }
        function validate_url() {
            var value = jQuery("#validateurl").val();
            if (typeof value != 'undefined') {
                if (value != '' && value != 'http://') {
                    if (value.match(/^(http|https|ftp)\:\/\/\w+([\.\-]\w+)*\.\w{2,4}(\:\d+)*([\/\.\-\?\&\%\#]\w+)*\/?$/i) ||
                            value.match(/^mailto\:\w+([\.\-]\w+)*\@\w+([\.\-]\w+)*\.\w{2,4}$/i))
                    {
                        return true;
                    }
                    else
                    {
                        jQuery("#validateurl").addClass("invalid");
                        alert('<?php echo JText::_('Please enter correct company address'); ?>');
                        return false;
                    }
                }
                return true;
            }
            return true;

        }
        function validate_since() {
            var date_since_make = new Array();
            var split_since_value = new Array();

            f = document.adminForm;
            var returnvalue = true;
            var today = new Date()
            today.setHours(0, 0, 0, 0);

            var since_string = document.getElementById("since").value;
            var format_type = document.getElementById("j_dateformat").value;
            if (format_type == 'd-m-Y') {
                split_since_value = since_string.split('-');

                date_since_make['year'] = split_since_value[2];
                date_since_make['month'] = split_since_value[1];
                date_since_make['day'] = split_since_value[0];


            } else if (format_type == 'm/d/Y') {
                split_since_value = since_string.split('/');
                date_since_make['year'] = split_since_value[2];
                date_since_make['month'] = split_since_value[0];
                date_since_make['day'] = split_since_value[1];


            } else if (format_type == 'Y-m-d') {

                split_since_value = since_string.split('-');

                date_since_make['year'] = split_since_value[0];
                date_since_make['month'] = split_since_value[1];
                date_since_make['day'] = split_since_value[2];
            }
            var sincedate = new Date(date_since_make['year'], date_since_make['month'] - 1, date_since_make['day']);

            if (sincedate > today) {
                jQuery("#since").addClass("invalid");
                alert('<?php echo JText::_('Start date must be less then today'); ?>');
                returnvalue = false;
            }
            return returnvalue;

        }

        function myValidate(f) {
            var msg = new Array();
            var since_obj = jQuery("#company-since-required").val();
            if (typeof since_obj !== 'undefined' && since_obj !== null) {
                var since_required = document.getElementById('company-since-required').value;
                if (since_required != '') {
                    var since_value = document.getElementById('since').value;
                    if (since_value == '') {
                        jQuery("#since").addClass("invalid");
                        msg.push('<?php echo JText::_('Please enter company since date'); ?>');
                        alert(msg.join('\n'));
                        return false;
                    }
                }
            }
            var desc_obj = jQuery("#company-description-required").val();
            if (typeof desc_obj !== 'undefined' && desc_obj !== null) {
                var desc_required = document.getElementById("company-description-required").value;
                if (desc_required != '') {
                    var comdescription = tinyMCE.get('description').getContent();
                    if (comdescription == '') {
                        msg.push('<?php echo JText::_('Please enter company description'); ?>');
                        alert(msg.join('\n'));
                        return false;
                    }
                }
            }
            var logo_obj = jQuery('#company-logo-required').val();
            if (typeof logo_obj !== 'undefined' && logo_obj !== null) {
                var logo_required = document.getElementById('company-logo-required').value;
                if (logo_required != '') {
                    var logo_value = document.getElementById('logo').value;
                    if (logo_value == '') {
                        var logofile_value = document.getElementById('company-logofilename').value;
                        if (logofile_value == '') {
                            msg.push('<?php echo JText::_('Please select company logo'); ?>');
                            alert(msg.join('\n'));
                            return false;
                        }
                    }
                }
            }
            var url_return = validate_url();
            if (url_return == false)
                return false;
            var call_since = jQuery("#since").val();
            if (typeof call_since != 'undefined') {
                var since_return = validate_since();
                if (since_return == false)
                    return false;
            }
            if (document.formvalidator.isValid(f)) {
                f.check.value = '<?php if (JVERSION < 3) echo JUtility::getToken(); else echo JSession::getFormToken(); ?>';//send token
            } else {
                msg.push('<?php echo JText::_('Some values are not acceptable, please retry'); ?>');
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
    if ($this->canaddnewcompany == VALIDATE) { // add new company, in edit case always VAlidate
        ?>
        <div id="js_main_wrapper">
            <span class="js_controlpanel_section_title"><?php echo JText::_('Company Information'); ?></span>
            <form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data"  class="jsautoz_form" onSubmit="return myValidate(this);">
                <?php
                $i = 0;
                foreach ($this->fieldsordering as $field) {
                    switch ($field->field) {
                        case "jobcategory":
                            ?>
                            <div class="fieldwrapper">
                                <div class="fieldtitle">
                                    <label  id="jobcategorymsg" for="jobcategory"><?php echo JText::_('Categories'); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                </div>
                                <div class="fieldvalue">
                            <?php echo $this->lists['jobcategory']; ?>
                                </div>
                            </div>				        
                            <?php
                            break;
                        case "name": $name_required = ($field->required ? 'required' : '');
                            ?>
                            <div class="fieldwrapper">
                                <div class="fieldtitle">
                                    <label id="namemsg" for="name"><?php echo JText::_('Company'); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                </div>
                                <div class="fieldvalue">
                                    <input class="inputbox <?php echo $name_required; ?>" type="text" name="name" id="name" size="40" maxlength="255" value="<?php if (isset($this->company)) echo $this->company->name; ?>" />
                                </div>
                            </div>				        
                            <?php
                            break;
                        case "url":
                            ?>
                    <?php if ($field->published == 1) {
                        $url_required = ($field->required ? 'required' : '');
                        ?>
                                <div class="fieldwrapper">
                                    <div class="fieldtitle">
                                        <label id="urlmsg" for="url"><?php echo JText::_('Website'); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                    </div>
                                    <div class="fieldvalue">
                                        <input class="inputbox <?php echo $url_required; ?>" type="text" id="validateurl" name="url" size="40" maxlength="100" onblur="checkUrl(this);" value="<?php if (isset($this->company)) echo trim($this->company->url); ?>" />
                                    </div>
                                </div>				        
                            <?php } ?>
                    <?php
                    break;
                case "contactname": $contactname_required = ($field->required ? 'required' : '');
                    ?>
                            <div class="fieldwrapper">
                                <div class="fieldtitle">
                                    <label id="contactnamemsg" for="contactname"><?php echo JText::_('Contact Name'); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                </div>
                                <div class="fieldvalue">
                                    <input class="inputbox <?php echo $contactname_required; ?>" type="text" name="contactname" id="contactname" size="40" maxlength="100" value="<?php if (isset($this->company)) echo $this->company->contactname; ?>" />
                                </div>
                            </div>				        
                            <?php
                            break;
                        case "contactphone":
                            ?>
                    <?php if ($field->published == 1) {
                        $contactphone_required = ($field->required ? 'required' : '');
                        ?>
                                <div class="fieldwrapper">
                                    <div class="fieldtitle">
                                        <label id="contactphonemsg" for="contactphone"><?php echo JText::_('Contact Phone'); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                    </div>
                                    <div class="fieldvalue">
                                        <input class="inputbox <?php echo $contactphone_required; ?>" type="text" name="contactphone" size="40" maxlength="100" value="<?php if (isset($this->company)) echo $this->company->contactphone; ?>" />
                                    </div>
                                </div>				        
                            <?php } ?>
                            <?php
                            break;
                        case "contactfax":
                            ?>
                    <?php if ($field->published == 1) {
                        $fax_required = ($field->required ? 'required' : '');
                        ?>
                                <div class="fieldwrapper">
                                    <div class="fieldtitle">
                                        <label id="companyfaxmsg" for="companyfax"><?php echo JText::_('Contact Fax'); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                    </div>
                                    <div class="fieldvalue">
                                        <input class="inputbox <?php echo $fax_required; ?>" type="text" name="companyfax" size="40" maxlength="100" value="<?php if (isset($this->company)) echo $this->company->companyfax; ?>" />
                                    </div>
                                </div>				        
                    <?php } ?>
                    <?php
                    break;
                case "contactemail": $email_required = ($field->required ? 'required' : '');
                    ?>
                            <div class="fieldwrapper">
                                <div class="fieldtitle">
                                    <label id="contactemailmsg" for="contactemail"><?php echo JText::_('Contact Email'); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                </div>
                                <div class="fieldvalue">
                                    <input class="inputbox <?php echo $email_required; ?> validate-email" type="text" name="contactemail" id="contactemail" size="40" maxlength="100" value="<?php if (isset($this->company)) echo $this->company->contactemail; ?>" />
                                </div>
                            </div>				        
                            <?php
                            break;
                        case "since":
                            ?>
                            <?php if ($field->published == 1) {
                                ?>
                                        <?php
                                        $since_required = ($field->required ? 'required' : '');
                                        $startdatevalue = '';
                                        if (isset($this->company))
                                            $startdatevalue = date($this->config['date_format'], strtotime($this->company->since));
                                        ?>
                                <div class="fieldwrapper">
                                    <div class="fieldtitle">
                                        <?php echo JText::_('Since'); ?>:<?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                    </div>
                                    <div class="fieldvalue">
                        <?php
                        if (isset($this->company))
                            echo JHTML::_('calendar', JHtml::_('date', $this->company->since, $this->config['date_format']), 'since', 'since', $js_dateformat, array('class' => 'inputbox validate-since', 'size' => '10', 'maxlength' => '19'));
                        else
                            echo JHTML::_('calendar', '', 'since', 'since', $js_dateformat, array('class' => 'inputbox validate-since', 'size' => '10', 'maxlength' => '19'));
                        ?>
                                        <input type='hidden' id='company-since-required' name="company-since-required" value="<?php echo $since_required; ?>">
                                    </div>
                                </div>				        
                    <?php } ?>
                    <?php
                    break;
                case "companysize":
                    ?>
                    <?php
                    if ($field->published == 1) {
                        $companysize_required = ($field->required ? 'required' : '');
                        ?>
                                <div class="fieldwrapper">
                                    <div class="fieldtitle">
                                        <label id="companysize" for="companysize"><?php echo JText::_('Company Size'); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                    </div>
                                    <div class="fieldvalue">
                                        <input class="inputbox <?php echo $companysize_required; ?> validate-numeric" maxlength="6" type="text" name="companysize" id="companysize" size="20" maxlength="20" value="<?php if (isset($this->company)) echo $this->company->companysize; ?>" />
                                    </div>
                                </div>				        
                    <?php } ?>
                    <?php
                    break;
                case "income":
                    ?>
                            <?php
                            if ($field->published == 1) {
                                $income_required = ($field->required ? 'required' : '');
                                ?>
                                <div class="fieldwrapper">
                                    <div class="fieldtitle">
                                        <label id="incomemsg" for="income"><?php echo JText::_('Income'); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                    </div>
                                    <div class="fieldvalue">
                                        <input class="inputbox <?php echo $income_required; ?> validate-numeric" maxlength="6" type="text" name="income" id="income" size="20" maxlength="10" value="<?php if (isset($this->company)) echo $this->company->income; ?>" />
                                    </div>
                                </div>				        
                    <?php } ?>
                                    <?php
                                    break;
                                case "description":
                                    ?>
                                    <?php
                                    if ($field->published == 1) {
                                        $description_required = ($field->required ? 'required' : '');
                                        ?>
                        <?php if ($this->config['comp_editor'] == '1') { ?>
                                    <div class="fieldwrapper">
                                        <div class="fieldtitle">
                                            <label id="descriptionmsg" for="description"><strong><?php echo JText::_('Description'); ?></strong></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                        </div>
                                        <div class="fieldvalue">
                            <?php
                            $editor = JFactory::getEditor();
                            if (isset($this->company))
                                echo $editor->display('description', $this->company->description, '100%', '100%', '60', '20', false);
                            else
                                echo $editor->display('description', '', '100%', '100%', '60', '20', false);
                            ?>	
                                            <input type='hidden' id='company-description-required' name="company-description-required" value="<?php echo $description_required; ?>" />
                                        </div>
                                    </div>				        
                                <?php } else { ?>
                                    <div class="js_job_form_wrapper fullwidth">
                                        <div class="fieldtitle">
                                            <label id="descriptionmsg" for="description"><?php echo JText::_('Description'); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                        </div>
                                        <div class="fieldvalue">
                                            <textarea class="inputbox <?php echo $description_required; ?>" name="description" id="description" cols="60" rows="5"><?php if (isset($this->company)) echo $this->company->description; ?></textarea>
                                        </div>
                                    </div>				        
                        <?php } ?>
                    <?php } ?>
                    <?php
                    break;
                case "city":
                    ?>
                            <?php if ($this->config['comp_city'] == 1) { ?>
                                <?php
                                if ($field->published == 1) {
                                    $city_required = ($field->required ? 'required' : '');
                                    ?>
                                    <div class="fieldwrapper">
                                        <div class="fieldtitle">
                                            <label id="citymsg" for="city"><?php echo JText::_('City'); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                        </div>
                                        <div class="fieldvalue">
                                            <input class="inputbox <?php echo $city_required; ?>" type="text" name="city" id="city" size="40" maxlength="100" value="" />
                                            <input class="inputbox" type="hidden" name="citynameforedit" id="citynameforedit" size="40" maxlength="100" value="<?php if (isset($this->multiselectedit)) echo $this->multiselectedit; ?>" />
                                        </div>
                                    </div>				        
                        <?php } ?>
                            <?php } ?>
                            <?php
                            break;
                        case "zipcode":
                            ?>
                            <?php if ($this->config['comp_zipcode'] == 1) { ?>
                                <?php
                                if ($field->published == 1) {
                                    $zipcode_required = ($field->required ? 'required' : '');
                                    ?>
                                    <div class="fieldwrapper">
                                        <div class="fieldtitle">
                                            <label id="zipcodemsg" for="zipcode"><?php echo JText::_('Zip Code'); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                        </div>
                                        <div class="fieldvalue">
                                            <input class="inputbox validate-numeric <?php echo $zipcode_required; ?>" maxlength="6" type="text" name="zipcode" size="40" maxlength="100" value="<?php if (isset($this->company)) echo $this->company->zipcode; ?>" />
                                        </div>
                                    </div>				        
                                <?php } ?>
                            <?php } ?>
                            <?php
                            break;
                        case "address1":
                            ?>
                    <?php
                    if ($field->published == 1) {
                        $address1_required = ($field->required ? 'required' : '');
                        ?>
                                <div class="fieldwrapper">
                                    <div class="fieldtitle">
                                        <label id="address1msg" for="address1"><?php echo JText::_('Address 1'); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                    </div>
                                    <div class="fieldvalue">
                                        <input class="inputbox <?php echo $address1_required; ?>" type="text" name="address1" size="40" maxlength="100" value="<?php if (isset($this->company)) echo $this->company->address1; ?>" />
                                    </div>
                                </div>				        
                            <?php } ?>
                            <?php
                            break;
                        case "address2":
                            ?>
                    <?php
                    if ($field->published == 1) {
                        $address2_required = ($field->required ? 'required' : '');
                        ?>
                                <div class="fieldwrapper">
                                    <div class="fieldtitle">
                                        <label id="address2msg" for="address2"><?php echo JText::_('Address 2'); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                    </div>
                                    <div class="fieldvalue">
                                        <input class="inputbox <?php echo $address2_required; ?>" type="text" name="address2" size="40" maxlength="100" value="<?php if (isset($this->company)) echo $this->company->address2; ?>" />
                                    </div>
                                </div>				        
                    <?php } ?>
                    <?php
                    break;
                case "logo":
                    ?>
                                           <?php
                                           if ($field->published == 1) {
                                               $logo_required = ($field->required ? 'required' : '');
                                               ?>
                                <div class="fieldwrapper">
                                    <div class="fieldtitle">
                                        <label id="logomsg" for="logo">	<?php echo JText::_('Logo'); ?>	</label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                    </div>
                                    <div class="fieldvalue">
                        <?php
                        if (isset($this->company)) {
                            if ($this->company->logofilename != '') {
                                ?>
                                                <div class="fieldvalue_checkboxoption"><input type='checkbox' name='deletelogo' value='1'><?php echo JText::_('Delete Logo File') . '[' . $this->company->logofilename . ']'; ?></div>
                                                <?php
                                            }
                                        }
                                        ?>				
                                        <input type="file" class="inputbox" id="logo" name="logo" size="20" maxlenght='3'/>
                                        <br><small><?php echo JText::_('Maximum Width'); ?> : 200px)</small>
                                        <br><small><?php echo JText::_('Maximum File Size') . ' (' . $this->config['company_logofilezize']; ?>KB)</small>
                                        <input type='hidden' id='company-logo-required' name="company-logo-required" value="<?php echo $logo_required; ?>">
                                        <input type='hidden' id='company-logofilename' value="<?php if (isset($this->company->logofilename))
                            echo $this->company->logofilename;
                        else
                            echo "";
                                        ?>">
                                    </div>
                                </div>				        
                    <?php } ?>
                    <?php
                    break;
                case "smalllogo":
                    ?>
                                    <?php if ($field->published == 1) {
                                        ?>
                                <div class="fieldwrapper">
                                    <div class="fieldtitle">
                                        <label id="smalllogomsg" for="smalllogo"><?php echo JText::_('Small Logo'); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                    </div>
                                    <div class="fieldvalue">
                        <?php
                        if (isset($this->company))
                            if ($this->company->smalllogofilename != '') {
                                ?>
                                                <div class="fieldvalue_checkboxoption"><input type='checkbox' name='deletesmalllogo' value='1'><?php echo JText::_('Delete Small Logo') . '[' . $this->company->smalllogofilename . ']'; ?></div>
                            <?php } ?>				
                                        <input type="file" class="inputbox" name="smalllogo" size="20" maxlenght='30'/>
                                    </div>
                                </div>				        
                    <?php } ?>
                            <?php
                            break;
                        case "aboutcompany":
                            ?>
                            <?php if ($field->published == 1) {
                                ?>
                                <div class="fieldwrapper">
                                    <div class="fieldtitle">
                                        <label id="aboutcompanymsg" for="aboutcompany"><?php echo JText::_('About Company'); ?>	</label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                    </div>
                                    <div class="fieldvalue">
                                <?php
                                if (isset($this->company))
                                    if ($this->company->aboutcompanyfilename != '') {
                                        ?>
                                                <div class="fieldvalue_checkboxoption"><input type='checkbox' name='deleteaboutcompany' value='1'><?php echo JText::_('Delete About Company File') . '[' . $this->company->aboutcompanyfilename . ']'; ?></div>
                                    <?php } ?>				
                                        <input type="file" class="inputbox" name="aboutcompany" size="20" maxlenght='30'/>
                                    </div>
                                </div>				        
                            <?php } ?>
                            <div class="fieldwrapper">
                                <div class="fieldtitle">
                                    <label  id="jobcategorymsg" for="jobcategory"><?php echo JText::_('Categories'); ?></label><?php if ($field->required == 1) { ?> &nbsp;<font color="red">*</font><?php } ?>
                                </div>
                                <div class="fieldvalue">
                            <?php echo $this->lists['jobcategory']; ?>
                                </div>
                            </div>				        
                            <?php
                            break;
                        default:
                            if ($field->published == 1) {
                                if (isset($this->userfields))
                                    foreach ($this->userfields as $ufield) {
                                        if ($field->field == $ufield[0]->id) {
                                            $userfield = $ufield[0];
                                            $i++;
                                            echo '<div class="fieldwrapper">';
                                            if ($field->required == 1) {
                                                echo '  <div class="fieldtitle">
                                                            <label id=' . $userfield->name . 'msg for="userfields_' . $i . '_id">' . JText::_($userfield->title) . '</label>&nbsp;<font color="red">*</font>
                                                        </div>';
                                                if ($userfield->type == 'emailaddress')
                                                    $cssclass = "class ='inputbox required validate-email' ";
                                                else
                                                    $cssclass = "class ='inputbox required' ";
                                            }else {
                                                if ($userfield->type == 'emailaddress')
                                                    $cssclass = "class ='inputbox validate-email' ";
                                                else
                                                    $cssclass = "class ='inputbox' ";
                                                echo '<div class="fieldtitle">'
                                                . JText::_($userfield->title) .
                                                '</div>
                                                                                ';
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
                                                    if ($cssclass == "class ='inputbox required' ")
                                                        $css = 'inputbox required';
                                                    else
                                                        $css = 'inputbox ';
                                                    echo JHTML::_('calendar', $fvalue, 'userfields_' . $i, 'userfields_' . $i, '%Y-%m-%d', array('class' => $css, 'size' => '10', 'maxlength' => $maxlength));
                                                    break;
                                                case 'textarea':
                                                    echo '<textarea name="userfields_' . $i . '" id="userfields_' . $i . '_field" cols="' . $userfield->cols . '" rows="' . $userfield->rows . '" ' . $readonly . $cssclass . '>' . $fvalue . '</textarea>';
                                                    break;
                                                case 'checkbox':
                                                    echo '<input type="checkbox" name="userfields_' . $i . '" id="userfields_' . $i . '_field" value="1" ' . 'checked="checked"' . '/>';
                                                    break;
                                                case 'select':
                                                    $htm = '<select name="userfields_' . $i . '" id="userfields_' . $i . '" >';
                                                    if (isset($ufield[2])) {
                                                        foreach ($ufield[2] as $opt) {
                                                            if ($opt->id == $fvalue)
                                                                $htm .= '<option value="' . $opt->id . '" selected="yes">' . $opt->fieldtitle . ' </option>';
                                                            else
                                                                $htm .= '<option value="' . $opt->id . '">' . $opt->fieldtitle . ' </option>';
                                                        }
                                                    }
                                                    $htm .= '</select>';
                                                    echo $htm;
                                                    break;
                                                case 'editortext':
                                                    $editor = JFactory::getEditor();
                                                    if (isset($this->company))
                                                        echo $editor->display("userfields_$i", $fvalue, '100%', '100%', '60', '20', false);
                                                    else
                                                        echo $editor->display("userfields_$i", '', '100%', '100%', '60', '20', false);
                                            }
                                            echo '</div></div>';
                                        }
                                    }
                            }
                    }
                }
                echo '<input type="hidden" id="userfields_total" name="userfields_total"  value="' . $i . '"  />';
                ?>
                <div class="fieldwrapper">
                    <input class="button" id="button" type="submit" name="submit_app" value="<?php echo JText::_('Save Company'); ?>" />
                </div>
        <?php
        if (isset($this->company)) {
            if (($this->company->created == '0000-00-00 00:00:00') || ($this->company->created == ''))
                $curdate = date('Y-m-d H:i:s');
            else
                $curdate = $this->company->created;
        } else
            $curdate = date('Y-m-d H:i:s');
        ?>
                <input type="hidden" name="created" value="<?php echo $curdate; ?>" />
                <input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
                <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                <input type="hidden" name="task" value="savecompany" />
                <input type="hidden" name="c" value="company" />
                <input type="hidden" name="check" id="check" value="" />
        <?php if (isset($this->packagedetail[0])) echo '<input type="hidden" name="packageid" value="' . $this->packagedetail[0] . '" />'; ?>
        <?php if (isset($this->packagedetail[1])) echo '<input type="hidden" name="paymenthistoryid" value="' . $this->packagedetail[1] . '" />'; ?>
                <input type="hidden" name="j_dateformat" id="j_dateformat" value="<?php echo $js_scriptdateformat; ?>" />

                <input type="hidden" name="Itemid" id="Itemid" value="<?php echo $this->Itemid; ?>" />
                <input type="hidden" id="id" name="id" value="<?php if (isset($this->company)) echo $this->company->id; ?>" />


                <script language=Javascript>
                    // This code is changed for multicity selection of cities in form	
                    jQuery(document).ready(function () {
                        var cityname = jQuery("#citynameforedit").val();
                        if (cityname != "") {
                            jQuery("#city").tokenInput("<?php echo JURI::root() . "index.php?option=com_jsjobs&c=cities&task=getaddressdatabycityname"; ?>", {
                                theme: "jsjobs",
                                preventDuplicates: true,
                                hintText: "<?php echo JText::_('Type In A Search'); ?>",
                                noResultsText: "<?php echo JText::_('No Results'); ?>",
                                searchingText: "<?php echo JText::_('Searching...'); ?>",
                                //tokenLimit: 1,
                                prePopulate: <?php if (isset($this->multiselectedit)) echo $this->multiselectedit; else echo "''"; ?>,
                                <?php if($this->config['newtyped_cities'] == 1){ ?>
                                onResult: function (item) {
                                    console.log('onresult ' + item);
                                    if (jQuery.isEmptyObject(item)) {
                                        return [{id: 0, name: jQuery("tester").text()}];
                                    } else {
                                        //add the item at the top of the dropdown
                                        item.unshift({id: 0, name: jQuery("tester").text()});
                                        return item;
                                    }
                                },
                                onAdd: function (item) {
                                    console.log('onadd ' + item);
                                    if (item.id > 0) {
                                        return;
                                    }
                                    if (item.name.search(",") == -1) {
                                        var input = jQuery("tester").text();
                                        msg = '<?php echo JText::_("Location format is not correct please enter city in this format")."<br/>".JText::_("city name, country name")."<br/>".JText::_("or")."<br/>".JText::_("city name, state name, country name"); ?>';
                                        jQuery('#city').tokenInput('remove', item);
                                        jQuery("div#warn-message").find("span.text").html(msg).show();
                                        jQuery("div#warn-message").show();
                                        jQuery("div#black_wrapper_jobapply").show();
                                        return false;
                                    } else {
                                        jQuery.post('index.php?option=com_jsjobs&task=cities.savecity', {citydata: jQuery("tester").text()}, function (data) {
                                            if (data) {
                                                try {
                                                    var value = jQuery.parseJSON(data);
                                                    jQuery('#city').tokenInput('remove', item);
                                                    jQuery('#city').tokenInput('add', {id: value.id, name: value.name});
                                                } catch (e) { // string is not the json its the message come from server
                                                    msg = data;
                                                    jQuery("div#warn-message").find("span.text").html(msg).show();
                                                    jQuery("div#warn-message").show();
                                                    jQuery("div#black_wrapper_jobapply").show();
                                                    jQuery('#city').tokenInput('remove', item);
                                                }
                                            }
                                        });
                                    }
                                }
                                <?php } ?>
                            });
                        } else {
                            jQuery("#city").tokenInput("<?php echo JURI::root() . "index.php?option=com_jsjobs&c=cities&task=getaddressdatabycityname"; ?>", {
                                theme: "jsjobs",
                                preventDuplicates: true,
                                hintText: "<?php echo JText::_('Type In A Search'); ?>",
                                noResultsText: "<?php echo JText::_('No Results'); ?>",
                                searchingText: "<?php echo JText::_('Searching...'); ?>",
                                //tokenLimit: 1
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
                                        jQuery('#city').tokenInput('remove', item);
                                        jQuery("div#warn-message").find("span.text").html(msg).show();
                                        jQuery("div#warn-message").show();
                                        jQuery("div#black_wrapper_jobapply").show();
                                        return false;
                                    } else {
                                        jQuery.post('index.php?option=com_jsjobs&task=cities.savecity', {citydata: jQuery("tester").text()}, function (data) {
                                            if (data) {
                                                try {
                                                    var value = jQuery.parseJSON(data);
                                                    jQuery('#city').tokenInput('remove', item);
                                                    jQuery('#city').tokenInput('add', {id: value.id, name: value.name});
                                                } catch (e) { // string is not the json its the message come from server
                                                    msg = data;
                                                    jQuery("div#warn-message").find("span.text").html(msg).show();
                                                    jQuery("div#warn-message").show();
                                                    jQuery("div#black_wrapper_jobapply").show();
                                                    jQuery('#city').tokenInput('remove', item);
                                                }
                                            }
                                        });
                                    }
                                }
                                <?php } ?>
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

                </script>


            </form>
        </div>
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
    
                    <?php
    } else { // can not add new company
        $itemid = JRequest::getVar('Itemid');
        switch ($this->canaddnewcompany) {
            case NO_PACKAGE:
                $link = "index.php?option=com_jsjobs&c=employerpackages&view=employerpackages&layout=packages&Itemid=".$this->Itemid;
                $this->jsjobsmessages->getPackageExpireMsg('Package Not Purchased', 'Package is required to perform this action, please get package', $link);
            break;
            case EXPIRED_PACKAGE:
                $link = "index.php?option=com_jsjobs&c=employerpackages&view=employerpackages&layout=packages&Itemid=".$this->Itemid;
                $this->jsjobsmessages->getPackageExpireMsg('Your current package is expired', 'Package is required to perform this action and your current package is expired, please get new package', $link);
            break;
            case COMPANY_LIMIT_EXCEEDS:
                $link = "index.php?option=com_jsjobs&c=employerpackages&view=employerpackages&layout=packages&Itemid=".$this->Itemid;
                $this->jsjobsmessages->getPackageExpireMsg('Company Limit Exceeds', 'You Can Not Add New Company, Please Get Package To Extend Your Company Limit', $link);
            break;
            case JOBSEEKER_NOT_ALLOWED_EMPLOYER_PRIVATE_AREA:
                $this->jsjobsmessages->getAccessDeniedMsg('Job Seeker Not Allow', 'Job seeker is not allow in employer private area', 0);
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

