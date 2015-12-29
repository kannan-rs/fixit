<?php
	$contractor  = isset($contractors) && is_array($contractors) ? $contractors[0] : null;

	$edit = false;
	$prefix = "create";
	if($contractor) {
		$edit = true;
		$prefix = "update";

		$id 							= $contractor->id;
		$name 							= $contractor->name;
		$company 						= $contractor->company;
		$type 							= $contractor->type;
		$license 						= $contractor->license;
		$bbb 							= $contractor->bbb;
		$status 						= $contractor->status;
		$office_email 					= $contractor->office_email;
		$office_ph 						= $contractor->office_ph;
		$mobile_ph 						= $contractor->mobile_ph;
		$prefer 						= $contractor->prefer;
		$website_url 					= $contractor->website_url;
		$service_area 					= $contractor->service_area;
		$default_contact_user_id		= $contractor->default_contact_user_id;
		$default_contact_user_disp_str	= isset($contractor->default_contact_user_disp_str) ? $contractor->default_contact_user_disp_str : "";

	}

	$notMandatory 	= $role_disp_name == "admin" ? "notMandatory" : "";
	$required		= $role_disp_name == "admin" ? " " : "required";
?>

<?php
if(!$edit && (!$openAs || $openAs != "popup")) {
	echo "<h2>".$this->lang->line_arr('contractor->headers->'.$prefix)."</h2>";
}

$validateOptions = "";
if($openAs) {
	$validateOptions = "'".$openAs."', '".$popupType."'";
}
?>

