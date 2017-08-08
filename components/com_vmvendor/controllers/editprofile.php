<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2016 Adrien Roussel Nordmograph.com
 * @license : GNU/GPL v3
 * @Website : https://www.nordmograph.com/extensions
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
//jimport('joomla.application.component.controller');


class VmvendorControllerEditprofile extends JControllerForm
{
	/**
	 * Custom Constructor
	 */
	 public function getModel($name = '', $prefix = '', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, array('ignore_request' => false));
	}
 	function __construct()	{
		parent::__construct( );
	}
	function getVendorid()
	{
		$user 	= JFactory::getUser();		
		$db		= JFactory::getDBO();	
		$q = "SELECT  `virtuemart_vendor_id` FROM `#__virtuemart_vmusers` 
		WHERE `virtuemart_user_id` = '".$user->id."' ";
		$db->setQuery($q);
		return $vendor_id = $db->loadResult();	
	}
	
	function save($key = NULL, $urlVar = NULL)
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		//require_once( JPATH_COMPONENT.'/helpers/functions.php' );
		jimport('joomla.application.component.controller');
		if (!class_exists( 'VmConfig' ))
			require JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php';
		VmConfig::loadConfig();
		$dblang = VMLANG;
		$image_path 			= VmConfig::get('media_vendor_path');
		$vmconfig_img_width		= VmConfig::get('img_width');	
		if(!$vmconfig_img_width) $vmconfig_img_width = 90;
		$thumb_path = $image_path.'resized/';
		$app 					= JFactory::getApplication();
		$user 					= JFactory::getUser();		
		$juri 					= JURI::base();
		$db						= JFactory::getDBO();	
		$model      			= $this->getModel ( 'editprofile' );
		$view       			= $this->getView  ( 'editprofile','html' );
		$Itemid 			= $app->input->get('Itemid');

		$data  = $this->input->post->get('jform', array(), 'array');
		$files = $this->input->files->get('jform');

		$form = $model->getForm();
		if (!$form)
		{
			$app->enqueueMessage(  $model->getError(),'error');
			return false;
		}
		$validate = $model->validate($form, $data);

		if ($validate === false)
		{
			// Get the validation messages.
			$errors	= $model->getErrors();
			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 5; $i++)
			{
				if ($errors[$i] instanceof Exception)
				{
					$app->enqueueMessage( $errors[$i]->getMessage(), 'warning');
				} else {
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}
			// Save the data in the session.
			$app->setUserState('com_vmvendor.editprofile.data', $data);
			// Redirect back to the form.
			$this->setRedirect(JRoute::_('index.php?option=com_vmvendor&view=editprofile&Itemid='.$Itemid, false));
			return false;
		}
		
		
		
		$cparams 				= JComponentHelper::getParams( 'com_vmvendor' );
		$multilang_mode 		= $cparams->get('multilang_mode', 0);
		if($multilang_mode >0)
		{
			//$active_languages	=	VmConfig::get( 'active_languages' );
			$lang = JFactory::getLanguage(); 
			$dblang = strtolower( str_replace('-' , '_' , $lang->getTag() ) );
		}

		$profileman 			= $cparams->get( 'profileman' );
		$maximgside 			= $cparams->get('maximgside', '800');
		$thumbqual 				= $cparams->get('thumbqual', 90);
		$wysiwyg_prof			= $cparams->get('wysiwyg_prof', 1);
		$paypalemail_field		= $cparams->get('paypalemail_field', 1);
		$currency_mode			= $cparams->get('currency_mode', 0);
		$withdraw_iban			= $cparams->get('withdraw_iban', 1);
		
		$vendor_title				=	$data['vendor_title'];
		$vendor_telephone			=	$data['vendor_telephone'];
		
		$vendor_address			=	$data['vendor_address'];
		$vendor_city			=	$data['vendor_city'];
		$vendor_zip				=	$data['vendor_zip'];
		$vendor_stateid			=	$data['vendor_state'];
		$vendor_countryid		=	$data['vendor_country'];
		
		$vendor_url					=	$data['vendor_url'];
		$vendor_store_desc			=	$data['vendor_store_desc'];
		$vendor_terms_of_service	=	$data['vendor_terms_of_service'];
		$vendor_legal_info			=	$data['vendor_legal_info'];
		$mycurrency 				= 	$data['mycurrency'];
		$paypal_email				=	$data['paypal_email'];
		$iban						=	$data['iban'];
		
		$oldaddress = $this->getOldAddress();
		if(
			!$oldaddress->latitude 
			OR !$oldaddress->longitude 
			OR $oldaddress->latitude=='255' 
			OR $oldaddress->longitude=='255'
			OR $oldaddress->address != $vendor_address 
			OR $oldaddress->city 	!= $vendor_city 
			OR $oldaddress->zip 	!= $vendor_zip 
			OR $oldaddress->virtuemart_state_id != $vendor_stateid 
			OR $oldaddress->virtuemart_country_id != $vendor_countryid
		)
		{
			$gclientid				= $cparams->get( 'gclientid');
			$gsignature				= $cparams->get( 'gsignature');
			$gbusiness_query ='';
			if( $gclientid	!='' && $gsignature!='')
				$gbusiness_query = '&client='.$gclientid.'&signature='.$gsignature;
	
			$q ="SELECT country_name FROM #__virtuemart_countries WHERE virtuemart_country_id='".$vendor_countryid."' ";
			$db->setQuery($q);
			$country_name = $db->loadResult();
			if($vendor_stateid)
			{
				$q ="SELECT state_name FROM #__virtuemart_states WHERE virtuemart_state_id='".$vendor_stateid."' ";
				$db->setQuery($q);
				$state_name = $db->loadResult();
			}
	
			// geocode address to get coordinates and validate address	
			$query = trim($vendor_address).',';
			$query .= trim($vendor_city).',';
			if ($vendor_stateid!="")$query .= trim($state_name).',';
			$query .= trim($vendor_zip).',';
			$query .= trim($country_name).',';
			
			function sendGeoQuery($url=null, $content=null)
			{
					if (function_exists('curl_init'))
					{
						$c = curl_init();
						curl_setopt($c, CURLOPT_URL, $url);
						curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 30);
						$content = trim(curl_exec($c));
						curl_close($c);
					}
					elseif (ini_get('allow_url_fopen'))
					{
						$timeout = ini_set('default_socket_timeout', 30);
						$fp = @fopen($url, 'r');
						$content = @fread($fp, 4096);
						@fclose($fp);
					}
					return $content;
			}
			function googleGeocode($query)
			{
					$xmlContent = sendGeoQuery('http://maps.googleapis.com/maps/api/geocode/xml?address='. $query.'&sensor=true');
					if($xmlContent==='OVER_QUERY_LIMIT')
						echo 'Google: '.$xmlContent .' - ';
					$xmlObject = simplexml_load_string($xmlContent);
					$result['lat'] = @$xmlObject->result->geometry->location->lat;
					$result['lng'] = @$xmlObject->result->geometry->location->lng;
					return $result;
			}
		
		
			function yahooGeocode($query)
			{
				$geourl  = "http://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20geo.placefinder%20where%20text%20%3D%20%221%20".$query."%22&format=json&diagnostics=true";
				$xmlContent = file_get_contents($geourl);
				$obj = json_decode($xmlContent);
				$result['lat'] = @$obj->query->results->Result->latitude;
				$result['lng'] = @$obj->query->results->Result->longitude;	
				return $result;
			}
			
			
			$result = googleGeocode( urlencode( $query.$gbusiness_query ) );
			$parsedLat = @$result['lat'];
			$parsedLng = @$result['lng'];
			if ( (!$parsedLat OR !$parsedLng) )
			{
				$app->enqueueMessage(JText::_('COM_VMVENDOR_EDITPRO_NOGOOGLEMAPSVALIDATION'),'warning');
				$result = yahooGeocode(urlencode( $query ) );
				$parsedLat = @$result['lat'];
				$parsedLng = @$result['lng'];
				if ( (!$parsedLat OR !$parsedLng) )
				{
					$app->redirect( 
						JRoute::_('index.php?option=com_vmvendor&view=editprofile&Itemid='.$app->input->get('Itemid'),false ), 
						JText::_('COM_VMVENDOR_EDITPRO_NOYAHOOMAPSVALIDATION'),'warning'
					);
					return false;
				}
				else
					$app->enqueueMessage('<i class="vmv-icon-ok"></i> '.JText::_('COM_VMVENDOR_EDITPRO_YAHOOMAPSVALIDSUCCESS'), 'message');
			}
			else
				$app->enqueueMessage('<i class="vmv-icon-ok"></i> '.JText::_('COM_VMVENDOR_EDITPRO_GOOGLEMAPSVALIDSUCCESS'), 'message');
			if($oldaddress)
			{
				$q = "UPDATE #__vmvendor_vendoraddress 
				SET address='".$db->escape($vendor_address)."' , 
				city='".$db->escape($vendor_city)."', 
				zip='".$db->escape($vendor_zip)."' ";
				$q .= ", virtuemart_state_id='".$db->escape($vendor_stateid)."' ,
					state_name='".$db->escape($state_name)."' ";
				$q .= ", virtuemart_country_id='".$db->escape($vendor_countryid)."' , 
				country_name='".$db->escape($country_name)."', 
				latitude='".$parsedLat."' , longitude='".$parsedLng."' 
				WHERE vendor_user_id='".$user->id."' ";
			}
			else
			{
				$q ="INSERT INTO #__vmvendor_vendoraddress
				(vendor_user_id,address,city,zip";
				$q .= ",virtuemart_state_id,state_name";
				$q .= ",virtuemart_country_id,country_name,latitude,longitude) 
				VALUES 
				('".$user->id."','".$db->escape($vendor_address)."','".$db->escape($vendor_city)."',
				'".$db->escape($vendor_zip)."' ";
				$q .= ",'".$db->escape($vendor_stateid)."','".$db->escape($state_name)."' ";
				$q .= ",'".$db->escape($vendor_countryid)."',
				'".$db->escape($country_name)."','".$parsedLat."','".$parsedLng."') ";	
			}
			$db->setQuery($q);
			$db->execute();
		}
		
		
		
		if($paypalemail_field)
		{
			$q ="SELECT id, paypal_email FROM `#__vmvendor_paypal_emails` WHERE userid='".$user->id."' ";
			$db->setQuery($q);
			$paypalemail_data = $db->loadObject();
			if($paypalemail_data->id>0 && $paypalemail_data->paypal_email != $paypal_email)
			{
				$q = "UPDATE #__vmvendor_paypal_emails SET paypal_email='".$db->escape($paypal_email)."' 
				WHERE userid='".$user->id."'" ;
				$db->setQuery($q);
				$db->execute();
			}
			elseif(!$paypalemail_data->id && $paypal_email!='' )
			{
				$q = "INSERT INTO `#__vmvendor_paypal_emails`  
				(userid,vendorid, paypal_email)
				VALUES
				('".$user->id."' , '".$this->getVendorid()."' ,'".$paypal_email."' )" ;
				$db->setQuery($q);
				$db->execute();
			}
		}
		
		
		if($iban !='')
		{
			if(VmvendorControllerEditprofile::checkIBAN($iban))
			{
				$q ="SELECT id, iban FROM `#__vmvendor_iban` WHERE userid='".$user->id."' ";
				$db->setQuery($q);
				$iban_data = $db->loadObject();
				if($iban_data->id>0 && $iban_data->iban != $iban)
				{
					$q = "UPDATE #__vmvendor_iban SET iban='".$iban."' 
					WHERE userid='".$user->id."'" ;
					$db->setQuery($q);
					$db->execute();
				}
				elseif(!$iban_data->id && $iban!='' )
				{
					$q = "INSERT INTO `#__vmvendor_iban`  
					(userid,vendorid, iban)
					VALUES
					('".$user->id."' , '".$this->getVendorid()."' ,'".$db->escape($iban)."' )" ;
					$db->setQuery($q);
					$db->execute();
				}
			}
			else
			{
				$app->enqueueMessage(JText::_('COM_VMVENDOR_EDITPROFILE_IBANERROR'),'error');
			}
		}
		else
		{
			$q = "DELETE FROM `#__vmvendor_iban` WHERE userid='".$user->id."' ";
			$db->setQuery($q);
			$db->execute();
		}
		
		
		

		$activity_stream		= &$data['activity_stream'];
		$slug					= JFilterOutput::stringURLSafe($user->id.'-'.$vendor_title);	
		
		$vendor_id = $this->getVendorid();		
		
		if(!$vendor_id)
		{
			require_once JPATH_COMPONENT.'/helpers/functions.php';	
			VmvendorFunctions::createVendor( $user->id , $mycurrency );
			$vendor_id = $this->getVendorid();	
		}

		$update = 1;
		if($multilang_mode >0)
		{ // check if data allready exists in the current language to know if we update or insert.
			$q = "SELECT  COUNT(*)  FROM `#__virtuemart_vendors_".$dblang."`  
			WHERE `virtuemart_vendor_id` 	='".$vendor_id."' ";
			$db->setQuery($q);
			$allready_in = $db->loadResult();	
			if($allready_in <1)
			{
				$update = 0;
				$q = "INSERT INTO `#__virtuemart_vendors_".$dblang."` 
				( `virtuemart_vendor_id` , `vendor_store_desc` , `vendor_terms_of_service` ,
				 `vendor_legal_info` , `vendor_store_name` , `vendor_phone` , `vendor_url` , `slug` ) 
				VALUES
				('".$vendor_id."' , 
					'".$db->escape( $vendor_store_desc )."'  ,
					 '".$db->escape( $vendor_terms_of_service )."' ,
					 '".$db->escape( $vendor_legal_info )."' , 
					 '".$db->escape( $vendor_title )."' , 
					 '".$db->escape( $vendor_telephone )."' , 
					 '".$db->escape( $vendor_url )."' , 
					 '".$db->escape( $slug )."'  ) ";			
				$db->setQuery($q);
				$db->execute();
			}
		}
			
		if($update==1)
		{
			$q = "UPDATE `#__virtuemart_vendors_".$dblang."` SET 
				`vendor_store_desc` 			= '".$db->escape( $vendor_store_desc )."'  ,
				`vendor_terms_of_service` 		= '".$db->escape( $vendor_terms_of_service )."' , 
				`vendor_legal_info` 			= '".$db->escape( $vendor_legal_info )."' , 
				`vendor_store_name`				= '".$db->escape( $vendor_title )."' , 
				`vendor_phone` 					= '".$db->escape( $vendor_telephone )."' , 
				`vendor_url` 					= '".$db->escape( $vendor_url )."' , 
				`slug` 							= '".$db->escape( $slug )."' 
			WHERE `virtuemart_vendor_id`='".$vendor_id."' ";
							
			$db->setQuery($q);
			$db->execute();
			
			$q = "UPDATE `#__virtuemart_vendors` SET 
			`vendor_name`				= '".$db->escape( $vendor_title )."'  ";
			if($currency_mode)
			{
				$q .= ", 	`vendor_currency`	= '".$db->escape( $mycurrency )."'  ";
			}
			$q .= "	WHERE `virtuemart_vendor_id`='".$vendor_id."' ";			
			$db->setQuery($q);
			$db->execute();
		}

		
		jimport('joomla.filesystem.file');
		$image = $files['vendor_image'];
			
		$image['name'] = JFile::makeSafe($image['name']);
		if($image['name']!='')
		{
			// check if there allready is a vendor image
			$imgisvalid = 1;
			$q = "SELECT vm.`virtuemart_media_id` , vm.`file_url` , vm.`file_url_thumb` 
			FROM `#__virtuemart_medias` vm 
			LEFT JOIN `#__virtuemart_vendor_medias` vvm ON vvm.`virtuemart_media_id` = vm.`virtuemart_media_id` 
			WHERE vvm.`virtuemart_vendor_id`='".$vendor_id."' ";
			$db->setQuery($q);
			$vendorimages = $db->loadRow();
			$virtuemart_media_id = $vendorimages[0];
			$file_url = $vendorimages[1];
			$file_url_thumb = $vendorimages[2];
			$vendorimage_path ='images/stories/virtuemart/vendor/';
			$vendorthumb_path ='images/stories/virtuemart/vendor/resized/';
			$infosImg = getimagesize($image['tmp_name']);		
			if ( (substr($image['type'],0,5) != 'image' ) )
			{
				$app->enqueueMessage(  JText::_('COM_VMVENDOR_VMVENADD_IMGEXTNOT'),'warning');
				$imgisvalid = 0;
			}
			$vendor_image = strtolower($user->id ."_".$image['name']);														
			$target_imagepath = JPATH_BASE . '/' . $vendorimage_path . $vendor_image;
			if($imgisvalid)
			{
				if( JFile::upload( $image['tmp_name'] , $target_imagepath )  )
				{
					$app->enqueueMessage( '<i class="vmv-icon-ok">
					</i> '.JText::_('COM_VMVENDOR_VMVENADD_IMAGEUPLOADRENAME_SUCCESS').' '.$vendor_image, 'message');
				}
				else
					$app->enqueueMessage( JText::_('COM_VMVENDOR_VMVENADD_IMAGEUPLOAD_ERROR') ,'warning');
			}
			$ext = JFile::getExt( $image['name'] ) ; 
			$ext = strtolower($ext);
			$ext = str_replace('jpeg','jpg',$ext);
			//SWITCHES THE IMAGE CREATE FUNCTION BASED ON FILE EXTENSION
			switch($ext)
			{
				case 'jpg':
					$source = imagecreatefromjpeg($target_imagepath);
					$large_source = imagecreatefromjpeg($target_imagepath);
				break;
				case 'png':
					$source = imagecreatefrompng($target_imagepath);
					$large_source = imagecreatefrompng($target_imagepath);
				break;
				case 'gif':
					$source = imagecreatefromgif($target_imagepath);
					$large_source = imagecreatefromgif($target_imagepath);
				break;
				default:
					$imgisvalid = 0;
				break;
			} 
			if($vendor_image!='' && $imgisvalid )
			{
				list($width,  $height) = getimagesize($target_imagepath); 
				if($width>$maximgside)
				{
					$resizedH = ( $maximgside * $height) / $width;
					if($ext=='gif')
						$largeone = imagecreate( $maximgside ,  $resizedH);
					else
						$largeone = imagecreatetruecolor( $maximgside ,  $resizedH);
					imagealphablending( $largeone, false);
					imagesavealpha( $largeone,true);
					$transparent = imagecolorallocatealpha($largeone, 255, 255, 255, 127);
					imagefilledrectangle($largeone, 0, 0, $maximgside, $resizedH, $transparent);
					imagecopyresampled( $largeone,  $large_source,  0,  0,  0,  0,  $maximgside ,  $resizedH,  $width,  $height );
				}
            	else
				{
                	$largeone = $target_imagepath;
             	}
				switch($ext)
				{
					case 'jpg':
						imagejpeg($largeone, JPATH_BASE . '/' . $image_path .'/' .$vendor_image,  $thumbqual);
					break;
					case 'jpeg':
						imagejpeg($largeone, JPATH_BASE . '/' . $image_path .'/' .$vendor_image,  $thumbqual);
					break;
					case 'png':
						imagepng($largeone, JPATH_BASE . '/' . $image_path .'/' .$vendor_image);
					break;
					case 'gif':
						imagegif($largeone, JPATH_BASE . '/' . $image_path .'/' .$vendor_image);
					break;
				} 
				imagedestroy($largeone);

				if($width>=$height)
				{ 
					$resizedH = ($vmconfig_img_width * $height) / $width;
					if($ext=='gif')
						$thumb = imagecreate( $vmconfig_img_width ,  $resizedH);
					else
						$thumb = imagecreatetruecolor( $vmconfig_img_width ,  $resizedH);
					imagealphablending( $thumb, false);
					imagesavealpha( $thumb,true);
					$transparent = imagecolorallocatealpha($thumb, 255, 255, 255, 127);
					imagefilledrectangle($thumb, 0, 0, $vmconfig_img_width , $resizedH, $transparent);
					imagecopyresampled( $thumb,  $source,  0,  0,  0,  0,  $vmconfig_img_width ,  $resizedH,  $width,  $height );
				}
				else
				{
					$resizedW = ( VmConfig::get('img_height') * $width) / $height;
					if($ext=='gif')
						$thumb = imagecreate($resizedW,  VmConfig::get('img_height') );
					else
						$thumb = imagecreatetruecolor($resizedW,  VmConfig::get('img_height') );
					imagealphablending( $thumb, false);
					imagesavealpha( $thumb,true);
					$transparent = imagecolorallocatealpha($thumb, 255, 255, 255, 127);
					imagefilledrectangle($thumb, 0, 0, $resizedW , VmConfig::get('img_height'), $transparent);
					imagecopyresampled($thumb ,$source,0,0,0,0,$resizedW,  VmConfig::get('img_height'),$width,$height );
				}
				switch($ext)
				{
					case 'jpg':
						imagejpeg($thumb, JPATH_BASE . '/' . $thumb_path .'/' .$vendor_image,  $thumbqual);
					break;
					case 'jpeg':
						imagejpeg($thumb, JPATH_BASE . '/' . $thumb_path .'/' .$vendor_image,  $thumbqual);
					break;
					case 'png':
						imagepng($thumb, JPATH_BASE . '/' . $thumb_path .'/' .$vendor_image);
					break;
					case 'gif':
						imagegif($thumb, JPATH_BASE . '/' . $thumb_path .'/' .$vendor_image);
					break;
				} 
				imagedestroy($thumb);
				if($virtuemart_media_id!='')
				{ // we updated the picture
				// delete all media file
					if($file_url !=  $image_path.JFile::makeSafe($vendor_image) )
					{ // only delete old file if new filename and old differ. If same file has allready been overwritten
						if($file_url!='')
							JFile::delete($file_url);
						if($file_url_thumb!='')
							JFile::delete($file_url_thumb);
					}						
					$q ="UPDATE `#__virtuemart_medias` SET 
					`file_title`='".$db->escape($vendor_title)."' , 
					`file_mimetype`='".$image['type']."' , 
					`file_url` = '".$vendorimage_path.JFile::makeSafe($vendor_image)."' , 
					`file_url_thumb` ='".$vendorthumb_path.JFile::makeSafe($vendor_image)."' ,
					`modified_on`='".date('Y-m-d H:i:s')."' , 
					`modified_by` ='".$user->id."' 
					WHERE `virtuemart_media_id` ='".$virtuemart_media_id."' ";
					$db->setQuery($q);
					$db->execute();
				}
				else
				{ // we insert the new file					
					$q = "INSERT INTO `#__virtuemart_medias` 
					( `virtuemart_vendor_id` , `file_title` , `file_mimetype` , `file_type` , `file_url` , `file_url_thumb` , `file_is_product_image` , `published` , `created_on` , `created_by`)
					VALUES
					(  '".$vendor_id."' , '".$db->escape($vendor_title)."' , '".$image['type']."' , 'vendor' , '".$vendorimage_path.JFile::makeSafe($vendor_image)."' , '".$vendorthumb_path.JFile::makeSafe($vendor_image)."' , '1', '1' , '".date('Y-m-d H:i:s')."' , '".$user->id."' )";
					$db->setQuery($q);
					$db->execute();
					$virtuemart_media_id = $db->insertid();
					$q = "INSERT INTO `#__virtuemart_vendor_medias` 
					( `virtuemart_vendor_id` , `virtuemart_media_id` )
					VALUES
					(  '".$vendor_id."' , '".$virtuemart_media_id."'  )";
					$db->setQuery($q);
					$db->execute();
				}
			}		
		}
		if( $activity_stream )
		{
			$vendorprofile_url = JRoute::_('index.php?option=com_vmvendor&view=vendorprofile&userid='.$user->id.'&Itemid='.$Itemid);
			if($profileman=='js')
			{
				$jspath = JPATH_ROOT . '/components/com_community';
				include_once $jspath. '/libraries/core.php';
				CFactory::load('libraries', 'activities');          
				$act = new stdClass();
				$act->cmd    = 'wall.write';
				$act->actor    = $user->id;
				$act->target    = 0; // no target
				$act->title    = JText::_( 'COM_VMVENDOR_JOMSOCIAL_EDITEDPROFILE').' <a href="'.$vendorprofile_url.'">'.ucfirst($vendor_title).'</a>' ;		
				$act->content    = '';
				$act->app    = 'vmvendor.vendorupdate';
				$act->cid    = 0;
				$act->comment_id	= CActivities::COMMENT_SELF;
				$act->comment_type	= 'vmvendor.vendorupdate';
				$act->like_id		= CActivities::LIKE_SELF;		
				$act->like_type		= 'vmvendor.vendorupdate';
				CActivityStream::add($act);	
			}
			if($profileman=='es') 
			{
				require_once JPATH_ROOT . '/administrator/components/com_easysocial/includes/foundry.php';
				$vendorprofile_link = '<a href="'.$vendorprofile_url.'">'.ucfirst($vendor_title).'</a>';
				$contextParams =  array(  'vendorprofile_link'=>$vendorprofile_link );
				$stream     = Foundry::stream();
				$template   = $stream->getTemplate();
				$template->setActor( $user->id, 'user' );
				$template->setContext( $user->id , 'vendorprofile' ,$contextParams);
				$template->setVerb( 'edit' );
				$stream->add( $template );
			}
		}
		$app->enqueueMessage( '<i class="vmv-icon-ok"></i> '.JText::_('COM_VMVENDOR_UPDATED_SUCCESS') , 'message');
		if($vendorprofile_itemid=='')
			$vendorprofile_itemid = $app->input->get('Itemid');
		$app->redirect( 
			JRoute::_('index.php?option=com_vmvendor&view=vendorprofile&Itemid='.$this->getVMVprofileItemid(),false ) 
		);
	}
	public function cancel($key = NULL, $urlVar = NULL)
	{	
		$this->setRedirect(JRoute::_('index.php?option=com_vmvendor&view=vendorprofile&Itemid='.$this->getVMVprofileItemid(),false ));	
	}
	
	public function getVMVprofileItemid()
	{
		$db 	= JFactory::getDBO();
		$lang 	= JFactory::getLanguage();
		$q = "SELECT `id` FROM `#__menu` WHERE `link` ='index.php?option=com_vmvendor&view=vendorprofile' 
		AND ( language ='".$lang->getTag()."' OR language='*') AND published='1' AND access='1' ";
		$db->setQuery($q);
		return $vmvendoritemid = $db->loadResult();	
	}
	
	static function checkIBAN($iban)
	{
		$iban = strtolower(str_replace(' ','',$iban));
		$Countries = array('al'=>28,'ad'=>24,'at'=>20,'az'=>28,'bh'=>22,'be'=>16,'ba'=>20,'br'=>29,'bg'=>22,'cr'=>21,'hr'=>21,'cy'=>28,'cz'=>24,'dk'=>18,'do'=>28,'ee'=>20,'fo'=>18,'fi'=>18,'fr'=>27,'ge'=>22,'de'=>22,'gi'=>23,'gr'=>27,'gl'=>18,'gt'=>28,'hu'=>28,'is'=>26,'ie'=>22,'il'=>23,'it'=>27,'jo'=>30,'kz'=>20,'kw'=>30,'lv'=>21,'lb'=>28,'li'=>21,'lt'=>20,'lu'=>20,'mk'=>19,'mt'=>31,'mr'=>27,'mu'=>30,'mc'=>27,'md'=>24,'me'=>22,'nl'=>18,'no'=>15,'pk'=>24,'ps'=>29,'pl'=>28,'pt'=>25,'qa'=>29,'ro'=>24,'sm'=>27,'sa'=>24,'rs'=>22,'sk'=>24,'si'=>19,'es'=>24,'se'=>24,'ch'=>21,'tn'=>24,'tr'=>26,'ae'=>23,'gb'=>22,'vg'=>24);
		$Chars = array('a'=>10,'b'=>11,'c'=>12,'d'=>13,'e'=>14,'f'=>15,'g'=>16,'h'=>17,'i'=>18,'j'=>19,'k'=>20,'l'=>21,'m'=>22,'n'=>23,'o'=>24,'p'=>25,'q'=>26,'r'=>27,'s'=>28,'t'=>29,'u'=>30,'v'=>31,'w'=>32,'x'=>33,'y'=>34,'z'=>35);
	
		if(strlen($iban) == $Countries[substr($iban,0,2)]){
	
			$MovedChar = substr($iban, 4).substr($iban,0,4);
			$MovedCharArray = str_split($MovedChar);
			$NewString = "";
	
			foreach($MovedCharArray AS $key => $value){
				if(!is_numeric($MovedCharArray[$key])){
					$MovedCharArray[$key] = $Chars[$MovedCharArray[$key]];
				}
				$NewString .= $MovedCharArray[$key];
			}
			
			if (!function_exists('bcmod')) {
				function bcmod( $x, $y )   
				{ 
					// how many numbers to take at once? carefull not to exceed (int) 
					$take = 5;     
					$mod = ''; 
						
					do 
					{ 
						$a = (int)$mod.substr( $x, 0, $take ); 
						$x = substr( $x, $take ); 
						$mod = $a % $y;    
					} 
					while ( strlen($x) ); 
				
					return (int)$mod; 
				} 
			}
			if(bcmod($NewString, '97') == 1)
			{
				return TRUE;
			}
			else{
				return FALSE;
			}
		}
		else{
			return FALSE;
		}   
	}
	
	static function getOldAddress()
	{
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$q = "SELECT address,zip,city,virtuemart_state_id,virtuemart_country_id,latitude,longitude 
		FROM #__vmvendor_vendoraddress 
		WHERE vendor_user_id='".$user->id."' ";
		$db->setQuery($q);
		$oldaddress = $db->loadObject();
		return 	$oldaddress;
	}
}
?>