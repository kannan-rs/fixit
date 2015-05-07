<h2>View User Details</h2>
<div class="create-link">
	<!--<a href="javascript:void(0);" onclick="personalDetailsObj._userInfo.editPage(<?php echo $user_details[$i]->sno; ?>);">Edit Details</a>-->
	<a href="javascript:void(0);" onclick="securityObj._users.createForm()">Create User</a>
</div>
<?php
	if(count($users) > 0 && count($user_details) > 0) {
		$i = 0;
?>
	<form>
	<div class='form'>
		<input type="hidden" name="user_details_sno" id="user_details_sno" value="<?php echo $user_details[$i]->sno; ?>">
		<input type="hidden" name="dbPrimaryContact" id="dbPrimaryContact" value="<?php echo $user_details[$i]->primary_contact; ?>">
		<input type="hidden" name="dbPrefContact" id="dbPrefContact" value="<?php echo $user_details[$i]->contact_pref; ?>">
		<input type="hidden" name="viewonly" value="true">

		<div class='label'>Privilege</div>
		<div><?php echo $users[$i]->account_type; ?></div>

		<div class="label">First Name:</div>
		<div><?php echo $user_details[$i]->first_name; ?></div>

		<div class="label">Last Name:</div>
		<div><?php echo $user_details[$i]->last_name; ?></div>

		<div class="label">User Belongs To:</div>
		<div><?php echo $user_details[$i]->belongs_to; ?></div>

		<div class="label">User Type:</div>
		<div><?php echo $user_details[$i]->type; ?></div>

		<div class="label">User Status:</div>
		<div><?php echo $user_details[$i]->status; ?></div>

		<!--
		<div class="label">Active Start Date:</div>
		<div>
			<input type="date" name="activeStartDate" id="activeStartDate" value="<?php echo explode(" ",$user_details[$i]->active_start_date)[0]; ?>" placeholder="Active Start Date" required>
		</div>
		<div class="label">Active End Date:</div>
		<div>
			<input type="date" name="activeEndDate" id="activeEndDate" value="<?php echo explode(" ",$user_details[$i]->active_end_date)[0]; ?>" placeholder="Active End Date" required>
		</div>
		-->
		
		<div class="label">Email ID:</div>
		<div><?php echo $user_details[$i]->email; ?></div>

		<div class="label">Contact Phone Number:</div>
		<div><?php echo $user_details[$i]->contact_ph1; ?></div>

		<div class="label">Mobile Number:</div>
		<div>
			<table  class="innerOption">
				<tr>
					<td colspan="2"><?php echo $user_details[$i]->contact_mobile; ?></td>
				</tr>
				<tr id="mobileradio">
					<td>
						<input type="radio" name="primaryContact" id="primaryMobileNumber" value="mobile" disabled="disabled">
					</td>
					<td><span>Primary Contact Number</span></td>
				</tr>
			</table>
		</div>

		<div class="label">Alternate Number:</div>
		<div>
			<table class="innerOption">
				<tr>
					<td colspan="2"><?php echo $user_details[$i]->contact_alt_mobile; ?></td>
				</tr>
				<tr id="alternateradio">
					<td>
						<input type="radio" name="primaryContact" id="primaryAlternateNumber" value="alternate" disabled="disabled">
					</td>
					<td><span>Primary Contact Number</span></td>
				</tr>
			</table>
		</div>
		
		<div class="label prefMode">Prefered Mode for Contact:</div>
		<div>
			<table class="innerOption">
				<tr>
					<td id="emailIdcheckbox"><input type="checkbox" name="prefContact" id="prefContactEmailId" value="emailId" disabled="disabled"></td>
					<td id="emailIdcheckboxlabel">Email</td>
					<td id="contactPhoneNumbercheckbox"><input type="checkbox" name="prefContact" id="prefContactContactPhoneNumber" value="contactPhoneNumber" disabled="disabled"></td>
					<td id="contactPhoneNumbercheckboxlabel">Home Phone</td>
				</tr>
				<tr>
					<td id="mobileNumbercheckbox"><input type="checkbox" name="prefContact" id="prefContactMobileNumber" value="mobileNumber" disabled="disabled"></td>
					<td id="mobileNumbercheckboxlabel">Mobile Number</td>
					<td id="altNumbercheckbox"><input type="checkbox" name="prefContact" id="prefContactAltNumber" value="altNumber" disabled="disabled"></td>
					<td id="altNumbercheckboxlabel">Alternate Number</td>
				</tr>
			</table>
		</div>
		
		<div class="label">Flat No:</div>
		<div><?php echo $user_details[$i]->addr1; ?></div>

		<div class="label">Building Name:</div>
		<div><?php echo $user_details[$i]->addr2; ?></div>

		<div class="label">Street:</div>
		<div><?php echo $user_details[$i]->addr3; ?></div>

		<div class="label">Main:</div>
		<div><?php echo $user_details[$i]->addr4; ?></div>

		<div class="label">City:</div>
		<div><?php echo $user_details[$i]->addr_city; ?></div>

		<div class="label">State:</div>
		<div><?php echo $user_details[$i]->addr_state; ?></div>

		<div class="label">Country:</div>
		<div><?php echo $user_details[$i]->addr_country; ?></div>

		<div class="label">Pin Code:</div>
		<div><?php echo $user_details[$i]->addr_pin;?></div>
	</div>
</form>
<?php
	}
?>