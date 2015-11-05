<?php
	//$update 	= false;
	$header 	= "Add";
	$descr 		= "";
	$amount 	= "";
	$date 		= "";
	$budgetId 	= "";

	if($updateBudget && count($updateBudget) == 1) {
		//$update = true;
		$updateBudget = $updateBudget[0];

		$descr 		= $updateBudget->descr;
		$amount 	= $updateBudget->amount;
		$date 		= $updateBudget->date;
		$budgetId	= $updateBudget->sno;

		$header	= "Update";
	}
?>
<!-- Create From Remaining Budget -->
<h3><?php echo $header; ?> Budget</h3>
<form id="create_budget_form" name="create_contractor_form" class="inputForm">
	<input type="hidden" id="budgetId" value="<?php echo $budgetId; ?>">
	<table class='form'>
		<tbody>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('budget->input_form->descr'); ?>:</td>
				<td>
					<textarea name="descr" id="descr" class="small-textarea" placeholder="<?php echo $this->lang->line_arr('budget->input_form->descr_ph'); ?>" required><?php echo $descr; ?></textarea>
				</td>
			</tr>
			
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('budget->input_form->amount'); ?></td>
				<td>
					<input type="text" name="amount" id="amount" value="<?php echo $amount; ?>" placeholder="<?php echo $this->lang->line_arr('budget->input_form->amount_ph'); ?>" required>
				</td>
			</tr>

			<tr>
				<td class="label"><?php echo $this->lang->line_arr('budget->input_form->date'); ?>:</td>
				<td>
					<input type="text" name="date" id="date" value="<?php echo $date; ?>" required placeholder="<?php echo $this->lang->line_arr('budget->input_form->date_ph'); ?>">
				</td>
			</tr>

			<tr>
				<td colspan="2">
					<p class="button-panel">
						<button type="button" id="create_pfbudget_submit" onclick="projectObj._remainingbudget.validate('<?php echo $openAs; ?>', '<?php echo $popupType;?>')"><?php echo $header; ?> Budget</button>
						<?php
						if($header == "Update") {
						?>
						<button type="button" id="create_pfbudget_submit" onclick="projectObj._remainingbudget.getListWithForm({'openAs': 'popup', 'popupType' : '2'})">Add New Budget</button>
						<?php
						}
						?>
						<button type="button" id="cancelButton" onclick="projectObj._projects.closeDialog({popupType: '<?php echo $popupType; ?>'})">Cancel</button>
					</p>
				</td>
			</tr>
			<tr>
				<td colspan="2"><?php echo $this->lang->line('mandatory_field_text'); ?></td>
			</tr>
		</tbody>
	</table>
</form>