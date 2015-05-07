<div class="create-link">
	<a href="javascript:void(0);" onclick="projectObj._tasks.viewAll('<?php echo $projectId; ?>')">Tasks</a>
	<a href="javascript:void(0);" onclick="projectObj._notes.viewAll('<?php echo $projectId; ?>')">Notes</a>
	<a href="javascript:void(0);" onclick="projectObj._docs.viewAll('<?php echo $projectId; ?>')">Documents</a>
</div>
<div class="projectDetails">
	<div>Project Name: '<?php echo $projectName; ?>'</div>
	<div>Project Description : <?php echo $projectDescr; ?></div>
</div>
<!-- Add Function Start -->
<h2>Create Task</h3>
	<form id="create_task_form" name="create_task_form">
	<div class='form'>
		<input type="hidden" name="parentId" id="parentId" value="<?php echo $projcetId; ?>">
		<div class="label">Task Name:</div>
		<div><input type="text" name="task_name" id="task_name" value=""></div>
		<div class="label">Description:</div>
		<div><textarea type="text" name="task_desc" id="task_desc" rows="10" cols="70" ></textarea></div>
		<div class="label">Start Date:</div>
		<div><input type="date" name="task_start_date" id="task_start_date" value=""></div>
		<div class="label">End Date:</div>
		<div><input type="date" name="task_end_date" id="task_end_date" value=""></div>
		<div class="label">Status:</div>
		<div>
			<select name="task_status" id="task_status">
				<option value="">--Select Task Status--</option>
				<option value="task created">Task Created</option>
				<option value="not assigned">Not Assigned</option>
				<option value="assigned to contractor">Assigned to Contractor</option>
				<option value="work in progress">Work in Progress</option>
				<option value="completed">Completed</option>
			</select>
		</div>
		<div class="label">Owner ID:</div>
		<div><input type="text" name="task_owner_id" id="task_owner_id" value=""></div>
		<div class="label">% Complete:</div>
		<div><input type="text" name="task_percent_complete" id="task_percent_complete" value=""></div>
		<div class="label">Dependency:</div>
		<div><input type="text" name="task_dependency" id="task_dependency" value=""></div>
		<div class="label">Trade Type:</div>
		<div><input type="text" name="task_trade_type" id="task_trade_type" value=""></div>
		<p class="button-panel">
			<button type="button" id="create_task_submit" onclick="projectObj._tasks.createValidate()">Create Task</button>
		</p>
	</div>
</form>
<!-- Add Function Ends -->
