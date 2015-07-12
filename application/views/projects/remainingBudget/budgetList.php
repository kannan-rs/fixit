<div id="projectBudgetList">
	<table>
	<tr><td>Paid Date</td><td>Amount</td><td>Description</td><td>Action</td></tr>
	<?php
	for($i = 0; $i < count($budgetList); $i++) {
	?>
	<tr>
		<td class="cell"><?php echo $budgetList[$i]->date; ?></td>
		<td class="cell"><?php echo $budgetList[$i]->amount; ?></td>
		<td class="cell"><?php echo $budgetList[$i]->descr; ?></td>
		<td class="cell table-action">
			<span>
				<a  class="step fi-deleteRow size-21 red delete" href="javascript:void(0);" onclick="<?php echo "projectObj._remainingBudget.deleteRecord('".$budgetList[$i]->sno."')"; ?>" title="Delete Paid Budget"></a>
			</span>
		</td>
	</tr>
	<?php
	}
	?>
	</table>
</div>