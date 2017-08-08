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
 * File Name:	views/employer/tmpl/mycompanies.php
  ^
 * Description: template view for my companies
  ^
 * History:		NONE
  ^
 */
defined('_JEXEC') or die('Restricted access');
?>
<script language=Javascript>
    function confirmdeletecompany() {
        return confirm("<?php echo JText::_('Are you sure to delete the company').'!'; ?>");
    }
</script>
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
    if ($this->mycompany_allowed == VALIDATE) {
        if ($this->companies) {
            ?>
            <div id="js_main_wrapper">
                <span class="js_controlpanel_section_title"><?php echo JText::_('My Companies'); ?></span>
                <form action="index.php" method="post" name="adminForm">
                    <?php
                    foreach ($this->companies AS $company) { ?>
                        <div class="js_job_main_wrapper">
                            <?php if (isset($this->fieldsordering['logo']) && $this->fieldsordering['logo'] == 1) { ?>
                                <div class="js_job_image_area">
                                    <div class="js_job_image_wrapper mycompany">
                                        <?php
                                        if (!empty($company->logofilename)) {
                                            $imgsrc = $this->config['data_directory'] . '/data/employer/comp_' . $company->id . '/logo/' . $company->logofilename;
                                        } else {
                                            $imgsrc = 'components/com_jsjobs/images/blank_logo.png';
                                        }
                                        ?>
                                        <img class="js_job_image" src="<?php echo $imgsrc; ?>" />
                                    </div>
                                </div>
                            <?php } ?>            
                            <div class="js_job_data_area">
                                <div class="js_job_data_1 mycompany">
                                    <?php if (isset($this->fieldsordering['name']) && $this->fieldsordering['name'] == 1 && $this->config['comp_name'] == 1) { ?>
                                        <span class="js_job_title"><?php echo $company->name; ?></span>
                                    <?php } ?>
                                    <span class="js_job_posted"><?php echo JText::_('Created') . ': ' . JHtml::_('date', $company->created, $this->config['date_format']); ?></span>
                                </div>
                                <div class="js_job_data_2">
                                    <?php if (isset($this->fieldsordering['jobcategory']) && $this->fieldsordering['jobcategory'] == 1) { ?>
                                        <div class='js_job_data_2_wrapper'>
                                            <span class="js_job_data_2_title"><?php echo JText::_('Category') . ":"; ?></span>
                                            <span class="js_job_data_2_value"><?php echo $company->cat_title; ?></span>
                                        </div>
                                    <?php } ?>                    
                                    <div class="js_job_data_2_wrapper">
                                        <span class="js_job_data_2_title"><?php echo JText::_('Status') . ":"; ?></span>
                                        <span class="js_job_data_2_value"><?php
                                            if ($company->status == 1)
                                                echo '<font color="green">' . JText::_('Approved') . '</font>';
                                            elseif ($company->status == 0) {
                                                echo '<span class="jobstatusmsg"> ' . JText::_('Pending') . '</span>';
                                            } elseif ($company->status == -1)
                                                echo '<font color="red"> ' . JText::_('Rejected') . '</font>';
                                            ?></span>
                                    </div>
                                </div>
                                        <?php if (isset($this->fieldsordering['city']) && $this->fieldsordering['city'] == 1 && $this->config['comp_city'] == 1) { ?>
                                    <div class="js_job_data_3 mycompany">
                                        <span class="js_job_data_location_title"><?php echo JText::_('Location'); ?>:&nbsp;</span>
                                            <?php if (isset($company->city) AND ! empty($company->city)) { ?>
                                            <span class="js_job_data_location_value">
                                                <?php
                                                if (strlen($company->multicity) > 35) {
                                                    echo JText::_('Multi City') . $company->multicity;
                                                } else {
                                                    echo $company->cityname;
                                                    switch ($this->config['defaultaddressdisplaytype']) {
                                                        case 'csc':
                                                            if(!empty($company->statename))
                                                                echo ', '.$company->statename;
                                                            echo ', '.$company->countryname;
                                                        break;
                                                        case 'cs':
                                                            if(!empty($company->statename))
                                                                echo ', '.$company->statename;
                                                        break;
                                                        case 'cc':
                                                            echo ', '.$company->countryname;
                                                        break;
                                                    }
                                                }
                                                ?>
                                            </span>
                    <?php }
                    ?>
                                    </div>
                                    <?php } ?>
                                <div class="js_job_data_4 mycompany">
                                    <a class="company_icon" href="index.php?option=com_jsjobs&c=company&view=company&layout=formcompany&cd=<?php echo $company->aliasid . "&Itemid=" . $this->Itemid; ?>"  title="<?php echo JText::_('Edit'); ?>">
                                        <img class="icon" width="15" height="15" src="components/com_jsjobs/images/edit.png" />
                                    </a>                    
                <?php
                $companyid = ($this->isjobsharing != "") ? $company->saliasid : $company->aliasid;
                $com_view_link = "index.php?option=com_jsjobs&c=company&view=company&layout=view_company&nav=31&cd=" . $companyid . "&Itemid=" . $this->Itemid;
                ?>
                                    <a class="company_icon" href="<?php echo $com_view_link; ?>"  title="<?php echo JText::_('View'); ?>">
                                        <img class="icon"width="15" height="15" src="components/com_jsjobs/images/view.png" />
                                    </a>
                                    <a class="company_icon" href="index.php?option=com_jsjobs&task=company.deletecompany&cd=<?php echo $company->aliasid; ?>&Itemid=<?php echo $this->Itemid; ?>" onclick=" return confirmdeletecompany();"  title="<?php echo JText::_('Delete'); ?>">
                                        <img class="icon"width="15" height="15" src="components/com_jsjobs/images/delete.png" />
                                    </a>
                                    <?php if ($company->status == 1) { ?> 
                                        <?php $link = JRoute::_('index.php?option=com_jsjobs&task=company.addtogoldcompany&cd=' . $company->aliasid . '&Itemid=' . $this->Itemid); ?>
                                    <?php } ?>
                                    <?php if ($company->status == 1) { ?> 
                                        <?php $link = JRoute::_('index.php?option=com_jsjobs&task=company.addtofeaturedcompany&cd=' . $company->aliasid . '&Itemid=' . $this->Itemid); ?>
                            <?php } ?>
                                </div>
                            </div>
                        </div>    
                <?php
            }
            ?>
                    <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                    <input type="hidden" name="task" value="deletecompany" />
                    <input type="hidden" name="c" value="company" />
                    <input type="hidden" id="id" name="id" value="" />
                </form>
            </div>

            <form action="<?php echo JRoute::_('index.php?option=com_jsjobs&c=company&view=company&layout=mycompanies&Itemid=' . $this->Itemid); ?>" method="post">
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
    } else {
        switch ($this->mycompany_allowed) {
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
}  //ol
?>	

<?php
$document = JFactory::getDocument();
$document->addScript('components/com_jsjobs/js/canvas_script.js');
?>