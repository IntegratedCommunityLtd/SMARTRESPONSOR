<?php
/*
 * @component SmartReSponsor
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<style>a.btn{display:none;}</style>
<?php
$user 	= JFactory::getUser();
$db 	= JFactory::getDBO();
$doc 	= JFactory::getDocument();
$juri 	= JURI::base();
//$doc->addStyleSheet($juri.'components/com_vmvendor/assets/css/addproduct.css');
//echo '<link rel="stylesheet" href="'.$juri.'components/com_vmvendor/assets/css/fontello.css">';
$app 	= JFactory::getApplication();
$safepath	= VmConfig::get( 'forSale_path' );
$cparams  = JComponentHelper::getParams('com_vmvendor');
$profileman = $cparams->get('profileman');
$naming 	= $cparams->get('naming', 'username');
$forbidcatids 	= $cparams->get('forbidcatids');
$onlycatids 	= $cparams->get('onlycatids');	
$price_format	= $this->price_format;
$symbol 		= $price_format[7];
$currency_id	= $price_format[0];
$currency 		= $price_format[4];
			
$termsurl 		= $cparams->get('termsurl');
$vmitemid 		= $cparams->get('vmitemid', '103');
$profileitemid 	= $cparams->get('profileitemid', '2');
$multicat	 	= $cparams->get('multicat', 0);
$maxfilesize 	= $cparams->get('maxfilesize', '4000000');//4 000 000 bytes  = 4M
$max_imagefields= $cparams->get('max_imagefields', 4);
if( $this->plan_max_img )
{
  $max_imagefields = $this->plan_max_img;
}
 $max_filefields	= $cparams->get('max_filefields', 4);
if( $this->plan_max_files )
{
	$max_filefields = $this->plan_max_files;
}
	
$maximgside 	= $cparams->get('maximgside', '600');
$thumbqual 		= $cparams->get('thumbqual', 70);
$show_sku 		= $cparams->get('show_sku', 0);
$enable_sdesc 	= $cparams->get('enable_sdesc', 1);
$wysiwyg_prod 		= $cparams->get('wysiwyg_prod', 0);
$enablefiles 	= $cparams->get('enablefiles', 0);
$enableweight 	= $cparams->get('enableweight', 0);
$weightunits 	= $cparams->get('weightunits');
//$allowpublicfiles	= $cparams->get('allowpublicfiles', 0);
$enableprice	= $cparams->get('enableprice', 1);
$enablestock	= $cparams->get('enablestock', 1);
$enablemanufield = $cparams->get('enablemanufield', 1);
$cat_suggest 	= $cparams->get('cat_suggest',1);
$filemandatory 	= $cparams->get('filemandatory', 1);
$imagemandatory	= $cparams->get('imagemandatory', 0);
$allowedexts 	= $cparams->get('allowedexts', 'zip,mp3');
$minimum_price	= $cparams->get('minimum_price');
$sepext 		= explode( "," , $allowedexts );
$countext 		= count($sepext);
$stream			= $cparams->get('strem', 0);
$maxspeed		= $cparams->get('maxspeed', '3000');
$acy_listid		= $cparams->get('acy_listid');

$flickr_autopost	= $cparams->get('flickr_autopost',0);
	
$enable_corecustomfields	= $cparams->get('enable_corecustomfields', 1);
$enable_vm2tags			= $cparams->get('enable_vm2tags', 0);
$tagslimit				= $cparams->get('tagslimit', '5');
$enable_vm2geolocator	= $cparams->get('enable_vm2geolocator', 0);
$enable_embedvideo		= $cparams->get('enable_embedvideo', 0);
$sku = $user->id.".".date('ymd.His');

$virtuemart_vendor_id = $this->virtuemart_vendor_id;


	//echo '<h1>'.JText::_('COM_VMVENDOR_VMVENADD_FORM_TITLE').'</h1>';
	echo '<script type="text/javascript">function validateForm(it){
	var warning = "'.JText::_('COM_VMVENDOR_VMVENADD_JS_FIXTHIS').' \n";
	var same = warning;
	if (it.formcat.value=="0" || it.formcat.value=="")	{
		warning += " * '.JText::_('COM_VMVENDOR_VMVENADD_JS_CATREQUIRED').' \n";
		it.formcat.style.backgroundColor = \'#ff9999\';
	}
	if (it.formname.value=="")	{
		warning += " * '.JText::_('COM_VMVENDOR_VMVENADD_JS_NAMEREQUIRED').' \n";
		it.formname.style.backgroundColor = \'#ff9999\';
	}';
	//if(!$wysiwyg_prod) // not checking description if wysiwyg_prod is on
	if ($enable_sdesc){
		echo 'if (it.form_s_desc.value=="")	{
			warning += " * '.JText::_('COM_VMVENDOR_VMVENADD_JS_DESCREQUIRED').' \n";
			it.form_s_desc.style.backgroundColor = \'#ff9999\';
		}';
	}
	if($wysiwyg_prod == 0){
		echo 'if (it.formdesc.value=="")	{
			warning += " * '.JText::_('COM_VMVENDOR_VMVENADD_JS_DESCREQUIRED').' \n";
			it.formdesc.style.backgroundColor = \'#ff9999\';
		}';
	}
	if($enableprice){
		echo 'if (it.formprice.value=="")	{
			warning += " * '.JText::_('COM_VMVENDOR_VMVENADD_JS_PRICEREQUIRED').' \n";
			it.formprice.style.backgroundColor = \'#ff9999\';
		}
		if (isNaN (it.formprice.value)){
			warning += " * '.JText::_('COM_VMVENDOR_VMVENADD_JS_UNVALIDPRICE').' \n";
			it.formprice.style.backgroundColor = \'#ff9999\';
		}';	
	}
	
	if($enablestock){
		echo ' if (it.formstock.value=="" || isNaN (it.formstock.value))	{
				warning += " * '.JText::_('COM_VMVENDOR_VMVENADD_JS_STOCKREQUIRED').' \n";
				document.getElementById("formstock").style.backgroundColor = \'#ff9999\';
				
			} ';	
		
	}
	
	
	if($enableweight){
		echo ' if (it.formweight.value=="" || isNaN (it.formweight.value)){
				warning += " * '.JText::_('COM_VMVENDOR_VMVENADD_JS_WEIGHTREQUIRED').' \n";
				document.getElementById("formweight").style.backgroundColor = \'#ff9999\';
				
			} ';	
			echo ' if (it.formweightunit.value=="" ){
				warning += " * '.JText::_('COM_VMVENDOR_VMVENADD_JS_WEIGHTUNITREQUIRED').' \n";
				document.getElementById("formweightunit").style.backgroundColor = \'#ff9999\';
				
			} ';	
		
	}
	
	if($enablefiles)
	{ 				 
		for( $i=1 ; $i<= $max_filefields ; $i++ )
		{
			echo ' if( document.getElementById("fileinput'.$i.'") )
			{
				var thisfile = it.file'.$i.';';
			echo 'if( thisfile.value!="" ';
							for ( $j=0 ; $j < $countext ; $j++ )
							{
								
								//if ( $j > 0 )
									echo ' && ';
								echo ' thisfile.value.indexOf(".'.$sepext[$j].'") == -1';
							}
			echo ')
			{ 
				warning += " * '.JText::_('COM_VMVENDOR_VMVENADD_JS_FILEMISSING').' \n"; 
				thisfile.style.backgroundColor = \'#ff9999\';
			}';
			
			echo '}';
		}
		
		
		if( $filemandatory){
			echo 'if (it.file1.value=="")
			{
				warning += " * '.JText::_('COM_VMVENDOR_VMVENADD_JS_FILEMISSING').' \n";
				document.getElementById("file1").style.backgroundColor = \'#ff9999\';
				
			}';	
		}	
	}
	if( $imagemandatory){
		echo 'if (it.image1.value=="")
		{
			warning += " * '.JText::_('COM_VMVENDOR_VMVENADD_JS_IMAGEMISSING').' \n";
			document.getElementById("image1").style.backgroundColor = \'#ff9999\';
			
		}';	
	}
	for( $i=1 ; $i<= $max_imagefields ; $i++)
	{
		echo 'if(document.getElementById("imginput'.$i.'"))
		{
			var thisimage = it.image'.$i.';
			if ( thisimage.value!="" && thisimage.value.indexOf(".jpg") == -1 && thisimage.value.indexOf(".gif") == -1 && thisimage.value.indexOf(".png") == -1
				&& thisimage.value.indexOf(".JPG") == -1 && thisimage.value.indexOf(".GIF") == -1 && thisimage.value.indexOf(".PNG") == -1 )
			{ 
				warning += " * '.JText::_('COM_VMVENDOR_IMAGETYPENOT').' \n";
				thisimage.style.backgroundColor = \'#ff9999\';
				
			}
		}';
	}
	if($enable_vm2geolocator)
	{
		echo ' if( document.getElementById("latitude").value=="" || document.getElementById("longitude").value=="" )
		{		
				warning += " * '.JText::_('COM_VMVENDOR_VMVENADD_FORM_MISSINGCOORDS').' \n";
				document.getElementById("latitude").style.backgroundColor = \'#ff9999\';
				document.getElementById("longitude").style.backgroundColor = \'#ff9999\';			
			}';
	}
	

	echo 'if (it.formterms.checked==false)
		{
			warning += " * '.JText::_('COM_VMVENDOR_VMVENADD_JS_ACCEPTTERMS').' \n";
			document.getElementById("checkboxtd").style.backgroundColor = \'#ff9999\';
			
		}
		if (warning == same)
		{
			it.loading.style.display = "";
			return true;
		}
		else
		{
			alert(warning);
			return false;
		}
	}
	</script>';
			echo '<form name="add" enctype="multipart/form-data" onsubmit="return validateForm(this);" method="post" class="addpublic">';
			echo '<INPUT type="hidden" value="'.$sku.'" name="formsku">';
			echo '<div class="row"><div class="col-xs-12 col-sm-12 com-md-6 col-lg-4"><h4>'.JText::_('COM_VMVENDOR_VMVENADD_FORM_PRODUCTINFO').'</h4></div><div class="col-xs-12 col-sm-12 com-md-6 col-lg-4"><b>*</b>'.JText::_('COM_VMVENDOR_VMVENADD_MANDATORYFIELDS').'</div></div>';
			echo '<div class="row"><div class="col-xs-12 col-sm-12 com-md-6 col-lg-4"><h4><label for="formname">'.JText::_('COM_VMVENDOR_VMVENADD_FORM_NAME').' <b>*</b></label></h4></div>';
			echo '<div class="col-xs-12 col-sm-12 com-md-6 col-lg-6">';
			echo '<input type="text" name="formname" id="formname" class="form-control" required="required" aria-required="true" size="50" onkeyup="this.style.backgroundColor = \'\'" ';
	//echo ' value="'.$get_title.'" ';
			echo '/>';
			echo '</div></div>';
			echo '<div class="row"><div class="col-xs-12 col-sm-12 com-md-6 col-lg-4"><h4>'.JText::_('COM_VMVENDOR_VMVENADD_FORM_PUBLICATION').'</h4></div>';
			echo '<div class="col-xs-12 col-sm-12 com-md-6 col-lg-6">';
				if($this->autopublish)
				{
					echo '<div class="badge badge-warning"><input type="radio" name="formpublished" id="unpublished" value="0"> 
					<label for="unpublished">'.JText::_('JUNPUBLISHED').'</label></div>
							<div class="badge badge-success"><input type="radio" name="formpublished" id="published" value="1" 
							 checked="checked" > <label for="published">'.JText::_('JPUBLISHED').'</label></div>';
				}
				else
				{

					echo '<input type="hidden" name="formpublished" value="0" />';
					echo '<div class="badge badge-warning hasTooltip" title="'.JText::_('COM_VMVENDOR_VMVENADD_FORM_TOBEMODERATED').'"><i class="vmv-icon-clock"></i> '.JText::_('JUNPUBLISHED').'</div>';
				}
				echo '</div></div>';
			echo '<div class="row"><div class="col-xs-12 col-sm-12 com-md-6 col-lg-4">';
			if($multicat)
            {
				echo JText::_('COM_VMVENDOR_VMVENADD_FORM_CATS');
            }
            else
            {
				echo JText::_('COM_VMVENDOR_VMVENADD_FORM_CAT');
            }
			echo '</div>';
	echo '<div class="col-xs-12 col-sm-12 com-md-6 col-lg-6">';//////////////////////// Category select field

	echo '<div class="form-group">';
	if($multicat)
	{
		echo '<select id="formcat" class="form-control vm-chzn-select" required="required" aria-required="true" ';
		echo ' name="formcat[]" multiple="multiple" ';
		
		echo 'onchange="this.style.backgroundColor = \'\'"><option value="0">'.JText::_('COM_VMVENDOR_VMVENADD_FORM_CHOOSECAT').'</option>';
		function traverse_tree_down( $class , $category_id , $level , $forbidcatids , $onlycatids , $virtuemart_vendor_id)
		{
				$db 						= JFactory::getDBO();	
				$banned_cats = explode(',',$forbidcatids);
				$prefered_cats = explode(',',$onlycatids);
				$level++;
				$q = "SELECT * FROM `#__virtuemart_categories_".VMLANG."` AS vmcl, `#__virtuemart_category_categories` AS vmcc,  `#__virtuemart_categories` AS vmc
					WHERE vmcc.`category_parent_id` = '".$category_id."' 
					AND vmcl.`virtuemart_category_id` = `category_child_id` 
					AND vmc.`virtuemart_category_id` = vmcl.`virtuemart_category_id` 
					AND vmc.`published`='1' 
					AND (vmc.`virtuemart_vendor_id`='1' OR vmc.`virtuemart_vendor_id` ='".$virtuemart_vendor_id."' OR vmc.`shared`='1' ) ";
				foreach($banned_cats as $banned_cat)
                {
					$q .= "AND vmc.`virtuemart_category_id` !='".$banned_cat."' ";
				}
				
				if($onlycatids !='')
					$q .= " AND vmc.`virtuemart_category_id` IN (". implode(',', $prefered_cats ) .") ";

					
				$q .= "	ORDER BY vmc.`ordering` ASC ";
				$db->setQuery($q);
				$cats = $db->loadObjectList();
				foreach($cats as $cat){
					echo '<option value="'.$cat->virtuemart_category_id.'">';
					for ($i=1; $i<$level; $i++){
						echo ' . ';
					}
					if($level >1)
                    {
							echo ' |_ ';
                    }
					echo JText::_($cat->category_name).'</option>';
					traverse_tree_down($class, $cat->category_child_id, $level,$forbidcatids , $onlycatids , $virtuemart_vendor_id);
				}
			}
			$traverse = traverse_tree_down('',0,0,$forbidcatids , $onlycatids , $virtuemart_vendor_id);
		echo '</select>';
	}
	else // multi steps ebay style cateogry selector	
	{
		function traverse_tree_down( $class , $category_id , $level , $forbidcatids , $onlycatids , $virtuemart_vendor_id , $toplevel)
		{
				$db 			= JFactory::getDBO();	
				$banned_cats = explode(',',$forbidcatids);
				$prefered_cats = explode(',',$onlycatids);
				$level++;
				$toplevel = 0;
				$q = "SELECT * FROM `#__virtuemart_categories_".VMLANG."` AS vmcl, `#__virtuemart_category_categories` AS vmcc,  `#__virtuemart_categories` AS vmc
					WHERE vmcc.`category_parent_id` = '".$category_id."' 
					AND vmcl.`virtuemart_category_id` = `category_child_id` 
					AND vmc.`virtuemart_category_id` = vmcl.`virtuemart_category_id` 
					AND vmc.`published`='1' 
					AND (vmc.`virtuemart_vendor_id`='1' OR vmc.`virtuemart_vendor_id` ='".$virtuemart_vendor_id."' OR vmc.`shared`='1' ) ";
				foreach($banned_cats as $banned_cat)
				{
					$q .= "AND vmc.`virtuemart_category_id` !='".$banned_cat."' ";
				}
				if($onlycatids !='')
					$q .= " AND vmc.`virtuemart_category_id` IN (". implode(',', $prefered_cats ) .") ";
					
				$q .= "	ORDER BY vmc.`ordering` ASC ";
				$db->setQuery($q);
				$cats = $db->loadObjectList();
				if(count($cats)>0)
				{
					$c_js = '';
					echo '<div class="form-group">';
					echo ' <select id="selectid'.$category_id.'" class="form-control catrank vm-chzn-select'.$level.'" required="required" aria-required="true" ';
					if($level==1)
					{
						$c_js = '';
						echo ' name="formcat" ';
					}
					elseif($level>1)
						echo ' style="display:none" ';
          	echo 'onchange="this.style.backgroundColor = \'\'" >';
					//if(count($cats)>1)
						echo '<option value="0">'.JText::_('COM_VMVENDOR_VMVENADD_FORM_CHOOSECAT').'</option>';	
					foreach($cats as $cat)
					{
						echo '<option value="'.$cat->virtuemart_category_id.'" ';
						echo '> ';
						for($iii = 1;$iii< $level; $iii++)
						{
							echo ' . ';
						}
						if($level>1)
							echo ' |_ ';
						echo JText::_($cat->category_name).'</option>';
					}
					echo '</select>';
					echo '</div>';
				}
				static $toplevel = 0;
				if($level>$toplevel)
					$toplevel = $level;
        foreach($cats as $cat)
					traverse_tree_down($class, $cat->category_child_id, $level,$forbidcatids , $onlycatids , $virtuemart_vendor_id ,$toplevel);
				return $toplevel;
			}
			
			$toplevel = traverse_tree_down('',0,0,$forbidcatids, $onlycatids , $virtuemart_vendor_id ,0 );
			$c_js = '';
			for($i=1;$i<$toplevel - 1 ;$i++)
			{
				if($i==1){
					$c_js .= 'jQuery(".catrank1").change(function () {
  								var str = "";
  								str = jQuery(this).val();';
					for($j=2;$j<$toplevel;$j++)				
					{	
  					if($j!=$i)
							{
									$c_js .= 'jQuery(".catrank'.$j.'").hide();
								 jQuery(".catrank'. $j .'").removeAttr( "name" );';
							}
					}
  				$c_js .= 'if( jQuery("#selectid"+str).length>0 && str!="")
					{
						jQuery(this).removeAttr( "name" );
						jQuery("#selectid"+str).show();
						jQuery("#selectid"+str).attr("name", "formcat");
					}
					else
										jQuery(this).attr("name", "formcat");
				
					});';
				}
				else
				{
					$c_js .= 'jQuery(".catrank'.$i.'").change(function () {
							var str = "";
							str = jQuery(this).val();';
							for($k=3;$k<$toplevel;$k++)				
							{
								if($k!=$i)
								{
										$c_js .= 'jQuery(".catrank'.$k.'").hide();
											jQuery(".catrank'. $k .'").removeAttr( "name" );';
								}
							}
							$c_js .= 'if( jQuery("#selectid"+str).length>0 && str!=0 && str!="")
									{
										jQuery(this).removeAttr( "name" );
										jQuery("#selectid" + str).show();
										jQuery("#selectid"+str).attr("name", "formcat");
									}
									else
										jQuery(this).attr("name", "formcat");
						});';	
				}	
			}			
		echo '<script>'.$c_js.'</script>';	
	}

///////////////////////////////// end Category select field
	if($cat_suggest==1 OR $cat_suggest==2) // email only or inserted unpublished
    {
		$suggest_message = JText::_('COM_VMVENDOR_CATSUGGEST_CATSUGGESTBUTTON');
    }
    elseif($cat_suggest==3) // inserted published
	{
        $suggest_message = JText::_('COM_VMVENDOR_CATSUGGEST_CATADDBUTTON');
    }
	
	 if($cat_suggest>0)
	 {
		JHTML::_('behavior.modal');
			
			
			
		$name = "CatsuggestModal";
		echo '<div><a href="#modal-' . $name.'" data-toggle="modal" class="btn btn-mini hasTooltip" title="'.$suggest_message.'"><i class="vmv-icon-plus" ></i></a></div>';
		$params = array();
		$params['title'] = $suggest_message;
		$params['url']  = 'index.php?option=com_vmvendor&view=catsuggest&tmpl=component';
		$params['height'] = 700;
		$params['width'] = "100%";
		echo JHtml::_('bootstrap.renderModal', 'modal-' . $name, $params);
 
	 }
	echo '</div></div></div>';

	if($show_sku){
		echo '<div class="row"><div class="col-xs-12 col-sm-12 com-md-6 col-lg-4"><h4>'.JText::_('COM_VMVENDOR_VMVENADD_FORM_SKU').'</h4></div>';
		echo '<div class="col-xs-12 col-sm-12 com-md-6 col-lg-6">'.$sku.'</div></div>';
	}
	
	echo '<div class="row"><div class="col-xs-12 col-sm-12 com-md-6 col-lg-4"><h4><label for="formmanufacturer">'.JText::_('COM_VMVENDOR_VMVENADD_MANUFACTURER').'</label></h4></div>';
    echo '<div class="col-xs-12 col-sm-12 com-md-6 col-lg-6"><div class="form-group><select id="formmanufacturer"></select>';
	echo '<select id="formmanufacturer" name="formmanufacturer" class="form-control vm-chzn-select"><option value="0">'.JText::_('COM_VMVENDOR_VMVENADD_FORM_CHOOSEMANUFACTURER').'</option>';
				$manufacturers = VmvendorModelAddproduct::getManufacturers();
		foreach($manufacturers as $manufacturer)
		{
		echo '<option value="'.$manufacturer->virtuemart_manufacturer_id.'">'.$manufacturer->mf_name.'</option>';	
		}
		echo '</select></div></div></div>';	
	

	if($enable_sdesc)
	{
				echo '<div class="row"><div class="col-xs-12 col-sm-12 com-md-6 col-lg-4"><label for="form_s_desc"><h4>'.JText::_('COM_VMVENDOR_VMVENADD_FORM_SDESC').'<b>*</b></h4><br /><B><span id=myCounter>255</span></B> '.JText::_('COM_VMVENDOR_VMVENADD_FORM_CHARSREMAINING').'</label></div>';
				echo '<div class="col-xs-12 col-sm-12 com-md-6 col-lg-6"><div class="form-group"><textarea name="form_s_desc" id="form_s_desc" class="form-control" cols="45" rows="5" onkeyup="this.style.backgroundColor = \'\';return taCount(this,\'myCounter\');"  onKeyPress="return taLimit(this)"  ></textarea>';
				echo '</div></div></div>';	
				$counterscript ='maxL=255;
				var bName = navigator.appName;
				function taLimit(taObj) {
					if (taObj.value.length==maxL) return false;
					return true;
				}
				
				function taCount(taObj,Cnt) { 
					objCnt=createObject(Cnt);
					objVal=taObj.value;
					if (objVal.length>maxL) objVal=objVal.substring(0,maxL);
					if (objCnt) {
						if(bName == "Netscape"){	
							objCnt.textContent=maxL-objVal.length;}
						else{objCnt.innerText=maxL-objVal.length;}
					}
					return true;
				}
				
				function createObject(objId) {
					if (document.getElementById) return document.getElementById(objId);
					else if (document.layers) return eval("document." + objId);
					else if (document.all) return eval("document.all." + objId);
					else return eval("document." + objId);
				}';
				$doc->addScriptDeclaration($counterscript);
				
			

	}
			
	echo '<div class="row"><div class="col-xs-12 col-sm-12 com-md-6 col-lg-4"><label for="formdesc" ><h4>'.JText::_('COM_VMVENDOR_VMVENADD_FORM_DESC').' <b>*</b></h4></label></div>';
	if($wysiwyg_prod){
				$text_default = '<p><span style="font-size: 12pt;"><strong>Опишите по возможности, какую проблему решает ваш проект:</strong></span></p>
<p><span style="font-size: 12pt;">здесь текст описания проблемы, которую решает ваш проект.</span></p>
<p><span style="font-size: 12pt;"> </span></p>
<p><span style="font-size: 12pt;"><strong>Опишите по возможности аудиторию, для которой этот проект может быть ценным, интересным, важным (включая георгафию проекта):<br /></strong></span></p>
<p><span style="font-size: 12pt;">здесь текст описания аудитории, для которой этот проект может быть ценным, интересным, важным.</span></p>
<p><span style="font-size: 12pt;"> </span></p>
<p><span style="font-size: 12pt;"><strong>Опишите по возможности бюджет (смету) проекта, предоставьте максимально точную информацию (используйте при этом специальное поле загрузки дополнительных документов):<br /></strong></span></p>
<p><span style="font-size: 12pt;">здесь текст описания бюджета проекта.</span></p>
<p><span style="font-size: 12pt;"> </span></p>
<p><span style="font-size: 12pt;"><strong>СПОНСОРСКИЕ ПАКЕТЫ</strong></span></p>
<hr />
<p><span style="font-size: 12pt;"><strong>Опишите по возможности, что вы предлагаете для Генерального спонсора:</strong></span></p>
<p><span style="font-size: 12pt;">здесь текст описания спонсорского пакета для Генерального спонсора.</span></p>
<p><span style="font-size: 12pt;"> </span></p>
<p><span style="font-size: 12pt;"><strong>Опишите по возможности, что вы предлагаете Информационного спонсора:</strong></span></p>
<p><span style="font-size: 12pt;">здесь текст описания спонсорского пакета для Информационного спонсора.</span></p>
<p><span style="font-size: 12pt;"> </span></p>
<p><span style="font-size: 12pt;"><strong>Опишите по возможности, что вы предлагаете всем спонсорам вашего проекта:</strong></span></p>
<p><span style="font-size: 12pt;">здесь текст описания спонсорского пакета для спонсоров вашего проекта.</span></p>
<p><span style="font-size: 12pt;"> </span></p>
<p><span style="font-size: 12pt;"><strong>Опишите по возможности, что вы отправляете всем спонсорам в качестве благодарности, сразу после любой оплаты (это может быть сувенир, открытка или любая другая материальная вещь):</strong></span></p>
<p><span style="font-size: 12pt;">здесь текст описания той благодарности, которую вы отправите каждому своему спонсору, как только будет совершен платеж.</span></p>
<p><span style="font-size: 12pt;"> </span></p>
<p><span style="font-size: 12pt;"><strong>ИНВЕСТИЦИОННОЕ ПРЕДЛОЖЕНИЕ<br /></strong></span></p>
<hr />
<p><span style="font-size: 12pt;"><strong>Если ваш проект коммерческий опишите по возможности, что вы предлагаете инвесторам, которые могут проинвестировать средства<br /></strong></span></p>
<p><span style="font-size: 12pt;">здесь текст описания инвестиционного предложения.</span></p>
<p><span style="font-size: 12pt;"> </span></p>';
				//$editor_w = $this->params->get('editor_h', '300');
				//$editor_r = $this->params->get('editor_r', '5');
				//$editor_c= $this->params->get('editor_c', '30');
				jimport( 'joomla.html.editor' );
				$editor = JFactory::getEditor();
				$editorhtml = $editor->display('formdesc', ''.$text_default .'', '100%;', '200', '', '', false);
				// ; required after %
				//$editorhtml = JEditor::display( 'editor', '' , 'description', '100%;', '150', '5', '30' );
				echo '<div class="col-xs-12 col-sm-12 com-md-6 col-lg-6">'.$editorhtml.'</div></div>';
	}
	else{
				//$get_description = '<p>Test</p>';
				echo '<div class="col-xs-12 col-sm-12 com-md-6 col-lg-6"><textarea name="formdesc" id="formdesc" class="form-control" required="required" cols="45" rows="5" onkeyup="this.style.backgroundColor = \'\'" style="width:100%;height:70px;">';
				echo $get_description;
				echo '</textarea></div></div>';
	}
	if($enableweight){
				echo '<div class="row"><div class="col-xs-12 col-sm-12 com-md-6 col-lg-4">'.JText::_('COM_VMVENDOR_VMVENADD_FORM_WEIGHT').' <b>*</b></div>';
				echo '<div class="col-xs-12 col-sm-12 com-md-6 col-lg-6"><div class="form-group">';
				echo '<input type="text" name="formweight" id="formweight" class="form-control" onkeyup="this.style.backgroundColor = \'\';" />';
				echo '</div>';
				if(count($weightunits)<1){
					echo ''.JText::_('COM_VMVENDOR_VMVENADD_FORM_WEIGHT_UNITNOTDEFINED');
					echo '<input type="hidden" id="formweightunit" class="form-control" name="formweightunit" value="NA" />';
				}
				elseif(count($weightunits)==1){
					echo JText::_('COM_VMVENDOR_VMVENADD_FORM_WEIGHT_'.$weightunits[0]);
					echo '<input type="hidden" id="formweightunit" class="form-control" name="formweightunit" value="'.$weightunits[0].'" />';	
				}
				elseif(count($weightunits)>1){
					echo ' <div class="form-group">';
					echo '<select id="formweightunit" name="formweightunit" class="form-control vm-chzn-select" onchange="this.style.backgroundColor = \'\';" >';
					echo '<option value="" >'.JText::_('COM_VMVENDOR_VMVENADD_FORM_WEIGHT_SELECTUNIT').'</option>';
					foreach($weightunits as $weightunit){
						echo '<option value="'.$weightunit.'" >';
						echo JText::_('COM_VMVENDOR_VMVENADD_FORM_WEIGHT_'.$weightunit);
						echo '</option>';
					}
					echo '</select>';
					echo '</div>';
				}
				echo '</select>';
				echo '</div>';
	}
			
			
	if($enablestock){
				echo '<div class="row"><div class="col-xs-12 col-sm-12 com-md-6 col-lg-4">'.JText::_('COM_VMVENDOR_VMVENADD_FORM_STOCK').' <b>*</b></div>';
				echo '<div class="col-xs-12 col-sm-12 com-md-6 col-lg-6"><input type="text" size="6" name="formstock" id="formstock" class="form-control form-inline" onkeyup="this.style.backgroundColor = \'\';this.value=this.value.replace(/\D/,\'\')" /></div></div>';
	}
			
	if($enableprice){
				echo '<div class="row"><div class="col-xs-12 col-sm-12 com-md-6 col-lg-4">'.JText::_('COM_VMVENDOR_VMVENADD_FORM_PRICE').' <b>*</b></div>';
				echo '<div class="col-xs-12 col-sm-12 com-md-6 col-lg-6">
				<div class="form-group"><input type="text" name="formprice" id="formprice" class="form-control" onkeyup="this.style.backgroundColor = \'\'" /></div>
				<div class="form-group"><label for="formprice">'.$symbol.' ( '.$currency.' )</label><INPUT type="hidden" value="'.$currency.'" name="currency">
				</div></div></div>';
	}

	echo '<div class="row">';
//	echo '<div class="row"><div class="col-xs-12 col-sm-12 com-md-6 col-lg-4"><h4>'.JText::_('COM_VMVENDOR_VMVENADD_FORM_FILES').'</h4></div>';

	if($enablefiles)
	{
		echo '<div class="col-xs-12 col-sm-6 com-md-6 col-lg-4">';
		// get upload_max_filesize from php.ini and 
		$umfs = ini_get('upload_max_filesize');
		// get it in bytes...
		$umfs = trim($umfs);
		$last = strtolower($umfs[strlen($umfs)-1]);
		switch($last)
		{
    			case 'g':
					$umfs *= 1024;
					case 'm':
					$umfs *= 1024;
					case 'k':
					$umfs *= 1024;
  	}
		//if smaller than $maxfilesize replace $maxfilesize
		if ($umfs < $maxfilesize)
        {
			$maxfilesize = $umfs;
        }
		$maxfilesizemega = $maxfilesize/(1024*1024);
		$maxfilesizemega = round($maxfilesizemega,1)."MB";
			
		echo '<h4>'.JText::_('COM_VMVENDOR_VMVENADD_FORM_FILE').'';
		
		if($filemandatory)
		{
          echo '<b>*</b>';
        }
          echo '</h4></div>';
		echo '<div class="col-xs-12 col-sm-6 com-md-6 col-lg-6"><i class="vmv-icon-info-sign hasTooltip" title="('.$allowedexts.') '.JText::_('COM_VMVENDOR_VMVENADD_FORM_MAX').': '.$maxfilesizemega.'"></i> <div class="badge pull-right">'.JText::_('COM_VMVENDOR_PLAN_MAX').': '.$max_filefields.'</div>';
		echo '</div>';
		echo '</div>';
				
		
		
				
		echo "<input type='hidden' name='MAX_FILE_SIZE' value='".$maxfilesize."' />";
		$file_ajax = "
				//var jQuery = jQuery.noConflict();
				jQuery(document).ready(function() {
      jQuery('#fileAdd').click(function() {
        var num   = jQuery('.fileclonedInput').length;
        var newNum = new Number(num + 1);
 
        var newElem = jQuery('#fileinput' + num).clone().attr('id', 'fileinput' + newNum);
 
        newElem.children(':first').attr('id', 'file' + newNum).attr('name', 'file' + newNum);
        jQuery('#fileinput' + num).after(newElem);
        jQuery('#fileDel').attr('disabled',false);
 
        if (newNum == ".$max_filefields .")
          jQuery('#fileAdd').attr('disabled',true);
      });
 
      jQuery('#fileDel').click(function() {
        var num = jQuery('.fileclonedInput').length;
 
        jQuery('#fileinput' + num).remove();
        jQuery('#fileAdd').attr('disabled',false);
 
        if (num-1 == 1)
          jQuery('#fileDel').attr('disabled',true);
      });
 
      jQuery('#fileDel').attr('disabled',true);
    });";
		$doc->addScriptDeclaration($file_ajax);
		echo '<div style="display:none;">'; //trick to have hidden image fields
		for( $i=2; $i<=$max_filefields; $i++){ // fout la merde dans la validation javascript
				//echo '<input type="file" id="file'.$i.'" name="file'.$i.'" />';	
		}
		echo '</div>';

		echo '<div class="row"><div id="fileinput1" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 fileclonedInput">';
		echo '<div class="input-group">';		
		echo ' <input type="file" name="file1" id="file1" class="form-control nomargin" onchange="this.style.backgroundColor = \'\'" />';
		if($max_filefields>1){		
		echo '<span class="input-group-btn">';
		echo '		
				<button type="button" id="fileAdd" class="btn btn-success"/><span class="glyphicon glyphicon-plus"></span></button>
				<button type="button" id="fileDel" class="btn btn-danger"/><span class="glyphicon glyphicon-minus"></span></button>';	
		echo '</span>';
		}		
		echo '</div>';
		echo '</div>';
		echo '</div>';
	}
	
	echo '<div class="row"><div class="col-xs-12 col-sm-6 com-md-6 col-lg-4">';
	echo '<h4>'.JText::_('COM_VMVENDOR_VMVENADD_FORM_IMAGE').'';
	if($imagemandatory)
  {
		echo '<b>*</b>';
  }
		echo '</h4></div>';			
	echo '<div class="col-xs-12 col-sm-6 com-md-6 col-lg-6"><i class="vmv-icon-info-sign hasTooltip" title="(png,gif,jpg) <h4>'.JText::_('COM_VMVENDOR_VMVENADD_FORM_MAXSIDE').' '.$maximgside.'px"></h4></i>';
	echo ' <div class="badge pull-right">'.JText::_('COM_VMVENDOR_PLAN_MAX').': '.$max_imagefields.'</div>';
	echo '</div></div>';
	echo '<div class="row">';
			
	$img_ajax = "jQuery(document).ready(function() {
					jQuery('#imgAdd').click(function() {
						var num   = jQuery('.imgclonedInput').length;
						var newNum = new Number(num + 1);
	
						var newElem = jQuery('#imginput' + num).clone().attr('id', 'imginput' + newNum);
	 
						newElem.children(':first').attr('id', 'image' + newNum).attr('name', 'image' + newNum);
						jQuery('#imginput' + num).after(newElem);
						jQuery('#imgDel').attr('disabled',false);
	 
						if (newNum == ".$max_imagefields .")
							jQuery('#imgAdd').attr('disabled',true);
					});
	 
					jQuery('#imgDel').click(function() {
						var num = jQuery('.imgclonedInput').length;
	 
						jQuery('#imginput' + num).remove();
						jQuery('#imgAdd').attr('disabled',false);
	 
						if (num-1 == 1)
							jQuery('#imgDel').attr('disabled',true);
					});
					jQuery('#imgDel').attr('disabled',true);
    		});";
		$doc->addScriptDeclaration($img_ajax);
		echo '<div style="display:none;">'; //trick to have hidden image fields
		for( $i=1; $i<=$max_imagefields; $i++){ // fout la merde dans la validation javascript
				//echo '<input type="file" id="image'.$i.'" name="image'.$i.'" />';	
		}
		echo '</div>';

		echo '<div id="imginput1" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 imgclonedInput">';
		echo '<div class="input-group">';		
		echo ' <input type="file" name="image1" id="image1" class="form-control nomargin" onchange="this.style.backgroundColor = \'\'" /> ';
		if($max_imagefields>1){
		echo '<span class="input-group-btn">';
		echo '
				<button type="button" id="imgAdd" class="btn btn-success"/><span class="glyphicon glyphicon-plus"></span></button>
				<button type="button" id="imgDel" class="btn btn-danger"/><span class="glyphicon glyphicon-minus"></span></button>';
		}		
		echo '</span>';			
		echo '</div>';			
		echo '</div>';			
	
	//}
		
	echo '</div>';
			////////////////////////////////////// 3rd party VM custom plugins integration	
	if($enable_vm2tags OR $enable_vm2geolocator OR $enable_embedvideo OR $enable_corecustomfields){

//		echo '<div class="row"><div class="col-xs-12 col-sm-12 com-md-6 col-lg-4">';
//		echo '<h4>'.JText::_('COM_VMVENDOR_VMVENADD_CUSTOMFIELDS').'</h4>';
//		echo '</div>';
//		echo '<div class="col-xs-12 col-sm-12 com-md-6 col-lg-4"></div></div>';	
	}
	
	if($enable_vm2tags)
  {
		echo '<div class="row"><div class="col-xs-12 col-sm-12 com-md-6 col-lg-4"><h4><label for="formtags" ><i class="vmv-icon-tags "></i> '.JText::_('COM_VMVENDOR_VMVENADD_FORM_TAGS').'</label></h4> 
		<i class="vmv-icon-info-sign hasTooltip" title="'.JText::_('COM_VMVENDOR_VMVENADD_FORM_TAGSLIMIT').': '.$tagslimit.'::'.JText::_('COM_VMVENDOR_VMVENADD_FORM_TAGSCOMASEPARATED').'"></i></div>';	
		echo '<div class="col-xs-12 col-sm-12 com-md-6 col-lg-6">';
		if( file_exists(JPATH_BASE.'/plugins/vmcustom/vm2tags/vm2tags.php') )
		{
			$doc->addScript($juri.'components/com_vmvendor/assets/js/jquery.tagsinput.min.js');
			$doc->addScript('//ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/jquery-ui.min.js');			
			$doc->addStylesheet($juri.'components/com_vmvendor/assets/css/jquery.tagsinput.css');
			$doc->addStylesheet('//ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/themes/start/jquery-ui.css');
			$tag_script = "function onAddTag(tag) {
										alert(\"Added a tag: \" + tag);
									}
									function onRemoveTag(tag) {
										alert(\"Removed a tag: \" + tag);
									}
									
									function onChangeTag(input,tag) {
										alert(\"Changed a tag: \" + tag);
									}
									
									jQuery(function() {
										jQuery('#formtags').tagsInput({width:'auto'});
								// Uncomment this line to see the callback functions in action
								//			jQuery('input.tags').tagsInput({onAddTag:onAddTag,onRemoveTag:onRemoveTag,onChange: onChangeTag});		
								
								// Uncomment this line to see an input with no interface for adding new tags.
								//			jQuery('input.tags').tagsInput({interactive:false});
			});";
			
			$doc->addScriptDeclaration($tag_script);
			
			echo '<input type="text" size="50" name="formtags" id="formtags" class="tags" />';	
		}
		else
		{
			echo '<p class="well bg-danger"><i class="vmv-icon-cancel"></i> VM2tags plugin and component missing. 
		
		Disable the option in <a target="_blank" href="administrator/index.php?option=com_config&view=component&component=com_vmvendor">VMVendor settings</a> or';
		
		/*<a class="btn btn-primary" target="_blank"
		href="#"></a>*/
		
		echo '</p>';
		}	
		echo '</div></div>';
	}
	
	if($enable_vm2geolocator)
	{// get the vm2geolocator custom plugin parameters
		
		echo '<div class="row"><div class="col-xs-12 col-sm-12 com-md-6 col-lg-4">';
		echo '<h4>'.JText::_('COM_VMVENDOR_VMVENADD_FORM_LOCATION').'</h4> 
		<i class="vmv-icon-location hasTooltip" title="'.JText::_('COM_VMVENDOR_VMVENADD_FORM_LOCATIONDESC').'"></i>';
		echo '</div>';
		echo '<div class="col-xs-12 col-sm-12 com-md-6 col-lg-6">';
		if( file_exists(JPATH_BASE.'/plugins/vmcustom/vm2geolocator/vm2geolocator.php') )
		{

			$q = "SELECT `custom_params` FROM `#__virtuemart_customs` WHERE `custom_element`='vm2geolocator' AND `published`='1' ";
			$db->setQuery($q);
		$vm2geo_params= $db->loadResult();
		function get_between($input, $start, $end)
		{ 
		 $substr = substr($input, strlen($start)+strpos($input, $start), (strlen($input) - strpos($input, $end))*(-1)); 
		 return $substr; 
		}
		$fe_map_width = '100%';
		$be_lat = get_between($vm2geo_params, 'default_lat="', '"|default_lng');
		if(!$be_lat) $be_lat = '0';
		$be_lng = get_between($vm2geo_params, 'default_lng="', '"|default_zoom');
		if(!$be_lng) $be_lng = '0';
		$be_zoom = get_between($vm2geo_params, 'default_zoom="', '"|default_maptype');
		if(!$be_zoom) $be_zoom = '1';
		$be_maptype	= get_between($vm2geo_params, 'default_maptype="', '"|stylez');
		if(!$be_maptype) $be_maptype = 'ROADMAP';
		$doc->addScript( "//maps.googleapis.com/maps/api/js?sensor=true&libraries=places");
				
		$mapscript ="function add_Event(obj_, evType_, fn_){ 
						if (obj_.addEventListener)
							obj_.addEventListener(evType_, fn_, false); 
						else
							obj_.attachEvent('on'+evType_, fn_); 
					};
					function initializemap(){
						directionsDisplay = new google.maps.DirectionsRenderer();
						var latlng = new google.maps.LatLng(".$be_lat.",".$be_lng.");
						var myOptions = {
							zoom: ".$be_zoom.",
							center: latlng,
							mapTypeId: google.maps.MapTypeId.".$be_maptype.",
							scrollwheel: false,
							navigationControl: true,
							scaleControl: true,
							mapTypeControl: true,
							overviewMapControl:true,
							streetViewControl: true
						}
						var map = new google.maps.Map(document.getElementById('map_canvas'), myOptions);					
						var input = document.getElementById('searchTextField');
						var autocomplete = new google.maps.places.Autocomplete(input);
							autocomplete.bindTo('bounds', map);
							var place_infowindow = new google.maps.InfoWindow();
							var place_marker = new google.maps.Marker({
							 map: map
							});
							google.maps.event.addListener(autocomplete, 'place_changed', function() {
							  place_infowindow.close();
							 var place = autocomplete.getPlace();
							 if (place.geometry.viewport) {
								map.fitBounds(place.geometry.viewport);
							 } else {
								map.setCenter(place.geometry.location);
								map.setZoom(17); // Why 17? Because it looks good.
							 }				
							 
							});
							var marker = new google.maps.Marker({						
								map: map,
								clickable: false,					
								title:'".JText::_('VMCUSTOM_VM2GEOLOCATOR_PRODUCTLOCATION')."'
							});
							google.maps.event.addListener(map, 'click', function(event) {
								place_infowindow.close();
								var PointTmp2 = event.latLng;
								marker.setPosition(PointTmp2);
								document.getElementById('latitude').value = PointTmp2.lat();
								document.getElementById('longitude').value = PointTmp2.lng();
								document.getElementById('latitude').style.backgroundColor = '';
								document.getElementById('longitude').style.backgroundColor = '';
							});	
							google.maps.event.addListener(map, 'zoom_changed', function(event) {
						  	document.getElementById('zoom').value = map.getZoom();
						 	});
							google.maps.event.addListener(map, 'maptypeid_changed', function(event) {
								var mapTypeID = map.getMapTypeId();
								document.getElementById('maptype').value = mapTypeID.toUpperCase();
						 	});
					}
						function initgmap() {
   					//if (arguments.callee.done) GUnload();
						document.getElementById('latitude').value ='';
						document.getElementById('longitude').value ='';
							arguments.callee.done = true;
							initializemap();
						};
						add_Event(window, 'load', initgmap);";
			$doc->addScriptDeclaration($mapscript);

			echo '<div id="map_canvas" style="height:300px;">#dev<div>
				<div style="clear:both;position:absolute;"></div>';
			echo '</div></div>';
			//echo '<tr class="geolocator" style="background-color:#f7f7f7;">';
			echo '<div class="form-inline">';
			echo '<div style="padding-bottom:3px;"><input id="searchTextField" type="text" size="50" placeholder="'.JText::_('COM_VMVENDOR_VMVENADD_FORM_PLACE_SEARCH').'" class="form-control "></div>';
			
			echo '<div class=" form-group col-lg-3">
			<label class="sr-only" for="latitude">latitude</label>
			<input title="'.JText::_('COM_VMVENDOR_VMVENADD_FORM_LAT').'" type="text" value="" size="10" name="latitude" id="latitude" class="form-control" readonly> </div>';
			
			echo '<div class="form-group col-lg-3">
			<label class="sr-only" for="longitude">longitude</label>
			<input title="'.JText::_('COM_VMVENDOR_VMVENADD_FORM_LNG').'" type="text" value="" size="10" name="longitude" id="longitude" class="form-control" readonly> </div>';
			
			echo '<div class="form-group col-lg-2"><label class="sr-only" for="zoom">zoom</label>
			<input title="'.JText::_('COM_VMVENDOR_VMVENADD_FORM_ZOOM').'" type="text" value="'.$be_zoom.'" size="2" name="zoom" id="zoom" class="form-control" readonly> </div>';
			echo '<div class="form-group col-lg-3"><input title="'.JText::_('COM_VMVENDOR_VMVENADD_FORM_MAPTYPE').'" type="text" value="'.$be_maptype.'" size="10" name="maptype" class="form-control" id="maptype" readonly> </div>';
			echo '<div class="form-group"><a href="javascript:initgmap();" class="btn btn-sm btn-default" hasTooltipt" title="'.JText::_('COM_VMVENDOR_VMVENADD_FORM_RESET').'"> <i class="vmv-icon-refresh"></i></a></div>';
			echo '</div>';
		}
		else
		{
			echo '<p class="well bg-danger"><i class="vmv-icon-cancel"></i>plugin missing. 
		
		Disable the option in <a target="_blank" href=""></a> or';
		
		/*<a class="btn btn-primary" target="_blank"
		href="">
		Download</a>*/
		
		echo '</p>';
		}	
		echo '</div></div>';
	}

	if($enable_embedvideo){
		foreach($this->getEmbedvideoFields as $vid_field)
		{
			echo '<div class="row"><div class="col-xs-12 col-sm-12 com-md-6 col-lg-4">';
			echo '<label for="embedvideo_'.$vid_field->virtuemart_custom_id.'"><i class="vmv-icon-youtube-play" ></i> '.$vid_field->custom_title.' </label>';
			if($vid_field->custom_tip)
				echo ' <i class="vmv-icon-info-sign hasTooltip" title="'.$vid_field->custom_tip.'" ></i>';
			echo '</div>';
			echo '<div class="col-xs-12 col-sm-12 com-md-6 col-lg-6">';
			if( file_exists(JPATH_BASE.'/plugins/vmcustom/embedvideo/embedvideo.php') )
				echo '<input type="text" placeholder="http://" class="form-control" name="embedvideo_'.$vid_field->virtuemart_custom_id.'" id="embedvideo_'.$vid_field->virtuemart_custom_id.'" />';
			else
				echo '<i class="vmv-icon-cancel"></i>';
			echo '</div></div>';

		}

	}
	
	////////////////////////////// Core Custom fields support Hasardous place as Virtuemart shared and multivendor custom fields is not totally done yet.	
			
	if($enable_corecustomfields)
	{

		$i = 0;
		foreach ($this->core_custom_fields as $core_custom_field)
		{
			$i++;
			echo '<div class="row"><div class="col-xs-12 col-sm-12 com-md-6 col-lg-4"><label for="corecustomfield_'.$i.'">';
			echo '<h4>'.JText::_($core_custom_field->custom_title).'</h4>';
			if($core_custom_field->custom_tip !='' OR $core_custom_field->custom_desc!='' )
				echo ' <i class="vmv-icon-info-sign hasTooltip" title="'.JText::_($core_custom_field->custom_tip).'"></i>
				</label></div>';		
			switch($core_custom_field->field_type){
				case "S": //string
				
				if($core_custom_field->is_list)
				{
					$ccfc_value = explode(';',$core_custom_field->custom_value);
					//echo '<div class="row"><div class="col-xs-12 col-sm-12 com-md-6 col-lg-4">';				
					//echo '<div class="row"><div class="col-xs-12 col-sm-12 com-md-6 col-lg-4">';
					echo '<div class="col-xs-12 col-sm-12 com-md-6 col-lg-6"><select class="form-control vm-chzn-select" name="corecustomfield_'.$i.'" id="corecustomfield_'.$i.'">';
					for( $ccfc_i=0; $ccfc_i<count($ccfc_value); $ccfc_i++)
					{
						echo '<option value="'.$ccfc_value[$ccfc_i].'" >'.JText::_($ccfc_value[$ccfc_i]).'</option>';	
					}
					
					echo '</select></div></div>';
				}
				else
					{
						echo '<div class="col-xs-12 col-sm-12 com-md-6 col-lg-4"><input name="corecustomfield_'.$i.'" id="corecustomfield_'.$i.'" type="text" value="'.$core_custom_field->custom_value.'" size="50" class="form-control" ';
							echo '/></div></div>';
					}
				break;
				case "I": // integer
						echo '<div class="row"><div class="col-xs-12 col-sm-12 com-md-6 col-lg-4">';				
						echo '<div class="row"><div class="col-xs-12 col-sm-12 com-md-6 col-lg-4">';	
						echo '<div class="col-xs-12 col-sm-12 com-md-6 col-lg-6"><input name="corecustomfield_'.$i.'" id="corecustomfield_'.$i.'" type="text" value="'.$core_custom_field->custom_value.'" size="50" class="form-control" /></div></div>';
				break;
				case "B": // bolean
							echo '<div class="radio-inline"><input name="corecustomfield_'.$i.'" id="corecustomfield_'.$i.'_0" type="radio"  value="0" ';
							if($core_custom_field->custom_value =='0' )
								echo ' checked="checked" ';
							echo ' /><div class="row"><div class="col-xs-12 col-sm-12 com-md-6 col-lg-4"><label for="corecustomfield_'.$i.'_0">'.JText::_('JNo').'</label></div></div>';
							
							echo '<div class="col-xs-12 col-sm-12 com-md-6 col-lg-4"><div class="radio-inline"><input name="corecustomfield_'.$i.'" id="corecustomfield_'.$i.'_1"  type="radio"  value="1"';
							if($core_custom_field->custom_value =='1' )
								echo ' checked="checked" ';
							echo ' /> <label for="corecustomfield_'.$i.'_1">'.JText::_('JYes').'</label></div></div></div>';
				break;
				case "D": // date
					
							echo JHTML::calendar('','corecustomfield_'.$i ,'corecustomfield_'.$i,'%Y-%m-%d');
				break;
				case "T": // time
							echo '<div class="row"><div class="col-xs-12 col-sm-12 com-md-6 col-lg-4">';				
							echo '<div class="row"><div class="col-xs-12 col-sm-12 com-md-6 col-lg-4">';	
							echo '<div class="col-xs-12 col-sm-12 com-md-6 col-lg-4"><input class="form-control" name="corecustomfield_'.$i.'" id="corecustomfield_'.$i.'" type="text" value="'.$core_custom_field->custom_value.'" size="50" /></div></div>';
				break;
				case "M": // image
							
				break;
				case "V": // cart variant
					if(!$core_custom_field->is_list)
						echo '<input name="corecustomfield_'.$i.'" type="text" value="'.$core_custom_field->custom_value.'" size="50" class="form-control" />';
					else{
						$exploded_cartvar = explode(';',$core_custom_field->custom_value);
						if(count($exploded_cartvar)>1){
							echo '<div class="row"><div class="col-xs-12 col-sm-12 com-md-6 col-lg-4">';				
							echo '<div class="row"><div class="col-xs-12 col-sm-12 com-md-6 col-lg-4">';	
							echo '<div class="col-xs-12 col-sm-12 com-md-6 col-lg-4"><select name="corecustomfield_'.$i.'" id="corecustomfield_'.$i.'" class="form-control vm-chzn-select" >';
							echo '<option value="">'.JText::_('COM_VMVENDOR_SELECT_OPTION').'</option>';
							for($i = 0; $i<count($exploded_cartvar);$i++){
								echo '<option value="'.$exploded_cartvar[$i].'">'.JText::_($exploded_cartvar[$i]).'</option>';	
							}
						
						
							echo '</select></div></div>';
						}
					}
						
				break;
				case "A": // generic Child variant
							
				break;
				case "X": // editor
					jimport( 'joomla.html.editor' );
					$editor = JFactory::getEditor();
				$editor_customfield_html = $editor->display("corecustomfield_".$i , $core_custom_field->custom_value , "100%;", '200', '5', '30', false);
				echo $editor_customfield_html;
							
				break;
					
				case "Y": // textarea
				echo '<div class="row"><div class="col-xs-12 col-sm-12 com-md-6 col-lg-4">';				
				echo '<div class="row"><div class="col-xs-12 col-sm-12 com-md-6 col-lg-4">';				
				echo '<div class="col-xs-12 col-sm-12 com-md-6 col-lg-4"><textarea name="corecustomfield_'.$i.'" id="corecustomfield_'.$i.'" >';
				echo $core_custom_field->custom_value;
				echo '</textarea></div></div><br>';			
				break;
			}
		}
		
	}
