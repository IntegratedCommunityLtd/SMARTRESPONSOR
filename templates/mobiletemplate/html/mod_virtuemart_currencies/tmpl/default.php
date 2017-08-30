<?php // no direct access
defined('_JEXEC') or die('Restricted access');
vmJsApi::jQuery();
vmJsApi::chosenDropDowns();
?>

<!-- Currency Selector Module -->

<form action="<?php echo vmURI::getCurrentUrlBy('get',true) ?>" method="post">
	<div class="btn-group-vertical componenta">
		<div class="btn-toolbar" role="toolbar">
			<div class="btn-group">
<?php echo JHTML::_('select.genericlist', $currencies, 'virtuemart_currency_id', 'class="inputbox vm-chzn-select"', 'virtuemart_currency_id', 'currency_txt', $virtuemart_currency_id) ; ?>		
			</div>
		</div>
	</div>
    <input class="button" type="submit" name="submit" class="btn-group btn btn-warning" value="<?php echo vmText::_('MOD_VIRTUEMART_CURRENCIES_CHANGE_CURRENCIES') ?>" />
	
</form>




