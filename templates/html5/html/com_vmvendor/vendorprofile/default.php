<?php

defined('_JEXEC') or die('Restricted access');
$browsecellwidth					= "width: 200px;";
$compaignslideid					= 1;
$active								= " active";
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
$doc->addStyleSheet($juri.'components/com_vmvendor/assets/css/vendorprofile.css');
echo '<link rel="stylesheet" href="'.$juri.'components/com_vmvendor/assets/css/fontello.css">';

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
$vmitemid	 			= $cparams->get('vmitemid');
$profileitemid			= $cparams->get('profileitemid');
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
$enable_vendormap		= $cparams->get('enable_vendormap',0);
$map_width				= $cparams->get('map_width','700');
$map_height				= $cparams->get('map_height','300');
$enable_rating			= $cparams->get('enable_rating',1);
$rating_stars			= $cparams->get('rating_stars','5');
$enable_jusergroup		= $cparams->get('enable_jusergroup',1);
$enable_jcomments		= $cparams->get('enable_jcomments',0);
$enable_fbcomments		= $cparams->get('enable_fbcomments',0);
$log_visits				= $cparams->get('log_visits',1);
$userid 				= $app->input->get('userid');
if(!$userid)
	$userid = $user->id;
$currency_symbol 			= $this->main_currency[0];
$currency_positive_style	= $this->main_currency[1];
$currency_decimal_place 	= $this->main_currency[2];
$currency_decimal_symbol 	= $this->main_currency[3];
$currency_thousands 		= $this->main_currency[4];
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
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>		
<?php //Вспомогательные кнопки ?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 other-btn-panel btn-group">
<?php //Filter
		if($products_category_filter && count($this->allmyproducts)>1 )
		{
			// category filter
			$avail_cats = array();
			
			$parent_cats = array();
			$done_cats = array();
			if($products_category_filter=='2')
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
			echo '<form method="POST" action="'.JRoute::_($formaction).'">
				<select id="catfilter" name="catfilter" class="form-control chosen" onchange="this.form.submit();"  >
				<option value="">'.JText::_('COM_VMVENDOR_PROFILE_ALLCATS').'</option>';
			$traverse = VmvendorModelVendorprofile::traverse_tree_down( 0,0, $avail_cats,$parent_cats);
			echo '</select></form>';
		}
?>
	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 col-lg-push-6">
	<button id="check-all" href="#" class="col-sm-12 col-md-12 col-lg-12  col-xs-12 btn btn-primary" disabled="disabled"><?php echo vmText::_ ( 'COM_VMVENDOR_CHECK_ALL' ); ?></button>
	</div>
	<div class="col-sm-6 col-md-6 col-lg-3 col-lg-push-6">
	<button id="fullscreen-btn" href="#" onclick="openbox('b1'); return false" class="col-sm-12 col-md-12 col-lg-12 btn btn-primary hidden-xs"><?php echo vmText::_ ( 'COM_VMVENDOR_FULLSCREEN' ); ?></button>	
	</div>
</div>
<div style="clear:both" ></div>
<div id="container" class="view js-masonry">
<?php
	foreach($this->myproducts as $product)
		{
	
				$product_url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$product->virtuemart_product_id.'&virtuemart_category_id='.$product->virtuemart_category_id.'&Itemid='.$vmitemid);
				$category_url = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$product->virtuemart_category_id.'&Itemid='.$vmitemid);
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
				$thumburl			= $db->loadResultArray();
				$count_images 		= $db->getAffectedRows ();	
				$rn					= rand(16,255);
				$gn					= rand(16,255);
				$bn					= rand(16,255);
				
				
				//if (!$thumburl  && $large_url!='' ){  // required in case pictures are added via the backend
				//	$thumburl = str_replace('virtuemart/product/','virtuemart/product/resized/',$large_url);
				//	$thum_side_width			=	VmConfig::get( 'img_width' );
				//	$thum_side_height			=	VmConfig::get( 'img_height' );
				//	$extension_pos = strrpos($thumburl, "."); // find position of the last dot, so where the extension starts
				//	$thumburl = substr($thumburl, 0, $extension_pos) . '_'.$thum_side_width.'x'.$thum_side_height . substr($thumburl, $extension_pos);
				//}
		
		
				//if(!$thumburl)
				//	$thumburl = 'components/com_virtuemart/assets/images/vmgeneral/'.VmConfig::get('no_image_set');
				?>
				
<div class="browse browsecellwidth" style="<?php echo $browsecellwidth; ?>;<?php $r = rand(16,255); $g = rand(16,255); $b = rand(16,255); ?>background:rgba(<?php echo "$r, $g, $b"; ?>, 0.2)" >
			<?php
			//echo '<span title="'.ucfirst( strip_tags($product->product_s_desc) ).'" class="hasTooltip"  >';
			echo '<div class="prodtitle">';
			?>
<div class="compaign-name" style="background:rgba(<?php echo $rn.','.$gn.','.$bn; ?>, 0.3)">
		<h6>
			<?php echo $product->product_name; ?>
		</h6>
				</div>
				
			
