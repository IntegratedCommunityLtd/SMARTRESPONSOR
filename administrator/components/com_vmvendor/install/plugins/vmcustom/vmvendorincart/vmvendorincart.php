<?php
defined('_JEXEC') or 	die( 'Direct Access to ' . basename( __FILE__ ) . ' is not allowed.' ) ;
if (!class_exists('vmCustomPlugin')) require(JPATH_VM_PLUGINS . '/vmcustomplugin.php');

class plgVmCustomVMVendorincart extends vmCustomPlugin {

	function __construct(& $subject, $config) {
		parent::__construct($subject, $config);
		
	}

	// get product param for this plugin on edit
	function plgVmOnProductEdit($field, $product_id, &$row,&$retValue)
	{		
		if ($field->custom_element != $this->_name) return '';

		$html ='<fieldset><legend>VMVendor Vendor name and link in cart</legend>';
		$juri = JURI::base();
		$doc = JFactory::getDocument();
		$doc->addStylesheet( $juri.'components/com_vmvendor/assets/css/fontello.css');
		$db = JFactory::getDBO();
		$q ="SELECT vv.vendor_name , vv.created_by 
		FROM #__virtuemart_vendors vv 
		JOIN #__virtuemart_products vp ON vp.virtuemart_vendor_id = vv.virtuemart_vendor_id 
		WHERE vp.virtuemart_product_id='".$product_id."' ";
		$db->setQuery($q);
		$vendor = $db->loadObject();
		$vendor_name 	= $vendor->vendor_name;
		$vendor_userid 	= $vendor->created_by;
		
		$profileman ='vmv';
		$Itemid = $this->getProfileItemd( $profileman );
		if($profileman =='vmv')
		{
			$profile_url = JRoute::_('index.php?option=com_vmvendor&view=vendorprofile&userid='.$vendor_userid.'&Itemid='.$Itemid)	;
		}
		elseif($profileman =='cb')
		{
			$profile_url = JRoute::_('index.php?option=com_comprofiler&task=userProfile&user='.$vendor_userid.'&Itemid='.$Itemid)	;
		}
		$profile_url = str_replace( 'administrator/','' ,$profile_url );
		$lang 	= JFactory::getLanguage();
		$lang->load( 'com_vmvendor', JPATH_SITE , '', false);
		$html .='<input type="hidden" value="1"  name="customfield_params['.$row.'][profileman]" id="customfield_params['.$row.'][profileman]" readonly> ';
		$html .= ' '.JText::_('COM_VMVENDOR_BYVENDOR').'  ';
		$html .= '<a href="'.$profile_url.'" target="_blank"> ';
		$html .= '<i class="vmv-icon-user"></i> '.$vendor_name;
		$html .= '</a> ';	
		$html .= '</fieldset>';
		$retValue .= $html;
		$row++;
		return true ;

	}

	function plgVmOnDisplayProductFEVM3(&$product,&$group)
	{
		if ($group->custom_element != $this->_name) return '';
		$group->display .= $this->renderByLayout('default',array(&$product,&$group) );
		return true;
	}


	/**
	 * Function for vm3
	 * @see components/com_virtuemart/helpers/vmCustomPlugin::plgVmOnViewCart()
	 * @author Patrick Kohl
	 */
	function plgVmOnViewCart($product,$row,&$html) {
		if (empty($product->productCustom->custom_element) or $product->productCustom->custom_element != $this->_name) return '';
		if (!$plgParam = $this->GetPluginInCart($product)) return '' ;
		//$html .='';
		return true;
	}

	/**
	 * Trigger for VM3
	 * @author Max Milbers
	 * @param $product
	 * @param $productCustom
	 * @param $html
	 * @return bool|string
	 */
	function plgVmOnViewCartVM3(&$product, &$productCustom, &$html )
	{
		if (empty($productCustom->custom_element) or $productCustom->custom_element != $this->_name) return false;
		$profileman = 'vmv';  // replace 'vmv' by 'cb' if you need
		$juri = JURI::base();
		$app = JFactory::getApplication();
		$doc = JFactory::getDocument();
		$doc->addStylesheet( $juri.'components/com_vmvendor/assets/css/fontello.css');
		$doc->addStylesheet( $juri.'plugins/vmcustom/vmvendorincart/css/style.css');
		$db = JFactory::getDBO();
		$q ="SELECT vendor_name , created_by FROM #__virtuemart_vendors 
		WHERE virtuemart_vendor_id='".$product->virtuemart_vendor_id."' ";
		$db->setQuery($q);
		$vendor = $db->loadObject();
		$vendor_name 	= $vendor->vendor_name;
		$vendor_userid 	= $vendor->created_by;

		$Itemid = $this->getProfileItemd( $profileman );
		if($profileman =='vmv')
			$profile_url = JRoute::_('index.php?option=com_vmvendor&view=vendorprofile&userid='.$vendor_userid.'&Itemid='.$Itemid)	;
		elseif($profileman =='cb')
			$profile_url = JRoute::_('index.php?option=com_comprofiler&task=userProfile&user='.$vendor_userid.'&Itemid='.$Itemid)	;
			
		if ($app->isAdmin())
			$profile_url = str_replace( 'administrator/','' ,$profile_url );
		$lang 	= JFactory::getLanguage();
		$lang->load( 'com_vmvendor', JPATH_SITE , '', false);
		
		$html .= '<span class="vmv-vmvendorincart">'.JText::_('COM_VMVENDOR_BYVENDOR').'  ';
		$html .= '<a href="'.$profile_url.'" ';
		if ($app->isAdmin())
			$html .= ' target="_blank" ';	
		$html .= ' title="'.JText::_('COM_VMVENDOR_VENDOR').'" class="hasTooltip"> ';
		$html .= '<i class="vmv-icon-shop"></i> '.$vendor_name;
		$html .= '</a></span>';
		return true;
	}

