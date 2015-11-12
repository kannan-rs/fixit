<?php
if($viewFor == "" || $viewFor != "projectViewOne") {
?>
	<div class="create-link">
		<?php echo $internalLink; ?>
	</div>
	<?php echo $projectNameDescr; ?>
	<h2><?php echo $this->lang->line_arr('tasks->headers->view_all'); ?></h2>
<?php
} else {
?>
<!-- Show Links above the table -->
<div class="tasks internal-tab-as-links" onclick="projectObj._tasks.showTaskList(event)">
	<a href="javascript:void(0);" data-option="open" title="<?php echo $this->lang->line_arr('tasks->buttons_links->open_title'); ?>"><?php echo $this->lang->line_arr('tasks->buttons_links->open'); ?></a>
	<a href="javascript:void(0);" data-option="completed" title="<?php echo $this->lang->line_arr('tasks->buttons_links->completed_title'); ?>"><?php echo $this->lang->line_arr('tasks->buttons_links->completed'); ?></a>
	<a href="javascript:void(0);" data-option="all" title="<?php echo $this->lang->line_arr('tasks->buttons_links->all_title'); ?>"><?php echo $this->lang->line_arr('tasks->buttons_links->all'); ?></a>
</div>
<?php
}

?>
<!-- List all the Functions from database -->
<input type="hidden" id="tasksCount" value="<?php echo $count[0]->count; ?>" />
<table  cellspacing="0" class="viewAll task-table-list">

<?php
	if(count($tasks) > 0) {
?>
		<tr class='heading'>
			<!--<td class='cell'>Sno</td>-->
			<td class='cell name'><?php echo $this->lang->line_arr('tasks->summary_table->task_name'); ?></td>
			<td class='cell descr'><?php echo $this->lang->line_arr('tasks->summary_table->description'); ?></td>
			<td class='cell'><?php echo $this->lang->line_arr('tasks->summary_table->owner'); ?></td>
			<td class='cell'><?php echo $this->lang->line_arr('tasks->summary_table->complete'); ?></td>
			<td class='cell'><?php echo $this->lang->line_arr('tasks->summary_table->start_date'); ?></td>
			<td class='cell'><?php echo $this->lang->line_arr('tasks->summary_table->end_date'); ?></td>
			<td class='cell'></td>
		</tr>
<?php
	}

	for($i = 0; $i < count($tasks); $i++) { 
		
		$taskId 		= $tasks[$i]->task_id;
		$task_name 		= $tasks[$i]->task_name ? $tasks[$i]->task_name : "--";
		$descr 			= $tasks[$i]->task_desc != "" ? $tasks[$i]->task_desc : '--';
		$percent 		= $tasks[$i]->task_percent_complete;
		$stard_date 	= $tasks[$i]->task_start_date_for_view;
		$end_date 		= $tasks[$i]->task_end_date_for_view;
		$projectId 		= $tasks[$i]->project_id;
		$taskStatus 	= $tasks[$i]->task_status;

		$rowCSS 		= $taskStatus == "completed" ? "completed" : "open";

		$issueCount 	= $tasks[$i]->issueCount;
		$issueFnOptions = "{'projectId' :".$tasks[$i]->project_id.", 'taskId' : ".$tasks[$i]->task_id.", 'openAs' : 'popup', 'popupType' : '' }";
		$issueFn = "projectObj._issues.viewAll(".$issueFnOptions.")";
		
		$deleteFn 	= ($viewFor == "" || $viewFor != "projectViewOne") ? "projectObj._tasks.deleteRecord" : "projectObj._projects.taskDelete";
		$deleteFn  .= "(".$taskId.",".$projectId.")";

		$viewOneFn 	= ($viewFor == "" || $viewFor != "projectViewOne") ? "projectObj._tasks.viewOne" : "projectObj._projects.taskViewOne";
		$viewOneFn .= "('".$taskId."')";

		$editFn 	= ($viewFor == "" || $viewFor != "projectViewOne") ? "projectObj._tasks.editTask" : "projectObj._projects.editTask";
		$editFn    .= "('".$taskId."')";

		$notesFn 		= "projectObj._projects.getTaskNotesList('".$taskId."',0, 5);";
		//$notesCreateFn = "projectObj._projects.addTaskNote('".$taskId."');";
		
		$ownerName = $tasks[$i]->task_owner_id && $tasks[$i]->task_owner_id != "" && array_key_exists($tasks[$i]->task_owner_id, $contractors) ? $contractors[$tasks[$i]->task_owner_id]->name : $customerName;
?>
		<tr class='row <?php echo $rowCSS; ?>' id="task_<?php echo $taskId; ?>">
			<!--<td class='cell number'>".($i+1)."</td>-->
			<td class='cell name'>
				<a href="javascript:void(0);" onclick="<?php echo $viewOneFn; ?>">
					<?php echo $task_name; ?>
				</a>
			</td>
			<td class='cell descr'><?php echo $descr; ?></td>
			<td class='cell'><?php echo $ownerName; ?></td>
			<td class='cell percentage'><?php echo $percent; ?></td>
			<td class='cell date'><?php echo  $stard_date; ?></td>
			<td class='cell date'><?php echo  $end_date; ?></td>
			<td class='cell table-action'>
				<span><a class="step fi-clipboard-notes size-21" href="javascript:void(0);" onclick="<?php echo $notesFn?>" title="<?php echo $this->lang->line_arr('tasks->buttons_links->notes_task_title'); ?>"></a></span>
				<!-- <span><a  class="step fi-page-edit size-21" href="javascript:void(0);" onclick="<?php echo $editFn; ?>" title="Edit Task"></a></span>
				<span><a  class="step fi-deleteRow size-21 red delete" href="javascript:void(0);" onclick="<?php echo $deleteFn; ?>" title="Delete Task"></a></span> -->
				<span>
					<a class="step fi-alert size-21 <?php echo $issueCount ? 'red' : ''; ?>" href="javascript:void(0);" onclick="<?php echo $issueFn; ?>" title="<?php echo $this->lang->line_arr('tasks->buttons_links->project_issue_title'); ?>">
						<span class="size-9"><?php echo $issueCount ? $issueCount : ""; ?></span>
					</a>
				</span>
			</td>
		</tr>
<?php
	}
?>
</table>