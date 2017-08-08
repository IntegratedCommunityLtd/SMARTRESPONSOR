<?php
/**
 * @version     1.0.0
 * @package     com_vmvendor
 * @copyright   Copyright (C) 2016. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Nordmograph <contact@nordmograph.com> - https://www.nordmograph.com/extensions
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Comment controller class.
 */
class VmvendorControllerComment extends JControllerForm
{

    function __construct() {
        $this->view_list = 'comments';
        parent::__construct();
    }

}