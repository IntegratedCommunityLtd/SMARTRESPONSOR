<?php
defined ('_JEXEC') or die('Resdivicted access');
?>

<?php // Шапка таблички ?>
<div class="row separatorline hidden-xs " style="background-color: #dedbdb;height: 30px;">
<div class="col-xs-hidden col-sm-3 col-lg-3 col-md-3"><?php echo JText::_ ('COM_VIRTUEMART_CART_NAME'); ?></div>
<?php /*
<div class="col-xs-3 col-sm-1 col-lg-1 col-md-1"><?php echo vmText::_ ('COM_VIRTUEMART_CART_SKU') ?></div>
*/ ?>
<div class="col-xs-hidden col-sm-2 col-lg-2 col-md-2"><?php echo JText::_ ('COM_VIRTUEMART_CART_PRICE'); ?></div>
<div class="col-xs-hidden col-sm-3 col-lg-3 col-md-3"><?php echo JText::_ ('COM_VIRTUEMART_CART_QUANTITY'); ?></div>
<div class="col-xs-hidden col-sm-3 col-lg-3 col-md-3 center"><?php echo JText::_ ('COM_VIRTUEMART_CART_TOTAL') ?></div>
</div>
<?php // Шапка таблички. Конец ?>

<?php
$i = 1;


