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
class VmvendorControllerMailcustomer extends JControllerForm
{
 	function __construct()
	{
		parent::__construct( );
	}
	public function getModel($name = '', $prefix = '', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, array('ignore_request' => false));
	}
	public function send() 
	{
		$user 			= JFactory::getUser();
		$doc 			= JFactory::getDocument();
		$db 			= JFactory::getDbo();
		$juri 			= JURI::base();
		$app 			= JFactory::getApplication();
		$cparams 		= JComponentHelper::getParams('com_vmvendor');
		$naming 				= $cparams->get('naming', 'username');	
		$customercontactform	= $cparams->get('customercontactform', '1'); //1=email 	11=email+admin    2=jomsocial pms
		
		$orderitem_id		= $app->input->post->getInt('orderitem_id');
		$getcust = VmvendorControllerMailcustomer::getCust($orderitem_id);
		$emailto = $getcust->email;
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$model 	= $this->getModel('mailcustomer');
		$data  	= $this->input->post->get('jform', array(), 'array');
		$form 	= $model->getForm();
		if (!$form)
		{
			$app->enqueueMessage( $model->getError(),  'error');
			return false;
		}
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
			$app->setUserState('com_vmvendor.mailcustomer.data', $data);

			// Redirect back to the form.
			$this->setRedirect(JRoute::_('index.php?option=com_vmvendor&view=mailcustomer&tmpl=component', false));
			return false;
		}
		
		
		$vendorname 	= $data['vendorname'];
		$subject 		= $data['subject'];
		$body 			= $data['body'];
		
		$mailfrom		= $user->email;
		//$app->enqueueMessage($fromname.' '.$mailfrom.' '.$subject.' '.$formmessage, 'message');
		
		if( $vendorname!='' && $mailfrom!='' && $emailto!='' &&  $subject!='' && $body!='' )
		{
			$product_url = $app->input->post->get('formhref');
			$mailer 	= JFactory::getMailer(); 
			$config 	= JFactory::getConfig();
			
			$body .= urldecode($product_url);
			$mailerror = JText::_('COM_VMVENDOR_ASKVENDOR_EMAILFAILED');
			$mailer->addRecipient($emailto); 
			$mailer->addBCC( $mailfrom );
			if($customercontactform=='11')
				$message->addBCC( $config->get( 'mailfrom' ) );   // site admin
			
			$mailer->setSubject($subject);
			//$mailer->isHTML(true);
			//$mailer->Encoding = 'base64';
			$mailer->setBody($body);
			$sender = array( $mailfrom, $vendorname );
			$mailer->setSender($sender);
			$sent = $mailer->send();
			if ($sent != 1) 
				$app->enqueueMessage($mailerror,'error');
			$app->redirect(
			'index.php?option=com_vmvendor&view=mailcustomer&Itemid='.$app->input->get('Itemid').'&tmpl=component&sent='.$sent);	
		}
		else
			$app->enqueueMessage(  JText::_('COM_VMVENDOR_ASKVENDOR_EMAILFAILED') ,'warning' );
	}	
	
	
	static function getCust( $orderitem_id )
	{
		$db 			= JFactory::getDBO();
		$app			= JFactory::getApplication();
		$q = "SELECT  vou.`first_name` , vou.`middle_name` , vou.`last_name` , vou.`email` 
		FROM `#__virtuemart_order_userinfos` vou 
		JOIN  #__virtuemart_order_items voi ON vou.virtuemart_order_id = voi.virtuemart_order_id 
		WHERE vou.address_type= 'BT' 
		AND voi.virtuemart_order_item_id ='".$orderitem_id."' ";
		$db->setQuery($q);
		$cust = $db->loadObject();
		return $cust;
	}
	
	
	
}
?>