<?php
defined ('_JEXEC') or die('Restricted access');
?>
<?php
if($viewData['orderable']) {
	echo '<button type="submit" name="addtocart" class="btn btn-info addtocart-button" value="'.vmText::_( 'COM_VIRTUEMART_CART_ADD_TO' ).'" style="width: 100%;-webkit-border-radius: 0 0 3px 3px;-moz-border-radius: 0 0 3px 3px;border-radius: 0 0 3px 3px" title="'.vmText::_( 'COM_VIRTUEMART_CART_ADD_TO' ).'" />
	<span class="glyphicon glyphicon-heart"> </span><span class="">'.vmText::_( 'COM_VIRTUEMART_CART_ADD_TO' ).'</span></button>';
}

