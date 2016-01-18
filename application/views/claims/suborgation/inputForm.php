<?php
	$suborgOne  = isset($suborgation) && is_array($suborgation) ? $suborgation[0] : null;

	$edit = false;
	$prefix = "create";

	$suborgation_id 			= "";
	$claim_id 					= "";
	$customer_id 				= "";
	$climant_name 				= "";
	$addressLine1				= "";
	$addressLine2				= "";
	$addr_city					= "";
	$addr_country				= "";
	$addr_state					= "";
	$addr_pin					= "";
	$description 				= "";
	$status 					= "";
	

	if($suborgOne) {
		$edit = true;
		$prefix = "update";

		$suborgation_id 			= $suborgOne->suborgation_id;
		$claim_id 					= $suborgOne->claim_id;
		$customer_id 				= $suborgOne->customer_id;
		$climant_name 				= $suborgOne->climant_name;
		$addressLine1 				= $suborgOne->addressLine1;
		$addressLine2 				= $suborgOne->addressLine2;
		$addr_city 					= $suborgOne->addr_city;
		$addr_state 				= $suborgOne->addr_state;
		$addr_country 				= $suborgOne->addr_country;
		$addr_pin 					= $suborgOne->addr_pin;
		$description 				= $suborgOne->description;
		$status 					= $suborgOne->status;
	}
?>

<form id="<?php echo $prefix ?>_suborgation_form" name="<?php echo $prefix ?>_suborgation_form" class="inputForm">
	<input type="hidden" id='suborgation_id' value="<?php echo isset($suborgation_id) ? $suborgation_id : "" ; ?>" />
	<input type="hidden" id='claim_id' value="<?php echo isset($claim_id) ? $claim_id : "" ; ?>" />
	<input type="hidden" value="<?php echo $customer_id;?>" name="customer_id" id="customer_id">

	<table class='form'>
		<tbody>

			<?php
			if(in_array('view', $customerPermission['operation'])) {
			?>
				<!-- Project Customer Name Search and Adding -->
				<tr>
					<td class="label"><?php echo $this->lang->line_arr('claim_suborgation->input_form->searchCustomerName'); ?></td>
					<td>
						<input type="text" name="searchCustomerName" id="searchCustomerName" value="" 
							placeholder="<?php echo $this->lang->line_arr('claim_suborgation->input_form->searchCustomerName_ph'); ?>" 
							onkeyup="_utils.showCustomerListInDropDown()" required>
						<span class="fi-zoom-in size-21 searchIcon" onclick="_utils.showCustomerListInDropDown()"></span>
					</td>
				</tr>
				<tr class="customer-search-result">
				<td  class="label notMandatory">&nbsp;</td>
					<td>
						<ul id="customerNameList" class="connectedSortable dropdown"></ul>
					</td>
				</tr>
			<?php
			}
			?>

			<tr>
				<td class="label"><?php echo $this->lang->line_arr('claim_suborgation->input_form->climant_name'); ?></td>
				<td>
						<input type="text" name="climant_name" id="climant_name" 
							value="<?php echo $climant_name; ?>" 
							placeholder="<?php echo $this->lang->line_arr('claim_suborgation->input_form->climant_name_ph'); ?>" required>
				</td>
			</tr>

			<?php
				echo $addressFile;
			?>

			<tr>
				<td class="label"><?php echo $this->lang->line_arr('claim_suborgation->input_form->description'); ?></td>
				<td>
						<textarea name="description" id="description" rows="10" cols="70"
							placeholder="<?php echo $this->lang->line_arr('claim_suborgation->input_form->description_ph'); ?>" required><?php echo $description; ?></textarea>
				</td>
			</tr>

			<tr>
				<td class="label"><?php echo $this->lang->line_arr('claim_suborgation->input_form->status'); ?></td>
				<td>
						<input type="text" name="status" id="status" value="<?php echo $status; ?>" 
							placeholder="<?php echo $this->lang->line_arr('claim_suborgation->input_form->status_ph'); ?>" required>
				</td>
			</tr>
			
			<tr>
				<td colspan="2">
					<p class="button-panel">
						<button type="button" id="create_suborgation_submit" 
							onclick="_claim_suborgation.<?php echo $prefix; ?>Validate()">
								<?php echo $this->lang->line_arr('claim_suborgation->buttons_links->'.$prefix); ?>
						</button>
						<button type="reset" id="resetButton" onclick=""><?php echo $this->lang->line_arr('buttons->reset'); ?></button>
						
					</p>
				</td>
			</tr>
			<tr>
				<td colspan="2"><?php echo $this->lang->line('mandatory_field_text'); ?></td>
			</tr>
		</tbody>
	</table>
</form>
