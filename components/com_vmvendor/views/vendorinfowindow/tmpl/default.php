<?php
/* 
 * @component com_vmvendor
 * @copyright Copyright (C) 2010-2016 Adrien ROUSSEL Nordmograph.com
 * @license GNU/GPL Version 3
 * @Website : https://www.nordmograph.com/extensions
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
$user = JFactory::getUser();
$cparams 				= JComponentHelper::getParams('com_vmvendor');
$tipclass		= $cparams->get('tipclass','');
?>

<div id="fd" class="geombox geom geombox-profile" >
	<div class="geombox-content" data-geombox-content="">
    	
        <div class="profile-details">
			<div class="profile-title">
				<a href="<?php echo $this->profile_url ; ?>">
					<?php echo ucwords($this->naming) ; ?>
            	</a>
			</div>
			<div class="profile-desp">
				<?php 
				if( $this->daysdiff>1 && $this->lastvisitDate!='0000-00-00 00:00:00')
					echo sprintf( JText::_('COM_VMVENDOR_MAP_LASTLOGIN') , $this->daysdiff ) ; 
				elseif($this->daysdiff==1)
					echo JText::_('COM_VMVENDOR_MAP_LASTLOGINYESTERDAY' ) ;
				elseif($this->daysdiff<1)
					echo JText::_('COM_VMVENDOR_MAP_LASTLOGINTODAY' ) ;
				?>	
          	</div>
		</div>
        
		<div class="geombox-cover">
      
          <div class="geom-photo-scaled geom-photo-wrap">
        
        	</div>
		</div>

        <a class="geom-avatar geom-avatar-medium geombox-avatar" href="<?php echo $this->profile_url ; ?>">
            <img alt="<?php echo ucfirst($this->naming) ; ?>" src="<?php echo $this->avatar ; ?>" width="60" >
        </a>
        <div class="geom-online-status geom-online-status-mini">
            <i title="<?php echo JText::_( 'COM_VMVENDOR_MAP_'.strtoupper($this->onlinestatus).'LINESTATUS' )  ?>" data-placement="top" class="<?php echo $tipclass ?> geom-status-<?php echo $this->onlinestatus ?>">
            </i>
        </div>

        <div class="geombox-info">
            <ul class="list-unstyled geombox-items">
                 <li>
					<div class="geombox-item-info">
                            <div class="geombox-item-text">
                                <?php echo JText::_('COM_VMVENDOR_MAP_PRODUCTSCOUNT') ?>
                            </div>
                            <div class="geombox-item-total">
                               <?php echo '<a href="'.$this->profile_url.'">'.$this->products.'</a>' ?>
                           	</div>
					</div>
				</li>
                <li>
					<div class="geombox-item-info">
                            <div class="geombox-item-text">
                                <?php echo JText::_('COM_VMVENDOR_MAP_AVERAGERATING') ?>
                            </div>
                            <div class="geombox-item-total" >
                                <?php echo $this->rating; ?> /5 		
                           	</div>
					</div>
                    </li>
                <li>
                    <div class="geombox-item-info">
                            <div class="geombox-item-text">
                                <?php echo JText::_('COM_VMVENDOR_MAP_REVIEWSCOUNT') ?>
                            </div>
                            <div class="geombox-item-total" >
                                <?php echo $this->reviews; ?>
                           	</div>
					</div>
				</li>
                 
			</ul>
		</div>
        <div class="geombox-footer">
        <?php if($this->userid != $user->id){ ?>
        	<div class="">
	
                <div class="btn-group btn-group-route">
					<a class="btn-geom btn-message" href="javascript:void(0);"  
                    onclick="javascript:calcRoute(<?php echo $this->latitude ?>,<?php echo $this->longitude ?> );">
                          <?php echo JText::_('COM_VMVENDOR_MAP_ROUTE') ?>
                   	</a>
				</div>
			</div>
                  <?php } ?>
		</div>
  
	</div>
</div>
<style>
div#fd.geom.geombox.geombox-profile {
	width: 310px ;
	height: 145px ;
	background-color: #fff ;
	padding: 0;
	overflow:hidden;
	position: relative;
}
.table-customfields{max-width:310px}

div#fd.geom.geombox.geombox-profile .geombox-cover {
	display: block;
	width: 100%;
	height: 70px;
	background: #333;
	position: absolute;
}
div#fd.geom.geombox.geombox-profile .geombox-cover>div {
	width: 100%;
	height: 100%;
}

div#fd.geom.geombox.geombox-profile .geombox-avatar {
	position: absolute;
	z-index: 2;
	left: 10px;
margin-top:15px;
	border-radius: 2px;
	overflow: visible;
	max-width: 64px;
	border: 2px solid #fff;
	background-color:#fff;
}
body div#fd.geom.geombox.geombox-profile .profile-details .profile-title>a {
	color: white ;
	font-weight:bold;
}

div#fd.geom.geombox.geombox-profile .profile-details {
	position: absolute;
	z-index: 2;
	bottom: 75px;
	left: 0;
	color: white;
	padding: 0 10px 0 86px;
	width: 310px;
	height: 61px;
	background-image: -webkit-linear-gradient(rgba(255,255,255,0),rgba(0,0,0,0.1) 30%,rgba(0,0,0,0.5));
	background-image: linear-gradient(rgba(255,255,255,0),rgba(0,0,0,0.1) 30%,rgba(0,0,0,0.5));
	background-repeat: no-repeat;
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#00ffffff',endColorstr='#7f000000',GradientType=0);
}

div#fd.geom.geombox.geombox-profile .geombox-footer {
	background: #f2f2f2;
	position: absolute;
	width: 310px;
	bottom: 0;
	padding: 7px 5px;
	z-index:11;
}

div#fd.geom.geombox.geombox-profile .btn-geom {
	display: inline-block;
	outline: 0;
	padding: 4px 12px;
	margin-bottom: 0;
	font-size: 11px;
	line-height: 16px;
	text-align: center;
	vertical-align: middle;
	cursor: pointer;
	border: 1px solid #d7d7d7;
	border-bottom-color: #bebebe;
	border-radius: 3px;
	background-color: #f7f7f7;
}
div#fd.geom.geombox.geombox-profile .btn-geom:hover {
	color: #333;
	text-decoration: none;
	background-position: 0 -15px;
	background-color: #e6e6e6;
	-webkit-transition: background-position .1s linear;
	transition: background-position .1s linear;
}

div#fd.geom.geombox.geombox-profile .geombox-info {
	position: absolute;
	width: 310px;
	padding-left: 96px;
	top: 70px;
}
div#fd.geom.geombox.geombox-profile .geombox-items>li {
	width: 33%;
	display: inline-block ;
	text-align: center ;
	margin: 0 -3px 0 0 ;
}
div#fd.geom.geombox.geombox-profile .geombox-item-info {
	border-left: 1px solid #f2f2f2 ;
	padding: 2px 2px 4px ;
	font-size: 11px ;
	line-height: 14px ;
	width: 100% ;
	height: 36px ;
	overflow: hidden ;
}



body div#fd.geom.geombox.geombox-profile .geom-online-status {
	position: absolute;
	bottom: 133px;
	left: 78px;
	right: auto;
	top: auto;
}
body div#fd.geom .geom-online-status {
	display: inline-block;
	position: absolute;
	z-index: 10;
	top: -3px;
	right: -3px;
	font-size: 0;
}
status-mini .geom-status-off {
	width: 8px;
	height: 8px;
	border: 1px solid #fff;
}
body div#fd.geom .geom-online-status .geom-status-off {
	background-color: #f38282;
}
body div#fd.geom .geom-online-status .geom-status-on {
background-color: #91c73c;
}
body div#fd.geom .geom-online-status .geom-status-on, body div#fd.geom .geom-online-status .geom-status-off {
	position: absolute;
	top: 0;
	right: 0;
	width: 8px;
	height: 8px;
	display: inline-block;
	border: 1px solid #fff;
	border-radius: 50%;
}
.btn-group {
	position: relative;
	display: inline-block;
	font-size: 0;
	vertical-align: middle;
	white-space: nowrap;
}
@media screen and (max-width: 600px) {
	.profile-desp,.geombox-footer	{display:none;}
	div#fd.geom.geombox.geombox-profile {
		width: 210px ;
		height: 75px ;
		background-color: #fff ;
		padding: 0;
		overflow:hidden;
	}
	
	
	div#fd.geom.geombox.geombox-profile .geombox-cover {
		display: block;
		width: 100%;
		height: 115px;
		background: #333;
		position: absolute;
	}
	div#fd.geom.geombox.geombox-profile .geombox-cover>div {
		width: 100%;
		height: 100%;
	}
	
	div#fd.geom.geombox.geombox-profile .geombox-avatar {
		position: absolute;
		z-index: 2;
		left: 3px;
		border-radius: 2px;
		overflow: visible;
		max-width: 60px;
		border: 2px solid #fff;
		background-color:#fff;
		top:-7px;
	}
	body div#fd.geom.geombox.geombox-profile .profile-details .profile-title>a {
		color: white ;
		font-weight:bold;
	}
	
	div#fd.geom.geombox.geombox-profile .profile-details {
		position: absolute;
		z-index: 2;
		top: -40px;
		left: 0;
		color: white;
		padding: 50px 14px 10px 73px;
		height: 61px;
		background-image: -webkit-linear-gradient(rgba(255,255,255,0),rgba(0,0,0,0.1) 30%,rgba(0,0,0,0.5));
		background-image: linear-gradient(rgba(255,255,255,0),rgba(0,0,0,0.1) 30%,rgba(0,0,0,0.5));
		background-repeat: no-repeat;
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#00ffffff',endColorstr='#7f000000',GradientType=0);
	}
	
	div#fd.geom.geombox.geombox-profile .geombox-footer {
		background: transparent;
		position: absolute;
		width: 200px;
		top: 85px;
		padding: 0 3px ;
		z-index: 11;
	}
	
	div#fd.geom.geombox.geombox-profile .btn-geom {
		display: inline-block;
		outline: 0;
		padding: 4px 12px;
		margin-bottom: 0;
		font-size: 11px;
		line-height: 16px;
		text-align: center;
		vertical-align: middle;
		cursor: pointer;
		border: 1px solid #d7d7d7;
		border-bottom-color: #bebebe;
		border-radius: 3px;
		background-color: #f7f7f7;
	}
	div#fd.geom.geombox.geombox-profile .btn-geom:hover {
		color: #333;
		text-decoration: none;
		background-position: 0 -15px;
		background-color: #e6e6e6;
		-webkit-transition: background-position .1s linear;
		transition: background-position .1s linear;
	}
	
	div#fd.geom.geombox.geombox-profile .geombox-info {
		display:none;
	}
	div#fd.geom.geombox.geombox-profile .geombox-items>li {
		width: 24%;
		display: inline-block ;
		text-align: center ;
		margin: 0 -3px 0 0 ;
	}
	div#fd.geom.geombox.geombox-profile .geombox-item-info {
		border-left: 1px solid #f2f2f2 ;
		padding: 2px 2px 4px ;
		font-size: 11px ;
		line-height: 14px ;
		width: 100% ;
		height: 36px ;
		overflow: hidden ;
	}
	
	
	
	body div#fd.geom.geombox.geombox-profile .geom-online-status {
		position: absolute;
		left: 71px;
		right: auto;
		top:3px;
	}
	body div#fd.geom .geom-online-status {
		display: inline-block;
		position: absolute;
		z-index: 10;
		
		right: -3px;
		font-size: 0;
	}
	status-mini .geom-status-off {
		width: 8px;
		height: 8px;
		border: 1px solid #fff;
	}
	body div#fd.geom .geom-online-status .geom-status-off {
		background-color: #f38282;
	}
	body div#fd.geom .geom-online-status .geom-status-on {
	background-color: #91c73c;
	}
	body div#fd.geom .geom-online-status .geom-status-on, body div#fd.geom .geom-online-status .geom-status-off {
		position: absolute;
		top: 0;
		right: 0;
		width: 8px;
		height: 8px;
		display: inline-block;
		border: 1px solid #fff;
		border-radius: 50%;
	}
	.btn-group {
		position: relative;
		display: inline-block;
		font-size: 0;
		vertical-align: middle;
		white-space: nowrap;
	}
	.btn-group-route{
		display:none;
	}
	.pull-right{
		float:left;
	}
}
</style>