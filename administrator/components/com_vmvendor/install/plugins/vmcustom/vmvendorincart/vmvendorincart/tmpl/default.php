<?php
defined('_JEXEC') or die();
$class='vmcustom-vmvendorincart';
$product = $viewData[0];
$params = $viewData[1];
$name = 'customProductData['.$product->virtuemart_product_id.']['.$params->virtuemart_custom_id.']['.$params->virtuemart_customfield_id .']';
?>
<input class="<?php echo $class ?>"  type="hidden" value=""  name="<?php echo $name?>" >