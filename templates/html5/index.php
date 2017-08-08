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
JHtml::_('jquery.framework');
JHtml::_('bootstrap.framework');

include_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php');
?>
<!DOCTYPE html>
<html xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

<jdoc:include type="head" />
<?php require JPATH_BASE . '/templates/html5/head_css.php'; ?>

		<style>
		a#move_up {
			position: fixed;
			top: 0;
			width: 100px;
			height: 100%;
			display: none;
			text-align: center;
			font: bold 12px Verdana, sans-serif;
			text-decoration: none;
			color: #7a7b76;
			padding-top: 10px;
			opacity: 0.7;
			filter: alpha(opacity=70);
			left: 0;
		}
		a#move_up:hover {
			color: #000;
			background: #bdbcb8;
			opacity: 0.8; 
			filter: alpha(opacity=80);
		}

#b1, #b3, #b4 {padding-top:5px;}
.z3-10, .z11-12, #b4 {padding-right:0;}
		</style>
</head>
<body>
				<?php require JPATH_BASE . '/templates/html5/fullscreen_message.php'; ?>
	<div id="wrapper">
				<?php require JPATH_BASE . '/templates/html5/navbar.php'; ?>

		<div class="container-fluid">
			<div id="smarttop" class="row row-offcanvas row-offcanvas-left">
				<?php require JPATH_BASE . '/templates/html5/b1.php'; ?>
				<?php require JPATH_BASE . '/templates/html5/b2.php'; ?>
				<?php require JPATH_BASE . '/templates/html5/b3.php'; ?>
				<?php require JPATH_BASE . '/templates/html5/b4.php'; ?>
			</div>
		</div>
	</div>
		<div class="clear"></div>

	<footer id="footer" class="footer">
		<div class="container-fluid">
				<?php require JPATH_BASE . '/templates/html5/breadcrumbs.php'; ?>
				<?php require JPATH_BASE . '/templates/html5/row_foot.php'; ?>
				<jdoc:include type="modules" name="debug" />
		</div>
	</footer>
				<?php require JPATH_BASE . '/templates/html5/footer_js.php'; ?>
</body>
</html>