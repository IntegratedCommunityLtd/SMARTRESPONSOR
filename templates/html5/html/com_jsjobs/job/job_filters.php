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
 * File Name:	views/jobseeker/tmpl/filters.php
  ^
 * Description: template view for filters
  ^
 * History:		NONE
  ^
 */
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_jsjobs/css/token-input-jsjobs.css');
if (JVERSION < 3) {
    JHtml::_('behavior.mootools');
    $document->addScript('components/com_jsjobs/js/jquery.js');
} else {
    JHtml::_('behavior.framework');
    JHtml::_('jquery.framework');
}
$document->addScript('components/com_jsjobs/js/jquery.tokeninput.js');


jimport('joomla.filter.output');
$filter_address_fields_width = $this->config['filter_address_fields_width'];
?>

<?php
if ($this->config['filter'] == 1) {
    $defaultradius = $this->config['defaultradius'];
    ?>
    <?php if ($this->config['filter_map'] == 1) { ?>
        <div id="map" >
            <div id="js_main_wrapper">
                <span class="js_job_applynow_heading"><?php echo JText::_('Map'); ?></span>
            </div>
            <div id="map_container"></div>
            <?php
            $radius = JText::_('Coordinates Radius');
            $style = "width:" . $this->config['filter_map_fields_width'] . "px;";
            $style .= "color:#808080;";
            $longitude = JText::_('Longitude');
            $style .= "color:#808080;";
            if (isset($this->filtervalues))
                if ($this->filtervalues['longitude'] != '') {
                    $longitude = $this->filtervalues['longitude'];
                    $style .= "color:black;";
                }
            ?>
            <input class="inputbox" type="text" id="filter_longitude" name="filter_longitude" value="<?php echo $longitude; ?>" size="25" style="<?php echo $style; ?>" onfocus="if (this.value == '<?php echo JText::_('Longitude'); ?>') {
                        this.value = '';
                        this.style.color = 'black';
                    }" onblur="if (this.value == '') {
                                this.style.color = '#808080';
                                this.value = '<?php echo JText::_('Longitude'); ?>';
                            }" />
                   <?php
                   $latitude = JText::_('Latitude');
                   $style .= "color:#808080;";
                   if (isset($this->filtervalues))
                       if ($this->filtervalues['latitude'] != '') {
                           $latitude = $this->filtervalues['latitude'];
                           $style .= "color:black;";
                       }
                   ?>
            <input class="inputbox" type="text" id="filter_latitude" name="filter_latitude" value="<?php echo $latitude; ?>" size="25" style="<?php echo $style; ?>" onfocus="if (this.value == '<?php echo JText::_('Latitude'); ?>') {
                        this.value = '';
                        this.style.color = 'black';
                    }" onblur="if (this.value == '') {
                                this.style.color = '#808080';
                                this.value = '<?php echo JText::_('Latitude'); ?>';
                            }" onchange="this.style.color = 'black';"/>
                   <?php
                   if (isset($this->filtervalues))
                       if ($this->filtervalues['radius'] != '') {
                           $radius = $this->filtervalues['radius'];
                           $style .= "color:black;";
                       }
                   ?>
            <input class="inputbox" type="text" id="filter_radius" name="filter_radius" value="<?php echo $radius; ?>" style="<?php echo $style; ?>" size="25" onfocus="if (this.value == '<?php echo JText::_('Coordinates Radius'); ?>') {
                        this.value = '';
                        this.style.color = 'black';
                    }" onblur="if (this.value == '') {
                                this.style.color = '#808080';
                                this.value = '<?php echo JText::_('Coordinates Radius'); ?>';
                            }" />
            <select name="filter_radius_length_type" id="filter_radius_length_type" style="<?php echo $style; ?>" class="inputbox">
                <option value="m" <?php if ($defaultradius == 1) echo 'selected="selected"'; ?> ><?php echo JText::_('Meters'); ?></option>
                <option value="km" <?php if ($defaultradius == 2) echo 'selected="selected"'; ?> ><?php echo JText::_('Kilometers'); ?></option>
                <option value="mile" <?php if ($defaultradius == 3) echo 'selected="selected"'; ?> ><?php echo JText::_('Miles'); ?></option>
                <option value="nacmiles" <?php if ($defaultradius == 4) echo 'selected="selected"'; ?> ><?php echo JText::_('Nautical Miles'); ?></option>
            </select>
            <div id="closetag"><a href="Javascript: hidediv();"><?php echo JText::_('Close'); ?></a></div>
        </div>
        <div id="black_wrapper_map" style="display:none;"></div>
    <?php } ?>
    <div id="tp_filter_in">
        <?php
        if ($this->config['filter_category'] == 1)
            echo $this->filterlists['jobcategory'];
        if ($this->config['filter_sub_category'] == 1)
            echo '<span id="td_jobsubcategory">' . $this->filterlists['jobsubcategory'] . '</span>';
        if ($this->config['filter_jobtype'] == 1)
            echo $this->filterlists['jobtype'];
        ?>	            
        <?php
        if ($this->config['filter_address'] == 1) {
            $style = "width:" . $this->config['filter_address_fields_width'] . "px;";
            $fieldwidth = $this->config["filter_map_fields_width"] . "px;";
            ?>
            <?php
            if ($this->isjobsharing) {
                if ($this->config['filter_address_country'] == 1) {
                    echo '<span style="float:left;" name="filter_country" id="filter_country">' . $this->filterlists['country'] . '</span>';
                }
            }
            ?>
            <div id="jsjobs_object_jqueryautocomplete_left" style="<?php echo $style; ?>">
                <a href="Javascript: showdiv();loadMap();"><img width="13px" height="22px" src="components/com_jsjobs/images/filternav.png" text="<?php echo JText::_('Location'); ?>" alt="<?php echo JText::_('Location'); ?>" /></a>
                <input type="text" name="txtfilter_city"  id="txtfilter_city" style="<?php echo $style; ?>" value="<?php echo $this->filtervalues['city']; ?>" />
                <input type="hidden" name="txtfilter_citylocation" id="txtfilter_citylocation" value="<?php echo $this->filtervalues['location']; ?>"  />
            </div>                                   

        <?php }
        ?>
        <div class="js_job_filter_button_wrapper">
            <?php
            if (isset($this->userrole)) {
                if (isset($this->uid) && (isset($this->userrole->rolefor))) {
                    if (isset($this->filterid)) { ?>
                        <button class="tp_filter_button" name="btn_delete" onclick="deleteSearch();"><?php echo JText::_('Delete This Filter'); ?></button>
                    <?php } else { ?>
                        <button class="tp_filter_button" name="btn_save" onclick="saveSearch();"><?php echo JText::_('Save This Filter'); ?></button>
                        <?php
                    }
                }
            }
            ?>
            <button class="tp_filter_button" onclick="return checkmapcooridnate();"><?php echo JText::_('Go'); ?></button>
            <button class="tp_filter_button" onclick="myReset();"><?php echo JText::_('Reset'); ?></button>
        </div>
    </div>
    <input type="hidden" name="id" value="<?php echo $this->filterid; ?>">
    <input type="hidden" name="formaction" value="">
    <input type="hidden" id="default_longitude" name="default_longitude" value="<?php echo $this->config['default_longitude']; ?>"/>
    <input type="hidden" id="default_latitude" name="default_latitude" value="<?php echo $this->config['default_latitude']; ?>"/>


    <script language=Javascript>
        jQuery(document).ready(function () {
            var value = jQuery("#txtfilter_city").val();
            if (value != "") {
                jQuery("#txtfilter_city").tokenInput("<?php echo JURI::root() . "index.php?option=com_jsjobs&c=cities&task=getaddressdatabycityname"; ?>", {
                    theme: "jsjobs",
                    width: '<?php echo $this->config['filter_address_fields_width'] ?>px',
                    preventDuplicates: true,
                    hintText: "<?php echo JText::_('Type In A Search'); ?>",
                    noResultsText: "<?php echo JText::_('No Results'); ?>",
                    searchingText: "<?php echo JText::_('Searching...'); ?>",
                    tokenLimit: 1,
                    prePopulate: [
                        {id: "<?php echo $this->filtervalues['city']; ?>", name: "<?php echo $this->filtervalues['location']; ?>"}
                    ]
                });
            } else {
                jQuery("#txtfilter_city").tokenInput("<?php echo JURI::root() . "index.php?option=com_jsjobs&c=cities&task=getaddressdatabycityname"; ?>", {
                    theme: "jsjobs",
                    width: '<?php echo $this->config['filter_address_fields_width'] ?>px',
                    preventDuplicates: true,
                    hintText: "<?php echo JText::_('Type In A Search'); ?>",
                    noResultsText: "<?php echo JText::_('No Results'); ?>",
                    searchingText: "<?php echo JText::_('Searching...'); ?>",
                    tokenLimit: 1

                });
            }
            jQuery("ul.jsjobs-input-list-jsjobs").css('width', jQuery("div.jsjobs-input-dropdown-jsjobs").css('width'));
            jQuery("div.jsjobs-input-dropdown-jsjobs").css('width', jQuery("ul.jsjobs-input-list-jsjobs").css('width'));
            jQuery("div#black_wrapper_map").click(function () {
                jQuery("div#map").css("visibility", 'hidden');
                jQuery(this).hide();
            });
        });
        function setWidthForFilter() {
            var totalwidth = document.getElementById("tp_filter_in_div").offsetWidth - 35;
            var addressobjects = 0;
            if (testIsValidObject(document.adminForm.cmbfilter_country))
                addressobjects = addressobjects + 1;
            if (testIsValidObject(document.adminForm.cmbfilter_state))
                addressobjects = addressobjects + 1;
            //if (testIsValidObject(document.adminForm.cmbfilter_county)) addressobjects=addressobjects+1;
            if (testIsValidObject(document.adminForm.cmbfilter_city))
                addressobjects = addressobjects + 1;
            if (testIsValidObject(document.adminForm.txtfilter_country))
                addressobjects = addressobjects + 1;
            if (testIsValidObject(document.adminForm.txtfilter_state))
                addressobjects = addressobjects + 1;
            //if (testIsValidObject(document.adminForm.txtfilter_county)) addressobjects=addressobjects+1;
            if (testIsValidObject(document.adminForm.txtfilter_city))
                addressobjects = addressobjects + 1;
            var width = (totalwidth / addressobjects) - 10;
            if (testIsValidObject(document.adminForm.cmbfilter_country))
                document.adminForm.cmbfilter_country.style.width = width + "px";
            if (testIsValidObject(document.adminForm.cmbfilter_state))
                document.adminForm.cmbfilter_state.style.width = width + "px";
            //if (testIsValidObject(document.adminForm.cmbfilter_county)) document.adminForm.cmbfilter_county.style.width = width+"px";
            if (testIsValidObject(document.adminForm.cmbfilter_city))
                document.adminForm.cmbfilter_city.style.width = width + "px";
            if (testIsValidObject(document.adminForm.txtfilter_country))
                document.adminForm.txtfilter_country.style.width = width + "px";
            if (testIsValidObject(document.adminForm.txtfilter_state))
                document.adminForm.txtfilter_state.style.width = width + "px";
            //if (testIsValidObject(document.adminForm.txtfilter_county)) document.adminForm.txtfilter_county.style.width = width+"px";
            if (testIsValidObject(document.adminForm.txtfilter_city))
                document.adminForm.txtfilter_city.style.width = width + "px";
            var mapobjects = 0;
            if (testIsValidObject(document.adminForm.filter_longitude))
                mapobjects = mapobjects + 1;
            if (testIsValidObject(document.adminForm.filter_latitude))
                mapobjects = mapobjects + 1;
            if (testIsValidObject(document.adminForm.filter_radius))
                mapobjects = mapobjects + 1;
            var width = (totalwidth / mapobjects) - 45;
            if (testIsValidObject(document.adminForm.filter_longitude))
                document.adminForm.filter_longitude.style.width = width + "px";
            if (testIsValidObject(document.adminForm.filter_latitude))
                document.adminForm.filter_latitude.style.width = width + "px";
            if (testIsValidObject(document.adminForm.filter_radius))
                document.adminForm.filter_radius.style.width = width + "px";
            var listobjects = 0;
            if (testIsValidObject(document.getElementsByName("btn_delete")[0]))
                listobjects = listobjects + 1;
            if (testIsValidObject(document.getElementsByName("btn_save")[0]))
                listobjects = listobjects + 1;
            if (testIsValidObject(document.adminForm.filter_jobcategory))
                listobjects = listobjects + 1;
            if (testIsValidObject(document.adminForm.filter_jobsubcategory))
                listobjects = listobjects + 1;
            if (testIsValidObject(document.adminForm.filter_jobtype))
                listobjects = listobjects + 1;
            var width = (totalwidth / listobjects) - 40;
            if (testIsValidObject(document.getElementsByName("btn_delete")[0]))
                document.getElementsByName("btn_delete")[0].style.width = width + "px";
            //if (testIsValidObject(document.getElementsByName("btn_save")[0])) document.getElementsByName("btn_save")[0].style.width = (width+16)+"px";
            if (testIsValidObject(document.adminForm.filter_jobcategory))
                document.adminForm.filter_jobcategory.style.width = width + "px";
            if (testIsValidObject(document.adminForm.filter_jobsubcategory))
                document.adminForm.filter_jobsubcategory.style.width = width + "px";
            if (testIsValidObject(document.adminForm.filter_jobtype))
                document.adminForm.filter_jobtype.style.width = width + "px";

        }
        //setWidthForFilter();
        function saveSearch() {
            document.adminForm.formaction.value = document.adminForm.action;
            document.adminForm.action = 'index.php?option=com_jsjobs&c=filter&task=savefilter';
            document.adminForm.submit();

        }

        function deleteSearch() {
            document.adminForm.formaction.value = document.adminForm.action;
            document.adminForm.action = 'index.php?option=com_jsjobs&c=filter&task=deletefilter';
            document.adminForm.submit();
        }
        function myReset() {
            var cmbfilter_country_combo = document.getElementById("cmbfilter_country");
            if (typeof cmbfilter_country_combo !== 'undefined' && cmbfilter_country_combo !== null) {
                document.adminForm.cmbfilter_country.value = ''
            }
            if (testIsValidObject(document.adminForm.txtfilter_city))
                document.adminForm.txtfilter_city.value = '';
            if (testIsValidObject(document.adminForm.filter_longitude))
                document.adminForm.filter_longitude.value = '';
            if (testIsValidObject(document.adminForm.filter_latitude))
                document.adminForm.filter_latitude.value = '';
            if (testIsValidObject(document.adminForm.filter_radius))
                document.adminForm.filter_radius.value = '';

            document.adminForm.filter_jobcategory.value = '';
            document.adminForm.filter_jobsubcategory.value = '';
            document.adminForm.filter_jobtype.value = '';

            document.adminForm.submit();

        }

        function testIsValidObject(objToTest) {
            if (null == objToTest) {
                return false;
            }
            if ("undefined" == typeof (objToTest)) {
                return false;
            }
            return true;

        }

        function fj_getsubcategories(src, val) {
            jQuery.post("index.php?option=com_jsjobs&c=subcategory&task=listfiltersubcategories", {val: val}, function (data) {
                if (data) {
                    jQuery("#" + src).html(data);
                }
            });
        }

    </script>

