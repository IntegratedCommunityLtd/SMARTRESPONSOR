<?php
defined('_JEXEC') or die;
$app				= JFactory::getApplication();
$doc				= JFactory::getDocument();
$user				= JFactory::getUser();
$option   			= $app->input->getCmd('option', '');
$view     			= $app->input->getCmd('view', '');
$layout   			= $app->input->getCmd('layout', '');
$task     			= $app->input->getCmd('task', '');
$itemid   			= $app->input->getCmd('Itemid', '');
$sitename 			= $app->getCfg('sitename');
$this->language		= $doc->language;
$this->direction	= $doc->direction;
?>
<script src="https://apis.google.com/js/platform.js" async defer>
  {lang: '<?php echo $this->language; ?>'}
</script>
<div class="g-community" data-href="https://plus.google.com/communities/115578168363282977719"></div>