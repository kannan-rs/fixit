<!-- Add Function Start -->
<?php
$createFn = "_claim_notes.createValidate()";
?>
<form id="create_claim_note_form" name="create_claim_note_form" class="inputForm">
	<input type="hidden" name="claim_id" id="claim_id" value="<?php echo $claim_id; ?>" />
	<table class='form'>
		<tbody>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('notes->input_form->noteContent'); ?>:</td>
				<td>
					<textarea type="text" name="noteContent" id="noteContent" rows="10" cols="70" placeholder="<?php echo $this->lang->line_arr('notes->input_form->noteContent_ph'); ?>" required></textarea>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<p class="button-panel">
						<button type="button" id="create_claim_note_submit" onclick="<?php echo $createFn; ?>"><?php echo $this->lang->line_arr('notes->buttons_links->add'); ?></button>
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