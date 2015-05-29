<!--
<div class="create-link">
	<?php echo $internalLink; ?>
</div>
-->
<?php
	$i = 0;
?>
<!--
<h2>Edit Project</h3>
-->
<form id="update_project_form" name="update_project_form" class="inputForm">
	<input type="hidden" id='project_sno' value="<?php echo $projects[$i]->proj_id; ?>" />
	<div class='form'>
		<div class="label">Project Title:</div>
		<div>
			<input type="text" name="projectTitle" id="projectTitle" value="<?php echo $projects[$i]->project_name; ?>" required>
		</div>
		<div class="label">Description</div>
		<div><textarea rows="6" cols="30" name="description" id="description" required><?php echo $projects[$i]->project_descr; ?></textarea></div>
		<div class="label notMandatory">Associated Claim Number</div>
		<div>
			<input type="text" name="associated_claim_num" id="associated_claim_num" value="<?php echo $projects[$i]->associated_claim_num;?>">
		</div>
		<div class="label">Project Type</div>
		<div>
			<input type="hidden" id="db_project_type" name="db_project_type" value="<?php echo $projects[$i]->project_type;?>">
			<select name="project_type" id="project_type">
				<option value="">--Select Project Type--</option>
				<option value="commercial">Commercial</option>
				<option value="personal">Personal</option>
			</select>
		</div>
		<div class="label">Project Status</div>
		<div>
			<input type="hidden" id="db_project_status" name="db_project_status" value="<?php echo $projects[$i]->project_status;?>">
			<select name="project_status" id="project_status">
				<option value="">--Select Project Status--</option>
				<option value="project created">Project Created</option>
				<option value="not assigned">Not Assigned</option>
				<option value="assigned to contractor">Assigned to Contractor</option>
				<option value="work in progress">Work in Progress</option>
				<option value="completed">Completed</option>
			</select>
		</div>
		<div class="label">Project Budget</div>
		<div>
			<input type="text" name="project_budget" id="project_budget" value="<?php echo $projects[$i]->project_budget;?>" >
		</div>
		<div class="label">Property Owner ID</div>
		<div>
			<input type="text" name="property_owner_id" id="property_owner_id" value="<?php echo $projects[$i]->property_owner_id;?>">
		</div>
		<div class="label">Contractor ID</div>
		<div>
			<input type="text" name="contractor_id" id="contractor_id" value="<?php echo $projects[$i]->contractor_id;?>" >
		</div>
		<div class="label">Adjuster ID</div>
		<div>
			<input type="text" name="adjuster_id" id="adjuster_id" value="<?php echo $projects[$i]->adjuster_id;?>" >
		</div>
		<div class="label">Customer ID</div>
		<div>
			<input type="text" name="customer_id" id="customer_id" value="<?php echo $projects[$i]->customer_id;?>" >
		</div>
		<div class="label">Paid from Budget</div>
		<div>
			<input type="text" name="paid_from_budget" id="paid_from_budget" value="<?php echo $projects[$i]->paid_from_budget;?>" >
		</div>
		<div class="label">Remaining Budget</div>
		<div>
			<input type="text" name="remaining_budget" id="remaining_budget" value="<?php echo $projects[$i]->remaining_budget;?>">
		</div>
		<?php
		if($userType == "admin") {
		?>
		<div class="label">Referral Fee</div>
		<div>
			<input type="text" name="referral_fee" id="referral_fee" value="<?php echo $projects[$i]->referral_fee;?>">
		</div>
		<?php
		}
		?>
		<div class="label">Project Lender</div>
		<div>
			<input type="text" name="project_lender" id="project_lender" value="<?php echo $projects[$i]->project_lender;?>">
		</div>
		<div class="label">Lend Amount</div>
		<div>
			<input type="text" name="lend_amount" id="lend_amount" value="<?php echo $projects[$i]->lend_amount;?>">
		</div>
		<!--
			<div class="label">Assign to User:</div>
			<div>
				<select name="assign_user" id="assign_user" required>
					<option value="">--select User--</option>
					<?php
						if(isset($users) && is_array($users)) {
							for( $j = 0; $j < count($users); $j++) {
								$selected = ($projects[$i]->owner == $users[$j]->sno) ? "selected" : "";
								echo "<option value='". $users[$j]->sno ."' ".$selected.">". $users[$j]->user_name ."</option>";
							}
						}
					?>
				</select>
			</div>
		-->
		<p class="button-panel">
			<button type="button" id="update_project_submit" onclick="projectObj._projects.updateValidate()">Update Project</button>
		</p>
	</div>
</form>
