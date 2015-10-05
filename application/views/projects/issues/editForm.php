<?php
	$contractor  = $contractors[0];
?>
<form id="update_contractor_form" name="update_contractor_form" class="inputForm">
	<input type="hidden" id='contractorId' value="<?php echo $contractor->id; ?>" />
	<div class='form'>
		<div class="label">Name:</div>
		<div>
			<input type="text" name="name" id="name" value="<?php echo $contractor->name;?>" required placeholder="Contractor Name">
		</div>
		<div class="label">Company:</div>
		<div>
			<input type="text" name="company" id="company" value="<?php echo $contractor->company;?>" required placeholder="Company Name">
		</div>
		<div class="label">Type</div>
		<div>
			<input type="text" name="type" id="type" value="<?php echo $contractor->type;?>" placeholder="Contractor Type" required>
		</div>
		<div class="label">License</div>
		<div>
			<input type="text" name="license" id="license" value="<?php echo $contractor->license;?>" placeholder="Contractor License" required>
		</div>
		<div class="label">BBB</div>
		<div>
			<input type="text" name="bbb" id="bbb" value="<?php echo $contractor->bbb;?>" placeholder="BBB" required>
		</div>
		<div class="label">Status</div>
		<div>
			<input type="hidden" name="statusDb" id="statusDb" value="<?php echo $contractor->status; ?>">
			<select name="status" id="status" required>
				<option>--Select Status--</option>
				<option value="active">Active</option>
				<option value="inactive">Inactive</option>
			</select>
		</div>

		<?php
			echo $addressFile;
		?>
		<div class="label">Office Email ID:</div>
		<div>
			<input type="email" name="emailId" id="emailId" value="<?php echo $contractor->office_email;?>" placeholder="Email ID" required>
		</div>
		<div class="label">Office Number:</div>
		<div>
			<input type="text" name="contactPhoneNumber" id="contactPhoneNumber" value="<?php echo $contractor->office_ph;?>" placeholder="Contact Phone Number" required>
		</div>
		<div class="label">Mobile Number:</div>
		<div>
				<input type="text" name="mobileNumber" id="mobileNumber" value="<?php echo $contractor->mobile_ph;?>" placeholder="Contact Mobile Number" required>
		</div>
		<div class="label prefMode">Prefered Mode for Contact:</div>
		<div>
			<input type="hidden" name="prefContactDb" id="prefContactDb" value="<?php echo $contractor->prefer; ?>" />
			<table class="innerOption">
				<tr>
					<td><input type="checkbox" name="prefContact" id="prefContactEmailId" value="emailId"></td>
					<td>Email</td>
					<td><input type="checkbox" name="prefContact" id="prefContactofficeNumber" value="officeNumber"></td>
					<td>Office Phone</td>
					<td><input type="checkbox" name="prefContact" id="prefContactMobileNumber" value="mobileNumber"></td>
					<td>Mobile Number</td>
				</tr>
			</table>
		</div>
		<div class="label">Website URL:</div>
		<div>
			<input type="text" name="websiteURL" id="websiteURL" value="<?php echo $contractor->website_url;?>" placeholder="Website URL" required>
		</div>
		<div class="label">Zip codes of Available Service Area:</div>
		<div>
			<textarea name="serviceZip" id="serviceZip" class="small-textarea" required><?php echo $contractor->service_area; ?></textarea>
		</div>
		<p class="button-panel">
			<button type="button" id="create_contractor_submit" onclick="projectObj._contractors.updateValidate()">Update Contractor</button>
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
