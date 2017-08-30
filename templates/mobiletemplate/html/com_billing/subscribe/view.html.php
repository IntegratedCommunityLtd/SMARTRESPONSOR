<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
//define('DS', DIRECTORY_SEPARATOR);
jimport( 'joomla.application.component.view');
require_once( JPATH_COMPONENT.DS.'billing15.php' );

/**
 * HTML View class for the Billing Component
 */

class BillingViewSubscribe extends JView
{
	function display($tpl = null)
	{
	}
}