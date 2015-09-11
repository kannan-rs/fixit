<?php
	$partner  = $partners[0];
?>
<form id="update_partner_form" name="update_partner_form" class="inputForm">
	<input type="hidden" id='partnerId' value="<?php echo $partner->id; ?>" />
	<div class='form'>
		<div class="label">Name:</div>
		<div>
			<input type="text" name="name" id="name" value="<?php echo $partner->name;?>" required placeholder="Partner Name">
		</div>
		<div class="label">Company:</div>
		<div>
			<input type="text" name="company" id="company" value="<?php echo $partner->company_name;?>" required placeholder="Company Name">
		</div>
		<div class="label">Type</div>
		<div>
			<input type="text" name="type" id="type" value="<?php echo $partner->type;?>" placeholder="Partner Type" required>
		</div>
		<div class="label">License</div>
		<div>
			<input type="text" name="license" id="license" value="<?php echo $partner->license;?>" placeholder="Partner License" required>
		</div>
		<div class="label">Status</div>
		<div>
			<input type="hidden" name="statusDb" id="statusDb" value="<?php echo $partner->status; ?>">
			<select name="status" id="status" required>
				<option>--Select Status--</option>
				<option value="active">Active</option>
				<option value="inactive">Inactive</option>
			</select>
		</div>

		<?php
			echo $addressFile;
		?>

		<div class="label">Office Number:</div>
		<div>
			<input type="number" name="wNumber" id="wNumber" value="<?php echo $partner->work_phone;?>" placeholder="Contact Phone Number" required>
		</div>
		<div class="label">Office Email ID:</div>
		<div>
			<input type="email" name="wEmailId" id="wEmailId" value="<?php echo $partner->work_email_id;?>" placeholder="Email ID" required>
		</div>
		<div class="label">Mobile Number:</div>
		<div>
			<input type="number" name="pNumber" id="pNumber" value="<?php echo $partner->mobile_no;?>" placeholder="Contact Mobile Number" required>
		</div>
		<div class="label">Personal Email ID:</div>
		<div>
			<input type="email" name="pEmailId" id="pEmailId" value="<?php echo $partner->personal_email_id; ?>" placeholder="Personal Email ID" required>
		</div>

		<div class="label prefMode">Prefered Mode for Contact:</div>
		<div>
			<input type="hidden" name="prefContactDb" id="prefContactDb" value="<?php echo $partner->contact_pref; ?>" />
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
		</div>
		<div class="label">Website URL:</div>
		<div>
			<input type="text" name="websiteURL" id="websiteURL" value="<?php echo $partner->website_url;?>" placeholder="Website URL" required>
		</div>
		<p class="button-panel">
			<button type="button" id="create_partner_submit" onclick="projectObj._partners.updateValidate()">Update Partner</button>
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
	</div>
</form>
