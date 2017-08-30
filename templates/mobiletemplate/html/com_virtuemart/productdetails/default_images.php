<?php

defined('_JEXEC') or die('Restricted access');
//	vmJsApi::js( 'fancybox/jquery.fancybox-1.3.4.pack');
//	vmJsApi::css('jquery.fancybox-1.3.4');
	$document = JFactory::getDocument ();
	$imageJS = '
	jQuery(document).ready(function() {
		jQuery("a[rel=vm-additional-images]").fancybox({
			"titlePosition" 	: "inside",
			"transitionIn"	:	"elastic",
			"transitionOut"	:	"elastic"
		});
		jQuery(".additional-images a.product-image.image-0").removeAttr("rel");
		jQuery(".additional-images img.product-image").click(function() {
			jQuery(".additional-images a.product-image").attr("rel","vm-additional-images" );
			jQuery(this).parent().children("a.product-image").removeAttr("rel");
			var src = jQuery(this).parent().children("a.product-image").attr("href");
			jQuery(".main-image img").attr("src",src);
			jQuery(".main-image img").attr("alt",this.alt );
			jQuery(".main-image a").attr("href",src );
			jQuery(".main-image a").attr("title",this.alt );
			jQuery(".main-image .vm-img-desc").html(this.alt);
		}); 
	});
	';
	
$document->addScriptDeclaration ($imageJS);

if (!empty($this->product->images)) {
	$image = $this->product->images[0];
	?>
	<div class="main-image">

		<?php
		echo $image->displayMediaFull("",true,"rel='vm-additional-images'");
		?>

		<div class="clear"></div>
	</div>
	<?php
	$count_images = count ($this->product->images);
	if ($count_images > 1) {
		?>
		<div class="additional-images">
			<?php
			$start_image = VmConfig::get('add_img_main', 1) ? 0 : 1;
			for ($i = $start_image; $i < $count_images; $i++) {
				$image = $this->product->images[$i];
				?>
				<div class="floatleft">
					<?php
					if(VmConfig::get('add_img_main', 1)) {
						echo $image->displayMediaThumb('class="product-image" style="cursor: pointer"',false,"");
						echo '<a href="'. $image->file_url .'"  class="product-image image-'. $i .'" style="display:none;" title="'. $image->file_meta .'" rel="vm-additional-images"></a>';
					} else {
						echo $image->displayMediaThumb("",true,"rel='vm-additional-images'");
					}
					?>
				</div>
			<?php
			}
			?>
			<div class="clear"></div>
		</div>
	<?php
	}
}
// Showing The Additional Images END ?>