<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2016 Adrien Roussel Nordmograph.com
 * @license : GNU/GPL v3
 * @Website : https://www.nordmograph.com/extensions
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
class VmvendorControllerAskvendor extends JControllerForm
{
 	function __construct()	{
		parent::__construct( );
	}
	
	public function getModel($name = '', $prefix = '', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, array('ignore_request' => false));
	}	
	public function send()
	{
		$user 			= JFactory::getUser();
		$db 			= JFactory::getDBO();
		$doc 			= JFactory::getDocument();
		$juri 			= JURI::base();
		$app 			= JFactory::getApplication();
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$model 			= $this->getModel('askvendor');
		$data  			= $this->input->post->get('jform', array(), 'array');
		//var_dump($data);break;
		$cparams 		= JComponentHelper::getParams('com_vmvendor');
		$naming 		= $cparams->get('naming', 'username');
		$form = $model->getForm();
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
			$app->setUserState('com_vmvendor.askvendor.data', $data);

			// Redirect back to the form.
			$this->setRedirect(JRoute::_('index.php?option=com_vmvendor&view=askvendor&tmpl=component', false));
			return false;
		}
			
			
		
		$fromname 		= $data['formname'];
		$mailfrom 		= $data['formemail'];
		$subject 		= $data['formsubject'];
		$formmessage  	= $data['formmessage'];
		$emailto		= $this->input->post->get('emailto','','raw');
		$product_url	= $this->input->post->get('formhref','','raw');

		if( $fromname !='' && $mailfrom !='' &&  $subject!='' &&   $formmessage!='' )
		{

			$mailer = JFactory::getMailer(); 
			$config = JFactory::getConfig();
			$body .= $formmessage.",\r\n\r\n";
			$body .= urldecode($product_url);
			$mailerror = JText::_('COM_VMVENDOR_ASKVENDOR_EMAILFAILED');
			$mailer->addRecipient($emailto); 
			$mailer->addBCC( $mailfrom );
			$mailer->setSubject($subject);
			$mailer->setBody($body);
			$sender = array( $mailfrom, $fromname );
			$mailer->setSender($sender);
			$sent = $mailer->send();
			if ($sent != 1) 
				$sent = 2;
			$app->redirect('index.php?option=com_vmvendor&view=askvendor&tmpl=component&sent='.$sent );
		}
	}	
}
?>