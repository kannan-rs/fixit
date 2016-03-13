<?php
	$partner  = isset($partners) && is_array($partners) ? $partners[0] : null;

	$edit = false;
	$prefix = "create";
	if($partner) {
		$edit = true;
		$prefix = "update";

		$id 							= $partner->id;
		$name 							= $partner->name;
		$company_name 					= $partner->company_name;
		$type 							= $partner->type;
		$license 						= $partner->license;
		$status 						= $partner->status;
		$work_phone 					= $partner->work_phone;
		$work_email_id 					= $partner->work_email_id;
		$mobile_no 						= $partner->mobile_no;
		$personal_email_id 				= $partner->personal_email_id;
		$contact_pref 					= $partner->contact_pref;
		$website_url 					= $partner->website_url;
		$default_contact_user_id		= $partner->default_contact_user_id;
		$default_contact_user_disp_str	= isset($partner->default_contact_user_disp_str) ? $partner->default_contact_user_disp_str : "";
	}
?>
<?php
if(!$edit && (!$openAs || $openAs != "popup")) {
	echo "<div class=\"header-options\"><h2>".$this->lang->line_arr('partner->headers->'.$prefix)."</h2></div>";
}
?>
<form id="<?php echo $prefix ?>_partner_form" name="<?php echo $prefix ?>_partner_form" class="inputForm">
	
	<input type="hidden" id='partnerId' value="<?php echo isset($id) ? $id : "" ; ?>" />
	<input type="hidden" name="statusDb" id="statusDb" value="<?php echo isset($status) ? $status : "" ; ?>">
	<input type="hidden" name="prefContactDb" id="prefContactDb" value="<?php echo isset($contact_pref) ? $contact_pref : "" ; ?>" />
	<input type="hidden" name = "db_default_user_id" id= "db_default_user_id" value="<?php echo isset($default_contact_user_id) ? $default_contact_user_id : ""; ?>" />

	<table class='form'>
		<tbody>
			<!-- <tr>
				<td class="label"><?php echo $this->lang->line_arr('partner->input_form->name'); ?>:</td>
				<td>
					<input type="text" name="name" id="name" value="<?php echo isset($name) ? $name : "" ;?>" required 
					placeholder="<?php echo $this->lang->line_arr('partner->input_form->name_ph'); ?>">
				</td>
			</tr> -->
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('partner->input_form->company'); ?>:</td>
				<td>
					<input type="text" name="company" id="company" value="<?php echo isset($company_name) ? $company_name : "" ;?>" required placeholder="<?php echo $this->lang->line_arr('partner->input_form->company_ph'); ?>">
				</td>
			</tr>
			<?php
			if($role_disp_name == ROLE_ADMIN) {
			?>
			<!-- Search for default admin user -->
			<tr>
				<td class="label notMandatory"><?php echo $this->lang->line_arr('partner->input_form->searchForDefaultAdjuster'); ?></td>
				<td>
					<input type="text" id="searchForDefaultAdjuster" name="searchForDefaultAdjuster" 
						placeholder="<?php echo $this->lang->line_arr('partner->input_form->searchForDefaultAdjuster_ph'); ?>" 
						onkeyup="_partners.searchUserByEmail({ emailId : this.value })" 
						value="<?php echo isset($default_contact_user_disp_str) ? $default_contact_user_disp_str : ""; ?>">
				</td>
			</tr>
			<tr class="default-user-search-result">
			<td  class="label notMandatory">&nbsp;</td>
				<td>
					<ul id="adjusterUserList" class="connectedSortable dropdown"></ul>
				</td>
			</tr>
			<?php
			}
			?>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('partner->input_form->type'); ?></td>
				<td>
					<input type="text" name="type" id="type" value="<?php echo isset($type) ? $type : "" ;?>" placeholder="<?php echo $this->lang->line_arr('partner->input_form->type_ph'); ?>" required>
				</td>
			</tr>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('partner->input_form->license'); ?></td>
				<td>
					<input type="text" name="license" id="license" value="<?php echo isset($license) ? $license : "" ;?>" placeholder="<?php echo $this->lang->line_arr('partner->input_form->license_ph'); ?>" required>
				</td>
			</tr>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('partner->input_form->status'); ?></td>
				<td>
					<select name="status" id="status" required>
						<option><?php echo $this->lang->line_arr('partner->input_form->status_option_0'); ?></option>
						<option value="active">Active</option>
						<option value="inactive">Inactive</option>
					</select>
				</td>
			</tr>

			<?php
				echo $addressFile;
			?>

			<tr>
				<td class="label"><?php echo $this->lang->line_arr('partner->input_form->wNumber'); ?>:</td>
				<td>
					<input type="text" name="wNumber" id="wNumber" value="<?php echo isset($work_phone) ? $work_phone : "" ;?>" placeholder="<?php echo $this->lang->line_arr('partner->input_form->wNumber_ph'); ?>" required>
				</td>
			</tr>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('partner->input_form->wEmailId'); ?>:</td>
				<td>
					<input type="email" name="wEmailId" id="wEmailId" value="<?php echo isset($work_email_id) ? $work_email_id : "" ;?>" placeholder="<?php echo $this->lang->line_arr('partner->input_form->wEmailId_ph'); ?>" required>
				</td>
			</tr>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('partner->input_form->pNumber'); ?>:</td>
				<td>
					<input type="text" name="pNumber" id="pNumber" value="<?php echo isset($mobile_no) ? $mobile_no : "" ;?>" placeholder="<?php echo $this->lang->line_arr('partner->input_form->pNumber_ph'); ?>" required>
				</td>
			</tr>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('partner->input_form->pEmailId'); ?></td>
				<td>
					<input type="email" name="pEmailId" id="pEmailId" value="<?php echo isset($personal_email_id) ? $personal_email_id : "" ; ?>" placeholder="<?php echo $this->lang->line_arr('partner->input_form->pEmailId_ph'); ?>" required>
				</td>
			</tr>

			<!-- <tr>
				<td class="label prefMode"><?php echo $this->lang->line_arr('partner->input_form->prefMode'); ?></td>
				<td>
					<table class="innerOption">
						<tr>
							<td><input type="checkbox" name="prefContact" id="prefwNumber" value="wNumber"></td>
							<td><?php echo $this->lang->line_arr('partner->input_form->prefwNumber'); ?></td>
							<td><input type="checkbox" name="prefContact" id="prefwEmailId" value="wEmailId"></td>
							<td><?php echo $this->lang->line_arr('partner->input_form->prefwEmailId'); ?></td>
						</tr>
						<tr>
							<td><input type="checkbox" name="prefContact" id="prefmNumber" value="mNumber"></td>
							<td><?php echo $this->lang->line_arr('partner->input_form->prefmNumber'); ?></td>
							<td><input type="checkbox" name="prefContact" id="prefwEmailId" value="pEmailId"></td>
							<td><?php echo $this->lang->line_arr('partner->input_form->prefwEmailId'); ?></td>
						</tr>
					</table>
				</td>
			</tr> -->
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('partner->input_form->websiteURL'); ?>:</td>
				<td>
					<input type="text" name="websiteURL" id="websiteURL" value="<?php echo isset($website_url) ? $website_url : "" ;?>" placeholder="<?php echo $this->lang->line_arr('partner->input_form->websiteURL_ph'); ?>" required>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<p class="button-panel">
						<button type="button" id="create_partner_submit" onclick="_partners.<?php echo $prefix; ?>Validate('<?php echo $openAs; ?>', '<?php echo $popupType;?>')"><?php echo $this->lang->line_arr('partner->buttons_links->'.$prefix); ?></button>
						<?php
							if($openAs == "popup") {
						?>
						<button type="button" id="cancelButton" onclick="_projects.closeDialog({popupType: '<?php echo $popupType; ?>'})"><?php echo $this->lang->line_arr('buttons->cancel'); ?></button>
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
