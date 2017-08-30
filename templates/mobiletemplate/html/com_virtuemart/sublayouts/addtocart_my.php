<?php
defined('_JEXEC') or die('Restricted access');
?>
<?php
$product = $viewData['product'];

if(isset($viewData['rowHeights'])){
	$rowHeights = $viewData['rowHeights'];
} else {
	$rowHeights['customfields'] = TRUE;
}

if(isset($viewData['position'])){
	$positions = $viewData['position'];
} else {
	$positions = 'addtocart';
}
if(!is_array($positions)) $positions = array($positions);

$addtoCartButton = '';
if(!VmConfig::get('use_as_catalog', 0)){
	if($product->addToCartButton){
		$addtoCartButton = $product->addToCartButton;
	} else {
		$addtoCartButton = shopFunctionsF::getAddToCartButton ($product->orderable);
	}

}


?>

	<form method="post" role="form" action="<?php echo JRoute::_ ('index.php?option=com_ajax&option=com_virtuemart',false); ?>" class="product">
				<?php
				if(!empty($rowHeights['customfields'])) {
					foreach($positions as $pos){
						echo shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$product,'position'=>$pos));
					}
				} ?>
				<?php
					echo shopFunctionsF::renderVmSubLayout('addtocartbar_my',array('product'=>$product));

			$itemId=vRequest::getInt('Itemid',false);
			if($itemId){
				echo '<input type="hidden" name="Itemid" value="'.$itemId.'"/>';
			} ?>
		
	</form>