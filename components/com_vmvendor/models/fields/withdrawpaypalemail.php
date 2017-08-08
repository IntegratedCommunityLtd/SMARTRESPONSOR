<?php 
/*
 * @component com_vmvendor
 * @copyright Copyright (C) 2010-2016 Adrien ROUSSEL Nordmograph.com
 * @license GNU/GPL Version 3
 * @Website : https://www.nordmograph.com/extensions
 */
  
 defined('JPATH_BASE') or die; 
 JFormHelper::loadFieldClass('sql'); 
  
 /** 
  * Supports an HTML select list of options driven by SQL 
  */ 
 class JFormFieldWithdrawpaypalemail extends JFormFieldSQL 
 { 
     /** 
      * The form field type. 
      */ 
     public $type = 'withdrawpaypalemail'; 
  
     /** 
      * Overrides parent's getinput method 
      */ 
     protected function getInput() 
     { 
        // Initialize variables. 
		$db 	= JFactory::getDBO();
		$user 	= JFactory::getUser();
		$cparams 				= JComponentHelper::getParams('com_vmvendor');
		$config_withdraw_iban	= $cparams->get('withdraw_iban',1);		
		$mypaypalemail = JFormFieldWithdrawpaypalemail::getPaypalEmail();

		echo '<input type="text" name = "'.$this->name.'"  id="'.$this->id.'" 
		class="inputbox validate-email ';
		if(!$config_withdraw_iban)
			echo ' required ';
		
		echo '" value="'.$mypaypalemail.'" />';
		$Itemid  = $this->getEditprofileItemid();
		$url = 'index.php?option=com_vmvendor&view=editprofile';
		if($Itemid)
			$url .= '&Itemid='.$Itemid;
		$url = JRoute::_( $url );
		echo '<div class="vmv-mini-info">'.sprintf(JText::_('COM_VMVENDOR_WITHDRAW_ONCE4ALLPAYPALEMAIL'),$url).'</div>';
     }
	 
	 protected function getLabel() 
     { 
        // Initialize variables. 
		$cparams 				= JComponentHelper::getParams('com_vmvendor');
		$config_withdraw_iban	= $cparams->get('withdraw_iban',1);
		

		echo '<label id="'.$this->id.'-lbl" for="'.$this->id.'" class="hasTooltip ';
		if(!$config_withdraw_iban)
			echo 'required';
		echo '" title="" data-original-title="<strong>'.JText::_('COM_VMVENDOR_WITHDRAW_PAYPALEMAIL_TITLE').'</strong>
		<br />'.JText::_('COM_VMVENDOR_WITHDRAW_PAYPALEMAIL_DESC').'.">'.JText::_('COM_VMVENDOR_WITHDRAW_PAYPALEMAIL_TITLE');
		if(!$config_withdraw_iban)
			echo ' <span class="star">&nbsp;*</span>';
		echo '</label>';
     } 
	static function getPaypalEmail()
    {
        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        $q ="SELECT `paypal_email` 
        FROM `#__vmvendor_paypal_emails`
        WHERE userid='".$user->id."' " ;        
        $db->setQuery($q);
        $result = $db->loadResult();
        return $result;
    }
	
	function getEditprofileItemid()
	{
		$app 	= JFactory::getApplication();
		$lang = JFactory::getLanguage();
		$db 	= JFactory::getDBO();
		$q = "SELECT `id` FROM `#__menu` WHERE `link`='index.php?option=com_vmvendor&view=editprofile' 
		AND `type`='component'  AND ( language ='".$lang->getTag()."' OR language='*') 
		AND published='1'  AND access='1' ";
		$db->setQuery($q);
		$itemid = $db->loadResult();
		return $itemid;

	
	}
 } 
 ?> 