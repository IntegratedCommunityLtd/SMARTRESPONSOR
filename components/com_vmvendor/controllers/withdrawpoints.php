<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2016 Adrien Roussel Nordmograph.com
 * @license : GNU/GPL v3
 * @Website : https://www.nordmograph.com/extensions
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
class VmvendorControllerWithdrawpoints extends JControllerForm
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
		$model 			= $this->getModel('withdrawpoints');
		
		$cparams 				= JComponentHelper::getParams('com_vmvendor');
		$withdraw_minimum		= $cparams->get('withdraw_minimum', '0');
		$withdraw_maximum		= $cparams->get('withdraw_maximum', '1000000');
		$naming 				= $cparams->get('naming', 'username');	
		$additional_recipient	= $cparams->get('additional_recipient');	
		$config_withdraw_paypal	= $cparams->get('withdraw_paypal',1);
		$config_withdraw_iban	= $cparams->get('withdraw_iban',1);
		$ratio 					= $cparams->get('aup_ratio','1');
		
		$data  					= $this->input->post->get('jform', array(), 'array');
		$withdraw_points 		= $data['withdrawpoints'];
		$prefered_method 		= $data['prefered_method'];
		$withdraw_paypalemail	= $data['withdrawpaypalemail'];
		$withdraw_iban			= $data['withdrawiban'];
		$copyoftherequest		= @$data['copyoftherequest']; //1
		
		$form = $model->getForm();
		if (!$form)
		{
			$app->enqueueMessage( $model->getError(),'error');
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
			$app->setUserState('com_vmvendor.withdrawpoints.data', $data);

			// Redirect back to the form.
			$this->setRedirect(JRoute::_('index.php?option=com_vmvendor&view=withdrawpoints', false));
			return false;
		}
		
		$mypoints = $model->getPoints();
		if($withdraw_points >$mypoints)
		{
			$this->setRedirect( JRoute::_('index.php?option=com_vmvendor&view=withdrawpoints', false),
				JText::_('ERROR'),'error');
		}
		if($withdraw_points <$withdraw_minimum)
		{
			$this->setRedirect( JRoute::_('index.php?option=com_vmvendor&view=withdrawpoints', false),
				JText::_('COM_VMVENDOR_WITHDRAWPOINTS_NOTENOUGHSET'),'warning');
		}
		if($withdraw_points >$withdraw_maximum)
		{
			$withdraw_points = $withdraw_maximum;
			$app->enqueueMessage( 
				sprintf( JText::_('COM_VMVENDOR_WITHDRAWPOINTS_AMOUNTREACHEDMAX'), $withdraw_maximum),
				'warning');
		}
		if(!$config_withdraw_paypal && $withdraw_iban=='')
		{
			
			$this->setRedirect(JRoute::_('index.php?option=com_vmvendor&view=withdrawpoints', false),
			JText::_('COM_VMVENDOR_WITHDRAWPOINTS_MISSINGIBAN'),'warning');
		}
		if(!$config_withdraw_iban && $withdraw_paypalemail=='')
		{
			$this->setRedirect(JRoute::_('index.php?option=com_vmvendor&view=withdrawpoints', false),
			JText::_('COM_VMVENDOR_WITHDRAWPOINTS_MISSINGPAYPAL'),'warning');
		}
		
		if( $prefered_method=='iban' )
		{
			if(!VmvendorControllerWithdrawpoints::checkIBAN($withdraw_iban))
			{
				$this->setRedirect(JRoute::_('index.php?option=com_vmvendor&view=withdrawpoints', false),
				JText::_('COM_VMVENDOR_WITHDRAWPOINTS_MISSINGIBAN'),'warning');
			}
		}
			
		
		$main_currency 		= $model->getMainCurrency();
		$main_currency_code 	= $main_currency->currency_code_3;
		$main_currency_symbol	= $main_currency->currency_symbol;
		$main_position			= $main_currency->currency_positive_style;
		$main_decim				= $main_currency->currency_decimal_place;
		
		$accept_curz 	= $model->getAcceptedCurrencies();
		if(count($accept_curz)<2)
			$currency		= $main_currency;
		else
			$currency 		= $model->getVendorMainCurrency();
		$currency_code 		= $currency->currency_code_3;
		$currency_symbol	= $currency->currency_symbol;
		$position			= $currency->currency_positive_style;
		$decim				= $currency->currency_decimal_place;
	
		
		if($main_currency_code == $currency_code)
			$currency_ratio = 1;
		else
			$currency_ratio = $model->getCurrencyRatio($main_currency_code , $currency_code ) ;
		
		$validmoneyamount = number_format( $withdraw_points / ($ratio / $currency_ratio) , $decim );
		$info_data = sprintf( 
				JText::_('COM_VMVENDOR_WITHDRAWPOINTS_INFODATA'), 
				strtoupper($prefered_method),
				$validmoneyamount , 
				$currency_code
			);
		

		$q = "INSERT INTO #__vmvendor_userpoints_details 
		( userid , points, insert_date , status , keyreference , datareference )
		VALUES ('".$user->id."' , '". -$withdraw_points ."' , '".date('Y-m-d H:i:s')."' , '1' , '' , '".$db->escape($info_data)."' ) ";	
		$db->setQuery( $q );
		$db->execute();
		$q = "UPDATE #__vmvendor_userpoints SET points = points - ".$withdraw_points ."  
		WHERE userid='".$user->id."' "	;
		$db->setQuery( $q );
		$db->execute();

		// send email
		$mailer 	= JFactory::getMailer(); 
		$config 	= JFactory::getConfig();
		$sendto		= $config->get( 'mailfrom' );
		$mailfrom 	= $config->get( 'mailfrom' );
		$fromname 	= $config->get( 'fromname' );
		
		$subject 	= sprintf(JText::_('COM_VMVENDOR_WITHDRAW_MAILSUBJECT'),
								ucfirst($user->username),
								$validmoneyamount,
								$currency_code,
								strtoupper($prefered_method)
								);
		$pts_page = $juri.'administrator/index.php?option=com_vmvendor&view=pointsactivity&userid='.$user->id;
		
		
		$body = sprintf(JText::_('COM_VMVENDOR_WITHDRAW_ISREQUESTING'),
								ucfirst($user->username),
								strtoupper($prefered_method),
								$validmoneyamount,
								$currency_code
								);
		$body .= sprintf( 
					JText::_('COM_VMVENDOR_WITHDRAW_FIRSTCHECK'),
					$withdraw_points,
					$pts_page
				);	
		$body .= $pts_page;
		$body .= '<br />';
		
		$purpose = sprintf( JText::_('COM_VMVENDOR_WITHDRAW_PURPOSE'),$user->username,$withdraw_points);
		$purpose .= ' - '.$juri;
		$purpose = urlencode($purpose);
		
		if( $prefered_method=='paypal')
		{
			$paypal_page = 'https://www.paypal.com/cgi-bin/webscr/?cmd=_donations&business='.$withdraw_paypalemail;
			$paypal_page .= '&item_name='.$purpose.'&amount='.$validmoneyamount;
			$paypal_page .= '&no_shipping=1&currency_code='.$currency_code.'&tax=0&bn=PP-DonationsBF';
			$body .= sprintf(JText::_('COM_VMVENDOR_WITHDRAW_PROCESSHERE'),$paypal_page).'<br /><br />';	
			$body .= $paypal_page;
		}
		
		if( $prefered_method=='iban')
		{
			$body .= JText::_('COM_VMVENDOR_WITHDRAW_IBANTOCREDIT').'<br />';	
			$body .= $withdraw_iban;
		}

		$recipients = array();
  		$recipients[] = $sendto;
		
		if( $copyoftherequest && $sendto !=$user->email )
		{
			$recipients[] = $user->email;
		}
		$mailer->addRecipient( $recipients );
		$mailer->setSubject($subject);
		$mailer->isHTML(true);
		$mailer->Encoding = 'base64';
		$mailer->setBody($body);
		$sender = array( $mailfrom, $fromname );
  		$mailer->setSender($sender);
  		$sent = $mailer->send();
  		if ($sent)
		{
			$msg = '<i class="vmv-icon-ok"></i> '.sprintf(
				JText::_('COM_VMVENDOR_WITHDRAW_REQUESTSENT'), 
				strtoupper($prefered_method) 
			);
		}
		else
		{
			$msg = '<i class="vmv-icon-cancel"></i> '.JText::_('COM_VMVENDOR_WITHDRAW_EMAILERROR');
			$this->setRedirect(JRoute::_('index.php?option=com_vmvendor&view=withdrawpoints', false), $msg, 'warning');
		}
		$this->setRedirect(JRoute::_('index.php?option=com_vmvendor&view=dashboard', false), $msg);
	}
	
	static function checkIBAN($iban)
	{
		$iban = strtolower(str_replace(' ','',$iban));
		$Countries = array('al'=>28,'ad'=>24,'at'=>20,'az'=>28,'bh'=>22,'be'=>16,'ba'=>20,'br'=>29,'bg'=>22,'cr'=>21,'hr'=>21,'cy'=>28,'cz'=>24,'dk'=>18,'do'=>28,'ee'=>20,'fo'=>18,'fi'=>18,'fr'=>27,'ge'=>22,'de'=>22,'gi'=>23,'gr'=>27,'gl'=>18,'gt'=>28,'hu'=>28,'is'=>26,'ie'=>22,'il'=>23,'it'=>27,'jo'=>30,'kz'=>20,'kw'=>30,'lv'=>21,'lb'=>28,'li'=>21,'lt'=>20,'lu'=>20,'mk'=>19,'mt'=>31,'mr'=>27,'mu'=>30,'mc'=>27,'md'=>24,'me'=>22,'nl'=>18,'no'=>15,'pk'=>24,'ps'=>29,'pl'=>28,'pt'=>25,'qa'=>29,'ro'=>24,'sm'=>27,'sa'=>24,'rs'=>22,'sk'=>24,'si'=>19,'es'=>24,'se'=>24,'ch'=>21,'tn'=>24,'tr'=>26,'ae'=>23,'gb'=>22,'vg'=>24);
		$Chars = array('a'=>10,'b'=>11,'c'=>12,'d'=>13,'e'=>14,'f'=>15,'g'=>16,'h'=>17,'i'=>18,'j'=>19,'k'=>20,'l'=>21,'m'=>22,'n'=>23,'o'=>24,'p'=>25,'q'=>26,'r'=>27,'s'=>28,'t'=>29,'u'=>30,'v'=>31,'w'=>32,'x'=>33,'y'=>34,'z'=>35);
	
		if(strlen($iban) == $Countries[substr($iban,0,2)]){
	
			$MovedChar = substr($iban, 4).substr($iban,0,4);
			$MovedCharArray = str_split($MovedChar);
			$NewString = "";
	
			foreach($MovedCharArray AS $key => $value){
				if(!is_numeric($MovedCharArray[$key])){
					$MovedCharArray[$key] = $Chars[$MovedCharArray[$key]];
				}
				$NewString .= $MovedCharArray[$key];
			}
			
			if (!function_exists('bcmod')) {
				function bcmod( $x, $y )   
				{ 
					// how many numbers to take at once? carefull not to exceed (int) 
					$take = 5;     
					$mod = ''; 
						
					do 
					{ 
						$a = (int)$mod.substr( $x, 0, $take ); 
						$x = substr( $x, $take ); 
						$mod = $a % $y;    
					} 
					while ( strlen($x) ); 
				
					return (int)$mod; 
				} 
			}
			if(bcmod($NewString, '97') == 1)
			{
				return TRUE;
			}
			else{
				return FALSE;
			}
		}
		else{
			return FALSE;
		}   
	}
}
?>