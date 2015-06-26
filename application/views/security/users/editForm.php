<?php if($viewFrom == "security") { 
	$heading = "Edit Users";
} else {
	$heading = "Edit Personal Details";
}
?>
<h2><?php echo $heading; ?></h2>
<form id="update_user_form" name="update_user_form">
	<div class='form'>
		<input type="hidden" name="user_details_sno" id="user_details_sno" value="<?php echo $user_details[0]->sno; ?>">
		<input type="hidden" id='userId' value="<?php echo $users[0]->sno; ?>" />
		<input type="hidden" name="dbPrimaryContact" id="dbPrimaryContact" value="<?php echo $user_details[0]->primary_contact; ?>">
		<input type="hidden" name="dbPrefContact" id="dbPrefContact" value="<?php echo $user_details[0]->contact_pref; ?>">

		<?php if($userType == "admin" && $viewFrom == "security") { ?>
		<div class="label">Privilege:</div>
		<div>
			<input type="hidden" id='privilege_db_val' value="<?php echo $users[0]->account_type; ?>" />
			<select name="privilege" id="privilege">
				<option value="">--Select Privilege--</option>
				<option value="1">Admin</option>
				<option value="2">User</option>
			</select>
		</div>
		<?php } ?>
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
			<input type="hidden" name="belongsToDb" id="belongsToDb" value="<?php echo $user_details[0]->belongs_to; ?>">
			<select name="belongsTo" id="belongsTo" <?php if($userType == "admin") { ?> onchange="securityObj._users.showContractor(this.value)" <?php } ?>>
				<option value="">--Select Belongs To--</option>
				<option value="customer">Customer</option>
				<option value="contractor">Contractor</option>
				<option value="adjuster">Adjuster</option>
			</select>
			<?php if(!empty($user_details[0]->belongs_to) && $user_details[0]->belongs_to == "contractor") { 
				echo "<span id=\"selectedContractorDB\">".$contractorName."</span>";
			}
			?>
		</div>
		<?php if($userType == "admin") { ?>
		<DIV class="contractor-search">
			<div class="label">Search Contractor By Zip Code and Select</div>
			<div>
				<input type="text" name="contractorZipCode" id="contractorZipCode" value="" Placeholder="Zip Code for search">
				<span class="fi-zoom-in size-21 searchIcon" onclick="securityObj._users.getContractorListUsingZip()"></span>
			</div>
			<div class="contractor-result">
				<DIV class="label">&nbsp;</DIV>
				<DIV>
					<ul id="contractorList" name="contractorList" class="connectedSortable owner-search-result users"></ul>
				</DIV>
			</div>
		</DIV>
		<div class="label">User Status:</div>
		<div>
			<input type="hidden" name="userStatusDb" id="userStatusDb" value="<?php echo $user_details[0]->status; ?>">
			<select name="userStatus" id="userStatus" required>
				<option>--Select Status--</option>
				<option value="active">Active</option>
				<option value="inactive">Inactive</option>
			</select>
		</div>
		<?php } ?>

		<?php if($userType == "admin"  && $viewFrom == "security") { ?>
		<div class="label">Active Start Date:</div>
		<div>
			<input type="date" name="activeStartDate" id="activeStartDate" value="<?php echo explode(" ",$user_details[0]->active_start_date)[0]; ?>" placeholder="Active Start Date" required>
		</div>
		<div class="label">Active End Date:</div>
		<div>
			<input type="date" name="activeEndDate" id="activeEndDate" value="<?php echo explode(" ",$user_details[0]->active_end_date)[0]; ?>" placeholder="Active End Date" required>
		</div>
		<?php } ?>
		
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
		<?php
			echo $addressFile;
		?>
		<p class="button-panel">
			<button type="button" id="update_user_submit" onclick="securityObj._users.updateValidate()">Update</button>
		</p>
	</div>
	<!--
	<div class='form'>
		<div class="label">Password</div>
		<div><input type="password" name="password" id="password" required></div>
		<div class="label">Confirm Password:</div>
		<div><input type="password" name="confirm_password" id="confirm_password" required></div>
		<p class="button-panel">
			<button type="button" id="update_user_submit" onclick="securityObj._users.updateValidate()">Update</button>
		</p>
	</div>
	-->
</form>