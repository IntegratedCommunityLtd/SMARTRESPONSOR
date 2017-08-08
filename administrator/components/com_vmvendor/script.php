<?php
/**
 * @package     com_vmvendor
 * @copyright   Copyright (C) 2016. Adrien ROUSSEL Nordmograph.com All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 * @author      Nordmograph <contact@nordmograph.com> - https://www.nordmograph.com./extensions
 */
defined( '_JEXEC' ) or die( 'Restricted access' ); 
jimport('joomla.installer.installer');
jimport('joomla.installer.helper');
/**
* Method to install the component
* 
* @param  mixed    $parent     The class calling this method
* @return void
*/

// Text should use language file strings which are defined in the administrator languages folder section in the XX-XX.com_lendr.sys.ini
class com_vmvendorInstallerScript
{
	
	function install($parent) 
	{

		echo '<h1><img src="components/com_vmvendor/assets/img/logo_90x90.png" alt="logo" width="90" height="90" style="vertical-align:center" /> VMVendor Component Installation</h1>';	
		$app = JFactory::getApplication();			
		$error = 0;		
		$cache =  JFactory::getCache();
		$cache->clean( null, 'com_vmvendor' );
		$db	= JFactory::getDBO();
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');	
				
				
		/************************************************************************
		 *
		 *                              START INSTALL
		 *
		 *************************************************************************/
		$install = '<table class="table table-condensed table-striped"><tbody>
		<tr><td><i class="vmv-icon-ok"></i> VMVENDOR Component installed successfully</td></tr>';
		
		
		
				
		/*/// Create a Vendor tasks menu if doesn't exsit
		$q = "SELECT COUNT(*) FROM  `#__menu_types` WHERE `menutype`='vmvendor' ";
		$db->setQuery($q);
		$ismenu = $db->loadResult();
		if($ismenu<1){
			$q ="INSERT INTO #__menu_types 	(menutype,title,description) VALUES ('vmvendor','VMVendor','VMVendor tasks') ";	
			$db->setQuery( $q );
			$db->execute();
			$q = "SELECT `extension_id` FROM `#__extensions` WHERE `name`= 'com_vmvendor' AND `type`= 'component'  AND `element`='com_vmvendor' ";
			$db->setQuery($q);
			$extension_id = $db->loadResult();
			
			// menu items
				//$menuitem_params = "{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"components\/com_vmvendor\/assets\/img\/vendor_profile.png\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}";
				$menuitem_params ='';
				$q  = "INSERT INTO `#__menu` 
				( `menutype` , `title` , `alias` ,  `path` , `link` , `type` , `published` , `parent_id` , `level` , `component_id` ,  `checked_out` , `checked_out_time` , `browserNav` , `access` , `img` , `template_style_id` , `params` ,  `home` , `language` , `client_id` ) 
				VALUES 
				( 'vmvendor' , 'Vendor Profile' , 'vendor-profile' , 'VMVendor/Profile' , 'index.php?option=com_vmvendor&view=vendorprofile' , 'component' , '1' , '1' , '1' , '".$extension_id."' , '1'  , '0000-00-00 00:00:00' , '0' , '0' ,'class:vmvendor-vendorprofile' , '0' , '".$menuitem_params."' , '0', '*' , '0'  )";
				$db->setQuery( $q );
				$db->execute();
				
				$menuitem_params ='';
				$q  = "INSERT INTO `#__menu` 
				( `menutype` , `title` , `alias` ,  `path` , `link` , `type` , `published` , `parent_id` , `level` , `component_id` ,  `checked_out` , `checked_out_time` , `browserNav` , `access` , `img` , `template_style_id` , `params` ,  `home` , `language` , `client_id` ) 
				VALUES 
				( 'vmvendor' , 'Vendor Dashboard' , 'vendor-dashboard' , 'VMVendor/Dashboard' , 'index.php?option=com_vmvendor&view=dashboard' , 'component' , '1' , '1' , '1' , '".$extension_id."' , '1' , '0000-00-00 00:00:00' , '0' , '0' ,'class:vmvendor-dashboard' , '0' , '".$menuitem_params."' , '0', '*' , '0'  )";
				$db->setQuery( $q );
				$db->execute();
				
				
				$menuitem_params ='';
				$q  = "INSERT INTO `#__menu` 
				( `menutype` , `title` , `alias` ,  `path` , `link` , `type` , `published` , `parent_id` , `level` , `component_id` , `checked_out` , `checked_out_time` , `browserNav` , `access` , `img` , `template_style_id` , `params` ,  `home` , `language` , `client_id` ) 
				VALUES 
				( 'vmvendor' , 'Add Product' , 'add-product' , 'VMVendor/AddProduct' , 'index.php?option=com_vmvendor&view=addproduct' , 'component' , '1' , '1' , '1' , '".$extension_id."' , '1' ,  '0000-00-00 00:00:00' , '0' , '0' ,'class:vmvendor-addproduct' , '0' , '".$menuitem_params."' , '0', '*' , '0'  )";
				$db->setQuery( $q );
				$db->execute();
			$install .=  '<tr><td><i class="vmv-icon-ok"></i> VMVendor Menu and menu items added successfully !</td></tr>';
			
			$q  = "INSERT INTO `#__modules` 
				( title , published, module , access , showtitle , client_id , language ) 
				VALUES 
				( 'Vendors Tasks' , '1' , 'mod_menu', '2' , '1' ,'0','*')";
				$db->setQuery( $q );
				$db->execute();
			$install .=  '<tr><td><i class="vmv-icon-ok"></i> VMVendor Tasks menu module created successfully !</td></tr>';
		}*/
				
			
					
		

		$module_installer = new JInstaller;
		$file_origin = JPATH_ADMINISTRATOR.'/components/com_vmvendor/install/modules/site/mod_vmvendors';
		if( $module_installer->install( $file_origin ) )
		{
			$install .= '<tr><td><i class="vmv-icon-ok"></i> VENDORS (mod_vmvendors) List module Installed successfully.</td></tr>';
		} else $error++;
		
		$module_installer = new JInstaller;
		$file_origin = JPATH_ADMINISTRATOR.'/components/com_vmvendor/install/modules/site/mod_vendorsmap';
		if( $module_installer->install( $file_origin ) )
		{
			$install .= '<tr><td><i class="vmv-icon-ok"></i> New! Map (mod_vendorsmap) module Installed successfully.</td></tr>';
		} else $error++;
		
		
		/*$module_installer = new JInstaller;
		$file_origin = JPATH_ADMINISTRATOR.'/components/com_vmvendor/install/modules/site/mod_vendorpoints2paypal';
		if( $module_installer->install( $file_origin ) )
		{	
			$install .= '<tr><td><i class="vmv-icon-ok"></i> VENDORPOINTS2PAYAPAL (mod_vendorpoints2paypal) module Installed successfully.</td></tr>';
		} else $error++;*/
		
		
		
		
		
		$plugin_installer = new JInstaller;
		$file_origin = JPATH_ADMINISTRATOR.'/components/com_vmvendor/install/plugins/content/vmvendor_vendorlink';
		if( $plugin_installer->install( $file_origin ) )
		{	
			$q = "UPDATE #__extensions SET enabled='1' WHERE `element`='vmvendor_vendorlink' AND `type`='plugin' AND folder='content'";
			$db->setQuery( $q );
			$db->execute();
			$install .= '<tr><td><i class="vmv-icon-ok"></i> VMVENDOR VENDOR LINK Content plugin Installed successfully.</td></tr>';
		} else $error++;
		
		
		
		
		
		$plugin_installer = new JInstaller;
		$file_origin = JPATH_ADMINISTRATOR.'/components/com_vmvendor/install/plugins/search/vmvendors';
		if( $plugin_installer->install( $file_origin ) )
		{	
			$q = "UPDATE #__extensions SET enabled='1' WHERE `element`='vmvendors' AND `type`='plugin' AND folder='search'";
			$db->setQuery( $q );
			$db->execute();
			$install .= '<tr><td><i class="vmv-icon-ok"></i> VMVENDOR VENDORS Search plugin Installed  and enabled successfully.</td></tr>';
		} else $error++;
		
		
		$plugin_installer = new JInstaller;
		$file_origin = JPATH_ADMINISTRATOR.'/components/com_vmvendor/install/plugins/vmpayment/points2vendor';
		if( $plugin_installer->install( $file_origin ) )
		{	
			$q = "UPDATE #__extensions SET enabled='1' WHERE `element`='points2vendor' AND `type`='plugin' AND folder='vmpayment'";
			$db->setQuery( $q );
			$db->execute();
			$install .= '<tr><td><i class="vmv-icon-ok"></i> VMVENDOR POINTS2VENDOR VMPayment plugin Installed and enabled successfully.</td></tr>';
		} else $error++;
		
		$plugin_installer = new JInstaller;
		$file_origin = JPATH_ADMINISTRATOR.'/components/com_vmvendor/install/plugins/vmcustom/vmvendorincart';
		if( $plugin_installer->install( $file_origin ) )
		{	
			$q = "UPDATE #__extensions SET enabled='1' WHERE `element`='vmvendorincart' AND `type`='plugin' AND folder='vmcustom'";
			$db->setQuery( $q );
			$db->execute();
			$install .= '<tr><td><i class="vmv-icon-ok"></i> NEW: VMVENDOR VENDOR LINK IN CART VMcustom plugin Installed successfully. You must create a new VM custom field (type: plugin) with this plugin. It will be automatically added to products on new product additions and editions. It will then display the link to vendor profile below the product name in cart and invoice views.</td></tr>';
		} else $error++;
		
		/*if (JFolder::exists($pathPluginsCOMMUNITY)) {
				$plugin_installer = new JInstaller;
				$file_origin = JPATH_ADMINISTRATOR.'/components/com_vmvendor/install/plugins/community/vmvendor';
				if( $plugin_installer->install( $file_origin ) )
				{	
					$install .= '<tr><td><i class="vmv-icon-ok"></i> VMVENDOR Jomsocial profile plugin Installed successfully. </td></tr>';
				} else $error++;	
			}*/
		

		$install .= '</tbody></table>';		
		$install .='<iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2Fpages%2FNordmograph-Web-marketing-and-Joomla-expertise%2F368385633962&amp;width&amp;layout=button_count&amp;action=recommend&amp;show_faces=false&amp;share=false&amp;height=21&amp;appId=739550822721946" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:21px;" allowTransparency="true"></iframe>';
		
		$install .='<div style="text-align:center;padding:0 0 100px; 0"><h3>Start here:</h3><br /><a href="index.php?option=com_config&view=component&component=com_vmvendor" class="btn btn-success btn-large"><i class="icon-cog"></i> VMVENDOR Component Settings</a></div>';
		
		echo $install;
	}
	/**
	* Method to update the component
	* 
	* @param  mixed  $parent   The class calling this method
	* @return void
	*/
function update($parent) 
{  
	$app = JFactory::getApplication();			
		$error = 0;		
		$cache =  JFactory::getCache();
		$cache->clean( null, 'com_vmvendor' );
		$db	= JFactory::getDBO();
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');	
		
		$q = "SELECT * FROM #__update_sites WHERE location LIKE 'http://www.nordmograph.com%' ";
		$db->setQuery($q);
		$sites = $db->loadObjectList();
		if(count($sites) )
		{
			foreach($sites as $site)
			{
				$old_location = $site->location;
				$new_location = str_replace('http:', 'https:', $old_location);
				$q = "UPDATE #__update_sites SET location ='".$new_location."' WHERE location='".$old_location."' ";
				$db->setQuery($q);
				$db->execute();
				$app->enqueueMessage('Update site location updated: '.$new_location , 'message');
			}
		}		
				
				
				
		// remove components\com_vmvendor\models\fields/mycurrency.php if exists
		JFile::delete(JPATH_SITE.'/components/com_vmvendor/models/fields/mycurrency.php', 'mycurrency.php');
		
		
		
		$update ='';
	

		$module_installer = new JInstaller;
		$file_origin = JPATH_ADMINISTRATOR.'/components/com_vmvendor/install/modules/site/mod_vmvendors';
		if( $module_installer->install( $file_origin ) )
		{
			$update .= '<div class="alert alert-success" ><i class="vmv-icon-ok"></i> VENDORS (mod_vmvendors) List module updated successfully.</div>';
		} else $error++;
		
		
		/*$module_installer = new JInstaller;
		$file_origin = JPATH_ADMINISTRATOR.'/components/com_vmvendor/install/modules/site/mod_vendorpoints2paypal';
		if( $module_installer->install( $file_origin ) )
		{	
			$update .= '<div class="alert alert-success" ><i class="vmv-icon-ok"></i> VENDORPOINTS2PAYAPAL (mod_vendorpoints2paypal) module updated successfully.</div>';
		} else $error++;*/
		
		
		
		
		
		$plugin_installer = new JInstaller;
		$file_origin = JPATH_ADMINISTRATOR.'/components/com_vmvendor/install/plugins/content/vmvendor_vendorlink';
		if( $plugin_installer->install( $file_origin ) )
		{	
			$update .= '<div class="alert alert-success" ><i class="vmv-icon-ok"></i> VMVENDOR VENDOR LINK Content plugin updated successfully.</div>';
		} else $error++;
		
		
		$plugin_installer = new JInstaller;
		$file_origin = JPATH_ADMINISTRATOR.'/components/com_vmvendor/install/plugins/search/vmvendors';
		if( $plugin_installer->install( $file_origin ) )
		{	
			$update .= '<div class="alert alert-success" ><i class="vmv-icon-ok"></i> VMVENDOR VENDORS Search plugin updated successfully.</div>';
		} else $error++;
		
		
		$plugin_installer = new JInstaller;
		$file_origin = JPATH_ADMINISTRATOR.'/components/com_vmvendor/install/plugins/vmpayment/points2vendor';
		if( $plugin_installer->install( $file_origin ) )
		{	
			$update .= '<div class="alert alert-success" ><i class="vmv-icon-ok"></i> VMVENDOR POINTS2VENDOR VMPayment plugin updated successfully.</div>';
		} else $error++;
		
		$plugin_installer = new JInstaller;
		$file_origin = JPATH_ADMINISTRATOR.'/components/com_vmvendor/install/plugins/vmcustom/vmvendorincart';
		if( $plugin_installer->install( $file_origin ) )
		{	
			$q = "UPDATE #__extensions SET enabled='1' WHERE `element`='vmvendorincart' AND `type`='plugin' AND folder='vmcustom'";
			$db->setQuery( $q );
			$db->execute();
			$update .= '<div class="alert alert-success" ><i class="vmv-icon-ok"></i> NEW: VMVENDOR VENDOR LINK IN CART VMcustom plugin updated successfully. You must create a new VM custom field (type: plugin) with this plugin. It will be automatically added to products on new product additions and editions. It will then display the link to vendor profile below the product name in cart and invoice views.</div>';
		} else $error++;
		
	/*	if (JFolder::exists( JPATH_SITE.'/plugins/community' )) {
				$plugin_installer = new JInstaller;
				$file_origin = JPATH_ADMINISTRATOR.'/components/com_vmvendor/install/plugins/community/vmvendor';
				if( $plugin_installer->install( $file_origin ) )
				{	
					$update .= '<div class="alert alert-success" ><i class="vmv-icon-ok"></i> VMVENDOR Jomsocial profile plugin Installed successfully.</div>';
				} else $error++;	
			}*/

 
  
  //echo JText::_('COM_GEOMMUNITY3JS_UPDATE_SUCCESSFULL');
  $update .='<iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2Fpages%2FNordmograph-Web-marketing-and-Joomla-expertise%2F368385633962&amp;width&amp;layout=button_count&amp;action=recommend&amp;show_faces=false&amp;share=false&amp;height=21&amp;appId=739550822721946" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:21px;" allowTransparency="true"></iframe>';
  $update .='<div style="text-align:center;padding:0 0 100px; 0"><br /><a href="index.php?option=com_vmvendor" class="btn btn-success btn-large"><i class="icon-cog"></i> VMVENDOR</a></div>';
  echo $update;
}
/**
* method to run before an install/update/uninstall method
*
* @param  mixed  $parent   The class calling this method
* @return void
*/
	function preflight($type, $parent) 
	{
	 // ...
	}
	 
