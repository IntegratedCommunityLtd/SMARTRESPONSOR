<?php
/**
 * @package	SmartReSponsor
 * @author Alexandr Tishchenko
 * @link http://www.smartresponsor.com
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
$vendorModel 	= VmModel::getModel ('vendor');
$vendor 		= $vendorModel->getVendor ($this->product->virtuemart_vendor_id);
$vendorModel->addImages ($vendor);

//echo '<pre>'; print_r (  $vendor  ); echo '</pre>'.__FILE__.' ----- '.__LINE__ ;
/* Let's see if we found the product */
if (empty($this->product)) {
	echo vmText::_('COM_VIRTUEMART_PRODUCT_NOT_FOUND');
	echo '<br /><br />  ' . $this->continue_link_html;
	return;
}
// echo shopFunctionsF::renderVmSubLayout('askrecomjs',array('product'=>$this->product));
?>
<div class="productdetails-view productdetails">
	<div id="publication" class="carousel slide joms-focus" data-ride="carousel">
		<div class="carousel-inner">



								<?php
									if(!empty($vendor->file_url) && $vendor->file_url != "/images/stories/virtuemart/vendor/")
									{
										if ($vendor_id == 0) {
										$vebdorJomsOnline = ' joms-online';
										} else {
										$vebdorJomsOnline = ' joms-offline';
										}
								?>
<?php
/*								
			<div class="joms-avatar--focus <?php //$vebdorJomsOnline; ?>">			</div>
*/
?>
				<a id="profile_avatar"><img class="img-thumbnail" src="/<?=$vendor->file_url ?>" alt="<?=$vendor->vendor_name?>"></a>
							
								<?php
									}
								?>

				<h2 class="hidden-xs"><strong><?=$vendor->vendor_name ?></strong></h2>

				<div class="hidden-xs spacer-buy-area">
							<?php
								echo shopFunctionsF::renderVmSubLayout('rating',array('showRating'=>$this->showRating,'product'=>$this->product));
							if (is_array($this->productDisplayShipments))
							{
								foreach ($this->productDisplayShipments as $productDisplayShipment)
								{
								echo $productDisplayShipment . '<br />';
								}
							}
							if (is_array($this->productDisplayPayments))
							{
								foreach ($this->productDisplayPayments as $productDisplayPayment)
								{
								echo $productDisplayPayment . '<br />';
								}
							}
								//In case you are not happy using everywhere the same price display fromat, just create your own layout
								//in override /html/fields and use as first parameter the name of your file
								echo shopFunctionsF::renderVmSubLayout('prices',array('product'=>$this->product,'currency'=>$this->currency));
								// Product Packaging
								$product_packaging = '';
							if ($this->product->product_box)
							{
								echo vmText::_('COM_VIRTUEMART_PRODUCT_UNITS_IN_BOX') .$this->product->product_box;
							}
								echo ($this->product->product_in_stock - $this->product->product_ordered);
							?>
				</div>
		</div>				
	<ul class="nav nav-tabs joms-focus__link">
		<li class="half">
			<div class="progress">
			<div class="progress-bar pbcompaign" style="width: <?php echo $tofinish; ?>%;"></div>
			</div>				
		</li>
		<li class="full liked">
            <a href="javascript:" class="joms-js--like-profile-356" onclick="joms.api.pageLike('profile', '356');" data-lang-like="Like" data-lang-liked="Liked">
                <svg viewBox="0 0 16 20" class="joms-icon">
                    <use class="joms-icon--svg-fixed" xlink:href="http://smartresponsor.com/ru/profile#joms-icon-thumbs-up"></use>
                </svg>
                <span class="joms-js--lang">Like</span>
                <span class="joms-text--light"> 0</span>
            </a>
        </li>
		<li class="pull-right hidden-xs">		
					<?php
								include(JPATH_BASE.'/templates/html5/html/com_wishlist/template/addtofavorites_form.tpl.php');
								echo shopFunctionsF::renderVmSubLayout('addtocart',array('product'=>$this->product));
					?>
        </li>        
    </ul>				

	
	

	</div>

				<div class="visible-xs spacer-buy-area">
							<?php
								echo shopFunctionsF::renderVmSubLayout('rating',array('showRating'=>$this->showRating,'product'=>$this->product));
							if (is_array($this->productDisplayShipments))
							{
								foreach ($this->productDisplayShipments as $productDisplayShipment)
								{
								echo $productDisplayShipment . '<br />';
								}
							}
							if (is_array($this->productDisplayPayments))
							{
								foreach ($this->productDisplayPayments as $productDisplayPayment)
								{
								echo $productDisplayPayment . '<br />';
								}
							}
								//In case you are not happy using everywhere the same price display fromat, just create your own layout
								//in override /html/fields and use as first parameter the name of your file
								echo shopFunctionsF::renderVmSubLayout('prices',array('product'=>$this->product,'currency'=>$this->currency));
								// Product Packaging
								$product_packaging = '';
							if ($this->product->product_box)
							{
								echo vmText::_('COM_VIRTUEMART_PRODUCT_UNITS_IN_BOX') .$this->product->product_box;
							}
								echo ($this->product->product_in_stock - $this->product->product_ordered);

							?>
				</div>								
	<div id="pub-content">

		<div class="joms-sidebar">
			<div id="joms-module--stacked" class="joms-module__wrapper--stacked">
				<div class="joms-module--stacked joms-module__body">
							<?php
								echo shopFunctionsF::renderVmSubLayout('rating',array('showRating'=>$this->showRating,'product'=>$this->product));
							if (is_array($this->productDisplayShipments))
							{
								foreach ($this->productDisplayShipments as $productDisplayShipment)
								{
								echo $productDisplayShipment . '<br />';
								}
							}
							if (is_array($this->productDisplayPayments))
							{
								foreach ($this->productDisplayPayments as $productDisplayPayment)
								{
								echo $productDisplayPayment . '<br />';
								}
							}
								//In case you are not happy using everywhere the same price display fromat, just create your own layout
								//in override /html/fields and use as first parameter the name of your file
								echo shopFunctionsF::renderVmSubLayout('prices',array('product'=>$this->product,'currency'=>$this->currency));
								// Product Packaging
								$product_packaging = '';
							if ($this->product->product_box)
							{
								echo vmText::_('COM_VIRTUEMART_PRODUCT_UNITS_IN_BOX') .$this->product->product_box;
							}
								echo ($this->product->product_in_stock - $this->product->product_ordered);
							?>
					<div class="visible-xs">							
							<?php
								include(JPATH_BASE.'/templates/html5/html/com_wishlist/template/addtofavorites_form.tpl.php');
								echo shopFunctionsF::renderVmSubLayout('addtocart',array('product'=>$this->product));
							?>
					</div>
				</div>
				<div class="joms-module--stacked joms-module__body">				
							<?php
								$product_in_stock = $product->product_in_stock;
								$product_sales = $product->product_sales;
								$tofinish = 100 * $product_sales / $product_in_stock;
							?>
					<div class="row infoofcompaign">
						  <div class="col-xs-4"><?php echo $product_sales; ?></div>
						  <div class="col-xs-2"><?php echo JText::_ ( 'COM_VIRTUEMART_PRODUCT_SALES' ); ?>:</div>
						  <div class="col-xs-2"></div>
						  <div class="col-xs-2"><b><?php echo round($tofinish,0); ?></b>%</div>
					</div>
					<div class="progress">
						<div class="progress-bar pbcompaign" style="width: <?php echo $tofinish; ?>%;"></div>
					</div>				
				</div>
			
			</div>
		</div>
			<div class="joms-main">
				<div class="joms-stream__container">
				<div class="joms-stream">
					<h2 class="" style="color: #232323;margin: 0 10px 0;"><?php echo $this->product->product_name ?></h2>
						<?php
							if (!empty($this->product->product_s_desc))
							{
						?>
					<div class="product-short-description">						
						<h6 class="hidden-xs" style="color: #232323; margin: 0 10px 0;">
									<?php echo $this->product->product_s_desc; ?>

						</h6>
					</div>	
						<?php
							}
						?>				
				</div>					
				
				
				
			<?php // onContentAfterDisplay event
					echo $this->product->event->afterDisplayContent;
			?>			
				</div>
			</div>

	


		<div class="vm3pr-">
			<?php echo shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$this->product,'position' => array('goal', 'www'))); ?>
		</div>
			
			<?php echo shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$product,'position'=>'www'));	
				if (!empty($this->product->customfieldsSorted['goal']))
				{
					$this->position='goal';
					echo $this->loadTemplate('customfields');
				}
			?>		


	</div>		

<?php 


//echo vmJsApi::writeJS();

if ($this->product->prices['salesPrice'] > 0)
{
  echo shopFunctionsF::renderVmSubLayout('snippets',array('product'=>$this->product, 'currency'=>$this->currency, 'showRating'=>$this->showRating));
}
?>
</div>