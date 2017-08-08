<?php
/**
 * SMFAQ
 *
 * @package		Component for Joomla 2.5.6+
 * @version		1.7.3
 * @copyright	(C)2009 - 2013 by SmokerMan (http://joomla-code.ru)
 * @license		GNU/GPL v.3 see http://www.gnu.org/licenses/gpl.html
 */

// защита от прямого доступа
defined('_JEXEC') or die('@-_-@');

jimport( 'joomla.application.component.view');

class SmfaqViewUnpublished extends JViewLegacy
{
	protected $items;
	
	function display( $tpl = null )
	{

		$items		= $this->get('Items');
		
		$this->assignRef('items',		$items);

		parent::display($tpl);
	}
	
}
