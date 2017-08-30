<?php
defined('_JEXEC') or die('Restricted access');

// Status Of Delimiter
$closeDelimiter = false;
$openTable = true;
$hiddenFields = '';
	
/*	
	
		$user = JFactory::getUser();
		jimport( 'joomla.user.helper' );
		$groups = JUserHelper::getUserGroups($user->id);
		if (array_key_exists(12, $groups)) {
			$userFieldsTemp = array();
			foreach ( $this->userFields['fields']  as  $k => $v ){  
				
//				echo '<pre>'; print_r ( $k ); echo '</pre>'.__FILE__.' in line:  '.__LINE__ ;
				if($k !== 'phone_1' && $k !== 'phone_2' ){ 
					$shArr = array('<input','<select');
					$resArr = array("<input disabled ",'<select disabled');
					$v['formcode'] =  str_replace($shArr, $resArr, $v['formcode']);
				} // end if
				$userFieldsTemp['fields'][$k] = $v ;
			}//foreach
			$this->userFields  = $userFieldsTemp ;
			echo '<!--  '.__FILE__.' строка '.__LINE__.' -->';   
			?>
			<h3><?=JText::_('EDIT_ADDRESS_USERFIELDS') ?></h3>
			<?php 
		}   // end if
*/
 
	// Output: Userfields
	foreach($this->userFields['fields'] as $field) {

		if($field['type'] == 'delimiter') {

		// For Every New Delimiter
		// We need to close the previous
		// table and delimiter
		if($closeDelimiter) { ?>
			</dl>
		</fieldset>
		<?php
		} ?>

		<fieldset>
			<legend><?php echo $field['title'] ?></legend>
		</fieldset>
		<?php
		$closeDelimiter = true;
		$openTable = true;

	} elseif ($field['hidden'] == true) {

		// We collect all hidden fields
		// and output them at the end
		$hiddenFields .= $field['formcode'] . "\n";

	} else {

		// If we have a new delimiter
		// we have to start a new table
		if($openTable) {
			$openTable = false;
			?>
<fieldset>
			<dl>

		<?php
		}

		// Output: Userfields
		?>

					<dt>
							<?php echo $field['title'] . ($field['required'] ? ' *' : '') ?>
					</dt>
					<dd>
						<?php echo $field['formcode'] ?>
					</dd>

	<?php
	}

}

// At the end we have to close the current
// table and delimiter ?>

			</dl>
<fieldset>

<?php // Output: Hidden Fields
echo $hiddenFields
?>