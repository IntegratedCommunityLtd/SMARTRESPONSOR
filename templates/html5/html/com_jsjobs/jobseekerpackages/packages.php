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
 * File Name:	views/jobseeker/tmpl/packages.php
  ^
 * Description: template view for packages
  ^
 * History:		NONE
  ^
 */
defined('_JEXEC') or die('Restricted access');
$link = 'index.php?option=com_jsjobs&c=jobseekerpackages&view=jobseekerpackages&layout=packages&Itemid=' . $this->Itemid;
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
        $printform = 1;
        if (isset($this->userrole))
            if (isset($this->userrole->rolefor) && $this->userrole->rolefor == 1) { // employer
                if ($this->config['employerview_js_controlpanel'] == 1)
                    $printform = true;
                else {
                    $this->jsjobsmessages->getAccessDeniedMsg('You are not allowed', 'You are not allow to view this page');
                    $printform = 0;
                }
            }

        if ($this->pagination == '0') {
            $this->jsjobsmessages->getAccessDeniedMsg('Could not find any matching results.','Could not find any matching results.');
            $printform = 0;
        }
        if ($printform == 1) {
            ?>
            <div id="js_main_wrapper">
                <span class="js_controlpanel_section_title"><?php echo JText::_('Packages'); ?></span>
                <?php
                if (isset($this->packages)) {
                    foreach ($this->packages as $package) {
                        ?>
                        <span class="js_job_title">
                            <?php
                            echo $package->title;
                            $curdate = date('Y-m-d H:i:s');
                            if (($package->discountstartdate <= $curdate) && ($package->discountenddate >= $curdate)) {
                                if ($package->discountmessage)
                                    echo $package->discountmessage;
                            }
                            ?>
                        </span>
                        <div class="js_listing_wrapper">
                            <span class="stats_data_title"><?php echo JText::_('Resume Allowed'); ?></span>
                            <span class="stats_data_value"><?php if ($package->resumeallow == -1)
                    echo JText::_('Unlimited');
                else
                    echo $package->resumeallow;
                ?></span>
                            <span class="stats_data_title"><?php echo JText::_('Cover letter Allow'); ?></span>
                            <span class="stats_data_value"><?php if ($package->coverlettersallow == -1)
                    echo JText::_('Unlimited');
                else
                    echo $package->coverlettersallow;
                ?></span>
                            <span class="stats_data_title"><?php echo JText::_('Job Search'); ?></span>
                            <span class="stats_data_value"><?php if ($package->jobsearch == 1)
                    echo JText::_('Yes');
                else
                    echo JText::_('No');
                ?></span>
                            <span class="stats_data_title"><?php echo JText::_('Save Job Search'); ?></span>
                            <span class="stats_data_value"><?php if ($package->savejobsearch == 1)
                    echo JText::_('Yes');
                else
                    echo JText::_('No');
                            ?></span>
                            <span class="stats_data_title"><?php echo JText::_('Apply Jobs'); ?></span>
                            <span class="stats_data_value"><?php if ($package->applyjobs == -1)
                    echo JText::_('Unlimited');
                else
                    echo $package->applyjobs;
                            ?></span>
                            <span class="stats_data_title"><?php echo JText::_('Package Expire In Days'); ?></span>
                            <span class="stats_data_value"><?php echo $package->packageexpireindays; ?></span>
                            <span class="stats_data_title"><?php echo JText::_('Price'); ?></span>
                            <span class="stats_data_value">
                                <?php
                                if ($package->price != 0) {
                                    $curdate = date('Y-m-d H:i:s');
                                    if (($package->discountstartdate <= $curdate) && ($package->discountenddate >= $curdate)) {
                                        if ($package->discounttype == 2) {
                                            $discountamount = ($package->price * $package->discount) / 100;
                                            $discountamount = $package->price - $discountamount;
                                            echo $package->symbol . $discountamount . ' [ ' . $package->discount . '% ' . JText::_('Discount') . ' ]';
                                        } elseif ($package->discounttype == 1) {
                                            $discountamount = $package->price - $package->discount;
                                            echo $package->symbol . $discountamount . ' [ ' . JText::_('Discount') . ' : ' . $package->symbol . $package->discount . ' ]';
                                        }
                                    } else
                                        echo $package->symbol . $package->price;
                                }else {
                                    echo JText::_('Free');
                                }
                                ?>
                            </span>
                            <div class="js_job_apply_button">
                                <?php $link = 'index.php?option=com_jsjobs&c=jobseekerpackages&view=jobseekerpackages&layout=package_details&gd=' . $package->id . '&Itemid=' . $this->Itemid; ?>
                                <a class="js_job_button" href="<?php echo $link ?>" class="pageLink"><strong><?php echo JText::_('View'); ?></strong></a>
                                <?php $link = 'index.php?option=com_jsjobs&c=jobseekerpackages&view=jobseekerpackages&layout=package_buynow&nav=21&gd=' . $package->id . '&Itemid=' . $this->Itemid; ?>
                                <a class="js_job_button" href="<?php echo $link ?>" class="pageLink"><strong><?php echo JText::_('Buy Now'); ?></strong></a>
                            </div>
                        </div>

                <?php
            }
        }
        ?>		
            </div>
            <form action="<?php echo JRoute::_('index.php?option=com_jsjobs&c=jobseekerpackages&view=jobseekerpackages&layout=packages&Itemid=' . $this->Itemid); ?>" method="post">
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
            </form>	<?php
    }
}//ol
?>	

