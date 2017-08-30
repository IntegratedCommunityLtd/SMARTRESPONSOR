<?php
defined('_JEXEC') or die;
//JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
//$this->addScript('/js/jquery.fullPage.js');

?>
<script type="text/javascript" src="/js/jquery.fullPage.js"></script>
<style>
 #navbar{display:none!Important;}
 #smarttop, #b1, #b2, #b3, #b4 {padding-top: 0px!important;margin-top: 0px!important;}
 .btn-xs {border-radius: 2px;}
</style>
<script>
//			jQuery('#b0').css('display', 'none!important');	
			jQuery('#fp-nav').addClass(' hidden-xs');	
//			jQuery('#navbar').css('display', 'none!important');	
//			jQuery('#b1').css('display', 'none!important');	
//			jQuery('#b2').css('display', 'none!important');	
//			jQuery('#b0').css('height', '0!important');	
//			jQuery('#b1').css('width', '0px!important');	
//			jQuery('#b2').css('width', '0px!important');
			jQuery('.active').css('border', 'none!important');	
			jQuery('#b3').css('width', 'inherit');	
			jQuery('#b3').css('padding', '0');				
			jQuery('#nent').css('max-width', '100%!important');	
			jQuery('#nent').css('width', '100%');	
//			jQuery('#nent').css('margin-top', '0');	
//			jQuery('#smarttop').css('margin-top', '0');	
//			jQuery('#page').css('margin-top', '45px');	
</script>
<link rel="stylesheet" type="text/css" defer href="/css/jquery.fullPage.css" />
<link rel="stylesheet" type="text/css" defer href="/css/checkbox-style.css" />
<link rel="stylesheet" type="text/css" href="/css/roboto.css">
<link rel="stylesheet" type="text/css" href="/css/sigin.css" />
<div id="page" class="page">
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

<div id="guest" class="section">
		<div class="clear"></div>
			<span class="text-center">
				<?php echo JText::_('COM_USERS_SIGIN_CONNECT') ?>
				<?php echo JText::_('COM_USERS_SIGIN_ITSEASY') ?>
				<?php echo JText::_('COM_USERS_SIGIN_WELLCOME_NET') ?>
			</span>
<div class="row">
<div class="col-md-2"></div>
<div class="col-md-8 text-center">
	<form role="form" id="member-login" action="<?php echo JRoute::_('index.php?option=com_ajax&option=com_users&task=user.login'); ?>" method="post" id="member-login" class="form-validate">
		<div class="btn-group-vertical">
			<div class="form-group member-login">
				<button id="btnformreg" type="button" class="glyphicon glyphicon-user registration"></button>
				
				<span class="text-center">
							<h3><?php echo JText::_('COM_USERS_SIGIN_LOGIN') ?></h3>
					<?php // устаревший код авторизации через ФБ
					// Возврат модуля с Регистраций через ФБ
					//	$modules =JModuleHelper::getModules('registration-fb-login-page');
					//		foreach ($modules as $module){
					//			echo JModuleHelper::renderModule($module);
					//		}
					?>
				</span>
				</br>
				<fieldset>
				
					<?php foreach ($this->form->getFieldset('credentials') as $field) : ?>
						<?php if (!$field->hidden) : ?>
									<?php echo ($field->input) ? $field->input : "&#160;"; ?>

					<?php endif; ?>					
					<?php endforeach; ?>
				</fieldset>	
					<input type="checkbox" class="checkbox" id="checkbox" name="remember" value="yes" />
					<label for="checkbox" class="remember"><h4><?php echo JText::_('COM_USERS_REMEMBER_ME') ?></h4></label><br>
						<div>
						<br>
							<a href="<?php echo JRoute::_('index.php?option=com_ajax&option=com_users&view=remind&Itemid=' . UsersHelperRoute::getRemindRoute()); ?>" class="btn btn-link reset"><?php echo JText::_('COM_USERS_LOGIN_REMIND'); ?></a>
							<span>&nbsp </span>
							<a href="<?php echo JRoute::_('index.php?option=com_ajax&option=com_users&view=reset&Itemid=' . UsersHelperRoute::getResetRoute()); ?>" class="btn btn-link reset"><?php echo JText::_('COM_USERS_LOGIN_RESET'); ?></a>
							<span>&nbsp </span>
							<button id="btnformregOne" type="button" class="btn btn-warning btn-xs"><?php echo JText::_('COM_USERS_REGISTER_DEFAULT_LABEL'); ?></button>
						</div>
				<input type="hidden" name="return" value="<?php echo base64_encode($this->params->get('login_redirect_url', $this->form->getValue('return'))); ?>" />
				<input type="hidden" name="option" value="com_users" />
				<input type="hidden" name="task" value="user.login" />
				<input type="hidden" name="return" value="<?php echo $return; ?>" />
				<?php echo JHtml::_('form.token'); ?>
			</div>
				<button type="submit" tabindex="0" name="Submit" class="btn btn-warning btn-lg submit-login"><?php echo JText::_('COM_USERS_SIGIN_AUTHORIZATION') ?></button>
		</div>
	</form>

					<?php
					// Возврат модуля Регистрации
					$document	= &JFactory::getDocument();
					$renderer	= $document->loadRenderer('modules');
					$position	= 'registration-login-page';
					echo $renderer->render($position, $options, null);					
