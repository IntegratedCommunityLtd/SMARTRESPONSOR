<?php
/**
 * @package     com_vmvendor
 * @copyright   Copyright (C) 2016. All rights reserved.
 * @license     GNU General Public License version 3; see LICENSE.txt
 * @author      Nordmograph <contact@nordmograph.com> - https://www.nordmograph.com/extensions
 */
// No direct access.
defined('_JEXEC') or die;
require_once JPATH_COMPONENT.'/controller.php';
class VmvendorControllerComments extends VmvendorController
{
	public function &getModel($name = 'Comments', $prefix = 'VmvendorModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	public function deletereview()
	{
		$app 	= JFactory::getApplication();
		$user 	=  JFactory::getUser();
		$db		= JFactory::getDBO();	

		$vendor_userid	=	$app->input->post->getInt('vendoruserid');
		$id				=	$app->input->post->getInt('id');
		
		$q = "DELETE FROM #__vmvendor_comments WHERE id='".$id."' 
		AND vendor_userid='".$vendor_userid."' AND created_by='".$user->id."' ";
		$db->setQuery($q);
		$db->execute();
		$message .= '<i class="vmv-icon-ok"></i> '.JText::_( 'COM_VMVENDOR_COMMENTS_COMMENTDELETED_SUCCESS' );
		$app->enqueueMessage( $message , 'message');
		$app->redirect('index.php?option=com_vmvendor&view=comments&vendoruserid='.$vendor_userid.'&Itemid='.$app->input->get('Itemid'));
	}
}