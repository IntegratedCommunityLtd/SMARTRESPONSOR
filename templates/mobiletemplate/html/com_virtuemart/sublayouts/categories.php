<?php
defined('_JEXEC') or die('Restricted access');
// Макет публикации на главной
$categories = $viewData['categories'];
?>
<style>
.z3-10, .z11-12 {padding:0!important;}
.masonry-brick {margin: 1px!important;}
.browse{padding: 5px 0px!important;}
</style>

<?php
// Category and Columns Counter
$iCol = 1;
$iCategory = 1;

?>
<div class="row view js-masonry">
    <?php
    // Start the Output
    foreach ($this->categories as $category) {
	    // Category Link
	    $caturl = JRoute::_('index.php?option=ajax&option=com_virtuemart&view=category&virtuemart_category_id=' . $category->virtuemart_category_id, FALSE);

	    // Show Category category ?>
    	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 masonry-brick browse" >
		
    	    <div class="spacer">
				<h2 class="cat-title">
					<a href="<?php echo $caturl ?>" title="<?php echo $category->category_name ?>" class="subcat text-left">
					<?php
					echo $category->images[0]->displayMediaThumb('style="width: 25px;"', false);
					echo ' ';
					echo $category->category_name; ?></a>
				</h2>
<?php /*				
				<div class="cat-content">
					<a href="<?php echo $caturl ?>" title="<?php echo $category->category_name ?>" class="subcat">
				   <?php
					echo $category->images[0]->displayMediaThumb('style="width: 100%;padding: 5px 5px 0px 5px;"', false);
					?>
					</a>
				</div>
*/ ?>
				<?php // Вывод подкатегорий ?>
								<div class="" style="text-align: left;width:100%;padding: 5px;max-width: 200px;">
				<?php
				foreach ($category->childs as $child) {
				$caturl = JRoute::_('index.php?option=com_virtuemart&amp;view=category&amp;virtuemart_category_id='.$child->virtuemart_category_id);
				$cattext = $child->category_name;
				?>
					<a href="<?php echo $caturl ?>" title="<?php echo $child->category_name ?>" class="subcat">
					<?php
				if (!empty($child->images)) {
					echo $child->images[0]->displayMediaThumb("", false);
				}
					echo $child->category_name;
					?></a>
				<?php
				}
				?>

				<?php echo '</div>'; // Подкатегории Конец ?>
    	    </div>
    	</div>
	<?php
	$iCategory++;

	    // Do we need to close the current row now?
        if ($iCol == $categories_per_row) { ?>


		    <?php
		    $iCol = 1;
	    } else {
		    $iCol ++;
	    }
    }
	// Do we need a final closing row tag?
	if ($iCol != 1) { ?>


	<?php
	}
	?>
