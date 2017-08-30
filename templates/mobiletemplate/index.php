<?php


// no direct access

defined( '_JEXEC' ) or die( 'Restricted access' );

$app = JFactory::getApplication();

include_once (dirname(__FILE__).'/blocks/head.php');

?>

	<body>
		<div id="page">
			<div id="header">
				<a href="#menu"></a>
			<?php echo htmlspecialchars($app->getCfg('sitename'));?>
				
			</div>
			<div id="content">
            	<jdoc:include type="modules" name="<?php echo $this->params->get('content_top','content_top');?>" />
            <jdoc:include type="message" />
			<jdoc:include type="component" />
			</div>
            <div id="footer">
            <jdoc:include type="modules" name="footer"/>
            </div>
			<nav id="menu">
				<jdoc:include type="modules" name="<?php echo $this->params->get('nav_module','mobile_nav');?>" />
			</nav>
			
		</div>
	</body>
</html>
