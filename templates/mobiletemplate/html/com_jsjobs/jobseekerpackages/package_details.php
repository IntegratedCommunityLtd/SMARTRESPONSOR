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
 * File Name:	views/jobseeker/tmpl/package_details.php
  ^
 * Description: template view for package details
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
        $printform = 1;
        if (isset($this->userrole))
            if (isset($this->userrole->rolefor) && $this->userrole->rolefor == 1) { // employer
                if ($this->config['employerview_js_controlpanel'] == 1)
                    $printform = true;
                else {
                    echo JText::_('You Are Not Allowed To View');
                    $printform = 0;
                }
            }
        if ($printform == 1) {
            if (isset($this->package)) {
                ?>
                <div id="js_main_wrapper">
                    <span class="js_controlpanel_section_title"><?php echo JText::_('Package Details'); ?></span>
                    <span class="js_job_title">
                        <?php
                        echo $this->package->title;
                        $curdate = date('Y-m-d H:i:s');
                        if (($this->package->discountstartdate <= $curdate) && ($this->package->discountenddate >= $curdate)) {
                            if ($this->package->discountmessage)
                                echo $this->package->discountmessage;
                        }
                        ?>
                    </span>
                    <div class="js_listing_wrapper">
                        <span class="stats_data_title"><?php echo JText::_('Resume Allowed'); ?></span>
                        <span class="stats_data_value"><?php if ($this->package->resumeallow == -1)
                echo JText::_('Unlimited');
            else
                echo $this->package->resumeallow;
            ?></span>
                        <span class="stats_data_title"><?php echo JText::_('Cover letter Allow'); ?></span>
                        <span class="stats_data_value"><?php if ($this->package->coverlettersallow == -1)
                echo JText::_('Unlimited');
            else
                echo $this->package->coverlettersallow;
            ?></span>
                        <span class="stats_data_title"><?php echo JText::_('Job Search'); ?></span>
                        <span class="stats_data_value"><?php if ($this->package->jobsearch == 1)
                echo JText::_('Yes');
            else
                echo JText::_('No');
            ?></span>
                        <span class="stats_data_title"><?php echo JText::_('Save Job Search'); ?></span>
                        <span class="stats_data_value"><?php if ($this->package->savejobsearch == 1)
                echo JText::_('Yes');
            else
                echo JText::_('No');
                        ?></span>
                        <span class="stats_data_title"><?php echo JText::_('Apply Jobs'); ?></span>
                        <span class="stats_data_value"><?php if ($this->package->applyjobs == -1)
                    echo JText::_('Unlimited');
                else
                    echo $this->package->applyjobs;
                        ?></span>
                        <span class="stats_data_title"><?php echo JText::_('Package Expire In Days'); ?></span>
                        <span class="stats_data_value"><?php echo $this->package->packageexpireindays; ?></span>
                        <span class="stats_data_title"><?php echo JText::_('Price'); ?></span>
                        <span class="stats_data_value">
                            <?php
                            if ($this->package->price != 0) {
                                $curdate = date('Y-m-d H:i:s');
                                if (($this->package->discountstartdate <= $curdate) && ($this->package->discountenddate >= $curdate)) {
                                    if ($this->package->discounttype == 2) {
                                        $discountamount = ($this->package->price * $this->package->discount) / 100;
                                        $discountamount = $this->package->price - $discountamount;
                                        echo $this->package->symbol . $discountamount . ' [ ' . $this->package->discount . '% ' . JText::_('Discount') . ' ]';
                                    } else {
                                        $discountamount = $this->package->price - $this->package->discount;
                                        echo $this->package->symbol . $discountamount . ' [ ' . JText::_('Discount') . ' : ' . $this->package->symbol . $this->package->discount . ' ]';
                                    }
                                } else
                                    echo $this->package->symbol . $this->package->price;
                            }else {
                                echo JText::_('Free');
                            }
                            ?>
                        </span>            
                        <span class="stats_data_title fullwidth"><?php echo JText::_('Description'); ?></span>
                        <span class="stats_data_value description"><?php echo $this->package->description; ?></span>
                        <div class="js_job_apply_button">
            <?php $link = 'index.php?option=com_jsjobs&c=jobseekerpackages&view=jobseekerpackages&layout=package_buynow&nav=22&gd=' . $this->package->id . '&Itemid=' . $this->Itemid; ?>
                            <a class="js_job_button" href="<?php echo $link ?>" class="pkgLink"><?php echo JText::_('Buy Now'); ?></a>
                        </div>
                    </div>
                </div>
            <?php
        }
        ?>	
        <?php
    }
}//ol
?>	

