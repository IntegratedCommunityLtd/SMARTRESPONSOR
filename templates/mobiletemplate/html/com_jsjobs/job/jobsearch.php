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
 * File Name:	views/jobseeker/tmpl/jobsearch.php
  ^
 * Description: template for job search
  ^
 * History:		NONE
  ^
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.pane');
$editor = JFactory :: getEditor();
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_jsjobs/css/token-input-jsjobs.css');
$document->addStyleSheet('components/com_jsjobs/css/combobox/chosen.css');
if (JVERSION < 3) {
    JHtml::_('behavior.mootools');
    $document->addScript('components/com_jsjobs/js/jquery.js');
} else {
    JHtml::_('behavior.framework');
    JHtml::_('jquery.framework');
}
$document->addScript('components/com_jsjobs/js/jquery.tokeninput.js');
$document->addScript('components/com_jsjobs/js/combobox/chosen.jquery.js');
$document->addScript('components/com_jsjobs/js/combobox/prism.js');


JHTML :: _('behavior.calendar');
$width_big = 40;
$width_med = 25;
$width_sml = 15;

if ($this->config['date_format'] == 'm/d/Y')
    $dash = '/';
else
    $dash = '-';
$dateformat = $this->config['date_format'];
$firstdash = strpos($dateformat, $dash, 0);
$firstvalue = substr($dateformat, 0, $firstdash);
$firstdash = $firstdash + 1;
$seconddash = strpos($dateformat, $dash, $firstdash);
$secondvalue = substr($dateformat, $firstdash, $seconddash - $firstdash);
$seconddash = $seconddash + 1;
$thirdvalue = substr($dateformat, $seconddash, strlen($dateformat) - $seconddash);
$js_dateformat = '%' . $firstvalue . $dash . '%' . $secondvalue . $dash . '%' . $thirdvalue;
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
        if ($this->myjobsearch_allowed == VALIDATE) {
            $defaultradius = $this->config['defaultradius'];
            ?>
            <div id="js_main_wrapper">
                <span class="js_controlpanel_section_title"><?php echo JText::_('Search Job'); ?></span>

                <form action="<?php echo JRoute::_('index.php?option=com_jsjobs&c=job&view=job&layout=job_searchresults&Itemid=' . $this->Itemid); ?>" method="post" name="adminForm" id="adminForm" class="jsautoz_form" >
                    <input type="hidden" name="isjobsearch" value="1" />
                    <?php if ($this->searchjobconfig['search_job_title'] == '1') { ?>
                        <div class="fieldwrapper">
                            <div class="fieldtitle">
                                <?php echo JText::_('Job Title'); ?>
                            </div>
                            <div class="fieldvalue">
                                <input class="inputbox" type="text" name="title" size="40" maxlength="255"  />
                            </div>
                        </div>				        
                    <?php } ?>
                    <?php if ($this->searchjobconfig['search_job_category'] == '1') { ?>
                        <div class="fieldwrapper">
                            <div class="fieldtitle">
                                <?php echo JText::_('Categories'); ?>
                            </div>
                            <div class="fieldvalue">
                                <?php echo $this->searchoptions['jobcategory']; ?>
                            </div>
                        </div>				        
                    <?php } ?>
                    <?php if ($this->searchjobconfig['search_job_subcategory'] == '1') { ?>
                        <div class="fieldwrapper">
                            <div class="fieldtitle">
                                <?php echo JText::_('Sub Categories'); ?>
                            </div>
                            <div class="fieldvalue" id="fj_subcategory">
                                <?php echo $this->searchoptions['jobsubcategory']; ?>
                            </div>
                        </div>				        
                    <?php } ?>
                    <?php if ($this->searchjobconfig['search_job_type'] == '1') { ?>
                        <div class="fieldwrapper">
                            <div class="fieldtitle">
                                <?php echo JText::_('Job Type'); ?>
                            </div>
                            <div class="fieldvalue">
                                <?php echo $this->searchoptions['jobtype']; ?>
                            </div>
                        </div>				        
                    <?php } ?>
                    <?php if ($this->searchjobconfig['search_job_status'] == '1') { ?>
                        <div class="fieldwrapper">
                            <div class="fieldtitle">
                                <?php echo JText::_('Job status'); ?>
                            </div>
                            <div class="fieldvalue">
                                <?php echo $this->searchoptions['jobstatus']; ?>
                            </div>
                        </div>				        
                    <?php } ?>
                    <?php if ($this->searchjobconfig['search_job_salaryrange'] == '1') { ?>
                        <div class="fieldwrapper">
                            <div class="fieldtitle">
                                <?php echo JText::_('Salary Range'); ?>
                            </div>
                            <div class="fieldvalue">
                                <?php echo $this->searchoptions['currency']; ?>
                                <?php echo $this->searchoptions['salaryrangefrom']; ?>
                                <?php echo $this->searchoptions['salaryrangeto']; ?>
                                <?php echo $this->searchoptions['salaryrangetypes']; ?>
                            </div>
                        </div>				        
                    <?php } ?>
                    <?php if ($this->searchjobconfig['search_job_shift'] == '1') { ?>
                        <div class="fieldwrapper">
                            <div class="fieldtitle">
                                <?php echo JText::_('Shift'); ?>
                            </div>
                            <div class="fieldvalue">
                                <?php echo $this->searchoptions['shift']; ?>
                            </div>
                        </div>				        
                    <?php } ?>
                    <?php if ($this->searchjobconfig['search_job_durration'] == '1') { ?>
                        <div class="fieldwrapper">
                            <div class="fieldtitle">
                                <?php echo JText::_('Duration'); ?>
                            </div>
                            <div class="fieldvalue">
                                <input class="inputbox" type="text" name="durration" size="10" maxlength="15"  />
                            </div>
                        </div>				        
                    <?php } ?>
                    <?php if ($this->searchjobconfig['search_job_startpublishing'] == '1') { ?>
                        <div class="fieldwrapper">
                            <div class="fieldtitle">
                                <?php echo JText::_('Start Publishing'); ?>
                            </div>
                            <div class="fieldvalue">
                                <?php echo JHTML::_('calendar', '', 'startpublishing', 'startpublishing', $js_dateformat, array('class' => 'inputbox', 'size' => '10', 'maxlength' => '19')); ?>
                            </div>
                        </div>				        
                    <?php } ?>
                    <?php if ($this->searchjobconfig['search_job_stoppublishing'] == '1') { ?>
                        <div class="fieldwrapper">
                            <div class="fieldtitle">
                                <?php echo JText::_('Stop Publishing'); ?>
                            </div>
                            <div class="fieldvalue">
                                <?php echo JHTML::_('calendar', '', 'stoppublishing', 'stoppublishing', $js_dateformat, array('class' => 'inputbox', 'size' => '10', 'maxlenght' => '19')); ?>
                            </div>
                        </div>				        
                    <?php } ?>
                    <?php if ($this->searchjobconfig['search_job_company'] == '1') { ?>
                        <div class="fieldwrapper">
                            <div class="fieldtitle">
                                <?php echo JText::_('Company'); ?>
                            </div>
                            <div class="fieldvalue">
                                <?php echo $this->searchoptions['companies']; ?>
                            </div>
                        </div>				        
                    <?php } ?>

                    <?php if ($this->searchjobconfig['search_job_city'] == '1') { ?>
                        <div class="fieldwrapper">
                            <div class="fieldtitle">
                                <?php echo JText::_('City'); ?>
                            </div>
                            <div class="fieldvalue" id="city">
                                <input type="text" name="searchcity" size="40" id="searchcity"  value="" />
                            </div>
                        </div>				        
                    <?php } ?>
                    <?php if ($this->searchjobconfig['search_job_zipcode'] == '1') { ?>
                        <div class="fieldwrapper">
                            <div class="fieldtitle">
                                <?php echo JText::_('Zipcode'); ?>
                            </div>
                            <div class="fieldvalue">
                                <input class="inputbox" type="text" name="zipcode" size="40" maxlength="100"  />
                            </div>
                        </div>				        
                    <?php } ?>
                    <?php if ($this->searchjobconfig['search_job_coordinates'] == '1') { ?>
                        <div class="fieldwrapper">
                            <div class="fieldtitle">
                                <?php echo JText::_('Map Coordinates'); ?>
                            </div>
                            <div class="fieldvalue">
                                <div id="outermapdiv">
                                    <div id="map" style="width:<?php echo $this->config['mapwidth']; ?>px; height:<?php echo $this->config['mapheight']; ?>px">
                                        <div id="closetag"><a href="Javascript: hidediv();"><?php echo JText::_('X'); ?></a></div>
                                        <div id="map_container"></div>
                                    </div>
                                </div>
                                <span id="anchor"><a class="anchor" href="Javascript: showdiv();loadMap();"><?php echo JText::_('Show Map'); ?></a></span>
                                <br/><input type="text" id="longitude" name="longitude" value=""/><?php echo JText::_('Longitude'); ?>
                                <br/><input type="text" id="latitude" name="latitude" value=""/><?php echo JText::_('Latitude'); ?>
                            </div>
                        </div>				        
                        <div class="fieldwrapper">
                            <div class="fieldtitle">
                                <?php echo JText::_('Coordinates Radius'); ?>
                            </div>
                            <div class="fieldvalue">
                                <input type="text" id="radius" name="radius" value=""/>
                            </div>
                        </div>				        
                        <div class="fieldwrapper">
                            <div class="fieldtitle">
                                <?php echo JText::_('Radius Length Type'); ?>
                            </div>
                            <div class="fieldvalue">
                                <select name="radius_length_type" id="radius_length_type" class="jsjobs-cbo">
                                    <option value="m" <?php if ($defaultradius == 1) echo 'selected="selected"'; ?> ><?php echo JText::_('Meters'); ?></option>
                                    <option value="km" <?php if ($defaultradius == 2) echo 'selected="selected"'; ?> ><?php echo JText::_('Kilometers'); ?></option>
                                    <option value="mile" <?php if ($defaultradius == 3) echo 'selected="selected"'; ?> ><?php echo JText::_('Miles'); ?></option>
                                    <option value="nacmiles" <?php if ($defaultradius == 4) echo 'selected="selected"'; ?> ><?php echo JText::_('Nautical Miles'); ?></option>
                                </select>
                            </div>
                        </div>				        
                    <?php } ?>
                    <?php if ($this->searchjobconfig['search_job_keywords'] == '1') { ?>
                        <div class="fieldwrapper">
                            <div class="fieldtitle">
                                <?php echo JText::_('Keywords'); ?>
                            </div>
                            <div class="fieldvalue">
                                <input class="inputbox" type="text" name="keywords" size="40" maxlength="100"  />
                            </div>
                        </div>				        
                    <?php } ?>
                    <div class="fieldwrapper">
                        <input type="submit" id="button" class="button" name="submit_app" onClick="return checkmapcooridnate();" value="<?php echo JText::_('Search Job'); ?>" />
                    </div>				        

                    <input type="hidden" name="view" value="job" />
                    <input type="hidden" name="layout" value="job_searchresults" />
                    <input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
                    <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                    <input type="hidden" name="task11" value="view" />
                    <input type="hidden" id="default_longitude" name="default_longitude" value="<?php echo $this->config['default_longitude']; ?>"/>
                    <input type="hidden" id="default_latitude" name="default_latitude" value="<?php echo $this->config['default_latitude']; ?>"/>

                </form>
            </div>
            <?php
        } else {
            switch ($this->myjobsearch_allowed) {
                case JOB_SEARCH_NOT_ALLOWED_IN_PACKAGE:
                    $link = "index.php?option=com_jsjobs&c=jobseekerpackages&view=jobseekerpackages&layout=packages&Itemid=".$this->Itemid;
                    $this->jsjobsmessages->getPackageExpireMsg('Job Search Not Allowed In Package', 'Job Search Not Allowed In Package Please Purchase Package Which Have This Option', $link);
                    break;
                case EMPLOYER_NOT_ALLOWED_JOBSEEKER_PRIVATE_AREA:
                    $this->jsjobsmessages->getAccessDeniedMsg('Employer Not Allow', 'Employer is not allow in job seeker private area', 0);
                    break;
                case EMPLOYER_NOT_ALLOWED_JOBSEARCH:
                    $this->jsjobsmessages->getAccessDeniedMsg('Job search is not allow to employer', 'Job search is not allow to employer', 0);
                    break;
                case USER_ROLE_NOT_SELECTED:
                    $link = "index.php?option=com_jsjobs&c=common&view=common&layout=new_injsjobs&Itemid=".$this->Itemid;
                    $this->jsjobsmessages->getUserNotSelectedMsg('User Role Not Selected', 'User Role Is Not Selected, Please Select Your Role', $link);
                break;
                case VISITOR_NOT_ALLOWED_JOBSEARCH:
                    $this->jsjobsmessages->getAccessDeniedMsg('Visitor Not Allow', 'Visitor is not allow in employer private area', 1);
                break;
            }
        }
    }//ol
    ?>	
    <style type="text/css">
        div#outermapdiv{
            position:relative;
            float:left;
        }
        div#map_container{
            z-index:1000;
            position:relative;
            background:#000;
            width:100%;
            height:100%;
        }
        div#map{
            height: 300px;
            left: 0px;
            position: absolute;
            overflow:true;
            top: -94px;
            display:none;
            width: 650px;
        }
    </style>
