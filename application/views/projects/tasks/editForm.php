<?php
	//Edit Individual
	$i = 0;
?>
<div class="create-link">
	<?php echo $internalLink; ?>
</div>
<?php echo $projectNameDescr; ?>
<h2>Edit Task</h3>
<form id="update_task_form" name="update_task_form">
	<input type="hidden" id='task_sno' value="<?php echo $tasks[$i]->task_id; ?>" />
	<div class='form'>
		<div class="label">Task Title:</div>
		<div><input type="text" name="task_name" id="task_name" value="<?php echo $tasks[$i]->task_name; ?>"></div>
		<div class="label">Description</div>
		<div><textarea rows="6" cols="30" name="task_desc" id="task_desc" ><?php echo $tasks[$i]->task_desc; ?></textarea></div>
		<div class="label">Start Date:</div>
		<div><input type="date" name="task_start_date" id="task_start_date" value="<?php echo $tasks[$i]->task_start_date; ?>" ></div>
		<div class="label">End Date:</div>
		<div><input type="date" name="task_end_date" id="task_end_date" value="<?php echo $tasks[$i]->task_end_date; ?>" ></div>
		<div class="label">Status:</div>
		<div>
			<input type="hidden" name="db_task_status" id="db_task_status" value="<?php echo $tasks[$i]->task_status; ?>">
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
		<div><input type="text" name="task_owner_id" id="task_owner_id" value="<?php echo $tasks[$i]->task_owner_id; ?>"></div>
		<div class="label">% Complete:</div>
		<div><input type="text" name="task_percent_complete" id="task_percent_complete" value="<?php echo $tasks[$i]->task_percent_complete; ?>"></div>
		<div class="label">Dependency:</div>
		<div><input type="text" name="task_dependency" id="task_dependency" value="<?php echo $tasks[$i]->task_dependency; ?>"></div>
		<div class="label">Trade Type:</div>
		<div><input type="text" name="task_trade_type" id="task_trade_type" value="<?php echo $tasks[$i]->task_trade_type; ?>"></div>
		<p class="button-panel">
			<button type="button" id="update_task_submit" onclick="projectObj._tasks.updateValidate()">Update Task</button>
		</p>
	</div>
</form>
