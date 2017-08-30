<?php
defined('_JEXEC') or die('Restricted access');
$addClass="";


if (VmConfig::get('oncheckout_show_steps', 1)) {
	echo '<div class="checkoutStep" id="checkoutStep3">' . vmText::_('COM_VIRTUEMART_USER_FORM_CART_STEP3') . '</div>';
}

if ($this->layoutName!='default') {
	$headerLevel = 1;
	if($this->cart->getInCheckOut()){
		$buttonclass = 'button vm-button-correct';
	} else {
		$buttonclass = 'default';
	}
	?>
	<form method="post" id="paymentForm" name="choosePaymentRate" action="<?php echo JRoute::_('index.php'); ?>" class="form-validate <?php echo $addClass ?>">
<?php } else {
	$headerLevel = 3;
	$buttonclass = 'vm-button-correct';
}

if($this->cart->virtuemart_paymentmethod_id){
	echo '<h'.$headerLevel.' class="vm-payment-header-selected">'.vmText::_('COM_VIRTUEMART_CART_SELECTED_PAYMENT_SELECT').'</h'.$headerLevel.'>';
} else {
		echo '<h3 class="vm-shipment-header-select">'.vmText::_('COM_VIRTUEMART_CART_SELECT_PAYMENT').'</h3>';
	}





if ($this->found_payment_method ) {


	echo '<fieldset class="vm-payment-shipment-select vm-payment-select">';
	foreach ($this->paymentplugins_payments as $paymentplugin_payments) {
		if (is_array($paymentplugin_payments)) {
			foreach ($paymentplugin_payments as $paymentplugin_payment) {
				echo '<div class="vm-payment-plugin-single">'.$paymentplugin_payment.'</div>';
			}
		}
	}
	echo '</fieldset>';

}

if ($this->layoutName!='default') {
	?>    <input type="hidden" name="option" value="com_virtuemart" />
	<input type="hidden" name="view" value="cart" />
	<input type="hidden" name="task" value="updatecart" />
	<input type="hidden" name="controller" value="cart" />
	</form>
<?php
}
?>