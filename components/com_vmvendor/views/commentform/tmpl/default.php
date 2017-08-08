<?php
/**
 * @package     com_vmvendor
 * @copyright   Copyright (C) 2016. All rights reserved.
 * @license     GNU General Public License version 3; see LICENSE.txt
 * @author      Nordmograph <contact@nordmograph.com> - https://www.nordmograph.com/extensions
 */
// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidator');


$cparams 		= JComponentHelper::getParams('com_vmvendor');
$profileman 	= $cparams->get('profileman', 0);
$vmvcomment_autopublish		= $cparams->get('vmvcomment_autopublish',1);

$tipclass		= $cparams->get('tipclass','');
$formbehavior_chosen 	= $cparams->get('formbehavior_chosen',1);
if($formbehavior_chosen)
	JHtml::_('formbehavior.chosen', 'select');

$app = JFactory::getApplication();
//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_vmvendor', JPATH_ADMINISTRATOR);
$doc = JFactory::getDocument();
//$doc->addScript(JUri::base() . '/components/com_vmvendor/assets/js/form.js');

$vendor_userid = $app->input->get->getInt('vendoruserid');
$Itemid 		= $app->input->get->getInt('Itemid');


?>
</style>
<script type="text/javascript">
    if (jQuery === 'undefined') {
        document.addEventListener("DOMContentLoaded", function(event) { 
            jQuery('#form-comment').submit(function(event) {
                
            }); 
        });
    } else {
        jQuery(document).ready(function() {
            jQuery('#form-comment').submit(function(event) {
                
            });

            
        });
    }
</script>

<div class="comment-edit front-end-edit">
    <?php if (!empty($this->item->id)): ?>
        <h1>Edit <?php echo $this->item->id; ?></h1>
    <?php else: ?>
        <h1><i class="vmv-icon-comment"></i> <?php echo JText::_('COM_VMVENDOR_COMMENTS_ADDNEWREVIEWTOVENDOR') ?></h1>
      <div class="well well-small">
      <i class="vmv-icon-info-sign"></i> <?php echo JText::_('COM_VMVENDOR_COMMENTS_ADDNEWREVIEWTOVENDOR_DESC') ?>
      </div>
    <?php endif; 
	
	echo '<h3><i class="vmv-icon-shop"></i> '.ucfirst( $this->storename ).'</h3>' ;?>

    <form id="form-comment" action="<?php echo JRoute::_('index.php?option=com_vmvendor&view=commentform&vendor='.$vendor_userid.''); ?>" method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
        
	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />

	<input type="hidden" name="jform[parent]" value="<?php echo $this->item->parent; ?>" />

	<input type="hidden" name="jform[vendor_userid]" value="<?php echo $vendor_userid; ?>" />

	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('title'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('title'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('comment'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('comment'); ?></div>
	</div>
    <?php 
	if( ($profileman =='js' OR $profileman=='es') && $vmvcomment_autopublish )
	{
	?>
		<div class="control-group">
		<div class="controls"><?php echo $this->form->getInput('commentactivitystream'); ?> 
		<?php echo $this->form->getLabel('commentactivitystream'); ?></div>
	</div>		
	<?php 
	}?>
    <input type="hidden" name="jform[lang]" value="<?php echo JFactory::getLanguage()->getTag(); ?>" />
    
	<?php if(empty($this->item->created_by)): ?>
		<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />
	<?php else: ?>
		<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />
	<?php endif; ?>
	<input type="hidden" name="jform[created_on]" value="<?php echo $this->item->created_on; ?>" />

	<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />

	<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />

	<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

	<input type="hidden" name="jform[reports]" value="<?php echo $this->item->reports; ?>" />

	<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />

        <div class="control-group">
            <div class="controls">
            <?php
			if($this->isCustomer)
			{ ?>
                <button type="submit" class="validate btn btn-primary"> <i class="vmv-icon-ok"></i> <?php echo JText::_('JSUBMIT'); ?></button>
            <?php } ?>
                <a class="btn" href="<?php echo JRoute::_('index.php?option=com_vmvendor&view=vendorprofile&userid='.$vendor_userid.'&Itemid='.$Itemid); ?>" title="<?php echo JText::_('JCANCEL'); ?>"><i class="vmv-icon-cancel"></i> <?php echo JText::_('JCANCEL'); ?></a>
            </div>
        </div>
        <input type="hidden" name="controller" value="commentform" />
        <input type="hidden" name="option" value="com_vmvendor" />
        <input type="hidden" name="task" value="commentform.save" />
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>
