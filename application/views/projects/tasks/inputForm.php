<?php
	//Edit Individual
$edit = false;
$prefix = "create";
$headerText = "Create Task";
$createFn = "projectObj._tasks.createValidate('".$viewFor."')";
$updateFn = "projectObj._tasks.updateValidate('".$viewFor."')";

if(isset($tasks) && count($tasks)) {
	$i = 0;
	$edit = true;
	$prefix = "update";
	$headerText = "Edit Task";
	$individualTask = $tasks[0];
}

$task_id 					= isset($individualTask) ? $individualTask->task_id : "";
$project_id 				= isset($individualTask) ? $individualTask->project_id : "";
$task_name 					= isset($individualTask) ? $individualTask->task_name : "";
$task_desc 					= isset($individualTask) ? $individualTask->task_desc : "";
$task_start_date_for_view 	= isset($individualTask) ? $individualTask->task_start_date_for_view : "";
$task_end_date_for_view 	= isset($individualTask) ? $individualTask->task_end_date_for_view : "";
$task_status 				= isset($individualTask) ? $individualTask->task_status : "";
$task_percent_complete 		= isset($individualTask) ? $individualTask->task_percent_complete : "";
$task_percent_complete 		= isset($individualTask) ? $individualTask->task_percent_complete : "";
$task_owner_id 				= isset($individualTask) ? $individualTask->task_owner_id : "";
$task_dependency 			= isset($individualTask) ? $individualTask->task_dependency : "";
$task_trade_type 			= isset($individualTask) ? $individualTask->task_trade_type : "";

	 

if($viewFor == "" || $viewFor != "projectViewOne") {
?>
	<div class="create-link"><?php echo $internalLink; ?></div>
	<?php echo $projectNameDescr; ?>
	<h2><?php echo $headerText; ?></h2>
<?php
}
?>

<form id="<?php echo $prefix; ?>_task_form" name="<?php echo $prefix; ?>_task_form" class="inputForm">
	<input type="hidden" id='task_sno' value="<?php echo $task_id; ?>" />
	<input type="hidden" id='projectId' value="<?php echo $project_id; ?>" />
	<table class='form'>
		<tbody>
			<tr>
				<td class="label">Task Title:</td>
				<td><input type="text" name="task_name" id="task_name" value="<?php echo $task_name; ?>" required></td>
			</tr>
			<tr>
				<td class="label">Description</td>
				<td><textarea rows="6" cols="30" name="task_desc" id="task_desc" required><?php echo $task_desc; ?></textarea></td>
			</tr>
			<tr>
				<td class="label">Start Date:</td>
				<td><input type="text" name="task_start_date" id="task_start_date" value="<?php echo $task_start_date_for_view; ?>" required></td>
			</tr>
			<tr>
				<td class="label">End Date:</td>
				<td><input type="text" name="task_end_date" id="task_end_date" value="<?php echo $task_end_date_for_view; ?>" required></td>
			</tr>
			<tr>
				<td class="label">Status:</td>
				<td>
					<input type="hidden" name="db_task_status" id="db_task_status" value="<?php echo $task_status; ?>">
					<select name="task_status" id="task_status"  onchange="projectObj._tasks.setPercentage(this.value)" required>
						<option value="">--Select Task Status--</option>
						<option value="task created">Task Created</option>
						<option value="not assigned">Not Assigned</option>
						<option value="assigned to contractor">Assigned to Contractor</option>
						<option value="work in progress">Work in Progress</option>
						<option value="completed">Completed</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="label">% Complete:</td>
				<td><input type="text" name="task_percent_complete" id="task_percent_complete" onchange="projectObj._tasks.percentageChange(this.value)" defaultValue="<?php echo $task_percent_complete; ?>" value="<?php echo $task_percent_complete; ?>" required></td>
			</tr>
			<tr>
				<td class="label notMandatory">Choose Owner:</td>
				<td>
					<input type="hidden" name="taskOwnerIdDb" id="taskOwnerIdDb" value="<?php echo $task_owner_id; ?>">
					<ul id="ownerSearchResult" class="connectedSortable owner-search-result"></ul>
				</td>
			</tr>
			<tr>
				<td class="label notMandatory">Dependency:</td>
				<td><input type="text" name="task_dependency" id="task_dependency" value="<?php echo $task_dependency; ?>"></td>
			</tr>
			<tr>
				<td class="label notMandatory">Trade Type:</td>
				<td><input type="text" name="task_trade_type" id="task_trade_type" value="<?php echo $task_trade_type; ?>"></td>
			</tr>
			<tr>
				<td colspan="2">
					<p class="button-panel">
						<button type="button" id="<?php echo $prefix; ?>_task_submit" onclick="projectObj._tasks.<?php echo $prefix; ?>Validate('<?php echo $viewFor; ?>')"><?php echo $prefix; ?> Task</button>
						<button type="button" onclick="projectObj._projects.closeDialog()">Cancel</button>
					</p>
				</td>
			</tr>
			<tr>
				<td colspan="2"><?php echo $this->lang->line('mandatory_field_text'); ?></td>
			</tr>
		</tbody>
	</table>
</form>