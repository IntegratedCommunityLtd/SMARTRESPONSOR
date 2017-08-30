<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

//define('DS', DIRECTORY_SEPARATOR);
jimport( 'joomla.application.component.view');
require_once( JPATH_COMPONENT.DS.'billing15.php' );

/**
 * HTML View class for the Billing Component
 */

class BillingViewCabinet extends JView
{
	function display($tpl = null)
	{
		$sum = JRequest::getCmd('sum');
	}
}