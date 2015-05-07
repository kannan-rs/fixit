<!-- Add Users Start -->
<h2>Create Users</h3>
<form id="create_user_form" name="create_user_form">
	<div class='form'>
		<div class="label">Privilege:</div>
		<div>
			<select name="privilege" id="privilege">
				<option value="">--Select Privilege--</option>
				<option value="1">Admin</option>
				<option value="2">User</option>
			</select>
		</div>
		<div class="label">First Name:</div>
		<div>
			<input type="text" name="firstName" id="firstName" value="" placeholder="First Name" required>
		</div>
		<div class="label">Last Name:</div>
		<div>
			<input type="text" name="lastName" id="lastName" value="" placeholder="Last Name" required>
		</div>
		<div class="label">Password</div>
		<div><input type="password" name="password" id="password" value="" placeholder="Password" required></div>
		<div class="label">Confirm Password:</div>
		<div><input type="password" name="confirmPassword" id="confirmPassword" value="" placeholder="Confirm Password" required></div>
		<div class="label">Password Hint:</div>
		<div>
			<input type="text" name="passwordHint" id="passwordHint" value="" placeholder="Password Hint">
		</div>
		<div class="label">User Belongs To:</div>
		<div>
			<input type="text" name="belongsTo" id="belongsTo" value="" placeholder="User Belongs To" required>
		</div>
		<div class="label">User Type:</div>
		<div>
			<input type="text" name="userType" id="userType" value="" placeholder="User Type" required>
		</div>
		<div class="label">User Status:</div>
		<div>
			<input type="text" name="userStatus" id="userStatus" value="" placeholder="User Status" required>
		</div>
		<div class="label">Email ID:</div>
		<div>
			<input type="email" name="emailId" id="emailId" value="" placeholder="Email ID" required>
		</div>
		<div class="label">Contact Phone Number:</div>
		<div>
			<input type="number" name="contactPhoneNumber" id="contactPhoneNumber" value="" placeholder="Contact Phone Number" required>
		</div>
		<div class="label">Mobile Number:</div>
		<div>
			<table  class="innerOption">
				<tr>
					<td colspan="2">
						<input type="number" name="mobileNumber" id="mobileNumber" value="" placeholder="Mobile Number">
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
						<input type="number" name="altNumber" id="altNumber" value="" placeholder="alternate">
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
		<p class="button-panel">
			<button type="button" id="create_user_submit" onclick="securityObj._users.createValidate()">Create User</button>
		</p>
	</div>
</form>
<!-- Add Users Ends -->