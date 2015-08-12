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
<form id="create_pfbudget_form" name="create_contractor_form" class="inputForm">
	<div class='form'>
		<input type="hidden" id="budgetId" value="<?php echo $budgetId; ?>">
		<div class="label">Description:</div>
		<div>
			<textarea name="descr" id="descr" class="small-textarea" required><?php echo $descr; ?></textarea>
		</div>
		
		<div class="label">amount</div>
		<div>
			<input type="number" name="amount" id="amount" value="<?php echo $amount; ?>" placeholder="Paid Amount" required>
		</div>

		<div class="label">Date:</div>
		<div>
			<input type="text" name="date" id="date" value="<?php echo $date; ?>" required placeholder="Payment Date">
		</div>

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
	</div>
</form>