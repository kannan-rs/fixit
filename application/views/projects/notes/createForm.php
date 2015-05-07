<!-- Add Function Start -->
<h2>Create Notes</h3>
<form id="create_project_note_form" name="create_project_note_form" class="inputForm">
	<div class='form'>
		<input type="hidden" name="projectId" id="projectId" value="<?php echo $projectId; ?>" />
		<div class="label">Note Name:</div>
		<div>
			<input type="text" name="noteName" id="noteName" value="" required>
		</div>
		<div class="label">Notes Content:</div>
		<div>
			<textarea type="text" name="noteContent" id="noteContent" rows="10" cols="70" required></textarea>
		</div>
		<p class="button-panel">
			<button type="button" id="create_project_note_submit" onclick="projectObj._notes.createValidate()">Create Notes</button>
		</p>
	</div>
</form>