<?php $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://"; ?>
<script type="text/javascript" src="<?php echo $protocol; ?>maps.googleapis.com/maps/api/js?sensor=false"></script>
    <script type="text/javascript">

                    function fj_getsubcategories(src, val) {
                        jQuery("#" + src).html("Loading ...");
                        jQuery.post('index.php?option=com_jsjobs&c=subcategory&task=listsubcategoriesForSearch', {val: val}, function (data) {
                            if (data) {
                                jQuery("#" + src).html(data);
                                jQuery("#" + src + " select.jsjobs-cbo").chosen();
                            } else {
                                jQuery("#" + src).html('<?php echo '<input type="text" name="jobsubcategory" value="">'; ?>');
                            }
                        });




                    }

                    function loadMap() {
                        var default_latitude = document.getElementById('default_latitude').value;
                        var default_longitude = document.getElementById('default_longitude').value;

                        var latitude = document.getElementById('latitude').value;
                        var longitude = document.getElementById('longitude').value;

                        if (latitude != '' && longitude != '') {
                            default_latitude = latitude;
                            default_longitude = longitude;
                        }
                        var latlng = new google.maps.LatLng(default_latitude, default_longitude);
                        zoom = 10;
                        var myOptions = {
                            zoom: zoom,
                            center: latlng,
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                        };
                        var map = new google.maps.Map(document.getElementById("map_container"), myOptions);
                        var lastmarker = new google.maps.Marker({
                            postiion: latlng,
                            map: map,
                        });
                        var marker = new google.maps.Marker({
                            position: latlng,
                            map: map,
                        });
                        marker.setMap(map);
                        lastmarker = marker;
                        document.getElementById('latitude').value = marker.position.lat();
                        document.getElementById('longitude').value = marker.position.lng();

                        google.maps.event.addListener(map, "click", function (e) {
                            var latLng = new google.maps.LatLng(e.latLng.lat(), e.latLng.lng());
                            geocoder = new google.maps.Geocoder();
                            geocoder.geocode({'latLng': latLng}, function (results, status) {
                                if (status == google.maps.GeocoderStatus.OK) {
                                    if (lastmarker != '')
                                        lastmarker.setMap(null);
                                    var marker = new google.maps.Marker({
                                        position: results[0].geometry.location,
                                        map: map,
                                    });
                                    marker.setMap(map);
                                    lastmarker = marker;
                                    document.getElementById('latitude').value = marker.position.lat();
                                    document.getElementById('longitude').value = marker.position.lng();

                                } else {
                                    alert("Geocode was not successful for the following reason: " + status);
                                }
                            });
                        });
                        //document.getElementById('map_container').innerHTML += "<a href='Javascript hidediv();'><?php echo JText::_('Close Map'); ?></a>";
                    }
                    function showdiv() {
                        document.getElementById('map').style.visibility = 'visible';
                    }
                    function hidediv() {
                        document.getElementById('map').style.visibility = 'hidden';
                    }
                    function checkmapcooridnate() {
                        var latitude = document.getElementById('latitude').value;
                        var longitude = document.getElementById('longitude').value;
                        var radius = document.getElementById('radius').value;
                        if (latitude != '' && longitude != '') {
                            if (radius != '') {
                                this.form.submit();
                            } else {
                                alert('<?php echo JText::_("Please Enter The Cooridnate Radius"); ?>');
                                return false;
                            }
                        }

                    }
                    jQuery(document).ready(function () {
                        jQuery("select.jsjobs-cbo").chosen();
                        jQuery("input.jsjobs-inputbox")
                                .css({
                                    'width': '192px',
                                    'border': '1px solid #A9ABAE',
                                    'cursor': 'text',
                                    'margin': '0',
                                    'padding': '4px'
                                });
                        jQuery("#searchcity").tokenInput("<?php echo JURI::root() . "index.php?option=com_jsjobs&c=cities&task=getaddressdatabycityname"; ?>", {
                            theme: "jsjobs",
                            preventDuplicates: true,
                            hintText: "<?php echo JText::_('Type In A Search'); ?>",
                            noResultsText: "<?php echo JText::_('No Results'); ?>",
                            searchingText: "<?php echo JText::_('Searching...'); ?>",
                        });
                    });








    </script>
