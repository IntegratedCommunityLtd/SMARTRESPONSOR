<?php
/**
 * @package     com_vmvendor
 * @copyright   Copyright (C) 2016. All rights reserved.
 * @license     GNU General Public License version 3; see LICENSE.txt
 * @author      Nordmograph <contact@nordmograph.com> - https://www.nordmograph.com/extensions
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modelform');
jimport('joomla.event.dispatcher');

/**
 * Vmvendor model.
 */
class VmvendorModelCommentForm extends JModelForm
{
    
    var $_item = null;
    
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication('com_vmvendor');

		// Load state from the request userState on edit or from the passed variable on default
        if (JFactory::getApplication()->input->get('layout') == 'edit') {
            $id = JFactory::getApplication()->getUserState('com_vmvendor.edit.comment.id');
        } else {
            $id = JFactory::getApplication()->input->get('id');
            JFactory::getApplication()->setUserState('com_vmvendor.edit.comment.id', $id);
        }
		$this->setState('comment.id', $id);

		// Load the parameters.
        $params = $app->getParams();
        $params_array = $params->toArray();
        if(isset($params_array['item_id'])){
            $this->setState('comment.id', $params_array['item_id']);
        }
		$this->setState('params', $params);

	}
        

	/**
	 * Method to get an ojbect.
	 *
	 * @param	integer	The id of the object to get.
	 *
	 * @return	mixed	Object on success, false on failure.
	 */
	public function &getData($id = null)
	{
		if ($this->_item === null)
		{
			$this->_item = false;

			if (empty($id)) {
				$id = $this->getState('comment.id');
			}

			// Get a level row instance.
			$table = $this->getTable();

			// Attempt to load the row.
			if ($table->load($id))
			{
                
                $user = JFactory::getUser();
                $id = $table->id;
      
                
				// Check published state.
				if ($published = $this->getState('filter.published'))
				{
					if ($table->state != $published) {
						return $this->_item;
					}
				}

				// Convert the JTable to a clean JObject.
				$properties = $table->getProperties(1);
				$this->_item = JArrayHelper::toObject($properties, 'JObject');
			} elseif ($error = $table->getError()) {
				$this->setError($error);
			}
		}

		return $this->_item;
	}
    
	public function getTable($type = 'Comment', $prefix = 'VmvendorTable', $config = array())
	{   
        $this->addTablePath(JPATH_COMPONENT_ADMINISTRATOR.'/tables');
        return JTable::getInstance($type, $prefix, $config);
	}     

    
	/**
	 * Method to check in an item.
	 *
	 * @param	integer		The id of the row to check out.
	 * @return	boolean		True on success, false on failure.
	 * @since	1.6
	 */
	public function checkin($id = null)
	{
		// Get the id.
		$id = (!empty($id)) ? $id : (int)$this->getState('comment.id');

		if ($id) {
            
			// Initialise the table
			$table = $this->getTable();

			// Attempt to check the row in.
            if (method_exists($table, 'checkin')) {
                if (!$table->checkin($id)) {
                    $this->setError($table->getError());
                    return false;
                }
            }
		}

		return true;
	}

	/**
	 * Method to check out an item for editing.
	 *
	 * @param	integer		The id of the row to check out.
	 * @return	boolean		True on success, false on failure.
	 * @since	1.6
	 */
	public function checkout($id = null)
	{
		// Get the user id.
		$id = (!empty($id)) ? $id : (int)$this->getState('comment.id');

		if ($id) {
            
			// Initialise the table
			$table = $this->getTable();

			// Get the current user object.
			$user = JFactory::getUser();

			// Attempt to check the row out.
            if (method_exists($table, 'checkout')) {
                if (!$table->checkout($user->get('id'), $id)) {
                    $this->setError($table->getError());
                    return false;
                }
            }
		}

		return true;
	}    
    
	/**
	 * Method to get the profile form.
	 *
	 * The base form is loaded from XML 
     * 
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_vmvendor.comment', 'commentform', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_vmvendor.edit.comment.data', array());
        if (empty($data)) {
            $data = $this->getData();
        }
        
        return $data;
	}

	/**
	 * Method to save the form data.
	 *
	 * @param	array		The form data.
	 * @return	mixed		The user id on success, false on failure.
	 * @since	1.6
	 */
	public function save($data)
	{
		$id = (!empty($data['id'])) ? $data['id'] : (int)$this->getState('comment.id');
        $user = JFactory::getUser();

		$data['state'] = 1;
        /*if ($authorised !== true) {
            JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
            return false;
        }*/
        
        $table = $this->getTable();
        if ($table->save($data) === true) {
            return $table->id;
        } else {
            return false;
        }
       
	}
    
     function delete($data)
    {
       $app = JFactory::getApplication();
	  // $app->enqueueMessage('testok'.$data['id'], 'message');
	    $id = (!empty($data['id'])) ? $data['id'] : (int)$this->getState('comment.id');
        if(JFactory::getUser()->authorise('core.delete', 'com_vmvendor') !== true){
            $app->enqueueMessage(  JText::_('JERROR_ALERTNOAUTHOR'),'error');
            return false;
        }
        $table = $this->getTable();
        if ($table->delete($data['id']) === true) {
            return $id;
        } else {
            return false;
        }
        
        return true;
    }
	
    static function isCustomer($vendor_uid )
	{
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$q = " SELECT voi.virtuemart_product_id 
			FROM #__virtuemart_order_items voi 
			LEFT JOIN #__virtuemart_products vp ON vp.virtuemart_product_id = voi.virtuemart_product_id 
			LEFT JOIN #__virtuemart_vmusers vv ON vv.virtuemart_vendor_id = vp.virtuemart_vendor_id 
			WHERE voi.created_by = '".$user->id."' 
			AND ( voi.order_status ='C' OR  voi.order_status ='S') 
			AND vv.virtuemart_user_id='".$vendor_uid."' ";
		$db->setQuery($q);
		$purchases_from_thisvendor = $db->loadObjectList();
		return count($purchases_from_thisvendor);
	}
	
	static function getMyleftreviewsCount()
	{
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$q = " SELECT COUNT(*) 
			FROM #__vmvendor_comments WHERE created_by='".$user->id."' ";
		$db->setQuery($q);
		$count = $db->loadResult();
		return $count;
	}
	public function getStoreName()
	{
		$db = JFactory::getDBO();
		$app = JFactory::getApplication();
		$vendor_userid = $app->input->get->getInt('vendoruserid');
		if (!class_exists( 'VmConfig' ))
			require JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php';
		VmConfig::loadConfig();
		$q = " SELECT vvl.vendor_store_name  
			FROM #__virtuemart_vendors_".VMLANG." vvl 
			JOIN #__virtuemart_vendors vv ON vv.virtuemart_vendor_id= vvl.virtuemart_vendor_id 
			JOIN #__virtuemart_vmusers vvu ON vvu.virtuemart_vendor_id= vvl.virtuemart_vendor_id
			WHERE vvu.virtuemart_user_id='".$vendor_userid."' ";
		$db->setQuery($q);
		$name = $db->loadResult();
		return $name;
	}
}