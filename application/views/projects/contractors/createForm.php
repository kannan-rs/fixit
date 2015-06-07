<!-- Add Function Start -->
<h2>Create Contractor</h3>
<form id="create_contractor_form" name="create_contractor_form" class="inputForm">
	<div class='form'>
		<div class="label">Name:</div>
		<div>
			<input type="text" name="name" id="name" value="" required placeholder="Contractor Name">
		</div>
		<div class="label">Company:</div>
		<div>
			<input type="text" name="company" id="company" value="" required placeholder="Company Name">
		</div>
		<div class="label">Type</div>
		<div>
			<input type="text" name="type" id="type" value="" placeholder="Contractor Type">
		</div>
		<div class="label">License</div>
		<div>
			<input type="text" name="license" id="license" value="" placeholder="Contractor License">
		</div>
		<div class="label">BBB</div>
		<div>
			<input type="text" name="bbb" id="bbb" value="" placeholder="BBB">
		</div>
		<div class="label">Status</div>
		<div>
			<input type="text" name="status" id="status" value="" placeholder="Status">
		</div>
		<div class="label">Flat No:</div>
		<div>
			<input type="text" name="addressLine1" id="addressLine1" value="" placeholder="Address Line 1:" required>
		</div>
		<div class="label">Building Name:</div>
		<div>
			<input type="text" name="addressLine2" id="addressLine2" value="" placeholder="Address Line 2" required>
		</div>
		<div class="label">Street:</div>
		<div>
			<input type="text" name="addressLine3" id="addressLine3" value="" placeholder="Address Line 3" >
		</div>
		<div class="label">Main:</div>
		<div>
			<input type="text" name="addressLine4" id="addressLine4" value="" placeholder="Address Line 4" >
		</div>
		<div class="label">City:</div>
		<div>
			<input type="text" name="city" id="city" value="" placeholder="City" required>
		</div>
		<div class="label">State:</div>
		<div>
			<input type="text" name="state" id="state" value="" placeholder="State" required>
		</div>
		<div class="label">Country:</div>
		<div>
			<input type="text" name="country" id="country" value="" placeholder="Country" required>
		</div>
		<div class="label">Pin Code:</div>
		<div>
			<input type="text" name="pinCode" id="pinCode" value="" placeholder="Pin Code" required>
		</div>
		<div class="label">Office Email ID:</div>
		<div>
			<input type="email" name="emailId" id="emailId" value="" placeholder="Email ID" required>
		</div>
		<div class="label">Office Number:</div>
		<div>
			<input type="number" name="contactPhoneNumber" id="contactPhoneNumber" value="" placeholder="Contact Phone Number" required>
		</div>
		<div class="label">Mobile Number:</div>
		<div>
				<input type="number" name="mobileNumber" id="mobileNumber" value="" placeholder="Contact Mobile Number" required>
		</div>
		<div class="label prefMode">Prefered Mode for Contact:</div>
		<div>
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
			<input type="text" name="websiteURL" id="websiteURL" value="" placeholder="Website URL" required>
		</div>
		<p class="button-panel">
			<button type="button" id="create_contractor_submit" onclick="projectObj._contractors.createValidate()">Create Contractor</button>
		</p>
	</div>
</form>
<!-- Add Function Ends -->