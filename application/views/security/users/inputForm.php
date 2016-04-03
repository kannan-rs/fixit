<?php 
$createOrEdit = "create";
$prefix = "create";

if(isset($users) && count($users)) {
	$createOrEdit 	= "edit";
	$prefix = "update";
	$user_details = $user_details[0];

	if($viewFrom == "security") { 
		$heading = $this->lang->line_arr('user->headers->admin_edit');
	} else {
		$heading = $this->lang->line_arr('user->headers->self_edit');
	}
	$buttonText 	= "Update User";
} else {
	if(isset($role_disp_name) && $role_disp_name == ROLE_ADMIN) { 
		$heading 		= $this->lang->line_arr('user->headers->admin_create');
		$buttonText 	= "Create User";
	} else { 
		$heading 		= $this->lang->line_arr('user->headers->enroll');
		$buttonText 	= "Signup";
	}
}

/* 
	Input form value setting for both create and edit
	Values are set from Database 
*/
$firstName 				= isset($user_details) ? $user_details->first_name : "";
$lastName 				= isset($user_details) ? $user_details->last_name : "";
$belongsTo 				= isset($user_details) ? $user_details->belongs_to : (isset($belongsTo) ? $belongsTo : "");
$belongsToId 			= isset($user_details) ? $user_details->belongs_to_id : "";

$referredBy 			= isset($user_details) ? $user_details->referred_by : "";
$referredById 			= isset($user_details) ? $user_details->referred_by_id : "";
$status 				= isset($user_details) ? $user_details->status : "";
$activeStartDate 		= isset($user_details) ? $user_details->active_start_date : "";
$activeEndDate 			= isset($user_details) ? $user_details->active_end_date : "";
$emailId 				= isset($user_details) ? $user_details->email : "";
$contactPhoneNumber 	= isset($user_details) ? $user_details->contact_ph1 : "";
$mobileNumber 			= isset($user_details) ? $user_details->contact_mobile : "";
$altNumber 				= isset($user_details) ? $user_details->contact_alt_mobile : "";
$user_details_sno 		= isset($user_details) ? $user_details->sno : "";
$dbPrefContact 			= isset($user_details) ? $user_details->contact_pref : "";
$dbPrimaryContact 		= isset($user_details) ? $user_details->primary_contact : "";
$is_service_provider 	= isset($is_service_provider) ? $is_service_provider : "";
$is_ins_comp 			= isset($is_ins_comp)  ? $is_ins_comp : "";
$is_partner 			= isset($is_partner) ? $is_partner : "";

$openAs 			= isset($openAs) ? $openAs : "";
$popupType 			= isset($popupType) ? $popupType : "";