	function postflight($type, $parent) 
	{
		$app = JFactory::getApplication();	
		$user = JFactory::getUser();	
		$db	= JFactory::getDBO();

		if($type=='install' OR $type=='update')
		{
			$aup = JPATH_SITE.'/components/com_altauserpoints/helper.php';
			if(file_exists($aup))
			{
				$q ="SELECT COUNT(*) FROM #__alpha_userpoints_rules 
				WHERE plugin_function ='plgaup_paypointstovendor' ";
				$db->setQuery($q);
				$is_installed = $db->loadResult();
				if($is_installed<1)
				{
					$q = "INSERT INTO `#__alpha_userpoints_rules` 
					( `rule_name` , 
					`rule_description` , 
					`rule_plugin` , 
					`plugin_function` ,  
					`access` ,
				 	`published` , 
					`system` , 
					`autoapproved` , 
					`fixedpoints` , 
					`category` , 
					`displaymsg` , 
					`notification`  )
					VALUES ( 'Points to Vendor' , 
					'Points for sells' , 'VMVendor' , 
					'plgaup_paypointstovendor' , '1' ,  '0' , '0' , '1' , '0' , 'pu' , '0' ,'1' )";
					$db->setQuery($q);
					$db->execute();
					$app->enqueueMessage( '<i class="icon-ok"></i> AltaUP rule to Pay points to vendors has been installed successfully ! Rule is NOT PUBLIHED. Publish it if you want to use AltaUserPoints for your Vendor points.' , 'message');
				}
				
				$q ="SELECT COUNT(*) FROM #__alpha_userpoints_rules 
				WHERE plugin_function ='plgaup_refundpointsfromvendor' ";
				$db->setQuery($q);
				$is_installed = $db->loadResult();
				if($is_installed<1)
				{
					$q = "INSERT INTO `#__alpha_userpoints_rules` 
					( `rule_name` , `rule_description` , `rule_plugin` , `plugin_function` ,  `access` ,
				 	`published` , `system` , `autoapproved` , `fixedpoints` , `category` , `displaymsg`, `notification`   )
					VALUES ( 'Refund Vendor Points' , 
					'Vendor refund points when an order is canceled or refunded' , 'VMVendor' , 
					'plgaup_refundpointsfromvendor' , '1' ,'0' , '0' , '1' , '0' , 'pu' , '0' ,'0' )";
					$db->setQuery($q);
					$db->execute();
					$app->enqueueMessage( '<i class="icon-ok"></i> AltaUP rule to refund points vendor previously got, when an order is canceled,  has been installed successfully ! Rule is NOT PUBLIHED. Publish it if you want to reward vendors with AltaUserPoints for their sells' , 'message');
				}	
			}				
		}
	}
}