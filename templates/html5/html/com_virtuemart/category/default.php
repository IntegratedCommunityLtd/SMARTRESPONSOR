<?php defined ('_JEXEC') or die('Restricted access'); ?>
<style>
.z1,.z2,.z11-12 {*display:none!important;}
.z3-10 {*width:100%!important;}
.z3-10, .z11-12 {padding:0!important;}
.js-recalculate, .quantity-plus, .quantity-minus, .formfavorit {display:none!important;}
</style>

<?php
		$compaignslideid = 1;
		$BrowseTotalProducts = count($this->products);	
		$rn = rand(16,255);
		$gn = rand(16,255);
		$bn = rand(16,255);			
?>
<div class="row view js-masonry">

	<?php // Start the Output

		if (!empty($this->category->children)) {
			foreach ($this->category->children as $category) {
				// Category Link
				$caturl = JRoute::_ ('index.php?option=com_ajax&option=com_virtuemart&view=category&virtuemart_category_id=' . $category->virtuemart_category_id, FALSE);
				// Show Category
/*				
				?>
	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 masonry-brick browse">
				<div class="spacer" style="text-align: center; padding: 0px;">
						<h2 class="cat-title">
							<a href="<?php echo $caturl ?>" title="<?php echo $category->category_name ?>" class="subcat">
							<?php 
							echo $category->images[0]->displayMediaThumb('style="width: 25px;"', false);
							echo ' ';
							echo $category->category_name;?></a>
						</h2>
					<div class="" style="text-align: left;width:100%;padding: 5px;max-width: 200px;">
						<a href="'.$caturl.'" title="'.$category->category_name.'" class="subcat"><?php echo $category->images[0]->displayMediaFull('style="width: 100%;"', false);?></a>
					</div>
				</div>
	</div>					
				<?php
*/
				$iCategory++;
			}
		}


