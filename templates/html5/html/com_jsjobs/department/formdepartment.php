<?php
/**
 * @Copyright Copyright (C) 2009-2011
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
  + Created by:          Ahmad Bilal
 * Company:		Buruj Solutions
  + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	May 17, 2010
  ^
  + Project: 		JS Jobs
 * File Name:	views/employer/tmpl/formdepartment.php
  ^
 * Description: template view for form department
  ^
 * History:		NONE
  ^
 */
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.formvalidation');
$document = JFactory::getDocument();
if (JVERSION < 3) {
    JHtml::_('behavior.mootools');
    $document->addScript('components/com_jsjobs/js/jquery.js');
} else {
    JHtml::_('behavior.framework');
    JHtml::_('jquery.framework');
}
?>
<div id="js_menu_wrapper">
    <?php
    if (sizeof($this->jobseekerlinks) != 0) {
        foreach ($this->jobseekerlinks as $lnk) {
            ?>                     
            <a class="js_menu_link <?php if ($lnk[2] == 'coverletter') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
            <?php
        }
    }
    if (sizeof($this->employerlinks) != 0) {
        foreach ($this->employerlinks as $lnk) {
            ?>
            <a class="js_menu_link <?php if ($lnk[2] == 'coverletter') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
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
    if ($this->formdepartment_allowed == VALIDATE) { // employer
        ?>
        <div id="js_main_wrapper">
            <span class="js_controlpanel_section_title"><?php echo JText::_('Department Information'); ?></span>        
            <form action="index.php" method="post" name="adminForm" id="adminForm" class="jsautoz_form" onSubmit="return myValidate(this);">
                <div class="fieldwrapper">
                    <div class="fieldtitle">
                        <label id="companyidmsg" for="companyid"><?php echo JText::_('Company'); ?></label>&nbsp;<font color="red">*</font>
                    </div>
                    <div class="fieldvalue">
        <?php echo $this->lists['companies']; ?>
                    </div>
                </div>				        
                <div class="fieldwrapper">
                    <div class="fieldtitle">
                        <label id="namemsg" for="name"><?php echo JText::_('Department Name'); ?></label>&nbsp;<font color="red">*</font>
                    </div>
                    <div class="fieldvalue">
                        <input class="inputbox required" type="text" name="name" id="name"  value="<?php if (isset($this->department)) echo $this->department->name; ?>" />
                    </div>
                </div>				        
                <div class="fieldwrapper">
                    <div class="fieldtitle">
                        <label id="descriptionmsg" for="description"><strong><?php echo JText::_('Description'); ?></strong></label>
                    </div>
                    <div class="fieldvalue">
                        <?php
                        $editor = JFactory::getEditor();
                        if (isset($this->department))
                            echo $editor->display('description', $this->department->description, '100%', '100%', '60', '20', false);
                        else
                            echo $editor->display('description', '', '100%', '100%', '60', '20', false);
                        ?>	
                    </div>
                </div>				        
                <div class="fieldwrapper">
                    <input type="submit" id="button" class="button" value="<?php echo JText::_('Save'); ?>"/>
                </div>				        

                <?php
                if (isset($this->department)) {
                    if (($this->department->created == '0000-00-00 00:00:00') || ($this->department->created == ''))
                        $curdate = date('Y-m-d H:i:s');
                    else
                        $curdate = $this->department->created;
                } else
                    $curdate = date('Y-m-d H:i:s');
                ?>
                <input type="hidden" name="created" value="<?php echo $curdate; ?>" />
                <input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
                <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                <input type="hidden" name="task" value="savedepartment" />
                <input type="hidden" name="c" value="department" />
                <input type="hidden" name="Itemid" id="Itemid" value="<?php echo $this->Itemid; ?>" />
                <input type="hidden" name="id" value="<?php if (isset($this->department)) echo $this->department->id; ?>" /> 
            </form>
        </div>		
        <?php
    } else {
        switch ($this->formdepartment_allowed) {
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
}
?>

