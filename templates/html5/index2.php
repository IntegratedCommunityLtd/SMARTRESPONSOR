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
include_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php');
?>
<!DOCTYPE html>
<html xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<script type="text/javascript" src="//smartresponsor.com/js/jquery.js"></script>
<script type="text/javascript" src="//smartresponsor.com/media/jui/js/jquery-migrate.min.js"></script>
<script type="text/javascript" src="//smartresponsor.com/media/system/js/modal.js"></script>
<script type="text/javascript" async src="//smartresponsor.com/js/jquery.fullPage.js"></script>
<script type="text/javascript" defer src="//smartresponsor.com/js/jquery.slimscroll.min.js"></script>
<script type="text/javascript" defer src="//smartresponsor.com/js/eskju.jquery.scrollflow.min.js"></script>
<jdoc:include type="head" />

<link rel="stylesheet" href="/components/com_komento/themes/wireframe/css/style.css" />
<link rel="stylesheet" href="/components/com_komento/assets/css/syntaxhighlighter/default.css" />
<!-- <link rel="stylesheet" type="text/css" href="/components/com_jsjobs/css/style.css" /> -->
<link rel="stylesheet" type="text/css" defer href="/css/smfaq.css" />
<link rel="stylesheet" type="text/css" defer href="/css/docs.min.css" />
<link rel="stylesheet" type="text/css" defer href="/css/smfaq_edit.css" />
<link rel="stylesheet" type="text/css" href="/css/chosen.css" />
<link rel="stylesheet" type="text/css" href="/css/login-inline-compact.css" />
<link rel="stylesheet" type="text/css" href="/css/template-turquoise.css" />
<link rel="stylesheet" type="text/css" href="/css/ltr-site.css" />
<!-- <link rel="stylesheet" type="text/css" href="/components/com_community/templates/jomsocial/assets/css/style.css" /> -->
<link rel="stylesheet" type="text/css" defer href="/css/style.min.css" />
<!-- <link rel="stylesheet" type="text/css" defer href="/modules/mod_cf_filtering/assets/style.css" /> -->
<link rel="stylesheet" type="text/css" defer href="/css/style.css" />
<!-- <link rel="stylesheet" type="text/css" defer href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" /> -->
<!-- <link rel="stylesheet" type="text/css" defer href="/components/com_virtuemart/assets/css/vm-ltr-common.css?vmver=9058" /> -->
<link rel="stylesheet" type="text/css" defer href="/css/vm-ltr-common.min.css?vmver=9058" />
<link rel="stylesheet" type="text/css" defer href="/css/ltr-site.css?vmver=9058" />
<!-- <link rel="stylesheet" type="text/css" defer href="/components/com_virtuemart/assets/css/vm-ltr-reviews.css?vmver=9058" /> -->
<link rel="stylesheet" type="text/css" defer href="/css/vm-ltr-reviews.min.css?vmver=9058" />
<link rel="stylesheet" type="text/css" defer href="/css/jquery.fancybox-1.3.4.css" />
<link rel="stylesheet" type="text/css" defer href="/css/bundle.css" />
<!-- <link rel="stylesheet" type="text/css" defer  href="/css/autocomplete.css" /> -->
<link rel="stylesheet" type="text/css" defer href="/css/module.css" />
<link rel="stylesheet" type="text/css" href="/css/style.css" />
<link rel="stylesheet" type="text/css" href="/css/smart-community.css" />
<link rel="stylesheet" type="text/css" href="/css/old.css" />

<link rel="stylesheet" type="text/css" href="/css/bootstrap.css" />
<!-- <link rel="stylesheet" type="text/css" href="/css/dropdowns.css" /> -->
<link rel="stylesheet" type="text/css" href="/css/offcanvas.css" />
<link rel="stylesheet" type="text/css" href="/css/smartresponsor.css" />
<link rel="stylesheet" type="text/css" href="/css/smarticon.css" />
<!-- <link rel="stylesheet" type="text/css" acync href="/media/mod_languages/css/template.css" /> -->
<link rel="stylesheet" type="text/css" acync href="/css/jkattackment.min.css" />


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
<div class="container-fluid" style="padding-left:0 !important; padding-right:0 !important">
<div id="wrapper">
<div class="container">
<div id="navbar" class="navbar navbar-default navbar-fixed-top z0" role="navigation">

	<div class="row">
			<div class="col-sm-1" style="margin: 0;padding:0px;">
				<button type="button" class="btn btn-primary navbar-toggle" data-toggle="offcanvas" data-target="#myNavmenu" data-canvas="body">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>	
			</div>
		<div class="col-sm-1 hidden-xs" style="width:165px;padding-right: 5px;padding-left: 0px;margin-left: -15px;float: left;margin: 0;"></div>
		<div class="col-sm-8 hidden-xs" style="margin: 0;padding:0px;">
			

	<div class="row">
	<div class="col-sm-5">
	<jdoc:include type="modules" name="navbar-search" class="col-sm-5"/>
	</div>							
							
								
	<div class="col-sm-7">							
								
				<ul class="nav navbar-nav navbar-right hidden-xs">
							<jdoc:include type="modules" name="navbar-sign" />
<jdoc:include type="modules" name="navbar-avatar" />
<jdoc:include type="modules" name="navbar-main-menu" />
					<?php if ($user->guest) {} else {
					include( JPATH_ROOT .'/components/com_community/templates/jomsocial/layouts/toolbar/base.php' ); ?>
					<li class="hidden-xs">
						<a href="#" data-toggle="dropdown"><span class="glyphicon glyphicon-chevron-down"></span></a>
							<jdoc:include type="modules" name="navbar-other-menu" />
					</li>
					<?php } ?>
				</ul>
												
				</div>								
												
				</div>								
		</div>
		<div class="col-sm-2 hidden-xs"></div>
	</div>