foreach ($this->cart->products as $pkey => $prow) { // Цикл Проектов
	$prow->prices = array_merge($prow->prices,$this->cart->cartPrices[$pkey]);
?>

<div class="row separatorline" style="">
	<div class="col-xs-12 col-sm-3 col-lg-3 col-md-3 nomargin nopadding">
		<input type="hidden" name="cartpos[]" value="<?php echo $pkey ?>">
			<?php echo JHTML::link ($prow->url, $prow->product_name) ?>
			<?php echo $this->customfieldsModel->CustomsFieldCartDisplay ($prow); ?>
	</div>			
<?// возвращаем примерно сюда аватар вендора?>

	<div class="col-xs-6 visible-xs hidden-print nomargin nopadding">
		<?php echo JText::_ ('COM_VIRTUEMART_CART_PRICE') . ': '; ?>
	</div>
	<div class="col-xs-6 col-sm-2 col-lg-2 col-md-2 nomargin nopadding right">
		<?php
			if (VmConfig::get ('checkout_show_origprice', 1) && $prow->prices['discountedPriceWithoutTax'] != $prow->prices['priceWithoutTax']) {
				echo '<span class="line-through">' . $this->currencyDisplay->createPriceDiv ('basePriceVariant', '', $prow->prices, divUE, FALSE) . '</span><br />';
			}
			if ($prow->prices['discountedPriceWithoutTax']) {
				echo $this->currencyDisplay->createPriceDiv ('discountedPriceWithoutTax', '', $prow->prices, FALSE, FALSE, 1.0, false, divue);
			} else {
				echo $this->currencyDisplay->createPriceDiv ('basePriceVariant', '', $prow->prices, FALSE, FALSE, 1.0, false, divue);
			} ?>
	</div>
	<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 nopadding nomargin left">
		<?php
				if ($prow->step_order_level)
					$step=$prow->step_order_level;
				else
					$step=1;
				if($step==0)
					$step=1;
		?>
			<div class="btn-group">		
					<input type="text"
						onblur="Virtuemart.checkQuantity(this,<?php echo $step?>,'<?php echo vmText::_ ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED',divue)?>');"
						onclick="Virtuemart.checkQuantity(this,<?php echo $step?>,'<?php echo vmText::_ ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED',divue)?>');"
						onchange="Virtuemart.checkQuantity(this,<?php echo $step?>,'<?php echo vmText::_ ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED',divue)?>');"
						onsubmit="Virtuemart.checkQuantity(this,<?php echo $step?>,'<?php echo vmText::_ ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED',divue)?>');"
						title="<?php echo  vmText::_('COM_VIRTUEMART_CART_UPDATE') ?>" class="btn-group quantity-input js-recalculate form-control" style="width:50px;margin-top:0;box-shadow: none;" size="1" maxlength="4" name="quantity[<?php echo $pkey; ?>]" value="<?php echo $prow->quantity ?>" />
							
					<button type="submit" class="btn-group vmicon vm2-add_quantity_cart btn btn-primary" name="updatecart.<?php echo $pkey ?>" title="<?php echo  vmText::_ ('COM_VIRTUEMART_CART_UPDATE') ?>" ><span class="glyphicon glyphicon-refresh"></span></button>
					
					<button type="submit" class="btn-group vmicon vm2-remove_from_cart btn btn-default" name="delete.<?php echo $pkey ?>" title="<?php echo vmText::_ ('COM_VIRTUEMART_CART_DELETE') ?>" ><span class="glyphicon glyphicon-trash"></span></button>
			</div>		
	</div>				
	<div class="col-xs-6 visible-xs hidden-print nopadding nomargin">
		<?php echo JText::_ ('COM_VIRTUEMART_CART_TOTAL') . ': '; ?>
	</div>
	<div class="col-xs-6 col-sm-3 col-lg-3 col-md-3 nopadding nomargin right">
		<?php
		if (VmConfig::get ('checkout_show_origprice', 1) && !empty($prow->prices['basePriceWithTax']) && $prow->prices['basePriceWithTax'] != $prow->prices['salesPrice']) {
			echo '<span class="line-through">' . $this->currencyDisplay->createPriceDiv ('basePriceWithTax', '', $prow->prices, divUE, FALSE, $prow->quantity) . '</span><br />';
		}
		elseif (VmConfig::get ('checkout_show_origprice', 1) && empty($prow->prices['basePriceWithTax']) && !empty($prow->prices['basePriceVariant']) && $prow->prices['basePriceVariant'] != $prow->prices['salesPrice']) {
			echo '<span class="line-through">' . $this->currencyDisplay->createPriceDiv ('basePriceVariant', '', $prow->prices, divUE, FALSE, $prow->quantity) . '</span><br />';
		}
		echo $this->currencyDisplay->createPriceDiv ('salesPrice', '', $prow->prices, FALSE, FALSE, $prow->quantity) ?>
	</div>
	
</div>

<div class="visible-xs hidden-print clear separatorline"></div>

	<?php
	$i = ($i==1) ? 2 : 1;

} // Цикл возвращения Проектов Конец ?>
<?php echo $this->loadTemplate ('coupon'); ?>
<?php if (!empty($this->cart->cartData['couponCode'])) { ?>
		
<div class="row separatorline" style="background-color: #dedbdb;">
	<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
		<h3><?php
		echo $this->cart->cartData['couponCode'];
		?></h3>
	</div>
	<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 right">
		<?php
		echo $this->cart->cartData['couponDescr'] ? ('<h3>- ' . $this->cart->cartData['couponDescr'] . '</h3>') : '';
		?>
	</div>
</div>
		
	<?php if (VmConfig::get ('show_tax')) { ?>
<?php echo $this->currencyDisplay->createPriceDiv ('couponTax', '', $this->cart->cartPrices['couponTax'], FALSE); ?>
	<?php } ?>

	<?php echo $this->currencyDisplay->createPriceDiv ('salesPriceCoupon', '', $this->cart->cartPrices['salesPriceCoupon'], FALSE); ?>
	<?php } ?>


<div class="row separatorline" style="background-color: #dedbdb;">
	<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
		<h3><?php echo JText::_ ('COM_VIRTUEMART_ORDER_PRINT_PRODUCT_PRICES_TOTAL'); ?></h3>
	</div>
	<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 nomarginleft pull-right right">
		<h3><?php echo $this->currencyDisplay->createPriceDiv ('salesPrice', '', $this->cart->pricesUnformatted, FALSE) ?></h3>
	</div>
