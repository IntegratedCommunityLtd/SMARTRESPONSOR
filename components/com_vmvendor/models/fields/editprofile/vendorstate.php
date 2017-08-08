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
 class JFormFieldVendorstate extends JFormFieldSQL 
 { 
     /** 
      * The form field type. 
      */ 
     public $type = 'vendorstate'; 
  
     /** 
      * Overrides parent's getinput method 
      */ 
     protected function getInput() 
     { 
         // Initialize variables. 
         $html = ''; 
         // Load user 
         $user = JFactory::getUser(); 
         $user_id = $user->get('id'); 
		 $db = JFactory::getDBO();
		 $app = JFactory::getApplication();
		
	
		
  		$q ="SELECT virtuemart_state_id, state_name FROM #__virtuemart_states 
		WHERE published='1' ORDER BY ordering ASC, state_name ASC ";
		$db->setQUery($q);
		$states = $db->loadObjectList();
         // do the SQL  
		$virtuemart_state_id = $this->getVendorstate();	
		 

			 echo '<select size="5" name = "'.$this->name.'"  id="'.$this->id.'" class="" >';
			 echo '<option value="0" ';
			 if(!$virtuemart_state_id OR $virtuemart_state_id=='0')
			 	echo 'selected="selected" ';
			 echo '>'.JText::_( 'JNONE' ).'</option>';
			foreach($states as $state)
			{
				echo '<option value="'.$state->virtuemart_state_id.'" ';
				if($virtuemart_state_id == $state->virtuemart_state_id)
					echo 'selected="selected" ';	
				echo '>'.JText::_( $state->state_name ).'</option>';
			}
	
			echo '</select>';
         // return the HTML 
        // return $data; 
     } 
	 
	 public function getVendorstate() 
	{
		$db 	= JFactory::getDBO();
		$user 	= JFactory::getUser();
		$q = "SELECT `virtuemart_state_id` FROM `#__vmvendor_vendoraddress` WHERE `vendor_user_id` = '".$user->id."' ";
		$db->setQuery($q);
		$virtuemart_state_id = $db->loadResult();
		$this->virtuemart_state_id = $virtuemart_state_id;
		return $this->virtuemart_state_id;
	}
}
?> 