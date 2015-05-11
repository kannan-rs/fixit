<div class="create-link"><a href="javascript:void(0);" onclick="securityObj._operations.createForm()">Create Operation</a></div>
<h2>View Operation Details</h2>
<div>
	<!-- List all the operations from database -->
	<table cellspacing="0">
	
	<?php
		if(count($operations) > 0) {
			$i = 0;
			echo "<tr>";
			echo "<td class='cell label'>Operation ID</td>"."<td class='cell'>".$operations[$i]->ope_id."</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td class='cell label'>Operation Name</td>"."<td class='cell'>". $operations[$i]->ope_name ."</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td class='cell label'>Operation Description</td>"."<td class='cell'>". $operations[$i]->ope_desc ."</td>";
			echo "</tr>";
		}
	?>
	</table>
</div>