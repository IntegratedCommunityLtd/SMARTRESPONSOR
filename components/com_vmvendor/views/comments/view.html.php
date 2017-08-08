<?php

/**
 * @package     com_vmvendor
 * @copyright   Copyright (C) 2016. All rights reserved.
 * @license     GNU General Public License version 3; see LICENSE.txt
 * @author      Nordmograph <contact@nordmograph.com> - https://www.nordmograph.com/extensions
 */
defined('_JEXEC') or die;
jimport('joomla.application.component.view');
class VmvendorViewComments extends JViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;
    protected $params;

    public function display($tpl = null)
	{
		$juri 	= JURI::base();
        $app 	= JFactory::getApplication();
		
		
		$user 	= JFactory::getUser();
		$this->vendoruserid = $app->input->getInt('vendoruserid' , $user->id);
		$doc 	= JFactory::getDocument();
		$doc->addStyleSheet($juri.'components/com_vmvendor/assets/css/fontello.css');
		$doc->addStyleSheet($juri.'components/com_vmvendor/assets/css/comments.css');
		if($user->id)
		{
			$doc->addStyleSheet($juri.'components/com_vmvendor/assets/js/sweetalert/sweetalert2.css');
			$doc->addScript($juri.'components/com_vmvendor/assets/js/sweetalert/sweetalert2.min.js');
		}
		$cparams 					= JComponentHelper::getParams('com_vmvendor');
		$vmvcomment_howmany			= $cparams->get('vmvcomment_howmany', 0);
		$this->date_display			= $cparams->get('date_display', 'Y.m.d');
		
		$this->myleftreviewsCount	= $this->get('myleftreviewsCount');
		$this->storename 			= $this->get('StoreName');
		$this->iscustomer 			= $this->get('isCustomer');

        $this->state = $this->get('State');
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->params = $app->getParams('com_vmvendor');
		
		$this->vendorprofileItemid = $this->get('VendorprofileItemid');
        
		

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
;
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