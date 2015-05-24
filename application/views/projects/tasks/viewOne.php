<?php
if($viewFor == "" || $viewFor != "projectViewOne") {
?>
	<div class="create-link">
		<?php echo $internalLink;
		if(count($tasks) > 0) {
		?>
		<span><a href="javascript:void(0);" onclick="projectObj._tasks.editTask(<?php echo $tasks[0]->task_id; ?>)">Update Task</a></span>
		<?php
		}
		?>
	</div>
	<?php echo $projectNameDescr; ?>
	<h2>Task Details</h3>
<?php
} else {
?>
	<div class="create-link">
		<?php if(count($tasks) > 0) { ?>
		<span><a href="javascript:void(0);" onclick="projectObj._projects.editTask(<?php echo $tasks[0]->task_id; ?>)">Update Task</a></span>
		<?php } ?>
	</div>
<?php
}
?>
	<table cellspacing="0">
	<?php
		if(count($tasks) > 0) {
			$i = 0;
	?>
	<tr>
	<td class='cell label'>Task Title:</td><td class='cell'><?php echo $tasks[$i]->task_name; ?></td>
	</tr>
	<tr>
	<td class='cell label'>Description:</td><td class='cell'><?php echo $tasks[$i]->task_desc; ?></td>
	</tr>
	<tr>
	<td class='cell label'>Start Date:</td><td class='cell'><?php echo $tasks[$i]->task_start_date_for_view; ?></td>
	</tr>
	<tr>
	<td class='cell label'>End Date:</td><td class='cell'><?php echo $tasks[$i]->task_end_date_for_view; ?></td>
	</tr>
	<tr>
	<td class='cell label'>Task Status:</td><td class='cell'><?php echo $tasks[$i]->task_status; ?></td>
	</tr>
	<tr>
	<td class='cell label'>Owner ID:</td><td class='cell'><?php echo $tasks[$i]->task_owner_id; ?></td>
	</tr>
	<tr>
	<td class='cell label'>Dependency:</td><td class='cell'><?php echo $tasks[$i]->task_dependency; ?></td>
	</tr>
	<tr>
	<td class='cell label'>Trend Type:</td><td class='cell'><?php echo $tasks[$i]->task_trade_type; ?></td>
	</tr>
	<tr>
	<td class='cell label'>% Complete:</td><td class='cell'><?php echo $tasks[$i]->task_percent_complete; ?></td>
	</tr>
	<tr>
	<td class='cell label'>Created By</td><td class='cell'><?php echo $created_by; ?></td>
	</tr>
	<tr>
	<td class='cell label'>Created On:</td><td class='cell'><?php echo $tasks[$i]->created_on_for_view; ?></td>
	</tr>
	<tr>
	<td class='cell label'>Updated By</td><td class='cell'><?php echo $updated_by; ?></td>
	</tr>
	<tr>
	<td class='cell label'>Last Updated on</td><td class='cell'><?php echo $tasks[$i]->updated_on_for_view; ?></td>
	</tr>
<?php
	}
?>
</table>
