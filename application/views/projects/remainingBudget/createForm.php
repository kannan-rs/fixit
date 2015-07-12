<!-- Create From Remaining Budget -->
<form id="create_pfbudget_form" name="create_contractor_form" class="inputForm">
	<div class='form'>
		<div class="label">Description:</div>
		<div>
			<textarea name="descr" id="descr" class="small-textarea" required></textarea>
		</div>
		
		<div class="label">amount</div>
		<div>
			<input type="number" name="amount" id="amount" value="" placeholder="Paid Amount">
		</div>

		<div class="label">Date:</div>
		<div>
			<input type="text" name="date" id="date" value="" required placeholder="Payment Date">
		</div>

		<p class="button-panel">
			<button type="button" id="create_pfbudget_submit" onclick="projectObj._remainingBudget.validate('<?php echo $openAs; ?>', '<?php echo $popupType;?>')">Add/Update Budget</button>
			<button type="button" id="cancelButton" onclick="projectObj._projects.closeDialog({popupType: '<?php echo $popupType; ?>'})">Cancel</button>
		</p>
	</div>
</form>