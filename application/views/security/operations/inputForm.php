<?php
$edit = false;
$prefix = "create";

if(isset($operations) && count($operations)) {
	$edit = true;
	$prefix = "update";
	$individualOperation = $operations[0];
}

$sno 		= isset($individualOperation) ? $individualOperation->sno : "";
$ope_id 	= isset($individualOperation) ? $individualOperation->ope_id : "";
$ope_name 	= isset($individualOperation) ? $individualOperation->ope_name : "";
$ope_desc 	= isset($individualOperation) ? $individualOperation->ope_desc : "";
?>

<h2><?php echo $this->lang->line_arr('operation->headers->'.$prefix); ?></h2>
<form id="<?php echo $prefix; ?>_operation_form" name="<?php echo $prefix; ?>_operation_form" class="inputForm">
	<input type="hidden" id='ope_sno' value="<?php echo $sno; ?>" />
	<table class='form'>
		<tbody>
			<!-- <tr>
				<td class="label"><?php echo $this->lang->line_arr('operation->input_form->operationId'); ?></td>
				<td><input type="text" name="operationId" id="operationId" value="<?php echo $ope_id; ?>" placeholder = "<?php echo $this->lang->line_arr('operation->input_form->operationId_ph'); ?>" required></td>
			<tr> -->
				<td class="label"><?php echo $this->lang->line_arr('operation->input_form->operationName'); ?></td>
				<td><input type="text" name="operationName" id="operationName" value="<?php echo $ope_name; ?>" placeholder = "<?php echo $this->lang->line_arr('operation->input_form->operationName_ph'); ?>" required></td>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('operation->input_form->operationDescr'); ?></td>
				<td><textarea rows="6" cols="30" name="operationDescr" id="operationDescr"placeholder = "<?php echo $this->lang->line_arr('operation->input_form->operationDescr_ph'); ?>" ><?php echo $ope_desc; ?></textarea></td>
			<tr>
				<td colspan="2">
					<p class="button-panel">
						<button type="button" id="<?php echo $prefix; ?>_operation_submit" onclick="_operations.<?php echo $prefix; ?>Validate()"><?php echo $this->lang->line_arr('operation->buttons_links->'.$prefix); ?></button>
					</p>
				</td>
			</tr>
			<tr>
				<td colspan="2"><?php echo $this->lang->line('mandatory_field_text'); ?></td>
			</tr>
		</tbody>
	</table>
</form>