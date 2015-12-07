<?php
		$users 			= $users[0];
		$user_details 	= $user_details[0];

		$heading = $viewFrom == "security" ? $this->lang->line_arr('user->headers->admin_view_one') : $this->lang->line_arr('user->headers->view_one');
		$heading = $viewFrom == "projects" ? "" : $heading;
		$heading = $heading != "" ? "<h2>".$heading."</h2>" : $heading;
?>
<div class="create-link">
	<?php if($viewFrom == "security") { ?>
	<a href="javascript:void(0);" onclick="_users.createForm()">
		<?php echo $this->lang->line_arr('user->buttons_links->create'); ?>
	</a>
	<?php } else if($viewFrom == "") { ?>
		<a href="javascript:void(0);" onclick="home._userInfo.editPage(<?php echo $user_details->sno; ?>);">
			<?php echo $this->lang->line_arr('user->buttons_links->edit_details'); ?>
		</a>
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
		
		<div class='label'><?php echo $this->lang->line_arr('user->details_view->role'); ?></div>
		<div class="capitalize role_id"><?php echo $users->role_id; ?></div>
		
		<div class="label"><?php echo $this->lang->line_arr('user->details_view->firstName'); ?></div>
		<div class="capitalize"><?php echo $user_details->first_name; ?></div>
		
		<div class="label"><?php echo $this->lang->line_arr('user->details_view->lastName'); ?></div>
		<div class="capitalize"><?php echo $user_details->last_name; ?></div>
		
		<div class="label"><?php echo $this->lang->line_arr('user->details_view->belongsTo'); ?></div>
		<div class="capitalize"><?php echo !empty($user_details->belongs_to) ? $user_details->belongs_to : "-NA-"; ?></div>
		<?php 
			if($user_details->belongs_to == "contractor") { 
		?>
			<div class="label"><?php echo $this->lang->line_arr('user->details_view->contractor'); ?></div>
			<div class="capitalize"><?php echo $belongsToName; ?></div>
		<?php 
			} else if($user_details->belongs_to == "adjuster") {
		?>
			<div class="label"><?php echo $this->lang->line_arr('user->details_view->adjuster'); ?></div>
			<div class="capitalize"><?php echo $belongsToName; ?></div>
		<?php
			}
		?>
		
		<div class="label"><?php echo $this->lang->line_arr('user->details_view->userStatus'); ?></div>
		<div class="capitalize"><?php echo $user_details->status; ?></div>
		
		<div class="label"><?php echo $this->lang->line_arr('user->details_view->activeStartDate'); ?></div>
		<div><?php echo explode(" ",$user_details->active_start_date)[0]; ?></div>
		
		<div class="label"><?php echo $this->lang->line_arr('user->details_view->activeEndDate'); ?></div>
		<div><?php echo explode(" ",$user_details->active_end_date)[0]; ?></div>	
		
		<div class="label"><?php echo $this->lang->line_arr('user->details_view->email'); ?></div>
		<div><?php echo $user_details->email; ?></div>
		
		<div class="label"><?php echo $this->lang->line_arr('user->details_view->contactPhoneNumber'); ?></div>
		<div><?php echo $user_details->contact_ph1; ?></div>
		
		<div class="label"><?php echo $this->lang->line_arr('user->details_view->mobileNumber'); ?></div>
		<div>
			<table  class="innerOption">
				<tr>
					<td colspan="2"><?php echo $user_details->contact_mobile; ?></td>
				</tr>
				<tr id="mobileradio">
					<td>
						<input type="radio" name="primaryContact" id="primaryMobileNumber" value="mobile" disabled="disabled">
					</td>
					<td><span><?php echo $this->lang->line_arr('user->details_view->primaryContact'); ?></span></td>
				</tr>
			</table>
		</div>
		
		<div class="label"><?php echo $this->lang->line_arr('user->details_view->altNumber'); ?></div>
		<div>
			<table class="innerOption">
				<tr>
					<td colspan="2"><?php echo $user_details->contact_alt_mobile; ?></td>
				</tr>
				<tr id="alternateradio">
					<td>
						<input type="radio" name="primaryContact" id="primaryAlternateNumber" value="alternate" disabled="disabled">
					</td>
					<td><span><?php echo $this->lang->line_arr('user->details_view->primaryContact'); ?></span></td>
				</tr>
			</table>
		</div>
		
		<!-- <div class="label prefMode"><?php echo $this->lang->line_arr('user->details_view->prefMode'); ?></div>
		<div>
			<table class="innerOption">
				<tr>
					<td id="emailIdcheckbox"><input type="checkbox" name="prefContact" id="prefContactEmailId" value="emailId" disabled="disabled"></td>
					<td id="emailIdcheckboxlabel"><?php echo $this->lang->line_arr('user->details_view->pref_email'); ?></td>
					<td id="contactPhoneNumbercheckbox"><input type="checkbox" name="prefContact" id="prefContactContactPhoneNumber" value="contactPhoneNumber" disabled="disabled"></td>
					<td id="contactPhoneNumbercheckboxlabel"><?php echo $this->lang->line_arr('user->details_view->pref_home_phone'); ?></td>
				</tr>
				<tr>
					<td id="mobileNumbercheckbox"><input type="checkbox" name="prefContact" id="prefContactMobileNumber" value="mobileNumber" disabled="disabled"></td>
					<td id="mobileNumbercheckboxlabel"><?php echo $this->lang->line_arr('user->details_view->pref_mobile_number'); ?></td>
					<td id="altNumbercheckbox"><input type="checkbox" name="prefContact" id="prefContactAltNumber" value="altNumber" disabled="disabled"></td>
					<td id="altNumbercheckboxlabel"><?php echo $this->lang->line_arr('user->details_view->pref_alt_number'); ?></td>
				</tr>
			</table>
		</div> -->
		
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