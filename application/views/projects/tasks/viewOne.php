<?php
$taskId = $tasks[0]->task_id;
$projectId = $tasks[0]->project_id;

if(in_array(OPERATION_VIEW, $issuesPermission['operation'])) {
	
}
?>
	<div class="header-options">
	<span class="options-icon">
	<!-- <table>
		<tr>
			<td class="cell table-action right"> -->
		<?php 
		if(count($tasks) > 0) { ?>
			<?php 
			if(in_array(OPERATION_UPDATE, $tasksPermission['operation'])) { 
				$editFn 	= "_projects.editTask('".$taskId."')";
			?>
			<span>
				<a class="step fi-page-edit size-21" 
					href="javascript:void(0);" 
					onclick="<?php echo $editFn; ?>" 
					title="<?php echo $this->lang->line_arr('tasks->buttons_links->edit_title'); ?>">
				</a>
			</span>
			<?php 
			}

			if(in_array(OPERATION_DELETE, $tasksPermission['operation'])) { 
				$deleteFn 	= "_projects.taskDelete(".$taskId.",".$projectId.")";
			?>
			<span>
				<a  class="step fi-deleteRow size-21 red delete" 
					href="javascript:void(0);" 
					onclick="<?php echo $deleteFn; ?>" 
					title="<?php echo $this->lang->line_arr('tasks->buttons_links->delete_title'); ?>">
				</a>
			</span>
			<?php 
			}

			if(in_array(OPERATION_VIEW, $issuesPermission['operation'])) { 
				$issueCount 	= $tasks[0]->issueCount;
				$issueFnOptions = "{'projectId' :".$projectId.", 'taskId' : ".$taskId.", 'openAs' : 'popup', 'popupType' : '' }";
				$issueFn = "_issues.viewAll(".$issueFnOptions.")";
			?>
			<span>
				<a class="step fi-alert size-21 <?php echo $issueCount ? 'red' : ''; ?>" 
					href="javascript:void(0);" 
					onclick="<?php echo $issueFn; ?>" 
					title="<?php echo $this->lang->line_arr('tasks->buttons_links->project_issue_title'); ?>">
					<span class="size-9"><?php echo $issueCount ? $issueCount : ""; ?></span>
				</a>
			</span>
			<?php 
			}
		} ?>
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
		<td class='cell label'><?php echo $this->lang->line_arr('tasks->details_view->task_title'); ?>:</td>
		<td class='cell'><?php echo $tasks[$i]->task_name; ?></td>
	</tr>
	<tr>
		<td class='cell label'><?php echo $this->lang->line_arr('tasks->details_view->description'); ?>:</td>
		<td class='cell'><?php echo $tasks[$i]->task_desc; ?></td>
	</tr>
	<tr>
		<td class='cell label'><?php echo $this->lang->line_arr('tasks->details_view->start_date'); ?>:</td>
		<td class='cell'><?php echo $tasks[$i]->task_start_date_for_view; ?></td>
	</tr>
	<tr>
		<td class='cell label'><?php echo $this->lang->line_arr('tasks->details_view->end_date'); ?>:</td>
		<td class='cell'><?php echo $tasks[$i]->task_end_date_for_view; ?></td>
	</tr>
	<tr>
		<td class='cell label'><?php echo $this->lang->line_arr('tasks->details_view->task_status'); ?>:</td>
		<td class='cell'><?php echo $tasks[$i]->task_status; ?></td>
	</tr>
	<tr>
		<td class='cell label'><?php echo $this->lang->line_arr('tasks->details_view->owner_name'); ?>:</td>
		<td class='cell'>
			<?php 
				echo (isset($task_owner_user_details) && !empty( $task_owner_user_details) ) ? $task_owner_user_details." from company '".$contractors[0]->company."'" : "Customer";
			?>
		</td>
	</tr>
	<tr>
		<td class='cell label'><?php echo $this->lang->line_arr('tasks->details_view->dependency'); ?>:</td>
		<td class='cell'><?php echo $tasks[$i]->task_dependency; ?></td>
	</tr>
	<tr>
		<td class='cell label'><?php echo $this->lang->line_arr('tasks->details_view->trend_type'); ?>:</td>
		<td class='cell'><?php echo $tasks[$i]->task_trade_type; ?></td>
	</tr>
	<tr>
		<td class='cell label'><?php echo $this->lang->line_arr('tasks->details_view->complete'); ?>:</td>
		<td class='cell'><?php echo $tasks[$i]->task_percent_complete; ?></td>
	</tr>
	<tr>
		<td class='cell label'><?php echo $this->lang->line_arr('common_text->created_by'); ?></td>
		<td class='cell'><?php echo $created_by; ?></td>
	</tr>
	<tr>
		<td class='cell label'><?php echo $this->lang->line_arr('common_text->created_on'); ?>:</td>
		<td class='cell'><?php echo $tasks[$i]->created_on_for_view; ?></td>
	</tr>
	<tr>
		<td class='cell label'><?php echo $this->lang->line_arr('common_text->updated_by'); ?></td>
		<td class='cell'><?php echo $updated_by; ?></td>
	</tr>
	<tr>
		<td class='cell label'><?php echo $this->lang->line_arr('common_text->updated_on'); ?></td>
		<td class='cell'><?php echo $tasks[$i]->updated_on_for_view; ?></td>
	</tr>
<?php
	}
?>
</table>
