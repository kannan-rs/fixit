<div class="create-link">
	<a href="javascript:void(0);" onclick="projectObj._notes.viewAll('<?php echo $projectId; ?>')">Notes</a>
	<a href="javascript:void(0);" onclick="projectObj._docs.viewAll('<?php echo $projectId; ?>')">Documents</a>
	<a href="javascript:void(0);" onclick="projectObj._tasks.createForm(<?php echo $projectId; ?>)">Create Task</a>
</div>
<div class="projectDetails">
	<div>Project Name: '<?php echo $projectName; ?>'</div>
	<div>Project Description : <?php echo $projectDescr; ?></div>
</div>
<div>
	<h2>Tasks List</h2>
	<!-- List all the Functions from database -->
	<table>
	
	<?php
		if(count($tasks) > 0) {
			echo "<tr class='heading'>";
			echo "<td class='cell'>Sno</td>";
			echo "<td class='cell'>Task Name</td>";
			echo "<td class='cell'>% Complete</td>";
			echo "<td class='cell'>Start Date</td>";
			echo "<td class='cell'>End Date</td>";
			echo "<td class='cell'>Action</td>";
			echo "</tr>";
		}

		for($i = 0; $i < count($tasks); $i++) { 
			$deleteText = "Delete";
			$deleteFn = $deleteText ? "projectObj._tasks.delete(".$tasks[$i]->task_id.",".$tasks[$i]->project_id.")" : "";
			echo "<tr class='row'>";
			echo "<td class='cell'>".($i+1)."</td>";
			echo "<td class='cell'>";
			echo "<a href=\"javascript:void(0);\" onclick=\"projectObj._tasks.viewOne('".$tasks[$i]->task_id."')\">". $tasks[$i]->task_name;
			echo "</a></td>";
			echo "<td>". $tasks[$i]->task_percent_complete ."</td>";
			echo "<td>". $tasks[$i]->task_start_date_for_view ."</td>";
			echo "<td>". $tasks[$i]->task_end_date_for_view ."</td>";
			echo "<td class='cell table-action'>";
			echo "<span><a href=\"javascript:void(0);\" onclick=\"projectObj._tasks.editTask('".$tasks[$i]->task_id."')\">Edit</a></span>";
			echo "<span><a href=\"javascript:void(0);\" onclick=\"".$deleteFn."\">".$deleteText."</a></span></td>";
			echo "</tr>";
		}
	?>
	</table>
</div>