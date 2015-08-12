<?php
$i = 0;
?>
<h2>Edit Operation</h2>
<form id="update_operation_form" name="update_operation_form" class="inputForm">
	<input type="hidden" id='ope_sno' value="<?php echo $operations[$i]->sno; ?>" />
	<div class='form'>
		<div class="label">Operation Id</div>
		<div><input type="text" name="operationId" id="operationId" value="<?php echo $operations[$i]->ope_id; ?>" required></div>
		<div class="label">Operation Name</div>
		<div><input type="text" name="operationName" id="operationName" value="<?php echo $operations[$i]->ope_name; ?>" required></div>
		<div class="label">Operation Description</div>
		<div><textarea rows="6" cols="30" name="operationDescr" id="operationDescr"><?php echo $operations[$i]->ope_desc; ?></textarea></div>
		<p class="button-panel">
			<button type="button" id="update_operation_submit" onclick="securityObj._operations.updateValidate()">Update Operation</button>
		</p>
	</div>
</form>