<?php
//if(count($project_notes) > 0) {
//	if($startRecord == 0) {
if($viewFor == "" || $viewFor != "projectViewOne") {
?>
	<div class="create-link">
		<?php echo $internalLink; ?>
	</div>
	<?php echo $projectNameDescr; ?>
 	<h2>Notes</h2>
 <?php
 }

 $countId = "notesCountForProject";
 if(!empty($projectId) && !empty($taskId)) {
	$countId = "notesCountForTask";
 }
 ?>
<input type="hidden" id="<?php echo $countId; ?>" value="<?php echo $count[0]->count; ?>" />

<div class="notes_list">
	<!-- List all the Functions from database -->
<table cellspacing="0" id="note_list_table">
<?php
//}
?>
	<?php
	for($i=0; $i < count($project_notes); $i++) {

		$deleteFn = ($viewFor == "" || $viewFor != "projectViewOne") ? "projectObj._notes.deleteRecord" : "projectObj._projects.notesDelete";
		$deleteFn .= "(".$project_notes[$i]->notes_id.", ".$project_notes[$i]->task_id.")";
	?>
		<!--
		<tr>
			<td class='cell label'>Note Name:</td>
			<td class='cell' colspan="3"><?php echo $project_notes[$i]->notes_name; ?></td>
		</tr>
		-->
		<tr id="notes_<?php echo $project_notes[$i]->notes_id; ?>">
			<td class='cell' colspan="3">
				<?php echo $project_notes[$i]->notes_content; ?> 
				<i>Created By: <?php echo $project_notes[$i]->created_by_name; ?> on 
					<?php echo $project_notes[$i]->created_date_for_view; ?>
				</i>
			</td>
			<td class="table-action">
				<span><a  class="step fi-deleteRow size-21 red delete" href="javascript:void(0);" onclick="<?php echo $deleteFn; ?>"></a></span>
			</td>
		</tr>
		<!--<tr>
			<td class='cell label'>Note Content:</td>
			<td class='cell' colspan="3"><?php echo $project_notes[$i]->notes_content; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Created By:</td>
			<td class='cell'><?php echo $project_notes[$i]->created_by_name; ?></td>
			<td class='cell label'>Created On:</td>
			<td class='cell'><?php echo $project_notes[$i]->created_date_for_view; ?></td>
		</tr>-->
	<?php
	}
?>
<?php
//if($startRecord == 0) {
?>
</table>
</div>
<?php
//	}
//}
?>