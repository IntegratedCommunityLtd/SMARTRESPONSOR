<?php 
/*
 * @component com_vmvendor
 * @copyright Copyright (C) 2010-2016 Adrien ROUSSEL Nordmograph.com
 * @license GNU/GPL Version 3
 * @Website : https://www.nordmograph.com/extensions
 */
  
 defined('JPATH_BASE') or die; 
 /* 
 jimport('joomla.html.html'); 
 jimport('joomla.form.formfield'); 
 jimport('joomla.form.helper'); 
 */ 
 JFormHelper::loadFieldClass('sql'); 
  
 /** 
  * Supports an HTML select list of options driven by SQL 
  */ 
 class JFormFieldCommentactivitystream extends JFormFieldSQL 
 { 
     /** 
      * The form field type. 
      */ 
     public $type = 'commentactivitystream'; 
  
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
		 
		$app = JFactory::getApplication();
		$doc = JFactory::getDocument();
		//$cparams 		= JComponentHelper::getParams('com_vmvendor');
		//$comment_minlength 	= $cparams->get('vmvcomment_minlength','20');
		
		  
	
		echo '<input type="checkbox" name = "'.$this->name.'" id="'.$this->id.'" 
			value="1" checked="checked" >';	
     } 
 } 
 ?> 