<?php // no direct access
defined('_JEXEC') or die('Restricted access'); 
//Addding Main CSS/JS VM_Theme files to header
//JHTML::stylesheet("theme.css", VM_THEMEURL);
//JHTML::stylesheet("template.css", "components/com_wishlist/");

$itemid = JRequest::getInt('Itemid',  1);
$i = 0;
$my_page =& JFactory::getDocument();
$conf =& JFactory::getConfig();
$sitename = $conf->get('sitename');
$my_page->setTitle($sitename. ' - ' .JText::_( 'VM_SHARED_LIST' )); 
?>
<form class="form-validate" role="form">
<?php 	if (empty( $this->data )){ ?>
	<h4 class='fav_header'><?php echo JText::_('VM_SHAREDLISTS_EMPTY') ?></h4>
	<?php	}
else { ?>	
	<table class="table table-striped table-hover table-condensed" width="100%">
	<thead>
		<tr>
			<th colspan="5">
				<h4><span class="fav_title"><?php echo JText::_( 'VM_SHARED_LIST' ); ?></span></h4>
			</th>
		</tr>
		<tr class="table_header">
			<th class="jcb_fieldDiv jcb_fieldLabel">
				<?php echo JText::_( 'FW_TYPE' ); ?>
			</th>
			<th class="jcb_fieldDiv jcb_fieldLabel">
				<?php echo JText::_( 'SHARE_DATE' ); ?>
			</th>
			<th class="jcb_fieldDiv jcb_fieldLabel">
				<?php echo JText::_( 'USER_NAME' ); ?>
			</th>
			<th class="jcb_fieldDiv jcb_fieldLabel">
				<?php echo JText::_( 'SHARE_TITLE' ); ?>
			</th>
		</tr>
	</thead>
	<tbody>
<?php 	foreach($this->data as $dataItem)
	{
		if ($i == 0) {
			$sectioncolor = "sectiontableentry2";
			$i += 1;
		}
		else {
			$sectioncolor = "sectiontableentry1";
			$i -= 1;
		}
		?> 
		<?php
		$link = JRoute::_( "index.php?option=com_wishlist&view=sharelist&user_id={$dataItem->user_id}&Itemid={$itemid}" );
		?>
		


		<?php if ($dataItem->isWishList) { ?>
		<tr class="<?php echo $sectioncolor ?> success">
		<td class="success">		
			<img src="components/com_wishlist/images/wishlist.png" title="<?php echo JText::_( 'VM_WISHLIST_TRUE' ); ?>" alt="<?php echo JText::_( 'VM_WISHLIST_TRUE' ); ?>" />
			<?php } 
		else {
			?>
		<tr class="<?php echo $sectioncolor ?> warning">	
		<td class="warning">	
			<img src="components/com_wishlist/images/favorites.png" title="<?php echo JText::_( 'VM_FAVORITES_TRUE' ); ?>" alt="<?php echo JText::_( 'VM_FAVORITES_TRUE' ); ?>" />
			<?php } ?>
		</td>
		<td class="jcb_fieldDiv jcb_fieldValue">
		<?php echo JHtml::date($dataItem->share_date, JText::_('DATE_FORMAT_LC4')); ?>
		</td>
		<td class="jcb_fieldDiv jcb_fieldValue">
		<?php echo $dataItem->name; ?>
		</td>
		<td class="jcb_fieldDiv jcb_fieldValue">
		<a href="<?php echo $link; ?>"><?php echo $dataItem->share_title; ?></a>
		</td>
		</tr>
		<?php
	}
}
?>
<tbody>
<tfoot>
<tr>
<td colspan="5">
<div class="jcb_pagination"><?php echo $this->pagination->getPagesLinks(); ?><?php echo $this->pagination->getPagesCounter(); ?></div>
</td>
</tr>
</tfoot>
</table>
</form>
