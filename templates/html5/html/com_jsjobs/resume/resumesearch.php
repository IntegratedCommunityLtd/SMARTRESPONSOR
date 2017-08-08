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
 * File Name:   views/employer/tmpl/jobsearch.php
  ^
 * Description: template for job search
  ^
 * History:     NONE
  ^
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.pane');
$document = JFactory::getDocument();
$document->addScript(JURI::base() . '/includes/js/joomla.javascript.js');
$document->addStyleSheet('components/com_jsjobs/css/token-input-jsjobs.css');
$document->addStyleSheet('components/com_jsjobs/css/combobox/chosen.css');
JHTML :: _('behavior.calendar');
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

$width_big = 40;
$width_med = 25;
$width_sml = 15;
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
    <?php
    if ($this->canview == VALIDATE) {
        ?>
        <div id="js_main_wrapper">
            <span class="js_controlpanel_section_title"><?php echo JText::_('Resume Search'); ?></span>
            <form action="<?php echo JRoute::_('index.php?option=com_jsjobs&c=resume&view=resume&layout=resume_searchresults&Itemid=' . $this->Itemid); ?>" method="post" name="adminForm" id="adminForm" class="jsautoz_form" >
                <?php if ($this->searchresumeconfig['search_resume_title'] == '1') { ?>
                    <div class="fieldwrapper">
                        <div class="fieldtitle">
                            <?php echo JText::_('Application Title'); ?>
                        </div>
                        <div class="fieldvalue">
                            <input class="inputbox" type="text" name="title" size="40" maxlength="255"  />
                        </div>
                    </div>                      
                <?php } ?>
                <?php if ($this->searchresumeconfig['search_resume_name'] == '1') { ?>
                    <div class="fieldwrapper">
                        <div class="fieldtitle">
                            <?php echo JText::_('Name'); ?>
                        </div>
                        <div class="fieldvalue">
                            <input class="inputbox" type="text" name="name" size="40" maxlength="255"  />
                        </div>
                    </div>                      
                <?php } ?>
                <?php if ($this->searchresumeconfig['search_resume_nationality'] == '1') { ?>
                    <div class="fieldwrapper">
                        <div class="fieldtitle">
                            <?php echo JText::_('Nationality'); ?>
                        </div>
                        <div class="fieldvalue">
                            <?php echo $this->searchoptions['nationality']; ?>
                        </div>
                    </div>                      
                <?php } ?>
                <?php if ($this->searchresumeconfig['search_resume_gender'] == '1') { ?>
                    <div class="fieldwrapper">
                        <div class="fieldtitle">
                            <?php echo JText::_('Gender'); ?>
                        </div>
                        <div class="fieldvalue">
                            <?php echo $this->searchoptions['gender']; ?>
                        </div>
                    </div>                      
                <?php } ?>
                <?php if ($this->searchresumeconfig['search_resume_available'] == '1') { ?>
                    <div class="fieldwrapper">
                        <div class="fieldtitle">
                            <?php echo JText::_('I Am Available'); ?>
                        </div>
                        <div class="fieldvalue">
                            <input type='checkbox' name='iamavailable' value='1' <?php if (isset($this->resume)) echo ($this->resume->iamavailable == 1) ? "checked='checked'" : ""; ?> />
                        </div>
                    </div>                      
                <?php } ?>
                <?php if ($this->searchresumeconfig['search_resume_category'] == '1') { ?>
                    <div class="fieldwrapper">
                        <div class="fieldtitle">
                            <?php echo JText::_('Categories'); ?>
                        </div>
                        <div class="fieldvalue">
                            <?php echo $this->searchoptions['jobcategory']; ?>
                        </div>
                    </div>                      
                <?php } ?>
                <?php if ($this->searchresumeconfig['search_resume_subcategory'] == '1') { ?>
                    <div class="fieldwrapper">
                        <div class="fieldtitle">
                            <?php echo JText::_('Sub Categories'); ?>
                        </div>
                        <div class="fieldvalue" id="fj_subcategory">
                            <?php echo $this->searchoptions['jobsubcategory']; ?>
                        </div>
                    </div>                      
                <?php } ?>
                <?php if ($this->searchresumeconfig['search_resume_type'] == '1') { ?>
                    <div class="fieldwrapper">
                        <div class="fieldtitle">
                            <?php echo JText::_('Job Type'); ?>
                        </div>
                        <div class="fieldvalue">
                            <?php echo $this->searchoptions['jobtype']; ?>
                        </div>
                    </div>                      
                <?php } ?>
                <?php if ($this->searchresumeconfig['search_resume_salaryrange'] == '1') { ?>
                    <div class="fieldwrapper">
                        <div class="fieldtitle">
                            <?php echo JText::_('Salary Range'); ?>
                        </div>
                        <div class="fieldvalue">
                            <?php echo $this->searchoptions['currency']; ?><?php echo $this->searchoptions['jobsalaryrange']; ?>
                        </div>
                    </div>                      
                <?php } ?>
                <?php if ($this->searchresumeconfig['search_resume_heighesteducation'] == '1') { ?>
                    <div class="fieldwrapper">
                        <div class="fieldtitle">
                            <?php echo JText::_('Highest Finished Education'); ?>
                        </div>
                        <div class="fieldvalue">
                            <?php echo $this->searchoptions['heighestfinisheducation']; ?>
                        </div>
                    </div>                      
                <?php } ?>
                <?php if ($this->searchresumeconfig['search_resume_experience'] == '1') { ?>
                    <div class="fieldwrapper">
                        <div class="fieldtitle">
                            <?php echo JText::_('Experience'); ?>
                        </div>
                        <div class="fieldvalue">
                            <input class="inputbox" type="text" name="experience" size="10" maxlength="15"  />
                        </div>
                    </div>                      
                <?php } ?>
                <?php if ($this->searchresumeconfig['search_resume_location'] == '1') { ?>
                    <div class="fieldwrapper">
                        <div class="fieldtitle">
                            <?php echo JText::_('Location'); ?>
                        </div>
                        <div class="fieldvalue">
                            <input type="text" name="searchcity" size="40" id="searchcity"  value="" />
                        </div>
                    </div>                      
                <?php } ?>
                <?php if ($this->searchresumeconfig['search_resume_zipcode'] == '1') { ?>
                    <div class="fieldwrapper">
                        <div class="fieldtitle">
                            <?php echo JText::_('Zip Code'); ?>
                        </div>
                        <div class="fieldvalue">
                            <input class="inputbox" type="text" name="zipcode" size="10" maxlength="15"  />
                        </div>
                    </div>                      
                <?php } ?>
                <?php if ($this->searchresumeconfig['search_resume_keywords'] == '1') { ?>
                    <div class="fieldwrapper">
                        <div class="fieldtitle">
                            <?php echo JText::_('Keywords'); ?>
                        </div>
                        <div class="fieldvalue">
                            <input class="inputbox" type="text" name="keywords" size="40"   />
                        </div>
                    </div>                      
                <?php } ?>
                <div class="fieldwrapper">
                    <input type="submit" id="button" class="button" name="submit_app" onclick="document.adminForm.submit();" value="<?php echo JText::_('Resume Search'); ?>" />
                </div>                      
                <input type="hidden" name="isresumesearch" value="1" />
                <input type="hidden" name="view" value="resume" />
                <input type="hidden" name="layout" value="resume_searchresults" />
                <input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
                <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                <input type="hidden" name="task11" value="view" />


                <script language="javascript">
                    function fj_getsubcategories(src, val) {
                        jQuery("#" + src).html("Loading ...");
                        jQuery.post('index.php?option=com_jsjobs&c=subcategory&task=listsubcategoriesForSearch', {val: val}, function (data) {
                            if (data) {
                                jQuery("#" + src).html(data);
                                jQuery("#" + src + " select.jsjobs-cbo").chosen();
                            } else {
                                jQuery("#" + src).html('<?php echo '<input type="text" name="jobsubcategory" value="">'; ?>');
                            }
                        });

                    }
                </script>


            </form>
        </div>
        <?php
    } else {
        switch ($this->canview) {
            case NO_PACKAGE:
                $link = "index.php?option=com_jsjobs&c=employerpackages&view=employerpackages&layout=packages&Itemid=".$this->Itemid;
                $this->jsjobsmessages->getPackageExpireMsg('Package Not Purchased', 'Package is required to perform this action, please get package', $link);
                break;
            case EXPIRED_PACKAGE:
                $link = "index.php?option=com_jsjobs&c=employerpackages&view=employerpackages&layout=packages&Itemid=".$this->Itemid;
                $this->jsjobsmessages->getPackageExpireMsg('Your current package is expired', 'Package is required to perform this action and your current package is expired, please get new package', $link);
                break;
            case RESUME_SEARCH_NOT_ALLOWED_IN_PACAKGE:
                $link = "index.php?option=com_jsjobs&c=employerpackages&view=employerpackages&layout=packages&Itemid=".$this->Itemid;
                $this->jsjobsmessages->getPackageExpireMsg('Resume search is not allowed in package', 'Package is required to perform this action and your current package is expired, please get new package', $link);
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

<script type="text/javascript" language="javascript">
    jQuery(document).ready(function () {
        jQuery("select.jsjobs-cbo").chosen();
        jQuery("input.jsjobs-inputbox")
                .css({
                    'width': '192px',
                    'border': '1px solid #A9ABAE',
                    'cursor': 'text',
                    'margin': '0',
                    'padding': '4px'
                });
        jQuery("#searchcity").tokenInput("<?php echo JURI::root() . "index.php?option=com_jsjobs&c=cities&task=getaddressdatabycityname"; ?>", {
            theme: "jsjobs",
            preventDuplicates: true,
            hintText: "<?php echo JText::_('Type In A Search'); ?>",
            noResultsText: "<?php echo JText::_('No Results'); ?>",
            searchingText: "<?php echo JText::_('Searching...'); ?>",
            tokenLimit: 1
        });

    });

</script>
