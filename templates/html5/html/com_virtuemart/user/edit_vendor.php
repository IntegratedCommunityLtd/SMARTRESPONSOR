<?php
defined('_JEXEC') or die('Restricted access');
$user = JFactory::getUser();  
	foreach ($user->groups as $key => $value){
		
		if ($key == 12) {

		}
    }
	  // echo '<pre>'; print_r (  ); echo '</pre>'.__FILE__.' in line:  '.__LINE__ ;
?>

	<div class="clearfix"></div>
	<ul id="dashboardTabTabs" class="nav nav nav-pills nav-justified">
		<li class="active">
        	<a data-toggle="tab" href="#shoper_form_info">
            	<span class="glyphicon glyphicon-user x-large"></span>
                <span class="hidden-xs"> <?= vmText::_('COM_VIRTUEMART_VENDOR_FORM_INFO_LBL') ?></span>
            </a>
        </li>
		<li class="">
        	<a data-toggle="tab" href="#vendor_form_info">
            	<span class="glyphicon glyphicon-qrcode x-large"></span>
                <span class="hidden-xs"> <?= vmText::_('COM_VIRTUEMART_VENDOR_FORM_INFO_LBL') ?></span>
            </a>
        </li>		
        <li class="">
        	<a data-toggle="tab" href="#currenccy_display">
            <span class="glyphicon glyphicon-euro x-large"></span>
            <span class="hidden-xs"> <?php echo vmText::_('COM_VIRTUEMART_STORE_CURRENCY_DISPLAY') ?></span></a>
		</li>
		<li class="">
			<a data-toggle="tab" href="#filesfandler"><span class="glyphicon glyphicon-file x-large"></span><span class="hidden-xs"> <?php echo vmText::_('COM_VIRTUEMART_VENDOR_FORM_INFO_LBL') ?></span></a>
		</li>
		<li class="">
			<a data-toggle="tab" href="#tos"><span class="glyphicon glyphicon-align-justify x-large"></span><span class="hidden-xs"> <?php echo vmText::_('COM_VIRTUEMART_STORE_FORM_TOS');?></span></a>
		</li>
		<li class="">
			<a data-toggle="tab" href="#legal"><span class="glyphicon glyphicon-align-justify x-large"></span><span class="hidden-xs"> <?php echo vmText::_('COM_VIRTUEMART_STORE_FORM_LEGAL');?></span></a>
		</li>	
	</ul>
	
    <div class="clearfix"></div>
		<div class="tab-content">
			<div id="shoper_form_info" class="tab-pane active">
				<?php echo $this->loadTemplate ( 'shopper' ); ?>
       
 			</div>		
			<div id="vendor_form_info" class="tab-pane">
				<fieldset>
                    <legend>
                        <?= vmText::_('COM_VIRTUEMART_VENDOR_FORM_INFO_LBL') ?>
                    </legend>

                    <dl class="dl-horizontal">
                    	<dt>
							<?= vmText::_('COM_VIRTUEMART_STORE_FORM_STORE_NAME'); ?>:
						</dt>
						
                        <dd>
							<input class="form-control" 
                            	type="text" 
                                name="vendor_store_name" 
                                id="vendor_store_name" 
                                size="50"  value="<?=$this->vendor->vendor_store_name; ?>" />
						</dd>
						 
                       
                        
		
						<dt>
						<?=vmText::_('COM_VIRTUEMART_STORE_FORM_COMPANY_NAME'); ?>:
						</dt>
						<dd>
							<input class="form-control" type="text" name="vendor_name" id="vendor_name" size="50"  value="<?= $this->vendor->vendor_name; ?>" />
						</dd>
		
		
						<dt>
							<?= vmText::_('COM_VIRTUEMART_PRODUCT_FORM_URL'); ?>:
                        </dt>
                        <dd>
							<input class="form-control" type="text" name="vendor_url" id="<?php echo $disabled ?>vendor_url" size="50" <?php echo $inputed ?> value="<?php echo $this->vendor->vendor_url; ?>" />
						</dd>
		
		
		<dt>
			<?php echo vmText::_('COM_VIRTUEMART_STORE_FORM_MPOV'); ?>:
		</dt>
		<dd>
			<input class="form-control" type="text" name="vendor_min_pov" id="vendor_min_pov" size="10"  value="<?php echo $this->vendor->vendor_min_pov; ?>" />
		</dd>
						
	</dl>
			
            
            <legend>
				<?= vmText::_('COM_VIRTUEMART_STORE_FORM_DESCRIPTION');?> 
			</legend>
			
            <style>
            	.vendor_store_desc{
					width: 100%;
    				height: 100px;
				}
            </style>
            
			<?php 
				if( !empty ($inputed) ){
					?>
					<textarea name="vendor_store_desc"  class="vendor_store_desc" >
            			<?= $this->vendor->vendor_store_desc ?>
            		</textarea>	
				<?php	
				}else{
					echo $this->editor->display('vendor_store_desc', $this->vendor->vendor_store_desc, '100%', 450, 70, 15);
				} // end if
			 	?>
			
			
            
			
			
		
        
        </fieldset>		
			</div>
	<div id="currenccy_display" class="tab-pane">
		<fieldset>
			<legend>
				<?php echo vmText::_('COM_VIRTUEMART_STORE_CURRENCY_DISPLAY') ?>
			</legend>
		</fieldset>		
			
            <dl class="dl-horizontal">
				<dt>
					<?php echo vmText::_('COM_VIRTUEMART_CURRENCY'); ?>:
				</dt>
				<dd>
					<?php echo JHtml::_('Select.genericlist', $this->currencies, 'vendor_currency', 'class="chosen"', 'virtuemart_currency_id', 'currency_name', $this->vendor->vendor_currency); ?>
				</dd>
				
				
				<dt>
					<?php echo vmText::_('COM_VIRTUEMART_STORE_FORM_ACCEPTED_CURRENCIES'); ?>:
				</dt>
				<dd>
					<?php echo JHtml::_('Select.genericlist', $this->currencies, 'vendor_accepted_currencies[]', 'size=10 multiple class="chosen"', 'virtuemart_currency_id', 'currency_name', $this->vendor->vendor_accepted_currencies); ?>
				</dd>
			</dl>
	</div>
	
    
    <?php // Изображение продавца  ?>
    <div id="filesfandler" class="tab-pane">
		<fieldset>
			<legend>
            	<?= vmText::_('Изображение продавца ');?>
			</legend>			
			<?php
			if( !empty ($inputed) )
			{
				if(!empty ($this->vendor->images[0]->file_name) )
				{ ?>
					<img src="<?php echo JURI::root () . $this->vendor->images[0]->file_url ?>"  />
				<?php							
				} // end if  
			}
			else
			{
				echo $this->vendor->images[0]->displayFilesHandler($this->vendor->virtuemart_media_id,'vendor');	
			}?>
		</fieldset>
	</div><!-- /#filesfandler -->
    
    
	
	
	<?php
	// Terms of Service
    if( !empty ($inputed) )
	{ ?>
    	<style>
    		#vendor_terms_of_service_parent,
			#vendor_legal_info_parent,
			[type="reset"]{
				display:none
			}
			#vendor_terms_of_service,
			#vendor_legal_info{
				display:block !important;
			}	
    	</style>
	<?php 
	} ?>
    
    <div id="tos" class="tab-pane ">
		<fieldset<?php echo $fieldset ?>>
			<legend>
				<?php echo vmText::_('COM_VIRTUEMART_STORE_FORM_TOS');?>
			</legend>
				<?php echo $this->editor->display('vendor_terms_of_service', $this->vendor->vendor_terms_of_service, '100%', 450, 70, 15)?>
		</fieldset>
	</div>
    
    
    
    
	<div id="legal" class="tab-pane">
		<fieldset <?php echo $fieldset ?>>
			<legend>
				<?php echo vmText::_('COM_VIRTUEMART_STORE_FORM_LEGAL');?>
			</legend>
				<?php echo $this->editor->display('vendor_legal_info', $this->vendor->vendor_legal_info, '100%', 400, 70, 15)?>
		</fieldset>
	</div>	
</div>

<input type="hidden" name="user_is_vendor" value="1" />
<input type="hidden" name="virtuemart_vendor_id" value="<?php echo $this->vendor->virtuemart_vendor_id; ?>" />
<input type="hidden" name="last_task" value="<?php echo vRequest::getCmd('task'); ?>" />
<script>
jQuery('input:text').addClass('form-control');
jQuery('#vendor_legal_info').addClass('form-control');
jQuery('#vendor_store_desc').addClass('form-control');
jQuery('#vendor_terms_of_service').addClass('form-control');
jQuery('#vendor_legal_info').addClass('form-control');
</script>