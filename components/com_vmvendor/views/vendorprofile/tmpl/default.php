<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2016 Adrien Roussel
 * @license : GNU/GPL v3
 * @Website : https://www.nordmograph.com/extensions
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
if (!class_exists( 'VmConfig' ))
	require JPATH_ADMINISTRATOR  . '/components/com_virtuemart/helpers/config.php';
VmConfig::loadConfig();
$showCustoms 	= VmConfig::get('show_pcustoms',1);
$use_as_catalog = VmConfig::get('use_as_catalog',0);
$img_width 		= VmConfig::get('img_width');

if(!$use_as_catalog)
{
	if(!class_exists('shopFunctionsF'))
		require JPATH_BASE.'/components/com_virtuemart/helpers/shopfunctionsf.php';
	vmJsApi::jSite();
	vmJsApi::jPrice();
}
require_once JPATH_BASE.'/administrator/components/com_virtuemart/models/product.php';
$productModel = VmModel::getModel('product');	
$app 				= JFactory::getApplication();
$user 				= JFactory::getUser();
$db 				= JFactory::getDBO();
$juri 				= JURI::base();
$lang 				= JFactory::getLanguage();
$langtag 			= $lang->get('tag');
$langtag			= str_replace("-","_",$langtag);		
$doc 				= JFactory::getDocument();

// add to cart button
vmJsApi::cssSite();
echo vmJsApi::writeJS();

if($this->ismyprofile)
{
	$doc->addStyleSheet($juri.'components/com_vmvendor/assets/js/sweetalert/sweetalert2.css');
	$doc->addScript($juri.'components/com_vmvendor/assets/js/sweetalert/sweetalert2.min.js');
}

$cparams 				= JComponentHelper::getParams('com_vmvendor');
$load_bootstrap_css		= $cparams->get('load_bootstrap_css', 0);
if($load_bootstrap_css)
	$doc->addStyleSheet( $juri.'media/jui/css/bootstrap.min.css');
$naming 				= $cparams->get('naming', 'username');
$profileman 			= $cparams->get('profileman');
$use_statefield			= $cparams->get('use_statefield',1);
$vendorcontactform		= $cparams->get('vendorcontactform');
$allow_deletion			= $cparams->get('allow_deletion'); //0 no 1 unpublish only 2 yes but 3 yes
$enablerss 				= $cparams->get('enablerss', 1);
$enablestock			= $cparams->get('enablestock', 1);
$enableprice			= $cparams->get('enableprice', 1);
$facebooklike			= $cparams->get('facebooklike',1);
$fbappid				= $cparams->get('appid');
$fb_width				= $cparams->get('fb_width','80');
$fb_action				= $cparams->get('fb_action','like');
$twitter				= $cparams->get('twitter',1);
$googleplus				= $cparams->get('googleplus',1);
$linkedin				= $cparams->get('linkedin',1);
$products_category_link = $cparams->get('products_category_link',1);
$products_category_filter = $cparams->get('products_category_filter',1);
$cat_filter_treshold	= $cparams->get('cat_filter_treshold',1);
$show_address			= $cparams->get('show_address',0);
$enable_vendormap		= $cparams->get('enable_vendormap',0);
$map_width				= $cparams->get('map_width','700');
$map_height				= $cparams->get('map_height','300');
$enable_rating			= $cparams->get('enable_rating',1);
$rating_stars			= $cparams->get('rating_stars','5');
$enable_jusergroup		= $cparams->get('enable_jusergroup',1);
$enable_jcomments		= $cparams->get('enable_jcomments',0);
$enable_fbcomments		= $cparams->get('enable_fbcomments',0);
$vmvcomments_enable		= $cparams->get('vmvcomments_enable',0);
$log_visits				= $cparams->get('log_visits',1);
$modaltype				= $cparams->get('modaltype','s');
$tipclass				= $cparams->get('tipclass','');
$userid 				= $app->input->getInt('userid');
if(!$userid)
	$userid = $user->id;
$currency_symbol 			= $this->session_currency[1];
$currency_positive_style	= $this->session_currency[2];
$currency_decimal_place 	= $this->session_currency[3];
$currency_decimal_symbol 	= $this->session_currency[4];
$currency_thousands 		= $this->session_currency[5];
$currency_session3			= $this->session_currency[6];

$vendor_store_desc 			= $this->vendor_data[0];
$vendor_terms_of_service	= $this->vendor_data[1];
$vendor_legal_info			= $this->vendor_data[2];	
$vendor_store_name			= ucfirst($this->vendor_data[3]);
$vendor_phone				= $this->vendor_data[4];
$vendor_url					= $this->vendor_data[5];
$vendor_id 					= $this->vendor_data[6];

$page_title = $doc->getTitle('Browser Title');
$doc -> setTitle( $page_title . ' - '. ucfirst( $vendor_store_name ) );
$Itemid = $app->input->getInt('Itemid');

if($this->vendor_thumb_url)
	$vendor_thumb_url = $juri.$this->vendor_thumb_url;
else
	$vendor_thumb_url = $juri.'components/com_vmvendor/assets/img/noimage.gif';
$thumb_title = $vendor_store_name;