<div id="compaign<?php echo $compaignslideid ?>" class="carousel slide" data-interval="false">  
	<div class="carousel-inner">
		<?php
							if ($count_images > 10) {
							for ($i = 1; $i < 11; $i++) {
								$ii = $i - 1;
		echo '<div class="'.$ii.' item';
							if ($ii == 0) echo $active;
		echo '"><a href="'.$juri.$thumburl[$ii].'" alt="'.$product->product_name.'" data-lightbox="roadtrip['.$product->product_name.']" tite="'.$product->product_s_desc.'" data-title="'.$product->product_s_desc.'"><img src="'.$juri.$prod_images[$ii].'" class="compaign-avatar"></a></div>';
							}
							} else {
							$count_images_total = $count_images + 1;
							for ($i = 1; $i < $count_images_total; $i++) {
								$ii = $i - 1;
		echo '<div class="'.$ii.' item';
							if ($ii == 0) echo $active;
		echo '"><a href="'.$juri.$thumburl[$ii].'" alt="'.$product->product_name.'" data-lightbox="roadtrip['.$product->product_name.']" tite="'.$product->product_s_desc.'" data-title="'.$product->product_s_desc.'"><img src="'.$juri.$prod_images[$ii].'" class="compaign-avatar"></a></div>';
							}
							}
							?>
	</div>
<?php if ($count_images > 1) { ?>
      <a class="left carousel-control" href="#compaign<?php echo $compaignslideid ?>" data-slide="prev"><span class="icon-arrow-left"></span></a>
      <a class="right carousel-control" href="#compaign<?php echo $compaignslideid ?>" data-slide="next"><span class="icon-arrow-right"></span></a>
		<ol class="carousel-indicators">
							<?php
							if ($count_images > 10) {
							for ($i = 1; $i < 11; $i++) {
								$ii = $i - 1; ?>
			<li data-target="#compaign <?php echo $compaignslideid ?>" data-slide-to="<?php echo $ii ?>" <?php if ($ii == 0) echo "class=\"$active\""; ?>></li>
			<?php }
							} else {
							$count_images_total = $count_images + 1;
							for ($i = 1; $i < $count_images_total; $i++)  {
								$ii = $i - 1; ?>
			<li data-target="#compaign <?php echo $compaignslideid ?>" data-slide-to="<?php echo $ii ?>" <?php if ($ii == 0) echo "class=\"$active\""; ?>></li>
			<?php }
							} ?>
		</ol>
<?php } ?>	  
    </div>				

<? // НОМИНАЛЫ СПОНСОРА ?>
<div class="row infoofcompaign">
  <div class="col-xs-6"><?php echo JText::_( 'COM_VMVENDOR_VMVENADD_FORM_WEIGHT' ); ?></div>
  <div class="col-xs-6"><?php echo round($this->product->product_width, 0); echo $product->product_lwh_uom; echo $currency_symbol; ?></div>
</div>
<div class="row infoofcompaign">
  <div class="col-xs-6"><?php echo JText::_( 'COM_VMVENDOR_COMPAIGN_CAT' );?>
  <?php
  /*
 			echo '<div class="prodcat"   >';
			if($products_category_link)
			{
				echo '<a href="'.$category_url.'" title="'.JText::_( 'COM_VMVENDOR_PROFILE_VISITCAT' ).'" class="hasTooltip" >
			</a> '.JText::_($product->category_name).'';
			}
			else
			{
				echo ''.JText::_($product->category_name).'';
			}
			echo '</div>';
	*/
  ?>

  </div>
  <div class="col-xs-6"><?php echo $product->category_name; ?></div>
</div>


<div class="row infoofcompaign">
  <div class="col-xs-6"><?php echo JText::_( 'COM_VMVENDOR_PROFILE_INSTOCK' );?></div>
  <div class="col-xs-6"><?php echo $product->product_in_stock - $product->product_ordered; ?></div>
</div>

<div class="row infoofcompaign">
<div class="col-xs-6"><?php echo JText::_ ( 'COM_VMVENDOR_VMVENADD_FORM_PRICE' );?></div>	  
<div class="col-xs-6"><?php //echo $formattedRounded_price;?></div>
<div class="col-xs-6"><?php //echo round($product->product_price,0); ?>
<div class="prodprice"><?php
				$product_with_tax = VmvendorModelVendorprofile:: applytaxes($product->product_price , $product->virtuemart_category_id , $product->virtuemart_manufacturer_id , $vendor_id);
				$res = number_format((float)$product_with_tax,$currency_decimal_place,$currency_decimal_symbol,$currency_thousands);
				$search = array('{sign}', '{number}', '{symbol}');
				$replace = array('+', $res, $currency_symbol);
				$formattedRounded_price = str_replace ($search,$replace,$currency_positive_style);
				echo $formattedRounded_price;
				//echo  '<a href="'.$product_url.'" >'.$formattedRounded_price.'</a>';
				echo '</div></div>';
				?></div>
<!-- НОМИНАЛЫ СПОНСОРА -->
<?php // ТАРГЕТ БАР
	$product_sales = $product->product_sales;
	$product_in_stock = $product->product_in_stock;
	$tofinish = 100 * $product_sales / $product_in_stock;