//					$modules =JModuleHelper::getModules('registration-login-page');
//							foreach ($modules as $module){
//								echo JModuleHelper::renderModule($module);
//							}
					?>
</div>					
<div class="col-md-2"></div>
</div>
				<h3 class="hidden-xs hidden-phone"><?php echo JText::_('COM_USERS_SIGIN_CONGRATS') ?></h4>
				<h4 class="hidden-xs hidden-phone"><?php echo JText::_('COM_USERS_IS') ?></h4>
				<h5 class="hidden-xs hidden-phone separatortop separatorbutton"><?php //echo JText::_('COM_USERS_ISIS') ?></h5>			
</div>	


<div id="visits" class="section hidden-xs post">
	<div class="bg-over">
		<div class="promo-content">
					<h2><?php echo JText::_('COM_USERS_SIGIN_EVERYDAY') ?></h2>
					
					<h3 class="text-center hidden-xs hidden-phone"><?php echo JText::_('COM_USERS_SIGIN_ADD') ?></h3>
					
	<br><br>				
					
	<style>.cubes .col-lg-2 {width: auto;margin: 2px;padding: 2px;}</style>
	<div class="row cubes hidden-xs hidden-phone">
	<div class="col-md-2 col-lg-2 col-xs-2"><img src="/images/undp/sdg-uk-01.png"></div>
	<div class="col-md-2 col-lg-2 col-xs-2"><img src="/images/undp/sdg-uk-02.png"></div>
	<div class="col-md-2 col-lg-2 col-xs-2"><img src="/images/undp/sdg-uk-03.png"></div>
	<div class="col-md-2 col-lg-2 col-xs-2"><img src="/images/undp/sdg-uk-04.png"></div>
	<div class="col-md-2 col-lg-2 col-xs-2"><img src="/images/undp/sdg-uk-05.png"></div>
	<div class="col-md-2 col-lg-2 col-xs-2"><img src="/images/undp/sdg-uk-06.png"></div>
	<div class="col-md-2 col-lg-2 col-xs-2"><img src="/images/undp/sdg-uk-07.png"></div>
	<div class="col-md-2 col-lg-2 col-xs-2"><img src="/images/undp/sdg-uk-08.png"></div>
	<div class="col-md-2 col-lg-2 col-xs-2"><img src="/images/undp/sdg-uk-09.png"></div>
	<div class="col-md-2 col-lg-2 col-xs-2"><img src="/images/undp/sdg-uk-10.png"></div>
	<div class="col-md-2 col-lg-2 col-xs-2"><img src="/images/undp/sdg-uk-11.png"></div>
	<div class="col-md-2 col-lg-2 col-xs-2"><img src="/images/undp/sdg-uk-12.png"></div>
	<div class="col-md-2 col-lg-2 col-xs-2"><img src="/images/undp/sdg-uk-13.png"></div>
	<div class="col-md-2 col-lg-2 col-xs-2"><img src="/images/undp/sdg-uk-14.png"></div>
	<div class="col-md-2 col-lg-2 col-xs-2"><img src="/images/undp/sdg-uk-15.png"></div>
	<div class="col-md-2 col-lg-2 col-xs-2"><img src="/images/undp/sdg-uk-16.png"></div>
	<div class="col-md-2 col-lg-2 col-xs-2"><img src="/images/undp/sdg-uk-17.png"></div>
    </div>
					
					
					
				<div class="row like">
				<div class="hidden-xs ">
				</div>
			
				<div id="jl"></br>
					<?php
					// Возврат модуля Лайков
