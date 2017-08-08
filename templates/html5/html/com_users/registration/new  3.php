<?php
defined('_JEXEC') or die;
//JHtml::_('behavior.keepalive');
//JHtml::_('behavior.tooltip');
//JHtml::_('behavior.formvalidation');

?>

<style>
 .z1, .z2, .z11-12{display:none!important;width:0px;}
.z3-10{width:inherit;float: inherit;padding: 0px;}
#smarttop {margin-top:0px;}
.active{border:none!important;}
#page {margin-top:40px;}
#nent {max-width:100%!important;width:100%;}
</style>

<!-- <link rel="stylesheet" type="text/css" href="http://smartresponsor.com/css/animate.css" /> -->
<!-- <link rel="stylesheet" type="text/css" href="http://smartresponsor.com/css/eskju.jquery.scrollflow.css" /> -->

<link rel="stylesheet" type="text/css" href="http://smartresponsor.com/css/smarticon.css" />
<link rel="stylesheet" type="text/css" href="http://smartresponsor.com/css/sigin.css" />

<div id="page" class="page">

<?php
/*

<div id="sigin" class="section login<?php echo $this->pageclass_sfx?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
	<h1>
		<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
	<?php endif; ?>

	<?php if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', $this->params->get('login_description')) != '') || $this->params->get('login_image') != '') : ?>
	<div class="login-description">
	<?php endif ; ?>

		<?php if($this->params->get('logindescription_show') == 1) : ?>
			<?php echo $this->params->get('login_description'); ?>
		<?php endif; ?>

		<?php if (($this->params->get('login_image')!='')) :?>
			<img src="<?php echo $this->escape($this->params->get('login_image')); ?>" class="login-image" alt="<?php echo JTEXT::_('COM_USER_LOGIN_IMAGE_ALT')?>"/>
		<?php endif; ?>

	<?php if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', $this->params->get('login_description')) != '') || $this->params->get('login_image') != '') : ?>
	</div>
	<?php endif ; ?>
</div>

*/
?>

<?php /*
<div id="compaign" class="carousel slide" data-interval="false">  
      <div class="carousel-inner">
							
        <div class="item active">

        </div>

      </div>
      <a class="left carousel-control" href="#compaign<?php echo $compaignslideid ?>" data-slide="prev"><span class="icon-arrow-left"></span></a>
      <a class="right carousel-control" href="#compaign<?php echo $compaignslideid ?>" data-slide="next"><span class="icon-arrow-right"></span></a>
		<ol class="carousel-indicators">
							
			<li data-target="#compaign<?php echo $compaignslideid ?>" data-slide-to="<?php echo $i ?>" <?php if ($i == 0) echo "class=\" active\""; ?>></li>
	
			</ol>
   </div>
	*/ ?>
	<?php /*

			<?php //АВТОРИЗАЦИЯ ЧЕРЕЗ СОЦИАЛЬНЫЕ СЕТИ ?>
			<div id="socnetwork" class="socnetwork" >
                    <a rel="nofollow" href="/component/slogin/provider/facebook/auth/aW5kZXgucGhwP0l0ZW1pZD0yOTQ="><span class="icon-facebook"></span></a>
                    <a rel="nofollow" href="/component/slogin/provider/google/auth/aW5kZXgucGhwP0l0ZW1pZD0yOTQ="><span class="icon-google__x2B_"></span></a>
                    <a rel="nofollow" href="/component/slogin/provider/mail/auth/aW5kZXgucGhwP0l0ZW1pZD0yOTQ="><span class="icon-mailru"></span></a>
                    <a rel="nofollow" href="/component/slogin/provider/odnoklassniki/auth/aW5kZXgucGhwP0l0ZW1pZD0yOTQ="><span class="icon-odnoklassniki"></span></a>
                    <a rel="nofollow" href="/component/slogin/provider/twitter/auth/aW5kZXgucGhwP0l0ZW1pZD0yOTQ="><span class="icon-twitter-3"></span></a>
                    <a rel="nofollow" href="/component/slogin/provider/vkontakte/auth/aW5kZXgucGhwP0l0ZW1pZD0yOTQ="><span class="icon-rus-vk-02"></span></a>
                    <a rel="nofollow" href="/component/slogin/provider/yandex/auth/aW5kZXgucGhwP0l0ZW1pZD0yOTQ="><span class="icon-rus-yandex-02"></span></a>
                    <a rel="nofollow" href="/component/slogin/provider/linkedin/auth/aW5kZXgucGhwP0l0ZW1pZD0yOTQ="><span class="icon-linkedin"></span></a>
                    <a rel="nofollow" href="/component/slogin/provider/yahoo/auth/aW5kZXgucGhwP0l0ZW1pZD0yOTQ="><span class="icon-yahoo"></span></a>
                    <a rel="nofollow" href="/component/slogin/provider/instagram/auth/aW5kZXgucGhwP0l0ZW1pZD0yOTQ="><span class="icon-instagram"></span></a><br>
					<a  href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>"><span><?php echo JText::_('COM_USERS_REGISTER_DEFAULT_LABEL'); ?></span></a>

			</div>


	*/ ?>

