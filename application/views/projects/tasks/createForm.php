<?php
$createFn = "projectObj._tasks.createValidate('".$viewFor."')";

if($viewFor == "" || $viewFor != "projectViewOne") {
	echo "<div class=\"create-link\">".$internalLink."</div>";
	echo $projectNameDescr; 

	echo "<h2>Create Task</h3>";
}
?>
	<form id="create_task_form" name="create_task_form" class="inputForm">
	<div class='form'>
	<?php
		if($viewFor == "" || $viewFor != "projectViewOne") {
	?>
		<input type="hidden" name="parentId" id="parentId" value="<?php //echo $projectId; ?>">
	<?php
		}
	?>
		<div class="label">Task Name:</div>
		<div><input type="text" name="task_name" id="task_name" value="" required></div>
		<div class="label">Description:</div>
		<div><textarea type="text" name="task_desc" id="task_desc" rows="10" cols="70" required></textarea></div>
		<div class="label">Start Date:</div>
		<div><input type="text" name="task_start_date" id="task_start_date" value="" required></div>
		<div class="label">End Date:</div>
		<div><input type="text" name="task_end_date" id="task_end_date" value="" required></div>
		<div class="label">Status:</div>
		<div>
			<select name="task_status" id="task_status" onchange="projectObj._tasks.setPercentage(this.value);" required>
				<option value="">--Select Task Status--</option>
				<option value="task created">Task Created</option>
				<option value="not assigned">Not Assigned</option>
				<option value="assigned to contractor">Assigned to Contractor</option>
				<option value="work in progress">Work in Progress</option>
				<option value="completed">Completed</option>
			</select>
		</div>
		<div class="label">% Complete:</div>
		<div><input type="text" name="task_percent_complete" id="task_percent_complete" defaultValue="" onchange="projectObj._tasks.percentageChange(this.value)" required></div>
		<div class="label notMandatory">Choose Owner:</div>
		<div>
			<ul id="ownerSearchResult" class="connectedSortable owner-search-result"></ul>
		</div>
		<div class="label notMandatory">Dependency:</div>
		<div><input type="text" name="task_dependency" id="task_dependency" value=""></div>
		<div class="label notMandatory">Trade Type:</div>
		<div><input type="text" name="task_trade_type" id="task_trade_type" value=""></div>
		<p class="button-panel">
			<button type="button" id="create_task_submit" onclick="<?php echo $createFn; ?>">Add Task</button>
			<button type="button" onclick="projectObj._projects.closeDialog()">Cancel</button>
		</p>
	</div>
</form>
<!-- Add Function Ends -->
