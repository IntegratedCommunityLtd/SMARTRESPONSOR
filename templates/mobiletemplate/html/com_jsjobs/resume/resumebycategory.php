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
 * File Name:	views/jobseeker/tmpl/jobcat.php
  ^
 * Description: template view for job categories 
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
        if ($this->canview == VALIDATE) {
            ?>

            <div id="js_main_wrapper">
                <span class="js_controlpanel_section_title"><?php echo JText::_('Resume By Category'); ?></span>
                <?php
                $noofcols = $this->config['categories_colsperrow'];
                $colwidth = round(100 / $noofcols);
                if (isset($this->categories)) {
                    foreach ($this->categories as $category) {
                        $lnks = 'index.php?option=com_jsjobs&c=resume&view=resume&layout=resume_bycategory&cat=' . $category->aliasid . '&Itemid=' . $this->Itemid;
                        $lnks = JRoute::_($lnks);
                        ?>
                        <span class="js_column_layout" style="width:<?php echo $colwidth - 2; ?>%;" ><a href="<?php echo $lnks; ?>" ><?php echo $category->cattitle; ?> (<?php echo $category->total; ?>)</a></span>
                        <?php
                    }
                }
                ?>		
            </div>
            <?php
        } else { // not allowed job posting 
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
                $this->jsjobsmessages->getPackageExpireMsg('Resume search is not allowed in package', 'Resume search is not allow in your package, please get new package', $link);
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

