<h2>View All Data Filters</h3>
<div class="create-link"><a href="javascript:void(0);" onclick="securityObj._dataFilters.createForm()">Create Data Filter</a></div>
<div>
	<!-- List all the Data Filters from database -->
	<table>
	
	<?php
		if(count($dataFilters) > 0) {
			echo "<tr class='heading'>";
			echo "<td class='cell'>Sno</td>";
			echo "<td class='cell'>Data Filter ID</td>";
			echo "<td class='cell'>Data Filter Name</td>";
			echo "<td class='cell'>Data Filter Descr</td>";
			echo "<td class='cell'>Action</td>";
			echo "</tr>";
		}

		for($i = 0; $i < count($dataFilters); $i++) { 
			$deleteText = "Delete";
			$deleteFn = $deleteText ? "securityObj._dataFilters.delete(".$dataFilters[$i]->sno.")" : "";
			echo "<tr class='row'>";
			echo "<td class='cell'>".($i+1)."</td>";
			echo "<td class='cell'>";
			echo "<a href=\"javascript:void(0);\" onclick=\"securityObj._dataFilters.viewOne('".$dataFilters[$i]->sno."')\">". $dataFilters[$i]->data_filter_id;
			echo "</td>";
			echo "<td class='cell'><a href=\"javascript:void(0);\" onclick=\"securityObj._dataFilters.viewOne('".$dataFilters[$i]->sno."')\">". $dataFilters[$i]->data_filter_name ."</td>";
			echo "<td>".$dataFilters[$i]->data_filter_descr."</td>";
			echo "<td class='cell table-action'>";
			echo "<span><a href=\"javascript:void(0);\" onclick=\"securityObj._dataFilters.editDataFilter('".$dataFilters[$i]->sno."')\">Edit</a></span>";
			echo "<span><a href=\"javascript:void(0);\" onclick=\"".$deleteFn."\">".$deleteText."</a></span></td>";
			echo "</tr>";
		}
	?>
	</table>
</div>