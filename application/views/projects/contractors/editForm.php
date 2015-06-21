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
			<input type="text" name="type" id="type" value="<?php echo $contractor->type;?>" placeholder="Contractor Type">
		</div>
		<div class="label">License</div>
		<div>
			<input type="text" name="license" id="license" value="<?php echo $contractor->license;?>" placeholder="Contractor License">
		</div>
		<div class="label">BBB</div>
		<div>
			<input type="text" name="bbb" id="bbb" value="<?php echo $contractor->bbb;?>" placeholder="BBB">
		</div>
		<div class="label">Status</div>
		<div>
			<input type="text" name="status" id="status" value="<?php echo $contractor->status;?>" placeholder="Status">
		</div>
		<div class="label">Flat No:</div>
		<div>
			<input type="text" name="addressLine1" id="addressLine1" value="<?php echo $contractor->address1;?>" placeholder="Address Line 1:" required>
		</div>
		<div class="label">Building Name:</div>
		<div>
			<input type="text" name="addressLine2" id="addressLine2" value="<?php echo $contractor->address2;?>" placeholder="Address Line 2" required>
		</div>
		<div class="label">Street:</div>
		<div>
			<input type="text" name="addressLine3" id="addressLine3" value="<?php echo $contractor->address3;?>" placeholder="Address Line 3" >
		</div>
		<div class="label">Main:</div>
		<div>
			<input type="text" name="addressLine4" id="addressLine4" value="<?php echo $contractor->address4;?>" placeholder="Address Line 4" >
		</div>
		<div class="label">City:</div>
		<div>
			<input type="text" name="city" id="city" value="<?php echo $contractor->city;?>" placeholder="City" required>
		</div>
		<div class="label">State:</div>
		<div>
			<input type="text" name="state" id="state" value="<?php echo $contractor->state;?>" placeholder="State" required>
		</div>
		<div class="label">Country:</div>
		<div>
			<input type="text" name="country" id="country" value="<?php echo $contractor->country;?>" placeholder="Country" required>
		</div>
		<div class="label">Pin Code:</div>
		<div>
			<input type="text" name="pinCode" id="pinCode" value="<?php echo $contractor->pin_code;?>" placeholder="Pin Code" required>
		</div>
		<div class="label">Office Email ID:</div>
		<div>
			<input type="email" name="emailId" id="emailId" value="<?php echo $contractor->office_email;?>" placeholder="Email ID" required>
		</div>
		<div class="label">Office Number:</div>
		<div>
			<input type="number" name="contactPhoneNumber" id="contactPhoneNumber" value="<?php echo $contractor->office_ph;?>" placeholder="Contact Phone Number" required>
		</div>
		<div class="label">Mobile Number:</div>
		<div>
				<input type="number" name="mobileNumber" id="mobileNumber" value="<?php echo $contractor->mobile_ph;?>" placeholder="Contact Mobile Number" required>
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
		</p>
	</div>
</form>
