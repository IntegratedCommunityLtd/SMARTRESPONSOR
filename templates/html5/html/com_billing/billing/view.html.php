<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
//define('DS', DIRECTORY_SEPARATOR);

jimport( 'joomla.application.component.view');
require_once( JPATH_COMPONENT.'/billing15.php' );

if(!class_exists('JViewLegacy'))
{
	class JViewLegacy extends JView
	{
	}
}

/**
 * HTML View class for the Billing Component
 */
	class BillingViewBilling extends JViewLegacy
	{
		function display($tpl = null)
		{
			$task = JRequest::getCmd('task');
			if ($task == '')
			{
				$tabno = JRequest::getInt('tabno');
				$msg = JRequest::getVar('msg');
				Cabinet($tabno, $msg);
			}
		}
	}