$doc->addCustomTag('<meta property="og:image" content="'.$vendor_thumb_url.'"/>');
$doc->addCustomTag('<meta property="og:site_name" content="'.$vendor_store_name.'"/>');
$doc->addCustomTag('<meta property="og:description" content="'.strip_tags($vendor_store_desc).'"/>');
if ($fbappid!="")
{
	echo '<div id="fb-root"></div>
	<script>(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/'.$langtag.'/sdk.js#xfbml=1&version=v2.5&appId='.$fbappid.'";
	fjs.parentNode.insertBefore(js, fjs);
	}(document, \'script\', \'facebook-jssdk\'));</script>';
}
if($facebooklike OR $twitter OR $googleplus OR $linkedin)
{
	$uri 	 =  JFactory::getURI();
	$prot	 = $uri->getScheme();
	$href	 = $prot.'://'.$_SERVER['HTTP_HOST'].JRoute::_('index.php?option=com_vmvendor&view=vendorprofile&userid='.$userid.'&Itemid='.$Itemid);
	$encodedurl = urlencode($href);
	echo '<div id="social_share" >';
	if($facebooklike>0)
	{
		$fb_lang = $langtag;
		echo '<div id="vm_fblike" style="width:'. $fb_width .'px;height:50px;float:right;text-align:right;">';
		if($facebooklike==1)
		{
			echo '<iframe src="//www.facebook.com/plugins/like.php?locale='.$fb_lang.'&amp;href='.$encodedurl.'&amp;layout=box_count&amp;show_faces=false&amp;width='.$fb_width.'&amp;action='.$fb_action.'&amp;font=arial&amp;colorscheme=light" 
			scrolling="no" frameborder="0" allowTransparency="true" 
			style="border:none;overflow:hidden; width:'.$fb_width.'px;"></iframe>';
		}
		elseif($facebooklike==2)
		{
			echo '<div class="fb-like" data-action="'.$fb_action.'" data-send="true" data-layout="button_count" 
			data-width="120" data-show-faces="false" data-share="false" ></div>';
		}
		echo '</div>';
	}			
	if($linkedin)
	{
		$doc->addScript('//platform.linkedin.com/in.js');
		echo '<div style="width:60px;float:right;"><div id="vm_linkedin"> <script type="IN/Share" 
		data-url="'.$href.'" data-counter="right" ></script></div></div>';
	}		
	if($googleplus)
	{
		$doc->addScript('//apis.google.com/js/plusone.js');
		echo '<div id="vm_plusone" style="width:70px;float:right;" >
		<g:plusone size="medium" count="1" callback="" ></g:plusone></div>';
	}	
	if($twitter)
	{
		$doc->addScript('//platform.twitter.com/widgets.js');
		echo '<div id="vm_tweet" style="margin-right:5px;float:right;" >
		<a href="http://twitter.com/share" class="twitter-share-button" 
		data-count="vertical" data-counturl="'.$href.'" data-url="'.$href.'" 
		data-lang="'.substr($langtag,0,2).'" ></a></div>';			
	}
	echo '</div>';	
}

echo '<h1>'.JText::_('COM_VMVENDOR_PROFILE_TITLE').'</h1>';
echo '<div class="clear"></div>';
echo '<div class="vmvendor-toolbar btn-group" >';
if($user->id>0 && $this->ismyprofile)
{
	if($log_visits)
	{
		echo '<a  href="'.JRoute::_('index.php?option=com_vmvendor&view=profilevisits' ).'" 
		class="btn btn-primary  '.$tipclass.'" 
		title="'.JText::_( 'COM_VMVENDOR_PROFILEVISITS' ).'"><i class="vmv-icon-eye"></i> </a>';
	}
	$dashboard_url = JRoute::_('index.php?option=com_vmvendor&amp;view=dashboard&amp;Itemid='
	.$this->dashboard_itemid);
	echo '<a href="'.$dashboard_url.'" class="btn btn-primary '.$tipclass.'" 
	title="'.JText::_( 'COM_VMVENDOR_PROFILE_DASHBOARD' ).'"><i class="vmv-icon-cog " ></i></a>';

	$editprourl = 'index.php?option=com_vmvendor&view=editprofile';
	if($this->editprofile_itemid)
		$editprourl .= '&Itemid='.$this->editprofile_itemid;
	echo '<a  href="'.JRoute::_( $editprourl ).'" 
	class="btn btn-primary '.$tipclass.'" 
	title="'.JText::_( 'COM_VMVENDOR_PROFILE_EDITPROFILE' ).'"><i class="vmv-icon-edit"></i> </a>';

}

		
echo '</div>';
echo '<div id="vendor-img-rating">';
echo '<div id="vendor-img">';

if($this->ismyprofile)
{
	$thumb_title = JText::_( 'COM_VMVENDOR_PROFILE_CLICKTOCHANGEAVATAR' );
	echo '<a href="'.JRoute::_('index.php?option=com_vmvendor&amp;view=editprofile&Itemid='.$Itemid).'" >';
}
if(!$img_width)
	$img_width ='90';
echo '<img src="'.$vendor_thumb_url.'" alt="" width="'.$img_width.'"  title="'.$thumb_title.'" class="'.$tipclass.'"/>';
if($this->ismyprofile)
	echo '</a>';
echo '</div>';
if($enable_jusergroup && $this->jgroup!='')
{
	echo '<div id="vmv_usergroup" class="badge badge-success">'.JText::_( $this->jgroup) .'</div>';	
}
	
if($enable_rating)
{
	$doc->addScript($juri.'components/com_vmvendor/assets/js/jquery.rating.js');
	$doc->addStylesheet($juri.'components/com_vmvendor/assets/css/jquery.rating.css');
	$vendor_rating = VmvendorModelVendorprofile::getVendorRating($userid);
	if(!$vendor_rating)
	{
		$vendor_rating['count']=0 ;
		$vendor_rating['percent']=0 ;
	}
	echo '<div id="vendor_stars" class="vmvrating">';
	echo '<form id="form1" class="form-inline">';
	$percent_in_stars = round( ($vendor_rating['percent'] * $rating_stars )/100 ,1,PHP_ROUND_HALF_UP) ;
	echo '<div id="rate1" class="vmvrating">';
	echo "<script type=\"text/javascript\">jQuery('#rate1').rating('index.php?option=com_vmvendor&view=vendorprofile_rating&format=raw&vendor_user_id=".$userid."', {maxvalue:".$rating_stars.", increment:.5, curvalue:".$percent_in_stars."});
		</script>";
	echo '</div>';
	echo '</form>';
	echo '</div>';		
	$icount= '0';
	if($vendor_rating['count']>0)
		$icount = $vendor_rating['count'];
	echo '<div id="rating_result">
	'.JText::_('COM_VMVENDOR_PROFILE_RATING').': '.number_format( ( $vendor_rating['percent'] * $rating_stars)/100 ,2,'.','') .' - '.JText::_('COM_VMVENDOR_PROFILE_VOTES').': '.$icount.'</div>';			
}		
echo '</div>
<div id="storename">
<h2>'.$vendor_store_name.'</h2>';
if($profileman)
{
	$avtr_class ='img-circle';
	$user_naming = $this->user_thumb[0];
	if($profileman!='es')
		$user_avatar = $this->user_thumb[1];
	if($profileman=='cb')
	{
		$user_avatar = $juri.'images/comprofiler/'.$this->user_thumb[1];
		$avtr_class ='';
	}
	elseif($profileman=='js')
	{
		$user_avatar = $juri.$this->user_thumb[1];
	}
	elseif($profileman=='es')
	{
		require_once JPATH_ADMINISTRATOR . '/components/com_easysocial/includes/foundry.php';
		$doc->addStyleSheet( $juri.'components/com_easysocial/themes/wireframe/styles/base.min.css' );
		$doc->addStyleSheet( $juri.'components/com_easysocial/themes/wireframe/styles/style.min.css' );
		$doc->addStyleSheet( $juri.'components/com_easysocial/themes/wireframe/styles/more.min.css' );
		Foundry::language()->load( 'com_easysocial' , JPATH_ROOT );
		$modules    = Foundry::modules( 'mod_easysocial_toolbar' );
		$modules->loadComponentScripts();
		$config 	= Foundry::config();
		$es_followers = $config->get( 'followers.enabled' );
		$es_user = Foundry::user($userid);
		$user_avatar = $es_user->getAvatar();
	}
	if($profileman!='es')
	{
		if(! $this->user_thumb[1] )
		{
			if($profileman=='cb')
				$user_avatar = $juri.'components/com_comprofiler/plugin/templates/default/images/avatar/tnnophoto_m.png';
			elseif($profileman=='js')
				$user_avatar = $juri.'components/com_community/assets/user-Male-thumb.png';
		}
	}
}	
echo '<div style="padding:0 0 50px 5px;">
<div style="float:left;width:50px">';
if($profileman !='0')
{
	if($profileman =='es')
		echo '<a data-user-id="'.$userid.'" data-popbox="module://easysocial/profile/popbox" >';
	echo '<img src="'.$user_avatar.'" width="40" alt="'.ucfirst($user_naming).'" 
	style="vertical-align:middle" class="'.$avtr_class.'" />';
	if($profileman =='es')
		echo '</a>';
}
echo '</div>';
echo '<div style="float:left">';
if($profileman !='0')
	echo '<h4>'. ucfirst($user_naming).'</h4>';
	
echo '<div id="icon-buttons" class="btn-group">';
if($profileman!='0')
{
	echo '<a href="'.$this->social_profile_url.'" class="btn btn-default '.$tipclass.'"  
	title="'.JText::_( 'COM_VMVENDOR_PROFILE_VISITUSERPROFILE' ).'">
	<i class="vmv-icon-user" data-user-id="'.$userid.'" 
	data-popbox="module://easysocial/profile/popbox"></i></a>';
}
if( $enablerss && file_exists(JPATH_BASE.'/media/vmvendorss/'.$userid.'.rss'))
{
	$feed_url = $juri.'media/vmvendorss/'.$userid.'.rss';
	echo '<a href="'.$feed_url.'" target="_blank" class="btn btn-default '.$tipclass.'"  
	title="'.JText::_( 'COM_VMVENDOR_PROFILE_RSSFEED' ).'" ><i class="vmv-icon-feed " ></i></a>';
}
if($vendor_phone)
	echo '<a class="btn btn-default '.$tipclass.'" title="'.$vendor_phone.'"><i class="vmv-icon-phone"></i></a>';
if($vendor_url)
	echo '<a href="'.$vendor_url.'" target="_blank" class="btn btn-default '.$tipclass.'" title="'.$vendor_url.'"><i class="vmv-icon-link"></i></a>';
if($userid != $user->id)
{
	if( $vendorcontactform == 2)
	{
		if($profileman=='js')
		{
			require_once JPATH_BASE . '/components/com_community/libraries/core.php';
			CWindow::load();
			echo  '<a href="javascript:" onclick="joms.api.pmSend('.$userid.');" 
			class="btn btn-default '.$tipclass.'" title="'.JText::_( 'COM_VMVENDOR_PROFILE_PMVENDOR' ).'">
			<i class="vmv-icon-mail" ></i></a>';
		}
		elseif($profileman=='es')
		{
			echo  '<a href="javascript:void(0)" data-es-conversations-compose 
			data-es-conversations-id="'.$userid.'" class="btn btn-default '.$tipclass.'" 
			title="'.JText::_( 'COM_VMVENDOR_PROFILE_PMVENDOR' ).'">
			<i class="vmv-icon-mail" ></i></a>';
		}
	}
	else
	{	 
		$iframe_url = 'index.php?option=com_vmvendor&view=askvendor&vendoruserid='.$userid.'&tmpl=component';
		if($modaltype=='b')
		{
			$name = "askvendor";
			$html = '<a href="#modal-' . $name.'"  data-toggle="modal" 
			class="btn btn-default '.$tipclass.'" title="'.JText::_('COM_VMVENDOR_PROFILE_EMAILVENDOR').'">
			<i class="vmv-icon-mail"></i></a>';
			$params = array();
			$params['title']  = JText::_('COM_VMVENDOR_PROFILE_EMAILVENDOR'). ' '.$vendor_store_name;
			$params['url']    = $iframe_url;
			$params['height'] = "400";
			$params['width']  = "100%";
			$footer='';
			$html .= JHtml::_('bootstrap.renderModal', 'modal-' . $name, $params, $footer);
			echo $html;	
		}
		elseif($modaltype=='j')
		{
			echo '<a class="btn btn-default  modal '.$tipclass.'" 
			title="'.JText::_('COM_VMVENDOR_PROFILE_EMAILVENDOR').'" 
			href="'.JRoute::_( $iframe_url ).'" 
 			rel="{handler: \'iframe\', size: {x: 550, y: 550}}">
			<i class="vmv-icon-mail" ></i></a> ';
		}
		elseif($modaltype=='s')
		 {
			$doc->addScript( $juri.'components/com_vmvendor/assets/js/sweetalert/sweetalert2.min.js');
		 	$doc->addStylesheet( $juri.'components/com_vmvendor/assets/js/sweetalert/sweetalert2.css'); 
			$emailvendor = "function emailvendor(){
			swal({
				  
				  html:
					'<iframe src=\"".$iframe_url."\" id=\"askvendor_iframe\" >' +
					
					'</iframe>',
					width: 600,
				  showCloseButton: false,
				  showConfirmButton: false,
				  showCancelButton: false
				 
				})
			}";
			$doc->addScriptDeclaration($emailvendor);
			 echo  '<a href="#" 
			class="btn  btn-default '.$tipclass.'"  onclick="emailvendor()" 
			title="'.JText::_('COM_VMVENDOR_PROFILE_EMAILVENDOR').'" >
			<i class="vmv-icon-mail" ></i></a>';
			 
		 }
	}
}
if($profileman=='es' && $es_followers)
{
	//$es_user     = Foundry::user($userid);
	$isfollowed = $es_user->isFollowed($user->id);
	if(!$isfollowed && $userid != $user->id)
	{
		echo '<a href="javascript:void(0);"  class="btn btn-success" 
		data-es-followers-follow data-es-followers-id="'.$userid.'">
		<i class="vmv-icon-follow" ></i> '. JText::_( 'COM_VMVENDOR_PROFILE_FOLLOW' ).'
		</a>';
	}
	else
	{
		echo '<a class="author-friend btn btn-default" disabled>
		<i class="vmv-icon-follow" ></i> '. JText::_( 'COM_VMVENDOR_PROFILE_FOLLOWING' ).'
		</a>';						
	}
}
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '<div class="clear" ></div>';
if($vmvcomments_enable)
{
	$comments_url = JRoute::_('index.php?option=com_vmvendor&view=comments&vendoruserid='.$userid);
	$commentform_url = JRoute::_('index.php?option=com_vmvendor&view=commentform&vendoruserid='.$userid);
	echo '<div id="vmvcomment" ><a name="vmvcomments"></a>';
	echo '<div id="vmvcomment-title"><i class="vmv-icon-comment ">
		</i> '.JText::_('COM_VMVENDOR_CUSTOMERREVIEWS_LATEST').'</div>';
	echo '<div id="vmvcomments">';
	$i = 0;
	//var_dump( $this->vmvcomments);
	foreach( $this->vmvcomments as $vmvcomment)
	{
		if($i<3)
		{
			echo '<div> <i class="vmv-icon-user '.$tipclass.'" title="'.$vmvcomment->$naming.'" ></i> 
			| <i><b>" </b> <span title="'.ucfirst($vmvcomment->comment).'" 
			class="'.$tipclass.'">'.ucfirst($vmvcomment->title).'</span></i></div> ';
		}
		$i++;
	}
	if(!$i)
		echo JText::_('COM_VMVENDOR_COMMENTS_NOREVIEWYET');
	if(!$this->ismyprofile)
	{
			echo ' <a class="btn btn-small btn-mini btn-success '.$tipclass.'" 
			href="'.$commentform_url.'" title="'.JText::_('COM_VMVENDOR_COMMENTS_ADD_REVIEW').'">
			<i class="vmv-icon-plus"></i><i class="vmv-icon-comment"></i></a> ';
	}
	echo ' <a class="btn btn-small btn-mini" 
			href="'.$comments_url.'" >'.JText::_('COM_VMVENDOR_COMMENTS_MORE').'</a>';
	echo '</div>';
	echo '</div>';
	echo '<div class="clear" ></div>';
}
$headline_modules = JModuleHelper::getModules('vmv-profile-headline');
if(count($headline_modules))
{
	foreach ($headline_modules as $headline_module)
	{	
		if($headline_module->params)
		{
			$headerclass = json_decode($headline_module->params);
			$icon_class = $headerclass->{'header_class'};
		}
		else
			$icon_class ='';
		echo '<div class="vmv-headline-mod"><i class="'.$icon_class.'" ></i> '.JModuleHelper::renderModule($headline_module).'</div>';
	}
}
echo '<a name="vendortabs" ></a>';
///////////////// start tab nav header
echo JHtml::_('bootstrap.startTabSet', 'vendorprofileTab', $this->tabsOptions );
echo JHtml::_('bootstrap.addTab', 'vendorprofileTab', 'vendorprofileTab_1',
 '<i class="vmv-icon-list"></i> '.JText::_('COM_VMVENDOR_PROFILE_PRODUCTS') , 'class="active"' ); 
