<h2>View Data Filter Details</h3>
<div class="create-link"><a href="javascript:void(0);" onclick="securityObj._dataFilters.createForm()">Create Data Filter</a></div>
<div>
	<!-- List all the Data Filters from database -->
	<table>
	
	<?php
		if(count($dataFilters) > 0) {
			$i = 0;
			echo "<tr>";
			echo "<td class='cell'>Data Filter ID</td>"."<td class='cell'>".$dataFilters[$i]->data_filter_id."</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td class='cell'>Data Filter Name</td>"."<td class='cell'>". $dataFilters[$i]->data_filter_name ."</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td class='cell'>Data Filter Description</td>"."<td class='cell'>". $dataFilters[$i]->data_filter_descr ."</td>";
			echo "</tr>";
		}
	?>
	</table>
</div>