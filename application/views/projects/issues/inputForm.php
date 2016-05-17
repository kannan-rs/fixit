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
	$headerText = $issueId != "" ? $this->lang->line_arr('issues->headers->eidt') : $this->lang->line_arr('issues->headers->create');
}



?>

<?php
if(!empty($headerText)) {
?>
<div class="header-options">
	<h2 class=''>$headerText</h2>
</div>
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
				<td class="label"><?php echo $this->lang->line_arr('issues->input_form->issueName'); ?>:</td>
				<td>
					<input type="text" name="issueName" id="issueName" value="<?php echo $issueName;?>" required placeholder="<?php echo $this->lang->line_arr('issues->input_form->issueName_ph'); ?>">
				</td>
			</tr>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('issues->input_form->issueDescr'); ?>:</td>
				<td>
					<textarea name="issueDescr" id="issueDescr" class="small-textarea" required placeholder="<?php echo $this->lang->line_arr('issues->input_form->issueDescr_ph'); ?>"><?php echo $issueDescr;?></textarea>
				</td>
			</tr>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('issues->input_form->assignedToUserType'); ?></td>
				<td>
					<select name="assignedToUserType" id="assignedToUserType" onchange="_issues.showAssignedToOptions()">
						<option value=""><?php echo $this->lang->line_arr('issues->input_form->assignedToUserType_option_0'); ?></option>
						<option value="customer">Customer</option>
						<option value="contractor">Service Provider</option>
						<!-- <option value="adjuster">Adjuster</option> -->
					</select>
				</td>
			</tr>
			
			<tr id="assignedToUserCustomer">
				<td class="label"><?php echo $this->lang->line_arr('issues->input_form->issueAssignedToCustomer'); ?></td>
				<td>
					<input type="text" name="issueAssignedToCustomer" id="issueAssignedToCustomer" value="" 
						required placeholder="<?php echo $this->lang->line_arr('issues->input_form->issueAssignedToCustomer_ph'); ?>" disabled>
				</td>
			</tr>

			<!-- Project Service Provider Search and Adding -->
			<tr class="issue-contractor-for-project-result" id="assignedToUserContractor">
				<!-- <div class="issue-contractor-for-project"> -->
				<td class="label"><?php echo $this->lang->line_arr('issues->input_form->issueContractorResult'); ?></td>
				<!-- </div> -->

				<td>
					<ul id="issueContractorResult" class="connectedSortable"></ul>
				</td>
				<!-- <div style="clear:both;"></div> -->
			</tr>

			<!-- Project Adjuster Search and Adding -->
			<!-- <tr class="issue-adjuster-for-project-result"  id="assignedToUserAdjuster">
				<td class="label"><?php echo $this->lang->line_arr('issues->input_form->issueAdjusterResult'); ?></td>
				<td>
					<ul id="issueAdjusterResult" class="connectedSortable">
					</ul>
				</td>
			</tr> -->

			<tr>
				<td class="label"><?php echo $this->lang->line_arr('issues->input_form->issueFromdate'); ?></td>
				<td>
					<input type="text" name="issueFromdate" id="issueFromdate" value="<?php echo $issueFromdate;?>" placeholder="<?php echo $this->lang->line_arr('issues->input_form->issueFromdate_ph'); ?>" required>
				</td>
			</tr>
			
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('issues->input_form->issueStatus'); ?></td>
				<td>
					<select name="issueStatus" id="issueStatus" required>
						<option value=""><?php echo $this->lang->line_arr('issues->input_form->issueStatus_option_0'); ?></option>
						<option value="open">Open</option>
						<option value="inProgress">In Progress</option>
						<option value="cancelled">Cancelled</option>
						<option value="closed">Closed</option>
					</select>
				</td>
			</tr>
			
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('issues->input_form->issueNotes'); ?>:</td>
				<td>
					<textarea name="issueNotes" id="issueNotes" class="small-textarea" placeholder="<?php echo $this->lang->line_arr('issues->input_form->issueNotes_ph'); ?>" required><?php echo $issueNotes;?></textarea>
				</td>
			</tr>
			
			<tr>
				<td colspan="2">
					<p class="button-panel">
						<?php
						if($issueId != "") {
						?>
						<button type="button" id="update_issue_submit" onclick="_issues.updateValidate('<?php echo $openAs; ?>', '<?php echo $popupType;?>')"><?php echo $this->lang->line_arr('issues->buttons_links->update'); ?></button>
						<?php
						} else {
						?>
						<button type="button" id="create_issue_submit" onclick="_issues.createValidate('<?php echo $openAs; ?>', '<?php echo $popupType;?>')"><?php echo $this->lang->line_arr('issues->buttons_links->create'); ?></button>
						<?php
						}
						?>
						
						<?php
						if($openAs == "popup") {
						?>
						<button type="button" id="cancelButton" onclick="_projects.closeDialog({popupType: '<?php echo $popupType; ?>'})"><?php echo $this->lang->line_arr('buttons->cancel'); ?></button>
						<?php
						} else {
						?>
						<button type="reset" id="resetButton" onclick=""><?php echo $this->lang->line_arr('buttons->clear'); ?></button>
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