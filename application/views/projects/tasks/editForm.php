<?php
	//Edit Individual
	$i = 0;
	 $updateFn = "projectObj._tasks.updateValidate('".$viewFor."')";

	if($viewFor == "" || $viewFor != "projectViewOne") {
?>
	<div class="create-link">
		<?php echo $internalLink; ?>
	</div>
	<?php echo $projectNameDescr; ?>
	<h2>Edit Task</h2>
<?php
	}
?>
<form id="update_task_form" name="update_task_form">
	<input type="hidden" id='task_sno' value="<?php echo $tasks[$i]->task_id; ?>" />
	<input type="hidden" id='projectId' value="<?php echo $tasks[$i]->project_id; ?>" />
	<div class='form'>
		<div class="label">Task Title:</div>
		<div><input type="text" name="task_name" id="task_name" value="<?php echo $tasks[$i]->task_name; ?>"></div>
		<div class="label">Description</div>
		<div><textarea rows="6" cols="30" name="task_desc" id="task_desc" ><?php echo $tasks[$i]->task_desc; ?></textarea></div>
		<div class="label">Start Date:</div>
		<div><input type="text" name="task_start_date" id="task_start_date" value="<?php echo $tasks[$i]->task_start_date_for_view; ?>" ></div>
		<div class="label">End Date:</div>
		<div><input type="text" name="task_end_date" id="task_end_date" value="<?php echo $tasks[$i]->task_end_date_for_view; ?>" ></div>
		<div class="label">Status:</div>
		<div>
			<input type="hidden" name="db_task_status" id="db_task_status" value="<?php echo $tasks[$i]->task_status; ?>">
			<select name="task_status" id="task_status"  onchange="projectObj._tasks.setPercentage(this.value)">
				<option value="">--Select Task Status--</option>
				<option value="task created">Task Created</option>
				<option value="not assigned">Not Assigned</option>
				<option value="assigned to contractor">Assigned to Contractor</option>
				<option value="work in progress">Work in Progress</option>
				<option value="completed">Completed</option>
			</select>
		</div>
		<div class="label">% Complete:</div>
		<div><input type="text" name="task_percent_complete" id="task_percent_complete" onchange="projectObj._tasks.percentageChange(this.value)" defaultValue="<?php echo $tasks[$i]->task_percent_complete; ?>" value="<?php echo $tasks[$i]->task_percent_complete; ?>"></div>
		<div class="label">Choose Owner:</div>
		<div>
			<input type="hidden" name="taskOwnerIdDb" id="taskOwnerIdDb" value="<?php echo $tasks[$i]->task_owner_id; ?>">
			<ul id="ownerSearchResult" class="connectedSortable owner-search-result"></ul>
		</div>
		<div class="label">Dependency:</div>
		<div><input type="text" name="task_dependency" id="task_dependency" value="<?php echo $tasks[$i]->task_dependency; ?>"></div>
		<div class="label">Trade Type:</div>
		<div><input type="text" name="task_trade_type" id="task_trade_type" value="<?php echo $tasks[$i]->task_trade_type; ?>"></div>
		<p class="button-panel">
			<button type="button" id="update_task_submit" onclick="<?php echo $updateFn; ?>">Update Task</button>
			<button type="button" onclick="projectObj._projects.closeDialog()">Cancel</button>
		</p>
	</div>
</form>
