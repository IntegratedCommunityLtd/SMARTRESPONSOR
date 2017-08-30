<?php // no direct access
defined('_JEXEC') or die('Restricted access');

//Addding Main CSS/JS VM_Theme files to header
JHTML::stylesheet("theme.css", VM_THEMEURL);
JHTML::stylesheet("template.css", "components/com_wishlist/");

$my_page =& JFactory::getDocument();
$conf =& JFactory::getConfig();
$sitename = $conf->get('sitename');
$option = JRequest::getString('option',  "");
$view = JRequest::getString('view',  "");
$user_id = JRequest::getInt('user_id');
$itemid = JRequest::getInt('Itemid', 1);
if (empty( $this->data )){ ?>
	<span class="fav_title"><?php echo JText::_( 'VM_SHARELIST_ERROR' ); ?></span>
	<?php echo "<div class='fav_header'>". JText::_('VM_SHARELIST_DENY')."</div>";

	
	
	
}
else
{
	//Loading Global Options
	$params = &JComponentHelper::getParams( 'com_wishlist' );
	$tmpl_favdate_enabled = $params->get( 'tmpl_favdate_enabled' );
	$tmpl_favimage_width = $params->get( 'tmpl_favimage_width' );
	$hdr_data = $this->data[0];
	$share_pass = JRequest::getString('share_pass',  "");
	$form_link = JRoute::_('index.php?option='.$option.'&view='.$view.'&user_id='.$user_id.'&Itemid='.$itemid);
	$form_share_link = JRoute::_('index.php?option='.$option.'&view='.$view.'&user_id='.$user_id.'&Itemid='.$itemid, true, -1);
	if ($hdr_data->share_pass == "" || $hdr_data->share_pass == md5($share_pass))
	{
		$uname = $hdr_data->name;
		$uid = $hdr_data->user_id;
		$shdate = $hdr_data->share_date;
		$shtitle = $hdr_data->share_title;
		$shdesc = $hdr_data->share_desc;
		$iswishlist = $hdr_data->isWishList;
		$my_page->setTitle($sitename. ' - ' .$uname." ". JText::_( 'VM_WISHLIST' ).' | ' . $shtitle);
		//Social Sharing Links
		if (!empty( $this->data ))
		{
			$form_social_fb = '<div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#appId=143548565733526&amp;xfbml=1"></script><fb:like href="'.$form_share_link.'" send="true" layout="button_count" width="100" show_faces="false" action="recommend"></fb:like>';
			$form_social_tw ='<a href="http://twitter.com/share" class="twitter-share-button" data-url="'.$form_share_link.'" data-text="'.$uname. ' - ' .$shtitle.'" data-count="horizontal">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>';
			$form_social_gp ='<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script><g:plusone size="medium" href="'.$form_share_link.'"></g:plusone>';
			echo '<p><span class="fav_title">'. JText::_( 'VM_SOCIAL_SHARE' ). '</span></p>';
			echo $form_social_fb;
			echo $form_social_tw;
			echo $form_social_gp;
		}
		if (!$iswishlist)
		{?>
			<div class="fav_title"><?php echo $uname." ".JText::_('VM_FAVORITES'); $my_page->setTitle($sitename. ' - ' .$uname." ".JText::_('VM_FAVORITES').' | ' .$shtitle); ?></div>
			<?php
		}
		else
		{?>
			<div class="fav_title"><?php echo $uname." ".JText::_('VM_WISHLIST'); $my_page->setTitle($sitename. ' - ' .$uname." ".JText::_('VM_WISHLIST').' | ' .$shtitle); ?></div>
			<?php
		}?>
		<div class="fav_header"><?php echo $shtitle." - ".$shdesc;  ?></div>
		<?php
		//Initialize the Virtuemart Product Model Class
		$productModel = new VirtueMartModelProduct();

		foreach($this->data as $dataItem)
		{
			$product = $productModel->getProduct($dataItem->product_id);
			$productModel->addImages($product);
			
			//Initialize Variables
			$product_qty = $dataItem->product_qty;
			$url_vm = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='
			.$product->virtuemart_product_id.'&virtuemart_category_id='
			.$product->virtuemart_category_id);
			
			echo "<div class='fav_container'>";
			//Display Linked Product Name
			echo "<div class='prod_name'>";
			echo "<a href='".$url_vm."'>".$product->product_name."</a>";
			//Display Product Price
			$currency = CurrencyDisplay::getInstance( );
			if (!empty($product->prices['salesPrice'] ) ) echo $currency->createPriceDiv('salesPrice','',$product->prices,true);
			//if (!empty($product->prices['salesPriceWithDiscount']) ) echo $currency->createPriceDiv('salesPriceWithDiscount','',$product->prices,true);
			echo "</div>";
			
			//Display Favorite Date		
			if ($tmpl_favdate_enabled)			
			echo "<div class='col_date'>".JHtml::date($dataItem->fav_date, JText::_('DATE_FORMAT_LC4'))."</div>";
			if (!empty($product->images[0]) ) $image = $product->images[0]->displayMediaThumb('width="'.$tmpl_favimage_width.'" border="0"',false) ;
			else $image = '';
			echo "<div class='col_image'>";
			echo JHTML::_('link', JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$product->virtuemart_product_id.'&virtuemart_category_id='.$product->virtuemart_category_id),$image,array('title' => $product->product_name) );
			echo "</div>";
			//Display Add To Cart Form
			echo "<div class='col_controls'>";
			if ($iswishlist && $product_qty <> 0) FavoritesModelSharelist::addtocart($product,JText::_('VM_WISHLIST_GIFTIT'),$iswishlist,$uname);
			else if ($iswishlist && $product_qty == 0) echo "<div class=\"wish_alert\">".JText::_('VM_WISHLIST_UNAVAILABLE')."</div>";
			else FavoritesModelSharelist::addtocart($product,JText::_('COM_VIRTUEMART_CART_ADD_TO'),$iswishlist,$uname);
			echo "</div>";
			echo "<div class='col_favorite' style='clear:both'>";
			if ($iswishlist && $product_qty > -1) echo "<p><strong>".JText::_('VM_WISHLIST_AVAILABLE').":</strong> ".sprintf($product_qty)."</p>";
			else if ($iswishlist && $product_qty <= -1) echo "<p><strong>".JText::_('VM_WISHLIST_AVAILABLE').": </strong>".JText::_('VM_WISHLIST_UNLIMITED')."</p>";
			echo "</div>";
			echo "</div>";
		}?>
		<div class="jcb_pagination"><?php echo $this->pagination->getPagesLinks(); ?> - <?php echo $this->pagination->getPagesCounter(); ?></div>
		<?php
	}
	else
	{
		if ($share_pass != "" && $hdr_data->share_pass != md5($share_pass)) $wrongpass_txt = JText::_( 'VM_PASSWORD_WRONG' );
		?>
			<div class="fav_title"><?php echo JText::_( 'VM_PASSWORD_ACCESS' ); ?></div>
			<div class="fav_header"><?php echo JText::_( 'VM_PASSWORD_PROMPT' ); ?></div>
		<?php
		echo"<form action=\"".$form_link."\" method=\"POST\" name=\"share\" id=\"share\">\n
				<input id=\"share_pass\" type=\"password\" class=\"inputbox\" size=\"35\" maxlength=\"32\" name=\"share_pass\" />\n
				<input type=\"hidden\" name=\"option\" value=\"$option\" />\n
				<input type=\"hidden\" name=\"user_id\" value=\"$user_id\" />\n
				<input type=\"hidden\" name=\"view\" value=\"$view\" />\n
				<input type=\"hidden\" name=\"Itemid\" value=\"$itemid\" />\n
				<p>".$wrongpass_txt."</p>
				<p style=\"padding:20px 0 20px 0\">
				<input type=\"button\" class=\"modns button art-button art-button addtocart_button\" value=\"".JText::_( 'VM_SHARELIST_BACK' )."\" title=\"".JText::_( 'VM_SHARELIST_BACK' )."\" onclick=\"javascript:history.back()\" />
				<input type=\"submit\" class=\"modns button art-button art-button\" value=\"".JText::_('VM_ACCESS_BUTTON')."\" title=\"".JText::_('VM_SHARE_BUTTON')."\" />
				</p>
			  </form>";
	}
}
vmJsApi::jQuery();
vmJsApi::jPrice();
vmJsApi::cssSite();
?>