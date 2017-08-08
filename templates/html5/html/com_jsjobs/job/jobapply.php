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
 * File Name:	views/jobseeker/tmpl/listnewestjobs.php
  ^
 * Description: template view for newest jobs
  ^
 * History:		NONE
  ^
 */
defined('_JEXEC') or die('Restricted access');
?>
<div id="black_wrapper_jobapply" style="display:none;"></div>
<div id="js_jobapply_main_wrapper" style="display: none;">

</div>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('a[data-jobapply="jobapply"]').click(function (e) {
            e.preventDefault();
            var jobid = jQuery(this).attr('data-jobid');
            jQuery.post("index.php?option=com_jsjobs&task=jobapply.applyjob", {jobid: jobid}, function (data) {
                if (data) { // data come from the controller
                    var response = jQuery.parseJSON(data);
                    if (typeof response == 'object')
                    {
                        if (response[0] === 'popup') {
                            jQuery("div#js_jobapply_main_wrapper").html(response[1]);
                            jQuery("div#black_wrapper_jobapply").fadeIn();
                            jQuery("div#js_jobapply_main_wrapper").slideDown("slow");
                        } else {
                            window.location = response[1];
                        }
                    }
                    else
                    {
                        if (response === false)
                        {
                            //the response was a string "false", parseJSON will convert it to boolean false
                        }
                        else
                        {
                            //the response was something else
                        }
                    }
                }
            });
        });
        jQuery("div#black_wrapper_jobapply").click(function () {
            jQuery("div#js_jobapply_main_wrapper").fadeOut();
            jQuery("div#black_wrapper_jobapply").fadeOut();
        });
        jQuery('a.js_job_quick_view_link').click(function (e) {
            e.preventDefault();
            var jobid = jQuery(this).attr('data-jobid');
            jQuery.post("index.php?option=com_jsjobs&task=job.quickview", {jobid: jobid}, function (data) {
                if (data) {
                    jQuery("div#js_jobapply_main_wrapper").html(data);
                    jQuery("div#black_wrapper_jobapply").fadeIn();
                    jQuery("div#js_jobapply_main_wrapper").slideDown("slow");
                }
            });
        });
    });
</script>