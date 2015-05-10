<div class="create-link">
	<?php echo $internalLink; ?>
</div>
<?php echo $projectNameDescr; ?>
<div>
	<h2>Tasks List</h2>
	<!-- List all the Functions from database -->
	<table  cellspacing="0">
	
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
			echo "<tr class='row viewAll'>";
			echo "<td class='cell number'>".($i+1)."</td>";
			echo "<td class='cell'>";
			echo "<a href=\"javascript:void(0);\" onclick=\"projectObj._tasks.viewOne('".$tasks[$i]->task_id."')\">". $tasks[$i]->task_name;
			echo "</a></td>";
			echo "<td class='cell percentage'>". $tasks[$i]->task_percent_complete ."</td>";
			echo "<td class='cell date'>". $tasks[$i]->task_start_date_for_view ."</td>";
			echo "<td class='cell date'>". $tasks[$i]->task_end_date_for_view ."</td>";
			echo "<td class='cell table-action'>";
			echo "<span><a href=\"javascript:void(0);\" onclick=\"projectObj._tasks.editTask('".$tasks[$i]->task_id."')\">Edit</a></span>";
			echo "<span><a href=\"javascript:void(0);\" onclick=\"".$deleteFn."\">".$deleteText."</a></span></td>";
			echo "</tr>";
		}
	?>
	</table>
</div>