<div id="guest" class="section ">
		<h2><?php echo JText::_('COM_USERS_SIGIN_CONNECT') ?></h2>
<?php
/*		
			<form id="login-form" class="form-inline" role="form" action="<?php echo JRoute::_('index.php?option=com_users&task=user.login'); ?>" method="post"  role="form">
					<div class="btn-group">					
					<div class="input-group visible-xs">
						<span class="input-group-addon glyphicon glyphicon-user"></span>
						<input type="text" name="username" id="modlgn-username" value="<?php echo $field->label; ?>" class="form-control" placeholder="<?php echo JText::_('COM_USERS_LOGIN_USERNAME_LABEL') ?>">
					</div>
					<div class="input-group visible-xs">
						<span class="input-group-addon glyphicon glyphicon-th"></span>
						<input type="password" name="password" id="modlgn-passwd" value="<?php echo $field->label; ?>" class="form-control" placeholder="<?php echo JText::_('COM_USERS_FIELD_RESET_PASSWORD1_LABEL') ?>">
					</div>
			
							<span class="btn btn-primary visible-xs"><input type="checkbox" class="" id="modlgn-remember" name="remember"  value="yes"/><?php echo JText::_('COM_USERS_REMEMBER_ME') ?></span>
							<button type="submit" class="btn btn-primary visible-xs" data-toggle="modal"><?php echo JText::_('JLOGIN'); ?></button>
							<input type="hidden" name="return" value="<?php echo base64_encode($this->params->get('login_redirect_url', $this->form->getValue('return'))); ?>" />
					
							<?php echo JHtml::_('form.token'); ?>
							<a  href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>" class="btn btn-warning"><?php echo JText::_('COM_USERS_REGISTER_DEFAULT_LABEL'); ?></a>
							<a  href="<?php echo JRoute::_('index.php?option=com_virtuemart&view=category'); ?>" class="btn btn-info"><?php echo JText::_('COM_PROJECTS'); ?></a>

					</div>
			</form>
*/
?>			
<?php
// Возврат модуля с меню Регистрация и Перейти к Публикациям
	$modules =JModuleHelper::getModules('auth');
		foreach ($modules as $module){
			echo JModuleHelper::renderModule($module);
		}
?>
	
<?php
//require_once JPATH_BASE .  '/templates/html5/html/com_users/registration/default.php';
//require_once JPATH_BASE . 'index.php?option=com_users&view=registration';
?>
		<div class="clear"></div>
			<span class="text-center">
				<h3 class="hidden-xs hidden-phone"><?php echo JText::_('COM_USERS_SIGIN_ITSEASY') ?></h3>
				<h3><?php echo JText::_('COM_USERS_SIGIN_WELLCOME_NET') ?></h4>
			</span>
