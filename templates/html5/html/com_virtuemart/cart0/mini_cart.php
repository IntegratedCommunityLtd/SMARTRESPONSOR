<?php
defined('_JEXEC') or die('Restricted access');

?>
	<a href="<?php echo $this->continue_link; ?>"><?php echo vmText::_('COM_VIRTUEMART_CONTINUE_SHOPPING') ?></a>
	<a style ="float:right;" href="<?php echo JRoute::_('index.php?option=com_ajax&option=com_virtuemart&view=cart'); ?>"><?php echo vmText::_('COM_VIRTUEMART_CART_SHOW') ?></a>
<br style="clear:both">
