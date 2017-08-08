<?php
defined('_JEXEC') or die('Restricted access');
?>
<?php/*
<div class="billto-shipto separatorline">
<div class="row nomargin ">
	<div class="col-md-6 nopaddingleft">
				<strong><span class="vmicon vm2-billto-icon"></span>
					<?php echo JText::_ ('COM_VIRTUEMART_USER_FORM_BILLTO_LBL'); ?>
				</strong>	
		<div class="output-billto">

				<?php // Output Bill To Address ?>

					<?php
					foreach ($this->cart->BTaddress['fields'] as $item) {
						if (!empty($item['value'])) {
							if ($item['name'] === 'agreed') {
								$item['value'] = ($item['value'] === 0) ? JText::_ ('COM_VIRTUEMART_USER_FORM_BILLTO_TOS_NO') : JText::_ ('COM_VIRTUEMART_USER_FORM_BILLTO_TOS_YES');
							}
							?><!-- span class="titles"><?php echo $item['title'] ?></span -->
							<span class="values vm2<?php echo '-' . $item['name'] ?>"><?php echo $this->escape ($item['value']) ?></span>
							<?php if ($item['name'] != 'title' and $item['name'] != 'first_name' and $item['name'] != 'middle_name' and $item['name'] != 'zip') { ?>
								<br class="clear"/>
								<?php
							}
						}
					} ?>
				
		</div>

		<a class="btn btn-primary" disabled="disabled" style="width: 100%;" href="<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=user&task=editaddresscart&addrtype=BT', $this->useXHTML, $this->useSSL) ?>">
			<?php echo JText::_ ('COM_VIRTUEMART_USER_FORM_EDIT_BILLTO_LBL'); ?>
		</a>
		<input type="hidden" name="billto" value="<?php echo $this->cart->lists['billTo']; ?>"/>	
	</div>



	<div class="col-md-6 nopaddingright">
			<strong><span class="vmicon vm2-shipto-icon"></span>
				<?php echo JText::_ ('COM_VIRTUEMART_USER_FORM_SHIPTO_LBL'); ?></strong>

		<div class="output-shipto">
			<?php // Output Bill To Address ?>
				<?php
				if (empty($this->cart->STaddress['fields'])) {
					echo JText::sprintf ('COM_VIRTUEMART_USER_FORM_EDIT_BILLTO_EXPLAIN', JText::_ ('COM_VIRTUEMART_USER_FORM_ADD_SHIPTO_LBL'));
				} else {
					if (!class_exists ('VmHtml')) {
						require(JPATH_VM_ADMINISTRATOR . DS . 'helpers' . DS . 'html.php');
					}
					echo JText::_ ('COM_VIRTUEMART_USER_FORM_ST_SAME_AS_BT');
					echo VmHtml::checkbox ('STsameAsBTjs', $this->cart->STsameAsBT) . '<br />';
					?>
					<div id="output-shipto-display">
						<?php
						foreach ($this->cart->STaddress['fields'] as $item) {
							if (!empty($item['value'])) {
								?>
								<!-- <span class="titles"><?php echo $item['title'] ?></span> -->
								<?php
								if ($item['name'] == 'first_name' || $item['name'] == 'middle_name' || $item['name'] == 'zip') {
									?>
									<span class="values<?php echo '-' . $item['name'] ?>"><?php echo $this->escape ($item['value']) ?></span>
									<?php } else { ?>
									<span class="values"><?php echo $this->escape ($item['value']) ?></span>
									<br class="clear"/>
									<?php
								}
							}
						}
						?>
					</div>
					<?php
				}
				?>
				

		</div>
		<?php if (!isset($this->cart->lists['current_id'])) {
		$this->cart->lists['current_id'] = 0;
	} ?>
		<a class="btn btn-primary" disabled="disabled" style="width: 100%;" href="<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=user&task=editaddresscart&addrtype=ST&virtuemart_user_id[]=' . $this->cart->lists['current_id'], $this->useXHTML, $this->useSSL) ?>">
			<?php echo JText::_ ('COM_VIRTUEMART_USER_FORM_ADD_SHIPTO_LBL'); ?>
		</a>
	</div>
</div>

	<div class="clear"></div>
</div> <?php // Верхушка. Конец ?>
*/?>






