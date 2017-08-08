<?php
/**
 * @package     com_vmvendor
 * @copyright   Copyright (C) 2016. All rights reserved.
 * @license     GNU General Public License version 3; see LICENSE.txt
 * @author      Nordmograph <contact@nordmograph.com> - https://www.nordmograph.com/extensions
 */

// No direct access.
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/controller.php';

/**
 * Vendors list controller class.
 */
class VmvendorControllerVendors extends VmvendorController
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function &getModel($name = 'Vendors', $prefix = 'VmvendorModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
}