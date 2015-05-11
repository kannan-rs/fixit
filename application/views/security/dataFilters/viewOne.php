<div class="create-link"><a href="javascript:void(0);" onclick="securityObj._dataFilters.createForm()">Create Data Filter</a></div>
<h2>View Data Filter Details</h2>
<div>
	<!-- List all the Data Filters from database -->
	<table cellspacing="0">
	
	<?php
		if(count($dataFilters) > 0) {
			$i = 0;
			echo "<tr>";
			echo "<td class='cell label'>Data Filter ID</td>"."<td class='cell'>".$dataFilters[$i]->data_filter_id."</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td class='cell label'>Data Filter Name</td>"."<td class='cell'>". $dataFilters[$i]->data_filter_name ."</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td class='cell label'>Data Filter Description</td>"."<td class='cell'>". $dataFilters[$i]->data_filter_descr ."</td>";
			echo "</tr>";
		}
	?>
	</table>
</div>