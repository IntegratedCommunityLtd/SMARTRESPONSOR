<?php

// Check to ensure this file is included in Joomla!
defined ('_JEXEC') or die('Restricted access');

static $ask_recommened_loaded = false;
if($ask_recommened_loaded) return '';

$product = $viewData['product'];
// addon for joomla modal Box
//JHtml::_('behavior.modal');

if(VmConfig::get('usefancy',1)){
	
//	vmJsApi::addJScript( 'fancybox/jquery.fancybox-1.3.4.pack',false);
//	vmJsApi::css('jquery.fancybox-1.3.4');
	$Modal ="
			$('a.ask-a-question, a.printModal, a.recommened-to-friend, a.manuModal').click(function(event){
              event.preventDefault();
		      $.fancybox({
		        href: $(this).attr('href'),
		        type: 'iframe',
		        height: 550,
				centerOnScroll: true
		        });
		      });
			";
	
} else {
	
	vmJsApi::addJScript( 'facebox', false );
	vmJsApi::css( 'facebox' );
    $Modal ="
    		$('a.ask-a-question, a.printModal, a.recommened-to-friend, a.manuModal').click(function(event){
		      event.preventDefault();
		      $.facebox({
		        iframe: $(this).attr('href'),
		        rev: 'iframe|550|550',
				centerOnScroll: true
		        });
		      });
    		"; 
}

vmJsApi::addJScript('popups',"
//<![CDATA[
	jQuery(document).ready(function($) {
		".$Modal."
	});
//]]>
");