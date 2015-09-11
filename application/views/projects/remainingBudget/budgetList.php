<div id="projectBudgetList">
	<table>
	<tr>
		<td class="cell">Paid Date</td>
		<td class="cell percentage">Amount</td>
		<td class="cell">Description</td>
		<td class="cell">Action</td>
	</tr>
	<?php
	for($i = 0; $i < count($budgetList); $i++) {
	?>
	<tr>
		<td class="cell"><?php echo $budgetList[$i]->date; ?></td>
		<td class="cell dollers"><div>$<?php echo number_format($budgetList[$i]->amount, 2, '.', ','); ?></div></td>
		<td class="cell"><?php echo $budgetList[$i]->descr; ?></td>
		<td class="cell table-action">
			<span>
				<span>
					<a  class="step fi-page-edit size-21" href="javascript:void(0);" onclick="<?php echo "projectObj._remainingbudget.editRecordForm('".$budgetList[$i]->sno."')"; ?>" title="Edit Paid Budget"></a>
				</span>
			</span>
			<span>
				<a class="step fi-deleteRow size-21 red delete" href="javascript:void(0);" onclick="<?php echo "projectObj._remainingbudget.deleteRecord('".$budgetList[$i]->sno."')"; ?>" title="Delete Paid Budget"></a>
			</span>
		</td>
	</tr>
	<?php
	}
	?>
	</table>
</div>