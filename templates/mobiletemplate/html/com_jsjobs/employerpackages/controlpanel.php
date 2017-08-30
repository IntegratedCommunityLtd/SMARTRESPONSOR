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
 * File Name:	views/employer/tmpl/controlpanel.php
  ^
 * Description: template view for control panel
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
                <a class="js_menu_link <?php if ($lnk[2] == 'controlpanel') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
                <?php
            }
        }
        if (sizeof($this->employerlinks) != 0) {
            foreach ($this->employerlinks as $lnk) {
                ?>
                <a class="js_menu_link <?php if ($lnk[2] == 'controlpanel') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
                <?php
            }
        }
        ?>
    </div>
<?php
if ($this->config['offline'] == '1') {
    $this->jsjobsmessages->getSystemOfflineMsg($this->config);
} else {
        $userrole = $this->userrole;
        $config = $this->config;
        $emcontrolpanel = $this->emcontrolpanel;
        if (isset($userrole->rolefor)) {
            if ($userrole->rolefor == 1) // employer
                $allowed = true;
            else
                $allowed = false;
        }else {
            if ($config['visitorview_emp_conrolpanel'] == 1)
                $allowed = true;
            else
                $allowed = false;
        } // user not logined
        if ($allowed == true) {
            ?>
            <div id="js_main_wrapper">
                <span class="js_controlpanel_section_title"><?php echo JText::_('My Stuff'); ?></span>
                <?php
                $print = checkLinks('formcompany', $userrole, $config, $emcontrolpanel);
                if ($print) {
                    ?>
                    <a class="js_controlpanel_link" href="index.php?option=com_jsjobs&c=company&view=company&layout=formcompany&Itemid=<?php echo $this->Itemid; ?>">
                        <img class="js_controlpanel_link_image" src="components/com_jsjobs/images/addcompany.png" alt="New Company" />
                        <div class="js_controlpanel_link_text_wrapper">
                            <span class="js_controlpanel_link_title"><?php echo JText::_('New Company'); ?></span>
                        </div>
                    </a>
                    <?php
                }
                $print = checkLinks('mycompanies', $userrole, $config, $emcontrolpanel);
                if ($print) {
                    ?>
                    <a class="js_controlpanel_link" href="index.php?option=com_jsjobs&c=company&view=company&layout=mycompanies&Itemid=<?php echo $this->Itemid; ?>">
                        <img class="js_controlpanel_link_image" src="components/com_jsjobs/images/mycompanies.png" alt="My Companies" />
                        <div class="js_controlpanel_link_text_wrapper">
                            <span class="js_controlpanel_link_title"><?php echo JText::_('My Companies'); ?></span>
                        </div>
                    </a>
                    <?php
                }
                $print = checkLinks('formjob', $userrole, $config, $emcontrolpanel);
                if ($print) {
                    ?>
                    <a class="js_controlpanel_link" href="index.php?option=com_jsjobs&c=job&view=job&layout=formjob&Itemid=<?php echo $this->Itemid; ?>">
                        <img class="js_controlpanel_link_image" src="components/com_jsjobs/images/addjob.png" alt="New Job" />
                        <div class="js_controlpanel_link_text_wrapper">
                            <span class="js_controlpanel_link_title"><?php echo JText::_('New Job'); ?></span>
                        </div>
                    </a>
                    <?php
                }
                $print = checkLinks('myjobs', $userrole, $config, $emcontrolpanel);
                if ($print) {
                    ?>
                    <a class="js_controlpanel_link" href="index.php?option=com_jsjobs&c=job&view=job&layout=myjobs&Itemid=<?php echo $this->Itemid; ?>">
                        <img class="js_controlpanel_link_image" src="components/com_jsjobs/images/myjobs.png" alt="My Jobs" />
                        <div class="js_controlpanel_link_text_wrapper">
                            <span class="js_controlpanel_link_title"><?php echo JText::_('My Jobs'); ?></span>
                        </div>
                    </a>
                    <?php
                }
                $print = checkLinks('formdepartment', $userrole, $config, $emcontrolpanel);
                if ($print) {
                    ?>
                    <a class="js_controlpanel_link" href="index.php?option=com_jsjobs&c=department&view=department&layout=formdepartment&Itemid=<?php echo $this->Itemid; ?>">
                        <img class="js_controlpanel_link_image" src="components/com_jsjobs/images/adddepartment.png" alt="Form Department" />
                        <div class="js_controlpanel_link_text_wrapper">
                            <span class="js_controlpanel_link_title"><?php echo JText::_('New Department'); ?></span>
                        </div>
                    </a>
                    <?php
                }
                $print = checkLinks('mydepartment', $userrole, $config, $emcontrolpanel);
                if ($print) {
                    ?>
                    <a class="js_controlpanel_link" href="index.php?option=com_jsjobs&c=department&view=department&layout=mydepartments&Itemid=<?php echo $this->Itemid; ?>">
                        <img class="js_controlpanel_link_image" src="components/com_jsjobs/images/mydepartments.png" alt="My Department" />
                        <div class="js_controlpanel_link_text_wrapper">
                            <span class="js_controlpanel_link_title"><?php echo JText::_('My Departments'); ?></span>
                        </div>
                    </a>
                    <?php
                }
                if ($emcontrolpanel['emploginlogout'] == 1) {
                    if (isset($userrole->rolefor)) {//jobseeker
                        $link = "index.php?option=com_users&c=users&task=logout&Itemid=" . $this->Itemid;
                        $text = JText::_('Logout');
                        $icon = "logout.png";
                    } else {
                        $redirectUrl = JRoute::_('index.php?option=com_jsjobs&c=jobseeker&view=jobseeker&layout=controlpanel&Itemid=' . $this->Itemid);
                        $redirectUrl = '&amp;return=' . $this->getJSModel('common')->b64ForEncode($redirectUrl);
                        $link = 'index.php?option=com_users&view=login' . $redirectUrl;
                        $text = JText::_('Login');
                        $icon = "login.png";
                    }
                    ?>
                    <a class="js_controlpanel_link" href="<?php echo $link; ?>">
                        <img class="js_controlpanel_link_image" src="components/com_jsjobs/images/<?php echo $icon; ?>" alt="Messages" />
                        <div class="js_controlpanel_link_text_wrapper">
                            <span class="js_controlpanel_link_title"><?php echo $text; ?></span>
                        </div>
                    </a>
                <?php } ?>
                <span class="js_controlpanel_section_title"><?php echo JText::_('Resumes'); ?></span>        
                <?php
                $print = checkLinks('resumesearch', $userrole, $config, $emcontrolpanel);
                if ($print) {
                    ?>
                    <a class="js_controlpanel_link" href="index.php?option=com_jsjobs&c=resume&view=resume&layout=resumesearch&Itemid=<?php echo $this->Itemid; ?>">
                        <img class="js_controlpanel_link_image" src="components/com_jsjobs/images/resumesearch.png" alt="Search Resume" />
                        <div class="js_controlpanel_link_text_wrapper">
                            <span class="js_controlpanel_link_title"><?php echo JText::_('Search Resume'); ?></span>
                        </div>
                    </a>
                    <?php
                }
                $print = checkLinks('resumesearch', $userrole, $config, $emcontrolpanel);
                if ($print) {
                    ?>
                    <a class="js_controlpanel_link" href="index.php?option=com_jsjobs&c=resume&view=resume&layout=my_resumesearches&Itemid=<?php echo $this->Itemid; ?>">
                        <img class="js_controlpanel_link_image" src="components/com_jsjobs/images/resumesavesearch.png" alt="Search Resume" />
                        <div class="js_controlpanel_link_text_wrapper">
                            <span class="js_controlpanel_link_title"><?php echo JText::_('Resume Save Searches'); ?></span>
                        </div>
                    </a>
                    <?php
                }
                $print = checkLinks('resumesearch', $userrole, $config, $emcontrolpanel);
                if ($print) {
                    ?>
                    <a class="js_controlpanel_link" href="index.php?option=com_jsjobs&c=resume&view=resume&layout=resumebycategory&Itemid=<?php echo $this->Itemid; ?>">
                        <img class="js_controlpanel_link_image" src="components/com_jsjobs/images/resumebycat.png" alt=" Resume By Category" />
                        <div class="js_controlpanel_link_text_wrapper">
                            <span class="js_controlpanel_link_title"><?php echo JText::_('Resume By Category'); ?></span>
                        </div>
                    </a>
                <?php } ?>
                <span class="js_controlpanel_section_title"><?php echo JText::_('Statistics'); ?></span>        
                <?php
                $print = checkLinks('packages', $userrole, $config, $emcontrolpanel);
                if ($print) {
                    ?>
                    <a class="js_controlpanel_link" href="index.php?option=com_jsjobs&c=employerpackages&view=employerpackages&layout=packages&Itemid=<?php echo $this->Itemid; ?>">
                        <img class="js_controlpanel_link_image" src="components/com_jsjobs/images/packages.png" alt=" Packages" />
                        <div class="js_controlpanel_link_text_wrapper">
                            <span class="js_controlpanel_link_title"><?php echo JText::_('Packages'); ?></span>
                        </div>
                    </a>
                    <?php
                }
                $print = checkLinks('purchasehistory', $userrole, $config, $emcontrolpanel);
                if ($print) {
                    ?>
                    <a class="js_controlpanel_link" href="index.php?option=com_jsjobs&c=purchasehistory&view=purchasehistory&layout=employerpurchasehistory&Itemid=<?php echo $this->Itemid; ?>">
                        <img class="js_controlpanel_link_image" src="components/com_jsjobs/images/purchase_history.png" alt=" Employer Purchase History" />
                        <div class="js_controlpanel_link_text_wrapper">
                            <span class="js_controlpanel_link_title"><?php echo JText::_('Purchase History'); ?></span>
                        </div>
                    </a>
                    <?php
                }
                $print = checkLinks('my_stats', $userrole, $config, $emcontrolpanel);
                if ($print) {
                    ?>
                    <a class="js_controlpanel_link" href="index.php?option=com_jsjobs&c=employer&view=employer&layout=my_stats&Itemid=<?php echo $this->Itemid; ?>">
                        <img class="js_controlpanel_link_image" src="components/com_jsjobs/images/mystats.png" alt="My Stats" />
                        <div class="js_controlpanel_link_text_wrapper">
                            <span class="js_controlpanel_link_title"><?php echo JText::_('My Stats'); ?></span>
                        </div>
                    </a>
                <?php } ?>
            </div>
            <?php
            if ($emcontrolpanel['empexpire_package_message'] == 1) {
                $message = '';
                if (!empty($this->packagedetail[0]->packageexpiredays)) {
                    $days = $this->packagedetail[0]->packageexpiredays - $this->packagedetail[0]->packageexpireindays;
                    if ($days == 1)
                        $days = $days . ' ' . JText::_('Day');
                    else
                        $days = $days . ' ' . JText::_('Days');
                    $message = "<strong><font color='red'>" . JText::_('Your Package') . ' &quot;' . $this->packagedetail[0]->packagetitle . '&quot; ' . JText::_('Has Expired') . ' ' . $days . ' ' . JText::_('Ago') . " <a href='index.php?option=com_jsjobs&c=jobseekerpackages&view=jobseekerpackages&layout=packages&Itemid=$this->Itemid'>" . JText::_('Employer Packages') . "</a></font></strong>";
                }
                if ($message != '') {
                    ?>
                    <div id="errormessage" class="errormessage">
                        <div id="message"><?php echo $message; ?></div>
                    </div>
                <?php
                }
            }
            ?>
        <?php } else { // not allowed job posting 
            $this->jsjobsmessages->getAccessDeniedMsg('You are not allowed', 'You are not allow to view this page');
        }
    }//ol
    ?>

    <?php

    function checkLinks($name, $userrole, $config, $emcontrolpanel) {
        $print = false;
        if (isset($userrole->rolefor)) {
            if ($userrole->rolefor == 1) {
                if ($emcontrolpanel[$name] == 1)
                    $print = true;
            }
        }else {
            if ($name == 'empmessages')
                $name = 'vis_emmessages';
            elseif ($name == 'empresume_rss')
                $name = 'vis_resume_rss';
            else
                $name = 'vis_em' . $name;

            if ($config[$name] == 1)
                $print = true;
        }
        return $print;
    }
    ?>