<div class="row hidden-xs hidden-phone">
  <div class="col-md-4"></div>
  <div class="col-md-4">
  	<form id="member-registration" role="form" action="<?php echo JRoute::_('index.php?option=com_ajax&option=com_users&task=registration.register'); ?>" method="post" class="form-validate" enctype="multipart/form-data" data-toggle="validator">
		<div class="form-group">
			<?php foreach ($this->form->getFieldsets() as $fieldset): // Iterate through the form fieldsets and display each one.?>
				<?php $fields = $this->form->getFieldset($fieldset->name);?>
				<?php if (count($fields)):?>
					<fieldset>
						<dl class="dl-horizontal">					
					<?php if (isset($fieldset->label)):// If the fieldset has a label set, display it as the legend.
					?>
						<h3><?php echo JText::_($fieldset->label);?></h3>
					<?php endif;?>

					<?php foreach($fields as $field):// Iterate through the fields in the set and display them.?>
						<?php if ($field->hidden):// If the field is hidden, just display the input.?>
							<dt><?php echo $field->input;?></dt>
							<dd></dd>
						<?php else:?>
							<dt>
								<?php echo $field->label; ?>
								<?php if (!$field->required && $field->type!='Spacer'): ?>
									<span class="optional"><?php echo JText::_('COM_USERS_OPTIONAL'); ?></span>
								<?php endif; ?>
							</dt>
							<dd><?php echo ($field->type!='Spacer') ? $field->input : "&#160;"; ?></dd>
						<?php endif;?>
					<?php endforeach;?>
						</dl>
					</fieldset>
				<?php endif;?>
			<?php endforeach;?>
		<div class="pull-right">
			<button type="submit" class="btn btn-primary validate"><?php echo JText::_('JREGISTER');?></button>
			<?php //echo JText::_('COM_USERS_OR');?>
			<a href="<?php echo JRoute::_('');?>" class="btn btn-default" title="<?php echo JText::_('JCANCEL');?>"><?php echo JText::_('JCANCEL');?></a>
			<input type="hidden" name="option" value="com_users" />
			<input type="hidden" name="task" value="registration.register" />
			<?php echo JHtml::_('form.token');?>
		</div>
		</div>
	</form>
  </div>
  <div class="col-md-4"></div>
</div>				
				<h3><?php echo JText::_('COM_USERS_SIGIN_CONGRATS') ?></h4>
				<h4 class="hidden-xs hidden-phone"><?php echo JText::_('COM_USERS_IS') ?></h4>				
</div>	
<div id="visits" class="section post">
	<div class="bg-over">
		<div class="promo-content">
			<h2><?php echo JText::_('COM_USERS_SIGIN_EVERYDAY') ?></h2>
					<span class="text-center">
					<h3><?php echo JText::_('COM_USERS_SIGIN_ADD') ?></h3>
					<h4><span class=""></span></h4>
					<img src="/images/win.jpg" alt="" class="img-responsive img-circle img-label hidden-xs" style="max-width: 20em;">
					<div class="text-left">
					<h4 class=""><?php echo JText::_('COM_USERS_SIGIN_HOWTO_UL') ?></h4>
					<h6 class="text-center"><?php echo JText::_('COM_USERS_SIGIN_HOWTO_ASTERIX') ?></h6>
					</div>
					</span>
										
		</div>
	</div>
</div>
<div id="howto" class="section post" style="">
	<div class="bg-over">	
		<div class="promo-content">
			<h2><?php echo JText::_('COM_USERS_SIGIN_HOWTO') ?></h2>
					<span class="text-center">
					<h3><?php echo JText::_('COM_USERS_SIGIN_HOWTO_MYFA') ?></h3>
					<img src="/images/peoplegears.png" alt="" class="img-responsive img-label hidden-xs" style="max-width: 20em;">
					<h4 class=""><?php echo JText::_('COM_USERS_SIGIN_HOWTO_FIRST') ?></h4>
					</span>

					
		</div>
	</div>
</div>
<div id="everyday" class="section post" style="">
	<div class="slide">	
			<h2><?php echo JText::_('COM_USERS_SIGIN_COMMU_TITLE') ?></h2>
					<span class="text-center">
					<h3><?php echo JText::_('COM_USERS_SIGIN_COMMU') ?></h3>
					<img src="/images/comm.jpg" alt="" class="img-responsive img-circle img-label hidden-xs" style="max-width: 20em;">
					<h4><?php echo JText::_('COM_USERS_SIGIN_COMMUN') ?></h4>
					</span>
					
