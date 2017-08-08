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
 * File Name:	views/jobseeker/tmpl/job_apply.php
  ^
 * Description: template view to apply for a job
  ^
 * History:		NONE
  ^
 */
defined('_JEXEC') or die('Restricted access');
$user = JFactory::getUser();
$isShowButton = 1;
?>
<div id="jsjobs_main">
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
        $show_job_apply = 1;
        if ($this->isjobsharing) {
            if (empty($this->job))
                $show_job_apply = 0;
        }
        if ($show_job_apply == 1) {

            if (isset($this->userrole->rolefor) && $this->userrole->rolefor == 2) { // job seeker
                if ($this->totalresume > 0) { // Resume not empty
                    ?>
                    <div id="js_main_wrapper">
                        <span class="js_controlpanel_section_title"><?php echo JText::_('Apply now'); ?></span>
                        <form action="index.php" method="post" name="adminForm" id="adminForm" class="jsautoz_form">
                            <div class="fieldwrapper rs_sectionheadline">
                                <?php echo JText::_('Emp App Info'); ?>
                            </div>				        
                            <div class="fieldwrapper">
                                <div class="fieldtitle">
                                    <?php echo JText::_('My Resume'); ?>
                                </div>
                                <div class="fieldvalue">
                                    <?php echo $this->myresumes; ?>
                                </div>
                            </div>				        
                            <div class="fieldwrapper">
                                <div class="fieldtitle">
                                    <?php echo JText::_('My Cover Letter'); ?>
                                </div>
                                <div class="fieldvalue">
                                    <?php echo $this->mycoverletters; ?>
                                </div>
                            </div>				        
                            <div class="fieldwrapper rs_sectionheadline">
                                <?php echo JText::_('Job Info'); ?>
                            </div>				        
                            <div class="fieldwrapper view">
                                <div class="fieldtitle">
                                    <?php echo JText::_('Title'); ?>
                                </div>
                                <div class="fieldvalue">
                                    <?php
                                    echo $this->job->title;
                                    $days = $this->config['newdays'];
                                    $isnew = date("Y-m-d H:i:s", strtotime("-$days days"));
                                    if ($this->job->created > $isnew)
                                        echo "<font color='red'> " . JText::_('New') . " </font>";
                                    ?>
                                </div>
                            </div>				        

                            <?php if ($this->listjobconfig['lj_category'] == '1') { ?>
                                <div class="fieldwrapper view">
                                    <div class="fieldtitle">
                                        <?php echo JText::_('Category'); ?>
                                    </div>
                                    <div class="fieldvalue">
                                        <?php echo $this->job->cat_title; ?>
                                    </div>
                                </div>				        
                            <?php
                            }
                            if ($this->listjobconfig['lj_jobtype'] == '1') {
                                ?>
                                <div class="fieldwrapper view">
                                    <div class="fieldtitle">
                    <?php echo JText::_('Job Type'); ?>
                                    </div>
                                    <div class="fieldvalue">
                    <?php echo $this->job->jobtypetitle; ?>
                                    </div>
                                </div>				        
                            <?php
                            }
                            if ($this->listjobconfig['lj_jobstatus'] == '1') {
                                ?>
                                <div class="fieldwrapper view">
                                    <div class="fieldtitle">
                                        <?php echo JText::_('Job status'); ?>
                                    </div>
                                    <div class="fieldvalue">
                                <?php echo "<font color='red'><strong>" . $this->job->jobstatustitle . "</strong></font>"; ?>
                                    </div>
                                </div>				        
                            <?php
                            }

                            if ($this->listjobconfig['lj_company'] == '1') {
                                ?>
                                <div class="fieldwrapper view">
                                    <div class="fieldtitle">
                                        <?php echo JText::_('Company'); ?>
                                    </div>
                                    <div class="fieldvalue">
                                        <?php
                                        if (isset($_GET['cat']))
                                            $jobcat = $_GET['cat'];
                                        else
                                            $jobcat = null;
                                        $link = 'index.php?option=com_jsjobs&c=company&view=company&layout=view_company&nav=32&cd=' . $this->job->companyaliasid . '&cat=' . $jobcat . '&Itemid=' . $this->Itemid;
                                        ?>
                                        <span id="anchor"><a class="anchor" href="<?php echo $link ?>"><?php echo $this->job->companyname; ?></a></span>
                                    </div>
                                </div>				        
                                    <?php
                                    }
                                    if ($this->listjobconfig['lj_companysite'] == '1') {
                                        ?>
                                <div class="fieldwrapper view">
                                    <div class="fieldtitle">
                                                             <?php echo JText::_('Companyurl'); ?>
                                    </div>
                                    <div class="fieldvalue">
                                        <span id="anchor"><a class="anchor" href='<?php
                                $chkprotocol = isURL($this->job->url);
                                if ($chkprotocol == true)
                                    echo $this->job->url;
                                else
                                    echo 'http://' . $this->job->url;
                                ?>' target='_blank'><?php echo $this->job->url; ?></a></span>
                                    </div>
                                </div>				        
                <?php
                }
                if ($this->listjobconfig['lj_companysite'] == '1') {
                    ?>
                                <div class="fieldwrapper view">
                                    <div class="fieldtitle">
                    <?php echo JText::_('Location'); ?>
                                    </div>
                                    <div class="fieldvalue">
                    <?php if ($this->job->multicity != '') echo $this->job->multicity; ?>
                                    </div>
                                </div>				        
                                    <?php } ?>	
                                    <?php
                                    if ($this->job->jobsalaryrange != 0) {
                                        ?>
                                <div class="fieldwrapper view">
                                    <div class="fieldtitle">
                                <?php echo JText::_('Salary Range'); ?>
                                    </div>
                                    <div class="fieldvalue">
                    <?php
                    echo $this->getJSModel('common')->getSalaryRangeView($this->job->symbol,$this->job->rangestart,$this->job->rangeend,$this->job->salarytypetitle,$this->config['currency_align']);
                    ?>
                                    </div>
                                </div>				        
                <?php
                }
                if ($this->listjobconfig['lj_noofjobs'] == '1') {
                    ?>
                                <?php if ($this->job->noofjobs != 0) {
                                    ?>
                                    <div class="fieldwrapper view">
                                        <div class="fieldtitle">
                                            <?php echo JText::_('No of jobs'); ?>
                                        </div>
                                        <div class="fieldvalue">
                                            <?php echo $this->job->noofjobs ?>
                                        </div>
                                    </div>				        
                                        <?php
                                    }
                                }
                                ?>
                            <div class="fieldwrapper view">
                                <div class="fieldtitle">
                                <?php echo JText::_('Date posted'); ?>
                                </div>
                                <div class="fieldvalue">
                                <?php echo JHtml::_('date', $this->job->created, $this->config['date_format']); ?>
                                </div>
                            </div>				        
                            <div class="fieldwrapper view">
                <?php if ($isShowButton == 1) { ?>
                                    <input type="submit" id="button" class="button" name="submit_app" onclick="document.adminForm.submit();"  value="<?php echo JText::_('Apply now'); ?>" /></td>
                    <?php
                } else if ($isShowButton == 2) {
                    echo "<font color='red'><strong>" . JText::_('Job status') . " : " . $jobstatus[$this->job->jobstatus - 1] . "</strong></font>";
                } else if ($isShowButton == 3) {
                    echo "<font color='red'><strong>" . JText::_('Emp App Wait Approval') . "</strong></font>";
                } else if ($isShowButton == 4) {
                    echo "<font color='red'><strong>" . JText::_('Emp App Reject') . "</strong></font>";
                }
                ?>
                            </div>				        
                            <input type="hidden" name="view" value="jobapply" />
                            <input type="hidden" name="layout" value="static" />
                            <input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
                            <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                            <input type="hidden" name="task" value="jobapply" />
                            <input type="hidden" name="c" value="jobapply" />
                            <input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
                            <input type="hidden" name="jobid" value="<?php echo $this->job->id; ?>" />
                            <input type="hidden" name="oldcvid" value="<?php if (isset($this->myapplication->id)) echo $this->myapplication->id; ?>" />
                            <input type="hidden" name="apply_date" value="<?php echo date('Y-m-d H:i:s'); ?>" />
                            <input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
                        </form>
                    </div>
            <?php }else { // Employment application is empty 
                ?>
                    <div class="js_job_error_messages_wrapper">
                        <div class="js_job_messages_image_wrapper">
                            <img class="js_job_messages_image" src="components/com_jsjobs/images/2.png"/>
                        </div>
                        <div class="js_job_messages_data_wrapper">
                            <span class="js_job_messages_main_text">
                    <?php echo JText::_('Ea Emp App Empty'); ?>
                            </span>
                            <span class="js_job_messages_block_text">
                <?php echo JText::_('Ea Emp App Empty'); ?>
                            </span>
                        </div>
                    </div>
                <?php
            }
        } elseif (!isset($this->userrole->rolefor)) {
            if ($this->config['visitor_show_login_message'] == 1) {
                $redirectUrl = 'index.php?option=com_jsjobs&c=job&view=job&layout=view_job&nav=15&bd=' . $this->bd . '&Itemid=' . $this->Itemid;
                $redirectUrl = $this->getJSModel('common')->b64ForEncode($redirectUrl);
                $formresumelink = JRoute::_('index.php?option=com_jsjobs&c=resume&view=resume&layout=formresume');
                ?>
                    <div id="jsjobs_apply_visitor">
                        <div class="js_apply_loginform">
                            <form action="<?php echo JRoute::_('index.php', true); ?>" method="post" id="login-form" >
                                <div class="js_apply_loginform_70">
                                    <span class="js_login_title"><?php echo JText::_('Login'); ?></span>
                                    <input id="modlgn-username" placeholder="<?php echo JText::_('User Name'); ?>" type="text" name="username" class="inputbox"  size="18" />
                                    <input id="modlgn-passwd" placeholder="<?php echo JText::_('Password'); ?>" type="password" name="password" class="inputbox" size="18"  />
                                </div>
                                <div class="js_apply_login_30">
                                    <a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>"><?php echo JText::_('Forgot Your Password'); ?></a>
                <?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
                                        <input id="modlgn-remember" type="checkbox" name="remember" class="inputbox" value="yes"/>
                                        <label for="modlgn-remember"><?php echo JText::_('Keep Me Login') ?></label>
                    <?php endif; ?>
                                    <input type="submit" name="Submit" class="js_apply_button" value="<?php echo JText::_('Jlogin') ?>" />
                                </div>
                                <input type="hidden" name="option" value="com_users" />
                                <input type="hidden" name="task" value="user.login" />
                                <input type="hidden" name="return" value="<?php echo $redirectUrl; ?>" />
                <?php echo JHtml::_('form.token'); ?>
                            </form>
                        </div>            
                        <div class="js_apply_visitor_apply">
                            <a href='<?php echo $formresumelink; ?>'><img src="components/com_jsjobs/images/visitor_apply.png" /><div><?php echo JTEXT::_('Job apply as a visitor'); ?></div></a>
                        </div>
                    </div>
                                <?php
                            }
                        } else { // not allowed job posting 
                            $this->jsjobsmessages->getAccessDeniedMsg('You are not allowed', 'You are not allow to view this page', 0);
                        }
        } else {
            if (!isset($this->userrole->rolefor)) {
                if ($this->config['visitor_show_login_message'] == 1) {
                    $redirectUrl = 'index.php?option=com_jsjobs&c=jsjobs&view=&layout=successfullogin';
                    $redirectUrl = '&amp;return=' . $this->getJSModel('common')->b64ForEncode($redirectUrl);
                    $finalUrl = 'index.php?option=com_users&view=login' . $redirectUrl;
                    $finalUrl = JRoute::_($finalUrl);
                    $formresumelink = JRoute::_('index.php?option=com_jsjobs&c=resume&view=resume&layout=formresume');
                    echo JTEXT::_('JS_PLEASE_LOGIN_TO_RECORD_YOUR_RESUME_FOR_FUTURE_USE');
                    echo "<br><a href='" . $finalUrl . "'><strong>" . JTEXT::_('JS_LOGIN') . "</strong></a> " . JTEXT::_('JS_OR') . "<strong><a href='" . $formresumelink . "'>" . JTEXT::_('JS_JOB_APPLY_AS_A_VISITOR') . "</a></strong>";
                }
            } else {
                $this->jsjobsmessages->getAccessDeniedMsg('Error Occure Please Contact To Administrator', 'Error Occure Please Contact To Administrator', 0);
            }
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
