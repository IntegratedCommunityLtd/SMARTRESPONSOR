<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
$user = JFactory::getUser();  
	foreach ($user->groups as $key => $value){
		
		if ($key == 12) {
//		echo $key;		
		echo '<script type="text/javascript">
jQuery(document).ready(function($){
    $("fieldset").attr("disabled", true);
});
</script>';
		}
    }	
		?>
<div class="tab-pane" id="shoper">
		<?php 
	//		echo $this->loadTemplate('vmshopper');
			echo $this->loadTemplate('address_userfields');
	//		echo $this->loadTemplate('address_addshipto');

		if ($key != 12) {

	/*
			<button class="btn btn-primary" type="submit" onclick="javascript:return myValidator(userForm, true);" ><span class="glyphicon glyphicon-floppy-save"></span><span class="hidden-xs"> <?php echo $this->button_lbl ?></span></button>
	*/
		?>
	<input type="hidden" name="task" value="saveUser" />
	<input type="hidden" name="address_type" value="<?php echo $this->address_type; ?>"/>
		<?php
		if(!empty($this->virtuemart_userinfo_id)){
			echo '<input type="hidden" name="virtuemart_userinfo_id" value="'.(int)$this->virtuemart_userinfo_id.'" />';
		}		
			}
			?>
</div>