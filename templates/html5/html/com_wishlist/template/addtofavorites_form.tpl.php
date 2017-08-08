<?php 
/**
 * Favorites Template Page for Favorites Component
 * 
 * @package    Favorites & Wishlist
 * @subpackage com_wishlist
 * @license  GNU/GPL v2
 * @copyright Copyright (C) 2013 2KWeb Solutions. All rights reserved.
 * This program is distributed under the terms of the GNU General Public License
 *
 */

//Load the com_favorite language file
$language =& JFactory::getLanguage();
$language_tag = $language->getTag();
JFactory::getLanguage()->load('com_wishlist', JPATH_SITE, $language_tag, true);

//Loading Template Options
$fav_params = &JComponentHelper::getParams( 'com_wishlist' );
$notorderable_enabled = $fav_params->get('tmpl_notorderable_enabled');
$qty_enabled = $fav_params->get('tmpl_qty_enabled');
$guest_enabled = $fav_params->get('tmpl_guest_enabled');
			
/* FAVORITES & WISHLIST ENTRY */
if(!isset($_COOKIE['virtuemart_wish_session']))
{
	$session = JFactory::getSession();
	setcookie('virtuemart_wish_session',$session->getId(),2592000 + time(),'/');
}
$db =& JFactory::getDBO();
$view = JRequest::getString('view',  "");
$itemid = JRequest::getInt('Itemid',  1);
$user =& JFactory::getUser();
$user_id = $user->guest ? $_COOKIE['virtuemart_wish_session'] : $user->id;
if ($view == "productdetails")
{
	$class = 'pull-right';
	$product_id = $this->product->virtuemart_product_id;
	$category_id = $this->product->virtuemart_category_id;
	$product_orderable = $this->product->orderable;
}
else 
{
	$class = 'pull-left';
	$product_id = $product->virtuemart_product_id;
	$category_id = $product->virtuemart_category_id;
	$product_orderable = $product->orderable;
}
if($product_orderable || $notorderable_enabled) {
	$favorite_id = JRequest::getInt('favorite_id',  1);
	$quantity = JRequest::getInt('quantity',  1);
	$mode = JRequest::getString('mode',  "null");
	$q = "SELECT COUNT(*) FROM #__virtuemart_favorites WHERE product_id=".$product_id;
	$db->setQuery($q);
	$total_fav = $db->loadResult();
	$q = "SELECT COUNT(*) FROM #__virtuemart_favorites WHERE user_id ='".$user_id."' AND product_id=".$product_id;
	$db->setQuery($q);
	$result = $db->loadResult();
	$url_fav = JURI::current().'?virtuemart_product_id='.$product_id.'&virtuemart_category_id='.$category_id.'&Itemid='.$itemid;
	$url_favlist = JRoute::_("index.php?option=com_wishlist&view=favoriteslist&Itemid=".$itemid);
	//generate button to remove from favorites list
	$form_deletefavorite = "<div class='clear'></div><form action='".$url_fav."' method='POST' role='form' name='deletefavo' id='". uniqid('deletefavo_') ."' class='formfavorit ".$class."'>\n
	<button type='submit' class='btn btn-primary hasTooltip in_fav' value='".JText::_('VM_REMOVE_FAVORITE')."' name='addtofavorites' data-original-title='".JText::_('VM_REMOVE_FAVORITE')."' data-content='".JText::_('VM_FAV_TOTAL_LIKES').' '.$total_fav.' '.JText::_('VM_FAV_MORE_PEOPLE')."' data-toggle='popover'><span class='glyphicon glyphicon-bookmark'></span></button>
	<input type='hidden' name='mode' value='fav_del' />\n
	<input type='hidden' name='favorite_id' value='". $product_id ."' />\n
	</form>\n";
	$addtofavorites = "";
	if ($total_fav > 0)
	$addtofavorites .= '';
	//$addtofavorites .= '<div style="clear:both; display:none;">'.JText::_('VM_FAV_TOTAL_LIKES').'<b>'.$total_fav.'</b>'.JText::_('VM_FAV_MORE_PEOPLE').'</div>';
	if ($result > 0 ){
			$total_fav --;
			if ($product_id == $favorite_id && $mode == "fav_del")
			{
				$Sql = "DELETE FROM #__virtuemart_favorites ";
				$Sql.= "WHERE product_id='$product_id' AND user_id='$user_id'";
				$db->setQuery($Sql);
				$db->query();
				$result = 0;
			//	JError::raiseNotice( 100, JText::_('VM_FAVORITE_REMOVED'));
			}
			else $addtofavorites .= $form_deletefavorite;
			//else $addtofavorites .= JText::_('VM_FAVORITE_EXIST') .'<a href="'.$url_favlist.'">'.JText::_('VM_ALL_FAVORITE_PRODUCTS').'</a>'.$form_deletefavorite;
	}
	if ($result == 0){
		if($product_id == $favorite_id && $mode == "fav_add") {
				$Sql = "INSERT INTO #__virtuemart_favorites ";
				$Sql.= "SET product_id='$product_id', product_qty='$quantity', user_id='$user_id', fav_date=NOW(), isGuest=".$user->guest;
				$db->setQuery($Sql);
				$db->query();
			//	JFactory::getApplication()->enqueueMessage(JText::_('VM_FAVORITE_ADDED'));
				$addtofavorites .= JText::_('VM_FAVORITE_EXIST') .'<a href="'.$url_favlist.'">'.JText::_('VM_ALL_FAVORITE_PRODUCTS').'</a>'.$form_deletefavorite;
		} 
		else {
			//Loading the Component Stylesheet
			JHTML::stylesheet("template.css", "components/com_wishlist/");
			if ($guest_enabled || !$user->guest){
					$addtofavorites .= '
					<div class="clear"></div>
					<form action="'.$url_fav.'" class="formfavorit '.$class.'" role="form" method="POST" name="addtofavorites" id="addtofavorites_'.$product_id.'">';
					// Product custom_fields
					if ($qty_enabled)
					$addtofavorites .= '<div style="float:left"><input id="quantity_'.$product_id.'" class="quantity-input" size="1" name="quantity" value="1" /></div>';
					$addtofavorites .= '<button type="submit" class="btn btn-primary hasTooltip" value="'
					.JText::_('VM_ADD_TO_FAVORITES').'" name="addtofavorites" data-original-title="'.JText::_('VM_ADD_TO_FAVORITES').'" data-content="'.JText::_('VM_FAV_TOTAL_LIKES').' '.$total_fav.'  '.JText::_('VM_FAV_MORE_PEOPLE').'" data-toggle="popover"><span class="glyphicon glyphicon-pushpin"></span></button>
					<input type="hidden" name="favorite_id" value="'.$product_id.'" />
					<input type="hidden" name="mode" value="fav_add" />
					</form>'; 
				}
			else {
					/*$redirectUrl = $url_fav.'&mode=fav_add&favorite_id='.$product_id;
					$redirectUrl = urlencode(base64_encode($redirectUrl));
					$redirectUrl = '&return='.$redirectUrl;
					$joomlaLoginUrl = '';
					//$joomlaLoginUrl = 'index.php?option=com_users&view=login';
					$finalUrl = $joomlaLoginUrl . $redirectUrl;
					$addtofavorites .= '';*/
					//$addtofavorites .= '<a href="'.$finalUrl.'" alt="Login" title="Login"><input type="submit" class="btn btn-primary" value="'
					//.JText::_('VM_ADD_TO_FAVORITES').'" name="addtofavorites" title="'.JText::_('VM_ADD_TO_FAVORITES').'" /></a>';
					
					$addtofavorites .= '
					<div class="clear"></div>
					<form action="'.$url_fav.'" class="formfavorit '.$class.'" role="form" method="POST" name="addtofavorites" id="addtofavorites_'.$product_id.'">';
					// Product custom_fields
					if ($qty_enabled)
					$addtofavorites .= '<div style="float:left"><input id="quantity_'.$product_id.'" class="quantity-input" size="1" name="quantity" value="1" /></div>';
					$addtofavorites .= '<div data-original-title="'.JText::_('VM_ADD_TO_FAVORITES').'" data-content="'.JText::_('VM_FAV_TOTAL_LIKES').' '.$total_fav.'  '.JText::_('VM_FAV_MORE_PEOPLE').'" data-toggle="popover"><button disabled="disabled" type="submit" class="btn btn-primary " slyle="border-radius: 0 0 3px 0px;" value="'
					.JText::_('VM_ADD_TO_FAVORITES').'" name="addtofavorites"><span class="glyphicon glyphicon-pushpin"></span></button></div>
					<input type="hidden" name="favorite_id" value="'.$product_id.'" />
					<input type="hidden" name="mode" value="fav_add" />
					</form>'; 
			}
		}
	}
	
	if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && $product_id == JRequest::getVar('virtuemart_product_id')) {
		ob_clean();
		
		$q = "SELECT COUNT(*) FROM #__virtuemart_favorites WHERE product_id=".$product_id;
		$db->setQuery($q);
		$total_fav = $db->loadResult();
		
		if (!$total_fav) {
			$total_fav = 0;	
		}
		
		die('###'.$total_fav);
	}
	
	echo $addtofavorites;
}
?>