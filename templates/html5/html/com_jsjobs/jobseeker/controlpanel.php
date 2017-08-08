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
 * File Name:	views/jobseeker/tmpl/controlpanel.php
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
        $jscontrolpanel = $this->jscontrolpanel;
        if (isset($userrole->rolefor)) {
            if ($userrole->rolefor != '') {
                if ($userrole->rolefor == 2) // job seeker
                    $allowed = true;
                elseif ($userrole->rolefor == 1) { // employer
                    if ($config['employerview_js_controlpanel'] == 1)
                        $allowed = true;
                    else
                        $allowed = false;
                }
            }else {
                $allowed = true;
            }
        } else
            $allowed = true; // user not logined
        if ($allowed == true) {
            $message = '';
            if ($jscontrolpanel['jsexpire_package_message'] == 1) {
                if (!empty($this->packagedetail[0]->packageexpiredays)) {
                    $days = $this->packagedetail[0]->packageexpiredays - $this->packagedetail[0]->packageexpireindays;
                    if ($days == 1)
                        $days = $days . ' ' . JText::_('Day');
                    else
                        $days = $days . ' ' . JText::_('Days');
                    $message = "<strong><font color='red'>" . JText::_('Your Package') . ' &quot;' . $this->packagedetail[0]->packagetitle . '&quot; ' . JText::_('Has Expired') . ' ' . $days . ' ' . JText::_('Ago') . " <a href='index.php?option=com_jsjobs&c=jobseekerpackages&view=jobseekerpackages&layout=packages&Itemid=$this->Itemid'>" . JText::_('Jobseeker Packages') . "</a></font></strong>";
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
            <div id="js_main_wrapper">
                <span class="js_controlpanel_section_title"><?php echo JText::_('My Stuff'); ?></span>
                <?php
                $print = checkLinks('formresume', $userrole, $config, $jscontrolpanel);
                if ($print) {
                    ?>
                    <a class="js_controlpanel_link" href="index.php?option=com_jsjobs&c=resume&view=resume&layout=formresume&Itemid=<?php echo $this->Itemid; ?>">
                        <img class="js_controlpanel_link_image" src="components/com_jsjobs/images/addresume.png" alt="<?php echo JText::_('Add Resume'); ?>" />
                        <div class="js_controlpanel_link_text_wrapper">
                            <span class="js_controlpanel_link_title"><?php echo JText::_('Add Resume'); ?></span>
                        </div>
                    </a>
                    <?php
                }
                $print = checkLinks('myresumes', $userrole, $config, $jscontrolpanel);
                if ($print) {
                    ?>
                    <a class="js_controlpanel_link" href="index.php?option=com_jsjobs&c=jobapply&view=resume&layout=myresumes&Itemid=<?php echo $this->Itemid; ?>">
                        <img class="js_controlpanel_link_image" src="components/com_jsjobs/images/myresumes.png" alt="<?php echo JText::_('My Resumes'); ?>" />
                        <div class="js_controlpanel_link_text_wrapper">
                            <span class="js_controlpanel_link_title"><?php echo JText::_('My Resumes'); ?></span>
                        </div>
                    </a>
                    <?php
                }
                $print = checkLinks('formcoverletter', $userrole, $config, $jscontrolpanel);
                if ($print) {
                    ?>
                    <a class="js_controlpanel_link" href="index.php?option=com_jsjobs&c=coverletter&view=coverletter&layout=formcoverletter&Itemid=<?php echo $this->Itemid; ?>">
                        <img class="js_controlpanel_link_image" src="components/com_jsjobs/images/addcoverletter.png" alt="<?php echo JText::_('Add Cover Letter'); ?>" />
                        <div class="js_controlpanel_link_text_wrapper">
                            <span class="js_controlpanel_link_title"><?php echo JText::_('Add Cover Letter'); ?></span>
                        </div>
                    </a>
                    <?php
                }
                $print = checkLinks('mycoverletters', $userrole, $config, $jscontrolpanel);
                if ($print) {
                    ?>
                    <a class="js_controlpanel_link" href="index.php?option=com_jsjobs&c=coverletter&view=coverletter&layout=mycoverletters&Itemid=<?php echo $this->Itemid; ?>">
                        <img class="js_controlpanel_link_image" src="components/com_jsjobs/images/mycoverletters.png" alt="<?php echo JText::_('My Cover Letters'); ?>" />
                        <div class="js_controlpanel_link_text_wrapper">
                            <span class="js_controlpanel_link_title"><?php echo JText::_('My Cover Letters'); ?></span>
                        </div>
                    </a>
                    <?php
                }
                if ($jscontrolpanel['jobsloginlogout'] == 1) {
                    if (isset($userrole->rolefor)) {//jobseeker
                        $link = "index.php?option=com_users&c=users&task=logout&Itemid=" . $this->Itemid;
                        $text = JText::_('Logout');
                        $icon = "logout.png";
                    } else {
                        $redirectUrl = JRoute::_('index.php?option=com_jsjobs&c=jobseeker&view=jobseeker&layout=controlpanel');
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
                <span class="js_controlpanel_section_title"><?php echo JText::_('Jobs'); ?></span>
                <?php
                $print = checkLinks('jobcat', $userrole, $config, $jscontrolpanel);
                if ($print) {
                    ?>
                    <a class="js_controlpanel_link" href="index.php?option=com_jsjobs&c=category&view=category&layout=jobcat&Itemid=<?php echo $this->Itemid; ?>">
                        <img class="js_controlpanel_link_image" src="components/com_jsjobs/images/jobcat.png" alt="<?php echo JText::_('Jobs By Categories'); ?>" />
                        <div class="js_controlpanel_link_text_wrapper">
                            <span class="js_controlpanel_link_title"><?php echo JText::_('Jobs By Categories'); ?></span>
                        </div>
                    </a>
                    <?php
                }
                $print = checkLinks('listnewestjobs', $userrole, $config, $jscontrolpanel);
                if ($print) {
                    ?>
                    <a class="js_controlpanel_link" href="index.php?option=com_jsjobs&c=job&view=job&layout=listnewestjobs&Itemid=<?php echo $this->Itemid; ?>">
                        <img class="js_controlpanel_link_image" src="components/com_jsjobs/images/newestjobs.png" alt="<?php echo JText::_('Newest Jobs'); ?>" />
                        <div class="js_controlpanel_link_text_wrapper">
                            <span class="js_controlpanel_link_title"><?php echo JText::_('Newest Jobs'); ?></span>
                        </div>
                    </a>
                    <?php
                }
                $print = checkLinks('myappliedjobs', $userrole, $config, $jscontrolpanel);
                if ($print) {
                    ?>
                    <a class="js_controlpanel_link" href="index.php?option=com_jsjobs&c=jobapply&view=jobapply&layout=myappliedjobs&Itemid=<?php echo $this->Itemid; ?>">
                        <img class="js_controlpanel_link_image" src="components/com_jsjobs/images/myappliedjobs.png" alt="<?php echo JText::_('My Applied Jobs'); ?>" />
                        <div class="js_controlpanel_link_text_wrapper">
                            <span class="js_controlpanel_link_title"><?php echo JText::_('My Applied Jobs'); ?></span>
                        </div>
                    </a>
                    <?php
                }
                $print = checkLinks('jobsearch', $userrole, $config, $jscontrolpanel);
                if ($print) {
                    ?>
                    <a class="js_controlpanel_link" href="index.php?option=com_jsjobs&c=job&view=job&layout=jobsearch&Itemid=<?php echo $this->Itemid; ?>">
                        <img class="js_controlpanel_link_image" src="components/com_jsjobs/images/jobsearch.png" alt="<?php echo JText::_('Search Job'); ?>" />
                        <div class="js_controlpanel_link_text_wrapper">
                            <span class="js_controlpanel_link_title"><?php echo JText::_('Search Job'); ?></span>
                        </div>
                    </a>
                    <?php
                }
                $print = checkLinks('my_jobsearches', $userrole, $config, $jscontrolpanel);
                if ($print) {
                    ?>
                    <a class="js_controlpanel_link" href="index.php?option=com_jsjobs&c=jobsearch&view=jobsearch&layout=my_jobsearches&Itemid=<?php echo $this->Itemid; ?>">
                        <img class="js_controlpanel_link_image" src="components/com_jsjobs/images/jobsavesearch.png" alt="<?php echo JText::_('Job Save Searches'); ?>" />
                        <div class="js_controlpanel_link_text_wrapper">
                            <span class="js_controlpanel_link_title"><?php echo JText::_('Job Save Searches'); ?></span>
                        </div>
                    </a>
                <?php } ?>
                <span class="js_controlpanel_section_title"><?php echo JText::_('Statistics'); ?></span>
                <?php
                $print = checkLinks('jspackages', $userrole, $config, $jscontrolpanel);
                if ($print) {
                    ?>
                    <a class="js_controlpanel_link" href="index.php?option=com_jsjobs&c=jobseekerpackages&view=jobseekerpackages&layout=packages&Itemid=<?php echo $this->Itemid; ?>">
                        <img class="js_controlpanel_link_image" src="components/com_jsjobs/images/packages.png" alt=" Packages" />
                        <div class="js_controlpanel_link_text_wrapper">
                            <span class="js_controlpanel_link_title"><?php echo JText::_('Packages'); ?></span>
                        </div>
                    </a>
                    <?php
                }
                $print = checkLinks('jspurchasehistory', $userrole, $config, $jscontrolpanel);
                if ($print) {
                    ?>
                    <a class="js_controlpanel_link" href="index.php?option=com_jsjobs&c=purchasehistory&view=purchasehistory&layout=jobseekerpurchasehistory&Itemid=<?php echo $this->Itemid; ?>">
                        <img class="js_controlpanel_link_image" src="components/com_jsjobs/images/purchase_history.png" alt=" Job seeker Purchase History" />
                        <div class="js_controlpanel_link_text_wrapper">
                            <span class="js_controlpanel_link_title"><?php echo JText::_('Purchase History'); ?></span>
                        </div>
                    </a>
                    <?php
                }
                $print = checkLinks('jsmy_stats', $userrole, $config, $jscontrolpanel);
                if ($print) {
                    ?>
                    <a class="js_controlpanel_link" href="index.php?option=com_jsjobs&c=jobseeker&view=jobseeker&layout=my_stats&Itemid=<?php echo $this->Itemid; ?>">
                        <img class="js_controlpanel_link_image" src="components/com_jsjobs/images/mystats.png" alt="My Stats" />
                        <div class="js_controlpanel_link_text_wrapper">
                            <span class="js_controlpanel_link_title"><?php echo JText::_('My Stats'); ?></span>
                        </div>
                    </a>
            <?php } ?>
            </div>
        <?php } else { // not allowed job posting 
                $this->jsjobsmessages->getAccessDeniedMsg('You are not allowed', 'You are not allow to view Job Seeker control panel', 0);
        }
    }//ol
    ?>

    <?php

    function checkLinks($name, $userrole, $config, $jscontrolpanel) {
        $print = false;
        switch ($name) {
            case 'jspackages': $visname = 'vis_jspackages';
                break;
            case 'jspurchasehistory': $visname = 'vis_jspurchasehistory';
                break;
            case 'jsmy_stats': $visname = 'vis_jsmy_stats';
                break;

            default:$visname = 'vis_js' . $name;
                break;
        }
        if (isset($userrole->rolefor)) {
            if ($userrole->rolefor == 2) {
                if ($jscontrolpanel[$name] == 1)
                    $print = true;
            }elseif ($userrole->rolefor == 1) {
                if ($config['employerview_js_controlpanel'] == 1)
                    if ($config[$visname] == 1)
                        $print = true;
            }
        }else {
            if ($config[$visname] == 1)
                $print = true;
        }
        return $print;
    }
    ?>
    <script type="text/javascript" language="javascript">
        function setwidth() {
            var totalwidth = document.getElementById("cp_icon_row").offsetWidth;
            var width = totalwidth - 317;
            width = (width / 3) / 3;
            document.getElementById("cp_icon_row").style.marginLeft = width + "px";
            var totalicons = document.getElementsByName("cp_icon").length;
            for (var i = 0; i < totalicons; i++)
            {
                document.getElementsByName("cp_icon")[i].style.marginLeft = width + "px";
                document.getElementsByName("cp_icon")[i].style.marginRight = width + "px";
            }
        }
        //setwidth();
        function setwidthheadline() {
            var totalwidth = document.getElementById("tp_heading").offsetWidth;
            var textwidth = document.getElementById("tp_headingtext").offsetWidth;
            var width = totalwidth - textwidth;
            width = width / 2;
            document.getElementById("left_image").style.width = width + "px";
            document.getElementById("right_image").style.width = width + "px";
        }
        //setwidthheadline();
    </script>