echo '<div>';
if($products_category_filter && count($this->allmyproducts)>1 )
{
	// category filter
	$avail_cats 	= array();
	$parent_cats 	= array();
	$done_cats 		= array();
	if($products_category_filter=='2' && count($this->allmyproducts) < $cat_filter_treshold)
	{
		$i = 0;
		foreach($this->allmyproducts as $product)
		{
			$loop =VmvendorModelVendorprofile::get_filterparentcats($product->virtuemart_category_id , $parent_cats, $i);
			$parent_cats[] =  $loop;
			$avail_cats[]  = $product->virtuemart_category_id; 
			$i++;
		}
		$avail_cats  = array_unique($avail_cats);
		$avail_catz = $avail_cats; // we need a different var s it doesn't get altered by next line's function
		$parent_cats = array_values( VmvendorModelVendorprofile::array_keys_multi($parent_cats , $avail_catz , $done_cats ));
	}
	$formaction = 'index.php?option=com_vmvendor&view=vendorprofile';
	if($userid!='')
		$formaction .='&userid='.$userid;
	$formaction .='&Itemid='.$Itemid.'#vendortabs';
	echo '<div class="vmv-form-group" >
	<form method="POST" action="'.JRoute::_($formaction).'">
	<i class="vmv-icon-filter '.$tipclass.'"  title="'.JText::_('COM_VMVENDOR_PROFILE_CATFILTER').'"></i>
	<select id="catfilter" name="catfilter" class="form-control" onchange="this.form.submit();"  >
	<option value="">'.JText::_('COM_VMVENDOR_PROFILE_ALLCATS').'</option>';
	$traverse = VmvendorModelVendorprofile::traverse_tree_down( 0,0, $avail_cats,$parent_cats);
	echo '</select>
	</form></div>';
}
if($this->ismyprofile)
{
	echo '<div id="add-product">
	<a href="'.JRoute::_('index.php?option=com_vmvendor&view=addproduct&Itemid='.$this->addproduct_itemid).'" 
	class="btn btn-primary btn-xs btn-mini "><i class="vmv-icon-plus">
	</i> '.JText::_( 'COM_VMVENDOR_PROFILE_ADDAPRODUCT' ).'</a></div><div style="clear:both"></div>';
}
echo '</div>';	
echo '<div style="clear:both"></div>';		
echo '<div id="vmvcontainer" data-masonry=\'{ "columnWidth": 150, "itemSelector": ".vmvthumb" }\'>';
foreach($this->myproducts as $product)
{
	if(!$use_as_catalog)
	{
		$vmproducts = $productModel->getProducts ( array($product->virtuemart_product_id) );
		if($showCustoms)
			shopFunctionsF::sortLoadProductCustomsStockInd($vmproducts,$productModel);
	}
			
			
	$product_url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$product->virtuemart_product_id.'&virtuemart_category_id='.$product->virtuemart_category_id.'&Itemid='.$this->vmitemid);
	$category_url = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$product->virtuemart_category_id.'&Itemid='.$this->vmitemid);
	
	//to be moved to model 
	$q ="SELECT vm.`file_url_thumb` , vm.file_url 
	FROM `#__virtuemart_medias` vm 
	LEFT JOIN `#__virtuemart_product_medias` vpm ON vpm.`virtuemart_media_id` = vm.`virtuemart_media_id` 
	WHERE vpm.`virtuemart_product_id`='".$product->virtuemart_product_id."' 
	AND vm.`file_mimetype` LIKE 'image/%' 		
	ORDER BY vpm.ordering ASC";  // orderby  with vm.`file_is_product_image` DESC , 
	$db->setQuery($q);
	$prod_images =  $db->loadRow();
	$thumburl = $prod_images[0];
	$large_url= $prod_images[1];  
	if (!$thumburl  && $large_url!='' )
	{  // required in case pictures are added via the backend
		$thumburl = str_replace('virtuemart/product/','virtuemart/product/resized/',$large_url);
		$thum_side_width			=	VmConfig::get( 'img_width' );
		$thum_side_height			=	VmConfig::get( 'img_height' );
		$extension_pos = strrpos($thumburl, "."); // find position of the last dot, where extension starts
		$thumburl = substr($thumburl, 0, $extension_pos) . '_'.$thum_side_width.'x'.$thum_side_height . substr($thumburl, $extension_pos);
	}
	if(!$thumburl)
		$thumburl = 'components/com_virtuemart/assets/images/vmgeneral/'.VmConfig::get('no_image_set');
	echo '<div class="vmvthumb " >';
	echo '<span title="'.ucfirst( strip_tags($product->product_s_desc) ).'" class="'.$tipclass.'"  >';
	echo '<div class="prodtitle">';
	if($this->ismyprofile)
	{			
		if($product->published)
		{
			echo '<div class="badge badge-success '.$tipclass.'" title="'.JText::_('JPUBLISHED').'">
			<i class="vmv-icon-ok ""></i></button>';
		}
		else
		{
			echo '<div class="badge badge-warning '.$tipclass.'" title="'.JText::_('JUNPUBLISHED').'">
			<i class="vmv-icon-cancel"></i></button> ';
		}
		echo '</div > ';
	} 

	echo '<a href="'.$product_url.'" >'.ucfirst($product->product_name).'</a>';
	echo '</div>';
	echo '<div class="prodpic">';
	echo '<a href="'.$product_url.'" ><img src="'.$juri.$thumburl.'"  alt="'.$product->product_name.'" /></a>';
	echo '<div class="prodcat"   >';
	if($products_category_link)
	{
		echo '<a href="'.$category_url.'" 
		title="'.JText::_( 'COM_VMVENDOR_PROFILE_VISITCAT' ).'" class="'.$tipclass.'" >
		<i class="vmv-icon-folder-open"></i></a> '.JText::_($product->category_name).'';
	}
	else
	{
		echo '<i class="vmv-icon-folder-open"></i> '.JText::_($product->category_name).'';
	}
	echo '</div>';
	if($enableprice)
	{
		echo '<div class="prodprice">';
		$product_product_price = $product->product_price;
		if( $this->session_currency[6] != $product->currency_code_3)
		{
			$product_product_price = VmvendorModelVendorprofile::convertPrice( 
			$product_product_price, 
			$product->currency_code_3 , 
			$this->session_currency[6] 
			);
		}
		$product_with_tax = VmvendorModelVendorprofile:: applytaxes($product_product_price , $product->virtuemart_category_id , $product->virtuemart_manufacturer_id , $vendor_id);
		$res = number_format((float)$product_with_tax,$currency_decimal_place,$currency_decimal_symbol,$currency_thousands);
		$search = array('{sign}', '{number}', '{symbol}');
		if($product->override=='1')
		{
			$product_override_with_tax = VmvendorModelVendorprofile:: applytaxes($product->product_override_price , $product->virtuemart_category_id , $product->virtuemart_manufacturer_id , $vendor_id);
			$res_override = number_format((float)$product_override_with_tax,$currency_decimal_place,$currency_decimal_symbol,$currency_thousands);
			$replace_override = array('+', $res_override, $currency_symbol);
			$formattedRounded_price_override = str_replace ($search,$replace_override,$currency_positive_style);
		}	
		$replace = array('+', $res, $currency_symbol);
		$formattedRounded_price = str_replace ($search,$replace,$currency_positive_style);
		if($product->override=='1')
			echo  '<div><s>'.$formattedRounded_price.'</s></div>';
		else
			echo  '<div><a href="'.$product_url.'" >'.$formattedRounded_price.'</a></div>';
		if($product->override=='1')
			echo  '<div><a href="'.$product_url.'" >'.$formattedRounded_price_override.'</a></div>';
		echo '</div>';
	}
	echo '</div>';
	echo '</span>';
	if(!$use_as_catalog && $product->published)
	{
		echo '<div class="add2cartcontainer">';
		$array['product'] = $vmproducts[0];
		$vmproducts[0]->position = 'addtocart';
		echo @shopFunctionsF::renderVmSubLayout('addtocart', $array ,'');
		echo '</div>';
	}
	if($this->ismyprofile )
	{
		echo '<div class="product-edit " style="text-align:center;">';
		if($allow_deletion!='0')
		{
			$confirm_alert="function sweetConfirm(it)
			{
				swal(
					{   title: '', 
				  text: '".JText::_( 'COM_VMVENDOR_PROFILE_SUREDELETE' )."', 
				  type: 'warning',
				  confirmButtonColor: 'green',   
				  cancelButtonColor: '#d33',   
				  confirmButtonText: '".JText::_('JYes')."',
				  showCancelButton: true,   
					},
					function(confirm) 
					{  
						if(confirm)
							it.submit();
					}
				)
			}";
			$doc->addScriptDeclaration( $confirm_alert );
			echo '<form name="delete_product" id="delete_product'.$product->virtuemart_product_id.'" 
			style="margin:0" method="post" >';
		}
		echo '<div style="float:left" class="btn-group" >';
		echo '<a href="'.JRoute::_('index.php?option=com_vmvendor&view=editproduct&productid='.$product->virtuemart_product_id.'&Itemid='.$this->addproduct_itemid).'" title="'.JText::_( 'COM_VMVENDOR_PROFILE_EDITPRODUCT' ).'" 
		class="btn btn-default btn-xs btn-mini '.$tipclass.'"><i class="vmv-icon-edit"></i></a>';

		if($allow_deletion!='0')
		{
			echo '<input type="hidden" name="option" value="com_vmvendor" />
			<input type="hidden" name="controller" value="vendorprofile" />
			<input type="hidden" name="task" value="deleteproduct" />
			<input type="hidden" name="delete_productid" value="'.$product->virtuemart_product_id.'" />
			<input type="hidden" name="userid" value="'.$userid.'" />
			<input type="hidden" name="price" value="'.$product->product_price.'" />';
			echo '<a title="'.JText::_( 'COM_VMVENDOR_PROFILE_DELPRODUCT' ).'"  
			class="btn btn-default btn-xs btn-mini  '.$tipclass.'" 
			onclick="sweetConfirm( document.getElementById(\'delete_product'.$product->virtuemart_product_id.'\') );" >
			<i class="vmv-icon-trash"></i></a>';
			echo '</form>';
		}
		echo '</div>';
		if($enablestock)
		{
			echo '<div id="product_in_stock"  title="'.JText::_( 'COM_VMVENDOR_PROFILE_INSTOCK' ).'" class="'.$tipclass.'" >';
			echo '<i class="vmv-icon-grid"></i> ';
			echo $product->product_in_stock;
			echo '</div>';
		}
		echo '</div>';
	}
	echo '</div>';
}
echo '</div>';
if(	count($this->myproducts) >2 )
{
	echo '<script type="text/javascript" src="'.$juri.'components/com_vmvendor/assets/js/jquery.masonry.min.js"></script>';
}		
echo '<div style="clear:both" ></div>';
echo '<div class="pagination center" >';
echo '<div>'.$this->pagination->getResultsCounter().'</div>';
echo '<div>'.$this->pagination->getPagesLinks().'</div>';
echo '<div>'.$this->pagination->getPagesCounter().'</div>';
echo '</div>';
echo '<div style="clear:both"></div>';
echo JHtml::_('bootstrap.endTab');
echo JHtml::_('bootstrap.addTab', 'vendorprofileTab', 'vendorprofileTab_2', '<i class="vmv-icon-shop"></i> '.JText::_('COM_VMVENDOR_PROFILE_VENDORPROFILE')  );
if($show_address )
{
	echo JHtml::_('bootstrap.startTabSet', 'vendordetailsTab', array( 'active' => 'vendordetailsTab_0' ) );
	echo JHtml::_('bootstrap.addTab', 'vendordetailsTab', 'vendordetailsTab_0', '<i class="vmv-icon-location"></i> '.JText::_('COM_VMVENDOR_EDITPRO_VENDORADDRESS') , 'class="active"' );
	$lat = $this->vendor_data[12];
	$lng =$this->vendor_data[13];
	$map_width = 250;
	$map_height = 140;
	$mapurl = '//maps.googleapis.com/maps/api/staticmap?size='.$map_width.'x'.$map_height.'&center='.$lat.','.$lng.'&zoom=5&maptype=roadmap&markers=size:normal%7Ccolor:black%7C'.$lat.','.$lng;
	echo'<div id="vmv_map" name="vmv_map" title="'.JText::_('COM_VMVENDOR_PROFILE_CLICLTOZOOM').'" class="'.$tipclass.' vmv_addressmap" >';
	echo '<a  onmouseover="document.vmvmap.src = \''.str_replace('zoom=5', 'zoom=10', $mapurl).'\';" 
		onmouseout="document.vmvmap.src = \''.$mapurl.'\';" 
		onclick="document.vmvmap.src = \''.str_replace('zoom=5', 'zoom=15', $mapurl).'\';">';
	echo '<img src="'.$mapurl.'" name="vmvmap" alt="Map"  width="'.$map_width.'" height="'.$map_height.'" class="img-rounded">';
	echo '</a>';
	echo '</div>';
	echo '<div class="vmv_addressmap">';
	echo '<div><strong>'.JText::_('COM_VMVENDOR_EDITPRO_VENDORADDRESS').'</strong> '.$this->vendor_data[7].'</div>';
	echo '<div><strong>'.JText::_('COM_VMVENDOR_EDITPRO_VENDORCITY').'</strong> '.$this->vendor_data[9].'</div>';
	echo '<div><strong>'.JText::_('COM_VMVENDOR_EDITPRO_VENDORZIP').'</strong> '.$this->vendor_data[8].'</div>';
	if($this->vendor_data[11] && $use_statefield)
		echo '<div><strong>'.JText::_('COM_VMVENDOR_EDITPRO_VENDORSTATE').'</strong> '.$this->vendor_data[11].'</div>';
	echo '<div><strong>'.JText::_('COM_VMVENDOR_EDITPRO_VENDORCOUNTRY').'</strong> '.$this->vendor_data[10].'</div>';
	if($vendor_phone)
		echo '<div><strong><i class="vmv-icon-phone"></i></strong> '.$vendor_phone.'</div>';
	echo '</div>
	<div class="clear"></div>';
	echo JHtml::_('bootstrap.endTab');
}
else
	echo JHtml::_('bootstrap.startTabSet', 'vendordetailsTab', array( 'active' => 'vendordetailsTab_1' ) );		