?>
<?php
if(!$openAs || $openAs != "popup") {
?>
<div class="header-options">
	<h2 class=''><?php echo $heading; ?></h2>
</div>
<?php
}
?>
<form id="<?php echo $prefix; ?>_user_form" name="<?php echo $prefix; ?>_user_form" class="inputForm">
	<?php
	if($createOrEdit == "edit") {
	?>
		<input type="hidden" id='userId' value="<?php echo $users[0]->sno; ?>" />
		<input type="hidden" id='privilege_db_val' value="<?php echo $users[0]->role_id; ?>" />
		
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
	<table class='form'>
		<tbody>
			<?php 
			if( isset($role_disp_name) && $role_disp_name == ROLE_ADMIN && 
				($createOrEdit == "create" || ($createOrEdit == "edit" && $viewFrom == "security"))) { 
				if(!isset($role_id) || !isset($users) || $role_id != $users[0]->role_id) {
				?>
				<tr>
					<td class="label"><?php echo $this->lang->line_arr('user->input_form->role'); ?></td>
					<td>
						<select name="privilege" id="privilege" onchange="_users.showBelongsToOption()">
							<option value="0"><?php echo $this->lang->line_arr('user->input_form->role_option_0'); ?></option>
							<?php
							if($roles && count($roles)) {
								for($i = 0; $i < count($roles); $i++) {
									echo "<option value='".$roles[$i]->sno."'>".$roles[$i]->role_name."</option>";
								}
							}
							?>
						</select>
					</td>
				</tr>
			<?php 
				}

				if($is_service_provider) {
			?>
				<tr>
					<td class="label">User Belongs to Service Provider</td>
					<td><span id="selectedContractorDb"><?php echo $belongsToName; ?></span></td>
				</tr>
			<?php
					} else if ($is_partner) {
			?>
				<tr>
					<td class="label">User Belongs to Partner</td>
					<td><span id="selectedAdjusterDB"><?php echo $belongsToName; ?></span></td>
				</tr>
			<?php
				} else if ( $is_ins_comp ) {
			?>
				<tr>
					<td class="label">User Belongs to Insurance Comp</td>
					<td><span id="selectedInsCompDB"><?php echo $belongsToName; ?></span></td>
				</tr>	
			<?php
				}
			?>
				<!-- Service Provider Search and search results -->
				<tr class="contractor-search">
					<td class="label notMandatory"><?php echo $this->lang->line_arr('user->input_form->contractorZipCode'); ?></td>
					<td>
						<input type="text" name="contractorZipCode" id="contractorZipCode" value="" Placeholder="<?php echo $this->lang->line_arr('user->input_form->contractorZipCode_ph'); ?>">
						<span class="fi-zoom-in size-21 searchIcon" onclick="_users.getContractorListUsingZip('')"></span>
					</td>
				</tr>
				<tr class="contractor-result">
					<td class="label notMandatory">&nbsp;</td>
					<td>
						<ul id="contractorList" name="contractorList" class="connectedSortable owner-search-result users"></ul>
					</td>
				</tr>

				<!-- Adjuster Search and search results -->
				<tr class="adjuster-search">
					<td class="label notMandatory"><?php echo $this->lang->line_arr('user->input_form->partnerCompanyName'); ?></td>
					<td>
						<input type="text" name="partnerCompanyName" id="partnerCompanyName" value="" Placeholder="<?php echo $this->lang->line_arr('user->input_form->partnerCompanyName_ph'); ?>">
						<span class="fi-zoom-in size-21 searchIcon" onclick="_users.getAdjusterByCompanyName('')"></span>
					</td>
				</tr>
				<tr class="adjuster-result">
					<td class="label notMandatory">&nbsp;</td>
					<td>
						<ul id="adjusterList" name="adjusterList" class="connectedSortable owner-search-result users"></ul>
					</td>
				</tr>

				<!-- Adjuster Search and search results -->
				<tr class="ins_comp-search">
					<td class="label notMandatory"><?php echo $this->lang->line_arr('user->input_form->ins_comp_name'); ?></td>
					<td>
						<input type="text" name="ins_comp_name" id="ins_comp_name" value="" Placeholder="<?php echo $this->lang->line_arr('user->input_form->ins_comp_name_ph'); ?>">
						<span class="fi-zoom-in size-21 searchIcon" onclick="_users.get_ins_comp_by_name('')"></span>
					</td>
				</tr>
				<tr class="ins_comp-result">
					<td class="label notMandatory">&nbsp;</td>
					<td>
						<ul id="ins_compList" name="ins_compList" class="connectedSortable owner-search-result users"></ul>
					</td>
				</tr>
			<?php
			}
			?>

			<tr>
				<td class="label"><?php echo $this->lang->line_arr('user->input_form->firstName'); ?></td>
				<td><input type="text" name="firstName" id="firstName" value="<?php echo $firstName; ?>" placeholder="<?php echo $this->lang->line_arr('user->input_form->firstName_ph'); ?>" required></td>
			</tr>
			<tr>
				<td class="label notMandatory"><?php echo $this->lang->line_arr('user->input_form->lastName'); ?></td>
				<td><input type="text" name="lastName" id="lastName" value="<?php echo $lastName; ?>" placeholder="<?php echo $this->lang->line_arr('user->input_form->lastName_ph'); ?>"></td>
			</tr>

			<?php if(!is_logged_in() || $createOrEdit == "create") { // only for create user ?>
				<tr>
					<td class="label"><?php echo $this->lang->line_arr('user->input_form->password'); ?></td>
					<td><input type="password" name="password" id="password" value="" placeholder="<?php echo $this->lang->line_arr('user->input_form->password_ph'); ?>" required></td>
				</tr>
				
				<tr>
					<td class="label"><?php echo $this->lang->line_arr('user->input_form->confirmPassword'); ?></td>
					<td><input type="password" name="confirmPassword" id="confirmPassword" value="" placeholder="<?php echo $this->lang->line_arr('user->input_form->confirmPassword_ph'); ?>" required></td>
				</tr>
				
				<tr>
					<td class="label notMandatory"><?php echo $this->lang->line_arr('user->input_form->passwordHint'); ?></td>
					<td><input type="text" name="passwordHint" id="passwordHint" value="" placeholder="<?php echo $this->lang->line_arr('user->input_form->passwordHint_ph'); ?>"></td>
				</tr>
			<?php
			}
			?>


			<!-- <tr>
				<td class="label"><?php echo $this->lang->line_arr('user->input_form->belongsTo'); ?></td>
				<td>
					<?php if($createOrEdit == "create" && $belongsTo != "") { // Create User from project by selecting contractor or adjuster ?>
					<div><?php echo $belongsTo; ?><input type="hidden" id="belongsToDb" value="<?php echo $belongsTo; ?>"></div>
					<?php } else {
					?>
					<select name="belongsTo" id="belongsTo" <?php if($role_disp_name == ROLE_ADMIN) { ?> onchange="_users.showBelongsToOption()" <?php } ?> required>
						<option value=""><?php echo $this->lang->line_arr('user->input_form->belongsTo_option_0'); ?></option>
						<option value="customer">Customer</option>
						<option value="contractor">Service Provider</option>
						<option value="adjuster">Adjuster</option>
					</select>
					<?php
					}
					
					if(!empty($belongsTo)) {
						if( $belongsTo == "contractor") {
							echo "<span id=\"selectedContractorDb\">Service Provider:".$belongsToName."</span>";
						} else if ($belongsTo == "adjuster") {
							echo "<span id=\"selectedAdjusterDB\">Adjuster:".$belongsToName."</span>";
						}
					}
					?>
				</td>
			</tr> -->

			<?php 
			if(isset($role_disp_name) && $role_disp_name == ROLE_ADMIN) { 
			?>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('user->input_form->userStatus'); ?></td>
				<td>
					<select name="userStatus" id="userStatus" required>
						<option value=""><?php echo $this->lang->line_arr('user->input_form->userStatus_option_0'); ?></option>
						<option value="active">Active</option>
						<option value="inactive">Inactive</option>
					</select>
				</td>
			</tr>
			<?php
			}
			?>

			<?php if($createOrEdit == "edit" && $role_disp_name == ROLE_ADMIN  && $viewFrom == "security") { ?>
			<tr>
				<td class="label notMandatory"><?php echo $this->lang->line_arr('user->input_form->activeStartDate'); ?></td>
				<td>
						<input type="text" name="activeStartDate" id="activeStartDate" value="<?php echo explode(" ",$activeStartDate)[0]; ?>" placeholder="<?php echo $this->lang->line_arr('user->input_form->activeStartDate_ph'); ?>">
				</td>
			</tr>
			<tr>
				<td class="label notMandatory"><?php echo $this->lang->line_arr('user->input_form->activeEndDate'); ?></td>
				<td>
						<input type="text" name="activeEndDate" id="activeEndDate" value="<?php echo explode(" ",$activeEndDate)[0]; ?>" placeholder="<?php echo $this->lang->line_arr('user->input_form->activeEndDate_ph'); ?>">
				</td>
			</tr>
			<?php } ?>
			
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('user->input_form->email'); ?></td>
				<td>
						<input type="email" name="emailId" id="emailId" value="<?php echo $emailId; ?>" placeholder="<?php echo $this->lang->line_arr('user->input_form->email_ph'); ?>" <?php echo $createOrEdit == "edit" ? "disabled" : ""; ?> required>
				</td>
			</tr>
			
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('user->input_form->contactPhoneNumber'); ?></td>
				<td>
						<input type="text" name="contactPhoneNumber" id="contactPhoneNumber" value="<?php echo $contactPhoneNumber; ?>" placeholder="<?php echo $this->lang->line_arr('user->input_form->contactPhoneNumber_ph'); ?>" required>
				</td>
			</tr>
			
			<tr>
				<td class="label notMandatory"><?php echo $this->lang->line_arr('user->input_form->mobileNumber'); ?></td>
				<td>
						<table  class="innerOption">
							<tr>
								<td colspan="2">
									<input type="text" name="mobileNumber" id="mobileNumber" value="<?php echo $mobileNumber; ?>" placeholder="<?php echo $this->lang->line_arr('user->input_form->mobileNumber_ph'); ?>">
								</td>
							</tr>
							<tr>
								<td>
									<input type="radio" name="primaryContact" id="primaryMobileNumber" value="mobile">
								</td>
								<td><span><?php echo $this->lang->line_arr('user->input_form->primaryContact'); ?></span></td>
							</tr>
						</table>
				</td>
			</tr>

			<tr>
				<td class="label notMandatory"><?php echo $this->lang->line_arr('user->input_form->altNumber'); ?></td>
				<td>
						<table class="innerOption">
							<tr>
								<td colspan="2">
									<input type="text" name="altNumber" id="altNumber" value="<?php echo $altNumber; ?>" placeholder="<?php echo $this->lang->line_arr('user->input_form->altNumber_ph'); ?>">
								</td>
							</tr>
							<tr>
								<td>
									<input type="radio" name="primaryContact" id="primaryAlternateNumber" value="alternate">
								</td>
								<td><span><?php echo $this->lang->line_arr('user->input_form->primaryContact'); ?></span></td>
							</tr>
						</table>
				</td>
			</tr>

			<!-- Preferred Mode of contact -->
			<!-- 
			<tr>
				<td class="label prefMode notMandatory"><?php echo $this->lang->line_arr('user->input_form->prefMode'); ?></td>
				<td>
						<table class="innerOption">
							<tr>
								<td><input type="checkbox" name="prefContact" id="prefContactEmailId" value="emailId"></td>
								<td><?php echo $this->lang->line_arr('user->input_form->prefContactEmailId'); ?></td>
								<td><input type="checkbox" name="prefContact" id="prefContactContactPhoneNumber" value="contactPhoneNumber"></td>
								<td><?php echo $this->lang->line_arr('user->input_form->prefContactContactPhoneNumber'); ?></td>
							</tr>
							<tr>
								<td><input type="checkbox" name="prefContact" id="prefContactMobileNumber" value="mobileNumber"></td>
								<td><?php echo $this->lang->line_arr('user->input_form->prefContactMobileNumber'); ?></td>
								<td><input type="checkbox" name="prefContact" id="prefContactAltNumber" value="altNumber"></td>
								<td><?php echo $this->lang->line_arr('user->input_form->prefContactAltNumber'); ?></td>
							</tr>
						</table>
				</td>
			</tr> -->
			
			<?php
				echo $addressFile;
			?>

			<?php 
			// Referr to will be applicable only when admin creates
			if(!empty($role_disp_name) && $role_disp_name == ROLE_ADMIN) { ?>
				<!-- Refers too -->
				<tr>
					<td class="label notMandatory"><?php echo $this->lang->line_arr('user->input_form->referredBy'); ?></td>
					<td>
							<select name="referredBy" id="referredBy" onchange="_users.showreferredByOption()">
								<option value=""><?php echo $this->lang->line_arr('user->input_form->referredBy_option_0'); ?></option>
								<option value="customer">Customer</option>
								<option value="contractor">Service Provider</option>
								<option value="adjuster">Adjuster</option>
							</select>
							<?php
							if(!empty($referredBy)) {
								if( $referredBy == "contractor") {
									echo "<span id=\"referredToselectedContractorDb\">Service Provider:".$referredByName."</span>";
								} else if ($referredBy == "adjuster") {
									echo "<span id=\"referredToselectedAdjusterDB\">Adjuster:".$referredByName."</span>";
								}
							}
							?>
					</td>
				</tr>

				<!-- Service Provider Search and search results -->
				<tr class="referredBycontractor-search">
					<td class="label notMandatory"><?php echo $this->lang->line_arr('user->input_form->referredBycontractorZipCode'); ?></td>
					<td>
						<input type="text" name="referredBycontractorZipCode" id="referredBycontractorZipCode" value="" Placeholder="<?php echo $this->lang->line_arr('user->input_form->referredBycontractorZipCode_ph'); ?>">
						<span class="fi-zoom-in size-21 searchIcon" onclick="_users.getContractorListUsingZip('referredBy')"></span>
					</td>
				</tr>
				<tr class="referredBycontractor-result">
					<td class="label notMandatory">&nbsp;</td>
					<td>
						<ul id="referredBycontractorList" name="referredBycontractorList" class="connectedSortable owner-search-result users"></ul>
					</td>
				</tr>

				<!-- Adjuster Search and search results -->
				<tr class="referredByadjuster-search">
					<td class="label notMandatory"><?php echo $this->lang->line_arr('user->input_form->referredBypartnerCompanyName'); ?></td>
					<td>
						<input type="text" name="referredBypartnerCompanyName" id="referredBypartnerCompanyName" value="" Placeholder="<?php echo $this->lang->line_arr('user->input_form->referredBypartnerCompanyName_ph'); ?>">
						<span class="fi-zoom-in size-21 searchIcon" onclick="_users.getAdjusterByCompanyName('referredBy')"></span>
					</td>
				</tr>
				<tr class="referredByadjuster-result">
					<td class="label notMandatory">&nbsp;</td>
					<td>
						<ul id="referredByadjusterList" name="referredByadjusterList" class="connectedSortable owner-search-result users"></ul>
					</td>
				</tr>
			<?php 
			} 
			?>

			<?php if($prefix == "create" && (!isset($role_disp_name) || $role_disp_name == "")) { ?>
			<tr class="signupTC">
				<td></td>
				<td>
					<table>
						<tr>
							<td>
								<input id="termsCondition" name="termsCondition" type="checkbox" onclick="_users.tcChecked()" />
							</td>
							<td>
							<?php
								$tc_start = "<a target=\"_blank\" href=\"<?php echo base_url(); ?>\">";
								$pp_start = "<a target=\"_blank\" href=\"<?php echo base_url(); ?>\">";
								$end = "</a>";
								echo str_replace(["##replace1##", "##replace2##", '##replace3##', '##replace4##'], [$tc_start, $end, $pp_start, $end], $this->lang->line_arr('user->input_form->tc'));
							?>
							</td>
						</tr>
						<tr id="tcError" class="error">
							<td colspan="2"><?php echo $this->lang->line_arr('user->input_form->tc_error'); ?></td>
						</tr>
					</table>
				</td>
			</tr>
			<?php } ?>
			
			<tr>
				<td colspan="2">
					<p class="button-panel">
						<button type="button" id="create_user_submit" onclick="_users.<?php echo $prefix; ?>Validate('<?php echo $openAs; ?>', '<?php echo $popupType;?>', '<?php echo $belongsTo; ?>')"><?php echo $buttonText; ?></button>
						<?php if(!$openAs || $openAs != "popup") { ?>
						<button type="reset" id="resetButton" onclick=""><?php echo $this->lang->line_arr('buttons->reset'); ?></button>
						<?php } else { ?>
						<button type="button" id="cancelButton" onclick="_projects.closeDialog({popupType: '<?php echo $popupType; ?>'})"><?php echo $this->lang->line_arr('buttons->cancel'); ?></button>
						<?php } ?>
					</p>
				</td>
			</tr>
			<tr>
				<td colspan="2"><?php echo $this->lang->line('mandatory_field_text'); ?></td>
			</tr>
		</tbody>
	</table>
</form>
<!-- Add Users Ends -->