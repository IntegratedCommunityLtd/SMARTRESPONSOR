<?php
defined('_JEXEC') or die('Restricted access');
?>
<div class="<?php echo $params->get('moduleclass_sfx'); ?>">
<?php
// Only display login form here.
if( $my->id == 0 )
{
    if( $config->get('fbconnectkey') && $config->get('fbconnectsecret') )
    {
?>
    <div id="fb-root"></div>
    <script type="text/javascript">
    var count   = 1;
    window.fbAsyncInit = function() {
        FB.init({appId: '<?php echo $config->get('fbconnectkey');?>', status: true, oauth:true, cookie: true, xfbml: true});

        /* All the events registered */
        FB.Event.subscribe('auth.login', function(response) {
            //if( count == 1 )
                //joms.api.fbcUpdate();

            //count++;
        });
    };
	
	
jQuery(function($){  
	$('.fbconnect').click(function(){
        var e = document.createElement('script');
        e.type = 'text/javascript';
        e.src = document.location.protocol +
            '//connect.facebook.net/en_US/all.js';
        e.async = true;
        document.getElementById('fb-root').appendChild(e);
    });
});
    </script>
    <fb:login-button autologoutlink="false" class="btn btn-link fbconnect" style="color:#fff; text-decoration:none;" onlogin="joms.api.fbcUpdate();" scope="read_stream,publish_actions,email,user_birthday,user_status"><h4><?php echo JText::_('COM_COMMUNITY_SIGN_IN_WITH_FACEBOOK');?></h4></fb:login-button>
<?php
    }
    else
    {
?>
    <div>
        <?php echo JText::_('Facebook API keys need to be provided to have Facebook connect work'); ?>
    </div>
<?php
    }
}
else
{
    if( $fbUser )
    {
?>
    <div id="fb-root"></div>
    <script type="text/javascript">
    var count   = 1;
    window.fbAsyncInit = function() {
        FB.init({appId: '<?php echo $config->get('fbconnectkey');?>', status: true, oauth:true, cookie: true, xfbml: true});
             FB.Event.subscribe('auth.logout', function(response) {
                document.location.href='<?php echo CRoute::_('index.php?option=com_community&view=connect&task=logout' , false );?>';
             });
    };
    (function() {
        var e = document.createElement('script');
        e.type = 'text/javascript';
        e.src = document.location.protocol +
            '//connect.facebook.net/en_US/all.js';
        e.async = true;
        document.getElementById('fb-root').appendChild(e);
    }());
    </script>
    <fb:login-button autologoutlink="true" size="small" background="light">///<?php echo JText::_('Logout');?></fb:login-button>
<?php
    }
}
?>
</div>
