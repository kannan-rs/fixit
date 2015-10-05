<?php 
$edit = false;
$prefix = "create";

if(isset($users) && count($users)) {
	$edit 	= true;
	$prefix = "update";
	$user_details = $user_details[0];

	if($viewFrom == "security") { 
		$heading = "Edit Users";
	} else {
		$heading = "Edit Personal Details";
	}
	$buttonText 	= "Update User";
} else {
	if($userType == "admin") { 
		$heading 		= "Create User";
		$buttonText 	= "Create User";
	} else { 
		$heading 		= "Enroll to Fixit";
		$buttonText 	= "Signup";
	}
}

/* 
	Input form value setting for both create and edit
	Values are set from Database 
*/
$firstName 			= isset($user_details) ? $user_details->first_name : "";
$lastName 			= isset($user_details) ? $user_details->last_name : "";
$belongsTo 			= isset($user_details) ? $user_details->belongs_to : (isset($belongsTo) ? $belongsTo : "");
$belongsToId 		= isset($user_details) ? $user_details->belongs_to_id : "";

$referredBy 		= isset($user_details) ? $user_details->referred_by : "";
$referredById 		= isset($user_details) ? $user_details->referred_by_id : "";

$status 			= isset($user_details) ? $user_details->status : "";
$activeStartDate 	= isset($user_details) ? $user_details->active_start_date : "";
$activeEndDate 		= isset($user_details) ? $user_details->active_end_date : "";
$emailId 			= isset($user_details) ? $user_details->email : "";
$contactPhoneNumber = isset($user_details) ? $user_details->contact_ph1 : "";
$mobileNumber 		= isset($user_details) ? $user_details->contact_mobile : "";
$altNumber 			= isset($user_details) ? $user_details->contact_alt_mobile : "";
$user_details_sno 	= isset($user_details) ? $user_details->sno : "";
$dbPrefContact 		= isset($user_details) ? $user_details->contact_pref : "";
$dbPrimaryContact 	= isset($user_details) ? $user_details->primary_contact : "";

$openAs 			= isset($openAs) ? $openAs : "";
$popupType 			= isset($popupType) ? $popupType : "";