<div class="billto-shipto btn-group-vertical">

		<span><span class="vmicon vm2-billto-icon"></span>
			<?php echo vmText::_ ('COM_VIRTUEMART_USER_FORM_BILLTO_LBL'); ?></span>
		<?php // Output Bill To Address ?>
		<div class="output-billto">
			<?php
			$cartfieldNames = array();
			foreach( $this->userFieldsCart['fields'] as $fields){
				$cartfieldNames[] = $fields['name'];
			}

			foreach ($this->cart->BTaddress['fields'] as $item) {
				if(in_array($item['name'],$cartfieldNames)) continue;
				if (!empty($item['value'])) {
					if ($item['name'] === 'agreed') {
						$item['value'] = ($item['value'] === 0) ? vmText::_ ('COM_VIRTUEMART_USER_FORM_BILLTO_TOS_NO') : vmText::_ ('COM_VIRTUEMART_USER_FORM_BILLTO_TOS_YES');
					}
					?><!-- span class="titles"><?php echo $item['title'] ?></span -->
			<span class="values vm2<?php echo '-' . $item['name'] ?>"><?php echo $item['value'] ?></span>
			<?php if ($item['name'] != 'title' and $item['name'] != 'first_name' and $item['name'] != 'middle_name' and $item['name'] != 'zip') { ?>
				<br class="clear"/>
			<?php
			}
			}
			} ?>
			<div class="clear"></div>
		</div>

		<a class="details" href="<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=user&task=editaddresscart&addrtype=BT', $this->useXHTML, $this->useSSL) ?>" rel="nofollow">
			<?php echo vmText::_ ('COM_VIRTUEMART_USER_FORM_EDIT_BILLTO_LBL'); ?>
		</a>

		<input type="hidden" name="billto" value="<?php echo $this->cart->lists['billTo']; ?>"/>


	<div class="width50 floatleft">

		<span><span class="vmicon vm2-shipto-icon"></span>
			<?php echo vmText::_ ('COM_VIRTUEMART_USER_FORM_SHIPTO_LBL'); ?></span>
		<?php // Output Bill To Address ?>
		<div class="output-shipto btn-group-vertical">
			<?php
			if (!class_exists ('VmHtml')) {
				require(VMPATH_ADMIN . DS . 'helpers' . DS . 'html.php');
			}
			if($this->cart->user->virtuemart_user_id==0){

				echo vmText::_ ('COM_VIRTUEMART_USER_FORM_ST_SAME_AS_BT');
				echo VmHtml::checkbox ('STsameAsBTjs', $this->cart->STsameAsBT) . '<br />';
			} else if(!empty($this->cart->lists['shipTo'])){
				echo $this->cart->lists['shipTo'];
			}

			if(!empty($this->cart->ST) and  !empty($this->cart->STaddress['fields'])){ ?>
				<div id="output-shipto-display">
					<?php
					foreach ($this->cart->STaddress['fields'] as $item) {
						if (!empty($item['value'])) {
							?>
							<!-- <span class="titles"><?php echo $item['title'] ?></span> -->
							<?php
							if ($item['name'] == 'first_name' || $item['name'] == 'middle_name' || $item['name'] == 'zip') {
								?>
								<span class="values<?php echo '-' . $item['name'] ?>"><?php echo $item['value'] ?></span>
							<?php } else { ?>
								<span class="values"><?php echo $item['value'] ?></span>
								<br class="clear"/>
							<?php
							}
						}
					}
					?>
				</div>
			<?php
			}
			?>
			<div class="clear"></div>
		</div>
		<?php if (!isset($this->cart->lists['current_id'])) {
			$this->cart->lists['current_id'] = 0;

		} ?>
		<a class="details" href="<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=user&task=editaddresscart&addrtype=ST&virtuemart_user_id[]=' . $this->cart->lists['current_id'], $this->useXHTML, $this->useSSL) ?>" rel="nofollow">
			<?php echo vmText::_ ('COM_VIRTUEMART_USER_FORM_ADD_SHIPTO_LBL'); ?>
		</a>

	</div>

	<div class="clear"></div>
</div>