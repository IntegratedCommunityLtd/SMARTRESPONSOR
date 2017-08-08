<?php
/**
 * @package     com_vmvendor
 * @copyright   Copyright (C) 2016. All rights reserved.
 * @license     GNU General Public License version 3; see LICENSE.txt
 * @author      Nordmograph <contact@nordmograph.com> - https://www.nordmograph.com/extensions
 */
// no direct access
defined('_JEXEC') or die;
$cparams 		= JComponentHelper::getParams('com_vmvendor');
$tipclass		= $cparams->get('tipclass','');


$canEdit = JFactory::getUser()->authorise('core.edit', 'com_vmvendor');
if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_vmvendor')) {
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>
<?php if ($this->item) : ?>

    <div class="item_fields">
        <table class="table">
            <tr>
			<th><?php echo JText::_('COM_VMVENDOR_FORM_LBL_COMMENT_ID'); ?></th>
			<td><?php echo $this->item->id; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_VMVENDOR_FORM_LBL_COMMENT_PARENT'); ?></th>
			<td><?php echo $this->item->parent; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_VMVENDOR_FORM_LBL_COMMENT_VENDOR_USERID'); ?></th>
			<td><?php echo $this->item->vendor_userid; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_VMVENDOR_FORM_LBL_COMMENT_TITLE'); ?></th>
			<td><?php echo $this->item->title; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_VMVENDOR_FORM_LBL_COMMENT_COMMENT'); ?></th>
			<td><?php echo $this->item->comment; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_VMVENDOR_FORM_LBL_COMMENT_LANG'); ?></th>
			<td><?php echo $this->item->lang; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_VMVENDOR_FORM_LBL_COMMENT_CREATED_BY'); ?></th>
			<td><?php echo $this->item->created_by_name; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_VMVENDOR_FORM_LBL_COMMENT_CREATED_ON'); ?></th>
			<td><?php echo $this->item->created_on; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_VMVENDOR_FORM_LBL_COMMENT_REPORTS'); ?></th>
			<td><?php echo $this->item->reports; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_VMVENDOR_FORM_LBL_COMMENT_STATE'); ?></th>
			<td>
			<i class="icon-<?php echo ($this->item->state == 1) ? 'publish' : 'unpublish'; ?>"></i></td>
</tr>

        </table>
    </div>
    <?php if($canEdit && $this->item->checked_out == 0): ?>
		<a class="btn" href="<?php echo JRoute::_('index.php?option=com_vmvendor&task=comment.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_VMVENDOR_EDIT_ITEM"); ?></a>
	<?php endif; ?>
								<?php if(JFactory::getUser()->authorise('core.delete','com_vmvendor')):?>
									<a class="btn" href="<?php echo JRoute::_('index.php?option=com_vmvendor&task=comment.remove&id=' . $this->item->id, false, 2); ?>"><?php echo JText::_("COM_VMVENDOR_DELETE_ITEM"); ?></a>
								<?php endif; ?>
    <?php
else:
    echo JText::_('COM_VMVENDOR_ITEM_NOT_LOADED');
endif;
?>
