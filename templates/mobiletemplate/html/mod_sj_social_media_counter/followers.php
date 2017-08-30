<?php
/**
 * @package SJ Social Media Counter 
 * @version 1.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2013 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 */

defined ( '_JEXEC' ) or die ();

if(!empty($list)) {
	$tag_id = 'sj_social_media_counts_'.rand().time();
	JHtml::stylesheet('modules/'.$module->module.'/assets/css/styles.css');
if($params->get('pretext') != ''){?>	
<div class="sc-pretext">
	<?php echo $params->get('pretext'); ?>
</div>
<?php }?>
<div id="<?php echo $tag_id; ?>" class="sj-social-media-counts">
	<div class="sc-wrap cf">
		<?php if(isset($list['count_facebook_like'])){ ?>
		<div class="fb-like-button sc-item">
			<div class="sc-item-inner">
				<a href="<?php echo $params->get('facebook_url','#'); ?>" title="" target="_blank">
					<span class="sc-icon-facebook sc-icon"></span>
					<span class="like-count"><?php echo $list['count_facebook_like']; ?></span>
					<span class="like-text">
						<?php //echo $list['count_facebook_like'] > 1?JText::_('LIKES_LABEL'):JText::_('LIKE_LABEL'); ?>
					</span>
				</a>
			</div>
		</div>
		<?php }
		if(isset($list['count_followers_twitter'])){ ?>
		<div class="twitter-like-button  sc-item ">
			<div class="sc-item-inner">
				<a href="<?php echo $params->get('twitter_url','#'); ?>" title="" target="_blank">
					<span class="sc-icon-twitter sc-icon"></span>
					<span class="like-count"><?php echo $list['count_followers_twitter']; ?></span>
					<span class="like-text">
						<?php //echo $list['count_followers_twitter'] > 1?JText::_('FOLLOWERS_LABEL'):JText::_('FOLLOWER_LABEL') ?>
					</span>
				</a>
			</div>	
		</div>
		<?php }
		if(isset($list['count_followers_linkedin'])){ ?>
		<div class="linkedin-like-button  sc-item ">
			<div class="sc-item-inner">
				<a href="<?php echo $params->get('linkedin_url','#'); ?>" title="" target="_blank">
					<span class="sc-icon-linkedin sc-icon"></span>
					<span class="like-count"><?php echo $list['count_followers_linkedin']; ?></span>
					<span class="like-text">
					<?php //echo $list['count_followers_linkedin'] > 1?JText::_('FOLLOWERS_LABEL'):JText::_('FOLLOWER_LABEL') ?>
					</span>
				</a>
			</div>	
		</div>
		<?php }
		if(isset($list['count_followers_gplus'])){ ?>
		<div class="gplus-like-button  sc-item">
			<div class="sc-item-inner">
				<a href="<?php echo $params->get('gplus_url','#'); ?>" title="" target="_blank">
					<span class="sc-icon-gplus sc-icon"></span>
					<span class="like-count"><?php echo $list['count_followers_gplus']; ?></span>
					<span class="like-text">
						<?php //echo $list['count_followers_gplus'] > 1?JText::_('FOLLOWERS_LABEL'):JText::_('FOLLOWER_LABEL') ?>
					</span>
				</a>
			</div>
		</div>
		<?php }
		if(isset($list['count_followers_instagram'])){ ?>
		<div class="instagram-like-button sc-item ">
			<div class="sc-item-inner">
				<a href="<?php echo $params->get('instagram_url','#'); ?>" title="" target="_blank">
					<span class="sc-icon-instagram sc-icon"></span>
					<span class="like-count"><?php echo $list['count_followers_instagram']; ?></span>
					<span class="like-text">
						<?php //echo $list['count_followers_instagram'] > 1?JText::_('FOLLOWERS_LABEL'):JText::_('FOLLOWER_LABEL') ?>
					</span>
				</a>
			</div>
		</div>
		<?php } ?>
	</div>
</div>

<?php } else{ 
	echo JText::_('Has no content to show!');	
}?>
