<?php
defined('_JEXEC') or die('Restricted access');
?>
<form method="post" id="shipmentForm" name="chooseShipmentRate" action="<?php echo JRoute::_('index.php'); ?>" class="form-validate">
	<?php

	if($this->cart->virtuemart_shipmentmethod_id){
		echo '<h3 class="vm-shipment-header-selected">'.vmText::_('COM_VIRTUEMART_CART_SELECTED_SHIPMENT_SELECT').'</h3>';
	} else {
		echo '<h3 class="vm-shipment-header-select">'.vmText::_('COM_VIRTUEMART_CART_SELECT_SHIPMENT').'</h3>';
	}


	?>


	<?php
	if ($this->found_shipment_method ) {

		echo '<fieldset class="vm-payment-shipment-select vm-shipment-select">';
		// if only one Shipment , should be checked by default
		foreach ($this->shipments_shipment_rates as $shipment_shipment_rates) {
			if (is_array($shipment_shipment_rates)) {
				foreach ($shipment_shipment_rates as $shipment_shipment_rate) {
					echo '<div class="vm-shipment-plugin-single">'.$shipment_shipment_rate.'</div>';
				}
			}
		}
		echo '</fieldset>';
	}


	?>
	<input type="hidden" name="option" value="com_virtuemart" />
	<input type="hidden" name="view" value="cart" />
	<input type="hidden" name="task" value="updatecart" />
	<input type="hidden" name="controller" value="cart" />
</form>

