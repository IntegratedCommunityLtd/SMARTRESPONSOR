<?php
defined('_JEXEC') or die('Restricted access');
?>

<?php //com_community ?>
<div class="other-btn-panel btn-group pull-left">
	<a href="#" onclick="history.back();" class="btn btn-primary" title=""><span class="glyphicon glyphicon-chevron-left"></span><span class="hidden-xs"> <?php echo vmText::sprintf('COM_VIRTUEMART_CATEGORY_BACK_TO') ?></span></a>
</div>
<div class="other-btn-panel btn-group pull-right">
 	<button href="#" onclick="#" class="btn btn-primary" disabled="disabled"><span class="glyphicon glyphicon-envelope visible-xs"></span><span class="hidden-xs"><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_ENQUIRY_LBL') ?></span></button>
	<button id="check-all" href="#" class="btn btn-primary" disabled="disabled"><?php echo vmText::_ ( 'COM_VIRTUEMART_CHECK_ALL' ); ?></button>	
	<button id="fullscreen-btn" href="#" onclick="openbox(event, 'b1');" class="btn btn-primary hidden-xs" disabled="disabled"><span id="resize-full" class="glyphicon glyphicon-fullscreen visible-xs"></span><span class="hidden-xs"> <?php echo vmText::_ ( 'COM_VIRTUEMART_FULLSCREEN' ); ?></span></button> 
</div>  	
<?php //com_virtuemart sublyout ?>
<div class="other-btn-panel btn-group pull-left">
	<?php // Back To Category Button
		if ($this->product->virtuemart_category_id) {
			$catURL =  JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$this->product->virtuemart_category_id, FALSE);
			$categoryName = vmText::_($this->product->category_name) ;
			$buttonDisabld = '';
		} else {
			$catURL =  JRoute::_('index.php?option=com_virtuemart');
			$categoryName = vmText::_('COM_VIRTUEMART_SHOP_HOME') ;
			$buttonDisabld = 'disabled="disabled"';
		}
		?>
    <a href="<?php echo $catURL ?>" class="btn btn-primary" title="<?php echo $categoryName ?>" <?php echo $buttonDisabld ?>"><span class="glyphicon glyphicon-chevron-left"></span><span class="hidden-xs"> <?php echo vmText::sprintf('COM_VIRTUEMART_CATEGORY_BACK_TO',$categoryName) ?></span></a>
</div>
<div class="other-btn-panel btn-group pull-right">
 	<button href="#" onclick="#" class="btn btn-primary" disabled="disabled"><span class="glyphicon glyphicon-envelope visible-xs"></span><span class="hidden-xs"><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_ENQUIRY_LBL') ?></span></button>
	<button id="check-all" href="#" class="btn btn-primary" disabled="disabled"><?php echo vmText::_ ( 'COM_VIRTUEMART_CHECK_ALL' ); ?></button>	
	<button id="fullscreen-btn" href="#" onclick="openbox(event, 'b1');" class="btn btn-primary hidden-xs" disabled="disabled"><span id="resize-full" class="glyphicon glyphicon-fullscreen visible-xs"></span><span class="hidden-xs"> <?php echo vmText::_ ( 'COM_VIRTUEMART_FULLSCREEN' ); ?></span></button> 
</div> 


 	<button href="javascript:" onclick="joms.api.pmSend(1629);" class="btn btn-primary"><span class="glyphicon glyphicon-envelope visible-xs"></span><span class="hidden-xs"><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_ENQUIRY_LBL') ?></span></button>
		if ($user->guest) {

							if(JRequest::getCmd('option') != 'com_community') {
							//require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php');
							// Для работы сквозной панели
							require_once JPATH_BASE .  '/components/com_community/libraries/core.php'; // Для плагина Вопрос Вендору
							}

		<a href="<?php echo $product->link.$ItemidStr ?>" title="<?php echo $product->product_name; ?>" class="btn btn-default" disabled="disabled"><span class="glyphicon glyphicon-pushpin"></span><span class="hidden-xs"> <?php echo vmText::_ ( 'COM_VIRTUEMART_PRODUCT_DETAILS' ); ?></span></a>




</div>



</div>
<?php //Вспомогательные кнопки ?>
<div class="other-btn-panel btn-group pull-right">
	<button id="check-all" href="#" class="btn btn-primary" disabled="disabled"><?php echo vmText::_ ( 'COM_VIRTUEMART_CHECK_ALL' ); ?></button>
	<button id="fullscreen-btn" href="#" onclick="openbox(event, 'b1');" class="btn btn-primary hidden-xs"><?php echo vmText::_ ( 'COM_VIRTUEMART_FULLSCREEN' ); ?></button>	
</div>
<div class="clear"></div>

<?php //Вспомогательные кнопки ?>
<div class="other-btn-panel btn-group pull-left">
	<a href="/" onclick="history.back();" class="btn btn-primary pull-left"><span class="glyphicon glyphicon-chevron-left"></span></a>
	<?php echo $this->orderByList['orderby']; ?>
	<?php //echo $this->orderByList['manufacturer'];    Страна сверстана, но в ней нет необходимости из-за бокового (правого) фильтра ?>
	<?php echo JModuleHelper::renderModule($module[0], $attribs); // выводим первый модуль из заданной позиции ?>
</div>

<div class="other-btn-panel btn-group pull-right">

	<button id="check-all" href="#" class="btn btn-primary" disabled="disabled"><?php echo vmText::_ ( 'COM_VIRTUEMART_CHECK_ALL' ); ?></button>
	<button id="fullscreen-btn" href="#" onclick="openbox(event, 'b1');" class="btn btn-primary hidden-xs"><span class="glyphicon glyphicon-fullscreen visible-xs"></span><span class="hidden-xs"> <?php echo vmText::_ ( 'COM_VIRTUEMART_FULLSCREEN' ); ?></button>
</div>
<div class="clear"></div>

<?php //Вспомогательные кнопки ?>
<div class="other-btn-panel btn-group pull-right">
	<button id="check-all" href="#" class="btn btn-primary" disabled="disabled"><?php echo vmText::_ ( 'COM_VIRTUEMART_CHECK_ALL' ); ?></button>
	<button id="fullscreen-btn" href="#" onclick="openbox(event, 'b1');" class="btn btn-primary hidden-xs"><?php echo vmText::_ ( 'COM_VIRTUEMART_FULLSCREEN' ); ?></button>	
</div>
<div class="clear"></div>