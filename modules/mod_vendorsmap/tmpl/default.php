<?php
/**
 * @subpackage  mod_vendorsmap
 * @copyright   Copyright (C) 2010 - 2016 Adrien ROUSSEL Nordmograph.com , Inc. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
JHtml::_('bootstrap.tooltip');
echo '<div id="mapajaxloader"><img src="'.$juri.'components/com_vmvendor/assets/img/maploader.gif" alt="loading" width="16" height="11" /></div>';





	echo '<div id="integrations"  style="display:none">';	
	echo '<table><tr>';
	echo '<td  title="'.JText::_('COM_VMVENDOR_MAP_VENDORS').'" class="'.$tipclass.'">
		<input checked type="checkbox" class="integrations_box" name="integrations" id="user" value="user"><label for="user" onmouseover="bounceIntegrationMarkers(\'type\',\'user\',1);"  onmouseout="bounceIntegrationMarkers(\'type\',\'user\',0);">
		<i class="vmv-icon-user"></i>
		</label>
		
		
		</td>';
	
		echo '<td title="'.JText::_('COM_VMVENDOR_MAP_CLUSTERING').'" class="'.$tipclass.' anx_integ">
		<input checked type="checkbox" class="integrations_box" name="integrations" id="clustering" value="clustering" ><label for="clustering"  >
		<i class="vmv-icon-wifi"></i>
		</label>
		</td>';

	echo '</tr></table></div>';	


if($addmodule!='')
{
	$embed_mods = JModuleHelper::getModules( $addmodule );
	if(count($embed_mods)>0)
	{
		echo '<div id="embed_modules">';
		foreach ($embed_mods as $embed_mod ){	
			echo '<h3 class="module-title">'.$embed_mod->title.'</h3>';
			echo '<div>'.JModuleHelper::renderModule($embed_mod).'</div>';
		}
		if($closemodule)
		{
			echo '<div id="close_embedmodule"><span onclick="jQuery(\'#embed_modules\').hide();" class="vmv-icon-cancel closebutton '.$tipclass.'"  title="'.JText::_('COM_VMVENDOR_MAP_CLOSE').'"></span></div>';	
		}
		echo '</div>';
	}
}

echo '<a name="map_top"></a>
<div id="map-canvas" >
<div id="map_loader">';

echo '<img src="'.$juri.'components/com_vmvendor/assets/img/maploader.gif" alt="loader" width="16" height="11" /> '.JText::_('COM_VMVENDOR_MAP_LOCATINGYOU').'</div>';
echo '</div><a name="map_bot"></a>';
if( $placeapifield )
	echo '<input id="pac-input" class="geomcontrols" type="text" placeholder="'.JText::_('COM_VMVENDOR_MAP_SEARCHBOX').'" style="display:none;" >';
	
if( $marker_search )
	echo '<input id="marker-search" class="geomcontrols auto" type="text" placeholder="'.JText::_('COM_VMVENDOR_MAP_MARKERSEARCH').'" style="display:none;" >';
	echo '<div id="directionspanel" class="directionspanel">';
	$default_modeoftravel='DRIVING';
	
	echo '<div class="closepanel" >
		<i class="vmv-icon-cancel '.$tipclass.'" title="'.JText::_('COM_VMVENDOR_MAP_CLOSEDIRPANEL').'" onclick="document.getElementById(\'directionspanel\').style.display=\'none\';directionsDisplay.setDirections({routes: []});"></i>
		</div>';

		echo '<input type="hidden" id="routelat" name="routelat" /><input type="hidden" id="routelng" name="routelng" />';
			echo '<div id="modeoftravel">
			<select id="mode" onchange="calcRoute(document.getElementById(\'routelat\').value,document.getElementById(\'routelng\').value,this.value);" class="form-control">';
		  	echo '<option value="DRIVING" ';
		  	if($default_modeoftravel=='DRIVING')
				echo 'selected';
		  	echo '>'.JText::_('COM_VMVENDOR_MAP_DRIVING').'</option>';
		  	echo '<option value="WALKING" ';
		  	if($default_modeoftravel=='WALKING')
				echo 'selected';
		  	echo '>'.JText::_('COM_VMVENDOR_MAP_WALKING').'</option>';
		  	echo '<option value="BICYCLING" ';
		  	if($default_modeoftravel=='BICYCLING')
				echo 'selected';
		  	echo '>'.JText::_('COM_VMVENDOR_MAP_BICYCLING').'</option>';
			echo '</select>';
		echo '</div>';

		echo '<div style="clear:both"></div>';
		echo '<style>#map-canvas{width:'.$width.';height:'.$height.';}</style>';
		if($fullscreen_button)
{
	echo '<div id="fs_toggler"><a class="btn btn-mini btn-primary '.$tipclass.'" id="fullscreen_toggler" title="'.JText::_('COM_VMVENDOR_MAP_TOGGLEFULLSCREEN').'"><i class="vmv-icon-screen"></i></a></div>';
}