/*	
	if($flickr_autopost)
	{
		echo '<td style="text-align:right"><i class="vmv-icon-flickr" ></i>';
	echo '</div><div class="col-xs-12 col-sm-12 com-md-6 col-lg-4"><div id="checkboxtd" class="checkbox"><input type="checkbox" name="flickrcheckbox" id="flickrcheckbox" checked/> '.JText::_('COM_VMVENDOR_VMVENADD_FORM_PROMOTEONFLICKR'); //
  echo ' </div></div></div>';
		
	}
	
	echo '<div class="row"><div class="col-xs-12 col-sm-12 com-md-6 col-lg-4">';
	echo '</div><div class="col-xs-12 col-sm-12 com-md-6 col-lg-4"><div id="checkboxtd" class="checkbox"><input type="checkbox" name="formterms" id="formterms" onchange="document.getElementById(\'checkboxtd\').style.backgroundColor = \'\';"/> <label for="formterms" >'.JText::_('COM_VMVENDOR_VMVENADD_FORM_IAGREE').'</label> '; //
	if ($termsurl != NULL) {
    echo '<a href="' . $termsurl . '" target="_blank" >' . JText::_('COM_VMVENDOR_VMVENADD_FORM_TERMS') . '</a>';
  } else {
    echo JText::_('COM_VMVENDOR_VMVENADD_FORM_TERMS');
  }
  echo ' <b>*</b></div></div></div>';

*/
	if ($user->id != 0) {
    echo '<button type="submit" name="add" id="button" class="btn btn-primary btn-large btn-block" value="' . JText::_('COM_VMVENDOR_VMVENADD_BTTN_ADD') . '" >' . JText::_('COM_VMVENDOR_VMVENADD_BTTN_ADD') . '</button>';
    echo ' <img src="' . $juri . 'components/com_vmvendor/assets/img/loader.gif" alt="" width="200" height="19" border="0" name="loading" id="loading" align="absmiddle" style="display: none;" />';
  } else{
    $app->enqueueMessage(JText::_('COM_VMVENDOR_VMVENADD_ONLYLOGGEDIN'));
  }
	echo '<input type="hidden" name="option" value="com_vmvendor" />
						<input type="hidden" name="controller" value="addproduct" />';
	echo '<input type="hidden" name="task" value="addproduct" />';
	echo '</form>';
	echo '<div style="clear:both;"> </div>';