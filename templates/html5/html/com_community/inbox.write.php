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
CFactory::attach('assets/easytabs/jquery.easytabs.min.js', 'js');
?>
<script type="text/javascript">
function prepareFormField() {
	var message = joms.jQuery('#message-body'),
		ct = message.closest('[data-type=wall-newcomment]'),
		attachment = ct.find('.joms-stream-attachment'),
		photo;

	if ( ct.data('saving') )
		return false;

	ct.data('saving', 1);
	ct.find('#photo').remove();
	ct.append('<input type="hidden" name="body" value="' + message.val() + '">');

	if ( attachment.is(':visible') ) {
		photo = attachment.find('.joms-thumbnail').find('img');
		photo = photo.data('photo_id');
		ct.append('<input type="hidden" name="photo" id="photo" value="' + photo + '">');
	}

	return true;
}

var yPos;

function addFriendName()
{
	var inputs 		= [];

	joms.jQuery('#selections option:selected').each( function() {
		inputs.push(this.value);
	});

	var x = inputs.join(', ');
	joms.jQuery('#to').val(x);
}

joms.jQuery(document).ready(function(){
	<?php
		//@since 2.4
		if(isset($data->toUsersInfo) && count($data->toUsersInfo) > 0 ){
			foreach($data->toUsersInfo as $user){
	?>
		cAddRecipients('<?php echo $user['rid'] ?>','<?php echo $user['avatar'] ?>','<?php echo $user['name'] ?>');
	<?php
			}
		}
	?>
});

</script>

<div id="community-walls">
<div class="cInbox-Write">
<form name="jsform-inbox-write" class="community-form-validate composeForm cForm" id="writeMessageForm" action="<?php echo CRoute::getURI(); ?>" method="post" onsubmit="return prepareFormField();">
<?php
	if( $totalSent >=  $maxSent && $maxSent != 0 )
	{
?>
	<div class="cAlert"><?php echo JText::_('COM_COMMUNITY_PM_LIMIT_REACHED');?></div>
<?php
	}
	else
	{
?>
	<ul class="cFormList cFormHorizontal cResetList">

		<?php echo $beforeFormDisplay;?>

		<!-- name -->
		<li>
			<label for="name" class="form-label">
				<?php echo ($useRealName == '1') ? JText::_('COM_COMMUNITY_COMPOSE_TO_REALNAME') : JText::_('COM_COMMUNITY_COMPOSE_TO_USERNAME'); ?>
				<span class="required-sign"> *</span>
			</label>
			<div class="form-field">
				<div id="inbox-selected-to-wrapper">
					<a id="addRecipient" href="javascript:void(0);" onclick="joms.friends.showForm('', 'friends,inviteUsers','0','1','joms.friends.selectRecipients()');;" class="btn">
						<?php echo JText::_('COM_COMMUNITY_INBOX_ADD_RECIPIENT');?>
					</a>
					<ul id="inbox-selected-to" class="cInbox-Selection cResetList"></ul>
				</div>
			</div>
		</li>

		<!-- subject -->
		<li class="has-seperator">
			<label class="form-label">
				<?php echo JText::_('COM_COMMUNITY_COMPOSE_SUBJECT'); ?>
				<span class="required-sign"> *</span>
			</label>
			<div class="form-field">
				<div class="input-wrap">
					<div class="cStream-Respond">
						<div style="margin-top:0">
							<form class="reset-gap">
								<div class="joms-stream-input-attach">
									<div class="cStream-FormInput">
										<input type="text" name="subject" class="cStream-FormText textarea" value="<?php echo htmlspecialchars($data->subject) ?>">
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</li>

		<!-- message -->
		<li>
			<label class="form-label">
				<?php echo JText::_('COM_COMMUNITY_COMPOSE_MESSAGE'); ?>
				<span class="required-sign"> *</span>
			</label>
			<div class="form-field">
				<div class="input-wrap">
					<div class="cStream-Respond">
						<div style="margin-top:0" data-type="wall-newcomment">
							<form class="reset-gap">
								<div class="joms-stream-input-attach">
									<div class="cStream-FormInput"><textarea id="message-body" class="cStream-FormText" name="comment"><?php echo $data->body ?></textarea></div>
									<div class="joms-stream-input-attachbtn joms-icon-camera" data-action="attach" style="top:0"></div>
								</div>
								<div class="joms-stream-attachment" style="display:none;">
									<div class="joms-loading"><img src="<?php echo JURI::root(true) ?>/components/com_community/assets/ajax-loader.gif"></div>
									<div class="joms-thumbnail"><img></div>
									<span class="joms-fetched-close" data-action="remove-attach"><i class="joms-icon-remove"></i></span>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</li>

		<?php echo $afterFormDisplay; ?>

		<!-- buttons -->
		<li class="has-seperator">
			<div class="form-field">
				<span class="form-helper"><?php echo JText::_( 'COM_COMMUNITY_REGISTER_REQUIRED_FILEDS' ); ?></span>
			</div>
		</li>
		<li>
			<div class="form-field">
				<input type="hidden" name="action" value="doSubmit"/>
				<input id="submitBtn" class="btn btn-primary validateSubmit" name="submitBtn" type="submit" value="<?php echo JText::_('COM_COMMUNITY_INBOX_SEND_MESSAGE'); ?>" />
			</div>
		</li>
	</ul>
</form>
<?php
	}
?>
</div>
</div>
