<?php
defined ('_JEXEC') or die('Restricted access');
vmJsApi::vmValidator();
?>
<div id="cart-view" class="cart-view">
	<div class="cartview-head">
		<h1><?php echo vmText::_ ('COM_VIRTUEMART_CART_TITLE'); ?></h1>
		<div class="payments-signin-button" ></div>
	<?php if (VmConfig::get ('oncheckout_show_steps', 1) ){
		if($this->checkout_task == 'checkout') {
			echo '<div class="checkoutStep" id="checkoutStep1">' . vmText::_ ('COM_VIRTUEMART_USER_FORM_CART_STEP1') . '</div>';
		} else { //if($this->checkout_task == 'confirm') {
			echo '<div class="checkoutStep" id="checkoutStep4">' . vmText::_ ('COM_VIRTUEMART_USER_FORM_CART_STEP4') . '</div>';
		}
	}  ?>

		<?php // Continue Shopping Button
		if (!empty($this->continue_link_html)) {
			echo $this->continue_link_html;
		} ?>

	<div class="clear"></div>
	</div>

<?php $taskRoute = ''; ?>
<form role="form" method="post" id="checkoutForm" name="checkoutForm" action="<?php echo JRoute::_ ('index.php?option=com_ajax&option=com_virtuemart&view=cart' . $taskRoute, $this->useXHTML, $this->useSSL); ?>">
		<?php
		if(!$this->isPdf and VmConfig::get('multixcart')=='byselection'){
			if (!class_exists('ShopFunctions')) require(VMPATH_ADMIN . DS . 'helpers' . DS . 'shopfunctions.php');
			echo shopFunctions::renderVendorFullVendorList($this->cart->vendorId);
			?><input type="submit" name="updatecart" title="<?php echo vmText::_('COM_VIRTUEMART_SAVE'); ?>" value="<?php echo vmText::_('COM_VIRTUEMART_SAVE'); ?>" class="btn btn-primary btn-sm button"  style="margin-left: 10px;"/><?php
		}
//		echo $this->loadTemplate ('address');
		// This displays the pricelist MUST be done with tables, because it is also used for the emails
		echo $this->loadTemplate ('pricelist');

		if (!empty($this->checkoutAdvertise)) {
			?> <div id="checkout-advertise-box"> <?php
			foreach ($this->checkoutAdvertise as $checkoutAdvertise) {
				?>
				<div class="checkout-advertise">
					<?php echo $checkoutAdvertise; ?>
				</div>
			<?php
			}
			?></div><?php
		}

//		echo $this->loadTemplate ('cartfields');
		?>
		<div class="checkout-button-top">
			<?php
				echo $this->checkout_link_html;
			?>
		</div>

		<?php // Continue and Checkout Button END ?>
		<input type='hidden' name='order_language' value='<?php echo $this->order_language; ?>'/>
		<input type='hidden' name='task' value='updatecart'/>
		<input type='hidden' name='option' value='com_virtuemart'/>
		<input type='hidden' name='view' value='cart'/>
</form>
</div>

<?php

if(VmConfig::get('oncheckout_ajax',false)){
	vmJsApi::addJScript('updDynamicListeners',"
if (typeof Virtuemart.containerSelector === 'undefined') Virtuemart.containerSelector = '#cart-view';
if (typeof Virtuemart.container === 'undefined') Virtuemart.container = jQuery(Virtuemart.containerSelector);

jQuery(document).ready(function() {
	if (Virtuemart.container)
		Virtuemart.updDynFormListeners();
}); ");
}


vmJsApi::addJScript('vm.checkoutFormSubmit',"
Virtuemart.bCheckoutButton = function(e) {
	e.preventDefault();
	jQuery(this).vm2front('startVmLoading');
	jQuery(this).attr('disabled', 'true');
	jQuery(this).removeClass( 'vm-button-correct' );
	jQuery(this).addClass( 'vm-button' );
	jQuery(this).fadeIn( 400 );
	var name = jQuery(this).attr('name');
	var div = '<input name=\"'+name+'\" value=\"1\" type=\"hidden\">';

	jQuery('#checkoutForm').append(div);
	//Virtuemart.updForm();
	jQuery('#checkoutForm').submit();
}
jQuery(document).ready(function($) {
	jQuery(this).vm2front('stopVmLoading');
	var el = jQuery('#checkoutFormSubmit');
	el.unbind('click dblclick');
	el.on('click dblclick',Virtuemart.bCheckoutButton);
});
	");

if( !VmConfig::get('oncheckout_ajax',false)) {
	vmJsApi::addJScript('vm.STisBT',"
		jQuery(document).ready(function($) {

			if ( $('#STsameAsBTjs').is(':checked') ) {
				$('#output-shipto-display').hide();
			} else {
				$('#output-shipto-display').show();
			}
			$('#STsameAsBTjs').click(function(event) {
				if($(this).is(':checked')){
					$('#STsameAsBT').val('1') ;
					$('#output-shipto-display').hide();
				} else {
					$('#STsameAsBT').val('0') ;
					$('#output-shipto-display').show();
				}
				var form = jQuery('#checkoutFormSubmit');
				form.submit();
			});
		});
	");
}

$this->addCheckRequiredJs();
?><div style="display:none;" id="cart-js">
<?php echo vmJsApi::writeJS(); ?>
</div>

<script>
	jQuery( "#checkoutFormSubmit" ).addClass( " btn btn-primary btn-lg" );
	jQuery( "#checkoutFormSubmit" ).css( "width", "100%" );
jQuery(function($){
	$(document).ready(function(){
	$( "#checkoutFormSubmit" ).addClass( " btn btn-primary btn-lg" );
	$( "#checkoutFormSubmit" ).css( "width", "100%" );
	});
});
</script>
