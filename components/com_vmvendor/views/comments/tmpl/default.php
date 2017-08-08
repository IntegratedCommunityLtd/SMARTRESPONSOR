<?php
/**
 * @package     com_vmvendor
 * @copyright   Copyright (C) 2016. All rights reserved.
 * @license     GNU General Public License version 3; see LICENSE.txt
 * @author      Nordmograph <contact@nordmograph.com> - https://www.nordmograph.com/extensions
 */
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');

$doc		= JFactory::getDocument();
$date 		= JFactory::getDate();
$user       = JFactory::getUser();
$userId     = $user->get('id');
$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
$canCreate  = $user->authorise('core.create', 'com_vmvendor');
$canEdit    = $user->authorise('core.edit', 'com_vmvendor');
$canCheckin = $user->authorise('core.manage', 'com_vmvendor');
$canChange  = $user->authorise('core.edit.state', 'com_vmvendor');
$canDelete  = $user->authorise('core.delete', 'com_vmvendor');

$cparams    	= JComponentHelper::getParams('com_vmvendor');
$tipclass		= $cparams->get('tipclass','');

$vendorprofile_url = JRoute::_('index.php?option=com_vmvendor&view=vendorprofile&userid='.$this->vendoruserid.'&Itemid='.$this->vendorprofileItemid);
echo '<h1><i class="vmv-icon-comment" ></i> '.JText::_('COM_VMVENDOR_COMMENTS_VENDORREVIEWS').'</h1>';
echo '<h3><a href="'.$vendorprofile_url.'"  title="'.JText::_('COM_VMVENDOR_PROFILE_VENDORPROFILE').'" class="'.$tipclass.'" ><i class="vmv-icon-shop" ></i> '.ucwords($this->storename) .'</a></h3>';
?>
	<?php echo JLayoutHelper::render('default_filter', array('view' => $this), dirname(__FILE__)); ?>
	<table class="table table-striped" id="commentList">
		
	
		<?php foreach ($this->items as $i => $item) : 

		$canEdit = $user->authorise('core.edit', 'com_vmvendor'); 
		 if (!$canEdit && $user->authorise('core.edit.own', 'com_vmvendor')): 
							 $canEdit = JFactory::getUser()->id == $item->created_by;
					 endif; ?>

			<tr >

				

							
				<td>
                <?php if (isset($this->items[0]->state)): ?>
					<?php $class = ($canEdit || $canChange) ? 'active' : 'disabled'; ?>
				
	<a class="btn btn-micro <?php echo $class; ?>" href="<?php echo ($canEdit || $canChange) ? JRoute::_('index.php?option=com_vmvendor&task=XXX_TABLE_ITEM_NAME_XXX.publish&id=' . $item->id . '&state=' . (($item->state + 1) % 2), false, 2) : '#'; ?>">
		<?php if ($item->state == 1): ?>
			<i class="icon-publish"></i>
		<?php endif; ?>
	</a>
				<?php endif;
				
						
						
						 ?>
				<?php if (isset($item->checked_out) && $item->checked_out){
					echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'comments.', $canCheckin); 
					} 
				 echo '<div class="vmvcomment-title" >" <i>'.ucfirst( $this->escape($item->title) ).'</i></div>'; 
         		$date = new JDate($item->created_on);
				$date = $date->toUnix();
                echo '<div class="vmvcomment-comment" >'.ucfirst( $this->escape($item->comment) ).'</div>'; 
				echo '<div class="vmvcomment-created" ><i class="vmv-icon-user" ></i> '.
				 sprintf( JText::_('COM_VMVENDOR_COMMENTS_CREATEDONBY') , JHTML::_('date', $date, JText::_($this->date_display) ) , $item->username ).'</div>'; 
				  
				 ?>
				</td>
				
<td class="center">
								<?php if ($canEdit || $item->created_by == $user->id || $canDelete ): ?>
					
						<?php if ($canEdit): ?>
							<a href="<?php echo JRoute::_('index.php?option=com_vmvendor&task=commentform.edit&id=' . $item->id, false ); ?>" class="btn btn-mini" type="button"><i class="vmv-icon-edit" ></i></a>
						<?php endif; 
						
						if ($item->created_by == $user->id || $canDelete  )
						{
						
						$confirm_alert="function sweetConfirm(it)
					{
						swal(
							{   title: '', 
						  text: '".JText::_( 'COM_VMVENDOR_COMMENTS_DELETEAREYOUSURE' )."', 
						  type: 'warning',
						  confirmButtonColor: 'green',   
						  cancelButtonColor: '#d33',   
						  confirmButtonText: '".JText::_('JYes')."',
						  showCancelButton: true,   
							},
							function(confirm) 
							{  
								if(confirm)
									it.submit();
							}
						)
					}";
					$doc->addScriptDeclaration( $confirm_alert );
					
					
							echo '<form id="delete_review'.$item->id.'" 
							action="'.JRoute::_('index.php?option=com_vmvendor&view=comments').'" 
							method="post" name="adminForm" id="adminForm">
							<button data-item-id="'.$item->id.'"  
							 class="btn btn-mini btn-warning delete-button" type="button" 
                            onclick="sweetConfirm( document.getElementById(\'delete_review'.$item->id.'\') );"
                            ><i class="vmv-icon-trash" ></i></button>
							<input type="hidden" name="controller" value="comments" />
							<input type="hidden" name="task" value="deletereview" />
							<input type="hidden" name="id" value="'.$item->id.'" />
							<input type="hidden" name="vendoruserid" value="'.$this->vendoruserid.'" />';
							echo JHtml::_('form.token'); 
							echo '</form>';
						}
						
					endif; 
					?>
				</td>
			</tr>
		<?php endforeach; ?>

	</table>
<?php 
if( count( $this->items )<1 )
{
	echo JText::_('COM_VMVENDOR_COMMENTS_NOREVIEWSYET');	
}

if( count( $this->items )>1 )
	echo $this->pagination->getListFooter(); 

     if ($canCreate || $this->iscustomer)
	 { 
		echo '<div><a href="'.JRoute::_('index.php?option=com_vmvendor&view=commentform&vendoruserid='.$this->vendoruserid).'"
			class="btn btn-success btn-small"><i
				class="icon-plus"></i> '.JText::_('COM_VMVENDOR_COMMENTS_ADD_REVIEW').'</a></div>';
	} 
	?>
	