	function plgVmOnViewCartModuleVM3( &$product, &$productCustom, &$html) {
		return $this->plgVmOnViewCartVM3($product,$productCustom,$html);
	}

	function plgVmDisplayInOrderBEVM3( &$product, &$productCustom, &$html) {
		$this->plgVmOnViewCartVM3($product,$productCustom,$html);
	}

	function plgVmDisplayInOrderFEVM3( &$product, &$productCustom, &$html) {
		$this->plgVmOnViewCartVM3($product,$productCustom,$html);
	}


	/**
	 *
	 * vendor order display BE
	 */
	function plgVmDisplayInOrderBE(&$item, $productCustom, &$html) {
		if(!empty($productCustom)){
			$item->productCustom = $productCustom;
		}
		if (empty($item->productCustom->custom_element) or $item->productCustom->custom_element != $this->_name) return '';
		$this->plgVmOnViewCart($item,$productCustom,$html); //same render as cart
    }


	/**
	 *
	 * shopper order display FE
	 */
	function plgVmDisplayInOrderFE(&$item, $productCustom, &$html) {
		if(!empty($productCustom)){
			$item->productCustom = $productCustom;
		}
		if (empty($item->productCustom->custom_element) or $item->productCustom->custom_element != $this->_name) return '';
		$this->plgVmOnViewCart($item,$productCustom,$html); //same render as cart
    }
	
	
	function getProfileItemd($profileman)
	{
		$app 	= JFactory::getApplication();
		$lang = JFactory::getLanguage();
		$db 	= JFactory::getDBO();
		if($profileman=='vmv')
			$link = 'index.php?option=com_vmvendor&view=vendorprofile';
		if($profileman=='cb')
			$link = 'index.php?option=com_comprofiler&view=userprofile';
		$q = "SELECT `id` FROM `#__menu` WHERE `link`='".$link."' 
		AND `type`='component'  AND ( language ='".$lang->getTag()."' OR language='*') 
		AND published='1'  AND access='1' ";
		$db->setQuery($q);
		return $itemid = $db->loadResult();
	}


	/**
	 * Trigger while storing an object using a plugin to create the plugin internal tables in case
	 *
	 * @author Max Milbers
	 */
	public function plgVmOnStoreInstallPluginTable($psType,$data,$table) {

		/*if($psType!=$this->_psType) return false;
		if(empty($table->custom_element) or $table->custom_element!=$this->_name ){
			return false;
		}
		if(empty($table->is_input)){
			vmInfo('COM_VIRTUEMART_CUSTOM_IS_CART_INPUT_SET');
			$table->is_input = 1;
			$table->store();
		}*/
		//Should the textinput use an own internal variable or store it in the params?
		//Here is no getVmPluginCreateTableSQL defined
 		//return $this->onStoreInstallPluginTable($psType);
	}

	/**
	 * Declares the Parameters of a plugin
	 * @param $data
	 * @return bool
	 */
	function plgVmDeclarePluginParamsCustomVM3(&$data){

		return $this->declarePluginParams('custom', $data);
	}

	function plgVmGetTablePluginParams($psType, $name, $id, &$xParams, &$varsToPush){
		return $this->getTablePluginParams($psType, $name, $id, $xParams, $varsToPush);
	}

	function plgVmSetOnTablePluginParamsCustom($name, $id, &$table,$xParams){
		return $this->setOnTablePluginParams($name, $id, $table,$xParams);
	}

	/**
	 * Custom triggers note by Max Milbers
	 */
	function plgVmOnDisplayEdit($virtuemart_custom_id,&$customPlugin){
		return $this->onDisplayEditBECustom($virtuemart_custom_id,$customPlugin);
	}

	public function plgVmPrepareCartProduct(&$product, &$customfield,$selected,&$modificatorSum){

		if ($customfield->custom_element !==$this->_name) return ;

		//$product->product_name .= 'Ice Saw';
		//vmdebug('plgVmPrepareCartProduct we can modify the product here');

		/*if (!empty($selected['comment'])) {
			if ($customfield->custom_price_by_letter ==1) {
				$charcount = strlen (html_entity_decode ($selected['comment']));
			} else {
				$charcount = 1.0;
			}
			$modificatorSum += $charcount * $customfield->customfield_price ;
		} else {
			$modificatorSum += 0.0;
		}*/

		return true;
	}


	public function plgVmDisplayInOrderCustom(&$html,$item, $param,$productCustom, $row ,$view='FE'){
		$this->plgVmDisplayInOrderCustom($html,$item, $param,$productCustom, $row ,$view);
	}

	public function plgVmCreateOrderLinesCustom(&$html,$item,$productCustom, $row ){
		echo 'ioio';
// 		$this->createOrderLinesCustom($html,$item,$productCustom, $row );
	}
	function plgVmOnSelfCallFE($type,$name,&$render) {
		$render->html = '';
	}

}

// No closing tag