//						JFactory::getLanguage()->load('plg_content_jllikepro', JPATH_ADMINISTRATOR, null, false, true);
					//	include_once JPATH_ROOT .'/pluginshttp://www.ua.undp.org/content/jllikepro/helper.php';
//							$helper = PlgJLLikeHelper::getInstance();
//							$helper->loadScriptAndStyle(0); //1-если в категории, 0-если в контенте
//					echo $helper->ShowIN($id, $link, $title, $image);
					?>
				<script>//jQuery(function($){ $('#jl').popover('show')});</script>
				</div>
<!---------------------------------->			
				
				<div class="text-center hidden-xs "></br><h3 class=""><?php echo JText::_('COM_USERS_MORE_SUCCESS') ?></h3></br>
				<?php /*?>
				<h4 class="hidden-xs hidden-phone"><?php echo JText::_('COM_USERS_MORE_SUCCESS_INFO') ?></h4></br><h4 class="hidden-xs hidden-phone"><?php echo JText::_('COM_USERS_MORE_NEWS_INFO') ?></h4>
				
				<?php */?>
				</div>
				</div>
			<?php /*?>
			<div class="row hidden-xs hidden-print">
			<div class="col-md-6"><div id="fb-googl" class="pull-right"><?php //include JPATH_BASE . '/templates/html5/fb_page.php'; ?></div></div>
			<div class="col-md-6"><div class="pull-left"><?php //include JPATH_BASE . '/templates/html5/google_group.php'; ?></div></div>
			</div>

					<span class="text-center">
					<h3><?php //echo JText::_('COM_USERS_SUBSCRIBE') ?></h3></br>
					</span>
			<?php */?>
					<?php
					// Возврат модуля Подписки
//						$modules =JModuleHelper::getModules('visits-section-subscribe');
//							foreach ($modules as $module){
								//echo JModuleHelper::renderModule($module);
//							}
					?>
		</div>
	</div>
</div>



<div id="howto" class="section hidden-xs post" style="">
	<div class="bg-over">	
		<div class="promo-content">
			<h2><?php echo JText::_('COM_USERS_SIGIN_HOWTO') ?></h2>
					<span class="text-center">
					<h3><?php echo JText::_('COM_USERS_SIGIN_HOWTO_MYFA') ?></h3>
					<img src="/images/peoplegears.png" alt="" class="img-responsive img-label hidden-xs" style="max-width: 20em;">
					<h4 class=""><?php echo JText::_('COM_USERS_SIGIN_HOWTO_FIRST') ?></h4>
					</span>
				<div class="row">
				<div class="col-md-4 hidden-xs"></div>
				<div class="col-md-4"><h4 class=""><?php echo JText::_('COM_USERS_SIGIN_HOWTO_UL') ?></h4></div>
				<div class="col-md-4 hidden-xs "></div>
				</div>
					
		</div>
	</div>
