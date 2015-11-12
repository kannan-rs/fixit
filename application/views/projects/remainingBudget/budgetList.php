<div id="projectBudgetList">
	<table>
	<tr>
		<td class="cell"><?php echo $this->lang->line_arr('budget->summary_table->paid_date'); ?></td>
		<td class="cell percentage"><?php echo $this->lang->line_arr('budget->summary_table->amount'); ?></td>
		<td class="cell"><?php echo $this->lang->line_arr('budget->summary_table->description'); ?></td>
		<td class="cell"><?php echo $this->lang->line_arr('budget->summary_table->action'); ?></td>
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
					<a  class="step fi-page-edit size-21" href="javascript:void(0);" onclick="<?php echo "projectObj._remainingbudget.editRecordForm('".$budgetList[$i]->sno."')"; ?>" title="<?php echo $this->lang->line_arr('budget->buttons_links->edit_budget_title'); ?>"></a>
				</span>
			</span>
			<span>
				<a class="step fi-deleteRow size-21 red delete" href="javascript:void(0);" onclick="<?php echo "projectObj._remainingbudget.deleteRecord('".$budgetList[$i]->sno."')"; ?>" title="<?php echo $this->lang->line_arr('budget->buttons_links->delete_budget_title'); ?>"></a>
			</span>
		</td>
	</tr>
	<?php
	}
	?>
	</table>
</div>