<!-- Add Function Start -->
<?php
$issueId = isset($issueId) && !empty($issueId) ? $issueId : "";

$issueName = isset($issues) && count($issues) ? $issues[0]->issue_name : "";
$issueDescr = isset($issues) && count($issues) ? $issues[0]->issue_desc : "";
$assignedToUserTypeDB = isset($issues) && count($issues) ? $issues[0]->assigned_to_user_type : "";
$assignedToUserDB = isset($issues) && count($issues) ? $issues[0]->assigned_to_user_id : "";
$issueFromdate = isset($issues) && count($issues) ? $issues[0]->issue_from_date : "";
$issueStatus = isset($issues) && count($issues) ? $issues[0]->status : "";
$issueNotes = isset($issues) && count($issues) ? $issues[0]->notes : "";

$headerText = "";
$prefix = $issueId ? "update" : "create";

if(!$openAs || $openAs != "popup") {
	$headerText = $issueId != "" ? "Edit Issue" : "Create Issue";
}



?>

<?php
if(!empty($headerText)) {
?>
<h2>$headerText</h2>
<?php
}
?>

<form id="<?php echo $prefix; ?>_issue_form" name="<?php echo $prefix; ?>_issue_form" class="inputForm">
	<div class='form'>
		<input type="hidden" name="issueProjectId" id="issueProjectId" value="<?php echo $projectId; ?>" />
		<input type="hidden" name="issueTaskId" id="issueTaskId" value="<?php echo $taskId; ?>" />
		<input type="hidden" name="issueId" id="issueId" value="<?php echo $issueId; ?>" />
		<input type="hidden" name="assignedToUserTypeDB" id="assignedToUserTypeDB" value="<?php echo $assignedToUserTypeDB; ?>" />
		<input type="hidden" name="assignedToUserDB" id="assignedToUserDB" value="<?php echo $assignedToUserDB; ?>" />
		<input type="hidden" name="issueStatusDB" id="issueStatusDB" />

		<div class="label">Issue Name:</div>
		<div>
			<input type="text" name="issueName" id="issueName" value="<?php echo $issueName;?>" required placeholder="Issue Name">
		</div>
		
		<div class="label">Issue Description:</div>
		<div>
			<textarea name="issueDescr" id="issueDescr" class="small-textarea" required placeholder="Issue Description"><?php echo $issueDescr;?></textarea>
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
			<input type="text" name="issueFromdate" id="issueFromdate" value="<?php echo $issueFromdate;?>" placeholder="Issue From Date" required>
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
			<textarea name="issueNotes" id="issueNotes" class="small-textarea" placeholder="Issue Notes" required><?php echo $issueNotes;?></textarea>
		</div>
		
		<p class="button-panel">
			<?php
			if($issueId != "") {
			?>
			<button type="button" id="update_issue_submit" onclick="projectObj._issues.updateValidate('<?php echo $openAs; ?>', '<?php echo $popupType;?>')">Update Issue</button>
			<?php
			} else {
			?>
			<button type="button" id="create_issue_submit" onclick="projectObj._issues.createValidate('<?php echo $openAs; ?>', '<?php echo $popupType;?>')">Create Issue</button>
			<?php
			}
			?>
			
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