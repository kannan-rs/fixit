<?php
	//Edit Individual
	$i = 0;
?>
<h2>Edit Function</h3>
<form id="update_function_form" name="update_function_form">
	<input type="hidden" id='function_sno' value="<?php echo $functions[$i]->sno; ?>" />
	<div class='form'>
		<p>
			<div class="label">Function Id</div>
			<div>
				<input type="text" name="functionId" id="functionId" value="<?php echo $functions[$i]->fn_id; ?>" required>
			</div>
		</p>
		<p>
			<div class="label">Function Name</div>
			<input type="text" name="functionName" id="functionName" value="<?php echo $functions[$i]->fn_name; ?>" required>
		</p>
		<p>
			<div class="label">Function Description</div>
			<div><textarea rows="6" cols="30" name="functionDescr" id="functionDescr"><?php echo $functions[$i]->fn_descr; ?></textarea></div>
		</p>
		<p class="button-panel">
			<button type="button" id="update_function_submit" onclick="securityObj._functions.updateValidate()">Update Function</button>
		</p>
	</div>
</form>