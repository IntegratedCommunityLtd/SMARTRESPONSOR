<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2016 Adrien Roussel
 * @license : GNU/GPL
 * @Website : https://www.nordmograph.com
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
$user 			= JFactory::getUser();
$app 			= JFactory::getApplication();
$db 			= JFactory::getDBO();
$juri 			= JURI::base();
$doc 			= JFactory::getDocument();
if (!class_exists( 'VmConfig' ))
	require JPATH_ADMINISTRATOR .  '/components/com_virtuemart/helpers/config.php';	
$use_as_catalog 	=  VmConfig::get('use_as_catalog');
$doc->addStyleSheet($juri.'components/com_vmvendor/assets/css/dashboard.css');
$doc->addStyleSheet($juri.'components/com_vmvendor/assets/js/sweetalert/sweetalert2.css');
$doc->addScript($juri.'components/com_vmvendor/assets/js/sweetalert/sweetalert2.min.js');
echo '<link rel="stylesheet" href="'.$juri.'components/com_vmvendor/assets/css/fontello.css">';
$cparams 					= JComponentHelper::getParams('com_vmvendor');
$load_bootstrap_css		= $cparams->get('load_bootstrap_css', 0);
if($load_bootstrap_css)
	$doc->addStyleSheet( $juri.'media/jui/css/bootstrap.min.css');

$naming 					= $cparams->get('naming', 'username');
$profileman 				= $cparams->get('profileman');
$profileitemid				= $cparams->get('profileitemid');
$date_display				= $cparams->get('date_display','Y.m.d');
$customercontactform		= $cparams->get('customercontactform');
$show_postalinfo 			= $cparams->get('show_postalinfo',1);
$show_worldmapstats			= $cparams->get('show_worldmapstats',1);
$allow_orderstatuschange	= $cparams->get('allow_orderstatuschange',1);
$manage_reviews 			= $cparams->get('manage_reviews',1);
$tax_mode					= $cparams->get('tax_mode',0);
$shipment_mode				= $cparams->get('shipment_mode',0);
$enable_vendorpoints		= $cparams->get('enable_vendorpoints',1);
$modaltype					= $cparams->get('modaltype','s');
$tipclass					= $cparams->get('tipclass','');
$disable_points				= $cparams->get('disable_points',0);
$currency_symbol 			= $this->main_currency[0];
$currency_positive_style	= $this->main_currency[1];
$currency_decimal_place 	= $this->main_currency[2];
$currency_decimal_symbol 	= $this->main_currency[3];
$currency_thousands 		= $this->main_currency[4];
$vmvendorprofile_itemid 	= $this->profile_itemid;

$Itemid = $app->input->getInt('Itemid');
$vendor_profile_url = JRoute::_('index.php?option=com_vmvendor&view=vendorprofile&userid='.$user->id.'&Itemid='.$vmvendorprofile_itemid);
		
echo '<h1>'.JText::_('COM_VMVENDOR_DASHBOARD_TITLE').'</h1>';
echo '<div class="vmvendor-toolbar btn-group" >
<a href="'.$vendor_profile_url.'"  class="btn btn-primary '.$tipclass.'" title="'.JText::_( 'COM_VMVENDOR_DASHBOARD_VENDORPROFILE' ).'">
<i class="vmv-icon-user"></i></a>
<a href="'.$vendor_profile_url.'" title="'.JText::_( 'COM_VMVENDOR_DASHBOARD_MYPRODUCTS' ).'"  class="btn  btn-primary '.$tipclass.'">
<i class="vmv-icon-list"></i></a></div>';
	
$top_modules = JModuleHelper::getModules('vmv-dashboard-top');
foreach ($top_modules as $top_module)
{	
	echo '<h3 class="module-title">'.$top_module->title.'</h3>';
	echo JModuleHelper::renderModule($top_module);
}
//////////////// tabs navigation header
echo JHtml::_('bootstrap.startTabSet', 'dashboardTab', $this->tabsOptions );

$confirm_alert="function sweetConfirmDelete(it){
				swal({   title: '', 
				  	text: '".JText::_( 'COM_VMVENDOR_DASHBOARD_DELETEAREYOUSURE' )."', 
				  	type: 'warning',
					showCancelButton: true, 
					confirmButtonColor: 'green',   
					cancelButtonColor: '#d33',   
					confirmButtonText: '".JText::_('JYes')."',
					cancelButtonText: '".JText::_('JNo')."'   
					},
					function(confirm){  
						if(confirm)
							it.submit();
					})
			}";
$doc->addScriptDeclaration( $confirm_alert );					
$statusupdate_alert="function sweetStatusUpdate(it){
						swal({   title: '".JText::_( 'COM_VMVENDOR_DASHBOARD_CONFIRMSTATUSUPDATE1' )."', 
						  text: '".JText::_( 'COM_VMVENDOR_DASHBOARD_CONFIRMSTATUSUPDATE2' )."', 
						  type: 'warning',
						   showCancelButton: true, 
						  confirmButtonColor: 'green',   
						  cancelButtonColor: '#d33',   
						  confirmButtonText: '".JText::_('JYes')."',
						  cancelButtonText: '".JText::_('JNo')."'
							},
							function(confirm){  
								if(confirm)
									it.submit();
								else
									it.neworderstatus.value = 'C';
							})
						}";
