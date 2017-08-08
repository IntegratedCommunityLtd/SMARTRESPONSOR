<?php
/**
 * @package	SmartReSponsor
 * @author Alexandr Tishchenko
 * @link http://www.smartresponsor.com
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
//JHtml::_('bootstrap.carousel', 'publication', array('interval'=>500, 'pause'=>'hover'));
$vendorModel 	= VmModel::getModel ('vendor');
$vendor 		= $vendorModel->getVendor ($this->product->virtuemart_vendor_id);
$vendorModel->addImages ($vendor);

/* Let's see if we found the product */
?>
		<link rel="stylesheet" type="text/css" href="/css/pablication-carusel.css" />
		<link rel="stylesheet" type="text/css" href="/css/pablication-carusel-component.css" />	

<div class="productdetails-view productdetails">
			<div id="namedesc" class="panel-body-padding-none">
				<h3 class="text-left font-variant-h3"><?php echo $this->product->product_name ?></h3>
				<?php
					if (!empty($this->product->product_s_desc)){	?>
												
							<h6 class="text-left hidden-xs">
										<?php echo $this->product->product_s_desc; ?>

							</h6>
						
							<?php
								}
							?>		
			</div>
	<div id="publication" class="carousel slide joms-focus" data-ride="carousel">
			<?php require JPATH_BASE . '/templates/html5/html/com_virtuemart/productdetails/carousel.php'; ?>
			<?php require JPATH_BASE . '/templates/html5/html/com_virtuemart/productdetails/carousel_ol.php'; ?>
			
	
	</div>					
				<div id="seurce">
					<div class="btn-group-vertical componenta">
							
							<?php
								echo shopFunctionsF::renderVmSubLayout('prices',array('product'=>$this->product,'currency'=>$this->currency));
								$product_in_stock = $product->product_in_stock;
								$product_sales = $product->product_sales;
								$tofinish = 100 * $product_sales / $product_in_stock;
							?>
					<div class="row infoofcompaign">
						  <div class="col-xs-1"><?php echo $product_sales; ?></div>
						  <div class="col-xs-5"><?php echo JText::_ ( 'COM_VIRTUEMART_PRODUCT_SALES' ); ?>:</div>
						  <div class="col-xs-2"></div>
						  <div class="col-xs-2"><b><?php echo round($tofinish,0); ?></b>%</div>
					</div>
					<div class="progress">
						<div class="progress-bar pbcompaign" style="width: <?php echo $tofinish; ?>%;">
						</div>
					</div>								
					</div>
					<?php
//								include(JPATH_BASE.'/templates/html5/html/com_wishlist/template/addtofavorites_form.tpl.php');					
								//echo shopFunctionsF::renderVmSubLayout('addtocart',array('product'=>$this->product));
//								echo shopFunctionsF::renderVmSubLayout('addtocart_my',array('product'=>$this->product));
								
								?>
				</div>


		<?php
		// Запрос информации о Спонсорах
//			$query = 'SELECT virtuemart_order_id, created_by, created_on, product_subtotal_with_tax FROM #__virtuemart_order_items WHERE virtuemart_product_id = '.$this->product->virtuemart_product_id.' AND order_status = "C" GROUP BY virtuemart_order_id ORDER BY created_on';
//			$db->setQuery($query);
//			$orders = $db->loadObjectList();
//			$count_sponsors = count ($orders);
		//	echo $count_sponsors;
		?>
				
				<ul class="nav nav-tabs joms-focus__link">
					<li id="sponsors" class="half <?php if ($count_sponsors != 0) echo ' active'; ?>">
					<a data-toggle="tab" href="#panel1" ><?php echo vmText::_('COM_VIRTUEMART_ALL_SPONSORS') ?><?php if ($count_sponsors != 0) echo '<span class="badge joms-text--light">' . $count_sponsors. '</span>'; ?></a>
					</li>
					<li id="description" class="half <?php if ($count_sponsors == 0) echo ' active'; ?>">
						<a data-toggle="tab" href="#panel2"><?php echo JText::_ ( 'COM_VIRTUEMART_PRODUCT_DESC_TITLE' ); ?><span class="joms-text--light"></span></a>
					</li>					
					<li id="comments" class="half">
						<a data-toggle="tab" href="#panel3"><?php echo vmText::_('SMART_TEMPLATE_COMMENTS') ?></a>
					</li>

					
					<li id="termsofservice" class="half">
						<a data-toggle="tab" href="#panel4"><span class="joms-text--light"><?php echo vmText::_('COM_VIRTUEMART_VENDOR_TOS') ?></span></a>
					</li>
					<li id="tofinish" class="half">
					<a data-toggle="tab" href="#"><span><?php echo JText::_ ( 'COM_VIRTUEMART_PRODUCT_SALES' ); ?><span/><span class="joms-text--light"><?php //echo round($tofinish,0); ?></span></a>
					</li>
				
				</ul>			
			<div class="tab-content">
		<div id="panel1" class="tab-pane <?php if ($count_sponsors != 0) echo ' active'; ?>" style="padding: 15px;">
				<?php
