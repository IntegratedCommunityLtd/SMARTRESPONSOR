<?php
defined('_JEXEC') or die;
?>

			<ol class="carousel-indicators hidden-xs">
										<?php
											if ($count_images > 10) {
											$i = 0;
											for ($i = 0; $i < 10; $i++) {
											?> 
							<li data-target="#publication" data-slide-to="<?php echo $i ?>" <?php if ($i == 0) echo 'class=" active"'; ?>></li>
										<?php
											}
											} else {
											$i = 0;
											for ($i = 0; $i < $count_images; $i++) {
											?>
							<li data-target="#publication" data-slide-to="<?php echo $i ?>" <?php if ($i == 0) echo 'class=" active"'; ?>></li> 
										<?php
											}
											}
											?>
			</ol>