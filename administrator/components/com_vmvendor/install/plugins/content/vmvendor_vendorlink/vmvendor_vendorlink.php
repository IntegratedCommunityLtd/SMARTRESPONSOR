<?php
/**
 * @package VMVendor Vendor Link
 * @author   Nordmograph
 * @link https://www.nordmograph.com/extensions
 * @license GNU General Public License version 3 or later; see LICENSE.txt
 * @copyright Copyright (C) 2016 nordmograph.com. All rights reserved.
*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
class plgContentVmvendor_Vendorlink extends JPlugin
{
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}
	function getAddproductItemid()
	{
		$lang = JFactory::getLanguage();
		$db 	= JFactory::getDBO();
		$q = "SELECT id FROM #__menu WHERE link='index.php?option=com_vmvendor&view=addproduct' 
		AND type='component'  AND ( language ='".$lang->getTag()."' OR language='*') 
		AND published='1' ";
		$db->setQuery($q);
		return $addproduct_itemid = $db->loadResult();
	}
	
	public function onContentPrepare($context, $row, $params, $page = 0)
	{	
		if($context == 'com_virtuemart.productdetails')	
		{	
			JHtml::_('bootstrap.tooltip');
			$user 				= JFactory::getUser();
			$juri 				= JURI::base();
			$app 				= JFactory::getApplication();
			$db 				= JFactory::getDBO();
			$doc				= JFactory::getDocument();
			$doc->addStyleSheet($juri.'plugins/content/vmvendor_vendorlink/css/style.css');	
			echo '<link rel="stylesheet" href="'.$juri.'components/com_vmvendor/assets/css/fontello.css">';
			$cparams 					= JComponentHelper::getParams('com_vmvendor');
			$profileman 				= $cparams->get('profileman', '0');
			$profileitemid 				= $cparams->get('profileitemid');
			$naming		 				= $cparams->get('naming', 'username');	
			$rating_stars				= $cparams->get('rating_stars', '5');
			$modaltype					= $cparams->get('modaltype','s');
			$tipclass					= $cparams->get('tipclass','');
			$forbidcatids				= $cparams->get('forbidcatids');
			$onlycatids					= $cparams->get('onlycatids');
			$show_hits					= $cparams->get('show_hits',0);
			
			$banned_cats = explode(',',$forbidcatids);
			$prefered_cats = explode(',',$onlycatids);
			if (!class_exists( 'VmConfig' ))
				require JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php';
			VmConfig::loadConfig();

			$virtuemart_category_id =  $row->virtuemart_category_id;
			
			if($onlycatids!='' && ( !in_array($virtuemart_category_id, $prefered_cats) &&  $virtuemart_category_id!= $onlycatids))
				return false;
				
			if($forbidcatids!='' && ( in_array($virtuemart_category_id, $banned_cats)  OR  $virtuemart_category_id ==  $forbidcatids  ) )
				return false;

			
			
			
			$linkto		 				= $this->params->get('linkto',1);
			$questionform		 		= $this->params->get('questionform',1);
			$show_deletebutton			= $this->params->get('show_deletebutton',1);
			$show_rating				= $this->params->get('show_rating',1);
			$show_vendorthumb			= $this->params->get('show_vendorthumb',1);
			$virtuemart_product_id 		= $app->input->get('virtuemart_product_id');			
			$html ='';
			$q = "SELECT  u.id , vvl.vendor_store_name 
			FROM #__users u 
			LEFT JOIN #__virtuemart_vmusers vv ON vv.virtuemart_user_id = u.id 
			LEFT JOIN #__virtuemart_products vp ON vp.virtuemart_vendor_id = vv.virtuemart_vendor_id 
			LEFT JOIN #__virtuemart_vendors_".VMLANG." vvl ON vvl.virtuemart_vendor_id = vp.virtuemart_vendor_id
			WHERE vv.user_is_vendor='1'  
			AND vp.virtuemart_product_id = '".$virtuemart_product_id."'";
			$db->setQuery($q);
			$resultrow = $db->loadRow();
			$vendor_uid = $resultrow[0];
			$vendor_store_name = $resultrow[1];
			
			if	($vendor_uid == $user->id)
			{ // hide core VM edit button to avoid confusion
				$hide_edit = "jQuery(document).ready(function(jQuery){
					jQuery('img').each(function(index){
					if (this.src.substr(this.src.length-28,28)=='media/system/images/edit.png'){
						this.style.display='none';
					}
				 });});";
				 $doc->addScriptDeclaration($hide_edit);
			}
			
			
			if($profileman=='js')
			{
				require_once JPATH_BASE.'/components/com_community/libraries/core.php';
				CWindow::load();
			}
			if($profileman=='es')
			{
				$doc->addStyleSheet( $juri.'components/com_easysocial/themes/wireframe/styles/more.min.css' );
				$file 	= JPATH_ROOT . '/administrator/components/com_easysocial/includes/foundry.php';
				jimport( 'joomla.filesystem.file' );
				if( !JFile::exists( $file ) )
					return;
				require_once $file ;
				Foundry::language()->load( 'com_easysocial' , JPATH_ROOT );
				$modules 	= Foundry::modules( 'mod_easysocial_toolbar' );
				$modules->loadComponentScripts();
				$config 	= Foundry::config();
				$es_followers = $config->get( 'followers.enabled' );
				$es_user = Foundry::user($vendor_uid);
			}
			$lang = JFactory::getLanguage();
			$q = "SELECT id FROM #__menu 
			WHERE link ='index.php?option=com_vmvendor&view=vendorprofile' 
			AND ( language ='".$lang->getTag()."' OR language='*') 
			AND published='1' AND access='1' ";
			$db->setQuery($q);
			$vmvitemid = $db->loadResult();
				
			if($linkto==1)
			{	
				$p = 'index.php?option=com_vmvendor&view=vendorprofile&userid='.$vendor_uid.'&Itemid='.$vmvitemid;
				$profile_url = JRoute::_($p);
			}
			elseif($linkto==2)
			{
				switch($profileman)
				{
					case '0':
						$profile_url = '';
					break;
					case 'cb':
						$profile_url = JRoute::_('index.php?option=com_comprofiler&task=userProfile&user='.$vendor_uid.'&Itemid='.$profileitemid);
					break;
					case 'js':
						$profile_url = JRoute::_('index.php?option=com_community&view=profile&userid='.$vendor_uid.'&Itemid='.$profileitemid);
					break;
					case 'es':
						$profile_url = JRoute::_('index.php?option=com_easysocial&view=profile&id='.$vendor_uid.':'.JFilterOutput::stringURLSafe($vendor_username).'&Itemid='.$profileitemid);
					break;
				}
			}
			if($vendor_uid && $questionform)
				$html .= '<div id="vmvendor_link" class="well well-sm">';
				
			if	($vendor_uid == $user->id)
			{
				$html .='<div>';
				if($show_deletebutton)
				{
					$html .='<div class="btn-group">
					<form id="delete_product" 
					action="'.JRoute::_('index.php?option=com_vmvendor&view=vendorprofile&Itemid='.$vmvitemid).'" 
					method="POST" 
					onsubmit="return confirm(\''.JText::_('PLG_VMVENDOR_VENDORLINK_ARE YOUSURE').'\');">';
				}
					
				$html .= '<a href="'.JRoute::_('index.php?option=com_vmvendor&view=editproduct&productid='.$virtuemart_product_id.'&Itemid='.$this->getAddproductItemid() ).'" 
				class="btn btn-small btn-mini btn-default '.$tipclass.'" 
				title="'.JText::_('PLG_VMVENDOR_VENDORLINK_EDITYOURPRODUCT').'">
				<span class="vmv-icon-edit"></span></a> ';
					
				if($show_deletebutton)
				{
					$q ="SELECT product_price 
					FROM #__virtuemart_product_prices WHERE virtuemart_product_id='".$virtuemart_product_id."' " ;
					$db->setQuery($q);
					$virtuemart_product_price = $db->loadResult();
					$html .= '<input type="hidden" name="controller" value="vendorprofile">
					<input type="hidden" name="task" value="deleteproduct">
					<input type="hidden" name="price" value="'.$virtuemart_product_price.'">
					<input type="hidden" name="delete_productid" value="'.$virtuemart_product_id.'">
					<input type="hidden" name="userid" value="'.$user->id.'">
					<button class="btn btn-small btn-mini btn-default '.$tipclass.'" 
					title="'.JText::_('PLG_VMVENDOR_VENDORLINK_DELETEYOURPRODUCT').'">
					<span class="vmv-icon-trash"></span></button></form></div>';
				}
				$html .='</div>';
			}
			
			if($vendor_uid)
			{
				$html .= '<div id="addedby" >';
				if($show_vendorthumb )
				{
					$q = "SELECT vm.file_url, vm.file_url_thumb 
					FROM #__virtuemart_medias vm 
					JOIN #__virtuemart_vendor_medias vvm ON vvm.virtuemart_media_id = vm.virtuemart_media_id
					JOIN #__virtuemart_vendor_users vvu ON vvu.virtuemart_vendor_id = vvm.virtuemart_vendor_id 
					WHERE vvu.virtuemart_user_id='".$vendor_uid."'  ";
					$db->setQuery($q);
					$object = $db->loadObject();
					$vendor_thumb = &$object->file_url_thumb;
					if(!$vendor_thumb)
						$vendor_thumb = &$object->file_url;
					if(!$vendor_thumb)
						$vendor_thumb = 'components/com_vmvendor/assets/img/noimage.gif';
					$html .= '<div id="vmv-thumb">';
					if($linkto && $profile_url!='' )
					{
						$html .= ' <a href="'.$profile_url.'" ';
						if($profileman=='es')
						{
							//$html .=' data-user-id="'.$vendor_uid.'"  data-popbox="module://easysocial/profile/popbox"';
						}
						$html .= '> ';
					}
					$html .= '<img id="vmv-img" src="'.$juri.$vendor_thumb.'" alt="vendor" height="50" />';
					if($linkto && $profile_url!='' )
						$html .= '</a>';
					$html .= '</div>';
				}
				
				$html .= '<div id="addedby_text" >';
				$html .= '<div>'.JText::_('PLG_VMVENDOR_VENDORLINK_ADDEDBY').' ';				
				if($linkto && $profile_url!='' )
				{
					$html .= ' <a href="'.$profile_url.'" ';
					if($profileman=='es')
					{
						//$html .=' data-user-id="'.$vendor_uid.'"  data-popbox="module://easysocial/profile/popbox"';
					}
					$html .= '> <i class="vmv-icon-user"></i>';
				}
	
				$html .= ucwords($vendor_store_name);
				if($linkto && $profile_url!='' )
					$html .= '</a>';
				$html .= '</div>';

				if($show_rating)
				{
					$vendor_rating = plgContentVmvendor_Vendorlink::getVendorRating($vendor_uid);
					$doc->addStyleSheet($juri.'components/com_vmvendor/assets/css/jquery.rating.css');
					$votes_count = $vendor_rating['count']  ;
					 $average_percent = $vendor_rating['percent']  ;
					$stars = ($average_percent * $rating_stars)/100 ;
					$html .= '<div id="vendor_rating" class="'.$tipclass.'" 
					title="'.JText::_('PLG_VMVENDOR_VENDORLINK_VENDORRATING').' '.$stars.'/'.$rating_stars.'" >';
					for ($i=1;$i<= $stars;$i++)
					{
							$html .= '<i class="vmv-icon-star"></i>';	
					}
					if(!is_int($stars))
					{
							$html .= '<i class="vmv-icon-star-half"></i>';
					}
					for ($j=1;$j<= $rating_stars - $stars ; $j++)
					{
							$html .= '<i class="vmv-icon-star-empty"></i>';	
					}
					$html .= '</div>';	
				}
				
				
				
				if($profileman=='es' && $es_followers)
				{
					$html .= '<div id="follow_vendor">';
					$isfollowed = $es_user->isFollowed($user->id);
					if(!$isfollowed && $user->id != $vendor_uid)
					{
						$html .= '<a href="javascript:void(0);"  class="btn btn-success btn-small btn-mini" 
						data-es-followers-follow data-es-followers-id="'.$vendor_uid.'">
						<i class="vmv-icon-follow" ></i> '. JText::_( 'PLG_VMVENDOR_VENDORLINK_FOLLOW' ).'
						</a>';
					}
					else
					{
						$html .= '<a class="author-friend btn btn-default btn-small btn-mini" disabled>
						<i class="vmv-icon-follow" ></i> '. JText::_( 'PLG_VMVENDOR_VENDORLINK_FOLLOWING' ).'
						</a>';	
					}
					$html .= '</div>';	
				}
				$html .= '</div>';
				$html .= '</div>';
			}
			
			
			
			if($questionform && $vendor_uid>0)
			{
				$uri 	 = JFactory::getURI();
				$href	 = urlencode(htmlentities($uri->toString() ));
				$html .= '<div id="questionto">';
				if($questionform==1)
				{ 
				$iframe_url = 'index.php?option=com_vmvendor&view=askvendor&productid='.$virtuemart_product_id.'&vendoruserid='.$vendor_uid.'&tmpl=component&href='.$href;
					if($modaltype=='b')
					{
						$name = "askvendor";
						$html .= '<a  class="btn btn-default btn-small btn-mini" ';
						if($vendor_uid == $user->id)
							$html .= ' disabled ';
						else
							$html .= 'href="#modal-' . $name.'"  data-toggle="modal" ';
						$html .= '><i class="vmv-icon-mail"></i>  '.JText::_('PLG_VMVENDOR_VENDORLINK_ASK'). '</a>';
						$params = array();
						$params['title']  = JText::_('PLG_VMVENDOR_VENDORLINK_ASK').' '.ucwords($vendor_store_name);
						$params['url']    = $iframe_url;
						$params['height'] = "450";
						$params['width']  = "100%";
						$footer='';
						$html .= JHtml::_('bootstrap.renderModal', 'modal-' . $name, $params, $footer);
					}
					elseif($modaltype=='j')
					{
						if($vendor_uid == $user->id)
							$disabled = 'disabled';
						else
							$disabled ='';
						JHTML::_('behavior.modal');	
						if($vendor_uid == $user->id)
						{
							$html .= '<a class="btn btn-default btn-small btn-mini   '.$disabled.' " >
							<i class="vmv-icon-mail" ></i> '.JText::_('PLG_VMVENDOR_VENDORLINK_ASK').'</a>';
						}
						else
						{
							$html .= '<a class="btn btn-default btn-small btn-mini modal '.$tipclass.' '.$disabled.' " 
							title="'.JText::_('PLG_VMVENDOR_VENDORLINK_ASK').'" 
							href="'.JRoute::_($iframe_url).'" 
							rel="{handler: \'iframe\', size: {x: 550, y: 550}}">
							<i class="vmv-icon-mail" ></i> '.JText::_('PLG_VMVENDOR_VENDORLINK_ASK').'</a>';
						}
					}
					elseif($modaltype=='s')
					{
						if($vendor_uid == $user->id)
							$disabled = 'disabled';
						else
							$disabled ='';
						JHTML::_('behavior.modal');	
						if($vendor_uid == $user->id)
						{
							$html .= '<a class="btn btn-default btn-small btn-mini   '.$disabled.' " >
							<i class="vmv-icon-mail" ></i> '.JText::_('PLG_VMVENDOR_VENDORLINK_ASK').'</a>';
						}
						else
						{
							$doc->addScript( $juri.'components/com_vmvendor/assets/js/sweetalert/sweetalert2.min.js');
							$doc->addStylesheet( $juri.'components/com_vmvendor/assets/js/sweetalert/sweetalert2.css'); 
							$emailvendor = "function emailvendor(){
							swal({ 
								  html:	'<iframe src=\"".$iframe_url."\" id=\"askvendor_iframe\" >' +
									'</iframe>',
									width: 600,
									padding:0,
								  showCloseButton: false,
								  showConfirmButton: false,
								  showCancelButton: false
								 
								})
							}";
							$doc->addScriptDeclaration($emailvendor);
			
							$html .= '<a class="btn btn-default btn-small btn-mini '.$tipclass.' '.$disabled.' " 
							title="'.JText::_('PLG_VMVENDOR_VENDORLINK_ASK').'" onclick="emailvendor()" >
							<i class="vmv-icon-mail" ></i> '.JText::_('PLG_VMVENDOR_VENDORLINK_ASK').'</a>';
						}
					}	
				}
				elseif($questionform==2 )
				{ 
					$html .= '<a ';
					if($vendor_uid == $user->id)
						$html .= ' disabled ';
					elseif( $profileman=='js')
					{
						$html .= ' href="javascript:" onclick="joms.api.pmSend('.$vendor_uid.');" ';
					}
					elseif( $profileman=='es')
					{
						$html .= ' href="javascript:void(0)"  
						data-es-conversations-compose data-es-conversations-id="'.$vendor_uid.'"  ';
					}
					$html .= ' class="btn btn-mini btn-default">';
					$html .= '<i class="icon-question-sign"></i> '.JText::_('PLG_VMVENDOR_VENDORLINK_ASK');
					$html .= '</a>';
				}
				$html .= '</div>';
			}
			if($show_hits OR $app->isAdmin())
			{
				$q ="SELECT hits ,created_on 
				FROM #__virtuemart_products 
				WHERE virtuemart_product_id ='".$virtuemart_product_id."' ";
				$db->setQuery( $q );
				$views = $db->loadObject();
				$hits = $views->hits;
				if(!$hits)
					$hits = '0';
				if(
					($show_hits=='1' && ($vendor_uid == $user->id OR $app->isAdmin() )  ) // vendor owner and admin
					OR
					($show_hits=='2') // public
					OR
					( $app->isAdmin() ) // admin only
					)
				{
					$html .= '<div id="vmv-hits-count">'.$hits.'</div>';
					
				}
			}
			$html .= '<div style="clear:both"></div>';
			if($vendor_uid && $questionform)
				$html .= '</div>';
			$row->text = $html . $row->text;
		}
	}
	 public function getVendorRating($vendor_user_id) 
	{
		$db = JFactory::getDBO();
		$vendor_rating = array();
		$q = "SELECT percent FROM #__vmvendor_vendorratings 
		WHERE vendor_user_id = '".$vendor_user_id."' AND percent >0 ";
		$db->setQuery($q);
		$votes = $db->loadObjectList();
		$votes_count = count($votes);
		$total_pct = 0;
		if(count($votes))
		{
			foreach($votes as $vote)
			{
				$total_pct = $total_pct + $vote->percent;
			}
			if($votes_count)
				$average_percent = $total_pct / $votes_count;
			$vendor_rating['count'] = $votes_count;
			$vendor_rating['percent'] = $average_percent;
		}
		else
		{
			$vendor_rating['count'] = 0;
			$vendor_rating['percent'] = 0;
		}
		return $vendor_rating;
	}
}