<?php
$taskId = $tasks[0]->task_id;
$projectId = $tasks[0]->project_id;

$deleteFn 	= "projectObj._projects.taskDelete(".$taskId.",".$projectId.")";
$editFn 	= "projectObj._projects.editTask('".$taskId."')";

$issueCount 	= $tasks[0]->issueCount;
$issueFnOptions = "{'projectId' :".$tasks[0]->project_id.", 'taskId' : ".$tasks[0]->task_id.", 'openAs' : 'popup', 'popupType' : '' }";
$issueFn = "projectObj._issues.viewAll(".$issueFnOptions.")";
/*if($viewFor == "" || $viewFor != "projectViewOne") {
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
	<h2>Task Details</h2>
<?php
} else { */
?>
	<div class="header-options">
	<span class="options-icon">
	<!-- <table>
		<tr>
			<td class="cell table-action right"> -->
		<?php if(count($tasks) > 0) { ?>
		<span><a class="step fi-page-edit size-21" href="javascript:void(0);" onclick="<?php echo $editFn; ?>" title="Edit Task"></a></span>
		<span><a  class="step fi-deleteRow size-21 red delete" href="javascript:void(0);" onclick="<?php echo $deleteFn; ?>" title="Delete Task"></a></span>
		<span>
			<a class="step fi-alert size-21 <?php echo $issueCount ? 'red' : ''; ?>" href="javascript:void(0);" onclick="<?php echo $issueFn; ?>" title="Project Issues">
				<span class="size-9"><?php echo $issueCount ? $issueCount : ""; ?></span>
			</a>
		</span>
		<?php } ?>
			<!-- </td>
		</tr>
	</table> -->
	</span>
	</div>
<?php
//}
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
	<td class='cell label'>Owner Name:</td><td class='cell'>
		<?php 
			echo count($contractors) ? $contractors[0]->name." from company '".$contractors[0]->company."'" : "Customer";
		?>
	</td>
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
