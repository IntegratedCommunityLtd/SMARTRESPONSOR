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
 * File Name:	views/employer/tmpl/packages.php
  ^
 * Description: template view packages
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
        if ($this->mypurchasehistory_allowed == VALIDATE) { // employer 
            if (!empty($this->packages)) {
                ?>
                <div id="js_main_wrapper">
                    <span class="js_controlpanel_section_title"><?php echo JText::_('Purchase History'); ?></span>
                    <?php
                    if (isset($this->packages)) {
                        foreach ($this->packages as $package) {
                            ?>
                            <span class="js_job_title">
                                <a class="anchor" href="index.php?option=com_jsjobs&c=employerpackages&view=employerpackages&layout=package_details&gd=<?php echo $package->id; ?>&Itemid=<?php echo $this->Itemid; ?>"><?php echo $package->title; ?></a>
                            </span>
                            <div class="js_listing_wrapper">
                                <span class="stats_data_title"><?php echo JText::_('Companies Allowed'); ?></span>
                                <span class="stats_data_value"><?php if ($package->companiesallow == -1)
                                echo JText::_('Unlimited');
                            else
                                echo $package->companiesallow;
                            ?></span>
                                <span class="stats_data_title"><?php echo JText::_('Jobs Allowed'); ?></span>
                                <span class="stats_data_value"><?php if ($package->jobsallow == -1)
                        echo JText::_('Unlimited');
                    else
                        echo $package->jobsallow;
                    ?></span>
                                <span class="stats_data_title"><?php echo JText::_('Package Expire In Days'); ?></span>
                                <span class="stats_data_value"><?php echo $package->packageexpireindays; ?></span>
                                <span class="stats_data_title"><?php echo JText::_('Payment'); ?></span>
                                <span class="stats_data_value"><?php if ($package->transactionverified == 1)
                        echo JText::_('Verified');
                    else
                        echo '<strong>' . JText::_('Not Verified') . '</strong>';
                    ?></span>
                                <span class="stats_data_title last-child"><?php echo JText::_('Buy Date'); ?></span>
                                <span class="stats_data_value last-child"><?php echo date($this->config['date_format'], strtotime($package->created)); ?></span>
                            </div>

                                    <?php
                                }
                            }
                            ?>		
                </div>
                <form action="<?php echo JRoute::_('index.php?option=com_jsjobs&c=purchasehistory&view=purchasehistory&layout=employerpurchasehistory&Itemid=' . $this->Itemid); ?>" method="post">
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
                </form>	<?php }else { // no result found in this category 
                                $this->jsjobsmessages->getAccessDeniedMsg('Could not find any matching results.', 'Could not find any matching results.', 0);
                            }
    } else { // not allowed job posting
        switch ($this->mypurchasehistory_allowed) {
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

