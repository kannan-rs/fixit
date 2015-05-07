<h2>Edit Personal Details</h3>
	<form id="user_details_form" name="user_details_form">
	<div class='form'>
		<input type="hidden" name="user_details_sno" id="user_details_sno" value="<?php echo $user_details[0]->sno; ?>">
		<input type="hidden" name="dbPrimaryContact" id="dbPrimaryContact" value="<?php echo $user_details[0]->primary_contact; ?>">
		<input type="hidden" name="dbPrefContact" id="dbPrefContact" value="<?php echo $user_details[0]->contact_pref; ?>">

		<div class="label">First Name:</div>
		<div>
			<input type="text" name="firstName" id="firstName" value="<?php echo $user_details[0]->first_name; ?>" placeholder="First Name" required>
		</div>
		<div class="label">Last Name:</div>
		<div>
			<input type="text" name="lastName" id="lastName" value="<?php echo $user_details[0]->last_name; ?>" placeholder="Last Name" required>
		</div>
		<div class="label">User Belongs To:</div>
		<div>
			<input type="text" name="belongsTo" id="belongsTo" value="<?php echo $user_details[0]->belongs_to; ?>" placeholder="User Belongs To" required>
		</div>
		<div class="label">User Type:</div>
		<div>
			<input type="text" name="userType" id="userType" value="<?php echo $user_details[0]->type; ?>" placeholder="User Type" required>
		</div>
		<div class="label">User Status:</div>
		<div>
			<input type="text" name="userStatus" id="userStatus" value="<?php echo $user_details[0]->status; ?>" placeholder="User Status" required>
		</div>
		<!--
		<div class="label">Active Start Date:</div>
		<div>
			<input type="date" name="activeStartDate" id="activeStartDate" value="<?php echo explode(" ",$user_details[0]->active_start_date)[0]; ?>" placeholder="Active Start Date" required>
		</div>
		<div class="label">Active End Date:</div>
		<div>
			<input type="date" name="activeEndDate" id="activeEndDate" value="<?php echo explode(" ",$user_details[0]->active_end_date)[0]; ?>" placeholder="Active End Date" required>
		</div>
		-->
		<div class="label">Email ID:</div>
		<div>
			<input type="email" name="emailId" id="emailId" value="<?php echo $user_details[0]->email; ?>" placeholder="Email ID" disabled required>
		</div>
		<div class="label">Contact Phone Number:</div>
		<div>
			<input type="number" name="contactPhoneNumber" id="contactPhoneNumber" value="<?php echo $user_details[0]->contact_ph1; ?>" placeholder="Contact Phone Number" required>
		</div>
		<div class="label">Mobile Number:</div>
		<div>
			<table  class="innerOption">
				<tr>
					<td colspan="2">
						<input type="number" name="mobileNumber" id="mobileNumber" value="<?php echo $user_details[0]->contact_mobile; ?>" placeholder="Mobile Number">
					</td>
				</tr>
				<tr>
					<td>
						<input type="radio" name="primaryContact" id="primaryMobileNumber" value="mobile">
					</td>
					<td><span>Set as Primary Contact Number</span></td>
				</tr>
			</table>
		</div>
		<div class="label">Alternate Number:</div>
		<div>
			<table class="innerOption">
				<tr>
					<td colspan="2">
						<input type="number" name="altNumber" id="altNumber" value="<?php echo $user_details[0]->contact_alt_mobile; ?>" placeholder="alternate">
					</td>
				</tr>
				<tr>
					<td>
						<input type="radio" name="primaryContact" id="primaryAlternateNumber" value="alternate">
					</td>
					<td><span>Set as Primary Contact Number</span></td>
				</tr>
			</table>
		</div>
		<div class="label prefMode">Prefered Mode for Contact:</div>
		<div>
			<table class="innerOption">
				<tr>
					<td><input type="checkbox" name="prefContact" id="prefContactEmailId" value="emailId"></td>
					<td>Email</td>
					<td><input type="checkbox" name="prefContact" id="prefContactContactPhoneNumber" value="contactPhoneNumber"></td>
					<td>Home Phone</td>
				</tr>
				<tr>
					<td><input type="checkbox" name="prefContact" id="prefContactMobileNumber" value="mobileNumber"></td>
					<td>Mobile Number</td>
					<td><input type="checkbox" name="prefContact" id="prefContactAltNumber" value="altNumber"></td>
					<td>Alternate Number</td>
				</tr>
			</table>
		</div>
		<div class="label">Flat No:</div>
		<div>
			<input type="text" name="addressLine1" id="addressLine1" value="<?php echo $user_details[0]->addr1; ?>" placeholder="Address Line 1:" required>
		</div>
		<div class="label">Building Name:</div>
		<div>
			<input type="text" name="addressLine2" id="addressLine2" value="<?php echo $user_details[0]->addr2; ?>" placeholder="Address Line 2" required>
		</div>
		<div class="label">Street:</div>
		<div>
			<input type="text" name="addressLine3" id="addressLine3" value="<?php echo $user_details[0]->addr3; ?>" placeholder="Address Line 3" >
		</div>
		<div class="label">Main:</div>
		<div>
			<input type="text" name="addressLine4" id="addressLine4" value="<?php echo $user_details[0]->addr4; ?>" placeholder="Address Line 4" >
		</div>
		<div class="label">City:</div>
		<div>
			<input type="text" name="city" id="city" value="<?php echo $user_details[0]->addr_city; ?>" placeholder="City" required>
		</div>
		<div class="label">State:</div>
		<div>
			<input type="text" name="state" id="state" value="<?php echo $user_details[0]->addr_state; ?>" placeholder="State" required>
		</div>
		<div class="label">Country:</div>
		<div>
			<input type="text" name="country" id="country" value="<?php echo $user_details[0]->addr_country; ?>" placeholder="Country" required>
		</div>
		<div class="label">Pin Code:</div>
		<div>
			<input type="text" name="pinCode" id="pinCode" value="<?php echo $user_details[0]->addr_pin;?>" placeholder="Pref" required>
		</div>
		<p class="button-panel">
			<button type="button" id="signup_user_submit" onclick="personalDetailsObj._userInfo.updateValidate()">Update</button>
		</p>
	</div>
</form>