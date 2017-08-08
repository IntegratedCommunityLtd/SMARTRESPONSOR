<?php
defined('_JEXEC') or die('Restricted access'); // no direct access
$document=JFactory::getDocument();
$jss=JURI::base().'modules/mod_pro_complete/assets/js/overlib_all_mini.js';
$document->addScript($jss);
global $Itemid;
$db=JFactory::getDBO();
$my=JFactory::getUser();

//Get Params
$param_fields=$params->get('js_fields');
$count_field=count($param_fields);
$usersname=$params->get('name',1);
$bar=$params->get('bars','both');
$image=$params->get( 'image', 1 ) ;
$img_path=JURI::base()."images/avatar";
$show_tooltipfield=$params->get('tooltipfield',1);
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
// Login if registered user


if(!$my->id) {	
	return;
}

$class_well='';
if($image==1)
{
	$class_well='';
}
$cnt=count($param_fields);
$tooltipfield=$pobj->tooltipfield;
$tootip='';

$arrs=implode(' <br/> ',$pobj->eval);
$tootip=implode(' &#10; ',$pobj->eval);

if($tooltipfield==1 && $tlp_whn_pro_complete==1)
{
	if(JVERSION>=3.0)
	{	// start tooltip div
		if($show_tooltipfield){
			echo '<div data-toggle="tooltip" title="'.JText::_('MOD_PRO_COMPLETE_TOOLTIP') .''.$tootip.'" >';
		}
	}
	else if($show_tooltipfield){
		echo '<div onmouseout="return nd();" onmouseover="return overlib(\''.$arrs.'\', CAPTION, \'&lt;label id=&quot;paramshelpsite-lbl&quot; for=&quot;paramshelpsite&quot; class=&quot;hasTip&quot; title=&quot;'.JText::_('MOD_PRO_COMPLETE').' :::&quot;&gt;'.JText::_('MOD_PRO_COMPLETE_FIELD_MESG').'&lt;/label&gt;\');">';
	}
}
else if($tlp_whn_pro_complete==0)
{
	if($show_tooltipfield){
		echo '<div data-toggle="tooltip" title="'.JText::_('MOD_PRO_COMPLETE_MSG_PROFILE_COMPLETE').'" >';
	}
	if(JVERSION>=3.0 AND $show_tooltipfield){
		echo '<div style="" class="'.$class_well.'" id="container11" title="'.JText::_('MOD_PRO_COMPLETE').'" data-content="'.JText::_('MOD_PRO_COMPLETE_MSG_PROFILE_COMPLETE').''.$arrs.'" data-placement="top" data-toggle="popover" data-original-title="">';
	}
	else
		echo '<div style=" " class="'.$class_well.' ">';
}

if($tlp_whn_pro_complete==1)
{
	if(JVERSION>=3.0 AND $show_tooltipfield){
		echo '<div style=" " class="'.$class_well.' " id="container11" title="'.JText::_('MOD_PRO_COMPLETE').'" data-content="'.JText::_('MOD_PRO_COMPLETE_FIELD_MESG').''.$arrs.'" data-placement="top" data-toggle="popover" data-original-title="">';
	}
}
foreach($user_info as $user)
{
	//$link = JRoute::_('index.php?option=com_community&view=profile&Itemid=' . $_Itemid);
	//$link1 = JRoute::_('index.php?option=com_community&view=profile&task=edit&Itemid=' . $_Itemid);
	$link = JRoute::_('editprofile');
	$link1 = JRoute::_('editprofile');

		if($usersname || $image)
		{
				if(!$js_user_image)
				{
					$js_user_image = JURI::base()."/images/default_thumb.jpg";
				}
				if($image == 1)
				{ ?>
						<li class="navbar-nav navbar-left hidden-xs"><a href="#<?php
				//echo $link1; ?>" class="navbar-avatar">
							<img src="<?php echo $js_user_image; ?>" class="navbar-avatar">
						</a></li>							
				<?php	}
				if($usersname == 1)
				{
					?>
					<?/* <div>
						<a href="<?php echo $link; ?>"><?php echo $my->name; ?></a><br />
					</div> */?>
					<?php
				} ?>
			<?php
		}
		?>


												<?php //if($bar == 'both'){ ?>
												<?//	<b style="color:#000000;">?><?// <?php echo $pobj->perc;?><?// %</b> ?>
												<?php //}?>


	<?php
}
if($tlp_whn_pro_complete==1)
{
	if(JVERSION>=3.0 AND $show_tooltipfield)
		echo '</div>';
}

