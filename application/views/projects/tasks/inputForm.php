<?php
	//Edit Individual
$edit = false;
$prefix = "create";
$createFn = "projectObj._tasks.createValidate('".$viewFor."')";
$updateFn = "projectObj._tasks.updateValidate('".$viewFor."')";

if(isset($tasks) && count($tasks)) {
	$i = 0;
	$edit = true;
	$prefix = "update";
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
	<h2><?php echo $this->lang->line_arr('tasks->headers->'.$prefix); ?><?php echo $headerText; ?></h2>
<?php
}
?>

<form id="<?php echo $prefix; ?>_task_form" name="<?php echo $prefix; ?>_task_form" class="inputForm">
	<input type="hidden" id='task_sno' value="<?php echo $task_id; ?>" />
	<input type="hidden" id='projectId' value="<?php echo $project_id; ?>" />
	<table class='form'>
		<tbody>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('tasks->input_form->task_name'); ?></td>
				<td><input type="text" name="task_name" id="task_name" value="<?php echo $task_name; ?>" placeholder="<?php echo $this->lang->line_arr('tasks->input_form->task_name_ph'); ?>" required></td>
			</tr>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('tasks->input_form->task_desc'); ?></td>
				<td><textarea rows="6" cols="30" name="task_desc" id="task_desc" placeholder="<?php echo $this->lang->line_arr('tasks->input_form->task_desc_ph'); ?>" required><?php echo $task_desc; ?></textarea></td>
			</tr>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('tasks->input_form->task_start_date'); ?></td>
				<td><input type="text" name="task_start_date" id="task_start_date" value="<?php echo $task_start_date_for_view; ?>" placeholder="<?php echo $this->lang->line_arr('tasks->input_form->task_start_date_ph'); ?>" required></td>
			</tr>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('tasks->input_form->task_end_date'); ?></td>
				<td><input type="text" name="task_end_date" id="task_end_date" value="<?php echo $task_end_date_for_view; ?>" placeholder="<?php echo $this->lang->line_arr('tasks->input_form->task_end_date_ph'); ?>" required></td>
			</tr>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('tasks->input_form->task_status'); ?>:</td>
				<td>
					<input type="hidden" name="db_task_status" id="db_task_status" value="<?php echo $task_status; ?>">
					<select name="task_status" id="task_status"  onchange="projectObj._tasks.setPercentage(this.value)" required>
						<option value=""><?php echo $this->lang->line_arr('tasks->input_form->task_status_option_0'); ?></option>
						<option value="task created">Task Created</option>
						<option value="not assigned">Not Assigned</option>
						<option value="assigned to contractor">Assigned to Contractor</option>
						<option value="work in progress">Work in Progress</option>
						<option value="completed">Completed</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('tasks->input_form->task_percent_complete'); ?>:</td>
				<td><input type="text" name="task_percent_complete" id="task_percent_complete" onchange="projectObj._tasks.percentageChange(this.value)" placeholder="<?php echo $this->lang->line_arr('tasks->input_form->task_percent_complete_ph'); ?>" defaultValue="<?php echo $task_percent_complete; ?>" value="<?php echo $task_percent_complete; ?>" required></td>
			</tr>
			<tr>
				<td class="label notMandatory"><?php echo $this->lang->line_arr('tasks->input_form->taskOwnerId'); ?></td>
				<td>
					<input type="hidden" name="taskOwnerIdDb" id="taskOwnerIdDb" value="<?php echo $task_owner_id; ?>">
					<ul id="ownerSearchResult" class="connectedSortable owner-search-result"></ul>
				</td>
			</tr>
			<tr>
				<td class="label notMandatory"><?php echo $this->lang->line_arr('tasks->input_form->task_dependency'); ?>:</td>
				<td><input type="text" name="task_dependency" id="task_dependency" placeholder="<?php echo $this->lang->line_arr('tasks->input_form->task_dependency_ph'); ?>" value="<?php echo $task_dependency; ?>"></td>
			</tr>
			<tr>
				<td class="label notMandatory"><?php echo $this->lang->line_arr('tasks->input_form->task_trade_type'); ?>:</td>
				<td><input type="text" name="task_trade_type" id="task_trade_type" placeholder="<?php echo $this->lang->line_arr('tasks->input_form->task_trade_type_ph'); ?>" value="<?php echo $task_trade_type; ?>"></td>
			</tr>
			<tr>
				<td colspan="2">
					<p class="button-panel">
						<button type="button" id="<?php echo $prefix; ?>_task_submit" onclick="projectObj._tasks.<?php echo $prefix; ?>Validate('<?php echo $viewFor; ?>')"><?php echo $this->lang->line_arr('tasks->buttons_links->'.$prefix); ?></button>
						<button type="button" onclick="projectObj._projects.closeDialog()"><?php echo $this->lang->line_arr('buttons->cancel'); ?></button>
					</p>
				</td>
			</tr>
			<tr>
				<td colspan="2"><?php echo $this->lang->line('mandatory_field_text'); ?></td>
			</tr>
		</tbody>
	</table>
</form>