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
	<input type="hidden" name="issueProjectId" id="issueProjectId" value="<?php echo $projectId; ?>" />
	<input type="hidden" name="issueTaskId" id="issueTaskId" value="<?php echo $taskId; ?>" />
	<input type="hidden" name="issueId" id="issueId" value="<?php echo $issueId; ?>" />
	<input type="hidden" name="assignedToUserTypeDB" id="assignedToUserTypeDB" value="<?php echo $assignedToUserTypeDB; ?>" />
	<input type="hidden" name="assignedToUserDB" id="assignedToUserDB" value="<?php echo $assignedToUserDB; ?>" />
	<input type="hidden" name="issueStatusDB" id="issueStatusDB" />

	<table class='form'>
		<tbody>
			<tr>
				<td class="label">Issue Name:</td>
				<td>
					<input type="text" name="issueName" id="issueName" value="<?php echo $issueName;?>" required placeholder="Issue Name">
				</td>
			</tr>
			<tr>
				<td class="label">Issue Description:</td>
				<td>
					<textarea name="issueDescr" id="issueDescr" class="small-textarea" required placeholder="Issue Description"><?php echo $issueDescr;?></textarea>
				</td>
			</tr>
			<tr>
				<td class="label">Assigned to</td>
				<td>
					<select name="assignedToUserType" id="assignedToUserType" onchange="projectObj._issues.showAssignedToOptions()">
						<option value="">--Select User Type--</option>
						<option value="customer">Customer</option>
						<option value="contractor">Contractor</option>
						<option value="adjuster">Adjuster</option>
					</select>
				</td>
			</tr>
			
			<tr id="assignedToUserCustomer">
				<td class="label">Assigned to Customer</td>
				<td>
					<input type="text" name="issueAssignedToCustomer" id="issueAssignedToCustomer" value="" required placeholder="Assigned To Customer" disabled>
				</td>
			</tr>

			<!-- Project Contractor Search and Adding -->
			<tr class="issue-contractor-for-project-result" id="assignedToUserContractor">
				<!-- <div class="issue-contractor-for-project"> -->
					<td class="label">Select Assigned to Contractor</td>
				<!-- </div> -->

				<td>
					<ul id="issueContractorResult" class="connectedSortable"></ul>
				</td>
				<!-- <div style="clear:both;"></div> -->
			</tr>

			<!-- Project Adjuster Search and Adding -->
			<tr class="issue-adjuster-for-project-result"  id="assignedToUserAdjuster">
				<td class="label">Select Assigned to Adjuster</td>
				<td>
					<ul id="issueAdjusterResult" class="connectedSortable">
					</ul>
				</td>
				<!-- <div style="clear:both;"></div> -->
			</tr>

			<tr>
				<td class="label">Issue From Date</td>
				<td>
					<input type="text" name="issueFromdate" id="issueFromdate" value="<?php echo $issueFromdate;?>" placeholder="Issue From Date" required>
				</td>
			</tr>
			
			<tr>
				<td class="label">Issue Status</td>
				<td>
					<select name="issueStatus" id="issueStatus" required>
						<option value="">--Select Status--</option>
						<option value="open">Open</option>
						<option value="inProgress">In Progress</option>
						<option value="cancelled">Cancelled</option>
						<option value="closed">Closed</option>
					</select>
				</td>
			</tr>
			
			<tr>
				<td class="label">Issue Notes:</td>
				<td>
					<textarea name="issueNotes" id="issueNotes" class="small-textarea" placeholder="Issue Notes" required><?php echo $issueNotes;?></textarea>
				</td>
			</tr>
			
			<tr>
				<td colspan="2">
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
				</td>
			</tr>
			<tr>
				<td colspan="2"><?php echo $this->lang->line('mandatory_field_text'); ?></td>
			</tr>
		</tbody>
	</table>
</form>
<!-- Add Function Ends -->