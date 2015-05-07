<h2>Projects List</h3>
<!--<div class="create-link"><a href="javascript:void(0);" onclick="projectObj._projects.createForm()">Create Project</a></div>-->
<div>
	<!-- List all the Functions from database -->
	<table>
	
	<?php
		if(count($projects) > 0) {
	?>
			<tr class='heading'>
			<td class='cell'>Sno</td>
			<td class='cell'>Project Name</td>
			<td class='cell'>% Complete</td>
			<td class='cell'>Start Date</td>
			<td class='cell'>End Date</td>
			<td class='cell'>Action</td>
			</tr>
	<?php
		}

		for($i = 0; $i < count($projects); $i++) { 
			$deleteText = "Delete";
			$deleteFn = $deleteText ? "projectObj._projects.delete(".$projects[$i]->proj_id.")" : "";
	?>
			<tr class='row'>
			<td class='cell'><?php echo ($i+1); ?></td>
			<td class='cell'>
				<a href="javascript:void(0);" onclick="projectObj._projects.viewOne('<?php echo $projects[$i]->proj_id; ?>')">
					<?php echo $projects[$i]->project_name; ?>
				</a>
			</td>
			<td><?php echo $projects[$i]->percentage; ?>%</td>
			<td><?php echo $projects[$i]->start_date; ?></td>
			<td><?php echo $projects[$i]->end_date; ?></td>
			<td class='cell table-action'>
			<span><a href="javascript:void(0);" onclick="projectObj._tasks.viewAll('<?php echo $projects[$i]->proj_id; ?>')">Tasks</a></span>
			<span><a href="javascript:void(0);" onclick="projectObj._notes.viewAll('<?php echo $projects[$i]->proj_id; ?>')">Notes</a></span>
			<span><a href="javascript:void(0);" onclick="projectObj._docs.viewAll('<?php echo $projects[$i]->proj_id; ?>')">Documents</a></span>
			<!--
			<span><a href="javascript:void(0);" onclick="projectObj._projects.editProject('<?php echo $projects[$i]->proj_id; ?>')">Edit</a></span>
			<span><a href="javascript:void(0);" onclick="<?php echo $deleteFn; ?>"> <?php echo $deleteText; ?></a></span></td>
			-->
			</tr>
	<?php
		}
	?>
	</table>
</div>