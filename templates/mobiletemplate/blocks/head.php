<?php

// no direct access

defined( '_JEXEC' ) or die( 'Restricted access' );
$submenu_type = $this->params->get('submenu_type','0');

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">



<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
<meta name="viewport" content="width=device-width initial-scale=1.0 maximum-scale=1.0 user-scalable=yes" />
   <jdoc:include type="head" />
<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/system/css/system.css" type="text/css" />

<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/system/css/general.css" type="text/css" />
	<link type="text/css" rel="stylesheet" href="<?php echo $this->baseurl;?>/templates/mobiletemplate/css/docs.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo $this->baseurl;?>/templates/mobiletemplate/css/jquery.mmenu.css" />
   	<style type="text/css">
				.mm-ismenu
			{
				background: <?php echo $this->params->get('tpl_color','#333');?>;
            }
#header,.header, #footer{
	background: <?php echo $this->params->get('tpl_header_color','#555');?>;
            }
		</style>


		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<script type="text/javascript" src="<?php echo $this->baseurl;?>/templates/mobiletemplate/js/jquery.mmenu.min.js"></script>
          <script language="javascript" type="text/javascript">jQuery.noConflict();</script>

		<script type="text/javascript">
    
			jQuery(function() {
                jQuery('nav#menu').mmenu({
<?php if($submenu_type ==1) echo 'slidingSubmenus: false';?>
					
				});
			});


			
		</script>


</head>



