<?php
defined('_JEXEC') or die('Restricted access');
if (isset($this->product->step_order_level))
	$step=$this->product->step_order_level;
else
	$step=1;
if($step==0)
	$step=1;
$alert=JText::sprintf ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED', $step);
?>

<div class="addtocart-area">
	<form method="post" class="form-inline product js-recalculate pull-right" role="form" action="<?php echo JRoute::_ ('index.php'); ?>">
        <input name="quantity" type="hidden" value="<?php echo $step ?>" />
		<?php // Product custom_fields
		if (!empty($this->product->customfieldsCart)) {
			?>

				<?php foreach ($this->product->customfieldsCart as $field) { ?>
				<div class="form-group product-field product-field-type-<?php echo $field->field_type ?>">
					<?php if ($field->show_title) { ?>
						<span class="product-fields-title-wrapper"><span class="product-fields-title"><strong><?php echo JText::_ ($field->custom_title) ?></strong></span>
					<?php }
					if ($field->custom_tip) {
						echo JHTML::tooltip ($field->custom_tip, JText::_ ($field->custom_title), 'tooltip.png');
					} ?></span>
					<span class="product-field-display"><?php echo $field->display ?></span>
					<span class="product-field-desc"><?php echo $field->custom_field_desc ?></span>
				</div><br/>
				<?php } ?>
				<?php
			if (!empty($this->product->customfieldsSorted['normal'])) {
			$this->position = 'normal';
			echo $this->loadTemplate('customfields');
			} // Product custom_fields END
			// Product Packaging
			$product_packaging = '';
			if ($this->product->product_box) {
		?>
			<div class="product-box">
			<?php
				echo JText::_('COM_VIRTUEMART_PRODUCT_UNITS_IN_BOX') .$this->product->product_box;
			?>
			</div>
		<?php } // Product Packaging END
		?>

			<?php
		}
		/* Product custom Childs
			 * to display a simple link use $field->virtuemart_product_id as link to child product_id
			 * custom_value is relation value to child
			 */

		if (!empty($this->product->customsChilds)) {
			?>
			
			

				<?php foreach ($this->product->customsChilds as $field) { ?>
				<div class="form-group product-field product-field-type-<?php echo $field->field->field_type ?>">
					<span class="product-fields-title"><strong><?php echo JText::_ ($field->field->custom_title) ?></strong></span>
					<span class="product-field-desc"><?php echo JText::_ ($field->field->custom_value) ?></span>
					<span class="product-field-display"><?php echo $field->display ?></span>

				</div>
				<?php } ?>
		<?php }

		if (!VmConfig::get('use_as_catalog', 0)  ) {
		?>
		
		<div class="addtocart-bar">

			<script type="text/javascript">
					function check(obj) {
					// use the modulus operator '%' to see if there is a remainder
					remainder=obj.value % <?php echo $step?>;
					quantity=obj.value;
					if (remainder  != 0) {
						alert('<?php echo $alert?>!');
						obj.value = quantity-remainder;
						return false;
						}
					return true;
					}
			</script> 

			<?php // Display the quantity box

			$stockhandle = VmConfig::get ('stockhandle', 'none');
			if (($stockhandle == 'disableit' or $stockhandle == 'disableadd') and ($this->product->product_in_stock - $this->product->product_ordered) < 1) {
				?>
				<a href="<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&layout=notify&virtuemart_product_id=' . $this->product->virtuemart_product_id); ?>" class="notify"><?php echo JText::_ ('COM_VIRTUEMART_CART_NOTIFY') ?></a>

				<?php } else { ?>
				<div class="btn-group">				
				<label for="quantity<?php echo $this->product->virtuemart_product_id; ?>" class="quantity_box"><?php echo JText::_ ('COM_VIRTUEMART_CART_QUANTITY'); ?>: </label>
					<input type="text" class="btn btn-default quantity-input js-recalculate" name="quantity[]" onblur="check(this);" value="<?php if (isset($this->product->step_order_level) && (int)$this->product->step_order_level > 0) {
							echo $this->product->step_order_level;
					} else
						if(!empty($this->product->min_order_level)){
							echo $this->product->min_order_level;
					}else {
							echo '1';
					} ?>"/>
					
					<input type="button" class="btn btn-primary quantity-controls quantity-plus" value="+"/>
					<input type="button" class="btn btn-primary quantity-controls quantity-minus" value="-"/>
					<?php echo shopFunctionsF::getAddToCartButton ($this->product->orderable); ?>
				</div>
				<?php } ?>

			<div class="clear"></div>
		</div>
		<?php }
		 // Display the add to cart button END  ?>
		<input type="hidden" class="pname" value="<?php echo htmlentities($this->product->product_name, ENT_QUOTES, 'utf-8') ?>"/>
		<input type="hidden" name="option" value="com_virtuemart"/>
		<input type="hidden" name="view" value="cart"/>
		<noscript><input type="hidden" name="task" value="add"/></noscript>
		<input type="hidden" name="virtuemart_product_id[]" value="<?php echo $this->product->virtuemart_product_id ?>"/>
	</form>

	<div class="clear"></div>
</div>
