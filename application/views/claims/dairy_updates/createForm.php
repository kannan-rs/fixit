<!-- Add Function Start -->
<?php
$createFn = "_claim_dairy_updates.createValidate()";
?>
<form id="create_claim_dairy_update_form" name="create_claim_dairy_update_form" class="inputForm">
	<input type="hidden" name="claim_id" id="claim_id" value="<?php echo $claim_id; ?>" />
	<table class='form'>
		<tbody>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('claim_daily_updates->input_form->dailyUpdateContent'); ?>:</td>
				<td>
					<textarea type="text" name="dailyUpdateContent" id="dailyUpdateContent" rows="10" cols="70" placeholder="<?php echo $this->lang->line_arr('claim_daily_updates->input_form->dailyUpdateContent_ph'); ?>" required></textarea>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<p class="button-panel">
						<button type="button" id="create_claim_daily_update_submit" onclick="<?php echo $createFn; ?>"><?php echo $this->lang->line_arr('claim_daily_updates->buttons_links->add'); ?></button>
						<button type="button" id="cancelButton" onclick="_projects.closeDialog()"><?php echo $this->lang->line_arr('buttons->cancel'); ?></button>
					</p>
				</td>
			</tr>
			<tr>
				<td colspan="2"><?php echo $this->lang->line('mandatory_field_text'); ?></td>
			</tr>
		</tbody>
	</table>
</form>