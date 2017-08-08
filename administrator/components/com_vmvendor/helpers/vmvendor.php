<?php

/**
 * @version     1.0.0
 * @package     com_vmvendor
 * @copyright   Copyright (C) 2016. All rights rserved
 * @license     GNU General Public License version 3 ; see LICENSE.txt
 * @author      Nordmograph <contact@nordmograph.com> - https://www.nordmograph.com/extensions
 */
// No direct access
defined('_JEXEC') or die;

/**
 * Vmvendor helper.
 */
class VmvendorHelper {

    /**
     * Configure the Linkbar.
     */
    public static function addSubmenu($vName = '') {
        		JHtmlSidebar::addEntry(
			JText::_('COM_VMVENDOR_TITLE_PLANS'),
			'index.php?option=com_vmvendor&view=plans',
			$vName == 'plans'
		);
		
		JHtmlSidebar::addEntry(
			JText::_('COM_VMVENDOR_TITLE_POINTSACTIVITIES'),
			'index.php?option=com_vmvendor&view=pointsactivities',
			$vName == 'pointsactivities'
		);


    }

    /**
     * Gets a list of the actions that can be performed.
     *
     * @return	JObject
     * @since	1.6
     */
    public static function getActions() {
        $user = JFactory::getUser();
        $result = new JObject;

        $assetName = 'com_vmvendor';

        $actions = array(
            'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
        );

        foreach ($actions as $action) {
            $result->set($action, $user->authorise($action, $assetName));
        }

        return $result;
    }

	/**
	* Get group name using group ID
	* @param integer $group_id Usergroup ID
	* @return mixed group name if the group was found, null otherwise
	*/
	public static function getGroupNameByGroupId($group_id) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select('title')
			->from('#__usergroups')
			->where('id = ' . intval($group_id));

		$db->setQuery($query);
		return $db->loadResult();
	}
	
	public static function recountPoints($uid)
	{
		if(!$uid)
			return ;
		$db 	= JFactory::getDBO();
		$app 	= JFactory::getApplication();
		$q = "SELECT SUM(points) FROM #__vmvendor_userpoints_details 
		WHERE userid='".$uid."' AND status='1' AND approved='1'  
		AND (expire_date > NOW() OR expire_date='0000-00-00 00:00:00') ";
		$db->setQuery($q);
		$nu_pts = $db->loadResult();
		$q = "SELECT userid, points FROM #__vmvendor_userpoints WHERE userid='".$uid."' ";
		$db->setQuery($q);
		$d = $db->loadObject();
		$ol_pts = $d->points;
		if( count($d)<1 && !$ol_pts && $nu_pts!=0)
		{	
			$q = "INSERT INTO #__vmvendor_userpoints (userid,points) VALUES ('".$uid."','".$nu_pts."') ";
			$db->setQuery($q);
			if (!$db->query()) die($db->stderr(true));
			$app->enqueueMessage( JText::_('COM_VMVENDOR_PTS_RECOUNT_SUCCESS') .$nu_pts , 'message');
		}
		elseif( $nu_pts!=$ol_pts)
		{
			$q = "UPDATE #__vmvendor_userpoints SET points='".$nu_pts."' WHERE userid='".$uid."' ";
			$db->setQuery($q);
			if (!$db->query()) die($db->stderr(true));
			$app->enqueueMessage( JText::_('COM_VMVENDOR_PTS_RECOUNT_SUCCESS') .$nu_pts , 'message');
		}
		
	}
}
