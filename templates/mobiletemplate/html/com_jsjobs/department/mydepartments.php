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
 * File Name:	views/employer/tmpl/mydepartments.php
  ^
 * Description: template view for my departments
  ^
 * History:		NONE
  ^
 */
defined('_JEXEC') or die('Restricted access');
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
    if ($this->mydepartment_allowed == VALIDATE) {
        if ($this->departments) {
            ?>
            <div id="js_main_wrapper">
                <span class="js_controlpanel_section_title"><?php echo JText::_('My Departments'); ?></span>        
                <form action="index.php" method="post" name="adminForm">
                    <?php foreach ($this->departments as $department) { ?>
                        <div class="js_listing_wrapper">
                            <span class="js_coverletter_title">
                                <?php echo $department->name; ?>
                            </span>
                            <span class="js_coverletter_sub_data">
                                <span class="js_listing_title_child"><span class="js_listing_title_child_title"><?php echo JText::_('Company'); ?>&nbsp;:</span><?php echo $department->companyname; ?></span>
                                <span class="js_listing_title_child"><span class="js_listing_title_child_title"><?php echo JText::_('Status'); ?>&nbsp;:</span>
                                    <?php
                                    if ($department->status == 1)
                                        echo '<font color="green">' . JText::_('Approved') . '</font>';
                                    elseif ($department->status == 0) {
                                        echo '<span class="jobstatusmsg"> ' . JText::_('Pending') . '</span>';
                                    } elseif ($department->status == -1)
                                        echo '<font color="red"> ' . JText::_('Rejected') . '</font>';
                                    ?>
                                </span>
                            </span>
                <?php if ($department->status == 1) { ?>
                                <div class="js_coverletter_button_area sub_data" >
                                    <span class="js_coverletter_created"><span class="js_coverletter_created_title"><?php echo JText::_('Created'); ?>&nbsp;:</span><?php echo JHtml::_('date', $department->created, $this->config['date_format']); ?></span>
                                    <a class="js_listing_icon" href="index.php?option=com_jsjobs&c=department&view=department&layout=formdepartment&pd=<?php echo $department->aliasid; ?>&Itemid=<?php echo $this->Itemid; ?>" title="<?php echo JText::_('Edit'); ?>">
                                        <img width="15" height="15" src="components/com_jsjobs/images/edit.png" />
                                    </a>
                                    <a class="js_listing_icon" href="index.php?option=com_jsjobs&c=department&view=department&layout=view_department&pd=<?php echo $department->aliasid; ?>&Itemid=<?php echo $this->Itemid; ?>" title="<?php echo JText::_('View'); ?>" >
                                        <img width="15" height="15" src="components/com_jsjobs/images/view.png" />
                                    </a>
                                    <a class="js_listing_icon" href="index.php?option=com_jsjobs&task=department.deletedepartment&pd=<?php echo $department->aliasid; ?>&Itemid=<?php echo $this->Itemid; ?>" title="<?php echo JText::_('Delete'); ?>">
                                        <img width="15" height="15" src="components/com_jsjobs/images/delete.png" />
                                    </a>
                                </div>
                        <?php } ?>
                        </div>
                <?php
            }
            ?>		
                    <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                    <input type="hidden" name="task" value="deletedepartment" />
                    <input type="hidden" name="department" value="deletedepartment" />
                    <input type="hidden" id="id" name="id" value="" />
                </form>
            </div>


            <form action="<?php echo JRoute::_('index.php?option=com_jsjobs&c=department&view=department&layout=mydepartments&Itemid=' . $this->Itemid); ?>" method="post">
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
        <?php } else { // no result found in this category 
                $this->jsjobsmessages->getAccessDeniedMsg('Could not find any matching results.', 'Could not find any matching results.', 0);
            }
    } else { // not allowed 
        switch ($this->mydepartment_allowed) {
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

