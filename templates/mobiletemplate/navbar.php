<?php
defined('_JEXEC') or die;
?>
<div id="navbar" class="navbar navbar-default navbar-fixed-top z0" role="navigation">
	<div class="row">
			<div class="col-xs-2 col-sm-1 col-md-2 col-lg-1" style="margin: 0;padding:0px;">
				<button type="button" class="btn btn-primary navbar-toggle pull-left" data-toggle="offcanvas" data-target="#myNavmenu" data-canvas="body">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>	
			</div>
		<div class="hidden-xs col-sm-1 col-md-1 col-lg-2">
		<h2><a class="navbar-brand" href="<?php echo JURI::base(); ?>" title="<?php echo $sitename; ?>"><?php echo $sitename; ?></a></h2>
		</div>
		<div class="col-xs-10 col-sm-10 col-md-8 col-lg-7 right nomargin nopadding">
			<div style="margin-left:5px;">
			<jdoc:include type="modules" name="navbar-search" />								
			</div>
			<ul class="nav navbar-nav navbar-right pane nomargin nopadding">
							<jdoc:include type="modules" name="navbar-sign" />
							<jdoc:include type="modules" name="navbar-avatar" />
							<jdoc:include type="modules" name="navbar-main-menu" />
					<?php if ($user->guest) {} else {
					include( JPATH_ROOT .'/components/com_community/templates/jomsocialsmart/layouts/toolbar/base.php' ); ?>
					
					<li class="dropdown">

						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-chevron-down"></span></a>
							<jdoc:include type="modules" name="navbar-other-menu" />
					</li>
					<?php } ?>
				</ul>							
		</div>
		<div class="col-xs-hidden col-sm-hidden col-md-1 col-lg-2"></div>
	</div>
</div>