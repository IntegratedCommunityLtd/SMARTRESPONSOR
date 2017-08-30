<?php defined ('_JEXEC') or die('Restricted access'); ?>
		<div class="carousel-inner">
			
								<?php
								$count_images 	= count($product->virtuemart_media_id);
								if($count_images >10) {
								for ($i = 0; $i < 10; $i++) {
							   ?>
				<div class="item<?php if ($i == 0) echo ' active'; ?>" style="margin-top: 27px;">
					<a href="/<?php echo $product->images[$i]->file_url ?>" alt="<?php echo $product->product_name ?>" rel="group<?php echo $compaignslideid ?>" tite="<?php echo $product->product_s_desc ?>" data-title="<?php echo $product->product_s_desc ?>" class="fancybox"><img src="/<?php echo $product->images[$i]->file_url ?>" class="compaign-avatar" style="min-height: inherit;"></a>
				</div>
							<?php
								}
								} else {
								for ($i = 0; $i < $count_images; $i++) {
									?>
				<div class="item<?php if ($i == 0) echo ' active'; ?>" style="margin-top: 27px;">
					<a href="/<?php echo $product->images[$i]->file_url ?>" alt="<?php echo $product->product_name ?>" rel="group<?php echo $compaignslideid ?>" tite="<?php echo $product->product_s_desc ?>" data-title="<?php echo $product->product_s_desc ?>" class="fancybox"><img src="/<?php echo $product->images[$i]->file_url ?>" class="compaign-avatar" style="min-height: inherit;"></a>
				</div>
								<?php
								}
								}
								?>

		</div>