<?php
/**
 * @version		$Id: register.php 1492 2012-02-22 17:40:09Z joomlaworks@gmail.com $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.parameter');
JHTML::_('behavior.formvalidation');
?>
<div id="jsjobs_main">
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
    ?>
        <div id="js_main_wrapper">
            <span class="js_controlpanel_section_title">
                <?php
                if ($this->userrole == 2) {
                    echo JText::_('Jobseeker Login');
                } elseif ($this->userrole == 3) {
                    echo JText::_('Employer Login');
                }
                ?>
            </span>
            <form action="<?php echo JRoute::_('index.php?option=com_users&task=user.login'); ?>" method="post" id="loginform" name="loginform">

                <div id="userform" class="userform">
                    <table cellpadding="5" cellspacing="0" border="0" width="100%" class="admintable" >
                        <tr>
                            <td align="right" nowrap>
                                <label id="name-lbl" for="name"><?php echo JText::_('User Name'); ?>: </label>* 
                            </td>
                            <td>
                                <input id="username" class="validate-username" type="text" size="25" value="" name="username" >
                            </td>
                        </tr>
                        <tr>
                            <td align="right" nowrap>
                                <label id="password-lbl" for="password"><?php echo JText::_('Password'); ?>: </label>* 
                            </td>
                            <td>
                                <input id="password" class="validate-password" type="password" size="25" value="" name="password">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center">
                                <input id="button" class="button validate" type="button" onclick="return checkformlogin();" value="<?php echo JText::_('Login'); ?>"/>

                <!--<button  type="submit" class="button validate" onclick="return myValidate(this.loginform);"><?php echo JText::_('Jlogin'); ?></button>-->
                            </td>
                        </tr>

                </table>
                <input type="hidden" name="return" value="<?php echo $this->loginreturn; ?>" />
                <?php echo JHtml::_('form.token'); ?>
            </div>	
        </form>
    </div>
    <div>
        <ul>
            <li>
                <a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
                    <?php echo JText::_('Forget Your Password?'); ?></a>
            </li>
            <li>
                <a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
                    <?php echo JText::_('Forget Your Username?'); ?></a>
            </li>
            <?php
            $usersConfig = JComponentHelper::getParams('com_users');
            if ($usersConfig->get('allowUserRegistration')) :
                ?>
                <li>
                    <a href="<?php echo JRoute::_('index.php?option=com_jsjobs&view=common&layout=userregister&userrole=' . $this->userrole . '&Itemid=0'); ?>">
                        <?php echo JText::_('Dont Have An Account?'); ?></a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
    <script type="text/javascript" language="javascript">
        function checkformlogin() {
            var username = document.getElementById('username').value;
            var password = document.getElementById('password').value;
            if (username != "" && password != "") {
                document.loginform.submit();
            } else {
                alert('<?php echo JText::_('Fill Req Fields'); ?>');
            }
        }
    </script>	


<?php } //ol  ?>


