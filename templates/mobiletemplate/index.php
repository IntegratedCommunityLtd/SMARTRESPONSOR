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
$view = JRequest::getVar('view', null);
include_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php');
/*
$headlink = $this->getHeadData();
//com_virtuemart
unset($headlink['scripts']['/components/com_virtuemart/assets/js/vmsite.js?vmver=c80ac3e4']);
unset($headlink['scripts']['/components/com_virtuemart/assets/js/fancybox/jquery.fancybox-1.3.4.pack.js?vmver=1.3.4']);
unset($headlink['scripts']['/components/com_virtuemart/assets/js/vmprices.js?vmver=c80ac3e4']);
//mootools
unset($headlink['scripts']['/media/system/js/mootools-more.js']);
unset($headlink['scripts']['/media/system/js/core.js']);
unset($headlink['scripts']['/media/system/js/mootools-core.js?b70e99e74a830f65747cf0e6a7fe7c82']);//не вырубается
unset($headlink['scripts']['/media/system/js/keepalive.js?b70e99e74a830f65747cf0e6a7fe7c82']);//не вырубается
//jquery
unset($headlink['scripts']['/media/jui/js/jquery.min.js?8a81e04eb7ef363799fb62fa68fbc692']);
unset($headlink['scripts']['/media/jui/js/jquery-noconflict.js?8a81e04eb7ef363799fb62fa68fbc692']);
unset($headlink['scripts']['/media/jui/js/jquery-migrate.min.js?8a81e04eb7ef363799fb62fa68fbc692']);
//com_community   НЕ СРАБАТЫВАЕТ
//unset($headlink['scripts']['/components/com_community/assets/release/js/loader.js']);
//unset($headlink['scripts']['/components/com_community/assets/release_32/js/bundle.js']);
//unset($headlink['scripts']['/components/com_community/assets/vendors/toolkit.min.js']);
$this->setHeadData($headlink);
*/
?>
<!DOCTYPE html>
<html xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

<jdoc:include type="head" />
<?php 
/*
$this->addScript('/media/jui/js/jquery.min.js');
$this->addScript('/js/jquery-noconflict.js');
$this->addScript('/js/jquery-migrate.js');
*/
$this->addScript('/js/jquery.cookie.js');

/*
$this->addScript('/js/bootstrap.js');
*/
$this->addScript('/js/jquery.bootstrap-autohidingnavbar.js');
/*
//com_community НЕ СРАБАТЫВАЕТ
//$this->addScript('/components/com_community/assets/release/js/loader.js');
//$this->addScript('/components/com_community/assets/release_32/js/bundle.js');
//$this->addScript('/components/com_community/assets/vendors/toolkit.min.js');
//VM
if ($option == 'com_virtuemart'){
$this->addScript('/js/vmsite.js');
$this->addScript('/js/jquery.fancybox-1.3.4.pack.js');
$this->addScript('/js/vmprices.js');	
	}
*/
?>	

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
<?php
	if ($view == "registration" or $view == "login"){
	} else {
		require JPATH_BASE . '/templates/html5/fullscreen_message.php';
		}
?>
	<div id="wrapper">
<?php
	if ($view == "registration" or $view == "login"){
	} else {
		require JPATH_BASE . '/templates/html5/navbar.php';
		}
?>	
		<div class="container-fluid">
			<div id="smarttop" class="row row-offcanvas row-offcanvas-left">
<?php

	if ($view == "registration" or $view == "login"){
	} else {
		require JPATH_BASE . '/templates/html5/b1.php';
		}

	if ($view == "registration" or $view == "login"){
	} else {
		require JPATH_BASE . '/templates/html5/b2.php';
		}
		require JPATH_BASE . '/templates/html5/b3.php';
	if ($view == "registration" or $view == "login"){
	} else {
		require JPATH_BASE . '/templates/html5/b4.php';
		}		
?>	
			</div>
		</div>
	</div>


<?php
	if ($view == "registration" or $view == "login"){
	} else {
?>
		<div class="clear"></div>
	<footer id="footer" class="footer">
		<div class="container-fluid">
				<?php require JPATH_BASE . '/templates/html5/breadcrumbs.php'; ?>
				<?php require JPATH_BASE . '/templates/html5/row_foot.php'; ?>
				<jdoc:include type="modules" name="debug" />
		</div>
	</footer>

<?
}
?>
				<?php //require JPATH_BASE . '/templates/html5/footer_js.php';
						$this->addScript('/js/smart-js.js');
						$this->addScript('/js/masonry.pkgd.min.js');
						?>
</body>
</html>