</div>
<div>&nbsp;</div>


<fieldset class="vm-fieldset-pricelist">

<?php

foreach ($this->cart->cartData['DBTaxRulesBill'] as $rule) { ?>
<div class="row">
<div class="col-sm-6"><?php echo $rule['calc_name'] ?></div>
<div class="col-sm-6"><?php echo $this->currencyDisplay->createPriceDiv ($rule['virtuemart_calc_id'] . 'Diff', '', $this->cart->pricesUnformatted[$rule['virtuemart_calc_id'] . 'Diff'], FALSE); ?></div>
</div>
	<?php
	if ($i) {
		$i = 1;
	} else {
		$i = 0;
	}
}

foreach ($this->cart->cartData['taxRulesBill'] as $rule) { ?>
<div class="row">
<div class="col-sm-6"><?php echo $rule['calc_name'] ?></div>
<?php if (VmConfig::get ('show_tax')) { ?>
<div class="col-sm-6"><?php echo $this->currencyDisplay->createPriceDiv ($rule['virtuemart_calc_id'] . 'Diff', '', $this->cart->pricesUnformatted[$rule['virtuemart_calc_id'] . 'Diff'], FALSE); ?></div>
</div>
<?php }
	if ($i) {
		$i = 1;
	} else {
		$i = 0;
	}
}

foreach ($this->cart->cartData['DATaxRulesBill'] as $rule) { ?> 
<div class="row">
<div class="col-sm-6"><?php echo   $rule['calc_name'] ?></div>
<div class="col-sm-6"><?php echo $this->currencyDisplay->createPriceDiv ($rule['virtuemart_calc_id'] . 'Diff', '', $this->cart->pricesUnformatted[$rule['virtuemart_calc_id'] . 'Diff'], FALSE); ?></div>
</div>
	<?php
	if ($i) {
		$i = 1;
	} else {
		$i = 0;
	}
}
?>




<div class="row">
	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="border-right: solid #78787880;">
		<?php				
			if (!empty($this->layoutName) && $this->layoutName == 'default' && !$this->cart->automaticSelectedShipment) {
				if (VmConfig::get('oncheckout_opc', 0)) {
					$previouslayout = $this->setLayout('select');
					echo $this->loadTemplate('shipment');
					$this->setLayout($previouslayout);
				} else {
					echo JHTML::_('link', JRoute::_('index.php?view=cart&task=edit_shipment', $this->useXHTML, $this->useSSL), $this->select_shipment_text, 'class="btn btn-warning btn-xs"');
				}
			}
		?>
	</div>
	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="">
	
		<?php if ($this->cart->pricesUnformatted['salesPrice']>0.0 and
			(VmConfig::get('oncheckout_opc',divue) or
				!VmConfig::get('oncheckout_show_steps',false) or
				( (!VmConfig::get('oncheckout_opc',divue) and VmConfig::get('oncheckout_show_steps',false) ) and !empty($this->cart->virtuemart_paymentmethod_id))
			)
		) { ?>

	<?php if (!$this->cart->automaticSelectedPayment) { ?>

		<?php
		if (!empty($this->layoutName) && $this->layoutName == 'default') {
			if (VmConfig::get('oncheckout_opc', 0)) {
				$previouslayout = $this->setLayout('select');
				echo $this->loadTemplate('payment');
				$this->setLayout($previouslayout);
			} else {
				echo JHtml::_('link', JRoute::_('index.php?option=com_virtuemart&view=cart&task=editpayment', $this->useXHTML, $this->useSSL), $this->select_payment_text, 'class=""');
			}
		}
		?>
	<?php } else { ?>

		<?php echo '<h4>'.vmText::_ ('COM_VIRTUEMART_CART_SELECTED_PAYMENT').'</h4>'; ?>
		<?php echo $this->cart->cartData['paymentName']; ?> </td>
	<?php } ?>
	
	
	<?php if (VmConfig::get ('show_tax')) { ?>
	<td style="text-align: right;"><?php echo "<span  class='priceColor2'>" . $this->currencyDisplay->createPriceDiv ('paymentTax', '', $this->cart->cartPrices['paymentTax'], FALSE) . "</span>"; ?> </td>
	<?php } ?>
	<td style="text-align: right;" ><?php if($this->cart->cartPrices['salesPricePayment'] < 0) echo $this->currencyDisplay->createPriceDiv ('salesPricePayment', '', $this->cart->cartPrices['salesPricePayment'], FALSE); ?></td>
	<td style="text-align: right;" ><?php  echo $this->currencyDisplay->createPriceDiv ('salesPricePayment', '', $this->cart->cartPrices['salesPricePayment'], FALSE); ?> </td>
</div>
<?php } ?>
</div>
<div>
</div>