?>
<?php
if(!$openAs || $openAs != "popup") {
?>
<h2><?php echo $heading; ?></h2>
<?php
}
?>
<form id="<?php echo $prefix; ?>_user_form" name="<?php echo $prefix; ?>_user_form" class="inputForm">
	<div class='form'>
		<?php
		if($edit) {
		?>
			<input type="hidden" id='userId' value="<?php echo $users[0]->sno; ?>" />
			<input type="hidden" id='privilege_db_val' value="<?php echo $users[0]->account_type; ?>" />
			
			<input type="hidden" name="user_details_sno" id="user_details_sno" value="<?php echo $user_details_sno; ?>">
			<input type="hidden" name="dbPrimaryContact" id="dbPrimaryContact" value="<?php echo $dbPrimaryContact; ?>">
			<input type="hidden" name="dbPrefContact" id="dbPrefContact" value="<?php echo $dbPrefContact; ?>">
			<input type="hidden" name="userStatusDb" id="userStatusDb" value="<?php echo $status; ?>">

			<input type="hidden" name="belongsToDb" id="belongsToDb" value="<?php echo $belongsTo; ?>">
			<input type="hidden" name="belongsToIdDb" id="belongsToIdDb" value="<?php echo $belongsToId; ?>">

			<input type="hidden" name="referredByDb" id="referredByDb" value="<?php echo $referredBy; ?>">
			<input type="hidden" name="referredByIdDb" id="referredByIdDb" value="<?php echo $referredById; ?>">
		<?php	
		}
		?>
		<?php if($userType == "admin" && ($edit == false || ($edit == true && $viewFrom == "security"))) { ?>
		<div class="label">Privilege:</div>
		<div>
			<select name="privilege" id="privilege">
				<option value="">--Select Privilege--</option>
				<option value="1">Admin</option>
				<option value="2">User</option>
			</select>
		</div>
		<?php } ?>
		<div class="label">First Name:</div>
		<div>
			<input type="text" name="firstName" id="firstName" value="<?php echo $firstName; ?>" placeholder="First Name" required>
		</div>
		<div class="label notMandatory">Last Name:</div>
		<div>
			<input type="text" name="lastName" id="lastName" value="<?php echo $lastName; ?>" placeholder="Last Name">
		</div>
		<?php if(!isset($is_logged_in) || $is_logged_in != 1 || $edit == false) { // only for create user ?>
		
		<div class="label">Password:</div>
		<div><input type="password" name="password" id="password" value="" placeholder="Password" required></div>
		
		<div class="label">Confirm Password:</div>
		<div><input type="password" name="confirmPassword" id="confirmPassword" value="" placeholder="Confirm Password" required></div>
		
		<div class="label notMandatory">Password Hint:</div>
		<div><input type="text" name="passwordHint" id="passwordHint" value="" placeholder="Password Hint"></div>
		
		<?php
		}
		?>

		<div class="label">User Belongs To:</div>
		<div>
			<?php if($edit == false && $belongsTo != "") { // Create User from project by selecting contractor or adjuster ?>
			<div><?php echo $belongsTo; ?><input type="hidden" id="belongsTo" value="<?php echo $belongsTo; ?>"></div>
			<?php } else {
			?>
			<select name="belongsTo" id="belongsTo" <?php if($userType == "admin") { ?> onchange="securityObj._users.showBelongsToOption()" <?php } ?> required>
				<option value="">--Select Belongs To--</option>
				<option value="customer">Customer</option>
				<option value="contractor">Contractor</option>
				<option value="adjuster">Adjuster</option>
			</select>
			<?php
			}
			
			if(!empty($belongsTo)) {
				if( $belongsTo == "contractor") {
					echo "<span id=\"selectedContractorDB\">Contractor:".$belongsToName."</span>";
				} else if ($belongsTo == "adjuster") {
					echo "<span id=\"selectedAdjusterDB\">Adjuster:".$belongsToName."</span>";
				}
			}
			?>
		</div>
		<?php if($userType == "admin") { ?>
		<!-- Contractor Search and search results -->
		<DIV class="contractor-search">
			<div class="label notMandatory">Search Contractor By Zip Code and Select</div>
			<div>
				<input type="text" name="contractorZipCode" id="contractorZipCode" value="" Placeholder="Zip Code for search">
				<span class="fi-zoom-in size-21 searchIcon" onclick="securityObj._users.getContractorListUsingZip('')"></span>
			</div>
			<div class="contractor-result">
				<DIV class="label notMandatory">&nbsp;</DIV>
				<DIV>
					<ul id="contractorList" name="contractorList" class="connectedSortable owner-search-result users"></ul>
				</DIV>
			</div>
		</DIV>

		<!-- Adjuster Search and search results -->
		<DIV class="adjuster-search">
			<div class="label notMandatory">Search Adjuster By Company Name and Select</div>
			<div>
				<input type="text" name="partnerCompanyName" id="partnerCompanyName" value="" Placeholder="adjuster Company Name">
				<span class="fi-zoom-in size-21 searchIcon" onclick="securityObj._users.getAdjusterByCompanyName('')"></span>
			</div>
			<div class="adjuster-result">
				<DIV class="label notMandatory">&nbsp;</DIV>
				<DIV>
					<ul id="adjusterList" name="adjusterList" class="connectedSortable owner-search-result users"></ul>
				</DIV>
			</div>
		</DIV>
		
		<div class="label">User Status:</div>
		<div>
			<select name="userStatus" id="userStatus" required>
				<option value="">--Select Status--</option>
				<option value="active">Active</option>
				<option value="inactive">Inactive</option>
			</select>
		</div>
		<?php } ?>

		<?php if($edit == true && $userType == "admin"  && $viewFrom == "security") { ?>
		<div class="label notMandatory">Active Start Date:</div>
		<div>
			<input type="text" name="activeStartDate" id="activeStartDate" value="<?php echo explode(" ",$activeStartDate)[0]; ?>" placeholder="Active Start Date">
		</div>
		<div class="label notMandatory">Active End Date:</div>
		<div>
			<input type="text" name="activeEndDate" id="activeEndDate" value="<?php echo explode(" ",$activeEndDate)[0]; ?>" placeholder="Active End Date">
		</div>
		<?php } ?>
		
		<div class="label">Email ID:</div>
		<div>
			<input type="email" name="emailId" id="emailId" value="<?php echo $emailId; ?>" placeholder="Email ID" <?php echo $edit == true ? "disabled" : ""; ?> required>
		</div>
		
		<div class="label">Contact Phone Number:</div>
		<div>
			<input type="text" name="contactPhoneNumber" id="contactPhoneNumber" value="<?php echo $contactPhoneNumber; ?>" placeholder="Contact Phone Number" required>
		</div>
		
		<div class="label notMandatory">Mobile Number:</div>
		<div>
			<table  class="innerOption">
				<tr>
					<td colspan="2">
						<input type="text" name="mobileNumber" id="mobileNumber" value="<?php echo $mobileNumber; ?>" placeholder="Mobile Number">
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
		
		<div class="label notMandatory">Alternate Number:</div>
		<div>
			<table class="innerOption">
				<tr>
					<td colspan="2">
						<input type="text" name="altNumber" id="altNumber" value="<?php echo $altNumber; ?>" placeholder="alternate">
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

		<div class="label prefMode notMandatory">Prefered Mode for Contact:</div>
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

		<?php if(!empty($userType)) { // Referr to will be applicable only when admin creates ?>
		<!-- Refers too -->
		<div class="label notMandatory">User Referred By:</div>
		<div>
			<select name="referredBy" id="referredBy" onchange="securityObj._users.showreferredByOption()">
				<option value="">--Select Referred By--</option>
				<option value="customer">Customer</option>
				<option value="contractor">Contractor</option>
				<option value="adjuster">Adjuster</option>
			</select>
			<?php
			if(!empty($referredBy)) {
				if( $referredBy == "contractor") {
					echo "<span id=\"referredToselectedContractorDB\">Contractor:".$referredByName."</span>";
				} else if ($referredBy == "adjuster") {
					echo "<span id=\"referredToselectedAdjusterDB\">Adjuster:".$referredByName."</span>";
				}
			}
			?>
		</div>
		<!-- Contractor Search and search results -->
		<DIV class="referredBycontractor-search">
			<div class="label notMandatory">Search Contractor By Zip Code and Select</div>
			<div>
				<input type="text" name="referredBycontractorZipCode" id="referredBycontractorZipCode" value="" Placeholder="Zip Code for search">
				<span class="fi-zoom-in size-21 searchIcon" onclick="securityObj._users.getContractorListUsingZip('referredBy')"></span>
			</div>
			<div class="contractor-result">
				<DIV class="label notMandatory">&nbsp;</DIV>
				<DIV>
					<ul id="referredBycontractorList" name="referredBycontractorList" class="connectedSortable owner-search-result users"></ul>
				</DIV>
			</div>
		</DIV>

		<!-- Adjuster Search and search results -->
		<DIV class="referredByadjuster-search">
			<div class="label notMandatory">Search Adjuster By Company Name and Select</div>
			<div>
				<input type="text" name="referredBypartnerCompanyName" id="referredBypartnerCompanyName" value="" Placeholder="Adjuster Company Name">
				<span class="fi-zoom-in size-21 searchIcon" onclick="securityObj._users.getAdjusterByCompanyName('referredBy')"></span>
			</div>
			<div class="referredByadjuster-result">
				<DIV class="label notMandatory">&nbsp;</DIV>
				<DIV>
					<ul id="referredByadjusterList" name="referredByadjusterList" class="connectedSortable owner-search-result users"></ul>
				</DIV>
			</div>
		</DIV>
		<?php } ?>
		
		<p class="button-panel">
			<button type="button" id="create_user_submit" onclick="securityObj._users.<?php echo $prefix; ?>Validate('<?php echo $openAs; ?>', '<?php echo $popupType;?>', '<?php echo $belongsTo; ?>')"><?php echo $buttonText; ?></button>
			<?php if(!$openAs || $openAs != "popup") { ?>
			<button type="reset" id="resetButton" onclick="">Reset</button>
			<?php } else { ?>
			<button type="button" id="cancelButton" onclick="projectObj._projects.closeDialog({popupType: '<?php echo $popupType; ?>'})">Cancel</button>
			<?php } ?>
		</p>
	</div>
</form>
<!-- Add Users Ends -->