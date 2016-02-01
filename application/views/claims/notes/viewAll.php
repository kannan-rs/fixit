 <?php

 $count_id = "notesCountForClaim";
 ?>
<input type="hidden" id="<?php echo $count_id; ?>" value="<?php echo $count[0]->count; ?>" />

<div class="notes_list">
	<!-- List all the Functions from database -->
<table cellspacing="0" id="note_list_table">
<?php
$count = count($claim_notes);
if($count) {
	for($i=0; $i < $count; $i++) {
	?>
		<tr id="notes_<?php echo $claim_notes[$i]->notes_id; ?>">
			<td class='cell' colspan="3">
				<?php echo $claim_notes[$i]->notes_content; ?> 
				<br/><i><?php echo $this->lang->line_arr('common_text->created_by'); ?>: <?php echo $claim_notes[$i]->created_by_name; ?> on 
					<?php echo $claim_notes[$i]->created_on_for_view; ?>
				</i>
			</td>
		</tr>
	<?php
	}
} else {
?>
<tr>
	<td>-- No notes added --  </td>
</tr>
<?php
}
?>
</table>
</div>
