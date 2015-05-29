<?php
if($viewFor == "" || $viewFor != "projectViewOne") {
?>
	<div class="create-link">
		<?php echo $internalLink; ?>
	</div>
	<?php echo $projectNameDescr; ?>
	<h2>Tasks List</h2>
<?php
}
?>
<!--<h2>Tasks List</h2>-->
<!-- List all the Functions from database -->
<table  cellspacing="0" class="viewAll">

<?php
	if(count($tasks) > 0) {
?>
		<tr class='heading'>
			<!--<td class='cell'>Sno</td>-->
			<td class='cell'>Task Name</td>
			<td class='cell'>Description</td>
			<td class='cell'>Owner</td>
			<td class='cell'>% Complete</td>
			<td class='cell'>Start Date</td>
			<td class='cell'>End Date</td>
			<td class='cell'></td>
		</tr>
<?php
	}

	for($i = 0; $i < count($tasks); $i++) { 
		
		$taskId 		= $tasks[$i]->task_id;
		$task_name 		= $tasks[$i]->task_name ? $tasks[$i]->task_name : "--";
		$descr 			= ($tasks[$i]->task_desc != "") ? $tasks[$i]->task_desc : '--';
		$percent 		= $tasks[$i]->task_percent_complete;
		$stard_date 	= $tasks[$i]->task_start_date_for_view;
		$end_date 		= $tasks[$i]->task_end_date_for_view;
		$projectId 		= $tasks[$i]->project_id;
		
		$deleteFn 	= ($viewFor == "" || $viewFor != "projectViewOne") ? "projectObj._tasks.delete" : "projectObj._projects.taskDelete";
		$deleteFn  .= "(".$taskId.",".$projectId.")";

		$viewOneFn 	= ($viewFor == "" || $viewFor != "projectViewOne") ? "projectObj._tasks.viewOne" : "projectObj._projects.taskViewOne";
		$viewOneFn .= "('".$taskId."')";

		$editFn 	= ($viewFor == "" || $viewFor != "projectViewOne") ? "projectObj._tasks.editTask" : "projectObj._projects.editTask";
		$editFn    .= "('".$taskId."')";

		$notesFn 	= "projectObj._projects.NotesForTaskviewAll('".$projectId."','".$taskId."',0, 5)";
?>
		<tr class='row' id="task_<?php echo $taskId; ?>">
			<!--<td class='cell number'>".($i+1)."</td>-->
			<td class='cell'>
				<a href="javascript:void(0);" onclick="<?php echo $viewOneFn; ?>">
					<?php echo $task_name; ?>
				</a>
			</td>
			<td class='cell'><?php echo $descr; ?></td>
			<td class='cell'>-- To be decided --</td>
			<td class='cell percentage'><?php echo $percent; ?></td>
			<td class='cell date'><?php echo  $stard_date; ?></td>
			<td class='cell date'><?php echo  $end_date; ?></td>
			<td class='cell table-action'>
				<!--<span>
					<a href="javascript:void(0);" onclick="projectObj._notes.viewAll('<?php echo $projectId; ?>','<?php echo $taskId; ?>',0, 5)">Notes</a>
				</span>-->
				<span><a class="step fi-clipboard-notes size-21" href="javascript:void(0);" onclick="<?php echo $notesFn?>" title="Notes For Task"></a></span>
				<span><a  class="step fi-page-edit size-21" href="javascript:void(0);" onclick="<?php echo $editFn; ?>" title="Edit Task"></a></span>
				<span><a  class="step fi-deleteRow size-21 red delete" href="javascript:void(0);" onclick="<?php echo $deleteFn; ?>" title="Delete Task"></a></span>

				<!--
				<span>
					<a href="javascript:void(0);" onclick="projectObj._tasks.editTask('<?php echo $taskId; ?>')">Edit</a>
				</span>
				<span>
					<a href="javascript:void(0);" onclick="<?php echo $deleteFn; ?>"><?php echo $deleteText; ?></a>
				</span>
				-->
			</td>
		</tr>
<?php
	}
?>
</table>