<?php
/*
						<div id="likes" class="like btn btn-primary btn-lg">
							<h2><?php echo JText::_('COM_USERS_SIGIN_LIKE') ?></h2>
							<?php
								$document	= &JFactory::getDocument();
								$renderer	= $document->loadRenderer('modules');
								$options	= array('style' => 'xhtml');
								$position	= 'sigin-like';
								echo $renderer->render($position, $options, null);
							?>
						</div>
*/
?>


		</div>

	<div class="slide">
			<h2><?php echo JText::_('COM_USERS_SIGIN_JOMSOCIAL_TITLE') ?></h2>
			<h4><?php echo JText::_('COM_USERS_SIGIN_JOMSOCIAL') ?></h4>
			<img src="/images/cube.jpg" alt="" class="img-responsive img-circle img-label hidden-xs" style="max-width: 20em;">
			
	</div>

</div>
<div id="wellcome" class="section post">
	<div class="bg-over">	
		<div class="promo-content">
			<h2><?php echo JText::_('COM_USERS_SIGIN_WELLCOME') ?></h2>
			<span class="text-center">
			<img src="/images/hand.jpg" alt="" class="img-responsive img-circle img-label hidden-xs" style="max-width: 20em;">
			<h3><?php echo JText::_('COM_USERS_SIGIN_WELLCOME_TITLE') ?></h3>
			</span>
<div class="row">
  <div class="col-md-2"></div>
  <div class="col-md-2"><img src="/images/pr.jpg" alt="" class="img-responsive img-circle" style="max-width: 120px;"><h6><?php echo JText::_('COM_USERS_PR') ?></h6></div>
  <div class="col-md-2"><img src="/images/di.jpg" alt="" class="img-responsive img-circle" style="max-width: 120px;"><h6><?php echo JText::_('COM_USERS_TISHCHENKO') ?></h6></div>
  <div class="col-md-2"><img src="/images/marketing.jpg" alt="" class="img-responsive img-circle" style="max-width: 120px;"><h6><?php echo JText::_('COM_USERS_MARKETING') ?></h6></div>
  <div class="col-md-2"><img src="/images/content.jpg" alt="" class="img-responsive img-circle" style="max-width: 120px;"><h6><?php echo JText::_('COM_USERS_CONTENT') ?></h6></div>
  <div class="col-md-2"></div>
</div>
			<h2 class="text-center"><?php echo JText::_('COM_USERS_0800') ?></h2>
		</div>
	</div>
</div>
<div id="beginners" class="section post">
	<div class="bg-over">	
		<div class="promo-content">
			<h2><?php echo JText::_('COM_USERS_SIGIN_FOR_BEGINNERS_TITLE') ?></h2>
			<span class="text-center">
			<img src="/images/BEGINNERS.png" alt="" class="img-responsive img-circle img-label hidden-xs" style="max-width: 20em;">
			<h4 class=""><span><?php echo JText::_('COM_USERS_SIGIN_FOR_BEGINNERS') ?></span></h4>
			</span>
		</div>
	</div>
</div>
<div id="experience" class="section post">
	<div class="slide">	
			<h2><?php echo JText::_('COM_USERS_SIGIN_FOR_EXPERIENCE_TITLE') ?></h2>
			<span class="text-center">
			<img src="/images/leg.jpg" alt="" class="img-responsive img-circle img-label hidden-xs" style="max-width: 20em;">
			<h4 class=""><?php echo JText::_('COM_USERS_SIGIN_FOR_EXPERIENCE') ?></h4>
			</span>
	</div>
	<div class="slide">
			<h2><?php echo JText::_('COM_USERS_SIGIN_OPENSEURCE_TITLE') ?></h2>
			<img src="/images/joomla.jpg" alt="" class="img-responsive img-circle" style="max-width: 20em;">			
			<h4><?php echo JText::_('COM_USERS_SIGIN_OPENSEURCE') ?></h4>
	</div>
	<div class="slide">
			<h2><?php echo JText::_('COM_USERS_SIGIN_DEVELOPMENT_TITLE') ?></h2>
			<h4><?php echo JText::_('COM_USERS_SIGIN_DEVELOPMENT') ?></h4>
	</div>
	<div class="slide">
			<h2><?php echo JText::_('COM_USERS_SIGIN_DEVELOPMENT_WORK_TITLE') ?></h2>
			<h3 class="text-center"><?php echo JText::_('COM_USERS_SIGIN_DEVELOPMENT_WORK_TITLE1') ?></h3>
			<h4><?php echo JText::_('COM_USERS_SIGIN_DEVELOPMENT_WORK') ?></h4>
	</div>
