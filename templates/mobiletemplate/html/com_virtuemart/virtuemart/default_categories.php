<?php
defined('_JEXEC') or die('Restricted access');
$iCategory			= 1;
?>
<div class="view">
    <?php
    // Start the Output
    foreach ($this->categories as $category) {
	    // Category Link
	    $caturl = JRoute::_('index.php?option=com_ajax&option=com_virtuemart&view=category&virtuemart_category_id=' . $category->virtuemart_category_id, FALSE);

	    // Show Category category ?>
    	<div class="masonry-brick browse">

    	    <div class="spacer">
				<h2 class="cat-title">
					<a href="<?php echo $caturl ?>" title="<?php echo $category->category_name ?>"><?php echo $category->category_name ?>	</a>
				</h2>
				
				<div class="cat-content">
					<a href="<?php echo $caturl ?>" title="<?php echo $category->category_name ?>">
				   <?php
						echo $category->images[0]->displayMediaThumb('style="width: 100%;"', false);
					?>
					</a>
				</div>
				<?php if ($category->childs ) { // Вывод подкатегорий ?>
								<div class="" style="text-align: left;width:100%;padding: 5px;max-width: 200px;">
					<?php
						foreach ($category->childs as $child) {
						$caturl			= JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$child->virtuemart_category_id);
						$cattext		= $child->category_name;
					?>
					<div style="display:block;">
							<a href="<?php echo $caturl ?>" title="<?php echo $child->category_name ?>" class="subcat">
								<?php
								echo $child->images[0]->displayMediaThumb('style="width: 10px"', false);
								echo $child->category_name;
								?>
							</a>
					</div>
				<?php } echo '</div>'; } // Подкатегории Конец ?>
    	    </div>

	<?php
	$iCategory++;
}
?>
	</div>

