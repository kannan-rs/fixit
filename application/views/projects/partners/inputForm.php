<?php
	$partner  = isset($partners) && is_array($partners) ? $partners[0] : null;

	$edit = false;
	$prefix = "create";
	if($partner) {
		$edit = true;
		$prefix = "update";

		$id 				= $partner->id;
		$name 				= $partner->name;
		$company_name 		= $partner->company_name;
		$type 				= $partner->type;
		$license 			= $partner->license;
		$status 			= $partner->status;
		$work_phone 		= $partner->work_phone;
		$work_email_id 		= $partner->work_email_id;
		$mobile_no 			= $partner->mobile_no;
		$personal_email_id 	= $partner->personal_email_id;
		$contact_pref 		= $partner->contact_pref;
		$website_url 		= $partner->website_url;
	}
?>
<?php
if(!$edit && (!$openAs || $openAs != "popup")) {
	echo "<h2>".$prefix." Partner</h2>";
}
?>
<form id="<?php echo $prefix ?>_partner_form" name="<?php echo $prefix ?>_partner_form" class="inputForm">
	<input type="hidden" id='partnerId' value="<?php echo isset($id) ? $id : "" ; ?>" />
	<input type="hidden" name="statusDb" id="statusDb" value="<?php echo isset($status) ? $status : "" ; ?>">
	<input type="hidden" name="prefContactDb" id="prefContactDb" value="<?php echo isset($contact_pref) ? $contact_pref : "" ; ?>" />
	<table class='form'>
		<tbody>
			<tr>
				<td class="label">Partner Name:</td>
				<td>
					<input type="text" name="name" id="name" value="<?php echo isset($name) ? $name : "" ;?>" required placeholder="Partner Name">
				</td>
			</tr>
			<tr>
				<td class="label">Company Name:</td>
				<td>
					<input type="text" name="company" id="company" value="<?php echo isset($company_name) ? $company_name : "" ;?>" required placeholder="Company Name">
				</td>
			</tr>
			<tr>
				<td class="label">Type</td>
				<td>
					<input type="text" name="type" id="type" value="<?php echo isset($type) ? $type : "" ;?>" placeholder="Partner Type" required>
				</td>
			</tr>
			<tr>
				<td class="label">License</td>
				<td>
					<input type="text" name="license" id="license" value="<?php echo isset($license) ? $license : "" ;?>" placeholder="Partner License" required>
				</td>
			</tr>
			<tr>
				<td class="label">Status</td>
				<td>
					<select name="status" id="status" required>
						<option>--Select Status--</option>
						<option value="active">Active</option>
						<option value="inactive">Inactive</option>
					</select>
				</td>
			</tr>

			<?php
				echo $addressFile;
			?>

			<tr>
				<td class="label">Office Number:</td>
				<td>
					<input type="text" name="wNumber" id="wNumber" value="<?php echo isset($work_phone) ? $work_phone : "" ;?>" placeholder="Office Phone Number" required>
				</td>
			</tr>
			<tr>
				<td class="label">Office Email ID:</td>
				<td>
					<input type="email" name="wEmailId" id="wEmailId" value="<?php echo isset($work_email_id) ? $work_email_id : "" ;?>" placeholder="Office Email ID" required>
				</td>
			</tr>
			<tr>
				<td class="label">Mobile Number:</td>
				<td>
					<input type="text" name="pNumber" id="pNumber" value="<?php echo isset($mobile_no) ? $mobile_no : "" ;?>" placeholder="Contact Mobile Number" required>
				</td>
			</tr>
			<tr>
				<td class="label">Personal Email ID:</td>
				<td>
					<input type="email" name="pEmailId" id="pEmailId" value="<?php echo isset($personal_email_id) ? $personal_email_id : "" ; ?>" placeholder="Personal Email ID" required>
				</td>
			</tr>

			<tr>
				<td class="label prefMode">Prefered Mode for Contact:</td>
				<td>
					<table class="innerOption">
						<tr>
							<td><input type="checkbox" name="prefContact" id="prefwNumber" value="wNumber"></td>
							<td>Office Phone Number</td>
							<td><input type="checkbox" name="prefContact" id="prefwEmailId" value="wEmailId"></td>
							<td>Office Email ID</td>
						</tr>
						<tr>
							<td><input type="checkbox" name="prefContact" id="prefmNumber" value="mNumber"></td>
							<td>Personal Mobile Number</td>
							<td><input type="checkbox" name="prefContact" id="prefwEmailId" value="pEmailId"></td>
							<td>Personal Email ID</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td class="label">Website URL:</td>
				<td>
					<input type="text" name="websiteURL" id="websiteURL" value="<?php echo isset($website_url) ? $website_url : "" ;?>" placeholder="Website URL" required>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<p class="button-panel">
						<button type="button" id="create_partner_submit" onclick="projectObj._partners.<?php echo $prefix; ?>Validate('<?php echo $openAs; ?>', '<?php echo $popupType;?>')"><?php echo $prefix; ?> Partner</button>
						<?php
							if($openAs == "popup") {
						?>
						<button type="button" id="cancelButton" onclick="projectObj._projects.closeDialog({popupType: '<?php echo $popupType; ?>'})">Cancel</button>
						<?php
						} else {
						?>
						<button type="reset" id="resetButton" onclick="">Reset</button>
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
