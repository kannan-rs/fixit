<!-- Add Users Start -->
<h2>Enroll to Fixit</h2>
	<form id="signup_user_form" name="signup_user_form">
	<DIV class='form'>
		<DIV class="label">First Name:</DIV>
		<DIV>
			<input type="text" name="firstName" id="firstName" value="" placeholder="First Name" required>
		</DIV>
		<DIV class="label">Last Name:</DIV>
		<DIV>
			<input type="text" name="lastName" id="lastName" value="" placeholder="Last Name" required>
		</DIV>
		<DIV class="label">Password</DIV>
		<DIV><input type="password" name="password" id="password" value="" placeholder="Password" required></DIV>
		<DIV class="label">Confirm Password:</DIV>
		<DIV><input type="password" name="confirmPassword" id="confirmPassword" value="" placeholder="Confirm Password" required></DIV>
		<DIV class="label">Password Hint:</DIV>
		<DIV>
			<input type="text" name="passwordHint" id="passwordHint" value="" placeholder="Password Hint">
		</DIV>
		<DIV class="label">User Belongs To:</DIV>
		<DIV>
			<select name="belongsTo" id="belongsTo" onchange="homeObj.showContractor(this.value)">
				<option value="">--Select Belongs To--</option>
				<option value="customer">Customer</option>
				<option value="contractor">Contractor</option>
				<option value="adjuster">Adjuster</option>
			</select>
		</DIV>
		<DIV class="contractorDetails">
			<DIV class="label">Select Contractor</DIV>
			<DIV>
				<select name="contractorId" id="contractorId" onchange="homeObj.showContractorCompany(this.value);">
					<option value="">--Select Contractor--</option>
				</select>
			</DIV>
			<DIV class="contractorCompany">
				<DIV class="label">Contractor Company Name:</DIV>
				<DIV class="contractorCompanyInfo">
				</DIV>
			</DIV>
		</DIV>
		<!-- <DIV class="label">User Type:</DIV>
		<DIV>
			<input type="text" name="userType" id="userType" value="" placeholder="User Type" required>
		</DIV>
		<DIV class="label">User Status:</DIV>
		<DIV>
			<input type="text" name="userStatus" id="userStatus" value="" placeholder="User Status" required>
		</DIV> -->
		<DIV class="label">Email ID:</DIV>
		<DIV>
			<input type="email" name="emailId" id="emailId" value="" placeholder="Email ID" required>
		</DIV>
		<DIV class="label">Contact Phone Number:</DIV>
		<DIV>
			<input type="number" name="contactPhoneNumber" id="contactPhoneNumber" value="" placeholder="Contact Phone Number" required>
		</DIV>
		<DIV class="label">Mobile Number:</DIV>
		<DIV>
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
		</DIV>
		<DIV class="label">Alternate Number:</DIV>
		<DIV>
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
		</DIV>
		<DIV class="label prefMode">Prefered Mode for Contact:</DIV>
		<DIV>
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
		</DIV>
		<DIV class="label">Flat No:</DIV>
		<DIV>
			<input type="text" name="addressLine1" id="addressLine1" value="" placeholder="Address Line 1:" required>
		</DIV>
		<DIV class="label">Building Name:</DIV>
		<DIV>
			<input type="text" name="addressLine2" id="addressLine2" value="" placeholder="Address Line 2" required>
		</DIV>
		<DIV class="label">Street:</DIV>
		<DIV>
			<input type="text" name="addressLine3" id="addressLine3" value="" placeholder="Address Line 3" >
		</DIV>
		<DIV class="label">Main:</DIV>
		<DIV>
			<input type="text" name="addressLine4" id="addressLine4" value="" placeholder="Address Line 4" >
		</DIV>
		<DIV class="label">City:</DIV>
		<DIV>
			<input type="text" name="city" id="city" value="" placeholder="City" required>
		</DIV>
		<DIV class="label">Country:</DIV>
		<DIV>
			<select name="country" id="country" required onchange="formUtilObj.populateState(this.value, 'state');">
				<option value="">--Select Country--</option>
			</select>
		</DIV>
		<DIV class="label">State:</DIV>
		<DIV>
			<select name="state" id="state" required>
				<option value="">--Select State--</option>
			</select>
		</DIV>
		<DIV class="label">Pin Code:</DIV>
		<DIV>
			<input type="text" name="pinCode" id="pinCode" value="" placeholder="Pin Code" required>
		</DIV>
		<p class="button-panel">
			<button type="button" id="signup_user_submit" onclick="homeObj.signupValidate()">Signup</button>
		</p>
	</DIV>
</form>
<!-- Add Users Ends -->