?>
<div class="row infoofcompaign">
  <div class="col-xs-4"><?php echo $product_sales; ?></div>
  <div class="col-xs-6"><?php echo JText::_ ( 'COM_VMVENDOR_DASHBOARD_STATS_REVENUE' );?>:</div>
  <div class="col-xs-2"><b><?php echo round($tofinish,0); ?></b>%</div>
</div>
<div class="progress compaign">
  <div class="progress-bar pbcompaign" style="width: <?php echo $tofinish; ?>%;"></div>
</div>

<?php // КРАТКОЕ ОПИСАНИЕ		
	if($product->product_s_desc)
	{
?>		
	<div class="row-fluid infoofcompaign separatorline">
		<div class="col-xs-12 separatorline s-desc"><?php echo $product->product_s_desc ?>
		</div>
	</div>
<?php
	}
?>
					<div class="row-fluid infoofcompaign">
						<div class="col-xs-5">
							<?php //echo JText::_( 'COM_VMVENDOR_COMPAIGN_STARTTDATE' ) ?>
						</div>
							<div class="col-xs-7"><?php //echo $product->created_on ?></div>
						</div>
					<div class="row infoofcompaign">
						<div class="col-xs-6"><?php //echo JText::_( 'COM_VMVENDOR_VMVENADD_FORM_LOCATION' ) ?></div>
						<div class="col-xs-6"><?php //echo $product->mf_name ?>
						</div>
					</div>

<?php
///////////////////////////////////////////////////
			if($this->ismyprofile )
			{
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
					echo '<form name="delete_product" id="delete_product'.$product->virtuemart_product_id.'" style="margin:0" method="post" >';
					//onsubmit="sweetConfirm(this);"
				}
				echo '<div class="btn-group pull-left">';
				echo '<a href="'.JRoute::_('index.php?option=com_vmvendor&view=editproduct&productid='.$product->virtuemart_product_id.'&Itemid='.$this->addproduct_itemid).'" data-toggle="tooltip" rel="tooltip" title="'.JText::_( 'COM_VMVENDOR_PROFILE_EDITPRODUCT' ).'" class="btn btn-primary">
				<span class="glyphicon glyphicon-pencil"></span></a>';
				?>
				
				<?php
				if($allow_deletion!='0')
				{
					echo '<input type="hidden" name="option" value="com_vmvendor" />
				<input type="hidden" name="controller" value="vendorprofile" />
				<input type="hidden" name="task" value="deleteproduct" />
				<input type="hidden" name="delete_productid" value="'.$product->virtuemart_product_id.'" />
					<input type="hidden" name="userid" value="'.$userid.'" />
					<input type="hidden" name="price" value="'.$product->product_price.'" />';
					echo '<a data-toggle="tooltip" rel="tooltip" title="'.JText::_( 'COM_VMVENDOR_PROFILE_DELPRODUCT' ).'"  class="btn btn-primary" onclick="sweetConfirm( document.getElementById(\'delete_product'.$product->virtuemart_product_id.'\') );" ><span class="glyphicon glyphicon-remove"></span></a>';
					echo '</form>';
				}
			}

				echo '</div>';
				echo '<a href="'.$product_url.'" data-toggle="tooltip" rel="tooltip" title="'.$product->product_name.'" class="btn btn-default pull-right"><span class="glyphicon glyphicon-info-sign visible-xs"></span><span class="hidden-xs">'.vmText::_ ( 'COM_VMVENDOR_PRODUCT_DETAILS' ).'</span></a>';	

				echo '</div>';	
				echo '</div>';
///////////////////////////////////////////////////	
$compaignslideid++;
		}
		echo '</div>';
		echo '<div style="clear:both" ></div>';
		echo '<div class="pagination center" >';
		echo '<div>'.$this->pagination->getResultsCounter().'</div>';
		echo '<div>'.$this->pagination->getPagesLinks('class="pagination"').'</div>';
		echo '<div>'.$this->pagination->getPagesCounter().'</div>';
		echo '</div>';
		echo '<div style="clear:both"></div>';
?>
<script src="http://smartresponsor.com/js/jquery.infinitescroll.min.js"></script>
<script src="http://smartresponsor.com/js/masonry.pkgd.js"></script>
<script src="http://desandro.github.io/imagesloaded/imagesloaded.pkgd.min.js"></script>


<script type="text/javascript">
jQuery(window).load(function() {
	var $container = jQuery('.view');
		$container.imagesLoaded(function(){
		$container.masonry({
		itemSelector: '.browse',
		});
		});
		$container.infinitescroll({
			navSelector  : '.pagination',
			nextSelector : '.pagination-next a',
			itemSelector : '.browse',
			loading: {
			finishedMsg: 'No more pages to load.',
			img: 'http://smartresponsor.com/images/6RMhx.gif'
			}
		},
  // trigger Masonry as a callback
function( newElements ) {
    var $newElems = jQuery( newElements );
		$newElems.imagesLoaded(function(){
		$container.masonry( 'appended', $newElems, true )
		});
}
);

});
</script>
<script type="text/javascript">
jQuery('#element').tooltip('show')
</script>