if (!empty($this->products)) {

	foreach ($this->products as $product) {

$vendorModel 	= VmModel::getModel ('vendor');
$vendor 		= $vendorModel->getVendor ($product->virtuemart_vendor_id);
$vendorModel->addImages ($vendor);
	
?>

		<div class="masonry-brick browse browsecellwidth" style="<?php $r = rand(16,255); $g = rand(16,255); $b = rand(16,255); ?>background:rgba(<?php echo "$rn, $gn, $bn"; ?>, 0.3);">
			<div id="compaign" class="carousel slide">
							<?php
							if ($vendor->file_url) {
							echo '<div class="hidden-xs category-vendor-avatar" style="background-image: url(/' . $vendor->file_url .');height: 55px; width: 55px;background-repeat: no-repeat;background-position: left, right;background-size: cover;" alt="' . $vendor->vendor_name . ';"></div>';
							}
							?>
				<div class="carousel-inner"> 
					<span class="glyphicon glyphicon-ok check"></span>
			<?php
				if ($product->product_special) {
					echo '<div class="featur"><span class="">'.JText::_( 'COM_VIRTUEMART_PUBLICATION_TYPE' ).'</span></div>';
				}
				?>
				<div class="compaign-avatar" style="background-image: url(/<?php echo $product->images[0]->file_url ?>)"></div>		
				</div>
			</div>
			<?php echo shopFunctionsF::renderVmSubLayout('addtocart',array('product'=>$product,'rowHeights'=>$rowsHeight[$row])); ?>
<?php 
echo $this->loadTemplate('addtocart'); 
?> 
			<div class="row infoofcompaign nomarginleft">
				<div class="col-xs-12">
					<h6><strong><?php echo $product->product_name; ?></strong></h6>
				</div>
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
			<div class="col-xs-6">
				<?php echo JText::_ ( 'COM_VIRTUEMART_PRODUCT_SALES' );?>
			</div>
			<div class="col-xs-6">
				<b><?php echo round($tofinish,0); ?></b>%
			</div>
		</div>
		<div class="progress compaign">
			<div class="progress-bar pbcompaign" style="width: <?php echo $tofinish; ?>%;">
			</div>
		</div>		
		<?php // Краткое описание
			if (!empty($product->product_s_desc)) { ?>
		<div class="row-fluid infoofcompaign separatorline hidden-xs">
			<div class="col-xs-12 separatorline s-desc hidden-xs">
				<?php echo shopFunctionsF::limitStringByWord ($product->product_s_desc, 240, ' ...'); ?>
			</div>
		</div>
		<?php }// Краткое описание. Конец ?>
		<?php //Бюджет ?>
						
		<div class="row infoofcompaign nomarginleft">	
					<?php foreach ($product->customfields as $field)
					{
					if ($field->layout_pos == 'goal' )
					{
					?>
			<div class="col-xs-3">
				<?php echo JText::_($field->custom_title) ?>
			</div>
			<div class="col-xs-3">
				<?php echo JText::_($field->customfield_value) ?>
			</div>
					<?php
					}
					if ($field->layout_pos == 'currency' ) {
					?>
			<div class="col-xs-3  pull-right">
				<?php echo JText::_($field->customfield_value) ?>
			</div>
					<?php
					}			
					}
					?>
		</div>
		<div class="row infoofcompaign nomarginleft" >
		<div class="col-xs-12"></div>
		<?php //echo shopFunctionsF::renderVmSubLayout('prices',array('product'=>$product,'currency'=>$currency)); ?>
		</div>

			
		<div class="row infoofcompaign nomarginleft">
			<div class="col-xs-6"><?php echo JText::_( 'COM_VIRTUEMART_PRODUCT_DETAILS_MANUFACTURER_LBL' ); ?></div>
			<div class="col-xs-6">
					<?php
					// Получение id производителя
						$id_manufacturer = (int)$product->virtuemart_manufacturer_id[0];
						echo $id_manufacturer;
					// Получаем объект коннектора базы данных
						$db = JFactory::getDBO();
					// Получаем объект запросов
						$query = $db->getQuery(true);
						$query_img = $db->getQuery(true);

					// Запрос для получения virtuemart_media_id производителя
						$query->select('virtuemart_media_id')
								->from($db->quoteName('#__virtuemart_manufacturer_medias'))
								->where($db->quoteName('virtuemart_manufacturer_id') . ' = '.$id_manufacturer);
					 
					// Запрос для получения file_url производителя
						$query_img->select('file_url')
							->from($db->quoteName('#__virtuemart_medias'))
							->where($db->quoteName('virtuemart_media_id') . ' IN (' . $query . ')');
					 
					// Получение file_url производителя
						$media_url = $db->setQuery($query_img)->loadResult();

					// Вывод изображения производителя
						echo '<img src="'.$media_url.'">';  

					?>		
						<?php //echo $product->mf_name; ?>
					<?php 
					foreach($this->product->manufacturers as $manufacturers_details) {
					 
						//Link to products
						$link = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_manufacturer_id=' . $manufacturers_details->virtuemart_manufacturer_id);
						$name = $manufacturers_details->mf_name;
						
						$media_url = $this->product->manufacturers[0]->file_url;
						
						// Avoid JavaScript on PDF Output
						if (!$this->writeJs) {
							$mans[] = JHtml::_('link', $link, $name);
						} else {
							$mans[] = '<a href="'.$link .'"><img src="'.$media_url.'"></a>';
						}
					}
					?>	
			</div>
		</div>

		<div class="row infoofcompaign nomarginleft">
			<div class="col-xs-6"><?php echo JText::_ ( 'COM_VIRTUEMART_PUBLISHED' );?></div>
			<div class="col-xs-6">
				<?php echo ($product->cdate); ?>
				<?php echo ($vendor->vendor_link); ?>
				<?php echo ($vendor->vendor_name); ?>
			</div>
		</div>
		<div class="clear"></div>

	
			<?php
/*
			if ($user->guest) 
			{
				// Ask a question about this Publication
				if (VmConfig::get('ask_question', 0) == 1) {
					$askquestion_url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&task=askquestion&virtuemart_product_id=' . $this->product->virtuemart_product_id . '&virtuemart_category_id=' . $this->product->virtuemart_category_id . '&tmpl=component', FALSE);
					?>

				<a class="btn btn-primary pull-left fancybox hidden-xs" href="<?php echo $askquestion_url ?>" rel="nofollow" title="<?php echo vmText::_('COM_VIRTUEMART_PRODUCT_ENQUIRY_LBL') ?>"><span class="glyphicon glyphicon-envelope"></span></a>
				<?php
				}	
			} else {
				require(JPATH_BASE.'/templates/html5/html/com_wishlist/template/addtofavorites_form.tpl.php');
			 ?>
			<button href="javascript:" onclick="joms.api.pmSend(<?= $vendor->created_by; ?>);" class="btn btn-primary pull-left hidden-xs">
			<span class="glyphicon glyphicon-envelope"></span>
			</button>
			<?php
			}
*/			
			?>
					<a href="<?php echo JRoute::_($product->link.$ItemidStr); ?>" title="<?php echo $product->product_name; ?>" class="btn btn-default pull-right"><span class="glyphicon glyphicon-info-sign"></span><span class="hidden-md"> <?php echo vmText::_ ( 'COM_VIRTUEMART_PRODUCT_DETAILS' ); ?></span></a>
		</div>

		
		
		
		
<?php // КАРТОЧКА В КАТЕГОРИИ Конец
		$compaignslideid++;	
	} 
	} 
?>
</div>