if($tooltipfield==1 && $tlp_whn_pro_complete==1)
{
	if($show_tooltipfield)
		echo '</div>';
}else if($tlp_whn_pro_complete==0)
{
	if($show_tooltipfield)
		echo '</div>';
	if(JVERSION>=3.0 AND $show_tooltipfield)
		echo '</div>';
	else
		echo '</div>';
}
	if(JVERSION<3.0): ?>
	<div id='pro-comp-container'>
		<?php
	endif;?>
				<?php
				if( $bar == 'both' || $bar == 'bar' )
				{
					if($tooltipfield==1 && $tlp_whn_pro_complete==1)
					{
						//tooltip div
						if(JVERSION>=3.0 AND $show_tooltipfield)
						{	// start tooltip div
							echo '<div data-toggle="tooltip" title="'.JText::_('MOD_PRO_COMPLETE_TOOLTIP') .''.$tootip.'" >';
						}
						else if($show_tooltipfield)
							echo '<div onmouseout="return nd();" onmouseover="return overlib(\''.$arrs.'\', CAPTION, \'&lt;label id=&quot;paramshelpsite-lbl&quot; for=&quot;paramshelpsite&quot; class=&quot;hasTip&quot; title=&quot;'.JText::_('MOD_PRO_COMPLETE').' :::&quot;&gt;'.JText::_('MOD_PRO_COMPLETE_FIELD_MESG').'&lt;/label&gt;\');">';
					}
							if(JVERSION<3.0): ?>
										<?/* <div id="bar" class="progress myprofilepogress" style="width:<?php echo $pobj->perc.'%'; ?>" >
											<?php if($bar == 'both') echo $pobj->perc.'%'; else echo ''; ?>
										</div> */?>
								<?php
							else : ?>
									<?/* <div>
										<div class="progress compaign">
											<div class="progress-bar pbcompaign" style="width: <?php echo $pobj->perc;?>%;">
												<?php if($bar == 'both'){ ?>
													<b style="color:#000000;"><?php echo $pobj->perc;?>%</b>
												<?php }?>
											</div>
										</div>
									</div> */?>
								<?php
							 endif;	?>
						<?php
					if($tooltipfield==1 && $tlp_whn_pro_complete==1)
						if($show_tooltipfield)
							echo"</div>";//end tooltip div
				}
		if( $bar == 'numeric' )
		{
			//if(JVERSION<3.0):
				echo '<div class="alert alert-info">';
					echo JText::_( 'MOD_PRO_COMPLETE_PRO_PROFILE' ) ." ".$pobj->perc . "% ". JText::_( 'MOD_PRO_COMPLETE_PRO_PROFILES' );
						echo "<br/>";
				echo '</div>';
			//endif;
		}
		if(JVERSION>=3.0):
			?>
			<?php
			/*
			<li class="navbar-nav navbar-left hidden-xs"><a href='<?php
				$msg=JText::_('MOD_PRO_COMPLETE_EDIT');
				echo $link1; ?>'>
				<div style="margin-left:25%;margin-top:1%;">
					<input id="LOGIN" class="btn btn-primary" type="button" value="<?php echo JText::_('MOD_PRO_COMPLETE_EDIT'); ?>">
				</div>
			</a></li>
			*/
			?>
			<?php
		else :?>
			<?/* <a href="<?php echo $link1; ?>"><?php echo JText::_('MOD_PRO_COMPLETE_EDIT'); ?></a> */?>
			<?php
		endif;
		 ?>

	<?php
	if(JVERSION<3.0)
		echo '</div>';//pro-comp-container
?>
<script>
jQuery("#container11").popover({ });
</script>