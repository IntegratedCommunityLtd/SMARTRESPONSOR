<?php
/* @component com_vmvendor
 * @copyright Copyright (C) 2008-2016 Adrien ROUSSEL Nordmograph.com
 * @license GNU/GPL Version 3
 * @Website : https://www.nordmograph.com/extensions
 */
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.view');
class VMVendorViewVendormarkers extends JViewLegacy
{
	function display($tpl = null)
	{
		$this->vendormarkers = json_encode($this->get('Vendormarkers') );
		parent::display($tpl);
	}
}