<form id="<?php echo $prefix; ?>_contractor_form" name="<?php echo $prefix; ?>_contractor_form" class="inputForm">
	
	<input type="hidden" id='contractorId' value="<?php echo isset($id) ? $id : ""; ?>" />
	<input type="hidden" name="statusDb" id="statusDb" value="<?php echo isset($status) ? $status : ""; ?>">
	<input type="hidden" name="prefContactDb" id="prefContactDb" value="<?php echo isset($prefer) ? $prefer : ""; ?>" />
	<input type="hidden" name = "db_default_user_id" id= "db_default_user_id" value="<?php echo isset($default_contact_user_id) ? $default_contact_user_id : ""; ?>" />

	<table class='form'>
		<tbody>
			<!-- <tr>
				<td class="label"><?php echo $this->lang->line_arr('contractor->input_form->name'); ?>:</td>
				<td>
					<input type="text" name="name" id="name" value="<?php echo isset($name) ? $name : "";?>" required 
						placeholder="<?php echo $this->lang->line_arr('contractor->input_form->name_ph'); ?>">
				</td>
			</tr> -->
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('contractor->input_form->company'); ?>:</td>
				<td>
					<input type="text" name="company" id="company" value="<?php echo isset($company) ? $company : "";?>" required 
						placeholder="<?php echo $this->lang->line_arr('contractor->input_form->company_ph'); ?>">
				</td>
			</tr>
			<?php
			if($role_disp_name == "admin") {
			?>
				<tr>
					<td class="label notMandatory"><?php echo $this->lang->line_arr('contractor->input_form->searchForDefaultContractor'); ?></td>
					<td>
						<input type="text" id="searchForDefaultContractor" name="searchForDefaultContractor" 
							placeholder="<?php echo $this->lang->line_arr('contractor->input_form->searchForDefaultContractor_ph'); ?>" 
							onkeyup="_contractors.searchUserByEmail({ emailId : this.value })" 
							value="<?php echo isset($default_contact_user_disp_str) ? $default_contact_user_disp_str : ""; ?>">
					</td>
				</tr>
				<tr class="default-user-search-result">
				<td  class="label notMandatory">&nbsp;</td>
					<td>
						<ul id="contractorUserList" class="connectedSortable dropdown"></ul>
					</td>
				</tr>
			<?php
			}
			?>
			<tr>
				<td class="label <?php echo $notMandatory;?>"><?php echo $this->lang->line_arr('contractor->input_form->type'); ?></td>
				<td>
					<input type="text" name="type" id="type" value="<?php echo isset($type) ? $type : "";?>" 
						placeholder="<?php echo $this->lang->line_arr('contractor->input_form->type_ph'); ?>" <?php  echo $required; ?> >
				</td>
			</tr>
			<tr>
				<td class="label <?php echo $notMandatory;?>"><?php echo $this->lang->line_arr('contractor->input_form->license'); ?></td>
				<td>
					<input type="text" name="license" id="license" value="<?php echo isset($license) ? $license : "";?>" 
						placeholder="<?php echo $this->lang->line_arr('contractor->input_form->license_ph'); ?>" <?php  echo $required; ?> >
				</td>
			</tr>
			<!-- <tr>
				<td class="label <?php echo $notMandatory;?>"><?php echo $this->lang->line_arr('contractor->input_form->bbb'); ?></td>
				<td>
					<input type="text" name="bbb" id="bbb" value="<?php echo isset($bbb) ? $bbb : "";?>" placeholder="<?php echo $this->lang->line_arr('contractor->input_form->bbb_ph'); ?>" <?php  echo $required; ?> >
				</td>
			</tr> -->
			<?php 
			if($role_disp_name == "admin") {
			?>
			<tr>
				<td class="label <?php echo $notMandatory;?>"><?php echo $this->lang->line_arr('contractor->input_form->status'); ?></td>
				<td>
					<select name="status" id="status" <?php  echo $required; ?> >
						<option><?php echo $this->lang->line_arr('contractor->input_form->status_option_0')?></option>
						<option value="active">Active</option>
						<option value="inactive">Inactive</option>
					</select>
				</td>
			</tr>
			<?php
			}
			?>

			<?php
				echo $addressFile;
			?>
			<tr>
				<td class="label <?php echo $notMandatory;?>"><?php echo $this->lang->line_arr('contractor->input_form->emailId'); ?>:</td>
				<td>
					<input type="email" name="emailId" id="emailId" value="<?php echo isset($office_email) ? $office_email : "";?>" 
						placeholder="<?php echo $this->lang->line_arr('contractor->input_form->emailId_ph'); ?>" <?php  echo $required; ?> >
				</td>
			</tr>
			<tr>
				<td class="label <?php echo $notMandatory;?>"><?php echo $this->lang->line_arr('contractor->input_form->contactPhoneNumber'); ?>:</td>
				<td>
					<input type="text" name="contactPhoneNumber" id="contactPhoneNumber" value="<?php echo isset($office_ph) ? $office_ph : "";?>" 
						placeholder="<?php echo $this->lang->line_arr('contractor->input_form->contactPhoneNumber_ph'); ?>" <?php  echo $required; ?> >
				</td>
			</tr>
			<tr>
				<td class="label <?php echo $notMandatory;?>"><?php echo $this->lang->line_arr('contractor->input_form->mobileNumber'); ?>:</td>
				<td>
					<input type="text" name="mobileNumber" id="mobileNumber" value="<?php echo isset($mobile_ph) ? $mobile_ph : "";?>" 
						placeholder="<?php echo $this->lang->line_arr('contractor->input_form->mobileNumber_ph'); ?>" <?php  echo $required; ?> >
				</td>
			</tr>
			<!-- <tr>
				<td class="label <?php echo $notMandatory;?> prefMode"><?php echo $this->lang->line_arr('contractor->input_form->prefMode'); ?>:</td>
				<td>
					<table class="innerOption">
						<tr>
							<td><input type="checkbox" name="prefContact" id="prefContactEmailId" value="emailId"></td>
							<td><?php echo $this->lang->line_arr('contractor->input_form->prefContactEmailId'); ?></td>
							<td><input type="checkbox" name="prefContact" id="prefContactofficeNumber" value="officeNumber"></td>
							<td><?php echo $this->lang->line_arr('contractor->input_form->prefContactofficeNumber'); ?></td>
							<td><input type="checkbox" name="prefContact" id="prefContactMobileNumber" value="mobileNumber"></td>
							<td><?php echo $this->lang->line_arr('contractor->input_form->prefContactMobileNumber'); ?></td>
						</tr>
					</table>
				</td>
			</tr> -->
			<tr>
				<td class="label <?php echo $notMandatory;?>"><?php echo $this->lang->line_arr('contractor->input_form->websiteURL'); ?>:</td>
				<td>
					<input type="text" name="websiteURL" id="websiteURL" value="<?php echo isset($website_url) ? $website_url : "";?>" placeholder="<?php echo $this->lang->line_arr('contractor->input_form->websiteURL_ph'); ?>" <?php  echo $required; ?> >
				</td>
			</tr>
			<tr>
				<td class="label <?php echo $notMandatory;?>"><?php echo $this->lang->line_arr('contractor->input_form->serviceZip'); ?>:</td>
				<td>
					<textarea name="serviceZip" id="serviceZip" class="small-textarea" placeholder="<?php echo $this->lang->line_arr('contractor->input_form->serviceZip_ph'); ?>" <?php  echo $required; ?> ><?php echo isset($service_area) ? $service_area : ""; ?></textarea>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<p class="button-panel">
						<button type="button" id="<?php echo $prefix; ?>_contractor_submit" 
							onclick="_contractors.<?php echo $prefix; ?>Validate(<?php echo $validateOptions; ?>)">
								<?php echo $this->lang->line_arr('contractor->buttons_links->'.$prefix); ?>
						</button>
						<?php
						if($openAs == "popup") {
						?>
						<button type="button" id="cancelButton" 
							onclick="_projects.closeDialog({popupType: '<?php echo $popupType; ?>'})">
								<?php echo $this->lang->line_arr('buttons->cancel'); ?>
							</button>
						<?php
						} else {
						?>
						<button type="reset" id="resetButton" onclick=""><?php echo $this->lang->line_arr('buttons->reset'); ?></button>
						<?php	
						}
						?>
					</p>
				</td>
			</tr>
			<tr>
				<td colspan="2"><?php echo $this->lang->line('mandatory_field_text'); ?></td>
			</tr>
		</tbody>
	</table>
</form>