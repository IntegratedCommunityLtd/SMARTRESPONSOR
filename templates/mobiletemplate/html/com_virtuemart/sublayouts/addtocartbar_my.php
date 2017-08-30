<?php
defined('_JEXEC') or die('Restricted access');
$product = $viewData['product'];

if(isset($viewData['rowHeights'])){
	$rowHeights = $viewData['rowHeights'];
} else {
	$rowHeights['customfields'] = TRUE;
}

$init = 1;
if(isset($viewData['init'])){
	$init = $viewData['init'];
}

if(!empty($product->min_order_level) and $init<$product->min_order_level){
	$init = $product->min_order_level;
}

$step=1;
if (!empty($product->step_order_level)){
	$step=$product->step_order_level;
	if(!empty($init)){
		if($init<$step){
			$init = $step;
		} else {
			$init = ceil($init/$step) * $step;

		}
	}
	if(empty($product->min_order_level) and !isset($viewData['init'])){
		$init = $step;
	}
}

$maxOrder= '';
if (!empty($product->max_order_level)){
	$maxOrder = ' max="'.$product->max_order_level.'" ';
}

$addtoCartButton = '';
	if(!$product->addToCartButton and $product->addToCartButton!==''){
		$addtoCartButton = shopFunctionsF::getAddToCartButton ($product->orderable);
	} else {
		$addtoCartButton = $product->addToCartButton;
	}

$position = 'addtocart';

?>


	<?php
	// Display the quantity box
	$stockhandle = VmConfig::get ('stockhandle', 'none');
	if (($stockhandle == 'disableit' or $stockhandle == 'disableadd') and ($product->product_in_stock - $product->product_ordered) < 1) { ?>
		<a href="<?php echo JRoute::_ ('index.php?option=com_ajax&option=com_virtuemart&view=productdetails&layout=notify&virtuemart_product_id=' . $product->virtuemart_product_id); ?>" class="notify btn-group btn btn-info "><?php echo vmText::_ ('COM_VIRTUEMART_CART_NOTIFY') ?></a>

<?php
	} else {
		$tmpPrice = (float) $product->prices['costPrice'];
		if (!( VmConfig::get('askprice', true) and empty($tmpPrice) ) ) { ?>
		<div class="input-group">		
			<?php if ($product->orderable) { ?>
				<input type="text" style="margin-top:0" class="form-control pull-left quantity-input js-recalculate " name="quantity[]"
					data-errStr="<?php echo vmText::_ ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED')?>"
					value="<?php echo $init; ?>" init="<?php echo $init; ?>" step="<?php echo $step; ?>" <?php echo $maxOrder; ?> />
					
				<div class="input-group-btn">	
					<button type="button" class="btn btn-primary quantity-plus" value="+"><span class="glyphicon glyphicon-plus"></span></button>
					<button type="button" class="btn btn-primary quantity-minus" value="-"><span class="glyphicon glyphicon-minus"></span></button>
				</div>
		</div>					
					<?php
						if(!empty($addtoCartButton)){
							echo $addtoCartButton;
						}
					?>
				
			<?php }
			?>
			<input type="hidden" name="virtuemart_product_id[]" value="<?php echo $product->virtuemart_product_id ?>"/>
			<noscript><input type="hidden" name="task" value="add"/></noscript>
			<?php
		}
	}	
	?>
