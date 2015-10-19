<?php
$edit = false;
$prefix = "create";
$headerText = "Create Function";

if(isset($functions) && count($functions)) {
	$i = 0;
	$edit = true;
	$prefix = "update";
	$headerText = "Edit Function";
	$individualFunction = $functions[0];
}

$sno 		= isset($individualFunction) ? $individualFunction->sno : "";
$fn_id 		= isset($individualFunction) ? $individualFunction->fn_id : "";
$fn_name 	= isset($individualFunction) ? $individualFunction->fn_name : "";
$fn_descr 	= isset($individualFunction) ? $individualFunction->fn_descr : "";

?>
<h2><?php echo $headerText; ?></h2>
<form id="<?php echo $prefix; ?>_function_form" name="<?php echo $prefix; ?>_function_form" class="inputForm">
	<input type="hidden" id='function_sno' value="<?php echo $sno; ?>" />
	
	<table class='form'>
		<tbody>
			<tr>
				<td class="label">Function Id</td>
				<td><input type="text" name="functionId" id="functionId" value="<?php echo $fn_id; ?>" required></td>
			</tr>
			<tr>
				<td class="label">Function Name</td>
				<td><input type="text" name="functionName" id="functionName" value="<?php echo $fn_name; ?>" required></td>
			</tr>
			<tr>
				<td class="label">Function Description</td>
				<td><textarea rows="6" cols="30" name="functionDescr" id="functionDescr"><?php echo $fn_descr; ?></textarea></td>
			</tr>
			<tr>
				<td colspan="2">
					<p class="button-panel">
						<button type="button" id="<?php echo $prefix; ?>_function_submit" onclick="securityObj._functions.<?php echo $prefix; ?>Validate()"><?php echo $prefix; ?> Function</button>
					</p>
				</td>
			</tr>
			<tr>
				<td colspan="2"><?php echo $this->lang->line('mandatory_field_text'); ?></td>
			</tr>
		</tbody>
	</table>
</form>