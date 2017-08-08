<?php
defined ('_JEXEC') or die('Restricted access');
// Clear cart http://joomlaforum.ru/index.php?topic=221323.0
?>
<a href="<?=JRoute::_( 'index.php?option=com_ajax&option=com_virtuemart&view=cart&task=deleteCart' )?>">Clear cart</a>

<?php // Шапка таблички ?>
<div class="row separatorline hidden-xs " style="background-color: #dedbdb;height: 30px;">
<div class="col-sm-3 col-lg-3 col-md-3"><?php echo JText::_ ('COM_VIRTUEMART_CART_NAME'); ?></div>
<div class="col-sm-2 col-lg-2 col-md-2"><?php echo JText::_ ('COM_VIRTUEMART_CART_PRICE'); ?></div>
<div class="col-sm-4 col-lg-4 col-md-4"><?php echo JText::_ ('COM_VIRTUEMART_CART_QUANTITY'); ?></div>
<div class="col-sm-3 col-lg-3 col-md-3 center"><?php echo JText::_ ('COM_VIRTUEMART_CART_TOTAL') ?></div>
</div>


<?php // Шапка таблички Конец ?>


<?php
$i = 1;

foreach ($this->cart->products as $pkey => $prow) { // Цикл Проектов
	$prow->prices = array_merge($prow->prices,$this->cart->cartPrices[$pkey]);
?>

<div class="row separatorline" style="max-height:35px; overflow:hidden;">

<div class="visible-xs hidden-print pull-left"><?php echo JText::_ ('COM_VIRTUEMART_CART_NAME') . ': '; ?></div>	

<div class="col-sm-3 col-lg-3 col-md-3">
		<input type="hidden" name="cartpos[]" value="<?php echo $pkey ?>">
			<?php echo JHTML::link ($prow->url, $prow->product_name) ?>
			<?php echo $this->customfieldsModel->CustomsFieldCartDisplay ($prow); ?>
<?// возвращаем сюда аватар вендора?>


</div>

<div class="visible-xs hidden-print pull-left"><?php echo JText::_ ('COM_VIRTUEMART_CART_PRICE') . ': '; ?></div>


<div class="col-sm-2 col-lg-2 col-md-2">

	<?php
		if (VmConfig::get ('checkout_show_origprice', 1) && $prow->prices['discountedPriceWithoutTax'] != $prow->prices['priceWithoutTax']) {
			echo '<span class="line-through">' . $this->currencyDisplay->createPriceDiv ('basePriceVariant', '', $prow->prices, TRUE, FALSE) . '</span><br />';
		}
		if ($prow->prices['discountedPriceWithoutTax']) {
			echo $this->currencyDisplay->createPriceDiv ('discountedPriceWithoutTax', '', $prow->prices, FALSE, FALSE, 1.0, false, true);
		} else {
			echo $this->currencyDisplay->createPriceDiv ('basePriceVariant', '', $prow->prices, FALSE, FALSE, 1.0, false, true);
		} ?>
	
</div>


<div class="visible-xs hidden-print pull-left"><?php echo JText::_ ('COM_VIRTUEMART_CART_QUANTITY') . ': '; ?></div>

<div class="col-sm-4 col-lg-4 col-md-4 center nopadding nomargin">
		<?php
				if ($prow->step_order_level)
					$step=$prow->step_order_level;
				else
					$step=1;
				if($step==0)
					$step=1;
				?>
	<div class="btn-group col-lg-12 col-sm-12 col-md-12">	
		
		<input type="text"
			onblur="Virtuemart.checkQuantity(this,<?php echo $step?>,'<?php echo vmText::_ ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED',true)?>');"
			onclick="Virtuemart.checkQuantity(this,<?php echo $step?>,'<?php echo vmText::_ ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED',true)?>');"
			onchange="Virtuemart.checkQuantity(this,<?php echo $step?>,'<?php echo vmText::_ ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED',true)?>');"
			onsubmit="Virtuemart.checkQuantity(this,<?php echo $step?>,'<?php echo vmText::_ ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED',true)?>');"
			title="<?php echo  vmText::_('COM_VIRTUEMART_CART_UPDATE') ?>" class="btn-group quantity-input js-recalculate form-control" style="width:50px;margin-top:0;box-shadow: none;" size="1" maxlength="4" name="quantity[<?php echo $pkey; ?>]" value="<?php echo $prow->quantity ?>" />
		
		
		<button type="submit" class="btn-group vmicon vm2-add_quantity_cart btn btn-primary" name="updatecart.<?php echo $pkey ?>" title="<?php echo  vmText::_ ('COM_VIRTUEMART_CART_UPDATE') ?>" ><span class="glyphicon glyphicon-refresh"></span></button>
		
		<button type="submit" class="btn-group vmicon vm2-remove_from_cart btn btn-default" name="delete.<?php echo $pkey ?>" title="<?php echo vmText::_ ('COM_VIRTUEMART_CART_DELETE') ?>" ><span class="glyphicon glyphicon-trash"></span></button>
		</div>		
	</div>				
				
		



<div class="visible-xs hidden-print pull-left"><?php echo JText::_ ('COM_VIRTUEMART_CART_TOTAL') . ': '; ?></div>

<div class="col-sm-3 col-lg-3 col-md-3 right">
		<?php
		if (VmConfig::get ('checkout_show_origprice', 1) && !empty($prow->prices['basePriceWithTax']) && $prow->prices['basePriceWithTax'] != $prow->prices['salesPrice']) {
			echo '<span class="line-through">' . $this->currencyDisplay->createPriceDiv ('basePriceWithTax', '', $prow->prices, TRUE, FALSE, $prow->quantity) . '</span><br />';
		}
		elseif (VmConfig::get ('checkout_show_origprice', 1) && empty($prow->prices['basePriceWithTax']) && !empty($prow->prices['basePriceVariant']) && $prow->prices['basePriceVariant'] != $prow->prices['salesPrice']) {
			echo '<span class="line-through">' . $this->currencyDisplay->createPriceDiv ('basePriceVariant', '', $prow->prices, TRUE, FALSE, $prow->quantity) . '</span><br />';
		}
		echo $this->currencyDisplay->createPriceDiv ('salesPrice', '', $prow->prices, FALSE, FALSE, $prow->quantity) ?>
</div>

</div>

<div class="visible-xs hidden-print clear separatorline"></div>

	<?php
	$i = ($i==1) ? 2 : 1;

} // Цикл возвращения Проектов Конец ?>

