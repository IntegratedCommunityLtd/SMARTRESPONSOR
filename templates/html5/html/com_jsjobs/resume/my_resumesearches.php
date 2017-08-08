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
 * File Name:	views/employer/tmpl/myjobsearches.php
  ^
 * Description: template view for my job searches
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
        if ($this->myresumesearch_allowed == VALIDATE) {
            if ($this->jobsearches) {
                ?>
                <div id="js_main_wrapper">
                    <span class="js_controlpanel_section_title"><?php echo JText::_('Resume Save Searches'); ?></span>        
                    <form action="index.php" method="post" name="adminForm">
                        <?php
                        jimport('joomla.filter.output');
                        foreach ($this->jobsearches as $search) {
                            ?>
                            <div class="js_listing_wrapper">
                                <span class="js_coverletter_title"><?php echo $search->searchname; ?></span>
                                <div class="js_coverletter_button_area" >
                                    <span class="js_coverletter_created"><span class="js_coverletter_created_title"><?php echo JText::_('Created'); ?>&nbsp;:</span><?php echo JHtml::_('date', $search->created, $this->config['date_format']); ?></span>
                                    <a class="js_listing_icon" href="index.php?option=com_jsjobs&c=resume&view=resume&layout=viewresumesearch&rs=<?php echo $search->id; ?>&Itemid=<?php echo $this->Itemid; ?>">
                                        <img width="15" height="15" src="components/com_jsjobs/images/view.png" />
                                    </a>
                                    <a class="js_listing_icon" href="index.php?option=com_jsjobs&task=resumesearch.deleteresumesearch&rs=<?php echo $search->id; ?>&Itemid=<?php echo $this->Itemid; ?>">
                                        <img width="15" height="15" src="components/com_jsjobs/images/delete.png" />
                                    </a>
                                </div>
                            </div>
                        <?php } ?>		
                        <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                        <input type="hidden" name="task" value="deletejobsearch" />
                        <input type="hidden" name="c" value="jobsearch" />
                        <input type="hidden" id="id" name="id" value="" />
                        <input type="hidden" name="boxchecked" value="0" />
                    </form>
                </div>
                <form action="index.php?option=com_jsjobs&c=resume&view=resume&layout=my_resumesearches&Itemid=<?php echo $this->Itemid; ?>" method="post">
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
        } else { // not allowed job posting
        switch ($this->myresumesearch_allowed) {
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