<?php } ?>
<style type="text/css">
    div#outermapdiv{
        position:relative;
        float:left;
    }
    div#map_container{
        z-index:1000;
        position:relative;
        background:#000;
        width:<?php echo $this->config['mapwidth']; ?>px;
        height:<?php echo $this->config['mapheight']; ?>px;
    }
    div#map{
        width:<?php echo $this->config['mapwidth']; ?>px;
        display:none;
    }
    div#map{position:fixed;top:100px !important;margin:0px auto;border-radius: 5px;padding:10px;z-index: 9999;}
</style>
<?php $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://"; ?>
<script type="text/javascript" src="<?php echo $protocol; ?>maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
    function loadMap() {
        var default_latitude = document.getElementById('default_latitude').value;
        var default_longitude = document.getElementById('default_longitude').value;

        var latitude = document.getElementById('filter_latitude').value;
        var longitude = document.getElementById('filter_longitude').value;

        if (!isNaN(latitude) && !isNaN(longitude)) {
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
        document.getElementById('filter_latitude').value = marker.position.lat();
        document.getElementById('filter_longitude').value = marker.position.lng();

        document.getElementById('filter_latitude').style.color = "black";
        document.getElementById('filter_longitude').style.color = "black";

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
                    document.getElementById('filter_latitude').value = marker.position.lat();
                    document.getElementById('filter_longitude').value = marker.position.lng();

                } else {
                    alert("Geocode was not successful for the following reason: " + status);
                }
            });
        });
//document.getElementById('map_container').innerHTML += "<a href='Javascript hidediv();'><?php echo JText::_('Close Map'); ?></a>";
    }
    function showdiv() {
        document.getElementById('map').style.display = 'block';
        document.getElementById('map').style.visibility = 'visible';
        document.getElementById('black_wrapper_map').style.display = 'block';
    }
    function hidediv() {
        document.getElementById('map').style.display = 'none';
        document.getElementById('black_wrapper_map').style.display = 'none';
    }

    function checkmapcooridnate() {
        var latitude = document.getElementById('filter_latitude').value;
        var longitude = document.getElementById('filter_longitude').value;
        var radius = document.getElementById('filter_radius').value;
        if (latitude != 'Latitude' && longitude != 'Longitude') {
            if (radius != 'Coordinates Radius') {
                this.form.submit();
            } else {
                alert('<?php echo JText::_("Please enter the coordinate radius"); ?>');
                return false;
            }
        }

    }
</script>