<div class="row">
<div class="col-sm-2"><strong><?php echo JText::_ ('COM_VIRTUEMART_ORDER_PRINT_PRODUCT_PRICES_TOTAL'); ?></strong></div>
<div class="col-sm-1 nopadding nomargin"><?php echo "<span  class='priceColor2'>" . $this->currencyDisplay->createPriceDiv ('taxAmount', '', $this->cart->pricesUnformatted, FALSE) . "</span>" ?></div>
<div class="col-sm-1 nomarginleft" style="background-color: #fff;"><?php echo "<span  class='priceColor2'>" . $this->currencyDisplay->createPriceDiv ('discountAmount', '', $this->cart->pricesUnformatted, FALSE) . "</span>" ?></div>
<div class="col-sm-4 nomarginleft pull-right right"><strong><?php echo $this->currencyDisplay->createPriceDiv ('salesPrice', '', $this->cart->pricesUnformatted, FALSE) ?></strong></div>
</div>

</div>
<?php
	if (!empty($this->layoutName) && $this->layoutName == 'default') { ?>
<div class="row">
<div class="col-sm-12 nopaddingright separatorline"><?php echo $this->loadTemplate ('coupon'); ?></div>
</div>	
	<?php } ?>



<?php if (!empty($this->cart->cartData['couponCode'])) { /*?>
<div class="row nopaddingright separatorline" style="margin-top: 5px;">
	<div class="col-sm-12 btn-warning center" style="height: 50px;padding: 16px 0px;">
		<?php
//		echo $this->cart->cartData['couponCode'];
//		echo $this->cart->cartData['couponDescr'] ? (' (' . $this->cart->cartData['couponDescr'] . ')') : '';
		?>
	</div>
</div>
<div class="row nopaddingright separatorline">
<?php if (VmConfig::get ('show_tax')) { ?>
<div class="col-sm-4"><?php echo $this->currencyDisplay->createPriceDiv ('couponTax', '', $this->cart->pricesUnformatted['couponTax'], FALSE); ?></div>
<?php } ?>
<div class="col-sm-4"><?php echo $this->currencyDisplay->createPriceDiv ('salesPriceCoupon', '', $this->cart->pricesUnformatted['salesPriceCoupon'], FALSE); ?></div>
</div>
	<?php */} ?>



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

	if (!$this->cart->automaticSelectedShipment) { ?>

<div class="row">
<?php /*	<td colspan="2" align="right"><?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_SHIPPING'); ?> </td> */ ?>
<div class="col-sm-12" style="background-color: #fff; margin-top: 5px;"><?php echo $this->cart->cartData['shipmentName'];
				
	if (!empty($this->layoutName) && $this->layoutName == 'default' && !$this->cart->automaticSelectedShipment) {
		if (VmConfig::get('oncheckout_opc', 0)) {
			$previouslayout = $this->setLayout('select');
			echo $this->loadTemplate('shipment');
			$this->setLayout($previouslayout);
		} else {
			echo JHTML::_('link', JRoute::_('index.php?view=cart&task=edit_shipment', $this->useXHTML, $this->useSSL), $this->select_shipment_text, 'class="btn btn-warning btn-xs"');
		}
	} else {
		echo JText::_ ('COM_VIRTUEMART_CART_SHIPPING');
	} ?>
</div>
</div>	
<?php } else { ?>
<div class="row">
<div class="col-sm-12" style="background-color: #fff;"><?php echo $this->cart->cartData['shipmentName']; ?></div>
</div>
	<?php }

	if (VmConfig::get ('show_tax')) { ?>
<div class="row">
<div class="col-sm-12"><?php echo "<span  class='priceColor2'>" . $this->currencyDisplay->createPriceDiv ('shipmentTax', '', $this->cart->pricesUnformatted['shipmentTax'], FALSE) . "</span>"; ?></div>
</div>
<?php }
	
	if($this->cart->pricesUnformatted['salesPriceShipment'] < 0) ?>