</div>
<div id="look" class="section post">
	<div class="bg-over">	
		<div class="promo-content">
			<h2><?php echo JText::_('COM_USERS_SIGIN_LOOK_TITLE') ?></h2>
			<span class="text-center">
			<img src="/images/look.png" alt="" class="img-responsive img-circle img-label hidden-xs" style="max-width: 20em;">
			<h4><?php echo JText::_('COM_USERS_SIGIN_LOOK') ?></h4>
			</span>
		</div>
	</div>
</div>
<div id="mission" class="section post">
	<div class="bg-over">	
		<div class="promo-content">		
			<h2><?php echo JText::_('COM_USERS_SIGIN_MISSION_TITLE') ?></h2>
			<span class="text-center">
			<img src="/images/mission.jpg" alt="" class="img-responsive img-circle img-label hidden-xs" style="max-width: 20em;">
			<h4 class="text-left"><?php echo JText::_('COM_USERS_SIGIN_MISSION') ?></h4>
			</span>
		</div>
	</div>
</div>
<div id="psiholo" class="section post">
	<div class="bg-over">	
		<div class="promo-content">
			<h2><?php echo JText::_('COM_USERS_SIGIN_PSIHO_TITLE') ?></h2>
			<span class="text-center">
			<img src="/images/psiholo.jpg" alt="" class="img-responsive img-circle  img-label hidden-xs" style="max-width: 20em;">		
			<h4><?php echo JText::_('COM_USERS_SIGIN_PSIHO') ?></h4>
			</span>
		</div>
	</div>
</div>
<div id="focus" class="section post">
	<div class="bg-over">	
		<div class="promo-content">
			<h2><?php echo JText::_('COM_USERS_SIGIN_FOCUS_TITLE') ?></h2>
			<span class="text-left">
			<img src="/images/focus.jpg" alt="" class="img-responsive img-circle img-label hidden-xs" style="max-width: 20em;">
			<h4><?php echo JText::_('COM_USERS_SIGIN_FOCUS') ?></h4>
			</span>
		</div>
	</div>
</div>
<div id="corp" class="section post">
	<div class="slide">	
			<h2><?php echo JText::_('COM_USERS_SIGIN_CORP_TITLE') ?></h2>
			<span class="text-center">
			<img src="/images/corp.jpg" alt="" class="img-responsive img-circle img-label hidden-xs" style="max-width: 20em;">
			<h4 class=""><?php echo JText::_('COM_USERS_SIGIN_CORP') ?></h4>
			</span>
	</div>
	<div class="slide">	
			<h2><?php echo JText::_('COM_USERS_SIGIN_CORP_TITLE1') ?></h2>
			<span class="text-center">
			<img src="/images/csr.jpg" alt="" class="img-responsive img-circle img-label hidden-xs" style="max-width: 20em;">
			<h4 class=""><?php echo JText::_('COM_USERS_SIGIN_CORP1') ?></h4>
			</span>
	</div>
</div>
</div>
<?php //Подключаем плагин прелодер ?>
<!-- <script type="text/javascript" src="http://smartresponsor.com/js/queryloader2.min.js"></script> -->
<script type="text/javascript">
		//jQuery("body").queryLoader2();	
            jQuery('#page').fullpage({
                //sectionsColor: ['', '', '', '', '', '', '', '', '', '', '', ''],
                //(ИДшники пунктов меню) anchors: ['firstPage', 'secondPage', '3rdPage', '4thpage', 'lastPage'],
                //menu: '#menu',
                //css3: true,
				slidesNavigation: true,
				slidesNavPosition: 'buttom',
                scrollingSpeed: 1000,
				navigation: true,
				scrollOverflow: true,
				afterLoad: function(link,index) {
					//if(index == 1){
					//$("#guest img").delay(2000).animate({'left':'0%'},2000);
					//}
					if(index == 1){
					jQuery("#guest h4").delay(2000).animate({'display':'block'},2000);
					}
				}
				
            });  
</script>