<?php
defined('_JEXEC') or die;
									if ($this->product->product_special) {
										echo '<div class="featur"><span class="">'.JText::_( 'COM_VIRTUEMART_PUBLICATION_TYPE' ).'</span></div>';
									}	
?>

		<div class="carousel-inner">
								<?php
									$count_images = count ($this->product->images);
									if ($count_images > 10) {
									$i = 0;
									for ($i = 0; $i < 10; $i++) {
									?>
				<div class="item<?php if ($i == 0) echo ' active'; ?>" style="background-image: url(/<?php echo $this->product->images[$i]->file_url ?>);background-size: contain;background-origin: padding-box;background-repeat: no-repeat;">
					<a href="/<?php echo $this->product->images[$i]->file_url ?>" alt="<?php echo $this->product->product_name ?>" rel="group1" tite="<?php echo $this->product->product_s_desc ?>" data-title="<?php echo $this->product->product_s_desc ?>" class="fancybox">
						<img src="/<?php echo $this->product->images[$i]->file_url ?>" class="pu">
					</a>
				</div>
								<?php
									}
									} else {
									$i = 0;
									for ($i = 0; $i < $count_images; $i++) {
									?>
									
				<div class="item<?php if ($i == 0) echo ' active'; ?>" style="background-image: url(/<?php echo $this->product->images[$i]->file_url ?>);background-size: contain;background-origin: padding-box;background-repeat: no-repeat;">
						<a href="/<?php echo $this->product->images[$i]->file_url ?>" alt="<?php echo $this->product->product_name ?>" rel="group1" tite="<?php echo $this->product->product_s_desc ?>" data-title="<?php echo $this->product->product_s_desc ?>" class="fancybox">
							<img src="/<?php echo $this->product->images[$i]->file_url ?>" class="pu">
						</a>
				</div>

								<?php
									}
									}
									?>
		</div>
