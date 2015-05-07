<!-- Add Operation Start -->
<h2>Create Operation</h3>
	<form id="create_operation_form" name="create_operation_form">
	<div class='form'>
		<p>
			<div class="label">Operation ID:</div>
			<div>
				<input type="text" name="operationId" id="operationId" value="" required>
			</div>
		</p>
		<p>
			<div class="label">Opertion Name:</div>
			<div><input type="text" name="operationName" id="operationName" required></div>
		</p>
		<p>
			<div class="label">Operation Description:</div>
			<div><textarea rows="6" cols="30" name="operationDescr" id="operationDescr"></textarea></div>
		</p>
		<p class="button-panel">
			<button type="button" id="create_operation_submit" onclick="securityObj._operations.createValidate()">Create Operation</button>
		</p>
	</div>
</form>
<!-- Add Operation Ends -->