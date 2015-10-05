<?php
		$users 			= $users[0];
		$user_details 	= $user_details[0];

		$heading = $viewFrom == "security" ? "View User Details" : "Personal Details";
		$heading = $viewFrom == "projects" ? "" : $heading;
		$heading = $heading != "" ? "<h2>".$heading."</h2>" : $heading;
?>
<div class="create-link">
	<?php if($viewFrom == "security") { ?>
	<a href="javascript:void(0);" onclick="securityObj._users.createForm()">Create User</a>
	<?php } else if($viewFrom == "") { ?>
		<a href="javascript:void(0);" onclick="home._userInfo.editPage(<?php echo $user_details->sno; ?>);">Edit Details</a>
	<?php } ?>
</div>
<?php echo $heading; ?>

<?php echo $noticeFile; ?>

<form>
	<div class='form'>
		<input type="hidden" name="user_details_sno" id="user_details_sno" value="<?php echo $user_details->sno; ?>">
		<input type="hidden" name="dbPrimaryContact" id="dbPrimaryContact" value="<?php echo $user_details->primary_contact; ?>">
		<input type="hidden" name="dbPrefContact" id="dbPrefContact" value="<?php echo $user_details->contact_pref; ?>">
		<input type="hidden" name="viewonly" value="true">
		
		<div class='label'>Privilege</div>
		<div class="capitalize"><?php echo $users->account_type; ?></div>
		
		<div class="label">First Name:</div>
		<div class="capitalize"><?php echo $user_details->first_name; ?></div>
		
		<div class="label">Last Name:</div>
		<div class="capitalize"><?php echo $user_details->last_name; ?></div>
		
		<div class="label">User Belongs To:</div>
		<div class="capitalize"><?php echo !empty($user_details->belongs_to) ? $user_details->belongs_to : "-NA-"; ?></div>
		<?php 
			if($user_details->belongs_to == "contractor") { 
		?>
			<div class="label">Contractor Company:</div>
			<div class="capitalize"><?php echo $belongsToName; ?></div>
		<?php 
			} else if($user_details->belongs_to == "adjuster") {
		?>
			<div class="label">Adjuster Company:</div>
			<div class="capitalize"><?php echo $belongsToName; ?></div>
		<?php
			}
		?>
		
		<div class="label">User Status:</div>
		<div class="capitalize"><?php echo $user_details->status; ?></div>
		
		<div class="label">Active Start Date:</div>
		<div><?php echo explode(" ",$user_details->active_start_date)[0]; ?></div>
		
		<div class="label">Active End Date:</div>
		<div><?php echo explode(" ",$user_details->active_end_date)[0]; ?></div>	
		
		<div class="label">Email ID:</div>
		<div><?php echo $user_details->email; ?></div>
		
		<div class="label">Contact Phone Number:</div>
		<div><?php echo $user_details->contact_ph1; ?></div>
		
		<div class="label">Mobile Number:</div>
		<div>
			<table  class="innerOption">
				<tr>
					<td colspan="2"><?php echo $user_details->contact_mobile; ?></td>
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
					<td colspan="2"><?php echo $user_details->contact_alt_mobile; ?></td>
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
		
		<?php
			echo $addressFile;
		?>
		<!-- Referred to Details -->
		<?php 
		if($user_details->referred_by) {
			echo "<div class='label'>User Referred By:</div>";
			echo "<div>".$user_details->referred_by."</div>";
			
			if($user_details->referred_by == "contractor") { 
				echo "<div class='label'>Referred By Contractor Company:</div>";
				echo "<div>".$referredByName."</div>";
			} else if($user_details->referred_by == "adjuster") {
				echo "<div class='label'>Referred By Adjuster Company:</div>";
				echo "<div>".$referredByName."</div>";
			} else {
				echo "<div class='label'>Referred By Customer Name:</div>";
				echo "<div>".($referredByName ? $referredByName : "-NA-")."</div>";
			}
		}
		?>
	</div>
</form>