<?php
defined('_JEXEC') or die('Restricted access');

?>

<fieldset>
	<legend>
		<?php echo vmText::_('COM_VIRTUEMART_SHOPPER_FORM_LBL') ?>
	</legend>
	<dl class="dl-horizontal">
			<?php if(Vmconfig::get('multix','none')!=='none'){ ?>
		<dt>
			<?php echo vmText::_('COM_VIRTUEMART_PRODUCT_FORM_VENDOR') ?>:
		</dt>
		<dd>
			<?php echo $this->lists['vendors']; ?>
		</dd>
			<?php } ?>

		<dt>
			<?php echo vmText::_('COM_VIRTUEMART_USER_FORM_CUSTOMER_NUMBER') ?>:
		</dt>
		<dd>
			<?php if(vmAccess::manager('user.edit')) { ?>
			<input type="text" class="form-control" name="customer_number" id="customer_number" size="40" value="<?php echo  $this->lists['custnumber']; ?>" />
			<?php } else {
			echo $this->lists['custnumber'];
			} ?>
		</dd>
			<?php if($this->lists['shoppergroups']) { ?>
		<dt>
			<?php echo vmText::_('COM_VIRTUEMART_SHOPPER_FORM_GROUP') ?>:
		</dt>
		<dd>
			<?php echo $this->lists['shoppergroups']; ?>
		</dd>
			<?php } ?>
	</dl>
</fieldset>
