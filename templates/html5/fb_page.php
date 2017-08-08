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

<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/<?php echo $this->language; ?>/sdk.js#xfbml=1&version=v2.6&appId=1680419398852277";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="fb-page" data-href="https://www.facebook.com/%D0%AF-%D0%A1%D0%BF%D0%BE%D0%BD%D1%81%D0%BE%D1%80-1203610866337462/" data-tabs="timeline" data-height="416" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/%D0%AF-%D0%A1%D0%BF%D0%BE%D0%BD%D1%81%D0%BE%D1%80-1203610866337462/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/smartresponsorhttps://www.facebook.com/%D0%AF-%D0%A1%D0%BF%D0%BE%D0%BD%D1%81%D0%BE%D1%80-1203610866337462/"><?php echo $sitename; ?></a></blockquote></div>


