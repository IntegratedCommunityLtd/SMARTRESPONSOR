<?php
defined('_JEXEC') or die('Restricted access');

echo '<p>' . $this->cart->getError() . '</p>';
echo '<a class="continue" href="' . $this->continue_link . '" >' . vmText::_('COM_VIRTUEMART_CONTINUE_SHOPPING') . '</a>';
if(!empty($this->errorMsg)){
	echo '<div>'.$this->errorMsg.'</div>';
}

?>
<br style="clear:both">
