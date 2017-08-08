<?php
defined('_JEXEC') or die('');
if ($this->display_title) {
	echo "<h3>".vmText::_('COM_VIRTUEMART_CART_ORDERDONE_THANK_YOU')."</h3>";
}
	echo $this->html;

