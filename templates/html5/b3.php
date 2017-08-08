<?php
defined('_JEXEC') or die;
?>
	<div id="b3" class="col-xs-12 col-sm-6 col-md-6 col-lg-6 z3-10">
		<div class="sidebar-offcanvas navbar-toggle z3 nopaddingleft" id="sidebar" role="navigation" style="padding-right: 0px;position: absolute;" id="myNavmenu">
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
							<? include( JPATH_ROOT .'/components/com_community/templates/smartresponsor/layouts/toolbar/base.php' ); ?>
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