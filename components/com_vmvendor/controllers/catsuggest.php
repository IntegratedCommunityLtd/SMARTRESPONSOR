<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2016 Adrien Roussel Nordmograph.com
 * @license : GNU/GPL v3
 * @Website : https://www.nordmograph.com/extensions
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
class VmvendorControllerCatsuggest extends JControllerForm
{
	/**
	 * Custom Constructor
	 */
 	function __construct()
	{
		parent::__construct( );
	}
	
	 public function getModel($name = '', $prefix = '', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, array('ignore_request' => false));
	}
	
	// function below moved to main controller as not working here. Needs investigation.
	
	public function save($key = NULL, $urlVar = NULL) // suggests or adds a new category
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$app 					= JFactory::getApplication();
		$user 					= JFactory::getUser();		
		$juri 					= JURI::base();
		$db						= JFactory::getDBO();	
		$model      			= $this->getModel ( 'catsuggest' );
		$view       			= $this->getView  ( 'catsuggest','html' );
		$form = $model->getForm();
		$data  = $this->input->post->get('jform', array(), 'array');
		$tmpl					=	$app->input->get('tmpl');
		
		$validate = $model->validate($form, $data);
		if ($validate === false)
		{
			// Get the validation messages.
			$errors	= $model->getErrors();
			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 5; $i++)
			{
				if ($errors[$i] instanceof Exception)
					$app->enqueueMessage( $errors[$i]->getMessage(), 'warning');
				else
					$app->enqueueMessage($errors[$i], 'warning');
			}

			// Save the data in the session.
			$app->setUserState('com_vmvendor.catsuggest.data', $data);

			// Redirect back to the form.
			if($tmpl!='component')
				$this->setRedirect( JRoute::_('index.php?option=com_vmvendor&view=catsuggest&Itemid='.$app->input->get('Itemid') ) );
			else	
				$this->setRedirect( JRoute::_('index.php?option=com_vmvendor&view=catsuggest&Itemid='.$app->input->get('Itemid').'&tmpl='.$tmpl ) );
			return false;
		}
		
		
		if (!class_exists( 'VmConfig' ))
			require JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php';
		VmConfig::loadConfig();	
		
		$cparams 		= JComponentHelper::getParams('com_vmvendor');
		$multilang_mode = $cparams->get('multilang_mode', 0);
		if($multilang_mode >0)
		{
			$active_languages	=	VmConfig::get( 'active_languages' ); //en-GB
		}
		
		
		$cat_suggest 			= $cparams->get('cat_suggest',1);
		$naming 				= $cparams->get('naming','username');
		$to 					= $cparams->get('to');
		$cat_name				=	$data['cat_name'];
		$cat_descr				=	$data['cat_descr'];
		$cat_parent				=	$data['suggestcatselectlist'];
		
		$cat_id					='';
		
		if($cat_suggest==3)
			$cat_published			=	1;
		else		
			$cat_published			=	0;
		if($cat_parent!='0')
		{
			$q = "SELECT `category_name` FROM `#__virtuemart_categories_".VMLANG."` 
			WHERE `virtuemart_category_id` ='".$cat_parent."' ";
			$db->setQuery($q);
			$parent_name = $db->loadResult();
		}
		else
			$parent_name = JText::_('COM_VMVENDOR_CATSUGGEST_ROOT');
		
		if($cat_suggest >1 && $cat_name !='' && $cat_parent!=''  )
		{
			$q = "INSERT INTO `#__virtuemart_categories`  
				( `virtuemart_vendor_id`   ,  `limit_list_step` , `limit_list_initial` , `hits` , 
				`ordering` , `shared` , `published` , `created_on` , `created_by` , `modified_on` , 
				`modified_by` , `locked_on` , `locked_by` )
				VALUES 
				('1','0','10','0','0','0','".$cat_published."' , '".date('Y-m-d H:i:s')."' , '".$user->id."' , 
				'0000-00-00 00:00:00' , '' , '0000-00-00 00:00:00' , '') ";
			$db->setQuery($q);
			$db->execute();
			$cat_id = $db->insertid();
			$cat_slug				= strtolower( str_replace(' ','-' , $cat_name) ).'-'.$cat_id;
			$q ="INSERT INTO  `#__virtuemart_categories_".VMLANG."`  
				( `virtuemart_category_id` , `category_name` , `category_description` ,  `customtitle` , `slug` ) 
				VALUES 
				('".$cat_id."','".ucfirst( $db->escape($cat_name) )."','".ucfirst( $db->escape($cat_descr))."','',
				'". $db->escape($cat_slug) ."') ";
			$db->setQuery($q);
			$db->execute();		
				
			if($multilang_mode >0)
			{ 					
				for($i = 0 ; $i < count( $active_languages ) ; $i++)
				{
					//$app->enqueueMessage($active_languages[$i]);
					if( str_replace('_' , '-' , VMLANG) != strtolower( $active_languages[$i]) )
					{
						$q ="INSERT INTO `#__virtuemart_categories_".strtolower(str_replace('-','_',$active_languages[$i]))."`  
							( `virtuemart_category_id` , `category_name` , `category_description` , 
							`metadesc` , `metakey` , `customtitle` , `slug` ) 
							VALUES 
							('".$cat_id."','".ucfirst( $db->escape($cat_name) )."',
							'".ucfirst( $db->escape($cat_descr))."','','','','". $db->escape($cat_slug) ."') ";
						$db->setQuery($q);
						$db->execute();
								
					}
							
				}
			}
				
				
						
			$q ="INSERT INTO  `#__virtuemart_category_categories`   
				( `category_parent_id` , `category_child_id` , `ordering` ) 
				VALUES 
				('".$cat_parent."','".$cat_id."','0') ";
			$db->setQuery($q);
			$db->execute();
				
			$msg = '<i class="vmv-icon-ok"></i> ';
			if($cat_suggest==3)
				$msg .= JText::_( 'COM_VMVENDOR_CATSUGGEST_THANKS_PUBLISHED' );
			else
				$msg .= JText::_( 'COM_VMVENDOR_CATSUGGEST_THANKS_UNDERMODERATION' );					
				
		}
		if($cat_suggest>=1 )
		{
				$subject = JText::_('COM_VMVENDOR_CATSUGGEST_EMAILNOTIFICATION_SUGGESTION_SUBJECT')." ".$user->$naming;	
				$mailurl= $juri.'administrator/index.php?option=com_virtuemart&view=category';
				if($cat_id!='')
					$mailurl .= '&task=edit&cid='.$cat_id;					
				$body = JText::_('COM_VMVENDOR_CATSUGGEST_EMAILNOTIFICATION_SUGGESTION_BODY');
				$body .= '<br /><br />'.JText::_('COM_VMVENDOR_CATSUGGEST_CATNAME').': '.ucfirst($cat_name);
				$body .= '<br />'.JText::_('COM_VMVENDOR_CATSUGGEST_CATDESCR').': '.ucfirst($cat_descr);
				$body .= '<br />'.JText::_('COM_VMVENDOR_CATSUGGEST_CATPARENT').': '.ucfirst($parent_name);
				if($cat_parent!='0')
					$body .= ' (ID:'.$cat_parent.')';
				$body .= '<br />URL: <a href="'.$mailurl.'">'.$mailurl.'</a>';
				$mailerror = '<i class="vmv-icon-cancel"></i> <font color="red"><b>'.JText::_('COM_VMVENDOR_EMAIL_FAIL').'</b></font>';		
				$mailer 	= JFactory::getMailer();
				$config 	= JFactory::getConfig();
				$mailer->addRecipient( array( $config->get( 'mailfrom' ) , $to ) );
				$mailer->setSubject( $subject );
				$mailer->setBody($body);
				$config = JFactory::getConfig();
				$sender = array( 
					$config->get( 'mailfrom' ),
					$config->get( 'fromname' )
				);
				$mailer->isHTML(true);
				$mailer->Encoding = 'base64';
				$mailer->setSender( $sender );
				$sent = $mailer->send();
				if ($sent != 1) 
					echo  $mailerror;
				if($cat_suggest==1)
					$msg .= JText::_( 'COM_VMVENDOR_CATSUGGEST_THANKS_UNDERMODERATION' );
			
		}
		$app->enqueueMessage( $msg , 'message');
		
		if($tmpl!='component')
			$app->redirect( JRoute::_('index.php?option=com_vmvendor&view=catsuggest&Itemid='.$app->input->get('Itemid') ) );
		else	
			$app->redirect( JRoute::_('index.php?option=com_vmvendor&view=catsuggest&Itemid='.$app->input->get('Itemid').'&tmpl='.$tmpl ) );
	}
}
?>