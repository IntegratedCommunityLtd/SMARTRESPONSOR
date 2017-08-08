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
 /** 
  * Supports an HTML select list of options driven by SQL 
  */ 
 class JFormFieldWeight_unit extends JFormField
 { 
     /** 
      * The form field type. 
      */ 
    public $type = 'weight_unit'; 
	protected function getInput()
	{
		return ShopFunctions::renderWeightUnitList($this->name, $this->value);
	}
} 
?> 