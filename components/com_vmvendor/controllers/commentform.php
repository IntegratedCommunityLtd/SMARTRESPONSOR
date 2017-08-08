<?php

/**
 * @package     com_vmvendor
 * @copyright   Copyright (C) 2016. All rights reserved.
 * @license     GNU General Public License version 3; see LICENSE.txt
 * @author      Nordmograph <contact@nordmograph.com> - https://www.nordmograph.com/extensions
 */
// No direct access
defined('_JEXEC') or die;

require_once JPATH_COMPONENT . '/controller.php';
class VmvendorControllerCommentForm extends VmvendorController
{
    public function edit() {
        $app = JFactory::getApplication();

        // Get the previous edit id (if any) and the current edit id.
        $previousId = (int) $app->getUserState('com_vmvendor.edit.comment.id');
        $editId = $app->input->getInt('id', null, 'array');

        // Set the user id for the user to edit in the session.
        $app->setUserState('com_vmvendor.edit.comment.id', $editId);

        // Get the model.
        $model = $this->getModel('CommentForm', 'VmvendorModel');

        // Check out the item
        if ($editId) {
            $model->checkout($editId);
        }

        // Check in the previous user.
        if ($previousId) {
            $model->checkin($previousId);
        }

        // Redirect to the edit screen.
        $this->setRedirect(JRoute::_('index.php?option=com_vmvendor&view=commentform&layout=edit', false));
    }


    /**
     * Method to save a user's profile data.
     *
     * @return	void
     * @since	1.6
     */
    public function save() {
        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Initialise variables.
        $app = JFactory::getApplication();
        $model = $this->getModel('CommentForm', 'VmvendorModel');

        // Get the user data.
        $data = JFactory::getApplication()->input->get('jform', array(), 'array');

        // Validate the posted data.
        $form = $model->getForm();
        if (!$form) {
            $app->enqueueMessage(  $model->getError(),'error');
            return false;
        }

        // Validate the posted data.
        $data = $model->validate($form, $data);

        // Check for errors.
        if ($data === false) {
            // Get the validation messages.
            $errors = $model->getErrors();

            // Push up to three validation messages out to the user.
            for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
                if ($errors[$i] instanceof Exception) {
                    $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
                } else {
                    $app->enqueueMessage($errors[$i], 'warning');
                }
            }

            $input = $app->input;
            $jform = $input->get('jform', array(), 'ARRAY');

            // Save the data in the session.
            $app->setUserState('com_vmvendor.edit.comment.data', $jform, array());

            // Redirect back to the edit screen.
            $id = (int) $app->getUserState('com_vmvendor.edit.comment.id');
            $this->setRedirect(JRoute::_('index.php?option=com_vmvendor&view=commentform&layout=edit&id=' . $id, false));
            return false;
        }

        // Attempt to save the data.
        $return = $model->save($data);

        // Check for errors.
        if ($return === false) {
            // Save the data in the session.
            $app->setUserState('com_vmvendor.edit.comment.data', $data);

            // Redirect back to the edit screen.
            $id = (int) $app->getUserState('com_vmvendor.edit.comment.id');
            $this->setMessage(JText::sprintf('Save failed', $model->getError()), 'warning');
            $this->setRedirect(JRoute::_('index.php?option=com_vmvendor&view=commentform&layout=edit&id=' . $id, false));
            return false;
        }


        // Check in the profile.
        if ($return) {
            $model->checkin($return);
        }

        // Clear the profile id from the session.
        $app->setUserState('com_vmvendor.edit.comment.id', null);

        // Redirect to the list screen.
        $this->setMessage(JText::_('COM_VMVENDOR_ITEM_SAVED_SUCCESSFULLY'));
        $menu = JFactory::getApplication()->getMenu();
        $item = $menu->getActive();
        $url = (empty($item->link) ? 'index.php?option=com_vmvendor&view=vendorprofile' : $item->link);
        $this->setRedirect(JRoute::_($url, false));
		
		
		
		
		

        // Flush the data from the session.
        $app->setUserState('com_vmvendor.edit.comment.data', null);
		 $app->redirect( JRoute::_('index.php?option=com_vmvendor&view=vendorprofile&userid='.$data['vendor_userid'].'&Itemid='.$app->input->getInt('Itemid'), false) );
    }










    function cancel() {
        $app = JFactory::getApplication();
        $editId = (int) $app->getUserState('com_vmvendor.edit.comment.id');
        $model = $this->getModel('CommentForm', 'VmvendorModel');
        if ($editId) {
            $model->checkin($editId);
        }
        $menu = JFactory::getApplication()->getMenu();
        $item = $menu->getActive();
        $url = (empty($item->link) ? 'index.php?option=com_vmvendor&view=comments' : $item->link);
        $this->setRedirect(JRoute::_($url, false));
    }









    public function remove() {

        // Initialise variables.
        $app = JFactory::getApplication();
        $model = $this->getModel('CommentForm', 'VmvendorModel');

        // Get the user data.
        $data = array();
        $data['id'] = $app->input->getInt('id');

        // Check for errors.
        if (empty($data['id'])) {
            // Get the validation messages.
            $errors = $model->getErrors();

            // Push up to three validation messages out to the user.
            for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
                if ($errors[$i] instanceof Exception) {
                    $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
                } else {
                    $app->enqueueMessage($errors[$i], 'warning');
                }
            }

            // Save the data in the session.
            $app->setUserState('com_vmvendor.edit.comment.data', $data);

            // Redirect back to the edit screen.
            $id = (int) $app->getUserState('com_vmvendor.edit.comment.id');
            $this->setRedirect(JRoute::_('index.php?option=com_vmvendor&view=comment&layout=edit&id=' . $id, false));
            return false;
        }

        // Attempt to save the data.
        $return = $model->delete($data);

        // Check for errors.
        if ($return === false) {
            // Save the data in the session.
            $app->setUserState('com_vmvendor.edit.comment.data', $data);

            // Redirect back to the edit screen.
            $id = (int) $app->getUserState('com_vmvendor.edit.comment.id');
            $this->setMessage(JText::sprintf('Delete failed', $model->getError()), 'warning');
            $this->setRedirect(JRoute::_('index.php?option=com_vmvendor&view=comment&layout=edit&id=' . $id, false));
            return false;
        }


        // Check in the profile.
        if ($return) {
            $model->checkin($return);
        }

        // Clear the profile id from the session.
        $app->setUserState('com_vmvendor.edit.comment.id', null);

        // Redirect to the list screen.
        $this->setMessage(JText::_('COM_VMVENDOR_ITEM_DELETED_SUCCESSFULLY'));
        $menu = JFactory::getApplication()->getMenu();
        $item = $menu->getActive();
        $url = (empty($item->link) ? 'index.php?option=com_vmvendor&view=comments' : $item->link);
        $this->setRedirect(JRoute::_($url, false));

        // Flush the data from the session.
        $app->setUserState('com_vmvendor.edit.comment.data', null);
    }

	
	public function getVMVprofileItemid()
	{
		$db 	= JFactory::getDBO();
		$lang 	= JFactory::getLanguage();
		$q = "SELECT `id` FROM `#__menu` WHERE `link` ='index.php?option=com_vmvendor&view=vendorprofile' AND ( language ='".$lang->getTag()."' OR language='*') AND published='1' AND access='1' ";
		$db->setQuery($q);
		return $vmvendoritemid = $db->loadResult();	
	}
}