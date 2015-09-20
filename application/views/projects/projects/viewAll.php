<h2>List pf Projects</h2>
<div class="projects internal-tab-as-links" onclick="projectObj._projects.showProjectsList(event)">
	<a href="javascript:void(0);" data-option="open" title="Click on this button to view open/active projects">Open</a>
	<a href="javascript:void(0);" data-option="completed" title="Click on this button to view completed projects">Completed</a>
	<?php if($account_type == "admin") { ?>
	<a href="javascript:void(0);" data-option="deleted" title="Click on this button to view deleted projects">Deleted</a>
	<?php } ?>
	<a href="javascript:void(0);" data-option="issues" title="Click on this button to view projects with issues">Issues</a>
	<a href="javascript:void(0);" data-option="all" title="Click on this button to view all projects">All</a>
</div>
<div>
	<!-- List all the Functions from database -->
	<table cellspacing="0" class="projects-table-list">
	
	<?php
		if(count($projects) > 0) {
	?>
			<tr class='heading'>
			<td class='cell'>Project Name</td>
			<td class='cell'>% Complete</td>
			<td class='cell'>Start Date</td>
			<td class='cell'>End Date</td>
			<td class='cell'></td>
			</tr>
	<?php
		}

		for($i = 0; $i < count($projects); $i++) {
			$deleteText = "Delete";
			$deleteFn = $deleteText ? "projectObj._projects.deleteRecord(".$projects[$i]->proj_id.")" : "";
			
			$issueCount 	= $projects[$i]->issueCount;
			$issueFnOptions = "{'projectId' :".$projects[$i]->proj_id.", 'openAs' : 'popup', 'popupType' : '' }";

			$issueFn = "projectObj._issues.viewAll(".$issueFnOptions.")";

			$cssStatus = $projects[$i]->project_status == "completed" || $projects[$i]->percentage == "100" ? "completed" : "open";
			$cssStatus = $projects[$i]->deleted ? "deleted" : $cssStatus;
	?>
			<tr class='row viewAll <?php echo $cssStatus; ?> <?php echo $issueCount ? "issues" : ""; ?>'>
				<td class='cell'>
					<a href="javascript:void(0);" onclick="projectObj._projects.viewOne('<?php echo $projects[$i]->proj_id; ?>')">
						<?php echo $projects[$i]->project_name; ?>
					</a>
				</td>
				<td class="cell percentage"><?php echo $projects[$i]->percentage; ?>%</td>
				<td class="cell date"><?php echo $projects[$i]->start_date; ?></td>
				<td class="cell date"><?php echo $projects[$i]->end_date; ?></td>
				<td class='cell table-action'>
				<span>
				<?php if($issueCount) { ?>
					<a class="step fi-alert size-21 red" href="javascript:void(0);" onclick="<?php echo $issueFn; ?>" title="Project Issues"><span class="size-9"><?php echo $issueCount; ?></span></a>
				<?php } ?>
				</span>
				</td>
			</tr>
	<?php
		}
	?>
	</table>
</div>