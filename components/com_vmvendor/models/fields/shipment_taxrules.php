<?php 
/*
 * @component com_vmvendor
 * @copyright Copyright (C) 2010-2016 Adrien ROUSSEL Nordmograph.com
 * @license GNU/GPL Version 3
 * @Website : https://www.nordmograph.com/extensions
 */

// used in the editshipment form
 defined('JPATH_BASE') or die;   
 if (!class_exists( 'VmConfig' ))
 	require JPATH_ROOT.'/administrator/components/com_virtuemart/helpers/config.php';
if (!class_exists('ShopFunctions'))
	require VMPATH_ADMIN .'/helpers/shopfunctions.php';

 class JFormFieldShipmenttaxrules extends JFormField
 { 
    public $type = 'shipment_taxrules'; 
	protected function getInput()
	{
    return ShopFunctions::renderTaxList($this->value, $this->name, '');
	}
} 
?> 