</div>
</div>
<div class="container">
<div id="smarttop" class="row row-offcanvas row-offcanvas-left">
	<div id="b1" class="col-sm-1 z1 visible-md visible-lg" style="margin: 0;padding:0px;">
		<div class="navbar-collapse collapse navbar-responsive-collapse">
		

			<a id="move_up" href="#"><span class="glyphicon glyphicon-chevron-up"></span> Вверх</a>
		</div>
	</div>
			<?php
				$b = 1;
				$width_main_col = 6;
					if($this->countModules('left-panel-menu')) :
				$b = 2;
				$width_main_col = 7;
			 ?>
	<div id="b2" class="col-sm-2 z2" style="padding-right: 5px; padding-left: 0; float: left; margin-right: 0;">
		<div id="left" class="navbar-collapse collapse navbar-responsive-collapse">
			<jdoc:include type="modules" name="left-panel-menu" style="smart" />
		</div>
	</div>
			<?php endif; ?>

	<div id="b3" class="col-sm-<?php echo $width_main_col ?> z3-10">
		<div class="sidebar-offcanvas navbar-toggle z3" id="sidebar" role="navigation" style="padding-right: 0px;padding-left: 0px;position: absolute;" id="myNavmenu">
			<div id="left">
				<jdoc:include type="modules" name="leftsearch" style="smart" />
					<?php
						if ($user->guest) {
					?>
						<ul class="visible-xs">
								<jdoc:include type="modules" name="navbar-sign" />
						</ul>		
					<?php
						} else { 	
					?>		
						<ul class="nav navbar-nav visible-xs pane">
						<script>
						//jQuery(document).ready(function($){
						//	$("#t").clone().appendTo('#l');
						//});
						</script>
							<? //include( JPATH_ROOT .'/components/com_community/templates/jomsocial/layouts/toolbar/base.php' ); ?>
						</ul>
								<jdoc:include type="modules" name="left-panel-menu" />
								<jdoc:include type="modules" name="navbar-other-menu" />
					<?php
						}	
					?>								
			</div>
		</div>
			<div id="nent">
				<jdoc:include type="message" />
				<jdoc:include type="component" />
			</div>
	</div>
	<div id="b4" class="col-sm-2 z11-12 visible-md visible-lg hidden-xs">
		<jdoc:include type="modules" name="right" style="smart" />
		<jdoc:include type="modules" name="jbolo" />
	</div>
</div>
</div>
</div>
<div class="clear"></div>

<footer id="footer" class="footer">

<div class="row hidden-phone hidden-xs" style="background-color:#F5F5F5;">
	<div class="col-sm-1 hidden-phone hidden-xs"></div>
	<div class="col-lg-11 z3-10 hidden-phone hidden-xs"><jdoc:include type="modules" name="breadcrumbs" /></div>
	<div class="col-lg-1 hidden-phone hidden-xs"></div>
</div>
<div class="row foot">
    <div class="col-sm-1 f1 hidden-xs"></div>
    <div class="col-lg-2 f2-3 hidden-xs"><jdoc:include type="modules" name="foot2-3" /></div>
	<div class="col-lg-2 f4-5 hidden-xs"><jdoc:include type="modules" name="foot4-5" style="smart" /></div>
	<div class="col-lg-2 f6-7 hidden-xs"><jdoc:include type="modules" name="foot6-7" style="smart" /></div>
	<div class="col-lg-2 f8-9 hidden-xs"><jdoc:include type="modules" name="foot8-9" style="smart" /></div>
	<div class="col-lg-2 f10-11 hidden-xs"><jdoc:include type="modules" name="foot10-11" style="smart" />
			<?php if($this->countModules('rightscrolltop')) : ?>
				<jdoc:include type="modules" name="rightscrolltop" />
			<?php endif; ?>
	</div>
	<div class="col-lg-1 f12"><jdoc:include type="modules" name="foot11-12" /></div>
</div>
<div class="row foot">
    <div class="col-sm-1 f10"></div>
    <div class="col-lg-2 f20-30"><jdoc:include type="modules" name="foot20-30" /></div>
	<div class="col-lg-2 f40-50 hidden-xs"><jdoc:include type="modules" name="foot40-50" style="smart" /></div>
	<div class="col-lg-2 f60-70 hidden-xs"><jdoc:include type="modules" name="foot60-70" style="smart" /></div>
	<div class="col-lg-2 f80-90 hidden-xs"><jdoc:include type="modules" name="foot80-90" style="smart" /><?php //require JPATH_BASE .  '/templates/html5/vk_group.php'; // Группа в VK ?></div>
	<div class="col-lg-2 f100-110 hidden-xs"><jdoc:include type="modules" name="foot100-110" style="smart" /></div>
	<div class="col-lg-1 f120 hidden-xs"><jdoc:include type="modules" name="foot110-120" /></div>
	<div class="col-lg-1 f120 hidden-xs"><?php //require JPATH_BASE .  '/templates/html5/fb_page.php'; // Страница с ФБ https://www.facebook.com/smartresponsor ?></div>
</div>
<jdoc:include type="modules" name="debug" />

</footer>
</div>

<?php require JPATH_BASE .  '/templates/html5/footer_js.php'; ?>

</body>
</html>