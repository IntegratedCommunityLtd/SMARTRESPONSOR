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
 * File Name:	views/jobseeker/tmpl/formcoverletter.php
  ^
 * Description: template for form cover letter
  ^
 * History:		NONE
  ^
 */
defined('_JEXEC') or die('Restricted access');
$big_field_width = 40;
$med_field_width = 25;
$sml_field_width = 15;
JHTML::_('behavior.formvalidation');
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
    ?>
    <script language="javascript">
        function myValidate(f) {
            if (document.formvalidator.isValid(f)) {
                f.check.value = '<?php if (JVERSION < 3)
        echo JUtility::getToken();
    else
        echo JSession::getFormToken();
    ?>';//send token
            } else {
                alert('<?php echo JText::_('Some values are not acceptable, please retry'); ?>');
                return false;
            }
            return true;
        }

    </script>
    <?php
    if ($this->canaddnewcoverletter == VALIDATE) { // add new coverletter, in edit case always 1
        ?>
        <div id="js_main_wrapper">
            <span class="js_controlpanel_section_title"><?php echo JText::_('Cover Letter Form'); ?></span>
            <form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate jsautoz_form" onSubmit="return myValidate(this);">
                <div class="fieldwrapper">
                    <div class="fieldtitle">
                        <label id="titlemsg" for="title"><?php echo JText::_('Title'); ?></label>&nbsp;<font color="red">*</font>:
                    </div>
                    <div class="fieldvalue">
                        <input class="inputbox required" type="text" name="title" id="title" size="<?php echo $big_field_width; ?>" maxlength="250" value = "<?php if (isset($this->coverletter)) echo $this->coverletter->title; ?>" />
                    </div>
                </div>				        
                <div class="fieldwrapper">
                    <div class="fieldtitle">
                        <label id="descriptionmsg" for="description"><?php echo JText::_('Description'); ?></label>&nbsp;<font color="red">*</font>:
                    </div>
                    <div class="fieldvalue">
                        <textarea class="inputbox required" name="description" id="description" cols="60" rows="9"><?php if (isset($this->coverletter)) echo $this->coverletter->description; ?></textarea>
                    </div>
                </div>				        
                <div class="fieldwrapper">
                    <input type="submit" id="button" class="button" value="<?php echo JText::_('Save Cover Letter'); ?>"/>
                </div>				        
                <?php
                if (isset($this->coverletter)) {
                    if (($this->coverletter->created == '0000-00-00 00:00:00') || ($this->coverletter->created == ''))
                        $curdate = date('Y-m-d H:i:s');
                    else
                        $curdate = $this->coverletter->created;
                } else
                    $curdate = date('Y-m-d H:i:s');
                ?>
                <input type="hidden" name="created" value="<?php echo $curdate; ?>" />
                <input type="hidden" name="id" value="<?php if (isset($this->coverletter)) echo $this->coverletter->id; ?>" />
                <input type="hidden" name="layout" value="empview" />
                <input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
                <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                <input type="hidden" name="task" value="savecoverletter" />
                <input type="hidden" name="c" value="coverletter" />
                <input type="hidden" name="check" value="" />
                <input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
        <?php if (isset($this->packagedetail[0])) echo '<input type="hidden" name="packageid" value="' . $this->packagedetail[0] . '" />'; ?>
        <?php if (isset($this->packagedetail[1])) echo '<input type="hidden" name="paymenthistoryid" value="' . $this->packagedetail[1] . '" />'; ?>



            </form>
        </div>

        <?php
    } else { // can not add new coverletter 
        switch ($this->canaddnewcoverletter) {
            case EMPLOYER_NOT_ALLOWED_JOBSEEKER_PRIVATE_AREA:
                $this->jsjobsmessages->getAccessDeniedMsg('Employer Not Allow', 'Employer is not allow in job seeker private area', 0);
                break;
            case NO_PACKAGE:
                $link = "index.php?option=com_jsjobs&c=jobseekerpackages&view=jobseekerpackages&layout=packages&Itemid=".$this->Itemid;
                $this->jsjobsmessages->getPackageExpireMsg('Package Not Purchased', 'Package is required to perform this action, please get package', $link);
                break;
            case EXPIRED_PACKAGE:
                $link = "index.php?option=com_jsjobs&c=jobseekerpackages&view=jobseekerpackages&layout=packages&Itemid=".$this->Itemid;
                $this->jsjobsmessages->getPackageExpireMsg('Your current package is expired', 'Package is required to perform this action and your current package is expired, please get new package', $link);
                break;
            case COVER_LETTER_LIMIT_EXCEEDS:
                $link = "index.php?option=com_jsjobs&c=jobseekerpackages&view=jobseekerpackages&layout=packages&Itemid=".$this->Itemid;
                $this->jsjobsmessages->getPackageExpireMsg('Cover letter limit exceeds', 'You Can Not Add New Cover Letter, Please Get Package To Extend Your Cover Letter Limit', $link);
                break;
            case USER_ROLE_NOT_SELECTED:
                $link = "index.php?option=com_jsjobs&c=common&view=common&layout=new_injsjobs&Itemid=".$this->Itemid;
                $this->jsjobsmessages->getUserNotSelectedMsg('User Role Not Selected', 'User Role Is Not Selected, Please Select Your Role', $link);
                break;
            case VISITOR_NOT_ALLOWED_JOBSEEKER_PRIVATE_AREA:
                $this->jsjobsmessages->getAccessDeniedMsg('Visitor Not Allow', 'Visitor is not allow in employer private area', 1);
                break;
        }
    }
}//ol
?>		

