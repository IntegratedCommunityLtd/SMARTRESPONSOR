<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$iCategory			= 1;
$categories_per_row = VmConfig::get('homepage_categories_per_row', 3);
$cellwidth = 'width: ' . floor(99 / $categories_per_row).'%;max-width:198px!important;min-width:198px!important;';


/* ID for jQuery dropdown */
$ID = str_replace('.', '_', substr(microtime(true), -8, 8));
$js="
//<![CDATA[
jQuery(document).ready(function() {
		jQuery('#VMmenu".$ID." li.VmClose ul').hide();
		jQuery('#VMmenu".$ID." li .VmArrowdown').click(
		function() {

			if (jQuery(this).parent().next('ul').is(':hidden')) {
				jQuery('#VMmenu".$ID." ul:visible').delay(500).slideUp(500,'linear').parents('li').addClass('VmClose').removeClass('VmOpen');
				jQuery(this).parent().next('ul').slideDown(500,'linear');
				jQuery(this).parents('li').addClass('VmOpen').removeClass('VmClose');
			}
		});
	});
//]]>
" ;

		$document = JFactory::getDocument();
		$document->addScriptDeclaration($js);?>

		<style>
.z3-10, .z11-12 {padding:0!important;}
</style>
<?php //Вспомогательные кнопки ?>
<div class="other-btn-panel btn-group pull-right">
	<button id="check-all" href="#" class="btn btn-primary" disabled="disabled"><?php echo vmText::_ ( 'COM_VIRTUEMART_CHECK_ALL' ); ?></button>
	<button id="fullscreen-btn" href="#" onclick="openbox('b1'); return false" class="btn btn-primary hidden-xs"><?php echo vmText::_ ( 'COM_VIRTUEMART_FULLSCREEN' ); ?></button>	
</div>
<div class="clear"></div>
<div class="view js-masonry">
    <?php
    // Start the Output
    foreach ($this->categories as $category) {
		$caturl			= JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$category->virtuemart_category_id);
		$cattext		= $category->category_name;
		if (in_array( $category->virtuemart_category_id, $parentCategories));

	    // Show Category category ?>

    	<div class="vmvthumb browse" >
    	    <div class="spacer">
				<h2 class="cat-title">
					<a href="<?php echo $caturl ?>" title="<?php echo $category->category_name ?>">
						<span class="s" data-hover="<?php echo $category->category_name ?>"><?php echo $category->category_name ?></span>
					</a>
				</h2>
				
				<div class="cat-content hidden-xs">
					<a href="<?php echo $caturl ?>" title="<?php echo $category->category_name ?>">
				   <?php
					if (!empty($category->images)) {
						echo $category->images[0]->displayMediaThumb("style=\"max-width:198px!important;\"", false);
					}
					?>
					</a>
				</div>
					<?php
					if ($category->childs ) {
						?>
				<div class="hidden-xs" style="text-align: left;width:100%;padding: 5px;max-width: 198px;">
					<div style="display:block;">
						<?php
						foreach ($category->childs as $child) {
						$caturl 	= JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$child->virtuemart_category_id);
						$cattext 	= vmText::_($child->category_name);
							?>
					<a href="/<?php echo $caturl ?>" title="<?php echo $child->category_name ?>"><?php echo $child->category_name ?></a>		
						<?php //echo JHTML::link($caturl, $cattext); ?>
						<?php
						}
						?>
					</div>
				</div>
						<?php
						}
						?>				

				
    	    </div>
    	</div>
					<?php
					$iCategory++;
	}
	?>
</div>