<?php
	$suborgOne  = isset($subrogation) && is_array($subrogation) ? $subrogation[0] : null;

	$edit = false;
	$prefix = "create";

	$subrogation_id 			= "";
	$claim_id 					= "";
	$customer_id 				= "";
	$climant_name 				= "";
	
	$description 				= "";
	$status 					= "";
	

	if($suborgOne) {
		$edit = true;
		$prefix = "update";

		$subrogation_id 			= $suborgOne->subrogation_id;
		$claim_id 					= $suborgOne->claim_id;
		$climant_name 				= $suborgOne->climant_name;
	
		$description 				= $suborgOne->description;
		$status 					= $suborgOne->status;
	}
?>

<form id="<?php echo $prefix ?>_subrogation_form" name="<?php echo $prefix ?>_subrogation_form" class="inputForm">
	<input type="hidden" id='subrogation_id' value="<?php echo isset($subrogation_id) ? $subrogation_id : "" ; ?>" />
	<input type="hidden" id='claim_id' value="<?php echo isset($claim_id) ? $claim_id : "" ; ?>" />
	<input type="hidden" value="<?php echo $status;?>" name="db_status_value" id="db_status_value">

	<div id="subrogation_accordion" class="accordion">
		<!-- Project Description -->
		<h3><span class="inner_accordion"><?php echo $this->lang->line_arr('claim_subrogation->headers->customer_details'); ?></span></h3>
		<div>
			<table class='form'>
				<tbody>

					<?php
					if(in_array(OPERATION_VIEW, $customerPermission['operation'])) {
					?>
						<!-- Project Customer Name Search and Adding -->
						<tr>
							<td class="label notMandatory"><?php echo $this->lang->line_arr('claim_subrogation->input_form->searchCustomerName'); ?></td>
							<td>
								<?php echo $customer_name_from_claim_db; ?>
							</td>
						</tr>
						<tr>
							<td colspan="2">Customer Communication Address</td>
						</tr>
						<?php echo $customer_communication_address_file; ?>
					<?php
					}
					?>
				</tbody>
			</table>
		</div>

		<h3><span class="inner_accordion"><?php echo $this->lang->line_arr('claim_subrogation->headers->climant_details'); ?></span></h3>
		<div>
			<table class="form">
				<tbody>
					<tr>
						<td class="label"><?php echo $this->lang->line_arr('claim_subrogation->input_form->climant_name'); ?></td>
						<td>
								<input type="text" name="climant_name" id="climant_name" 
									value="<?php echo $climant_name; ?>" 
									placeholder="<?php echo $this->lang->line_arr('claim_subrogation->input_form->climant_name_ph'); ?>" required>
						</td>
					</tr>

					<?php
						echo $claimant_address_file;
					?>
				</tbody>
			</table>
		</div>

		<h3><span class="inner_accordion"><?php echo $this->lang->line_arr('claim_subrogation->headers->other_details'); ?></span></h3>
		<div>
			<table class="form">
				<tbody>
					<tr>
						<td class="label"><?php echo $this->lang->line_arr('claim_subrogation->input_form->description'); ?></td>
						<td>
								<textarea name="description" id="description" rows="10" cols="70"
									placeholder="<?php echo $this->lang->line_arr('claim_subrogation->input_form->description_ph'); ?>" required><?php echo $description; ?></textarea>
						</td>
					</tr>

					<tr>
						<td class="label"><?php echo $this->lang->line_arr('claim_subrogation->input_form->status'); ?></td>
						<td>
								<select name="status" id="status" required>
									<option value="">-- Select Status --</option>
									<option value="assigned">Assigned</option>
									<option value="investigating">Investigating</option>
									<option value="denied">Denied</option>
									<option value="payment requested">Payment Requested</option>
									<option value="closed">Closed</option>
								</select>
								<!-- <input type="text" name="status" id="status" value="<?php echo $status; ?>" 
									placeholder="<?php echo $this->lang->line_arr('claim_subrogation->input_form->status_ph'); ?>" required> -->
						</td>
					</tr>
					
					<tr>
						<td colspan="2">
							<p class="button-panel">
								<button type="button" id="create_subrogation_submit" 
									onclick="_claim_subrogation.<?php echo $prefix; ?>Validate()">
										<?php echo $this->lang->line_arr('claim_subrogation->buttons_links->'.$prefix); ?>
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
		</div>
	</div>
</form>
