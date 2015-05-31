<!-- Add Function Start -->
<?php
$createFn = "projectObj._notes.createValidate('".$viewFor."')";

if($viewFor == "" || $viewFor != "projectViewOne") {
	echo "<h2>Create Note</h3>";
}
?>
<form id="create_project_note_form" name="create_project_note_form" class="inputForm">
	<div class='form'>
		<input type="hidden" name="projectId" id="projectId" value="<?php echo $projectId; ?>" />
		<input type="hidden" name="taskId" id="taskId" value="<?php echo $taskId; ?>" />
		<!--
		<div class="label">Note Name:</div>
		<div>
			<input type="text" name="noteName" id="noteName" value="" required>
		</div>
		-->
		<div class="label">Notes Content:</div>
		<div>
			<textarea type="text" name="noteContent" id="noteContent" rows="10" cols="70" required></textarea>
		</div>
		<p class="button-panel">
			<button type="button" id="create_project_note_submit" onclick="<?php echo $createFn; ?>">Add Notes</button>
		</p>
	</div>
</form>