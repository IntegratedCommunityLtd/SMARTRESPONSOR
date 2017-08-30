<?php
/**
 * @package     Smartresponsor.Site
 * @subpackage  mod_breadcrumbs_dropdown
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

//JHtml::_('bootstrap.tooltip');
?>
111
<div class="btn-group">
    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
		<?php echo JText::_('MOD_BREADCRUMBS_HERE'); ?>
		<span class="glyphicon glyphicon-chevron-down"></span>
      </button>
<ul itemscope itemtype="http://schema.org/BreadcrumbList" class="dropdown-menu <?php echo $moduleclass_sfx; ?>">
    
	<?php
	// Get rid of duplicated entries on trail including home page when using multilanguage
	for ($i = 0; $i < $count; $i++)
	{
		if ($i == 1 && !empty($list[$i]->link) && !empty($list[$i - 1]->link) && $list[$i]->link == $list[$i - 1]->link)
		{
			unset($list[$i]);
		}
	}

	// Find last and penultimate items in breadcrumbs list
	end($list);
	$last_item_key = key($list);
	prev($list);
	$penult_item_key = key($list);

	// Make a link if not the last item in the breadcrumbs
	$show_last = $params->get('showLast', 1);

	// Generate the trail
	foreach ($list as $key => $item) :
		if ($key != $last_item_key) :
			// Render all but last item - along with separator ?>
			<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
				<?php if (!empty($item->link)) : ?>
					<a itemprop="item" href="<?php echo $item->link; ?>" class="">
						<!-- <span itemprop="name"> -->
							<?php echo $item->name; ?>
						<!-- </span> -->
					</a>
				<?php else : ?>
					<a itemprop="item" href="" class="">
					<!-- <span itemprop="name"> -->
						<?php $item->name; ?>
					<!-- </span> -->
					</a>
				<?php endif; ?>

				<?php if (($key != $penult_item_key) || $show_last) : ?>

				<?php endif; ?>
				<meta itemprop="position" content="<?php echo $key + 1; ?>">
			</li>
		<?php elseif ($show_last) :
			// Render last item if reqd. ?>
			<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="">
				<button type="button" class="btn btn-link" disabled="disabled">
					<?php echo $item->name; ?>
				</button>
				<meta itemprop="position" content="<?php echo $key + 1; ?>">
			</li>
		<?php endif;
	endforeach; ?>
<ul>		
</div>	