</div>
<div id="everyday" class="section hidden-xs post" style="">
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
<div id="wellcome" class="section hidden-xs post">
	<div class="bg-over">	
		<div class="promo-content">
			<h2><?php echo JText::_('COM_USERS_SIGIN_WELLCOME') ?></h2>
			<span class="text-center">
			<img src="/images/hand.jpg" alt="" class="img-responsive img-circle img-label hidden-xs" style="max-width: 20em;">
			<h3><?php echo JText::_('COM_USERS_SIGIN_WELLCOME_TITLE') ?></h3>
			</span>
			<span class="text-center hidden-xs hidden-phone">
			<h3><?php echo JText::_('COM_USERS_SIGIN_WELLCOME_TEAME') ?></h3>
			</span>
<div class="row hidden-xs hidden-phone">
  <div class="col-md-2"></div>
  <div class="col-md-2"><img src="/images/pr.jpg" alt="" class="img-responsive img-circle" style="max-width: 120px;"><h6 class="text-center"><?php echo JText::_('COM_USERS_PR') ?></h6></div>
  <div class="col-md-2"><img src="/images/di.jpg" alt="" class="img-responsive img-circle" style="max-width: 120px;"><h6 class="text-center"><?php echo JText::_('COM_USERS_TISHCHENKO') ?></h6></div>
  <div class="col-md-2"><img src="/images/marketing.jpg" alt="" class="img-responsive img-circle" style="max-width: 120px;"><h6 class="text-center"><?php echo JText::_('COM_USERS_MARKETING') ?></h6></div>
  <div class="col-md-2"><img src="/images/content.jpg" alt="" class="img-responsive img-circle" style="max-width: 120px;"><h6 class="text-center"><?php echo JText::_('COM_USERS_CONTENT') ?></h6></div>
  <div class="col-md-2"></div>
</div>
			<h2 class="text-center"><?php echo JText::_('COM_USERS_0800') ?></h2>
		</div>
	</div>
</div>
<div id="beginners" class="section hidden-xs post">
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
<div id="experience" class="section hidden-xs post">
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
<div id="look" class="section hidden-xs post">
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
<div id="mission" class="section hidden-xs post">
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
<div id="psiholo" class="section hidden-xs post">
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
<div id="focus" class="section hidden-xs post">
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
<div id="corp" class="section hidden-xs post">
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
<script type="text/javascript">
var viewportHeight = document.documentElement.clientHeight;
	if (viewportHeight < 800) {
		jQuery('.hidden-phone').addClass(' displaynone');
		}
		
jQuery(document).ready(function($){

			$('#fp-nav').addClass('hidden-xs');	
            $('#page').fullpage({
                //sectionsColor: ['', '', '', '', '', '', '', '', '', '', '', ''],
                //(ИДшники пунктов меню) anchors: ['firstPage', 'secondPage', '3rdPage', '4thpage', 'lastPage'],
                //menu: '#menu',
                //css3: true,
				slidesNavigation: true,
				slidesNavPosition: 'buttom',
                scrollingSpeed: 1000,
				navigation: true,
				scrollOverflow: false,
				afterLoad: function(link,index) {
					//if(index == 1){
					//$("#guest img").delay(2000).animate({'left':'0%'},2000);
					//}
					if(index == 1){
					$("#guest h4").delay(2000).animate({'display':'block'},2000);
					}
				}
            });
// tab form login & registration
$('#btnformlogin').click(function() {
		$('#member-registration').css('display', 'none');
		$('#member-login').css('display', 'block');
});
$('#btnformloginOne').click(function() {
		$('#member-registration').css('display', 'none');
		$('#member-login').css('display', 'block');
});
$('#btnformreg').click(function() {
		$('#member-registration').css('display', 'block');
		$('#member-login').css('display', 'none');
})
$('#btnformregOne').click(function() {
		$('#member-registration').css('display', 'block');
		$('#member-login').css('display', 'none');
})

//add class .form-control to input
		$('input.inputbox').addClass('form-control');
});


</script>