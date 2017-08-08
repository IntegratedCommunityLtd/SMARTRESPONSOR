<?php

/**
 * @package     com_vmvendor
 * @copyright   Copyright (C) 2016. All rights reserved.
 * @license     GNU General Public License version 3; see LICENSE.txt
 * @author      Nordmograph <contact@nordmograph.com> - https://www.nordmograph.com/extensions
 */
defined('_JEXEC') or die;
jimport('joomla.application.component.modellist');
class VmvendorModelComments extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				                'id', 'a.id',
                'parent', 'a.parent',
                'vendor_userid', 'a.vendor_userid',
                'title', 'a.title',
                'comment', 'a.comment',
                'lang', 'a.lang',
                'created_by', 'a.created_by',
                'created_on', 'a.created_on',
                'ordering', 'a.ordering',
                'reports', 'a.reports',
                'state', 'a.state',

			);
		}
		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since    1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{


		// Initialise variables.
		$app = JFactory::getApplication();

		// List state information
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
		$this->setState('list.limit', $limit);

		$limitstart = $app->input->getInt('limitstart', 0);
		$this->setState('list.start', $limitstart);

		if ($list = $app->getUserStateFromRequest($this->context . '.list', 'list', array(), 'array'))
		{
			foreach ($list as $name => $value)
			{
				// Extra validations
				switch ($name)
				{
					case 'fullordering':
						$orderingParts = explode(' ', $value);

						if (count($orderingParts) >= 2)
						{
							// Latest part will be considered the direction
							$fullDirection = end($orderingParts);

							if (in_array(strtoupper($fullDirection), array('ASC', 'DESC', '')))
							{
								$this->setState('list.direction', $fullDirection);
							}

							unset($orderingParts[count($orderingParts) - 1]);

							// The rest will be the ordering
							$fullOrdering = implode(' ', $orderingParts);

							if (in_array($fullOrdering, $this->filter_fields))
							{
								$this->setState('list.ordering', $fullOrdering);
							}
						}
						else
						{
							$this->setState('list.ordering', $ordering);
							$this->setState('list.direction', $direction);
						}
						break;

					case 'ordering':
						if (!in_array($value, $this->filter_fields))
						{
							$value = $ordering;
						}
						break;

					case 'direction':
						if (!in_array(strtoupper($value), array('ASC', 'DESC', '')))
						{
							$value = $direction;
						}
						break;

					case 'limit':
						$limit = $value;
						break;

					// Just to keep the default case
					default:
						$value = $value;
						break;
				}

				$this->setState('list.' . $name, $value);
			}
		}

		// Receive & set filters
		if ($filters = $app->getUserStateFromRequest($this->context . '.filter', 'filter', array(), 'array'))
		{
			foreach ($filters as $name => $value)
			{
				$this->setState('filter.' . $name, $value);
			}
		}

		$ordering = $app->input->get('filter_order');
		if (!empty($ordering))
		{
			$list             = $app->getUserState($this->context . '.list');
			$list['ordering'] = $app->input->get('filter_order');
			$app->setUserState($this->context . '.list', $list);
		}

		$orderingDirection = $app->input->get('filter_order_Dir');
		if (!empty($orderingDirection))
		{
			$list              = $app->getUserState($this->context . '.list');
			$list['direction'] = $app->input->get('filter_order_Dir');
			$app->setUserState($this->context . '.list', $list);
		}

		$list = $app->getUserState($this->context . '.list');

		if (empty($list['ordering']))
{
	$list['ordering'] = 'ordering';
}

if (empty($list['direction']))
{
	$list['direction'] = 'asc';
}

		$this->setState('list.ordering', $list['ordering']);
		$this->setState('list.direction', $list['direction']);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return    JDatabaseQuery
	 * @since    1.6
	 */
	protected function getListQuery()
	{
		$app = JFactory::getApplication();
		$vendoruserid = $app->input->getInt('vendoruserid');
		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query
			->select(
				$this->getState(
					'list.select', 'DISTINCT a.*'
				)
			);
		$query->select('uc.username');
		$query->from('`#__vmvendor_comments` AS a');

		$query->join('', '#__users AS uc ON uc.id=a.created_by');
    // Join over the users for the checked out user.
//    $query->select('uc.name AS editor');
 //   
    
		// Join over the created by field 'created_by'
	//	$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');

		
		if (!JFactory::getUser()->authorise('core.edit.state', 'com_vmvendor'))
		{
			//$query->where('a.state = 1');
		}

		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
				$query->where('( a.title LIKE '.$search.' )');
			}
		}

		
		$query->where(' a.vendor_userid ='.$vendoruserid.' ');
		
		//vendor_userid='".$uid."'
		// Add the list ordering clause.
		//$orderCol  = $this->state->get('list.ordering');
		$orderCol  = 'id';
		//$orderDirn = $this->state->get('list.direction');
		$orderDirn = 'DESC';
		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}
		return $query;
	}

	public function getItems()
	{
		$items = parent::getItems();
		

		return $items;
	}

	/**
	 * Overrides the default function to check Date fields format, identified by
	 * "_dateformat" suffix, and erases the field if it's not correct.
	 */
	protected function loadFormData()
	{
		$app              = JFactory::getApplication();
		$filters          = $app->getUserState($this->context . '.filter', array());
		$error_dateformat = false;
		foreach ($filters as $key => $value)
		{
			if (strpos($key, '_dateformat') && !empty($value) && !$this->isValidDate($value))
			{
				$filters[$key]    = '';
				$error_dateformat = true;
			}
		}
		if ($error_dateformat)
		{
			$app->enqueueMessage(JText::_("COM_VMVENDOR_SEARCH_FILTER_DATE_FORMAT"), "warning");
			$app->setUserState($this->context . '.filter', $filters);
		}

		return parent::loadFormData();
	}

	/**
	 * Checks if a given date is valid and in an specified format (YYYY-MM-DD)
	 *
	 * @param string Contains the date to be checked
	 *
	 */
	private function isValidDate($date)
	{
		return preg_match("/^(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/", $date) && date_create($date);
	}
	
	static function getIsCustomer()
	{
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		$vendor_userid = $app->input->get->getInt('vendoruserid');
		$q = " SELECT voi.virtuemart_product_id 
			FROM #__virtuemart_order_items voi 
			LEFT JOIN #__virtuemart_products vp ON vp.virtuemart_product_id = voi.virtuemart_product_id 
			LEFT JOIN #__virtuemart_vmusers vv ON vv.virtuemart_vendor_id = vp.virtuemart_vendor_id 
			WHERE voi.created_by = '".$user->id."' 
			AND ( voi.order_status ='C' OR  voi.order_status ='S') 
			AND vv.virtuemart_user_id='".$vendor_userid."' ";
		$db->setQuery($q);
		$purchases_from_thisvendor = $db->loadObjectList();
		//return count($purchases_from_thisvendor);
		return 1;
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
	
	function getVendorprofileItemid()
	{
		$lang = JFactory::getLanguage();
		$db 	= JFactory::getDBO();
		$q = "SELECT `id` FROM `#__menu` WHERE `link`='index.php?option=com_vmvendor&view=vendorprofile' 
		AND `type`='component'  AND ( language ='".$lang->getTag()."' OR language='*') 
		AND published='1'  AND access='1' ";
		$db->setQuery($q);
		return $profile_itemid = $db->loadResult();
	
	}
}
