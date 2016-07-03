<table cellspacing="0" class="viewOne projectViewOne">
	<tr>
		<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view_budget->project_budget'); ?></td>
		<td class='cell dollers'><div>$<?php echo number_format((float)$project->project_budget, 2, '.', ','); ?></div></td>
	</tr>
	<tr>
		<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view_budget->paid_from_budget'); ?></td>
		<td class='cell dollers'><div>$<?php echo number_format((float)$project->paid_from_budget, 2, '.', ','); ?></div></td>
	</tr>
	<tr>
		<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view_budget->remaining_budget'); ?></td>
		<td class='cell dollers'><div>$<?php echo number_format(((float)$project->project_budget - (float)$project->paid_from_budget), 2, '.', ','); ?></div></td>
	</tr>
	<tr>
		<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view_budget->deductible'); ?></td>
		<td class='cell dollers' ><div>$<?php echo number_format((float)$project->deductible, 2, '.', ','); ?></div></td>
	</tr>
	<?php
	if($userType == ROLE_ADMIN ) {
	?>
	<tr>
		<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view_budget->referral_fee'); ?></td>
		<td class='cell dollers' ><div>$<?php echo number_format(((((float)$project->project_budget - (float)$project->deductible)/100) * 7), 2, '.', ','); ?></div></td>
	</tr>
	<?php
	}
	?>
</table>