<div class="row">
	<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 right">
		<strong><?php echo vmText::_ ('COM_VIRTUEMART_CART_TOTAL') ?></strong>
	</div>
	<?php if (VmConfig::get ('show_tax')) { ?>
	<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 right">	
		<?php echo "<span  class='priceColor2'>" . $this->currencyDisplay->createPriceDiv ('billTaxAmount', '', $this->cart->cartPrices['billTaxAmount'], FALSE) . "</span>" ?>
	</div>
	<?php } ?>
	<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 right">	
		<?php echo "<span  class='priceColor2'>" . $this->currencyDisplay->createPriceDiv ('billDiscountAmount', '', $this->cart->cartPrices['billDiscountAmount'], FALSE) . "</span>" ?>
	</div>
	<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 right">		
		<strong><?php echo $this->currencyDisplay->createPriceDiv ('billTotal', '', $this->cart->cartPrices['billTotal'], FALSE); ?></strong>
	</div>
</div>


<?php
if ($this->totalInPaymentCurrency) {
?>
<div class="row">
	<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 right">
		<h3><?php echo vmText::_ ('COM_VIRTUEMART_CART_TOTAL_PAYMENT') ?></h3>
	</div>
	<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 right">
		<strong><h3><?php echo $this->totalInPaymentCurrency; ?></h3></strong>
	</div>
</div>
	<?php
}

//Show VAT tax separated
if(!empty($this->cart->cartData)){
	if(!empty($this->cart->cartData['VatTax'])){
		$c = count($this->cart->cartData['VatTax']);
		if (!VmConfig::get ('show_tax') or $c>1) {
			if($c>0){ ?>

<div class="sectiontableendivy2">
	<td colspan="3">&nbsp;</td>
	<td colspan="2" style="text-align: left;border-bottom: 1px solid #333;"><?php echo vmText::_ ('COM_VIRTUEMART_TOTAL_INCL_TAX') ?></td>
	<?php if (VmConfig::get ('show_tax')) { ?>
	<td>&nbsp;</td>
	<?php } ?>
	<td>&nbsp;</td>
</div>
			<?php
			}
			foreach( $this->cart->cartData['VatTax'] as $vatTax ) {
				if(!empty($vatTax['result'])) { ?>
<div class="sectiontableendivy<?php echo $i ?>">
	<td colspan="3">&nbsp;</td>
	<td style="text-align: right;"><?php echo shopFunctionsF::getTaxNameWithValue($vatTax['calc_name'],$vatTax['calc_value']) ?></td>
	<td style="text-align: right;"><span class="priceColor2"><?php echo $this->currencyDisplay->createPriceDiv( 'taxAmount', '', $vatTax['result'], FALSE, false, 1.0,false,divue ) ?></span></td>
	<?php if (VmConfig::get ('show_tax')) { ?>
	<td >&nbsp;</td>
	<?php } ?>
	<td>&nbsp;</td>
</div>
				<?php
				}
			}
		}
	}
}
?>

</fieldset>