$doc->addScriptDeclaration( $statusupdate_alert );		     
if(!$use_as_catalog)
{
	echo JHtml::_('bootstrap.addTab', 'dashboardTab', 'mysells', '<i class="vmv-icon-cart"></i> '.JText::_('COM_VMVENDOR_DASHBOARD_MYSALES') , 'class="active"' );
	echo '<table class="table table-striped table-condensed table-hover table-bordered">
	<thead><tr style="text-align:center;">
	<th  colspan="3">'.JText::_( 'COM_VMVENDOR_DASHBOARD_LATEST_ORDERS' ).'</th>
	<th  colspan="8">'.JText::_( 'COM_VMVENDOR_DASHBOARD_CUSTOMERS' ).'</th>
	</tr>';
	echo '<tr>
	<th>'.JText::_( 'COM_VMVENDOR_DASHBOARD_ORDER' ).' / ('.JText::_( 'COM_VMVENDOR_DASHBOARD_DATE' ).')</th>
	<th>'.JText::_( 'COM_VMVENDOR_DASHBOARD_ITEM' ).'</th>
	<th>'.JText::_( 'COM_VMVENDOR_DASHBOARD_QTYXPRICE' ).'</th>
	<th>'.JText::_( 'COM_VMVENDOR_DASHBOARD_NAME' ).'</th>';
	if($show_postalinfo)
	{
		echo '<th>'.JText::_( 'COM_VMVENDOR_DASHBOARD_COMPANY' ).'</th>
		<th>'.JText::_( 'COM_VMVENDOR_DASHBOARD_ADDRESS' ).'</th>
		<th>'.JText::_( 'COM_VMVENDOR_DASHBOARD_ZIPCITY' ).'<br />'.JText::_( 'COM_VMVENDOR_DASHBOARD_STATE' ).'</th>
		<th>'.JText::_( 'COM_VMVENDOR_DASHBOARD_COUNTRY' ).'</th>';
	}
	if($allow_orderstatuschange)
		echo '<th>'.JText::_( 'COM_VMVENDOR_DASHBOARD_ORDERITEMSTATUS' ).'</th>';
	echo '<th></th>
	</tr></thead><tbody >';
	
	foreach($this->mysells as $sell)
	{
		echo '<tr>';
		////////////////////// Order 
		$date = new JDate($sell->created_on);
		$date = $date->toUnix();
		echo '<td >#'.$sell->order_number.' ('.JHTML::_('date', $date, JText::_($date_display)).')';
		
		if($sell->customer_note)
		{
			echo ' <div title="'.JText::_( 'COM_VMVENDOR_DASHBOARD_CUSTNOTE' ).': '.$sell->customer_note.'" class="'.$tipclass.'">
			<i class="vmv-icon-warning"></i>
			</div>';
		}
		echo '</td>';
		
		$item_url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$sell->virtuemart_product_id.'&virtuemart_category_id='.$sell->virtuemart_category_id.'&Itemid='.$this->vmitemid);
		echo '<td title="Product Sku::'.$sell->order_item_sku.'" class="'.$tipclass.'"><a href="'.$item_url.'">'.$sell->order_item_name.'</a></td>';
		echo '<td>'.$sell->product_quantity.'x';
		
		$res = number_format((float)$sell->product_item_price,$currency_decimal_place,$currency_decimal_symbol,$currency_thousands);
		$search = array('{sign}', '{number}', '{symbol}');
		$replace = array('+', $res, $currency_symbol);
		$formattedRounded_price = str_replace ($search,$replace,$currency_positive_style);
		
		echo $formattedRounded_price.'</td>';
		/////////////////////////////// Customer
		$sell_title = $sell->title;
		if(!$sell_title) $sell_title = $sell->guest_title;
		$sell_first_name = $sell->first_name;
		if(!$sell_first_name) $sell_first_name = $sell->guest_first_name;
		$sell_middle_name = $sell->middle_name;
		if(!$sell_middle_name) $sell_middle_name = $sell->guest_middle_name;
		$sell_last_name = $sell->last_name;
		if(!$sell_last_name) $sell_last_name = $sell->guest_last_name;
		$sell_company = $sell->company;
		if(!$sell_company) $sell_company = $sell->guest_company;
		$sell_address_1 = $sell->address_1;
		if(!$sell_address_1) $sell_address_1 = $sell->guest_address_1;
		$sell_address_2 = $sell->address_2;
		if(!$sell_address_2) $sell_address_2 = $sell->guest_address_2;
		$sell_zip = $sell->zip;
		if(!$sell_zip) $sell_zip = $sell->guest_zip;
		$sell_city = $sell->city;
		if(!$sell_city) $sell_city = $sell->guest_city;
		$sell_state_name = $sell->state_name;
		if(!$sell_state_name) $sell_state_name = $sell->guest_state_name;
		$sell_country_name = $sell->country_name;
		//if(!$sell_country_name) $sell_country_name = $sell->guest_country_name;
		if(!$sell_country_name) $sell_country_name = '';
		$sell_phone_1 = $sell->phone_1;
		if(!$sell_phone_1) $sell_phone_1 = $sell->guest_phone_1;
		$sell_phone_2 = $sell->phone_2;
		if(!$sell_phone_2) $sell_phone_2 = $sell->guest_phone_2;
		
		$cust_naming = ucfirst($sell_title).' '.ucfirst($sell_first_name).' '.ucfirst($sell_middle_name).' '.ucfirst($sell_last_name);
		echo '<td>'.$cust_naming.'</td>';
		if($show_postalinfo)
		{
			echo '<td>'.$sell_company.'</td>';
			echo '<td>'.$sell_address_1.' '.$sell_address_2.'</td>';
			echo '<td>';
			if($sell_zip)
				echo $sell_zip.'<br />';
			echo $sell_city;
			if($sell_state_name)
				echo '<br />'.$sell_state_name;
			echo '</td>
			<td>'.$sell_country_name.'</td>';
		}
		
		if($allow_orderstatuschange)
		{
			echo '<td>';
			if($sell->payment_name)
				echo $sell->payment_name.'<br />';
			if($sell->shipment_name)
				echo $sell->shipment_name.'<br />';
			if($sell->order_status =='C'  )
			{
				$q = "SELECT `customfield_params` FROM `#__virtuemart_product_customfields` 
				WHERE `customfield_value`='st42_download' AND `virtuemart_product_id`='".$sell->virtuemart_product_id."' "; 
				// check if item has a forsale file
				$db->setQuery($q);
				$hasfile = $db->loadResult();
				if(!$hasfile)
				{
					echo '<div class="badge badge-success" style="margin-left:auto"><i class="vmv-icon-warning"></i></div>
					<form method="POST" name="statusform'.$sell->virtuemart_order_item_id.'" 
					id="statusform'.$sell->virtuemart_order_item_id.'" >
					<select name="neworderstatus" 
					onchange="sweetStatusUpdate( document.getElementById(\'statusform'.$sell->virtuemart_order_item_id.'\')) ;" >
					<option value="C" selected="selected">'.JText::_('COM_VMVENDOR_DASHBOARD_CONFIRMED').'</option>
					<option value="S" >'.JText::_('COM_VMVENDOR_DASHBOARD_SHIPPED').'</option>
					<option value="X" >'.JText::_('COM_VMVENDOR_DASHBOARD_CANCELED').'</option>
					</select>';
					echo ' <input type="hidden" name="orderitemid" value="'.$sell->virtuemart_order_item_id.'" />
							<input type="hidden" name="saleordernumber" value="'.$sell->order_number.'" />
							<input type="hidden" name="option" value="com_vmvendor" />
							<input type="hidden" name="controller" value="dashboard" />
							<input type="hidden" name="task" value="updateorderstatus" />
							</form>';
				}
				else
					echo '<div class="badge badge-success">'.JText::_('COM_VMVENDOR_DASHBOARD_CONFIRMED').'';
			}
			elseif($sell->order_status =='S')
				echo '<div class="badge badge-success">'.JText::_('COM_VMVENDOR_DASHBOARD_SHIPPED').'</div>';
			elseif($sell->order_status =='X')
				echo '<div class="badge badge-danger">'.JText::_('COM_VMVENDOR_DASHBOARD_CANCELED').'</div>';
			elseif($sell->order_status =='P')
				echo '<div class="badge badge-warning">'.JText::_('COM_VMVENDOR_DASHBOARD_PENDING').'</div>';
			elseif($sell->order_status =='R')
				echo '<div class="">'.JText::_('COM_VMVENDOR_DASHBOARD_REFUNDED').'</div>';
			elseif($sell->order_status =='U')
				echo '<div class="badge badge-warning">'.JText::_('COM_VMVENDOR_DASHBOARD_CONFBYSHOPPER').'</div>';
			else
				echo $sell->order_status;		
			echo '</td>';	
		}
		
		echo '<td>';
		if($sell->virtuemart_user_id )
		{
			if($profileman=='cb')
				$profile_url =JRoute::_('index.php?option=com_comprofiler&task=userProfile&user='.$sell->virtuemart_user_id.'&Itemid='.$profileitemid);
			elseif($profileman=='js')
				$profile_url = JRoute::_('index.php?option=com_community&view=profile&userid='.$sell->virtuemart_user_id.'&Itemid='.$profileitemid);
			elseif($profileman=='es'){
				$db 			= JFactory::getDBO();
				$q = "SELECT username FROM #__users WHERE id ='".$sell->virtuemart_user_id."' "	;
				$db->setQuery($q);
				$username = $db->loadResult();
	
				$profile_url = JRoute::_('index.php?option=com_easysocial&view=profile&id='.$sell->virtuemart_user_id.':'.$username.'&Itemid='.$profileitemid);
			}
			
			if($profileman!='0')	
				echo '<div><a href="'.$profile_url.'"  title="'.JText::_( 'COM_VMVENDOR_DASHBOARD_VISITPROFILE' ).'" class="btn btn-mini btn-default '.$tipclass.'">
				<i class="vmv-icon-user" ></i></a></div>';
		}
		if($profileman=='js' && $customercontactform == 2 && $sell->virtuemart_user_id !='0' && $sell->virtuemart_user_id )
		{
			require_once JPATH_BASE . '/components/com_community/libraries/core.php';
			CWindow::load();
		
			echo  '<a href="javascript:" onclick="joms.api.pmSend('.$sell->virtuemart_user_id.');" 
			class="btn btn-small btn-mini btn-default '.$tipclass.'" title="'.JText::_( 'COM_VMVENDOR_DASHBOARD_PMCUSTOMER' ).'">
			<i class="vmv-icon-mail"></i></a>';
		}
		elseif($profileman=='es' && $customercontactform == 2 && $sell->virtuemart_user_id !='0' && $sell->virtuemart_user_id )
		{
			echo  '<a href="javascript:void(0)" data-es-conversations-compose 
			data-es-conversations-id="'.$sell->virtuemart_user_id.'" 
			title="'.JText::_( 'COM_VMVENDOR_DASHBOARD_PMCUSTOMER' ).'" 
			class="btn btn-mini btn-default btn-es btn-message '.$tipclass.'">
			<i class="vmv-icon-mail"></i></a>';
		}
		else
		{
			$iframe_url = 'index.php?option=com_vmvendor&view=mailcustomer&orderitem_id='.$sell->virtuemart_order_item_id.'&customer_userid='.$sell->virtuemart_user_id.'&tmpl=component';
			if($modaltype=='b')
			{
				$name = "mailcustomer";
				$html = '<a href="#modal-' . $name.'" data-toggle="modal" class="btn btn-mini" 
				title="'.JText::_('COM_VMVENDOR_VENDORLINK_ASK'). '"><i class="vmv-icon-mail"></i></a>';
				$params = array();
				$params['title']  = JText::_('COM_VMVENDOR_DASHBOARD_EMAILCUSTOMER').' '.$cust_naming;
				$params['url']    = $iframe_url ;
				$params['height'] = "400";
				$params['width']  = "100%";
				$footer='';
				$html .=JHtml::_('bootstrap.renderModal', 'modal-' . $name, $params, $footer);
			}
			elseif($modaltype=='j')
			{
				$html = '<a class="btn btn-default btn-small btn-mini modal '.$tipclass.'" 
				title="'.JText::_('COM_VMVENDOR_DASHBOARD_EMAILCUSTOMER').'" 
				href="'.JRoute::_( $iframe_url ).'" 
				rel="{handler: \'iframe\', size: {x: 550, y: 500}}">
				<i class="vmv-icon-mail" ></i></a> ';
			}
			elseif($modaltype=='s')
			{
				$emailcust = "function emailcust(url){
							swal({ 
								  html:	'<iframe src=\"'+url+'\" id=\"mailcustomer_iframe\" >' +
									'</iframe>',
									width: 600,
									padding:0,
								  showCloseButton: false,
								  showConfirmButton: false,
								  showCancelButton: false
								 
								})
							}";
				$doc->addScriptDeclaration($emailcust);
				$html = '<a class="btn btn-default btn-small btn-mini  '.$tipclass.'" 
				title="'.JText::_('COM_VMVENDOR_DASHBOARD_EMAILCUSTOMER').'" onclick="emailcust(\''.$iframe_url.'\')" >
				<i class="vmv-icon-mail" ></i></a> ';
			}

			echo $html ;		
		}
			
		if($sell_phone_1)
			echo '<div title="'.$sell_phone_1.'" class=" btn btn-mini btn-default '.$tipclass.'"><i class="vmv-icon-phone"></i></div>';
		if($sell_phone_2)
			echo '<div title="'.$sell_phone_2.'" class=" btn btn-mini btn-default '.$tipclass.'"><i class="vmv-icon-phone"></i></div>';	
			
		echo '</td>
		</tr>';
	}
	echo '</tbody></table>
	
	<div class="pagination" >';
	echo $this->pagination->getResultsCounter();
	echo $this->pagination->getPagesLinks();
	echo $this->pagination->getPagesCounter();
	echo '</div>';
	echo JHtml::_('bootstrap.endTab');
}
if ( !$use_as_catalog && $enable_vendorpoints)
{
	echo JHtml::_('bootstrap.addTab', 'dashboardTab', 'mypoints', '<i class="vmv-icon-star"></i> '.JText::_('COM_VMVENDOR_DASHBOARD_MYPOINTSTITLE')  );
	
	if ( $use_aup=0 && file_exists( JPATH_SITE.'/components/com_altauserpoints/helper.php' ) )
	{
		require_once ($api_AUP);
		$listActivity = AltaUserPointsHelper::getListActivity('VMVendor', $user->id, $app->getCfg('list_limit') );
		$ref = AltaUserPointsHelper::getAnyUserReferreID($user->id);
		echo '<div id="points_div">
		<h3>'.JText::_('COM_VMVENDOR_DASHBOARD_POINTS').' '.AltaUserPointsHelper::getCurrentTotalPoints($ref).'</h3>
		</div>';
		if(count($listActivity) >0)
		{
			echo '<table class="table table-striped table-condensed table-hover table-bordered">
			<thead><tr >
			<th width="15%">'. JText::_( 'COM_VMVENDOR_DASHBOARD_AUP_DATE' ).'</th>
			<th width="30%">'.JText::_( 'COM_VMVENDOR_DASHBOARD_AUP_ACTION' ).'</th>
			<th >'.JText::_( 'COM_VMVENDOR_DASHBOARD_AUP_AMOUNT' ).'</th>
			<th width="70%">'.JText::_( 'COM_VMVENDOR_DASHBOARD_AUP_DETAIL' ).'</th>
			</tr></thead>
			<tbody>';
			$i=0;
			foreach ( $listActivity as $activity ) {
				echo '<tr><td>';
				echo  '<span style="color:#333;">'.JHTML::_('date', $activity->insert_date, JText::_($date_display)).'</span><br /><span style="color:#777;font-style:oblique;">'.JHTML::_('date', $activity->insert_date, JText::_('H:i:s')).'</span>';
					$color = $activity->points>0 ? "#009900" : ($activity->points<0 ? "#ff0000" : ($activity->points==0.00 ? "#777" : "#777"));
				echo '</td><td >';
				echo  JText::_( $activity->rule_name );
				echo '</td><td style="text-align: right; color:'. $color .';">';
				echo  $activity->points;
				echo '&nbsp;&nbsp;</td><td  style="color:#777;">';
				echo  $activity->datareference;
				echo '</td></tr>';
			}
			echo '</tbody></table>';
		} 
	}
	else
	{
		$listActivity 	= VmvendorModelDashboard::getPointsDetails( $app->getCfg('list_limit') );
		$mypoint_stotal = VmvendorModelDashboard::getVendorPoints( );
		echo '<div id="points_div">
		<h3>'.JText::_('COM_VMVENDOR_DASHBOARD_POINTS').' <span class="badge">'.$mypoint_stotal.'</span></h3>		
		<a class="btn btn-primary btn-mini btn-small" href="'.JRoute::_('index.php?option=com_vmvendor&view=withdrawpoints').'"
		><i class="vmv-icon-wallet"></i>'.JText::_('COM_VMVENDOR_DASHBOARD_WITHDRAWPOINTS').'</a>		
		</div><div style="clear:both"></div>';
		if(count($listActivity) >0)
		{
			echo '<table class="table table-striped table-condensed table-hover table-bordered"><thead>
			<tr ><th width="15%">'.JText::_( 'COM_VMVENDOR_DASHBOARD_AUP_DATE' ).'</th>
			<th>'. JText::_( 'COM_VMVENDOR_DASHBOARD_AUP_AMOUNT' ).'</th>
			<th>'.JText::_( 'COM_VMVENDOR_DASHBOARD_AUP_DETAIL' ).'</th>
			</tr></thead><tbody>';
			$i=0;
			foreach ( $listActivity as $activity ) {
				echo '<tr><td>';
				echo  '<span>'.JHTML::_('date', $activity->insert_date, JText::_($date_display)).'</span><br /><span style="color:#777;font-style:oblique;">'.JHTML::_('date', $activity->insert_date, JText::_('H:i:s')).'</span>';
					$badge_class = $activity->points>0 ? "success" : ($activity->points<0 ? "warning" : ($activity->points==0.00 ? "success" : "success"));
				echo '</td><td  style="text-align:right"> <span class="badge badge-'. $badge_class .'">';
				echo  $activity->points;
				echo '</span></td><td>';
				echo  $activity->datareference;
				echo '</td></tr>';
			}
			echo '</tbody></table>';
		} 
	}
	
	
	echo JHtml::_('bootstrap.endTab');
}
if(!$use_as_catalog)
{
	echo JHtml::_('bootstrap.addTab', 'dashboardTab', 'mystats', '<i class="vmv-icon-chart"></i> '.JText::_('COM_VMVENDOR_DASHBOARD_STATISTICS')  );
		$start_date = $app->input->get('start_date');
				if(!$start_date)
					$start_date= date('Y-m').'-01';
				$end_date = $app->input->get('end_date');
				if(!$end_date)
					$end_date= date('Y-m-d');
				$time_unit = $app->input->get('time_unit');
				if(!$time_unit)
					$time_unit = 'days';
				$subject = $app->input->get('subject');
				if(!$subject)
					$subject = 'revenue';
		
		echo '<div >';
		echo '<form method="POST" class="form-inline"><div class="form-group">';

		echo '<div class="form-group" style="padding:0 5px;">'.JText::_( 'COM_VMVENDOR_DASHBOARD_STATSSTARTDATE' ).' '.JHTML::_('calendar', $start_date, 'start_date', 'start_date',  '%Y-%m-%d', array('class'=>'inputbox form-control', 'size'=>'5',  'maxlength'=>'10')).'</div>'; 
		echo '<div class="form-group" style="padding:0 5px;">'.JText::_( 'COM_VMVENDOR_DASHBOARD_STATSENDDATE' ).' '. JHTML::_('calendar', $end_date, 'end_date', 'end_date',  '%Y-%m-%d', array('class'=>'inputbox form-control', 'size'=>'5',  'maxlength'=>'10')).'</div>'; 
		
		echo '<div class="form-group" style="padding:0 5px;" ><select id="time_unit" name="time_unit" class="form-control">
		<option value="days" ';
		if($time_unit == 'days')
			echo 'selected ';
		echo '>'.JText::_('COM_VMVENDOR_DASHBOARD_DAYS').'</option>';
		echo '<option value="months" ';
		if($time_unit == 'months')
			echo 'selected ';
		echo '>'.JText::_('COM_VMVENDOR_DASHBOARD_MONTHS').'</option>';
		echo '<option value="years" ';
		if($time_unit == 'years')
			echo 'selected ';
		echo '>'.JText::_('COM_VMVENDOR_DASHBOARD_YEARS').'</option>';
		echo '</select></div>';
		echo '<div class="form-group" style="padding:0 5px;"><select id="subject" name="subject" class="form-control">';
		echo '<option value="revenue" ';
		if($subject == 'revenue')
			echo 'selected ';
		echo '>'.JText::_('COM_VMVENDOR_DASHBOARD_REVENUE').'</option>';
		echo '<option value="orders" ';
		if($subject == 'orders')
			echo 'selected ';
		echo '>'.JText::_('COM_VMVENDOR_DASHBOARD_ORDERS').'</option>
		</select></div>
		<div class="form-group" style="padding:0 5px;"><input type="submit" 
		value="' . JText::_( 'COM_VMVENDOR_DASHBOARD_DISPLAY' ) . '" class="btn btn-primary" /></div>
		</form></div>';
				
		if($time_unit == 'days')
		{
			$data = '';
			$countrydata = '';
			$total_revenue = 0;
			$total_orders = 0;
			$total_medium = 0; // revenue / orders
			$date1 = $start_date; 
			$date2 = $end_date;
			//Extraction des données
			list($annee1, $mois1, $jour1) = explode('-', $date1); 
			list($annee2, $mois2, $jour2) = explode('-', $date2);	 
			//Calcul des timestamp
			$timestamp1 = mktime(0,0,0,$mois1,$jour1,$annee1); 
			$timestamp2 = mktime(0,0,0,$mois2,$jour2,$annee2); 
			$daycount = abs($timestamp2 - $timestamp1)/86400; //Affichage du nombre de jour : 10.0416666667 au lieu de 10
			$countries = array();
			for( $i=0 ; $i <= $daycount ; $i++){
				$day_revenue = 0;
				$day_orders =  0;
				$day_timestp = $timestamp1 + ($i * 86400);
				$day_date = date('Y-m-d',$day_timestp);		
				$q = "SELECT voi.`product_quantity`, voi.`product_item_price` ,
				vo.`order_number` , vc.`country_name` 
					FROM `#__virtuemart_order_items` voi  
					LEFT JOIN `#__virtuemart_products` vp ON vp.`virtuemart_product_id` = voi.`virtuemart_product_id` 
					LEFT JOIN `#__virtuemart_vmusers` vv ON vv.`virtuemart_vendor_id` = vp.`virtuemart_vendor_id` 
					LEFT JOIN `#__virtuemart_orders` vo ON vo.`virtuemart_order_id` = voi.`virtuemart_order_id` 
					LEFT JOIN `#__virtuemart_userinfos` vu ON vu.`virtuemart_user_id` = vo.`virtuemart_user_id` 
					LEFT JOIN `#__users` u ON u.`id` = vo.`virtuemart_user_id` 
					LEFT JOIN `#__virtuemart_countries` vc ON vc.`virtuemart_country_id` = vu.`virtuemart_country_id` 
					LEFT JOIN `#__virtuemart_states` vs ON vs.`virtuemart_state_id` = vu.`virtuemart_state_id` 
					WHERE vv.`virtuemart_user_id` = '".$user->id."' AND (vo.`order_status`='C' OR vo.`order_status`='S') 
					AND SUBSTR(voi.`created_on` , 1, 10) = '".$day_date."'   ";
				$db->setQuery($q);
				$day_orderitems = $db->loadObjectList();
				$day_orders = count($day_orderitems);
				foreach($day_orderitems as $day_orderitem)
				{
					if($day_orderitem->country_name)
						array_push($countries , $day_orderitem->country_name);
					$day_revenue = $day_revenue +  ($day_orderitem->product_quantity * $day_orderitem->product_item_price);			
					
				}
				
				$total_revenue = $total_revenue + $day_revenue;
				$total_orders = $total_orders + $day_orders;
				if($subject=='revenue' && $day_orders>0)
						$data .= '[\''.$day_date.'\','.$day_revenue.','. $day_revenue / $day_orders.'],';
				
						
					if($subject=='orders')
						$data .= '[\''.$day_date.'\','.$day_orders.'],';
						
						
				if(!count($day_orderitems) && $subject=='revenue')
					$data .= '[\''.$day_date.'\',0,0],';
				elseif(!count($day_orderitems) && $subject=='orders')
					$data .= '[\''.$day_date.'\',0],';
			}
			if(count($countries))
				$countries =  array_count_values($countries) ;	
			$country_list ='';
			while (list($key, $value) = each($countries)) {
				$country_list .= '[\''.$key.'\', '.$value.'],';
			}
		}
		elseif($time_unit == 'months')
		{
			$data = '';
			$countrydata = '';
			$total_revenue = 0;
			$total_orders = 0;
			$total_medium = 0; // revenue / orders
			$date1 = $start_date; 
			$date2 = $end_date;
			//Extraction des données
			list($annee1, $mois1, $jour1) = explode('-', $date1);  // 2004 - 12 
			list($annee2, $mois2, $jour2) = explode('-', $date2);	//2008 - 7
			$d1 = strtotime($date1);
			$d2 = strtotime($date2);
			$min_date = min($d1, $d2);
			$max_date = max($d1, $d2);
			$monthcount = 0;
			while (($min_date = strtotime("+1 MONTH", $min_date)) <= $max_date)
			{
				$monthcount++;
			}
			$countries = array();
			$y = $annee1;
			$m = $mois1;
			for( $i=0 ; $i <= $monthcount ; $i++)
			{
				$month_revenue = 0;
				$month_orders =  0;
				$month_date = $y.'-'.sprintf("%02d", $m);		
				$q = "SELECT voi.`product_quantity`, voi.`product_item_price` ,
				vo.`order_number` , vc.`country_name` 
					FROM `#__virtuemart_order_items` voi  
					LEFT JOIN `#__virtuemart_products` vp ON vp.`virtuemart_product_id` = voi.`virtuemart_product_id` 
					LEFT JOIN `#__virtuemart_vmusers` vv ON vv.`virtuemart_vendor_id` = vp.`virtuemart_vendor_id` 
					LEFT JOIN `#__virtuemart_orders` vo ON vo.`virtuemart_order_id` = voi.`virtuemart_order_id` 
					LEFT JOIN `#__virtuemart_userinfos` vu ON vu.`virtuemart_user_id` = vo.`virtuemart_user_id` 
					LEFT JOIN `#__users` u ON u.`id` = vo.`virtuemart_user_id` 
					LEFT JOIN `#__virtuemart_countries` vc ON vc.`virtuemart_country_id` = vu.`virtuemart_country_id` 
					LEFT JOIN `#__virtuemart_states` vs ON vs.`virtuemart_state_id` = vu.`virtuemart_state_id` 
					WHERE vv.`virtuemart_user_id` = '".$user->id."' AND (vo.`order_status`='C' OR vo.`order_status`='S') 
					AND SUBSTR(voi.`created_on` , 1, 7) = '".$month_date."'   ";
				$db->setQuery($q);
				$month_orderitems = $db->loadObjectList();
				$month_orders = count($month_orderitems);
				foreach($month_orderitems as $month_orderitem){
					array_push($countries , $month_orderitem->country_name);
					$month_revenue = $month_revenue +  ($month_orderitem->product_quantity * $month_orderitem->product_item_price);			
					
				}
				
				$total_revenue = $total_revenue + $month_revenue;
				$total_orders = $total_orders + $month_orders;
				if($subject=='revenue' && $month_orders>0)
						$data .= '[\''.$month_date.'\','.$month_revenue.','. $month_revenue / $month_orders.'],';
				
						
					if($subject=='orders')
						$data .= '[\''.$month_date.'\','.$month_orders.'],';
						
						
				if(!count($month_orderitems) && $subject=='revenue')
					$data .= '[\''.$month_date.'\',0,0],';
				elseif(!count($month_orderitems) && $subject=='orders')
					$data .= '[\''.$month_date.'\',0],';
				$m++;
				if($m>12){
					$m=1;
					$y++;
				}
			}
			$countries =  array_count_values($countries) ;	
			$country_list ='';
			while (list($key, $value) = each($countries)) {
				$country_list .= '[\''.$key.'\', '.$value.'],';
			}
		}
		elseif($time_unit == 'years')
		{
			$data = '';
			$countrydata = '';
			$total_revenue = 0;
			$total_orders = 0;
			$total_medium = 0; // revenue / orders
			$date1 = $start_date; 
			$date2 = $end_date;
			//Extraction des données
			list($annee1, $mois1, $jour1) = explode('-', $date1);  // 2004 - 12 
			list($annee2, $mois2, $jour2) = explode('-', $date2);	//2008 - 7		
			$yearcount = $annee2 - $annee1 + 1;
			$countries = array();
			$y = $annee1;	
			for( $i=1 ; $i <= $yearcount ; $i++)
			{
				$year_revenue = 0;
				$year_orders =  0;
				$year_date = $y;		
				$q = "SELECT voi.`product_quantity`, voi.`product_item_price` ,
				vo.`order_number` , vc.`country_name` 
					FROM `#__virtuemart_order_items` voi  
					LEFT JOIN `#__virtuemart_products` vp ON vp.`virtuemart_product_id` = voi.`virtuemart_product_id` 
					LEFT JOIN `#__virtuemart_vmusers` vv ON vv.`virtuemart_vendor_id` = vp.`virtuemart_vendor_id` 
					LEFT JOIN `#__virtuemart_orders` vo ON vo.`virtuemart_order_id` = voi.`virtuemart_order_id` 
					LEFT JOIN `#__virtuemart_userinfos` vu ON vu.`virtuemart_user_id` = vo.`virtuemart_user_id` 
					LEFT JOIN `#__users` u ON u.`id` = vo.`virtuemart_user_id` 
					LEFT JOIN `#__virtuemart_countries` vc ON vc.`virtuemart_country_id` = vu.`virtuemart_country_id` 
					LEFT JOIN `#__virtuemart_states` vs ON vs.`virtuemart_state_id` = vu.`virtuemart_state_id` 
					WHERE vv.`virtuemart_user_id` = '".$user->id."' AND (vo.`order_status`='C' OR vo.`order_status`='S') 
					AND SUBSTR(voi.`created_on` , 1, 4) = '".$year_date."'   ";
				$db->setQuery($q);
				$year_orderitems = $db->loadObjectList();
				$year_orders = count($year_orderitems);
				foreach($year_orderitems as $year_orderitem){
					array_push($countries , $year_orderitem->country_name);
					$year_revenue = $year_revenue +  ($year_orderitem->product_quantity * $year_orderitem->product_item_price);			
					
				}
				
				$total_revenue = $total_revenue + $year_revenue;
				$total_orders = $total_orders + $year_orders;
				
				if($subject=='revenue' && $year_orders>0)
						$data .= '[\''.$year_date.'\','.$year_revenue.','. $year_revenue / $year_orders.'],';
				
						
					if($subject=='orders')
						$data .= '[\''.$year_date.'\','.$year_orders.'],';
						
						
				if(!count($year_orderitems) && $subject=='revenue')
					$data .= '[\''.$year_date.'\',0,0],';
				elseif(!count($year_orderitems) && $subject=='orders')
					$data .= '[\''.$year_date.'\',0],';
				$m++;
		
					$y++;
		
			}
			$countries =  array_count_values($countries) ;	
			$country_list ='';
			while (list($key, $value) = each($countries)) {
				$country_list .= '[\''.$key.'\', '.$value.'],';
			}
		}
		
	
		if($app->input->get('start_date'))
		{	
			$doc->addScript('https://www.google.com/jsapi');
			$chart_script = " google.load(\"visualization\", \"1\", {packages:[\"corechart\"]});
				   google.setOnLoadCallback(drawVisualization);
				   function drawVisualization() {
					var data = google.visualization.arrayToDataTable([ ";
					if($subject=='revenue')											  
					 $chart_script .= "['".ucfirst($time_unit)."', '".JText::_('COM_VMVENDOR_DASHBOARD_REVENUE')."','".JText::_('COM_VMVENDOR_DASHBOARD_AVERAGEPERORDER')."'],";
					 elseif($subject=='orders')											  
					 $chart_script .= "['".ucfirst($time_unit)."', '".JText::_('COM_VMVENDOR_DASHBOARD_ORDERS')."'],";
					  $chart_script .=$data."
					]);
					
					options = {
					  title: '".ucfirst($subject)."',
					
					  vAxis: {title: '".JText::_('COM_VMVENDOR_DASHBOARD_STATS_AMOUNT')."'}, ";
					  if($subject=='revenue')
					 $chart_script .= " hAxis: {title: '".JText::_( 'COM_VMVENDOR_DASHBOARD_STATS_TOTAL' )." ".$total_revenue." '},";
					 elseif($subject=='orders')	
					 $chart_script .= " hAxis: {title: '".JText::_( 'COM_VMVENDOR_DASHBOARD_STATS_TOTALORDERS' )." ".$total_orders." '},";
					 
					 $chart_script .= " seriesType: 'bars',
					  series: {1: {type: 'line'} }
					};
					var chart = new google.visualization.ComboChart(document.getElementById('columnchart_div'));
					chart.draw(data, options);
				  }";
			if($show_worldmapstats)
			{
				  $chart_script .="google.load(\"visualization\", \"1\", {packages:[\"geochart\"]});
				   google.setOnLoadCallback(drawRegionsMap);
				  function drawRegionsMap() {
					var data2 = google.visualization.arrayToDataTable([
					  ['Country', '".JText::_('COM_VMVENDOR_DASHBOARD_STATS_SALES')."'],
					  ".$country_list." 
					]);
					var options2 = {
						colorAxis: {minValue: 0,  colors: ['red', 'yellow', 'green']}
						};
					var chart2 = new google.visualization.GeoChart(document.getElementById('mapchart_div'));
					chart2.draw(data2, options2);
				 }";
			}
			$doc->addScriptDeclaration($chart_script);
		echo '<h3>'.JText::_('COM_VMVENDOR_DASHBOARD_COMBOCHARTTITLE').'</h3>
		<div id="columnchart_div" ></div>
		<div style="clear:both" > </div>';
		if($show_worldmapstats)
		{
			echo '<h3>'.JText::_('COM_VMVENDOR_DASHBOARD_GEOCHARTTITLE').'</h3>
			<div id="mapchart_div" ></div>
			<div style="clear:both" > </div>';
		}
	}
	echo '</div>';
	echo JHtml::_('bootstrap.endTab');
}
if ($manage_reviews)
{	
	$tr = '';
	$unpublished_count = 0;
	if(count($this->myreviews) >0)
	{	
		$publish_alert="function sweetPublishReview(it)
		{
			swal(
				{   title: '', 
			  text: '".JText::_( 'COM_VMVENDOR_DASHBOARD_PUBLISHAREYOUSURE' )."', 
			  type: 'warning',
			   showCancelButton: true, 
			  confirmButtonColor: 'green',   
			  cancelButtonColor: '#d33',   
			  confirmButtonText: '".JText::_('JYes')."',
			  cancelButtonText: '".JText::_('JNo')."'
				},
				function(confirm) 
				{  
					if(confirm)
						it.submit();
				}
			)
		}";
		$doc->addScriptDeclaration( $publish_alert );
	
		foreach($this->myreviews as $review)
		{
			$tr .='<tr><td>';
			if($review->published == 1)
			{ // allow deletion
				$review_status= JText::_( 'COM_VMVENDOR_DASHBOARD_PUBLISHED');
				$class= 'vmv-icon-ok';
			}
			else
			{
				$review_status= JText::_( 'COM_VMVENDOR_DASHBOARD_UNPUBLISHED');
				$class= 'vmv-icon-cancel';
				$unpublished_count ++;
			}
			$reviewed_item_url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$review->virtuemart_product_id.'&virtuemart_category_id='.$review->virtuemart_category_id.'&Itemid='.$this->vmitemid);
			$tr .='<i title="'.$review_status.'" alt="'.$review_status.'" class="'.$class.' '.$tipclass.'"  /></i>
			</td>
			<td>'.$review->created_on.'</td>
			<td><a href="'.$reviewed_item_url.'">'.$review->product_name.'</a></td>
			<td>'.$review->comment.'</td>
			<td>'.$review->review_rating.'/5</td>
			<td>'.ucfirst($review->$naming).'</td>
			<td>';
			if(!$review->published)
			{
				$tr .='<form method="POST" name="publish_review'.$review->virtuemart_rating_review_id.'" id="publish_review'.$review->virtuemart_rating_review_id.'" >
				<input type="hidden" name="task" value="publishreview" />
				<input type="image" src="'.$juri.'components/com_vmvendor/assets/img/good.png" name="image" title="'.JText::_( 'COM_VMVENDOR_DASHBOARD_PUBLISH').'" 
				alt="Publish" class="'.$tipclass.'" width="16" height="16" 
				onclick="sweetPublishReview(document.getElementById(\'publish_review'.$review->virtuemart_rating_review_id.'\') )" >
				<input name="review_id" type="hidden" value="'.$review->virtuemart_rating_review_id.'" />
				<input name="created_on" type="hidden" value="'.$review->created_on.'" />
				<input type="hidden" name="option" value="com_vmvendor" />
				<input type="hidden" name="controller" value="dashboard" />
				</form>';
			}				
			$tr .='<form method="POST" name="delete_review'.$review->virtuemart_rating_review_id.'" 
			id="delete_review'.$review->virtuemart_rating_review_id.'">
			<input type="hidden" name="task" value="deletereview" />
			<a title="'.JText::_( 'COM_VMVENDOR_DASHBOARD_DELETE').'" class="'.$tipclass.' btn btn-mini btn-danger" 
			onclick="sweetConfirmDelete( document.getElementById(\'delete_review'.$review->virtuemart_rating_review_id.'\') );"
			><i class="vmv-icon-trash"></i></a>
			<input name="review_id" type="hidden" value="'.$review->virtuemart_rating_review_id.'" />
			<input name="created_on" type="hidden" value="'.$review->created_on.'" />
			<input type="hidden" name="option" value="com_vmvendor" />
			<input type="hidden" name="controller" value="dashboard" />
			</form>
			</td></tr>';
		}
	}
	
	$panel_title =  JText::_( 'COM_VMVENDOR_DASHBOARD_REVIEWS' );
	if($unpublished_count>0)
	{
		//don't break the line:
		$panel_title .=' <span class="badge badge-warning '.$tipclass.'"  title="'.JText::_( 'COM_VMVENDOR_DASHBOARD_REVIEWS_UNPUBLISHEDCOUNT' ).'"  >'.$unpublished_count.'</span>';
	}
		
	echo JHtml::_('bootstrap.addTab', 'dashboardTab', 'productreviews', '<i class="vmv-icon-comment"></i> '.$panel_title );
	echo '<div>';
	if(count($this->myreviews)>0)
	{
		echo '<table class="table table-striped table-condensed table-hover table-bordered">
		<thead><tr>
		<th >'.JText::_( 'COM_VMVENDOR_DASHBOARD_STATUS' ).'</th>
		<th >'.JText::_( 'COM_VMVENDOR_DASHBOARD_DATE' ).'</th>
		<th >'.JText::_( 'COM_VMVENDOR_DASHBOARD_PRODUCT' ).'</th>
		<th >'.JText::_( 'COM_VMVENDOR_DASHBOARD_REVIEW' ).'</th>
		<th >'.JText::_( 'COM_VMVENDOR_DASHBOARD_RATING' ).'</th>
		<th >'.JText::_( 'COM_VMVENDOR_DASHBOARD_USER' ).'</th>
		<th ></th>
		</tr></thead>
		<tbody>'.$tr.'</tbody>
		</table>';
	}
	else
		echo JText::_( 'COM_VMVENDOR_DASHBOARD_NOREVIEWSYET');
	echo '</div>';

	echo JHtml::_('bootstrap.endTab');
}
if($tax_mode ==2 OR ($tax_mode ==1 && count($this->mytaxes)>0) )
{
	echo JHtml::_('bootstrap.addTab', 'dashboardTab', 'mytaxes', '<i class="vmv-icon-barcode"></i>% '.JText::_('COM_VMVENDOR_DASHBOARD_TAXES')  );
	if($tax_mode==2)
	{
			echo '<div class="right_addbutton" >
			<a href="'.JRoute::_('index.php?option=com_vmvendor&view=edittax&Itemid='.$Itemid).'" class="btn btn-mini btn-primary">
			<i class="vmv-icon-plus"></i> '.JText::_( 'COM_VMVENDOR_EDITTAX_FORM_NEWTAX' ).'</a></div>';
	}
		
	if(count($this->mytaxes)>0)
	{
		echo '<table class="table table-striped table-condensed table-hover table-bordered">
		<thead><tr>
		<th >'.JText::_( 'COM_VMVENDOR_DASHBOARD_TAX_NAME' ).'</th>
		<th >'.JText::_( 'COM_VMVENDOR_DASHBOARD_TAX_KIND' ).'</th>
		<th >'.JText::_( 'COM_VMVENDOR_DASHBOARD_TAX_VALUE' ).'</th>
		<th >'.JText::_( 'COM_VMVENDOR_DASHBOARD_TAXCAT' ).'</th>
		<th ></th>
		</tr></thead><tbody>';
		
		if (!class_exists( 'VmConfig' ))
			require(JPATH_ADMINISTRATOR .  '/components/com_virtuemart/helpers/config.php');
		VmConfig::loadConfig();
		foreach($this->mytaxes as $mytax)
		{
			echo '<tr>
			<td>'.JText::_( $mytax->calc_name );
			if($mytax->calc_descr)
				echo ' <i class="vmv-icon-info-sign '.$tipclass.'" title="'.JText::_( $mytax->calc_descr).'"></i>';
			echo '</td>
			<td>'.JText::_('COM_VMVENDOR_EDITTAX_FORM_TAXKIND_'.strtoupper($mytax->calc_kind).'').'</td>
			<td>'.$mytax->calc_value_mathop.' '.number_format($mytax->calc_value,2).'</td>
			<td>';
			
			
			$q ="SELECT COUNT(*) FROM `#__virtuemart_categories` WHERE `published`='1' ";
			$db->setQuery($q);
			$categories_total_count = $db->loadResult();
			
			$q ="SELECT vcl.`category_name` 
			FROM `#__virtuemart_categories_".VMLANG."` vcl 
			LEFT JOIN `#__virtuemart_calc_categories`  vcc ON vcc.`virtuemart_category_id` = vcl.`virtuemart_category_id` 
			LEFT JOIN `#__virtuemart_categories` vc ON vc.`virtuemart_category_id` = vcl.`virtuemart_category_id`
			WHERE vcc.`virtuemart_calc_id` = '".$mytax->virtuemart_calc_id."' 
			AND vc.`published`='1' ";
			$db->setQuery($q);
			$tax_categories = $db->loadObjectList();
			if(count($tax_categories)== $categories_total_count || count($tax_categories)==0)
			{
				echo JText::_( 'COM_VMVENDOR_DASHBOARD_TAX_CAT_ALL' );
			}
			else
			{
				$i = 0;
				foreach($tax_categories as $tax_category)
				{
					$i++;
					echo JText::_( $tax_category->category_name );
					if($i < count($tax_categories))
						echo' - ' ;
				}			
			}
			echo '</td>
			<td>';
			if(!$mytax->shared  && $this->vendor_id == $mytax->virtuemart_vendor_id)
			{	
				if($tax_mode==2)
				{
					echo ' <form name="deletetax'.$mytax->virtuemart_calc_id.'" id="deletetax'.$mytax->virtuemart_calc_id.'" method="post"   >
					<div class="btn-group">
					<a href="'.JRoute::_('index.php?option=com_vmvendor&view=edittax&taxid='.$mytax->virtuemart_calc_id.'&Itemid='.$Itemid).'" 
					class="btn btn-mini btn-primary '.$tipclass.'" title="'.JText::_( 'COM_VMVENDOR_DASHBOARD_TAX_EDIT' ).'">
					<i class="vmv-icon-edit"></i></a> ';
					echo '<input type="hidden" name="option" value="com_vmvendor">
					<input type="hidden" name="controller" value="dashboard">
					<input type="hidden" name="task" value="deletetax">
					<input type="hidden" name="delete_taxid" value="'.$mytax->virtuemart_calc_id.'">
					<input type="hidden" name="userid" value="'.$user->id.'">
					<a title="'.JText::_( 'COM_VMVENDOR_DASHBOARD_TAX_DELETE' ).'" class="btn btn-mini btn-danger '.$tipclass.'" 
					onclick="sweetConfirmDelete( document.getElementById(\'deletetax'.$mytax->virtuemart_calc_id.'\') );"
					>
					<i class="vmv-icon-trash"></i></a>
					</div>
					</form>';
				}
			}
			else
				echo '<i class="vmv-icon-users"></i> '.JText::_( 'COM_VMVENDOR_DASHBOARD_TAX_SHARED' );
			echo '</td>
			</tr>';
		}
		echo  '</tbody></table>';
	}
	echo JHtml::_('bootstrap.endTab');
}
if($shipment_mode)
{
	echo JHtml::_('bootstrap.addTab', 'dashboardTab', 'shipments', '<i class="vmv-icon-truck"></i> '. JText::_( 'COM_VMVENDOR_DASHBOARD_SHIPMENT_MANAGER')  );	
	echo '<div class="right_addbutton">
	<a href="'.JRoute::_('index.php?option=com_vmvendor&view=editshipment&jpluginid='.$this->shipmentextensionid.'&Itemid='.$Itemid).'" 
	class="btn btn-mini btn-primari"><i class="vmv-icon-plus"></i> '.JText::_('COM_VMVENDOR_DASHBOARD_SHIPMENT_ADD').'</a>
	</div>
	<div style="clear:both;"></div>
	<div id="vmv-myshipments">';
	$myshipments = VmvendorModelDashboard::getMyShipments();
	if(count($myshipments))
	{
		echo '<table class="table table-striped table-condensed table-hover table-bordered" ><thead>
		<tr>
		<th></th>
		<th>'.JText::_('COM_VMVENDOR_DASHBOARD_SHIPMENT_NAME').'</th>
		<th><i class="vmv-icon-globe '.$tipclass.'" title="'.JText::_('COM_VMVENDOR_DASHBOARD_SHIPMENT_COUNTRIES').'"></i></th>
		<th>'.JText::_('COM_VMVENDOR_DASHBOARD_SHIPMENT_ZIP').'</th>
		<th>'.JText::_('COM_VMVENDOR_DASHBOARD_SHIPMENT_WEIGHT').'</th>
		<th><i class="vmv-icon-cubes '.$tipclass.'" title="'.JText::_('COM_VMVENDOR_DASHBOARD_SHIPMENT_PRODUCTS').'"></i> </th>
		<th>'.JText::_('COM_VMVENDOR_DASHBOARD_SHIPMENT_AMOUNTS').'</th>
		<th>'.JText::_('COM_VMVENDOR_DASHBOARD_SHIPMENT_FREESHIPAMOUNT').'</th>
		<th><i class="vmv-icon-box '.$tipclass.'" title="'.JText::_('COM_VMVENDOR_DASHBOARD_SHIPMENT_PACKAGEFEE').'"></i> </th>
		<th>'.JText::_('COM_VMVENDOR_DASHBOARD_SHIPMENT_COST').'</th>
		<th>'.JText::_('COM_VMVENDOR_DASHBOARD_SHIPMENT_TAX').'</th>
		<th></th>
		</tr></thead><tbody>';
		function get_string_between($string, $start, $end)
		{
			$string = " ".$string;
			$ini = strpos($string,$start);
			if ($ini == 0) return "";
			$ini += strlen($start);
			$len = strpos($string,$end,$ini) - $ini;
			return substr($string,$ini,$len);
		}

		foreach($myshipments as $shipment)
		{

			$shipment_params = str_replace('"| ', '"|', $shipment->shipment_params);
			$shipm_countries 		= get_string_between( $shipment_params , 'countries="', '"|zip_start' );
			$shipm_zip_start 		= get_string_between( $shipment_params , 'zip_start="', '"|zip_stop' );
			$shipm_zip_stop 		= get_string_between( $shipment_params , 'zip_stop="', '"|weight_start' );
			$shipm_weight_start 	= get_string_between( $shipment_params , 'weight_start="', '"|weight_stop' );
			$shipm_weight_stop		= get_string_between( $shipment_params , 'weight_stop="', '"|weight_unit' );
			$shipm_weight_unit		= get_string_between( $shipment_params , 'weight_unit="', '"|nbproducts_start' );
			/// no double quote for 2v following lines why ? makes no sense , but it's that way in VM
			$shipm_nbproducts_start = get_string_between( $shipment_params , 'nbproducts_start=', '|nbproducts_stop' );
			$shipm_nbproducts_stop 	= get_string_between( $shipment_params , 'nbproducts_stop=', '|orderamount_start' );
			///
			$shipm_orderamount_start= get_string_between( $shipment_params , 'orderamount_start="', '"|orderamount_stop' );
			$shipm_orderamount_stop	= get_string_between( $shipment_params , 'orderamount_stop="', '"|shipment_cost' );
			$shipm_shipment_cost	= get_string_between( $shipment_params , 'shipment_cost="', '"|package_fee' );
			$shipm_package_fee 		= get_string_between( $shipment_params , 'package_fee="', '"|tax_id' );
			$shipm_tax_id 			= get_string_between( $shipment_params , 'tax_id="', '"|free_shipment' );
			$shipm_free_shipment 	= get_string_between( $shipment_params , 'free_shipment="', '"|' );
			$editshipment_url	= JRoute::_('index.php?option=com_vmvendor&view=editshipment&shipmentid='.$shipment->virtuemart_shipmentmethod_id);

			if($shipment->published==1)
				$trclass="success";
			else
				$trclass="danger";
			echo '<tr class="'.$trclass.'">
			<td>';
			//publishes
			if($shipment->published==1)
				echo '<i class="vmv-icon-ok  '.$tipclass.'" title="'.JText::_('COM_VMVENDOR_DASHBOARD_SHIPMENT_PUBLISHED').'"></i>';
			else
				echo '<i class="vmv-icon-cancel danger '.$tipclass.'" title="'.JText::_('COM_VMVENDOR_DASHBOARD_SHIPMENT_NOTPUBLISHED').'"></i>';
			echo '</td>
			<td>
			<a class="'.$tipclass.'" title="'.JText::_('COM_VMVENDOR_EDITSHIPMENT_FORM_DESCR')
			.'<br />'.$shipment->shipment_desc.'" href="'.$editshipment_url.'">'.$shipment->shipment_name.'</a>
			</td>
			<td>';
			$multicountries = str_replace( array('[','"',']'), '', $shipm_countries );
			$countries = explode(',' , $multicountries);
			foreach($countries as $id)
			{
				echo '<span class="badge">'.VmvendorModelDashboard::getCountryName($id).'</span> ';
			}
			echo '</td><td>';
			if($shipm_zip_start)
				echo '> <span class="'.$tipclass.'" title="'.JText::_('COM_VMVENDOR_DASHBOARD_SHIPMENT_ZIPSTART').'">'.$shipm_zip_start.'</span>';
			if($shipm_zip_stop)
			{
				echo '<br />< <span class="'.$tipclass.'" title="'.JText::_('COM_VMVENDOR_DASHBOARD_SHIPMENT_ZIPEND').'">'.$shipm_zip_stop.'</span>';
			}
			echo '</td>
			<td>';
			if($shipm_weight_start OR $shipm_weight_stop )
			echo '<span class="'.$tipclass.'" title="'.JText::_('COM_VMVENDOR_DASHBOARD_SHIPMENT_WEIGHTSTART').'">> '.$shipm_weight_start.strtolower($shipm_weight_unit).'</span>';
			if($shipm_weight_stop)
			{
				echo '<br /><span class="'.$tipclass.'" title="'.JText::_('COM_VMVENDOR_DASHBOARD_SHIPMENT_WEIGHTEND').'">< '
				.$shipm_weight_stop.strtolower($shipm_weight_unit).'</span>';
			}
			
			echo '</td>
			<td>';
			if($shipm_nbproducts_start OR $shipm_nbproducts_stop)
				echo '> <span class="'.$tipclass.'" title="'.JText::_('COM_VMVENDOR_DASHBOARD_SHIPMENT_MINPRODUCTS').'">'.$shipm_nbproducts_start.'</span>';
			if($shipm_nbproducts_stop>0)
			{
				echo '<br />< <span class="'.$tipclass.'" 
				title="'.JText::_('COM_VMVENDOR_DASHBOARD_SHIPMENT_MAXPRODUCTS').'">'.$shipm_nbproducts_stop.'</span>';
			}
			echo '</td>
			<td>';
			$search = array('{sign}', '{number}', '{symbol}');
			if($shipm_orderamount_start OR $shipm_orderamount_stop)
			{
				$res = number_format((float)$shipm_orderamount_start,$currency_decimal_place,$currency_decimal_symbol,$currency_thousands);
				$replace = array('+', $res, $currency_symbol);
				$formattedRounded_orderamount_start = str_replace ($search,$replace,$currency_positive_style);
				echo '> <span class="'.$tipclass.'" title="'.JText::_('COM_VMVENDOR_DASHBOARD_SHIPMENT_MINAMOUNTS').'">'
				.$formattedRounded_orderamount_start.'</span>';
			}
			
			if($shipm_orderamount_stop)
			{
				echo '<br />';
				$res = number_format((float)$shipm_orderamount_stop,$currency_decimal_place,$currency_decimal_symbol,$currency_thousands);
				$replace = array('+', $res, $currency_symbol);
				$formattedRounded_orderamount_stop = str_replace ($search,$replace,$currency_positive_style);
				echo '< <span class="'.$tipclass.'" title="'.JText::_('COM_VMVENDOR_DASHBOARD_SHIPMENT_MAXAMOUNTS').'">'
				.$formattedRounded_orderamount_stop.'</span>';
			}
			echo '</td>
			<td>';
			if($shipm_free_shipment)
			{
				$res = number_format((float)$shipm_free_shipment,$currency_decimal_place,$currency_decimal_symbol,$currency_thousands);
				$replace = array('+', $res, $currency_symbol);
				$formattedRounded_free_shipment = str_replace ($search,$replace,$currency_positive_style);
				echo $formattedRounded_free_shipment;
			}
			echo '</td>
			<td>';
			if($shipm_package_fee>0)
			{
				$res = number_format((float)$shipm_package_fee,$currency_decimal_place,$currency_decimal_symbol,$currency_thousands);
				$replace = array('+', $res, $currency_symbol);
				$formattedRounded_package_fee = str_replace ($search,$replace,$currency_positive_style);
				echo $formattedRounded_package_fee;
			}
			echo '</td>
			<td>';
			if($shipm_shipment_cost)
			{
				$res = number_format((float)$shipm_shipment_cost,$currency_decimal_place,$currency_decimal_symbol,$currency_thousands);
				$replace = array('+', $res, $currency_symbol);
				$formattedRounded_shipment_cost = str_replace ($search,$replace,$currency_positive_style);
				echo $formattedRounded_shipment_cost;
			}
			echo '</td>
			<td>';
			if($shipm_tax_id==0)
					echo JText::_('COM_VIRTUEMART_PRODUCT_TAX_NO_SPECIAL');
			elseif($shipm_tax_id>0)
				echo VmvendorModelDashboard::getShipmentTaxName($shipm_tax_id);
			echo '</td>
			<td>
			<form name="deleteshipment'.$shipment->virtuemart_shipmentmethod_id.'" 
			id="deleteshipment'.$shipment->virtuemart_shipmentmethod_id.'" method="post" >
			<div class="btn-group">
			<a class="btn btn-mini btn-primary '.$tipclass.'" title="'.JText::_('COM_VMVENDOR_DASHBOARD_SHIPMENT_EDIT').'" href="'.$editshipment_url.'">
			<i class="vmv-icon-edit"></i></a>
			<input type="hidden" name="controller" value="dashboard">
				<input type="hidden" name="task" value="deleteshipment">
				<input type="hidden" name="delete_shipmentid" value="'.$shipment->virtuemart_shipmentmethod_id.'">
				<input type="hidden" name="userid" value="'.$user->id.'">
				<a class="btn btn-mini btn-danger '.$tipclass.'" title="'.JText::_('COM_VMVENDOR_DASHBOARD_SHIPMENT_TRASH').'" 
				onclick="sweetConfirmDelete( document.getElementById(\'deleteshipment'.$shipment->virtuemart_shipmentmethod_id.'\') );">
			<i class="vmv-icon-trash"></i></a>
			</div>
			</form>
			</td>
			</tr>';
		}
		echo '</tbody></table>';
	}
	echo '</div>';
	echo JHtml::_('bootstrap.endTab');
}
$tab_modules = JModuleHelper::getModules('vmv-dashboard-tab');
$i = 1;
foreach ($tab_modules as $tab_module)
{
	echo JHtml::_('bootstrap.addTab', 'dashboardTab', 'module'.$i, JText::_($tab_module->title)  );		
	echo '<div>'.JModuleHelper::renderModule($tab_module).'</div>';
	$i++;
	echo JHtml::_('bootstrap.endTab');
}
echo JHtml::_('bootstrap.endTabSet');
$bot_modules = JModuleHelper::getModules('vmv-dashboard-bot');
foreach ($bot_modules as $bot_module)
{	
	echo '<h3 class="module-title">'.$bot_module->title.'</h3>
	<div>'.JModuleHelper::renderModule($bot_module).'</div>';
}
?>