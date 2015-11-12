<input type="hidden" id="docsCount" value="<?php echo $count[0]->count; ?>" />
<?php
if(count($project_docs) > 0) {
?>
<div class="docs_list">
	<!-- List all the Functions from database -->
	<table cellspacing="0">
	<?php
	for($i=0; $i < count($project_docs); $i++) {
		$deleteFn = "projectObj._projects.documentDelete('".$project_docs[$i]->doc_id."')";
	?>
		<tr id="<?php echo 'docId_'.$project_docs[$i]->doc_id; ?>">
			<td class='cell' colspan="3">
				<!--<a href="javascript:void(0);" onclick="projectObj._docs.getAttachment('<?php echo $project_docs[$i]->doc_id; ?>')">-->
				<a href="/projects/docs/downloadAttachment/<?php echo $project_docs[$i]->doc_id; ?>" target="_blank">
					<?php echo $project_docs[$i]->document_name; ?>
				</a>
			</td>
			<td class='cell'>Created By: <?php echo $project_docs[$i]->created_by_name; ?> on <?php echo $project_docs[$i]->created_date_for_view; ?></td>
			<?php
			if($accountType == "admin") {
			?>
			<td class="table-action">
				<span><a  class="step fi-deleteRow size-21 red delete" href="javascript:void(0);" onclick="<?php echo $deleteFn; ?>" title="<?php echo $this->lang->line_arr('docs->buttons_links->delete_title'); ?>"></a></span>
				<!--<a onclick="projectObj._docs.delete('<?php echo $project_docs[$i]->doc_id; ?>')">Delete</a></td>-->
			<?php
			}
			?>
		</tr>
	<?php
	}
	?>
	</table>
</div>
<?php
}
?>