<div class="row">
<div class="col-sm-12" style="background-color: #fff;"><?php echo $this->currencyDisplay->createPriceDiv ('salesPriceShipment', '', $this->cart->pricesUnformatted['salesPriceShipment'], FALSE); ?></div>
</div>

<div class="row">
<div class="col-sm-12" style="background-color: #fff;"><?php echo $this->currencyDisplay->createPriceDiv ('salesPriceShipment', '', $this->cart->pricesUnformatted['salesPriceShipment'], FALSE); ?></div>
</div>

<?php
	if ($this->cart->pricesUnformatted['salesPrice']>0.0 ) {
		if (!$this->cart->automaticSelectedPayment) { ?>
</br>		
<div class="row">
<div class="col-sm-12" style="background-color: #fff;"><?php echo $this->cart->cartData['paymentName']; ?>
		<br/>

		
		
		<?php 
			if (VmConfig::get('oncheckout_opc', 0)) {
				$previouslayout = $this->setLayout('select');
				echo $this->loadTemplate('payment');
				$this->setLayout($previouslayout);
			} else {
				echo JHTML::_('link', JRoute::_('index.php?view=cart&task=editpayment', $this->useXHTML, $this->useSSL), $this->select_payment_text, 'class="btn btn-primary btn-xs"');
			}
			?>
</div>
</div>
	<?php } else { ?>
<div class="row">
<div class="col-sm-12 nopadding nomargin"><?php echo $this->cart->cartData['paymentName']; ?></div>
</div>
	<?php } ?>

<?php if (VmConfig::get ('show_tax')) { ?>
<div class="row">
<div class="col-sm-12"><?php echo "<span  class='priceColor2'>" . $this->currencyDisplay->createPriceDiv ('paymentTax', '', $this->cart->pricesUnformatted['paymentTax'], FALSE) . "</span>"; ?></div>
</div>
	<?php } ?>

<div class="row">
<div class="col-sm-12"><?php if($this->cart->pricesUnformatted['salesPricePayment'] < 0) echo $this->currencyDisplay->createPriceDiv ('salesPricePayment', '', $this->cart->pricesUnformatted['salesPricePayment'], FALSE); ?></div>
</div>

<div class="row">
<div class="col-sm-12"><?php  echo $this->currencyDisplay->createPriceDiv ('salesPricePayment', '', $this->cart->pricesUnformatted['salesPricePayment'], FALSE); ?></div>
</div>
<?php } ?>

