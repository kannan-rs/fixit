<h2>Projects List</h3>
<div>
	<!-- List all the Functions from database -->
	<table cellspacing="0">
	
	<?php
		if(count($projects) > 0) {
	?>
			<tr class='heading'>
			<!--<td class='cell'>Sno</td>-->
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
			$deleteFn = $deleteText ? "projectObj._projects.delete(".$projects[$i]->proj_id.")" : "";
	?>
			<tr class='row viewAll'>
				<!--<td class='cell number'><?php echo ($i+1); ?></td>-->
				<td class='cell'>
					<a href="javascript:void(0);" onclick="projectObj._projects.viewOne('<?php echo $projects[$i]->proj_id; ?>')">
						<?php echo $projects[$i]->project_name; ?>
					</a>
				</td>
				<td class="cell percentage"><?php echo $projects[$i]->percentage; ?>%</td>
				<td class="cell date"><?php echo $projects[$i]->start_date; ?></td>
				<td class="cell dates"><?php echo $projects[$i]->end_date; ?></td>
				<td class='cell table-action'>
					<!--
					<span><a class="step fi-list-thumbnails size-21" href="javascript:void(0);" onclick="projectObj._tasks.viewAll('<?php echo $projects[$i]->proj_id; ?>')" title="Tasks"></a></span>
					<span><a class="step fi-clipboard-notes size-21" href="javascript:void(0);" onclick="projectObj._notes.viewAll('<?php echo $projects[$i]->proj_id; ?>')" title="Notes"></a></span>
					<span><a class="step fi-page-doc size-21" href="javascript:void(0);" onclick="projectObj._docs.viewAll('<?php echo $projects[$i]->proj_id; ?>')" title="Documents"></a></span>
					-->
				</td>
			</tr>
	<?php
		}
	?>
	</table>
</div>