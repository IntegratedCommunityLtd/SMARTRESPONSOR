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
		<a href="<?php echo JRoute::_ ('index.php?option=com_ajax&option=com_virtuemart&view=productdetails&layout=notify&virtuemart_product_id=' . $product->virtuemart_product_id); ?>" class="btn-group btn btn-info notify"><?php echo vmText::_ ('COM_VIRTUEMART_CART_NOTIFY') ?></a><?php
	} else {
		$tmpPrice = (float) $product->prices['costPrice'];
		if (!( VmConfig::get('askprice', true) and empty($tmpPrice) ) ) { ?>
			<?php if ($product->orderable) { ?>

					<?php
							echo $addtoCartButton;
					?>
				
			<?php }
			?>
			<input type="hidden" name="virtuemart_product_id[]" value="<?php echo $product->virtuemart_product_id ?>"/>
			<noscript><input type="hidden" name="task" value="add"/></noscript>
			<?php
		}
	}
	?>

