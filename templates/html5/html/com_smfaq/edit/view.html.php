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

jimport( 'joomla.application.component.view' );

class SmfaqViewEdit extends JViewLegacy
{
	protected $state;
	protected $item;
	protected $form;
	protected $comments;

	public function display($tpl = null)
	{
		// данные из модели
		$this->state	= $this->get('State');
		$this->form		= $this->get('Form');
		$this->comments	= $this->get('Comments');
		
		// запрет прямого просмотра для пользователей
		$user		= JFactory::getUser();
		$catid = $this->form->getValue('catid');
		
		if (!$catid) {
			$catid = JFactory::getApplication()->input->get('catid', null, 'int');
		}
		
		$authorised = $user->authorise('core.edit', 'com_smfaq.category.'.$catid);
		
		if (($authorised !== true) || !$catid) {
			JError::raiseError(403, JText::_("JERROR_ALERTNOAUTHOR"));
			return false;
		}
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}
		
		
		// установка значений для формы
		if ($this->form->getValue('user_id')) {
			$this->form->setFieldAttribute('created_by', 'readonly', 'true');
			$this->form->setFieldAttribute('created_by_email', 'readonly', 'true');
		}
		if (!$this->form->getValue('answer_email')) {
			$this->form->setFieldAttribute('answer_email', 'disabled', 'true');
		}
		
		$this->form->setFieldAttribute('answer', 'buttons', 'false');
		
		$this->form->removeField('answer_created_by_id');


		
		$baseurl = $this->document->baseurl;
		
		$this->document->addStyleSheet( $baseurl . 'components/com_smfaq/css/smfaq_edit.css' );

		require_once $baseurl . 'components/com_smfaq/libraries/calendar/calendar.php';
		SmfaqHelperCalendar::setup();
		$this->document->addScript( $baseurl . 'components/com_smfaq/libraries/calendar/js/jscal2.js');		
		$this->document->addStyleSheet( $baseurl . 'components/com_smfaq/libraries/calendar/css/jscal2.css');
		$this->document->addStyleSheet( $baseurl . 'components/com_smfaq/libraries/calendar/css/gold/gold.css');
		
		$url = JURI::getInstance();
		$this->assignRef('url', $url);

		
		parent::display($tpl);
	}

}