/*					
					if ($orders) {
					//include_once(JPATH_ROOT.'/components/com_community/libraries/core.php');
						$currency_model = VmModel::getModel('currency');
						$displayCurrency = $currency_model->getCurrency($this->product->product_currency);

						echo '<div id="js-masonry" class="view">';
							$categories_per_row = VmConfig::get ('categories_per_row', 4);
							$cellwidth = 'width: ' . floor(99 / $categories_per_row).'%;max-width:200px!important;min-width:160px!important;';
							$compaignslideid = 1;
						foreach ($orders as $order) {
							$user_id = $order->created_by;
							$user_cb = CFactory::getUser($user_id);
							$isOnline = $user_cb->isOnline();
							
							$r = rand(16,255);
							$g = rand(16,255);
							$b = rand(16,255);
							echo '<div id="products" class="browse" style="background:rgba('.$r.','.$g.','.$b.', 0.2)">222';
							$rn = rand(16,255);
							$gn = rand(16,255);
							$bn = rand(16,255);
							echo '<div id="compaign'.$compaignslideid.'" class="carousel slide" data-interval="false">';
							if ($isOnline) { 
								$jomsonlines = ' joms-online';
								$cover = '//smartresponsor.com/components/com_community/assets/cover-undefined-default.png';				
							} else {
								$jomsonlines = ' joms-offline';
								$cover = '/components/com_community/assets/cover-undefined-default.png';
							}
							echo '<div class="joms-focus__cover" style="height: 64px;">';
							echo '<div class="joms-focus__cover-image joms-js--cover-image">';
							echo '<img src="'.$cover.'" alt="'.$user_cb->getDisplayName().'" style="width:100%;top:0">';
							echo '</div>';//cover-image	

							
							echo '<div class="joms-focus__header">';
								$link_cb = CRoute::_('index.php?option=com_ajax&option=com_community&view=profile&userid='.$user_id);

									
							if ($user_id == 0) {
							echo '<div class="joms-avatar--focus'.$jomsonlines.'" style="top:0; padding: 5px;"><img src="'.$user_cb->getThumbAvatar().'"/></div>';								
							} else {
									echo '<div class="joms-avatar--focus'.$jomsonlines.'" style=" top:0;padding: 5px;"><img src="'.$user_cb->getThumbAvatar().'" /></div>';						
							}
							//echo '<div class="joms-focus__title"><a target="_blank" href="'.$link_cb.'">'.$user_cb->getDisplayName().'</a></div>';				
							echo '</div>';//header
							echo '</div>';//cover
							echo '<div class="joms-focus__title"><a target="_blank" href="'.$link_cb.'">'.$user_cb->getDisplayName().'</a></div>';	
							//echo '<div class="ub_id">ID '.$user_id.'</div>';
							//echo '<div class="ub_price">'.number_format($order->product_subtotal_with_tax, 0, '.', ' ').$displayCurrency->currency_symbol.'</div>';
							//echo '<div class="ub_date">'.date('Y-m-d', strtotime($order->created_on)).'</div>';
							echo '</div>';//Sponsor
							echo '</div>';//browse
								
						}
						echo '</div>';//masonery
						echo '<script>
								jQuery(function($){
										var explode = function(){
											$(".view") .masonry("layout")
											};									
											$("#sponsors").click(function(){
										setTimeout(explode, 10);
										});
								});
								</script>';	
								
					}
*/					
				?>
				
		</div>
		<div id="panel2" class="tab-pane <?php if ($count_sponsors == 0) echo ' active'; ?>">
					<div class="publication-description">
						<?php /** @todo Test if content plugins modify the product description */ ?>
						<?php echo $this->product->product_desc; ?>
					</div>
		</div>
		<div id="panel3" class="tab-pane">
			<?php // onContentAfterDisplay event
					echo $this->product->event->afterDisplayContent;
			?>	
		</div>	
		<div id="panel4" class="tab-pane">
			<?php $vendor->vendor_terms_of_service; ?>	
		</div>
	</div>			
	
</div>				

		
		

<?php 
// echo vmJsApi::writeJS();
?>
<?php 
$view = JRequest::getVar('view', null); if ($view == "productdetails"){
echo "		<script>
			jQuery(function($){
				$('#add').prepend($('#seurce'));
			});
			</script>";
}
?>	
							<script>
							jQuery(document).ready(function($) {
									$("a.fancybox").fancybox({
										"showCloseButton"	: true,
										"showNavArrows"		: true,
								});
							});
							</script>