echo JHtml::_('bootstrap.addTab', 'vendordetailsTab', 'vendordetailsTab_1', '<i class="vmv-icon-shop"></i> '.JText::_('COM_VMVENDOR_PROFILE_DESCRIPTION') , 'class="active"' ); 
if($vendor_store_desc)
	echo $vendor_store_desc;
else
	echo JText::_('COM_VMVENDOR_PROFILE_NOTFILLEDINYET');
if($user->id>0 && $this->ismyprofile)
{
	echo '<br /><a  href="'.JRoute::_('index.php?option=com_vmvendor&amp;view=editprofile').'#desc" 
	class="btn btn-default btn-xs btn-mini '.$tipclass.'" title="'.JText::_( 'COM_VMVENDOR_PROFILE_EDITPROFILE' ).'">
	<i class="vmv-icon-edit"></i></a>';
}
echo JHtml::_('bootstrap.endTab');

if(!$use_as_catalog)
{
	echo JHtml::_('bootstrap.addTab', 'vendordetailsTab', 'vendordetailsTab_2','<i class="vmv-icon-doc"></i> '. JText::_('COM_VMVENDOR_PROFILE_TERMSOFSERVICE') , '' ); 
	if($vendor_terms_of_service)
		echo $vendor_terms_of_service;
	else
		echo JText::_('COM_VMVENDOR_PROFILE_NOTFILLEDINYET');
	if($user->id>0 && $this->ismyprofile)
	{
		echo '<br /><a  href="'.JRoute::_('index.php?option=com_vmvendor&amp;view=editprofile').'#tos" 
		class="btn btn-default btn-xs btn-mini  '.$tipclass.'" 
		title="'.JText::_( 'COM_VMVENDOR_PROFILE_EDITPROFILE' ).'" ><i class="vmv-icon-edit"></i></a>';
	}
	echo JHtml::_('bootstrap.endTab');			
	echo JHtml::_('bootstrap.addTab', 'vendordetailsTab', 'vendordetailsTab_3', '<i class="vmv-icon-hammer"></i> '.JText::_(	'COM_VMVENDOR_PROFILE_LEGALINFO') , '' ); 
	if($vendor_legal_info)
		echo $vendor_legal_info;
	else
		echo JText::_('COM_VMVENDOR_PROFILE_NOTFILLEDINYET');	
	if($user->id>0 && $this->ismyprofile)
	{
		echo '<br /><a  href="'.JRoute::_('index.php?option=com_vmvendor&amp;view=editprofile').'#legal" 
		class="btn btn-default btn-xs btn-mini '.$tipclass.'" 
		title="'.JText::_( 'COM_VMVENDOR_PROFILE_EDITPROFILE' ).'"><i class="vmv-icon-edit"></i></a>';
	}
	echo JHtml::_('bootstrap.endTab');
}
echo JHtml::_('bootstrap.endTabSet');
echo JHtml::_('bootstrap.endTab');
	
