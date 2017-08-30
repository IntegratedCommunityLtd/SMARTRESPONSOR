<?php
defined('_JEXEC') or die('Restricted access');

	$user = JFactory::getUser();
    foreach ($user->groups as $key => $value){
		if ($key == 3) {
//		echo $key;
		echo '<script type="text/javascript">
jQuery(document).ready(function($){
    $("fieldset").attr("disabled", true);
    $("#virtuemart_country_id_field").attr("disabled", true);
    $("#virtuemart_state_id_field").attr("disabled", true);
	
});
</script>';
    }
	}	
	
	
?> 
<div id="bonusChek">
<?php 
//	echo $this->loadTemplate ( 'ballans' );	

 ?>
</div>
	
<form method="post" id="adminForm" name="userForm" role="form" action="<?php if ($key != 3) { echo JRoute::_('index.php?view=user',$this->useXHTML,$this->useSSL); } ?>" class="form-validate">
<?php // Loading Templates in Tabs
    if($this->userDetails->user_is_vendor){
		echo $this->loadTemplate ( 'vendor' );
    }
?>


			<?php 
			if ($key != 3) {
			?>
	
			<button class="btn btn-primary" type="submit" style="width: 100%;" onclick="javascript:return myValidator(userForm, 'saveUser');" ><span class="glyphicon glyphicon-floppy-save"></span><span class="hidden-xs"> 
								<?php echo $this->button_lbl ?></span></button>
<input type="hidden" name="option" value="com_virtuemart" />
<input type="hidden" name="controller" value="user" />									
				<?php
				echo JHTML::_( 'form.token' );
				}
				?>
</form>

<script>
jQuery('input:text').addClass('form-control');
jQuery('select').addClass('form-control');
jQuery('div#tab-2 ul li').addClass('form-control');
</script>