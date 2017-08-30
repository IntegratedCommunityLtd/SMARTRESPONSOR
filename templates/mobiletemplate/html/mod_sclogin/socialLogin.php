<?php
defined('_JEXEC') or die('Restricted access');

if (!class_exists('JFBCFactory'))
    return;
	$loginButtons = $helper->getLoginButtons($orientation, $alignment);
	echo $loginButtons;