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
 class JFormFieldVendorcountry extends JFormFieldSQL 
 { 
     /** 
      * The form field type. 
      */ 
     public $type = 'vendorcountry'; 
  
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
		
	
		
  		$q ="SELECT virtuemart_country_id, country_name FROM #__virtuemart_countries 
		WHERE published='1' ORDER BY ordering ASC, country_name ASC ";
		$db->setQUery($q);
		$countries = $db->loadObjectList();
         // do the SQL  
		$virtuemart_country_id = $this->getVendorcountry();

		echo '<select size="5" name = "'.$this->name.'"  id="'.$this->id.'" class="input required" >';
		foreach($countries as $country)
		{
				echo '<option value="'.$country->virtuemart_country_id.'" ';
				if($virtuemart_country_id == $country->virtuemart_country_id)
					echo ' selected="selected" ';
				echo '>'.JText::_( $country->country_name ).'</option>';
			}
	
			echo '</select>';
         // return the HTML 
        // return $data; 
     } 
	 
	 public function getVendorcountry() 
	{
		$db 	= JFactory::getDBO();
		$user 	= JFactory::getUser();
		$q = "SELECT `virtuemart_country_id` FROM `#__vmvendor_vendoraddress` WHERE `vendor_user_id` = '".$user->id."' ";
		$db->setQuery($q);
		$virtuemart_country_id = $db->loadResult();
		$this->virtuemart_country_id = $virtuemart_country_id;
		return $this->virtuemart_country_id;
	}
 } 
 ?> 