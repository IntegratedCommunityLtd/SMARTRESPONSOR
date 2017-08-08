<?php defined('_JEXEC') or die; ?>
<div class="mod-languages<?php echo $moduleclass_sfx ?>">
	<form role="form" id="footer-languages-select" name="lang" method="post" action="<?php echo htmlspecialchars(JUri::current()); ?>">
	
	<div class="form-group">
	<select class="form-control vm-chzn-select" onchange="document.location.replace(this.value);" >
	<?php foreach ($list as $language) : ?>
		<option dir=<?php echo JLanguage::getInstance($language->lang_code)->isRtl() ? '"rtl"' : '"ltr"'?> value="<?php echo $language->link;?>" <?php echo $language->active ? 'selected="selected"' : ''?>>
		<?php echo $language->title_native;?></option>
	<?php endforeach; ?>
	</select>
	</div>
	</form>
</div>
