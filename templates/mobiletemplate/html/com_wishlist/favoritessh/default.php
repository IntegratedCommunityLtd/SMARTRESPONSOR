<?php // no direct access
defined('_JEXEC') or die('Restricted access'); 
$user =& JFactory::getUser();
if ($user->id > 0)
{
$data = $this->data;

//Addding Main CSS/JS VM_Theme files to header
JHTML::stylesheet("theme.css", VM_THEMEURL);
JHTML::stylesheet("template.css", "components/com_wishlist/");
$my_page =& JFactory::getDocument();
$conf =& JFactory::getConfig();
$sitename = $conf->get('sitename');
$my_page->setTitle($sitename. ' - ' .JText::_( 'VM_SHARE_FAVORITES' ));
$mode = JRequest::getCmd('mode');
$share_date = $data->share_date;
$share_message = '';
switch ($mode) {
				case "share":
					$share_message.= JText::_('VM_FAVORITE_SH_UPDATED');
					if ($share_date > "1900-01-01") $share_message.=' '.JText::_('VM_FAVORITE_SH_PUBLIC');
					else $share_message.=' '.JText::_('VM_FAVORITE_SH_PRIVATE');
					JFactory::getApplication()->enqueueMessage($share_message);
        			break;
    			case "unshare":
					$share_message.= JText::_('VM_FAVORITE_USH_UPDATED');
					JFactory::getApplication()->enqueueMessage($share_message);
        			break;
}

/*
				<h4><span class="fav_title"><?php echo JText::_( 'VM_SHARE_FAVORITES' ); ?></span></h4>
*/

$option = JRequest::getString('option',  "");
$view = JRequest::getString('view',  "");
$itemid = JRequest::getInt('Itemid', 1);
if (!empty($data)) $share_title = $data->share_title;
else $share_title = JText::_('VM_FAVORITE_LIST');
$share_desc = $data->share_desc;
$iswishlist = $data->isWishList;
$share_pass = $data->share_pass;
if ($share_date > "1900-01-01" || !$share_date) $acc_opt = '<option value="public" selected="selected">'.JText::_('VM_SHARE_PUBLIC').'</option><option value="private">'.JText::_('VM_SHARE_PRIVATE').'</option>';
else $acc_opt = '<option value="public">'.JText::_('VM_SHARE_PUBLIC').'</option><option value="private" selected="selected">'.JText::_('VM_SHARE_PRIVATE').'</option>';
if ($iswishlist == 0) $wish_opt = '<option value="0" selected="selected">'.JText::_('VM_WISHLIST_NO').'</option><option value="1">'.JText::_('VM_WISHLIST_YES').'</option>';
else $wish_opt = '<option value="0">'.JText::_('VM_WISHLIST_NO').'</option><option value="1" selected="selected">'.JText::_('VM_WISHLIST_YES').'</option>';
if ($share_pass == null || $share_pass == "") $share_pass_ctr = '
				<div class="form-group input-group col-xs-12">
				<label class="control-label col-xs-12">'.JText::_('VM_SHARE_PASSWORD_NEW').'</label>
				<div class="col-xs-12">
				<div class="col-xs-12 col-sm-11">
				<input id="share_pass" type="password" class="form-control inputbox" size="35" maxlength="32" name="share_pass" />
				</div>
				<div class="col-sm-1">
				<span class="input-group-addon glyphicon glyphicon-qrcode hidden-xs"></span>	</div>	
</div>				
				</div>';
else $share_pass_ctr = JText::_('VM_SHARE_PASSWORD_EXIST').'
				<div class="form-group input-group col-xs-12">
				<label class="col-xs-12 control-label"></label>
				<div class="col-xs-12">
				<div class="col-xs-12 col-sm-11">
				<input id="share_pass" type="password" class="form-control inputbox" size="35" maxlength="32" name="share_pass" />
				</div>
				<div class="col-sm-1">
				<span class="input-group-addon glyphicon glyphicon-qrcode hidden-xs"></span>
				</div>
				</div></div>
				<div class="checkbox">
				<label>
				<input id="pass_clear" type="checkbox" name="pass_clear" />'.JText::_('VM_SHARE_PASSWORD_CLEAR').'
				</label>
				</div>';
$form_link = JRoute::_('index.php?option='.$option.'&view='.$view.'&Itemid='.$itemid);
$form_share_link = JRoute::_('index.php?option='.$option.'&view=sharelist&user_id='.$data->user_id.'&Itemid='.$itemid, true, -1);
$form_share_favorites = '<script language="javascript" type="text/javascript"><!--function imposeMaxLength(Object, MaxLen){return (Object.value.length <= MaxLen);}--></script>';
$form_share_favorites .= '
				<form action="'.$form_link.'" method="POST" role="form" name="share" id="share" class="form-validate">
				<input type="hidden" name="option" value="'.$option.'" />
				<input type="hidden" name="view" value="'.$view.'" />
				<input type="hidden" name="Itemid" value="'.$itemid.'" />
				<input type="hidden" name="mode" value="share" />
				<div class="form-group input-group col-xs-12">
				<label class="control-label col-xs-12">'
				.JText::_('VM_SHARE_ACCESS').'</label>
				<div class="col-xs-12">
				<div class="col-sm-11 col-xs-12">
				<select name="acc_type" id="acc_type" class="form-control chosen">'.$acc_opt.'</select>
				</div>
				<div class="col-sm-1">
				<span class="input-group-addon glyphicon glyphicon-folder-open hidden-xs"></span>
				</div>
				</div>
				</div>
				<div class="form-group input-group col-xs-12">		<label class="control-label col-xs-12">'
				.JText::_('VM_IS_WISHLIST').'</label>	
				<div class="col-xs-12">
				<div class="col-xs-12 col-sm-11">
				<select name="is_wishlist" id="is_wishlist" class="form-control chosen">'.$wish_opt.'
				</select>
				</div>
				<div class="col-sm-1">
				<span class="input-group-addon glyphicon glyphicon-glass hidden-xs"></span>	
				</div></div>			
				</div>
				<div class="form-group input-group col-xs-12">
				<label class="control-label col-xs-12">'
                .JText::_('VM_SHARE_TITLE').'</label>
				<div class="col-xs-12">
				<div class="col-xs-12 col-sm-11">
				<input id="share_title" class="form-control inputbox" size="35" maxlength="32" name="share_title" value="'. $share_title .'" />
				</div>
				<div class="col-sm-1">
				<span class="input-group-addon glyphicon glyphicon-tag hidden-xs"></span>
				</div>
				</div>
				</div>
				<div class="form-group input-group col-xs-12">
				<label class=" col-xs-12">'
				.JText::_('VM_SHARE_DESC').'</label>
				<div class="col-xs-12">
				<div class="col-xs-12 col-sm-11">
				<textarea id="share_desc" class="form-control inputbox" cols="50" rows="2" name="share_desc" onkeypress="return imposeMaxLength(this, 100);" placeholder="111">'. $share_desc .'
				</textarea>
				</div>
				<div class="col-sm-1">
				<span class="input-group-addon glyphicon glyphicon-align-left hidden-xs"></span>
				</div>
				</div>
				</div>'
				.$share_pass_ctr.'
				<br><div class="col-xs-12 col-sm-12"><button type="submit" class="col-sm-12 col-xs-12 btn btn-primary modns button art-button art-button" value="'.JText::_('VM_SHARE_BUTTON').'" title="'.JText::_('VM_SHARE_BUTTON').'"><span class="visible-xs glyphicon glyphicon-folder-open"></span>'.JText::_('VM_SHARE_BUTTON').'</button>
				</div>
				</form>';
$form_unshare_favorites = '
				<br><br><br><form action="'.$form_link.'" method="POST" role="form "name="unshare" id="unshare" class="form-validate">
				<input type="hidden" name="option" value="'.$option.'" />
				<input type="hidden" name="view" value="'.$view.'" />
				<input type="hidden" name="Itemid" value="'.$itemid.'" />
				<input type="hidden" name="mode" value="unshare" />
                <div class="col-sm-12 col-xs-12"><button type="submit" class="col-sm-12 col-xs-12 btn btn-primary modns button art-button art-button" value="'.JText::_('VM_UNSHARE_BUTTON').'" title="'.JText::_('VM_SHARE_BUTTON').'" onclick="return confirm("'.JText::_('VM_FAVORITE_UNSHARE_MSG').'")"><span class="visible-xs glyphicon glyphicon-folder-close"></span>'.JText::_('VM_UNSHARE_BUTTON').'</button>
				</div>
				</form>';
//Email Form
$form_email_favorites = '
				<form action="'.$form_link.'" method="POST" role="form" name="sendmail" id="sendmail" class="form-validate">
				<input type="hidden" name="option" value="'.$option.'" />
				<input type="hidden" name="view" value="'.$view.'" />
				<input type="hidden" name="Itemid" value="'.$itemid.'" />
				<input type="hidden" name="mode" value="sendmail" />'
				.JText::_('VM_EMAIL_TO').'
				<div class="input-group">
				<input id="email_to" class="form-control inputbox" size="35" name="email_to" placeholder="Text input" />
				<span class="input-group-addon glyphicon glyphicon-envelope hidden-xs"></span>
				</div>'
				.JText::_('VM_EMAIL_SUBJECT').'
				<div class="input-group">
				<input id="email_subj" class="form-control inputbox" size="35" maxlength="32" name="email_subj" value="'. $share_title .'" />
				<span class="input-group-addon glyphicon glyphicon-map-marker hidden-xs"></span>
				</div>'
				.JText::_('VM_EMAIL_BODY').'
				<div class="input-group">
				<textarea id="email_body" class="form-control inputbox" cols="50" rows="2" name="email_body" onkeypress="return imposeMaxLength(this, 100);" placeholder="Text input">'. $share_desc .'</textarea>
				<span class="input-group-addon glyphicon glyphicon-align-left hidden-xs"></span>
				</div>'
				.JText::_('VM_EMAIL_BODY_NOTE').'
				<button type="submit" class="btn btn-primary modns button art-button art-button addtocart_button" value="'.JText::_('VM_EMAIL_SEND').'" title="'.JText::_('VM_EMAIL_SEND').'"><span class="visible-xs glyphicon glyphicon-envelope"></span>'.JText::_('VM_EMAIL_SEND').'</button>
				</form>';
$form_social_fb = '<div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#appId=143548565733526&amp;xfbml=1"></script><fb:like href="'.$form_share_link.'" send="true" layout="button_count" width="100" show_faces="false" action="recommend"></fb:like>';
$form_social_tw ='<a href="http://twitter.com/share" class="twitter-share-button" data-url="'.$form_share_link.'" data-text="'.$share_title.'" data-count="horizontal">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>';
$form_social_gp ='<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script><g:plusone size="medium" href="'.$form_share_link.'"></g:plusone>';

echo $form_share_favorites;
echo $form_unshare_favorites;
echo '<form role="form" class="form-validate">';
if (empty($data)) echo '<div class="form-group col-xs-12 col-sm-12"><h4 data-dismiss="alert">'. JText::_('VM_SHARELIST_MANDATORY').'</h4></div>';
else{
echo '<div style="clear:both"></div><h4 data-dismiss="alert">'. JText::_( 'VM_SOCIAL_SHARE' ).'</h4>';
echo $form_social_fb;
echo $form_social_tw;
echo $form_social_gp;
echo '<h4><span class="fav_title">'. JText::_( 'VM_SOCIAL_LINK' ).'</span></h4>';
echo '<span class="highlighted_txt" data-dismiss="alert">'.$form_share_link.'</span>';
echo '<h4><span class="fav_title">'. JText::_( 'VM_EMAIL_SHARE' ).'</span></h4>';
echo '</form>';
echo $form_email_favorites;
}
?>

<?php
}
else { ?>

				<h4><span class="fav_title alert alert-warning fade in" data-dismiss="alert"><?php echo JText::_( 'VM_SHARELIST_ERROR' ); ?></span></h4>

               	<?php echo '<h4 data-dismiss="alert">'. JText::_('VM_SHARELIST_DENY').'</h4>'; ?>


<button type="button" class="btn btn-prymari modns button art-button art-button addtocart_button" value="<?php echo JText::_( 'VM_SHARELIST_BACK' ); ?>" title="<?php echo JText::_( 'VM_SHARELIST_BACK' ); ?>" onclick="javascript:history.back()"></button>
<?php
}
?>