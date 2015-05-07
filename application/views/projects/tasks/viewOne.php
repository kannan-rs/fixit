<div class="create-link">
	<a href="javascript:void(0);" onclick="projectObj._tasks.viewAll('<?php echo $tasks[0]->project_id; ?>')">Tasks</a>
	<a href="javascript:void(0);" onclick="projectObj._notes.viewAll('<?php echo $tasks[0]->project_id; ?>')">Notes</a>
	<a href="javascript:void(0);" onclick="projectObj._docs.viewAll('<?php echo $tasks[0]->project_id; ?>')">Documents</a>
	<?php
	if(count($tasks) > 0) {
	?>
	<a href="javascript:void(0);" onclick="projectObj._tasks.editTask(<?php echo $tasks[0]->task_id; ?>)">Update Task</a>
	<?php
	}
	?>
</div>

<div class="projectDetails">
	<div>Project Name: '<?php echo $projectName; ?>'</div>
	<div>Project Description : <?php echo $projectDescr; ?></div>
</div>
<h2>View Task Details</h3>
<div>
	<table>
	<?php
		if(count($tasks) > 0) {
			$i = 0;
	?>
		<tr>
		<td class='cell'>Task Title:</td><td class='cell'><?php echo $tasks[$i]->task_name; ?></td>
		</tr>
		<tr>
		<td class='cell'>Description:</td><td class='cell'><?php echo $tasks[$i]->task_desc; ?></td>
		</tr>
		<tr>
		<td class='cell'>Start Date:</td><td class='cell'><?php echo $tasks[$i]->task_start_date_for_view; ?></td>
		</tr>
		<tr>
		<td class='cell'>End Date:</td><td class='cell'><?php echo $tasks[$i]->task_end_date_for_view; ?></td>
		</tr>
		<tr>
		<td class='cell'>Task Status:</td><td class='cell'><?php echo $tasks[$i]->task_status; ?></td>
		</tr>
		<tr>
		<td class='cell'>Owner ID:</td><td class='cell'><?php echo $tasks[$i]->task_owner_id; ?></td>
		</tr>
		<tr>
		<td class='cell'>Dependency:</td><td class='cell'><?php echo $tasks[$i]->task_dependency; ?></td>
		</tr>
		<tr>
		<td class='cell'>Trend Type:</td><td class='cell'><?php echo $tasks[$i]->task_trade_type; ?></td>
		</tr>
		<tr>
		<td class='cell'>% Complete:</td><td class='cell'><?php echo $tasks[$i]->task_percent_complete; ?></td>
		</tr>
		<tr>
		<td class='cell'>Created By</td><td class='cell'><?php echo $created_by; ?></td>
		</tr>
		<tr>
		<td class='cell'>Created On:</td><td class='cell'><?php echo $tasks[$i]->created_on_for_view; ?></td>
		</tr>
		<tr>
		<td class='cell'>Updated By</td><td class='cell'><?php echo $updated_by; ?></td>
		</tr>
		<tr>
		<td class='cell'>Last Updated on</td><td class='cell'><?php echo $tasks[$i]->updated_on_for_view; ?></td>
		</tr>
	<?php
		}
	?>
	</table>
</div>
