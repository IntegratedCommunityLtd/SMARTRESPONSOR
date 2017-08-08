<?php
/**
 *
 * Show the Publication details page (Additional images)
 *
 * @package	SmartReSopnsor
 * @subpackage
 * @author Alexandr Tishchenko
 */
// Check to ensure this file is included in!
defined('_JEXEC') or die('Restricted access');
?>
<ol class="carousel-indicators added-thumb hidden-xs">
	<?php
	$start_image = VmConfig::get('add_img_main', 1) ? 0 : 1;
	for ($i = $start_image; $i < count($this->product->images); $i++) {
		$image = $this->product->images[$i];
		?>

			<?php
			if(VmConfig::get('add_img_main', 1)) {
				echo '<li data-target="#publication" data-slide-to="'. $i .'">';				
				echo $image->displayMediaThumb('class="product-image-thumb" style="cursor: pointer"',false,$image->file_description);
				echo '<a href="'. $image->file_url .'"  class="product-image image-'. $i .'" style="display:none;" title="'. $image->file_meta .'" rel="vm-additional-images"></a>';
			} else {
				echo $image->displayMediaThumb("",true,"rel='vm-additional-images'",true,$image->file_description);
				echo '<li data-target="#publication" data-slide-to="'. $i .'"></li>';
			}
			?>

	<?php
	}
	?>
</ol>	
	<div class="clear"></div>