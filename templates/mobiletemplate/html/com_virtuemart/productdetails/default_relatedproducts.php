<?php
	defined ( '_JEXEC' ) or die ( 'Restricted access' );
	$countlimit = 2;
	$imgResizeConfig = array(
		'background' => '#ffffff',
		'thumbnail_mode' => 'stretch'
	);		
	$model = new VirtueMartModelProduct();
	$calculator = calculationHelper::getInstance();
	$currency = CurrencyDisplay::getInstance();	
	
	YTTemplateUtils::getImageResizerHelper($imgResizeConfig);
	$app = & JFactory::getApplication();
	$templateDir = JURI::base() . 'templates/' . $app->getTemplate();

?>

<div class="product-related-products">
	<h3 class="item-title"><?php echo JText::_('COM_VIRTUEMART_RELATED_PRODUCTS'); ?></h3>

	<div id="yt_relate" class="pro_relate">
		<div class="caroufredsel">
		
			<ul id="yt_caroufredsel">
					<?php
						foreach ($this->product->customfieldsRelatedProducts as $field) {
							if(!empty($field->display)) {
					?>
						<li class="item">
							<div class="spacer">
								<div class="product-header">
									<h3 class="title pull-left">
										<?php echo $field->display ?>
										
									</h3>
									<div class="price pull-left">
										<div class="PricesalesPrice">
											<span class="PricesalesPrice">
											<?php
												echo jText::_($field->custom_field_desc);
												$product = $model->getProductSingle($field->custom_value,false);
												$price = $calculator -> getProductPrices($product);
												echo $currency->priceDisplay($price['salesPrice']);
											?> 
											</span>
										</div>
									</div>
								</div>
								
								<div class="product-content">
									<div class="product-content-inner">
										
										<div class="product-back">
											<div class="product_s_desc"><?php echo $this->product->product_s_desc ?></div>                                                                     
											
										</div>			
										<div class="product-front">
											 <div class="product-img">
												<!--// Product Image-->
												<?php preg_match('/<img.+src=[\'"](?P<src>.+)[\'"].*>/i', $field->display , $image);  ?>
													<img alt ="" src="<?php echo $image['src'];?>"/>
											</div>
										</div>
										
										
									</div>
								</div>

							

							<?php // This is the beginning of "Add to cart" ?>

								<div class="addtocart-area">
									<form method="post" class="product" action="index.php" id="addtocartproduct<?php echo $product->virtuemart_product_id ?>">
											<div class="addtocart-bar">

												<?php // Display the quantity box

												$stockhandle = VmConfig::get ('stockhandle', 'none');
												if (($stockhandle == 'disableit' or $stockhandle == 'disableadd') and ($this->product->product_in_stock - $this->product->product_ordered) < 1) {
													?>
													<a href="<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&layout=notify&virtuemart_product_id=' . $this->product->virtuemart_product_id); ?>" class="notify"><?php echo JText::_ ('COM_VIRTUEMART_CART_NOTIFY') ?></a>

													<?php } else { ?>
														<input type="text" class="quantity-input js-recalculate" name="quantity[]" onblur="check(this);" value="<?php if (isset($this->product->step_order_level) && (int)$this->product->step_order_level > 0) {
															echo $this->product->step_order_level;
														} else if(!empty($this->product->min_order_level)){
															echo $this->product->min_order_level;
														}else {
															echo '1';
														} ?>"/>
													
													
													<?php
													// Display the add to cart button
													?>
													<span class="addtocart-button">
														<?php echo shopFunctionsF::getAddToCartButton ($this->product->orderable); ?>
													</span>
													<?php } ?>

												<div class="clear"></div>
											</div>

											<?php // Display the add to cart button END ?>
											<input type="hidden" class="pname" value="<?php echo $product->product_name ?>">
											<input type="hidden" name="option" value="com_virtuemart" />
											<input type="hidden" name="view" value="cart" />
											<noscript><input type="hidden" name="task" value="add" /></noscript>
											<input type="hidden" name="virtuemart_product_id[]" value="<?php echo $product->virtuemart_product_id ?>" />
											<?php /** @todo Handle the manufacturer view */ ?>
											<input type="hidden" name="virtuemart_manufacturer_id" value="<?php echo $product->virtuemart_manufacturer_id ?>" />
											<input type="hidden" name="virtuemart_category_id[]" value="<?php echo $product->virtuemart_category_id ?>" />
									
									</form>
								</div>
							</div>
						</li>		
					<?php 
							}
						} 
					?>		
			</ul>
		</div>
	</div>
</div>

		
<?php
$document = JFactory::getDocument();
//$app = JFactory::getApplication();
//$templateDir = JURI::base() . 'templates/' . $app->getTemplate();
?>
<script type="text/javascript" src="<?php echo $templateDir.'/js/caroufredsel/jquery.carouFredSel-6.2.0-packed.js' ?>">
</script>
<script type="text/javascript" src="<?php echo $templateDir.'/js/caroufredsel/helper-plugins/jquery.mousewheel.min.js' ?>"></script>
<script type="text/javascript" src="<?php echo $templateDir.'/js/caroufredsel/helper-plugins/jquery.touchSwipe.min.js' ?>"></script>
<?php if(count($this->product->customfieldsRelatedProducts) > 3){ ?>
	<script type="text/javascript">
		jQuery(window).load(function(){
			jQuery('#yt_caroufredsel').carouFredSel({
				responsive: true,
				auto: false,
				scroll: 1,
				prev: '',
				next: '',
				mousewheel: true,
				swipe: {
					onMouse: true,
					onTouch: true
				},
				items: {
					width: 290,
					height: 'auto',	//	optionally resize item-height
					visible: {
						min: 1,
						max: 3
					}
				}
			});
		});
	</script>
<?php } ?>
<?php ?>
