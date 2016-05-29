<?php
		$users 			= $users[0];
		$user_details 	= $user_details[0];

		$heading = $viewFrom == "security" ? $this->lang->line_arr('user->headers->admin_view_one') : $this->lang->line_arr('user->headers->view_one');
		$heading = $viewFrom == "projects" ? "" : $heading;
		//$heading = $heading != "" ? "<div class=\"header-options\"><h2 class=''>".$heading."</h2></div>" : $heading;
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

<?php 
	if( !empty($heading) ) {
?>
	<div class="header-options">
		<h2 class=''> <?php echo $heading; ?> </h2>
		<span class="options-icon">
			<?php
			if ( $this->session->userdata('logged_in_email') == $users->user_name ) {
			?>
			<span>
				<a class="step fi-page-edit size-21" href="/main/home/view_my_details/edit/<?php echo $users->sno; ?>" title="Edit Personal Details" >
				</a>
			</span>
			<?php	
			}
			?>
		</span>
	</div>
<?php
	}
?>

<?php echo $noticeFile; ?>

<input type="hidden" name="user_details_sno" id="user_details_sno" value="<?php echo $user_details->sno; ?>">
<input type="hidden" name="dbPrefContact" id="dbPrefContact" value="<?php echo $user_details->contact_pref; ?>">
<input type="hidden" name="viewonly" value="true">

<table cellspacing="0" class="viewOne">	
	<tbody>
		<tr>
			<td class='label'><?php echo $this->lang->line_arr('user->details_view->role'); ?></td>
			<td class="capitalize role_id"><?php echo $users->role_disp_name; ?></td>
		</tr>
		<?php
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
			}
		?>
		<tr>
			<td class="label"><?php echo $this->lang->line_arr('user->details_view->firstName'); ?></td>
			<td class="capitalize"><?php echo $user_details->first_name; ?></td>
		</tr>
		<tr>
			<td class="label"><?php echo $this->lang->line_arr('user->details_view->lastName'); ?></td>
			<td class="capitalize"><?php echo $user_details->last_name; ?></td>
		</tr>
		<tr>
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
			<?php 
				echo $user_details->contact_mobile; 
				if( $user_details->primary_contact == "mobile" || empty($user_details->primary_contact) ) {
					echo " ( ".$this->lang->line_arr('user->details_view->primaryContact')." )";
				}
			?>
			</td>
			<?php
			} else {
			?>
			<td>--</td>
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
				<?php 
				echo $user_details->contact_alt_mobile; 
				if( $user_details->primary_contact == "alternate" ) {
					echo " ( ".$this->lang->line_arr('user->details_view->primaryContact')." )";
				}
				?>
			</td>
			<?php
			} else {
			?>
			<td>--</td>
			<?php 
			}
			?>
		</tr>
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
					echo "<td class='label'>Referred By Service Provider Company:</td>";
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