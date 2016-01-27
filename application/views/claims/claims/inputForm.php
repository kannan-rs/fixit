<?php
	$claim  = isset($claims) && is_array($claims) ? $claims[0] : null;

	$edit = false;
	$prefix = "create";

	$claim_id 					= "";
	$claim_customer_id 			= "";
	$claim_type 				= "";
	$linked_to_disaster 		= "";
	$natational_disaster 		= "";
	$national_disaster_type 	= "";
	$insurer_name 				= "";
	$insurer_id 				= "";
	$property_name 				= "";
	
	$is_property_address_same 	= "";

	$customer_contact_no 		= "";
	$customer_email_id 			= "";
	$claim_number 				= "";
	$claim_description 			= "";
	$claim_details 				= "";
	$claim_admin 				= "";
	$claim_adjuster_id 			= "";
	$claim_start_date 			= "";
	$claim_end_date 			= "";
	$claim_complete_date 		= "";
	$claim_close_date 			= "";
	$is_deleted 				= "";
	$created_on 				= "";
	$updated_on 				= "";
	$created_by 				= "";
	$updated_by 				= "";
	$Property_damage_reserve 	= "";

	$same_address_checked		= "";
	$same_address_disabled_prop	= "disabled=disabled";

	if($claim) {
		$edit = true;
		$prefix = "update";

		$claim_id 					= $claim->claim_id;
		$claim_customer_id 			= $claim->claim_customer_id;
		$claim_type 				= $claim->claim_type;
		$linked_to_disaster 		= $claim->linked_to_disaster;
		$natational_disaster 		= $claim->natational_disaster;
		$national_disaster_type 	= $claim->national_disaster_type;
		$insurer_name 				= $claim->insurer_name;
		$insurer_id 				= $claim->insurer_id;
		$property_name 				= $claim->property_name;

		$is_property_address_same 	= $claim->is_property_address_same;
		$same_address_checked 		= $is_property_address_same ? " checked" : $same_address_checked;
		$same_address_disabled_prop	= "";

		$customer_contact_no 		= $claim->customer_contact_no;
		$customer_email_id 			= $claim->customer_email_id;
		$claim_number 				= $claim->claim_number;
		$claim_description 			= $claim->claim_description;
		$claim_details 				= $claim->claim_details;
		$claim_admin 				= $claim->claim_admin;
		$claim_adjuster_id 			= $claim->claim_adjuster_id;
		$claim_start_date 			= $claim->claim_start_date;
		$claim_end_date 			= $claim->claim_end_date;
		$claim_complete_date 		= $claim->claim_complete_date;
		$claim_close_date 			= $claim->claim_close_date;
		$is_deleted 				= $claim->is_deleted;
		$created_on 				= $claim->created_on;
		$updated_on 				= $claim->updated_on;
		$created_by 				= $claim->created_by;
		$updated_by 				= $claim->updated_by;
		$Property_damage_reserve 	= $claim->Property_damage_reserve;
	}
?>

<h2><?php echo $this->lang->line_arr('claim->headers->'.$prefix);?></h2>
<form id="<?php echo $prefix ?>_claim_form" name="<?php echo $prefix ?>_claim_form" class="inputForm">
	<input type="hidden" id='claimId' value="<?php echo isset($claim_id) ? $claim_id : "" ; ?>" />
	<input type="hidden" value="<?php echo $claim_customer_id;?>" name="customer_id" id="customer_id">

	<div id="claim_accordion" class="accordion">
		<h3><span class="inner_accordion"><?php echo $this->lang->line_arr('claim->headers->customer_details'); ?></span></h3>
		<div>
			<?php
				if(in_array('view', $customerPermission['operation'])) {
				?>
				<table class='form'>
					<tbody>
						<!-- Project Customer Name Search and Adding -->
						<tr>
							<td class="label"><?php echo $this->lang->line_arr('claim->input_form->searchCustomerName'); ?></td>
							<td>
								<input type="text" name="searchCustomerName" id="searchCustomerName" value="" 
									placeholder="<?php echo $this->lang->line_arr('claim->input_form->searchCustomerName_ph'); ?>" 
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
					</tbody>
				</table>
				<?php
				}
				?>
				<div id="claim_customer_address">
				<?php
					echo $customer_address_file;
				?>
				</div>
			</div>

			<h3><span class="inner_accordion"><?php echo $this->lang->line_arr('claim->headers->property_address'); ?></span></h3>
			<div>
				<table class="form">
					<tbody>
						<tr>
							<td colspan="2">
								<input type="checkbox" name="is_property_address_same" id="is_property_address_same" 
									<?php echo $same_address_disabled_prop;?> <?php echo $same_address_checked; ?>> 
									Is property address and communication address are same
							</td>
						</tr>
					</tbody>
				</table>
				<table class="form" id="claim_property_address">
					<tbody>
					<?php
						echo $property_address_file;
					?>
					</tbody>
				</table>
			</div>

			<h3><span class="inner_accordion"><?php echo $this->lang->line_arr('claim->headers->other_details'); ?></span></h3>
			<div>
				<table class="form">
					<tbody>
					<tr>
						<td class="label"><?php echo $this->lang->line_arr('claim->input_form->contactPhoneNumber'); ?></td>
						<td>
								<input type="number" name="contactPhoneNumber" id="contactPhoneNumber" value="<?php echo $customer_contact_no; ?>" placeholder="<?php echo $this->lang->line_arr('claim->input_form->contactPhoneNumber_ph'); ?>" required>
						</td>
					</tr>

					<tr>
						<td class="label"><?php echo $this->lang->line_arr('claim->input_form->email'); ?></td>
						<td>
								<input type="email" name="emailId" id="emailId" value="<?php echo $customer_email_id; ?>" placeholder="<?php echo $this->lang->line_arr('claim->input_form->email_ph'); ?>" required>
						</td>
					</tr>

					<tr>
						<td class="label"><?php echo $this->lang->line_arr('claim->input_form->claim_number'); ?></td>
						<td>
								<input type="text" name="claim_number" id="claim_number" value="<?php echo $claim_number; ?>" placeholder="<?php echo $this->lang->line_arr('claim->input_form->claim_number_ph'); ?>" required>
						</td>
					</tr>

					<tr>
						<td class="label"><?php echo $this->lang->line_arr('claim->input_form->description'); ?>:</td>
						<td>
							<textarea type="text" name="description" id="description" rows="10" cols="70" placeholder="<?php echo $this->lang->line_arr('claim->input_form->description_ph'); ?>" required><?php echo $claim_description; ?></textarea>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<table class="form">
		<tbody>
			<tr>
				<td colspan="2">
					<p class="button-panel">
						<button type="button" id="create_claim_submit" onclick="_claims.<?php echo $prefix; ?>Validate()"><?php echo $this->lang->line_arr('claim->buttons_links->'.$prefix); ?></button>
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
