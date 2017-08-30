<?php
/**
 * @package Sj Filter for VirtueMart
 * @version 3.0.1
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2015 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 *
 */

defined('_JEXEC') or die;

$currency = CurrencyDisplay::getInstance();
$symbol = $currency->getSymbol();
$_cls_open_close = ((int)$params->get('openform_prices', 1)) ? ' ft-open ' : ' ft-close ';

if((int)$params->get('price_min') == 0 || (int)$params->get('price_min') < 0){
	$price_min = '10';
}else{
	$price_min = (int)$params->get('price_min');
}
if((int)$params->get('price_max') == 0 || (int)$params->get('price_max') < 0){
	$price_max = '1000';
}else{
	$price_max = (int)$params->get('price_max');
}
?>

<div class=" ft-group <?php echo $_cls_open_close; ?> ft-group-prices">
<?php
/*	
	<div class="ft-heading ">
		<div class="ft-heading-inner">
			<span class="pull-left"><?php echo JText::_('SEARCH_PRICE_LABEL'); ?></span>
			<?php //echo JText::_('PRICE'); ?>
			<span class="pull-right glyphicon glyphicon-chevron-up"></span>
		</div>
	</div>
*/
?>		
	<div class="ft-content ft-content-prices">
		<ul class="ft-select">
			<li class="ft-option ft-prices ">
				<div class="ft-opt-inner">
					<span class="ft-price-value ft-price-left">
						<input type="text" maxlength="6" class="ft-price-min ft-price-input" name="ft_price_min"
							   value="<?php echo $price_min; ?>"/>
						<span class="ft-price-curent">
							<?php echo $symbol; ?>
						</span>
					</span>
					<span class="ft-price-label"><?php echo JText::_('MOD_SJ_TO'); ?></span>
					<span class="ft-price-value ft-price-right">
						<input type="text" maxlength="6" class="ft-price-max ft-price-input" name="ft_price_max"
							   value="<?php echo $price_max; ?>"/>
						<span class="ft-price-curent">
							<?php echo $symbol; ?>
						</span>
					</span>
				</div>
				<div class="ft-slider-price"></div>
			</li>
		</ul>
		<button type="button" class="ft-opt-clearall"><?php echo JText::_('MOD_SJ_CLEAR_ALL'); ?></button>
	</div>
</div>
