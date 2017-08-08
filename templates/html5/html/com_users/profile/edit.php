<?php
defined('_JEXEC') or die;
//JHtml::_('behavior.keepalive');
//JHtml::_('behavior.tooltip');
//JHtml::_('behavior.formvalidation');
//load user_profile plugin language
$lang = JFactory::getLanguage();
$user = JFactory::getUser();
$lang->load( 'plg_user_profile', JPATH_ADMINISTRATOR );

?>
<div id ="proedit" class="modal0 fade in profile-edit<?php echo $this->pageclass_sfx?>" role="dialog" tabindex="-1" aria-hidden="true">
<div class="modal-dialog0 col-sm-8 col-xs-12">
<?php 
foreach ($user->groups as $key => $value){
		if ($key == 12) {
		echo '<script type="text/javascript">
jQuery(document).ready(function($){
    $("fieldset").attr("disabled", true);
});
</script>';
    }
	} ?>

<div class="modal-content0">
<?php if ($this->params->get('show_page_heading')) : ?>
	<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
<?php endif; ?>
		<?php if (isset($fieldset->label)):// If the fieldset has a label set, display it as the legend.?>
		<?php echo JText::_($fieldset->label); ?>
		<?php endif;?>


<form id="member-profile" role="form" action="<?php echo JRoute::_('index.php?option=com_users&task=profile.save'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
<?php foreach ($this->form->getFieldsets() as $group => $fieldset):// Iterate through the form fieldsets and display each one.?>
	<?php $fields = $this->form->getFieldset($group);?>
	<?php if (count($fields)):?>

<div class="modal-header0">	
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<?php if (isset($fieldset->label)):// If the fieldset has a label set, display it as the legend.?>
		<h4 class="modal-title" id="proedit"><?php echo JText::_($fieldset->label); ?></h4>
		<?php endif;?>
		
</div>	
	<fieldset>	
<div class="modal-body0" style="background-color: #eee;">		
		<?php foreach ($fields as $field):// Iterate through the fields in the set and display them.?>
			<?php if ($field->hidden):// If the field is hidden, just display the input.?>
				<?php echo $field->input;?>
			<?php else:?>

					<?php echo $field->label; ?>
					<?php if (!$field->required && $field->type!='Spacer' && $field->name!='jform[username]'): ?>
					<span class="optional"><?php echo JText::_('COM_USERS_OPTIONAL'); ?> </span>
					<?php endif; ?>

				<?php echo $field->input; ?>
			<?php endif;?>
		<?php endforeach;?>
		<?php
		if ($key != 12) {
		echo  '<div class="modal-footer0"><button type="submit" class="btn btn-primary col-sm-12 col-xs-12 validate"><span>' . JText::_('JSUBMIT') . '</span></button><input type="hidden" name="option" value="com_users" /><input type="hidden" name="task" value="profile.save" />' . JHtml::_('form.token');
    } ?>		
</div>		
	</fieldset>
	<?php endif;?>
<?php endforeach;?>

	</form>

</div>
</div>
</div>
<script type="text/javascript">
jQuery(document).ready(function($){
//   $("#proedit").modal('show');
	$("input").addClass(' form-control');
});
</script>

