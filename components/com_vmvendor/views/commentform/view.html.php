<?php

/**
 * @package     com_vmvendor
 * @copyright   Copyright (C) 2016. All rights reserved.
 * @license     GNU General Public License version 3; see LICENSE.txt
 * @author      Nordmograph <contact@nordmograph.com> - https://www.nordmograph.com/extensions
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View to edit
 */
class VmvendorViewCommentform extends JViewLegacy {

    protected $state;
    protected $item;
    protected $form;
    protected $params;

    /**
     * Display the view
     */
    public function display($tpl = null) {

        $app = JFactory::getApplication();
        $user = JFactory::getUser();
		$doc = JFactory::getDocument();
		$doc->addStylesheet(JURI::base().'components/com_vmvendor/assets/css/fontello.css');
		$doc->addScript(JUri::base() . '/components/com_vmvendor/assets/js/form.js');
		$vendor_uid = $app->input->get->getInt('vendoruserid');
		
		$cparams 					= JComponentHelper::getParams('com_vmvendor');
		$vmvcomment_howmany			= $cparams->get('vmvcomment_howmany', 0);
		$this->myleftreviewsCount	= $this->get('myleftreviewsCount');
		$this->storename 			= $this->get('StoreName');
		
		$this->state = $this->get('State');
        $this->item = $this->get('Data');
        $this->params = $app->getParams('com_vmvendor');
        $this->form		= $this->get('Form');
		
		
		if($vendor_uid=='' )
		{
			$app->enqueueMessage('Vendor reference missing. Aborting... 
			Contact an administrator to report this error.', 'warning');
			return false;
		}
		if(!$user->id )
		{
			$app->enqueueMessage( JText::_('COM_VMVENDOR_VMVENADD_ONLYLOGGEDIN'), 'warning');
			return false;
		}
		$this->isCustomer = VmvendorModelCommentForm::isCustomer($vendor_uid );
		if(!$this->isCustomer)
		{
			$app->enqueueMessage( JText::_('COM_VMVENDOR_PROFILERATING_NOTYET'), 'warning');
			//return false;
		}
		if($this->myleftreviewsCount >= $vmvcomment_howmany && $vmvcomment_howmany>0)
		{
			$reviews_url = JRoute::_('index.php?option=com_vmvendor&view=comments&vendoruserid='.$vendor_uid);
			$app->enqueueMessage( sprintf( JText::_('COM_VMVENDOR_MAXREVIEWS_REACHED'), $vmvcomment_howmany ), 'warning');
			$app->enqueueMessage( '<a class="btn btn-primary" href="'.$reviews_url.'"><i class="vmv-icon-comment ">
			</i> '.JText::_('COM_VMVENDOR_CUSTOMERREVIEWS').'</a>', 'warning');
			return false;
		}

        
		

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }

        

        $this->_prepareDocument();

        parent::display($tpl);
    }

    /**
     * Prepares the document
     */
    protected function _prepareDocument() {
        $app = JFactory::getApplication();
        $menus = $app->getMenu();
        $title = null;

        // Because the application sets a default page title,
        // we need to get it from the menu item itself
        $menu = $menus->getActive();
        if ($menu) {
            $this->params->def('page_heading', $this->params->get('page_title', $menu->title));
        } else {
            $this->params->def('page_heading', JText::_('COM_VMVENDOR_DEFAULT_PAGE_TITLE'));
        }
        $title = $this->params->get('page_title', '');
        if (empty($title)) {
            $title = $app->getCfg('sitename');
        } elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
            $title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
        } elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
            $title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
        }
        $this->document->setTitle($title);

        if ($this->params->get('menu-meta_description')) {
            $this->document->setDescription($this->params->get('menu-meta_description'));
        }

        if ($this->params->get('menu-meta_keywords')) {
            $this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
        }

        if ($this->params->get('robots')) {
            $this->document->setMetadata('robots', $this->params->get('robots'));
        }
    }

}
