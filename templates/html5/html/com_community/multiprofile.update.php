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

if( $fields )
{
	$required	= false;
?>
	<form action="<?php echo CRoute::getURI(); ?>" method="post" id="jomsForm" name="jomsForm" class="community-form-validate">
<?php
	foreach( $fields as $name => $fieldGroup )
	{
		$fieldName	= $name == 'ungrouped' ? '' : $name;
?>
		<div class="ctitle">
			<h4><?php echo JText::_( $fieldName ); ?></h4>
		</div>

		<ul class="cFormList cFormHorizontal cResetList">



<?php
		foreach($fieldGroup as $field )
		{
			$field = JArrayHelper::toObject ( $field );
			if( !$required && $field->required == 1 )
			{
				$required	= true;
			}

			$html = CProfileLibrary::getFieldHTML($field);
?>
				<li>
					<label id="lblfield<?php echo $field->id;?>" for="field<?php echo $field->id;?>" class="form-label">
					<?php echo JText::_($field->name); ?>
					<?php if($field->required == 1) echo '<span class="required-sign"> *</span>'; ?>
					</label>
					<div class="form-field">
						<?php echo $html; ?>
					</div>
				</li>
<?php
		}
?>
		</ul>
<?php
	}
?>

	<ul class="cFormList cFormHorizontal cResetList">
	<?php
	if( $required )
	{
	?>
		<li>
			<label class="form-label" >&nbsp;</label>
			<div class="form-field"><?php echo JText::_( 'COM_COMMUNITY_REGISTER_REQUIRED_FILEDS' ); ?></div>
		</li>
<?php
	}
?>
		<li>
			<label class="form-label" >&nbsp;</label>
			<div class="form-field">
				<div id="cwin-wait" style="display:none;"></div>
				<input class="button validateSubmit" type="submit" id="btnSubmit" value="<?php echo JText::_('COM_COMMUNITY_NEXT'); ?>" name="submit">
			</div>

		</li>

	</ul>

	<input type="hidden" name="profileType" value="<?php echo $profileType;?>" />
	<input type="hidden" name="task" value="updateProfile" />
	</form>
	<script type="text/javascript">
	    cvalidate.init();
	    cvalidate.setSystemText('REM','<?php echo addslashes(JText::_("COM_COMMUNITY_ENTRY_MISSING")); ?>');

		joms.jQuery( '#jomsForm' ).submit( function() {
		    joms.jQuery('#btnSubmit').hide();
			joms.jQuery('#cwin-wait').show();
		});
	</script>
<?php
}
else
{
?>
	<div><?php echo JText::_('COM_COMMUNITY_NO_CUSTOM_PROFILE_CREATED_YET');?></div>
<?php
}
?>