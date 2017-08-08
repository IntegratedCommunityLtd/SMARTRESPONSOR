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
 * File Name:	views/employer/tmpl/viewjob.php
  ^
 * Description: template view for a company
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
    if (isset($this->company)) { ?>
        <div id="js_main_wrapper">
            <span class="js_controlpanel_section_title"><?php echo JText::_('Company Information'); ?></span>
            <?php if (isset($this->fieldsordering['name']) && $this->fieldsordering['name'] == 1 && $this->config['comp_name'] == 1) { ?>
                <span class="js_job_title">
                    <?php echo $this->company->name; ?>
                </span><br/><br/>
            <?php } ?>
            <?php if (isset($this->fieldsordering['logo']) && $this->fieldsordering['logo'] == 1) { ?>
                <div class="js_job_company_logo">
                    <?php
                    $logourl = 'components/com_jsjobs/images/blank_logo.png';
                    if (!empty($this->company->companylogo)) {
                        if ($this->isjobsharing) {
                            $logourl = $this->company->companylogo;
                        } else {
                            $logourl = $this->config['data_directory'] . '/data/employer/comp_' . $this->company->id . '/logo/' . $this->company->companylogo;
                        }
                    }
                    ?>
                    <img class="js_job_company_logo" src="<?php echo $logourl; ?>" />
                </div>
            <?php } ?>
            <div class="js_job_company_data">
                <?php
                if (isset($this->fieldsordering['url']) && $this->fieldsordering['url'] == 1 && $this->config['comp_show_url'] == 1) {
                    if ($this->company->url) {
                        ?>
                        <div class="js_job_data_wrapper">
                            <span class="js_job_data_title"><?php echo JText::_('Website'); ?></span>
                            <span class="js_job_data_value">
                                <a class="js_job_company_anchor" target="_blank" href="<?php echo $this->company->url; ?>">
                                    <?php echo $this->company->url; ?>
                                </a>
                            </span>
                        </div>
                        <?php
                    }
                }
                if (isset($this->fieldsordering['contactname']) && $this->fieldsordering['contactname'] == 1) {
                    ?>
                    <div class="js_job_data_wrapper">
                        <span class="js_job_data_title"><?php echo JText::_('Contact Name'); ?></span>
                        <span class="js_job_data_value">
                            <?php echo $this->company->contactname; ?>
                        </span>
                    </div>
                    <?php
                }
                if (isset($this->fieldsordering['contactemail']) && $this->fieldsordering['contactemail'] == 1 && $this->config['comp_email_address']) {
                    ?>
                    <div class="js_job_data_wrapper">
                        <span class="js_job_data_title"><?php echo JText::_('Contact Email'); ?></span>
                        <span class="js_job_data_value">
                            <?php echo $this->company->contactemail; ?>
                        </span>
                    </div>
                    <?php
                }
                if (isset($this->fieldsordering['contactphone']) && $this->fieldsordering['contactphone'] == 1) {
                    ?>
                    <div class="js_job_data_wrapper">
                        <span class="js_job_data_title"><?php echo JText::_('Contact Phone'); ?></span>
                        <span class="js_job_data_value">
                            <?php echo $this->company->contactphone; ?>
                        </span>
                    </div>
                    <?php
                }
                    ?>            
                    <div class="js_job_data_wrapper">
                        <span class="js_job_data_title"><?php echo JText::_('Since'); ?></span>
                        <span class="js_job_data_value"><?php echo JHtml::_('date', $this->company->since, $this->config['date_format']); ?></span>
                    </div>
            </div>
            <?php if (isset($this->fieldsordering['address1']) && $this->fieldsordering['address1'] == 1) { ?>
                <div class="js_job_data_wrapper">
                    <span class="js_job_data_title"><?php echo JText::_('Address 1'); ?></span>
                    <span class="js_job_data_value"><?php echo $this->company->address1; ?></span>
                </div>
                <?php
            }
            if (isset($this->fieldsordering['address2']) && $this->fieldsordering['address2'] == 1) {
                ?>
                <div class="js_job_data_wrapper">
                    <span class="js_job_data_title"><?php echo JText::_('Address 2'); ?></span>
                    <span class="js_job_data_value"><?php echo $this->company->address2; ?></span>
                </div>
                <?php
            }
            if (isset($this->fieldsordering['city']) && $this->fieldsordering['city'] == 1 && $this->config['comp_city'] == 1) {
                ?>
                <div class="js_job_data_wrapper">
                    <span class="js_job_data_title"><?php echo JText::_('Location'); ?>:&nbsp;</span>
                    <span class="js_job_data_value"><?php if ($this->company->multicity != '') echo $this->company->multicity; ?></span>
                </div>
                <?php
            }
            $i = 0;
            foreach ($this->fieldsordering as $fieldkey => $value) {
                //echo '<br> uf'.$field->field;
                switch ($fieldkey) {
                    case "jobcategory":
                        ?>
                        <?php if ($value == 1) { ?>
                            <div class="js_job_data_wrapper">
                                <span class="js_job_data_title"><?php echo JText::_('Categories'); ?></span>
                                <span class="js_job_data_value"><?php echo $this->company->cat_title; ?></span>
                            </div>
                        <?php } ?>
                        <?php
                        break;
                    case "contactphone":
                        ?>
                        <?php if ($value == 1) { ?>
                            <div class="js_job_data_wrapper">
                                <span class="js_job_data_title"><?php echo JText::_('Contact Phone'); ?></span>
                                <span class="js_job_data_value"><?php echo $this->company->contactphone; ?></span>
                            </div>
                        <?php } ?>
                        <?php
                        break;
                    case "contactfax":
                        ?>
                        <?php if ($value == 1) { ?>
                            <div class="js_job_data_wrapper">
                                <span class="js_job_data_title"><?php echo JText::_('Contact Fax'); ?></span>
                                <span class="js_job_data_value"><?php echo $this->company->companyfax; ?></span>
                            </div>
                        <?php } ?>
                        <?php
                        break;
                    case "since":
                        ?>
                        <?php if ($value == 1) { ?>
                            <div class="js_job_data_wrapper">
                                <span class="js_job_data_title"><?php echo JText::_('Since'); ?></span>
                                <span class="js_job_data_value"><?php echo JHtml::_('date', $this->company->since, $this->config['date_format']); ?></span>
                            </div>
                        <?php } ?>
                        <?php
                        break;
                    case "companysize":
                        ?>
                        <?php if ($value == 1) { ?>
                            <div class="js_job_data_wrapper">
                                <span class="js_job_data_title"><?php echo JText::_('Company Size'); ?></span>
                                <span class="js_job_data_value"><?php echo $this->company->companysize; ?></span>
                            </div>
                        <?php } ?>
                        <?php
                        break;
                    case "income":
                        ?>
                        <?php if ($value == 1) { ?>
                            <div class="js_job_data_wrapper">
                                <span class="js_job_data_title"><?php echo JText::_('Income'); ?></span>
                                <span class="js_job_data_value"><?php echo $this->company->income; ?></span>
                            </div>
                        <?php } ?>
                        <?php
                        break;
                    case "description":
                        ?>
                        <?php if ($value == 1) { ?>
                            <span class="js_controlpanel_section_title"><?php echo JText::_('Description'); ?></span>
                            <div class="js_job_full_width_data"><?php echo $this->company->description; ?></div>
                        <?php } ?>
                        <?php
                        break;
                    default:
                        if ($value == 1) {
                            
                        }
                }
            }
            if ($this->isjobsharing) {
                if (isset($this->userfields)) {
                    foreach ($this->userfields as $ufield) {
                        echo '<div class="js_job_data_wrapper">';
                        echo '<span class="js_job_data_title">' . $ufield['field_title'] . '</span>';
                        echo '<span class="js_job_data_value">' . $ufield['field_value'] . '</span>';
                        echo '</div>';
                    }
                }
            } else {
                foreach ($this->userfields as $ufield) {
                    if (isset($this->fieldsordering[$ufield[0]->id]) && $this->fieldsordering[$ufield[0]->id] == 1) {
                        $userfield = $ufield[0];
                        $i++;
                        echo '<div class="js_job_data_wrapper">';
                        echo '<span class="js_job_data_title">' . $userfield->title . '</span>';
                        if ($userfield->type != "select") {
                            if (isset($ufield[1])) {
                                $fvalue = $ufield[1]->data;
                                $userdataid = $ufield[1]->id;
                            } else {
                                $fvalue = "";
                                $userdataid = "";
                            }
                        } elseif ($userfield->type == "select") {
                            if (isset($ufield[2])) {
                                $fvalue = $ufield[2]->fieldtitle;
                                $userdataid = $ufield[1]->id;
                            } else {
                                $fvalue = "";
                                $userdataid = "";
                            }
                        }
                        echo '<span class="js_job_data_value">' . $fvalue . '</span>';
                        echo '</div>';
                    }
                }
            }
            ?>
            <div class="js_job_apply_button">
                <?php if ($this->nav != '31' && $this->nav != '41') { ?>
                    <a class="js_job_button" href="index.php?option=com_jsjobs&c=company&view=company&layout=company_jobs&cd=<?php echo $this->company->aliasid; ?>&Itemid=<?php echo $this->Itemid; ?>" ><?php echo JText::_('View All Jobs'); ?></a>
                <?php } ?>	
            </div>
        </div>
    <?php } else { 
        $this->jsjobsmessages->getAccessDeniedMsg('Could not find any matching results.', 'Could not find any matching results.', 0);
        }
}//ol
?>	

<?php

function isURL($url = NULL) {
    if ($url == NULL)
        return false;
    $protocol = '(http://|https://)';
    if (ereg($protocol, $url) == true)
        return true;
    else
        return false;
}
?>
