<?php
		$users 			= $users[0];
		$user_details 	= $user_details[0];

		$heading = $viewFrom == "security" ? $this->lang->line_arr('user->headers->admin_view_one') : $this->lang->line_arr('user->headers->view_one');
		$heading = $viewFrom == "projects" ? "" : $heading;
		$heading = $heading != "" ? "<div class=\"header-options\"><h2 class=''>".$heading."</h2></div>" : $heading;
?>
<?php if($viewFrom == "security") { ?>
<!-- <div class="create-link">
	<a href="javascript:void(0);" onclick="_users.createForm()">
		<?php echo $this->lang->line_arr('user->buttons_links->create'); ?>
	</a>
</div> -->
<?php } else if($viewFrom == "") { ?>
<div class="create-link">
	<a href="javascript:void(0);" onclick="home._userInfo.editPage(<?php echo $user_details->sno; ?>);">
		<?php echo $this->lang->line_arr('user->buttons_links->edit_details'); ?>
	</a>
</div>
<?php } ?>

<?php echo $heading; ?>

<?php echo $noticeFile; ?>

<input type="hidden" name="user_details_sno" id="user_details_sno" value="<?php echo $user_details->sno; ?>">
<input type="hidden" name="dbPrimaryContact" id="dbPrimaryContact" value="<?php echo $user_details->primary_contact; ?>">
<input type="hidden" name="dbPrefContact" id="dbPrefContact" value="<?php echo $user_details->contact_pref; ?>">
<input type="hidden" name="viewonly" value="true">

<table cellspacing="0" class="viewOne">	
	<tbody>
		<tr>
			<td class='label'><?php echo $this->lang->line_arr('user->details_view->role'); ?></td>
			<td class="capitalize role_id"><?php echo $users->role_id; ?></td>
		</tr>
		<tr>
			<td class="label"><?php echo $this->lang->line_arr('user->details_view->firstName'); ?></td>
			<td class="capitalize"><?php echo $user_details->first_name; ?></td>
		</tr>
		<tr>
			<td class="label"><?php echo $this->lang->line_arr('user->details_view->lastName'); ?></td>
			<td class="capitalize"><?php echo $user_details->last_name; ?></td>
		</tr>
		<!-- <tr>
			<td class="label"><?php echo $this->lang->line_arr('user->details_view->belongsTo'); ?></td>
			<td class="capitalize"><?php echo !empty($user_details->belongs_to) ? $user_details->belongs_to : "-NA-"; ?></td>
		</tr>
			<?php 
			if($user_details->belongs_to == "contractor") { 
			?>
				<tr>
					<td class="label"><?php echo $this->lang->line_arr('user->details_view->contractor'); ?></td>
					<td class="capitalize"><?php echo $belongsToName; ?></td>
				</tr>
			<?php 
			} else if($user_details->belongs_to == "adjuster") {
			?>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('user->details_view->adjuster'); ?></td>
				<td class="capitalize"><?php echo $belongsToName; ?></td>
			</tr>
			<?php
			}
			?>
		<tr> -->
			<td class="label"><?php echo $this->lang->line_arr('user->details_view->userStatus'); ?></td>
			<td class="capitalize"><?php echo $user_details->status; ?></td>
		</tr>
		<tr>
			<td class="label"><?php echo $this->lang->line_arr('user->details_view->activeStartDate'); ?></td>
			<td><?php echo explode(" ",$user_details->active_start_date)[0]; ?></td>
		</tr>
		<tr>
			<td class="label"><?php echo $this->lang->line_arr('user->details_view->activeEndDate'); ?></td>
			<td><?php echo explode(" ",$user_details->active_end_date)[0]; ?></td>	
		</tr>
		<tr>
			<td class="label"><?php echo $this->lang->line_arr('user->details_view->email'); ?></td>
			<td><?php echo $user_details->email; ?></td>
		</tr>
		<tr>
			<td class="label"><?php echo $this->lang->line_arr('user->details_view->contactPhoneNumber'); ?></td>
			<td><?php echo $user_details->contact_ph1; ?></td>
		</tr>
		<tr>
			<td class="label"><?php echo $this->lang->line_arr('user->details_view->mobileNumber'); ?></td>
			<?php
			if(isset($user_details->contact_mobile) && $user_details->contact_mobile != "") {
			?>
			<td>
				<?php echo $user_details->contact_mobile; ?><br/>
				<input type="radio" name="primaryContact" id="primaryMobileNumber" value="mobile" disabled="disabled">&nbsp;&nbsp;
				<?php echo $this->lang->line_arr('user->details_view->primaryContact'); ?>
				<!-- <table  class="innerOption">
					<tr>
						<td colspan="2"><?php echo $user_details->contact_mobile; ?></td>
					</tr>
					<tr id="mobileradio">
						<td>
							<input type="radio" name="primaryContact" id="primaryMobileNumber" value="mobile" disabled="disabled">
						</td>
						<td><span><?php echo $this->lang->line_arr('user->details_view->primaryContact'); ?></span></td>
					</tr>
				</table> -->
			</td>
			<?php
			} else {
			?>
			<td>-NA-</td>
			<?php 
			}
			?>
		</tr>
		<tr>
			<td class="label"><?php echo $this->lang->line_arr('user->details_view->altNumber'); ?></td>
			<?php
			if(isset($user_details->contact_alt_mobile) && $user_details->contact_alt_mobile != "") {
			?>
			<td>
				<?php echo $user_details->contact_alt_mobile; ?><br/>
				<input type="radio" name="primaryContact" id="primaryAlternateNumber" value="alternate" disabled="disabled">&nbsp;&nbsp;
				<?php echo $this->lang->line_arr('user->details_view->primaryContact'); ?>
				<!-- <table class="innerOption">
					<tr>
						<td colspan="2"><?php echo $user_details->contact_alt_mobile; ?></td>
					</tr>
					<tr id="alternateradio">
						<td>
							<input type="radio" name="primaryContact" id="primaryAlternateNumber" value="alternate" disabled="disabled">
						</td>
						<td><span><?php echo $this->lang->line_arr('user->details_view->primaryContact'); ?></span></td>
					</tr>
				</table> -->
			</td>
			<?php
			} else {
			?>
			<td>-NA-</td>
			<?php 
			}
			?>
		</tr>
		<!-- 
		<tr>
			<td class="label prefMode"><?php echo $this->lang->line_arr('user->details_view->prefMode'); ?></td>
			<td>
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
			</td>
		</tr>
		-->
			
			<?php
				echo $addressFile;
			?>
			<!-- Referred to Details -->
			<?php 
			if($user_details->referred_by) {
				echo "<tr>";
				echo "<td class='label'>User Referred By:</td>";
				echo "<td>".$user_details->referred_by."</td>";
				echo "</tr>";
				
				if($user_details->referred_by == "contractor") { 
					echo "<tr>";
					echo "<td class='label'>Referred By Contractor Company:</td>";
					echo "<td>".$referredByName."</td>";
					echo "</tr>";
				} else if($user_details->referred_by == "adjuster") {
					echo "<tr>";
					echo "<td class='label'>Referred By Adjuster Company:</td>";
					echo "<td>".$referredByName."</td>";
					echo "</tr>";
				} else {
					echo "<tr>";
					echo "<td class='label'>Referred By Customer Name:</td>";
					echo "<td>".($referredByName ? $referredByName : "-NA-")."</td>";
					echo "</tr>";
				}
			}
			?>
	</tbody>
</table>