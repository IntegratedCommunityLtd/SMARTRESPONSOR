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
 class JFormFieldCommentarea extends JFormFieldSQL 
 { 
     /** 
      * The form field type. 
      */ 
     public $type = 'commentarea'; 
  
     /** 
      * Overrides parent's getinput method 
      */ 
     protected function getInput() 
     { 
		$html = '';  
        $user = JFactory::getUser(); 
        $user_id = $user->get('id'); 
		 
		$app = JFactory::getApplication();
		$doc = JFactory::getDocument();
		$cparams 		= JComponentHelper::getParams('com_vmvendor');
		$comment_minlength 	= $cparams->get('vmvcomment_minlength','20');
		$comment_maxlength 	= $cparams->get('vmvcomment_maxlength','200');
		  
		$script = "function countChar(val) {
        	var len = val.value.length;
			if(len < ".$comment_minlength.")
				jQuery('#".$this->id."').css('color','red');
			else
			{
				jQuery('#".$this->id."').css({color:''});
			}
        	if (len > ".$comment_maxlength.") {
          		val.value = val.value.substring(0, ".$comment_maxlength.");
        	} else {
         		jQuery('#charNum').text(".$comment_maxlength." - len);
        	}
      	};";
	  	$doc->addScriptDeclaration( $script );

		echo '<div><span id="charNum" >'.$comment_maxlength.'</span> '.JText::_('COM_VMVENDOR_COMMENTS_CHARSLEFT').'</div>';
		echo '<textarea onkeyup="countChar(this)" name = "'.$this->name.'"  id="'.$this->id.'" 
		required="true" rows="4" cols="50" ></textarea>';	
     } 
 } 
 ?> 