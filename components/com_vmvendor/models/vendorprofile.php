<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2016 Adrien Roussel
 * @license : GNU/GPL v3 
 * @Website : https://www.nordmograph.com/extensions
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');

class VmvendorModelVendorprofile extends JModelItem
{
	public function getMyproducts() 
	{
		$cparams 		= JComponentHelper::getParams('com_vmvendor');
		$forbidcatids 	= $cparams->get('forbidcatids');
		$onlycatids 	= $cparams->get('onlycatids');

		$app 	= JFactory::getApplication();
		$db 	= JFactory::getDBO();
		$user 	= JFactory::getUser();
		$userid = $app->input->getInt('userid');
		if(!$userid  )
			$userid = $user->id;
		if (!class_exists( 'VmConfig' ))
			require JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php';
		VmConfig::loadConfig();
		
		$my_shoppergroups = $this->getMyShopperGroups();
		if(!$my_shoppergroups)
			$my_shoppergroups = array();
		$my_shoppergroups = join(',' , $my_shoppergroups );

		// Get the pagination request variables
		$limit = $app->getUserStateFromRequest('com_vmvendor.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart = $app->input->get('limitstart', 0, '', 'int');
		$catfilter	= $app->input->getInt('catfilter');
		
		$q = "SELECT DISTINCT(vp.virtuemart_product_id) , vp.product_sku, vp.product_in_stock , vp.published,
		vpl.product_s_desc , vpl.product_name ,  
		vpc.virtuemart_category_id ,
		vcl.category_name ,
		vpp.product_price , vpp.override ,vpp.product_override_price, vpp.product_currency , vcu.currency_code_3,
		vmnf.virtuemart_manufacturer_id , 
		vpsg.virtuemart_shoppergroup_id ";
		
		$q .= " FROM #__virtuemart_products vp 
		JOIN #__virtuemart_products_".VMLANG." vpl ON vpl.virtuemart_product_id = vp.virtuemart_product_id
		JOIN #__virtuemart_vmusers vv ON vv.virtuemart_vendor_id = vp.virtuemart_vendor_id 
		
		LEFT JOIN #__virtuemart_product_prices vpp ON vpp.virtuemart_product_id = vp.virtuemart_product_id 
		LEFT JOIN #__virtuemart_currencies vcu ON vpp.product_currency = vcu.virtuemart_currency_id ";
		
		$q .= "LEFT JOIN #__virtuemart_product_shoppergroups vpsg ON vpsg.virtuemart_product_id = vp.virtuemart_product_id ";
		
		$q .= "JOIN #__virtuemart_product_categories vpc ON vpc.virtuemart_product_id = vp.virtuemart_product_id 
		JOIN #__virtuemart_categories_".VMLANG." vcl ON vcl.virtuemart_category_id = vpc.virtuemart_category_id 
		JOIN #__virtuemart_categories vc ON vc.virtuemart_category_id = vpc.virtuemart_category_id 
		LEFT JOIN #__virtuemart_product_manufacturers vmnf ON vmnf.virtuemart_product_id = vp.virtuemart_product_id 
		WHERE vv.virtuemart_user_id = '".$userid."' 
		AND vp.virtuemart_vendor_id!='0'  
		AND (  vpsg.virtuemart_shoppergroup_id IS NULL OR vpsg.virtuemart_shoppergroup_id IN ('".$my_shoppergroups."')  )
		";
		// AND (  vpsg.virtuemart_shoppergroup_id IS NOT NULL OR vpsg.virtuemart_shoppergroup_id IN ('".$my_shoppergroups."')  )
		if($userid!=$user->id)
		{
			$q .="AND vc.published ='1' 
				AND vp.published='1'";
		}
		if($catfilter!='')
			$q .=" AND vpc.virtuemart_category_id='".$catfilter."' ";
		
		if($forbidcatids !='')
			$q .= " AND vpc.virtuemart_category_id NOT IN (". $forbidcatids .") ";
		
		if($onlycatids !='')
			$q .= " AND vpc.virtuemart_category_id IN (".$onlycatids .") ";
		
		$q .= "GROUP BY vp.virtuemart_product_id 
		ORDER BY vp.virtuemart_product_id DESC ";
		$db->setQuery($q);
		
		$total = @$this->_getListCount($q);
		
		// update new VM table...  piece of code to be removed in some future releases
		// this in now done on vendor creation function. But some older vendors did not have that table	
		if($total>0 && $userid!='0' )
		{
			$qq = "SELECT virtuemart_vendor_id FROM #__virtuemart_vendor_users WHERE virtuemart_user_id='".$userid."' ";
			$db->setQuery($qq);
			$virtuemart_vendor_id = $db->loadResult();
			if(!$virtuemart_vendor_id)
			{
				$qq="SELECT virtuemart_vendor_id FROM #__virtuemart_vmusers WHERE virtuemart_user_id='".$userid."'  ";
				$db->setQuery($qq);
				$virtuemart_vendor_id = $db->loadResult();
				$qq = "INSERT INTO #__virtuemart_vendor_users (virtuemart_vendor_id,virtuemart_user_id)
				VALUES ( '".$virtuemart_vendor_id."' , '".$userid."' ) ";
				$db->setQuery($qq);
				$db->execute();
			}
		}
		// end transitional piece of code
		
		$myproducts = $this->_getList($q, $limitstart, $limit);
		return array($myproducts, $total, $limit, $limitstart);
	}
	
	public function getMyShopperGroups()
	{
		$db 		= JFactory::getDBO();
		$user 		= JFactory::getUser();
		$q = "SELECT virtuemart_shoppergroup_id 
		FROM #__virtuemart_vmuser_shoppergroups 
		WHERE virtuemart_user_id='".$user->id."' ";
		$db->setQuery($q);
		$myshoppergroups = $db->loadObjectList();
		return  $myshoppergroups;	
	}
	
	public function getAllmyproducts() 
	{
		$app 		= JFactory::getApplication();
		$db 		= JFactory::getDBO();
		$user 		= JFactory::getUser();
		$userid 	= $app->input->getInt('userid');
		if(!$userid  )
			$userid = $user->id;
		$cparams 		= JComponentHelper::getParams('com_vmvendor');
		$forbidcatids 	= $cparams->get('forbidcatids');
		$onlycatids 	= $cparams->get('onlycatids');
		$q = "SELECT DISTINCT(vp.virtuemart_product_id) ,vpc.virtuemart_category_id 
		FROM #__virtuemart_products vp 
		JOIN #__virtuemart_vmusers vv ON vv.virtuemart_vendor_id = vp.virtuemart_vendor_id 
		JOIN #__virtuemart_product_categories vpc ON vpc.virtuemart_product_id = vp.virtuemart_product_id 		
		JOIN #__virtuemart_categories vc ON vc.virtuemart_category_id = vpc.virtuemart_category_id 		
		WHERE vv.virtuemart_user_id = '".$userid."' 
		AND vp.virtuemart_vendor_id!='0' 
		AND vp.published='1' 
		AND vp.product_parent_id ='0' 
		AND vc.published ='1' ";
		if($forbidcatids !='')
			$q .= " AND vpc.virtuemart_category_id NOT IN (". $forbidcatids .") ";
		
		if($onlycatids !='')
			$q .= " AND vpc.virtuemart_category_id IN (". $onlycatids .") ";
		$q .= "GROUP BY vp.virtuemart_product_id ";
		$db->setQuery($q);
		$allmyproducts = $db->loadObjectList();
		return $allmyproducts;
	}
	
	public function getSocialprofileitemid()
	{
		$cparams 		= JComponentHelper::getParams('com_vmvendor');
		$profileman 	= $cparams->get('profileman');
		if($profileman=='cb')
			$url = 'index.php?option=com_comprofiler&view=userprofile';
		elseif($profileman=='js')
			$url = 'index.php?option=com_community&view=profile';
		elseif($profileman=='es')
			$url = 'index.php?option=com_easysocial&view=profile';
		$lang = JFactory::getLanguage();
		$db 	= JFactory::getDBO();
		$q = "SELECT id FROM #__menu 
		WHERE link='".$url."' 
		AND type='component'  AND ( language ='".$lang->getTag()."' OR language='*') AND published='1'  ";
		$db->setQuery($q);
		return $itemid = $db->loadResult();
		
	}
	
	
	public function getSessionCurrency() 
	{
		$app = JFactory::getApplication();
		$formcur = $app->input->post->getInt('virtuemart_currency_id');
		if($formcur!='')
			$cur = $formcur;
		else
		{
			if (!class_exists('CurrencyDisplay'))
				require JPATH_VM_ADMINISTRATOR.'/helpers/currencydisplay.php';
			$currencyDisplay = CurrencyDisplay::getInstance();
 			$cur = $app->getUserStateFromRequest( "virtuemart_currency_id", 'virtuemart_currency_id',vRequest::getInt('virtuemart_currency_id',$currencyDisplay->_vendorCurrency) );
		}
		$db = JFactory::getDBO();
		$q ="SELECT   vc.virtuemart_currency_id,  vc.currency_symbol , vc.currency_positive_style ,vc.currency_decimal_place,
		 vc.currency_decimal_symbol , vc.currency_thousands ,vc.currency_code_3
		FROM #__virtuemart_currencies vc 
		WHERE vc.virtuemart_currency_id ='".$cur."' " ;		
		$db->setQuery($q);
		$this->main_currency = $db->loadRow();
		return $this->main_currency;
	}
	
	public function getMainCurrency() 
	{
		$db = JFactory::getDBO();
		$q ="SELECT vc.currency_code_3 
		FROM #__virtuemart_currencies vc 
		LEFT JOIN #__virtuemart_vendors vv ON vv.vendor_currency = vc.virtuemart_currency_id 
		WHERE vv.virtuemart_vendor_id ='1' " ;		
		$db->setQuery($q);
		$this->main_currency = $db->loadResult();
		return $this->main_currency;
	}
	
	public function getVendordata() 
	{
		$user 			= JFactory::getUser();
		$app			= JFactory::getApplication();
		$db 			= JFactory::getDBO();
		$cparams 		= JComponentHelper::getParams('com_vmvendor');
		$show_address 	= $cparams->get('show_address',0);
		$userid = $app->input->get('userid',null,'int');
		if(!$userid)
			$userid = $user->id;		
		if (!class_exists( 'VmConfig' ))
			require JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php';
		VmConfig::loadConfig();
		
		$q ="SELECT  vvl.vendor_store_desc , vvl.vendor_terms_of_service , vvl.vendor_legal_info , 
		vvl.vendor_store_name , vvl.vendor_phone , vvl.vendor_url ,	vv.virtuemart_vendor_id ";
		if($show_address)
		{
			$q .= ",  vva.address,vva.zip,vva.city  
					, vco.country_name , vcs.state_name ,vva.latitude,vva.longitude";
		}
		$q .= " FROM #__virtuemart_vendors_".VMLANG." vvl 
		LEFT JOIN #__virtuemart_vmusers vv ON vv.virtuemart_vendor_id = vvl.virtuemart_vendor_id ";
		if($show_address)
		{
			$q .= " LEFT JOIN #__vmvendor_vendoraddress vva ON vva.vendor_user_id = vv.virtuemart_user_id 
				LEFT JOIN #__virtuemart_countries vco ON vco.virtuemart_country_id = vva.virtuemart_country_id 
				LEFT JOIN #__virtuemart_states vcs ON vcs.virtuemart_state_id = vva.virtuemart_state_id ";
		}
		$q .= " WHERE vv.virtuemart_user_id='".$userid."' " ;	
		$db->setQuery($q);
		$this->vendor_data = $db->loadRow();
		return $this->vendor_data;
	}
	
	public function getUserthumb() 
	{
		$db 			= JFactory::getDBO();
		$app			= JFactory::getApplication();
		$cparams 		= JComponentHelper::getParams('com_vmvendor');
		$profileman 	= $cparams->get('profileman');
		$naming 		= $cparams->get('naming','username');
		$user 			= JFactory::getUser();
		$userid = $app->input->getInt('userid');
		if(!$userid)
			$userid = $user->id;
		$q = "SELECT u.".$naming."  ";
		if($profileman=='cb')
			$q .=", c.avatar ";
		elseif($profileman=='js')
			$q .=", c.thumb AS avatar ";
			
		// use Easysocial method to retrieve ES avatars.
	
		$q .=" FROM #__users u ";
		if($profileman=='cb')
			$q .=" LEFT JOIN #__comprofiler c ON c.user_id = u.id";
		elseif($profileman=='js')
			$q .=" LEFT JOIN #__community_users c ON c.userid = u.id";
		$q .=" WHERE u.id = '".$userid."' ";
		$db->setQuery($q);
		$this->user_thumb = $db->loadRow();
		return $this->user_thumb;
	}
	
	public function getJSProfileallowed($profiletypes_ids) 
	{
		$db 				= JFactory::getDBO();
		$user 				= JFactory::getUser();
		$cparams 			= JComponentHelper::getParams('com_vmvendor');
		$profiletypes_mode 	= $cparams->get('profiletypes_mode');
		
		$allowed = 0;
		if($profiletypes_mode==1)
			$q = "SELECT profile_id FROM #__community_users WHERE userid='".$user->id."' ";
		if($profiletypes_mode==2)
			$q = "SELECT profiletype FROM #__xipt_users WHERE userid='".$user->id."' ";
		$db->setQuery($q);
		$user_profile_id = $db->loadResult();
		$allowedprofiles_array = array();
		if(strpos( $profiletypes_ids , ',' ) ){
			$exploded = explode( ',' , $profiletypes_ids);
			$count = count($exploded);
			for($i= 0 ; $i < $count ; $i++ ){
				$allowedprofiles_array[] = $exploded[$i];	
			}		  
		}
		else
			$allowedprofiles_array[] = $profiletypes_ids;
		if(  in_array ($user_profile_id, $allowedprofiles_array ) )
			$allowed	= 1 ;
		return $allowed;
	}
	
	static function getVendorRating($vendor_user_id) 
	{
		$db = JFactory::getDBO();
		$vendor_rating = array();
		$q = "SELECT percent FROM #__vmvendor_vendorratings WHERE vendor_user_id = '".$vendor_user_id."' AND percent >0 ";
		$db->setQuery($q);
		$votes = $db->loadObjectList();
		$votes_count = count($votes);
		$total_pct = 0;
		if(count($votes))
		{
			foreach($votes as $vote){
				$total_pct = $total_pct + $vote->percent;
			}
			if($votes_count)
				$average_percent = $total_pct / $votes_count;
			$vendor_rating['count'] = $votes_count;
			$vendor_rating['percent'] = $average_percent;
		}
		
		return $vendor_rating;
	}
	
	public function getVendorJgroup() 
	{
		$db 	= JFactory::getDBO();
		$app	= JFactory::getApplication();
		$user 	= JFactory::getUser();
		$userid = $app->input->get('userid',null,'int');
		if(!$userid)
			$userid = $user->id;
		$q = "SELECT ug.title
		FROM #__usergroups ug 
		
		JOIN #__vmvendor_plans p ON p.jusergroups = ug.id  AND p.jusergroups!='' AND p.state='1' 
		JOIN #__user_usergroup_map ugm ON ugm.group_id=ug.id AND ugm.user_id =  '".$userid."'";
		$db->setQuery($q);
		$jgroup = $db->loadResult();
		return $jgroup;
	}
	
	static function traverse_tree_down( $category_id , $level , $avail_cats,$parent_cats)
	{
		if (!class_exists( 'VmConfig' ))
		{
			require_once JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php';
			VmConfig::loadConfig();
		}
		
		$cparams 		= JComponentHelper::getParams('com_vmvendor');
		$products_category_filter = $cparams->get('products_category_filter',1);
		$forbidcatids 	= $cparams->get('forbidcatids');
		$onlycatids 	= $cparams->get('onlycatids');
		$app = JFactory::getApplication();
		$catfilter	= $app->input->getInt('catfilter');
		$db 		= JFactory::getDBO();	
		$level++;
		$q = "SELECT * 
		FROM #__virtuemart_categories_".VMLANG." AS vmcl, 
		#__virtuemart_category_categories AS vmcc,   
		#__virtuemart_categories AS vmc
		WHERE vmcc.category_parent_id = '".$category_id."' 
		AND vmcl.virtuemart_category_id = category_child_id 
		AND vmc.virtuemart_category_id = vmcl.virtuemart_category_id 
		AND vmc.published='1'";
		if($forbidcatids !='')
			$q .= " AND vmc.virtuemart_category_id NOT IN (". $forbidcatids .") ";
		if($onlycatids !='')
			$q .= " AND vmc.virtuemart_category_id IN (". $onlycatids .") ";
		$q .= "	ORDER BY vmc.ordering ASC ";
		$db->setQuery($q);
		$cats = $db->loadObjectList();

		foreach($cats as $cat)
		{
			if($products_category_filter=='2') // vendors only
			{
				if( in_array($cat->virtuemart_category_id , $avail_cats)  ||  in_array($cat->virtuemart_category_id , $parent_cats) )
				{
					echo '<option value="'.$cat->virtuemart_category_id.'"';
					if($catfilter == $cat->virtuemart_category_id)
						echo ' selected="selected" ';
					if( !in_array($cat->virtuemart_category_id , $avail_cats) )
						echo ' disabled="disabled" ';
					echo ' >';
					$parent =0;
					for ($i=1; $i<$level; $i++)
					{
						echo ' . ';
					}
					if($level >1)
							echo '  |_ ';
					echo  JText::_($cat->category_name).'</option>';
				}
			}
			elseif($products_category_filter=='1') // all
			{
				echo '<option value="'.$cat->virtuemart_category_id.'"';
				if($catfilter == $cat->virtuemart_category_id)
					echo ' selected="selected" ';
				//if( !in_array($cat->virtuemart_category_id , $avail_cats) )
					//echo ' disabled="disabled" ';
				echo ' >';
				$parent =0;
				for ($i=1; $i<$level; $i++)
				{
					echo ' . ';
				}
				if($level >1)
						echo '  |_ ';
				echo  JText::_($cat->category_name).'</option>';
				
			}
			VmvendorModelVendorprofile::traverse_tree_down($cat->category_child_id, $level, $avail_cats, $parent_cats);		
		}
	}
	
	static function get_filterparentcats($category_id, $parent_cats,  $i)// to get cat  filter parent categories
	{
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();	
		$cparams 		= JComponentHelper::getParams('com_vmvendor');
		$forbidcatids 	= $cparams->get('forbidcatids');
		$onlycatids 	= $cparams->get('onlycatids');
		$q = "SELECT category_parent_id FROM #__virtuemart_category_categories WHERE category_child_id ='".$category_id."' ";
		if($forbidcatids !='')
			$q .= " AND category_parent_id NOT IN (". $forbidcatids .") ";
		if($onlycatids !='')
			$q .= " AND category_parent_id IN (".$onlycatids .") ";
		$db->setQuery($q);
		$parentcat = $db->loadResult();
		if($parentcat!='0' && $parentcat!='')
		{
			$parent_cats[] = $parentcat;
			$parent_cats[] = VmvendorModelVendorprofile::get_filterparentcats($parentcat, $parent_cats,  $i);
		}
		return $parent_cats ;
		$i++;	
	}
	
	static function array_keys_multi($array,&$vals, $done_cats) 
	{
		foreach ($array as $key => $value)
		{
			if (is_array($value) && !in_array($value,$done_cats ) )
			{
				$done_cats[] = $value;
				VmvendorModelVendorprofile::array_keys_multi($value,$vals,$done_cats);
			}
			else
			{
				$vals[] = $value; 
			}
		}
		return $vals;
	}
	
	function getVendorLocation()
	{
		$db 	= JFactory::getDBO();
		$user 	= JFactory::getUser();
		$app = JFactory::getApplication();
		$userid = $app->input->get('userid',null,'int');
		if(!$userid)
			$userid = $user->id;
		if($userid==0)
			return false;
		
		$cparams 		= JComponentHelper::getParams('com_vmvendor');
		$profileman 	= $cparams->get('profileman','0');
		$q = '';
		if($profileman =='cb')
			$q ="SELECT geolat , geolng FROM #__comprofiler WHERE geoLat!='' AND geoLng !='' AND geoLat!='0' AND geoLng !='0' AND user_id ='".$userid."' ";
		elseif($profileman =='js')
			$q ="SELECT latitude , longitude FROM #__community_users WHERE latitude!='255' AND longitude !='255' AND userid ='".$userid."' ";
		elseif($profileman =='es')
		{
			$q ="SELECT sfd.data  AS latitude , 
			 sfd2.data AS longitude 
			FROM #__social_fields_data AS sfd 
		 	JOIN #__social_fields_data AS sfd2 ON sfd.field_id = sfd2.field_id AND sfd.uid = sfd2.uid 
		  	WHERE  sfd.type='user' 
			AND sfd.datakey='latitude' 
			AND sfd2.type='user' 
			AND sfd2.datakey='longitude' 
			AND sfd.uid='".$userid."' ";
	
		}
		if($q!='')
		{
			$db->setQuery($q);
			$coords = $db->loadRow();
			return $coords;
		}
		else
			return false;
		
	}
	
	function getVendorThumb()
	{
		$db 	= JFactory::getDBO();
		$user 	= JFactory::getUser();
		$app = JFactory::getApplication();
		$userid = $app->input->get('userid',null,'int');
		if(!$userid)
			$userid = $user->id;
		if($userid==0)
			return false;
			
		$q = "SELECT vm.file_url 
			FROM #__virtuemart_medias vm 
			LEFT JOIN #__virtuemart_vendor_medias vvm ON vvm.virtuemart_media_id = vm.virtuemart_media_id 
			WHERE vvm.virtuemart_vendor_id = '". $this->vendor_data[6]."' 
			AND vm.file_type='vendor' ORDER BY file_is_product_image DESC ";
		$db->setQuery($q);
		return $vendor_thumb_url = $db->loadResult();
	}
	
	
	
	static function applytaxes( $pricebefore, $catid , $manufacturer_id ,  $vendor_id){
		$is_shopper = 1;
		$user = JFactory::getUser();
		$db = JFactory::getDBO();
		$q ="SELECT vc.virtuemart_calc_id , vc.calc_name , vc.calc_kind , vc.calc_value_mathop , vc.calc_value , vc.calc_currency ,  vc.ordering 
		FROM #__virtuemart_calcs vc 
		WHERE vc.published='1' 
		AND (vc.shared ='1' OR vc.virtuemart_vendor_id = '".$vendor_id."' ) ";
		
		$q .= "AND ('".$catid."' IN (SELECT virtuemart_category_id FROM #__virtuemart_calc_categories WHERE virtuemart_calc_id = vc.virtuemart_calc_id)  OR  (SELECT COUNT(virtuemart_category_id) FROM #__virtuemart_calc_categories WHERE virtuemart_calc_id = vc.virtuemart_calc_id)=0) " ;
				
		$q .= "AND ('".$manufacturer_id."' IN (SELECT virtuemart_manufacturer_id FROM #__virtuemart_calc_manufacturers WHERE virtuemart_calc_id = vc.virtuemart_calc_id)  OR  (SELECT COUNT(virtuemart_manufacturer_id) FROM #__virtuemart_calc_manufacturers WHERE virtuemart_calc_id = vc.virtuemart_calc_id)=0) " ;
			
		$q .= "AND ( (SELECT virtuemart_shoppergroup_id FROM #__virtuemart_vmuser_shoppergroups WHERE virtuemart_user_id='".$user->id."') IN (SELECT virtuemart_shoppergroup_id FROM #__virtuemart_calc_shoppergroups WHERE virtuemart_calc_id = vc.virtuemart_calc_id)  OR  (SELECT COUNT(virtuemart_shoppergroup_id) FROM #__virtuemart_calc_shoppergroups WHERE virtuemart_calc_id = vc.virtuemart_calc_id)=0)" ;

		$q .= "AND (vc.publish_up='0000-00-00 00:00:00' OR vc.publish_up <= NOW() ) ";
		$q .= "AND (vc.publish_down='0000-00-00 00:00:00' OR vc.publish_down >= NOW() ) ";
		$q .= "ORDER BY vc.ordering ASC";
		$db->setQuery($q);
		$taxes = $db->loadObjectList();
		$price_withtax = $pricebefore;
		if(count($taxes)>0)
		{
			foreach($taxes as $tax)
			{
				$calc_value_mathop = $tax->calc_value_mathop;
				$calc_value = $tax->calc_value;
				switch ($calc_value_mathop)
				{
					case '+':
						$price_withtax = $price_withtax + $calc_value;
					break;
					case '-':
						$price_withtax = $price_withtax - $calc_value;
					break;
					case '+%':
						$price_withtax = $price_withtax + ( ( $price_withtax * $calc_value ) / 100 );
					break;
					case '-%':
						$price_withtax = $price_withtax - ( ( $price_withtax * $calc_value ) / 100 );
					break;
				}	
			}
		}		
		return $price_withtax;	
	}
	
	static function getLogvisit( )
	{
		$cparams 			= JComponentHelper::getParams('com_vmvendor');
		$exclude_users		= $cparams->get('exclude_users');
		$exclude_users	= explode(',', $exclude_users);
		$log_profilevisits	= $cparams->get('log_profilevisits', 1);
		$db 	= JFactory::getDBO();
		$juser 	= JFactory::getUser();
		$app = JFactory::getApplication();
		$userid = $app->input->getInt('userid');
		if(!$userid)
			return false;
		if(in_array( $juser->id , $exclude_users) OR $juser->id == $userid OR !$log_profilevisits )
		{
			return false;
		}
		else
		{	
			$db = JFactory::getDBO();
			$q = "SELECT id FROM #__vmvendor_profilevisits 
			WHERE profile_userid='".$userid."' 
			AND visitor_userid='".$juser->id."' 
			AND SUBSTR(date, 1, 10) ='".date('Y-m-d')."'  ";
			$db->setQuery($q);
			$id = $db->loadResult();
			if(!$id)
			{
				$q = "INSERT INTO #__vmvendor_profilevisits
				(profile_userid , visitor_userid, date  )
				VALUES 
				('".$userid."' , '".$juser->id."' , '".date('Y-m-d H:i:s')."'  )  ";
				$db->setQuery($q);
				$db->execute();
			}
		}
	}
	public function getVMVcomments()
	{
		$cparams	= JComponentHelper::getParams('com_vmvendor');
		$naming		= $cparams->get('naming','username');
		
		$app = JFactory::getApplication();
		$uid = $app->input->get->getInt('userid');
		if(!$uid)
			$uid = JFactory::getUser()->id;
		$db 	= JFactory::getDBO();
		$q = "SELECT vc.id, vc.title, vc.comment, vc.lang, vc.created_by, vc.created_on ,
		u.".$naming." 
		FROM #__vmvendor_comments vc 
		JOIN #__users u ON u.id = vc.created_by
		WHERE vc.vendor_userid='".$uid."' AND u.block='0'  AND vc.parent='0' ";
		//$q .= " AND vc.state='1' ";
		$q .= "	ORDER BY vc.id DESC LIMIT 4"; //3 + 1 to see if a more button should be displayed
		$db->setQuery($q);
		$comments = $db->loadObjectList();
		return $comments;		
	}
	function getDashboardItemid()
	{
		$app 	= JFactory::getApplication();
		$lang = JFactory::getLanguage();
		$db 	= JFactory::getDBO();
		$q = "SELECT id FROM #__menu 
		WHERE link='index.php?option=com_vmvendor&view=dashboard' 
		AND type='component'  AND ( language ='".$lang->getTag()."' OR language='*') AND published='1'  ";
		$db->setQuery($q);
		$itemid = $db->loadResult();
		if(!$itemid)
			$app->enqueueMessage('You must set and publish a menu item to the VMVendor Dashboard page (set to all languages or to this specific language).','warning');
		else
			return $itemid;
	
	}
	
	function getAddproductItemid()
	{
		$app 	= JFactory::getApplication();
		$lang 	= JFactory::getLanguage();
		$db 	= JFactory::getDBO();
		$q = "SELECT id FROM #__menu 
		WHERE link='index.php?option=com_vmvendor&view=addproduct' 
		AND type='component'  AND ( language ='".$lang->getTag()."' OR language='*') AND published='1' ";
		$db->setQuery($q);
		$itemid = $db->loadResult();
		if(!$itemid)
			$app->enqueueMessage('You must set and publish a menu item to the VMVendor Addproduct page (set to all languages or to this specific language).','warning');
		else
			return $itemid;
	}
	
	function getEditprofileItemid()
	{
		$app 	= JFactory::getApplication();
		$lang = JFactory::getLanguage();
		$db 	= JFactory::getDBO();
		$q = "SELECT `id` FROM `#__menu` WHERE `link`='index.php?option=com_vmvendor&view=editprofile' 
		AND `type`='component'  AND ( language ='".$lang->getTag()."' OR language='*') 
		AND published='1'  AND access='1' ";
		$db->setQuery($q);
		$itemid = $db->loadResult();
		return $itemid;

	
	}
	
	
	static function convertPrice($product_price, $product_currency , $session_currency)
	{
		require_once JPATH_ADMINISTRATOR.'/components/com_virtuemart/plugins/currency_converter/convertECB.php';
		$currencyConverter = new convertECB();
		//$ratio = convertECB::convert( $amountA, $currA='', $currB='', $a2rC = true, $relatedCurrency = 'EUR')
		$nu_price = $currencyConverter->convert( $product_price, $product_currency,$session_currency,$a2rC=true,$relatedCurrency='EUR');
		return $nu_price;
	}
}
?>