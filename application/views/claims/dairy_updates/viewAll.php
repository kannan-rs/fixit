 <?php

 $count_id = "dairyUpdatesCountForClaim";
 ?>
<input type="hidden" id="<?php echo $count_id; ?>" value="<?php echo $count[0]->count; ?>" />

<div class="dairy_updates_list">
	<!-- List all the Functions from database -->
<table cellspacing="0" id="dairy_update_list_table">
<?php
$count = count($claim_dairy_updates);
if($count) {
	for($i=0; $i < $count; $i++) {
	?>
		<tr id="dairy_updates_<?php echo $claim_dairy_updates[$i]->dairy_updates_id; ?>">
			<td class='cell' colspan="3">
				<?php echo $claim_dairy_updates[$i]->dairy_updates_content; ?> 
				<br/><i><?php echo $this->lang->line_arr('common_text->created_by'); ?>: <?php echo $claim_dairy_updates[$i]->created_by_name; ?> on 
					<?php echo $claim_dairy_updates[$i]->created_on_for_view; ?>
				</i>
			</td>
		</tr>
	<?php
	}
} else {
?>
<tr>
	<td>-- No dairy updates added --  </td>
</tr>
<?php
}
?>
</table>
</div>
