<!-- Add Function Start -->
<?php
$createFn = "_notes.createValidate('".$viewFor."')";

if($viewFor == "" || $viewFor != "projectViewOne") {
	echo "<h2>".$this->lang->line_arr('notes->headers->create')."</h3>";
}
?>
<form id="create_project_note_form" name="create_project_note_form" class="inputForm">
	<input type="hidden" name="projectId" id="projectId" value="<?php echo $projectId; ?>" />
	<input type="hidden" name="taskId" id="taskId" value="<?php echo $taskId; ?>" />
	<table class='form'>
		<tbody>
			<!--
			<tr>
				<td class="label">Note Name:</td>
				<td>
					<input type="text" name="noteName" id="noteName" value="" required>
				</td>
			</tr>
			-->
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('notes->input_form->noteContent'); ?>:</td>
				<td>
					<textarea type="text" name="noteContent" id="noteContent" rows="10" cols="70" placeholder="<?php echo $this->lang->line_arr('notes->input_form->noteContent_ph'); ?>" required></textarea>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<p class="button-panel">
						<button type="button" id="create_project_note_submit" onclick="<?php echo $createFn; ?>"><?php echo $this->lang->line_arr('notes->buttons_links->add'); ?></button>
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