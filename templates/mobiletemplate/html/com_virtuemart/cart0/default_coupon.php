<?php
defined('_JEXEC') or die('Restricted access');

if ($this->layoutName!='default') {
?>
<form method="post" role="form" id="userForm" name="enterCouponCode" action="<?php echo JRoute::_('index.php'); ?>">
<?php } ?>
<div class="input-group">
    <input type="text" name="coupon_code" size="20" maxlength="50" class="coupon form-control nomargin" alt="<?php echo $this->coupon_text ?>" placeholder="<?php echo $this->coupon_text ?>" value="<?php //echo $this->coupon_text; ?>" onblur="if(this.value=='') this.value='<?php echo $this->coupon_text; ?>';" onfocus="if(this.value=='<?php echo $this->coupon_text; ?>') this.value='';" />
	
	<span class="input-group-btn">
		<input class="btn btn-primary details-button" type="submit" name="setcoupon" title="<?php echo vmText::_('COM_VIRTUEMART_SAVE'); ?>" value="<?php echo vmText::_('COM_VIRTUEMART_SAVE'); ?>"/>
	</span>
</div>
<?php
if ($this->layoutName!='default') {
?>
    <input type="hidden" name="option" value="com_virtuemart" />
    <input type="hidden" name="view" value="cart" />
    <input type="hidden" name="task" value="setcoupon" />
    <input type="hidden" name="controller" value="cart" />
</form>
<?php } ?>