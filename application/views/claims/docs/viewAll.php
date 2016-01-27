<input type="hidden" id="docsCount" value="<?php echo $count[0]->count; ?>" />
<?php
if(count($claim_docs) > 0) {
?>
<div class="docs_list">
	<!-- List all the Functions from database -->
	<input type="hidden" value="<?php echo count($claim_docs);?>" id="doc_count" name="doc_count">
	<table cellspacing="0">
	<?php
	for($i=0; $i < count($claim_docs); $i++) {
	?>
		<tr id="<?php echo 'docId_'.$claim_docs[$i]->doc_id; ?>">
			<td class='cell' colspan="3">
				<a href="/claims/docs/downloadAttachment/<?php echo $claim_docs[$i]->doc_id; ?>" target="_blank">
					<?php echo $claim_docs[$i]->document_name; ?>
				</a>
			</td>
			<td class='cell'>Created By: <?php echo $claim_docs[$i]->created_by_name; ?> on <?php echo $claim_docs[$i]->created_date_for_view; ?></td>
		</tr>
	<?php
	}
	?>
	</table>
</div>
<?php
}
?>