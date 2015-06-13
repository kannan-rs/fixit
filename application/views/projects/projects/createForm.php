<!-- Add Function Start -->
<h2>Create Project</h2>
<form id="create_project_form" name="create_project_form" class="inputForm">
	<div class='form'>
		<div class="label">Project Title:</div>
		<div>
			<input type="text" name="projectTitle" id="projectTitle" value="" required>
		</div>
		<div class="label">Description:</div>
		<div>
			<textarea type="text" name="description" id="description" rows="10" cols="70" required></textarea>
		</div>
		<div class="label notMandatory">Associated Claim Number</div>
		<div>
			<input type="text" name="associated_claim_num" id="associated_claim_num" value="">
		</div>
		<div class="label">Project Type</div>
		<div>
			<select name="project_type" id="project_type">
			<option value="">--Select Project Type--</option>
			<option value="commercial">Commercial</option>
			<option value="personal">Personal</option>
			</select>
		</div>
		<div class="label">Project Status</div>
		<div>
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
			<input type="text" name="project_budget" id="project_budget" value="" >
		</div>
		<div class="label">Property Owner ID</div>
		<div>
			<input type="text" name="property_owner_id" id="property_owner_id" value="">
		</div>
		<div class="label">Contractor Name</div>
		<div>
			<select class="multi-select" name="contractorId" id="contractorId" multiple="multiple">
			</select>
			Do you want to add new contractor? <a href="javascript:void(0);" onclick="projectObj._contractors.createForm('popup')">Click Here</a>.
		</div>
		<div class="label">Adjuster ID</div>
		<div>
			<input type="text" name="adjuster_id" id="adjuster_id" value="" >
		</div>
		<div class="label">Customer ID</div>
		<div>
			<input type="text" name="customer_id" id="customer_id" value="" >
		</div>
		<div class="label">Paid from Budget</div>
		<div>
			<input type="text" name="paid_from_budget" id="paid_from_budget" value="" >
		</div>
		<div class="label">Remaining Bbudget</div>
		<div>
			<input type="text" name="remaining_budget" id="remaining_budget" value="">
		</div>
		<?php
		if($userType == "admin") {
		?>
		<div class="label">Referral Fee</div>
		<div>
			<input type="text" name="referral_fee" id="referral_fee" value="">
		</div>
		<?php
		}
		?>
		<div class="label">Project Lender</div>
		<div>
			<input type="text" name="project_lender" id="project_lender" value="">
		</div>
		<div class="label">Lend Amount</div>
		<div>
			<input type="text" name="lend_amount" id="lend_amount" value="" required>
		</div>
		<!--
		<div class="label">Assign to User:</div>
		<div>
			<select name="assign_user" id="assign_user" required>
				<option value="">--select User--</option>
				<?php
					if(isset($users) && is_array($users)) {
						for( $i = 0; $i < count($users); $i++) {
							echo "<option value='". $users[$i]->sno ."'>". $users[$i]->user_name ."</option>";
						}
					}
				?>
			</select>
		</div>
		-->
		<p class="button-panel">
			<button type="button" id="create_project_submit" onclick="projectObj._projects.createValidate()">Create Project</button>
		</p>
	</div>
</form>
<!-- Add Function Ends -->
