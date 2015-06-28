<!-- Add Function Start -->
<h2>Create Function</h2>
	<form id="create_function_form" name="create_function_form">
	<div class='form'>
		<div class="label">Function ID:</div>
		<div><input type="text" name="functionId" id="functionId" value="" required></div>		
		<div class="label">Function Name:</div>
		<div><input type="text" name="functionName" id="functionName" required></div>
		<div class="label">Function Description:</div>
		<div><textarea rows="6" cols="30" name="functionDescr" id="functionDescr"></textarea></div>
		<p class="button-panel">
			<button type="button" id="create_function_submit" onclick="securityObj._functions.createValidate()">Create Function</button>
		</p>
	</div>
</form>
<!-- Add Function Ends -->