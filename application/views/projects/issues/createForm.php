<!-- Add Function Start -->
<?php
if(!$openAs || $openAs != "popup") {
?>
<h2>Create Issue</h3>
<?php
}
?>
<form id="create_issue_form" name="create_issue_form" class="inputForm">
	<div class='form'>
		<input type="hidden" name="issueProjectId" id="issueProjectId" value="<?php echo $projectId; ?>" />
		<input type="hidden" name="issueTaskId" id="issueTaskId" value="<?php echo $taskId; ?>" />
		<input type="hidden" name="assignedToUserTypeDB" id="assignedToUserTypeDB" />
		<input type="hidden" name="issueStatusDB" id="issueStatusDB" />

		<div class="label">Issue Name:</div>
		<div>
			<input type="text" name="issueName" id="issueName" value="" required placeholder="Issue Name">
		</div>
		
		<div class="label">Issue Description:</div>
		<div>
			<textarea name="issueDescr" id="issueDescr" class="small-textarea" required placeholder="Issue Description"></textarea>
		</div>

		<div class="label">Assigned to</div>
		<div>
			<select name="assignedToUserType" id="assignedToUserType" onchange="projectObj._issues.showAssignedToOptions()">
				<option value="">--Select User Type--</option>
				<option value="customer">Customer</option>
				<option value="contractor">Contractor</option>
				<option value="adjuster">Adjuster</option>
			</select>
		</div>
		
		<div id="assignedToUserCustomer">
			<div class="label">Assigned to Customer</div>
			<div>
				<input type="text" name="issueAssignedToCustomer" id="issueAssignedToCustomer" value="" required placeholder="Assigned To Customer" disabled>
			</div>
		</div>

		<!-- Project Contractor Search and Adding -->
		<div class="issue-contractor-for-project-result" id="assignedToUserContractor">
			<div class="issue-contractor-for-project">
				<div class="label">Select Assigned to Contractor</div>
			</div>

			<div>
				<ul id="issueContractorResult" class="connectedSortable"></ul>
			</div>
			<div style="clear:both;"></div>
		</div>

		<!-- Project Adjuster Search and Adding -->
		<div class="issue-adjuster-for-project-result"  id="assignedToUserAdjuster">
			<div class="label">Select Assigned to Adjuster</div>
			<div>
				<ul id="issueAdjusterResult" class="connectedSortable">
				</ul>
			</div>
			<div style="clear:both;"></div>
		</div>

		<div class="label">Issue From Date</div>
		<div>
			<input type="text" name="issueFromdate" id="issueFromdate" value="" placeholder="Issue From Date" required>
		</div>
		
		<div class="label">Issue Status</div>
		<div>
			<select name="issueStatus" id="issueStatus" required>
				<option value="">--Select Status--</option>
				<option value="open">Open</option>
				<option value="inProgress">In Progress</option>
				<option value="cancelled">Cancelled</option>
				<option value="closed">Closed</option>
			</select>
		</div>
		
		<div class="label">Issue Notes:</div>
		<div>
			<textarea name="issueNotes" id="issueNotes" class="small-textarea" placeholder="Issue Notes" required></textarea>
		</div>
		
		<p class="button-panel">
			<button type="button" id="create_issue_submit" onclick="projectObj._issues.createValidate('<?php echo $openAs; ?>', '<?php echo $popupType;?>')">Create Issue</button>
			<?php
			if($openAs == "popup") {
			?>
			<button type="button" id="cancelButton" onclick="projectObj._projects.closeDialog({popupType: '<?php echo $popupType; ?>'})">Cancel</button>
			<?php
			} else {
			?>
			<button type="reset" id="resetButton" onclick="">Clear</button>
			<?php	
			}
			?>
		</p>
	</div>
</form>
<!-- Add Function Ends -->