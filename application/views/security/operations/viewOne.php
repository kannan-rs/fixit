<h2>View Operation Details</h3>
<div class="create-link"><a href="javascript:void(0);" onclick="securityObj._operations.createForm()">Create Operation</a></div>
<div>
	<!-- List all the operations from database -->
	<table>
	
	<?php
		if(count($operations) > 0) {
			$i = 0;
			echo "<tr>";
			echo "<td class='cell'>Operation ID</td>"."<td class='cell'>".$operations[$i]->ope_id."</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td class='cell'>Operation Name</td>"."<td class='cell'>". $operations[$i]->ope_name ."</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td class='cell'>Operation Description</td>"."<td class='cell'>". $operations[$i]->ope_desc ."</td>";
			echo "</tr>";
		}
	?>
	</table>
</div>