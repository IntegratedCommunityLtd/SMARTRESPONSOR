<?php
defined('_JEXEC') or die;
?>
<div class="row foot">
    <div class="col-xs-12 col-lg-1 col-sm-12 f1 hidden-xs"></div>
    <div class="col-xs-12 col-lg-2 col-sm-12 f2-3"><jdoc:include type="modules" name="foot2-3" /></div>
	<div class="col-xs-12 col-lg-2 col-sm-12 f4-5"><jdoc:include type="modules" name="foot4-5" style="smart" /></div>
	<div class="col-xs-12 col-lg-2 col-sm-12 f6-7"><jdoc:include type="modules" name="foot6-7" style="smart" /></div>
	<div class="col-xs-12 col-lg-2 col-sm-12 f8-9"><jdoc:include type="modules" name="foot8-9" style="smart" /></div>
	<div class="col-xs-12 col-lg-2 col-sm-12 f10-11"><jdoc:include type="modules" name="foot10-11" style="smart" />
			<?php if($this->countModules('rightscrolltop')) : ?>
				<jdoc:include type="modules" name="rightscrolltop" />
			<?php endif; ?>
	</div>
	<div class="col-xs-12 col-lg-2 col-sm-12 f12"><jdoc:include type="modules" name="foot11-12" /></div>
</div>
<div class="row foot">
    <div class="col-xs-12 col-lg-1 col-sm-12 f10"></div>
    <div class="col-xs-12 col-lg-2 col-sm-12 f20-30"><jdoc:include type="modules" name="foot20-30" /></div>
	<div class="col-xs-12 col-lg-2 col-sm-12 f40-50"><jdoc:include type="modules" name="foot40-50" style="smart" /></div>
	<div class="col-xs-12 col-lg-2 col-sm-12 f60-70"><jdoc:include type="modules" name="foot60-70" style="smart" /></div>
	<div class="col-xs-12 col-lg-2 col-sm-12 f80-90"><jdoc:include type="modules" name="foot80-90" style="smart" /><?php //require JPATH_BASE .  '/templates/html5/vk_group.php'; // Группа в VK ?></div>
	<div class="col-xs-12 col-lg-2 col-sm-12 f100-110"><jdoc:include type="modules" name="foot100-110" style="smart" /></div>
	<div class="col-xs-12 col-lg-1 col-sm-12 f120"><jdoc:include type="modules" name="foot110-120" /></div>
	<div class="col-xs-12 col-lg-1 col-sm-12 f120"><?php //require JPATH_BASE .  '/templates/html5/fb_page.php'; // Страница с ФБ https://www.facebook.com/smartresponsor ?></div>
</div>