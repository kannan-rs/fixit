<?php
	$ins_comp  = isset($ins_comps) && is_array($ins_comps) ? $ins_comps[0] : null;

	$edit = false;
	$prefix = "create";
	if($ins_comp) {
		$edit = true;
		$prefix = "update";

		$id 							= $ins_comp->ins_comp_id;
		$company 						= $ins_comp->ins_comp_name;
		$email_id 						= $ins_comp->email_id;
		$contact_no 					= $ins_comp->contact_no;
		$website_url 					= $ins_comp->website_url;
		$default_contact_user_id		= $ins_comp->default_contact_user_id;
		$default_contact_user_disp_str	= isset($ins_comp->default_contact_user_disp_str) ? $ins_comp->default_contact_user_disp_str : "";

	}

	$notMandatory 	= $role_disp_name == ROLE_ADMIN ? "notMandatory" : "";
	$required		= $role_disp_name == ROLE_ADMIN ? " " : "required";
?>

<?php
if(!$edit && (!$openAs || $openAs != "popup")) {
	echo "<div class=\"header-options\"><h2 class='page-header'>".$this->lang->line_arr('ins_comp->headers->'.$prefix)."</h2></div>";
}

$validateOptions = "";
if($openAs) {
	$validateOptions = "'".$openAs."', '".$popupType."'";
}
?>

<form id="<?php echo $prefix; ?>_ins_comp_form" name="<?php echo $prefix; ?>_ins_comp_form" class="inputForm">
	
	<input type="hidden" id='ins_comp_id' value="<?php echo isset($id) ? $id : ""; ?>" />
	<input type="hidden" name = "db_default_user_id" id= "db_default_user_id" value="<?php echo isset($default_contact_user_id) ? $default_contact_user_id : ""; ?>" />

	<table class='form'>
		<tbody>
			<!-- Company name -->
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('ins_comp->input_form->company'); ?>:</td>
				<td>
					<input type="text" name="company" id="company" value="<?php echo isset($company) ? $company : "";?>" required 
						placeholder="<?php echo $this->lang->line_arr('ins_comp->input_form->company_ph'); ?>">
				</td>
			</tr>
			<?php
			if($role_disp_name == ROLE_ADMIN) {
			?>
			<!-- Search for default admin user -->
			<tr>
				<td class="label notMandatory"><?php echo $this->lang->line_arr('ins_comp->input_form->search_for_default_user'); ?></td>
				<td>
					<input type="text" id="search_for_default_user" name="search_for_default_user" 
						placeholder="<?php echo $this->lang->line_arr('ins_comp->input_form->search_for_default_user_ph'); ?>" 
						onkeyup="_ins_comp.searchUserByEmail({ emailId : this.value })" 
						value="<?php echo isset($default_contact_user_disp_str) ? $default_contact_user_disp_str : ""; ?>">
				</td>
			</tr>
			<tr class="default-user-search-result">
			<td  class="label notMandatory">&nbsp;</td>
				<td>
					<ul id="ins_compUserList" class="connectedSortable dropdown"></ul>
				</td>
			</tr>
			<?php
			}
			?>
			<!-- Contact details -->
			<tr>
				<td class="label <?php echo $notMandatory;?>"><?php echo $this->lang->line_arr('ins_comp->input_form->emailId'); ?>:</td>
				<td>
					<input type="email" name="emailId" id="emailId" value="<?php echo isset($email_id) ? $email_id : "";?>" 
						placeholder="<?php echo $this->lang->line_arr('ins_comp->input_form->emailId_ph'); ?>" <?php  echo $required; ?> >
				</td>
			</tr>
			<tr>
				<td class="label <?php echo $notMandatory;?>"><?php echo $this->lang->line_arr('ins_comp->input_form->contactPhoneNumber'); ?>:</td>
				<td>
					<input type="text" name="contactPhoneNumber" id="contactPhoneNumber" value="<?php echo isset($contact_no) ? $contact_no : "";?>" 
						placeholder="<?php echo $this->lang->line_arr('ins_comp->input_form->contactPhoneNumber_ph'); ?>" <?php  echo $required; ?> >
				</td>
			</tr>
			<!-- Address -->
			<?php
				echo $addressFile;
			?>
			<tr>
				<td class="label <?php echo $notMandatory;?>"><?php echo $this->lang->line_arr('ins_comp->input_form->websiteURL'); ?>:</td>
				<td>
					<input type="text" name="websiteURL" id="websiteURL" value="<?php echo isset($website_url) ? $website_url : "";?>" placeholder="<?php echo $this->lang->line_arr('ins_comp->input_form->websiteURL_ph'); ?>" <?php  echo $required; ?> >
				</td>
			</tr>
			<!-- Action buttons -->
			<tr>
				<td colspan="2">
					<p class="button-panel">
						<button type="button" id="<?php echo $prefix; ?>_insurance_provider_submit" 
							onclick="_ins_comp.<?php echo $prefix; ?>Validate(<?php echo $validateOptions; ?>)">
								<?php echo $this->lang->line_arr('ins_comp->buttons_links->'.$prefix); ?>
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
