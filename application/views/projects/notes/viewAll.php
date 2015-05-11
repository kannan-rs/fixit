<?php
if(count($project_notes) > 0) {
	if($startRecord == 0) {
?>
<div class="create-link">
	<?php echo $internalLink; ?>
</div>
<?php echo $projectNameDescr; ?>
<h2>Notes List</h3>
<?php
}
?>
<div class="notes_list">
	<!-- List all the Functions from database -->
	<?php
	for($i=0; $i < count($project_notes); $i++) {
	?>
	<table cellspacing="0">
		<tr>
			<td class='cell label'>Note Name:</td>
			<td class='cell' colspan="3"><?php echo $project_notes[$i]->notes_name; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Note Content:</td>
			<td class='cell' colspan="3"><?php echo $project_notes[$i]->notes_content; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Created By:</td>
			<td class='cell'><?php echo $project_notes[$i]->created_by_name; ?></td>
			<td class='cell label'>Created On:</td>
			<td class='cell'><?php echo $project_notes[$i]->created_date_for_view; ?></td>
		</tr>
	</table>
	<?php
	}
	?>
</div>
<?php
}
?>