<div class="row nomargin nopadding">
<div class="col-sm-6 col-md-6 col-xs-6 col-lg-3 nomargin nopadding x-large"><strong><?php echo JText::_ ('COM_VIRTUEMART_CART_TOTAL') ?></strong>:</div>
<div class="col-lg-3 col-sm-6 col-md-6 col-xs-6 nomargin nopadding x-large"><strong><?php echo $this->currencyDisplay->createPriceDiv ('billTotal', '', $this->cart->pricesUnformatted['billTotal'], FALSE); ?></strong></div>

<?php if ($this->totalInPaymentCurrency) { ?>
<div class="col-sm-6 col-md-6 col-xs-6 col-lg-3"><?php echo JText::_ ('COM_VIRTUEMART_CART_TOTAL_PAYMENT') ?>:</div>
<div class="col-lg-3 col-sm-6 col-md-6 col-xs-6 nomargin nopadding x-large"><strong><?php echo $this->totalInPaymentCurrency; ?></strong></div>
	<?php } ?>
</div>

<?php
//Show VAT tax separated
if(!empty($this->cart->cartData)){
	if(!empty($this->cart->cartData['VatTax'])){
		$c = count($this->cart->cartData['VatTax']);
		if (!VmConfig::get ('show_tax') or $c>1) {
			if($c>0){ ?>

<tr class="sectiontableentry2">
	<td colspan="3">&nbsp;</td>
	<td colspan="2" style="text-align: left;border-bottom: 1px solid #333;"><?php echo vmText::_ ('COM_VIRTUEMART_TOTAL_INCL_TAX') ?></td>
	<?php if (VmConfig::get ('show_tax')) { ?>
	<td>&nbsp;</td>
	<?php } ?>
	<td>&nbsp;</td>
</tr>
			<?php
			}
			foreach( $this->cart->cartData['VatTax'] as $vatTax ) {
				if(!empty($vatTax['result'])) { ?>
<tr class="sectiontableentry<?php echo $i ?>">
	<td colspan="3">&nbsp;</td>
	<td style="text-align: right;"><?php echo shopFunctionsF::getTaxNameWithValue($vatTax['calc_name'],$vatTax['calc_value']) ?></td>
	<td style="text-align: right;"><span class="priceColor2"><?php echo $this->currencyDisplay->createPriceDiv( 'taxAmount', '', $vatTax['result'], FALSE, false, 1.0,false,true ) ?></span></td>
	<?php if (VmConfig::get ('show_tax')) { ?>
	<td >&nbsp;</td>
	<?php } ?>
	<td>&nbsp;</td>
</tr>
				<?php
				}
			}
		}
	}
}
?>
<script type="text/javascript" src="//smartresponsor.com/js/jquery.slimscroll.min.js"></script>
<script>
$(function(){
    $('.output-billto').slimScroll({
        height: '250px'
    });
});
$(function(){
    $('.output-shipto').slimScroll({
        height: '250px'
    });
});
</script>