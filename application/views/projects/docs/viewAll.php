<?php
if(count($project_docs) > 0) {
	if($startRecord == 0) {
?>
	<h2>Docs List</h3>
<?php
}
?>
<div class="docs_list">
	<!-- List all the Functions from database -->
	<?php
	for($i=0; $i < count($project_docs); $i++) {
	?>
	<table>
		<tr>
			<td class='cell' colspan="3">
				<!--<a href="javascript:void(0);" onclick="projectObj._docs.getAttachment('<?php echo $project_docs[$i]->doc_id; ?>')">-->
				<a href="/projects/docs/downloadAttachment/<?php echo $project_docs[$i]->doc_id; ?>" target="_blank">
					<?php echo $project_docs[$i]->document_name; ?>
				</a>
			</td>
			<td class='cell'>Created By: <?php echo $project_docs[$i]->created_by_name; ?> on <?php echo $project_docs[$i]->created_date_for_view; ?></td>
		</tr>
	</table>
	<?php
	}
	?>
</div>
<?php
}
?>