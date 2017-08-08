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
 class JFormFieldwithdrawpoints extends JFormFieldSQL 
 { 

     public $type = 'withdrawpoints'; 
     protected function getInput() 
     { 
        // Initialize variables. 
		$db 	= JFactory::getDBO();
		$user 	= JFactory::getUser();
	
		$q = 'SELECT `points`
		FROM `#__vmvendor_userpoints` 				  
		WHERE `userid` ='.$user->id;
        $db->setQuery($q);
        $mypoints = $db->loadResult();
		$value = $mypoints;
		$cparams 				= JComponentHelper::getParams('com_vmvendor');
		$withdraw_maximum		= $cparams->get('withdraw_maximum', '1000000');
		$config_withdraw_iban	= $cparams->get('withdraw_iban', 1);
		if($value>$withdraw_maximum)
			$value = $withdraw_maximum;
		echo '<input type="text" name = "'.$this->name.'"  id="'.$this->id.'" onkeyup="javascript:calcul()"
		class="inputbox required validate-maxpoints" required value="'.$value.'" /><br />';
     } 
	  protected function getLabel() 
     { 
        // Initialize variables. 
		$cparams 				= JComponentHelper::getParams('com_vmvendor');
		$withdraw_minimum		= $cparams->get('withdraw_minimum', '0');
		$withdraw_maximum		= $cparams->get('withdraw_maximum', '1000000');

		echo '<label id="'.$this->id.'-lbl" for="'.$this->id.'" class="hasTooltip ';
		if(!$config_withdraw_iban)
			echo 'required';
		echo '" title="" data-original-title="<strong>'.JText::_('COM_VMVENDOR_WITHDRAW_POINTSTOWITHDRAW_TITLE').'</strong>
		<br />'.sprintf(
			JText::_('COM_VMVENDOR_WITHDRAW_POINTSTOWITHDRAW_DESC'),
			$withdraw_minimum,
			$withdraw_maximum
			).'">'.JText::_('COM_VMVENDOR_WITHDRAW_POINTSTOWITHDRAW_TITLE');
		echo ' <span class="star">&nbsp;*</span>';
		echo '</label>';
     } 
 } 
 ?> 