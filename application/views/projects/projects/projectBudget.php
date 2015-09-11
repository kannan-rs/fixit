<table cellspacing="0" class="viewOne projectViewOne">
	<tr>
		<td class='cell label'>Project Budget</td>
		<td class='cell dollers'><div>$<?php echo number_format($project->project_budget, 2, '.', ','); ?></div></td>
	</tr>
	<tr>
		<td class='cell label'>Paid from Budget</td>
		<td class='cell dollers'><div>$<?php echo number_format($project->paid_from_budget, 2, '.', ','); ?></div></td>
	</tr>
	<tr>
		<td class='cell label'>Remaining Bbudget</td>
		<td class='cell dollers'><div>$<?php echo number_format(($project->project_budget - $project->paid_from_budget), 2, '.', ','); ?></div></td>
	</tr>
	<tr>
		<td class='cell label'>Deductible</td>
		<td class='cell dollers' ><div>$<?php echo number_format($project->deductible, 2, '.', ','); ?></div></td>
	</tr>
	<?php
	if($userType == "admin") {
	?>
	<tr>
		<td class='cell label'>Referral Fee</td>
		<td class='cell dollers' ><div>$<?php echo number_format(((($project->project_budget - $project->deductible)/100) * 7), 2, '.', ','); ?></div></td>
	</tr>
	<?php
	}
	?>
</table>