<?php
defined('_JEXEC') or die('Restricted access');
$currency							= $viewData['currency'];
$showRating							= $viewData['showRating'];
$compaignslideid					= 1;
?>

<?php
/*
<script language="javascript">
	// mainwcell = Math.round(jQuery('#b3').width() / 200);
</script>
*/
?>

<?php
foreach ( $this->products as $type	=> $productList) {
//$productTitle						= JText::_('COM_VIRTUEMART_'.$type.'_PRODUCT');	
?>

<?php //echo '<h2>'.$productTitle.'</h2>'; ?>
<div class="row view js-masonry hidden-xs">
		<?php // Start the Output
foreach ( $productList as $product ) {
		?>
		

<div class="col-xs-4 col-sm-3 col-md-4 col-lg-4 vmvthumb masonry-brick browse browsecellwidth" style="<?php echo //$cellwidth; ?><?php $r = rand(16,255); $g = rand(16,255); $b = rand(16,255); ?>background:rgba(<?php echo "$rn, $gn, $bn"; ?>, 0.3);">
<?php $rn = rand(16,255); $gn = rand(16,255); $bn = rand(16,255); ?>
	<div id="compaign<?php echo $compaignslideid ?>" class="carousel slide" data-interval="false">

      <div class="carousel-inner">
						<?php
							if ($product->product_special) {
								echo '<div class="featur"><span class="">'.JText::_( 'COM_VIRTUEMART_PUBLICATION_TYPE' ).'</span></div>';
							}

							$count_images 	= count ($product->images);
							if ($count_images > 10) {
							for ($i = 0; $i < 10; $i++) {
						   ?>
        <div class="item<?php if ($i == 0) echo ' active'; ?>">
			<a href="/<?php echo $product->images[$i]->file_url ?>" alt="<?php echo $product->product_name ?>"data-lightbox="roadtrip[<?php echo $compaignslideid ?>]" rel="group1" tite="<?php echo $product->product_s_desc ?>" data-title="<?php echo $product->product_s_desc ?>" class="fancybox">
				<img src="/<?php echo $product->images[$i]->file_url ?>" onerror="this.onerror=null;this.src='/images/noimages.jpg';" class="compaign-avatar">
			</a>
        </div>
								<?php
							}
							} elseif ($count_images >= 1)  {
							for ($i = 0; $i < $count_images; $i++) {
								?>
        <div class="item<?php if ($i == 0) echo ' active'; ?>">
				<a href="/<?php echo $product->images[$i]->file_url ?>" alt="<?php echo $product->product_name ?>"data-lightbox="roadtrip[<?php echo $compaignslideid ?>]" rel="group2" tite="<?php echo $product->product_s_desc ?>" data-title="<?php echo $product->product_s_desc ?>" class="fancybox">
					<img src="/<?php echo $product->images[$i]->file_url ?>" onerror="this.onerror=null;this.src='/images/noimages.jpg';" class="compaign-avatar">
				</a>
        </div>
							<?php
							}
							}
							?>
      </div>
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
						<script type="text/javascript">
							jQuery(document).ready(function() {
								jQuery('a.fancybox').fancybox({
									'showCloseButton' : true,
									'showNavArrows'	:	true,
								});
							});
						</script>
<div class="tablo">
<?php // ДОБАВИТЬ В ПОРУЧЕНИЕ Display the quantity box
			$stockhandle = VmConfig::get ('stockhandle', 'none');
							if (($stockhandle == 'disableit' or $stockhandle == 'disableadd') and ($product->product_in_stock - $product->product_ordered) < 1) {
						?>
							<a href="<?php echo JRoute::_ ('index.php?option=com_ajax&option=com_virtuemart&view=productdetails&layout=notify&virtuemart_product_id=' . $product->virtuemart_product_id); ?>" class="send_me_n">
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
						?>
	</div>
<?php // ДОБАВИТЬ В ПОРУЧЕНИЕ КОНЕЦ?>	
<?php // Краткое описание
	if (!empty($product->product_s_desc)) { ?>
<div class="row-fluid infoofcompaign separatorline hidden-xs">
	<div class="col-xs-12 separatorline s-desc">
<?php echo shopFunctionsF::limitStringByWord ($product->product_s_desc, 60, ' ...'); ?>
	</div>
</div>
<?php }// Краткое описание. Конец ?>
<?php //Бюджет ?>						
<div class="row infoofcompaign">
  <div class="col-xs-6 hidden-xs"><?php echo JText::_( 'COM_VIRTUEMART_PRODUCT_WEIGHT' ); ?></div>
  <div class="col-xs-6"><?php echo round($product->product_weight, 0); echo $product->product_weight_uom; ?></div>
</div>
<div class="row infoofcompaign">
  <div class="col-xs-6 hidden-xs"><?php echo JText::_( 'COM_VIRTUEMART_PRODUCT_DETAILS_MANUFACTURER_LBL' ); ?></div>
  <div class="col-xs-6"><?php echo $product->mf_name; ?></div>
</div>
<div class="row infoofcompaign">
  <div class="col-xs-6 hidden-xs"><?php echo JText::_( 'COM_VIRTUEMART_CATEGORY' );?></div>
  <div class="col-xs-6"><?php //echo '<a style="" href="'.$product->manufacturer_link.'">.'$product->mf_name.'</a>'; ?></div>
</div>
<?php if($enablestock){ ?>
<div class="row infoofcompaign">
  <div class="col-xs-6 hidden-xs"><?php echo JText::_( 'COM_VIRTUEMART_PRODUCT_IN_STOCK' );?></div>
  <div class="col-xs-6"><?php echo $product->product_in_stock - $product->product_ordered; ?></div>
</div>
<?php } ?>
<div class="row infoofcompaign">
  <div class="col-xs-6 hidden-xs"><?php //echo JText::_ ( 'COM_VIRTUEMART_PRODUCT_FORM_CREATION_DATE' );?></div>
  <div class="col-xs-6"><?php echo $product->cdate; ?></div>
</div>
<?php if($enableprice){ ?>
<div class="row infoofcompaign">
<div class="col-xs-6"><?php $product_with_tax			= applytaxes($product->product_price , $product->virtuemart_category_id , $ismyprofile , $vendor_id , $userid);
					  $res						= number_format((float)$product_with_tax,$currency_decimal_place,$currency_decimal_symbol,$currency_thousands);
					  $search					= array('{sign}', '{number}', '{symbol}');
					  $replace					= array('+', $res, $currency_symbol);
					  $formattedRounded_price	= str_replace ($search,$replace,$currency_positive_style);
					  echo JText::_ ( 'COM_VIRTUEMART_PRODUCT_BASEPRICE' );?></div>
<div class="col-xs-6"><?php echo  '<a href="'.$product_url.'" >'.$formattedRounded_price.'</a>';?></div>
<div class="col-xs-6"><?php echo round($product->product_price,0); ?><?php $product->currency; ?></div>
</div>
<?php } ?>


<div class="row infoofcompaign">
  <div class="col-xs-6"><?php echo JText::_ ( 'COM_VIRTUEMART_PRODUCT_UNITS_IN_BOX' );?></div>
  <div class="col-xs-6"><?php echo $product->product_in_stock; ?></div>
</div>
<div class="row infoofcompaign">
  <div class="col-xs-6"><?php //echo JText::_ ( 'COM_VIRTUEMART_PRODUCT_FORM_CREATION_DATE' );?></div>
  <div class="col-xs-6"><?php //echo ($product->cdate); ?></div>
</div>
<div class="row infoofcompaign">
  <div class="col-xs-6"><?php //echo JText::_ ( 'COM_VIRTUEMART_VENDOR' );?></div>
  <div class="col-xs-6" style="overflow: hidden;"><a style="white-space: -moz-pre-space;" href="<?php //echo ($product->vendor_link); ?>"><?php //echo ($product->vendor_name); ?></a></div>
</div>

<?php // НОМИНАЛЫ СПОНСОРА. КОНЕЦ. ТАРГЕТ БАР
	$product_sales = $product->product_sales;
	$product_prices = round($product->product_price,0);
	if ($product->product_in_stock <= 0 ) {
		$product_in_stock = 1;
	} else {
	$product_in_stock = $product->product_in_stock;}
	$tofinish = 100 * $product_sales / round($product->product_weight, 0);

?>
<div class="row infoofcompaign">
  <div class="col-xs-4"><?php echo $product_sales; ?><?php echo JText::_ ( 'COM_VIRTUEMART_PRODUCT_UNITS_IN_BOX' );?></div>
  <div class="col-xs-5"><?php echo JText::_ ( 'COM_VIRTUEMART_PRODUCT_SALES' );?>:</div>
  <div class="col-xs-3"><b class="pull_right"><?php echo round($tofinish,0); ?>%</b></div>
</div>
<div class="progress compaign">
  <div class="progress-bar pbcompaign" style="width: <?php echo $tofinish; ?>%;"></div>
</div>


<?php
			//	echo shopFunctionsF::renderVmSubLayout('prices',array('product'=>$product,'currency'=>$currency)); ?>

		
<?php // Запас акций. ?>
						<?php
							//if ( VmConfig::get ('display_stock', 1)) {
							//echo '<span class="pull-right stock-level">'.$product->stock->stock_tip.'</span>';
						?>
						<?php
							/*
							<span class="pull-right vmicon vm2-<?php echo $product->stock->stock_level ?>" title="<?php echo $product->stock->stock_tip ?>"></span>
							*/
						?>	
						<?php //}
							//echo shopFunctionsF::renderVmSubLayout('stockhandle',array('product'=>$product));
						?>
<?php // Запас акций. Конец ?>
<?php // Добавить в Избранное. Задать вопрос. Информация ?>
		<a href="<?php echo $product->link.$ItemidStr ?>" title="<?php echo $product->product_name; ?>" class="btn btn-default pull-left" disabled="disabled"><span class="glyphicon glyphicon-pushpin"></span><span class="hidden-xs"> <?php //echo vmText::_ ( 'COM_VIRTUEMART_PRODUCT_DETAILS' ); ?></span></a>
		
		<?php
		// Ask a question about this Publication
			$askquestion_url = JRoute::_('index.php?option=com_ajax&option=com_virtuemart&view=productdetails&task=askquestion&virtuemart_product_id=' . $this->product->virtuemart_product_id . '&virtuemart_category_id=' . $this->product->virtuemart_category_id . '&tmpl=component', FALSE);
			?>
				<a class="btn btn-primary fancybox" href="<?php echo $askquestion_url ?>" rel="nofollow" title="<?php echo vmText::_('COM_VIRTUEMART_PRODUCT_ENQUIRY_LBL') ?>"><span class="glyphicon glyphicon-envelope"></span></a>
		
		<a href="<?php echo $product->link.$ItemidStr ?>" title="<?php echo $product->product_name; ?>" class="btn btn-default pull-right"><span class="glyphicon glyphicon-info-sign visible-xs"></span><span class="hidden-xs"> <?php echo vmText::_ ( 'COM_VIRTUEMART_PRODUCT_DETAILS' ); ?></span></a>
		<a href="<?php echo JRoute::_($product->link.$ItemidStr); ?>" title="<?php echo $product->product_name; ?>" class="btn btn-default pull-right"><span class="glyphicon glyphicon-info-sign visible-xs"></span><span class="hidden-xs"> <?php echo vmText::_ ( 'COM_VIRTUEMART_PRODUCT_DETAILS' ); ?></span></a>
<?php // Добавить в Избранное. Задать вопрос. Информация.Конец ?>

</div>
<?php // КАРТОЧКА В КАТЕГОРИИ Конец
$compaignslideid++;
}
?>
</div>

<!-- <div class="clear"></div> -->
<?php } ?>




























<?php
/*
<script src="http://smartresponsor.com/js/jquery.infinitescroll.min.js"></script>
<script src="http://smartresponsor.com/js/masonry.pkgd.js"></script>
<script src="http://desandro.github.io/imagesloaded/imagesloaded.pkgd.min.js"></script>
<script type="text/javascript">
(function(jQuery) {
    jQuery('.view').imagesLoaded(function() {
        jQuery(this).masonry({
            itemSelector : '.browse',
			
        });
    });
});

(function (jQuery) {
	jQuery( '.view' ).masonry( {
		itemSelector: '.browse'
	});
});
 
(function(jQuery) {
	var $container = jQuery('.view');
		$container.imagesLoaded( function() {
		$container.masonry();
	});
});
</script>

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
*/
?>