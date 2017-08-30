<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @since		1.5
 */

defined('_JEXEC') or die;
?>
					<?php
					// Возврат модуля Ленты Активности
						$modules =JModuleHelper::getModules('signout-page');
							foreach ($modules as $module){
								echo JModuleHelper::renderModule($module);
							}
					?>