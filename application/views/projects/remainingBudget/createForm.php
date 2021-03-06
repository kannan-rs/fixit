<?php
	//$update 	= false;
	$prefix 	= "Add";
	$descr 		= "";
	$amount 	= "";
	$date 		= "";
	$budgetId 	= "";

	if(in_array(OPERATION_UPDATE, $budgetPermission['operation']) && isset($updateBudget) && count($updateBudget) == 1) {
		//$update = true;
		$updateBudget = $updateBudget[0];

		$descr 		= $updateBudget->descr;
		$amount 	= $updateBudget->amount;
		$date 		= $updateBudget->date;
		$budgetId	= $updateBudget->sno;

		$prefix	= "Update";
	}
?>
<!-- Create From Remaining Budget -->
<h3><?php echo $this->lang->line_arr('budget->headers->'.$prefix); ?></h3>
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
							<button type="button" id="create_pfbudget_submit" 
								onclick="_remainingbudget.validate('<?php echo $openAs; ?>', '<?php echo $popupType;?>')">
								<?php echo $this->lang->line_arr('budget->buttons_links->'.strtolower($prefix)); ?>
							</button>
						<?php
						if(in_array(OPERATION_UPDATE, $budgetPermission['operation']) && $prefix == "Update") {
						?>
							<button type="button" id="create_pfbudget_submit" 
								onclick="_remainingbudget.getListWithForm(event, {'openAs': 'popup', 'popupType' : '2'})">
									<?php echo $this->lang->line_arr('budget->buttons_links->add_new'); ?>
							</button>
						<?php
						}
						?>
							<button type="button" id="cancelButton" 
								onclick="_projects.closeDialog({popupType: '<?php echo $popupType; ?>'})">
								<?php echo $this->lang->line_arr('buttons->cancel'); ?>
							</button>
					</p>
				</td>
			</tr>
			<tr>
				<td colspan="2"><?php echo $this->lang->line('mandatory_field_text'); ?></td>
			</tr>
		</tbody>
	</table>
</form>