$tab_modules = JModuleHelper::getModules('vmv-profile-tab');
$i = 1;
if(count($tab_modules))
{
	foreach ($tab_modules as $tab_module)
	{
		if($tab_module->params)
		{
			$headerclass = json_decode($tab_module->params);
			$icon_class = $headerclass->{'header_class'};
		}
		else
			$icon_class ='';
		echo JHtml::_('bootstrap.addTab', 'vendorprofileTab', 'module'.$i, '<i class="'.$icon_class.'" ></i> '.JText::_($tab_module->title)  );	
		echo '<div>'.JModuleHelper::renderModule($tab_module).'</div>';
		$i++;
		echo JHtml::_('bootstrap.endTab');
	}
}
			
if($enable_jcomments)
{
	echo JHtml::_('bootstrap.addTab', 'vendorprofileTab', 'vendorprofileTab_4', '<i class="vmv-icon-comment"></i> '.JText::_('COM_VMVENDOR_PROFILE_COMMENTS')  );
	$jcomments = JPATH_BASE . '/components/com_jcomments/jcomments.php';
	if (file_exists($jcomments))
	{
		require_once $jcomments;
		echo JComments::showComments($userid, 'com_vmvendor', $vendor_store_name);
	}
	else
	{
		echo '<div class="alert alert-danger">
		jComments component required! Download it free from <a href="http://www.joomlatune.com/jcomments.html" 
		target="_blank">here</a></div>';
	}
	echo JHtml::_('bootstrap.endTab');
}
echo JHtml::_('bootstrap.endTabSet');
if($enable_fbcomments)
{
	echo '<hr /><h3><a name="vmv-fbcomments"></a>
	<i class="vmv-icon-comment"></i> '.JText::_('COM_VMVENDOR_PROFILE_FBCOMMENTS').'</h3> 
	<div class="fb-comments" data-href="'.$href.'" data-width="750" data-numposts="5" 
	data-colorscheme="light" data-numposts="5"></div>';	
}
$bot_modules = JModuleHelper::getModules('vmv-profile-bot');
if(count($bot_modules))
{
	foreach ($bot_modules as $bot_module)
	{	
		if($bot_module->params)
		{
			$headerclass = json_decode($bot_module->params);
			$icon_class = $headerclass->{'header_class'};
		}
		else
			$icon_class ='';
		echo '<h3 class="module-title"><i class="'.$icon_class.'" ></i> '.$bot_module->title.'</h3>
		<div>'.JModuleHelper::renderModule($bot_module).'</div>';
	}
}
?>