<?php // no direct access 
defined('_JEXEC') or die('Restricted access');

		//from VM
		$compaignslideid 			= 1;
		$cellwidth					= 'width: '.floor (99 / 4 ).'%;max-width:200px!important;min-width:200px!important;';

//Loading Main Component Stylesheet
JHTML::stylesheet("template.css", "components/com_wishlist/");

$my_page =& JFactory::getDocument();
$conf =& JFactory::getConfig();
$user =& JFactory::getUser();
$sitename = $conf->get('sitename');
$my_page->setTitle($sitename. ' - ' .JText::_( 'VM_FAVORITE_LIST' )); 
?>
<?php
$itemid = JRequest::getInt('Itemid',  1);
$prod_name = JRequest::getString('prod_name',  "");
$mode = JRequest::getString('mode',  "");
if ($prod_name != "" && $mode == "delete") { 
		JError::raiseNotice( 100, JText::_('VM_DELETED_TITLE').'<strong> '.$prod_name.' </strong>'.JText::_('VM_DELETED_TITLE2'));
}
if (empty( $this->data )){ ?>
	<div class='alert alert-warning fade in'><?php echo JText::_('VM_FAVORITE_EMPTY') ?></div>
	<?php	}
else { 
	//Loading Global Options
	$params = &JComponentHelper::getParams( 'com_wishlist' );
	$tmpl_favdate_enabled = $params->get( 'tmpl_favdate_enabled' );
	$tmpl_favimage_width = $params->get( 'tmpl_favimage_width' );
	//Initialize the Virtuemart Product Model Class
	$productModel = new VirtueMartModelProduct();
	$afaq_message = JText::_('VM_AFAQ_MESSAGE');
	foreach($this->data as $dataItem)	
	{
		//from VM
		$vendorModel 	= VmModel::getModel ('vendor');
		$vendor 		= $vendorModel->getVendor ($product->virtuemart_vendor_id);
		$vendorModel->addImages ($vendor);
		$product = $productModel->getProduct($dataItem->product_id);
		$productModel->addImages($product);
		// from VM End
		$product_qty = $dataItem->product_qty;
		$product_ord = $product_qty > 0 ? $product_qty : 1;
		$url_favlist = JRoute::_("index.php?option=com_wishlist&view=favoriteslist&Itemid={$itemid}");
		$afaq_message .= $product_ord."x <b>".$product->product_name."</b>";
		//generate button to remove from favorites list
		$form_deletefavorite = "<form action='". $url_favlist ."' method='POST' role='form' name='deletefavo' id='". uniqid('deletefavo_') ."' class='formfavorit'>
		<button type='submit' class='modns button art-button art-button btn btn-primary pull-left hasTooltip' value='".JText::_('VM_REMOVE_FAVORITE')."' data-original-title='".JText::_('VM_REMOVE_FAVORITE')."' data-toggle='popover' onclick=\"return confirm('".JText::_('VM_REMOVEFAV_CONFIRM')."')\"><span class='glyphicon glyphicon-bookmark'></span></button>
		<input type='hidden' name='mode' value='delete' />
		<input type='hidden' name='fav_id' value='". $dataItem->fav_id ."' />
		<input type='hidden' name='prod_name' value='". $product->product_name ."' />
		</form>";		
		?>
		
<div class='vmvthumb masonry-brick browse browsecellwidth' style="<?php echo $cellwidth; ?><?php $r = rand(16,255); $g = rand(16,255); $b = rand(16,255); ?>background:rgba(<?php echo "$rn, $gn, $bn"; ?>, 0.3);">
	<?php $rn = rand(16,255); $gn = rand(16,255); $bn = rand(16,255); ?>
	<div id="compaign<?php echo $compaignslideid; ?>" class="carousel slide" data-interval="false">

		<?php
		//Display Linked Product Name
		$url_vm = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$product->virtuemart_product_id.'&virtuemart_category_id='.
		$product->virtuemart_category_id);
		
		if ($product->product_special) {
			echo '<div class="featured"><span class="">'.JText::_( 'COM_VIRTUEMART_PUBLICATION_TYPE' ).'</span></div>';
		}			
		?>
	<div class="carousel-inner">
		<img src="<?php echo $vendor->file_url; ?>" alt="<?php echo $vendor->vendor_name; ?>" class="category-vendor-avatar" style="border: 4px inset rgba(<?php echo "$rn, $gn, $bn"; ?>, 0.2);">			
							<?php
							$count_images 	= count ($product->images);
							if ($count_images > 10) {
							for ($i = 0; $i < 10; $i++) {
						   ?>
        <div class="item<?php if ($i == 0) echo ' active'; ?>">
			<a href="/<?php echo $product->images[$i]->file_url ?>" alt="<?php echo $product->product_name ?>" data-lightbox="roadtrip[<?php echo $compaignslideid ?>]" rel="group1" tite="<?php echo $product->product_s_desc ?>" data-title="<?php echo $product->product_s_desc ?>" class="fancybox<?php echo $compaignslideid ?>">
				<img src="/<?php echo $product->images[$i]->file_url ?>" class="compaign-avatar">
			</a>
        </div>
								<?php
							}
							} elseif ($count_images >= 1)  {
							for ($i = 0; $i < $count_images; $i++) {
								?>
        <div class="item<?php if ($i == 0) echo ' active'; ?>">
				<a href="/<?php echo $product->images[$i]->file_url ?>" alt="<?php echo $product->product_name ?>" data-lightbox="roadtrip[<?php echo $compaignslideid ?>]" rel="group2" tite="<?php echo $product->product_s_desc ?>" data-title="<?php echo $product->product_s_desc ?>" class="fancybox<?php echo $compaignslideid ?>">
					<img src="/<?php echo $product->images[$i]->file_url ?>" class="compaign-avatar">
				</a>
        </div>
							<?php
							}
							}
							?>
	</div>
				<script type="text/javascript">
					jQuery(document).ready(function() {
						jQuery("a.fancybox<?php echo $compaignslideid ?>").fancybox({
								'showCloseButton' : true,
								'showNavArrows'	:	true,						
						});
					});
				</script>
		<?php
		//Display Linked Product Image
		//if (!empty($product->images[0]) ) $image = $product->images[0]->displayMediaThumb('width="'.$tmpl_favimage_width.'" border="0"',false) ;
		//else $image = '';	
		//echo '<div class="carousel-inner">';
		//echo JHTML::_('link', JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$product->virtuemart_product_id.'&virtuemart_category_id='.$product->virtuemart_category_id),$image,array('title' => $product->product_name) );		
		//echo "</div>";
		?>
<?php if ($count_images > 1) { ?>
<?php
	/*
      <a class="left carousel-control" href="#compaign<?php echo $compaignslideid ?>" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
      <a class="right carousel-control" href="#compaign<?php echo $compaignslideid ?>" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
	*/
	?>
		<ol class="carousel-indicators">
							<?php
							$count_images 	= count ($product->images);
							if ($count_images > 10) {
							for ($i = 0; $i < 10; $i++) {
						   ?> 
			<li data-target="#compaign<?php echo $compaignslideid ?>" data-slide-to="<?php echo $i ?>" <?php if ($i == 0) echo "class=\" active\""; ?>></li>
							<?php
							}
							} else {
							for ($i = 0; $i < $count_images; $i++) {
								?>
			<li data-target="#compaign<?php echo $compaignslideid ?>" data-slide-to="<?php echo $i ?>" <?php if ($i == 0) echo "class=\" active\""; ?>></li> 
							<?php
							}
							}
							?>
			</ol>
<?php } ?> 		
    </div>
		<?php 
		//Display Add To Cart Form
		//FavoritesModelFavoriteslist::addtocart($product, JText::_('COM_VIRTUEMART_CART_ADD_TO'),$product_ord);
			$stockhandle = VmConfig::get ('stockhandle', 'none');
							if (($stockhandle == 'disableit' or $stockhandle == 'disableadd') and ($product->product_in_stock - $product->product_ordered) < 1) {
						?>
							<a href="<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&layout=notify&virtuemart_product_id=' . $product->virtuemart_product_id); ?>" class="send_me_n">
								<?php echo JText::_ ('COM_VIRTUEMART_CART_NOTIFY') ?>
							</a>

						<?php
							} else {
						echo $rowsHeight[$row]['customs'] ?>
								<div class="vm3pr-<?php echo $rowsHeight[$row]['customfields'] ?>"> <?php
										echo shopFunctionsF::renderVmSubLayout('addtocart',array('product'=>$product,'rowHeights'=>$rowsHeight[$row])); ?>
								</div>
						<?php 
							}
		//Display Add To Cart Form. End
		// НАИМЕНОВАНИЕ ?>
<div class="row infoofcompaign">
	<div class="col-xs-12"><h6><strong><?php echo $product->product_name; ?></strong></h6></div>
</div>
<?php // ТАРГЕТ БАР
	$product_sales = $product->product_sales;
	$product_prices = round($product->product_price,0);
	if ($product->product_in_stock <= 0 ) {
		$product_in_stock = 1;
	} else {
	$product_in_stock = $product->product_in_stock;
	}
	$tofinish = 100 * $product_sales / ($product_sales + $product_in_stock);

?>
<div class="row infoofcompaign">
	<div class="col-xs-6"><?php echo JText::_ ( 'COM_VIRTUEMART_PRODUCT_SALES' );?>:</div>
	<div class="col-xs-6"><b><?php echo round($tofinish,0); ?></b>%</div>
</div>
<div class="progress compaign">
	<div class="progress-bar pbcompaign" style="width: <?php echo $tofinish; ?>%;"></div>
</div>
<?php // Краткое описание
	if (!empty($product->product_s_desc)) { ?>
<div class="row-fluid infoofcompaign separatorline hidden-xs">
	<div class="col-xs-12 separatorline s-desc">
<?php echo shopFunctionsF::limitStringByWord ($product->product_s_desc, 240, ' ...'); ?>
	</div>
</div>
<?php }// Краткое описание. Конец ?>
<?php //Бюджет ?>						
<div class="row infoofcompaign">
	<div class="col-xs-6"><strong><a href="#productPrice<?php echo $product->virtuemart_product_id; ?>" class="fancybox<?php echo $compaignslideid ?>"><?php echo JText::_( 'COM_VIRTUEMART_PRODUCT_WEIGHT' ); ?></a></strong></div>
	<div class="col-xs-6"><strong><?php echo round($product->product_weight, 0); echo $product->product_weight_uom; ?></strong></div>
</div>
<div class="row infoofcompaign" style="display: none;">
<?php //echo shopFunctionsF::renderVmSubLayout('prices',array('product'=>$product,'currency'=>$currency)); ?>
</div>
<div class="row infoofcompaign">
	<div class="col-xs-6"><strong><?php echo JText::_ ( 'COM_VIRTUEMART_PRODUCT_UNITS_IN_BOX' );?></strong></div>
	<div class="col-xs-6"><strong><?php echo $product->product_in_stock; ?></strong></div>
</div>
<div class="row infoofcompaign">
	<div class="col-xs-6"><strong><?php echo JText::_ ( 'COM_VIRTUEMART_PRODUCT_UNITS_IN_BOX' );?></strong></div>
	<div class="col-xs-6"><strong><?php
		$currency = CurrencyDisplay::getInstance( );
		if (!empty($product->prices['salesPrice'] ) ) echo $currency->createPriceDiv('salesPrice','',$product->prices,true);
		if (!empty($product->prices['salesPriceWithDiscount']) ) echo $currency->createPriceDiv('salesPriceWithDiscount','',$product->prices,true); ?></strong>
	</div>
</div>
<?php if($enablestock){ ?>
<div class="row infoofcompaign">
	<div class="col-xs-6"><?php echo JText::_( 'COM_VIRTUEMART_PRODUCT_IN_STOCK' );?></div>
	<div class="col-xs-6"><?php echo $product->product_in_stock - $product->product_ordered; ?></div>
</div>
<?php } ?>
<div class="row infoofcompaign">
	<div class="col-xs-6"><?php //echo JText::_( 'COM_VIRTUEMART_CATEGORY' );?></div>
	<div class="col-xs-6"></div>
</div>
<div class="row infoofcompaign">
	<div class="col-xs-6"><?php echo JText::_( 'COM_VIRTUEMART_PRODUCT_DETAILS_MANUFACTURER_LBL' ); ?></div>
	<div class="col-xs-6"><?php echo $product->mf_name; ?></div>
</div>
<div class="row infoofcompaign">
	<div class="col-xs-6"><?php echo JText::_ ( 'COM_VIRTUEMART_PRODUCT_FORM_CREATION_DATE' );?></div>
	<div class="col-xs-6"><?php echo $vendor->created_on; ?></div>
</div>
<div class="clear"></div>
<?php // Добавить в Избранное. Задать вопрос. Информация ?>
		<?php echo $form_deletefavorite; ?>
		
		<button href="javascript:" onclick="joms.api.pmSend(<?php echo $vendor->created_by; ?>);" class="btn btn-primary"><span class="glyphicon glyphicon-envelope"></span></button>
		<?php
		// Ask a question about this Publication
		if (VmConfig::get('ask_question', 0) == 1) {
			$askquestion_url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&task=askquestion&virtuemart_product_id=' . $this->product->virtuemart_product_id . '&virtuemart_category_id=' . $this->product->virtuemart_category_id . '&tmpl=component', FALSE);
			?>
				<a class="btn btn-primary fancybox" href="<?php echo $askquestion_url ?>" rel="nofollow" title="<?php echo vmText::_('COM_VIRTUEMART_PRODUCT_ENQUIRY_LBL') ?>"><span class="glyphicon glyphicon-envelope"></span></a>

		<?php
		}
		?>
	
<?php // Запас акций. ?>
						<?php
						/*
						if ( VmConfig::get ('display_stock', 1)) { ?>
							<span class="vmicon vm2-<?php echo $product->stock->stock_level ?>" title="<?php echo $product->stock->stock_tip ?>"></span>
						<?php }
							echo shopFunctionsF::renderVmSubLayout('stockhandle',array('product'=>$product));
						*/
						?>
<?php // Запас акций. Конец ?>		
			
		
		<a href="<?php echo JRoute::_($product->link.$ItemidStr); ?>" title="<?php echo $product->product_name; ?>" class="btn btn-default pull-right"><span class="glyphicon glyphicon-info-sign visible-xs"></span><span class="hidden-xs"> <?php echo vmText::_ ( 'COM_VIRTUEMART_PRODUCT_DETAILS' ); ?></span></a>
<?php // Добавить в Избранное. Задать вопрос. Информация.Конец ?>



		
		<?php
			
		//Display Favorite Date
		if ($tmpl_favdate_enabled)	
		echo "<div class='col_date'>".JHtml::date($dataItem->fav_date, JText::_('DATE_FORMAT_LC4'))."</div>";
		
	
		//Display Delete Favorite Form
		echo "</div>";

	}
	$compaignslideid ++;
	?>
	<div class="clear">
	<div class="jcb_pagination"><?php echo $this->pagination->getPagesLinks(); ?><?php echo $this->pagination->getPagesCounter(); ?></div>
	<?php
	//Ask for a Quote Form
	/*
	if (!$user->guest)
	{
		echo '<div class="clear"></div><div class="pull-left"><form action="'.$url_favlist.'" method="POST" role="form" name="sendmail" id="sendmail" class="formfavorit">
		<input type="hidden" name="mode" value="sendmail" />
		<input type="hidden" id="email_to" name="email_to" value="'.$conf->get('mailfrom').'" />
		<input type="hidden" id="email_subj" name="email_subj" value="'.JText::_('VM_AFAQ_SUBJECT').'" />
		<input type="hidden" id="email_body" name="email_body" value="'.$afaq_message.'" />
		<input type="submit" class="modns button art-button art-button btn btn-primary" value="'.JText::_("VM_AFAQ_BUTTON").'" title="'.JText::_("VM_AFAQ_BUTTON").'" />
		</form></div>';
	}
	*/
}
vmJsApi::jQuery();
vmJsApi::jPrice();
vmJsApi::cssSite();
?>
