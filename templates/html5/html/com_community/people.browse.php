<?php
/**
* @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author iJoomla.com <webmaster@ijoomla.com>
* @url https://www.jomsocial.com/license-agreement
* The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
* More info at https://www.jomsocial.com/license-agreement
*/
defined('_JEXEC') or die();
//CFactory::load( 'libraries' , 'messaging' );

if( $featuredList && $showFeaturedList )
{

	$firstuser = CFactory::getUser( $featuredList[0] );

?>
	<!-- Slider Kit compatibility -->
		<!--[if IE 6]><?php CFactory::attach('assets/featuredslider/sliderkit-ie6.css', 'css'); ?><![endif]-->
		<!--[if IE 7]><?php CFactory::attach('assets/featuredslider/sliderkit-ie7.css', 'css'); ?><![endif]-->
		<!--[if IE 8]><?php CFactory::attach('assets/featuredslider/sliderkit-ie8.css', 'css'); ?><![endif]-->

		<!-- Slider Kit scripts -->
		<?php CFactory::attach('assets/featuredslider/sliderkit/jquery.sliderkit.1.8.js', 'js'); ?>

		<!-- Slider Kit launch -->
		<script type="text/javascript">
			joms.jQuery(window).load(function(){

				<?php if(JRequest::getVar('limitstart')!="" || JRequest::getVar('sort')!=""){?>
					var target_offset = joms.jQuery("#lists").offset();
					var target_top = target_offset.top;
					joms.jQuery('html, body').animate({scrollTop:target_top}, 200);
				<?php } ?>

				jax.call('community' , 'profile,ajaxShowProfileFeatured' , <?php echo $firstuser->id; ?> );

				joms.jQuery(".featured-user").sliderkit({
					shownavitems:3,
					scroll:<?php echo $config->get('featuredmemberscroll'); ?>,
					// set auto to true to autoscroll
					auto:false,
					mousewheel:true,
					circular:true,
					scrollspeed:500,
					autospeed:10000,
					start:0
				});
				joms.jQuery('.cBoxPad').click(function(){
					var user_id = joms.jQuery(this).parent().attr('id');
					user_id = user_id.split("cPhoto");
					user_id = user_id[1];

					 jax.call('community' , 'profile,ajaxShowProfileFeatured' , user_id );
				});
			});

			function updateFeaturedProfile(userId, username,  likes, avatar,  userLink, userUnfeature, userStatus, friendList ){
				joms.jQuery('#like-container').html(likes);
				joms.jQuery('#user-name').html(username);
				joms.jQuery('#user-status').html(userStatus);
				joms.jQuery('#user-avatar').attr('src',avatar);
				userLink = userLink.replace(/\&amp;/g,'&');
				joms.jQuery('.user-link').attr('href',userLink);
				joms.jQuery('.album-actions').html(userUnfeature);
				joms.jQuery('#friend-list').html(friendList);

			}
		</script>
<? /*
		<div id="cFeatured">

			<!--.user-main-->
			<div class="cFeaturedTop cFeaturedFriends">
				<div class="cFeatured-PageCover cFeaturedThumb cFloat-L">
					<a class="user-link" href="<?php echo CRoute::_('index.php?option=com_community&view=profile&userid=' . $firstuser->id );?>" id="user-link">
						<img id="user-avatar" src="<?php echo $firstuser->getAvatar( 'avatar' ); ?>" />
					</a>

					<?php if( $isCommunityAdmin ){?>
					<b>
						<a title="<?php echo JText::_('COM_COMMUNITY_REMOVE_FEATURED'); ?>" onclick="joms.featured.remove('<?php echo $firstuser->id;?>','search');" href="javascript:void(0);"><?php echo JText::_('COM_COMMUNITY_REMOVE_FEATURED'); ?></a>
					</b>
					<?php } ?>

					<div id="like-container" class="cFeaturedLike"></div>
				</div>

				<!-- member information Information -->
				<div class="cFeaturedInfo Page">
					<!-- Title -->
					<div class="cFeaturedTitle">
						<a href="<?php echo CRoute::_('index.php?option=com_community&view=profile&userid=' . $firstuser->id );?>" class="user-link">
							<span id="user-name"><?php echo $firstuser->getDisplayName(); ?></span>
						</a>
					</div>
					<div id="user-status"><?php echo $firstuser->getStatus(); ?></div>
					<div id="friend-list"></div>
				</div><!--.group-info -->

				<!-- group Top: App Like -->
				<div class="jsApLike">

					<div class="clear"></div>
				</div>
				<!-- end: App Like -->
				<div style="clear: left;"></div>
			</div>

			<!-- navigation container -->
			<div class="cFeaturedBottom cFeaturedContent">
				<!--#####SLIDER#####-->
				<div class="cSlider featured-user">
					<div class="cSlider-Wrap cSlider-nav">
						<div class="cSlider-Clip cSlider-nav-clip">
							<ul class="cSlider-List Groups cFloatedList cResetList clearfix">

								<?php $x = 0; foreach($featuredList as $id) { $user = CFactory::getUser( $id ); ?>
							<li id="cPhoto<?php echo $user->id; ?>" class="<?php echo $user->id;?>">
									 <div id="<?php echo $x; ?>" class="cBoxPad cBoxBorder cBoxBorderLow">

									 	<div class="cFeaturedThumb">
											<a class="cFeaturedAvatar" href="javascript:void(0);">
												<img src="<?php echo $user->getThumbAvatar();?>" alt="<?php echo $user->getDisplayName(); ?>"/>
											</a>
											<?php if( $isCommunityAdmin ) { ?>
											<b>
												<a onclick="joms.featured.remove('<?php echo $user->id;?>','search');" href="javascript:void(0);" title="<?php echo JText::_('COM_COMMUNITY_REMOVE_FEATURED'); ?>" class="album-action remove-featured">
													<i class="com-icon-award-minus"></i>
												</a>
											</b>
											<?php } ?>
										</div>

										<div class="cFeaturedTitle">
											<b><?php echo JHTML::_('string.truncate', strip_tags($user->getDisplayName()),25);?></b>
										</div>

										<div class="cFeaturedMeta Action-Icons">
											<a class="cFeatured-icons" onclick="<?php echo CMessaging::getPopup($user->id); ?>" href="javascript:void(0);" title="<?php echo JText::_('COM_COMMUNITY_INBOX_SEND_MESSAGE'); ?>">
												<i class="com-icon-mail-go"></i>
											</a>
											<?php if( isset($alreadyfriend) && isset($alreadyfriend[$user->id]) ): ?>
											<a class="cFeatured-icons" onclick="joms.friends.connect('<?php echo $user->id; ?>')" href="javascript:void(0)" >
												<i class="com-icon-user-plus"></i>
											</a>
											<?php endif; ?>
										</div>
									</div>
							</li>
							<?php
									$x++;
								} // end foreach
							?>
							</ul>
						</div>
						<div class="cSlider-btn cSlider-nav-btn cSlider-nav-prev"><a href="javascript:void(0);" title="<?php echo JText::_('COM_COMMUNITY_PREVIOUS_BUTTON');?>"><span>Previous</span></a></div>
						<div class="cSlider-btn cSlider-nav-btn cSlider-nav-next"><a href="javascript:void(0);" title="<?php echo JText::_('COM_COMMUNITY_NEXT_BUTTON');?>"><span>Next</span></a></div>
					</div>
				</div><!--.cSlider-->
			</div>
	</div><!--#cFeatured--> */?>
	<div class="clear"></div>
<?php
}
?>
<?php echo $sortings; ?>
<?php if( !empty( $data ) ) { ?>
	<a id="lists" name="listing"></a>
<?/*------------------------------------------------------------------------------------------------------------------------*/?>
<div id="container" class="view js-masonry">
<?php foreach( $data as $row ) : ?>
			<?php $displayname = $row->user->getDisplayName(); ?>
		<?php if(!empty($row->user->id) && !empty($displayname)) : ?>
<div class="browse browsecellwidth" style="<?php echo $browsecellwidth; ?>;<?php $r = rand(16,255); $g = rand(16,255); $b = rand(16,255); ?>background:rgba(<?php echo "$r, $g, $b"; ?>, 0.2)">
	<div class="compaign-name" style="background:rgba('.$rn.','.$gn.','.$bn.', 0.3)">
		<h6>
			<?php // echo $product->product_name; ?>
				<a href="<?php // echo $product_url ?>" title="<?php // echo $product->product_name; ?>" class="product-details"><?php //echo vmText::_ ( 'COM_VMVENDOR_PRODUCT_DETAILS' ); ?></a>
		</h6>
		<h6 class="cIndex-Name cResetH">
				<a href="<?php echo $row->profileLink; ?>"><?php echo $row->user->getDisplayName(); ?></a>
		</h6>
	</div>
	
			<a href="<?php echo $row->profileLink; ?>" class="cIndex-Avatar cFloat-C">
				<img class="cAvatar" src="<?php echo $row->user->getThumbAvatar(); ?>" alt="<?php echo $row->user->getDisplayName(); ?>" />
				<?php if($row->user->isOnline()): ?>
				<b class="cStatus-Online">
					<?php echo JText::_('COM_COMMUNITY_ONLINE'); ?>
				</b>
				<?php endif; ?>
			</a>
			<div style="clear:both" ></div>
				<div class="cIndex-Status"><?php echo $row->user->getStatus() ;?></div>
				<div class="cIndex-Actions---">

					<?php if( $config->get('enablepm') && $my->id != $row->user->id && $my->id != 0): ?>
						<div>
							<i class="com-icon-mail-go"></i>
							<a onclick="<?php echo CMessaging::getPopup($row->user->id); ?>" href="javascript:void(0);">
								<?php echo JText::_('COM_COMMUNITY_INBOX_SEND_MESSAGE'); ?>
							</a>
						</div>
					<?php endif; ?>

					<div>
						<i class="com-icon-groups"></i>
						<span><?php echo JText::sprintf( (CStringHelper::isPlural($row->friendsCount)) ? 'COM_COMMUNITY_FRIENDS_COUNT_MANY' : 'COM_COMMUNITY_FRIENDS_COUNT', $row->friendsCount);?></span>
					</div>

					<?php
					if($row->addFriend)
					{
						$isWaitingApproval = CFriendsHelper::isWaitingApproval($my->id, $row->user->id);
					?>
						<div>
							<?php if(isset($row->isMyFriend) && $row->isMyFriend==1){ ?>
								<i class="com-icon-info"></i>
								<a href="javascript:void(0)" onclick="joms.friends.connect('<?php echo $row->user->id;?>')"><span><?php echo JText::_('COM_COMMUNITY_PROFILE_PENDING_FRIEND_REQUEST'); ?></span></a>
							<?php } else { ?>
								<?php if(!$isWaitingApproval){?>
									<i class="com-icon-user-plus"></i>
									<a href="javascript:void(0)" onclick="joms.friends.connect('<?php echo $row->user->id;?>')"><span><?php echo JText::_('COM_COMMUNITY_PROFILE_ADD_AS_FRIEND'); ?></span></a>
								<?php }else{ ?>
									<i class="com-icon-info"></i> <span><?php echo JText::_('COM_COMMUNITY_PROFILE_PENDING_FRIEND_REQUEST'); ?></span>
								<?php }?>
							<?php } ?>
						</div>
					<?php
					}
					else
					{
					?>
					<?php
						if(($my->id != $row->user->id) && ($my->id !== 0))
						{
					?>
						<div>
							<i class="com-icon-tick"></i> <span><?php echo JText::_('COM_COMMUNITY_PROFILE_ADDED_AS_FRIEND'); ?></span>
						</div>
					<?php
						}
					}
					?>

					<?php
					if( $isCommunityAdmin && isset($featuredList))
					{
						if( !in_array($row->user->id, $featuredList) )
						{
					?>
					<div id="featured-<?php echo $row->user->id;?>" class="cIndex-Feature">

							<a onclick="joms.featured.add('<?php echo $row->user->id;?>','search');" href="javascript:void(0);" title="<?php echo JText::_('COM_COMMUNITY_MAKE_FEATURED'); ?>"><i class="com-icon-award-plus"></i><span><?php echo JText::_('COM_COMMUNITY_MAKE_FEATURED'); ?></span></a>
					</div>
					<?php
						}
					}
					?>
				</div>



	
		
		


			
	<div id="compaign<?php // echo $compaignslideid ?>" class="carousel slide" data-interval="false">  
		<div class="carousel-inner">
		<?php /*
							if ($count_images > 10) {
							for ($i = 1; $i < 11; $i++) {
								$ii = $i - 1;
		echo '<div class="'.$ii.' item';
							if ($ii == 0) echo $active;
		echo '"><a href="'.$juri.$thumburl[$ii].'" alt="'.$product->product_name.'" data-lightbox="roadtrip['.$product->product_name.']" tite="'.$product->product_s_desc.'" data-title="'.$product->product_s_desc.'"><img src="'.$juri.$thumburl[$ii].'" class="compaign-avatar"></a></div>';
							}
							} else {
							$count_images_total = $count_images + 1;
							for ($i = 1; $i < $count_images_total; $i++) {
								$ii = $i - 1;
		echo '<div class="'.$ii.' item';
							if ($ii == 0) echo $active;
		echo '"><a href="'.$juri.$thumburl[$ii].'" alt="'.$product->product_name.'" data-lightbox="roadtrip['.$product->product_name.']" tite="'.$product->product_s_desc.'" data-title="'.$product->product_s_desc.'"><img src="'.$juri.$thumburl[$ii].'" class="compaign-avatar"></a></div>';
							}
							}
					*/		?>
		</div>
				</div>
<?php /* if ($count_images > 1) { ?>
      <a class="left carousel-control" href="#compaign<?php echo $compaignslideid ?>" data-slide="prev"><span class="icon-arrow-left"></span></a>
      <a class="right carousel-control" href="#compaign<?php echo $compaignslideid ?>" data-slide="next"><span class="icon-arrow-right"></span></a>
		<ol class="carousel-indicators">
							<?php
							if ($count_images > 10) {
							for ($i = 1; $i < 11; $i++) {
								$ii = $i - 1; ?>
			<li data-target="#compaign <?php echo $compaignslideid ?>" data-slide-to="<?php echo $ii ?>" <?php if ($ii == 0) echo "class=\"$active\""; ?>></li>
			<?php }
							} else {
							$count_images_total = $count_images + 1;
							for ($i = 1; $i < $count_images_total; $i++)  {
								$ii = $i - 1; ?>
			<li data-target="#compaign <?php echo $compaignslideid ?>" data-slide-to="<?php echo $ii ?>" <?php if ($ii == 0) echo "class=\"$active\""; ?>></li>
			<?php }
							} ?>
		</ol>
<?php } ?>	  
    </div>			
<? // НОМИНАЛЫ СПОНСОРА ?>
<div class="row infoofcompaign">
  <div class="col-xs-6"><?php echo JText::_( 'COM_VMVENDOR_VMVENADD_FORM_WEIGHT' ); ?></div>
  <div class="col-xs-6"><?php echo $product->product_width; echo $product->product_lwh_uom; echo $currency_symbol; ?></div>
</div>
<div class="row infoofcompaign">
  <div class="col-xs-6"><?php echo JText::_( 'COM_VMVENDOR_COMPAIGN_CAT' );?></div>
  <div class="col-xs-6"><?php echo $product->category_name; ?></div>
</div>
<?php if($enablestock){ ?>
<div class="row infoofcompaign">
  <div class="col-xs-6"><?php echo JText::_( 'COM_VMVENDOR_PROFILE_INSTOCK' );?></div>
  <div class="col-xs-6"><?php echo $product->product_in_stock - $product->product_ordered; ?></div>
</div>
<?php } ?>
<?php if($enableprice){ ?>
<div class="row infoofcompaign">
<div class="col-xs-6"><?php $product_with_tax			= applytaxes($product->product_price , $product->virtuemart_category_id , $ismyprofile , $vendor_id , $userid);
							$res						= number_format((float)$product_with_tax,$currency_decimal_place,$currency_decimal_symbol,$currency_thousands);
							$search						= array('{sign}', '{number}', '{symbol}');
							$replace					= array('+', $res, $currency_symbol);
							$formattedRounded_price		= str_replace ($search,$replace,$currency_positive_style);
					  echo JText::_ ( 'COM_VMVENDOR_VMVENADD_FORM_PRICE' );?></div>
<div class="col-xs-6"><?php echo $formattedRounded_price;?></div>
<div class="col-xs-6"><?php echo round($product->product_price,0); ?></div>
</div>
<?php } ?>
<!-- НОМИНАЛЫ СПОНСОРА -->
<?php // ТАРГЕТ БАР
	$product_sales = $product->product_sales;
	$product_in_stock = $product->product_in_stock;
	$tofinish = 100 * $product_sales / $product_in_stock;
?>
<div class="row infoofcompaign">
  <div class="col-xs-4"><?php echo $product_sales; ?></div>
  <div class="col-xs-6"><?php echo JText::_ ( 'COM_VMVENDOR_DASHBOARD_STATS_REVENUE' );?>:</div>
  <div class="col-xs-2"><b><?php echo round($tofinish,0); ?></b>%</div>
</div>
<div class="progress compaign">
  <div class="progress-bar pbcompaign" style="width: <?php echo $tofinish; ?>%;"></div>
</div>

<!-- ДОБАВИТЬ В ПОРУЧЕНИЕ -->
	<div class="addtocart-bar-category">
						<?php // Display the quantity box
			$stockhandle = VmConfig::get ('stockhandle', 'none');
							if (($stockhandle == 'disableit' or $stockhandle == 'disableadd') and ($product->product_in_stock - $product->product_ordered) < 1) {
						?>
							<a href="<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&layout=notify&virtuemart_product_id=' . $product->virtuemart_product_id); ?>" class="send_me_n">
								<?php echo JText::_ ('COM_VIRTUEMART_CART_NOTIFY') ?>
							</a>

						<?php
							} else {
						//echo $rowsHeight[$row]['customs'] ?>
								<div class="vm3pr-<?php echo $rowsHeight[$row]['customfields'] ?>"> <?php
										echo shopFunctionsF::renderVmSubLayout('addtocart',array('product'=>$product,'rowHeights'=>$rowsHeight[$row])); ?>
								</div>
						<?php 
							}
						?>
	</div>

<?php // ТАРГЕТ БАР. КОНЕЦ			
			if($product->product_s_desc){ ?>		
				<div class="row-fluid infoofcompaign separatorline">
					<div class="col-xs-12 separatorline"><?php echo $product->product_s_desc ?>
					</div>
				</div>
<?php
			} ?>		
					<div class="row-fluid infoofcompaign">
						<div class="col-xs-5">
							<?php echo JText::_( 'COM_VMVENDOR_COMPAIGN_STARTTDATE' ) ?>
						</div>
							<div class="col-xs-7"><?php echo $product->created_on ?></div>
						</div>
					<div class="row infoofcompaign">
						<div class="col-xs-6"><?php echo JText::_( 'COM_VMVENDOR_VMVENADD_FORM_LOCATION' ) ?></div>
						<div class="col-xs-6"><?php echo $product->mf_name ?>
						</div>
					</div>
<?php			
			if($ismyprofile )

			{

echo '<div class="row-fluid infoofcompaign"><div class="col-xs-1"><a href="'.JRoute::_('index.php?option=com_vmvendor&view=editproduct&productid='.$product->virtuemart_product_id).'"><img src="'.$juri.'components/com_vmvendor/assets/img/edit.png" title="'.JText::_( 'COM_VMVENDOR_PROFILE_EDITPRODUCT' ).'" class="'.$tooltip_class.'"/></a></div><div class="col-xs-1"><a href="'.$category_url.'" ><img src="'.$juri.'components/com_vmvendor/assets/img/category.png"  alt="Category" width="14" height="14" title="'.JText::_( 'COM_VMVENDOR_PROFILE_VISITCAT' ).'" class="'.$tooltip_class.'"  /></a></div><div class="col-xs-1"><form name="delete_product" id="delete_product'.$product->virtuemart_product_id.'" method="post" onsubmit="return confirm(\''.JText::_( 'COM_VMVENDOR_PROFILE_SUREDELETE' ).'\');">';
				?>
				<input type="hidden" name="option" value="com_vmvendor" />
				<input type="hidden" name="controller" value="vendorprofile" />
				<input type="hidden" name="task" value="deleteproduct" />
				<input type="hidden" name="delete_productid" value="<?php echo $product->virtuemart_product_id ?>" />
				<input type="hidden" name="userid" value="<?php echo $userid ?>" />
				<input type="hidden" name="price" value="<?php echo $product->product_price ?>" />
				<input type="image" value="submit" src="<?php echo $juri.'components/com_vmvendor/assets/img/del.png" width="16" title="'.JText::_( 'COM_VMVENDOR_PROFILE_DELPRODUCT' ) ?>"  class="<?php echo $tooltip_class ?>"/>
				</form>
				</div>
				</div>
</div>
<?php
}
$compaignslideid++;
}
			echo '<div style="clear:both" ></div>';
			echo '<div class="pagination" >';
			echo $this->pagination->getResultsCounter();
			echo $this->pagination->getPagesLinks();
			echo $this->pagination->getPagesCounter();
			echo '</div>';
			
		if(	count($this->myproducts) >4 ){ ?>




*/?>
</div>

		<?php endif; ?>
	<?php endforeach; ?>
</div>
</div>
<script src="http://0.smartresponsor.com/js/masonry.pkgd.js"></script>
<script src="http://desandro.github.io/imagesloaded/imagesloaded.pkgd.min.js"></script>
<?/*------------------------------------------------------------------------------------------------------------------------*/?>
<? /* 	<ul class="cIndexList forFriendsList cResetList">
	<?php foreach( $data as $row ) : ?>
		<?php $displayname = $row->user->getDisplayName(); ?>
		<?php if(!empty($row->user->id) && !empty($displayname)) : ?>
		<li>
		<div class="cIndex-Box clearfix">
			<a href="<?php echo $row->profileLink; ?>" class="cIndex-Avatar cFloat-L">
				<img class="cAvatar" src="<?php echo $row->user->getThumbAvatar(); ?>" alt="<?php echo $row->user->getDisplayName(); ?>" />
				<?php if($row->user->isOnline()): ?>
				<b class="cStatus-Online">
					<?php echo JText::_('COM_COMMUNITY_ONLINE'); ?>
				</b>
				<?php endif; ?>
			</a>
			<div class="cIndex-Content">
				<h3 class="cIndex-Name cResetH">
					<a href="<?php echo $row->profileLink; ?>"><?php echo $row->user->getDisplayName(); ?></a>
				</h3>
				<div class="cIndex-Status"><?php echo $row->user->getStatus() ;?></div>
				<div class="cIndex-Actions">

					<?php if( $config->get('enablepm') && $my->id != $row->user->id && $my->id != 0): ?>
						<div>
							<i class="com-icon-mail-go"></i>
							<a onclick="<?php echo CMessaging::getPopup($row->user->id); ?>" href="javascript:void(0);">
								<?php echo JText::_('COM_COMMUNITY_INBOX_SEND_MESSAGE'); ?>
							</a>
						</div>
					<?php endif; ?>

					<div>
						<i class="com-icon-groups"></i>
						<span><?php echo JText::sprintf( (CStringHelper::isPlural($row->friendsCount)) ? 'COM_COMMUNITY_FRIENDS_COUNT_MANY' : 'COM_COMMUNITY_FRIENDS_COUNT', $row->friendsCount);?></span>
					</div>

					<?php
					if($row->addFriend)
					{
						$isWaitingApproval = CFriendsHelper::isWaitingApproval($my->id, $row->user->id);
					?>
						<div>
							<?php if(isset($row->isMyFriend) && $row->isMyFriend==1){ ?>
								<i class="com-icon-info"></i>
								<a href="javascript:void(0)" onclick="joms.friends.connect('<?php echo $row->user->id;?>')"><span><?php echo JText::_('COM_COMMUNITY_PROFILE_PENDING_FRIEND_REQUEST'); ?></span></a>
							<?php } else { ?>
								<?php if(!$isWaitingApproval){?>
									<i class="com-icon-user-plus"></i>
									<a href="javascript:void(0)" onclick="joms.friends.connect('<?php echo $row->user->id;?>')"><span><?php echo JText::_('COM_COMMUNITY_PROFILE_ADD_AS_FRIEND'); ?></span></a>
								<?php }else{ ?>
									<i class="com-icon-info"></i> <span><?php echo JText::_('COM_COMMUNITY_PROFILE_PENDING_FRIEND_REQUEST'); ?></span>
								<?php }?>
							<?php } ?>
						</div>
					<?php
					}
					else
					{
					?>
					<?php
						if(($my->id != $row->user->id) && ($my->id !== 0))
						{
					?>
						<div>
							<i class="com-icon-tick"></i> <span><?php echo JText::_('COM_COMMUNITY_PROFILE_ADDED_AS_FRIEND'); ?></span>
						</div>
					<?php
						}
					}
					?>

					<?php
					if( $isCommunityAdmin && isset($featuredList))
					{
						if( !in_array($row->user->id, $featuredList) )
						{
					?>
					<div id="featured-<?php echo $row->user->id;?>" class="cIndex-Feature">

							<a onclick="joms.featured.add('<?php echo $row->user->id;?>','search');"
							   href="javascript:void(0);"
							   class="btn Icon"
							   title="<?php echo JText::_('COM_COMMUNITY_MAKE_FEATURED'); ?>">
								<i class="com-icon-award-plus"></i>
							</a>
					</div>
					<?php
						}
					}
					?>
				</div>
			</div>

		</div>
		</li>
		<?php endif; ?>
	<?php endforeach; ?>
	</ul> */ ?>

	<?php

	if ( !empty($pagination)  )
	{
	?>
	<div class="cPagination">
		<?php echo $pagination->getPagesLinks(); ?>
	</div>
	<?php
	}
	?>

<?php
	}
	else
	{
?>
		<div class="cAlert cNotFound-People"><?php echo JText::_('COM_COMMUNITY_SEARCH_NO_RESULT');?></div>
<?php
	}
?>
