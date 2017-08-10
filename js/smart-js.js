// Pagination

jQuery(window).load(function() {

	var $container = jQuery('.view');
	$container.imagesLoaded(function(){
		$container.masonry({
			itemSelector: '.browse',
		});
	});

	

	$container.infinitescroll({
		navSelector  : '.pagination',
		nextSelector : '.pagination-next a',
		itemSelector : '.browse',
		loading: {
			finishedMsg: 'No more pages to load.',
			img: '/images/6RMhx.gif'
		}
	},

	// trigger Masonry as a callback
	function(newElements) {
		var $newElems = jQuery(newElements);
		$newElems.imagesLoaded(function(){
			$container.masonry('appended', $newElems, true);
		});
	});

});

//bootstrap anabled

jQuery(function($){
	// Tooltip enable
//	$('[data-toggle=tooltip]').popover();
	// Popover enable
	$('[data-toggle=popover]').popover();
	// Alert enable	
	$('[data-dismiss=alert]').alert();




// add to favorit
	$('.formfavorit').on('submit', function(){
		$self = this;

		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: $.param($(this).serializeArray()),
			success: function(data) {
				var btn = $($self).find('.btn');
				var res = data.split('###');

				if (!btn.hasClass('in_fav')) {
					btn.addClass('in_fav');
					btn.find('.glyphicon').removeClass('glyphicon-pushpin');
					btn.find('.glyphicon').addClass('glyphicon-bookmark');
					btn.attr('data-original-title', '<?php echo JText::_("VM_REMOVE_FAVORITE"); ?>');
					btn.attr('data-content', '<?php echo JText::_("VM_FAV_TOTAL_LIKES"); ?> ' + res[1] + '<?php echo JText::_("VM_FAV_MORE_PEOPLE"); ?>');
					$($self).find('input[name="mode"]').val('fav_del');
				} else {
					btn.removeClass('in_fav');
					btn.find('.glyphicon').removeClass('glyphicon-bookmark');
					btn.find('.glyphicon').addClass('glyphicon-pushpin');
					btn.attr('data-original-title', '<?php echo JText::_("VM_ADD_TO_FAVORITES"); ?>');
					btn.attr('data-content', '<?php echo JText::_("VM_FAV_TOTAL_LIKES"); ?> ' + res[1] + '<?php echo JText::_("VM_FAV_MORE_PEOPLE"); ?>');
					$($self).find('input[name="mode"]').val('fav_add');
				}
			},
			error: function(error) {
			},
		});
		return false;	
	});

	

//left-side-btn

	$('[data-toggle=offcanvas]').click(function () {
		$('.row-offcanvas').toggleClass('active')
	});

	

//left-move_up-panel
	$(window).scroll(function () {
        if ($(this).scrollTop() > 400) {
            $('a#move_up').fadeIn(600);
		} else {
            $('a#move_up').fadeOut(600);
		}
    });
// move_up	
    $('a#move_up').click(function () {
        $('body,html').animate({
            scrollTop: 0
        }, 0);
        return false;
    });



$(".chzn-select").chosen();
$(".vm-chzn-select").chosen();
$(".joms-select").addClass("vm-chzn-select");

$('#button').click(function(){
		$('#append').load('chosen.html', function(){
			$('.chosennew').chosen().change(function(e){
			console.log($(this).val());
		});
	});

});

// navbar-fixed-top
	$('div.navbar-fixed-top').autoHidingNavbar();
//min-height #wrapper
var viewportHeight = document.documentElement.clientHeight + 'px';
		jQuery('#wrapper').css('min-height', viewportHeight);
	

});
// auto popover.close
//jQuery(document).ready(function() {setTimeout(function () {
 //     div.popover.close();
//}, 4000);});	

// toolbar.submenu
//joms.jQuery(document).ready( function() {
//	joms.jQuery('#community-wrap .cSubmenu li.action:last').addClass('last-child');
//});