<div class="create-link">
	<?php echo $internalLink; ?>
</div>
<h2>Project Details</h2>
<div>
	<!-- List all the Functions from database -->
	<?php
	if(count($projects) > 0) {
		$i = 0;
	?>
	<table cellspacing="0" class="viewOne projectViewOne">
		<tr>
			<td class='cell label'>Project Title:</td>
			<td class='cell' ><?php echo $projects[$i]->project_name; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Project Start Date</td>
			<td class='cell' ><?php echo $projects[$i]->start_date; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Projected End Date</td>
			<td class='cell' ><?php echo $projects[$i]->end_date; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Budget</td>
			<td class='cell' >-- What is the calculation --</td>
		</tr>
	</table>
	<div id="accordion" class="accordion">
		<h3><span class="inner_accordion">Customer Details<span></h3>
		<div>
			<table cellspacing="0" class="viewOne projectViewOne">
				<tr>
					<td class='cell label'>First Name</td>
					<td class='cell' >-- Need to Take from customer --</td>
				</tr>
				<tr>
					<td class='cell label'>Last Name</td>
					<td class='cell' >-- Need to Take from customer --</td>
				</tr>
				<tr>
					<td class='cell label'>Address</td>
					<td class='cell' >-- Need to Take from customer --</td>
				</tr>
				<tr>
					<td class='cell label'>Phone</td>
					<td class='cell' >-- Need to Take from customer --</td>
				</tr>
				<tr>
					<td class='cell label'>Email</td>
					<td class='cell' >-- Need to Take from customer --</td>
				</tr>
			</table>
		</div>
		<h3><span class="inner_accordion">Insurance Details<span></h3>
		<div>
			<table cellspacing="0" class="viewOne projectViewOne">
				<tr>
					<td class='cell label'>Insurance Provider</td>
					<td class='cell' >-- Need to Take from Insirence Provider --</td>
				</tr>
				<tr>
					<td class='cell label'>Provider Address</td>
					<td class='cell' >-- Need to Take from Insirence Provider --</td>
				</tr>
				<tr>
					<td class='cell label'>Provider Phone</td>
					<td class='cell' >-- Need to Take from Insirence Provider --</td>
				</tr>
			</table>
		</div>
	</div>
	<table cellspacing="0" class="viewOne">
		<tr>
			<td class='cell label'>Contractor resources</td>
			<td class='cell' >-- Need to Take from Contractor --</td>
		</tr>
	</table>
	<!-- Project Description -->
	<h3>Project Description</h3>
	<table cellspacing="0" class="viewOne">
		<tr>
			<td class='cell'><?php echo $projects[$i]->project_descr; ?></td>
		</tr>
	</table>
	<!-- Task List -->
	<!--<h3>Task and Status<?php if(count($tasks)) { echo " (".count($tasks).")"; } ?></h3>
		<table  cellspacing="0">
	
	<?php
		if(count($tasks) > 0) {
			echo "<tr class='heading'>";
			//echo "<td class='cell'>Sno</td>";
			echo "<td class='cell'>Task Name</td>";
			echo "<td class='cell'>Description</td>";
			echo "<td class='cell'>Owner</td>";
			echo "<td class='cell'>% Complete</td>";
			echo "<td class='cell'>Start Date</td>";
			echo "<td class='cell'>End Date</td>";
			echo "</tr>";
		}

		for($i = 0; $i < count($tasks); $i++) { 
			echo "<tr class='row viewAll'>";
			//echo "<td class='cell number'>".($i+1)."</td>";
			echo "<td class='cell'>";
			echo "<a href=\"javascript:void(0);\" onclick=\"projectObj._tasks.viewOne('".$tasks[$i]->task_id."')\">";
			echo $tasks[$i]->task_name != "" ? $tasks[$i]->task_name : "--";
			echo "</a></td>";
			echo "<td class='cell'>".(($tasks[$i]->task_desc != "") ? $tasks[$i]->task_desc : '--')."</td>";
			echo "<td class='cell'>-- To be decided --</td>";
			echo "<td class='cell percentage'>". $tasks[$i]->task_percent_complete ."</td>";
			echo "<td class='cell date'>". $tasks[$i]->task_start_date_for_view ."</td>";
			echo "<td class='cell date'>". $tasks[$i]->task_end_date_for_view ."</td>";
			echo "</tr>";
		}
	?>
	</table>
	<h3>Project Notes<?php if(count($notes)) { echo " (".count($notes).")"; } ?></h3>
	<table cellspacing="0" class="viewOne">
	<?php
	for($i=0; $i < count($notes); $i++) {
	?>
		<tr>
			<td class='cell' colspan="3"><?php echo $notes[$i]->notes_content; ?> <i>Created By: <?php echo $notes[$i]->created_by_name; ?> on <?php echo $notes[$i]->created_date_for_view; ?></i></td>
		</tr>
	<?php
	}
	?>
	</table>
	-->
	<?php
	}
	?>
</div>